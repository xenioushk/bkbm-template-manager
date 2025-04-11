<?php
namespace BKBTPL\Callbacks\Filters\Templates;

use BKBTPL\Helpers\BkbTplHelpers;

/**
 * Class for registering single template callback.
 *
 * @package BKBTPL
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class SingleCb {

	/**
	 * Get the template for single KB posts.
	 *
	 * @param string $single The single template.
	 * @return string
	 */
	public function get_template( $single ) {

		// Check if the current page is a KB single.

		if ( ! is_singular( BKBM_POST_TYPE ) ) {
			return $single;
		}

		return BkbTplHelpers::bkb_get_template_hierarchy( 'single-' . BKBM_POST_TYPE );
	}
}
