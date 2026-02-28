<?php
defined('BASEPATH') or exit('No direct script access allowed');

// SELECT cl.id, 
// cl.callStartTime ,
// cl.sessionId,
// cl.destinationNumber, cl.durationInSeconds,cl.branch_id,
// WP.workplace_name,  WP.phone , stf.firstname,stf.lastname,
// cl.user_id, cl.currencyCode, cl.amount, cl.call_direction ,
// cl.status,
// cl.recordingUrl
// FROM calls_logs cl
// left JOIN tblworkplace WP ON   WP.workplace_id = cl.branch_id
// left join tblstaff stf on stf.staffid = cl.user_id
// where durationInSeconds > 0 and branch_id is not null and user_id is not null
// order by id desc

$CI =& get_instance();
$aColumns = [
    'calls_logs.id as id',
    'callStartTime',
    'sessionId',
    'destinationNumber',
    'durationInSeconds',
    'branch_id',
    'tblworkplace.workplace_name as workplace_name',
    'tblworkplace.phone',
    'firstname',
    'lastname',
    'amount',
    'call_direction',
    'status',
    'recordingUrl',
    'clientDialedNumber',
    'callerNumber'
    // ' (SELECT  SUM(amount) AS total_paid  FROM  tblinvoicepaymentrecords where tblinvoicepaymentrecords.invoiceid  = tblinvoices.id) as total_paid2',
    // db_prefix() . 'clients.phonenumber as phonenumber'
];
$sIndexColumn = 'id';
$sTable       = 'calls_logs';
$filter = [];
$where = [];
// $staff_id =  get_staff_user_id();
// $invoice = 'invoice';
// if(has_permission('reminder','','view_own') && !is_admin()){
//     array_push($where, 'AND created_by_staff='.$staff_id);
// }

// $isnotified = $this->ci->input->post('isnotified');
// if($isnotified){
//     array_push($filter, "AND (" . db_prefix() . "reminders.isnotified=". $this->ci->input->post('isnotified')." OR ". db_prefix() . "reminders.isnotified = 0)");
// }else{
//     array_push($filter, "AND " . db_prefix() . "reminders.isnotified= 0");
// }
// if ( $CI->session->userdata('admin') != 1 ) {
// //    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
//     array_push($where, 'AND ('. db_prefix() . 'reminders.branch_id= ' .  $CI->session->userdata('branch_id'). ')');

// }
 array_push($where, 'AND (calls_logs.durationInSeconds > 0  )');
  array_push($where, 'AND (calls_logs.branch_id IS NOT NULL  )');

// if ($this->ci->input->post('reminder_filter_number')) {
//     array_push($filter, "AND " . db_prefix() . "reminders.id LIKE '%" . removeQ($this->ci->input->post('reminder_filter_number')) . "%'");
// }
if ($this->ci->input->post('filter_date_follow_f') && $this->ci->input->post('filter_date_follow_t')) {
    array_push($filter, "AND calls_logs.createdAt BETWEEN '" . strtotimemod($this->ci->input->post('filter_date_follow_f')) . "' AND '" . strtotimemod($this->ci->input->post('filter_date_follow_t')) . "'");
}
// reminder_filter_date_f: 2023-07-24
// reminder_filter_date_t: 2023-07-31
// //Check date filter
if ($this->ci->input->post('reminder_filter_date_f')) {
    array_push($where, "AND calls_logs.createdAt >= '" .  to_sql_date($this->ci->input->post('reminder_filter_date_f')) . "'");
}
if ($this->ci->input->post('reminder_filter_date_t')) {
    array_push($where, "AND calls_logs.createdAt <= '" .  to_sql_date($this->ci->input->post('reminder_filter_date_t')) . "'+ INTERVAL 1 DAY");
}
//call_filter_selected_branch
if ($this->ci->input->post('reminder_filter_assigned')) {
    // echo "call_filter_selected_branch";
    array_push($where, "AND (calls_logs.branch_id = '" .  ($this->ci->input->post('reminder_filter_assigned')) . "')");
}
// //Check status filter
// if ($this->ci->input->post('reminder_filter_related')) {
//     array_push($filter, "AND " . db_prefix() . "reminders.rel_type = '" . $this->ci->input->post('reminder_filter_related')."'");
// }
// //Check company filter
// if ($this->ci->input->post('reminder_filter_company')) {
//     array_push($filter, "AND customer = " . get_client_id_by_company($this->ci->input->post('reminder_filter_company')));
// }
// //Check name filter
// if ($this->ci->input->post('reminder_filter_contact_val')) {
//     array_push($filter, "AND contact = " . get_contact_id_by_full_name($this->ci->input->post('reminder_filter_contact_val')));
// }
// //Check status filter
// if ($this->ci->input->post('reminder_filter_assigned')) {
//     array_push($filter, "AND " . db_prefix() . "reminders.assigned_to = " . $this->ci->input->post('reminder_filter_assigned'));
// }
// //Check email filter
// if ($this->ci->input->post('reminder_filter_description')) {
//     array_push($filter, "AND description LIKE '%" . $this->ci->input->post('reminder_filter_description') . "%'");
// }
// //Check follow up date filter
if ($this->ci->input->post('filter_date_follow_f') && $this->ci->input->post('filter_date_follow_t')) {
    // array_push($filter, "AND follow_up_date BETWEEN '" . strtotimemod($this->ci->input->post('filter_date_follow_f')) . "' AND '" . strtotimemod($this->ci->input->post('filter_date_follow_t')) . "'");
}

