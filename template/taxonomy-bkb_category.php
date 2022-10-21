<?php
/**
 * Template for displaying Knowledgebase Category posts.
 */
//Load current theme header content.
get_header();

// Default Template Settings.
//global $bkb_data;

$bkb_tpl_search_box = 1;
$bkb_tpl_show_cat_desc = 1;
$bkb_list_style_type = ( isset($bkb_data['bkb_cat_tpl_list_style']) && $bkb_data['bkb_cat_tpl_list_style'] != "" ) ? $bkb_data['bkb_cat_tpl_list_style'] : 'rounded'; // 1=rectangle 2=iconized 3=rounded 4=simple
$bkb_cat_tpl_layout = 1; // 1=right_sidebar, 2=full_width, 3=left_sidebar

// @Added: Version 1.0.1
$bkb_cat_tpl_order_by = 'date'; // date=date, ID=ID, title=Title.
$bkb_cat_tpl_order = 'DESC'; // ASC=Ascending, DESC = Descending.

if (isset($bkb_data['bkb_tpl_search_box']) && $bkb_data['bkb_tpl_search_box'] == "") {

        $bkb_tpl_search_box = 0;
}


if (isset($bkb_data['bkb_tpl_show_cat_desc']) && $bkb_data['bkb_tpl_show_cat_desc'] == "") {

        $bkb_tpl_show_cat_desc = 0;
}

// Layout Settings.

if (isset($bkb_data['bkb_cat_tpl_layout']) && $bkb_data['bkb_cat_tpl_layout'] != "") {

        $bkb_cat_tpl_layout = $bkb_data['bkb_cat_tpl_layout'];
}

if ($bkb_cat_tpl_layout == 3) : //left sidebar

    $layout = array(
        
        '0' => 'tpl-cat-sidebar-part',
        '1' => 'tpl-cat-content-part'
    );

elseif ($bkb_cat_tpl_layout == 2) : // full width

    $layout = array(
         '1' => 'tpl-cat-content-part'
    );
else: // right sidebar

    $layout = array(
        '0' => 'tpl-cat-content-part',
        '1' => 'tpl-cat-sidebar-part'
    );

endif;


// @Description: Order By & Order Type Settings.
// @Since: 1.0.1

if (isset($bkb_data['bkb_cat_tpl_order_by']) ) {

        $bkb_cat_tpl_order_by = $bkb_data['bkb_cat_tpl_order_by'];
}

if (isset($bkb_data['bkb_cat_tpl_order']) ) {

        $bkb_cat_tpl_order = $bkb_data['bkb_cat_tpl_order'];
}

// @Descriptio: Pagination
// @since: 1.0.1

$bkb_cat_pagination = 0;
$bkb_cat_tpl_ipp = -1;
$paged = 1;
                
    if ( isset($bkb_data['bkb_cat_pagination_conditinal_fields']) && isset($bkb_data['bkb_cat_pagination_conditinal_fields']['enabled']) && $bkb_data['bkb_cat_pagination_conditinal_fields']['enabled'] == 'on' && is_numeric( $bkb_data['bkb_cat_pagination_conditinal_fields']['bkb_cat_tpl_ipp']) ) {
        
        $bkb_cat_pagination = 1;                
        $bkb_cat_tpl_ipp = $bkb_data['bkb_cat_pagination_conditinal_fields']['bkb_cat_tpl_ipp'];
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        
    }

    /*
     *@Since: Version 1.0.5
     *@Action Ref: includes/bkbm-tpl-helpers.php
     * */

    do_action('bkbm_before_main_content');
    
         foreach( $layout as $post_layout ) :
                        
            switch ($post_layout) :

                case 'tpl-cat-content-part':

                     include_once 'category/tpl-cat-content-part.php';

                break;

                case 'tpl-cat-sidebar-part':
                    include_once 'category/tpl-cat-sidebar-part.php';
                break;

            endswitch;


        endforeach;
        
    /*
     *@Since: Version 1.0.5
     *@Action Ref: includes/bkbm-tpl-helpers.php
     * */

    do_action('bkbm_after_main_content');

get_footer();