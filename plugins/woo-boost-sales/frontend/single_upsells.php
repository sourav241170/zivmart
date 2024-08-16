<?php

/**
 * Class VI_WOO_BOOSTSALES_Frontend_Single
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Frontend_Single_Upsells {
	protected $settings;

	public function __construct() {
		$this->settings = VI_WOO_BOOSTSALES_Data::get_instance();
		if ( $this->settings->enable() ) {
			if ( $this->settings->get_option( 'enable_upsell' ) ) {
				add_action( 'wp_footer', array( $this, 'init_upsells' ) );
			}
		}
	}

	/**
	 * Show HTML code
	 */
	public function init_upsells() {
		/*Get data form submition*/
		$product_id = filter_input( INPUT_POST, 'add-to-cart', FILTER_SANITIZE_NUMBER_INT );
		if ( ! $product_id ) {
			$product_id = filter_input( INPUT_GET, 'add-to-cart', FILTER_SANITIZE_NUMBER_INT );
			if ( is_plugin_active( 'woo-sticky-add-to-cart/woo-sticky-add-to-cart.php' ) ) {
				$vi_satc_settings     = new VI_WOO_STICKY_ATC_DATA();
				$vi_wsatc_sb_ajax_atc = $vi_satc_settings->get_params( 'sb_ajax_atc' );
			}
			if ( ! $product_id && empty( $vi_wsatc_sb_ajax_atc ) ) {
				return;
			}
		} else {
			if ( ! isset( VI_WOO_BOOSTSALES_Frontend_Upsells::$added_to_cart[ $product_id ] ) ) {
				return;
			}
		}
		$variation_id = filter_input( INPUT_POST, 'variation_id', FILTER_SANITIZE_NUMBER_INT );
		if ( $product_id || is_product() ) {
			if ( ! $product_id ) {
				$class      = 'wbs-ajax-loaded';
				$product_id = get_the_ID();
			} else {
				$class = 'wbs-form-submit';
			}
			$html = $this->show_product( $product_id, $variation_id );
			if ( $html ) {
				?>
                <div id="wbs-content-upsells"
                     class="woocommerce-boost-sales wbs-content-up-sell wbs-single-page <?php echo esc_attr( $class ) ?>">
					<?php echo $html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
				<?php
			} else {
				return;
			}
		}
	}

	/**
	 * @param $product_id
	 * @param $variation_id
	 *
	 * @return false|string
	 */
	protected function show_product( $product_id, $variation_id ) {
		$upsells  = VI_WOO_BOOSTSALES_Frontend_Upsells::get_upsells_ids( $product_id );
		$quantity = filter_input( INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT );
		if ( ! $quantity ) {
			$quantity = 1;
		}
		$obj_upsell = new VI_WOO_BOOSTSALES_Upsells( $product_id, $quantity, $upsells, $variation_id, VI_WOO_BOOSTSALES_Frontend_Upsells::$cart_item_key );

		return $obj_upsell->show_html();
	}
}