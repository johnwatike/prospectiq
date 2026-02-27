<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      
            <?php echo form_open(admin_url('debt_collection/follow_up/edit/' . $record['id'])); ?>
        <div class="col-md-12">
          <div class="panel_s">
            <div class="panel-body">
              <h4 class="no-margin"><?php echo _l('edit_follow_up'); ?></h4>
              <hr class="hr-panel-heading" />
              <div class="row">
                <div class="col-md-6">

                <div class="form-group">
                  <label for="branch_name"><?php echo _l('branch_name'); ?> *</label>
                  <input type="text" name="branch_name" class="form-control" value="<?php echo isset($record) ? $record['branch_name'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="admission_no"><?php echo _l('admission_no'); ?> *</label>
                  <input type="text" name="admission_no" class="form-control" value="<?php echo isset($record) ? $record['admission_no'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="student_name"><?php echo _l('student_name'); ?> *</label>
                  <input type="text" name="student_name" class="form-control" value="<?php echo isset($record) ? $record['student_name'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="registration_date"><?php echo _l('registration_date'); ?> *</label>
                  <input type="date" name="registration_date" class="form-control" value="<?php echo isset($record) ? $record['registration_date'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="fee"><?php echo _l('fee'); ?> *</label>
                  <input type="number" name="fee" class="form-control" value="<?php echo isset($record) ? $record['fee'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="fee_paid"><?php echo _l('fee_paid'); ?> *</label>
                  <input type="number" name="fee_paid" class="form-control" value="<?php echo isset($record) ? $record['fee_paid'] : ''; ?>" required>
                </div>
    
              </div>
              <div class="col-md-6">

                <div class="form-group">
                  <label for="fee_balance"><?php echo _l('fee_balance'); ?> *</label>
                  <input type="number" name="fee_balance" class="form-control" value="<?php echo isset($record) ? $record['fee_balance'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="id_no"><?php echo _l('id_no'); ?> *</label>
                  <input type="text" name="id_no" class="form-control" value="<?php echo isset($record) ? $record['id_no'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="phone_no"><?php echo _l('phone_no'); ?> *</label>
                  <input type="text" name="phone_no" class="form-control" value="<?php echo isset($record) ? $record['phone_no'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="course"><?php echo _l('course'); ?> *</label>
                  <input type="text" name="course" class="form-control" value="<?php echo isset($record) ? $record['course'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="status"><?php echo _l('status'); ?> *</label>
                  <input type="text" name="status" class="form-control" value="<?php echo isset($record) ? $record['status'] : ''; ?>" required>
                </div>
    

                <div class="form-group">
                  <label for="feedback"><?php echo _l('feedback'); ?> *</label>
                  <textarea name="feedback" class="form-control" rows="3"><?php echo isset($record) ? $record['feedback'] : ''; ?></textarea>
                </div>
    
              </div> <!-- end col -->
            </div> <!-- end row -->

            <div class="form-group mtop20">
              <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
              <a href="<?php echo admin_url('debt_collection/follow_up'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

