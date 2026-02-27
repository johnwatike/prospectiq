<?php

defined('BASEPATH') or exit('No direct script access allowed');
init_head();
echo '<script src="' . base_url('modules/poly_utilities/assets/js/lib/vuejs/3.3.13/vue.global.js') . '"></script>';
?>
<div id="wrapper">
    <div id="polyApp" class="content" v-cloak>

        <div class="poly-loader">
            <div :class="{'poly-loading': !dataLoaded }">&nbsp;</div>
        </div>

        <div class="poly_utilities_settings poly-data-container" :class="{'disabled': !dataLoaded }">
            <iframe width="100%" height="100%" style="height:100vh" src="<?php echo html_escape($custom_menu['href']); ?>"></iframe>
        </div>
    </div>
</div>
<?php init_tail();

echo '<script src="' . base_url('modules/poly_utilities/assets/js/admin/custom_menu_details.js') . '"></script>';
