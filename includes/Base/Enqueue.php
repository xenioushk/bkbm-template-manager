<?php
namespace BKBTPL\Base;

/**
 * Class for registering the plugin scripts and styles.
 *
 * @package BKBTPL
 */
class Enqueue {

	/**
	 * Frontend script slug.
	 *
	 * @var string $frontend_script_slug
	 */
	private $frontend_script_slug;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Frontend script slug.
		// This is required to hook the loclization texts.
		$this->frontend_script_slug = 'bkbm-tpl-frontend';
	}

	/**
	 * Register the plugin scripts and styles loading actions.
	 */
	public function register() {
		add_action( 'wp_enqueue_scripts', [ $this, 'get_the_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'get_the_scripts' ] );
	}

	/**
	 * Load the plugin styles.
	 */
	public function get_the_styles() {

		wp_enqueue_style(
            $this->frontend_script_slug,
            BKBTPL_PLUGIN_STYLES_ASSETS_DIR . 'frontend.css',
            [],
            BKBTPL_PLUGIN_VERSION
		);
	}

	/**
	 * Load the plugin scripts.
	 */
	public function get_the_scripts() {

		// Register JS

		// wp_enqueue_script(
		// $this->frontend_script_slug,
		// BKBTPL_PLUGIN_SCRIPTS_ASSETS_DIR . 'frontend.js',
		// [ 'jquery' ],
		// BKBTPL_PLUGIN_VERSION,
		// true
		// );

		// Load frontend variables used by the JS files.
		$this->get_the_localization_texts();
	}

	/**
	 * Load the localization texts.
	 */
	private function get_the_localization_texts() {

		// Localize scripts.
		// Frontend.
		// Access data: BkbTplFrontendData.version
		wp_localize_script(
            'jquery',
            'BkbTplFrontendData',
            [
				'version' => BKBTPL_PLUGIN_VERSION,
			]
		);
	}
}
