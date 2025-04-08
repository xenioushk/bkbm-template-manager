<?php
namespace BKBTPL\Callbacks\Actions\Templates;

/**
 * Class for registering single content callback.
 *
 * @package BKBTPL
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class SingleCb {

	/**
	 * Before content callback.
	 *
	 * @param int $layout The layout.
	 */
	public function before_content( $layout = 1 ) {

		$layout       = intval( $layout );
		$custom_class = ( BKBM_BOOTSTRAP_FRAMEWORK === 1 )
		? ( $layout === 2 ? 'col-md-12 col-sm-12' : 'col-md-8 col-sm-12' )
		: ( $layout === 2 ? 'bkbcol-1-1' : 'bkbcol-8-12' );

		$content_string = '<div class="' . $custom_class . ' bkb-tpl-content-pad">
                    <div class="content">';

		echo $content_string; //phpcs:ignore
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
