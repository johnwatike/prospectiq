<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Phone
Description: Default module for sending phone
Version: 2.3.0
Requires at least: 2.3.*
*/

require(__DIR__ . '/vendor/autoload.php');

define('PHONE_MODULE_NAME', 'phone');

hooks()->add_action('after_cron_run', 'phone_send');
hooks()->add_action('admin_init', 'phone_module_init_menu_items');
hooks()->add_action('admin_init', 'phone_permissions');
hooks()->add_action('admin_init', 'phone_add_settings_tab');
hooks()->add_action('after_cron_settings_last_tab', 'phone_cron_settings_tab');
hooks()->add_action('after_cron_settings_last_tab_content', 'phone_cron_settings_tab_content');
hooks()->add_action('contact_deleted', 'phone_contact_deleted_hook', 10, 2);

hooks()->add_filter('numbers_of_features_using_cron_job', 'phone_numbers_of_features_using_cron_job');
hooks()->add_filter('used_cron_features', 'phone_used_cron_features');
hooks()->add_filter('migration_tables_to_replace_old_links', 'phone_migration_tables_to_replace_old_links');
hooks()->add_filter('global_search_result_query', 'phone_global_search_result_query', 10, 3);
hooks()->add_filter('global_search_result_output', 'phone_global_search_result_output', 10, 2);

function phone_global_search_result_output($output, $data)
{
    if ($data['type'] == 'phone') {
        $output = '<a href="' . admin_url('phone/phone/' . $data['result']['surveyid']) . '">' . $data['result']['subject'] . '</a>';
    }

    return $output;
}

function phone_global_search_result_query($result, $q, $limit)
{
    $CI = &get_instance();
    if (has_permission('phone', '', 'view')) {
        // Surveys
        $CI->db->select()
        ->from(db_prefix() . 'phone')
        ->like('subject', $q)
        ->or_like('slug', $q)
        ->or_like('description', $q)
        ->or_like('viewdescription', $q)
        ->limit($limit);

        $CI->db->order_by('subject', 'ASC');

        $result[] = [
            'result'         => $CI->db->get()->result_array(),
            'type'           => 'phone',
            'search_heading' => _l('phone'),
        ];
    }

    return $result;
}

function phone_contact_deleted_hook($id, $contact)
{
    $CI = &get_instance();
    $CI->db->where('email', $contact->email);
    $CI->db->delete(db_prefix() . 'surveysemailsendcron');
    if (is_gdpr()) {
        $CI->db->where('ip', $contact->last_ip);
        $CI->db->delete(db_prefix() . 'surveyresultsets');
    }
}

function phone_cron_settings_tab()
{
    get_instance()->load->view('phone/settings_tab');
}

function phone_cron_settings_tab_content()
{
    get_instance()->load->view('phone/settings_tab_content');
}

function phone_add_settings_tab()
{
    if (staff_can('view', 'settings')) {
        $CI = &get_instance();
        $CI->app->add_settings_section_child('other', 'phone-settings', [
            'name'     => _l('phone_settings'),
            'view'     => 'phone/phone_settings',
            'position' => 50,
        ]);
    }
}

function phone_migration_tables_to_replace_old_links($tables)
{
    $tables[] = [
        'table' => db_prefix() . 'surveys',
        'field' => 'description',
    ];
    $tables[] = [
        'table' => db_prefix() . 'surveys',
        'field' => 'viewdescription',
    ];

    return $tables;
}

function phone_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('phone', $capabilities, _l('phone'));
}

function phone_numbers_of_features_using_cron_job($number)
{
    $feature = total_rows(db_prefix() . 'surveysemailsendcron');
    $number += $feature;

    return $number;
}

function phone_used_cron_features($features)
{
    $feature = total_rows(db_prefix() . 'surveysemailsendcron');
    if ($feature > 0) {
        array_push($features, 'Surveys');
    }

    return $features;
}

function phone_send($cronManuallyInvoked)
{
    $CI = &get_instance();
    $CI->load->library(PHONE_MODULE_NAME . '/' . 'surveys_module');
    $CI->surveys_module->send($cronManuallyInvoked);
}

