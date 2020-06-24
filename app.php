<?php
/**
 * Plugin Name:			Landing Page Booster
 * Plugin URI:			http://www.landingpagebooster.com
 * Description:			Landing Page Booster is a plugin that performs dynamic keyword insertion into pages. It functions as a tool that aligns your keywords or set of keywords to be dynamically inserted within the pages of your website or within your landing pages. For CPC campaigns like AdWords, it does a great job of boosting visitor relevance and increases the conversion rate.
 * Author:				Netseek
 * Version:				3.2.2.4
 * Requires at least: 	3.3
 * Tested up to:		4.7.2
 * Author URI: 			Rodel Ednalan
 *
 * @package LandingPageBooster
 * @category Core
 * @author Netseek
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function KR_PlUGIN_URL() {
	return untrailingslashit( plugins_url( '/', __FILE__ ) );
}
function KR_PlUGIN_PATH() {
	return untrailingslashit( plugin_dir_path( __FILE__ ) );
}
define( 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
require KR_PlUGIN_PATH() . '/model/plugin-update-checker/plugin-update-checker.php';

/*
 * Plugin Updater.
 * 
 * @link  https://github.com/YahnisElsts/plugin-update-checker#github-integration
 */


/*
 * Check Update
 */

 

// If using a private repository, specify the access token.


if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}


if( file_exists ( KR_PlUGIN_PATH() . '/main.php' ) ) {
	
	include KR_PlUGIN_PATH() . '/main.php';
}



?>
