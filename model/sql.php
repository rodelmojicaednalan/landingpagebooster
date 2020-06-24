<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * LandingPageBooster KwReplacerQuery
 *
 * SQL Query API Method
 *
 * @class 		KwReplacerQuery
 * @version		2.4.4
 * @package		LandingPageBooster/Classes
 * @category	Class
 * @author 		Netseek
 */   

class KwReplacerQuery
{
    /**
	 * Get Keyword Default Tags
	 *
	 * @access public
	 * @return array
	 */
	 
    public function GetNstags()
    {
        global $post;
        global $wpdb;

        $linkid = esc_sql( $post->ID );
        $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_tags WHERE linkid='{$linkid}'");

            if($result){
                foreach ($result as $val ){
                      $data =  array(
                                    'id'        => (string) $val->id,
                                    'linkid'    => (string) $val->linkid,
                                    'title'     => (string) $val->title,
                                    'keyword'   => (string) $val->keyword,
                                    'description'   => (string) $val->description,
                                    'tags'           => (string) $val->tags
                      );
                }

                $response['respCode'] = TRUE;
                $response['respMsg'] = $data;

            }else{

                $response['respCode'] = FALSE;
                $response['respMsg']  = 'Request Error!, Please try again.';
            }

            return $response;
    }

    /**
	 * Get Keyword Dynamic Tags
	 *
	 * @access public
	 * @return array
	 */

    public function GetDataTags($krtags)
    {
        global $wpdb;
        $krtags = esc_sql( $krtags );
        $tags   = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_links WHERE Tags = '{$krtags}'");

        foreach ($tags as $post) {
            $output[] = array(
                'Data'      =>  (string) $post->Data
            );
        }
        return $output;
    }
    
    /**
	 * Get Keyword Dynamic Tags
	 *
	 * @access public
	 * @return array
	 */
    
    public function GetDataTags2($krtags)
    {
        global $wpdb;
        $krtags = esc_sql( $krtags );
        $tags   = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_links WHERE PageNameOverride = '{$krtags}'");

        foreach ($tags as $post) {
            $output[] = array(
                'Data'      =>  (string) $post->Data
            );
        }
        return $output;
    }
    
    /**
	 * Get Keyword Paragraph Tags
	 *
	 * @access public
	 * @return array
	 */
	 
    public function GetParaByTags($krtags)
    {
        global $wpdb;
        $krtags = esc_sql( $krtags );
        $tags   = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_links WHERE Tags = '{$krtags}'");

        foreach ($tags as $post) {
            $output[] = array(
                '##PARA1##'      => stripslashes(html_entity_decode($post->para1)),
                '##PARA2##'      => stripslashes(html_entity_decode($post->para2)),
                '##PARA3##'      => stripslashes(html_entity_decode($post->para3))
            );
        }
        return $output;
    }
    
    /**
	 * Get Keyword Paragraph Tags
	 *
	 * @access public
	 * @return array
	 */
	     
    public function GetParaByPageNameOverride($krtags)
    {
        global $wpdb;
        $krtags = esc_sql( $krtags );
        $tags   = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_links WHERE PageNameOverride = '{$krtags}'");

        foreach ($tags as $post) {
            $output[] = array(
                '##PARA1##'      =>  stripslashes(html_entity_decode($post->para1)),
                '##PARA2##'      =>  stripslashes(html_entity_decode($post->para2)),
                '##PARA3##'      =>  stripslashes(html_entity_decode($post->para3)) 
            );
        }
        
        return $output;
    }
    
    /**
	 * Get Keyword Default Tags by ID
	 *
	 * @access public
	 * @return array
	 */
    
    public function GetTags($id)
    {
    global $post;
    global $wpdb;

    $id = esc_sql( $id );
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_tags WHERE linkid='{$id}'");

        if($result){
            foreach ($result as $val ){
                  $data =  array(
                                'tags'        => (string) $val->tags
                  );
            }
            $response['respCode'] = TRUE;
            $response['respMsg'] = $data;

        }else{

            $response['respCode'] = FALSE;
            $response['respMsg']  = 'A request error occurred, please try again.';
        }

        return $response;
    }

    /**
	 * Get Keyword Admin Params by ID
	 *
	 * @access public
	 * @return array
	 */
    
    
    public function GetKRAdminParams( $id )
    {
        
    global $post;
    global $wpdb;

    $id     = esc_sql( $id );
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_links WHERE id='{$id}'");
    
    if($result){
        
        foreach ($result as $val ){
                  $data =  array(
                                'para1'        => (string) $val->para1,
                                'para2'        => (string) $val->para2,
                                'para3'        => (string) $val->para3
                  );
            }

            $response['respCode'] = TRUE;
            $response['respMsg'] = $data;
        
    }else{
        $response['respCode'] = FALSE;
        $response['respMsg']  = 'A request error occurred, please try again.';
    }
    
    return $response;
    
    }

