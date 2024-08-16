<?php
 
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
 class WebdWoocommerceCrosellPopupInit{
	
	public $tab;
	public $activeTab;
	public $onMobile = 'onMobile';

	
	public function adminHeader(){
			
		//print "<h1>".$this->name."</h1>";?>
		<h2><img src='<?php echo plugins_url( "images/".$this->plugin.".png", __FILE__ ); ?>' style='width:100%' />
		<?php
	}
	
	public function rating(){
	?>
		<div class="notice notice-success <?php print $this->plugin; ?>Rating is-dismissible">
			<p>
			<?php esc_html_e( "Do you like our effort? ", 'webd-woocommerce-crosell-popup' ); ?></i><i class='fa fa-2x fa-smile-o' ></i> <?php esc_html_e( "Then please give us a  ", 'webd-woocommerce-crosell-popup' ); ?>
				<a target='_blank' href='https://wordpress.org/support/plugin/webd-woocommerce-crosell-popup/reviews/#new-post'>
					<i class='fa fa-2x fa-star' ></i><i class='fa fa-2x fa-star' ></i><i class='fa fa-2x fa-star' ></i><i class='fa fa-2x fa-star' ></i><i class='fa fa-2x fa-star' ></i> <?php esc_html_e( " Rating", 'webd-woocommerce-crosell-popup' ); ?>
				</a>
			</p>
		</div> 	
	<?php	
	}	
	
	public function adminSettings(){
			$this->adminTabs();	
			
			?>
			<form method="post" id='<?php print $this->plugin; ?>Form'  
			action= "<?php echo admin_url( "admin.php?page=".$this->slug ); ?>">
			<?php
			
			settings_fields(  $this->plugin.'general-options' );
			do_settings_sections(  $this->plugin.'general-options' );
			
			$this->proFeatures();
			
			wp_nonce_field($this->plugin);
			submit_button();
			
			?></form>
				
			<?php $this->rating(); ?> 
			<div class='result'><?php $this->adminProcessSettings(); ?> </div>
	<?php
		$this->proProduct();		
	}
	
	public function proFeatures(){
		?>
			<table class="form-table"><tbody>
		
			<tr>
				<th scope='row'>
					<?php esc_html_e( "Single Product Page: Show Related if Upsells not Set	", 'webd-woocommerce-crosell-popup' ); ?>
				</th>
				<td>
					<select disabled class='premium'>
					<option value=''><?php esc_html_e( "PRO VERSION Only", 'webd-woocommerce-crosell-popup' ); ?></option></select>
				</td>				
			</tr>
			<tr>
				<th scope='row'>
					
					<?php esc_html_e( "Archive/Shop Page: Show Popup", 'webd-woocommerce-crosell-popup' ); ?>					
				</th>
				<td>
					<select disabled class='premium'><option value=''><?php esc_html_e( "PRO VERSION Only", 'webd-woocommerce-crosell-popup' ); ?></option></select>
				</td>				
			</tr>
			<tr>
				<th scope='row'>
					<?php esc_html_e( "Archive Page: What to show", 'webd-woocommerce-crosell-popup' ); ?>
					
				</th>
				<td>
					<select disabled class='premium'><option value=''><?php esc_html_e( "PRO VERSION Only", 'webd-woocommerce-crosell-popup' ); ?></option></select>
				</td>				
			</tr>			
			</tbody></table>
			<?php		
	}

	public function proProduct(){ ?>
		<div class='right_wrap rightToLeft'>
			<h2  class='center'><?php esc_html_e( "NEED MORE FEATURES?", 'webd-woocommerce-crosell-popup' ); ?> </h2>

			<ul class='webd_plugins'>
				<li>			
					<p class='img-container'>
						<a target='_blank'  href='<?php print $this->proUrl;?>'>
							<img class='premium_img' src='<?php echo plugins_url( 'images/crosell-popup-pro.jpg', __FILE__ ); ?>' alt='<?php print $this->name;?>' style='width:150px;height:150px;' title='<?php print $this->name;?>' />
						</a>
					</p>

					<div>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Ability to Show Related Propducts on Popup if Upsells have not been set - so plugin will work out of the Box", 'webd-woocommerce-crosell-popup' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Show Popup on Shop, Categories and Archive Pages. Ability to show or hide", 'webd-woocommerce-crosell-popup' ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Choose what to Show in the Archive page. This is very useful in terms of if your eshop has thousands of products, setting Upsells for each product one by one is not an easy job.
						Thus, you can to show either Upsell or Crosell but Also", 'webd-woocommerce-crosell-popup' ); ?> <b><?php esc_html_e( "RELATED that belong to same category and will be shown automatically.", 'webd-woocommerce-crosell-popup' ); ?></b></p>
						<a class='premium_button' target='_blank'  href='<?php print $this->proUrl;?>'>
							<?php esc_html_e("Get it here",'webd-woocommerce-crosell-popup' );?>	
						</a>						
					</div>	

					
				</li>
				
			</ul>

		</div>
	<?php
	}	
	
	public function adminTabs(){
			$this->tab = array( 'settings' => esc_html__('Settings','webd-woocommerce-crosell-popup' ) );
			if( isset( $_GET['tab'] ) ){
				$this->activeTab = $_GET['tab'] ;
			}else $this->activeTab = 'main';
			echo '<h2 class="nav-tab-wrapper" >';
			foreach( $this->tab as $tab => $name ){
				$class = ( $tab == $this->activeTab ) ? ' nav-tab-active' : '';
				echo "<a class='nav-tab".$class." contant' href='?page=".$this->slug."&tab=".$tab."'>".$name."</a>";
			}?>
				<a class='nav-tab gopro <?php print $this->plugin; ?>Toggler' href='#'><?php esc_html_e("PRO VERSION",'webd-woocommerce-crosell-popup' );?></a>
			<?php
			echo '</h2>';		
	}

	
	public function adminFooter(){ ?>	
		<hr>
		<?php 
	}
	
	public function onMobile(){
		
		if( isset($_REQUEST[$this->plugin.'onMobile'] ) ){
			$onMobile =  sanitize_text_field($_REQUEST[$this->plugin.'onMobile']);
		}else $onMobile = get_option($this->plugin.'onMobile'); 
				
		?>
			<select name="<?php print $this->plugin.$this->onMobile;?>" id="<?php print $this->plugin.$this->onMobile;?>" required  placeholder='Show on Mobile' >

				<option  value='Yes' <?php if($onMobile === 'Yes') print "selected='selected'"; ?> >Yes</option>
				<option  value='No' <?php if($onMobile === 'No') print "selected='selected'"; ?> >No</option>
			</select>		
		<?php
	}
	public function adminPanels(){
		add_settings_section($this->plugin."general", "", null,  $this->plugin."general-options");
		
		add_settings_field('onMobile', esc_html__('Show Popup on Mobile','webd-woocommerce-crosell-popup' ), array($this, 'onMobile'),   $this->plugin."general-options",  $this->plugin."general");			
		register_setting( $this->plugin."general", $this->plugin.$this->onMobile);
		
	}
	
	public function adminProcessSettings(){
		
		if($_SERVER['REQUEST_METHOD'] == 'POST' && current_user_can('administrator') ){
		
			check_admin_referer( $this->plugin );
			check_ajax_referer($this->plugin);	
			if($_REQUEST[$this->plugin.$this->onMobile]){
				update_option($this->plugin.$this->onMobile,sanitize_text_field($_REQUEST[$this->plugin.$this->onMobile]));				
			}			
		}
	}

 }