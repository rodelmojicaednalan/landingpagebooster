<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * LandingPageBooster KwReplacerCore
 *
 * 
 Core API Method
 *
 * @class 		KwReplacerCore
 * @version		2.5.8
 * @package		LandingPageBooster/Classes
 * @category	Class
 * @author 		Netseek
 */   

class KwReplacerCore
{
	/**
	 * Get Re-Write Rules  
	 *
	 * @access public
	 * @return void
	 */
    public function GetKrReWriteRules()
    {
        global $wp_rewrite;
        return $wp_rewrite->rewrite_rules();
    }

	/**
	 * Set Re-Write Rules  
	 *
	 * @access public
	 * @return void
	 */


    public function SetKrFlushRules()
    {
        global $wpdb;
        global $sql;
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

	/**
	 * Get WP Query Variables 
	 *
	 * @access public
	 * @return array
	 */

    public function GetKrQueryVar($vars)
    {
        array_push( $vars, 'krtags' );
        return $vars;
    }

    /**
     * Set Tags For Title Pages
	 *	
     * @access public
     * @return array
     */

    public function SetKrPageTitle( $title )
    {
        global $wp_query;
        global $sql;
		
        $dbTitle = $sql->GetNstags();
        $sep = '';
        $data 				= SL_M()->get_license_info();

		//echo ! empty( $data[ 'deactivate_checkbox' ]);
		if( $data[ 'deactivate_checkbox' ] == "off"){
			 return $title;
		}
        if( $dbTitle['respCode']){
			$key_linkid= $dbTitle['respMsg']['linkid'];
		$page_status=$sql->is_page_default_active( $key_linkid );
		$status_in = md5("inactive");
		
            $pageTitle  = $dbTitle['respMsg']['title'];
            $krURLtags  = ( ! empty( $wp_query->query_vars["krtags"] ) ? $wp_query->query_vars["krtags"] : '' ); 
		if($page_status[0]->status != $status_in )
		{
		  if(!empty ($krURLtags) ){ 
		 
                $data     = $sql->GetDataTags($krURLtags,  $this->get_top_parent_page_id());
                if(!empty ($data)){
                    $params   = $data[0]['Data'];
                    $response = $this->TagsStorage($params);
                }else{
                    $response = $this->TagsStorage(str_replace('/', '::', $krURLtags));
                }

            }else{
                $data = array();
                $response = $this->TagsStorage($data);
				
            }

            $license = $this->InitLicense();
            if($license['code']){
                $pageTitle  = str_replace($response['newTag'], $response['newRep'], $pageTitle,$count) ;
				$replaceq   = ( ! empty( $replaceq ) ? $replaceq : '' );
                $title      = ucwords(str_replace($replaceq,' ', $pageTitle. ' '.$sep.' '));
            }
		}
        }

        return $title;
    }

    /**
     * Set Tags For Meta Keyword
	 *
     * @access public
     * @return string
     */

    public function SetKrKeyword()
    {
        global $wp_query;
        global $sql;

        $dbKeyword = $sql->GetNstags();
		
		$data 				= SL_M()->get_license_info();

		//echo ! empty( $data[ 'deactivate_checkbox' ]);
		if( $data[ 'deactivate_checkbox' ] == "off"){
			 return null;
		}
		
        if($dbKeyword['respCode']){
		$key_linkid= $dbKeyword['respMsg']['linkid'];
		$page_status=$sql->is_page_default_active( $key_linkid );
		$status_in = md5("inactive");
		if($page_status[0]->status != $status_in )
		{
            $pageKeyword  = $dbKeyword['respMsg']['keyword'];
            $krURLtags  = ( ! empty( $wp_query->query_vars["krtags"]) ? $wp_query->query_vars["krtags"] : '' ) ;
            if(!empty ($krURLtags)){
                $data     = $sql->GetDataTags($krURLtags,  $this->get_top_parent_page_id());
                if(!empty ($data)){
                    $params   = $data[0]['Data'];
                    $response = $this->TagsStorage($params);
                }else{
                    $response = $this->TagsStorage(str_replace('/', '::', $krURLtags));
                }
            }else{
                $data = array();
                $response = $this->TagsStorage($data);
            }
            
             $license = $this->InitLicense();
             if($license['code']){
                 $pageKeyword  = str_replace($response['newTag'], $response['newRep'], $pageKeyword,$count) ;
                 return $Keyword      = '<meta name="keywords" content="'.$pageKeyword.'" />';  
             }
		}
        }
        
        //return $Keyword;
    }

    /**
     * Set Tags For Meta Description
     * 
     * @access public
     * @return string
     */

    public function SetKrDescription()
    {
        global $wp_query;
        global $sql;

        $dbDescription = $sql->GetNstags();

		$data 				= SL_M()->get_license_info();

		//echo ! empty( $data[ 'deactivate_checkbox' ]);
		if( $data[ 'deactivate_checkbox' ] == "off"){
			 return null;
		}
		
        if($dbDescription['respCode']){
		$key_linkid= $dbDescription['respMsg']['linkid'];
		$status_in = md5("inactive");
		$page_status=$sql->is_page_default_active( $key_linkid );
		$key_linkid= $dbDescription['respMsg']['linkid'];
		$page_status=$sql->is_page_default_active( $key_linkid );
			if($page_status[0]->status != $status_in )
			{
				$pageDescription  = $dbDescription['respMsg']['description'];
				$krURLtags  = ( ! empty( $wp_query->query_vars["krtags"]) ? $wp_query->query_vars["krtags"] : '' ) ;
				if(!empty ($krURLtags)){
					$data     = $sql->GetDataTags($krURLtags, $this->get_top_parent_page_id());
					if(!empty ($data)){
						$params   = $data[0]['Data'];
						$response = $this->TagsStorage($params);
					}else{
						$response = $this->TagsStorage(str_replace('/', '::', $krURLtags));
					}

				}else{
					$data = array();
					$response = $this->TagsStorage($data);
				}
				
				$license = $this->InitLicense();
				 if($license['code']){
					 $pageDescription  = str_replace($response['newTag'], $response['newRep'], $pageDescription,$count) ;
					 return $description      = '<meta name="description" content="'.$pageDescription.'" /> ';
				 }
			}
		}
        //return $description;
    }

    /**
     * Set Tags For Content
     * 
     * @access public
     * @return sting
     */

    public function SetKrContent( $text )
    {
        global $wp_query;
        global $sql;
        global $post;
        
        $dbTags = $sql->GetNstags();
		// session_start(); // initialize session
		// session_destroy(); // destroy session
		// setcookie("PHPSESSID","",time()-3600,"/");
		$data 				= SL_M()->get_license_info();

		//echo ! empty( $data[ 'deactivate_checkbox' ]);
		if( $data[ 'deactivate_checkbox' ] == "off"){
			 return $text;
		}
        if($dbTags['respCode']){
			$key_linkid= $dbTags['respMsg']['linkid'];
			$page_status=$sql->is_page_default_active( $key_linkid );
			$status = md5("active");
			if($page_status[0]->status == $status ){
            $pageTags  = $dbTags['respMsg']['tags'];
			
			if( ! empty ( $wp_query->query_vars["krtags"] ) ) {
					$krURLtags  = $wp_query->query_vars["krtags"];
			}
			
             
            if(!empty ($krURLtags)){
                $data     = $sql->GetDataTags($krURLtags,  $this->get_top_parent_page_id());
                
                if(!empty ($data)){
                    $params   = $data[0]['Data'];
                    $response = $this->TagsStorage($params);
                }else{
                    $response = $this->TagsStorage(str_replace('/', '::', $krURLtags));
                }
            }else{
                $data = array();
                $response = $this->TagsStorage($data);
				
				
				
            } 
            
			//echo '<pre>'; print_r($wp_query->query_vars); echo '</pre>';
            
            $license = $this->InitLicense();
             if($license['code']){
                 $text  = str_replace($response['newTag'], $response['newRep'], $text ,$count) ;
             }
			 }
        }
        return $text;
    }
    
    /**
     * Set Tags For Nav Menu
     * 
     * @access public
     * @return sting
     */
    
    public function SetKrTagsNavMenu( $text ){

        global $wp_query;
        global $sql;
        global $wpdb;
        global $post;


        $dbTags = $sql->GetNstags();
		$status = md5("active");
        if($dbTags['respCode']){
		$key_linkid= $dbTags['respMsg']['linkid'];
		$page_status=$sql->is_page_default_active( $key_linkid );
		if($page_status[0]->status == $status)
		{
			$pageTags  = $dbTags['respMsg']['tags'];
			$krURLtags  = $wp_query->query_vars["krtags"];

			if(!empty ($krURLtags)){
				$data     = $sql->GetDataTags($krURLtags,  $this->get_top_parent_page_id());
				$params   = $data[0]['Data'];
				$response = $this->TagsStorage($params);
			}else{
				$data = array();
				$response = $this->TagsStorage($data);
			}
			
			$license = $this->InitLicense();
				 if($license['code']){
					 $text  = str_replace($response['newTag'], $response['newRep'], $text ,$count) ;
				 }

			}
		}
        return $text;
    }


	/**
     * Set Tags Post Title
     * 
     * @access public
     * @return sting
     */

    public function SetKrPostTiltle( $text )
    {
        global $wp_query;
        global $sql;
        global $wpdb;
        global $post;

        $dbTags = $sql->GetNstags();
		$status = md5("active");
        if($dbTags['respCode']){
		$key_linkid= $dbTags['respMsg']['linkid'];
		$page_status=$sql->is_page_default_active( $key_linkid );
		if($page_status[0]->status == $status)
		{
			$pageTags  = $dbTags['respMsg']['tags'];
			$krURLtags  = $wp_query->query_vars["krtags"];

			if(!empty ($krURLtags)){
				$data     = $sql->GetDataTags($krURLtags, $this->get_top_parent_page_id());
				$params   = $data[0]['Data'];
				$response = $this->TagsStorage($params);
			}else{
				$data = array();
				$response = $this->TagsStorage($data);
			}
			
			$license = $this->InitLicense();
				 if($license['code']){
					 $text  = str_replace($response['newTag'], $response['newRep'], $text ,$count) ;
				 }

			}
		}
        return $text;
    }

    /**
     * Tags Storage Engine
     * 
     * @access public
     * @return array
     */

    public function TagsStorage($data)
    {
       global $utilities;
       global $wp_query;
       global $sql;

       $thePostID = $wp_query->post->ID;
       $linkTags  = $sql->GetTags($thePostID);
 
       if(!empty ($data)){
		
           /* Get Krtags 1st Level ( Defualt tags stored into db )*/
           if($linkTags['respCode']){
               $expTags = explode('::', $linkTags['respMsg']['tags']);
               if(!empty ($expTags)){
                    $x = 1;
                    foreach( $expTags as $v ){
                           $replaceq   = array('%20','-');
                           $defaultTags['TAG'.$x++] = trim(str_replace($replaceq,' ', strtoupper($v)));
                    }
                    
                    
                    $x2 = 1;
                    foreach( $expTags as $v2 ){
                           $replaceq   = array('%20','-');
                           $defaultTags['tag'.$x2++] = trim(str_replace($replaceq,' ', strtolower($v2)));
                    }
                    
                    $x3 = 1;
                    foreach( $expTags as $v3 ){
                           $replaceq   = array('%20','-');
                           $defaultTags['Tag'.$x3++] = trim(str_replace($replaceq,' ', ucwords($v3)));
                    }

                   // original text
                   $x4 = 1;
                   foreach( $expTags as $v4 ){
                       $replaceq   = array('%20','-');
                       $defaultTags['otag'.$x4++] = trim(str_replace($replaceq,' ', $v4));
                   }

               }
           }else{
               $defaultTags['TAG1'] = '';
           }

           /* Get Krtags 3rd Level ( Dynamic URL Re-Write Tags into db )*/

           $data   = explode('::',$data);

           if(!empty ($data)){
               $y = 1;
                foreach( $data as $v ){
                       //$dynamicTags['TAG'.$y++] = trim($v);
                       $replaceq   = array('%20','-');
                       $dynamicTags['TAG'.$y++] = trim(str_replace($replaceq,' ', strtoupper($v)));
                }
                
                $y2 = 1;
                foreach( $data as $v2 ){
                       //$dynamicTags['TAG'.$y++] = trim($v);
                       $replaceq   = array('%20','-');
                       $dynamicTags['tag'.$y2++] = trim(str_replace($replaceq,' ', strtolower($v2)));
                }
                
                $y3 = 1;
                foreach( $data as $v3 ){
                       //$dynamicTags['TAG'.$y++] = trim($v);
                       $replaceq   = array('%20','-');
                       $dynamicTags['Tag'.$y3++] = trim(str_replace($replaceq,' ', ucwords($v3)));
                }

               // original text
               $y4 = 1;
               foreach( $data as $v4 ){
                   //$dynamicTags['TAG'.$y++] = trim($v);
                   $replaceq   = array('%20','-');
                   $dynamicTags['otag'.$y4++] = trim(str_replace($replaceq,' ', $v4));
               }
                
           }

           
           foreach ($dynamicTags as $key => $val){
               $tag = "##".strtoupper($key)."##";
               $rep = $val;
               
               if(array_key_exists(strtoupper($key), $defaultTags)){
                      $newTag[] = $tag;
                      $newRep[] = $utilities->CleanParams(strtoupper($rep));
                      unset($defaultTags[strtoupper($key)]);
                }else{
                     $newTag[] = $tag;
                     $newRep[] = $utilities->CleanParams($rep);
                }
                
                
                $tag = "##".  strtolower($key)."##"; 
                
                if(array_key_exists(strtolower($key), $defaultTags)){
                        $newTag[] = $tag;
                        $newRep[] = $utilities->CleanParams(strtolower($rep));
                        unset($defaultTags[strtolower($key)]);
                }else{
                        $newTag[] = $tag;
                        $newRep[] = $utilities->CleanParams($rep);
                }


                $tag = "##". ucwords($key)."##"; 
                
                if(array_key_exists(ucwords($key), $defaultTags)){
                        $newTag[] = $tag;
                        $newRep[] = $utilities->CleanParams(ucwords($rep));
                        unset($defaultTags[ucwords($key)]);
                }else{
                        $newTag[] = $tag;
                        $newRep[] = $utilities->CleanParams($rep);
                }

               // original text
               $tag = "##". $key."##";

               if(array_key_exists($key, $defaultTags)){
                   $newTag[] = $tag;
                   $newRep[] = $utilities->CleanParams($rep);
                   unset($defaultTags[$key]);
               }else{
                   $newTag[] = $tag;
                   $newRep[] = $utilities->CleanParams($rep);
               }
                

            }
            
            

           /*Displasy the remaining unset Default Tags*/

            if(!empty ($defaultTags)){
                foreach ($defaultTags as $k => $val){
                   $newTag[] = "##".strtoupper($k)."##";
                   $newRep[] = addslashes(htmlspecialchars(strtoupper($val)));
                }
                
                foreach ($defaultTags as $k => $val){
                   $newTag[] = "##".strtolower($k)."##";
                   $newRep[] = addslashes(htmlspecialchars(strtolower($val)));
                }
                
                foreach ($defaultTags as $k => $val){
                   $newTag[] = "##".ucwords($k)."##";
                   $newRep[] = addslashes(htmlspecialchars(ucwords($val)));
                }
                // original text
                foreach ($defaultTags as $k => $val){
                    $newTag[] = "##".$k."##";
                    $newRep[] = addslashes(htmlspecialchars($val));
                }
                
            }
            
            
        
            
       }else{ 
          
           /* Get Krtags 1st Level ( Defualt tags stored into db )*/
 
			if( ! empty( $linkTags['respMsg']['tags'] )) {
				 $expTags = explode('::', $linkTags['respMsg']['tags']);
				 
			} else {  
				if(isset($linkTags['respMsg']['tags']))
				{
					
					$linkTags['respMsg']['tags'] = array();
					//$expTags = explode('::', $linkTags['respMsg']['tags']);
					$expTags = '';
				}
				
			}

           
           if(!empty ($expTags)){
               
                $x = 1;
                    foreach( $expTags as $v ){
                           $replaceq   = array('%20','-');
                           $defaultTags['TAG'.$x++] = trim(str_replace($replaceq,' ', strtoupper($v)));
                    }
                    
                    
                    $x2 = 1;
                    foreach( $expTags as $v2 ){
                           $replaceq   = array('%20','-');
                           $defaultTags['tag'.$x2++] = trim(str_replace($replaceq,' ', strtolower($v2)));
                    }
                    
                    $x3 = 1;
                    foreach( $expTags as $v3 ){
                           $replaceq   = array('%20','-');
                           $defaultTags['Tag'.$x3++] = trim(str_replace($replaceq,' ', ucwords($v3)));
                    }

                   // original text
                   $x4 = 1;
                   foreach( $expTags as $v4 ){
                       $replaceq   = array('%20','-');
                       $defaultTags['otag'.$x4++] = trim(str_replace($replaceq,' ', $v4));
                   }

           }else{
               $defaultTags['TAG1'] = '';
           }

           /* Get Krtags 2nd Level (URL Paramter Manual Set)*/
           /* Non-Secure Request / Security Breach */
			
           $qry = array_change_key_case($_GET);
		  
		   /* checking tags keys*/
           unset($t_sess);
		   if(!empty($qry)) 
		   { 
				
				if(!is_null( session_id()))
				{ 
					$t_sess[] = $qry;
					// print_r($_GET); 
					// print_r($_GET);
				}
		   }      
           foreach ($qry as $key => $val){
            $tag = "##".strtoupper($key)."##";
            $rep = array_key_exists($key, $qry)?str_replace('-',' ',$qry[$key]):$val;
            
                if(array_key_exists(strtoupper($key), $defaultTags)){
                      $newTag[] = $tag;
                      $newRep[] = $utilities->CleanParams(strtoupper($rep));
                      unset($defaultTags[strtoupper($key)]);
                }else{
                     $newTag[] = $tag;
                     $newRep[] = $utilities->CleanParams($rep);
                }
                
                $tag = "##".  strtolower($key)."##"; 
                if(array_key_exists(strtolower($key), $defaultTags)){
                      $newTag[] = $tag;
                      $newRep[] = $utilities->CleanParams(strtolower($rep));
                      unset($defaultTags[strtolower($key)]);
                }else{
                     $newTag[] = $tag;
                     $newRep[] = $utilities->CleanParams($rep);
                }
                
                
                $tag = "##". ucwords($key)."##"; 
                if(array_key_exists(ucwords($key), $defaultTags)){
                      $newTag[] = $tag;
                      $newRep[] = $utilities->CleanParams(ucwords($rep));
                      unset($defaultTags[ucwords($key)]);
                }else{
                     $newTag[] = $tag;
                     $newRep[] = $utilities->CleanParams($rep);
                }

               // original text
               $tag = "##". $key."##";
               if(array_key_exists($key, $defaultTags)){
                   $newTag[] = $tag;
                   $newRep[] = $utilities->CleanParams($rep);
                   unset($defaultTags[$key]);
               }else{
                   $newTag[] = $tag;
                   $newRep[] = $utilities->CleanParams($rep);
               }
            }

            /*Display the remaining unset Default Tags*/
            
            if(!empty ($defaultTags)){
				 
                foreach ($defaultTags as $k => $val){
                   $newTag[] = "##".strtoupper($k)."##";
                   $newRep[] = addslashes(htmlspecialchars(strtoupper($val)));
                }
                
                foreach ($defaultTags as $k => $val){
                   $newTag[] = "##".strtolower($k)."##";
                   $newRep[] = addslashes(htmlspecialchars(strtolower($val)));
                }
                
                foreach ($defaultTags as $k => $val){
                   $newTag[] = "##".ucwords($k)."##";
                   $newRep[] = addslashes(htmlspecialchars(ucwords($val)));
                }

                foreach ($defaultTags as $k => $val){
                    $newTag[] = "##".$k."##";
                    $newRep[] = addslashes(htmlspecialchars($val));
                }
            }
       }
        $the_id = get_the_ID();
		
		$tagssid= array_key_exists('newTag' , $_SESSION)?$_SESSION[ 'newTag' ][$the_id]:null;//get session tags
		
		//remove session if tags changes by PHPSESSID
		if(isset($tagssid))
		{
			foreach ($tagssid as $key => $val) {
			 if (preg_match('(tag|TAG|Tag|OTAG|otag|Otag)', $val))
			 {
			
			 }
			 else
			 {
				 session_start(); // initialize session
				 session_destroy(); // destroy session
				 setcookie("PHPSESSID","",time()-3600,"/");
			 }
		}	
		}
	
		// if session exist	change tag by session
	    if( ! empty( $t_sess ) ) {
			
			unset($_SESSION[ 'newTag' ][$the_id]);
			unset($_SESSION[ 'newRep' ][$the_id]);

			
			$_SESSION[ 'newTag' ][$the_id] = $newTag;
			$_SESSION[ 'newRep' ][$the_id] = $newRep;
			
			$response['newTag'] = $_SESSION[ 'newTag' ][$the_id];
            $response['newRep'] = $_SESSION[ 'newRep' ][$the_id];
			
		} else {
		
			if( ! empty( $_SESSION[ 'newTag' ][$the_id] ) && ! empty( $_SESSION[ 'newRep' ][$the_id] ) ){
				
				$response['newTag'] = $_SESSION[ 'newTag' ][$the_id];
                $response['newRep'] = $_SESSION[ 'newRep' ][$the_id];
				
				
					
				
			} else{
				
				$response['newTag'] = $newTag;
				$response['newRep'] = $newRep; 
				
			}
		}
       
        return $response;
    }

    /**
     * Keywords & Tags Generator
     * 
     * @access public
     * @return array
     */

    public  function KRTagsWpGenerator()
    {
        global $utilities;

        $tag1   =  trim(urlencode($_POST['tag1']));
        $tag2   =  trim(urlencode($_POST['tag2']));
        $tag3   =  trim(urlencode($_POST['tag3']));

         if($utilities->isKwrValidURL($_POST['url'])){
            $validate = $this->KwrValidateForm($tag1);
            if($validate['code']){
                $tags1   = $this->KwrExplodeByDash($tag1);
                $tags2   = $this->KwrExplodeByDash($tag2);
                $tags3   = $this->KwrExplodeByDash($tag3);


                    if(!empty ($tags1)){
                        foreach ($tags1 as $key => $value)
                        {
                             $arr['L'.$key][] = $value;
                        }
                    }

                    if(!empty ($tags2)){
                        foreach ($tags2 as $key => $value)
                        {
                             $arr['L'.$key][] = $value;
                        }
                    }

                    if(!empty ($tags3)){
                        foreach ($tags3 as $key => $value)
                        {
                            $arr['L'.$key][] = $value;
                        }
                    }


                    if(!empty ($arr)){
                       foreach ($arr as $key => $value)
                        {
                           $x = 0;
                            foreach($value as $key2 => $val2){

                              $x = $x+1;
                                if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1){
                                       $val2 = urlencode(stripslashes(trim($val2)));
                                } else {
                                        $val2 = urlencode(trim($val2));
                                }

                                if(!empty ($val2)){
                                    $req[$key] .= "-".trim($val2);
                                    $req2[$key] .= "::".trim($val2);
                                    $req3[$key][] = $val2;
                                }

                            }
                        }


                    }else{
                        $response['code'] = FALSE;
                        $response['msg']  = 'Invalid Url Builder!';
                    }
                    unset ($_SESSION['AUTH_TAGS']);

                    if(!empty ($req)){
                        $_SESSION['AUTH_TAGS']['DATA'] = $req3;
                        foreach ($req as $key3 => $val3){
                            if(strlen(str_replace("%2B", "-", substr($val3,1))."") > '200'){
                              $cntTags = strlen(str_replace("%2B", "-", substr($val3,1))."");
                              $result .= '<div class="updated settings-error">This list is limited to 200 rows!</div>';
                            }else{  
                            
                            $kr_query_url = str_replace("%2B", "-", substr($val3,1));
                            $_SESSION['AUTH_TAGS']['TAGS'][$key3] = str_replace("%20", "", $kr_query_url )."";
                            //$_SESSION['AUTH_TAGS']['TAGS'][$key3] = str_replace("%2B", "-", substr($val3,1))."";

                            $result .= '<div class="updated settings-error">'.rtrim(addslashes(htmlspecialchars($_POST['url'])), '/') .'/'. str_replace("%2B", "-", substr($val3,1)) . '<br>';
                            $result .= '<small>( Tags Passed = '.str_replace("%2B", " ",substr(substr($req2[$key3],1),1)).' )</small></div><br>';
							$totalCount[] = str_replace("%2B", " ",substr(substr($req2[$key3],1),1)); 	
                            }
                            
                        }

                        $response['code'] = TRUE;
                        $response['msg']  = $result;
                        $response['kwrtags']  = $tagsSave;
                        $response['totalCount']  = $totalCount;

                    }

            }else{
                $response['code'] = $validate['code'];
                $response['msg']  = $validate['msg'];
            }

        }else{
                $response['code'] = FALSE;
                $response['msg']  = 'Invalid Url Format!';
            }

        return $response;
    }

