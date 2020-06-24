<?php
/**
 * User Interface Administration 
 *
 * @author 		Netseek
 * @category 	Admin
 * @package 	LandingPageBooster/Admin/View
 * @version     2.4.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_version;

$page_array = array('krurls','AddKrwUrl','krSetup');

if( ! empty ( $_REQUEST['page'] ) ) {
	if(in_array( $_REQUEST['page'] , $page_array)){
		add_action( 'admin_footer', 'admin_kr_css' );
	}
}

 /**
 	 * CSS Method Hook
 	 *
 	 * @access public
 	 * @Register Function
 	 */
function admin_kr_css(){ ?>
    <style>
        #loader {
            border:1px solid #ccc;
            padding:10px;
            margin:10px 0 0 0;
            text-align:center;
            cursor: pointer;
        }
        .contentpane{}
        .rowHolder{
            border:1px solid #ccc;
            padding:10px;
            margin:10px 0 0 0;
        }
        
        input.krInput, textarea.krTextarea{
    
            padding: 5px;   
            border: 1px solid #DDDDDD;

            /*Applying CSS3 gradient*/
            background: -moz-linear-gradient(center top , #FFFFFF,  #EEEEEE 1px, #FFFFFF 20px);    
            background: -webkit-gradient(linear, left top, left 20, from(#FFFFFF), color-stop(5%, #EEEEEE) to(#FFFFFF));
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FBFBFB', endColorstr='#FFFFFF');

            /*Applying CSS 3radius*/   
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;

            /*Applying CSS3 box shadow*/
            -moz-box-shadow: 0 0 2px #DDDDDD;
            -webkit-box-shadow: 0 0 2px #DDDDDD;
            box-shadow: 0 0 2px #DDDDDD;

        }
        input.krInput:hover , textarea.krTextarea:hover
        {
            border:1px solid #cccccc;
        }
        input.krInput:focus , textarea.krInput:focus
        {
            box-shadow:0 0 2px #FFFE00;
        }
        
        fieldset{
            border-top:1px solid #ccc;
            border-left:0;
            border-bottom:0;
            border-right:0;
            padding:6px;
            margin:0px 30px 0px 0px;
        }
        
        legend{
            text-align:left;
            color:#ccc;
            font-size:18px;
            padding:0px 4px 0px 4px;
            margin-left:20px;
        }
        
        label{
            font-size: 15px;
            width:auto;
            float: left;
            text-align: left;
            color:#999;
            clear:left;
            margin:4px 4px 0px 0px;
            padding:0px;
        } 
		.notice-warning, div.warning { 
			border-left-color:#ffba00!important;
		}
	    div.warning {
			background: #fff;
			border-left: 4px solid #fff;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
			box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
			margin: 5px 15px 2px;
			padding: 1px 12px;
		}	
		#confirm-revert{
			display: none; padding-right: 17px;
		}
		.pagination {
			clear:both;
			padding:20px 0;
			position:relative;
			font-size:11px;
			line-height:13px;
		}
		.pagination span, .pagination a {
			display:block;
			float:left;
			margin: 2px 2px 2px 0;
			padding:6px 9px 5px 9px;
			text-decoration:none;
			width:auto;
			color:#fff;
			background: #555;
		}
		 
		.pagination a:hover{
			color:#fff;
			background: #3279BB;
		}
		 
		.pagination .current{
			padding:6px 9px 5px 9px;
			background: #3279BB;
			color:#fff;
		}
		#the-list tr td
		{
			line-height: 41px;
		}
    </style>
		
<?php
}
if($wp_version <= '3.0.4' ){
	if( $_GET[ 'page' ]  == 'AddKrwUrl' && $_GET[ 'section' ] == 'editpagestags' ){
		 add_filter('admin_head','myplugin_tinymce');
		 add_action('admin_init', 'editor_admin_init');
	}

}
 /**
 	 * Call Enqueque Function 
 	 *
 	 * @access public
 	 * 
 	 */
function myplugin_tinymce()
{
  wp_enqueue_script('common');
  wp_enqueue_script('jquery-color');
  wp_admin_css('thickbox');
  wp_print_scripts('post');
  wp_print_scripts('media-upload');
  wp_print_scripts('jquery');
  wp_print_scripts('jquery-ui-core');
  wp_print_scripts('jquery-ui-tabs');
  wp_print_scripts('tiny_mce');
  wp_print_scripts('editor');
  wp_print_scripts('editor-functions');
  add_thickbox();
  wp_tiny_mce();
  wp_admin_css();
  wp_enqueue_script('utils');
  do_action("admin_print_styles-post-php");
  do_action('admin_print_styles');
  remove_all_filters('mce_external_plugins');
}

function editor_admin_init() {
  wp_enqueue_script('word-count');
  wp_enqueue_script('post');
  wp_enqueue_script('editor');
  wp_enqueue_script('media-upload');
}

function myplugin_add_custom_box() {

    global $sql;
    
    add_meta_box(
        'myplugin_sectionid',
        __( 'Landing Page Booster', 'myplugin_textdomain' ),
        'ns_meta_box',
        'post'
    );
    add_meta_box(
        'myplugin_sectionid',
        __( 'Landing Page Booster', 'myplugin_textdomain' ),
        'ns_meta_box',
        'page'
    );

    $sql->SetSQLInstaller();
}

function ns_meta_box() {
    
    global $sql;
    global $post;


    $res = $sql->GetNstags();
    if($res['respCode']){
        $title          = trim($res['respMsg']['title']);
        $keyword        = trim($res['respMsg']['keyword']);
        $description    = trim($res['respMsg']['description']);
        $tags           = trim($res['respMsg']['tags']);
    }
     $plan 			= $sql->CounterTagsDefaultPage();
     $dynamicpage 	= $sql->CounterTagsPage();
     $plan_name = __( 'LPB Platinum License','netseek' );
     $plan_message = "<strong>Unlimited</strong> LPB Landing Pages Remaining";
     

?>
<!--
    <form name="NsForm1" method="post" action="">
    -->
<p style="border:1px;padding:10px;background-color: rgba(252, 255, 204, 0.83);border-color:#e6db55;color: rgb(85, 85, 85);"><?php _e( $plan_message ,'netseek'); ?></p>
<table id="dt-page-definition" width="100%" cellspacing="5px">
<tr valign="top">
    <td style="width:20%;"><label style="font-size: 14px;" for="ns-title" title="Enter the page title string with your ##Tags## inserted where you wish for them to be displayed."><b>LPB Page Title</b> <span style="color:red">*</span></label></td>
        <td><input type     ="text"
                   id       ="ns-title"
                   name     ="ns-title"
                   class    ="heading form-input-tip"
                   size     ="16"
                   autocomplete ="off"
                   value        ="<?php $title = ( !empty ( $title) ? $title : '' );  _e( $title );?>"
                   tabindex     ="6"
                   style        ="width:50.5%"
                   title="Enter the page title string, with your ##TAgs## inserted where you wish for them to be displayed."
                   placeholder = "Example: Your Title Test Including ##Tag1##, ##Tag2## and ##Tag3##"
              />
        </td>
</tr>
<tr valign="top">
    <td><label style="font-size: 14px;"  for="ns-description" title="Enter the page META Description string with your ##TAGS## insterted where you wish for them to be displayed."><b>LPB META Description</b> <span style="color:red">*</span></label></td>
    <td><input type     ="text"
                   id       ="ns-description"
                   name     ="ns-description"
                   class    ="additional-info form-input-tip code"
                   size  ="20"
                   autocomplete ="off"
                   value        ="<?php $description = ( ! empty( $description )  ? $description : '' ); _e( $description ); ?>"
                   tabindex     ="6"
                   style        ="width:99.5%"
                   title="Enter the META Description string including your ##Tags## inserted where you wish for them to be displayed."
                   placeholder = "Example: This is an example of a description with ##Tag1##, ##Tag2## and ##Tag3## included"
              />
    </td>
</tr>
    <tr valign="top">
        <td><label style="font-size: 14px;"  for="ns-keywords" title="Enter the META Keyword string including your ##Tags## inserted where you wish for them to be displayed."><b>LPB META Keywords</b><span style="color:red">*</span></label></td>
        <td>
            <input type     ="text"
                   id       ="ns-keywords"
                   name     ="ns-keywords"
                   class    ="listdata form-input-tip"
                   size  ="20"
                   autocomplete ="off"
                   value        ="<?php $keyword = ( ! empty( $keyword ) ? $keyword : '' ); _e( $keyword );  ?>"
                   tabindex     ="6"
                   style        ="width:99.5%"
                   title="Enter the META Keyword string including your ##Tags## inserted where you wish for them to be displayed."
                   placeholder = "Example: Keyword1, Keyword 2, ##Tag1##, ##Tag2##, ##Tag3##"
              />
            <br />
        </td>
    </tr>

    <tr valign="top">
        <td><label style="font-size: 14px;"  for="ns-tags" title="For each of your ##Tags## used, enter your default values that will be shown if no landing page parameters are passed."><b>LPB Default Tag Values </b><span style="color:red">*</span></label></td>
        <td>
            <input type     ="text"
                   id       ="ns-tags"
                   name     ="ns-tags"
                   class    ="listdata form-input-tip"
                   size  ="20"
                   autocomplete ="off"
                   value        ="<?php  $tags = ( ! empty( $tags ) ? $tags : '' ); _e( $tags );  ?>"
                   tabindex     ="6"
                   style        ="width:99.5%"
                   title        ="For each of your ##Tags## used, enter your default values that will be shown if no landing page parameters are passed."
                   placeholder = "Example: Keyword-1::Keyword-Phrase-2::Keyword-Phrase-3"
              />
            <br />
        </td>
    </tr>
        <tr>
            <td><input type="submit" value="Save Changes" class="button-primary" name="NsSubmit"> </td>
         <td> </td>
    </tr>
</table>
	<!--
        </form>
        -->
<?php
}




function AddKrwUrl()
{

    global $sql;
    global $core;
    global $utilities;
    global $wpdb;
    
	if( ! empty( $_REQUEST['section'] ) ) {
		$section = $_REQUEST['section'];
	} else{
		$section = '';
	}
    
    if(empty ($section)){
        AddNewTagsList();
    }else{
		
        switch ($section)
        {
            case 'pagestags':
                PagesTags();
                break;
            
            case 'addtags';
                WpHtmlFormKwrUI();
                break;
            
            case 'savepagestags':
                $core->WpSaveKrTags();
				
                break;
            
            case 'editpagestags':
                  echo WpEditKrTags();
                break;
            
            case 'deleteTags':
                $core->WpDeleteKrTags();
                break;
            
            case 'override':
                echo WpEditOverridePage();
                break;
            case 'clear_pages':
				 $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}ns_tags WHERE linkid = %d", $_REQUEST['post']));
				
				 if ( wp_get_referer() )
				 {
							wp_safe_redirect( wp_get_referer() ); exit;
				 }
				 else
				 {
					wp_safe_redirect( get_home_url() ); exit;
				 }
            break;
            case 'recalculate_pages':
                if ( current_user_can( 'delete_pages') ) {
					if($wpdb->get_var("show tables like '{$wpdb->prefix}ns_tags%'") == "{$wpdb->prefix}ns_tags")
					{
					    if ( wp_get_referer() ){
							wp_safe_redirect( wp_get_referer() ); exit;
						}else{
							wp_safe_redirect( get_home_url() ); exit;
						}
					}
                }
                
                break;   
            
            default:
                AddNewTagsList();
            break;
        }
    }
}
function WpClearPages($post_id)
{
	
}
function AddNewTagsList()
{
    require_once ABSPATH .'wp-content/plugins/landing-page-booster/view/wp_page_list.php';
}

