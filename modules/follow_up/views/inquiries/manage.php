<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <a href="<?php echo admin_url('follow_up/inquiries/add'); ?>" class="btn btn-primary pull-right mbot10">
              <?php echo _l('add_inquiries'); ?>
            </a>
            <a href="<?php echo admin_url('follow_up/inquiries/import_view'); ?>" class="btn btn-success pull-right mbot10 mright10">
              <?php echo _l('import_data'); ?>
            </a>
            <h4 class="no-margin"><?php echo _l('inquiries_list'); ?></h4>
            <hr class="hr-panel-heading" />
            <?php render_datatable([
              _l('id'),
              _l('client_name'),
              _l('contact'),
              _l('course'),
              _l('feedback'),
             
              _l('actions')], 'table-inquiries', [], ['data-url' => admin_url('follow_up/inquiries/table_data')]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
