<?php
/**
 * LandingPageBooster Bootstrap
 *
 * @package         LandingPageBooster
 * @subpackage      Bootstrap
 * @author          Netseek	 
 */ 

 /*
 * Flush ob start.
 */

 
add_action('init', 'add_ob_start');
function add_ob_start(){
    ob_start(); 
    if ( !session_id() && session_status()!=PHP_SESSION_ACTIVE) 
        session_start();
} 


/*
 * KWR URL Management
 */

add_action('admin_menu', 'MenuManagement',10); 

require_once KR_PlUGIN_PATH() . '/view/admin.php';
require_once KR_PlUGIN_PATH() . '/config.php';
require_once KR_PlUGIN_PATH() . '/model/lpb_sl_check.php';
require_once KR_PlUGIN_PATH() . '/model/password_man.php';
require_once KR_PlUGIN_PATH() . '/model/utilities.php';
require_once KR_PlUGIN_PATH() . '/model/sql.php';
require_once KR_PlUGIN_PATH() . '/model/core.php';
require_once KR_PlUGIN_PATH() . '/model/lpb_api.php';


/*
 * Key Validity.
 */
add_action('admin_notices', 'SL_Manager::checkApiKeyValidity' );

/*
 * Admin Notice Block and Expired.
 */
add_action('admin_notices', 'SL_Manager::admin_notice_block_expired' );

/*
 * Admin Notice Deactivation and Activation.
 */
add_action('admin_notices', 'SL_Manager::act_deact_notice' );

/*
 * Remotly Eliminate License.
 */
add_action( 'remove_license', 'SL_Manager::remove_license' );

/*
 * Remotly Eliminate License on Unintallation.
 */
add_action( 'deactivated_plugin', 'detect_plugin_deactivation', 10, 2 );

/*
 * Detect plugin Activation.
 */
 add_action( 'activated_plugin', 'activation_redirect', 10, 2  );
 
/*
 * Check Update Plugin.
 */
 add_action( 'init', 'SL_Manager::Check_update' );
 

 
 
 
/*
 * Flush ob end.
 */
add_action('wp_footer', 'flush_ob_end');
function flush_ob_end() {
    ob_end_flush();
}

$utilities  = new KwReplacerUtilities();
$sql        = new KwReplacerQuery();
$core       = new KwReplacerCore(); 

/*
 * Remove Extra Meta Feed Links Headers.
 */
add_action('init', 'remove_header_info');
function remove_header_info() {
    remove_action( 'wp_head', 'feed_links_extra', 3 );
}

/*
 * Load Rewrite Rules.
 */
add_action('init', 'getRewriteRules');
function getRewriteRules() {
    global $core;
    return $core->GetKrReWriteRules();
}

/*
 * Flush Rewrite Rules.
 */
add_action( 'wp_loaded','kr_flush_rules' );
function kr_flush_rules() {
    global $core;
    return $core->SetKrFlushRules();
}

/*
 * Get All HTTP Request parameters and variables.
 */
add_filter( 'query_vars','kr_query_vars' );
function kr_query_vars( $vars ) {
    global $core;
    return $core->GetKrQueryVar( $vars );
}


/*
 * Remotly Eliminate License on Unintallation.
 */
function detect_plugin_deactivation(  $plugin, $network_activation )
{
    // do stuff
	 $plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
	 if($plugin_data['Name'] == "Landing Page Booster")
	 {
		do_action( 'remove_license','' );
	 }
}


/*
 * Redirect after activation.
 */
function activation_redirect( $plugin ) {
		
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
		 if($plugin_data['Name'] == "Landing Page Booster")
		 {
			do_action( 'remove_license','' );
			exit( wp_redirect( admin_url( 'admin.php?page=krSetup' ) ) );
		 }
    
}
/*
 * Replace WP Meta Title.
 */
add_filter('pre_get_document_title', 'pre_get_SetKwrTitle'); 
function pre_get_SetKwrTitle( $title ) {
    global $core;
    return $core->SetKrPageTitle( $title );
}
add_filter('wp_title', 'SetKwrTitle'); 
function SetKwrTitle( $title ) {
    global $core;
    return $core->SetKrPageTitle( $title );
}
/*
 * Replace WP Meta Keyword.
 */
add_action('wp_head','SetKwrKeyword',2 );
function SetKwrKeyword(){
    global $core;
    echo $core->SetKrKeyword();
}

/*
 * Replace WP Meta Description.
 */
add_action('wp_head','SetKwrDescription',2);
function SetKwrDescription(){
    global $core;
    echo $core->SetKrDescription();
}
/*
 * Replace WP Page Content.
 */
add_filter('the_content','SetKwrContent');
function SetKwrContent($text) {
    global $core;
    return $core->SetKrContent($text);
}

/*
 * User Interface Content Default For Tags.
 */
