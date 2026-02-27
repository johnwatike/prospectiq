<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Follow_up_note extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Follow_up_note_model');
        $this->load->library('form_validation');
        $this->load->library('excel_import');
    }

    public function index()
    {
        if (!has_permission('debt_collection', '', 'view')) {
            access_denied('debt_collection');
        }
        $data['title'] = _l('debt_collection');
        $this->load->view('follow_up_note/manage', $data);
    }

    public function table_data()
    {
        if (!has_permission('debt_collection', '', 'view')) {
            ajax_access_denied();
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('debt_collection', 'datatables/follow_up_note_table'), ['postData' => $_POST]);
        }
    }

    public function add()
    {
        if (!has_permission('debt_collection', '', 'create')) {
            access_denied('debt_collection');
        }
        $_POST['follow_up_id'] = $this->input->post('follow_up_id', true) ?? '';
        $this->form_validation->set_rules('follow_up_id', _l('follow_up_id'), 'required');
        $_POST['note'] = $this->input->post('note', true) ?? '';
        $this->form_validation->set_rules('note', _l('note'), 'required');
        $_POST['created_by'] = $this->input->post('created_by', true) ?? '';
        $this->form_validation->set_rules('created_by', _l('created_by'), 'required');
        $_POST['created_at'] = $this->input->post('created_at', true) ?? '';
        $this->form_validation->set_rules('created_at', _l('created_at'), 'required');

        if ($this->form_validation->run() === true) {
            $data = $this->input->post(null, true);
            $id = $this->Follow_up_note_model->add($data);
            if ($id) {
                set_alert('success', _l('added_successfully', _l('follow_up_note')));
            }
            redirect(admin_url('debt_collection/follow_up_note'));
        }

        $data['title'] = _l('add_follow_up_note');
        $this->load->view('follow_up_note/add', $data);
    }


    public function edit($id)
    {
        if (!has_permission('debt_collection', '', 'edit')) {
            access_denied('debt_collection');
        }
        $_POST['follow_up_id'] = $this->input->post('follow_up_id', true) ?? '';
        $this->form_validation->set_rules('follow_up_id', _l('follow_up_id'), 'required');
        $_POST['note'] = $this->input->post('note', true) ?? '';
        $this->form_validation->set_rules('note', _l('note'), 'required');
        $_POST['created_by'] = $this->input->post('created_by', true) ?? '';
        $this->form_validation->set_rules('created_by', _l('created_by'), 'required');
        $_POST['created_at'] = $this->input->post('created_at', true) ?? '';
        $this->form_validation->set_rules('created_at', _l('created_at'), 'required');

        if ($this->form_validation->run() === true) {
            $data = [];
            $data['follow_up_id'] = $this->input->post('follow_up_id', true);
            $data['note'] = $this->input->post('note', true);
            $data['created_by'] = $this->input->post('created_by', true);
            $data['created_at'] = $this->input->post('created_at', true);
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'follow_up_note_', $data);
            set_alert('success', _l('updated_successfully', _l('follow_up_note')));
            redirect(admin_url('debt_collection/follow_up_note'));
        }

        $data['record'] = $this->Follow_up_note_model->get($id);
        if (!$data['record']) {
            set_alert('warning', _l('record_not_found'));
            redirect(admin_url('debt_collection/follow_up_note'));
        }

        $data['title']  = _l('edit_follow_up_note');
        $this->load->view('follow_up_note/edit', $data);
    }


    public function delete($id)
    {
        if (!has_permission('debt_collection', '', 'delete')) {
            access_denied('debt_collection');
        }
        if ($this->Follow_up_note_model->delete($id)) {
            set_alert('success', _l('deleted', _l('follow_up_note')));
        }
        redirect(admin_url('debt_collection/follow_up_note'));
    }

    public function import_view()
    {
        if (!has_permission('debt_collection', '', 'create')) {
            access_denied('debt_collection');
        }
        $data['title'] = _l('import_data');
        $this->load->view('follow_up_note/import', $data);
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
                    $this->db->insert(db_prefix() . 'follow_up_note_', $record);
                }
                set_alert('success', 'Import completed successfully.');
            } catch (Exception $e) {
                set_alert('danger', 'Import failed: ' . $e->getMessage());
            }
        } else {
            set_alert('danger', 'No file uploaded.');
        }

         redirect(admin_url('debt_collection/follow_up_note'));
    }
}
