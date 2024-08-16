<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VI_WOO_BOOSTSALES_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-boost-sales" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOOSTSALES_ADMIN', VI_WOO_BOOSTSALES_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOOSTSALES_PLUGIN', VI_WOO_BOOSTSALES_DIR . "plugins" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOOSTSALES_FRONTEND', VI_WOO_BOOSTSALES_DIR . "frontend" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOOSTSALES_LANGUAGES', VI_WOO_BOOSTSALES_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOOSTSALES_INCLUDES', VI_WOO_BOOSTSALES_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOOSTSALES_TEMPLATES', VI_WOO_BOOSTSALES_DIR . "templates" . DIRECTORY_SEPARATOR );
$plugin_url = plugins_url( '', __FILE__ );
$plugin_url = str_replace( '/includes', '', $plugin_url );
define( 'VI_WOO_BOOSTSALES_CSS', $plugin_url . "/css/" );
define( 'VI_WOO_BOOSTSALES_CSS_DIR', VI_WOO_BOOSTSALES_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOOSTSALES_JS', $plugin_url . "/js/" );
define( 'VI_WOO_BOOSTSALES_JS_DIR', VI_WOO_BOOSTSALES_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_BOOSTSALES_IMAGES', $plugin_url . "/images/" );

/*Include functions file*/
if ( is_file( VI_WOO_BOOSTSALES_INCLUDES . "mobile_detect.php" ) ) {
	require_once VI_WOO_BOOSTSALES_INCLUDES . "mobile_detect.php";
}
/*Include functions file*/
if ( is_file( VI_WOO_BOOSTSALES_INCLUDES . "data.php" ) ) {
	require_once VI_WOO_BOOSTSALES_INCLUDES . "data.php";
}
if ( is_file( VI_WOO_BOOSTSALES_INCLUDES . "upsells.php" ) ) {
	require_once VI_WOO_BOOSTSALES_INCLUDES . "upsells.php";
}
if ( is_file( VI_WOO_BOOSTSALES_INCLUDES . "cross-sells.php" ) ) {
	require_once VI_WOO_BOOSTSALES_INCLUDES . "cross-sells.php";
}
if ( is_file( VI_WOO_BOOSTSALES_INCLUDES . "functions.php" ) ) {
	require_once VI_WOO_BOOSTSALES_INCLUDES . "functions.php";
}
/*Include functions file*/
if ( is_file( VI_WOO_BOOSTSALES_INCLUDES . "fields.php" ) ) {
	require_once VI_WOO_BOOSTSALES_INCLUDES . "fields.php";
}
if ( is_file( VI_WOO_BOOSTSALES_INCLUDES . "support.php" ) ) {
	require_once VI_WOO_BOOSTSALES_INCLUDES . "support.php";
}
vi_include_folder( VI_WOO_BOOSTSALES_ADMIN, 'VI_WOO_BOOSTSALES_Admin_' );
vi_include_folder( VI_WOO_BOOSTSALES_FRONTEND, 'VI_WOO_BOOSTSALES_Frontend_' );
vi_include_folder( VI_WOO_BOOSTSALES_PLUGIN, 'VI_WOO_BOOSTSALES_PLUGINS_' );
