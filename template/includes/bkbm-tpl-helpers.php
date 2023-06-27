<?php

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

    global $post, $bkb_data;

    $post_ID = 0;

    if (!empty($post)) {
        $post_ID = $post->ID;
    }

    $bkb_breadcrumb_conditinal_fields = 1;

    if (isset($bkb_data['bkb_breadcrumb_conditinal_fields']['enabled']) && $bkb_data['bkb_breadcrumb_conditinal_fields']['enabled'] == "") {

        $bkb_breadcrumb_conditinal_fields = 0;

        return '';
    }

    $bkb_breadcrumb_icon = 'fa fa-chevron-right';

    if (isset($bkb_data['bkb_rtrl_support']) && $bkb_data['bkb_rtrl_support'] == 1) {

        $bkb_breadcrumb_icon = 'fa fa-chevron-left';
    }

    // Breadcrumb initialization.

    $bkb_home_page_title = __('Knowledge Base', 'bkb_tpl'); // initialize home page title
    $bkb_home_page_slug = ""; //initialize home page slug.
    $bkb_home_page_html = ""; // Initialize home page url.
    $bkb_additional_url = "";

    //Home Page Title Section.
    if (isset($bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title']) && $bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title'] != "" && $bkb_home_page_title != $bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title']) {

        $bkb_home_page_title = $bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_title'];
    }

    // Home Page Slug Section.
    if (isset($bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug']) && $bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug'] != "") {

        $bkb_home_page_slug = $bkb_data['bkb_breadcrumb_conditinal_fields']['bkb_home_page_slug'];
        $bkb_home_page_html .= '<a href="' . home_url() . '/' . $bkb_home_page_slug . '">' . $bkb_home_page_title . '</a> <span class="' . $bkb_breadcrumb_icon . '"></span> ';
    }

    if (is_tax('bkb_category') && $bkb_breadcrumb_conditinal_fields == 1) {

        $bkbm_cat_id = get_queried_object()->term_id;

        //        $bkb_taxonomy_term = get_the_terms($post_ID, 'bkb_category');
        //        echo "<pre>";
        //        print_r($bkb_taxonomy_term);
        //        echo "</pre>";

        // Added in version 1.0.6

        if (defined('CPTP_VERSION')) {

            $args = array(
                'separator' => '<span class="' . $bkb_breadcrumb_icon . '"></span>'
            );
            $bkbm_category_name = get_term_parents_list($bkbm_cat_id, 'bkb_category', $args);
            $bkbm_cptp_breadcrumb_class = " cptm-breadcrumbs";
        } else {

            $bkbm_category_link = get_term_link($bkbm_cat_id, 'bkb_category');
            $bkbm_category_title = get_queried_object()->name;
            $bkbm_category_name = sprintf('<a href="%s">%s</a>', $bkbm_category_link, $bkbm_category_title);
            $bkbm_cptp_breadcrumb_class = "";
        }

        $bkb_additional_url .= $bkbm_category_name;
    } elseif (is_tax('bkb_tags') && $bkb_breadcrumb_conditinal_fields == 1) {

        $bkbm_tag_id = get_queried_object()->term_id;

        //        $bkb_taxonomy_term = get_the_terms($post_ID, 'bkb_tags');

        // Added in version 1.0.6

        if (defined('CPTP_VERSION')) {

            $args = array(
                'separator' => '<span class="' . $bkb_breadcrumb_icon . '"></span>'
            );
            //            $bkbm_tag_name = get_term_parents_list($bkb_taxonomy_term[0]->term_id, 'bkb_tags', $args);
            $bkbm_tag_name = get_term_parents_list($bkbm_tag_id, 'bkb_tags', $args);

            $bkbm_cptp_breadcrumb_class = " cptm-breadcrumbs";
        } else {

            $bkbm_tag_link = get_term_link($bkbm_tag_id, 'bkb_tags');
            $bkbm_tag_title = get_queried_object()->name;
            $bkbm_tag_name = sprintf('<a href="%s">%s</a>', $bkbm_tag_link, $bkbm_tag_title);
            $bkbm_cptp_breadcrumb_class = "";
        }

        $bkb_additional_url .= $bkbm_tag_name;
    } elseif (is_singular('bwl_kb') && $bkb_breadcrumb_conditinal_fields == 1) {

        $bkb_taxonomy_term = get_the_terms($post->ID, 'bkb_category');

        // Added in version 1.0.6

        //            if( class_exists( 'CPTP' ) ) {
        if (defined('CPTP_VERSION')) {

            $args = array(
                'separator' => '<span class="' . $bkb_breadcrumb_icon . '"></span>'
            );
            $bkbm_category_name = get_term_parents_list($bkb_taxonomy_term[0]->term_id, 'bkb_category', $args);

            $bkbm_cptp_breadcrumb_class = " cptm-breadcrumbs";
        } else {

            $bkbm_category_link = get_term_link($bkb_taxonomy_term[0]->term_id, 'bkb_category');
            $bkbm_category_title = $bkb_taxonomy_term[0]->name;
            $bkbm_category_name   = sprintf('<a href="%s">%s</a>', $bkbm_category_link, $bkbm_category_title);
            $bkbm_cptp_breadcrumb_class = "";
        }

        $bkbm_post_title = get_the_title();

        if (strlen($bkbm_post_title) >= 70) {
            // $bkbm_post_title = substr($bkbm_post_title, 0, 70) . "....";
            $bkbm_post_title = $bkbm_post_title;
        }

        $bkb_additional_url .= $bkbm_category_name . '<span class="' . $bkb_breadcrumb_icon . '"></span>' . $bkbm_post_title;
    }

    $bkbm_breadcrumbs_html = '<div class="bkbm-breadcrumbs' . $bkbm_cptp_breadcrumb_class . '">
                    <ul>
                        <li>
                        <a href="' . esc_url(home_url('/')) . '" title="' . __('Home', 'bkb_tpl') . '">' . __('Home', 'bkb_tpl') . '</a> <span class="' . $bkb_breadcrumb_icon . '"></span> 
                        ' . $bkb_home_page_html
        . $bkb_additional_url . '</li>
                    </ul>
                </div>';

    return $bkbm_breadcrumbs_html;
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

        $content_string = '<div class="grid grid-pad bkb_tpl_custom_margin">';
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