    /**
     * Keywords & Tags Explode by Dash
     * 
     * @access public
     * @return array
     */

    public  function KwrExplodeByDash($tags){
        
       //echo '<pre>'; print_r($tags); echo '</pre>'; 
       $tagsParticle = explode("%0D%0A", $tags);
       //$tagsParticle = explode("%0D%0A", str_replace('+', '', $tags));
        return $tagsParticle;
    }

    /**
     * Keywords & Tags Validation Form
     * 
     * @access public
     * @return array
     */



    public  function KwrValidateForm($tags)
    {

        $response['code'] = TRUE;
        $response['msg']  = '';

        if(empty ($tags)){
            $response['code'] = FALSE;
            $response['msg']  = 'Invalid Keyword for TAG!';
        }
        
        
        
        return $response;
    }
    
    /**
     * Save Keywords & Tags
     * 
     * @access public
     * @return array
     */

    public function WpSaveKrTags()
    {
        global $wpdb;
        global $sql;
        unset($_SESSION['AUTH']['FLASHMSG']);
        $id = $_REQUEST['pageid'];
        $page   = $sql->GetKRPostNameByID($id);
		
        if(!empty ($id)){
            if(!empty ($_SESSION['AUTH_TAGS']['TAGS'])){
                $data = $_SESSION['AUTH_TAGS']['DATA'];
                foreach ($_SESSION['AUTH_TAGS']['TAGS'] as $key => $val ){
                    $result  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ns_links WHERE PageName='{$page[$id]}' AND Tags='{$val}'");
                    if(!$result){
                        $result = $wpdb->insert("{$wpdb->prefix}ns_links",
                                          array(
                                                 'PostId'      =>  $id,
                                                 'PageName'    => $page[$id] ,
                                                 'Tags'        => $val,
                                                 'Data'        => str_replace("%2B"," ",  implode('::', $data[$key]))
                                                ) );


                    }
                }


                $_SESSION['AUTH']['FLASHMSG'] = 'Success: Keyword Tags Successfully Saved.';
                wp_redirect( home_url() . '/wp-admin/admin.php?page=AddKrwUrl&section=pagestags&pageid='.$id );
                ob_clean();
                flush();
                exit;


            }else{

                $_SESSION['AUTH']['FLASHMSG'] = 'Error: Invalid Parameter Tag Data!';
                wp_redirect( home_url() . '/wp-admin/admin.php?page=AddKrwUrl');
                ob_clean();
                flush();
                exit;
            }

        }else{
            $_SESSION['AUTH']['FLASHMSG'] = 'Error: Invalid Tag Identifier Used';
            wp_redirect( home_url() . '/wp-admin/admin.php?page=AddKrwUrl');
            ob_clean();
            flush();
            exit;
        }
    }

