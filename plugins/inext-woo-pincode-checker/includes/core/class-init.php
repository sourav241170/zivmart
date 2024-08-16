<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_INIT {
    private static $initiated = false;

    public static function init() {
		if ( ! self::$initiated ) :
			self::init_hooks();
		endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
    private static function init_hooks() {
        self::$initiated = true;
    }

    /** Actions **/
    public static function inext_wpc_init_action() {
        add_action( 'admin_init', array ( 'INEXT_WPC_INIT', 'inext_wpc_init_action_callback') );
    }

	public static function inext_wpc_plugin_links_action() {
		add_filter( 'plugin_action_links_' . INEXT_WPC_PLUGIN_BASENAME, array ( 'INEXT_WPC_INIT', 'inext_wpc_plugin_links_callback') );
    }

    public static function inext_wpc_plugin_row_meta_action() {
		add_filter( 'plugin_row_meta', array ( 'INEXT_WPC_INIT', 'inext_wpc_plugin_row_meta_callback'), 10, 4 );
    }

    /** Callbacks **/
	public static function inext_wpc_init_action_callback() {
		if ( version_compare( $GLOBALS['wp_version'], INEXT_WPC_PLUGIN_MIN_WP_VERSION, '<' ) ) :
			$msg = '<strong>'.sprintf(esc_html__( '%s %s requires WordPress %s or higher.' , 'inext-woo-pincode-checker'), INEXT_WPC_PLUGIN_NAME, INEXT_WPC_PLUGIN_VERSION, INEXT_WPC_PLUGIN_MIN_WP_VERSION ).'</strong><br> '.sprintf(__('Please <a href="%1$s">upgrade</a> WordPress to a latest version, or <a href="%2$s">downgrade</a>  to version %3$s of the %4$s plugin.',  'inext-woo-pincode-checker' ), 'https://codex.wordpress.org/Upgrading_WordPress', 'https://wordpress.org/extend/plugins/inxet/download/', INEXT_WPC_PLUGIN_PREVIOUS_VERSION, INEXT_WPC_PLUGIN_NAME);
            INEXT_WPC_NOTICE::notice('error', $msg);
        endif;
    }

    public static function inext_wpc_init_variables_action() {
    }

	public static function inext_wpc_plugin_links_callback($links) {
		$links[] = '<a href="' . admin_url('admin.php?page=inext-wpc-settings') . '">' . __('Settings', 'inext-woo-pincode-checker') . '</a>';
		return $links;
	}

	public static function inext_wpc_plugin_row_meta_callback($links, $plugin_file_name, $plugin_data, $status) {
		if($plugin_file_name == INEXT_WPC_PLUGIN_BASENAME){
			$links[] = '<a href="https://wordpress.org/support/plugin/inext-woo-pincode-checker" target="_blank">' . __('<strong>Get Support</strong>', 'inext-woo-pincode-checker') . '</a>';
			$links[] = '<a href="https://plugins.imdadnextweb.com" target="_blank">' . __('<strong>Demo</strong>', 'inext-woo-pincode-checker') . '</a>';
		}
		return $links;
	}
}

INEXT_WPC_INIT::inext_wpc_init_action();
INEXT_WPC_INIT::inext_wpc_plugin_links_action();
INEXT_WPC_INIT::inext_wpc_plugin_row_meta_action();
// INEXT_WPC_INIT::inext_wpc_init_variables_action(); // will work soon
?>
