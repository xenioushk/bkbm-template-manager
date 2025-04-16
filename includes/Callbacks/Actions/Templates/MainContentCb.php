<?php
namespace BKBTPL\Callbacks\Actions\Templates;

/**
 * Class for registering main content callback.
 *
 * @package BKBTPL
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class MainContentCb {

	/**
	 * Before content callback.
	 */
	public function before_content() {

		$content_string = ( BKBM_BOOTSTRAP_FRAMEWORK === 1 )
		? '<div class="container bkb_tpl_custom_margin"><div class="row">'
		: '<div class="bkbm-grid-container"><div class="bkbm-grid bkbm-grid-pad bwl-row-cols-2-1 bkb_tpl_custom_margin">';

		$content_string = apply_filters( 'bkbm_before_main_content_wrapper', $content_string );

		echo $content_string; //phpcs:ignore
	}

	/**
	 * After content callback.
	 */
	public function after_content() {

			$content_string = '</div>
        </div>';

				$content_string = apply_filters( 'bkbm_after_main_content_wrapper', $content_string );

			echo $content_string; //phpcs:ignore
	}
}
