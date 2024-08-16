<?php
/**
* Fired when the plugin is uninstalled.
**
* iNext Woo Pincode Checker Uninstall
*
* Uninstalling iNext Woo Pincode Checker deletes prefined options which are using on this plugin.
*
 * @link       https://imdadnextweb.com
 * @since      1.0.0
 *
 * @package    INEXT_WPC
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) :
	exit;
endif;
