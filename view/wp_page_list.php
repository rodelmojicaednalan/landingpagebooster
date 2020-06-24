<?php
/**
 * User Interface Record Page Listing
 *
 * @author 		Netseek
 * @category 	Admin
 * @package 	LandingPageBooster/Admin/View
 * @version     2.4.4
 */


add_action( 'admin_footer', 'admin_kr_page_list_js' );

function admin_kr_page_list_js() {

    ?>
    <script type="text/javascript">
    /* <![CDATA[ */
    
    jQuery(document).ready(function($){
        
        var ShowMoreHandler =  function(event){
            
            $('div#loader').unbind("click");
            $('div#loader').html('<img src="<?php echo plugins_url(); ?>/landing-page-booster/assets/images/ajax-loader.gif" alt="Loading...">');
            
            $.post("<?php echo plugins_url(); ?>/landing-page-booster/view/wp_page_list_ajax.php?lastID="+$(".contentpane table tr:last").attr("id"),
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

    global $sql;
    global $core;
    global $utilities;   
	$numberstripe= 0;
  
  
	$valid = get_option( '_deactivate_checkbox_key' );
	
    if(isset($_GET['lastID']) && is_numeric($_GET['lastID']))
        $lastID =intval($_GET['lastID']);
     
    if(!isset($_GET['lastID'])){
     $pages = $sql->GetKRPostName2();
     $plan_name = __( 'LPB Platinum License','netseek' );
     $plan_message =    "<strong>Unlimited</strong> LPB Landing Pages Allowed ";
   
    ?>
	  <style>
	#the-list tr td
		{
			line-height: 41px;
		}
        </style>
    <div style="margin-right: 20px; margin-left: 20px; margin-top: 20px;">
        <h1><?php _e( 'Landing Page Booster URL Parameter Tag Management' )?></h1>
         <p style="border: 1px solid rgba(0, 0, 0, 0.07);padding:10px;"><?php _e('The list below contains your site\'s published pages. Within this list you should see the Landing Page Booster pages you have created that you wish to pass your keyword tags to. You can set the default tag values of your designated page by selecting Set Page Default on the right of the page item. Once page defaults have been set up, that page is ready to accept tag parameters.');?></p>
       <?php
		//display Package Status box if activated
	   if($valid == 'on')
	   {
		   ?>
		    <p style="border:1px;padding:10px;background-color: #ffffff;border-left: #03c300 3px solid;"><?php _e( $plan_message ,'netseek'); ?></p>

		   <?php
	   }
	   
	/*  html seperator Pagination of LPB with Navi Link */
	
	$pagination = "<div class=\"tablenav top\">";
	$status = md5("active");
	/*Max Number of results to show*/
	$max = 20;
	// get count the publish and draft page 
	$n_page = wp_count_posts('page'); 
	$n_page_publish = $n_page->publish;
	$n_page_drafts = $n_page->draft;
	//count the setpage defualt
	$ns_page_dafualt = $sql->count_Pagedefault_active();
	$ns_page_dafualt = $ns_page_dafualt[0]->Tags;
	$post_status= "";
	if(isset($_GET['page_link'])){
		$p = $_GET['page_link'];
	}else{
		$p = 1;
	}
	if(isset($_GET['post_status'])){
		$post_status= $_GET['post_status'];
		$totalres = $ns_page_dafualt;
	}
	else
	{
		$totalres = ($n_page_publish + $n_page_drafts);
	}
	$limit = ($p - 1) * $max;
	$prev = $p - 1;
	$next = $p + 1;
	$limits = (int)($p - 1) * $max;
	$all_pages = ($n_page_publish + $n_page_drafts);
	$result = $sql->get_pagination($limits,$max,$post_status);
	
	if(!empty ($result)&& is_array($result)){
		foreach($result as $key=>$value){
		if(empty($value)){
			foreach(get_post($key) as $key2=>$value1)
			{
				 if($key2 == "post_title") {
					 //echo $value1."<br>";
					 $result[$key] = $value1;
				 }
					
			} 
		
		}
	}
	}
	$totalposts = ceil($totalres / $max);
	$lpm1 = $totalposts - 1; 

	// echo $count_posts;
	// post status link page html
	$first_page = isset($_GET['post_status']) ? "?page=krurls&post_status=LandingPage&page_link=1": "?page=krurls&page_link=1";
 	$prev_page = isset($_GET['post_status']) ? "?page=krurls&post_status=LandingPage&page_link=$prev": "?page=krurls&page_link=$prev";
	$next_page = isset($_GET['post_status']) ? "?page=krurls&post_status=LandingPage&page_link=$next": "?page=krurls&page_link=$next";
	$last_page = isset($_GET['post_status']) ? "?page=krurls&post_status=LandingPage&page_link=$totalposts": "?page=krurls&page_link=$totalposts";
  
	$adjacents = 3;
	// html filter page categories
	$pagination .= "<ul class=\"subsubsub\" style=\"margin: 2px 0 0!important;\">";
	$pagination .= "<li class=\"all\">";
	$pagination .= isset($_GET['post_status'])  ? "<a href=\"admin.php?page=krurls\">All ": "<a class=\"current\" href=\"admin.php?page=krurls\">All ";
	$pagination .= "<span class=\"count\">($all_pages)</span>";
	$pagination .= "</a> |</li>";
	$pagination .= "<li class=\"live-editor\">";
	$pagination .= isset($_GET['post_status']) ? "<a class=\"current\" href=\"admin.php?page=krurls&post_status=LandingPage\">LBP Pages ": "<a  href=\"admin.php?page=krurls&post_status=LandingPage\">LBP Pages ";
	$pagination .= "<span class=\"count\">($ns_page_dafualt)</span>";
	$pagination .= "</a></li>";
	$pagination .= "</ul>";
	
	
	//pagenavi if landing page list with input page link
	$pagination .= "<div class=\"tablenav-pages\">";
	$pagination .= "<span class=\"displaying-num\">".$totalres ." Items </span>";
	if($totalposts > 1)
	{

	$pagination .= "<span class=\"pagination-links\" style=\"display: inline-block;\">";
	//previous button
	if ($p > 2)
	{
	//$pagination.= "<a href=\"?page=krurls&page_link=1\">‹‹</a>";
	$pagination.= "<a href=\"$first_page\">«</a>";
	}
	else
	{
	$pagination.= "<span class=\"tablenav-pages-navspan\" aria-hidden=\"true\">«</span>";
	}
	if ($p > 1)
	{
	$pagination.= "<a class=\"prev-page\" href=\"$prev_page\">";
	$pagination.= "<span class=\"screen-reader-text\">Previous page</span>";
	$pagination.= "<span aria-hidden=\"true\">‹</span>";
	$pagination.= "</a>";
	}
	else
	{
	$pagination.= "<span class=\"tablenav-pages-navspan\" aria-hidden=\"true\">‹</span>";
	}

	/* Page link input
	*  Label maximum page link 
	*/
	$pagination.= " <span class=\"paging-input\">";
	$pagination.= "  <label for=\"current-page-selector\" class=\"screen-reader-text\">Current Page</label>";
	$pagination.= " <input onkeydown=\"page_link($totalposts)\" class=\"current-page\" id=\"current-page-selector\" type=\"text\" name=\"paged\" value=$p size=\"1\" aria-describedby=\"table-paging\">";
	$pagination.= " of ";
	$counter_pages = 0;
	for ( $counter =1; $counter <= $totalposts; $counter++){
	if ($counter == $p)

	$counter_pages = $counter;
	else
	$counter_pages = $counter;

	} 
	$pagination.= "<span class=\"total-pages\" style=\"padding-right: 5px;display: inline-block;\">$totalposts </span>";

	$pagination.= "</span>";
	//next button
	if ($p < $counter_pages)
	{
	$pagination.= "<a href=\"$next_page\">›</a>";
	}
	else
	{
	$pagination.= "<span class=\"tablenav-pages-navspan\" aria-hidden=\"true\">›</span>";
	}
	if ($p < $counter_pages - 1)
	{
	$pagination.= "<a href=\"$last_page\">»</a>";
	}
	else
	{
	$pagination.= "<span class=\"tablenav-pages-navspan\" aria-hidden=\"true\">»</span>";
	}
	}
	$pagination.= "</div></div>\n";
	
	echo  $pagination;  
  ?>
    </div>
    <div style="margin-right: 20px; margin-left: 20px; margin-top: 16px;">
   <div class="contentpane">
  <table class="wp-list-table widefat fixed posts">
        <thead>
            <tr>
				<!--<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>-->
                <th>Page Name</th>
                <th><center>LPB Defaults Applied</center></th>
                <th  style="width:23%;"><center>Action</center></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
			<!--<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>-->
            <th>Page Name</th>
            <th><center>LPB Defaults Applied</center></th>
            <th style="width:23%;"><center>Action</center></th>
            </tr>
        </tfoot>
        <tbody id="the-list">
            <?php
         if(!empty ($result) && is_array($result))
		 {
			 
           foreach ($result as $key => $val){
			   if($val == "")
			   {
				   
			   }
			   else
			   {
			$filterbg=$numberstripe++ % 2 == 0;
            ?>
           <tr  class="iedit author-self level-0 post-943 type-page status-publish hentry" id="<?php echo $key;?>" <?php echo $filterbg == 1?'style="background-color: #f6f6f6;"':'';?>>
               <!--<td>
			   </td>-->
			  <td  data-colname="Title" class="row-title" style="color: #0073aa;"> <strong><?php echo  str_replace("-"," ", $val); ?></strong>
               </td>
             <td>
				<center>
             <?php
             	if( $sql->is_page_default( $key ) ) {
					// html dashicons check icon of set page defualt
					$page_status=$sql->is_page_default_active( $key );
					if( $page_status[0]->status == $status)
					{
						echo '<span style="line-height: 41px;font-size: 23px;"class="dashicons dashicons-yes"></span>';
					}
             	}
             ?> </center>
             </td>
             <td >
			 <center>
			 
			 <?php
			 
				if( $sql->is_page_default( $key ) ) {
				 	$page_status=$sql->is_page_default_active( $key );
					if( $page_status[0]->status == $status)
					{
					// html view page and clear page default value 			
					?>
                    <a href="<?php echo get_bloginfo('url').'/'.$val ;?>" target="_blank">View </a>|
				    <a href="<?=get_bloginfo('wpurl').'/wp-admin/post.php?post='.$key.'&action=edit';?>">Set Page Defaults </a>|
					<a href='javascript:;' onclick='_clear_page(<?php echo $key; ?>)'>Clear</a> 
				 
			<?php 
					}
					else
					{
						?>
							<a href="<?=get_bloginfo('wpurl').'/wp-admin/post.php?post='.$key.'&action=edit';?>">Set Page Defaults</a>
						<?php 
					}
					}
					else
					{
						?>
							<a href="<?=get_bloginfo('wpurl').'/wp-admin/post.php?post='.$key.'&action=edit';?>">Set Page Defaults</a>
					<?php 
					}
					
			?>
			 </center>
             </td>
		   </tr>
           <?php
		   }
           }
         }else{
           ?>
          <tr style="background-color: #f9f9f9;" class="no-items"><!--<td></td>--><td class="colspanchange">No pages found.</td><td></td><td></td></tr>
         
        <?php
         }
        ?>
		
        </tbody>
    </table>

     </div>    
       <!-- <div id="loader">Show More ...</div> -->
    </div>
	<script language="javascript" type="text/javascript">
    <!--
	
	 
	 // Redicrect Page link on input number of pagenavi
	 
	function page_link(totalposts)
	{
		var keyPressed = event.keyCode || event.which;
		page_selector = document.getElementById('current-page-selector');
		page_links = <?php echo $p;?>;

		//alert(totalposts + " " + page_selector.value +(totalposts > page_selector.value ));
		if(keyPressed==13 )
		{
			if(totalposts > page_selector.value )
			{
					location.href = "?page=krurls&page_link=" + page_selector.value;
			}
			else
			{
					location.href = "?page=krurls&page_link=" + totalposts;
			}
		}
		
	}
    function deletes()
    {
      var url = '<?php echo get_bloginfo('url') ."/wp-admin/admin.php?page=AddKrwUrl&section=deleteTags&pageid="?>' + pageid + '<?php echo '&id='?>' + id;
	
	  alert("asdfasd");
	
    }
    // -->
    </script>
    <?php
    }else{
        
    }
	
	function kriesi_pagination($pages = '', $range = 2)
	{  
		 
	}	
?>
