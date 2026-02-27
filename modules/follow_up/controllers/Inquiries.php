<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inquiries extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Inquiries_model');
        $this->load->library('form_validation');
        $this->load->library('excel_import');
    }

    public function index()
    {
        if (!has_permission('follow_up', '', 'view') && !has_permission('follow_up', '', 'view_own')) {
            access_denied('follow_up');
        }
        $data['title'] = _l('follow_up');
        $this->load->view('inquiries/manage', $data);
    }

    public function table_data()
    {
        if (!has_permission('follow_up', '', 'view') && !has_permission('follow_up', '', 'view_own')) {
            ajax_access_denied();
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('follow_up', 'datatables/inquiries_table'), ['postData' => $_POST]);
        }
    }

    public function add()
    {
        if (!has_permission('follow_up', '', 'create')) {
            access_denied('follow_up');
        }
        $_POST['client_name'] = $this->input->post('client_name', true) ?? '';
        $this->form_validation->set_rules('client_name', _l('client_name'), 'required');
        $_POST['contact'] = $this->input->post('contact', true) ?? '';
        $this->form_validation->set_rules('contact', _l('contact'), 'required');
        $_POST['course'] = $this->input->post('course', true) ?? '';
        $this->form_validation->set_rules('course', _l('course'), 'required');
        $_POST['feedback'] = $this->input->post('feedback', true) ?? '';
        $this->form_validation->set_rules('feedback', _l('feedback'), 'required');
      
         if ($this->form_validation->run() === true) {
            $data = $this->input->post(null, true);
            $data['branch_id'] = $this->session->userdata('branch_id');
            $data['user_id'] = $this->session->userdata('staff_user_id');

            $data['record'] = $this->Inquiries_model->add($data);

            if ($id) {
                set_alert('success', _l('added_successfully', _l($ENTITY)));
            }

            redirect(admin_url("$MODULE_NAME/$ENTITY"));
        }

        $data['title'] = _l('add_inquiries');
        $this->load->view('inquiries/add', $data);
    }


    public function view($id)
    {
        if (!has_permission('follow_up', '', 'view') && !has_permission('follow_up', '', 'view_own')) {
            access_denied('follow_up');
        }

        $data['record'] = $this->Inquiries_model->get($id);
        if (!$data['record']) {
            set_alert('warning', _l('record_not_found'));
            redirect(admin_url('follow_up/inquiries'));
        }

        // Optional: restrict if user only has view_own
        if (!has_permission('follow_up', '', 'view') && has_permission('follow_up', '', 'view_own')) {
            if ($data['record']['user_id'] != get_staff_user_id()) {
                access_denied('follow_up');
            }
        }

        $data['title']  = _l('view_inquiries');
        $this->load->view('inquiries/view', $data);
    }


    public function edit($id)
    {
        if (!has_permission('follow_up', '', 'edit')) {
            access_denied('follow_up');
        }
        $_POST['client_name'] = $this->input->post('client_name', true) ?? '';
        $this->form_validation->set_rules('client_name', _l('client_name'), 'required');
        $_POST['contact'] = $this->input->post('contact', true) ?? '';
        $this->form_validation->set_rules('contact', _l('contact'), 'required');
        $_POST['course'] = $this->input->post('course', true) ?? '';
        $this->form_validation->set_rules('course', _l('course'), 'required');
        $_POST['feedback'] = $this->input->post('feedback', true) ?? '';
        $this->form_validation->set_rules('feedback', _l('feedback'), 'required');
       

        if ($this->form_validation->run() === true) {
            $data = [];
            $data['client_name'] = $this->input->post('client_name', true);
            $data['contact'] = $this->input->post('contact', true);
            $data['course'] = $this->input->post('course', true);
            $data['feedback'] = $this->input->post('feedback', true);
           
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'inquiries', $data);
            set_alert('success', _l('updated_successfully', _l('inquiries')));
            redirect(admin_url('follow_up/inquiries'));
        }

        $data['record'] = $this->Inquiries_model->get($id);
        if (!$data['record']) {
            set_alert('warning', _l('record_not_found'));
            redirect(admin_url('follow_up/inquiries'));
        }

        $data['title']  = _l('edit_inquiries');
        $this->load->view('inquiries/edit', $data);
    }


    public function delete($id)
    {
        if (!has_permission('follow_up', '', 'delete')) {
            access_denied('follow_up');
        }
        if ($this->Inquiries_model->delete($id)) {
            set_alert('success', _l('deleted', _l('inquiries')));
        }
        redirect(admin_url('follow_up/inquiries'));
    }

    public function import_view()
    {
        if (!has_permission('follow_up', '', 'create')) {
            access_denied('follow_up');
        }
        $data['title'] = _l('import_data');
        $this->load->view('inquiries/import', $data);
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
                    $this->db->insert(db_prefix() . 'inquiries', $record);
                }
                set_alert('success', 'Import completed successfully.');
            } catch (Exception $e) {
                set_alert('danger', 'Import failed: ' . $e->getMessage());
            }
        } else {
            set_alert('danger', 'No file uploaded.');
        }

         redirect(admin_url('follow_up/inquiries'));
    }
}
