<?php
namespace BKBTPL\Helpers;

/**
 * Class for plugin helpers.
 *
 * @package BKBTPL
 */
class BkbTplHelpers {

    /**
     * Get the template hierarchy.
     *
     * @param string $template The template name.
     *
     * @return string The template file path.
     */
	public static function bkb_get_template_hierarchy( $template ) {

        // Get the template slug
        $template_slug = rtrim( $template, '.php' );
        $template      = $template_slug . '.php';

        // Check if a custom template exists in the theme folder.
        // If not, load the plugin template file.
        $file = \locate_template( [ 'bkb_template/' . $template ] ) ?: BKBTPL_PLUGIN_FILE_PATH . BKBTPL_TEMPLATES_DIR . $template;

        return apply_filters( 'rc_repl_template_' . $template, $file );
    }
}
