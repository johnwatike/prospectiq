<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Follow_up extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Follow_up_model');
        $this->load->library('form_validation');
        $this->load->library('excel_import');
    }

    public function index()
    {
        if (!has_permission('debt_collection', '', 'view')) {
            access_denied('debt_collection');
        }
        $data['title'] = _l('debt_collection');
        $this->load->view('follow_up/manage', $data);
    }

    public function table_data()
    {
        if (!has_permission('debt_collection', '', 'view')) {
            ajax_access_denied();
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('debt_collection', 'datatables/follow_up_table'), ['postData' => $_POST]);
        }
    }

    public function add()
    {
        if (!has_permission('debt_collection', '', 'create')) {
            access_denied('debt_collection');
        }
        $_POST['branch_name'] = $this->input->post('branch_name', true) ?? '';
        $this->form_validation->set_rules('branch_name', _l('branch_name'), 'required');
        $_POST['admission_no'] = $this->input->post('admission_no', true) ?? '';
        $this->form_validation->set_rules('admission_no', _l('admission_no'), 'required');
        $_POST['student_name'] = $this->input->post('student_name', true) ?? '';
        $this->form_validation->set_rules('student_name', _l('student_name'), 'required');
        $_POST['registration_date'] = $this->input->post('registration_date', true) ?? '';
        $this->form_validation->set_rules('registration_date', _l('registration_date'), 'required');
        $_POST['fee'] = $this->input->post('fee', true) ?? '';
        $this->form_validation->set_rules('fee', _l('fee'), 'required');
        $_POST['fee_paid'] = $this->input->post('fee_paid', true) ?? '';
        $this->form_validation->set_rules('fee_paid', _l('fee_paid'), 'required');
        $_POST['fee_balance'] = $this->input->post('fee_balance', true) ?? '';
        $this->form_validation->set_rules('fee_balance', _l('fee_balance'), 'required');
        $_POST['id_no'] = $this->input->post('id_no', true) ?? '';
        $this->form_validation->set_rules('id_no', _l('id_no'), 'required');
        $_POST['phone_no'] = $this->input->post('phone_no', true) ?? '';
        $this->form_validation->set_rules('phone_no', _l('phone_no'), 'required');
        $_POST['course'] = $this->input->post('course', true) ?? '';
        $this->form_validation->set_rules('course', _l('course'), 'required');
        $_POST['status'] = $this->input->post('status', true) ?? '';
        $this->form_validation->set_rules('status', _l('status'), 'required');
        $_POST['feedback'] = $this->input->post('feedback', true) ?? '';
        $this->form_validation->set_rules('feedback', _l('feedback'), 'required');

        if ($this->form_validation->run() === true) {
            $data = $this->input->post(null, true);
            $id = $this->Follow_up_model->add($data);
            if ($id) {
                set_alert('success', _l('added_successfully', _l('follow_up')));
            }
            redirect(admin_url('debt_collection/follow_up'));
        }

        $data['title'] = _l('add_follow_up');
        $this->load->view('follow_up/add', $data);
    }


    public function edit($id)
    {
        if (!has_permission('debt_collection', '', 'edit')) {
            access_denied('debt_collection');
        }
        $_POST['branch_name'] = $this->input->post('branch_name', true) ?? '';
        $this->form_validation->set_rules('branch_name', _l('branch_name'), 'required');
        $_POST['admission_no'] = $this->input->post('admission_no', true) ?? '';
        $this->form_validation->set_rules('admission_no', _l('admission_no'), 'required');
        $_POST['student_name'] = $this->input->post('student_name', true) ?? '';
        $this->form_validation->set_rules('student_name', _l('student_name'), 'required');
        $_POST['registration_date'] = $this->input->post('registration_date', true) ?? '';
        $this->form_validation->set_rules('registration_date', _l('registration_date'), 'required');
        $_POST['fee'] = $this->input->post('fee', true) ?? '';
        $this->form_validation->set_rules('fee', _l('fee'), 'required');
        $_POST['fee_paid'] = $this->input->post('fee_paid', true) ?? '';
        $this->form_validation->set_rules('fee_paid', _l('fee_paid'), 'required');
        $_POST['fee_balance'] = $this->input->post('fee_balance', true) ?? '';
        $this->form_validation->set_rules('fee_balance', _l('fee_balance'), 'required');
        $_POST['id_no'] = $this->input->post('id_no', true) ?? '';
        $this->form_validation->set_rules('id_no', _l('id_no'), 'required');
        $_POST['phone_no'] = $this->input->post('phone_no', true) ?? '';
        $this->form_validation->set_rules('phone_no', _l('phone_no'), 'required');
        $_POST['course'] = $this->input->post('course', true) ?? '';
        $this->form_validation->set_rules('course', _l('course'), 'required');
        $_POST['status'] = $this->input->post('status', true) ?? '';
        $this->form_validation->set_rules('status', _l('status'), 'required');
        $_POST['feedback'] = $this->input->post('feedback', true) ?? '';
        $this->form_validation->set_rules('feedback', _l('feedback'), 'required');

        if ($this->form_validation->run() === true) {
            $data = [];
            $data['branch_name'] = $this->input->post('branch_name', true);
            $data['admission_no'] = $this->input->post('admission_no', true);
            $data['student_name'] = $this->input->post('student_name', true);
            $data['registration_date'] = $this->input->post('registration_date', true);
            $data['fee'] = $this->input->post('fee', true);
            $data['fee_paid'] = $this->input->post('fee_paid', true);
            $data['fee_balance'] = $this->input->post('fee_balance', true);
            $data['id_no'] = $this->input->post('id_no', true);
            $data['phone_no'] = $this->input->post('phone_no', true);
            $data['course'] = $this->input->post('course', true);
            $data['status'] = $this->input->post('status', true);
            $data['feedback'] = $this->input->post('feedback', true);
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'follow_up_', $data);
            set_alert('success', _l('updated_successfully', _l('follow_up')));
            redirect(admin_url('debt_collection/follow_up'));
        }

        $data['record'] = $this->Follow_up_model->get($id);
        if (!$data['record']) {
            set_alert('warning', _l('record_not_found'));
            redirect(admin_url('debt_collection/follow_up'));
        }

        $data['title']  = _l('edit_follow_up');
        $this->load->view('follow_up/edit', $data);
    }


    public function delete($id)
    {
        if (!has_permission('debt_collection', '', 'delete')) {
            access_denied('debt_collection');
        }
        if ($this->Follow_up_model->delete($id)) {
            set_alert('success', _l('deleted', _l('follow_up')));
        }
        redirect(admin_url('debt_collection/follow_up'));
    }

    public function import_view()
    {
        if (!has_permission('debt_collection', '', 'create')) {
            access_denied('debt_collection');
        }
        $data['title'] = _l('import_data');
        $this->load->view('follow_up/import', $data);
    }

    public function import()
    {
        if (!has_permission('', '', 'create')) {
            access_denied('');
        }

        if (!empty($_FILES['import_file']['tmp_name'])) {
            try {
                $records = $this->excel_import->parse_excel($_FILES['import_file']['tmp_name']);
                foreach ($records as $record) {
                    $this->db->insert(db_prefix() . 'follow_up_', $record);
                }
                set_alert('success', 'Import completed successfully.');
            } catch (Exception $e) {
                set_alert('danger', 'Import failed: ' . $e->getMessage());
            }
        } else {
            set_alert('danger', 'No file uploaded.');
        }

         redirect(admin_url('debt_collection/follow_up'));
    }
}
