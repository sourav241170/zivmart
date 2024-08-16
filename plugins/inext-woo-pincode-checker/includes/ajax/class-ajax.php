<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_AJAX {
	public static function inext_wpc_check_pin_code(){
		global $wpdb, $table_prefix;
		$pin_codes = array();

		$shipping_zones = $wpdb->get_results("SELECT DISTINCT location_code FROM ". $table_prefix. "woocommerce_shipping_zone_locations WHERE location_type = 'postcode'");
		foreach($shipping_zones as $shipping_zone){
			foreach($shipping_zone as $k => $v){
				array_push($pin_codes, $v);
			}
		}

		if($_POST['pin_code_value'] == ''){
			$msg['status'] = 0;
			$msg['msg'] = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BLANK );
		}
		else{
			if(in_array($_POST['pin_code_value'], $pin_codes)){
				$msg['status'] = 1;
				$msg['msg'] = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_SUCCESS );
			}
			else{
				$msg['status'] = 0;
				$msg['msg'] = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_ERROR );
			}
		}
		_e(json_encode($msg));
		wp_die();
	}
}
?>