    /**
	 * Get WP Postname
	 *
	 * @access public
	 * @return array
	 */

    public function GetPostName()
    {
        global $wpdb;
        $request = "SELECT ID, post_name FROM {$wpdb->prefix}posts WHERE post_status = 'publish' ";
        $posts = $wpdb->get_results($request);
        $output = '';
        foreach ($posts as $post) {
            $post_name = ($post->post_name);
            $output[] = $post_name;
        }
        return $output;
    }

    /**
	 * SQL Installer
	 *
	 * @access public
	 * @return void
	 */

    public static function SetSQLInstaller()
    {

    global $wpdb;

        if($wpdb->get_var("show tables like '{$wpdb->prefix}ns_tags%'") == "{$wpdb->prefix}ns_tags"){
        }else{

                $sql = "
                        CREATE TABLE `{$wpdb->prefix}ns_tags` (
                          `id` bigint(20) NOT NULL AUTO_INCREMENT,
                          `linkid` int(11) DEFAULT NULL,
                          `title` varchar(200) DEFAULT NULL,
                          `keyword` varchar(200) DEFAULT NULL,
                          `description` text ,
                          `tags` text ,
                          `status` varchar(50) DEFAULT NULL ,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
                ";
                $wpdb->query($sql);
        }

        if($wpdb->get_var("show tables like '{$wpdb->prefix}ns_links%'") == "{$wpdb->prefix}ns_links"){
            
            
            $col_para1 = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}ns_links WHERE Field = 'para1'");
            $col_para2 = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}ns_links WHERE Field = 'para2'");
            $col_para3 = $wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}ns_links WHERE Field = 'para3'");
            
            if(empty($col_para1)){
                $wpdb->query("ALTER TABLE {$wpdb->prefix}ns_links ADD para1 text");
            }
            
            if(empty($col_para2)){
                $wpdb->query("ALTER TABLE {$wpdb->prefix}ns_links ADD para2 text");$wpdb->query($sql);
            }
            
