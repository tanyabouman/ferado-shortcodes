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

if ( class_exists( 'Woocommerce' ) ) {
	if ( ! function_exists( 'wr_ferado_product_filter' ) ) {
		function wr_ferado_product_filter( $atts, $content = null ) {
			$html = $script = $title = $limit = $orderby = $order = $single = $class = $filter_position = '';
			extract( shortcode_atts(
				array(
					'limit'           => -1,
					'orderby'         => 'date',
					'order'           => 'desc',
					'cat'             => '',
					'layout'          => '',
					'col'             => '',
					'title'           => '',
					'filter_position' => 'right',
				), $atts ) );

			// Global variables
			global $post, $product, $woocommerce;

			// The meta query for the page
			$meta_query = WC()->query->get_meta_query();

			// Generate random id
			$length = 10;
			$ids    = substr( str_shuffle( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, $length );

			// Filter product post type
			$args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'posts_per_page'      => $limit,
				'ignore_sticky_posts' => 1,
				'orderby'             => $orderby,
				'order'               => $order,
				'meta_query'          => $meta_query,
			);

			if ( ! empty( $cat ) ) {
				$args['tax_query'] = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'id',
						'terms'    => explode( ',', $cat )
					),
				);
			}

			$products = new WP_Query( $args );

			// Retrieve all the categories and save them as a variable $categories
			$categories = get_terms( 'product_cat', array( 'include' => $cat ) );

			// Filter position
			if ( 'center' == $filter_position ) {
				$filter_position = 'center';
			} elseif ( 'left' == $filter_position ) {
				$filter_position = 'left';
			} elseif ( 'right' == $filter_position ) {
				$filter_position = 'right';
			}
			// Show filter
			if ( $categories ) {
				$html .= '<nav class="filters-product cl ' . $filter_position . '">';
				if ( $title ) :
					$html .= '<div class="filter-title">' . $title . '</div>';
				endif;

				$html .= '<a data-filter="*" class="selected">' . esc_html__( 'All', 'exmax' ) . '</a>';

				// Use foreach to split $categories into individual categories and store as variable $category
				foreach ( $categories as $category ):
					$html .= '<a data-filter=".' . $category->slug . '">' . $category->name . '</a>';
				endforeach;

				$html .= '</nav>';
			}

			$script .= '
				<scr' . 'ipt>
					(function($) {
						"use strict";
						$(window).load(function() {
							var $ = jQuery;
							var pcontent = $("#' . $ids . '");
							pcontent.isotope({
								itemSelector: ".product",
								layoutMode: "fitRows",
							});
							$("nav.filters-product a").click(function () {
								var selector = $(this).attr("data-filter");
								$("#' . $ids . '").isotope({
									filter: selector,
									transitionDuration: "0.8s"
								});
								return false;
							});

							var $optionSets = $("nav.filters-product"),
							$optionLinks = $optionSets.find("a");

							$optionLinks.click(function () {
								var $this = $(this);
								// don"t proceed if already selected
								if ($this.hasClass("selected")) {
									return false;
								}
								var $optionSet = $this.parents("nav.filters-product");
								$optionSet.find(".selected").removeClass("selected");
								$this.addClass("selected");
							});
						});
					})(jQuery);
				</scr' . 'ipt>
				';
				$html .= '<div class="woocommerce">';
				$html .= '<ul class="products">';
				$html .= '<div id="' . $ids . '" class="filter-list">';

					while ( $products->have_posts() ) : $products->the_post();

						$id       = get_the_ID();
						$url      = get_the_permalink();
						$title    = get_the_title();
						$img_link = wp_get_attachment_url( get_post_thumbnail_id() );
						$img      = aq_resize( $img_link, 195, 146, true, false );
						$product  = get_product( $id );

						// Filter attachment post type
						$args = array(
							'post_type'      => 'attachment',
							'numberposts'    => -1,
							'post_mime_type' => 'image',
							'post_status'    => 'public',
							'post_parent'    => $id,
							'posts_per_page' => 1
						);

						// Get product terms
						$classes = array();
						$terms = get_the_terms( $post->ID, 'product_cat' );
						foreach ( $terms as $term ) {
							$product_cat = $term->name;
							$term_link   = get_term_link( $term );
							$classes[]     = $term->slug;
						}

						$html .= '<li class="' . implode( ' ', get_post_class( $classes, $id ) ) . '">';
						$html .= '<div class="p-info">';
						$html .= '<div class="list-left">';
						$html .= '<span class="p-image">';
						$html .= '<img width="195" height="146" src="' . $img[0] . '" alt="' . $title . '" />';
						$html .= '<div class="mask">';
						$html .= apply_filters( 'woocommerce_loop_add_to_cart_link',
							sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s"><i class="dashicons dashicons-cart"></i></a>',
								esc_url( $product->add_to_cart_url() ),
								esc_attr( $product->id ),
								esc_attr( $product->get_sku() ),
								$product->is_purchasable() ? 'add_to_cart_button' : '',
								esc_attr( $product->product_type ),
								esc_html( $product->add_to_cart_text() )
							),
						$product );
						$html .= '<a class="p-detail" href="' . $url . '"><i class="dashicons dashicons-migrate"></i></a>';
						$html .= '</div>';
						$html .= '</span>';
						$html .= '<span class="info-single">';
						$html .= '<h3><a href="' . $url . '">' . $title . '</a></h3>';
						if ( $product->get_price_html() ) {
							$html .= '<span class="price">';
							$html .= '<span>' . $product->get_price_html() . '</span>';
							$html .= '</span>';
						}
						$html .= '<span class="list-desc">';
						$html .= '</span>';
						$html .= '</span>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</li>';

					endwhile;

				$html .= '</div>';
				$html .= '</ul>';
				$html .= '</div>';

			// Restore original Post Data
			wp_reset_postdata();

			return apply_filters( 'wr_ferado_product_filter', force_balance_tags( $html . $script ) );
		}
	}
}