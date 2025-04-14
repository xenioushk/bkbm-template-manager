<?php

if ( $bkb_tpl_layout == 2 ) {

	$bkb_content_class = 'bkbcol-1-1';

} else {

	$bkb_tpl_layout    = 1;
	$bkb_content_class = 'bkbcol-8-12';
}

?>

<?php do_action( 'bkbm_before_single_content',$bkb_tpl_layout ); ?>

<?php if ( have_posts() ) : ?>

<header class="bkbm-archive-header">

	<?php echo do_shortcode( '[bkbm_tpl_bc /]' ); ?>

    <h1 class="bkbm-archive-title">
    <?php
                    $bkb_tpl_tag_head_default_title = __( 'Knowledge Base Tag:', 'bkb_tpl' );
                    $bkb_tpl_tag_head_title         = ( isset( $bkb_data['bkb_tpl_tag_head_title'] ) && $bkb_data['bkb_tpl_tag_head_title'] != '' && $bkb_tpl_tag_head_default_title != $bkb_data['bkb_tpl_tag_head_title'] ) ? $bkb_data['bkb_tpl_tag_head_title'] : $bkb_tpl_tag_head_default_title;
                    echo $bkb_tpl_tag_head_title . ' ' . '<span>' . single_tag_title( '', false ) . '</span>';

	?>
    </h1>

	<?php if ( category_description() && $bkb_tpl_show_tag_desc ) : // Show an optional category description ?>
    <div class="bkbm-archive-meta"><?php echo category_description(); ?></div>
    <?php endif; ?>

</header><!-- .archive-header -->

<div class="bkb-taxonomy-content">

	<?php
	if ( $bkb_tpl_search_box == 1 ) {
		echo do_shortcode( '[bkb_search /]' );
	}

                $current_queried_object = $wp_query->get_queried_object();
                $tag_slug               = $current_queried_object->slug;

                // If has child then I'm going to display the blocks.

                global $wpdb;

                $parent_tags_id = $current_queried_object->term_id;

                $child_tags_id = $wpdb->get_col( "SELECT term_id FROM $wpdb->term_taxonomy WHERE parent=$parent_tags_id" );

                $bkb_tag_has_child = 0;

                $bkb_kb_tags_default_icon = '';
                $posts_count              = 1;
                $hide_empty               = 1;

                echo do_shortcode( '[bkb_tags posts_count=1  tags="' . $tag_slug . '" cols=1 orderby="' . $bkb_tag_tpl_order_by . '" order="' . $bkb_tag_tpl_order . '" limit="' . $bkb_tag_tpl_ipp . '" show_title=0 bkb_list_type="' . $bkb_list_style_type . '" paginate="' . $bkb_tag_pagination . '" posts_per_page="' . $bkb_tag_tpl_ipp . '"  paged="' . $paged . '"/]' );

	if ( $child_tags_id ) {

		$bkb_child_cats = '';

		foreach ( $child_tags_id as $kid ) {

			$childCatName = $wpdb->get_row( "SELECT name, term_id,slug FROM $wpdb->terms WHERE term_id=$kid" );

			$child_category_slug = $childCatName->slug;

			$bkb_kb_tags_icon = get_tax_meta( $childCatName->term_id, 'bkb_fa_id', true );

			if ( $bkb_kb_tags_icon == '' ) {

				$bkb_kb_tags_icon = $bkb_kb_tags_default_icon;
			}

			$bkb_tag_icon_string = '<i class="' . $bkb_kb_tags_icon . '"></i> &nbsp;';

			// Parent Category Items Count String.

			$bkb_child_tags_items_string = '';

			$bkb_child_tags_total_items = bkb_get_sub_category_count( $childCatName->term_id );

			if ( $posts_count == 1 ) {

				$bkb_child_tags_items_string .= ' (' . $bkb_child_tags_total_items . ') ';
			}

			// If user set hide elements if empty and total counted item is 0 then we print a null string.

			if ( $hide_empty == 1 && $bkb_child_tags_total_items == 0 ) {

				// $output .='';

			} else {

				$bkb_child_cats .= $child_category_slug . ',';

			}

			$custom_category_slug = substr( $bkb_child_cats, 0, strlen( $bkb_child_cats ) - 1 );

			if ( strlen( $custom_category_slug ) > 1 ) {
				$bkb_tag_has_child = 1;
			}
		}


		if ( $bkb_tag_has_child == 1 ) {

			echo do_shortcode( '[bkb_tags count_info="1" posts_count="1" box_view="1" cols="2"  tags="' . $custom_category_slug . '" orderby="' . $bkb_tag_tpl_order_by . '" order="' . $bkb_tag_tpl_order . '" limit="' . $bkb_tag_tpl_ipp . '" bkb_list_type="' . $bkb_list_style_type . '" posts_per_page="' . $bkb_tag_tpl_ipp . '" /]' );

		}
	}

	?>

</div>

<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>

<?php do_action( 'bkbm_after_single_content' ); ?>
