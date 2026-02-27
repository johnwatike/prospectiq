<?php
defined('BASEPATH') or exit('No direct script access allowed');

class follow_up_utils
{
    public function log_action($msg)
    {
        $upperName = strtoupper('follow_up');
        log_activity('[' . $upperName . '] ' . $msg);
    }
}
