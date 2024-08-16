<?php

add_action( 'wp_ajax_save_all_selected_products', 'beucw_to_save_all_selected_products' );
add_action( 'wp_ajax_nopriv_save_all_selected_products', 'beucw_to_save_all_selected_products' );

/**
 * To save all upsell and cross-sell product ids on submit.
 * Sanitizing a multi-dimensional array reference: https://github.com/WordPress/WordPress-Coding-Standards/issues/1660.
 */
function beucw_to_save_all_selected_products() {

	if ( isset( $_POST['nonce'] ) && current_user_can( 'edit_products' ) && isset( $_POST['selected_data'] ) && ! empty( $_POST['selected_data'] ) ) {

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ) ) {
			wp_die( esc_html__( 'Permission Denied.', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ) );
		}

		$selected_data = map_deep( wp_unslash( $_POST['selected_data'] ), 'sanitize_text_field' );

		foreach ( $selected_data as $selected_product ) {

			$product_id = intval( $selected_product['beucw_product_id'] );
			$product    = wc_get_product( $product_id );

			if ( $product ) {

				// To save all selected products for upsell.
				$upsells_product_ids = array_map( 'intval', (array) $selected_product['beucw_product_upsells_ids'] );

				// To delete current product id from array.
				$upsells_key = array_search( $product_id, $upsells_product_ids, true );

				if ( false !== $upsells_key ) {
					unset( $upsells_product_ids[ $upsells_key ] );
				}

				$product->set_upsell_ids( $upsells_product_ids );

				// To save all selected products for cross sell.
				$cross_sell_product_ids = array_map( 'intval', (array) $selected_product['beucw_product_crosssells_ids'] );

				// To delete current product id from array.
				$cross_key = array_search( $product_id, $cross_sell_product_ids, true );

				if ( false !== $cross_key ) {
					unset( $cross_sell_product_ids[ $cross_key ] );
				}

				$product->set_cross_sell_ids( $cross_sell_product_ids );

				$product->save();
			}
		}
	}
	die();
}


/**
 * To return products Ids for given category_ids.
 *
 * @param array $category_ids array of category ids.
 * @return array $product_ids .
 */
function beucw_get_category_products_ids( $category_ids ) {
	$terms_slug  = array(); // Stores all term slug.
	$product_ids = array(); // Store all product id.

	// Gets all term slug.
	foreach ( $category_ids as $category_id ) {
		$term = get_term( $category_id );
		array_push( $terms_slug, $term->slug );
	}

	// All product count with selected categories.
	$product_id_count = wc_get_products(
		array(
			'category' => $terms_slug,
			'limit'    => -1,
			'status'   => 'publish',
			'return'   => 'ids',
		)
	);

	$product_id = wc_get_products(
		array(
			'category' => $terms_slug,
			'limit'    => isset( $_POST['limitdata'] ) ? intval( $_POST['limitdata'] ) : 5,
			'offset'   => isset( $_POST['offsetdata'] ) ? intval( $_POST['offsetdata'] ) : 0,
			'status'   => 'publish',
			'return'   => 'ids',
		)
	);

	array_push( $product_ids, ...$product_id );
	return array(
		'count' => count( $product_id_count ),
		'data'  => array_unique( $product_ids ),
	);
}


/**
 * To return products Ids for given tag_ids.
 *
 * @param array $tag_ids array of tag ids.
 * @return array $product_ids .
 */
function beucw_get_tags_products_ids( $tag_ids ) {
	$terms_slug  = array(); // Stores all term slug.
	$product_ids = array(); // Store all product id.

	foreach ( $tag_ids as $tag_id ) {
		$term = get_term( $tag_id );
		array_push( $terms_slug, $term->slug );
	}

	// All product count with selected categories.
	$product_id_count = wc_get_products(
		array(
			'tag'    => $terms_slug,
			'limit'  => -1,
			'status' => 'publish',
			'return' => 'ids',
		)
	);

	// 10 product per page.
	$product_id = wc_get_products(
		array(
			'tag'    => $terms_slug,
			'limit'  => isset( $_POST['limitdata'] ) ? intval( $_POST['limitdata'] ) : 5,
			'offset' => isset( $_POST['offsetdata'] ) ? intval( $_POST['offsetdata'] ) : 0,
			'status' => 'publish',
			'return' => 'ids',
		)
	);

	array_push( $product_ids, ...$product_id );
	return array(
		'count' => count( $product_id_count ),
		'data'  => array_unique( $product_ids ),
	);
}


/**
 * To return products Ids for given tag_ids.
 *
 * @param array $product_ids array of product ids.
 * @return array $product_ids .
 */
