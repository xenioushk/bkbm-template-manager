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

		if ( ! is_admin()
				&& is_tax( 'bkb_category' )
				&& $query->is_main_query()
				&& isset( $options['bkb_cat_pagination_conditinal_fields'] )
				&& isset( $options['bkb_cat_pagination_conditinal_fields']['enabled'] )
				&& $options['bkb_cat_pagination_conditinal_fields']['enabled'] == 'on'
				&& is_numeric( $options['bkb_cat_pagination_conditinal_fields']['bkb_cat_tpl_ipp'] )
		) {
				return $query->set( 'posts_per_page', $options['bkb_cat_pagination_conditinal_fields']['bkb_cat_tpl_ipp'] );
		} elseif ( ! is_admin()
				&& is_tax( 'bkb_tags' )
				&& $query->is_main_query()
				&& isset( $options['bkb_tag_pagination_conditinal_fields'] )
				&& isset( $options['bkb_tag_pagination_conditinal_fields']['enabled'] )
				&& $options['bkb_tag_pagination_conditinal_fields']['enabled'] == 'on'
				&& is_numeric( $options['bkb_tag_pagination_conditinal_fields']['bkb_tag_tpl_ipp'] )
		) {

				return $query->set( 'posts_per_page', $options['bkb_tag_pagination_conditinal_fields']['bkb_tag_tpl_ipp'] );
		} else {
				// Do nothing.
				return $query;
		}

	}
}
