<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_ACTION {
    private static $initiated = false;

    public static function action() {
		if ( ! self::$initiated ) :
			self::action_hooks();
		endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
	private static function action_hooks() {
        self::$initiated = true;
    }


    /** Actions **/
    public static function inext_wpc_check_pin_code_action() {
        add_action( 'wp_ajax_inext_wpc_check_pin_code', array( 'INEXT_WPC_ACTION', 'inext_wpc_check_pin_code_callback' ) ); //will modify the classname to var
        add_action( 'wp_ajax_nopriv_inext_wpc_check_pin_code', array( 'INEXT_WPC_ACTION', 'inext_wpc_check_pin_code_callback' ) ); //will modify the classname to var
    }

    /** Callbacks **/
    public static function inext_wpc_check_pin_code_callback() {
        INEXT_WPC_AJAX::inext_wpc_check_pin_code();
    }
}
?>
