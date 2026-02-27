<?php

defined('BASEPATH') or exit('No direct script access allowed');

class poly_utilities_common_helper
{
    public static $rels = ['follow', 'nofollow', 'alternate', 'author', 'bookmark', 'external', 'help', 'license', 'next', 'noreferrer', 'noopener', 'prev', 'search', 'tag'];
    public static $targets = ['_self', '_blank', '_parent', '_top'];
    public static $link_type = [['default' => 'Default (as link)'], ['none' => 'None (# Create a root-level custom menu)'], ['iframe' => 'Iframe (embed link)'], ['popup' => 'Popup (Display list of child items in a popup)']];
    public static $alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    public static $numbers = ['1', '2', '3', '4', '5', '6', '7', '8', '9'];
    public static $aio_supports_type = ['link', 'email', 'mobile', 'facebook_messenger', 'viber', 'whatsapp', 'other'];

    /**
     * Don't cache some resource files for support
     */
    public static function getParamDoNotCacheFile($paramCharacter = '')
    {
        return ((!empty($paramCharacter)) ? $paramCharacter : '?') . "date=" . time();
    }

    public static function array_map_to_objects_key_value($arr_input, $key_name = 'id', $value_name = 'text')
    {
        $arr = [];
        foreach ($arr_input as $item) {
            foreach ($item as $key => $value) {
                $arr[] = array(
                    $key_name => $key,
                    $value_name => $value
                );
            }
        }
        return $arr;
    }

    public static function isExisted($arr, $field, $content)
    {
        if (count($arr) == 0) return false;
        if (is_array($arr)) {
            foreach ($arr as $itm) {
                if (isset($content) && $itm[$field] === $content) {
                    return true;
                    break;
                }
            }
        }
        return false;
    }

    public static function removeDataByField($arr, $field, $value)
    {
        foreach ($arr as $key => $item) {
            if ($item[$field] === $value) {
                unset($arr[$key]);
                break;
            }
        }
        return array_values($arr);
    }

    /**
     * Removes an item from the array and its sub-arrays based on the specified field and value.
     * This function recursively searches through the array and any sub-arrays defined by $sub_field to find and remove items where $field equals $content.
     * 
     * @param array &$arr The array to search through, passed by reference to allow modifications.
     * @param string $field The field name in the array items to compare against $content.
     * @param string $sub_field The field name in the array items that may contain sub-arrays to recursively search through.
     * @param mixed $content The value to compare against the $field value to determine if an item should be removed.
     * @return bool Returns true if at least one item was removed; otherwise, returns false.
     */
    public static function isRemoveWhenExisted(&$arr, $field, $sub_field, $content)
    {
        if (count($arr) == 0) return false;
        $removed = false;

        foreach ($arr as $key => &$itm) {
            if (isset($itm[$field]) && $itm[$field] === $content) {
                unset($arr[$key]);
                $removed = true;
            }

            if (isset($itm[$sub_field]) && is_array($itm[$sub_field])) {
                $subRemoved = self::isRemoveWhenExisted($itm[$sub_field], $field, $sub_field, $content);
                if ($subRemoved) {
                    $removed = true;
                    $itm[$sub_field] = array_values($itm[$sub_field]);
                }
            }
        }

        if ($removed) {
            $arr = array_values($arr);
        }

        return $removed;
    }

    public static function updateDataByField($arr, $field, $value, $obj)
    {
        foreach ($arr as $key => $item) {
            if ($item[$field] === $value) {
                unset($arr[$key]);
                $arr[] = $obj;
                break;
            }
        }
        return array_values($arr);
    }

    public static function generateUniqueID()
    {
        $uniqueID = uniqid();
        $hashedID = md5($uniqueID);
        return $hashedID;
    }

