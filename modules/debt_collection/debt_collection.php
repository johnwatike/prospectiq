<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Module Name: Debt Collection
 * Description: Track and manage student fee follow-ups including reminders, reports, and status updates.
 * Version: 1.0.0
 * Author: DevOracle
 */

define('DEBT_COLLECTION_MODULE_NAME', 'debt_collection');

hooks()->add_action('admin_init', 'debt_collection_module_menu');
hooks()->add_action('admin_init', 'debt_collection_permissions');
hooks()->add_action('app_admin_footer', 'debt_collection_load_js');

register_language_files(DEBT_COLLECTION_MODULE_NAME, ['debt_collection']);

hooks()->add_action('admin_init', function () {
    $CI = &get_instance();
    $CI->load->helper('debt_collection/debt_collection');
});

hooks()->add_action('settings_tabs', function ($tabs) {
    $tabs[] = ['name' => _l('debt_collection_settings'), 'view' => DEBT_COLLECTION_MODULE_NAME . '/settings', 'position' => 100];
    return $tabs;
});

function debt_collection_module_menu()
{
    $CI = &get_instance();
    if (is_admin() || has_permission('debt_collection', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('debt_collection', [
            'slug' => 'debt_collection',
            'name' => _l('debt_collection_main_menu'),
            'icon' => 'fa fa-database',
            'href' => admin_url('debt_collection'),
            'position' => 50,
        ]);
        // Sidebar for follow_up
        $CI->app_menu->add_sidebar_children_item('debt_collection', [
            'slug' => 'manage-follow_up',
            'name' => _l('manage_follow_up_menu'),
            'href' => admin_url('debt_collection/follow_up'),
            'position' => 1,
        ]);
        $CI->app_menu->add_sidebar_children_item('debt_collection', [
            'slug' => 'add-follow_up',
            'name' => _l('add_follow_up_menu'),
            'href' => admin_url('debt_collection/follow_up/add'),
            'position' => 2,
        ]);
        // Sidebar for follow_up_note
        $CI->app_menu->add_sidebar_children_item('debt_collection', [
            'slug' => 'manage-follow_up_note',
            'name' => _l('manage_follow_up_note_menu'),
            'href' => admin_url('debt_collection/follow_up_note'),
            'position' => 3,
        ]);
        $CI->app_menu->add_sidebar_children_item('debt_collection', [
            'slug' => 'add-follow_up_note',
            'name' => _l('add_follow_up_note_menu'),
            'href' => admin_url('debt_collection/follow_up_note/add'),
            'position' => 4,
        ]);
    }
}

function debt_collection_load_js()
{
    echo '<script src="' . module_dir_url('debt_collection', 'assets/js/common.js') . '"></script>';
    echo '<script src="' . module_dir_url('debt_collection', 'assets/js/follow_up.js') . '"></script>';
    echo '<script src="' . module_dir_url('debt_collection', 'assets/js/follow_up_note.js') . '"></script>';
}

function debt_collection_permissions()
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
    register_staff_capabilities('debt_collection', $capabilities, _l('debt_collection_permissions_label'));
}

register_activation_hook(DEBT_COLLECTION_MODULE_NAME, 'debt_collection_module_activation_hook');
function debt_collection_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}
