<?php

/**
 * Class VI_WOO_BOOSTSALES_Frontend_Single
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Frontend_Cross_Sells {
	protected static $settings;
	protected static $bundles_from_cart;
	protected $cross_sell_shown;

	public function __construct() {
		self::$settings         = VI_WOO_BOOSTSALES_Data::get_instance();
		$this->cross_sell_shown = false;
		/*Check global enable*/
		if ( self::$settings->enable() ) {
			/*Check cross-sell enable*/
			if ( self::$settings->get_option( 'crosssell_enable' ) ) {
				add_action( 'wp_footer', array( $this, 'show_crosssell_popup' ) );
			}
		}
//		add_filter( 'woocommerce_get_price_html', array( $this, 'woocommerce_get_price_html' ), 10, 2 );
	}

	/**Show original price for WBS bundle products on single page
	 *
	 * @param $price_html
	 * @param $product WC_Product_Wbs_Bundle
	 *
	 * @return float|string
	 */
	public function woocommerce_get_price_html( $price_html, $product ) {
		$original_price = '';
		if ( is_product() && $product && $product->is_type( 'wbs_bundle' ) ) {
			$bundled_items = $product->get_bundled_items();
			if ( count( $bundled_items ) ) {
				$array_price = array();
				foreach ( $bundled_items as $bundled_item ) {
					/**
					 * @var WBS_WC_Bundled_Item $bundled_item
					 */
					$bundled_product = $bundled_item->get_product();
					$price           = wc_get_price_to_display( $bundled_product );
					$array_price[]   = $price;
				}
				$sum_pr               = array_sum( $array_price );
				$product_bundle_price = wc_get_price_to_display( $product );
				$save_price           = $sum_pr - $product_bundle_price;
				if ( $save_price > 0 ) {
					ob_start();
					?>
                    <del>
						<?php echo wc_price( $sum_pr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                    </del>
					<?php
					$original_price = ob_get_clean();
				}
			}
		}
		$price_html = $original_price . $price_html;

		return $price_html;
	}

	public function woocommerce_after_template_part( $name ) {
		if ( is_product() && $name === 'single-product/tabs/description.php' ) {
			$this->show_crosssell_product();
		}
	}

	/**
	 * Show bundles below description
	 */
	public function show_crosssell_product() {
		if ( is_product() && ! $this->cross_sell_shown ) {
			$this->cross_sell_shown = true;
			$product_id             = get_the_ID();
			$other_bundle_id        = get_post_meta( $product_id, '_wbs_crosssells_bundle', true );
			if ( $other_bundle_id ) {
				if ( get_post_status( $other_bundle_id ) == 'publish' ) {
					if ( self::is_bundle_in_cart( $other_bundle_id ) ) {
						return;
					}
					if ( ( self::$settings->get_option( 'hide_out_of_stock' ) && ! self::is_in_stock( $other_bundle_id ) ) ) {
						return;
					}
					$output = new VI_WOO_BOOSTSALES_Cross_Sells( $other_bundle_id );
					echo $output->show_html( true );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			} else {
				$crosssells = get_post_meta( $product_id, '_wbs_crosssells', true );
				if ( isset( $crosssells[0] ) ) {
					if ( get_post_status( $crosssells[0] ) == 'publish' ) {
						if ( self::is_bundle_in_cart( $crosssells[0] ) ) {
							return;
						}
						if ( ( self::$settings->get_option( 'hide_out_of_stock' ) && ! self::is_in_stock( $crosssells[0] ) ) ) {
							return;
						}
						$output = new VI_WOO_BOOSTSALES_Cross_Sells( $crosssells[0] );
						echo $output->show_html( true );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				}
			}
		}
	}

	public static function is_bundle_in_cart( $bundle_id ) {
		$return       = false;
		$cart_content = WC()->cart->cart_contents;
		if ( is_array( $cart_content ) && count( $cart_content ) ) {
			foreach ( $cart_content as $key => $value ) {
				if ( $value['product_id'] == $bundle_id && ! empty( $value['wbs_bundled_items'] ) ) {
					$return = true;
					break;
				}
			}
		}

		return $return;
	}

	public function show_crosssell_popup() {
		if ( is_product() ) {
			$product_id        = filter_input( INPUT_POST, 'add-to-cart', FILTER_SANITIZE_NUMBER_INT );
			$quantity          = filter_input( INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT );
			$hide_out_of_stock = self::$settings->get_option( 'hide_out_of_stock' );
			if ( $product_id && $quantity ) {
				$other_bundle_id = get_post_meta( $product_id, '_wbs_crosssells_bundle', true );
				if ( $other_bundle_id ) {
					if ( get_post_status( $other_bundle_id ) == 'publish' ) {
						if ( self::is_bundle_in_cart( $other_bundle_id ) ) {
							return;
						}
						if ( ( $hide_out_of_stock && ! self::is_in_stock( $other_bundle_id ) ) ) {
							return;
						}
						$output = new VI_WOO_BOOSTSALES_Cross_Sells( $other_bundle_id );
						echo $output->show_html();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				} else {
					$crosssells = get_post_meta( $product_id, '_wbs_crosssells', true );

					if ( isset( $crosssells[0] ) ) {
						if ( get_post_status( $crosssells[0] ) == 'publish' ) {
							if ( self::is_bundle_in_cart( $crosssells[0] ) ) {
								return;
							}
							if ( ( $hide_out_of_stock && ! self::is_in_stock( $crosssells[0] ) ) ) {
								return;
							}
							$output = new VI_WOO_BOOSTSALES_Cross_Sells( $crosssells[0] );
							echo $output->show_html();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					}
				}
			} else {
				$other_bundle_id = get_post_meta( get_the_ID(), '_wbs_crosssells_bundle', true );
				if ( $other_bundle_id ) {
					if ( get_post_status( $other_bundle_id ) == 'publish' ) {
						if ( self::is_bundle_in_cart( $other_bundle_id ) ) {
							return;
						}
						if ( ( $hide_out_of_stock && ! self::is_in_stock( $other_bundle_id ) ) ) {
							return;
						}
						$output = new VI_WOO_BOOSTSALES_Cross_Sells( $other_bundle_id );
						echo $output->show_html();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				} else {
					$crosssells = get_post_meta( get_the_ID(), '_wbs_crosssells', true );
					if ( isset( $crosssells[0] ) ) {
						if ( get_post_status( $crosssells[0] ) == 'publish' ) {
							if ( self::is_bundle_in_cart( $crosssells[0] ) ) {
								return;
							}
							if ( ( $hide_out_of_stock && ! self::is_in_stock( $crosssells[0] ) ) ) {
								return;
							}
							$output = new VI_WOO_BOOSTSALES_Cross_Sells( $crosssells[0] );
							echo $output->show_html();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					}
				}
			}
		}
	}

	/**
	 * @param $bundle_id
	 *
	 * @return bool
	 */
	protected static function is_in_stock( $bundle_id ) {
		$instock        = true;
		$product_bundle = wc_get_product( $bundle_id );
		if ( ! $product_bundle ) {
			return false;
		} elseif ( ! $product_bundle->is_type( 'wbs_bundle' ) ) {
			return $product_bundle->is_in_stock();
		}
		$bundled_items = $product_bundle->get_bundled_items();
		if ( ! count( $bundled_items ) ) {
			return false;
		}
		foreach ( $bundled_items as $bundled_item ) {
			if ( ! $bundled_item->is_in_stock() ) {
				$instock = false;
				break;
			}
		}

		return $instock;
	}

	protected static function get_random_bundle_id( $crosssells, $hide_out_of_stock, &$bundle_of ) {
		$bundle_id = '';
		$bundle_of = '';
		if ( count( $crosssells ) ) {
			$index     = rand( 0, count( $crosssells ) - 1 );
			$bundle_id = $crosssells[ $index ]['id'];
			$bundle_of = $crosssells[ $index ]['bundle_of'];
			if ( $hide_out_of_stock && ! self::is_in_stock( $bundle_id ) ) {
				unset( $crosssells[ $index ] );
				$bundle_id = self::get_random_bundle_id( $crosssells, $hide_out_of_stock, $bundle_of );
			}
		}

		return $bundle_id;
	}
}