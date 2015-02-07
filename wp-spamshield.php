<?php
/*
Plugin Name: WP-SpamShield
Plugin URI: http://www.redsandmarketing.com/plugins/wp-spamshield/
Description: An extremely powerful and user-friendly all-in-one anti-spam plugin that <strong>eliminates comment spam, trackback spam, contact form spam, and registration spam</strong>. No CAPTCHA's, challenge questions, or other inconvenience to website visitors. Enjoy running a WordPress site without spam! Includes a spam-blocking contact form feature.
Author: Scott Allen
Version: 1.7.4
Author URI: http://www.redsandmarketing.com/
Text Domain: wp-spamshield
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


// PLUGIN - BEGIN

/* Note to any other PHP developers reading this:
My use of the closing curly braces "}" is a little funky in that I indent them, I know. IMO it's easier to debug. Just know that it's on purpose even though it's not standard. One of my programming quirks, and just how I roll. :)
*/

// Make sure plugin remains secure if called directly
if ( !function_exists( 'add_action' ) ) {
	if ( !headers_sent() ) {
		header('HTTP/1.1 403 Forbidden');
		}
	die( 'ERROR: This plugin requires WordPress and will not function if called directly.' );
	}

define( 'WPSS_VERSION', '1.7.4' );
define( 'WPSS_REQUIRED_WP_VERSION', '3.7' );
define( 'WPSS_MAX_WP_VERSION', '5.0' );
/** Setting important URL and PATH constants so the plugin can find things
* Constants prefixed with 'RSMP_' are shared with other RSM Plugins for efficiency.
**/
if ( !defined( 'WPSS_DEBUG' ) ) 				{ define( 'WPSS_DEBUG', false ); } // Do not change value unless developer asks you to - for debugging only. Change in wp-config.php.
if ( !defined( 'RSMP_SITE_URL' ) ) 				{ define( 'RSMP_SITE_URL', untrailingslashit( site_url() ) ); }
if ( !defined( 'RSMP_CONTENT_DIR_URL' ) ) 		{ define( 'RSMP_CONTENT_DIR_URL', WP_CONTENT_URL ); }
if ( !defined( 'RSMP_CONTENT_DIR_PATH' ) ) 		{ define( 'RSMP_CONTENT_DIR_PATH', WP_CONTENT_DIR ); }
if ( !defined( 'RSMP_PLUGINS_DIR_URL' ) ) 		{ define( 'RSMP_PLUGINS_DIR_URL', WP_PLUGIN_URL ); }
if ( !defined( 'RSMP_PLUGINS_DIR_PATH' ) ) 		{ define( 'RSMP_PLUGINS_DIR_PATH', WP_PLUGIN_DIR ); }
if ( !defined( 'RSMP_ADMIN_URL' ) ) 			{ define( 'RSMP_ADMIN_URL', untrailingslashit( admin_url() ) ); }
if ( !defined( 'WPSS_PLUGIN_BASENAME' ) ) 		{ define( 'WPSS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); }
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
if ( !defined( 'WPSS_PLUGIN_LANG_PATH' ) ) 		{ define( 'WPSS_PLUGIN_LANG_PATH', WPSS_PLUGIN_PATH . '/languages' ); }
if ( !defined( 'RSMP_SERVER_IP_NODOT' ) ) {
	$wpss_server_ip_nodot = str_replace( '.', '', spamshield_get_server_addr() );
	define( 'RSMP_SERVER_IP_NODOT', $wpss_server_ip_nodot );
	}
if ( !defined( 'RSMP_HASH_ALT' ) ) {
	$wpss_alt_prefix = spamshield_md5( RSMP_SERVER_IP_NODOT );
	define( 'RSMP_HASH_ALT', $wpss_alt_prefix );
	}
if ( !defined( 'RSMP_HASH' ) ) {
	if ( defined( 'COOKIEHASH' ) ) { $wpss_hash_prefix = COOKIEHASH; } else { $wpss_hash_prefix = spamshield_md5( RSMP_SITE_URL ); }
	define( 'RSMP_HASH', $wpss_hash_prefix );
	}
if ( !defined( 'RSMP_SERVER_ADDR' ) ) 			{ define( 'RSMP_SERVER_ADDR', spamshield_get_server_addr() ); }
if ( !defined( 'RSMP_SERVER_NAME' ) ) 			{ define( 'RSMP_SERVER_NAME', spamshield_get_server_name() ); }
if ( !defined( 'RSMP_SERVER_NAME_REV' ) ) 		{ define( 'RSMP_SERVER_NAME_REV', strrev( RSMP_SERVER_NAME ) ); }
if ( !defined( 'WPSS_REF2XJS' ) ) 				{ define( 'WPSS_REF2XJS', 'ref2xJS' ); }
if ( !defined( 'RSMP_DEBUG_SERVER_NAME' ) ) 	{ define( 'RSMP_DEBUG_SERVER_NAME', '.redsandmarketing.com' ); }
if ( !defined( 'RSMP_DEBUG_SERVER_NAME_REV' ) ) { define( 'RSMP_DEBUG_SERVER_NAME_REV', strrev( RSMP_DEBUG_SERVER_NAME ) ); }
if ( !defined( 'WPSS_HOME_LINK' ) ) 			{ define( 'WPSS_HOME_LINK', 'http://www.redsandmarketing.com/plugins/wp-spamshield/' ); }
if ( !defined( 'WPSS_WP_LINK' ) ) 				{ define( 'WPSS_WP_LINK', 'http://wordpress.org/extend/plugins/wp-spamshield/' ); }
if ( !defined( 'WPSS_WP_RATING_LINK' ) ) 		{ define( 'WPSS_WP_RATING_LINK', 'https://wordpress.org/support/view/plugin-reviews/wp-spamshield?rate=5#postform' ); }
if ( !defined( 'RSMP_SERVER_SOFTWARE' ) ) 		{ define( 'RSMP_SERVER_SOFTWARE', $_SERVER['SERVER_SOFTWARE'] ); }
if ( !defined( 'RSMP_PHP_UNAME' ) ) 			{ define( 'RSMP_PHP_UNAME', @php_uname() ); }
if ( !defined( 'RSMP_PHP_VERSION' ) ) 			{ define( 'RSMP_PHP_VERSION', phpversion() ); }
/*
if ( !defined( 'RSMP_WIN_SERVER' ) ) {
	// global $is_IIS;
	$wpss_php_uname = @php_uname('s');
	if ( preg_match( "~^(cyg|u)?win~i", $wpss_php_uname ) ) { define( 'RSMP_WIN_SERVER', 'Win' ); } else { define( 'RSMP_WIN_SERVER', 'NotWin' ); }
	}
*/
if ( !defined( 'RSMP_PHP_MEM_LIMIT' ) ) {
	$wpss_php_memory_limit = spamshield_format_bytes( ini_get( 'memory_limit' ) );
	define( 'RSMP_PHP_MEM_LIMIT', $wpss_php_memory_limit );
	}
if ( !defined( 'RSMP_WP_VERSION' ) ) {
	global $wp_version;
	$wpss_wp_version = $wp_version;
	define( 'RSMP_WP_VERSION', $wpss_wp_version );
	}
if ( !defined( 'RSMP_USER_AGENT' ) ) {
	$wpss_user_agent = 'WP-SpamShield/'.WPSS_VERSION.' (WordPress/'.RSMP_WP_VERSION.') PHP/'.RSMP_PHP_VERSION.' ('.RSMP_SERVER_SOFTWARE.')';
	define( 'RSMP_USER_AGENT', $wpss_user_agent );
	}
// INCLUDE POPULAR CACHE PLUGINS HERE (12)
$popular_cache_plugins_default = array (
	'wp-super-cache',
	'w3-total-cache',
	'quick-cache',
	'hyper-cache',
	'wp-fastest-cache',
	'db-cache-reloaded-fix',
	'cachify',
	'db-cache-reloaded',
	'hyper-cache-extended',
	'wp-fast-cache',
	'lite-cache',
	'gator-cache',
	);
if ( !defined( 'WPSS_POPULAR_CACHE_PLUGINS' ) ) {
	// popular cache plugins - convert from array to constant
	define( 'WPSS_POPULAR_CACHE_PLUGINS', serialize( $popular_cache_plugins_default ) );
	}
// SET THE DEFAULT CONSTANT VALUES HERE:
$spamshield_default = array (
	'block_all_trackbacks' 				=> 0,
	'block_all_pingbacks' 				=> 0,
	'comment_logging'					=> 0,
	'comment_logging_start_date'		=> 0,
	'comment_logging_all'				=> 0,
	'enhanced_comment_blacklist'		=> 0,
	'enable_whitelist'					=> 0, // Add 'enable_whitelist' to rest of plugin
	'allow_proxy_users'					=> 1,
	'hide_extra_data'					=> 0,
	'registration_shield_disable'		=> 0,
	'registration_shield_level_1'		=> 0,
	'allow_comment_author_keywords'		=> 0,
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
	'form_response_thank_you_message'	=> __( 'Your message was sent successfully. Thank you.', WPSS_PLUGIN_NAME ),
	'form_include_user_meta'			=> 1,
	'promote_plugin_link'				=> 0,
	);
if ( !defined( 'WPSS_DEFAULT_VALUES' ) ) { define( 'WPSS_DEFAULT_VALUES', serialize( $spamshield_default ) ); }

// Includes - BEGIN
require_once( WPSS_PLUGIN_INCL_PATH.'/blacklists.php' );
// Includes - END

// Standard Functions - BEGIN

function spamshield_start_session() {
	$wpss_session_test = @session_id();
	if ( empty($wpss_session_test) && !headers_sent() ) {
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
	if ( function_exists( 'mb_strlen' ) ) { $num_chars = mb_strlen($string); } else { $num_chars = strlen($string); }
	return $num_chars;
	}

function spamshield_strtoupper($string) {
	// Use this function instead of mb_strlen for compatibility
	// BUT mb_strtoupper is superior to strtoupper, so use it whenever possible
	if ( function_exists( 'mb_strtoupper' ) ) { $uc_string = mb_strtoupper($string); } else { $uc_string = strtoupper($string); }
	return $uc_string;
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

function spamshield_md5( $string ) {
	// Use this function instead of hash for compatibility
	// BUT hash is faster than md5, so use it whenever possible
	if ( function_exists( 'hash' ) ) { $hash = hash( 'md5', $string ); } else { $hash = md5( $string );	}
	return $hash;
	}

function spamshield_microtime() {
	$mtime = microtime( true );
	return $mtime;
	}

function spamshield_timer( $start = NULL, $end = NULL, $show_seconds = false, $precision = 8 ) {
	if ( empty( $start ) || empty( $end ) ) { $start = $end = 0; }
	// $precision will default to 8 but can be set to anything - 1,2,3,4,5,etc.
	$total_time = $end - $start;
	$total_time_for = spamshield_number_format( $total_time, $precision );
	if ( !empty( $show_seconds ) ) { $total_time_for .= ' seconds'; }
	return $total_time_for;
	}

function spamshield_number_format( $number, $precision = 8 ) {
	// $precision will default to 8 but can be set to anything - 1,2,3,4,5,etc.
	if ( function_exists( 'number_format_i18n' ) ) { $number_for = number_format_i18n( $number, $precision ); } 
	else { $number_for = number_format( $number, $precision ); }
	return $number_for;
	}

function spamshield_format_bytes( $size, $precision = 2 ) {
	if ( !is_numeric($size) ) { return $size; }
    $base = log($size) / log(1024);
    $suffixes = array('', 'k', 'M', 'G', 'T');
	$formatted_num = round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    return $formatted_num;
	}

function spamshield_date_diff($start, $end) {
	$start_ts = strtotime($start);
	$end_ts = strtotime($end);
	$diff = ($end_ts-$start_ts);
	$start_array = explode('-', $start);
	$start_year = $start_array[0];
	$end_array = explode('-', $end);
	$end_year = $end_array[0];
	$years = $end_year-$start_year;
	if (($years%4) == 0) { $extra_days = ((($end_year-$start_year)/4)-1); } else { $extra_days = ((($end_year-$start_year)/4)); }
	$extra_days = round($extra_days);
	return round($diff/86400)+$extra_days;
	}

function spamshield_scandir( $dir ) {
	clearstatcache();
	$dot_files = array( '..', '.' );
	$dir_contents_raw = scandir( $dir );
	$dir_contents = array_values( array_diff( $dir_contents_raw, $dot_files ) );
	return $dir_contents;
	}

function spamshield_get_domain($url) {
	// Get domain from URL
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

function spamshield_get_query_string($url) {
	// Get query string from URL
	// Filter URLs with nothing after http
	if ( empty( $url ) || preg_match( "~^https?\:*/*$~i", $url ) ) { return ''; }
	// Fix poorly formed URLs so as not to throw errors when parsing
	$url = spamshield_fix_url($url);
	// NOW start parsing
	$parsed = parse_url($url);
	// Filter URLs with no query string
	if ( empty( $parsed['query'] ) ) { return ''; }
	$query_str = $parsed['query'];
	return $query_str;
	}

function spamshield_get_email_domain($email) {
	// Get domain from email address
	if ( empty( $email ) ) { return ''; }
	$email_elements = explode( '@', $email );
	$domain = $email_elements[1];
	return $domain;
	}

function spamshield_parse_links( $haystack, $type = 'url' ) {
	// Parse a body of content for links - extracts URLs and Anchor Text
	// $type: 'url' for URLs, 'domain' for just Domains, 'url_at' for URLs from Anchor Text Links only, 'anchor_text' for Anchor Text
	// Returns an array
	$parse_links_regex = "~(<\s*a\s+[a-z0-9\-_\.\?\='\"\:\(\)\{\}\s]*\s*href|\[(url|link))\s*\=\s*['\"]?\s*(https?\://[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)\s*['\"]?\s*[a-z0-9\-_\.\?\='\"\:;\(\)\{\}\s]*\s*(>|\])([a-z0-9àáâãäåçèéêëìíîïñńņňòóôõöùúûü\-_\/\.\?\&\=\~\@\%\+\#\:;\!,'\(\)\{\}\s]*)(<|\[)\s*\/\s*a\s*(>|(url|link)\])~iu";
	$search_http_regex ="~(?:^|\s+)(https?\://[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)(?:$|\s+)~iu";
	preg_match_all( $parse_links_regex, $haystack, $matches_links, PREG_PATTERN_ORDER );
	$parsed_links_matches 			= $matches_links[3]; // Array containing URLs parsed from Anchor Text Links in haystack text
	$parsed_anchortxt_matches		= $matches_links[5]; // Array containing Anchor Text parsed from Anchor Text Links in haystack text
	if ( $type == 'url' || $type == 'domain' ) {
		$url_haystack = preg_replace( "~\s~", ' - ', $haystack ); // Workaround Added 1.3.8
		preg_match_all( $search_http_regex, $url_haystack, $matches_http, PREG_PATTERN_ORDER );
		$parsed_http_matches 		= $matches_http[1]; // Array containing URLs parsed from haystack text
		$parsed_urls_all_raw 		= array_merge( $parsed_links_matches, $parsed_http_matches );
		$parsed_urls_all			= array_unique( $parsed_urls_all_raw );
		if ( $type == 'url' ) {
			$results = $parsed_urls_all;
			}
		elseif ( $type == 'domain' ) {
			$parsed_urls_all_domains = array();
			foreach( $parsed_urls_all as $u => $url_raw ) {
				$url = strtolower( trim( stripslashes( $url_raw ) ) );
				if ( empty( $url ) ) { continue; }
				$domain = spamshield_get_domain($url); // Add arg to strip www in function
				if ( !in_array( $domain, $parsed_urls_all_domains, true ) ) {
					$parsed_urls_all_domains[] = $domain;
					}
				}
			$results = $parsed_urls_all_domains;
			}
		}
	elseif ( $type == 'url_at' ) { // Added 1.3.8
		$results = $parsed_links_matches;
		}
	elseif ( $type == 'anchor_text' ) {
		$results = $parsed_anchortxt_matches;
		}
	return $results;
	}

function spamshield_fix_url( $url, $rem_frag = false, $rem_query = false, $rev = false ) {
	// Fix poorly formed URLs so as not to throw errors or cause problems
	// Too many forward slashes or colons after http
	$url = preg_replace( "~^(https?)\:+/+~i", "$1://", $url);
	// Too many dots
	$url = preg_replace( "~\.+~i", ".", $url);
	// Too many slashes after the domain
	$url = preg_replace( "~([a-z0-9]+)/+([a-z0-9]+)~i", "$1/$2", $url);
	// Remove fragments
	if ( !empty( $rem_frag ) && strpos( $url, '#' ) !== false ) { $url_arr = explode( '#', $query ); $url = $url_arr[0]; }
	// Remove query string completely
	if ( !empty( $rem_query ) && strpos( $url, '?' ) !== false ) { $url_arr = explode( '?', $query ); $url = $url_arr[0]; }
	// Reverse
	if ( !empty( $rev ) ) { $url = strrev($url); }
	return $url;
	}

function spamshield_get_url() {
	if ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) { $url = 'https://'; } else { $url = 'http://'; }
	$url .= RSMP_SERVER_NAME.$_SERVER['REQUEST_URI'];
	return $url;
	}

function spamshield_get_server_addr() {
	if ( !empty( $_SERVER['SERVER_ADDR'] ) ) { $server_addr = $_SERVER['SERVER_ADDR']; } else { $server_addr = getenv('SERVER_ADDR'); }
	return $server_addr;
	}

function spamshield_get_server_name() {
	if ( !empty( $_SERVER['SERVER_NAME'] ) ) { $server_name = strtolower( $_SERVER['SERVER_NAME'] ); } else { $server_name = strtolower( getenv('SERVER_NAME') ); }
	return $server_name;
	}

function spamshield_get_query_arr($url) {
	// Get array of variables from query string
	$query_str = spamshield_get_query_string($url); // 1.7.3 - Validates better
	if ( !empty( $query_str ) ) { $query_arr = explode( '&', $query_str ); } else { $query_arr = ''; }
	return $query_arr;
	}

function spamshield_remove_query( $url, $skip_wp_args = false ) {
	$query_arr = spamshield_get_query_arr($url);
	if ( empty( $query_arr ) ) { return $url; }
	$remove_args = array();
	foreach( $query_arr as $i => $query_arg ) {
		$query_arg_arr = explode( '=', $query_arg );
		$key = $query_arg_arr[0];
		//if ( !empty( $skip_wp_args ) && ( $key == 'p' || $key == 'page_id' || $key == 'cpage' ) ) { continue; }
		if ( !empty( $skip_wp_args ) && ( $key == 'p' || $key == 'page_id' ) ) { continue; } // DO NOT ADD 'cpage', only 'p' and 'page_id'!!
		$remove_args[] = $key;
		}
	$clean_url = remove_query_arg( $remove_args, $url );
	return $clean_url;
	}

function spamshield_get_user_agent( $raw = false, $lowercase = false ) {
	// Gives User-Agent with filters
	// If blank, gives an initialized var to eliminate need for testing if isset() everywhere
	// Default is sanitized
	// Added option for raw & lowercase in 1.5
	if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
		if ( !empty ( $raw ) ) {
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			}
		else {
			$user_agent = sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] );
			}
		if ( !empty ( $lowercase ) ) {
			$user_agent = strtolower( $user_agent );
			}
		}
	else { $user_agent = ''; }
	return $user_agent;
	}

function spamshield_is_valid_ip( $ip, $incl_priv_res = false, $ipv4_c_block = false ) {
	if ( !empty( $ipv4_c_block ) ) {
		//if ( preg_match( "~^(([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])?$~", $ip ) ) {
			// Valid C-Block check - checking for at least C-block or complete IP Address: '123.456.78.' or '123.456.78.90' format
		if ( preg_match( "~^(([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.){3}$~", $ip ) ) {
			// Valid C-Block check - checking for C-block: '123.456.78.' format
			return true;
			}
		else {
			return false;
			}
		}
	if ( function_exists( 'filter_var' ) ) {
		if ( empty( $incl_priv_res ) ) {
			if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) { $ip_valid = true; } else { $ip_valid = false; }
			}
		elseif ( filter_var( $ip, FILTER_VALIDATE_IP ) ) { $ip_valid = true; } else { $ip_valid = false; }
		// FILTER_FLAG_IPV4,FILTER_FLAG_IPV6,FILTER_FLAG_NO_PRIV_RANGE,FILTER_FLAG_NO_RES_RANGE
		}
	elseif ( preg_match( "~^([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])$~", $ip ) && !preg_match( "~^192\.168\.~", $ip ) ) { 
		$ip_valid = true; 
		} 
	else { 
		$ip_valid = false; 
		}
	return $ip_valid;
	}

function spamshield_get_regex_phrase( $input, $custom_delim = NULL, $flag = "N" ) {
	// Get Regex Phrase from an Array or String
	$flag_regex_arr = array( 
		"N" 			=> "(^|[\s\.])(X)($|[\s\.,\!\?\@])",
		"S" 			=> "^(X)($|[\s\.,\!\?\@])",
		"E" 			=> "(^|[\s\.])(X)$",
		"W" 			=> "^(X)$",
		"email_addr"	=> "^(X)$",
		"email_prefix" 	=> "^(X)",
		"email_domain" 	=> "\@((ww[w0-9]|m)\.)?(X)$",
		"domain" 		=> "^((ww[w0-9]|m)\.)?(X)$",
		"authorkw"		=> "(^|\s+)(X)($|\s+)",
		"atxtwrap"		=> "(<\s*a\s+[a-z0-9\-_\.\?\='\"\:\(\)\{\}\s]*\s*href|\[(url|link))\s*\=\s*['\"]?\s*(https?\:/+[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)\s*['\"]?\s*[a-z0-9\-_\.\?\='\"\:;\(\)\{\}\s]*\s*(>|\])([a-z0-9àáâãäåçèéêëìíîïñńņňòóôõöùúûü\-_\/\.\?\&\=\~\@\%\+\#\:;\!,'\(\)\{\}\s]*\s+)?(X)([a-z0-9àáâãäåçèéêëìíîïñńņňòóôõöùúûü\-_\/\.\?\&\=\~\@\%\+\#\:;\!,'\(\)\{\}\s]*\s+)?(<|\[)\s*\/\s*a\s*(>|(url|link)\])",
		// REFERENCE: Parse full html links with this:
		// $parse_links_regex = "~(<\s*a\s+[a-z0-9\-_\.\?\='\"\:\(\)\{\}\s]*\s*href|\[(url|link))\s*\=\s*['\"]?\s*(https?\:/+[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)\s*['\"]?\s*[a-z0-9\-_\.\?\='\"\:;\(\)\{\}\s]*\s*(>|\])([a-z0-9àáâãäåçèéêëìíîïñńņňòóôõöùúûü\-_\/\.\?\&\=\~\@\%\+\#\:;\!,'\(\)\{\}\s]*)(<|\[)\s*\/\s*a\s*(>|(url|link)\])~iu";
		"linkwrap"		=> "(<\s*a\s+([a-z0-9\-_\.\?\='\"\:\(\)\{\}\s]*)\s*href|\[(url|link))\s*\=\s*(['\"])?\s*https?\:/+((ww[w0-9]|m)\.)?(X)/?([a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]*)(['\"])?(>|\])",
		"httplinkwrap"	=> "(^|\b)https?\:/+((ww[w0-9]|m)\.)?(X)/?([a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]*)",
		//REFERENCE: Parse stripped http links with this:
		// $search_http_regex ="~\s+(https?\://[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)\s+~i";
		"red_str"		=> "(X)", // Red-flagged string
		"rgx_str"		=> "(X)", // Regex-ready string
		);
	if ( is_array( $input) ) {
		$regex_flag = $flag_regex_arr[$flag];
		$regex_phrase_pre_arr = array();
		foreach( $input as $i => $val ) {
			if ( $flag == "rgx_str" || $flag == "authorkw" || $flag == "atxtwrap" ) { $val_reg_pre = $val; } // Variable must come in prepped for regex (preg_quoted)
			else { $val_reg_pre = spamshield_preg_quote($val); }
			$regex_phrase_pre_arr[] = $val_reg_pre;
			}
		$regex_phrase_pre_str 	= implode( "|", $regex_phrase_pre_arr );
		$regex_phrase_str 		= preg_replace( "~X~", $regex_phrase_pre_str, $regex_flag );
		if ( !empty( $custom_delim ) ) {  $delim = $custom_delim; } else { $delim = "~"; }
		$regex_phrase = $delim.$regex_phrase_str.$delim."iu"; // UTF-8 enabled
		if ( $flag == "email_addr" || $flag == "red_str" ) {
			$regex_phrase = str_replace( '@gmail\.', '@g(oogle)?mail\.', $regex_phrase );
			}
		}
	elseif ( is_string( $input ) ) {
		$val = $input;
		$regex_flag = $flag_regex_arr[$flag];
		if ( $flag == "rgx_str" || $flag == "authorkw" || $flag == "atxtwrap" ) { $val_reg_pre = $val; } // Variable must come in prepped for regex (preg_quoted)
		else { $val_reg_pre = spamshield_preg_quote($val); }
		$regex_phrase_str 	= preg_replace( "~X~", $val_reg_pre, $regex_flag );
		if ( !empty( $custom_delim ) ) {  $delim = $custom_delim; } else { $delim = "~"; }
		$regex_phrase = $delim.$regex_phrase_str.$delim."iu"; // UTF-8 enabled
		if ( $flag == "email_addr" || $flag == "red_str" ) {
			$regex_phrase = str_replace( '@gmail\.', '@g(oogle)?mail\.', $regex_phrase );
			}
		}
	else {
		return $input;
		}
	
	return $regex_phrase;
	}

function spamshield_rubik_mod( $str, $mod = 'en', $exp = false, $del = '~' ) {
	$lft = '.!:;1234567890|abcdefghijklmnopqrstuvwxyz{}()<>~@#$%^&*?,_-+= ';
	$rgt = 'ghiJVWXyz@#$%^&*?,_-+=1234567890ABCdefGHIjklMNOpqrSTUvwxYZabcD';
	if ( $mod == 'en' ) { $mod_dat = strtr( $str, $lft, $rgt ); } else { $mod_dat = strtr( $str, $rgt, $lft ); }
	if ( !empty( $exp ) ) { $mod_dat = explode(  $del, $mod_dat ); }
	return $mod_dat;
	}

function spamshield_is_plugin_active( $plugin_name ) {
	// Using this because is_plugin_active() only works in Admin
	// ex. $plugin_name = 'commentluv/commentluv.php';
	$plugin_active_status = false;
	if ( empty( $plugin_name ) ){ return $plugin_active_status; }
	$wpss_active_plugins = get_option( 'active_plugins' );
	if ( in_array( $plugin_name, $wpss_active_plugins, true ) ) {
		$plugin_active_status = true;
		}
	return $plugin_active_status;
	}

function spamshield_error_txt( $case = 'UC' ) {
	// $case = 'def' (default - unaltered), 'UC' (uppercase)
	$default_error_txt = __( 'Error' );
	if ( $case == 'UC' ) { $error_txt = spamshield_strtoupper( $default_error_txt ); }
	else { $error_txt = $default_error_txt; }
	return $error_txt;
	}

function spamshield_doc_txt() {
	$doc_txt = __( 'Documentation', WPSS_PLUGIN_NAME );
	return $doc_txt;
	}

function spamshield_is_lang_en_us( $strict = true ) {
	// Test if site is set to use English (US) - the default - or another language/localization
	$wpss_locale = get_locale();
	if ( $strict != true ) {
		// Not strict - English, but localized translations may be in use
		if ( !empty( $wpss_locale ) && !preg_match( "~^(en(_[a-z]{2})?)?$~i", $wpss_locale ) ) { $lang_en_us = false; } else { $lang_en_us = true; }
		}
	else {
		// Strict - English (US), no translation being used
		if ( !empty( $wpss_locale ) && !preg_match( "~^(en(_us)?)?$~i", $wpss_locale ) ) { $lang_en_us = false; } else { $lang_en_us = true; }
		}
	return $lang_en_us;
	}

function spamshield_is_login_page() {
	$wpss_login_page_check = in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
    return $wpss_login_page_check;
	}

function spamshield_is_xmlrpc() {
	$xmlrpc_status = false;
	if ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) { return true; }
    return $xmlrpc_status;
	}

function spamshield_is_cron() {
	$cron_status = false;
	if ( defined('DOING_CRON') && DOING_CRON ) { return true; }
    return $cron_status;
	}

function spamshield_is_firefox() {
	$is_firefox = false;
	$user_agent = spamshield_get_user_agent( true, false );
	if ( strpos( $user_agent, 'Firefox' ) !== false && strpos( $user_agent, 'SeaMonkey' ) === false ) { return true; }
    return $is_firefox;
	}

// Standard Functions - END

function spamshield_load_languages() {
	load_plugin_textdomain( WPSS_PLUGIN_NAME, false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

function spamshield_first_action() {
	
	spamshield_start_session();
	// Add all commands after this
	
	//Add Vars Here
	$key_main_page_hits		= 'wpss_page_hits_'.RSMP_HASH;
	$key_main_pages_hist 	= 'wpss_pages_hit_'.RSMP_HASH;
	$key_main_hits_per_page	= 'wpss_pages_hit_count_'.RSMP_HASH;
	$key_first_ref			= 'wpss_referer_init_'.RSMP_HASH;
	if ( !empty( $_SERVER['HTTP_REFERER'] ) ) { $current_ref = $_SERVER['HTTP_REFERER']; } else { $current_ref=''; }
	$key_auth_hist 			= 'wpss_author_history_'.RSMP_HASH;
	$key_comment_auth 		= 'comment_author_'.RSMP_HASH;
	$key_email_hist			= 'wpss_author_email_history_'.RSMP_HASH;
	$key_auth_url_hist 		= 'wpss_author_url_history_'.RSMP_HASH;
	
	if ( empty( $_SESSION['wpss_user_ip_init_'.RSMP_HASH] ) ) {
		$_SESSION['wpss_user_ip_init_'.RSMP_HASH] 	= $_SERVER['REMOTE_ADDR'];
		}
	if ( empty( $_SESSION['wpss_user_agent_init_'.RSMP_HASH] ) ) {
		$_SESSION['wpss_user_agent_init_'.RSMP_HASH]= spamshield_get_user_agent();
		}

	$_SESSION['wpss_version_'.RSMP_HASH] 			= WPSS_VERSION;
	$_SESSION['wpss_site_url_'.RSMP_HASH_ALT] 		= RSMP_SITE_URL;
	$_SESSION['wpss_plugin_url_'.RSMP_HASH_ALT] 	= WPSS_PLUGIN_URL;
	$_SESSION['wpss_user_ip_current_'.RSMP_HASH]	= $_SERVER['REMOTE_ADDR'];
	$_SESSION['wpss_user_agent_current_'.RSMP_HASH] = spamshield_get_user_agent();
	
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

function spamshield_reset_procdat() {
	$wpss_proc_data = array( 
		'total_tracked' 			=> 0, 
		'total_wpss_time' 			=> 0, 
		'avg_wpss_proc_time' 		=> 0, 
		'total_comment_proc_time' 	=> 0, 
		'avg_comment_proc_time' 	=> 0, 
		'total_wpss_avg_tracked' 	=> 0, 
		'total_avg_wpss_proc_time' 	=> 0, 
		'avg2_wpss_proc_time' 		=> 0 
		); 
	update_option( 'spamshield_procdat', $wpss_proc_data );
	}

function spamshield_ak_accuracy_fix() {
	// Akismet's counter is (or was) taking credit for some spam blocked by WP-SpamShield - the following ensures accurate reporting.
	// The reason for this fix is that Akismet may have marked the same comment as spam, but WP-SpamShield actually kills it - with or without Akismet.
	$ak_count_pre	= get_option('ak_count_pre');
	$ak_count_post	= get_option('akismet_spam_count');
	if ($ak_count_post > $ak_count_pre) { update_option( 'akismet_spam_count', $ak_count_pre ); }
	}

// Counters - BEGIN

function spamshield_counter( $counter_option = 0 ) {
	$counter_option_max = 9;
	$counter_option_min = 1;
	$counter_spam_blocked_msg = __( 'spam blocked by WP-SpamShield', WPSS_PLUGIN_NAME );
	if ( empty ( $counter_option ) || $counter_option > $counter_option_max || $counter_option < $counter_option_min ) {
		$spamshield_count = number_format( get_option('spamshield_count') );
		echo '<a href="'.WPSS_HOME_LINK.'" style="text-decoration:none;" rel="external" title="'.spamshield_promo_text(11).'" >'.$spamshield_count.' '.$counter_spam_blocked_msg.'</a>';
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
			$server_ip_first_char = substr(RSMP_SERVER_ADDR, 0, 1);
			if ( ( $counter_option >= 1 && $counter_option <= 3 ) || ( $counter_option >= 7 && $counter_option <= 8 ) ) {
				if ( $server_ip_first_char > '5' ) {
					$spamshield_counter_title_text = spamshield_promo_text(2);
					}
				else {
					$spamshield_counter_title_text = spamshield_promo_text(3);
					}
				echo '<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_LINK.'" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >';
				echo '<span style="color:#ffffff;font-size:20px;line-height:80%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'; 
				echo '<span style="color:#ffffff;font-size:14px;line-height:20%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.spamshield_promo_text(0).'</span><br />'; 
				echo '<span style="color:#ffffff;font-size:9px;line-height:30%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.spamshield_promo_text(1).'</span>';
				echo '</a></strong>';
				}
			elseif ( $counter_option == 4 || $counter_option == 9 ) {
				if ( $server_ip_first_char > '5' ) {
					$spamshield_counter_title_text = spamshield_promo_text(4);
					}
				else {
					$spamshield_counter_title_text = spamshield_promo_text(5);
					}
				echo '<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_LINK.'" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >';
				echo '<span style="color:#000000;font-size:9px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.' '.spamshield_promo_text(0).'</span><br />'; 
				echo '</a></strong>'; 
				}
			elseif ( $counter_option == 5 ) {
				$spamshield_counter_title_text = spamshield_promo_text(6);
				echo '<strong style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_LINK.'" style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >';
				echo '<span style="color:#FEB22B;font-size:14px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'; 
				echo '</a></strong>'; 
				}
			elseif ( $counter_option == 6 ) {
				if ( $server_ip_first_char > '5' ) {
					$spamshield_counter_title_text = spamshield_promo_text(7);
					}
				else {
					$spamshield_counter_title_text = spamshield_promo_text(8);
					}
				echo '<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_LINK.'" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >';
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
	$counter_spam_blocked_msg = __( 'spam blocked by WP-SpamShield', WPSS_PLUGIN_NAME );
	if ( empty( $counter_option ) || $counter_option > $counter_option_max || $counter_option < $counter_option_min ) {
		$spamshield_count = number_format( get_option('spamshield_count') );
		$wpss_shortcode_content = '<a href="'.WPSS_HOME_LINK.'" style="text-decoration:none;" rel="external" title="'.spamshield_promo_text(11).'" >'.$spamshield_count.' '.$counter_spam_blocked_msg.'</a>'."\n";
		return $wpss_shortcode_content;
		}
	// Display Counter
	/* Implementation: <?php if ( function_exists('spamshield_counter') ) { spamshield_counter(1); } ?> */
	/* Implementation: [spamshieldcounter style=1] or [spamshieldcounter] where "style" is 0-9 */
	$spamshield_count = number_format( get_option('spamshield_count') );
	$counter_div_height = array('0','66','66','66','106','61','67','66','66','106');
	$counter_count_padding_top = array('0','11','11','11','79','14','17','11','11','79');
	
	$wpss_shortcode_content  = '';
	$wpss_shortcode_content .= '<style type="text/css">'."\n";
	$wpss_shortcode_content .= '#spamshield_counter_wrap {color:#ffffff;text-decoration:none;width:140px;}'."\n";
	$wpss_shortcode_content .= '#spamshield_counter {background:url('.WPSS_PLUGIN_COUNTER_URL.'/spamshield-counter-bg-'.$counter_option.'.png) no-repeat top left;height:'.$counter_div_height[$counter_option].'px;width:140px;overflow:hidden;border-style:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;padding-top:'.$counter_count_padding_top[$counter_option].'px;}'."\n";
	$wpss_shortcode_content .= '</style>'."\n";
	
	$wpss_shortcode_content .= '<div id="spamshield_counter_wrap" >'."\n";
	$wpss_shortcode_content .= "\t".'<div id="spamshield_counter" >'."\n";
	$server_ip_first_char = substr(RSMP_SERVER_ADDR, 0, 1);
	if ( ( $counter_option >= 1 && $counter_option <= 3 ) || ( $counter_option >= 7 && $counter_option <= 8 ) ) {
		if ( $server_ip_first_char > '5' ) {
			$spamshield_counter_title_text = spamshield_promo_text(2);
			}
		else {
			$spamshield_counter_title_text = spamshield_promo_text(3);
			}
		$wpss_shortcode_content .= "\t".'<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_LINK.'" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:20px;line-height:80%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n"; 
		$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:14px;line-height:20%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.spamshield_promo_text(0).'</span><br />'."\n"; 
		$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:9px;line-height:30%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.spamshield_promo_text(1).'</span>'."\n";
		$wpss_shortcode_content .= "\t".'</a></strong>';
		}
	elseif ( $counter_option == 4 || $counter_option == 9 ) {
		if ( $server_ip_first_char > '5' ) {
			$spamshield_counter_title_text = spamshield_promo_text(4);
			}
		else {
			$spamshield_counter_title_text = spamshield_promo_text(5);
			}
		$wpss_shortcode_content .= "\t".'<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_LINK.'" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#000000;font-size:9px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.' '.spamshield_promo_text(0).'</span><br />'."\n"; 
		$wpss_shortcode_content .= "\t".'</a></strong>'."\n"; 
		}
	elseif ( $counter_option == 5 ) {
		$wpss_shortcode_content .= "\t".'<strong style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_LINK.'" style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.spamshield_promo_text(6).'" >'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#FEB22B;font-size:14px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n"; 
		$wpss_shortcode_content .= "\t".'</a></strong>'."\n"; 
		}
	elseif ( $counter_option == 6 ) {
		if ( $server_ip_first_char > '5' ) {
			$spamshield_counter_title_text = "\t".''.spamshield_promo_text(7)."\n";
			}
		else {
			$spamshield_counter_title_text = "\t".''.spamshield_promo_text(8)."\n";
			}
		$wpss_shortcode_content .= "\t".'<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_LINK.'" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#000000;font-size:14px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n"; 
		$wpss_shortcode_content .= "\t".'</a></strong>'."\n"; 
		}
	$wpss_shortcode_content .= "\t".'</div>'."\n";
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
			$server_ip_first_char = substr(RSMP_SERVER_ADDR, 0, 1);

			if ( ( $counter_sm_option >= 1 && $counter_sm_option <= 5 ) ) {
				if ( $server_ip_first_char > '5' ) {
					$spamshield_counter_title_text = spamshield_promo_text(9);
					}
				else {
					$spamshield_counter_title_text = spamshield_promo_text(10);
					}
				echo '<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_LINK.'" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >';
				echo '<span style="color:#ffffff;font-size:18px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'; 
				echo '<span style="color:#ffffff;font-size:10px;line-height:120%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.spamshield_promo_text(0).'</span>';
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
	
	$wpss_shortcode_content  = '';
	$wpss_shortcode_content .= "\n\n";
	$wpss_shortcode_content .= '<style type="text/css">'."\n";
	$wpss_shortcode_content .= '#spamshield_counter_sm_wrap {color:#ffffff;text-decoration:none;width:120px;}'."\n";
	$wpss_shortcode_content .= '#spamshield_counter_sm {background:url('.WPSS_PLUGIN_COUNTER_URL.'/spamshield-counter-sm-bg-'.$counter_sm_option.'.png) no-repeat top left;height:'.$counter_sm_div_height[$counter_sm_option].'px;width:120px;overflow:hidden;border-style:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;padding-top:'.$counter_sm_count_padding_top[$counter_sm_option].'px;}'."\n";
	$wpss_shortcode_content .= '</style>'."\n\n";
	$wpss_shortcode_content .= '<div id="spamshield_counter_sm_wrap" >'."\n\t";
	$wpss_shortcode_content .= '<div id="spamshield_counter_sm" >'."\n";
	
	$server_ip_first_char = substr(RSMP_SERVER_ADDR, 0, 1);

	if ( ( $counter_sm_option >= 1 && $counter_sm_option <= 5 ) ) {
		if ( $server_ip_first_char > '5' ) {
			$spamshield_counter_title_text = spamshield_promo_text(9);
			}
		else {
			$spamshield_counter_title_text = spamshield_promo_text(10);
			}
		$wpss_shortcode_content .= "\t".'<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_LINK.'" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:18px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n"; 
		$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:10px;line-height:120%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.spamshield_promo_text(0).'</span>'."\n";
		$wpss_shortcode_content .= "\t".'</a></strong>'."\n"; 
		}
	$wpss_shortcode_content .= "\t".'</div>'."\n";
	$wpss_shortcode_content .= '</div>'."\n";
	
	return $wpss_shortcode_content;
	}

// Widget
function spamshield_load_widgets() {
	register_widget( 'WP_SpamShield_Widget' );
	}
	
class WP_SpamShield_Widget extends WP_Widget {

	function WP_SpamShield_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'spamshield-widget-counter-sm', 'description' => __( 'Show how much spam is being blocked by WP-SpamShield.', WPSS_PLUGIN_NAME ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => null, 'height' => null, 'id_base' => 'spamshield_widget_counter_sm' );

		/* Create the widget. */
		$this->WP_Widget( 'spamshield_widget_counter_sm', __('Spam', WPSS_PLUGIN_NAME), $widget_ops, $control_ops );
		}

	function widget( $args, $instance ) {
		extract( $args );
		$widget_title = __( 'Spam', WPSS_PLUGIN_NAME );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title (before and after defined by themes). */
		echo $before_title . $widget_title . $after_title;
		
		/* Display the spam counter. */
		spamshield_counter_sm();

		/* After widget (defined by themes). */
		echo $after_widget;
		}

	}

// Counters - END
	
function spamshield_log_reset( $ip = NULL ) {
	// $ip optional - will override detecting current user IP
	
	if ( empty( $ip ) && !empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
		}
	
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
		// IF BOM needs to be added to log, do it here.
		}
	if ( file_exists( $wpss_htaccess_file ) && file_exists( $wpss_htaccess_empty_file ) ) {
		@copy( $wpss_htaccess_empty_file, $wpss_htaccess_file );
		}
	if ( !empty( $ip ) ) {
		$wpss_htaccess_http_host = str_replace( '.', '\.', $_SERVER['HTTP_HOST'] );
		$wpss_htaccess_blog_url = str_replace( '.', '\.', RSMP_SITE_URL );
		if ( !empty( $wpss_htaccess_blog_url ) ) {
			$wpss_htaccess_data  = "SetEnvIfNoCase Referer ".RSMP_ADMIN_URL."/ wpss_access\n";
			}
		$wpss_htaccess_data .= "SetEnvIf Remote_Addr ^".$ip."$ wpss_access\n\n";
		$wpss_htaccess_data .= "<Files temp-comments-log.txt>\n";
		$wpss_htaccess_data .= "order deny,allow\n";
		$wpss_htaccess_data .= "deny from all\n";
		$wpss_htaccess_data .= "allow from env=wpss_access\n";
		$wpss_htaccess_data .= "</Files>\n";
		}
	@$wpss_htaccess_fp = fopen( $wpss_htaccess_file,'a+' );
	@fwrite( $wpss_htaccess_fp, $wpss_htaccess_data );
	@fclose( $wpss_htaccess_fp );
	//spamshield_reset_procdat();
	}

function spamshield_update_session_data( $spamshield_options, $extra_data = NULL ) {
	//$_SESSION['wpss_spamshield_options_'.RSMP_HASH] 	= $spamshield_options;
	$_SESSION['wpss_version_'.RSMP_HASH] 				= WPSS_VERSION;
	$_SESSION['wpss_site_url_'.RSMP_HASH_ALT] 			= RSMP_SITE_URL;
	$_SESSION['wpss_plugin_url_'.RSMP_HASH_ALT] 		= WPSS_PLUGIN_URL;
	$_SESSION['wpss_user_ip_current_'.RSMP_HASH]		= $_SERVER['REMOTE_ADDR'];
	$_SESSION['wpss_user_agent_current_'.RSMP_HASH] 	= spamshield_get_user_agent();
	// First Referrer - Where Visitor Entered Site
	if ( !empty( $_SERVER['HTTP_REFERER'] ) && empty( $_SESSION['wpss_referer_init_'.RSMP_HASH] ) ) {
		$_SESSION['wpss_referer_init_'.RSMP_HASH] = $_SERVER['HTTP_REFERER'];
		}	
	}

function spamshield_get_key_values() {
	// Set Cookie & JS Values - BEGIN
	$wpss_session_id = @session_id();
	//CK
	$wpss_ck_key_phrase 	= 'wpss_ckkey_'.RSMP_SERVER_IP_NODOT.'_'.$wpss_session_id;
	$wpss_ck_val_phrase 	= 'wpss_ckval_'.RSMP_SERVER_IP_NODOT.'_'.$wpss_session_id;
	$wpss_ck_key 			= spamshield_md5( $wpss_ck_key_phrase );
	$wpss_ck_val 			= spamshield_md5( $wpss_ck_val_phrase );
	//JS
	$wpss_js_key_phrase 	= 'wpss_jskey_'.RSMP_SERVER_IP_NODOT.'_'.$wpss_session_id;
	$wpss_js_val_phrase 	= 'wpss_jsval_'.RSMP_SERVER_IP_NODOT.'_'.$wpss_session_id;
	$wpss_js_key 			= spamshield_md5( $wpss_js_key_phrase );
	$wpss_js_val 			= spamshield_md5( $wpss_js_val_phrase );
	// Set Cookie & JS Values - END
	$wpss_key_values = array(
		'wpss_ck_key'	=> $wpss_ck_key,
		'wpss_ck_val' 	=> $wpss_ck_val,
		'wpss_js_key' 	=> $wpss_js_key,
		'wpss_js_val' 	=> $wpss_js_val,
		);
	return $wpss_key_values;
	}

function spamshield_append_log_data( $str = NULL, $rsds_only = false ) {
	// Adds data to the log for debugging - only use when Debugging - Use with WP_DEBUG & WPSS_DEBUG
	/*
	* Example:
	* spamshield_append_log_data( "\n".'$wpss_example_variable: "'.$wpss_example_variable.'" Line: '.__LINE__, false );
	*/
	if ( WP_DEBUG === true && WPSS_DEBUG === true ) {
		if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) {
			$wpss_log_str = 'WP-SpamShield DEBUG: '.str_replace("\n", "", $str);
			error_log( $wpss_log_str, 0 ); // Logs to debug.log
			return;
			}
		elseif ( !empty( $rsds_only ) ) {
			return;
			}
		$wpss_log_str = 'WP-SpamShield DEBUG: '.str_replace("\n", "", $str);
		error_log( $wpss_log_str, 0 ); // Logs to debug.log
		}
	}

function spamshield_get_log_session_data() {
	$noda 					= '[No Data]';
	$key_total_page_hits	= 'wpss_page_hits_js_'.RSMP_HASH;
	$key_last_ref			= 'wpss_jscripts_referer_last_'.RSMP_HASH;
	$key_pages_hist 		= 'wpss_jscripts_referers_history_'.RSMP_HASH;
	$key_hits_per_page		= 'wpss_jscripts_referers_history_count_'.RSMP_HASH;
	$key_ip_hist 			= 'wpss_jscripts_ip_history_'.RSMP_HASH;
	$key_init_ip			= 'wpss_user_ip_init_'.RSMP_HASH;
	$key_init_mt			= 'wpss_time_init_'.RSMP_HASH;
	$key_init_dt			= 'wpss_timestamp_init_'.RSMP_HASH;
	$key_first_ref			= 'wpss_referer_init_'.RSMP_HASH;
	$key_auth_hist 			= 'wpss_author_history_'.RSMP_HASH;
	$key_comment_auth 		= 'comment_author_'.RSMP_HASH;
	$key_email_hist 		= 'wpss_author_email_history_'.RSMP_HASH;
	$key_comment_email		= 'comment_author_email_'.RSMP_HASH;
	$key_auth_url_hist 		= 'wpss_author_url_history_'.RSMP_HASH;
	$key_comment_url		= 'comment_author_url_'.RSMP_HASH;
	$key_comment_acc		= 'wpss_comments_accepted_'.RSMP_HASH;
	$key_comment_den		= 'wpss_comments_denied_'.RSMP_HASH;
	$key_comment_stat_curr	= 'wpss_comments_status_current_'.RSMP_HASH;
	$key_append_log_data	= 'wpss_append_log_data_'.RSMP_HASH;
	$wpss_session_id 		= @session_id();
	$wpss_session_name 		= @session_name();
	if ( empty( $wpss_session_name ) ) { $wpss_session_name = 'PHPSESSID'; }
	if ( !empty( $_COOKIE[$wpss_session_name] ) ) {
		$wpss_session_ck = $_COOKIE[$wpss_session_name];
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
	if ( !empty( $_SESSION[$key_init_mt] ) ) { $wpss_time_init = $_SESSION[$key_init_mt]; } else { $wpss_time_init = $noda; }
	$ck_key_init_dt = 'NCS_INENTIM'; //Initial Entry Time - Cookie is backup to session var
	if ( !empty( $_COOKIE[$ck_key_init_dt] ) ) {
		$wpss_ck_timestamp_init_int = (int) $_COOKIE[$ck_key_init_dt];
		}
	else { $wpss_ck_timestamp_init_int = false; }
	$wpss_current_time_minus_1hr = intval(time()-3600);
	if ( !empty( $_SESSION[$key_init_dt] ) ) {
		$wpss_timestamp_init = $_SESSION[$key_init_dt];
		}
	elseif ( !empty( $_COOKIE[$ck_key_init_dt] ) && $_COOKIE[$ck_key_init_dt] == $wpss_ck_timestamp_init_int && $wpss_ck_timestamp_init_int >= $wpss_current_time_minus_1hr ) {
		$wpss_timestamp_init = $_COOKIE[$ck_key_init_dt];
		}
	else { $wpss_timestamp_init = $noda; }
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
		'wpss_session_name'				=> $wpss_session_name,
		'wpss_session_ck'				=> $wpss_session_ck,
		'wpss_session_verified'			=> $wpss_session_verified,
		'wpss_page_hits'				=> $wpss_page_hits,
		'wpss_last_page_hit'			=> $wpss_last_page_hit,
		'wpss_pages_history'			=> $wpss_pages_history,
		'wpss_hits_per_page'			=> $wpss_hits_per_page,
		'wpss_user_ip_init'				=> $wpss_user_ip_init,
		'wpss_ip_history'				=> $wpss_ip_history,
		'wpss_time_init'				=> $wpss_time_init,
		'wpss_timestamp_init'			=> $wpss_timestamp_init,
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

	$noda = '[No Data]';
	$wpss_display_name = ''; $wpss_user_firstname = ''; $wpss_user_lastname = ''; $wpss_user_email = ''; $wpss_user_url = ''; $wpss_user_login = ''; $wpss_user_id = '';
	$wpss_user_logged_in 		= false;
	if ( is_user_logged_in() ) {
		global $current_user;  
		get_currentuserinfo();
		$wpss_display_name 		= $current_user->display_name;
		$wpss_user_firstname 	= $current_user->user_firstname;
		$wpss_user_lastname 	= $current_user->user_lastname;
		$wpss_user_email		= $current_user->user_email;
		$wpss_user_url			= $current_user->user_url;
		$wpss_user_login 		= $current_user->user_login;
		$wpss_user_id	 		= $current_user->ID;
		$wpss_user_logged_in	= true;
		}

	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);

	$wpss_log_session_data 			= spamshield_get_log_session_data();
	$wpss_session_id 				= $wpss_log_session_data['wpss_session_id'];
	$wpss_session_name 				= $wpss_log_session_data['wpss_session_name'];
	$wpss_session_ck 				= $wpss_log_session_data['wpss_session_ck'];
	$wpss_session_verified 			= $wpss_log_session_data['wpss_session_verified'];
	$wpss_page_hits 				= $wpss_log_session_data['wpss_page_hits'];
	$wpss_last_page_hit 			= $wpss_log_session_data['wpss_last_page_hit'];
	$wpss_pages_history 			= $wpss_log_session_data['wpss_pages_history'];
	$wpss_hits_per_page 			= $wpss_log_session_data['wpss_hits_per_page'];
	$wpss_user_ip_init 				= $wpss_log_session_data['wpss_user_ip_init'];
	$wpss_ip_history 				= $wpss_log_session_data['wpss_ip_history'];
	$wpss_time_init 				= $wpss_log_session_data['wpss_time_init'];
	$wpss_timestamp_init 			= $wpss_log_session_data['wpss_timestamp_init'];
	$wpss_referer_init 				= $wpss_log_session_data['wpss_referer_init'];
	$wpss_author_history 			= $wpss_log_session_data['wpss_author_history'];
	$wpss_author_email_history 		= $wpss_log_session_data['wpss_author_email_history'];
	$wpss_author_url_history 		= $wpss_log_session_data['wpss_author_url_history'];
	$wpss_comments_accepted 		= $wpss_log_session_data['wpss_comments_accepted'];
	$wpss_comments_denied 			= $wpss_log_session_data['wpss_comments_denied'];
	$wpss_comments_status_current	= $wpss_log_session_data['wpss_comments_status_current'];
	$wpss_append_log_data			= $wpss_log_session_data['wpss_append_log_data'];
	
	$wpss_active_plugins_arr 		= get_option( 'active_plugins' );
	$wpss_active_plugins			= implode( ', ', $wpss_active_plugins_arr );
	$wpss_cl_active					= spamshield_is_plugin_active( 'commentluv/commentluv.php' );
	
	$wpss_time_end					= spamshield_microtime();
	if ( $wpss_time_init != $noda ) {
		$wpss_time_on_site			= spamshield_timer( $wpss_time_init, $wpss_time_end, true, 2 );
		}
	else { $wpss_time_on_site 		= $noda; }
	if ( $wpss_timestamp_init != $noda ) {
		$wpss_site_entry_time		= get_date_from_gmt( @date( 'Y-m-d H:i:s', $wpss_timestamp_init ), 'Y-m-d (D) H:i:s e' ); // Added 1.7.3
		}
	else { $wpss_site_entry_time 	= $noda; }
	
	$comment_logging 				= $spamshield_options['comment_logging'];
	$comment_logging_start_date 	= $spamshield_options['comment_logging_start_date'];
	$comment_logging_all 			= $spamshield_options['comment_logging_all'];
	if ( !empty( $wpss_log_comment_data_array['javascript_page_referrer'] ) ) {
		$wpss_javascript_page_referrer = $wpss_log_comment_data_array['javascript_page_referrer'];
		}
	else { $wpss_javascript_page_referrer = ''; }
	//$wpss_php_page_referrer 		= $wpss_log_comment_data_array['php_page_referrer'];
	if ( !empty( $wpss_log_comment_data_array['jsonst'] ) ) {
		$wpss_jsonst		 		= $wpss_log_comment_data_array['jsonst'];
		}
	else { $wpss_jsonst = ''; }
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
	if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) { $reset_interval = $reset_interval * 4; }
	// $time_threshold = $get_current_time - ( 60 * $reset_interval_minutes * $reset_interval_hours ); // seconds * minutes * hours
	$time_threshold = $get_current_time - $reset_interval; // seconds * minutes * hours
	// This turns off if over x amount of time since starting, or filesize exceeds max
	if ( ( !empty( $comment_logging_start_date ) && $time_threshold > $comment_logging_start_date ) || ( file_exists( $wpss_log_file ) && filesize( $wpss_log_file ) >= $wpss_log_max_filesize ) ) {
		//spamshield_log_reset();
		$comment_logging = $comment_logging_start_date = $comment_logging_all = 0;
		$spamshield_options_update = array (
			'cookie_validation_name' 				=> $spamshield_options['cookie_validation_name'],
			'cookie_validation_key' 				=> $spamshield_options['cookie_validation_key'],
			'form_validation_field_js' 				=> $spamshield_options['form_validation_field_js'],
			'form_validation_key_js' 				=> $spamshield_options['form_validation_key_js'],
			'block_all_trackbacks' 					=> $spamshield_options['block_all_trackbacks'],
			'block_all_pingbacks' 					=> $spamshield_options['block_all_pingbacks'],
			'comment_logging'						=> $comment_logging,
			'comment_logging_start_date'			=> $comment_logging_start_date,
			'comment_logging_all'					=> $comment_logging_all,
			'enhanced_comment_blacklist'			=> $spamshield_options['enhanced_comment_blacklist'],
			'enable_whitelist'						=> $spamshield_options['enable_whitelist'],
			'allow_proxy_users'						=> $spamshield_options['allow_proxy_users'],
			'hide_extra_data'						=> $spamshield_options['hide_extra_data'],
			'registration_shield_disable'			=> $spamshield_options['registration_shield_disable'],
			'registration_shield_level_1'			=> $spamshield_options['registration_shield_level_1'],
			'allow_comment_author_keywords'			=> $spamshield_options['allow_comment_author_keywords'],
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
		update_option( 'spamshield_options', $spamshield_options_update );
		}
	else {
		// LOG DATA
		$wpss_log_datum = @date('Y-m-d (D) H:i:s',$get_current_time_display);
		$wpss_log_comment_data = "*************************************************************************************\n";
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= ":: ".$wpss_log_comment_type_display." BEGIN ::"."\n";
		
		$submitter_ip_address = $_SERVER['REMOTE_ADDR'];
		$submitter_ip_address_short_l = trim( substr( $submitter_ip_address, 0, 6) );
		$submitter_ip_address_short_r = trim( substr( $submitter_ip_address, -6, 2) );
		$submitter_ip_address_obfuscated = $submitter_ip_address_short_l.'****'.$submitter_ip_address_short_r.'.***';

		// IP / PROXY INFO - BEGIN
		global $wpss_ip_proxy_info;
		if ( empty( $wpss_ip_proxy_info ) ) {
			$wpss_ip_proxy_info 		= spamshield_ip_proxy_info();
			}
		$ip_proxy_info					= $wpss_ip_proxy_info;
		$ip 							= $ip_proxy_info['ip'];
		$reverse_dns 					= $ip_proxy_info['reverse_dns'];
		$reverse_dns_lc 				= $ip_proxy_info['reverse_dns_lc'];
		$reverse_dns_lc_regex 			= $ip_proxy_info['reverse_dns_lc_regex'];
		$reverse_dns_lc_rev 			= $ip_proxy_info['reverse_dns_lc_rev'];
		$reverse_dns_lc_rev_regex		= $ip_proxy_info['reverse_dns_lc_rev_regex'];
		$reverse_dns_ip 				= $ip_proxy_info['reverse_dns_ip'];
		$reverse_dns_ip_regex 			= $ip_proxy_info['reverse_dns_ip_regex'];
		$reverse_dns_authenticity 		= $ip_proxy_info['reverse_dns_authenticity'];
		$ip_proxy_via 					= $ip_proxy_info['ip_proxy_via'];
		$ip_proxy_via_lc 				= $ip_proxy_info['ip_proxy_via_lc'];
		$masked_ip 						= $ip_proxy_info['masked_ip'];
		$ip_proxy 						= $ip_proxy_info['ip_proxy'];
		$ip_proxy_short 				= $ip_proxy_info['ip_proxy_short'];
		$ip_proxy_data 					= $ip_proxy_info['ip_proxy_data'];
		$proxy_status 					= $ip_proxy_info['proxy_status'];
		$ip_proxy_chrome_compression	= $ip_proxy_info['ip_proxy_chrome_compression'];
		// IP / PROXY INFO - END
		
		/*
		if ( RSMP_WIN_SERVER == 'Win' ) {
			$wpss_win_server = RSMP_WIN_SERVER;
			$wpss_win_server_disp = $wpss_win_server.'; ';
			}
		else {
			$wpss_win_server = '';
			$wpss_win_server_disp = '';
			}
		*/
		
		if ( $wpss_log_comment_type == 'comment' ) {
			$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
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
			$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
			$wpss_log_comment_data .= "Comment Author: 	['".$wpss_log_comment_data_array['comment_author']."']\n";
			$wpss_log_comment_data .= "Comment Author Email: 	['".$comment_author_email."']\n";
			$wpss_log_comment_data .= "Comment Author URL: 	['".$wpss_log_comment_data_array['comment_author_url']."']\n";
			$wpss_log_comment_data .= "Comment Content: "."\n['comment_content_begin']\n".$wpss_log_comment_data_array['comment_content']."\n['comment_content_end']\n";

			}
		elseif ( $wpss_log_comment_type == 'register' ) {
			$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
			$wpss_log_comment_data .= "Date/Time: 		['".$wpss_log_datum."']\n";
			if ( empty( $wpss_log_comment_data_array['ID'] ) ) { $wpss_log_comment_data_array['ID'] = '[None]'; }
			$wpss_log_comment_data .= "User ID: 		['".$wpss_log_comment_data_array['ID']."']\n";
			$wpss_log_comment_data .= "User Login: 		['".$wpss_log_comment_data_array['user_login']."']\n";
			$wpss_log_comment_data .= "Display Name: 		['".$wpss_log_comment_data_array['display_name']."']\n";
			$wpss_log_comment_data .= "First Name: 		['".$wpss_log_comment_data_array['user_firstname']."']\n";
			$wpss_log_comment_data .= "Last Name: 		['".$wpss_log_comment_data_array['user_lastname']."']\n";
			$wpss_log_comment_data .= "User Email: 		['".$wpss_log_comment_data_array['user_email']."']\n";
			if ( empty( $wpss_log_comment_data_array['user_url'] ) ) { $wpss_log_comment_data_array['user_url'] = '[None]'; }
			$wpss_log_comment_data .= "User URL: 		['".$wpss_log_comment_data_array['user_url']."']\n";
			}
		elseif ( $wpss_log_comment_type == 'contact form' ) {
			$wpss_log_comment_data .= "Date/Time: 		['".$wpss_log_datum."']\n";	
			$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
			$wpss_log_comment_data .= $wpss_log_contact_form_data;
			}
		$wpss_sessions_enabled = isset( $_SESSION ) ? 'Enabled' : 'Disabled';
		
		if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
			$wpss_http_accept_language 	= sanitize_text_field($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			} else { $wpss_http_accept_language = ''; }
		if ( !empty( $_SERVER['HTTP_ACCEPT'] ) ) {
			$wpss_http_accept 			= sanitize_text_field($_SERVER['HTTP_ACCEPT']);
			} else { $wpss_http_accept 	= ''; }
		$wpss_http_user_agent 			= spamshield_get_user_agent();
		if ( !empty( $_SERVER['HTTP_REFERER'] ) ) {
			$wpss_http_referer 			= esc_url_raw($_SERVER['HTTP_REFERER']);
			} else { $wpss_http_referer = ''; }	
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		if ( $wpss_user_logged_in != false ) {
			$wpss_log_comment_data .= "User ID: 		['".$wpss_user_id."']\n";
			}
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
		if ( $wpss_log_comment_type == 'register' ) {
			$wpss_log_comment_data .= $wpss_log_comment_type_ucwords_ref_disp." Processor Ref: ['";
			}
		else {
			$wpss_log_comment_data .= $wpss_log_comment_type_ucwords_ref_disp." Processor Ref: 	['";
			}
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
		if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) {
			if ( !empty( $_SESSION ) ) { 	$wpss_log_data_serial_session	= serialize($_SESSION); }	else { $wpss_log_data_serial_session = ''; }
			if ( !empty( $_COOKIE ) ) { 	$wpss_log_data_serial_cookie 	= serialize($_COOKIE); }	else { $wpss_log_data_serial_cookie = ''; }
			if ( !empty( $_GET ) ) { 		$wpss_log_data_serial_get 		= serialize($_GET); }		else { $wpss_log_data_serial_get = ''; }
			if ( !empty( $_POST ) ) { 		$wpss_log_data_serial_post 		= serialize($_POST); } 		else { $wpss_log_data_serial_post = ''; }
			if ( !empty( $_SERVER['REQUEST_METHOD'] ) ) { $wpss_server_request_method = $_SERVER['REQUEST_METHOD']; } else { $wpss_server_request_method = ''; }
			$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
			$wpss_log_comment_data .= "PHP Session ID: 	['".$wpss_session_id."']\n";
			$wpss_log_comment_data .= "PHP Session Cookie: 	['".$wpss_session_ck."']\n";
			$wpss_log_comment_data .= "Sess ID/CK Match: 	['".$wpss_session_verified."']\n";
			$wpss_log_comment_data .= "Page Hits: 		['".$wpss_page_hits."']\n";
			$wpss_log_comment_data .= "Last Page Hit: 		['".$wpss_last_page_hit."']\n";
			$wpss_log_comment_data .= "Hits Per Page: "."\n['hits_per_page_begin']".$wpss_hits_per_page."['hits_per_page_end']\n";
			$wpss_log_comment_data .= "Original IP: 		['".$wpss_user_ip_init."']\n";
			$wpss_log_comment_data .= "IP History: 		['".$wpss_ip_history."']\n";
			$wpss_log_comment_data .= "Time on Site: 		['".$wpss_time_on_site."']\n";
			$wpss_log_comment_data .= "Site Entry Time: 	['".$wpss_site_entry_time."']\n"; // Added 1.7.3
			$wpss_log_comment_data .= "Original Referrer: 	['".$wpss_referer_init."']\n";
			$wpss_log_comment_data .= "Author History:		['".$wpss_author_history."']\n";
			$wpss_log_comment_data .= "Email History:		['".$wpss_author_email_history."']\n";
			$wpss_log_comment_data .= "URL History: 		['".$wpss_author_url_history."']\n";
			$wpss_log_comment_data .= "Entries Accepted: 	['".$wpss_comments_accepted."']\n";
			$wpss_log_comment_data .= "Entries Denied: 	['".$wpss_comments_denied."']\n";
			$wpss_log_comment_data .= "Status Before This: 	['".$wpss_comments_status_current."']\n";
			$wpss_log_comment_data .= "REQUEST_METHOD: 	['".$wpss_server_request_method."']\n";
			//$wpss_log_comment_data .= '$_SESSION'." Data: 	['".$wpss_log_data_serial_session."']\n";
			$wpss_log_comment_data .= '$_COOKIE'." Data:		['".$wpss_log_data_serial_cookie."']\n";
			$wpss_log_comment_data .= '$_GET'." Data: 		['".$wpss_log_data_serial_get."']\n";
			$wpss_log_comment_data .= '$_POST'." Data: 		['".$wpss_log_data_serial_post."']\n";
			$wpss_log_comment_data .= "CL Active: 		['".$wpss_cl_active."']\n";
			$wpss_log_comment_data .= "Extra Data: 		['".$wpss_append_log_data."']\n";
			}
		// New Data Section - End
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		if ( preg_match( "~^No Error~", $wpss_log_comment_data_errors ) ) {
			$wpss_log_comment_data_errors_count = 0;
			}
		else {
			$wpss_log_comment_data_errors_count = spamshield_count_words($wpss_log_comment_data_errors);
			}
		if ( empty( $wpss_log_comment_data_errors ) ) { $wpss_log_comment_data_errors = '[None]'; }
		if ( $wpss_log_comment_type == 'comment' ) {
			if ( empty( $wpss_log_comment_data_array['total_time_jsck_filter'] ) ) {
				$wpss_total_time_jsck_filter 		= 0;
				}
			else {
				$wpss_total_time_jsck_filter 		= $wpss_log_comment_data_array['total_time_jsck_filter'];
				}
			$wpss_total_time_jsck_filter_disp 		= spamshield_number_format( $wpss_total_time_jsck_filter, 6 );
			if ( empty( $wpss_log_comment_data_array['total_time_content_filter'] ) ) {
				$wpss_total_time_content_filter 	= 0;
				}
			else {
				$wpss_total_time_content_filter 	= $wpss_log_comment_data_array['total_time_content_filter'];
				}
			$wpss_total_time_content_filter_disp 	= spamshield_number_format( $wpss_total_time_content_filter, 6 );
			$wpss_start_time_comment_processing 	= $wpss_log_comment_data_array['start_time_comment_processing'];
			// Timer End - Comment Processing
			$wpss_end_time_comment_processing 		= spamshield_microtime();
			$wpss_total_time_wpss_processing 		= $wpss_total_time_jsck_filter + $wpss_total_time_content_filter;
			$wpss_total_time_wpss_processing_disp	= spamshield_number_format( $wpss_total_time_wpss_processing, 6 );
			$wpss_total_time_comment_processing 	= spamshield_timer( $wpss_start_time_comment_processing, $wpss_end_time_comment_processing, false, 6 );
			$wpss_total_time_comment_proc_disp		= $wpss_total_time_comment_processing;
			$wpss_total_time_wp_processing			= $wpss_total_time_comment_processing - $wpss_total_time_wpss_processing;
			$wpss_total_time_wp_processing_disp		= spamshield_number_format( $wpss_total_time_wp_processing, 6 );
			$wpss_log_comment_data .= "JS/C Processing Time: 	['".$wpss_total_time_jsck_filter_disp." seconds'] Time for JS/Cookies Layer to test for spam\n";
			$wpss_log_comment_data .= "Algo Processing Time: 	['".$wpss_total_time_content_filter_disp." seconds'] Time for Algorithmic Layer to test for spam\n";
			$wpss_log_comment_data .= "WPSS Processing Time: 	['".$wpss_total_time_wpss_processing_disp." seconds'] Total time for WP-SpamShield to test for spam\n";
			if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) {
				$wpss_total_time_part_1 			= $wpss_log_comment_data_array['total_time_part_1'];
				$wpss_total_time_part_1_disp		= spamshield_number_format( $wpss_total_time_part_1, 6 );
				$wpss_proc_data = get_option( 'spamshield_procdat' );
				if ( empty( $wpss_proc_data ) || !isset( $wpss_proc_data['total_wpss_time'] ) || !isset( $wpss_proc_data['total_comment_proc_time'] ) ) { 
					$wpss_proc_data = array( 
						'total_tracked' 			=> 0, 
						'total_wpss_time' 			=> 0, 
						'avg_wpss_proc_time' 		=> 0, 
						'total_comment_proc_time' 	=> 0, 
						'avg_comment_proc_time' 	=> 0, 
						'total_wpss_avg_tracked' 	=> 0, 
						'total_avg_wpss_proc_time' 	=> 0, 
						'avg2_wpss_proc_time' 		=> 0 
						); 
					}
				if ( !isset( $wpss_proc_data['total_wpss_avg_tracked'] ) ) { $wpss_proc_data['total_wpss_avg_tracked'] = 0; }
				if ( !isset( $wpss_proc_data['total_avg_wpss_proc_time'] ) ) { $wpss_proc_data['total_avg_wpss_proc_time'] = 0; }
				if ( !isset( $wpss_proc_data['avg2_wpss_proc_time'] ) ) { $wpss_proc_data['avg2_wpss_proc_time'] = 0; }
				$wpss_proc_data_total_tracked 				= $wpss_proc_data['total_tracked'] + 1;
				$wpss_proc_data_total_wpss_time 			= $wpss_proc_data['total_wpss_time'] + $wpss_total_time_wpss_processing;
				$wpss_proc_data_avg_wpss_proc_time 			= $wpss_proc_data_total_wpss_time / $wpss_proc_data_total_tracked;
				$wpss_proc_data_total_comment_proc_time 	= $wpss_proc_data['total_comment_proc_time'] + $wpss_total_time_comment_processing;
				$wpss_proc_data_avg_comment_proc_time 		= $wpss_proc_data_total_comment_proc_time / $wpss_proc_data_total_tracked;
				$wpss_proc_data_total_wpss_avg_tracked 		= $wpss_proc_data['total_wpss_avg_tracked'] + 1;
				$wpss_proc_data_total_avg_wpss_proc_time	= $wpss_proc_data['total_avg_wpss_proc_time'] + $wpss_proc_data_avg_wpss_proc_time;
				$wpss_proc_data_avg2_wpss_proc_time 		= $wpss_proc_data_total_avg_wpss_proc_time / $wpss_proc_data_total_wpss_avg_tracked;
				$wpss_proc_data = array( 
					'total_tracked' 			=> $wpss_proc_data_total_tracked, 
					'total_wpss_time' 			=> $wpss_proc_data_total_wpss_time, 
					'avg_wpss_proc_time' 		=> $wpss_proc_data_avg_wpss_proc_time, 
					'total_comment_proc_time' 	=> $wpss_proc_data_total_comment_proc_time, 
					'avg_comment_proc_time' 	=> $wpss_proc_data_avg_comment_proc_time, 
					'total_wpss_avg_tracked' 	=> $wpss_proc_data_total_wpss_avg_tracked, 
					'total_avg_wpss_proc_time' 	=> $wpss_proc_data_total_avg_wpss_proc_time, 
					'avg2_wpss_proc_time' 		=> $wpss_proc_data_avg2_wpss_proc_time, 
					);
				update_option( 'spamshield_procdat', $wpss_proc_data );	
				$wpss_proc_data_avg_wpss_proc_time_disp 	= spamshield_number_format( $wpss_proc_data_avg_wpss_proc_time, 6 );
				$wpss_proc_data_avg2_wpss_proc_time_disp 	= spamshield_number_format( $wpss_proc_data_avg2_wpss_proc_time, 6 );
				$wpss_proc_data_avg_comment_proc_time_disp 	= spamshield_number_format( $wpss_proc_data_avg_comment_proc_time, 6 );
				$wpss_log_comment_data .= "WP Processing Time:	['".$wpss_total_time_wp_processing_disp." seconds'] Time for other WordPress processes\n"; // Comment out
				$wpss_log_comment_data .= "Total Processing Time: 	['".$wpss_total_time_comment_proc_disp." seconds'] Total time for WordPress to process comment\n";
				//$wpss_log_comment_data .= "WPSS Other Proc Time: 	['".$wpss_total_time_part_1_disp." seconds'] Other Processing Time\n"; // Comment out
				$wpss_log_comment_data .= "Avg WPSS Proc Time:	['".$wpss_proc_data_avg_wpss_proc_time_disp." seconds'] Average total time for WP-SpamShield to test for spam\n";
				$wpss_log_comment_data .= "FAvg WPSS Proc Time:	['".$wpss_proc_data_avg2_wpss_proc_time_disp." seconds'] Fuzzy Average total WPSS time\n";
				$wpss_log_comment_data .= "Avg Total Proc Time:	['".$wpss_proc_data_avg_comment_proc_time_disp." seconds'] Average total time for WordPress to process comments\n";
				}
			$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
			}
		$wpss_log_comment_data .= "Failed Tests: 		['".$wpss_log_comment_data_errors_count."']\n";
		$wpss_log_comment_data .= "Failed Test Codes: 	['".$wpss_log_comment_data_errors."']\n";
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= "Debugging Data:		['PHP MemLimit: ".RSMP_PHP_MEM_LIMIT."; WP MemLimit: ".WP_MEMORY_LIMIT."; Sessions: ".$wpss_sessions_enabled."']\n";
		$wpss_log_comment_data .= "Site Server Name:	['".RSMP_SERVER_NAME."']\n";
		$wpss_log_comment_data .= "Site Server IP:		['".RSMP_SERVER_ADDR."']\n";
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= "Active Plugins:		['".$wpss_active_plugins."']\n";
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= RSMP_USER_AGENT."\n";
		$wpss_log_comment_data .= RSMP_PHP_UNAME."\n";
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= ":: ".$wpss_log_comment_type_display." END ::"."\n";
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= "*************************************************************************************\n\n\n";

		@$wpss_log_fp = fopen( $wpss_log_file,'a+' );
		@fwrite( $wpss_log_fp, $wpss_log_comment_data );
		@fclose( $wpss_log_fp );
		}
	}

function spamshield_comment_form_addendum() {
	$spamshield_options 	= get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);
	$promote_plugin_link 	= $spamshield_options['promote_plugin_link'];
	$wpss_key_values 		= spamshield_get_key_values();
	$wpss_ck_key  			= $wpss_key_values['wpss_ck_key'];
	$wpss_ck_val 			= $wpss_key_values['wpss_ck_val'];
	$wpss_js_key 			= $wpss_key_values['wpss_js_key'];
	$wpss_js_val 			= $wpss_key_values['wpss_js_val'];

	echo "\n".'<script type=\'text/javascript\'>'."\n".'// <![CDATA['."\n".WPSS_REF2XJS.'=escape(document[\'referrer\']);'."\n".'hf1N=\''.$wpss_js_key.'\';'."\n".'hf1V=\''.$wpss_js_val.'\';'."\n".'document.write("<input type=\'hidden\' name=\''.WPSS_REF2XJS.'\' value=\'"+'.WPSS_REF2XJS.'+"\'><input type=\'hidden\' name=\'"+hf1N+"\' value=\'"+hf1V+"\'>");'."\n".'// ]]>'."\n".'</script>';
	echo '<noscript><input type="hidden" name="JSONST" value="NS1"></noscript>'."\n";

	if ( !empty( $promote_plugin_link ) ) {
		$sip5c = '0'; $sip5c = substr(RSMP_SERVER_ADDR, 4, 1); // Server IP 5th Char
		$ppl_code = array( '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '.' => 10 );
		if ( preg_match( "~^[0-9\.]$~", $sip5c ) ) { $int = $ppl_code[$sip5c]; } else { $int = 0; }
		echo spamshield_comment_promo_link($int)."\n";
		}
	$wpss_js_disabled_msg 	= __( 'Currently you have JavaScript disabled. In order to post comments, please make sure JavaScript and Cookies are enabled, and reload the page.', WPSS_PLUGIN_NAME );
	$wpss_js_enable_msg 	= __( 'Click here for instructions on how to enable JavaScript in your browser.', WPSS_PLUGIN_NAME );
	echo '<noscript><p><strong>'.$wpss_js_disabled_msg.'</strong> <a href="http://enable-javascript.com/" rel="nofollow external" >'.$wpss_js_enable_msg.'</a></p></noscript>'."\n";	

	// If need to add anything else to comment area, start here

	}

function spamshield_get_author_cookie_data() {
	// Get Comment Author Data Stored in Cookies
	$key_comment_auth	= 'comment_author_'.RSMP_HASH;
	$key_comment_email	= 'comment_author_email_'.RSMP_HASH;
	$key_comment_url 	= 'comment_author_url_'.RSMP_HASH;
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
	$key_comment_auth	= 'comment_author_'.RSMP_HASH;
	$key_comment_email	= 'comment_author_email_'.RSMP_HASH;
	$key_comment_url 	= 'comment_author_url_'.RSMP_HASH;
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
	$wpss_datum 			= @date('Y-m-d (D) H:i:s',$get_current_time_display);
	$key_comment_acc 		= 'wpss_comments_accepted_'.RSMP_HASH;
	$key_comment_den 		= 'wpss_comments_denied_'.RSMP_HASH;
	$key_comment_stat_curr	= 'wpss_comments_status_current_'.RSMP_HASH;
	$key_auth_hist 			= 'wpss_author_history_'.RSMP_HASH;
	$key_email_hist 		= 'wpss_author_email_history_'.RSMP_HASH;
	$key_auth_url_hist 		= 'wpss_author_url_history_'.RSMP_HASH;
	if ( empty( $_SESSION[$key_comment_den] ) ) { $_SESSION[$key_comment_den] = 0; }
	if ( empty( $_SESSION[$key_comment_acc] ) ) { $_SESSION[$key_comment_acc] = 0; }
	if ( $status == 'r' ) {
		++$_SESSION[$key_comment_den];
		$_SESSION[$key_comment_stat_curr] = '[REJECTED '.$line.' '.$wpss_datum.']';
		}
	elseif ( $status == 'a' ) {
		++$_SESSION[$key_comment_acc];
		$_SESSION[$key_comment_stat_curr] = '[ACCEPTED '.$line.' '.$wpss_datum.']';
		}
	else { $_SESSION[$key_comment_stat_curr] = '[ERROR '.$line.' '.$wpss_datum.']'; }
	if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) !== 0 ) { $_SESSION[$key_comment_stat_curr] = ''; }
	$wpss_comment_author 		= $commentdata['comment_author'];
	$wpss_comment_author_email	= $commentdata['comment_author_email'];
	$wpss_comment_author_url 	= $commentdata['comment_author_url'];
	if ( empty ( $wpss_comment_author ) ) 		{ $wpss_comment_author 			= '[No Data: ERROR 1: '.$line.']'; }
	if ( empty ( $wpss_comment_author_email ) ) { $wpss_comment_author_email 	= '[No Data: ERROR 1: '.$line.']'; }
	if ( empty ( $wpss_comment_author_url ) ) 	{ $wpss_comment_author_url 		= '[No Data: ERROR 1: '.$line.']'; }
	$_SESSION['wpss_comment_author_'.RSMP_HASH] = $wpss_comment_author;
	if ( empty( $_SESSION[$key_auth_hist] ) ) { $_SESSION[$key_auth_hist] = array(); }
	$_SESSION[$key_auth_hist][] = $wpss_comment_author;
	$_SESSION['wpss_comment_author_email_'.RSMP_HASH] = $wpss_comment_author_email;
	if ( empty( $_SESSION[$key_email_hist] ) ) { $_SESSION[$key_email_hist] = array(); }
	$_SESSION[$key_email_hist][] = $wpss_comment_author_email;
	$_SESSION['wpss_comment_author_url_'.RSMP_HASH] = $wpss_comment_author_url;
	if ( empty( $_SESSION[$key_auth_url_hist] ) ) { $_SESSION[$key_auth_url_hist] = array(); }
	$_SESSION[$key_auth_url_hist][] = $wpss_comment_author_url;
	//if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) { $_SESSION['wpss_commentdata_'.RSMP_HASH] = $commentdata; }
	// To pass the $commentdata values through SESSION vars to denied_post functions because WP hook won't allow us to.
	$_SESSION['wpss_commentdata_'.RSMP_HASH] = $commentdata;
	}

function spamshield_contact_shortcode( $attr = NULL ) {
	/* Implementation: [spamshieldcontact] */
	$shortcode_check = 'shortcode';
	$content_new_shortcode = @spamshield_contact_form($content,$shortcode_check);
	return $content_new_shortcode;
	}
	
function spamshield_contact_form( $content, $shortcode_check = NULL ) {
	
	$spamshield_contact_repl_text = array( '<!--spamshield-contact-->', '<!--spamfree-contact-->' );
	
	$server_name 				= RSMP_SERVER_NAME;
	if ( substr( $server_name , 0, 4 ) == 'www.' ) { $server_name = substr( $server_name, 4 ); } // Get rid of 'www'
	//$server_name 				= preg_replace( "~^(ww[w0-9]|m)\.~i", '', $server_name ); // Get rid of 'www' and such
	$wpss_contact_sender_email	= 'wpspamshield.noreply@'.$server_name;
	$wpss_contact_sender_name	= __( 'Contact Form', WPSS_PLUGIN_NAME );

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
	if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) { $user_agent = trim($_SERVER['HTTP_USER_AGENT']); } else { $user_agent = ''; }
	$user_agent_lc 				= strtolower($user_agent);
	$user_agent_lc_word_count 	= spamshield_count_words($user_agent_lc);
	if ( !empty( $_SERVER['HTTP_ACCEPT'] ) ) { $user_http_accept = trim($_SERVER['HTTP_ACCEPT']); } else { $user_http_accept = ''; }
	$user_http_accept_lc		= strtolower($user_http_accept);
	if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) { $user_http_accept_language = trim($_SERVER['HTTP_ACCEPT_LANGUAGE']); }
	else { $user_http_accept_language = ''; }
	$user_http_accept_language_lc = strtolower($user_http_accept_language);
	$spamshield_contact_form_url = $_SERVER['REQUEST_URI'];
	$spamshield_contact_form_url_lc = strtolower($spamshield_contact_form_url);
	// Moved Back URL here to make available to rest of contact form back end - v 1.5.5
	if ( strpos( $spamshield_contact_form_url_lc, '&form=response' ) !== false ) {
		$spamshield_contact_form_back_url = str_replace('&form=response','',$spamshield_contact_form_url );
		}
	elseif ( strpos( $spamshield_contact_form_url_lc, '?form=response' ) !== false ) {
		$spamshield_contact_form_back_url = str_replace('?form=response','',$spamshield_contact_form_url );
		}
	if ( !empty( $_SERVER['QUERY_STRING'] ) ) { $spamshield_contact_form_query_op = '&amp;'; } else { $spamshield_contact_form_query_op = '?'; }
	if ( !empty( $_GET['form'] ) ) { $get_form = $_GET['form']; } else { $get_form = ''; }
	if ( !empty( $_POST['JSONST'] ) ) { $post_jsonst = $_POST['JSONST']; } else { $post_jsonst = ''; }
	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }	
	$post_ref2xjs_lc = strtolower($post_ref2xjs);
	$wpss_error_code = $spamshield_contact_form_content = '';
	if ( is_page() && ( !is_home() && !is_feed() && !is_archive() && !is_search() && !is_404() ) ) {
		$spamshield_options						= get_option('spamshield_options');
		$wpss_key_values 						= spamshield_get_key_values();
		$wpss_ck_key  							= $wpss_key_values['wpss_ck_key'];
		$wpss_ck_val 							= $wpss_key_values['wpss_ck_val'];
		$wpss_js_key 							= $wpss_key_values['wpss_js_key'];
		$wpss_js_val 							= $wpss_key_values['wpss_js_val'];
		if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wp_contact_validation_js = $_COOKIE[$wpss_ck_key]; } else { $wp_contact_validation_js = ''; }
		$form_include_website					= $spamshield_options['form_include_website'];
		$form_require_website					= $spamshield_options['form_require_website'];
		$form_include_phone						= $spamshield_options['form_include_phone'];
		$form_require_phone						= $spamshield_options['form_require_phone'];
		$form_include_company					= $spamshield_options['form_include_company'];
		$form_require_company					= $spamshield_options['form_require_company'];
		$form_include_drop_down_menu			= $spamshield_options['form_include_drop_down_menu'];
		$form_require_drop_down_menu			= $spamshield_options['form_require_drop_down_menu'];
		$form_drop_down_menu_title				= $spamshield_options['form_drop_down_menu_title'];
		$form_drop_down_menu_item_1				= $spamshield_options['form_drop_down_menu_item_1'];
		$form_drop_down_menu_item_2				= $spamshield_options['form_drop_down_menu_item_2'];
		$form_drop_down_menu_item_3				= $spamshield_options['form_drop_down_menu_item_3'];
		$form_drop_down_menu_item_4				= $spamshield_options['form_drop_down_menu_item_4'];
		$form_drop_down_menu_item_5				= $spamshield_options['form_drop_down_menu_item_5'];
		$form_drop_down_menu_item_6				= $spamshield_options['form_drop_down_menu_item_6'];
		$form_drop_down_menu_item_7				= $spamshield_options['form_drop_down_menu_item_7'];
		$form_drop_down_menu_item_8				= $spamshield_options['form_drop_down_menu_item_8'];
		$form_drop_down_menu_item_9				= $spamshield_options['form_drop_down_menu_item_9'];
		$form_drop_down_menu_item_10			= $spamshield_options['form_drop_down_menu_item_10'];
		$form_message_width						= $spamshield_options['form_message_width'];
		$form_message_height					= $spamshield_options['form_message_height'];
		$form_message_min_length				= $spamshield_options['form_message_min_length'];
		$form_message_recipient					= $spamshield_options['form_message_recipient'];
		$form_response_thank_you_message		= $spamshield_options['form_response_thank_you_message'];
		$form_include_user_meta					= $spamshield_options['form_include_user_meta'];
		$form_include_user_meta_hide_ext_data	= $spamshield_options['hide_extra_data'];
		$promote_plugin_link					= $spamshield_options['promote_plugin_link'];
			
		if ( $form_message_width < 40 ) { $form_message_width = 40; }
		if ( $form_message_height < 5 ) { $form_message_height = 5; } elseif ( empty( $form_message_height ) ) { $form_message_height = 10; }
		if ( $form_message_min_length < 15 ) { $form_message_min_length = 15; } elseif ( empty( $form_message_min_length ) ) { $form_message_min_length = 25; }
		if ( $get_form == 'response' && ( $_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST) ) ) {
			// REQUEST_METHOD not POST or empty $_POST - Not a legitimate contact form submission - likely a bot scraping the site
			// Added in v 1.5.5 to conserve server resources
			$error_txt = spamshield_error_txt();
			$wpss_error = $error_txt.':';
			$spamshield_contact_form_content = '<p><strong>'.$wpss_error.' ' . __( 'Please return to the contact form and fill out all required fields.', WPSS_PLUGIN_NAME ) . '</strong><p>&nbsp;</p>'."\n";
			$content_new = str_replace($content, $spamshield_contact_form_content, $content);
			$content_shortcode = $spamshield_contact_form_content;
			}
		elseif ( $get_form == 'response' ) {
			// CONTACT FORM BACK END - BEGIN
			$wpss_whitelist = $wp_blacklist = $message_spam = $blank_field = $invalid_value = $bad_email = $bad_phone = $bad_company = $message_short = $js_cookie_fail = $contact_form_spam_loc = $contact_form_domain_spam_loc = $generic_spam_company = $free_email_address = 0;
			$combo_spam_signal_1 = $combo_spam_signal_2  = $combo_spam_signal_3 = $bad_phone_spammer = 0;	
			$wpss_user_blacklisted_prior_cf = 0;
			// TO DO: Add here
			
			// PROCESSING CONTACT FORM - BEGIN
			$wpss_contact_name = $wpss_contact_email = $wpss_contact_website = $wpss_contact_phone = $wpss_contact_company = $wpss_contact_drop_down_menu = $wpss_contact_subject = $wpss_contact_message = $wpss_raw_contact_message = '';
			
			$wpss_contact_time 					= spamshield_microtime(); // Added in v1.6
			
			if ( !empty( $_POST['wpss_contact_name'] ) ) {
				$wpss_contact_name 				= sanitize_text_field($_POST['wpss_contact_name']);
				}
			if ( !empty( $_POST['wpss_contact_email'] ) ) {
				$wpss_contact_email 			= sanitize_email($_POST['wpss_contact_email']);
				}
			$wpss_contact_email_lc 				= strtolower( $wpss_contact_email );
			$wpss_contact_email_lc_rev 			= strrev( $wpss_contact_email_lc );
			if ( !empty( $_POST['wpss_contact_website'] ) ) {
				$wpss_contact_website 			= esc_url_raw($_POST['wpss_contact_website']);
				}
			$wpss_contact_website_lc 			= strtolower( $wpss_contact_website );
			$wpss_contact_domain 				= spamshield_get_domain( $wpss_contact_website_lc );
			$wpss_contact_domain_rev 			= strrev( $wpss_contact_domain );
			if ( !empty( $_POST['wpss_contact_phone'] ) ) {
				$wpss_contact_phone 			= sanitize_text_field($_POST['wpss_contact_phone']);
				}
			if ( !empty( $_POST['wpss_contact_company'] ) ) {
				$wpss_contact_company 			= sanitize_text_field($_POST['wpss_contact_company']);
				}
			$wpss_contact_company_lc			= strtolower( $wpss_contact_company );
			if ( !empty( $_POST['wpss_contact_drop_down_menu'] ) ) {
				$wpss_contact_drop_down_menu	= sanitize_text_field($_POST['wpss_contact_drop_down_menu']);
				}
			if ( !empty( $_POST['wpss_contact_subject'] ) ) {
				$wpss_contact_subject 			= sanitize_text_field($_POST['wpss_contact_subject']);
				}
			$wpss_contact_subject_lc 			= strtolower( $wpss_contact_subject );
			if ( !empty( $_POST['wpss_contact_message'] ) ) {
				$wpss_contact_message 			= sanitize_text_field($_POST['wpss_contact_message']);
				$wpss_raw_contact_message 		= trim($_POST['wpss_contact_message']);
				}
			$wpss_contact_message_lc 			= strtolower($wpss_contact_message);
			$wpss_raw_contact_message_lc 		= strtolower($wpss_raw_contact_message);
			$wpss_raw_contact_message_lc_deslashed	= stripslashes($wpss_raw_contact_message_lc);
			$wpss_contact_extracted_urls 		= spamshield_parse_links( $wpss_raw_contact_message_lc_deslashed, 'url' ); // Parse message content for all URLs
			
			$contact_form_author_data = array( 'comment_author' => $wpss_contact_name, 'comment_author_email' => $wpss_contact_email_lc, 'comment_author_url' => $wpss_contact_website_lc );

			$wpss_contact_id_slug 			= $wpss_contact_email_lc.'_'.$ip.'_'.$wpss_contact_time; // Email/IP/Time
			$wpss_contact_id_hash 			= spamshield_md5( $wpss_contact_id_slug );
			$key_contact_status				= 'contact_status_'.$wpss_contact_id_hash;
			
			// Update Session Vars
			$key_comment_auth 				= 'comment_author_'.RSMP_HASH;
			$key_comment_email				= 'comment_author_email_'.RSMP_HASH;
			$key_comment_url				= 'comment_author_url_'.RSMP_HASH;
			$_SESSION[$key_comment_auth] 	= $wpss_contact_name;
			$_SESSION[$key_comment_email]	= $wpss_contact_email_lc;
			$_SESSION[$key_comment_url] 	= $wpss_contact_website_lc;
			$_SESSION[$key_contact_status] 	= 'INITIATED';
			
			// Add New Tests for Logging - BEGIN
			if ( !empty( $post_ref2xjs ) ) {
				$ref2xJS = strtolower( addslashes( urldecode( $post_ref2xjs ) ) );
				$ref2xJS = str_replace( '%3a', ':', $ref2xJS );
				$ref2xJS = str_replace( ' ', '+', $ref2xJS );
				$wpss_javascript_page_referrer = esc_url_raw( $ref2xJS );
				}
			else { $wpss_javascript_page_referrer = '[None]'; }
		
			if ( $post_jsonst == 'NS2' ) { $wpss_jsonst = $post_jsonst; } else { $wpss_jsonst = '[None]'; }
			
			$contact_form_author_data['javascript_page_referrer']	= $wpss_javascript_page_referrer;
			$contact_form_author_data['jsonst']						= $wpss_jsonst;
			// Add New Tests for Logging - END

			// PROCESSING CONTACT FORM - END

			// FORM INFO - BEGIN

			if ( !empty( $form_message_recipient ) ) {
				$wpss_contact_form_to			= $form_message_recipient;
				}
			else {
				$wpss_contact_form_to 			= get_option('admin_email');
				}
			$wpss_contact_form_to_name 			= $wpss_contact_form_to;
			$wpss_contact_form_subject 			= '[' . __( 'Website Contact', WPSS_PLUGIN_NAME ) . '] '.$wpss_contact_subject;
			$wpss_contact_form_msg_headers 		= "From: $wpss_contact_sender_name <$wpss_contact_sender_email>" . "\r\n" . "Reply-To: $wpss_contact_name <$wpss_contact_email_lc>" . "\r\n" . "Content-Type: text/plain\r\n"; // 1.7.3
			$wpss_contact_form_blog				= RSMP_SITE_URL;
			// Another option: "Content-Type: text/html"

			// FORM INFO - END

			// TEST TO PREVENT CONTACT FORM SPAM - BEGIN
			
			// Check if user is blacklisted prior to submitting contact form
			$wpss_lang_ck_key = 'UBR_LANG';
			$wpss_lang_ck_val = 'default';
			if ( !empty( $_SESSION['wpss_blacklisted_user_'.RSMP_HASH] ) || ( !empty( $_COOKIE[$wpss_lang_ck_key] ) && $_COOKIE[$wpss_lang_ck_key] == $wpss_lang_ck_val ) ) {
				$wpss_user_blacklisted_prior_cf = 1;
				}
			
			// TESTING SUBMISSION FOR SPAM - BEGIN
			
			if ( $wp_contact_validation_js != $wpss_ck_val ) { // Check for Cookie
				$js_cookie_fail=1;
				$wpss_error_code .= ' CF-COOKIEFAIL';
				}
			
			// WPSS Whitelist Check - BEGIN
			
			// Test WPSS Whitelist if option set
			if ( !empty( $spamshield_options['enable_whitelist'] ) && empty( $wpss_error_code ) && spamshield_whitelist_check( $wpss_contact_email_lc ) ) {
				$wpss_whitelist = 1;
				}
			// WPSS Whitelist Check - END

			
			// TO DO: REWORK SO THAT IF FAILS COOKIE TEST, TESTS ARE COMPLETE
			
			// ERROR CHECKING
			$contact_form_blacklist_status = $contact_response_status_message_addendum = '';
			// TO DO: Switch this old code to REGEX
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
			
			// Message Content Red Flags - TO DO: Add Blacklist Function
			$contact_form_msg_spam_signals = array(
				'.Email us back to get a full proposal.', 
				);
			/*
			$regex_phrase_arr = array();
			foreach( $contact_form_msg_spam_signals_rgx as $i => $contact_form_msg_spam_signal_rgx ) {
				$regex_phrase_arr[] = spamshield_get_regex_phrase($contact_form_msg_spam_signal_rgx,'','rgx_str');
				}
			*/
			
			// Check if Subject seems spammy
			$subject_blacklisted_count = 0;
			$contact_form_spam_subj_arr = array(
				'link request', 'link exchange', 'seo service $99 per month', 'seo services $99 per month', 'seo services @ $99 per month', 'partnership with offshore development center',
				);
			$contact_form_spam_subj_arr_regex = spamshield_get_regex_phrase( $contact_form_spam_subj_arr,'','red_str' );
			if ( preg_match( $contact_form_spam_subj_arr_regex, $wpss_contact_subject_lc ) ) { $subject_blacklisted = true; $subject_blacklisted_count = 1; } else { $subject_blacklisted = false; }
			
			// Check if email is blacklisted
			if( empty( $wpss_whitelist ) && spamshield_email_blacklist_chk( $wpss_contact_email_lc ) ) { 
				$email_blacklisted = true; 
				$wpss_error_code .= ' CF-9200E-BL';
				} 
			else { $email_blacklisted = false; }
			// Website - Check if domain is blacklisted
			if( empty( $wpss_whitelist ) && spamshield_domain_blacklist_chk( $wpss_contact_domain ) ) { 
				$domain_blacklisted = true; 
				$wpss_error_code .= ' CF-10500AU-BL';
				} 
			else { $domain_blacklisted = false; }
			// Website - URL Shortener Check - Added in 1.3.8
			if ( empty( $wpss_whitelist ) && spamshield_urlshort_blacklist_chk( $wpss_contact_website_lc ) ) {
				$website_shortened_url = true; 
				$wpss_error_code .= ' CF-10501AU-BL';
				}
			else { $website_shortened_url = false; }
			// Website - Excessively Long URL Check (Obfuscated & Exploit) - Added in 1.3.8
			if( empty( $wpss_whitelist ) && spamshield_long_url_chk( $wpss_contact_website_lc ) ) {
				$website_long_url = true; 
				$wpss_error_code .= ' CF-10502AU-BL';
				}
			else { $website_long_url = false; }
			/*
			// Spam URL Check -  Check for URL Shorteners, Bogus Long URLs, and Misc Spam Domains
			if( empty( $wpss_whitelist ) && spamshield_at_link_spam_url_chk( $wpss_contact_website_lc ) ) {
				$website_spam_url = true; 
				$wpss_error_code .= ' CF-10510AU-BL';
				}
			else { $website_spam_url = false; }
			*/
			// Add Misc Spam URLs next...
			
			// Check Website URL for Exploits - Ignores Whitelist
			if ( spamshield_exploit_url_chk( $wpss_contact_website_lc ) ) {
				$website_exploit_url = true;
				$wpss_error_code .= ' CF-15000AU-XPL'; // Added in 1.4
				}
			else { $website_exploit_url = false; }
			
			// Message Content - Parse URLs and check for URL Shortener Links - Added in 1.3.8
			if( empty( $wpss_whitelist ) && spamshield_cf_link_spam_url_chk( $wpss_raw_contact_message_lc_deslashed, $wpss_contact_email_lc ) ) {
				$content_shortened_url = true; 
				$wpss_error_code .= ' CF-10530CU-BL';
				}
			else { $content_shortened_url = false; }
			
			// Check all URL's in Message Content for Exploits - Ignores Whitelist
			if ( spamshield_exploit_url_chk( $wpss_contact_extracted_urls ) ) {
				$content_exploit_url = true;
				$wpss_error_code .= ' CF-15000CU-XPL'; // Added in 1.4
				}
			else { $content_exploit_url = false; }
			
			$contact_form_spam_term_total = $contact_form_spam_1_count + $contact_form_spam_2_count + $contact_form_spam_3_count + $contact_form_spam_4_count + $contact_form_spam_7_count + $contact_form_spam_10_count + $contact_form_spam_11_count + $contact_form_spam_12_count + $subject_blacklisted_count;
			$contact_form_spam_term_total_limit = 15;
			
			if ( strpos( $reverse_dns_lc_rev, 'ni.' ) === 0 || strpos( $reverse_dns_lc_rev, 'ur.' ) === 0 || strpos( $reverse_dns_lc_rev, 'kp.' ) === 0 || strpos( $reverse_dns_lc_rev, 'nc.' ) === 0 || strpos( $reverse_dns_lc_rev, 'au.' ) === 0 || strpos( $reverse_dns_lc_rev, 'rt.' ) === 0 || preg_match( "~^1\.22\.2(19|20|23)\.~", $ip ) || strpos( $reverse_dns_lc_rev, '.aidni-tenecap.' ) ) {
				$contact_form_spam_loc = 1;
				// TO DO: Add more, switch to Regex
				}
			elseif ( strpos( $wpss_contact_email_lc_rev , 'ni.' ) === 0 || strpos( $wpss_contact_email_lc_rev , 'ur.' ) === 0 || strpos( $wpss_contact_email_lc_rev , 'kp.' ) === 0 || strpos( $wpss_contact_email_lc_rev , 'nc.' ) === 0 || strpos( $wpss_contact_email_lc_rev , 'au.' ) === 0 || strpos( $wpss_contact_email_lc_rev , 'rt.' ) === 0 ) {
				$contact_form_spam_loc = 2;
				// TO DO: Add more, switch to Regex
				}
			elseif ( strpos( $wpss_contact_domain_rev , 'ni.' ) === 0 || strpos( $wpss_contact_domain_rev , 'ur.' ) === 0 || strpos( $wpss_contact_domain_rev , 'kp.' ) === 0 || strpos( $wpss_contact_domain_rev , 'nc.' ) === 0 || strpos( $wpss_contact_domain_rev , 'au.' ) === 0 || strpos( $wpss_contact_domain_rev , 'rt.' ) === 0 ) {
				$contact_form_spam_loc = 3;
				// TO DO: Add more, switch to Regex
				}
			if ( strpos( RSMP_SERVER_NAME_REV, 'ni.' ) === 0 || strpos( RSMP_SERVER_NAME_REV, 'ur.' ) === 0 || strpos( RSMP_SERVER_NAME_REV, 'kp.' ) === 0 || strpos( RSMP_SERVER_NAME_REV, 'nc.' ) === 0 || strpos( RSMP_SERVER_NAME_REV, 'au.' ) === 0 || strpos( RSMP_SERVER_NAME_REV, 'rt.' ) === 0 ) {
				$contact_form_domain_spam_loc = 1;
				// TO DO: Add more, switch to Regex
				}
			if ( !empty( $form_include_company ) && !empty( $wpss_contact_company_lc ) && preg_match( "~^(se(o|m)|(search\s*engine|internet)\s*(optimiz(ation|ing|er)|market(ing|er))|(se(o|m)|((search\s*engine|internet)\s*)?(optimiz(ation|ing|er)|market(ing|er))|web\s*(design(er|ing)?|develop(ment|er|ing))|(content\s*|copy\s*)?writ(er?|ing))s?\s*(company|firm|services?|freelanc(er?|ing))|(company|firm|services?|freelanc(er?|ing))\s*(se(o|m)|((search\s*engine|internet)\s*)?(optimiz(ation|ing|er)|market(ing|er))|web\s*(design(er|ing)?|develop(ment|er|ing))|(content\s*|copy\s*)?writ(er?|ing))s?)$~", $wpss_contact_company_lc ) ) {
				$generic_spam_company = 1;
				}
			if ( preg_match( "~\@(g(oogle)?mail|hotmail|outlook|yahoo|mail|gmx|inbox|myway)\.(com|(com?\.)?[a-z]{2})$~", $wpss_contact_email_lc ) ) {
				$free_email_address = 1;
				}

			// Combo Tests - Pre
			//if ( preg_match( "~(reply|email\s+us)\s+back\s+to\s+get\s+a\s+full\s+proposal\.$~", $wpss_contact_message_lc ) ) {
			if ( preg_match( "~((reply|email\s+us)\s+back\s+to\s+get\s+(a\s+)?full\s+proposal\.$|can\s+you\s+outsource\s+some\s+seo\s+business\s+to\s+us|humble\s+request\s+we\s+are\s+not\s+spammers\.|if\s+by\s+sending\s+this\s+email\s+we\s+have\s+made\s+(an\s+)?offense\s+to\s+you|if\s+you\s+are\s+not\s+interested\s+then\s+please\s+(do\s+)?reply\s+back\s+as|in\s+order\s+to\s+stop\s+receiving\s+(such\s+)?emails\s+from\s+us\s+in\s+(the\s+)?future\s+please\s+reply\s+with|if\s+you\s+do\s+not\s+wish\s+to\s+receive\s+further\s+emails\s+(kindly\s+)?reply\s+with)~", $wpss_contact_message_lc ) ) {
				$combo_spam_signal_1 = 1;
				}
			if ( preg_match( "~^(get|want)\s+more\s+(customer|client|visitor)s?\s+(and|\&|or)\s+(customer|client|visitor)s?\?+$~", $wpss_contact_subject_lc ) ) {
				$combo_spam_signal_2 = 1;
				}

			if ( preg_match( "~(?:^|[,;\.\!\?\s]+)india(?:[,;\.\!\?\s]+|$)~", $wpss_contact_message_lc ) ) {
				preg_match_all( "~(?:^|[,;\.\!\?\s]+)(SEO)(?:[,;\.\!\?\s]+|$)~", $wpss_contact_message, $matches_raw, PREG_PATTERN_ORDER );
				$spam_signal_3_matches 			= $matches_raw[1]; // Array containing matches parsed from haystack text ($wpss_contact_message)
				$spam_signal_3_matches_count	= count( $spam_signal_3_matches );
				// Changed from 7 to 2 occurrences - 1.6.2
				if ( $spam_signal_3_matches_count > 1 ) { $combo_spam_signal_3 = 1;	}
				}
			if ( preg_match( "~^(01[2-9]){3}0$~", $wpss_contact_phone ) ) {
				$bad_phone_spammer = 1;
				}
			// Combo Tests
			if( empty( $wpss_whitelist ) && ( $contact_form_spam_term_total > $contact_form_spam_term_total_limit || $contact_form_spam_1_count > $contact_form_spam_1_limit || $contact_form_spam_2_count > $contact_form_spam_2_limit || $contact_form_spam_5_count > $contact_form_spam_5_limit || $contact_form_spam_6_count > $contact_form_spam_6_limit || $contact_form_spam_10_count > $contact_form_spam_10_limit ) && !empty( $contact_form_spam_loc ) ) {
				$message_spam = 1;
				$wpss_error_code .= ' CF-MSG-SPAM1';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing requests will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			elseif( empty( $wpss_whitelist ) && ( !empty( $subject_blacklisted ) || $contact_form_spam_8_count > $contact_form_spam_8_limit || $contact_form_spam_9_count > $contact_form_spam_9_limit || $contact_form_spam_11_count > $contact_form_spam_11_limit || $contact_form_spam_12_count > $contact_form_spam_12_limit || !empty( $email_blacklisted ) || !empty( $domain_blacklisted ) || !empty( $website_shortened_url ) || !empty( $website_long_url ) || !empty( $website_exploit_url ) || !empty( $content_shortened_url ) || !empty( $content_exploit_url ) ) ) {
				$message_spam = 1;
				$wpss_error_code .= ' CF-MSG-SPAM2';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			elseif( empty( $wpss_whitelist ) && !empty( $contact_form_spam_loc ) && empty ( $contact_form_domain_spam_loc ) && !empty( $free_email_address ) && ( !empty( $generic_spam_company ) || !empty( $combo_spam_signal_1 ) || !empty( $combo_spam_signal_2 ) || !empty( $bad_phone_spammer ) ) ) {
				$message_spam = 1;
				$wpss_error_code .= ' CF-MSG-SPAM3';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				// Blacklist on failure - future attempts blocked
				$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true;
				}
			elseif( empty( $wpss_whitelist ) && !empty( $generic_spam_company ) && !empty( $combo_spam_signal_3 ) ) {
				$message_spam = 1;
				$wpss_error_code .= ' CF-MSG-SPAM4';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				// Blacklist on failure - future attempts blocked
				$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true;
				}
			elseif( empty( $wpss_whitelist ) && !empty( $generic_spam_company ) && !empty( $free_email_address ) ) {
				// BOTH are odd as legit companies include their name and don't use free email
				$message_spam = 1;
				$wpss_error_code .= ' CF-MSG-SPAM5';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				// Blacklist on failure - future attempts blocked
				$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true;
				}
			// JSONST & Referrer Scrape Test
			else {
				if ( $post_jsonst == 'NS2' ) {
					$message_spam = 1;
					$wpss_error_code .= ' CF-JSONST-1000';
					}
				if ( !empty( $post_ref2xjs ) && strpos( $post_ref2xjs_lc, 'ref2xjs' ) !== false ) {
					$message_spam = 1;
					$wpss_error_code .= ' CF-REF-2-1023';
					}
				/*
				// BAD ROBOT TEST - BEGIN
				// Can remove previous CF-REF-2-1023 test above as this replaces it.
				$bad_robot_filter_data 	 = spamshield_bad_robot_blacklist_chk( 'contact', '', '', '', $wpss_contact_name, $wpss_contact_email_lc );
				//$cf_filter_status		 = $bad_robot_filter_data['status'];
				$revdns_blacklisted 	 = $bad_robot_filter_data['blacklisted'];
				if ( !empty( $revdns_blacklisted ) ) {
					$message_spam = 1;
					$wpss_error_code 	.= $bad_robot_filter_data['error_code'];
					//$cf_badrobot_error = true;
					}
				
				// BAD ROBOT TEST - END
				*/
				if ( $message_spam == 1 ) {
					$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
					}
				}
				
			if ( empty( $wpss_contact_name ) || empty( $wpss_contact_email ) || empty( $wpss_contact_subject ) || empty( $wpss_contact_message ) || ( !empty( $form_include_website ) && !empty( $form_require_website ) && empty( $wpss_contact_website ) ) || ( !empty( $form_include_phone ) && !empty( $form_require_phone ) && empty( $wpss_contact_phone ) ) || ( !empty( $form_include_company ) && !empty( $form_require_company ) && empty( $wpss_contact_company ) ) || ( !empty( $form_include_drop_down_menu ) && !empty( $form_require_drop_down_menu ) && empty( $wpss_contact_drop_down_menu ) ) ) {
				$blank_field=1;
				$wpss_error_code .= ' CF-BLANKFIELD';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'At least one required field was left blank.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}

			if ( !is_email($wpss_contact_email) ) {
				$invalid_value=1;
				$bad_email=1;
				$wpss_error_code .= ' CF-INVAL-EMAIL';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Please enter a valid email address.' ) . '<br />&nbsp;<br />';
				}
			
			// TO DO: RE-WORK THIS SECTION
			$wpss_contact_phone_zero = str_replace( array( '0120120120', '0130130130', '123456', ' ', '0', '-', '(', ')', '+', 'N/A', 'NA', 'n/a', 'na' ), '', $wpss_contact_phone );
			$wpss_contact_phone_clean = preg_replace( "~[^0-9]+~", "", $wpss_contact_phone );
			$phone_length = spamshield_strlen( $wpss_contact_phone_clean ); // Min = 5
			if ( !empty( $form_require_phone ) && !empty( $form_include_phone ) && ( empty( $wpss_contact_phone_zero ) || !empty( $bad_phone_spammer ) || $phone_length < 5 ) ) {
				$invalid_value=1;
				$bad_phone=1;
				$wpss_error_code .= ' CF-INVAL-PHONE';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Please enter a valid phone number.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
				
			if( !empty( $form_require_company ) && !empty( $form_include_company ) && ( empty( $wpss_contact_company_lc ) || preg_match( "~(^https?\:/+|^(0+|company|confidential|empty|fuck\s*you|invalid|na|n/a|nada|negative|nein|no|non|none|nothing|null|nyet|private|restricted|secret|unknown|void)$)~", $wpss_contact_company_lc ) ) ) {
				$invalid_value=1;
				$bad_company=1;
				$wpss_error_code .= ' CF-INVAL-COMPANY';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Please enter a valid company.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
				
			$message_length = spamshield_strlen($wpss_contact_message);
			if ( $message_length < $form_message_min_length ) {
				$message_short=1;
				$wpss_error_code .= ' CF-MSG-SHORT';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message too short. Please enter a complete message.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			
			// Test Reverse DNS Hosts - Do all with Reverse DNS not Remote Host
			// Added in 1.1.7.1
			$rev_dns_filter_data 	= spamshield_revdns_filter( 'contact', $contact_form_blacklist_status, $ip, $reverse_dns_lc, $wpss_contact_name, $wpss_contact_email );
			$revdns_blacklisted 	= $rev_dns_filter_data['blacklisted'];
			if ( !empty( $revdns_blacklisted ) ) {
				$message_spam = 1;
				$server_blacklisted = true;
				$wpss_error_code .= $rev_dns_filter_data['error_code'];
				$contact_form_blacklist_status = $rev_dns_filter_data['status']; // Implement
				$contact_response_status_message_addendum = '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, SEO outsourcing/offshoring spam, and automated contact form submissions will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			else { $server_blacklisted = false; }
			
			// WP Blacklist Check - BEGIN
			
			// Test WP Blacklist if option set
			if( empty( $wpss_whitelist ) && !empty( $spamshield_options['enhanced_comment_blacklist'] ) && empty( $wpss_error_code ) ) {
				if ( spamshield_blacklist_check( '', $wpss_contact_email_lc, '', '', $ip, '', $reverse_dns_lc ) ) {
					$message_spam = 1;
					$wp_blacklist = 1;
					$wpss_error_code .= ' CF-WP-BLACKLIST';
					$contact_response_status_message_addendum = '&bull; ' . __( 'Your message has been blocked based on the website owner\'s blacklist settings.', WPSS_PLUGIN_NAME ) . ' ' . __( 'If you feel this is in error, please contact the site owner by some other method.', WPSS_PLUGIN_NAME );
					if ( !empty( $contact_form_spam_loc ) && empty ( $contact_form_domain_spam_loc ) ) {
						$contact_response_status_message_addendum .= ' ' . __( 'Please note that link requests, link exchange requests, SEO outsourcing/offshoring spam, and automated contact form submissions will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME );
						}
					$contact_response_status_message_addendum .= '<br />&nbsp;<br />';
					}
				}
			// WP Blacklist Check - END

			// FINAL TEST
			// TEST 0-POST - See if user has already been blacklisted this session (before submission of this form), or a previous session, included for cases where caching is active
			if ( !empty( $wpss_user_blacklisted_prior_cf ) ) {
				// User is blacklisted prior to submitting contact form
				$message_spam = 1;
				$user_blacklisted = true;
				$wpss_error_code .= ' CF-0-POST-BL';
				$contact_form_blacklist_status = '2'; // Implement
				$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true;
				$contact_response_status_message_addendum = '&bull; ' . __( 'Your location has been identified as part of a reported spam network. Contact form has been disabled to prevent spam.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			else { $user_blacklisted = false; }

			// Track # of submissions this session
			// Must go after spam tests
			if ( !isset( $_SESSION['wpss_cf_submissions_'.RSMP_HASH] ) ) {
				$_SESSION['wpss_cf_submissions_'.RSMP_HASH] = 1;
				}
			else {
				++$_SESSION['wpss_cf_submissions_'.RSMP_HASH];
				}
			
			// TESTING SUBMISSION FOR SPAM - END
				
			//Sanitize the rest
			$wpss_contact_form_http_accept_language = $wpss_contact_form_http_accept = $wpss_contact_form_http_referer = '';
			if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
				$wpss_contact_form_http_accept_language	= sanitize_text_field($_SERVER['HTTP_ACCEPT_LANGUAGE']);
				}
			if ( !empty( $_SERVER['HTTP_ACCEPT'] ) ) {
				$wpss_contact_form_http_accept 			= sanitize_text_field($_SERVER['HTTP_ACCEPT']);
				}
			$wpss_contact_form_http_user_agent 			= spamshield_get_user_agent();
			if ( !empty( $_SERVER['HTTP_REFERER'] ) ) {
				$wpss_contact_form_http_referer 		= esc_url_raw($_SERVER['HTTP_REFERER']);
				}

			// MESSAGE CONTENT - BEGIN
			$wpss_contact_form_msg_1 = $wpss_contact_form_msg_2 = $wpss_contact_form_msg_3 = '';
			
			$wpss_contact_form_msg_1 .= __( 'Message', WPSS_PLUGIN_NAME ) . ': '."\n";
			$wpss_contact_form_msg_1 .= $wpss_contact_message."\n";
			
			$wpss_contact_form_msg_1 .= "\n";
		
			$wpss_contact_form_msg_1 .= __( 'Name' ) . ': '.$wpss_contact_name."\n";
			$wpss_contact_form_msg_1 .= __( 'Email' ) . ': '.$wpss_contact_email_lc."\n";
			if ( !empty( $form_include_phone ) ) {
				$wpss_contact_form_msg_1 .= __( 'Phone', WPSS_PLUGIN_NAME ) . ': '.$wpss_contact_phone."\n";
				}
			if ( !empty( $form_include_company ) ) {
				$wpss_contact_form_msg_1 .= __( 'Company', WPSS_PLUGIN_NAME ) . ': '.$wpss_contact_company."\n";
				}
			if ( !empty( $form_include_website ) ) {
				$wpss_contact_form_msg_1 .= __( 'Website' ) . ': '.$wpss_contact_website_lc."\n";
				}
			if ( !empty( $form_include_drop_down_menu ) ) {
				$wpss_contact_form_msg_1 .= $form_drop_down_menu_title.": ".$wpss_contact_drop_down_menu."\n";
				}
			
			$wpss_contact_form_msg_2 .= "\n";
			//Check following variables to make sure not repeating
			if ( !empty( $form_include_user_meta ) ) {
				$blacklist_text = __( 'Blacklist the IP Address:', WPSS_PLUGIN_NAME );
				$blacklist_url 	= RSMP_ADMIN_URL.'/options-general.php?page='.WPSS_PLUGIN_NAME.'&wpss_action=blacklist_ip&submitter_ip='.$ip;
				$wpss_contact_form_msg_2 .= "\n";
				$wpss_contact_form_msg_2 .= __( 'Website Generating This Email', WPSS_PLUGIN_NAME ) . ': '.$wpss_contact_form_blog."\n";
				$wpss_contact_form_msg_2 .= __( 'Referrer', WPSS_PLUGIN_NAME ) . ': '.$wpss_contact_form_http_referer."\n";
				$wpss_contact_form_msg_2 .= __( 'User-Agent (Browser/OS)', WPSS_PLUGIN_NAME ) . ": ".$wpss_contact_form_http_user_agent."\n";
				$wpss_contact_form_msg_2 .= __( 'IP Address', WPSS_PLUGIN_NAME ) . ': '.$ip."\n";
				$wpss_contact_form_msg_2 .= __( 'Server', WPSS_PLUGIN_NAME ) . ': '.$reverse_dns."\n";
				$wpss_contact_form_msg_2 .= __( 'IP Address Lookup', WPSS_PLUGIN_NAME ) . ': http://whatismyipaddress.com/ip/'.$ip."\n";
				$wpss_contact_form_msg_2 .= $blacklist_text.' '.$blacklist_url."\n";
				// DEBUG ONLY - BEGIN
				if ( empty( $form_include_user_meta_hide_ext_data ) && strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) {
					$wpss_contact_form_msg_2 .= '-------------------------------------------------------------------'."\n";
					$wpss_contact_form_msg_2 .= ':: ' . __( 'Additional Technical Data Added by WP-SpamShield', WPSS_PLUGIN_NAME ) . ' ::'."\n";
					$wpss_contact_form_msg_2 .= '-------------------------------------------------------------------'."\n";
					$wpss_contact_form_msg_2 .= 'JS Page Referrer Check: '.$wpss_javascript_page_referrer."\n";
					$wpss_contact_form_msg_2 .= 'JSONST: '.$wpss_jsonst."\n";
					$wpss_contact_form_msg_2 .= 'Reverse DNS IP: '.$reverse_dns_ip."\n";
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
					$wpss_contact_form_msg_2 .= 'HTTP_VIA: '.$wpss_contact_form_http_via."\n";
					if ( empty( $wpss_contact_form_http_x_forwarded_for ) ) {
						$wpss_contact_form_http_x_forwarded_for = '[None]';
						}
					$wpss_contact_form_msg_2 .= 'HTTP_X_FORWARDED_FOR: '.$wpss_contact_form_http_x_forwarded_for."\n";
					$wpss_contact_form_msg_2 .= 'HTTP_ACCEPT_LANGUAGE: '.$wpss_contact_form_http_accept_language."\n";
					$wpss_contact_form_msg_2 .= 'HTTP_ACCEPT: '.$wpss_contact_form_http_accept."\n";
					}
				// DEBUG ONLY - END
				}

			$wpss_contact_form_msg_3 .= "\n\n";
			
			$wpss_contact_form_msg = $wpss_contact_form_msg_1.$wpss_contact_form_msg_2.$wpss_contact_form_msg_3;
			$wpss_contact_form_msg_cc = $wpss_contact_form_msg_1.$wpss_contact_form_msg_3;
			// MESSAGE CONTENT - END

			if ( empty( $blank_field ) && empty( $invalid_value ) && empty( $message_short ) && empty( $message_spam ) && empty( $js_cookie_fail ) && empty( $server_blacklisted ) && empty( $user_blacklisted ) ) {  
				// SEND MESSAGE
				
				// Verify if Already Sent to Prevent Duplicates - Added in 1.6
				if ( !empty( $_SESSION[$key_contact_status] ) && $_SESSION[$key_contact_status] != 'SENT' ) {
					@wp_mail( $wpss_contact_form_to, $wpss_contact_form_subject, $wpss_contact_form_msg, $wpss_contact_form_msg_headers );
					$_SESSION[$key_contact_status] = 'SENT';
					}
				
				$contact_response_status = 'thank-you';
				$wpss_error_code = 'No Error';
				spamshield_update_sess_accept_status($contact_form_author_data,'a','Line: '.__LINE__);
				if ( !empty( $spamshield_options['comment_logging'] ) && !empty( $spamshield_options['comment_logging_all'] ) ) {
					spamshield_log_data( $contact_form_author_data, $wpss_error_code, 'contact form', $wpss_contact_form_msg );
					}
				}
			else {
				update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
				spamshield_update_sess_accept_status($contact_form_author_data,'r','Line: '.__LINE__);
				$contact_response_status = 'error';
				if ( !empty( $spamshield_options['comment_logging'] ) ) {
					$wpss_error_code = ltrim($wpss_error_code);
					spamshield_log_data( $contact_form_author_data, $wpss_error_code, 'contact form', $wpss_contact_form_msg );
					}
				}				
			
			// TEST TO PREVENT CONTACT FORM SPAM - END
			
			$form_response_thank_you_message_default = '<p>' . __( 'Your message was sent successfully. Thank you.', WPSS_PLUGIN_NAME ) . '</p><p>&nbsp;</p>';
			$form_response_thank_you_message = str_replace( "\\", "", $form_response_thank_you_message );
			
			$error_txt = spamshield_error_txt();
			$wpss_error = $error_txt.':';
			$wpss_js_disabled_msg_short = __( 'Currently you have JavaScript disabled.', WPSS_PLUGIN_NAME );
		
			if ( $contact_response_status == 'thank-you' ) {
				if ( !empty( $form_response_thank_you_message ) ) {
					$spamshield_contact_form_content .= '<p>'.$form_response_thank_you_message.'</p><p>&nbsp;</p>'."\n";
					}
				else {
					$spamshield_contact_form_content .= $form_response_thank_you_message_default."\n";
					}
				}
			else {
				// Back URL was here...moved
				if ( !empty( $message_spam ) ) {
					$contact_response_status_message_addendum .= '<noscript><br />&nbsp;<br />&bull; '.$wpss_js_disabled_msg_short.'</noscript>'."\n";
					$spamshield_contact_form_content .= '<p><strong>'.$wpss_error.' <br />&nbsp;<br />'.$contact_response_status_message_addendum.'</strong><p>&nbsp;</p>'."\n";
					}
				else {
					$contact_response_status_message_addendum .= '<noscript><br />&nbsp;<br />&bull; '.$wpss_js_disabled_msg_short.'</noscript>'."\n";
					$spamshield_contact_form_content .= '<p><strong>'.$wpss_error.' ' . __( 'Please return to the contact form and fill out all required fields.', WPSS_PLUGIN_NAME );
					$spamshield_contact_form_content .= ' ' . __( 'Please make sure JavaScript and Cookies are enabled in your browser.', WPSS_PLUGIN_NAME );
					$spamshield_contact_form_content .= '<br />&nbsp;<br />'.$contact_response_status_message_addendum.'</strong><p>&nbsp;</p>'."\n";
					}

				}
			$content_new = str_replace($content, $spamshield_contact_form_content, $content);
			$content_shortcode = $spamshield_contact_form_content;
			// CONTACT FORM BACK END - END
			}
		else {
			if ( !empty( $_COOKIE['comment_author_'.RSMP_HASH] ) ) {
				// Can't use server side if caching is active - TO DO: AJAX
				$stored_author_data 	= spamshield_get_author_cookie_data();
				$stored_author 			= $stored_author_data['comment_author'];
				$stored_author_email	= $stored_author_data['comment_author_email'];
				$stored_author_url 		= $stored_author_data['comment_author_url'];			
				}
			$spamshield_contact_form_content .= '<form id="wpss_contact_form" name="wpss_contact_form" action="'.$spamshield_contact_form_url.$spamshield_contact_form_query_op.'form=response" method="post" style="text-align:left;" >'."\n";

			$spamshield_contact_form_content .= '<p><label><strong>' . __( 'Name' ) . '</strong> *<br />'."\n";

			$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_name" name="wpss_contact_name" value="" size="40" /> </label></p>'."\n";
			$spamshield_contact_form_content .= '<p><label><strong>' . __( 'Email' ) . '</strong> *<br />'."\n";
			$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_email" name="wpss_contact_email" value="" size="40" /> </label></p>'."\n";
			
			if ( !empty( $form_include_website ) ) {
				$spamshield_contact_form_content .= '<p><label><strong>' . __( 'Website' ) . '</strong> ';
				if ( !empty( $form_require_website ) ) {
					$spamshield_contact_form_content .= '*';
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_website" name="wpss_contact_website" value="" size="40" /> </label></p>'."\n";
				}
				
			if ( !empty( $form_include_phone ) ) {
				$spamshield_contact_form_content .= '<p><label><strong>' . __( 'Phone', WPSS_PLUGIN_NAME ) . '</strong> ';
				if ( !empty( $form_require_phone ) ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_phone" name="wpss_contact_phone" value="" size="40" /> </label></p>'."\n";
				}

			if ( !empty( $form_include_company ) ) {
				$spamshield_contact_form_content .= '<p><label><strong>' . __( 'Company', WPSS_PLUGIN_NAME ) . '</strong> ';
				if ( !empty( $form_require_company ) ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_company" name="wpss_contact_company" value="" size="40" /> </label></p>'."\n";
				}

			if ( !empty( $form_include_drop_down_menu ) && !empty( $form_drop_down_menu_title ) && !empty( $form_drop_down_menu_item_1 ) && !empty( $form_drop_down_menu_item_2 ) ) {
				$spamshield_contact_form_content .= '<p><label><strong>'.$form_drop_down_menu_title.'</strong> ';
				if ( !empty( $form_require_drop_down_menu ) ) { 
					$spamshield_contact_form_content .= '*'; 
					}
				$spamshield_contact_form_content .= '<br />'."\n";
				$spamshield_contact_form_content .= '<select id="wpss_contact_drop_down_menu" name="wpss_contact_drop_down_menu" > '."\n";
				$spamshield_contact_form_content .= '<option value="" selected="selected">' . __( 'Select' ) . '</option> '."\n";
				$spamshield_contact_form_content .= '<option value="">--------------------------</option> '."\n";
				if ( !empty( $form_drop_down_menu_item_1 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$form_drop_down_menu_item_1.'">'.$form_drop_down_menu_item_1.'</option> '."\n";
					}
				if ( !empty( $form_drop_down_menu_item_2 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$form_drop_down_menu_item_2.'">'.$form_drop_down_menu_item_2.'</option> '."\n";
					}
				if ( !empty( $form_drop_down_menu_item_3 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$form_drop_down_menu_item_3.'">'.$form_drop_down_menu_item_3.'</option> '."\n";
					}
				if ( !empty( $form_drop_down_menu_item_4 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$form_drop_down_menu_item_4.'">'.$form_drop_down_menu_item_4.'</option> '."\n";
					}
				if ( !empty( $form_drop_down_menu_item_5 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$form_drop_down_menu_item_5.'">'.$form_drop_down_menu_item_5.'</option> '."\n";
					}
				if ( !empty( $form_drop_down_menu_item_6 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$form_drop_down_menu_item_6.'">'.$form_drop_down_menu_item_6.'</option> '."\n";
					}
				if ( !empty( $form_drop_down_menu_item_7 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$form_drop_down_menu_item_7.'">'.$form_drop_down_menu_item_7.'</option> '."\n";
					}
				if ( !empty( $form_drop_down_menu_item_8 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$form_drop_down_menu_item_8.'">'.$form_drop_down_menu_item_8.'</option> '."\n";
					}
				if ( !empty( $form_drop_down_menu_item_9 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$form_drop_down_menu_item_9.'">'.$form_drop_down_menu_item_9.'</option> '."\n";
					}
				if ( !empty( $form_drop_down_menu_item_10 ) ) {
					$spamshield_contact_form_content .= '<option value="'.$form_drop_down_menu_item_10.'">'.$form_drop_down_menu_item_10.'</option> '."\n";
					}
				$spamshield_contact_form_content .= '</select> '."\n";
				$spamshield_contact_form_content .= '</label></p>'."\n";
				}
			
			$spamshield_contact_form_content .= '<p><label><strong>' . __( 'Subject', WPSS_PLUGIN_NAME ) . '</strong> *<br />'."\n";
    		$spamshield_contact_form_content .= '<input type="text" id="wpss_contact_subject" name="wpss_contact_subject" value="" size="40" /> </label></p>'."\n";			
			$spamshield_contact_form_content .= '<p><label><strong>' . __( 'Message', WPSS_PLUGIN_NAME ) . '</strong> *<br />'."\n";
			$spamshield_contact_form_content .= '<textarea id="wpss_contact_message" name="wpss_contact_message" cols="'.$form_message_width.'" rows="'.$form_message_height.'"></textarea> </label></p>'."\n";
			
			$spamshield_contact_form_content .= '<script type=\'text/javascript\'>'."\n";
			$spamshield_contact_form_content .= '// <![CDATA['."\n";
			$spamshield_contact_form_content .= WPSS_REF2XJS.'=escape(document[\'referrer\']);'."\n";
			$spamshield_contact_form_content .= 'document.write("<input type=\'hidden\' name=\''.WPSS_REF2XJS.'\' value=\'"+'.WPSS_REF2XJS.'+"\'>");'."\n";
			$spamshield_contact_form_content .= '// ]]>'."\n";
			$spamshield_contact_form_content .= '</script>'."\n";
			
			$spamshield_contact_form_content .= '<noscript><input type="hidden" name="JSONST" value="NS2"></noscript>'."\n";
			$wpss_js_disabled_msg 	= __( 'Currently you have JavaScript disabled. In order to use this contact form, please make sure JavaScript and Cookies are enabled, and reload the page.', WPSS_PLUGIN_NAME );
			$wpss_js_enable_msg 	= __( 'Click here for instructions on how to enable JavaScript in your browser.', WPSS_PLUGIN_NAME );
			$spamshield_contact_form_content .= '<noscript><p><strong>'.$wpss_js_disabled_msg.'</strong> <a href="http://enable-javascript.com/" rel="nofollow external" >'.$wpss_js_enable_msg.'</a></p></noscript>'."\n";
			$spamshield_contact_form_content .= '<p><input type="submit" id="wpss_contact_submit" name="wpss_contact_submit" value="' . __( 'Send Message', WPSS_PLUGIN_NAME ) . '" /></p>'."\n";
			$spamshield_contact_form_content .= '<p>' . sprintf( __( 'Required fields are marked %s' ), '*' ) . '</p>'."\n";
			$spamshield_contact_form_content .= '<p>&nbsp;</p>'."\n";
			if ( !empty( $promote_plugin_link ) ) {
				$sip5c = '0';
				$sip5c = substr(RSMP_SERVER_ADDR, 4, 1); // Server IP 5th Char
				$ppl_code = array( '0' => 2, '1' => 2, '2' => 2, '3' => 2, '4' => 2, '5' => 2, '6' => 1, '7' => 0, '8' => 2, '9' => 2, '.' => 2 );
				if ( preg_match( "~^[0-9\.]$~", $sip5c ) ) {
					$int = $ppl_code[$sip5c];
					}
				else { $int = 0; }
				$spamshield_contact_form_content .= spamshield_contact_promo_link($int)."\n";
				$spamshield_contact_form_content .= '<p>&nbsp;</p>'."\n";
				}
			$spamshield_contact_form_content .= '</form>'."\n";
			
			// PRE-TESTS, WILL DISABLE CONTACT FORM
			$contact_form_blacklist_status = ''; // Used in pre-tests, not yet implemented in post
			// Test Reverse DNS Hosts - Do all with Reverse DNS not Remote Host
			$rev_dns_filter_data = spamshield_revdns_filter( 'contact', $contact_form_blacklist_status, $ip, $reverse_dns_lc, '', '' );
			$revdns_blacklisted = $rev_dns_filter_data['blacklisted'];
			if ( !empty( $revdns_blacklisted ) ) {
				$contact_form_blacklist_status = $rev_dns_filter_data['status']; // '2'
				$wpss_error_code .= $rev_dns_filter_data['error_code'];
				}
			// UA Tests
			if ( empty( $user_agent_lc ) ) {
				$contact_form_blacklist_status = '2';
				$wpss_error_code .= ' CF-UA1001';
				}
			if ( !empty( $user_agent_lc ) && $user_agent_lc_word_count < 3 ) {
				$contact_form_blacklist_status = '2';
				$wpss_error_code .= ' CF-UA1003';
				}
			if ( strpos( $user_agent_lc, 'libwww' ) !== false 
				|| strpos( $user_agent_lc, 'nutch' ) === 0
				|| strpos( $user_agent_lc, 'larbin' ) === 0
				|| strpos( $user_agent_lc, 'jakarta' ) === 0
				|| strpos( $user_agent_lc, 'java' ) === 0
				|| strpos( $user_agent_lc, 'mechanize' ) === 0
				|| strpos( $user_agent_lc, 'phpcrawl' ) === 0
				|| strpos( $user_agent_lc, 'iopus-' ) !== false ) {
				$contact_form_blacklist_status = '2';
				$wpss_error_code .= ' CF-UA1004';
				}
			if ( empty( $user_http_accept_lc ) ) {
				$contact_form_blacklist_status = '2';
				$wpss_error_code .= ' CF-HA1001';
				}
			if ( $user_http_accept_lc == 'application/json, text/javascript, */*; q=0.01' ) {
				$contact_form_blacklist_status = '2';
				$wpss_error_code .= ' CF-HA1002';
				}
			if ( $user_http_accept_lc == '*' ) {
				$contact_form_blacklist_status = '2';
				$wpss_error_code .= ' CF-HA1003';
				}
			if ( empty( $user_http_accept_language_lc ) ) {
				$contact_form_blacklist_status = '2';
				$wpss_error_code .= ' CF-HAL1001';
				}
			if ( $user_http_accept_language_lc == '*' ) {
				$contact_form_blacklist_status = '2';
				$wpss_error_code .= ' CF-HAL1002';
				}
			// Add blacklist check - IP's only though.
			
			// TEST 0-PRE - See if user has already been blacklisted this session
			$wpss_lang_ck_key = 'UBR_LANG';
			$wpss_lang_ck_val = 'default';
			if ( !empty( $_SESSION['wpss_blacklisted_user_'.RSMP_HASH] ) || ( !empty( $_COOKIE[$wpss_lang_ck_key] ) && $_COOKIE[$wpss_lang_ck_key] == $wpss_lang_ck_val ) ) {
				$contact_form_blacklist_status = '2';
				$wpss_error_code .= ' CF-0-PRE-BL';
				$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true;
				}

			$wpss_cache_check 			= spamshield_check_cache_status();
			$wpss_cache_check_status	= $wpss_cache_check['cache_check_status'];

			// DISABLE CONTACT FORM IF BLACKLISTED
			if ( !empty( $contact_form_blacklist_status ) && $wpss_cache_check_status != 'ACTIVE' ) {
				$spamshield_contact_form_content = '<strong>' . __( 'Your location has been identified as part of a reported spam network. Contact form has been disabled to prevent spam.', WPSS_PLUGIN_NAME ) . '</strong>';
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

function spamshield_bad_robot_blacklist_chk( $type = 'comment', $status = NULL, $ip = NULL, $rev_dns = NULL, $author = NULL, $email = NULL ) {
	// Use this to determine if a visitor is a bad robot.
	$wpss_error_code = '';
	$blacklisted = false;
	if ( empty( $ip ) ) { $ip = $_SERVER['REMOTE_ADDR']; }
	$ip_regex = spamshield_preg_quote( $ip );
	if ( empty( $rev_dns  ) ) { $rev_dns = gethostbyaddr($ip); }
	$rev_dns = strtolower(trim($rev_dns));
	if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) { $user_agent = strtolower(trim($_SERVER['HTTP_USER_AGENT'])); } else { $user_agent = ''; }
	$user_agent_word_count = spamshield_count_words($user_agent);
	if ( !empty( $_SERVER['HTTP_ACCEPT'] ) ) { $user_http_accept = strtolower(trim($_SERVER['HTTP_ACCEPT'])); } else { $user_http_accept = ''; }
	if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) { $user_http_accept_language = strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])); } else { $user_http_accept_language = ''; }
	if ( $type == 'register' ) { $pref = 'R-'; $ns_val = 'NS3'; } elseif ( $type == 'contact' ) { $pref = 'CF-'; $ns_val = 'NS2'; } else { $pref = ''; $ns_val = 'NS1'; }
	
	// REF2XJS
	// This case only happens if bots scrape the form. Nice try guys.
	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }	
	$post_ref2xjs_lc = strtolower($post_ref2xjs);
	if ( !empty( $post_ref2xjs ) && strpos( $post_ref2xjs_lc, 'ref2xjs' ) !== false ) {
		$wpss_error_code .= ' '.$pref.'REF-2-1023';
		$blacklisted = true;
		}
	
	// HA / HAL - RoboBrowsers
	if ( empty( $user_http_accept ) ) {
		$wpss_error_code .= ' '.$pref.'HA1001';
		$blacklisted = true;
		}
	if ( $user_http_accept == 'application/json, text/javascript, */*; q=0.01' ) {
		$wpss_error_code .= ' '.$pref.'HA1002';
		$blacklisted = true;
		}
	if ( $user_http_accept == '*' ) {
		$wpss_error_code .= ' '.$pref.'HA1003';
		$blacklisted = true;
		}
	$user_http_accept_mod_1 = preg_replace( "~([\s\;]+)~", ",", $user_http_accept );
	$user_http_accept_elements = explode( ',', $user_http_accept_mod_1 );
	$user_http_accept_elements_count = count($user_http_accept_elements);
	$i = 0;
	// The following line to prevent exploitation:
	$i_max = 20;
	while ( $i < $user_http_accept_elements_count && $i < $i_max ) {
		if ( !empty( $user_http_accept_elements[$i] ) ) {
			if ( $user_http_accept_elements[$i] == '*' ) {
				$wpss_error_code .= ' '.$pref.'HA1004';
				$blacklisted = true;
				break;
				}
			}
		$i++;
		}
	if ( empty( $user_http_accept_language ) ) {
		$wpss_error_code .= ' '.$pref.'HAL1001';
		$blacklisted = true;
		}
	if ( $user_http_accept_language == '*' ) {
		$wpss_error_code .= ' '.$pref.'HAL1002';
		$blacklisted = true;
		}
	$user_http_accept_language_mod_1 = preg_replace( "~([\s\;]+)~", ",", $user_http_accept_language );
	$user_http_accept_language_elements = explode( ',', $user_http_accept_language_mod_1 );
	$user_http_accept_language_elements_count = count($user_http_accept_language_elements);
	$i = 0;
	// The following line to prevent exploitation:
	$i_max = 20;
	while ( $i < $user_http_accept_language_elements_count && $i < $i_max ) {
		if ( !empty( $user_http_accept_language_elements[$i] ) ) {
			if ( $user_http_accept_language_elements[$i] == '*' && strpos( $user_agent, 'links (' ) !== 0 ) {
				$wpss_error_code .= ' '.$pref.'HAL1004';
				$blacklisted = true;
				break;
				}
			}
		$i++;
		}
	// HAL1005

	// USER-AGENT
	// Add Blacklisted User-Agent Function - Note 1.4
	if ( empty( $user_agent ) ) {
		$wpss_error_code .= ' '.$pref.'UA1001';
		$blacklisted = true;
		}
	if ( !empty( $user_agent ) && $user_agent_word_count < 3 ) {
		$wpss_error_code .= ' '.$pref.'UA1003';
		$blacklisted = true;
		}
	if ( strpos( $user_agent, 'libwww' ) !== false 
		|| strpos( $user_agent, 'nutch' ) === 0
		|| strpos( $user_agent, 'larbin' ) === 0
		|| strpos( $user_agent, 'jakarta' ) === 0
		|| strpos( $user_agent, 'java' ) === 0
		|| strpos( $user_agent, 'mechanize' ) === 0
		|| strpos( $user_agent, 'phpcrawl' ) === 0
		|| strpos( $user_agent, 'iopus-' ) !== false ) {
		$wpss_error_code .= ' '.$pref.'UA1004';
		$blacklisted = true;
		}
	
	// REVDNS
	$rev_dns_filter_data 	 = spamshield_revdns_filter( $type, $status, $ip, $rev_dns, $author, $email );
	$revdns_blacklisted 	 = $rev_dns_filter_data['blacklisted'];

	if ( !empty( $revdns_blacklisted ) ) {
		$wpss_error_code 	.= $rev_dns_filter_data['error_code'];
		$blacklisted = true;
		}
	
	if ( !empty( $blacklisted ) ) { $status = '2'; }
	$bad_robot_filter_data = array( 'status' => $status, 'error_code' => $wpss_error_code, 'blacklisted' => $blacklisted );
	return $bad_robot_filter_data;
	}

function spamshield_email_blacklist_chk( $email = NULL, $get_eml_list_arr = false, $get_pref_list_arr = false, $get_str_list_arr = false, $get_str_rgx_list_arr = false ) {
	// Email Blacklist Check
	$blacklisted_emails = spamshield_rubik_mod( spamshield_get_email_blacklist(), 'de', true );
	if ( !empty( $get_eml_list_arr ) ) { return $blacklisted_emails; }
	$blacklisted_email_prefixes = array(
		// The beginning part of the email
		"anonymous@", "fuckyou@", "root@", "spam@", "spambot@", "spammer@",
		);
	if ( !empty( $get_pref_list_arr ) ) { return $blacklisted_email_prefixes; }

	$blacklisted_email_strings = array(
		// Red-flagged strings that occur anywhere in the email address
		".seo@gmail.com", ".bizapps@gmail.com", 
		);
	if ( !empty( $get_str_list_arr ) ) { return $blacklisted_email_strings; }
	
	$blacklisted_email_strings_rgx = array(
		// Custom regex strings that occur in the email address
		"spinfilel?namesdat", "\.((marketing|business|web)manager|seo(services?)?)[0-9]*\@(g(oogle)?mail|hotmail|outlook|yahoo|mail|gmx|inbox|myway)\.(com|(com?\.)?[a-z]{2})", "^((marketing|business|web)manager|seo(services?)?)\..*\@(g(oogle)?mail|hotmail|outlook|yahoo|mail|gmx|inbox|myway)\.(com?|(co\.)?[a-z]{2})", 
		"\.((marketing|business|web)manager|seo(services?)?).*\@(g(oogle)?mail|hotmail|outlook|yahoo|mail|gmx|inbox|myway)\.(com|(com?\.)?[a-z]{2})", "^name\-[0-9]{5}\@g(oogle)?mail\.com$", 
		);
	if ( !empty( $get_str_rgx_list_arr ) ) { return $blacklisted_email_strings_rgx; }
	
	// Goes after all arrays
	$blacklist_status = false;
	if ( empty( $email ) ) { return false; }
	$blacklisted_domains = spamshield_domain_blacklist_chk('',true);

	$n = 0; // 1-4
	$t = 0; // Total
	$regex_phrase_arr = array();
	foreach( $blacklisted_emails as $i => $blacklisted_email ) {
		$regex_phrase_arr[] = spamshield_get_regex_phrase($blacklisted_email,'','email_addr');
		++$n;
		++$t;
		}
	foreach( $blacklisted_email_prefixes as $i => $blacklisted_email_prefix ) {
		$regex_phrase_arr[] = spamshield_get_regex_phrase($blacklisted_email_prefix,'','email_prefix');
		++$n;
		++$t;
		}
	foreach( $blacklisted_email_strings as $i => $blacklisted_email_string ) {
		$regex_phrase_arr[] = spamshield_get_regex_phrase($blacklisted_email_string,'','red_str');
		++$n;
		++$t;
		}
	foreach( $blacklisted_email_strings_rgx as $i => $blacklisted_email_string_rgx ) {
		$regex_phrase_arr[] = spamshield_get_regex_phrase($blacklisted_email_string_rgx,'','rgx_str');
		++$n;
		++$t;
		}
	foreach( $blacklisted_domains as $i => $blacklisted_domain ) {
		$regex_phrase_arr[] = spamshield_get_regex_phrase($blacklisted_domain,'','email_domain');
		++$t;
		}

	foreach( $regex_phrase_arr as $i => $regex_phrase ) {
		if ( preg_match( $regex_phrase, $email ) ) {
			if ( $i > $n ) { $_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true; }
			return true;
			}
		}

	return $blacklist_status;
	}

function spamshield_domain_blacklist_chk( $domain = NULL, $get_list_arr = false ) {
	// Domain Blacklist Check
	$blacklisted_domains = spamshield_rubik_mod( spamshield_get_domain_blacklist(), 'de', true );
	if ( !empty( $get_list_arr ) ) { return $blacklisted_domains; }
	// Goes after array
	$blacklist_status = false;
	if ( empty( $domain ) ) { return false; }
	// Other Checks
	$regex_phrases_other_checks = array(
		// Payday Loan Spammers - Keywords in Domain
		"~((payday|short-?term|instant|(personal-?)?cash)-?loans?|(cash|payday)-?advance|quick-?cash)~i", 
		// "plan cul" Spammers
		"~(plan-?cul|tonplanq)~i", 
		// Misc
		"~^((ww[w0-9]|m)\.)?whereto(buy|get)cannabisoil~i", 
		);
	foreach( $regex_phrases_other_checks as $i => $regex_check_phrase ) {
		if ( preg_match( $regex_check_phrase, $domain ) ) { 
			$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true;
			return true; 
			}
		}
	// Final Check - The Blacklist...takes longest once blacklist is populated, so put last
	foreach( $blacklisted_domains as $i => $blacklisted_domain ) {
		$regex_check_phrase = spamshield_get_regex_phrase( $blacklisted_domain, '', 'domain' );
		if ( preg_match( $regex_check_phrase, $domain ) ) {
			$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true;
			return true;
			}
		}

	return $blacklist_status;
	}

function spamshield_urlshort_blacklist_chk( $url = NULL, $email_domain = NULL, $comment_type = NULL  ) {
	// URL Shortener Blacklist Check
	// Dangerous because users have no idea what website they are clicking through to
	// Added $comment_type in 1.5 for trackbacks
	$url_shorteners = array(
		// Initial Set - Will add more as new ones are verified
		// 15 per line
		"›.ws", "014.me", "0rz.tw", "1url.com", "2.gp", "2tu.us", "66.re", "888.hn", "adcrun.ch", "adf.ly", "aka.gr", "amzn.to", "bc.vc", "bit.do", "bit.ly", 
		"bitly.com", "blankrefer.com", "budurl.com", "buff.ly", "buzurl.com", "cli.gs", "cutt.us", "dft.ba", "dlvr.it", "fb.me", "filoops.info", "fur.ly", "goo.gl", "goxl.me", "hiderefer.com", 
		"is.gd", "ity.im", "j.mp", "jump2now.com", "l.gg", "lemde.fr", "linkto.im", "moourl.com", "ow.ly", "po.st", "q.gs", "qr.net", "rdlnk.com", "scrnch.me", "smsh.me", 
		"sn.im", "snipurl.com", "snurl.com", "su.pr", "t.co", "tiny.cc", "tinyurl.com", "tl.gd", "tota2.com", "twitthis.com", "u.to", "v.gd", "x.co", "y2u.be", "youtu.be", 
		// YOURLS user-created shorteners
		"atho.me", "axr.be", "hgld.ru", "of.vg", "we.cx", 
		);
	// Goes after array
	$blacklist_status = false;
	if ( empty( $url ) ) { return false; }
	$url = spamshield_fix_url($url);
	$domain = spamshield_get_domain($url);
	if ( empty( $domain ) ) { return false; }
	$domain_rgx = spamshield_preg_quote($domain);
	// See if link points to domain root or legit corporate page (ie. bitly.com)
	if ( $comment_type != 'trackback' && preg_match( "~^https?\://$domain_rgx(/|.*([a-z0-9\-]+/[a-z0-9\-]{4,}.*|[a-z0-9\-]{4,}\.[a-z]{3,4}))?$~iu", $url ) ) { return false; }
	// Shortened URL check begins
	$regex_phrase = spamshield_get_regex_phrase($url_shorteners,'','domain');
		// Consider adding regex for 2-letter domains with 2-letter extensions ( "aa.xx" )
	if ( $email_domain != $domain && preg_match( $regex_phrase, $domain ) ) {
		return true;
		}
	return $blacklist_status;
	}

function spamshield_long_url_chk( $url = NULL ) {
	// Excessively Long URL Check - Added in 1.3.8
	// To prevent obfuscated & exploit URL's
	$blacklist_status = false;
	if ( empty( $url ) ) { return false; }
	$url_lim = 140;
	$url_len = spamshield_strlen($url);
	if ( $url_len > $url_lim ) { 
		return true;
		}
	return $blacklist_status;
	}

function spamshield_social_media_url_chk( $url = NULL, $comment_type = NULL ) {
	// Social Media URL Check - Added in 1.3.8
	// To prevent author url and anchor text links to spam social media profiles
	// Added $comment_type in 1.5 for trackbacks
	$social_media_domains = array(
		// 10 per line
		//"apps.facebook.com", "bebo.com", "ca.linkedin.com", "dailymotion.com", "digg.com", "facebook.com", "flickr.com", "instagram.com", "linkedin.com", "meetup.com", 
		//"myspace.com", "pinterest.com", "plus.google.com", "recordr.tv", "stumbleupon.com", "tagged.com", "twitter.com", "vimeo.com", "vk.com", "youtube.com", 
		"apps.facebook.com", "bebo.com", "dailymotion.com", "digg.com", "flickr.com", "instagram.com", "meetup.com", "myspace.com", "pinterest.com", "recordr.tv", 
		"stumbleupon.com", "tagged.com", "vimeo.com", "vk.com", "youtube.com", 
		);
	$social_media_domains_ext = array(
		// 10 per line
		"apps.facebook.com", "bebo.com", "ca.linkedin.com", "dailymotion.com", "digg.com", "facebook.com", "flickr.com", "instagram.com", "linkedin.com", "meetup.com", 
		"myspace.com", "pinterest.com", "plus.google.com", "recordr.tv", "stumbleupon.com", "tagged.com", "vimeo.com", "vk.com", "youtube.com", 
		// Left out Twitter to allow tweetbacks
		);
	// Goes after array
	$blacklist_status = false;
	if ( empty( $url ) ) { return false; }
	if ( $comment_type == 'trackback' ) { $social_media_domains = $social_media_domains_ext; }
	$domain = spamshield_get_domain($url);
	$domain_rgx = spamshield_preg_quote($domain);
	$url = spamshield_fix_url($url);
	// See if link points to domain root (ie. facebook.com)
	if ( $comment_type != 'trackback' && preg_match( "~^https?\://$domain_rgx/?$~iu", $url ) ) { return false; }
	$regex_phrase = spamshield_get_regex_phrase($social_media_domains,'','domain');
	if ( preg_match( $regex_phrase, $domain ) ) {
		return true;
		}
	// When $regex_phrase exceeds a certain size, switch this to run smaller groups or run each domain individually
	return $blacklist_status;
	}

function spamshield_misc_spam_url_chk( $url = NULL ) {
	// Spam Domain URL Check - Added in 1.3.8
	// To prevent author url and anchor text links to spam domains
	$spam_domains = array(
		// 10 per line
		"150m.com", "250m.com", "297286.com", "agriimplements.com", "animatedfavicon.com", "blogs.ign.com", "choice-direct.com", "christiantorrents.ru", "cignusweb.com", "clickaudit.com", 
		"docs.google.com", "experl.com", "freehostia.com", "free-site-host.com", "grillpartssteak.com", "groups.google.com", "groups.google.us", "groups.yahoo.com", "groups.yahoo.us", "johnbeck.com", 
		"johnbeck.net", "johnbeck.tv", "johnbeckseminar.com", "johnbeckseminar.net", "johnbeckseminar.tv", "johnbeckssuccessstories.com", "johnbeckssuccessstories.net", "johnbeckssuccessstories.tv", "kankamforum.net", "lifecity.info", 
		"lifecity.tv", "mastersofseo.com", "members.lycos.co.uk", "mmoinn.com", "mradar.com", "netcallidus.com", "page2rss.com", "phpbbserver.com", "play.google.com", "real-url.org", 
		"redcamtube.com", "registry-error-cleaner.com", "rsschannelwriter.com", "shredderwarehouse.com", "sitemapwriter.com", "sqiar.com", "squidoo.com", "sunitawedsamit.com", "t35.com", "tpbunblocked.com", 
		"widecircles.com", "wordpressautocomment.com", 
		);
	// Goes after array
	$blacklist_status = false;
	if ( empty( $url ) ) { return false; }
	$domain = spamshield_get_domain($url);
	$regex_phrases_other_checks = array(
		"~youporn([0-9]+)\.vox\.com~i", 
		"~shopsquareone\.com/stores/~i", 
		"~yellowpages\.ca/bus/~i", 
		"~spence-?diamonds?~i", 
		"~seo-?services?-?(new-?york|ny)~i", 
		"~jasonberkowitzseo~i", 
		);
	foreach( $regex_phrases_other_checks as $i => $regex_check_phrase ) {
		if ( preg_match( $regex_check_phrase, $url ) ) {
			return true;
			}
		}
	$regex_phrase = spamshield_get_regex_phrase($spam_domains,'','domain');
	if ( preg_match( $regex_phrase, $domain ) ) {
		return true;
		}
	// When $regex_phrase exceeds a certain size, switch this to run smaller groups or run each domain individually
	return $blacklist_status;
	}

function spamshield_link_blacklist_chk( $haystack = NULL, $type = 'domain' ) {
	// Link Blacklist Check
	// $haystack can be any body of content you want to search for links to blacklisted domains
	// $type can be 'domain', 'url', or 'urlshort' depending on what kind of check you need to do. 'domain' is faster, hence it's the default
	$blacklist_status = false;
	if ( empty( $haystack ) ) { return false; }
	$extracted_domains = spamshield_parse_links( $haystack, 'domain' );
	foreach( $extracted_domains as $d => $domain ) {
		if ( spamshield_domain_blacklist_chk( $domain ) ) {
			return true;
			}
		}
	return $blacklist_status;
	}

function spamshield_at_link_spam_url_chk( $urls = NULL, $comment_type = NULL ) {
	// Anchor Text Link Spam URL Check
	// Check Anchor Text Links in comment content for links to common spam URLs
	// $urls - an array of URLs parsed from anchor text links
	// If $urls is string, will convert to array
	// Added $comment_type in 1.5 for trackbacks
	$blacklist_status = false;
	if ( empty( $urls ) ) { return false; }
	if ( is_string( $urls ) ) {
		$urls_arr 	= array();
		$urls_arr[]	= $urls;
		$urls 		= $urls_arr;
		}
	foreach( $urls as $u => $url ) {
		if ( spamshield_urlshort_blacklist_chk( $url, '', $comment_type ) || spamshield_long_url_chk( $url ) || spamshield_social_media_url_chk( $url, $comment_type ) || spamshield_misc_spam_url_chk( $url ) ) {
			// Shortened URLs, Long URLs, Social Media, Other common spam URLs
			return true;
			}
		}
	return $blacklist_status;
	}

function spamshield_cf_link_spam_url_chk( $haystack = NULL, $email = NULL ) {
	// Contact Form Link Spam URL Check
	// Check Anchor Text Links in message content for links to shortened URLs
	// $haystack is contact form message content
	$blacklist_status = false;
	if ( empty( $haystack ) || empty( $email ) ) { return false; }
	$email_domain = spamshield_get_email_domain($email);
	$extracted_urls = spamshield_parse_links( $haystack, 'url' );
	foreach( $extracted_urls as $u => $url ) {
		if ( spamshield_urlshort_blacklist_chk( $url, $email_domain ) ) {
			return true;
			}
		}
	return $blacklist_status;
	}

function spamshield_exploit_url_chk( $urls = NULL ) {
	// Security - Misc Exploit URL Check - Added in 1.4
	// Check ALL links for common exploit URLs
	// $urls - an array of URLs parsed from comment or message content
	// If $urls is string, will convert to array (so can be used for Comment Author URL or Contact Form Website)
	$blacklist_status = false;
	if ( empty( $urls ) ) { return false; }
	if ( is_string( $urls ) ) {
		$urls_arr 	= array();
		$urls_arr[]	= $urls;
		$urls 		= $urls_arr;
		}
	foreach( $urls as $u => $url ) {
		$query_str = spamshield_get_query_string($url);
		if ( preg_match( "~/phpinfo\.php\?~i", $url ) ) {
			// phpinfo.php Redirect - Used in XSS
			return true;
			}
		elseif ( preg_match( "~^(https?\:/+)?([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/?~i", $url ) ) {
			// IP Address URLs
			// Normal people (and Trackbacks/Pingbacks) don't post IP addresses as their website address in a comment, DUH
			// Dangerous because users have no idea what website they are clicking through to
			// Likely a Phishing site or XSS
			return true;
			}
		elseif ( !empty( $query_str ) && preg_match( "~(\.\.(\/|%2f)|boot\.ini|(ftp|https?)(\:|%3a)|mosconfig_[a-z_]{1,21}(\=|%3d)|base64_encode.*\(.*\)|[\[\]\(\)\{\}\<\>\|\"\';\?\*\$]|%22|%24|%27|%2a|%3b|%3c|%3e|%3f|%5b|%5d|%7b|%7c|%7d|%0|%a|%b|%c|%d|%e|%f|127\.0|globals|encode|localhost|loopback|request|select|insert|union|declare)~i", $query_str ) ) { // Check Query String
			// Dangerous Exploit URLs - XSS, SQL injection, or other
			// Test Query String - This covers a number of SQL Injection and other exploits
			return true;
			}			
		elseif ( preg_match( "~([\[\]\(\)\{\}\<\>\|\"\';\*\$]|%22|%24|%27|%2a|%3b|%3c|%3e|%3f|%5b|%5d|%7b|%7c|%7d)~i", $url ) ) { // Check Query String
			// Dangerous Exploit URLs - XSS, SQL injection, or other
			// Test URL - no reason these would occur in a normal URL - they're not legal in a URL, but we've seen them in a lot of spam URL submissions
			return true;
			}
		}
	return $blacklist_status;
	}

function spamshield_anchortxt_blacklist_chk( $haystack = NULL, $get_list_arr = false, $haystack_type = 'author', $url = NULL ) {
	// Author Keyword Blacklist Check
	// Use for testing Comment Author, New User Registrations, and anywhere else you need to test an author name.
	// This list assembled based on statistical analysis of common anchor text spam keyphrases.
	// Script creates all the necessary alphanumeric and linguistic variations to effectively test.
	// $haystack_type can be 'author' (default) or 'content'
	$wpss_cl_active		= spamshield_is_plugin_active( 'commentluv/commentluv.php' ); // Check if active for compatibility with CommentLuv
	$spamshield_options = get_option('spamshield_options');	
	if ( !empty( $spamshield_options['allow_comment_author_keywords'] ) ) { $wpss_cak_active = 1; } else { $wpss_cak_active = 0; } // Check if Comment Author Name Keywords are allowed - equivalent to CommentLuve being active
	$blacklisted_keyphrases = spamshield_rubik_mod( spamshield_get_anchortxt_blacklist(), 'de', true );
	$blacklisted_keyphrases_lite = array( 
		// Use this for content link anchor text, not author names
		"accident lawyer", "accutane", "acomplia", "adipex", "air jordan", "alprostadil", "amature movie", "amature video", "attorney", "avanafil", "bankruptcy", "bestiality", "betting", "bisexual", 
		"blackjack", "blow job", "build link", "build muscle", "buy pill", "call girl", "cambogia", "cannabis", "cash advance", "casinos", "celebrity movie", "celebrity video", "cellulite", "celulit", 
		"chaturbate", "cialis", "clitoris", "clonazepam", "comment poster", "comment submitter", "consolidate debt", "consolidation debt", "consolidation loan", "cosmetic surgeon", "cosmetic surgery", 
		"credit card", "cum", "cum shot", "dapoxetine", "debt consolidation", "desnuda", "diet pill", "dildo", "drug rehab", "dui lawyer", "earn money", "ejaculate", "ephedra", "ephedrine", "erectile", 
		"erection", "eretil", "erotic", "eroticism", "escort service", "fat loss", "foreclosure", "forex", "formula t10", "fuck", "fuckbuddy", "fuckin", "gambling", "gambling online", "garcinia", 
		"garcinia cambogia", "hack wifi", "hand bag", "hair loss", "hentai", "herbalife", "herpes", "heterosexual", "homeopathic", "homosexual", "impotence", "impotencia", "impotent", "incest", 
		"india outsource", "inhibitor pde5", "injury lawyer", "keratin", "ketone", "klonopin", "labia", "labial", "labiale", "levitra", "levtira", "libido", "link builder", "link building", "loan payday", 
		"loan student", "loan title", "logo design", "lose fat", "lose weight", "lunette", "make money", "marijuana", "massage", "masturbate", "medical", "medication", "milf", "money make", "muscle build", 
		"muscle builder", "muscle ripped", "naked", "nike air", "nike shoe", "nude", "online consultant", "online consulting", "online design", "online developer", "online development", "online hosting", 
		"online gambling", "online marketer", "online marketing", "online optimization", "online rank", "online ranking", "online template", "opiate", "orgasm", "outsource india", "page rank", "payday", 
		"payday loan", "pde5 inhibitor", "penetracion", "penetrate", "peniana", "peniano", "penile", "penis", "pharmacy", "phentermine", "php expert", "pills", "plan cul", "plan q", "plantar fasciitis", 
		"plastic surgeon", "plastic surgery", "porn", "porn star", "porno", "pornographic", "pornography", "porntube", "prescription", "priligy", "propecia", "prostitute", "pussy", "rapes", "raping", 
		"rapist", "rhinoplasty", "rimonabant", "ripped muscle", "rivotril", "search engine", "search engine consulting", "search engine consultant", "search engine marketer", "search engine marketing", 
		"search engine optimization", "search engine optimizer", "search engine rank", "search engine ranking", "search consultant", "search consulting", "search marketer", "search marketing", 
		"search optimization", "search optimizer", "search rank", "search ranking", "sem", "seo", "sex", "sex chat", "sex drive", "sex movie", "sex tape",  "sex video", "sexcam", "sexe", "sexologia", "sexual", 
		"sexual performance", "sexual service", "sexy", "short-term loan", "sildenafil", "social bookmark", "social media consulting", "social media marketing", "social media optimization", "social poster", 
		"social submitter", "soma", "spence diamond", "staxyn", "stendra", "steroid", "student loan", "sunglasses", "supplement", "surgeons", "surgery", "sweating", "tadalafil", "testosterone", "title loan", 
		"trackback", "tramadol", "treatment", "vagina", "vaginal", "valium", "vardenafil", "viagra", "vigara", "vigrx", "webmaster", "weight loss", "wifi hack", "wifi hacker","xanax", "xxx", "zimulti", 
		"zithromax", "zoekmachine optimalisatie", 
		);
	if ( $haystack_type == 'author' && ( !empty( $wpss_cl_active ) || !empty( $wpss_cak_active ) || empty( $url ) ) ) {
		$blacklisted_keyphrases = $blacklisted_keyphrases_lite;
		}
	if ( !empty( $get_list_arr ) ) {
		if ( $haystack_type == 'content' ) { return $blacklisted_keyphrases_lite; }
		else { return $blacklisted_keyphrases; }
		}
	// Goes after array
	$blacklist_status = false;
	if ( empty( $haystack ) ) { return false; }
	if ( $haystack_type == 'author' ) {
		// Check 1: Testing for URLs in author name
		if ( preg_match( "~^https?~i", $haystack ) ) {
			return true;
			}
		// Check 2: Testing for max # words in author name, more than 7 is fail
		$author_words = spamshield_count_words( $haystack );
		$word_max = 7; // Default
		If ( !empty( $wpss_cl_active ) || !empty( $wpss_cak_active ) ) { $word_max = 10; } /* CL or CAK active */
		if ( $author_words > $word_max ) {
			return true;
			}
		// Check 3: Testing for Odd Characters in author name
		$odd_char_regex = "~[\@\*]+~"; // Default
		If ( !empty( $wpss_cl_active ) || !empty( $wpss_cak_active ) ) { $odd_char_regex = "~(\@{2,}|\*)+~"; } /* CL or CAK active */
		if ( preg_match( $odd_char_regex, $haystack ) ) {
			return true;
			}
		/*
		// Check 4: Testing for *author name* surrounded by asterisks
		*/
		// Check 5: Testing for numbers and cash references ('1000','$5000', etc) in author name 
		if ( empty( $wpss_cl_active ) && empty( $wpss_cak_active ) && preg_match( "~(^|[\s\.])(\$([0-9]+)([0-9,\.]+)?|([0-9]+)([0-9,\.]{3,})|([0-9]{3,}))($|[\s])~", $haystack ) ) {
			return true;
			}
		// Final Check: The Blacklist
		foreach( $blacklisted_keyphrases as $i => $blacklisted_keyphrase ) {
			$blacklisted_keyphrase_rgx = spamshield_regexify( $blacklisted_keyphrase );
			$regex_check_phrase = spamshield_get_regex_phrase( $blacklisted_keyphrase_rgx, '', 'authorkw' );
			if ( preg_match( $regex_check_phrase, $haystack ) ) {
				return true;
				}
			}
		}
	elseif ( $haystack_type == 'content' ) {
		// Parse content for links with Anchor Text
		// Test 1: Coming Soon
		// For possible use later - from old filter: ((payday|students?|title|onli?ne|short([\s\.\-_]*)term)([\s\.\-_]*)loan|cash([\s\.\-_]*)advance)
		// Final Check: The Blacklist
		$anchor_text_phrases = spamshield_parse_links( $haystack, 'anchor_text' );
		foreach( $anchor_text_phrases as $a => $anchor_text_phrase ) {
			foreach( $blacklisted_keyphrases_lite as $i => $blacklisted_keyphrase ) {
				$blacklisted_keyphrase_rgx = spamshield_regexify( $blacklisted_keyphrase );
				$regex_check_phrase = spamshield_get_regex_phrase( $blacklisted_keyphrase_rgx, '', 'authorkw' );
				if ( preg_match( $regex_check_phrase, $anchor_text_phrase ) ) {
					return true;
					}
				}
			}
		}
	return $blacklist_status;
	}

function spamshield_regexify( $var ) {
	if ( is_array( $var ) ) {
		$output = array();
		foreach( $var as $i => $string ) {
			$output[] = spamshield_regex_alpha_replace( $string );
			}		
		}
	elseif ( is_string( $var) ) {
		$output = spamshield_regex_alpha_replace( $var );
		}
	else { $output = $var; }
	return $output;
	}

function spamshield_regex_alpha_replace( $string ) {
	$tmp_string = strtolower( trim( $string ) );
	// Translates 1337 (LEET) as well
	$input = array( // 26
		"~(^|[\s\.])(online|internet|web(\s*(site|page))?)\s*gambling($|[\s])~i", "~(^|[\s\.])gambling\s*(online|internet|web(\s*(site|page))?)($|[\s])~i", 
		"~(?!^online|internet|web(\s*(site|page))?$)(^|[\s\.])(online|internet|web(\s*(site|page))?)($|[\s])~i", "~(?!^india|china|russia|ukraine$)(^|[\s\.])(india|china|russia|ukraine)($|[\s])~i", 
		"~(?!^offshore|outsource|data\s+entry$)(^|[\s\.])(offshore|outsource|data\s+entry)($|[\s])~i", "~ph~i",	"~(^|[\s\.])porn~i", "~ual($|[\s])~i", "~al($|[\s])~i", "~ay($|[\s])~i", "~ck($|[\s])~i", 
		"~(ct|x)ion($|[\s])~i", "~te($|[\s])~i", "~(?!te$)e($|[\s])~i", "~er($|[\s])~i", "~ey($|[\s])~i", "~ic($|[\s])~i", "~ign($|[\s])~i", "~iou?r($|[\s])~i", "~ism($|[\s])~i", "~ous($|[\s])~i", 
		"~oy($|[\s])~i", "~ss($|[\s])~i", "~tion($|[\s])~i", "~y($|[\s])~i", "~([abcdghklmnoprtw])($|[\s])~i", 
		);
	$output = array( // 26
		" (online|internet|web( (site|page))?)s? (bet(ting|s)?|blackjack|casinos?|gambl(e|ing)|poker) ", " (bet(ting|s)?|blackjack|casinos?|gambl(e|ing)|poker) (online|internet|web( (site|page))?)s? ", 
		" (online|internet|web( (site|page))?)s? ", " (india|china|russia|ukraine) ", " (offshor(e(d|r|s|n|ly)?|ing)s?|outsourc(e(d|r|s|n|ly)?|ing)s?|data entry) ", "(ph|f)", "p(or|ro)n", "u(a|e)l(ly|s)? ", 
		"al(ly|s)? ", "ays? ", "ck(e(d|r)?|ing)?s? ", "(ct|cc|x)ions? ", "t(e(d|r|s|n|ly)?|ing|ion)?s? ", "(e(d|r|s|n|ly)?|ing|ation)s? ", "(er|ing)s? ", "eys? ", "i(ck?|que)(s|ly)? ", "ign(e(d|r))?s? ", 
		"iou?rs? ", "is(m|t) ", "ous(ly)? ", "oys? ", "ss(es)? ", "(t|c)ions? ", "(y|ie(d|r|s)?) ", "$1s? ", 
		);
	$tmp_string = preg_replace( $input, $output, $tmp_string );
	$tmp_string = strtolower( trim( $tmp_string ) );
	$the_replacements = array(
		" "	=> "([\s\.,\;\:\?\!\/\|\@\(\)\[\]\{\}\-_]*)", "-" => "([\s\.,\;\:\?\!\/\|\@\(\)\[\]\{\}\-_]*)", "a"	=> "([a4\@àáâãäåæāăą])", "b"	=> "([b8ßƀƃƅ])", "c"	=> "([c¢©çćĉċč])", "d" => "([dďđ])", 
		"e" => "([e3èéêëēĕėęěǝ])", "g" => "([g9ĝğġģ])", "h" => "([hĥħ])", "i"	=> "([i1yìíîïĩīĭį])", "k" => "([kķĸ])", "j"	=> "([jĵ])", "l" => "([l1ĺļľŀł])", "n" => "([nñńņňŉ])", "o" => "([o0ðòóôõöōŏőœ])", 
		"r"	=> "([r®ŕŗř])", "s"	=> "([s5\$śŝşš])", "t"	=> "([t7ťŧţ])", "u" => "([uùúûüũūŭůűų])", "w" => "([wŵ])", "y" => "([y1i¥ýÿŷ])", "z" => "([z2sźżž])", 
		);
	if ( !preg_match( "~^NOMOD~i", $tmp_string ) ){
		$new_string = strtr( $tmp_string, $the_replacements );
		}
	else {
		$new_string = preg_replace( "~^NOMOD~i", "", $tmp_string );
		}
	return $new_string;
	}

function spamshield_revdns_filter( $type = 'comment', $status = NULL, $ip = NULL, $rev_dns = NULL, $author = NULL, $email = NULL ) {
	$wpss_error_code = '';
	$blacklisted = false;
	if ( empty( $ip ) ) { $ip = $_SERVER['REMOTE_ADDR']; }
	$ip_regex = spamshield_preg_quote( $ip );
	if ( empty( $ip ) ) { $ip = $_SERVER['REMOTE_ADDR']; }
	$ip_regex = spamshield_preg_quote( $ip );
	if ( empty( $rev_dns  ) ) { $rev_dns = gethostbyaddr($ip); }
	$rev_dns = strtolower(trim($rev_dns));
	if ( !empty( $author ) ) { $author = strtolower( $author ); }
	if ( !empty( $email ) ) { $author = strtolower( $email ); }
	if ( $type == 'contact' ) { $pref = 'CF-'; } elseif ( $type == 'register' ) { $pref = 'R-'; } elseif ( $type == 'trackback' ) { $pref = 'T-'; } else { $pref = ''; }

	// Test Reverse DNS Hosts - Do all with Reverse DNS moving forward
	
	// Bad Robot!
	$banned_servers = array(
		// Web servers should not post comments/contact forms/register
		"REVD1023" => "~(^|\.)keywordspy\.com$~",
		"REVD1024" => "~(^|\.)clients\.your-server\.de$~",
		"REVD1025" => "~^rover-host\.com$~",
		"REVD1026" => "~^host\.lotosus\.com$~",
		"REVD1027" => "~^rdns\.softwiseonline\.com$~",
		"REVD1028" => "~^s([a-z0-9]+)\.websitehome\.co\.uk$~",
		"REVD1029" => "~\.opentransfer\.com$~",
		"REVD1030" => "~(^|\.)arkada\.rovno\.ua$~",
		"REVD1031" => "~^(host?|vm?)[0-9]+\.server[0-9]+\.vpn(999|2buy)\.com$~",
		"REVD1032" => "~^(ip[\.\-])?[0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-](rdns\.(as15003\.net|cloudradium\.com|continuumdatacenters\.com|purewebtech\.net|scalabledns\.com|ubiquityservers\.com)|static\.(hostnoc\.net|dimenoc\.com|reverse\.(softlayer\.com|queryfoundry\.net))|ip\.idealhosting\.net\.tr|triolan\.net|chunkhost\.com|hosted-by\.xtrmhosting\.com|rev\.poneytelecom\.eu|ipvnow.com|customer-(rethinkvps|incero)\.com|unknown\.steephost\.(net|com))$~",
		"REVD1033" => "~^(r?d)?ns([0-9]{1,3})\.(webmasters|rootleveltech)\.com$~",
		"REVD1034" => "~^server([0-9]+)\.(shadowbrokers|junctionmethod|([a-z0-9\-]+))\.(com|net)$~",
		"REVD1035" => "~^(hosted-by\.(ipxcore\.com|hosthatch\.com|reliablesite\.net|slaskdatacenter\.pl)|host\.colocrossing\.com)$~",
		"REVD1036" => "~^($ip_regex\.static|unassigned)\.quadranet\.com$~",
		"REVD1037" => "~^([a-z]{2}[0-9]*[\.\-])?[0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}(\.[a-z]{2}-((north|south)(east|west)|north|south|east|west)-([0-9]+))?\.compute\.amazonaws\.com$~",
		"REVD1038" => "~^([a-z]+)([0-9]{1,3})\.(guarmarr\.com|startdedicated\.com)$~",
		"REVD1039" => "~^([a-z0-9\-\.]+)\.rev\.sprintdatacenter\.pl$~",
		"REVD1040" => "~^ns([0-9]+)\.ovh\.net$~",
		"REVD1041" => "~^(static|clients?)[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-](clients\.your-server\.de|customers\.filemedia\.net|hostwindsdns\.com)$~",
		"REVD1042" => "~^([0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}|ks[0-9]{7})[\.\-]kimsufi\.com$~",
		"REVD1043" => "~^[0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-]static-reverse\.[a-z]+-cloud\.serverhub\.com$~",
		"REVD1044" => "~^host[0-9]+\.sale24news\.com$~",
		"REVD1045" => "~^[0-9]+-[0-9]+-[0-9]+\.unassigned\.userdns\.com$~",
		"REVD1046" => "~^h?[0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-](rackcentre|host)\.redstation\.(co|net)\.uk$~",
		"REVD1047" => "~^[0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-]hostvenom\.com$~",
		"REVD1048" => "~^[a-z]+[0-9]*\.zeehostbox\.com$~",
		"REVD1049" => "~^(h[\.\-])?[0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-]keyweb\.de$~",
		);

	$banned_trackback_servers = array(
		// ISP's Should not send trackbacks, ever
		//"REVD2101" => "~^([0-9]{1,3}[\.\-]){4}broad\.[a-z]{2}\.[a-z]{2}\.dynamic\.163data\.com\.cn$~", 
		"REVD2102" => "~^c\-([0-9]{1,3}[\.\-]){4}hsd[0-9]+\.[a-z]{2,}\.comcast\.net$~", 
		//"REVD2103" => "~dynamic\-([0-9]{1,3}[\.\-]){4}airtelbroadband\.in$~", 
		"REVD2103" => "~([0-9]{1,3}[\.\-]){4}(static[\.\-])?[a-z]+broadband(\.[a-z]{2,3})?\.[a-z]{2,3}$~", 
		"REVD2104" => "~^host([0-9]{1,3}[\.\-]){4}range[0-9]+\-[0-9]+\.btcentralplus\.com$~", 
		"REVD2105" => "~^ip([0-9]{1,3}[\.\-]){4}[a-z]{2,}\.[a-z]{2,}\.cox\.net$~", 
		"REVD2106" => "~^([0-9]{1,3}[\.\-]){4}([a-z]{3}\.)?broadband\.kyivstar\.net$~", 
		"REVD2107" => "~^([0-9]{1,3}[\.\-]){2}broadband\.[a-z]{4,}(\.[a-z]{2,3})?\.[a-z]{2,3}$~", 
		"REVD2108" => "~^abs\-static\-([0-9]{1,3}[\.\-]){4}aircel\.co\.in$~", 
		"REVD2109" => "~^([0-9]{1,3}[\.\-]){4}res\.bhn\.net$~", 
		"REVD2110" => "~^([0-9]{1,3}[\.\-]){4}static\-[a-z]+\.vsnl\.net\.in$~", 
		"REVD2111" => "~^cpc([0-9]{1,3})\-([a-z]{4})([0-9]{1,3})\-([0-9]{1,3})\-([0-9]{1,3})\-cust([0-9]{1,3})\.([0-9]{1,3})\-([0-9]{1,3})\.cable\.virginm\.net$~", 
		"REVD2112" => "~([0-9]{1,3}[\.\-]){4}lightspeed\.[a-z]{5,6}\.sbcglobal\.net$~", 
		"REVD2113" => "~^(static\-)?([0-9]{1,3}[\.\-]){1,4}tataidc\.co\.in$~", 
		"REVD2114" => "~([0-9]{1,3}[\.\-]){4}asianet\.co\.in$~", 
		"REVD2115" => "~([0-9]{1,3}[\.\-]){4}ras\.beamtele\.(net|in)$~", 
		"REVD2116" => "~([0-9]{1,3}[\.\-]){4}(bol|mtnl)\.net\.in$~", 
		"REVD2117" => "~([0-9]{1,3}[\.\-]){4}reverse\.spectranet\.in$~", 
		"REVD2118" => "~([0-9]{1,3}[\.\-]){4}vasaicable\.co\.in$~", 
		);
	
	if ( $type == 'trackback' ) { $banned_servers = $banned_trackback_servers; }

	if ( $type == 'trackback' && strpos( $rev_dns, '.dynamic.' ) !== false && strpos( $rev_dns, 'www.dynamic.' ) === false ) {
		$wpss_error_code .= ' '.$pref.'REVD2000'; $blacklisted = true;
		}
	elseif ( $type == 'trackback' && strpos( $rev_dns, '.broadband.' ) !== false && strpos( $rev_dns, 'www.broadband.' ) === false ) {
		$wpss_error_code .= ' '.$pref.'REVD2001'; $blacklisted = true;
		}
	elseif ( $type == 'trackback' && ( strpos( $rev_dns, '.dsl.dyn.' ) !== false || strpos( $rev_dns, '.dyn.dsl.' ) !== false ) ) {
		$wpss_error_code .= ' '.$pref.'REVD2010'; $blacklisted = true;
		}
	else {
		foreach( $banned_servers as $error_code => $regex_phrase ) {
			if ( preg_match( $regex_phrase, $rev_dns ) ) { $wpss_error_code .= ' '.$pref.$error_code; $blacklisted = true; }
			}
		}
	
	if ( empty( $blacklisted ) && !empty( $author ) && !empty( $email ) && ( $type == 'comment' || $type == 'register' ) ) { 
		// The 8's Pattern - from relakks.com - Anonymous surfing, powered by bots
		if ( preg_match( "~^anon-([0-9]+)-([0-9]+)\.relakks\.com$~", $rev_dns ) && preg_match( "~^([a-z]{8})$~", $author ) && preg_match( "~^([a-z]{8})\@([a-z]{8})\.com$~", $email ) ) {
			//anon-###-##.relakks.com spammer pattern
			$wpss_error_code .= ' '.$pref.'REVDA-1050';
			$blacklisted = true;
			}
		// The 8's - also coming from from rackcentre.redstation.net.uk
		}
	
	if ( !empty( $wpss_error_code ) ) { $status = '2'; }
	$rev_dns_filter_data = array( 'status' => $status, 'error_code' => $wpss_error_code, 'blacklisted' => $blacklisted );
	return $rev_dns_filter_data;
	}

///BLACKLISTS - END

function spamshield_check_comment_type($commentdata) {

	// Timer Start - Comment Processing
	$commentdata['start_time_comment_processing'] = spamshield_microtime();

	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);
	
	$wpss_error_code 	= $wpss_js_key_test = '';
	$bypass_tests 		= false;

	// Add New Tests for Logging - BEGIN
	if ( !empty( $_POST['JSONST'] ) ) { $post_jsonst = $_POST['JSONST']; } else { $post_jsonst = ''; }
	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }	
	$post_ref2xjs_lc 	= strtolower($post_ref2xjs);
	if ( !empty( $post_ref2xjs ) ) {
		$ref2xJS = strtolower( addslashes( urldecode( $post_ref2xjs ) ) );
		$ref2xJS = str_replace( '%3a', ':', $ref2xJS );
		$ref2xJS = str_replace( ' ', '+', $ref2xJS );
		$wpss_javascript_page_referrer = esc_url_raw( $ref2xJS );
		}
	else { $wpss_javascript_page_referrer = '[None]'; }

	if ( $post_jsonst == 'NS1' ) { $wpss_jsonst = $post_jsonst; } else { $wpss_jsonst = '[None]'; }

	$commentdata['comment_post_title']			= get_the_title($commentdata['comment_post_ID']);
	$commentdata['comment_post_url']			= get_permalink($commentdata['comment_post_ID']);
	$commentdata['comment_post_type']			= get_post_type($commentdata['comment_post_ID']);
	$commentdata['comment_post_comments_open']	= comments_open($commentdata['comment_post_ID']);
	$commentdata['comment_post_pings_open']		= pings_open($commentdata['comment_post_ID']);
	$commentdata['javascript_page_referrer']	= $wpss_javascript_page_referrer;
	$commentdata['jsonst']						= $wpss_jsonst;
	
	// Add New Tests for Logging - END
	
	$wpss_comment_author_email_lc				= strtolower($commentdata['comment_author_email']);
	
	// User Authorization - BEGIN

	/* Don't use elseif() for these tests - stacking $wpss_error_code_addendum results */

	$wpss_error_code_addendum = '';
	// 1) is_admin() - If in Admin, don't test so user can respond directly to comments through admin
	if ( is_admin() ) {
			$bypass_tests = true;
			$wpss_error_code_addendum .= ' 1-ADMIN';
			}
	
	// 2) current_user_can('moderate_comments') - If user has Admin or Editor level access, don't test
	if ( current_user_can('moderate_comments') ) {
			$bypass_tests = true;
			$wpss_error_code_addendum .= ' 2-MODCOM';
			}

	if ( current_user_can('publish_posts') ) {
		// Added Author Requirement - current_user_can('publish_posts') - v 1.4.7
		global $current_user;  
		get_currentuserinfo();
		$wpss_display_name 		= $current_user->display_name;
		$wpss_user_firstname 	= $current_user->user_firstname;
		$wpss_user_lastname 	= $current_user->user_lastname;
		$wpss_user_email		= $current_user->user_email;
		$wpss_user_url			= $current_user->user_url;
		$wpss_user_login 		= $current_user->user_login;
		$wpss_user_id	 		= $current_user->ID;

		if ( !empty( $wpss_user_email ) ) {
			$wpss_user_email_parts				= explode( '@', $wpss_user_email );
			if ( !empty( $wpss_user_email_parts[1] ) ) {
			$wpss_user_email_domain				= $wpss_user_email_parts[1];
			}
			else { $wpss_user_email_domain 		= ''; }
			$wpss_user_email_domain_no_w3		= preg_replace( "~^(ww[w0-9]|m)\.~i", "", $wpss_user_email_domain );
			$wpss_user_email_domain_no_w3_regex	= spamshield_preg_quote( $wpss_user_email_domain_no_w3 );
			}
		if ( !empty( $wpss_user_url ) ) {
			$wpss_user_domain					= spamshield_get_domain( $wpss_user_url );
			$wpss_user_domain_no_w3 			= preg_replace( "~^(ww[w0-9]|m)\.~i", "", $wpss_user_domain );
			$wpss_user_domain_no_w3_regex		= spamshield_preg_quote( $wpss_user_domain_no_w3 );
			}
		$wpss_server_domain_no_w3 				= preg_replace( "~^(ww[w0-9]|m)\.~i", "", RSMP_SERVER_NAME );

		// 3) If user is logged in, Author, and email is from same domain as website, don't test
		if ( !empty( $wpss_user_email_domain_no_w3 ) && preg_match( "~(^|\.)$wpss_user_email_domain_no_w3_regex$~i", $wpss_server_domain_no_w3 ) ) {
			$bypass_tests = true;
			$wpss_error_code_addendum .= ' 3-AEMLDOM';
			}
		// 4) If user is logged in, Author, and url is same domain as website, don't test
		if ( !empty( $wpss_user_domain_no_w3 ) && preg_match( "~(^|\.)$wpss_user_domain_no_w3_regex$~i", $wpss_server_domain_no_w3 ) ) {
			$bypass_tests = true;
			$wpss_error_code_addendum .= ' 4-AURLDOM';
			}

		}

	// 5) Whitelist
	if ( !empty( $spamshield_options['enable_whitelist'] ) && spamshield_whitelist_check( $wpss_comment_author_email_lc ) ) {
		$bypass_tests = true;
		$wpss_error_code_addendum .= ' 5-WHITELIST';
		}

	if ( $bypass_tests != false ) {
		$wpss_error_code = 'No Error';
		//$wpss_error_code .= $wpss_error_code_addendum;
		}
	// Timer End - Part 1
	$wpss_end_time_part_1 = spamshield_microtime();
	$wpss_total_time_part_1 = spamshield_timer( $commentdata['start_time_comment_processing'], $wpss_end_time_part_1, false, 6 );
	$commentdata['total_time_part_1'] = $wpss_total_time_part_1;
	// User Authorization - END
	
	if ( $bypass_tests != true ) {
		// ONLY IF NOT ADMINS, EDITORS - BEGIN
		
		// First Do JS/Cookies Test
		
		/* JS/Cookies TEST - BEGIN */
		// Rework this
		if ( $commentdata['comment_type'] != 'trackback' && $commentdata['comment_type'] != 'pingback' ) {
			// If Comment is not a trackback or pingback

			// Timer Start - JS/Cookies Filter
			$wpss_start_time_jsck_filter = spamshield_microtime();
			$commentdata['start_time_jsck_filter'] = $wpss_start_time_jsck_filter;
			
			//add_filter('pre_comment_approved', 'spamshield_jsck_legacy_filter', 1);
			//add_filter('pre_comment_approved', 'spamshield_denied_post_js_cookie', 1); // When ready to switch from legacy code
			
			// LOG DATA - BEGIN
			//if ( !empty( $spamshield_options['comment_logging'] ) ) {
			
			$wpss_key_values 	= spamshield_get_key_values();
			$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
			$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
			$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
			$wpss_js_val 		= $wpss_key_values['wpss_js_val'];
			
			if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wpss_jsck_cookie_val = $_COOKIE[$wpss_ck_key]; }
			else { $wpss_jsck_cookie_val = ''; }
			if ( !empty( $_POST[$wpss_js_key] ) ) {
				$wpss_jsck_field_val 	= $_POST[$wpss_js_key]; //Comments Post Verification
				}
			else { $wpss_jsck_field_val = ''; }
			$wpss_cache_check = spamshield_check_cache_status();
			$wpss_cache_check_status = $wpss_cache_check['cache_check_status'];
			if ( $wpss_jsck_field_val == $wpss_js_val || $wpss_cache_check_status == 'ACTIVE' ) {
				$wpss_js_key_test = 'PASS';
				}
			if ( $wpss_jsck_cookie_val != $wpss_ck_val ) {
				// JS/CK Test 01
				// Failed the Cookie Test
				// Part of the JavaScript/Cookies Layer
				$wpss_error_code .= ' COOKIE-1';
				$commentdata['wpss_error_code'] = $wpss_error_code;
				return spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code );
				}
			//Test JS Referrer for Obvious Scraping Spambots
			if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }
			$post_ref2xjs_lc = strtolower($post_ref2xjs);
			if ( !empty( $post_ref2xjs ) && strpos( $post_ref2xjs_lc, 'ref2xjs' ) !== false ) {
				// JS/CK Test 02
				$wpss_error_code .= ' REF-2-1023-1';
				$commentdata['wpss_error_code'] = $wpss_error_code;
				return spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code );
				}
			// JavaScript Off NoScript Test - JSONST - will only be sent by Scraping Spambots
			if ( !empty( $_POST['JSONST'] ) ) { $post_jsonst = $_POST['JSONST']; } else { $post_jsonst = ''; }
			if ( $post_jsonst == 'NS1' ) {
				// JS/CK Test 03
				$wpss_error_code .= ' JSONST-1000-1';
				$commentdata['wpss_error_code'] = $wpss_error_code;
				return spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code );
				}
			if ( $wpss_js_key_test != 'PASS' ) {
				// JS/CK Test 04
				// Failed the FVFJS Test
				// Part of the JavaScript/Cookies Layer
				$wpss_error_code .= ' FVFJS-1';
				$commentdata['wpss_error_code'] = $wpss_error_code;
				return spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code );
				}
			// Timer End - JS/Cookies Filter
			$wpss_end_time_jsck_filter = spamshield_microtime();
			$wpss_total_time_jsck_filter = spamshield_timer( $wpss_start_time_jsck_filter, $wpss_end_time_jsck_filter, false, 6 );
			$commentdata['total_time_jsck_filter'] = $wpss_total_time_jsck_filter;
			if ( empty( $wpss_error_code ) ) {
				// Passed all tests, move on to next set...
				spamshield_update_sess_accept_status($commentdata,'a','Line: '.__LINE__);
				}
			else {
				spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
				if ( !empty( $spamshield_options['comment_logging'] ) ) {
					$wpss_error_code = ltrim($wpss_error_code);
					spamshield_log_data( $commentdata, $wpss_error_code );
					}
				add_filter('pre_comment_approved', 'spamshield_denied_post_js_cookie', 1);
				return $commentdata;
				}
			}
		/* JS/Cookies TEST - END */
		
		// 2ND - Trackbacks/Pingbacks
		if ( $commentdata['comment_type'] == 'trackback' || $commentdata['comment_type'] == 'pingback' ) {
			// 1ST - Trackback Content Filter
			$commentdata = spamshield_trackback_content_filter( $commentdata, $spamshield_options );
			$content_filter_status = $commentdata['content_filter_status'];
			if ( !empty( $content_filter_status ) ) {
				spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
				add_filter('pre_comment_approved', 'spamshield_denied_post', 1);
				return $commentdata;
				}
			// 2ND - Trackback False IP Check
			if ( $commentdata['comment_type'] == 'trackback' ) {
				$commentdata = spamshield_trackback_ip_filter( $commentdata, $spamshield_options );
				$content_filter_status = $commentdata['content_filter_status'];
				if ( !empty( $content_filter_status ) ) {
					spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
					add_filter('pre_comment_approved', 'spamshield_denied_post', 1);
					return $commentdata;
					}
				}
			}

		// 3RD - (Was 1ST), test if comment is too short
		$commentdata = spamshield_content_short( $commentdata, $spamshield_options );
		$content_short_status = $commentdata['content_short_status'];
		
		if ( empty( $content_short_status ) ) {
			// If it doesn't fail the comment length test, run it through the content filter
			// This is where the magic happens...
			
			// 4TH - Full Content Filter
			$commentdata = spamshield_content_filter( $commentdata, $spamshield_options );
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

		/* JS/Cookies TEST - OLD LOCATION */

		// ONLY IF NOT ADMINS, EDITORS - END
		}

	elseif ( !empty( $spamshield_options['comment_logging_all'] ) ) { // Bypass tests, ie. Admins
		if ( empty( $wpss_error_code ) || ( !empty( $wpss_error_code ) && !preg_match( "~^No Error~", $wpss_error_code ) ) ) {
			$wpss_error_code = 'No Error';
			$commentdata['wpss_error_code'] = $wpss_error_code;
			}
		spamshield_log_data( $commentdata, $wpss_error_code );
		}

	return $commentdata;
	}

// function spamshield_jsck_legacy_filter() was here

// REJECT BLOCKED SPAM COMMENTS - BEGIN

function spamshield_denied_post_js_cookie($approved) {
	// REJECT BOTS THAT FAIL JS/COOKIES TEST - BEGIN
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	$error_txt = spamshield_error_txt();
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	if ( !empty( $_COOKIE['SJECT15'] ) ) {
		$spamshield_jsck_error_ck_test = $_COOKIE['SJECT15']; // Default value is 'CKON15'
		}
	else {
		$spamshield_jsck_error_ck_test = '';
		}
	if ( $spamshield_jsck_error_ck_test == 'CKON15' ) {
		$spamshield_jsck_error_ck_status = __( 'PHP detects that cookies appear to be enabled.', WPSS_PLUGIN_NAME );
		}
	else {
		$spamshield_jsck_error_ck_status = __( 'PHP detects that cookies appear to be disabled.', WPSS_PLUGIN_NAME ) . ' <script type="text/javascript">if (navigator.cookieEnabled==true) { document.write(\'(' . __( 'However, JavaScript detects that cookies are enabled.', WPSS_PLUGIN_NAME ) . ')\'); } else { document.write(\'\(' . __( 'JavaScript also detects that cookies are disabled.', WPSS_PLUGIN_NAME ) . '\)\'); }; </script>';
		}
	
	$spamshield_jsck_error_message_standard = '<strong>'.$error_txt.':</strong> ' . __( 'Sorry, there was an error. Please be sure JavaScript and Cookies are enabled in your browser and try again.', WPSS_PLUGIN_NAME );

	$spamshield_jsck_error_message_detailed = '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'JavaScript and Cookies are required in order to post a comment.', WPSS_PLUGIN_NAME ) . '</strong><br /><br />'."\n";
	$spamshield_jsck_error_message_detailed .= '<noscript>' . __( 'Status: JavaScript is currently disabled.', WPSS_PLUGIN_NAME ) . '<br /><br /></noscript>'."\n";
	$spamshield_jsck_error_message_detailed .= '<strong>' . __( 'Please be sure JavaScript and Cookies are enabled in your browser. Then, please hit the back button on your browser, and try posting your comment again. (You may need to reload the page.)', WPSS_PLUGIN_NAME ) . '</strong><br /><br />'."\n";
	$spamshield_jsck_error_message_detailed .= '<br /><hr noshade />'."\n";
	if ( $spamshield_jsck_error_ck_test == 'CKON15' ) {
		$spamshield_jsck_error_message_detailed .= __( 'If you feel you have received this message in error (for example if JavaScript and Cookies are in fact enabled and you have tried to post several times), there is most likely a technical problem (could be a plugin conflict or misconfiguration). Please contact the author of this site, and let them know they need to look into it.', WPSS_PLUGIN_NAME ) . '<br />'."\n";
		$spamshield_jsck_error_message_detailed .= '<hr noshade /><br />'."\n";
		}
	$spamshield_jsck_error_message_detailed .= '</span>'."\n";

	$args = array( 'response' => '403' );
	wp_die( __( $spamshield_jsck_error_message_detailed, WPSS_PLUGIN_NAME ), '', $args );
	return false;
	// REJECT BOTS THAT FAIL JS/COOKIES TEST - END
	}
	
function spamshield_denied_trackback_ip_filter($approved) {
	// REJECT BASED ON TRACKBACK IP FILTER - BEGIN
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	$error_txt = spamshield_error_txt();
	// Update message text. Not like it matters for trackbacks.
	$spamshield_trackback_ip_filter_error_message = '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'Your location has been identified as part of a reported spam network. Comments have been disabled to prevent spam.', WPSS_PLUGIN_NAME ) . '</strong><br /><br /></span>'."\n";
	
	$args = array( 'response' => '403' );
	wp_die( __( $spamshield_trackback_ip_filter_error_message, WPSS_PLUGIN_NAME ), '', $args );
	return false;
	// REJECT BASED ON TRACKBACK IP FILTER - END
	}

function spamshield_denied_post($approved) {
	// REJECT SPAM - BEGIN
	if ( !empty( $_SESSION['wpss_commentdata_'.RSMP_HASH] ) ) { $commentdata = $_SESSION['wpss_commentdata_'.RSMP_HASH]; } else { $commentdata = ''; }
	if ( !empty( $_SESSION['wpss_comment_author_'.RSMP_HASH] ) ) { $wpss_comment_author = $_SESSION['wpss_comment_author_'.RSMP_HASH]; } else { $wpss_comment_author = ''; }
	//if ( !empty( $_SESSION['wpss_comment_author_email_'.RSMP_HASH] ) ) { $wpss_comment_author_email = $_SESSION['wpss_comment_author_email_'.RSMP_HASH]; } else { $wpss_comment_author_email = ''; }
	//if ( !empty( $_SESSION['wpss_comment_author_url_'.RSMP_HASH] ) ) { $wpss_comment_author_url = $_SESSION['wpss_comment_author_url_'.RSMP_HASH]; } else { $wpss_comment_author_url = ''; }
	if ( !empty( $commentdata['wpss_error_code'] ) ) { $wpss_error_code = ltrim( $commentdata['wpss_error_code'] ); } else { $wpss_error_code = ''; }

	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	$error_txt = spamshield_error_txt();
	$spamshield_filter_error_message_standard = '<span style="font-size:12px;"><strong>'.$error_txt.':</strong> ' . __( 'Comments have been temporarily disabled to prevent spam. Please try again later.', WPSS_PLUGIN_NAME ) . '</span>'; // Stop spammers without revealing why.
	
	$spamshield_filter_error_message_detailed = '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'Your comment appears to be spam. We don\'t really appreciate spam here.', WPSS_PLUGIN_NAME ) . '</strong><br /><br />'."\n";
	if ( $wpss_error_code == '10500A-BL' && strpos( $wpss_comment_author, '@' ) !== false ) {
		$spamshield_filter_error_message_detailed .= sprintf( __( '"%1$s" appears to be spam. Please enter a different value in the <strong> %2$s </strong> field.', WPSS_PLUGIN_NAME ), sanitize_text_field($wpss_comment_author), __( 'Name' ) ) . '<br /><br />'."\n";
		}
	$spamshield_filter_error_message_detailed .= __( 'Please go back and try to say something useful.', WPSS_PLUGIN_NAME ) . '</span>'."\n";
	
	if ( is_user_logged_in() ) {
		$spamshield_filter_error_message_detailed .= '<br /><br />'."\n";
		$spamshield_filter_error_message_detailed .= '<span style="font-size:12px;">' . __( 'If you are a logged in user, and you are seeing this message repeatedly, then you may need to check your registered user information for spam data.', WPSS_PLUGIN_NAME ) . '</span>'."\n";
		}
	$args = array( 'response' => '403' );
	wp_die( __( $spamshield_filter_error_message_detailed, WPSS_PLUGIN_NAME ), '', $args );
	return false;
	// REJECT SPAM - END
	}

function spamshield_denied_post_short($approved) {
	// REJECT SHORT COMMENTS - BEGIN
	//if ( !empty( $_SESSION['wpss_commentdata_'.RSMP_HASH] ) ) { $commentdata = $_SESSION['wpss_commentdata_'.RSMP_HASH]; } else { $commentdata = ''; }
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	$error_txt = spamshield_error_txt();

	$args = array( 'response' => '403' );
	wp_die( '<span style="font-size:12px;"><strong>'.$error_txt.':</strong> ' . __( 'Your comment was too short. Please try to say something useful.', WPSS_PLUGIN_NAME ) . '</span>', '', $args );
	return false;
	// REJECT SHORT COMMENTS - END
	}
	
function spamshield_denied_post_content_filter($approved) {
	// REJECT BASED ON CONTENT FILTER - BEGIN
	//if ( !empty( $_SESSION['wpss_commentdata_'.RSMP_HASH] ) ) { $commentdata = $_SESSION['wpss_commentdata_'.RSMP_HASH]; } else { $commentdata = ''; }
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	$error_txt = spamshield_error_txt();
	$spamshield_content_filter_error_message_detailed = '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'Your location has been identified as part of a reported spam network. Comments have been disabled to prevent spam.', WPSS_PLUGIN_NAME ) . '</strong><br /><br /></span>'."\n";
	
	$args = array( 'response' => '403' );
	wp_die( __( $spamshield_content_filter_error_message_detailed, WPSS_PLUGIN_NAME ), '', $args );
	return false;
	// REJECT BASED ON COMMENT FILTER - END
	}
	
function spamshield_denied_post_proxy($approved) {
	// REJECT PROXY COMMENTERS - BEGIN
	//if ( !empty( $_SESSION['wpss_commentdata_'.RSMP_HASH] ) ) { $commentdata = $_SESSION['wpss_commentdata_'.RSMP_HASH]; } else { $commentdata = ''; }
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	$error_txt = spamshield_error_txt();
	$spamshield_proxy_error_message_detailed = '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'Your comment has been blocked because the website owner has set their spam filter to not allow comments from users behind proxies.', WPSS_PLUGIN_NAME ) . '</strong><br/><br/>' . __( 'If you are a regular commenter or you feel that your comment should not have been blocked, please contact the site owner and ask them to modify this setting.', WPSS_PLUGIN_NAME ) . '<br /><br /></span>'."\n";
	
	$args = array( 'response' => '403' );
	wp_die( __( $spamshield_proxy_error_message_detailed, WPSS_PLUGIN_NAME ), '', $args );
	return false;
	// REJECT PROXY COMMENTERS - END
	}

function spamshield_denied_post_wp_blacklist($approved) {
	// REJECT BLACKLISTED COMMENTERS - BEGIN
	//if ( !empty( $_SESSION['wpss_commentdata_'.RSMP_HASH] ) ) { $commentdata = $_SESSION['wpss_commentdata_'.RSMP_HASH]; } else { $commentdata = ''; }
	// Update Count
	update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
	spamshield_ak_accuracy_fix();
	//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	$error_txt = spamshield_error_txt();
	$spamshield_blacklist_error_message_detailed = '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'Your comment has been blocked based on the website owner\'s blacklist settings.', WPSS_PLUGIN_NAME ) . '</strong><br/><br/>' . __( 'If you feel this is in error, please contact the site owner by some other method.', WPSS_PLUGIN_NAME ) . '<br /><br /></span>'."\n";
	
	$args = array( 'response' => '403' );
	wp_die( __( $spamshield_blacklist_error_message_detailed, WPSS_PLUGIN_NAME ), '', $args );
	return false;
	// REJECT BLACKLISTED COMMENTERS - END
	}

// REJECT BLOCKED SPAM COMMENTS - BEGIN

// COMMENT SPAM FILTERS - BEGIN

function spamshield_content_short( $commentdata, $spamshield_options ) {
	// COMMENT LENGTH CHECK - BEGIN

	// Timer Start  - Content Filter
	if ( empty( $commentdata['start_time_content_filter'] ) ) {
		$wpss_start_time_content_filter = spamshield_microtime();
		$commentdata['start_time_content_filter'] = $wpss_start_time_content_filter;
		}

	$content_short_status 						= $wpss_error_code = ''; // Must go before tests
	$commentdata_comment_content				= $commentdata['comment_content'];
	$commentdata_comment_content_lc				= strtolower($commentdata_comment_content);
	$commentdata_comment_content_lc_deslashed	= stripslashes($commentdata_comment_content_lc);
	$comment_length 							= spamshield_strlen($commentdata_comment_content_lc_deslashed);
	$comment_min_length 						= 15;
	$commentdata_comment_type					= $commentdata['comment_type'];
	if ( $comment_length < $comment_min_length && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		$content_short_status = true;
		$wpss_error_code .= ' SHORT15';
		}
	if ( !empty( $wpss_error_code ) ) {
		//spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
		$wpss_error_code = ltrim($wpss_error_code);
		spamshield_update_session_data($spamshield_options);
		// Timer End - Content Filter
		$wpss_end_time_content_filter 				= spamshield_microtime();
		$wpss_total_time_content_filter 			= spamshield_timer( $commentdata['start_time_content_filter'], $wpss_end_time_content_filter, false, 6 );
		$commentdata['total_time_content_filter']	= $wpss_total_time_content_filter;
		if ( !empty( $spamshield_options['comment_logging'] ) ) {
			spamshield_log_data( $commentdata, $wpss_error_code );
			}
		}

	$commentdata['content_short_status'] = $content_short_status;
	$commentdata['wpss_error_code'] = $wpss_error_code;
	return $commentdata;
	// COMMENT LENGTH CHECK - END
	}

/*
function spamshield_js_cookie_filter( $commentdata, $spamshield_options ) {
	// JavaScript and Cookies Layer
	// Coming soon
	}
*/

function spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code ) {
	// Exit JS/CK Filter
	// This fires when a JavaScript/Cookies spam test is failed.
	
	$commentdata['wpss_error_code'] = $wpss_error_code;
	// spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	$wpss_error_code = ltrim($wpss_error_code);
	// Timer End - Content Filter
	$wpss_end_time_jsck_filter 				= spamshield_microtime();
	$wpss_total_time_jsck_filter 			= spamshield_timer( $commentdata['start_time_jsck_filter'], $wpss_end_time_jsck_filter, false, 6 );
	$commentdata['total_time_jsck_filter']	= $wpss_total_time_jsck_filter;
	if ( !empty( $spamshield_options['comment_logging'] ) ) {
		spamshield_log_data( $commentdata, $wpss_error_code );
		}
	add_filter('pre_comment_approved', 'spamshield_denied_post_js_cookie', 1);
	return $commentdata;
	}

function spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status ) {
	// Exit Content Filter
	// This fires when an algo spam test is failed.
	
	$commentdata['wpss_error_code'] = $wpss_error_code;
	// spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
	$wpss_error_code = ltrim($wpss_error_code);
	// Timer End - Content Filter
	$wpss_end_time_content_filter 				= spamshield_microtime();
	$wpss_total_time_content_filter 			= spamshield_timer( $commentdata['start_time_content_filter'], $wpss_end_time_content_filter, false, 6 );
	$commentdata['total_time_content_filter']	= $wpss_total_time_content_filter;
	if ( !empty( $spamshield_options['comment_logging'] ) ) {
		spamshield_log_data( $commentdata, $wpss_error_code );
		}
	$commentdata['content_filter_status'] = $content_filter_status;
	return $commentdata;
	}

function spamshield_trackback_content_filter( $commentdata, $spamshield_options ) {
	// Trackback Content Filter
	// This will knock out 98% of Trackback Spam
	// Keeping this separate and before trackback IP filter because it's fast
	// If passes this, then next filter will take out the rest
	
	// Timer Start  - Content Filter
	if ( empty( $commentdata['start_time_content_filter'] ) ) {
		$wpss_start_time_content_filter = spamshield_microtime();
		$commentdata['start_time_content_filter'] = $wpss_start_time_content_filter;
		}

	$content_filter_status 						= $wpss_error_code = ''; // Must go before tests
	
	$block_all_trackbacks 						= $spamshield_options['block_all_trackbacks'];
	$block_all_pingbacks 						= $spamshield_options['block_all_pingbacks'];
	
	$commentdata_comment_type					= $commentdata['comment_type'];

	$commentdata_comment_author					= $commentdata['comment_author'];
	$commentdata_comment_author_deslashed		= stripslashes($commentdata_comment_author);
	$commentdata_comment_author_lc				= strtolower($commentdata_comment_author);
	$commentdata_comment_author_lc_deslashed	= stripslashes($commentdata_comment_author_lc);
	$commentdata_comment_author_url				= $commentdata['comment_author_url'];
	$commentdata_comment_author_url_lc			= strtolower($commentdata_comment_author_url);
	$commentdata_comment_author_url_domain_lc	= spamshield_get_domain($commentdata_comment_author_url_lc);
	$commentdata_comment_content				= $commentdata['comment_content'];
	$commentdata_comment_content_lc				= strtolower($commentdata_comment_content);
	$commentdata_comment_content_lc_deslashed	= stripslashes($commentdata_comment_content_lc);

	$commentdata_remote_addr					= $_SERVER['REMOTE_ADDR'];
	$commentdata_remote_addr_lc					= strtolower($commentdata_remote_addr);
	if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) { $commentdata_user_agent = trim($_SERVER['HTTP_USER_AGENT']); } else { $commentdata_user_agent = ''; }
	$commentdata_user_agent_lc					= strtolower($commentdata_user_agent);
	$commentdata_user_agent_lc_word_count 		= spamshield_count_words($commentdata_user_agent_lc);

	$commentdata_comment_author_lc_spam_strong = '<strong>'.$commentdata_comment_author_lc_deslashed.'</strong>'; // Trackbacks

	$commentdata_comment_author_lc_spam_strong_dot1 = '...</strong>'; // Trackbacks
	$commentdata_comment_author_lc_spam_strong_dot2 = '...</b>'; // Trackbacks
	$commentdata_comment_author_lc_spam_strong_dot3 = '<strong>...'; // Trackbacks
	$commentdata_comment_author_lc_spam_strong_dot4 = '<b>...'; // Trackbacks
	$commentdata_comment_author_lc_spam_a1 = $commentdata_comment_author_lc_deslashed.'</a>'; // Trackbacks/Pingbacks
	$commentdata_comment_author_lc_spam_a2 = $commentdata_comment_author_lc_deslashed.' </a>'; // Trackbacks/Pingbacks
	
	if ( $commentdata_remote_addr == RSMP_SERVER_ADDR && $commentdata['comment_type'] == 'pingback' ) { $local_pingback = true; } else { $local_pingback = false; }
		
	if ( !empty( $block_all_trackbacks ) && $commentdata['comment_type'] == 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' BLOCKING-TRACKBACKS ';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( !empty( $block_all_pingbacks ) && $commentdata['comment_type'] == 'pingback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' BLOCKING-PINGBACKS';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	
	// Test User-Agents
	if ( empty( $commentdata_user_agent_lc ) ) {
		// There is no reason for a blank UA String, unless it's been altered.
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; } // Was 2, changed to 1 - V1.0.0.0
		$wpss_error_code .= ' TUA1001';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	$trackback_is_mobile = wp_is_mobile();
	$trackback_is_firefox = spamshield_is_firefox();
	global $is_chrome, $is_IE, $is_gecko, $is_opera, $is_safari, $is_iphone, $is_lynx, $is_NS4;
	if ( $trackback_is_mobile || $trackback_is_firefox || $is_chrome || $is_IE || $is_gecko || $is_opera || $is_safari || $is_iphone || $is_lynx || $is_NS4 ) {
		// There is no reason for a normal browser's UA String to be used in a Trackback/Pingback, unless it's been altered.
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' TUA1002';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( !empty( $commentdata_user_agent_lc ) && $commentdata_user_agent_lc_word_count < 3 ) {
		if ( strpos( $commentdata_user_agent_lc, 'movabletype' ) === false && $commentdata_comment_type == 'trackback' ) {
			// Another test for altered UA's.
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; } // Was 2, changed to 1 - V1.0.0.0
			$wpss_error_code .= ' TUA1003';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	if ( strpos( $commentdata_user_agent_lc, 'libwww' ) !== false 
		|| strpos( $commentdata_user_agent_lc, 'nutch' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'larbin' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'jakarta' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'java' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'mechanize' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'phpcrawl' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'iopus-' ) !== false ) {
		// There is no reason for a human or Trackback/Pingback to use one of these UA strings. Commonly used to attack/spam WP.
		$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
		$wpss_error_code .= ' TUA1004';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// TRACKBACK/PINGBACK SPECIFIC TESTS -  BEGIN
	
	// TRACKBACK COOKIE TEST - Trackbacks can't have cookies, but some fake ones do
	if ( !empty( $_COOKIE ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T-COOKIE';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// Number of occurrences of 'http://' in comment_content
	$filter_t1_count_http 	= spamshield_substr_count( $commentdata_comment_content_lc_deslashed, 'http://' );
	$filter_t1_count_https 	= spamshield_substr_count( $commentdata_comment_content_lc_deslashed, 'https://' );
	$filter_t1_count 		= $filter_t1_count_http + $filter_t1_count_https;
	$filter_t1_limit 		= 0;
	if ( empty( $local_pingback ) && $filter_t1_count > $filter_t1_limit ) {
	//if ( strpos( $commentdata_comment_content_lc_deslashed, 'http://' ) !== false || strpos( $commentdata_comment_content_lc_deslashed, 'https://' ) !== false ) {
		// Genuine trackbacks should have text only, not hyperlinks
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T1-HT-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	if ( preg_match( "~\[\.{1,3}\]\s*\[\.{1,3}\]~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T200-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	if ( $commentdata_comment_type == 'trackback' && strpos( $commentdata_user_agent_lc, 'wordpress' ) !== false ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T3000-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// Testing if Bot Uses Faked User-Agent for WordPress version that doesn't exist yet
	// Check History of WordPress User-Agents and Keep up to Date
	// Current: 'The Incutio XML-RPC PHP Library -- WordPress/4.0.1'
	if ( empty( $local_pingback ) && strpos( $commentdata_user_agent_lc, 'incutio xml-rpc -- wordpress/' ) !== false ) {
		$wp_ua_search_array = array( 'mu', 'wordpress-mu-' );
		$commentdata_user_agent_lc_wp = str_replace ( $wp_ua_search_array, '', $commentdata_user_agent_lc);
		$commentdata_user_agent_lc_explode = explode( '/', $commentdata_user_agent_lc_wp );
		// Changed to version_compare in 1.0.1.0
		if ( version_compare( $commentdata_user_agent_lc_explode[1], WPSS_MAX_WP_VERSION, '>' ) && $commentdata_user_agent_lc_explode[1] !='MU' ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' T1001-1';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	if ( empty( $local_pingback ) && strpos( $commentdata_user_agent_lc, 'the incutio xml-rpc php library -- wordpress/' ) !== false ) {
		$wp_ua_search_array = array( 'mu', 'wordpress-mu-' );
		$commentdata_user_agent_lc_wp = str_replace ( $wp_ua_search_array, '', $commentdata_user_agent_lc);
		$commentdata_user_agent_lc_explode = explode( '/', $commentdata_user_agent_lc_wp );
		if ( version_compare( $commentdata_user_agent_lc_explode[1], WPSS_MAX_WP_VERSION, '>' ) && $commentdata_user_agent_lc_explode[1] !='MU' ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' T1002-1';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	if ( empty( $local_pingback ) && strpos( $commentdata_user_agent_lc, 'wordpress/' ) === 0 ) {
		$wp_ua_search_array = array( 'mu', 'wordpress-mu-' );
		$commentdata_user_agent_lc_wp = str_replace ( $wp_ua_search_array, '', $commentdata_user_agent_lc);
		$commentdata_user_agent_lc_explode = explode( '/', $commentdata_user_agent_lc_wp );
		if ( version_compare( $commentdata_user_agent_lc_explode[1], WPSS_MAX_WP_VERSION, '>' ) && $commentdata_user_agent_lc_explode[1] !='MU' ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' T1003-1';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	if ( empty( $local_pingback ) && $commentdata_comment_author_deslashed == $commentdata_comment_author_lc_deslashed && preg_match( "~([a-z0-9\s\-_\.']+)~i", $commentdata_comment_author_lc_deslashed ) ) {
		// Check to see if Comment Author is lowercase. Normal blog ping Authors are properly capitalized. No brainer.
		// Added second test to only run when using standard alphabet.
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T1010-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// IP / PROXY INFO - BEGIN
	global $wpss_ip_proxy_info;
	if ( empty( $wpss_ip_proxy_info ) ) {
		$wpss_ip_proxy_info 		= spamshield_ip_proxy_info();
		}
	$ip_proxy_info					= $wpss_ip_proxy_info;
	$ip 							= $ip_proxy_info['ip'];
	$reverse_dns 					= $ip_proxy_info['reverse_dns'];
	$reverse_dns_lc 				= $ip_proxy_info['reverse_dns_lc'];
	$reverse_dns_lc_regex 			= $ip_proxy_info['reverse_dns_lc_regex'];
	$reverse_dns_lc_rev 			= $ip_proxy_info['reverse_dns_lc_rev'];
	$reverse_dns_lc_rev_regex		= $ip_proxy_info['reverse_dns_lc_rev_regex'];
	$reverse_dns_ip 				= $ip_proxy_info['reverse_dns_ip'];
	$reverse_dns_ip_regex 			= $ip_proxy_info['reverse_dns_ip_regex'];
	$reverse_dns_authenticity 		= $ip_proxy_info['reverse_dns_authenticity'];
	$ip_proxy_via 					= $ip_proxy_info['ip_proxy_via'];
	$ip_proxy_via_lc 				= $ip_proxy_info['ip_proxy_via_lc'];
	$masked_ip 						= $ip_proxy_info['masked_ip'];
	$ip_proxy 						= $ip_proxy_info['ip_proxy'];
	$ip_proxy_short 				= $ip_proxy_info['ip_proxy_short'];
	$ip_proxy_data 					= $ip_proxy_info['ip_proxy_data'];
	$proxy_status 					= $ip_proxy_info['proxy_status'];
	$ip_proxy_chrome_compression	= $ip_proxy_info['ip_proxy_chrome_compression'];
	// IP / PROXY INFO - END

	if ( empty( $local_pingback ) && $ip_proxy == 'PROXY DETECTED' ) {
		// Check to see if Trackback/Pingback is using proxy. Real ones don't do that since they come directly from a website/server.
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T1011-FPD-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// REVDNS FILTER
	$rev_dns_filter_data = spamshield_revdns_filter( 'trackback', $content_filter_status, $ip, $reverse_dns_lc, $commentdata_comment_author_lc_deslashed );
	$revdns_blacklisted = $rev_dns_filter_data['blacklisted'];
	if ( !empty( $revdns_blacklisted ) ) {
		$content_filter_status = $rev_dns_filter_data['status'];
		$wpss_error_code .= $rev_dns_filter_data['error_code'];
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// MISC
	if ( $commentdata_comment_content == '[...] read more [...]' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T1020-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	$replace_apostrophes						= array('’','`','&acute;','&grave;','&#39;','&#96;','&#101;','&#145;','&#146;','&#158;','&#180;','&#207;','&#208;','&#8216;','&#8217;');
	$commentdata_comment_content_lc_norm_apost 	= str_replace($replace_apostrophes,"'",$commentdata_comment_content_lc_deslashed);
	$SplogTrackbackPhrase1 						= 'an interesting post today.here\'s a quick excerpt';
	$SplogTrackbackPhrase1a 					= 'an interesting post today.here&#8217;s a quick excerpt';
	$SplogTrackbackPhrase2 						= 'an interesting post today. here\'s a quick excerpt';
	$SplogTrackbackPhrase2a 					= 'an interesting post today. here&#8217;s a quick excerpt';
	$SplogTrackbackPhrase3 						= 'an interesting post today onhere\'s a quick excerpt';
	$SplogTrackbackPhrase3a						= 'an interesting post today onhere&#8217;s a quick excerpt';
	$SplogTrackbackPhrase4 						= 'read the rest of this great post here';
	$SplogTrackbackPhrase5 						= 'here to see the original:';
	$SplogTrackbackPhrase20a 					= 'an interesting post today on';
	$SplogTrackbackPhrase20b 					= 'here\'s a quick excerpt';
	$SplogTrackbackPhrase20c 					= 'here&#8217;s a quick excerpt';
	if ( strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase1 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase1a ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase2 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase2a ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase3 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase3a ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase4 ) !== false || strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase5 ) !== false || ( strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase20a ) !== false && ( strpos( $commentdata_comment_content_lc_norm_apost, $SplogTrackbackPhrase20b ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $SplogTrackbackPhrase20c ) !== false ) ) ) {
		// Check to see if common patterns exist in comment content.
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T2002-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong ) !== false ) {
		// Check to see if Comment Author is repeated in content, enclosed in <strong> tags.
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T2003-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_a1 ) !== false || strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_a2 ) !== false ) {
		// Check to see if Comment Author is repeated in content, enclosed in <a> tags.
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T2004-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot1 ) !== false ) {
		// Check to see if Phrase... in bold is in content
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T2005-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot2 ) !== false ) {
		// Check to see if Phrase... in bold is in content
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T2006-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot3 ) !== false ) {
		// Check to see if Phrase... in bold is in content
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T2007-1-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( strpos( $commentdata_comment_content_lc_deslashed, $commentdata_comment_author_lc_spam_strong_dot4 ) !== false ) {
		// Check to see if Phrase... in bold is in content
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T2007-2-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( preg_match( "~<strong>(.*)?\[trackback\](.*)?</strong>~i", $commentdata_comment_content_lc_deslashed ) ) {
		// Check to see if Phrase... in bold is in content
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T2010-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	// Check to see if keyword phrases in url match Comment Author - spammers do this to get links with desired keyword anchor text.
	$Domains = array('.abogado','.ac','.academy','.accountants','.active','.actor','.ad','.adult','.ae','.aero','.af','.ag','.agency','.ai','.airforce','.al','.allfinanz','.alsace','.am','.amsterdam','.an','.android','.ao','.aq','.aquarelle','.ar','.archi','.army','.arpa','.as','.asia','.associates','.at','.attorney','.au','.auction','.audio','.autos','.aw','.ax','.axa','.az','.ba','.band','.bank','.bar','.barclaycard','.barclays','.bargains','.bayern','.bb','.bd','.be','.beer','.berlin','.best','.bf','.bg','.bh','.bi','.bid','.bike','.bio','.biz','.bj','.bl','.black','.blackfriday','.bloomberg','.blue','.bm','.bmw','.bn','.bnpparibas','.bo','.boo','.boutique','.bq','.br','.brussels','.bs','.bt','.budapest','.build','.builders','.buzz','.bv','.bw','.by','.bz','.bzh','.ca','.cab','.cal','.camera','.camp','.cancerresearch','.capetown','.capital','.caravan','.cards','.care','.career','.careers','.cartier','.casa','.cash','.cat','.catering','.cc','.cd','.center','.ceo','.cern','.cf','.cg','.ch','.channel','.cheap','.christmas','.chrome','.church','.ci','.citic','.city','.ck','.cl','.claims','.cleaning','.click','.clinic','.clothing','.club','.cm','.cn','.co','.coach','.codes','.coffee','.college','.cologne','.com','.community','.company','.computer','.condos','.construction','.consulting','.contractors','.cooking','.cool','.coop','.country','.cr','.credit','.creditcard','.cricket','.crs','.cruises','.cu','.cuisinella','.cv','.cw','.cx','.cy','.cymru','.cz','.dabur','.dad','.dance','.dating','.day','.dclk','.de','.deals','.degree','.delivery','.democrat','.dental','.dentist','.desi','.design','.dev','.diamonds','.diet','.digital','.direct','.directory','.discount','.dj','.dk','.dm','.dnp','.do','.docs','.domains','.doosan','.durban','.dvag','.dz','.eat','.ec','.edu','.education','.ee','.eg','.eh','.email','.emerck','.energy','.engineer','.engineering','.enterprises','.equipment','.er','.es','.esq','.estate','.et','.eu','.eurovision','.eus','.events','.everbank','.exchange','.expert','.exposed','.fail','.farm','.fashion','.feedback','.fi','.finance','.financial','.firmdale','.fish','.fishing','.fit','.fitness','.fj','.fk','.flights','.florist','.flowers','.flsmidth','.fly','.fm','.fo','.foo','.forsale','.foundation','.fr','.frl','.frogans','.fund','.furniture','.futbol','.ga','.gal','.gallery','.garden','.gb','.gbiz','.gd','.ge','.gent','.gf','.gg','.ggee','.gh','.gi','.gift','.gifts','.gives','.gl','.glass','.gle','.global','.globo','.gm','.gmail','.gmo','.gmx','.gn','.goog','.google','.gop','.gov','.gp','.gq','.gr','.graphics','.gratis','.green','.gripe','.gs','.gt','.gu','.guide','.guitars','.guru','.gw','.gy','.hamburg','.hangout','.haus','.healthcare','.help','.here','.hermes','.hiphop','.hiv','.hk','.hm','.hn','.holdings','.holiday','.homes','.horse','.host','.hosting','.house','.how','.hr','.ht','.hu','.ibm','.id','.ie','.ifm','.il','.im','.immo','.immobilien','.in','.industries','.info','.ing','.ink','.institute','.insure','.int','.international','.investments','.io','.iq','.ir','.irish','.is','.it','.iwc','.je','.jetzt','.jm','.jo','.jobs','.joburg','.jp','.juegos','.kaufen','.kddi','.ke','.kg','.kh','.ki','.kim','.kitchen','.kiwi','.km','.kn','.koeln','.kp','.kr','.krd','.kred','.kw','.ky','.kyoto','.kz','.la','.lacaixa','.land','.lat','.latrobe','.lawyer','.lb','.lc','.lds','.lease','.legal','.lgbt','.li','.lidl','.life','.lighting','.limo','.link','.lk','.loans','.london','.lotte','.lotto','.lr','.ls','.lt','.ltda','.lu','.luxe','.luxury','.lv','.ly','.ma','.madrid','.maison','.management','.mango','.market','.marketing','.marriott','.mc','.md','.me','.media','.meet','.melbourne','.meme','.memorial','.menu','.mf','.mg','.mh','.miami','.mil','.mini','.mk','.ml','.mm','.mn','.mo','.mobi','.moda','.moe','.monash','.money','.mormon','.mortgage','.moscow','.motorcycles','.mov','.mp','.mq','.mr','.ms','.mt','.mu','.museum','.mv','.mw','.mx','.my','.mz','.na','.nagoya','.name','.navy','.nc','.ne','.net','.network','.neustar','.new','.nexus','.nf','.ng','.ngo','.nhk','.ni','.ninja','.nl','.no','.np','.nr','.nra','.nrw','.nu','.nyc','.nz','.okinawa','.om','.one','.ong','.onl','.ooo','.org','.organic','.osaka','.otsuka','.ovh','.pa','.paris','.partners','.parts','.party','.pe','.pf','.pg','.ph','.pharmacy','.photo','.photography','.photos','.physio','.pics','.pictures','.pink','.pizza','.pk','.pl','.place','.plumbing','.pm','.pn','.pohl','.poker','.porn','.post','.pr','.praxi','.press','.pro','.prod','.productions','.prof','.properties','.property','.ps','.pt','.pub','.pw','.py','.qa','.qpon','.quebec','.re','.realtor','.recipes','.red','.rehab','.reise','.reisen','.reit','.ren','.rentals','.repair','.report','.republican','.rest','.restaurant','.reviews','.rich','.rio','.rip','.ro','.rocks','.rodeo','.rs','.rsvp','.ru','.ruhr','.rw','.ryukyu','.sa','.saarland','.sale','.samsung','.sarl','.sb','.sc','.sca','.scb','.schmidt','.schule','.schwarz','.science','.scot','.sd','.se','.services','.sew','.sexy','.sg','.sh','.shiksha','.shoes','.shriram','.si','.singles','.sj','.sk','.sky','.sl','.sm','.sn','.so','.social','.software','.sohu','.solar','.solutions','.soy','.space','.spiegel','.sr','.ss','.st','.su','.supplies','.supply','.support','.surf','.surgery','.suzuki','.sv','.sx','.sy','.sydney','.systems','.sz','.taipei','.tatar','.tattoo','.tax','.tc','.td','.technology','.tel','.temasek','.tf','.tg','.th','.tienda','.tips','.tires','.tirol','.tj','.tk','.tl','.tm','.tn','.to','.today','.tokyo','.tools','.top','.town','.toys','.tp','.tr','.trade','.training','.travel','.trust','.tt','.tui','.tv','.tw','.tz','.ua','.ug','.uk','.um','.university','.uno','.uol','.us','.uy','.uz','.va','.vacations','.vc','.ve','.vegas','.ventures','.versicherung','.vet','.vg','.vi','.viajes','.video','.villas','.vision','.vlaanderen','.vn','.vodka','.vote','.voting','.voto','.voyage','.vu','.wales','.wang','.watch','.webcam','.website','.wed','.wedding','.wf','.whoswho','.wien','.wiki','.williamhill','.wme','.work','.works','.world','.ws','.wtc','.wtf','.xxx','.xyz','.yachts','.yandex','.ye','.yoga','.yokohama','.youtube','.yt','.za','.zip','.zm','.zone','.zuerich','.zw');
	// from http://www.iana.org/domains/root/db/ - Updated in 1.7.3
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
	// Start with url and convert to text phrase for matching against author.
	$i = 0;
	while ( $i < $KeywordURLPhrasesCount ) {
		if ( $KeywordURLPhrases[$i] == $commentdata_comment_author_lc_deslashed ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' T3001-1';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		if ( $KeywordURLPhrases[$i] == $commentdata_comment_content_lc_deslashed ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' T3002-1';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
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
			$wpss_error_code .= ' T3003-1-1';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		elseif ( $KeywordCommentAuthorPhrase2SubStrCount >= 1 ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' T3003-2-1';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		elseif ( $KeywordCommentAuthorPhrase3SubStrCount >= 1 ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' T3003-3-1';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
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
			$wpss_error_code .= ' T9000-'.$i.'-1';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		$i++;
		}
	
	// Blacklisted Domains Check
	if ( spamshield_domain_blacklist_chk( $commentdata_comment_author_url_domain_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T-10500AU-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// Check for URL Shorteners, Bogus Long URLs, Social Media, and Misc Spam Domains
	if ( spamshield_at_link_spam_url_chk( $commentdata_comment_author_url_lc, 'trackback' ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' T-10510AU-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// TRACKBACK/PINGBACK SPECIFIC TESTS -  END

	/*
	return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
	*/

	// After spamshield_exit_content_filter() implemented, can remove following code - BEGIN
	if ( !empty( $wpss_error_code ) ) {
		// spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
		$wpss_error_code = ltrim($wpss_error_code);
		// Timer End - Content Filter
		$wpss_end_time_content_filter 				= spamshield_microtime();
		$wpss_total_time_content_filter 			= spamshield_timer( $commentdata['start_time_content_filter'], $wpss_end_time_content_filter, false, 6 );
		$commentdata['total_time_content_filter']	= $wpss_total_time_content_filter;
		if ( !empty( $spamshield_options['comment_logging'] ) ) {
			spamshield_log_data( $commentdata, $wpss_error_code );
			}
		}
	// After spamshield_exit_content_filter() implemented, can remove previous code - END
	
	$commentdata['wpss_error_code'] = $wpss_error_code;
	$commentdata['content_filter_status'] = $content_filter_status;
	return $commentdata;
	}

function spamshield_trackback_ip_filter( $commentdata, $spamshield_options ) {
	// Trackback IP Filter
	// This will knock out 99.99% of Trackback Spam
	// Keeping this separate and before content filter because it's fast
	// If passes this, then content filter will take out the rest
	
	// Timer Start  - Content Filter
	if ( empty( $commentdata['start_time_content_filter'] ) ) {
		$wpss_start_time_content_filter = spamshield_microtime();
		$commentdata['start_time_content_filter'] = $wpss_start_time_content_filter;
		}

	$content_filter_status 				= $wpss_error_code = ''; // Must go before tests


	$commentdata_remote_addr			= $_SERVER['REMOTE_ADDR'];
	$commentdata_remote_addr_lc			= strtolower($commentdata_remote_addr);
	$commentdata_comment_type			= $commentdata['comment_type'];
	$commentdata_comment_author_url		= $commentdata['comment_author_url'];
	$commentdata_comment_author_url_lc	= strtolower($commentdata_comment_author_url);
	
	// Check to see if IP Trackback client IP matches IP of Server where link is supposedly coming from
	if ( $commentdata_comment_type == 'trackback' ) {
		$trackback_domain = spamshield_get_domain($commentdata_comment_author_url_lc);
		$trackback_ip = gethostbyname($trackback_domain);
		if ( $commentdata_remote_addr_lc != $trackback_ip ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' T1000-FIP-1';
			}
		}

	if ( !empty( $wpss_error_code ) ) {
		// spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
		$wpss_error_code = ltrim($wpss_error_code);
		// Timer End - Content Filter
		$wpss_end_time_content_filter 				= spamshield_microtime();
		$wpss_total_time_content_filter 			= spamshield_timer( $commentdata['start_time_content_filter'], $wpss_end_time_content_filter, false, 6 );
		$commentdata['total_time_content_filter']	= $wpss_total_time_content_filter;
		if ( !empty( $spamshield_options['comment_logging'] ) ) {
			spamshield_log_data( $commentdata, $wpss_error_code );
			}
		}
	
	$commentdata['wpss_error_code'] = $wpss_error_code;
	$commentdata['content_filter_status'] = $content_filter_status;
	return $commentdata;
	}

function spamshield_content_filter( $commentdata, $spamshield_options ) {
	// Content Filter aka The Algorithmic Layer
	// Blocking the Obvious to Improve Human/Pingback/Trackback Defense
	
	// Timer Start  - Content Filter
	if ( empty( $commentdata['start_time_content_filter'] ) ) {
		$wpss_start_time_content_filter = spamshield_microtime();
		$commentdata['start_time_content_filter'] = $wpss_start_time_content_filter;
		}
	
	$content_filter_status = $wpss_error_code = ''; // Must go before tests

	spamshield_update_session_data($spamshield_options);

	// TEST 0 - See if user has already been blacklisted this session
	$wpss_lang_ck_key = 'UBR_LANG';
	$wpss_lang_ck_val = 'default';
	if ( !is_user_logged_in() && ( !empty( $_SESSION['wpss_blacklisted_user_'.RSMP_HASH] ) || ( !empty( $_COOKIE[$wpss_lang_ck_key] ) && $_COOKIE[$wpss_lang_ck_key] == $wpss_lang_ck_val ) ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '2'; }
		$wpss_error_code .= ' 0-BL';
		$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true;
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	
	$wpss_key_values 	= spamshield_get_key_values();
	$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
	$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
	$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
	$wpss_js_val 		= $wpss_key_values['wpss_js_val'];

	if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wpss_jsck_cookie_val = $_COOKIE[$wpss_ck_key]; }
	else { $wpss_jsck_cookie_val = ''; }
	if ( !empty( $_POST[$wpss_js_key] ) ) {
		$wpss_jsck_field_val 	= $_POST[$wpss_js_key]; //Comments Post Verification
		}
	else { $wpss_jsck_field_val = ''; }
	if ( !empty( $_POST['JSONST'] ) ) { $post_jsonst = $_POST['JSONST']; } else { $post_jsonst = ''; }
	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }
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
	$commentdata_comment_content_extracted_urls 	= spamshield_parse_links( $commentdata_comment_content_lc_deslashed, 'url' ); // Parse comment content for all URLs
	$commentdata_comment_content_extracted_urls_at 	= spamshield_parse_links( $commentdata_comment_content_lc_deslashed, 'url_at' ); // Parse comment content for Anchor Text Link URLs
	
	$replace_apostrophes							= array('’','`','&acute;','&grave;','&#39;','&#96;','&#101;','&#145;','&#146;','&#158;','&#180;','&#207;','&#208;','&#8216;','&#8217;');
	$commentdata_comment_content_lc_norm_apost 		= str_replace($replace_apostrophes,"'",$commentdata_comment_content_lc_deslashed);
	
	$commentdata_comment_type						= $commentdata['comment_type'];
	
	/*
	if ( $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		$commentdata_comment_type = 'comment';
		}
	*/
	
	if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) { $commentdata_user_agent = trim($_SERVER['HTTP_USER_AGENT']); } else { $commentdata_user_agent = ''; }
	$commentdata_user_agent_lc			= strtolower($commentdata_user_agent);
	
	if ( !empty( $_SERVER['HTTP_ACCEPT'] ) ) { $user_http_accept = strtolower(trim($_SERVER['HTTP_ACCEPT'])); } else { $user_http_accept = ''; }
	if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) { $user_http_accept_language = strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])); } else { $user_http_accept_language = ''; }

	$commentdata_remote_addr			= $_SERVER['REMOTE_ADDR'];
	$commentdata_remote_addr_regex 		= spamshield_preg_quote($commentdata_remote_addr);
	$commentdata_remote_addr_lc			= strtolower($commentdata_remote_addr);
	$commentdata_remote_addr_lc_regex 	= spamshield_preg_quote($commentdata_remote_addr_lc);

	$commentdata_referrer				= $_SERVER['HTTP_REFERER'];
	$commentdata_referrer_lc			= strtolower($commentdata_referrer);
	$commentdata_blog					= RSMP_SITE_URL;
	$commentdata_blog_lc				= strtolower($commentdata_blog);
	$commentdata_php_self				= $_SERVER['PHP_SELF'];
	$commentdata_php_self_lc			= strtolower($commentdata_php_self);
	$wp_comments_post_url 				= $commentdata_blog_lc.'/wp-comments-post.php';
	
	$blog_server_ip 					= RSMP_SERVER_ADDR;
	$blog_server_name 					= RSMP_SERVER_NAME;
	
	// IP / PROXY INFO - BEGIN
	global $wpss_ip_proxy_info;
	if ( empty( $wpss_ip_proxy_info ) ) {
		$wpss_ip_proxy_info 		= spamshield_ip_proxy_info();
		}
	$ip_proxy_info					= $wpss_ip_proxy_info;
	$ip 							= $ip_proxy_info['ip'];
	$reverse_dns 					= $ip_proxy_info['reverse_dns'];
	$reverse_dns_lc 				= $ip_proxy_info['reverse_dns_lc'];
	$reverse_dns_lc_regex 			= $ip_proxy_info['reverse_dns_lc_regex'];
	$reverse_dns_lc_rev 			= $ip_proxy_info['reverse_dns_lc_rev'];
	$reverse_dns_lc_rev_regex		= $ip_proxy_info['reverse_dns_lc_rev_regex'];
	$reverse_dns_ip 				= $ip_proxy_info['reverse_dns_ip'];
	$reverse_dns_ip_regex 			= $ip_proxy_info['reverse_dns_ip_regex'];
	$reverse_dns_authenticity 		= $ip_proxy_info['reverse_dns_authenticity'];
	$ip_proxy_via 					= $ip_proxy_info['ip_proxy_via'];
	$ip_proxy_via_lc 				= $ip_proxy_info['ip_proxy_via_lc'];
	$masked_ip 						= $ip_proxy_info['masked_ip'];
	$ip_proxy 						= $ip_proxy_info['ip_proxy'];
	$ip_proxy_short 				= $ip_proxy_info['ip_proxy_short'];
	$ip_proxy_data 					= $ip_proxy_info['ip_proxy_data'];
	$proxy_status 					= $ip_proxy_info['proxy_status'];
	$ip_proxy_chrome_compression	= $ip_proxy_info['ip_proxy_chrome_compression'];
	// IP / PROXY INFO - END
	
	// Post Type Filter
	/*
	// Removed V 1.1.7 - Found Exception
	if ( $commentdata_comment_post_type != 'post' ) {
		// Prevents Trackback, Pingback, and Automated Spam on 'Page' Post Type
		// Invalid types: 'page', 'attachment', 'revision', 'nav_menu_item'
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' INVALTY';
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
	
	// Execute Simple Filter Test(s)
	if ( $filter_1_count >= $filter_1_limit ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 1-HT';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// Authors Only - Non-Trackback
	//Removed Filters 300-423 and replaced with Regex

	// Author Blacklist Check - Invalid Author Names - Stopping Human Spam
	if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' && spamshield_anchortxt_blacklist_chk( $commentdata_comment_author_lc_deslashed, '', 'author', $commentdata_comment_author_url_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10500A-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// Regular Expression Tests - 2nd Gen - Comment Author/Author URL - BEGIN

	// 10500-13000 - Complex Test for terms in Comment Author/URL - $commentdata_comment_author_lc_deslashed/$commentdata_comment_author_url_domain_lc

	// Blacklisted Domains Check
	if ( spamshield_domain_blacklist_chk( $commentdata_comment_author_url_domain_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10500AU-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// Check for URL Shorteners, Bogus Long URLs, and Misc Spam Domains
	if ( spamshield_at_link_spam_url_chk( $commentdata_comment_author_url_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10510AU-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
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
					$wpss_error_code .= ' 10511AUA';
					//break;
					return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
					}
				}
			$i++;
			}
		}
	// Potential Exploits
	// Includes protection for Trackbacks and Pingbacks

	// Check Author URL for Exploits
	if ( spamshield_exploit_url_chk( $commentdata_comment_author_url_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 15000AU-XPL'; // Added in 1.4 - Replacing 15001AU-XPL and 15002AU-XPL, and adds additional protection
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	
	// Regular Expression Tests - 2nd Gen - Comment Author/Author URL - END
	
	$blacklist_word_combo_limit = 7; 
	$blacklist_word_combo = 0;

	$i = 0;
	
	// Regular Expression Tests - 2nd Gen - Comment Content - BEGIN
	
	// Miscellaneous Patterns that Keep Repeating
	if ( preg_match( "~^([0-9]{6})\s([0-9]{6})(.*)\s([0-9]{6})$~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10401C';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	// Blacklisted Anchor Text Check - Links in Content - Stopping Human Spam
	if ( spamshield_anchortxt_blacklist_chk( $commentdata_comment_content_lc_deslashed, '', 'content' ) && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10500CAT-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	// Blacklisted Domains Check - Links in Content
	if ( spamshield_link_blacklist_chk( $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10500CU-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	// Check Anchor Text Links for URL Shorteners, Bogus Long URLs, and Misc Spam Domains
	if ( spamshield_at_link_spam_url_chk( $commentdata_comment_content_extracted_urls_at ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10510CU-BL'; // Replacing 10510CU-MSC
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	
	// Check all URL's in Comment Content for Exploits
	if ( spamshield_exploit_url_chk( $commentdata_comment_content_extracted_urls ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 15000CU-XPL'; // Added in 1.4
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
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
			if ( $RepeatedTermsInContentCount >= 5 && $RepeatedTermsInContentStrLength >= 4 && $RepeatedTermsInContentDensity > 40 ) {		
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$wpss_error_code .= ' 9000-'.$i;
				return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
				}
			}
		$i++;
		}

	// Comment Author and Comment Author URL appearing in Content - REGEX VERSION
	if ( preg_match( "~(<\s*a\s+([a-z0-9\-_\.\?\='\"\:\(\)\{\}\s]*)\s*href|\[(url|link))\s*\=\s*(['\"])?\s*$commentdata_comment_author_url_lc_regex([a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]*)(['\"])?(>|\])$commentdata_comment_author_lc_deslashed_regex(<|\[)\s*\/\s*a\s*(>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 9100-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( $commentdata_comment_author_url_lc == $commentdata_comment_author_lc_deslashed && !preg_match( "~https?\:/+~i", $commentdata_comment_author_url_lc ) && preg_match( "~(<\s*a\s+([a-z0-9\-_\.\?\='\"\:\(\)\{\}\s]*)\s*href|\[(url|link))\s*\=\s*(['\"])?\s*(https?\:/+[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)\s*(['\"])?\s*(>|\])$commentdata_comment_author_lc_deslashed_regex(<|\[)\s*\/\s*a\s*(>|(url|link)\])~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 9101';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( preg_match( "~^((ww[w0-9]|m)\.)?$commentdata_comment_author_lc_deslashed_regex$~i", $commentdata_comment_author_url_domain_lc) && !preg_match( "~https?\:/+~i", $commentdata_comment_author_lc_deslashed ) ) {
		// Changed to include Trackbacks and Pingbacks in 1.1.4.4
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 9102';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( $commentdata_comment_author_url_lc == $commentdata_comment_author_lc_deslashed && !preg_match( "~https?\:/+~i", $commentdata_comment_author_url_lc ) && preg_match( "~(https?\:/+[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 9103';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// Email Filters
	// New Test with Blacklists
	if ( spamshield_email_blacklist_chk($commentdata_comment_author_email_lc) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 9200E-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// TEST REFERRERS 1 - TO THE COMMENT PROCESSOR
	if ( strpos( $wp_comments_post_url, $commentdata_php_self_lc ) !== false && $commentdata_referrer_lc == $wp_comments_post_url ) {
		// Often spammers send the referrer as the URL for the wp-comments-post.php page. Nimrods.
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' REF-1-1011';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	// TEST REFERRERS 2 - SPAMMERS SEARCHING FOR PAGES TO COMMENT ON
	if ( !empty( $post_ref2xjs ) ) {
		$ref2xJS = addslashes( urldecode( $post_ref2xjs ) );
		$ref2xJS = str_replace( '%3A', ':', $ref2xJS );
		$ref2xJS = str_replace( ' ', '+', $ref2xJS );
		$ref2xJS = esc_url_raw( $ref2xJS );
		$ref2xJS_lc = strtolower( $ref2xJS );
		if ( preg_match( "~\.google\.co(m|\.[a-z]{2})~i", $ref2xJS ) && strpos( $ref2xJS_lc, 'leave a comment' ) !== false ) {
			// make test more robust for other versions of google & search query
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' REF-2-1021';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		// add Keyword Script Here
		}
	
	// TEST REFERRERS 3 - TO THE PAGE BEING COMMENTED ON
	/* DISABLED IN V1.5.9
	$test_fail = false;
	if ( !empty( $commentdata_referrer_lc ) && $commentdata_referrer_lc != $commentdata_comment_post_url_lc && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		// If Comment Processor Referrer exists, make sure it matches page being commented on
		// Test if JetPack Active - Added Compatibility Fix in 1.3.5
		$wpss_jp_active	= spamshield_is_plugin_active( 'jetpack/jetpack.php' );
		// Start Comment Processor Referrer Tests
		if ( !empty( $wpss_jp_active ) && $commentdata_referrer_lc == 'http://jetpack.wordpress.com/jetpack-comment/' ) {
			$test_fail = false;
			}
		else {
			$wpss_permalink_structure = get_option('permalink_structure');
			if ( !empty( $wpss_permalink_structure ) ) { // Using Permalinks
				if ( strpos( $commentdata_referrer_lc, '?' ) !== false ) { // URL has query string
					$referrer_no_query = spamshield_remove_query( $commentdata_referrer_lc );
					}
				else { // URL does not have query string
					$referrer_no_query = $commentdata_referrer_lc;
					}
				$wpss_page_comments = get_option('page_comments');
				if ( !empty( $wpss_page_comments ) ) { // Breaking Comments Into Pages
					$referrer_no_query = preg_replace( "~comment\-page\-[0-9]+/$~i", "", $referrer_no_query );
					}
				//if ( $referrer_no_query != $commentdata_comment_post_url_lc ) { $test_fail = true; }
				if ( $referrer_no_query != $commentdata_comment_post_url_lc ) { 
					$test_fail = true; 
					}
				}
			elseif ( strpos( $commentdata_referrer_lc, '?' ) !== false ) { // Not using Permalinks & URL has query string
				$referrer_wp_query = spamshield_remove_query( $commentdata_referrer_lc, true );
				$post_url_wp_query = spamshield_remove_query( $commentdata_comment_post_url_lc, true );
				if ( $referrer_wp_query != $post_url_wp_query ) { $test_fail = true; }
				}
			else { // Not using Permalinks & URL does not have query string
				$test_fail = true;
				}
			}
		if ( !empty( $test_fail ) ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' REF-3-1031';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	*/
		
	// Spam Network - BEGIN

	// Test User-Agents
	if ( empty( $commentdata_user_agent_lc ) ) {
		// There is no reason for a blank UA String, unless it's been altered.
		$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
		$wpss_error_code .= ' UA1001';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	$commentdata_user_agent_lc_word_count = spamshield_count_words($commentdata_user_agent_lc);
	if ( !empty( $commentdata_user_agent_lc ) && $commentdata_user_agent_lc_word_count < 3 ) {
		if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' || ( strpos( $commentdata_user_agent_lc, 'movabletype' ) === false && $commentdata_comment_type == 'trackback' ) ) {
		//if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' || ( strpos( $commentdata_user_agent_lc, 'movabletype' ) === false && ( $commentdata_comment_type == 'trackback' || $commentdata_comment_type == 'pingback' ) ) ) {
			// Another test for altered UA's.
			$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
			$wpss_error_code .= ' UA1003';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	if ( strpos( $commentdata_user_agent_lc, 'libwww' ) !== false 
		|| strpos( $commentdata_user_agent_lc, 'nutch' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'larbin' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'jakarta' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'java' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'mechanize' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'phpcrawl' ) === 0
		|| strpos( $commentdata_user_agent_lc, 'iopus-' ) !== false ) {
		// There is no reason for a human to use one of these UA strings. Commonly used to attack/spam WP.
		$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
		$wpss_error_code .= ' UA1004';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	
	if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
	
		//Test HTTP_ACCEPT
		if ( empty( $user_http_accept ) ) {
			$content_filter_status = '1';
			$wpss_error_code .= ' HA1001';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		if ( $user_http_accept == 'application/json, text/javascript, */*; q=0.01' ) {
			$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
			$wpss_error_code .= ' HA1002';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		if ( $user_http_accept == '*' ) {
			$content_filter_status = '1';
			$wpss_error_code .= ' HA1003';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
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
					$wpss_error_code .= ' HA1004';
					//break;
					return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
					}
				}
			$i++;
			}
	
		//Test HTTP_ACCEPT_LANGUAGE
		if ( empty( $user_http_accept_language ) ) {
			$content_filter_status = '1'; // Was 2, changed to 1 - V1.0.0.0
			$wpss_error_code .= ' HAL1001';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		if ( $user_http_accept_language == '*' ) {
			$content_filter_status = '1';
			$wpss_error_code .= ' HAL1002';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
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
					$wpss_error_code .= ' HAL1004';
					//break;
					return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
					}
				}
			$i++;
			}
		// HAL1005
		// After testing port to CF and R
		/*
		//if ( preg_match( "~^[a-z]{2}(-[A-Z]{2})?$~", $user_http_accept_language ) ) { MSIE FAILS
		if ( $user_http_accept_language == 'en' ) {
			if ( ( strpos( $commentdata_user_agent_lc, 'Opera' ) === 0 && strpos( $commentdata_user_agent_lc, 'Linux' ) === false ) || strpos( $commentdata_user_agent_lc, 'Opera' ) !== 0 ) {
				// Opera on Linux is only UA that exhibits this behavior. All others are bots.
				$content_filter_status = '1';
				$wpss_error_code .= ' HAL1005';
				}
			}
		*/

		//Test PROXY STATUS if option
		//Google Chrome Compression Proxy Bypass
		if ( $ip_proxy == 'PROXY DETECTED' && $ip_proxy_chrome_compression != 'TRUE' && empty( $spamshield_options['allow_proxy_users'] ) ) {
			$content_filter_status = '10';
			$wpss_error_code .= ' PROXY1001';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
	
		}

	// Test IPs - was here

	if ( strpos( $commentdata_remote_addr_lc, '192.168.' ) === 0 && strpos( $blog_server_ip, '192.168.' ) !== 0 && strpos( $blog_server_name, 'localhost' ) === false ) {
		$content_filter_status = '2';
		$wpss_error_code .= ' IP1003';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	// IP1004 - replace in_array test of $spamshield_ip_bans
		
	// Reverse DNS Server Tests - BEGIN
	if ( $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		// Test Reverse DNS Hosts - Do all with Reverse DNS not Remote Host
		$rev_dns_filter_data = spamshield_revdns_filter( 'comment', $content_filter_status, $ip, $reverse_dns_lc, $commentdata_comment_author_lc_deslashed, $commentdata_comment_author_email_lc );
		$revdns_blacklisted = $rev_dns_filter_data['blacklisted'];
		if ( !empty( $revdns_blacklisted ) ) {
			$content_filter_status = $rev_dns_filter_data['status'];
			$wpss_error_code .= $rev_dns_filter_data['error_code'];
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	// Reverse DNS Server Tests - END

	// Spam Network - END


	// Test Pingbacks and Trackbacks - OLD LOCATION

	// Miscellaneous
	if ( preg_match( "~\[\.+\]\s+\[\.+\]~", $commentdata_comment_content ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 5000-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( $commentdata_comment_content == '<new comment>' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 5001';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( strpos( $commentdata_comment_content_lc_deslashed, 'blastogranitic atremata antiviral unteacherlike choruser coccygalgia corynebacterium reason' ) !== false ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 5002';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	// "Hey this is off topic but.." Then why are you commenting? Common phrase in spam
	if ( preg_match( "~^([a-z0-9\s\.,!]{0,12})?((he.a?|h([ily]{1,2}))(\s+there)?|howdy|hello|bonjour|good\s+day)([\.,!])?\s+(([ily]{1,2})\s+know\s+)?th([ily]{1,2})s\s+([ily]{1,2})s\s+([a-z\s]{3,12}|somewhat|k([ily]{1,2})nd\s*of)?(of{1,2}\s+)?of{1,2}\s+top([ily]{1,2})c\s+(but|however)\s+([ily]{1,2})\s+(was\s+wonder([ily]{1,2})nn?g?|need\s+some\s+adv([ily]{1,2})ce)~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 5003';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( preg_match( "~^th([ily]{1,2})s\s+([ily]{1,2})s\s+k([ily]{1,2})nd\s+of\s+off\s+top([ily]{1,2})c\s+but~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 5004';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	// BOILERPLATE: Add common boilerplate/template spam phrases... Add Blacklist functions

	// WP Blacklist Check - BEGIN
	
	// Test WP Blacklist if option set
	if ( !empty( $spamshield_options['enhanced_comment_blacklist'] ) && empty( $content_filter_status ) ) {
		if ( spamshield_blacklist_check( $commentdata_comment_author_lc_deslashed, $commentdata_comment_author_email_lc, $commentdata_comment_author_url_lc, $commentdata_comment_content_lc_deslashed, $ip, $commentdata_user_agent_lc, '' ) ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '100'; }
			$wpss_error_code .= ' WP-BLACKLIST';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	// WP Blacklist Check - END
	
	// Timer End - Content Filter
	$wpss_end_time_content_filter 				= spamshield_microtime();
	$wpss_total_time_content_filter 			= spamshield_timer( $commentdata['start_time_content_filter'], $wpss_end_time_content_filter, false, 6 );
	$commentdata['total_time_content_filter']	= $wpss_total_time_content_filter;
	
	if ( empty( $wpss_error_code ) ) {
		$wpss_error_code = 'No Error';
		if ( !empty( $spamshield_options['comment_logging'] ) && !empty( $spamshield_options['comment_logging_all'] ) ) {
			spamshield_log_data( $commentdata, $wpss_error_code );
			}
		}
	else {
		// spamshield_update_sess_accept_status($commentdata,'r','Line: '.__LINE__);
		if ( $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' && $wpss_jsck_cookie_val != $wpss_ck_val ) {
			$wpss_error_code .= ' COOKIE-2';
			}
		$wpss_error_code = ltrim($wpss_error_code);
		if ( !empty( $spamshield_options['comment_logging'] ) ) {
			spamshield_log_data( $commentdata, $wpss_error_code );
			}
		}

	//$spamshield_error_data = array( $wpss_error_code, $blacklist_word_combo, $blacklist_word_combo_total );
	
	$commentdata['wpss_error_code'] = $wpss_error_code;
	$commentdata['content_filter_status'] = $content_filter_status;
	
	return $commentdata;
	
	// CONTENT FILTERING - END
	}

function spamshield_ip_proxy_info() {
	// IP / PROXY INFO - BEGIN
	$ip = $_SERVER['REMOTE_ADDR'];
	if ( !empty( $_SERVER['HTTP_VIA'] ) ) { $ip_proxy_via=trim($_SERVER['HTTP_VIA']); } else { $ip_proxy_via = ''; }
	$ip_proxy_via_lc = strtolower($ip_proxy_via);
	$masked_ip = '';
	if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$masked_ip = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
		if ( $masked_ip == $ip ) { $masked_ip = ''; }
		}

	$reverse_dns 				= gethostbyaddr($ip);
	if ( $reverse_dns == '.' ) { $reverse_dns_ip = '[No Data]'; } elseif ( $reverse_dns == $ip ) { $reverse_dns_ip = $ip; } else { $reverse_dns_ip = gethostbyname($reverse_dns); }
	$reverse_dns_ip_regex 		= spamshield_preg_quote($reverse_dns_ip);
	$reverse_dns_lc 			= strtolower($reverse_dns);
	$reverse_dns_lc_regex 		= spamshield_preg_quote($reverse_dns_lc);
	$reverse_dns_lc_rev 		= strrev($reverse_dns_lc);
	$reverse_dns_lc_rev_regex 	= spamshield_preg_quote($reverse_dns_lc_rev);
	if ( $reverse_dns_ip != $ip || $ip == $reverse_dns ) { $reverse_dns_authenticity = '[Possibly Forged]'; } else { $reverse_dns_authenticity = '[Verified]'; }
	// Detect Use of Proxy
	$ip_proxy_chrome_compression='FALSE';
	if ( !empty( $ip_proxy_via ) || !empty( $masked_ip ) ) {
		if ( empty( $masked_ip ) ) { $masked_ip='[No Data]'; }
		$ip_proxy='PROXY DETECTED';
		$ip_proxy_short='PROXY';
		$ip_proxy_data=$ip.' | MASKED IP: '.$masked_ip;
		$proxy_status='TRUE';
		//Google Chrome Compression Check
		if ( strpos( $ip_proxy_via_lc, 'chrome compression proxy' ) !== false && preg_match( "~^google-proxy-(.*)\.google\.com$~i", $reverse_dns ) ) { $ip_proxy_chrome_compression='TRUE'; }
		}
	else {
		$ip_proxy='No Proxy';
		$ip_proxy_short=$ip_proxy;
		$ip_proxy_data=$ip;
		$proxy_status='FALSE';
		}
	// IP / PROXY INFO - END
	$ip_proxy_info = array(
		'ip' 							=> $ip,
		'reverse_dns' 					=> $reverse_dns,
		'reverse_dns_lc' 				=> $reverse_dns_lc,
		'reverse_dns_lc_regex' 			=> $reverse_dns_lc_regex,
		'reverse_dns_lc_rev' 			=> $reverse_dns_lc_rev,
		'reverse_dns_lc_rev_regex'		=> $reverse_dns_lc_rev_regex,
		'reverse_dns_ip' 				=> $reverse_dns_ip,
		'reverse_dns_ip_regex' 			=> $reverse_dns_ip_regex,
		'reverse_dns_authenticity' 		=> $reverse_dns_authenticity,
		'ip_proxy_via' 					=> $ip_proxy_via,
		'ip_proxy_via_lc' 				=> $ip_proxy_via_lc,
		'masked_ip' 					=> $masked_ip,
		'ip_proxy' 						=> $ip_proxy,
		'ip_proxy_short' 				=> $ip_proxy_short,
		'ip_proxy_data' 				=> $ip_proxy_data,
		'proxy_status' 					=> $proxy_status,
		'ip_proxy_chrome_compression'	=> $ip_proxy_chrome_compression,
		);
	return $ip_proxy_info;
	}

// COMMENT SPAM FILTERS - END

function spamshield_comment_moderation_addendum( $text, $comment_id ) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$blacklist_text = __( 'Blacklist the IP Address:', WPSS_PLUGIN_NAME );
	$blacklist_url = RSMP_ADMIN_URL.'/options-general.php?page='.WPSS_PLUGIN_NAME.'&wpss_action=blacklist_ip&submitter_ip='.$ip;
	$text .= $blacklist_text.' '.$blacklist_url."\r\n";
	
	$text = spamshield_extra_notification_data( $text );
	return $text;
	}

function spamshield_comment_notification_addendum( $text, $comment_id ) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$blacklist_text = __( 'Blacklist the IP Address:', WPSS_PLUGIN_NAME );
	$blacklist_url = RSMP_ADMIN_URL.'/options-general.php?page='.WPSS_PLUGIN_NAME.'&wpss_action=blacklist_ip&submitter_ip='.$ip;
	$text .= $blacklist_text.' '.$blacklist_url."\r\n";
	// For now these two functions are the same, but at some point we may want just the next line:
	$text = spamshield_extra_notification_data( $text );
	return $text;
	}

function spamshield_extra_notification_data( $text ) {
	//$text .= "\r\n";
	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);
	if ( !empty( $_POST['JSONST'] ) ) { $post_jsonst = $_POST['JSONST']; } else { $post_jsonst = ''; }
	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }	
	$post_ref2xjs_lc = strtolower($post_ref2xjs);

	// IP / PROXY INFO - BEGIN
	global $wpss_ip_proxy_info;
	if ( empty( $wpss_ip_proxy_info ) ) {
		$wpss_ip_proxy_info 		= spamshield_ip_proxy_info();
		}
	$ip_proxy_info					= $wpss_ip_proxy_info;
	$ip 							= $ip_proxy_info['ip'];
	$reverse_dns 					= $ip_proxy_info['reverse_dns'];
	$reverse_dns_lc 				= $ip_proxy_info['reverse_dns_lc'];
	$reverse_dns_lc_regex 			= $ip_proxy_info['reverse_dns_lc_regex'];
	$reverse_dns_lc_rev 			= $ip_proxy_info['reverse_dns_lc_rev'];
	$reverse_dns_lc_rev_regex		= $ip_proxy_info['reverse_dns_lc_rev_regex'];
	$reverse_dns_ip 				= $ip_proxy_info['reverse_dns_ip'];
	$reverse_dns_ip_regex 			= $ip_proxy_info['reverse_dns_ip_regex'];
	$reverse_dns_authenticity 		= $ip_proxy_info['reverse_dns_authenticity'];
	$ip_proxy_via 					= $ip_proxy_info['ip_proxy_via'];
	$ip_proxy_via_lc 				= $ip_proxy_info['ip_proxy_via_lc'];
	$masked_ip 						= $ip_proxy_info['masked_ip'];
	$ip_proxy 						= $ip_proxy_info['ip_proxy'];
	$ip_proxy_short 				= $ip_proxy_info['ip_proxy_short'];
	$ip_proxy_data 					= $ip_proxy_info['ip_proxy_data'];
	$proxy_status 					= $ip_proxy_info['proxy_status'];
	$ip_proxy_chrome_compression	= $ip_proxy_info['ip_proxy_chrome_compression'];
	// IP / PROXY INFO - END

	if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
		$wpss_http_accept_language 	= sanitize_text_field($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		} else { $wpss_http_accept_language = ''; }
	if ( !empty( $_SERVER['HTTP_ACCEPT'] ) ) {
		$wpss_http_accept 			= sanitize_text_field($_SERVER['HTTP_ACCEPT']);
		} else { $wpss_http_accept = ''; }
	$wpss_http_user_agent 		= spamshield_get_user_agent();
	if ( !empty( $_SERVER['HTTP_REFERER'] ) ) {
		$wpss_http_referer 			= esc_url_raw($_SERVER['HTTP_REFERER']);
		} else { $wpss_http_referer = ''; }
	if ( empty( $spamshield_options['hide_extra_data'] ) ) {
		$text .= "\r\n";
		$text .= '-----------------------------------------------------------------'."\r\n";
		$text .= __( 'Additional Technical Data Added by WP-SpamShield', WPSS_PLUGIN_NAME ) . "\r\n";
		$text .= '-----------------------------------------------------------------'."\r\n";
		// DEBUG ONLY - BEGIN
		if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) {
			if ( !empty( $post_ref2xjs ) ) {
				$ref2xJS = addslashes( urldecode( $post_ref2xjs ) );
				$ref2xJS = str_replace( '%3A', ':', $ref2xJS );
				$ref2xJS = str_replace( ' ', '+', $ref2xJS );
				$ref2xJS = esc_url_raw( $ref2xJS );
				$text .= "\r\nJS Page Referrer Check: $ref2xJS\r\n";
				}
			if ( !empty( $post_jsonst ) ) {
				$JSONST = sanitize_text_field( $post_jsonst );
				$text .= "\r\nJSONST: $JSONST\r\n";
				}
			}
		// DEBUG ONLY - END
		else {
			if ( !empty( $post_ref2xjs ) ) {
				$ref2xJS = addslashes( urldecode( $post_ref2xjs ) );
				$ref2xJS = str_replace( '%3A', ':', $ref2xJS );
				$ref2xJS = str_replace( ' ', '+', $ref2xJS );
				$ref2xJS = esc_url_raw( $ref2xJS );
				$text .= "\r\n" . __( 'Page Referrer Check.', WPSS_PLUGIN_NAME ) . ': '.$ref2xJS."\r\n";
				}
			}
		$text .= "\r\n" . __( 'Referrer', WPSS_PLUGIN_NAME ) . ': '.$wpss_http_referer;
		$text .= "\r\n";
		$text .= "\r\n" . __( 'User-Agent', WPSS_PLUGIN_NAME ) . ': '.$wpss_http_user_agent;
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
		$text .= "\r\nHTTP_ACCEPT_LANGUAGE     : ".$wpss_http_accept_language;
		$text .= "\r\n";
		$text .= "\r\nHTTP_ACCEPT: ".$wpss_http_accept;
		$text .= "\r\n";
		$text .= "\r\n" . __( 'IP Address Lookup', WPSS_PLUGIN_NAME ) . ': http://whatismyipaddress.com/ip/'.$ip;
		$text .= "\r\n";
		$text .= "\r\n".'(' . __( 'This data is helpful if you need to submit a spam sample.', WPSS_PLUGIN_NAME ) . ')';
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
	update_option( 'blacklist_keys', $blacklist_keys );
	}

/**
 * Does comment or contact form contain blacklisted characters, words, IP addresses, or email addresses.
 *
 * @since 1.5.4
 *
 * @param string $author The author name of the submitter
 * @param string $email The email of the submitter
 * @param string $url The url used in the submission
 * @param string $content The submitted content
 * @param string $user_ip The submitter IP address
 * @param string $user_agent The submitter's browser / user agent
 * @param string $user_server The submitter's server (reverse DNS of IP)
 * @return bool True if submission contains blacklisted content, false if submission does not
 */
function spamshield_blacklist_check( $author, $email, $url, $content, $user_ip, $user_agent, $user_server ) {
	/**
	 * Fires at end of contact form and comment content filters.
	 * Upgrade from WordPress' built-in and flawed wp_blacklist_check() function.
	 * Removed User-Agent filter from wp_blacklist_check() - Not a good idea to let users play with User-Agent filtering, most people don't realize this will be tested, leading to false-positives.
	 * Also, it's not in the documentation...nowhere in the WP Dashboard does it mention testing against User-Agents.
	 *
	 * @since 1.5.4
	 *
	 * @param string $author     	Comment or Contact Form author name.
	 * @param string $email      	Comment or Contact Form author's email.
	 * @param string $url        	Comment or Contact Form author's URL.
	 * @param string $content    	Comment or Contact Form content.
	 * @param string $user_ip    	Comment or Contact Form author's IP address.
	 * @param string $user_agent 	Comment or Contact Form author's browser / user agent.
	 * @param string $user_server	Comment or Contact Form author's server (reverse DNS of IP).
	 */
	//do_action( 'spamshield_blacklist_check', $author, $email, $url, $content, $user_ip, $user_agent, $user_server );
	
	$blacklist_keys = trim(stripslashes(get_option('blacklist_keys')));
	if ( empty( $blacklist_keys ) ) {
		return false; // If blacklist keys are empty
		}
	$blacklist_keys_arr = explode("\n", $blacklist_keys );

	foreach ( (array) $blacklist_keys_arr as $key ) {
		$key = trim( $key );

		// Skip empty lines
		if ( empty( $key ) ) { continue; }

		// Do some escaping magic so that '~' chars in the spam words don't break things:
		$key_pq = spamshield_preg_quote( $key );
		
		$pattern_regex = "~$key_pq~i";
		
		if ( strpos( $key, '[WPSS-ECBL]' ) === 0 ) {
			$key = str_replace( '[WPSS-ECBL]', '', $key );
			if ( strpos( $key, '[SERVER]' ) === 0 && !empty( $user_server ) ) {
				$key = str_replace( '[SERVER]', '', $key );
				if ( strpos( $key, '.' ) === 0 || strpos( $key, '-' ) === 0 ) {
					$key_pq = spamshield_preg_quote( $key );
					if ( preg_match( "~$key_pq$~i", $user_server ) ) {
						return true;
						}
					}
				elseif ( $key == $user_server ) {
					return true;
					}
				}
			}
		elseif ( is_email( $key ) ) {
			if ( !empty( $email ) && $key == $email ) {
				return true;
				}
			}
		elseif ( spamshield_is_valid_ip( $key, '', true ) ) { // IP C-block
			if ( !empty( $user_ip ) && strpos( $user_ip, $key ) === 0 ) {
				return true;
				}
			}
		elseif ( spamshield_is_valid_ip( $key ) ) { // Complete IP Address
			if ( !empty( $user_ip ) && $key == $user_ip ) {
				return true;
				}
			}
		elseif ( 
			   ( !empty( $author ) 	&& preg_match( $pattern_regex, $author ) )
			|| ( !empty( $url ) 	&& preg_match( $pattern_regex, $url ) )
			|| ( !empty( $content )	&& preg_match( $pattern_regex, $content ) )
			) {
			return true;
			}
		}
	return false;
	}

function spamshield_update_whitelist_keys($whitelist_keys) {
	$whitelist_keys_arr		= explode("\n",$whitelist_keys);
	$whitelist_keys_arr_tmp	= spamshield_sort_unique($whitelist_keys_arr);
	$whitelist_keys			= implode("\n",$whitelist_keys_arr_tmp);
	update_option( 'spamshield_whitelist_keys', $whitelist_keys );
	}

function spamshield_whitelist_check( $email = null ) {
	/**
	 * Fires at beginning of contact form and comment content filters.
	 * Bypasses filters if true.
	 * Still subject to cookies test on contact form to prevent potential abuse by bots.
	 *
	 * @since 1.7.4
	 *
	 * @param string $email      	Comment or Contact Form author's email.
	 */
	
	$whitelist_keys = trim(stripslashes(get_option('spamshield_whitelist_keys')));
	if ( empty( $whitelist_keys ) || empty( $email ) ) {
		return false; // If whitelist keys or $email are empty
		}
	$whitelist_keys_arr = explode("\n", $whitelist_keys );

	foreach ( (array) $whitelist_keys_arr as $key ) {
		$key = trim( $key );

		// Skip empty lines
		if ( empty( $key ) ) { continue; }
		
		if ( is_email( $key ) ) {
			if ( $key == $email ) {
				return true;
				}
			}
		}
	return false;
	}

// Spam Registration Protection - BEGIN

function spamshield_register_form_addendum() {
	
	$spamshield_options = get_option('spamshield_options');	
	spamshield_update_session_data($spamshield_options);

	// Check if registration spam shield is disabled - Added in 1.6.9
	if ( !empty( $spamshield_options['registration_shield_disable'] ) ) { return; }
	
	$wpss_key_values 	= spamshield_get_key_values();
	$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
	$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
	$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
	$wpss_js_val 		= $wpss_key_values['wpss_js_val'];

	$new_fields = array(
		'first_name' 	=> __( 'First Name', WPSS_PLUGIN_NAME ),
		'last_name' 	=> __( 'Last Name', WPSS_PLUGIN_NAME ),
		'display_name' 	=> __( 'Display Name', WPSS_PLUGIN_NAME ),
		);

	foreach( $new_fields as $k => $v ) {
		echo '	<p>
		<label for="'.$k.'">'.$v.'<br />
		<input type="text" name="'.$k.'" id="'.$k.'" class="input" value="" size="25" style="width:272px; height:41px;" /></label>
	</p>
';
		}

	echo "\n\t".'<script type=\'text/javascript\'>'."\n\t".'// <![CDATA['."\n\t".WPSS_REF2XJS.'=escape(document[\'referrer\']);'."\n\t".'hf3N=\''.$wpss_js_key.'\';'."\n\t".'hf3V=\''.$wpss_js_val.'\';'."\n\t".'document.write("<input type=\'hidden\' name=\''.WPSS_REF2XJS.'\' value=\'"+'.WPSS_REF2XJS.'+"\'><input type=\'hidden\' name=\'"+hf3N+"\' value=\'"+hf3V+"\'>");'."\n\t".'// ]]>'."\n\t".'</script>'."\n\t";
	echo '<noscript><input type="hidden" name="JSONST" value="NS3"></noscript>'."\n\t";
	$wpss_js_disabled_msg 	= __( 'Currently you have JavaScript disabled. In order to register, please make sure JavaScript and Cookies are enabled, and reload the page.', WPSS_PLUGIN_NAME );
	$wpss_js_enable_msg 	= __( 'Click here for instructions on how to enable JavaScript in your browser.', WPSS_PLUGIN_NAME );
	echo '<noscript><p><strong>'.$wpss_js_disabled_msg.'</strong> <a href="http://enable-javascript.com/" rel="nofollow external" >'.$wpss_js_enable_msg.'</a><br /><br /></p></noscript>'."\n\t";

	// If need to add anything else to registration area, start here
	}

if ( !function_exists('wp_new_user_notification') ) {
	/**
	 * WPSS Redefined: Copied from pluggable.php in WordPress core and added filters.
	 *
	 * Email login credentials to a newly-registered user.
	 *
	 * A new user registration notification is also sent to admin email.
	 *
	 * @since 2.0.0
	 *
	 * @param int    $user_id        User ID.
	 * @param string $plaintext_pass Optional. The user's plaintext password. Default empty.
	 */
	function wp_new_user_notification($user_id, $plaintext_pass = '') {
		$user = get_userdata( $user_id );

		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$admin_message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
		$admin_message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
		$admin_message .= sprintf(__('E-mail: %s'), $user->user_email) . "\r\n";
		
		$admin_message = apply_filters( 'spamshield_signup_notification_text_admin', $admin_message, $user_id, $user );

		@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $admin_message);

		if ( empty($plaintext_pass) )
			return;

		$user_message  = sprintf(__('Username: %s'), $user->user_login) . "\r\n";
		$user_message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n";
		$user_message .= wp_login_url() . "\r\n";
		
		$user_message = apply_filters( 'spamshield_signup_notification_text_user', $user_message, $user_id, $user );

		wp_mail($user->user_email, sprintf(__('[%s] Your username and password'), $blogname), $user_message);

	}

	// WPSS Added

	function spamshield_modify_signup_notification_admin( $text, $user_id, $user ) {
	
		// Check if registration spam shield is disabled - Added in 1.6.9
		$spamshield_options = get_option('spamshield_options');	
		if ( !empty( $spamshield_options['registration_shield_disable'] ) ) { return $text; }

		$wpss_display_name 		= $user->display_name;
		$wpss_user_firstname 	= $user->user_firstname;
		$wpss_user_lastname 	= $user->user_lastname;
		$wpss_user_email		= $user->user_email;
		$wpss_user_url			= $user->user_url;
		$wpss_user_login 		= $user->user_login;
		$wpss_user_id	 		= $user->ID;
		
		$display_name_txt 		= __( 'Display Name', WPSS_PLUGIN_NAME );
		$first_name_txt 		= __( 'First Name', WPSS_PLUGIN_NAME );
		$last_name_txt 			= __( 'Last Name', WPSS_PLUGIN_NAME );		
		$user_id_txt 			= __( 'User ID', WPSS_PLUGIN_NAME );		
		
		
		$text .= "\r\n";
		$text .= sprintf( $display_name_txt.': %s', $wpss_display_name ) . "\r\n\r\n";
		$text .= sprintf( $first_name_txt.': %s', $wpss_user_firstname ) . "\r\n\r\n";
		$text .= sprintf( $last_name_txt.': %s', $wpss_user_lastname ) . "\r\n\r\n";
		$text .= sprintf( $user_id_txt .': %s', $wpss_user_id ) . "\r\n\r\n\r\n";
		
		$text = spamshield_extra_notification_data( $text );

		return $text;

		}

	function spamshield_modify_signup_notification_user( $text, $user_id, $user ) {
	
		// Check if registration spam shield is disabled - Added in 1.6.9
		$spamshield_options = get_option('spamshield_options');	
		if ( !empty( $spamshield_options['registration_shield_disable'] ) ) { return $text; }

		// Add three new fields
		$wpss_display_name 		= $user->display_name;
		$wpss_user_firstname 	= $user->user_firstname;
		$wpss_user_lastname 	= $user->user_lastname;
		$wpss_user_email		= $user->user_email;
		$wpss_user_url			= $user->user_url;
		$wpss_user_login 		= $user->user_login;
		$wpss_user_id	 		= $user->ID;
		
		$display_name_txt 		= __( 'Display Name', WPSS_PLUGIN_NAME );
		$first_name_txt 		= __( 'First Name', WPSS_PLUGIN_NAME );
		$last_name_txt 			= __( 'Last Name', WPSS_PLUGIN_NAME );
		
		$text .= "\r\n\r\n";
		$text .= sprintf( $display_name_txt.': %s', $wpss_display_name ) . "\r\n";
		$text .= sprintf( $first_name_txt.': %s', $wpss_user_firstname ) . "\r\n";
		$text .= sprintf( $last_name_txt.': %s', $wpss_user_lastname ) . "\r\n\r\n";

		return $text;
		}
	
	}

function spamshield_check_new_user( $errors, $user_login, $user_email ) {
	// Error checking for new user registration
	$spamshield_options 	= get_option('spamshield_options');
	
	// Check if registration spam shield is disabled - Added in 1.6.9
	if ( !empty( $spamshield_options['registration_shield_disable'] ) ) { return $errors; }
	
	$reg_filter_status		= $wpss_error_code= '';
	$reg_jsck_error			= $reg_badrobot_error = false;
	$ns_val					= 'NS3';
	$pref					= 'R-';
	$error_txt = spamshield_error_txt();
	
	$new_fields = array(
		'first_name' 	=> __( 'First Name', WPSS_PLUGIN_NAME ),
		'last_name' 	=> __( 'Last Name', WPSS_PLUGIN_NAME ),
		'display_name' 	=> __( 'Display Name', WPSS_PLUGIN_NAME ),
		);
	$user_data = array();
	foreach( $new_fields as $k => $v ) {
		if ( isset( $_POST[$k] ) ) { $user_data[$k] = trim( wp_unslash( $_POST[$k] ) ); } else { $user_data[$k] = ''; }
		}

	// Check New Fields for Blanks
	foreach( $new_fields as $k => $v ) {
		$k_uc = strtoupper($k);
		if ( empty( $_POST[$k]) ) {
			$errors->add( 'empty_'.$k, '<strong>'.$error_txt.':</strong> ' . sprintf( __( 'Please enter your %s', WPSS_PLUGIN_NAME ) . '.', $v ) );
			$wpss_error_code .= ' R-BLANK-'.$k_uc;
			}
		}
	
	// BAD ROBOT TEST - BEGIN
	
	$bad_robot_filter_data 	 = spamshield_bad_robot_blacklist_chk( 'register', $reg_filter_status, '', '', $user_data['display_name'], $user_email );
	$reg_filter_status		 = $bad_robot_filter_data['status'];
	$revdns_blacklisted 	 = $bad_robot_filter_data['blacklisted'];
	
	if ( !empty( $revdns_blacklisted ) ) {
		$wpss_error_code 	.= $bad_robot_filter_data['error_code'];
		$reg_badrobot_error = true;
		}

	// BAD ROBOT TEST - END

	// BAD ROBOTS
	if ( $reg_badrobot_error != false ) {
		$errors->add( 'badrobot_error', '<strong>'.$error_txt.':</strong> ' . __( 'User registration is currently not allowed.' ) );
		}
		
	// JS/COOKIES CHECK
	$wpss_key_values 	= spamshield_get_key_values();
	$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
	$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
	$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
	$wpss_js_val 		= $wpss_key_values['wpss_js_val'];
	
	if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wpss_jsck_cookie_val = $_COOKIE[$wpss_ck_key]; }
	else { $wpss_jsck_cookie_val = ''; }
	if ( $wpss_jsck_cookie_val != $wpss_ck_val ) {
		$wpss_error_code .= ' '.$pref.'COOKIE-3';
		$reg_jsck_error = true;
		}
	if ( !empty( $_POST[$wpss_js_key] ) ) {
		$wpss_jsck_field_val 	= $_POST[$wpss_js_key];
		}
	else { $wpss_jsck_field_val = ''; }
	if ( $wpss_jsck_field_val != $wpss_js_val ) {
		$wpss_error_code .= ' '.$pref.'FVFJS-3';
		$reg_jsck_error = true;
		}
	if ( !empty( $_POST['JSONST'] ) ) { $post_jsonst = $_POST['JSONST']; } else { $post_jsonst = ''; }
	if ( $post_jsonst == $ns_val ) {
		$wpss_error_code .= ' '.$pref.'JSONST-1000-3';
		$reg_jsck_error = true;
		}
	
	if ( $reg_jsck_error != false && $reg_badrobot_error != true ) {
		$errors->add( 'jsck_error', '<strong>'.$error_txt.':</strong> ' . __( 'JavaScript and Cookies are required in order to register. Please be sure JavaScript and Cookies are enabled in your browser, and reload the page.', WPSS_PLUGIN_NAME ) );
		}

	// EMAIL BLACKLIST
	if ( spamshield_email_blacklist_chk($user_email) ) {
		$wpss_error_code .= ' '.$pref.'9200E-BL';
		if ( $reg_badrobot_error != true && $reg_jsck_error != true ) {
			$errors->add( 'blacklist_email_error', '<strong>'.$error_txt.':</strong> ' . __( 'Sorry, that email address is not allowed!' ) . ' ' . __( 'Please enter a valid email address.' ) );
			}
		}
		
	// AUTHOR KEYPHRASE BLACKLIST
	foreach( $user_data as $k => $v ) {
		$k_uc = strtoupper($k);
		if ( ( $k == 'user_login' || $k == 'first_name' || $k == 'last_name' || $k == 'display_name' ) && spamshield_anchortxt_blacklist_chk($v) ) {
			$wpss_error_code .= ' '.$pref.'10500A-BL-'.$k_uc;
			if ( $reg_badrobot_error != true && $reg_jsck_error != true ) {
				$nfk = $new_fields[$k];
				$errors->add( 'blacklist_'.$k.'_error', '<strong>'.$error_txt.':</strong> ' . sprintf( __( '"%1$s" appears to be spam. Please enter a different value in the <strong> %2$s </strong> field.', WPSS_PLUGIN_NAME ), sanitize_text_field($v), $nfk ) );
				}
			}
		}
		
	// BLACKLISTED USER
	$wpss_lang_ck_key = 'UBR_LANG';
	$wpss_lang_ck_val = 'default';
	if ( empty( $wpss_error_code ) && ( !empty( $_SESSION['wpss_blacklisted_user_'.RSMP_HASH] ) || ( !empty( $_COOKIE[$wpss_lang_ck_key] ) && $_COOKIE[$wpss_lang_ck_key] == $wpss_lang_ck_val ) ) ) {
		$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true;
		$wpss_error_code .= ' '.$pref.'0-BL';
		$errors->add( 'blacklisted_user_error', '<strong>'.$error_txt.':</strong> ' . __( 'User registration is currently not allowed.' ) );
		}

	// Done with Tests
	
	// Now Log the Errors, if any

	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }
	$post_ref2xjs = strtolower($post_ref2xjs);
	if ( !empty( $post_ref2xjs ) ) {
		$ref2xJS = strtolower( addslashes( urldecode( $post_ref2xjs ) ) );
		$ref2xJS = str_replace( '%3a', ':', $ref2xJS );
		$ref2xJS = str_replace( ' ', '+', $ref2xJS );
		$wpss_javascript_page_referrer = esc_url_raw( $ref2xJS );
		}
	else { $wpss_javascript_page_referrer = '[None]'; }

	if ( $post_jsonst == 'NS3' ) { $wpss_jsonst = $post_jsonst; } else { $wpss_jsonst = '[None]'; }
	
	$user_id = 'None'; // Possibly change to ''
	
	$register_author_data = array(
		'display_name' 				=> $user_data['display_name'],
		'user_firstname' 			=> $user_data['first_name'],
		'user_lastname' 			=> $user_data['last_name'],
		'user_email' 				=> $user_email,
		'user_login' 				=> $user_login,
		'ID' 						=> $user_id,
		'comment_author'			=> $user_data['display_name'],
		'comment_author_email'		=> $user_email,
		'comment_author_url'		=> '',
		'javascript_page_referrer'	=> $wpss_javascript_page_referrer,
		'jsonst'					=> $wpss_jsonst,
		);
	
	$wpss_error_code = trim($wpss_error_code);
	
	if ( !empty( $wpss_error_code ) && !empty( $spamshield_options['comment_logging'] ) ) {
		spamshield_log_data( $register_author_data, $wpss_error_code, 'register' );
		}

	if ( empty( $wpss_error_code ) ) {
		$wpss_error_code = 'No Error';
		}
	else {
		spamshield_update_sess_accept_status( $register_author_data, 'r', 'Line: '.__LINE__ );
		update_option( 'spamshield_count', get_option('spamshield_count') + 1 );
		update_option( 'spamshield_reg_count', get_option('spamshield_reg_count') + 1 );
		}

	// Now return the error values
	return $errors;
	}

function spamshield_user_register( $user_id ) {
	if ( spamshield_is_login_page() ) {
		$spamshield_options 	= get_option('spamshield_options');
		
		// Check if registration spam shield is disabled - Added in 1.6.9
		if ( !empty( $spamshield_options['registration_shield_disable'] ) ) { return; }
		
		$new_fields = array(
			'first_name' 	=> __( 'First Name', WPSS_PLUGIN_NAME ),
			'last_name' 	=> __( 'Last Name', WPSS_PLUGIN_NAME ),
			'display_name' 	=> __( 'Display Name', WPSS_PLUGIN_NAME ),
			);
		$user_data = array();
		foreach( $new_fields as $k => $v ) {
			if ( isset( $_POST[$k] ) ) { $user_data[$k] = trim( wp_unslash( $_POST[$k] ) ); } else { $user_data[$k] = ''; }
			}
		if ( !empty($user_data) ) {
			$user_data['ID'] = $user_id;
			wp_update_user( $user_data );
			}

		$wpss_display_name = $wpss_user_firstname = $wpss_user_lastname = $wpss_user_email = $wpss_user_url = $wpss_user_login = '';

		$user_info = get_userdata( $user_id );
		
		if ( isset( $user_info->display_name ) ) 	{ $wpss_display_name	= $user_info->display_name; }
		if ( isset( $user_info->user_firstname ) ) 	{ $wpss_user_firstname	= $user_info->user_firstname; }
		if ( isset( $user_info->user_lastname ) ) 	{ $wpss_user_lastname	= $user_info->user_lastname; }
		if ( isset( $user_info->user_email ) ) 		{ $wpss_user_email		= $user_info->user_email; }
		if ( isset( $user_info->user_url ) ) 		{ $wpss_user_url		= $user_info->user_url; }
		if ( isset( $user_info->user_login ) ) 		{ $wpss_user_login		= $user_info->user_login; }
		
		$wpss_comment_author 		= $wpss_display_name;
		$wpss_comment_author_email	= $wpss_user_email;
		$wpss_comment_author_url 	= $wpss_user_url;
			
		$register_author_data = array(
			'display_name' 			=> $wpss_display_name,
			'user_firstname' 		=> $wpss_user_firstname,
			'user_lastname' 		=> $wpss_user_lastname,
			'user_email' 			=> $wpss_user_email,
			'user_url' 				=> $wpss_user_url,
			'user_login' 			=> $wpss_user_login,
			'ID' 					=> $user_id,
			'comment_author'		=> $wpss_display_name,
			'comment_author_email'	=> $wpss_user_email,
			'comment_author_url'	=> $wpss_user_url,
			);

		$wpss_error_code = 'No Error';
		
		spamshield_update_sess_accept_status( $register_author_data, 'a', 'Line: '.__LINE__ );

		if ( !empty( $spamshield_options['comment_logging'] ) && !empty( $spamshield_options['comment_logging_all'] ) ) {
			spamshield_log_data( $register_author_data, $wpss_error_code, 'register' );
			}
		}
	}

/*
function spamshield_login_robot_check() {
	$rev_dns_filter_data = spamshield_revdns_filter( 'register' );
	$revdns_blacklisted 	 = $rev_dns_filter_data['blacklisted'];
	if ( !empty( $revdns_blacklisted ) ) {
		$robot_error = __( 'Silly robot, this page is for humans!', WPSS_PLUGIN_NAME );
		$args = array( 'response' => '403' );
		wp_die( $robot_error, '', $args );
		}
	}
*/
	
// Spam Registration Protection - END


// Admin Functions - BEGIN

function spamshield_dashboard_stats() {
	
	$spamshield_count = spamshield_count();
	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);

	if ( empty( $spamshield_options['install_date'] ) ) {
		$install_date = @date('Y-m-d');
		$spamshield_options_update = $spamshield_options;
		$spamshield_options_update['install_date'] = $install_date;
		update_option( 'spamshield_options', $spamshield_options_update );
		}
	else {
		$install_date = $spamshield_options['install_date'];
		}
	$current_date = @date('Y-m-d');
	$num_days_installed = spamshield_date_diff($install_date, $current_date);
	if ( $num_days_installed < 1 ) {
		$num_days_installed = 1;
		}
	$spam_count_so_far = $spamshield_count;
	if ( $spam_count_so_far < 1 ) {
		$spam_count_so_far = 1;
		}
	$avg_blocked_daily = @round( $spam_count_so_far / $num_days_installed );
	
	if ( current_user_can('manage_options') ) {
		$spam_stat_incl_link = ' (<a href="options-general.php?page='.WPSS_PLUGIN_NAME.'"">' . __( 'Settings' ) . '</a>)</p>'."\n";
		$spam_stat_url = 'options-general.php?page='.WPSS_PLUGIN_NAME;
		$spam_stat_href_attr = '';
		}
	else {
		$spam_stat_incl_link = '';
		$spam_stat_url = WPSS_HOME_LINK;
		$spam_stat_href_attr = 'target="_blank" rel="external"';
		}

	if ( empty( $spamshield_count ) ) {
		echo '<p>' . __( 'No comment spam attempts have been detected yet.', WPSS_PLUGIN_NAME ) . $spam_stat_incl_link;
		}
	else {
		echo '<p>'."<img src='".WPSS_PLUGIN_IMG_URL."/spam-protection-24.png' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' />".' <a href="'.$spam_stat_url.'" '.$spam_stat_href_attr.'>WP-SpamShield</a> '.sprintf( __( 'has blocked <strong> %s </strong> spam.', WPSS_PLUGIN_NAME ), number_format($spamshield_count) ).'</p>'."\n";
		if ( $avg_blocked_daily >= 2 ) {
			echo "<p><img src='".WPSS_PLUGIN_IMG_URL."/spacer.gif' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> " . __( 'Average spam blocked daily', WPSS_PLUGIN_NAME ) . ": <strong>".$avg_blocked_daily."</strong></p>"."\n";
			}
		}
	}

function spamshield_filter_plugin_actions( $links, $file ) {
	// Add "Settings" Link on Admin Plugins page, in plugin listings
	if ( $file == WPSS_PLUGIN_BASENAME ){
		$settings_link = '<a href="options-general.php?page='.WPSS_PLUGIN_NAME.'">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
		}
	return $links;
	}

function spamshield_filter_plugin_meta( $links, $file ) {
	// Add "Settings" Link on Admin Plugins page, in plugin meta
	if ( $file == WPSS_PLUGIN_BASENAME ){
		// after other links
		//$links[] = '<a href="options-general.php?page='.WPSS_PLUGIN_NAME.'">' . __('Settings') . '</a>';
		$links[] = '<a href="http://www.redsandmarketing.com/plugins/wp-spamshield/" target="_blank" rel="external" >' . spamshield_doc_txt() . '</a>';
		$links[] = '<a href="http://www.redsandmarketing.com/plugins/wp-spamshield/support/" target="_blank" rel="external" >' . __( 'Support', WPSS_PLUGIN_NAME ) . '</a>';
		$links[] = '<a href="http://bit.ly/wp-spamshield-donate" target="_blank" rel="external" >' . __( 'Donate', WPSS_PLUGIN_NAME ) . '</a>';
		}
	return $links;
	}

function spamshield_admin_notices() {
	$admin_notices = get_option('spamshield_admin_notices');
	if ( !empty( $admin_notices ) ) {
		$style 	= $admin_notices['style']; // 'error'  or 'updated'
		$notice	= $admin_notices['notice'];
		echo '<div class="'.$style.'"><p>'.$notice.'</p></div>';
		}
	delete_option('spamshield_admin_notices');
	}

function spamshield_upgrade_check( $installed_ver = null ) {
	if ( empty( $installed_ver ) ) { $installed_ver = get_option('wp_spamshield_version'); }
	if ( $installed_ver != WPSS_VERSION ) { update_option( 'wp_spamshield_version', WPSS_VERSION ); }
	}

function spamshield_admin_jp_fix() {
	// Fix Compatibility with JetPack if active
	// The JP Comments module modifies WordPress' comment system core functionality, incapacitating MANY fine plugins...sorry guys, but this has to be deactivated
	$wpss_jp_active	= spamshield_is_plugin_active( 'jetpack/jetpack.php' );
	if ( !empty( $wpss_jp_active ) ) {
		$jp_active_mods = get_option('jetpack_active_modules');
		if ( !empty( $jp_active_mods ) && is_array( $jp_active_mods ) ) {
			$jp_com_key = array_search( 'comments', $jp_active_mods, true );
			if ( !isset( $jp_com_key ) || !is_int( $jp_com_key ) ) { $jp_com_key = 'Not found in array.'; }
			}
		else {
			$jp_com_key = 'Not array or empty array.';
			return;
			}
		if ( isset( $jp_com_key ) && is_int( $jp_com_key ) ) {
			$jp_num_active_mods = count( $jp_active_mods );
			if ( empty( $jp_active_mods ) ) { $jp_num_active_mods = 0; }
			if ( $jp_num_active_mods < 2 ) { $jp_active_mods = array(); } else { unset( $jp_active_mods[$jp_com_key] ); }
			update_option( 'jetpack_active_modules', $jp_active_mods );
			spamshield_append_log_data( "\n".'JetPack Comments module deactivated.'.'" Line: '.__LINE__, false );
			}
		}
	}

// Admin Functions - END


// wpSpamShield CLASS - BEGIN

if (!class_exists('wpSpamShield')) {
    class wpSpamShield {
	
		// The name the options are saved under in the database.
		var $adminOptionsName = 'wp_spamshield_options';
		
		// The name of the database table used by the plugin
		var $db_table_name = 'wp_spamshield';
		
		function wpSpamShield() {
			global $wpdb;
			if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) !== 0 && RSMP_SERVER_ADDR != '127.0.0.1' && !WPSS_DEBUG && !WP_DEBUG ) {
				error_reporting(0); // Prevents error display on production sites, but testing on 127.0.0.1 will display errors, or if debug mode turned on
				}
			register_activation_hook( __FILE__, array(&$this,'spamshield_activation') );
			add_action( 'admin_init', array(&$this,'spamshield_check_version') );
			add_action( 'plugins_loaded', 'spamshield_load_languages' );
			add_action( 'init', 'spamshield_first_action', 1 );
			add_action( 'widgets_init', 'spamshield_load_widgets' ); // Added 1.6.5
			add_action( 'wp_logout', 'spamshield_end_session' );
			add_action( 'wp_login', 'spamshield_end_session' );
			add_action( 'admin_menu', array(&$this,'spamshield_add_plugin_settings_page') );
			add_action( 'wp_head', array(&$this, 'spamshield_insert_head_js') );
			add_filter( 'the_content', 'spamshield_contact_form', 10 );
			add_action( 'comment_form', 'spamshield_comment_form_addendum',10 );
			add_action( 'preprocess_comment', 'spamshield_check_comment_type',1 );
			add_filter( 'comment_notification_text', 'spamshield_comment_notification_addendum', 10, 2 );
			add_filter( 'comment_moderation_text', 'spamshield_comment_moderation_addendum', 10, 2 );
			add_action( 'activity_box_end', 'spamshield_dashboard_stats' );
			add_filter( 'plugin_action_links', 'spamshield_filter_plugin_actions', 10, 2 );
			add_filter( 'plugin_row_meta', 'spamshield_filter_plugin_meta', 10, 2 ); // Added 1.7.1
			add_action( 'login_head', array(&$this, 'spamshield_insert_head_js') );
			//add_action( 'login_head', 'spamshield_login_robot_check', 1 );
			add_action( 'register_form', 'spamshield_register_form_addendum', 1 );
			add_filter( 'registration_errors', 'spamshield_check_new_user', 1, 3 );
			add_action( 'user_register', 'spamshield_user_register' );
			if ( function_exists( 'spamshield_modify_signup_notification_admin' ) ) {
				add_filter( 'spamshield_signup_notification_text_admin', 'spamshield_modify_signup_notification_admin', 1, 3 );
				}
			if ( function_exists( 'spamshield_modify_signup_notification_user' ) ) {
				add_filter( 'spamshield_signup_notification_text_user', 'spamshield_modify_signup_notification_user', 1, 3 );
				}
			add_shortcode( 'spamshieldcountersm', 'spamshield_counter_sm_short' );
			add_shortcode( 'spamshieldcounter', 'spamshield_counter_short' );
			add_shortcode( 'spamshieldcontact', 'spamshield_contact_shortcode' );
        	}

		// Class Admin Functions - BEGIN
		
		function spamshield_activation() {
			global $wpdb;
			$installed_ver = get_option('wp_spamshield_version');
			$spamshield_options = get_option('spamshield_options');
			spamshield_update_session_data($spamshield_options);

			spamshield_upgrade_check( $installed_ver );
			
			// Only run installation if not installed already
			if ( empty( $installed_ver ) || empty( $spamshield_options ) ) {
				
				// Upgrade from old version
				// Import existing WP-SpamFree Options, only on first activation, if old plugin is active
				$old_version = 'wp-spamfree/wp-spamfree.php';
				$old_version_active = spamshield_is_plugin_active( $old_version );
				$wpsf_installed_ver = get_option('wp_spamfree_version');
				if ( !empty( $wpsf_installed_ver ) && empty( $installed_ver ) && !empty( $old_version_active ) ) {
					$spamfree_options = get_option('spamfree_options');
					}

				// Set Initial Options
				if ( !empty( $spamshield_options ) ) {
					$spamshield_options_update = $spamshield_options;	
					}
				elseif ( !empty( $spamfree_options ) ) {
					$spamshield_options_update = $spamfree_options;
					$notice_text = __( 'You have successfully upgraded from WP-SpamFree to WP-SpamShield. You will now experience improved security, page load speed, and spam-blocking power. All your old settings have been imported, and your contact forms will continue to work without you having to do anything else. You can safely remove the outdated WP-SpamFree plugin.', WPSS_PLUGIN_NAME );
					$new_admin_notice = array( 'style' => 'updated', 'notice' => $notice_text );
					update_option( 'spamshield_admin_notices', $new_admin_notice );
					}
				else {
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
						'block_all_trackbacks' 					=> $spamshield_default['block_all_trackbacks'],
						'block_all_pingbacks' 					=> $spamshield_default['block_all_pingbacks'],
						'comment_logging'						=> $spamshield_default['comment_logging'],
						'comment_logging_start_date'			=> $spamshield_default['comment_logging_start_date'],
						'comment_logging_all'					=> $spamshield_default['comment_logging_all'],
						'enhanced_comment_blacklist'			=> $spamshield_default['enhanced_comment_blacklist'],
						'enable_whitelist'						=> $spamshield_default['enable_whitelist'],
						'allow_proxy_users'						=> $spamshield_default['allow_proxy_users'],
						'hide_extra_data'						=> $spamshield_default['hide_extra_data'],
						'registration_shield_disable'			=> $spamshield_default['registration_shield_disable'],
						'registration_shield_level_1'			=> $spamshield_default['registration_shield_level_1'],
						'allow_comment_author_keywords'			=> $spamshield_default['allow_comment_author_keywords'],
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
					update_option( 'spamshield_count', 0 );
					}
				update_option('spamshield_options', $spamshield_options_update);
				//spamshield_reset_procdat();
				// Reset Log and Initialize .htaccess
				$last_admin_ip = get_option( 'spamshield_last_admin' );
				if ( !empty( $last_admin_ip ) ) { spamshield_log_reset( $last_admin_ip ); }
				update_option('ak_count_pre', get_option('akismet_spam_count'));
				// Require Author Names and Emails on Comments - Added 1.1.7
				update_option('require_name_email', '1');
				// Set 'default_role' to 'subscriber' for security - Added 1.3.7
				update_option('default_role', 'subscriber');
				// Turn on Comment Moderation
				//update_option('comment_moderation', 1);
				//update_option('moderation_notify', 1);
				// Compatibility Checks
				spamshield_admin_jp_fix();
				
				// Ensure Correct Permissions of JS file - BEGIN
				$installation_file_test_3 = WPSS_PLUGIN_JS_PATH.'/jscripts.php';
				clearstatcache();
				$installation_file_test_3_perm = substr(sprintf('%o', fileperms($installation_file_test_3)), -4);
				if ( $installation_file_test_3_perm < '0755' || !is_readable($installation_file_test_3) || !is_executable($installation_file_test_3) ) {
					@chmod( $installation_file_test_3, 0755 ); // 755, really? Yes, this really has to be 755!
					}
				// Ensure Correct Permissions of JS file - END
				}
			}

		function spamshield_add_plugin_settings_page() {
			//add_submenu_page( 'options-general.php', 'WP-SpamShield ' . __('Settings'), 'WP-SpamShield', 'manage_options', WPSS_PLUGIN_NAME, array(&$this,'spamshield_plugin_settings_page') );
			add_options_page( 'WP-SpamShield ' . __('Settings'), 'WP-SpamShield', 'manage_options', WPSS_PLUGIN_NAME, array(&$this,'spamshield_plugin_settings_page') );
			}
		
		function spamshield_plugin_settings_page() {
			if ( !current_user_can('manage_options') ) {
				$restricted_area_warning = __( 'You do not have sufficient permissions to access this page.' );
				wp_die( $restricted_area_warning );
				}
			
			echo "\n\t\t\t".'<div class="wrap">'."\n\t\t\t".'<h2>WP-SpamShield ' . __( 'Settings' ) . '</h2>'."\n";

			$spamshield_count = spamshield_count();
			$spamshield_options = get_option('spamshield_options');
			spamshield_update_session_data($spamshield_options);
		
			$install_date = $spamshield_options['install_date'];
			if ( empty( $install_date ) ) {
				$install_date = @date('Y-m-d');
				}
			$current_date = @date('Y-m-d');
			$num_days_installed = spamshield_date_diff($install_date, $current_date);
			if ( $num_days_installed < 1 ) {
				$num_days_installed = 1;
				}
			$spam_count_so_far = $spamshield_count;
			if ( $spam_count_so_far < 1 ) {
				$spam_count_so_far = 1;
				}
			$avg_blocked_daily = @round( $spam_count_so_far / $num_days_installed );

			$installation_plugins_get_test_1	= WPSS_PLUGIN_NAME; // 'wp-spamshield' - Checking for 'options-general.php?page='.WPSS_PLUGIN_NAME
			$installation_file_test_0 			= WPSS_PLUGIN_FILE_PATH; // '/public_html/wp-content/plugins/wp-spamshield/wp-spamshield.php'
			$installation_file_test_3 			= WPSS_PLUGIN_JS_PATH.'/jscripts.php';
			clearstatcache();
			$installation_file_test_3_perm = substr(sprintf('%o', fileperms($installation_file_test_3)), -4);
			if ( $installation_file_test_3_perm < '0755' || !is_readable($installation_file_test_3) || !is_executable($installation_file_test_3) ) {
				@chmod( $installation_file_test_3, 0755 ); // 755, really? Yes, this really has to be 755!
				}
			clearstatcache();
			if ( !empty( $_GET['page'] ) ) {
				$get_page = $_GET['page'];
				}
			else {
				$get_page = '';
				}
			if ( $installation_plugins_get_test_1 == $get_page && file_exists($installation_file_test_0) && file_exists($installation_file_test_3) ) {
				$wp_installation_status = 1;
				$wp_installation_status_image = 'status-installed-correctly-24';
				$wp_installation_status_color = 'green';
				$wp_installation_status_bg_color = '#CCFFCC';
				$wp_installation_status_msg_main = __( 'Installed Correctly', WPSS_PLUGIN_NAME );
				$wp_installation_status_msg_text = strtolower($wp_installation_status_msg_main);
				}
			else {
				$wp_installation_status = 0;
				$wp_installation_status_image = 'status-not-installed-correctly-24';
				$wp_installation_status_color = 'red';
				$wp_installation_status_bg_color = '#FFCCCC';
				$wp_installation_status_msg_main = __( 'Not Installed Correctly', WPSS_PLUGIN_NAME );
				$wp_installation_status_msg_text = strtolower($wp_installation_status_msg_main);
				}

			if ( !empty( $_REQUEST['submit_wpss_general_options'] ) && current_user_can('manage_options') && check_admin_referer('wpss_general_options_nonce') ) {
				echo '<div class="updated fade"><p>' . __( 'Plugin Spam settings saved.', WPSS_PLUGIN_NAME ) . '</p></div>';
				}
			if ( !empty( $_REQUEST['submit_wpss_contact_options'] ) && current_user_can('manage_options') && check_admin_referer('wpss_contact_options_nonce') ) {
				echo '<div class="updated fade"><p>' . __( 'Plugin Contact Form settings saved.', WPSS_PLUGIN_NAME ) . '</p></div>';
				}
			if ( !empty( $_REQUEST['wpss_action'] ) ) { $wpss_action = $_REQUEST['wpss_action']; } else { $wpss_action = ''; }
			if ( $wpss_action == 'blacklist_ip' && !empty( $_REQUEST['submitter_ip'] ) && current_user_can('manage_options') && empty( $_REQUEST['submit_wpss_general_options'] ) && empty( $_REQUEST['submit_wpss_contact_options'] ) ) {
				$ip_to_blacklist = trim(stripslashes($_REQUEST['submitter_ip']));
				if ( spamshield_is_valid_ip( $ip_to_blacklist ) ) {
					$$ip_to_blacklist_valid='1';
					spamshield_add_ip_to_blacklist($ip_to_blacklist);
					echo '<div class="updated fade"><p>' . __( 'IP Address added to Comment Blacklist.', WPSS_PLUGIN_NAME ) . '</p></div>';
					}
				else {
					echo '<div class="error fade"><p>' . __( 'Invalid IP Address - not added to Comment Blacklist.', WPSS_PLUGIN_NAME ) . '</p></div>';
					}
				}

			?>
			<div style='width:797px;border-style:solid;border-width:1px;border-color:<?php echo $wp_installation_status_color; ?>;background-color:<?php echo $wp_installation_status_bg_color; ?>;padding:0px 15px 0px 15px;margin-top:30px;float:left;clear:left;'>
			<p><strong><?php echo "<img src='".WPSS_PLUGIN_IMG_URL."/".$wp_installation_status_image.".png' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> " . __( 'Installation Status', WPSS_PLUGIN_NAME ) . ": <span style='color:".$wp_installation_status_color.";'>".$wp_installation_status_msg_main."</span>"; ?></strong></p>
			</div>
			
			<?php
			if ($spamshield_count) {
				echo "\n\t\t\t<div style='width:797px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:15px;float:left;clear:left;'>\n\t\t\t<p><img src='".WPSS_PLUGIN_IMG_URL."/spam-protection-24.png' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> " . sprintf( __( 'WP-SpamShield has blocked <strong> %s </strong> spam!', WPSS_PLUGIN_NAME ), number_format($spamshield_count) ) . "</p>\n\t\t\t";
				if ( $avg_blocked_daily >= 2 ) {
					echo "<p><img src='".WPSS_PLUGIN_IMG_URL."/spacer.gif' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> " . sprintf( __( 'That\'s <strong> %s </strong> spam a day that you don\'t have to worry about.', WPSS_PLUGIN_NAME ), $avg_blocked_daily ) . "</p>\n\t\t\t";
					}
				echo "</div>\n\t\t\t";
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
				if ( !empty( $_REQUEST['comment_logging'] ) ) { $req_comment_logging = $_REQUEST['comment_logging']; } else { $req_comment_logging = 0; }
				if ( !empty( $_REQUEST['comment_logging_all'] ) ) { $req_comment_logging_all = $_REQUEST['comment_logging_all']; } else { $req_comment_logging_all = 0; }
				if ( !empty( $req_comment_logging_all ) ) { $req_comment_logging = 1; }
				if ( !empty( $req_comment_logging ) && empty( $spamshield_options['comment_logging_start_date'] ) ) {
					$comment_logging_start_date = time();
					spamshield_log_reset();
					}
				elseif ( !empty( $req_comment_logging ) && !empty( $spamshield_options['comment_logging_start_date'] ) ) {
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
				
				$wpss_options_general_boolean = array ( 'block_all_trackbacks', 'block_all_pingbacks', 'comment_logging', 'comment_logging_all', 'enhanced_comment_blacklist', 'enable_whitelist', 'allow_proxy_users', 'hide_extra_data', 'registration_shield_disable', 'registration_shield_level_1', 'allow_comment_author_keywords', 'promote_plugin_link' );
				
				$wpss_options_general_boolean_count = count( $wpss_options_general_boolean );
				$i = 0;
				while ( $i < $wpss_options_general_boolean_count ) {
					if ( !empty( $_REQUEST[$wpss_options_general_boolean[$i]] ) && ( $_REQUEST[$wpss_options_general_boolean[$i]] == 'on' || $_REQUEST[$wpss_options_general_boolean[$i]] == 1 || $_REQUEST[$wpss_options_general_boolean[$i]] == '1' ) ) { $valid_req_spamshield_options[$wpss_options_general_boolean[$i]] = 1; }
					else { $valid_req_spamshield_options[$wpss_options_general_boolean[$i]] = 0; }
					$i++;
					}
				if ( empty( $spamshield_options['comment_logging_all'] ) && $valid_req_spamshield_options['comment_logging_all'] == 1 ) { // Added 1.4.3 - Turns Blocked Comment Logging Mode on if user selects "Log All Comments"
					$valid_req_spamshield_options['comment_logging'] = 1;
					}
				if ( !empty( $spamshield_options['comment_logging'] ) && $valid_req_spamshield_options['comment_logging'] == 0 ) { // Added 1.4.3 - If Blocked Comment Logging Mode is turned off then deselects "Log All Comments"
					$valid_req_spamshield_options['comment_logging_all'] = 0;
					}

				// Update Values
				$spamshield_options_update = array (
						'cookie_validation_name' 				=> $spamshield_options['cookie_validation_name'],
						'cookie_validation_key' 				=> $spamshield_options['cookie_validation_key'],
						'form_validation_field_js' 				=> $spamshield_options['form_validation_field_js'],
						'form_validation_key_js' 				=> $spamshield_options['form_validation_key_js'],
						'block_all_trackbacks' 					=> $valid_req_spamshield_options['block_all_trackbacks'],
						'block_all_pingbacks' 					=> $valid_req_spamshield_options['block_all_pingbacks'],
						'comment_logging'						=> $valid_req_spamshield_options['comment_logging'],
						'comment_logging_start_date'			=> $comment_logging_start_date,
						'comment_logging_all'					=> $valid_req_spamshield_options['comment_logging_all'],
						'enhanced_comment_blacklist'			=> $valid_req_spamshield_options['enhanced_comment_blacklist'],
						'enable_whitelist'						=> $valid_req_spamshield_options['enable_whitelist'],
						'allow_proxy_users'						=> $valid_req_spamshield_options['allow_proxy_users'],
						'hide_extra_data'						=> $valid_req_spamshield_options['hide_extra_data'],
						'registration_shield_disable'			=> $valid_req_spamshield_options['registration_shield_disable'],
						'registration_shield_level_1'			=> $valid_req_spamshield_options['registration_shield_level_1'],
						'allow_comment_author_keywords'			=> $valid_req_spamshield_options['allow_comment_author_keywords'],
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
				update_option( 'spamshield_options', $spamshield_options_update );
				if ( !empty( $_SERVER['REMOTE_ADDR'] ) ) { update_option( 'spamshield_last_admin', $_SERVER['REMOTE_ADDR'] ); }
				$blacklist_keys_update = trim(stripslashes($_REQUEST['wordpress_comment_blacklist']));
				spamshield_update_blacklist_keys($blacklist_keys_update);
				$whitelist_keys_update = trim(stripslashes($_REQUEST['wpss_whitelist']));
				spamshield_update_whitelist_keys($whitelist_keys_update);
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
						'block_all_trackbacks' 					=> $spamshield_options['block_all_trackbacks'],
						'block_all_pingbacks' 					=> $spamshield_options['block_all_pingbacks'],
						'comment_logging'						=> $spamshield_options['comment_logging'],
						'comment_logging_start_date'			=> $spamshield_options['comment_logging_start_date'],
						'comment_logging_all'					=> $spamshield_options['comment_logging_all'],
						'enhanced_comment_blacklist'			=> $spamshield_options['enhanced_comment_blacklist'],
						'enable_whitelist'						=> $spamshield_options['enable_whitelist'],
						'allow_proxy_users'						=> $spamshield_options['allow_proxy_users'],
						'hide_extra_data'						=> $spamshield_options['hide_extra_data'],
						'registration_shield_disable'			=> $spamshield_options['registration_shield_disable'],
						'registration_shield_level_1'			=> $spamshield_options['registration_shield_level_1'],
						'allow_comment_author_keywords'			=> $spamshield_options['allow_comment_author_keywords'],
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
				if ( !empty( $spamshield_options['comment_logging_all'] ) ) { $spamshield_options_update['comment_logging'] = 1; }
				if ( empty( $spamshield_options['comment_logging'] ) ) { $spamshield_options_update['comment_logging_all'] = 0; }
				update_option( 'spamshield_options', $spamshield_options_update );
				if ( !empty( $_SERVER['REMOTE_ADDR'] ) ) { update_option( 'spamshield_last_admin', $_SERVER['REMOTE_ADDR'] ); }
				}
			$spamshield_options = get_option('spamshield_options');
			spamshield_update_session_data($spamshield_options);
			if ( !spamshield_is_lang_en_us() ) { $wpss_info_box_height = '400'; } else { $wpss_info_box_height = '305'; }
			?>
			
			<div style="width:375px;height:<?php echo $wpss_info_box_height; ?>px;border-style:solid;border-width:1px;border-color:#003366;background-color:#DDEEFF;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;clear:left;">
			<p><a name="wpss_top"><strong><?php _e( 'Quick Navigation - Contents', WPSS_PLUGIN_NAME ); ?></strong></a></p>
			<ol style="list-style-type:decimal;padding-left:30px;">
				<li><a href="#wpss_general_options"><?php _e( 'General Settings', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="#wpss_contact_form_options"><?php _e( 'Contact Form Settings', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_LINK; ?>#wpss_installation_instructions" target="_blank" rel="external" ><?php _e( 'Installation Instructions', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_LINK; ?>#wpss_displaying_stats" target="_blank" rel="external" ><?php _e( 'Displaying Spam Stats on Your Blog', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_LINK; ?>#wpss_adding_contact_form" target="_blank" rel="external" ><?php _e( 'Adding a Contact Form to Your Blog', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_LINK; ?>#wpss_configuration" target="_blank" rel="external" ><?php _e( 'Configuration Information', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_LINK; ?>#wpss_known_conflicts" target="_blank" rel="external" ><?php _e( 'Known Plugin Conflicts', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_LINK; ?>#wpss_troubleshooting" target="_blank" rel="external" ><?php _e( 'Troubleshooting Guide / Support', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="#wpss_let_others_know"><?php _e( 'Let Others Know About WP-SpamShield', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="#wpss_download_plugin_documentation"><?php echo spamshield_doc_txt(); ?></a></li>
			</ol>
			</div>
			<div style="width:375px;height:<?php echo $wpss_info_box_height; ?>px;border-style:solid;border-width:1px;border-color:#003366;background-color:#DDEEFF;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;">
			<p>
			<?php if ( $spamshield_count > 100 ) { ?>
			<strong><?php _e( 'Happy with WP-SpamShield?', WPSS_PLUGIN_NAME ); ?></strong><br /> <a href="<?php echo WPSS_WP_RATING_LINK; ?>" target="_blank" rel="external" ><?php _e( 'Let others know by giving it a good rating on WordPress.org!', WPSS_PLUGIN_NAME ); ?></a><br />
			<img src='<?php echo WPSS_PLUGIN_IMG_URL; ?>/5-stars-rating.gif' alt='' width='99' height='19' style='border-style:none;padding-top:3px;padding-bottom:0px;' /><br /><br />
			<?php } ?>
			
			<strong><?php echo spamshield_doc_txt(); ?>:</strong> <a href="<?php echo WPSS_HOME_LINK; ?>" target="_blank" rel="external" ><?php _e( 'Plugin Homepage', WPSS_PLUGIN_NAME ); ?></a><br />
			<strong><?php _e( 'Tech Support', WPSS_PLUGIN_NAME ); ?>:</strong> <a href="<?php echo WPSS_HOME_LINK; ?>support/" target="_blank" rel="external" ><?php _e( 'WP-SpamShield Support', WPSS_PLUGIN_NAME ); ?></a><br />
			<strong><?php _e( 'Follow on Twitter', WPSS_PLUGIN_NAME ); ?>:</strong> <a href="http://twitter.com/WPSpamShield" target="_blank" rel="external" >@WPSpamShield</a><br />			
			<strong><?php _e( 'Let Others Know', WPSS_PLUGIN_NAME ); ?>:</strong> <a href="http://www.redsandmarketing.com/blog/wp-spamshield-wordpress-plugin-released/#comments" target="_blank" rel="external" ><?php _e( 'Leave a Comment' ); ?></a><br />
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="margin-top:10px;">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="DFMTNHJEPFFUL">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
			</p>
			<?php 
			// English only right now, until we get translations
			if ( spamshield_is_lang_en_us() ) {
				echo '<p><strong><a href="http://bit.ly/wp-spamshield-donate" title="' . __( 'WP-SpamShield is provided for free.', WPSS_PLUGIN_NAME ) . ' ' . __( 'If you like the plugin, consider a donation to help further its development.', WPSS_PLUGIN_NAME ) . '" target="_blank" rel="external" >' . __( 'Donate to WP-SpamShield', WPSS_PLUGIN_NAME ) . '</a></strong></p>';
				}
			?>
			</div>
			<div style='width:797px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;clear:left;'>
			<p><a name="wpss_general_options"><h3><?php _e( 'General Settings', WPSS_PLUGIN_NAME ); ?></h3></a></p>
			<form name="wpss_general_options" method="post">
			<input type="hidden" name="submitted_wpss_general_options" value="1" />
            <?php
			wp_nonce_field('wpss_general_options_nonce') ?>

			<fieldset class="options">
				<ul style="list-style-type:none;padding-left:30px;">
					<li>
					<label for="comment_logging">
						<input type="checkbox" id="comment_logging" name="comment_logging" <?php echo ($spamshield_options['comment_logging']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php echo __( 'Blocked Comment Logging Mode', WPSS_PLUGIN_NAME ) . ' &mdash; ' . __( 'See what spam has been blocked!', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Temporary diagnostic mode that logs blocked comment submissions for 7 days, then turns off automatically.', WPSS_PLUGIN_NAME ); ?><br /><?php _e( 'Log is cleared each time this feature is turned on.', WPSS_PLUGIN_NAME ); ?>
					</label>
					<?php
					// TO DO: MAKE Standalone function, Params to include feedback string for test fail (Message)
					//if ( !empty( $spamshield_options['comment_logging'] ) || !empty( $spamshield_options['comment_logging_all'] ) ) {			
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
						if ( file_exists( $wpss_htaccess_file ) ) {
							$wpss_htaccess_filesize = filesize( $wpss_htaccess_file );
							}
						else { $wpss_htaccess_filesize = 0; }
						if ( empty( $wpss_htaccess_filesize ) ) {
							@chmod( WPSS_PLUGIN_DATA_PATH, 0775 );
							@chmod( $wpss_htaccess_orig_file, 0666 );
							@chmod( $wpss_htaccess_empty_file, 0666 );
							@rename( $wpss_htaccess_orig_file, $wpss_htaccess_file );
							@copy( $wpss_htaccess_empty_file, $wpss_htaccess_orig_file );
							if ( !empty( $_SERVER['REMOTE_ADDR'] ) ) {
								$wpss_htaccess_http_host = str_replace( '.', '\.', $_SERVER['HTTP_HOST'] );
								$wpss_htaccess_blog_url = str_replace( '.', '\.', RSMP_SITE_URL );
								if ( !empty( $wpss_htaccess_blog_url ) ) {
									$wpss_htaccess_data  = "SetEnvIfNoCase Referer ".RSMP_ADMIN_URL."/ wpss_access\n";
									}
								// Possibly add login cookie info
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
							echo '<br/>'."\n".'<span style="color:red;"><strong>' . sprintf( __( 'The log file may not be writeable. You may need to manually correct the file permissions.<br/>Set the permission for the "%1$s" directory to "%2$s" and all files within it to "%3$s".</strong><br/>If that doesn\'t work, then please read the <a href="%4$s" %5$s>FAQ</a> for this topic.', WPSS_PLUGIN_NAME ), WPSS_PLUGIN_DATA_PATH, '755', '644', WPSS_HOME_LINK.'#wpss_faqs_5', 'target="_blank"' ) . '</span><br/>'."\n";
							}
						}
					?>
					<br /><strong><a href="<?php echo WPSS_PLUGIN_DATA_URL; ?>/temp-comments-log.txt" target="_blank"><?php _e( 'Download Comment Log File', WPSS_PLUGIN_NAME ); ?></a> - <?php _e( 'Right-click, and select "Save Link As"', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</li>
					<li>
					<label for="comment_logging_all">
						<input type="checkbox" id="comment_logging_all" name="comment_logging_all" <?php echo ($spamshield_options['comment_logging_all']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Log All Comments', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Requires that Blocked Comment Logging Mode be engaged. Instead of only logging blocked comments, this will allow the log to capture all comments while logging mode is turned on. This provides more technical data for comment submissions than WordPress provides, and helps us improve the plugin.<br/>If you plan on submitting spam samples to us for analysis, it\'s helpful for you to turn this on, otherwise it\'s not necessary.', WPSS_PLUGIN_NAME ); ?></label>
					<br/><a href="<?php echo WPSS_HOME_LINK; ?>#wpss_configuration_log_all_comments" target="_blank" rel="external" ><?php _e( 'For more about this, see the documentation.', WPSS_PLUGIN_NAME ); ?></a><br />&nbsp;
					
					</li>
					<li>
					<label for="enhanced_comment_blacklist">
						<input type="checkbox" id="enhanced_comment_blacklist" name="enhanced_comment_blacklist" <?php echo ($spamshield_options['enhanced_comment_blacklist']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Enhanced Comment Blacklist', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Enhances WordPress\'s Comment Blacklist - instead of just sending comments to moderation, they will be completely blocked. Also adds a link in the comment notification emails that will let you blacklist a commenter\'s IP with one click.<br/>(Useful if you receive repetitive human spam or harassing comments from a particular commenter.)', WPSS_PLUGIN_NAME ); ?></label>
					<br/><a href="<?php echo WPSS_HOME_LINK; ?>#wpss_configuration_enhanced_comment_blacklist" target="_blank" rel="external" ><?php _e( 'For more about this, see the documentation.', WPSS_PLUGIN_NAME ); ?></a><br />&nbsp;

					</li>
					<label for="wordpress_comment_blacklist">
						<?php 
						$wordpress_comment_blacklist 			= trim(stripslashes(get_option('blacklist_keys')));
						$wordpress_comment_blacklist_arr		= explode("\n",$wordpress_comment_blacklist);
						$wordpress_comment_blacklist_arr_tmp	= spamshield_sort_unique($wordpress_comment_blacklist_arr);
						$wordpress_comment_blacklist			= implode("\n",$wordpress_comment_blacklist_arr_tmp);
						?>
						<strong><?php _e( 'Your current WordPress Comment Blacklist', WPSS_PLUGIN_NAME ); ?></strong><br/><?php _e( 'When a comment contains any of these words in its content, name, URL, e-mail, or IP, it will be completely blocked, not just marked as spam. One word or IP per line. It is not case-sensitive and will match included words, so "press" on your blacklist will block "WordPress" in a comment.', WPSS_PLUGIN_NAME ); ?><br />
						<textarea id="wordpress_comment_blacklist" name="wordpress_comment_blacklist" cols="80" rows="8" /><?php echo $wordpress_comment_blacklist; ?></textarea><br/>
					</label>
					<?php _e( 'You can update this list here.', WPSS_PLUGIN_NAME ); ?> <a href="<?php echo RSMP_ADMIN_URL; ?>/options-discussion.php"><?php _e( 'You can also update it on the WordPress Discussion Settings page.', WPSS_PLUGIN_NAME ); ?></a><br/>&nbsp;
					<li>
					<label for="enable_whitelist">
						<input type="checkbox" id="enable_whitelist" name="enable_whitelist" <?php echo ($spamshield_options['enable_whitelist']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Enable WP-SpamShield Whitelist', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Enables WP-SpamShield\'s Whitelist - for comments and contact form submissions. When a comment or contact form is submitted from an e-mail address on the whitelist, it will bypass spam filters and be allowed through.<br/>(Useful if you have specific users that you want to let bypass the filters.)', WPSS_PLUGIN_NAME ); ?></label>
					<br/><a href="<?php echo WPSS_HOME_LINK; ?>#wpss_configuration_enable_whitelist" target="_blank" rel="external" ><?php _e( 'For more about this, see the documentation.', WPSS_PLUGIN_NAME ); ?></a><br />&nbsp;

					</li>
					<label for="wpss_whitelist">
						<?php 
						$wpss_whitelist 			= trim(stripslashes(get_option('spamshield_whitelist_keys')));
						$wpss_whitelist_arr			= explode("\n",$wpss_whitelist);
						$wpss_whitelist_arr_tmp		= spamshield_sort_unique($wpss_whitelist_arr);
						$wpss_whitelist				= implode("\n",$wpss_whitelist_arr_tmp);
						?>
						<strong><?php _e( 'Your current WP-SpamShield Whitelist', WPSS_PLUGIN_NAME ); ?></strong><br/><?php _e( 'One email address per line. Each entry must be a valid and complete email address, like <em>user@yourwebsite.com</em>. It is not case-sensitive and will only make exact matches, not partial matches.', WPSS_PLUGIN_NAME ); ?><br />
						<textarea id="wpss_whitelist" name="wpss_whitelist" cols="80" rows="8" /><?php echo $wpss_whitelist; ?></textarea><br/>&nbsp;
					</label>
					<li>
					<label for="block_all_trackbacks">
						<input type="checkbox" id="block_all_trackbacks" name="block_all_trackbacks" <?php echo ($spamshield_options['block_all_trackbacks']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Disable trackbacks.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Use if trackback spam is excessive. (Not recommended)', WPSS_PLUGIN_NAME ); ?><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="block_all_pingbacks">
						<input type="checkbox" id="block_all_pingbacks" name="block_all_pingbacks" <?php echo ($spamshield_options['block_all_pingbacks']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Disable pingbacks.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Use if pingback spam is excessive. Disadvantage is reduction of communication between blogs. (Not recommended)', WPSS_PLUGIN_NAME ); ?><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="allow_proxy_users">
						<input type="checkbox" id="allow_proxy_users" name="allow_proxy_users" <?php echo ($spamshield_options['allow_proxy_users']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Allow users behind proxy servers to comment?', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Many human spammers hide behind proxies, so you can uncheck this option for extra protection. (For highest user compatibility, leave it checked.)', WPSS_PLUGIN_NAME ); ?><br/>&nbsp;</label>
					</li>
					<li>
					<label for="hide_extra_data">
						<input type="checkbox" id="hide_extra_data" name="hide_extra_data" <?php echo ($spamshield_options['hide_extra_data']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Hide extra technical data in comment notifications.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'This data is helpful if you need to submit a spam sample. If you dislike seeing the extra info, you can use this option.', WPSS_PLUGIN_NAME ); ?><br/>&nbsp;</label>					
					</li>
					<li>
					<label for="registration_shield_disable">
						<input type="checkbox" id="registration_shield_disable" name="registration_shield_disable" <?php echo ($spamshield_options['registration_shield_disable']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Disable Registration Spam Shield.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'This option will disable the anti-spam shield for the WordPress registration form only. While not recommended, this option is available if you need it. Anti-spam will still remain active for comments, pingbacks, trackbacks, and contact forms.', WPSS_PLUGIN_NAME ); ?><br/>&nbsp;</label>					
					</li>
					<li>
					<label for="allow_comment_author_keywords">
						<input type="checkbox" id="allow_comment_author_keywords" name="allow_comment_author_keywords" <?php echo ($spamshield_options['allow_comment_author_keywords']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Allow Keywords in Comment Author Names.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php echo sprintf( __( 'This will allow some keywords to be used in comment author names. By default, WP-SpamShield blocks many common spam keywords from being used in the comment "%1$s" field. This option is useful for sites with users that use pseudonyms, or for sites that simply want to allow business names and keywords to be used in the comment "%2$s" field. This option is not recommended, as it can potentially allow more human spam, but it is available if you choose. Your site will still be protected against all automated comment spam.', WPSS_PLUGIN_NAME ), __( 'Name' ), __( 'Name' ) ); ?><br/>&nbsp;</label>
					</li>
					<li>
					<label for="promote_plugin_link">
						<input type="checkbox" id="promote_plugin_link" name="promote_plugin_link" <?php echo ($spamshield_options['promote_plugin_link']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Help promote WP-SpamShield?', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'This places a small link under the comments and contact form, letting others know what\'s blocking spam on your blog.', WPSS_PLUGIN_NAME ); ?><br />&nbsp;
					</label>
					</li>
				</ul>
			</fieldset>
			<p class="submit">
			<input type="submit" name="submit_wpss_general_options" value="<?php _e( 'Save Changes' ); ?>" class="button-primary" style="float:left;" />
			</p>
			</form>
			<p>&nbsp;</p>
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top"><?php _e( 'BACK TO TOP', WPSS_PLUGIN_NAME ); ?></a> ]</div></p>
			<p>&nbsp;</p>
			</div>
			<div style='width:797px;border-style:solid;border-width:1px;border-color:#003366;background-color:#DDEEFF;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;clear:left;'>
			<p><a name="wpss_contact_form_options"><h3><?php _e( 'Contact Form Settings', WPSS_PLUGIN_NAME ); ?></h3></a></p>
			<form name="wpss_contact_options" method="post">
			<input type="hidden" name="submitted_wpss_contact_options" value="1" />
            <?php
			wp_nonce_field('wpss_contact_options_nonce') ?>

			<fieldset class="options">
				<ul style="list-style-type:none;padding-left:30px;">
					<li>
					<label for="form_include_website">
						<input type="checkbox" id="form_include_website" name="form_include_website" <?php echo ($spamshield_options['form_include_website']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Include "Website" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_website">
						<input type="checkbox" id="form_require_website" name="form_require_website" <?php echo ($spamshield_options['form_require_website']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Require "Website" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_phone">
						<input type="checkbox" id="form_include_phone" name="form_include_phone" <?php echo ($spamshield_options['form_include_phone']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Include "Phone" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_phone">
						<input type="checkbox" id="form_require_phone" name="form_require_phone" <?php echo ($spamshield_options['form_require_phone']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Require "Phone" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_company">
						<input type="checkbox" id="form_include_company" name="form_include_company" <?php echo ($spamshield_options['form_include_company']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Include "Company" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_company">
						<input type="checkbox" id="form_require_company" name="form_require_company" <?php echo ($spamshield_options['form_require_company']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Require "Company" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>					<li>
					<label for="form_include_drop_down_menu">
						<input type="checkbox" id="form_include_drop_down_menu" name="form_include_drop_down_menu" <?php echo ($spamshield_options['form_include_drop_down_menu']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Include drop-down menu select field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_drop_down_menu">
						<input type="checkbox" id="form_require_drop_down_menu" name="form_require_drop_down_menu" <?php echo ($spamshield_options['form_require_drop_down_menu']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Require drop-down menu select field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>					
					<li>
					<label for="form_drop_down_menu_title">
						<?php $form_drop_down_menu_title = trim(stripslashes($spamshield_options['form_drop_down_menu_title'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_title" name="form_drop_down_menu_title" value="<?php if ( !empty( $form_drop_down_menu_title ) ) { echo $form_drop_down_menu_title; } else { echo '';} ?>" />
						<strong><?php _e( 'Title of drop-down select menu. (Menu won\'t be shown if empty.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_1">
						<?php $form_drop_down_menu_item_1 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_1'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_1" name="form_drop_down_menu_item_1" value="<?php if ( !empty( $form_drop_down_menu_item_1 ) ) { echo $form_drop_down_menu_item_1; } else { echo '';} ?>" />
						<strong><?php _e( 'Drop-down select menu item 1. (Menu won\'t be shown if empty.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_2">
						<?php $form_drop_down_menu_item_2 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_2'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_2" name="form_drop_down_menu_item_2" value="<?php if ( !empty( $form_drop_down_menu_item_2 ) ) { echo $form_drop_down_menu_item_2; } else { echo '';} ?>" />
						<strong><?php _e( 'Drop-down select menu item 2. (Menu won\'t be shown if empty.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_3">
						<?php $form_drop_down_menu_item_3 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_3'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_3" name="form_drop_down_menu_item_3" value="<?php if ( !empty( $form_drop_down_menu_item_3 ) ) { echo $form_drop_down_menu_item_3; } else { echo '';} ?>" />
						<strong><?php _e( 'Drop-down select menu item 3. (Leave blank if not using.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_4">
						<?php $form_drop_down_menu_item_4 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_4'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_4" name="form_drop_down_menu_item_4" value="<?php if ( !empty( $form_drop_down_menu_item_4 ) ) { echo $form_drop_down_menu_item_4; } else { echo '';} ?>" />
						<strong><?php _e( 'Drop-down select menu item 4. (Leave blank if not using.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_5">
						<?php $form_drop_down_menu_item_5 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_5'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_5" name="form_drop_down_menu_item_5" value="<?php if ( !empty( $form_drop_down_menu_item_5 ) ) { echo $form_drop_down_menu_item_5; } else { echo '';} ?>" />
						<strong><?php _e( 'Drop-down select menu item 5. (Leave blank if not using.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_6">
						<?php $form_drop_down_menu_item_6 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_6'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_6" name="form_drop_down_menu_item_6" value="<?php if ( !empty( $form_drop_down_menu_item_6 ) ) { echo $form_drop_down_menu_item_6; } else { echo '';} ?>" />
						<strong><?php _e( 'Drop-down select menu item 6. (Leave blank if not using.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_7">
						<?php $form_drop_down_menu_item_7 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_7'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_7" name="form_drop_down_menu_item_7" value="<?php if ( !empty( $form_drop_down_menu_item_7 ) ) { echo $form_drop_down_menu_item_7; } else { echo '';} ?>" />
						<strong><?php _e( 'Drop-down select menu item 7. (Leave blank if not using.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_8">
						<?php $form_drop_down_menu_item_8 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_8'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_8" name="form_drop_down_menu_item_8" value="<?php if ( !empty( $form_drop_down_menu_item_8 ) ) { echo $form_drop_down_menu_item_8; } else { echo '';} ?>" />
						<strong><?php _e( 'Drop-down select menu item 8. (Leave blank if not using.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_9">
						<?php $form_drop_down_menu_item_9 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_9'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_9" name="form_drop_down_menu_item_9" value="<?php if ( !empty( $form_drop_down_menu_item_9 ) ) { echo $form_drop_down_menu_item_9; } else { echo '';} ?>" />
						<strong><?php _e( 'Drop-down select menu item 9. (Leave blank if not using.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_drop_down_menu_item_10">
						<?php $form_drop_down_menu_item_10 = trim(stripslashes($spamshield_options['form_drop_down_menu_item_10'])); ?>
						<input type="text" size="40" id="form_drop_down_menu_item_10" name="form_drop_down_menu_item_10" value="<?php if ( !empty( $form_drop_down_menu_item_10 ) ) { echo $form_drop_down_menu_item_10; } else { echo '';} ?>" />
						<strong><?php _e( 'Drop-down select menu item 10. (Leave blank if not using.)', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_width">
						<?php $form_message_width = trim(stripslashes($spamshield_options['form_message_width'])); ?>
						<input type="text" size="4" id="form_message_width" name="form_message_width" value="<?php if ( !empty( $form_message_width ) && $form_message_width >= 40 ) { echo $form_message_width; } else { echo '40';} ?>" />
						<strong><?php echo sprintf( __( '"Message" field width. (Minimum %s)', WPSS_PLUGIN_NAME ), '40' ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_height">
						<?php $form_message_height = trim(stripslashes($spamshield_options['form_message_height'])); ?>
						<input type="text" size="4" id="form_message_height" name="form_message_height" value="<?php if ( !empty( $form_message_height ) && $form_message_height >= 5 ) { echo $form_message_height; } elseif ( empty( $form_message_height ) ) { echo '10'; } else { echo '5';} ?>" />
						<strong><?php echo sprintf( __( '"Message" field height. (Minimum %1$s, Default %2$s)', WPSS_PLUGIN_NAME ), '5', '10' ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_min_length">
						<?php $form_message_min_length = trim(stripslashes($spamshield_options['form_message_min_length'])); ?>
						<input type="text" size="4" id="form_message_min_length" name="form_message_min_length" value="<?php if ( !empty( $form_message_min_length ) && $form_message_min_length >= 15 ) { echo $form_message_min_length; } elseif ( empty( $form_message_width ) ) { echo '25'; } else { echo '15';} ?>" />
						<strong><?php echo sprintf( __( 'Minimum message length (# of characters). (Minimum %1$s, Default %2$s)', WPSS_PLUGIN_NAME ), '15', '25' ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_message_recipient">
						<?php $form_message_recipient = trim(stripslashes($spamshield_options['form_message_recipient'])); ?>
						<input type="text" size="40" id="form_message_recipient" name="form_message_recipient" value="<?php if ( empty( $form_message_recipient ) ) { echo get_option('admin_email'); } else { echo $form_message_recipient; } ?>" />
						<strong><?php _e( 'Optional: Enter alternate form recipient. Default is blog admin email.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_response_thank_you_message">
						<?php 
						$form_response_thank_you_message = trim(stripslashes($spamshield_options['form_response_thank_you_message']));
						?>
						<?php _e( '<strong>Enter message to be displayed upon successful contact form submission.</strong><br/>Can be plain text, HTML, or an ad, etc.', WPSS_PLUGIN_NAME ); ?><br />
						<textarea id="form_response_thank_you_message" name="form_response_thank_you_message" cols="80" rows="3" /><?php if ( empty( $form_response_thank_you_message ) ) { _e( 'Your message was sent successfully. Thank you.', WPSS_PLUGIN_NAME ); } else { echo $form_response_thank_you_message; } ?></textarea><br/>&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_user_meta">
						<input type="checkbox" id="form_include_user_meta" name="form_include_user_meta" <?php echo ($spamshield_options['form_include_user_meta']==true?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Include user technical data in email.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'This adds some extra technical data to the end of the contact form email about the person submitting the form.<br />It includes: <strong>Browser / User Agent</strong>, <strong>Referrer</strong>, <strong>IP Address</strong>, <strong>Server</strong>, etc.<br />This is helpful for dealing with abusive or threatening comments. You can use the IP address provided to identify or block trolls from your site with whatever method you prefer.', WPSS_PLUGIN_NAME ); ?><br />&nbsp;
					</label>
					</li>
				</ul>
			</fieldset>
			<p class="submit">
			<input type="submit" name="submit_wpss_contact_options" value="<?php _e( 'Save Changes' ); ?>" class="button-primary" style="float:left;" />
			</p>
			</form>
			<p>&nbsp;</p>
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top"><?php _e( 'BACK TO TOP', WPSS_PLUGIN_NAME ); ?></a> ]</div></p>
			<p>&nbsp;</p>
			</div>
			<div style='width:797px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;clear:left;'>
  			<p><a name="wpss_let_others_know"><h3><?php _e( 'Let Others Know About WP-SpamShield', WPSS_PLUGIN_NAME ); ?></h3></a></p>
			<p><?php _e( '<strong>How does it feel to blog without being bombarded by automated comment spam?</strong> If you\'re happy with WP-SpamShield, there\'s a few things you can do to let others know:', WPSS_PLUGIN_NAME ); ?></p>
			<ul style="list-style-type:disc;padding-left:30px;">
				<li><a href="http://www.redsandmarketing.com/blog/wp-spamshield-wordpress-plugin-released/#comments" target="_blank" rel="external" ><?php _e( 'Leave a Comment' ); ?></a></li>
				<li><a href="<?php echo WPSS_WP_RATING_LINK; ?>" target="_blank" rel="external" ><?php _e( 'Give WP-SpamShield a good rating on WordPress.org.', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_LINK; ?>end-blog-spam/" target="_blank" rel="external" ><?php _e( 'Place a graphic link on your site.', WPSS_PLUGIN_NAME ); ?></a> <?php _e( 'Let others know how they can help end blog spam.', WPSS_PLUGIN_NAME ); ?> ( &lt/BLOGSPAM&gt; )</li>
			</ul>
			<p><a href="<?php echo WPSS_HOME_LINK; ?>" style="border-style:none;text-decoration:none;" target="_blank" rel="external" ><img src="<?php echo WPSS_PLUGIN_IMG_URL; ?>/end-blog-spam-button-01-black.png" alt="End Blog Spam! WP-SpamShield Comment Spam Protection for WordPress" width="140" height="66" style="border-style:none;text-decoration:none;margin-top:15px;margin-left:15px;" /></a></p>
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top"><?php _e( 'BACK TO TOP', WPSS_PLUGIN_NAME ); ?></a> ]</div></p>
			<p>&nbsp;</p>
			</div>
			<div style='width:797px;border-style:solid;border-width:1px;border-color:#003366;background-color:#DDEEFF;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;clear:left;'>
			<p><a name="wpss_download_plugin_documentation"><h3><?php echo spamshield_doc_txt(); ?></h3></a></p>
			<p><?php echo __( 'Plugin Homepage', WPSS_PLUGIN_NAME ) . ' / ' . spamshield_doc_txt(); ?>: <a href="<?php echo WPSS_HOME_LINK; ?>" target="_blank" rel="external" >WP-SpamShield</a><br />
			<?php _e( 'Leave a Comment' ); ?>: <a href="http://www.redsandmarketing.com/blog/wp-spamshield-wordpress-plugin-released/" target="_blank" rel="external" ><?php _e( 'WP-SpamShield Release Announcement Blog Post', WPSS_PLUGIN_NAME ); ?></a><br />
			<?php _e( 'WordPress.org Page', WPSS_PLUGIN_NAME ); ?>: <a href="<?php echo WPSS_WP_LINK; ?>" target="_blank" rel="external" >WP-SpamShield</a><br />
			<?php _e( 'Tech Support / Questions', WPSS_PLUGIN_NAME ); ?>: <a href="<?php echo WPSS_HOME_LINK; ?>support/" target="_blank" rel="external" ><?php _e( 'WP-SpamShield Support Page', WPSS_PLUGIN_NAME ); ?></a><br />
			<?php _e( 'End Blog Spam', WPSS_PLUGIN_NAME ); ?>: <a href="<?php echo WPSS_HOME_LINK; ?>end-blog-spam/" target="_blank" rel="external" ><?php _e( 'Let Others Know About WP-SpamShield', WPSS_PLUGIN_NAME ); ?>!</a><br />
			Twitter: <a href="http://twitter.com/WPSpamShield" target="_blank" rel="external" >@WPSpamShield</a><br />
			<?php 
			if ( spamshield_is_lang_en_us() ) {
				echo 'Need WordPress Consulting? <a href="http://www.redsandmarketing.com/web-design/wordpress-consulting/" target="_blank" rel="external" >We can help.</a><br />';
				}
			?>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="margin-top:10px;">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="DFMTNHJEPFFUL">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
			</p>
			<?php 
			// English only right now, until we get translations
			if ( spamshield_is_lang_en_us() ) {
				echo '<p><strong><a href="http://bit.ly/wp-spamshield-donate" target="_blank" rel="external" >' . __( 'Donate to WP-SpamShield', WPSS_PLUGIN_NAME ) . '</a></strong><br />' . __( 'WP-SpamShield is provided for free.', WPSS_PLUGIN_NAME ) . ' ' . __( 'If you like the plugin, consider a donation to help further its development.', WPSS_PLUGIN_NAME ) . '</p>';
				}
			?>
			
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top"><?php _e( 'BACK TO TOP', WPSS_PLUGIN_NAME ); ?></a> ]</div></p>
			<p>&nbsp;</p>
			</div>
			
			
			<?php
			// Recommended Partners - BEGIN - Added in 1.6.9
			if ( spamshield_is_lang_en_us() ) {
			?>
			
			<div style='width:797px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;clear:left;'>
			<p><h3>Recommended Partners</h3></p>
			<p>Each of these products or services are ones that we highly recommend, based on our experience and the experience of our clients. We do receive a commission if you purchase one of these, but these are all products and services we were already recommending because we believe in them. By purchasing from these providers, you get quality and you help support the further development of WP-SpamShield.</p></div>
				

			<div style="width:375px;height:280px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;clear:left;">
			<p><strong><a href="http://bit.ly/RSM_Hostgator" target="_blank" rel="external" >Hostgator Website Hosting</a></strong></p>
			<p><strong>Affordable, high quality web hosting. Great for WordPress and a variety of web applications.</strong></p>
			<p>Hostgator has variety of affordable plans, reliable service, and customer support. Even on shared hosting, you get fast servers that are well-configured. Hostgator provides great balance of value and quality, which is why we recommend them.</p>
			<p><a href="http://bit.ly/RSM_Hostgator"target="_blank" rel="external" >Click here to find out more. >></a></p>
			</div>
			
			<div style="width:375px;height:280px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;">
			<p><strong><a href="http://bit.ly/RSM_Level10" target="_blank" rel="external" >Level10 Domains</a></strong></p>
			<p><strong>Inexpensive web domains with an easy to use admin dashboard.</strong></p>
			<p>Level10 Domains offers some of the best prices you'll find on web domain purchasing. The dashboard provides an easy way to manage your domains.</p>
			<p><a href="http://bit.ly/RSM_Level10" target="_blank" rel="external" >Click here to find out more. >></a></p>
			</div>
		
			<div style="width:375px;height:280px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;clear:left;">
			<p><strong><a href="http://bit.ly/RSM_Genesis" target="_blank" rel="external" >Genesis WordPress Framework</a></strong></p>
			<p><strong>Other themes and frameworks have nothing on Genesis. Optimized for site speed and SEO.</strong></p>

			<p>Simply put, the Genesis framework is one of the best ways to design and build a WordPress site. Built-in SEO and optimized for speed. Create just about any kind of design with child themes.</p>
			<p><a href="http://bit.ly/RSM_Genesis" target="_blank" rel="external" >Click here to find out more. >></a></p>
			</div>
			
			<div style="width:375px;height:280px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:15px;margin-right:15px;float:left;">
			<p><strong><a href="http://bit.ly/RSM_AIOSEOP" target="_blank" rel="external" >All in One SEO Pack Pro</a></strong></p>
			<p><strong>The best way to manage the code-related SEO for your WordPress site.</strong></p>
			<p>Save time and effort optimizing the code of your WordPress site with All in One SEO Pack. One of the top rated, and most downloaded plugins on WordPress.org, this time-saving plugin is incredibly valuable. The pro version provides powerful features not available in the free version.</p>
			<p><a href="http://bit.ly/RSM_AIOSEOP" target="_blank" rel="external" >Click here to find out more. >></a></p>
			</div>
			
			<?php
				}
			// Recommended Partners - END - Added in 1.6.9
			?>
			
			<p style="clear:both;">&nbsp;</p>
			<p style="clear:both;"><em><?php 
			echo 'Version '.WPSS_VERSION; 
			if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) {
				$wpss_proc_data = get_option( 'spamshield_procdat' );
				if ( !isset( $wpss_proc_data['avg2_wpss_proc_time'] ) && isset( $wpss_proc_data['avg_wpss_proc_time'] ) ) { 
					$wpss_proc_data['avg2_wpss_proc_time'] = $wpss_proc_data['avg_wpss_proc_time']; 
					}
				elseif ( !isset( $wpss_proc_data['avg2_wpss_proc_time'] ) ) { $wpss_proc_data['avg2_wpss_proc_time'] = 0; }
				$wpss_proc_data_avg2_wpss_proc_time_disp = spamshield_number_format( $wpss_proc_data['avg2_wpss_proc_time'], 6 );
				echo "<br />\n".'Avg WPSS Proc Time: '.$wpss_proc_data_avg2_wpss_proc_time_disp.' seconds';
				}
			?></em></p>

			<p><div style="float:right;clear:both;font-size:12px;">[ <a href="#wpss_top"><?php _e( 'BACK TO TOP', WPSS_PLUGIN_NAME ); ?></a> ]</div></p>
			<p>&nbsp;</p>
			</div>
			<?php
			}
		
		function spamshield_check_version() {
			if ( current_user_can('manage_network') ) {
				// Check for pending admin notices
				$admin_notices = get_option('spamshield_admin_notices');
				if ( !empty( $admin_notices ) ) {
					add_action( 'network_admin_notices', 'spamshield_admin_notices' );
					}
				// Make sure not network activated
				if ( is_plugin_active_for_network( WPSS_PLUGIN_BASENAME ) ) {
					deactivate_plugins( WPSS_PLUGIN_BASENAME, true, true );
					$notice_text = __( 'Plugin deactivated. WP-SpamShield is not available for network activation.', WPSS_PLUGIN_NAME );
					$new_admin_notice = array( 'style' => 'error', 'notice' => $notice_text );
					update_option( 'spamshield_admin_notices', $new_admin_notice );
					add_action( 'network_admin_notices', 'spamshield_admin_notices' );
					spamshield_append_log_data( "\n".$notice_text.' Line: '.__LINE__, false );
					return false;
					}
				}
			elseif ( current_user_can('manage_options') ) {
				// Check if plugin has been upgraded
				spamshield_upgrade_check();
				// Check for pending admin notices
				$admin_notices = get_option('spamshield_admin_notices');
				if ( !empty( $admin_notices ) ) {
					add_action( 'admin_notices', 'spamshield_admin_notices' );
					}
				// Make sure user has minimum required WordPress version, in order to prevent issues
				//if ( version_compare( RSMP_WP_VERSION, WPSS_REQUIRED_WP_VERSION, '<' ) ) {
				$wpss_wp_version = RSMP_WP_VERSION;
				if ( !empty( $wpss_wp_version ) && version_compare( $wpss_wp_version, WPSS_REQUIRED_WP_VERSION, '<' ) ) {
					deactivate_plugins( WPSS_PLUGIN_BASENAME );
					$notice_text = sprintf( __( 'Plugin deactivated. WordPress Version %s required. Please upgrade WordPress to the latest version.', WPSS_PLUGIN_NAME ), WPSS_REQUIRED_WP_VERSION );
					$new_admin_notice = array( 'style' => 'error', 'notice' => $notice_text );
					update_option( 'spamshield_admin_notices', $new_admin_notice );
					add_action( 'admin_notices', 'spamshield_admin_notices' );
					spamshield_append_log_data( "\n".$notice_text.' Line: '.__LINE__, false );
					return false;
					}
				// Security Check - See if (extremely) old version of plugin still active
				$old_version = 'wp-spamfree/wp-spamfree.php';
				$old_version_active = spamshield_is_plugin_active( $old_version );
				if ( !empty( $old_version_active ) ) {
					// Not safe to keep old version active due to unpatched security hole(s), broken PHP, and lack of maintenance
					// For security reasons, deactivate old version
					deactivate_plugins( $old_version );
					// Clean up database
					delete_option('wp_spamfree_version'); delete_option('spamfree_count'); delete_option('spamfree_options');
					// Good to go!
					// Since WP-SpamShield takes over 100% of old version's responsibilities, there is no loss of functionality, only improvements.
					// Site speed will improve and server load will now drop dramatically.
					}
				// Compatibility Checks
				spamshield_admin_jp_fix();
				}
			}

		// Class Admin Functions - END

		function spamshield_insert_head_js() {
			// The JavaScript is NOT enqueued on purpose. It's not coded improperly. This is done exactly like it is for a very good reason.
			// It needs to not be altered or messed with by any other plugin.
			// The JS file is really a dynamically generated script that uses both server-side and client-side code so it requires the PHP functionality.
			// "But couldn't that be done by..." Stop right there...No, it cannot.

			if ( ( !is_admin() && is_user_logged_in() ) || !is_user_logged_in() ) {

				// Following was in JS, but since this is immediately before that code is executed, placed here
				// May be issue with caching though, but minor
				update_option( 'ak_count_pre', get_option('akismet_spam_count') );

				$wpSpamShieldVerJS=' v'.WPSS_VERSION;
				echo "\n";
				echo "<script type='text/javascript' src='".WPSS_PLUGIN_JS_URL."/jscripts.php'></script> "."\n";
				if ( !empty( $_SESSION['wpss_user_ip_init_'.RSMP_HASH] ) ) {
					$_SESSION['wpss_user_ip_init_'.RSMP_HASH] = $_SERVER['REMOTE_ADDR'];
					}
				}
			}

		
		}
	}

// wpSpamShield CLASS - END


// Promo Link Functions - BEGIN
function spamshield_promo_text( $int ) {
	// Text to display in counter widget and promo links.
	// Also used in link title attribute.
	$promo_txt = array(
		// Showing key numbers to avoid having to count manually when implementing
		0 	=> __( 'SPAM BLOCKED', WPSS_PLUGIN_NAME ),
		1	=> __( 'BY WP-SPAMSHIELD', WPSS_PLUGIN_NAME ),
		2	=> 'WP-SpamShield ' . __( 'Spam Plugin for WordPress', WPSS_PLUGIN_NAME ),
		3	=> 'WP-SpamShield ' . __( 'WordPress Anti-Spam Plugin', WPSS_PLUGIN_NAME ),
		4	=> 'WP-SpamShield - ' . __( 'WordPress Spam Filter', WPSS_PLUGIN_NAME ),
		5	=> 'WP-SpamShield - ' . __( 'WordPress Anti-Spam', WPSS_PLUGIN_NAME ),
		6	=> __( 'Spam Blocked by WP-SpamShield', WPSS_PLUGIN_NAME ) . ' - ' . __( 'WordPress Anti-Spam Plugin', WPSS_PLUGIN_NAME ),
		7	=> __( 'Spam Blocked by WP-SpamShield', WPSS_PLUGIN_NAME ) . ' - ' . __( 'WordPress Spam Plugin', WPSS_PLUGIN_NAME ),
		8	=> __( 'Spam Blocked by WP-SpamShield', WPSS_PLUGIN_NAME ) . ' - ' . __( 'WordPress Spam Blocker', WPSS_PLUGIN_NAME ),
		9	=> __( 'Protected by WP-SpamShield', WPSS_PLUGIN_NAME ) . ' - ' . __( 'WordPress Spam Filter', WPSS_PLUGIN_NAME ),
		10	=> __( 'Protected by WP-SpamShield', WPSS_PLUGIN_NAME ) . ' - ' . __( 'WordPress Anti Spam Plugin', WPSS_PLUGIN_NAME ),
		11	=> 'WP-SpamShield - ' . __( 'WordPress Anti-Spam Plugin', WPSS_PLUGIN_NAME ),
		12	=> 'WP-SpamShield - ' . __( 'WordPress Anti Spam Plugin', WPSS_PLUGIN_NAME ),
		);
	return $promo_txt[$int];
	}

function spamshield_contact_promo_link( $int ) {
	// Promo link for contact page if user opts in
	$contact_form_title = __( 'WP-SpamShield Contact Form for WordPress', WPSS_PLUGIN_NAME );
	$contact_form_txt = __( 'Contact Form', WPSS_PLUGIN_NAME );
	$url = spamshield_get_promo_link_url();
	if ( empty( $url ) ) { $url = WPSS_HOME_LINK; }
	$link_template = '<a href="'.$url.'" title="'.$contact_form_title.'" >X1X2X</a>';
	$promo_link_data = array(
		// Showing key numbers to avoid having to count manually when implementing
		0	=> __( 'Contact Form Powered by WP-SpamShield', WPSS_PLUGIN_NAME ) . '|' . __( 'Contact Form', WPSS_PLUGIN_NAME ),
		1	=> __( 'Powered by WP-SpamShield Contact Form', WPSS_PLUGIN_NAME ) . '|' . __( 'WP-SpamShield Contact Form', WPSS_PLUGIN_NAME ),
		2	=> __( 'Contact Form Powered by WP-SpamShield', WPSS_PLUGIN_NAME ) . '|' . 'WP-SpamShield',
		);
	$promo_link_arr = explode( '|', $promo_link_data[$int] );
	if ( !spamshield_is_lang_en_us() ) {
		$promo_link_ahref = str_replace( 'X1X2X', $promo_link_arr[0], $link_template );
		$promo_link_phrase = $promo_link_ahref;
		}
	else {
		$promo_link_ahref = str_replace( 'X1X2X', $promo_link_arr[1], $link_template );
		$promo_link_phrase = str_replace( $promo_link_arr[1], $promo_link_ahref, $promo_link_arr[0] );
		}
	$promo_link_html = '<p style="font-size:9px;clear:both;">'.$promo_link_phrase.'</p>';
	return $promo_link_html;
	}
	
function spamshield_comment_promo_link( $int ) {
	// Promo link for comments box if user opts in
	$comment_title = __( 'WP-SpamShield WordPress Anti-Spam Plugin', WPSS_PLUGIN_NAME );
	$url = spamshield_get_promo_link_url();
	if ( empty( $url ) ) { $url = WPSS_HOME_LINK; }
	$link_template = '<a href="'.$url.'" title="'.$comment_title.'" >X1X2X</a>';
	$promo_link_data = array(
		// Showing key numbers to avoid having to count manually when implementing
		0	=> __( 'WordPress Anti-Spam by WP-SpamShield', WPSS_PLUGIN_NAME ) . '|' . __( 'WordPress Anti-Spam', WPSS_PLUGIN_NAME ),
		1	=> __( 'WordPress Anti-Spam by WP-SpamShield', WPSS_PLUGIN_NAME ) . '|' . 'WP-SpamShield',
		2	=> __( 'WordPress Anti Spam by WP-SpamShield', WPSS_PLUGIN_NAME ) . '|' . __( 'WordPress Anti Spam', WPSS_PLUGIN_NAME ),
		3	=> __( 'Comments Protected by WP-SpamShield Anti-Spam', WPSS_PLUGIN_NAME ) . '|' . __( 'WP-SpamShield Anti-Spam', WPSS_PLUGIN_NAME ),
		4	=> __( 'Comments Protected by WP-SpamShield Spam Plugin', WPSS_PLUGIN_NAME ) . '|' . __( 'WP-SpamShield Spam Plugin', WPSS_PLUGIN_NAME ),
		5	=> __( 'Comments Protected by WP-SpamShield Spam Filter', WPSS_PLUGIN_NAME ) . '|' . __( 'WP-SpamShield Spam Filter', WPSS_PLUGIN_NAME ),
		6	=> __( 'Comments Protected by WP-SpamShield Spam Blocker', WPSS_PLUGIN_NAME ) . '|' . __( 'WP-SpamShield Spam Blocker', WPSS_PLUGIN_NAME ),
		7	=> __( 'Comments Protected by WP-SpamShield for WordPress', WPSS_PLUGIN_NAME ) . '|' . __( 'WP-SpamShield for WordPress', WPSS_PLUGIN_NAME ),
		8	=> __( 'Spam Blocking by WP-SpamShield', WPSS_PLUGIN_NAME ) . '|' . __( 'Spam Blocking', WPSS_PLUGIN_NAME ),
		9	=> __( 'Anti-Spam by WP-SpamShield', WPSS_PLUGIN_NAME ) . '|' . __( 'Anti-Spam', WPSS_PLUGIN_NAME ),
		10	=> __( 'Comment Spam Blocking by WP-SpamShield', WPSS_PLUGIN_NAME ) . '|' . __( 'Comment Spam Blocking', WPSS_PLUGIN_NAME ),
		);
	$promo_link_arr = explode( '|', $promo_link_data[$int] );
	if ( !spamshield_is_lang_en_us() ) {
		$promo_link_ahref = str_replace( 'X1X2X', $promo_link_arr[0], $link_template );
		$promo_link_phrase = $promo_link_ahref;
		}
	else {
		$promo_link_ahref = str_replace( 'X1X2X', $promo_link_arr[1], $link_template );
		$promo_link_phrase = str_replace( $promo_link_arr[1], $promo_link_ahref, $promo_link_arr[0] );
		}
	$promo_link_html = '<p style="font-size:9px;clear:both;">'.$promo_link_phrase.'</p>';
	return $promo_link_html;
	}

function spamshield_get_promo_link_url() {
	// In the plugin promo links, sometimes use plugin homepage link, sometime use WP.org link to make sure both get visited
	// 4 to 1 plugin homepage to WP.org (which more people link to anyway)
	$sip5c = '0';
	$sip5c = substr(RSMP_SERVER_ADDR, 4, 1); // Server IP 5th Char
	$gplu_code = array( '0' => 0, '1' => 1, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 1, '.' => 0 );
	$urls = array( WPSS_HOME_LINK, WPSS_WP_LINK );
	if ( preg_match( "~^[0-9\.]$~", $sip5c ) ) { $k = $gplu_code[$sip5c]; } else { $k = 0; }
	$url = $urls[$k];
	if ( empty( $url ) ) { $url = WPSS_HOME_LINK; }
	return $url;
	}
// Promo Link Functions - END


// Instantiate the class
if (class_exists('wpSpamShield')) {
	$wpSpamShield = new wpSpamShield();
	}

// PLUGIN - END
?>