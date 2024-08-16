<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_VIEWS {
    private static $initiated = false;

    public static function views() {
		if ( ! self::$initiated ) :
			self::views_hooks();
		endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
	private static function views_hooks() {
        self::$initiated = true;
    }

    /** Actions **/
    public static function inext_wpc_product_page_action() {
        add_action( 'woocommerce_after_add_to_cart_button', array( 'INEXT_WPC_VIEWS', 'inext_wpc_product_page_action_callback' ) ); //will modify the classname to var
    }

    public static function inext_wpc_cart_page_action() {
        add_action( 'woocommerce_before_cart_totals', array( 'INEXT_WPC_VIEWS', 'inext_wpc_cart_page_action_callback' ) ); //will modify the classname to var
    }

    public static function inext_wpc_checkout_page_action() {
        add_action( 'woocommerce_checkout_before_order_review', array( 'INEXT_WPC_VIEWS', 'inext_wpc_checkout_page_action_callback' ) ); //will modify the classname to var
    }

    public static function inext_wpc_shortcode_action() {
        add_action( 'init', array( 'INEXT_WPC_VIEWS', 'inext_wpc_shortcode_action_callback' ) ); //will modify the classname to var
    }

    /** Callbacks **/
    public static function inext_wpc_product_page_action_callback() {
        INEXT_WPC_BASIC_VIEWS::inext_wpc_after_add_to_cart_button_action();
    }

    public static function inext_wpc_cart_page_action_callback() {
        INEXT_WPC_BASIC_VIEWS::inext_wpc_after_cart_totals_shipping_action();
    }

    public static function inext_wpc_checkout_page_action_callback() {
        INEXT_WPC_BASIC_VIEWS::inext_wpc_after_review_order_shipping_action();
    }

    public static function inext_wpc_shortcode_action_callback() {
        INEXT_WPC_BASIC_VIEWS::inext_wpc_shortcode_action();
    }
}

if(get_option( INEXT_WPC_PLUGIN_ENABLED )):
    if(get_option( INEXT_WPC_PLUGIN_PRODUCT_ENABLED )):
        INEXT_WPC_VIEWS::inext_wpc_product_page_action();
    endif;

    if(get_option( INEXT_WPC_PLUGIN_CART_ENABLED )):
        INEXT_WPC_VIEWS::inext_wpc_cart_page_action();
    endif;

    if(get_option( INEXT_WPC_PLUGIN_CHECKOUT_ENABLED )):
        INEXT_WPC_VIEWS::inext_wpc_checkout_page_action();
    endif;

    INEXT_WPC_VIEWS::inext_wpc_shortcode_action();
    INEXT_WPC_JS_VARIABLES::inext_wpc_js_variables_action();
endif;

require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'views/basic/class-basic-views.php' );

?>
