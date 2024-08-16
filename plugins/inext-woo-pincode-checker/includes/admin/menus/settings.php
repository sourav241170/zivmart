<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_ADMIN_MENU_SETTINGS {
    private static $initiated = false;

    public static function settings() {
		if ( ! self::$initiated ) :
			self::views_settings();
		endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
	private static function views_settings() {
        self::$initiated = true;
    }

    /** Actions **/
    public static function inext_wpc_admin_menu_settings_action() {
        INEXT_WPC_ADMIN_MENU_SETTINGS::inext_wpc_html_callback();
    }


	/** Callbacks **/
	public static function inext_wpc_html_callback() {
		$inext_wpc_is_enabled = get_option( INEXT_WPC_PLUGIN_ENABLED );
		$inext_wpc_is_product_enabled = get_option( INEXT_WPC_PLUGIN_PRODUCT_ENABLED );
		$inext_wpc_is_cart_enabled = get_option( INEXT_WPC_PLUGIN_CART_ENABLED );
		$inext_wpc_is_checkout_enabled = get_option( INEXT_WPC_PLUGIN_CHECKOUT_ENABLED );
		$inext_wpc_is_single_product_atc_btn_disabled = get_option( INEXT_WPC_PLUGIN_SINGLE_PRODUCT_ATC_BTN_DISABLED );
		$inext_wpc_is_pincode_alphanumric_enabled = get_option( INEXT_WPC_PLUGIN_PINCODE_ALPHANUMERIC_ENABLED );

		$inext_wpc_pincode_field_label_is_enabled = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL_ENABLED );
		$inext_wpc_pincode_field_label = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL );
		$inext_wpc_pincode_field_placeholder_is_enabled = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER_ENABLED );
		$inext_wpc_pincode_field_placeholder = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER );
		$inext_wpc_pincode_user_shipping_fetch_is_enabled = get_option( INEXT_WPC_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED );
		$inext_wpc_pincode_field_button_text = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BUTTON_TEXT );
		$inext_wpc_pincode_field_length = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_LENGTH );
		$inext_wpc_pincode_field_success = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_SUCCESS );
		$inext_wpc_pincode_field_error = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_ERROR );
		$inext_wpc_pincode_field_not_found = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_FOUND );
		$inext_wpc_pincode_field_not_valid = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_VALID );
		$inext_wpc_pincode_field_max_length_error = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_MAX_LENGTH_ERROR );
		$inext_wpc_pincode_field_min_length_error = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_MIN_LENGTH_ERROR );
		$inext_wpc_pincode_field_blank = get_option( INEXT_WPC_PLUGIN_PINCODE_FIELD_BLANK );

		$html = '';

		$html .=
			'<div class="wrap inext_wpc_wrapper wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">'.
				'<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl compress-card">';

				$html .=
					'<div class="content flex-row-fluid" id="kt_content">'.
						'<div class="flex-lg-row-fluid">';

						$html .=
							'<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8" id="settings_tab">'.
								'<li class="nav-item">'.
									'<a class="nav-link text-active-primary pb-4 active" data-toggle="tab" data-parent="settings_tab" data-target="settings_general" href="javascript:void(0)">'.
										'<i class="fas fa-home"></i> '. esc_html('General', 'inext-woo-pincode-checker') .
									'</a>'.
								'</li>'.
								'<li class="nav-item">'.
									'<a class="nav-link text-active-primary pb-4" data-toggle="tab" data-parent="settings_tab" data-target="settings_store" href="javascript:void(0)">'.
									'<span class="svg-icon svg-icon-2 me-2">'.
										'<i class="fas fa-spell-check"></i> '. esc_html('Message', 'inext-woo-pincode-checker') .
									'</a>'.
								'</li>'.
							'</ul>';

							$html .=
							'<div class="tab-content" id="settings_tab_content">';

							$html .=
								'<div class="tab-pane fade active show" id="settings_general" role="tabpanel">'.
									'<div class="card bg-white">'.
										'<div class="card-body pt-0">'.
											'<div class="fs-6 text-muted mt-5">If you are find our plugin helpful, please drop a review <a href="https://wordpress.org/plugins/inext-woo-pincode-checker/#reviews" class="fw-bold" target="_blank">here</a><br> For any kind of query, <a href="https://wordpress.org/support/plugin/inext-woo-pincode-checker" class="fw-bold" target="_blank">raise a ticket</a></div>'.
											'<div class="fs-6 text-muted mt-5">If you want to embed the pincode checker in any page please use <strong><code>[inext_wpc/]</code></strong> shortcode.</div>'.
											'<form id="'. esc_attr(INEXT_WPC_PLUGIN_SLUG__ ."_save_settings_general_form") .'" class="" action="" method="post">'.
												'<input type="hidden" name="inext_wpc_save_settings_general_nonce" value="'. esc_attr(wp_create_nonce()) .'" />'.
												'<input type="hidden" name="action" value="'. esc_attr(INEXT_WPC_PLUGIN_SLUG__ ."_save_settings_general") .'">'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Enable / Disable the plugin', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enable or disable the plugin. All the settings will stop work with one click."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<div class="d-flex mt-3">'.
															'<div class="form-check form-switch form-check-custom form-check-solid">'.
																'<input class="form-check-input" type="checkbox" value="1" id="'. esc_attr(INEXT_WPC_PLUGIN_ENABLED) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_ENABLED) .'" '. esc_attr((isset($inext_wpc_is_enabled) && $inext_wpc_is_enabled == 1) ? 'checked' : '') .' />'.
																'<label class="form-check-label" for="'. esc_attr(INEXT_WPC_PLUGIN_ENABLED) .'">'.

																'</label>'.
															'</div>'.
														'</div>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Display on product page?', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enable this if you want to display the pincode checker on product details page after add to cart."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<div class="d-flex mt-3">'.
															'<div class="form-check form-switch form-check-custom form-check-solid">'.
																'<input class="form-check-input" type="checkbox" value="1" id="'. esc_attr(INEXT_WPC_PLUGIN_PRODUCT_ENABLED) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PRODUCT_ENABLED) .'" '. esc_attr((isset($inext_wpc_is_product_enabled) && $inext_wpc_is_product_enabled == 1) ? 'checked' : '') .' />'.
																'<label class="form-check-label" for="'. esc_attr(INEXT_WPC_PLUGIN_PRODUCT_ENABLED) .'">'.

																'</label>'.
															'</div>'.
														'</div>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Display on cart page?', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enable this if you want to display the pincode checker on cart page before total order."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<div class="d-flex mt-3">'.
															'<div class="form-check form-switch form-check-custom form-check-solid">'.
																'<input class="form-check-input" type="checkbox" value="1" id="'. esc_attr(INEXT_WPC_PLUGIN_CART_ENABLED) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_CART_ENABLED) .'" '. esc_attr((isset($inext_wpc_is_cart_enabled) && $inext_wpc_is_cart_enabled == 1) ? 'checked' : '') .' />'.
																'<label class="form-check-label" for="'. esc_attr(INEXT_WPC_PLUGIN_CART_ENABLED) .'">'.

																'</label>'.
															'</div>'.
														'</div>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Display on checkout page?', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enable this if you want to display the pincode checker on checkout page before order details."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<div class="d-flex mt-3">'.
															'<div class="form-check form-switch form-check-custom form-check-solid">'.
																'<input class="form-check-input" type="checkbox" value="1" id="'. esc_attr(INEXT_WPC_PLUGIN_CHECKOUT_ENABLED) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_CHECKOUT_ENABLED) .'" '. esc_attr((isset($inext_wpc_is_checkout_enabled) && $inext_wpc_is_checkout_enabled == 1) ? 'checked' : '') .' />'.
																'<label class="form-check-label" for="'. esc_attr(INEXT_WPC_PLUGIN_CHECKOUT_ENABLED) .'">'.

																'</label>'.
															'</div>'.
														'</div>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Hide Add To Cart button on product page?', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enable this if you want to hide the add to cart button on product details page when the pincode is not serviceable."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<div class="d-flex mt-3">'.
															'<div class="form-check form-switch form-check-custom form-check-solid">'.
																'<input class="form-check-input" type="checkbox" value="1" id="'. esc_attr(INEXT_WPC_PLUGIN_SINGLE_PRODUCT_ATC_BTN_DISABLED) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_SINGLE_PRODUCT_ATC_BTN_DISABLED) .'" '. esc_attr((isset($inext_wpc_is_single_product_atc_btn_disabled) && $inext_wpc_is_single_product_atc_btn_disabled == 1) ? 'checked' : '') .' />'.
																'<label class="form-check-label" for="'. esc_attr(INEXT_WPC_PLUGIN_SINGLE_PRODUCT_ATC_BTN_DISABLED) .'">'.

																'</label>'.
															'</div>'.
														'</div>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Fetch customer billing or shipping pincode?', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enable this if you want to automatically fetch customer billing pincode instead of entering the pincode every time. If billing pincde will not found, customer\'s shipping pincode will used."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<div class="d-flex mt-3">'.
															'<div class="form-check form-switch form-check-custom form-check-solid">'.
																'<input class="form-check-input" type="checkbox" value="1" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_USER_SHIPPING_FETCH_ENABLED) .'" '. esc_attr((isset($inext_wpc_pincode_user_shipping_fetch_is_enabled) && $inext_wpc_pincode_user_shipping_fetch_is_enabled == 1) ? 'checked' : '') .' />'.
															'</div>'.
														'</div>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Pincode Length', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Set a pincode length as per your locality"></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="number" class="form-control max-w-100px" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_LENGTH) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_LENGTH) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_length) && $inext_wpc_pincode_field_length) ? $inext_wpc_pincode_field_length : '') .'">'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Enable Alphanumeric Pincode?', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enable this if you want to check alphanumeric type pincodes e.g. T3SFR4"></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<div class="d-flex mt-3">'.
															'<div class="form-check form-switch form-check-custom form-check-solid">'.
																'<input class="form-check-input" type="checkbox" value="1" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_ALPHANUMERIC_ENABLED) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_ALPHANUMERIC_ENABLED) .'" '. esc_attr((isset($inext_wpc_is_pincode_alphanumric_enabled) && $inext_wpc_is_pincode_alphanumric_enabled == 1) ? 'checked' : '') .' />'.
															'</div>'.
														'</div>'.
													'</div>'.
												'</div>'.

												'<div class="row py-7">'.
													'<div class="col">'.
														'<div class="d-flex justify-content-end">'.
															'<button type="reset" class="btn btn-light me-5">'. esc_html('Cancel', 'inext-woo-pincode-checker') .'</button>'.
															'<button type="submit" class="btn btn-primary submit">'. esc_html('Save', 'inext-woo-pincode-checker') .'</button>'.
														'</div>'.
													'</div>'.
												'</div>'.
											'</form>'.
										'</div>'.
									'</div>'.
								'</div>';

							$html .=
								'<div class="tab-pane fade" id="settings_store" role="tabpanel">'.
									'<div class="card bg-white">'.
										'<div class="card-body pt-0">'.
											'<div class="fs-6 text-muted mt-5">If you are find our plugin helpful, please drop a review <a href="https://wordpress.org/plugins/inext-woo-pincode-checker/#reviews" class="fw-bold" target="_blank">here</a><br> For any kind of query, <a href="https://wordpress.org/support/plugin/inext-woo-pincode-checker" class="fw-bold" target="_blank">raise a ticket</a></div>'.
											'<div class="fs-6 text-muted mt-5">If you want to embed the pincode checker in any page please use <strong><code>[inext_wpc/]</code></strong> shortcode.</div>'.
											'<form id="'. esc_attr(INEXT_WPC_PLUGIN_SLUG__ ."_save_settings_message_form") .'" class="" action="" method="post">'.
												'<input type="hidden" name="inext_wpc_save_settings_message_nonce" value="'. esc_attr(wp_create_nonce()) .'" />'.
												'<input type="hidden" name="action" value="'. esc_attr(INEXT_WPC_PLUGIN_SLUG__ ."_save_settings_message") .'">'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Pincode Field Label', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enter a custom label for pincode field so that customer have to know how to use it. Enable it if the label is required."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="text" class="form-control" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_label) && $inext_wpc_pincode_field_label) ? $inext_wpc_pincode_field_label : '') .'">'.
													'</div>'.
													'<div class="col">'.
														'<div class="d-flex mt-3">'.
															'<div class="form-check form-switch form-check-custom form-check-solid">'.
															   '<input class="form-check-input" type="checkbox" value="1" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL_ENABLED) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL_ENABLED) .'" '. esc_attr((isset($inext_wpc_pincode_field_label_is_enabled) && $inext_wpc_pincode_field_label_is_enabled == 1) ? 'checked' : '') .' />'.
															'</div>'.
														'</div>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Pincode Field Placeholder', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enter a custom placeholder for pincode field. Enable it if the placeholder is required."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="text" class="form-control" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_placeholder) && $inext_wpc_pincode_field_placeholder) ? $inext_wpc_pincode_field_placeholder : '') .'">'.
													'</div>'.
													'<div class="col">'.
														'<div class="d-flex mt-3">'.
															'<div class="form-check form-switch form-check-custom form-check-solid">'.
																'<input class="form-check-input" type="checkbox" value="1" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_LABEL_ENABLED) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_PLACEHOLDER_ENABLED) .'" '. esc_attr((isset($inext_wpc_pincode_field_placeholder_is_enabled) && $inext_wpc_pincode_field_placeholder_is_enabled == 1) ? 'checked' : '') .' />'.
															'</div>'.
														'</div>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Button Text', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enter a custom text for check pincode button."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="text" class="form-control" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_BUTTON_TEXT) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_BUTTON_TEXT) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_button_text) && $inext_wpc_pincode_field_button_text) ? $inext_wpc_pincode_field_button_text : '') .'">'.
													'</div>'.
													'<div class="col">'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Success Message', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enter a text as success message when the pincode is serviceable."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="text" class="form-control" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_SUCCESS) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_SUCCESS) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_success) && $inext_wpc_pincode_field_success) ? $inext_wpc_pincode_field_success : '') .'">'.
													'</div>'.
													'<div class="col">'.
														'<p class="fs-8 mt-3">'.
															'<span>'. esc_html('e.g We will deliver here ! <strong>WOW</strong>', 'inext-woo-pincode-checker') .'</span>'.
														'</p>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Error Message', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enter a text as error message when the pincode is not serviceable."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="text" class="form-control" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_ERROR) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_ERROR) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_error) && $inext_wpc_pincode_field_error) ? $inext_wpc_pincode_field_error : '') .'">'.
													'</div>'.
													'<div class="col">'.
														'<p class="fs-8 mt-3">'.
															'<span>'. esc_html('e.g We will not deliver ! <a href="/contact">Talk to us</a>', 'inext-woo-pincode-checker') .'</span>'.
														'</p>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Pincode Not Found Message', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enter a text as not found message when no pincode is available on shipping zone."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="text" class="form-control" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_FOUND) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_FOUND) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_not_found) && $inext_wpc_pincode_field_not_found) ? $inext_wpc_pincode_field_not_found : '') .'">'.
													'</div>'.
													'<div class="col">'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Pincode Not Valid Message', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enter a text as invalid message when the pincode is not valid."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="text" class="form-control" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_VALID) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_NOT_VALID) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_not_valid) && $inext_wpc_pincode_field_not_valid) ? $inext_wpc_pincode_field_not_valid : '') .'">'.
													'</div>'.
													'<div class="col">'.
													'</div>'.
												'</div>'.
												
												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Pincode Maximum Length Error Message', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enter a text as error message when the pincode length higher than minimum limit."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="text" class="form-control" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_MAX_LENGTH_ERROR) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_MAX_LENGTH_ERROR) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_max_length_error) && $inext_wpc_pincode_field_max_length_error) ? $inext_wpc_pincode_field_max_length_error : '') .'">'.
													'</div>'.
													'<div class="col">'.
														'<p class="fs-8 mt-3">'.
															'<span>'. esc_html('e.g Please enter maximum {length} digits', 'inext-woo-pincode-checker') .'</span>'.
														'</p>'.
													'</div>'.
												'</div>'.
												
												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Pincode Minimum Length Error Message', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enter a text as error message when the pincode length lower than maximum limit"></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="text" class="form-control" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_MIN_LENGTH_ERROR) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_MIN_LENGTH_ERROR) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_min_length_error) && $inext_wpc_pincode_field_min_length_error) ? $inext_wpc_pincode_field_min_length_error : '') .'">'.
													'</div>'.
													'<div class="col">'.
														'<p class="fs-8 mt-3">'.
															'<span>'. esc_html('e.g Please enter minimum {length} digits', 'inext-woo-pincode-checker') .'</span>'.
														'</p>'.
													'</div>'.
												'</div>'.

												'<div class="row border-bottom py-7">'.
													'<div class="col">'.
														'<label class="fs-6 fw-bold form-label mt-3">'.
															'<span>'. esc_html('Pincode Field Required Message', 'inext-woo-pincode-checker') .'</span>'.
															'<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" data-bs-original-title="Enter a text as required message when the pincode field is empty/blank while checking."></i>'.
														'</label>'.
													'</div>'.
													'<div class="col">'.
														'<input type="text" class="form-control" placeholder="" id="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_BLANK) .'" name="'. esc_attr(INEXT_WPC_PLUGIN_PINCODE_FIELD_BLANK) .'" value="'. esc_attr((isset($inext_wpc_pincode_field_blank) && $inext_wpc_pincode_field_blank) ? $inext_wpc_pincode_field_blank : '') .'">'.
													'</div>'.
													'<div class="col">'.
													'</div>'.
												'</div>'.

												'<div class="row py-7">'.
													'<div class="col">'.
														'<div class="d-flex justify-content-end">'.
															'<button type="reset" class="btn btn-light me-5">'. esc_html('Cancel', 'inext-woo-pincode-checker') .'</button>'.
															'<button type="submit" class="btn btn-primary submit">'. esc_html('Save', 'inext-woo-pincode-checker') .'</button>'.
														'</div>'.
													'</div>'.
												'</div>'.
											'</form>'.
										'</div>'.
									'</div>'.
								'</div>';

						$html .=
							'</div>';

					$html .=
						'<div class="inext_wpc_notices">'.
					'</div>';

			$html .=
				'</div>'.
				'</div>'.
			'</div>';

		$html .=
			'<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">'.
				'<div class="container-xxl d-flex flex-column flex-md-row border-bottom align-items-center justify-content-between">'.
					'<div class="text-dark order-2 order-md-1">'.
						'<span class="text-muted fw-bold me-1">'. esc_html('&copy;'.INEXT_WPC_PLUGIN_PUBLISH_YEAR, 'inext-woo-pincode-checker') .'</span>'.
						'<a href="javascript:void(0)" target="_blank" class="text-gray-800 text-hover-primary">'. esc_html(INEXT_WPC_PLUGIN_AUTHOR_NAME, 'inext-woo-pincode-checker') .'</a>'.
					'</div>'.
				'</div>'.
			'</div>';

	$html .=
	'</div>';

		_e($html, 'inext-woo-pincode-checker');
	}
}

INEXT_WPC_ADMIN_MENU_SETTINGS::inext_wpc_admin_menu_settings_action();
INEXT_WPC_JS_VARIABLES::inext_wpc_js_variables_action();
?>
