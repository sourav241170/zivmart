<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_ACTIVATION {
    private static $initiated = false;

    public static function activate() {
		if ( ! self::$initiated ) :
			self::activation_hooks();
		endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
    private static function activation_hooks() {
        self::$initiated = true;
    }

    /** Actions **/
    public static function inext_wpc_activation_action() {
        register_activation_hook( INEXT_WPC_PLUGIN_FILE, array ( 'INEXT_WPC_ACTIVATION', 'inext_wpc_activation_action_callback') );
    }

    /** Callbacks **/
    public static function inext_wpc_activation_action_callback() {
        update_option( INEXT_WPC_PLUGIN_ACTIVATED, 1 );

        update_option( INEXT_WPC_PLUGIN_ENABLED, 1 );
        update_option( INEXT_WPC_PLUGIN_PRODUCT_ENABLED, 1 );
        update_option( INEXT_WPC_PLUGIN_CART_ENABLED, 0 );
        update_option( INEXT_WPC_PLUGIN_CHECKOUT_ENABLED, 0 );
        update_option( INEXT_WPC_PLUGIN_SINGLE_PRODUCT_ATC_BTN_DISABLED, 0 );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_ALPHANUMERIC_ENABLED, 1 );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL_ENABLED, 1 );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL, 'Use pincode to check delivery info' );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER_ENABLED, 1 );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER, 'Enter a pin code' );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED, 1 );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BUTTON_TEXT, 'Check' );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LENGTH, '6' );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_SUCCESS, 'Delivery is available to this area' );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_ERROR, 'We are not delivering to this area' );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_FOUND, 'Pincode not available' );
        update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_VALID, 'Please enter a valid pin code' );
		update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_MIN_LENGTH_ERROR, 'Please enter minimum 6 digits' );
		update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_MAX_LENGTH_ERROR, 'Please enter maximum 6 digits' );
    	update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BLANK, 'Please enter a pincode' );
    }
}

INEXT_WPC_ACTIVATION::inext_wpc_activation_action();
?>
