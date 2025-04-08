<?php

/**
 * Plugin Name: Templify KB - Knowledge Base Addon
 * Plugin URI: https://1.envato.market/bkbm-wp
 * Description: Addon allows you to display Knowledge Base categories, tags and single posts in custom templates without modifying any of the files inside theme forlder.
 * Author: Md Mahbub Alam Khan
 * Version: 2.0.0
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

                global $bkb_data;
                $bkb_data = get_option( 'bkb_options' );

                // DECLARE CONSTANTS

                define( 'BWL_KB_TPL_PLUGIN_VERSION', '1.1.9' );
                define( 'BKBTPL_PARENT_PLUGIN_INSTALLED_VERSION', get_option( 'bwl_kb_plugin_version' ) );
				define( 'BKBTPL_ADDON_PARENT_PLUGIN_TITLE', '<b>BWL Knowledge Base Manager Plugin</b> ' );
                define( 'BKBTPL_ADDON_TITLE', '<b>Templify KB</b>' );
                define( 'BKBTPL_PARENT_PLUGIN_REQUIRED_VERSION', '1.4.2' ); // change plugin required version in here.
                define( 'BKBTPL_ADDON_CURRENT_VERSION', BWL_KB_TPL_PLUGIN_VERSION ); // change plugin current version in here.
                define( 'BKBTPL_ADDON_INSTALLATION_TAG', 'bkbm_tpl_installation_' . str_replace( '.', '_', BKBTPL_ADDON_CURRENT_VERSION ) );

                define( 'BKBTPL_ADDON_UPDATER_SLUG', plugin_basename( __FILE__ ) );

                define( 'BKBTPL_ADDON_CC_ID', '11888104' ); // Plugin codecanyon Id.

                $this->included_files( $bkb_data ); // Include all the required files for Addon.

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

        function included_files() {

            $bkb_action_located = locate_template( 'bkb_template/includes/bkbm-tpl-helpers.php' );

            if ( ! empty( $bkb_action_located ) ) {

                include_once get_stylesheet_directory() . '/bkb_template/includes/bkbm-tpl-helpers.php';
            } else {

                include_once __DIR__ . '/template/includes/bkbm-tpl-helpers.php';
            }
        }
    }

}
