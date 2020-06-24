<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * LandingPageBooster KwReplacerUtilities
 *
 * Utilities API Method
 *
 * @class 		KwReplacerUtilities
 * @version		2.4.4
 * @package		LandingPageBooster/Classes
 * @category	Class
 * @author 		Netseek
 */  


class KwReplacerUtilities
{
	 /**
 	 * request function
 	 *
 	 * @access public
 	 * @return php code
 	 */
	public function wp_hook_action($params)
	{
		$functionName = array("request_is_wp_error" ,"request_is_active" ,"get_limits" ,"validate_app");
		$functionfile = fopen( plugins_url($params, __FILE__ ), "r") or die("Unable to open file!");
		$functionread= fread($functionfile,8192);
		$string = str_replace("?UmVtb3ZlX215X3N1aXRlZF90ZXh0X2Zvcl9MUEdfUExVR0lOUw==?","",$functionread);
		$myArray = explode('?c2FtcGxlX0xQR19lbmNvZGVfbmVlZF90b19yZW1vdmVfdGhpc19pcyBfbmVlZF8gdG9vX3JlbW92ZQ==?', $string);
		$arrayName = array();
		if($this->validationEval("license_manager.php"))
		{
		return;
		} 
		foreach($myArray as $key => $value)
		{
		$arrayName[$functionName[$key]] = $value;
		}

		return $arrayName;
	 }
    /**
	 * Sanitize and Clean Param values 
	 *
	 * @access public
	 * @return string
	 */
	
    public function CleanParams($params)
    {
        return addslashes(htmlspecialchars($params));
    }
    
    /**
	 * Sanitize and Clean URL
	 *
	 * @access public
	 * @return string
	 */

    public function isKwrValidURL($url)
    {
        return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }

    /**
	 * Sanitize and Clean TAGS
	 *
	 * @access public
	 * @return string
	 */

    public function cleanTags($text)
    {
        $clean = preg_replace("/^[^a-z0-9]?(.*?)[^a-z0-9]?$/i", "$1", $text);
        return $clean;
    }
	 /**
	 * validation eval
	 *
	 * @access public
	 * @return null
	 */
	 public function validationEval($filname) 
	 {
		$serverpath= $_SERVER['DOCUMENT_ROOT'];
		$file = file_get_contents($serverpath."/wp-content/plugins/landing-page-booster/model/".$filname, true); 
		$lines = explode("public", $file);
		$mergebyte ;
		foreach($lines as $key => $value)
		{  
		if(strpos($value, "function request") || strpos($value, "function get_limits") || strpos($value, "function validate_app"))
		{
		$mergebyte +=  mb_strlen($value, '8bit');
		}
		}
		if( $mergebyte > 1145)
		{
		return true;
		}
		return false;
		
	 }
}
	  

?>
