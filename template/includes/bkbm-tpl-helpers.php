<?php


use \BwlKbManager\Base\BaseController;

// KB Custom Breadcrumb

function category_has_parent($catid)
{

    $category = get_category($catid);

    if ($category->category_parent > 0) {

        return 1;
    }

    return 0;
}

function bkbm_breadcrumbs()
{

    $baseController = new BaseController();

    if (
        isset($baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['enabled']) &&
        $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['enabled'] == ""
    ) {
        return "";
    }

    $bkb_breadcrumb_icon = 'fa fa-chevron-right';

    // Breadcrumb initialization.

    $bkb_home_page_title = __('Knowledge Base', 'bkb_tpl'); // initialize home page title
    $bkb_home_page_slug = ""; //initialize home page slug.
    $bkb_home_page_html = ""; // Initialize home page url.
    $bkb_additional_url = "";

    //Home Page Title Section.
    if (
        isset($baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title']) &&
        $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title'] != "" &&
        $bkb_home_page_title != $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title']
    ) {

        $bkb_home_page_title = $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title'];
    }

    // Home Page Slug Section.
    if (
        isset($baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug']) &&
        $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug'] != ""
    ) {

        $bkb_home_page_slug = $baseController->bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug'];
        $bkb_home_page_html .= '<a href="' . home_url() . '/' . $bkb_home_page_slug . '">' . $bkb_home_page_title . '</a> <span class="' . $bkb_breadcrumb_icon . '"></span> ';
    }

    $bkb_additional_url = getBkbBreadcrumbElements($baseController);

    $bkbm_breadcrumbs_html = '<div class="bkbm-breadcrumbs">
                    <ul>
                        <li>
                        <a href="' . esc_url(home_url('/')) . '" title="' . __('Home', 'bkb_tpl') . '">' . __('Home', 'bkb_tpl') . '</a> <span class="' . $bkb_breadcrumb_icon . '"></span> 
                        ' . $bkb_home_page_html . $bkb_additional_url . '</li>
                    </ul>
                </div>';

    return $bkbm_breadcrumbs_html;
}


function getBkbBreadcrumbElements($baseController)
{

    $bkb_breadcrumb_icon = 'fa fa-chevron-right';
    $current_url = $_SERVER['REQUEST_URI'];
    $url_parts = explode('/', $current_url);

    $breadcrumb = '';

    // Remove empty parts and the first element (domain)
    $url_parts = array_filter($url_parts);
    array_shift($url_parts);

    // Retrieve the "knowledgebase" part
    $knowledgebase_index = array_search($baseController->plugin_cpt_custom_slug, $url_parts);

    // Remove the parts before "knowledgebase"
    if ($knowledgebase_index !== false) {
        $url_parts = array_slice($url_parts, $knowledgebase_index + 1);
    }

    $category = '';
    $subcategories = [];


    foreach ($url_parts as $url_part) {
        $term = get_term_by('slug', $url_part, $baseController->plugin_cpt_tax_category);

        if ($term && !is_wp_error($term)) {
            if (empty($category)) {
                $category = $term;
            } else {
                $subcategories[] = $term;
            }
        }
    }

    if (!empty($category)) {
        $breadcrumb .= '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';

        foreach ($subcategories as $subcategory) {
            if ($subcategory->parent === $category->term_id) {
                $breadcrumb .= '<span class="' . $bkb_breadcrumb_icon . '"></span><a href="' . esc_url(get_term_link($subcategory)) . '">' . esc_html($subcategory->name) . '</a>';
                $category = $subcategory; // Set current subcategory as the new category for the next iteration
            }
        }
    }

    if (is_singular($baseController->plugin_post_type)) {
        $breadcrumb .= '<span class="' . $bkb_breadcrumb_icon . '"></span>' . get_the_title();
    }

    return $breadcrumb;
}



// Shortcode Feature added in version 1.0.1

add_shortcode('bkbm_tpl_bc', 'bkbm_breadcrumbs');


// KB Categories & Topics Pagination

if (!function_exists('bkb_tpl_pagination')) :

    function bkb_tpl_pagination($loop = null)
    {

        $big = 999999999;

        $paginate = paginate_links(
            array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'type' => 'array',
                'total' => $loop->max_num_pages,
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'prev_text' => '<i class="fa fa-caret-left icon-color"></i>',
                'next_text' => '<i class="fa fa-caret-right icon-color"></i>',
            )

        );

        $paginate_output = "";

        if ($loop->max_num_pages > 1) :

            $paginate_output .= '<div class="bkbcol-1-1"><div class="bkb-content"><div class="paginate paginate-wrapper"><ul>';

            foreach ($paginate as $page) {
                $paginate_output .= '<li>' . $page . '</li>';
            }

            $paginate_output .= '</ul> <!-- end pagination  --></div></div></div> <!--  end blog-pagination -->';

        endif;

        return $paginate_output;
    }

endif;

// Actions.

add_action('bkbm_before_main_content', 'bkbm_before_main_content');

function bkbm_before_main_content()
{

    if (BKBM_BOOTSTRAP_FRAMEWORK == 1) {

        $content_string = '<div class="container bkb_tpl_custom_margin">
            <div class="row">';
    } else {

        $content_string = '<div class="bkbm-grid bkbm-grid-pad bwl-row-cols-2-1 bkb_tpl_custom_margin">';
    }

    echo $content_string;
}

add_action('bkbm_after_main_content', 'bkbm_after_main_content');

function bkbm_after_main_content()
{

    if (BKBM_BOOTSTRAP_FRAMEWORK == 1) {

        $content_string = '</div>
        </div>';
    } else {
        $content_string = '</div>';
    }

    echo $content_string;
}

// Single Page.

add_action('bkbm_before_single_content', 'bkbm_before_single_content', 10, 1);

function bkbm_before_single_content($layout = "1")
{

    if (BKBM_BOOTSTRAP_FRAMEWORK == 1) {

        $custom_class = ($layout == 2) ? 'col-md-12 col-sm-12' : 'col-md-8 col-sm-12';

        $content_string = '<div class="' . $custom_class . ' bkb-tpl-content-pad">

                                            <div class="content">';
    } else {

        $custom_class = ($layout == 2) ? 'bkbcol-1-1' : 'bkbcol-8-12';

        $content_string = '<div class="' . $custom_class . ' bkb-tpl-content-pad">

                                            <div class="content">';
    }

    echo $content_string;
}

add_action('bkbm_after_single_content', 'bkbm_after_single_content');

function bkbm_after_single_content()
{

    if (BKBM_BOOTSTRAP_FRAMEWORK == 1) {

        $content_string = '</div>
        </div>';
    } else {
        $content_string = '</div>
        </div>';
    }

    echo $content_string;
}

// Sidebar Page.

add_action('bkbm_before_sidebar_content', 'bkbm_before_sidebar_content', 10, 1);

function bkbm_before_sidebar_content($layout = "1")
{

    if (BKBM_BOOTSTRAP_FRAMEWORK == 1) {

        $custom_class = ($layout == 2) ? 'col-md-12 col-sm-12' : 'col-md-4 col-sm-12';

        $content_string = '<div class="' . $custom_class . ' bkb-tpl-sidebar-pad">

                                            <div class="content">';
    } else {

        $custom_class = ($layout == 2) ? 'bkbcol-1-1' : 'bkbcol-4-12';

        $content_string = '<div class="' . $custom_class . ' bkb-tpl-sidebar-pad">

                                            <div class="content">';
    }

    echo $content_string;
}


add_action('bkbm_after_sidebar_content', 'bkbm_after_sidebar_content');

function bkbm_after_sidebar_content()
{

    if (BKBM_BOOTSTRAP_FRAMEWORK == 1) {

        $content_string = '</div>
        </div>';
    } else {
        $content_string = '</div>
        </div>';
    }

    echo $content_string;
}
