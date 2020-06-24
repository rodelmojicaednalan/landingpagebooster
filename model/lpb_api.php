<?php

/**
 * Class for handling API actions as well as security and licensing
 * @author Netseek Pty Ltd
 */
class LandingPageBooster_sl_api
{
	const API_KEY_PARAM = 'lpb_sl_key';
    const LPB_KEY_STATUS = 'lpb_sl_key_status';
    const LPB_ELIGIBILITY_STATUS = 'lpb_sl_eligibility_status';
	const SECRET_KEY = "58f862dcac7da6.74677125";
	const LPB_SL_V = '2.6.5';
	const KEY_INSTALLED_URL = "installation_url";
	const KEY_STATUS = "status";
	const URL = 'lpb2.6bin.com';
	const CHECK_STATUS = "active";
	const NULL_STATUS = null;
    protected static $instance;
	
    protected $customer = array();

    public static function getInstance()
    {
        if (null === self::$instance) 
		{
            self::$instance = new self;
        }

        return self::$instance;
    }
    private function __construct()
    {
       // echo self::getPluginVersion();
    }
	public function getPluginVersion()
    {
        return get_plugin_data( KR_PlUGIN_PATH().'\app.php' )['Version'];
    }
	public function getApiKey()
    {
        return get_transient(self::API_KEY_PARAM);
    }
	public function getInstallationUrl()
    {
		return get_site_url();
    }
	public function getStatus()
    {
		return self::get_data_transient(self::KEY_STATUS) == CHECK_STATUS ? '':1;
    }
	public function value_status($status)
    {
		return $status == "active" ? 1:'' ;
    }
    public function setApiKey($key)
    {
		set_transient( self::API_KEY_PARAM, $key);
    }
	
	public function eligible()
	{
		$data = null;
		$api_params = array
					(
						'slm_action' => 'slm_check' ,
						'secret_key' => self::SECRET_KEY,
						'license_key' => get_option( '_license_key' ),
					);
		$query = esc_url_raw(add_query_arg($api_params,  "https://lpb2.6bin.com"));
		$response = json_decode(wp_remote_retrieve_body(wp_remote_get($query, array('timeout' => 20, 'sslverify' => false))));
		if (is_wp_error($response)) 
		{
            $data = '';
        }
		else if ($response->result == "error") 
		{
			 if (is_admin()) 
			 {
				 $response->status = self::NULL_STATUS;
				 $data = serialize((array)$response);
			 }
		}
		else
		{
			 
			$data = serialize(self::changes_object($response));
		}
		
		return $data;
	}
	
	function get_data_transient($lpb_key)
	{
		$data = unserialize(get_transient( lpb_transient_name()));
		$return = '';
		if(!is_wp_error($data[$lpb_key])){
			$return = $data[$lpb_key];
		}
		return $return;
	}
	public function changes_object($arg)
	{
		
		$return = array();
        $return['status'] = $arg->status;
		
        foreach( $arg->registered_domains as $value )
		{
                //Check if current domain, then get current domain and license key
				if( $value->registered_domain == get_site_url() ){
					//domain
					$return['installation_url'] = $value->registered_domain;
					//license
					$return['license_key'] = $value->lic_key; 
					$return['eligibility'] = true; 
                break;
			  } 
        }
        return $return;
	}

}
function lpb_sl_parse()
{
	return LandingPageBooster_sl_api::getInstance()->eligible();
}
function lpb_sl_save_key($key)
{
   LandingPageBooster_sl_api::getInstance()->setApiKey($key);
   delete_transient(LandingPageBooster_sl_api::getInstance()->API_KEY_STATUS);
}
function lpb_sl_get_key()
{
    return LandingPageBooster_sl_api::getInstance()->getApiKey();
}
function lpb_sl_get_version()
{
    return LandingPageBooster_sl_api::getInstance()->getPluginVersion();
}
function lpb_sl_get_url()
{
    return LandingPageBooster_sl_api::URL;
}
function lpb_transient_name()
{
	
    return 'lpb_sl_key_status'.'_'. lpb_sl_get_version().'_'.lpb_sl_get_url();
}
function get_lpb_key_status()
{
   return LandingPageBooster_sl_api::LPB_KEY_STATUS;
}
function lpb_get_url()
{
    return LandingPageBooster_sl_api::getInstance()->getInstallationUrl();
}
function lpb_get_ping()
{
    return LandingPageBooster_sl_api::getInstance()->getStatus();
}
function change_value_status($status)
{
	return LandingPageBooster_sl_api::getInstance()->value_status($status);
}
	?>