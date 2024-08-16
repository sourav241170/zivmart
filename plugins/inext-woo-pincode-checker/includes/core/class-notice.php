<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_NOTICE {
    private static $initiated = false;
    private static $_type = '';
    private static $_msg = '';


    public static function notice($type, $msg) {
		if ( ! self::$initiated ) :
            self::$_type = $type;
            self::$_msg = $msg;

			self::notice_hooks();
			self::inext_wpc_notice_action();
		endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
    private static function notice_hooks() {
        self::$initiated = true;
    }

    /** Actions **/
    public static function inext_wpc_notice_action() {
        add_action( 'admin_notices', array( 'INEXT_WPC_NOTICE', 'inext_wpc_notice_action_callback' ) ); //will modify the classname to var
    }

    /** Callbacks **/
    public static function inext_wpc_notice_action_callback() {
        _e('<div class="notice notice-'. self::$_type .' is-dismissible"><p>'. self::$_msg .'</p></div>', 'inext-woo-pincode-checker');
    }
}
?>
