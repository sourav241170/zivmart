<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_ADMIN_ACTION {
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
    public static function inext_wpc_save_settings_general_action() {
        add_action( 'wp_ajax_inext_wpc_save_settings_general', array( 'INEXT_WPC_ADMIN_ACTION', 'inext_wpc_save_settings_general_callback' ) );
    }

    public static function inext_wpc_save_settings_message_action() {
        add_action( 'wp_ajax_inext_wpc_save_settings_message', array( 'INEXT_WPC_ADMIN_ACTION', 'inext_wpc_save_settings_message_callback' ) );
    }

    /** Callbacks **/
    public static function inext_wpc_save_settings_general_callback() {
        INEXT_WPC_ADMIN_AJAX::inext_wpc_save_settings_general();
    }

    public static function inext_wpc_save_settings_message_callback() {
        INEXT_WPC_ADMIN_AJAX::inext_wpc_save_settings_message();
    }
}
?>
