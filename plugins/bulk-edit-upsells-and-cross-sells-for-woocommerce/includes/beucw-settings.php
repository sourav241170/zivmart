<?php

add_action( 'admin_menu', 'beucw_submenu_page' );

/**
 * Add menu function callback.
 */
function beucw_submenu_page() {
	add_submenu_page( 'edit.php?post_type=product', __( 'Upsells & Cross-sells', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ), __( 'Upsells & Cross-sells', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ), 'edit_products', 'bulk-edit-upsells-crosssells', 'beucw_bulk_edit_upsells_submenu_page_callback', 5 );
}

/**
 * Add menu function callback.
 */
function beucw_bulk_edit_upsells_submenu_page_callback() {
	?>
	<div class="bucw-headingwrap">

		<!-- Plugin Heading -->
		<section class="bucw-header">
			<div>
				<h1><?php echo esc_attr__( 'Bulk Edit UpSells and Cross-sells for WooCommerce', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> </h1>
			</div>
			<div class="bucw-collapse-bulk-screen"></div>
		</section>

		<!-- Show all the settings -->
		<main>
			<form method="get" id="bucw-upsells-crosssell" action="options.php">
				<?php
					settings_fields( 'bucw-section' );
					do_settings_sections( 'bulk-edit-upsells-crosssells' );
				?>
				<div class="footerSavebutton">
					<button type="button" class="bucw-save" id="bucw-save-bottom" ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V173.3c0-17-6.7-33.3-18.7-45.3L352 50.7C340 38.7 323.7 32 306.7 32H64zm0 96c0-17.7 14.3-32 32-32H288c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V128zM224 416c-35.3 0-64-28.7-64-64s28.7-64 64-64s64 28.7 64 64s-28.7 64-64 64z"/></svg> <?php esc_html_e( 'Save', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></button>
				</div>
			</form>
		</main>

		<!-- Footer divs -->
		<div class="bucw-footer-upgrade">
			<div class='sft-logo'>
				<a href="<?php echo esc_url( plugins_url( '../assets/img/saffiretech_logo.png', __FILE__ ) ); ?>">
					<img src="<?php echo esc_url( plugins_url( '../assets/img/saffiretech_logo.png', __FILE__ ) ); ?>">
				</a>
			</div>

			<!-- Upgrade now button -->
			<div class="bucw-upgrade-col1">
				<h3><?php esc_html_e( 'Unlock Advanced Features with our PRO plugin', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></h3>
				<div class="bucw-moneyback-badge">
					<div>
						<a href="<?php echo esc_url( plugins_url( '../assets/img/moneyback-badge.png', __FILE__ ) ); ?>">
							<img src="<?php echo esc_url( plugins_url( '../assets/img/moneyback-badge.png', __FILE__ ) ); ?>">
						</a>
					</div>
					<div class="bucw-cashback-text">
						<h3><?php esc_html_e( '100% Risk-Free Money Back Guarantee!', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></h3>
						<p><?php esc_html_e( 'We guarantee you a complete refund for new purchases or renewals if a request is made within 15 Days of purchase.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></p>
						<input type='button'value='Upgrade To Pro!' class="btn" onclick="location.href='https:\/\/www.saffiretech.com/woocommerce-related-products-pro?utm_source=wp_plugin&utm_medium=footer&utm_campaign=free2pro&utm_id=c1&utm_term=upgrade_now&utm_content=beucw';"/>
					</div>
				</div>
			</div>

			<!-- pro features lists -->
			<div class="bucw-upgrade-col">
				<ul>
					<li><i class="fa fa-check" aria-hidden="true"> </i><strong><?php esc_html_e( 'Advanced Bulk Management', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> </strong> : <?php esc_html_e( 'Now set Upsells, Cross-sells and Related products  in go from one single screen in a swift action.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></li>
					<li><i class="fa fa-check" aria-hidden="true"> </i><strong><?php esc_html_e( 'Increased Product Limit', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> </strong> : <?php esc_html_e( 'Boost your efficiency with the capability to manage 50 products at once, a ten fold increase from the free version.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></li>
					<li><i class="fa fa-check" aria-hidden="true"> </i><strong><?php esc_html_e( 'Customizable AJAX Slider', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> </strong> : <?php esc_html_e( 'Elevate your Upsells Section with fast-loading, unlimited product displays for smoother customer engagement.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></li>
					<li><i class="fa fa-check" aria-hidden="true"> </i><strong><?php esc_html_e( 'Custom Control', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> </strong> : <?php esc_html_e( 'Handpick each item in the "Related Products" section for tailored product recommendations.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></li>
					<li><i class="fa fa-check" aria-hidden="true"> </i><strong><?php esc_html_e( 'Sales Boost', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> </strong> : <?php esc_html_e( 'Increase average order value and revenue by displaying more relevant products to customers.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></li>
				</ul>	
			</div>
		</div>
	</div>
	<?php
}

/**
 * Setting field callback function for filter dropdown and select field.
 */
function beucw_products_table() {

	// All allowed html tags.
	$allowed_html = array(
		'select' => array(
			'id'       => array(),
			'name'     => array(),
			'class'    => array(),
			'value'    => array(),
			'multiple' => array(),
		),
		'option' => array(
			'id'       => array(),
			'value'    => array(),
			'selected' => array(),
		),
	);
	?>

	<!-- Filter DropDown Divs -->
	<div class="bucw-filter-dropdown">

		<!-- Filters header select box -->
		<div id="bucw-filter-header">
			<div> 
				<!-- Filter type -->
				<select name="filter-type" id="filter-type">
					<option value="filter-by"><?php esc_html_e( 'Select Filter', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></option>
					<option id="bucw-category" value="bucw-category"><?php esc_html_e( 'Category', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></option>
					<option id="bucw-tags" value="bucw-tags"><?php esc_html_e( 'Tags', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></option>
					<option id="bucw-sku" value="bucw-sku"><?php esc_html_e( 'SKU', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></option>
					<option id="bucw-product" value="bucw-product"><?php esc_html_e( 'Product Name', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></option>
				</select>
			</div>

			<!-- Filter dropdown -->
			<div id="bucw-filter-container"> 

				<!-- Categoty select box -->
				<div id="bucw-select-categories">
					<select class="bucw-filter-box" id="bucw-multiple-categories" name="bucw-multiple-categories[]" data-placeholder="<?php echo esc_attr__( 'Search for categories…', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>" multiple="multiple">
						<?php echo wp_kses( beucw_select2_get_all_categories(), $allowed_html ); ?>
					</select>
				</div>

				<!-- Tag select box -->
				<div id="bucw-select-tags">
					<select class="bucw-filter-box" id="bucw-multiple-tags" name="bucw-multiple-tags[]" data-placeholder="<?php echo esc_attr__( 'Search for tags…', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>" multiple="multiple">
						<?php echo wp_kses( beucw_select2_get_all_tags(), $allowed_html ); ?>
					</select>
				</div>

				<!-- SKU select box -->
				<div id="bucw-select-sku">
					<select class="bucw-filter-box" id="bucw-multiple-sku" name="bucw-multiple-sku[]" data-placeholder="<?php echo esc_attr__( 'Search for SKU…', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>" multiple="multiple">
						<?php echo wp_kses( beucw_select2_get_all_product_sku(), $allowed_html ); ?>
					</select>
				</div>

				<!-- Single product select box -->
				<div id="bucw-select-product">
					<select class="bucw-product-filter-box" id="bucw-single-product" name="bucw-single-product[]" data-placeholder="<?php echo esc_attr__( 'Type any product name...', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>" multiple="multiple">
					<option value=""></option >
						<?php
						$all_products = beucw_get_all_products();

						foreach ( $all_products as $beuc_product ) {
							echo '<option value="' . esc_attr( $beuc_product['product_id'] ) . '">' . esc_attr( $beuc_product['label'] ) . '</option >';
						}
						?>
					</select>
				</div>
			</div>

			<!-- Search div -->
			<div class="bucw-search">
				<button type="button" class="button" id="bucw-search-product" ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352c79.5 0 144-64.5 144-144s-64.5-144-144-144S64 128.5 64 208s64.5 144 144 144z"/></svg><?php esc_html_e( 'Search', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></button>
				<button type="button" class="bucw-save" id="bucw-save-top" > <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V173.3c0-17-6.7-33.3-18.7-45.3L352 50.7C340 38.7 323.7 32 306.7 32H64zm0 96c0-17.7 14.3-32 32-32H288c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V128zM224 416c-35.3 0-64-28.7-64-64s28.7-64 64-64s64 28.7 64 64s-28.7 64-64 64z"/></svg><?php esc_html_e( 'Save', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> 
			</div>
		</div>
	</div><br/>

	<!-- Pagignation section upper -->
	<div class="bucw-left-way">

		<!-- product number -->
		<div class="bucw-number-filter">
			<label for="bucw-number">Product Count</label>
			<select name="bucw-number" id="bucw-number">
				<option value="5">5</option>
				<option value="">10 <a href="https://www.saffiretech.com/">(Pro Version)</a></option>
				<option value="">15 <a href="https://www.saffiretech.com/">(Pro Version)</a></option>
				<option value="">25 <a href="https://www.saffiretech.com/">(Pro Version)</a></option>
			</select>
			&nbsp;&nbsp;
			<span><svg class="bucw_pro_notice" onclick="beucw_call_notice()" xmlns="http://www.w3.org/2000/svg" height="20" width="22" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path fill="#f8c844" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5 .4 5.1 .8 7.7 .8 26.5 0 48-21.5 48-48s-21.5-48-48-48z"/></svg> Now, manage 50 products in one go with our <a href="https://www.saffiretech.com/woocommerce-related-products-pro/?utm_source=wp_plugin&utm_medium=profield&utm_campaign=free2pro&utm_id=c1&utm_term=related-product-pro-for-woocommerce&utm_content=beucw">PRO plugin!</a></span>
		</div>

		<!-- pagignation div -->
		<div class="bucw-pagig-div">
			<span class="bucw_product_count"></span>

			<button type="button" class="bucw_product_first width_but" onclick="bucw_first_get_data()">&laquo;</button>
			<button type="button" class="bucw_product_prev width_but" onclick="bucw_prev_get_data( this )">&lsaquo;</button>

			<span class="bucw_product_num">
				<input type="number" class="bucw_numtext upf" min="1" value="1" onkeypress="bucw_get_text_data( this, event )">
			</span> of <span class="bucw_pages_total"></span>

			<button type="button" class="bucw_product_next width_but" onclick="bucw_next_get_data()">&rsaquo;</button>
			<button type="button" class="bucw_product_last width_but" onclick="bucw_last_get_data()">&raquo;</button>
		</div>
	</div>
	<br/><br/>

	<!-- Loader Image-->
	<div id="bucw_loader">
		<img id="loading-image" src="<?php echo esc_url( plugins_url() . '/bulk-edit-upsells-and-cross-sells-for-woocommerce/assets/img/loader.gif' ); ?>" style="display:none;"/>
	</div>

	<!-- Product div to show upsells, cross-sells and related products -->
	<div id="products-table" class="products-table"></div>

	<!-- Pagignation section lower -->
	<div class="bucw-left-way bucw_bottom">

		<!-- pagignation div -->
		<div class="bucw-pagig-div">
			<span class="bucw_product_count"></span>

			<button type="button" class="bucw_product_first width_but" onclick="bucw_first_get_data()">&laquo;</button>
			<button type="button" class="bucw_product_prev width_but" onclick="bucw_prev_get_data( this )">&lsaquo;</button>

			<span class="bucw_product_num">
				<input type="number" class="bucw_numtext dpf" min="1" value="1" onkeypress="bucw_get_text_data( this, event )">
			</span> of <span class="bucw_pages_total"></span>

			<button type="button" class="bucw_product_next width_but" onclick="bucw_next_get_data()">&rsaquo;</button>
			<button type="button" class="bucw_product_last width_but" onclick="bucw_last_get_data()">&raquo;</button>
		</div>
	</div>
	<br/><br/>

	<script>

		// On first button click.
		function bucw_first_get_data() {

			var filterType = jQuery("#filter-type").val();

			var taxonomyID;

			if ("bucw-category" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-categories").val();
			} else if ("bucw-tags" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-tags").val();
			} else if ("bucw-product" == filterType) {
				var taxonomyID = jQuery("#bucw-single-product").val();
			} else if ("bucw-sku" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-sku").val();
			} else {
				Swal.fire('', __('Please select a filter ( product category, tags, product name or SKU) to search your products.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
				return;
			}

			if (!(taxonomyID === "")) {
				jQuery.ajax({
					url: upsellajaxapi.url,
					type: "POST",
					data: {
						action: "taxonomyID_action",
						nonce: upsellajaxapi.nonce,
						filterType: filterType,
						taxonomyID: taxonomyID,
						limitdata : 5,
						offsetdata: 0,
					},
					beforeSend: function () {
						jQuery("#loading-image").show();
					},
					success: function (data) {
						if (!data) {
							Swal.fire('', __('No products found on current on selected search criteria. Please change filter or search for other products.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
							jQuery("#products-table").hide();
							jQuery("#loading-image").hide();
							jQuery(".bucw-left-way").hide();
						} else {
							jQuery("#products-table").show();
							jQuery("#products-table").html(data);
							jQuery('.beucw-select2').select2({ width: '100%', minimumInputLength: 2 });
							jQuery(".bucw-save").show();

							jQuery("#loading-image").hide();
							jQuery(".bucw-left-way").css('display','flex');

							// Total Product Count.
							let productTotalCount = parseInt( jQuery(".beucw_total").val() );

							let total_page_numbers  = Math.ceil( parseFloat( parseInt( productTotalCount ) / parseInt( 5 ) ) );

							// Total Product count.
							jQuery(".bucw_product_count").html( productTotalCount + " Items  " );

							// Total Page count after of number.
							jQuery(".bucw_pages_total").html( Math.ceil( productTotalCount / 5 ) );

							// Default value one.
							jQuery(".bucw_numtext").val(1);

							jQuery( '.bucw_product_prev' ).prop('disabled', true);
							jQuery( '.bucw_product_first' ).prop('disabled', true);

							jQuery( '.bucw_product_next' ).prop('disabled', false);
							jQuery( '.bucw_product_last' ).prop('disabled', false);
						}
					},
				});
			} else {
				Swal.fire('', __('Please input  keywords/ terms for the chosen filter for the products you wish to update', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
			}
		}

		// On previous button click.
		function bucw_prev_get_data( predata ) {
			var taxonomyID;

			var filterType    = jQuery("#filter-type").val();
			let selected_data = jQuery('#bucw-number').val();

			if ("bucw-category" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-categories").val();
			} else if ("bucw-tags" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-tags").val();
			} else if ("bucw-product" == filterType) {
				var taxonomyID = jQuery("#bucw-single-product").val();
			} else if ("bucw-sku" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-sku").val();
			} else {
				Swal.fire('', __('Please select a filter ( product category, tags, product name or SKU) to search your products.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
				return;
			}

			if (!(taxonomyID === "")) {

				// Current textbox value.
				let currentbox = jQuery( predata ).siblings('.bucw_product_num').children('.bucw_numtext').val();

				console.log( currentbox );

				// Current page number.
				let current_page_number = parseInt( currentbox );

				// Set dynamic page number to textbox.
				jQuery('.bucw_numtext.upf').val( Math.ceil( ( current_page_number !== 1 ) ? current_page_number - 1 : 1 ) );
				jQuery('.bucw_numtext.dpf').val( Math.ceil( ( current_page_number !== 1 ) ? current_page_number - 1 : 1 ) );

				// get the value.
				let new_page = jQuery(predata).siblings('.bucw_product_num').children('.bucw_numtext').val( Math.ceil( ( current_page_number !== 1 ) ? current_page_number - 1 : 1 ) );

				// Get the current data.
				let page_data = new_page.val();

				jQuery.ajax({
					url: upsellajaxapi.url,
					type: "POST",
					data: {
						action: "taxonomyID_action",
						nonce: upsellajaxapi.nonce,
						filterType: filterType,
						taxonomyID: taxonomyID,
						limitdata : 5,
						offsetdata: ( page_data != 1 ) ? ( page_data ) * parseInt( 5 ) - parseInt( 5 ) : 0,
					},
					beforeSend: function () {
						jQuery("#loading-image").show();
					},
					success: function (data) {
						if (!data) {
							Swal.fire('', __('No products found on current on selected search criteria. Please change filter or search for other products.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
							jQuery("#products-table").hide();
							jQuery("#loading-image").hide();
							jQuery(".bucw-left-way").hide();
						} else {
							jQuery("#products-table").show();
							jQuery("#products-table").html(data);
							jQuery('.beucw-select2').select2({ width: '100%', minimumInputLength: 2 });
							jQuery(".bucw-save").show();

							jQuery("#loading-image").hide();
							jQuery(".bucw-left-way").css('display','flex');

							// Total Product Count.
							let productTotalCount  = parseInt( jQuery(".beucw_total").val() );
							let total_page_numbers = Math.ceil( parseFloat( parseInt( productTotalCount ) / parseInt( 5 ) ) );

							// Total Product count.
							jQuery(".bucw_product_count").html( productTotalCount + " Items  " );

							// Total Page count after of number.
							jQuery(".bucw_pages_total").html( Math.ceil( productTotalCount / 5 ) );

							// Current textbox value.
							let currentPageVal = parseInt( new_page.val() );

							if ( currentPageVal == 1 ) {
								jQuery( '.bucw_product_prev' ).prop('disabled', true);
								jQuery( '.bucw_product_first' ).prop('disabled', true);

								jQuery( '.bucw_product_next' ).prop('disabled', false);
								jQuery( '.bucw_product_last' ).prop('disabled', false);
							} else {
								jQuery( '.bucw_product_prev' ).prop('disabled', false);
								jQuery( '.bucw_product_first' ).prop('disabled', false);

								jQuery( '.bucw_product_next' ).prop('disabled', false);
								jQuery( '.bucw_product_last' ).prop('disabled', false);
							}
						}
					},
				});
			} else {
				Swal.fire('', __('Please input  keywords/ terms for the chosen filter for the products you wish to update', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
			}
		}

		// On input enter change.
		function bucw_get_text_data( curdata, event ) {

			var key = event.keyCode || event.which;

			// On enter key press.
			if ( key == 13 ) {
				var taxonomyID;

				var filterType    = jQuery("#filter-type").val();
				let selected_data = jQuery('#bucw-number').val();

				// Filter types.
				if ("bucw-category" == filterType) {
					var taxonomyID = jQuery("#bucw-multiple-categories").val();
				} else if ("bucw-tags" == filterType) {
					var taxonomyID = jQuery("#bucw-multiple-tags").val();
				} else if ("bucw-product" == filterType) {
					var taxonomyID = jQuery("#bucw-single-product").val();
				} else if ("bucw-sku" == filterType) {
					var taxonomyID = jQuery("#bucw-multiple-sku").val();
				} else {
					Swal.fire('', __('Please select a filter ( product category, tags, product name or SKU) to search your products.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
					return;
				}

				// If filter is selected.
				if (!(taxonomyID === "")) {

					// Get page maximum value. 
					let pageMax = parseInt( jQuery(curdata).attr( 'max' ) );

					// Get current page no.
					var current_page_number = parseInt( jQuery(curdata).val() );

					// Set default page number.
					if ( current_page_number > 0 && current_page_number < pageMax ) {
						current_page_number = Math.ceil( current_page_number );

						jQuery(curdata).val( current_page_number );
						jQuery(".bucw_numtext.upf").val( current_page_number );
						jQuery(".bucw_numtext.dpf").val( current_page_number );
					} else if ( current_page_number <  0 || current_page_number === 0 ) {
						current_page_number = 1;

						jQuery(curdata).val( current_page_number );
						jQuery(".bucw_numtext.upf").val( current_page_number );
						jQuery(".bucw_numtext.dpf").val( current_page_number );
					} else if ( current_page_number > pageMax || current_page_number == pageMax ) {
						current_page_number = pageMax;

						jQuery(curdata).val( current_page_number );
						jQuery(".bucw_numtext.upf").val( current_page_number );
						jQuery(".bucw_numtext.dpf").val( current_page_number );
					}

					jQuery.ajax({
						url: upsellajaxapi.url,
						type: "POST",
						data: {
							action: "taxonomyID_action",
							nonce: upsellajaxapi.nonce,
							filterType: filterType,
							taxonomyID: taxonomyID,
							limitdata : 5,
							offsetdata: ( current_page_number != 1 ) ? ( current_page_number ) * parseInt( 5 ) - parseInt( 5 ) : 0,
						},
						beforeSend: function () {
							jQuery("#loading-image").show();
						},
						success: function (data) {
							if (!data) {
								Swal.fire('', __('No products found on current on selected search criteria. Please change filter or search for other products.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
								jQuery("#products-table").hide();
							} else {
								jQuery("#products-table").show();
								jQuery("#products-table").html(data);
								jQuery('.beucw-select2').select2({ width: '100%', minimumInputLength: 2 });
								jQuery(".bucw-save").show();

								jQuery("#loading-image").hide();
								jQuery(".bucw-left-way").css('display','flex');

								// Total Product Count.
								let productTotalCount  = parseInt( jQuery(".beucw_total").val() );
								let total_page_numbers = Math.ceil( parseFloat( parseInt( productTotalCount ) / 5 ) );

								// Total Product count on span.
								jQuery(".bucw_product_count").html( productTotalCount + " Items  " );

								// Total Page count after of number.
								jQuery(".bucw_pages_total").html( Math.ceil( productTotalCount / 5 ) );

								// Set default page number.
								if ( current_page_number > 0 && current_page_number < total_page_numbers ) {
									jQuery(curdata).val( Math.ceil( current_page_number ) );
									jQuery(".bucw_numtext.upf").val( Math.ceil( current_page_number ) );
									jQuery(".bucw_numtext.dpf").val( Math.ceil( current_page_number ) );
								} else if ( current_page_number <  0 ) {
									jQuery(curdata).val( 1 );
									jQuery(".bucw_numtext.upf").val( 1 );
									jQuery(".bucw_numtext.dpf").val( 1 );
								} else if ( current_page_number > total_page_numbers ) {
									jQuery(curdata).val( total_page_numbers );
									jQuery(".bucw_numtext.upf").val( total_page_numbers );
									jQuery(".bucw_numtext.dpf").val( total_page_numbers );
								}

								// Current page no.
								let currentPageNo = parseInt( jQuery(curdata).val() );

								// If only one page
								if ( currentPageNo === 1 && total_page_numbers > 1 ) {
									jQuery( '.bucw_product_first' ).prop('disabled', true);
									jQuery( '.bucw_product_prev' ).prop('disabled', true);

									jQuery( '.bucw_product_next' ).prop('disabled', false);
									jQuery( '.bucw_product_last' ).prop('disabled', false);
								}

								// If page no are same.
								if ( currentPageNo == total_page_numbers ) {
									jQuery( '.bucw_product_next' ).prop('disabled', true);
									jQuery( '.bucw_product_last' ).prop('disabled', true);

									jQuery( '.bucw_product_prev' ).prop('disabled', false);
									jQuery( '.bucw_product_first' ).prop('disabled', false);

								} else if ( currentPageNo > 1 && total_page_numbers > 1 ) {
									jQuery( '.bucw_product_next' ).prop('disabled', false);
									jQuery( '.bucw_product_last' ).prop('disabled', false);

									jQuery( '.bucw_product_prev' ).prop('disabled', false);
									jQuery( '.bucw_product_first' ).prop('disabled', false);
								}
							}
						},
					});
				} else {
					Swal.fire('', __('Please input  keywords/ terms for the chosen filter for the products you wish to update', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
				}
			}
		}

		// On next button click.
		function bucw_next_get_data() {
			var taxonomyID;

			var filterType = jQuery("#filter-type").val();

			if ("bucw-category" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-categories").val();
			} else if ("bucw-tags" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-tags").val();
			} else if ("bucw-product" == filterType) {
				var taxonomyID = jQuery("#bucw-single-product").val();
			} else if ("bucw-sku" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-sku").val();
			} else {
				Swal.fire('', __('Please select a filter ( product category, tags, product name or SKU) to search your products.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
				return;
			}

			if (!(taxonomyID === "")) {

				// Get maximum page value.
				let pageMax = parseInt( jQuery(".bucw_numtext").attr( 'max' ) );

				// Get current page number.
				let current_page_number = parseInt( jQuery(".bucw_numtext").val() );

				jQuery.ajax({
					url: upsellajaxapi.url,
					type: "POST",
					data: {
						action: "taxonomyID_action",
						nonce: upsellajaxapi.nonce,
						filterType: filterType,
						taxonomyID: taxonomyID,
						limitdata : 5,
						offsetdata: current_page_number * parseInt( 5 ),
					},
					beforeSend: function () {
						jQuery("#loading-image").show();
					},
					success: function (data) {
						if (!data) {
							Swal.fire('', __('No products found on current on selected search criteria. Please change filter or search for other products.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
							jQuery("#products-table").hide();
							jQuery('#loading-image').hide();
							jQuery('.bucw-left-way').hide();
						} else {
							jQuery("#products-table").show();
							jQuery("#products-table").html(data);
							jQuery('.beucw-select2').select2({ width: '100%', minimumInputLength: 2 });
							jQuery(".bucw-save").show();

							// Hide loading image and show pagignation.
							jQuery('#loading-image').hide();
							jQuery('.bucw-left-way').css('display','flex');

							// Total Product count.
							let productTotalCount = parseInt( jQuery(".beucw_total").val() );

							let total_page_numbers  = Math.ceil( parseFloat( parseInt( productTotalCount ) / parseInt( 5 ) ) );

							// Total Product count on span.
							jQuery(".bucw_product_count").html( productTotalCount + " Items  " );

							// Total Page count after of number.
							jQuery(".bucw_pages_total").html( Math.ceil( productTotalCount / 5 ) );

							// Dynamic text box value.
							jQuery(".bucw_numtext").val( Math.ceil( ( current_page_number !== total_page_numbers ) ? current_page_number + 1 : total_page_numbers ) );

							let currentPageVal = parseInt( jQuery(".bucw_numtext").val() );

							if ( currentPageVal == total_page_numbers ) {
								jQuery( '.bucw_product_next' ).prop('disabled', true);
								jQuery( '.bucw_product_last' ).prop('disabled', true);

								jQuery( '.bucw_product_first' ).prop('disabled', false);
								jQuery( '.bucw_product_prev' ).prop('disabled', false);
							} else {
								jQuery( '.bucw_product_next' ).prop('disabled', false);
								jQuery( '.bucw_product_last' ).prop('disabled', false);

								jQuery( '.bucw_product_first' ).prop('disabled', false);
								jQuery( '.bucw_product_prev' ).prop('disabled', false);
							}
						}
					},
				});
			} else {
				Swal.fire('', __('Please input  keywords/ terms for the chosen filter for the products you wish to update', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
			}
		}

		// On last button click.
		function bucw_last_get_data() {
			var taxonomyID;

			let pageTotal = Math.ceil( jQuery(".beucw_total_pages").val() );
			let pageMax   = parseInt( jQuery(".bucw_numtext").attr( 'max' ) );

			var filterType = jQuery("#filter-type").val();

			if ("bucw-category" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-categories").val();
			} else if ("bucw-tags" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-tags").val();
			} else if ("bucw-product" == filterType) {
				var taxonomyID = jQuery("#bucw-single-product").val();
			} else if ("bucw-sku" == filterType) {
				var taxonomyID = jQuery("#bucw-multiple-sku").val();
			} else {
				Swal.fire('', __('Please select a filter ( product category, tags, product name or SKU) to search your products.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
				return;
			}

			if (!(taxonomyID === "")) {

				// Total pages count.
				let productTotalCount = parseInt( jQuery(".beucw_total").val() );

				let total_page_numbers  = Math.ceil( parseFloat( parseInt( productTotalCount ) / parseInt( 5 ) ) );

				jQuery.ajax({
					url: upsellajaxapi.url,
					type: "POST",
					data: {
						action: "taxonomyID_action",
						nonce: upsellajaxapi.nonce,
						filterType: filterType,
						taxonomyID: taxonomyID,
						limitdata : 5,
						offsetdata: ( total_page_numbers - 1 ) * parseInt( 5 ),
					},
					beforeSend: function () {
						jQuery("#loading-image").show();
					},
					success: function (data) {
						if (!data) {
							Swal.fire('', __('No products found on current on selected search criteria. Please change filter or search for other products.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
							jQuery("#products-table").hide();
							jQuery("#loading-image").hide();
							jQuery(".bucw-left-way").hide();
						} else {
							jQuery("#products-table").show();
							jQuery("#products-table").html(data);
							jQuery('.beucw-select2').select2({ width: '100%', minimumInputLength: 2 });
							jQuery(".bucw-save").show();

							jQuery("#loading-image").hide();
							jQuery(".bucw-left-way").css('display','flex');

							// Total Product count.
							let productTotalCount = parseInt( jQuery(".beucw_total").val() );

							let total_page_numbers  = Math.ceil( parseFloat( parseInt( productTotalCount ) / parseInt( 5 ) ) );

							// Total Product count.
							jQuery(".bucw_product_count").html( productTotalCount + " Items  " );

							// Total Page count after of number.
							jQuery(".bucw_pages_total").html( Math.ceil( productTotalCount / 5 ) );

							// Total page count set to textbox.
							jQuery(".bucw_numtext").val( Math.ceil( total_page_numbers ) );

							jQuery( '.bucw_product_next' ).prop('disabled', true);
							jQuery( '.bucw_product_last' ).prop('disabled', true);

							jQuery( '.bucw_product_first' ).prop('disabled', false);
							jQuery( '.bucw_product_prev' ).prop('disabled', false);
						}
					},
				});
			} else {
				Swal.fire('', __('Please input  keywords/ terms for the chosen filter for the products you wish to update', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
			}
		}

		// SVG notice PoPup.
		function beucw_call_notice() {

			var bucwUpgradeNow = '<?php esc_html_e( 'Upgrade Now', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>';

			Swal.fire({
				title: '<div class="pro-alert-header">' + '<?php esc_html_e( 'Pro Field Alert!', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>' + '</div>',
				showCloseButton: true,
				html: '<div class="pro-crown"><svg xmlns="http://www.w3.org/2000/svg" height="100" width="100" viewBox="0 0 640 512"><path fill="#f8c844" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5 .4 5.1 .8 7.7 .8 26.5 0 48-21.5 48-48s-21.5-48-48-48z"/></svg></div><div class="popup-text1"><?php esc_html_e( 'Looking for this cool feature? Go Pro!', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></div><div class="popup-text2"><?php esc_html_e( 'Go with our premium version to unlock the following features:', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>' + '</div> <ul><li><svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ff3d3d" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>' + '<?php esc_html_e( 'bulk-edit-upsells-and-cross-sells-for-woocommerceBulk Update  Related Products, Upsells, and Cross-Sells from a single screen.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> ' + '</li><li><svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ff3d3d" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg><?php esc_html_e( 'Custom Related Products  Shortcode with AJAX Slider.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></li><li><svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 448 512"><path fill="#ff3d3d" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>' + '<?php esc_html_e( 'More Control for Related Products : Show Ratings, Sale Price, Widget Location & more.', '' ); ?>' + '</li><li><svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ff3d3d" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg><?php esc_html_e( 'Sales Boost: Increase average order value and revenue.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></li></ul><button class="bucw-upgrade-now" style="border: none"><a href="https://www.saffiretech.com/woocommerce-related-products-pro?utm_source=wp_plugin&utm_medium=profield&utm_campaign=free2pro&utm_id=c1&utm_term=upgrade_now&utm_content=beucw" target="_blank" class="purchase-pro-link">'+bucwUpgradeNow+'</a></button>',
				customClass: "bucw-popup",
				showConfirmButton: false,
			});

			jQuery( '.bucw-popup' ).css('width', '800px');
			jQuery( '.bucw-popup > .swal2-header').css('background', '#061727' );
			jQuery( '.bucw-popup > .swal2-header').css('margin', '-20px' );
			jQuery( '.pro-alert-header' ).css('padding-top', '25px' );
			jQuery( '.pro-alert-header' ).css('padding-bottom', '20px' );
			jQuery( '.pro-alert-header' ).css( 'color', 'white' );
			jQuery( '.pro-crown' ).css( 'margin-top', '20px' );
			jQuery( '.popup-text1').css( 'font-size', '30px' );
			jQuery( '.popup-text1' ).css( 'font-weight', '600' );
			jQuery( '.popup-text1' ).css( 'padding-bottom', '10px' );
			jQuery( '.bucw-popup > .swal2-content > .swal2-html-container > ul ' ).css( 'text-align', 'justify' );
			jQuery( '.bucw-popup > .swal2-content > .swal2-html-container > ul ' ).css( 'padding-left', '25px' );
			jQuery( '.bucw-popup > .swal2-content > .swal2-html-container > ul ' ).css( 'padding-right', '25px' );
			jQuery( '.bucw-popup > .swal2-content > .swal2-html-container > ul ' ).css( 'line-height', '2em' );
			jQuery( '.popup-text2' ).css( 'padding', '10px' );
			jQuery( '.popup-text2' ).css( 'font-weignt', '500');
			jQuery( '.bucw-popup > .swal2-content > .swal2-html-container > ul, .popup-text1, .popup-text2').css('color', '#061727' );
		}
	</script>

	<?php
}

add_action( 'admin_init', 'beucw_bulk_edit_settings_function' );

/**
 * Function to add setting field .
 */
function beucw_bulk_edit_settings_function() {
	add_settings_section( 'bucw-section', '', null, 'bulk-edit-upsells-crosssells' );
	add_settings_field( 'bucw-product-categories', '', 'beucw_products_table', 'bulk-edit-upsells-crosssells', 'bucw-section', array( 'class' => 'bucw-settings-field' ) );
}

add_action( 'wp_ajax_taxonomyID_action', 'beucw_taxonomy_id_callback' );
add_action( 'wp_ajax_nopriv_taxonomyID_action', 'beucw_taxonomy_id_callback' );

/**
 * To display all products in tabular format for upsell and cross-sell .
 */
function beucw_taxonomy_id_callback() {

	if ( isset( $_POST['nonce'] ) && isset( $_POST['filterType'] ) && ! empty( $_POST['filterType'] ) && isset( $_POST['taxonomyID'] ) && ! empty( $_POST['taxonomyID'] ) ) {

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ) ) {
			wp_die( esc_html__( 'Permission Denied.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ) );
		}

		$filter_type = sanitize_text_field( wp_unslash( $_POST['filterType'] ) );
		$taxonomy_id = array_map( 'intval', wp_unslash( $_POST['taxonomyID'] ) );

		switch ( $filter_type ) {
			case 'bucw-category':
				$products_ids = beucw_get_category_products_ids( $taxonomy_id );
				break;
			case 'bucw-tags':
				$products_ids = beucw_get_tags_products_ids( $taxonomy_id );
				break;
			case 'bucw-product':
				$products_ids = beucw_get_products_ids_products( $taxonomy_id );
				break;
			case 'bucw-sku':
				$products_ids = beucw_get_products_ids_products( $taxonomy_id );
				break;
			default:
				break;
		}

		if ( ! empty( $products_ids ) && ! ( null === $products_ids ) ) {
			?>

			<!-- Table Row Heading Title -->
			<div>
				<span><?php esc_html_e( 'Product Name', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></span>  
				<span>
					<?php esc_html_e( 'UpSells', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>
					<span class="setting-help-tip">
						<div class="tooltipdata"> <?php esc_html_e( 'Please search for your products and set upsells for it in the corressponding box of this column', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> </div>
					</span>
				</span>

				<span>
					<?php esc_html_e( 'Cross-Sells', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>
					<span class="setting-help-tip">
						<div class="tooltipdata"> <?php esc_html_e( 'Please search for your products and set cross-sells for it in the corressponding box of this column', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> </div>
					</span>
				</span>

				<span>
					<?php esc_html_e( 'Related Products', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>
					<svg class="bucw_pro_notice" onclick="beucw_call_notice()" xmlns="http://www.w3.org/2000/svg" height="16" width="20" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path fill="#f8c844" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5 .4 5.1 .8 7.7 .8 26.5 0 48-21.5 48-48s-21.5-48-48-48z"/></svg>
				</span> 
			</div>

			<?php
			$all_products = beucw_get_all_products_with_variations();

			// Iterate through all the products.
			foreach ( $products_ids['data'] as $product_id ) {
				$product              = wc_get_product( $product_id );
				$product_title        = $product->get_title();
				$upsells_ids          = $product->get_upsell_ids();
				$cross_sell_ids       = $product->get_cross_sell_ids();
				$related_products_ids = beucw_get_related_products( $product_id );
				$product_sku          = $product->get_sku() ? ' (' . $product->get_sku() . ')' : false;
				$thumbnail            = $product->get_image( 'woocommerce_thumbnail' );
				?>

				<!-- All Product row and products -->
				<div class="product-row" >
					<div class ="product-name">
						<div class="bucw-product-thumbnail">
							<a href="<?php echo esc_url( $product->get_permalink() ); ?>" target="_blank">
								<?php
								if ( $product->get_image_id() > 0 ) {
									echo wp_kses_post( $thumbnail );
								} else {
									$source = wc_placeholder_img_src( 'woocommerce_thumbnail' );
									echo '<img src="' . esc_url( $source ) . '">';
								}
								?>
							</a>
						</div>
						<div>
							<span class="bucw-product-name"><?php esc_html_e( 'Product Name', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></span>
							<span class="bucw-product-title"><a id="<?php echo esc_attr( $product_id ); ?>" href="<?php echo esc_url( $product->get_permalink() ); ?>" target="_blank"><?php echo 'ID:' . esc_attr( $product_id ) . ' ' . esc_attr( $product_title ) . ' ' . esc_attr( $product_sku ); ?></a></span>
						</div>
					</div>

					<!-- UpSells product -->
					<div class="beuc-upsells-products">
						<span class="bucw-upsells"><?php esc_html_e( 'Upsells', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></span>
						<select class="beucw-select2 upsells-token" id="<?php echo 'upsell-' . esc_attr( $product_id ); ?>" name="<?php echo 'upsell-' . esc_attr( $product_id ) . '[]'; ?>" data-placeholder="<?php echo esc_attr__( 'Search for a product…', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>" multiple="multiple">
							<?php
							foreach ( $all_products as $beuc_product ) {
								if ( in_array( $beuc_product['product_id'], $upsells_ids, true ) ) {
									echo '<option selected="selected" value="' . esc_attr( $beuc_product['product_id'] ) . '">' . esc_attr( $beuc_product['label'] ) . '</option >';
								} else {
									echo '<option value="' . esc_attr( $beuc_product['product_id'] ) . '">' . esc_attr( $beuc_product['label'] ) . '</option >';
								}
							}
							?>
						</select>
					</div>

					<!-- Cross-Sells Product -->
					<div class="beuc-crosssell-products">
						<span class="bucw-crosssells"><?php esc_html_e( 'Cross-sells', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></span>
						<select class="beucw-select2 crosssells-token" id="<?php echo 'cross-sell-' . esc_attr( $product_id ); ?>" name="<?php echo 'cross-sell-' . esc_attr( $product_id ) . '[]'; ?>" data-placeholder="<?php echo esc_attr__( 'Search for a product…', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>" multiple="multiple">
							<?php
							foreach ( $all_products as $beuc_product ) {
								if ( in_array( $beuc_product['product_id'], $cross_sell_ids, true ) ) {
									echo '<option selected="selected" value="' . esc_attr( $beuc_product['product_id'] ) . '">' . esc_attr( $beuc_product['label'] ) . '</option >';
								} else {
									echo '<option value="' . esc_attr( $beuc_product['product_id'] ) . '">' . esc_attr( $beuc_product['label'] ) . '</option >';
								}
							}
							?>
						</select>
					</div>

					<!-- Related Products -->
					<div class="beuc-related-products">
						<span class="bucw-related"></span>
						<select class="beucw-select2 related-pro-notice related-token" id="<?php echo 'related-' . esc_attr( $product_id ); ?>" data-placeholder="<?php echo esc_attr__( 'Search for a product…', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>" multiple="multiple">
							<?php
							foreach ( $all_products as $rprow_product ) {
								if ( in_array( $rprow_product['product_id'], $related_products_ids, true ) ) {
									echo '<option selected="selected" value="' . esc_attr( $rprow_product['product_id'] ) . '">' . esc_attr( $rprow_product['label'] ) . '</option >';
								} else {
									echo '<option value="' . esc_attr( $rprow_product['product_id'] ) . '">' . esc_attr( $rprow_product['label'] ) . '</option >';
								}
							}
							?>
						</select>
					</div>
				</div>
				<?php
			}

			// Gets the product & page count.
			$product_total_count = intval( $products_ids['count'] );
			$total_page_numbers  = floatval( $product_total_count / 5 );

			if ( ! $total_page_numbers % 5 ) {
				++$product_total_count;
			}
			?>

			<!-- Page No showing -->
			<div class="pager">
				<div id="pageNumbers">
					<input type="hidden" value="<?php echo esc_html( $product_total_count ); ?>" class="beucw_total"/>
					<input type="hidden" value="<?php echo esc_html( $total_page_numbers ); ?>" class="beucw_total_pages"/>
				</div>
			</div>
			<?php
		}
	}
	?>

	<!-- PoPup showing content -->
	<script>
		var { __ } = wp.i18n;

		var myTimeout = setTimeout(() => {
			jQuery( '.beuc-related-products span.select2-selection--multiple  ul.select2-selection__rendered li.select2-selection__choice span.select2-selection__choice__remove' ).text('');
			jQuery( 'div.beuc-related-products .select2-container .select2-selection--multiple .select2-selection__rendered li.select2-search > input.select2-search__field' ).on( 'keypress', function (e) {
				e.preventDefault();

				var bucwAlertMessage = '<?php esc_html_e( 'This field is available in related products pro plugin', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>';
				var bucwUpgradeNow = '<?php esc_html_e( 'Upgrade Now', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>';

				Swal.fire({
					title: '<div class="pro-alert-header">'+ '<?php esc_html_e( 'Pro Field Alert!', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>' + '</div>',
					showCloseButton: true,
					html: '<div class="pro-crown"><svg xmlns="http://www.w3.org/2000/svg" height="100" width="100" viewBox="0 0 640 512"><path fill="#f8c844" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5 .4 5.1 .8 7.7 .8 26.5 0 48-21.5 48-48s-21.5-48-48-48z"/></svg></div><div class="popup-text1"><?php esc_html_e( 'Looking for this cool feature? Go Pro!', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></div><div class="popup-text2"><?php esc_html_e( 'Go with our premium version to unlock the following features:', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?>' + '</div> <ul><li><svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ff3d3d" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>' + '<?php esc_html_e( 'bulk-edit-upsells-and-cross-sells-for-woocommerceBulk Update  Related Products, Upsells, and Cross-Sells from a single screen.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?> ' + '</li><li><svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ff3d3d" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg><?php esc_html_e( 'Custom Related Products  Shortcode with AJAX Slider.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></li><li><svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 448 512"><path fill="#ff3d3d" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>' + '<?php esc_html_e( 'More Control for Related Products : Show Ratings, Sale Price, Widget Location & more.', '' ); ?>' + '</li><li><svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ff3d3d" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg><?php esc_html_e( 'Sales Boost: Increase average order value and revenue.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></li></ul><button class="bucw-upgrade-now" style="border: none"><a href="https://www.saffiretech.com/woocommerce-related-products-pro?utm_source=wp_plugin&utm_medium=profield&utm_campaign=free2pro&utm_id=c1&utm_term=upgrade_now&utm_content=beucw" target="_blank" class="purchase-pro-link">'+bucwUpgradeNow+'</a></button>',
					customClass: "bucw-popup",
					showConfirmButton: false,
				});

				jQuery( '.bucw-popup' ).css('width', '800px');
				jQuery( '.bucw-popup > .swal2-header').css('background', '#061727' );
				jQuery( '.bucw-popup > .swal2-header').css('margin', '-20px' );
				jQuery( '.pro-alert-header' ).css('padding-top', '25px' );
				jQuery( '.pro-alert-header' ).css('padding-bottom', '20px' );
				jQuery( '.pro-alert-header' ).css( 'color', 'white' );
				jQuery( '.pro-crown' ).css( 'margin-top', '20px' );
				jQuery( '.popup-text1').css( 'font-size', '30px' );
				jQuery( '.popup-text1' ).css( 'font-weight', '600' );
				jQuery( '.popup-text1' ).css( 'padding-bottom', '10px' );
				jQuery( '.bucw-popup > .swal2-content > .swal2-html-container > ul ' ).css( 'text-align', 'justify' );
				jQuery( '.bucw-popup > .swal2-content > .swal2-html-container > ul ' ).css( 'padding-left', '25px' );
				jQuery( '.bucw-popup > .swal2-content > .swal2-html-container > ul ' ).css( 'padding-right', '25px' );
				jQuery( '.bucw-popup > .swal2-content > .swal2-html-container > ul ' ).css( 'line-height', '2em' );
				jQuery( '.popup-text2' ).css( 'padding', '10px' );
				jQuery( '.popup-text2' ).css( 'font-weignt', '500');
				jQuery( '.bucw-popup > .swal2-content > .swal2-html-container > ul, .popup-text1, .popup-text2').css('color', '#061727' );
			});
		}, 200 );
	</script>
	<?php
	die();
}

add_action( 'wp_ajax_beucw_update', 'beucw_ajax_update_notice' );
add_action( 'wp_ajax_nopriv_beucw_update', 'beucw_ajax_update_notice' );

/**
 * Update rating Notice.
 */
function beucw_ajax_update_notice() {
	global $current_user;

	if ( isset( $_POST['nonce'] ) && ! empty( $_POST['nonce'] ) ) {
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ) ) {
			wp_die( esc_html__( 'Permission Denied.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ) );
		}

		update_user_meta( $current_user->ID, 'beucw_rate_notices', 'rated' );
		echo esc_url( network_admin_url() );
	}

	wp_die();
}

add_action( 'admin_notices', 'beucw_plugin_notice' );

/**
 * Rating notice widget.
 * Save the date to display notice after 10 days.
 */
function beucw_plugin_notice() {
	global $current_user;

	$user_id = $current_user->ID;

	wp_enqueue_script( 'jquery' );

	// if plugin is activated and date is not set then set the next 10 days.
	$today_date = strtotime( 'now' );

	if ( ! get_user_meta( $user_id, 'beucw_notices_time' ) ) {
		$after_10_day = strtotime( '+10 day', $today_date );
		update_user_meta( $user_id, 'beucw_notices_time', $after_10_day );
	}

	// gets the option of user rating status and week status.
	$rate_status = get_user_meta( $user_id, 'beucw_rate_notices', true );
	$next_w_date = get_user_meta( $user_id, 'beucw_notices_time', true );

	// show if user has not rated the plugin and it has been 1 week.
	if ( 'rated' !== $rate_status && $today_date > $next_w_date ) {
		?>

		<div class="notice notice-warning is-dismissible">
			<p><span><?php esc_html_e( "Awesome, you've been using", 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></span><span><?php echo '<strong> Bulk Edit Upsells and Cross-Sells for WooCommerce </strong>'; ?><span><?php esc_html_e( 'for more than 1 week', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></span></p>
			<p><?php esc_html_e( 'If you like our plugin would you like to rate our plugin at WordPress.org ?', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></p>
			<span><a href="https://wordpress.org/plugins/bulk-edit-upsells-and-cross-sells-for-woocommerce/#reviews" target="_blank"><?php esc_html_e( "Yes, I'd like to rate it!", 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></a></span>&nbsp; - &nbsp;<span><a class="beucw_hide_rate" href="#"><?php esc_html_e( 'I already did!', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></a></span>
			<br/><br/>
		</div>

		<script>
			let beucwAjaxURL = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";
			let beucwNonce = "<?php echo esc_attr( wp_create_nonce( 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ) ); ?>";

			// Redirect to same page after rated.
			jQuery(".beucw_hide_rate").click(function (event) {
				event.preventDefault();

				jQuery.ajax({
					method: 'POST',
					url: beucwAjaxURL,
					data: {
						action: 'beucw_update',
						nonce: beucwNonce,
					},
					success: (res) => {
						window.location.href = window.location.href
					}
				});
			});
		</script>
		<?php
	}
}