    /**
     * Delete Keywords & Tags
     * 
     * @access public
     * @return void
     */


    public function WpDeleteKrTags()
    {
      global $wpdb;
      $pageid    = esc_sql($_REQUEST['pageid']);
      $id       = esc_sql($_REQUEST['id']);
      $tags     = $wpdb->query("DELETE FROM {$wpdb->prefix}ns_links WHERE id = '{$id}'");

      if($tags){
        $_SESSION['AUTH']['FLASHMSG'] = 'Tags # '.$id.' Record Deleted!';
            wp_redirect( home_url() . '/wp-admin/admin.php?page=AddKrwUrl&section=pagestags&pageid='.$pageid );
            exit;
      }else{
           $_SESSION['AUTH']['FLASHMSG'] = 'Tags # '.$id.' Unable to Delete!';
            wp_redirect( home_url() . '/wp-admin/admin.php?page=AddKrwUrl&section=pagestags&pageid='.$pageid );
            exit;
      }

    }

	/**
     * Init License Key and Validation
     * 
     * @access public
     * @return array
     */

    public function InitLicense()
    {
       /*  
        $app = KRLicenseManager::validate_app();
        
        if( $app ) {
			$response['code'] = true;
        } else {
        	$response['code'] = false;
        } */
        $response['code'] = true;
        return $response;
    }

	/**
     * Get Parent Page ID
     * 
     * @access public
     * @return array
     */

    
    public function get_top_parent_page_id() {

    global $post;

    // Check if page is a child page (any level)
    if ($post->ancestors) {

        //  Grab the ID of top-level page from the tree
        return end($post->ancestors);

    } else {

        // Page is the top level, so use  it's own id
        return $post->ID;

    }

}
/**
	 * Get Keyword Limits by License product id
	 *
	 * @access public
	 * @return object
	 */	
	
	public static function get_limitby_name($license) 
	{
		switch( $license ){
			case 'LandingPageBooster-Silver';
				$limits_license['pages'] = 3;
				$limits_license['links'] = 30; 
				break;
			case 'LandingPageBooster-Gold';
				$limits_license['pages']  = 9; 
				$limits_license['links'] = 60; 
				break;
			case 'LandingPageBooster-Platinum';
				$limits_license['pages'] =10000; 
				$limits_license['links'] = 60; 
				break;
			default:			
				$limits_license['pages'] = 0;
				$limits_license['links'] = 0; 
				break;
		}
		
	return $limits_license;
		
	}
    
}

?>
