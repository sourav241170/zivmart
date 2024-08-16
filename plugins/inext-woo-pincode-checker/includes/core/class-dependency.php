<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_DEPENDENCY {
    private static $initiated = false;
    private static $active_plugins;

    public static function init() {
		if ( ! self::$initiated ) :
            self::$active_plugins = (array) get_option( 'active_plugins', array() );
        endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
	private static function dependency_hooks() {
		self::$initiated = true;
    }

    public static function check_woocommerce_plugin_is_active() {
		if ( ! self::$active_plugins ) :
            self::init();
        endif;

        if( in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins ) ) :
            return true;
        else:
            return false;
        endif;
	}
}

add_action( 'admin_init', array ( 'INEXT_WPC_DEPENDENCY', 'init') );

if( ! INEXT_WPC_DEPENDENCY::check_woocommerce_plugin_is_active() ) :
    $msg = sprintf(esc_html__( '%s%s%s requires woocommerce Plugin to be active. ' , 'inext-woo-pincode-checker'), '<strong>', INEXT_WPC_PLUGIN_NAME, '</strong>') . sprintf( __('Please install and activate the woocommerce plugin from <a href="%1$s">here</a>.',  'inext-woo-pincode-checker' ), esc_url( admin_url( 'plugin-install.php??tab=plugin-information&plugin=woocommerce&section=description&TB_iframe=true&width=772&height=583' ) ) );
    INEXT_WPC_NOTICE::notice('error', $msg);
else:
	global $wpdb, $table_prefix;
	$pin_codes = array();

	$shipping_zones = $wpdb->get_results("SELECT DISTINCT location_code FROM ". $table_prefix. "woocommerce_shipping_zone_locations WHERE location_type = 'postcode'");
	foreach($shipping_zones as $shipping_zone){
		foreach($shipping_zone as $k => $v){
			array_push($pin_codes, $v);
		}
	}
	if(empty($pin_codes)){
		$msg = sprintf( __( 'There are no shipping pincode available on your woocommerce store. <a href="%s">Click here</a> to add new one.' , 'inext-woo-pincode-checker'), esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping&zone_id=new' )) );
    	INEXT_WPC_NOTICE::notice('error', $msg);
	}
endif;
?>
