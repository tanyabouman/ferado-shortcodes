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

if ( ! function_exists( 'wr_ferado_member' ) ) {
	function wr_ferado_member( $atts, $content = null ) {
		$html = '';
		extract( shortcode_atts(
			array(
				'avatar'   => '',
				'name'     => '',
				'position' => '',
				'bio'      => '',
				'style'    => '',
			), $atts ) );

		// Style to display member, default 'style-1'
		$style = ( $style != '' ) ? ' style-' . esc_attr( $style ) : '';

		$html .= '<div class="member ' . $style . '">';
		$html .= '<div class="avatar">';
		$html .= '<img src="' . $avatar . '" alt="' . $name . '" />';
		$html .= '</div>';
		$html .= '<div class="info">';
		$html .= '<h3 class="name">' . $name . '</h3>';
		$html .= '<span class="position">' . $position . '</span>';
		$html .= '</div>';
		$html .= '<div class="mask">';
		$html .= '<h3 class="name">' . $name . '</h3>';
		$html .= '<span class="position">' . $position . '</span>';
		$html .= '<p class="bio">' . $bio . '</p>';
		$html .= '</div>';
		$html .= '</div>';
		
		return apply_filters( 'wr_ferado_member', force_balance_tags( $html ) );
	}
}