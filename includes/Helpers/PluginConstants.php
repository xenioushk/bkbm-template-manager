<?php
namespace BKBTPL\Helpers;

/**
 * Class for plugin constants.
 *
 * @package BKBTPL
 */
class PluginConstants {

		/**
         * Static property to hold plugin options.
         *
         * @var array
         */
	public static $plugin_options = [];

	/**
	 * Initialize the plugin options.
	 */
	public static function init() {

		self::$plugin_options = get_option( 'bkb_options' );
	}

		/**
         * Get the relative path to the plugin root.
         *
         * @return string
         * @example wp-content/plugins/<plugin-name>/
         */
	public static function get_plugin_path(): string {
		return dirname( dirname( __DIR__ ) ) . '/';
	}


    /**
     * Get the plugin URL.
     *
     * @return string
     * @example http://appealwp.local/wp-content/plugins/<plugin-name>/
     */
	public static function get_plugin_url(): string {
		return plugin_dir_url( self::get_plugin_path() . BKBTPL_PLUGIN_ROOT_FILE );
	}

	/**
	 * Register the plugin constants.
	 */
	public static function register() {
		self::init();
		self::set_paths_constants();
		self::set_base_constants();
		self::set_assets_constants();
		self::set_templates_constants();
		self::set_updater_constants();
		self::set_product_info_constants();
	}

	/**
	 * Set the plugin base constants.
     *
	 * @example: $plugin_data = get_plugin_data( BKBTPL_PLUGIN_DIR . '/' . BKBTPL_PLUGIN_ROOT_FILE );
	 * echo '<pre>';
	 * print_r( $plugin_data );
	 * echo '</pre>';
	 * @example_param: Name,PluginURI,Description,Author,Version,AuthorURI,RequiresAtLeast,TestedUpTo,TextDomain,DomainPath
	 */
	private static function set_base_constants() {

		$plugin_data = get_plugin_data( BKBTPL_PLUGIN_DIR . '/' . BKBTPL_PLUGIN_ROOT_FILE );

		define( 'BKBTPL_PLUGIN_VERSION', $plugin_data['Version'] ?? '1.0.0' );
		define( 'BKBTPL_PLUGIN_TITLE', $plugin_data['Name'] ?? 'Templify KB - Knowledge Base Addon' );
		define( 'BKBTPL_TRANSLATION_DIR', $plugin_data['DomainPath'] ?? '/lang/' );
		define( 'BKBTPL_TEXT_DOMAIN', $plugin_data['TextDomain'] ?? '' );

		define( 'BKBTPL_PLUGIN_FOLDER', 'bkbm-template-manager' );
		define( 'BKBTPL_PLUGIN_CURRENT_VERSION', BKBTPL_PLUGIN_VERSION );
		define( 'BKBTPL_PLUGIN_POST_TYPE', 'bwl_kb' );
		define( 'BKBTPL_PLUGIN_TAXONOMY_CAT', 'bkb_category' );
		define( 'BKBTPL_PLUGIN_TAXONOMY_TAGS', 'bkb_tags' );
	}

	/**
	 * Set the plugin paths constants.
	 */
	private static function set_paths_constants() {
		define( 'BKBTPL_PLUGIN_ROOT_FILE', 'bkbm-template-manager.php' );
		define( 'BKBTPL_PLUGIN_DIR', self::get_plugin_path() );
		define( 'BKBTPL_PLUGIN_FILE_PATH', BKBTPL_PLUGIN_DIR );
		define( 'BKBTPL_PLUGIN_URL', self::get_plugin_url() );
	}

	/**
	 * Set the plugin assets constants.
	 */
	private static function set_assets_constants() {
		define( 'BKBTPL_PLUGIN_STYLES_ASSETS_DIR', BKBTPL_PLUGIN_URL . 'assets/styles/' );
		define( 'BKBTPL_PLUGIN_SCRIPTS_ASSETS_DIR', BKBTPL_PLUGIN_URL . 'assets/scripts/' );
		define( 'BKBTPL_PLUGIN_LIBS_DIR', BKBTPL_PLUGIN_URL . 'libs/' );
	}
	/**
	 * Set the plugin template constants.
	 */
	private static function set_templates_constants() {
		define( 'BKBTPL_TEMPLATES_DIR', 'templates/' );
		define( 'BKBM_BOOTSTRAP_FRAMEWORK', ! empty( self::$plugin_options['bkb_tpl_bootstrap_status'] ) ? 1 : 0 );
		define( 'BKBTPL_CAT_TPL', ! empty( self::$plugin_options['bkb_enable_cat_tpl'] ) ? 1 : 0 );
		define( 'BKBTPL_TAG_TPL', ! empty( self::$plugin_options['bkb_enable_tag_tpl'] ) ? 1 : 0 );
		define( 'BKBTPL_SINGLE_TPL', ! empty( self::$plugin_options['bkb_enable_single_tpl'] ) ? 1 : 0 );
	}

	/**
	 * Set the updater constants.
	 */
	private static function set_updater_constants() {

		// Only change the slug.
		$slug        = 'bkbm/notifier_bkbm_tpl.php';
		$updater_url = "https://projects.bluewindlab.net/wpplugin/zipped/plugins/{$slug}";

		define( 'BKBTPL_PLUGIN_UPDATER_URL', $updater_url ); // phpcs:ignore
		define( 'BKBTPL_PLUGIN_UPDATER_SLUG', BKBTPL_PLUGIN_FOLDER . '/' . BKBTPL_PLUGIN_ROOT_FILE ); // phpcs:ignore
		define( 'BKBTPL_PLUGIN_PATH', BKBTPL_PLUGIN_DIR );
	}

	/**
	 * Set the product info constants.
	 */
	private static function set_product_info_constants() {
		define( 'BKBTPL_PRODUCT_ID', '11888104' ); // Plugin codecanyon/themeforest Id.
		define( 'BKBTPL_PRODUCT_INSTALLATION_TAG', 'bkbm_tpl_installation_' . str_replace( '.', '_', BKBTPL_PLUGIN_VERSION ) );
	}
}
