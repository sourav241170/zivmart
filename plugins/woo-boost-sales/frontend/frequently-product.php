<?php

/**
 * Class VI_WOO_BOOSTSALES_Frontend_Frequently_Product
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Frontend_Frequently_Product {
	protected static $settings;
	protected static $is_product;
	public static $is_cross_sells;
	protected static $language;
	protected static $atc_error;

	public function __construct() {
		self::$settings       = VI_WOO_BOOSTSALES_Data::get_instance();
		self::$is_product     = false;
		self::$is_cross_sells = false;
		self::$language       = '';
		add_action( 'init', array( $this, 'shortcode_init' ) );
		if ( self::$settings->enable() ) {
			add_action( 'wp_ajax_vi_wbs_frequently_product_add_to_cart', array(
				$this,
				'frequently_product_add_to_cart'
			) );
			add_action( 'wp_ajax_nopriv_vi_wbs_frequently_product_add_to_cart', array(
				$this,
				'frequently_product_add_to_cart'
			) );
			add_action( 'woocommerce_boost_sales_frequently_product_select', array(
				$this,
				'frequently_product_select'
			), 10, 3 );
			add_filter( 'vi_wbs_frequently_product_item_displayed_name', array(
				$this,
				'frequently_product_item_name'
			), 10, 4 );
			/*single product*/
			add_action( 'woocommerce_after_add_to_cart_form', array(
				$this,
				'woocommerce_after_add_to_cart_form'
			) );
		}
	}

	public function frequently_product_select( $product, $item_variation_attributes, $select_type ) {
		add_filter( 'woocommerce_available_variation', array( $this, 'woocommerce_available_variation' ), 10, 3 );
		add_filter( 'option_woocommerce_hide_out_of_stock_items', array(
			$this,
			'woocommerce_hide_out_of_stock_items'
		) );
		wbs_get_template(
			'single-product/add-to-cart/frequently-product.php', array(
			'available_variations' => $product->get_available_variations(),
			'attributes'           => $product->get_variation_attributes(),
			'selected_attributes'  => $item_variation_attributes,
			'product'              => $product,
			'select_type'          => $select_type,
		), '', VI_WOO_BOOSTSALES_TEMPLATES
		);
		remove_filter( 'woocommerce_available_variation', array( $this, 'woocommerce_available_variation' ) );
		remove_filter( 'option_woocommerce_hide_out_of_stock_items', array(
			$this,
			'woocommerce_hide_out_of_stock_items'
		) );
	}

	public function woocommerce_hide_out_of_stock_items( $value ) {
		return 'yes';
	}

	public function woocommerce_available_variation( $available_variation, $product, $variation ) {
		if ( ! $available_variation['price_html'] ) {
			$available_variation['price_html'] = $variation->get_price_html();
		}

		return $available_variation;
	}

	public function shortcode_init() {
		add_shortcode( 'wbs_frequently_product', array( $this, 'frequently_product_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array(
			$this,
			'wp_enqueue_scripts'
		) );
	}

	public function frequently_product_shortcode( $atts ) {
		global $wbs_frequently_product;
		if ( $wbs_frequently_product === null ) {
			$wbs_frequently_product = 1;
		} else {
			$wbs_frequently_product ++;
		}
		$args                    = shortcode_atts( array(
			'product_id'     => '',
			'product_ids'    => '',
			'source'         => 'up_sells',
			'style'          => 'vertical',
			'title_line'     => 1,
			'show_attribute' => self::$settings->get_option( 'frequently_product_show_attribute' ),
			'hide_if_added'  => '',
			'select_type'    => self::$settings->get_option( 'frequently_product_select_type' ),
			'ajax_load'      => '',
			'after_atc'      => 'none',
			'message'        => esc_html__( 'Frequently bought together:', 'woo-boost-sales' ),
			'show_rating'    => '',
			'attributes'     => '',
		), $atts );
		$frequently_product_html = '';
		$product_id              = '';
		$check_cart              = true;
		if ( $args['product_id'] ) {
			$product_id = $args['product_id'];
			if ( wp_doing_ajax() ) {
				$check_cart = false;
			}
		} else {
			global $product;
			if ( $product ) {
				$product_id         = $product->get_id();
				$args['product_id'] = $product_id;
			}
		}
		if ( ! wp_doing_ajax() ) {
			if ( self::$settings->get_detect() === 'mobile' ) {
				$args['style'] = 'vertical';
			}
		}
		if ( $product_id && ( ! boolval( $args['hide_if_added'] ) || ! $check_cart || ! self::is_product_in_cart( $product_id ) ) ) {
			if ( ! wp_doing_ajax() ) {
				$css = '';
				if ( ! wp_script_is( 'woo-boost-sales-frequently-product' ) ) {
					wp_enqueue_script( 'woo-boost-sales-frequently-product' );
					wp_enqueue_style( 'woo-boost-sales-frequently-product' );
					$image_size = self::$settings->get_option( 'frequently_product_image_size' );
					$css        .= '.vi-wbs-frequently-products-container.vi-wbs-frequently-products-vertical .vi-wbs-frequently-products .vi-wbs-frequently-product-item .vi-wbs-frequently-product-item-detail .vi-wbs-frequently-product-item-image img{width:' . $image_size . 'px;}';
//					$css .= '@media screen and (min-width: 600px) {.vi-wbs-frequently-products-container.vi-wbs-frequently-products-vertical .vi-wbs-frequently-products .vi-wbs-frequently-product-item .vi-wbs-frequently-product-item-attributes-select-modal{left:' . ($image_size+14) . 'px;}}';
				}
				$css .= "#vi-wbs-frequently-products-container-{$wbs_frequently_product} .vi-wbs-frequently-products .vi-wbs-frequently-product-item .vi-wbs-frequently-product-item-detail .vi-wbs-frequently-product-item-name{-webkit-line-clamp:{$args['title_line']};}";
				wp_add_inline_style( 'woo-boost-sales-frequently-product', $css );
			}
			ob_start();
			self::frequently_item_list_html( $product_id, $args );
			$frequently_product_html = ob_get_clean();
		}

		return $frequently_product_html;
	}

	public function wp_enqueue_scripts() {
		if ( WP_DEBUG ) {
			$css_ext = '.css';
			$js_ext  = '.js';
		} else {
			$css_ext = '.min.css';
			$js_ext  = '.min.js';
		}
		self::$is_product = is_product();
		wp_register_script( 'woo-boost-sales-frequently-product', VI_WOO_BOOSTSALES_JS . 'frequently-product' . $js_ext, array(
			'jquery',
			'jquery-vi_flexslider'
		), VI_WOO_BOOSTSALES_VERSION, true );
		wp_localize_script( 'woo-boost-sales-frequently-product', 'wbs_frequently_product_params', array(
				'url'                                 => admin_url( 'admin-ajax.php' ),
				'frequently_product_add_to_cart_text' => self::$settings->get_option( 'frequently_product_add_to_cart_text', self::$language ),
				'decimals'                            => wc_get_price_decimals(),
				'decimal_separator'                   => wc_get_price_decimal_separator(),
				'thousand_separator'                  => wc_get_price_thousand_separator(),
				'locale'                              => str_replace( '_', '-', get_locale() ),
				'frequently_product_source'           => 'up_sells',
				'frequently_product_max_title_line'   => 1,
				'frequently_product_show_attribute'   => self::$settings->get_option( 'frequently_product_show_attribute' ),
				'frequently_product_hide_if_added'    => '',
				'frequently_product_select_type'      => self::$settings->get_option( 'frequently_product_select_type' ),
				'is_cart'                             => is_cart(),
				'is_checkout'                         => is_checkout(),
				'is_product'                          => self::$is_product,
				'language'                            => self::$language,
			)
		);

		wp_register_style( 'woo-boost-sales-frequently-product', VI_WOO_BOOSTSALES_CSS . 'frequently-product' . $css_ext, array(), VI_WOO_BOOSTSALES_VERSION );
	}


	public function frequently_product_item_name( $item_name, $item_id, $product_id, $is_product ) {
		if ( $item_id === $product_id && $is_product ) {
			$currently_watching_text = self::$settings->get_option( 'frequently_product_currently_watching_text', self::$language );
			if ( $currently_watching_text ) {
				$item_name = '<strong>' . esc_html( strip_tags( $currently_watching_text ) ) . '</strong>' . $item_name;
			}
		}

		return $item_name;
	}

	public function woocommerce_add_error( $error ) {
		if ( $error ) {
			self::$atc_error = $error;
		}

		return '';
	}

	/**Add multiple products to cart
	 * @throws Exception
	 */
	public function frequently_product_add_to_cart() {
		$data           = isset( $_POST['data'] ) ? $_POST['data'] : array();// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$response       = array(
			'status'    => 'error',
			'message'   => '',
			'after_atc' => '',
		);
		$added_products = array();
		if ( count( $data ) ) {
			add_filter( 'woocommerce_add_error', array( $this, 'woocommerce_add_error' ) );
			foreach ( $data as $key => $value ) {
				if ( ! empty( $value['product_id'] ) ) {
					$product_id   = empty( $value['variation_id'] ) ? sanitize_text_field( $value['product_id'] ) : sanitize_text_field( $value['variation_id'] );
					$variation_id = empty( $value['variation_id'] ) ? 0 : sanitize_text_field( $value['variation_id'] );
					$variation    = isset( $value['variation_attributes'] ) ? $value['variation_attributes'] : array();// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					if ( WC()->cart->add_to_cart( $product_id, 1, $variation_id, $variation ) ) {
						$added_products[] = $product_id;
					}
				}
			}
		}
		$added_products_count = count( $added_products );
		if ( $added_products_count ) {
			$response['status']  = 'success';
			$response['message'] = sprintf( _n( 'Added %s product to cart', 'Added %s products to cart', $added_products_count, 'woo-boost-sales' ), $added_products_count );
		} else {
			if ( self::$atc_error !== null && count( $data ) === 1 ) {
				$response['message'] = self::$atc_error;
			} else {
				$response['message'] = esc_html__( 'Cannot add product(s) to cart', 'woo-boost-sales' );
			}
		}
		wp_send_json( $response );
	}

	public function woocommerce_after_add_to_cart_form() {
		self::show_frequently_product( 'after_cart' );
	}

	public static function show_frequently_product( $position ) {
		if ( self::$settings->get_option( 'frequently_product' ) && is_product() && self::$settings->get_option( 'frequently_product_position' ) === $position ) {
			echo do_shortcode( '[wbs_frequently_product]' );
		}
	}

	public static function frequently_item_list_html( $product_id, $args ) {
		global $wbs_frequently_product;
		$selected_attributes = array();
		if ( $args['attributes'] ) {
			$selected_attributes = json_decode( $args['attributes'], true );
		}
		$frequently_product_message = $args['message'];
		$class                      = array(
			'vi-wbs-frequently-products-container',
			"vi-wbs-frequently-products-{$args['show_attribute']}",
			"vi-wbs-frequently-products-type-{$args['select_type']}",
			"vi-wbs-frequently-products-{$args['style']}"
		);
		$discounted_price           = 0;
		$discount_type              = '';
		$discount_amount            = 0;
		$bundle_data                = array();
		$frequently_products        = VI_WOO_BOOSTSALES_Frontend_Upsells::get_upsells_ids( $product_id );
		$frequently_products        = array_diff( $frequently_products, array( $product_id ) );
		if ( count( $frequently_products ) ) {
			$total_price      = 0;
			$total_items      = 0;
			$added_attributes = self::get_added_attributes();
			if ( count( $selected_attributes ) ) {
				self::get_added_attribute( $selected_attributes, $added_attributes );
			}
			$frequently_items_list_html = '';
			foreach ( $frequently_products as $frequently_product ) {
				$frequently_items_list_html .= self::frequently_item_html( $frequently_product, $added_attributes, $product_id, $total_price, $total_items, $selected_attributes, $args, self::get_bundle_item_quantity( $bundle_data, $frequently_product ) );
			}
			if ( $frequently_items_list_html ) {
				?>
                <div id="<?php echo esc_attr( "vi-wbs-frequently-products-container-{$wbs_frequently_product}" ) ?>"
                     class="<?php echo esc_attr( implode( ' ', $class ) ) ?>"
                     data-wbs_fp_shortcode="<?php echo esc_attr( json_encode( $args ) ) ?>">
                    <div class="vi-wbs-frequently-products">
						<?php
						if ( $frequently_product_message ) {
							?>
                            <div class="vi-wbs-frequently-products-message"><?php echo esc_html( strip_tags( str_replace( '{product_title}', get_the_title(), $frequently_product_message ) ) ) ?></div>
							<?php
						}
						?>
                        <div class="vi-wbs-frequently-products-list">
							<?php
							echo self::frequently_item_html( $product_id, $added_attributes, $product_id, $total_price, $total_items, $selected_attributes, $args, self::get_bundle_item_quantity( $bundle_data, $product_id ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo $frequently_items_list_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
                        </div>
						<?php
						$discounted_amount = 0;
						?>
                        <div class="vi-wbs-frequently-add-to-cart-message"></div>
                        <div class="vi-wbs-frequently-add-to-cart">
                            <button type="submit"
                                    class="button alt vi-wbs-frequently-add-to-cart-button"><?php echo esc_html( str_replace( '{number_of_items}', $total_items, self::$settings->get_option( 'frequently_product_add_to_cart_text', self::$language ) ) ) ?></button>
                            <span class="vi-wbs-frequently-sum-price"
                                  data-saved_type=""
                                  data-dynamic_price=""
                                  data-discount_type="<?php echo esc_attr( $discount_type ) ?>"
                                  data-discount_amount="<?php echo esc_attr( VI_WOO_BOOSTSALES_Data::convert_price_to_float( $discount_amount ) ) ?>"
                                  data-discounted_amount="<?php echo esc_attr( VI_WOO_BOOSTSALES_Data::convert_price_to_float( $discounted_amount ) ) ?>"
                                  data-discounted_price="<?php echo esc_attr( VI_WOO_BOOSTSALES_Data::convert_price_to_float( $discounted_price ) ) ?>"
                                  data-total_price="<?php echo esc_attr( VI_WOO_BOOSTSALES_Data::convert_price_to_float( $total_price ) ) ?>"><span
                                        class="vi-wbs-frequently-sum-price-label"><?php esc_html_e( 'Total', 'woo-boost-sales' ) ?>: </span><span
                                        class="vi-wbs-frequently-sum-price-value"
                                        style="<?php if ( $discounted_amount > 0 ) {
											echo esc_attr( 'display:none;' );
										} ?>"><?php echo wc_price( $total_price );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                                    <?php
                                    if ( $discounted_amount > 0 ) {
	                                    ?>
                                        <span class="vi-wbs-frequently-sum-price-show-discount vi-wbs-frequently-sum-price-value-with-discount"><del
                                                    class="vi-wbs-frequently-sum-price-show-discount-origin"><?php echo wc_price( $total_price );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></del>&nbsp;<ins
                                                    class="vi-wbs-frequently-sum-price-show-discount-current"><?php echo wc_price( $discounted_price );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ins></span>
	                                    <?php
                                    }
                                    ?>
                                </span>
                        </div>
                    </div>
                    <div class="vi-wbs-frequently-products-overlay"></div>
                </div>
				<?php
			}
		}
	}

	protected static function get_bundle_item_quantity( $bundle_data, $product_id ) {
		$quantity = '';
		if ( count( $bundle_data ) ) {
			foreach ( $bundle_data as $bundle_item ) {
				if ( $bundle_item['product_id'] == $product_id ) {
					$quantity = $bundle_item['bp_quantity'];
					break;
				}
			}
		}

		return $quantity;
	}

	/**
	 * @param $item_id
	 * @param $added_attributes
	 * @param $product_id
	 * @param $total_price
	 * @param $total_items
	 * @param $selected_attributes
	 * @param $args
	 * @param string $quantity
	 *
	 * @return string
	 */
	protected static function frequently_item_html( $item_id, $added_attributes, $product_id, &$total_price, &$total_items, $selected_attributes, $args, $quantity = '' ) {
		$product              = wc_get_product( $item_id );
		$frequently_item_html = '';
		if ( $product ) {
			$item_price                = false;
			$item_price_html           = '';
			$item_product_id           = $item_id;
			$item_variation_id         = '';
			$item_image                = '';
			$item_name                 = $product->get_title();
			$item_url                  = '';
			$item_variation_attributes = array();
			$product_type              = $product->get_type();
			$style                     = $args['style'];
			$select_type               = $args['select_type'];

			if ( $product_type === 'variable' ) {
				$variation_attributes = $product->get_variation_attributes();
				/*First search for variation by selected attributes of currently watching product*/
				if ( count( $selected_attributes ) && count( $selected_attributes ) === count( $variation_attributes ) ) {
					$variation_id = VI_WOO_BOOSTSALES_Data::find_matching_product_variation_id( $item_product_id, $selected_attributes );
					if ( $variation_id ) {
						$variation                 = wc_get_product( $variation_id );
						$item_price                = wc_get_price_to_display( $variation, array( 'qty' => 1 ) );
						$item_price_html           = $variation->get_price_html();
						$item_variation_id         = $variation->get_id();
						$item_image                = $variation->get_image();
						$item_url                  = $variation->get_permalink();
						$item_variation_attributes = $variation->get_attributes();
					}
				}
				if ( $item_price === false ) {
					/*If no variation found, search by default attributes*/
					$added_attributes_names = array_intersect_key( $added_attributes, $variation_attributes );

					$maybe_select        = array();
					$selected_attributes = array();
					foreach ( $added_attributes_names as $key => $value ) {
						$select = array_intersect( $value, $variation_attributes[ $key ] );
						if ( count( $select ) ) {
							$maybe_select[ $key ]                    = array_splice( $select, 0, 1 )[0];
							$selected_attributes["attribute_{$key}"] = $maybe_select[ $key ];
						}
					}
					if ( count( $selected_attributes ) && count( $selected_attributes ) === count( $variation_attributes ) ) {
						$variation_id = VI_WOO_BOOSTSALES_Data::find_matching_product_variation_id( $item_product_id, $selected_attributes );
						if ( $variation_id ) {
							$variation                 = wc_get_product( $variation_id );
							$item_price                = wc_get_price_to_display( $variation, array( 'qty' => 1 ) );
							$item_price_html           = $variation->get_price_html();
							$item_variation_id         = $variation->get_id();
							$item_image                = $variation->get_image();
							$item_url                  = $variation->get_permalink();
							$item_variation_attributes = $variation->get_attributes();
						}
					}
					if ( $item_price === false ) {
						$variation_ids = $product->get_children();
						if ( ! count( $variation_ids ) ) {
							return $frequently_item_html;
						}
						$default_attributes = $product->get_default_attributes();
						if ( $default_attributes ) {
							if ( is_callable( '_prime_post_caches' ) ) {
								_prime_post_caches( $variation_ids );
							}
							foreach ( $variation_ids as $variation_id ) {
								$variation = wc_get_product( $variation_id );
								// Hide out of stock variations if 'Hide out of stock items from the catalog' is checked.
								if ( ! $variation || ! $variation->exists() || ! $variation->is_in_stock() ) {
									continue;
								}

								// Filter 'woocommerce_hide_invisible_variations' to optionally hide invisible variations (disabled variations and variations with empty price).
								if ( apply_filters( 'woocommerce_hide_invisible_variations', true, $item_product_id, $variation ) && ! $variation->variation_is_visible() ) {
									continue;
								}
								$item_price        = wc_get_price_to_display( $variation, array( 'qty' => 1 ) );
								$item_price_html   = $variation->get_price_html();
								$item_variation_id = $variation->get_id();
								$item_image        = $variation->get_image();
//						$item_name                 = $variation->get_name();
								$item_url                  = $variation->get_permalink();
								$item_variation_attributes = $variation->get_attributes();
								if ( $variation->get_attributes() === $default_attributes ) {
									break;
								}
							}
						}
						/*If still no variation found, search by added attributes*/
						if ( $item_price === false ) {
							if ( is_callable( '_prime_post_caches' ) ) {
								_prime_post_caches( $variation_ids );
							}
							foreach ( $variation_ids as $variation_id ) {
								$variation = wc_get_product( $variation_id );
								// Hide out of stock variations if 'Hide out of stock items from the catalog' is checked.
								if ( ! $variation || ! $variation->exists() || ! $variation->is_in_stock() ) {
									continue;
								}

								// Filter 'woocommerce_hide_invisible_variations' to optionally hide invisible variations (disabled variations and variations with empty price).
								if ( apply_filters( 'woocommerce_hide_invisible_variations', true, $item_product_id, $variation ) && ! $variation->variation_is_visible() ) {
									continue;
								}
								$item_price        = wc_get_price_to_display( $variation, array( 'qty' => 1 ) );
								$item_price_html   = $variation->get_price_html();
								$item_variation_id = $variation->get_id();
								$item_image        = $variation->get_image();
//						$item_name                 = $variation->get_name();
								$item_url                  = $variation->get_permalink();
								$item_variation_attributes = $variation->get_attributes();
								$found                     = 0;
								foreach ( $variation->get_attributes() as $v_attribute_k => $v_attribute_v ) {
									if ( isset( $default_attributes[ $v_attribute_k ] ) && in_array( $v_attribute_v, array(
											'',
											$default_attributes[ $v_attribute_k ]
										), true ) ) {
										$found ++;
									}
								}
								if ( $found === count( $default_attributes ) ) {
									break;
								}
							}
						}
					}
				}
				/*If still no variation found, select the first variation*/
				if ( ! count( $item_variation_attributes ) ) {
					foreach ( $variation_attributes as $variation_attribute_k => $variation_attribute ) {
						$item_variation_attributes["attribute_{$variation_attribute_k}"] = $variation_attribute[0];
					}
				} else {
					foreach ( $item_variation_attributes as $v_attribute_k => $v_attribute_v ) {
						$decode_attr = urldecode( html_entity_decode( $v_attribute_k ) );
						if ( $v_attribute_v === '' ) {
							if ( isset( $variation_attributes[ $v_attribute_k ] ) ) {
								$item_variation_attributes[ $v_attribute_k ] = $variation_attributes[ $v_attribute_k ][0];
							} else {
								if ( isset( $variation_attributes[ $decode_attr ] ) ) {
									$item_variation_attributes[ $v_attribute_k ] = $variation_attributes[ $decode_attr ][0];
								} else {
									$decode_attr = function_exists( 'mb_strtolower' ) ? mb_strtolower( $decode_attr ) : strtolower( $decode_attr );
									$found       = false;
									foreach ( $variation_attributes as $variation_attribute_k_1 => $variation_attribute_v_1 ) {
										$variation_attribute_k_1_l = function_exists( 'mb_strtolower' ) ? mb_strtolower( $variation_attribute_k_1 ) : strtolower( $variation_attribute_k_1 );
										if ( $variation_attribute_k_1_l === $decode_attr ) {
											$item_variation_attributes[ $v_attribute_k ] = $variation_attributes[ $variation_attribute_k_1 ][0];
											$found                                       = true;
											break;
										}
									}
									if ( $found === false ) {
										$item_variation_attributes[ $v_attribute_k ] = '';
									}
								}
							}
						}
						$item_variation_attributes[ urldecode( html_entity_decode( $v_attribute_k ) ) ] = $item_variation_attributes[ $v_attribute_k ];
					}
				}
			} elseif ( in_array( $product_type, array(
					'variation',
					'simple',
					'subscription',
					'member',
					'woosb',
				) ) && $product->is_in_stock() ) {
				if ( $product_type === 'variation' ) {
					$item_variation_attributes = $product->get_attributes();
					$item_variation_id         = $item_id;
				}
				$item_price      = wc_get_price_to_display( $product, array( 'qty' => 1 ) );
				$item_price_html = $product->get_price_html();
				$item_image      = $product->get_image();
				$item_url        = $product->get_permalink();
			}
			if ( $item_price !== false && $item_price !== '' ) {
				$item_price  = VI_WOO_BOOSTSALES_Data::convert_price_to_float( $item_price );
				$total_price += $item_price;
				$total_items ++;
				$attributes_count = count( $item_variation_attributes );
				ob_start();
				?>
                <div class="vi-wbs-frequently-product-item <?php echo esc_attr( "vi-wbs-frequently-product-item-type-{$product_type}" ) ?>"
                     data-product_id="<?php echo esc_attr( $item_id ) ?>"
                     data-variation_id="<?php echo esc_attr( $item_variation_id ) ?>"
                     data-variation_attributes="<?php echo esc_attr( json_encode( $item_variation_attributes ) ) ?>"
                     data-item_price="<?php echo esc_attr( $item_price ) ?>"
                     data-item_quantity="<?php echo esc_attr( $quantity ) ?>">
                    <input type="checkbox" <?php echo apply_filters( 'vi_wbs_frequently_product_item_checked_by_default', true, $product_id, $item_id ) ? 'checked' : '' ?>
                           class="vi-wbs-frequently-product-item-check">
                    <div class="vi-wbs-frequently-product-item-detail">
						<?php
						$quantity_html = $quantity > 1 ? '<span class="product-quantity">x' . $quantity . '</span>' : '';
						if ( $product_id === $item_id && self::$is_product ) {
							?>
                            <span title="<?php echo esc_attr( $item_name ) ?>"
                                  class="vi-wbs-frequently-product-item-image"><?php echo $item_image . $quantity_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php
						} else {
							?>
                            <a href="<?php echo esc_url( $item_url ) ?>" class="vi-wbs-frequently-product-item-image"
                               target="_blank"
                               title="<?php echo esc_attr( $item_name ) ?>"><?php echo $item_image . $quantity_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
							<?php
						}
						$displayed_item_name = apply_filters( 'vi_wbs_frequently_product_item_displayed_name', $item_name, $item_id, $product_id, self::$is_product );
						?>
                        <div class="vi-wbs-frequently-product-item-text">
							<?php
							if ( $product_id === $item_id && self::$is_product ) {
								?>
                                <span class="vi-wbs-frequently-product-item-name"
                                      title="<?php echo esc_attr( $item_name ) ?>"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $displayed_item_name ) ?></span>
								<?php
							} else {
								?>
                                <a class="vi-wbs-frequently-product-item-name"
                                   href="<?php echo esc_url( $item_url ) ?>"
                                   target="_blank"
                                   title="<?php echo esc_attr( $item_name ) ?>"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $displayed_item_name ) ?></a>
								<?php
							}
							if ( $attributes_count ) {
								self::item_attributes_html( $product, $product_type, $item_variation_attributes, $select_type );
							}
							?>
                        </div>
                    </div>
                    <span class="vi-wbs-frequently-product-item-price"><?php echo $item_price_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                </div>
				<?php
				$frequently_item_html .= ob_get_clean();
			}
		}

		return $frequently_item_html;
	}

	protected static function item_attributes_html( $product, $product_type, $item_variation_attributes, $select_type ) {
		$variation_attributes = implode( ', ', $item_variation_attributes );
		$title                = $product_type === 'variation' ? $variation_attributes : '';
		?>
        <div class="vi-wbs-frequently-product-item-attributes">
            <div class="vi-wbs-frequently-product-item-attributes-value"
                 title="<?php echo esc_attr( $title ) ?>"><?php echo esc_html( $variation_attributes ) ?></div>
			<?php
			if ( $product_type === 'variable' ) {
				?>
                <div class="vi-wbs-frequently-product-item-attributes-arrow">
                    <div class="vi-wbs-frequently-product-arrow-down"></div>
                </div>
				<?php
			}
			?>
        </div>
        <div class="vi-wbs-frequently-product-arrow-left">
            <div class="vi-wbs-frequently-product-arrow-left-inner"></div>
        </div>
        <div class="vi-wbs-frequently-product-arrow-right">
            <div class="vi-wbs-frequently-product-arrow-right-inner"></div>
        </div>
		<?php
		if ( $product_type === 'variable' ) {
			do_action( 'woocommerce_boost_sales_frequently_product_select', $product, $item_variation_attributes, $select_type );
		}
	}

	protected static function get_bundle_items( $bundle_id ) {
		$bundle_items = array();
		$bundle_data  = get_post_meta( $bundle_id, '_wbs_wcpb_bundle_data', true );
		if ( is_array( $bundle_data ) && count( $bundle_data ) ) {
			$bundle_items = array_column( $bundle_data, 'product_id' );
		}

		return $bundle_items;
	}

	protected static function get_products_list_from_bundle( $product_id, &$bundle_id ) {
		$frequently_products = array();
		$other_bundle_id     = get_post_meta( $product_id, '_wbs_crosssells_bundle', true );
		if ( $other_bundle_id ) {
			$frequently_products = self::get_bundle_items( $other_bundle_id );
			if ( count( $frequently_products ) ) {
				$bundle_id = $other_bundle_id;
			}
		} else {
			$cross_sell_id = get_post_meta( $product_id, '_wbs_crosssells', true );
			if ( is_array( $cross_sell_id ) && count( $cross_sell_id ) ) {
				$frequently_products = self::get_bundle_items( $cross_sell_id[0] );
				if ( count( $frequently_products ) ) {
					$bundle_id = $cross_sell_id[0];
				}
			}
		}

		return $frequently_products;
	}

	protected static function get_added_attribute( $attributes, &$added_attributes ) {
		foreach ( $attributes as $key => $value ) {
			$key = substr( $key, 10 );
			if ( ! isset( $added_attributes[ $key ] ) ) {
				$added_attributes[ $key ] = array( $value );
				if ( substr( $key, 0, 3 ) === 'pa_' ) {
					$added_attributes[ substr( $key, 3 ) ] = array( $value );
				} else {
					$added_attributes["pa_{$key}"] = array( $value );
				}
			} elseif ( ! in_array( $value, $added_attributes[ $key ] ) ) {
				$added_attributes[ $key ][] = $value;
				if ( substr( $key, 0, 3 ) === 'pa_' ) {
					array_unshift( $added_attributes[ substr( $key, 3 ) ], $value );
				} else {
					array_unshift( $added_attributes["pa_{$key}"], $value );
				}
			}
		}
	}

	public static function get_added_attributes() {
		$added_attributes = array();
		$items            = WC()->cart->get_cart_contents();

		if ( count( $items ) ) {
			foreach ( $items as $item ) {
				self::get_added_attribute( $item['variation'], $added_attributes );
			}
		}

		return $added_attributes;
	}

	public static function is_product_in_cart( $product_id ) {
		$in_cart    = false;
		$cart_items = WC()->cart->get_cart();
		if ( is_array( $cart_items ) && count( $cart_items ) ) {
			foreach ( $cart_items as $cart_item ) {
				if ( $cart_item['product_id'] == $product_id ) {
					$in_cart = true;
					break;
				}
			}
		}

		return $in_cart;
	}
}