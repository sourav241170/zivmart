<?php

/**
 * Class VI_WOO_BOOSTSALES_Frontend_Notify
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\Jetpack\Constants;

class VI_WOO_BOOSTSALES_Frontend_Scripts {
	protected $settings;
	protected $message;
	protected $auto_open_cart;

	public function __construct() {
		$this->settings       = VI_WOO_BOOSTSALES_Data::get_instance();
		$this->auto_open_cart = false;
		if ( $this->settings->enable() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'init_scripts' ), 99999 );
			add_action( 'wp_enqueue_scripts', array( $this, 'init_scripts_side_cart' ), 999999 );
//			add_action( 'wp_ajax_nopriv_wbs_set_notice', array( $this, 'wbs_set_notice' ) );
//			add_action( 'wp_ajax_wbs_set_notice', array( $this, 'wbs_set_notice' ) );
		}
		add_action( 'woocommerce_before_main_content', array( $this, 'ajax_add_to_cart_notices' ) );
	}

	public function ajax_add_to_cart_notices() {
		?>
        <div class="wbs-add-to-cart-notices-ajax"></div>
		<?php
	}

	public function wbs_set_notice() {
		$product_id = isset( $_GET['product_id'] ) ? wc_clean( $_GET['product_id'] ) : '';
		$notices    = '';
		if ( $product_id && function_exists( 'wc_add_to_cart_message' ) ) {
			$notices .= '<div class="woocommerce-message">' . wc_add_to_cart_message( $product_id, false, true ) . '</div>';
		}
		wp_send_json( array( 'html' => $notices ) );
		die;
	}

	/**
	 * Auto open cart feature of WooCommerce Side cart for non-ajax crosssell
	 */
	public function init_scripts_side_cart() {
		if ( $this->auto_open_cart !== false && isset( $_POST['add-to-cart'], $_POST['quantity'] ) && sanitize_text_field( $_POST['add-to-cart'] ) && sanitize_text_field( $_POST['quantity'] ) ) {
			if ( WP_DEBUG || Constants::is_true( 'SCRIPT_DEBUG' ) ) {
				$js_ext = '.js';
			} else {
				$js_ext = '.min.js';
			}
			wp_enqueue_script( 'woo-boost-sales-woo-side-cart-script', VI_WOO_BOOSTSALES_JS . 'woo-boost-sales-side-cart' . $js_ext, array(
				'jquery',
				'xoo-wsc'
			) );
		}
	}

	/**
	 * Add Script and Style
	 */

	public function init_scripts() {
		$button_color                     = $this->settings->get_option( 'button_color' );
		$button_bg_color                  = $this->settings->get_option( 'button_bg_color' );
		$bg_color_cross_sell              = $this->settings->get_option( 'bg_color_cross_sell' );
		$text_color_cross_sell            = $this->settings->get_option( 'text_color_cross_sell' );
		$price_text_color_cross_sell      = $this->settings->get_option( 'price_text_color_cross_sell' );
		$save_price_text_color_cross_sell = $this->settings->get_option( 'save_price_text_color_cross_sell' );
		$custom_css                       = $this->settings->get_option( 'custom_css' );
		$icon_color                       = $this->settings->get_option( 'icon_color' );
		$icon_bg_color                    = $this->settings->get_option( 'icon_bg_color' );
		if ( WP_DEBUG || Constants::is_true( 'SCRIPT_DEBUG' ) ) {
			$css_ext = '.css';
			$js_ext  = '.js';
		} else {
			$css_ext = '.min.css';
			$js_ext  = '.min.js';
		}
		/*Flexslider*/

		wp_enqueue_style( 'jquery-vi_flexslider', VI_WOO_BOOSTSALES_CSS . 'vi_flexslider' . $css_ext, array(), '2.7.0' );
		wp_enqueue_script( 'jquery-vi_flexslider', VI_WOO_BOOSTSALES_JS . 'jquery.vi_flexslider' . $js_ext, array( 'jquery' ), '2.7.0', true );
		wp_enqueue_style( 'woo-boost-sales', VI_WOO_BOOSTSALES_CSS . 'woo-boost-sales' . $css_ext, array(), VI_WOO_BOOSTSALES_VERSION );
		if ( is_rtl() ) {
			wp_enqueue_style( 'woo-boost-sales-rtl', VI_WOO_BOOSTSALES_CSS . 'woo-boost-sales-rtl' . $css_ext, array(), VI_WOO_BOOSTSALES_VERSION );
		}

		wp_enqueue_script( 'woo-boost-sales', VI_WOO_BOOSTSALES_JS . 'woo-boost-sales' . $js_ext, array(
			'jquery',
			'jquery-vi_flexslider'
		), VI_WOO_BOOSTSALES_VERSION, true );

		$gl_options         = get_option( 'xoo-wsc-gl-options', array() );
		$auto_open_cart     = isset( $gl_options['sc-auto-open'] ) ? $gl_options['sc-auto-open'] : 1;
		$auto_redirect_time = $this->settings->get_option( 'redirect_after_second' );
		wp_localize_script( 'woo-boost-sales', 'woocommerce_boost_sales_params', array(
				'i18n_added_to_cart'          => esc_attr__( 'Added to cart', 'woo-boost-sales' ),
				'added_to_cart'               => count( VI_WOO_BOOSTSALES_Frontend_Upsells::$added_to_cart ) ? VI_WOO_BOOSTSALES_Frontend_Upsells::$added_to_cart : '',
				'url'                         => admin_url( 'admin-ajax.php' ),
				'side_cart_auto_open'         => $auto_open_cart,
				'product_option_warning'      => esc_attr__( 'Please choose product option you want to add to cart', 'woo-boost-sales' ),
				'hide_out_of_stock'           => $this->settings->get_option( 'hide_out_of_stock' ),
				'show_if_empty'               => $this->settings->get_option( 'show_if_empty' ),
				'wc_hide_out_of_stock'        => get_option( 'woocommerce_hide_out_of_stock_items' ),
				'crosssells_max_item_desktop' => apply_filters( 'woocommerce_boost_sales_crosssells_max_item', 3, 'desktop' ),
				'crosssells_max_item_tablet'  => apply_filters( 'woocommerce_boost_sales_crosssells_max_item', 2, 'tablet' ),
				'crosssells_max_item_mobile'  => apply_filters( 'woocommerce_boost_sales_crosssells_max_item', 2, 'mobile' ),
				'show_thank_you'              => false,
				'auto_redirect'               => $this->settings->get_option( 'enable_checkout' ),
				'crosssell_enable'            => $this->settings->get_option( 'crosssell_enable' ),
				'auto_redirect_time'          => $auto_redirect_time,
				'auto_redirect_message'       => sprintf( __( 'Redirect to checkout after <span>%s</span>s', 'woo-boost-sales' ), $auto_redirect_time ),
				'modal_price'                 => wc_price( 1 ),
				'decimal_separator'           => wc_get_price_decimal_separator(),
				'thousand_separator'          => wc_get_price_thousand_separator(),
				'decimals'                    => wc_get_price_decimals(),
				'price_format'                => get_woocommerce_price_format(),
			)
		);
		$css_inline = '';
		if ( $button_bg_color ) {
			$css_inline .= "
			.woocommerce-boost-sales .wbs-upsells .product-controls button.wbs-single_add_to_cart_button,
			.woocommerce-boost-sales .wbs-upsells-items .product-controls button.wbs-single_add_to_cart_button,
			.wbs-content-inner-crs .wbs-crosssells-button-atc button.wbs-single_add_to_cart_button,
			.woocommerce-boost-sales .wbs-upsells .product-controls .wbs-cart .wbs-product-link,
			.wbs-content-inner-crs .wbs-crosssells-button-atc button.wbs-single_add_to_cart_button,
			.woocommerce-boost-sales .wbs-breadcrum .wbs-header-right a,
			.vi-wbs-btn-redeem{
				background-color: {$button_bg_color};
			}";
		}
		if ( $button_color ) {
			$css_inline .= ".wbs-content-inner-crs .wbs-crosssells-button-atc .wbs-single_add_to_cart_button,
			.vi-wbs-btn-redeem:hover,.woocommerce-boost-sales .wbs-breadcrum .wbs-header-right a::before,
			.woocommerce-boost-sales .wbs-upsells .product-controls button.wbs-single_add_to_cart_button:hover,
			.woocommerce-boost-sales .wbs-upsells-items .product-controls button.wbs-single_add_to_cart_button:hover,
			.wbs-content-inner-crs .wbs-crosssells-button-atc button.wbs-single_add_to_cart_button:hover,
			.woocommerce-boost-sales .wbs-upsells .product-controls .wbs-cart .wbs-product-link:hover{
			background-color: {$button_color};
			}	";
		}
		if ( $bg_color_cross_sell || $text_color_cross_sell ) {
			$css_inline .= "
				.woocommerce-boost-sales .wbs-content-crossell{
				background-color: {$bg_color_cross_sell}; 
				color:{$text_color_cross_sell}
				}";

		}
		if ( $price_text_color_cross_sell ) {
			$css_inline .= "
				.wbs-crs-regular-price{
				color: {$price_text_color_cross_sell}; 
				}";
		}
		if ( $save_price_text_color_cross_sell ) {
			$css_inline .= "
				.wbs-crosssells-price > div.wbs-crs-save-price > div.wbs-save-price{
				color: {$save_price_text_color_cross_sell}; 
				}";
		}
		if ( $icon_color && $icon_bg_color ) {
			$css_inline .= "
				.gift-button.gift_right.wbs-icon-font:before{
				background-color: {$icon_bg_color}; 
				color: {$icon_color}; 
				}";
		}
		$css_inline .= $custom_css;
		wp_add_inline_style( 'woo-boost-sales', $css_inline );
	}
}