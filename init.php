<?php
/**
 * Plugin Name: Ferado Shortcodes
 * Plugin URI:  http://www.woorockets.com
 * Description: Extra shortcodes for Ferado theme
 * Version:     1.0
 * Author:      WooRockets Team <support@www.woorockets.com>
 * Author URI:  http://www.woorockets.com
 * License:     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

// Set custom error reporting level
error_reporting( E_ALL ^ E_NOTICE );

// Define path to this plugin file
define( 'FERADO_SHORTCODE_URL', plugins_url( '', __FILE__ ) );

/**
 * Include shortcodes file.
 *
 * @package  Ferado
 * @since    1.0
 */
$shortcodes = 'product_slider, product_filter, blog_post, slider, slider_item, counter, image_banner, social_bars, member';
$shortcodes = explode( ',', $shortcodes );
$shortcodes = array_map( 'trim', $shortcodes );

foreach ( $shortcodes as $shortcode ) {
	require_once dirname( __FILE__ ) . '/shortcodes/' . $shortcode . '.php';
	add_shortcode( 'ferado_' . $shortcode, 'wr_ferado_' . $shortcode );
}

/**
 * Remove automatics - wpautop and support shortcode on widget.
 *
 * @package  Ferado
 * @since    1.0
 */
if ( ! function_exists( 'ferado_pre_shortcode' ) ) {
	function ferado_pre_shortcode( $content ) {
		$shortcodes = 'product_slider, product_filter, blog_post, slider, slider_item, counter, image_banner, social_bars, member';
		$shortcodes = explode( ',', $shortcodes );
		$shortcodes = array_map( 'trim', $shortcodes );

		global $shortcode_tags;

		// Backup current registered shortcodes and clear them all out
		$orig_shortcode_tags = $shortcode_tags;
		$shortcode_tags = array( );

		foreach ( $shortcodes as $shortcode ) {
			add_shortcode( 'ferado_' . $shortcode, 'wr_ferado_' . $shortcode );
		}
		// Do the shortcode (only the one above is registered)
		$content = do_shortcode( $content );

		// Put the original shortcodes back
		$shortcode_tags = $orig_shortcode_tags;

		return $content;
	}
	add_filter( 'widget_text', 'ferado_pre_shortcode', 7 );
	add_filter( 'the_content', 'ferado_pre_shortcode', 7 );
}

/**
 * Enqueue scripts.
 *
 * @package  Ferado
 * @since    1.0
 */
if ( ! function_exists( 'wr_ferado_shortcodes_scripts' ) ) {
	function wr_ferado_shortcodes_scripts() {
		wp_enqueue_style( 'main-css', FERADO_SHORTCODE_URL . '/assets/css/shortcodes.css' );
	}
	add_action( 'wp_enqueue_scripts', 'wr_ferado_shortcodes_scripts' );	
}
