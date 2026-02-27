<?php
defined('BASEPATH') or exit('No direct script access allowed');

class debt_collection_utils
{
    public function log_action($msg)
    {
        $upperName = strtoupper('debt_collection');
        log_activity('[' . $upperName . '] ' . $msg);
    }
}
