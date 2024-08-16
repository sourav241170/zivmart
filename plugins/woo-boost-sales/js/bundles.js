jQuery(document).ready(function ($) {
    'use strict';
    jQuery('.wbs-wcpb-bundled-items.ui-sortable').sortable();
    var bundled_items_cont = jQuery('#wbs_bundled_product_data .wbs-wcpb-bundled-items'),
        add_bundled_product_btn = jQuery('#wbs-wcpb-add-bundled-product'),
        b_prod_id = jQuery('#wbs-wcpb-bundled-product'),
        items_count = jQuery('#wbs_bundled_product_data .wbs-wcpb-bundled-items .wbs-wcpb-bundled-item').length,
        bundled_product_data_container = jQuery('#wbs_bundled_product_data');
    items_count++;
    add_bundled_product_btn.on('click', function () {
        if (b_prod_id.val() == 0) {
            return;
        }

        var data = {
            action: 'wbs_wcpb_add_product_in_bundle',
            open_closed: 'open',
            post_id: woocommerce_admin_meta_boxes.post_id,
            id: items_count,
            product_id: b_prod_id.val(),
        };

        jQuery.post(woocommerce_admin_meta_boxes.ajax_url, data, function (response) {
            if (response === 'notsimple') {
                alert(ajax_object.free_not_simple);
                bundled_product_data_container.unblock();
                return;
            }
            bundled_items_cont.append(response);
            bundled_items_cont.find('.help_tip').tipTip();
            bundled_product_data_container.unblock();
            b_prod_id.val(0);
            items_count++;
        });
    });

    jQuery('body').on('click', '.wbs-wcpb-remove-bundled-product-item', function () {
        if (confirm('Remove this item from bundle?')) {
            jQuery(this).parent().parent().remove();
        }
    });
});