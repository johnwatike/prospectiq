<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php echo form_open(admin_url('phone/settings')); ?>
<div class="row">
    <div class="col-md-12">
        <h4 class="no-margin"><?php echo _l('phone_settings'); ?></h4>
        <hr class="hr-panel-heading" />
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php
        $attrs = ['required' => true];
        echo render_input('settings[phone_api_key]', 'phone_api_key', get_option('phone_api_key'), 'text', $attrs);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php
        $attrs = ['required' => true];
        echo render_input('settings[phone_username]', 'phone_username', get_option('phone_username'), 'text', $attrs);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php
        $attrs = ['required' => true];
        echo render_input('settings[phone_number]', 'phone_number', get_option('phone_number'), 'text', $attrs);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
    </div>
</div>

<?php echo form_close(); ?>
