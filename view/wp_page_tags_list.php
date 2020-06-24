<?php
/**
 * User Interface Record Tags Listing 
 *
 * @author 		Netseek
 * @category 	Admin
 * @package 	LandingPageBooster/Admin/View
 * @version     2.4.4
 */


add_action( 'admin_footer', 'admin_kr_page_tags_list_js' ); 

function admin_kr_page_tags_list_js() {

    ?>
    <script type="text/javascript">
    /* <![CDATA[ */
    
    jQuery(document).ready(function($){
        
        var ShowMoreHandler =  function(event){
            
            $('div#loader').unbind("click");
            $('div#loader').html('<img src="<?php echo plugins_url(); ?>/landing-page-booster/assets/images/ajax-loader.gif" alt="Loading...">');
            
            $.post("<?php echo plugins_url(); ?>/landing-page-booster/view/wp_page_tags_list_ajax.php?pageid=<?=$_GET['pageid']?>&lastID="+$(".contentpane table tr:last").attr("id"),
                function(data){
                    if (data != "") {
                        $(".contentpane table tr:last").after(data);           
                        $('div#loader').html("Show More ...");
                        $('div#loader').bind('click',ShowMoreHandler);
                    }else{
                        $('div#loader').html("No More Record(s)"); 
                        $('div#loader').unbind("click");
                    }
                });
           return false;       
        };
       
        $('div#loader').bind('click',ShowMoreHandler);
        
        $(window).scroll(function(){
            if  ($(window).scrollTop() == $(document).height() - $(window).height()){
                    $('div#loader').trigger('click');
            }

        });

       

    });
    
    /* ]]> */
    </script>
    <?php
}
    
    $pages = $data['page'];
    $tags  = $data['tags'];
    $id     = $_REQUEST['pageid'];
    
    global $wpdb;
    global $sql;

    $ancestors = get_post_ancestors( $id );
    sort($ancestors);
    if(!empty ($ancestors)){
        foreach ($ancestors as $value ){
          $parents = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE ID='{$value}' AND post_status='publish'");
          if(!empty ($parents)){
              //echo '<pre>'; print_r($parents); echo '</pre>';
              foreach ($parents as $child ){
                $plink .= $child->post_name .'/';
              }
          }
        }
    }
    if(isset($_GET['lastID']) && is_numeric($_GET['lastID']))
        $lastID =intval($_GET['lastID']);
     
    if(!isset($_GET['lastID'])){
?>
    <div style="margin-right: 20px; margin-left: 20px; margin-top: 20px;">
        <h2>Keyword Replacer URL Tag List Page : /<?=$pages[$id]?>/ </h2>
        <p style="border:1px;border-style:dotted;padding:10px;">The list below contains all your page keyword variations. If the list in empty, it means you have not created any dynamic landing pages yet - click on the Add New button to get started. </p>
        <br>
        <?php
        
        $linkid  = $wpdb->escape( $_GET['pageid'] );
        $result  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_tags WHERE linkid='{$linkid}'");

        $tagsCounter = $sql->CounterTagsDefaultPage();
        $resMessage  = $tagsCounter['message'];
        if($tagsCounter['status']){
            if(!empty($result)){
          ?>
                    <p>
                        <input type="button" value="Add New" class="button-primary" name="AddNew" onclick="location.replace('<?php echo get_bloginfo('url') . '/wp-admin/admin.php?page=AddKrwUrl&section=addtags&pageid='.$id;?>');">
                        <input type="button" value="Back" class="button-primary" name="Back" onclick="location.replace('<?php echo get_bloginfo('url') . '/wp-admin/admin.php?page=AddKrwUrl';?>');">
                    </p>
               <?php
            }else{
                ?>
                <p>
                    <input type="button" value="Back" class="button-primary" name="Back" onclick="location.replace('<?php echo get_bloginfo('url') . '/wp-admin/admin.php?page=AddKrwUrl';?>');">
                 </p>
                 <?php   
                showMessage2('Before you begin, please define the default LPB values in edit page settings! <a href="'.get_bloginfo('wpurl').'/wp-admin/post.php?post='.$id.'&action=edit">Set Page Defaults</a>');
            }   
        }else{
            if(!empty($result)){
                ?>
                    <p>
                        <input type="button" value="Add New" class="button-primary" name="AddNew" onclick="location.replace('<?php echo get_bloginfo('url') . '/wp-admin/admin.php?page=AddKrwUrl&section=addtags&pageid='.$id;?>');">
                        <input type="button" value="Back" class="button-primary" name="Back" onclick="location.replace('<?php echo get_bloginfo('url') . '/wp-admin/admin.php?page=AddKrwUrl';?>');">
                    </p>
               <?php
                }else{
                   ?>
                    <p>
                        <input type="button" value="Back" class="button-primary" name="Back" onclick="location.replace('<?php echo get_bloginfo('url') . '/wp-admin/admin.php?page=AddKrwUrl';?>');">
                    </p>
                    <?php
                    showMessage2($resMessage);
                }
        }
        
        ?> 
      <?php  if(!empty($_SESSION['AUTH']['FLASHMSG'])){
                    ?> 
                <div class="updated settings-error" style="margin-left: 0px; margin-right: 0px;"><p><?=$_SESSION['AUTH']['FLASHMSG'];?></p></div>
                    <?php
                    unset($_SESSION['AUTH']['FLASHMSG']);
                }
    ?>
    </div>
    <div style="margin-right: 20px; margin-left: 20px; margin-top: 20px;">
        <div class="contentpane">
    <table class="wp-list-table widefat fixed posts">
        <thead>
            <tr>
                <th style="width: 30px;">ID</th>
                <th>Page Name</th>
                <th>URL</th>
                <th>Tags</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
            <th>ID</th>
            <th>Page Name</th>
            <th>URL</th>
            <th>Tags</th>
            <th>Action</th>
            </tr>
        </tfoot>

        <tbody>
            <?php
         if(!empty ($tags)){
          $x = 0;
           foreach ($tags as $key => $val){
            ?>
           <tr id="<?php echo $val['id'];?>">
               <td ><?php echo $val['id']; ?></td>
               <td>/<?php echo str_replace("-"," ", $val['PageName']); ?>/</td>
               <td><a href="<?php echo get_bloginfo('url') .  '/'. $plink  . $val['PageName'] . '/'. $val['Tags'];?>" target="_blank"><?php echo $plink . $val['PageName'] . '/'. $val['Tags']; ?></a></td>
             <td><?php
             if(!empty ($val['Data'])){
                    echo $val['Data'];
                }else{
                    echo 'No Tags Available';
             }
             ?></td>
             <td>
                 <a href="<?php echo get_bloginfo('url') .  '/'. $plink  . $val['PageName'] . '/'. $val['Tags'];?>" target="_blank">View </a> |
                 <a href="admin.php?page=AddKrwUrl&section=editpagestags&pageid=<?=$val['id']?>">Edit </a> |
                 <a href="#" onclick="return deletes('<?=$val['id']?>','<?=$id?>');">Delete</a>
             </td>
           </tr>
           <?php
           }
         }
        ?>
        </tbody>
    </table>
     </div>
         <div id="loader">Show More ...</div>
    </div>
    <script language="javascript" type="text/javascript">
    <!--

    function deletes( id , pageid )
    {
      var url = '<?php echo get_bloginfo('url') ."/wp-admin/admin.php?page=AddKrwUrl&section=deleteTags&pageid="?>' + pageid + '<?php echo '&id='?>' + id;
      var answer = confirm ("Delete TAGS # " + id + " ?");
        if (answer){
            window.location = url;
                return true;
            }else{
                return false;
            }
    }
    // -->
    </script>
    <?php
    } 
    ?>