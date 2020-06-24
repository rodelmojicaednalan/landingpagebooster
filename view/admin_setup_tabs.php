<?php
/**
 * License Setup and Activation 
 *
 * @author 		Netseek
 * @category 	Admin
 * @package 	LandingPageBooster/Admin/View
 * @version     2.4.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


kr_settings_page();

function kr_admin_tabs( $current = 'license' ) { 
    $tabs = array( 'license' => 'License'); 
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=krSetup&tab=$tab'>$name</a>";
        
    } 
    echo '</h2>';
}
function set_selected($desired_value, $new_value)
{
    if($desired_value==$new_value)
    {
        echo ' selected="selected"';
    }
}

/**
 * Returns current plugin version.
 * 
 * @return string Plugin version
 */
function plugin_get_version() {
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_data = get_plugin_data(ABSPATH . 'wp-content/plugins/landing-page-booster/app.php' );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;   
}

function kr_settings_page() {
	global $pagenow;
	global $sql;
	global $core;
	global $utilities;
	global $wpdb;
	?>
	
	<div class="wrap" >
		<span style="float: right;"><?php _e( "Landing Page Booster ".plugin_get_version(), 'netseek' );//show current version ?></span>
		<h2 style="font-size: 23px;font-weight: 400;margin-bottom: 15px;">Landing Page Booster</h2>
		<?php
			$count_Page 	= $sql->count_Pagedefault_active();
			$status 		= md5("inactive");
		 	$activate 		= ( !empty($_POST['Activate']) ? $_POST['Activate'] : FALSE  );
			$deactivate 	= ( !empty($_POST['Deactivate']) ? $_POST['Deactivate'] : FALSE  );
			$update			= ( !empty($_POST['Update']) ? $_POST['Update'] : FALSE  );
			$email 			= ( !empty( $_POST[ 'email' ] ) ? $_POST[ 'email' ] : '' );
			$licence_key 	= ( !empty( $_POST[ 'licence_key' ] ) ? $_POST[ 'licence_key' ] : ''  );
			if( $deactivate ){
			 	if ( ! isset( $_POST['_landingpagebooster_license_nonce'] ) || ! wp_verify_nonce( $_POST['_landingpagebooster_license_nonce'], '_landingpagebooster_license_nonce' ) ) die("Security Check");
		
			//echo $deactivate ;
				$response = SL_M()->trigger_deact($licence_key );
				/* if( ! empty( $response->message) ){
					echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'error' , $response->message  ); 
				}    */
				
				
			} elseif( $activate ) {
				///
				if ( ! isset( $_POST['_landingpagebooster_license_nonce'] ) || ! wp_verify_nonce( $_POST['_landingpagebooster_license_nonce'], '_landingpagebooster_license_nonce' ) ) die("Security Check");
		
				$response = SL_M()->trigger_act($licence_key );
				
			 /* 	if( ! empty( $response->message && $response->result == "error" )){
					echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'error' , $response->message  ); 
				}  
				else if ( ! empty ( $response->message ) && $response->result != "success " ){
					
					echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'updated' ,$response->message  ) ;
					
					
					}
				else
				{
					echo  sprintf( '<div class="%s"><p>' . __( '%s' ) . '</p></div>', 'error' ,"Entered API key and email") ;
				} */
			}

			$data 				= SL_M()->get_license_info();
			//echo get_option( '_license_key' );
			$license 			= ( ! empty( $data[ 'license' ] ) ? $data[ 'license' ] : '' );
			$email_2				= ( ! empty( $data[ 'email' ] ) ? $data[ 'email' ] : '' ); 
			$deactivate_checkbox_2  = ( ! empty( $data[ 'deactivate_checkbox' ]) ? $data[ 'deactivate_checkbox' ] : ''  );
			$deactivate_checkbox = ( $deactivate_checkbox_2 == 'on' ?  'on' :  'off' );
			$licence_key_txt 	= (  $deactivate_checkbox != "on" ? $licence_key : $license );
			$email_txt 			= ( $deactivate_checkbox != "on" ? $email : $email_2 );
			
		 //echo $deactivate_checkbox;
		 	//$disabled = ( $deactivate_checkbox == 'on' ? 'disabled' : '' );
                   
			//if ( isset ( $_GET['tab'] ) ) kr_admin_tabs($_GET['tab']); else kr_admin_tabs('license');
		?>

		<div id="poststuff">
		 <?php
		if ( $deactivate_checkbox != 'on' ){
		?>
		
		
		
		
		<div class="card" style="margin-top: 0px;">
            <p>Welcome to Landing Page Booster. To get started, please provide the license key below so we can verify the legitimacy of this copy. You will only need to do this once.</p>
        </div>
						<?php }
		if ( $deactivate_checkbox == 'on' ){?>
		<?php 
		
		$license_data = get_transient('lpb_sl_license_info') ;
		if($license_data->result == 'success'){
                    ?>
                    <table class="wp-list-table widefat striped importers ndf_license_info">
                        <tr>
                            <td class="import-system"><strong>License Status</strong></td>
                            <td><strong><?php echo ucfirst($license_data->status); ?><strong></td>
                        </tr>
                        <tr>
                            <td class="import-system">Maximum Allowed Domains</td>
                            <td><?php echo ucfirst($license_data->max_allowed_domains); ?></td>
                        </tr>
                        <?php
                        if( is_array( $license_data->registered_domains ) ){
                            foreach( $license_data->registered_domains as $registered_domains ){
                                ?>
                                <tr>
                                    <td><strong>License Key</strong></td>
                                    <td><strong><?php echo $registered_domains->lic_key; ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Item Reference</td>
                                    <td><?php echo $registered_domains->item_reference; ?></td>
                                </tr>
                                <tr>
                                    <td>Registered Domain</td>
                                    <td><?php echo $registered_domains->registered_domain; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <?php if( $license_data->date_created != '0000-00-00' ){ ?>
                        <tr>
                            <td class="import-system">Date Created</td>
                            <td><?php echo date( 'F j, Y', strtotime( $license_data->date_created ) ); ?></td>
                        </tr>
                        <?php } if( $license_data->date_renewed != '0000-00-00' ){ ?>
                        <tr>
                            <td class="import-system">Date Renewed</td>
                            <td><?php echo date( 'F j, Y', strtotime( $license_data->date_renewed ) ); ?></td>
                        </tr>
                        <?php } if( $license_data->date_expiry != '0000-00-00' ){ ?>
                        <tr>
                            <td class="import-system">Date Expiry</td>
                            <td><?php echo date( 'F j, Y', strtotime( $license_data->date_expiry ) ); ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <?php
                }
				} 
				?>
		<div class="card">
			<form method="post" action="<?php admin_url( 'admin.php?page=krSetup' ); ?>">
				<?php
				wp_nonce_field( '_landingpagebooster_license_nonce', '_landingpagebooster_license_nonce' );
				
				if ( $pagenow == 'admin.php' && $_GET['page'] == 'krSetup' ){ 
				
					if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; 
					else $tab = 'license'; 
					
					echo '<table class="form-table">';
					switch ( $tab ){
						case 'support' :
							?>
                                                        <tr>
								<th><label for="kr_tag_class">Phone (Australia)</label></th>
								<td><span class="description">1800 180 183</span></td>
							</tr>
							<tr>
								<th><label for="kr_tag_class">Phone (International)</label></th>
								<td><span class="description">+61 2 9209 4055</span></td>
							</tr>
                                                        <tr>
								<th><label for="kr_tag_class">Fax</label></th>
								<td><span class="description">1800 180 184</span></td>
							</tr>
							<tr>
								<th><label for="kr_tag_class">Email:</label></th>
								<td><span class="description"><a href="http://www.landingpagebooster.com/support" target="_blank">Contact Us</a></span></td>
							</tr>
							<?php
						break; 
						case 'license' : 
							?>
							<tr>
                   
                  </tr>
                   <tr>
                    <td width="150" align="left" class="key">License Key:</td>
                    <td>
                        <input type="text" name="licence_key"   <?php echo $deactivate_checkbox == 'on' ?'readonly="readonly"':'';?> class="text_area"  style="width:404px" value="<?=$licence_key_txt;?>" /> 
                    </td>
                  </tr>
                
                   <tr>
                    <td width="150" align="right" class="key"></td>
                    <td>
                        
                        <?php
						
                        if ( get_transient(lpb_transient_name()) ){
                        ?>
                        	<input type="submit" value="Deactivate" class="button-primary" name="Deactivate">
                        <?php
                        }else{
                        ?>
							<input type="submit" value="Activate" class="button-primary" name="Activate">
                        <?php
                        }
                        ?>

                    </td>
                  </tr>
							<?php
						break;
					}
					echo '</table>';
				}
				
				?>
				
			</form>
		</div>
		</div>

	</div>
<?php
}


?>
