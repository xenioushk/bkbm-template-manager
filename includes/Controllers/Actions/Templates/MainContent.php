<?php
namespace BKBTPL\Controllers\Actions\Templates;

use Xenioushk\BwlPluginApi\Api\Actions\ActionsApi;
use BKBTPL\Callbacks\Actions\Templates\MainContentCb;

/**
 * Class for registering the frontend main content actions.
 *
 * @since: 1.1.0
 * @package BKBTPL
 */
class MainContent {

    /**
	 * Register filters.
	 */
    public function register() {

        if ( ! BKBTPL_SINGLE_TPL && ! BKBTPL_CAT_TPL && ! BKBTPL_TAG_TPL ) {
            return;
        }
        // Initialize API.
        $actions_api = new ActionsApi();

        // Initialize callbacks.
        $main_content_cb = new MainContentCb();

        $actions = [
            [
                'tag'      => 'bkbm_before_main_content',
                'callback' => [ $main_content_cb, 'before_content' ],
            ],
            [
                'tag'      => 'bkbm_after_main_content',
                'callback' => [ $main_content_cb, 'after_content' ],
            ],

        ];

        $actions_api->add_actions( $actions )->register();
    }
}
