<?php

/**
 * Plugin Name: Templify KB - Knowledge Base Addon
 * Plugin URI: https://1.envato.market/bkbm-wp
 * Description: Templify KB - Knowledge Base Addon allows you to display Knowledge Base categories, tags and single posts  in custom templates without modifying any of the files inside theme forlder. Addon automatically handle BWL Knowledge base categories, tags and single posts templates. Addon comes with responsive and mobile friendly bkbm-grid layout. So that you can easily display you're Knowledge Base contents in small devices.
 * Author: Md Mahbub Alam Khan
 * Version: 1.1.2
 * Author URI: https://1.envato.market/bkbm-wp
 * WP Requires at least: 6.0+
 * Text Domain: bkb_tpl
 */

if (!class_exists('BKBM_Template_Manager')) {

    class BKBM_Template_Manager
    {
        function __construct()
        {

            //Checking plugin compatibility and require parent plugin.
            $bkb_tpl_compatibily_status = $this->bkb_tpl_compatibily_status();

            // If plugin is not compatible and dependent plugins are required then display a notice in admin panel.
            if ($bkb_tpl_compatibily_status == 0 && is_admin()) {

                add_action('admin_notices', array($this, 'bkb_tpl_requirement_admin_notices'));
            }

            //If plugin is compatible then load all require files.

            if ($bkb_tpl_compatibily_status == 1) {

                global $bkb_data;
                $bkb_data = get_option('bkb_options');

                // DECLARE CONSTANTS

                define('BKBM_BOOTSTRAP_FRAMEWORK', (isset($bkb_data['bkb_tpl_bootstrap_status']) && $bkb_data['bkb_tpl_bootstrap_status'] == 1)  ? 1 : 0);

                define("BWL_KB_TPL_PLUGIN_VERSION", '1.1.2'); // Addon version.
                define('BKBTPL_PARENT_PLUGIN_INSTALLED_VERSION', get_option('bwl_kb_plugin_version')); // 
                define('BKBTPL_ADDON_PARENT_PLUGIN_TITLE', '<b>BWL Knowledge Base Manager Plugin</b> ');
                define('BKBTPL_ADDON_TITLE', '<b>Templify KB</b>');
                define('BKBTPL_PARENT_PLUGIN_REQUIRED_VERSION', '1.4.2'); // change plugin required version in here.
                define('BKBTPL_ADDON_CURRENT_VERSION', BWL_KB_TPL_PLUGIN_VERSION); // change plugin current version in here.
                define('BKBTPL_ADDON_UPDATER_SLUG', plugin_basename(__FILE__)); // change plugin current version in here.

                add_action('admin_notices', array($this, 'bkb_tpl_version_update_admin_notice'));

                add_action('wp_enqueue_scripts', array($this, 'bkb_tpl_enqueue_scripts'));
                add_action('admin_enqueue_scripts', array($this, 'bkb_tpl_admin_enqueue_scripts'));

                $this->included_files($bkb_data); // Include all the required files for Addon.
                $this->bkbm_template_sidebars(); // Added custom wiidget area for Addon. @Introduced in version 1.0.1

                // Initializing

                $bkb_enable_single_tpl = 1;

                if (isset($bkb_data['bkb_enable_single_tpl']) && $bkb_data['bkb_enable_single_tpl'] == "") {

                    $bkb_enable_single_tpl = 0;
                }


                // Pagination Filter Introduced in version 1.0.9
                add_filter('pre_get_posts', array($this, 'bkb_tpl_taxonomy_filters'));

                /* Filter the single_template with our custom function*/

                add_filter('taxonomy_template', array($this, 'bkb_texonomy_custom_template'));

                if ($bkb_enable_single_tpl == 1) {
                    add_filter('single_template', array($this, 'bkb_single_custom_template'));
                }
            }
        }

        //Version Manager:  Update Checking

        public function bkb_tpl_version_update_admin_notice()
        {

            global $current_user;

            $current_user_role = "";
            // Extract Current User Role Info
            if (isset($current_user->roles[0])) {
                $current_user_role = $current_user->roles[0];
            }

            if ($current_user_role == "administrator" && BKBTPL_PARENT_PLUGIN_INSTALLED_VERSION < BKBTPL_PARENT_PLUGIN_REQUIRED_VERSION) {

                echo '<div class="updated"><p>' . BKBTPL_ADDON_TITLE . ' Addon ( version-' . BKBTPL_ADDON_CURRENT_VERSION . ') required latest version of '
                    . BKBTPL_ADDON_PARENT_PLUGIN_TITLE . '(' . BKBTPL_PARENT_PLUGIN_REQUIRED_VERSION . ') ! <br />Please <a href="http://codecanyon.net/download?ref=xenioushk" target="_blank">Download & Update</a> ' . BKBTPL_ADDON_PARENT_PLUGIN_TITLE . '!</p></div>';
            }
        }


        function bkb_tpl_compatibily_status()
        {

            include_once(ABSPATH . 'wp-admin/includes/plugin.php');

            $current_version = get_option('bwl_kb_plugin_version');

            if ($current_version == "") {
                $current_version = '1.0.6';
            }


            if (class_exists('BwlKbManager\\Init') && $current_version > '1.0.6') {

                return 1; // Parent KB Plugin has been installed & activated.

            } else {

                return 0; // Parent KB Plugin is not installed or activated.

            }
        }

        function bkb_tpl_requirement_admin_notices()
        {

            echo '<div class="updated"><p>You need to download & install '
                . '<b><a href="https://1.envato.market/bkbm-wp" target="_blank">BWL Knowledge Base Manager Plugin</a></b> '
                . 'to use <b>Templify KB - Knowledge Base Addon</b>. </p></div>';
        }

        function bkb_tpl_taxonomy_filters($query)
        {

            global $bkb_data;

            if (
                !is_admin() &&
                is_tax('bkb_category') &&
                $query->is_main_query()  &&
                isset($bkb_data['bkb_cat_pagination_conditinal_fields']) &&
                isset($bkb_data['bkb_cat_pagination_conditinal_fields']['enabled']) &&
                $bkb_data['bkb_cat_pagination_conditinal_fields']['enabled'] == 'on' &&
                is_numeric($bkb_data['bkb_cat_pagination_conditinal_fields']['bkb_cat_tpl_ipp'])
            ) {

                $query->set('posts_per_page', $bkb_data['bkb_cat_pagination_conditinal_fields']['bkb_cat_tpl_ipp']);
            } else if (
                !is_admin() &&
                is_tax('bkb_tags') &&
                $query->is_main_query() &&
                isset($bkb_data['bkb_tag_pagination_conditinal_fields']) &&
                isset($bkb_data['bkb_tag_pagination_conditinal_fields']['enabled']) &&
                $bkb_data['bkb_tag_pagination_conditinal_fields']['enabled'] == 'on' &&
                is_numeric($bkb_data['bkb_tag_pagination_conditinal_fields']['bkb_tag_tpl_ipp'])
            ) {

                $query->set('posts_per_page', $bkb_data['bkb_tag_pagination_conditinal_fields']['bkb_tag_tpl_ipp']);
            } else {
                // Do nothing.
            }

            return $query;
        }


        /**
         * Get the custom template if is set
         *
         * @since 1.0
         */

        function bkb_texonomy_custom_template($template)
        {

            global $wp_query, $post, $bkb_data;

            $plugindir = dirname(__FILE__);

            //Load Templify Addon Stylesheet

            $bkb_tpl_stylesheet = 0; // default false(0)

            if (isset($bkb_data['bkb_tpl_stylesheet']) && $bkb_data['bkb_tpl_stylesheet'] == 1) {

                $bkb_tpl_stylesheet = 1;
            }


            if ($bkb_tpl_stylesheet == 0) {

                // Enqueue Stylesheet.
                wp_enqueue_style('bkbm-tpl-frontend');
            }

            //Load Category Template.
            $bkb_enable_cat_tpl = 1;

            if (isset($bkb_data['bkb_enable_cat_tpl']) && $bkb_data['bkb_enable_cat_tpl'] == "") {

                $bkb_enable_cat_tpl = 0;
            }

            if (is_tax('bkb_category') && $bkb_enable_cat_tpl == 1) {

                // Updated in version 1.0.5
                return bkb_get_template_hierarchy('taxonomy-bkb_category');
            }

            //Load Tag Template.
            $bkb_enable_tag_tpl = 1;

            if (isset($bkb_data['bkb_enable_tag_tpl']) && $bkb_data['bkb_enable_tag_tpl'] == "") {

                $bkb_enable_tag_tpl = 0;
            }

            if (is_tax('bkb_tags') && $bkb_enable_tag_tpl == 1) {

                // Updated in version 1.0.5
                return bkb_get_template_hierarchy('taxonomy-bkb_tags');
            }

            return $template;
        }



        function bkb_single_custom_template($single)
        {

            global $wp_query, $post, $bkb_data;

            $plugindir = dirname(__FILE__);

            //Load Templify Addon Stylesheet

            $bkb_tpl_stylesheet = 0; // default false(0)

            if (isset($bkb_data['bkb_tpl_stylesheet']) && $bkb_data['bkb_tpl_stylesheet'] == 1) {

                $bkb_tpl_stylesheet = 1;
            }

            if ($bkb_tpl_stylesheet == 0) {

                // Enqueue Stylesheet.
                wp_enqueue_style('bkbm-tpl-frontend');
            }

            if ($post->post_type == "bwl_kb") {

                // Updated in version 1.0.5
                return bkb_get_template_hierarchy('single-bwl_kb');
            }

            return $single;
        }


        /**
         * @description: Manager knowledge base template widgets.
         * @since version 1.0.1
         * @update 23-03-2016
         * */

        function bkbm_template_sidebars()
        {

            global $bkb_data;

            // Default title tag for widget is h4.
            $bkb_tpl_widget_heading_tag =  'h4';
            $bkb_tpl_widget_heading_class = 'widget-title';

            // user can set their theme similiar heading tag for KB template widget.
            if (isset($bkb_data['bkb_tpl_widget_heading_tag']) && $bkb_data['bkb_tpl_widget_heading_tag'] != "") {

                $bkb_tpl_widget_heading_tag =  $bkb_data['bkb_tpl_widget_heading_tag'];
            }

            register_sidebar(array(
                'name' => __('BKBM Custom Sidebar', 'bkb_tpl'),
                'id' => 'bkbm_template_widget',
                'description' => __('Custom Sidebars for Knowledgebase plugin', 'bkb_tpl'),
                'before_widget' => '<aside id="%1$s" class="bkb-custom-sidebar %2$s">',
                'after_widget' => '</aside>',
                'before_title' => '<' . $bkb_tpl_widget_heading_tag . ' class="' . $bkb_tpl_widget_heading_class . '">',
                'after_title' => '</' . $bkb_tpl_widget_heading_tag . '>'
            ));
        }

        function included_files()
        {

            $bkb_action_located = locate_template('bkb_template/includes/bkbm-tpl-helpers.php');

            if (!empty($bkb_action_located)) {

                include_once get_stylesheet_directory() . '/bkb_template/includes/bkbm-tpl-helpers.php';
            } else {

                include_once dirname(__FILE__) . '/template/includes/bkbm-tpl-helpers.php';
            }

            require_once dirname(__FILE__) . '/includes/autoupdater/WpAutoUpdater.php';
            require_once dirname(__FILE__) . '/includes/autoupdater/updater.php';
            require_once dirname(__FILE__) . '/includes/autoupdater/installer.php';
        }

        function bkb_tpl_enqueue_scripts()
        {
            wp_register_style('bkbm-tpl-frontend', plugins_url('assets/styles/frontend.css', __FILE__), array(), BWL_KB_TPL_PLUGIN_VERSION);
        }

        function bkb_tpl_admin_enqueue_scripts()
        {
            wp_enqueue_script('bkbm-tpl-admin', plugins_url('assets/scripts/admin.js', __FILE__), ['jquery'], BWL_KB_TPL_PLUGIN_VERSION, TRUE);
            wp_localize_script(
                'bkbm-tpl-admin',
                'BkbmTplAdminData',
                [
                    'product_id' => 11888104,
                    'installation' => get_option('bkbm_tpl_installation')
                ]
            );
        }
    }

    /*------------------------------ Initialization ---------------------------------*/

    function init_bkbm_template_manager()
    {
        new BKBM_Template_Manager();
    }

    add_action('init', 'init_bkbm_template_manager');

    /*------------------------------  TRANSLATION FILE ---------------------------------*/

    load_plugin_textdomain('bkb_tpl', FALSE, dirname(plugin_basename(__FILE__)) . '/lang/');

    /**
     * Get the custom template if is set
     *
     * @since 1.0
     */

    function bkb_get_template_hierarchy($template)
    {

        // Get the template slug
        $template_slug = rtrim($template, '.php');
        $template = $template_slug . '.php';

        // Check if a custom template exists in the theme folder, if not, load the plugin template file
        if ($theme_file = locate_template(array('bkb_template/' . $template))) {
            $file = $theme_file;
        } else {
            $file = dirname(__FILE__) . '/template/' . $template;
        }

        return apply_filters('rc_repl_template_' . $template, $file);
    }
}
