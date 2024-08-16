<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INEXT_WPC_ADMIN_MENU_WELCOME {
    private static $initiated = false;

    public static function welcome() {
		if ( ! self::$initiated ) :
			self::views_welcome();
		endif;
	}

    /**
 	 * Initializes WordPress hooks
 	**/
	private static function views_welcome() {
        self::$initiated = true;
    }

    /** Actions **/
    public static function inext_wpc_admin_menu_welcome_action() {
        INEXT_WPC_ADMIN_MENU_WELCOME::inext_wpc_html_callback();
    }

	/** Callbacks **/
	public static function inext_wpc_html_callback() {
		$html = '';

		$html .=
			'<div class="wrap inext_wpc_wrapper wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">';

		$html .=
			// '<div class="wrap inext_wpc_wrapper wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">'.
				'<div class="toolbar py-5 py-lg-5" id="kt_toolbar">'.
					'<div id="kt_toolbar_container" class="container-xxl py-5">'.
						'<div class="row gy-0 gx-10">';

						$html .=
							'<div class="col-xl-8 mb-5">'.
								'<div class="card card-xl-stretch bg-body border-0 mb-5 mb-xl-0">'.
									'<div class="card-body d-flex flex-column flex-lg-row flex-stack p-lg-15">'.
										'<div class="d-flex flex-column justify-content-center align-items-center align-items-lg-start me-10 text-center text-lg-start">'.
											'<h3 class="fs-1 line-height-lg mb-5">'.
												'<span class="fw-bold">'. esc_html('Welcome to', 'inext-woo-pincode-checker') .'</span>'.
												'<br>'.
												'<span class="fw-bolder">'. esc_html(INEXT_WPC_PLUGIN_SHORT_NAME, 'inext-woo-pincode-checker') .'</span>'.
											'</h3>'.
											'<div class="fs-6 text-muted mb-7">'. esc_html(INEXT_WPC_PLUGIN_SHORT_NAME . 'is a free plugin with premium features which can help you to check customer pincode', 'inext-woo-pincode-checker') .'</div>'.
											'<div class="d-lg-flex d-sm-block">'.
												'<a href="#features" class="btn btn-success fw-bold px-6 py-3">'. esc_html('Features', 'inext-woo-pincode-checker') .'</a>'.
												'<a href="#how-it-works" class="btn btn-success fw-bold px-6 py-3 ms-5">'. esc_html('How it works', 'inext-woo-pincode-checker') .'</a>'.
											'</div>'.
										'</div>'.
										'<img src="'. esc_url(INEXT_WPC_PLUGIN_ASSETS . '/admin/img/welcome-banner.gif', 'inext-woo-pincode-checker') .'" alt="" class="mw-200px mw-lg-200px mt-lg-n10">'.
									'</div>'.
								'</div>'.
							'</div>';

							$html .=
								'<div class="col-xl-4 mb-5">'.
									'<div class="card card-xl-stretch bg-body border-0">'.
										'<div class="card-body pt-5 mb-xl-9 position-relative">'.
											'<div class="d-flex flex-center mb-5 mb-xxl-0">'.
												'<div id="kt_charts_mixed_widget_16_chart" style="height: 260px; min-height: 240px;">'.
													'<div id="apexchartsevxpxw57j" class="apexcharts-canvas apexchartsevxpxw57j apexcharts-theme-light" style="width: 350px; height: 240px;">'.
														'<svg id="SvgjsSvg1097" width="350" height="240" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent none repeat scroll 0% 0%;">'.
															'<g id="SvgjsG1099" class="apexcharts-inner apexcharts-graphical" transform="translate(58, 0)">'.
																'<defs id="SvgjsDefs1098">'.
																	'<clipPath id="gridRectMaskevxpxw57j">'.
																		'<rect id="SvgjsRect1101" width="242" height="260" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>'.
																		'</clipPath>'.
																	'<clipPath id="forecastMaskevxpxw57j"></clipPath>'.
																	'<clipPath id="nonForecastMaskevxpxw57j"></clipPath>'.
																	'<clipPath id="gridRectMarkerMaskevxpxw57j">'.
																		'<rect id="SvgjsRect1102" width="240" height="262" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>'.
																	'</clipPath>'.
																'</defs>'.
																'<g id="SvgjsG1103" class="apexcharts-radialbar">'.
																	'<g id="SvgjsG1104">'.
																		'<g id="SvgjsG1105" class="apexcharts-tracks">'.
																			'<g id="SvgjsG1106" class="apexcharts-radialbar-track apexcharts-track" rel="1">'.
																				'<path id="apexcharts-radialbarTrack-0" d="M 30.93048780487804 117.99999999999999 A 87.06951219512196 87.06951219512196 0 0 1 205.06951219512194 118" fill="none" fill-opacity="1" stroke="rgba(232,255,243,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="11.36829268292683" stroke-dasharray="0" class="apexcharts-radialbar-area" data:pathOrig="M 30.93048780487804 117.99999999999999 A 87.06951219512196 87.06951219512196 0 0 1 205.06951219512194 118"></path>'.
																			'</g>'.
																		'</g>'.
																		'<g id="SvgjsG1108">'.
																			'<g id="SvgjsG1113" class="apexcharts-series apexcharts-radial-series" seriesName="TotalxMembers" rel="1" data:realIndex="0">'.
																				'<!-- <path id="SvgjsPath1114" d="M 30.93048780487804 117.99999999999999 A 87.06951219512196 87.06951219512196 0 0 1 177.3812645285149 54.32138995792205" fill="none" fill-opacity="0.85" stroke="rgba(80,205,137,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="11.368292682926832" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-0" data:angle="133" data:value="74" index="0" j="0" data:pathOrig="M 30.93048780487804 117.99999999999999 A 87.06951219512196 87.06951219512196 0 0 1 177.3812645285149 54.32138995792205"></path> -->'.
																				'<path id="SvgjsPath1114" fill="none" fill-opacity="0.85" stroke="rgba(80,205,137,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="11.368292682926832" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-0" data:angle="133" data:value="74" index="0" j="0" data:pathorig="M 30.93048780487804 117.99999999999999 A 87.06951219512196 87.06951219512196 0 0 1 177.3812645285149 54.32138995792205" d="M 30.93048780487804 117.99999999999999 A 87.06951219512196 87.06951219512196 0 0 1 205 117.99999999999999"></path>'.
																			'</g>'.
																			'<circle id="SvgjsCircle1109" r="81.38536585365854" cx="118" cy="118" class="apexcharts-radialbar-hollow" fill="transparent"></circle>'.
																			'<g id="SvgjsG1110" class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)" style="opacity: 1;">'.
																				'<text id="SvgjsText1111" font-family="inherit" x="118" y="113" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="700" fill="#a1a5b7" class="apexcharts-text apexcharts-datalabel-label" style="font-family: inherit;">'. esc_html('Accuracy', 'inext-woo-pincode-checker') .'</text>'.
																				'<text id="SvgjsText1112" font-family="inherit" x="118" y="94" text-anchor="middle" dominant-baseline="auto" font-size="30px" font-weight="700" fill="#5e6278" class="apexcharts-text apexcharts-datalabel-value" style="font-family: inherit;">'. esc_html('100%', 'inext-woo-pincode-checker') .'</text>'.
																			'</g>'.
																		'</g>'.
																	'</g>'.
																'</g>'.
															'</g>'.
															'<g id="SvgjsG1100" class="apexcharts-annotations"></g>'.
														'</svg>'.
														'<div class="apexcharts-legend"></div>'.
													'</div>'.
												'</div>'.
											'</div>'.
											'<div class="text-center position-absolute bottom-0 start-50 translate-middle-x w-100 mb-10">'.
												'<p class="fw-bold fs-4 text-gray-400 mb-7 px-5">'. esc_html('This will give you accurate result without any page refresh.', 'inext-woo-pincode-checker') .'</p>'.
												'<div class="m-0">'.
													'<a href="https://plugins.imdadnextweb.com" class="btn btn-success fw-bold px-6 py-3" target="_blank">'. esc_html('Demo', 'inext-woo-pincode-checker') .'</a>'.
													'<a href="https://wordpress.org/support/plugin/inext-woo-pincode-checker" class="btn btn-success fw-bold px-6 py-3 ms-5" target="_blank">'. esc_html('Get Support', 'inext-woo-pincode-checker') .'</a>'.
												'</div>'.
											'</div>'.
										'</div>'.
									'</div>'.
								'</div>';

							$html .=
								'<div class="col-xl-6 mb-5" id="features">'.
									'<div class="card card-xl-stretch bg-body border-0 mb-5 mb-xl-0">'.
										'<div class="card-body d-flex flex-column flex-lg-row flex-stack p-lg-15">'.
											'<div class="d-flex flex-column justify-content-center align-items-center align-items-lg-start me-10 text-center text-lg-start">'.
												'<h3 class="fs-1 line-height-lg mb-5">'.
													'<span class="fw-bold">'. esc_html('Features', 'inext-woo-pincode-checker') .'</span>'.
												'</h3>'.
												'<div class="fs-6 mb-7">'.
													'<ol>'.
														'<li>'. esc_html('You can enabled / disabled any time without losing plugins data.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('Enabled / disabled display on product page, cart page and checkout page.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('Auto detect user billing / shipping pin code.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('Dynamic min, max pin code validation.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('You can modify the button text, label, placeholder, response texts and all from admin dashboard.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('Used only AJAX so no need to refresh the page for checking pin code availability.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('Manually upload / import Excel, CSV files of pin codes list is not required. This plugin will automatically fetch from the system.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('Automatically delete data on uninstalling the plugin.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('More features coming soon on next version.', 'inext-woo-pincode-checker') .
													'</ol>'.
												'</div>'.
											'</div>'.
										'</div>'.
									'</div>'.
								'</div>';

							$html .=
								'<div class="col-xl-6 mb-5" id="how-it-works">'.
									'<div class="card card-xl-stretch bg-body border-0 mb-5 mb-xl-0">'.
										'<div class="card-body d-flex flex-column flex-lg-row flex-stack p-lg-15">'.
											'<div class="d-flex flex-column justify-content-center align-items-center align-items-lg-start me-10 text-center text-lg-start">'.
												'<h3 class="fs-1 line-height-lg mb-5">'.
													'<span class="fw-bold">'. esc_html('How It Works', 'inext-woo-pincode-checker') .'</span>'.
												'</h3>'.
												'<div class="fs-6 mb-7">'.
													'<ol>'.
														'<li>'. esc_html('First, install the iNext Woo Pincode Checker plugin and activate it.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('Install and activate the woocommerce plugin. (Skip this step if you have already activated the woocommerce plugin).', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('Add shipping zone(s) on Woocommerce > Settings > Shipping > Add shipping zone from admin dashboard.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('While adding a shipping zone, in Zone regions field, add some pincode(s) by clicking Limit to specific ZIP/Pincodes(one pincode per line) on where the delivery is available for your store.', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('Great. You have successfully configured the plugin. Such a simple process. Right?', 'inext-woo-pincode-checker') .
														'<li>'. esc_html('Now customer can check the delivery availability on single product page, cart page, checkout page.', 'inext-woo-pincode-checker') .
													'</ol>'.
												'</div>'.
											'</div>'.
										'</div>'.
									'</div>'.
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
				'</div>'.
			'</div>';

		_e($html, 'inext-woo-pincode-checker');
	}
}

INEXT_WPC_ADMIN_MENU_WELCOME::inext_wpc_admin_menu_welcome_action();
?>
