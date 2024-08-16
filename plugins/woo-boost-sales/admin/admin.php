<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOOSTSALES_Admin_Admin {
	function __construct() {
		add_filter(
			'plugin_action_links_woo-boost-sales/woo-boost-sales.php', array(
				$this,
				'settings_link'
			)
		);
		add_action( 'init', array( $this, 'init' ) );
//		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Init Script in Admin
	 */
	public function admin_enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field($_REQUEST['page']) : '';
		if ( $page == 'woo-boost-sales' ) {
			wp_enqueue_style( 'woo-boost-sales', VI_WOO_BOOSTSALES_CSS . 'woo-boost-sales-admin.css', array(), VI_WOO_BOOSTSALES_VERSION );
			wp_enqueue_script( 'woo-boost-sales', VI_WOO_BOOSTSALES_JS . 'woo-boost-sales-admin.js', array( 'jquery' ) );
		}
	}

	/**
	 * Link to Settings
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=woo-boost-sales" title="' . __( 'Settings', 'woo-boost-sales' ) . '">' . __( 'Settings', 'woo-boost-sales' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Function init when run plugin+
	 */
	public function init() {
		load_plugin_textdomain( 'woo-boost-sales' );
		$this->load_plugin_textdomain();
		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'   => 'https://wordpress.org/support/plugin/woo-boost-sales',
					'docs'      => 'http://docs.villatheme.com/?item=woocommerce-boost-sales',
					'review'    => 'https://wordpress.org/support/plugin/woo-boost-sales/reviews/?rate=5#rate-response',
					'pro_url'   => 'https://1.envato.market/yQBL3',
					'css'       => VI_WOO_BOOSTSALES_CSS,
					'image'     => VI_WOO_BOOSTSALES_IMAGES,
					'slug'      => 'woo-boost-sales',
					'menu_slug' => 'woo-boost-sales',
					'survey_url' => 'https://script.google.com/macros/s/AKfycbyeO281ICjtyRlYtXXD0mrrth7qo-nT9MqpiMIJ7G-QPWyETIzGl1E7eWOlH3nMsF0gwQ/exec',
					'version'   => VI_WOO_BOOSTSALES_VERSION
				)
			);
		}
	}


	/**
	 * load Language translate
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woo-boost-sales' );
		// Global + Frontend Locale
		load_textdomain( 'woo-boost-sales', VI_WOO_BOOSTSALES_LANGUAGES . "woo-boost-sales-$locale.mo" );
		load_plugin_textdomain( 'woo-boost-sales', false, VI_WOO_BOOSTSALES_LANGUAGES );
	}
}
