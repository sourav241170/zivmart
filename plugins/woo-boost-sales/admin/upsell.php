<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Admin_Upsell {
	protected $settings;

	public function __construct() {
		$this->settings = VI_WOO_BOOSTSALES_Data::get_instance();
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'set-screen-option', array( $this, 'save_screen_options' ), 10, 3 );
		add_action( 'wp_ajax_wbs_search_product', array( $this, 'wbs_search_product' ) );
		add_action( 'wp_ajax_wbs_u_save_product', array( $this, 'wbs_u_save_product' ) );
		add_action( 'wp_ajax_wbs_u_remove_product', array( $this, 'wbs_u_remove_product' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99999 );
		add_action( 'wp_ajax_wbs_u_sync_product', array( $this, 'wbs_u_sync_product' ) );
		add_action( 'wp_ajax_wbs_ajax_enable_upsell', array( $this, 'ajax_enable_upsell' ) );
		add_action( 'set_object_terms', array( $this, 'set_object_terms' ), 10, 5 );
	}

	/**
	 * Delete upsells transient if Products in category is enabled
	 *
	 * @param $object_id
	 * @param $terms
	 * @param $tt_ids
	 * @param $taxonomy
	 * @param $append
	 */
	public function set_object_terms( $object_id, $terms, $tt_ids, $taxonomy, $append ) {
		if ( $taxonomy === 'product_cat' ) {
			delete_transient( 'vi_woocommerce_boost_sales_product_in_category_ids_' . $object_id );
		} elseif ( $taxonomy === 'product_tag' ) {
			delete_transient( 'vi_woocommerce_boost_sales_product_in_tags_ids_' . $object_id );
		}
	}

	public function ajax_enable_upsell() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		global $wbs_settings;
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), '_wbs_upsells_search' ) ) {
			$wbs_settings['enable']        = 1;
			$wbs_settings['enable_upsell'] = 1;
			update_option( '_woocommerce_boost_sales', $wbs_settings );
		}
		die;
	}

	/**
	 * Sync product up sells
	 */
	public function wbs_u_sync_product() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$paged = 1;
		while ( true ) {
			$args      = array(
				'post_status'    => VI_WOO_BOOSTSALES_Data::search_product_statuses(),
				'post_type'      => 'product',
				'posts_per_page' => 50,
				'paged'          => $paged,
				'fields'         => 'ids'
			);
			$the_query = new WP_Query( $args );
			// The Loop
			if ( $the_query->have_posts() ) {
				foreach ( $the_query->posts as $product_id ) {
					// Do Stuff
					$meta = get_post_meta( $product_id, '_wbs_upsells', true );
					$u_id = get_post_meta( $product_id, '_upsell_ids', true );
					if ( ! is_array( $meta ) ) {
						$meta = array();
					}
					if ( ! is_array( $u_id ) ) {
						$u_id = array();
					}
					$meta = array_merge( $meta, $u_id );
					$meta = array_unique( $meta );
					if ( in_array( $product_id, $meta ) ) {
						$index = array_search( $product_id, $meta );
						unset( $meta[ $index ] );
						$meta = array_values( $meta );
					}
					update_post_meta( $product_id, '_wbs_upsells', $meta );
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

	/**
	 * Remove all Upsell
	 */
	public function wbs_u_remove_product() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();
		$p_id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING );
		$msg  = array();

		if ( empty( $p_id ) ) {
			die();
		}
		delete_post_meta( $p_id, '_wbs_upsells' );
		$msg['check'] = 'done';
		ob_clean();
		echo json_encode( $msg );
		die;
	}

	/**
	 * Save up sells
	 */
	public function wbs_u_save_product() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$p_id = isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$u_id = ! empty( $_POST['u_id'] ) ? array_map( 'sanitize_text_field', $_POST['u_id'] ) : array();
		$msg  = array(
			'check' => 'done'
		);
		if ( ! empty( $p_id ) ) {
			update_post_meta( $p_id, '_wbs_upsells', $u_id );
		}
		wp_send_json( $msg );
	}

	/**
	 * Select 2 Search ajax
	 */
	public function wbs_search_product() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();

		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
		$p_id    = filter_input( INPUT_GET, 'p_id', FILTER_SANITIZE_STRING );

		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => VI_WOO_BOOSTSALES_Data::search_product_statuses(),
			'post_type'      => 'product',
			'posts_per_page' => 50,
			's'              => $keyword,
			'fields'         => 'ids',
			'post__not_in'   => array( $p_id ),
