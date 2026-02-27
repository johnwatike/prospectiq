<?php

defined('BASEPATH') or exit('No direct script access allowed');
set_time_limit(0);
 // Set the response header
//  header('Referrer-Policy: no-referrer-when-downgrade');
class Phone extends AdminController{
    
 public function __construct()
    {
        parent::__construct();
        $this->load->model('reminder_model');
        $this->load->model('currencies_model');
        $this->loadSettings();
    }

    // Settings loaded from database
    private $apiKey;
    private $username;
    private $phoneNumber;
    private $lastRegisteredClient;

    private function loadSettings() {
        $this->apiKey = get_option('phone_api_key');
        $this->username = get_option('phone_username');
        $this->phoneNumber = get_option('phone_number');
        $this->lastRegisteredClient = $this->username . '.browser1';
    }

    public function index() {
        
        $data['title'] = _l('phone');
        $CI =& get_instance();
        $branch_id =  $CI->session->userdata('branch_id');//isset($_GET['name']) ? $_GET['name'] : '';
        $user_id = $CI->session->userdata('staff_user_id');

// Use the $user_id as needed
// echo "User ID: " . $user_id;
        $data['name'] = $branch_id.".".$user_id;
// $this->app_scripts->add('surveys-js', module_dir_url('phone', 'assets/js/index.js'), 'admin', ['app-js']);
// $this->app_css->add('surveys-css', module_dir_url('phone', 'assets/css/surveys.css'), 'admin', ['app-css']);

        $this->load->view('phone/dialer', $data);
    }
    public function phone_history($reminder_id = ''){
    //   if (!has_permission('reminder', '', 'view') && !has_permission('reminder', '', 'view_own')) {
    //         access_denied('reminder');
    //     }
    // Enable error reporting
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
        $this->load->model('clients_model');
        $data['clients']    = $this->reminder_model->getCustomersData();
        $data['customers']    = $this->clients_model->get();
        $data['staff']         = $this->staff_model->get('', ['active' => 1]);
        $data['reminderid']           = $reminder_id;
        $data['title']                 = _l('Call History');
        $data['reminder_sale_agents']  = $this->reminder_model->get_sale_agents();
        $data['years']                 = $this->reminder_model->get_reminder_years();
        $data['statuses']              = $this->reminder_model->get_statuses();
        $data['created_ids']              = $this->reminder_model->get_created_by_ids();

        $this->load->view('phone/call_history', $data);
    }
    public function dialer() {
        $CI =& get_instance();
        $CI->session->userdata('branch_id');
        $data['title'] = _l('phone');
        $name =  $CI->session->userdata('branch_id');//isset($_GET['name']) ? $_GET['name'] : '';
        $phonenumber = isset($_GET['phonenumber']) ? $_GET['phonenumber'] : '';
        $call_log_id = isset($_GET['call_log_id']) ? $_GET['call_log_id'] : '';
        $data['name'] = $name;
        $data['phonenumber'] = $phonenumber;
        $data['call_log_id'] = $call_log_id;

        $this->load->view('phone/dialer_phone', $data);
    }
public function table()
    {
        if (
            !has_permission('reminder', '', 'view')
            && !has_permission('reminder', '', 'view_own')
        ) {
            ajax_access_denied();
        }

        $this->app->get_table_data(module_views_path(PHONE_MODULE_NAME, 'tables/call_history'));
        $this->app->get_table_data('call_history');
    }
    public function mico_dialer() {
        $this->load->database();
         $CI =& get_instance();
       // $CI->session->userdata('branch_id');
        // ?name=john&phonenumber=0726339982&call_log_id=1234&branch_id=1
        $data['title'] = _l('phone');
        
        $branch_id =  $CI->session->userdata('branch_id');//isset($_GET['name']) ? $_GET['name'] : '';
        $user_id = $CI->session->userdata('staff_user_id');

// Use the $user_id as needed
// echo "User ID: " . $user_id;
        $data['name'] = $branch_id.".".$user_id;
        $name = $branch_id.".".$user_id;// $CI->session->userdata('branch_id');//isset($_GET['name']) ? $_GET['name'] : '';
        $phonenumber = isset($_GET['phonenumber']) ? $_GET['phonenumber'] : '';
        $call_log_id = isset($_GET['call_log_id']) ? $_GET['call_log_id'] : '';
        // $branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : '';
       
        $last_10_digits = substr($phonenumber, -9);

        $this->db->where('phonenumber', $last_10_digits);
        $query = $this->db->get('customer_phone_branch');

        if ($query->num_rows() > 0) {
            // Phone number exists, perform an update
            $datax = array(
                'branch_id' => $branch_id
            );
        
            $this->db->where('phonenumber', $last_10_digits);
            $this->db->update('customer_phone_branch', $datax);
        } else {
            // Phone number does not exist, perform an insert
            $datax = array(
                'phonenumber' => $last_10_digits,
                'branch_id' => $branch_id
            );
        
            $this->db->insert('customer_phone_branch', $datax);
        }

        $data['name'] = $branch_id.".".$user_id;
        $data['phonenumber'] = $phonenumber;
        $data['call_log_id'] = $call_log_id;
        $this->load->view('phone/micro_dialer_phone', $data);
    }

