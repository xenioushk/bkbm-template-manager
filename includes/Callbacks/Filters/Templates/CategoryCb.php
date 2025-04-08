<?php
namespace BKBTPL\Callbacks\Filters\Templates;

use BKBTPL\Helpers\BkbTplHelpers;

/**
 * Class for registering template for KB category.
 *
 * @package BKBTPL
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class CategoryCb {

	/**
	 * Get the template for KB category.
	 *
	 * @param string $template The template.
	 * @return string
	 */
	public function get_template( $template ) {

		if ( is_tax( BKBM_TAX_CAT ) ) {
			return BkbTplHelpers::bkb_get_template_hierarchy( 'taxonomy-' . BKBM_TAX_CAT );
		}
		return $template;

	}
}
