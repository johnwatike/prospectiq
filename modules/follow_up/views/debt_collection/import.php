<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('import_data'); ?></h4>
            <hr class="hr-panel-heading" />
            <?php echo form_open_multipart(admin_url('follow_up/debt_collection/import'), ['id' => 'import-form']); ?>
            <div class="form-group">
              <label for="import_file"><?php echo _l('choose_excel_file'); ?> (.xlsx)</label>
              <input type="file" name="import_file" class="form-control" accept=".xlsx" required>
            </div>
            <button type="submit" class="btn btn-success"><?php echo _l('import'); ?></button>
            <a href="<?php echo admin_url('follow_up/debt_collection'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
