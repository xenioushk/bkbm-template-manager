<?php
/**
 * The template for displaying Knowledgebase Category posts.
 *
 * This template is used to display posts within a specific Knowledgebase category.
 * It includes layout settings, pagination, and dynamic content rendering for the category page.
 *
 * @package BKBTPL
 * @since 1.0.0
 */

/**
 * Default Template Settings
 *
 * Retrieves plugin options and sets default values for the template.
 * - `$bkb_tpl_search_box`: Whether to display the search box (default: 1).
 * - `$bkb_tpl_show_cat_desc`: Whether to display the category description (default: 1).
 * - `$bkb_list_style_type`: The style of the list (default: 'rounded').
 * - `$bkb_cat_tpl_order_by`: The order by parameter for posts (default: 'date').
 * - `$bkb_cat_tpl_order`: The order direction for posts (default: 'DESC').
 * - `$bkb_tpl_layout`: The layout type for the category page (default: 1).
 * - `$bkb_cat_pagination`: Whether pagination is enabled (default: 0).
 * - `$bkb_cat_tpl_ipp`: Items per page (default: -1 for no limit).
 * - `$paged`: Current page number (default: 1).
 */

use BKBTPL\Helpers\PluginConstants;
get_header();


// Default Template Settings.
$bkb_data = PluginConstants::$plugin_options;

// Template Type.
$bkb_template = 'category';

$bkb_tpl_search_box    = $bkb_data['bkb_tpl_search_box'] ?? 1;
$bkb_tpl_show_cat_desc = $bkb_data['bkb_tpl_show_cat_desc'] ?? 1;
$bkb_list_style_type   = $bkb_data['bkb_cat_tpl_list_style'] ?? 'rounded';
$bkb_cat_tpl_order_by  = $bkb_data['bkb_cat_tpl_order_by'] ?? 'date';
$bkb_cat_tpl_order     = $bkb_data['bkb_cat_tpl_order'] ?? 'DESC';

$bkb_cat_pagination = 0;
$bkb_cat_tpl_ipp    = -1;
$paged              = get_query_var( 'paged', 1 );

if ( isset( $bkb_data['bkb_cat_pagination_conditinal_fields'] )
&& isset( $bkb_data['bkb_cat_pagination_conditinal_fields']['enabled'] )
&& $bkb_data['bkb_cat_pagination_conditinal_fields']['enabled'] == 'on'
&& is_numeric( $bkb_data['bkb_cat_pagination_conditinal_fields']['bkb_cat_tpl_ipp'] ) ) {

	$bkb_cat_pagination = 1;
    $bkb_cat_tpl_ipp    = (int) $bkb_data['bkb_cat_pagination_conditinal_fields']['bkb_cat_tpl_ipp'];
}

// Layout settings.
$bkb_tpl_layout = $bkb_data['bkb_cat_tpl_layout'] ?? 1;
switch ( $bkb_tpl_layout ) {
    case 2:
        // full width
        $layout = [
            '1' => 'tpl-cat-content-part',
        ];
        break;
    case 3:
        // left sidebar
        $layout = [

            '0' => 'tpl-cat-sidebar-part',
            '1' => 'tpl-cat-content-part',
        ];
        break;
    default:
		// right sidebar
		$layout = [
			'0' => 'tpl-cat-content-part',
			'1' => 'tpl-cat-sidebar-part',
		];
}

do_action( 'bkbm_before_main_content' );

foreach ( $layout as $post_layout ) :

	switch ( $post_layout ) :

		case 'tpl-cat-content-part':
			include_once 'category/tpl-cat-content-part.php';

            break;

		case 'tpl-cat-sidebar-part':
            include_once 'sidebar/tpl-sidebar-part.php';
            break;

endswitch;

        endforeach;

do_action( 'bkbm_after_main_content' );

get_footer();
