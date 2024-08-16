<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Admin_ZCrosssell {
	protected $settings;

	public function __construct() {
		$this->settings = VI_WOO_BOOSTSALES_Data::get_instance();
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'set-screen-option', array( $this, 'save_screen_options' ), 10, 3 );
		add_action( 'wp_ajax_wbs_search_product_crs', array( $this, 'wbs_search_product_crs' ) );
		add_action( 'wp_ajax_wbs_c_save_product', array( $this, 'wbs_c_save_product' ) );
		add_action( 'wp_ajax_wbs_update_product', array( $this, 'wbs_update_product' ) );
		add_action( 'wp_ajax_wbs_c_remove_product', array( $this, 'wbs_c_remove_product' ) );
		add_action( 'wp_ajax_wbs_u_create_bundle_from_crosssells', array(
			$this,
			'wbs_u_create_bundle_from_crosssells'
		) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99999 );

		add_action( 'admin_init', array( $this, 'cross_sells_data_update' ), 90 );
		add_action( 'wp_ajax_wbs_ajax_enable_crosssell', array( $this, 'ajax_enable_crosssell' ) );
	}

	public function wbs_u_create_bundle_from_crosssells() {
		global $wp_error;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), '_wbs_cross_sells_search' ) ) {
			$user         = wp_get_current_user();
			$current_user = $user->get( 'ID' );
			$paged        = 1;
			while ( true ) {
				$args      = array(
					'post_status'    => VI_WOO_BOOSTSALES_Data::search_product_statuses(),
					'post_type'      => 'product',
					'posts_per_page' => 50,
					'paged'          => $paged
				);
				$the_query = new WP_Query( $args );
				// The Loop
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$p_id = get_the_ID();
						// Do Stuff
						$woo_c_ids      = get_post_meta( $p_id, '_crosssell_ids', true );
						$created_bundle = get_post_meta( $p_id, '_wbs_crosssells', true );
						if ( is_array( $created_bundle ) && count( $created_bundle ) && ! in_array( $p_id, $created_bundle ) ) {
							continue;
						}

						if ( is_array( $woo_c_ids ) && count( $woo_c_ids ) ) {
							$product_bundle_name = $this->settings->get_option( 'product_bundle_name' ) ? $this->settings->get_option( 'product_bundle_name' ) : 'Bundle of {product_title}';
							$product_name        = str_replace( '{product_title}', esc_html( get_post_field( 'post_title', $p_id ) ), $product_bundle_name );
							$woo_b_ids           = array_unique( array_merge( array( $p_id ), $woo_c_ids ) );
							$arry_pa             = array();
							$get_prices          = array();
							foreach ( $woo_b_ids as $absr => $pa_id ) {
								$indiv_product = wc_get_product( $pa_id );
								$get_prices[]  = $indiv_product->get_price();
								$step          = $absr + 1;
								$arry          = array(
									'bundle_order' => $step,
									'product_id'   => $pa_id,
									'bp_quantity'  => 1
								);
								$arry_pa[]     = $arry;
							}
							$total_price     = array_sum( $get_prices );
							$post_individual = array(
								'post_author'  => $current_user,
								'post_content' => '',
								'post_status'  => 'publish',
								'post_title'   => $product_name,
								'post_parent'  => '',
								'post_type'    => "product",
							);

							$post_id = wp_insert_post( $post_individual, $wp_error );
							if ( $post_id ) {
								$attach_id = get_post_meta( $p_id, "_thumbnail_id", true );
								add_post_meta( $post_id, '_thumbnail_id', $attach_id );
							}
							wp_set_object_terms( $post_id, 'wbs_bundle', 'product_type' );
							update_post_meta( $post_id, '_visibility', 'hidden' );
							update_post_meta( $post_id, '_stock_status', 'instock' );
							update_post_meta( $post_id, 'total_sales', '0' );
							update_post_meta( $post_id, '_downloadable', 'no' );
							update_post_meta( $post_id, '_virtual', 'yes' );
							update_post_meta( $post_id, '_regular_price', $total_price );
							update_post_meta( $post_id, '_sale_price', '' );
							update_post_meta( $post_id, '_purchase_note', '' );
							update_post_meta( $post_id, '_featured', 'no' );
							update_post_meta( $post_id, '_weight', '' );
							update_post_meta( $post_id, '_length', '' );
							update_post_meta( $post_id, '_width', '' );
							update_post_meta( $post_id, '_height', '' );
							update_post_meta( $post_id, '_sku', '' );
							update_post_meta( $post_id, '_product_attributes', array() );
							update_post_meta( $post_id, '_sale_price_dates_from', '' );
							update_post_meta( $post_id, '_sale_price_dates_to', '' );
							update_post_meta( $post_id, '_price', $total_price );
							update_post_meta( $post_id, '_sold_individually', '' );
							update_post_meta( $post_id, '_manage_stock', 'no' );
							update_post_meta( $post_id, '_backorders', 'no' );
							update_post_meta( $post_id, '_stock', '' );
							$product_new = wc_get_product( $post_id );
							$terms       = array( 'exclude-from-search', 'exclude-from-catalog' );

							if ( ! is_wp_error( wp_set_post_terms( $post_id, $terms, 'product_visibility', false ) ) ) {
								delete_transient( 'wc_featured_products' );
								do_action( 'woocommerce_product_set_visibility', $post_id, $product_new->get_catalog_visibility() );
							}

							if ( count( $arry_pa ) ) {
								update_post_meta( $post_id, '_wbs_wcpb_bundle_data', $arry_pa );
								update_post_meta( $p_id, '_wbs_crosssells', array( $post_id ) );
							}
						}
					}
				} else {
					break;
				}

				$paged ++;
				wp_reset_postdata();
			}
			$msg['check'] = 'done';
			echo json_encode( $msg );
			die;
		}
	}

	public function ajax_enable_crosssell() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		global $wbs_settings;
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), '_wbs_cross_sells_search' ) ) {
			$wbs_settings['enable']           = 1;
			$wbs_settings['crosssell_enable'] = 1;
			update_option( '_woocommerce_boost_sales', $wbs_settings );
		}
		die;
	}

	/**
	 * @throws WC_Data_Exception
	 */
	function cross_sells_data_update() {
		$dismiss_opt = get_option( 'dismiss_update_crsells' );
		if ( empty( $dismiss_opt ) || $dismiss_opt != '1' ) {
			$user_id   = get_current_user_id();
			$arg_first = array(
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'posts_per_page' => - 1,
				'meta_query'     => array(
					array(
						'key'     => '_wbs_crosssells',
						'value'   => '',
						'compare' => '!='
					)
				)
			);
			$get_post  = get_posts( $arg_first );

			if ( count( $get_post ) ) {
				$array2 = array();
				foreach ( $get_post as $list_id ) {
					$get_pmt_cross     = get_post_meta( $list_id->ID, '_wbs_crosssells' );
					$array2['id'][]    = $list_id->ID;
					$array2['value'][] = $get_pmt_cross[0][0];
				}

				$duplicate_element = array_unique( array_diff_assoc( $array2['value'], array_unique( $array2['value'] ) ) );
				if ( count( $duplicate_element ) ) {
					foreach ( $duplicate_element as $key_sep => $separate_e ) {
						$dupp = $array2['id'][ $key_sep ];
						$prda = wc_get_product( $separate_e );

						if ( get_post_status( $separate_e ) == 'publish' ) {
							$meta_to_exclude = array_filter( apply_filters( 'woocommerce_duplicate_product_exclude_meta', array() ) );
							$duplicate       = clone $prda;
							$duplicate->set_id( 0 );
							$duplicate->set_name( sprintf( __( '%s (Copy)', 'woo-boost-sales' ), $duplicate->get_name() ) );
							$duplicate->set_total_sales( 0 );
							if ( '' !== $prda->get_sku( 'edit' ) ) {
								$duplicate->set_sku( wc_product_generate_unique_sku( 0, $prda->get_sku( 'edit' ) ) );
							}
							$duplicate->set_status( 'publish' );
							$duplicate->set_date_created( null );
							$duplicate->set_slug( '' );
							$duplicate->set_rating_counts( 0 );
							$duplicate->set_average_rating( 0 );
							$duplicate->set_review_count( 0 );

							foreach ( $meta_to_exclude as $meta_key ) {
								$duplicate->delete_meta_data( $meta_key );
							}

							// This action can be used to modify the object further before it is created - it will be passed by reference. @since 3.0
							do_action( 'woocommerce_product_duplicate_before_save', $duplicate, $prda );

							// Save parent product.
							$duplicate->save();
							$dup_id = $duplicate->get_id();
							update_post_meta( $dupp, '_wbs_crosssells', array( $dup_id ) );
							add_user_meta( $user_id, 'dismiss_cross_sells_data_update', 'true', true );

						}
					}
				}
			}
			update_option( 'dismiss_update_crsells', 1 );
		}
	}

	/**
	 * Get all cross sells product chosen
	 */
	public function get_crs_select( $p_id ) {
		global $wpdb;
		$prds = wc_get_product( $p_id );
		if ( $prds->has_child() && $prds->get_type() == 'variable' ) {
			$children = $prds->get_children();
			if ( count( $children ) ) {
				foreach ( $children as $child ) {
					$sql_parent    = $wpdb->prepare( "SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_wbs_wcpb_bundle_data' AND meta_value LIKE '%s'", '%' . $child . '%' );
					$result_parent = $wpdb->get_results( $sql_parent, OBJECT );

					if ( ! $result_parent ) {
						continue;
					} else {
						foreach ( $result_parent as $post_id ) {
							$array_pid  = (array) $post_id;
							$get_status = get_post_status( $array_pid['post_id'] );
							$get_pid    = get_post_meta( $array_pid['post_id'], '_wbs_wcpb_bundle_data' );

							if ( is_array( $get_pid ) && $get_status == 'publish' ) {
								if ( count( array_filter( $get_pid ) ) ) {
									foreach ( $get_pid as $items ) {
										foreach ( $items as $item ) {
											if ( in_array( $p_id, $item ) ) {
												return $array_pid['post_id'];
											}
										}
									}
								}
							} else {
								return 0;
							}
						}
					}
				}
			}
		}

		$sql    = $wpdb->prepare( "SELECT post_id FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_wbs_wcpb_bundle_data' AND meta_value LIKE '%s'", '%' . $p_id . '%' );
		$result = $wpdb->get_results( $sql, OBJECT );
		if ( ! $result ) {
			return 0;
		} else {
			foreach ( $result as $post_id ) {
				$array_pid   = (array) $post_id;
				$get_status2 = get_post_status( $array_pid['post_id'] );
				$get_pid     = get_post_meta( $array_pid['post_id'], '_wbs_wcpb_bundle_data' );

				if ( is_array( $get_pid ) && $get_status2 == 'publish' ) {
					if ( count( array_filter( $get_pid ) ) ) {
						foreach ( $get_pid as $items ) {
							foreach ( $items as $item ) {
								if ( in_array( $p_id, $item ) ) {
									return $array_pid['post_id'];
								}
							}
						}
					}
				} else {
					return 0;
				}
			}
		}

	}

	/**
	 * Get product bundle from id
	 */
	protected function get_product_bundle_from_id( $p_id ) {
		$array_wbs_bundle = array( $p_id );
		$arg_first        = array(
			'post_status'    => 'publish',
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'meta_query'     => array(
				array(
					'key'     => '_wbs_wcpb_bundle_data',
					'value'   => '',
					'compare' => '!='
				)
			)
		);
		$post_alls        = get_posts( $arg_first );
		if ( count( $post_alls ) ) {
			foreach ( $post_alls as $post_all ) {
				$meta_a = get_post_meta( $post_all->ID, '_wbs_wcpb_bundle_data' );
				if ( count( $meta_a ) ) {
					foreach ( $meta_a as $meta_b ) {
						foreach ( $meta_b as $all_items ) {
							$array_wbs_bundle[] = $all_items['product_id'];
						}
					}
				}
			}
		}

		return $array_wbs_bundle;
	}

	/**
	 * Select 2 Search ajax
	 */
	public function wbs_search_product_crs() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();

		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
		//		$p_id    = filter_input( INPUT_GET, 'p_id', FILTER_SANITIZE_STRING );

		if ( empty( $keyword ) ) {
			die();
		}

		$arg            = array(
			'post_status'    => VI_WOO_BOOSTSALES_Data::search_product_statuses(),
			'post_type'      => 'product',
			'posts_per_page' => 50,
			's'              => $keyword,
			//			'post__not_in'   => array( $p_id ),
			'tax_query'      => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'wbs_bundle',
					'operator' => 'NOT IN'
				),
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array( 'simple', 'variable', 'subscription', 'variable-subscription', 'member' ),
					'operator' => 'IN'
				),
			)
		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$get_wc_product = wc_get_product( get_the_ID() );

				if ( $get_wc_product->has_child() && $get_wc_product->get_type() == 'variable' ) {
					if ( $get_wc_product->is_in_stock() ) {
						$product = array(
							'id'   => get_the_ID(),
							'text' => get_the_title() . ' (#' . get_the_ID() . ') (#VARIABLE) '
						);
					} else {
						$product = array(
							'id'   => get_the_ID(),
							'text' => get_the_title() . ' (#' . get_the_ID() . ') (#VARIABLE)(' . esc_html__( 'Out of stock', 'woo-boost-sales' ) . ')'
						);
					}

					$found_products[]  = $product;
					$children_variable = $get_wc_product->get_children();
					foreach ( $children_variable as $child ) {
						$product_child = wc_get_product( $child );
						if ( $product_child->is_in_stock() ) {
							$product = array(
								'id'   => $child,
								'text' => $product_child->get_name() . ' (#' . $child . ')'
							);
						} else {
							$product = array(
								'id'   => $child,
								'text' => $product_child->get_name() . ' (#' . $child . ')(' . esc_html__( 'Out of stock', 'woo-boost-sales' ) . ')'
							);
						}
						$found_products[] = $product;
					}
				} else {
					if ( $get_wc_product->is_in_stock() ) {
						$product = array(
							'id'   => get_the_ID(),
							'text' => get_the_title() . ' (#' . get_the_ID() . ')'
						);
					} else {
						$product = array(
							'id'   => get_the_ID(),
							'text' => get_the_title() . ' (#' . get_the_ID() . ')(' . esc_html__( 'Out of stock', 'woo-boost-sales' ) . ')'
						);
					}
					$found_products[] = $product;
				}
			}
		}
		// Reset Post Data
		wp_reset_postdata();
		wp_send_json( $found_products );
		die;
	}

	/**
	 * Remove all Cross-sell
	 */
	public function wbs_c_remove_product() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();
		$p_id              = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING );
		$product_bundle_id = filter_input( INPUT_POST, 'product_bundle_id', FILTER_SANITIZE_STRING );
		$msg               = array();

		if ( empty( $p_id ) ) {
			die();
		}
		update_post_meta( $p_id, '_wbs_crosssells_bundle', '' );
		if ( $product_bundle_id ) {
			wp_delete_post( $product_bundle_id );
			update_post_meta( $p_id, '_wbs_crosssells', '' );
			$arg       = array(
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'posts_per_page' => - 1,
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'   => '_wbs_crosssells_bundle',
						'value' => $product_bundle_id,
					),
				)
			);
			$the_query = new WP_Query( $arg );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					update_post_meta( get_the_ID(), '_wbs_crosssells_bundle', '' );
				}
			}
			wp_reset_postdata();
		}

		$msg['check'] = 'done';
		ob_clean();
		echo json_encode( $msg );
		die;
	}

	/**
	 * Save cross sells
	 */
	public function wbs_c_save_product() {
		global $wp_error;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$user         = wp_get_current_user();
		$current_user = $user->get( 'ID' );
		ob_start();

		$p_id              = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING );
		$c_id              = filter_input( INPUT_POST, 'c_id', FILTER_SANITIZE_STRING );
		$product_bundle_id = filter_input( INPUT_POST, 'product_bundle_id', FILTER_SANITIZE_STRING );
		$other_bundle_id   = isset( $_POST['other_bundle_id'] ) ? sanitize_text_field( $_POST['other_bundle_id'] ) : '';
		$msg               = array();

		if ( empty( $p_id ) ) {
			die;
		}

		update_post_meta( $p_id, '_wbs_crosssells_bundle', $other_bundle_id );
		if ( ( empty( $c_id ) || $c_id == 'null' ) ) {
			if ( $other_bundle_id ) {
				$msg['check'] = 'done';
				ob_clean();

				echo json_encode( $msg );
				die;
			} else {
				/*delete_post_meta( $product_bundle_id, '_wbs_wcpb_bundle_data' );
			$post3 = array( 'ID' => $product_bundle_id, 'post_status' => 'draft' );
			wp_update_post( $post3 );*/
				$msg['check'] = 'wrong';
				ob_clean();

				echo json_encode( $msg );
				die;
			}
		}
		$c_id = array_filter( explode( ',', $c_id ) );
		if ( count( $c_id ) ) {
			$b_ids      = array_unique( array_merge( array( $p_id ), $c_id ) );
			$arry_pa    = array();
			$get_prices = array();
			foreach ( $b_ids as $absr => $pa_id ) {
				$indiv_product = wc_get_product( $pa_id );
				$get_prices[]  = $indiv_product->get_price();
				$step          = $absr + 1;
				$arry          = array(
					'bundle_order' => $step,
					'product_id'   => $pa_id,
					'bp_quantity'  => 1
				);
				$arry_pa[]     = $arry;
			}
			$total_price = array_sum( $get_prices );
			$new_bundle  = true;
			if ( $product_bundle_id ) {
				$product_bundle_obj = function_exists( 'wc_get_product' ) ? wc_get_product( $product_bundle_id ) : new WC_Product( $product_bundle_id );
				if ( $product_bundle_obj && $product_bundle_obj->is_type( 'wbs_bundle' ) ) {
					$new_bundle = false;
				}
			}

			if ( $new_bundle ) {
				$product_bundle_name = $this->settings->get_option( 'product_bundle_name' ) ? $this->settings->get_option( 'product_bundle_name' ) : 'Bundle of {product_title}';
				$product_name        = str_replace( '{product_title}', esc_html( get_post_field( 'post_title', $p_id ) ), $product_bundle_name );
				$post_individual     = array(
					'post_author'  => $current_user,
					'post_content' => '',
					'post_status'  => 'publish',
					'post_title'   => $product_name,
					'post_parent'  => '',
					'post_type'    => "product",
				);

				$post_id = wp_insert_post( $post_individual, $wp_error );
				if ( $post_id ) {
					$attach_id = get_post_meta( $p_id, "_thumbnail_id", true );
					add_post_meta( $post_id, '_thumbnail_id', $attach_id );
				}
				wp_set_object_terms( $post_id, 'wbs_bundle', 'product_type' );
				update_post_meta( $post_id, '_visibility', 'hidden' );
				update_post_meta( $post_id, '_stock_status', 'instock' );
				update_post_meta( $post_id, 'total_sales', '0' );
				update_post_meta( $post_id, '_downloadable', 'no' );
				update_post_meta( $post_id, '_virtual', 'yes' );
				update_post_meta( $post_id, '_regular_price', $total_price );
				update_post_meta( $post_id, '_sale_price', '' );
				update_post_meta( $post_id, '_purchase_note', '' );
				update_post_meta( $post_id, '_featured', 'no' );
				update_post_meta( $post_id, '_weight', '' );
				update_post_meta( $post_id, '_length', '' );
				update_post_meta( $post_id, '_width', '' );
				update_post_meta( $post_id, '_height', '' );
				update_post_meta( $post_id, '_sku', '' );
				update_post_meta( $post_id, '_product_attributes', array() );
				update_post_meta( $post_id, '_sale_price_dates_from', '' );
				update_post_meta( $post_id, '_sale_price_dates_to', '' );
				update_post_meta( $post_id, '_price', $total_price );
				update_post_meta( $post_id, '_sold_individually', '' );
				update_post_meta( $post_id, '_manage_stock', 'no' );
				update_post_meta( $post_id, '_backorders', 'no' );
				update_post_meta( $post_id, '_stock', '' );
				$product_new = wc_get_product( $post_id );
				$terms       = array( 'exclude-from-search', 'exclude-from-catalog' );
				if ( ! is_wp_error( wp_set_post_terms( $post_id, $terms, 'product_visibility', false ) ) ) {
					delete_transient( 'wc_featured_products' );
					do_action( 'woocommerce_product_set_visibility', $post_id, $product_new->get_catalog_visibility() );
				}

				if ( count( $arry_pa ) ) {
					update_post_meta( $post_id, '_wbs_wcpb_bundle_data', $arry_pa );
					update_post_meta( $p_id, '_wbs_crosssells', array( $post_id ) );
				}
				$msg['check'] = 'done';
			} else {
				if ( count( $arry_pa ) ) {
					$post2 = array( 'ID' => $product_bundle_id, 'post_status' => 'publish' );
					wp_update_post( $post2 );
					update_post_meta( $product_bundle_id, '_wbs_wcpb_bundle_data', $arry_pa );
					update_post_meta( $p_id, '_wbs_crosssells', array( $product_bundle_id ) );
					update_post_meta( $product_bundle_id, '_regular_price', $total_price );
					update_post_meta( $product_bundle_id, '_price', $total_price );
				}
				$msg['check'] = 'done';
			}

		} else {
			$msg['check'] = 'error';
		}
		ob_clean();

		echo json_encode( $msg );
		die;
	}

	/**
	 * Update product bundle
	 */
	public function wbs_update_product() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();

		$p_id  = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING );
		$title = filter_input( INPUT_POST, 'title', FILTER_SANITIZE_STRING );
		$price = filter_input( INPUT_POST, 'price', FILTER_SANITIZE_STRING );
		if ( empty( $p_id ) ) {
			die;
		}

		$post_bundle = array(
			'ID'         => $p_id,
			'post_title' => $title
		);

		wp_update_post( $post_bundle, true );
		update_post_meta( $p_id, '_price', $price );
		update_post_meta( $p_id, '_regular_price', $price );
		update_post_meta( $p_id, '_sale_price', '' );
		$msg = array();
		if ( ! is_wp_error( $p_id ) ) {
			$msg['check'] = 'done';
		} else {
			$msg['check'] = 'wrong';
			$errors       = $p_id->get_error_messages();
			foreach ( $errors as $error ) {
				$msg['detail_err'] = $error;
			}
		}

		ob_clean();
		echo json_encode( $msg );
		die;
	}

	/**
	 * Init scripts
	 */
	public function enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $page == 'woo-boost-sales-crosssell' ) {
			global $wp_scripts, $wp_styles;
			$scripts = $wp_scripts->registered;

			foreach ( $scripts as $k => $script ) {
				preg_match( '/select2/i', $k, $result );
				if ( count( array_filter( $result ) ) ) {
					unset( $wp_scripts->registered[ $k ] );
					wp_dequeue_script( $script->handle );
				}
				preg_match( '/bootstrap/i', $k, $result );
				if ( count( array_filter( $result ) ) ) {
					unset( $wp_scripts->registered[ $k ] );
					wp_dequeue_script( $script->handle );
				}
			}
			wp_enqueue_style( 'select2', VI_WOO_BOOSTSALES_CSS . 'select2.min.css' );
			wp_enqueue_script( 'select2-v4', VI_WOO_BOOSTSALES_JS . 'select2.js', array( 'jquery' ), '4.0.3' );
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_style( 'woo-boost-sales-crosssell-admin', VI_WOO_BOOSTSALES_CSS . 'crosssell-admin.css', array(), VI_WOO_BOOSTSALES_VERSION );
			wp_enqueue_script( 'woo-boost-sales-crosssell-admin', VI_WOO_BOOSTSALES_JS . 'woo-boost-sales-crosssell-admin.js', array( 'jquery' ), VI_WOO_BOOSTSALES_VERSION );
			wp_localize_script( 'woo-boost-sales-crosssell-admin', 'wbs_crosssell_admin_params', array(
				'url' => admin_url( 'admin-ajax.php' )
			) );
		}
	}

	/**
	 * Add Menu
	 */
	public function admin_menu() {
		$send_now = add_submenu_page(
			'woo-boost-sales', esc_html__( 'Cross-Sells', 'woo-boost-sales' ), esc_html__( 'Cross-Sells', 'woo-boost-sales' ), 'manage_options', 'woo-boost-sales-crosssell', array(
				$this,
				'page_callback'
			)
		);
		add_action( "load-$send_now", array( $this, 'screen_options_page' ) );
	}

	/**
	 * Save options from screen options
	 *
	 * @param $status
	 * @param $option
	 * @param $value
	 *
	 * @return mixed
	 */
	public function save_screen_options( $status, $option, $value ) {
		if ( 'wbsc_per_page' == $option ) {
			return $value;
		}

		return $status;
	}

	/**
	 * Add Screen Options
	 */
	public function screen_options_page() {

		$option = 'per_page';

		$args = array(
			'label'   => esc_html__( 'Number of items per page', 'wp-admin' ),
			'default' => 30,
			'option'  => 'wbsc_per_page'
		);

		add_screen_option( $option, $args );
	}

	/**
	 * Menu page call back
	 */
	public function page_callback() {
		$user     = get_current_user_id();
		$screen   = get_current_screen();
		$option   = $screen->get_option( 'per_page', 'option' );
		$per_page = get_user_meta( $user, $option, true );

		if ( empty ( $per_page ) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}

		$paged   = isset( $_GET['paged'] ) ? sanitize_text_field( $_GET['paged'] ) : 1;
		$args    = array(
			'post_type'      => 'product',
			'post_status'    => VI_WOO_BOOSTSALES_Data::search_product_statuses(),
			'order'          => 'DESC',
			'orderby'        => 'ID',
			'tax_query'      => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'wbs_bundle',
					'operator' => '!='
				),
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array(
						'simple',
						'variable',
						'external',
						'subscription',
						'variable-subscription',
						'member'
					),
					'operator' => 'IN'
				),
			),
			'posts_per_page' => $per_page,
			'paged'          => $paged,
		);
		$keyword = isset( $_GET['wbs_cs_search'] ) ? sanitize_text_field( $_GET['wbs_cs_search'] ) : '';
		if ( $keyword ) {
			$args['s'] = $keyword;
		}
		$the_query  = new WP_Query( $args );
		$count      = $the_query->found_posts;
		$total_page = $the_query->max_num_pages;
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'CROSS-SELLS', 'woo-boost-sales' ) ?></h2>

            <p class="description"><?php esc_html_e( 'Cross-sells are products that instead of you buy this product you can buy bundle products contain this product, based on the current product. **For example, if you are selling a laptop, cross-sells might be a protective case or stickers or a special adapter.**', 'woo-boost-sales' ) ?>
                <br>
                <a href="javascript:void(0)" id="wbs_different_up-cross-sell" title=""
                   data-wbs_up_crosssell="http://new2new.com/envato/woocommerce-boost-sales/product-cross-sells.gif"><?php esc_html_e( 'What is CROSS-SELLS?', 'woo-boost-sales' ); ?></a>
            </p>
			<?php
			if ( ! $this->settings->get_option( 'enable' ) || ! $this->settings->get_option( 'crosssell_enable' ) ) {
				?>
                <div class="error">
                    <p><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( __( 'Cross-sells feature is currently disabled. <span class="wbs-crosssells-ajax-enable button button-primary">Enable now</span>', 'woo-boost-sales' ) ) ?></p>
                </div>
				<?php
			}

			if ( $the_query->have_posts() ) {
				ob_start();
				?>
                <form method="get">
					<?php wp_nonce_field( '_wbs_cross_sells_search', '_wsm_nonce' ) ?>
                    <input type="hidden" name="page" value="woo-boost-sales-crosssell">
                    <div class="tablenav top">
                        <div class="buttons-container">
                            <div class="alignleft actions bulkactions">
                                <span class="button action btn-sync-crosssell"
                                      title="<?php esc_html_e( 'Create bundle from WooCommerce cross-sells for products whose bundles are not set yet', 'woo-boost-sales' ) ?>"><?php esc_html_e( 'Sync Cross-Sells', 'woo-boost-sales' ) ?></span>
                            </div>
                        </div>
                        <div class="tablenav-pages">
                            <div class="pagination-links">
								<?php
								if ( $paged > 2 ) {
									?>
                                    <a class="prev-page button" href="<?php echo esc_url( add_query_arg(
										array(
											'page'          => 'woo-boost-sales-crosssell',
											'paged'         => 1,
											'wbs_cs_search' => $keyword,
										), admin_url( 'admin.php' )
									) ) ?>"><span
                                                class="screen-reader-text"><?php esc_html_e( 'First Page', 'woo-boost-sales' ) ?></span><span
                                                aria-hidden="true">«</span></a>
									<?php
								} else {
									?>
                                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
									<?php
								}
								/*Previous button*/
								if ( $per_page * $paged > $per_page ) {
									$p_paged = $paged - 1;
								} else {
									$p_paged = 0;
								}
								if ( $p_paged ) {
									$p_url = add_query_arg(
										array(
											'page'          => 'woo-boost-sales-crosssell',
											'paged'         => $p_paged,
											'wbs_cs_search' => $keyword,
										), admin_url( 'admin.php' )
									);
									?>
                                    <a class="prev-page button" href="<?php echo esc_url( $p_url ) ?>"><span
                                                class="screen-reader-text"><?php esc_html_e( 'Previous Page', 'woo-boost-sales' ) ?></span><span
                                                aria-hidden="true">‹</span></a>
									<?php
								} else {
									?>
                                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
									<?php
								}
								?>
                                <span class="screen-reader-text"><?php esc_html_e( 'Current Page', 'woo-boost-sales' ) ?></span>
                                <span id="table-paging" class="paging-input">
                                    <input class="current-page" type="text" name="paged" size="1"
                                           value="<?php echo esc_html( $paged ) ?>"><span class="tablenav-paging-text"> of <span
                                                class="total-pages"><?php echo esc_html( $total_page ) ?></span></span>

							</span>
								<?php /*Next button*/
								if ( $per_page * $paged < $count ) {
									$n_paged = $paged + 1;
								} else {
									$n_paged = 0;
								}
								if ( $n_paged ) {
									$n_url = add_query_arg(
										array(
											'page'          => 'woo-boost-sales-crosssell',
											'paged'         => $n_paged,
											'wbs_cs_search' => $keyword,
										), admin_url( 'admin.php' )
									); ?>
                                    <a class="next-page button" href="<?php echo esc_url( $n_url ) ?>"><span
                                                class="screen-reader-text"><?php esc_html_e( 'Next Page', 'woo-boost-sales' ) ?></span><span
                                                aria-hidden="true">›</span></a>
									<?php
								} else {
									?>
                                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
									<?php
								}
								if ( $total_page > $paged + 1 ) {
									?>
                                    <a class="next-page button" href="<?php echo esc_url( add_query_arg(
										array(
											'page'          => 'woo-boost-sales-crosssell',
											'paged'         => $total_page,
											'wbs_cs_search' => $keyword,
										), admin_url( 'admin.php' )
									) ) ?>"><span
                                                class="screen-reader-text"><?php esc_html_e( 'Last Page', 'woo-boost-sales' ) ?></span><span
                                                aria-hidden="true">»</span></a>
									<?php
								} else {
									?>
                                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
									<?php
								}
								?>
                            </div>
                        </div>
                        <p class="search-box">
                            <input type="search" class="text short" name="wbs_cs_search"
                                   placeholder="<?php esc_attr_e( 'Search product', 'woo-boost-sales' ) ?>"
                                   value="<?php echo esc_attr( $keyword ) ?>">
                            <input type="submit" name="submit" class="button"
                                   value="<?php echo esc_attr( 'Search product', 'woo-boost-sales' ) ?>">
                        </p>
                    </div>
                </form>
				<?php
				$pagination_html = ob_get_clean();
				echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $pagination_html );
				?>
                <div class="list-products">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                        <tr>
                            <th scope="col" id="product-name"
                                class="manage-column column-product-name column-primary sortable desc">
                                <a href="#"><span><?php esc_html_e( 'Product Name', 'woo-boost-sales' ) ?></span></a>
                            </th>
                            <th scope="col" id="up-sells" class="manage-column column-up-sells sortable desc">
                                <span><?php esc_html_e( 'Cross-sells', 'woo-boost-sales' ) ?></span>
                            </th>
                            <th scope="col" id="actions" class="manage-column column-actions sortable desc">
								<?php esc_html_e( 'Actions', 'woo-boost-sales' ) ?>
                            </th>
                        </tr>
                        </thead>

                        <tbody id="the-list" data-wp-lists="list:product">
						<?php
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$p_id = get_the_ID(); ?>
                            <tr id="product-<?php echo esc_attr( $p_id ) ?>">
                                <td class="product column-product has-row-actions column-primary"
                                    data-colname="product-name">
                                    <a href="<?php echo esc_url( 'post.php?action=edit&post=' . $p_id ) ?>"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( '[#' . get_the_ID() . '] ' . the_title( '', '', '' ) ) ?></a>
                                </td>
                                <td data-id="<?php echo esc_attr( $p_id ) ?>" class="name column-cross-sells"
                                    data-colname="<?php esc_attr_e( 'Cross sells', 'woo-boost-sales' ) ?>">
									<?php
									$post_meta_of = get_post_meta( $p_id, '_wbs_crosssells', true );
									if ( is_array( $post_meta_of ) && count( array_filter( $post_meta_of ) ) ) {
										$bundle_id = isset( $post_meta_of[0] ) ? $post_meta_of[0] : '';
									} else {
										$bundle_id = '';
									}
									?>
                                    <input type="hidden" name="_wbs_cross_sell_of"
                                           value="<?php echo esc_attr( $bundle_id ) ?>">
                                    <div class="product-search-crs-container">
                                        <select multiple="multiple" name="_wbs_cross_sell"
                                                class="product-search-crs u-product-<?php echo get_the_ID() ?>">
											<?php
											if ( $bundle_id ) {
												if ( get_post_status( $bundle_id ) == 'publish' ) {
													$product_chosen = get_post_meta( $bundle_id, '_wbs_wcpb_bundle_data', true );
													if ( is_array( $product_chosen ) && count( $product_chosen ) ) {
														foreach ( $product_chosen as $product_chose ) {
															if ( isset( $product_chose['product_id'] ) && intval( $product_chose['product_id'] ) ) {
																$dt_product = wc_get_product( $product_chose['product_id'] );
																if ( $dt_product ) {
																	$parent = $out_stock = '';
																	if ( $dt_product->has_child() && $dt_product->get_type() == 'variable' ) {
																		$parent = ' (#PARENT)';
																	}
																	if ( ! $dt_product->is_in_stock() ) {
																		$out_stock = '(' . esc_html__( 'Out of stock', 'woo-boost-sales' ) . ')';
																	}
																	if ( get_post_status( $product_chose['product_id'] ) == 'publish' ) {
																		?>
                                                                        <option selected="selected"
                                                                                value="<?php echo esc_attr( $product_chose['product_id'] ); ?>">
																			<?php echo esc_html( $dt_product->get_name() . ' (#' . $product_chose['product_id'] . ')' . $parent . $out_stock ) ?>
                                                                        </option>
																		<?php
																	}
																}
															}
														}
													}
												}
											}
											?>
                                        </select>
										<?php
										if ( $bundle_id ) {
											if ( get_post_status( $bundle_id ) == 'publish' ) {
												$detail_bundle = wc_get_product( $bundle_id ); ?>
                                                <br>
                                                <a target="_blank"
                                                   href="<?php echo esc_url( get_edit_post_link( $bundle_id ) ); ?>"
                                                   class="button-edit"><?php esc_attr_e( 'Edit product bundle', 'woo-boost-sales' ) ?></a> |
                                                <span class="button-edit button-quick-edit"><?php esc_attr_e( 'Quick Edit product bundle', 'woo-boost-sales' ) ?></span>
                                                <div class="inline-edit-row"
                                                     data-product_bundle_id="<?php echo esc_attr( $bundle_id ); ?>">
                                                    <fieldset class="">
                                                        <legend class="inline-edit-legend">Quick Edit</legend>
                                                        <div class="inline-edit-col">
                                                            <label>
                                                                <span class="title"><?php esc_html_e( 'Title', 'woo-boost-sales' ) ?></span>
                                                                <span class="input-text-wrap"><input type="text"
                                                                                                     name="post_bundle_title"
                                                                                                     class="ptitle"
                                                                                                     value="<?php echo esc_attr( $detail_bundle->get_title() ); ?>"></span>
                                                            </label>
                                                            <label class="wbs-bundle-fixed-price-fields">
                                                                <span class="title"><?php esc_html_e( 'Price', 'woo-boost-sales' ) ?></span>
                                                                <span class="input-text-wrap"><input
                                                                            class="text wc_input_price" type="text"
                                                                            name="product_bundle_regular_price"
                                                                            title="<?php esc_html_e( 'Please enter the number of price', 'woo-boost-sales' ) ?>"
                                                                            value="<?php echo esc_attr( $detail_bundle->get_price() ); ?>"></span>
                                                            </label>

                                                        </div>
                                                    </fieldset>
                                                    <p class="submit inline-edit-save">
														<?php wp_nonce_field( 'wp_update_bundle_product', '_wbs_update_nonce' ) ?>
                                                        <button type="button"
                                                                class="button cancel alignleft button-cancel">
                                                            Cancel
                                                        </button>
                                                        <button type="button"
                                                                class="button button-primary save alignright button-update">
                                                            Update
                                                        </button>
                                                        <span class="spinner"></span>
                                                    </p>
                                                </div>
												<?php
											}
										}
										?>
                                    </div>
                                </td>
                                <td class="email column-action product-action-<?php echo esc_attr( $p_id ); ?>"
                                    data-colname="<?php esc_attr_e( 'Actions', 'woo-boost-sales' ) ?>"
                                    data-id="<?php echo esc_attr( $p_id ); ?>">
                                    <a class="button" target="_blank"
                                       href="<?php the_permalink( $p_id ) ?>"><?php esc_attr_e( 'View', 'woo-boost-sales' ) ?></a>
                                    <span class="button button-save"><?php esc_attr_e( 'Save', 'woo-boost-sales' ) ?></span>
                                    <span class="button button-remove"><?php esc_attr_e( 'Remove all', 'woo-boost-sales' ) ?></span>
                                </td>
                            </tr>
						<?php } ?>
                        </tbody>
						<?php
						// Reset Post Data
						wp_reset_postdata();
						?>
                    </table>
                </div>
				<?php
				echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $pagination_html );
			} else {
				?>
                <form method="get">
					<?php wp_nonce_field( '_wbs_cross_sells_search', '_wsm_nonce' ) ?>
                    <input type="hidden" name="page" value="woo-boost-sales-crosssell">
                    <input type="search" class="text short" name="wbs_cs_search"
                           placeholder="<?php esc_attr_e( 'Search product', 'woo-boost-sales' ) ?>"
                           value="<?php echo esc_attr( $keyword ) ?>">
                    <input type="submit" name="submit" class="button"
                           value="<?php echo esc_attr( 'Search product', 'woo-boost-sales' ) ?>">
                    <p>
						<?php esc_html_e( 'No products found', 'woo-boost-sales' ) ?>
                    </p>
                </form>
				<?php
			}
			?>
        </div>
		<?php
	}
}