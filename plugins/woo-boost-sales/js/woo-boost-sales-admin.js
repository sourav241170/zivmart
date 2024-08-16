jQuery(document).ready(function ($) {
    'use strict';
    $('.wbs-frequently-bought-together-shortcode').on('click', function () {
        let $input = $(this), $shortcode_message = $('.wbs-frequently-bought-together-shortcode-message');
        $input.select();
        document.execCommand('copy');
        $shortcode_message.show();
        $shortcode_message.fadeOut(5000);
    });
    $('select[name="_woocommerce_boost_sales[upsell_mobile_template]"]').on('change', function () {
        let $atc_mobile = $('select[name="_woocommerce_boost_sales[add_to_cart_style_mobile]"]');
        if ($(this).val() === 'slider') {
            $atc_mobile.closest('tr').fadeIn('200')
        } else {
            $atc_mobile.closest('tr').fadeOut('200')
        }
    }).trigger('change');

    /*Save Submit button*/
    let submit_button;
    jQuery('.wbs-submit').on('click', function (e) {
        submit_button = jQuery(this);
    });
    jQuery('.woocommerce-boost-sales').on('submit', 'form', function (e) {
        submit_button.addClass('loading');
    });
    /*Add row*/
    jQuery('.wbs-crosssell-price-rule-add').on('click', function () {
        let rows = jQuery('.wbs-crosssell-price-rule-row'),
            lastRow = rows.last(),
            min_price = 0;
        if (lastRow.length > 0) {
            min_price = parseInt(lastRow.find('.wbs-crosssell-bundle-price-from').val()) + 1;
            lastRow.find('.wbs-crosssell-bundle-price-from').prop('max', min_price - 1);
        }
        let newRow = lastRow.clone(),
            newRowPlusVal = parseInt(newRow.find('.wbs-crosssell-bundle-price-discount-value').val());
        newRow.find('.wbs-crosssell-bundle-price-from').val(min_price).prop('min', min_price).prop('max', '');
        newRow.find('.vi-ui.dropdown').dropdown();
        jQuery('.wbs-crosssell-price-rule-container').append(newRow);
        recalculatePriceRange();
    });

    /*remove last row*/
    jQuery('.wbs-crosssell-price-rule-remove').on('click', function () {
        let rows = jQuery('.wbs-crosssell-price-rule-row'),
            lastRow = rows.last();
        if (rows.length > 1) {
            let prev = jQuery('.wbs-crosssell-price-rule-row').eq(rows.length - 2);
            lastRow.remove();
            if (rows.length > 2) {
                prev.find('.wbs-crosssell-bundle-price-from').prop('max', '');
            }

        } else {
            alert('Cannot remove more.')
        }
        recalculatePriceRange();
    });
    recalculatePriceRange();

    function recalculatePriceRange() {
        jQuery('.wbs-crosssell-bundle-price-from').unbind().on('change', function () {
            let rows = jQuery('.wbs-crosssell-price-rule-row'),
                current = jQuery(this).parent().parent(),
                value = parseInt(jQuery(this).val());
            let currentPos = rows.index(current),
                nextRow = rows.eq(currentPos + 1),
                prevRow = rows.eq(currentPos - 1);
            let max = parseInt(jQuery(this).prop('max')),
                min = parseInt(jQuery(this).prop('min'));
            if (value < min) {
                value = min;
                jQuery(this).val(value);
            } else if (value > max) {
                value = max;
                jQuery(this).val(value);
            }
            if (currentPos > 1) {
                prevRow.find('.wbs-crosssell-bundle-price-from').prop('max', value - 1);
            }
            if (nextRow.length > 0) {
                nextRow.find('.wbs-crosssell-bundle-price-from').prop('min', value + 1);
            }
        });
    }
});