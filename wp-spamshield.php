<?php
/*
Plugin Name: WP-SpamShield
Plugin URI: http://www.redsandmarketing.com/plugins/wp-spamshield/
Description: An extremely robust and user-friendly anti-spam plugin that simply destroys comment spam. Enjoy a WordPress blog without spam! Includes a spam-blocking contact form feature too.
Author: Scott Allen
Version: 1.1.3.3
Author URI: http://www.redsandmarketing.com/
License: GPLv2
*/

/*  Copyright 2014    Scott Allen  (email : wpspamshield [at] redsandmarketing [dot] com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Begin the Plugin

/* Note to any other PHP developers reading this:
My use of the end curly braces "}" is a little funky in that I indent them, I know. IMO it's easier to debug. Just know that it's on purpose even though it's not standard. One of my programming quirks, and just how I roll. :)
*/

// Make sure plugin remains secure if called directly
if ( !function_exists( 'add_action' ) ) {
	if ( !headers_sent() ) {
		header('HTTP/1.1 403 Forbidden');
		}
	die('ERROR: This plugin requires WordPress and will not function if called directly.');
	}

define( 'WPSS_VERSION', '1.1.3.3' );
define( 'WPSS_REQUIRED_WP_VERSION', '3.0' );
define( 'WPSS_MAX_WP_VERSION', '4.0' );
if ( ! defined( 'WPSS_SITE_URL' ) ) {
	define( 'WPSS_SITE_URL', untrailingslashit( site_url() ) ); // http://example.com
	}
if ( ! defined( 'WPSS_PLUGINS_DIR_URL' ) ) {
	define( 'WPSS_PLUGINS_DIR_URL', untrailingslashit( plugins_url() ) ); // http://example.com/wp-content/plugins
	}
if ( ! defined( 'WPSS_CONTENT_DIR_URL' ) ) {
	define( 'WPSS_CONTENT_DIR_URL', untrailingslashit( content_url() ) ); // http://example.com/wp-content
	}
if ( ! defined( 'WPSS_ADMIN_URL' ) ) {
	define( 'WPSS_ADMIN_URL', untrailingslashit( admin_url() ) ); // http://example.com/wp-admin
	}
if ( ! defined( 'WPSS_PLUGIN_BASENAME' ) ) {
	define( 'WPSS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // wp-spamshield/wp-spamshield.php
	}
if ( ! defined( 'WPSS_PLUGIN_FILE_BASENAME' ) ) {
	define( 'WPSS_PLUGIN_FILE_BASENAME', trim( basename( __FILE__ ), '/' ) ); // wp-spamshield.php
	}
if ( ! defined( 'WPSS_PLUGIN_NAME' ) ) {
	define( 'WPSS_PLUGIN_NAME', trim( dirname( WPSS_PLUGIN_BASENAME ), '/' ) ); // wp-spamshield
	}
if ( ! defined( 'WPSS_PLUGIN_URL' ) ) {
	define( 'WPSS_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) ); // http://example.com/wp-content/plugins/wp-spamshield
	}
if ( ! defined( 'WPSS_PLUGIN_FILE_URL' ) ) {
	define( 'WPSS_PLUGIN_FILE_URL',  WPSS_PLUGIN_URL.'/'.WPSS_PLUGIN_FILE_BASENAME ); // http://example.com/wp-content/plugins/wp-spamshield/wp-spamshield.php
	}
if ( ! defined( 'WPSS_PLUGIN_COUNTER_URL' ) ) {
	define( 'WPSS_PLUGIN_COUNTER_URL', WPSS_PLUGIN_URL . '/counter' ); // http://example.com/wp-content/plugins/wp-spamshield/counter
	}
if ( ! defined( 'WPSS_PLUGIN_DATA_URL' ) ) {
	define( 'WPSS_PLUGIN_DATA_URL', WPSS_PLUGIN_URL . '/data' ); // http://example.com/wp-content/plugins/wp-spamshield/data
	}
if ( ! defined( 'WPSS_PLUGIN_IMG_URL' ) ) {
	define( 'WPSS_PLUGIN_IMG_URL', WPSS_PLUGIN_URL . '/img' ); // http://example.com/wp-content/plugins/wp-spamshield/img
	}
if ( ! defined( 'WPSS_PLUGIN_JS_URL' ) ) {
	define( 'WPSS_PLUGIN_JS_URL', WPSS_PLUGIN_URL . '/js' ); // http://example.com/wp-content/plugins/wp-spamshield/js
	}
if ( ! defined( 'WPSS_PLUGIN_PATH' ) ) {
	define( 'WPSS_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) ); // /public_html/wp-content/plugins/wp-spamshield
	}
if ( ! defined( 'WPSS_PLUGIN_FILE_PATH' ) ) {
	define( 'WPSS_PLUGIN_FILE_PATH', WPSS_PLUGIN_PATH.'/'.WPSS_PLUGIN_FILE_BASENAME ); // /public_html/wp-content/plugins/wp-spamshield/wp-spamshield.php
	}
if ( ! defined( 'WPSS_PLUGIN_COUNTER_PATH' ) ) {
	define( 'WPSS_PLUGIN_COUNTER_PATH', WPSS_PLUGIN_PATH . '/counter' ); // /public_html/wp-content/plugins/wp-spamshield/counter
	}
if ( ! defined( 'WPSS_PLUGIN_DATA_PATH' ) ) {
	define( 'WPSS_PLUGIN_DATA_PATH', WPSS_PLUGIN_PATH . '/data' ); // /public_html/wp-content/plugins/wp-spamshield/data
	}
if ( ! defined( 'WPSS_PLUGIN_IMG_PATH' ) ) {
	define( 'WPSS_PLUGIN_IMG_PATH', WPSS_PLUGIN_PATH . '/img' ); // /public_html/wp-content/plugins/wp-spamshield/img
	}
if ( ! defined( 'WPSS_PLUGIN_JS_PATH' ) ) {
	define( 'WPSS_PLUGIN_JS_PATH', WPSS_PLUGIN_PATH . '/js' ); // /public_html/wp-content/plugins/wp-spamshield/js
	}
if ( ! defined( 'WPSS_SERVER_ADDR' ) ) {
	define( 'WPSS_SERVER_ADDR', $_SERVER['SERVER_ADDR'] ); // 10.20.30.100
	}
if ( ! defined( 'WPSS_SERVER_NAME' ) ) {
	define( 'WPSS_SERVER_NAME', strtolower( $_SERVER['SERVER_NAME'] ) ); // example.com
	}
if ( ! defined( 'WPSS_SERVER_NAME_REV' ) ) {
	define( 'WPSS_SERVER_NAME_REV', strrev( WPSS_SERVER_NAME ) ); // moc.elpmaxe
	}
if ( ! defined( 'WPSS_DEBUG_SERVER_NAME' ) ) {
	define( 'WPSS_DEBUG_SERVER_NAME', '.redsandmarketing.com' );
	}
if ( ! defined( 'WPSS_DEBUG_SERVER_NAME_REV' ) ) {
	define( 'WPSS_DEBUG_SERVER_NAME_REV', strrev( WPSS_DEBUG_SERVER_NAME ) );
	}
// INCLUDE POPULAR CACHE PLUGINS HERE
$popular_cache_plugins_default = array (
	'w3-total-cache',
	'wp-super-cache',
	'db-cache-reloaded',
	'db-cache-reloaded-fix',
	'hyper-cache',
	'hyper-cache-extended',
	'wp-fast-cache',
	'wp-fastest-cache',
	'quick-cache',
	'lite-cache',
	);
if ( ! defined( 'WPSS_POPULAR_CACHE_PLUGINS' ) ) {
	// popular cache plugins - convert from array to constant
	define( 'WPSS_POPULAR_CACHE_PLUGINS', serialize( $popular_cache_plugins_default ) );
	}
// SET THE DEFAULT CONSTANT VALUES HERE:
$spamshield_default = array (
	'cookie_get_function_name' 				=> '',
	'cookie_set_function_name' 				=> '',
	'cookie_delete_function_name' 			=> '',
	'comment_validation_function_name' 		=> '',
	'wp_cache' 								=> 0,
	'wp_super_cache' 						=> 0,
	'block_all_trackbacks' 					=> 0,
	'block_all_pingbacks' 					=> 0,
	'use_alt_cookie_method'					=> 0,
	'use_alt_cookie_method_only'			=> 0,
	'use_captcha_backup' 					=> 0,
	'use_trackback_verification'		 	=> 0,
	'comment_logging'						=> 0,
	'comment_logging_start_date'			=> 0,
	'comment_logging_all'					=> 0,
	'enhanced_comment_blacklist'			=> 0,
	'allow_proxy_users'						=> 1,
	'hide_extra_data'						=> 0,
	'form_include_website' 					=> 1,
	'form_require_website' 					=> 0,
	'form_include_phone' 					=> 1,
	'form_require_phone' 					=> 0,
	'form_include_company' 					=> 0,
	'form_require_company' 					=> 0,
	'form_include_drop_down_menu'			=> 0,
	'form_require_drop_down_menu'			=> 0,
	'form_drop_down_menu_title'				=> '',
	'form_drop_down_menu_item_1'			=> '',
	'form_drop_down_menu_item_2'			=> '',
	'form_drop_down_menu_item_3'			=> '',
	'form_drop_down_menu_item_4'			=> '',
	'form_drop_down_menu_item_5'			=> '',
	'form_drop_down_menu_item_6'			=> '',
	'form_drop_down_menu_item_7'			=> '',
	'form_drop_down_menu_item_8'			=> '',
	'form_drop_down_menu_item_9'			=> '',
	'form_drop_down_menu_item_10'			=> '',
	'form_message_width' 					=> 40,
	'form_message_height' 					=> 10,
	'form_message_min_length'				=> 25,
	'form_response_thank_you_message'		=> 'Your message was sent successfully. Thank you.',
	'form_include_user_meta'				=> 1,
	'promote_plugin_link'					=> 0,
	);
if ( ! defined( 'WPSS_DEFAULT_VALUES' ) ) {
	// initial constant values - convert from array to constant
	define( 'WPSS_DEFAULT_VALUES', serialize( $spamshield_default ) );
	}

function spamshield_init() {
	update_option('wp_spamshield_version', WPSS_VERSION);
	spamshield_update_keys(0);
	}
	
function spamshield_create_random_key() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;

    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $keyCode = $keyCode . $tmp;
        $i++;
    	}
		
	if ($keyCode=='') {
		srand((double)74839201183*1000000);
    	$i = 0;
    	$pass = '' ;

    	while ($i <= 7) {
        	$num = rand() % 33;
        	$tmp = substr($chars, $num, 1);
        	$keyCode = $keyCode . $tmp;
        	$i++;
    		}
		}
    return $keyCode;
	}
	
function spamshield_update_keys($reset_keys) {
	$spamshield_options = get_option('spamshield_options');
	
	// Determine Time Key Was Last Updated
	$KeyUpdateTime = $spamshield_options['last_key_update'];

	// Set Random Cookie Name
	$CookieValidationName = $spamshield_options['cookie_validation_name'];
	if (!$CookieValidationName||$reset_keys==1) {
		$randomComValCodeCVN1 = spamshield_create_random_key();
		$randomComValCodeCVN2 = spamshield_create_random_key();
		$CookieValidationName = $randomComValCodeCVN1.$randomComValCodeCVN2;
		}
	// Set Random Cookie Value
	$CookieValidationKey = $spamshield_options['cookie_validation_key'];
	if (!$CookieValidationKey||$reset_keys==1) {
		$randomComValCodeCKV1 = spamshield_create_random_key();
		$randomComValCodeCKV2 = spamshield_create_random_key();
		$CookieValidationKey = $randomComValCodeCKV1.$randomComValCodeCKV2;
		}
	// Set Random Form Field Name
	$FormValidationFieldJS = $spamshield_options['form_validation_field_js'];
	if (!$FormValidationFieldJS||$reset_keys==1) {
		$randomComValCodeJSFFN1 = spamshield_create_random_key();
		$randomComValCodeJSFFN2 = spamshield_create_random_key();
		$FormValidationFieldJS = $randomComValCodeJSFFN1.$randomComValCodeJSFFN2;
		}
	// Set Random Form Field Value
	$FormValidationKeyJS = $spamshield_options['form_validation_key_js'];
	if (!$FormValidationKeyJS||$reset_keys==1) {
		$randomComValCodeJS1 = spamshield_create_random_key();
		$randomComValCodeJS2 = spamshield_create_random_key();
		$FormValidationKeyJS = $randomComValCodeJS1.$randomComValCodeJS2;
		}
	if (!$KeyUpdateTime||$reset_keys==1) {
		$KeyUpdateTime = time();
		}
	$spamshield_options_update = array (
		'cookie_validation_name' 				=> $CookieValidationName,
		'cookie_validation_key' 				=> $CookieValidationKey,
		'form_validation_field_js' 				=> $FormValidationFieldJS,
		'form_validation_key_js' 				=> $FormValidationKeyJS,
		'cookie_get_function_name' 				=> '',
		'cookie_set_function_name' 				=> '',
		'cookie_delete_function_name' 			=> '',
		'comment_validation_function_name' 		=> '',
		'last_key_update'						=> $KeyUpdateTime,
		'wp_cache' 								=> $spamshield_options['wp_cache'],
		'wp_super_cache' 						=> $spamshield_options['wp_super_cache'],
		'block_all_trackbacks' 					=> $spamshield_options['block_all_trackbacks'],
		'block_all_pingbacks' 					=> $spamshield_options['block_all_pingbacks'],
		'use_alt_cookie_method' 				=> $spamshield_options['use_alt_cookie_method'],
		'use_alt_cookie_method_only' 			=> $spamshield_options['use_alt_cookie_method_only'],
		'use_captcha_backup' 					=> $spamshield_options['use_captcha_backup'],
		'use_trackback_verification'		 	=> $spamshield_options['use_trackback_verification'],
		'comment_logging'						=> $spamshield_options['comment_logging'],
		'comment_logging_start_date'			=> $spamshield_options['comment_logging_start_date'],
		'comment_logging_all'					=> $spamshield_options['comment_logging_all'],
		'enhanced_comment_blacklist'			=> $spamshield_options['enhanced_comment_blacklist'],
		'allow_proxy_users'						=> $spamshield_options['allow_proxy_users'],
		'hide_extra_data'						=> $spamshield_options['hide_extra_data'],
		'form_include_website' 					=> $spamshield_options['form_include_website'],
		'form_require_website' 					=> $spamshield_options['form_require_website'],
		'form_include_phone' 					=> $spamshield_options['form_include_phone'],
		'form_require_phone' 					=> $spamshield_options['form_require_phone'],
		'form_include_company' 					=> $spamshield_options['form_include_company'],
		'form_require_company' 					=> $spamshield_options['form_require_company'],
		'form_include_drop_down_menu'			=> $spamshield_options['form_include_drop_down_menu'],
		'form_require_drop_down_menu'			=> $spamshield_options['form_require_drop_down_menu'],
		'form_drop_down_menu_title'				=> $spamshield_options['form_drop_down_menu_title'],
		'form_drop_down_menu_item_1'			=> $spamshield_options['form_drop_down_menu_item_1'],
		'form_drop_down_menu_item_2'			=> $spamshield_options['form_drop_down_menu_item_2'],
		'form_drop_down_menu_item_3'			=> $spamshield_options['form_drop_down_menu_item_3'],
		'form_drop_down_menu_item_4'			=> $spamshield_options['form_drop_down_menu_item_4'],
		'form_drop_down_menu_item_5'			=> $spamshield_options['form_drop_down_menu_item_5'],
		'form_drop_down_menu_item_6'			=> $spamshield_options['form_drop_down_menu_item_6'],
		'form_drop_down_menu_item_7'			=> $spamshield_options['form_drop_down_menu_item_7'],
		'form_drop_down_menu_item_8'			=> $spamshield_options['form_drop_down_menu_item_8'],
		'form_drop_down_menu_item_9'			=> $spamshield_options['form_drop_down_menu_item_9'],
		'form_drop_down_menu_item_10'			=> $spamshield_options['form_drop_down_menu_item_10'],
		'form_message_width' 					=> $spamshield_options['form_message_width'],
		'form_message_height' 					=> $spamshield_options['form_message_height'],
		'form_message_min_length'				=> $spamshield_options['form_message_min_length'],
		'form_message_recipient'				=> $spamshield_options['form_message_recipient'],
		'form_response_thank_you_message'		=> $spamshield_options['form_response_thank_you_message'],
		'form_include_user_meta'				=> $spamshield_options['form_include_user_meta'],
		'promote_plugin_link'					=> $spamshield_options['promote_plugin_link'],
		'install_date'							=> $spamshield_options['install_date'],
		);
	update_option('spamshield_options', $spamshield_options_update);		
	}

function spamshield_date_diff($start, $end) {
	$start_ts = strtotime($start);
	$end_ts = strtotime($end);
	$diff = ( $end_ts - $start_ts );
	$start_array = split("-", $start);
	$start_year = $start_array[0];
	$end_array = split("-", $end);
	$end_year = $end_array[0];
	$years = $end_year-$start_year;
	if (($years%4) == 0) {
		$extra_days = ((($end_year-$start_year)/4)-1);
		} else {
		$extra_days = ((($end_year-$start_year)/4));
		}
	$extra_days = round($extra_days);
	return round($diff / 86400)+$extra_days;
	}

function spamshield_get_domain($url) {
	$parsed = parse_url($url);
	$hostname = $parsed['host'];
	return $hostname;
	}

function spamshield_count_words($string) {
	$string = trim($string);
	$char_count = spamshield_strlen($string);
	if ( !$string || $string == '' || $char_count == 0 ) {
		$number_of_words = 0;
		}
	else { 
		$exploded_string = explode (' ',$string);
		$number_of_words = count($exploded_string);		
		}
	return $number_of_words;
	}

function spamshield_strlen($string) {
	// Use this function instead of mb_strlen because some IIS servers have mb_strlen disabled by default
	// BUT mb_strlen is superior to strlen, so use it whenever possible
	if (function_exists( 'mb_strlen' ) ) {
		$number_of_chars = mb_strlen($string);
		}
	else {
		$number_of_chars = strlen($string);
		}
	return $number_of_chars;
	}

function spamshield_check_cache_status() {
	// TEST FOR CACHING
	$wpss_active_plugins = get_option( 'active_plugins' );
	$wpss_active_plugins_ser = serialize( $wpss_active_plugins );
	$wpss_active_plugins_ser_lc = strtolower($wpss_active_plugins_ser);
	if ( ! defined( 'WP_CACHE' ) || ( ( defined( 'WP_CACHE' ) && constant( 'WP_CACHE' ) == false ) ) ) {
		$wpss_caching_status = 'INACTIVE';
		}
	elseif ( defined( 'WP_CACHE' ) && constant( 'WP_CACHE' ) == true ) {
		$wpss_caching_status = 'ACTIVE';
		}
	if ( ! defined( 'ENABLE_CACHE' ) || ( ( defined( 'ENABLE_CACHE' ) && constant( 'ENABLE_CACHE' ) == false ) ) ) {
		$wpss_caching_enabled_status = 'INACTIVE';
		}
	elseif ( defined( 'ENABLE_CACHE' ) && constant( 'ENABLE_CACHE' ) == true ) {
		$wpss_caching_enabled_status = 'ACTIVE';
		}
	// Check if any popular cache plugins are active
	$popular_cache_plugins = unserialize(WPSS_POPULAR_CACHE_PLUGINS);
	$popular_cache_plugins_array_count = count($popular_cache_plugins);
	$wpss_active_plugins_array_count = count($wpss_active_plugins);
	$popular_cache_plugins_active = array();
	$popular_cache_plugins_temp = 0;
	$i = 0;
	while ($i < $popular_cache_plugins_array_count) {
		if ( strpos( $wpss_active_plugins_ser_lc, $popular_cache_plugins[$i] ) !== false ) {
			$popular_cache_plugins_temp = 1;
			$popular_cache_plugins_active[] = $popular_cache_plugins[$i];
			}
		$i++;
		}
	if ( $popular_cache_plugins_temp == 1 ) {
		$wpss_caching_plugin_status = 'ACTIVE';
		}
	else {
		$wpss_caching_plugin_status = 'INACTIVE';
		}
	if ( $wpss_caching_status == 'ACTIVE' || $wpss_caching_enabled_status == 'ACTIVE' || $wpss_caching_plugin_status == 'ACTIVE' ) {
		// This is the overall test if caching is active.
		$wpss_cache_check_status = 'ACTIVE';
		}
	else {
		$wpss_cache_check_status = 'INACTIVE';
		}
	$wpss_caching_plugins_active = serialize( $popular_cache_plugins_active );
	$wpss_all_plugins_active = serialize( $wpss_active_plugins );
	$wpss_cache_check = array(
		'cache_check_status'		=> $wpss_cache_check_status,
		'caching_status'			=> $wpss_caching_status,
		'caching_enabled_status'	=> $wpss_caching_enabled_status,
		'caching_plugin_status'		=> $wpss_caching_plugin_status,
		'caching_plugins_active'	=> $wpss_caching_plugins_active,
		'all_plugins_active'		=> $wpss_all_plugins_active,
		);
	return $wpss_cache_check;
	}

function spamshield_count() {
	$spamshield_count = get_option('spamshield_count');
	return $spamshield_count;
	}

function spamshield_ak_accuracy_fix() {
	// Akismet's counter is currently taking credit for some spam killed by WP-SpamShield - the following ensures accurate reporting.
	// The reason for this fix is that Akismet may have marked the same comment as spam, but WP-SpamShield actually kills it - with or without Akismet.
	$ak_count_pre	= get_option('ak_count_pre');
	$ak_count_post	= get_option('akismet_spam_count');
	if ($ak_count_post > $ak_count_pre) {
		update_option( 'akismet_spam_count', $ak_count_pre );
		}
	}

function spamshield_counter($counter_option) {
	$counter_option_max = 9;
	$counter_option_min = 1;
	if ( !$counter_option || $counter_option > $counter_option_max || $counter_option < $counter_option_min ) {
		$spamshield_count = number_format( get_option('spamshield_count') );
		echo '<a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="text-decoration:none;" rel="external" title="WP-SpamShield - WordPress Anti-Spam Plugin" >'.$spamshield_count.' spam blocked by WP-SpamShield</a>';
		return;
		}
	// Display Counter
	/* Implementation: <?php if ( function_exists(spamshield_counter) ) { spamshield_counter(1); } ?> */
	$spamshield_count = number_format( get_option('spamshield_count') );
	$counter_div_height = array('0','66','66','66','106','61','67','66','66','106');
	$counter_count_padding_top = array('0','11','11','11','79','14','17','11','11','79');
	
	?>
	
	<style type="text/css">
	#spamshield_counter_wrap {color:#ffffff;text-decoration:none;width:140px;}
	#spamshield_counter {background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-bg-<?php echo $counter_option; ?>.png) no-repeat top left;height:<?php echo $counter_div_height[$counter_option]; ?>px;width:140px;overflow:hidden;border-style:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;padding-top:<?php echo $counter_count_padding_top[$counter_option]; ?>px;}
	</style>
	
	<div id="spamshield_counter_wrap" >
		<div id="spamshield_counter" >
		<?php 
			$server_ip_first_char = substr(WPSS_SERVER_ADDR, 0, 1);
			if ( ( $counter_option >= 1 && $counter_option <= 3 ) || ( $counter_option >= 7 && $counter_option <= 8 ) ) {
				if ( $server_ip_first_char > '5' ) {
					$spamshield_counter_title_text = 'WP-SpamShield Spam Plugin for WordPress';
					}
				else {
					$spamshield_counter_title_text = 'WP-SpamShield WordPress Anti-Spam Plugin';
					}
				echo '<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >';
				echo '<span style="color:#ffffff;font-size:20px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'; 
				echo '<span style="color:#ffffff;font-size:14px;line-height:110%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">SPAM KILLED</span><br />'; 
				echo '<span style="color:#ffffff;font-size:9px;line-height:120%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">BY WP-SPAMSHIELD</span>';
				echo '</a></strong>';
				}
			else if ( $counter_option == 4 || $counter_option == 9 ) {
				if ( $server_ip_first_char > '5' ) {
					$spamshield_counter_title_text = 'WP-SpamShield - WordPress Spam Protection';
					}
				else {
					$spamshield_counter_title_text = 'WP-SpamShield - WordPress Anti-Spam';
					}
				echo '<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >';
				echo '<span style="color:#000000;font-size:9px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.' SPAM KILLED</span><br />'; 
				echo '</a></strong>'; 
				}
			else if ( $counter_option == 5 ) {
				echo '<strong style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="Spam Killed by WP-SpamShield, a WordPress Anti-Spam Plugin" >';
				echo '<span style="color:#FEB22B;font-size:14px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'; 
				echo '</a></strong>'; 
				}
			else if ( $counter_option == 6 ) {
				if ( $server_ip_first_char > '5' ) {
					$spamshield_counter_title_text = 'Spam Killed by WP-SpamShield - Ultimate Spam Protection';
					}
				else {
					$spamshield_counter_title_text = 'Spam Killed by WP-SpamShield - Robust WordPress Anti-Spam';
					}
				echo '<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >';
				echo '<span style="color:#000000;font-size:14px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'; 
				echo '</a></strong>'; 
				}
		?>
		</div>
	</div>
	
	<?php
	}

function spamshield_counter_short($attr) {
	
	$counter_option = $attr['style'];
	
	$counter_option_max = 9;
	$counter_option_min = 1;
	if ( !$counter_option || $counter_option > $counter_option_max || $counter_option < $counter_option_min ) {
		$spamshield_count = number_format( get_option('spamshield_count') );
		$wpss_shortcode_content = '<a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="text-decoration:none;" rel="external" title="WP-SpamShield - WordPress Anti-Spam Plugin" >'.$spamshield_count.' spam blocked by WP-SpamShield</a>'."\n";
		return $wpss_shortcode_content;
		}
	// Display Counter
	/* Implementation: <?php if ( function_exists(spamshield_counter) ) { spamshield_counter(1); } ?> */
	/* Implementation: [spamshieldcounter style=1] or [spamshieldcounter] where "style" is 0-9 */
	$spamshield_count = number_format( get_option('spamshield_count') );
	$counter_div_height = array('0','66','66','66','106','61','67','66','66','106');
	$counter_count_padding_top = array('0','11','11','11','79','14','17','11','11','79');
	
	$wpss_shortcode_content .= '<style type="text/css">'."\n";
	$wpss_shortcode_content .= '#spamshield_counter_wrap {color:#ffffff;text-decoration:none;width:140px;}'."\n";
	$wpss_shortcode_content .= '#spamshield_counter {background:url('.WPSS_PLUGIN_COUNTER_URL.'/spamshield-counter-bg-'.$counter_option.'.png) no-repeat top left;height:'.$counter_div_height[$counter_option].'px;width:140px;overflow:hidden;border-style:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;padding-top:'.$counter_count_padding_top[$counter_option].'px;}'."\n";
	$wpss_shortcode_content .= '</style>'."\n";
	
	$wpss_shortcode_content .= '<div id="spamshield_counter_wrap" >'."\n";
	$wpss_shortcode_content .= '	<div id="spamshield_counter" >'."\n";
	$server_ip_first_char = substr(WPSS_SERVER_ADDR, 0, 1);
	if ( ( $counter_option >= 1 && $counter_option <= 3 ) || ( $counter_option >= 7 && $counter_option <= 8 ) ) {
		if ( $server_ip_first_char > '5' ) {
			$spamshield_counter_title_text = 'WP-SpamShield Spam Plugin for WordPress';
			}
		else {
			$spamshield_counter_title_text = 'WP-SpamShield WordPress Anti-Spam Plugin';
			}
		$wpss_shortcode_content .= '	<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= '	<span style="color:#ffffff;font-size:20px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n"; 
		$wpss_shortcode_content .= '	<span style="color:#ffffff;font-size:14px;line-height:110%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">SPAM KILLED</span><br />'."\n"; 
		$wpss_shortcode_content .= '	<span style="color:#ffffff;font-size:9px;line-height:120%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">BY WP-SPAMSHIELD</span>'."\n";
		$wpss_shortcode_content .= '	</a></strong>';
		}
	else if ( $counter_option == 4 || $counter_option == 9 ) {
		if ( $server_ip_first_char > '5' ) {
			$spamshield_counter_title_text = 'WP-SpamShield - WordPress Spam Protection';
			}
		else {
			$spamshield_counter_title_text = 'WP-SpamShield - WordPress Anti-Spam';
			}
		$wpss_shortcode_content .= '	<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= '	<span style="color:#000000;font-size:9px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.' SPAM KILLED</span><br />'."\n"; 
		$wpss_shortcode_content .= '	</a></strong>'."\n"; 
		}
	else if ( $counter_option == 5 ) {
		$wpss_shortcode_content .= '	<strong style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="Spam Killed by WP-SpamShield, a WordPress Anti-Spam Plugin" >'."\n";
		$wpss_shortcode_content .= '	<span style="color:#FEB22B;font-size:14px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n"; 
		$wpss_shortcode_content .= '	</a></strong>'."\n"; 
		}
	else if ( $counter_option == 6 ) {
		if ( $server_ip_first_char > '5' ) {
			$spamshield_counter_title_text = '	Spam Killed by WP-SpamShield - Ultimate Spam Protection'."\n";
			}
		else {
			$spamshield_counter_title_text = '	Spam Killed by WP-SpamShield - Robust WordPress Anti-Spam'."\n";
			}
		$wpss_shortcode_content .= '	<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= '	<span style="color:#000000;font-size:14px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n"; 
		$wpss_shortcode_content .= '	</a></strong>'."\n"; 
		}
	$wpss_shortcode_content .= '	</div>'."\n";
	$wpss_shortcode_content .= '</div>'."\n";

	return $wpss_shortcode_content;
	}

function spamshield_counter_sm($counter_sm_option) {
	$counter_sm_option_max = 5;
	$counter_sm_option_min = 1;
	if ( !$counter_sm_option || $counter_sm_option > $counter_sm_option_max || $counter_sm_option < $counter_sm_option_min ) {
		$counter_sm_option = 1;
		}
	// Display Small Counter
	/* Implementation: <?php if ( function_exists(spamshield_counter_sm) ) { spamshield_counter_sm(1); } ?> */
	$spamshield_count = number_format( get_option('spamshield_count') );
	$counter_sm_div_height = array('0','50','50','50','50','50');
	$counter_sm_count_padding_top = array('0','11','11','11','11','11');
	
	?>
	
	<style type="text/css">
	#spamshield_counter_sm_wrap {color:#ffffff;text-decoration:none;width:120px;}
	#spamshield_counter_sm {background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-sm-bg-<?php echo $counter_sm_option; ?>.png) no-repeat top left;height:<?php echo $counter_sm_div_height[$counter_sm_option]; ?>px;width:120px;overflow:hidden;border-style:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;padding-top:<?php echo $counter_sm_count_padding_top[$counter_sm_option]; ?>px;}
	</style>
	
	<div id="spamshield_counter_sm_wrap" >
		<div id="spamshield_counter_sm" >
		<?php 
			$server_ip_first_char = substr(WPSS_SERVER_ADDR, 0, 1);

			if ( ( $counter_sm_option >= 1 && $counter_sm_option <= 5 ) ) {
				if ( $server_ip_first_char > '5' ) {
					$spamshield_counter_title_text = 'Protected by WP-SpamShield - WordPress Anti-Spam Plugin';
					}
				else {
					$spamshield_counter_title_text = 'Protected by WP-SpamShield - WordPress Spam Plugin';
					}
				echo '<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >';
				echo '<span style="color:#ffffff;font-size:18px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'; 
				echo '<span style="color:#ffffff;font-size:10px;line-height:120%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">SPAM BLOCKED</span>';
				echo '</a></strong>'; 
				}
		?>
		</div>
	</div>
	
	<?php
	}

function spamshield_counter_sm_short($attr) {
	
	$counter_sm_option = $attr['style'];
	
	$counter_sm_option_max = 5;
	$counter_sm_option_min = 1;
	if ( !$counter_sm_option || $counter_sm_option > $counter_sm_option_max || $counter_sm_option < $counter_sm_option_min ) {
		$counter_sm_option = 1;
		}
	// Display Small Counter
	/* Implementation: <?php if ( function_exists(spamshield_counter_sm) ) { spamshield_counter_sm(1); } ?> */
	/* Implementation: [spamshieldcountersm style=1] or [spamshieldcountersm] where "style" is 1-5 */
	$spamshield_count = number_format( get_option('spamshield_count') );
	$counter_sm_div_height = array('0','50','50','50','50','50');
	$counter_sm_count_padding_top = array('0','11','11','11','11','11');
	
	$wpss_shortcode_content = '';
	$wpss_shortcode_content .= "\n\n";
	$wpss_shortcode_content .= "<style type=\"text/css\">\n";
	$wpss_shortcode_content .= "#spamshield_counter_sm_wrap {color:#ffffff;text-decoration:none;width:120px;}\n";
	$wpss_shortcode_content .= "#spamshield_counter_sm {background:url(".WPSS_PLUGIN_COUNTER_URL."/spamshield-counter-sm-bg-".$counter_sm_option.".png) no-repeat top left;height:".$counter_sm_div_height[$counter_sm_option]."px;width:120px;overflow:hidden;border-style:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;padding-top:".$counter_sm_count_padding_top[$counter_sm_option]."px;}\n";
	$wpss_shortcode_content .= "</style>\n\n";
	$wpss_shortcode_content .= "<div id=\"spamshield_counter_sm_wrap\" >\n";
	$wpss_shortcode_content .= "	<div id=\"spamshield_counter_sm\" >\n";
	
	$server_ip_first_char = substr(WPSS_SERVER_ADDR, 0, 1);

	if ( ( $counter_sm_option >= 1 && $counter_sm_option <= 5 ) ) {
		if ( $server_ip_first_char > '5' ) {
			$spamshield_counter_title_text = 'Protected by WP-SpamShield - WordPress Anti-Spam Plugin';
			}
		else {
			$spamshield_counter_title_text = 'Protected by WP-SpamShield - WordPress Spam Plugin';
			}
		$wpss_shortcode_content .= '	<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= '	<span style="color:#ffffff;font-size:18px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n"; 
		$wpss_shortcode_content .= '	<span style="color:#ffffff;font-size:10px;line-height:120%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">SPAM BLOCKED</span>'."\n";
		$wpss_shortcode_content .= '	</a></strong>'."\n"; 
		}
	$wpss_shortcode_content .= "	</div>\n";
	$wpss_shortcode_content .= "</div>\n";
	
	return $wpss_shortcode_content;
	}

// Widget
function widget_spamshield_register() {
	function widget_spamshield($args) {
		extract($args);
		echo $before_widget;
		echo $before_title.'Spam'.$after_title;
		spamshield_counter_sm();
		echo $after_widget;
		}
	register_sidebar_widget('WP-SpamShield Counter','widget_spamshield');
	}
	
function spamshield_log_reset() {
	
	$wpss_log_filename = 'temp-comments-log.txt';
	$wpss_log_empty_filename = 'temp-comments-log.init.txt';
	$wpss_htaccess_filename = '.htaccess';
	$wpss_htaccess_orig_filename = 'htaccess.txt';
	$wpss_htaccess_empty_filename = 'htaccess.init.txt';
	$wpss_log_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_log_filename;
	$wpss_log_empty_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_log_empty_filename;
	$wpss_htaccess_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_htaccess_filename;
	$wpss_htaccess_orig_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_htaccess_orig_filename;
	$wpss_htaccess_empty_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_htaccess_empty_filename;
	
	clearstatcache();
	if ( !file_exists( $wpss_htaccess_file ) ) {
		@chmod( WPSS_PLUGIN_DATA_PATH, 0775 );
		@chmod( $wpss_htaccess_orig_file, 0666 );
		@chmod( $wpss_htaccess_empty_file, 0666 );
		@rename( $wpss_htaccess_orig_file, $wpss_htaccess_file );
		@copy( $wpss_htaccess_empty_file, $wpss_htaccess_orig_file );
		}

	clearstatcache();
	$wpss_perm_log_dir = substr(sprintf('%o', fileperms(WPSS_PLUGIN_DATA_PATH)), -4);
	$wpss_perm_log_file = substr(sprintf('%o', fileperms($wpss_log_file)), -4);
	$wpss_perm_log_empty_file = substr(sprintf('%o', fileperms($wpss_log_empty_file)), -4);
	$wpss_perm_htaccess_file = substr(sprintf('%o', fileperms($wpss_htaccess_file)), -4);
	$wpss_perm_htaccess_empty_file = substr(sprintf('%o', fileperms($wpss_htaccess_empty_file)), -4);
	if ( $wpss_perm_log_dir < '0775' || !is_writable(WPSS_PLUGIN_DATA_PATH) || $wpss_perm_log_file < '0666' || !is_writable($wpss_log_file) || $wpss_perm_log_empty_file < '0666' || !is_writable($wpss_log_empty_file) || $wpss_perm_htaccess_file < '0666' || !is_writable($wpss_htaccess_file) || $wpss_perm_htaccess_empty_file < '0666' || !is_writable($wpss_htaccess_empty_file) ) {
		@chmod( WPSS_PLUGIN_DATA_PATH, 0775 );
		@chmod( $wpss_log_file, 0666 );
		@chmod( $wpss_log_empty_file, 0666 );
		@chmod( $wpss_htaccess_file, 0666 );
		@chmod( $wpss_htaccess_empty_file, 0666 );
		}
	if ( file_exists( $wpss_log_file ) && file_exists( $wpss_log_empty_file ) ) {
		@copy( $wpss_log_empty_file, $wpss_log_file );
		}
	if ( file_exists( $wpss_htaccess_file ) && file_exists( $wpss_htaccess_empty_file ) ) {
		@copy( $wpss_htaccess_empty_file, $wpss_htaccess_file );
		}
	if ( $_SERVER['REMOTE_ADDR'] ) {
		$wpss_htaccess_http_host = str_replace( '.', '\.', $_SERVER['HTTP_HOST'] );
		$wpss_htaccess_blog_url = str_replace( '.', '\.', WPSS_SITE_URL );
		if ( $wpss_htaccess_blog_url ) {
			$wpss_htaccess_data  = "SetEnvIfNoCase Referer ".WPSS_ADMIN_URL."/ wpss_access\n";
			}
		$wpss_htaccess_data .= "SetEnvIf Remote_Addr ^".$_SERVER['REMOTE_ADDR']."$ wpss_access\n\n";	
		$wpss_htaccess_data .= "<Files temp-comments-log.txt>\n";
		$wpss_htaccess_data .= "order deny,allow\n";
		$wpss_htaccess_data .= "deny from all\n";
		$wpss_htaccess_data .= "allow from env=wpss_access\n";
		$wpss_htaccess_data .= "</Files>\n";
		}
	@$wpss_htaccess_fp = fopen( $wpss_htaccess_file,'a+' );
	@fwrite( $wpss_htaccess_fp, $wpss_htaccess_data );
	@fclose( $wpss_htaccess_fp );
	}

function spamshield_log_data( $wpss_log_comment_data_array, $wpss_log_comment_data_errors, $wpss_log_comment_type = 'comment', $wpss_log_contact_form_data = NULL ) {

	$wpss_log_filename = 'temp-comments-log.txt';
	$wpss_log_empty_filename = 'temp-comments-log.init.txt';
	$wpss_log_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_log_filename;
	$wpss_log_max_filesize = 2*1048576; // 2 MB
	
	if ( !$wpss_log_comment_type || $wpss_log_comment_type === NULL ) {
		$wpss_log_comment_type = 'comment';
		}
	$wpss_log_comment_type_display 	= strtoupper($wpss_log_comment_type);
	$wpss_log_comment_type_ucwords	= ucwords($wpss_log_comment_type);
	
	$spamshield_options = get_option('spamshield_options');
	
	$CommentLogging 			= $spamshield_options['comment_logging'];
	$CommentLoggingStartDate 	= $spamshield_options['comment_logging_start_date'];
	$CommentLoggingAll 			= $spamshield_options['comment_logging_all'];
	
	$wpss_javascript_page_referrer	= $wpss_log_comment_data_array['javascript_page_referrer'];
	//$wpss_php_page_referrer 		= $wpss_log_comment_data_array['php_page_referrer'];
	$wpss_jsonst		 			= $wpss_log_comment_data_array['jsonst'];
	
	$GetCurrentTime = time();
	$ResetIntervalHours = 24 * 7; // Reset interval in hours
	$ResetIntervalMinutes = 60; // Reset interval minutes default
	$ResetIntervalMinutesOverride = $ResetIntervalMinutes; // Use as override for testing; leave = $ResetIntervalMinutes when not testing

	if ( $ResetIntervalMinutesOverride != $ResetIntervalMinutes ) {
		$ResetIntervalHours = 1;
		$ResetIntervalMinutes = $ResetIntervalMinutesOverride;
		}
	$TimeThreshold = $GetCurrentTime - ( 60 * $ResetIntervalMinutes * $ResetIntervalHours ); // seconds * minutes * hours
	// This turns off if over x amount of time since starting, or filesize exceeds max

	if ( ( $CommentLoggingStartDate && $TimeThreshold > $CommentLoggingStartDate ) || ( file_exists( $wpss_log_file ) && filesize( $wpss_log_file ) >= $wpss_log_max_filesize ) ) {
		//spamshield_log_reset();
		$CommentLogging = 0;
		$CommentLoggingStartDate = 0;
		$CommentLoggingAll = 0;
		$spamshield_options_update = array (
			'cookie_validation_name' 				=> $spamshield_options['cookie_validation_name'],
			'cookie_validation_key' 				=> $spamshield_options['cookie_validation_key'],
			'form_validation_field_js' 				=> $spamshield_options['form_validation_field_js'],
			'form_validation_key_js' 				=> $spamshield_options['form_validation_key_js'],
			'cookie_get_function_name' 				=> $spamshield_options['cookie_get_function_name'],
			'cookie_set_function_name' 				=> $spamshield_options['cookie_set_function_name'],
			'cookie_delete_function_name' 			=> $spamshield_options['cookie_delete_function_name'],
			'comment_validation_function_name' 		=> $spamshield_options['comment_validation_function_name'],
			'last_key_update'						=> $spamshield_options['last_key_update'],
			'wp_cache' 								=> $spamshield_options['wp_cache'],
			'wp_super_cache' 						=> $spamshield_options['wp_super_cache'],
			'block_all_trackbacks' 					=> $spamshield_options['block_all_trackbacks'],
			'block_all_pingbacks' 					=> $spamshield_options['block_all_pingbacks'],
			'use_alt_cookie_method' 				=> $spamshield_options['use_alt_cookie_method'],
			'use_alt_cookie_method_only' 			=> $spamshield_options['use_alt_cookie_method_only'],
			'use_captcha_backup' 					=> $spamshield_options['use_captcha_backup'],
			'use_trackback_verification' 			=> $spamshield_options['use_trackback_verification'],
			'comment_logging'						=> $CommentLogging,
			'comment_logging_start_date'			=> $CommentLoggingStartDate,
			'comment_logging_all'					=> $CommentLoggingAll,
			'enhanced_comment_blacklist'			=> $spamshield_options['enhanced_comment_blacklist'],
			'allow_proxy_users'						=> $spamshield_options['allow_proxy_users'],
			'hide_extra_data'						=> $spamshield_options['hide_extra_data'],
			'form_include_website' 					=> $spamshield_options['form_include_website'],
			'form_require_website' 					=> $spamshield_options['form_require_website'],
			'form_include_phone' 					=> $spamshield_options['form_include_phone'],
			'form_require_phone' 					=> $spamshield_options['form_require_phone'],
			'form_include_company' 					=> $spamshield_options['form_include_company'],
			'form_require_company' 					=> $spamshield_options['form_require_company'],
			'form_include_drop_down_menu'			=> $spamshield_options['form_include_drop_down_menu'],
			'form_require_drop_down_menu'			=> $spamshield_options['form_require_drop_down_menu'],
			'form_drop_down_menu_title'				=> $spamshield_options['form_drop_down_menu_title'],
			'form_drop_down_menu_item_1'			=> $spamshield_options['form_drop_down_menu_item_1'],
			'form_drop_down_menu_item_2'			=> $spamshield_options['form_drop_down_menu_item_2'],
			'form_drop_down_menu_item_3'			=> $spamshield_options['form_drop_down_menu_item_3'],
			'form_drop_down_menu_item_4'			=> $spamshield_options['form_drop_down_menu_item_4'],
			'form_drop_down_menu_item_5'			=> $spamshield_options['form_drop_down_menu_item_5'],
			'form_drop_down_menu_item_6'			=> $spamshield_options['form_drop_down_menu_item_6'],
			'form_drop_down_menu_item_7'			=> $spamshield_options['form_drop_down_menu_item_7'],
			'form_drop_down_menu_item_8'			=> $spamshield_options['form_drop_down_menu_item_8'],
			'form_drop_down_menu_item_9'			=> $spamshield_options['form_drop_down_menu_item_9'],
			'form_drop_down_menu_item_10'			=> $spamshield_options['form_drop_down_menu_item_10'],
			'form_message_width' 					=> $spamshield_options['form_message_width'],
			'form_message_height' 					=> $spamshield_options['form_message_height'],
			'form_message_min_length' 				=> $spamshield_options['form_message_min_length'],
			'form_message_recipient' 				=> $spamshield_options['form_message_recipient'],
			'form_response_thank_you_message' 		=> $spamshield_options['form_response_thank_you_message'],
			'form_include_user_meta' 				=> $spamshield_options['form_include_user_meta'],
			'promote_plugin_link' 					=> $spamshield_options['promote_plugin_link'],
			'install_date'							=> $spamshield_options['install_date'],
			);
		update_option('spamshield_options', $spamshield_options_update);
		}
	else {
		// LOG DATA
		$wpss_log_datum = date("Y-m-d (D) H:i:s",$GetCurrentTime);
		$wpss_log_comment_data  = ":: ".$wpss_log_comment_type_display." BEGIN ::"."\n";
		
		$submitter_ip_address = $_SERVER['REMOTE_ADDR'];
		$submitter_ip_address_short_l = trim( substr( $submitter_ip_address, 0, 6) );
		$submitter_ip_address_short_r = trim( substr( $submitter_ip_address, -6, 2) );
		$submitter_ip_address_obfuscated = $submitter_ip_address_short_l.'****'.$submitter_ip_address_short_r.'.***';
		
		// IP / PROXY INFO - BEGIN
		$ip = $submitter_ip_address;
		$ipBlock=explode('.',$ip);
		$ipProxyVIA=trim($_SERVER['HTTP_VIA']);
		$ipProxyVIA_LC=strtolower($ipProxyVIA);
		$MaskedIP=trim($_SERVER['HTTP_X_FORWARDED_FOR']); // Stated Original IP - Can be faked
		$MaskedIPBlock=explode('.',$MaskedIP);
		if ( preg_match("/^([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])/", $MaskedIP ) && $MaskedIP != '' && $MaskedIP != 'unknown' && strpos( $MaskedIP, '192.168.' ) !== 0 ) {
			$MaskedIPValid = true;
			$MaskedIPCore = rtrim( $MaskedIP, " unknown;," );
			}
		$ReverseDNS = sanitize_text_field(gethostbyaddr($ip));
		$ReverseDNSIP = gethostbyname($ReverseDNS);

		if ( $_SERVER['REMOTE_HOST'] ) {
			$submitter_remote_host = sanitize_text_field($_SERVER['REMOTE_HOST']);
			}
		else {
			$submitter_remote_host = $ReverseDNS;
			}
		
		if ( $ReverseDNSIP != $ip || $ip == $ReverseDNS ) {
			$ReverseDNSAuthenticity = '[Possibly Forged]';
			} 
		else {
			$ReverseDNSAuthenticity = '[Verified]';
			}
		// Detect Use of Proxy
		//if ($ipProxyVIA||$MaskedIP) {
		if ( ( $ipProxyVIA && $ipProxyVIA !='' ) || ( $MaskedIP && $MaskedIP !='' ) ) {
			if ( !$MaskedIP || $MaskedIP == '' ) { $MaskedIP='[No Data]'; }
			$ipProxy='PROXY DETECTED';
			$ipProxyShort='PROXY';
			$ipProxyData=$ip.' | MASKED IP: '.$MaskedIP;
			$ProxyStatus='TRUE';
			//Google Chrome Compression Check
			if ( strpos( $ipProxyVIA_LC, 'chrome compression proxy' ) !== false && preg_match( "@^google-proxy-(.*)\.google\.com$@i", $ReverseDNS ) ) {
				$ipProxyChromeCompression='TRUE';
				}
			else {
				$ipProxyChromeCompression='FALSE';
				}
			}
		else {
			$ipProxy='No Proxy';
			$ipProxyShort=$ipProxy;
			$ipProxyData='[None]';
			$ProxyStatus='FALSE';
			}
		// IP / PROXY INFO - END
		
		if ( $wpss_log_comment_type == 'comment' ) {
			// Comment Post Info
			$comment_author_email = $wpss_log_comment_data_array['comment_author_email'];
			$comment_author_email_short_l = trim( substr( $comment_author_email, 0, 4) );
			$comment_author_email_short_r = trim( substr( $comment_author_email, -5, 5) );
			$comment_author_email_obfuscated = $comment_author_email_short_l.'***@***'.$comment_author_email_short_r;
			if ( !$comment_author_email ) { $comment_author_email_obfuscated = '[None]'; }
			
			$wpss_log_comment_data .= $wpss_log_datum."\n";
			$wpss_log_comment_data .= "Comment Post ID: ".$wpss_log_comment_data_array['comment_post_ID']."\n";
			$wpss_log_comment_data .= "Comment Post Title: ".$wpss_log_comment_data_array['comment_post_title']."\n";
			$wpss_log_comment_data .= "Comment Post URL: ".$wpss_log_comment_data_array['comment_post_url']."\n";
			$wpss_log_comment_data .= "Comment Author: ".$wpss_log_comment_data_array['comment_author']."\n";
			//$wpss_log_comment_data .= "comment_author_email: ".$comment_author_email_obfuscated." [Obfuscated for Privacy]\n";
			$wpss_log_comment_data .= "Comment Author Email: ".$comment_author_email."\n";
			$wpss_log_comment_data .= "Comment Author URL: ".$wpss_log_comment_data_array['comment_author_url']."\n";
			$wpss_log_comment_data .= "Comment Content: "."\n".$wpss_log_comment_data_array['comment_content']."\n";
			$wpss_log_comment_data .= "Comment Type: ";
			if ( $wpss_log_comment_data_array['comment_type'] ) {
				$wpss_log_comment_data .= $wpss_log_comment_data_array['comment_type'];
				}
			else {
				$wpss_log_comment_data .= "comment";
				}
			}
		else if ( $wpss_log_comment_type == 'contact form' ) {
			$wpss_log_comment_data .= $wpss_log_datum."\n";			
			$wpss_log_comment_data .= '----------------------------------------------------------------------------------'."\n";
			$wpss_log_comment_data .= $wpss_log_contact_form_data;
			$wpss_log_comment_data .= '----------------------------------------------------------------------------------'."\n";
			}
		
		//Sanitize the rest, but then again, this IS the LOG
		$ipProxyVIA = sanitize_text_field($ipProxyVIA);
		$MaskedIP = sanitize_text_field($MaskedIP);
		$wpss_http_accept_language = sanitize_text_field($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$wpss_http_accept = sanitize_text_field($_SERVER['HTTP_ACCEPT']);
		$wpss_http_user_agent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
		$wpss_http_referer = esc_url_raw($_SERVER['HTTP_REFERER']);

		$wpss_log_comment_data .= "\n";
		//$wpss_log_comment_data .= "IP Address: ".$submitter_ip_address_obfuscated." [Obfuscated for Privacy]\n";
		$wpss_log_comment_data .= "IP Address: ".$submitter_ip_address."\n";
		//Some servers don't automatically lookup REMOTE_HOST, for speed. Looking up manually is slow.
		//$wpss_log_comment_data .= "Remote Host: ".$submitter_remote_host."\n";
		$wpss_log_comment_data .= "Reverse DNS: ".$ReverseDNS."\n";
		$wpss_log_comment_data .= "Reverse DNS IP: ".$ReverseDNSIP."\n";
		$wpss_log_comment_data .= "Reverse DNS Authenticity: ".$ReverseDNSAuthenticity."\n";
		$wpss_log_comment_data .= "Proxy Info: ".$ipProxy."\n";
		$wpss_log_comment_data .= "Proxy Data: ".$ipProxyData."\n";
		$wpss_log_comment_data .= "Proxy Status: ".$ProxyStatus."\n";
		if ( $ipProxyVIA ) {
			$wpss_log_comment_data .= "HTTP_VIA: ".$ipProxyVIA."\n";
			}
		if ( $MaskedIP && ( $MaskedIP != '[No Data]' ) ) {
			$wpss_log_comment_data .= "HTTP_X_FORWARDED_FOR: ".$MaskedIP."\n";
			}
		$wpss_log_comment_data .= "HTTP_ACCEPT_LANGUAGE: ".$wpss_http_accept_language."\n";
		$wpss_log_comment_data .= "HTTP_ACCEPT: ".$wpss_http_accept."\n";
		$wpss_log_comment_data .= "User-Agent: ".$wpss_http_user_agent."\n";
		$wpss_log_comment_data .= $wpss_log_comment_type_ucwords." Processor Referrer: ";
		if ( $wpss_http_referer ) {
			$wpss_log_comment_data .= $wpss_http_referer;
			}
		else {
			$wpss_log_comment_data .= '[None]';
			}
		$wpss_log_comment_data .= "\n";
		$wpss_log_comment_data .= "JavaScript Page Referrer: ";
		if ( $wpss_javascript_page_referrer ) {
			$wpss_log_comment_data .= $wpss_javascript_page_referrer;
			}
		else {
			$wpss_log_comment_data .= '[None]';
			}
		$wpss_log_comment_data .= "\n";
		
		/*
		// Removed for caching
		$wpss_log_comment_data .= "PHP Page Referrer: ";
		if ( $wpss_php_page_referrer ) {
			$wpss_log_comment_data .= $wpss_php_page_referrer;
			}
		else {
			$wpss_log_comment_data .= '[None]';
			}
		$wpss_log_comment_data .= "\n";
		*/
		

		$wpss_log_comment_data .= "JSONST: ";
		if ( $wpss_jsonst ) {
			$wpss_log_comment_data .= $wpss_jsonst;
			}
		else {
			$wpss_log_comment_data .= '[None]';
			}
		$wpss_log_comment_data .= "\n";
		if ( $wpss_log_comment_data_errors == 'No Error' ) {
			$wpss_log_comment_data_errors_count = 0;
			}
		else {
			$wpss_log_comment_data_errors_count = spamshield_count_words($wpss_log_comment_data_errors);
			}
		if ( !$wpss_log_comment_data_errors ) { $wpss_log_comment_data_errors = '[None]'; }
		$wpss_log_comment_data .= "Failed Tests: ".$wpss_log_comment_data_errors_count."\n";
		$wpss_log_comment_data .= "Failed Test Codes: ".$wpss_log_comment_data_errors."\n";
		$wpss_log_comment_data .= ":: ".$wpss_log_comment_type_display." END ::"."\n\n";
		
		@$wpss_log_fp = fopen( $wpss_log_file,'a+' );
		@fwrite( $wpss_log_fp, $wpss_log_comment_data );
		@fclose( $wpss_log_fp );
		}
	}

function spamshield_content_addendum($content) {
	if ( !is_feed() && !is_page() && !is_home() ) {	
		$spamshield_options = get_option('spamshield_options');
		if ( ($_COOKIE[$spamshield_options['cookie_validation_name']] != $spamshield_options['cookie_validation_key'] && $spamshield_options['use_alt_cookie_method'] ) || $spamshield_options['use_alt_cookie_method_only'] ) {
			$user_agent_lc = strtolower($_SERVER['HTTP_USER_AGENT']);
			if ( strpos( $user_agent_lc, 'opera' ) === false ) {
				$wpss_img_p_disp = ' style="clear:both;display:none;"';
				$wpss_img_disp = 'display:none;';
				}
			else { 
				$wpss_img_p_disp = ' style="clear:both;"';
				$wpss_img_disp = '';
				}	
			update_option( 'ak_count_pre', get_option('akismet_spam_count') );
			$content .=  '<span'.$wpss_img_p_disp.'><img src="'.WPSS_PLUGIN_IMG_URL.'/img.php" width="0" height="0" alt="" style="border-style:none;width:0px;height:0px;'.$wpss_img_disp.'" /></span>';
			}	
		}
	return $content;
	}

function spamshield_comment_form() {

	$spamshield_options = get_option('spamshield_options');
	
	$PromotePluginLink = $spamshield_options['promote_plugin_link'];
	$FormValidationFieldJS 		= $spamshield_options['form_validation_field_js'];
	$FormValidationKeyJS 		= $spamshield_options['form_validation_key_js'];
	
	if ( $PromotePluginLink ) {
		$server_ip_first_char = substr(WPSS_SERVER_ADDR, 0, 1);
		$server_ip_fifth_char = substr(WPSS_SERVER_ADDR, 4, 1);
		if ( $server_ip_first_char == '6' ) {
			echo '<p style="font-size:9px;clear:both;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >Spam Blocking</a> by WP-SpamShield</p>'."\n";
			}
		else if ( $server_ip_first_char == '7' || $server_ip_first_char == '2' ) {
			echo '<p style="font-size:9px;clear:both;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >Anti-Spam</a> by WP-SpamShield</p>'."\n";
			}
		else if ( $server_ip_first_char == '8' ) {
			echo '<p style="font-size:9px;clear:both;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >Comment Spam Protection</a> by WP-SpamShield</p>'."\n";
			}
		else if ( $server_ip_first_char == '9' ) {
			echo '<p style="font-size:9px;clear:both;">Spam Protection by <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >WP-SpamShield Plugin</a></p>'."\n";
			}
		else if ( $server_ip_fifth_char == '5' ) {
			echo '<p style="font-size:9px;clear:both;"><a href="http://wordpress.org/extend/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >Anti-Spam Protection</a> by WP-SpamShield</p>'."\n";
			}
		else if ( $server_ip_fifth_char == '4' ) {
			echo '<p style="font-size:9px;clear:both;"><a href="http://wordpress.org/extend/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >Spam Defense</a> by WP-SpamShield</p>'."\n";
			}
		else {
			echo '<p style="font-size:9px;clear:both;">Spam Protection by <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >WP-SpamShield</a></p>'."\n";
			}
		}
	
	if ( !$spamshield_options['use_alt_cookie_method'] && !$spamshield_options['use_alt_cookie_method_only'] ) {
		echo '<noscript><p><strong>Currently you have JavaScript disabled. In order to post comments, please make sure JavaScript and Cookies are enabled, and reload the page.</strong> <a href="http://enable-javascript.com/" rel="nofollow external" >Click here for instructions</a> on how to enable JavaScript in your browser.</p></noscript>'."\n";	
		}
	// If need to add anything else to comment area, start here
	
	echo '<input type="hidden" name="'.$FormValidationFieldJS.'" value="'.$FormValidationKeyJS .'" />'."\n";
	}

function spamshield_contact_shortcode( $attr = NULL ) {
	/* Implementation: [spamshieldcontact] */
	$shortcode_check = 'shortcode';
	$content_new_shortcode = spamshield_contact_form($content,$shortcode_check);
	
	return $content_new_shortcode;
	}
	
function spamshield_contact_form( $content, $shortcode_check = NULL ) {
	
	$spamshield_contact_repl_text = array( '<!--spamshield-contact-->', '<!--spamfree-contact-->' );

	$spamshield_contact_form_url = $_SERVER['REQUEST_URI'];
	$spamshield_contact_form_url_lc = strtolower($spamshield_contact_form_url);
	if ( $_SERVER['QUERY_STRING'] ) {
		$spamshield_contact_form_query_op = '&amp;';
		}
	else {
		$spamshield_contact_form_query_op = '?';
		}
	$spamshield_contact_form_content = '';
	if ( is_page() && ( !is_home() && !is_feed() && !is_archive() && !is_search() && !is_404() ) ) {

		$spamshield_options				= get_option('spamshield_options');
		$CookieValidationName  			= $spamshield_options['cookie_validation_name'];
		$CookieValidationKey 			= $spamshield_options['cookie_validation_key'];
		$WPContactValidationJS 			= $_COOKIE[$CookieValidationName];
		$FormIncludeWebsite				= $spamshield_options['form_include_website'];
		$FormRequireWebsite				= $spamshield_options['form_require_website'];
		$FormIncludePhone				= $spamshield_options['form_include_phone'];
		$FormRequirePhone				= $spamshield_options['form_require_phone'];
		$FormIncludeCompany				= $spamshield_options['form_include_company'];
		$FormRequireCompany				= $spamshield_options['form_require_company'];
		$FormIncludeDropDownMenu		= $spamshield_options['form_include_drop_down_menu'];
		$FormRequireDropDownMenu		= $spamshield_options['form_require_drop_down_menu'];
		$FormDropDownMenuTitle			= $spamshield_options['form_drop_down_menu_title'];
		$FormDropDownMenuItem1			= $spamshield_options['form_drop_down_menu_item_1'];
		$FormDropDownMenuItem2			= $spamshield_options['form_drop_down_menu_item_2'];
		$FormDropDownMenuItem3			= $spamshield_options['form_drop_down_menu_item_3'];
		$FormDropDownMenuItem4			= $spamshield_options['form_drop_down_menu_item_4'];
		$FormDropDownMenuItem5			= $spamshield_options['form_drop_down_menu_item_5'];
		$FormDropDownMenuItem6			= $spamshield_options['form_drop_down_menu_item_6'];
		$FormDropDownMenuItem7			= $spamshield_options['form_drop_down_menu_item_7'];
		$FormDropDownMenuItem8			= $spamshield_options['form_drop_down_menu_item_8'];
		$FormDropDownMenuItem9			= $spamshield_options['form_drop_down_menu_item_9'];
		$FormDropDownMenuItem10			= $spamshield_options['form_drop_down_menu_item_10'];
		$FormMessageWidth				= $spamshield_options['form_message_width'];
		$FormMessageHeight				= $spamshield_options['form_message_height'];
		$FormMessageMinLength			= $spamshield_options['form_message_min_length'];
		$FormMessageRecipient			= $spamshield_options['form_message_recipient'];
		$FormResponseThankYouMessage	= $spamshield_options['form_response_thank_you_message'];
		$FormIncludeUserMeta			= $spamshield_options['form_include_user_meta'];
		$FormIncludeUserMetaHideExtData = $spamshield_options['hide_extra_data'];
		$PromotePluginLink				= $spamshield_options['promote_plugin_link'];
		
		if ( $FormMessageWidth < 40 ) {
			$FormMessageWidth = 40;
			}
		if ( $FormMessageHeight < 5 ) {
			$FormMessageHeight = 5;
			}
		else if ( !$FormMessageHeight ) {
			$FormMessageHeight = 10;
			}
		if ( $FormMessageMinLength < 15 ) {
			$FormMessageMinLength = 15;
			}
		else if ( !$FormMessageMinLength ) {
			$FormMessageMinLength = 25;
			}
		if ( $_GET['form'] == 'response' ) {
		
			// PROCESSING CONTACT FORM - BEGIN
			$wpss_contact_name 				= sanitize_text_field($_POST['wpss_contact_name']);
			$wpss_contact_email 			= sanitize_email($_POST['wpss_contact_email']);
			$wpss_contact_website 			= esc_url_raw($_POST['wpss_contact_website']);
			$wpss_contact_phone 			= sanitize_text_field($_POST['wpss_contact_phone']);
			$wpss_contact_company 			= sanitize_text_field($_POST['wpss_contact_company']);
			$wpss_contact_drop_down_menu	= sanitize_text_field($_POST['wpss_contact_drop_down_menu']);
			$wpss_contact_subject 			= sanitize_text_field($_POST['wpss_contact_subject']);
			$wpss_contact_message 			= sanitize_text_field($_POST['wpss_contact_message']);
			/*
			$wpss_contact_cc 				= Trim(stripslashes(strip_tags($_POST['wpss_contact_cc'])));
			*/
		
			// Add New Tests for Logging - BEGIN
			if( $_POST['ref2xJS'] && ( $_POST['ref2xJS'] != '' ) ) {
				$ref2xJS = strtolower( addslashes( urldecode( $_POST['ref2xJS'] ) ) );
				$ref2xJS = str_replace( '%3a', ':', $ref2xJS );
				$ref2xJS = str_replace( ' ', '+', $ref2xJS );
				$wpss_javascript_page_referrer = esc_url_raw( $ref2xJS );
				}
			else {
				$wpss_javascript_page_referrer = '[None]';
				}
			
			/*
			// Removed for caching
			if( $_POST['ref2xPH'] && ( $_POST['ref2xPH'] != '' ) ) {
				$ref2xPH = strtolower( addslashes( urldecode( $_POST['ref2xPH'] ) ) );
				$ref2xPH = str_replace( '%3a', ':', $ref2xPH );
				$wpss_php_page_referrer = esc_url_raw( $ref2xPH );
				}
			else {
				$wpss_php_page_referrer = '[None]';
				}
			*/
		
			if( $_POST['JSONST'] == 'NS2' ) {
				$wpss_jsonst = $_POST['JSONST'];
				}
			else {
				$wpss_jsonst = '[None]';
				}
			
			$commentdata['javascript_page_referrer']	= $wpss_javascript_page_referrer;
			//$commentdata['php_page_referrer']			= $wpss_php_page_referrer;
			$commentdata['jsonst']						= $wpss_jsonst;
			// Add New Tests for Logging - END

			// PROCESSING CONTACT FORM - END
			
			/*
			if ( !$wpss_contact_cc ) {
				$wpss_contact_cc ='No';
				}
			*/
			
			// FORM INFO - BEGIN
			
			if ( $FormMessageRecipient ) {
				$wpss_contact_form_to			= $FormMessageRecipient;
				}
			else {
				$wpss_contact_form_to 			= get_option('admin_email');
				}
			//$wpss_contact_form_cc_to 			= $wpss_contact_email;
			$wpss_contact_form_to_name 			= $wpss_contact_form_to;
			//$wpss_contact_form_cc_to_name 		= $wpss_contact_name;
			$wpss_contact_form_subject 			= '[Website Contact] '.$wpss_contact_subject;
			//$wpss_contact_form_cc_subject		= '[Website Contact CC] '.$wpss_contact_subject;
			$wpss_contact_form_msg_headers 		= "From: $wpss_contact_name <$wpss_contact_email>" . "\r\n" . "Reply-To: $wpss_contact_email" . "\r\n" . "Content-Type: text/plain\r\n";
			$wpss_contact_form_blog				= WPSS_SITE_URL;
			// Another option: "Content-Type: text/html"
			
			// FORM INFO - END
			
			// TEST TO PREVENT CONTACT FORM SPAM - BEGIN
			
			$ip 						= $_SERVER['REMOTE_ADDR'];
			$ip_regex 					= preg_quote($ip);
			$ip_lc						= strtolower($ip);
			$ip_lc_regex 				= preg_quote($ip_lc);
			$ReverseDNS 				= gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$ReverseDNSIP 				= gethostbyname($ReverseDNS);
			$ReverseDNSIP_regex 		= preg_quote($ReverseDNSIP);
			$ReverseDNS 				= sanitize_text_field($ReverseDNS);
			$ReverseDNS_LC 				= strtolower($ReverseDNS);
			$ReverseDNS_LC_regex 		= preg_quote($ReverseDNS_LC);
			$ReverseDNS_LC_Rev 			= strrev($ReverseDNS_LC);
			$ReverseDNS_LC_Rev_regex 	= preg_quote($ReverseDNS_LC_Rev);
			$wpss_contact_message_lc 	= strtolower($wpss_contact_message);
			
			if ( $WPContactValidationJS != $CookieValidationKey ) { // Check for Cookie
				$JSCookieFail=1;
				$spamshield_error_code .= ' CONTACTFORM-COOKIEFAIL';
				}
				
			// ERROR CHECKING
			
			$contact_form_spam_1_count = substr_count( $wpss_contact_message_lc, 'link');
			$contact_form_spam_1_limit = 7;
			$contact_form_spam_2_count = substr_count( $wpss_contact_message_lc, 'link building');
			$contact_form_spam_2_limit = 3;
			$contact_form_spam_3_count = substr_count( $wpss_contact_message_lc, 'link exchange');
			$contact_form_spam_3_limit = 2;
			$contact_form_spam_4_count = substr_count( $wpss_contact_message_lc, 'link request');
			$contact_form_spam_4_limit = 1;
			$contact_form_spam_5_count = substr_count( $wpss_contact_message_lc, 'link building service');
			$contact_form_spam_5_limit = 2;
			$contact_form_spam_6_count = substr_count( $wpss_contact_message_lc, 'link building experts india');
			$contact_form_spam_6_limit = 0;
			$contact_form_spam_7_count = substr_count( $wpss_contact_message_lc, 'india');
			$contact_form_spam_7_limit = 1;
			$contact_form_spam_8_count = substr_count( $wpss_contact_message_lc, 'can you outsource some seo business to us? we will work according to you and your clients and for a long term relationship we can start our SEO services in only $99 per month per website. looking forward for your positive reply');
			$contact_form_spam_8_limit = 0;
			$contact_form_spam_9_count = substr_count( $wpss_contact_message_lc, 'can you outsource some seo business to us');
			$contact_form_spam_9_limit = 0;
			$contact_form_spam_10_count = substr_count( $wpss_contact_message_lc, 'outsource some seo business');
			$contact_form_spam_10_limit = 0;
			$contact_form_spam_11_count = substr_count( $wpss_contact_message_lc, 'hit4hit.org');
			$contact_form_spam_11_limit = 1;
			$contact_form_spam_12_count = substr_count( $wpss_contact_message_lc, 'traffic exchange');
			$contact_form_spam_12_limit = 1;
			
			$wpss_contact_subject_lc = strtolower( $wpss_contact_subject );
			$contact_form_spam_subj_1_count = substr_count( $wpss_contact_subject_lc, 'link request');
			$contact_form_spam_subj_1_limit = 0;
			$contact_form_spam_subj_2_count = substr_count( $wpss_contact_subject_lc, 'link exchange');
			$contact_form_spam_subj_2_limit = 0;
			$contact_form_spam_subj_3_count = substr_count( $wpss_contact_subject_lc, 'seo service $99 per month');
			$contact_form_spam_subj_3_limit = 0;
			$contact_form_spam_subj_4_count = substr_count( $wpss_contact_subject_lc, 'seo services $99 per month');
			$contact_form_spam_subj_4_limit = 0;
			$contact_form_spam_subj_5_count = substr_count( $wpss_contact_subject_lc, 'seo services @ $99 per month');
			$contact_form_spam_subj_5_limit = 0;
			
			$wpss_contact_email_lc = strtolower( $wpss_contact_email  );
			$contact_form_spam_email_1_count = substr_count( $wpss_contact_email_lc, 'ranksindia.');
			$contact_form_spam_email_1_limit = 0;
			$contact_form_spam_email_2_count = substr_count( $wpss_contact_email_lc, 'ranksdigitalmedia.');
			$contact_form_spam_email_2_limit = 0;
			$contact_form_spam_email_3_count = substr_count( $wpss_contact_email_lc, 'semmiami.');
			$contact_form_spam_email_3_limit = 0;
			$contact_form_spam_email_4_count = substr_count( $wpss_contact_email_lc, 'globaldata4u.com');
			$contact_form_spam_email_4_limit = 0;
			
			$wpss_contact_website_lc = strtolower( $wpss_contact_website  );
			$contact_form_spam_website_1_count = substr_count( $wpss_contact_website_lc, 'ranksindia.');
			$contact_form_spam_website_1_limit = 0;
			$contact_form_spam_website_2_count = substr_count( $wpss_contact_website_lc, 'ranksdigitalmedia.');
			$contact_form_spam_website_2_limit = 0;
			$contact_form_spam_website_3_count = substr_count( $wpss_contact_website_lc, 'semmiami.');
			$contact_form_spam_website_3_limit = 0;
			$contact_form_spam_website_4_count = substr_count( $wpss_contact_website_lc, 'hit4hit.org');
			$contact_form_spam_website_4_limit = 0;
			$contact_form_spam_website_5_count = substr_count( $wpss_contact_website_lc, 'globaldata4u.com');
			$contact_form_spam_website_5_limit = 0;
			
			$contact_form_spam_term_total = $contact_form_spam_1_count + $contact_form_spam_2_count + $contact_form_spam_3_count + $contact_form_spam_4_count + $contact_form_spam_7_count + $contact_form_spam_10_count + $contact_form_spam_11_count + $contact_form_spam_12_count + $contact_form_spam_subj_1_count + $contact_form_spam_subj_2_count + $contact_form_spam_subj_3_count + $contact_form_spam_subj_4_count + $contact_form_spam_subj_5_count;
			$contact_form_spam_term_total_limit = 15;
			
			if ( strpos( $ReverseDNS_LC_Rev, 'ni.' ) === 0 || strpos( $ReverseDNS_LC_Rev, 'ur.' ) === 0 || strpos( $ReverseDNS_LC_Rev, 'kp.' ) === 0 || strpos( $ReverseDNS_LC_Rev, 'nc.' ) === 0 || strpos( $ReverseDNS_LC_Rev, 'au.' ) === 0 || strpos( $ReverseDNS_LC_Rev, 'rt.' ) === 0 || preg_match( "/^1.22.2(19|20|23)./", $ip ) ) {
				$contact_form_spam_loc_in = 1;
				}
			if ( ( $contact_form_spam_term_total > $contact_form_spam_term_total_limit || $contact_form_spam_1_count > $contact_form_spam_1_limit || $contact_form_spam_2_count > $contact_form_spam_2_limit || $contact_form_spam_5_count > $contact_form_spam_5_limit || $contact_form_spam_6_count > $contact_form_spam_6_limit || $contact_form_spam_10_count > $contact_form_spam_10_limit ) && $contact_form_spam_loc_in ) {
				$MessageSpam=1;
				$spamshield_error_code .= ' CONTACTFORM-MESSAGESPAM1';
				$contact_response_status_message_addendum .= '&bull; Message appears to be spam. Please note that link requests, link exchange requests, and SEO outsourcing requests will be automatically deleted, and are not an acceptable use of this contact form.<br />&nbsp;<br />';
				}
			else if ( $contact_form_spam_subj_1_count > $contact_form_spam_subj_1_limit || $contact_form_spam_subj_2_count > $contact_form_spam_subj_2_limit || $contact_form_spam_subj_3_count > $contact_form_spam_subj_3_limit || $contact_form_spam_subj_4_count > $contact_form_spam_subj_4_limit || $contact_form_spam_subj_5_count > $contact_form_spam_subj_5_limit || $contact_form_spam_8_count > $contact_form_spam_8_limit || $contact_form_spam_9_count > $contact_form_spam_9_limit || $contact_form_spam_11_count > $contact_form_spam_11_limit || $contact_form_spam_12_count > $contact_form_spam_12_limit || $contact_form_spam_email_1_count > $contact_form_spam_email_1_limit || $contact_form_spam_email_2_count > $contact_form_spam_email_2_limit || $contact_form_spam_email_3_count > $contact_form_spam_email_3_limit || $contact_form_spam_email_4_count > $contact_form_spam_email_4_limit || $contact_form_spam_website_1_count > $contact_form_spam_website_1_limit || $contact_form_spam_website_2_count > $contact_form_spam_website_2_limit || $contact_form_spam_website_3_count > $contact_form_spam_website_3_limit || $contact_form_spam_website_4_count > $contact_form_spam_website_4_limit || $contact_form_spam_website_5_count > $contact_form_spam_website_5_limit ) {
				$MessageSpam=1;
				$spamshield_error_code .= ' CONTACTFORM-MESSAGESPAM2';
				$contact_response_status_message_addendum .= '&bull; Message appears to be spam. Please note that link requests, link exchange requests, and SEO outsourcing requests will be automatically deleted, and are not an acceptable use of this contact form.<br />&nbsp;<br />';
				}
			// JSONST & Referrer Scrape Test
			else {
				if ( $_POST['JSONST'] == 'NS2' ) {
					$MessageSpam=1;
					$spamshield_error_code .= ' CF-JSONST-1000';
					}
				$post_ref2xjs_lc = strtolower($_POST['ref2xJS']);
				if ( $_POST['ref2xJS'] && strpos( $post_ref2xjs_lc, 'ref2xjs' ) !== false ) {
					$MessageSpam=1;
					$spamshield_error_code .= ' CF-REF-2-1023';
					}
				if ( $MessageSpam == 1 ) {
					$contact_response_status_message_addendum .= '&bull; Message appears to be spam. Please note that link requests, link exchange requests, and SEO outsourcing requests will be automatically deleted, and are not an acceptable use of this contact form.<br />&nbsp;<br />';
					}
				}
				
			if ( !$wpss_contact_name || !$wpss_contact_email || !$wpss_contact_subject || !$wpss_contact_message || ( $FormIncludeWebsite && $FormRequireWebsite && !$wpss_contact_website ) || ( $FormIncludePhone && $FormRequirePhone && !$wpss_contact_phone ) || ( $FormIncludeCompany && $FormRequireCompany && !$wpss_contact_company ) || ( $FormIncludeDropDownMenu && $FormRequireDropDownMenu && !$wpss_contact_drop_down_menu ) ) {
				$BlankField=1;
				$spamshield_error_code .= ' CONTACTFORM-BLANKFIELD';
				$contact_response_status_message_addendum .= '&bull; At least one required field was left blank.<br />&nbsp;<br />';
				}

			if ( !is_email($wpss_contact_email) ) {
				$InvalidValue=1;
				$BadEmail=1;
				$spamshield_error_code .= ' CONTACTFORM-INVALIDVALUE-EMAIL';
				$contact_response_status_message_addendum .= '&bull; Please enter a valid email address.<br />&nbsp;<br />';
				}
			
			$wpss_contact_phone_zerofake1 = str_replace( '000-000-0000', '', $wpss_contact_phone );
			$wpss_contact_phone_zerofake2 = str_replace( '(000) 000-0000', '', $wpss_contact_phone );
			$wpss_contact_phone_zero = str_replace( '0', '', $wpss_contact_phone );
			$wpss_contact_phone_na1 = str_replace( 'N/A', '', $wpss_contact_phone );
			$wpss_contact_phone_na2 = str_replace( 'NA', '', $wpss_contact_phone );
			if ( $FormIncludePhone && $FormRequirePhone && ( !$wpss_contact_phone_zerofake1 || !$wpss_contact_phone_zerofake2 || !$wpss_contact_phone_zero || !$wpss_contact_phone_na1 || !$wpss_contact_phone_na2 ) ) {
				$InvalidValue=1;
				$BadPhone=1;
				$spamshield_error_code .= ' CONTACTFORM-INVALIDVALUE-PHONE';
				$contact_response_status_message_addendum .= '&bull; Please enter a valid phone number.<br />&nbsp;<br />';
				}
				
			$MessageLength = spamshield_strlen($wpss_contact_message);
			if ( $MessageLength < $FormMessageMinLength ) {
				$MessageShort=1;
				$spamshield_error_code .= ' CONTACTFORM-MESSAGESHORT';
				$contact_response_status_message_addendum .= '&bull; Message too short. Please enter a complete message.<br />&nbsp;<br />';
				}

			//Sanitize the rest
			$wpss_contact_form_http_accept_language = sanitize_text_field($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$wpss_contact_form_http_accept = sanitize_text_field($_SERVER['HTTP_ACCEPT']);
			$wpss_contact_form_http_user_agent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
			$wpss_contact_form_http_referer = esc_url_raw($_SERVER['HTTP_REFERER']);

			
			// MESSAGE CONTENT - BEGIN
			$wpss_contact_form_msg_1 .= "Message: "."\n";
			$wpss_contact_form_msg_1 .= $wpss_contact_message."\n";
			
			$wpss_contact_form_msg_1 .= "\n";
		
			$wpss_contact_form_msg_1 .= "Name: ".$wpss_contact_name."\n";
			$wpss_contact_form_msg_1 .= "Email: ".$wpss_contact_email."\n";
			if ( $FormIncludePhone ) {
				$wpss_contact_form_msg_1 .= "Phone: ".$wpss_contact_phone."\n";
				}
			if ( $FormIncludeCompany ) {
				$wpss_contact_form_msg_1 .= "Company: ".$wpss_contact_company."\n";
				}
			if ( $FormIncludeWebsite ) {
				$wpss_contact_form_msg_1 .= "Website: ".$wpss_contact_website."\n";
				}
			if ( $FormIncludeDropDownMenu ) {
				$wpss_contact_form_msg_1 .= $FormDropDownMenuTitle.": ".$wpss_contact_drop_down_menu."\n";
				}
			
			$wpss_contact_form_msg_2 .= "\n";
			//Check following variables to make sure not repeating
			if ( $FormIncludeUserMeta ) {
				$wpss_contact_form_msg_2 .= "\n";
				$wpss_contact_form_msg_2 .= "Website Generating This Email: ".$wpss_contact_form_blog."\n";
				$wpss_contact_form_msg_2 .= "Referrer: ".$wpss_contact_form_http_referer."\n";
				$wpss_contact_form_msg_2 .= "User-Agent (Browser/OS): ".$wpss_contact_form_http_user_agent."\n";
				$wpss_contact_form_msg_2 .= "IP Address: ".$ip."\n";
				$wpss_contact_form_msg_2 .= "Server: ".$ReverseDNS."\n";
				$wpss_contact_form_msg_2 .= "IP Address Lookup: http://whatismyipaddress.com/ip/".$ip."\n";
				// DEBUG ONLY - BEGIN
				if ( !$FormIncludeUserMetaHideExtData && strpos( WPSS_SERVER_NAME_REV, WPSS_DEBUG_SERVER_NAME_REV ) === 0 ) {
					$wpss_contact_form_msg_2 .= "------------------------------------------------------\n";
					$wpss_contact_form_msg_2 .= ":: Additional Technical Data Added by WP-SpamShield ::\n";
					$wpss_contact_form_msg_2 .= "------------------------------------------------------\n";
					$wpss_contact_form_msg_2 .= "JavaScript Page Referrer Check: ".$wpss_javascript_page_referrer."\n";
					//$wpss_contact_form_msg_2 .= "PHP Page Referrer Check: ".$wpss_php_page_referrer."\n";
					$wpss_contact_form_msg_2 .= "JavaScript Off NoScript Test: ".$wpss_jsonst."\n";
					$wpss_contact_form_msg_2 .= "Reverse DNS IP: ".$ReverseDNSIP."\n";
					if ( $_SERVER['HTTP_VIA'] ) {
						$wpss_contact_form_msg_2 .= "HTTP_VIA: ".$_SERVER['HTTP_VIA']."\n";
						}
					if ( $_SERVER['HTTP_X_FORWARDED_FOR'] ) {
						$wpss_contact_form_msg_2 .= "HTTP_X_FORWARDED_FOR: ".$_SERVER['HTTP_X_FORWARDED_FOR']."\n";
						}
					$wpss_contact_form_msg_2 .= "HTTP_ACCEPT_LANGUAGE: ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."\n";
					$wpss_contact_form_msg_2 .= "HTTP_ACCEPT: ".$_SERVER['HTTP_ACCEPT']."\n";
					}
				// DEBUG ONLY - END
				}

			$wpss_contact_form_msg_3 .= "\n";
			$wpss_contact_form_msg_3 .= "\n";
			
			$wpss_contact_form_msg = $wpss_contact_form_msg_1.$wpss_contact_form_msg_2.$wpss_contact_form_msg_3;
			$wpss_contact_form_msg_cc = $wpss_contact_form_msg_1.$wpss_contact_form_msg_3;
			// MESSAGE CONTENT - END

			if ( !$BlankField && !$InvalidValue && !$MessageShort && !$MessageSpam && !$JSCookieFail ) {  
				// SEND MESSAGE
				@wp_mail( $wpss_contact_form_to, $wpss_contact_form_subject, $wpss_contact_form_msg, $wpss_contact_form_msg_headers );								
				$contact_response_status = 'thank-you';
				$spamshield_error_code = 'No Error';
				if ( $spamshield_options['comment_logging'] && $spamshield_options['comment_logging_all'] ) {
					spamshield_log_data( $commentdata, $spamshield_error_code, 'contact form', $wpss_contact_form_msg );
					}
				}
			else {
				update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
				if ( $spamshield_options['comment_logging'] ) {
					$spamshield_error_code = ltrim($spamshield_error_code);
					spamshield_log_data( $commentdata, $spamshield_error_code, 'contact form', $wpss_contact_form_msg );
					}
				}				
			
			// TEST TO PREVENT CONTACT FORM SPAM - END
			
			$FormResponseThankYouMessageDefault = '<p>Your message was sent successfully. Thank you.</p><p>&nbsp;</p>';
			$FormResponseThankYouMessage = str_replace( "\\", "", $FormResponseThankYouMessage );
		
			if ( $contact_response_status == 'thank-you' ) {
				if ( $FormResponseThankYouMessage ) {
					$spamshield_contact_form_content .= '<p>'.$FormResponseThankYouMessage.'</p><p>&nbsp;</p>'."\n";
					}
				else {
					$spamshield_contact_form_content .= $FormResponseThankYouMessageDefault."\n";
					}
				}
			else {
				if ( strpos( $spamshield_contact_form_url_lc, '&form=response' ) !== false ) {
					$spamshield_contact_form_back_url = str_replace('&form=response','',$spamshield_contact_form_url );
					}
				else if ( strpos( $spamshield_contact_form_url_lc, '?form=response' ) !== false ) {
					$spamshield_contact_form_back_url = str_replace('?form=response','',$spamshield_contact_form_url );
					}
				if ( $MessageSpam ) {
					if ( !$spamshield_options['use_alt_cookie_method'] && !$spamshield_options['use_alt_cookie_method_only'] ) {
						$contact_response_status_message_addendum .= '<noscript><br />&nbsp;<br />&bull; Currently you have JavaScript disabled.</noscript>'."\n";
						}
					$spamshield_contact_form_content .= '<p><strong>ERROR: <br />&nbsp;<br />'.$contact_response_status_message_addendum.'</strong><p>&nbsp;</p>'."\n";
					}
				else {
					if ( !$spamshield_options['use_alt_cookie_method'] && !$spamshield_options['use_alt_cookie_method_only'] ) {
						$contact_response_status_message_addendum .= '<noscript><br />&nbsp;<br />&bull; Currently you have JavaScript disabled.</noscript>'."\n";
						}
					$spamshield_contact_form_content .= '<p><strong>ERROR: Please return to the <a href="'.$spamshield_contact_form_back_url.'" >contact form</a> and fill out all required fields.';
					if ( !$spamshield_options['use_alt_cookie_method'] && !$spamshield_options['use_alt_cookie_method_only'] ) {
						$spamshield_contact_form_content .= ' Please make sure JavaScript and Cookies are enabled in your browser.';
						}
					else if ( $spamshield_options['use_alt_cookie_method_only'] ) {
						$spamshield_contact_form_content .= ' Please make sure Images and Cookies are enabled in your browser.';
						}
					else {
						$spamshield_contact_form_content .= ' Please make sure Cookies are enabled in your browser.';
						}
					$spamshield_contact_form_content .= '<br />&nbsp;<br />'.$contact_response_status_message_addendum.'</strong><p>&nbsp;</p>'."\n";
					}

				}
			$content_new = str_replace($content, $spamshield_contact_form_content, $content);
			$content_shortcode = $spamshield_contact_form_content;
			}
		else {		
			$spamshield_contact_form_content .= '<form id="wpss_contact_form" name="wpss_contact_form" action="'.$spamshield_contact_form_url.$spamshield_contact_form_query_op.'form=response" method="post" style="text-align:left;" >'."\n";

			$spamshield_contact_form_content .= '<p><label><strong>Name</strong> *<br />'."\n";

			$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_name" name="wpss_contact_name" value="" size="40" /> </label></p>'."\n";
			$spamshield_contact_form_content .= '<p><label><strong>Email</strong> *<br />'."\n";
			$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_email" name="wpss_contact_email" value="" size="40" /> </label></p>'."\n";
			
			if ( $FormIncludeWebsite ) {
				$spamshield_contact_form_content .= '<p><label><strong>Website</strong> ';
				if ( $FormRequireWebsite ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_website" name="wpss_contact_website" value="" size="40" /> </label></p>'."\n";
				}
				
			if ( $FormIncludePhone ) {
				$spamshield_contact_form_content .= '<p><label><strong>Phone</strong> ';
				if ( $FormRequirePhone ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_phone" name="wpss_contact_phone" value="" size="40" /> </label></p>'."\n";
				}

			if ( $FormIncludeCompany ) {
				$spamshield_contact_form_content .= '<p><label><strong>Company</strong> ';
				if ( $FormRequireCompany ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_company" name="wpss_contact_company" value="" size="40" /> </label></p>'."\n";
				}

			if ( $FormIncludeDropDownMenu && $FormDropDownMenuTitle && $FormDropDownMenuItem1 && $FormDropDownMenuItem2 ) {
				$spamshield_contact_form_content .= '<p><label><strong>'.$FormDropDownMenuTitle.'</strong> ';
				if ( $FormRequireDropDownMenu ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<select id="wpss_contact_drop_down_menu" name="wpss_contact_drop_down_menu" > '."\n";
				$spamshield_contact_form_content .= '<option value="" selected="selected">Please Select</option> '."\n";
				$spamshield_contact_form_content .= '<option value="">--------------------------</option> '."\n";
				if ( $FormDropDownMenuItem1 ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem1.'">'.$FormDropDownMenuItem1.'</option> '."\n";
					}
				if ( $FormDropDownMenuItem2 ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem2.'">'.$FormDropDownMenuItem2.'</option> '."\n";
					}
				if ( $FormDropDownMenuItem3 ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem3.'">'.$FormDropDownMenuItem3.'</option> '."\n";
					}
				if ( $FormDropDownMenuItem4 ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem4.'">'.$FormDropDownMenuItem4.'</option> '."\n";
					}
				if ( $FormDropDownMenuItem5 ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem5.'">'.$FormDropDownMenuItem5.'</option> '."\n";
					}
				if ( $FormDropDownMenuItem6 ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem6.'">'.$FormDropDownMenuItem6.'</option> '."\n";
					}
				if ( $FormDropDownMenuItem7 ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem7.'">'.$FormDropDownMenuItem7.'</option> '."\n";
					}
				if ( $FormDropDownMenuItem8 ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem8.'">'.$FormDropDownMenuItem8.'</option> '."\n";
					}
				if ( $FormDropDownMenuItem9 ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem9.'">'.$FormDropDownMenuItem9.'</option> '."\n";
					}
				if ( $FormDropDownMenuItem10 ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem10.'">'.$FormDropDownMenuItem10.'</option> '."\n";
					}
				$spamshield_contact_form_content .= '</select> '."\n";
				$spamshield_contact_form_content .= '</label></p>'."\n";
				}
			
			$spamshield_contact_form_content .= '<p><label><strong>Subject</strong> *<br />'."\n";
    		$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_subject" name="wpss_contact_subject" value="" size="40" /> </label></p>'."\n";			

			$spamshield_contact_form_content .= '<p><label><strong>Message</strong> *<br />'."\n";
			$spamshield_contact_form_content .= '<textarea id="wpss_contact_message" name="wpss_contact_message" cols="'.$FormMessageWidth.'" rows="'.$FormMessageHeight.'"></textarea> </label></p>'."\n";
			$spamshield_contact_form_content .= "<script type='text/javascript'>\n";
			$spamshield_contact_form_content .= '// <![CDATA['."\n";
			$spamshield_contact_form_content .= "ref2xJS = escape( document[ 'referrer' ] );\n";
			$spamshield_contact_form_content .= "document.write(\"<input type='hidden' name='ref2xJS' value='\"+ref2xJS+\"'>\");\n";
			$spamshield_contact_form_content .= '// ]]>'."\n";
			$spamshield_contact_form_content .= '</script>'."\n";
			//Removing for caching
			//$ref2xPH = esc_url_raw( $_SERVER['HTTP_REFERER'] );
			//$spamshield_contact_form_content .= '<input type="hidden" name="ref2xPH" value="'.$ref2xPH.'">'."\n";
			$spamshield_contact_form_content .= '<noscript><input type="hidden" name="JSONST" value="NS2"></noscript>'."\n";			
			
			if ( ( !$spamshield_options['use_alt_cookie_method'] && !$spamshield_options['use_alt_cookie_method_only'] ) ) {
				$spamshield_contact_form_content .= '<noscript><p><strong>Currently you have JavaScript disabled. In order to use this contact form, please make sure JavaScript and Cookies are enabled, and reload the page.</strong> <a href="http://enable-javascript.com/" rel="nofollow external" >Click here for instructions</a> on how to enable JavaScript in your browser.</p></noscript>'."\n";		
				}

			$spamshield_contact_form_content .= '<p><input type="submit" id="wpss_contact_submit" name="wpss_contact_submit" value="Send Message" /></p>'."\n";

			$spamshield_contact_form_content .= '<p>* Required Field</p>'."\n";
			$spamshield_contact_form_content .= '<p>&nbsp;</p>'."\n";
			
			if ( $PromotePluginLink ) {
				$server_ip_first_char = substr(WPSS_SERVER_ADDR, 0, 1);
				if ( $server_ip_first_char == '7' ) {
					$spamshield_contact_form_content .= '<p style="font-size:9px;clear:both;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield Contact Form for WordPress" >Contact Form</a> Powered by WP-SpamShield</p>'."\n";
					}
				else if ( $server_ip_first_char == '6' ) {
					$spamshield_contact_form_content .= '<p style="font-size:9px;clear:both;">Powered by <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield Contact Form for WordPress" >WP-SpamShield Contact Form</a></p>'."\n";
					}
				else {
					$spamshield_contact_form_content .= '<p style="font-size:9px;clear:both;">Contact Form Powered by <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield Contact Form for WordPress" >WP-SpamShield</a></p>'."\n";
					}
				$spamshield_contact_form_content .= '<p>&nbsp;</p>'."\n";
				}
			$spamshield_contact_form_content .= '</form>'."\n";
			
			if ( ($_COOKIE[$spamshield_options['cookie_validation_name']] != $spamshield_options['cookie_validation_key'] && $spamshield_options['use_alt_cookie_method'] ) || $spamshield_options['use_alt_cookie_method_only'] ) {
				$user_agent_lc = strtolower($_SERVER['HTTP_USER_AGENT']);
				if ( strpos( $user_agent_lc, 'opera' ) !== false ) { 
					$wpss_img_p_disp = ' style="clear:both;display:none;"';
					$wpss_img_disp = 'display:none;';
					}
				else { 
					$wpss_img_p_disp = ' style="clear:both;"';
					$wpss_img_disp = ''; 
					}	
				update_option( 'ak_count_pre', get_option('akismet_spam_count') );

				$spamshield_contact_form_content .=  '<span'.$wpss_img_p_disp.'><img src="'.WPSS_PLUGIN_IMG_URL.'/img.php" width="0" height="0" alt="" style="border-style:none;width:0px;height:0px;'.$wpss_img_disp.'" /></span>';
				}	
			
			$contact_form_blacklist_status = '';
			$spamshield_contact_form_ip_bans = array(
													'66.60.98.1',
													'67.227.135.200',
													'74.86.148.194',
													'77.92.88.13',
													'77.92.88.27',
													'78.129.202.15',
													'78.129.202.2',
													'78.157.143.202',
													'87.106.55.101',
													'91.121.77.168',
													'92.241.176.200',
													'92.48.122.2',
													'92.48.122.3',
													'92.48.65.27',
													'92.241.168.216',
													'115.42.64.19',
													'116.71.33.252',
													'116.71.35.192',
													'116.71.59.69',
													'122.160.70.94',
													'122.162.251.167',
													'123.237.144.189',
													'123.237.144.92',
													'123.237.147.71',
													'193.37.152.242',
													'193.46.236.151',
													'193.46.236.152',
													'193.46.236.234',
													'194.44.97.14',
													);
			// Check following variables to make sure not repeating										
			$commentdata_remote_addr_lc = strtolower($_SERVER['REMOTE_ADDR']);
			$commentdata_remote_addr_lc_rev = strrev($commentdata_remote_addr_lc);
			if ( $_SERVER['REMOTE_HOST'] ) {
				$commentdata_remote_host_lc = strtolower($_SERVER['REMOTE_HOST']);
				}
			else {
				$commentdata_remote_host_lc = strtolower($ReverseDNS);
				}
			$commentdata_remote_host_lc_rev = strrev($commentdata_remote_host_lc);
			if ( in_array( $commentdata_remote_addr_lc, $spamshield_contact_form_ip_bans ) || strpos( $commentdata_remote_addr_lc, '78.129.202.' ) === 0 || preg_match( "/^123\.237\.14([47])\./", $commentdata_remote_addr_lc ) || preg_match( "/^194\.8\.7([45])\./", $commentdata_remote_addr_lc ) || strpos( $commentdata_remote_addr_lc, '193.37.152.' ) === 0 || strpos( $commentdata_remote_addr_lc, '193.46.236.' ) === 0 || preg_match( "/^92\.48\.122\.([0-9]|[12][0-9]|3[01])$/", $commentdata_remote_addr_lc ) || strpos( $commentdata_remote_addr_lc, '116.71.' ) === 0 || ( strpos( $commentdata_remote_addr_lc, '192.168.' ) === 0 && strpos( WPSS_SERVER_ADDR, '192.168.' ) !== 0 && strpos( WPSS_SERVER_NAME, 'localhost' ) === false ) ) {
				// 194.8.74.0 - 194.8.75.255 BAD spam network - BRITISH VIRGIN ISLANDS
				// 193.37.152.0 - 193.37.152.255 SPAM NETWORK - WEB HOST, NOT ISP - GERMANY
				// 193.46.236.0 - 193.46.236.255 SPAM NETWORK - WEB HOST, NOT ISP - LATVIA
				// 92.48.122.0 - 92.48.122.31 SPAM NETWORK - SERVERS, NOT ISP - BELGRADE
				// KeywordSpy caught using IP's in the range 123.237.144. and 123.237.147.
				// 91.121.77.168 real-url.org
				// 92.48.122.0 - 92.48.122.31 SPAM NETWORK - SERVERS, NOT ISP - BELGRADE
				
				// 87.106.55.101 SPAM NETWORK - SERVERS, NOT ISP - (.websitehome.co.uk)
				// 74.86.148.194 SPAM NETWORK - WEB HOST, NOT ISP (rover-host.com)
				// 67.227.135.200 SPAM NETWORK - WEB HOST, NOT ISP (host.lotosus.com)
				// 66.60.98.1 SPAM NETWORK - WEB SITE/HOST, NOT ISP - (rdns.softwiseonline.com)
				// 116.71.0.0 - 116.71.255.255 - SPAM NETWORK - PAKISTAN - Ptcl Triple Play Project
				// 194.44.97.14 , arkada.rovno.ua - SPAM NETWORK
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-IP1002';
				}
			// Test Reverse DNS Hosts - Do all with Reverse DNS moving forward
			if ( strpos( $ReverseDNS_LC, 'keywordspy.com' ) !== false ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1023';
				}
			if ( preg_match( "@clients\.your\-server\.de$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1024';
				}
			if ( preg_match( "@^rover\-host\.com$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1025';
				}
			if ( preg_match( "@^host\.lotosus\.com$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1026';
				}
			if ( preg_match( "@^rdns\.softwiseonline\.com$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1027';
				}
			if ( preg_match( "@^s([a-z0-9]+)\.websitehome\.co\.uk$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1028';
				}
			if ( preg_match( "@\.opentransfer\.com$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1029';
				}
			if ( preg_match( "@arkada\.rovno\.ua$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1030';
				}
		
			// Servers that dish out a **LOT** of spam
			
			// HOSTS NOT ISP, so all hits are from machines
			// VERIFIED SPAMMERS + HACKERS - VERY BAD REPUTATIONS
			if ( preg_match( "@^(host?|v)([0-9]+)\.server([0-9]+)\.vpn(999|2buy)\.com$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1031';
				}
			if ( preg_match( "@^(ip-)?([0-9]{1,3})-([0-9]{1,3})-([0-9]{1,3})-([0-9]{1,3})\.(rackcentre\.redstation\.net\.uk|static\.hostnoc\.net|ip\.idealhosting\.net\.tr|triolan\.net|chunkhost\.com|unknown\.steephost\.(net|com))$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1032';
				}
			if ( preg_match( "@^ns([0-9]+)\.webmasters\.com$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1033';
				}
			if ( preg_match( "@^server([0-9]+)\.(shadowbrokers|junctionmethod|([a-z0-9-]+))\.(com|net)$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1034';
				}
			if ( preg_match( "@^(hosted-by\.(ipxcore\.com|slaskdatacenter\.pl)|host\.colocrossing\.com)$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1035';
				}
			if ( preg_match( "@^$ip_lc_regex\.static\.quadranet\.com$@i", $ReverseDNS_LC ) ) {
				$content_filter_status = '2';
				$spamshield_error_code .= ' CF-REVD1036';
				}
	
			$user_agent_lc = strtolower(trim($_SERVER['HTTP_USER_AGENT']));
			$user_agent_lc_word_count = spamshield_count_words($user_agent_lc);
			if ( !$user_agent_lc ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-UA1001-0';
				}
			if ( $user_agent_lc && $user_agent_lc_word_count < 3 ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-UA1001-1';
				}
			if ( strpos( $user_agent_lc, 'libwww' ) !== false || preg_match( "/^(nutch|larbin|jakarta|java|mechanize|phpcrawl)/i", $user_agent_lc ) ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-UA1002';
				}
			if ( strpos( $user_agent_lc, 'iopus-' ) !== false ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-UA1003';
				}
			$user_http_accept = trim(strtolower($_SERVER['HTTP_ACCEPT']));
			if ( !$user_http_accept ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-HA1001';
				}
			if ( $user_http_accept == 'application/json, text/javascript, */*; q=0.01' ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-HA1002';
				}
			if ( $user_http_accept == '*' ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-HA1003';
				}
			$user_http_accept_language = trim($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			if ( !$user_http_accept_language ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-HAL1001';
				}
			if ( $user_http_accept_language == '*' ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-HAL1002';
				}

			// Add blacklist check - IP's only though.
			$wpss_cache_check 			= spamshield_check_cache_status();
			$wpss_cache_check_status	= $wpss_cache_check['cache_check_status'];

			if ( $contact_form_blacklist_status && $wpss_cache_check_status != 'ACTIVE' ) {
				$spamshield_contact_form_content = '<strong>Your location has been identified as part of a reported spam network. Contact form has been disabled to prevent spam.</strong>';
				}
			$content_new = str_replace($spamshield_contact_repl_text, $spamshield_contact_form_content, $content);
			$content_shortcode = $spamshield_contact_form_content;
			}

		}
	if ( $_GET['form'] == 'response' ) {
		$content_new = str_replace($content, $spamshield_contact_form_content, $content);
		$content_shortcode = $spamshield_contact_form_content;
		}
	else {
		$content_new = str_replace($spamshield_contact_repl_text, $spamshield_contact_form_content, $content);
		$content_shortcode = $spamshield_contact_form_content;
		}
	if ( $shortcode_check == 'shortcode' && $content_shortcode ) {
		$content_new = $content_shortcode;
		}
	return $content_new;
	}
	
function spamshield_check_comment_type($commentdata) {
	
	$spamshield_options = get_option('spamshield_options');
	
	// Add New Tests for Logging - BEGIN
	if( $_POST['ref2xJS'] && ( $_POST['ref2xJS'] != '' ) ) {
		$ref2xJS = strtolower( addslashes( urldecode( $_POST['ref2xJS'] ) ) );
		$ref2xJS = str_replace( '%3a', ':', $ref2xJS );
		$ref2xJS = str_replace( ' ', '+', $ref2xJS );
		$wpss_javascript_page_referrer = esc_url_raw( $ref2xJS );
		}
	else {
		$wpss_javascript_page_referrer = '[None]';
		}
	
	/*
	// Removed for caching
	if( $_POST['ref2xPH'] && ( $_POST['ref2xPH'] != '' ) ) {
		$ref2xPH = strtolower( addslashes( urldecode( $_POST['ref2xPH'] ) ) );
		$ref2xPH = str_replace( '%3a', ':', $ref2xPH );
		$wpss_php_page_referrer = esc_url_raw( $ref2xPH );
		}
	else {
		$wpss_php_page_referrer = '[None]';
		}
	*/

	if( $_POST['JSONST'] == 'NS1' ) {
		$wpss_jsonst = $_POST['JSONST'];
		}
	else {
		$wpss_jsonst = '[None]';
		}

	$commentdata['comment_post_title']			= get_the_title($commentdata['comment_post_ID']);
	$commentdata['comment_post_url']			= get_permalink($commentdata['comment_post_ID']);
	$commentdata['javascript_page_referrer']	= $wpss_javascript_page_referrer;
	//$commentdata['php_page_referrer']			= $wpss_php_page_referrer;
	$commentdata['jsonst']						= $wpss_jsonst;
	
	// Add New Tests for Logging - END
	
	if ( !is_admin() && !current_user_can('moderate_comments') && !current_user_can('edit_post') ) {
		// ONLY IF NOT ADMINS, EDITORS, AUTHORS - BEGIN
		$BlockAllTrackbacks 		= $spamshield_options['block_all_trackbacks'];
		$BlockAllPingbacks 			= $spamshield_options['block_all_pingbacks'];
	
		$content_short_status		= spamshield_content_short($commentdata);
			
		if ( !$content_short_status ) {
			$content_filter_status 	= spamshield_content_filter($commentdata);
			}
		
		if ( $content_short_status ) {
			add_filter('pre_comment_approved', 'spamshield_denied_post_short', 1);
			}
		else if ( $content_filter_status == '2' ) {
			add_filter('pre_comment_approved', 'spamshield_denied_post_content_filter', 1);
			}
		else if ( $content_filter_status == '10' ) {
			add_filter('pre_comment_approved', 'spamshield_denied_post_proxy', 1);
			}
		else if ( $content_filter_status == '100' ) {
			add_filter('pre_comment_approved', 'spamshield_denied_post_wp_blacklist', 1);
			}
		else if ( $content_filter_status ) {
			add_filter('pre_comment_approved', 'spamshield_denied_post', 1);
			}	
		else if ( ( $commentdata['comment_type'] != 'trackback' && $commentdata['comment_type'] != 'pingback' ) || ( $BlockAllTrackbacks && $BlockAllPingbacks ) || ( $BlockAllTrackbacks && $commentdata['comment_type'] == 'trackback' ) || ( $BlockAllPingbacks && $commentdata['comment_type'] == 'pingback' ) ) {
			// If Comment is not a trackback or pingback, or 
			// Trackbacks and Pingbacks are blocked, or 
			// Trackbacks are blocked and comment is Trackback, or 
			// Pingbacks are blocked and comment is Pingback
			add_filter('pre_comment_approved', 'spamshield_allowed_post', 1);

			// LOG DATA - BEGIN
			if ( $spamshield_options['comment_logging'] ) {
				$CookieValidationName  		= $spamshield_options['cookie_validation_name'];
				$CookieValidationKey 		= $spamshield_options['cookie_validation_key'];
				$FormValidationFieldJS 		= $spamshield_options['form_validation_field_js'];
				$FormValidationKeyJS 		= $spamshield_options['form_validation_key_js'];
				$WPCommentValidationJS 		= $_COOKIE[$CookieValidationName];
				$WPFormValidationPost 		= $_POST[$FormValidationFieldJS]; //Comments Post Verification
				$wpss_cache_check 			= spamshield_check_cache_status();
				$wpss_cache_check_status	= $wpss_cache_check['cache_check_status'];
				if ( $WPFormValidationPost == $FormValidationKeyJS || $wpss_cache_check_status == 'ACTIVE' ) {
					$FormValidationFieldJSTest = 'PASS';
					}
				if ( $commentdata['comment_type'] != 'trackback' && $commentdata['comment_type'] != 'pingback' && $WPCommentValidationJS != $CookieValidationKey ) {
					$spamshield_error_code .= ' COOKIE';
					}
				if ( $commentdata['comment_type'] != 'trackback' && $commentdata['comment_type'] != 'pingback' && $FormValidationFieldJSTest != 'PASS' ) {
					$spamshield_error_code .= ' FVFJS';
					}
				if ( $BlockAllTrackbacks && $commentdata['comment_type'] == 'trackback' ) {
					$spamshield_error_code .= ' BLOCKING-TRACKBACKS ';
					}
				if ( $BlockAllPingbacks && $commentdata['comment_type'] == 'pingback' ) {
					$spamshield_error_code .= ' BLOCKING-PINGBACKS';
					}
				if ( !$spamshield_error_code ) {
					$spamshield_error_code = 'No Error';
					}
				if ( $spamshield_options['comment_logging_all'] ) {
					$spamshield_error_code = ltrim($spamshield_error_code);
					spamshield_log_data( $commentdata, $spamshield_error_code );
					}
				
				}
			// LOG DATA - END
			}

		// ONLY IF NOT ADMINS, EDITORS, AUTHORS - END
		}

	else if ( $spamshield_options['comment_logging_all'] ) {
		$spamshield_error_code = 'No Error';
		spamshield_log_data( $commentdata, $spamshield_error_code );
		}
			
	return $commentdata;
	}

function spamshield_allowed_post($approved) {
	// TEST TO PREVENT COMMENT SPAM FROM BOTS - BEGIN
	$spamshield_options			= get_option('spamshield_options');
	$CookieValidationName  		= $spamshield_options['cookie_validation_name'];
	$CookieValidationKey 		= $spamshield_options['cookie_validation_key'];
	$FormValidationFieldJS 		= $spamshield_options['form_validation_field_js'];
	$FormValidationKeyJS 		= $spamshield_options['form_validation_key_js'];
	$KeyUpdateTime 				= $spamshield_options['last_key_update'];
	$WPCommentValidationJS 		= $_COOKIE[$CookieValidationName];
	$WPFormValidationPost 		= $_POST[$FormValidationFieldJS]; //Comments Post Verification
	// if( $WPCommentValidationJS == $CookieValidationKey ) { // Comment allowed
	$wpss_cache_check 			= spamshield_check_cache_status();
	$wpss_cache_check_status	= $wpss_cache_check['cache_check_status'];
	if ( $WPFormValidationPost == $FormValidationKeyJS || $wpss_cache_check_status == 'ACTIVE' ) {
		$FormValidationFieldJSTest = 'PASS';
		}
	//if( $_COOKIE[$CookieValidationName] == $CookieValidationKey && $_POST[$FormValidationFieldJS] == $FormValidationKeyJS ) { // Comment allowed
	if( $_COOKIE[$CookieValidationName] == $CookieValidationKey && $FormValidationFieldJSTest == 'PASS' ) { // Comment allowed
		// Clear Key Values and Update
		$GetCurrentTime = time();
		$ResetIntervalHours = 24; // Reset interval in hours
		$ResetIntervalMinutes = 60; // Reset interval minutes default
		$ResetIntervalMinutesOverride = $ResetIntervalMinutes; // Use as override for testing; leave = $ResetIntervalMinutes when not testing
        if ( $ResetIntervalMinutesOverride != $ResetIntervalMinutes ) {
			$ResetIntervalHours = 1;
			$ResetIntervalMinutes = $ResetIntervalMinutesOverride;
			}
		$TimeThreshold = $GetCurrentTime - ( 60 * $ResetIntervalMinutes * $ResetIntervalHours ); // seconds * minutes * hours
		// This only resets key if over x amount of time after last reset
		if ( $TimeThreshold > $KeyUpdateTime  ) {
			spamshield_update_keys(1);
			}
		return $approved;
		}
	else { // Comment spam killed
	
		// Update Count
		update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
		spamshield_ak_accuracy_fix();

		$spamshield_jsck_error_ck_test = $_COOKIE['SJECT14']; // Default value is 'CKON14'
		
		if ( $spamshield_jsck_error_ck_test == 'CKON14' ) {
			$spamshield_jsck_error_ck_status = 'PHP detects that cookies appear to be enabled.';
			}
		else {
			$spamshield_jsck_error_ck_status = 'PHP detects that cookies appear to be disabled. <script type="text/javascript">if (navigator.cookieEnabled==true) { document.write(\'(However, JavaScript detects that cookies are enabled.)\'); } else { document.write(\'\(JavaScript also detects that cookies are disabled.\)\'); }; </script>';
			}
		
		$spamshield_jsck_error_message_standard = 'Sorry, there was an error. Please be sure JavaScript and Cookies are enabled in your browser and try again.';

		$spamshield_jsck_error_message_detailed = '<span style="font-size:12px;"><strong>Sorry, there was an error. JavaScript and Cookies are required in order to post a comment.</strong><br /><br />'."\n";
		$spamshield_jsck_error_message_detailed .= '<noscript>Status: JavaScript is currently disabled.<br /><br /></noscript>'."\n";
		$spamshield_jsck_error_message_detailed .= '<strong>Please be sure JavaScript and Cookies are enabled in your browser. Then, please hit the back button on your browser, and try posting your comment again. (You may need to reload the page)</strong><br /><br />'."\n";
		$spamshield_jsck_error_message_detailed .= '<br /><hr noshade />'."\n";
		if ( $spamshield_jsck_error_ck_test == 'CKON14' ) {
			$spamshield_jsck_error_message_detailed .= 'If you feel you have received this message in error (for example <em>if JavaScript and Cookies are in fact enabled</em> and you have tried to post several times), there is most likely a technical problem (could be a plugin conflict or misconfiguration). Please contact the author of this blog, and let them know they need to look into it.<br />'."\n";
			$spamshield_jsck_error_message_detailed .= '<hr noshade /><br />'."\n";
			}
		$spamshield_jsck_error_message_detailed .= '</span>'."\n";
		//$spamshield_jsck_error_message_detailed .= '<span style="font-size:9px;">This message was generated by WP-SpamShield.</span><br /><br />'."\n";
	
		$spamshield_imgphpck_error_message_standard = 'Sorry, there was an error. Please enable Images and Cookies in your browser and try again.';
		
		$spamshield_imgphpck_error_message_detailed = '<span style="font-size:12px;"><strong>Sorry, there was an error. Images and Cookies are required in order to post a comment.<br/>You appear to have at least one of these disabled.</strong><br /><br />'."\n";
		$spamshield_imgphpck_error_message_detailed .= '<strong>Please enable Images and Cookies in your browser. Then, please go back, reload the page, and try posting your comment again.</strong><br /><br />'."\n";
		$spamshield_imgphpck_error_message_detailed .= '<br /><hr noshade />'."\n";
		$spamshield_imgphpck_error_message_detailed .= 'If you feel you have received this message in error (for example <em>if Images and Cookies are in fact enabled</em> and you have tried to post several times), please alert the author of this blog, and let them know they need to look into it.<br />'."\n";
		$spamshield_imgphpck_error_message_detailed .= '<hr noshade /><br /></span>'."\n";
		//$spamshield_imgphpck_error_message_detailed .= '<span style="font-size:9px;">This message was generated by WP-SpamShield.</span><br /><br />'."\n";

		if( $spamshield_options['use_alt_cookie_method_only'] ) {
			wp_die( __($spamshield_imgphpck_error_message_detailed) );
			}
		else {
			wp_die( __($spamshield_jsck_error_message_detailed) );
			}
			
		return false;
		}
	// TEST TO PREVENT COMMENT SPAM FROM BOTS - END
	}
		
function spamshield_denied_post($approved) {
	// REJECT SPAM - BEGIN
	
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();

	$spamshield_filter_error_message_standard = '<span style="font-size:12px;">Comments have been temporarily disabled to prevent spam. Please try again later.</span>'; // Stop spammers without revealing why.
	
	$spamshield_filter_error_message_detailed = '<span style="font-size:12px;"><strong>Hmmm, your comment seems a bit spammy. We\'re not real big on spam around here.</strong><br /><br />'."\n";
	$spamshield_filter_error_message_detailed .= 'Please go back and try again.</span>'."\n";

	wp_die( __($spamshield_filter_error_message_detailed) );
	return false;
	// REJECT SPAM - END
	}

function spamshield_denied_post_short($approved) {
	// REJECT SHORT COMMENTS - BEGIN

	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();

	wp_die( __('<span style="font-size:12px;">Your comment was a bit too short. Please go back and try again.</span>') );
	return false;
	// REJECT SHORT COMMENTS - END
	}
	
function spamshield_denied_post_content_filter($approved) {
	// REJECT BASED ON CONTENT FILTER - BEGIN

	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	
	$spamshield_content_filter_error_message_detailed = '<span style="font-size:12px;"><strong>Your location has been identified as part of a reported spam network. Comments have been disabled to prevent spam.</strong><br /><br /></span>'."\n";
	
	wp_die( __($spamshield_content_filter_error_message_detailed) );
	return false;
	// REJECT BASED ON COMMENT FILTER - END
	}
	
function spamshield_denied_post_proxy($approved) {
	// REJECT PROXY COMMENTERS - BEGIN

	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	
	$spamshield_proxy_error_message_detailed = '<span style="font-size:12px;"><strong>Your comment has been blocked because the blog owner has set their spam filter to not allow comments from users behind proxies.</strong><br/><br/>If you are a regular commenter or you feel that your comment should not have been blocked, please contact the blog owner and ask them to modify this setting.<br /><br /></span>'."\n";
	
	wp_die( __($spamshield_proxy_error_message_detailed) );
	return false;
	// REJECT PROXY COMMENTERS - END
	}

function spamshield_denied_post_wp_blacklist($approved) {
	// REJECT BLACKLISTED COMMENTERS - BEGIN

	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	
	$spamshield_blacklist_error_message_detailed = '<span style="font-size:12px;"><strong>Your comment has been blocked based on the blog owner\'s blacklist settings.</strong><br/><br/>If you feel this is in error, please contact the blog owner by some other method.<br /><br /></span>'."\n";
	
	wp_die( __($spamshield_blacklist_error_message_detailed) );
	return false;
	// REJECT BLACKLISTED COMMENTERS - END
	}

function spamshield_content_short($commentdata) {
	// COMMENT LENGTH CHECK - BEGIN
	$commentdata_comment_content					= $commentdata['comment_content'];
	$commentdata_comment_content_lc					= strtolower($commentdata_comment_content);
	$commentdata_comment_content_lc_deslashed		= stripslashes($commentdata_comment_content_lc);
	
	$commentdata_comment_content_length 			= spamshield_strlen($commentdata_comment_content_lc_deslashed);
	$commentdata_comment_content_min_length 		= 15;
	
	$commentdata_comment_type						= $commentdata['comment_type'];
	
	if( $commentdata_comment_content_length < $commentdata_comment_content_min_length && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		$content_short_status = true;
		$spamshield_error_code .= ' SHORT15';
		}
	
	if ( !$spamshield_error_code ) {
		$spamshield_error_code = 'No Error';
		}
	else {
		$spamshield_error_code = ltrim($spamshield_error_code);
		$spamshield_options = get_option('spamshield_options');
		if ( $spamshield_options['comment_logging'] ) {
			spamshield_log_data( $commentdata, $spamshield_error_code );
			}
		}
	
	$spamshield_error_data = array( $spamshield_error_code, $blacklist_word_combo, $blacklist_word_combo_total );

	return $content_short_status;
	// COMMENT LENGTH CHECK - END
	}

function spamshield_content_filter($commentdata) {
	// Supplementary Defense - Blocking the Obvious to Improve Human/Pingback/Trackback Defense
	// FYI, Certain loops are unrolled because of a weird compatibility issue with certain servers. Works fine on most, but for some unforeseen reason, a few have issues. When I get more time to test, will try to figure it out. For now these have to stay unrolled.

	$spamshield_options = get_option('spamshield_options');
	$CookieValidationName  		= $spamshield_options['cookie_validation_name'];
	$CookieValidationKey 		= $spamshield_options['cookie_validation_key'];

	
	// CONTENT FILTERING - BEGIN
	
	$commentdata_comment_post_id					= $commentdata['comment_post_ID'];
	$commentdata_comment_post_title					= $commentdata['comment_post_title'];
	$commentdata_comment_post_title_lc				= strtolower($commentdata_comment_post_title);
	$commentdata_comment_post_title_lc_regex 		= preg_quote($commentdata_comment_post_title_lc);
	$commentdata_comment_post_url					= $commentdata['comment_post_url'];
	$commentdata_comment_post_url_lc				= strtolower($commentdata_comment_post_url);
	$commentdata_comment_post_url_lc_regex 			= preg_quote($commentdata_comment_post_url_lc);
	
	$commentdata_comment_author						= $commentdata['comment_author'];
	$commentdata_comment_author_lc					= strtolower($commentdata_comment_author);
	$commentdata_comment_author_lc_regex 			= preg_quote($commentdata_comment_author_lc);
	$commentdata_comment_author_lc_words 			= spamshield_count_words($commentdata_comment_author_lc);
	$commentdata_comment_author_lc_space 			= ' '.$commentdata_comment_author_lc.' ';
	$commentdata_comment_author_lc_deslashed		= stripslashes($commentdata_comment_author_lc);
	$commentdata_comment_author_lc_deslashed_regex 	= preg_quote($commentdata_comment_author_lc_deslashed);
	$commentdata_comment_author_lc_deslashed_words 	= spamshield_count_words($commentdata_comment_author_lc_deslashed);
	$commentdata_comment_author_lc_deslashed_space 	= ' '.$commentdata_comment_author_lc_deslashed.' ';
	$commentdata_comment_author_email				= $commentdata['comment_author_email'];
	$commentdata_comment_author_email_lc			= strtolower($commentdata_comment_author_email);
	$commentdata_comment_author_email_lc_regex 		= preg_quote($commentdata_comment_author_email_lc);
	$commentdata_comment_author_url					= $commentdata['comment_author_url'];
	$commentdata_comment_author_url_lc				= strtolower($commentdata_comment_author_url);
	$commentdata_comment_author_url_lc_regex 		= preg_quote($commentdata_comment_author_url_lc);
	
	$commentdata_comment_content					= $commentdata['comment_content'];
	$commentdata_comment_content_lc					= strtolower($commentdata_comment_content);
	$commentdata_comment_content_lc_deslashed		= stripslashes($commentdata_comment_content_lc);
	
	$replace_apostrophes							= array('’','`','&acute;','&grave;','&#39;','&#96;','&#101;','&#145;','&#146;','&#158;','&#180;','&#207;','&#208;','&#8216;','&#8217;');
	$commentdata_comment_content_lc_norm_apost 		= str_replace($replace_apostrophes,"'",$commentdata_comment_content_lc_deslashed);
	
	$commentdata_comment_type						= $commentdata['comment_type'];
	
	/*
	if ( $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		$commentdata_comment_type = 'comment';
		}
	*/
	
	// Altered to Accommodate WP 2.5+
	$commentdata_user_agent					= $_SERVER['HTTP_USER_AGENT'];
	$commentdata_user_agent_lc				= strtolower($commentdata_user_agent);
	$commentdata_remote_addr				= $_SERVER['REMOTE_ADDR'];
	$commentdata_remote_addr_regex 			= preg_quote($commentdata_remote_addr);
	$commentdata_remote_addr_lc				= strtolower($commentdata_remote_addr);
	$commentdata_remote_addr_lc_regex 		= preg_quote($commentdata_remote_addr_lc);

	$commentdata_referrer					= $_SERVER['HTTP_REFERER'];
	$commentdata_referrer_lc				= strtolower($commentdata_referrer);
	$commentdata_blog						= WPSS_SITE_URL;
	$commentdata_blog_lc					= strtolower($commentdata_blog);
	$commentdata_php_self					= $_SERVER['PHP_SELF'];
	$commentdata_php_self_lc				= strtolower($commentdata_php_self);
	
	$BlogServerIP = WPSS_SERVER_ADDR;
	$BlogServerName = WPSS_SERVER_NAME;

	// IP / PROXY INFO - BEGIN
	$ipBlock = explode('.',$commentdata_remote_addr);
	$ipProxyVIA = trim($_SERVER['HTTP_VIA']);
	$ipProxyVIA_LC = strtolower($ipProxyVIA);
	$MaskedIP = trim($_SERVER['HTTP_X_FORWARDED_FOR']); // Stated Original IP - Can be faked
	$MaskedIPBlock = explode('.',$MaskedIP);
	if ( preg_match("/^([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])/", $MaskedIP ) && $MaskedIP != '' && $MaskedIP != 'unknown' && strpos( $MaskedIP, '192.168.' ) !== 0 ) {
		$MaskedIPValid=true;
		$MaskedIPCore=rtrim($MaskedIP," unknown;,");
		}
	$ReverseDNS 				= gethostbyaddr($commentdata_remote_addr);
	$ReverseDNSIP 				= gethostbyname($ReverseDNS);
	$ReverseDNSIP_regex 		= preg_quote($ReverseDNSIP);
	$ReverseDNS_LC 				= strtolower($ReverseDNS);
	$ReverseDNS_LC_regex 		= preg_quote($ReverseDNS_LC);
	$ReverseDNS_LC_Rev 			= strrev($ReverseDNS_LC);
	$ReverseDNS_LC_Rev_regex 	= preg_quote($ReverseDNS_LC_Rev);
	
	if ( $_SERVER['REMOTE_HOST'] ) {
		$commentdata_remote_host = $_SERVER['REMOTE_HOST'];
		}
	else {
		$commentdata_remote_host = $ReverseDNS;
		}
	$commentdata_remote_host_lc	= strtolower($commentdata_remote_host);

	if ( !$commentdata_remote_host_lc ) {
		$commentdata_remote_host_lc = 'blank';
		}

	if ( $ReverseDNSIP != $commentdata_remote_addr || $commentdata_remote_addr == $ReverseDNS ) {
		$ReverseDNSAuthenticity = '[Possibly Forged]';
		} 
	else {
		$ReverseDNSAuthenticity = '[Verified]';
		}
	// Detect Use of Proxy
	//if ( ( $_SERVER['HTTP_VIA'] && $_SERVER['HTTP_VIA'] !='' ) || ( $_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['HTTP_X_FORWARDED_FOR'] !='' ) ) {
	if ( ( $ipProxyVIA && $ipProxyVIA !='' ) || ( $MaskedIP && $MaskedIP !='' ) ) {
		if ( !$MaskedIP || $MaskedIP == '' ) { $MaskedIP='[No Data]'; }
		$ipProxy='PROXY DETECTED';
		$ipProxyShort='PROXY';
		$ipProxyData=$commentdata_remote_addr.' | MASKED IP: '.$MaskedIP;
		$ProxyStatus='TRUE';
		//Google Chrome Compression Check
		if ( strpos( $ipProxyVIA_LC, 'chrome compression proxy' ) !== false && preg_match( "@^google-proxy-(.*)\.google\.com$@i", $ReverseDNS ) ) {
			$ipProxyChromeCompression='TRUE';
			}
		else {
			$ipProxyChromeCompression='FALSE';
			}
		}
	else {
		$ipProxy='No Proxy';
		$ipProxyShort=$ipProxy;
		$ipProxyData=$commentdata_remote_addr;
		$ProxyStatus='FALSE';
		}
	// IP / PROXY INFO - END

	// Simple Filters
	
	$blacklist_word_combo_total_limit = 10; // you may increase to 30+ if blog's topic is adult in nature
	$blacklist_word_combo_total = 0;
	
	// Filter 1: Number of occurrences of 'http://' in comment_content
	$filter_1_count_http = substr_count($commentdata_comment_content_lc_deslashed, 'http://');
	$filter_1_count_https = substr_count($commentdata_comment_content_lc_deslashed, 'https://');
	$filter_1_count = $filter_1_count_http + $filter_1_count_https;
	$filter_1_limit = 4;
	$filter_1_trackback_limit = 1;
	
	// Medical-Related Filters
	// Comment Content ONLY - These are not Author Tests
	
	// Dev Note: Redo later to use word breaks in php regex
	
	$filter_2_term = 'viagra';
	$filter_2_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_2_term);
	$filter_2_limit = 2;
	$filter_2_trackback_limit = 1;
	$filter_2_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_2_term);
	$filter_2_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_2_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_2_author_count;
	// Filter 3: Number of occurrences of 'v1agra' in comment_content
	$filter_3_term = 'v1agra';
	$filter_3_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_3_term);
	$filter_3_limit = 1;
	$filter_3_trackback_limit = 1;
	$filter_3_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_3_term);
	$filter_3_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_3_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_3_author_count;
	// Filter 4: Number of occurrences of ' cialis' in comment_content
	$filter_4_term = 'cialis'; 
	// Testing something next 4 lines. Will make more efficient soon.
	$filter_4_term_space = ' '.$filter_4_term; 
	$filter_4_term_slash = '-'.$filter_4_term; 
	$filter_4_term_dash = '/'.$filter_4_term;
	$filter_4_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_4_term_space)+substr_count($commentdata_comment_content_lc_deslashed, $filter_4_term_slash)+substr_count($commentdata_comment_content_lc_deslashed, $filter_4_term_dash);
	//$filter_4_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_4_term);
	$filter_4_limit = 2;
	$filter_4_trackback_limit = 1;
	$filter_4_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_4_term_space)+substr_count($commentdata_comment_author_lc_deslashed, $filter_4_term_slash)+substr_count($commentdata_comment_author_lc_deslashed, $filter_4_term_dash);
	//$filter_4_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_4_term);
	$filter_4_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_4_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_4_author_count;
	// Filter 5: Number of occurrences of 'c1alis' in comment_content
	$filter_5_term = 'c1alis';
	$filter_5_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_5_term);
	$filter_5_limit = 1;
	$filter_5_trackback_limit = 1;
	$filter_5_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_5_term);
	$filter_5_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_5_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_5_author_count;
	// Filter 6: Number of occurrences of 'levitra' in comment_content
	$filter_6_term = 'levitra';
	$filter_6_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_6_term);
	$filter_6_limit = 2;
	$filter_6_trackback_limit = 1;
	$filter_6_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_6_term);
	$filter_6_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_6_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_6_author_count;
	// Filter 7: Number of occurrences of 'lev1tra' in comment_content
	$filter_7_term = 'lev1tra';
	$filter_7_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_7_term);
	$filter_7_limit = 1;
	$filter_7_trackback_limit = 1;
	$filter_7_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_7_term);
	$filter_7_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_7_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_7_author_count;
	// Filter 8: Number of occurrences of 'erectile dysfunction' in comment_content
	$filter_8_term = 'erectile dysfunction';
	$filter_8_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_8_term);
	$filter_8_limit = 2;
	$filter_8_trackback_limit = 1;
	$filter_8_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_8_term);
	$filter_8_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_8_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_8_author_count;
	// Filter 9: Number of occurrences of 'erection' in comment_content
	$filter_9_term = 'erection';
	$filter_9_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_9_term);
	$filter_9_limit = 3;
	$filter_9_trackback_limit = 1;
	$filter_9_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_9_term);
	$filter_9_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_9_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_9_author_count;
	// Filter 10: Number of occurrences of 'erectile' in comment_content
	$filter_10_term = 'erectile';
	$filter_10_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_10_term);
	$filter_10_limit = 2;
	$filter_10_trackback_limit = 1;
	$filter_10_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_10_term);
	$filter_10_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_10_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_10_author_count;
	// Filter 11: Number of occurrences of 'xanax' in comment_content
	$filter_11_term = 'xanax';
	$filter_11_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_11_term);
	$filter_11_limit = 3;
	$filter_11_trackback_limit = 2;
	$filter_11_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_11_term);
	$filter_11_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_11_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_11_author_count;
	// Filter 12: Number of occurrences of 'zithromax' in comment_content
	$filter_12_term = 'zithromax';
	$filter_12_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_12_term);
	$filter_12_limit = 3;
	$filter_12_trackback_limit = 2;
	$filter_12_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_12_term);
	$filter_12_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_12_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_12_author_count;
	// Filter 13: Number of occurrences of 'phentermine' in comment_content
	$filter_13_term = 'phentermine';
	$filter_13_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_13_term);
	$filter_13_limit = 3;
	$filter_13_trackback_limit = 2;
	$filter_13_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_13_term);
	$filter_13_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_13_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_13_author_count;
	// Filter 14: Number of occurrences of ' soma ' in comment_content
	$filter_14_term = ' soma ';
	$filter_14_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_14_term);
	$filter_14_limit = 3;
	$filter_14_trackback_limit = 2;
	$filter_14_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_14_term);
	$filter_14_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_14_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_14_author_count;
	// Filter 15: Number of occurrences of ' soma.' in comment_content
	$filter_15_term = ' soma.';
	$filter_15_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_15_term);
	$filter_15_limit = 3;
	$filter_15_trackback_limit = 2;
	$filter_15_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_15_term);
	$filter_15_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_15_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_15_author_count;
	// Filter 16: Number of occurrences of 'prescription' in comment_content
	$filter_16_term = 'prescription';
	$filter_16_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_16_term);
	$filter_16_limit = 3;
	$filter_16_trackback_limit = 2;
	$filter_16_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_16_term);
	$filter_16_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_16_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_16_author_count;
	// Filter 17: Number of occurrences of 'tramadol' in comment_content
	$filter_17_term = 'tramadol';
	$filter_17_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_17_term);
	$filter_17_limit = 3;
	$filter_17_trackback_limit = 2;
	$filter_17_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_17_term);
	$filter_17_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_17_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_17_author_count;
	// Filter 18: Number of occurrences of 'penis enlargement' in comment_content
	$filter_18_term = 'penis enlargement';
	$filter_18_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_18_term);
	$filter_18_limit = 2;
	$filter_18_trackback_limit = 1;
	$filter_18_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_18_term);
	$filter_18_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_18_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_18_author_count;
	// Filter 19: Number of occurrences of 'buy pills' in comment_content
	$filter_19_term = 'buy pills';
	$filter_19_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_19_term);
	$filter_19_limit = 3;
	$filter_19_trackback_limit = 2;
	$filter_19_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_19_term);
	$filter_19_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_19_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_19_author_count;
	// Filter 20: Number of occurrences of 'diet pill' in comment_content
	$filter_20_term = 'diet pill';
	$filter_20_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_20_term);
	$filter_20_limit = 3;
	$filter_20_trackback_limit = 2;
	$filter_20_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_20_term);
	$filter_20_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_20_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_20_author_count;
	// Filter 21: Number of occurrences of 'weight loss pill' in comment_content
	$filter_21_term = 'weight loss pill';
	$filter_21_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_21_term);
	$filter_21_limit = 3;
	$filter_21_trackback_limit = 2;
	$filter_21_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_21_term);
	$filter_21_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_21_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_21_author_count;
	// Filter 22: Number of occurrences of 'pill' in comment_content
	$filter_22_term = 'pill';
	$filter_22_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_22_term);
	$filter_22_limit = 10;
	$filter_22_trackback_limit = 2;
	$filter_22_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_22_term);
	$filter_22_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_22_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_22_author_count;
	// Filter 23: Number of occurrences of ' pill,' in comment_content
	$filter_23_term = ' pill,';
	$filter_23_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_23_term);
	$filter_23_limit = 5;
	$filter_23_trackback_limit = 2;
	$filter_23_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_23_term);
	$filter_23_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_23_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_23_author_count;
	// Filter 24: Number of occurrences of ' pills,' in comment_content
	$filter_24_term = ' pills,';
	$filter_24_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_24_term);
	$filter_24_limit = 5;
	$filter_24_trackback_limit = 2;
	$filter_24_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_24_term);
	$filter_24_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_24_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_24_author_count;
	// Filter 25: Number of occurrences of 'propecia' in comment_content
	$filter_25_term = 'propecia';
	$filter_25_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_25_term);
	$filter_25_limit = 2;
	$filter_25_trackback_limit = 1;
	$filter_25_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_25_term);
	$filter_25_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_25_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_25_author_count;
	// Filter 26: Number of occurrences of 'propec1a' in comment_content
	$filter_26_term = 'propec1a';
	$filter_26_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_26_term);
	$filter_26_limit = 1;
	$filter_26_trackback_limit = 1;
	$filter_26_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_26_term);
	$filter_26_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_26_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_26_author_count;
	// Filter 27: Number of occurrences of 'online pharmacy' in comment_content
	$filter_27_term = 'online pharmacy';
	$filter_27_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_27_term);
	$filter_27_limit = 5;
	$filter_27_trackback_limit = 2;
	$filter_27_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_27_term);
	$filter_27_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_27_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_27_author_count;
	// Filter 28: Number of occurrences of 'medication' in comment_content
	$filter_28_term = 'medication';
	$filter_28_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_28_term);
	$filter_28_limit = 7;
	$filter_28_trackback_limit = 3;
	$filter_28_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_28_term);
	$filter_28_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_28_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_28_author_count;
	// Filter 29: Number of occurrences of 'buy now' in comment_content
	$filter_29_term = 'buy now';
	$filter_29_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_29_term);
	$filter_29_limit = 7;
	$filter_29_trackback_limit = 3;
	$filter_29_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_29_term);
	$filter_29_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_29_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_29_author_count;
	// Filter 30: Number of occurrences of 'ephedrin' in comment_content
	$filter_30_term = 'ephedrin';
	$filter_30_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_30_term);
	$filter_30_limit = 3;
	$filter_30_trackback_limit = 2;
	$filter_30_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_30_term);
	$filter_30_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_30_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_30_author_count;
	// Filter 31: Number of occurrences of 'ephedrin' in comment_content
	$filter_31_term = 'ephedrine';
	$filter_31_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_31_term);
	$filter_31_limit = 3;
	$filter_31_trackback_limit = 2;
	$filter_31_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_31_term);
	$filter_31_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_31_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_31_author_count;
	// Filter 32: Number of occurrences of 'ephedrin' in comment_content
	$filter_32_term = 'ephedr1n';
	$filter_32_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_32_term);
	$filter_32_limit = 1;
	$filter_32_trackback_limit = 1;
	$filter_32_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_32_term);
	$filter_32_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_32_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_32_author_count;
	// Filter 33: Number of occurrences of 'ephedrin' in comment_content
	$filter_33_term = 'ephedr1ne';
	$filter_33_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_33_term);
	$filter_33_limit = 1;
	$filter_33_trackback_limit = 1;
	$filter_33_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_33_term);
	$filter_33_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_33_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_33_author_count;
	// Filter 34: Number of occurrences of 'ephedra' in comment_content
	$filter_34_term = 'ephedra';
	$filter_34_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_34_term);
	$filter_34_limit = 3;
	$filter_34_trackback_limit = 2;
	$filter_34_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_34_term);
	$filter_34_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_34_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_34_author_count;
	// Filter 35: Number of occurrences of 'valium' in comment_content
	$filter_35_term = 'valium';
	$filter_35_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_35_term);
	$filter_35_limit = 3;
	$filter_35_trackback_limit = 2;
	$filter_35_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_35_term);
	$filter_35_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_35_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_35_author_count;
	// Filter 36: Number of occurrences of 'adipex' in comment_content
	$filter_36_term = 'adipex';
	$filter_36_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_36_term);
	$filter_36_limit = 3;
	$filter_36_trackback_limit = 2;
	$filter_36_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_36_term);
	$filter_36_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_36_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_36_author_count;
	// Filter 37: Number of occurrences of 'accutane' in comment_content
	$filter_37_term = 'accutane';
	$filter_37_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_37_term);
	$filter_37_limit = 3;
	$filter_37_trackback_limit = 2;
	$filter_37_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_37_term);
	$filter_37_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_37_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_37_author_count;
	// Filter 38: Number of occurrences of 'acomplia' in comment_content
	$filter_38_term = 'acomplia';
	$filter_38_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_38_term);
	$filter_38_limit = 3;
	$filter_38_trackback_limit = 2;
	$filter_38_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_38_term);
	$filter_38_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_38_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_38_author_count;
	// Filter 39: Number of occurrences of 'rimonabant' in comment_content
	$filter_39_term = 'rimonabant';
	$filter_39_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_39_term);
	$filter_39_limit = 3;
	$filter_39_trackback_limit = 2;
	$filter_39_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_39_term);
	$filter_39_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_39_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_39_author_count;
	// Filter 40: Number of occurrences of 'zimulti' in comment_content
	$filter_40_term = 'zimulti';
	$filter_40_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_40_term);
	$filter_40_limit = 3;
	$filter_40_trackback_limit = 2;
	$filter_40_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_40_term);
	$filter_40_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_40_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_40_author_count;
	// Filter 41: Number of occurrences of 'herbalife' in comment_content
	$filter_41_term = 'herbalife';
	$filter_41_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_41_term);
	$filter_41_limit = 8;
	$filter_41_trackback_limit = 7;
	$filter_41_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_41_term);
	$filter_41_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_41_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_41_author_count;


	// Non-Medical Author Tests
	// Filter 210: Number of occurrences of 'drassyassut' in comment_content
	$filter_210_term = 'drassyassut'; //DrassyassuT
	$filter_210_count = substr_count($commentdata_comment_content_lc_deslashed, $filter_210_term);
	$filter_210_limit = 1;
	$filter_210_trackback_limit = 1;
	$filter_210_author_count = substr_count($commentdata_comment_author_lc_deslashed, $filter_210_term);
	$filter_210_author_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_210_count;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_210_author_count;

	// Sex-Related Filter
	// Comment Content ONLY - These are not Author Tests
	// Filter 104: Number of occurrences of 'porn' in comment_content
	$filter_104_count = substr_count($commentdata_comment_content_lc_deslashed, 'porn');
	$filter_104_limit = 5;
	$filter_104_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_104_count;
	// Filter 105: Number of occurrences of 'teen porn' in comment_content
	$filter_105_count = substr_count($commentdata_comment_content_lc_deslashed, 'teen porn');
	$filter_105_limit = 1;
	$filter_105_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_105_count;
	// Filter 106: Number of occurrences of 'rape porn' in comment_content
	$filter_106_count = substr_count($commentdata_comment_content_lc_deslashed, 'rape porn');
	$filter_106_limit = 1;
	$filter_106_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_106_count;
	// Filter 107: Number of occurrences of 'incest porn' in comment_content
	$filter_107_count = substr_count($commentdata_comment_content_lc_deslashed, 'incest porn');
	$filter_107_limit = 1;
	$filter_107_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_107_count;
	// Filter 108: Number of occurrences of 'hentai' in comment_content
	$filter_108_count = substr_count($commentdata_comment_content_lc_deslashed, 'hentai');
	$filter_108_limit = 2;
	$filter_108_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_108_count;
	// Filter 109: Number of occurrences of 'sex movie' in comment_content
	$filter_109_count = substr_count($commentdata_comment_content_lc_deslashed, 'sex movie');
	$filter_109_limit = 2;
	$filter_109_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_109_count;
	// Filter 110: Number of occurrences of 'sex tape' in comment_content
	$filter_110_count = substr_count($commentdata_comment_content_lc_deslashed, 'sex tape');
	$filter_110_limit = 2;
	$filter_110_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_110_count;
	// Filter 111: Number of occurrences of 'sex' in comment_content
	$filter_111_count = substr_count($commentdata_comment_content_lc_deslashed, 'sex');
	$filter_111_limit = 5; // you may increase to 15+ if blog's topic is adult in nature
	$filter_111_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_111_count;
	// Filter 112: Number of occurrences of 'sex' in comment_content
	$filter_112_count = substr_count($commentdata_comment_content_lc_deslashed, 'pussy');
	$filter_112_limit = 3;
	$filter_112_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_112_count;
	// Filter 113: Number of occurrences of 'penis' in comment_content
	$filter_113_count = substr_count($commentdata_comment_content_lc_deslashed, 'penis');
	$filter_113_limit = 3;
	$filter_113_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_113_count;
	// Filter 114: Number of occurrences of 'vagina' in comment_content
	$filter_114_count = substr_count($commentdata_comment_content_lc_deslashed, 'vagina');
	$filter_114_limit = 3;
	$filter_114_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_114_count;
	// Filter 115: Number of occurrences of 'gay porn' in comment_content
	$filter_115_count = substr_count($commentdata_comment_content_lc_deslashed, 'gay porn');
	$filter_115_limit = 2;
	$filter_115_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_115_count;
	// Filter 116: Number of occurrences of 'torture porn' in comment_content
	$filter_116_count = substr_count($commentdata_comment_content_lc_deslashed, 'torture porn');
	$filter_116_limit = 1; 
	$filter_116_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_116_count;
	// Filter 117: Number of occurrences of 'masturbation' in comment_content
	$filter_117_count = substr_count($commentdata_comment_content_lc_deslashed, 'masturbation');
	$filter_117_limit = 3;
	$filter_117_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_117_count;
	// Filter 118: Number of occurrences of 'masterbation' in comment_content
	$filter_118_count = substr_count($commentdata_comment_content_lc_deslashed, 'masterbation');
	$filter_118_limit = 2;
	$filter_118_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_118_count;
	// Filter 119: Number of occurrences of 'masturbate' in comment_content
	$filter_119_count = substr_count($commentdata_comment_content_lc_deslashed, 'masturbate');
	$filter_119_limit = 3;
	$filter_119_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_119_count;
	// Filter 120: Number of occurrences of 'masterbate' in comment_content
	$filter_120_count = substr_count($commentdata_comment_content_lc_deslashed, 'masterbate');
	$filter_120_limit = 2;
	$filter_120_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_120_count;
	// Filter 121: Number of occurrences of 'masturbating' in comment_content
	$filter_121_count = substr_count($commentdata_comment_content_lc_deslashed, 'masturbating');
	$filter_121_limit = 3;
	$filter_121_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_121_count;
	// Filter 122: Number of occurrences of 'masterbating' in comment_content
	$filter_122_count = substr_count($commentdata_comment_content_lc_deslashed, 'masterbating');
	$filter_122_limit = 2;
	$filter_122_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_122_count;
	// Filter 123: Number of occurrences of 'anal sex' in comment_content
	$filter_123_count = substr_count($commentdata_comment_content_lc_deslashed, 'anal sex');
	$filter_123_limit = 3;
	$filter_123_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_123_count;
	// Filter 124: Number of occurrences of 'xxx' in comment_content
	$filter_124_count = substr_count($commentdata_comment_content_lc_deslashed, 'xxx');
	$filter_124_limit = 5;
	$filter_124_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_124_count;
	// Filter 125: Number of occurrences of 'naked' in comment_content
	$filter_125_count = substr_count($commentdata_comment_content_lc_deslashed, 'naked');
	$filter_125_limit = 5;
	$filter_125_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_125_count;
	// Filter 126: Number of occurrences of 'nude' in comment_content
	$filter_126_count = substr_count($commentdata_comment_content_lc_deslashed, 'nude');
	$filter_126_limit = 5;
	$filter_126_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_126_count;
	// Filter 127: Number of occurrences of 'fucking' in comment_content
	$filter_127_count = substr_count($commentdata_comment_content_lc_deslashed, 'fucking');
	$filter_127_limit = 5;
	$filter_127_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_127_count;
	// Filter 128: Number of occurrences of 'orgasm' in comment_content
	$filter_128_count = substr_count($commentdata_comment_content_lc_deslashed, 'orgasm');
	$filter_128_limit = 5;
	$filter_128_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_128_count;
	// Filter 129: Number of occurrences of 'pron' in comment_content
	$filter_129_count = substr_count($commentdata_comment_content_lc_deslashed, 'pron');
	$filter_129_limit = 5;
	$filter_129_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_129_count;
	// Filter 130: Number of occurrences of 'bestiality' in comment_content
	$filter_130_count = substr_count($commentdata_comment_content_lc_deslashed, 'bestiality');
	$filter_130_limit = 2;
	$filter_130_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_130_count;
	// Filter 131: Number of occurrences of 'animal sex' in comment_content
	$filter_131_count = substr_count($commentdata_comment_content_lc_deslashed, 'animal sex');
	$filter_131_limit = 2;
	$filter_131_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_131_count;
	// Filter 132: Number of occurrences of 'dildo' in comment_content
	$filter_132_count = substr_count($commentdata_comment_content_lc_deslashed, 'dildo');
	$filter_132_limit = 4;
	$filter_132_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_132_count;
	// Filter 133: Number of occurrences of 'ejaculate' in comment_content
	$filter_133_count = substr_count($commentdata_comment_content_lc_deslashed, 'ejaculate');
	$filter_133_limit = 3;
	$filter_133_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_133_count;
	// Filter 134: Number of occurrences of 'ejaculation' in comment_content
	$filter_134_count = substr_count($commentdata_comment_content_lc_deslashed, 'ejaculation');
	$filter_134_limit = 3;
	$filter_134_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_134_count;
	// Filter 135: Number of occurrences of 'ejaculating' in comment_content
	$filter_135_count = substr_count($commentdata_comment_content_lc_deslashed, 'ejaculating');
	$filter_135_limit = 3;
	$filter_135_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_135_count;
	// Filter 136: Number of occurrences of 'lesbian' in comment_content
	$filter_136_count = substr_count($commentdata_comment_content_lc_deslashed, 'lesbian');
	$filter_136_limit = 7;
	$filter_136_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_136_count;
	// Filter 137: Number of occurrences of 'sex video' in comment_content
	$filter_137_count = substr_count($commentdata_comment_content_lc_deslashed, 'sex video');
	$filter_137_limit = 2;
	$filter_137_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_137_count;
	// Filter 138: Number of occurrences of ' anal ' in comment_content
	$filter_138_count = substr_count($commentdata_comment_content_lc_deslashed, ' anal ');
	$filter_138_limit = 5;
	$filter_138_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_138_count;
	// Filter 139: Number of occurrences of '>anal ' in comment_content
	$filter_139_count = substr_count($commentdata_comment_content_lc_deslashed, '>anal ');
	$filter_139_limit = 5;
	$filter_139_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_139_count;
	// Filter 140: Number of occurrences of 'desnuda' in comment_content
	$filter_140_count = substr_count($commentdata_comment_content_lc_deslashed, 'desnuda');
	$filter_140_limit = 5;
	$filter_140_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_140_count;
	// Filter 141: Number of occurrences of 'cumshots' in comment_content
	$filter_141_count = substr_count($commentdata_comment_content_lc_deslashed, 'cumshots');
	$filter_141_limit = 2;
	$filter_141_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_141_count;
	// Filter 142: Number of occurrences of 'porntube' in comment_content
	$filter_142_count = substr_count($commentdata_comment_content_lc_deslashed, 'porntube');
	$filter_142_limit = 2;
	$filter_142_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_142_count;
	// Filter 143: Number of occurrences of 'fuck' in comment_content
	$filter_143_count = substr_count($commentdata_comment_content_lc_deslashed, 'fuck');
	$filter_143_limit = 6;
	$filter_143_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_143_count;
	// Filter 144: Number of occurrences of 'celebrity' in comment_content
	$filter_144_count = substr_count($commentdata_comment_content_lc_deslashed, 'celebrity');
	$filter_144_limit = 6;
	$filter_144_trackback_limit = 6;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_144_count;
	// Filter 145: Number of occurrences of 'celebrities' in comment_content
	$filter_145_count = substr_count($commentdata_comment_content_lc_deslashed, 'celebrities');
	$filter_145_limit = 6;
	$filter_145_trackback_limit = 6;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_145_count;
	// Filter 146: Number of occurrences of 'erotic' in comment_content
	$filter_146_count = substr_count($commentdata_comment_content_lc_deslashed, 'erotic');
	$filter_146_limit = 6;
	$filter_146_trackback_limit = 4;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_146_count;
	// Filter 147: Number of occurrences of 'gay' in comment_content
	$filter_147_count = substr_count($commentdata_comment_content_lc_deslashed, 'gay');
	$filter_147_limit = 7;
	$filter_147_trackback_limit = 4;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_147_count;
	// Filter 148: Number of occurrences of 'heterosexual' in comment_content
	$filter_148_count = substr_count($commentdata_comment_content_lc_deslashed, 'heterosexual');
	$filter_148_limit = 7;
	$filter_148_trackback_limit = 4;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_148_count;
	// Filter 149: Number of occurrences of 'blowjob' in comment_content
	$filter_149_count = substr_count($commentdata_comment_content_lc_deslashed, 'blowjob');
	$filter_149_limit = 2;
	$filter_149_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_149_count;
	// Filter 150: Number of occurrences of 'blow job' in comment_content
	$filter_150_count = substr_count($commentdata_comment_content_lc_deslashed, 'blow job');
	$filter_150_limit = 2;
	$filter_150_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_150_count;
	// Filter 151: Number of occurrences of 'rape' in comment_content
	$filter_151_count = substr_count($commentdata_comment_content_lc_deslashed, 'rape');
	$filter_151_limit = 5;
	$filter_151_trackback_limit = 3;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_151_count;
	// Filter 152: Number of occurrences of 'prostitute' in comment_content
	$filter_152_count = substr_count($commentdata_comment_content_lc_deslashed, 'prostitute');
	$filter_152_limit = 7;
	$filter_152_trackback_limit = 5;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_152_count;
	// Filter 153: Number of occurrences of 'call girl' in comment_content
	$filter_153_count = substr_count($commentdata_comment_content_lc_deslashed, 'call girl');
	$filter_153_limit = 7;
	$filter_153_trackback_limit = 5;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_153_count;
	// Filter 154: Number of occurrences of 'escort service' in comment_content
	$filter_154_count = substr_count($commentdata_comment_content_lc_deslashed, 'escort service');
	$filter_154_limit = 7;
	$filter_154_trackback_limit = 5;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_154_count;
	// Filter 155: Number of occurrences of 'sexual service' in comment_content
	$filter_155_count = substr_count($commentdata_comment_content_lc_deslashed, 'sexual service');
	$filter_155_limit = 7;
	$filter_155_trackback_limit = 5;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_155_count;
	// Filter 156: Number of occurrences of 'adult movie' in comment_content
	$filter_156_count = substr_count($commentdata_comment_content_lc_deslashed, 'adult movie');
	$filter_156_limit = 4;
	$filter_156_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_156_count;
	// Filter 157: Number of occurrences of 'adult video' in comment_content
	$filter_157_count = substr_count($commentdata_comment_content_lc_deslashed, 'adult video');
	$filter_157_limit = 4;
	$filter_157_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_157_count;
	// Filter 158: Number of occurrences of 'clitoris' in comment_content
	$filter_158_count = substr_count($commentdata_comment_content_lc_deslashed, 'clitoris');
	$filter_158_limit = 3;
	$filter_158_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_158_count;
	// Filter 159: Number of occurrences of 'cyber sex' in comment_content
	$filter_159_count = substr_count($commentdata_comment_content_lc_deslashed, 'cyber sex');
	$filter_159_limit = 3;
	$filter_159_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_159_count;
	
	// Pingback/Trackback Filters
	// Filter 200: Pingback: Blank data in comment_content: [...]  [...]
	$filter_200_count = substr_count($commentdata_comment_content_lc_deslashed, '[...]  [...]');
	$filter_200_limit = 1;
	$filter_200_trackback_limit = 1;

	// Authors Only - Non-Trackback
	//Removed Filters 300-423 and replaced with Regex


	// Author Test: for *author names* surrounded by asterisks
	if ( preg_match( "/^\*/", $commentdata_comment_author_lc_deslashed ) || preg_match( "/\*$/", $commentdata_comment_author_lc_deslashed ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 300001AUTH-STAR';
		}
		
	// Author Test: if $commentdata_comment_author_lc_deslashed is a URL, NO GO
	if ( preg_match( "@^https?@i", $commentdata_comment_author_lc_deslashed ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 300002AUTH-HTTP';
		}

	// Author Test: if $commentdata_comment_author_lc_deslashed contains more than 7 words, NO GO
	if ( $commentdata_comment_author_lc_deslashed_words > 7 && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 300003AUTH-MAX';
		}

	// Author Test: if $commentdata_comment_author_lc_deslashed contains "seo" at either end of phrase, NO GO
	if ( ( preg_match( "/^seo\s/i", $commentdata_comment_author_lc_deslashed ) || preg_match( "/\sseo$/i", $commentdata_comment_author_lc_deslashed ) ) && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 300004AUTH-SEO';
		}

	//Simple Author equals X (==) Tests
	$filter_300400_term = 'business';
	$filter_300401_term = 'marketing';
	$filter_300402_term = 'cialis';
	$filter_300403_term = 'seo';
	$filter_300404_term = 'cheap';
	$filter_300405_term = 'discount';
	$filter_300406_term = 'insurance';
	$filter_300407_term = 'development';
	$filter_300408_term = 'software';
	$filter_300409_term = 'guide';
	$filter_300410_term = 'tips';
	$filter_300411_term = 'reviews';
	$filter_300412_term = 'test';
	$filter_300413_term = 'hosting';
	

	// General Spam Terms
	// Filter 500: Number of occurrences of ' loan' in comment_content
	$filter_500_count = substr_count($commentdata_comment_content_lc_deslashed, ' loan');
	$filter_500_limit = 7;
	$filter_500_trackback_limit = 3;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_500_count;
	// Filter 501: Number of occurrences of 'student ' in comment_content
	$filter_501_count = substr_count($commentdata_comment_content_lc_deslashed, 'student ');
	$filter_501_limit = 11;
	$filter_501_trackback_limit = 6;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_501_count;
	// Filter 502: Number of occurrences of 'loan consolidation' in comment_content
	$filter_502_count = substr_count($commentdata_comment_content_lc_deslashed, 'loan consolidation');
	$filter_502_limit = 5;
	$filter_502_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_502_count;
	// Filter 503: Number of occurrences of 'credit card' in comment_content
	$filter_503_count = substr_count($commentdata_comment_content_lc_deslashed, 'credit card');
	$filter_503_limit = 5;
	$filter_503_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_503_count;
	// Filter 504: Number of occurrences of 'health insurance' in comment_content
	$filter_504_count = substr_count($commentdata_comment_content_lc_deslashed, 'health insurance');
	$filter_504_limit = 5;
	$filter_504_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_504_count;
	// Filter 505: Number of occurrences of 'student loan' in comment_content
	$filter_505_count = substr_count($commentdata_comment_content_lc_deslashed, 'student loan');
	$filter_505_limit = 4;
	$filter_505_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_505_count;
	// Filter 506: Number of occurrences of 'student credit card' in comment_content
	$filter_506_count = substr_count($commentdata_comment_content_lc_deslashed, 'student credit card');
	$filter_506_limit = 4;
	$filter_506_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_506_count;
	// Filter 507: Number of occurrences of 'consolidation student' in comment_content
	$filter_507_count = substr_count($commentdata_comment_content_lc_deslashed, 'consolidation student');
	$filter_507_limit = 4;
	$filter_507_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_507_count;
	// Filter 508: Number of occurrences of 'student health insurance' in comment_content
	$filter_508_count = substr_count($commentdata_comment_content_lc_deslashed, 'student health insurance');
	$filter_508_limit = 4;
	$filter_508_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_508_count;
	// Filter 509: Number of occurrences of 'student loan consolidation' in comment_content
	$filter_509_count = substr_count($commentdata_comment_content_lc_deslashed, 'student loan consolidation');
	$filter_509_limit = 4;
	$filter_509_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_509_count;
	// Filter 510: Number of occurrences of 'data entry' in comment_content
	$filter_510_count = substr_count($commentdata_comment_content_lc_deslashed, 'data entry');
	$filter_510_limit = 5;
	$filter_510_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_510_count;
	// Filter 511: Number of occurrences of 'asdf' in comment_content
	$filter_511_count = substr_count($commentdata_comment_content_lc_deslashed, 'asdf');
	$filter_511_limit = 6;
	$filter_511_trackback_limit = 2;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_511_count;

	/*
	// Medical-Related Filters Example
	$filter_set_2 = array(
						'viagra[::wpss::]2[::wpss::]2',
						'v1agra[::wpss::]1[::wpss::]1',
						);
	
	// Sex-Related Filters - Common Words occuring in Sex/Porn Spam
	$filter_set_3 = array(
						'porn[::wpss::]5[::wpss::]5',
						'teen porn[::wpss::]1[::wpss::]1',
						'rape porn[::wpss::]1[::wpss::]1',
						'incest porn[::wpss::]1[::wpss::]1',
						);

	// Pingback/Trackback Filters
	$filter_set_4 = array( 
						'[...]  [...][::wpss::]0[::wpss::]1'
						);
		
	// Test Filters
	$filter_set_5 = array( 
						'wpssteststring-3n44j57kkdsmks39248sje83njd839[::wpss::]1[::wpss::]1'
						);
	
	$filter_set_master = array_merge( $filter_set_1, $filter_set_2, $filter_set_3, $filter_set_4, $filter_set_5 );
	$filter_set_master_count = count($filter_set_master);
	*/
	
	// Complex Filters
	// Check for Optimized URL's and Keyword Phrases Occurring in Author Name and Content
	
	// Filter 10001: Number of occurrences of 'this is something special' in comment_content
	$filter_10001_count = substr_count($commentdata_comment_content_lc_deslashed, 'this is something special');
	$filter_10001_limit = 1;
	$filter_10001_trackback_limit = 1;
	// Filter 10002: Number of occurrences of 'http://groups.google.com/group/' in comment_content
	$filter_10002_count = substr_count($commentdata_comment_content_lc_deslashed, 'http://groups.google.com/group/');
	$filter_10002_limit = 1;
	$filter_10002_trackback_limit = 1;
	// Filter 10003: Number of occurrences of 'youporn' in comment_content
	$filter_10003_count = substr_count($commentdata_comment_content_lc_deslashed, 'youporn');
	$filter_10003_limit = 1;
	$filter_10003_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_10003_count;
	// Filter 10004: Number of occurrences of 'pornotube' in comment_content
	$filter_10004_count = substr_count($commentdata_comment_content_lc_deslashed, 'pornotube');
	$filter_10004_limit = 1;
	$filter_10004_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_10004_count;
	// Filter 10005: Number of occurrences of 'porntube' in comment_content
	$filter_10005_count = substr_count($commentdata_comment_content_lc_deslashed, 'porntube');
	$filter_10005_limit = 1;
	$filter_10005_trackback_limit = 1;
	$blacklist_word_combo_total = $blacklist_word_combo_total + $filter_10005_count;
	// Filter 10006: Number of occurrences of 'http://groups.google.us/group/' in comment_content
	$filter_10006_count = substr_count($commentdata_comment_content_lc_deslashed, 'http://groups.google.us/group/');
	$filter_10006_limit = 1;
	$filter_10006_trackback_limit = 1;
	
	
	// Regular Expression Tests - 2nd Gen - Comment Author/Author URL - BEGIN

	// 10500 - Complex Test for terms in Comment Author/URL - $commentdata_comment_author_lc_deslashed/$CommentAuthorURLDomain
	// Added V 1.1.1.0 - 04/14
	$CommentAuthorURLDomain = spamshield_get_domain($commentdata_comment_author_url_lc);
	// PayDay Loan Spammers
	if ( preg_match( "@((payday|students?|title)([a-z0-9-]*)loan|cash([a-z0-9-]*)advance)@i", $CommentAuthorURLDomain ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10501AU';
		}
	// PayDay Loan Spammers Group - Author URL
	if ( preg_match( "@^(ww[w0-9]\.)?(burnleytaskforce\.org\.uk|ccls5280\.org|chrislonergan\.co\.uk|getwicked\.co\.uk|kickstartmediagroup\.co\.uk|mpaydayloansa1\.info|neednotgreed\.org\.uk|royalspicehastings\.co\.uk|snakepaydayloans\.co\.uk|solarsheild\.co\.uk|transitionwestcliff\.org\.uk|blyweertbeaufort\.co\.uk|disctoprint\.co\.uk|fish-instant-payday-loans\.co\.uk|heritagenorth\.co\.uk|standardsdownload\.co\.uk|21joannapaydayloanscompany\.joannaloans\.co\.uk)$@i", $CommentAuthorURLDomain ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10502AU-PDL';
		}
	// Miscellaneous Common Spam Domains - Author URL
	// Correlates to and replaces filters 20001-20072
	if ( preg_match( "@^(ww[w0-9]\.)?(groups\.(google|yahoo)\.(com|us)|(phpbbserver|freehostia|free-site-host|keywordspy|t35|([12])50m|widecircles|netcallidus|webseomasters|mastersofseo|mysmartseo|sitemapwriter|shredderwarehouse|mmoinn|animatedfavicon|cignusweb|rsschannelwriter|clickaudit|choice-direct|experl|registry-error-cleaner|sunitawedsamit|agriimplements|submit(-trackback|bookmark)|(comment|youtube-)poster|post-comments|wordpressautocomment|grillpartssteak|tpbunblocked|sqiar|redcamtube|globaldata4u)\.com|youporn([0-9]+)\.vox\.com|blogs\.ign\.com|members\.lycos\.co\.uk|(christiantorrents\.ru|lifecity\.(tv|info))|(phpdug|kankamforum)\.net|(real-url|hit4hit)\.org|johnbeck(seminar|ssuccessstories)?\.(com|net|tv)|((youtube|facebook|twitter)\.com|youtu\.be)|((bitly|tinyurl)\.com|(bit|adf|ow)\.ly)|((ranksindia|ranksdigitalmedia|semmiami)\.(com|net|org)))$@i", $CommentAuthorURLDomain ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10510AU-MSC';
		}
	// Debt Consolidation Spammers
	if ( preg_match( "@(debt(\s*))?consolidat(ion|or|er)((\s*)loan)?@i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10521A-DC';
		}
	// SEO Spammers
	if ( preg_match( "@((internet|search|zoekmachine|social(\s*)media)(\s+)(engine(\s+))?(optimi[sz](ation|er|ing)|optimalisatie|market(ing|er)|consult(ant|ing)|rank(ing)?)|seo|sem)$@i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10601A-SEO';
		}
	if ( preg_match( "@((internet|search|zoekmachine|social(\s*)media)(\s+)(engine(\s+))?(optimi[sz](ation|er|ing)|optimalisatie|market(ing|er)|consult(ant|ing)|rank(ing)?)|link(\s*)build(ing|er)|web(\s*)(site)?(\s*)promot(ion|ing|er)|(trackback|social|comments?)(\s*)(submit(ter|ting)?|poster))@i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10602A-SEO';
		}
	// Website Design/Hosting Spammers
	if ( preg_match( "@(web(\s*)(site)?(\s*)(host(ing)?|design(er|ing)?|develop(ment|er|ing)?)|javascript|webmaster|website|template)@i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10701A-WEB';
		}
	// Online Gambling Spammers
	if ( ( preg_match( "@(online|internet|web)(\s*)(gambling|casinos?|poker|blackjack)@i", $commentdata_comment_author_lc_deslashed ) || preg_match( "@(gambling|casinos?|poker|blackjack)(\s*)(online|internet|web)@i", $commentdata_comment_author_lc_deslashed ) ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10801A-OLG';
		}
	// Medical Spammers
	// Correlates to filters 2-41 AUTH
	if ( preg_match( "@(v(([i1y])agra|c([i1y])alis|lev([i1y])tra|erect([i1y])le([\s-]*)d([i1y])sfunct([i1y])on|erect([i1y])(on|le)|xanax|z([i1y])thromax|phenterm([i1y])ne|(\s*)soma([.\s]*)|prescr([i1y])pt([i1y])on|tramadol|pen([i1y])s([\s-]*)enlargement|^pen([i1y])s([\s-]*)|^vagina([l\s]*)|buy([\s-]*)pills|(diet|weight([\s-]*)loss)([\s-]*)pill|([\s-]*)pills?([\s,-]*)|propec([i1y])a|onl([i1y])ne([\s-]*)pharmacy|med([i1y])cat([i1y])on|ephedr(([i1y])ne?|a)|val([i1y])um|ad([i1y])pex|accutane|acompl([i1y])a|r([i1y])monabant|z([i1y])mult([i1y])|herbal([i1y])fe|steroid|drug(\s+)rehab|plantar(\s+)fasciitis)@i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10901A-MED';
		}
	// Porn/Sex Spammers
	// Correlates to filters 104-159
	if ( preg_match( "@(^([\s-]*)(p(or|ro)n|sex|mast(u|e)rbat(e|ion|ing)|rap(e|er|ist|ing)|incest(ual|uous)?|bestiality|cum|hentai|pussy|penis|vagina|xxx|naked|nude|desnuda|orgasm|fuck(ing)?|dildo|ejaculat(e|ion|ing)|lesbian|gay|(homo|bi|hetero)?sexual|cumshots|anal|erotic(ism)?|clitoris|porntube|blow([\s-]*)jobs?|prostitutes?|hookers?|call([\s-]*)girls?|(escort|sex(ual)?)([\s-]*)services?|celebrit(y|ies))([\s-]*)$|([a-z0-9-]*)([\s-]+)(p(or|ro)n|sex|xxx|hentai)([\s-]*)$|(teen|rape|incest|anal|vaginal|gay|lesbian|torture|bestiality|animal|celebrit(y|ies)|cyber)([\s-]*)(porn|hentai|xxx|sex)|(sex|adult|xxx|p(or|ro)n|hentai)([\s-]*)(movie|tape|vid(s|eos?)))@i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 11001A-SXP';
		}
	// Offshore/Outsourcing Spam
	// Correlates to filters 300-423 AUTH
	if ( preg_match( "@((india|russia|ukraine|china)(\s+)(offshore|outsourc(e|ing))|(offshore|outsourc(e|ing))(\s+)(india|russia|ukraine|china)|data(\s+)entry(\s+)(india|russia|ukraine|china))@i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12001A-OFS';
		}
	// Miscellaneous Spammers
	// Correlates to filters 300-423 AUTH
	if ( preg_match( "@(modulesoft|(\s+)company|business|organization|(\s+)seminar|phpdug|(\s+)review|(\s+)sunglasses|(designer|christian(\s+)dior|hermes|michael(\s+)kors)?(\s+)handbags?|(\s+)outlet|property(\s+)vault|foreclosure|earn(\s+)money|software|home(\s+)design|(learning|how(\s*)to)(\s+)|youtube|facebook|twitter|instagram|social(\s+)bookmark|united(\s+)states|johannesburg|bucuresti|(\s+)city|for(\s+)sale|buy(\s+)(cheap|online)|property|logo(\s+)design|injury(\s+)lawyer|internas?tional|information|advertising|car(\s+)rental|rent(\s+)a(\s+)car|development|technology|forex(\s+)trading|anonymous|php(\s+)expert|travel(\s+)deals|college(\s+)student|health(\s*)(insurance|care)|click(\s+)here|visit(\s+)now|turbo(\s+)tax|photoshop|power(\s+)kite|stop(\s+)sweating?(\s+)me|sweating?(\s+)on|online(\s+)jobs|jobs(\s+)online|(pc|computer|laptop|laptopuri)(\s+)(repair|reparatii)|(repair|reparatii)(\s+)(pc|computer|laptop|laptopuri)|mobilabonnement(\s+)priser|kroatien(\s+)insel(\s+)brac|unblocked|(\s+)(coupon|discount)s?|(coupon|promo|voucher|shipping)(\s+)codes?|personalization|(\s+)homepage|(\s+)best(\s+)|bluetooth|prox(y|ies)(\s+)(surf(ing|er)?|software)|(\s+)homepage)@i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12501A-MSC';
		}
	//Misc - Author begins with Keyword
	if ( preg_match( "@^(cheap|discount(ed)?|buy)(\s+)$@i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12502A-MSC';
		}

	//Misc - Author ends with Keyword
	if ( preg_match( "@(\s+)(clothing|online|cheats?)$@i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12503A-MSC';
		}


	// Regular Expression Tests - 2nd Gen - Comment Author/Author URL - END

	$commentdata_comment_author_lc_spam_strong = '<strong>'.$commentdata_comment_author_lc_deslashed.'</strong>'; // Trackbacks

	$commentdata_comment_author_lc_spam_strong_dot1 = '...</strong>'; // Trackbacks
	$commentdata_comment_author_lc_spam_strong_dot2 = '...</b>'; // Trackbacks
	$commentdata_comment_author_lc_spam_strong_dot3 = '<strong>...'; // Trackbacks
	$commentdata_comment_author_lc_spam_strong_dot4 = '<b>...'; // Trackbacks
	$commentdata_comment_author_lc_spam_a1 = $commentdata_comment_author_lc_deslashed.'</a>'; // Trackbacks/Pingbacks
	$commentdata_comment_author_lc_spam_a2 = $commentdata_comment_author_lc_deslashed.' </a>'; // Trackbacks/Pingbacks
	
	$WPCommentsPostURL = $commentdata_blog_lc.'/wp-comments-post.php';

	$Domains = array('.ac','.academy','.actor','.ad','.ae','.aero','.af','.ag','.agency','.ai','.al','.am','.an','.ao','.aq','.ar','.archi','.arpa','.as','.asia','.at','.au','.aw','.ax','.axa','.az','.ba','.bar','.bargains','.bb','.bd','.be','.berlin','.best','.bf','.bg','.bh','.bi','.bid','.bike','.biz','.bj','.bl','.black','.blue','.bm','.bn','.bo','.boutique','.bq','.br','.bs','.bt','.build','.builders','.buzz','.bv','.bw','.by','.bz','.ca','.cab','.camera','.camp','.cards','.careers','.cat','.catering','.cc','.cd','.center','.ceo','.cf','.cg','.ch','.cheap','.christmas','.ci','.ck','.cl','.cleaning','.clothing','.club','.cm','.cn','.co','.codes','.coffee','.cologne','.com','.community','.company','.computer','.construction','.contractors','.cooking','.cool','.coop','.country','.cr','.cruises','.cu','.cv','.cw','.cx','.cy','.cz','.dance','.dating','.de','.democrat','.diamonds','.directory','.dj','.dk','.dm','.do','.domains','.dz','.ec','.edu','.education','.ee','.eg','.eh','.email','.enterprises','.equipment','.er','.es','.estate','.et','.eu','.events','.expert','.exposed','.farm','.fi','.fish','.fishing','.fj','.fk','.flights','.florist','.fm','.fo','.foundation','.fr','.futbol','.ga','.gallery','.gb','.gd','.ge','.gf','.gg','.gh','.gi','.gift','.gl','.glass','.gm','.gn','.gov','.gp','.gq','.gr','.graphics','.gs','.gt','.gu','.guitars','.guru','.gw','.gy','.haus','.hk','.hm','.hn','.holdings','.holiday','.horse','.house','.hr','.ht','.hu','.id','.ie','.il','.im','.immobilien','.in','.industries','.info','.institute','.int','.international','.io','.iq','.ir','.is','.it','.je','.jetzt','.jm','.jo','.jobs','.jp','.kaufen','.ke','.kg','.kh','.ki','.kim','.kitchen','.kiwi','.km','.kn','.koeln','.kp','.kr','.kred','.kw','.ky','.kz','.la','.land','.lb','.lc','.li','.lighting','.limo','.link','.lk','.london','.lr','.ls','.lt','.lu','.luxury','.lv','.ly','.ma','.management','.mango','.marketing','.mc','.md','.me','.meet','.menu','.mf','.mg','.mh','.miami','.mil','.mk','.ml','.mm','.mn','.mo','.mobi','.moda','.moe','.monash','.mp','.mq','.mr','.ms','.mt','.mu','.museum','.mv','.mw','.mx','.my','.mz','.na','.nagoya','.name','.nc','.ne','.net','.neustar','.nf','.ng','.ni','.ninja','.nl','.no','.np','.nr','.nu','.nyc','.nz','.okinawa','.om','.onl','.org','.pa','.partners','.parts','.pe','.pf','.pg','.ph','.photo','.photography','.photos','.pics','.pink','.pk','.pl','.plumbing','.pm','.pn','.post','.pr','.pro','.productions','.properties','.ps','.pt','.pub','.pw','.py','.qa','.qpon','.re','.recipes','.red','.ren','.rentals','.repair','.report','.rest','.reviews','.rich','.ro','.rodeo','.rs','.ru','.ruhr','.rw','.ryukyu','.sa','.saarland','.sb','.sc','.sd','.se','.sexy','.sg','.sh','.shiksha','.shoes','.si','.singles','.sj','.sk','.sl','.sm','.sn','.so','.social','.sohu','.solar','.solutions','.sr','.ss','.st','.su','.supplies','.supply','.support','.sv','.sx','.sy','.systems','.sz','.tattoo','.tc','.td','.technology','.tel','.tf','.tg','.th','.tienda','.tips','.tj','.tk','.tl','.tm','.tn','.to','.today','.tokyo','.tools','.tp','.tr','.trade','.training','.travel','.tt','.tv','.tw','.tz','.ua','.ug','.uk','.um','.uno','.us','.uy','.uz','.va','.vacations','.vc','.ve','.vegas','.ventures','.vg','.vi','.viajes','.villas','.vision','.vn','.vodka','.vote','.voting','.voto','.voyage','.vu','.wang','.watch','.webcam','.wed','.wf','.wien','.wiki','.works','.ws','.xxx','.xyz','.ye','.yokohama','.yt','.za','.zm','.zone','.zw');
	// from http://www.iana.org/domains/root/db/
	$ConversionSeparator = '-';
	$ConversionSeparators = array('-','_');
	$FilterElementsPrefix = array('http://www.','http://','https://www.','https://');
	$FilterElementsPage = array('.php','.asp','.aspx','.mspx','.cfm','.jsp','.shtml','.html','.htm','.pl','.py');
	$FilterElementsNum = array('1','2','3','4','5','6','7','8','9','0');
	$FilterElementsSlash = array('////','///','//');
	$TempPhrase1 = str_replace($FilterElementsPrefix,'',$commentdata_comment_author_url_lc);
	$TempPhrase2 = str_replace($FilterElementsPage,'',$TempPhrase1);
	$TempPhrase3 = str_replace($Domains,'',$TempPhrase2);
	$TempPhrase4 = str_replace($FilterElementsNum,'',$TempPhrase3);
	$TempPhrase5 = str_replace($FilterElementsSlash,'/',$TempPhrase4);
	$TempPhrase6 = strtolower(str_replace($ConversionSeparators,' ',$TempPhrase5));
	$KeywordURLPhrases = explode('/',$TempPhrase6);
	$KeywordURLPhrasesCount = count($KeywordURLPhrases);
	$KeywordCommentAuthorPhrasePunct = array(':',';','+','-','!','.',',','[',']','@','#','$','%','^','&','*','(',')','/','\\','|','=','_');
	$KeywordCommentAuthorTempPhrase = str_replace($KeywordCommentAuthorPhrasePunct,'',$commentdata_comment_author_lc_deslashed);
	$KeywordCommentAuthorPhrase1 = str_replace(' ','',$KeywordCommentAuthorTempPhrase);
	$KeywordCommentAuthorPhrase2 = str_replace(' ','-',$KeywordCommentAuthorTempPhrase);
	$KeywordCommentAuthorPhrase3 = str_replace(' ','_',$KeywordCommentAuthorTempPhrase);
	$KeywordCommentAuthorPhraseURLVariation = $FilterElementsPage;
	$KeywordCommentAuthorPhraseURLVariation[] = '/';
	$KeywordCommentAuthorPhraseURLVariationCount = count($KeywordCommentAuthorPhraseURLVariation);
	
	$SplogTrackbackPhrase1 		= 'an interesting post today.here\'s a quick excerpt';
	$SplogTrackbackPhrase1a 	= 'an interesting post today.here&#8217;s a quick excerpt';
	$SplogTrackbackPhrase2 		= 'an interesting post today. here\'s a quick excerpt';
	$SplogTrackbackPhrase2a 	= 'an interesting post today. here&#8217;s a quick excerpt';
	$SplogTrackbackPhrase3 		= 'an interesting post today onhere\'s a quick excerpt';
	$SplogTrackbackPhrase3a		= 'an interesting post today onhere&#8217;s a quick excerpt';
	$SplogTrackbackPhrase4 		= 'read the rest of this great post here';
	$SplogTrackbackPhrase5 		= 'here to see the original:';
		
	$SplogTrackbackPhrase20a 	= 'an interesting post today on';
	$SplogTrackbackPhrase20b 	= 'here\'s a quick excerpt';
	$SplogTrackbackPhrase20c 	= 'here&#8217;s a quick excerpt';
	
	$blacklist_word_combo_limit = 7;
	$blacklist_word_combo = 0;

	$i = 0;
	
	// Execute Simple Filter Test(s)
	if ( $filter_1_count >= $filter_1_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 1-HT';
		}
	if ( $filter_2_count >= $filter_2_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 2';
		}
	if ( $filter_2_count ) { $blacklist_word_combo++; }
	if ( $filter_3_count >= $filter_3_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 3';
		}
	if ( $filter_3_count ) { $blacklist_word_combo++; }
	if ( $filter_4_count >= $filter_4_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 4';
		}
	if ( $filter_4_count ) { $blacklist_word_combo++; }
	if ( $filter_5_count >= $filter_5_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 5';
		}
	if ( $filter_5_count ) { $blacklist_word_combo++; }
	if ( $filter_6_count >= $filter_6_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 6';
		}
	if ( $filter_6_count ) { $blacklist_word_combo++; }
	if ( $filter_7_count >= $filter_7_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 7';
		}
	if ( $filter_7_count ) { $blacklist_word_combo++; }
	if ( $filter_8_count >= $filter_8_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 8';
		}
	if ( $filter_8_count ) { $blacklist_word_combo++; }
	if ( $filter_9_count >= $filter_9_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 9';
		}
	if ( $filter_9_count ) { $blacklist_word_combo++; }
	if ( $filter_10_count >= $filter_10_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10';
		}
	if ( $filter_10_count ) { $blacklist_word_combo++; }
	if ( $filter_11_count >= $filter_11_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 11';
		}
	if ( $filter_11_count ) { $blacklist_word_combo++; }
	if ( $filter_12_count >= $filter_12_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12';
		}
	if ( $filter_12_count ) { $blacklist_word_combo++; }
	if ( $filter_13_count >= $filter_13_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 13';
		}
	if ( $filter_13_count ) { $blacklist_word_combo++; }	
	if ( $filter_14_count >= $filter_14_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 14';
		}
	if ( $filter_14_count ) { $blacklist_word_combo++; }	
	if ( $filter_15_count >= $filter_15_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 15';
		}
	if ( $filter_15_count ) { $blacklist_word_combo++; }	
	if ( $filter_16_count >= $filter_16_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 16';
		}
	if ( $filter_16_count ) { $blacklist_word_combo++; }
	if ( $filter_17_count >= $filter_17_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 17';
		}
	if ( $filter_17_count ) { $blacklist_word_combo++; }
	if ( $filter_18_count >= $filter_18_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 18';
		}
	if ( $filter_18_count ) { $blacklist_word_combo++; }
	if ( $filter_19_count >= $filter_19_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 19';
		}
	if ( $filter_19_count ) { $blacklist_word_combo++; }
	if ( $filter_20_count >= $filter_20_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 20';
		}
	if ( $filter_20_count ) { $blacklist_word_combo++; }
	if ( $filter_21_count >= $filter_21_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 21';
		}
	if ( $filter_21_count ) { $blacklist_word_combo++; }
	if ( $filter_22_count >= $filter_22_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 22';
		}
	if ( $filter_22_count ) { $blacklist_word_combo++; }
	if ( $filter_23_count >= $filter_23_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 23';
		}
	if ( $filter_23_count ) { $blacklist_word_combo++; }
	if ( $filter_24_count >= $filter_24_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 24';
		}
	if ( $filter_24_count ) { $blacklist_word_combo++; }
	if ( $filter_25_count >= $filter_25_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 25';
		}
	if ( $filter_25_count ) { $blacklist_word_combo++; }
	if ( $filter_26_count >= $filter_26_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 26';
		}
	if ( $filter_26_count ) { $blacklist_word_combo++; }
	if ( $filter_27_count >= $filter_27_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 27';
		}
	if ( $filter_27_count ) { $blacklist_word_combo++; }
	if ( $filter_28_count >= $filter_28_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 28';
		}
	if ( $filter_28_count ) { $blacklist_word_combo++; }
	if ( $filter_29_count >= $filter_29_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 29';
		}
	if ( $filter_29_count ) { $blacklist_word_combo++; }
	if ( $filter_30_count >= $filter_30_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 30';
		}
	if ( $filter_30_count ) { $blacklist_word_combo++; }
	if ( $filter_31_count >= $filter_31_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 31';
		}
	if ( $filter_31_count ) { $blacklist_word_combo++; }
	if ( $filter_32_count >= $filter_32_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 32';
		}
	if ( $filter_32_count ) { $blacklist_word_combo++; }
	if ( $filter_33_count >= $filter_33_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 33';
		}
	if ( $filter_33_count ) { $blacklist_word_combo++; }
	if ( $filter_34_count >= $filter_34_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 34';
		}
	if ( $filter_34_count ) { $blacklist_word_combo++; }
	if ( $filter_35_count >= $filter_35_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 35';
		}
	if ( $filter_35_count ) { $blacklist_word_combo++; }
	if ( $filter_36_count >= $filter_36_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 36';
		}
	if ( $filter_36_count ) { $blacklist_word_combo++; }
	if ( $filter_37_count >= $filter_37_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 37';
		}
	if ( $filter_37_count ) { $blacklist_word_combo++; }
	if ( $filter_38_count >= $filter_38_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 38';
		}
	if ( $filter_38_count ) { $blacklist_word_combo++; }
	if ( $filter_39_count >= $filter_39_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 39';
		}
	if ( $filter_39_count ) { $blacklist_word_combo++; }
	if ( $filter_40_count >= $filter_40_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 40';
		}
	if ( $filter_40_count ) { $blacklist_word_combo++; }
	if ( $filter_41_count >= $filter_41_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 41';
		}
	if ( $filter_41_count ) { $blacklist_word_combo++; }
		
	if ( $filter_104_count >= $filter_104_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 104';
		}
	if ( $filter_104_count ) { $blacklist_word_combo++; }
	if ( $filter_105_count >= $filter_105_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 105';
		}
	if ( $filter_105_count ) { $blacklist_word_combo++; }
	if ( $filter_106_count >= $filter_106_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 106';
		}
	if ( $filter_106_count ) { $blacklist_word_combo++; }
	if ( $filter_107_count >= $filter_107_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 107';
		}
	if ( $filter_107_count ) { $blacklist_word_combo++; }
	if ( $filter_108_count >= $filter_108_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 108';
		}
	if ( $filter_108_count ) { $blacklist_word_combo++; }
	if ( $filter_109_count >= $filter_109_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 109';
		}
	if ( $filter_109_count ) { $blacklist_word_combo++; }
	if ( $filter_110_count >= $filter_110_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 110';
		}
	if ( $filter_110_count ) { $blacklist_word_combo++; }
	if ( $filter_111_count >= $filter_111_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 111';
		}
	if ( $filter_111_count ) { $blacklist_word_combo++; }
	if ( $filter_112_count >= $filter_112_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 112';
		}
	if ( $filter_112_count ) { $blacklist_word_combo++; }
	if ( $filter_113_count >= $filter_113_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 113';
		}
	if ( $filter_113_count ) { $blacklist_word_combo++; }
	if ( $filter_114_count >= $filter_114_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 114';
		}
	if ( $filter_114_count ) { $blacklist_word_combo++; }
	if ( $filter_115_count >= $filter_115_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 115';
		}
	if ( $filter_115_count ) { $blacklist_word_combo++; }
	if ( $filter_116_count >= $filter_116_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 116';
		}
	if ( $filter_116_count ) { $blacklist_word_combo++; }
	if ( $filter_117_count >= $filter_117_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 117';
		}
	if ( $filter_117_count ) { $blacklist_word_combo++; }
	if ( $filter_118_count >= $filter_118_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 118';
		}
	if ( $filter_118_count ) { $blacklist_word_combo++; }
	if ( $filter_119_count >= $filter_119_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 119';
		}
	if ( $filter_119_count ) { $blacklist_word_combo++; }
	if ( $filter_120_count >= $filter_120_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 120';
		}
	if ( $filter_120_count ) { $blacklist_word_combo++; }
	if ( $filter_121_count >= $filter_121_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 121';
		}
	if ( $filter_121_count ) { $blacklist_word_combo++; }
	if ( $filter_122_count >= $filter_122_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 122';
		}
	if ( $filter_122_count ) { $blacklist_word_combo++; }
	if ( $filter_123_count >= $filter_123_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 123';
		}
	if ( $filter_123_count ) { $blacklist_word_combo++; }
	if ( $filter_124_count >= $filter_124_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 124';
		}
	if ( $filter_124_count ) { $blacklist_word_combo++; }
	if ( $filter_125_count >= $filter_125_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 125';
		}
	if ( $filter_125_count ) { $blacklist_word_combo++; }
	if ( $filter_126_count >= $filter_126_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 126';
		}
	if ( $filter_126_count ) { $blacklist_word_combo++; }
	if ( $filter_127_count >= $filter_127_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 127';
		}
	if ( $filter_127_count ) { $blacklist_word_combo++; }
	if ( $filter_128_count >= $filter_128_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 128';
		}
	if ( $filter_128_count ) { $blacklist_word_combo++; }
	if ( $filter_129_count >= $filter_129_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 129';
		}
	if ( $filter_129_count ) { $blacklist_word_combo++; }
	if ( $filter_130_count >= $filter_130_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 130';
		}
	if ( $filter_130_count ) { $blacklist_word_combo++; }
	if ( $filter_131_count >= $filter_131_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 131';
		}
	if ( $filter_131_count ) { $blacklist_word_combo++; }
	if ( $filter_132_count >= $filter_132_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 132';
		}
	if ( $filter_132_count ) { $blacklist_word_combo++; }
	if ( $filter_133_count >= $filter_133_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 133';
		}
	if ( $filter_133_count ) { $blacklist_word_combo++; }
	if ( $filter_134_count >= $filter_134_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 134';
		}
	if ( $filter_134_count ) { $blacklist_word_combo++; }
	if ( $filter_135_count >= $filter_135_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 135';
		}
	if ( $filter_135_count ) { $blacklist_word_combo++; }
	if ( $filter_136_count >= $filter_136_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 136';
		}
	if ( $filter_136_count ) { $blacklist_word_combo++; }
	if ( $filter_137_count >= $filter_137_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 137';
		}
	if ( $filter_137_count ) { $blacklist_word_combo++; }
	if ( $filter_138_count >= $filter_138_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 138';
		}
	if ( $filter_138_count ) { $blacklist_word_combo++; }
	if ( $filter_139_count >= $filter_139_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 139';
		}
	if ( $filter_139_count ) { $blacklist_word_combo++; }
	if ( $filter_140_count >= $filter_140_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 140';
		}
	if ( $filter_140_count ) { $blacklist_word_combo++; }
	if ( $filter_141_count >= $filter_141_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 141';
		}
	if ( $filter_141_count ) { $blacklist_word_combo++; }
	if ( $filter_142_count >= $filter_142_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 142';
		}
	if ( $filter_142_count ) { $blacklist_word_combo++; }
	if ( $filter_143_count >= $filter_143_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 143';
		}
	if ( $filter_143_count ) { $blacklist_word_combo++; }
	if ( $filter_144_count >= $filter_144_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 144';
		}
	if ( $filter_144_count ) { $blacklist_word_combo++; }
	if ( $filter_145_count >= $filter_145_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 145';
		}
	if ( $filter_145_count ) { $blacklist_word_combo++; }
	if ( $filter_146_count >= $filter_146_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 146';
		}
	if ( $filter_146_count ) { $blacklist_word_combo++; }
	if ( $filter_147_count >= $filter_147_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 147';
		}
	if ( $filter_147_count ) { $blacklist_word_combo++; }
	if ( $filter_148_count >= $filter_148_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 148';
		}
	if ( $filter_148_count ) { $blacklist_word_combo++; }
	if ( $filter_149_count >= $filter_149_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 149';
		}
	if ( $filter_149_count ) { $blacklist_word_combo++; }
	if ( $filter_150_count >= $filter_150_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 150';
		}
	if ( $filter_150_count ) { $blacklist_word_combo++; }
	if ( $filter_151_count >= $filter_151_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 151';
		}
	if ( $filter_151_count ) { $blacklist_word_combo++; }
	if ( $filter_152_count >= $filter_152_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 152';
		}
	if ( $filter_152_count ) { $blacklist_word_combo++; }
	if ( $filter_153_count >= $filter_153_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 153';
		}
	if ( $filter_153_count ) { $blacklist_word_combo++; }
	if ( $filter_154_count >= $filter_154_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 154';
		}
	if ( $filter_154_count ) { $blacklist_word_combo++; }
	if ( $filter_155_count >= $filter_155_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 155';
		}
	if ( $filter_155_count ) { $blacklist_word_combo++; }
	if ( $filter_156_count >= $filter_156_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 156';
		}
	if ( $filter_156_count ) { $blacklist_word_combo++; }
	if ( $filter_157_count >= $filter_157_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 157';
		}
	if ( $filter_157_count ) { $blacklist_word_combo++; }
	if ( $filter_158_count >= $filter_158_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 158';
		}
	if ( $filter_158_count ) { $blacklist_word_combo++; }
	if ( $filter_159_count >= $filter_159_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 159';
		}
	if ( $filter_159_count ) { $blacklist_word_combo++; }


	if ( $filter_500_count >= $filter_500_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 500';
		}
	if ( $filter_500_count ) { $blacklist_word_combo++; }
	if ( $filter_501_count >= $filter_501_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 501';
		}
	if ( $filter_501_count ) { $blacklist_word_combo++; }
	if ( $filter_502_count >= $filter_502_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 502';
		}
	if ( $filter_502_count ) { $blacklist_word_combo++; }
	if ( $filter_503_count >= $filter_503_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 503';
		}
	if ( $filter_503_count ) { $blacklist_word_combo++; }
	if ( $filter_504_count >= $filter_504_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 504';
		}
	if ( $filter_504_count ) { $blacklist_word_combo++; }
	if ( $filter_505_count >= $filter_505_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 505';
		}
	if ( $filter_505_count ) { $blacklist_word_combo++; }
	if ( $filter_506_count >= $filter_506_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 506';
		}
	if ( $filter_506_count ) { $blacklist_word_combo++; }
	if ( $filter_507_count >= $filter_507_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 507';
		}
	if ( $filter_507_count ) { $blacklist_word_combo++; }
	if ( $filter_508_count >= $filter_508_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 508';
		}
	if ( $filter_508_count ) { $blacklist_word_combo++; }
	if ( $filter_509_count >= $filter_509_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 509';
		}
	if ( $filter_509_count ) { $blacklist_word_combo++; }
	if ( $filter_510_count >= $filter_510_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 510';
		}
	if ( $filter_510_count ) { $blacklist_word_combo++; }
	if ( $filter_511_count >= $filter_511_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 511';
		}
	if ( $filter_511_count ) { $blacklist_word_combo++; }

	/*
	// Execute Filter Test(s)

	$i = 0;
	while ( $i <= $filter_set_master_count ) {
		$filter_phrase_parameters = explode( '[::wpss::]', $filter_set_master[$i] );
		$filter_phrase 					= $filter_phrase_parameters[0];
		$filter_phrase_limit 			= $filter_phrase_parameters[1];
		$filter_phrase_trackback_limit 	= $filter_phrase_parameters[2];
		$filter_phrase_count			= substr_count( $commentdata_comment_content_lc_deslashed, $filter_phrase );
		if ( ( $filter_phrase_limit != 0 && $filter_phrase_count >= $filter_phrase_limit ) || ( $filter_phrase_limit == 1 && strpos( $commentdata_comment_author_lc_deslashed, $filter_phrase ) !== false || $commentdata_comment_author_lc_deslashed == $filter_phrase ) ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			}
		$i++;
		}
	*/
	

	// Regular Expression Tests - 2nd Gen - Comment Content - BEGIN
	
	// Miscellaneous Patterns that Keep Repeating
	if ( preg_match( "@^([0-9]{6})\s([0-9]{6})(.*)\s([0-9]{6})$@i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10401C';
		}
	
	// PayDay Loan Spammers and the like...
	if ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?://([a-z0-9-\.]+)\.([a-z0-9-_'\"\.\/\?\&\=\s]+)(\s*)(['\"])?(\s*)>([a-z0-9-\s]*)((payday|students?|title)(\s+)loan|cash(\s+)advance)([a-z0-9-\s]*)<(\s*)/(\s*)a(\s*)>@i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10501C-PDL';
		}
	// PayDay Loan Spammers Group
	if ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?://(ww[w0-9]\.)?(burnleytaskforce\.org\.uk|ccls5280\.org|chrislonergan\.co\.uk|getwicked\.co\.uk|kickstartmediagroup\.co\.uk|mpaydayloansa1\.info|neednotgreed\.org\.uk|royalspicehastings\.co\.uk|snakepaydayloans\.co\.uk|solarsheild\.co\.uk|transitionwestcliff\.org\.uk|blyweertbeaufort\.co\.uk|disctoprint\.co\.uk|fish-instant-payday-loans\.co\.uk|heritagenorth\.co\.uk|standardsdownload\.co\.uk|21joannapaydayloanscompany\.joannaloans\.co\.uk)/?([a-z0-9-_'\"\.\?\&\=\s]*)(['\"])?>@i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10502C-PDL';
		}

	// Miscellaneous Common Spam Domains - Comment Content
	// Correlates to and replaces filters 20001-20072
	if ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?://(ww[w0-9]\.)?(groups\.(google|yahoo)\.(com|us)|play\.google\.com/store/apps/details\?id\=|(phpbbserver|freehostia|free-site-host|keywordspy|t35|([12])50m|widecircles|netcallidus|webseomasters|mastersofseo|mysmartseo|sitemapwriter|shredderwarehouse|mmoinn|animatedfavicon|cignusweb|rsschannelwriter|clickaudit|choice-direct|experl|registry-error-cleaner|sunitawedsamit|agriimplements|submit(-trackback|bookmark)|(comment|youtube-)poster|post-comments|wordpressautocomment|grillpartssteak|tpbunblocked|sqiar|redcamtube|globaldata4u)\.com|youporn([0-9]+)\.vox\.com|blogs\.ign\.com|members\.lycos\.co\.uk|([/\.])(christiantorrents\.ru|lifecity\.(tv|info))|(phpdug|kankamforum)\.net|(real-url|hit4hit)\.org|johnbeck(seminar|ssuccessstories)?\.(com|net|tv)|((ranksindia|ranksdigitalmedia|semmiami)\.(com|net|org))/?([a-z0-9-_'\"\.\?\&\=\s]*)(['\"])?>@i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10510C-MSC';
		}
	// Online Gambling Spammers
	if ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?\://([a-z0-9-\.]+\.)?([a-z0-9-_/\.\?\&\=]+)(\s*)(['\"])?(\s*)>(\s*)(debt(\s*))?consolidat(ion|or|er)((\s*)loan)?([a-z0-9-\s]*)<(\s*)/(\s*)a(\s*)>@i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10521C-DC';
		}
	// SEO Spammers
	if ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?\://([a-z0-9-\.]+\.)?([a-z0-9-_/\.\?\&\=]+)(\s*)(['\"])?(\s*)>(\s*)(([a-z0-9-\s]*)\s)?((internet|search|zoekmachine|social(\s*)media)(\s+)(engine(\s+))?(optimi[sz](ation|er|ing)|optimalisatie|market(ing|er)|consult(ant|ing)|rank(ing)?)|seo|sem)<(\s*)/(\s*)a(\s*)>@i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10601C-SEO';
		}
	if ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?\://([a-z0-9-\.]+\.)?([a-z0-9-_/\.\?\&\=]+)(\s*)(['\"])?(\s*)>(\s*)(([a-z0-9-\s]*)\s)?((internet|search|zoekmachine|social(\s*)media)(\s+)(engine(\s+))?(optimi[sz](ation|er|ing)|optimalisatie|market(ing|er)|consult(ant|ing)|rank(ing)?)|link(\s*)build(ing|er)|web(\s*)(site)?(\s*)promot(ion|ing|er)|(trackback|social|comments?)(\s*)(submit(ter|ting)?|poster))(\s*)<(\s*)/(\s*)a(\s*)>@i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10602C-SEO';
		}
	// Website Design/Hosting Spammers
	if ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?\://([a-z0-9-\.]+\.)?([a-z0-9-_/\.\?\&\=]+)(\s*)(['\"])?(\s*)>(\s*)web(\s*)(site)?(\s*)(host(ing)?|design(er|ing)?|develop(ment|er|ing)?)(\s*)<(\s*)/(\s*)a(\s*)>@i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10701C-WEB';
		}
	// Online Gambling Spammers
	if ( ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?\://([a-z0-9-\.]+\.)?([a-z0-9-_/\.\?\&\=]+)(\s*)(['\"])?(\s*)>([a-z0-9-\s]*)(online|internet|web)([a-z0-9-\s]*)(gambling|casinos?|poker|blackjack)([a-z0-9-\s]*)<(\s*)/(\s*)a(\s*)>@i", $commentdata_comment_content_lc_deslashed ) || preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?\://([a-z0-9-\.]+\.)?([a-z0-9-_/\.\?\&\=]+)(\s*)(['\"])?(\s*)>([a-z0-9-\s]*)(gambling|casinos?|poker|blackjack)([a-z0-9-\s]*)(online|internet|web)([a-z0-9-\s]*)<(\s*)/(\s*)a(\s*)>@i", $commentdata_comment_content_lc_deslashed ) ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10801C-OLG';
		}
	// Porn/Sex Spammers
	if ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?\://([a-z0-9-\.]+\.)?([a-z0-9-_/\.\?\&\=]+)(\s*)(['\"])?(\s*)>([a-z0-9-\s]*)(([a-z0-9-]*)([\s-]+)(p(or|ro)n|sex|xxx|hentai)([\s-]*)$|(teen|rape|incest|anal|vaginal|gay|lesbian|torture|bestiality|animal|celebrit(y|ies)|cyber)([\s-]*)(porn|hentai|xxx|sex)|(sex|adult|xxx|p(or|ro)n|hentai)([\s-]*)(movie|tape|vid(s|eos?)))([a-z0-9-\s]*)<(\s*)/(\s*)a(\s*)>@i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 11001C-SXP';
		}
	// Miscellaneous Spammers
	// Correlates to filters 300-423 AUTH
	if ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)https?\://([a-z0-9-\.]+\.)?([a-z0-9-_/\.\?\&\=]+)(\s*)(['\"])?(\s*)>([a-z0-9-\s]*)((oakley|gucci|prescription)(\s+)sunglasses|(designer|christian(\s+)dior|hermes|michael(\s+)kors)?(\s+)handbags?|injury(\s+)lawyer|car(\s+)rental|rent(\s+)a(\s+)car|forex(\s+)trading|(pc|computer|laptop|laptopuri)(\s+)(repair|reparatii)|(repair|reparatii)(\s+)(pc|computer|laptop|laptopuri)|(\s+)(coupon|discount)s?|(coupon|promo|voucher|shipping)(\s+)codes?|proxy(\s+)surf)([a-z0-9-\s]*)<(\s*)/(\s*)a(\s*)>@i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12501C-MSC';
		}
	// Regular Expression Tests - 2nd Gen - Comment Content - END


	// Test Comment Author 
	// Words in Comment Author Repeated in Content - With Keyword Density
	$RepeatedTermsFilters = array('.','-',':');
	$RepeatedTermsTempPhrase = str_replace($RepeatedTermsFilters,'',$commentdata_comment_author_lc_deslashed);
	$RepeatedTermsTest = explode(' ',$RepeatedTermsTempPhrase);
	$RepeatedTermsTestCount = count($RepeatedTermsTest);
	$CommentContentTotalWords = spamshield_count_words($commentdata_comment_content_lc_deslashed);
	$i = 0;
	while ( $i <= $RepeatedTermsTestCount ) {
		if ( $RepeatedTermsTest[$i] ) {
			$RepeatedTermsInContentCount = substr_count( $commentdata_comment_content_lc_deslashed, $RepeatedTermsTest[$i] );
			$RepeatedTermsInContentStrLength = spamshield_strlen($RepeatedTermsTest[$i]);
			if ( $RepeatedTermsInContentCount > 1 && $CommentContentTotalWords < $RepeatedTermsInContentCount ) {
				$RepeatedTermsInContentCount = 1;
				}
			$RepeatedTermsInContentDensity = ( $RepeatedTermsInContentCount / $CommentContentTotalWords ) * 100;
			//$spamshield_error_code .= ' 9000-'.$i.' KEYWORD: '.$RepeatedTermsTest[$i].' DENSITY: '.$RepeatedTermsInContentDensity.'% TIMES WORD OCCURS: '.$RepeatedTermsInContentCount.' TOTAL WORDS: '.$CommentContentTotalWords;
			if ( $RepeatedTermsInContentCount >= 5 && $RepeatedTermsInContentStrLength >= 4 && $RepeatedTermsInContentDensity > 40 ) {		
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' 9000-'.$i;
				}
			}
		$i++;
		}
	// Comment Author and Comment Author URL appearing in Content
	if ( $commentdata_comment_author_url_lc ) {
		$commentdata_comment_author_lc_inhref = '>'.$commentdata_comment_author_lc_deslashed.'</a>';
		
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_url_lc ) !== false && strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_inhref ) !== false ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 9100';
			}
		}
	// REGEX VERSION - TESTING
	// Stage 3 Testing - Replace 9100 in next Ver
	if ( preg_match( "@<(\s*)a(\s+)([a-z0-9-_\.\?\='\"\:\s]*)(\s*)href(\s*)=(\s*)(['\"])?(\s*)$commentdata_comment_author_url_lc_regex([a-z0-9-_'\"\.\?\&\=\s]*)(['\"])?>$commentdata_comment_author_lc_deslashed_regex<(\s*)/(\s*)a(\s*)>@i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 9100-T1';
		}

	// Emails
	if ( $commentdata_comment_author_email_lc == 'aaron@yahoo.com' || $commentdata_comment_author_email_lc == 'asdf@yahoo.com' || $commentdata_comment_author_email_lc == 'a@a.com' || $commentdata_comment_author_email_lc == 'bill@berlin.com' || $commentdata_comment_author_email_lc == 'capricanrulz@hotmail.com' || $commentdata_comment_author_email_lc == 'dominic@mail.com' || $commentdata_comment_author_email_lc == 'fuck@you.com' || $commentdata_comment_author_email_lc == 'heel@mail.com' || $commentdata_comment_author_email_lc == 'jane@mail.com' || $commentdata_comment_author_email_lc == 'neo@hotmail.com' || $commentdata_comment_author_email_lc == 'nick76@mailbox.com' || $commentdata_comment_author_email_lc == '12345@yahoo.com' || 	$commentdata_comment_author_email_lc == 'poster78@gmail.com' || $commentdata_comment_author_email_lc == 'ycp_m23@hotmail.com' || $commentdata_comment_author_email_lc == 'grey_dave@yahoo.com' || $commentdata_comment_author_email_lc == 'grren_dave55@hotmail.com' || $commentdata_comment_author_email_lc == 'dave_morales@hotmail.com' || $commentdata_comment_author_email_lc == 'tbs_guy@hotmail.com' || $commentdata_comment_author_email_lc == 'test@test.com' || strpos( $commentdata_comment_author_email_lc, '.seo@gmail.com' ) !== false || strpos( $commentdata_comment_author_email_lc, '@keywordspy.com' ) !== false || strpos( $commentdata_comment_author_email_lc, '@ranksindia.net' ) !== false || strpos( $commentdata_comment_author_email_lc, '@ranksdigitalmedia.com' ) !== false || strpos( $commentdata_comment_author_email_lc, '@semmiami.com' ) !== false || strpos( $commentdata_comment_author_email_lc, '@hit4hit.org' ) !== false || strpos( $commentdata_comment_author_email_lc, '@fuckyou.com' ) !== false || strpos( $commentdata_comment_author_email_lc, 'fuckyou@' ) !== false || strpos( $commentdata_comment_author_email_lc, 'spammer@' ) !== false || strpos( $commentdata_comment_author_email_lc, 'spambot@' ) !== false || strpos( $commentdata_comment_author_email_lc, 'spam@' ) !== false || strpos( $commentdata_comment_author_email_lc, 'anonymous@' ) !== false || strpos( $commentdata_comment_author_email_lc, 'root@' ) !== false ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 9200';
		}
	// TEST REFERRERS 1 - TO THE COMMENT PROCESSOR
	if ( strpos( $WPCommentsPostURL, $commentdata_php_self_lc ) !== false && $commentdata_referrer_lc == $WPCommentsPostURL ) {
		// Often spammers send the referrer as the URL for the wp-comments-post.php page. Nimrods.
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' REF-1-1011';
		}

	// TEST REFERRERS 2 - SPAMMERS SEARCHING FOR PAGES TO COMMENT ON
	if( $_POST[ 'ref2xJS' ] && ( $_POST[ 'ref2xJS' ] != '' ) ) {
		$ref2xJS = addslashes( urldecode( $_POST[ 'ref2xJS' ] ) );
		$ref2xJS = str_replace( '%3A', ':', $ref2xJS );
		$ref2xJS = str_replace( ' ', '+', $ref2xJS );
		$ref2xJS = esc_url_raw( $ref2xJS );
		$ref2xJS_lc = strtolower( $ref2xJS );
		if ( preg_match( "@\.google\.co(m|\.[a-z]{2})@i", $ref2xJS ) && strpos( $ref2xJS_lc, 'leave a comment' ) !== false ) {
			// make test more robust for other versions of google & search query
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' REF-2-1021';
			}
		// add Keyword Script Here
		}

	/*
	// Removed for caching
	if( $_POST[ 'ref2xPH' ] && ( $_POST[ 'ref2xPH' ] != '' ) ) {
		$ref2xPH = esc_url_raw( $_POST[ 'ref2xPH' ] );
		$ref2xPH = str_replace( '%3A', ':', $ref2xPH );
		$ref2xPH_lc = strtolower( $ref2xPH );
		if ( preg_match( "@\.google\.co(m|\.[a-z]{2})@i", $ref2xPH ) && strpos( $ref2xPH_lc, 'leave a comment' ) !== false ) {
			// make test more robust for other versions of google & search query
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' REF-2-1022';
			}
		// add Keyword Script Here
		}
	*/
		
	//Test JS Referrer for Obvious Scraping Spambots
	$post_ref2xjs_lc = strtolower($_POST['ref2xJS']);
	if ( $_POST['ref2xJS'] && strpos( $post_ref2xjs_lc, 'ref2xjs' ) !== false ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' REF-2-1023';
		}

	// TEST REFERRERS 3 - TO THE PAGE BEING COMMENTED ON
	if ( $commentdata_referrer_lc && $commentdata_referrer_lc != $commentdata_comment_post_url_lc && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		// If referrer exists, make sure referrer matches page being commented on
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' REF-3-1031';
		}
	
	// JavaScript Off NoScript Test - JSONST - will only be sent by Scraping Spambots
	if ( $_POST['JSONST'] == 'NS1' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' JSONST-1000';
		}
	
	// Spam Network - BEGIN

	// Test User-Agents
	if ( !$commentdata_user_agent_lc  ) {
		// There is no reason for a blank UA String, unless it's been altered.
		$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
		$spamshield_error_code .= ' UA1001-0';
		}
	$commentdata_user_agent_lc_word_count = spamshield_count_words($commentdata_user_agent_lc);
	if ( $commentdata_user_agent_lc && $commentdata_user_agent_lc_word_count < 3 ) {
		if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' || ( strpos( $commentdata_user_agent_lc, 'movabletype' ) === false && $commentdata_comment_type == 'trackback' ) ) {
		//if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' || ( strpos( $commentdata_user_agent_lc, 'movabletype' ) === false && ( $commentdata_comment_type == 'trackback' || $commentdata_comment_type == 'pingback' ) ) ) {
			// Another test for altered UA's.
			$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
			$spamshield_error_code .= ' UA1001-1';
			}
		}
	if ( strpos( $commentdata_user_agent_lc, 'libwww' ) !== false || preg_match( "/^(nutch|larbin|jakarta|java|mechanize|phpcrawl)/i", $commentdata_user_agent_lc ) ) {
		// There is no reason for a human to use one of these UA strings. Commonly used to attack/spam WP.
		$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
		$spamshield_error_code .= ' UA1002';
		}
	if ( strpos( $commentdata_user_agent_lc, 'iopus-' ) !== false ) {
		$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
		$spamshield_error_code .= ' UA1003';
		}
	
	if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
	
		//Test HTTP_ACCEPT
		$user_http_accept = trim(strtolower($_SERVER['HTTP_ACCEPT']));
		if ( !$user_http_accept ) {
			$content_filter_status = '1';
			$spamshield_error_code .= ' HA1001';
			}
		if ( $user_http_accept == 'application/json, text/javascript, */*; q=0.01' ) {
			$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
			$spamshield_error_code .= ' HA1002';
			}
		if ( $user_http_accept == '*' ) {
			$content_filter_status = '1';
			$spamshield_error_code .= ' HA1003';
			}
	
		//Test HTTP_ACCEPT_LANGUAGE
		$user_http_accept_language = trim($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		if ( !$user_http_accept_language ) {
			$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
			$spamshield_error_code .= ' HAL1001';
			}
		if ( $user_http_accept_language == '*' ) {
			$content_filter_status = '1';
			$spamshield_error_code .= ' HAL1002';
			}

		//Test PROXY STATUS if option
		//Google Chrome Compression Proxy Bypass
		if ( $ipProxy == 'PROXY DETECTED' && $ipProxyChromeCompression != 'TRUE' && !$spamshield_options['allow_proxy_users'] ) {
			$content_filter_status = '10';
			$spamshield_error_code .= ' PROXY1001';
			}
	
		}


	// Test IPs
	$spamshield_ip_bans = array(
								'66.60.98.1',
								'67.227.135.200',
								'74.86.148.194',
								'77.92.88.13',
								'77.92.88.27',
								'78.129.202.15',
								'78.129.202.2',
								'78.157.143.202',
								'87.106.55.101',
								'91.121.77.168',
								'92.241.176.200',
								'92.48.122.2',
								'92.48.122.3',
								'92.48.65.27',
								'92.241.168.216',
								'115.42.64.19',
								'116.71.33.252',
								'116.71.35.192',
								'116.71.59.69',
								'122.160.70.94',
								'122.162.251.167',
								'123.237.144.189',
								'123.237.144.92',
								'123.237.147.71',
								'193.37.152.242',
								'193.46.236.151',
								'193.46.236.152',
								'193.46.236.234',
								'194.44.97.14',
								);
	if ( in_array( $commentdata_remote_addr_lc, $spamshield_ip_bans ) || strpos( $commentdata_remote_addr_lc, '78.129.202.' ) === 0 || preg_match( "/^92\.48\.122\.([0-9]|[12][0-9]|3[01])$/", $commentdata_remote_addr_lc ) || strpos( $commentdata_remote_addr_lc, '116.71.' ) === 0 || preg_match( "/^123\.237\.14([47])\./", $commentdata_remote_addr_lc ) || strpos( $commentdata_remote_addr_lc, '193.37.152.' ) === 0 || strpos( $commentdata_remote_addr_lc, '193.46.236.' ) === 0 || preg_match( "/^194\.8\.7([45])\./", $commentdata_remote_addr_lc ) ) {
		// 194.8.74.0 - 194.8.75.255 BAD spam network - BRITISH VIRGIN ISLANDS
		// 193.37.152.0 - 193.37.152.255 SPAM NETWORK - WEB HOST, NOT ISP - GERMANY
		// 193.46.236.0 - 193.46.236.255 SPAM NETWORK - WEB HOST, NOT ISP - LATVIA
		// 92.48.122.0 - 92.48.122.31 SPAM NETWORK - SERVERS, NOT ISP - BELGRADE
		// KeywordSpy.com caught using IP's in the range 123.237.144. and 123.237.147.
		// 91.121.77.168 real-url.org
		
		// 87.106.55.101 SPAM NETWORK - SERVERS, NOT ISP - (.websitehome.co.uk)
		// 74.86.148.194 SPAM NETWORK - WEB HOST, NOT ISP (rover-host.com)
		// 67.227.135.200 SPAM NETWORK - WEB HOST, NOT ISP (host.lotosus.com)
		// 66.60.98.1 SPAM NETWORK - WEB SITE/HOST, NOT ISP - (rdns.softwiseonline.com)
		// 116.71.0.0 - 116.71.255.255 - SPAM NETWORK - PAKISTAN - Ptcl Triple Play Project
		// 194.44.97.14 , arkada.rovno.ua - SPAM NETWORK

		$content_filter_status = '2';
		$spamshield_error_code .= ' IP1002-'.$commentdata_remote_addr_lc;
		}
	if ( strpos( $commentdata_remote_addr_lc, '192.168.' ) === 0 && strpos( $BlogServerIP, '192.168.' ) !== 0 && strpos( $BlogServerName, 'localhost' ) === false ) {
		$content_filter_status = '2';
		$spamshield_error_code .= ' IP1003-'.$commentdata_remote_addr_lc;
		}
		
	// Reverse DNS Server Tests - BEGIN
	if ( $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
	
		/*	
		if ( preg_match( "@^host\.@i", $commentdata_remote_host_lc ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' RH1009';
			}
		*/
	
		// Test Reverse DNS Hosts - Do all with Reverse DNS moving forward
		if ( strpos( $ReverseDNS_LC, 'keywordspy.com' ) !== false ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1023';
			}
		if ( preg_match( "@clients\.your\-server\.de$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1024';
			}
		if ( preg_match( "@^rover\-host\.com$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1025';
			}
		if ( preg_match( "@^host\.lotosus\.com$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1026';
			}
		if ( preg_match( "@^rdns\.softwiseonline\.com$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1027';
			}
		if ( preg_match( "@^s([a-z0-9]+)\.websitehome\.co\.uk$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1028';
			}
		if ( preg_match( "@\.opentransfer\.com$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1029';
			}
		if ( preg_match( "@arkada\.rovno\.ua$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1030';
			}
	
		// Servers that dish out a **LOT** of spam
		
		// HOSTS NOT ISP, so all hits are from machines
		// VERIFIED SPAMMERS + HACKERS - VERY BAD REPUTATIONS
		if ( preg_match( "@^(host?|v)([0-9]+)\.server([0-9]+)\.vpn(999|2buy)\.com$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1031';
			}
			if ( preg_match( "@^(ip-)?([0-9]{1,3})-([0-9]{1,3})-([0-9]{1,3})-([0-9]{1,3})\.(rackcentre\.redstation\.net\.uk|static\.hostnoc\.net|ip\.idealhosting\.net\.tr|triolan\.net|chunkhost\.com|unknown\.steephost\.(net|com))$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1032';
			}
		if ( preg_match( "@^ns([0-9]+)\.webmasters\.com$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1033';
			}
		if ( preg_match( "@^server([0-9]+)\.(shadowbrokers|junctionmethod|([a-z0-9-]+))\.(com|net)$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1034';
			}
		if ( preg_match( "@^(hosted-by\.(ipxcore\.com|slaskdatacenter\.pl)|host\.colocrossing\.com)$@i", $ReverseDNS_LC ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1035';
			}
		if ( preg_match( "@^$commentdata_remote_addr_lc_regex\.static\.quadranet\.com$@i", $ReverseDNS_LC ) ) {
		//if ( preg_match( "@^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.static\.quadranet\.com$@i", $ReverseDNS_LC ) ) {
			// First part of server matches $commentdata_remote_addr_lc
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVD1036';
			}

		// The 8's Pattern
		// The 8's - from relakks.com - Anonymous surfing, powered by bots
		if ( preg_match( "@^anon-([0-9]+)-([0-9]+)\.relakks\.com$@i", $ReverseDNS_LC ) && preg_match( "@^([a-z]{8})$@i", $commentdata_comment_author_lc_deslashed ) && preg_match( "/^([a-z]{8})@([a-z]{8})\.com$/i", $commentdata_comment_author_email_lc ) ) {
			//anon-###-##.relakks.com spammer pattern
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVDA-1050';
			}
		// The 8's - coming from from rackcentre.redstation.net.uk
		/*
		if ( preg_match( "@^([0-9]+)-([0-9]+)-([0-9]+)-([0-9]+)\.rackcentre.redstation.net.uk$@i", $ReverseDNS ) && preg_match( "@^([a-z]{8})$@i", $commentdata_comment_author_lc_deslashed ) && preg_match( "/^([a-z]{8})@([a-z]{8})\.com$/i", $commentdata_comment_author_email_lc ) ) {
			$content_filter_status = '2';
			$spamshield_error_code .= ' REVDA-1051';
			}
		*/

		}
	// Reverse DNS Server Tests - END

	// Test Reverse DNS IP's
	/* 
	// Temporarily disabling to investigate errors - 02/22/09
	// Possibly remove permanently in next version
	// 
	// If faked to Match blog Server IP
	if ( $ReverseDNSIP == $BlogServerIP && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		$content_filter_status = '2';
		$spamshield_error_code .= ' 1031';
		}
	// If faked to be single dot
	if ( $ReverseDNSIP == '.' ) {
		$content_filter_status = '2';
		$spamshield_error_code .= ' 1032';
		}
	*/	
	// Spam Network - END


	// Test Pingbacks and Trackbacks
	if ( $commentdata_comment_type == 'pingback' || $commentdata_comment_type == 'trackback' ) {
	
		if ( $filter_1_count >= $filter_1_trackback_limit ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T1-HT';
			}
		if ( $filter_200_count >= $filter_200_trackback_limit ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T200';
			}
		if ( $filter_200_count ) { $blacklist_word_combo++; }
		if ( $commentdata_comment_type == 'trackback' && strpos( $commentdata_user_agent_lc, 'wordpress' ) !== false ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T3000';
			}

		// Check to see if IP Trackback client IP matches IP of Server where link is supposedly coming from
		if ( $commentdata_comment_type == 'trackback' ) {
			$TrackbackDomain = spamshield_get_domain($commentdata_comment_author_url_lc);
			$TrackbackIP = gethostbyname($TrackbackDomain);
			if ( $commentdata_remote_addr_lc != $TrackbackIP ) {
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T1000-FIP';
				}
			}

		// Testing if Bot Uses Faked User-Agent for WordPress version that doesn't exist yet
		// Check History of WordPress User-Agents and Keep up to Date
		if ( strpos( $commentdata_user_agent_lc, 'incutio xml-rpc -- wordpress/' ) !== false ) {
			$wp_ua_search_array = array( 'mu', 'wordpress-mu-' );
			$commentdata_user_agent_lc_wp = str_replace ( $wp_ua_search_array, '', $commentdata_user_agent_lc);
			$commentdata_user_agent_lc_explode = explode( '/', $commentdata_user_agent_lc_wp );
			// Changed to version_compare in 1.0.1.0
			//if ( $commentdata_user_agent_lc_explode[1] > WPSS_MAX_WP_VERSION && $commentdata_user_agent_lc_explode[1] !='MU' ) {
			if ( version_compare( $commentdata_user_agent_lc_explode[1], WPSS_MAX_WP_VERSION, '>' ) && $commentdata_user_agent_lc_explode[1] !='MU' ) {
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T1001';
				}
			}
		if ( strpos( $commentdata_user_agent_lc, 'the incutio xml-rpc php library -- wordpress/' ) !== false ) {
			$wp_ua_search_array = array( 'mu', 'wordpress-mu-' );
			$commentdata_user_agent_lc_wp = str_replace ( $wp_ua_search_array, '', $commentdata_user_agent_lc);
			$commentdata_user_agent_lc_explode = explode( '/', $commentdata_user_agent_lc_wp );
			if ( version_compare( $commentdata_user_agent_lc_explode[1], WPSS_MAX_WP_VERSION, '>' ) && $commentdata_user_agent_lc_explode[1] !='MU' ) {
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T1002';
				}
			}
		// 2014/04/07
		if ( strpos( $commentdata_user_agent_lc, 'wordpress/' ) === 0 ) {
			$wp_ua_search_array = array( 'mu', 'wordpress-mu-' );
			$commentdata_user_agent_lc_wp = str_replace ( $wp_ua_search_array, '', $commentdata_user_agent_lc);
			$commentdata_user_agent_lc_explode = explode( '/', $commentdata_user_agent_lc_wp );
			if ( version_compare( $commentdata_user_agent_lc_explode[1], WPSS_MAX_WP_VERSION, '>' ) && $commentdata_user_agent_lc_explode[1] !='MU' ) {
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T1003';
				}
			}

		if ( $commentdata_comment_author == $commentdata_comment_author_lc_deslashed && preg_match( "/([a-z]+)/i", $commentdata_comment_author ) ) {
			// Check to see if Comment Author is lowercase. Normal blog pings Authors are properly capitalized. No brainer.
			// Added second test to only run when using standard alphabet.
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T1010';
			}
		if ( $ipProxy == 'PROXY DETECTED' ) {
			// Check to see if Trackback/Pingback is using proxy. Real ones don't do that since they come directly from a website/server.
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T1011-FPD';
			}
		if ( $commentdata_comment_content == '[...] read more [...]' ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T1020';
			}
		if ( strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase1 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase1a ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase2 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase2a ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase3 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase3a ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase4 ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase5 ) !== false || ( strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase20a ) !== false && ( strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase20b ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase20c ) !== false ) ) ) {
			// Check to see if common patterns exist in comment content.
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2002';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong ) !== false ) {
			// Check to see if Comment Author is repeated in content, enclosed in <strong> tags.
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2003';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_a1 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_a2 ) !== false ) {
			// Check to see if Comment Author is repeated in content, enclosed in <a> tags.
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2004';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot1 ) !== false ) {
			// Check to see if Phrase... in bold is in content
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2005';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot2 ) !== false ) {
			// Check to see if Phrase... in bold is in content
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2006';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot3 ) !== false ) {
			// Check to see if Phrase... in bold is in content
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2007';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot4 ) !== false ) {
			// Check to see if Phrase... in bold is in content
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2007';
			}
		if ( preg_match( "@<strong>(.*)?\[trackback\](.*)?</strong>@i", $commentdata_comment_content_lc_deslashed ) ) {
			// Check to see if Phrase... in bold is in content
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2010';
			}
		// Check to see if keyword phrases in url match Comment Author - spammers do this to get links with desired keyword anchor text.
		// Start with url and convert to text phrase for matching against author.
		$i = 0;
		while ( $i <= $KeywordURLPhrasesCount ) {
			if ( $KeywordURLPhrases[$i] == $commentdata_comment_author_lc_deslashed ) {
				if ( !$content_filter_status ) { $content_filter_status = '1'; }

				$spamshield_error_code .= ' T3001';
				}
			if ( $KeywordURLPhrases[$i] == $commentdata_comment_content_lc_deslashed ) {
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T3002';
				}
			$i++;
			}
		// Reverse check to see if keyword phrases in url match Comment Author. Start with author and convert to url phrases.
		$i = 0;
		while ( $i <= $KeywordCommentAuthorPhraseURLVariationCount ) {
			$KeywordCommentAuthorPhrase1Version = '/'.$KeywordCommentAuthorPhrase1.$KeywordCommentAuthorPhraseURLVariation[$i];
			$KeywordCommentAuthorPhrase2Version = '/'.$KeywordCommentAuthorPhrase2.$KeywordCommentAuthorPhraseURLVariation[$i];
			$KeywordCommentAuthorPhrase3Version = '/'.$KeywordCommentAuthorPhrase3.$KeywordCommentAuthorPhraseURLVariation[$i];
			$KeywordCommentAuthorPhrase1SubStrCount = substr_count($commentdata_comment_author_url_lc, $KeywordCommentAuthorPhrase1Version);
			$KeywordCommentAuthorPhrase2SubStrCount = substr_count($commentdata_comment_author_url_lc, $KeywordCommentAuthorPhrase2Version);
			$KeywordCommentAuthorPhrase3SubStrCount = substr_count($commentdata_comment_author_url_lc, $KeywordCommentAuthorPhrase3Version);
			if ( $KeywordCommentAuthorPhrase1SubStrCount >= 1 ) {
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T3003-1-'.$KeywordCommentAuthorPhrase1Version;
				}
			else if ( $KeywordCommentAuthorPhrase2SubStrCount >= 1 ) {
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T3003-2-'.$KeywordCommentAuthorPhrase2Version;
				}
			else if ( $KeywordCommentAuthorPhrase3SubStrCount >= 1 ) {
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T3003-3-'.$KeywordCommentAuthorPhrase3Version;
				}
			$i++;
			}
		/*
		$i = 0;
		while ( $i <= $filter_set_master_count ) {
			$filter_phrase_parameters = explode( '[::wpss::]', $filter_set_master[$i] );
			$filter_phrase 					= $filter_phrase_parameters[0];
			$filter_phrase_limit 			= $filter_phrase_parameters[1];
			$filter_phrase_trackback_limit 	= $filter_phrase_parameters[2];
			$filter_phrase_count			= substr_count( $commentdata_comment_content_lc_deslashed, $filter_phrase );
			if ( $filter_phrase_count >= $filter_phrase_trackback_limit ) {
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				}
			$i++;
			}
		*/

		// Test Comment Author 
		// Words in Comment Author Repeated in Content		
		$RepeatedTermsFilters = array('.','-',':');
		$RepeatedTermsTempPhrase = str_replace($RepeatedTermsFilters,'',$commentdata_comment_author_lc_deslashed);
		$RepeatedTermsTest = explode(' ',$RepeatedTermsTempPhrase);
		$RepeatedTermsTestCount = count($RepeatedTermsTest);
		$i = 0;
		while ( $i <= $RepeatedTermsTestCount ) {
			$RepeatedTermsInContentCount = substr_count( $commentdata_comment_content_lc_deslashed, $RepeatedTermsTest[$i] );
			$RepeatedTermsInContentStrLength = spamshield_strlen($RepeatedTermsTest[$i]);
			if ( $RepeatedTermsInContentCount >= 6 && $RepeatedTermsInContentStrLength >= 4 ) {		
				if ( !$content_filter_status ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T9000-'.$i;
				}
			$i++;
			}
		}
	// Miscellaneous
	if ( $commentdata_comment_content == '[...]  [...]' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 5000';
		}
	if ( $commentdata_comment_content == '<new comment>' ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 5001';
		}
	if ( strpos( $commentdata_comment_content_lc_deslashed, 'blastogranitic atremata antiviral unteacherlike choruser coccygalgia corynebacterium reason' ) !== false ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 5002';
		}
	if ( preg_match( "/^hey i know this is off topic but i was wondering if/i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }

		$spamshield_error_code .= ' 5003';
		}
	if ( preg_match( "/^this is kind of off topic but/i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 5004';
		}


	// Execute Complex Filter Test(s)
	if ( $filter_10001_count >= $filter_10001_limit && $filter_10002_count >= $filter_10002_limit &&  ( $filter_10003_count >= $filter_10003_limit || $filter_10004_count >= $filter_10004_limit ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' CF10000';
		}
	if ( $filter_10003_count ) { $blacklist_word_combo++; }



	// Comment Author Tests
	// Deleted Filter 2AUTH-34AUTH and replaced with REGEX Versions

	if ( strpos( $commentdata_comment_author_lc_deslashed, 'buy' ) !== false && ( strpos( $commentdata_comment_author_lc_deslashed, 'online' ) !== false || strpos( $commentdata_comment_author_lc_deslashed, 'pill' ) !== false ) ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 200AUTH';
		$blacklist_word_combo++;
		}

	// Non-Medical Author Tests
	if ( $filter_210_author_count >= 1 ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 210AUTH';
		}
	if ( $filter_210_count ) { $blacklist_word_combo++; }
	
	// Comment Author Tests - Non-Trackback - SEO/WebDev/Offshore + Other
	if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
	
		// Simple Author='' Tests - Non-Trackback/Non-Pingback
		if ( $commentdata_comment_author_lc_deslashed == $filter_300400_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300400AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300401_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300401AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300402_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300402AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300403_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300403AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300404_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300404AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300405_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300405AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300406_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300406AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300407_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300407AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300408_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300408AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300409_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300409AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300410_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300410AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300411_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300411AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300412_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300412AUTH';
			}
		if ( $commentdata_comment_author_lc_deslashed == $filter_300413_term ) {
			if ( !$content_filter_status ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 300413AUTH';
			}
		}
	
	// Blacklist Word Combinations
	if ( $blacklist_word_combo >= $blacklist_word_combo_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' BLC1000';
		}
	if ( $blacklist_word_combo_total >= $blacklist_word_combo_total_limit ) {
		if ( !$content_filter_status ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' BLC1010';
		}


	// WP Blacklist Check - BEGIN
	
	// Test WP Blacklist if option set
	
	// Before long make own blacklist function - WP's is flawed with IP's
	if ( $spamshield_options['enhanced_comment_blacklist'] && !$content_filter_status ) {
		if ( wp_blacklist_check($commentdata_comment_author, $commentdata_comment_author_email, $commentdata_comment_author_url, $commentdata_comment_content, $commentdata_remote_addr, $commentdata_user_agent) ) {
			if ( !$content_filter_status ) { $content_filter_status = '100'; }
			$spamshield_error_code .= ' WP-BLACKLIST';
			}
		}
	// WP Blacklist Check - END
	
	if ( !$spamshield_error_code ) {
		$spamshield_error_code = 'No Error';
		}
	else {
		if( $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' && $_COOKIE[$CookieValidationName] != $CookieValidationKey ) {
			$spamshield_error_code .= ' COOKIE';
			}
		$spamshield_error_code = ltrim($spamshield_error_code);
		if ( $spamshield_options['comment_logging'] ) {
			spamshield_log_data( $commentdata, $spamshield_error_code );
			}
		}

	$spamshield_error_data = array( $spamshield_error_code, $blacklist_word_combo, $blacklist_word_combo_total );
	
	return $content_filter_status;
	// CONTENT FILTERING - END
	}

function spamshield_stats() {
	
	if ( version_compare( get_bloginfo( 'version' ), '2.5', '<' ) ) {
		echo '<h3>WP-SpamShield</h3>';
		}
	$spamshield_count = spamshield_count();
	$spamshield_options = get_option('spamshield_options');

	$InstallDate = $spamshield_options['install_date'];
	$CurrentDate = date('Y-m-d');
	$NumDaysInstalled = spamshield_date_diff($InstallDate, $CurrentDate);
	$AvgBlockedDaily = round( $spamshield_count / $NumDaysInstalled );
	
	if ( current_user_can('manage_options') ) {
		$SpamStatInclLink = ' (<a href="options-general.php?page='.WPSS_PLUGIN_BASENAME.'"">Settings</a>)</p>'."\n";
		$SpamStatURL = 'options-general.php?page='.WPSS_PLUGIN_BASENAME;
		$SpamStatHrefAttr = '';
		}
	else {
		$SpamStatInclLink = '';
		$SpamStatURL = 'http://www.redsandmarketing.com/plugins/wp-spamshield/';
		$SpamStatHrefAttr = 'target="_blank" rel="external"';
		}

	if ( !$spamshield_count ) {
		echo '<p>No comment spam attempts have been detected yet.'.$SpamStatInclLink;
		}
	else {
		echo '<p>'.sprintf(__("<img src='".WPSS_PLUGIN_IMG_URL."/spam-protection-24.png' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' />".' <a href="%1$s" '.$SpamStatHrefAttr.'>WP-SpamShield</a> has blocked <strong>%2$s</strong> spam comments.'), $SpamStatURL,  number_format($spamshield_count) ).'</p>'."\n";
		if ( $AvgBlockedDaily >= 2 ) {
			echo "<p><span title=\"That's ".$AvgBlockedDaily." spam comments a day that you don't have to worry about!\"><img src='".WPSS_PLUGIN_IMG_URL."/spacer.gif' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> Average spam blocked daily: <strong>".$AvgBlockedDaily."</strong></span></p>"."\n";
			}
		}
	}

function spamshield_filter_plugin_actions( $links, $file ){
	if ( $file == WPSS_PLUGIN_BASENAME ){
		$settings_link = '<a href="options-general.php?page='.WPSS_PLUGIN_BASENAME.'">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
		}
	return $links;
	}

function spamshield_comment_add_referrer_js($post_id) {
	?>
	<script type='text/javascript'>
	// <![CDATA[
	ref2xJS = escape( document[ 'referrer' ] );
	document.write("<input type='hidden' name='ref2xJS' value='"+ref2xJS+"'>");
	// ]]>
	</script>
    <?php
	
	// Removed for caching
	//$ref2xPH = esc_url_raw( $_SERVER['HTTP_REFERER'] );
	//echo '<input type="hidden" name="ref2xPH" value="'.$ref2xPH.'">'."\n";
	echo '<noscript><input type="hidden" name="JSONST" value="NS1"></noscript>'."\n";
	}

function spamshield_modify_notification( $text, $comment_id ) {

	$spamshield_options = get_option('spamshield_options');
	
	// IP / PROXY INFO - BEGIN
	$ip = $_SERVER['REMOTE_ADDR'];
	$ipBlock=explode('.',$ip);
	$ipProxyVIA=trim($_SERVER['HTTP_VIA']);
	$ipProxyVIA_LC=strtolower($ipProxyVIA);	
	$MaskedIP=trim($_SERVER['HTTP_X_FORWARDED_FOR']); // Stated Original IP - Can be faked
	$MaskedIPBlock=explode('.',$MaskedIP);
	if ( preg_match("/^([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])/", $MaskedIP ) && $MaskedIP != '' && $MaskedIP != 'unknown' && strpos( $MaskedIP, '192.168.' ) !== 0 ) {
		$MaskedIPValid=true;
		$MaskedIPCore=rtrim($MaskedIP,' unknown;,');
		}
	$ReverseDNS = gethostbyaddr($ip);
	$ReverseDNSIP = gethostbyname($ReverseDNS);
	
	if ( $ReverseDNSIP != $ip || $ip == $ReverseDNS ) {
		$ReverseDNSAuthenticity = '[Possibly Forged]';
		} 
	else {
		$ReverseDNSAuthenticity = '[Verified]';
		}
	// Detect Use of Proxy
	//if ($_SERVER['HTTP_VIA']||$_SERVER['HTTP_X_FORWARDED_FOR']) {
	if ( ( $ipProxyVIA && $ipProxyVIA !='' ) || ( $MaskedIP && $MaskedIP !='' ) ) {
		if ( !$MaskedIP || $MaskedIP == '' ) { $MaskedIP='[No Data]'; }
		$ipProxy='PROXY DETECTED';
		$ipProxyShort='PROXY';
		$ipProxyData=$ip.' | MASKED IP: '.$MaskedIP;
		$ProxyStatus='TRUE';
		//Google Chrome Compression Check
		if ( strpos( $ipProxyVIA_LC, 'chrome compression proxy' ) !== false && preg_match( "@^google-proxy-(.*)\.google\.com$@i", $ReverseDNS ) ) {
			$ipProxyChromeCompression='TRUE';
			}
		else {
			$ipProxyChromeCompression='FALSE';
			}
		}
	else {
		$ipProxy='No Proxy';
		$ipProxyShort=$ipProxy;
		$ipProxyData='[None]';
		$ProxyStatus='FALSE';
		}
	// IP / PROXY INFO - END
	$text .= "\r\nBlacklist the IP Address: ".WPSS_ADMIN_URL.'/options-general.php?page=wp-spamshield/wp-spamshield.php&wpss_action=blacklist_ip&comment_ip='.$ip;
	
	$text .= "\r\n";

	if ( !$spamshield_options['hide_extra_data'] ) {
		
		$CommentType = ucwords(get_comment_type());

		$text .= "\r\n------------------------------------------------------";
		$text .= "\r\n:: Additional Technical Data Added by WP-SpamShield ::";
		$text .= "\r\n------------------------------------------------------";
		$text .= "\r\n";
		$text .= "\r\nComment Type: ".$CommentType.' ("Comment", "Pingback", or "Trackback")';
		$text .= "\r\n";

		// DEBUG ONLY - BEGIN
		if ( strpos( WPSS_SERVER_NAME_REV, WPSS_DEBUG_SERVER_NAME_REV ) === 0 ) {
			if( $_POST[ 'ref2xJS' ] && ( $_POST[ 'ref2xJS' ] != '' ) ) {
				$ref2xJS = addslashes( urldecode( $_POST[ 'ref2xJS' ] ) );
				$ref2xJS = str_replace( '%3A', ':', $ref2xJS );
				$ref2xJS = str_replace( ' ', '+', $ref2xJS );
				$ref2xJS = esc_url_raw( $ref2xJS );
				$text .= "\r\nJavaScript Page Referrer Check: $ref2xJS\r\n";
				}

			/*
			// Removed for caching
			if( $_POST[ 'ref2xPH' ] && ( $_POST[ 'ref2xPH' ] != '' ) ) {
				$ref2xPH = esc_url_raw( $_POST[ 'ref2xPH' ] );
				$ref2xPH = str_replace( '%3A', ':', $ref2xPH );
				$text .= "\r\nPHP Page Referrer Check: $ref2xPH\r\n";
				}
			*/

			if( $_POST[ 'JSONST' ] && ( $_POST[ 'JSONST' ] != '' ) ) {
				$JSONST = sanitize_text_field( $_POST[ 'JSONST' ] );
				$text .= "\r\nJavaScript Off NoScript Test: $JSONST\r\n";
				}
			}
		// DEBUG ONLY - END
		else {
			if( $_POST[ 'ref2xJS' ] && ( $_POST[ 'ref2xJS' ] != '' ) ) {
				$ref2xJS = addslashes( urldecode( $_POST[ 'ref2xJS' ] ) );
				$ref2xJS = str_replace( '%3A', ':', $ref2xJS );
				$ref2xJS = str_replace( ' ', '+', $ref2xJS );
				$ref2xJS = esc_url_raw( $ref2xJS );
				$text .= "\r\nPage Referrer Check: $ref2xJS\r\n";
				}
			}

		$text .= "\r\nComment Processor Referrer: ".$_SERVER['HTTP_REFERER'];
		$text .= "\r\n";
		$text .= "\r\nUser-Agent: ".$_SERVER['HTTP_USER_AGENT'];
		$text .= "\r\n";
		$text .= "\r\nIP Address               : ".$ip;
		$text .= "\r\nReverse DNS              : ".$ReverseDNS;
		$text .= "\r\nReverse DNS IP           : ".$ReverseDNSIP;
		$text .= "\r\nReverse DNS Authenticity : ".$ReverseDNSAuthenticity;
		$text .= "\r\nProxy Info               : ".$ipProxy;
		$text .= "\r\nProxy Data               : ".$ipProxyData;
		$text .= "\r\nProxy Status             : ".$ProxyStatus;
		if ( $_SERVER['HTTP_VIA'] ) {
			$text .= "\r\nHTTP_VIA                 : ".$_SERVER['HTTP_VIA'];
			}
		if ( $_SERVER['HTTP_X_FORWARDED_FOR'] ) {
			$text .= "\r\nHTTP_X_FORWARDED_FOR     : ".$_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		$text .= "\r\nHTTP_ACCEPT_LANGUAGE     : ".$_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$text .= "\r\n";
		$text .= "\r\nHTTP_ACCEPT: ".$_SERVER['HTTP_ACCEPT'];
		$text .= "\r\n";
		$text .= "\r\nIP Address Lookup: http://whatismyipaddress.com/ip/".$ip;
		$text .= "\r\n";
		$text .= "\r\n(This data is helpful if you need to submit a spam sample.)";
		$text .= "\r\n";
		}
	return $text;
	}
	
function spamshield_add_ip_to_blacklist($ip_to_blacklist) {
	$blacklist_keys = trim(stripslashes(get_option('blacklist_keys')));
	$blacklist_keys_update = $blacklist_keys."\n".$ip_to_blacklist;
	update_option('blacklist_keys', $blacklist_keys_update);
	}

function wpss_check_if_spider() {
	global $spider_status_check;
	$spider_status_check = 0;
	// The following is to reduce server load & bandwidth. Bots just don't need jscripts.php file.
	if ( $_SERVER['REMOTE_ADDR'] == WPSS_SERVER_ADDR ) {
		$spider_status_check = 1;
		}
	else {
		// Add spiders to this array
		$wpss_spiders_array = array( 'googlebot', 'google.com', 'googleproducer', 'feedfetcher-google', 'google wireless transcoder', 'google favicon', 'mediapartners-google', 'adsbot-google', 'yahoo', 'slurp', 'msnbot', 'bingbot', 'gtmetrix', 'wordpress', 'twitterfeed', 'feedburner', 'ia_archiver', 'spider', 'crawler', 'search', 'bot', 'offline', 'download', 'validator', 'link', 'user-agent:', 'curl', 'httpclient', 'jakarta', 'java/', 'larbin', 'libwww', 'lwp-trivial', 'mechanize', 'nutch', 'parser', 'php/', 'python-urllib ', 'wget', 'snoopy', 'binget', 'lftp/', '!susie', 'arachmo', 'automate', 'cerberian', 'charlotte', 'cocoal.icio.us', 'copier', 'cosmos', 'covario', 'csscheck', 'cynthia', 'emailsiphon', 'extractor', 'ezooms', 'feedly', 'getright', 'heritrix', 'holmes', 'htdig', 'htmlparser', 'httrack', 'igdespyder', 'internetseer', 'itunes', 'l.webis', 'mabontland', 'magpie', 'metauri', 'mogimogi', 'morning paper', 'mvaclient', 'newsgator', 'nymesis', 'oegp', 'peach', 'pompos', 'pxyscand', 'qseero', 'reaper', 'sbider', 'scoutjet', 'scrubby', 'semanticdiscovery', 'snagger', 'silk', 'snappy', 'sqworm', 'stackrambler', 'stripper', 'sucker', 'teoma', 'truwogps', 'updated', 'vyu2', 'webcapture', 'webcopier', 'webzip', 'windows-media-player', 'yeti' );
		$wpss_spiders_array_count = count($wpss_spiders_array);
		// Loop through each spider and check if it appears in:
		// the User Agent
		$user_agent_lc = strtolower($_SERVER['HTTP_USER_AGENT']);
		$i = 0;
		while ($i < $wpss_spiders_array_count) {
			if ( strpos( $user_agent_lc, $wpss_spiders_array[$i] ) !== false ) {
				$spider_status_check = 1;
				break;
				}
			$i++;
			}
		}
	$_SESSION['spider_status_check'] = $spider_status_check;
	return $spider_status_check;
	}

if (!class_exists('wpSpamShield')) {
    class wpSpamShield {
	
		/**
		* @var string   The name the options are saved under in the database.
		*/
		var $adminOptionsName = 'wp_spamshield_options';
		
		/**
		* @var string   The name of the database table used by the plugin
		*/	
		var $db_table_name = 'wp_spamshield';
		
		/**
		* PHP 4 Compatible Constructor
		*/
		//function wpSpamShield(){$this->__construct();}

		/**
		* PHP 5 Constructor
		*/		
		//function __construct(){

		function wpSpamShield(){
			
			global $wpdb;
					
			error_reporting(0); // Prevents error display
			
			register_activation_hook(__FILE__,array(&$this,'install_on_activation'));
			add_action('init', 'spamshield_init');
			add_action('init', 'widget_spamshield_register');
			add_action('init', 'wpss_start_session', 1);
			add_action('wp_logout', 'wpss_end_session');
			add_action('wp_login', 'wpss_end_session');
			add_action('admin_menu', array(&$this,'add_admin_pages'));
			add_action('wp_head', array(&$this, 'wp_head_intercept'));
			add_filter('the_content', 'spamshield_contact_form', 10);
			add_filter('the_content', 'spamshield_content_addendum', 999);
			add_action('comment_form', 'spamshield_comment_form',10);
			add_action('comment_form', 'spamshield_comment_add_referrer_js',1);
			add_action('preprocess_comment', 'spamshield_check_comment_type',1);
			add_filter('comment_notification_text', 'spamshield_modify_notification', 10, 2);
			add_filter('comment_moderation_text', 'spamshield_modify_notification', 10, 2);
			add_action('activity_box_end', 'spamshield_stats');
			add_filter('plugin_action_links', 'spamshield_filter_plugin_actions', 10, 2 );
			add_shortcode( 'spamshieldcountersm', 'spamshield_counter_sm_short' );
			add_shortcode( 'spamshieldcounter', 'spamshield_counter_short' );
			add_shortcode( 'spamshieldcontact', 'spamshield_contact_shortcode' );
        	}

		function wpss_start_session(){
			$wpss_session_test = session_id();
			if( empty($wpss_session_test) && !headers_sent() ) {
				session_start();
				global $wpss_session_id;
				$wpss_session_id = session_id();
				}
			}

		function wpss_end_session(){
			session_destroy();
			}

		function add_admin_pages(){
			if ( current_user_can('manage_options') ) { // was 'level_8'
				add_submenu_page("options-general.php","WP-SpamShield","WP-SpamShield",1, __FILE__, array(&$this,"output_existing_menu_sub_admin_page"));
				}
			}
		
		function output_existing_menu_sub_admin_page(){
			
			?>
			<div class="wrap">
			<h2>WP-SpamShield</h2>
			
			<?php
			if ( !current_user_can('manage_options') ) {
				wp_die('Insufficient privileges!');
				}

			$spamshield_count = spamshield_count();
			$spamshield_options = get_option('spamshield_options');
		
			$InstallDate = $spamshield_options['install_date'];
			$CurrentDate = date('Y-m-d');
			$NumDaysInstalled = spamshield_date_diff($InstallDate, $CurrentDate);
			$AvgBlockedDaily = round( $spamshield_count / $NumDaysInstalled );

			$installation_plugins_get_test_1		= WPSS_PLUGIN_BASENAME; // 'wp-spamshield/wp-spamshield.php'
			$installation_file_test_0 				= WPSS_PLUGIN_FILE_PATH; // '/public_html/wp-content/plugins/wp-spamshield/wp-spamshield.php'
			if ( file_exists( ABSPATH.'wp-load.php' ) ) {
				// WP 2.6+
				$installation_file_test_1 			= ABSPATH.'wp-load.php'; // '/public_html/wp-load.php'
				$installation_file_test_1_status	= true;
				}
			else {
				// Before 2.6
				$installation_file_test_1 			= ABSPATH.'wp-config.php'; // '/public_html/wp-config.php'
				if ( file_exists( $installation_file_test_1 ) ) {
					$installation_file_test_1_status = true;
					}
				}
			$installation_file_test_2 				= WPSS_PLUGIN_IMG_PATH.'/img.php';
			$installation_file_test_3 				= WPSS_PLUGIN_JS_PATH.'/jscripts.php';
			
			clearstatcache();
			$installation_file_test_2_perm = substr(sprintf('%o', fileperms($installation_file_test_2)), -4);
			$installation_file_test_3_perm = substr(sprintf('%o', fileperms($installation_file_test_3)), -4);
			if ( $installation_file_test_2_perm < '0755' || $installation_file_test_3_perm < '0755' || !is_readable($installation_file_test_2) || !is_executable($installation_file_test_2) || !is_readable($installation_file_test_3) || !is_executable($installation_file_test_3) ) {
				@chmod( $installation_file_test_2, 0755 );
				@chmod( $installation_file_test_3, 0755 );
				}
			clearstatcache();
			if ( $installation_plugins_get_test_1 == $_GET['page'] && file_exists($installation_file_test_0) && $installation_file_test_1_status && file_exists($installation_file_test_2) && file_exists($installation_file_test_3) ) {
			//if ( $installation_plugins_get_test_1 == $_GET['page'] && file_exists($installation_file_test_0) && $installation_file_test_1_status && file_exists($installation_file_test_2) && file_exists($installation_file_test_3) && $installation_file_test_2_perm == '0644' && $installation_file_test_3_perm == '0644' ) {
				$wp_installation_status = 1;
				$wp_installation_status_image = 'status-installed-correctly-24';
				$wp_installation_status_color = 'green';
				$wp_installation_status_bg_color = '#CCFFCC';
				$wp_installation_status_msg_main = 'Installed Correctly';
				$wp_installation_status_msg_text = strtolower($wp_installation_status_msg_main);
				}
			else {
				$wp_installation_status = 0;
				$wp_installation_status_image = 'status-not-installed-correctly-24';
				$wp_installation_status_color = 'red';
				$wp_installation_status_bg_color = '#FFCCCC';
				$wp_installation_status_msg_main = 'Not Installed Correctly';
				$wp_installation_status_msg_text = strtolower($wp_installation_status_msg_main);
				}

			if ( $_REQUEST['submit_wpss_general_options'] && current_user_can('manage_options') && check_admin_referer('wpss_general_options_nonce') ) {
				echo '<div class="updated fade"><p>Plugin Spam settings saved.</p></div>';
				}
			if ( $_REQUEST['submit_wpss_contact_options'] && current_user_can('manage_options') && check_admin_referer('wpss_contact_options_nonce')  ) {
				echo '<div class="updated fade"><p>Plugin Contact Form settings saved.</p></div>';
				}
			if ( $_REQUEST['wpss_action'] == 'blacklist_ip' && $_REQUEST['comment_ip'] && current_user_can('manage_options') && !$_REQUEST['submit_wpss_general_options'] && !$_REQUEST['submit_wpss_contact_options'] ) {
				$ip_to_blacklist = trim(stripslashes($_REQUEST['comment_ip']));
				if ( preg_match("/^([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])$/" ,$ip_to_blacklist ) ) {
					$$ip_to_blacklist_valid='1';
					spamshield_add_ip_to_blacklist($ip_to_blacklist);
					echo '<div class="updated fade"><p>IP Address added to Comment Blacklist.</p></div>';
					}
				else {
					echo '<div class="updated fade"><p>Invalid IP Address - not added to Comment Blacklist.</p></div>';
					}
				}

			?>
			<div style='width:600px;border-style:solid;border-width:1px;border-color:<?php echo $wp_installation_status_color; ?>;background-color:<?php echo $wp_installation_status_bg_color; ?>;padding:0px 15px 0px 15px;margin-top:15px;'>
			<p><strong><?php echo "<img src='".WPSS_PLUGIN_IMG_URL."/".$wp_installation_status_image.".png' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> Installation Status: <span style='color:".$wp_installation_status_color.";'>".$wp_installation_status_msg_main."</span>"; ?></strong></p>
			</div>

			
			<?php
			if ($spamshield_count) {
				echo "\n			<br />\n			<div style='width:600px;border-style:solid;border-width:1px;border-color:#000033;background-color:#CCCCFF;padding:0px 15px 0px 15px;'>\n			<p><img src='".WPSS_PLUGIN_IMG_URL."/spam-protection-24.png' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> WP-SpamShield has blocked <strong>".number_format($spamshield_count)."</strong> spam comments!</p>\n			";
				if ( $AvgBlockedDaily >= 2 ) {
					echo "<p><img src='".WPSS_PLUGIN_IMG_URL."/spacer.gif' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> That's <strong>".$AvgBlockedDaily."</strong> spam comments a day that you don't have to worry about.</p>\n			";
					}
				echo "</div>\n			";
				}
			$spamshield_options = get_option('spamshield_options');
			if ( !$spamshield_options['install_date'] ) {
				$InstallDate = date('Y-m-d');
				}
			else { 
				$InstallDate = $spamshield_options['install_date'];
				}
			if ($_REQUEST['submitted_wpss_general_options'] && current_user_can('manage_options') && check_admin_referer('wpss_general_options_nonce') ) {
				if ( $_REQUEST['comment_logging'] && !$spamshield_options['comment_logging_start_date'] ) {
					$CommentLoggingStartDate = time();
					spamshield_log_reset();
					}
				else if ( $_REQUEST['comment_logging'] && $spamshield_options['comment_logging_start_date'] ) {
					$CommentLoggingStartDate = $spamshield_options['comment_logging_start_date'];
					}				
				else {
					$CommentLoggingStartDate = 0;
					}
				// Validate Request Values
				$valid_req_spamshield_options = $_REQUEST;
				if ( $_REQUEST['block_all_trackbacks'] 			== 'on' )	{ $valid_req_spamshield_options['block_all_trackbacks'] 		= 1; }
				if ( $_REQUEST['block_all_pingbacks'] 			== 'on' ) 	{ $valid_req_spamshield_options['block_all_pingbacks'] 			= 1; }
				if ( $_REQUEST['use_alt_cookie_method']			== 'on' ) 	{ $valid_req_spamshield_options['use_alt_cookie_method'] 		= 1; }
				if ( $_REQUEST['use_alt_cookie_method_only'] 	== 'on' ) 	{ $valid_req_spamshield_options['use_alt_cookie_method_only'] 	= 1; }
				if ( $_REQUEST['comment_logging'] 				== 'on' ) 	{ $valid_req_spamshield_options['comment_logging'] 				= 1; }
				if ( $_REQUEST['comment_logging_all'] 			== 'on' ) 	{ $valid_req_spamshield_options['comment_logging_all'] 			= 1; }
				if ( $_REQUEST['enhanced_comment_blacklist'] 	== 'on' ) 	{ $valid_req_spamshield_options['enhanced_comment_blacklist'] 	= 1; }
				if ( $_REQUEST['allow_proxy_users'] 			== 'on' ) 	{ $valid_req_spamshield_options['allow_proxy_users'] 			= 1; }
				if ( $_REQUEST['hide_extra_data'] 				== 'on' ) 	{ $valid_req_spamshield_options['hide_extra_data'] 				= 1; }
				if ( $_REQUEST['promote_plugin_link'] 			== 'on' ) 	{ $valid_req_spamshield_options['promote_plugin_link'] 			= 1; }
				// Update Values
				$spamshield_options_update = array (
						'cookie_validation_name' 				=> $spamshield_options['cookie_validation_name'],
						'cookie_validation_key' 				=> $spamshield_options['cookie_validation_key'],
						'form_validation_field_js' 				=> $spamshield_options['form_validation_field_js'],
						'form_validation_key_js' 				=> $spamshield_options['form_validation_key_js'],
						'cookie_get_function_name' 				=> $spamshield_options['cookie_get_function_name'],
						'cookie_set_function_name' 				=> $spamshield_options['cookie_set_function_name'],
						'cookie_delete_function_name' 			=> $spamshield_options['cookie_delete_function_name'],
						'comment_validation_function_name' 		=> $spamshield_options['comment_validation_function_name'],
						'last_key_update'						=> $spamshield_options['last_key_update'],
						'wp_cache' 								=> $spamshield_options['wp_cache'],
						'wp_super_cache' 						=> $spamshield_options['wp_super_cache'],
						'block_all_trackbacks' 					=> $valid_req_spamshield_options['block_all_trackbacks'],
						'block_all_pingbacks' 					=> $valid_req_spamshield_options['block_all_pingbacks'],
						'use_alt_cookie_method' 				=> $valid_req_spamshield_options['use_alt_cookie_method'],
						'use_alt_cookie_method_only' 			=> $valid_req_spamshield_options['use_alt_cookie_method_only'],
						'use_captcha_backup' 					=> $spamshield_options['use_captcha_backup'],
						'use_trackback_verification' 			=> $spamshield_options['use_trackback_verification'],
						'comment_logging'						=> $valid_req_spamshield_options['comment_logging'],
						'comment_logging_start_date'			=> $CommentLoggingStartDate,
						'comment_logging_all'					=> $valid_req_spamshield_options['comment_logging_all'],
						'enhanced_comment_blacklist'			=> $valid_req_spamshield_options['enhanced_comment_blacklist'],
						'allow_proxy_users'						=> $valid_req_spamshield_options['allow_proxy_users'],
						'hide_extra_data'						=> $valid_req_spamshield_options['hide_extra_data'],
						'form_include_website' 					=> $spamshield_options['form_include_website'],
						'form_require_website' 					=> $spamshield_options['form_require_website'],
						'form_include_phone' 					=> $spamshield_options['form_include_phone'],
						'form_require_phone' 					=> $spamshield_options['form_require_phone'],
						'form_include_company' 					=> $spamshield_options['form_include_company'],
						'form_require_company' 					=> $spamshield_options['form_require_company'],
						'form_include_drop_down_menu'			=> $spamshield_options['form_include_drop_down_menu'],
						'form_require_drop_down_menu'			=> $spamshield_options['form_require_drop_down_menu'],
						'form_drop_down_menu_title'				=> $spamshield_options['form_drop_down_menu_title'],
						'form_drop_down_menu_item_1'			=> $spamshield_options['form_drop_down_menu_item_1'],
						'form_drop_down_menu_item_2'			=> $spamshield_options['form_drop_down_menu_item_2'],
						'form_drop_down_menu_item_3'			=> $spamshield_options['form_drop_down_menu_item_3'],
						'form_drop_down_menu_item_4'			=> $spamshield_options['form_drop_down_menu_item_4'],
						'form_drop_down_menu_item_5'			=> $spamshield_options['form_drop_down_menu_item_5'],
						'form_drop_down_menu_item_6'			=> $spamshield_options['form_drop_down_menu_item_6'],
						'form_drop_down_menu_item_7'			=> $spamshield_options['form_drop_down_menu_item_7'],
						'form_drop_down_menu_item_8'			=> $spamshield_options['form_drop_down_menu_item_8'],
						'form_drop_down_menu_item_9'			=> $spamshield_options['form_drop_down_menu_item_9'],
						'form_drop_down_menu_item_10'			=> $spamshield_options['form_drop_down_menu_item_10'],
						'form_message_width' 					=> $spamshield_options['form_message_width'],
						'form_message_height' 					=> $spamshield_options['form_message_height'],
						'form_message_min_length' 				=> $spamshield_options['form_message_min_length'],
						'form_message_recipient' 				=> $spamshield_options['form_message_recipient'],
						'form_response_thank_you_message' 		=> $spamshield_options['form_response_thank_you_message'],
						'form_include_user_meta' 				=> $spamshield_options['form_include_user_meta'],
						'promote_plugin_link' 					=> $valid_req_spamshield_options['promote_plugin_link'],
						'install_date'							=> $InstallDate,
						);
				update_option('spamshield_options', $spamshield_options_update);
				//$blacklist_keys = trim(stripslashes(get_option('blacklist_keys')));
				$blacklist_keys_update = trim(stripslashes($_REQUEST['wordpress_comment_blacklist']));
				update_option('blacklist_keys', $blacklist_keys_update);
				}
			if ( $_REQUEST['submitted_wpss_contact_options'] && current_user_can('manage_options') && check_admin_referer('wpss_contact_options_nonce') ) {
				// Validate Request Values
				$valid_req_spamshield_options = $_REQUEST;
				if ( $_REQUEST['form_include_user_meta'] 		== 'on' ) 	{ $valid_req_spamshield_options['form_include_user_meta'] 		= 1; }
				$spamshield_options_update = array (
						'cookie_validation_name' 				=> $spamshield_options['cookie_validation_name'],
						'cookie_validation_key' 				=> $spamshield_options['cookie_validation_key'],
						'form_validation_field_js' 				=> $spamshield_options['form_validation_field_js'],
						'form_validation_key_js' 				=> $spamshield_options['form_validation_key_js'],
						'cookie_get_function_name' 				=> $spamshield_options['cookie_get_function_name'],
						'cookie_set_function_name' 				=> $spamshield_options['cookie_set_function_name'],
						'cookie_delete_function_name' 			=> $spamshield_options['cookie_delete_function_name'],
						'comment_validation_function_name' 		=> $spamshield_options['comment_validation_function_name'],
						'last_key_update'						=> $spamshield_options['last_key_update'],
						'wp_cache' 								=> $spamshield_options['wp_cache'],
						'wp_super_cache' 						=> $spamshield_options['wp_super_cache'],
						'block_all_trackbacks' 					=> $spamshield_options['block_all_trackbacks'],
						'block_all_pingbacks' 					=> $spamshield_options['block_all_pingbacks'],
						'use_alt_cookie_method' 				=> $spamshield_options['use_alt_cookie_method'],
						'use_alt_cookie_method_only'			=> $spamshield_options['use_alt_cookie_method_only'],
						'use_captcha_backup' 					=> $spamshield_options['use_captcha_backup'],
						'use_trackback_verification' 			=> $spamshield_options['use_trackback_verification'],
						'comment_logging'						=> $spamshield_options['comment_logging'],
						'comment_logging_start_date'			=> $spamshield_options['comment_logging_start_date'],
						'comment_logging_all'					=> $spamshield_options['comment_logging_all'],
						'enhanced_comment_blacklist'			=> $spamshield_options['enhanced_comment_blacklist'],
						'allow_proxy_users'						=> $spamshield_options['allow_proxy_users'],
						'hide_extra_data'						=> $spamshield_options['hide_extra_data'],
						'form_include_website' 					=> $_REQUEST['form_include_website'],
						'form_require_website' 					=> $_REQUEST['form_require_website'],
						'form_include_phone' 					=> $_REQUEST['form_include_phone'],
						'form_require_phone' 					=> $_REQUEST['form_require_phone'],
						'form_include_company' 					=> $_REQUEST['form_include_company'],
						'form_require_company' 					=> $_REQUEST['form_require_company'],
						'form_include_drop_down_menu'			=> $_REQUEST['form_include_drop_down_menu'],
						'form_require_drop_down_menu'			=> $_REQUEST['form_require_drop_down_menu'],
						'form_drop_down_menu_title'				=> trim(stripslashes($_REQUEST['form_drop_down_menu_title'])),
						'form_drop_down_menu_item_1'			=> trim(stripslashes($_REQUEST['form_drop_down_menu_item_1'])),
						'form_drop_down_menu_item_2'			=> trim(stripslashes($_REQUEST['form_drop_down_menu_item_2'])),
						'form_drop_down_menu_item_3'			=> trim(stripslashes($_REQUEST['form_drop_down_menu_item_3'])),
						'form_drop_down_menu_item_4'			=> trim(stripslashes($_REQUEST['form_drop_down_menu_item_4'])),
						'form_drop_down_menu_item_5'			=> trim(stripslashes($_REQUEST['form_drop_down_menu_item_5'])),
						'form_drop_down_menu_item_6'			=> trim(stripslashes($_REQUEST['form_drop_down_menu_item_6'])),
						'form_drop_down_menu_item_7'			=> trim(stripslashes($_REQUEST['form_drop_down_menu_item_7'])),
						'form_drop_down_menu_item_8'			=> trim(stripslashes($_REQUEST['form_drop_down_menu_item_8'])),
						'form_drop_down_menu_item_9'			=> trim(stripslashes($_REQUEST['form_drop_down_menu_item_9'])),
						'form_drop_down_menu_item_10'			=> trim(stripslashes($_REQUEST['form_drop_down_menu_item_10'])),
						'form_message_width' 					=> trim(stripslashes($_REQUEST['form_message_width'])),
						'form_message_height' 					=> trim(stripslashes($_REQUEST['form_message_height'])),
						'form_message_min_length' 				=> trim(stripslashes($_REQUEST['form_message_min_length'])),
						'form_message_recipient' 				=> trim(stripslashes($_REQUEST['form_message_recipient'])),
						'form_response_thank_you_message' 		=> trim(stripslashes($_REQUEST['form_response_thank_you_message'])),
						'form_include_user_meta' 				=> $valid_req_spamshield_options['form_include_user_meta'],
						'promote_plugin_link' 					=> $spamshield_options['promote_plugin_link'],
						'install_date'							=> $InstallDate,
						);
				update_option('spamshield_options', $spamshield_options_update);
				}
			$spamshield_options = get_option('spamshield_options');
			?>
			
			<div style="width:305px;height:305px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;clear:left;">
			
			<p><a name="wpss_top"><strong>Quick Navigation - Contents</strong></a></p>
			
			<ol style="list-style-type:decimal;padding-left:30px;">
				<li><a href="#wpss_general_options">General Options</a></li>
				<li><a href="#wpss_contact_form_options">Contact Form Options</a></li>
				<li><a href="#wpss_installation_instructions">Installation Instructions</a></li>
				<li><a href="#wpss_displaying_stats">Displaying Spam Stats on Your Blog</a></li>
				<li><a href="#wpss_adding_contact_form">Adding a Contact Form to Your Blog</a></li>
				<li><a href="#wpss_configuration">Configuration Information</a></li>
				<li><a href="#wpss_known_conflicts">Known Plugin Conflicts</a></li>
				<li><a href="#wpss_troubleshooting">Troubleshooting Guide / Support</a></li>
				<li><a href="#wpss_let_others_know">Let Others Know About WP-SpamShield</a></li>
				<li><a href="#wpss_download_plugin_documentation">Download Plugin / Documentation</a></li>
			</ol>
			</div>
			
			<div style="width:250px;height:305px;border-style:solid;border-width:1px;border-color:#000033;background-color:#CCCCFF;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;">
			
			<p>
			<?php if ( $spamshield_count > 100 ) { ?>
			<strong>Happy with WP-SpamShield?</strong><br /> Let others know by <a href="http://wordpress.org/extend/plugins/wp-spamshield/" target="_blank" rel="external" >giving it a good rating</a> on WordPress.org!<br />
			<img src='<?php echo WPSS_PLUGIN_IMG_URL; ?>/5-stars-rating.gif' alt='' width='99' height='19' style='border-style:none;padding-top:3px;padding-bottom:0px;' /><br /><br />
			<?php } ?>
			
			<strong>Documentation:</strong> <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" target="_blank" rel="external" >Plugin Homepage</a><br />
			<strong>Tech Support:</strong> <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/support/" target="_blank" rel="external" >WP-SpamShield Support</a><br />
			<strong>Follow on Twitter:</strong> <a href="http://twitter.com/WPSpamShield" target="_blank" rel="external" >@WPSpamShield</a><br />			
			<strong>Let Others Know:</strong> <a href="http://www.redsandmarketing.com/blog/wp-spamshield-wordpress-plugin-released/#comments" target="_blank" rel="external" >Leave Comments</a><br />
            
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="margin-top:10px;">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="DFMTNHJEPFFUL">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
			</p>
			
			</div>


			<p style="clear:both;">&nbsp;</p>
			
			<p><a name="wpss_general_options"><strong>General Options</strong></a></p>

			<form name="wpss_general_options" method="post">
			<input type="hidden" name="submitted_wpss_general_options" value="1" />
            <?php
			wp_nonce_field('wpss_general_options_nonce') ?>

			<fieldset class="options">
				<ul style="list-style-type:none;padding-left:30px;">
					<li>
					<label for="use_alt_cookie_method">
						<input type="checkbox" id="use_alt_cookie_method" name="use_alt_cookie_method" <?php echo ($spamshield_options['use_alt_cookie_method']==true?"checked=\"checked\"":"") ?> />
						<strong>M2 - Use two methods to set cookies.</strong><br />This adds a secondary non-JavaScript method to set cookies in addition to the standard JS method.<br />&nbsp;
					</label>
					</li>
										
					<li>
					<label for="comment_logging">
						<input type="checkbox" id="comment_logging" name="comment_logging" <?php echo ($spamshield_options['comment_logging']==true?"checked=\"checked\"":"") ?> />
						<strong>Blocked Comment Logging Mode</strong><br />Temporary diagnostic mode that logs blocked comment submissions for 7 days, then turns off automatically.<br />Log is cleared each time this feature is turned on.<br /><em>May use slightly higher server resources, so for best performance, only use when necessary. (Most websites won't notice any difference.)</em>
					</label>
					<?php
					if ( $spamshield_options['comment_logging'] ) {			
						$wpss_log_filename = 'temp-comments-log.txt';
						$wpss_log_empty_filename = 'temp-comments-log.init.txt';
						$wpss_htaccess_filename = '.htaccess';
						$wpss_htaccess_orig_filename = 'htaccess.txt';
						$wpss_htaccess_empty_filename = 'htaccess.init.txt';
						$wpss_log_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_log_filename;
						$wpss_log_empty_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_log_empty_filename;
						$wpss_htaccess_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_htaccess_filename;
						$wpss_htaccess_orig_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_htaccess_orig_filename;
						$wpss_htaccess_empty_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_htaccess_empty_filename;
						
						clearstatcache();
						if ( !file_exists( $wpss_htaccess_file ) ) {
							@chmod( WPSS_PLUGIN_DATA_PATH, 0775 );
							@chmod( $wpss_htaccess_orig_file, 0666 );
							@chmod( $wpss_htaccess_empty_file, 0666 );
							@rename( $wpss_htaccess_orig_file, $wpss_htaccess_file );
							@copy( $wpss_htaccess_empty_file, $wpss_htaccess_orig_file );
							}

						clearstatcache();
						$wpss_perm_log_dir = substr(sprintf('%o', fileperms(WPSS_PLUGIN_DATA_PATH)), -4);
						$wpss_perm_log_file = substr(sprintf('%o', fileperms($wpss_log_file)), -4);
						$wpss_perm_log_empty_file = substr(sprintf('%o', fileperms($wpss_log_empty_file)), -4);
						$wpss_perm_htaccess_file = substr(sprintf('%o', fileperms($wpss_htaccess_file)), -4);
						$wpss_perm_htaccess_empty_file = substr(sprintf('%o', fileperms($wpss_htaccess_empty_file)), -4);
						if ( $wpss_perm_log_dir < '0775' || !is_writable(WPSS_PLUGIN_DATA_PATH) || $wpss_perm_log_file < '0666' || !is_writable($wpss_log_file) || $wpss_perm_log_empty_file < '0666' || !is_writable($wpss_log_empty_file) || $wpss_perm_htaccess_file < '0666' || !is_writable($wpss_htaccess_file) || $wpss_perm_htaccess_empty_file < '0666' || !is_writable($wpss_htaccess_empty_file) ) {
							@chmod( WPSS_PLUGIN_DATA_PATH, 0775 );
							@chmod( $wpss_log_file, 0666 );
							@chmod( $wpss_log_empty_file, 0666 );
							@chmod( $wpss_htaccess_file, 0666 );
							@chmod( $wpss_htaccess_empty_file, 0666 );
							}
						clearstatcache();
						$wpss_perm_log_dir = substr(sprintf('%o', fileperms(WPSS_PLUGIN_DATA_PATH)), -4);
						$wpss_perm_log_file = substr(sprintf('%o', fileperms($wpss_log_file)), -4);
						$wpss_perm_log_empty_file = substr(sprintf('%o', fileperms($wpss_log_empty_file)), -4);
						$wpss_perm_htaccess_file = substr(sprintf('%o', fileperms($wpss_htaccess_file)), -4);
						$wpss_perm_htaccess_empty_file = substr(sprintf('%o', fileperms($wpss_htaccess_empty_file)), -4);
						if ( $wpss_perm_log_dir < '0755' || !is_writable(WPSS_PLUGIN_DATA_PATH) || $wpss_perm_log_file < '0644' || !is_writable($wpss_log_file) || $wpss_perm_log_empty_file < '0644' || !is_writable($wpss_log_empty_file) || ( file_exists( $wpss_htaccess_file ) && ( $wpss_perm_htaccess_file < '0644' || !is_writable($wpss_htaccess_file) ) ) || $wpss_perm_htaccess_empty_file < '0644' || !is_writable($wpss_htaccess_empty_file) ) {
							echo '<br/>'."\n".'<span style="color:red;"><strong>The log file may not be writeable. You may need to manually correct the file permissions.<br/>Set the  permission for the "/wp-spamshield/data" directory to 755 and all files within to 644.</strong><br/>If that doesn\'t work then you may want to read the <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_faqs_5" target="_blank">FAQ</a> for this topic.</span><br/>'."\n";
							}
						}
					?>
					<br /><strong>Download <a href="<?php echo WPSS_PLUGIN_DATA_URL; ?>/temp-comments-log.txt" target="_blank">Comment Log File</a> - Right-click, and select "Save Link As"</strong><br />&nbsp;
					</li>
					<li>
					<label for="comment_logging_all">
						<input type="checkbox" id="comment_logging_all" name="comment_logging_all" <?php echo ($spamshield_options['comment_logging_all']==true?"checked=\"checked\"":"") ?> />
						<strong>Log All Comments</strong><br />Requires that Blocked Comment Logging Mode be engaged. Instead of only logging blocked comments, this will allow the log to capture <em>all</em> comments while logging mode is turned on. This provides more technical data for comment submissions than WordPress provides, and helps us improve the plugin.<br/>If you plan on submitting spam samples to us for analysis, it's helpful for you to turn this on, otherwise it's not necessary.</label>
					<br/>For more about this, see <a href="#wpss_configuration_log_all_comments">below</a>.<br />&nbsp;
					
					</li>
					<li>
					<label for="enhanced_comment_blacklist">
						<input type="checkbox" id="enhanced_comment_blacklist" name="enhanced_comment_blacklist" <?php echo ($spamshield_options['enhanced_comment_blacklist']==true?"checked=\"checked\"":"") ?> />
						<strong>Enhanced Comment Blacklist</strong><br />Enhances WordPress's Comment Blacklist - instead of just sending comments to moderation, they will be completely blocked. Also adds a link in the comment notification emails that will let you blacklist a commenter's IP with one click.<br/>(Useful if you receive repetitive human spam or harassing comments from a particular commenter.)<br/>&nbsp;</label>					
					</li>
					<label for="wordpress_comment_blacklist">
						<?php 
						$WordPressCommentBlacklist = trim(get_option('blacklist_keys'));
						?>
						<strong>Your current WordPress Comment Blacklist</strong><br/>When a comment contains any of these words in its content, name, URL, e-mail, or IP, it will be completely blocked, not just marked as spam. One word or IP per line. It is not case-sensitive and will match included words, so "press" on your blacklist will block "WordPress" in a comment.<br />
						<textarea id="wordpress_comment_blacklist" name="wordpress_comment_blacklist" cols="80" rows="8" /><?php echo $WordPressCommentBlacklist; ?></textarea><br/>
					</label>
					You can either update this list here or on the <a href="<?php echo WPSS_ADMIN_URL; ?>/options-discussion.php">WordPress Discussion Settings page</a>.<br/>&nbsp;
					<li>
					<label for="block_all_trackbacks">
						<input type="checkbox" id="block_all_trackbacks" name="block_all_trackbacks" <?php echo ($spamshield_options['block_all_trackbacks']==true?"checked=\"checked\"":"") ?> />
						<strong>Disable trackbacks.</strong><br />Use if trackback spam is excessive. (Not recommended)<br />&nbsp;
					</label>
					</li>
					<li>
					<label for="block_all_pingbacks">
						<input type="checkbox" id="block_all_pingbacks" name="block_all_pingbacks" <?php echo ($spamshield_options['block_all_pingbacks']==true?"checked=\"checked\"":"") ?> />
						<strong>Disable pingbacks.</strong><br />Use if pingback spam is excessive. Disadvantage is reduction of communication between blogs. (Not recommended)<br />&nbsp;
					</label>
					</li>
					<li>
					<label for="allow_proxy_users">
						<input type="checkbox" id="allow_proxy_users" name="allow_proxy_users" <?php echo ($spamshield_options['allow_proxy_users']==true?"checked=\"checked\"":"") ?> />
						<strong>Allow users behind proxy servers to comment?</strong><br />Many human spammers hide behind proxies, so you can uncheck this option for extra protection. (For highest user compatibility, leave it checked.)<br/>&nbsp;</label>
					</li>
					<li>
					<label for="hide_extra_data">
						<input type="checkbox" id="hide_extra_data" name="hide_extra_data" <?php echo ($spamshield_options['hide_extra_data']==true?"checked=\"checked\"":"") ?> />
						<strong>Hide extra technical data in comment notifications.</strong><br />This data is helpful if you need to submit a spam sample. If you dislike seeing the extra info, you can use this option.<br/>&nbsp;</label>					
					</li>
					<li>
					<label for="promote_plugin_link">
						<input type="checkbox" id="promote_plugin_link" name="promote_plugin_link" <?php echo ($spamshield_options['promote_plugin_link']==true?"checked=\"checked\"":"") ?> />
						<strong>Help promote WP-SpamShield?</strong><br />This places a small link under the comments and contact form, letting others know what's blocking spam on your blog.<br />&nbsp;
					</label>
					</li>
				</ul>
			</fieldset>
			<p class="submit">
			<input type="submit" name="submit_wpss_general_options" value="Update Options &raquo;" class="button-primary" style="float:left;" />
			</p>
			</form>

			<p>&nbsp;</p>

			<p>&nbsp;</p>
			
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top">BACK TO TOP</a> ]</div></p>

			<p>&nbsp;</p>
			
			<p><a name="wpss_contact_form_options"><strong>Contact Form Options</strong></a></p>

			<form name="wpss_contact_options" method="post">
			<input type="hidden" name="submitted_wpss_contact_options" value="1" />
            <?php
			wp_nonce_field('wpss_contact_options_nonce') ?>

			<fieldset class="options">
				<ul style="list-style-type:none;padding-left:30px;">
					<li>
					<label for="form_include_website">
						<input type="checkbox" id="form_include_website" name="form_include_website" <?php echo ($spamshield_options['form_include_website']==true?"checked=\"checked\"":"") ?> />
						<strong>Include "Website" field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_website">
						<input type="checkbox" id="form_require_website" name="form_require_website" <?php echo ($spamshield_options['form_require_website']==true?"checked=\"checked\"":"") ?> />
						<strong>Require "Website" field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_phone">
						<input type="checkbox" id="form_include_phone" name="form_include_phone" <?php echo ($spamshield_options['form_include_phone']==true?"checked=\"checked\"":"") ?> />
						<strong>Include "Phone" field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_phone">
						<input type="checkbox" id="form_require_phone" name="form_require_phone" <?php echo ($spamshield_options['form_require_phone']==true?"checked=\"checked\"":"") ?> />
						<strong>Require "Phone" field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_company">
						<input type="checkbox" id="form_include_company" name="form_include_company" <?php echo ($spamshield_options['form_include_company']==true?"checked=\"checked\"":"") ?> />
						<strong>Include "Company" field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_company">
						<input type="checkbox" id="form_require_company" name="form_require_company" <?php echo ($spamshield_options['form_require_company']==true?"checked=\"checked\"":"") ?> />
						<strong>Require "Company" field.</strong><br />&nbsp;
					</label>
					</li>					<li>
					<label for="form_include_drop_down_menu">
						<input type="checkbox" id="form_include_drop_down_menu" name="form_include_drop_down_menu" <?php echo ($spamshield_options['form_include_drop_down_menu']==true?"checked=\"checked\"":"") ?> />
						<strong>Include drop-down menu select field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_drop_down_menu">
						<input type="checkbox" id="form_require_drop_down_menu" name="form_require_drop_down_menu" <?php echo ($spamshield_options['form_require_drop_down_menu']==true?"checked=\"checked\"":"") ?> />
						<strong>Require drop-down menu select field.</strong><br />&nbsp;
					</label>
					</li>					
					<li>
					<label for="form_drop_down_menu_title">
						<?php $FormDropDownMenuTitle = trim(stripslashes($spamshield_options['form_drop_down_menu_title'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_title" name="form_drop_down_menu_title" value="<?php if ( $FormDropDownMenuTitle ) { echo $FormDropDownMenuTitle; } else { echo '';} ?>" />
						<strong>Title of drop-down select menu. (Menu won't be shown if empty.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_1">
						<?php $FormDropDownMenuItem1 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_1'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_1" name="form_drop_down_menu_item_1" value="<?php if ( $FormDropDownMenuItem1 ) { echo $FormDropDownMenuItem1; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 1. (Menu won't be shown if empty.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_2">
						<?php $FormDropDownMenuItem2 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_2'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_2" name="form_drop_down_menu_item_2" value="<?php if ( $FormDropDownMenuItem2 ) { echo $FormDropDownMenuItem2; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 2. (Menu won't be shown if empty.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_3">
						<?php $FormDropDownMenuItem3 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_3'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_3" name="form_drop_down_menu_item_3" value="<?php if ( $FormDropDownMenuItem3 ) { echo $FormDropDownMenuItem3; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 3. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_4">
						<?php $FormDropDownMenuItem4 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_4'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_4" name="form_drop_down_menu_item_4" value="<?php if ( $FormDropDownMenuItem4 ) { echo $FormDropDownMenuItem4; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 4. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_5">
						<?php $FormDropDownMenuItem5 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_5'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_5" name="form_drop_down_menu_item_5" value="<?php if ( $FormDropDownMenuItem5 ) { echo $FormDropDownMenuItem5; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 5. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_6">
						<?php $FormDropDownMenuItem6 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_6'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_6" name="form_drop_down_menu_item_6" value="<?php if ( $FormDropDownMenuItem6 ) { echo $FormDropDownMenuItem6; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 6. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_7">
						<?php $FormDropDownMenuItem7 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_7'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_7" name="form_drop_down_menu_item_7" value="<?php if ( $FormDropDownMenuItem7 ) { echo $FormDropDownMenuItem7; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 7. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_8">
						<?php $FormDropDownMenuItem8 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_8'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_8" name="form_drop_down_menu_item_8" value="<?php if ( $FormDropDownMenuItem8 ) { echo $FormDropDownMenuItem8; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 8. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_9">
						<?php $FormDropDownMenuItem9 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_9'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_9" name="form_drop_down_menu_item_9" value="<?php if ( $FormDropDownMenuItem9 ) { echo $FormDropDownMenuItem9; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 9. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_10">
						<?php $FormDropDownMenuItem10 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_10'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_10" name="form_drop_down_menu_item_10" value="<?php if ( $FormDropDownMenuItem10 ) { echo $FormDropDownMenuItem10; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 10. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_width">
						<?php $FormMessageWidth = trim(stripslashes($spamshield_options['form_message_width'])); ?>
						<input type="text" size="4" id="form_message_width" name="form_message_width" value="<?php if ( $FormMessageWidth && $FormMessageWidth >= 40 ) { echo $FormMessageWidth; } else { echo '40';} ?>" />
						<strong>"Message" field width. (Minimum 40)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_height">
						<?php $FormMessageHeight = trim(stripslashes($spamshield_options['form_message_height'])); ?>
						<input type="text" size="4" id="form_message_height" name="form_message_height" value="<?php if ( $FormMessageHeight && $FormMessageHeight >= 5 ) { echo $FormMessageHeight; } else if ( !$FormMessageHeight ) { echo '10'; } else { echo '5';} ?>" />
						<strong>"Message" field height. (Minimum 5, Default 10)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_min_length">
						<?php $FormMessageMinLength = trim(stripslashes($spamshield_options['form_message_min_length'])); ?>
						<input type="text" size="4" id="form_message_min_length" name="form_message_min_length" value="<?php if ( $FormMessageMinLength && $FormMessageMinLength >= 15 ) { echo $FormMessageMinLength; } else if ( !$FormMessageWidth ) { echo '25'; } else { echo '15';} ?>" />
						<strong>Minimum message length (# of characters). (Minimum 15, Default 25)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_recipient">
						<?php $FormMessageRecipient = trim(stripslashes($spamshield_options['form_message_recipient'])); ?>
						<input type="text" size="40" id="form_message_recipient" name="form_message_recipient" value="<?php if ( !$FormMessageRecipient ) { echo get_option('admin_email'); } else { echo $FormMessageRecipient; } ?>" />
						<strong>Optional: Enter alternate form recipient. Default is blog admin email.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_response_thank_you_message">
						<?php 
						$FormResponseThankYouMessage = trim(stripslashes($spamshield_options['form_response_thank_you_message']));
						?>
						<strong>Enter message to be displayed upon successful contact form submission.</strong><br/>Can be plain text, HTML, or an ad, etc.<br />
						<textarea id="form_response_thank_you_message" name="form_response_thank_you_message" cols="80" rows="3" /><?php if ( !$FormResponseThankYouMessage ) { echo 'Your message was sent successfully. Thank you.'; } else { echo $FormResponseThankYouMessage; } ?></textarea><br/>&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_user_meta">
						<input type="checkbox" id="form_include_user_meta" name="form_include_user_meta" <?php echo ($spamshield_options['form_include_user_meta']==true?"checked=\"checked\"":"") ?> />
						<strong>Include user technical data in email.</strong><br />This adds some extra technical data to the end of the contact form email about the person submitting the form.<br />It includes: <strong>Browser / User Agent</strong>, <strong>Referrer</strong>, <strong>IP Address</strong>, <strong>Server</strong>, etc.<br />This is helpful for dealing with abusive or threatening comments. You can use the IP address provided to identify or block trolls from your site with whatever method you prefer.<br />&nbsp;
					</label>
					</li>					

				</ul>
			</fieldset>
			<p class="submit">
			<input type="submit" name="submit_wpss_contact_options" value="Update Options &raquo;" class="button-primary" style="float:left;" />
			</p>
			</form>
			
			<p>&nbsp;</p>
			
			<p>&nbsp;</p>
			
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top">BACK TO TOP</a> ]</div></p>

			<p>&nbsp;</p>
			
			<p><a name="wpss_installation_instructions"><strong>Installation Instructions</strong></a></p>

			<ol style="list-style-type:decimal;padding-left:30px;">
			    <li>After downloading, unzip file and upload the enclosed 'wp-spamshield' directory to your WordPress plugins directory: '/wp-content/plugins/'.<br />&nbsp;</li>
				<li>As always, <strong>activate</strong> the plugin on your WordPress plugins page.<br />&nbsp;</li>
				<li>Check to make sure the plugin is installed properly. Many support requests for this plugin originate from improper installation and can be easily prevented. To check proper installation status, go to the WP-SpamShield page in your Admin. It's a submenu link on the Plugins page. Go the the 'Installation Status' area near the top and it will tell you if the plugin is installed correctly. If it tells you that the plugin is not installed correctly, please double-check what directory you have installed WP-SpamShield in, delete any WP-SpamShield files you have uploaded to your server, re-read the Installation Instructions, and start the Installation process over from step 1. If it is installed correctly, then move on to the next step.<br />&nbsp;<br /><strong>Currently your plugin is: <?php echo "<span style='color:".$wp_installation_status_color.";'>".$wp_installation_status_msg_main."</span>"; ?></strong><br />&nbsp;</li>
				<li>Select desired configuration options. Due to popular request, I've added the option to block trackbacks and pingbacks if the user feels they are excessive. I'd recommend not doing this, but the choice is yours.<br />&nbsp;</li>
				<li>If you are using front-end anti-spam plugins (CAPTCHA's, challenge questions, etc), be sure they are disabled since there's no longer a need for them, and these could likely conflict. Also if you were previously using WP-SpamFree, be sure to disable this as well. (Back-end anti-spam plugins like Akismet are fine, although unnecessary.)</li>
			</ol>	
			<p>&nbsp;</p>
			<p>You're done! Sit back and see what it feels like to blog without comment spam!</p>
					
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top">BACK TO TOP</a> ]</div></p>

			<p>&nbsp;</p>
			
			<p><a name="wpss_displaying_stats"><strong>Displaying Spam Stats on Your Blog</strong></a></p>

			<p>Want to show off your spam stats on your blog and tell others about WP-SpamShield? Simply add the following code to your WordPress theme where you'd like the stats displayed: <br />&nbsp;<br /><code>&lt;?php if ( function_exists(spamshield_counter) ) { spamshield_counter(1); } ?&gt;</code><br />&nbsp;<br /> where '1' is the style. Replace the '1' with a number from 1-9 that corresponds to one of the images below that matches the style you'd like to use. To simply display text stats on your site (no graphic), replace the '1' with '0'.<br />&nbsp;<br />To add it to any page or post, add the following shortcode to the page or post where you'd like the stats displayed (using the HTML editing tab, NOT the Visual editor): <br />&nbsp;<br /><code>[spamshieldcounter style=1]</code><br />&nbsp;<br /> where '1' is the style. Replace the '1' with a number from 1-9 that corresponds to one of the images below that matches the style you'd like to use. To simply display text stats on your site (no graphic), replace the '1' with '0'.</p>

<p>
<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-lg-bg-1-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 170px; height: 136px" width="170" height="136" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-lg-bg-2-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 170px; height: 136px" width="170" height="136" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-lg-bg-3-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 170px; height: 136px" width="170" height="136" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-lg-bg-4-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 170px; height: 136px" width="170" height="136" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-lg-bg-5-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 170px; height: 136px" width="170" height="136" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-lg-bg-6-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 170px; height: 136px" width="170" height="136" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-lg-bg-7-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 170px; height: 136px" width="170" height="136" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-lg-bg-8-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 170px; height: 136px" width="170" height="136" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-lg-bg-9-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 170px; height: 136px" width="170" height="136" />
</p>
						
			<p><strong>Small Counter</strong><br /><br />To add smaller counter to your site, add the following code to your WordPress theme where you'd like the stats displayed: <br />&nbsp;<br /><code>&lt;?php if ( function_exists(spamshield_counter_sm) ) { spamshield_counter_sm(1); } ?&gt;</code><br />&nbsp;<br /> where '1' is the style. Replace the '1' with a number from 1-5 that corresponds to one of the images below that matches the style you'd like to use.<br />&nbsp;<br />To add it to any page or post, add the following shortcode to the page or post where you'd like the stats displayed (using the HTML editing tab, NOT the Visual editor): <br />&nbsp;<br /><code>[spamshieldcountersm style=1]</code><br />&nbsp;<br /> where '1' is the style. Replace the '1' with a number from 1-5 that corresponds to one of the images below.</p>

<p>
<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-sm-bg-1-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 150px; height: 90px" width="150" height="90" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-sm-bg-2-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 150px; height: 90px" width="150" height="90" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-sm-bg-3-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 150px; height: 90px" width="150" height="90" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-sm-bg-4-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 150px; height: 90px" width="150" height="90" />

<img src='<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-sm-bg-5-preview.png' style="border-style:none; margin-right: 10px; margin-top: 7px; margin-bottom: 7px; width: 150px; height: 90px" width="150" height="90" />
</p>

<p>Or, you can simply <strong>use the widget</strong>. It displays stats in the style of small counter #1. <strong>Now you can show spam stats on your blog without knowing any code.</strong></p>

			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top">BACK TO TOP</a> ]</div></p>

			<p>&nbsp;</p>
			
			<p><a name="wpss_adding_contact_form"><strong>Adding a Contact Form to Your Blog</strong></a></p>

			<p>First create a page (not post) where you want to have your contact form. Then, insert the following shortcode (using the HTML editing tab, NOT the Visual editor) and you're done: <code>[spamshieldcontact]</code><br />&nbsp;<br />
			
			There is no need to configure the form. It allows you to simply drop it into the page you want to install it on. However, there are a few basic configuration options. You can choose whether or not to include Phone and Website fields, whether they should be required, add a drop down menu with up to 10 options, set the width and height of the Message box, set the minimum message length, set the form recipient, enter a custom message to be displayed upon successful contact form submission, and choose whether or not to include user technical data in the email.<br />&nbsp;<br />
			
			If you want to modify the style of the form using CSS, all the form elements have an ID attribute you can reference in your stylesheet.<br />&nbsp;<br />

			<strong>What the Contact Form feature IS:</strong> A simple drop-in contact form that won't get spammed.<br />
			<strong>What the Contact Form feature is NOT:</strong> A configurable and full-featured plugin like some other contact form plugins out there.<br />
			<strong>Note:</strong> Please do not request new features for the contact form, as the main focus of the plugin is spam protection. Thank you.</p>
			
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top">BACK TO TOP</a> ]</div></p>

			<p>&nbsp;</p>
			
			<p><a name="wpss_configuration"><strong>Configuration Information</strong></a></p>
			
			<p><a name="wpss_configuration_spam_options"><strong>Spam Options</strong></a>
			
			<p><a name="wpss_configuration_m2"><strong>M2 - Use two methods to set cookies.</strong></a><br />This adds a secondary non-JavaScript method to set cookies in addition to the standard JS method.</p>

			<p><a name="wpss_configuration_blocked_comment_logging_mode"><strong>Blocked Comment Logging Mode</strong></a><br />This is a temporary diagnostic mode that logs blocked comment submissions for 7 days, then turns off automatically. If you want to see what spam has been blocked on your site, this is the option to use. Also, if you experience any technical issues, this will help with diagnosis, as you can email this log file to support if necessary. If you suspect you are having a technical issue, please turn this on right away and start logging data. Then submit a <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/support/" target="_blank">support request</a>, and we'll email you back asking to see the log file so we can help you fix whatever the issue may be. The log is cleared each time this feature is turned on, so make sure you download the file before turning it back on. Also the log is capped at 2MB for security. <em>This feature may use slightly higher server resources, so for best performance, only use when necessary. (Most websites won't notice any difference.)</em> </p>

			<p><a name="wpss_configuration_log_all_comments"><strong>Log All Comments</strong></a><br />Requires that Blocked Comment Logging Mode be engaged. Instead of only logging blocked comments, this will allow the log to capture <em>all</em> comments while logging mode is turned on. This provides more technical data for comment submissions than WordPress provides, and helps us improve the plugin. If you plan on submitting spam samples to us for analysis, it's helpful for you to turn this on, otherwise it's not necessary. If you have any spam comments that you feel WP-SpamShield should have blocked (usually human spam), then please submit a <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/support/" target="_blank">support request</a>. When we email you back we will ask you to forward the data to us by email.</p>
			
			<p>This extra data will be extremely valuable in helping us improve the spam protection capabilites of the plugin.</p>
			
			<p><a name="wpss_configuration_enhanced_comment_blacklist"><strong>Enhanced Comment Blacklist</strong></a><br />Enhances WordPress's Comment Blacklist - instead of just sending comments to moderation, they will be completely blocked if this is enabled. (Useful if you receive repetitive human spam or harassing comments from a particular commenter.) Also adds <strong>one-click blacklisting</strong> - a link will now appear in the comment notification emails that you can click to blacklist a commenter's IP. This link appears whether or not the feature is enabled. If you click the link and this feature is disabled, it will add the commenter's IP to the blacklist but blacklisting will operate according to WordPress's default functionality.</p>
			
			<p>The WP-SpamShield blacklist shares the WordPress Comment Blacklist data, but the difference is that now when a comment contains any of these words in its content, name, URL, e-mail, or IP, it will be completely blocked, not just marked as spam. One word or IP per line...add each new blacklist item on a new line. If you're not sure how to use it, start by just adding an IP address, or click on the link in one of the notification emails. It is not case-sensitive and will match included words, so "press" on your blacklist will block "WordPress" in a comment.</p>			

			<p><a name="wpss_configuration_disable_trackbacks"><strong>Disable trackbacks.</strong></a><br />Use if trackback spam is excessive. It is recomended that you don't use this option unless you are experiencing an extreme spam attack.</p>

			<p><a name="wpss_configuration_disable_pingbacks"><strong>Disable pingbacks.</strong></a><br />Use if pingback spam is excessive. The disadvantage is a reduction of communication between blogs. When blogs ping each other, it's like saying "Hi, I just wrote about you" and disabling these pingbacks eliminates that ability. It is recomended that you don't use this option unless you are experiencing an extreme spam attack.</p>

			<p><a name="wpss_configuration_allow_proxy_users"><strong>Allow users behind proxy servers to comment?</strong></a><br />Many human spammers hide behind proxies. Leaving this unckecked adds an extra layer of spam protection. In the rare event that a non-spam commenter gets blocked by this, they will be notified what the situation is, and instructed to contact you to ask you to modify this setting. (For highest user compatibility, you can leave it checked.)</p>
			
			<p><a name="wpss_configuration_hide_extra_data"><strong>Hide extra technical data in comment notifications.</strong></a><br />The plugin now addes some extra technical data to the comment moderation and notification emails, including the referrer that brought the user to the page where they commented, the referrer that brought them to the WordPress comments processing page (helps with fighting spam), User-Agent, Remote Host, Reverse DNS, Proxy Info, Browser Language, and more. This data is helpful if you ever need to <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/support/" target="_blank">submit a spam sample</a>. If you dislike seeing the extra info, you can use this option to prevent the info from being displayed in the emails. If you don't mind seeing it, please leave it this unchecked, because if you ever need to submit a spam sample, it helps us track spam patterns.</p>
			
			<p><a name="wpss_configuration_help_promote_plugin"><strong>Help promote WP-SpamShield?</strong></a><br />This places a small link under the comments and contact form, letting others know what's blocking spam on your blog. This plugin is provided for free, so this is much appreciated. It's a small way you can give back and let others know about WP-SpamShield.</p>
			
			<p><a name="wpss_configuration_contact_form_options"><strong>Contact Form Options</strong></a><br />
			These are self-explanatory.</p>
					
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top">BACK TO TOP</a> ]</div></p>

			<p>&nbsp;</p>	

			<p><a name="wpss_known_conflicts"><strong>Known Plugin Conflicts</strong></a></p>
			
			<p>For the most up-to-date info, view the <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_known_conflicts" target="_blank" >Known Plugin Conflicts</a> list.</p>
			
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top">BACK TO TOP</a> ]</div></p>

			<p>&nbsp;</p>	

			<p><a name="wpss_troubleshooting"><strong>Troubleshooting Guide / Support</strong></a></p>
			<p>If you're having trouble getting things to work after installing the plugin, here are a few things to check:</p>
			<ol style="list-style-type:decimal;padding-left:30px;">
				<li>Check the <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/#wpss_faqs" target="_blank">FAQ's</a>.<br />&nbsp;</li>
				<li>If you haven't yet, please upgrade to the latest version.<br />&nbsp;</li>
				<li>Check to make sure the plugin is installed properly. Many support requests for this plugin originate from improper installation and can be easily prevented. To check proper installation status, go to the WP-SpamShield page in your Admin. It's a submenu link on the Plugins page. Go the the 'Installation Status' area near the top and it will tell you if the plugin is installed correctly. If it tells you that the plugin is not installed correctly, please double-check what directory you have installed WP-SpamShield in, delete any WP-SpamShield files you have uploaded to your server, re-read the Installation Instructions, and start the Installation process over from step 1.<br />&nbsp;<br /><strong>Currently your plugin is: <?php echo "<span style='color:".$wp_installation_status_color.";'>".$wp_installation_status_msg_main."</span>"; ?></strong><br />&nbsp;</li>
				<li>Clear your browser's cache, clear your cookies, and restart your browser. Then reload the page.<br />&nbsp;</li>
				<li>If you are receiving the error message: "Sorry, there was an error. Please enable JavaScript and Cookies in your browser and try again." then you need to make sure <em>JavaScript</em> and <em>cookies</em> are enabled in your browser. (JavaScript is different from Java. Java is not required.) These are enabled by default in web browsers. The status display will let you know if these are turned on or off (as best the page can detect - occasionally the detection does not work.) If this message comes up consistently even after JavaScript and cookies are enabled, then there most likely is an installation problem, plugin conflict, or JavaScript conflict. Read on for possible solutions.<br />&nbsp;</li>
				<li>If you have multiple domains that resolve to the same server, or are parked on the same hosting account, make sure the domain set in the WordPress configuration options matches the domain where you are accessing the blog from. In other words, if you have people going to your blog using http://www.yourdomain.com/ and the WordPress configuration has: http://www.yourdomain2.com/ you will have a problem (not just with this plugin, but with a lot of things.)<br />&nbsp;</li>
				<li>Check your WordPress Version. If you are using a release earlier than 2.3, you may want to upgrade for a whole slew of reasons, including features and security.<br />&nbsp;</li>
				<li>Check the options you have selected to make sure they are not disabling a feature you want to use.<br />&nbsp;</li>
				<li>Make sure that you are not using other front-end anti-spam plugins (CAPTCHA's, challenge questions, etc) since there's no longer a need for them, and these could likely conflict. Also if you were previously using WP-SpamFree, be sure to disable this as well. (Back-end anti-spam plugins like Akismet are fine, although unnecessary.)<br />&nbsp;</li>
				<li>Visit http://www.yourblog.com/wp-content/plugins/wp-spamshield/js/jscripts.php (where <em>yourblog.com</em> is your blog url) and check two things. <br />&nbsp;<br /><strong>First, see if the file comes normally or if it comes up blank or with errors.</strong> That would indicate a problem. Submit a support request (see last troubleshooting step) and copy and past any error messages on the page into your message. <br />&nbsp;<br /><strong>Second, check for a 403 Forbidden error.</strong> That means there is a problem with your file permissions. If the files in the wp-spamshield folder don't have standard permissions (at least 644 or higher) they won't work. This usually only happens by manual modification, but strange things do happen. <strong>The <em>AskApache Password Protect Plugin</em> is known to cause this error.</strong> Users have reported that using its feature to protect the /wp-content/ directory creates an .htaccess file in that directory that creates improper permissions and conflicts with WP-SpamShield (and most likely other plugins as well). You'll need to disable this feature, or disable the <em>AskApache Password Protect Plugin</em> and delete any .htaccess files it has created in your /wp-content/ directory before using WP-SpamShield.<br />&nbsp;</li>
        <li>Check for conflicts with other JavaScripts installed on your site. This usually occurs with with JavaScripts unrelated to WordPress or plugins. However some themes contain JavaScripts that aren't compatible. (And some don't have the call to the <code>wp_head()</code> function which is also a problem. Read on to see how to test/fix this issue.) If in doubt, try switching themes. If that fixes it, then you know the theme was at fault. If you discover a conflicting theme, please let us know.<br />&nbsp;</li>
        <li>Check for conflicts with other WordPress plugins installed on your blog. Although errors don't occur often, this is one of the most common causes of the errors that do occur. I can't guarantee how well-written other plugins will be. First, see the <a href="#wpss_known_conflicts">Known Plugin Conflicts</a> list. If you've disabled any plugins on that list and still have a problem, then proceed. <br />&nbsp;<br />To start testing for conflicts, temporarily deactivate all other plugins except WP-SpamShield. Then check to see if WP-SpamShield works by itself. (For best results make sure you are logged out and clear your cookies. Alternatively you can use another browser for testing.) If WP-SpamShield allows you to post a comment with no errors, then you know there is a plugin conflict. The next step is to activate each plugin, one at a time, log out, and try to post a comment. Then log in, deactivate that plugin, and repeat with the next plugin. (If possible, use a second browser to make it easier. Then you don't have to keep logging in and out with the first browser.) Be sure to clear cookies between attempts (before loading the page you want to comment on). If you do identify a plugin that conflicts, please let me know so I can work on bridging the compatibility issues.<br />&nbsp;</li>
		<li>Make sure the theme you are using has the call to <code>wp_head()</code> (which most properly coded themes do) usually found in the <code>header.php</code> file. It will be located somewhere before the <code>&lt;/head&gt;</code> tag. If not, you can insert it before the <code>&lt;/head&gt;</code> tag and save the file. If you've never edited a theme before, proceed at your own risk: <br />&nbsp;
			<ol style="list-style-type:decimal;padding-left:30px;">
				<li>In the WordPress admin, go to <em>Themes (Appearance) - Theme Editor</em><br />&nbsp;</li>
				<li>Click on Header (or <code>header.php</code>)<br />&nbsp;</li>
				<li>Locate the line with <code>&lt;/head&gt;</code> and insert <code>&lt;?php wp_head(); ?&gt;</code> before it.<br />&nbsp;</li>
				<li>Click 'Save'<br/>&nbsp;</li>
			</ol>
		</li>
        <li>On the WP-SpamShield Options page in the WordPress Admin, under <a href="#wpss_general_options">General Options</a>, check the option "M2 - Use two methods to set cookies." and see if this helps.<br />&nbsp;</li>
		<li>If have checked all of these, and still can't quite get it working, please submit a support request at the <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/support/" target="_blank" rel="external" >WP-SpamShield Support Page</a>.</li>
	</ol>
			
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top">BACK TO TOP</a> ]</div></p>

			<p>&nbsp;</p>
			
  			<p><a name="wpss_let_others_know"><strong>Let Others Know About WP-SpamShield</strong></a></p>
	
			<p><strong>How does it feel to blog without being bombarded by automated comment spam?</strong> If you're happy with WP-SpamShield, there's a few things you can do to let others know:</p>
			
			<ul style="list-style-type:disc;padding-left:30px;">
				<li><a href="http://www.redsandmarketing.com/blog/wp-spamshield-wordpress-plugin-released/#comments" target="_blank" >Post a comment.</a></li>
				<li><a href="http://wordpress.org/extend/plugins/wp-spamshield/" target="_blank" >Give WP-SpamShield a good rating</a> on WordPress.org.</li>
				<li><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/end-blog-spam/" target="_blank" >Place a graphic link</a>  on your site letting others know how they can help end blog spam. ( &lt/BLOGSPAM&gt; )</li>
			</ul>

			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top">BACK TO TOP</a> ]</div></p>

			<p>&nbsp;</p>

			<p><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" target="_blank" rel="external" style="border-style:none;text-decoration:none;" ><img src="<?php echo WPSS_PLUGIN_IMG_URL; ?>/end-blog-spam-button-01-black.png" alt="End Blog Spam! WP-SpamShield Comment Spam Protection for WordPress" width="140" height="66" style="border-style:none;text-decoration:none;" /></a></p>

			<p><a name="wpss_download_plugin_documentation"><strong>Download Plugin / Documentation</strong></a><br />
			Latest Version: <a href="http://www.redsandmarketing.com/library/plugins/wp-spamshield.zip" target="_blank" rel="external" >Download Now</a><br />
			Plugin Homepage / Documentation: <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" target="_blank" rel="external" >WP-SpamShield</a><br />
			Leave Comments: <a href="http://www.redsandmarketing.com/blog/wp-spamshield-wordpress-plugin-released/" target="_blank" rel="external" >WP-SpamShield Release Announcement Blog Post</a><br />
			WordPress.org Page: <a href="http://wordpress.org/extend/plugins/wp-spamshield/" target="_blank" rel="external" >WP-SpamShield</a><br />
			Tech Support/Questions: <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/support/" target="_blank" rel="external" >WP-SpamShield Support Page</a><br />
			End Blog Spam: <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/end-blog-spam/" target="_blank" rel="external" >Let Others Know About WP-SpamShield!</a><br />
			Twitter: <a href="http://twitter.com/WPSpamShield" target="_blank" rel="external" >@WPSpamShield</a><br />

            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="margin-top:10px;">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="DFMTNHJEPFFUL">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
			</p>

			<p>&nbsp;</p>

			<p><em><?php echo 'Version '.WPSS_VERSION; ?></em></p>
	
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top">BACK TO TOP</a> ]</div></p>

			<p>&nbsp;</p>

			<p>&nbsp;</p>
			</div>
			<?php
			}

		function wp_head_intercept(){
			if (!is_admin()) {
				if (empty($session_wpss_bot_status) || empty($session_wpss_plugin_url) || empty($session_wpSpamShieldVer) || empty($session_spamshield_options) || empty($session_CookieValidationName) || empty($session_CookieValidationKey) ) {
					global $session_wpss_bot_status,$session_wpss_plugin_url,$session_wpSpamShieldVer,$session_spamshield_options,$session_CookieValidationName,$session_CookieValidationKey;
					}
					
				$spamshield_options = get_option('spamshield_options');
				$CookieValidationName = $spamshield_options['cookie_validation_name'];
				$CookieValidationKey = $spamshield_options['cookie_validation_key'];
				//Following was in JS, but since this is immediately before that code is executed, placed here - may be problem with caching though, but minor
				update_option( 'ak_count_pre', get_option('akismet_spam_count') );

				$wpSpamShieldVerJS=' v'.WPSS_VERSION;
				echo "\n";

				/*
				// DEACTIVATED SECTION FOR CACHE COMPATIBILITY
				//Benchmark Spider Check - START
				$wpss_spider_check_start_time = wpss_microtime();
				//Spider Check
				$wpss_check_if_spider_true = wpss_check_if_spider();
				//Benchmark Spider Check - END
				$wpss_spider_check_end_time = wpss_microtime();
				$wpss_spider_check_total_time = substr(($wpss_spider_check_end_time - $wpss_spider_check_start_time),0,8). " seconds";
				
				
				if ( $_SESSION['spider_status_check'] || $wpss_check_if_spider_true ) {
					$wpss_bot_status = true;
					}
				else {
					$wpss_bot_status = false;
					}
				*/
				
				// FOLLOWING LINE ADDED FOR CACHE COMPATIBILITY
				$wpss_bot_status = false;

				if ( comments_open() || is_page() ) {
					if ( !$wpss_bot_status ) {
						if ( $spamshield_options['use_alt_cookie_method_only'] ) {
							echo '<!-- M2 -->'."\n";
							}
						else {
							echo '<script type="text/javascript" async defer src="'.WPSS_PLUGIN_JS_URL.'/jscripts.php"></script> '."\n";
							}
						}
					}

				//Set Session Variables
				$session_wpss_plugin_url 			= WPSS_PLUGIN_URL;
				$session_wpSpamShieldVer 			= WPSS_VERSION;
				$session_spamshield_options 		= $spamshield_options;
				$session_CookieValidationName 		= $CookieValidationName;
				$session_CookieValidationKey 		= $CookieValidationKey;
				$_SESSION['wpss_bot_status'] 		= $wpss_bot_status;
				$_SESSION['wpss_plugin_url'] 		= WPSS_PLUGIN_URL;
				$_SESSION['wpSpamShieldVer'] 		= WPSS_VERSION;
				$_SESSION['spamshield_options'] 	= $spamshield_options;
				$_SESSION['CookieValidationName'] 	= $CookieValidationName;
				$_SESSION['CookieValidationKey'] 	= $CookieValidationKey;
				}
			}

		function install_on_activation() {
			global $wpdb;
			$installed_ver = get_option('wp_spamshield_version');
			$spamshield_options = get_option('spamshield_options');
			
			//only run installation if not installed or if previous version installed
			if ( ( $installed_ver === false || $installed_ver != $plugin_db_version ) && !$spamshield_options ) {
				
				// Import WP-SpamFree Options, only on first activation
				$wpsf_installed_ver = get_option('wp_spamfree_version');
				if ( $wpsf_installed_ver && $installed_ver === false ) {
					$spamfree_options = get_option('spamfree_options');
					}
				
				//add a database version number for future upgrade purposes
				update_option('wp_spamshield_version', WPSS_VERSION);

				// Set Random Cookie Name
				$randomComValCodeCVN1 = spamshield_create_random_key();
				$randomComValCodeCVN2 = spamshield_create_random_key();
				$CookieValidationName = strtoupper($randomComValCodeCVN1.$randomComValCodeCVN2);
				// Set Random Cookie Value
				$randomComValCodeCKV1 = spamshield_create_random_key();
				$randomComValCodeCKV2 = spamshield_create_random_key();
				$CookieValidationKey = $randomComValCodeCKV1.$randomComValCodeCKV2;
				// Set Random Form Field Name
				$randomComValCodeJSFFN1 = spamshield_create_random_key();
				$randomComValCodeJSFFN2 = spamshield_create_random_key();
				$FormValidationFieldJS = $randomComValCodeJSFFN1.$randomComValCodeJSFFN2;
				// Set Random Form Field Value
				$randomComValCodeJS1 = spamshield_create_random_key();
				$randomComValCodeJS2 = spamshield_create_random_key();
				$FormValidationKeyJS = $randomComValCodeJS1.$randomComValCodeJS2;
				// TIME
				$KeyUpdateTime = time();
				// DATE
				$InstallDate = date('Y-m-d');

				// Options array
				if ( $spamfree_options ) {
					$spamshield_options_update = $spamfree_options;
					}
				else {
					$spamshield_default = unserialize(WPSS_DEFAULT_VALUES);
					$spamshield_options_update = array (
						'cookie_validation_name' 				=> $CookieValidationName,
						'cookie_validation_key' 				=> $CookieValidationKey,
						'form_validation_field_js' 				=> $FormValidationFieldJS,
						'form_validation_key_js' 				=> $FormValidationKeyJS,
						'cookie_get_function_name' 				=> $spamshield_default['cookie_get_function_name'],
						'cookie_set_function_name' 				=> $spamshield_default['cookie_set_function_name'],
						'cookie_delete_function_name' 			=> $spamshield_default['cookie_delete_function_name'],
						'comment_validation_function_name' 		=> $spamshield_default['comment_validation_function_name'],
						'last_key_update'						=> $KeyUpdateTime,
						'wp_cache' 								=> $spamshield_default['wp_cache'],
						'wp_super_cache' 						=> $spamshield_default['wp_super_cache'],
						'block_all_trackbacks' 					=> $spamshield_default['block_all_trackbacks'],
						'block_all_pingbacks' 					=> $spamshield_default['block_all_pingbacks'],
						'use_alt_cookie_method'					=> $spamshield_default['use_alt_cookie_method'],
						'use_alt_cookie_method_only'			=> $spamshield_default['use_alt_cookie_method_only'],
						'use_captcha_backup' 					=> $spamshield_default['use_captcha_backup'],
						'use_trackback_verification'		 	=> $spamshield_default['use_trackback_verification'],
						'comment_logging'						=> $spamshield_default['comment_logging'],
						'comment_logging_start_date'			=> $spamshield_default['comment_logging_start_date'],
						'comment_logging_all'					=> $spamshield_default['comment_logging_all'],
						'enhanced_comment_blacklist'			=> $spamshield_default['enhanced_comment_blacklist'],
						'allow_proxy_users'						=> $spamshield_default['allow_proxy_users'],
						'hide_extra_data'						=> $spamshield_default['hide_extra_data'],
						'form_include_website' 					=> $spamshield_default['form_include_website'],
						'form_require_website' 					=> $spamshield_default['form_require_website'],
						'form_include_phone' 					=> $spamshield_default['form_include_phone'],
						'form_require_phone' 					=> $spamshield_default['form_require_phone'],
						'form_include_company' 					=> $spamshield_default['form_include_company'],
						'form_require_company' 					=> $spamshield_default['form_require_company'],
						'form_include_drop_down_menu'			=> $spamshield_default['form_include_drop_down_menu'],
						'form_require_drop_down_menu'			=> $spamshield_default['form_require_drop_down_menu'],
						'form_drop_down_menu_title'				=> $spamshield_default['form_drop_down_menu_title'],
						'form_drop_down_menu_item_1'			=> $spamshield_default['form_drop_down_menu_item_1'],
						'form_drop_down_menu_item_2'			=> $spamshield_default['form_drop_down_menu_item_2'],
						'form_drop_down_menu_item_3'			=> $spamshield_default['form_drop_down_menu_item_3'],
						'form_drop_down_menu_item_4'			=> $spamshield_default['form_drop_down_menu_item_4'],
						'form_drop_down_menu_item_5'			=> $spamshield_default['form_drop_down_menu_item_5'],
						'form_drop_down_menu_item_6'			=> $spamshield_default['form_drop_down_menu_item_6'],
						'form_drop_down_menu_item_7'			=> $spamshield_default['form_drop_down_menu_item_7'],
						'form_drop_down_menu_item_8'			=> $spamshield_default['form_drop_down_menu_item_8'],
						'form_drop_down_menu_item_9'			=> $spamshield_default['form_drop_down_menu_item_9'],
						'form_drop_down_menu_item_10'			=> $spamshield_default['form_drop_down_menu_item_10'],
						'form_message_width' 					=> $spamshield_default['form_message_width'],
						'form_message_height' 					=> $spamshield_default['form_message_height'],
						'form_message_min_length'				=> $spamshield_default['form_message_min_length'],
						'form_message_recipient'				=> get_option('admin_email'),
						'form_response_thank_you_message'		=> $spamshield_default['form_response_thank_you_message'],
						'form_include_user_meta'				=> $spamshield_default['form_include_user_meta'],
						'promote_plugin_link'					=> $spamshield_default['promote_plugin_link'],
						'install_date'							=> $InstallDate,
						);
					}
					
				$spamshield_count = spamshield_count();
				if (!$spamshield_count) {
					update_option('spamshield_count', 0);
					}
				update_option('spamshield_options', $spamshield_options_update);
				update_option('ak_count_pre', get_option('akismet_spam_count'));
				// Turn on Comment Moderation
				//update_option('comment_moderation', 1);
				//update_option('moderation_notify', 1);
				
				// Ensure Correct Permissions of IMG and JS file - BEGIN
				
				$installation_file_test_2 = WPSS_PLUGIN_IMG_PATH.'/img.php';
				$installation_file_test_3 = WPSS_PLUGIN_JS_PATH.'/jscripts.php';
				
				clearstatcache();
				$installation_file_test_2_perm = substr(sprintf('%o', fileperms($installation_file_test_2)), -4);
				$installation_file_test_3_perm = substr(sprintf('%o', fileperms($installation_file_test_3)), -4);
				if ( $installation_file_test_2_perm < '0755' || $installation_file_test_3_perm < '0755' || !is_readable($installation_file_test_2) || !is_executable($installation_file_test_2) || !is_readable($installation_file_test_3) || !is_executable($installation_file_test_3) ) {
					@chmod( $installation_file_test_2, 0755 );
					@chmod( $installation_file_test_3, 0755 );
					}
					
				// Ensure Correct Permissions of IMG and JS file - END

				}
			}
					
		}
	}

function wpss_microtime() {
	// FOR BENCHMARK & DEBUG ONLY
	$mtime = microtime();
	$mtime = explode(' ',$mtime); 
   	$mtime = $mtime[1]+$mtime[0];
	return $mtime;
	}

//instantiate the class
if (class_exists('wpSpamShield')) {
	$wpSpamShield = new wpSpamShield();
	}

?>