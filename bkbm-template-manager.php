<?php

/**
 * Plugin Name: Templify KB - Knowledge Base Addon
 * Plugin URI: https://1.envato.market/bkbm-wp
 * Description: Templify KB - Knowledge Base Addon allows you to display Knowledge Base categories, tags and single posts in custom templates without modifying any of the files inside theme forlder. Addon automatically handle BWL Knowledge base categories, tags and single posts templates. Addon comes with responsive and mobile friendly bkbm-grid layout. So that you can easily display you're Knowledge Base contents in small devices.
 * Author: Md Mahbub Alam Khan
 * Version: 1.2.0
 * Author URI: https://bluewindlab.net
 * WP Requires at least: 6.0+
 * Text Domain: bkb_tpl
 * Domain Path:     /lang/
 *
 * @package BKBTPL
 * @author Mahbub Alam Khan
 * @license GPL-2.0+
 * @link https://codecanyon.net/user/xenioushk
 * @copyright 2025 BlueWindLab
 */

namespace BKBTPL;

// security check.
defined( 'ABSPATH' ) || die( 'Unauthorized access' );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Load the plugin constants
if ( file_exists( __DIR__ . '/includes/Helpers/DependencyManager.php' ) ) {
	require_once __DIR__ . '/includes/Helpers/DependencyManager.php';
	Helpers\DependencyManager::register();
}

use KAFWPB\Base\Activate;
use KAFWPB\Base\Deactivate;

/**
 * Function to handle the activation of the plugin.
 *
 * @return void
 */
 function activate_plugin() { // phpcs:ignore
	$activate = new Activate();
	$activate->activate();
}

/**
 * Function to handle the deactivation of the plugin.
 *
 * @return void
 */
 function deactivate_plugin() { // phpcs:ignore
	Deactivate::deactivate();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate_plugin' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate_plugin' );

/**
 * Function to handle the initialization of the plugin.
 *
 * @return void
 */
function init_bkbtpl() {

	// Check if the parent plugin installed.
	if ( ! class_exists( 'BwlKbManager\\Init' ) ) {
		add_action( 'admin_notices', [ Helpers\DependencyManager::class, 'notice_missing_main_plugin' ] );
		return;
	}

	// Check parent plugin activation status.
	if ( ! ( Helpers\DependencyManager::get_product_activation_status() ) ) {
		add_action( 'admin_notices', [ Helpers\DependencyManager::class, 'notice_missing_purchase_verification' ] );
		return;
	}

	if ( class_exists( 'BKBTPL\\Init' ) ) {
		Init::register_services();
	}
}

add_action( 'init', __NAMESPACE__ . '\\init_bkbtpl' );

return;


