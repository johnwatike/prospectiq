<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin"><?php echo _l('view_inquiries'); ?></h4>
            <hr class="hr-panel-heading" />
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th width="30%"><?php echo _l('client_name'); ?></th>
                  <td><?php echo isset($record['client_name']) ? htmlspecialchars($record['client_name']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('contact'); ?></th>
                  <td><?php echo isset($record['contact']) ? htmlspecialchars($record['contact']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('course'); ?></th>
                  <td><?php echo isset($record['course']) ? htmlspecialchars($record['course']) : ''; ?></td>
                </tr>
                <tr>
                  <th width="30%"><?php echo _l('feedback'); ?></th>
                  <td><?php echo isset($record['feedback']) ? htmlspecialchars($record['feedback']) : ''; ?></td>
                </tr>
               
              </tbody>
            </table>
            <div class="text-right">
              <a href="<?php echo admin_url('follow_up/inquiries/edit/' . $record['id']); ?>" class="btn btn-primary">
                <i class="fa fa-pencil"></i> <?php echo _l('edit'); ?>
              </a>
              <a href="<?php echo admin_url('follow_up/inquiries'); ?>" class="btn btn-default">
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