// if ($this->ci->input->post('assigned')) {
//     array_push($where, 'AND branch_id =' . $this->ci->db->escape_str($this->ci->input->post('assigned')));
// }

//array_push($where, "AND " . db_prefix() . "reminders.rel_type=`invoice`");
// array_push($where, "AND (" . db_prefix() . "reminders.rel_type=". "'invoice' )");

// $agents    = $this->ci->reminder_model->get_sale_agents();
// $agentsIds = [];
// foreach ($agents as $agent) {
//     if ($this->ci->input->post('sale_agent_' . $agent['sale_agent'])) {
//         array_push($agentsIds, $agent['sale_agent']);
//     }
// }
// // assigned_to
// if (count($agentsIds) > 0) {
//     array_push($filter, 'AND branch_id IN (' . implode(', ', $agentsIds) . ')');
// }
// $customer    = $this->ci->reminder_model->getCustomersData();
// $customerIds = [];
// foreach ($customer as $agent) {
//     if ($this->ci->input->post('customer_' . $agent['userid'])) {
//         array_push($customerIds, $agent['userid']);
//     }
// }
// $rel_types = ['quotes','estimate','invoice','credit_note','tickets'] ;
// $relationTypesIds = [];
// foreach ($rel_types as $type) {
//     if ($this->ci->input->post('rel_type_' . $type)) {
//         array_push($relationTypesIds, $type);
//     }
// }
// $created_by    = $this->ci->reminder_model->get_created_by_ids();
// $created_by_ids = [];
// foreach ($created_by as $id) {
//     if ($this->ci->input->post('created_by_' . $id['by_staff'])) {
//         array_push($created_by_ids, $id['by_staff']);
//     }
// }
// if (count($created_by_ids) > 0) {
//     array_push($filter, 'AND created_by_staff IN (' . implode(', ', $created_by_ids) . ')');
// }
// if (count($customerIds) > 0) {
//     array_push($filter, 'AND customer IN (' . implode(', ', $customerIds) . ')');
// }