function beucw_get_products_ids_products( $product_ids ) {
	$product_id_array = array(); // Stores all product id.

	foreach ( $product_ids as $product_id ) {
		array_push( $product_id_array, $product_id );
	}

	$product_with_limits = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => isset( $_POST['limitdata'] ) ? intval( $_POST['limitdata'] ) : 5,
		'post__in'       => array_unique( $product_ids ),
		'offset'         => isset( $_POST['offsetdata'] ) ? intval( $_POST['offsetdata'] ) : 0,
		'fields'         => 'ids',
	);

	$products_posts = new WP_Query( $product_with_limits );

	return array(
		'count' => count( array_unique( $product_id_array ) ),
		'data'  => array_unique( $products_posts->posts ),
	);
}


// Showing products in select boxes.

/**
 * All products in required format (only simple).
 *
 * @return array
 */
function beucw_get_all_products_with_variations() {
	$all_products = array();

	$products_id = wc_get_products(
		array(
			'limit'  => -1,  // All products.
			'status' => 'publish', // Only published products.
			'return' => 'ids',
		)
	);

	foreach ( $products_id as $product_id ) {
		$product = wc_get_product( $product_id );

		// All simple products.
		$all_products[] = array(
			'product_id' => intval( $product_id ),
			'label'      => 'ID:' . esc_attr( $product_id ) . ' ' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ),
		);

		// All variation product.
		if ( $product->is_type( 'variable' ) ) {

			foreach ( $product->get_children() as $variation_id ) {
				$variation      = wc_get_product( $variation_id );
				$all_products[] = array(
					'product_id' => intval( $variation_id ),
					'label'      => 'ID:' . esc_attr( $variation_id ) . ' ' . esc_html( wp_strip_all_tags( $variation->get_formatted_name() ) ),
				);

			}
		}
	}

	return $all_products;
}


/**
 * All products in required format.
 *
 * @return array $all_products .
 */
function beucw_get_all_products() {
	$all_products = array();
	$products_id  = wc_get_products(
		array(
			'limit'  => -1,                      // All products.
			'status' => 'publish',              // Only published products.
			'return' => 'ids',
		)
	);

	foreach ( $products_id as $product_id ) {
		$product = wc_get_product( $product_id );

		$all_products[] = array(
			'product_id' => intval( $product_id ),
			'label'      => 'ID:' . esc_attr( $product_id ) . ' ' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ),
		);
	}

	return $all_products;
}

/**
 * To return all categories names with ID for select2 options .
 *
 * @return string $categories_option .
 */
function beucw_select2_get_all_categories() {

	$categories_option  = '';
	$args               = array(
		'taxonomy'   => 'product_cat',
		'orderby'    => 'name',
		'order'      => 'ASC',
		'hide_empty' => true,
	);
	$product_categories = get_terms( $args );
	foreach ( $product_categories as $category ) {
		$category_slug      = $category->slug ? ' ( ' . $category->slug . ' )' : false;
		$categories_option .= '<option value="' . esc_attr( intval( $category->term_id ) ) . '">' . esc_attr( $category->name . ' ' . $category_slug ) . '</option >';
	}
	return $categories_option;
}

/**
 * To return all tags names with ID for select2 options .
 *
 * @return string $tags_option .
 */
function beucw_select2_get_all_tags() {
	$tags_option = '';
	$args        = array(
		'taxonomy'   => 'product_tag',
		'orderby'    => 'name',
		'order'      => 'ASC',
		'hide_empty' => true,

	);
	$product_tags = get_terms( $args );
	$all_tags     = array();
	foreach ( $product_tags as $tag ) {
		$tag_id       = strval( $tag->term_id );
		$all_tags[]   = array(
			'tag_id' => intval( $tag_id ),
			'label'  => 'ID:' . esc_attr( $tag->term_id ) . ' ' . esc_attr( $tag->name ),
		);
		$tags_option .= '<option value="' . esc_attr( intval( $tag->term_id ) ) . '">ID:' . esc_attr( $tag->term_id ) . ' ' . esc_attr( $tag->name ) . '</option >';
	}
	return $tags_option;
}


/**
 * To return all products SKU for select2 options.
 * Todo -- add post_status "Publish"
 *
 * @return string $sku_option .
 */
function beucw_select2_get_all_product_sku() {

	$sku_option  = '';
	$products_id = wc_get_products(
		array(
			'limit'  => -1,                      // All products.
			'status' => 'publish',              // Only published products.
			'return' => 'ids',
		)
	);

	foreach ( $products_id as $product_id ) {
		$product     = wc_get_product( $product_id );
		$sku_option .= '<option value="' . intval( $product_id ) . '">ID:' . esc_attr( $product_id ) . ' ' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option >';
	}

	return $sku_option;
}


/**
 * Get the related products.
 *
 * @param int $product_id .
 * @return array
 */
function beucw_get_related_products( $product_id ) {
	$related_products_ids = get_post_meta( $product_id, 'related_products_individual_select' );
	return $related_products_ids;
}
