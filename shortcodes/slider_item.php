<?php
/**
 * @version    1.0
 * @package    Ferado
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

if ( ! function_exists( 'wr_ferado_slider_item' ) ) {
	function wr_ferado_slider_item( $atts, $content = null ) {
		$html = '';
		extract( shortcode_atts(
			array(

			), $atts ) );

		$html .= '<div class="wr-slider-item">';
		$html .= do_shortcode( $content );
		$html .= '</div>';

		return apply_filters( 'wr_ferado_slider_item', force_balance_tags( $html ) );
	}
}