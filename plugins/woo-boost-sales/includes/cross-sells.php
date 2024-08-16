<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Cross_Sells {
	private $settings;
	private $bundle_id;

	/**
	 * VI_WOO_BOOSTSALES_Cross_Sells constructor.
	 *
	 * @param $cross_sells
	 */
	public function __construct( $cross_sells ) {
		$this->settings  = VI_WOO_BOOSTSALES_Data::get_instance();
		$this->bundle_id = $cross_sells;
	}

	/**
	 * @param bool $layout
	 *
	 * @return bool|false|string
	 */
	public function show_html( $layout = false ) {
		/*Check product bundles*/
		if ( ! $this->bundle_id ) {
			return false;
		}
		$product = wc_get_product( $this->bundle_id );

		if ( $product->get_type() !== 'wbs_bundle' || $product->get_status() !== 'publish' ) {
			return false;
		}

		$crosssell_description = $this->settings->get_option( 'crosssell_description' );
		$display_saved_price   = $this->settings->get_option( 'display_saved_price' );
		$detect                = $this->settings->get_detect();
		$wbs_class             = array( 'wbs-crosssells' );
		if ( ! $layout ) {
			$icon_position = $this->settings->get_option( 'icon_position' );
			$init_delay    = $this->settings->get_option( 'init_delay' );
			$open          = $this->settings->get_option( 'enable_cross_sell_open' );
			$added         = filter_input( INPUT_POST, 'add-to-cart', FILTER_SANITIZE_NUMBER_INT );
			$quantity      = filter_input( INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT );
			if ( $open && is_product() && $added && $quantity ) {
				$open = 0;
			}
			$init_random = array();
			if ( $init_delay ) {
				$init_random = array_filter( explode( ',', $init_delay ) );
			}
			if ( count( $init_random ) == 2 ) {
				$init_delay = rand( $init_random[0], $init_random[1] );
			}

			if ( count( $product->bundle_data ) ) {
				$class = 'woocommerce-boost-sales';
				if ( $detect === 'mobile' ) {
					$class .= ' woocommerce-boost-sales-mobile';
				}
				ob_start();
				?>
                <div id="gift-button"
                     class="gift-button animated  wbs-icon-font <?php echo $icon_position == 0 ? 'gift_right' : 'gift_left'; ?>"
                     style="display: none;"">
                </div>
                <div id="wbs-content-cross-sells" class="<?php echo esc_attr( $class ) ?>" style="display: none"
                     data-initial_delay="<?php echo esc_attr( $init_delay ); ?>"
                     data-open="<?php echo esc_attr( $open ); ?>">
                    <div class="wbs-overlay"></div>
                    <div class="wbs-wrapper">
                        <div class="wbs-content-crossell <?php echo $icon_position == 0 ? 'gift_right' : 'gift_left'; ?>">
                            <div class="wbs-content-inner wbs-content-inner-crs">
                                <span class="wbs-close"
                                      title="<?php esc_html_e( 'Close', 'woo-boost-sales' ) ?>"><span>X</span></span>
                                <div class="wbs-added-to-cart-overlay">
                                    <div class="wbs-loading"></div>
                                    <div class="wbs-added-to-cart-overlay-content">
                                        <span class="wbs-icon-added"></span>
										<?php esc_html_e( 'Added to cart', 'woo-boost-sales' ) ?>
                                    </div>
                                </div>
                                <div class="wbs-bottom">
									<?php
									if ( $crosssell_description ) {
										?>
                                        <div class="crosssell-title"><?php echo esc_html( $crosssell_description ) ?></div>
										<?php
									}
									?>
                                    <form class="woocommerce-boost-sales-cart-form" method="post"
                                          enctype='multipart/form-data'>
                                        <div class="<?php echo esc_attr( implode( ' ', $wbs_class ) ) ?>"
                                             data-dynamic_price=""
                                             data-fixed_price="<?php echo esc_attr( $product->get_price() ) ?>"
                                             data-saved_type="<?php echo esc_attr( $display_saved_price ) ?>"
                                             data-discount_type=""
                                             data-discount_amount="">
											<?php
											wc_setup_product_data( $product->get_id() );
											$return = VI_WOO_BOOSTSALES_Frontend_Bundles::show_crossell_html();
											if ( false === $return ) {
												ob_end_clean();

												return '';
											} else {
												echo $return;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											}
											wp_reset_postdata();
											?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
				return ob_get_clean();
			} else {
				return false;
			}
		} else {
			$class = 'wbs-content-cross-sells-product-single-container';
			if ( $detect === 'mobile' ) {
				$class .= ' woocommerce-boost-sales-mobile';
			}
			ob_start();
			?>
            <div class="<?php echo esc_attr( $class ) ?>">
				<?php
				$class = '';
				if ( $this->settings->get_option( 'crosssell_display_on_slide' ) ) {
					$class = 'crosssell-display-on-slide';
				}
				?>
                <div id="wbs-content-cross-sells-product-single" class="<?php echo esc_attr( $class ) ?>">
					<?php if ( $crosssell_description ) { ?>
                        <div class="crosssell-title"><?php echo esc_html( $crosssell_description ) ?></div>
					<?php } ?>
                    <form class="woocommerce-boost-sales-cart-form" method="post" enctype='multipart/form-data'>
                        <div class="<?php echo esc_attr( implode( ' ', $wbs_class ) ) ?>"
                             data-dynamic_price=""
                             data-fixed_price="<?php echo esc_attr( $product->get_price() ) ?>"
                             data-saved_type="<?php echo esc_attr( $display_saved_price ) ?>"
                             data-discount_type=""
                             data-discount_amount="">
							<?php
							wc_setup_product_data( $product->get_id() );
							$return = VI_WOO_BOOSTSALES_Frontend_Bundles::show_crossell_html();
							if ( false === $return ) {
								ob_end_clean();

								return '';
							} else {
								echo $return;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							wp_reset_postdata();
							?>
                        </div>
                    </form>
                </div>
                <div class="woocommerce-message">
					<?php
					echo wc_add_to_cart_message( $product->get_id(), false, true );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
                </div>
            </div>
			<?php


			return ob_get_clean();
		}
	}
}