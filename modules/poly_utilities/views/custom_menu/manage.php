<?php
defined('BASEPATH') or exit('No direct script access allowed');
init_head();
echo '<script src="' . base_url('modules/poly_utilities/assets/js/lib/vuejs/3.3.13/vue.global.js') . '"></script>';
echo '<link rel="stylesheet" href="' . base_url('modules/poly_utilities/assets/css/lib/select2/select2.min.css') . '">';

$menu_link_type_map = poly_utilities_common_helper::array_map_to_objects_key_value(poly_utilities_common_helper::$link_type);

?>
<div id="polyApp" v-cloak>
    <div id="wrapper">

        <div class="poly-loader">
            <div :class="{'poly-loading': isProccessing }">&nbsp;</div>
        </div>

        <div class="content" :class="{ 'disabled': isProccessing }">
            <div class="row poly_utilities_settings poly-data-container" v-if="dataLoaded">
                <div class="col-md-12">
                    <div class="tw-mb-2 sm:tw-mb-4">
                        <!-- Add Custom Link -->
                        <?php
                        if (has_permission('poly_utilities_custom_menu_extend', '', 'create')) {
                            echo form_open(admin_url('poly_utilities/update_custom_sidebar_menu'), ['id' => 'poly_utilities_add_custom_sidebar_form', '@submit.prevent' => 'handleSubmit']);
                        ?>
                            <div class="panel_s">
                                <div class="panel-body tw-pb-0">
                                    <?php $this->load->view('poly_utilities/custom_menu/tabs'); ?>
                                    <div class="row">
                                        <div class="col-md-1">
                                            <label><i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1 hidden-xs" data-toggle="tooltip" data-title="<?php echo _l('poly_utilities_quick_access_icon_help') ?>"></i><?php echo _l('poly_utilities_quick_access_icon') ?></label>
                                            <div class="input-group" id="poly_field_aio_supports_button">
                                                <textarea name="icon" class="form-control poly_aio_supports_icon_button poly_aio_supports_icon hide">{{item_edit_object.icon ||'fa-solid fa-shield-halved fa-fw'}}</textarea>
                                                <span class="btn btn-default poly-utilities-aio-icon-select" data-id="poly_field_aio_supports_button">
                                                    <i :class="item_edit_object.icon || 'fa-solid fa-shield-halved fa-fw'"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Badge
                                                <?php echo render_input('badge[value]', '', '', 'text', array('placeholder' => _l('poly_utilities_custom_menu_badge_name_placeholder'), 'v-model' => 'item_edit_object.badge.value')); ?>
                                            </label>

                                        </div>
                                        <div class="col-md-2">
                                            <label>Badge color
                                                <div class="input-group colorpicker-input colorpicker-element">
                                                    <input type="text" name="badge[color]" class="poly-colorpicker-input-value form-control" data-fieldto="badge[color]">
                                                    <span class="input-group-addon cursor" :style="'background-color:'+item_edit_object.badge.color">&nbsp;</span>
                                                </div>
                                            </label>
                                        </div>

                                        <?php echo poly_utilities_common_helper::render_input_vuejs('name', _l('poly_utilities_custom_menu_title'), '', 'text', array('placeholder' => _l('poly_utilities_custom_menu_title')), [], 'col-md-7', '', 'item_edit_object.name', 'validation_fields.name'); ?>

                                    </div>

                                    <div class="row">
                                        <div v-if="roles && roles.length" class="form-group col-md-5">
                                            <label style="width: 100%" for="roles"><?php echo _l('poly_utilities_custom_menu_specific_roles_label') ?>
                                                <select style="width: 100%" class="select2 roles form-control" name="roles[]" multiple="multiple">
                                                    <option v-for="role in roles" :key="role.roleid" :value="role.roleid">{{role.name}}</option>
                                                </select></label>
                                        </div>
                                        <div class="form-group col-md-7 poly-utilities-specific-users">
                                            <label style="width: 100%" for="users"><?php echo _l('poly_utilities_custom_menu_specific_users_label') ?>
                                                <select style="width: 100%" class="select2 users form-control" name="users[]" multiple="multiple">
                                                </select></label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="parent_slug"><?php echo _l('poly_utilities_custom_menu_parent_label') ?></label>
                                            <select name="parent_slug" class="form-control" v-model="item_edit_object.parent_slug">
                                                <option v-for="item in menu_items" :key="item.slug" :value="item.slug">{{item.name}}</option>
                                            </select>
                                        </div>
                                        <?php echo poly_utilities_common_helper::render_select('type', $menu_link_type_map, '', _l('poly_utilities_custom_menu_type_label'), 'col-md-2', '', array('v-model' => 'item_edit_object.type')); ?>
                                        <div class="col-md-4">
                                            <?php echo render_input('href', _l('poly_utilities_custom_menu_href_label'), '', 'text', array('placeholder' => 'https://...', 'v-model' => 'item_edit_object.href')); ?>
                                        </div>
                                        <?php echo poly_utilities_common_helper::render_select('target', poly_utilities_common_helper::$targets, "_self", 'Target', 'col-md-2', '', array('v-model' => 'item_edit_object.target')); ?>
                                        <?php echo poly_utilities_common_helper::render_select('rel', poly_utilities_common_helper::$rels, 'nofollow', 'Rel', 'col-md-2', '', array('v-model' => 'item_edit_object.rel')); ?>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="tw-flex tw-items-center">
                                        <button type="submit" class="btn btn-primary" @click="isEdit(false)"><?php echo _l('poly_utilities_custom_menu_button_save'); ?></button>
                                        &nbsp;<button type="submit" v-if="is_edit" class="btn btn-success" @click="isEdit(true)"><?php echo _l('poly_utilities_custom_menu_button_update'); ?></button>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close();
                        }
                        ?>

                        <!-- END Add Custom Link -->

                        <!-- Menu items -->
                        <div class="panel_s">
                            <div class="panel-body tw-pb-0">
                                <div id="shared-lists" class="row">
                                    <div class="col-md-6 poly-menu">
                                        <h4 class="col-12">Active Menu Items</h4>
                                        <div id="poly-active-menu" class="list-group col nested-sortable">
                                            <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1 hidden-xs"></i><?php echo _l('poly_utilities_custom_menu_arange_help') ?>
                                            <template v-for="(item, parent_index) in menu_items" :key="item.slug">
                                                <div v-if="item.slug && !item.slug.includes('root')" :class="['list-group-item', `nested-${parent_index}`]" :data-id="item.slug" :data-icon="item.icon" :data-badge="JSON.stringify(item.badge)" :data-href="item.href" :data-type="item.type" :data-roles="item.roles" :data-users="item.users" :data-is_custom="item.is_custom" :data-name="item.name" :data-slug="item.slug" :data-parent_slug="item.parent_slug">
                                                    <i :class="item.icon"></i>&nbsp;<a :href="item.href" :slug="item.href">{{item.name}}&nbsp;<span v-if="item.badge" :style="'background-color:'+item.badge.color" class="tw-ml-2 badge bg-info">{{item.badge.value}}</span></a>
                                                    <a v-if="item.children && item.children.length" href="#" class="tw-mr-1 text-muted toggle-widgets widget-item-blocks pull-right"><i class="fa-solid fa-caret-up"></i></a><a href="#" v-if="item.is_custom=='true'" class="poly-cursor tw-mr-1 text-muted toggle-menu-options main-item-options pull-right"><i class="fas fa-cog"></i></a><span @click.stop="handleDelete(item)" :data-id="item.slug" v-if="item.is_custom=='true'" class="poly-cursor tw-mr-1 text-muted pull-right"><i class="fas fa-trash"></i></span>
                                                    <!-- Submenu container area -->
                                                    <div v-if="item.children && item.children.length" :class="['tw-mt-2 list-group nested-sortable poly-hide']">
                                                        <template v-for="item_child in item.children" :key="item_child.slug">
                                                            <div v-if="item_child.slug && !item_child.slug.includes('_add')" :class="['list-group-item sub',`nested-${parent_index}`]" :data-id="item_child.slug" :data-type="item_child.type" :data-roles="item_child.roles" :data-users="item_child.users" :data-is_custom="item_child.is_custom" :data-name="item_child.name" :data-href="item_child.href" :data-icon="item_child.icon" :data-badge="JSON.stringify(item_child.badge)" :data-slug="item_child.slug" :data-parent_slug="item_child.parent_slug">
                                                                <i :class="item_child.icon"></i>&nbsp;<a :href="item_child.href" :slug="item_child.href">{{item_child.name}}&nbsp;<span v-if="item_child.href=='#'">&nbsp;(Root)</span><span v-if="item_child.badge" :style="'background-color:'+item_child.badge.color" class="tw-ml-2 badge bg-info">{{item_child.badge.value}}</span></a>
                                                                <a href="#" v-if="item_child.is_custom=='true'" class="poly-cursor tw-mr-1 text-muted toggle-menu-options main-item-options pull-right"><i class="fas fa-cog"></i></a><span @click.stop="handleDelete(item_child)" :data-id="item.slug" v-if="item_child.is_custom=='true'" class="poly-cursor tw-mr-1 text-muted pull-right"><i class="fas fa-trash"></i></span>
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <!-- END: Submenu container area -->
                                                    <!-- Empty submenu container area -->
                                                    <div class="tw-mt-2 list-group nested-sortable">
                                                        <div :class="['list-group-item sub empty',`nested-${parent_index}`]"></div>
                                                    </div>
                                                    <!-- END: Empty submenu container area -->
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="col-12">Custom Menu Items</h4>
                                        <div id="poly-custom-menu" class="list-group col">
                                            <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1 hidden-xs"></i><?php echo _l('poly_utilities_custom_menu_list_help') ?>
                                            <div v-for="item in custom_menu_items" class="list-group-item">
                                                <div style="display:table">
                                                    <i :class="item.icon"></i>&nbsp;<a :href="item.href" :parent="item.parent_slug" :slug="item.slug" :data-type="item.type" target="_blank" rel="nofollow">{{item.name}} <span :style="'background-color:'+item.badge.color" class="tw-ml-2 badge pull-right bg-info">{{item.badge.value}}</span></a>
                                                </div>
                                                <div><i class="fa-solid fa-list fa-fw"></i> Type: {{item.type}}<span @click.stop="handleDelete(item)" :data-id="item.slug" v-if="item.is_custom=='true'" class="poly-cursor tw-mr-1 text-muted pull-right"><i class="fas fa-trash"></i></span><span @click.stop="handleEdit(item)" v-if="item.is_custom=='true'" class="poly-cursor poly-menu-item-edit tw-mr-1 text-muted pull-right"><i class="fas fa-pencil"></i></span>
                                                </div>
                                                <div class="tw-mt-1"><i class="fa-solid fa-unlock fa-fw"></i> Roles: <span class="poly-label label label-danger tw-ml-1 tw-mr-1" v-if="item.aroles && item.aroles.length==0"><?php echo _l('poly_utilities_custom_menu_admin_allow_all_access') ?></span><span v-for="role in item.aroles"><span class="poly-label label label-danger tw-ml-1 tw-mr-1" @click.stop="handleRoleInfo(role)">{{role.text}}</span></span></div>
                                                <div class="tw-mt-1"><i class="fa-solid fa-unlock fa-fw"></i> Users: <span class="poly-label label label-info tw-ml-1 tw-mr-1" v-if="item.ausers && item.ausers.length==0"><?php echo _l('poly_utilities_custom_menu_admin_allow_all_access') ?></span>
                                                    <span v-for="user in item.ausers"><span class="poly-label label label-info tw-ml-1 tw-mr-1 poly-block-users" @click.stop="handleStaffInfo(user)"><img class="avatar-user" :src="user.avatar" />{{user.text}}</span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END Menu items -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail();
echo '<script src="' . base_url('modules/poly_utilities/assets/js/lib/sortable/1.15.0/sortable.min.js') . '"></script>';
echo '<script src="' . base_url('modules/poly_utilities/assets/js/lib/select2/select2.min.js') . '"></script>';
echo '<script src="' . base_url('modules/poly_utilities/assets/js/admin/custom_menu.js') . '"></script>';
