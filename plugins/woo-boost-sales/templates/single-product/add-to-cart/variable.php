<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings       = VI_WOO_BOOSTSALES_Data::get_instance();
$attribute_keys = array_keys( $attributes );
?>
<form class="wbs-variations_form cart" action="" method="post"
      enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>"
      data-product_variations="<?php echo esc_attr( htmlspecialchars( wp_json_encode( $available_variations ) ) ) ?>">
	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
        <p class="stock out-of-stock"><?php esc_html_e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
	<?php else : ?>
        <table class="variations" cellspacing="0">
            <tbody>
			<?php foreach ( $attributes as $attribute_name => $options ) : ?>
                <tr>
                    <td class="label"><label
                                for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
                    </td>
                    <td class="value">
						<?php
						$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
						wbs_wc_dropdown_variation_attribute_options( array(
							'options'          => $options,
							'attribute'        => $attribute_name,
							'product'          => $product,
							'selected'         => $selected,
							'show_option_none' => sprintf( esc_html__( 'Choose %s', 'woo-boost-sales' ), wc_attribute_label( $attribute_name ) )
						) );
						echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) : '';
						?>
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>

        <div class="single_variation_wrap">
			<?php


			/**
			 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
			 * @since 2.4.0
			 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
			 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
			 */
			do_action( 'woocommerce_boost_sales_single_variation', $product );

			?>
        </div>
	<?php endif; ?>

</form>

