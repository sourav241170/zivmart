<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_ADMIN_AJAX {
	public static function inext_wpc_save_settings_general(){
		$msg = [];

		if ( is_admin() ) {
            // Check if nonce is set
            if(!isset($_POST['inext_wpc_save_settings_general_nonce'])):
                return 0;
            endif;

        	if(!current_user_can('manage_options')):
                return 0;
            endif;

        	if(isset($_POST["_inext_wpc_is_enabled"]) && $_POST["_inext_wpc_is_enabled"] == 1):
                update_option( INEXT_WPC_PLUGIN_ENABLED, 1 );
			else :
				update_option( INEXT_WPC_PLUGIN_ENABLED, 0 );
            endif;

			if(isset($_POST["_inext_wpc_is_product_enabled"]) && $_POST["_inext_wpc_is_product_enabled"] == 1):
                update_option( INEXT_WPC_PLUGIN_PRODUCT_ENABLED, 1 );
			else :
				update_option( INEXT_WPC_PLUGIN_PRODUCT_ENABLED, 0 );
            endif;

			if(isset($_POST["_inext_wpc_is_cart_enabled"]) && $_POST["_inext_wpc_is_cart_enabled"] == 1):
                update_option( INEXT_WPC_PLUGIN_CART_ENABLED, 1 );
			else :
				update_option( INEXT_WPC_PLUGIN_CART_ENABLED, 0 );
            endif;

			if(isset($_POST["_inext_wpc_is_checkout_enabled"]) && $_POST["_inext_wpc_is_checkout_enabled"] == 1):
                update_option( INEXT_WPC_PLUGIN_CHECKOUT_ENABLED, 1 );
			else :
				update_option( INEXT_WPC_PLUGIN_CHECKOUT_ENABLED, 0 );
            endif;

            if(isset($_POST["_inext_wpc_is_single_product_atc_btn_disabled"]) && $_POST["_inext_wpc_is_single_product_atc_btn_disabled"] == 1):
                update_option( INEXT_WPC_PLUGIN_SINGLE_PRODUCT_ATC_BTN_DISABLED, 1 );
			else :
				update_option( INEXT_WPC_PLUGIN_SINGLE_PRODUCT_ATC_BTN_DISABLED, 0 );
            endif;

			if(isset($_POST["_inext_wpc_is_pincode_alphanumeric_enabled"]) && $_POST["_inext_wpc_is_pincode_alphanumeric_enabled"] == 1):
                update_option( INEXT_WPC_PLUGIN_PINCODE_ALPHANUMERIC_ENABLED, 1 );
			else :
				update_option( INEXT_WPC_PLUGIN_PINCODE_ALPHANUMERIC_ENABLED, 0 );
            endif;

            if(isset($_POST["_inext_wpc_pincode_user_shipping_fetch_is_enabled"]) && $_POST["_inext_wpc_pincode_user_shipping_fetch_is_enabled"] == 1):
                update_option( INEXT_WPC_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED , 1 );
			else:
				update_option( INEXT_WPC_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED , 0 );
            endif;

            if(isset($_POST["_inext_wpc_pincode_field_length"])):
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LENGTH , sanitize_text_field($_POST["_inext_wpc_pincode_field_length"] ));
			endif;
        }

		$msg['status'] = 1;
		$msg['msg'] = 'Saved successfully';

		_e(json_encode($msg));
		wp_die();
	}

	public static function inext_wpc_save_settings_message(){
		$msg = [];

		if ( is_admin() ) {
            // Check if nonce is set
            if(!isset($_POST['inext_wpc_save_settings_message_nonce'])):
                return 0;
            endif;

        	if(!current_user_can('manage_options')):
                return 0;
            endif;

        	if(isset($_POST["_inext_wpc_pincode_field_label"])):
                update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL , sanitize_text_field($_POST["_inext_wpc_pincode_field_label"] ));
            endif;

			if(isset($_POST["_inext_wpc_pincode_field_label_is_enabled"]) && $_POST["_inext_wpc_pincode_field_label_is_enabled"] == 1):
                update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL_ENABLED , 1 );
			else:
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL_ENABLED , 0 );
            endif;

			if(isset($_POST["_inext_wpc_pincode_field_placeholder"])):
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER , sanitize_text_field($_POST["_inext_wpc_pincode_field_placeholder"] ));
			endif;

			if(isset($_POST["_inext_wpc_pincode_field_placeholder_is_enabled"]) && $_POST["_inext_wpc_pincode_field_placeholder_is_enabled"] == 1):
                update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER_ENABLED , 1 );
			else:
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER_ENABLED , 0 );
            endif;

			if(isset($_POST["_inext_wpc_pincode_field_button_text"])):
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BUTTON_TEXT , sanitize_text_field($_POST["_inext_wpc_pincode_field_button_text"] ));
			endif;

			if(isset($_POST["_inext_wpc_pincode_field_success"])):
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_SUCCESS , __(str_replace("\"", "\'", wp_unslash($_POST["_inext_wpc_pincode_field_success"])), 'inext-woo-pincode-checker' ));
			endif;

			if(isset($_POST["_inext_wpc_pincode_field_error"])):
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_ERROR , __(str_replace("\"", "\'", wp_unslash($_POST["_inext_wpc_pincode_field_error"])), 'inext-woo-pincode-checker' ));
			endif;

			if(isset($_POST["_inext_wpc_pincode_field_not_found"])):
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_FOUND , __(str_replace("\"", "\'", wp_unslash($_POST["_inext_wpc_pincode_field_not_found"])), 'inext-woo-pincode-checker' ));
			endif;

			if(isset($_POST["_inext_wpc_pincode_field_not_valid"])):
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_VALID , __(str_replace("\"", "\'", wp_unslash($_POST["_inext_wpc_pincode_field_not_valid"])), 'inext-woo-pincode-checker' ));
			endif;
			
			if(isset($_POST["_inext_wpc_pincode_field_max_length_error"])):
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_MAX_LENGTH_ERROR , sanitize_text_field($_POST["_inext_wpc_pincode_field_max_length_error"]) );
			endif;
			
			if(isset($_POST["_inext_wpc_pincode_field_min_length_error"])):
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_MIN_LENGTH_ERROR , sanitize_text_field($_POST["_inext_wpc_pincode_field_min_length_error"]) );
			endif;

			if(isset($_POST["_inext_wpc_pincode_field_blank"])):
				update_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BLANK , sanitize_text_field($_POST["_inext_wpc_pincode_field_blank"] ));
			endif;

        }

		$msg['status'] = 1;
		$msg['msg'] = 'Saved successfully';

		_e(json_encode($msg));
		wp_die();
	}
}
?>
