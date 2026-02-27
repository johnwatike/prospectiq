<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Module Name: Follow Up
 * Description: Track and manage student fee follow-ups including reminders, reports, and status updates.
 * Version: 1.0.0
 * Author: DevOracle
 */

define('FOLLOW_UP_MODULE_NAME', 'follow_up');

hooks()->add_action('admin_init', 'follow_up_module_menu');
hooks()->add_action('admin_init', 'follow_up_permissions');
hooks()->add_action('app_admin_footer', 'follow_up_load_js');

register_language_files(FOLLOW_UP_MODULE_NAME, ['follow_up']);

hooks()->add_action('admin_init', function () {
    $CI = &get_instance();
    $CI->load->helper('follow_up/follow_up');
});

hooks()->add_action('settings_tabs', function ($tabs) {
    $tabs[] = ['name' => _l('follow_up_settings'), 'view' => FOLLOW_UP_MODULE_NAME . '/settings', 'position' => 100];
    return $tabs;
});

function follow_up_module_menu()
{
    $CI = &get_instance();
    if (is_admin() || has_permission('follow_up', '', 'view')|| has_permission('follow_up', '', 'view_own')) {
        $CI->app_menu->add_sidebar_menu_item('follow_up', [
            'slug' => 'follow_up',
            'name' => _l('follow_up_main_menu'),
            'icon' => 'fa fa-database',
            'href' => admin_url('follow_up'),
            'position' => 50,
        ]);
        // Sidebar for debt_collection
        $CI->app_menu->add_sidebar_children_item('follow_up', [
            'slug' => 'manage-debt_collection',
            'name' => _l('manage_debt_collection_menu'),
            'href' => admin_url('follow_up/debt_collection'),
            'position' => 1,
        ]);
        $CI->app_menu->add_sidebar_children_item('follow_up', [
            'slug' => 'add-debt_collection',
            'name' => _l('add_debt_collection_menu'),
            'href' => admin_url('follow_up/debt_collection/add'),
            'position' => 2,
        ]);
        // Sidebar for inquiries
        $CI->app_menu->add_sidebar_children_item('follow_up', [
            'slug' => 'manage-inquiries',
            'name' => _l('manage_inquiries_menu'),
            'href' => admin_url('follow_up/inquiries'),
            'position' => 3,
        ]);
        $CI->app_menu->add_sidebar_children_item('follow_up', [
            'slug' => 'add-inquiries',
            'name' => _l('add_inquiries_menu'),
            'href' => admin_url('follow_up/inquiries/add'),
            'position' => 4,
        ]);
    }
}

function follow_up_load_js()
{
    // Load Vue.js before the module scripts if not already loaded
    echo '<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>';
    echo '<script src="' . module_dir_url('follow_up', 'assets/js/common.js') . '"></script>';
    echo '<script src="' . module_dir_url('follow_up', 'assets/js/debt_collection.js') . '"></script>';
    echo '<script src="' . module_dir_url('follow_up', 'assets/js/inquiries.js') . '"></script>';
}

function follow_up_permissions()
{
    $capabilities = [
        'capabilities' => [
            'view'      => _l('permission_view'),
            'view_own'  => _l('permission_view_own'),
            'create'    => _l('permission_create'),
            'edit'      => _l('permission_edit'),
            'delete'    => _l('permission_delete'),
        ]
    ];
    register_staff_capabilities('follow_up', $capabilities, _l('follow_up_permissions_label'));
}

register_activation_hook(FOLLOW_UP_MODULE_NAME, 'follow_up_module_activation_hook');
function follow_up_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}
