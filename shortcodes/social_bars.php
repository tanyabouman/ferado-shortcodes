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

if ( ! function_exists( 'wr_ferado_social_bars' ) ) {
	function wr_ferado_social_bars( $atts, $content = null ) {
		$html = '';
		extract( shortcode_atts(
			array(
				'style'      => '',
				'facebook'   => '',
				'twitter'    => '',
				'linkedin'   => '',
				'instagram'  => '',
				'gplus'      => '',
				'skype'      => '',
				'pinterest'  => '',
				'github'     => '',
				'foursquare' => '',
				'dribbble'   => '',
				'youtube'    => '',
				'rss'        => '',
			), $atts ) );

		// Style for social bar
		if ( isset( $atts['style'] ) && ! in_array( $atts['style'], array( 'large-icon', 'dark-icon', 'line-icon', 'colors-icon' ) ) ) {
			$atts['style'] = ' ';
		}

		$html .= '<div class="social-bar '. $style .'">';
		if ( $facebook ) {
			$html .='<a class="facebook" href="' . $facebook . '"><i class="fa fa-facebook"></i><span>Facebook</span></a>';
		}
		if ( $twitter ) {
			$html .='<a class="twitter" href="' . $twitter . '"><i class="fa fa-twitter"></i><span>Twitter</span></a>';
		}
		if ( $linkedin ) {
			$html .='<a class="linkedin" href="' . $linkedin . '"><i class="fa fa-linkedin"></i><span>Linkedin</span></a>';
		}
		if ( $instagram ) {
			$html .='<a class="instagram" href="' . $instagram . '"><i class="fa fa-instagram"></i><span>Instagram</span></a>';
		}
		if ( $gplus ) {
			$html .='<a class="gplus" href="' . $gplus . '"><i class="fa fa-google-plus"></i><span>Google Plus</span></a>';
		}
		if ( $skype ) {
			$html .='<a class="skype" href="skype:' . $skype . '?call"><i class="fa fa-skype"></i><span>Skype</span></a>';
		}
		if ( $pinterest ) {
			$html .='<a class="pinterest" href="' . $pinterest . '"><i class="fa fa-pinterest"></i><span>Pinterest</span></a>';
		}
		if ( $github ) {
			$html .='<a class="github" href="' . $github . '"><i class="fa fa-github"></i><span>Github</span></a>';
		}
		if ( $foursquare ) {
			$html .='<a class="foursquare" href="' . $foursquare . '"><i class="fa fa-foursquare"></i><span>Foursquare</span></a>';
		}
		if ( $dribbble ) {
			$html .='<a class="dribbble" href="' . $dribbble . '"><i class="fa fa-dribbble"></i><span>Dribbble</span></a>';
		}
		if ( $youtube ) {
			$html .='<a class="youtube" href="' . $youtube . '"><i class="fa fa-youtube"></i><span>Youtube</span></a>';
		}
		if ( $rss ) {
			$html .='<a class="rss" href="' . $rss . '"><i class="fa fa-rss"></i><span>Rss</span></a>';
		}
		$html .= '</div>';

		return apply_filters( 'wr_ferado_social_bars', force_balance_tags( $html ) );
	}
}