<?php
global $wp_query;
if ( $bkb_tpl_layout == 2 ) {

    $bkb_content_class = 'bkbcol-1-1';
} else {

    $bkb_tpl_layout    = 1;
    $bkb_content_class = 'bkbcol-8-12';
}

?>

<?php do_action( 'bkbm_before_single_content', $bkb_tpl_layout ); ?>

<?php if ( have_posts() ) : ?>

<header class="bkbm-archive-header">

	<?php echo do_shortcode( '[bkbm_tpl_bc /]' ); ?>

	<?php

        $bkb_tpl_cat_head_title = $bkb_data['bkb_tpl_cat_head_title'] ?? esc_html__( 'Knowledge Base Category:', 'bkb_tpl' );

	?>

    <h1 class="bkbm-archive-title">
    <?php
        echo $bkb_tpl_cat_head_title . ' ' . '<span>' . single_cat_title( '', false ) . '</span>'; //phpcs:ignore
	?>
    </h1>

	<?php
	if ( category_description() && $bkb_tpl_show_cat_desc == 1 ) : // Show an optional category description
        ?>
    <div class="bkbm-archive-meta"><?php echo category_description(); ?></div>
    <?php endif; ?>

</header><!-- .archive-header -->

<div class="bkb-taxonomy-content">

	<?php
	if ( $bkb_tpl_search_box == 1 ) {
		echo do_shortcode( '[bkb_search /]' );
	}

        $current_queried_object = $wp_query->get_queried_object();
        $category_slug          = $current_queried_object->slug;

        // If has child then we'll display the blocks.

        global $wpdb;

        $parent_cat_id = $current_queried_object->term_id;

        $child_cat_id = $wpdb->get_col( "SELECT term_id FROM $wpdb->term_taxonomy WHERE parent=$parent_cat_id" );

        $bkb_cat_has_child = 0;

        $bkb_kb_cat_default_icon = '';
        $posts_count             = 1;
        $hide_empty              = 1;
        echo do_shortcode( '[bkb_category categories="' . $category_slug . '" cols="1" orderby="' . $bkb_cat_tpl_order_by . '" order="' . $bkb_cat_tpl_order . '" limit="' . $bkb_cat_tpl_ipp . '" show_title="0" bkb_list_type="' . $bkb_list_style_type . '" paginate="' . $bkb_cat_pagination . '" posts_per_page="' . $bkb_cat_tpl_ipp . '"  paged="' . $paged . '"/]' );

	if ( $child_cat_id ) {

		$bkb_child_cats = '';

		foreach ( $child_cat_id as $kid ) {

			$childCatName = $wpdb->get_row( "SELECT name, term_id,slug FROM $wpdb->terms WHERE term_id=$kid" );

			$child_category_slug = $childCatName->slug;

			$bkb_kb_cat_icon = get_tax_meta( $childCatName->term_id, 'bkb_fa_id', true );

			if ( $bkb_kb_cat_icon == '' ) {

				$bkb_kb_cat_icon = $bkb_kb_cat_default_icon;
			}

			$bkb_cat_icon_string = '<i class="' . $bkb_kb_cat_icon . '"></i> &nbsp;';

			// Parent Category Items Count String.

			$bkb_child_cat_items_string = '';

			$bkb_child_cat_total_items = bkb_get_sub_category_count( $childCatName->term_id );

			if ( $posts_count == 1 ) {

				$bkb_child_cat_items_string .= ' (' . $bkb_child_cat_total_items . ') ';
			}

			// If user set hide elements if empty and total counted item is 0 then we print a null string.

			if ( $hide_empty == 1 && $bkb_child_cat_total_items == 0 ) {

				// $output .='';

			} else {

				$bkb_child_cats .= $child_category_slug . ',';
			}

			$custom_category_slug = substr( $bkb_child_cats, 0, strlen( $bkb_child_cats ) - 1 );

			if ( strlen( $custom_category_slug ) > 1 ) {
				$bkb_cat_has_child = 1;
			}
		}


		if ( $bkb_cat_has_child == 1 ) {

			echo do_shortcode( '[bkb_category count_info="1" posts_count="1" box_view="1" cols="2" categories="' . $custom_category_slug . '" orderby="' . $bkb_cat_tpl_order_by . '" order="' . $bkb_cat_tpl_order . '" limit="' . $bkb_cat_tpl_ipp . '" bkb_list_type="' . $bkb_list_style_type . '" posts_per_page="' . $bkb_cat_tpl_ipp . '" paginate=0/]' );
		}
	}

	?>

</div>

	<?php
else :
    get_template_part( 'content', 'none' );
endif;
    do_action( 'bkbm_after_single_content' );
