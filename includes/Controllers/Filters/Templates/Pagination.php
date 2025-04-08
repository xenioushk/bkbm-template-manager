<?php
namespace BKBTPL\Controllers\Filters\Templates;

use Xenioushk\BwlPluginApi\Api\Filters\FiltersApi;
use BKBTPL\Callbacks\Filters\Templates\PaginationCb;

/**
 * Class for registering the frontend filters.
 *
 * @since: 1.1.0
 * @package BKBTPL
 */
class Pagination {

    /**
	 * Register filters.
	 */
    public function register() {

        if ( ! BKBTPL_CAT_TPL && ! BKBTPL_TAG_TPL ) {
            return;
        }

        // Initialize API.
        $filters_api = new FiltersApi();

        // Initialize callbacks.
        $pagination_cb = new PaginationCb();

        $filters = [
            [
                'tag'      => 'pre_get_posts',
                'callback' => [ $pagination_cb, 'set_pagination' ],
            ],
        ];

        $filters_api->add_filters( $filters )->register();
    }
}
