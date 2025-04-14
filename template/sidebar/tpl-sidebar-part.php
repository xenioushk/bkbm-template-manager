<?php
/**
 * Template part for displaying the sidebar in the single template.
 *
 * @package BKBTPL
 * @since 1.0.0
 */

// Fetch the current template type.
$template = $bkb_template ?? 'single';

// Fetch the sidebar based on the template.
switch ( $template ) {
    case 'category':
        $sidebar = $bkb_data['bkb_cat_tpl_sidebar'] ?? 'bkbm_template_widget';
        break;
    case 'tags':
        $sidebar = $bkb_data['bkb_tag_tpl_sidebar'] ?? 'bkbm_template_widget';
        break;
    case 'single':
        $sidebar = $bkb_data['bkb_single_tpl_sidebar'] ?? 'bkbm_template_widget';
        break;
    default:
		$sidebar = 'bkbm_template_widget';
        break;
}

do_action( 'bkbm_before_sidebar_content', $bkb_tpl_layout );

if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $sidebar ) ) :
        echo '<p>⚠️ ' . esc_html__( 'No widgets found.', 'bkb_tpl' ) . '</p>';
endif;

do_action( 'bkbm_after_sidebar_content', $bkb_tpl_layout );
