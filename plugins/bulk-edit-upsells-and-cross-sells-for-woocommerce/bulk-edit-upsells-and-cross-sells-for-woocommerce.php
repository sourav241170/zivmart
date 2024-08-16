<?php
/**
 * Plugin Name: Bulk Edit Upsells and Cross-Sells for WooCommerce
 * Description: Bulk Edit Upsells and Cross-sells for WooCommerce is a fast and easy way to set or update upsells and cross-sells for products in your WooCommerce catalog.
 * Author URI:  https://www.saffiretech.com
 * Author:      SaffireTech
 * Text Domain: bulk-edit-upsells-and-cross-sells-for-woocommerce
 * Domain Path: /languages
 * Stable Tag : 2.0.5
 * Requires at least: 5.0
 * Tested up to: 6.4.2
 * Requires PHP: 7.2
 * WC requires at least: 4.0.0
 * WC tested up to: 8.5.2
 * License:     GPLv3
 * License URI: URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Version:     2.0.5
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

define( 'BUCW_BULK_EDIT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Check the installation of pro version.
 *
 * @return bool
 */
function beucw_check_pro_version() {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	if ( is_plugin_active( 'woocommerce-related-products-pro/woocommerce-related-products-pro.php' ) ) {
		return true;
	} else {
		return false;
	}
}

add_action( 'plugins_loaded', 'beucw_free_plugin_install' );

/**
 * Display notice if pro plugin found.
 */
function beucw_free_plugin_install() {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	// if pro plugin found deactivate free plugin.
	if ( beucw_check_pro_version() ) {

		deactivate_plugins( plugin_basename( __FILE__ ), true ); // deactivate free plugin if pro found.

		if ( defined( 'RPROW_PRO_PLUGIN' ) ) {
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
			add_action( 'admin_notices', 'beucw_install_free_admin_notice' );
		}
	}
}

/**
 * Add message if pro version is installed.
 */
function beucw_install_free_admin_notice() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php esc_html_e( 'Bulk Edit Upsells and Cross-Sells for WooCommerce Pro Activated', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ); ?></p>
	</div>
	<?php
}

add_action( 'init', 'beucw_upsells_crosssells_include_file' );

/**
 * This function includes all required file.
 */
function beucw_upsells_crosssells_include_file() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if ( ! beucw_check_pro_version() ) {
		if ( ( file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'includes/beucw-settings.php';
			require_once plugin_dir_path( __FILE__ ) . 'includes/beucw-functions.php';
		}
	}

	// load the css file.
	wp_enqueue_style( 'beucw_upsells_css', plugins_url( 'assets/css/beucw-bulk-upsells-crosssells.css', __FILE__ ), array(), '2.0.2' );
}

add_action( 'admin_enqueue_scripts', 'beucw_upsells_assets' );

/**
 * Function enqueue different library.
 *
 * @param string $hook .
 */
function beucw_upsells_assets( $hook ) {

	if ( 'product_page_bulk-edit-upsells-crosssells' === $hook ) {

		if ( ! beucw_check_pro_version() ) {

			wp_enqueue_script( 'jquery' );

			wp_enqueue_style( 'bucw_upsells_css', plugins_url( 'assets/css/beucw-bulk-upsells-crosssells.css', __FILE__ ), array(), '2.0.2' );
			wp_enqueue_style( 'bucw_sweetalert2_css', plugins_url( 'assets/css/sweetalert2.min.css', __FILE__ ), array(), '10.10.1' );
			wp_enqueue_style( 'bucw_select2', plugins_url( 'assets/css/select2.min.css', __FILE__ ), array(), '10.10.1' );

			wp_enqueue_style( 'bucw_font_icons', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'bucw_sweetalert2_js', plugins_url( 'assets/js/sweetalert2.all.min.js', __FILE__ ), array(), '10.10.1', false );
			wp_enqueue_script( 'bucw_select2', plugins_url( 'assets/js/select2.min.js', __FILE__ ), array(), '0.10.0', false );

			wp_register_script( 'bucw_upsells_js', plugins_url( 'assets/js/beucw-bulk-upsells-crosssells.js', __FILE__ ), array( 'jquery', 'jquery-ui-autocomplete' ), '2.0.2', true );
			wp_enqueue_script( 'bucw_upsells_js' );
			wp_localize_script(
				'bucw_upsells_js',
				'upsellajaxapi',
				array(
					'url'   => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ),
				)
			);

			wp_set_script_translations( 'bucw_upsells_js', 'bulk-edit-upsells-and-cross-sells-for-woocommerce', plugin_dir_path( __FILE__ ) . 'languages/' );
		}
	}
}

add_action( 'admin_init', 'beucw_load_plugin_textdomain_file' );

/**
 * To load text domain files.
 */
function beucw_load_plugin_textdomain_file() {
	load_plugin_textdomain( 'bulk-edit-upsells-and-cross-sells-for-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'beucw_action_links_callback', 10, 1 );

/**
 * Settings link in plugin page.
 *
 * @param array $links links Plugin links on plugins.php.
 * @return array
 */
function beucw_action_links_callback( $links ) {

	// Add the plugin settings link.
	$settinglinks = array(
		'<a href="' . admin_url( 'admin.php?page=bulk-edit-upsells-crosssells' ) . '">' . __( 'Setting', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ) . '</a>',
		'<a class="beucw-setting-upgrade" href="https://www.saffiretech.com/woocommerce-related-products-pro?utm_source=wp_plugin&utm_medium=plugins_archive&utm_campaign=free2pro&utm_id=c1&utm_term=upgrade_now&utm_content=beucw" target="_blank">' . __( 'UpGrade to Pro !', 'bulk-edit-upsells-and-cross-sells-for-woocommerce' ) . '</a>',
	);

	return array_merge( $settinglinks, $links );
}


// HPOS Compatibility.
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);
