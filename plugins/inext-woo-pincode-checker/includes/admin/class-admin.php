<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Include Admin Files**/
if ( is_admin() ) {
    /** Menu **/
    require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/admin/class-admin-menu.php' );

	/** Ajax **/
	require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/ajax/class-admin-ajax.php' );

	/** Action Hooks **/
	require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'includes/action/class-admin-action.php' );

	/** Assets **/
	require_once( INEXT_WPC_PLUGIN_NAME_DIR . 'assets/class-admin-assets.php' );
}
