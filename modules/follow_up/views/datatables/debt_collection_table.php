<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'branch_name',
    'admission_no',
    'student_name',
    'registration_date',
    'fee',
    'fee_paid',
    'fee_balance',
    'id_no',
    'phone_no',
    'course',
    'status',
    'feedback',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'debt_collection';

$CI = &get_instance();
$where = [];

// 🔐 Permission Check: Only show own records if not allowed to view all
if (!has_permission('follow_up', '', 'view') && has_permission('follow_up', '', 'view_own')) {
    $user_id = $CI->session->userdata('staff_user_id');
    $where[] = 'AND user_id = ' . $CI->db->escape($user_id);
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    foreach ($aColumns as $col) {
        $row[] = $aRow[$col];
    }

    // ✏️ Edit and ❌ Delete buttons with icons
    $viewUrl = admin_url('follow_up/debt_collection/view/' . $aRow['id']);
    $editUrl = admin_url('follow_up/debt_collection/edit/' . $aRow['id']);
    $deleteUrl = admin_url('follow_up/debt_collection/delete/' . $aRow['id']);

    $row[] = '<a href="' . $viewUrl . '" class="btn btn-sm btn-info mright5" title="' . _l('view') . '">
                 <i class="fa fa-eye"></i>
              </a>
              <a href="' . $editUrl . '" class="btn btn-sm btn-primary mright5" title="' . _l('edit') . '">
                 <i class="fa fa-pencil"></i>
              </a>
              <a href="' . $deleteUrl . '" class="btn btn-sm btn-danger _delete" title="' . _l('delete') . '">
                 <i class="fa fa-trash"></i>
              </a>';

    $output['aaData'][] = $row;
}
