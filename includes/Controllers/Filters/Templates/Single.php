<?php
namespace BKBTPL\Controllers\Filters\Templates;

use Xenioushk\BwlPluginApi\Api\Filters\FiltersApi;
use BKBTPL\Callbacks\Filters\Templates\SingleCb;

/**
 * Class for registering the frontend filters.
 *
 * @since: 1.1.0
 * @package BKBTPL
 */
class Single {

    /**
	 * Register filters.
	 */
    public function register() {

        // Load Helper File.

        $this->load_helper_file();

        // Initialize API.
        $filters_api = new FiltersApi();

        // Initialize callbacks.
        $single_cb = new SingleCb();

        $filters = [
            [
                'tag'      => 'template_include',
                'callback' => [ $single_cb, 'get_template' ],
            ],
        ];

        $filters_api->add_filters( $filters )->register();
    }

    /**
     * Load the helper file.
     *
     * @since 1.0.0
     */
    public function load_helper_file() {

        $file = locate_template( 'bkb_template/includes/bkbm-tpl-helpers.php' )
			?: BKBTPL_PLUGIN_PATH . BKBTPL_TEMPLATES_DIR . 'includes/bkbm-tpl-helpers.php';

			include_once $file;
    }
}
