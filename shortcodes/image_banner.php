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

if ( ! function_exists( 'wr_ferado_image_banner' ) ) {
	function wr_ferado_image_banner( $atts, $content = null ) {
		$html = $class = '';
		extract( shortcode_atts(
			array(
				'img'       => '',
				'img_hover' => '',
				'link'      => '',
				'style'     => '',
				'bg_color'  => '',
				'rounded'   => '',
				'title'     => '',
			), $atts ) );

		// Style to display image, include 'style-1', 'style-2'
		$style = ( $style != '' ) ? ' ' . esc_attr( $style ) : '';
		
		// Background color
		$bg_color = ( $bg_color != '' ) ? ' style="background-color: ' . esc_attr( $bg_color ) . ';"' : '';

		$html .= '<div class="image-banner ' . $style . '">';
		$html .= '<a href="' . $link . '" ' . $bg_color . '>';
		$html .= '<img class="front" src="' . $img_hover . '" />';
		$html .= '<img class="back" src="' . $img . '" alt="' . $title . '" />';
		$html .= '</a>';
		$html .= '</div>';

		return apply_filters( 'wr_ferado_image_banner', force_balance_tags( $html ) );
	}
}