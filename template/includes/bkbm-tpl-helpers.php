<?php

// KB Custom Breadcrumb

function category_has_parent( $catid ) {

    $category = get_category( $catid );

    if ( $category->category_parent > 0 ) {

        return 1;
    }

    return 0;
}

function bkbmTplWidgetsNotice() {
    if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) :
        ob_start();

		?>

<p>
    <i class="fa fa-info-circle"></i>
    <strong><a href="<?php echo esc_url( home_url( '/' ) ); ?>wp-admin/widgets.php"
        target="_blank"><?php _e( 'Click here', 'bkb_tpl' ); ?></a></strong>
		<?php _e( 'to set sidebar widgets.', 'bkb_tpl' ); ?>
</p>

		<?php
        return ob_get_clean();
    endif;
}

// KB Categories & Topics Pagination

if ( ! function_exists( 'bkb_tpl_pagination' ) ) :

    function bkb_tpl_pagination( $loop = null ) {

        $big = 999999999;

        $paginate = paginate_links(
            [
                'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'type'      => 'array',
                'total'     => $loop->max_num_pages,
                'format'    => '?paged=%#%',
                'current'   => max( 1, get_query_var( 'paged' ) ),
                'prev_text' => '<i class="fa fa-caret-left icon-color"></i>',
                'next_text' => '<i class="fa fa-caret-right icon-color"></i>',
            ]
        );

        $paginate_output = '';

        if ( $loop->max_num_pages > 1 ) :

            $paginate_output .= '<div class="bkbcol-1-1"><div class="bkb-content"><div class="paginate paginate-wrapper"><ul>';

            foreach ( $paginate as $page ) {
                $paginate_output .= '<li>' . $page . '</li>';
            }

            $paginate_output .= '</ul> <!-- end pagination  --></div></div></div> <!--  end blog-pagination -->';

        endif;

        return $paginate_output;
    }

endif;
