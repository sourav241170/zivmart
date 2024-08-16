<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_VARIABLE {
    private static $initiated = false;

    public static function variable() {
		if ( ! self::$initiated ) :
			self::variable_hooks();
		endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
    private static function variable_hooks() {
        self::$initiated = true;
    }

    /** Actions **/
    public static function inext_wpc_variable_action() {
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_ACTIVATED', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_is_activated' );

        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_ENABLED', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_is_enabled' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PRODUCT_ENABLED', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_is_product_enabled' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_CART_ENABLED', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_is_cart_enabled' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_CHECKOUT_ENABLED', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_is_checkout_enabled' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_SINGLE_PRODUCT_ATC_BTN_DISABLED', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_is_single_product_atc_btn_disabled' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_ALPHANUMERIC_ENABLED', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_is_pincode_alphanumeric_enabled' );
        
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_LABEL', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_label' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_LABEL_ENABLED', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_label_is_enabled' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_PLACEHOLDER', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_placeholder' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_PLACEHOLDER_ENABLED', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_placeholder_is_enabled' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_user_shipping_fetch_is_enabled' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_BUTTON_TEXT', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_button_text' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_LENGTH', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_length' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_SUCCESS', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_success' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_ERROR', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_error' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_NOT_FOUND', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_not_found' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_NOT_VALID', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_not_valid' );
		define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_MIN_LENGTH_ERROR', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_min_length_error' );
		define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_MAX_LENGTH_ERROR', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_max_length_error' );
        define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_PINCODE_FIELD_BLANK', '_' . INEXT_WPC_PLUGIN_GLOBAL_NAME_LOWER.'_pincode_field_blank' );
    }
}

INEXT_WPC_VARIABLE::inext_wpc_variable_action();
?>