    public static function convertToFileName($string)
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9]+/', '-', $string);
        $string = trim($string, '-');
        $string = str_replace(' ', '-', $string);
        return $string;
    }

    public static function render_select($id, $options, $value, $label = '', $group_class = '', $select_class = '', $input_attrs = [])
    {
        $rest = '<div class="' . $group_class . '">' . ((!empty($label)) ? "<label>{$label}</label>" : '');

        $_input_attrs     = '';
        foreach ($input_attrs as $key => $val) {
            if ($key == 'title') {
                $val = _l($val);
            }
            $_input_attrs .= $key . '=' . '"' . $val . '" ';
        }

        $_input_attrs = rtrim($_input_attrs);

        $rest .= "<select class='form-control " . $select_class . "' " . $_input_attrs . " id='{$id}' name='{$id}'>";
        foreach ($options as $key => $option) {
            if (is_array($option)) {
                $selected = ($option['id'] === $value) ? ' selected' : '';
                $rest .= '<option value="' . $option['id'] . '"' . $selected . '>' . $option['text'] . '</option>';
            } elseif (is_object($option)) {
                $selected = ($option->id === $value) ? ' selected' : '';
                $rest .= '<option value="' . $option->id . '"' . $selected . '>' . $option->text . '</option>';
            } elseif (is_string($option)) {
                $selected = ($option === $value) ? ' selected' : '';
                $rest .= "<option value='{$option}'{$selected}>{$option}</option>";
            }
        }
        $rest = $rest . "</select></div>";
        return $rest;
    }

    /**
     * Function that renders input for admin area based on passed arguments. Handle from render_input
     * @param  string $name             input name
     * @param  string $label            label name
     * @param  string $value            default value
     * @param  string $type             input type eq text,number
     * @param  array  $input_attrs      attributes on <input
     * @param  array  $form_group_attr  <div class="form-group"> html attributes
     * @param  string $form_group_class additional form group class
     * @param  string $input_class      additional class on input
     * @param  string $field_validation      additional field validation
     * @return string
     */
    public static function render_input_vuejs($name, $label = '', $value = '', $type = 'text', $input_attrs = [], $form_group_attr = [], $form_group_class = '', $input_class = '', $v_model = '', $field_validation = '',)
    {
        $input            = '';
        $_form_group_attr = '';
        $_input_attrs     = '';

        if (is_array($input_attrs)) {
            $input_attrs = array_merge($input_attrs, array('v-model' => $v_model));
        }

        foreach ($input_attrs as $key => $val) {
            if ($key == 'title') {
                $val = _l($val);
            }
            $_input_attrs .= $key . '=' . '"' . $val . '" ';
        }

        $_input_attrs = rtrim($_input_attrs);

        $form_group_attr['app-field-wrapper'] = $name;

        foreach ($form_group_attr as $key => $val) {
            if ($key == 'title') {
                $val = _l($val);
            }
            $_form_group_attr .= $key . '=' . '"' . $val . '" ';
        }

        $_form_group_attr = rtrim($_form_group_attr);

        if (!empty($form_group_class)) {
            $form_group_class = ' ' . $form_group_class;
        }
        if (!empty($input_class)) {
            $input_class = ' ' . $input_class;
        }
        $input .= '<div class="form-group' . $form_group_class . '" ' . $_form_group_attr . ' :class="{\'has-error\':' . $field_validation . ' && ' . $v_model . '==\'\'}">';
        if ($label != '') {
            $input .= '<label for="' . $name . '" class="control-label">' . _l($label, '', false) . '</label>';
        }
        $input .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . ' value="' . set_value($name, $value) . '">';
        $input .= '<p v-if="' . $field_validation . ' && ' . $v_model . '==\'\' " class="text-danger">{{ ' . $field_validation . ' }}</p>';
        $input .= '</div>';

        return $input;
    }

    public static function json_decode($json_string, $is_array = true)
    {
        if (is_array($json_string)) {
            return $json_string;
        }
        $arr = json_decode($json_string, $is_array);
        return is_array($arr) ? $arr : [];
    }

    /**
     * Retrieves or checks an item by $field corresponding to the test value $value
     * 
     * @param array $arr An array of items
     * @param string $field Name of the attribute to check.
     * @param string $value Value to test.
     * @param boolean $is_object returns an object if set to true. By default, it returns true if found and false if not found.
     * 
     * @return mixed Returns true/false when $is_object = true and object or null if false.
     */
    public static function poly_utilities_get_item_by($arr, $field, $value, $is_object = false)
    {
        $value_check = strval($value);
        foreach ($arr as $item) {
            $currentValue = $is_object ? $item->$field : $item[$field];
            if ($currentValue === $value_check) {
                return $is_object ? true : $item;
            }
        }
        return $is_object ? null : false;
    }

    public static function domain()
    {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $currentDomain = $scheme . '://' . $host;
        return $currentDomain;
    }

    //#region debugs
    /**
     * Function to reset some values saved through define variables, options
     */
    public static function debug_reset()
    {
        if (isset($_GET['reset'])) {
            update_option(POLY_MENU_SIDEBAR_CUSTOM_ACTIVE, '[]');
            update_option(POLY_MENU_SIDEBAR, '[]');

            update_option(POLY_MENU_SETUP_CUSTOM_ACTIVE, '[]');
            update_option(POLY_MENU_SETUP, '[]');

            update_option(POLY_MENU_CLIENTS_CUSTOM_ACTIVE, '[]');
            update_option(POLY_MENU_CLIENTS, '[]');
        }
    }

    public static function echo($str)
    {
        echo '<div style="margin-left:280px">' . $str . '</div>';
    }

    public static function debug_textarea($data = null, $pretty = false)
    {
        if (is_array($data) || is_object($data)) {
            if ($pretty === true) {
                echo '<textarea style="margin:0px auto;display:table">' . json_encode($data, JSON_PRETTY_PRINT) . '</textarea>';
            } else {
                echo '<textarea style="margin:0px auto;display:table">' . json_encode($data) . '</textarea>';
            }
        } elseif (is_string($data)) {
            echo '<textarea style="margin:0px auto;display:table">' . $data . '</textarea>';
        } else {
            echo '<textarea style="margin:0px auto;display:table">None</textarea>';
        }
    }
    //#endregion debugs

    //#region files

    /**
     * Requires one file into another file.
     * 
     * The function creates a require statement using a template and places it at a specified location in the target file. If no location is provided, it adds the statement to the file's end.
     * 
     * @param string $destPath      The path to the destination file.
     * @param string $requirePath   The path to the file to require.
     * @param boolean $force        Insert content regardless of whether it exists.
     * @param boolean $position     Location for inserting the require statement. If set to False, append it to the end of the file.
     * 
     * @return mixed
     */
    public static function require_in_file($destPath, $requirePath, $force = false, $position = false)
    {
        if (!file_exists($destPath)) {
            poly_utilities_common_helper::file_put_contents($destPath, "<?php defined('BASEPATH') or exit('No direct script access allowed');\n");
        }

        if (file_exists($destPath)) {
            $content = file_get_contents($destPath);
            $template = poly_utilities_common_helper::require_in_file_template($requirePath);

            $exist = preg_match(poly_utilities_common_helper::require_signature($requirePath), $content);
            if ($exist && !$force) {
                return;
            }
            $content = poly_utilities_common_helper::unrequire_in_file($destPath, $requirePath);

            if ($position !== false) {
                $content = substr_replace($content, $template . "\n", $position, 0);
            } else {
                $content = $content . $template;
            }

            poly_utilities_common_helper::file_put_contents($destPath, $content);
        }
    }

    /**
     * Removes a file's require statement from another file.
     * 
     * This function deletes a require statement, which was created using a template, from a specified position in the target file. If no specific position is provided, the function will search for and remove the require statement from the end of the file.
     * 
     * @param string $destPath      The path to the target file.
     * @param string $requirePath   The path to the file whose require statement needs to be removed.
     * 
     * @return string The modified content of the destination file.
     */
    public static function unrequire_in_file($destPath, $requirePath)
    {
        if (file_exists($destPath)) {
            $content = file_get_contents($destPath);
            $content = preg_replace(poly_utilities_common_helper::require_signature($requirePath), '', $content);
            poly_utilities_common_helper::file_put_contents($destPath, $content);
            return $content;
        }
    }

    public static function require_signature($file)
    {
        $basename = str_ireplace(['"', "'"], '', basename($file));
        return "#//".POLY_UTILITIES_MODULE_NAME.":start:" . $basename . "([\s\S]*)//" . POLY_UTILITIES_MODULE_NAME . ":end:" . $basename . "#";
    }

    public static function require_in_file_template($path)
    {
        $template = "//" . POLY_UTILITIES_MODULE_NAME . ":start:#filename\n//Do not delete or modify the code in this block\nrequire_once(#path);\n//END: Do not delete or modify the code in this block\n//".POLY_UTILITIES_MODULE_NAME.":end:#filename";

        $template = str_ireplace('#filename', str_ireplace(['"', "'"], '', basename($path)), $template);
        $template = str_ireplace('#path', $path, $template);
        return $template;
    }

    public static function file_put_contents($path, $content)
    {
        @chmod($path, FILE_WRITE_MODE);
        if (!$fp = fopen($path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
            return false;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $content, strlen($content));
        flock($fp, LOCK_UN);
        fclose($fp);
        @chmod($path, FILE_READ_MODE);
        return true;
    }

    public static function read_file($file_name, $directory)
    {
        if (file_exists($directory . '/' . $file_name)) {
            $content = file_get_contents($directory . '/' . $file_name);
            if ($content !== false) {
                return $content;
            }
        }
        return '';
    }
    
    public static function save_to_file($file_name, $directory, $content, $is_overwrite = false)
    {
        $file_path = $directory . '/' . $file_name;

        if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
            return 0;
        }

        if ($is_overwrite && file_exists($file_path) && !unlink($file_path)) {
            return 0;
        }

        if (!$is_overwrite && file_exists($file_path)) {
            return 0;
        }

        return file_put_contents($file_path, $content) !== false ? 1 : 0;
    }
    //#endregion files
}
