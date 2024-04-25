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

if ( ! function_exists( 'wr_ferado_blog_post' ) ) {
	function wr_ferado_blog_post( $atts, $content = null ) {
		$html = $script = $id = $i = $column = $class = $script = $class = $limit = $orderby = $order = '';
		extract( shortcode_atts(
			array(
			'limit'   => '10',
			'orderby' => 'date',
			'order'   => 'desc',
			'column'  => '',
			'slider'  => '',
			'style'   => '',
			'title'   => '',
			'mode'    => 'horizontal',
			'cat'     => '',
			'min'     => '5',
			'max'     => '5',
			'move'    => '1',
		), $atts ) );

		// Filter post type
		$args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby'             => $orderby,
			'order'               => $order,
			'posts_per_page'      => $limit,
			'cat'                 => $cat
		);

		$blog = new WP_Query ( $args );

		$classes = array();

		// Column output (2,3 or 4 columns)
		if ( $column ) {
			$classes[] = 'columns-' . $column;
		}

		// Style of blog post list
		if ( '2' == $style ) {
			$classes[] = 'style-2';
		} elseif ( '3' == $style ) {
			$classes[] = 'style-3';
		} else {
			$classes[] = '';
		}

		// Enable post slider
		if ( $slider ) {
			if ( '3' != $style ) {
				// Generate random id
				$length = 10;
				$ids    = substr( str_shuffle( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, $length );
				$id     = ( $ids != '' ) ? ' id="' . $ids . '"' : '';
				$script = '
					<scr' . 'ipt>
						(function($) {
							"use strict";
							$(window).load(function() {
								$("#' . $ids . '").bxSlider({
									mode: "' . $mode . '",
									nextText: "<i class=\"dashicons dashicons-arrow-right-alt2\"></i>",
		  							prevText: "<i class=\"dashicons dashicons-arrow-left-alt2\"></i>",
		  							minSlides: ' . $min . ',
									maxSlides: ' . $max . ',
									moveSlides: ' . $move . ',
									pager: false
								});
							});
						})(jQuery);
					</scr' . 'ipt> 
				';
			}
		}

		$html .= '<div ' . $id . ' class="post-list ' . implode( ' ', $classes ) . '">';
			if ( '3' == $style  ) {
				if ( $title ) {
					$html .= '<h3 class="post-list-title">' . $title . '</h3>';
				}
				if ( $slider ) {
					$script = '
						<scr' . 'ipt>
							(function($) {
								"use strict";
								$(window).load(function() {
									$(".post-list .left").bxSlider({
										pagerCustom: ".right",
										mode: "' . $mode . '",
										nextText: "<i class=\"dashicons dashicons-arrow-right-alt2\"></i>",
			  							prevText: "<i class=\"dashicons dashicons-arrow-left-alt2\"></i>"
									});
								});
							})(jQuery);
						</scr' . 'ipt> 
					';
				}
				$html .= '<div class="left">';
			}

			while ( $blog->have_posts() ) : $blog->the_post();

			$thumbnail       = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
			$content         = get_the_content();
			$trimmed_content = wp_trim_words( strip_shortcodes( $content ), 20, '' );
			$trimmed_more    = wp_trim_words( strip_shortcodes( $content ), 40, '<a class="more-link" href="' . get_permalink() . '">Continue Reading</a>' );

			if ( '2' == $style ) {
				$html .= '<article id="post-' . get_the_ID() . '" class="' . implode( ' ', get_post_class() ) . '">';
				$html .= '<h2 class="entry-title">';
				$html .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
				$html .= '</h2>';
				$html .= '<div class="entry-meta">';
					ob_start();
					$byline = sprintf(
						_x( 'Posted by %s.', 'post author', 'ferado' ),
						'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
					);

					$html .= '<span class="byline"> ' . $byline . '</span>';

					// Used between list items, there is a space after the comma
					$html .= '<span class="posted-on">' . get_the_time( 'j / M / Y' ) . '</span>';

				$html .= ob_get_clean();
				$html .= '</div>';
				$html .= '</article>';
			} elseif ( '3' == $style ) {
				$html .= '<article id="post-' . get_the_ID() . '">';
				$html .= '<h2 class="entry-title">';
				$html .= '<a  href="' . get_permalink() . '">' . get_the_title() . '</a>';
				$html .= '</h2>';
				$html .= '<div class="entry-meta">';
					ob_start();
					$byline = sprintf(
						_x( 'Posted by %s.', 'post author', 'ferado' ),
						'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
					);
						$html .= '<span class="byline"> ' . $byline . '</span>';
					// Used between list items, there is a space after the comma
					$html .= '<span class="posted-on">' . get_the_time( 'j / M / Y' ) . '</span>';

				$html .= ob_get_clean();
				$html .= '</div>';
				$html .= '<p>' . $trimmed_more . '</p>';
				$html .= '</article>';		
			} else {
				$html .= '<article id="post-' . get_the_ID() . '" class="' . implode( ' ', get_post_class() ) . '">';
				$html .= '<div class="entry-thumb">';
				$html .= '<a href="' . get_permalink() . '">';
				if ( has_post_thumbnail() ) {
					$html .= get_the_post_thumbnail( get_the_ID(), 'full' );
				}
				$html .= '</a>';
				$html .= '</div>';
				$html .= '<div class="entry-content">';
				$html .= '<header class="entry-header">';
				$html .= '<h2 class="entry-title">';
				$html .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
				$html .= '</h2>';

				$html .= '<div class="entry-meta">';
					ob_start();
					// Used between list items, there is a space after the comma
					$html .= '<span class="posted-on"><i class="wr-icon-calendar"></i> ' . get_the_time( 'j M, Y' ) . '</span>';

					$byline = sprintf(
						_x( '<i class="wr-icon-user"></i>%s', 'post author', 'ferado' ),
						'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
					);
					$html .= '<span class="byline"> ' . $byline . '</span>';

					// Get comments
					if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) :
						$html .= '<span class="comments-link"><i class="wr-icon-comments"></i>';
							comments_popup_link( __( '0 Comment', 'ferado' ), __( '1 Comment', 'ferado' ), __( '% Comments', 'ferado' ) );
						$html .= '</span>';
					endif;

				$html .= ob_get_clean();
				$html .= '</div>';
				$html .= '</header>';
				$html .= $trimmed_content;
				$html .= '</div>';		
				$html .= '</article>';
			}

			endwhile; // End loop 1

			// Duplicate loop if slider enable for blog list style 3
			if ( '3' == $style ) {
				$html .= '</div>';
				$html .= '<div id="bx-pager" class="right">';
				$i;
				while ( $blog->have_posts() ) : $blog->the_post();
				$i++;
				$nav = $i - 1;
					$html .= '<article ' . $nav . ' id="post-' . get_the_ID() . '" class="' . implode( ' ', get_post_class() ) . '">';
					$html .= '<h2 class="entry-title">';
					$html .= '<a data-slide-index="' . $nav . '" href="' . get_permalink() . '">' . get_the_title() . '</a>';
					$html .= '</h2>';
					$html .= '<div class="entry-meta">';
					$html .= '<span class="posted-on">' . get_the_time( 'j / M / Y' ) . '</span>';
					$html .= '</div>';
					$html .= '</article>';
				endwhile;
				$html .= '</div>';
			}

		$html .= '</div>'; // .post-list

		// Restore original Post Data
		wp_reset_postdata();

		return apply_filters( 'wr_ferado_blog_post', force_balance_tags( $html . $script ) );
	}
}