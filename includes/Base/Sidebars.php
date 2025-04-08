<?php
namespace BKBTPL\Base;

use BKBTPL\Helpers\PluginConstants;

/**
 * Class for register plugin sidebars.
 *
 * @since: 1.1.0
 * @package BKBTPL
 */
class Sidebars {

  	/**
     * Register the plugin text domain.
     */
	public function register() {

		$options = PluginConstants::$plugin_options;
		$id      = 'bkbm_template_widget'; // This must be unique.
		$class   = 'widget-title';
		$tag     = $options['bkb_tpl_widget_heading_tag'] ?? 'h4';

            \register_sidebar(
                [
					'name'          => esc_html__( 'BKBM Custom Sidebar', 'bkb_tpl' ),
					'id'            => $id,
					'description'   => esc_html__( 'Custom Sidebars for Knowledgebase plugin', 'bkb_tpl' ),
					'before_widget' => '<aside id="%1$s" class="bkb-custom-sidebar %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<' . $tag . ' class="' . $class . '">',
					'after_title'   => '</' . $tag . '>',
                ]
            );

	}
}
