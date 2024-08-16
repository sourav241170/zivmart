<?php

/**
 * Class VI_WOO_BOOSTSALES_Frontend_Notify
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Frontend_Archive_Upsells {
	protected $settings;

	public function __construct() {
		$this->settings = VI_WOO_BOOSTSALES_Data::get_instance();
		if ( $this->settings->enable() && $this->settings->get_option( 'enable_upsell' ) ) {
			add_action( 'wp_footer', array( $this, 'init_boost_sales' ) );
			add_action( 'wp_ajax_wbs_get_product', array( $this, 'product_html' ) );
			add_action( 'wp_ajax_nopriv_wbs_get_product', array( $this, 'product_html' ) );
			add_filter( 'woocommerce_add_to_cart_fragments', array(
				$this,
				'save_added_to_cart'
			) );
		}
	}

	public function save_added_to_cart( $fragments ) {
		$wc_ajax    = isset( $_GET['wc-ajax'] ) ? sanitize_text_field( $_GET['wc-ajax'] ) : '';
		$product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : '';
		if ( in_array( $wc_ajax, array(
				'wpvs_add_to_cart',
				'add_to_cart'
			) ) && count( VI_WOO_BOOSTSALES_Frontend_Upsells::$added_to_cart ) && $product_id ) {
			$added_to_cart = VI_WOO_BOOSTSALES_Frontend_Upsells::$added_to_cart;
			if ( ! empty( $added_to_cart[ $product_id ] ) ) {
				$fragments['wbs_added_to_cart'] = $added_to_cart;
				$upsells                        = VI_WOO_BOOSTSALES_Frontend_Upsells::get_upsells_ids( $product_id );
				$quantity                       = $added_to_cart[ $product_id ]['quantity'];
				$obj_upsell                     = new VI_WOO_BOOSTSALES_Upsells( $product_id, $quantity, $upsells, $product_id, VI_WOO_BOOSTSALES_Frontend_Upsells::$cart_item_key );
				$fragments['wbs_upsells_html']  = $obj_upsell->show_html();
			}
		}

		return $fragments;
	}

	/**
	 * Show HTML on front end
	 */
	public function product_html() {
		VI_WOO_BOOSTSALES_Frontend_Upsells::$added_to_cart = isset( $_POST['added_to_cart'] ) ? wc_clean( $_POST['added_to_cart'] ) : array();
		$enable                                            = $this->settings->get_option( 'enable' );
		$product_id                                        = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
		$upsells_html                                      = '';
		if ( $enable && $product_id ) {
			$upsells_html = $this->show_product( $product_id );
		}
		wp_send_json( array(
			'upsells_html'      => $upsells_html,
			'discount_bar_html' => '',
		) );
	}


	/**
	 * Show HTML code
	 */
	public function init_boost_sales() {
		wp_enqueue_script( 'wc-add-to-cart-variation' );
		if ( ! is_single() ) {
			echo $this->show_product();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * @param null $product_id
	 *
	 * @return false|string
	 */
	protected function show_product( $product_id = null ) {
		if ( ! $product_id ) {
			ob_start();
			?>
            <div id="wbs-content-upsells"
                 class="woocommerce-boost-sales wbs-content-up-sell wbs-archive-page" style="display: none;"></div>
			<?php
			return ob_get_clean();
		} else {
			$upsells  = VI_WOO_BOOSTSALES_Frontend_Upsells::get_upsells_ids( $product_id );
			$quantity = filter_input( INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT );

			$obj_upsell = new VI_WOO_BOOSTSALES_Upsells( $product_id, $quantity, $upsells, $product_id, VI_WOO_BOOSTSALES_Frontend_Upsells::$cart_item_key );

			return $obj_upsell->show_html();
		}
	}
}