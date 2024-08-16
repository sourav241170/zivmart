<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_ADMIN_ASSETS {
    private static $initiated = false;
    private static $inext_wpc_css = '';
    private static $inext_wpc_js = '';
    private static $inext_wpc_plugins = '';
    private static $v  = '';

    public static function init() {
		if ( ! self::$initiated ) :
            $inext_wpc_css = plugins_url( '/', __FILE__ ) . 'admin/css';
            $inext_wpc_js = plugins_url( '/', __FILE__ ) . 'admin/js';
            $inext_wpc_plugins = plugins_url( '/', __FILE__ ) . 'admin/plugins';
            $v  = '1.0.0';

			self::styles_hooks();
        endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
	private static function styles_hooks() {
        self::$initiated = true;
    }

    /** Actions **/
    public static function inext_wpc_admin_assets_action() {
        add_action( 'admin_enqueue_scripts', array( 'INEXT_WPC_ADMIN_ASSETS', 'inext_wpc_admin_assets_action_callback' ) );
    }

    /** Callbacks **/
    public static function inext_wpc_admin_assets_action_callback() {
        $inext_wpc_css = plugins_url( '/', __FILE__ ) . 'admin/css'; //will add globally
        $inext_wpc_js = plugins_url( '/', __FILE__ ) . 'admin/js'; //will add globally
        $inext_wpc_plugins = plugins_url( '/', __FILE__ ) . 'admin/plugins'; //will add globally

        wp_enqueue_style( 'inext-wpc-fontawesome-style', $inext_wpc_plugins . '/fontawesome/css/all.min.css', array(), null );
        wp_enqueue_style( 'inext-wpc-admin-style', $inext_wpc_css . '/styles.css', array(), null );

		wp_enqueue_script( 'inext-wpc-popper-script', $inext_wpc_js . '/popper.min.js', array( 'jquery' ), null, true );
        wp_enqueue_script( 'inext-wpc-bootstrap-script', $inext_wpc_js . '/bootstrap.min.js', array( 'jquery' ), null, true );
        wp_enqueue_script( 'inext-wpc-fontawesome-script', $inext_wpc_plugins . '/fontawesome/js/all.min.js', array( 'jquery' ), null, true );
        wp_enqueue_script( 'inext-wpc-inext-alert-script', $inext_wpc_plugins . '/inext/js/inext-alert.js', array( 'jquery' ), null, true );
        wp_enqueue_script( 'inext-wpc-admin-script', $inext_wpc_js . '/scripts.js', array( 'jquery' ), null, true );
		wp_localize_script('inext-wpc-admin-script', 'inext_wpc_admin_ajax_variables',
			[
				'adminajaxurl' => admin_url( 'admin-ajax.php' )
			]
		);
    }
}

INEXT_WPC_ADMIN_ASSETS::init();

// will add condition // recommended
INEXT_WPC_ADMIN_ASSETS::inext_wpc_admin_assets_action();
INEXT_WPC_ADMIN_ACTION::inext_wpc_save_settings_general_action();
INEXT_WPC_ADMIN_ACTION::inext_wpc_save_settings_message_action();
?>
