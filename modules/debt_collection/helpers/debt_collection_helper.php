<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Add your custom helper functions here
function debt_collection_format_date($date)
{
    return date('d M Y', strtotime($date));
}
