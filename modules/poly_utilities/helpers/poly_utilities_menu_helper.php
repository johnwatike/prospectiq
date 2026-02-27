<?php

defined('BASEPATH') or exit('No direct script access allowed');

function poly_create_menu_item($custom_link)
{
    $id = 'i' . uniqid(); //slug

    $menu_item = $custom_link;
    $menu_item['id'] = $id;
    $menu_item['slug'] = $id;

    return $menu_item;
}

function poly_custom_create_menu_item_array($item)
{
    $href = poly_utilities_normalize_url($item['href']);
    switch ($item['type']) {
        case 'none':
            $href = '#';
            break;
        case 'iframe':
            $href = site_url(POLY_UTILITIES_CUSTOM_MENU_CLIENTS_SLUG . '/' . $item['slug']);
            break;
        case 'popup':
            $href = '#';
            $class = $item['href_attributes']['class'];
            $arr_class = explode(' ', $class);
            array_push($arr_class, 'poly-menu-popup');
            $item['href_attributes']['class'] = implode(' ', $arr_class);
            $item['href_attributes']['popup'] = $item['slug'];
            break;
    }
    $item['href'] = $href;

    $menu_item = $item;

    $menu_item['href_attributes'] = [
        "target" => $item['target'],
        "rel" => $item['rel']
    ];

    return $menu_item;
}

function app_admin_poly_custom_setup_menu_items($items)
{
    $menu_items_arranged = poly_utilities_custom_sidebar_menu_items_pre_render($items, POLY_MENU_SETUP_CUSTOM_ACTIVE);

    $custom_menu_items_option = get_option(POLY_MENU_SETUP);
    $custom_menu_items = ($custom_menu_items_option != null) ? json_decode($custom_menu_items_option, TRUE) : [];

    $rest_menu_items = poly_utilities_custom_sidebar_menu_defined($menu_items_arranged, $custom_menu_items);
    return $rest_menu_items;
}

function app_admin_poly_custom_sidebar_menu_items($items)
{
    $menu_items_arranged = poly_utilities_custom_sidebar_menu_items_pre_render($items, POLY_MENU_SIDEBAR_CUSTOM_ACTIVE);

    $custom_menu_items_option = get_option(POLY_MENU_SIDEBAR);
    $custom_menu_items = ($custom_menu_items_option != null) ? json_decode($custom_menu_items_option, TRUE) : [];

    $rest_menu_items = poly_utilities_custom_sidebar_menu_defined($menu_items_arranged, $custom_menu_items);
    return $rest_menu_items;
}

function app_admin_poly_custom_clients_menu_items()
{
    $menu_items_custom = get_option(POLY_MENU_CLIENTS);
    $custom_clients_menu_items = poly_utilities_common_helper::json_decode($menu_items_custom, TRUE);

    //Permissions
    $current_client_id = get_client_user_id();
    foreach ($custom_clients_menu_items as $item) {
        if (is_admin()) { // Admin accept all.
            $menu_item = poly_custom_create_menu_item_array($item);
            add_theme_menu_item($menu_item['slug'], $menu_item);
            continue;
        }

        if (!isset($item['require_login'])) { // Do not require login
            $menu_item = poly_custom_create_menu_item_array($item);
            add_theme_menu_item($menu_item['slug'], $menu_item);
            continue;
        }

        if ($item['require_login'] == "on" && !is_client_logged_in()) { // !(Require login)
            continue;
        }

        if (!empty($item['clients']) && $item['clients'] != '[]') {
            $clients = poly_utilities_common_helper::json_decode($item['clients'], true);
            $client_can_access = poly_utilities_common_helper::poly_utilities_get_item_by($clients, 'id', $current_client_id);

            if (!$client_can_access)
                continue;
        }

        $menu_item = poly_custom_create_menu_item_array($item);
        add_theme_menu_item($menu_item['slug'], $menu_item);
    }
}

/**
 * Render the main sidebar list with custom attributes, categorizing menus: iframe, popup, blank,...
 */