add_action( 'admin_init', 'myplugin_add_custom_box', 1 );
add_action( 'save_post', 'SetNsTags' );
function SetNsTags(){
    global $sql;
    global $post;
    global $wpdb;
    $linkid  = esc_sql( $post->ID );
    $result  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_tags WHERE linkid='{$linkid}'");
    $status = md5("active");
    $tagsCounter = $sql->CounterTagsDefaultPage();
	// Check  Landing Page Remaining 
    if( $tagsCounter['status'] == 1 ){ 

    	$sql        = new KwReplacerQuery();
        $result = $sql->SetNsTags();
		//display message reached limit
        if( $result[ 'respCode' ] == FALSE ){
        	 add_admin_message($result['respMsg']);
        }
        else
		{
			
			 add_admin_message( $result['respMsg'],$result[ 'respCode' ] );
		}
    }
    else {
		// If tags exist record
        if(!empty($result)) {
			
			 if( $tagsCounter['status'] == true)
			 {
				   $result =  $sql->SetNsTags();
					
                    if( $result[ 'respCode' ] == FALSE )
					{
						//Message Requiredments of unable to save
						add_admin_message( $result['respMsg']);
					}
					else
					{
						//Message Update
						add_admin_message( $result['respMsg'],$result[ 'respCode' ] );
					}
			 }
			 else{
				 if(!empty($result)){
					 add_admin_message($tagsCounter[ 'message' ]);
				 }
				 else{
					  add_admin_message( $tagsCounter[ 'message' ]);
				 }
				
			 }
		}else{
			//if NsSubmit action show message 
			if($_POST['NsSubmit'] || $_POST['save'] == "Update")
			{
				$title                  = esc_sql( trim($_POST['ns-title']) );
				$keyword                = esc_sql( trim($_POST['ns-keywords']) );
				$description            = esc_sql( trim($_POST['ns-description']) );
				$tags                   = esc_sql( trim($_POST['ns-tags']) );
				$is_empty = empty( $title  ) || empty( $keyword  ) || empty( $description) || empty( $tags) ;
				if(!$is_empty) {add_admin_message($is_empty. $tagsCounter['message']);}
			}
			else
			{
				//if trash action remove the selected page values lpb
				if(isset($_GET['action'])  == 'trash')
				{
					$get_bulk = $_GET['post'];
					
					foreach($get_bulk as $getkeys => $getvalue)
					{
						$wpdb->query(
						 "UPDATE {$wpdb->prefix}ns_tags
								SET title       = '{$title}' ,
								 keyword        = '{$keyword}' ,
								 description    = '{$description}',
								 tags           = '{$tags}',
								 status			=  '{$status}'
						   WHERE linkid         = '{$getvalue}'"
						);
						
					}
							
				}
			}
		}
    }
    
    $sql->auto_removal_counter_tags();
    
}

/**
 * Messages with the default wordpress classes
 */
function showMessage($message, $errormsg = false)
{
	
    if ($errormsg) {
        echo '<div id="message" class="error">';
    }
    else {
        echo '<div id="message" class="updated notice notice-success is-dismissible">';
    }

    echo "<p>$message</p></div>";
}

/**
 * Messages with the default wordpress classes
 */
 
function showMessage2($message, $errormsg = false)
{
    if ($errormsg) {
        echo '<div id="message" class="error">';
    }
    else {
        echo '<div id="message" class="updated fade" style="margin-left: 0px; margin-right: 0px;">';
    }

    echo "<p>$message</p></div>";
}
/**
 * Display custom messages
 */
function show_admin_messages()
{
    if(isset($_COOKIE['wp-admin-messages-normal'])) {
        $messages = strtok($_COOKIE['wp-admin-messages-normal'], "@@");

        while ($messages !== false) {
            showMessage($messages, true);
            $messages = strtok("@@");
        }

        setcookie('wp-admin-messages-normal', null);
    }
    if(isset($_COOKIE['wp-admin-messages-error'])) {
        $messages = strtok($_COOKIE['wp-admin-messages-error'], "@@");

        while ($messages !== false) {
            showMessage($messages, false);
            $messages = strtok("@@");
        }

        setcookie('wp-admin-messages-error', null);
    }
}

/**
 *  Display Admin Notices
 */

add_action('admin_notices', 'show_admin_messages');

function add_admin_message($message, $error = false)
{
	//if( strstr($_SERVER['REQUEST_URI'], 'wp-admin/post-new.php') || strstr($_SERVER['REQUEST_URI'], 'wp-admin/post.php') ) {
  /*  if(!strstr($_SERVER['REQUEST_URI'], 'wp-admin/post.php') || $_GET['action'] == 'edit' || empty($message)) return false;
   if(isset($_GET['action'])  == 'trash'  ) return false;  */
    if($error) {
        setcookie('wp-admin-messages-error', $_COOKIE['wp-admin-messages-error'] . '@@' . $message, time()+60);
    } else {
        setcookie('wp-admin-messages-normal', $_COOKIE['wp-admin-messages-normal'] . '@@' . $message, time()+60);
    }
}     



/**
 * Paragraph HTML Text Replacer using ##PARA1## , ##PARA2## , ##PARA3##
 * @global type $wp_query
 * @global KwReplacerQuery $sql
 * @global type $wpdb
 * @global type $post
 * @global type $wp
 * @param type $article
 * @param type $case_sensitive
 * @return type 
 */

