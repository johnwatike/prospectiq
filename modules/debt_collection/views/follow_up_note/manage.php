<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <a href="<?php echo admin_url('debt_collection/follow_up_note/add'); ?>" class="btn btn-primary pull-right mbot10">
              <?php echo _l('add_follow_up_note'); ?>
            </a>
            <a href="<?php echo admin_url('debt_collection/follow_up_note/import_view'); ?>" class="btn btn-success pull-right mbot10 mright10">
              <?php echo _l('import_data'); ?>
            </a>
            <h4 class="no-margin"><?php echo _l('follow_up_note_list'); ?></h4>
            <hr class="hr-panel-heading" />
            <?php render_datatable([
              _l('id'),
              _l('follow_up_id'),
              _l('note'),
              _l('created_by'),
              _l('created_at'),
              _l('actions')], 'table-follow_up_note', [], ['data-url' => admin_url('debt_collection/follow_up_note/table_data')]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