            if(empty($col_para3)){
                $wpdb->query("ALTER TABLE {$wpdb->prefix}ns_links ADD para3 text");
            }
            
            
        }else{
            $sql = "
                        CREATE TABLE `{$wpdb->prefix}ns_links` (
                          `id` bigint(20) NOT NULL AUTO_INCREMENT,
                          `PostId` int(11) DEFAULT NULL,
                          `PageName` varchar(200) DEFAULT NULL,
                          `Tags` text,
                          `Data` text,
                          `PageNameOverride` varchar(200) DEFAULT NULL,
                          `para1` text NOT NULL,
                          `para2` text NOT NULL,
                          `para3` text NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
                ";

            $wpdb->query($sql);
        }
        
    }

    /**
	 * Set NS Tags URL Links
	 *
	 * @access public
	 * @return array
	 */

    public  function SetNsTagsUrlLinks()
    {
        global $wpdb;
        global $post;

        if($_POST['NsSubmit']){
            $nsurltagsid    = esc_sql( trim($_POST['ns-url-tags-id']) );
            $nsurltags     = esc_sql( trim($_POST['ns-url-tags']) );

            $result = $wpdb->query(
                         "UPDATE {$wpdb->prefix}posts
                             SET guid          = '{$nsurltags}'
                           WHERE ID         = '{$nsurltagsid}'"
                    );

             if($result){
                    $response['respCode'] = TRUE;
                    $response['respMsg'] = 'Data Updated Successfully!';
              }else{
                $response['respCode'] = FALSE;
                $response['respMsg']  = 'Data Update Failed!';
              }
        }

        return $response;
    }

    /**
	 * Set NS Tags
	 *
	 * @access public
	 * @return array
	 */

    public function SetNsTags()
    {

        global $wpdb;
        global $post;

        //if($_POST['NsSubmit']){

        $linkid                 = esc_sql( $post->ID );
        $title                  = esc_sql( trim($_POST['ns-title']) );
        $keyword                = esc_sql( trim($_POST['ns-keywords']) );
        $description            = esc_sql( trim($_POST['ns-description']) );
        $tags                   = esc_sql( trim($_POST['ns-tags']) );
        $guid                   = $_POST['ns-link'];
        $id                     = esc_sql( $countryCode );
        $result                 = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_tags WHERE linkid='{$linkid}'");
        $is_empty = empty( $title  ) || empty( $keyword  ) || empty( $description) ;
        $keys_data = array();
		$status = md5("active");
        unset ( $keys_data );
        
        if( ! empty( $title  ) ) {
        	$keys_data[] = $title;
        }

		if( ! empty( $keyword  ) ) {
        	$keys_data[] = $keyword;
        }

		if( ! empty( $description  ) ) {
        	$keys_data[] = $description;
        }
        
        if( ! empty ( $keys_data ) && empty ( $tags ) ) {
        
        	$response['respCode'] = FALSE;
			$response['respMsg']  = 'Invalid Landing Page Booster default Tag values. Please enter them using :: separators and dashes ( - ) in place of spaces'; 
        
        } else {
			
			$plan 			= $this->CounterTagsDefaultPage();
			
			// Statement empty page of fields
			 if($is_empty && !isset($_GET['action'])  == 'trash')
			{
				if($_POST['NsSubmit'])
				{
					$response['respCode'] = FALSE;
					$response['respMsg']  = 'Please complete the required fields!';
					return $response; 
				}
				if($_POST['save'] && !empty( $title  ) || !empty( $keyword  ) || !empty( $description) )
				{
					$response['respCode'] = FALSE;
					$response['respMsg']  = 'Please complete the required fields!';
					return $response; 
				}
				else
				{
					$response['respCode'] = FALSE;
					$response['respMsg']  = '';
					return $response; 
				}
			}
			// 
			if(!$result)
			{	
				$result = $wpdb->insert("{$wpdb->prefix}ns_tags",
							  array(
									 'linkid'           => empty($linkid) ? ' ' : $linkid ,
									 'title'            => empty($title) ? ' ' : $title  ,
									 'keyword'          => empty($keyword) ? ' ' : $keyword  ,
									 'description'      => empty($description) ? ' ' : $description,
									 'tags'             => empty($tags) ? ' ' : $tags,
									 'status'             => $status
				) );
				// if action multi trash , remove values of landing page
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
				$response['respCode'] = FALSE;
				$response['respMsg']  = "";
			}
			/* 	 else
			 {
				   $response['respCode'] = FALSE;
				   $response['respMsg']  = 'Unable to save Landing Page Booster!';
			 }  */
		 	  else{
				 $result = $wpdb->query(
								"UPDATE {$wpdb->prefix}ns_tags
								 SET title      = '{$title}' ,
								 keyword        = '{$keyword}' ,
								 description    = '{$description}',
								 tags           = '{$tags}',
								 status			=  '{$status}'
								 WHERE linkid   = '{$linkid}'"
							);
					 if($result){
							$response['respCode'] = TRUE;
							$response['respMsg'] = '';
					  }else{
						// $response['respCode'] = FALSE;
						// $response['respMsg']  = 'Data Update Failed!';
							 $response['respCode'] = TRUE;
							$response['respMsg'] =  '';						
					  }  
				}
        }
        //echo 'xxx';
        //}else{
         //   $response['respCode'] = FALSE;
          //  $response['respMsg']  = 'Data Updated Failed!';
       // }
        return $response;
    }


    /**
	 * Get Wordpress Post Name
	 *
	 * @access public
	 * @return array
	 */


    public function get_post_name()
    {
        global $wpdb;
        $request = "SELECT ID, post_name FROM {$wpdb->prefix}posts WHERE post_status = 'publish' ";
        $posts = $wpdb->get_results($request);
        $output = '';
        foreach ($posts as $post) {
            $post_name = ($post->post_name);
            $output[] = $post_name;
        }
        return $output;
    }

    /**
	 * Get KR Post Name
	 *
	 * @access public
	 * @return array
	 */


    function GetKRPostName()
    {
        global $wpdb;
        $request = "SELECT ID, post_name FROM {$wpdb->prefix}posts WHERE post_status = 'publish' AND post_type ='page'";
        $posts = $wpdb->get_results($request);
        $output = '';
        foreach ($posts as $post) {
            $post_name = ($post->post_name);
            $output[$post->ID] = $post_name;
        }
        return $output;
    }

    /**
	 * Get KR Post Name
	 *
	 * @access public
	 * @return array
	 */


    function GetKRPostName2()
    {
        global $wpdb;
        $request = "SELECT ID, post_name FROM {$wpdb->prefix}posts WHERE post_status = 'draft' OR post_status = 'publish' AND post_type ='page' ORDER BY ID DESC limit 50";
		// $parents = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE ID='{$value}' AND post_status='publish' AND post_status='draft'");
        $posts = $wpdb->get_results($request);
        $output = '';
        foreach ($posts as $post) {
            $post_name = ($post->post_name);
            $output[$post->ID] = $post_name;
        }
        return $output;
    }
	
    /**
	 * Get KR Post Name By Last ID
	 *
	 * @access public
	 * @return array
	 */
    
    function GetKRPostNameLastID($lastID)
    {
        global $wpdb;
        $request = "SELECT ID, post_name FROM {$wpdb->prefix}posts WHERE post_status = 'publish' AND post_type ='page' AND ID < $lastID ORDER BY ID DESC limit 25";
        $posts = $wpdb->get_results($request);
        
        if(!empty($posts)){
            foreach ($posts as $post) {
                $post_name = ($post->post_name);
                $output[$post->ID] = $post_name;
            }
        }else{
            $output = '';
        }
        return $output;
    }

    /**
	 * Get KR Post Name By ID
	 *
	 * @access public
	 * @return array
	 */

    function GetKRPostNameByID($id)
    {
        global $wpdb;

        $request = "SELECT ID, post_name FROM {$wpdb->prefix}posts WHERE ID='{$id}'";
        $posts = $wpdb->get_results($request);
        $output = '';
        foreach ($posts as $post) {
            $post_name = ($post->post_name);
            $output[$post->ID] = $post_name;
        }
        return $output;
    }

    /**
	 * Get KR Page Name By Last ID
	 *
	 * @access public
	 * @return array
	 */

    function GetKRPageNameByID($id)
    {
        global $wpdb;

        $request = "SELECT * FROM {$wpdb->prefix}ns_links WHERE id='{$id}'";
        $posts = $wpdb->get_results($request);
        $output = '';
        foreach ($posts as $post) {
            $output[] = array(
                'id'        =>  (string) $post->id,
                'PostId'    =>  (string) $post->PostId,
                'PageName'  =>  (string) $post->PageName,
                'Tags'      =>  (string) $post->Tags,
                'Data'      =>  (string) $post->Data,
                'PageNameOverride'      =>  (string) $post->PageNameOverride
            );
        }
        return $output;
    }

    /**
	 * Get KR Tags Links By Page ID
	 *
	 * @access public
	 * @return array
	 */

    function GetKRtagsLinksByPageId($id)
    {
        global $wpdb;

        $request = "SELECT * FROM {$wpdb->prefix}ns_links WHERE PostId ='{$id}' ORDER By id DESC limit 30";
        $posts = $wpdb->get_results($request);
        $output = '';
        foreach ($posts as $post) {
            $output[] = array(
                'id'        =>  (string) $post->id,
                'PostId'    =>  (string) $post->PostId,
                'PageName'  =>  (string) $post->PageName,
                'Tags'      =>  (string) $post->Tags,
                'Data'      =>  (string) $post->Data,
                'PageNameOverride'      =>  (string) $post->PageNameOverride
            );
        }
        return $output;
    }

    /**
	 * Get KR Tags Links By Page Id & Last Id
	 *
	 * @access public
	 * @return array
	 */

    
    function GetKRtagsLinksByPageIdLastId($id,$lastID){
         global $wpdb;

        $request = "SELECT * FROM {$wpdb->prefix}ns_links WHERE PostId ='{$id}' AND id < {$lastID} ORDER BY id DESC limit 25";
        $posts = $wpdb->get_results($request);
        $output = '';
        foreach ($posts as $post) {
            $output[] = array(
                'id'        =>  (string) $post->id,
                'PostId'    =>  (string) $post->PostId,
                'PageName'  =>  (string) $post->PageName,
                'Tags'      =>  (string) $post->Tags,
                'Data'      =>  (string) $post->Data,
                'PageNameOverride'      =>  (string) $post->PageNameOverride
            );
        }
        return $output;
    }

    /**
	 * Get KR All Tags
	 *
	 * @access public
	 * @return array
	 */


    function GetAllTags($id)
    {
        global $wpdb;
        $tags = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_links WHERE PostId = '{$id}'");

        foreach ($tags as $post) {
            $output[] = array(
                'id'        =>  (string) $post->id,
                'PostId'    =>  (string) $post->PostId,
                'PageName'  =>  (string) $post->PageName,
                'Tags'      =>  (string) $post->Tags,
                'Data'      =>  (string) $post->Data
            );
        }
        return $output;
    }

    
    /**
	 * Get Counter Tags Page
	 *
	 * @access public
	 * @return array
	 */

    function CounterTagsPage(){
        global $wpdb;

        $query = $wpdb->get_results("SELECT COUNT(*) AS Tags FROM {$wpdb->prefix}ns_links");
        
		$response['status'] = TRUE; 
		$response['limits'] = 1000;
        return $response;
    }
    
    /**
	 * Get Counter Tags Default Page
	 *
	 * @access public
	 * @return array
	 */
    
    function CounterTagsDefaultPage(){
       
        global $wpdb;
		global $post;
		$status = md5("active");
        $query = $wpdb->get_results("SELECT COUNT(*) AS Tags FROM {$wpdb->prefix}ns_tags WHERE status='{$status}'");
       
		$response['status'] = TRUE;
        
        return $response;
    }
    
	    /**
	 *Auto Clear Tags 
	 *
	 * @access public
	 * 
	 */

    function auto_removal_counter_tags()
    {
        global $wpdb;
        
        $tags = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_tags WHERE title='' AND keyword='' AND description='' AND tags=''");
        
        if( ! empty( $tags ) ) {
        	foreach( $tags as $t  ) {
        		//echo "DELETE FROM {$wpdb->prefix}ns_tags WHERE id ='".$t->id."'";
        		//exit;
				   $wpdb->query( 
						$wpdb->prepare( 
							"DELETE FROM {$wpdb->prefix}ns_tags WHERE linkid='%d'", $t->linkid
							)
					);

        	
        	}
        }
                    
    
    }
    
		    /**
	 *Check Defualt Pages Tags
	 *
	 * @access public
	 * Return Boolean
	 */
    function is_page_default( $pid )
    {
    	global $wpdb;
    		
    	 $pid     = esc_sql( $pid );	
    		
    	$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_tags WHERE linkid='{$pid}'");
    	
    	if( ! empty ( $result ) ) {
    		return true;
    	} 
    	
    	return false;
    	
    }
	
	
		
    /**
	 * Get KR Post Name of page publish and draft 
	 *
	 * @access public
	 * @return array
	 */


    function get_pagination($limits=1000,$max=1000,$post_status = "")
    {
        global $wpdb;
		if($post_status != "LandingPage")
		{
		  $request = "SELECT ID, post_name FROM {$wpdb->prefix}posts WHERE post_status = 'draft' OR post_status = 'publish' AND post_type ='page' ORDER BY ID DESC limit ".$limits.",".$max."";
		}
		else
		{
		  $id_active = $this->GetTags_active_id();
		  $request = "SELECT * FROM {$wpdb->prefix}posts WHERE FIND_IN_SET(`id`, '".$id_active."') ORDER BY ID DESC limit ".$limits.",".$max."";
		}
		// $parents = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE ID='{$value}' AND post_status='publish' AND post_status='draft'");
        $posts = $wpdb->get_results($request);
        $output = array();
        foreach ($posts as $post) {
            $post_name = ($post->post_name);
            $output[$post->ID] = $post_name;
        }
        return $output;
    }
	
	 /**
	 * Get KR set page 
	 *
	 * @access public
	 * @return array
	 */
	function is_page_default_active( $pid )
    {
    	global $wpdb;
    		
    	 $pid     = esc_sql( $pid );	
    		
    	$result = $wpdb->get_results("SELECT status FROM {$wpdb->prefix}ns_tags WHERE linkid='{$pid}'");
    	
    	return $result;
    	
    }
	
	
	 /**
	 * Get KR Count page active
	 *
	 * @access public
	 * @return array
	 */
	function count_Pagedefault_active(){
        
	global $wpdb;
	$status = md5("active");
	$query = $wpdb->get_results("SELECT COUNT(*) AS Tags FROM {$wpdb->prefix}ns_tags WHERE status='{$status}'");
	return $query ;
	}
	
	 /**
	 * Get KR status page
	 *
	 * @access public
	 * @return array
	 */
	 public function GetTags_active_inactive($id)
    {
    global $post;
    global $wpdb;

    $id = esc_sql( $id );
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_tags WHERE linkid='{$id}'");
	return $result;
    }
	
	/**
	 * Get KR page ID
	 *
	 * @access public
	 * @return array
	 */
	public function GetTags_active_id()
    {
    global $post;
    global $wpdb;
	$status = md5("active");
    $result = $wpdb->get_results("SELECT linkid FROM {$wpdb->prefix}ns_tags WHERE status='{$status}'");
	$ID_string = "";
	foreach($result as $key)
	{
		//echo $key->linkid;
		$ID_string .= $key->linkid.",";
	}
	
	return $ID_string ;
    }
	
}

?>