function poly_utilities_custom_sidebar_menu_items_pre_render($items, $MENU_DEFINE)
{
    $menu_items_merged = $items;
    $menu_items_custom = get_option($MENU_DEFINE);
    if (!empty($menu_items_custom)) {
        $tmp = poly_utilities_init_custom_sidebar_menu_items($menu_items_custom);
        $menu_items_merged = array_merge($items, $tmp);
    }
    $menu_items_merged = poly_utilities_init_custom_sidebar_menu_items($menu_items_merged);

    foreach ($menu_items_merged as $key => &$value) {
        if (!isset($value['position'])) {
            $value['position'] = 0;
        }
    }
    unset($value);

    usort($menu_items_merged, function ($a, $b) {
        return $a['position'] <=> $b['position'];
    });

    // Reset root position
    $maxPositionParent = 0;
    foreach ($menu_items_merged as $key => &$value) {
        $value['position'] = $maxPositionParent++;
    }
    unset($value);

    foreach ($menu_items_merged as $key => &$value) {
        if (!empty($value['children']) && is_array($value['children'])) {
            $positions = array_column($value['children'], 'position');
            $maxPosition = (!empty($positions)) ? max($positions) : 0;

            foreach ($value['children'] as &$item) {
                if (!isset($item['position'])) {
                    $maxPosition++;
                    $item['position'] = $maxPosition;
                }
            }
            unset($item);

            usort($value['children'], function ($a, $b) {
                return $a['position'] <=> $b['position'];
            });
        }
    }
    return $menu_items_merged;
}

/**
 * Function to rearrange the order of menu items based on parent_slug and children.
 * @param array $custom_menu_items List of menus to be sorted.
 * @return array Array of sorted menu items.
 */
function poly_utilities_init_custom_sidebar_menu_items($custom_menu_items)
{
    $menu_items = $custom_menu_items;
    if (is_string($custom_menu_items)) {
        $menu_items = json_decode($custom_menu_items, true);
    }
    $result = [];
    $temp = [];
    foreach ($menu_items as &$item) {
        $temp[$item['slug']] = $item + ['children' => []];
    }
    unset($item);

    foreach ($temp as $key => &$itm) {
        if (!empty($itm['parent_slug']) && isset($temp[$itm['parent_slug']])) {
            $temp[$itm['parent_slug']]['children'][] = &$itm;
        } else {
            $result[$itm['slug']] = &$itm;
        }
    }
    unset($itm);
    return $result;
}

/**
 * Searches for a menu item by its slug within a list of menu items.
 * 
 * @param array $custom_menu_items An array of menu item objects, where each item is expected
 * to be an associative array with at least a 'slug' key.
 * @param string $menu_item_slug The slug string to search for within the 'slug' attribute of each menu item array.
 * 
 * @return mixed Returns the found menu item array if a match is found, or null if no match is found.
 */
function poly_utilities_find_menu_item_by_slug($custom_menu_items, $menu_item_slug, $is_object = false)
{
    foreach ($custom_menu_items as $item) {
        if ($item['slug'] === $menu_item_slug) {
            return $is_object ? $item : true;
        }
        if (isset($item['children']) && is_array($item['children'])) {
            if (poly_utilities_find_menu_item_by_slug($item['children'], $menu_item_slug)) {
                return $is_object ? $item : true;
            }
        }
    }
    return $is_object ? null : false;
}

/**
 * Reorders the full menu list to maintain the custom sort order of the custom menu.
 * @param array &$custom_menu_items The custom sorted menu list. This array may be modified to include items from $menu_items that are not present.
 * @param array $menu_items The full list of menu items, including those in $custom_menu_items but not sorted.
 */
function poly_utilities_menu_sidebar_merged(&$custom_menu_items, $menu_items)
{
    //TODO: $item exist in $menu_items but not in $custom_menu_items => add it
    foreach ($menu_items as &$item) {
        $current_object = poly_utilities_find_menu_item_by_slug($custom_menu_items, $item['slug']);
        if (!$current_object) {
            $custom_menu_items[] = $item;
        }
    }
    unset($item);

    //TODO: $item exist in $custom_menu_items but not in $menu_item => remove it
    $menu_items_mapped = [];
    poly_utilities_map_slug_arr_sidebar_menu($menu_items, $menu_items_mapped);
    poly_utilities_menu_sidebar_merged_mapped($custom_menu_items, $menu_items_mapped);
}

/**
 * Function to remove all custom elements in $custom_menu_items if they do not exist in the main menu $menu_items (mapped by slug).
 */
function poly_utilities_menu_sidebar_merged_mapped(&$custom_menu_items, $menu_items_mapped)
{
    foreach ($custom_menu_items as $key => &$custom_item) {
        $exists = false;
        foreach ($menu_items_mapped as $item) {
            if ($item['slug'] === $custom_item['slug']) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            unset($custom_menu_items[$key]);
        } else {
            if (isset($custom_item['children']) && is_array($custom_item['children'])) {
                poly_utilities_menu_sidebar_merged_mapped($custom_item['children'], $menu_items_mapped);
            }
        }
    }
}

/**
 * Check permission to view the custom menu feature
 */
function staff_can_poly_utilities_custom_menu()
{
    // Allow admin or staff can
    /**
     * TODO features: Handle the case where granting admin rights then removes permissions to install modules, backup
     */
    if (!is_admin() || !staff_can('view', 'poly_utilities_custom_menu_extend')) {
        access_denied();
    }
}

