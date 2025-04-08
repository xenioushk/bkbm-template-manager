<?php
namespace BKBTPL\Callbacks\Filters\Templates;

use BKBTPL\Helpers\BkbTplHelpers;
use BKBTPL\Helpers\PluginConstants;
use Xenioushk\BwlPluginApi\Api\View\ViewApi;

/**
 * Class for registering template for KB category.
 *
 * @package BKBTPL
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class CategoryCb extends ViewApi {

	/**
	 * Get the template for KB category.
	 *
	 * @param string $template The template.
	 * @return string
	 */
	public function get_template( $template ) {

		// Check if the current page is a KB category.

		if ( ! is_tax( BKBM_TAX_CAT ) ) {
			return $template;
		}

			$template = BkbTplHelpers::bkb_get_template_hierarchy( 'taxonomy-' . BKBM_TAX_CAT );

			$data = [
				'bkb_data' => PluginConstants::$plugin_options,
			];
			$this->render( $template, $data );

	}
}
