<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'VillaTheme_Admin_Fields' ) ) {

	class VillaTheme_Admin_Fields {
		protected $settings;
		public $params = null;
		public $prefix = '_woocommerce_boost_sales';
		public $post_type = array();
		public $page = array( 'woo-boost-sales' );

		public function __construct( $prefix = '', $load_scripts = true ) {
			$this->settings = VI_WOO_BOOSTSALES_Data::get_instance();
			if ( $prefix ) {
				$this->prefix = $prefix;
				add_action( 'villatheme_setting_html_' . $prefix, array( $this, 'villatheme_setting_html' ) );
			}
			add_action( 'admin_init', array( $this, 'update_options' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99 );
			add_action( 'villatheme_setting_html', array( $this, 'villatheme_setting_html' ) );
			add_action( 'wp_ajax_wbs_select_coupon', array( $this, 'wbs_select_coupon' ) );
			add_action( 'wp_ajax_wbs_search_product_excl', array( $this, 'wbs_search_product_excl' ) );
			add_action( 'wp_ajax_wbs_search_category_excl', array( $this, 'wbs_search_category_excl' ) );
		}

		/**
		 * Enqueue scripts
		 */
		public function admin_enqueue_scripts() {
			global $wp_scripts, $wp_styles;
			if ( count( $this->post_type ) ) {
				if ( ! in_array( get_post_type(), $this->post_type ) ) {
					return;
				}
			}
			if ( count( $this->page ) ) {
				if ( isset( $_GET['page'] ) ) {
					if ( ! in_array( sanitize_text_field( $_GET['page'] ), $this->page ) ) {
						return;
					}
					/*Remove other scripts*/
					global $wp_scripts;
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
				} else {
					return;
				}
			}
			wp_dequeue_style( 'eopa-admin-css' );
			/*Stylesheet*/
			wp_enqueue_style( 'villatheme-field-image', VI_WOO_BOOSTSALES_CSS . 'image.min.css' );
			wp_enqueue_style( 'villatheme-field-transition', VI_WOO_BOOSTSALES_CSS . 'transition.min.css' );
			wp_enqueue_style( 'villatheme-field-form', VI_WOO_BOOSTSALES_CSS . 'form.min.css' );
			wp_enqueue_style( 'villatheme-field-input', VI_WOO_BOOSTSALES_CSS . 'input.min.css' );
			wp_enqueue_style( 'villatheme-field-icon', VI_WOO_BOOSTSALES_CSS . 'icon.min.css' );
			wp_enqueue_style( 'villatheme-field-dropdown', VI_WOO_BOOSTSALES_CSS . 'dropdown.min.css' );
			wp_enqueue_style( 'villatheme-field-checkbox', VI_WOO_BOOSTSALES_CSS . 'checkbox.min.css' );
			wp_enqueue_style( 'villatheme-field-segment', VI_WOO_BOOSTSALES_CSS . 'segment.min.css' );
			wp_enqueue_style( 'villatheme-field-menu', VI_WOO_BOOSTSALES_CSS . 'menu.min.css' );
			wp_enqueue_style( 'villatheme-field-tab', VI_WOO_BOOSTSALES_CSS . 'tab.css' );
			wp_enqueue_style( 'villatheme-field-message', VI_WOO_BOOSTSALES_CSS . 'message.min.css' );
			wp_enqueue_style( 'villatheme-field-button', VI_WOO_BOOSTSALES_CSS . 'button.min.css' );
			wp_enqueue_style( 'villatheme-fields', VI_WOO_BOOSTSALES_CSS . 'fields.css' );

			/*Script*/
			wp_enqueue_script( 'villatheme-field-transition', VI_WOO_BOOSTSALES_JS . 'transition.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'villatheme-field-dropdown', VI_WOO_BOOSTSALES_JS . 'dropdown.js', array( 'jquery' ) );
			wp_enqueue_script( 'villatheme-field-checkbox', VI_WOO_BOOSTSALES_JS . 'checkbox.js', array( 'jquery' ) );
			wp_enqueue_script( 'villatheme-field-tab', VI_WOO_BOOSTSALES_JS . 'tab.js', array( 'jquery' ) );
			wp_enqueue_script( 'villatheme-field-address', VI_WOO_BOOSTSALES_JS . 'jquery.address-1.6.min.js', array( 'jquery' ) );
			wp_enqueue_media();
			wp_enqueue_script( 'villatheme-fields', VI_WOO_BOOSTSALES_JS . 'fields.js', array( 'jquery' ) );
			/*Color picker*/
			wp_enqueue_script(
				'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1
			);


			$scripts = $wp_scripts->registered;

			foreach ( $scripts as $k => $script ) {
				preg_match( '/select2/i', $k, $result );
				if ( count( array_filter( $result ) ) ) {
					unset( $wp_scripts->registered[ $k ] );
					wp_dequeue_script( $script->handle );
				}
			}

			wp_enqueue_style( 'select2', VI_WOO_BOOSTSALES_CSS . 'select2.min.css' );
			wp_enqueue_script( 'select2-v4', VI_WOO_BOOSTSALES_JS . 'select2.js', array( 'jquery' ), '4.0.3', true );


			/*admin ajax*/
			$script = 'var wbs_admin_ajax_url = "' . admin_url( 'admin-ajax.php' ) . '"';
			wp_add_inline_script( 'villatheme-fields', $script );
		}

		/*
		 * Ajax search categories
		 */
		public function wbs_search_category_excl() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			ob_start();

			$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
			if ( empty( $keyword ) ) {
				die();
			}

			$arg_p = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
				'fields'     => 'id=>name'
			);

			$terms = get_terms( $arg_p );
			if ( count( $terms ) ) {
				$results = array();
				foreach ( $terms as $key => $name ) {
					$cate      = array(
						'id'   => $key,
						'text' => $name
					);
					$results[] = $cate;
				}
				wp_send_json( $results );
			} else {
				echo esc_html__( 'Category is not found', 'woo-boost-sales' );
			}
			die;
		}

		/* ajax search product*/
		public function wbs_search_product_excl() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			ob_start();

			$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
			if ( empty( $keyword ) ) {
				die();
			}

			$arg_p = array(
				'post_status'    => 'publish',
				'post_type'      => 'product',
				's'              => $keyword,
				'posts_per_page' => - 1,
				'orderby'        => 'title',
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => 'wbs_bundle',
						'operator' => 'NOT IN'
					),
				),
				'meta_query'     => array(
					array(
						'key'   => '_stock_status',
						'value' => 'instock'
					)
				)
			);

			$the_query      = new WP_Query( $arg_p );
			$found_products = array();
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$data = wc_get_product();
					if ( $data->is_in_stock() ) {
						$product = array(
							'id'   => get_the_ID(),
							'text' => get_the_title() . ' - (#' . get_the_ID() . ')'
						);
					} else {
						$product = array(
							'id'   => get_the_ID(),
							'text' => get_the_title() . ' - (#' . get_the_ID() . ') - ' . esc_html__( 'Out of stock', 'woo-boost-sales' )
						);
					}
					$found_products[] = $product;
				}
			}
			wp_reset_postdata();
			wp_send_json( $found_products );
			die;
		}

		/* ajax search product*/
		public function wbs_select_coupon() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			ob_start();

			$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
			if ( empty( $keyword ) ) {
				die();
			}

			$arg_p = array(
				'post_status'    => 'publish',
				'post_type'      => 'shop_coupon',
				's'              => $keyword,
				'posts_per_page' => - 1,
				'orderby'        => 'title',

			);

			$the_query      = new WP_Query( $arg_p );
			$found_products = array();
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$coupon = new WC_Coupon( get_the_ID() );
					if ( $coupon->get_usage_limit() > 0 && $coupon->get_usage_count() >= $coupon->get_usage_limit() ) {
						continue;
					}
					if ( $coupon->get_date_expires() && current_time( 'timestamp', true ) > $coupon->get_date_expires()->getTimestamp() ) {
						continue;
					}
					$product          = array(
						'id'   => get_the_ID(),
						'text' => get_the_title()
					);
					$found_products[] = $product;
				}
			}
			wp_reset_postdata();
			wp_send_json( $found_products );
			die;
		}

		/**
		 * Run setting layout
		 *
		 * @param array $args
		 */
		public function villatheme_setting_html() {
			$args = apply_filters( 'wbs_data_settings', array() );
			$args = apply_filters( 'wbs_data_settings_' . $this->prefix, $args );
			if ( ! $this->params ) {
				$this->params = get_option( $this->prefix, array() );
			}
			ob_start();
			if ( count( $args ) ) { ?>
                <form method="post" action="" class="vi-ui form">
					<?php wp_nonce_field( $this->prefix . '_settings', $this->prefix . '_nonce' ) ?>
                    <div class="vi-ui attached tabular menu">
						<?php foreach ( $args as $k => $arg ) { ?>
                            <div class="item <?php echo ( isset( $arg['active'] ) && $arg['active'] ) ? 'active' : '' ?>"
                                 data-tab="<?php echo esc_attr( $this->reformat_str( $k ) ) ?>">
								<?php echo isset( $arg['title'] ) ? VI_WOO_BOOSTSALES_Data::wp_kses_post( $arg['title'] ) : '' ?>
                            </div>
						<?php } ?>
                    </div>
					<?php
					foreach ( $args as $k => $arg ) { ?>
                        <div class="vi-ui bottom attached tab segment <?php echo ( isset( $arg['active'] ) && $arg['active'] ) ? 'active' : '' ?>"
                             data-tab="<?php echo esc_attr( $this->reformat_str( $k ) ) ?>"
                             id="<?php echo esc_attr( $this->reformat_str( $k ) ) ?>">
							<?php do_action( 'wbs_settings_start_of_tab_' . $k, $this ) ?>
                            <table class="optiontable form-table">
								<?php

								if ( $k == 'discount' ) {
									if ( wc_coupons_enabled() == false ) { ?>
                                        <div
                                                class="ui red message"><?php esc_html_e( 'Please enable Coupon in WooCommerce >> Settings >> Checkout before enable Discount !', 'woo-boost-sales' ) ?></div>
									<?php }
								}

								if ( isset( $arg['fields'] ) && count( $arg['fields'] ) ) {
									foreach ( $arg['fields'] as $field ) {
										$name         = isset( $field['name'] ) ? $field['name'] : '';
										$product_type = isset( $field['product_type'] ) ? $field['product_type'] : '';
										$taxonomy     = isset( $field['taxonomy'] ) ? $field['taxonomy'] : '';
										$value        = isset( $field['value'] ) ? $field['value'] : '';
										$type         = isset( $field['type'] ) ? $field['type'] : 'text';
										$description  = isset( $field['description'] ) ? $field['description'] : '';
										$label        = isset( $field['label'] ) ? $field['label'] : '';
										$class        = isset( $field['class'] ) ? $field['class'] : '';
										$do_action    = isset( $field['do_action'] ) ? $field['do_action'] : '';
										$options      = isset( $field['options'] ) ? $field['options'] : array();
										if ( ! $name && ! $do_action ) {
											?>
                                            <tr class="<?php echo esc_attr( $class ); ?>">
                                                <th scope="row">
                                                    <label>
														<?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $label ) ?>
                                                    </label>
                                                </th>
                                                <td>
													<?php
													if ( $type === 'title' ) {
														echo '<h3 class="wbs-field-title">' . esc_html( isset( $field['value'] ) ? $field['value'] : '' ) . '</h3>';
													} else {
														echo "<a target='_blank' class='vi-ui button' href='https://1.envato.market/yQBL3'>" . esc_html__( 'Upgrade to Premium version', 'woo-boost-sales' ) . "</a>";
														if ( $value ) {
															?>
                                                            <div class="vi-ui input disabled"><input disabled
                                                                                                     value="<?php echo esc_attr( $value ) ?>">
                                                            </div>
															<?php
														}
													}
													if ( $description ) { ?>
                                                        <p class="description"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $description ) ?></p>
														<?php
													}
													?>
                                                </td>
                                            </tr>
											<?php continue;
										}
										?>
                                        <tr class="<?php echo esc_attr( $class ); ?>">
                                            <th scope="row">
                                                <label
                                                        for="<?php echo esc_attr( $this->prefix . '[' . $this->reformat_str( $name ) . ']' ) ?>">
													<?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $label ) ?>
                                                </label>
                                            </th>
                                            <td>
												<?php
												switch ( $type ) {
													case 'text':
													case 'radio':
													case 'checkbox':
													case 'number':
													case 'email':
													case 'password':
													case 'color-picker':
													case 'date-picker':
													case 'file':
													case 'image':
														echo $this->text_field( $field );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'select':
														echo $this->select( $field );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'textarea':
														echo $this->textarea( $field );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'select2_ajax':
														echo $this->select2_ajax( $field, $product_type );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'select2_ajax_category':
														echo $this->select2_ajax_category( $field, $taxonomy );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													default:
												}
												/*Add to action*/
												if ( $do_action ) {
													do_action( $do_action );
												}
												if ( $description ) {
													?>
                                                    <p class="description"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( $description ) ?></p>
													<?php
												}
												?>
                                            </td>
                                        </tr>
										<?php

									}
								} ?>
                            </table>
							<?php do_action( 'wbs_settings_end_of_tab_' . $k, $this ) ?>
                        </div>
						<?php
					}
					?>
                    <p class="wbs-button-save-settings-container">
                        <button class="vi-ui button labeled icon primary wbs-submit">
                            <i class="send icon"></i> <?php esc_html_e( 'Save', 'woo-boost-sales' ) ?>
                        </button>
                    </p>
                </form>
				<?php
			}
		}

		/**
		 *
		 */
		public function update_options() {
			global $wpdb, $wbs_settings;
			if ( ! isset( $_POST[ $this->prefix ] ) || ! isset( $_POST[ $this->prefix ] ) ) {
				return;
			}
			if ( ! isset( $_POST[ $this->prefix . '_nonce' ] ) || ! wp_verify_nonce( sanitize_text_field( $_POST[ $this->prefix . '_nonce' ] ), $this->prefix . '_settings' ) ) {
				return;
			}
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$data         = array_map( array( $this, 'stripslashes' ), $_POST[ $this->prefix ] );
			$sort_product = isset( $data['sort_product'] ) ? $data['sort_product'] : '';
			/*Delete products in same categories transient*/
			if ( $sort_product != $this->get_option( 'sort_product' ) ) {
				$query = "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '_transient_vi_woocommerce_boost_sales_product_in_category_ids_%' OR option_name LIKE '_transient_timeout_vi_woocommerce_boost_sales_product_in_category_ids_%'";
				$wpdb->query( $query );
			}
			$item_per_row_mobile = isset( $data['item_per_row_mobile'] ) ? intval( $data['item_per_row_mobile'] ) : 1;
			if ( $item_per_row_mobile < 1 || $item_per_row_mobile > 2 ) {
				$data['item_per_row_mobile'] = 1;
			}
			foreach ( $this->settings->get_default() as $key => $value ) {
				if ( ! isset( $data[ $key ] ) ) {
					if ( is_array( $value ) ) {
						$data[ $key ] = array();
					} else {
						$data[ $key ] = '';
					}
				}
			}
			$data = array_merge( $this->settings->get_option(), $data );
			update_option( $this->prefix, $data );
			$this->params   = $data;
			$wbs_settings   = $data;
			$this->settings = VI_WOO_BOOSTSALES_Data::get_instance( true );
		}

		/**
		 * Remove striplashes
		 *
		 * @param $item
		 *
		 * @return array|string
		 */
		public function stripslashes( $item ) {
			if ( is_array( $item ) ) {
				return array_map( array( $this, 'stripslashes' ), $item );
			} else {
				return stripslashes( $item );
			}
		}

		/**
		 * Get value
		 *
		 * @param null $field Name field
		 * @param string $default Data default
		 *
		 * @return bool|null|string|array
		 */
		public function get_option( $field = null, $default = '' ) {
			if ( ! $field ) {
				return false;
			}
			if ( ! $this->params ) {
				$this->params = get_option( $this->prefix, array() );
			}

			if ( isset( $this->params[ $field ] ) && $field ) {
				return $this->params[ $field ];
			} else {
				return $default;
			}
		}

		/**
		 * Input text
		 *
		 * @param array $field
		 *
		 * @return string|void
		 */
		protected function text_field( $field = array() ) {
			$name    = isset( $field['name'] ) ? $field['name'] : '';
			$type    = isset( $field['type'] ) ? $field['type'] : 'text';
			$value   = isset( $field['value'] ) ? $this->get_option( $field['name'], $field['value'] ) : '';
			$options = isset( $field['options'] ) ? $field['options'] : array();
			$holder  = isset( $field['holder'] ) ? $field['holder'] : '';
			$class   = isset( $field['class'] ) ? $field['class'] : '';

			$html = '';
			switch ( $type ) {
				case 'text':
				case 'number':
				case 'email':
				case 'password':
					$html .= '<input value="' . esc_attr( $value ) . '" type="' . esc_attr( $type ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" id="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" placeholder="' . esc_attr( $holder ) . '" />';
					break;
				case 'radio':
				case 'checkbox':
					if ( count( $options ) ) {
						foreach ( $options as $k => $title ) {
							if ( $k == $value ) {
								$checked = 'checked="checked"';
							} else {
								$checked = '';
							}
							$html .= '<div class="vi-ui toggle ' . esc_attr( $type ) . '">';
							$html .= '<input ' . $checked . ' id="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']' . '" type="' . esc_attr( $type ) . '" tabindex="0" class="hidden" value="' . esc_attr( $k ) . '" name="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']' . '"/>';
							$html .= '<label><p class="description">' . $title . '</p></label>';
							$html .= '</div>';
						}
					}
					break;
				case 'color-picker':
					$class .= ' ' . $type;
					$type  = 'text';
					$html  .= '<input style="background-color:' . esc_attr( $value ) . '" value="' . esc_attr( $value ) . '" type="' . esc_attr( $type ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" id="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" placeholder="' . esc_attr( $holder ) . '" />';
					break;
				case 'date-picker':
					$class .= ' ' . $type;
					$type  = 'text';
					$html  .= '<input value="' . esc_attr( $value ) . '" type="' . esc_attr( $type ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" id="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" placeholder="' . esc_attr( $holder ) . '" />';
					break;
				case 'file':
					$html = '<div class="vi-ui action input"><input type="text" readonly value="' . esc_attr( $value ) . '" /><input type="' . esc_attr( $type ) . '" value="' . esc_attr( $value ) . '" name="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" /><div class="vi-ui icon button"><i class="cloud upload icon"></i></div></div>';
					break;
				default:
					return;
			}

			return $html;
		}

		/**
		 * Text area
		 *
		 * @param array $arg
		 *
		 * @return string
		 */
		protected function textarea( $field = array() ) {
			$name   = isset( $field['name'] ) ? $field['name'] : '';
			$value  = isset( $field['value'] ) ? $this->get_option( $field['name'], $field['value'] ) : '';
			$holder = isset( $field['holder'] ) ? $field['holder'] : '';
			$class  = isset( $field['class'] ) ? $field['class'] : '';
			$cols   = isset( $field['cols'] ) ? $field['cols'] : 30;
			$rows   = isset( $field['rows'] ) ? $field['rows'] : 10;
			$html   = '<textarea cols="' . esc_attr( $cols ) . '" rows="' . esc_attr( $rows ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" id="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" placeholder="' . esc_attr( $holder ) . '" />' . esc_html( $value ) . '</textarea>';

			return $html;
		}

		/**
		 * Select field
		 *
		 * @param array $arg
		 *
		 * @return string|void
		 */
		protected function select( $field = array() ) {
			$name     = isset( $field['name'] ) ? $field['name'] : '';
			$value    = isset( $field['value'] ) ? $this->get_option( $field['name'], $field['value'] ) : '';
			$options  = isset( $field['options'] ) ? $field['options'] : array();
			$class    = isset( $field['class'] ) ? $field['class'] : '';
			$multiple = isset( $field['multiple'] ) ? $field['multiple'] : '';
			$disabled = isset( $field['disabled'] ) ? $field['disabled'] : array();
			$html     = '';
			if ( count( $options ) ) {
				if ( $multiple ) {
					$multiple = 'multiple="multiple"';
					$m_data   = '[]';
				} else {
					$multiple = '';
					$m_data   = '';
				}

				$html .= '<select ' . $multiple . ' name="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']' . $m_data . '" id="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" class="vi-ui fluid dropdown ' . esc_attr( $class ) . '">';
				foreach ( $options as $k => $title ) {
					$dis = $selected = '';
					if ( in_array( $k, $disabled ) ) {
						$dis = 'disabled';
					} else {
						if ( $multiple ) {
							if ( is_array( $value ) ) {
								if ( in_array( $k, $value ) ) {
									$selected = 'selected="selected"';
								}
							}
						} else {
							if ( selected( $k, $value, false ) ) {
								$selected = 'selected="selected"';;
							}
						}
					}

					$html .= '<option ' . $selected . ' value="' . esc_attr( $k ) . '" ' . $dis . '>' . esc_html( $title ) . '</option>';
				}
				$html .= '</select>';
			} else {
				return '';
			}

			return $html;
		}

		/**
		 * Process Product Ajax
		 *
		 * @param array $field
		 * @param string $product_type
		 *
		 * @return string
		 */
		protected function select2_ajax( $field = array(), $product_type = 'product' ) {
			if ( ! $product_type ) {
				$product_type = 'product';
			}
			$name        = isset( $field['name'] ) ? $field['name'] : '';
			$value       = isset( $field['value'] ) ? $this->get_option( $field['name'], $field['value'] ) : '';
			$class       = isset( $field['class'] ) ? $field['class'] : '';
			$multiple    = isset( $field['multiple'] ) ? $field['multiple'] : '';
			$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
			$html        = '';

			if ( $multiple ) {
				$multiple = 'multiple="multiple"';
				$m_data   = '[]';
			} else {
				$multiple = '';
				$m_data   = '';
			}

			$html .= '<select ' . $multiple . ' name="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']' . $m_data . '" id="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" class="' . esc_attr( $class ) . '" data-placeholder="' . esc_attr( $placeholder ) . '">';

			if ( $multiple && is_array( $value ) && count( $value ) ) {

				$args      = array(
					'post_status'    => 'public',
					'post_type'      => $product_type,
					'posts_per_page' => - 1,
					'post__in'       => $value

				);
				$the_posts = new WP_Query( $args );
				if ( $the_posts->have_posts() ) {
					while ( $the_posts->have_posts() ) {
						$the_posts->the_post();
						$html .= '<option selected="selected" value="' . esc_attr( get_the_ID() ) . '">' . esc_html( get_the_title() . '  #' . get_the_ID() ) . '</option>';
					}
				}
				wp_reset_postdata();
			} elseif ( $value ) {

				$args      = array(
					'post_status'    => 'public',
					'post_type'      => $product_type,
					'posts_per_page' => - 1,
					'post__in'       => array( $value )

				);
				$the_posts = new WP_Query( $args );
				if ( $the_posts->have_posts() ) {
					while ( $the_posts->have_posts() ) {
						$the_posts->the_post();
						$html .= '<option selected="selected" value="' . esc_attr( get_the_ID() ) . '">' . esc_html( get_the_title() . '  (#' . get_the_ID() ) . ')</option>';
					}
				}
				wp_reset_postdata();
			}

			$html .= '</select>';

			return $html;
		}

		/**
		 * Process Product Ajax
		 *
		 * @param array $field
		 * @param string $product_type
		 *
		 * @return string
		 */
		protected function select2_ajax_category( $field = array(), $taxonomy = 'product_cat' ) {
			if ( ! $taxonomy ) {
				$product_type = 'product_cat';
			}
			$name        = isset( $field['name'] ) ? $field['name'] : '';
			$value       = isset( $field['value'] ) ? $this->get_option( $field['name'], $field['value'] ) : '';
			$class       = isset( $field['class'] ) ? $field['class'] : '';
			$multiple    = isset( $field['multiple'] ) ? $field['multiple'] : '';
			$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
			$html        = '';

			if ( $multiple ) {
				$multiple = 'multiple="multiple"';
				$m_data   = '[]';
			} else {
				$multiple = '';
				$m_data   = '';
			}

			$html .= '<select ' . $multiple . ' name="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']' . $m_data . '" id="' . esc_attr( $this->prefix ) . '[' . $this->reformat_str( $name ) . ']" class="' . esc_attr( $class ) . '" data-placeholder="' . esc_attr( $placeholder ) . '">';

			if ( $multiple && is_array( $value ) && count( $value ) ) {
				foreach ( $value as $data ) {
					$term = get_term( $data, $taxonomy );
					$html .= '<option selected="selected" value="' . $term->term_id . '">' . $term->name . '</option>';
				}
			} elseif ( $value ) {

				$arg_p = array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => true,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'fields'     => 'id=>name'
				);

				$terms = get_terms( $arg_p );

				if ( is_array( $terms ) && count( $terms ) ) {
					foreach ( $terms as $key => $name ) {
						$html .= '<option selected="selected" value="' . $key . '">' . $name . '</option>';
					}
				}
				wp_reset_postdata();
			}

			$html .= '</select>';

			return $html;
		}

		/**
		 * Reformat string (ID, Class)
		 *
		 * @param string $string
		 *
		 * @return mixed|string
		 */
		private function reformat_str( $string = '' ) {
			$string = trim( $string );
			$string = preg_replace( '/\W/i', '_', $string );
			$string = strtolower( $string );

			return $string;
		}

	}

	new VillaTheme_Admin_Fields();
}