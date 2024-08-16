<?php
/**
 * Plugin Name:       iNext Woo Pincode Checker
 * Plugin URI:        https://plugins.imdadnextweb.com
 * Description:       A powerful plugin that makes your ecommerce site more engaging. It allows the admin to enable the pincode checker feature on their site with 100% ajax based iNext Woo Pincode Checker.
 * Version:           2.0.2
 * Requires at least: 5.0.1
 * Requires PHP:      8.0
 * Author:            Imdad Next Web
 * Author URI:        https://imdadnextweb.com
 * License:           GPL
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       inext-woo-pincode-checker
 * Domain Path:       /languages
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/** Constants **/
define('INEXT_WPC_PLUGIN_GLOBAL_NAME', 'INEXT_WPC' );
define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_FILE', __FILE__ );
define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_NAME_DIR', plugin_dir_path( __FILE__ ) );
define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_NAME_URL', plugin_dir_url( __FILE__ ) );
define(INEXT_WPC_PLUGIN_GLOBAL_NAME . '_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/core/class-constant.php' );

/** Variables **/
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/core/class-variable.php' );

/** Init Classes **/
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/core/class-init.php' );
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/core/class-notice.php' );
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/core/class-dependency.php' );
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/core/class-js-variables.php' );

/** Register Activation and Deactivation Hooks **/
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/core/class-activation.php' );
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/core/class-deactivation.php' );

/** Ajax **/
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/ajax/class-ajax.php' );

/** Action Hooks **/
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/action/class-action.php' );

/** Views **/
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'views/class-views.php' );

/** Assets **/
require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'assets/class-assets.php' );

/** Include Admin **/
if ( is_admin() ) {
    require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/admin/class-admin.php' );
}
