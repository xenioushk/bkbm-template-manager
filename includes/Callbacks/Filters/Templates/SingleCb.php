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

		global $post;

		if ( $post->post_type === BKBM_POST_TYPE ) {

			// Updated in version 1.0.5
			return BkbTplHelpers::bkb_get_template_hierarchy( 'single-bwl_kb' );
		}

		return $single;
	}
}