/**
 * Prevents access to a specific custom menu by removing it from the $menu_items list.
 * The function iterates over $menu_items, checking for a specific 'slug'. If the 'slug' matches
 * the predefined value and the staff does not have the 'view' permission for this menu, it is removed.
 * It also recursively checks and applies the same logic to any children menus.
 *
 * @param array &$menu_items An array representing the list of menu items. Each item is an associative array that may include 'slug' and 'children' keys.
 */
function poly_utilities_denie_access_custom_menu(&$menu_items)
{
    foreach ($menu_items as $key => &$item) {
        if (isset($item['slug']) && $item['slug'] === 'poly_utilities_custom_menu_extend' && !staff_can('view', $item['slug'])) {
            unset($menu_items[$key]);
            continue;
        }

        if (array_key_exists('children', $item) && is_array($item['children'])) {
            poly_utilities_denie_access_custom_menu($item['children']);
        }
    }
    unset($item);
    $menu_items = array_values($menu_items);
}

/**
 * Sorts the main menu items $menu_items according to the order specified in the list of custom menu items.
 * This function adjusts the order of $menu_items based on their positions in the $custom_menu_items list,
 * ensuring that the final order of menu items reflects the custom order defined.
 *
 * @param array $menu_items An array of the main menu items. Each item in this array is expected to be
 * an associative array that could represent a menu item.
 * @param array $custom_menu_items An array of custom menu items specifying the desired order. Each item
 * in this array should correspond to or be identifiable with items in $menu_items, dictating the order
 * the items in $menu_items should be arranged in.
 */
function poly_utilities_custom_sidebar_menu_defined($menu_items, $custom_menu_items)
{
    if ($custom_menu_items != null) {
        foreach ($custom_menu_items as $key => &$item) {
            if (!array_key_exists('children', $item)) {
                $item['children'] = [];
            }
            if (!empty($item['children'])) {
                poly_utilities_denie_access_custom_menu($item['children']);
            }
        }
        unset($item);

        $menu_sidebar_slug_map_items = [];

        poly_utilities_map_slug_arr_sidebar_menu($menu_items, $menu_sidebar_slug_map_items);
        poly_utilities_menu_sidebar_language($custom_menu_items, $menu_sidebar_slug_map_items);

        poly_utilities_menu_sidebar_merged($custom_menu_items, $menu_items);
        $menu_items = $custom_menu_items;
    }

    poly_utilities_menu_sidebar_define_by_type($menu_items);

    //ROLES & Permissions
    $staff_id = get_staff_user_id();
    poly_utilities_menu_sidebar_users_access($menu_items, $staff_id);
    //ROLES & Permissions

    return $menu_items;
}

function poly_utilities_menu_sidebar_users_access(&$menu_items, $staff_id)
{
    foreach ($menu_items as $key => &$item) {
        //Badge
        if ($item['is_custom'] === 'true') {
            if (isset($item['badge']) && empty($item['badge']['value'])) {
                unset($item['badge']);
            } else {
                $item['badge']['value'] = $item['badge']['value'];
            }
        }
        //Badge

        //Roles
        $user_can_access = false;
        $role_can_access = false;

        if (!empty($item['roles'])) {
            $role_by_staffid = poly_utilities_user_helper::get_user_role($staff_id);
            if ($role_by_staffid !== null) {
                $roleid_by_user = $role_by_staffid->role;
                $roles_access = poly_utilities_common_helper::json_decode($item['roles'], true);
                $role_can_access = poly_utilities_common_helper::poly_utilities_get_item_by($roles_access, 'id', $roleid_by_user);
            }
        } else {
            $role_can_access = true;
        }

        //Users
        if (!empty($item['users'])) {
            $users = poly_utilities_common_helper::json_decode($item['users'], true);
            $user_can_access = poly_utilities_common_helper::poly_utilities_get_item_by($users, 'id', $staff_id);
        } else {
            $user_can_access = true;
        }

        //Remove menu items from the list if the account or group does not have access permission.
        if (!$role_can_access && !$user_can_access && !is_admin()) {
            unset($menu_items[$key]);
        } elseif (!empty($item['children'])) {
            poly_utilities_menu_sidebar_users_access($item['children'], $staff_id);
        }
    }
    unset($item);
}

/**
 * Handle custom item by type: none, iframe, popup,...
 */
