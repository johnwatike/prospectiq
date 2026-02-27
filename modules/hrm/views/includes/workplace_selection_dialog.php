<?php defined('BASEPATH') or exit('No direct script access allowed'); 
$CI = &get_instance();
$CI->load->model('hrm_model');
$workplaces = $CI->hrm_model->get_workplace();
?>
<div class="modal fade" id="workplace_selection_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo _l('select') . ' ' . _l('workplace'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <?php echo _l('please_select_your_workplace'); ?>
                </div>
                <div class="form-group">
                    <label for="workplace_id"><?php echo _l('workplace'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $workplace_options = [];
                    foreach ($workplaces as $workplace) {
                        $workplace_options[] = [
                            'workplace_id' => $workplace['workplace_id'],
                            'workplace_name' => $workplace['workplace_name']
                        ];
                    }
                    echo render_select('workplace_id', $workplace_options, ['workplace_id', 'workplace_name'], '', '', [
                        'data-none-selected-text' => _l('workplace'),
                        'required' => true
                    ]);
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="save_workplace_btn">
                    <i class="fa fa-check"></i> <?php echo _l('save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
