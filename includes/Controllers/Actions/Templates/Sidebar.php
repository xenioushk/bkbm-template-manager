<?php
namespace BKBTPL\Controllers\Actions\Templates;

use Xenioushk\BwlPluginApi\Api\Actions\ActionsApi;
use BKBTPL\Callbacks\Actions\Templates\SidebarCb;

/**
 * Class for registering the frontend sidebar actions.
 *
 * @since: 1.1.0
 * @package BKBTPL
 */
class Sidebar {

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
        $sidebar_cb = new SidebarCb();

        $actions = [
            [
                'tag'      => 'bkbm_before_sidebar_content',
                'callback' => [ $sidebar_cb, 'before_content' ],
            ],
            [
                'tag'      => 'bkbm_after_sidebar_content',
                'callback' => [ $sidebar_cb, 'after_content' ],
            ],

        ];

        $actions_api->add_actions( $actions )->register();
    }
}
