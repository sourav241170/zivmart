<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_ADMIN_MENU {
    private static $initiated = false;

    public static function admin_menu() {
		if ( ! self::$initiated ) :
			self::admin_menu_hooks();
		endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
	private static function admin_menu_hooks() {
        self::$initiated = true;
    }

	/** Actions **/
    public static function inext_wpc_admin_menu_action() { // can not same as class
		add_action( 'admin_menu', array( 'INEXT_WPC_ADMIN_MENU', 'inext_wpc_admin_menu_action_callback' ) ); //will modify the classname to var
    }

    /** Callbacks **/
    public static function inext_wpc_admin_menu_action_callback() {
        add_menu_page(
    		$page_title = INEXT_WPC_PLUGIN_SHORT_NAME,
    		$menu_title = INEXT_WPC_PLUGIN_SHORT_NAME,
    		$capability = 'manage_options',
    		$menu_slug = INEXT_WPC_PLUGIN_SLUG .'-welcome',
    		$callback = array( 'INEXT_WPC_ADMIN_MENU', INEXT_WPC_PLUGIN_SLUG__ .'_welcome' ),
    		$icon_url = 'dashicons-location',
    		$position = 10 //position w.r.t other sidebar menu
    	);

        add_submenu_page( // first sub menu with same params as menu prevent to add main menu in submenu
            $parent_slug = INEXT_WPC_PLUGIN_SLUG .'-welcome',
            $page_title = 'Welcome',
    		$menu_title = 'Welcome',
    		$capability = 'manage_options',
    		$menu_slug = INEXT_WPC_PLUGIN_SLUG .'-welcome',
    		$callback = array( 'INEXT_WPC_ADMIN_MENU', INEXT_WPC_PLUGIN_SLUG__ .'_welcome' ),
    		$position = 0
        );

        add_submenu_page(
            $parent_slug = INEXT_WPC_PLUGIN_SLUG .'-welcome',
            $page_title = 'Settings',
    		$menu_title = 'Settings',
    		$capability = 'manage_options',
    		$menu_slug = INEXT_WPC_PLUGIN_SLUG .'-settings',
    		$callback = array( 'INEXT_WPC_ADMIN_MENU', INEXT_WPC_PLUGIN_SLUG__ .'_settings' ),
    		$position = 2
        );
    }

    public static function inext_wpc_welcome() {
    	require_once INEXT_WPC_PLUGIN_NAME_DIR . 'includes/admin/menus/welcome.php';
    }

    public static function inext_wpc_settings() {
    	require_once INEXT_WPC_PLUGIN_NAME_DIR . 'includes/admin/menus/settings.php';
    }
}

INEXT_WPC_ADMIN_MENU::inext_wpc_admin_menu_action();
?>
