<?php

namespace BKBTPL\Callbacks\Shortcodes;

use BKBTPL\Helpers\PluginConstants;

/**
 * Class BreadcrumbCb
 * Handles Breadcrumb shortcode.
 *
 * @package BKBTPL
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class BreadcrumbCb {

	/**
	 * Icon for the breadcrumb.
	 *
	 * @var $icon string Breadcrumb icon.
	 */
	public $icon = 'fa fa-chevron-right';

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Initialize the class.
	}

	/**
	 * Get the output.
	 *
	 * @param array $atts Attributes.
	 *
	 * @return string
	 */
	public function get_the_output( $atts ) {

		$atts = shortcode_atts([
			'icon' => '',
		], $atts);

		extract( $atts );

		if ( ! empty( $icon ) ) {
			$this->icon = $icon;
		}

		$options = PluginConstants::$plugin_options;

		if (
        isset( $options['bkb_breadcrumb_conditinal_fields']['enabled'] ) &&
        $options['bkb_breadcrumb_conditinal_fields']['enabled'] == ''
		) {
			return '';
		}

		// Breadcrumb initialization.

		$bkb_home_page_title = esc_html__( 'Knowledge Base', 'bkb_tpl' ); // initialize home page title
		$bkb_home_page_slug  = ''; // initialize home page slug.
		$bkb_home_page_html  = ''; // Initialize home page url.
		$bkb_additional_url  = '';

		// Home Page Title Section.
		if (
        isset( $options['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title'] ) &&
        $options['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title'] != '' &&
        $bkb_home_page_title != $options['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title']
		) {

			$bkb_home_page_title = $options['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title'];
		}

		// Home Page Slug Section.
		if (
        isset( $options['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug'] ) &&
        $options['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug'] != ''
		) {

			$bkb_home_page_slug  = $options['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug'];
			$bkb_home_page_html .= '<a href="' . home_url() . '/' . $bkb_home_page_slug . '">' . $bkb_home_page_title . '</a> <span class="' . $this->icon . '"></span> ';
		}

		$bkb_additional_url = $this->get_breadcrumb_elements();

		$bkbm_breadcrumbs_html = '<div class="bkbm-breadcrumbs">
                    <ul>
                        <li>
                        <a href="' . esc_url( home_url( '/' ) ) . '" title="' . __( 'Home', 'bkb_tpl' ) . '">' . __( 'Home', 'bkb_tpl' ) . '</a> <span class="' . $this->icon . '"></span> 
                        ' . $bkb_home_page_html . $bkb_additional_url . '</li>
                    </ul>
                </div>';

		return $bkbm_breadcrumbs_html;
	}

	/**
	 * Get breadcrumb elements.
	 *
	 * @return string
	 */
	private function get_breadcrumb_elements() {

		$current_url         = $_SERVER['REQUEST_URI']; //phpcs:ignore
		$url_parts   = explode( '/', $current_url );

		$breadcrumb = '';

		// Remove empty parts and the first element (domain)
		$url_parts = array_filter( $url_parts );
		array_shift( $url_parts );

		// Retrieve the "knowledgebase" part
		$knowledgebase_index = array_search( BKBM_CPT_SLUG, $url_parts, true );

		// Remove the parts before "knowledgebase"
		if ( $knowledgebase_index !== false ) {
			$url_parts = array_slice( $url_parts, $knowledgebase_index + 1 );
		}

		$category      = '';
		$subcategories = [];

		foreach ( $url_parts as $url_part ) {

			$term = get_term_by( 'slug', $url_part, is_tax( BKBM_TAX_TAGS ) ? BKBM_TAX_TAGS : BKBM_TAX_CAT );

			if ( $term && ! is_wp_error( $term ) ) {
				if ( empty( $category ) ) {
					$category = $term;
				} else {
					$subcategories[] = $term;
				}
			}
		}
		// Set current subcategory as the new category for the next iteration
		if ( ! empty( $category ) ) {
			$breadcrumb .= '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';

			foreach ( $subcategories as $subcategory ) {
				if ( $subcategory->parent === $category->term_id ) {
					$breadcrumb .= '<span class="' . $this->icon . '"></span><a href="' . esc_url( get_term_link( $subcategory ) ) . '">' . esc_html( $subcategory->name ) . '</a>';
					$category    = $subcategory;
				}
			}
		}

		if ( is_singular( BKBM_POST_TYPE ) ) {
			$breadcrumb .= '<span class="' . $this->icon . '"></span>' . get_the_title();
		}

		return $breadcrumb;
	}
}