function ParaReplace( $article, $case_sensitive = false )
{
    
    global $wp_query;
    global $sql;
    global $wpdb;
    global $post;
    global $wp;
    
    $krURLtags  = ( ! empty( $wp_query->query_vars["krtags"] ) ? $wp_query->query_vars["krtags"] : '' );
    $parent     = esc_sql( $krURLtags );
    
    if(!empty ($krURLtags)){
            $data     = $sql->GetParaByTags($krURLtags);
            if(!empty($data)){
                $paragraph = $data[0];
                $article = str_replace(array_keys($paragraph), array_values($paragraph), $article, $count);
            }else{
                $paragraph = array(
                    '##PARA1##'      => '',
                    '##PARA2##'      => '',
                    '##PARA3##'      => '' 
                );
            $article = str_replace(array_keys($paragraph), array_values($paragraph), $article, $count);
            }
            
      
      }elseif(!empty ($parent)){
          $pages    = explode("/", $parent);
          $data     = $sql->GetParaByTags(end($pages));
            if(!empty($data)){
                $paragraph = $data[0];
                $article = str_replace(array_keys($paragraph), array_values($paragraph), $article, $count);
            }else{
             $paragraph = array(
                '##PARA1##'      => '',
                '##PARA2##'      => '',
                '##PARA3##'      => '' 
            );
            $article = str_replace(array_keys($paragraph), array_values($paragraph), $article, $count);
            }     
      }else{
          $paragraph = array(
                '##PARA1##'      => '',
                '##PARA2##'      => '',
                '##PARA3##'      => '' 
            );
          $article = str_replace(array_keys($paragraph), array_values($paragraph), $article, $count);
      }
    
    return $article;
}

add_filter('the_content', 'ParaReplace', 2); /* content */


//


/* Custom Re-write */

add_filter( 'rewrite_rules_array','krplugin_add_rewrite_rules' );

function krplugin_add_rewrite_rules($rules) {  
    
    global $wpdb;
    $newrules = array();
    
    $kr_tags = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_links GROUP BY PageName");
    
    if(!empty($kr_tags)){
        
        foreach ($kr_tags as $value){
            $newrules['^'.  str_replace(site_url('/'),'', get_permalink( $value->PostId  )).'([^/]*)/?']  = 'index.php?page_id='.$value->PostId.'&krtags=$matches[1]';
        }
    }
    return $newrules + $rules;
    
    
}  
/* Delete Pages Counter */

add_action( 'admin_init', 'kr_codex_init'  ) ;

function kr_codex_init() 
{
	if ( current_user_can( 'delete_posts' ) )
		add_action( 'delete_post', 'kr_codex_sync' , 10 ) ; 
}
	
function kr_codex_sync( $pid ) 
{
	global $wpdb;
	
	 $pid = esc_sql( $pid );
	 $wpdb->query( "DELETE FROM {$wpdb->prefix}ns_tags WHERE linkid='{$pid}'" );
	
	return true;
}
function recalculate_pages() {
	$url = admin_url( 'admin.php?page=krurls&section=recalculate_pages');
	$clear_url = admin_url( 'admin.php?page=krurls&section=clear_pages');
    ?>
     
   <script type='text/javascript'>
    /* <![CDATA[ */
    	function _recalculate_pages()
    	{
    		var r = confirm("Would you like to recaculate your landing page license counts now?");
    		
    		if( r == true ) {
    			 window.top.location.href = '<?=$url;?>';
    		
    		} else {
    			return false;
    		}
    		
    	}
    	/* ]]> */
		
		 /* <![CDATA[ */
    	function _clear_page(keys)
    	{
			
    		var r = confirm("Are you sure you want to clear page default?");
    		if( r == true ) {
    			 window.top.location.href = '<?=$clear_url;?>&post='+keys;
    		
    		} else {
    			return false;
    		}
    		
    	}
    	/* ]]> */
    </script>
    
    <?php
}
add_action('in_admin_footer', 'recalculate_pages');

// Database table instaler

register_activation_hook( ABSPATH .'wp-content/plugins/landing-page-booster/app.php' , 'krplugin_activation' );

function krplugin_activation() {
	KwReplacerQuery::SetSQLInstaller();
}


// Deactivation 
register_deactivation_hook( ABSPATH .'wp-content/plugins/landing-page-booster/app.php' , 'krplugin_deactivate' );




register_activation_hook( __FILE__, 'kr_install' );

function kr_install(){
	
	
$kr_LPB_custom_capabilities = array(
		'read'							=> true,
		'kr_LPB_admin'					=> true,
	);
	
	/* if( get_role('kr_LPB_admin_role') ){
		remove_role( 'kr_LPB_admin_role' );
	} */
	// Create our NDF Admin role and assign the custom capabilities to it
	add_role( 'kr_LPB_admin_role', 'LPB Admin', $kr_LPB_custom_capabilities );	
}

function krplugin_deactivate(){
	global $wpdb;
	 $email 			= get_option( '_activation_email' );  
	 $licence_key 		= get_option( '_license_key' );
	 $software_title  	= get_option( '_product_id_key');
	

} 

?>