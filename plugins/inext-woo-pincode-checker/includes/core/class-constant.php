<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_CONSTANT {
    private static $initiated = false;

    public static function constant() {
		if ( ! self::$initiated ) :
			self::constant_hooks();
		endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
    private static function constant_hooks() {
        self::$initiated = true;
    }

    /** Actions **/
    public static function inext_wpc_constant_action() {
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_NAME', 'iNext Woo Pincode Checker' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_SHORT_NAME', 'Woo Pincode Checker' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PREVIOUS_VERSION', '1.0.0' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_VERSION', '2.0.2' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_MIN_WP_VERSION', '5.0.0' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_MIN_PHP_VERSION', '5.4.0' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_ASSETS', INEXT_WPC_PLUGIN_NAME_URL . 'assets' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_TEXT_DOMAIN', 'inext-woo-pincode-checker' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_SHORT_TEXT_DOMAIN', 'inext-wpc' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_GLOBAL_NAME_LOWER', 'inext_wpc' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_AUTHOR_NAME', 'Imdad Next Web' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PUBLISH_YEAR', '2022' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_AUTHOR_URL', 'https://imdadnextweb.com' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_SLUG', 'inext-wpc' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_SLUG__', 'inext_wpc' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_LOADER_WRAPPER_CLASS', 'inext_loader_wrapper' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_LOADER_CLASS', 'inext_loader' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_LOADER', "<div class='". INEXT_WPC_PLUGIN_LOADER_WRAPPER_CLASS ."'><div class='". INEXT_WPC_PLUGIN_LOADER_CLASS ."'></div></div>" );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_SINGLE_PRODUCT_ATC_BTN', ".single-product form.cart button[name='add-to-cart']" );
    }
}

INEXT_WPC_CONSTANT::inext_wpc_constant_action();
?>
