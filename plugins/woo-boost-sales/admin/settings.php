<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Admin_Settings {
	protected $settings;

	public function __construct() {
		$this->settings = VI_WOO_BOOSTSALES_Data::get_instance();
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_filter( 'wbs_data_settings', array( $this, 'set_options' ) );
		add_action( 'wbs_settings_end_of_tab_crosssell', array( $this, 'add_bundle_price_rule' ) );
		add_filter( 'admin_enqueue_scripts', array( $this, 'init_scripts' ), 999999 );
		add_action( 'wbs_settings_start_of_tab_upsell', array( $this, 'upsell_notice' ) );
		add_action( 'wbs_settings_start_of_tab_crosssell', array( $this, 'crosssell_notice' ) );
		add_action( 'wbs_settings_start_of_tab_frequently_product', array( $this, 'frequently_product_notice' ) );
	}

	public function frequently_product_notice( $settings ) {
		?>
        <div class="vi-ui message positive tiny">
            <div class="header"><?php esc_html_e( 'Frequently bought together shortcode:', 'woo-boost-sales' ) ?>
                <span class="wbs-frequently-bought-together-shortcode-message"
                      style="display: none;font-weight: 500;"><?php esc_html_e( 'Copied to clipboard', 'woo-boost-sales' ) ?></span>
            </div>
            <ul>
                <li>
                    <textarea rows="1" class="wbs-frequently-bought-together-shortcode" readonly>[wbs_frequently_product product_id="" show_attribute="click" select_type="button" message="Frequently bought together:"]</textarea>
                </li>
            </ul>
        </div>
		<?php
	}

	public function crosssell_notice( $settings ) {
		?>
        <div class="vi-ui message positive tiny">
            <div class="header"><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( __( 'To use this feature, please go to <a target="_blank" href="admin.php?page=woo-boost-sales-crosssell">Cross-Sells</a> page to create bundles', 'woo-boost-sales' ) ) ?></div>
        </div>
		<?php
	}

	public function upsell_notice( $settings ) {
		?>
        <div class="vi-ui message positive tiny">
            <div class="header"><?php esc_html_e( 'Upsells is a popup shown after a product is added to cart. If you limit number of items of upsells, this plugin will get upsells follow this order:', 'woo-boost-sales' ) ?></div>
            <ol>
                <li><?php printf( VI_WOO_BOOSTSALES_Data::wp_kses_post( __( '<strong>Products/categories</strong> you select on <a target="_blank" href="%s">Up-sells page</a>', 'woo-boost-sales' ) ), admin_url( 'admin.php?page=woo-boost-sales-upsell' ) ) ?></li>
                <li><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( __( '<strong>Recently Viewed Products</strong> if enabled', 'woo-boost-sales' ) ) ?></li>
                <li><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( __( '<strong>Products in category</strong> if enabled', 'woo-boost-sales' ) ) ?></li>
                <li><?php echo VI_WOO_BOOSTSALES_Data::wp_kses_post( __( '<strong>Upsell by tags</strong> if enabled', 'woo-boost-sales' ) ) ?></li>
            </ol>
        </div>
		<?php
	}

	protected function set_params( $name = '', $class = false, $multiple = false ) {
		if ( $name ) {
			if ( $class ) {
				echo esc_attr( 'wbs-crosssell-' . str_replace( '_', '-', $name ) );
			} else {
				if ( $multiple ) {
					echo esc_attr( 'wbs_crosssell_' . $name . '[]' );
				} else {
					echo esc_attr( 'wbs_crosssell_' . $name );
				}
			}
		}
	}

	/**
	 * @param $params VI_WOO_BOOSTSALES_Data
	 */
	public function add_bundle_price_rule( $params ) {
		?>
        <div class="vi-ui message">
            <div class="header"><?php esc_html_e( 'Recalculate bundle price.', 'woo-boost-sales' ); ?></div>
            <ul class="list">
                <li><?php esc_html_e( 'This is to set price for a bundle when it\'s created, not dynamic rules that can apply to price of all bundles in the store frontend', 'woo-boost-sales' ); ?></li>
            </ul>
        </div>
        <table class="optiontable form-table">
            <tbody class="<?php $this->set_params( 'price_rule_container', true ) ?>">
            <tr>
                <th scope="row"><?php esc_html_e( 'Bundle Price From', 'woo-boost-sales' ) ?></th>
                <th scope="row"><?php esc_html_e( 'Use dynamic price', 'woo-boost-sales' ) ?></th>
                <th scope="row"><?php esc_html_e( 'Discount Type', 'woo-boost-sales' ) ?></th>
                <th scope="row"><?php esc_html_e( 'Discount Value', 'woo-boost-sales' ) ?></th>
            </tr>
            <tr>
                <td colspan="4"><?php echo "<a target='_blank' class='vi-ui button' href='https://1.envato.market/yQBL3'>" . esc_html__( 'Upgrade to Premium version', 'woo-boost-sales' ) . "</a>"; ?></td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	public function init_scripts() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $page === 'woo-boost-sales' ) {
			wp_enqueue_script( 'woo-boost-sales-admin', VI_WOO_BOOSTSALES_JS . 'woo-boost-sales-admin.js', array( 'jquery' ), VI_WOO_BOOSTSALES_VERSION );
			wp_localize_script( 'woo-boost-sales-admin', 'wbs_admin_params', array( 'i18n_shortcode_copied' => esc_html__( 'Copied to clipboard', 'woo-boost-sales' ) ) );
			wp_enqueue_style( 'woo-boost-sales', VI_WOO_BOOSTSALES_CSS . 'woo-boost-sales-admin.css', array(), VI_WOO_BOOSTSALES_VERSION );
		}
	}

	/**
	 *
	 */
	public static function page_callback() {
		?>
        <div class="wrap woocommerce-boost-sales">
            <h2><?php esc_html_e( 'Boost Sales for WooCommerce Settings', 'woo-boost-sales' ) ?></h2>
			<?php
			do_action( 'villatheme_setting_html' );
			do_action( 'villatheme_support_woo-boost-sales' );
			?>
        </div>
		<?php
	}

	/**
	 * Get list option
	 * @return array
	 */
	public function set_options( $data ) {
		$data['general'] = array(
			'title'  => esc_html__( 'General', 'woo-boost-sales' ),
			'active' => true,
			'fields' => array(
				array(
					'name'        => 'enable',
					'type'        => 'checkbox',
					'value'       => 0,
					'description' => '',
					'label'       => esc_html__( 'Enable', 'woo-boost-sales' ),
					'options'     => array(
						'1' => esc_html__( 'Yes', 'woo-boost-sales' ),
					)
				),
				array(
					'name'        => 'enable_mobile',
					'type'        => 'checkbox',
					'value'       => 0,
					'description' => '',
					'label'       => esc_html__( 'Enable Mobile', 'woo-boost-sales' ),
					'options'     => array(
						'1' => esc_html__( 'Yes', 'woo-boost-sales' ),
					)
				)
			)
		);

		$data['upsell'] = array(
			'title'  => esc_html__( 'Upsell', 'woo-boost-sales' ),
			'fields' => array(
				array(
					'name'        => 'enable_upsell',
					'type'        => 'checkbox',
					'value'       => 0,
					'description' => '',
					'label'       => esc_html__( 'Enable', 'woo-boost-sales' ),
					'options'     => array(
						'1' => esc_html__( 'Yes', 'woo-boost-sales' ),
					)
				),
				array(
					'label' => esc_html__( 'Hide on Single Product Page', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Hide on Cart Page', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Hide on Checkout Page', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'hide_out_stock',
					'type'        => 'checkbox',
					'value'       => 0,
					'description' => '',
					'label'       => esc_html__( 'Hide out-of-stock products', 'woo-boost-sales' ),
					'options'     => array(
						'1' => esc_html__( 'Yes', 'woo-boost-sales' ),
					)
				),
				array(
					'label' => esc_html__( 'Hide Products Added to Cart', 'woo-boost-sales' ),
				),
				array(
					'label'       => esc_html__( 'Go to cart page', 'woo-boost-sales' ),
					'description' => esc_html__( 'Go to cart page when product is added to cart on up sells.', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Recently Viewed Products', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'show_with_category',
					'type'        => 'checkbox',
					'value'       => 0,
					'description' => esc_html__( 'Upsell popup will show products in the same category. Upsell products of Upsells page will not use.', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Products in category', 'woo-boost-sales' ),
					'options'     => array(
						'1' => esc_html__( 'Yes', 'woo-boost-sales' ),
					)
				),

//				array(
//					'name'        => 'show_upsells_checkbox',
//					'class'       => 'wbs-products-in-category',
//					'type'        => 'select',
//					'value'       => 0,
//					'description' => esc_html__( 'Customer can add to cart many upsell products to cart.', 'woo-boost-sales' ),
//					'label'       => esc_html__( 'Show up products ', 'woo-boost-sales' ),
//					'options'     => array(
//						'0' => esc_html__( 'Not Show', 'woo-boost-sales' ),
//						'1' => esc_html__( 'Show above description', 'woo-boost-sales' )
//					)
//				),
				array(
					'description' => esc_html__( 'Only get products from current subcategory. It is the end subcategory.', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Only Subcategory', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Exclude products to enable upsell', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Exclude products that display in upsell popup', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Exclude categories to enable upsell', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Exclude categories that display in upsell popup', 'woo-boost-sales' ),
				),
				array(
					'description' => esc_html__( 'Upsell products which have the same tags as the main product', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Upsell by tags', 'woo-boost-sales' ),
				),

				array(
					'name'        => 'sort_product',
					'type'        => 'select',
					'value'       => '0',
					'description' => '',
					'label'       => esc_html__( 'Sort by', 'woo-boost-sales' ),
					'class'       => 'wbs_exclude_product',
					'options'     => array(
						'0' => esc_html__( 'Title A-Z', 'woo-boost-sales' ),
						'1' => esc_html__( 'Title Z-A', 'woo-boost-sales' ),
						'2' => esc_html__( 'Price highest', 'woo-boost-sales' ),
						'3' => esc_html__( 'Price lowest', 'woo-boost-sales' ),
						'4' => esc_html__( 'Random', 'woo-boost-sales' ),
						'5' => esc_html__( 'Best Selling', 'woo-boost-sales' ),
					)
				),
				array(
					'description' => esc_html__( 'Use ajax add to cart on single product page.', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Ajax Add To Cart', 'woo-boost-sales' ),
				),
				array(
					'description' => esc_html__( 'Do not redirect page when customers add up-sells products to their cart. This will override option "Go to cart page". This will not apply if the "Add-to-cart style" option is set to Theme default', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Ajax add to cart for product on up-sell popup', 'woo-boost-sales' ),
				),
				array(
					'description' => esc_html__( 'Show upsell popup even if there\'s no upsells', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Show if empty', 'woo-boost-sales' ),
				),
			)
		);

		$data['crosssell']          = array(
			'title'  => esc_html__( 'Cross sell', 'woo-boost-sales' ),
			'fields' => array(
				array(
					'name'    => 'crosssell_enable',
					'type'    => 'checkbox',
					'value'   => 0,
					'label'   => esc_html__( 'Enable', 'woo-boost-sales' ),
					'options' => array(
						'1' => esc_html__( 'Yes', 'woo-boost-sales' ),
					),
				),
				array(
					'label' => esc_html__( 'Hide on Single Product Page', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'crosssell_display_on',
					'type'        => 'select',
					'value'       => 0,
					'label'       => esc_html__( 'Display on', 'woo-boost-sales' ),
					'options'     => array(
						'0' => esc_html__( 'Popup', 'woo-boost-sales' ),
						'1' => esc_html__( 'Below Add to cart button(Premium feature)', 'woo-boost-sales' ),
						'2' => esc_html__( 'Above Description Tab(Premium feature)', 'woo-boost-sales' ),
						'3' => esc_html__( 'Below description(Premium feature)', 'woo-boost-sales' ),
						'4' => esc_html__( 'Custom Hook(Premium feature)', 'woo-boost-sales' ),
					),
					'description' => __( 'Select how/where you want to show cross sell on single product', 'woo-boost-sales' ),
					'disabled'    => array( 1, 2, 3, 4 )
				),
				array(
					'name'        => 'crosssell_display_on_slide',
					'type'        => 'checkbox',
					'value'       => 0,
					'label'       => esc_html__( 'Slide', 'woo-boost-sales' ),
					'options'     => array(
						'1' => esc_html__( 'Yes', 'woo-boost-sales' ),
					),
					'description' => esc_html__( 'Make slider for cross-sells', 'woo-boost-sales' ),
					'class'       => 'crosssell_display_on'
				),
				array(
					'label' => esc_html__( 'Show on Cart page', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Show on Checkout page', 'woo-boost-sales' ),
				),
				array(
					'name'            => 'crosssell_description',
					'type'            => 'text',
					'value'           => esc_html__( 'Hang on! We have this offer just for you!', 'woo-boost-sales' ),
					'label'           => esc_html__( 'Description', 'woo-boost-sales' ),
					'is_multilingual' => true,
				),
				array(
					'name'        => 'display_saved_price',
					'type'        => 'select',
					'value'       => 0,
					'label'       => esc_html__( 'Display saved price', 'woo-boost-sales' ),
					'options'     => array(
						'0' => esc_html__( 'Price', 'woo-boost-sales' ),
						'1' => esc_html__( 'Percent', 'woo-boost-sales' ),
						'2' => esc_html__( 'None', 'woo-boost-sales' ),
					),
					'description' => esc_html__( 'Display saved price on cross-sell.', 'woo-boost-sales' ),
					'disabled'    => array( 1, 2 )
				),
				array(
					'label'       => esc_html__( 'Override products', 'woo-boost-sales' ),
					'description' => esc_html__( 'Remove the same products on cart when add combo.', 'woo-boost-sales' )
				),
				array(
					'description' => esc_html__( 'Do not redirect page when customers add bundle to their cart on single product page', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Ajax add to cart for bundle', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'hide_out_of_stock',
					'type'        => 'checkbox',
					'value'       => 0,
					'description' => esc_html__( 'Do not show crosssell if one of bundle items is out of stock', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Hide out of stock', 'woo-boost-sales' ),
					'options'     => array(
						'1' => esc_html__( 'Yes', 'woo-boost-sales' ),
					)
				),
				array(
					'name'        => 'product_bundle_name',
					'type'        => 'text',
					'value'       => 'Bundle of {product_title}',
					'label'       => esc_html__( 'Product bundle name', 'woo-boost-sales' ),
					'description' => __( 'Name of product bundle when creating new bundle. {product_title} refers to the title of main product that the bundle is created for.<p>e.g when you create a bundle for product named "Product A", if Product bundle name is set "Bundle of {product_title}" then new bundle\'s name will be "Bundle of Product A"</p>', 'woo-boost-sales' )
				),
				array(
					'label'       => esc_html__( 'Bundle categories', 'woo-boost-sales' ),
					'description' => __( 'Default categories when you create new bundle', 'woo-boost-sales' ),
				),
			)
		);
		$data['frequently_product'] = array(
			'title'  => esc_html__( 'Frequently Bought Together', 'woo-boost-sales' ),
			'fields' => array(
				array(
					'name'        => 'frequently_product',
					'type'        => 'checkbox',
					'value'       => 0,
					'description' => '',
					'label'       => esc_html__( 'Enable', 'woo-boost-sales' ),
					'options'     => array(
						'1' => esc_html__( 'Yes', 'woo-boost-sales' ),
					)
				),
				array(
					'label' => esc_html__( 'Hide if added', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'frequently_product_source',
					'type'        => 'select',
					'value'       => 'up_sells',
					'label'       => esc_html__( 'Source', 'woo-boost-sales' ),
					'options'     => array(
						'cross_sells'     => esc_html__( 'Cross sells - Same as Cross sells products(Premium feature)', 'woo-boost-sales' ),
						'up_sells'        => esc_html__( 'Up sells - Same as Up sells products', 'woo-boost-sales' ),
						'woo_up_sells'    => esc_html__( 'Woo Upsells - Products set in Edit product/Link products/Upsells(Premium feature)', 'woo-boost-sales' ),
						'woo_cross_sells' => esc_html__( 'Woo Cross-sells - Products set in Edit product/Link products/Cross-sells(Premium feature)', 'woo-boost-sales' ),
						'any'             => esc_html__( 'Any of the above(Premium feature)', 'woo-boost-sales' ),
					),
					'description' => __( 'Which products will be displayed in Frequently Bought Together?', 'woo-boost-sales' ),
					'disabled'    => array( 'cross_sells', 'woo_up_sells', 'woo_cross_sells', 'any' )
				),
				array(
					'name'        => 'frequently_product_position',
					'type'        => 'select',
					'value'       => 'after_cart',
					'label'       => esc_html__( 'Position', 'woo-boost-sales' ),
					'options'     => array(
						'after_cart'            => esc_html__( 'After Add to cart form', 'woo-boost-sales' ),
						'after_product_summary' => esc_html__( 'After product summary(Premium feature)', 'woo-boost-sales' ),
						'after_product_tabs'    => esc_html__( 'After product tabs(Premium feature)', 'woo-boost-sales' ),
					),
					'description' => __( 'Please select a position on single product page where you want to display Frequently Bought Together', 'woo-boost-sales' ),
					'disabled'    => array( 'after_product_summary', 'after_product_tabs' )
				),
				array(
					'name'        => 'frequently_product_style',
					'type'        => 'select',
					'value'       => 'vertical',
					'label'       => esc_html__( 'Style', 'woo-boost-sales' ),
					'options'     => array(
						'vertical'   => esc_html__( 'Vertical', 'woo-boost-sales' ),
						'horizontal' => esc_html__( 'Horizontal(Premium feature)', 'woo-boost-sales' ),
					),
					'description' => __( 'Only use Horizontal style if the container is wide enough. On Mobile, it will automatically switch to Vertical style no matter what you select.', 'woo-boost-sales' ),
					'disabled'    => array( 'horizontal' )
				),
				array(
					'name'        => 'frequently_product_image_size',
					'type'        => 'select',
					'value'       => $this->settings->get_option( 'frequently_product_image_size' ),
					'label'       => esc_html__( 'Image size(px)', 'woo-boost-sales' ),
					'options'     => array(
						36 => esc_html__( '36', 'woo-boost-sales' ),
						48 => esc_html__( '48', 'woo-boost-sales' ),
						64 => esc_html__( '64', 'woo-boost-sales' ),
						75 => esc_html__( '75', 'woo-boost-sales' ),
					),
					'description' => __( 'This option is only used for Vertical style.', 'woo-boost-sales' )
				),
				array(
					'name'        => 'frequently_product_currently_watching',
					'type'        => 'select',
					'value'       => $this->settings->get_option( 'frequently_product_currently_watching' ),
					'description' => esc_html__( 'If Frequently Bought Together products source is Cross sells and the option "Add bundle instead of items separately" is on, currently watching product will always show', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Currently watching product', 'woo-boost-sales' ),
					'options'     => array(
						'show'              => esc_html__( 'Always show', 'woo-boost-sales' ),
						'show_if_not_added' => esc_html__( 'Only show if the currently watching product is not in the cart yet(Premium feature)', 'woo-boost-sales' ),
						'hide'              => esc_html__( 'Hide(Premium feature)', 'woo-boost-sales' ),
					),
					'disabled'    => array( 'show_if_not_added', 'hide' )
				),

				array(
					'name'        => 'frequently_product_currently_watching_text',
					'type'        => 'text',
					'value'       => $this->settings->get_option( 'frequently_product_currently_watching_text' ),
					'description' => esc_html__( '', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Currently watching product text', 'woo-boost-sales' ),
				),
				array(
					'value'       => esc_html__( 'Frequently bought together:', 'woo-boost-sales' ),
					'description' => esc_html__( '{product_title}: Title of main product', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Message', 'woo-boost-sales' ),
				),
				array(
					'name'            => 'frequently_product_add_to_cart_text',
					'type'            => 'text',
					'value'           => $this->settings->get_option( 'frequently_product_add_to_cart_text' ),
					'description'     => esc_html__( '{number_of_items}: Number of selected items when clicking Add to cart button', 'woo-boost-sales' ),
					'label'           => esc_html__( 'Add to cart text', 'woo-boost-sales' ),
					'is_multilingual' => true,
				),
				array(
					'label' => esc_html__( 'Add bundle to cart', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Load with Ajax', 'woo-boost-sales' ),
				),
				array(
					'value'       => 1,
					'description' => esc_html__( 'If product title is too long and take many lines to display, it will be cut off. Set 0 to not cut off long product title', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Number of lines for product title', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Show product rating', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'frequently_product_show_attribute',
					'type'        => 'select',
					'value'       => $this->settings->get_option( 'frequently_product_show_attribute' ),
					'description' => esc_html__( 'This option is used for variable products', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Show attributes selection when', 'woo-boost-sales' ),
					'options'     => array(
						'click' => esc_html__( 'Click', 'woo-boost-sales' ),
						'hover' => esc_html__( 'Hover', 'woo-boost-sales' ),
					)
				),
				array(
					'name'        => 'frequently_product_select_type',
					'type'        => 'select',
					'value'       => $this->settings->get_option( 'frequently_product_select_type' ),
					'description' => esc_html__( 'This option is used for variable products', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Attributes selection type', 'woo-boost-sales' ),
					'options'     => array(
						'select' => esc_html__( 'Select', 'woo-boost-sales' ),
						'button' => esc_html__( 'Button', 'woo-boost-sales' ),
					)
				),
				array(
					'name'        => 'frequently_product_after_successful_atc',
					'type'        => 'select',
					'value'       => 'none',
					'description' => esc_html__( 'What to do after successfully adding product(s) to cart', 'woo-boost-sales' ),
					'label'       => esc_html__( 'After product is added to cart', 'woo-boost-sales' ),
					'options'     => array(
						'none'              => esc_html__( 'Do nothing', 'woo-boost-sales' ),
						'redirect_cart'     => esc_html__( 'Redirect to Cart(Premium feature)', 'woo-boost-sales' ),
						'redirect_checkout' => esc_html__( 'Redirect to Checkout(Premium feature)', 'woo-boost-sales' ),
						'hide'              => esc_html__( 'Hide(Premium feature)', 'woo-boost-sales' ),
					),
					'disabled'    => array( 'redirect_cart', 'redirect_checkout', 'hide' )
				),
				array(
					'name'        => '',
					'type'        => '',
					'do_action'   => 'woo-boost-sales-settings-frequently_product',
					'value'       => '',
					'label'       => '',
					'description' => ''
				)
			)
		);
		$data['discount']           = array(
			'title'  => esc_html__( 'Discount', 'woo-boost-sales' ),
			'fields' => array(
				array(
					'label' => esc_html__( 'Enable', 'woo-boost-sales' ),
				),
				array(
					'description' => esc_html__( 'If this option is disabled, discount bar will only show each time a customer add a product to cart', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Always show discount bar', 'woo-boost-sales' ),
				),
				array(
					'description' => esc_html__( 'If 2 coupons have the name - the latest coupon will be used.', 'woo-boost-sales' ) . esc_html__( 'Dashboard >> WooCommerce >> Coupons >>', 'woo-boost-sales' ) . '<a target="_bank" href="' . esc_url( admin_url( 'post-new.php?post_type=shop_coupon' ) ) . '">' . esc_html__( 'Add New Coupon', 'woo-boost-sales' ) . '</a>',
					'label'       => esc_html__( 'Select Coupon', 'woo-boost-sales' ),
				),
				array(
					'description' => esc_html__( '{discount_amount} - The number of discount.', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Head line', 'woo-boost-sales' ),
				),
				array(
					'description' => esc_html__( 'Congrats when get coupon', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Thank You', 'woo-boost-sales' ),
				),
				array(
					'description' => esc_html__( '{discount_amount} - The number of discount', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Congratulation message', 'woo-boost-sales' ),
				),
				array(
					'value' => esc_html__( 'Checkout now', 'woo-boost-sales' ),
					'label' => esc_html__( 'Checkout button title', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Auto redirect to checkout', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Redirect after second', 'woo-boost-sales' ),
				),
			)
		);
		$data['design']             = array(
			'title'  => esc_html__( 'Design', 'woo-boost-sales' ),
			'fields' => array(
				array(
					'type'  => 'title',
					'value' => esc_html__( 'General', 'woo-boost-sales' )
				),
				array(
					'name'  => 'button_bg_color',
					'type'  => 'color-picker',
					'value' => '#bdbdbd',
					'label' => esc_html__( 'Button background color', 'woo-boost-sales' ),
				),
				array(
					'name'  => 'button_color',
					'type'  => 'color-picker',
					'value' => '#111111',
					'label' => esc_html__( 'Button background color on hovering', 'woo-boost-sales' ),
				),
				array(
					'type'  => 'title',
					'value' => esc_html__( 'Cross-Sells', 'woo-boost-sales' )
				),
				array(
					'value'       => '3,10',
					'label'       => esc_html__( 'Init delay', 'woo-boost-sales' ),
					'description' => esc_html__( 'Cross-sell will show with popup or gift icon. If you want to time randomly, 2 numbers are separated by comma. Eg: 3,20. It is random from 3 to 20.', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Auto popup', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Hide Gift Icon', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'icon',
					'type'        => 'select',
					'value'       => 0,
					'description' => '',
					'label'       => esc_html__( 'Icon ', 'woo-boost-sales' ),
					'options'     => array(
						'0' => esc_html__( 'Default', 'woo-boost-sales' ),
						'1' => esc_html__( 'Gift box(Premium feature)', 'woo-boost-sales' ),
						'2' => esc_html__( 'Custom(Premium feature)', 'woo-boost-sales' ),
					),
					'disabled'    => array( '1', '2' )
				),
				array(
					'label'       => esc_html__( 'Custom Gift Box Icon', 'woo-boost-sales' ),
					'description' => esc_html__( 'Dimension should be 58x58(px). Please change "Icon Option" to "Custom"', 'woo-boost-sales' ),
				),
				array(
					'value'       => '#555',
					'description' => esc_html__( 'Only apply with Icon default', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Icon Color', 'woo-boost-sales' ),
				),
				array(
					'value'       => '#fff',
					'description' => esc_html__( 'Only apply with Icon default', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Icon Background Color', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'icon_position',
					'type'        => 'select',
					'value'       => 0,
					'description' => '',
					'label'       => esc_html__( 'Icon Position', 'woo-boost-sales' ),
					'options'     => array(
						'0' => esc_html__( 'Bottom right', 'woo-boost-sales' ),
						'1' => esc_html__( 'Botton left', 'woo-boost-sales' )
					)
				),
				array(
					'value'       => '#ffffff',
					'description' => esc_html__( 'Background color for popup cross-sell.', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Background Color', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Background Image', 'woo-boost-sales' ),
				),
				array(
					'value' => '#9e9e9e',
					'label' => esc_html__( 'Text Color', 'woo-boost-sales' ),
				),
				array(
					'value' => '#111111',
					'label' => esc_html__( 'Price Color', 'woo-boost-sales' ),
				),
				array(
					'value' => '#111111',
					'label' => esc_html__( 'Save Price Color', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'crosssell_template',
					'type'        => 'select',
					'value'       => $this->settings->get_option( 'crosssell_template' ),
					'description' => '',
					'label'       => esc_html__( 'Template', 'woo-boost-sales' ),
					'options'     => array(
						'slider'     => esc_html__( 'Slider', 'woo-boost-sales' ),
						'vertical'   => esc_html__( 'Vertical with checkbox(Premium feature)', 'woo-boost-sales' ),
						'horizontal' => esc_html__( 'Horizontal with checkbox(Premium feature)', 'woo-boost-sales' ),
					),
					'disabled'    => array( 'vertical', 'horizontal' )
				),
				array(
					'name'        => 'crosssell_mobile_template',
					'type'        => 'select',
					'value'       => 'slider',
					'description' => '',
					'label'       => esc_html__( 'Template on mobile', 'woo-boost-sales' ),
					'options'     => array(
						'slider'   => esc_html__( 'Slider', 'woo-boost-sales' ),
						'scroll'   => esc_html__( 'Scroll(Premium feature)', 'woo-boost-sales' ),
						'vertical' => esc_html__( 'Vertical with checkbox(Premium feature)', 'woo-boost-sales' )
					),
					'disabled'    => array( 'vertical', 'scroll' )
				),
				array(
					'type'  => 'title',
					'value' => esc_html__( 'Upsells', 'woo-boost-sales' )
				),
				array(
					'value' => '4',
					'label' => esc_html__( 'Item per row', 'woo-boost-sales' ),
				),
				array(
					'value' => '1',
					'label' => esc_html__( 'Item per row for Mobile', 'woo-boost-sales' ),
				),
				array(
					'value'       => '8',
					'label'       => esc_html__( 'Max item', 'woo-boost-sales' ),
					'description' => esc_html__( 'Maximum number of upsells per product. Used only if "Products in category" is enabled.', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Popup style', 'woo-boost-sales' ),
				),
				array(
					'name'            => 'message_bought',
					'type'            => 'text',
					'value'           => 'Frequently bought with {name_product}',
					'description'     => esc_html__( '{name_product} - The name of product purchased', 'woo-boost-sales' ),
					'label'           => esc_html__( 'Message in popup', 'woo-boost-sales' ),
					'is_multilingual' => true,
				),
				array(
					'label' => esc_html__( 'Add-to-cart style', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'upsell_mobile_template',
					'type'        => 'select',
					'value'       => 'slider',
					'description' => '',
					'label'       => esc_html__( 'Template on mobile', 'woo-boost-sales' ),
					'options'     => array(
						'slider' => esc_html__( 'Slider', 'woo-boost-sales' ),
						'scroll' => esc_html__( 'Scroll(Premium feature)', 'woo-boost-sales' )
					),
					'disabled'    => array( 'scroll' )
				),
				array(
					'label' => esc_html__( 'Add-to-cart style on mobile', 'woo-boost-sales' ),
				),
				array(
					'value' => 'Continue Shopping',
					'label' => esc_html__( 'Button "Continue Shopping" title', 'woo-boost-sales' ),
				),
				array(
					'name'        => 'continue_shopping_action',
					'type'        => 'select',
					'value'       => 'stay',
					'description' => '',
					'label'       => esc_html__( 'Button "Continue Shopping" action', 'woo-boost-sales' ),
					'options'     => array(
						'stay' => esc_html__( 'Just close popup', 'woo-boost-sales' ),
						'shop' => esc_html__( 'Go to Shop page(Premium feature)', 'woo-boost-sales' ),
						'home' => esc_html__( 'Go to Home page(Premium feature)', 'woo-boost-sales' ),
					),
					'disabled'    => array( 'shop', 'home' )
				),
				array(
					'name'    => 'hide_view_more_button',
					'type'    => 'checkbox',
					'value'   => 0,
					'label'   => esc_html__( 'Hide view more button', 'woo-boost-sales' ),
					'options' => array(
						'1' => esc_html__( 'Yes', 'woo-boost-sales' ),
					)
				),

				array(
					'type'  => 'title',
					'value' => esc_html__( 'Discount Bar', 'woo-boost-sales' )
				),
				array(
					'label' => esc_html__( 'Select Position', 'woo-boost-sales' ),
				),
				array(
					'description' => esc_html__( 'Color for text of process bar.', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Text color', 'woo-boost-sales' ),
				),
				array(
					'label' => esc_html__( 'Process bar main color', 'woo-boost-sales' ),
				),
				array(
					'description' => esc_html__( 'Color of main process bar.', 'woo-boost-sales' ),
					'label'       => esc_html__( 'Process bar background color', 'woo-boost-sales' ),
				),
				array(
					'type'  => 'title',
					'value' => esc_html__( 'Custom', 'woo-boost-sales' )
				),
				array(
					'name'        => 'custom_css',
					'type'        => 'textarea',
					'value'       => '',
					'description' => '',
					'label'       => esc_html__( 'Custom CSS', 'woo-boost-sales' ),
				),
			)
		);

		return $data;
	}

	protected function get_url_template( $src ) {
		$imag = '<img src="' . VI_WOO_BOOSTSALES_IMAGES . $src . '" />';
		if ( $src ) {
			return $imag;
		}

		return '';
	}

	/**
	 * Register a custom menu page.
	 */
	public function menu_page() {
		add_menu_page(
			esc_html__( 'Boost Sales for WooCommerce', 'woo-boost-sales' ), esc_html__( 'Woo Boost Sales', 'woo-boost-sales' ), 'manage_options', 'woo-boost-sales', array(
			$this,
			'page_callback'
		), 'dashicons-chart-line', 2
		);
	}
}