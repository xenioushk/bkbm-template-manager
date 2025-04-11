<?php

/**
 * The template for displaying Knowledgebase single posts.
 *
 * @package BKBTPL
 * @since 1.0.0
 */
use BKBTPL\Helpers\PluginConstants;
get_header();

// Default Template Settings.
$bkb_data = PluginConstants::$plugin_options;

// Layout settings.
$bkb_single_tpl_layout = $bkb_data['bkb_single_tpl_layout'] ?? 1;
switch ( $bkb_single_tpl_layout ) {
    case 2:
        // full width
        $layout = [
            '1' => 'tpl-single-content-part',
        ];
        break;
    case 3:
        // left sidebar
        $layout = [

            '0' => 'tpl-single-sidebar-part',
            '1' => 'tpl-single-content-part',
        ];
        break;
    default:
		// right sidebar
		$layout = [
			'0' => 'tpl-single-content-part',
			'1' => 'tpl-single-sidebar-part',
		];
}

do_action( 'bkbm_before_main_content' );

foreach ( $layout as $post_layout ) :

    switch ( $post_layout ) :

        case 'tpl-single-content-part':
            include_once 'single/tpl-single-content-part.php';

            break;

        case 'tpl-single-sidebar-part':
            include_once 'single/tpl-single-sidebar-part.php';
            break;

    endswitch;

endforeach;

do_action( 'bkbm_after_main_content' );

get_footer();
