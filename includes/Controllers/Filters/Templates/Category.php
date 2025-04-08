<?php
namespace BKBTPL\Controllers\Filters\Templates;

use Xenioushk\BwlPluginApi\Api\Filters\FiltersApi;
use BKBTPL\Callbacks\Filters\Templates\CategoryCb;

/**
 * Class for registering the frontend filters.
 *
 * @since: 1.1.0
 * @package BKBTPL
 */
class Category {

    /**
	 * Register filters.
	 */
    public function register() {

        if ( ! BKBTPL_CAT_TPL ) {
            return;
        }

        // Initialize API.
        $filters_api = new FiltersApi();

        // Initialize callbacks.
        $category_cb = new CategoryCb();

        $filters = [
            [
                'tag'      => 'taxonomy_template',
                'callback' => [ $category_cb, 'get_template' ],
            ],
        ];

        $filters_api->add_filters( $filters )->register();
    }
}
