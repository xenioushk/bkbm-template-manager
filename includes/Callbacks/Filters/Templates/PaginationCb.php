<?php
namespace BKBTPL\Callbacks\Filters\Templates;

use BKBTPL\Helpers\PluginConstants;

/**
 * Class for registering taxonomy pagination.
 *
 * @package BKBTPL
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class PaginationCb {

	/**
	 * Set pagination for KB category and tag.
	 *
	 * @param object $query The query object.
	 * @return object
	 */
	public function set_pagination( $query ) {

		$options = PluginConstants::$plugin_options;

		if ( ! is_admin() && $query->is_main_query() ) {
			$taxonomies = [
				'bkb_cat' => 'bkb_cat_pagination_conditinal_fields',
				'bkb_tag' => 'bkb_tag_pagination_conditinal_fields',
			];

			foreach ( $taxonomies as $taxonomy => $field_key ) {
				if ( is_tax( $taxonomy ) &&
					isset( $options[ $field_key ]['enabled'] ) &&
					$options[ $field_key ]['enabled'] === 'on' &&
					is_numeric( $options[ $field_key ][ $taxonomy . '_tpl_ipp' ] )
				) {
					$query->set( 'posts_per_page', $options[ $field_key ][ $taxonomy . '_tpl_ipp' ] );
					break;
				}
			}
		}

		return $query;

	}
}
