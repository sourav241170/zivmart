<?php
/*
 * Plugin Name: Upsells add to Cart Ajax Modal Popup | Boost Woocommerce Cross Sales
 * Plugin URI: https://extend-wp.com/product/boost-woocommerce-cross-sales-with-upsell-crossell-related-products-popup/
 * Description: Boost Cross Sales with Upsell products Popup with Woocommerce Upsell Plugin.
 * Version: 1.5
 * Author: extendWP
 * Author URI: https://extend-wp.com/
 * Text Domain: webd-woocommerce-crosell-popup
 * Domain Path: /lang 
 * WC requires at least: 2.2
 * WC tested up to: 8.4
 *   
 * License: GPL2
 * Created On: 20-03-2018
 * Updated On: 22-12-2023
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once( plugin_dir_path(__FILE__) ."/class-main.php");
  
class WebdWoocommerceCrosellPopup extends WebdWoocommerceCrosellPopupInit{
	
		public $plugin = 'WebdWoocommerceCrosellPopup';		
		public $name = 'WebD Upsell Popup Boost Woocommerce Cross Sales ';
		public $shortName = 'Woocommerce Cross Sales Popup';
		public $slug = 'webd-woocommerce-crosell-popup';
		public $dashicon = 'dashicons-editor-table';
		public $proUrl = 'https://extend-wp.com/product/boost-woocommerce-cross-sales-with-upsell-crossell-related-products-popup/';
		public $menuPosition ='50';
		public $localizeBackend;
		public $localizeFrontend;
		public $description = 'Boost Cross Sales with Upsell products Popup with Woocommerce Upsell Plugin';
 
		public function __construct() {		
			
			add_action('plugins_loaded', array($this, 'translate') );			
			
			add_action('admin_enqueue_scripts', array($this, 'BackEndScripts') );
			add_filter('widget_text', 'do_shortcode');
			add_action('admin_menu', array($this, 'SettingsPage') );
			//add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'Links') );

			register_activation_hook( __FILE__,  array($this, 'onActivation') );
			
			//MAIN FUNCTION FOR THE POPUP
			if(esc_attr(get_option($this->plugin.$this->onMobile)) =='No'){ 
				//if in option hide on mobile sont show the main popup function
				if ( wp_is_mobile() ) {					
				}else add_action('wp_enqueue_scripts', array($this, 'FrontEndScripts') );
			}else{
				add_action('wp_enqueue_scripts', array($this, 'FrontEndScripts') );
			}
			add_action("admin_init", array($this, 'adminPanels') );	


			// HPOS compatibility declaration

			add_action( 'before_woocommerce_init', function() {
				if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
				}
			} );
			
		}
		
		public function onActivation(){ 
			//ACTIVATE AJAX FOR Woocommerce
			update_option( 'woocommerce_enable_ajax_add_to_cart', 'yes', 'yes' );
		}
		
	    public function print_scripts() {
	               //if want to print some inline script
	    }		

		public function translate() {
	         load_plugin_textdomain( $this->plugin, false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
	    }
		
		public function BackEndScripts(){
			wp_enqueue_style( $this->plugin."adminCss", plugins_url( "/css/backend.css", __FILE__ ) );	
			wp_enqueue_style( $this->plugin."adminCss");	
			
			wp_enqueue_script('jquery');
			//wp_enqueue_style( 'wp-color-picker' );
			//wp_enqueue_media();
		
			wp_enqueue_script( $this->plugin."adminJs", plugins_url( "/js/backend.js", __FILE__ ) , array('jquery','wp-color-picker') , null, true);	
						
			$this->localizeBackend = array( 
				'plugin_url' => plugins_url( '', __FILE__ ),
				'ajax_url' => admin_url( 'admin-ajax.php' )."&lang=".get_locale(),
				'siteUrl'	=>	site_url(),
				'plugin_wrapper'=> $this->plugin,
			);		
			wp_localize_script($this->plugin."adminJs", $this->plugin , $this->localizeBackend );
			wp_enqueue_script( $this->plugin."adminJs");

			//add_action( 'admin_footer-widgets.php', array( $this, 'print_scripts' ), 9999 );
		}
		
		public function FrontEndScripts(){
			
			wp_enqueue_style( $this->plugin."css", plugins_url( "/css/frontend.css", __FILE__ ) );	
			wp_enqueue_style( $this->plugin."css");
				
			wp_enqueue_script('jquery');

			if( ! wp_script_is( $this->plugin."_fa", 'enqueued' ) ) {
				wp_enqueue_style( $this->plugin."_fa", plugins_url( '/css/font-awesome.min.css', __FILE__ ));
			}
			
			wp_enqueue_script( $this->plugin."single-product-pagejs", plugins_url( "/js/single-product-page.js", __FILE__ ) , array('jquery') , null, true);
			
			
			$this->localizeFrontend = array( 
				'plugin_url' => plugins_url( '', __FILE__ ),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'siteUrl'	=>	site_url(),
				'plugin_wrapper'=> $this->plugin,
				'view_your_cart' => __( 'View Your Cart','webd-woocommerce-crosell-popup' ),
				'checkout' => __( 'Checkout', 'webd-woocommerce-crosell-popup' ),
			);		
			wp_localize_script($this->plugin."single-product-pagejs", $this->plugin , $this->localizeFrontend );			
			wp_enqueue_script($this->plugin."single-product-pagejs");
						
		}		
		

		public function SettingsPage(){
			//add_menu_page($this->name, $this->name , 'administrator', $this->slug, array($this, 'init') , $this->dashicon, $this->menuPosition );	
			add_submenu_page( 'woocommerce', $this->shortName, $this->shortName, 'manage_options', $this->slug, array($this, 'init') );
			
		}		
		
		public function Links(){
			$links[] =  '<a href="' . admin_url( "admin.php?page=".$this->slug ) . '">'.__( 'Settings', 'webd-woocommerce-crosell-popup' ).'</a>';
			$links[] = "<a href='".$this->proUrl."' target='_blank'>".__( 'PRO Version', 'webd-woocommerce-crosell-popup' )."</a>";
			return $links;			
		}



		public function init(){
			print "<div class='".$this->plugin."'>";
					$this->adminHeader();
					$this->adminSettings();
					$this->adminFooter();
			print "</div>";
			
			
		}		
		
}
$instantiate = new WebdWoocommerceCrosellPopup();