jQuery(document).ready(function ($) {
    'use strict';
    jQuery(document).on('click', '#wbs-content-discount-bar .wbs-overlay, #wbs-content-discount-bar .wbs-close', function () {
        jQuery('#wbs-content-discount-bar').fadeOut(200);
        clearTimeout(time_redirect);
        woocommerce_boost_sales_params.show_thank_you = true;
    });
    jQuery('body').on('click', '.wbs-button-continue-stay', function (e) {
        e.preventDefault();
        jQuery(this).closest('.woocommerce-boost-sales').find('.wbs-close').click();
    });
    if (typeof woocommerce_boost_sales_params !== 'undefined') {
        var $woocommerce_boost_sales_cross_sells = jQuery('.wbs-crosssells');
        if ($woocommerce_boost_sales_cross_sells.length > 0) {
            var bundle_selects = $woocommerce_boost_sales_cross_sells.find('select');
            $woocommerce_boost_sales_cross_sells.find('.wbs-variations_form').map(function () {
                let $form = $(this), $frequently_item = $form.closest('.wbs-product'),
                    $current_product_image = $frequently_item.find('.product-image img').eq(0);
                $form.wc_variation_form();
                $form.on('found_variation', function (e, variation) {
                    if (variation.attributes && variation.is_in_stock && variation.is_purchasable) {
                        $frequently_item.data('variation_id', variation['variation_id']);
                        if (variation.price_html) {
                            $frequently_item.data('item_price', parseFloat(variation['display_price']));
                            $frequently_item.find('.price:not(wbs-bundle-item-variation-price)').hide();
                            $frequently_item.find('.wbs-bundle-item-variation-price').html($(variation['price_html']).html()).show();
                        }
                        change_product_image(variation['image'], $current_product_image);
                    }
                    var enable_add_to_cart = true;
                    for (var i = 0; i < bundle_selects.length; i++) {
                        if (bundle_selects.eq(i).val() == '') {
                            enable_add_to_cart = false;
                            break;
                        }
                    }
                    if (enable_add_to_cart) {
                        woo_boost_sale.handle_price($woocommerce_boost_sales_cross_sells, true);
                        $woocommerce_boost_sales_cross_sells.find('.wbs-single_add_to_cart_button').removeClass('disabled wc-variation-selection-needed');
                    } else {
                        woo_boost_sale.handle_price($woocommerce_boost_sales_cross_sells);
                        $woocommerce_boost_sales_cross_sells.find('.wbs-single_add_to_cart_button').addClass('disabled wc-variation-selection-needed');
                    }
                });
            });
            if (bundle_selects.length > 0) {
                $woocommerce_boost_sales_cross_sells.find('.wbs-single_add_to_cart_button').addClass('disabled wc-variation-selection-needed');
                bundle_selects.on('change', function () {
                    if (!$(this).val()) {
                        let $current_product = $(this).closest('.wbs-product'),
                            $current_product_image = $current_product.find('.product-image img').eq(0);
                        $current_product.find('.price:not(wbs-bundle-item-variation-price)').show();
                        $current_product.find('.wbs-bundle-item-variation-price').hide();
                        change_product_image($current_product.data('item_image'), $current_product_image);
                        woo_boost_sale.handle_price($woocommerce_boost_sales_cross_sells);
                        $woocommerce_boost_sales_cross_sells.find('.wbs-single_add_to_cart_button').addClass('disabled wc-variation-selection-needed');
                    }
                })
            }
        }
    }
    woo_boost_sale.init();
    woo_boost_sale.add_to_cart();

    function change_product_image(image_data, $image) {
        if (image_data) {
            if (image_data.hasOwnProperty('srcset') && image_data.srcset) {
                $image.attr('srcset', image_data.srcset);
            } else {
                $image.attr('srcset', '');
            }
            if (image_data.hasOwnProperty('thumb_src') && image_data.thumb_src) {
                $image.attr('src', image_data.thumb_src);
            } else if (image_data.hasOwnProperty('url') && image_data.url) {
                $image.attr('src', image_data.url);
            }
        }
    }
});