// if (count($relationTypesIds) > 0) {
//     array_push($filter, 'AND rel_type IN ("' . implode('", "', $relationTypesIds) . '")');
// }
// $years      = $this->ci->reminder_model->get_reminder_years();
// $yearsArray = [];
// foreach ($years as $year) {
//     if ($this->ci->input->post('year_' . $year['year'])) {
//         array_push($yearsArray, $year['year']);
//     }
// }
// if (count($yearsArray) > 0) {
//     array_push($filter, 'AND YEAR(date) IN (' . implode(', ', $yearsArray) . ')');
// }
// if (count($filter) > 0) {
//     array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
// }
//$join          = [];
$join = [
    ' JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = calls_logs.user_id',
    ' JOIN ' . db_prefix() . 'workplace ON ' . db_prefix() . 'workplace.workplace_id =  calls_logs.branch_id',
//    'LEFT JOIN (select id,  phonenumber as contact_phonenumber from ' . db_prefix() . 'contacts) as contact ON contact.id = ' . db_prefix() . 'reminders.contact',
// 'LEFT JOIN (SELECT id,  total FROM tblinvoices WHERE status < 5 OR status = 3 OR status = 5 GROUP BY clientid) AS i ON ' .  db_prefix() . 'reminders.invoice_id = i.id',
//    'LEFT JOIN (SELECT  SUM(total) AS total2 FROM tblinvoices WHERE status < 5 OR status = 3 OR status = 5 ) AS i ON .id = ' . db_prefix() . 'reminders.customer',

//    'LEFT JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'reminders.invoice_id',
//    'JOIN ' . db_prefix() . 'leads_sources ON ' . db_prefix() . 'leads_sources.id = ' . db_prefix() . 'leads.source',
//    'LEFT JOIN ' . db_prefix() . 'workplace ON ' . db_prefix() . 'workplace.workplace_id=' . db_prefix() . 'leads.branch_id  ' ,
    // 'LEFT JOIN (select name as courses_name, id from ' . db_prefix() . 'courses )  as courses ON   courses.id=' . db_prefix() . 'clients.course_id  ',
    // ' LEFT JOIN (
    //     SELECT 
    //         tblinvoicepaymentrecords.invoiceid, 
    //         SUM(amount) AS total_paid
    //     FROM 
    //         tblinvoicepaymentrecords 
    //     GROUP BY 
    //          tblinvoicepaymentrecords.invoiceid
    // ) AS p 
    // ON   p.invoiceid ='. db_prefix().'reminders.invoice_id '
];
// $custom_fields = get_table_custom_fields('reminder');
// $aColumns = hooks()->apply_filters('reminder_table_sql_columns', $aColumns);
// // Fix for big queries. Some hosting have max_join_limit
// if (count($custom_fields) > 4) {
//     @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
// }
//@$this->ci->db->query('left  JOIN tblinvoices ON ' . db_prefix() . 'reminders.customer = tblinvoices.clientid');
//
//$this->ci->db->join('tblinvoices', 'tblreminders.customer = tblinvoices.clientid', 'left');
//@$this->ci->db->join('(SELECT *, SUM(total) AS total2 FROM tblinvoices WHERE status < 5 OR status = 3 OR status = 5 GROUP BY clientid) AS i',   db_prefix() . 'reminders.customer = i.clientid', 'left');

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [ 'calls_logs.id as id']);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
//    $row[] = !empty(get_client($aRow['customer'])) ? get_client($aRow['customer'])->company : '';
    $customer = (isset($aRow['customer']) && !empty($aRow['customer'])) ? (get_client($aRow['customer']) ? get_client($aRow['customer'])->company : '') : '';
//    $numberOutput = '<a href="javascript:void(0);" onclick="init_reminder(' . $aRow['id'] . '); return false;">' ._dt($aRow['id']) . '</a>';
//    $numberOutput .= '<div class="row-options">';
//    $numberOutput .= '<a href="javascript:void(0);" onclick="getViewModal(' . $aRow['id'] . ')">' . _l('view') . '</a>';
//    if (has_permission('reminder', '', 'delete')) {
//        $numberOutput .= ' | <a href="' . admin_url('reminder/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
//    }
//    $numberOutput .= '</div>';

//    $row[] = '<div  id="lead_'. $aRow['id'] .'"class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
//     $hrefAttr = 'href="' . admin_url('leads/index/' . $aRow['id']) . '" onclick="init_lead(' . $aRow['customer'] . ');return false;"';
// //    $row[]    = '<a ' . $hrefAttr . '>' . $aRow['id'] . '</a>';
//     $nameRow = '<a ' . $hrefAttr . '>' . $customer . '</a>';
//     $nameRow .= '<div class="row-options">';
// //    $nameRow .= '<a ' . $hrefAttr . '>' . _l('SAVE') . '</a>';
//     $nameRow .=' <button class="btn btn-sm btn-primary btn-edit-row" data-row-id="' . $aRow['id'] . '">' . _l('Save Changes') . '</button>';
//     $nameRow .= '</div>';
//     $row[] = $nameRow;


//     $row[] =  $aRow['date'];

//    $row[] = $numberOutput;
//    $row[] = !empty(get_staff($aRow['assigned_to'])) ? get_staff($aRow['assigned_to'])->firstname.' '.get_staff($aRow['assigned_to'])->lastname : '';
//    $row[] = !empty(get_client($aRow['customer'])) ? get_client($aRow['customer'])->company : '';
//    $row[] =  $aRow['phonenumber'];  //get_contact_full_name($aRow['contact']);
//     $phonenumber = $aRow['phonenumber'];//(isset($lead) && $lead->phonenumber != '' ?  $lead->phonenumber  : '') ;

