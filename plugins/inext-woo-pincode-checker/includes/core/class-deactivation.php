<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_DEACTIVATION {
    private static $initiated = false;

    public static function deactivate() {
		if ( ! self::$initiated ) {
			self::deactivation_hooks();
		}
	}

    /**
 	 * Initializes WordPress hooks
 	**/
    private static function deactivation_hooks() {
        self::$initiated = true;
    }

    /** Actions **/
    public static function inext_wpc_deactivation_action() {
        register_deactivation_hook( INEXT_WPC_PLUGIN_FILE, array ( 'INEXT_WPC_DEACTIVATION', 'inext_wpc_deactivation_action_callback') );
    }

    /** Callbacks **/
    public static function inext_wpc_deactivation_action_callback() {
        delete_option( INEXT_WPC_PLUGIN_ACTIVATED);

        delete_option( INEXT_WPC_PLUGIN_ENABLED );
        delete_option( INEXT_WPC_PLUGIN_PRODUCT_ENABLED );
        delete_option( INEXT_WPC_PLUGIN_CART_ENABLED );
        delete_option( INEXT_WPC_PLUGIN_CHECKOUT_ENABLED );
        delete_option( INEXT_WPC_PLUGIN_SINGLE_PRODUCT_ATC_BTN_DISABLED);
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_ALPHANUMERIC_ENABLED );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL_ENABLED );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER_ENABLED );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BUTTON_TEXT );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LENGTH );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_SUCCESS );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_ERROR );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_FOUND );
        delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_VALID );
		delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_MIN_LENGTH_ERROR );
		delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_MAX_LENGTH_ERROR );
    	delete_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BLANK );
    }
}

INEXT_WPC_DEACTIVATION::inext_wpc_deactivation_action();
?>