function poly_utilities_menu_sidebar_define_by_type(&$finally_sidebar_menu_items)
{
    foreach ($finally_sidebar_menu_items as $key => &$item) {
        $href = poly_utilities_normalize_url($item['href']);
        switch ($item['type']) {
            case 'none':
                $href = '#';
                break;
            case 'iframe':
                $href = admin_url('poly_utilities/details/' . $item['slug']);
                break;
            case 'popup':
                $href = '#';
                $class = $item['href_attributes']['class'];
                $arr_class = explode(' ', $class);
                array_push($arr_class, 'poly-menu-popup');
                $item['href_attributes']['class'] = implode(' ', $arr_class);
                $item['href_attributes']['popup'] = $item['slug'];
                break;
        }
        $item['href'] = $href;

        $item['href_attributes']['target'] = $item['target'];
        $item['href_attributes']['rel'] = $item['rel'];
        $item['href_attributes']['data-type'] = $item['type'];

        foreach ($item['children'] as &$child_item) {
            $child_href = poly_utilities_normalize_url($child_item['href']);
            switch ($child_item['type']) {
                case 'none':
                    $child_href = '#';
                    break;
                case 'iframe':
                    $child_href = admin_url('poly_utilities/details/' . $child_item['slug']);
                    break;
                case 'popup': // Display popup
                    $child_href = '#';
                    $class = $child_item['href_attributes']['class'];
                    $arr_class = explode(' ', $class);
                    array_push($arr_class, 'poly-menu-popup');
                    $child_item['href_attributes']['class'] = implode(' ', $arr_class);
                    $child_item['href_attributes']['popup'] = $child_item['slug'];
                    break;
            }
            if (($child_item['href'] == '#' || empty($child_item['href'])) && $child_item['type'] != 'popup') { //Hidden root in menu display
                $child_item['href_attributes']['class'] = 'hide';
            }

            $child_item['href'] = $child_href;

            $child_item['href_attributes']['target'] = $child_item['target'];
            $child_item['href_attributes']['rel'] = $child_item['rel'];
            $child_item['href_attributes']['data-type'] = $child_item['type'];
        }
        unset($child_item);
    }
    unset($item);
}

function poly_utilities_menu_sidebar_update(&$menu_items, $menu_item_update)
{
    foreach ($menu_items as &$item) {
        if ($menu_item_update['id'] === $item['id']) {
            $menu_item_update['slug'] = $item['slug'];

            $menu_item_update['position'] = $item['position'];
            if (!empty($item['children'])) {
                $menu_item_update['children'] = $item['children'];
            }
            $item = $menu_item_update;
            break;
        }

        if (!empty($item['children'])) {
            poly_utilities_menu_sidebar_update($item['children'], $menu_item_update);
        }
    }
    unset($item);
}

function poly_utilities_normalize_url($url)
{
    $parsedUrl = parse_url($url);
    $path = $parsedUrl['path'];
    $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

    $parts = explode('/', $path);

    $adminKey = array_search('admin', $parts);
    if ($adminKey === false) {
        return $url;
    }
    $afterAdminParts = array_slice($parts, $adminKey + 1);
    $afterAdmin = implode('/', $afterAdminParts) . $query;

    return admin_url($afterAdmin);
}

/**
 * Handles multilingual display
 */
function poly_utilities_menu_sidebar_language(&$menu_items, $menu_items_map)
{
    foreach ($menu_items as &$item) {
        if (isset($menu_items_map[$item['slug']])) {
            $item['name'] = $menu_items_map[$item['slug']]['name'];
        }
        if (!empty($item['children'])) {
            poly_utilities_menu_sidebar_language($item['children'], $menu_items_map);
        }
    }
    unset($item);
}

function poly_utilities_map_slug_arr_sidebar_menu($menu_items, &$slugMap)
{
    foreach ($menu_items as $item) {
        $slugMap[$item['slug']] = $item;
        if (!empty($item['children'])) {
            poly_utilities_map_slug_arr_sidebar_menu($item['children'], $slugMap);
        }
    }
}

/**
 * Delete a custom sidebar menu item by id in POLY_MENU_SIDEBAR & POLY_MENU_SIDEBAR_CUSTOM_ACTIVE.
 */
function poly_utilities_delete_custom_sidebar_menu_by_id($id, $storage)
{
    $obj_storage = get_option($storage);
    if (!empty($obj_storage)) {
        $obj_old_data = json_decode($obj_storage, true);

        poly_utilities_common_helper::isRemoveWhenExisted($obj_old_data, 'id', 'children', $id);

        update_option($storage, json_encode($obj_old_data));
        return true;
    }
    return false;
}

function poly_utilities_custom_menu_items($MENU_DEFINE = POLY_MENU_SIDEBAR_CUSTOM_ACTIVE)
{
    $menu_items_custom = get_option($MENU_DEFINE);
    $menu_items_custom = $menu_items_custom ? json_decode($menu_items_custom, TRUE) : [];

    $arr = [];
    foreach ($menu_items_custom as $key => $value) {
        $arr[$value['slug']] = $value;
    }
    return $arr;
}

function poly_get_clients_menu_items()
{
    $CI = &get_instance();
    return $CI->app_menu->get_theme_items();
}
