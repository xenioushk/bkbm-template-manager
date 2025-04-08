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

        if ( ! BKBTPL_SINGLE_TPL ) {
            return;
        }
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
}
