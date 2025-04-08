<?php
namespace BKBTPL\Controllers\Actions\Templates;

use Xenioushk\BwlPluginApi\Api\Actions\ActionsApi;
use BKBTPL\Callbacks\Actions\Templates\SingleCb;

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
        $actions_api = new ActionsApi();

        // Initialize callbacks.
        $single_cb = new SingleCb();

        $actions = [
            [
                'tag'      => 'bkbm_before_single_content',
                'callback' => [ $single_cb, 'before_content' ],
            ],
            [
                'tag'      => 'bkbm_after_single_content',
                'callback' => [ $single_cb, 'after_content' ],
            ],

        ];

        $actions_api->add_actions( $actions )->register();
    }
}
