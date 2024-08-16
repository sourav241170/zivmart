<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_BASIC_VIEWS {
    private static $initiated = false;

    public static function single_product() {
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
    public static function inext_wpc_after_add_to_cart_button_action() {
        INEXT_WPC_BASIC_VIEWS::inext_wpc_html_callback();
    }

    public static function inext_wpc_after_cart_totals_shipping_action() {
        INEXT_WPC_BASIC_VIEWS::inext_wpc_html_callback();
    }

    public static function inext_wpc_after_review_order_shipping_action() {
        INEXT_WPC_BASIC_VIEWS::inext_wpc_html_callback();
    }

    public static function inext_wpc_shortcode_action() {
        add_shortcode('inext_wpc', array ( 'INEXT_WPC_BASIC_VIEWS', 'inext_wpc_shortcode_action_callback'));
    }

    public static function inext_wpc_shortcode_action_callback($atts, $content = null) {
        $default = array(
        );
        $a = shortcode_atts($default, $atts);
        $content = do_shortcode($content);
    
        return INEXT_WPC_BASIC_VIEWS::inext_wpc_html_callback(1);
    }

    public static function inext_wpc_html_callback($return=0) {
        global $woocommerce;

        $pincheck_wrapper = '';

        $pincheck_wrapper .= '<div class="inext_wpc_wrapper pincheck_wrapper" id="pincheck_wrapper">';

        $pincheck_wrapper .= '<div class="pincheck_inner">';

        if(get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL_ENABLED )):
            $pincheck_wrapper .= '<p id="pin_label" class="pin_label">'. esc_html( get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL ) ) .'</p>';
        endif;

        $pincheck_wrapper .= '<div class="form_inline">';

        $pincheck_wrapper .= '<div class="form_input_group">';

		$pincheck_wrapper .= '<img class="pin_marker" src="'. esc_url(INEXT_WPC_PLUGIN_ASSETS . '/frontend/img/location-marker.png', 'inext-woo-pincode-checker') .'">';

        if(get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER_ENABLED )):
            if(get_option( INEXT_WPC_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED )):
                if( WC()->customer->get_billing_postcode() ):
                    $pincheck_wrapper .= '<input class="pin_code form-control input-text" id="pin_code" name="pin_code" placeholder="'. esc_attr( get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER ) ) .'" value="'. esc_attr( WC()->customer->get_billing_postcode() ) .'">';
                else:
                    $pincheck_wrapper .= '<input class="pin_code form-control input-text" id="pin_code" name="pin_code" placeholder="'. esc_attr( get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER ) ) .'" value="'. esc_attr( WC()->customer->get_shipping_postcode() ) .'">';
                endif;
            else:
                $pincheck_wrapper .= '<input class="pin_code form-control input-text" id="pin_code" name="pin_code" placeholder="'. esc_attr( get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER ) ) .'" value="">';
            endif;
        else:
            if(get_option( INEXT_WPC_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED )):
                $pincheck_wrapper .= '<input class="pin_code form-control input-text" id="pin_code" name="pin_code" placeholder="" value="'. esc_attr( WC()->customer->get_shipping_postcode() ) .'">';
            else:
                $pincheck_wrapper .= '<input class="pin_code form-control input-text" id="pin_code" name="pin_code" placeholder="" value="">';
            endif;
        endif;

		$pincheck_wrapper .= '</div>';

        if(get_option( INEXT_WPC_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED ) && WC()->customer->get_shipping_postcode() != ''):
            $pincheck_wrapper .= '<button type="button" class="btn button" id="check_pin">'. esc_html( get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BUTTON_TEXT ) ) .'</button>';
        else:
            $pincheck_wrapper .= '<button type="button" class="btn button disabled" id="check_pin">'. esc_html( get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BUTTON_TEXT ) ) .'</button>';
        endif;

        $pincheck_wrapper .= '</div>';

        $pincheck_wrapper .= '<p id="pin_res" class="res text_small"></p>';

        $pincheck_wrapper .= '</div>';

        $pincheck_wrapper .= '</div>';

        if($return){
            return $pincheck_wrapper;
        }
        else{
            _e($pincheck_wrapper, 'inext-woo-pincode-checker');
        }
    }
}
?>
