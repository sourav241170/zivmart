jQuery(document).ready(function () {

  const { __ } = wp.i18n;

  // Select2 library loading. 
  jQuery('.bucw-filter-box').select2({ width: '400px' });
  jQuery('.bucw-product-filter-box').select2({ width: '400px' });

  // Initailly hide all things.
  jQuery(
    "#bucw-filter-container, #bucw-select-categories,#bucw-select-tags,#bucw-select-sku, #bucw-select-product, .bucw-save"
  ).hide();

  // Collpase and expand
  setTimeout(() => {
    if( jQuery("#collapse-button").attr('aria-expanded') === 'true' ){
      jQuery('.bucw-collapse-bulk-screen').empty().html('<svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M200 32H56C42.7 32 32 42.7 32 56V200c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l40-40 79 79-79 79L73 295c-6.9-6.9-17.2-8.9-26.2-5.2S32 302.3 32 312V456c0 13.3 10.7 24 24 24H200c9.7 0 18.5-5.8 22.2-14.8s1.7-19.3-5.2-26.2l-40-40 79-79 79 79-40 40c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H456c13.3 0 24-10.7 24-24V312c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2l-40 40-79-79 79-79 40 40c6.9 6.9 17.2 8.9 26.2 5.2s14.8-12.5 14.8-22.2V56c0-13.3-10.7-24-24-24H312c-9.7 0-18.5 5.8-22.2 14.8s-1.7 19.3 5.2 26.2l40 40-79 79-79-79 40-40c6.9-6.9 8.9-17.2 5.2-26.2S209.7 32 200 32z"/></svg>');
    } else {
      jQuery('.bucw-collapse-bulk-screen').empty().html('<svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M9.4 9.4C21.9-3.1 42.1-3.1 54.6 9.4L160 114.7V96c0-17.7 14.3-32 32-32s32 14.3 32 32v96c0 4.3-.9 8.5-2.4 12.2c-1.6 3.7-3.8 7.3-6.9 10.3l-.1 .1c-3.1 3-6.6 5.3-10.3 6.9c-3.8 1.6-7.9 2.4-12.2 2.4H96c-17.7 0-32-14.3-32-32s14.3-32 32-32h18.7L9.4 54.6C-3.1 42.1-3.1 21.9 9.4 9.4zM256 256a64 64 0 1 1 128 0 64 64 0 1 1 -128 0zM114.7 352H96c-17.7 0-32-14.3-32-32s14.3-32 32-32h96 0l.1 0c8.8 0 16.7 3.6 22.5 9.3l.1 .1c3 3.1 5.3 6.6 6.9 10.3c1.6 3.8 2.4 7.9 2.4 12.2v96c0 17.7-14.3 32-32 32s-32-14.3-32-32V397.3L54.6 502.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L114.7 352zM416 96c0-17.7 14.3-32 32-32s32 14.3 32 32v18.7L585.4 9.4c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3L525.3 160H544c17.7 0 32 14.3 32 32s-14.3 32-32 32H448c-8.8 0-16.8-3.6-22.6-9.3l-.1-.1c-3-3.1-5.3-6.6-6.9-10.3s-2.4-7.8-2.4-12.2l0-.1v0V96zM525.3 352L630.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L480 397.3V416c0 17.7-14.3 32-32 32s-32-14.3-32-32V320v0c0 0 0-.1 0-.1c0-4.3 .9-8.4 2.4-12.2c1.6-3.8 3.9-7.3 6.9-10.4c5.8-5.8 13.7-9.3 22.5-9.4c0 0 .1 0 .1 0h0 96c17.7 0 32 14.3 32 32s-14.3 32-32 32H525.3z"/></svg>');
    }
  }, 200);

  // Expand and collapse div.
  jQuery('.bucw-collapse-bulk-screen').click(function(){
    jQuery('#collapse-button').trigger('click');
    if( jQuery("#collapse-menu button").attr('aria-expanded') === 'true' ){
      jQuery('.bucw-collapse-bulk-screen').empty().html('<svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M200 32H56C42.7 32 32 42.7 32 56V200c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l40-40 79 79-79 79L73 295c-6.9-6.9-17.2-8.9-26.2-5.2S32 302.3 32 312V456c0 13.3 10.7 24 24 24H200c9.7 0 18.5-5.8 22.2-14.8s1.7-19.3-5.2-26.2l-40-40 79-79 79 79-40 40c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H456c13.3 0 24-10.7 24-24V312c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2l-40 40-79-79 79-79 40 40c6.9 6.9 17.2 8.9 26.2 5.2s14.8-12.5 14.8-22.2V56c0-13.3-10.7-24-24-24H312c-9.7 0-18.5 5.8-22.2 14.8s-1.7 19.3 5.2 26.2l40 40-79 79-79-79 40-40c6.9-6.9 8.9-17.2 5.2-26.2S209.7 32 200 32z"/></svg>');
    } else {
      jQuery('.bucw-collapse-bulk-screen').empty().html('<svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M9.4 9.4C21.9-3.1 42.1-3.1 54.6 9.4L160 114.7V96c0-17.7 14.3-32 32-32s32 14.3 32 32v96c0 4.3-.9 8.5-2.4 12.2c-1.6 3.7-3.8 7.3-6.9 10.3l-.1 .1c-3.1 3-6.6 5.3-10.3 6.9c-3.8 1.6-7.9 2.4-12.2 2.4H96c-17.7 0-32-14.3-32-32s14.3-32 32-32h18.7L9.4 54.6C-3.1 42.1-3.1 21.9 9.4 9.4zM256 256a64 64 0 1 1 128 0 64 64 0 1 1 -128 0zM114.7 352H96c-17.7 0-32-14.3-32-32s14.3-32 32-32h96 0l.1 0c8.8 0 16.7 3.6 22.5 9.3l.1 .1c3 3.1 5.3 6.6 6.9 10.3c1.6 3.8 2.4 7.9 2.4 12.2v96c0 17.7-14.3 32-32 32s-32-14.3-32-32V397.3L54.6 502.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L114.7 352zM416 96c0-17.7 14.3-32 32-32s32 14.3 32 32v18.7L585.4 9.4c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3L525.3 160H544c17.7 0 32 14.3 32 32s-14.3 32-32 32H448c-8.8 0-16.8-3.6-22.6-9.3l-.1-.1c-3-3.1-5.3-6.6-6.9-10.3s-2.4-7.8-2.4-12.2l0-.1v0V96zM525.3 352L630.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L480 397.3V416c0 17.7-14.3 32-32 32s-32-14.3-32-32V320v0c0 0 0-.1 0-.1c0-4.3 .9-8.4 2.4-12.2c1.6-3.8 3.9-7.3 6.9-10.4c5.8-5.8 13.7-9.3 22.5-9.4c0 0 .1 0 .1 0h0 96c17.7 0 32 14.3 32 32s-14.3 32-32 32H525.3z"/></svg>');
    }
  })

  // Filter Type on change. 
  jQuery("#filter-type").on("change", function () {
    jQuery('#bucw-filter-container').show();
    var filterType = jQuery(this).val();
    switch (filterType) {
      case "bucw-category":
        jQuery("#bucw-select-categories").show();
        jQuery("#bucw-select-product, #bucw-select-tags,#bucw-select-sku").hide();
        break;
      case "bucw-tags":
        jQuery("#bucw-select-tags").show();
        jQuery("#bucw-select-categories, #bucw-select-sku, #bucw-select-product").hide();
        break;
      case "bucw-product":
        jQuery("#bucw-select-product").show();
        jQuery("#bucw-select-categories, #bucw-select-tags, #bucw-select-sku").hide();
        break;
      case "bucw-sku":
        jQuery("#bucw-select-sku").show();
        jQuery("#bucw-select-categories, #bucw-select-product, #bucw-select-tags").hide();
        break;
      default:
        break;
    }
  });

  // To search all product based on selected taxonomy .
  jQuery("#bucw-search-product").on("click", function () {
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
      jQuery.ajax({
        url: upsellajaxapi.url,
        type: "POST",
        data: {
          action: "taxonomyID_action",
          nonce: upsellajaxapi.nonce,
          filterType: filterType,
          taxonomyID: taxonomyID,
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
            
            // Hide loding image and show the pagignation. 
            jQuery("#loading-image").hide();
            jQuery('.bucw-left-way').css('display','flex');

            // Total Product Count.
            let productTotalCount  = parseInt( jQuery(".beucw_total").val() );
            let total_page_numbers = Math.ceil( parseFloat( parseInt( productTotalCount ) / parseInt( 5 ) ) );

            // Total Product count on span.
            jQuery(".bucw_product_count").html( productTotalCount + " Items  " );

            // Total Page count after of number.
						jQuery(".bucw_pages_total").html( Math.ceil( productTotalCount / 5 ) );

            jQuery(".bucw_numtext").attr( 'max', Math.ceil( productTotalCount / 5 ) );

            // Set one on new search.
            jQuery( '.bucw_numtext' ).val( 1 );

            // Current page no text values.
            let currentPageNo = parseInt( jQuery( '.bucw_numtext' ).val() );
  
            // If only one page
            if ( currentPageNo === 1 && total_page_numbers > 1 ) {
              jQuery( '.bucw_product_first' ).prop( 'disabled', true );
              jQuery( '.bucw_product_prev' ).prop( 'disabled', true );

              jQuery( '.bucw_product_next' ).prop( 'disabled', false );
              jQuery( '.bucw_product_last' ).prop( 'disabled', false );
            }

            // if both pages are equal disable all.
            if ( currentPageNo == total_page_numbers ) {
              jQuery( '.bucw_product_next' ).prop( 'disabled', true );
              jQuery( '.bucw_product_last' ).prop( 'disabled', true );

              jQuery( '.bucw_product_first' ).prop( 'disabled', true );
              jQuery( '.bucw_product_prev' ).prop( 'disabled', true );
            }
          }
        },
      });
    } else {
      Swal.fire('', __('Please input  keywords/ terms for the chosen filter for the products you wish to update', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'warning');
    }
  });

  // Hide all rendered things on filter type change.
  jQuery("#filter-type").on("change", function () {
    jQuery("#products-table").hide();
    jQuery(".bucw-save").hide();

    // Hide loader and pagignation
    jQuery("#loading-image").hide();
    jQuery(".bucw-left-way").hide();
  });

  // To save all ids of upsell and cross-sell products.
  jQuery(".bucw-save").click(function (event) {
    var TableData = []; //initialize array;
    var bucw_data = ""; //empty var;

    //To hide 'Saving Changes..' popup.
    Swal.fire({
      title: __('Saving Changes...', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'),
      html: __('This will take a few seconds.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'),
      didOpen: () => {
        Swal.showLoading();
      },
    })

    //Here traverse and  read input/select values present in each row, ;
    jQuery("#products-table .product-row").each(function (index, row) {

      var currentRow = null;
      currentRow = jQuery(this);

      TableData[index] = {
        beucw_product_id: currentRow.find('.product-name .bucw-product-title a').attr("id"),
        beucw_product_upsells_ids: currentRow.find('select.upsells-token').val(),
        beucw_product_crosssells_ids: currentRow.find('select.crosssells-token').val(),
      };

      //Convert tableData array to JsonData
      // bucw_data = JSON.stringify(TableData);
    });

    jQuery.ajax({
      url: upsellajaxapi.url,
      method: "POST",
      data: {
        action: "save_all_selected_products",
        nonce: upsellajaxapi.nonce,
        selected_data: TableData,
      },
      success: function (response) {

        Swal.hideLoading();
        Swal.clickConfirm();  // To hide 'Saving Changes..' popup.
        Swal.fire('', __('Products Updated Successfully!', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), 'success');
        if (0 == jQuery('#bucw-success-massage').length) {
          jQuery("#bucw-upsells-crosssell").before(
            '<div id="bucw-success-massage" class="updated notice is-dismissible"><p>' + __('Products Updated Successfully!', 'bulk-edit-upsells-and-cross-sells-for-woocommerce') + '</p><button id="bucw-dismiss-admin-message" class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>'
          );
        }

        jQuery("#bucw-dismiss-admin-message").click(function (event) {
          jQuery("#bucw-success-massage").remove();
        });
      },
      error: function (jqXHR, textStatus, errorThrown) {

        Swal.fire(__('Some Error Occurred', 'bulk-edit-upsells-and-cross-sells-for-woocommerce'), errorThrown, 'error');
        jQuery("#bucw-upsells-crosssell").before(
          '<div id="bucw-error-massage" class="error notice is-dismissible"><p>' +
          errorThrown +
          '</p><button id="bucw-dismiss-admin-message" class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>'
        );
        jQuery("#bucw-dismiss-admin-message").click(function (event) {
          jQuery("#bucw-error-massage").remove();
        });
      },
    });

  });

  // Copy all the products.
  jQuery(document).on('copy', '.beuc-upsells-products span.select2,.beuc-crosssell-products span.select2', function (e) {

    let selectParent = jQuery(this).parent().children("select.beucw-select2");

    if (selectParent.val()) {
      let copyedValues = selectParent.val();
      navigator.clipboard.writeText(copyedValues).then(function () {
      }, function (err) {
        // console.error('Could not copy text: ', err);
      });

    }
  });

  // Paste all the Products.
  jQuery(document).on('paste', '.beuc-upsells-products span.select2,.beuc-crosssell-products span.select2', function (e) {

    let clipboard = (e.originalEvent || e).clipboardData.getData('text/plain');
    let currentSelect = jQuery(this).parent().children("select.beucw-select2");

    const selectedPId = currentSelect.val();
    const copiedPId = clipboard.split(',');

    const children = selectedPId.concat(copiedPId);

    currentSelect.val(children);
    currentSelect.trigger('change');

    if (!isNaN(Number(copiedPId[0]))) {
      setTimeout(function () {
        currentSelect.parent().find('.select2-search__field').val("");
      }.bind(this), 0);
    }
  });
});
