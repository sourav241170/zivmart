jQuery(document).ready(function ($) {
    'use strict';
    /*Set paged to 1 before submitting*/
    let is_current_page_focus = false;
    $('.tablenav-pages').find('.current-page').on('focus', function (e) {
        is_current_page_focus = true;
    }).on('blur', function (e) {
        is_current_page_focus = false;
    });
    $('.search-box').find('input[type="submit"]').on('click', function () {
        let $form = $(this).closest('form');
        if (!is_current_page_focus) {
            $form.find('.current-page').val(1);
        }
    });
    $(".wbs-category-search").select2({
        closeOnSelect: false,
        placeholder: "Please fill in your category title",
        ajax: {
            url: "admin-ajax.php?action=wbs_search_category_excl",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1
    });

    $(".product-search").select2({
        closeOnSelect: false,
        placeholder: "Please enter product title",
        ajax: {
            url: "admin-ajax.php?action=wbs_search_product",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term,
                    p_id: $(this).closest('td').data('id')
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1
    });
    let upsells_products = {};
    $('.product-search').next(".select2-container").find('ul.select2-selection__rendered').sortable({
        containment: 'parent',
        stop: function (event, ui) {
            var product_id = $(this).closest('tr').find('.column-action').data('id');
            // event target would be the <ul> which also contains a list item for searching (which has to be excluded)
            var arr = Array.from($(event.target).find('li:not(.select2-search)').map(function () {
                return $(this).data('data').id;
            }));
            upsells_products[product_id] = arr;
        }
    });
    let upsells_categories = {};
    $('.wbs-category-search').next(".select2-container").find('ul.select2-selection__rendered').sortable({
        containment: 'parent',
        stop: function (event, ui) {
            var product_id = $(this).closest('tr').find('.column-action').data('id');
            // event target would be the <ul> which also contains a list item for searching (which has to be excluded)
            var arr = Array.from($(event.target).find('li:not(.select2-search)').map(function () {
                return $(this).data('data').id;
            }));
            upsells_categories[product_id] = arr;
        }
    });
    /*Save Up sell*/
    $('.button-save').on('click', function () {
        var product_id = $(this).closest('td').data('id');
        var btn = $(this);
        if (product_id) {
            var u_id;
            var u_cate_ids;
            if (upsells_products.hasOwnProperty(product_id)) {
                u_id = upsells_products[product_id];
            } else {
                u_id = $('select.u-product-' + product_id).val();
            }
            if (upsells_categories.hasOwnProperty(product_id)) {
                u_cate_ids = upsells_categories[product_id];
            } else {
                u_cate_ids = $('select.u-categories-' + product_id).val();
            }
            btn.text('Saving');
            $.ajax({
                type: 'POST',
                data: {
                    action: 'wbs_u_save_product',
                    id: product_id,
                    u_id: u_id,
                    u_cate_ids: u_cate_ids,
                },
                url: wbs_upsell_admin_params.url,
                success: function (obj) {
                    if (obj.check == 'done') {
                        btn.text('Save');
                        btn.removeClass('button-primary');
                    } else {

                    }
                },
                error: function (html) {
                }
            })
        } else {
            return false;
        }
    });
    /*Remove all*/
    $('.button-remove').on('click', function () {
        var r = confirm("Your products in up-sells of selected product will be removed all. Are you sure ?");
        if (r == true) {
            var product_id = $(this).closest('td').data('id');

            var btn = $(this);
            if (product_id) {
                btn.text('Removing');
                $.ajax({
                    type: 'POST',
                    data: 'action=wbs_u_remove_product' + '&id=' + product_id,
                    url: wbs_upsell_admin_params.url,
                    success: function (html) {
                        var obj = $.parseJSON(html);
                        if (obj.check == 'done') {
                            btn.text('Remove all');
                            $('select.u-product-' + product_id).val('null').trigger("change");
                        } else {

                        }
                    },
                    error: function (html) {
                    }
                })
            } else {
                return false;
            }
        }
    });
    /*Action after selected product*/
    $('.product-search').on("select2:selecting", function (e) {
        // what you would like to happen
        var p_id = $(this).closest('td').data('id');
        $('.product-action-' + p_id).find('.button-save').addClass('button-primary');
    });
    /*Action after remove product*/
    $('.product-search').on("select2:unselecting", function (e) {
        var p_id = $(this).closest('td').data('id');
        $('.product-action-' + p_id).find('.button-save').addClass('button-primary');
    });
    /*Remove all*/
    $('.btn-sync-upsell').on('click', function () {
        if (confirm('Create Up-sells to use with Boost Sales for WooCommerce plugin from Up-sells data in WooCommerce single product settings. Continue?')) {
            var btn = $(this);
            btn.text('Syncing');
            $.ajax({
                type: 'POST',
                data: 'action=wbs_u_sync_product',
                url: wbs_upsell_admin_params.url,
                success: function (html) {
                    var obj = $.parseJSON(html);
                    if (obj.check == 'done') {
                        btn.text('Get Product Up-Sells');
                        reload_cache();
                    } else {

                    }
                },
                error: function (html) {
                }
            })
        }
    });

    function reload_cache() {
        $('.product-search').trigger('change');
        location.reload();
    }

    var wbs_different_up = $('#wbs_different_up-cross-sell').data('wbs_up_crosssell');
    $(document).tooltip({
        items: "#wbs_different_up-cross-sell",
        position: {
            my: "right top+10"
        },
        track: true,
        content: '<img class="wbs_img_tooltip_dfc" src="' + wbs_different_up + '" width="700px" style="float: left; margin-left: 180px;" />',
        show: {
            effect: "slideDown",
            delay: 150
        }
    });
    $('.wbs-upsells-ajax-enable').on('click', function () {
        $.ajax({
            type: 'POST',
            url: wbs_upsell_admin_params.url,
            data: {
                action: 'wbs_ajax_enable_upsell',
                nonce: $('#_wsm_nonce').val(),
            },
            success: function (response) {
            },
            error: function (err) {
            },
            complete: function (err) {
                $('.wbs-upsells-ajax-enable').closest('.error').fadeOut(300);
            }
        });
    })
});