function instructions()
{
	include_once ABSPATH .'wp-content/plugins/landing-page-booster/view/instructions.php';
}


function PagesTags()
{
    global $sql;
    global $core;
    global $utilities;
    global $wpdb;
    
    $id     = $_REQUEST['pageid'];
    if ( get_option('permalink_structure') != '' ) {

        $permalinks = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name = 'permalink_structure' AND option_value='/%postname%/'");
        
        if(empty($permalinks)){
?>
     <div id="message" class="error"><div style="margin-right: 20px; margin-left: 20px; margin-top: 20px;"><p><a href="options-permalink.php">Please Update Your Permalinks to Custom Structure /%postname%/</a></p></div></div>
 <?php
        }
        
        if(!empty ($id)){
            $page   = $sql->GetKRPostNameByID($id);
            $tags   = $sql->GetKRtagsLinksByPageId($id);
            
            $params['page'] = $page;
            $params['tags'] = $tags;
            AddPagetagsUI($params);

        }else{
           wp_redirect( get_bloginfo('url') . '/wp-admin/admin.php?page=AddKrwUrl'); exit;
            exit;
        }
    }else{
?>
    <div style="margin-right: 20px; margin-left: 20px; margin-top: 20px;"><p><a href="options-permalink.php">Please Update Your Permalinks to Custom Structure /%postname%/</a></p></div>
 <?php
    }

}

