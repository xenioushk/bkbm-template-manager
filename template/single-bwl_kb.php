<?php

/**
 *
 * The Template for displaying Knowledege Base single posts
 */
use BKBTPL\Helpers\PluginConstants;
get_header();

// Default Template Settings.
$bkb_data = PluginConstants::$plugin_options;

$bkb_single_tpl_layout = 1; // 1=right_sidebar, 2=full_width, 3=left_sidebar

// Layout Settings.

if ( isset( $bkb_data['bkb_single_tpl_layout'] ) && $bkb_data['bkb_single_tpl_layout'] != '' ) {

    $bkb_single_tpl_layout = $bkb_data['bkb_single_tpl_layout'];
}

if ( $bkb_single_tpl_layout == 3 ) : // left sidebar

    $layout = [

        '0' => 'tpl-single-sidebar-part',
        '1' => 'tpl-single-content-part',
    ];

elseif ( $bkb_single_tpl_layout == 2 ) : // full width

    $layout = [
        '1' => 'tpl-single-content-part',
    ];
else : // right sidebar

    $layout = [
        '0' => 'tpl-single-content-part',
        '1' => 'tpl-single-sidebar-part',
    ];

endif;

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
