<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Data {
	private $params, $default, $detect;
	protected static $instance = null;
	protected static $allow_html = null;

	/**
	 * VI_WOO_BOOSTSALES_Data constructor.
	 * Init setting
	 */
	public function __construct() {
		global $wbs_settings;
		if ( ! $wbs_settings ) {
			$wbs_settings = get_option( '_woocommerce_boost_sales', array() );
		}
		$this->params  = $wbs_settings;
		$this->default = array(
			'enable'                                       => 0,
			'enable_mobile'                                => 0,
			/*Upsell*/
			'enable_upsell'                                => 0,
			'hide_on_single_product_page'                  => 0,
			'hide_on_cart_page'                            => 0,
			'hide_on_checkout_page'                        => 0,
			'show_recently_viewed_products'                => 0,
			'hide_products_added'                          => 0,
			'show_with_category'                           => 0,
			'show_with_tags'                               => 1,
//			'show_upsells_checkbox'                        => 0,
			'exclude_product'                              => array(),
			'upsell_exclude_products'                      => array(),
			'exclude_categories'                           => array(),
			'upsell_exclude_categories'                    => array(),
			'sort_product'                                 => 0,
			'ajax_button'                                  => 0,
			'hide_view_more_button'                        => 0,
			'show_with_subcategory'                        => 0,
			'hide_out_stock'                               => 0,
			'go_to_cart'                                   => 0,
			'ajax_add_to_cart_for_upsells'                 => 0,
			'show_if_empty'                                => 0,
			'add_to_cart_style'                            => 'hover',
			'add_to_cart_style_mobile'                     => 'hover',
			/*Cross-sells*/
			'crosssell_enable'                             => 0,
			'crosssells_hide_on_single_product_page'       => 0,
			'crosssell_display_on'                         => 0,
			'crosssell_custom_position'                    => '',
			'crosssell_display_on_slide'                   => 0,
			'hide_cross_sell_archive'                      => 0,
			'enable_cart_page'                             => 0,
			'cart_page_option'                             => 1,
			'crosssell_display_on_cart'                    => 'popup',
			'crosssell_custom_position_cart'               => '',
			'enable_checkout_page'                         => 0,
			'bundle_added'                                 => 0,
			'checkout_page_option'                         => 1,
			'crosssell_display_on_checkout'                => 'popup',
			'crosssell_custom_position_checkout'           => '',
			'crosssell_template'                           => 'slider',
			'display_saved_price'                          => 0,
			'override_products_on_cart'                    => 0,
			'ajax_add_to_cart_for_crosssells'              => 0,
			'hide_out_of_stock'                            => 0,
			'product_bundle_name'                          => 'Bundle of {product_title}',
			'bundle_price_from'                            => array( 0 ),
			'bundle_price_discount_value'                  => array( 0 ),
			'bundle_price_discount_type'                   => array( 'percent' ),
			'bundle_price_dynamic'                         => array( '1' ),
			'bundle_categories'                            => array(),
			/*Discount bar*/
			'enable_discount'                              => 0,
			'discount_always_show'                         => 0,
			'coupon'                                       => '',
			'coupon_position'                              => 0,
			'text_color_discount'                          => '#111111',
			'process_color'                                => '#111111',
			'process_background_color'                     => '#bdbdbd',
			'coupon_desc'                                  => '',
			'enable_thankyou'                              => 0,
			'message_congrats'                             => 'You have successfully reached the goal, and a {discount_amount} discount will be applied to your order.',
			'enable_checkout'                              => 0,
			'redirect_after_second'                        => 5,
			/*Button*/
			'button_color'                                 => '#111111',
			'button_bg_color'                              => '#bdbdbd',
			/*Upsells*/
			'item_per_row'                                 => 4,
			'item_per_row_mobile'                          => 1,
			'limit'                                        => 8,
			'select_template'                              => 1,
			'message_bought'                               => 'Frequently bought with {name_product}',
			'upsell_mobile_template'                       => 'slider',
			'continue_shopping_title'                      => 'Continue Shopping',
			'continue_shopping_action'                     => 'stay',
			/*Cross-sell*/
			'crosssell_description'                        => '',
			'init_delay'                                   => '3,10',
			'enable_cross_sell_open'                       => 0,
			'icon_position'                                => 0,
			'icon'                                         => 0,
			'icon_color'                                   => '#555',
			'icon_bg_color'                                => '#fff',
			'hide_gift'                                    => 0,
			'bg_color_cross_sell'                          => '#fff',
			'bg_image_cross_sell'                          => '',
			'text_color_cross_sell'                        => '#9e9e9e',
			'price_text_color_cross_sell'                  => '#111111',
			'save_price_text_color_cross_sell'             => '#111111',
			'crosssell_mobile_template'                    => 'slider',
			'custom_css'                                   => '',
			/*Update*/
			'key'                                          => '',
			/*Frequently product*/
			'frequently_product'                           => 0,
			'frequently_product_hide_if_added'             => 1,
			'frequently_product_source'                    => 'up_sells',
			'frequently_product_style'                     => 'horizontal',
			'frequently_product_add_bundle_if_cross_sells' => 1,
			'frequently_product_position'                  => 'after_cart',
			'frequently_product_currently_watching'        => 'show_if_not_added',
			'frequently_product_currently_watching_text'   => 'You\'re watching:',
			'frequently_product_add_to_cart_text'          => 'ADD TO CART',
			'frequently_product_message'                   => 'Frequently bought together:',
			'frequently_product_after_successful_atc'      => 'none',
			'frequently_product_max_title_line'            => 1,
			'frequently_product_show_attribute'            => 'click',
			'frequently_product_select_type'               => 'button',
			'frequently_product_ajax_load'                 => 0,
			'frequently_product_image_size'                => 48,
			'frequently_product_show_rating'               => 0,
		);
		$this->params  = apply_filters( 'wbs_settings_args', wp_parse_args( $this->params, $this->default ) );
	}

	public static function get_instance( $new = false ) {
		if ( $new || null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function get_detect() {
		if ( $this->detect === null ) {
			$detect = new VillaTheme_Mobile_Detect();
			if ( $detect->isMobile() && ! $detect->isTablet() ) {
				$this->detect = 'mobile';
			} elseif ( $detect->isTablet() ) {
				$this->detect = 'tablet';
			} else {
				$this->detect = 'desktop';
			}
		}

		return $this->detect;
	}

	/**
	 * @param $field_name
	 * @param string $language
	 *
	 * @return bool|mixed|void
	 */
	public function get_option( $field_name = '', $language = '' ) {
		if ( $field_name ) {
			if ( $language ) {
				$field_name_language = $field_name . '_' . $language;
				if ( array_key_exists( $field_name_language, $this->params ) ) {
					return apply_filters( 'wbs_get_' . $field_name_language, $this->params[ $field_name_language ] );
				} elseif ( array_key_exists( $field_name, $this->params ) ) {
					return apply_filters( 'wbs_get_' . $field_name_language, $this->params[ $field_name ] );
				} else {
					return false;
				}
			} else {
				if ( array_key_exists( $field_name, $this->params ) ) {
					return apply_filters( 'wbs_get_' . $field_name, $this->params[ $field_name ] );
				} else {
					return false;
				}
			}
		} else {
			return $this->params;
		}
	}

	public function get_default( $name = "" ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 's2w_params_default' . $name, $this->default[ $name ] );
		} else {
			return false;
		}
	}

	public function enable() {
		$enble        = $this->get_option( 'enable' );
		$enble_mobile = $this->get_option( 'enable_mobile' );
		// Any mobile device (phones or tablets).
		// Include and instantiate the class.
		$detect = new VillaTheme_Mobile_Detect();
		if ( $detect->isMobile() && ! $detect->isTablet() ) {
			$this->detect = 'mobile';
		} elseif ( $detect->isTablet() ) {
			$this->detect = 'tablet';
		} else {
			$this->detect = 'desktop';
		}
		if ( $detect->isMobile() ) {
			if ( ! $enble_mobile || ! $enble ) {
				return false;
			}
		}

		return $enble;
	}

	public static function search_product_statuses() {
		return apply_filters( 'woocommerce_boost_sales_search_product_statuses', current_user_can( 'edit_private_products' ) ? array(
			'private',
			'publish'
		) : array( 'publish' ) );
	}

	public static function sanitize_taxonomy_name( $name ) {
		return strtolower( urlencode( wc_sanitize_taxonomy_name( $name ) ) );
	}

	public static function find_matching_product_variation_id( $product_id, $attributes ) {
		return ( new \WC_Product_Data_Store_CPT() )->find_matching_product_variation(
			new \WC_Product( $product_id ),
			$attributes
		);
	}

	public static function convert_price_to_float( $price ) {
		$args = apply_filters(
			'wc_price_args',
			array(
				'ex_tax_label'       => false,
				'currency'           => '',
				'decimal_separator'  => wc_get_price_decimal_separator(),
				'thousand_separator' => wc_get_price_thousand_separator(),
				'decimals'           => wc_get_price_decimals(),
				'price_format'       => get_woocommerce_price_format(),
			)
		);

		$negative = $price < 0;
		$price    = floatval( $negative ? $price * - 1 : $price );
		$price    = number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

		return floatval( str_replace( array( $args['thousand_separator'], $args['decimal_separator'] ), array(
			'',
			'.'
		), $price ) );
	}

	public static function wp_kses_post( $content ) {
		if ( self::$allow_html === null ) {
			self::$allow_html = wp_kses_allowed_html( 'post' );
			self::$allow_html = array_merge_recursive( self::$allow_html, array(
					'input'  => array(
						'type'         => 1,
						'id'           => 1,
						'name'         => 1,
						'class'        => 1,
						'placeholder'  => 1,
						'autocomplete' => 1,
						'style'        => 1,
						'value'        => 1,
						'size'         => 1,
						'checked'      => 1,
						'disabled'     => 1,
						'readonly'     => 1,
						'data-*'       => 1,
					),
					'form'   => array(
						'method' => 1,
						'id'     => 1,
						'class'  => 1,
						'action' => 1,
						'data-*' => 1,
					),
					'select' => array(
						'id'       => 1,
						'name'     => 1,
						'class'    => 1,
						'multiple' => 1,
						'data-*'   => 1,
					),
					'option' => array(
						'value'    => 1,
						'selected' => 1,
						'data-*'   => 1,
					),
				)
			);
			foreach ( self::$allow_html as $key => $value ) {
				if ( $key === 'input' ) {
					self::$allow_html[ $key ]['data-*']   = 1;
					self::$allow_html[ $key ]['checked']  = 1;
					self::$allow_html[ $key ]['disabled'] = 1;
					self::$allow_html[ $key ]['readonly'] = 1;
				} elseif ( in_array( $key, array(
					'div',
					'span',
					'a',
					'form',
					'select',
					'option',
					'tr',
					'td',
					'img'
				) ) ) {
					self::$allow_html[ $key ]['data-*'] = 1;
				}
			}
		}

		return wp_kses( $content, self::$allow_html );
	}

}