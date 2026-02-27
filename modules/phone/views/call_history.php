<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $CI =& get_instance(); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="_filters _hidden_inputs">
                <?php 
                // if(isset($statuses) && !empty($statuses)){
                //     foreach($statuses as $_status){
                //         $val = '';
                //         if($_status == $this->input->get('status')){
                //             $val = $_status;
                //         }
                //         echo form_hidden('reminder_'.$_status,$val);
                //     } 
                // }
                // foreach($years as $year){
                //     echo form_hidden('year_'.$year['year'],$year['year']);
                // }
                // foreach($reminder_sale_agents as $agent){
                //     echo form_hidden('sale_agent_'.$agent['sale_agent']);
                // }
                // foreach($clients as $cust){
                //     echo form_hidden('customer_'.$cust['userid']);
                // } 
                // echo form_hidden('leads_related');
                // echo form_hidden('customers_related');
                // echo form_hidden('expired');
                // echo form_hidden('isnotified',0);
                // $reminder_filter_number_val = !empty($this->session->userdata['reminder_filter_number']) ? $this->session->userdata['reminder_filter_number'] : '';
                // echo form_hidden('reminder_filter_number', $reminder_filter_number_val);
                $reminder_filter_date_f_val = !empty($this->session->userdata['reminder_filter_date_f']) ? $this->session->userdata['reminder_filter_date_f'] : '';
                echo form_hidden('reminder_filter_date_f', $reminder_filter_date_f_val);
                $reminder_filter_date_t_val = !empty($this->session->userdata['reminder_filter_date_t']) ? $this->session->userdata['reminder_filter_date_t'] : '';
                echo form_hidden('reminder_filter_date_t', $reminder_filter_date_t_val);
                $reminder_filter_company_val = !empty($this->session->userdata['reminder_filter_company']) ? $this->session->userdata['reminder_filter_company'] : '';
               // echo form_hidden('reminder_filter_company', $reminder_filter_company_val);
               // $reminder_filter_contact_val = !empty($this->session->userdata['reminder_filter_contact']) ? $this->session->userdata['reminder_filter_contact'] : '';
               // echo form_hidden('reminder_filter_contact', $reminder_filter_contact_val);
               // $reminder_filter_description_val = !empty($this->session->userdata['reminder_filter_description']) ? $this->session->userdata['reminder_filter_description'] : '';
               // echo form_hidden('reminder_filter_description', $reminder_filter_description_val);
                $reminder_filter_assigned_val = !empty($this->session->userdata['reminder_filter_assigned']) ? $this->session->userdata['reminder_filter_assigned'] : '';
                echo form_hidden('reminder_filter_assigned', $reminder_filter_assigned_val);

                // echo form_hidden('rel_type_quotes');
                // echo form_hidden('rel_type_estimate');
                // echo form_hidden('rel_type_invoice');
                // echo form_hidden('rel_type_credit_note');
                // echo form_hidden('rel_type_tickets');
                foreach($created_ids as $id){
                    echo form_hidden('created_by_'.$id['by_staff']);
                } 
                ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel_s mbot10">
                        <div class="panel-body _buttons">
                            <div class="row">
                                <div class="col-md-12">
                                  
                                   <div class="col-md-4">
                                        <?php echo render_date_input('date_f','','',['placeholder' => _l('rm_from_date')]); ?> 
                                    </div>
                                    <div class="col-md-4">
                                     <?php echo render_date_input('date_t','','',['placeholder' => _l('rm_to_date')]); ?>    
                                 </div>
                                  <div class="col-md-4 leads-filter-column ">
                                            <?php 
                                             // Get branches/workplaces from database if table exists
                                             $branches = [];
                                             $CI =& get_instance();
                                             if ($CI->db->table_exists(db_prefix() . 'workplace')) {
                                                 $branches = $CI->db->select('workplace_id, workplace_name')
                                                     ->get(db_prefix() . 'workplace')
                                                     ->result_array();
                                             } elseif ($CI->db->table_exists(db_prefix() . 'timesheets_workplace')) {
                                                 $branches = $CI->db->select('id as workplace_id, workplace_name')
                                                     ->get(db_prefix() . 'timesheets_workplace')
                                                     ->result_array();
                                             }

                                             echo render_select('view_assigned', $branches, ['workplace_id', 'workplace_name'], '', '', ['data-none-selected-text' => _l('Select Branch')]);

                                        ?>
                                        </div>
                                 <div class="col-md-3 hidden">
                                    <div class="display-block text-right">
                                        <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-filter" aria-hidden="true"></i>
                                            </button>
                                            <ul class="dropdown-menu width300">
                                                <li>
                                                    <a href="#" data-cview="all" onclick="dt_custom_view('','.table-reminder',''); return false;">
                                                        <?php echo _l('proposals_list_all'); ?>
                                                    </a>
                                                </li>
                                                <li class="divider"></li>
                                                <?php if(count($years) > 0){ ?>
                                                    <?php foreach($years as $year){ ?>
                                                        <li class="active">
                                                            <a href="#" data-cview="year_<?php echo $year['year']; ?>" onclick="dt_custom_view(<?php echo $year['year']; ?>,'.table-reminder','year_<?php echo $year['year']; ?>'); return false;"><?php echo $year['year']; ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <li class="divider"></li>
                                            <?php } ?>
                                            <li>
                                                <a href="#" data-cview="isnotified" onclick="dt_custom_view('1','.table-reminder','isnotified'); return false;">
                                                    <?php echo _l('Completed Follow Up'); ?>
                                                </a>
                                            </li>
                                            <?php if(count($reminder_sale_agents) > 0){ ?>
                                                <div class="clearfix"></div>
                                                <li class="divider"></li>
                                                <li class="dropdown-submenu pull-left">
                                                    <a href="#" tabindex="-1"><?php echo _l('Branch'); ?></a>
                                                    <ul class="dropdown-menu dropdown-menu-left">
                                                        
                                                        <?php 
                                                        
                                                        // Get branches/workplaces from database if table exists
                                                        $branches = [];
                                                        $CI =& get_instance();
                                                        if ($CI->db->table_exists(db_prefix() . 'workplace')) {
                                                            $branches = $CI->db->select('workplace_id, workplace_name')
                                                                ->get(db_prefix() . 'workplace')
                                                                ->result_array();
                                                        } elseif ($CI->db->table_exists(db_prefix() . 'timesheets_workplace')) {
                                                            $branches = $CI->db->select('id as workplace_id, workplace_name')
                                                                ->get(db_prefix() . 'timesheets_workplace')
                                                                ->result_array();
                                                        }
                                                        foreach($branches as $agent){ 
                                                         

                                           //  echo render_select('view_assigned', $branches, [ 'workplace_id', [ 'workplace_name']], '', '', ['data-none-selected-text' => _l('Select Branch')]);

                                            
                                                        ?>
                                                            <li>
                                                                <a href="#" data-cview="sale_agent_<?php echo $agent['sale_agent']; ?>" onclick="dt_custom_view('sale_agent_<?php echo $agent['workplace_id']; ?>','.table-reminder','sale_agent_<?php echo $agent['workplace_id']; ?>'); return false;"><?php echo $agent['workplace_name']; //get_staff_full_name($agent['sale_agent']); ?>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        <?php if(count($clients) > 0){ ?>
                                            <div class="clearfix"></div>
                                            <li class="divider"></li>
                                            <li class="dropdown-submenu pull-left">
                                                <a href="#" tabindex="-1"><?php echo _l('customers'); ?></a>
                                                <ul class="dropdown-menu dropdown-menu-left">
                                                    <?php foreach($clients as $cust){ ?>
                                                        <li>
                                                            <a href="#" data-cview="customer_<?php echo $cust['userid']; ?>" onclick="dt_custom_view('customer_<?php echo $cust['userid']; ?>','.table-reminder','customer_<?php echo $cust['userid']; ?>'); return false;"><?php echo $cust['company']; ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <div class="clearfix"></div>
                                    <li class="divider"></li>
                                    <li class="dropdown-submenu pull-left hidden">
                                        <a href="#" tabindex="-1"><?php echo _l('reminder_rel_type'); ?></a>
                                        <ul class="dropdown-menu dropdown-menu-left">
                                            <li>
                                                <a href="#" data-cview="rel_type_quotes" onclick="dt_custom_view('rel_type_quotes','.table-reminder','rel_type_quotes'); return false;"><?php echo _l('rm_proposals'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-cview="rel_type_estimate" onclick="dt_custom_view('rel_type_estimate','.table-reminder','rel_type_estimate'); return false;"><?php echo _l('rm_estimates'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-cview="rel_type_invoice" onclick="dt_custom_view('rel_type_invoice','.table-reminder','rel_type_invoice'); return false;"><?php echo _l('rm_invoices'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-cview="rel_type_credit_note" onclick="dt_custom_view('rel_type_credit_note','.table-reminder','rel_type_credit_note'); return false;"><?php echo _l('rm_credit_notes'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="#" data-cview="rel_type_tickets" onclick="dt_custom_view('rel_type_tickets','.table-reminder','rel_type_tickets'); return false;"><?php echo _l('rm_tickets'); ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php if(count($created_ids) > 0 && is_admin()){ ?>
                    <div class="clearfix"></div>
                    <li class="divider"></li>
                    <li class="dropdown-submenu pull-left">
                        <a href="#" tabindex="-1"><?php echo _l('reminder_created_by_th'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left">
                            <?php foreach($created_ids as $id){ ?>
                                <li>
                                    <a href="#" data-cview="created_by_<?php echo $id['by_staff']; ?>" onclick="dt_custom_view('created_by_<?php echo $id['by_staff']; ?>','.table-reminder','created_by_<?php echo $id['by_staff']; ?>'); return false;"><?php echo $id['full_name']; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </div>
    <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs" onclick="reminder_toggle_small_view('.table-reminder','#reminder'); return false;" data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="row">
   <div class="col-md-12" id="small-table">
      <div class="panel_s">
         <div class="panel-body">
            <?php echo form_hidden('reminderid',$reminderid);
            $table_data = array(
                _l('call_id'),
                _l('reminder_date'),
//                _l('reminder_assigned'),
                _l('branch'),
                _l('branch_manager'),
                _l('call_direction'),
                _l('call_durarion'),
                _l('customer_number'),
                _l('Call_Type'),
                  _l('call_log'),
                // _l('reminder_next_review_date'),
//                _l('actions'),


//                _l('reminder_next_review_date'),
//                _l('reminder_actions'),
            );
            $custom_fields = get_custom_fields('reminder',array('show_on_table'=>1));
            foreach($custom_fields as $field){
                array_push($table_data,$field['name']);
            }
            $table_data = hooks()->apply_filters('reminder_table_columns', $table_data);
            render_datatable($table_data,'reminder',[],[
                'id'                         => 'table-reminder',
                'data-last-order-identifier' => 'reminder',
                'data-default-order'         => get_table_last_order('reminder'),
            ]);
            ?>
        </div>
    </div>
</div>
<div class="col-md-6 small-table-right-col">
    <div id="reminder" class="hide">
    </div>
</div>
</div>
</div>
</div>
</div>
<div id="reminderModalData">
</div>
<?php //$this->load->view('admin/includes/modals/reminder_add_file'); ?>
<div id="reminderViewData" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<script>
    var hidden_columns = [3,4,5];
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
</script>
<?php init_tail(); ?>
<script type="text/javascript" src="<?php echo module_dir_url('phone','assets/manage.js') ?>"></script>
<script>
    $('#date_t').on('change',function(){
        if($('#date_f').val()){
            $('input[name="reminder_filter_date_f"]').val($('#date_f').val());
            $('input[name="reminder_filter_date_t"]').val($('#date_t').val());
            $('.table-reminder').DataTable().ajax.reload(); 
        }
    });
    $('#date_f').on('change',function(){
        if($('#date_t').val()){
            $('input[name="reminder_filter_date_f"]').val($('#date_f').val());
            $('input[name="reminder_filter_date_t"]').val($('#date_t').val());
            $('.table-reminder').DataTable().ajax.reload(); 
        }
    });
    // view_assigned
    $('#view_assigned').on('change',function(){
        if($('#view_assigned').val()){
            $('input[name="reminder_filter_assigned"]').val($('#view_assigned').val());
            // $('input[name="reminder_filter_date_t"]').val($('#date_t').val());
            $('.table-reminder').DataTable().ajax.reload(); 
        }
    });
    function getViewModal(id='')
    {   
        $('body').append('<div class="dt-loader"></div>');
        $.post(admin_url + 'phone/getreminderViewModal', {
            id: id
        }).done(function (response) {
            $('body').find('.dt-loader').remove();
            $("#reminderViewData").html(response);
            $("#reminderViewData").modal('show');
        });
    }


    $(document).ready(function() {
        // Initialize the data table
        var table = $('#your-data-table').DataTable({
            // Add your data table options here
        });

        // Assign row IDs to the data table rows
        $('#table-reminder tbody tr').each(function(index) {
            $(this).attr('id', 'row_' + index);
        });
        // Handle the click event on the "Edit" button
        $('#table-reminder').on('click', '.btn-edit-row', function() {
            var rowId = $(this).data('row-id');
            // alert(rowId);
            // Get the content of the span element by ID
            var feedbackContent = $('#feedback_' + rowId).text(); // Replace '1' with the actual ID you want to retrieve
            // alert(feedbackContent);

// Get the content (value) of the date input element by ID
            var reviewDateValue = $('#review_date_' + rowId).val(); // Replace '1' with the actual ID you want to retrieve
            // alert(reviewDateValue);
            // alert(reviewDateValue);

            // $.ajax({
            //     url: '/api/updateFeedback',
            //     method: 'POST',
            //     data: {
            //         id: rowId,
            //         feedback: feedbackContent,
            //         review_date: reviewDateValue
            //     },
            //     success: function(response) {
            //         console.log(response);
            //         // Handle the success response
            //     },
            //     error: function(error) {
            //         console.log(error);
            //         // Handle the error
            //     }
            // });

            // var settings = {
            //     "url": "/api/updateFeedback",
            //     "method": "POST",
            //     "timeout": 0,
            //     "headers": {
            //         "Content-Type": "application/json"
            //     },
            //     "data": JSON.stringify({
            //         "id": rowId,
            //         "feedback": feedbackContent,
            //         "review_date": reviewDateValue
            //     }),
            // };
            //
            // $.ajax(settings).done(function (response) {
            //     console.log(response);
            //     alert(response);
            // });


            var myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");
            myHeaders.append("Cookie", "csrf_cookie_name=c0c1f6b992e1a06c0db1114b422594c8; sp_session=70da0i0di2egc0drmfrff7qgkje57f17");

            var raw = JSON.stringify({
                "id": rowId,
                "feedback": feedbackContent,
                "review_date": reviewDateValue
            });

            var requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
            };

            fetch("/api/updateFeedback", requestOptions)
                .then(response => response.text())
                .then(result => console.log(result))
                 .then(result => alert("Feedback Updated Successfully"))
                .catch(error => console.log('error', error));
            // Get the row data
            // var rowData = table.row('#lead_' + rowId).data();

            // Implement your custom logic for editing the row data
            // For example, you can show a modal or redirect to an edit page
            // using the row data and rowId

            // Example: Show an alert with the row data
            // alert(JSON.stringify(rowData));
        });


    });

</script>
        
</body>
</html>
