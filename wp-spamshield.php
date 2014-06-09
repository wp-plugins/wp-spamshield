<?php
/*
Plugin Name: WP-SpamShield
Plugin URI: http://www.redsandmarketing.com/plugins/wp-spamshield/
Description: An extremely robust and user-friendly anti-spam plugin that simply destroys comment spam. Enjoy a WordPress blog without spam! Includes a spam-blocking contact form feature too.
Author: Scott Allen
Version: 1.1.7.3a1
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

define( 'WPSS_VERSION', '1.1.7.3a1' );
define( 'WPSS_REQUIRED_WP_VERSION', '3.0' );
define( 'WPSS_MAX_WP_VERSION', '4.0' );
/** Setting important URL and PATH constants so the plugin can find things
* WPSS_SITE_URL - THE Site base URL	- Ex: http://example.com
* WPSS_CONTENT_DIR_URL 				- Ex: http://example.com/wp-content
* WPSS_CONTENT_DIR_PATH 			- Ex: /public_html/wp-content
* WPSS_PLUGINS_DIR_URL 				- Ex: http://example.com/wp-content/plugins
* WPSS_PLUGINS_DIR_PATH 			- Ex: /public_html/wp-content/plugins
* WPSS_ADMIN_URL					- Ex: http://example.com/wp-admin
* WPSS_PLUGIN_BASENAME				- Ex: wp-spamshield/wp-spamshield.php
* WPSS_PLUGIN_FILE_BASENAME			- Ex: wp-spamshield.php
* WPSS_PLUGIN_NAME					- Ex: wp-spamshield
* WPSS_PLUGIN_URL					- Ex: http://example.com/wp-content/plugins/wp-spamshield
* WPSS_PLUGIN_FILE_URL				- Ex: http://example.com/wp-content/plugins/wp-spamshield/wp-spamshield.php
* WPSS_PLUGIN_COUNTER_URL			- Ex: http://example.com/wp-content/plugins/wp-spamshield/counter
* WPSS_PLUGIN_DATA_URL				- Ex: http://example.com/wp-content/plugins/wp-spamshield/data
* WPSS_PLUGIN_IMG_URL				- Ex: http://example.com/wp-content/plugins/wp-spamshield/img
* WPSS_PLUGIN_JS_URL				- Ex: http://example.com/wp-content/plugins/wp-spamshield/js
* WPSS_PLUGIN_PATH					- Ex: /public_html/wp-content/plugins/wp-spamshield
* WPSS_PLUGIN_FILE_PATH				- Ex: /public_html/wp-content/plugins/wp-spamshield/wp-spamshield.php
* WPSS_PLUGIN_COUNTER_PATH			- Ex: /public_html/wp-content/plugins/wp-spamshield/counter
* WPSS_PLUGIN_DATA_PATH				- Ex: /public_html/wp-content/plugins/wp-spamshield/data
* WPSS_PLUGIN_IMG_PATH				- Ex: /public_html/wp-content/plugins/wp-spamshield/img
* WPSS_PLUGIN_INCL_PATH				- Ex: /public_html/wp-content/plugins/wp-spamshield/includes
* WPSS_PLUGIN_JS_PATH				- Ex: /public_html/wp-content/plugins/wp-spamshield/js
**/
if ( !defined( 'WPSS_SITE_URL' ) ) 				{ define( 'WPSS_SITE_URL', untrailingslashit( site_url() ) ); }
if ( !defined( 'WPSS_CONTENT_DIR_URL' ) ) 		{ define( 'WPSS_CONTENT_DIR_URL', WP_CONTENT_URL ); }
if ( !defined( 'WPSS_CONTENT_DIR_PATH' ) ) 		{ define( 'WPSS_CONTENT_DIR_PATH', WP_CONTENT_DIR ); }
if ( !defined( 'WPSS_PLUGINS_DIR_URL' ) ) 		{ define( 'WPSS_PLUGINS_DIR_URL', WP_PLUGIN_URL ); }
if ( !defined( 'WPSS_PLUGINS_DIR_PATH' ) ) 		{ define( 'WPSS_PLUGINS_DIR_PATH', WP_PLUGIN_DIR ); }
if ( !defined( 'WPSS_ADMIN_URL' ) ) 			{ define( 'WPSS_ADMIN_URL', untrailingslashit( admin_url() ) ); }
if ( !defined( 'WPSS_PLUGIN_BASENAME' ) ) 		{ define( 'WPSS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );	}
if ( !defined( 'WPSS_PLUGIN_FILE_BASENAME' ) ) 	{ define( 'WPSS_PLUGIN_FILE_BASENAME', trim( basename( __FILE__ ), '/' ) ); }
if ( !defined( 'WPSS_PLUGIN_NAME' ) ) 			{ define( 'WPSS_PLUGIN_NAME', trim( dirname( WPSS_PLUGIN_BASENAME ), '/' ) ); }
if ( !defined( 'WPSS_PLUGIN_URL' ) ) 			{ define( 'WPSS_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) ); }
if ( !defined( 'WPSS_PLUGIN_FILE_URL' ) ) 		{ define( 'WPSS_PLUGIN_FILE_URL',  WPSS_PLUGIN_URL.'/'.WPSS_PLUGIN_FILE_BASENAME ); }
if ( !defined( 'WPSS_PLUGIN_COUNTER_URL' ) ) 	{ define( 'WPSS_PLUGIN_COUNTER_URL', WPSS_PLUGIN_URL . '/counter' ); }
if ( !defined( 'WPSS_PLUGIN_DATA_URL' ) ) 		{ define( 'WPSS_PLUGIN_DATA_URL', WPSS_PLUGIN_URL . '/data' ); }
if ( !defined( 'WPSS_PLUGIN_IMG_URL' ) ) 		{ define( 'WPSS_PLUGIN_IMG_URL', WPSS_PLUGIN_URL . '/img' ); }
if ( !defined( 'WPSS_PLUGIN_JS_URL' ) ) 		{ define( 'WPSS_PLUGIN_JS_URL', WPSS_PLUGIN_URL . '/js' );	}
if ( !defined( 'WPSS_PLUGIN_PATH' ) ) 			{ define( 'WPSS_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) ); }
if ( !defined( 'WPSS_PLUGIN_FILE_PATH' ) ) 		{ define( 'WPSS_PLUGIN_FILE_PATH', WPSS_PLUGIN_PATH.'/'.WPSS_PLUGIN_FILE_BASENAME ); }
if ( !defined( 'WPSS_PLUGIN_COUNTER_PATH' ) ) 	{ define( 'WPSS_PLUGIN_COUNTER_PATH', WPSS_PLUGIN_PATH . '/counter' ); }
if ( !defined( 'WPSS_PLUGIN_DATA_PATH' ) ) 		{ define( 'WPSS_PLUGIN_DATA_PATH', WPSS_PLUGIN_PATH . '/data' ); }
if ( !defined( 'WPSS_PLUGIN_IMG_PATH' ) ) 		{ define( 'WPSS_PLUGIN_IMG_PATH', WPSS_PLUGIN_PATH . '/img' ); }
if ( !defined( 'WPSS_PLUGIN_INCL_PATH' ) ) 		{ define( 'WPSS_PLUGIN_INCL_PATH', WPSS_PLUGIN_PATH . '/includes' ); }
if ( !defined( 'WPSS_PLUGIN_JS_PATH' ) ) 		{ define( 'WPSS_PLUGIN_JS_PATH', WPSS_PLUGIN_PATH . '/js' ); }
if ( !defined( 'WPSS_HASH_ALT' ) ) {
	$wpss_server_ip_nodot = preg_replace( "~\.~", "", $_SERVER['SERVER_ADDR'] );
	$wpss_alt_prefix = hash( 'md5', $wpss_server_ip_nodot );
	define( 'WPSS_HASH_ALT', $wpss_alt_prefix );
	}
if ( !defined( 'WPSS_HASH' ) ) {
	if ( defined( 'COOKIEHASH' ) ) { $wpss_hash_prefix = COOKIEHASH; } else { $wpss_hash_prefix = hash( 'md5', WPSS_SITE_URL ); }
	define( 'WPSS_HASH', $wpss_hash_prefix );
	}
if ( !defined( 'WPSS_SERVER_ADDR' ) ) 			{ define( 'WPSS_SERVER_ADDR', $_SERVER['SERVER_ADDR'] ); }
if ( !defined( 'WPSS_SERVER_NAME' ) ) 			{ define( 'WPSS_SERVER_NAME', strtolower( $_SERVER['SERVER_NAME'] ) ); }
if ( !defined( 'WPSS_SERVER_NAME_REV' ) ) 		{ define( 'WPSS_SERVER_NAME_REV', strrev( WPSS_SERVER_NAME ) ); }
if ( !defined( 'WPSS_DEBUG_SERVER_NAME' ) ) 	{ define( 'WPSS_DEBUG_SERVER_NAME', '.redsandmarketing.com' ); }
if ( !defined( 'WPSS_DEBUG_SERVER_NAME_REV' ) ) { define( 'WPSS_DEBUG_SERVER_NAME_REV', strrev( WPSS_DEBUG_SERVER_NAME ) ); }
if ( !defined( 'WPSS_SERVER_SOFTWARE' ) ) 		{ define( 'WPSS_SERVER_SOFTWARE', $_SERVER['SERVER_SOFTWARE'] ); }
if ( !defined( 'WPSS_PHP_UNAME' ) ) 			{ define( 'WPSS_PHP_UNAME', @php_uname() ); }
if ( !defined( 'WPSS_PHP_VERSION' ) ) 			{ define( 'WPSS_PHP_VERSION', phpversion() ); }
if ( !defined( 'WPSS_WIN_SERVER' ) ) {
	$wpss_php_uname = @php_uname('s');
	if ( preg_match( "~^(cyg|u)?win~i", $wpss_php_uname) ) { define( 'WPSS_WIN_SERVER', 'Win' ); } else { define( 'WPSS_WIN_SERVER', 'NotWin' ); }
	}
if ( !defined( 'WPSS_PHP_MEM_LIMIT' ) ) {
	$wpss_php_memory_limit = spamshield_format_bytes( ini_get( 'memory_limit' ) );
	define( 'WPSS_PHP_MEM_LIMIT', $wpss_php_memory_limit );
	}
if ( !defined( 'WPSS_WP_VERSION' ) ) {
	$wpss_wp_version = get_bloginfo( 'version' );
	define( 'WPSS_WP_VERSION', $wpss_wp_version );
	}
if ( !defined( 'WPSS_USER_AGENT' ) ) {
	$wpss_user_agent = 'WP-SpamShield/'.WPSS_VERSION.' (WordPress/'.WPSS_WP_VERSION.') PHP/'.WPSS_PHP_VERSION.' ('.WPSS_SERVER_SOFTWARE.')';
	define( 'WPSS_USER_AGENT', $wpss_user_agent );
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
if ( !defined( 'WPSS_POPULAR_CACHE_PLUGINS' ) ) {
	// popular cache plugins - convert from array to constant
	define( 'WPSS_POPULAR_CACHE_PLUGINS', serialize( $popular_cache_plugins_default ) );
	}
// SET THE DEFAULT CONSTANT VALUES HERE:
$spamshield_default = array (
	'cookie_get_function_name' 			=> '',
	'cookie_set_function_name' 			=> '',
	'cookie_delete_function_name' 		=> '',
	'comment_validation_function_name' 	=> '',
	'wp_cache' 							=> 0,
	'wp_super_cache' 					=> 0,
	'block_all_trackbacks' 				=> 0,
	'block_all_pingbacks' 				=> 0,
	'use_alt_cookie_method'				=> 0,
	'use_alt_cookie_method_only'		=> 0,
	'use_captcha_backup' 				=> 0,
	'use_trackback_verification'		=> 0,
	'comment_logging'					=> 0,
	'comment_logging_start_date'		=> 0,
	'comment_logging_all'				=> 0,
	'enhanced_comment_blacklist'		=> 0,
	'allow_proxy_users'					=> 1,
	'hide_extra_data'					=> 0,
	'form_include_website' 				=> 1,
	'form_require_website' 				=> 0,
	'form_include_phone' 				=> 1,
	'form_require_phone' 				=> 0,
	'form_include_company' 				=> 0,
	'form_require_company' 				=> 0,
	'form_include_drop_down_menu'		=> 0,
	'form_require_drop_down_menu'		=> 0,
	'form_drop_down_menu_title'			=> '',
	'form_drop_down_menu_item_1'		=> '',
	'form_drop_down_menu_item_2'		=> '',
	'form_drop_down_menu_item_3'		=> '',
	'form_drop_down_menu_item_4'		=> '',
	'form_drop_down_menu_item_5'		=> '',
	'form_drop_down_menu_item_6'		=> '',
	'form_drop_down_menu_item_7'		=> '',
	'form_drop_down_menu_item_8'		=> '',
	'form_drop_down_menu_item_9'		=> '',
	'form_drop_down_menu_item_10'		=> '',
	'form_message_width' 				=> 40,
	'form_message_height' 				=> 10,
	'form_message_min_length'			=> 25,
	'form_response_thank_you_message'	=> 'Your message was sent successfully. Thank you.',
	'form_include_user_meta'			=> 1,
	'promote_plugin_link'				=> 0,
	);
if ( !defined( 'WPSS_DEFAULT_VALUES' ) ) { define( 'WPSS_DEFAULT_VALUES', serialize( $spamshield_default ) ); }
	
// Standard Functions - BEGIN

function spamshield_start_session() {
	$wpss_session_test = @session_id();
	if( empty($wpss_session_test) && !headers_sent() ) {
		@session_start();
		global $wpss_session_id;
		$wpss_session_id = @session_id();
		}
	}

function spamshield_end_session() {
	@session_destroy();
	}

function spamshield_count_words($string) {
	$string = trim($string);
	$char_count = spamshield_strlen($string);
	if ( empty( $string ) || $char_count == 0 ) { $num_words = 0; }
	else { 
		$exploded_string = preg_split( "~\s+~", $string );
		$num_words = count($exploded_string);		
		}
	return $num_words;
	}

function spamshield_strlen($string) {
	// Use this function instead of mb_strlen because some IIS servers have mb_strlen disabled by default
	// BUT mb_strlen is superior to strlen, so use it whenever possible
	if (function_exists( 'mb_strlen' ) ) { $num_chars = mb_strlen($string); } else { $num_chars = strlen($string); }
	return $num_chars;
	}

function spamshield_substr_count( $haystack, $needle, $offset = 0, $length = NULL ) {
	// Has error correction built in
	$haystack_len = spamshield_strlen($haystack);
	$needle_len = spamshield_strlen($needle);
	if ( $offset >= $haystack_len || $offset < 0 ) { $offset = 0; }
	if ( empty( $length ) || $length <= 0 ) { $length = $haystack_len; }
	$haystack_len_offset_diff = $haystack_len - $offset;
	if ( $length > $haystack_len_offset_diff ) { $length = $haystack_len_offset_diff; }
	$needle_instances = 0;
	if ( !empty( $needle ) && !empty( $haystack ) && $needle_len <= $haystack_len ) {
		$needle_instances = substr_count( $haystack, $needle, $offset, $length );
		}
	return $needle_instances;
	}

function spamshield_sort_unique($arr) {
	$arr_tmp = array_unique($arr); natcasesort($arr_tmp); $new_arr = array_values($arr_tmp);
	return $new_arr;
	}

function spamshield_preg_quote( $string ) {
	// Prep for use in Regex, this plugin uses '~' as a delimiter exclusively, can be changed
	$regex_string = preg_quote( $string, '~' );
	$regex_string = preg_replace( "~\s+~", "\s+", $regex_string );
	return $regex_string;
	}

function spamshield_microtime() {
	$mtime = microtime();
	$mtime = explode(' ',$mtime); 
   	$mtime = $mtime[1]+$mtime[0];
	return $mtime;
	}

function spamshield_timer( $start = NULL, $end = NULL, $show_seconds = false, $digits_accuracy = 8 ) {
	if ( empty( $start ) || empty( $end ) ) { $start = 0; $end = 0; }
	// $digits_accuracy will default to 8 but can be set to 4 or 6 for custom accuracy and debugging
	$total_time = substr(($end - $start),0,$digits_accuracy);
	if ( !empty( $show_seconds ) ) { $total_time .= ' seconds'; }
	return $total_time;
	}

function spamshield_format_bytes($size, $precision = 2) {
	if ( !is_numeric($size) ) { return $size; }
    $base = log($size) / log(1024);
    $suffixes = array('', 'k', 'M', 'G', 'T');
	$formatted_num = round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    return $formatted_num;
	}

function spamshield_date_diff($start, $end) {
	$start_ts = strtotime($start);
	$end_ts = strtotime($end);
	$diff = ( $end_ts - $start_ts );
	$start_array = explode('-', $start);
	$start_year = $start_array[0];
	$end_array = explode('-', $end);
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
	// Filter URLs with nothing after http
	if ( empty( $url ) || preg_match( "~^https?\:*/*$~i", $url ) ) { return ''; }
	// Fix poorly formed URLs so as not to throw errors when parsing
	$url = spamshield_fix_url($url);
	// NOW start parsing
	$parsed = parse_url($url);
	// Filter URLs with no domain
	if ( empty( $parsed['host'] ) ) { return ''; }
	$hostname = $parsed['host'];
	return $hostname;
	}

function spamshield_fix_url($url) {
	// Fix poorly formed URLs so as not to throw errors or cause problems
	// Too many forward slashes or colons after http
	$url = preg_replace( "~^(https?)\:+/+~i", "$1://", $url);
	// Too many dots
	$url = preg_replace( "~\.+~i", ".", $url);
	// Too many slashes after the domain
	$url = preg_replace( "~([a-z0-9]+)/+([a-z0-9]+)~i", "$1/$2", $url);
	return $url;
	}

function spamshield_get_url() {
	if ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) { $url = 'https://'; } else { $url = 'http://'; }
	$url .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	return $url;
	}

function spamshield_get_query_arr($url) {
	$parsed = parse_url($url);
	$query = $parsed['query'];
	if ( !empty( $query ) ) { $query_arr = explode( '&', $query ); } else { $query_arr = ''; }
	return $query_arr;
	}

function spamshield_remove_query( $url, $skip_wp_args = false ) {
	$query_arr = spamshield_get_query_arr($url);
	if ( empty( $query_arr ) ) { return $url; }
	$remove_args = array();
	foreach( $query_arr as $i => $query_arg ) {
		$query_arg_arr = explode( '=', $query_arg );
		$key = $query_arg_arr[0];
		if ( !empty( $skip_wp_args ) && ( $key == 'p' || $key == 'page_id' ) ) { continue; }
		$remove_args[] = $key;
		}
	$clean_url = remove_query_arg( $remove_args, $url );
	return $clean_url;
	}

function spamshield_is_valid_ip( $ip, $incl_priv_res = false ) {
	if ( function_exists( 'filter_var' ) ) {
		if ( empty( $incl_priv_res ) ) {
			if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) { $ip_valid = true; } else { $ip_valid = false; }
			}
		elseif ( filter_var( $ip, FILTER_VALIDATE_IP ) ) { $ip_valid = true; } else { $ip_valid = false; }
		// FILTER_FLAG_IPV4,FILTER_FLAG_IPV6,FILTER_FLAG_NO_PRIV_RANGE,FILTER_FLAG_NO_RES_RANGE
		}
	elseif ( preg_match( "~^([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])$~", $ip ) && !preg_match( "~^192\.168\.~", $ip ) ) { $ip_valid = true; } else { $ip_valid = false; }
	return $ip_valid;
	}

function spamshield_get_regex_phrase( $input_array, $custom_delim = NULL, $flag = "N" ) {
	// Get Regex Phrase from an Array
	$flag_regex_arr = array( 
		"N" 			=> "\b(X)\b",
		"S" 			=> "^(X)\b",
		"E" 			=> "\b(X)$",
		"W" 			=> "^(X)$",
		"email_addr"	=> "^(X)$",
		"email_prefix" 	=> "^(X)",
		"email_domain" 	=> "@((ww[w0-9]|m)\.)?(X)$",
		"domain" 		=> "^((ww[w0-9]|m)\.)?(X)$",
		"atxtwrap"		=> "(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+)\.([a-z0-9\-_/'\"\.\/\?\&\=\~\@\s]+)(\s*)(['\"])?(\s*)(>|\])([a-z0-9\-\.\?\!,@\s]*)(X)([a-z0-9\-\.\?\!,@\s]*)(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])",
		"linkwrap"		=> "(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://((ww[w0-9]|m)\.)?(X)/?([a-z0-9\-_/'\"\.\?\&\=\~\@\s]*)(['\"])?(>|\])",
		"red_str"		=> "(X)", // Red-flagged string
		"rgx_str"		=> "(X)", // Regex-ready string
		);
	$regex_flag = $flag_regex_arr[$flag];
	$regex_phrase_pre_arr = array();
	foreach( $input_array as $i => $val ) {
		$val_reg_pre = spamshield_preg_quote($val);
		if ( $flag == "rgx_str" ) { $val_reg_pre = $val; }
		$regex_phrase_pre_arr[] = $val_reg_pre;
		}
	$regex_phrase_pre_str 	= implode( "|", $regex_phrase_pre_arr );
	$regex_phrase_str 		= preg_replace( "~X~", $regex_phrase_pre_str, $regex_flag );
	if ( !empty( $custom_delim ) ) {  $delim = $custom_delim; } else { $delim = "~"; }
	$regex_phrase = $delim.$regex_phrase_str.$delim."i";
	return $regex_phrase;
	}

// Standard Functions - END

function spamshield_first_action() {

	spamshield_start_session();
	// All all commands after this
	
	//Add Vars Here
	$key_main_page_hits		= 'wpss_page_hits_'.WPSS_HASH;
	$key_main_pages_hist 	= 'wpss_pages_hit_'.WPSS_HASH;
	$key_main_hits_per_page	= 'wpss_pages_hit_count_'.WPSS_HASH;
	$key_first_ref			= 'wpss_referer_init_'.WPSS_HASH;
	if ( !empty( $_SERVER['HTTP_REFERER'] ) ) { $current_ref = $_SERVER['HTTP_REFERER']; } else { $current_ref=''; }
	$key_auth_hist 			= 'wpss_author_history_'.WPSS_HASH;
	$key_comment_auth 		= 'comment_author_'.WPSS_HASH;
	$key_email_hist			= 'wpss_author_email_history_'.WPSS_HASH;
	$key_auth_url_hist 		= 'wpss_author_url_history_'.WPSS_HASH;
	
	if ( empty( $_SESSION['wpss_user_ip_init_'.WPSS_HASH] ) ) {
		$_SESSION['wpss_user_ip_init_'.WPSS_HASH] = $_SERVER['REMOTE_ADDR'];
		}

	$_SESSION['wpss_version_'.WPSS_HASH] 			= WPSS_VERSION;
	$_SESSION['wpss_site_url_'.WPSS_HASH_ALT] 		= WPSS_SITE_URL;
	$_SESSION['wpss_plugin_url_'.WPSS_HASH_ALT] 	= WPSS_PLUGIN_URL;
	$_SESSION['wpss_user_ip_current_'.WPSS_HASH]	= $_SERVER['REMOTE_ADDR'];
		
	if ( !is_admin() && !current_user_can('moderate_comments') ) {
		// Page hits
		if ( empty( $_SESSION[$key_main_page_hits] ) ) {
			$_SESSION[$key_main_page_hits] = 0;
			}
		++$_SESSION[$key_main_page_hits];
		// Pages visited history
		if ( empty( $_SESSION[$key_main_pages_hist] ) ) {
			$_SESSION[$key_main_pages_hist] = array();
			$_SESSION[$key_main_hits_per_page] = array();
			}
		$_SESSION[$key_main_pages_hist][] = spamshield_get_url();
		// Initial referrer
		if ( empty( $_SESSION[$key_first_ref] ) ) {
			if ( !empty( $current_ref ) ) { $_SESSION[$key_first_ref] = $current_ref; }
			else { $_SESSION[$key_first_ref] = '[No Referrer]'; }
			}
		if ( !empty( $_COOKIE[$key_comment_auth] ) ) {
			$stored_author_data 	= spamshield_get_author_data();
			$stored_author 			= $stored_author_data['comment_author'];
			$stored_author_email	= $stored_author_data['comment_author_email'];
			$stored_author_url 		= $stored_author_data['comment_author_url'];
			if ( empty( $_SESSION[$key_auth_hist] ) && !empty( $stored_author ) ) {
				$_SESSION[$key_auth_hist] = array();
				$_SESSION[$key_auth_hist][] = $stored_author;
				}
			if ( empty( $_SESSION[$key_email_hist] ) && !empty( $stored_author_email ) ) {
				$_SESSION[$key_email_hist] = array();
				$_SESSION[$key_email_hist][] = $stored_author_email;
				}
			if ( empty( $_SESSION[$key_auth_url_hist] ) && !empty( $stored_author_url ) ) {
				$_SESSION[$key_auth_url_hist] = array();
				$_SESSION[$key_auth_url_hist][] = $stored_author_url;
				}
			}
		}
	}

function spamshield_check_cache_status() {
	// TEST FOR CACHING
	$wpss_active_plugins = get_option( 'active_plugins' );
	$wpss_active_plugins_ser = serialize( $wpss_active_plugins );
	$wpss_active_plugins_ser_lc = strtolower($wpss_active_plugins_ser);
	if ( !defined( 'WP_CACHE' ) || ( ( defined( 'WP_CACHE' ) && constant( 'WP_CACHE' ) == false ) ) ) {
		$wpss_caching_status = 'INACTIVE';
		}
	elseif ( defined( 'WP_CACHE' ) && constant( 'WP_CACHE' ) == true ) {
		$wpss_caching_status = 'ACTIVE';
		}
	if ( !defined( 'ENABLE_CACHE' ) || ( ( defined( 'ENABLE_CACHE' ) && constant( 'ENABLE_CACHE' ) == false ) ) ) {
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
	// Akismet's counter is (or was) taking credit for some spam killed by WP-SpamShield - the following ensures accurate reporting.
	// The reason for this fix is that Akismet may have marked the same comment as spam, but WP-SpamShield actually kills it - with or without Akismet.
	$ak_count_pre	= get_option('ak_count_pre');
	$ak_count_post	= get_option('akismet_spam_count');
	if ($ak_count_post > $ak_count_pre) { update_option( 'akismet_spam_count', $ak_count_pre ); }
	}

// Counters - BEGIN

function spamshield_counter( $counter_option = 0 ) {
	$counter_option_max = 9;
	$counter_option_min = 1;
	if ( empty ( $counter_option ) || $counter_option > $counter_option_max || $counter_option < $counter_option_min ) {
		$spamshield_count = number_format( get_option('spamshield_count') );
		echo '<a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="text-decoration:none;" rel="external" title="WP-SpamShield - WordPress Anti-Spam Plugin" >'.$spamshield_count.' spam blocked by WP-SpamShield</a>';
		return;
		}
	// Display Counter
	/* Implementation: <?php if ( function_exists('spamshield_counter') ) { spamshield_counter(1); } ?> */
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
			elseif ( $counter_option == 4 || $counter_option == 9 ) {
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
			elseif ( $counter_option == 5 ) {
				echo '<strong style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="Spam Killed by WP-SpamShield, a WordPress Anti-Spam Plugin" >';
				echo '<span style="color:#FEB22B;font-size:14px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'; 
				echo '</a></strong>'; 
				}
			elseif ( $counter_option == 6 ) {
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

function spamshield_counter_short( $attr = NULL ) {
	
	$counter_option = $attr['style'];
	
	$counter_option_max = 9;
	$counter_option_min = 1;
	if ( empty( $counter_option ) || $counter_option > $counter_option_max || $counter_option < $counter_option_min ) {
		$spamshield_count = number_format( get_option('spamshield_count') );
		$wpss_shortcode_content = '<a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="text-decoration:none;" rel="external" title="WP-SpamShield - WordPress Anti-Spam Plugin" >'.$spamshield_count.' spam blocked by WP-SpamShield</a>'."\n";
		return $wpss_shortcode_content;
		}
	// Display Counter
	/* Implementation: <?php if ( function_exists('spamshield_counter') ) { spamshield_counter(1); } ?> */
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
	elseif ( $counter_option == 4 || $counter_option == 9 ) {
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
	elseif ( $counter_option == 5 ) {
		$wpss_shortcode_content .= '	<strong style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="Spam Killed by WP-SpamShield, a WordPress Anti-Spam Plugin" >'."\n";
		$wpss_shortcode_content .= '	<span style="color:#FEB22B;font-size:14px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n"; 
		$wpss_shortcode_content .= '	</a></strong>'."\n"; 
		}
	elseif ( $counter_option == 6 ) {
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

function spamshield_counter_sm( $counter_sm_option = 1 ) {
	$counter_sm_option_max = 5;
	$counter_sm_option_min = 1;
	if ( empty( $counter_sm_option ) || $counter_sm_option > $counter_sm_option_max || $counter_sm_option < $counter_sm_option_min ) {
		$counter_sm_option = 1;
		}
	// Display Small Counter
	/* Implementation: <?php if ( function_exists('spamshield_counter_sm') ) { spamshield_counter_sm(1); } ?> */
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

function spamshield_counter_sm_short( $attr = NULL ) {
	
	$counter_sm_option = $attr['style'];
	
	$counter_sm_option_max = 5;
	$counter_sm_option_min = 1;
	if ( empty( $counter_sm_option ) || $counter_sm_option > $counter_sm_option_max || $counter_sm_option < $counter_sm_option_min ) {
		$counter_sm_option = 1;
		}
	// Display Small Counter
	/* Implementation: <?php if ( function_exists('spamshield_counter_sm') ) { spamshield_counter_sm(1); } ?> */
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
function spamshield_register_widget() {
	function spamshield_sidebar_widget_1( $args = NULL ) {
		extract($args);
		echo $before_widget;
		echo $before_title.'Spam'.$after_title;
		spamshield_counter_sm();
		echo $after_widget;
		}
	$id 		= 'wpss_widget_1';
	$name 		= 'WP-SpamShield Counter';
	$func 		= 'spamshield_sidebar_widget_1';
	$options 	= array(
					'description' => 'Show how much spam is being blocked by WP-SpamShield.'
					);
	wp_register_sidebar_widget($id,$name,$func,$options);
	}

// Counters - END
	
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
	if ( !empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$wpss_htaccess_http_host = str_replace( '.', '\.', $_SERVER['HTTP_HOST'] );
		$wpss_htaccess_blog_url = str_replace( '.', '\.', WPSS_SITE_URL );
		if ( !empty( $wpss_htaccess_blog_url ) ) {
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

function spamshield_update_session_data( $spamshield_options, $extra_data = NULL ) {
	$_SESSION['wpss_spamshield_options_'.WPSS_HASH] 	= $spamshield_options;
	$_SESSION['wpss_version_'.WPSS_HASH] 				= WPSS_VERSION;
	$_SESSION['wpss_site_url_'.WPSS_HASH_ALT] 			= WPSS_SITE_URL;
	$_SESSION['wpss_plugin_url_'.WPSS_HASH_ALT] 		= WPSS_PLUGIN_URL;
	$_SESSION['wpss_user_ip_current_'.WPSS_HASH]		= $_SERVER['REMOTE_ADDR'];
	// First Referrer - Where Visitor Entered Site
	if ( !empty( $_SERVER['HTTP_REFERER'] ) && empty( $_SESSION['wpss_referer_init_'.WPSS_HASH] ) ) {
		$_SESSION['wpss_referer_init_'.WPSS_HASH] = $_SERVER['HTTP_REFERER']; 
		}	
	}

function spamshield_get_key_values() {
	// Set Cookie & JS Values - BEGIN
	$wpss_session_id = @session_id();
	$wpss_server_ip_nodot = preg_replace( "~\.~", "", $_SERVER['SERVER_ADDR'] );
	//CK
	$wpss_ck_key_phrase 	= 'wpss_ckkey_'.$wpss_server_ip_nodot.'_'.$wpss_session_id;
	$wpss_ck_val_phrase 	= 'wpss_ckval_'.$wpss_server_ip_nodot.'_'.$wpss_session_id;
	$wpss_ck_key 			= hash( 'md5', $wpss_ck_key_phrase );
	$wpss_ck_val 			= hash( 'md5', $wpss_ck_val_phrase );
	//JS
	$wpss_js_key_phrase 	= 'wpss_jskey_'.$wpss_server_ip_nodot.'_'.$wpss_session_id;
	$wpss_js_val_phrase 	= 'wpss_jsval_'.$wpss_server_ip_nodot.'_'.$wpss_session_id;
	$wpss_js_key 			= hash( 'md5', $wpss_js_key_phrase );
	$wpss_js_val 			= hash( 'md5', $wpss_js_val_phrase );
	// Set Cookie & JS Values - END
	$wpss_key_values = array(
		'wpss_ck_key'	=> $wpss_ck_key,
		'wpss_ck_val' 	=> $wpss_ck_val,
		'wpss_js_key' 	=> $wpss_js_key,
		'wpss_js_val' 	=> $wpss_js_val,						
		);
	return $wpss_key_values;
	}

function spamshield_append_log_data( $str = NULL ) {
	$key_append_log_data	= 'wpss_append_log_data_'.WPSS_HASH;
	if ( empty( $_SESSION[$key_append_log_data] ) ) { $_SESSION[$key_append_log_data] = ''; }
	$_SESSION[$key_append_log_data] .= $str;
	}


function spamshield_get_log_session_data() {
	$noda 					= '[No Data]';
	$key_total_page_hits	= 'wpss_page_hits_js_'.WPSS_HASH;
	$key_last_ref			= 'wpss_jscripts_referer_last_'.WPSS_HASH;
	$key_pages_hist 		= 'wpss_jscripts_referers_history_'.WPSS_HASH;
	$key_hits_per_page		= 'wpss_jscripts_referers_history_count_'.WPSS_HASH;
	$key_ip_hist 			= 'wpss_jscripts_ip_history_'.WPSS_HASH;
	$key_init_ip			= 'wpss_user_ip_init_'.WPSS_HASH;
	$key_first_ref			= 'wpss_referer_init_'.WPSS_HASH;
	$key_auth_hist 			= 'wpss_author_history_'.WPSS_HASH;
	$key_comment_auth 		= 'comment_author_'.WPSS_HASH;
	$key_email_hist 		= 'wpss_author_email_history_'.WPSS_HASH;
	$key_comment_email		= 'comment_author_email_'.WPSS_HASH;
	$key_auth_url_hist 		= 'wpss_author_url_history_'.WPSS_HASH;
	$key_comment_url		= 'comment_author_url_'.WPSS_HASH;
	$key_comment_acc		= 'wpss_comments_accepted_'.WPSS_HASH;
	$key_comment_den		= 'wpss_comments_denied_'.WPSS_HASH;
	$key_comment_stat_curr	= 'wpss_comments_status_current_'.WPSS_HASH;
	$key_append_log_data	= 'wpss_append_log_data_'.WPSS_HASH;
	$wpss_session_id = @session_id();
	if ( !empty( $_COOKIE['PHPSESSID'] ) ) { 
		$wpss_session_ck = $_COOKIE['PHPSESSID'];
		if ( $wpss_session_ck == $wpss_session_id  ) { $wpss_session_verified = '[Verified]'; } 
		else { $wpss_session_verified = '[Not Verified]'; }
		} 
	else { $wpss_session_ck = '[No Session Cookie]'; $wpss_session_verified = '[Not Verified]'; }
	if ( !empty( $_SESSION[$key_total_page_hits] ) ) { $wpss_page_hits = $_SESSION[$key_total_page_hits]; }
	else { $wpss_page_hits = $noda; }
	if ( !empty( $_SESSION[$key_last_ref] ) ) { $wpss_last_page_hit = $_SESSION[$key_last_ref]; }
	else { $wpss_last_page_hit = $noda; }
	$wpss_pages_history = '';
	if ( !empty( $_SESSION[$key_pages_hist] ) ) { $wpss_pages_history_data = $_SESSION[$key_pages_hist]; }
	else { $wpss_pages_history = $noda; }
	if ( $wpss_pages_history != $noda ) {
		$wpss_pages_history = "\n";
		foreach ( $wpss_pages_history_data as $page ) { $wpss_pages_history .= "['".$page."']\n"; }
		}
	$wpss_hits_per_page = '';
	if ( !empty( $_SESSION[$key_hits_per_page] ) ) { $wpss_hits_per_page_data = $_SESSION[$key_hits_per_page]; }
	else { $wpss_hits_per_page = $noda; }
	if ( $wpss_hits_per_page != $noda ) {
		$wpss_hits_per_page = "\n";
		foreach ( $wpss_hits_per_page_data as $page => $hits ) { $wpss_hits_per_page .= "['".$hits."'] ['".$page."']\n"; }
		}
	if ( !empty( $_SESSION[$key_init_ip] ) ) { $wpss_user_ip_init = $_SESSION[$key_init_ip]; } else { $wpss_user_ip_init = $noda; }		
	if ( !empty( $_SESSION[$key_ip_hist] ) ) { $wpss_ip_history = implode(', ', $_SESSION[$key_ip_hist]); }
	else { $wpss_ip_history = $noda; }
	if ( !empty( $_SESSION[$key_first_ref] ) ) { $wpss_referer_init = $_SESSION[$key_first_ref]; } else { $wpss_referer_init = $noda; }
	if ( !empty( $_SESSION[$key_auth_hist] ) ) { $wpss_author_history = implode(', ', $_SESSION[$key_auth_hist]); }
	elseif ( !empty( $_COOKIE[$key_comment_auth] ) ) { $wpss_author_history = $_COOKIE[$key_comment_auth]; }
	else { $wpss_author_history = $noda; }
	if ( !empty( $_SESSION[$key_email_hist] ) ) { $wpss_author_email_history = implode(', ', $_SESSION[$key_email_hist]); }
	elseif ( !empty( $_COOKIE[$key_comment_email] ) ) { $wpss_author_email_history = $_COOKIE[$key_comment_email]; } 
	else { $wpss_author_email_history = $noda; }
	if ( !empty( $_SESSION[$key_auth_url_hist] ) ) { $wpss_author_url_history = implode(', ', $_SESSION[$key_auth_url_hist]); }
	elseif ( !empty( $_COOKIE[$key_comment_url] ) ) { $wpss_author_url_history = $_COOKIE[$key_comment_url]; } 
	else { $wpss_author_url_history = $noda; }
	if ( !empty( $_SESSION[$key_comment_acc] ) ) { $wpss_comments_accepted = $_SESSION[$key_comment_acc]; }
	else { $wpss_comments_accepted = $noda; }
	if ( !empty( $_SESSION[$key_comment_den] ) ) { $wpss_comments_denied = $_SESSION[$key_comment_den]; }
	else { $wpss_comments_denied = $noda; }
	//Current status
	if ( !empty( $_SESSION[$key_comment_stat_curr] ) ) { $wpss_comments_status_current = $_SESSION[$key_comment_stat_curr]; }
	else { $wpss_comments_status_current = $noda; }
	if ( !empty( $_SESSION[$key_append_log_data] ) ) { $wpss_append_log_data = $_SESSION[$key_append_log_data]; }
	else { $wpss_append_log_data = $noda; }
	$wpss_log_session_data = array(
		'wpss_session_id'				=> $wpss_session_id,
		'wpss_session_ck'				=> $wpss_session_ck,
		'wpss_session_verified'			=> $wpss_session_verified,
		'wpss_page_hits'				=> $wpss_page_hits,
		'wpss_last_page_hit'			=> $wpss_last_page_hit,
		'wpss_pages_history'			=> $wpss_pages_history,
		'wpss_hits_per_page'			=> $wpss_hits_per_page,
		'wpss_user_ip_init'				=> $wpss_user_ip_init,
		'wpss_ip_history'				=> $wpss_ip_history,
		'wpss_referer_init'				=> $wpss_referer_init,
		'wpss_author_history'			=> $wpss_author_history,
		'wpss_author_email_history'		=> $wpss_author_email_history,
		'wpss_author_url_history'		=> $wpss_author_url_history,
		'wpss_comments_accepted'		=> $wpss_comments_accepted,
		'wpss_comments_denied'			=> $wpss_comments_denied,
		'wpss_comments_status_current'	=> $wpss_comments_status_current,
		'wpss_append_log_data'			=> $wpss_append_log_data,
		);
	return $wpss_log_session_data;
	}
	
function spamshield_log_data( $wpss_log_comment_data_array, $wpss_log_comment_data_errors, $wpss_log_comment_type = 'comment', $wpss_log_contact_form_data = NULL ) {

	$wpss_log_filename = 'temp-comments-log.txt';
	$wpss_log_empty_filename = 'temp-comments-log.init.txt';
	$wpss_log_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_log_filename;
	$wpss_log_max_filesize = 2*1048576; // 2 MB
	
	if ( empty( $wpss_log_comment_type) ) { $wpss_log_comment_type = 'comment'; }
	$wpss_log_comment_type_display 			= strtoupper($wpss_log_comment_type);
	$wpss_log_comment_type_ucwords			= ucwords($wpss_log_comment_type);
	$wpss_log_comment_type_ucwords_ref_disp	= preg_replace("~\sform~i", "", $wpss_log_comment_type_ucwords);
	
	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);

	$wpss_log_session_data 			= spamshield_get_log_session_data();
	$wpss_session_id 				= $wpss_log_session_data['wpss_session_id'];
	$wpss_session_ck 				= $wpss_log_session_data['wpss_session_ck'];
	$wpss_session_verified 			= $wpss_log_session_data['wpss_session_verified'];
	$wpss_page_hits 				= $wpss_log_session_data['wpss_page_hits'];
	$wpss_last_page_hit 			= $wpss_log_session_data['wpss_last_page_hit'];
	$wpss_pages_history 			= $wpss_log_session_data['wpss_pages_history'];
	$wpss_hits_per_page 			= $wpss_log_session_data['wpss_hits_per_page'];
	$wpss_user_ip_init 				= $wpss_log_session_data['wpss_user_ip_init'];
	$wpss_ip_history 				= $wpss_log_session_data['wpss_ip_history'];
	$wpss_referer_init 				= $wpss_log_session_data['wpss_referer_init'];
	$wpss_author_history 			= $wpss_log_session_data['wpss_author_history'];
	$wpss_author_email_history 		= $wpss_log_session_data['wpss_author_email_history'];
	$wpss_author_url_history 		= $wpss_log_session_data['wpss_author_url_history'];
	$wpss_comments_accepted 		= $wpss_log_session_data['wpss_comments_accepted'];
	$wpss_comments_denied 			= $wpss_log_session_data['wpss_comments_denied'];
	$wpss_comments_status_current	= $wpss_log_session_data['wpss_comments_status_current'];
	$wpss_append_log_data			= $wpss_log_session_data['wpss_append_log_data'];
	
	$comment_logging 				= $spamshield_options['comment_logging'];
	$comment_logging_start_date 	= $spamshield_options['comment_logging_start_date'];
	$comment_logging_all 			= $spamshield_options['comment_logging_all'];
	
	$wpss_javascript_page_referrer	= $wpss_log_comment_data_array['javascript_page_referrer'];
	//$wpss_php_page_referrer 		= $wpss_log_comment_data_array['php_page_referrer'];
	$wpss_jsonst		 			= $wpss_log_comment_data_array['jsonst'];
	$get_current_time = time();
	// Updated next line in Version 1.1.4.4 - Display local time in logs. Won't match other time logs, because those need to be UTC.
	$get_current_time_display = current_time( 'timestamp', 0 );
	$reset_interval_hours = 24 * 7; // Reset interval in hours
	$reset_interval_minutes = 60; // Reset interval minutes default
	$reset_interval_minutes_override = $reset_interval_minutes; // Use as override for testing; leave = $reset_interval_minutes when not testing
	if ( $reset_interval_minutes_override != $reset_interval_minutes ) {
		$reset_interval_hours = 1;
		$reset_interval_minutes = $reset_interval_minutes_override;
		}
	// Default is one week
	$reset_interval = 60 * $reset_interval_minutes * $reset_interval_hours; // seconds * minutes * hours
	if ( strpos( WPSS_SERVER_NAME_REV, WPSS_DEBUG_SERVER_NAME_REV ) === 0 ) { $reset_interval = $reset_interval * 4; }
	// $time_threshold = $get_current_time - ( 60 * $reset_interval_minutes * $reset_interval_hours ); // seconds * minutes * hours
	$time_threshold = $get_current_time - $reset_interval; // seconds * minutes * hours
	// This turns off if over x amount of time since starting, or filesize exceeds max
	if ( ( !empty( $comment_logging_start_date ) && $time_threshold > $comment_logging_start_date ) || ( file_exists( $wpss_log_file ) && filesize( $wpss_log_file ) >= $wpss_log_max_filesize ) ) {
		//spamshield_log_reset();
		$comment_logging = 0;
		$comment_logging_start_date = 0;
		$comment_logging_all = 0;
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
			'comment_logging'						=> $comment_logging,
			'comment_logging_start_date'			=> $comment_logging_start_date,
			'comment_logging_all'					=> $comment_logging_all,
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
		$wpss_log_datum = date("Y-m-d (D) H:i:s",$get_current_time_display);
		$wpss_log_comment_data = "***********************************************************************************************\n";
		$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= ":: ".$wpss_log_comment_type_display." BEGIN ::"."\n";
		
		$submitter_ip_address = $_SERVER['REMOTE_ADDR'];
		$submitter_ip_address_short_l = trim( substr( $submitter_ip_address, 0, 6) );
		$submitter_ip_address_short_r = trim( substr( $submitter_ip_address, -6, 2) );
		$submitter_ip_address_obfuscated = $submitter_ip_address_short_l.'****'.$submitter_ip_address_short_r.'.***';
		
		// IP / PROXY INFO - BEGIN
		$ip = $submitter_ip_address;
		$ipBlock=explode('.',$ip);
		if ( !empty( $_SERVER['HTTP_VIA'] ) ) { $ip_proxy_via=trim($_SERVER['HTTP_VIA']); } else { $ip_proxy_via = ''; }
		$ip_proxy_via_lc=strtolower($ip_proxy_via);
		if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { $masked_ip = trim($_SERVER['HTTP_X_FORWARDED_FOR']); } else { $masked_ip = ''; }
		$masked_ip_block=explode('.',$masked_ip);
		if ( spamshield_is_valid_ip( $masked_ip ) ) {
			$masked_ip_valid = true;
			$masked_ip_core = rtrim( $masked_ip, " unknown;," );
			}
		$reverse_dns = gethostbyaddr($ip);
		if ( $reverse_dns == '.' ) { $reverse_dns_ip = '[No Data]'; } elseif ( $reverse_dns == $ip ) { $reverse_dns_ip = $ip; } else { $reverse_dns_ip = gethostbyname($reverse_dns); }

		$submitter_remote_host = $reverse_dns;
		
		if ( $reverse_dns_ip != $ip || $ip == $reverse_dns ) {
			$reverse_dns_authenticity = '[Possibly Forged]';
			} 
		else {
			$reverse_dns_authenticity = '[Verified]';
			}
		// Detect Use of Proxy
		if ( !empty( $ip_proxy_via ) || !empty( $masked_ip ) ) {
			if ( empty( $masked_ip ) ) { $masked_ip='[No Data]'; }
			$ip_proxy='PROXY DETECTED';
			$ip_proxy_short='PROXY';
			$ip_proxy_data=$ip.' | MASKED IP: '.$masked_ip;
			$proxy_status='TRUE';
			//Google Chrome Compression Check
			if ( strpos( $ip_proxy_via_lc, 'chrome compression proxy' ) !== false && preg_match( "~^google-proxy-(.*)\.google\.com$~i", $reverse_dns ) ) {
				$ip_proxy_chrome_compression='TRUE';
				}
			else {
				$ip_proxy_chrome_compression='FALSE';
				}
			}
		else {
			$ip_proxy='No Proxy';
			$ip_proxy_short=$ip_proxy;
			$ip_proxy_data='[None]';
			$proxy_status='FALSE';
			}
		// IP / PROXY INFO - END
		
		if ( WPSS_WIN_SERVER == 'Win' ) {
			$wpss_win_server = WPSS_WIN_SERVER;
			$wpss_win_server_disp = $wpss_win_server.'; ';
			}
		else {
			$wpss_win_server = '';
			$wpss_win_server_disp = '';
			}
		
		if ( $wpss_log_comment_type == 'comment' ) {
			$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
			// Comment Post Info
			$comment_author_email = $wpss_log_comment_data_array['comment_author_email'];
			$comment_types_allowed = '';
			if ( !empty( $wpss_log_comment_data_array['comment_post_comments_open'] ) ) {
				$comment_post_comments_open = 'Open';
				$comment_types_allowed .= 'comments';
				}
			else {
				$comment_post_comments_open = 'Closed';
				}
			if ( !empty( $wpss_log_comment_data_array['comment_post_pings_open'] ) ) {
				$comment_post_pings_open = 'Open';
				if ( !empty( $comment_types_allowed ) ) {
					$comment_types_allowed .= ',';
					}
				$comment_types_allowed .= 'pingbacks,trackbacks';
				}
			else {
				$comment_post_pings_open = 'Closed';
				}
			if ( empty( $comment_types_allowed ) ) {
					$comment_types_allowed = 'none, comments closed';
					}
			$comment_post_type_ucw = ucwords($wpss_log_comment_data_array['comment_post_type']);
			
			$wpss_log_comment_data .= "Date/Time: 		['".$wpss_log_datum."']\n";
			$wpss_log_comment_data .= "Comment Post ID: 	['".$wpss_log_comment_data_array['comment_post_ID']."']\n";
			$wpss_log_comment_data .= "Comment Post Title: 	['".$wpss_log_comment_data_array['comment_post_title']."']\n";
			$wpss_log_comment_data .= "Comment Post URL: 	['".$wpss_log_comment_data_array['comment_post_url']."']\n";
			$wpss_log_comment_data .= "Comment Post Type: 	['".$wpss_log_comment_data_array['comment_post_type']."']\n";
			$wpss_log_comment_data .= $comment_post_type_ucw." Allows Types:	['".$comment_types_allowed."']\n";
			$wpss_log_comment_data .= "Comment Type: 		['";
			if ( !empty( $wpss_log_comment_data_array['comment_type'] ) ) {
				$wpss_log_comment_data .= $wpss_log_comment_data_array['comment_type'];
				}
			else {
				$wpss_log_comment_data .= "comment";
				}
			$wpss_log_comment_data .= "']";
			$wpss_log_comment_data .= "\n";
			$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
			$wpss_log_comment_data .= "Comment Author: 	['".$wpss_log_comment_data_array['comment_author']."']\n";
			$wpss_log_comment_data .= "Comment Author Email: 	['".$comment_author_email."']\n";
			$wpss_log_comment_data .= "Comment Author URL: 	['".$wpss_log_comment_data_array['comment_author_url']."']\n";
			$wpss_log_comment_data .= "Comment Content: "."\n['comment_content_begin']\n".$wpss_log_comment_data_array['comment_content']."\n['comment_content_end']\n";

			}
		elseif ( $wpss_log_comment_type == 'contact form' ) {
			$wpss_log_comment_data .= "Date/Time: 		['".$wpss_log_datum."']\n";	
			$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
			$wpss_log_comment_data .= $wpss_log_contact_form_data;
			//$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
			}
		$wpss_sessions_enabled = isset( $_SESSION ) ? 'Enabled' : 'Disabled';
		
		if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
			$wpss_http_accept_language 	= sanitize_text_field($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			} else { $wpss_http_accept_language = ''; }
		if ( !empty( $_SERVER['HTTP_ACCEPT'] ) ) {
			$wpss_http_accept 			= sanitize_text_field($_SERVER['HTTP_ACCEPT']);
			} else { $wpss_http_accept = ''; }
		if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$wpss_http_user_agent 		= sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
			} else { $wpss_http_user_agent = ''; }
		if ( !empty( $_SERVER['HTTP_REFERER'] ) ) {
			$wpss_http_referer 			= esc_url_raw($_SERVER['HTTP_REFERER']);
			} else { $wpss_http_referer = ''; }	
		$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= "IP Address: 		['".$ip."'] ['http://whatismyipaddress.com/ip/".$ip."']\n";
		$wpss_log_comment_data .= "Reverse DNS: 		['".$reverse_dns."']\n";
		$wpss_log_comment_data .= "Reverse DNS IP: 	['".$reverse_dns_ip."']\n";
		$wpss_log_comment_data .= "Reverse DNS Verified: 	['".$reverse_dns_authenticity."']\n";
		$wpss_log_comment_data .= "Proxy Info: 		['".$ip_proxy."']\n";
		$wpss_log_comment_data .= "Proxy Data: 		['".$ip_proxy_data."']\n";
		$wpss_log_comment_data .= "Proxy Status: 		['".$proxy_status."']\n";
		if ( empty( $ip_proxy_via ) ) { $ip_proxy_via = '[None]'; }
		$wpss_log_comment_data .= "HTTP_VIA: 		['".$ip_proxy_via."']\n";
		if ( empty( $masked_ip ) ) { $masked_ip = '[None]'; }
		$wpss_log_comment_data .= "HTTP_X_FORWARDED_FOR: 	['".$masked_ip."']\n";
		$wpss_log_comment_data .= "HTTP_ACCEPT_LANGUAGE: 	['".$wpss_http_accept_language."']\n";
		$wpss_log_comment_data .= "HTTP_ACCEPT: 		['".$wpss_http_accept."']\n";
		$wpss_log_comment_data .= "User-Agent: 		['".$wpss_http_user_agent."']\n";
		$wpss_log_comment_data .= $wpss_log_comment_type_ucwords_ref_disp." Processor Ref: 	['";
		if ( !empty( $wpss_http_referer ) ) {
			$wpss_log_comment_data .= $wpss_http_referer;
			}
		else { $wpss_log_comment_data .= '[None]'; }
		$wpss_log_comment_data .= "']";
		$wpss_log_comment_data .= "\n";
		$wpss_log_comment_data .= "JS Page Ref: 		['";
		if ( !empty( $wpss_javascript_page_referrer ) ) {
			$wpss_log_comment_data .= $wpss_javascript_page_referrer;
			}
		else { $wpss_log_comment_data .= '[None]'; }
		$wpss_log_comment_data .= "']";
		$wpss_log_comment_data .= "\n";

		$wpss_log_comment_data .= "JSONST: 		['";
		if ( !empty( $wpss_jsonst ) ) {
			$wpss_log_comment_data .= $wpss_jsonst;
			}
		else { $wpss_log_comment_data .= '[None]'; }
		$wpss_log_comment_data .= "']";
		$wpss_log_comment_data .= "\n";

		// New Data Section - Begin
		if ( strpos( WPSS_SERVER_NAME_REV, WPSS_DEBUG_SERVER_NAME_REV ) === 0 ) {
			$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
			$wpss_log_comment_data .= "PHP Session ID: 	['".$wpss_session_id."']\n";
			$wpss_log_comment_data .= "PHP Session Cookie: 	['".$wpss_session_ck."']\n";
			$wpss_log_comment_data .= "Sess ID/CK Match: 	['".$wpss_session_verified."']\n";
			$wpss_log_comment_data .= "Page Hits: 		['".$wpss_page_hits."']\n";
			$wpss_log_comment_data .= "Last Page Hit: 		['".$wpss_last_page_hit."']\n";
			$wpss_log_comment_data .= "Hits Per Page: "."\n['hits_per_page_begin']".$wpss_hits_per_page."['hits_per_page_end']\n";
			$wpss_log_comment_data .= "Original IP: 		['".$wpss_user_ip_init."']\n";
			$wpss_log_comment_data .= "IP History: 		['".$wpss_ip_history."']\n";
			$wpss_log_comment_data .= "Original Referrer: 	['".$wpss_referer_init."']\n";
			$wpss_log_comment_data .= "Author History:		['".$wpss_author_history."']\n";
			$wpss_log_comment_data .= "Email History:		['".$wpss_author_email_history."']\n";
			$wpss_log_comment_data .= "URL History: 		['".$wpss_author_url_history."']\n";
			$wpss_log_comment_data .= "Comments Accepted: 	['".$wpss_comments_accepted."']\n";
			$wpss_log_comment_data .= "Comments Denied: 	['".$wpss_comments_denied."']\n";
			$wpss_log_comment_data .= "Status Before This: 	['".$wpss_comments_status_current."']\n";
			$wpss_log_comment_data .= "Extra Data: 		['".$wpss_append_log_data."']\n";
			}
		// New Data Section - End
		$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
		if ( $wpss_log_comment_data_errors == 'No Error' ) {
			$wpss_log_comment_data_errors_count = 0;
			}
		else {
			$wpss_log_comment_data_errors_count = spamshield_count_words($wpss_log_comment_data_errors);
			}
		if ( empty( $wpss_log_comment_data_errors ) ) { $wpss_log_comment_data_errors = '[None]'; }
		if ( $wpss_log_comment_type == 'comment' ) {
			$wpss_total_time_content_filter 		= $wpss_log_comment_data_array['total_time_content_filter'];
			$wpss_start_time_comment_processing 	= $wpss_log_comment_data_array['start_time_comment_processing'];
			// $wpss_start_time_jsc_filter 			= $wpss_log_comment_data_array['start_time_jsc_filter'];
			// $wpss_end_time_jsc_filter 			= $wpss_log_comment_data_array['end_time_jsc_filter'];
			// $wpss_total_time_jsc_filter 			= spamshield_timer( $wpss_start_time_jsc_filter, $wpss_end_time_jsc_filter );
			// Timer End - Comment Processing
			$wpss_end_time_comment_processing 		= spamshield_microtime();
			$wpss_total_time_comment_processing 	= spamshield_timer( $wpss_start_time_comment_processing, $wpss_end_time_comment_processing );
			$wpss_total_time_jsc_filter = substr( ( $wpss_total_time_comment_processing - $wpss_total_time_content_filter + 0.000000001 ), 0, 8 );
			$wpss_log_comment_data .= "JS/C Processing Time: 	['".$wpss_total_time_jsc_filter." seconds'] Time for JS/Cookies Layer to test for spam\n";
			$wpss_log_comment_data .= "Algo Processing Time: 	['".$wpss_total_time_content_filter." seconds'] Time for Algorithmic Layer to test for spam\n";
			$wpss_log_comment_data .= "Total Processing Time: 	['".$wpss_total_time_comment_processing." seconds'] Total time for WP-SpamShield to process comment\n";
			$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
			}
		$wpss_log_comment_data .= "Failed Tests: 		['".$wpss_log_comment_data_errors_count."']\n";
		$wpss_log_comment_data .= "Failed Test Codes: 	['".$wpss_log_comment_data_errors."']\n";
		$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= "Debugging Data:		['PHP MemLimit: ".WPSS_PHP_MEM_LIMIT."; WP MemLimit: ".WP_MEMORY_LIMIT."; Sessions: ".$wpss_sessions_enabled."']\n";
		$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= WPSS_USER_AGENT."\n";
		$wpss_log_comment_data .= WPSS_PHP_UNAME."\n";
		$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= ":: ".$wpss_log_comment_type_display." END ::"."\n";
		$wpss_log_comment_data .= "-----------------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= "***********************************************************************************************\n\n\n";

		@$wpss_log_fp = fopen( $wpss_log_file,'a+' );
		@fwrite( $wpss_log_fp, $wpss_log_comment_data );
		@fclose( $wpss_log_fp );
		}
	}

function spamshield_content_addendum($content) {
	if ( !is_feed() && !is_page() && !is_home() ) {
		$spamshield_options = get_option('spamshield_options');
		spamshield_update_session_data($spamshield_options);
		$wpss_key_values 			= spamshield_get_key_values();
		$wpss_ck_key  				= $wpss_key_values['wpss_ck_key'];
		$wpss_ck_val 				= $wpss_key_values['wpss_ck_val'];
		$wpss_js_key 				= $wpss_key_values['wpss_js_key'];
		$wpss_js_val 				= $wpss_key_values['wpss_js_val'];
		$use_alt_cookie_method		= $spamshield_options['use_alt_cookie_method'];
		$use_alt_cookie_method_only	= $spamshield_options['use_alt_cookie_method_only'];
		if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wpss_comment_validation_js = $_COOKIE[$wpss_ck_key]; }
		else { $wpss_comment_validation_js = ''; }
		if ( !empty( $_POST[$wpss_js_key] ) ) {
			$wpss_form_validation_post 	= $_POST[$wpss_js_key]; //Comments Post Verification
			}
		else { $wpss_form_validation_post = ''; }
		// DEPRECATED - BEGIN
		if ( ( $wpss_comment_validation_js != $wpss_ck_val && !empty( $use_alt_cookie_method ) ) || !empty( $use_alt_cookie_method_only ) ) {
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
			$content .=  '<span'.$wpss_img_p_disp.'><img src="'.WPSS_PLUGIN_IMG_URL.'/img.php" width="0" height="0" alt="" style="border-style:none;width:0px;height:0px;'.$wpss_img_disp.'" /></span>'; // DEPRECATED
			}
		// DEPRECATED - END
		}
	return $content;
	}

function spamshield_comment_form() {
	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);
	$promote_plugin_link 	= $spamshield_options['promote_plugin_link'];
	$wpss_key_values 	= spamshield_get_key_values();
	$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
	$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
	$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
	$wpss_js_val 		= $wpss_key_values['wpss_js_val'];
	if ( !empty( $promote_plugin_link ) ) {
		$server_ip_first_char = substr(WPSS_SERVER_ADDR, 0, 1);
		$server_ip_fifth_char = substr(WPSS_SERVER_ADDR, 4, 1);
		if ( $server_ip_first_char == '6' ) {
			echo '<p style="font-size:9px;clear:both;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >Spam Blocking</a> by WP-SpamShield</p>'."\n";
			}
		elseif ( $server_ip_first_char == '7' || $server_ip_first_char == '2' ) {
			echo '<p style="font-size:9px;clear:both;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >Anti-Spam</a> by WP-SpamShield</p>'."\n";
			}
		elseif ( $server_ip_first_char == '8' ) {
			echo '<p style="font-size:9px;clear:both;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >Comment Spam Protection</a> by WP-SpamShield</p>'."\n";
			}
		elseif ( $server_ip_first_char == '9' ) {
			echo '<p style="font-size:9px;clear:both;">Spam Protection by <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >WP-SpamShield Plugin</a></p>'."\n";
			}
		elseif ( $server_ip_fifth_char == '5' ) {
			echo '<p style="font-size:9px;clear:both;"><a href="http://wordpress.org/extend/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >Anti-Spam Protection</a> by WP-SpamShield</p>'."\n";
			}
		elseif ( $server_ip_fifth_char == '4' ) {
			echo '<p style="font-size:9px;clear:both;"><a href="http://wordpress.org/extend/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >Spam Defense</a> by WP-SpamShield</p>'."\n";
			}
		else {
			echo '<p style="font-size:9px;clear:both;">Spam Protection by <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield WordPress Anti-Spam Plugin" >WP-SpamShield</a></p>'."\n";
			}
		}
	
	if ( empty( $spamshield_options['use_alt_cookie_method'] ) && empty( $spamshield_options['use_alt_cookie_method_only'] ) ) {
		echo '<noscript><p><strong>Currently you have JavaScript disabled. In order to post comments, please make sure JavaScript and Cookies are enabled, and reload the page.</strong> <a href="http://enable-javascript.com/" rel="nofollow external" >Click here for instructions</a> on how to enable JavaScript in your browser.</p></noscript>'."\n";	
		}
	// If need to add anything else to comment area, start here
	
	echo '<input type="hidden" name="'.$wpss_js_key.'" value="'.$wpss_js_val .'" />'."\n";
	}

function spamshield_get_author_cookie_data() {
	// Get Comment Author Data Stored in Cookies
	$key_comment_auth	= 'comment_author_'.WPSS_HASH;
	$key_comment_email	= 'comment_author_email_'.WPSS_HASH;
	$key_comment_url 	= 'comment_author_url_'.WPSS_HASH;
	if ( !empty( $_COOKIE[$key_comment_auth] ) ) {
		$comment_author = $_COOKIE[$key_comment_auth]; 
		$_SESSION[$key_comment_auth] = $comment_author;
		} else { $comment_author = ''; }
	if ( !empty( $_COOKIE[$key_comment_email] ) ) {
		$comment_author_email = $_COOKIE[$key_comment_email]; 
		$_SESSION[$key_comment_email] = $comment_author_email;
		} else { $comment_author_email = ''; }
	if ( !empty( $_COOKIE[$key_comment_url] ) ) {
		$comment_author_url = $_COOKIE[$key_comment_url]; 
		$_SESSION[$key_comment_url] = $comment_author_url;
		} else { $comment_author_url = ''; }
	$author_data = array( 'comment_author' => $comment_author, 'comment_author_email' => $comment_author_email, 'comment_author_url' => $comment_author_url );
	return $author_data;
	}

function spamshield_get_author_data() {

	// Get Comment Author Data Stored in Cookies and Session Vars
	$key_comment_auth	= 'comment_author_'.WPSS_HASH;
	$key_comment_email	= 'comment_author_email_'.WPSS_HASH;
	$key_comment_url 	= 'comment_author_url_'.WPSS_HASH;
	if ( !empty( $_COOKIE[$key_comment_auth] ) ) {
		$comment_author = $_COOKIE[$key_comment_auth]; 
		$_SESSION[$key_comment_auth] = $comment_author;
		}
	elseif ( !empty( $_SESSION[$key_comment_auth] ) ) {
		$comment_author = $_SESSION[$key_comment_auth];
		} else { $comment_author = ''; }
	if ( !empty( $_COOKIE[$key_comment_email] ) ) {
		$comment_author_email = $_COOKIE[$key_comment_email]; 
		$_SESSION[$key_comment_email] = $comment_author_email;
		}
	elseif ( !empty( $_SESSION[$key_comment_email] ) ) {
		$comment_author_email = $_SESSION[$key_comment_email];
		} else { $comment_author_email = ''; }
	if ( !empty( $_COOKIE[$key_comment_url] ) ) {
		$comment_author_url = $_COOKIE[$key_comment_url]; 
		$_SESSION[$key_comment_url] = $comment_author_url;
		}
	elseif ( !empty( $_SESSION[$key_comment_url] ) ) {
		$comment_author_url = $_SESSION[$key_comment_url];
		} else { $comment_author_url = ''; }
	$author_data = array( 'comment_author' => $comment_author, 'comment_author_email' => $comment_author_email, 'comment_author_url' => $comment_author_url );
	return $author_data;
	}

function spamshield_update_sess_accept_status( $commentdata, $status = NULL, $line = NULL ) {
	$get_current_time_display = current_time( 'timestamp', 0 );
	$wpss_datum = date("Y-m-d (D) H:i:s",$get_current_time_display);
	$key_comment_acc 		= 'wpss_comments_accepted_'.WPSS_HASH;
	$key_comment_den 		= 'wpss_comments_denied_'.WPSS_HASH;
	$key_comment_stat_curr	= 'wpss_comments_status_current_'.WPSS_HASH;
	$key_auth_hist 			= 'wpss_author_history_'.WPSS_HASH;
	$key_email_hist 		= 'wpss_author_email_history_'.WPSS_HASH;
	$key_auth_url_hist 		= 'wpss_author_url_history_'.WPSS_HASH;
	if ( empty( $_SESSION[$key_comment_den] ) ) {
		$_SESSION[$key_comment_den] = 0;
		}
	if ( empty( $_SESSION[$key_comment_acc] ) ) {
		$_SESSION[$key_comment_acc] = 0;
		}
	if ( $status == 'r' ) {
		++$_SESSION[$key_comment_den];
		$_SESSION[$key_comment_stat_curr] = '[REJECTED '.$line.' '.$wpss_datum.']';
		}
	elseif ( $status == 'a' ) {
		++$_SESSION[$key_comment_acc];
		$_SESSION[$key_comment_stat_curr] = '[ACCEPTED '.$line.' '.$wpss_datum.']';
		}
	else { $_SESSION[$key_comment_stat_curr] = '[ERROR '.$line.' '.$wpss_datum.']'; }	
	$wpss_comment_author 		= $commentdata['comment_author'];
	$wpss_comment_author_email	= $commentdata['comment_author_email'];
	$wpss_comment_author_url 	= $commentdata['comment_author_url'];
	if ( empty ( $wpss_comment_author ) ) 		{ $wpss_comment_author 			= '[No Data: ERROR 1: '.$line.']'; }
	if ( empty ( $wpss_comment_author_email ) ) { $wpss_comment_author_email 	= '[No Data: ERROR 1: '.$line.']'; }
	if ( empty ( $wpss_comment_author_url ) ) 	{ $wpss_comment_author_url 		= '[No Data: ERROR 1: '.$line.']'; }
	$_SESSION['wpss_comment_author_'.WPSS_HASH] = $wpss_comment_author;
	if ( empty( $_SESSION[$key_auth_hist] ) ) { $_SESSION[$key_auth_hist] = array(); }
	$_SESSION[$key_auth_hist][] = $wpss_comment_author;
	$_SESSION['wpss_comment_author_email_'.WPSS_HASH] = $wpss_comment_author_email;
	if ( empty( $_SESSION[$key_email_hist] ) ) { $_SESSION[$key_email_hist] = array(); }
	$_SESSION[$key_email_hist][] = $wpss_comment_author_email;
	$_SESSION['wpss_comment_author_url_'.WPSS_HASH] = $wpss_comment_author_url;
	if ( empty( $_SESSION[$key_auth_url_hist] ) ) { $_SESSION[$key_auth_url_hist] = array(); }
	$_SESSION[$key_auth_url_hist][] = $wpss_comment_author_url;
	if ( empty( $_SESSION['wpss_commentdata_'.WPSS_HASH] ) ) { $_SESSION['wpss_current_commentdata_'.WPSS_HASH] = $commentdata; }
	}

function spamshield_contact_shortcode( $attr = NULL ) {
	/* Implementation: [spamshieldcontact] */
	$shortcode_check = 'shortcode';
	$content_new_shortcode = @spamshield_contact_form($content,$shortcode_check);
	return $content_new_shortcode;
	}
	
function spamshield_contact_form( $content, $shortcode_check = NULL ) {
	
	$spamshield_contact_repl_text = array( '<!--spamshield-contact-->', '<!--spamfree-contact-->' );
	
	$ip 						= $_SERVER['REMOTE_ADDR'];
	$ip_regex 					= spamshield_preg_quote($ip);
	$ip_lc						= strtolower($ip);
	$ip_lc_regex 				= spamshield_preg_quote($ip_lc);
	$reverse_dns 				= gethostbyaddr($ip);
	if ( $reverse_dns == '.' ) { $reverse_dns_ip = '[No Data]'; } elseif ( $reverse_dns == $ip ) { $reverse_dns_ip = $ip; } else { $reverse_dns_ip = gethostbyname($reverse_dns); }
	$reverse_dns_ip_regex 		= spamshield_preg_quote($reverse_dns_ip);
	$reverse_dns_lc				= strtolower($reverse_dns);
	$reverse_dns_lc_regex 		= spamshield_preg_quote($reverse_dns_lc);
	$reverse_dns_lc_rev 		= strrev($reverse_dns_lc);
	$reverse_dns_lc_rev_regex 	= spamshield_preg_quote($reverse_dns_lc_rev);
	if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) { $user_agent = trim($_SERVER['HTTP_USER_AGENT']); }
	else { $user_agent = ''; }
	$user_agent_lc 				= strtolower($user_agent);
	$user_agent_lc_word_count 	= spamshield_count_words($user_agent_lc);
	if ( !empty( $_SERVER['HTTP_ACCEPT'] ) ) { $user_http_accept = trim($_SERVER['HTTP_ACCEPT']); } else { $user_http_accept = ''; }
	$user_http_accept_lc		= strtolower($user_http_accept);
	if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) { $user_http_accept_language = trim($_SERVER['HTTP_ACCEPT_LANGUAGE']); }
	else { $user_http_accept_language = ''; }
	$user_http_accept_language_lc = strtolower($user_http_accept_language);
	$spamshield_contact_form_url = $_SERVER['REQUEST_URI'];
	$spamshield_contact_form_url_lc = strtolower($spamshield_contact_form_url);
	if ( !empty( $_SERVER['QUERY_STRING'] ) ) { $spamshield_contact_form_query_op = '&amp;'; } else { $spamshield_contact_form_query_op = '?'; }
	if ( !empty( $_GET['form'] ) ) { $get_form = $_GET['form']; } else { $get_form = ''; }
	if ( !empty( $_POST['JSONST'] ) ) { $post_jsonst = $_POST['JSONST']; } else { $post_jsonst = ''; }
	if ( !empty( $_POST['ref2xJS'] ) ) { $post_ref2xjs = $_POST['ref2xJS']; } else { $post_ref2xjs = ''; }	
	$post_ref2xjs_lc = strtolower($post_ref2xjs);
	$spamshield_error_code = '';
	$spamshield_contact_form_content = '';
	if ( is_page() && ( !is_home() && !is_feed() && !is_archive() && !is_search() && !is_404() ) ) {
		$spamshield_options		= get_option('spamshield_options');
		$wpss_key_values 		= spamshield_get_key_values();
		$wpss_ck_key  			= $wpss_key_values['wpss_ck_key'];
		$wpss_ck_val 			= $wpss_key_values['wpss_ck_val'];
		$wpss_js_key 			= $wpss_key_values['wpss_js_key'];
		$wpss_js_val 			= $wpss_key_values['wpss_js_val'];
		if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $WPContactValidationJS = $_COOKIE[$wpss_ck_key]; } else { $WPContactValidationJS = ''; }
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
		$promote_plugin_link				= $spamshield_options['promote_plugin_link'];
		$use_alt_cookie_method				= $spamshield_options['use_alt_cookie_method'];
		$use_alt_cookie_method_only			= $spamshield_options['use_alt_cookie_method_only'];
			
		if ( $FormMessageWidth < 40 ) { $FormMessageWidth = 40; }
		if ( $FormMessageHeight < 5 ) { $FormMessageHeight = 5; } elseif ( empty( $FormMessageHeight ) ) { $FormMessageHeight = 10; }
		if ( $FormMessageMinLength < 15 ) { $FormMessageMinLength = 15; } elseif ( empty( $FormMessageMinLength ) ) { $FormMessageMinLength = 25; }
		if ( $get_form == 'response' ) {
			$message_spam 	= 0;
			$blank_field 	= 0;
			$invalid_value 	= 0;
			$bad_email 		= 0;
			$bad_phone 		= 0;
			$message_short 	= 0;
			$js_cookie_fail = 0;
			
			// PROCESSING CONTACT FORM - BEGIN
			if ( !empty( $_POST['wpss_contact_name'] ) ) {
				$wpss_contact_name 				= sanitize_text_field($_POST['wpss_contact_name']);
				} else { $wpss_contact_name 	= ''; }
			if ( !empty( $_POST['wpss_contact_email'] ) ) {
				$wpss_contact_email 			= sanitize_email($_POST['wpss_contact_email']);
				} else { $wpss_contact_email 	= ''; }
			$wpss_contact_email_lc 				= strtolower( $wpss_contact_email );
			if ( !empty( $_POST['wpss_contact_website'] ) ) {
				$wpss_contact_website 			= esc_url_raw($_POST['wpss_contact_website']);
				} else { $wpss_contact_website 	= ''; }
			$wpss_contact_website_lc 			= strtolower( $wpss_contact_website );
			$wpss_contact_domain 				= spamshield_get_domain( $wpss_contact_website_lc );
			if ( !empty( $_POST['wpss_contact_phone'] ) ) {
				$wpss_contact_phone 			= sanitize_text_field($_POST['wpss_contact_phone']);
				} else { $wpss_contact_phone 	= ''; }
			if ( !empty( $_POST['wpss_contact_company'] ) ) {
				$wpss_contact_company 			= sanitize_text_field($_POST['wpss_contact_company']);
				} else { $wpss_contact_company 	= ''; }
			if ( !empty( $_POST['wpss_contact_drop_down_menu'] ) ) {
				$wpss_contact_drop_down_menu	= sanitize_text_field($_POST['wpss_contact_drop_down_menu']);
				} else { $wpss_contact_drop_down_menu = ''; }
			if ( !empty( $_POST['wpss_contact_subject'] ) ) {
				$wpss_contact_subject 			= sanitize_text_field($_POST['wpss_contact_subject']);
				} else { $wpss_contact_subject 	= ''; }
			if ( !empty( $_POST['wpss_contact_message'] ) ) {
				$wpss_contact_message 			= sanitize_text_field($_POST['wpss_contact_message']);
				} else { $wpss_contact_message 	= ''; }
			$wpss_contact_message_lc 		= strtolower($wpss_contact_message);
			
			// Update Session Vars
			$key_comment_auth 				= 'comment_author_'.WPSS_HASH;
			$key_comment_email				= 'comment_author_email_'.WPSS_HASH;
			$key_comment_url				= 'comment_author_url_'.WPSS_HASH;
			$_SESSION[$key_comment_auth] 	= $wpss_contact_name;
			$_SESSION[$key_comment_email]	= $wpss_contact_email_lc;
			$_SESSION[$key_comment_url] 	= $wpss_contact_website_lc;
			
			/*
			$wpss_contact_cc 				= trim(stripslashes(strip_tags($_POST['wpss_contact_cc'])));
			*/
		
			// Add New Tests for Logging - BEGIN
			if( !empty( $post_ref2xjs ) ) {
				$ref2xJS = strtolower( addslashes( urldecode( $post_ref2xjs ) ) );
				$ref2xJS = str_replace( '%3a', ':', $ref2xJS );
				$ref2xJS = str_replace( ' ', '+', $ref2xJS );
				$wpss_javascript_page_referrer = esc_url_raw( $ref2xJS );
				}
			else { $wpss_javascript_page_referrer = '[None]'; }
		
			if( $post_jsonst == 'NS2' ) { $wpss_jsonst = $post_jsonst; } else { $wpss_jsonst = '[None]'; }
			
			$commentdata['javascript_page_referrer']	= $wpss_javascript_page_referrer;
			//$commentdata['php_page_referrer']			= $wpss_php_page_referrer;
			$commentdata['jsonst']						= $wpss_jsonst;
			// Add New Tests for Logging - END

			// PROCESSING CONTACT FORM - END

			//if ( empty( $wpss_contact_cc ) ) { $wpss_contact_cc ='No'; }

			// FORM INFO - BEGIN

			if ( !empty( $FormMessageRecipient ) ) {
				$wpss_contact_form_to			= $FormMessageRecipient;
				}
			else {
				$wpss_contact_form_to 			= get_option('admin_email');
				}
			//$wpss_contact_form_cc_to 			= $wpss_contact_email;
			$wpss_contact_form_to_name 			= $wpss_contact_form_to;
			//$wpss_contact_form_cc_to_name 	= $wpss_contact_name;
			$wpss_contact_form_subject 			= '[Website Contact] '.$wpss_contact_subject;
			//$wpss_contact_form_cc_subject		= '[Website Contact CC] '.$wpss_contact_subject;
			$wpss_contact_form_msg_headers 		= "From: $wpss_contact_name <$wpss_contact_email>" . "\r\n" . "Reply-To: $wpss_contact_email" . "\r\n" . "Content-Type: text/plain\r\n";
			$wpss_contact_form_blog				= WPSS_SITE_URL;
			// Another option: "Content-Type: text/html"

			// FORM INFO - END

			// TEST TO PREVENT CONTACT FORM SPAM - BEGIN
			
			if ( $WPContactValidationJS != $wpss_ck_val ) { // Check for Cookie
				$js_cookie_fail=1;
				$spamshield_error_code .= ' CF-COOKIEFAIL';
				}
				
			// ERROR CHECKING
			$contact_form_blacklist_status = 0;
			$contact_response_status_message_addendum = '';
			
			$contact_form_spam_1_count = spamshield_substr_count( $wpss_contact_message_lc, 'link');
			$contact_form_spam_1_limit = 7;
			$contact_form_spam_2_count = spamshield_substr_count( $wpss_contact_message_lc, 'link building');
			$contact_form_spam_2_limit = 3;
			$contact_form_spam_3_count = spamshield_substr_count( $wpss_contact_message_lc, 'link exchange');
			$contact_form_spam_3_limit = 2;
			$contact_form_spam_4_count = spamshield_substr_count( $wpss_contact_message_lc, 'link request');
			$contact_form_spam_4_limit = 1;
			$contact_form_spam_5_count = spamshield_substr_count( $wpss_contact_message_lc, 'link building service');
			$contact_form_spam_5_limit = 2;
			$contact_form_spam_6_count = spamshield_substr_count( $wpss_contact_message_lc, 'link building experts india');
			$contact_form_spam_6_limit = 0;
			$contact_form_spam_7_count = spamshield_substr_count( $wpss_contact_message_lc, 'india');
			$contact_form_spam_7_limit = 1;
			$contact_form_spam_8_count = spamshield_substr_count( $wpss_contact_message_lc, 'can you outsource some seo business to us? we will work according to you and your clients and for a long term relationship we can start our SEO services in only $99 per month per website. looking forward for your positive reply');
			$contact_form_spam_8_limit = 0;
			$contact_form_spam_9_count = spamshield_substr_count( $wpss_contact_message_lc, 'can you outsource some seo business to us');
			$contact_form_spam_9_limit = 0;
			$contact_form_spam_10_count = spamshield_substr_count( $wpss_contact_message_lc, 'outsource some seo business');
			$contact_form_spam_10_limit = 0;
			$contact_form_spam_11_count = spamshield_substr_count( $wpss_contact_message_lc, 'hit4hit.org');
			$contact_form_spam_11_limit = 1;
			$contact_form_spam_12_count = spamshield_substr_count( $wpss_contact_message_lc, 'traffic exchange');
			$contact_form_spam_12_limit = 1;
			
			$wpss_contact_subject_lc = strtolower( $wpss_contact_subject );
			// Check if Subject seems spammy
			$subject_blacklisted_count = 0;
			$contact_form_spam_subj_arr = array(
				'link request', 'link exchange', 'seo service $99 per month', 'seo services $99 per month', 'seo services @ $99 per month', 'partnership with offshore development center',
				);
			$contact_form_spam_subj_arr_regex = spamshield_get_regex_phrase( $contact_form_spam_subj_arr,'','red_str' );
			if ( preg_match( $contact_form_spam_subj_arr_regex, $wpss_contact_subject_lc ) ) { $subject_blacklisted = true; $subject_blacklisted_count = 1; } else { $subject_blacklisted = false; }
			
			// Check if email is blacklisted
			if ( spamshield_email_blacklist_chk( $wpss_contact_email_lc ) ) { $email_blacklisted = true; } else { $email_blacklisted = false; }
			// Check if domain is blacklisted
			if ( spamshield_domain_blacklist_chk( $wpss_contact_domain ) ) { $domain_blacklisted = true; } else { $domain_blacklisted = false; }
			
			$contact_form_spam_term_total = $contact_form_spam_1_count + $contact_form_spam_2_count + $contact_form_spam_3_count + $contact_form_spam_4_count + $contact_form_spam_7_count + $contact_form_spam_10_count + $contact_form_spam_11_count + $contact_form_spam_12_count + $subject_blacklisted_count;
			$contact_form_spam_term_total_limit = 15;
			
			if ( strpos( $reverse_dns_lc_rev, 'ni.' ) === 0 || strpos( $reverse_dns_lc_rev, 'ur.' ) === 0 || strpos( $reverse_dns_lc_rev, 'kp.' ) === 0 || strpos( $reverse_dns_lc_rev, 'nc.' ) === 0 || strpos( $reverse_dns_lc_rev, 'au.' ) === 0 || strpos( $reverse_dns_lc_rev, 'rt.' ) === 0 || preg_match( "~^1\.22\.2(19|20|23)\.~", $ip ) ) {
				$contact_form_spam_loc = 1;
				}
			if ( ( $contact_form_spam_term_total > $contact_form_spam_term_total_limit || $contact_form_spam_1_count > $contact_form_spam_1_limit || $contact_form_spam_2_count > $contact_form_spam_2_limit || $contact_form_spam_5_count > $contact_form_spam_5_limit || $contact_form_spam_6_count > $contact_form_spam_6_limit || $contact_form_spam_10_count > $contact_form_spam_10_limit ) && !empty( $contact_form_spam_loc ) ) {
				$message_spam=1;
				$spamshield_error_code .= ' CF-MSG-SPAM1';
				$contact_response_status_message_addendum .= '&bull; Message appears to be spam. Please note that link requests, link exchange requests, and SEO outsourcing requests will be automatically deleted, and are not an acceptable use of this contact form.<br />&nbsp;<br />';
				}
			elseif ( !empty( $subject_blacklisted ) || $contact_form_spam_8_count > $contact_form_spam_8_limit || $contact_form_spam_9_count > $contact_form_spam_9_limit || $contact_form_spam_11_count > $contact_form_spam_11_limit || $contact_form_spam_12_count > $contact_form_spam_12_limit || !empty( $email_blacklisted ) || !empty( $domain_blacklisted ) ) {
				$message_spam=1;
				$spamshield_error_code .= ' CF-MSG-SPAM2';
				$contact_response_status_message_addendum .= '&bull; Message appears to be spam. Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.<br />&nbsp;<br />';
				}
			// JSONST & Referrer Scrape Test
			else {
				if ( $post_jsonst == 'NS2' ) {
					$message_spam=1;
					$spamshield_error_code .= ' CF-JSONST-1000';
					}
				if ( !empty( $post_ref2xjs ) && strpos( $post_ref2xjs_lc, 'ref2xjs' ) !== false ) {
					$message_spam=1;
					$spamshield_error_code .= ' CF-REF-2-1023';
					}
				if ( $message_spam == 1 ) {
					$contact_response_status_message_addendum .= '&bull; Message appears to be spam. Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.<br />&nbsp;<br />';
					}
				}
				
			if ( empty( $wpss_contact_name ) || empty( $wpss_contact_email ) || empty( $wpss_contact_subject ) || empty( $wpss_contact_message ) || ( !empty( $FormIncludeWebsite ) && !empty( $FormRequireWebsite ) && empty( $wpss_contact_website ) ) || ( !empty( $FormIncludePhone ) && !empty( $FormRequirePhone ) && empty( $wpss_contact_phone ) ) || ( !empty( $FormIncludeCompany ) && !empty( $FormRequireCompany ) && empty( $wpss_contact_company ) ) || ( !empty( $FormIncludeDropDownMenu ) && !empty( $FormRequireDropDownMenu ) && empty( $wpss_contact_drop_down_menu ) ) ) {
				$blank_field=1;
				$spamshield_error_code .= ' CF-BLANKFIELD';
				$contact_response_status_message_addendum .= '&bull; At least one required field was left blank.<br />&nbsp;<br />';
				}

			//if ( !is_email($wpss_contact_email) || spamshield_email_blacklist_chk($wpss_contact_email) ) {
			if ( !is_email($wpss_contact_email) ) {
				$invalid_value=1;
				$bad_email=1;
				$spamshield_error_code .= ' CF-INVAL-EMAIL';
				$contact_response_status_message_addendum .= '&bull; Please enter a valid email address.<br />&nbsp;<br />';
				}
			
			$wpss_contact_phone_zerofake1 = str_replace( '000-000-0000', '', $wpss_contact_phone );
			$wpss_contact_phone_zerofake2 = str_replace( '(000) 000-0000', '', $wpss_contact_phone );
			$wpss_contact_phone_zero = str_replace( '0', '', $wpss_contact_phone );
			$wpss_contact_phone_na1 = str_replace( 'N/A', '', $wpss_contact_phone );
			$wpss_contact_phone_na2 = str_replace( 'NA', '', $wpss_contact_phone );
			if ( !empty( $FormIncludePhone ) && !empty( $FormRequirePhone ) && ( empty( $wpss_contact_phone_zerofake1 ) || empty( $wpss_contact_phone_zerofake2 ) || empty( $wpss_contact_phone_zero ) || empty( $wpss_contact_phone_na1 ) || empty( $wpss_contact_phone_na2 ) ) ) {
				$invalid_value=1;
				$bad_phone=1;
				$spamshield_error_code .= ' CF-INVAL-PHONE';
				$contact_response_status_message_addendum .= '&bull; Please enter a valid phone number.<br />&nbsp;<br />';
				}
				
			$MessageLength = spamshield_strlen($wpss_contact_message);
			if ( $MessageLength < $FormMessageMinLength ) {
				$message_short=1;
				$spamshield_error_code .= ' CF-MSG-SHORT';
				$contact_response_status_message_addendum .= '&bull; Message too short. Please enter a complete message.<br />&nbsp;<br />';
				}
			
			// Test Reverse DNS Hosts - Do all with Reverse DNS not Remote Host
			// Added in 1.1.7.1
			$rev_dns_filter_data = spamshield_revdns_filter( 'contact', $contact_form_blacklist_status, $ip, $reverse_dns_lc, '', '' );
			$revdns_blacklisted 				 = $rev_dns_filter_data['blacklisted'];
			if ( !empty( $revdns_blacklisted ) ) {
				$server_blacklisted = true;
				$spamshield_error_code 			.= $rev_dns_filter_data['error_code'];
				$contact_form_blacklist_status 	 = $rev_dns_filter_data['status'];
				$contact_response_status_message_addendum = '&bull;  Message appears to be spam. Please note that link requests, link exchange requests, SEO outsourcing/offshoring spam, and automated contact form submissions will be automatically deleted, and are not an acceptable use of this contact form.<br />&nbsp;<br />';
				}
			else { $server_blacklisted = false; }
				
			//Sanitize the rest
			if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
				$wpss_contact_form_http_accept_language	= sanitize_text_field($_SERVER['HTTP_ACCEPT_LANGUAGE']);
				} else { $wpss_contact_form_http_accept_language = ''; }
			if ( !empty( $_SERVER['HTTP_ACCEPT'] ) ) {
				$wpss_contact_form_http_accept 			= sanitize_text_field($_SERVER['HTTP_ACCEPT']);
				} else { $wpss_contact_form_http_accept = ''; }
			if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
				$wpss_contact_form_http_user_agent 		= sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
				} else { $wpss_contact_form_http_user_agent = ''; }
			if ( !empty( $_SERVER['HTTP_REFERER'] ) ) {
				$wpss_contact_form_http_referer 		= esc_url_raw($_SERVER['HTTP_REFERER']);
				} else { $wpss_contact_form_http_referer = ''; }

			// MESSAGE CONTENT - BEGIN
			$wpss_contact_form_msg_1 = '';
			$wpss_contact_form_msg_2 = '';
			$wpss_contact_form_msg_3 = '';
			
			$wpss_contact_form_msg_1 .= "Message: "."\n";
			$wpss_contact_form_msg_1 .= $wpss_contact_message."\n";
			
			$wpss_contact_form_msg_1 .= "\n";
		
			$wpss_contact_form_msg_1 .= "Name: 			".$wpss_contact_name."\n";
			$wpss_contact_form_msg_1 .= "Email: 			".$wpss_contact_email_lc."\n";
			if ( !empty( $FormIncludePhone ) ) {
				$wpss_contact_form_msg_1 .= "Phone: 			".$wpss_contact_phone."\n";
				}
			if ( !empty( $FormIncludeCompany ) ) {
				$wpss_contact_form_msg_1 .= "Company: 		".$wpss_contact_company."\n";
				}
			if ( !empty( $FormIncludeWebsite ) ) {
				$wpss_contact_form_msg_1 .= "Website: 		".$wpss_contact_website_lc."\n";
				}
			if ( !empty( $FormIncludeDropDownMenu ) ) {
				$wpss_contact_form_msg_1 .= $FormDropDownMenuTitle.":	".$wpss_contact_drop_down_menu."\n";
				}
			
			$wpss_contact_form_msg_2 .= "\n";
			//Check following variables to make sure not repeating
			if ( !empty( $FormIncludeUserMeta ) ) {
				$wpss_contact_form_msg_2 .= "\n";
				$wpss_contact_form_msg_2 .= "Website Generating This Email:	".$wpss_contact_form_blog."\n";
				$wpss_contact_form_msg_2 .= "Referrer: 			".$wpss_contact_form_http_referer."\n";
				$wpss_contact_form_msg_2 .= "User-Agent (Browser/OS):	".$wpss_contact_form_http_user_agent."\n";
				$wpss_contact_form_msg_2 .= "IP Address: 		".$ip."\n";
				$wpss_contact_form_msg_2 .= "Server: 		".$reverse_dns."\n";
				$wpss_contact_form_msg_2 .= "IP Address Lookup:	http://whatismyipaddress.com/ip/".$ip."\n";
				// DEBUG ONLY - BEGIN
				if ( empty( $FormIncludeUserMetaHideExtData ) && strpos( WPSS_SERVER_NAME_REV, WPSS_DEBUG_SERVER_NAME_REV ) === 0 ) {
					$wpss_contact_form_msg_2 .= "------------------------------------------------------\n";
					$wpss_contact_form_msg_2 .= ":: Additional Technical Data Added by WP-SpamShield ::\n";
					$wpss_contact_form_msg_2 .= "------------------------------------------------------\n";
					$wpss_contact_form_msg_2 .= "JS Page Referrer Check:	".$wpss_javascript_page_referrer."\n";
					$wpss_contact_form_msg_2 .= "JSONST: 		".$wpss_jsonst."\n";
					$wpss_contact_form_msg_2 .= "Reverse DNS IP: 	".$reverse_dns_ip."\n";
					if ( !empty( $_SERVER['HTTP_VIA'] ) ) { $wpss_contact_form_http_via = trim($_SERVER['HTTP_VIA']); } else { $wpss_contact_form_http_via = ''; }
					if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { 
						$wpss_contact_form_http_x_forwarded_for = trim($_SERVER['HTTP_X_FORWARDED_FOR']); 
						} 
					else { 
						$wpss_contact_form_http_x_forwarded_for = ''; 
						}
					if ( empty( $wpss_contact_form_http_via ) ) {
						$wpss_contact_form_http_via = '[None]';
						}
					$wpss_contact_form_msg_2 .= "HTTP_VIA: 		".$wpss_contact_form_http_via."\n";
					if ( empty( $wpss_contact_form_http_x_forwarded_for ) ) {
						$wpss_contact_form_http_x_forwarded_for = '[None]';
						}
					$wpss_contact_form_msg_2 .= "HTTP_X_FORWARDED_FOR: 	".$wpss_contact_form_http_x_forwarded_for."\n";
					$wpss_contact_form_msg_2 .= "HTTP_ACCEPT_LANGUAGE: 	".$_SERVER['HTTP_ACCEPT_LANGUAGE']."\n";
					$wpss_contact_form_msg_2 .= "HTTP_ACCEPT: 		".$_SERVER['HTTP_ACCEPT']."\n";
					}
				// DEBUG ONLY - END
				}

			$wpss_contact_form_msg_3 .= "\n";
			$wpss_contact_form_msg_3 .= "\n";
			
			$wpss_contact_form_msg = $wpss_contact_form_msg_1.$wpss_contact_form_msg_2.$wpss_contact_form_msg_3;
			$wpss_contact_form_msg_cc = $wpss_contact_form_msg_1.$wpss_contact_form_msg_3;
			// MESSAGE CONTENT - END

			$contact_form_author_data = array( 'comment_author' => $wpss_contact_name, 'comment_author_email' => $wpss_contact_email_lc, 'comment_author_url' => $wpss_contact_website_lc );
			if ( empty( $blank_field ) && empty( $invalid_value ) && empty( $message_short) && empty( $message_spam ) && empty( $js_cookie_fail ) ) {  
				// SEND MESSAGE
				@wp_mail( $wpss_contact_form_to, $wpss_contact_form_subject, $wpss_contact_form_msg, $wpss_contact_form_msg_headers );								
				$contact_response_status = 'thank-you';
				$spamshield_error_code = 'No Error';
				spamshield_update_sess_accept_status($contact_form_author_data,'a','Line: '.__LINE__);
				if ( !empty( $spamshield_options['comment_logging'] ) && !empty( $spamshield_options['comment_logging_all'] ) ) {
					spamshield_log_data( $commentdata, $spamshield_error_code, 'contact form', $wpss_contact_form_msg );
					}
				}
			else {
				update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
				spamshield_update_sess_accept_status($contact_form_author_data,'r','Line: '.__LINE__);
				$contact_response_status = 'error';
				if ( !empty( $spamshield_options['comment_logging'] ) ) {
					$spamshield_error_code = ltrim($spamshield_error_code);
					spamshield_log_data( $commentdata, $spamshield_error_code, 'contact form', $wpss_contact_form_msg );
					}
				}				
			
			// TEST TO PREVENT CONTACT FORM SPAM - END
			
			$FormResponseThankYouMessageDefault = '<p>Your message was sent successfully. Thank you.</p><p>&nbsp;</p>';
			$FormResponseThankYouMessage = str_replace( "\\", "", $FormResponseThankYouMessage );
		
			if ( $contact_response_status == 'thank-you' ) {
				if ( !empty( $FormResponseThankYouMessage ) ) {
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
				elseif ( strpos( $spamshield_contact_form_url_lc, '?form=response' ) !== false ) {
					$spamshield_contact_form_back_url = str_replace('?form=response','',$spamshield_contact_form_url );
					}
				if ( !empty( $message_spam ) ) {
					if ( empty( $use_alt_cookie_method ) && empty( $use_alt_cookie_method_only ) ) {
						$contact_response_status_message_addendum .= '<noscript><br />&nbsp;<br />&bull; Currently you have JavaScript disabled.</noscript>'."\n";
						}
					$spamshield_contact_form_content .= '<p><strong>ERROR: <br />&nbsp;<br />'.$contact_response_status_message_addendum.'</strong><p>&nbsp;</p>'."\n";
					}
				else {
					if ( empty( $use_alt_cookie_method ) && empty( $use_alt_cookie_method_only ) ) {
						$contact_response_status_message_addendum .= '<noscript><br />&nbsp;<br />&bull; Currently you have JavaScript disabled.</noscript>'."\n";
						}
					$spamshield_contact_form_content .= '<p><strong>ERROR: Please return to the <a href="'.$spamshield_contact_form_back_url.'" >contact form</a> and fill out all required fields.';
					if ( empty( $use_alt_cookie_method ) && empty( $use_alt_cookie_method_only ) ) {
						$spamshield_contact_form_content .= ' Please make sure JavaScript and Cookies are enabled in your browser.';
						}
					elseif ( !empty( $use_alt_cookie_method_only ) ) {
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
			if ( !empty( $_COOKIE['comment_author_'.WPSS_HASH] ) ) {
				// Can't use server side if caching is active - TO DO: AJAX
				$stored_author_data 	= spamshield_get_author_cookie_data();
				$stored_author 			= $stored_author_data['comment_author'];
				$stored_author_email	= $stored_author_data['comment_author_email'];
				$stored_author_url 		= $stored_author_data['comment_author_url'];				
				}
			$spamshield_contact_form_content .= '<form id="wpss_contact_form" name="wpss_contact_form" action="'.$spamshield_contact_form_url.$spamshield_contact_form_query_op.'form=response" method="post" style="text-align:left;" >'."\n";

			$spamshield_contact_form_content .= '<p><label><strong>Name</strong> *<br />'."\n";

			$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_name" name="wpss_contact_name" value="" size="40" /> </label></p>'."\n";
			$spamshield_contact_form_content .= '<p><label><strong>Email</strong> *<br />'."\n";
			$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_email" name="wpss_contact_email" value="" size="40" /> </label></p>'."\n";
			
			if ( !empty( $FormIncludeWebsite ) ) {
				$spamshield_contact_form_content .= '<p><label><strong>Website</strong> ';
				if ( !empty( $FormRequireWebsite ) ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_website" name="wpss_contact_website" value="" size="40" /> </label></p>'."\n";
				}
				
			if ( !empty( $FormIncludePhone ) ) {
				$spamshield_contact_form_content .= '<p><label><strong>Phone</strong> ';
				if ( !empty( $FormRequirePhone ) ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_phone" name="wpss_contact_phone" value="" size="40" /> </label></p>'."\n";
				}

			if ( !empty( $FormIncludeCompany ) ) {
				$spamshield_contact_form_content .= '<p><label><strong>Company</strong> ';
				if ( !empty( $FormRequireCompany ) ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_company" name="wpss_contact_company" value="" size="40" /> </label></p>'."\n";
				}

			if ( !empty( $FormIncludeDropDownMenu ) && !empty( $FormDropDownMenuTitle ) && !empty( $FormDropDownMenuItem1 ) && !empty( $FormDropDownMenuItem2 ) ) {
				$spamshield_contact_form_content .= '<p><label><strong>'.$FormDropDownMenuTitle.'</strong> ';
				if ( !empty( $FormRequireDropDownMenu ) ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<select id="wpss_contact_drop_down_menu" name="wpss_contact_drop_down_menu" > '."\n";
				$spamshield_contact_form_content .= '<option value="" selected="selected">Please Select</option> '."\n";
				$spamshield_contact_form_content .= '<option value="">--------------------------</option> '."\n";
				if ( !empty( $FormDropDownMenuItem1 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem1.'">'.$FormDropDownMenuItem1.'</option> '."\n";
					}
				if ( !empty( $FormDropDownMenuItem2 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem2.'">'.$FormDropDownMenuItem2.'</option> '."\n";
					}
				if ( !empty( $FormDropDownMenuItem3 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem3.'">'.$FormDropDownMenuItem3.'</option> '."\n";
					}
				if ( !empty( $FormDropDownMenuItem4 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem4.'">'.$FormDropDownMenuItem4.'</option> '."\n";
					}
				if ( !empty( $FormDropDownMenuItem5 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem5.'">'.$FormDropDownMenuItem5.'</option> '."\n";
					}
				if ( !empty( $FormDropDownMenuItem6 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem6.'">'.$FormDropDownMenuItem6.'</option> '."\n";
					}
				if ( !empty( $FormDropDownMenuItem7 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem7.'">'.$FormDropDownMenuItem7.'</option> '."\n";
					}
				if ( !empty( $FormDropDownMenuItem8 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem8.'">'.$FormDropDownMenuItem8.'</option> '."\n";
					}
				if ( !empty( $FormDropDownMenuItem9 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$FormDropDownMenuItem9.'">'.$FormDropDownMenuItem9.'</option> '."\n";
					}
				if ( !empty( $FormDropDownMenuItem10 ) ) {
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
			
			if ( empty( $use_alt_cookie_method ) && empty( $use_alt_cookie_method_only ) ) {
				$spamshield_contact_form_content .= '<noscript><p><strong>Currently you have JavaScript disabled. In order to use this contact form, please make sure JavaScript and Cookies are enabled, and reload the page.</strong> <a href="http://enable-javascript.com/" rel="nofollow external" >Click here for instructions</a> on how to enable JavaScript in your browser.</p></noscript>'."\n";		
				}

			$spamshield_contact_form_content .= '<p><input type="submit" id="wpss_contact_submit" name="wpss_contact_submit" value="Send Message" /></p>'."\n";

			$spamshield_contact_form_content .= '<p>* Required Field</p>'."\n";
			$spamshield_contact_form_content .= '<p>&nbsp;</p>'."\n";

			if ( !empty( $promote_plugin_link ) ) {
				$server_ip_first_char = substr(WPSS_SERVER_ADDR, 0, 1);
				if ( $server_ip_first_char == '7' ) {
					$spamshield_contact_form_content .= '<p style="font-size:9px;clear:both;"><a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield Contact Form for WordPress" >Contact Form</a> Powered by WP-SpamShield</p>'."\n";
					}
				elseif ( $server_ip_first_char == '6' ) {
					$spamshield_contact_form_content .= '<p style="font-size:9px;clear:both;">Powered by <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield Contact Form for WordPress" >WP-SpamShield Contact Form</a></p>'."\n";
					}
				else {
					$spamshield_contact_form_content .= '<p style="font-size:9px;clear:both;">Contact Form Powered by <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" title="WP-SpamShield Contact Form for WordPress" >WP-SpamShield</a></p>'."\n";
					}
				$spamshield_contact_form_content .= '<p>&nbsp;</p>'."\n";
				}
			$spamshield_contact_form_content .= '</form>'."\n";
			
			if ( ($WPContactValidationJS != $wpss_ck_val && !empty( $use_alt_cookie_method ) ) || !empty( $use_alt_cookie_method_only ) ) {
				if ( strpos( $user_agent_lc, 'opera' ) !== false ) { 
					$wpss_img_p_disp = ' style="clear:both;display:none;"';
					$wpss_img_disp = 'display:none;';
					}
				else { 
					$wpss_img_p_disp = ' style="clear:both;"';
					$wpss_img_disp = ''; 
					}	
				update_option( 'ak_count_pre', get_option('akismet_spam_count') );

				$spamshield_contact_form_content .=  '<span'.$wpss_img_p_disp.'><img src="'.WPSS_PLUGIN_IMG_URL.'/img.php" width="0" height="0" alt="" style="border-style:none;width:0px;height:0px;'.$wpss_img_disp.'" /></span>'; // DEPRECATED
				}	
			
			$contact_form_blacklist_status = '';
			$spamshield_contact_form_ip_bans = array(
				'66.60.98.1', '67.227.135.200', '74.86.148.194', '77.92.88.13', '77.92.88.27', '78.129.202.15', '78.129.202.2',	'78.157.143.202', '87.106.55.101', '91.121.77.168',
				'92.241.176.200', '92.48.122.2', '92.48.122.3', '92.48.65.27', '92.241.168.216', '115.42.64.19', '116.71.33.252', '116.71.35.192', '116.71.59.69', '122.160.70.94',
				'122.162.251.167', '123.237.144.189', '123.237.144.92', '123.237.147.71', '193.37.152.242', '193.46.236.151', '193.46.236.152', '193.46.236.234', '194.44.97.14',
				);
			// Check following variables to make sure not repeating										
			$commentdata_remote_addr_lc = strtolower($_SERVER['REMOTE_ADDR']);
			$commentdata_remote_addr_lc_rev = strrev($commentdata_remote_addr_lc);
			$commentdata_remote_host_lc = strtolower($reverse_dns);
			$commentdata_remote_host_lc_rev = strrev($commentdata_remote_host_lc);
			if ( in_array( $commentdata_remote_addr_lc, $spamshield_contact_form_ip_bans ) || strpos( $commentdata_remote_addr_lc, '78.129.202.' ) === 0 || preg_match( "~^123\.237\.14([47])\.~", $commentdata_remote_addr_lc ) || preg_match( "~^194\.8\.7([45])\.~", $commentdata_remote_addr_lc ) || strpos( $commentdata_remote_addr_lc, '193.37.152.' ) === 0 || strpos( $commentdata_remote_addr_lc, '193.46.236.' ) === 0 || preg_match( "~^92\.48\.122\.([0-9]|[12][0-9]|3[01])$~", $commentdata_remote_addr_lc ) || strpos( $commentdata_remote_addr_lc, '116.71.' ) === 0 || ( strpos( $commentdata_remote_addr_lc, '192.168.' ) === 0 && strpos( WPSS_SERVER_ADDR, '192.168.' ) !== 0 && strpos( WPSS_SERVER_NAME, 'localhost' ) === false ) ) {
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
			// Test Reverse DNS Hosts - Do all with Reverse DNS not Remote Host
			$rev_dns_filter_data = spamshield_revdns_filter( 'contact', $contact_form_blacklist_status, $ip, $reverse_dns_lc, '', '' );
			$contact_form_blacklist_status 	 = $rev_dns_filter_data['status'];
			$spamshield_error_code 			.= $rev_dns_filter_data['error_code'];
			$revdns_blacklisted 			 = $rev_dns_filter_data['blacklisted'];
			
			// UA Tests
			if ( empty( $user_agent_lc ) ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-UA1001-0';
				}
			if ( !empty( $user_agent_lc ) && $user_agent_lc_word_count < 3 ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-UA1001-1';
				}
			if ( strpos( $user_agent_lc, 'libwww' ) !== false || preg_match( "~^(nutch|larbin|jakarta|java|mechanize|phpcrawl)~i", $user_agent_lc ) ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-UA1002';
				}
			if ( strpos( $user_agent_lc, 'iopus-' ) !== false ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-UA1003';
				}
			if ( empty( $user_http_accept_lc ) ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-HA1001';
				}
			if ( $user_http_accept_lc == 'application/json, text/javascript, */*; q=0.01' ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-HA1002';
				}
			if ( $user_http_accept_lc == '*' ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-HA1003';
				}
			if ( empty( $user_http_accept_language_lc ) ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-HAL1001';
				}
			if ( $user_http_accept_language_lc == '*' ) {
				$contact_form_blacklist_status = '2';
				$spamshield_error_code .= ' CF-HAL1002';
				}

			// Add blacklist check - IP's only though.
			$wpss_cache_check 			= spamshield_check_cache_status();
			$wpss_cache_check_status	= $wpss_cache_check['cache_check_status'];

			if ( !empty( $contact_form_blacklist_status ) && $wpss_cache_check_status != 'ACTIVE' ) {
				$spamshield_contact_form_content = '<strong>Your location has been identified as part of a reported spam network. Contact form has been disabled to prevent spam.</strong>';
				}
			$content_new = str_replace($spamshield_contact_repl_text, $spamshield_contact_form_content, $content);
			$content_shortcode = $spamshield_contact_form_content;
			}

		}
	if ( $get_form == 'response' ) {
		$content_new = str_replace($content, $spamshield_contact_form_content, $content);
		$content_shortcode = $spamshield_contact_form_content;
		}
	else {
		$content_new = str_replace($spamshield_contact_repl_text, $spamshield_contact_form_content, $content);
		$content_shortcode = $spamshield_contact_form_content;
		}
	if ( $shortcode_check == 'shortcode' && !empty( $content_shortcode ) ) {
		$content_new = $content_shortcode;
		}
	return $content_new;
	}

// BLACKLISTS - BEGIN

function spamshield_email_blacklist_chk( $email = NULL, $get_eml_list_arr = false, $get_pref_list_arr = false, $get_str_list_arr = false, $get_str_rgx_list_arr = false ) {
	// Email Blacklist Check
	$blacklisted_emails = array(
		// The whole email address
		"12345@yahoo.com", "a@a.com", "asdf@yahoo.com", "fuck@you.com", "test@test.com", 
		);
	if ( !empty( $get_eml_list_arr ) ) { return $blacklisted_emails; }
	$blacklisted_email_prefixes = array(
		// The beginning part of the email
		"anonymous@", "fuckyou@", "root@", "spam@", "spambot@", "spammer@",
		);
	if ( !empty( $get_pref_list_arr ) ) { return $blacklisted_email_prefixes; }

	$blacklisted_email_strings = array(
		// Redflagged strings that occur anywhere in the email address
		".seo@gmail.com",
		);
	if ( !empty( $get_str_list_arr ) ) { return $blacklisted_email_strings; }
	
	$blacklisted_email_strings_rgx = array(
		// Custom regex strings that occur in the email address
		"spinfilel?namesdat",
		);
	if ( !empty( $get_str_rgx_list_arr ) ) { return $blacklisted_email_strings_rgx; }
	
	// Goes after all arrays
	$blacklist_status = false;
	if ( empty( $email ) ) { return false; }
	$blacklisted_domains = spamshield_domain_blacklist_chk('',true);

	$regex_phrase_arr = array();
	$regex_phrase_arr[] = spamshield_get_regex_phrase($blacklisted_emails,'','email_addr');
	$regex_phrase_arr[] = spamshield_get_regex_phrase($blacklisted_email_prefixes,'','email_prefix');
	$regex_phrase_arr[] = spamshield_get_regex_phrase($blacklisted_email_strings,'','red_str');
	$regex_phrase_arr[] = spamshield_get_regex_phrase($blacklisted_email_strings_rgx,'','rgx_str');
	$regex_phrase_arr[] = spamshield_get_regex_phrase($blacklisted_domains,'','email_domain');
	
	foreach( $regex_phrase_arr as $i => $regex_phrase ) {
		if ( preg_match( $regex_phrase, $email ) ) { $blacklist_status = true; }
		//spamshield_append_log_data( "\n".'$regex_phrase:'.$regex_phrase.' Line: '.__LINE__ );
		}
	
	return $blacklist_status;
	}

function spamshield_domain_blacklist_chk( $domain = NULL, $get_list_arr = false ) {
	// Domain Blacklist Check
	$blacklisted_domains = array(
		// THE Master List - Documented spammers - 10 per line
		"agenciade.serviciosdeseo.com", "fat-milf.com", "fuckyou.com", "globaldata4u.com", "hhmla.ca", "hit4hit.org", "keywordspy.com", "manektech.com", "ranksindia.net", "ranksdigitalmedia.com",
		"rizecorp.com", "rizedigital.com", "semmiami.com", "seoindia.co.in", "serviciosdeseo.com", "webdesigncompany.org", "webpromotioner.com",
		// Add more here
		);
	if ( !empty( $get_list_arr ) ) { return $blacklisted_domains; }
	// Goes after array
	$blacklist_status = false;
	if ( empty( $domain ) ) { return false; }
	$regex_phrase = spamshield_get_regex_phrase($blacklisted_domains,'','domain');
	//spamshield_append_log_data( "\n".'$regex_phrase:'.$regex_phrase.' Line: '.__LINE__ );
	if ( preg_match( $regex_phrase, $domain ) ) {
		$blacklist_status = true;
		}
	return $blacklist_status;
	}

function spamshield_link_blacklist_chk( $haystack = NULL ) {
	// Link Blacklist Check
	// $haystack can be any body of content you want to search for links to blacklisted domains
	$blacklist_status = false;
	if ( empty( $haystack ) ) { return false; }
	$blacklisted_domains = spamshield_domain_blacklist_chk('',true);
	$regex_phrase = spamshield_get_regex_phrase($blacklisted_domains,'','linkwrap');
	//spamshield_append_log_data( "\n".'$regex_phrase:'.$regex_phrase.' Line: '.__LINE__ );
	if ( preg_match( $regex_phrase, $haystack ) ) {
		$blacklist_status = true;
		}
	return $blacklist_status;
	}


function spamshield_author_keyword_blacklist_chk( $author = NULL, $get_list_arr = false ) {
	// Author Keyword Blacklist Check
	$blacklisted_author_keywords = array(
		// SEO
		"seo", "search engine optimization", "search engine marketing", "search marketing",
		// Web
		// Medical
		// Payday Loans
		// Misc
		
		// Add more here
		);
	if ( !empty( $get_list_arr ) ) { return $blacklisted_domains; }
	// Goes after array
	$blacklist_status = false;
	if ( empty( $author ) ) { return false; }
	$regex_phrase = spamshield_get_regex_phrase($blacklisted_author_keywords,'','N');
	//spamshield_append_log_data( "\n".'$regex_phrase:'.$regex_phrase.' Line: '.__LINE__ );
	if ( preg_match( $regex_phrase, $domain ) ) {
		$blacklist_status = true;
		}
	return $blacklist_status;
	}

///BLACKLISTS - END

function spamshield_check_comment_type($commentdata) {

	// Timer Start - Comment Processing
	$commentdata['start_time_comment_processing'] = spamshield_microtime();

	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);
	
	$spamshield_error_code 	= '';
	$wpss_js_key_test 		= '';

	// Add New Tests for Logging - BEGIN
	if ( !empty( $_POST['JSONST'] ) ) {
		$post_jsonst 	= $_POST['JSONST'];
		}
	else {
		$post_jsonst	= '';
		}
	if ( !empty( $_POST['ref2xJS'] ) ) {
		$post_ref2xjs 	= $_POST['ref2xJS'];
		}
	else {
		$post_ref2xjs	= '';
		}	
	$post_ref2xjs_lc 	= strtolower($post_ref2xjs);
	if( !empty( $post_ref2xjs ) ) {
		$ref2xJS = strtolower( addslashes( urldecode( $post_ref2xjs ) ) );
		$ref2xJS = str_replace( '%3a', ':', $ref2xJS );
		$ref2xJS = str_replace( ' ', '+', $ref2xJS );
		$wpss_javascript_page_referrer = esc_url_raw( $ref2xJS );
		}
	else {
		$wpss_javascript_page_referrer = '[None]';
		}

	if( $post_jsonst == 'NS1' ) {
		$wpss_jsonst = $post_jsonst;
		}
	else {
		$wpss_jsonst = '[None]';
		}

	$commentdata['comment_post_title']			= get_the_title($commentdata['comment_post_ID']);
	$commentdata['comment_post_url']			= get_permalink($commentdata['comment_post_ID']);
	$commentdata['comment_post_type']			= get_post_type($commentdata['comment_post_ID']);
	$commentdata['comment_post_comments_open']	= comments_open($commentdata['comment_post_ID']);
	$commentdata['comment_post_pings_open']		= pings_open($commentdata['comment_post_ID']);
	$commentdata['javascript_page_referrer']	= $wpss_javascript_page_referrer;
	//$commentdata['php_page_referrer']			= $wpss_php_page_referrer;
	$commentdata['jsonst']						= $wpss_jsonst;
	
	// Add New Tests for Logging - END
	
	if ( !is_admin() && !current_user_can('moderate_comments') ) {
		//spamshield_append_log_data( "\n".'current_user_can(): !current_user_can(\'moderate_comments\') Line: '.__LINE__ );
		// For Details on Roles: http://codex.wordpress.org/Roles_and_Capabilities

		// ONLY IF NOT ADMINS, EDITORS - BEGIN
		$BlockAllTrackbacks 		= $spamshield_options['block_all_trackbacks'];
		$BlockAllPingbacks 			= $spamshield_options['block_all_pingbacks'];
		
		// First, test if comment is too short
		//Rework - original was: $content_short_status		= spamshield_content_short($commentdata);
		$commentdata = spamshield_content_short($commentdata);
		$content_short_status = $commentdata['content_short_status'];
		
		if ( empty( $content_short_status ) ) {
			// If it doesn't fail the comment length test, run it through the content filter
			// This is where the magic happens...
			
			// Rework - original was: $content_filter_status = spamshield_content_filter($commentdata);
			$commentdata = spamshield_content_filter($commentdata);
			// Now we have a lot more power to work with
			$content_filter_status = $commentdata['content_filter_status'];
			}
		
		if ( !empty( $content_short_status ) ) {
			spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
			add_filter('pre_comment_approved', 'spamshield_denied_post_short', 1);
			}
		elseif ( $content_filter_status == '2' ) {
			spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
			add_filter('pre_comment_approved', 'spamshield_denied_post_content_filter', 1);
			}
		elseif ( $content_filter_status == '10' ) {
			spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
			add_filter('pre_comment_approved', 'spamshield_denied_post_proxy', 1);
			}
		elseif ( $content_filter_status == '100' ) {
			spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
			add_filter('pre_comment_approved', 'spamshield_denied_post_wp_blacklist', 1);
			}
		elseif ( !empty( $content_filter_status ) ) {
			spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
			add_filter('pre_comment_approved', 'spamshield_denied_post', 1);
			}	
		// Rework this
		elseif ( ( $commentdata['comment_type'] != 'trackback' && $commentdata['comment_type'] != 'pingback' ) || ( !empty( $BlockAllTrackbacks ) && !empty( $BlockAllPingbacks ) ) || ( !empty( $BlockAllTrackbacks ) && $commentdata['comment_type'] == 'trackback' ) || ( !empty( $BlockAllPingbacks ) && $commentdata['comment_type'] == 'pingback' ) ) {
			// If Comment is not a trackback or pingback, or 
			// Trackbacks and Pingbacks are blocked, or 
			// Trackbacks are blocked and comment is Trackback, or 
			// Pingbacks are blocked and comment is Pingback

			// Timer Start - JS/Cookies Filter
			// $wpss_start_time_jsc_filter = spamshield_microtime();
			// $commentdata['start_time_jsc_filter'] = $wpss_start_time_jsc_filter;
			
			add_filter('pre_comment_approved', 'spamshield_allowed_post', 1);
			
			// LOG DATA - BEGIN
			//if ( !empty( $spamshield_options['comment_logging'] ) ) {
			
			$wpss_key_values 	= spamshield_get_key_values();
			$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
			$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
			$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
			$wpss_js_val 		= $wpss_key_values['wpss_js_val'];
			
			if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wpss_comment_validation_js = $_COOKIE[$wpss_ck_key]; }
			else { $wpss_comment_validation_js = ''; }
			if ( !empty( $_POST[$wpss_js_key] ) ) {
				$wpss_form_validation_post 	= $_POST[$wpss_js_key]; //Comments Post Verification
				}
			else { $wpss_form_validation_post = ''; }
			$wpss_cache_check = spamshield_check_cache_status();
			$wpss_cache_check_status = $wpss_cache_check['cache_check_status'];
			if ( $wpss_form_validation_post == $wpss_js_val || $wpss_cache_check_status == 'ACTIVE' ) {
				$wpss_js_key_test = 'PASS';
				}
			if ( $commentdata['comment_type'] != 'trackback' && $commentdata['comment_type'] != 'pingback' && $wpss_comment_validation_js != $wpss_ck_val ) {
				// Failed the Cookie Test
				// Part of the JavaScript/Cookies Layer
				$spamshield_error_code .= ' COOKIE';
				}
			if ( $commentdata['comment_type'] != 'trackback' && $commentdata['comment_type'] != 'pingback' && $wpss_js_key_test != 'PASS' ) {
				// Failed the FVFJS Test
				// Part of the JavaScript/Cookies Layer
				$spamshield_error_code .= ' FVFJS';
				}
			if ( !empty( $BlockAllTrackbacks ) && $commentdata['comment_type'] == 'trackback' ) {
				$spamshield_error_code .= ' BLOCKING-TRACKBACKS ';
				}
			if ( !empty( $BlockAllPingbacks ) && $commentdata['comment_type'] == 'pingback' ) {
				$spamshield_error_code .= ' BLOCKING-PINGBACKS';
				}
			if ( empty( $spamshield_error_code ) ) {
				// Passed all tests
				$spamshield_error_code = 'No Error';
				spamshield_update_sess_accept_status($commentdata,'a','Line: '.__LINE__);
				}
			else {
				spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
				}
	
			if ( !empty( $spamshield_options['comment_logging'] ) ) {
			
				if ( !empty( $spamshield_options['comment_logging_all'] ) ) {
					// Timer End - JS/Cookies Filter
					// $wpss_end_time_jsc_filter = spamshield_microtime();
					// $commentdata['end_time_jsc_filter'] = $wpss_end_time_jsc_filter;

					$spamshield_error_code = ltrim($spamshield_error_code);
					spamshield_log_data( $commentdata, $spamshield_error_code );
					}
				
				}
			// LOG DATA - END
			}

		// ONLY IF NOT ADMINS, EDITORS - END
		}

	elseif ( !empty( $spamshield_options['comment_logging_all'] ) ) {
		$spamshield_error_code = 'No Error';
		spamshield_log_data( $commentdata, $spamshield_error_code );
		}

	return $commentdata;
	}

function spamshield_allowed_post( $approved = NULL ) {
	//if ( !empty( $_SESSION['commentdata_'.WPSS_HASH] ) ) { $commentdata = $_SESSION['commentdata_'.WPSS_HASH]; } else { $commentdata = ''; }
	// JavaScript and Cookies Layer
	// TEST TO PREVENT COMMENT SPAM FROM BOTS - BEGIN
	$spamshield_options	= get_option('spamshield_options');
	
	$wpss_key_values 	= spamshield_get_key_values();
	$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
	$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
	$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
	$wpss_js_val 		= $wpss_key_values['wpss_js_val'];

	$key_update_time 	= $spamshield_options['last_key_update'];
	if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wpss_comment_validation_js = $_COOKIE[$wpss_ck_key]; }
	else { $wpss_comment_validation_js = ''; }
	if ( !empty( $_POST[$wpss_js_key] ) ) {
		$wpss_form_validation_post 	= $_POST[$wpss_js_key]; //Comments Post Verification
		}
	else { $wpss_form_validation_post = ''; }
	// if( $wpss_comment_validation_js == $wpss_ck_val ) { // Comment allowed
	$wpss_cache_check = spamshield_check_cache_status();
	$wpss_cache_check_status = $wpss_cache_check['cache_check_status'];
	if ( $wpss_form_validation_post == $wpss_js_val || $wpss_cache_check_status == 'ACTIVE' ) {
		$wpss_js_key_test = 'PASS';
		}
	if( $wpss_comment_validation_js == $wpss_ck_val && $wpss_js_key_test == 'PASS' ) { // Comment allowed
		// Comment allowed
		return $approved;
		}
	else { // Comment denied - spam killed

		// Update Count
		update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
		spamshield_ak_accuracy_fix();
		//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
		if ( !empty( $_COOKIE['SJECT14'] ) ) {
			$spamshield_jsck_error_ck_test = $_COOKIE['SJECT14']; // Default value is 'CKON14'
			}
		else {
			$spamshield_jsck_error_ck_test = '';
			}
		if ( $spamshield_jsck_error_ck_test == 'CKON14' ) {
			$spamshield_jsck_error_ck_status = 'PHP detects that cookies appear to be enabled.';
			}
		else {
			$spamshield_jsck_error_ck_status = 'PHP detects that cookies appear to be disabled. <script type="text/javascript">if (navigator.cookieEnabled==true) { document.write(\'(However, JavaScript detects that cookies are enabled.)\'); } else { document.write(\'\(JavaScript also detects that cookies are disabled.\)\'); }; </script>';
			}
		
		$spamshield_jsck_error_message_standard = '<strong>ERROR:</strong> Sorry, there was an error. Please be sure JavaScript and Cookies are enabled in your browser and try again.';

		$spamshield_jsck_error_message_detailed = '<span style="font-size:12px;"><strong>ERROR: JavaScript and Cookies are required in order to post a comment.</strong><br /><br />'."\n";
		$spamshield_jsck_error_message_detailed .= '<noscript>Status: JavaScript is currently disabled.<br /><br /></noscript>'."\n";
		$spamshield_jsck_error_message_detailed .= '<strong>Please be sure JavaScript and Cookies are enabled in your browser. Then, please hit the back button on your browser, and try posting your comment again. (You may need to reload the page)</strong><br /><br />'."\n";
		$spamshield_jsck_error_message_detailed .= '<br /><hr noshade />'."\n";
		if ( $spamshield_jsck_error_ck_test == 'CKON14' ) {
			$spamshield_jsck_error_message_detailed .= 'If you feel you have received this message in error (for example <em>if JavaScript and Cookies are in fact enabled</em> and you have tried to post several times), there is most likely a technical problem (could be a plugin conflict or misconfiguration). Please contact the author of this site, and let them know they need to look into it.<br />'."\n";
			$spamshield_jsck_error_message_detailed .= '<hr noshade /><br />'."\n";
			}
		$spamshield_jsck_error_message_detailed .= '</span>'."\n";
	
		$spamshield_imgphpck_error_message_standard = '<strong>ERROR:</strong> Sorry, there was an error. Please enable Images and Cookies in your browser and try again.';
		
		$spamshield_imgphpck_error_message_detailed = '<span style="font-size:12px;"><strong>ERROR: Images and Cookies are required in order to post a comment.<br/>You appear to have at least one of these disabled.</strong><br /><br />'."\n";
		$spamshield_imgphpck_error_message_detailed .= '<strong>Please enable Images and Cookies in your browser. Then, please go back, reload the page, and try posting your comment again.</strong><br /><br />'."\n";
		$spamshield_imgphpck_error_message_detailed .= '<br /><hr noshade />'."\n";
		$spamshield_imgphpck_error_message_detailed .= 'If you feel you have received this message in error (for example <em>if Images and Cookies are in fact enabled</em> and you have tried to post several times), please alert the author of this site, and let them know they need to look into it.<br />'."\n";
		$spamshield_imgphpck_error_message_detailed .= '<hr noshade /><br /></span>'."\n";

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
	//if ( !empty( $_SESSION['commentdata_'.WPSS_HASH] ) ) { $commentdata = $_SESSION['commentdata_'.WPSS_HASH]; } else { $commentdata = ''; }
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);

	$spamshield_filter_error_message_standard = '<span style="font-size:12px;"><strong>ERROR:</strong> Comments have been temporarily disabled to prevent spam. Please try again later.</span>'; // Stop spammers without revealing why.
	
	$spamshield_filter_error_message_detailed = '<span style="font-size:12px;"><strong>ERROR: Your comment seems a bit spammy. We\'re not real big on spam here.</strong><br /><br />'."\n";
	$spamshield_filter_error_message_detailed .= 'Please go back and try to say something useful.</span>'."\n";

	wp_die( __($spamshield_filter_error_message_detailed) );
	return false;
	// REJECT SPAM - END
	}

function spamshield_denied_post_short($approved) {
	// REJECT SHORT COMMENTS - BEGIN
	//if ( !empty( $_SESSION['commentdata_'.WPSS_HASH] ) ) { $commentdata = $_SESSION['commentdata_'.WPSS_HASH]; } else { $commentdata = ''; }
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);

	wp_die( __('<span style="font-size:12px;"><strong>ERROR:</strong> Your comment was too short. Please try to say something useful.</span>') );
	return false;
	// REJECT SHORT COMMENTS - END
	}
	
function spamshield_denied_post_content_filter($approved) {
	// REJECT BASED ON CONTENT FILTER - BEGIN
	//if ( !empty( $_SESSION['commentdata_'.WPSS_HASH] ) ) { $commentdata = $_SESSION['commentdata_'.WPSS_HASH]; } else { $commentdata = ''; }
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);

	$spamshield_content_filter_error_message_detailed = '<span style="font-size:12px;"><strong>ERROR: Your location has been identified as part of a reported spam network. Comments have been disabled to prevent spam.</strong><br /><br /></span>'."\n";
	
	wp_die( __($spamshield_content_filter_error_message_detailed) );
	return false;
	// REJECT BASED ON COMMENT FILTER - END
	}
	
function spamshield_denied_post_proxy($approved) {
	// REJECT PROXY COMMENTERS - BEGIN
	//if ( !empty( $_SESSION['commentdata_'.WPSS_HASH] ) ) { $commentdata = $_SESSION['commentdata_'.WPSS_HASH]; } else { $commentdata = ''; }
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	$spamshield_proxy_error_message_detailed = '<span style="font-size:12px;"><strong>ERROR: Your comment has been blocked because the website owner has set their spam filter to not allow comments from users behind proxies.</strong><br/><br/>If you are a regular commenter or you feel that your comment should not have been blocked, please contact the site owner and ask them to modify this setting.<br /><br /></span>'."\n";
	wp_die( __($spamshield_proxy_error_message_detailed) );
	return false;
	// REJECT PROXY COMMENTERS - END
	}

function spamshield_denied_post_wp_blacklist($approved) {
	// REJECT BLACKLISTED COMMENTERS - BEGIN
	//if ( !empty( $_SESSION['commentdata_'.WPSS_HASH] ) ) { $commentdata = $_SESSION['commentdata_'.WPSS_HASH]; } else { $commentdata = ''; }
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	
	$spamshield_blacklist_error_message_detailed = '<span style="font-size:12px;"><strong>ERROR: Your comment has been blocked based on the website owner\'s blacklist settings.</strong><br/><br/>If you feel this is in error, please contact the site owner by some other method.<br /><br /></span>'."\n";
	
	wp_die( __($spamshield_blacklist_error_message_detailed) );
	return false;
	// REJECT BLACKLISTED COMMENTERS - END
	}

/*
function spamshield_denied_post_js_cookie($approved) {
	//Coming sooon
	}
*/

function spamshield_revdns_filter( $type = NULL, $status = NULL, $ip = NULL, $rev_dns = NULL, $author = NULL, $email = NULL ) {
	$spamshield_error_code = '';
	$blacklisted = false;
	if ( empty( $ip ) ) { $ip = $_SERVER['REMOTE_ADDR']; }
	$ip_regex = spamshield_preg_quote( $ip );
	if ( $type == 'contact' ) { $pref = 'CF-'; } else { $pref = ''; }
	
	// Test Reverse DNS Hosts - Do all with Reverse DNS moving forward
	
	// Bad Robot!
	$banned_servers = array(
		//"REVD0000" => "~(^|\.)007guard\.com$~i", // For testing on 127.0.0.1
		"REVD1023" => "~(^|\.)keywordspy\.com$~i",
		"REVD1024" => "~(^|\.)clients\.your-server\.de$~i",
		"REVD1025" => "~^rover-host\.com$~i",
		"REVD1026" => "~^host\.lotosus\.com$~i",
		"REVD1027" => "~^rdns\.softwiseonline\.com$~i",
		"REVD1028" => "~^s([a-z0-9]+)\.websitehome\.co\.uk$~i",
		"REVD1029" => "~\.opentransfer\.com$~i",
		"REVD1030" => "~(^|\.)arkada\.rovno\.ua$~i",
		"REVD1031" => "~^(host?|vm?)([0-9]+)\.server([0-9]+)\.vpn(999|2buy)\.com$~i",
		"REVD1032" => "~^(ip([\.\-]))?([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])(rackcentre\.redstation\.net\.uk|rdns\.(scalabledns\.com|as15003\.net|cloudradium\.com|ubiquityservers\.com)|static\.(hostnoc\.net|dimenoc\.com|reverse\.(softlayer\.com|queryfoundry\.net))|ip\.idealhosting\.net\.tr|triolan\.net|chunkhost\.com|kimsufi\.com|hosted-by\.xtrmhosting\.com|rev\.poneytelecom\.eu|customer-(rethinkvps|incero)\.com|unknown\.steephost\.(net|com))$~i",
		"REVD1033" => "~^(r?d)?ns([0-9]{1,3})\.(webmasters|rootleveltech)\.com$~i",
		"REVD1034" => "~^server([0-9]+)\.(shadowbrokers|junctionmethod|([a-z0-9\-]+))\.(com|net)$~i",
		"REVD1035" => "~^(hosted-by\.(ipxcore\.com|reliablesite\.net|slaskdatacenter\.pl)|host\.colocrossing\.com)$~i",
		"REVD1036" => "~^($ip_regex\.static|unassigned)\.quadranet\.com$~i",
		//"REVD1037" => "~^(ec([0-9])?([\.\-]))?([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])compute-([0-9])\.amazonaws\.com$~i",
		"REVD1037" => "~^(ec([0-9])?([\.\-]))?([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])([0-9]{1,3})(\.([a-z]{2})-(north|south)(east|west)-([0-9]+))?\.compute\.amazonaws\.com$~i",
		"REVD1038" => "~^([a-z]+)([0-9]{1,3})\.(guarmarr\.com|startdedicated\.com)$~i",
		"REVD1039" => "~^([a-z0-9\-\.]+)\.rev\.sprintdatacenter\.pl$~i",
		"REVD1040" => "~^ns([0-9]+)\.ovh\.net$~i",
		"REVD1041" => "~^(static|clients?)([\.\-])([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])([0-9]{1,3})([\.\-])(clients\.your-server\.de|customers\.filemedia\.net|hostwindsdns\.com)$~i",
		);

	foreach( $banned_servers as $error_code => $regex_phrase ) {
		if ( preg_match( $regex_phrase, $rev_dns ) ) { $spamshield_error_code .= ' '.$pref.$error_code; $blacklisted = true; }
		}
	
	if ( $type == 'comment' ) { 
		// The 8's Pattern - from relakks.com - Anonymous surfing, powered by bots
		if ( preg_match( "~^anon-([0-9]+)-([0-9]+)\.relakks\.com$~i", $rev_dns ) && preg_match( "~^([a-z]{8})$~i", $author ) && preg_match( "~^([a-z]{8})@([a-z]{8})\.com$~i", $email ) ) {
			//anon-###-##.relakks.com spammer pattern
			$spamshield_error_code .= ' '.$pref.'REVDA-1050';
			$blacklisted = true;
			}
		// The 8's - also coming from from rackcentre.redstation.net.uk
		}
	
	if ( !empty( $spamshield_error_code ) ) { $status = '2'; }
	$rev_dns_filter_data = array( 'status' => $status, 'error_code' => $spamshield_error_code, 'blacklisted' => $blacklisted );
	return $rev_dns_filter_data;
	}

function spamshield_content_short($commentdata) {
	// COMMENT LENGTH CHECK - BEGIN
	//if ( !empty( $_SESSION['commentdata_'.WPSS_HASH] ) ) { $commentdata = $_SESSION['commentdata_'.WPSS_HASH]; } else { $commentdata = ''; }
	$content_short_status						= ''; // Must go before tests
	$spamshield_error_code 						= ''; // Must go before tests
	$commentdata_comment_content				= $commentdata['comment_content'];
	$commentdata_comment_content_lc				= strtolower($commentdata_comment_content);
	$commentdata_comment_content_lc_deslashed	= stripslashes($commentdata_comment_content_lc);
	$comment_length 							= spamshield_strlen($commentdata_comment_content_lc_deslashed);
	$comment_min_length 						= 15;
	$commentdata_comment_type					= $commentdata['comment_type'];
	if( $comment_length < $comment_min_length && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		$content_short_status = true;
		$spamshield_error_code .= ' SHORT15';
		}
	if ( empty( $spamshield_error_code ) ) {
		$spamshield_error_code = 'No Error';
		}
	else {
		//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
		$spamshield_error_code = ltrim($spamshield_error_code);
		$spamshield_options = get_option('spamshield_options');
		spamshield_update_session_data($spamshield_options);
			
		if ( !empty( $spamshield_options['comment_logging'] ) ) {
			spamshield_log_data( $commentdata, $spamshield_error_code );
			}
		}
	
	// For Future Use
	// $spamshield_error_data = array( $spamshield_error_code, $blacklist_word_combo, $blacklist_word_combo_total );

	$commentdata['content_short_status'] = $content_short_status;
	
	// Original was:
	// return $content_short_status;
	return $commentdata;
	// COMMENT LENGTH CHECK - END
	}

/*
function spamshield_js_cookie_filter($commentdata) {

	// JavaScript and Cookies Layer
	
	// Coming soon

	$commentdata['js_cookie_filter_status'] = $js_cookie_filter_status;
	return $commentdata;
	}
*/
	
function spamshield_content_filter($commentdata) {
	// Content Filter aka The Algorithmic Layer
	// Blocking the Obvious to Improve Human/Pingback/Trackback Defense
	
	// Note: Certain loops are unrolled because of a weird compatibility issue with certain servers. Works fine on most, but for some unforeseen reason, a few have issues.
	// Switching to REGEX filters over time anyway so these will get removed.
	
	// Timer Start  - Content Filter
	$wpss_start_time_content_filter = spamshield_microtime();
	
	$content_filter_status 		= ''; // Must go before tests
	$spamshield_error_code 		= ''; // Must go before tests

	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);
	
	$wpss_key_values 	= spamshield_get_key_values();
	$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
	$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
	$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
	$wpss_js_val 		= $wpss_key_values['wpss_js_val'];

	$key_update_time 	= $spamshield_options['last_key_update']; // DEPRECATED
			
	if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wpss_comment_validation_js = $_COOKIE[$wpss_ck_key]; }
	else { $wpss_comment_validation_js = ''; }
	if ( !empty( $_POST[$wpss_js_key] ) ) {
		$wpss_form_validation_post 	= $_POST[$wpss_js_key]; //Comments Post Verification
		}
	else { $wpss_form_validation_post = ''; }
	if ( !empty( $_POST['JSONST'] ) ) { $post_jsonst = $_POST['JSONST']; } else { $post_jsonst = ''; }
	if ( !empty( $_POST['ref2xJS'] ) ) { $post_ref2xjs = $_POST['ref2xJS']; } else { $post_ref2xjs = ''; }
	$post_ref2xjs_lc = strtolower($post_ref2xjs);
	$wpss_date_this_year = @date('Y');
	$wpss_date_next_year = $wpss_date_this_year + 1;
	$wpss_date_last_year = $wpss_date_this_year - 1;
	
	// CONTENT FILTERING - BEGIN

	$commentdata_comment_post_id					= $commentdata['comment_post_ID'];
	$commentdata_comment_post_title					= $commentdata['comment_post_title'];
	$commentdata_comment_post_title_lc				= strtolower($commentdata_comment_post_title);
	$commentdata_comment_post_title_lc_regex 		= spamshield_preg_quote($commentdata_comment_post_title_lc);
	$commentdata_comment_post_url					= $commentdata['comment_post_url'];
	$commentdata_comment_post_url_lc				= strtolower($commentdata_comment_post_url);
	$commentdata_comment_post_url_lc_regex 			= spamshield_preg_quote($commentdata_comment_post_url_lc);

	$commentdata_comment_post_type					= $commentdata['comment_post_type'];
		// Possible results: 'post', 'page', 'attachment', 'revision', 'nav_menu_item'

	// Next two are boolean
	$commentdata_comment_post_comments_open			= $commentdata['comment_post_comments_open'];
	$commentdata_comment_post_pings_open			= $commentdata['comment_post_pings_open'];

	$commentdata_comment_author						= $commentdata['comment_author'];
	$commentdata_comment_author_deslashed			= stripslashes($commentdata_comment_author);
	$commentdata_comment_author_lc					= strtolower($commentdata_comment_author);
	$commentdata_comment_author_lc_regex 			= spamshield_preg_quote($commentdata_comment_author_lc);
	$commentdata_comment_author_lc_words 			= spamshield_count_words($commentdata_comment_author_lc);
	$commentdata_comment_author_lc_space 			= ' '.$commentdata_comment_author_lc.' ';
	$commentdata_comment_author_lc_deslashed		= stripslashes($commentdata_comment_author_lc);
	$commentdata_comment_author_lc_deslashed_regex 	= spamshield_preg_quote($commentdata_comment_author_lc_deslashed);
	$commentdata_comment_author_lc_deslashed_words 	= spamshield_count_words($commentdata_comment_author_lc_deslashed);
	$commentdata_comment_author_lc_deslashed_space 	= ' '.$commentdata_comment_author_lc_deslashed.' ';
	$commentdata_comment_author_email				= $commentdata['comment_author_email'];
	$commentdata_comment_author_email_lc			= strtolower($commentdata_comment_author_email);
	$commentdata_comment_author_email_lc_regex 		= spamshield_preg_quote($commentdata_comment_author_email_lc);
	$commentdata_comment_author_url					= $commentdata['comment_author_url'];
	$commentdata_comment_author_url_lc				= strtolower($commentdata_comment_author_url);
	$commentdata_comment_author_url_lc_regex 		= spamshield_preg_quote($commentdata_comment_author_url_lc);
	$commentdata_comment_author_url_domain_lc		= spamshield_get_domain($commentdata_comment_author_url_lc);
	
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
	
	$commentdata_user_agent					= $_SERVER['HTTP_USER_AGENT'];
	$commentdata_user_agent_lc				= strtolower($commentdata_user_agent);
	$commentdata_remote_addr				= $_SERVER['REMOTE_ADDR'];
	$commentdata_remote_addr_regex 			= spamshield_preg_quote($commentdata_remote_addr);
	$commentdata_remote_addr_lc				= strtolower($commentdata_remote_addr);
	$commentdata_remote_addr_lc_regex 		= spamshield_preg_quote($commentdata_remote_addr_lc);

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
	if ( !empty( $_SERVER['HTTP_VIA'] ) ) { $ip_proxy_via=trim($_SERVER['HTTP_VIA']); } else { $ip_proxy_via = ''; }
	$ip_proxy_via_lc = strtolower($ip_proxy_via);
	if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { $masked_ip = trim($_SERVER['HTTP_X_FORWARDED_FOR']); } else { $masked_ip = ''; }
	$masked_ip_block = explode('.',$masked_ip);
	if ( spamshield_is_valid_ip( $masked_ip ) ) {
		$masked_ip_valid=true;
		$masked_ip_core=rtrim($masked_ip," unknown;,");
		}
	$ip 						= $commentdata_remote_addr;
	$reverse_dns 				= gethostbyaddr($commentdata_remote_addr);
	if ( $reverse_dns == '.' ) { $reverse_dns_ip = '[No Data]'; } elseif ( $reverse_dns == $ip ) { $reverse_dns_ip = $ip; } else { $reverse_dns_ip = gethostbyname($reverse_dns); }
	$reverse_dns_ip_regex 		= spamshield_preg_quote($reverse_dns_ip);
	$reverse_dns_lc 			= strtolower($reverse_dns);
	$reverse_dns_lc_regex 		= spamshield_preg_quote($reverse_dns_lc);
	$reverse_dns_lc_rev 		= strrev($reverse_dns_lc);
	$reverse_dns_lc_rev_regex 	= spamshield_preg_quote($reverse_dns_lc_rev);
	
	$commentdata_remote_host = $reverse_dns;
	$commentdata_remote_host_lc	= strtolower($commentdata_remote_host);

	if ( empty( $commentdata_remote_host_lc ) ) {
		$commentdata_remote_host_lc = 'blank';
		}

	if ( $reverse_dns_ip != $commentdata_remote_addr || $commentdata_remote_addr == $reverse_dns ) {
		$reverse_dns_authenticity = '[Possibly Forged]';
		} 
	else {
		$reverse_dns_authenticity = '[Verified]';
		}
	// Detect Use of Proxy
	if ( !empty( $ip_proxy_via ) || !empty( $masked_ip ) ) {
		if ( empty( $masked_ip ) ) { $masked_ip='[No Data]'; }
		$ip_proxy='PROXY DETECTED';
		$ip_proxy_short='PROXY';
		$ip_proxy_data=$commentdata_remote_addr.' | MASKED IP: '.$masked_ip;
		$proxy_status='TRUE';
		//Google Chrome Compression Check
		if ( strpos( $ip_proxy_via_lc, 'chrome compression proxy' ) !== false && preg_match( "~^google-proxy-(.*)\.google\.com$~i", $reverse_dns ) ) {
			$ip_proxy_chrome_compression='TRUE';
			}
		else {
			$ip_proxy_chrome_compression='FALSE';
			}
		}
	else {
		$ip_proxy='No Proxy';
		$ip_proxy_short=$ip_proxy;
		$ip_proxy_data=$commentdata_remote_addr;
		$proxy_status='FALSE';
		}
	// IP / PROXY INFO - END
	
	// Post Type Filter
	/*
	// Removed V 1.1.7 - Found Exception
	if ( $commentdata_comment_post_type != 'post' ) {
		// Prevents Trackback, Pingback, and Automated Spam on 'Page' Post Type
		// Invalid types: 'page', 'attachment', 'revision', 'nav_menu_item'
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' INVALTY';
		}
	*/

	// Simple Filters
	
	$blacklist_word_combo_total_limit = 10; // you may increase to 30+ if blog's topic is adult in nature - DEPRECATED
	$blacklist_word_combo_total = 0;
	
	// Filter 1: Number of occurrences of 'http://' in comment_content
	$filter_1_count_http = spamshield_substr_count($commentdata_comment_content_lc_deslashed, 'http://');
	$filter_1_count_https = spamshield_substr_count($commentdata_comment_content_lc_deslashed, 'https://');
	$filter_1_count = $filter_1_count_http + $filter_1_count_https;
	$filter_1_limit = 4;
	$filter_1_trackback_limit = 1;

	// Pingback/Trackback Filters
	// Filter 200: Pingback: Blank data in comment_content: [...]  [...]
	//$filter_200_count = spamshield_substr_count($commentdata_comment_content_lc_deslashed, '[...]  [...]');
	//$filter_200_limit = 1;
	//$filter_200_trackback_limit = 1;

	// Authors Only - Non-Trackback
	//Removed Filters 300-423 and replaced with Regex

	// Author Test: for *author names* surrounded by asterisks
	if ( preg_match( "~^\*~", $commentdata_comment_author_lc_deslashed ) || preg_match( "~\*$~", $commentdata_comment_author_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 300001A-STR';
		}
		
	// Author Test: if $commentdata_comment_author_lc_deslashed is a URL, NO GO
	if ( preg_match( "~^https?~i", $commentdata_comment_author_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 300002A-URL';
		}

	// Author Test: if $commentdata_comment_author_lc_deslashed contains more than 7 words, NO GO
	if ( $commentdata_comment_author_lc_deslashed_words > 7 && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 300003A-MAX';
		}

	// Author Test: if $commentdata_comment_author_lc_deslashed contains "seo" at either end of phrase, NO GO
	if ( ( preg_match( "~^seo\s~i", $commentdata_comment_author_lc_deslashed ) || preg_match( "~\sseo$~i", $commentdata_comment_author_lc_deslashed ) ) && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 300004A-SEO';
		}

	// Regular Expression Tests - 2nd Gen - Comment Author/Author URL - BEGIN

	// 10500-13000 - Complex Test for terms in Comment Author/URL - $commentdata_comment_author_lc_deslashed/$commentdata_comment_author_url_domain_lc
	// $CommentAuthorURLDomain = spamshield_get_domain($commentdata_comment_author_url_lc);
	// Blacklisted Domains Check
	if ( spamshield_domain_blacklist_chk( $commentdata_comment_author_url_domain_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10500AU-BL';
		}
	// PayDay Loan Spammers
	if ( preg_match( "~((payday|students?|title|online|short-?term)([a-z0-9\-]*)loan|cash([a-z0-9\-]*)advance)~i", $commentdata_comment_author_url_domain_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10501AU-PDL';
		}
	// PayDay Loan Spammers Group - Author URL
	if ( preg_match( "~^((ww[w0-9]|m)\.)?(burnleytaskforce\.org\.uk|ccls5280\.org|chrislonergan\.co\.uk|getwicked\.co\.uk|kickstartmediagroup\.co\.uk|mpaydayloansa1\.info|neednotgreed\.org\.uk|royalspicehastings\.co\.uk|snakepaydayloans\.co\.uk|solarsheild\.co\.uk|transitionwestcliff\.org\.uk|blyweertbeaufort\.co\.uk|disctoprint\.co\.uk|fish-instant-payday-loans\.co\.uk|heritagenorth\.co\.uk|standardsdownload\.co\.uk|21joannapaydayloanscompany\.joannaloans\.co\.uk)$~i", $commentdata_comment_author_url_domain_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10502AU-PDL';
		}
	// Miscellaneous Common Spam Domains - Author URL
	// Correlates to and replaces filters 20001-20072
	if ( preg_match( "~^((ww[w0-9]|m)\.)?(groups\.(google|yahoo)\.(com|us)|(phpbbserver|freehostia|free-site-host|keywordspy|t35|(1|2)50m|widecircles|netcallidus|webseomasters|mastersofseo|mysmartseo|sitemapwriter|shredderwarehouse|mmoinn|animatedfavicon|cignusweb|rsschannelwriter|clickaudit|choice-direct|experl|registry-error-cleaner|sunitawedsamit|agriimplements|submit(-trackback|bookmark)|(comment|youtube-)poster|post-comments|wordpressautocomment|grillpartssteak|tpbunblocked|sqiar|redcamtube|globaldata4u|297286)\.com|youporn([0-9]+)\.vox\.com|blogs\.ign\.com|members\.lycos\.co\.uk|christiantorrents\.ru|lifecity\.(tv|info)|(phpdug|kankamforum)\.net|(real-url|hit4hit)\.org|johnbeck(seminar|ssuccessstories)?\.(com|net|tv)|(m\.)?(youtube|dailymotion|facebook|twitter|plus\.google)\.com|youtu\.be|(bitly|tinyurl)\.com|(bit|adf|ow)\.ly|(ranksindia|ranksdigitalmedia|semmiami|(agenciade)?\.serviciosdeseo)\.(com|net|org))$~i", $commentdata_comment_author_url_domain_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10510AU-MSC';
		}
	// Testing for a unique identifying string from the comment content in the Author URL Domain
	preg_match( "~\s+([a-z0-9]{6,})$~i", $commentdata_comment_content_lc_deslashed, $wpss_str_matches );
	if ( !empty( $wpss_str_matches[1] ) ) { $wpss_spammer_id_string = $wpss_str_matches[1]; } else { $wpss_spammer_id_string = ''; }
	$commentdata_comment_author_url_domain_lc_elements = explode( '.', $commentdata_comment_author_url_domain_lc );
	$commentdata_comment_author_url_domain_lc_elements_count = count( $commentdata_comment_author_url_domain_lc_elements ) - 1;
	if ( !empty ( $wpss_spammer_id_string ) ) {
		$i = 0;
		// The following line to prevent exploitation:
		$i_max = 20;
		while ( $i < $commentdata_comment_author_url_domain_lc_elements_count && $i < $i_max ) {
			if ( !empty( $commentdata_comment_author_url_domain_lc_elements[$i] ) ) {
				if ( $commentdata_comment_author_url_domain_lc_elements[$i] == $wpss_spammer_id_string ) {
					if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
					$spamshield_error_code .= ' 10511AUA';
					break;
					}
				}
			$i++;
			}
		}
	// Potential Exploits
	// Includes protection for Trackbacks and Pingbacks
	if ( preg_match( "~/phpinfo\.php\?~i", $commentdata_comment_author_url_lc ) ) {
		// Used in XSS
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 15001AU-XPL';
		}
	if ( preg_match( "~^(https?\://)?([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/?~i", $commentdata_comment_author_url_lc ) ) {
		// Normal people (and Trackbacks/Pingbacks) don't post IP addresses as their website address in a comment, DUH
		// Dangerous because users have no idea what website they are clicking through to
		// Likely a Phishing site or XSS
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 15002AU-XPL';
		}
	// PayDay Loan Spammers
	if ( preg_match( "~((payday|students?|title|onli?ne|short([\s\.\-_]*)term)([\s\.\-_]*)loan|cash([\s\.\-_]*)advance)~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10501A-PDL';
		}
	// Debt Consolidation Spammers
	if ( preg_match( "~(debt([\s\.\-_]*))?consolidat(ion|or|er)(([\s\.\-_]*)loan)?~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10521A-DC';
		}
	// SEO Spammers
	if ( preg_match( "~((internet|search|zoekmachine|social([\s\.\-_]*)media)([\s\.\-_]+)(engine([\s\.\-_]+))?(optimi[sz](ation|er|ing)|optimalisatie|market(ing|er)|consult(ant|ing)|rank(ing)?)|seo|sem)$~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10601A-SEO';
		}
	if ( preg_match( "~((internet|search|zoekmachine|social([\s\.\-_]*)media)([\s\.\-_]+)(engine([\s\.\-_]+))?(optimi[sz](ation|er|ing)|optimalisatie|market(ing|er)|consult(ant|ing)|rank(ing)?)|网站推广|谷歌排名|link([\s\.\-_]*)build(ing|er)|(\s+)se([o0m])(\s+)(business|company|firm|agency)|web([\s\.\-_]*)(site)?([\s\.\-_]*)promot(ion|ing|er)|(trackback|social|comments?)([\s\.\-_]*)(submit(ter|ting)?|poster))~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10602A-SEO';
		}
	if ( preg_match( "~(^|[\s\.])(seo|sem)($|[\s\.,\!\?\@])~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10603A-SEO';
		}
	// Website Design/Hosting Spammers
	if ( preg_match( "~(web([\s\.\-_]*)(site)?([\s\.\-_]*)(host(ing)?|design(er|ing)?|develop(ment|er|ing)?)|javascript|webmaster|website|template)~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10701A-WEB';
		}
	// Online Gambling Spammers
	if ( preg_match( "~(onli?ne|internet|web)([a-z0-9\.\-_\s]*)(gambling|bet(ting)?|casinos?|poker|blackjack)|(gambling|bet(ting)?|casinos?|poker|blackjack)([a-z0-9\.\-_\s]*)(onli?ne|internet|web)~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10801A-OLG';
		}
	// Medical Spammers
	// Correlates to filters 2-41 AUTH
	if ( preg_match( "~(viagra|cialis|levitra|erectile([\s\.\-_]*)disfunction|erecti(on|le)|xanax|zithromax|phentermine|([\s\.\-_]+)soma([\s\.\-_]+)|prescription|tramadol|(penis|male)([\s\.\-_]*)en(larg|hanc)ement|^penis([\s\.\-_]+)|buy([\s\.\-_]*)pills?|diet([\s\.\-_]*)pills?|weight([\s\.\-_]*)loss|lose([\s\.\-_]*)weight|propecia|onli?ne([\s\.\-_]*)pharmacy|medications?|medicines|homeopathics?|ephedr(ine?|a)|valium|adipex|acc?utane|acomplia|rimonabant|zimulti|garcinia([\s\.\-_]*)cambogia|herbalife|formula([\s\.\-_]*)t10|steroids?|ripped([\s\.\-_]*)muscles?|muscle([\s\.\-_]*)builders?|build([\s\.\-_]*)muscles?|drug([\s\.\-_]*)rehab|plantar([\s\.\-_]*)fasciitis|periodontist|rhinoplasty|([\s\.\-_]+)surge(ons|ry))~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		// weight loss, male enhancement, diet, ripped muscle, lose weight, etc
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10901A-MED';
		}
	// Porn/Sex Spammers
	// Correlates to filters 104-159
	if ( preg_match( "~(^([\s\.\-_]*)(p(or|ro)n|sexe?|mast(u|e)rbat(e|ion|ing)|rap(e|er|ist|ing)|incest(ual|uous)?|bestiality|cum|hentai|pussy|penis|vagina|xxx|naked|nude|desnuda|orgasm|fuck(ing)?|dildo|ejaculat(e|ion|ing)|lesbian|gay|(homo|bi|hetero)?sexu([ae])l|cumshots?|anal|eroti([ck]{1,2})(ism)?|clitoris|porntube|blow([\s\.\-_]*)jobs?|prostitutes?|call([\s\.\-_]*)girls?|(escort|sexe?(u([ae])l)?)([\s\.\-_]*)services?|celebrit(y|ies))([\s\.\-_]*)$|([a-z0-9\-]*)([\s\.\-_]+)(p(or|ro)n|sexe?|xxx|hentai)([\s\.\-_]*)$|(teen|rape|incest|anal|vaginal|gay|lesbian|torture|bestiality|animal|celebrit(y|ies)|cyber)([\s\.\-_]*)(porn|hentai|xxx|sexe?)|(sexe?|adult|xxx|p(or|ro)n|hentai)([\s\.\-_]*)(movie|tape|vid(s|eos?))|p(or|ro)nographi(c|que))~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 11001A-SXP';
		}
	if ( preg_match( "~(^|[\s\.])(p(or|ro)no?(gra(ph|f+)i(c|que)?)?(tube)?|hentai|(bi|homo|hetero)?sex(e|y|u([ae])ls?)?|xxx|naked|nud(e|is([mt]))s?|desnuda|eroti([ck]{1,2})(ism)?|cum|orgasm|puss(y|ies)|penis|vagina([sl])?|clitoris|ejaculat(e|ion|ing)|fuck(ing|ed)?|dildos?|incest(ual|uous)?|bestiality|cumshots?|blow([\s\.\-_]*)jobs?|prostitutes?|call([\s\.\-_]*)girls?|(escort|sexe?(u([ae])l)?)([\s\.\-_]*)services?)($|[\s\.,\!\?\@])~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		// Common Sex/Porn Words: porn, pornography, pornographic, porntube, hentai, bisexual, homosexual, heterosexual, sexual, sex, sexy, xxx, naked, nude, nudist, nudism, desnuda, erotic, eroticism, cum, orgasm, pussy, penis, vagina, vaginal, clitoris, ejaculate, ejaculation, ejaculating, fuck, fucking, fucked, dildo, incest, bestiality, cumshot, blow job, prostitute, hooker, call girl, escort services, sexual services, - along with plurals and variations
		// on hold rap(e|er|ist|ing)|
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 11002A-SXP';
		}
	// Offshore/Outsourcing Spam
	// Correlates to filters 300-423 AUTH
	if ( preg_match( "~((india|russia|ukraine|china)([\s\.\-_]+)(offshore|outsourc(e|ing))|(offshore|outsourc(e|ing))([\s\.\-_]+)(india|russia|ukraine|china)|data([\s\.\-_]+)entry([\s\.\-_]+)(india|russia|ukraine|china))~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12001A-OFS';
		}
	// Miscellaneous Spammers
	// Correlates to filters 300-423 AUTH
	if ( preg_match( "~(modulesoft|([\s\.\-_]+)company|business|organization|([\s\.\-_]+)seminar|phpdug|([\s\.\-_]+)sunglasses|([\s\.\-_]+)lunettes?|(designer|christian([\s\.\-_]+)dior|hermes|michael([\s\.\-_]+)kors)?([\s\.\-_]+)hand([\s\.\-_]*)bags?|([\s\.\-_]+)outlet|property([\s\.\-_]+)vault|foreclosure|earn([\s\.\-_]+)money|software|home([\s\.\-_]+)design|(([\s\.\-_]+)e?-?learning([\s\.\-_]+)|([\s\.\-_]*)how([\s\.\-_]*)to)([\s\.\-_]+)|youtube|dailymotion|facebook|twitter|instagram|social([\s\.\-_]+)bookmark|united([\s\.\-_]+)states|johannesburg|bucuresti|([\s\.\-_]+)city$|for([\s\.\-_]+)sale|buy([\s\.\-_]+)(cheap|onli?ne)|property|logo([\s\.\-_]+)design|injury([\s\.\-_]+)lawyer|internas?tional|information|advertising|car([\s\.\-_]+)rental|rent([\s\.\-_]+)a([\s\.\-_]+)car|development|technology|forex([\s\.\-_]+)trading|anonymous|php([\s\.\-_]+)expert|travel([\s\.\-_]+)deals|college([\s\.\-_]+)student|health([\s\.\-_]*)(insurance|care)|click([\s\.\-_]+)here|visit([\s\.\-_]+)now|turbo([\s\.\-_]+)tax|photoshop|power([\s\.\-_]+)kite|stop([\s\.\-_]+)sweating?([\s\.\-_]+)me|sweating?([\s\.\-_]+)on|onli?ne([\s\.\-_]+)jobs|jobs([\s\.\-_]+)onli?ne|(pc|computer|laptop|laptopuri)([\s\.\-_]+)(repair|reparatii)|(repair|reparatii)([\s\.\-_]+)(pc|computer|laptop|laptopuri)|mobilabonnement([\s\.\-_]+)priser|kroatien([\s\.\-_]+)insel([\s\.\-_]+)brac|unblocked|([\s\.\-_]+)(coupon|discount)s?|(coupon|promo|voucher|shipping)([\s\.\-_]+)codes?|^free([\s\.\-_]+)([a-z0-9\s\.\-_]+)([\s\.\-_]+)codes?$|personalization|([\s\.\-_]+)(home|web)page|bluetooth|numerology|prox(y|ies)([\s\.\-_]+)(surf(ing|er)?|software)|([\s\.\-_]+)(home|web)page)~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12501A-MSC';
		}
	// Misc - Author begins with Keyword
	if ( preg_match( "~^(buy|gratis)([\s\.\-_]+)~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12502A-MSC';
		}
	// Misc - Author ends with Keyword
	if ( preg_match( "~([\s\.\-_]+)(clothing|prox(y|ies)|surge(ons|ry))$~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12503A-MSC';
		}
	// Misc - Author contains Keyword anywhere in phrase, surrounded by word boundaries
	if ( preg_match( "~(^|[\s\.])(android|attorneys?|business|cheap|cheats?|click([\s\.\-_]+)here|company|computers?|contractors?|crack(er)?s?|credit([\s\.\-_]+)cards?|develop(ment|er)s?|discount(ed)?|downloads?|facebook|gratis|hand([\s\.\-_]*)bags?|(home|web)page|how([\s\.\-_]+)to|internet|instagram|install(ation|er)s?|insurance|laptops?|lunettes?|online|organizations?|passwords?|power([\s\.\-_]+)leveling|prepaid|(sun|eye)glass(es)?|software|technolog(y|ies)|text([\s\.\-_]+)messages?|twitter|unlimited|web([\s\.\-_]*)sites?|youtube|$wpss_date_this_year|$wpss_date_next_year|$wpss_date_last_year)($|[\s\.,\!\?\@])~i", $commentdata_comment_author_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12504A-MSC';
		}

	// Misc - Author equals Keyword
	// Correlates to filters 300400AUTH-300413AUTH
	// Includes Trackbacks and Pingbacks
	if ( preg_match( "~^(designs?|guides?|hosting|marketing|(natural)?(penis|male)([\s\.\-_]*)en(larg|hanc)ement|pills?|se([o0m])|sex(e|y|u([ae])l)?|tips?)$~i", $commentdata_comment_author_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 12505A-MSC';
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
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 1-HT';
		}

	// Regular Expression Tests - 2nd Gen - Comment Content - BEGIN
	
	// Miscellaneous Patterns that Keep Repeating
	if ( preg_match( "~^([0-9]{6})\s([0-9]{6})(.*)\s([0-9]{6})$~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10401C';
		}
	// Blacklisted Domains Check - Links in Content
	if ( spamshield_link_blacklist_chk( $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10500CU-BL';
		}
	// PayDay Loan Spammers and the like...
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+)\.([a-z0-9\-_/'\"\.\/\?\&\=\~\@\s]+)(\s*)(['\"])?(\s*)(>|\])([a-z0-9\-\.\?\!,@\s]*)((payday|students?|title|onli?ne|short([\s\.\-_]*)term)([\s\.\-_]*)loan|cash([\s\.\-_]*)advance)([a-z0-9\-\.\?\!,@\s]*)(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10501C-PDL';
		}
	// PayDay Loan Spammers Group
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://((ww[w0-9]|m)\.)?(burnleytaskforce\.org\.uk|ccls5280\.org|chrislonergan\.co\.uk|getwicked\.co\.uk|kickstartmediagroup\.co\.uk|mpaydayloansa1\.info|neednotgreed\.org\.uk|royalspicehastings\.co\.uk|snakepaydayloans\.co\.uk|solarsheild\.co\.uk|transitionwestcliff\.org\.uk|blyweertbeaufort\.co\.uk|disctoprint\.co\.uk|fish-instant-payday-loans\.co\.uk|heritagenorth\.co\.uk|standardsdownload\.co\.uk|21joannapaydayloanscompany\.joannaloans\.co\.uk)/?([a-z0-9\-_/'\"\.\?\&\=\~\@\s]*)(['\"])?(>|\])~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10502CU-PDL';
		}

	// Miscellaneous Common Spam Domains - Comment Content
	// Correlates to and replaces filters 20001-20072
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://((ww[w0-9]|m)\.)?(groups\.(google|yahoo)\.(com|us)|(phpbbserver|freehostia|free-site-host|keywordspy|t35|(1|2)50m|widecircles|netcallidus|webseomasters|mastersofseo|mysmartseo|sitemapwriter|shredderwarehouse|mmoinn|animatedfavicon|cignusweb|rsschannelwriter|clickaudit|choice-direct|experl|registry-error-cleaner|sunitawedsamit|agriimplements|submit(-trackback|bookmark)|(comment|youtube-)poster|post-comments|wordpressautocomment|grillpartssteak|tpbunblocked|sqiar|redcamtube|globaldata4u|297286)\.com|youporn([0-9]+)\.vox\.com|blogs\.ign\.com|members\.lycos\.co\.uk|christiantorrents\.ru|lifecity\.(tv|info)|(phpdug|kankamforum)\.net|(real-url|hit4hit)\.org|johnbeck(seminar|ssuccessstories)?\.(com|net|tv)|(m\.)?(youtube|dailymotion|facebook|twitter|plus\.google)\.com|youtu\.be|(bitly|tinyurl)\.com|(bit|adf|ow)\.ly|(ranksindia|ranksdigitalmedia|semmiami|(agenciade)?\.serviciosdeseo)\.(com|net|org))/?([a-z0-9\-_/'\"\.\?\&\=\~\@\s]*)(['\"])?(>|\])~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10510CU-MSC';
		}
	// Debt Consolidation Spammers
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+\.)?([a-z0-9\-_/\.\?\&\=\~\@]+)(\s*)(['\"])?(\s*)(>|\])(\s*)(debt([\s\.\-_]*))?consolidat(ion|or|er)(([\s\.\-_]*)loan)?([a-z0-9\-\.\?\!,@\s]*)(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10521C-DC';
		}
	// SEO Spammers
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+\.)?([a-z0-9\-_/\.\?\&\=\~\@]+)(\s*)(['\"])?(\s*)(>|\])(\s*)(([a-z0-9\-\.\?\!,@\s]*)\s)?((internet|search|zoekmachine|social([\s\.\-_]*)media)([\s\.\-_]+)(engine([\s\.\-_]+))?(optimi[sz](ation|er|ing)|optimalisatie|market(ing|er)|consult(ant|ing)|rank(ing)?)|seo|sem)(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10601C-SEO';
		}
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+\.)?([a-z0-9\-_/\.\?\&\=\~\@]+)(\s*)(['\"])?(\s*)(>|\])(\s*)(([a-z0-9\-\.\?\!,@\s]*)\s)?((internet|search|zoekmachine|social([\s\.\-_]*)media)([\s\.\-_]+)(engine([\s\.\-_]+))?(optimi[sz](ation|er|ing)|optimalisatie|market(ing|er)|consult(ant|ing)|rank(ing)?)|网站推广|谷歌排名|link([\s\.\-_]*)build(ing|er)|(\s+)se([o0m])(\s+)(business|company|firm|agency)|web([\s\.\-_]*)(site)?([\s\.\-_]*)promot(ion|ing|er)|(trackback|social|comments?)([\s\.\-_]*)(submit(ter|ting)?|poster))(\s*)(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10602C-SEO';
		}
	// Website Design/Hosting Spammers
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+\.)?([a-z0-9\-_/\.\?\&\=\~\@]+)(\s*)(['\"])?(\s*)(>|\])(\s*)web([\s\.\-_]*)(site)?([\s\.\-_]*)(host(ing)?|design(er|ing)?|develop(ment|er|ing)?)(\s*)(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10701C-WEB';
		}
	// Online Gambling Spammers
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+\.)?([a-z0-9\-_/\.\?\&\=\~\@]+)(\s*)(['\"])?(\s*)(>|\])([a-z0-9\-\.\?\!,@\s]*)((onli?ne|internet|web)([a-z0-9\.\-_\s]*)(gambling|bet(ting)?|casinos?|poker|blackjack)|(gambling|bet(ting)?|casinos?|poker|blackjack)([a-z0-9\.\-_\s]*)(onli?ne|internet|web))([a-z0-9\-\.\?\!,@\s]*)(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10801C-OLG';
		}
	// Medical Spammers
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+\.)?([a-z0-9\-_/\.\?\&\=\~\@]+)(\s*)(['\"])?(\s*)(>|\])([a-z0-9\-\.\?\!,@\s]*)(viagra|cialis|levitra|erectile([\s\.\-_]*)disfunction|erecti(on|le)|xanax|zithromax|phentermine|([\s\.\-_]+)soma([\s\.\-_]+)|prescription|tramadol|(penis|male)([\s\.\-_]*)en(larg|hanc)ement|^penis([\s\.\-_]+)|^vagina([l1\s]+)|buy([\s\.\-_]*)pills?|diet([\s\.\-_]*)pills?|weight([\s\.\-_]*)loss|propecia|onli?ne([\s\.\-_]*)pharmacy|medication|ephedr(ine?|a)|valium|adipex|acc?utane|acomplia|rimonabant|zimulti|herbalife|steroi|drug([\s\.\-_]*)rehab|plantar([\s\.\-_]*)fasciitis|perlodontlst|rhlnoplasty|([\s\.\-_]+)surge(ons|ry))([a-z0-9\-\.\?\!,@\s]*)(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 10901C-MED';
		}
	// Porn/Sex Spammers
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+\.)?([a-z0-9\-_/\.\?\&\=\~\@]+)(\s*)(['\"])?(\s*)(>|\])([a-z0-9\-\.\?\!,@\s]*)(([a-z0-9\-]*)([\s\.\-_]+)(p(or|ro)n|sexe?|xxx|hentai)([\s\.\-_]*)$|(teen|rape|incest|anal|vaginal|gay|lesbian|torture|bestiality|animal|celebrit(y|ies)|cyber)([\s\.\-_]*)(porn|hentai|xxx|sexe?)|(sexe?|adult|xxx|p(or|ro)n|hentai)([\s\.\-_]*)(movie|tape|vid(s|eos?))|p(or|ro)nographi(c|que))([a-z0-9\-\.\?\!,@\s]*)(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 11001C-SXP';
		}
	// Miscellaneous Spammers
	// Correlates to filters 300-423 AUTH
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+\.)?([a-z0-9\-_/\.\?\&\=\~\@]+)(\s*)(['\"])?(\s*)(>|\])([a-z0-9\-\.\?\!,@\s]*)((oakley|ray(\s+)ban|gucci|prescrip([tc])ion)(\s+)(sunglasses|lunettes?)|(designer|christian(\s+)dior|hermes|michael(\s+)kors)?(\s+)handbags?|injury(\s+)lawyer|car(\s+)rental|rent(\s+)a(\s+)car|forex(\s+)trading|(pc|computer|laptop|laptopuri)(\s+)(repair|reparatii)|(repair|reparatii)(\s+)(pc|computer|laptop|laptopuri)|(\s+)(coupon|discount)s?|(coupon|promo|voucher|shipping)(\s+)codes?|free([\s\.\-_]+)([a-z0-9\s\.\-_]+)([\s\.\-_]+)codes?|proxy(\s+)surf)([a-z0-9\-\.\?\!,@\s]*)(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) && $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
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
	while ( $i < $RepeatedTermsTestCount ) {
		if ( !empty( $RepeatedTermsTest[$i] ) ) {
			$RepeatedTermsInContentCount = spamshield_substr_count( $commentdata_comment_content_lc_deslashed, $RepeatedTermsTest[$i] );
			$RepeatedTermsInContentStrLength = spamshield_strlen($RepeatedTermsTest[$i]);
			if ( $RepeatedTermsInContentCount > 1 && $CommentContentTotalWords < $RepeatedTermsInContentCount ) {
				$RepeatedTermsInContentCount = 1;
				}
			$RepeatedTermsInContentDensity = ( $RepeatedTermsInContentCount / $CommentContentTotalWords ) * 100;
			//$spamshield_error_code .= ' 9000-'.$i.' KEYWORD: '.$RepeatedTermsTest[$i].' DENSITY: '.$RepeatedTermsInContentDensity.'% TIMES WORD OCCURS: '.$RepeatedTermsInContentCount.' TOTAL WORDS: '.$CommentContentTotalWords;
			if ( $RepeatedTermsInContentCount >= 5 && $RepeatedTermsInContentStrLength >= 4 && $RepeatedTermsInContentDensity > 40 ) {		
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' 9000-'.$i;
				}
			}
		$i++;
		}
	// Comment Author and Comment Author URL appearing in Content
	/* This test deprecated then deleted on 05/02/2014
	if ( !empty( $commentdata_comment_author_url_lc ) ) {
		$commentdata_comment_author_lc_inhref = '>'.$commentdata_comment_author_lc_deslashed.'</a>';
		
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_url_lc ) !== false && strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_inhref ) !== false ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' 9100';
			}
		}
	*/
	// REGEX VERSION
	if ( preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)$commentdata_comment_author_url_lc_regex([a-z0-9\-_/'\"\.\?\&\=\~\@\s]*)(['\"])?(>|\])$commentdata_comment_author_lc_deslashed_regex(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 9100-1';
		}
	if ( $commentdata_comment_author_url_lc == $commentdata_comment_author_lc_deslashed && !preg_match( "~https?\://~i", $commentdata_comment_author_url_lc ) && preg_match( "~(<(\s*)a(\s+)([a-z0-9\-_\.\?\='\"\:\s]*)(\s*)href|\[(url|link))(\s*)\=(\s*)(['\"])?(\s*)https?\://([a-z0-9\-\.]+\.)?([a-z0-9\-_/\.\?\&\=\~\@]+)(\s*)(['\"])?(\s*)(>|\])$commentdata_comment_author_lc_deslashed_regex(<|\[)(\s*)/((\s*)a(\s*)>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 9101';
		}
	if ( preg_match( "~^((ww[w0-9]|m)\.)?$commentdata_comment_author_lc_deslashed_regex$~i", $commentdata_comment_author_url_domain_lc) && !preg_match( "~https?\://~i", $commentdata_comment_author_lc_deslashed ) ) {
		// Changed to include Trackbacks and Pingbacks in 1.1.4.4
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 9102';
		}
	if ( $commentdata_comment_author_url_lc == $commentdata_comment_author_lc_deslashed && !preg_match( "~https?\://~i", $commentdata_comment_author_url_lc ) && preg_match( "~https?\://([a-z0-9\-\.]+\.)?([a-z0-9\-_/\.\?\&\=\~\@]+)~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 9103';
		}

	// Email Filters
	// New Test with Blacklists
	if ( spamshield_email_blacklist_chk($commentdata_comment_author_email_lc) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 9200E-BL';
		}
	/*
	//spinfilelnamesdat
	if ( preg_match( "~spinfilel?namesdat([a-z0-9]*)@[a-z0-9]*\.[a-z0-9]{2,}~i", $commentdata_comment_author_email_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 9201E';
		}
	*/

	// TEST REFERRERS 1 - TO THE COMMENT PROCESSOR
	if ( strpos( $WPCommentsPostURL, $commentdata_php_self_lc ) !== false && $commentdata_referrer_lc == $WPCommentsPostURL ) {
		// Often spammers send the referrer as the URL for the wp-comments-post.php page. Nimrods.
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' REF-1-1011';
		}

	// TEST REFERRERS 2 - SPAMMERS SEARCHING FOR PAGES TO COMMENT ON
	if( !empty( $post_ref2xjs ) ) {
		$ref2xJS = addslashes( urldecode( $post_ref2xjs ) );
		$ref2xJS = str_replace( '%3A', ':', $ref2xJS );
		$ref2xJS = str_replace( ' ', '+', $ref2xJS );
		$ref2xJS = esc_url_raw( $ref2xJS );
		$ref2xJS_lc = strtolower( $ref2xJS );
		if ( preg_match( "~\.google\.co(m|\.[a-z]{2})~i", $ref2xJS ) && strpos( $ref2xJS_lc, 'leave a comment' ) !== false ) {
			// make test more robust for other versions of google & search query
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' REF-2-1021';
			}
		// add Keyword Script Here
		}
		
	//Test JS Referrer for Obvious Scraping Spambots
	if ( !empty( $post_ref2xjs ) && strpos( $post_ref2xjs_lc, 'ref2xjs' ) !== false ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' REF-2-1023';
		}

	// TEST REFERRERS 3 - TO THE PAGE BEING COMMENTED ON
	$test_fail = false;
	if ( !empty( $commentdata_referrer_lc ) && $commentdata_referrer_lc != $commentdata_comment_post_url_lc && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		// If referrer exists, make sure referrer matches page being commented on
		if ( strpos( $commentdata_referrer_lc, '?' ) !== false ) {
			$wpss_permalink_structure = get_option('permalink_structure');
			//spamshield_append_log_data( "\n".'$wpss_permalink_structure:'.$wpss_permalink_structure.' Line: '.__LINE__ );
			if ( !empty( $wpss_permalink_structure ) ) {
				$referrer_no_query = spamshield_remove_query( $commentdata_referrer_lc );
				//spamshield_append_log_data( "\n".'$referrer_no_query:'.$referrer_no_query.' Line: '.__LINE__ );
				if ( $referrer_no_query != $commentdata_comment_post_url_lc ) { $test_fail = true; }
				}
			else {
				$referrer_wp_query = spamshield_remove_query( $commentdata_referrer_lc, true );
				//spamshield_append_log_data( "\n".'$referrer_wp_query:'.$referrer_wp_query.' Line: '.__LINE__ );
				$post_url_wp_query = spamshield_remove_query( $commentdata_comment_post_url_lc, true );
				//spamshield_append_log_data( "\n".'$post_url_wp_query:'.$post_url_wp_query.' Line: '.__LINE__ );
				if ( $referrer_wp_query != $post_url_wp_query ) { $test_fail = true; }
				}
			}
		else { $test_fail = true; }
		//spamshield_append_log_data( "\n".'$test_fail:'.$test_fail.' Line: '.__LINE__ );
		if ( !empty( $test_fail ) ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' REF-3-1031';
			//spamshield_append_log_data( "\n".'$spamshield_error_code:'.$spamshield_error_code.' Line: '.__LINE__ );
			}
		}
	
	// JavaScript Off NoScript Test - JSONST - will only be sent by Scraping Spambots
	if ( $post_jsonst == 'NS1' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' JSONST-1000';
		}
	
	// Spam Network - BEGIN

	// Test User-Agents
	if ( empty( $commentdata_user_agent_lc ) ) {
		// There is no reason for a blank UA String, unless it's been altered.
		$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
		$spamshield_error_code .= ' UA1001-0';
		}
	$commentdata_user_agent_lc_word_count = spamshield_count_words($commentdata_user_agent_lc);
	if ( !empty( $commentdata_user_agent_lc ) && $commentdata_user_agent_lc_word_count < 3 ) {
		if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' || ( strpos( $commentdata_user_agent_lc, 'movabletype' ) === false && $commentdata_comment_type == 'trackback' ) ) {
		//if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' || ( strpos( $commentdata_user_agent_lc, 'movabletype' ) === false && ( $commentdata_comment_type == 'trackback' || $commentdata_comment_type == 'pingback' ) ) ) {
			// Another test for altered UA's.
			$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
			$spamshield_error_code .= ' UA1001-1';
			}
		}
	if ( strpos( $commentdata_user_agent_lc, 'libwww' ) !== false || preg_match( "~^(nutch|larbin|jakarta|java|mechanize|phpcrawl)~i", $commentdata_user_agent_lc ) ) {
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
		if ( empty( $user_http_accept ) ) {
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
		// More complex test for invalid 'HTTP_ACCEPT'
		$user_http_accept_mod_1 = preg_replace( "~([\s\;]+)~", ",", $user_http_accept );
		$user_http_accept_elements = explode( ',', $user_http_accept_mod_1 );
		$user_http_accept_elements_count = count($user_http_accept_elements);
		$i = 0;
		// The following line to prevent exploitation:
		$i_max = 20;
		while ( $i < $user_http_accept_elements_count && $i < $i_max ) {
			if ( !empty( $user_http_accept_elements[$i] ) ) {
				if ( $user_http_accept_elements[$i] == '*' ) {
					$content_filter_status = '1';
					$spamshield_error_code .= ' HA1004';
					break;
					}
				}
			$i++;
			}
	
		//Test HTTP_ACCEPT_LANGUAGE
		$user_http_accept_language = trim($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		if ( empty( $user_http_accept_language ) ) {
			$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
			$spamshield_error_code .= ' HAL1001';
			}
		if ( $user_http_accept_language == '*' ) {
			$content_filter_status = '1';
			$spamshield_error_code .= ' HAL1002';
			}
		// More complex test for invalid 'HTTP_ACCEPT_LANGUAGE'
		$user_http_accept_language_mod_1 = preg_replace( "~([\s\;]+)~", ",", $user_http_accept_language );
		$user_http_accept_language_elements = explode( ',', $user_http_accept_language_mod_1 );
		$user_http_accept_language_elements_count = count($user_http_accept_language_elements);
		$i = 0;
		// The following line to prevent exploitation:
		$i_max = 20;
		while ( $i < $user_http_accept_language_elements_count && $i < $i_max ) {
			if ( !empty( $user_http_accept_language_elements[$i] ) ) {
				if ( $user_http_accept_language_elements[$i] == '*' && strpos( $commentdata_user_agent_lc, 'links (' ) !== 0 ) {
					$content_filter_status = '1';
					$spamshield_error_code .= ' HAL1004';
					break;
					}
				}
			$i++;
			}

		//Test PROXY STATUS if option
		//Google Chrome Compression Proxy Bypass
		if ( $ip_proxy == 'PROXY DETECTED' && $ip_proxy_chrome_compression != 'TRUE' && empty( $spamshield_options['allow_proxy_users'] ) ) {
			$content_filter_status = '10';
			$spamshield_error_code .= ' PROXY1001';
			}
	
		}


	// Test IPs
	$spamshield_ip_bans = array(
		'66.60.98.1', '67.227.135.200', '74.86.148.194', '77.92.88.13', '77.92.88.27', '78.129.202.15', '78.129.202.2', '78.157.143.202', '87.106.55.101', '91.121.77.168',
		'92.241.176.200', '92.48.122.2', '92.48.122.3', '92.48.65.27', '92.241.168.216', '115.42.64.19', '116.71.33.252', '116.71.35.192', '116.71.59.69', '122.160.70.94',
		'122.162.251.167', '123.237.144.189', '123.237.144.92', '123.237.147.71', '193.37.152.242', '193.46.236.151', '193.46.236.152', '193.46.236.234', '194.44.97.14',
		);
	$spamshield_ip_bans_count = count($spamshield_ip_bans);
	if ( strpos( $commentdata_remote_addr_lc, '78.129.202.' ) === 0 || preg_match( "~^92\.48\.122\.([0-9]|[12][0-9]|3[01])$~", $commentdata_remote_addr_lc ) || strpos( $commentdata_remote_addr_lc, '116.71.' ) === 0 || preg_match( "~^123\.237\.14([47])\.~", $commentdata_remote_addr_lc ) || strpos( $commentdata_remote_addr_lc, '193.37.152.' ) === 0 || strpos( $commentdata_remote_addr_lc, '193.46.236.' ) === 0 || preg_match( "~^194\.8\.7([45])\.~", $commentdata_remote_addr_lc ) ) {
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
		$spamshield_error_code .= ' IP1002';
		}
	if ( strpos( $commentdata_remote_addr_lc, '192.168.' ) === 0 && strpos( $BlogServerIP, '192.168.' ) !== 0 && strpos( $BlogServerName, 'localhost' ) === false ) {
		$content_filter_status = '2';
		$spamshield_error_code .= ' IP1003';
		}
	// IP1004 - replace in_array test of $spamshield_ip_bans
		
	// Reverse DNS Server Tests - BEGIN
	if ( $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		// Test Reverse DNS Hosts - Do all with Reverse DNS not Remote Host
		$rev_dns_filter_data = spamshield_revdns_filter( 'comment', $content_filter_status, $ip, $reverse_dns_lc, $commentdata_comment_author_lc_deslashed, $commentdata_comment_author_email_lc );
		$content_filter_status	 = $rev_dns_filter_data['status'];
		$spamshield_error_code 	.= $rev_dns_filter_data['error_code'];
		$revdns_blacklisted 	 = $rev_dns_filter_data['blacklisted'];
		}
	// Reverse DNS Server Tests - END

	// Spam Network - END


	// Test Pingbacks and Trackbacks
	if ( $commentdata_comment_type == 'pingback' || $commentdata_comment_type == 'trackback' ) {
	
		if ( $filter_1_count >= $filter_1_trackback_limit ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T1-HT';
			}

		// $filter_200_count = spamshield_substr_count($commentdata_comment_content_lc_deslashed, '[...]  [...]');
		if ( preg_match( "~\[\.{1,3}\]\s*\[\.{1,3}\]~i", $commentdata_comment_content_lc_deslashed ) ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T200';
			}

		if ( $commentdata_comment_type == 'trackback' && strpos( $commentdata_user_agent_lc, 'wordpress' ) !== false ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T3000';
			}

		// Check to see if IP Trackback client IP matches IP of Server where link is supposedly coming from
		if ( $commentdata_comment_type == 'trackback' ) {
			$TrackbackDomain = spamshield_get_domain($commentdata_comment_author_url_lc);
			$TrackbackIP = gethostbyname($TrackbackDomain);
			if ( $commentdata_remote_addr_lc != $TrackbackIP ) {
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
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
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T1001';
				}
			}
		if ( strpos( $commentdata_user_agent_lc, 'the incutio xml-rpc php library -- wordpress/' ) !== false ) {
			$wp_ua_search_array = array( 'mu', 'wordpress-mu-' );
			$commentdata_user_agent_lc_wp = str_replace ( $wp_ua_search_array, '', $commentdata_user_agent_lc);
			$commentdata_user_agent_lc_explode = explode( '/', $commentdata_user_agent_lc_wp );
			if ( version_compare( $commentdata_user_agent_lc_explode[1], WPSS_MAX_WP_VERSION, '>' ) && $commentdata_user_agent_lc_explode[1] !='MU' ) {
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T1002';
				}
			}
		if ( strpos( $commentdata_user_agent_lc, 'wordpress/' ) === 0 ) {
			$wp_ua_search_array = array( 'mu', 'wordpress-mu-' );
			$commentdata_user_agent_lc_wp = str_replace ( $wp_ua_search_array, '', $commentdata_user_agent_lc);
			$commentdata_user_agent_lc_explode = explode( '/', $commentdata_user_agent_lc_wp );
			if ( version_compare( $commentdata_user_agent_lc_explode[1], WPSS_MAX_WP_VERSION, '>' ) && $commentdata_user_agent_lc_explode[1] !='MU' ) {
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T1003';
				}
			}

		if ( $commentdata_comment_author_deslashed == $commentdata_comment_author_lc_deslashed && preg_match( "~([a-z0-9\s\-_\.']+)~i", $commentdata_comment_author_lc_deslashed ) ) {
			// Check to see if Comment Author is lowercase. Normal blog ping Authors are properly capitalized. No brainer.
			// Added second test to only run when using standard alphabet.
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T1010';
			}
		if ( $ip_proxy == 'PROXY DETECTED' ) {
			// Check to see if Trackback/Pingback is using proxy. Real ones don't do that since they come directly from a website/server.
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T1011-FPD';
			}
		if ( $commentdata_comment_content == '[...] read more [...]' ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T1020';
			}
		if ( strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase1 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase1a ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase2 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase2a ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase3 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase3a ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase4 ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase5 ) !== false || ( strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase20a ) !== false && ( strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase20b ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase20c ) !== false ) ) ) {
			// Check to see if common patterns exist in comment content.
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2002';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong ) !== false ) {
			// Check to see if Comment Author is repeated in content, enclosed in <strong> tags.
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2003';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_a1 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_a2 ) !== false ) {
			// Check to see if Comment Author is repeated in content, enclosed in <a> tags.
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2004';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot1 ) !== false ) {
			// Check to see if Phrase... in bold is in content
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2005';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot2 ) !== false ) {
			// Check to see if Phrase... in bold is in content
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2006';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot3 ) !== false ) {
			// Check to see if Phrase... in bold is in content
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2007';
			}
		if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot4 ) !== false ) {
			// Check to see if Phrase... in bold is in content
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2007';
			}
		if ( preg_match( "~<strong>(.*)?\[trackback\](.*)?</strong>~i", $commentdata_comment_content_lc_deslashed ) ) {
			// Check to see if Phrase... in bold is in content
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$spamshield_error_code .= ' T2010';
			}
		// Check to see if keyword phrases in url match Comment Author - spammers do this to get links with desired keyword anchor text.
		// Start with url and convert to text phrase for matching against author.
		$i = 0;
		while ( $i < $KeywordURLPhrasesCount ) {
			if ( $KeywordURLPhrases[$i] == $commentdata_comment_author_lc_deslashed ) {
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }

				$spamshield_error_code .= ' T3001';
				}
			if ( $KeywordURLPhrases[$i] == $commentdata_comment_content_lc_deslashed ) {
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T3002';
				}
			$i++;
			}
		// Reverse check to see if keyword phrases in url match Comment Author. Start with author and convert to url phrases.
		$i = 0;
		while ( $i < $KeywordCommentAuthorPhraseURLVariationCount ) {
			$KeywordCommentAuthorPhrase1Version = '/'.$KeywordCommentAuthorPhrase1.$KeywordCommentAuthorPhraseURLVariation[$i];
			$KeywordCommentAuthorPhrase2Version = '/'.$KeywordCommentAuthorPhrase2.$KeywordCommentAuthorPhraseURLVariation[$i];
			$KeywordCommentAuthorPhrase3Version = '/'.$KeywordCommentAuthorPhrase3.$KeywordCommentAuthorPhraseURLVariation[$i];
			$KeywordCommentAuthorPhrase1SubStrCount = spamshield_substr_count($commentdata_comment_author_url_lc, $KeywordCommentAuthorPhrase1Version);
			$KeywordCommentAuthorPhrase2SubStrCount = spamshield_substr_count($commentdata_comment_author_url_lc, $KeywordCommentAuthorPhrase2Version);
			$KeywordCommentAuthorPhrase3SubStrCount = spamshield_substr_count($commentdata_comment_author_url_lc, $KeywordCommentAuthorPhrase3Version);
			if ( $KeywordCommentAuthorPhrase1SubStrCount >= 1 ) {
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T3003-1';
				}
			elseif ( $KeywordCommentAuthorPhrase2SubStrCount >= 1 ) {
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T3003-2';
				}
			elseif ( $KeywordCommentAuthorPhrase3SubStrCount >= 1 ) {
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T3003-3';
				}
			$i++;
			}

		// Test Comment Author 
		// Words in Comment Author Repeated in Content		
		$RepeatedTermsFilters = array('.','-',':');
		$RepeatedTermsTempPhrase = str_replace($RepeatedTermsFilters,'',$commentdata_comment_author_lc_deslashed);
		$RepeatedTermsTempPhrase = preg_replace("~\s+~", " ", $RepeatedTermsTempPhrase);
		$RepeatedTermsTest = explode(' ',$RepeatedTermsTempPhrase);
		$RepeatedTermsTestCount = count($RepeatedTermsTest);
		$RepeatedTermsInContentCount = 0;
		$i = 0;
		while ( $i < $RepeatedTermsTestCount ) {
			$RepeatedTermsInContentCount = 0;
			if ( !empty( $RepeatedTermsTest[$i] ) ) {
				$RepeatedTermsInContentCount = spamshield_substr_count( $commentdata_comment_content_lc_deslashed, $RepeatedTermsTest[$i] );
				}
			$RepeatedTermsInContentStrLength = spamshield_strlen($RepeatedTermsTest[$i]);
			if ( $RepeatedTermsInContentCount >= 6 && $RepeatedTermsInContentStrLength >= 4 ) {		
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$spamshield_error_code .= ' T9000-'.$i;
				}
			$i++;
			}
		}
	// Miscellaneous
	if ( preg_match( "~\[\.+\]\s+\[\.+\]~", $commentdata_comment_content ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 5000-1';
		}
	if ( $commentdata_comment_content == '<new comment>' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 5001';
		}
	if ( strpos( $commentdata_comment_content_lc_deslashed, 'blastogranitic atremata antiviral unteacherlike choruser coccygalgia corynebacterium reason' ) !== false ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 5002';
		}
	// "Hey this is off topic but.." Then why are you commenting? Common phrase in spam
	if ( preg_match( "~^([a-z0-9\s\.,!]{0,12})?((he.a?|h([ily]{1,2}))(\s+there)?|howdy|hello|bonjour)([\.,!])?\s+(([ily]{1,2})\s+know\s+)?th([ily]{1,2})s\s+([ily]{1,2})s\s+([a-z\s]{3,12}|somewhat|k([ily]{1,2})nd\s*of)?(of{1,2}\s+)?of{1,2}\s+top([ily]{1,2})c\s+(but|however)\s+([ily]{1,2})\s+(was\s+wonder([ily]{1,2})nn?g?\s+|need\s+some\s+adv([ily]{1,2})ce)~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 5003';
		}
	if ( preg_match( "~^th([ily]{1,2})s\s+([ily]{1,2})s\s+k([ily]{1,2})nd\s+of\s+off\s+top([ily]{1,2})c\s+but~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$spamshield_error_code .= ' 5004';
		}

	// WP Blacklist Check - BEGIN
	
	// Test WP Blacklist if option set
	
	// Before long make own blacklist function - WP's is flawed with IP's
	if ( !empty( $spamshield_options['enhanced_comment_blacklist'] ) && empty( $content_filter_status ) ) {
		if ( wp_blacklist_check($commentdata_comment_author, $commentdata_comment_author_email, $commentdata_comment_author_url, $commentdata_comment_content, $commentdata_remote_addr, $commentdata_user_agent) ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '100'; }
			$spamshield_error_code .= ' WP-BLACKLIST';
			}
		}
	// WP Blacklist Check - END
	
	// Timer End - Content Filter
	$wpss_end_time_content_filter = spamshield_microtime();
	//$wpss_total_time_content_filter = substr(($wpss_end_time_content_filter - $wpss_start_time_content_filter),0,8). " seconds";
	$wpss_total_time_content_filter = spamshield_timer( $wpss_start_time_content_filter, $wpss_end_time_content_filter );
	$commentdata['total_time_content_filter'] = $wpss_total_time_content_filter;
	
	if ( empty( $spamshield_error_code ) ) {
		$spamshield_error_code = 'No Error';
		}
	else {
		// spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
		// Timer Start - JS/Cookies Filter
		// $wpss_start_time_jsc_filter = spamshield_microtime();
		// $commentdata['start_time_jsc_filter'] = $wpss_start_time_jsc_filter;
		if( $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' && $wpss_comment_validation_js != $wpss_ck_val ) {
			$spamshield_error_code .= ' COOKIE';
			}
		// Timer End - JS/Cookies Filter
		// $wpss_end_time_jsc_filter = spamshield_microtime();
		// $commentdata['end_time_jsc_filter'] = $wpss_end_time_jsc_filter;
		$spamshield_error_code = ltrim($spamshield_error_code);
		if ( !empty( $spamshield_options['comment_logging'] ) ) {
			spamshield_log_data( $commentdata, $spamshield_error_code );
			}
		}

	//$spamshield_error_data = array( $spamshield_error_code, $blacklist_word_combo, $blacklist_word_combo_total );
	
	$commentdata['content_filter_status'] = $content_filter_status;
	
	// Original was:
	// return $content_filter_status;
	return $commentdata;
	
	// CONTENT FILTERING - END
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
	spamshield_update_session_data($spamshield_options);

	if ( !empty( $_POST['JSONST'] ) ) {
		$post_jsonst 	= $_POST['JSONST'];
		}
	else {
		$post_jsonst	= '';
		}
	if ( !empty( $_POST['ref2xJS'] ) ) {
		$post_ref2xjs 	= $_POST['ref2xJS'];
		}
	else {
		$post_ref2xjs	= '';
		}	
	$post_ref2xjs_lc 	= strtolower($post_ref2xjs);
	
	// IP / PROXY INFO - BEGIN
	$ip = $_SERVER['REMOTE_ADDR'];
	$ipBlock=explode('.',$ip);
	if ( !empty( $_SERVER['HTTP_VIA'] ) ) { $ip_proxy_via=trim($_SERVER['HTTP_VIA']); } else { $ip_proxy_via = ''; }
	$ip_proxy_via_lc=strtolower($ip_proxy_via);	
	if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { $masked_ip = trim($_SERVER['HTTP_X_FORWARDED_FOR']); } else { $masked_ip = ''; }
	$masked_ip_block=explode('.',$masked_ip);
	if ( spamshield_is_valid_ip( $masked_ip ) ) {
		$masked_ip_valid=true;
		$masked_ip_core=rtrim($masked_ip,' unknown;,');
		}
	$reverse_dns = gethostbyaddr($ip);
	if ( $reverse_dns == '.' ) { $reverse_dns_ip = '[No Data]'; } elseif ( $reverse_dns == $ip ) { $reverse_dns_ip = $ip; } else { $reverse_dns_ip = gethostbyname($reverse_dns); }
	
	if ( $reverse_dns_ip != $ip || $ip == $reverse_dns ) {
		$reverse_dns_authenticity = '[Possibly Forged]';
		} 
	else {
		$reverse_dns_authenticity = '[Verified]';
		}
	// Detect Use of Proxy
	if ( !empty( $ip_proxy_via ) || !empty( $masked_ip ) ) {
		if ( empty( $masked_ip ) ) { $masked_ip='[No Data]'; }
		$ip_proxy='PROXY DETECTED';
		$ip_proxy_short='PROXY';
		$ip_proxy_data=$ip.' | MASKED IP: '.$masked_ip;
		$proxy_status='TRUE';
		//Google Chrome Compression Check
		if ( strpos( $ip_proxy_via_lc, 'chrome compression proxy' ) !== false && preg_match( "~^google-proxy-(.*)\.google\.com$~i", $reverse_dns ) ) {
			$ip_proxy_chrome_compression='TRUE';
			}
		else {
			$ip_proxy_chrome_compression='FALSE';
			}
		}
	else {
		$ip_proxy='No Proxy';
		$ip_proxy_short=$ip_proxy;
		$ip_proxy_data='[None]';
		$proxy_status='FALSE';
		}
	// IP / PROXY INFO - END
	$text .= "\r\nBlacklist the IP Address: ".WPSS_ADMIN_URL.'/options-general.php?page=wp-spamshield/wp-spamshield.php&wpss_action=blacklist_ip&comment_ip='.$ip;
	
	$text .= "\r\n";

	if ( empty( $spamshield_options['hide_extra_data'] ) ) {
		
		$CommentType = ucwords( get_comment_type( $comment_id ) );

		$text .= "\r\n------------------------------------------------------";
		$text .= "\r\n:: Additional Technical Data Added by WP-SpamShield ::";
		$text .= "\r\n------------------------------------------------------";
		$text .= "\r\n";
		$text .= "\r\nComment Type: ".$CommentType.' ("Comment", "Pingback", or "Trackback")';
		$text .= "\r\n";
		// DEBUG ONLY - BEGIN
		if ( strpos( WPSS_SERVER_NAME_REV, WPSS_DEBUG_SERVER_NAME_REV ) === 0 ) {
			if( !empty( $post_ref2xjs ) ) {
				$ref2xJS = addslashes( urldecode( $post_ref2xjs ) );
				$ref2xJS = str_replace( '%3A', ':', $ref2xJS );
				$ref2xJS = str_replace( ' ', '+', $ref2xJS );
				$ref2xJS = esc_url_raw( $ref2xJS );
				$text .= "\r\nJS Page Referrer Check: $ref2xJS\r\n";
				}

			if( !empty( $post_jsonst ) ) {
				$JSONST = sanitize_text_field( $post_jsonst );
				$text .= "\r\nJSONST: $JSONST\r\n";
				}
			}
		// DEBUG ONLY - END
		else {
			if( !empty( $post_ref2xjs ) ) {
				$ref2xJS = addslashes( urldecode( $post_ref2xjs ) );
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
		$text .= "\r\nReverse DNS              : ".$reverse_dns;
		$text .= "\r\nReverse DNS IP           : ".$reverse_dns_ip;
		$text .= "\r\nReverse DNS Authenticity : ".$reverse_dns_authenticity;
		$text .= "\r\nProxy Info               : ".$ip_proxy;
		$text .= "\r\nProxy Data               : ".$ip_proxy_data;
		$text .= "\r\nProxy Status             : ".$proxy_status;
		if ( !empty( $ip_proxy_via ) ) {
			$text .= "\r\nHTTP_VIA                 : ".$ip_proxy_via;
			}
		if ( !empty( $masked_ip ) ) {
			$text .= "\r\nHTTP_X_FORWARDED_FOR     : ".$masked_ip;
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
	$blacklist_keys 		= trim(stripslashes(get_option('blacklist_keys')));
	$blacklist_keys_update	= $blacklist_keys."\n".$ip_to_blacklist;
	spamshield_update_blacklist_keys($blacklist_keys_update);
	}

function spamshield_update_blacklist_keys($blacklist_keys) {
	$blacklist_keys_arr		= explode("\n",$blacklist_keys);
	$blacklist_keys_arr_tmp	= spamshield_sort_unique($blacklist_keys_arr);
	$blacklist_keys			= implode("\n",$blacklist_keys_arr_tmp);
	update_option('blacklist_keys', $blacklist_keys);
	}

function spamshield_check_if_spider() {
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
	$_SESSION['wpss_spider_status_check_'.WPSS_HASH] = $spider_status_check;
	return $spider_status_check;
	}

// Admin Functions - BEGIN

function spamshield_stats() {
	
	$spamshield_count = spamshield_count();
	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);

	$install_date = $spamshield_options['install_date'];
	if ( empty( $install_date ) ) {
		$install_date = @date('Y-m-d');
		}
	$current_date = @date('Y-m-d');
	$NumDaysInstalled = spamshield_date_diff($install_date, $current_date);
	if ( $NumDaysInstalled < 1 ) {
		$NumDaysInstalled = 1;
		}
	$SpamCountSoFar = $spamshield_count;
	if ( $SpamCountSoFar < 1 ) {
		$SpamCountSoFar = 1;
		}
	@$AvgBlockedDaily = round( $SpamCountSoFar / $NumDaysInstalled );
	
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

	if ( empty( $spamshield_count ) ) {
		echo '<p>No comment spam attempts have been detected yet.'.$SpamStatInclLink;
		}
	else {
		echo '<p>'.sprintf(__("<img src='".WPSS_PLUGIN_IMG_URL."/spam-protection-24.png' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' />".' <a href="%1$s" '.$SpamStatHrefAttr.'>WP-SpamShield</a> has blocked <strong>%2$s</strong> spam comments.'), $SpamStatURL,  number_format($spamshield_count) ).'</p>'."\n";
		if ( $AvgBlockedDaily >= 2 ) {
			echo "<p><span title=\"That's ".$AvgBlockedDaily." spam comments a day that you don't have to worry about!\"><img src='".WPSS_PLUGIN_IMG_URL."/spacer.gif' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> Average spam blocked daily: <strong>".$AvgBlockedDaily."</strong></span></p>"."\n";
			}
		}
	}

function spamshield_filter_plugin_actions( $links, $file ) {
	if ( $file == WPSS_PLUGIN_BASENAME ){
		$settings_link = '<a href="options-general.php?page='.WPSS_PLUGIN_BASENAME.'">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
		}
	return $links;
	}

// Admin Functions - END

if (!class_exists('wpSpamShield')) {
    class wpSpamShield {
	
		// The name the options are saved under in the database.
		var $adminOptionsName = 'wp_spamshield_options';
		
		// The name of the database table used by the plugin
		var $db_table_name = 'wp_spamshield';
		
		//PHP 5 Constructor	
		//function __construct() {

		function wpSpamShield() {
			
			global $wpdb;
			register_activation_hook(__FILE__,array(&$this,'install_on_activation'));
			add_action('init', 'spamshield_first_action', 1);
			add_action('init', 'spamshield_register_widget');
			add_action('wp_logout', 'spamshield_end_session');
			add_action('wp_login', 'spamshield_end_session');
			add_action('admin_menu', array(&$this,'add_admin_pages'));
			add_action('wp_head', array(&$this, 'spamshield_head_intercept'));
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

		// Class Admin Functions - BEGIN
		
		function install_on_activation() {
			global $wpdb;
			$installed_ver = get_option('wp_spamshield_version');
			$spamshield_options = get_option('spamshield_options');
			spamshield_update_session_data($spamshield_options);

			if ( $installed_ver != WPSS_VERSION ) {
				update_option('wp_spamshield_version', WPSS_VERSION);
				}

			// Only run installation if not installed already
			if ( empty( $installed_ver ) || empty( $spamshield_options ) ) {
				
				// Import existing WP-SpamFree Options, only on first activation
				$wpsf_installed_ver = get_option('wp_spamfree_version');
				if ( !empty( $wpsf_installed_ver ) && empty( $installed_ver ) ) {
					$spamfree_options = get_option('spamfree_options');
					}

				// Set Initial Options
				if ( !empty( $spamshield_options ) ) {
					$spamshield_options_update = $spamshield_options;
					}
				elseif ( !empty( $spamfree_options ) ) {
					$spamshield_options_update = $spamfree_options;
					}
				else {
					// TIME
					$key_update_time = time(); // DEPRECATED
					// DATE
					$install_date = @date('Y-m-d');
					// KEY VALUES
					$wpss_key_values	= spamshield_get_key_values();
					$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
					$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
					$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
					$wpss_js_val 		= $wpss_key_values['wpss_js_val'];
					// DEFAULTS
					$spamshield_default = unserialize(WPSS_DEFAULT_VALUES);
					$spamshield_options_update = array (
						'cookie_validation_name' 				=> $wpss_ck_key,
						'cookie_validation_key' 				=> $wpss_ck_val,
						'form_validation_field_js' 				=> $wpss_js_key,
						'form_validation_key_js' 				=> $wpss_js_val,
						'cookie_get_function_name' 				=> $spamshield_default['cookie_get_function_name'],
						'cookie_set_function_name' 				=> $spamshield_default['cookie_set_function_name'],
						'cookie_delete_function_name' 			=> $spamshield_default['cookie_delete_function_name'],
						'comment_validation_function_name' 		=> $spamshield_default['comment_validation_function_name'],
						'last_key_update'						=> $key_update_time,
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
						'install_date'							=> $install_date,
						);
					}
					
				$spamshield_count = spamshield_count();
				if ( empty( $spamshield_count ) ) {
					update_option('spamshield_count', 0);
					}
				update_option('spamshield_options', $spamshield_options_update);
				update_option('ak_count_pre', get_option('akismet_spam_count'));
				// Require Author Names and Emails on Comments - Added 1.1.7
				update_option('require_name_email', '1');
				// Turn on Comment Moderation
				//update_option('comment_moderation', 1);
				//update_option('moderation_notify', 1);
				
				// Ensure Correct Permissions of IMG and JS file - BEGIN
				$installation_file_test_2 = WPSS_PLUGIN_IMG_PATH.'/img.php'; // DEPRECATED
				$installation_file_test_3 = WPSS_PLUGIN_JS_PATH.'/jscripts.php';
				clearstatcache();
				$installation_file_test_2_perm = substr(sprintf('%o', fileperms($installation_file_test_2)), -4);
				$installation_file_test_3_perm = substr(sprintf('%o', fileperms($installation_file_test_3)), -4);
				if ( $installation_file_test_2_perm < '0755' || $installation_file_test_3_perm < '0755' || !is_readable($installation_file_test_2) || !is_executable($installation_file_test_2) || !is_readable($installation_file_test_3) || !is_executable($installation_file_test_3) ) {
					@chmod( $installation_file_test_2, 0755 ); // DEPRECATED
					@chmod( $installation_file_test_3, 0755 );
					}
				// Ensure Correct Permissions of IMG and JS file - END
				}
			}

		function add_admin_pages() {
			if ( current_user_can('manage_options') ) {
				add_submenu_page("options-general.php","WP-SpamShield","WP-SpamShield",1, __FILE__, array(&$this,"output_existing_menu_sub_admin_page"));
				}
			}
		
		function output_existing_menu_sub_admin_page() {
			if ( !current_user_can('manage_options') ) {
				wp_die('Insufficient privileges!');
				}
			?>
			<div class="wrap">
			<h2>WP-SpamShield</h2>
			<?php

			$spamshield_count = spamshield_count();
			$spamshield_options = get_option('spamshield_options');
			spamshield_update_session_data($spamshield_options);
		
			$install_date = $spamshield_options['install_date'];
			if ( empty( $install_date ) ) {
				$install_date = @date('Y-m-d');
				}
			$current_date = @date('Y-m-d');
			$NumDaysInstalled = spamshield_date_diff($install_date, $current_date);
			if ( $NumDaysInstalled < 1 ) {
				$NumDaysInstalled = 1;
				}
			$SpamCountSoFar = $spamshield_count;
			if ( $SpamCountSoFar < 1 ) {
				$SpamCountSoFar = 1;
				}
			@$AvgBlockedDaily = round( $SpamCountSoFar / $NumDaysInstalled );

			$installation_plugins_get_test_1		= WPSS_PLUGIN_BASENAME; // 'wp-spamshield/wp-spamshield.php'
			$installation_file_test_0 				= WPSS_PLUGIN_FILE_PATH; // '/public_html/wp-content/plugins/wp-spamshield/wp-spamshield.php'
			
			// DEPRECATED - BEGIN
			$installation_file_test_1 			= ABSPATH.'wp-load.php'; // '/public_html/wp-load.php'
			if ( file_exists( $installation_file_test_1 ) ) { $installation_file_test_1_status = true; }
			else { $installation_file_test_1_status	= false; }
			$installation_file_test_2 			= WPSS_PLUGIN_IMG_PATH.'/img.php'; // DEPRECATED
			// DEPRECATED - END
			$installation_file_test_3 			= WPSS_PLUGIN_JS_PATH.'/jscripts.php';
			clearstatcache();
			$installation_file_test_2_perm = substr(sprintf('%o', fileperms($installation_file_test_2)), -4); // DEPRECATED
			$installation_file_test_3_perm = substr(sprintf('%o', fileperms($installation_file_test_3)), -4);
			if ( $installation_file_test_2_perm < '0755' || $installation_file_test_3_perm < '0755' || !is_readable($installation_file_test_2) || !is_executable($installation_file_test_2) || !is_readable($installation_file_test_3) || !is_executable($installation_file_test_3) ) {
				@chmod( $installation_file_test_2, 0755 ); // DEPRECATED
				@chmod( $installation_file_test_3, 0755 );
				}
			clearstatcache();
			if ( !empty( $_GET['page'] ) ) {
				$get_page = $_GET['page'];
				}
			else {
				$get_page = '';
				}
			if ( $installation_plugins_get_test_1 == $get_page && file_exists($installation_file_test_0) && !empty( $installation_file_test_1_status ) && file_exists($installation_file_test_2) && file_exists($installation_file_test_3) ) {
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

			if ( !empty( $_REQUEST['submit_wpss_general_options'] ) && current_user_can('manage_options') && check_admin_referer('wpss_general_options_nonce') ) {
				echo '<div class="updated fade"><p>Plugin Spam settings saved.</p></div>';
				}
			if ( !empty( $_REQUEST['submit_wpss_contact_options'] ) && current_user_can('manage_options') && check_admin_referer('wpss_contact_options_nonce') ) {
				echo '<div class="updated fade"><p>Plugin Contact Form settings saved.</p></div>';
				}
			if ( !empty( $_REQUEST['wpss_action'] ) ) { $wpss_action = $_REQUEST['wpss_action']; } else { $wpss_action = ''; }
			if ( $wpss_action == 'blacklist_ip' && !empty( $_REQUEST['comment_ip'] ) && current_user_can('manage_options') && empty( $_REQUEST['submit_wpss_general_options'] ) && empty( $_REQUEST['submit_wpss_contact_options'] ) ) {
				$ip_to_blacklist = trim(stripslashes($_REQUEST['comment_ip']));
				if ( spamshield_is_valid_ip( $ip_to_blacklist ) ) {
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
			spamshield_update_session_data($spamshield_options);
			
			if ( empty( $spamshield_options['install_date'] ) ) {
				$install_date = @date('Y-m-d');
				}
			else { 
				$install_date = $spamshield_options['install_date'];
				}
			if ( !empty( $_REQUEST['submitted_wpss_general_options'] ) && current_user_can('manage_options') && check_admin_referer('wpss_general_options_nonce') ) {
				if ( !empty( $_REQUEST['comment_logging'] ) && empty( $spamshield_options['comment_logging_start_date'] ) ) {
					$comment_logging_start_date = time();
					spamshield_log_reset();
					}
				elseif ( !empty( $_REQUEST['comment_logging'] ) && !empty( $spamshield_options['comment_logging_start_date'] ) ) {
					$comment_logging_start_date = $spamshield_options['comment_logging_start_date'];
					}				
				else {
					$comment_logging_start_date = 0;
					}
				
				// Validate Request Values
				if ( !empty( $_REQUEST ) ) {
					$valid_req_spamshield_options = $_REQUEST;
					}
				else {
					$valid_req_spamshield_options = array();
					}
				
				$wpss_options_general_boolean = array ( 'block_all_trackbacks', 'block_all_pingbacks', 'use_alt_cookie_method', 'use_alt_cookie_method_only', 'comment_logging', 'comment_logging_all', 'enhanced_comment_blacklist', 'allow_proxy_users', 'hide_extra_data', 'promote_plugin_link' );
				// use next once alt cookie method removed
				// $wpss_options_general_boolean = array ( 'block_all_trackbacks', 'block_all_pingbacks', 'comment_logging', 'comment_logging_all', 'enhanced_comment_blacklist', 'allow_proxy_users', 'hide_extra_data', 'promote_plugin_link' );
				
				$wpss_options_general_boolean_count = count( $wpss_options_general_boolean );
				$i = 0;
				while ( $i < $wpss_options_general_boolean_count ) {
					if ( !empty( $_REQUEST[$wpss_options_general_boolean[$i]] ) && ( $_REQUEST[$wpss_options_general_boolean[$i]] == 'on' || $_REQUEST[$wpss_options_general_boolean[$i]] == 1 || $_REQUEST[$wpss_options_general_boolean[$i]] == '1' ) ) { $valid_req_spamshield_options[$wpss_options_general_boolean[$i]] = 1; }
					else { $valid_req_spamshield_options[$wpss_options_general_boolean[$i]] = 0; }
					$i++;
					}

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
						'comment_logging_start_date'			=> $comment_logging_start_date,
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
						'install_date'							=> $install_date,
						);
				update_option('spamshield_options', $spamshield_options_update);
				$blacklist_keys_update = trim(stripslashes($_REQUEST['wordpress_comment_blacklist']));
				spamshield_update_blacklist_keys($blacklist_keys_update);
				}
			if ( !empty( $_REQUEST['submitted_wpss_contact_options'] ) && current_user_can('manage_options') && check_admin_referer('wpss_contact_options_nonce') ) {
				// Validate Request Values
				if ( !empty( $_REQUEST ) ) {
					$valid_req_spamshield_options = $_REQUEST;
					}
				else {
					$valid_req_spamshield_options = array();
					}
				$spamshield_default = unserialize(WPSS_DEFAULT_VALUES);
				$wpss_options_contact_boolean = array ( 'form_include_website', 'form_require_website', 'form_include_phone', 'form_require_phone', 'form_include_company', 'form_require_company', 'form_include_drop_down_menu', 'form_require_drop_down_menu', 'form_include_user_meta' );
				$wpss_options_contact_boolean_count = count( $wpss_options_contact_boolean );
				$i = 0;
				while ( $i < $wpss_options_contact_boolean_count ) {
					if ( !empty( $_REQUEST[$wpss_options_contact_boolean[$i]] ) && ( $_REQUEST[$wpss_options_contact_boolean[$i]] == 'on' || $_REQUEST[$wpss_options_contact_boolean[$i]] == 1 || $_REQUEST[$wpss_options_contact_boolean[$i]] == '1' ) ) { $valid_req_spamshield_options[$wpss_options_contact_boolean[$i]] = 1; }
					else { $valid_req_spamshield_options[$wpss_options_contact_boolean[$i]] = 0; }
					$i++;
					}
				
				$wpss_options_contact_text = array ( 'form_drop_down_menu_title', 'form_drop_down_menu_item_1', 'form_drop_down_menu_item_2', 'form_drop_down_menu_item_3', 'form_drop_down_menu_item_4', 'form_drop_down_menu_item_5', 'form_drop_down_menu_item_6', 'form_drop_down_menu_item_7', 'form_drop_down_menu_item_8', 'form_drop_down_menu_item_9', 'form_drop_down_menu_item_10', 'form_response_thank_you_message' );
				$wpss_options_contact_text_count = count( $wpss_options_contact_text );
				$i = 0;
				while ( $i < $wpss_options_contact_text_count ) {
					if ( !empty( $_REQUEST[$wpss_options_contact_text[$i]] ) ) { $valid_req_spamshield_options[$wpss_options_contact_text[$i]] = trim( stripslashes( $_REQUEST[$wpss_options_contact_text[$i]] ) ); }
					else { $valid_req_spamshield_options[$wpss_options_contact_text[$i]] = $spamshield_default[$wpss_options_contact_text[$i]]; }
					$i++;
					}
				
				if ( !empty( $_REQUEST['form_message_width'] ) ) {
					$form_message_width_temp = trim( stripslashes( $_REQUEST['form_message_width'] ) );
					if ( $form_message_width_temp < 40 ) {
						$form_message_width_temp = 40;
						}
					$valid_req_spamshield_options['form_message_width']	= $form_message_width_temp;
					}
				else { $valid_req_spamshield_options['form_message_width']	= $spamshield_default['form_message_width']; }

				if ( !empty( $_REQUEST['form_message_height'] ) ) {
					$form_message_height_temp = trim( stripslashes( $_REQUEST['form_message_height'] ) );
					if ( $form_message_height_temp < 5 ) {
						$form_message_height_temp = 5;
						}
					$valid_req_spamshield_options['form_message_height']	= $form_message_height_temp;
					}
				else { $valid_req_spamshield_options['form_message_height']	= $spamshield_default['form_message_height']; }

				if ( !empty( $_REQUEST['form_message_min_length'] ) ) {
					$form_message_min_length_temp = trim( stripslashes( $_REQUEST['form_message_min_length'] ) );
					if ( $form_message_min_length_temp < 15 ) {
						$form_message_min_length_temp = 15;
						}
					$valid_req_spamshield_options['form_message_min_length']	= $form_message_min_length_temp;
					}
				else { $valid_req_spamshield_options['form_message_min_length']	= $spamshield_default['form_message_min_length']; }

				if ( !empty( $_REQUEST['form_message_recipient'] ) ) {
					$form_message_recipient_temp = trim( stripslashes( $_REQUEST['form_message_recipient'] ) );
					if ( is_email( $form_message_recipient_temp ) ) {
						$valid_req_spamshield_options['form_message_recipient'] = $form_message_recipient_temp;
						}
					else { $valid_req_spamshield_options['form_message_recipient']	= get_option('admin_email'); }
					}
				else { $valid_req_spamshield_options['form_message_recipient']	= get_option('admin_email'); }

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
						'form_include_website' 					=> $valid_req_spamshield_options['form_include_website'],
						'form_require_website' 					=> $valid_req_spamshield_options['form_require_website'],
						'form_include_phone' 					=> $valid_req_spamshield_options['form_include_phone'],
						'form_require_phone' 					=> $valid_req_spamshield_options['form_require_phone'],
						'form_include_company' 					=> $valid_req_spamshield_options['form_include_company'],
						'form_require_company' 					=> $valid_req_spamshield_options['form_require_company'],
						'form_include_drop_down_menu'			=> $valid_req_spamshield_options['form_include_drop_down_menu'],
						'form_require_drop_down_menu'			=> $valid_req_spamshield_options['form_require_drop_down_menu'],
						'form_drop_down_menu_title'				=> $valid_req_spamshield_options['form_drop_down_menu_title'],
						'form_drop_down_menu_item_1'			=> $valid_req_spamshield_options['form_drop_down_menu_item_1'],
						'form_drop_down_menu_item_2'			=> $valid_req_spamshield_options['form_drop_down_menu_item_2'],
						'form_drop_down_menu_item_3'			=> $valid_req_spamshield_options['form_drop_down_menu_item_3'],
						'form_drop_down_menu_item_4'			=> $valid_req_spamshield_options['form_drop_down_menu_item_4'],
						'form_drop_down_menu_item_5'			=> $valid_req_spamshield_options['form_drop_down_menu_item_5'],
						'form_drop_down_menu_item_6'			=> $valid_req_spamshield_options['form_drop_down_menu_item_6'],
						'form_drop_down_menu_item_7'			=> $valid_req_spamshield_options['form_drop_down_menu_item_7'],
						'form_drop_down_menu_item_8'			=> $valid_req_spamshield_options['form_drop_down_menu_item_8'],
						'form_drop_down_menu_item_9'			=> $valid_req_spamshield_options['form_drop_down_menu_item_9'],
						'form_drop_down_menu_item_10'			=> $valid_req_spamshield_options['form_drop_down_menu_item_10'],
						'form_message_width' 					=> $valid_req_spamshield_options['form_message_width'],
						'form_message_height' 					=> $valid_req_spamshield_options['form_message_height'],
						'form_message_min_length' 				=> $valid_req_spamshield_options['form_message_min_length'],
						'form_message_recipient' 				=> $valid_req_spamshield_options['form_message_recipient'],
						'form_response_thank_you_message' 		=> $valid_req_spamshield_options['form_response_thank_you_message'],
						'form_include_user_meta' 				=> $valid_req_spamshield_options['form_include_user_meta'],
						'promote_plugin_link' 					=> $spamshield_options['promote_plugin_link'],
						'install_date'							=> $install_date,
						);
				update_option('spamshield_options', $spamshield_options_update);
				}
			$spamshield_options = get_option('spamshield_options');
			spamshield_update_session_data($spamshield_options);
			
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
					<label for="comment_logging">
						<input type="checkbox" id="comment_logging" name="comment_logging" <?php echo ($spamshield_options['comment_logging']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Blocked Comment Logging Mode</strong><br />Temporary diagnostic mode that logs blocked comment submissions for 7 days, then turns off automatically.<br />Log is cleared each time this feature is turned on.<br /><em>May use slightly higher server resources, so for best performance, only use when necessary. (Most websites won't notice any difference.)</em>
					</label>
					<?php
					if ( !empty( $spamshield_options['comment_logging'] ) ) {			
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
						<input type="checkbox" id="comment_logging_all" name="comment_logging_all" <?php echo ($spamshield_options['comment_logging_all']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Log All Comments</strong><br />Requires that Blocked Comment Logging Mode be engaged. Instead of only logging blocked comments, this will allow the log to capture <em>all</em> comments while logging mode is turned on. This provides more technical data for comment submissions than WordPress provides, and helps us improve the plugin.<br/>If you plan on submitting spam samples to us for analysis, it's helpful for you to turn this on, otherwise it's not necessary.</label>
					<br/>For more about this, see <a href="#wpss_configuration_log_all_comments">below</a>.<br />&nbsp;
					
					</li>
					<li>
					<label for="enhanced_comment_blacklist">
						<input type="checkbox" id="enhanced_comment_blacklist" name="enhanced_comment_blacklist" <?php echo ($spamshield_options['enhanced_comment_blacklist']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Enhanced Comment Blacklist</strong><br />Enhances WordPress's Comment Blacklist - instead of just sending comments to moderation, they will be completely blocked. Also adds a link in the comment notification emails that will let you blacklist a commenter's IP with one click.<br/>(Useful if you receive repetitive human spam or harassing comments from a particular commenter.)<br/>&nbsp;</label>					
					</li>
					<label for="wordpress_comment_blacklist">
						<?php 
						$wordpress_comment_blacklist 			= trim(stripslashes(get_option('blacklist_keys')));
						$wordpress_comment_blacklist_arr		= explode("\n",$wordpress_comment_blacklist);
						$wordpress_comment_blacklist_arr_tmp	= spamshield_sort_unique($wordpress_comment_blacklist_arr);
						$wordpress_comment_blacklist			= implode("\n",$wordpress_comment_blacklist_arr_tmp);
						?>
						<strong>Your current WordPress Comment Blacklist</strong><br/>When a comment contains any of these words in its content, name, URL, e-mail, or IP, it will be completely blocked, not just marked as spam. One word or IP per line. It is not case-sensitive and will match included words, so "press" on your blacklist will block "WordPress" in a comment.<br />
						<textarea id="wordpress_comment_blacklist" name="wordpress_comment_blacklist" cols="80" rows="8" /><?php echo $wordpress_comment_blacklist; ?></textarea><br/>
					</label>
					You can either update this list here or on the <a href="<?php echo WPSS_ADMIN_URL; ?>/options-discussion.php">WordPress Discussion Settings page</a>.<br/>&nbsp;
					<li>
					<label for="block_all_trackbacks">
						<input type="checkbox" id="block_all_trackbacks" name="block_all_trackbacks" <?php echo ($spamshield_options['block_all_trackbacks']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Disable trackbacks.</strong><br />Use if trackback spam is excessive. (Not recommended)<br />&nbsp;
					</label>
					</li>
					<li>
					<label for="block_all_pingbacks">
						<input type="checkbox" id="block_all_pingbacks" name="block_all_pingbacks" <?php echo ($spamshield_options['block_all_pingbacks']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Disable pingbacks.</strong><br />Use if pingback spam is excessive. Disadvantage is reduction of communication between blogs. (Not recommended)<br />&nbsp;
					</label>
					</li>
					<li>
					<label for="allow_proxy_users">
						<input type="checkbox" id="allow_proxy_users" name="allow_proxy_users" <?php echo ($spamshield_options['allow_proxy_users']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Allow users behind proxy servers to comment?</strong><br />Many human spammers hide behind proxies, so you can uncheck this option for extra protection. (For highest user compatibility, leave it checked.)<br/>&nbsp;</label>
					</li>
					<li>
					<label for="hide_extra_data">
						<input type="checkbox" id="hide_extra_data" name="hide_extra_data" <?php echo ($spamshield_options['hide_extra_data']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Hide extra technical data in comment notifications.</strong><br />This data is helpful if you need to submit a spam sample. If you dislike seeing the extra info, you can use this option.<br/>&nbsp;</label>					
					</li>
					<li>
					<label for="use_alt_cookie_method">
						<input type="checkbox" id="use_alt_cookie_method" name="use_alt_cookie_method" <?php echo ($spamshield_options['use_alt_cookie_method']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>M2 - Use two methods to set cookies.</strong><br />This adds a secondary non-JavaScript method to set cookies in addition to the standard JS method.<br />&nbsp;
					</label>
					</li>
					<li>
					<label for="promote_plugin_link">
						<input type="checkbox" id="promote_plugin_link" name="promote_plugin_link" <?php echo ($spamshield_options['promote_plugin_link']==true?"checked=\"checked\"":"") ?> value="1" />
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
						<input type="checkbox" id="form_include_website" name="form_include_website" <?php echo ($spamshield_options['form_include_website']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Include "Website" field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_website">
						<input type="checkbox" id="form_require_website" name="form_require_website" <?php echo ($spamshield_options['form_require_website']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Require "Website" field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_phone">
						<input type="checkbox" id="form_include_phone" name="form_include_phone" <?php echo ($spamshield_options['form_include_phone']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Include "Phone" field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_phone">
						<input type="checkbox" id="form_require_phone" name="form_require_phone" <?php echo ($spamshield_options['form_require_phone']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Require "Phone" field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_company">
						<input type="checkbox" id="form_include_company" name="form_include_company" <?php echo ($spamshield_options['form_include_company']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Include "Company" field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_company">
						<input type="checkbox" id="form_require_company" name="form_require_company" <?php echo ($spamshield_options['form_require_company']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Require "Company" field.</strong><br />&nbsp;
					</label>
					</li>					<li>
					<label for="form_include_drop_down_menu">
						<input type="checkbox" id="form_include_drop_down_menu" name="form_include_drop_down_menu" <?php echo ($spamshield_options['form_include_drop_down_menu']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Include drop-down menu select field.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_drop_down_menu">
						<input type="checkbox" id="form_require_drop_down_menu" name="form_require_drop_down_menu" <?php echo ($spamshield_options['form_require_drop_down_menu']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong>Require drop-down menu select field.</strong><br />&nbsp;
					</label>
					</li>					
					<li>
					<label for="form_drop_down_menu_title">
						<?php $FormDropDownMenuTitle = trim(stripslashes($spamshield_options['form_drop_down_menu_title'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_title" name="form_drop_down_menu_title" value="<?php if ( !empty( $FormDropDownMenuTitle ) ) { echo $FormDropDownMenuTitle; } else { echo '';} ?>" />
						<strong>Title of drop-down select menu. (Menu won't be shown if empty.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_1">
						<?php $FormDropDownMenuItem1 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_1'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_1" name="form_drop_down_menu_item_1" value="<?php if ( !empty( $FormDropDownMenuItem1 ) ) { echo $FormDropDownMenuItem1; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 1. (Menu won't be shown if empty.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_2">
						<?php $FormDropDownMenuItem2 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_2'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_2" name="form_drop_down_menu_item_2" value="<?php if ( !empty( $FormDropDownMenuItem2 ) ) { echo $FormDropDownMenuItem2; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 2. (Menu won't be shown if empty.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_3">
						<?php $FormDropDownMenuItem3 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_3'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_3" name="form_drop_down_menu_item_3" value="<?php if ( !empty( $FormDropDownMenuItem3 ) ) { echo $FormDropDownMenuItem3; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 3. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_4">
						<?php $FormDropDownMenuItem4 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_4'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_4" name="form_drop_down_menu_item_4" value="<?php if ( !empty( $FormDropDownMenuItem4 ) ) { echo $FormDropDownMenuItem4; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 4. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_5">
						<?php $FormDropDownMenuItem5 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_5'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_5" name="form_drop_down_menu_item_5" value="<?php if ( !empty( $FormDropDownMenuItem5 ) ) { echo $FormDropDownMenuItem5; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 5. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_6">
						<?php $FormDropDownMenuItem6 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_6'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_6" name="form_drop_down_menu_item_6" value="<?php if ( !empty( $FormDropDownMenuItem6 ) ) { echo $FormDropDownMenuItem6; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 6. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_7">
						<?php $FormDropDownMenuItem7 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_7'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_7" name="form_drop_down_menu_item_7" value="<?php if ( !empty( $FormDropDownMenuItem7 ) ) { echo $FormDropDownMenuItem7; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 7. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_8">
						<?php $FormDropDownMenuItem8 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_8'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_8" name="form_drop_down_menu_item_8" value="<?php if ( !empty( $FormDropDownMenuItem8 ) ) { echo $FormDropDownMenuItem8; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 8. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_9">
						<?php $FormDropDownMenuItem9 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_9'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_9" name="form_drop_down_menu_item_9" value="<?php if ( !empty( $FormDropDownMenuItem9 ) ) { echo $FormDropDownMenuItem9; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 9. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_10">
						<?php $FormDropDownMenuItem10 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_10'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_10" name="form_drop_down_menu_item_10" value="<?php if ( !empty( $FormDropDownMenuItem10 ) ) { echo $FormDropDownMenuItem10; } else { echo '';} ?>" />
						<strong>Drop-down select menu item 10. (Leave blank if not using.)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_width">
						<?php $FormMessageWidth = trim(stripslashes($spamshield_options['form_message_width'])); ?>
						<input type="text" size="4" id="form_message_width" name="form_message_width" value="<?php if ( !empty( $FormMessageWidth ) && $FormMessageWidth >= 40 ) { echo $FormMessageWidth; } else { echo '40';} ?>" />
						<strong>"Message" field width. (Minimum 40)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_height">
						<?php $FormMessageHeight = trim(stripslashes($spamshield_options['form_message_height'])); ?>
						<input type="text" size="4" id="form_message_height" name="form_message_height" value="<?php if ( !empty( $FormMessageHeight ) && $FormMessageHeight >= 5 ) { echo $FormMessageHeight; } elseif ( empty( $FormMessageHeight ) ) { echo '10'; } else { echo '5';} ?>" />
						<strong>"Message" field height. (Minimum 5, Default 10)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_min_length">
						<?php $FormMessageMinLength = trim(stripslashes($spamshield_options['form_message_min_length'])); ?>
						<input type="text" size="4" id="form_message_min_length" name="form_message_min_length" value="<?php if ( !empty( $FormMessageMinLength ) && $FormMessageMinLength >= 15 ) { echo $FormMessageMinLength; } elseif ( empty( $FormMessageWidth ) ) { echo '25'; } else { echo '15';} ?>" />
						<strong>Minimum message length (# of characters). (Minimum 15, Default 25)</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_recipient">
						<?php $FormMessageRecipient = trim(stripslashes($spamshield_options['form_message_recipient'])); ?>
						<input type="text" size="40" id="form_message_recipient" name="form_message_recipient" value="<?php if ( empty( $FormMessageRecipient ) ) { echo get_option('admin_email'); } else { echo $FormMessageRecipient; } ?>" />
						<strong>Optional: Enter alternate form recipient. Default is blog admin email.</strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_response_thank_you_message">
						<?php 
						$FormResponseThankYouMessage = trim(stripslashes($spamshield_options['form_response_thank_you_message']));
						?>
						<strong>Enter message to be displayed upon successful contact form submission.</strong><br/>Can be plain text, HTML, or an ad, etc.<br />
						<textarea id="form_response_thank_you_message" name="form_response_thank_you_message" cols="80" rows="3" /><?php if ( empty( $FormResponseThankYouMessage ) ) { echo 'Your message was sent successfully. Thank you.'; } else { echo $FormResponseThankYouMessage; } ?></textarea><br/>&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_user_meta">
						<input type="checkbox" id="form_include_user_meta" name="form_include_user_meta" <?php echo ($spamshield_options['form_include_user_meta']==true?"checked=\"checked\"":"") ?> value="1" />
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

			<p>Want to show off your spam stats on your blog and tell others about WP-SpamShield? Simply add the following code to your WordPress theme where you'd like the stats displayed: <br />&nbsp;<br /><code>&lt;?php if ( function_exists('spamshield_counter') ) { spamshield_counter(1); } ?&gt;</code><br />&nbsp;<br /> where '1' is the style. Replace the '1' with a number from 1-9 that corresponds to one of the images below that matches the style you'd like to use. To simply display text stats on your site (no graphic), replace the '1' with '0'.<br />&nbsp;<br />To add it to any page or post, add the following shortcode to the page or post where you'd like the stats displayed (using the HTML editing tab, NOT the Visual editor): <br />&nbsp;<br /><code>[spamshieldcounter style=1]</code><br />&nbsp;<br /> where '1' is the style. Replace the '1' with a number from 1-9 that corresponds to one of the images below that matches the style you'd like to use. To simply display text stats on your site (no graphic), replace the '1' with '0'.</p>

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
						
			<p><strong>Small Counter</strong><br /><br />To add smaller counter to your site, add the following code to your WordPress theme where you'd like the stats displayed: <br />&nbsp;<br /><code>&lt;?php if ( function_exists('spamshield_counter_sm') ) { spamshield_counter_sm(1); } ?&gt;</code><br />&nbsp;<br /> where '1' is the style. Replace the '1' with a number from 1-5 that corresponds to one of the images below that matches the style you'd like to use.<br />&nbsp;<br />To add it to any page or post, add the following shortcode to the page or post where you'd like the stats displayed (using the HTML editing tab, NOT the Visual editor): <br />&nbsp;<br /><code>[spamshieldcountersm style=1]</code><br />&nbsp;<br /> where '1' is the style. Replace the '1' with a number from 1-5 that corresponds to one of the images below.</p>

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

			<p><a name="wpss_configuration_blocked_comment_logging_mode"><strong>Blocked Comment Logging Mode</strong></a><br />This is a temporary diagnostic mode that logs blocked comment submissions for 7 days, then turns off automatically. If you want to see what spam has been blocked on your site, this is the option to use. Also, if you experience any technical issues, this will help with diagnosis, as you can email this log file to support if necessary. If you suspect you are having a technical issue, please turn this on right away and start logging data. Then submit a <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/support/" target="_blank">support request</a>, and we'll email you back asking to see the log file so we can help you fix whatever the issue may be. The log is cleared each time this feature is turned on, so make sure you download the file before turning it back on. Also the log is capped at 2MB for security. <em>This feature may use slightly higher server resources, so for best performance, only use when necessary. (Most websites won't notice any difference.)</em> </p>

			<p><a name="wpss_configuration_log_all_comments"><strong>Log All Comments</strong></a><br />Requires that Blocked Comment Logging Mode be engaged. Instead of only logging blocked comments, this will allow the log to capture <em>all</em> comments while logging mode is turned on. This provides more technical data for comment submissions than WordPress provides, and helps us improve the plugin. If you plan on submitting spam samples to us for analysis, it's helpful for you to turn this on, otherwise it's not necessary. If you have any spam comments that you feel WP-SpamShield should have blocked (usually human spam), then please submit a <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/support/" target="_blank">support request</a>. When we email you back we will ask you to forward the data to us by email.</p>
			
			<p>This extra data will be extremely valuable in helping us improve the spam protection capabilities of the plugin.</p>
			
			<p><a name="wpss_configuration_enhanced_comment_blacklist"><strong>Enhanced Comment Blacklist</strong></a><br />Enhances WordPress's Comment Blacklist - instead of just sending comments to moderation, they will be completely blocked if this is enabled. (Useful if you receive repetitive human spam or harassing comments from a particular commenter.) Also adds <strong>one-click blacklisting</strong> - a link will now appear in the comment notification emails that you can click to blacklist a commenter's IP. This link appears whether or not the feature is enabled. If you click the link and this feature is disabled, it will add the commenter's IP to the blacklist but blacklisting will operate according to WordPress's default functionality.</p>
			
			<p>The WP-SpamShield blacklist shares the WordPress Comment Blacklist data, but the difference is that now when a comment contains any of these words in its content, name, URL, e-mail, or IP, it will be completely blocked, not just marked as spam. One word or IP per line...add each new blacklist item on a new line. If you're not sure how to use it, start by just adding an IP address, or click on the link in one of the notification emails. It is not case-sensitive and will match included words, so "press" on your blacklist will block "WordPress" in a comment.</p>			

			<p><a name="wpss_configuration_disable_trackbacks"><strong>Disable trackbacks.</strong></a><br />Use if trackback spam is excessive. It is recommended that you don't use this option unless you are experiencing an extreme spam attack.</p>

			<p><a name="wpss_configuration_disable_pingbacks"><strong>Disable pingbacks.</strong></a><br />Use if pingback spam is excessive. The disadvantage is a reduction of communication between blogs. When blogs ping each other, it's like saying "Hi, I just wrote about you" and disabling these pingbacks eliminates that ability. It is recommended that you don't use this option unless you are experiencing an extreme spam attack.</p>

			<p><a name="wpss_configuration_allow_proxy_users"><strong>Allow users behind proxy servers to comment?</strong></a><br />Many human spammers hide behind proxies. Leaving this unchecked adds an extra layer of spam protection. In the rare event that a non-spam commenter gets blocked by this, they will be notified what the situation is, and instructed to contact you to ask you to modify this setting. (For highest user compatibility, you can leave it checked.)</p>
			
			<p><a name="wpss_configuration_hide_extra_data"><strong>Hide extra technical data in comment notifications.</strong></a><br />The plugin now addes some extra technical data to the comment moderation and notification emails, including the referrer that brought the user to the page where they commented, the referrer that brought them to the WordPress comments processing page (helps with fighting spam), User-Agent, Remote Host, Reverse DNS, Proxy Info, Browser Language, and more. This data is helpful if you ever need to <a href="http://www.redsandmarketing.com/plugins/wp-spamshield/support/" target="_blank">submit a spam sample</a>. If you dislike seeing the extra info, you can use this option to prevent the info from being displayed in the emails. If you don't mind seeing it, please leave it this unchecked, because if you ever need to submit a spam sample, it helps us track spam patterns.</p>
			
			<p><a name="wpss_configuration_m2"><strong>M2 - Use two methods to set cookies.</strong></a><br />This adds a secondary non-JavaScript method to set cookies in addition to the standard JS method.</p>
			
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

		// Class Admin Functions - END

		function spamshield_head_intercept() {
			if (!is_admin()) {
				
				// Following was in JS, but since this is immediately before that code is executed, placed here
				// May be issue with caching though, but minor
				update_option( 'ak_count_pre', get_option('akismet_spam_count') );

				$wpSpamShieldVerJS=' v'.WPSS_VERSION;
				echo "\n";
				echo '<script type="text/javascript" async defer src="'.WPSS_PLUGIN_JS_URL.'/jscripts.php"></script> '."\n";
				if ( !empty( $_SESSION['wpss_user_ip_init_'.WPSS_HASH] ) ) {
					$_SESSION['wpss_user_ip_init_'.WPSS_HASH] 	= $_SERVER['REMOTE_ADDR'];
					}
				}
			}

		
		}
	}

// Instantiate the class
if (class_exists('wpSpamShield')) {
	$wpSpamShield = new wpSpamShield();
	}

?>