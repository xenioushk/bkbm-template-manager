<?php

do_action( 'bkbm_before_sidebar_content', $bkb_tag_tpl_layout );

$sidebar = $bkb_data['bkb_tag_tpl_sidebar'] ?? 'bkbm_template_widget';

if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $sidebar ) ) :
        echo '<p>' . esc_html__( 'No widgets found in tag template sidebar.', 'bkb_tpl' ) . '</p>';
endif;

do_action( 'bkbm_after_sidebar_content', $bkb_tag_tpl_layout );
