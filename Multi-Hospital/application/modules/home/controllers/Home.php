<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        // ------------------------------------------------------------------
        // PRIV-001 REMEDIATION: Mandatory Authentication Guard.
        //
        // Without this check, any HTTP request to /home/* (including from
        // unauthenticated users and bots) reaches the constructor, and all
        // models below are loaded. The index() method then falls into the
        // !in_group('superadmin') branch (because unauthenticated sessions
        // return FALSE for any in_group check), which executes all hospital
        // financial and clinical DB queries without any auth requirement.
        //
        // $this->ion_auth->logged_in() checks session->userdata('identity').
        // If the session contains no identity (unauthenticated state), it
        // returns FALSE and we redirect immediately.
        //
        // exit() after redirect() is MANDATORY in CI3. redirect() outputs
        // a Location header but does NOT terminate PHP execution. Without
        // exit(), the remaining constructor body (model loading, etc.) and
        // any subsequent method call would still execute, negating the guard.
        // ------------------------------------------------------------------
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
            exit();
        }

        $this->load->model('finance/finance_model');
        $this->load->model('appointment/appointment_model');
        $this->load->model('notice/notice_model');
        $this->load->model('home_model');
        $this->load->model('patient/patient_model');
    }


    public function index()
    {
        $data = array();

        if (!$this->ion_auth->in_group(array('superadmin'))) {

            $date = date('d-m-Y');
            $clock_in = date('h:i A');
            $clock_out = "";
            $late = "";
            $halfday = "";
            $work_from = "";
            $details = array();
            $month = date('F', strtotime($date));
            $year = date('Y', strtotime($date));
            $day = explode('-', $date);

            $result = $this->db->get_where('attendance', array('staff' => $this->ion_auth->user()->row()->id, 'month' => $month, 'year' => $year))->row();
            if (!empty($result->details)) {
                $details = explode('#', $result->details);

                $detail = explode('_', $details[$day[0] - 1] ?? '');
            }

            $finalDetail = ($clock_in != '' ? $clock_in : 'NONE') . '_' . ($clock_out != '' ? $clock_out : 'NONE') . '_' . ($late == 'late' ? $late : 'NONE') . '_' . ($halfday == 'halfday' ? $halfday : 'NONE') . '_' . ($work_from == '' ? 'office' : $work_from);

            $details[$day[0] - 1] = $finalDetail;

            $detail = implode('#', $details);
            if (!empty($result->log)) {
                $logs = explode("_", $result->log);
                $checkAdded = "";

                if ($clock_in != '') {
                    $checkAdded = $logs[$day[0] - 1] ?? '';
                    $logs[$day[0] - 1] = 'yes';
                }

                $log = implode('_', $logs);
            } else {
                $log = '';
                $checkAdded = '';
            }


            $data = array(
                'log' => $log,
                'details' => $detail
            );
            if (!empty($result->id)) {
                if ($checkAdded != 'yes') {
                    $this->db->where('id', $result->id);
                    $this->db->update('attendance', $data);
                }
            }

            $data['sum'] = $this->home_model->getSum('gross_total', 'payment');
            $data['payments'] = $this->finance_model->getPayment();
            $data['notices'] = $this->notice_model->getNotice();
            $data['this_month'] = $this->finance_model->getThisMonth();
            $data['expenses'] = $this->finance_model->getExpense();

            if ($this->ion_auth->in_group(array('Doctor'))) {
                redirect('doctor/details');
            } else {
                $data['appointments'] = $this->appointment_model->getAppointment();
            }

            if ($this->ion_auth->in_group(array('Accountant', 'Receptionist'))) {
                redirect('finance/addPaymentView');
            }

            if ($this->ion_auth->in_group(array('Pharmacist'))) {
                redirect('finance/pharmacy/home');
            }

            if ($this->ion_auth->in_group(array('Patient'))) {
                redirect('patient/medicalHistory');
            }
            if ($this->ion_auth->in_group(array('Laboratorist'))) {
                redirect('lab');
            }


            if (!$this->ion_auth->in_group(array('Patient', 'Pharmacist', 'Accountant', 'Receptionist', 'Doctor'))) {
                $data['this_month']['payment'] = $this->finance_model->thisMonthPayment();
                $data['this_month']['expense'] = $this->finance_model->thisMonthExpense();
                $data['this_month']['appointment'] = $this->finance_model->thisMonthAppointment();

                $data['this_day']['payment'] = $this->finance_model->thisDayPayment();
                $data['this_day']['expense'] = $this->finance_model->thisDayExpense();
                $data['this_day']['appointment'] = $this->finance_model->thisDayAppointment();

                $data['this_year']['payment'] = $this->finance_model->thisYearPayment();
                $data['this_year']['expense'] = $this->finance_model->thisYearExpense();
                $data['this_year']['appointment'] = $this->finance_model->thisYearAppointment();

                $data['this_month']['appointment'] = $this->finance_model->thisMonthAppointment();
                $data['this_month']['appointment_treated'] = $this->finance_model->thisMonthAppointmentTreated();
                $data['this_month']['appointment_cancelled'] = $this->finance_model->thisMonthAppointmentCancelled();

                $data['this_year']['payment_per_month'] = $this->finance_model->getPaymentPerMonthThisYear();

                $data['this_year']['expense_per_month'] = $this->finance_model->getExpensePerMonthThisYear();

                $data['this_day']['deposit'] = $this->home_model->getTotalDepositToday();
                $data['this_month']['deposit'] = $this->home_model->getTotalDepositThisMonth();
                $data['this_year']['deposit'] = $this->home_model->getTotalDepositThisYear();

                $data['this_day']['due'] = ($data['this_day']['payment'] ?? 0) - ($data['this_day']['deposit'] ?? 0);
                $data['this_month']['due'] = ($data['this_month']['payment'] ?? 0) - ($data['this_month']['deposit'] ?? 0);
                $data['this_year']['due'] = ($data['this_year']['payment'] ?? 0) - ($data['this_year']['deposit'] ?? 0);

                $last_month_due = $this->home_model->getTotalDueLastMonth();
                $data['last_month_due'] = $last_month_due;



                if ($last_month_due > 0) {
                    $change_due = (($data['this_month']['due'] ?? 0) - $last_month_due);
                    $percentage_change_due = ($change_due / $last_month_due) * 100;
                } elseif (($data['this_month']['due'] ?? 0) > 0) {
                    $percentage_change_due = 100;
                } else {
                    $percentage_change_due = 0;
                }



                $data['percentage_change_bill'] = $this->home_model->billPercentageChangedFromLastMonth();
                $data['percentage_change_deposit'] = $this->home_model->depositPercentageChangedFromLastMonth();
                $data['percentage_change_due'] = number_format($percentage_change_due, 2, '.', ',');
                $data['percentage_change_expense'] = $this->home_model->expensePercentageChangedFromLastMonth();




                // Now stats starts
                $doctorsOnDuty = $this->home_model->countDoctorsOnDuty();
                $nursesOnDuty = $this->home_model->countNursesOnDuty();
                $pharmacistsOnDuty = $this->home_model->countPharmacistsOnDuty();
                $laboratoristsOnDuty = $this->home_model->countLaboratoristsOnDuty();
                $receptionistsOnDuty = $this->home_model->countReceptionistsOnDuty();
                $accountantsOnDuty = $this->home_model->countAccountantsOnDuty();
                $patientAdmitted = $this->home_model->countPatientsAdmitted();

                $data['doctorsOnDuty'] = $doctorsOnDuty;
                $data['nursesOnDuty'] = $nursesOnDuty;
                $data['pharmacistsOnDuty'] = $pharmacistsOnDuty;
                $data['laboratoristsOnDuty'] = $laboratoristsOnDuty;
                $data['receptionistsOnDuty'] = $receptionistsOnDuty;
                $data['accountantsOnDuty'] = $accountantsOnDuty;
                $data['patientAdmitted'] = $patientAdmitted;

                // Now stats ends



                // today stats starts

                $registeredToday = $this->home_model->countPatientsRegisteredToday();
                $admittedToday = $this->home_model->countPatientsAdmittedToday();
                $dischargedToday = $this->home_model->countPatientsDischargedToday();

                $data['registeredToday'] = $registeredToday;
                $data['admittedToday'] = $admittedToday;
                $data['dischargedToday'] = $dischargedToday;

                // today stats ends

                // this month stats starts

                $registeredThisMonth = $this->home_model->countPatientsRegisteredThisMonth();
                $admittedThisMonth = $this->home_model->countPatientsAdmittedThisMonth();
                $dischargedThisMonth = $this->home_model->countPatientsDischargedThisMonth();

                $data['registeredThisMonth'] = $registeredThisMonth;
                $data['admittedThisMonth'] = $admittedThisMonth;
                $data['dischargedThisMonth'] = $dischargedThisMonth;

                // this month stats ends


                // this year stats starts
                $registeredThisYear = $this->home_model->countPatientsRegisteredThisYear();
                $admittedThisYear = $this->home_model->countPatientsAdmittedThisYear();
                $dischargedThisYear = $this->home_model->countPatientsDischargedThisYear();
                $data['registeredThisYear'] = $registeredThisYear;
                $data['admittedThisYear'] = $admittedThisYear;
                $data['dischargedThisYear'] = $dischargedThisYear;
                // this year stats ends




                // bed category wise occupancy number. 
                // Table names = bed, bed_category and alloted_bed
                // final loop should be on categories becaue we need to show the total bed number and ocuupency and availibilty of each category

                $bedCategories = $this->home_model->getBedCategories();
                $beds = $this->home_model->getBeds();
                $allotedBeds = $this->home_model->getAllotedBeds();


                $bedCategoryWiseOccupancy = array();
                foreach ($bedCategories as $bedCategory) {
                    $bedCategoryWiseOccupancy[$bedCategory->category] = array(
                        'total' => 0,
                        'occupied' => 0,
                        'available' => 0
                    );
                }

                foreach ($beds as $bed) {
                    $bedCategoryWiseOccupancy[$bed->category]['total']++;
                }

                foreach ($allotedBeds as $allotedBed) {
                    $bedCategoryWiseOccupancy[$allotedBed->category]['occupied']++;
                }

                foreach ($bedCategoryWiseOccupancy as $key => $value) {
                    $bedCategoryWiseOccupancy[$key]['available'] = $bedCategoryWiseOccupancy[$key]['total'] - $bedCategoryWiseOccupancy[$key]['occupied'];
                }

                $data['bedCategoryWiseOccupancy'] = $bedCategoryWiseOccupancy;


                // bed category wise occupancy number ends


                // Top Diagnoses and Treatments starts

                $diagnoses = $this->home_model->getDiagnosis();

                foreach ($diagnoses as $diagnosis) {
                    $diagnoses_number[$diagnosis->id] = $this->home_model->getCaseNumberByDiagnosisLastThirtyDays($diagnosis->id);
                }

                $treatments = $this->home_model->getTreatment();

                foreach ($treatments as $treatment) {
                    $treatments_number[$treatment->id] = $this->home_model->getCaseNumberByTreatmentLastThirtyDeays($treatment->id);
                }

                if (!empty($diagnoses_number)) {
                    arsort($diagnoses_number);
                    $diagnoses_number = array_slice($diagnoses_number, 0, 10, true);
                    $data['topDiagnoses'] = $diagnoses_number;
                }

                if (!empty($treatments_number)) {
                    arsort($treatments_number);
                    $treatments_number = array_slice($treatments_number, 0, 10, true);
                    $data['topTreatments'] = $treatments_number;
                }
                // Top Diagnoses and Treatments ends






                // diseas with outbrerak potential starts
                $data['diseasesWithOutbreakPotential'] = $this->home_model->getDiseasesWithOutbreakPotential() ?? [];
                $diseases = $this->home_model->getDiseasesWithOutbreakPotential() ?? [];

                $data['cases'] = []; // Initialize the array for storing case ratios

                if (is_array($diseases) && !empty($diseases)) {
                    foreach ($diseases as $disease) {
                        $expected_patients = $disease->maximum_expected_number_of_patient_in_a_week ?? 0;
                        $number_of_cases = $this->home_model->getCasesNumberForOutbreakDisease($disease->id) ?? 0;

                        // Ensure that $expected_patients is not zero to avoid division by zero
                        if ($expected_patients > 0) {
                            $ratio = ($number_of_cases / $expected_patients);
                        } else {
                            $ratio = 0; // If $expected_patients is zero, set ratio to 0
                        }

                        $data['cases'][$disease->id] = $ratio;
                    }
                }

                if (is_array($data['cases']) && !empty($data['cases'])) {
                    arsort($data['cases']); // Sort cases by ratio in descending order
                    $data['cases'] = array_slice($data['cases'], 0, 10, true); // Get the top 10 ratios
                }


                // diseas with outbrerak potential ends


                // Top Revenue Generating Services starts
                $payment_categories = $this->home_model->getPaymentCategories();
                $payments = $this->home_model->getPaymentLastThirtyDays();

                $category_total = array();
                foreach ($payment_categories as $cat_name) {
                    foreach ($payments as $payment) {
                        $categories_in_payment = explode(',', $payment->category_name);
                        foreach ($categories_in_payment as $key => $category_in_payment) {
                            $category_id = explode('*', $category_in_payment);
                            if ($category_id[0] == $cat_name->id) {
                                $category_total[$cat_name->id] = ($category_total[$cat_name->id] ?? 0) + ($category_id[1] ?? 0) * ($category_id[3] ?? 0);
                            }
                        }
                    }
                }

                arsort($category_total);
                // get 10 first 10 elemts of the array
                $category_total = array_slice($category_total, 0, 10, true);

                $data['topRevenueGeneratingServices'] = $category_total;

                // Top Revenue Generating Services ends

                // Low Stock of Key Medications starts
                $data['lowStockMedicines'] = $this->home_model->getMedicineByStockAlert();
                // Low Stock of Key Medications ends



                $data['settings'] = $this->settings_model->getSettings();
                $this->load->view('dashboard'); // just the header file
                $this->load->view('home', $data);
                $this->load->view('footer', $data);
            }
        } else {
            $data['hospitals'] = $this->hospital_model->getHospital();
            $data['this_month']['payment'] = $this->hospital_model->thisMonthlyDepositCount();
            $data['this_yearly']['payment'] = $this->hospital_model->thisYearlyDepositCount();
            $data['this_year']['payment_per_month'] = $this->hospital_model->getPaymentPerMonthThisYear();
            $data['this_monthly']['payment'] = $this->hospital_model->thisMonthlyDeposit();
            $data['this_year']['payment'] = $this->hospital_model->thisYearlyDeposit();
            $data['this_day']['payment'] = $this->hospital_model->thisDayMonthlyPayment();
            $data['this_day']['payment_yearly'] = $this->hospital_model->thisDayYearlyPayment();
            $data['this_year_payment']['payment'] = $this->hospital_model->thisYearYearlyPayment();
            $data['this_month_payment']['payment'] = $this->hospital_model->thisYearMonthlyPayment();
            $data['hospitals'] = $this->hospital_model->getHospital();
            $data['settings'] = $this->settings_model->getSettings();
            $this->load->view('dashboard'); // just the header file
            $this->load->view('home', $data);
            $this->load->view('footer');
        }
    }


    public function permission()
    {
        $this->load->view('permission');
    }

    function updateSidebarState()
    {
        $userId = $this->ion_auth->get_user_id(); // Get the current user ID
        $sidebarValue = $this->input->post('sidebar'); // Get the sidebar value from the POST data
        $data = array(
            'sidebar' => $sidebarValue
        );
        $this->home_model->updateUser($userId, $data);
    }

    function updateDarkMode()
    {
        $darkModeValue = $this->input->post('darkMode'); // Get the dark mode value from the POST data
        $this->session->set_userdata('darkMode', $darkModeValue);
    }



    function businessChart()
    {
        $this->load->view('business_chart');
    }

    function modernDashboard()    
    {
        $this->load->view('modern_dashboard');
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
