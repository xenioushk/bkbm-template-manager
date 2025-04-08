<?php
use BwlKbManager\Base\BaseController;

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

function bkbm_breadcrumbs() {

    $baseController = new BaseController();

    if (
        isset( $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['enabled'] ) &&
        $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['enabled'] == ''
    ) {
        return '';
    }

    $bkb_breadcrumb_icon = 'fa fa-chevron-right';

    // Breadcrumb initialization.

    $bkb_home_page_title = __( 'Knowledge Base', 'bkb_tpl' ); // initialize home page title
    $bkb_home_page_slug  = ''; // initialize home page slug.
    $bkb_home_page_html  = ''; // Initialize home page url.
    $bkb_additional_url  = '';

    // Home Page Title Section.
    if (
        isset( $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title'] ) &&
        $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title'] != '' &&
        $bkb_home_page_title != $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title']
    ) {

        $bkb_home_page_title = $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title'];
    }

    // Home Page Slug Section.
    if (
        isset( $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug'] ) &&
        $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug'] != ''
    ) {

        $bkb_home_page_slug  = $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug'];
        $bkb_home_page_html .= '<a href="' . home_url() . '/' . $bkb_home_page_slug . '">' . $bkb_home_page_title . '</a> <span class="' . $bkb_breadcrumb_icon . '"></span> ';
    }

    $bkb_additional_url = getBkbBreadcrumbElements( $baseController );

    $bkbm_breadcrumbs_html = '<div class="bkbm-breadcrumbs">
                    <ul>
                        <li>
                        <a href="' . esc_url( home_url( '/' ) ) . '" title="' . __( 'Home', 'bkb_tpl' ) . '">' . __( 'Home', 'bkb_tpl' ) . '</a> <span class="' . $bkb_breadcrumb_icon . '"></span> 
                        ' . $bkb_home_page_html . $bkb_additional_url . '</li>
                    </ul>
                </div>';

    return $bkbm_breadcrumbs_html;
}


function getBkbBreadcrumbElements( $baseController ) {

    $bkb_breadcrumb_icon = 'fa fa-chevron-right';
    $current_url         = $_SERVER['REQUEST_URI'];
    $url_parts           = explode( '/', $current_url );

    $breadcrumb = '';

    // Remove empty parts and the first element (domain)
    $url_parts = array_filter( $url_parts );
    array_shift( $url_parts );

    // Retrieve the "knowledgebase" part
    $knowledgebase_index = array_search( $baseController->plugin_cpt_custom_slug, $url_parts );

    // Remove the parts before "knowledgebase"
    if ( $knowledgebase_index !== false ) {
        $url_parts = array_slice( $url_parts, $knowledgebase_index + 1 );
    }

    $category      = '';
    $subcategories = [];

    foreach ( $url_parts as $url_part ) {
        $term = get_term_by( 'slug', $url_part, $baseController->plugin_cpt_tax_category );

        if ( $term && ! is_wp_error( $term ) ) {
            if ( empty( $category ) ) {
                $category = $term;
            } else {
                $subcategories[] = $term;
            }
        }
    }

    if ( ! empty( $category ) ) {
        $breadcrumb .= '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';

        foreach ( $subcategories as $subcategory ) {
            if ( $subcategory->parent === $category->term_id ) {
                $breadcrumb .= '<span class="' . $bkb_breadcrumb_icon . '"></span><a href="' . esc_url( get_term_link( $subcategory ) ) . '">' . esc_html( $subcategory->name ) . '</a>';
                $category    = $subcategory; // Set current subcategory as the new category for the next iteration
            }
        }
    }

    if ( is_singular( $baseController->plugin_post_type ) ) {
        $breadcrumb .= '<span class="' . $bkb_breadcrumb_icon . '"></span>' . get_the_title();
    }

    return $breadcrumb;
}


// Shortcode Feature added in version 1.0.1

add_shortcode( 'bkbm_tpl_bc', 'bkbm_breadcrumbs' );


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
