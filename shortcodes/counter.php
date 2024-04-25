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

if ( ! function_exists( 'wr_ferado_counter' ) ) {
	function wr_ferado_counter( $atts, $content = null ) {
		$html =  $script = $class = $icon = $number = $title = '';
		extract( shortcode_atts(
			array(
				'number'     => '',
				'title'      => '',
				'icon'       => '',
				'icon_color' => '',
				'icon_bg'    => '',
				'style'      => '',
			), $atts ) );

		if ( 'circle' == $style ) {
			$class = 'circle';
		}

		$classes = array();
		if ( $icon_color ) {
			$classes[] = 'color: ' . $icon_color . ';';
		}
		if ( $icon_bg ) {
			$classes[] = 'background-color: ' . $icon_bg . ';';
		}

		$html .= '<div class="counter-wrap ' . $class . '">';
		if ( $icon ) {
			$html .= '<div class="icon" style="' . implode( ' ', $classes ) . '"><i class="' . $icon . '"></i></div>';
		}
		$html .= '<div class="info">';
		$html .= '<span class="number">' . $number . '</span>';
		$html .= '<span class="title">' . $title . '</span>';
		$html .= '</div>';
		$html .= '</div>';

		$script .= '
			<scr' . 'ipt>
				(function($) {
					"use strict";
					$(document).ready(function() {
						$(".counter-wrap .number").counterUp({
							delay: 10,
							time: 1000
						});
					});
				})(jQuery);
			</scr' . 'ipt>
		';

		// Init jquery counter up script
		wp_enqueue_script( 'jquery-counterup', get_template_directory_uri() . '/assets/js/vendor/jquery.counterup.min.js' );

		return apply_filters( 'wr_ferado_counter', force_balance_tags( $html ) );
	}
}