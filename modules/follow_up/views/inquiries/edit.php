<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      
            <?php echo form_open(admin_url('follow_up/inquiries/edit/' . $record['id'])); ?>
        <div class="col-md-12">
          <div class="panel_s">
            <div class="panel-body">
              <h4 class="no-margin"><?php echo _l('edit_inquiries'); ?></h4>
              <hr class="hr-panel-heading" />
              <div class="row">
                <div class="col-md-6">

                <div class="form-group">
                  <label for="client_name"><?php echo _l('client_name'); ?> *</label>
                  <input type="text" name="client_name" class="form-control" value="<?php echo isset($record) ? $record['client_name'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="contact"><?php echo _l('contact'); ?> *</label>
                  <input type="text" name="contact" class="form-control" value="<?php echo isset($record) ? $record['contact'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="course"><?php echo _l('course'); ?> *</label>
                  <input type="text" name="course" class="form-control" value="<?php echo isset($record) ? $record['course'] : ''; ?>" required>
                </div>
    
              </div>
              <div class="col-md-6">

                <div class="form-group">
                  <label for="feedback"><?php echo _l('feedback'); ?> *</label>
                  <textarea name="feedback" class="form-control" rows="3"><?php echo isset($record) ? $record['feedback'] : ''; ?></textarea>
                </div>
    

               
    
              </div> <!-- end col -->
            </div> <!-- end row -->

            <div class="form-group mtop20">
              <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
              <a href="<?php echo admin_url('follow_up/inquiries'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

