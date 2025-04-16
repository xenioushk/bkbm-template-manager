<?php
namespace BKBTPL\Callbacks\Actions\Templates;

/**
 * Class for registering sidebar content callback.
 *
 * @package BKBTPL
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class SidebarCb {

	/**
	 * Before content callback.
	 *
	 * @param int $layout The layout.
	 */
	public function before_content( $layout = 1 ) {
		$layout       = intval( $layout );
		$custom_class = ( BKBM_BOOTSTRAP_FRAMEWORK === 1 )
		? ( $layout === 2 ? 'col-md-12 col-sm-12' : 'col-md-4 col-sm-12' )
		: ( $layout === 2 ? 'bkbcol-1-1' : 'bkbcol-4-12' );

		$custom_class = $custom_class . ' bkb-tpl-sidebar-pad';

		$sidebar_class = apply_filters( 'bkbm_sidebar_custom_class', $custom_class );
		$sidebar_id    = apply_filters( 'bkbm_sidebar_custom_id', 'secondary' );

		$content_string = "<div id='{$sidebar_id}' class='{$sidebar_class}'>
                    <div class='content'>";

		echo $content_string;//phpcs:ignore
	}

	/**
	 * After content callback.
	 */
	public function after_content() {

			$content_string = '</div>
        </div>';

			echo $content_string; //phpcs:ignore
	}
}