//   $row[] = $aRow['phonenumber'].'<iframe id="widget-iframe" src="/admin/phone/mico_dialer?name=Petans-driving&phonenumber='.$phonenumber.'&call_log_id=123456&branch_id='.$CI->session->userdata('branch_id').'"></iframe>';
// //    $row[] = $aRow['description'];
//     $row[] =  $aRow['branch_code'];//branch
//     $row[] =  $aRow['courses_name'];//course
//     $row[] =  $aRow['invoice_total'];//course invoice amount

//     $row[] =  ($aRow['total_paid'] == '') ? 0 : $aRow['total_paid'];

//     // $aRow['total_paid'];//fee paid
//     $row[] =  ($aRow['balance'] == '') ? 0 : $aRow['balance'];
//     // $aRow['balance'];//balance

//     $row[] =  $aRow['amount_recoverd'];// amount rcovered
// //    $row[] =  $aRow['description'];//feedbakck
//     $row[] = '<span id="feedback_' . $aRow['id'] . '" contenteditable="true" style="background-color: #f1f1f1; border: 1px solid #ddd; padding: 5px;">' . $aRow['description'] . '</span> <hr>'.' <input type="date" class="datepicker" id="review_date_' . $aRow['id'] . '" value="' . $aRow['date'] . '" required>';

// //    $row[] =  $aRow['phonenumber'];//
//   $value = (isset($aRow['date']) ? _d($aRow['date']) : '') ;

//    $row[] = render_datetime_input('date','set_reminder_date',$value,array('data-date-min-date'=>_d(date('Y-m-d'))));
//    $row[] =   '<input type="date" id="review_date_' . $aRow['id'] . '" value="' . $aRow['date'] . '" required>';
    //$row[] = '<input type="date" class="datepicker" id="review_date_' . $aRow['id'] . '" value="' . $aRow['date'] . '" required>';
 $row[]=$aRow['id'];

$row[]=$aRow['callStartTime'];
$row[]=$aRow['workplace_name'];
$row[]=$aRow['firstname'].' '.$aRow['lastname'];
$row[]=$aRow['call_direction'];
$row[]=$aRow['durationInSeconds'];
if($aRow['call_direction'] == "Outgoing" || $aRow['call_direction'] == "outgoing"){
    $row[]=$aRow['clientDialedNumber']; 
}else{
   
    $row[]=$aRow['callerNumber']; 
}
$row[]= '<audio controls>
  <source src="'.'https://phone.petanns.co.ke/'.$aRow['recordingUrl'].'" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>
';//;

// $row[]='';
// $row[]='';

//    if($this->ci->input->post('reminder_filter_related')=='custom_reminder'){
//        $row[] = ucfirst(_l($aRow['other_relation_type']));
//    }else{
//        $row[] = ucfirst(_l($aRow['rel_type']));
//    }
//    $row[] = $aRow['isnotified'] ? '<span class="label label-success">'._l('rm_notified_status').'</span>' : '<span class="label label-warning">'._l('rm_not_notified_status').'</span>';
//    $staff = '';
//    if(!empty($aRow['created_by_staff'])){
//        $oStaff = $this->ci->staff_model->get($aRow['created_by_staff']);
//        $staff = '<a data-toggle="tooltip" data-title="' . $oStaff->full_name . '" href="' . admin_url('profile/' . $aRow['created_by_staff']) . '">' . staff_profile_image($aRow['created_by_staff'], [
//            'staff-profile-image-small',
//        ]) . '</a>';
//        $staff .= '<span class="hide">' . $oStaff->full_name . '</span>';
//    }
//    $row[] = $staff;

    $buttonsHTML = '
        <div class="buttons-container">
           
            <button class="load-widget-button btn-call-row" data-row-id="' . $aRow['id'] . '">Load Widget</button>
        </div>
    ';

//    $row[] = $buttonsHTML;

    $row['DT_RowClass'] = 'has-row-options';
    $row['DT_RowLink'] = $aRow['id'];
    $row = hooks()->apply_filters('reminder_table_row_data', $row, $aRow);
    $output['aaData'][] = $row;
}