if ( ! class_exists( 'BKBM_Template_Manager' ) ) {

    class BKBM_Template_Manager {

        function __construct() {

            // Checking plugin compatibility and require parent plugin.
            $compatibilyStatus = $this->bkb_tpl_compatibily_status();

            // Display a notice if parent plugin is missing.
            if ( $compatibilyStatus == 0 && is_admin() ) {

                add_action( 'admin_notices', [ $this, 'bkb_tpl_requirement_admin_notices' ] );
            }

            // Checking purchase status.
            $purchaseStatus = $this->getPurchaseStatus();

            // Display notice if purchase code is missing.
            if ( is_admin() && $purchaseStatus == 0 ) {

                add_action( 'admin_notices', [ $this, 'bkbTplPurchaseVerificationNotice' ] );
            }

            // if the compatibility and purchase code is okay
            // then we will set the status 1.
            $compatibilyStatus = $purchaseStatus ? 1 : 0;

            // Finally, load the required files for the addon.

            if ( $compatibilyStatus == 1 ) {

                global $bkb_data;
                $bkb_data = get_option( 'bkb_options' );

                // DECLARE CONSTANTS

                define( 'BKBM_BOOTSTRAP_FRAMEWORK', ( isset( $bkb_data['bkb_tpl_bootstrap_status'] ) && $bkb_data['bkb_tpl_bootstrap_status'] == 1 ) ? 1 : 0 );

                define( 'BWL_KB_TPL_PLUGIN_VERSION', '1.1.9' );
                define( 'BKBTPL_PARENT_PLUGIN_INSTALLED_VERSION', get_option( 'bwl_kb_plugin_version' ) );
				define( 'BKBTPL_ADDON_PARENT_PLUGIN_TITLE', '<b>BWL Knowledge Base Manager Plugin</b> ' );
                define( 'BKBTPL_ADDON_TITLE', '<b>Templify KB</b>' );
                define( 'BKBTPL_PARENT_PLUGIN_REQUIRED_VERSION', '1.4.2' ); // change plugin required version in here.
                define( 'BKBTPL_ADDON_CURRENT_VERSION', BWL_KB_TPL_PLUGIN_VERSION ); // change plugin current version in here.
                define( 'BKBTPL_ADDON_INSTALLATION_TAG', 'bkbm_tpl_installation_' . str_replace( '.', '_', BKBTPL_ADDON_CURRENT_VERSION ) );

                define( 'BKBTPL_ADDON_UPDATER_SLUG', plugin_basename( __FILE__ ) );

                define( 'BKBTPL_ADDON_CC_ID', '11888104' ); // Plugin codecanyon Id.

                add_action( 'admin_notices', [ $this, 'bkb_tpl_version_update_admin_notice' ] );

                add_action( 'wp_enqueue_scripts', [ $this, 'bkb_tpl_enqueue_scripts' ] );
                add_action( 'admin_enqueue_scripts', [ $this, 'bkb_tpl_admin_enqueue_scripts' ] );

                $this->included_files( $bkb_data ); // Include all the required files for Addon.
                $this->bkbm_template_sidebars(); // Added custom wiidget area for Addon. @Introduced in version 1.0.1

                // Initializing

                $bkb_enable_single_tpl = 1;

                if ( isset( $bkb_data['bkb_enable_single_tpl'] ) && $bkb_data['bkb_enable_single_tpl'] == '' ) {

                    $bkb_enable_single_tpl = 0;
                }

                // Pagination Filter Introduced in version 1.0.9
                add_filter( 'pre_get_posts', [ $this, 'bkb_tpl_taxonomy_filters' ] );

                /* Filter the single_template with our custom function*/

                add_filter( 'taxonomy_template', [ $this, 'bkb_texonomy_custom_template' ] );

                if ( $bkb_enable_single_tpl == 1 ) {
                    add_filter( 'single_template', [ $this, 'bkb_single_custom_template' ] );
                }
            }
        }

        public function getPurchaseStatus() {
            return 1;
            // return get_option('bkbm_purchase_verified') == 1 ? 1 : 0;
        }

        function bkbTplPurchaseVerificationNotice() {
            $licensePage = admin_url( 'edit.php?post_type=bwl_kb&page=bkb-license' );

            echo '<div class="updated"><p>You need to <a href="' . $licensePage . '">activate</a> '
                . '<b>BWL Knowledge Base Manager Plugin</b> '
                . 'to use <b>Templify KB - Knowledge Base Addon</b>. </p></div>';
        }


        // Version Manager:  Update Checking

        public function bkb_tpl_version_update_admin_notice() {

            global $current_user;

            $current_user_role = '';
            // Extract Current User Role Info
            if ( isset( $current_user->roles[0] ) ) {
                $current_user_role = $current_user->roles[0];
            }

            if ( $current_user_role == 'administrator' && BKBTPL_PARENT_PLUGIN_INSTALLED_VERSION < BKBTPL_PARENT_PLUGIN_REQUIRED_VERSION ) {

                echo '<div class="updated"><p>' . BKBTPL_ADDON_TITLE . ' Addon ( version-' . BKBTPL_ADDON_CURRENT_VERSION . ') required latest version of '
                    . BKBTPL_ADDON_PARENT_PLUGIN_TITLE . '(' . BKBTPL_PARENT_PLUGIN_REQUIRED_VERSION . ') ! <br />Please <a href="http://codecanyon.net/download?ref=xenioushk" target="_blank">Download & Update</a> ' . BKBTPL_ADDON_PARENT_PLUGIN_TITLE . '!</p></div>';
            }
        }


        function bkb_tpl_compatibily_status() {

            include_once ABSPATH . 'wp-admin/includes/plugin.php';

            $current_version = get_option( 'bwl_kb_plugin_version' );

            if ( $current_version == '' ) {
                $current_version = '1.0.6';
            }

            if ( class_exists( 'BwlKbManager\\Init' ) && $current_version > '1.0.6' ) {

                return 1; // Parent KB Plugin has been installed & activated.

            } else {

                return 0; // Parent KB Plugin is not installed or activated.

            }
        }

        function bkb_tpl_requirement_admin_notices() {

            echo '<div class="updated"><p>You need to download & install '
                . '<b><a href="https://1.envato.market/bkbm-wp" target="_blank">BWL Knowledge Base Manager Plugin</a></b> '
                . 'to use <b>Templify KB - Knowledge Base Addon</b>. </p></div>';
        }

        function bkb_tpl_taxonomy_filters( $query ) {

            global $bkb_data;

            if ( ! is_admin()
                && is_tax( 'bkb_category' )
                && $query->is_main_query()
                && isset( $bkb_data['bkb_cat_pagination_conditinal_fields'] )
                && isset( $bkb_data['bkb_cat_pagination_conditinal_fields']['enabled'] )
                && $bkb_data['bkb_cat_pagination_conditinal_fields']['enabled'] == 'on'
                && is_numeric( $bkb_data['bkb_cat_pagination_conditinal_fields']['bkb_cat_tpl_ipp'] )
            ) {

                $query->set( 'posts_per_page', $bkb_data['bkb_cat_pagination_conditinal_fields']['bkb_cat_tpl_ipp'] );
            } elseif ( ! is_admin()
                && is_tax( 'bkb_tags' )
                && $query->is_main_query()
                && isset( $bkb_data['bkb_tag_pagination_conditinal_fields'] )
                && isset( $bkb_data['bkb_tag_pagination_conditinal_fields']['enabled'] )
                && $bkb_data['bkb_tag_pagination_conditinal_fields']['enabled'] == 'on'
                && is_numeric( $bkb_data['bkb_tag_pagination_conditinal_fields']['bkb_tag_tpl_ipp'] )
            ) {

                $query->set( 'posts_per_page', $bkb_data['bkb_tag_pagination_conditinal_fields']['bkb_tag_tpl_ipp'] );
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
        function bkb_texonomy_custom_template( $template ) {

            global $wp_query, $post, $bkb_data;

            $plugindir = __DIR__;

            // Load Templify Addon Stylesheet

            $bkb_tpl_stylesheet = 0; // default false(0)

            if ( isset( $bkb_data['bkb_tpl_stylesheet'] ) && $bkb_data['bkb_tpl_stylesheet'] == 1 ) {

                $bkb_tpl_stylesheet = 1;
            }

            if ( $bkb_tpl_stylesheet == 0 ) {

                // Enqueue Stylesheet.
                wp_enqueue_style( 'bkbm-tpl-frontend' );
            }

            // Load Category Template.
            $bkb_enable_cat_tpl = 1;

            if ( isset( $bkb_data['bkb_enable_cat_tpl'] ) && $bkb_data['bkb_enable_cat_tpl'] == '' ) {

                $bkb_enable_cat_tpl = 0;
            }

            if ( is_tax( 'bkb_category' ) && $bkb_enable_cat_tpl == 1 ) {

                // Updated in version 1.0.5
                return bkb_get_template_hierarchy( 'taxonomy-bkb_category' );
            }

            // Load Tag Template.
            $bkb_enable_tag_tpl = 1;

            if ( isset( $bkb_data['bkb_enable_tag_tpl'] ) && $bkb_data['bkb_enable_tag_tpl'] == '' ) {

                $bkb_enable_tag_tpl = 0;
            }

            if ( is_tax( 'bkb_tags' ) && $bkb_enable_tag_tpl == 1 ) {

                // Updated in version 1.0.5
                return bkb_get_template_hierarchy( 'taxonomy-bkb_tags' );
            }

            return $template;
        }



        function bkb_single_custom_template( $single ) {

            global $wp_query, $post, $bkb_data;

            $plugindir = __DIR__;

            // Load Templify Addon Stylesheet

            $bkb_tpl_stylesheet = 0; // default false(0)

            if ( isset( $bkb_data['bkb_tpl_stylesheet'] ) && $bkb_data['bkb_tpl_stylesheet'] == 1 ) {

                $bkb_tpl_stylesheet = 1;
            }

            if ( $bkb_tpl_stylesheet == 0 ) {

                // Enqueue Stylesheet.
                wp_enqueue_style( 'bkbm-tpl-frontend' );
            }

            if ( $post->post_type == 'bwl_kb' ) {

                // Updated in version 1.0.5
                return bkb_get_template_hierarchy( 'single-bwl_kb' );
            }

            return $single;
        }


        /**
         * @description: Manager knowledge base template widgets.
         * @since        version 1.0.1
         * @update       23-03-2016
         * */
        function bkbm_template_sidebars() {

            global $bkb_data;

            // Default title tag for widget is h4.
            $bkb_tpl_widget_heading_tag   = 'h4';
            $bkb_tpl_widget_heading_class = 'widget-title';

            // user can set their theme similiar heading tag for KB template widget.
            if ( isset( $bkb_data['bkb_tpl_widget_heading_tag'] ) && $bkb_data['bkb_tpl_widget_heading_tag'] != '' ) {

                $bkb_tpl_widget_heading_tag = $bkb_data['bkb_tpl_widget_heading_tag'];
            }

            register_sidebar(
                [
					'name'          => __( 'BKBM Custom Sidebar', 'bkb_tpl' ),
					'id'            => 'bkbm_template_widget',
					'description'   => __( 'Custom Sidebars for Knowledgebase plugin', 'bkb_tpl' ),
					'before_widget' => '<aside id="%1$s" class="bkb-custom-sidebar %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<' . $bkb_tpl_widget_heading_tag . ' class="' . $bkb_tpl_widget_heading_class . '">',
					'after_title'   => '</' . $bkb_tpl_widget_heading_tag . '>',
                ]
            );
        }

        function included_files() {

            $bkb_action_located = locate_template( 'bkb_template/includes/bkbm-tpl-helpers.php' );

            if ( ! empty( $bkb_action_located ) ) {

                include_once get_stylesheet_directory() . '/bkb_template/includes/bkbm-tpl-helpers.php';
            } else {

                include_once __DIR__ . '/template/includes/bkbm-tpl-helpers.php';
            }
            if ( is_admin() ) {
                include_once __DIR__ . '/includes/autoupdater/WpAutoUpdater.php';
                include_once __DIR__ . '/includes/autoupdater/updater.php';
                include_once __DIR__ . '/includes/autoupdater/installer.php';
            }
        }

        function bkb_tpl_enqueue_scripts() {
            wp_register_style( 'bkbm-tpl-frontend', plugins_url( 'assets/styles/frontend.css', __FILE__ ), [], BWL_KB_TPL_PLUGIN_VERSION );
        }

        function bkb_tpl_admin_enqueue_scripts() {
            wp_enqueue_script( 'bkbm-tpl-admin', plugins_url( 'assets/scripts/admin.js', __FILE__ ), [ 'jquery' ], BWL_KB_TPL_PLUGIN_VERSION, true );
            wp_localize_script(
                'bkbm-tpl-admin',
                'BkbmTplAdminData',
                [
                    'product_id'   => BKBTPL_ADDON_CC_ID,
                    'installation' => get_option( BKBTPL_ADDON_INSTALLATION_TAG ),
                ]
            );
        }
    }

    // Addon initialization.

    function initBkbmTemplateManager() {
        new BKBM_Template_Manager();
    }

    add_action( 'init', 'initBkbmTemplateManager' );

    // Load translation file

    load_plugin_textdomain( 'bkb_tpl', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

    /**
     * Get the custom template if is set
     *
     * @since 1.0
     */
    function bkb_get_template_hierarchy( $template ) {

        // Get the template slug
        $template_slug = rtrim( $template, '.php' );
        $template      = $template_slug . '.php';

        // Check if a custom template exists in the theme folder, if not, load the plugin template file
        if ( $theme_file = locate_template( [ 'bkb_template/' . $template ] ) ) {
            $file = $theme_file;
        } else {
            $file = __DIR__ . '/template/' . $template;
        }

        return apply_filters( 'rc_repl_template_' . $template, $file );
    }
}
