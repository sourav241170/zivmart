<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Upsells {
	private $settings;
	private $quantity;
	private $product;
	private $upsells;
	private $variation_id;
	private $cart_item_key;
	protected $language;

	/**
	 * VI_WOO_BOOSTSALES_Upsells constructor.
	 * Init setting
	 */
	public function __construct( $product_id, $quantity, $upsells, $variation_id = false, $cart_item_key = '' ) {
		$this->product       = $product_id;
		$this->quantity      = $quantity;
		$this->upsells       = $upsells;
		$this->variation_id  = $variation_id;
		$this->cart_item_key = $cart_item_key;
		$this->language      = '';
		$this->settings      = VI_WOO_BOOSTSALES_Data::get_instance();
	}

	/**
	 * Use this function to not get affected by filter of function $product->get_image()
	 *
	 * @param $product WC_Product
	 * @param string $size
	 * @param array $attr
	 * @param bool $placeholder
	 *
	 * @return string
	 */
	public static function get_product_image( $product, $size = 'woocommerce_thumbnail', $attr = array(), $placeholder = true ) {
		$image = '';
		if ( $product->get_image_id() ) {
			$image = wp_get_attachment_image( $product->get_image_id(), $size, false, $attr );
		} elseif ( $product->get_parent_id() ) {
			$parent_product = wc_get_product( $product->get_parent_id() );
			if ( $parent_product ) {
				$image = self::get_product_image( $parent_product, $size, $attr, $placeholder );
			}
		}

		if ( ! $image && $placeholder ) {
			$image = wc_placeholder_img( $size, $attr );
		}

		return $image;
	}

	/**
	 * @return false|string
	 */
	public function show_html() {
		$cart = WC()->cart;

		if ( ( ! is_array( $this->upsells ) || ! count( $this->upsells ) ) ) {
			return '';
		}
		$cart_url       = wc_get_cart_url();
		$checkout_url   = wc_get_checkout_url();
		$message_bought = $this->settings->get_option( 'message_bought' );
		$item_per_row   = $this->settings->get_option( 'item_per_row' );

		$main_product = wc_get_product( $this->product );
//		$product_image    = woocommerce_get_product_thumbnail();
		$product_image    = self::get_product_image( $main_product );
		$product_title    = $main_product->get_name();
		$main_product_url = $main_product->get_permalink();
		$added_to_cart    = VI_WOO_BOOSTSALES_Frontend_Upsells::$added_to_cart;
		if ( isset( $added_to_cart[ $this->product ] ) && $added_to_cart[ $this->product ]['quantity'] && $added_to_cart[ $this->product ]['price'] !== false ) {
			$this->quantity = $added_to_cart[ $this->product ]['quantity'];
			$total_product  = wc_price( $added_to_cart[ $this->product ]['price'] * $added_to_cart[ $this->product ]['quantity'] );
		} else {
			$upsell_price = wc_get_price_to_display( $main_product );
			if ( ! is_numeric( $upsell_price ) ) {
				$upsell_price = floatval( $upsell_price );
			}
			$total_product = wc_price( $upsell_price * $this->quantity );

			if ( $this->variation_id ) {
				$variation = wc_get_product( $this->variation_id );
				if ( $variation ) {
					$variation_image = self::get_product_image( $variation );
					if ( $variation_image ) {
						$product_image = $variation_image;
					}
					$product_title    = $variation->get_name();
					$main_product_url = $variation->get_permalink();
					$upsell_price     = wc_get_price_to_display( $variation );
					if ( ! is_numeric( $upsell_price ) ) {
						$upsell_price = floatval( $upsell_price );
					}
					$total_product = wc_price( $upsell_price * $this->quantity );
				}
			}
		}
		if ( $this->cart_item_key && class_exists( 'WC_PB_Display' ) ) {
			$pb            = WC_PB_Display::instance();
			$total_product = $pb->cart_item_price( $total_product, $cart->get_cart_item( $this->cart_item_key ), $this->cart_item_key );
		}
		$upsells_count = 0;
		$get_detect    = $this->settings->get_detect();
		ob_start();
		$atc_style = 'hover';
		?>
        <div class="vi-flexslider" id="flexslider-up-sell"
             data-rtl="<?php echo esc_attr( is_rtl() ? 1 : 0 ) ?>"
             data-item-per-row="<?php echo esc_attr( $item_per_row ); ?>"
             data-item-per-row-mobile="<?php echo esc_attr( $this->settings->get_option( 'item_per_row_mobile' ) ); ?>">
            <div class="wbs-upsells wbs-vi-slides <?php echo esc_attr( "wbs-upsells-atc-style-{$atc_style}" ) ?>">
				<?php
				foreach ( $this->upsells as $upsell_id ) {
					$upsell_product = wc_get_product( $upsell_id );
					if ( $upsell_product ) {
						if ( ! $upsell_product->is_in_stock() && $this->settings->get_option( 'hide_out_stock' ) ) {
							continue;
						}
						$upsells_count ++;
						$product_url = $upsell_product->get_permalink();
						?>
                        <div class="vi-wbs-chosen wbs-variation wbs-product">
                            <div class="wbs-upsells-add-items"></div>
                            <div class="product-top">
								<?php
								if ( $product_url && $atc_style !== 'hover' ) {
									?>
                                    <a href="<?php echo esc_url( $product_url ) ?>" target="_blank"
                                       class="wbs-upsells-item-url">
										<?php
										do_action( 'woocommerce_boost_sales_before_shop_loop_item_title', $upsell_product );
										?>
                                    </a>
									<?php
								} else {
									do_action( 'woocommerce_boost_sales_before_shop_loop_item_title', $upsell_product );
								}
								?>
                            </div>
                            <div class="product-desc">
								<?php
								if ( $product_url && $get_detect !== 'mobile' ) {
									?>
                                    <a href="<?php echo esc_url( $product_url ) ?>" target="_blank"
                                       class="wbs-upsells-item-url">
										<?php
										do_action( 'woocommerce_boost_sales_shop_loop_item_title', $upsell_product );
										?>
                                    </a>
									<?php
								} else {
									do_action( 'woocommerce_boost_sales_shop_loop_item_title', $upsell_product );
								}
								do_action( 'woocommerce_boost_sales_after_shop_loop_item_title', $upsell_product );
								?>
                            </div>
							<?php
							if ( $atc_style !== 'hide' ) {
								?>
                                <div class="product-controls">
                                    <div class="wbs-cart">
										<?php do_action( 'woocommerce_boost_sales_single_product_summary', $upsell_product ) ?>
                                    </div>
                                </div>
								<?php
							}
							do_action( 'woocommerce_boost_sales_upsells_slider_item_end', $upsell_product );
							?>
                        </div>
						<?php
					}
				}
				?>
            </div>
        </div>
		<?php

		$upsells_list = ob_get_clean();
		if ( $upsells_count == 0 ) {
			return '';
		}
		ob_start();
		?>
        <div class="wbs-overlay"></div>
		<?php do_action( 'wbs_before_upsells' ) ?>
        <div class="wbs-wrapper wbs-archive-upsells wbs-upsell-template-1 wbs-upsell-items-count-<?php echo esc_attr( $upsells_count ) ?>"
             style="opacity: 0">
            <div class="wbs-content">
                <div class="wbs-content-inner">
                    <span class="wbs-close"
                          title="<?php esc_html_e( 'Close', 'woo-boost-sales' ) ?>"><span>X</span></span>

                    <div class="wbs-breadcrum">
                        <p class="wbs-notify_added wbs-title_style1">
                            <span class="wbs-icon-added"></span> <?php printf( _n( '<span class="wbs-notify_added-quantity">%s</span>  new item has been added to your cart', '<span class="wbs-notify_added-quantity">%s</span>  new items have been added to your cart', $this->quantity, 'woo-boost-sales' ), $this->quantity ); ?>
                        </p>
                        <div class="wbs-header-right">
                            <a href="<?php echo esc_url( $cart_url ) ?>"
                               class="wbs-button-view"><?php esc_html_e( 'View Cart', 'woo-boost-sales' ) ?></a>
                            <a href="#"
                               class="wbs-button-continue <?php esc_attr_e( 'wbs-button-continue-stay' ) ?>"><?php esc_html_e( 'Continue Shopping', 'woo-boost-sales' ) ?></a>
                            <a href="<?php echo esc_url( $checkout_url ) ?>"
                               class="wbs-button-check">
								<?php
								echo apply_filters( 'woocommerce_boost_sales_upsells_checkout_text', esc_html__( 'Checkout', 'woo-boost-sales' ) );
								?>
                            </a>
                        </div>
                        <div class="wbs-product">
                            <div class="wbs-p-image">
								<?php
								if ( $main_product_url ) {
									?>
                                    <a href="<?php echo esc_url( $main_product_url ) ?>" class="wbs-p-url"
                                       target="_self"><?php echo $product_image;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
									<?php
								} else {
									echo $product_image;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
                            </div>
                            <div class="wbs-p-title">
								<?php
								if ( $main_product_url ) {
									?>
                                    <a href="<?php echo esc_url( $main_product_url ) ?>" class="wbs-p-url"
                                       target="_self"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $product_title ); ?></a>
									<?php
								} else {
									echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $product_title );
								}
								?>
                            </div>
                            <div class="wbs-p-price">
                                <div class="wbs-p-quantity">
                                    <span class="wbs-p-quantity-text"><?php esc_html_e( 'Quantity:', 'woo-boost-sales' ); ?></span>
                                    <span class="wbs-p-quantity-number"
                                          style="float: none;"><?php echo esc_html( $this->quantity ); ?></span>
                                </div>
                                <div class="wbs-price-total">
                                    <div class="wbs-total-price"><?php esc_html_e( 'Total', 'woo-boost-sales' ) ?>
                                        <span class="wbs-money"
                                              style="float: none;"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $total_product ); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php
					if ( $upsells_count > 0 ) {
						?>
                        <div class="wbs-bottom">
							<?php
							if ( $message_bought ) {
								?>
                                <h3 class="upsell-title"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( str_replace( '{name_product}', $product_title, strip_tags( $message_bought ) ) ); ?></h3>
                                <hr/>
								<?php
							}
							/*upsells list here*/
							echo $upsells_list;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							do_action( 'woocommerce_boost_sales_after_upsells_list', $main_product, $this->upsells );
							?>
                        </div>
						<?php
					}
					?>
                </div>
            </div>
        </div>
		<?php
		do_action( 'wbs_after_upsells' );

		return ob_get_clean();
	}
}