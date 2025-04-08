<?php do_action( 'bkbm_before_sidebar_content', $bkb_cat_tpl_layout ); ?>

<?php

$bkb_cat_tpl_sidebar = 'bkbm_template_widget';

global $bkb_data;

if ( isset( $bkb_data['bkb_cat_tpl_sidebar'] ) && $bkb_data['bkb_cat_tpl_sidebar'] != '' ) {

        $bkb_cat_tpl_sidebar = $bkb_data['bkb_cat_tpl_sidebar'];
}

?>

<?php
if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $bkb_cat_tpl_sidebar ) ) :

		echo bkbmTplWidgetsNotice();
        endif;
?>

<?php
do_action( 'bkbm_after_sidebar_content', $bkb_cat_tpl_layout );
