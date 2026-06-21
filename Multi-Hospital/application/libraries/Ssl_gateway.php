<?php
defined('BASEPATH') OR exit('No direct script access allowed');

interface Sms_driver_interface {
    public function send($to, $message);
}

class Twilio_driver implements Sms_driver_interface {
    protected $sid;
    protected $token;
    protected $sender;

    public function __construct($config) {
        $this->sid = $config->sid ?? '';
        $this->token = $config->token ?? '';
        $this->sender = $config->sendernumber ?? '';
    }

    public function send($to, $message) {
        if (empty($this->sid) || empty($this->token) || empty($this->sender)) {
            log_message('error', 'Twilio parameters missing.');
            return false;
        }
        try {
            $client = new \Twilio\Rest\Client($this->sid, $this->token);
            $client->messages->create($to, array(
                'from' => $this->sender,
                'body' => $message
            ));
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Twilio Send Error: ' . $e->getMessage());
            return false;
        }
    }
}

class Local_bd_driver implements Sms_driver_interface {
    protected $authkey;
    protected $sender;
    protected $api_url;

    public function __construct($config) {
        // Safe mapping to dedicated LOCAL_SMS database row credentials 
        $this->authkey = $config->authkey ?? ''; 
        $this->sender = $config->sender ?? '';
        $this->api_url = "https://api.greenweb.com.bd/api.php";
    }

    public function send($to, $message) {
        if (empty($this->authkey)) {
            log_message('error', 'Local BD Gateway Token/Authkey is not configured.');
            return false;
        }

        $postdata = array(
            'token' => $this->authkey,
            'to' => $to,
            'message' => $message
        );

        if (!empty($this->sender)) {
            $postdata['queue'] = $this->sender;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Strict SSL verification for remote BD gateway
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            log_message('error', 'Local BD SMS HTTP Request failed: ' . $curl_error);
            return false;
        }

        return true;
    }
}

class Ssl_gateway {
    protected $CI;
    protected $driver;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('sms/sms_model');
        $this->CI->load->model('settings/settings_model');
        
        $this->initialize_driver();
    }

    private function initialize_driver() {
        $gateway_name = $this->CI->settings_model->getSettings()->sms_gateway;
        
        if (empty($gateway_name)) {
            log_message('error', 'No SMS Gateway selected in system settings.');
            return;
        }

        $config = $this->CI->sms_model->getSmsSettingsByGatewayName($gateway_name);
        
        if (!$config) {
            log_message('error', 'SMS configuration parameters not found for: ' . $gateway_name);
            return;
        }

        switch (strtolower($gateway_name)) {
            case 'twilio':
                $this->driver = new Twilio_driver($config);
                break;
            case 'local_sms':
            case 'greenweb':
                $this->driver = new Local_bd_driver($config);
                break;
            default:
                log_message('error', 'Unsupported gateway configuration: ' . $gateway_name);
                break;
        }
    }

    public function send_sms($to, $message) {
        if (!$this->driver) {
            log_message('error', 'Ssl_gateway SMS driver is not initialized.');
            return false;
        }
        return $this->driver->send($to, $message);
    }
}
