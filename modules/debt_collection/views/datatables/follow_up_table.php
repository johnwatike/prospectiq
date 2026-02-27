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
$sTable = db_prefix() . 'follow_up_';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], []);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    foreach ($aColumns as $col) {
        $row[] = $aRow[$col];
    }

    // Edit and Delete buttons with icons
    $editUrl = admin_url('debt_collection/follow_up/edit/' . $aRow['id']);
    $deleteUrl = admin_url('debt_collection/follow_up/delete/' . $aRow['id']);
    $row[] = '<a href="' . $editUrl . '" class="btn btn-sm btn-primary mright5" title="' . _l('edit') . '">
                 <i class="fa fa-pencil"></i>
               </a>
               <a href="' . $deleteUrl . '" class="btn btn-sm btn-danger _delete" title="' . _l('delete') . '">
                 <i class="fa fa-trash"></i>
               </a>';

    $output['aaData'][] = $row;
}
