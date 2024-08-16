<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_JS_VARIABLES {
    private static $initiated = false;

    public static function js_variables() {
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
    public static function inext_wpc_js_variables_action() {
        if ( is_admin() ):
            add_action( 'admin_head', array( 'INEXT_WPC_JS_VARIABLES', 'inext_wpc_admin_js_variables_action_callback' ) ); //will modify the classname to var
        else:
            add_action( 'wp_head', array( 'INEXT_WPC_JS_VARIABLES', 'inext_wpc_js_variables_action_callback' ) ); //will modify the classname to var
        endif;
    }

    /** Callbacks **/
    public static function inext_wpc_js_variables_action_callback() {
        $js_variables =
            '<script type="text/javascript">'.
                'var inext_wpc_plugin_enabled = "'. get_option( INEXT_WPC_PLUGIN_ENABLED ) .'";'.
                'var loader_wrapper_class = "'. INEXT_WPC_PLUGIN_LOADER_WRAPPER_CLASS .'";'.
                'var loader_class = "'. INEXT_WPC_PLUGIN_LOADER_CLASS .'";'.
                'var loader = "'. INEXT_WPC_PLUGIN_LOADER .'";'.
                'var hide_single_product_atc_btn = "'. get_option( INEXT_WPC_PLUGIN_SINGLE_PRODUCT_ATC_BTN_DISABLED ) .'";'.
                'var single_product_atc_btn = "'. INEXT_WPC_PLUGIN_SINGLE_PRODUCT_ATC_BTN .'";'.
                'var pin_code_alphanumeric = "'. get_option( INEXT_WPC_PLUGIN_PINCODE_ALPHANUMERIC_ENABLED ) .'";'.
                'var pin_code_length = "'. get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LENGTH ) .'";'.
                'var pin_code_success = "'. get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_SUCCESS ) .'";'.
                'var pin_code_error = "'. get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_ERROR ) .'";'.
                'var pin_code_not_found = "'. get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_FOUND ) .'";'.
                'var pin_code_not_valid = "'. get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_VALID ) .'";'.
				'var pin_code_min_length_error = "'. get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_MIN_LENGTH_ERROR ) .'";'.
				'var pin_code_max_length_error = "'. get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_MAX_LENGTH_ERROR ) .'";'.
                'var pin_code_blank = "'. get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BLANK ) .'";'.
            '</script>';

        _e($js_variables, 'inext-woo-pincode-checker');
    }

    public static function inext_wpc_admin_js_variables_action_callback() {
        $js_variables =
            '<script type="text/javascript">'.
                'var loader_wrapper_class = "'. INEXT_WPC_PLUGIN_LOADER_WRAPPER_CLASS .'";'.
                'var loader_class = "'. INEXT_WPC_PLUGIN_LOADER_CLASS .'";'.
                'var loader = "'. INEXT_WPC_PLUGIN_LOADER .'";'.
            '</script>';

        _e($js_variables, 'inext-woo-pincode-checker');
    }
}
?>
