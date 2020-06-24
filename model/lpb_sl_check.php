<?php 
/**
 * LandingPageBooster SL_Manager
 *
 * update API Method
 *
 * @class 		SL_Manager
 * @version		1.0.0
 * @package		LandingPageBooster/Classes
 * @category	Class
 * @author 		Netseek
 */ 
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 
require_once( ABSPATH . '/wp-load.php' );
if ( ! class_exists( 'SL_Manager' ) ) :
	class SL_Manager 
	{
		protected static $_instance = null;
		private $license_data = null;
		 /**
		 * @var KRLicenseManager $dataKey
		 */
		public static $dataKey;
		/**
		 * @var KRLicenseManager $license
		 */
		public static $license; 
		
		/**
		 * @var KRLicenseManager $email
		 */
		public static $email;
				
		/**
		 * @var KRLicenseManager $deactivateCheckboxKey
		 */
		public static $deactivateCheckboxKey;
		
		/**
		 * @var KRLicenseManager $activatedKey
		 */
		public static $activatedKey;
		
		
		/**
		 * @var KRLicenseManager $action_act
		 */
		public static $action_act = "on";
		
		/**
		 * @var KRLicenseManager $action_deact
		 */
		public static $action_deact = "off";
		
		/**
		 * @var KRLicenseManager $key_act
		 */
		public static $key_act = "Activated";
		
		/**
		 * @var KRLicenseManager $key_deact
		 */
		public static $key_deact = "Dectivated";
		
		/**
		 * @var KRLicenseManager $domain_url
		 */
		public static $domain_url = "https://lpb2.6bin.com";
		
		/**
		 * @var KRLicenseManager $secret_key
		 */
		public static $secret_key = "58f862dcac7da6.74677125";
		/**
		 * @var KRLicenseManager $timetocheck_key
		 */
		public static $timetocheck_key = '_timetocheck_key';
		
		/**
		 * @var KRLicenseManager $lpb_user
		 */
		public  $lpb_user = null;
		
		
		/**
		 * @var KRLicenseManager instance
		 */
		public static function instance() 
		{
			if ( is_null( self::$_instance ) )
				self::$_instance = new self();
			return self::$_instance;
		}
		
		/**
		* Constructor for the cart class. Loads options and hooks in the init method.
		*
		* @access public
		* @return void
		*/
		function __construct()
		{
			self::$dataKey 			= '_keywordreplacer';
			self::$email 		= get_option( '_activation_email' );
			self::$license 	= get_option( '_license_key' );
			self::$deactivateCheckboxKey 	= '_deactivate_checkbox_key';
			self::$activatedKey 			= 'kr_license_key_activated';
			$options = get_option( self::$dataKey );
		}
		
		/**
		* Check Key validity
		*
		* @access public
		* @return string
		*/	
		static function checkApiKeyValidity()
		{
		  if((!get_transient(lpb_transient_name()) || !get_transient('lpb_sl_license_info')) )
		  {
			$validity = unserialize(lpb_sl_parse());
			if($validity['status'] != null && array_key_exists('license_key', $validity))
			{
				$validity['status'] = change_value_status($validity['status']);
				if ((int)$validity['status'] === 1 && $validity['license_key'] === lpb_sl_get_key() && $validity['installation_url'] === lpb_get_url()) 
				{
					set_transient(lpb_transient_name(), lpb_sl_parse(),6 * HOUR_IN_SECONDS);
					set_transient( "lpb_sl_license_info",self::check_license_info(get_option( '_license_key' ),"") ,6 * HOUR_IN_SECONDS);  
					return;
				}
			}
			
			SL_Manager::remove_license();  
		 }
		if(!get_transient('lpb_sl_key') &&  get_transient(lpb_transient_name()))
			lpb_sl_save_key(get_option( '_license_key' ));
		} 
		
		/**
		* Admin Notice Block and Expired
		*
		* @access public
		* 
		*/	
		public static function admin_notice_block_expired()
		{
			if(self::get_license_info()['deactivate_checkbox'] === 'off'){
				$lpb_status_check = self::check_license_info(get_option( '_license_key' ), '' );
				if(isset($lpb_status_check->status))
				{
					if($lpb_status_check->status === "expired" || $lpb_status_check->status === "block")
					{
						echo sprintf( '<div class="error"><p>' . __( 'There seems to be a problem with your Landing Page Booster license. Please re-enter. If you experience problems, kindly contact the %sdeveloper%s </div></p>', 'kr' ), '<a href="http://www.landingpagebooster.com/">', '</a>' );
					}
				}
			}
		}
		
		/**
		* Remotely Eliminate License 
		*
		* @access public
		* 
		*/
		public static function remove_license()
		{
			$api_params = array
						(
							'slm_action' => 'slm_deactivate' ,
							'secret_key' => "58f862dcac7da6.74677125",
							'license_key' => get_option( '_license_key' ),
							'registered_domain' => get_site_url() ,
							'item_reference' => urlencode("Landing Page Booster"),
						);
			// Send query to the license manager server
			$query = esc_url_raw(add_query_arg($api_params,  "https://lpb2.6bin.com"));
			$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false)); 
			$license_data = json_decode(wp_remote_retrieve_body($response)); 
			
			if ( is_wp_error( $response ) )
			{
				 return null;
			}
			if($license_data->result == "success" )
				self::setting_remove();
			elseif($license_data->result && $license_data->result == "error ")
				self::setting_remove();
			else
				self::setting_remove();
			
			
			//echo "asdgasdf";
		}
		
		/**
		* License Notice
		*
		* @access public
		* 
		*/
		public static function act_deact_notice()
		{
			if ( is_admin() ) 
			{
				if ( isset( $_GET['page'] ) && 'krSetup' != $_GET['page'] ) return;
				$data 				= SL_M()->get_license_info();
				
				if ( isset( $_GET['result']) && isset( $_GET['action']  ) )
				{
					if($_GET['action'] == "activate" )
					{
						if($_GET['result'] == "success" && $data[ 'deactivate_checkbox' ] == "on")
							echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'updated' , "License Activated"  ); 
						
					}
				}
				else if(isset( $_GET['result']  ) && !get_transient(lpb_transient_name()))
				{
					if($_GET['result'] == "error")
					{
						if($_GET['error_code'] == "1")
							echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'error' , "An unexpected error occurred. Please contact the <a href='http://www.netseek.com.au/'>developer</a>"  );
						if($_GET['error_code'] == "60")
							echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'error' , " Invalid license key"  ); 
						if($_GET['error_code'] == "40")
							echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'error' , " License key already in use on ". get_site_url()  ); 
						if($_GET['error_code'] == "80")
							echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'error' , " The license key on this domain is already inactive "  ); 
						if($_GET['error_code'] == "20")
							echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'error' , " Your License key is blocked "  ); 
						if($_GET['error_code'] == "30")
							echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'error' , " Your License key has expired "  ); 
						if($_GET['error_code'] == "50")
							echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'error' , " Reached maximum allowable domains "  ); 
					
					}
				}
				else{}
			}
		}
		/**
		* Trigger Activation
		*
		* @access public
		* 
		*/
		public static function  trigger_act($licence_key)
		{
			$api_params = array
						  (
							'slm_action' => 'slm_activate' ,
							'secret_key' => self::$secret_key,
							'license_key' => $licence_key,
							'registered_domain' => get_site_url() ,
							'item_reference' => urlencode("Landing Page Booster"),
						  );
			$query = esc_url_raw(add_query_arg($api_params,  self::$domain_url));
			$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));
			return SL_Manager::request("trigger_act",$licence_key,$response);
		}
		/**
		* Trigger Dectivation
		*
		* @access public
		* 
		*/
		public static function trigger_deact($licence_key)
		{
			$api_params = array
						  (
							'slm_action' => 'slm_deactivate' ,
							'secret_key' => self::$secret_key,
							'license_key' => $licence_key,
							'registered_domain' => get_site_url(),
							'item_reference' => urlencode("Landing Page Booster"),
						  );
			$query = esc_url_raw(add_query_arg($api_params,  self::$domain_url));
			$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));
			return SL_Manager::request("trigger_deact",$licence_key,$response);
		}
		
	    /**
	    *  SLM Remote Call Request
	    *
	    * @access public
	    * @return array
	    */	
		private static function request($action , $licence_key,$params = array())
		{
			$license_data = json_decode(wp_remote_retrieve_body($params));
			if($action == "trigger_act")
			{
				$get_current_user_id = get_current_user_id();
				$lpb_user = new WP_User( $get_current_user_id );
				if ( is_wp_error( $params ) )
				{
					header('Location: '.get_site_url().'/wp-admin/admin.php?page=krSetup&result=error_domain');		
					return $license_data;
				}
				if($license_data->result == "error" )
				{ 
					update_option('_license_key',$licence_key);
					header('Location: '.get_site_url().'/wp-admin/admin.php?page=krSetup&result='.$license_data->result.'&error_code='.$license_data->error_code);	
					return $license_data;
				}
				else if(isset($license_data->result))
				{
					if( $license_data->result == 'success' )
					{
						update_option( "_deactivate_checkbox_key", self::$action_act );	
						update_option( "kr_license_key_activated", self::$key_act );
						lpb_sl_save_key($licence_key);
						set_transient( lpb_transient_name(), lpb_sl_parse() ,6 * HOUR_IN_SECONDS);  
						set_transient( "lpb_sl_license_info",self::check_license_info($licence_key,"") ,6 * HOUR_IN_SECONDS);  
											
						$args = array
						(
							'role' => 'administrator',
						);
						 $users = get_users($args);
						 foreach ($users as $user) 
						 {
							 $lpb_user = new WP_User( $user->ID );
							 $lpb_user->add_role( 'kr_LPB_admin' );
							
						 }
						 SL_Manager::set_options( $licence_key, $params ); 	
					}
					else
					{
						header('Location: '.get_site_url().'/wp-admin/admin.php?page=krSetup&result=error&error_code=2');		
					} 
				}
				else
				{
					header('Location: '.get_site_url().'/wp-admin/admin.php?page=krSetup&result=error&action=activate');
				}
				return $license_data;
			}
			if($action == "trigger_deact")
			{
				$license_data = json_decode(wp_remote_retrieve_body($params));
					
				if ( is_wp_error( $params ) )
				{
					
					header('Location: '.get_site_url().'/wp-admin/admin.php?page=krSetup&result=error_domain');		
					
					return $license_data;
				}
				else if(isset($license_data->result))
				{
					 if($license_data->result == 'success' )
					 {
						self::setting_remove();
						 header('Location: '.get_site_url().'/wp-admin/admin.php?page=krSetup&action=deactivate');	
						 return null;
					 }
					 if($license_data->result == 'error' || $license_data->error_code == '80'  )
					 {
						
						  self::setting_remove();
						  header('Location: '.get_site_url().'/wp-admin/admin.php?page=krSetup&result='.$license_data->result.'&error_code='.$license_data->error_code);
						
						return null;
					 }
				}
				else{}
				return $license_data;
			}
		}
		public static function setting_remove()
		{
			 update_option( "_deactivate_checkbox_key", self::$action_deact );	
			 update_option( "kr_license_key_activated", self::$key_deact );	
			 delete_transient( lpb_transient_name() );
			 delete_transient( "lpb_sl_key");	
			 delete_transient( "lpb_sl_license_info");	
			 $args = array
			 (
				'role' => 'kr_LPB_admin',
			 );
			 $users = get_users($args);
			 foreach ($users as $user) 
			 {
				 $lpb_user = new WP_User( $user->ID );
				 $lpb_user->remove_cap( 'kr_LPB_admin' );
				
			 }
			
		}
		
		
		/**
		* License Get Information API Request
		*
		* @access public
		* @return array
		*/	
		public static function check_license_info($licence_key, $email )
		{
			$api_params = array
						(
							'slm_action' => 'slm_check' ,
							'secret_key' => self::$secret_key,
							'license_key' => $licence_key,//"58fcfb4179526"
							'registered_domain' => get_site_url() ,
							'item_reference' => urlencode("Landing Page Booster"),
						);
			// Send query to the license manager server
			$query = esc_url_raw(add_query_arg($api_params,  self::$domain_url));
			$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));
			return json_decode(wp_remote_retrieve_body($response));
		}
		
		/**
		* Save Option Setting
		*
		* @access public
		* 
		*/
		public static function set_options($licence_key, $params = array() )
		{
			$options = get_option( self::$dataKey );
			if( ! empty ( $options ) )
			{
				update_option( '_license_key', $licence_key );
			}
			else
			{
				add_option( '_keywordreplacer', self::$dataKey );		
				add_option( '_license_key', $licence_key);
				
			} 
			header('Location: '.get_site_url().'/wp-admin/admin.php?page=krSetup&result=success&action=activate');	
		}
		
		/**
		* Get License Information
		*
		* @access public
		* 
		*/
		public static function get_license_info()
		{
			$options = get_option( self::$dataKey );
			$params = array();

			if ( ! empty( $options ) && $options !== false ) 
			{
				$params[ 'license' ] 	= get_option( '_license_key' ); 	
				$params[ 'deactivate_checkbox' ] = get_option( self::$deactivateCheckboxKey ); 
			} else 
			{
				$params = array();
			}
			return  $params;
		}
		
		/**
		* Check Update
		*
		* @access public
		* 
		*/
		public static function Check_update()
		{
			 if(get_transient(lpb_transient_name()) && is_admin() )
			 {
				$lpb_UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
					'https://github.com/hananetseek/2016-LandingPageBooster/',
					KR_PlUGIN_PATH().'\app.php',
					'landing-page-booster'
				); 
				// If using a private repository, specify the access token.
				$lpb_UpdateChecker->setAuthentication('818cf0e2ba5d816407d62e0dd48753309584dfc8');
				return;
			}
			add_filter( 'plugin_row_meta', array(__CLASS__,'my_plugin_row_meta'), 10, 4 ); 
			
		}
		public static function my_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) 
		{
			if ( PLUGIN_BASENAME == $plugin_file )
				$plugin_meta[] = '<p style="display: inline;">You are not eligible for updates. Please obtain a valid license in <a href="www.landingpagebooster.com"> landingpagebooster.com</a></p>';
			return $plugin_meta;
		}
	}
endif;

function SL_M()
{
	return SL_Manager::instance();
}
if (is_admin())
{
	SL_M();
}


?>