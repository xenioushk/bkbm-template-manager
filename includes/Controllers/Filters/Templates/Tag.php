<?php
namespace BKBTPL\Controllers\Filters\Templates;

use Xenioushk\BwlPluginApi\Api\Filters\FiltersApi;
use BKBTPL\Callbacks\Filters\Templates\TagCb;

/**
 * Class for registering the frontend filters.
 *
 * @since: 1.1.0
 * @package BKBTPL
 */
class Tag {

    /**
	 * Register filters.
	 */
    public function register() {

        // Initialize API.
        $filters_api = new FiltersApi();

        // Initialize callbacks.
        $tag_cb = new TagCb();

        $filters = [
            [
                'tag'      => 'taxonomy_template',
                'callback' => [ $tag_cb, 'get_template' ],
            ],
        ];

        $filters_api->add_filters( $filters )->register();
    }
}
