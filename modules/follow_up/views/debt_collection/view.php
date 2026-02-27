<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('view_debt_collection'); ?></h4>
            <hr class="hr-panel-heading" />
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th width="30%"><?php echo _l('branch_name'); ?></th>
                  <td><?php echo isset($record['branch_name']) ? htmlspecialchars($record['branch_name']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('admission_no'); ?></th>
                  <td><?php echo isset($record['admission_no']) ? htmlspecialchars($record['admission_no']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('student_name'); ?></th>
                  <td><?php echo isset($record['student_name']) ? htmlspecialchars($record['student_name']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('registration_date'); ?></th>
                  <td><?php echo isset($record['registration_date']) ? htmlspecialchars($record['registration_date']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('fee'); ?></th>
                  <td><?php echo isset($record['fee']) ? htmlspecialchars($record['fee']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('fee_paid'); ?></th>
                  <td><?php echo isset($record['fee_paid']) ? htmlspecialchars($record['fee_paid']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('fee_balance'); ?></th>
                  <td><?php echo isset($record['fee_balance']) ? htmlspecialchars($record['fee_balance']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('id_no'); ?></th>
                  <td><?php echo isset($record['id_no']) ? htmlspecialchars($record['id_no']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('phone_no'); ?></th>
                  <td><?php echo isset($record['phone_no']) ? htmlspecialchars($record['phone_no']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('course'); ?></th>
                  <td><?php echo isset($record['course']) ? htmlspecialchars($record['course']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('status'); ?></th>
                  <td><?php echo isset($record['status']) ? htmlspecialchars($record['status']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('feedback'); ?></th>
                  <td><?php echo isset($record['feedback']) ? htmlspecialchars($record['feedback']) : ''; ?></td>
                </tr>
              
              </tbody>
            </table>
            <div class="text-right">
              <a href="<?php echo admin_url('follow_up/debt_collection/edit/' . $record['id']); ?>" class="btn btn-primary">
                <i class="fa fa-pencil"></i> <?php echo _l('edit'); ?>
              </a>
              <a href="<?php echo admin_url('follow_up/debt_collection'); ?>" class="btn btn-default">
                <?php echo _l('back_to_list'); ?>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
