<?php
namespace BKBTPL\Callbacks\Filters\Templates;

use BKBTPL\Helpers\BkbTplHelpers;

/**
 * Class for registering template for KB Tag.
 *
 * @package BKBTPL
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class TagCb {

	/**
	 * Get the template for KB Tag.
	 *
	 * @param string $template The template.
	 * @return string
	 */
	public function get_template( $template ) {

		// Check if the current page is a KB Tag.

		if ( ! is_tax( BKBM_TAX_TAGS ) ) {
			return $template;
		}

		return BkbTplHelpers::bkb_get_template_hierarchy( 'taxonomy-' . BKBM_TAX_TAGS );

	}
}