function AddPagetagsUI($data)
{
    require_once ABSPATH .'wp-content/plugins/landing-page-booster/view/wp_page_tags_list.php';
}


function WpHtmlFormKwrUI()
{
    global $sql;
    global $core;
    global $utilities;
    global $wpdb;
    
    $tagsCounter = $sql->CounterTagsPage();
    
    if($_POST){
        if($tagsCounter['status']){
            $builder = $core->KRTagsWpGenerator();
        }else{
            $builder['code'] = FALSE;
            $builder['msg']  = $tagsCounter['message'];
        }
    }

    $id     = $_REQUEST['pageid'];
    $ancestors = get_post_ancestors( $id );
    sort($ancestors);
    if(!empty ($ancestors)){
        foreach ($ancestors as $value ){
          $parents = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE ID='{$value}' AND post_status='publish'");
          if(!empty ($parents)){
              foreach ($parents as $child ){
                $plink .= $child->post_name .'/';
              }
          }

        }
    }


    $page   = $sql->GetKRPostNameByID($id);
    $tags   = $sql->GetKRtagsLinksByPageId($id);
    
?>
    <div style="margin-right: 20px; margin-left: 20px; margin-top: 20px;">
    
    <input type="button" value="Back" class="button-primary" onclick="Back()" name="Back">
    <h2>Keyword Replacer URL Tag Generator Page : /<?=$page[$id];?>/ </h2>
    <p style="border:1px;border-style:dotted;padding:10px;">This page allows you to create your dynamic pages with up to three ##TAG## values. <br><br>Enter (or paste) your keywords into each list box, and then click the Generate button. Each dynamic page URL will be listed to confirm. <br>You can regenerate if something does not look correct prior to saving. When you're happy with the output, press Save to save and activate your dynamic page links.</p>
    
    <br>
    <br>

    <form method="POST">

    <fieldset style="display:inline;">
        <legend>TAG1</legend>
        <textarea class="krInput" name="tag1" rows="20" cols="25" style="margin: 5px;"><?=$_POST['tag1'];?></textarea>
    </fieldset>

    <fieldset style="display:inline;">
    <legend>TAG2</legend>
    <textarea class="krInput" name="tag2" rows="20" cols="25" style="margin: 5px;"><?=$_POST['tag2'];?></textarea>
    </fieldset>

    <fieldset style="display:inline;">
    <legend>TAG3</legend>
        <textarea class="krInput" name="tag3" rows="20" cols="25" style="margin: 5px;"><?=$_POST['tag3'];?></textarea>
    </fieldset>
    <br><br><br>
    <label for="url">URL </label>
    <input class="krInput" type="text" name="url" value="<?php echo get_bloginfo('url').'/'. $plink . $page[$id];?>" size="70" readonly="readonly" /> <input  class="button-primary" type="submit" value="Generate" />
    </form><br><br>
    <?php
	
    if($_POST){
    	
    	$plan 			= $sql->CounterTagsDefaultPage();
    	
    	if( $tagsCounter['limits'] != 0   ){
    		
    		if( $plan[ 'limits' ] == 1 ){
    			$plan_name = __( 'silver','netseek' );
    		}elseif(  $plan[ 'limits' ] == 3 ){
    			$plan_name = __( 'gold','netseek' );
    		}else{
    			$plan_name = __( 'platinum','netseek' );
    		}
    		$limits			 = $tagsCounter['limits'];	
    		$current_counter = count( $builder['totalCount'] );
    		$sumtags 		 = ( $tagsCounter['storedtags'] + $current_counter );
    		$remaining 		 = ( $limits - $tagsCounter['storedtags'] );    
    		
	    	if( $remaining <= 0 ){
		    	$remaining = 0;
		    }else{
		    	$remaining = $remaining;
		    }
    		
    		if( $sumtags > $limits  ){
    			$builder['code'] = FALSE;
    			$builder['msg']  = __( 'Only '.$remaining.' LPB dynamic url pages remaining under this '.$plan_name.' version of Landing Page Booster!','netseek');
    		}
    		
    	}
    	
    	
         if($builder['code']){
         	
         	
             ?>
               <fieldset style="border:1px;border-style:dotted;padding:10px;">
                <legend >Link Builder Result(s) :</legend>
                <div style="width:100;padding:10px;"><p> <?=$builder['msg'];?></p></div>
                <p>

                <input type="button" value="Save Tags" class="button-primary" onclick="JsSaveKrTags()" name="Save Tags">
                <input type="button" value="Reset" class="button-primary" onclick="Reset()" name="Reset">
                </p></fieldset>
    <?php
        }

        if(!$builder['code']){?>
            <script language="javascript" type="text/javascript">
            alert("<?=$builder['msg'];?>");
            </script>
    <?php
        }else{

        }
        ?>
        <script language="javascript" type="text/javascript">
            function JsSaveKrTags(){
                location.href="<?php echo admin_url( 'admin.php?page=AddKrwUrl&section=savepagestags&pageid='.$id );?>";
            }
            function Reset(){
                location.href="<?php echo admin_url( 'admin.php?page=AddKrwUrl&section=addtags&pageid='. $id );?>"; 
            }

            function Back(){
                location.href="<?php echo admin_url( 'admin.php?page=AddKrwUrl&section=pagestags&pageid='.$id );?>";
            }
       </script>
    <?php
    }
    ?><script language="javascript" type="text/javascript">
            function Back(){
                location.href="<?php echo admin_url( 'admin.php?page=AddKrwUrl&section=pagestags&pageid='.$id  );?>";
            }
      </script>

   </div>
    <?php
}

