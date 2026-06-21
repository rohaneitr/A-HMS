<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sslcommerz extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('finance/finance_model');
        $this->load->model('pgateway/pgateway_model');
        $this->load->model('patient/patient_model');
        $this->load->library('session');
    }

    /**
     * Initiate SSLCOMMERZ Payment Request
     */
    public function initiate_payment() {
        $amount = $this->input->get('deposited_amount');
        $payment_id = $this->input->get('payment_id');
        $patient_id = $this->input->get('patient');
        $redirect_type = $this->input->get('redirect'); // e.g., '0save', '0saveandprint', '1deposit', '1due', '2'
        
        if (empty($amount) || empty($payment_id) || empty($patient_id)) {
            show_error('Invalid payment parameters.');
        }

        // Retrieve gateway credentials
        $ssl_settings = $this->pgateway_model->getPaymentGatewaySettingsByName('SSLCOMMERZ');
        if (!$ssl_settings) {
            show_error('SSLCOMMERZ gateway settings not configured.');
        }

        $store_id = $ssl_settings->APIUsername; 
        $store_passwd = $ssl_settings->APIPassword; 
        $sandbox = ($ssl_settings->status == 'test');

        // Fetch patient information
        $patient = $this->patient_model->getPatientById($patient_id);
        if (!$patient) {
            show_error('Patient record not found.');
        }

        // Generate uniquely identifiable transaction code
        $tran_id = 'SSLC_' . uniqid() . '_' . rand(1000, 9999);

        // Success / Fail / Cancel redirect parameters containing fallback tran_id
        $success_url = base_url() . 'sslcommerz/success?tran_id=' . $tran_id;
        $fail_url = base_url() . 'sslcommerz/fail?tran_id=' . $tran_id;
        $cancel_url = base_url() . 'sslcommerz/cancel?tran_id=' . $tran_id;

        // Persist transaction state in database before redirecting (SameSite cookie drop fallback)
        $state_data = array(
            'tran_id' => $tran_id,
            'patient_id' => $patient_id,
            'payment_id' => $payment_id,
            'amount' => $amount,
            'user_id' => $this->session->userdata('user_id') ? $this->session->userdata('user_id') : $this->ion_auth->get_user_id(),
            'hospital_id' => $this->session->userdata('hospital_id'),
            'redirect_link' => $redirect_type,
            'status' => 'pending'
        );
        $this->db->insert('sslcommerz_payments_state', $state_data);

        // Build Payload
        $post_data = array();
        $post_data['store_id'] = $store_id;
        $post_data['store_passwd'] = $store_passwd;
        $post_data['total_amount'] = $amount;
        $post_data['currency'] = 'BDT';
        $post_data['tran_id'] = $tran_id;
        $post_data['success_url'] = $success_url;
        $post_data['fail_url'] = $fail_url;
        $post_data['cancel_url'] = $cancel_url;

        # CUSTOMER INFO
        $post_data['cus_name'] = !empty($patient->name) ? $patient->name : 'Patient';
        $post_data['cus_email'] = !empty($patient->email) ? $patient->email : 'no-email@hospital.com';
        $post_data['cus_add1'] = !empty($patient->address) ? $patient->address : 'Dhaka';
        $post_data['cus_city'] = 'Dhaka';
        $post_data['cus_country'] = 'Bangladesh';
        $post_data['cus_phone'] = !empty($patient->phone) ? $patient->phone : '01700000000';

        # SHIPMENT & PRODUCT DETAILS
        $post_data['shipping_method'] = 'NO';
        $post_data['num_of_item'] = '1';
        $post_data['product_name'] = 'Medical Services';
        $post_data['product_category'] = 'Healthcare';
        $post_data['product_profile'] = 'non-physical-goods';

        // Select endpoint
        $direct_api_url = $sandbox 
            ? "https://sandbox.sslcommerz.com/gwprocess/v4/api.php" 
            : "https://securepay.sslcommerz.com/gwprocess/v4/api.php";

        // Dispatch POST request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $direct_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // cURL SSL Configuration Checks
        if ($sandbox) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        }

        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            show_error('cURL Error: ' . $curl_error);
        }

        $sslcz = json_decode($response, true);

        if (isset($sslcz['status']) && $sslcz['status'] == 'SUCCESS' && !empty($sslcz['GatewayPageURL'])) {
            header("Location: " . $sslcz['GatewayPageURL']);
            exit;
        } else {
            show_error("SSLCOMMERZ API Error: " . (isset($sslcz['failedreason']) ? $sslcz['failedreason'] : 'Unknown error'));
        }
    }

    /**
     * Success Redirection Validation Endpoint
     */
    public function success() {
        $tran_id = $this->input->get('tran_id');
        $val_id = $this->input->post('val_id'); // From SSLCOMMERZ POST callback

        if (empty($tran_id)) {
            $tran_id = $this->input->post('tran_id');
        }

        if (empty($tran_id)) {
            show_error('Transaction Token (tran_id) is missing.');
        }

        // Fetch transaction context from state log (survive SameSite session drop)
        $state = $this->db->get_where('sslcommerz_payments_state', array('tran_id' => $tran_id))->row();
        if (!$state) {
            show_error('Transaction state lookup failed.');
        }

        // Avoid duplicate callback execution if already resolved
        if ($state->status === 'success') {
            $this->redirect_user($state);
            return;
        }

        // Validation payload matching settings
        $ssl_settings = $this->pgateway_model->getPaymentGatewaySettingsByName('SSLCOMMERZ');
        $store_id = $ssl_settings->APIUsername;
        $store_passwd = $ssl_settings->APIPassword;
        $sandbox = ($ssl_settings->status == 'test');

        $validation_url = $sandbox 
            ? "https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php"
            : "https://securepay.sslcommerz.com/validator/api/validationserverAPI.php";

        $validation_url .= "?val_id=" . $val_id . "&store_id=" . urlencode($store_id) . "&store_passwd=" . urlencode($store_passwd) . "&v=1&format=json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $validation_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // cURL Verification Checks
        if ($sandbox) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['status']) && ($result['status'] == 'VALID' || $result['status'] == 'VALIDATED')) {
            // Atomic status check guard to prevent concurrency double execution
            $this->db->where(array('tran_id' => $tran_id, 'status' => 'pending'));
            $this->db->update('sslcommerz_payments_state', array('status' => 'success'));

            if ($this->db->affected_rows() > 0) {
                // Thread won lock, write to ledger
                $this->complete_invoice($state);
                $this->session->set_flashdata('feedback', 'Payment Completed Successfully');
                $this->redirect_user($state);
            } else {
                // Safely fetch state update result
                $latest_state = $this->db->get_where('sslcommerz_payments_state', array('tran_id' => $tran_id))->row();
                if ($latest_state && $latest_state->status === 'success') {
                    if ($this->input->is_ajax_request() || $_SERVER['REQUEST_METHOD'] === 'POST') {
                        echo "Already processed.";
                        return;
                    }
                    $this->session->set_flashdata('feedback', 'Payment already processed successfully.');
                    $this->redirect_user($latest_state);
                } else {
                    show_error('Transaction already processed or failed.');
                }
            }
        } else {
            // Update state status to failed
            $this->db->where(array('tran_id' => $tran_id, 'status' => 'pending'));
            $this->db->update('sslcommerz_payments_state', array('status' => 'failed'));

            $this->session->set_flashdata('feedback', 'Transaction validation failed.');
            $this->redirect_user($state);
        }
    }

    /**
     * Fail Endpoint
     */
    public function fail() {
        $tran_id = $this->input->get('tran_id');
        if (empty($tran_id)) {
            $tran_id = $this->input->post('tran_id');
        }

        $state = $this->db->get_where('sslcommerz_payments_state', array('tran_id' => $tran_id))->row();
        if ($state) {
            $this->db->where(array('tran_id' => $tran_id, 'status' => 'pending'));
            $this->db->update('sslcommerz_payments_state', array('status' => 'failed'));
            $this->session->set_flashdata('feedback', 'Payment Failed!');
            $this->redirect_user($state);
        } else {
            show_error('Invalid transaction reference.');
        }
    }

    /**
     * Cancel Endpoint
     */
    public function cancel() {
        $tran_id = $this->input->get('tran_id');
        if (empty($tran_id)) {
            $tran_id = $this->input->post('tran_id');
        }

        $state = $this->db->get_where('sslcommerz_payments_state', array('tran_id' => $tran_id))->row();
        if ($state) {
            $this->db->where(array('tran_id' => $tran_id, 'status' => 'pending'));
            $this->db->update('sslcommerz_payments_state', array('status' => 'cancelled'));
            $this->session->set_flashdata('feedback', 'Payment Cancelled.');
            $this->redirect_user($state);
        } else {
            show_error('Invalid transaction reference.');
        }
    }

    /**
     * IPN Webhook Endpoint (M2M Callback)
     */
    public function ipn() {
        $tran_id = $this->input->post('tran_id');
        $val_id = $this->input->post('val_id');

        if (empty($tran_id) || empty($val_id)) {
            return;
        }

        $state = $this->db->get_where('sslcommerz_payments_state', array('tran_id' => $tran_id))->row();
        if (!$state || $state->status === 'success') {
            return; 
        }

        $ssl_settings = $this->pgateway_model->getPaymentGatewaySettingsByName('SSLCOMMERZ');
        $store_id = $ssl_settings->APIUsername;
        $store_passwd = $ssl_settings->APIPassword;
        $sandbox = ($ssl_settings->status == 'test');

        $validation_url = $sandbox 
            ? "https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php"
            : "https://securepay.sslcommerz.com/validator/api/validationserverAPI.php";

        $validation_url .= "?val_id=" . $val_id . "&store_id=" . urlencode($store_id) . "&store_passwd=" . urlencode($store_passwd) . "&v=1&format=json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $validation_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($sandbox) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['status']) && ($result['status'] == 'VALID' || $result['status'] == 'VALIDATED')) {
            // Atomic status check guard to prevent concurrency double execution
            $this->db->where(array('tran_id' => $tran_id, 'status' => 'pending'));
            $this->db->update('sslcommerz_payments_state', array('status' => 'success'));

            if ($this->db->affected_rows() > 0) {
                // Thread won lock, write to ledger
                $this->complete_invoice($state);
                echo "Transaction successfully validated and logged.";
            } else {
                echo "Already processed.";
            }
        } else {
            echo "Failed validation.";
        }
    }

    /**
     * Complete invoice structure reconciliation
     */
    private function complete_invoice($state) {
        $date = time();
        $redirect = $state->redirect_link;

        // Restore context variables to Session wrapper
        if (!$this->session->userdata('hospital_id')) {
            $this->session->set_userdata('hospital_id', $state->hospital_id);
        }

        // Complete billing depending on redirection trigger
        if (strpos($redirect, '0') === 0) {
            $data1 = array(
                'date' => $date,
                'patient' => $state->patient_id,
                'deposited_amount' => $state->amount,
                'payment_id' => $state->payment_id,
                'amount_received_id' => $state->payment_id . '.gp',
                'gateway' => 'SSLCOMMERZ',
                'deposit_type' => 'Card',
                'user' => $state->user_id,
                'hospital_id' => $state->hospital_id
            );
            $this->finance_model->insertDeposit($data1);

            $data_payment = array(
                'amount_received' => $state->amount, 
                'deposit_type' => 'Card'
            );
            $this->finance_model->updatePayment($state->payment_id, $data_payment);
        } else {
            $data1 = array(
                'date' => $date,
                'patient' => $state->patient_id,
                'payment_id' => $state->payment_id,
                'deposited_amount' => $state->amount,
                'deposit_type' => 'Card',
                'gateway' => 'SSLCOMMERZ',
                'user' => $state->user_id,
                'hospital_id' => $state->hospital_id
            );
            $this->finance_model->insertDeposit($data1);
        }
    }

    /**
     * Context-aware UI Redirection Routing
     */
    private function redirect_user($state) {
        $redirect = $state->redirect_link;

        if (!$this->session->userdata('hospital_id')) {
            $this->session->set_userdata('hospital_id', $state->hospital_id);
        }

        if ($redirect == '0save') {
            redirect('finance/invoice?id=' . $state->payment_id);
        } elseif ($redirect == '0saveandprint') {
            redirect('finance/printInvoice?id=' . $state->payment_id);
        } elseif ($redirect == '1deposit') {
            redirect('finance/patientPaymentHistory?patient=' . $state->patient_id);
        } elseif ($redirect == '1due') {
            redirect('finance/invoice?id=' . $state->payment_id);
        } elseif ($redirect == '2') {
            redirect('patient/myPaymentHistory');
        } else {
            if ($this->ion_auth->in_group(array('Patient'))) {
                redirect('patient/myPaymentHistory');
            } else {
                redirect('finance/patientPaymentHistory?patient=' . $state->patient_id);
            }
        }
    }
}
