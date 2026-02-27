<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      
            <?php echo form_open(admin_url('debt_collection/follow_up_note/add')); ?>
        <div class="col-md-6">
          <div class="panel_s">
            <div class="panel-body">
              <h4 class="no-margin"><?php echo _l('add_follow_up_note'); ?></h4>
              <hr class="hr-panel-heading" />
              <div class="row">
                <div class="col-md-12">

                <div class="form-group">
                  <label for="follow_up_id"><?php echo _l('follow_up_id'); ?> *</label>
                  <input type="number" name="follow_up_id" class="form-control" value="<?php echo isset($record) ? $record['follow_up_id'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="note"><?php echo _l('note'); ?> *</label>
                  <textarea name="note" class="form-control" rows="3"><?php echo isset($record) ? $record['note'] : ''; ?></textarea>
                </div>
    

                <div class="form-group">
                  <label for="created_by"><?php echo _l('created_by'); ?> *</label>
                  <input type="number" name="created_by" class="form-control" value="<?php echo isset($record) ? $record['created_by'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="created_at"><?php echo _l('created_at'); ?> *</label>
                  <input type="date" name="created_at" class="form-control" value="<?php echo isset($record) ? $record['created_at'] : ''; ?>" required>
                </div>
    
              </div> <!-- end col -->
            </div> <!-- end row -->

            <div class="form-group mtop20">
              <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
              <a href="<?php echo admin_url('debt_collection/follow_up_note'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

