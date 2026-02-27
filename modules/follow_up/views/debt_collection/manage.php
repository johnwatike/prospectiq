<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <a href="<?php echo admin_url('follow_up/debt_collection/add'); ?>" class="btn btn-primary pull-right mbot10">
              <?php echo _l('add_debt_collection'); ?>
            </a>
            <a href="<?php echo admin_url('follow_up/debt_collection/import_view'); ?>" class="btn btn-success pull-right mbot10 mright10">
              <?php echo _l('import_data'); ?>
            </a>
            <h4 class="no-margin"><?php echo _l('debt_collection_list'); ?></h4>
            <hr class="hr-panel-heading" />
            <?php render_datatable([
              _l('id'),
              _l('branch_name'),
              _l('admission_no'),
              _l('student_name'),
              _l('registration_date'),
              _l('fee'),
              _l('fee_paid'),
              _l('fee_balance'),
              _l('id_no'),
              _l('phone_no'),
              _l('course'),
              _l('status'),
              _l('feedback'),
            
              _l('actions')], 'table-debt_collection', [], ['data-url' => admin_url('follow_up/debt_collection/table_data')]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