/**
* Register activation module hook
*/
register_activation_hook(PHONE_MODULE_NAME, 'phone_module_activation_hook');

function phone_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(PHONE_MODULE_NAME, [PHONE_MODULE_NAME]);

/**
* Init surveys module menu items in setup in admin_init hook
* @return null
*/
function phone_module_init_menu_items()
{
    $CI = &get_instance();
// $CI->app_menu->add_sidebar_menu_item('Phone', [
// 	 		'name'     => _l('Phone  Management'),
// 	 	 'icon'       => 'fa-solid fa-phone', 
// 	 		'position' => 5,
// 	 	]);
// $CI->app_menu->add_sidebar_menu_item('phone', [
// 	 		'name'     => _l('hr_hr_profile'),
// 	 		'icon'     => 'fa fa-users', 
// 	 		'position' => 5,
// 	 	]);
    // $CI->app->add_quick_actions_link([
    //     'name'       => _l('phonex'),
    //     'permission' => 'phone',
    //     'url'        => 'phone/phone',
    //     'position'   => 69,
    //     'icon'       => 'fa-regular fa-circle-question',
    // ]);
    //  if (has_permission('phone', '', 'edit')) {
    // $CI->app->add_quick_actions_link([
    //     'name'       => _l('phone_history'),
    //     'permission' => 'phone',
    //     'url'        => 'phone/phone_history',
    //     'position'   => 00,
    //     'icon'       => 'fa-regular fa-circle-question',
    // ]);
    //  }
    // $CI->app_menu->add_sidebar_menu_item([
    //     'name'       => _l('phone_history'),
    //     'permission' => 'phone',
    //     'url'        => 'phone/phone_history',
    //     'position'   => 00,
    //     'icon'       => 'fa-regular fa-circle-question',
    // ]);


    // if (has_permission('phone', '', 'view')) {
    //     $CI->app_menu->add_sidebar_children_item('Phone', [
    //         'slug'     => 'phone',
    //         'name'     => _l('Soft Phone'),
    //         'href'     => admin_url('phone'),
    //         'position' => 00,
    //     ]);
    // }
    
    // // if (has_permission('phone', '', 'edit')) {
    //     $CI->app_menu->add_sidebar_menu_item( [
    //         'slug'     => 'phone_history',
    //         'name'     => _l('Call History'),
    //         'href'     => admin_url('phone/phone_history'),
    //         'position' => 0,
            
    //     ]);
    // }
    
    	if (has_permission('phone', '', 'view')) {
		$CI->app_menu->add_sidebar_menu_item('phone_manager', [
			'slug'     => 'phone_manager',
			'name'     => _l('phone_manager'),
			'position' => 5,
			'icon'     => 'fa fa-sitemap',
			'href'     => admin_url('lead_manager')
		]);
		$CI->app_menu->add_sidebar_children_item('phone_manager', [
			'slug'     => 'soft_phone', 
			'name'     => _l('soft_phone'),
			'href'     => admin_url('phone'), 
			'position' => 5,
		]);
		if (has_permission('phone', '', 'edit')) {
		$CI->app_menu->add_sidebar_children_item('phone_manager', [
			'slug'     => 'phone_history',  
			'name'     => _l('phone_history'),
			'href'     => admin_url('phone/phone_history'),
			'position' => 5,
		]); 
		}

// 		$CI->app_menu->add_sidebar_children_item('lead_manager', [
// 			'slug'     => 'lead_manager_leads', 
// 			'name'     => _l('lead_manager_lead'),
// 			'href'     => admin_url('lead_manager'), 
// 			'position' => 5,
// 		]);	
	}
    
}
/**
* Helper function to get text question answers
* @param  integer $questionid
* @param  itneger $surveyid
* @return array
*/
function phone_get_text_question_answers($questionid, $surveyid)
{
    $CI = & get_instance();
    $CI->db->select('answer,resultid');
    $CI->db->from(db_prefix() . 'form_results');
    $CI->db->where('questionid', $questionid);
    $CI->db->where('rel_id', $surveyid);
    $CI->db->where('rel_type', 'survey');

    return $CI->db->get()->result_array();
}