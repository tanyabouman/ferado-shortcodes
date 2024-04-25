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
	if ( ! function_exists( 'wr_ferado_product_slider' ) ) {
		function wr_ferado_product_slider( $atts, $content = null ) {
			$limit = $orderby = $order = $single = $class = $effect_output = '';
			extract( shortcode_atts(
				array(
				'limit'      => '10',
				'orderby'    => 'date',
				'order'      => 'desc',
				'pagination' => 'false',
				'navigation' => 'false',
				'item'       => 6,
				'single'     => 'false',
				'cat'        => '',
				'title'      => '',
				'full'       => '',
				'effect'     => '',
			), $atts ) );

			global $product, $woocommerce_loop, $post;

			// The meta query for the page
			$meta_query = WC()->query->get_meta_query();

			// Generate random id
			$length = 10;
			$id     = substr( str_shuffle( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, $length );

			// Filter product post type
			$args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'orderby'             => $orderby,
				'order'               => $order,
				'meta_query'          => $meta_query,
				'posts_per_page'      => $limit,
				'cat'                 => $cat,
			);

			if ( $effect ) {
				$effect_output = '
					beforeMove: updateCarousel,
					afterInit: function() {
						setTimeout(updateCarousel, 100)
					}';
			}

			ob_start();

			$products  = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

			echo '
				<scr' . 'ipt>
					(function($) {
						"use strict";
						$(document).ready(function() {
							var owl = $("#' . $id . '");
							owl.owlCarousel({
								navigation: ' . $navigation . ',
								navigationText: [
									"<i class=\"dashicons dashicons-arrow-left-alt2\"></i>",
									"<i class=\"dashicons dashicons-arrow-right-alt2\"></i>",
								],
								pagination: ' . $pagination . ',
								singleItem: ' . $single . ',
								items: ' . $item . ',
								itemsMobile : [540,1],
								itemsTablet : [768,2],
								itemsDesktopSmall : [1366,4],
								itemsDesktop: [1920,6],
								slideSpeed : 600,
								' . $effect_output . '
							});
							function updateCarousel() {
								var start = data.currentItem;
								var count = data.options.items;	
								
								if ( window.innerWidth < 1921 ) {
									var endStart = start+count-1;
									var first2 = owl.find(".owl-item").slice(start,start+1);
									var last2 = owl.find(".owl-item").slice(endStart, endStart+1);
								} else {
									var endStart = start+count-2;
									var first2 = owl.find(".owl-item").slice(start,start+2);
									var last2 = owl.find(".owl-item").slice(endStart, endStart+2);
								}
								
								owl.find(".owl-item").removeClass("blur");
								first2.addClass("blur");
								last2.addClass("blur");

							}
							var data = owl.data("owlCarousel");
						});
					})(jQuery);
				</scr' . 'ipt>';

			if ( 'true' == $single ) :
				$class .= 'single-gal';
			endif;

			if ( 'true' != $single && $title ) :
				echo '<div class="h-slider"><h4 class="slider-title">' . $title . '</h4></div>';
			endif;
			// Begin the loop
			if ( $products->have_posts() ) :
				if ( function_exists( 'woocommerce_product_loop_start' ) ) {
					woocommerce_product_loop_start();
				}
				?>
				<div id="<?php echo $id ?>" class="owl-carousel <?php echo $class; ?>">

					<?php
						while ( $products->have_posts() ) : $products->the_post();
						global $product;
						$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
						$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
							?>
							<li <?php post_class(); ?>>

								<div class="p-inner">

									<div class="p-grid">
										<span class="p-image">
											<?php
												if ( class_exists( 'YITH_WCWL_UI' ) )  {
													echo wr_ferado_wishlist_button();
												}
												do_action( 'woocommerce_before_shop_loop_item_title' );
											?>
											<div class="p-mask">
												<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
												<?php woocommerce_get_template( 'loop/rating.php' ); ?>
												<span class="p-desc">
													<?php
														if ( ! apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ) {
															$content = $post->post_content;
															echo  wp_trim_words( wpautop( $content ), 15 );
														} else {
															echo wp_trim_words( apply_filters( 'woocommerce_short_description', $post->post_excerpt ), 15 );
														}
													?>
												</span>
												<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
													<span class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span>.</span>
												<?php endif; ?>
													<?php echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'woocommerce' ) . ' ', '.</span>' ); ?>
												<?php echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'woocommerce' ) . ' ', '.</span>' ); ?>
											</div>
										</span>
										<span class="p-info">
											<div class="p-cart">
												<?php woocommerce_get_template( 'loop/add-to-cart.php' ); ?>
											</div>
											<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
										</span>
									</div>
								</div>
							</li>
							<?php
						endwhile;
					?>

				</div>

				<?php
				if ( function_exists( 'woocommerce_product_loop_end' ) ) {
					woocommerce_product_loop_end();
				}
			endif;

			// Restore original Post Data
			wp_reset_postdata();

			return '<div class="woocommerce product-slider">' . ob_get_clean() . '</div>';
		}
	}
}