//			'tax_query'      => array(
//				'relation' => 'AND',
//				array(
//					'taxonomy' => 'product_type',
//					'field'    => 'slug',
//					'terms'    => array( 'wbs_bundle', 'bundle' ),
//					'operator' => 'NOT IN'
//				),
//				array(
//					'taxonomy' => 'product_type',
//					'field'    => 'slug',
//					'terms'    => array(
//						'simple',
//						'variable',
//						'external',
//						'subscription',
//						'variable-subscription',
//						'member',
//						'woosb',
//						'booking',
//					),
//					'operator' => 'IN'
//				),
//			)
		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			foreach ( $the_query->posts as $product_id ) {
				$_product = wc_get_product( $product_id );
				$parent   = '';
				if ( $_product->get_type() == 'variable' && $_product->has_child() ) {
					$parent = '(#VARIABLE)';
				}
				$found_products[] = array(
					'id'   => $_product->get_id(),
					'text' => $_product->get_title() . ' (#' . $product_id . ') ' . $parent
				);
			}
		}
		// Reset Post Data
		wp_reset_postdata();
		wp_send_json( $found_products );
		die;
	}

	/**
	 * Init scripts
	 */
	public function enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $page == 'woo-boost-sales-upsell' ) {
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
			wp_enqueue_script( 'select2-v4', VI_WOO_BOOSTSALES_JS . 'select2.js', array( 'jquery' ), '4.0.3', true );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_style( 'woo-boost-sales-upsell-admin', VI_WOO_BOOSTSALES_CSS . 'upsell-admin.css', array(), VI_WOO_BOOSTSALES_VERSION );
			wp_enqueue_script( 'woo-boost-sales-upsell-admin', VI_WOO_BOOSTSALES_JS . 'woo-boost-sales-upsell-admin.js', array( 'jquery' ), VI_WOO_BOOSTSALES_VERSION, true );
			wp_localize_script( 'woo-boost-sales-upsell-admin', 'wbs_upsell_admin_params', array(
				'url' => admin_url( 'admin-ajax.php' )
			) );
		}
	}

	/**
	 * Add Menu
	 */
	public function admin_menu() {
		$send_now = add_submenu_page(
			'woo-boost-sales', __( 'Up-Sells', 'woo-boost-sales' ), __( 'Up-Sells', 'woo-boost-sales' ), 'manage_options', 'woo-boost-sales-upsell', array(
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
		if ( 'wbs_per_page' == $option ) {
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
			'default' => 50,
			'option'  => 'wbs_per_page'
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
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'wbs_bundle',
					'operator' => 'NOT IN'
				),
			),
			'posts_per_page' => $per_page,
			'paged'          => $paged,
		);
		$keyword = isset( $_GET['wbs_us_search'] ) ? sanitize_text_field( $_GET['wbs_us_search'] ) : '';
		if ( $keyword ) {
			$args['s'] = $keyword;
		}
		$the_query  = new WP_Query( $args );
		$count      = $the_query->found_posts;
		$total_page = $the_query->max_num_pages;

		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'UP-SELLS', 'woo-boost-sales' ) ?></h2>
            <p class="description"><?php esc_html_e( 'Up-sells are products that you recommend instead of the currently product added to cart. They are typically products that are more profitable or better quality or more expensive', 'woo-boost-sales' ) ?>
                <br>
                <a href="javascript:void(0)" id="wbs_different_up-cross-sell" title=""
                   data-wbs_up_crosssell="http://new2new.com/envato/woocommerce-boost-sales/product-upsells.gif"><?php esc_html_e( 'What is UPSELLS?', 'woo-boost-sales' ); ?></a>
            </p>
			<?php
			if ( ! $this->settings->get_option( 'enable' ) || ! $this->settings->get_option( 'enable_upsell' ) ) {
				?>
                <div class="error">
                    <p><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( __( 'Up-sells feature is currently disabled. <span class="wbs-upsells-ajax-enable button button-primary">Enable now</span>', 'woo-boost-sales' ) ) ?></p>
                </div>
				<?php
			}
			if ( $the_query->have_posts() ) {
				ob_start();
				?>
                <form method="get">
					<?php wp_nonce_field( '_wbs_upsells_search', '_wsm_nonce' ) ?>
                    <input type="hidden" name="page" value="woo-boost-sales-upsell">
                    <div class="tablenav top">
                        <div class="buttons-container">
                            <div class="alignleft actions bulkactions">
                                <span class="button action btn-sync-upsell"
                                      title="<?php esc_attr_e( 'Create Up-sells to use with Boost Sales for WooCommerce plugin from Up-sells data in WooCommerce single product settings.', 'woo-boost-sales' ) ?>"><?php esc_html_e( 'Get Product Up-Sells', 'woo-boost-sales' ) ?></span>
								<?php
								if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
									global $sitepress;
									$default_lang = $sitepress->get_default_language();
									$current_lang = $sitepress->get_current_language();
									if ( $current_lang !== $default_lang ) {
										?>
                                        <span class="button action wbs-sync-upsells-from-default-language"
                                              title="<?php esc_attr_e( 'Sync upsells from default language', 'woo-boost-sales' ) ?>"><?php esc_html_e( 'Sync from default language', 'woo-boost-sales' ) ?></span>
										<?php
									}
								}
								?>
                            </div>
                        </div>
                        <div class="tablenav-pages">
                            <div class="pagination-links">
								<?php
								if ( $paged > 2 ) {
									?>
                                    <a class="prev-page button" href="<?php echo esc_url( add_query_arg(
										array(
											'page'          => 'woo-boost-sales-upsell',
											'paged'         => 1,
											'wbs_us_search' => $keyword,
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
											'page'          => 'woo-boost-sales-upsell',
											'paged'         => $p_paged,
											'wbs_us_search' => $keyword,
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
											'page'          => 'woo-boost-sales-upsell',
											'paged'         => $n_paged,
											'wbs_us_search' => $keyword,
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
											'page'          => 'woo-boost-sales-upsell',
											'paged'         => $total_page,
											'wbs_us_search' => $keyword,
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
                            <input type="search" class="text short" name="wbs_us_search"
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
                                <a href="#">
                                    <span><?php esc_html_e( 'Product Name', 'woo-boost-sales' ) ?></span>
                                </a>
                            </th>
                            <th scope="col" id="up-sells" class="manage-column column-up-sells sortable desc">
                                <span><?php esc_html_e( 'Up-sells products', 'woo-boost-sales' ) ?></span>
                            </th>
                            <th style="width: 222px;" scope="col" id="actions"
                                class="manage-column column-actions sortable desc">
								<?php esc_html_e( 'Actions', 'woo-boost-sales' ) ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="the-list" data-wp-lists="list:product">
						<?php
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							?>
                            <tr id="product-<?php echo get_the_ID() ?>">
                                <td class="product column-product has-row-actions column-primary"
                                    data-colname="product-name">
                                    <a href="<?php echo esc_url( 'post.php?action=edit&post=' . get_the_ID() ) ?>"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( '[#' . get_the_ID() . '] ' . the_title( '', '', '' ) ) ?></a>
                                </td>
                                <td data-id="<?php echo get_the_ID() ?>" class="name column-up-sells"
                                    data-colname="<?php esc_attr_e( 'Up sells', 'woo-boost-sales' ) ?>">
									<?php
									$products = get_post_meta( get_the_ID(), '_wbs_upsells', true );
									if ( ! is_array( $products ) ) {
										$products = array();
									}
									?>
                                    <select multiple="multiple" name="_wbs_up_sell"
                                            class="product-search u-product-<?php echo get_the_ID() ?>">
										<?php if ( count( $products ) ) {
											foreach ( $products as $product ) {
												$data = wc_get_product( $product );
												if ( $data ) {
													$parent = $out_stock = '';
													if ( $data->is_type( 'variable' ) && $data->has_child() ) {
														$parent = '(#VARIABLE)';
													}
													if ( ! $data->is_in_stock() ) {
														$out_stock = '(' . esc_html__( 'Out of stock', 'woo-boost-sales' ) . ')';
													}
													?>
                                                    <option selected="selected"
                                                            value="<?php echo esc_attr( $data->get_id() ) ?>"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( ' (#' . $product . ') ' . $data->get_title() . $parent . $out_stock ) ?></option>
													<?php
												}
											}
										}
										?>
                                    </select>
                                </td>
                                <td class="email column-action product-action-<?php echo get_the_ID() ?>"
                                    data-colname="<?php esc_attr_e( 'Actions', 'woo-boost-sales' ) ?>"
                                    data-id="<?php echo get_the_ID() ?>">
                                    <a target="_blank" href="<?php the_permalink( get_the_ID() ) ?>"
                                       class="button"><?php esc_html_e( 'View', 'woo-boost-sales' ) ?></a>
                                    <span
                                            class="button button-save"><?php esc_html_e( 'Save', 'woo-boost-sales' ) ?></span>
                                    <span
                                            class="button button-remove"><?php esc_html_e( 'Remove all', 'woo-boost-sales' ) ?></span>

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
					<?php wp_nonce_field( '_wbs_upsells_search', '_wsm_nonce' ) ?>
                    <input type="hidden" name="page" value="woo-boost-sales-upsell">
                    <input type="search" class="text short" name="wbs_us_search"
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
			wp_reset_postdata();
			?>
        </div>
		<?php
	}
}