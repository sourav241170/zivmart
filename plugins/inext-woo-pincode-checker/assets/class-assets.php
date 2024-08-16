<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_ASSETS {
    private static $initiated = false;
    private static $inext_wpc_css = '';
    private static $inext_wpc_js = '';
    private static $v  = '';

    public static function init() {
		if ( ! self::$initiated ) :
            $inext_wpc_css = plugins_url( '/', __FILE__ ) . 'frontend/css';
            $inext_wpc_js = plugins_url( '/', __FILE__ ) . 'frontend/js';
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
    public static function inext_wpc_single_product_assets() {
        add_action( 'wp_enqueue_scripts', array( 'INEXT_WPC_ASSETS', 'inext_wpc_single_product_assets_callback' ) );
    }

    /** Callbacks **/
    public static function inext_wpc_single_product_assets_callback() {
        $inext_wpc_css = plugins_url( '/', __FILE__ ) . 'frontend/css'; //will add globally
        $inext_wpc_js = plugins_url( '/', __FILE__ ) . 'frontend/js'; //will add globally

        wp_enqueue_style( 'inext-wpc-style', $inext_wpc_css . '/styles.css', array(), null );

		wp_enqueue_script( 'inext-wpc-script', $inext_wpc_js . '/scripts.js', array( 'jquery' ), null, true );
		wp_localize_script('inext-wpc-script', 'inext_wpc_ajax_variables',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			]
		);
    }
}

INEXT_WPC_ASSETS::init();

// will add condition
// single product
INEXT_WPC_ASSETS::inext_wpc_single_product_assets();
INEXT_WPC_ACTION::inext_wpc_check_pin_code_action();
?>