function WpEditKrTags()
{
    global $sql;
    global $core;
    global $utilities;
  
    $id     = $_REQUEST['pageid'];
    $page   = $sql->GetKRPageNameByID($id);
    
    if($_POST['Update']){
          global $wpdb;
          $url = $wpdb->escape( $_POST['url'] );
          $data = $wpdb->escape( $_POST['data'] );
          $tagid = $wpdb->escape( $_POST['tagid'] );
          $content = $wpdb->escape( $_POST['content'] );
          $edit = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_links WHERE Tags='{$url}'");
           
           if(empty($edit)){
               switch($_POST['Select_Para']){
                        case 'PARA1':
                            $result = $wpdb->query(
                                "UPDATE {$wpdb->prefix}ns_links
                                    SET Tags   = '".$url."',
                                        Data   = '".$data."',
                                        para1   = '".$content."'
                                WHERE id    = '{$tagid}'");
                               
                            
                            break;
                        case 'PARA2':
                            $result = $wpdb->query(
                                "UPDATE {$wpdb->prefix}ns_links
                                    SET Tags   = '".$url."' ,
                                        Data   = '".$data."',
                                        para2   = '".$content."'
                                WHERE id    = '{$tagid}'");
                            break;
                        case 'PARA3':
                            $result = $wpdb->query(
                                "UPDATE {$wpdb->prefix}ns_links
                                    SET Tags   = '".$url."' ,
                                        Data   = '".$data."',
                                        para3   = '".$content."'
                                WHERE id    = '{$tagid}'");
                            
                            break;
               }


             if($result){
                  $html .= '<div class="updated settings-error" style="margin-left: 0px;"><p>Tags and URL have been updated!</p></div>';
             }else{
                  $html .= '<div class="updated settings-error" style="margin-left: 0px;"><p>Tags and URL have been unable to update!</p></div>';
             }
               
           }else{
               switch($_POST['Select_Para']){
                        case 'PARA1':
                            $result = $wpdb->query(
                                "UPDATE {$wpdb->prefix}ns_links
                                    SET Tags   = '".$url."' ,
                                        Data   = '".$data."',
                                        para1   = '".$content."'
                                WHERE id    = '{$tagid}'");
                            
                            break;
                        case 'PARA2':
                            $result = $wpdb->query(
                                "UPDATE {$wpdb->prefix}ns_links
                                    SET Tags   = '".$url."' ,
                                        Data   = '".$data."',
                                        para2   = '".$content."'
                                WHERE id    = '{$tagid}'");
                            break;
                        case 'PARA3':
                            $result = $wpdb->query(
                                "UPDATE {$wpdb->prefix}ns_links
                                    SET Tags   = '".$url."' ,
                                        Data   = '".$data."',
                                        para3   = '".$content."'
                                WHERE id    = '{$tagid}'");
                            
                            break;
               }
               
               
                       
               $html .= '<div class="updated settings-error" style="margin-left: 0px;"><p>Tags and URL have been Updated!</p></div>';
           }
             $page   = $sql->GetKRPageNameByID($id);
    }
    
    
    
    
    ?>

    <div style="margin-right: 20px; margin-left: 20px; margin-top: 20px;">
    <input type="button" value="Back" class="button-primary" onclick="Back()" name="Back">
    <h2>Edit Keyword Replacer URL Tags Page : /<?=$page[0]['PageName'];?>/ </h2>
    <p style="border:1px;border-style:dotted;padding:10px;">Edit Your LPB Page Tags and URL</p>
    
    <?php  if(!empty($html)){
            echo $html;
        }
    ?>

    <form method="POST">
    <fieldset style="display:inline;border:1px;border-style:dotted;padding:10px;">
    <legend>URL</legend>
    <input  class="krInput" type="text" name="url" value="<?=$page[0]['Tags'];?>" size="70"/>
     </fieldset>
    <br>
    <br>
    <fieldset style="display:inline;border:1px;border-style:dotted;padding:10px;">
    <legend>TAGS</legend>
        <input type="hidden" name="tagid" value="<?=$id;?>" size="70"/>
        <input class="krInput" type="text" name="data" value="<?=$page[0]['Data'];?>" size="70"/>
    </fieldset>
    <br>
    <br>
    <?php
    
    $adminParams = $sql->GetKRAdminParams( $id );
    
    
    
    if($adminParams['respCode']){
        $para1 = stripcslashes(html_entity_decode($adminParams['respMsg']['para1']));
        $para2 = stripcslashes(html_entity_decode($adminParams['respMsg']['para2']));
        $para3 = stripcslashes(html_entity_decode($adminParams['respMsg']['para3']));
        
    }else{
        $para1 = '';
        $para2 = '';
        $para3 = '';
    }
    
    ?>
    
    <fieldset style="width: 97%;display:inline;border:1px;border-style:dotted;padding:10px;">
     <legend>UNIQUE PARAGRAPHS</legend>
     <select name="Select_Para">
        <option value="PARA1" <?php if ($_POST['Select_Para'] == 'PARA1') { echo 'selected';}?>>PARA1</option>
        <option value="PARA2" <?php if ($_POST['Select_Para'] == 'PARA2') { echo 'selected';}?>>PARA2</option>
        <option value="PARA3" <?php if ($_POST['Select_Para'] == 'PARA3') { echo 'selected';}?>>PARA3</option>
    </select> 
     <input class="button-primary"  type="submit" value="View Paragraph" name="Submit_Para" />
     <br>
     
   <?php
   
   if(function_exists('the_editor')){
       
        switch($_POST['Select_Para']){

            case 'PARA1':
        ?>
            <div id="poststuff">
                <?php
                wp_enqueue_script(array('jquery', 'editor', 'thickbox', 'media-upload'));
                wp_enqueue_style('thickbox');
                the_editor($para1, "content", "", false);
                ?>
            </div>
            <?php
            break;
            case 'PARA2':
            ?>
                <div id="poststuff">
                    <?php
                    wp_enqueue_script(array('jquery', 'editor', 'thickbox', 'media-upload'));
                    wp_enqueue_style('thickbox');
                    the_editor($para2, "content", "", false);
                    ?>
                </div>
                <?php
                break;
            case 'PARA3':
            ?>
                <div id="poststuff">
                    <?php
                    wp_enqueue_script(array('jquery', 'editor', 'thickbox', 'media-upload'));
                    wp_enqueue_style('thickbox');
                    the_editor($para3, "content", "", false);
                    ?>
                </div>
                <?php
                break;
            default :
            ?>
                <div id="poststuff">
                    <?php
                    wp_enqueue_script(array('jquery', 'editor', 'thickbox', 'media-upload'));
                    wp_enqueue_style('thickbox');
                    the_editor($para1, "content", "", false);
                    ?>
                </div>
                <?php
                break;

        }

     
     

     ?>
    
        
     <?php }else{ ?>   
        PARA1 : <textarea rows="2" cols="20"><?=$para1;?></textarea><br>
        PARA2 : <textarea rows="2" cols="20"><?=$para2;?></textarea><br>
        PARA3 : <textarea rows="2" cols="20"><?=$para3;?></textarea><br>
     <?php } 
     
     
     ?>   
         
    </fieldset>
    <br>
    <br>
    <input class="button-primary" type="submit" name="Update" value="Update" />
    </form><br><br>
    </div>
    <script language="javascript" type="text/javascript">
        function Back(){
            location.href="<?php echo  get_bloginfo('url') .'/wp-admin/admin.php?page=AddKrwUrl&section=pagestags&pageid='.$page[0]['PostId'];?>";
        }
    </script>
<?php

}



function WpEditOverridePage()
{
    global $sql;
    global $core;
    global $utilities;
    
    $id     = $_REQUEST['pageid'];
    $page   = $sql->GetKRPageNameByID($id);

    if($_POST){
        
          global $wpdb;
           $url     = $wpdb->escape( rtrim($_POST['url'] , "/"));
           $tagid   = $wpdb->escape( $_POST['tagid'] );
           
           $edit = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_links WHERE PageNameOverride='{$url}'");
           
           if(empty($edit)){
               $result = $wpdb->query(
                     "UPDATE {$wpdb->prefix}ns_links
                         SET PageNameOverride   = '".$url."'
                       WHERE id    = '{$tagid}'"
                );
                       
                if($result){
                  $html .= '<div class="updated settings-error" style="margin-left: 0px;"><p>Page URL Override has been Updated!</p></div>';
                    }else{
                  $html .= '<div class="updated settings-error" style="margin-left: 0px;"><p>Page URL Override has been unable to update!</p></div>';
                }       
           }else{
               $html .= '<div class="updated settings-error" style="margin-left: 0px;"><p>Page URL Override Already Exists!</p></div>';
           } 

             $page   = $sql->GetKRPageNameByID($id);
             
            
    }
    
     
    ?>

    <div style="margin-right: 20px; margin-left: 20px; margin-top: 20px;">
    <input type="button" value="Back" class="button-primary" onclick="Back()" name="Back">
    <h2>Override KR Page URL</h2>
    
    <?php  if(!empty($html)){
            echo $html;
        }
    ?>

    <form method="POST">
    <fieldset style="display:inline;border:1px;border-style:dotted;padding:10px;">
    <legend>URL</legend>
    <input class="krInput" type="text" name="url" value="<?=$page[0]['PageNameOverride'];?>" size="70"/>
    <input type="hidden" name="tagid" value="<?=$id;?>" size="70"/>
     </fieldset>
    <br>
    <br>
    <input type="submit" value="Update" class="button-primary"  />
    </form><br><br>
    </div>
    <script language="javascript" type="text/javascript">
        function Back(){
            location.href="<?php echo  get_bloginfo('url') .'/wp-admin/admin.php?page=AddKrwUrl&section=pagestags&pageid='.$page[0]['PostId'];?>";
        }
    </script>
<?php

}


function krSetup(){
    require_once ABSPATH .'wp-content/plugins/landing-page-booster/view/admin_setup_tabs.php';  
}

function MenuManagement()
{
	
 	// if ( get_option( '_deactivate_checkbox_key' ) == 'on' ){
		add_menu_page('Landing Page Booster' , 'Landing Page Booster', 'kr_LPB_admin', 'krurls', 'AddKrwUrl',false);
		// add_submenu_page('krurls', __('Add New','menu-kwr'), __('Add New','menu-kwr'), 'manage_options', 'AddKrwUrl', 'AddKrwUrl');
		add_submenu_page('krurls', __('Instructions','menu-kwr'), __('Instructions','menu-kwr'), 'kr_LPB_admin', 'instructions', 'instructions');
		add_submenu_page('krurls', __('License','menu-kwr'), __('License','menu-kwr'), 'manage_options', 'krSetup', 'krSetup');
	/* } 
	else{
		add_menu_page('Landing Page Booster' , 'Landing Page Booster', 'manage_options', 'krSetup', 'krSetup',false);
		// remove_submenu_page('krurls',"instructions");
	}
	
 */
}


?>