function wbs_sort_object(object) {
    'use strict';
    return Object.keys(object).sort().reduce(function (result, key) {
        result[key] = object[key];
        return result;
    }, {});
}

var time_redirect;
var cross_sell_init;
var woo_boost_sale = {
    hide_crosssell_init: 0,
    check_quantity: 0,
    init: function () {
        if (typeof wbs_add_to_cart_params == 'undefined' || parseInt(wbs_add_to_cart_params.ajax_button) != 1) {
            if (typeof viwsatc_sb_params === 'undefined' || viwsatc_sb_params.added_to_cart) {
                this.slider();
            }
        } else if (woocommerce_boost_sales_params.added_to_cart) {

            this.slider();
        }
        this.product_variation();
        woo_boost_sale.hide();
        if (!this.hide_crosssell_init) {
            this.initial_delay_icon();
        }
        jQuery('.gift-button').on('click', function () {
            // jQuery(document).scrollTop(0);
            //woo_boost_sale.hide_upsell();
            woo_boost_sale.show_cross_sell();
            //woo_boost_sale.slider_cross_sell();
            jQuery('.vi-wbs-headline').removeClass('wbs-crosssell-message').addClass('wbs-crosssell-message');

        });
        /*Cross sells below add to cart button*/
        if (jQuery('#wbs-content-cross-sells-product-single .wbs-crosssells').length > 0) {
            this.cross_slider();
        }
        jQuery('.woocommerce-boost-sales.wbs-content-up-sell .single_add_to_cart_button').unbind();
        // if (jQuery('.wbs-msg-congrats').length > 0) {
        //     var time = jQuery('.wbs-msg-congrats').attr('data-time');
        //     if (time) {
        //         woo_boost_sale.counter(jQuery('.auto-redirect span'), time);
        //     }
        // }
        jQuery('#wbs-gift-button-cat').on('click', function () {
            woo_boost_sale.hide_upsell();
            woo_boost_sale.show_cross_sell_archive();
        });
        if (jQuery('.vi-wbs-topbar').hasClass('wbs_top_bar')) {
            var windowsize = jQuery(window).width();
            jQuery('.vi-wbs-headline').css('top', '50px');
            if (windowsize >= 1366) {
                jQuery('.wbs-archive-upsells .wbs-content').css('margin-top', '45px');
            } else {
                jQuery('.wbs-archive-upsells .wbs-content').css('margin-top', '85px');
            }
        }
        if (jQuery('.vi-wbs-topbar').hasClass('wbs_bottom_bar')) {
        } else {
            var windowsize = jQuery(window).width();
            if (windowsize < 1366) {
                jQuery('.wbs-archive-upsells .wbs-content').css('margin-top', '70px');
            }
            if (windowsize < 640) {
                jQuery('.wbs-archive-upsells .wbs-content').css('margin-top', '0px');
            }
        }
        if (jQuery('.wbs-message-success').length < 1) {
            jQuery('.wbs-content-up-sell').css('height', '100%');
        }
        if (jQuery('.wbs-content').hasClass('wbs-msg-congrats')) {
            setTimeout(function () {
                jQuery('.vi-wbs-headline').show();
            }, 0);
        }
        jQuery(document).on('click', '.vi-wbs_progress_close', function () {
            jQuery('.vi-wbs-topbar').fadeOut('slow');
        });
        if (!jQuery('#flexslider-cross-sell .vi-flex-prev').hasClass('vi-flex-disabled')) {
            jQuery('#flexslider-cross-sell').hover(function () {
                jQuery('#flexslider-cross-sell .vi-flex-prev').css("opacity", "1");
            }, function () {
                jQuery('#flexslider-cross-sell .vi-flex-prev').css("opacity", "0");
            });
        }
        if (!jQuery('#flexslider-cross-sell .vi-flex-next').hasClass('vi-flex-disabled')) {
            jQuery('#flexslider-cross-sell').hover(function () {
                jQuery('#flexslider-cross-sell .vi-flex-next').css("opacity", "1");
            }, function () {
                jQuery('#flexslider-cross-sell .vi-flex-next').css("opacity", "0");
            });
        }
        /*Smooth Archive page*/
        jQuery('.wbs-wrapper').animate({
            opacity: 1
        }, 200);
        woo_boost_sale.chosen_variable_upsell();
        jQuery('.wbs-upsells > .wbs-').find('div.vi-wbs-chosen:first').removeClass('wbs-hidden-variable').addClass('wbs-show-variable');

    },
    product_variation: function () {
        jQuery('#wbs-content-upsells').find('.wbs-variations_form').each(function () {
            // jQuery(this).addClass('variations_form');
            jQuery(this).wc_variation_form();
        });
        jQuery('#wbs-content-upsells').on('check_variations', function () {
            jQuery(this).find('.variations_button').each(function () {
                if (jQuery(this).hasClass('woocommerce-variation-add-to-cart-disabled')) {
                    jQuery(this).find('.wbs-single_add_to_cart_button').addClass('disabled wc-variation-selection-needed');
                } else {
                    jQuery(this).find('.wbs-single_add_to_cart_button').removeClass('disabled wc-variation-selection-needed');
                }
            });
        });

        jQuery('#wbs-content-upsells').on('show_variation', function () {
            jQuery(this).find('.variations_button').each(function () {
                if (jQuery(this).hasClass('woocommerce-variation-add-to-cart-disabled')) {
                    jQuery(this).find('.wbs-single_add_to_cart_button').addClass('disabled wc-variation-selection-needed');
                } else {
                    jQuery(this).find('.wbs-single_add_to_cart_button').removeClass('disabled wc-variation-selection-needed');
                }
            })
        });
        jQuery('.wbs-single_add_to_cart_button').on('click', function (e) {
            if (jQuery(this).is('.disabled')) {
                e.preventDefault();

                if (jQuery(this).hasClass('wc-variation-is-unavailable')) {
                    window.alert(wc_add_to_cart_variation_params.i18n_unavailable_text);
                } else if (jQuery(this).hasClass('wc-variation-selection-needed')) {
                    window.alert(wc_add_to_cart_variation_params.i18n_make_a_selection_text);
                }
            }
        })
    },
    add_to_cart: function () {
        var check_quantity = 0, $upsells = jQuery('.wbs-content-up-sell');
        jQuery(document).ajaxComplete(function (event, jqxhr, settings) {
            if (settings.hasOwnProperty('contentType') && settings.contentType === false) {
                return;
            }
            var ajax_link = settings.url;
            var data_post = settings.data;
            var product_id = 0;
            var variation_id = 0;
            var check_variation = 0;
            if (data_post == '' || data_post == null || jQuery.isEmptyObject(data_post)) {
                return;
            }
            var data_process = data_post.split('&');
            /*Process get Product ID - Require product_id*/
            for (var i = 0; i < data_process.length; i++) {
                if (data_process[i].search(/product_id/i) >= 0) {
                    product_id = data_process[i];
                } else if (data_process[i].search(/add-to-cart/i) >= 0) {
                    product_id = data_process[i];
                }
                if (data_process[i].search(/variation_id/i) >= 0) {
                    variation_id = data_process[i];
                    check_variation = 1;
                }
            }
            /*Reformat Product ID*/
            if (check_variation) {
                if (variation_id) {
                    product_id = variation_id.replace(/^\D+/g, '');
                    product_id = parseInt(product_id);
                } else {
                    return;
                }
            } else {
                if (product_id) {
                    product_id = product_id.replace(/^\D+/g, '');
                    product_id = parseInt(product_id);
                } else {
                    return;
                }
            }
            if (ajax_link.search(/wc-ajax=add_to_cart/i) >= 0 || ajax_link.search(/wc-ajax=viwcaio_add_to_cart/i) >= 0 || ajax_link.search(/wc-ajax=wpvs_add_to_cart/i) >= 0 || data_post.search(/action=wbs_ajax_add_to_cart/i) >= 0 || data_post.search(/action=wacv_ajax_add_to_cart/i) >= 0 || data_post.search(/action=woofc_update_cart/i) >= 0) {
                let added_to_cart = [];
                if (jqxhr !== undefined && jqxhr.hasOwnProperty('responseJSON') && jqxhr.responseJSON) {
                    if (jqxhr.responseJSON.hasOwnProperty('fragments') && jqxhr.responseJSON.fragments) {
                        let fragments = jqxhr.responseJSON.fragments;
                        if (fragments.hasOwnProperty('wbs_added_to_cart') && fragments.wbs_added_to_cart) {
                            if (fragments.wbs_added_to_cart.hasOwnProperty(product_id) && fragments.wbs_added_to_cart[product_id]) {
                                added_to_cart = fragments.wbs_added_to_cart;
                            }
                        }
                        if (fragments.hasOwnProperty('wbs_upsells_html')) {
                            if (fragments['wbs_upsells_html']) {
                                if (fragments['wbs_upsells_html'].search(/wbs-overlay/i) < 1) {
                                    jQuery('html').removeClass('wbs-html-overflow');
                                    jQuery('.vi-wbs-topbar').animate({opacity: 1}, 500);
                                }
                                if ($upsells.length === 0) {
                                    $upsells = jQuery('<div id="wbs-content-upsells" class="woocommerce-boost-sales wbs-content-up-sell wbs-archive-page" style="display: none;"></div>');
                                    jQuery('body').append($upsells);
                                }
                                $upsells.html(fragments['wbs_upsells_html']);
                                $upsells.css({
                                    'opacity': 0,
                                    'display': 'flex',
                                    'visibility': 'visible'
                                }).animate({'opacity': 1}, 300);
                                woo_boost_sale.hide_crosssell_init = 1;
                                woo_boost_sale.init();
                                woo_boost_sale.slider();
                                setTimeout(function () {
                                    jQuery('.wbs-wrapper').animate({
                                        opacity: 1
                                    }, 200);
                                }, 200);
                            }
                        }
                    }
                }
            }
        });
    },
    hide: function () {
        jQuery('.wbs-close, .woocommerce-boost-sales .wbs-overlay').unbind();
        jQuery('.wbs-close, .woocommerce-boost-sales .wbs-overlay').on('click', function () {
            jQuery('.woocommerce-boost-sales').not('.woocommerce-boost-sales-active-discount').fadeOut(200);
            jQuery('html').removeClass('wbs-html-overflow');
            clearTimeout(time_redirect);
            woocommerce_boost_sales_params.show_thank_you = true;
        });
    },
    slider: function () {
        var windowsize = jQuery(window).width();
        var item_per_row = jQuery('#flexslider-up-sell').attr('data-item-per-row');
        var item_per_row_mobile = jQuery('#flexslider-up-sell').attr('data-item-per-row-mobile');
        var rtl = jQuery('#flexslider-up-sell').attr('data-rtl');
        if (parseInt(rtl)) {
            rtl = true;
        } else {
            rtl = false;
        }
        if (item_per_row == undefined) {
            item_per_row = 4;
        }
        if (windowsize < 768 && windowsize >= 600) {
            item_per_row = 2;
        }
        if (windowsize < 600) {
            item_per_row = item_per_row_mobile;
        }
        /*Up-sells*/
        if (jQuery('#flexslider-up-sell').length > 0) {
            jQuery('#flexslider-up-sell').vi_flexslider({
                namespace: "woocommerce-boost-sales-",
                selector: '.wbs-vi-slides > .wbs-product',
                animation: "slide",
                animationLoop: false,
                itemWidth: 145,
                itemMargin: 12,
                controlNav: false,
                maxItems: item_per_row,
                reverse: false,
                slideshow: false,
                rtl: rtl
            });
            if (jQuery('#wbs-content-upsells').hasClass('wbs-form-submit') || (typeof wbs_add_to_cart_params != 'undefined' && parseInt(wbs_add_to_cart_params.ajax_button) != 1)) {
                jQuery('html').addClass('wbs-html-overflow');
            }
        }

    },
    cross_slider: function () {
        var rtl = jQuery('.wbs-cross-sells').attr('data-rtl');
        var windowsize = jQuery(window).width(),
            min_item = 3,
            itemMargin = 24,
            max_item = woocommerce_boost_sales_params.crosssells_max_item_desktop,
            cross_sells_single_width = jQuery('#flexslider-cross-sells').width();
        if (windowsize < 768 && windowsize >= 600) {
            min_item = 2;
            max_item = woocommerce_boost_sales_params.crosssells_max_item_tablet;
        }
        if (windowsize < 600) {
            itemMargin = 6;
            min_item = 1;
            max_item = woocommerce_boost_sales_params.crosssells_max_item_mobile;
        }
        if (max_item < 2) {
            max_item = 2;
        }
        if (parseInt(rtl)) {
            rtl = true;
        } else {
            rtl = false;
        }
        var slide_items = jQuery('#flexslider-cross-sells').find('.wbs-product').length;
        if (max_item > slide_items) {
            max_item = slide_items;
        }
        if (jQuery('#wbs-content-cross-sells-product-single #flexslider-cross-sells').length > 0) {
            itemMargin = 6;
            jQuery('#flexslider-cross-sells').vi_flexslider({
                namespace: "woocommerce-boost-sales-",
                selector: '.wbs-cross-sells > .wbs-product',
                animation: "slide",
                animationLoop: false,
                itemWidth: (parseInt(cross_sells_single_width / max_item) - 6),
                itemMargin: itemMargin,
                controlNav: false,
                maxItems: max_item,
                slideshow: false,
                rtl: rtl
            });
        } else {
            var $crs_flexslider = jQuery('#flexslider-cross-sells');
            if ($crs_flexslider.length > 0) {
                var itemWidth = 150;
                if (slide_items < 3) {
                    itemWidth = 175;
                }
                cross_sells_single_width = (itemWidth + 24) * max_item + 30;
                jQuery('.wbs-content-inner.wbs-content-inner-crs').css({'max-width': $crs_flexslider.find('.wbs-cross-sells').hasClass('wbs-products-1') ? 380 : cross_sells_single_width + 'px'});
                $crs_flexslider.vi_flexslider({
                    namespace: "woocommerce-boost-sales-",
                    selector: '.wbs-cross-sells > .wbs-product',
                    animation: "slide",
                    animationLoop: false,
                    itemWidth: itemWidth,
                    itemMargin: itemMargin,
                    controlNav: false,
                    maxItems: max_item,
                    slideshow: false,
                    rtl: rtl
                });
                jQuery('html').addClass('wbs-html-overflow');
            }
        }
    },
    hide_upsell: function () {
        jQuery('.wbs-content').fadeOut(200);
    },
    hide_cross_sell: function () {
        jQuery('#wbs-content-cross-sells').fadeOut(200);
    },
    show_cross_sell: function () {
        jQuery('#wbs-content-cross-sells').fadeIn('slow');
        jQuery('html').addClass('wbs-html-overflow');
        this.cross_slider();
    },
    show_cross_sell_archive: function () {
        jQuery('#wbs-cross-sell-archive').fadeIn('slow');

    },
    counter: function ($el, n) {
        var checkout_url = jQuery('.vi-wbs-btn-redeem').attr('href');
        (function loop() {
            $el.html(n);
            if (n == 0) {
                if (checkout_url) {
                    window.location.href = checkout_url;
                }
            }
            if (n--) {
                time_redirect = setTimeout(loop, 1000);
            }
        })();
    },
    initial_delay_icon: function () {
        if (jQuery('#wbs-content-cross-sells').length > 0) {
            var initial_delay = jQuery('#wbs-content-cross-sells').attr('data-initial_delay');
            var open = jQuery('#wbs-content-cross-sells').attr('data-open');
            cross_sell_init = setTimeout(function () {
                jQuery('.gift-button').fadeIn('medium');
                if (open > 0) {
                    woo_boost_sale.show_cross_sell()
                }
            }, initial_delay * 1000);
        }
    },
    chosen_variable_upsell: function () {
        jQuery('select.wbs-variable').on('change', function () {
            var selected = jQuery(this).val();
            jQuery(this).closest('div.wbs-product').find('.vi-wbs-chosen').removeClass('wbs-show-variable').addClass('wbs-hidden-variable');
            jQuery(this).closest('div.wbs-product').find('.wbs-variation-' + selected).removeClass('wbs-hidden-variable').addClass('wbs-show-variable');
        });
    },
    format_number(number, decimals, decimal_separator, thousand_separator) {
        if (decimals === undefined) {
            decimals = woocommerce_boost_sales_params.decimals;
        }
        if (decimal_separator === undefined) {
            decimal_separator = woocommerce_boost_sales_params.decimal_separator;
        }
        if (thousand_separator === undefined) {
            thousand_separator = woocommerce_boost_sales_params.thousand_separator;
        }
        /*First convert number to en-US format: "," as thousand separator and "." as decimal separator*/
        number = number.toLocaleString("en-US", {
            maximumFractionDigits: decimals,
            minimumFractionDigits: decimals
        });
        /*Split to integer and decimal parts*/
        let arr = number.split('.');
        /*Replace "," with correct thousand separator*/
        number = arr[0].split(',').join(thousand_separator);
        /*Join integer part with decimal part with correct decimal separator if any*/
        if (arr.length === 2) {
            number = number + decimal_separator + arr[1];
        }
        return number;
    },
    handle_price($woocommerce_boost_sales_cross_sells, is_validate = false) {
        let $items = $woocommerce_boost_sales_cross_sells.find('.wbs-product'), total_price = 0,
            $overall = jQuery('.wbs-crosssells-overall-price'),
            $total_origin = jQuery('.wbs-total-price-origin'),
            $total_current = jQuery('.wbs-total-price-current'),
            $save_origin = jQuery('.wbs-save-price-origin'),
            $save_current = jQuery('.wbs-save-price-current'),
            saved_type = parseInt($woocommerce_boost_sales_cross_sells.data('saved_type')),
            fixed_price = parseFloat($woocommerce_boost_sales_cross_sells.data('fixed_price')),
            $atc_price = jQuery('.wbs-crosssells-atc-price');
        $items.map(function () {
            let $loop_item = jQuery(this);
            total_price += parseInt($loop_item.data('item_quantity')) * parseFloat($loop_item.data('item_price'));
        });
        if (is_validate) {
            $overall.hide();
            $total_current.html(woocommerce_boost_sales_params['modal_price'].replace(woo_boost_sale.format_number(1), woo_boost_sale.format_number(total_price))).show();
            let discount_type = $woocommerce_boost_sales_cross_sells.data('discount_type'),
                discount_amount = $woocommerce_boost_sales_cross_sells.data('discount_amount'),
                final_price = total_price, saved_amount = 0;
            if ($woocommerce_boost_sales_cross_sells.data('dynamic_price')) {
                if (discount_amount) {
                    discount_amount = parseFloat($woocommerce_boost_sales_cross_sells.data('discount_amount'));
                } else {
                    discount_amount = 0;
                }
                if (discount_type === 'percent') {
                    final_price = total_price * (1 - discount_amount / 100);
                    if (final_price < 0) {
                        final_price = 0;
                    }
                } else {
                    final_price = total_price - discount_amount;
                    if (final_price < 0) {
                        final_price = 0;
                    }
                }
            } else {
                final_price = fixed_price
            }
            final_price = parseFloat(woo_boost_sale.format_number(final_price, undefined, '.', ''));
            saved_amount = total_price - final_price;
            if (saved_type === 0) {
                $save_origin.hide();
                $save_current.html(woocommerce_boost_sales_params['modal_price'].replace(woo_boost_sale.format_number(1), woo_boost_sale.format_number(saved_amount))).show();
            } else if (saved_type === 1) {
                $save_origin.hide();
                $save_current.html(`${woo_boost_sale.format_number(saved_amount * 100 / total_price, 0)}%`).show();
            }
            $total_origin.hide();
            $atc_price.html(woocommerce_boost_sales_params['modal_price'].replace(woo_boost_sale.format_number(1), woo_boost_sale.format_number(final_price))).show();
        } else {
            $overall.show();
            $total_origin.show();
            $total_current.hide();
            if (saved_type === 0) {
                $save_origin.show();
                $save_current.hide();
            }
            $atc_price.hide();
        }

    }
};