    // used by browser to fetch token.
    // the token is needed by the browser to initialize session
    public function capability_token_() {
        // Set the CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

       
        $params = array(
            'clientName' => $this->input->post('clientName')
        );

        $token = $this->capabilityToken($params);

        if ($token) {
            // $this->output->set_output($token);
            echo json_encode($token);
            // return json_encode($token);
            // echo json_encode($token);
        } else {
            $this->output->set_status_header(500);
            $this->output->set_output('Error generating capability token');
        }
    }

    // make sure to add this route as your callbck url from the africastalking dashboard
    public function callback_ur_l() {
         // Set the response header
    header('Referrer-Policy: no-referrer-when-downgrade');
        $clientDialedNumber = $this->input->post('clientDialedNumber');

        if ($clientDialedNumber) {
            // assumes a browser tried to make a call
            $callActions = '<Dial phoneNumbers="' . $clientDialedNumber . '"/>';
        } else {
            // assumes virtual number was called so tries to route call to the last browser session
            $callActions = '<Dial phoneNumbers="' . $this->lastRegisteredClient . '"/>';   
        }

        $responseAction = '<?xml version="1.0" encoding="UTF-8"?><Response>' . $callActions . '</Response>';

        $this->output->set_output($responseAction);
    }

    // make sure to add this route as your events callbck url from the africastalking dashboard. 
    // You can use this to monitor your events
    public function events_url_() {
        $events = $this->input->post('events');
        log_message('info', print_r(array('events' => $events), true));
    }

    /////////////////////////////////////utility functions///////////////////////////////////////////

    private function capabilityToken_($params) {
        $token = null;
        $url = 'https://webrtc.africastalking.com/capability-token/request';
        $data = array(
            'username' => $this->username,
            'phoneNumber' => $this->phoneNumber,
            'clientName' => isset($params['clientName']) ? $params['clientName'] : 'browser1',
            'incoming' => 'true',
            'outgoing' => 'true',
            'expire' => '7200s'
        );

        if ($data['username'] && $data['clientName'] && $data['phoneNumber']) {
            // add API key here
            $headers = array(
                'APIKEY: ' . $this->apiKey,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($result, true);

            if (isset($response['token'])) {
                $token = $response;
            }
        }

        return $token;
    }
    // used by browser to fetch token.
    // the token is needed by the browser to initialize session
    public function capability_token() {
        // Set the CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');

        $clientName = isset($_GET['clientName']) ? $_GET['clientName'] : '';
        $params = array(
            'clientName' => $clientName,
        );

        $token = $this->capabilityToken($params);

        if ($token) {
            // $this->output->set_output($token);
            echo json_encode($token);
            // return json_encode($token);
            // echo json_encode($token);
        } else {
            $this->output->set_status_header(500);
            $this->output->set_output('Error generating capability token');
        }
    }

    // make sure to add this route as your callbck url from the africastalking dashboard
    public function callback_url() {
        // Set the response header
        header('Referrer-Policy: no-referrer-when-downgrade');
        $clientDialedNumber = $this->input->post('clientDialedNumber');

        if ($clientDialedNumber) {
            // assumes a browser tried to make a call
            $callActions = '<Dial phoneNumbers="' . $clientDialedNumber . '"/>';
        } else {
            // assumes virtual number was called so tries to route call to the last browser session
            $callActions = '<Dial phoneNumbers="' . $this->lastRegisteredClient . '"/>';
        }

        $responseAction = '<?xml version="1.0" encoding="UTF-8"?><Response>' . $callActions . '</Response>';

        $this->output->set_output($responseAction);
    }

    // make sure to add this route as your events callbck url from the africastalking dashboard.
    // You can use this to monitor your events
    public function events_url() {
        $events = $this->input->post('events');
        log_message('info', print_r(array('events' => $events), true));
    }

    /////////////////////////////////////utility functions///////////////////////////////////////////

    private function capabilityToken($params) {
        $token = null;
        $url = 'https://webrtc.africastalking.com/capability-token/request';
        $data = array(
            'username' => $this->username,
            'phoneNumber' => $this->phoneNumber,
            'clientName' => isset($params['clientName']) ? $params['clientName'] : 'browser1',
            'incoming' => 'true',
            'outgoing' => 'true',
            'expire' => '7200s'
        );

        if ($data['username'] && $data['clientName'] && $data['phoneNumber']) {
            // add API key here
            $headers = array(
                'APIKEY: ' . $this->apiKey,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($result, true);

            if (isset($response['token'])) {
                $token = $response;
            }
        }

        return $token;
    }

    /**
     * Phone settings page
     */
    public function settings()
    {
        if (!has_permission('phone', '', 'edit')) {
            access_denied('phone');
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            
            if (isset($data['settings'])) {
                foreach ($data['settings'] as $key => $value) {
                    update_option($key, $value);
                }
                set_alert('success', _l('updated_successfully', _l('settings')));
            }
            
            redirect(admin_url('phone/settings'));
        }

        $data['title'] = _l('phone_settings');
        
        $this->load->view('phone/phone_settings', $data);
    }

}