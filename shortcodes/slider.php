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

if ( ! function_exists( 'wr_ferado_slider' ) ) {
	function wr_ferado_slider( $atts, $content = null ) {
		$html = $script = $class = $pagination = $navigation = $items = $itemsDesktop = $itemsTablet = $itemsMobile = $single = $autoPlay = $itemsMobile = '';
		extract( shortcode_atts(
			array(
				'pagination'   => 'true',
				'navigation'   => 'true',
				'items'        => 4,
				'itemsDesktop' => 4,
				'itemsTablet'  => 2,
				'itemsMobile'  => 1,
				'single'       => 'false',
				'autoPlay'     => 'false',
				'lazyLoad'     => 'false',
			), $atts ) );

		// Generate random id
		$length = 10;
		$id     = substr( str_shuffle( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, $length );

		if ( 'true' == $single ) {
			$class = 'single';
		}
		$script = '
			<scr' . 'ipt>
				(function($) {
					"use strict";
					$(document).ready(function() {
						var owl = $("#' . $id . '");
						owl.owlCarousel({
							navigation: ' . $navigation . ',
							navigationText: [
								"<i class=\"dashicons dashicons-arrow-left\"></i>",
								"<i class=\"dashicons dashicons-arrow-right\"></i>",
							],
							pagination: ' . $pagination . ',
							items: ' . $items . ',
							itemsDesktop: [1366, ' . $itemsDesktop . '],
							itemsTablet: [768, ' . $itemsTablet . '],
							itemsMobile: [480, ' . $itemsMobile . '],
							singleItem: ' . $single . ',
							autoPlay: ' . $autoPlay . ',
							lazyLoad: ' . $lazyLoad . ',
						});
					});
				})(jQuery);
			</scr' . 'ipt>';

		$html .= '<div id="' . $id . '" class="wr-slider ' . $class . '">';
		$html .= do_shortcode( $content );
		$html .= '</div>';

		return apply_filters( 'wr_ferado_slider', force_balance_tags( $html . $script ) );
	}
}