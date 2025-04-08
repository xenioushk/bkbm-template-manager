<?php
namespace BKBTPL\Controllers\Filters;

use Xenioushk\BwlPluginApi\Api\Filters\FiltersApi;

use BKBTPL\Callbacks\Filters\Templates\SingleCb;

/**
 * Class for registering the frontend filters.
 *
 * @since: 1.1.0
 * @package BKBTPL
 */
class TemplateFilters {

    /**
	 * Register filters.
	 */
    public function register() {

        // Initialize API.
        $filters_api = new FiltersApi();

        // Initialize callbacks.
        $single_cb = new SingleCb();

        // All filters.
        $filters = [
            [
                'tag'      => 'single_template',
                'callback' => [ $single_cb, 'get_template' ],
            ],
        ];

        $filters_api->add_filters( $filters )->register();
    }
}
