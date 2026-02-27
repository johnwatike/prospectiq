<?php
defined('BASEPATH') or exit('No direct script access allowed');

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class Poly_utilities extends AdminController
{
    private $CI;
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        
        poly_utilities_common_helper::debug_reset();
    }

    /**
     * Scripts
     * @return view
     */
    public function scripts()
    {
        $data['title'] = _l('poly_utilities_scripts_extend');
        $this->load->view('scripts/manage', $data);
    }

    /**
     * Add Scripts
     * @return view
     */ //
    public function scripts_add()
    {
        $data['title'] = (isset($_GET['id'])) ? _l('poly_utilities_scripts_update_extend') : _l('poly_utilities_scripts_add_extend');
        $this->load->view('scripts/create', $data);
    }

    /**
     * Styles
     * @return view
     */
    public function styles()
    {
        $data['title'] = _l('poly_utilities_styles_extend');
        $this->load->view('styles/manage', $data);
    }

    /**
     * Add Styles
     * @return view
     */
    public function styles_add()
    {
        $data['title'] = (isset($_GET['id'])) ? _l('poly_utilities_styles_update_extend') : _l('poly_utilities_styles_add_extend');
        $this->load->view('styles/create', $data);
    }

    /**
     * Quick Access Menu
     * @return view
     */
    public function quick_access()
    {
        $data['title'] = _l('poly_utilities_shortcut_menu_extend');
        $this->load->view('quick_access/manage', $data);
    }

    /**
     * Custom Menu
     * @return view
     */
    public function custom_menu()
    {
        staff_can_poly_utilities_custom_menu();

        // View display tab by: menu settup, menu clients, menu sidebar.
        $tab_menu = $this->input->get('menu');

        if ($tab_menu == 'setup') {
            $data['title'] = _l('poly_utilities_custom_setup_menu_extend');
            $data['active'] = 'setup';
            $this->load->view('custom_menu/menu_setup', $data);
        } elseif ($tab_menu == 'clients') {
            $data['title'] = _l('poly_utilities_custom_clients_menu_extend');
            $data['active'] = 'clients';
            $this->load->view('custom_menu/menu_clients', $data);
        } else {
            hooks()->remove_filter('sidebar_menu_items', 'app_poly_admin_sidebar_custom_options', 999);
            $data['title'] = _l('poly_utilities_custom_sidebar_menu_extend');
            $data['active'] = 'sidebar';
            $this->load->view('custom_menu/manage', $data);
        }
    }

    /**
     * Re init configs
     */
    public function ajax_reinit_configs()
    {
        $data = $this->input->post('data');
        // Refresh register the routes and hoooks
        poly_utilities_common_helper::require_in_file(APPPATH . 'config/my_routes.php', "FCPATH.'modules/" . POLY_UTILITIES_MODULE_NAME . "/config/my_routes.php'");
        poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
    }

    /**
     * Retrieve the list of sidebar menus + custom sidebar menus
     */
    public function ajax_sidebar_menu_items()
    {
        $items = $this->app_menu->get_sidebar_menu_items();
        array_unshift($items, ['name' => 'Root', 'slug' => 'root', 'href' => '#']);
        header('Content-Type: application/json');
        echo json_encode($items);
        exit();
    }

    /**
     * Retrieve the list of custom sidebar menus
     */
    public function ajax_custom_sidebar_menu_items()
    {
        $data = get_option(POLY_MENU_SIDEBAR_CUSTOM_ACTIVE);
        $data = $data ? $data : '[]';
        header('Content-Type: application/json');
        echo $data;
        exit();
    }

    /**
     * Retrieve the list of setup menus
     */
    public function ajax_setup_menu_items()
    {
        $items = $this->app_menu->get_setup_menu_items();
        array_unshift($items, ['name' => 'Root', 'slug' => 'root', 'href' => '#']);
        header('Content-Type: application/json');
        echo json_encode($items);
        exit();
    }

    /**
     * Retrieve the list of custom setup menus
     */
    public function ajax_custom_setup_menu_items()
    {
        $data = get_option(POLY_MENU_SETUP_CUSTOM_ACTIVE);
        $data = $data ? $data : '[]';
        header('Content-Type: application/json');
        echo $data;
        exit();
    }

    /**
     * Retrieve the list of clients menus
     */
    public function ajax_client_menu_items()
    {
        hooks()->do_action('clients_init');

        $clients_menu_items = poly_get_clients_menu_items(); //  $this->app_menu->get_theme_items();//
        array_unshift($clients_menu_items, ['name' => 'Root', 'slug' => 'root', 'href' => '#']);
        header('Content-Type: application/json');
        echo json_encode($clients_menu_items);
        exit();
    }

    /**
     * Retrieve the list of custom clients menus
     */
    public function ajax_custom_clients_menu_items()
    {
        $data = get_option(POLY_MENU_CLIENTS_CUSTOM_ACTIVE);
        $data = $data ? $data : '[]';
        header('Content-Type: application/json');
        echo $data;
        exit();
    }

    /**
     * Retrieve the list of roles
     */
    public function ajax_roles()
    {
        $this->load->model("Roles_model");
        $data = $this->Roles_model->get();

        $data_slim_objects = array_map(function ($role) {
            return [
                'roleid' => $role['roleid'],
                'name' => $role['name']
            ];
        }, $data);

        $data_slim_objects = $data_slim_objects ? $data_slim_objects : [];
        header('Content-Type: application/json');
        echo json_encode($data_slim_objects);
        exit();
    }

    /**
     * Retrieve the list of clients based on search keywords.
     */
    public function ajax_clients_search()
    {
        $result = [];
        if (isset($_GET['search'])) {
            $search_keywords = $_GET['search'];
            $this->db->select('userid, company, address, phonenumber');
            $this->db->from(db_prefix() . 'clients');
            $this->db->group_start();
            $this->db->like('company', $search_keywords);
            $this->db->or_like('address', $search_keywords);
            $this->db->or_like('phonenumber', $search_keywords);
            $this->db->or_like('address', $search_keywords);
            $this->db->group_end();
            $this->db->order_by('company', 'ASC');
            $result = $this->db->get()->result_array();
            unset($value);
        }
        header('Content-Type: application/json');
        echo json_encode($result);
        exit();
    }

    /**
     * Retrieve the list of users based on search keywords.
     */
    public function ajax_users_search()
    {
        $result = [];
        if (isset($_GET['search'])) {
            $search_keywords = $_GET['search'];
            if (has_permission('staff', '', 'view')) {
                $this->db->select('staffid, firstname, lastname');
                $this->db->from(db_prefix() . 'staff');
                $this->db->group_start();
                $this->db->like('firstname', $search_keywords);
                $this->db->or_like('lastname', $search_keywords);
                $this->db->or_like("CONCAT(firstname, ' ', lastname)", $search_keywords, false);
                $this->db->or_like("CONCAT(lastname, ' ', firstname)", $search_keywords, false);
                $this->db->or_like('phonenumber', $search_keywords);
                $this->db->or_like('email', $search_keywords);
                $this->db->group_end();
                $this->db->order_by('firstname', 'ASC');
                $result = $this->db->get()->result_array();

                foreach ($result as $key => &$value) {
                    $value['avatar'] = staff_profile_image_url($value['staffid']);
                }
                unset($value);
            }
        }
        header('Content-Type: application/json');
        echo json_encode($result);
        exit();
    }

    /**
     * Update the order of sidebar menu items.
     */
    public function update_sidebar_menu_positions()
    {
        $full_menu_items = $this->input->post('data');
        update_option(POLY_MENU_SIDEBAR, json_encode($full_menu_items));
        poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
    }

    /**
     * Add or update a custom sidebar menu.
     */
    public function update_custom_sidebar_menu()
    {
        if ($this->input->post()) {
            $menu_item = $this->input->post();
            $isEdit = $menu_item['is_edit'];
            unset($menu_item['is_edit']);
            if ($isEdit !== 'true') {
                $menu_items_custom = get_option(POLY_MENU_SIDEBAR_CUSTOM_ACTIVE);
                $menu_items_custom = poly_utilities_common_helper::json_decode($menu_items_custom, TRUE);

                $menu_object = poly_create_menu_item($menu_item);

                $menu_items_custom[] = $menu_object;

                update_option(POLY_MENU_SIDEBAR_CUSTOM_ACTIVE, json_encode($menu_items_custom));

                $custom_items_position = get_option(POLY_MENU_SIDEBAR);

                if (!empty($custom_items_position)) {
                    $custom_items_position = poly_utilities_common_helper::json_decode($custom_items_position, TRUE);

                    if ($menu_object['parent_slug'] === 'root') {
                        array_unshift($custom_items_position, $menu_object);
                        update_option(POLY_MENU_SIDEBAR, json_encode($custom_items_position));
                    } else {
                        foreach ($custom_items_position as &$item) {
                            if ($item['slug'] === $menu_object['parent_slug']) {
                                if (!array_key_exists('children', $item)) {
                                    $item['children'] = array();
                                }
                                array_push($item['children'], $menu_object);
                            }
                        }
                        unset($item);
                        update_option(POLY_MENU_SIDEBAR, json_encode($custom_items_position));
                    }
                }
                poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
                set_alert('success', _l('poly_utilities_response_add_success'));
            } else {
                $menu_items_custom = poly_utilities_common_helper::json_decode(get_option(POLY_MENU_SIDEBAR_CUSTOM_ACTIVE), TRUE);
                poly_utilities_menu_sidebar_update($menu_items_custom, $menu_item);
                update_option(POLY_MENU_SIDEBAR_CUSTOM_ACTIVE, json_encode($menu_items_custom));

                $menu_items = poly_utilities_common_helper::json_decode(get_option(POLY_MENU_SIDEBAR), TRUE);
                poly_utilities_menu_sidebar_update($menu_items, $menu_item);
                update_option(POLY_MENU_SIDEBAR, json_encode($menu_items));

                poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_update_success'));
                set_alert('success', _l('poly_utilities_response_update_success'));
            }
            exit();
        }
    }

    /**
     * Delete a custom sidebar menu.
     */
    public function delete_custom_sidebar_menu()
    {
        $this->db->trans_begin();

        foreach (['id'] as $input_object) {
            $$input_object = $this->input->post($input_object, FALSE);
        }

        $rest_delete_in_sidebar = poly_utilities_delete_custom_sidebar_menu_by_id($id, POLY_MENU_SIDEBAR);
        $rest_delete_in_custom_sidebar = poly_utilities_delete_custom_sidebar_menu_by_id($id, POLY_MENU_SIDEBAR_CUSTOM_ACTIVE);

        if ($rest_delete_in_sidebar && $rest_delete_in_custom_sidebar) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                poly_utilities_ajax_response_helper::response_error("Error occurred while deleting.");
            } else {
                $this->db->trans_commit();
                poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
            }
        } else {
            $this->db->trans_rollback();
            poly_utilities_ajax_response_helper::response_error("Error occurred while deleting.");
        }
    }

    /**
     * Update the order of setup menu items.
     */
    public function update_setup_menu_positions()
    {
        $full_menu_items = $this->input->post('data');
        update_option(POLY_MENU_SETUP, json_encode($full_menu_items));
        poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
    }

    public function update_custom_setup_menu()
    {
        if ($this->input->post()) {
            $menu_item = $this->input->post();
            $isEdit = $menu_item['is_edit'];
            unset($menu_item['is_edit']);
            if ($isEdit !== 'true') {
                $menu_items_custom = get_option(POLY_MENU_SETUP_CUSTOM_ACTIVE);
                $menu_items_custom = poly_utilities_common_helper::json_decode($menu_items_custom, TRUE);

                $menu_object = poly_create_menu_item($menu_item);

                $menu_items_custom[] = $menu_object;

                update_option(POLY_MENU_SETUP_CUSTOM_ACTIVE, json_encode($menu_items_custom));

                $custom_items_position = get_option(POLY_MENU_SETUP);

                if (!empty($custom_items_position)) {
                    $custom_items_position = poly_utilities_common_helper::json_decode($custom_items_position, TRUE);

                    if ($menu_object['parent_slug'] === 'root') {
                        array_unshift($custom_items_position, $menu_object);
                        update_option(POLY_MENU_SETUP, json_encode($custom_items_position));
                    } else {
                        foreach ($custom_items_position as &$item) {
                            if ($item['slug'] === $menu_object['parent_slug']) {
                                if (!array_key_exists('children', $item)) {
                                    $item['children'] = array();
                                }
                                array_push($item['children'], $menu_object);
                            }
                        }
                        unset($item);
                        update_option(POLY_MENU_SETUP, json_encode($custom_items_position));
                    }
                }
                poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
                set_alert('success', _l('poly_utilities_response_add_success'));
            } else {
                $menu_items_custom = poly_utilities_common_helper::json_decode(get_option(POLY_MENU_SETUP_CUSTOM_ACTIVE), TRUE);
                poly_utilities_menu_sidebar_update($menu_items_custom, $menu_item);
                update_option(POLY_MENU_SETUP_CUSTOM_ACTIVE, json_encode($menu_items_custom));

                $menu_items = poly_utilities_common_helper::json_decode(get_option(POLY_MENU_SETUP), TRUE);
                poly_utilities_menu_sidebar_update($menu_items, $menu_item);
                update_option(POLY_MENU_SETUP, json_encode($menu_items));

                poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_update_success'));
                set_alert('success', _l('poly_utilities_response_update_success'));
            }
            exit();
        }
    }

    /**
     * Delete a custom setup menu.
     */
    public function delete_custom_setup_menu()
    {
        $this->db->trans_begin();

        foreach (['id'] as $input_object) {
            $$input_object = $this->input->post($input_object, FALSE);
        }

        $rest_delete_in_sidebar = poly_utilities_delete_custom_sidebar_menu_by_id($id, POLY_MENU_SETUP);
        $rest_delete_in_custom_sidebar = poly_utilities_delete_custom_sidebar_menu_by_id($id, POLY_MENU_SETUP_CUSTOM_ACTIVE);

        if ($rest_delete_in_sidebar && $rest_delete_in_custom_sidebar) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                poly_utilities_ajax_response_helper::response_error("Error occurred while deleting.");
            } else {
                $this->db->trans_commit();
                poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
            }
        } else {
            $this->db->trans_rollback();
            poly_utilities_ajax_response_helper::response_error("Error occurred while deleting.");
        }
    }

    /**
     * Update the order of clients menu items.
     */
    public function update_clients_menu_positions()
    {
        $full_menu_items = $this->input->post('data');
        update_option(POLY_MENU_CLIENTS, json_encode($full_menu_items));
        poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
    }

    /**
     * Add or update a custom clients menu.
     */
    public function update_custom_clients_menu()
    {
        if ($this->input->post()) {
            $menu_item = $this->input->post();
            $isEdit = $menu_item['is_edit'];
            unset($menu_item['is_edit']);
            if ($isEdit !== 'true') {
                $menu_items_custom = get_option(POLY_MENU_CLIENTS_CUSTOM_ACTIVE);
                $menu_items_custom = poly_utilities_common_helper::json_decode($menu_items_custom, TRUE);

                $menu_object = poly_create_menu_item($menu_item);

                $menu_items_custom[] = $menu_object;

                update_option(POLY_MENU_CLIENTS_CUSTOM_ACTIVE, json_encode($menu_items_custom));

                $custom_items_position = get_option(POLY_MENU_CLIENTS);

                if (!empty($custom_items_position)) {
                    $custom_items_position = poly_utilities_common_helper::json_decode($custom_items_position, TRUE);

                    if ($menu_object['parent_slug'] === 'root') {
                        array_unshift($custom_items_position, $menu_object);
                        update_option(POLY_MENU_CLIENTS, json_encode($custom_items_position));
                    } else {
                        foreach ($custom_items_position as &$item) {
                            if ($item['slug'] === $menu_object['parent_slug']) {
                                if (!array_key_exists('children', $item)) {
                                    $item['children'] = array();
                                }
                                array_push($item['children'], $menu_object);
                            }
                        }
                        unset($item);
                        update_option(POLY_MENU_CLIENTS, json_encode($custom_items_position));
                    }
                }
                poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
                set_alert('success', _l('poly_utilities_response_add_success'));
            } else {
                $menu_items_custom = poly_utilities_common_helper::json_decode(get_option(POLY_MENU_CLIENTS_CUSTOM_ACTIVE), TRUE);
                poly_utilities_menu_sidebar_update($menu_items_custom, $menu_item);
                update_option(POLY_MENU_CLIENTS_CUSTOM_ACTIVE, json_encode($menu_items_custom));

                $menu_items = poly_utilities_common_helper::json_decode(get_option(POLY_MENU_CLIENTS), TRUE);
                poly_utilities_menu_sidebar_update($menu_items, $menu_item);
                update_option(POLY_MENU_CLIENTS, json_encode($menu_items));

                poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_update_success'));
                set_alert('success', _l('poly_utilities_response_update_success'));
            }
            exit();
        }
    }

    /**
     * Delete a custom sidebar menu.
     */
    public function delete_custom_clients_menu()
    {
        $this->db->trans_begin();

        foreach (['id'] as $input_object) {
            $$input_object = $this->input->post($input_object, FALSE);
        }

        $rest_delete_in_sidebar = poly_utilities_delete_custom_sidebar_menu_by_id($id, POLY_MENU_CLIENTS);
        $rest_delete_in_custom_sidebar = poly_utilities_delete_custom_sidebar_menu_by_id($id, POLY_MENU_CLIENTS_CUSTOM_ACTIVE);

        if ($rest_delete_in_sidebar && $rest_delete_in_custom_sidebar) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                poly_utilities_ajax_response_helper::response_error("Error occurred while deleting.");
            } else {
                $this->db->trans_commit();
                poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
            }
        } else {
            $this->db->trans_rollback();
            poly_utilities_ajax_response_helper::response_error("Error occurred while deleting.");
        }
    }

    /**
     * Widgets
     * @return view
     */
    public function widgets()
    {
        $data['title'] = _l('poly_utilities_widgets_extend');
        $this->load->view('widgets/manage', $data);
    }

    /**
     * Support
     * @return view
     */
    public function support()
    {
        $poly_utilities_aio_supports = clear_textarea_breaks(get_option(POLY_SUPPORTS));
        $poly_utilities_aio_supports = !empty($poly_utilities_aio_supports) ? json_decode($poly_utilities_aio_supports, true) : [];

        $data['title'] = _l('poly_utilities_support');
        $data['poly_utilities_aio_supports'] = $poly_utilities_aio_supports;

        $this->load->view('support/manage', $data);
    }

    /**
     * Settings
     * @return view
     */
    public function settings()
    {
        $poly_utilities_settings = clear_textarea_breaks(get_option(POLY_UTILITIES_SETTINGS));

        if (empty($poly_utilities_settings)) {
            $obj_settings = new stdClass();
            $obj_settings->is_sticky = false;
            $obj_settings->is_toggle_sidebar_menu = false;
            $obj_settings->is_table_of_content = false;
            $obj_settings->is_active_scripts = true;
            $obj_settings->is_active_styles = true;
            $obj_settings->is_note_confirm_delete = true;
            $obj_settings->is_operation_functions = true;
            $obj_settings->is_scroll_to_top = false;
            update_option(POLY_UTILITIES_SETTINGS, json_encode($obj_settings));
            $poly_utilities_settings = clear_textarea_breaks(get_option(POLY_UTILITIES_SETTINGS));
        }
        $poly_utilities_settings = !empty($poly_utilities_settings) ? json_decode($poly_utilities_settings, true) : [];

        $data['title'] = _l('poly_utilities_settings');
        $data['poly_utilities_settings'] = $poly_utilities_settings;

        $this->load->view('settings', $data);
    }

    /**
     * Remove Quick Access Menu
     * @return view
     */
    public function delete_quick_access()
    {
        foreach (['link'] as $input_object) {
            $$input_object = $this->input->post($input_object, FALSE);
            $$input_object = trim($$input_object);
            $$input_object = nl2br($$input_object);
        }

        $obj_storage = clear_textarea_breaks(get_option(POLY_QUICK_ACCESS_MENU));

        $obj_old_data = [];
        if (!empty($obj_storage)) {
            $obj_old_data = json_decode($obj_storage, true);

            if (poly_utilities_common_helper::isExisted($obj_old_data, 'link', $link)) {
                $x = poly_utilities_common_helper::removeDataByField($obj_old_data, 'link', $link);
                update_option(POLY_QUICK_ACCESS_MENU, json_encode($x));
                poly_utilities_ajax_response_helper::response_success("Remove {$link}");
            }
        }
    }

    /**
     * Update Quick Access Menu
     * @return view
     */
    public function update_quick_access_menu()
    {
        $objs = $this->input->post('data', FALSE);
        update_option(POLY_QUICK_ACCESS_MENU, json_encode($objs));
        poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
    }

    /**
     * Add Quick Access Menu
     * @return view
     */
    public function save_quick_access()
    {
        foreach (['icon', 'title', 'link', 'shortcut_key', 'target', 'rel'] as $input_object) {
            $$input_object = $this->input->post($input_object, FALSE);
            $$input_object = trim($$input_object);
            $$input_object = nl2br($$input_object);
        }

        $obj = new stdClass();
        $obj->index = poly_utilities_common_helper::generateUniqueID();
        $obj->icon = $icon;
        $obj->title = $title;
        $obj->link = $link;
        $obj->target = !empty($target) ? $target : '_self';
        $obj->rel = !empty($rel) ? $rel : 'nofollow';
        $obj->shortcut_key = $shortcut_key;

        $obj_storage = clear_textarea_breaks(get_option(POLY_QUICK_ACCESS_MENU));

        $obj_old_data = [];
        if (!empty($obj_storage)) {
            $obj_old_data = json_decode($obj_storage, true);
            if (!poly_utilities_common_helper::isExisted($obj_old_data, 'link', $obj->link)) {
                $obj_old_data[] = $obj;
                update_option(POLY_QUICK_ACCESS_MENU, json_encode($obj_old_data));
            } else {
                poly_utilities_ajax_response_helper::response_data_exists(_l('poly_utilities_data_existed'));
            }
        } else {
            $obj_old_data[] = $obj;
            update_option(POLY_QUICK_ACCESS_MENU, json_encode($obj_old_data));
        }
        poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_add_success'));
    }

    /**
     * Uppdate All in One Supports
     * @return json
     */
    public function save_aio_supports()
    {
        $objs = $this->input->post('data', FALSE);
        update_option(POLY_SUPPORTS, json_encode($objs));
        poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
    }

    /**
     * Add resource: js | css
     * @return view
     */
    public function save_resource()
    {
        foreach (['title', 'file', 'mode', 'content', 'state', 'resource', 'is_embed', 'is_embed_position'] as $input_object) {
            $$input_object = $this->input->post($input_object, FALSE);
            $$input_object = trim($$input_object);
        }
        switch ($mode) {
            case 'truetrue':
                $mode = 'admin_customers';
                break;
            case 'truefalse':
                $mode = 'admin';
                break;
            case 'falsetrue':
                $mode = 'customers';
                break;
        }
        $resourceTable = POLY_SCRIPTS;
        $resourceExtension = '.js';
        switch ($resource) {
            case 'js': {
                    $resourceTable = POLY_SCRIPTS;
                    $resourceExtension = '.js';
                    break;
                }
            case 'css': {
                    $resourceTable = POLY_STYLES;
                    $resourceExtension = '.css';
                    break;
                }
        }

        $obj = new stdClass();
        $obj->title = $title;
        $obj->file = ($file ? $file : poly_utilities_common_helper::convertToFileName($title));
        $obj->mode = $mode; //admin, customers, admin_customers;
        $obj->is_embed = $is_embed;
        $obj->is_embed_position = $is_embed_position;

        $obj_storage = clear_textarea_breaks(get_option($resourceTable));
        $obj_old_data = [];
        if (!empty($obj_storage)) {
            $obj_old_data = json_decode($obj_storage, true);
            if (isset($state) && $state == true) {
                foreach ($obj_old_data as &$item) {
                    if ($item['file'] === $obj->file) {
                        $item['title'] =  $obj->title;
                        $item['mode'] = $mode;
                        $item['is_embed'] = $is_embed;
                        $item['is_embed_position'] = $is_embed_position;
                    }
                }
                unset($item);

                $isSave = poly_utilities_common_helper::save_to_file($obj->file . $resourceExtension, POLY_UTILITIES_MODULE_UPLOAD_FOLDER . '/' . $resource, $content, true);
                if ($isSave == 1) {
                    update_option($resourceTable, json_encode($obj_old_data));
                    poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_update_success'));
                } else {
                    poly_utilities_ajax_response_helper::response_data_not_saved(_l('poly_utilities_resource_access_error'));
                }
            }
        }

        if (poly_utilities_common_helper::isExisted($obj_old_data, 'file', $obj->file)) {
            poly_utilities_ajax_response_helper::response_data_exists(_l('poly_utilities_data_existed'));
        } else {
            $file_path = POLY_UTILITIES_MODULE_UPLOAD_FOLDER . '/' . $resource . '/' . $file . $resourceExtension;
            if (file_exists($file_path)) {
                if (!unlink($file_path)) {
                    poly_utilities_ajax_response_helper::response_data_not_saved(_l('poly_utilities_resource_access_error'));
                }
            }
        }

        $obj_old_data[] = $obj;
        $isSave = poly_utilities_common_helper::save_to_file($obj->file . $resourceExtension, POLY_UTILITIES_MODULE_UPLOAD_FOLDER . '/' . $resource, $content);
        if ($isSave == 1) {
            update_option($resourceTable, json_encode($obj_old_data));
            poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_add_success'));
        } else {
            poly_utilities_ajax_response_helper::response_data_not_saved(_l('poly_utilities_resource_access_error'));
        }
    }

    /**
     * Remove resource: js | css
     * @return view
     */
    public function delete_resource()
    {
        foreach (['id', 'resource'] as $input_object) {
            $$input_object = $this->input->post($input_object, FALSE);
            $$input_object = trim($$input_object);
        }

        $resourceTable = POLY_SCRIPTS;
        switch ($resource) {
            case 'js': {
                    $resourceTable = POLY_SCRIPTS;
                    break;
                }
            case 'css': {
                    $resourceTable = POLY_STYLES;
                    break;
                }
        }

        $obj_storage = clear_textarea_breaks(get_option($resourceTable));

        $obj_old_data = [];
        if (!empty($obj_storage)) {
            $obj_old_data = json_decode($obj_storage, true);
            if (poly_utilities_common_helper::isExisted($obj_old_data, 'file', $id)) {
                $x = poly_utilities_common_helper::removeDataByField($obj_old_data, 'file', $id);
                update_option($resourceTable, json_encode($x));
                $file_path = POLY_UTILITIES_MODULE_UPLOAD_FOLDER . '/' . $resource . '/' . $id . '.' . $resource;
                if (file_exists($file_path)) {
                    if (unlink($file_path)) {
                        poly_utilities_ajax_response_helper::response_success("Remove {$id}");
                    }
                }
            }
        }
    }

    /**
     * Update Settings
     * @return view
     */
    public function update_settings()
    {
        $objs = $this->input->post('data', FALSE);
        update_option(POLY_UTILITIES_SETTINGS, json_encode($objs));
        poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
    }

    /**
     * Update the display filter configuration for the data tables.
     * @return view
     */
    public function save_data_filters()
    {
        $objs = $this->input->post('data', FALSE);

        $obj = new stdClass();
        $obj->key = $objs['key'];
        $obj->value = $objs['value'];

        $dataFilters = get_option(POLY_TABLE_FILTERS);

        $dataTaleFilters = [];
        if (!empty($dataFilters)) {
            $dataTaleFilters = json_decode($dataFilters, true);

            //Update
            if (poly_utilities_common_helper::isExisted($dataTaleFilters, 'key', $obj->key)) {
                $dataTableFiltersUpdate = poly_utilities_common_helper::updateDataByField($dataTaleFilters, 'key', $obj->key, $obj);
                if (update_option(POLY_TABLE_FILTERS, json_encode($dataTableFiltersUpdate)) === true) {
                    poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
                } else {
                    poly_utilities_ajax_response_helper::response_data_not_saved('Error');
                }
            }
        }

        $dataTaleFilters[] = $obj;
        if (update_option(POLY_TABLE_FILTERS, json_encode($dataTaleFilters)) === true) {
            poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
        } else {
            poly_utilities_ajax_response_helper::response_data_not_saved('Error');
        }
    }

    public function update_widget()
    {
        if (is_admin() || has_permission('poly_utilities_widgets_extend', '', 'edit') || has_permission('poly_utilities_widgets_extend', '', 'delete')) {
            $objs = $this->input->post('data', FALSE);
            update_option(POLY_WIDGETS, json_encode($objs));
            poly_utilities_ajax_response_helper::response_success(_l('poly_utilities_response_success'));
        } else {
            poly_utilities_ajax_response_helper::response_data_not_saved(_l('access_denied'));
        }
    }

    //#region display custom menu
    public function details($slug)
    {
        if (!$slug) {
            show_404();
        }

        $menu_items = poly_utilities_custom_menu_items(POLY_MENU_SIDEBAR_CUSTOM_ACTIVE);
        if (empty($menu_items[$slug])) {
            $menu_items = poly_utilities_custom_menu_items(POLY_MENU_SETUP_CUSTOM_ACTIVE);
        }
        
        $object = $menu_items[$slug];
        $data['custom_menu'] = $object;
        $data['title'] = $object['name'];
        $this->load->view('custom_menu/details', $data);
    }
    //#endregion display custom menu
}
