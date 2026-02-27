<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'follow_up_id',
    'note',
    'created_by',
    'created_at',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'follow_up_note_';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], []);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    foreach ($aColumns as $col) {
        $row[] = $aRow[$col];
    }

    // Edit and Delete buttons with icons
    $editUrl = admin_url('debt_collection/follow_up_note/edit/' . $aRow['id']);
    $deleteUrl = admin_url('debt_collection/follow_up_note/delete/' . $aRow['id']);
    $row[] = '<a href="' . $editUrl . '" class="btn btn-sm btn-primary mright5" title="' . _l('edit') . '">
                 <i class="fa fa-pencil"></i>
               </a>
               <a href="' . $deleteUrl . '" class="btn btn-sm btn-danger _delete" title="' . _l('delete') . '">
                 <i class="fa fa-trash"></i>
               </a>';

    $output['aaData'][] = $row;
}
