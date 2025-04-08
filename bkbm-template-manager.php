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
