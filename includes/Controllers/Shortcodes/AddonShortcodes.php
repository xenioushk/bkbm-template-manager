<?php

namespace BKBTPL\Controllers\Shortcodes;

use Xenioushk\BwlPluginApi\Api\Shortcodes\ShortcodesApi;
use BKBTPL\Callbacks\Shortcodes\BreadcrumbCb;
/**
 * Class for Addon shortcodes.
 *
 * @since: 1.1.0
 * @package BKBTPL
 */
class AddonShortcodes {

    /**
	 * Register shortcode.
	 */
    public function register() {
        // Initialize API.
        $shortcodes_api = new ShortcodesApi();

        // Initialize callbacks.
        $breadcrumb_cb = new BreadcrumbCb();

        // All Shortcodes.
        $shortcodes = [
            [
                'tag'      => 'bkbm_tpl_bc',
                'callback' => [ $breadcrumb_cb, 'get_the_output' ],
            ],
        ];

        $shortcodes_api->add_shortcodes( $shortcodes )->register();
    }
}
