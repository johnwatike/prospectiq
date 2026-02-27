window.addEventListener('load', function () {
    (function ($) {
        "use strict";

        PolyCustomMenu.Init();

        const { createApp, ref, watch, onMounted, nextTick } = Vue

        const app = createApp({
            setup() {
                const menu_items = ref([]);
                const item_edit_object = ref({});
                const custom_menu_items = ref([]);
                const roles = ref([]);
                const dataLoaded = ref(false);
                const isProccessing = ref(false);
                const is_edit = ref(false);

                const selected_users_staff = ref([]);
                const selected_roles = ref([]);

                var $select_roles = null;
                var $select_users = null;
                var $input_color = null;

                const validation_fields = ref({});

                function initDataDefault() {
                    item_edit_object.value = {
                        name: '',
                        parent_slug: 'root',
                        target: '_self',
                        rel: 'nofollow',
                        type: 'default',
                        badge: {
                            color: '#FC0000',
                            value: ''
                        }
                    }

                }

                function definedWatchLoad() {
                    $input_color = $('.poly-colorpicker-input-value');
                    $select_roles = $('.select2.roles');
                    $select_users = $('.select2.users');
                }

                function updateColorPicker() {
                    $input_color.val(item_edit_object.value.badge.color);
                }

                initDataDefault();

                function fetchData(is_root = false) {
                    Promise.all([
                        fetchMenuItems(),
                        fetchCustomMenuItems(),
                        fetchRoles()
                    ]).then(([menuData, customMenuData, rolesList]) => {

                        menu_items.value = menuData;
                        customMenuData.forEach(element => {
                            try {
                                element['aroles'] = element['roles'] ? JSON.parse(element['roles']) : [];
                            } catch (error) {
                                element['aroles'] = [];
                            }

                            try {
                                element['ausers'] = element['users'] ? JSON.parse(element['users']) : [];
                            } catch (error) {
                                element['ausers'] = [];
                            }
                        });

                        custom_menu_items.value = customMenuData;
                        roles.value = rolesList;
                        dataLoaded.value = true;
                    }).catch(error => {
                        console.error('Error fetching data:', error);
                    });
                }

                watch([menu_items, custom_menu_items, roles], () => {
                    nextTick().then(() => {

                        definedWatchLoad();
                        setupSortable();
                        appColorPicker();
                        updateColorPicker();
                        PolyOperationFunctions.Icons($('.poly-utilities-aio-icon-select'));

                        if ($select_roles.length > 0) {
                            $select_roles.select2({
                                placeholder: 'Select an option',
                                width: 'resolve'
                            });

                            $select_roles.on('change', function (e) {
                                var selectedData = $select_roles.select2('data');
                                var selectedItems = selectedData.map(function (item) {
                                    return { id: item.id, text: item.text };
                                });
                                selected_roles.value = selectedItems;
                            });
                        }
                        if ($select_users.length > 0) {
                            $select_users.select2({
                                placeholder: 'Select users',
                                width: 'resolve',
                                delay: 250,
                                minimumInputLength: 2,
                                ajax: {
                                    url: `${admin_url}poly_utilities/ajax_users_search`,
                                    dataType: 'json',
                                    data: function (params) {
                                        var query = {
                                            search: params.term,
                                            type: 'public'
                                        }
                                        return query;
                                    },
                                    processResults: function (data) {
                                        let rest = data.map(user => ({ id: user.staffid, text: `${user.lastname} ${user.firstname}`, avatar: user.avatar }));
                                        return {
                                            results: rest
                                        };
                                    }
                                },
                                templateResult: function (user) {
                                    if (!user.id) {
                                        return user.text;
                                    }
                                    var $result = $(
                                        `<div class="poly-utilities-user-search-result"><img src="${user.avatar}" class="avatar-user"/> ${user.text}</div>`
                                    );
                                    return $result;
                                },
                                templateSelection: function (user) {
                                    if (!user.id) return user.text;

                                    let userAvatar = "";
                                    if (user.element) {
                                        var avatarData = $(user.element).data('avatar');
                                        if (avatarData) {
                                            userAvatar = avatarData;
                                        }
                                    }
                                    userAvatar = user.avatar ?? userAvatar;

                                    var $selection = $(
                                        `<span class="poly-utilities-user-search-result"><img src="${userAvatar}" class="avatar-user"/> ${user.text}</span>`
                                    );
                                    return $selection;
                                },
                                escapeMarkup: function (markup) {
                                    return markup;
                                },
                            });

                            $select_users.on('change', function (e) {
                                var selectedData = $select_users.select2('data');
                                var selectedItems = selectedData.map(function (item) {
                                    return { id: item.id, text: item.text, avatar: item.avatar };
                                });
                                selected_users_staff.value = selectedItems;
                            });
                        }

                    });
                });

                onMounted(() => {
                    fetchData();
                });

                function fetchMenuItems() {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: `${admin_url}poly_utilities/ajax_setup_menu_items`,
                            type: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                resolve(response);
                            },
                            error: function (xhr, status, error) {
                                reject(error);
                            }
                        });
                    });
                };

                function fetchRoles() {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: `${admin_url}poly_utilities/ajax_roles`,
                            type: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                resolve(response);
                            },
                            error: function (xhr, status, error) {
                                reject(error);
                            }
                        });
                    });
                }

                const setupSortable = () => {
                    //#region order index
                    var nestedSortables = [].slice.call(document.querySelectorAll('.poly-menu .nested-sortable'));
                    for (var i = 0; i < nestedSortables.length; i++) {
                        new Sortable(nestedSortables[i], {
                            group: 'poly-menu',
                            animation: 150,
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            onStart: function (evt) {
                                //var itemEl = evt.item; console.log(`${$(itemEl).attr('class')}`);
                            },
                            onAdd: function (evt) {
                                var itemEl = evt.item;

                                var parentEl = itemEl.parentNode;
                                if (parentEl.classList.contains('list-group')) {
                                    var parentSlug = parentEl.parentNode.getAttribute('data-slug');
                                    if (parentSlug !== null) {
                                        itemEl.classList.add('sub');
                                        itemEl.setAttribute('data-parent_slug', parentSlug);
                                    } else {
                                        itemEl.setAttribute('data-parent_slug', 'root');
                                        itemEl.classList.remove('sub');
                                    }
                                }
                            },
                            onEnd: function (evt) {
                                var items = $('body').find('#poly-active-menu .list-group-item').not(".list-group-item.sub");
                                var mainPosition = false;
                                var menu_active = [];
                                $.each(items, function (key, val) {
                                    var main_menu = $(this);
                                    if (mainPosition === false) {
                                        mainPosition = key + 1;
                                    } else {
                                        mainPosition = mainPosition + 1;
                                    }
                                    var main_obj = {};
                                    let parent_slug = main_menu.data('parent_slug');
                                    main_obj.name = main_menu.data('name');
                                    let is_custom_root = main_menu.data('is_custom');
                                    main_obj.is_custom = is_custom_root ?? false;
                                    main_obj.id = main_menu.data('id');
                                    main_obj.slug = main_menu.data('slug');
                                    main_obj.type = main_menu.data('type');
                                    main_obj.roles = JSON.stringify(main_menu.data('roles'));
                                    main_obj.users = JSON.stringify(main_menu.data('users'));
                                    main_obj.href = main_menu.data('href');
                                    main_obj.parent_slug = parent_slug ?? 'root';
                                    main_obj.icon = main_menu.data('icon');
                                    main_obj.badge = main_menu.data('badge');
                                    main_obj.position = mainPosition;

                                    var sub_items = main_menu.find('.list-group-item.sub');
                                    var subPosition = false;
                                    var menu_sub_active = [];
                                    $.each(sub_items, function (subKey, val) {
                                        if (subPosition === false) {
                                            subPosition = subKey + 1;
                                        } else {
                                            subPosition = subPosition + 1;
                                        }
                                        var sub_item = $(this);
                                        if (sub_item.data('id') !== undefined) {
                                            var sub_obj = {};
                                            sub_obj.id = sub_item.data('id');
                                            sub_obj.name = sub_item.data('name');
                                            let is_custom = sub_item.data('is_custom');
                                            sub_obj.is_custom = is_custom ?? false;
                                            sub_obj.slug = sub_item.data('slug');
                                            sub_obj.type = sub_item.data('type');
                                            sub_obj.roles = JSON.stringify(sub_item.data('roles'));
                                            sub_obj.users = JSON.stringify(sub_item.data('users'));
                                            sub_obj.href = sub_item.data('href');
                                            sub_obj.parent_slug = sub_item.data('parent_slug');
                                            sub_obj.icon = sub_item.data('icon');
                                            sub_obj.badge = sub_item.data('badge');
                                            sub_obj.position = subPosition;
                                            menu_sub_active.push(sub_obj);
                                        }
                                    });
                                    main_obj.children = menu_sub_active;

                                    menu_active.push(main_obj);
                                });
                                $.post(admin_url + 'poly_utilities/update_setup_menu_positions', {
                                    data: menu_active,
                                }).done(function (response) {
                                    let obj = JSON.parse(response);
                                    PolyMessage.displayNotification(obj.status, obj.message);
                                });
                            }
                        });
                    }

                    //#toggle sub menu
                    $(document).on('click', '.list-group.nested-sortable', function (e) {
                        e.preventDefault();
                        let $target = $(e.target);
                        let _parent = $target.closest('div');

                        let $icon = _parent.find('.toggle-widgets i');

                        let toggle_content = _parent.find('.list-group.nested-sortable');
                        if (toggle_content) {
                            toggle_content.slideToggle();
                            if ($icon.hasClass('fa-caret-up')) {
                                $icon.removeClass('fa-caret-up').addClass('fa-caret-down');
                            } else {
                                $icon.removeClass('fa-caret-down').addClass('fa-caret-up');
                            }
                        }
                    });
                    //#toggle sub menu

                    //#endregion order index
                }

                function fetchCustomMenuItems() {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: `${admin_url}poly_utilities/ajax_custom_setup_menu_items`,
                            type: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                resolve(response);
                            },
                            error: function (xhr, status, error) {
                                reject(error);
                            }
                        });
                    });
                };

                function handleStaffInfo(user) {
                    window.open(`${admin_url}staff/member/${user.id}`, '_blank');
                }
                function handleRoleInfo(role) {
                    window.open(`${admin_url}roles/role/${role.id}`, '_blank');
                }

                function isEdit(editMode) {
                    is_edit.value = editMode;
                }

                function validationForm(dataForm, errors) {
                    const obj = Object.fromEntries(dataForm.entries());
                    let state = true;
                    if (!obj.name.trim()) {
                        errors.value.name = 'Title cannot be empty.';
                        state = false;
                    } else {
                        errors.value.name = null;
                    }
                    return state;
                }

                //functions

                const handleSubmit = (event) => {
                    isProccessing.value = true;
                    const form = $('#poly_utilities_add_custom_sidebar_form').get(0);

                    let dataForm = new FormData(form);

                    if (!validationForm(dataForm, validation_fields)) {
                        isProccessing.value = false;
                        return false;
                    }

                    dataForm.set('users', JSON.stringify(selected_users_staff.value));
                    dataForm.set('roles', JSON.stringify(selected_roles.value));

                    dataForm.append('is_edit', is_edit.value);
                    dataForm.append('is_custom', 'true');

                    if (is_edit.value === true) {
                        dataForm.append('id', item_edit_object.value.id);
                    }

                    fetch(form.action, {
                        method: 'POST',
                        body: dataForm
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!is_edit.value) {
                                window.location.reload();
                            } else {
                                PolyMessage.displayNotification(`${data.status}`, `${data.message}`);
                            }
                        })
                        .catch(error => {
                            console.error('There was a problem with the fetch operation:', error);
                        }).finally(() => {
                            isProccessing.value = false;
                        });
                };

                const handleEdit = (item) => {

                    is_edit.value = true;

                    item_edit_object.value = item;

                    //Roles
                    var selectedRoles = JSON.parse(item.roles);
                    var selectedRoleItems = selectedRoles.map(function (item) {
                        return { id: item.id, text: item.text };
                    });
                    selected_roles.value = selectedRoleItems;
                    let selectedRoleIds = selectedRoleItems.map(item => item.id);
                    $select_roles.val(selectedRoleIds).trigger('change');

                    //Users
                    var selectedUsers = JSON.parse(item.users);
                    var selectedItems = selectedUsers.map(function (item) {
                        return { id: item.id, text: item.text, avatar: item.avatar };
                    });
                    selected_users_staff.value = selectedItems;

                    $select_users.empty();
                    selectedItems.forEach(function (user) {
                        var option = new Option(user.text, user.id, true, true);
                        $(option).data('avatar', user.avatar);
                        $select_users.append(option);
                    });

                    updateColorPicker();

                    PolyOperationFunctions.Scrolling();
                };

                const handleDelete = (item) => {
                    "use strict";

                    var data = {};
                    data.id = item.slug;

                    Swal.fire({
                        title: poly_utilities_settings.popup_delete.header,
                        text: poly_utilities_settings.popup_delete.message,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: poly_utilities_settings.popup_delete.confirm_color,
                        cancelButtonColor: poly_utilities_settings.popup_delete.cancel_color,
                        cancelButtonText: poly_utilities_settings.popup_delete.cancel,
                        confirmButtonText: poly_utilities_settings.popup_delete.confirm,
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post(admin_url + 'poly_utilities/delete_custom_setup_menu', data).done(function (response) {
                                window.location.reload();
                            });

                        }
                    });
                }

                function handleReactivationModule() {
                    PolyCustomMenu.handleReactivationModule();
                }

                return {
                    dataLoaded,
                    isProccessing,
                    validation_fields,
                    menu_items,
                    custom_menu_items,
                    roles,
                    item_edit_object,
                    is_edit,
                    isEdit,
                    handleEdit,
                    handleDelete,
                    handleSubmit,
                    handleStaffInfo,
                    handleRoleInfo,
                    handleReactivationModule
                };
            },

        }).mount('#polyApp');
    })(jQuery);
});