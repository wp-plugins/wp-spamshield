<?php
/*
Plugin Name: WP-SpamShield
Plugin URI: http://www.redsandmarketing.com/plugins/wp-spamshield/
Description: An extremely powerful and user-friendly all-in-one anti-spam plugin that <strong>eliminates comment spam, trackback spam, contact form spam, and registration spam</strong>. No CAPTCHA's, challenge questions, or other inconvenience to website visitors. Enjoy running a WordPress site without spam! Includes a spam-blocking contact form feature.
Author: Scott Allen
Version: 1.8.9.6
Author URI: http://www.redsandmarketing.com/
Text Domain: wp-spamshield
License: GPLv2
*/

/*  Copyright 2014-2015 Scott Allen (email : wpspamshield [at] redsandmarketing [dot] com)

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


/* PLUGIN - BEGIN */

/***
* Note to any other PHP developers reading this:
* My use of the closing curly braces "}" is a little funky in that I indent them, I know. IMO it's easier to debug. Just know that it's on purpose even though it's not standard. One of my programming quirks, and just how I roll. :)
***/

/* Make sure plugin remains secure if called directly */
if ( !defined( 'ABSPATH' ) ) {
	if ( !headers_sent() ) { header('HTTP/1.1 403 Forbidden'); }
	die( 'ERROR: This plugin requires WordPress and will not function if called directly.' );
	}

define( 'WPSS_VERSION', '1.8.9.6' );
define( 'WPSS_REQUIRED_WP_VERSION', '3.9' );
define( 'WPSS_REQUIRED_PHP_VERSION', '5.3' );
/***
* Setting important URL and PATH constants so the plugin can find things
* Constants prefixed with 'RSMP_' are shared with other RSM Plugins for efficiency.
***/
if ( !defined( 'WPSS_DEBUG' ) ) 				{ define( 'WPSS_DEBUG', FALSE ); } /* Do not change value unless developer asks you to - for debugging only. Change in wp-config.php. */
if ( !defined( 'WPSS_EDGE' ) ) 					{ define( 'WPSS_EDGE', FALSE ); }
if ( !defined( 'WPSS_MEMORY_LIMIT' ) ) 			{ define( 'WPSS_MEMORY_LIMIT', '128M' ); }
if ( !defined( 'RSMP_SITE_URL' ) ) 				{ define( 'RSMP_SITE_URL', untrailingslashit( site_url() ) ); }
if ( !defined( 'RSMP_SITE_DOMAIN' ) ) 			{ define( 'RSMP_SITE_DOMAIN', spamshield_get_domain( RSMP_SITE_URL ) ); }
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
if ( !defined( 'WPSS_PLUGIN_CSS_URL' ) ) 		{ define( 'WPSS_PLUGIN_CSS_URL', WPSS_PLUGIN_URL . '/css' );	}
if ( !defined( 'WPSS_PLUGIN_DATA_URL' ) ) 		{ define( 'WPSS_PLUGIN_DATA_URL', WPSS_PLUGIN_URL . '/data' ); }
if ( !defined( 'WPSS_PLUGIN_IMG_URL' ) ) 		{ define( 'WPSS_PLUGIN_IMG_URL', WPSS_PLUGIN_URL . '/img' ); }
if ( !defined( 'WPSS_PLUGIN_JS_URL' ) ) 		{ define( 'WPSS_PLUGIN_JS_URL', WPSS_PLUGIN_URL . '/js' );	}
if ( !defined( 'WPSS_PLUGIN_PATH' ) ) 			{ define( 'WPSS_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) ); }
if ( !defined( 'WPSS_PLUGIN_FILE_PATH' ) ) 		{ define( 'WPSS_PLUGIN_FILE_PATH', WPSS_PLUGIN_PATH.'/'.WPSS_PLUGIN_FILE_BASENAME ); }
if ( !defined( 'WPSS_PLUGIN_COUNTER_PATH' ) ) 	{ define( 'WPSS_PLUGIN_COUNTER_PATH', WPSS_PLUGIN_PATH . '/counter' ); }
if ( !defined( 'WPSS_PLUGIN_CSS_PATH' ) ) 		{ define( 'WPSS_PLUGIN_CSS_PATH', WPSS_PLUGIN_PATH . '/css' ); }
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
if ( !defined( 'WPSS_REF2XJS' ) ) 				{ define( 'WPSS_REF2XJS', 'r3f5x9JS' ); }
if ( !defined( 'WPSS_JSONST' ) ) 				{ define( 'WPSS_JSONST', 'JS04X7' ); }
if ( !defined( 'RSMP_DEBUG_SERVER_NAME' ) ) 	{ define( 'RSMP_DEBUG_SERVER_NAME', '.redsandmarketing.com' ); }
if ( !defined( 'RSMP_DEBUG_SERVER_NAME_REV' ) ) { define( 'RSMP_DEBUG_SERVER_NAME_REV', strrev( RSMP_DEBUG_SERVER_NAME ) ); }
if ( !defined( 'RSMP_RSM_URL' ) ) 				{ define( 'RSMP_RSM_URL', 'http://www.redsandmarketing.com/' ); }
if ( !defined( 'WPSS_HOME_URL' ) ) 				{ define( 'WPSS_HOME_URL', RSMP_RSM_URL.'plugins/wp-spamshield/' ); }
if ( !defined( 'WPSS_WP_URL' ) ) 				{ define( 'WPSS_WP_URL', 'http://wordpress.org/extend/plugins/wp-spamshield/' ); }
if ( !defined( 'WPSS_WP_RATING_URL' ) ) 		{ define( 'WPSS_WP_RATING_URL', 'http://bit.ly/wp-spamshield-rate' ); }
if ( !defined( 'RSMP_SERVER_SOFTWARE' ) ) 		{ define( 'RSMP_SERVER_SOFTWARE', $_SERVER['SERVER_SOFTWARE'] ); }
if ( !defined( 'RSMP_PHP_UNAME' ) ) 			{ define( 'RSMP_PHP_UNAME', @php_uname() ); }
if ( !defined( 'RSMP_PHP_VERSION' ) ) 			{ define( 'RSMP_PHP_VERSION', PHP_VERSION ); }
if ( !defined( 'RSMP_PHP_MEM_LIMIT' ) ) {
	$wpss_php_memory_limit = spamshield_format_bytes( ini_get( 'memory_limit' ) );
	define( 'RSMP_PHP_MEM_LIMIT', $wpss_php_memory_limit );
	}
if ( !defined( 'RSMP_WP_VERSION' ) ) {
	global $wp_version;
	define( 'RSMP_WP_VERSION', $wp_version );
	}
if ( !defined( 'RSMP_USER_AGENT' ) ) {
	$wpss_user_agent = 'WP-SpamShield/'.WPSS_VERSION.' (WordPress/'.RSMP_WP_VERSION.') PHP/'.RSMP_PHP_VERSION.' ('.RSMP_SERVER_SOFTWARE.')';
	define( 'RSMP_USER_AGENT', $wpss_user_agent );
	}
/* INCLUDE POPULAR CACHE PLUGINS HERE (12) */
$popular_cache_plugins_default = array ( 'wp-super-cache', 'w3-total-cache', 'quick-cache', 'hyper-cache', 'wp-fastest-cache', 'db-cache-reloaded-fix', 'cachify', 'db-cache-reloaded', 'hyper-cache-extended', 'wp-fast-cache', 'lite-cache', 'gator-cache' );
if ( !defined( 'WPSS_POPULAR_CACHE_PLUGINS' ) ) {
	/* Popular cache plugins - convert from array and store in constant */
	define( 'WPSS_POPULAR_CACHE_PLUGINS', serialize( $popular_cache_plugins_default ) );
	}
/* SET THE DEFAULT CONSTANT VALUES HERE */
$spamshield_default = array (
	'block_all_trackbacks' 				=> 0,
	'block_all_pingbacks' 				=> 0,
	'comment_logging'					=> 0,
	'comment_logging_start_date'		=> 0,
	'comment_logging_all'				=> 0,
	'enhanced_comment_blacklist'		=> 0,
	'enable_whitelist'					=> 0,
	'allow_proxy_users'					=> 1,
	'hide_extra_data'					=> 0,
	'js_head_disable'					=> 0,
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

spamshield_set_memory();

/* Includes - BEGIN */
require_once( WPSS_PLUGIN_INCL_PATH.'/blacklists.php' );
require_once( WPSS_PLUGIN_INCL_PATH.'/class.wpss-widget.php' );
/* Includes - END */

/* Standard Functions - BEGIN */

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

function spamshield_set_memory() {
	/***
	* Boost memory limits
	* WordPress' default is 40M, but it requires at least 64M to run smoothly on many sites. 128M+ is better.
	***/
	if ( function_exists( 'memory_get_usage' ) ) {
		$current_limit		= @ini_get( 'memory_limit' );
		$current_limit_int	= intval( $current_limit );
		if ( FALSE !== strpos( $current_limit, 'G' ) ) { $current_limit_int *= 1024; }
		$wpss_limit_int = intval( WPSS_MEMORY_LIMIT );
		if ( FALSE !== strpos( WPSS_MEMORY_LIMIT, 'G' ) ) { $wpss_limit_int *= 1024; }
		if ( -1 != $current_limit && ( -1 == WPSS_MEMORY_LIMIT || $current_limit_int < $wpss_limit_int ) ) {
			@ini_set( 'memory_limit', WPSS_MEMORY_LIMIT );
			}
		}
	}

function spamshield_count_words($string) {
	$string = trim($string);
	$char_count = spamshield_strlen($string);
	if ( empty( $string ) || $char_count == 0 ) { $num_words = 0; } else { $exploded_string = preg_split( "~\s+~", $string ); $num_words = count($exploded_string);	}
	return $num_words;
	}

function spamshield_strlen($string) {
	/***
	* Use this function instead of mb_strlen because some servers (often IIS) have mb_ functions disabled by default
	* BUT mb_strlen is superior to strlen, so use it whenever possible
	***/
	if ( function_exists( 'mb_strlen' ) ) { $num_chars = mb_strlen($string, 'UTF-8'); } else { $num_chars = strlen($string); }
	return $num_chars;
	}

function spamshield_casetrans( $type, $string ) {
	/***
	* Convert case using multibyte version if available, if not, use defaults
	* Added 1.8.4
	***/
	switch ($type) {
		case 'upper':
			if ( function_exists( 'mb_strtoupper' ) ) { return mb_strtoupper($string, 'UTF-8'); } else { return strtoupper($string); }
		case 'lower':
			if ( function_exists( 'mb_strtolower' ) ) { return mb_strtolower($string, 'UTF-8'); } else { return strtolower($string); }
		case 'ucfirst':
			if ( function_exists( 'mb_strtoupper' ) && function_exists( 'mb_substr' ) ) { return mb_strtoupper(mb_substr($string, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($string, 1, NULL, 'UTF-8'); } else { return ucfirst($string); }
		case 'ucwords':
			if ( function_exists( 'mb_convert_case' ) ) { return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8'); } else { return ucwords($string); }
			/***
			* Note differences in results between ucwords() and this. 
			* ucwords() will capitalize first characters without altering other characters, whereas this will lowercase everything, but capitalize the first character of each word.
			* This works better for our purposes, but be aware of differences.
			***/
		default:
			return $string;
		}
	}

function spamshield_substr_count( $haystack, $needle, $offset = 0, $length = NULL ) {
	/* Has error correction built in */
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
	/* Prep for use in Regex, this plugin uses '~' as a delimiter exclusively, can be changed */
	$regex_string = preg_quote( $string, '~' );
	$regex_string = preg_replace( "~\s+~", "\s+", $regex_string );
	return $regex_string;
	}

function spamshield_md5( $string ) {
	/***
	* Use this function instead of hash for compatibility
	* BUT hash is faster than md5, so use it whenever possible
	***/
	if ( function_exists( 'hash' ) ) { $hash = hash( 'md5', $string ); } else { $hash = md5( $string );	}
	return $hash;
	}

function spamshield_get_wpss_eid( $args ) {
	/***
	* Creates unique temporary IDs from hash of data for both contact forms and comments.
	* Added 1.7.7
	* $type: 'comment','contact'
	* $args: name, email, url, content
	* 'eid' - Entity ID; 'ecid' - Entity Content ID
	***/
	$wpsseid = array( 'eid' => '', 'ecid' => '');
	$wpsseid_args_data_str = implode( '', $args );
	$wpsseid['eid'] = spamshield_md5( $wpsseid_args_data_str );
	$wpsseid['ecid'] = spamshield_md5( $args['content'] );
	return $wpsseid;
	}

function spamshield_create_nonce( $action, $name = '_wpss_nonce' ) {
	/***
	* Creates a different nonce system than WordPress.
	* 24 hours or 1 time use. 
	* Difference vs WP nonces: Nonce must exist in database, is not tied to a user ID, and is truly 1 time use.
	* WP nonces don't work for every application. If a comment is posted, and a notification email is sent to admin with link to blacklist the IP, this works better.
	***/
	$i = wp_nonce_tick();
	$timenow = time();
	$nonce = substr( spamshield_md5( $i . $action . $name . RSMP_HASH . $timenow ), -12, 10 );
	$spamshield_nonces = get_option( 'spamshield_nonces' );
	if ( empty( $spamshield_nonces ) ) { $spamshield_nonces = array(); }
	else {
		foreach( $spamshield_nonces as $i => $n ) {
			if ( $n['expire'] <= $timenow ) {
				unset( $spamshield_nonces[$i] );
				}
			}
		}
	$expire = $timenow + 86400; /* 24 hours */
	$spamshield_nonces[] = array( 'nonce' => $nonce, 'action' => $action, 'name' => $name, 'expire' => $expire );
	update_option( 'spamshield_nonces', $spamshield_nonces );
	return $nonce;
	}

function spamshield_verify_nonce( $value, $action, $name = '_wpss_nonce' ) {
	/***
	* Verify a WP-SpamShield nonce.
	* $value 	= value of nonce you're testing for
	* $action 	= descriptive string used internally for what you're trying to do
	* $name 	= identifier of nonce
	***/
	$nonce_valid = FALSE;
	$timenow = time();
	$spamshield_nonces = get_option( 'spamshield_nonces' );
	if ( empty( $spamshield_nonces ) ) { return FALSE; }
	foreach( $spamshield_nonces as $i => $n ) {
		if ( $n['nonce'] == $value && $n['action'] == $action && $n['expire'] > $timenow ) {
			unset( $spamshield_nonces[$i] );
			$nonce_valid = TRUE;
			}
		elseif ( $n['expire'] <= $timenow ) {
			unset( $spamshield_nonces[$i] );
			}
		}
	update_option( 'spamshield_nonces', $spamshield_nonces );
	return $nonce_valid;
	}

function spamshield_purge_nonces() {
	/***
	* Purge expired nonces. Keep the nonce cache clean.
	***/
	$timenow = time();
	$spamshield_nonces = get_option( 'spamshield_nonces' );
	if ( empty( $spamshield_nonces ) ) { return FALSE; }
	foreach( $spamshield_nonces as $i => $n ) {
		if ( $n['expire'] <= $timenow ) {
			unset( $spamshield_nonces[$i] );
			}
		}
	update_option( 'spamshield_nonces', $spamshield_nonces );
	return TRUE;
	}

function spamshield_microtime() {
	$mtime = microtime( TRUE );
	return $mtime;
	}

function spamshield_timer( $start = NULL, $end = NULL, $show_seconds = FALSE, $precision = 8, $no_format = FALSE ) {
	/***
	* $precision will default to 8 but can be set to anything - 1,2,3,4,5,6,etc.
	* Use $no_format when clean numbers are needed for calculations. International formatting throws a wrench into things.
	***/
	if ( empty( $start ) || empty( $end ) ) { $start = $end = 0; }
	$total_time = $end - $start;
	if ( empty( $no_format ) ) {
		$total_time_for = spamshield_number_format( $total_time, $precision );
		if ( !empty( $show_seconds ) ) { $total_time_for .= ' seconds'; }
		}
	else {
		$total_time_for = number_format( $total_time, $precision );
		}
	return $total_time_for;
	}

function spamshield_number_format( $number, $precision = NULL ) {
	/* $precision will default to NULL but can be set to anything - 1,2,3,4,5,6,etc. */
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

function spamshield_wp_memory_used() {
	$wp_memory_used = 0;
	if ( function_exists( 'memory_get_usage' ) ) { $wp_memory_used = spamshield_format_bytes( memory_get_usage() ); }
    return $wp_memory_used;
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
	/***
	* Get domain from URL
	* Filter URLs with nothing after http
	***/
	if ( empty( $url ) || preg_match( "~^https?\:*/*$~i", $url ) ) { return ''; }
	/* Fix poorly formed URLs so as not to throw errors when parsing */
	$url = spamshield_fix_url($url);
	/* NOW start parsing */
	$parsed = @parse_url($url);
	/* Filter URLs with no domain */
	if ( empty( $parsed['host'] ) ) { return ''; }
	return spamshield_casetrans('lower',$parsed['host']);
	}

function spamshield_get_query_string($url) {
	/***
	* Get query string from URL
	* Filter URLs with nothing after http
	***/
	if ( empty( $url ) || preg_match( "~^https?\:*/*$~i", $url ) ) { return ''; }
	/* Fix poorly formed URLs so as not to throw errors when parsing */
	$url = spamshield_fix_url($url);
	/* NOW start parsing */
	$parsed = @parse_url($url);
	/* Filter URLs with no query string */
	if ( empty( $parsed['query'] ) ) { return ''; }
	$query_str = $parsed['query'];
	return $query_str;
	}

function spamshield_get_email_domain($email) {
	/* Get domain from email address */
	if ( empty( $email ) ) { return ''; }
	$email_elements = explode( '@', $email );
	$domain = $email_elements[1];
	return $domain;
	}

function spamshield_parse_links( $haystack, $type = 'url' ) {
	/***
	* Parse a body of content for links - extracts URLs and Anchor Text
	* $type: 'url' for URLs, 'domain' for just Domains, 'url_at' for URLs from Anchor Text Links only, 'anchor_text' for Anchor Text
	* Returns an array
	***/
	$parse_links_regex = "~(<\s*a\s+[a-z0-9\-_\.\?\='\"\:\(\)\{\}\s]*\s*href|\[(url|link))\s*\=\s*['\"]?\s*(https?\://[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)\s*['\"]?\s*[a-z0-9\-_\.\?\='\"\:;\(\)\{\}\s]*\s*(>|\])([a-z0-9àáâãäåçèéêëìíîïñńņňòóôõöùúûü\-_\/\.\?\&\=\~\@\%\+\#\:;\!,'\(\)\{\}\s]*)(<|\[)\s*\/\s*a\s*(>|(url|link)\])~iu";
	$search_http_regex ="~(?:^|\s+)(https?\://[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)(?:$|\s+)~iu";
	preg_match_all( $parse_links_regex, $haystack, $matches_links, PREG_PATTERN_ORDER );
	$parsed_links_matches 			= $matches_links[3]; /* Array containing URLs parsed from Anchor Text Links in haystack text */
	$parsed_anchortxt_matches		= $matches_links[5]; /* Array containing Anchor Text parsed from Anchor Text Links in haystack text */
	if ( $type == 'url' || $type == 'domain' ) {
		$url_haystack = preg_replace( "~\s~", ' - ', $haystack ); /* Workaround Added 1.3.8 */
		preg_match_all( $search_http_regex, $url_haystack, $matches_http, PREG_PATTERN_ORDER );
		$parsed_http_matches 		= $matches_http[1]; /* Array containing URLs parsed from haystack text */
		$parsed_urls_all_raw 		= array_merge( $parsed_links_matches, $parsed_http_matches );
		$parsed_urls_all			= array_unique( $parsed_urls_all_raw );
		if ( $type == 'url' ) {
			$results = $parsed_urls_all;
			}
		elseif ( $type == 'domain' ) {
			$parsed_urls_all_domains = array();
			foreach( $parsed_urls_all as $u => $url_raw ) {
				$url = spamshield_casetrans( 'lower', trim( stripslashes( $url_raw ) ) );
				if ( empty( $url ) ) { continue; }
				$domain = spamshield_get_domain($url);
				if ( !in_array( $domain, $parsed_urls_all_domains, TRUE ) ) {
					$parsed_urls_all_domains[] = $domain;
					}
				}
			$results = $parsed_urls_all_domains;
			}
		}
	elseif ( $type == 'url_at' ) { /* Added 1.3.8 */
		$results = $parsed_links_matches;
		}
	elseif ( $type == 'anchor_text' ) {
		$results = $parsed_anchortxt_matches;
		}
	return $results;
	}

function spamshield_fix_url( $url, $rem_frag = FALSE, $rem_query = FALSE, $rev = FALSE ) {
	/***
	* Fix poorly formed URLs so as not to throw errors or cause problems
	***/
	$url = trim( $url );
	/* Too many forward slashes or colons after http */
	$url = preg_replace( "~^(https?)\:+/+~i", "$1://", $url);
	/* Too many dots */
	$url = preg_replace( "~\.+~i", ".", $url);
	/* Too many slashes after the domain */
	$url = preg_replace( "~([a-z0-9]+)/+([a-z0-9]+)~i", "$1/$2", $url);
	/* Remove fragments */
	if ( !empty( $rem_frag ) && strpos( $url, '#' ) !== FALSE ) { $url_arr = explode( '#', $url ); $url = $url_arr[0]; }
	/* Remove query string completely */
	if ( !empty( $rem_query ) && strpos( $url, '?' ) !== FALSE ) { $url_arr = explode( '?', $url ); $url = $url_arr[0]; }
	/* Reverse */
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
	$wpss_site_domain 	= $server_name = RSMP_SITE_DOMAIN;
	$wpss_env_http_host	= getenv('HTTP_HOST');
	$wpss_env_srvr_name	= getenv('SERVER_NAME');
	if 		( !empty( $_SERVER['HTTP_HOST'] ) 	&& strpos( $wpss_site_domain, $_SERVER['HTTP_HOST'] ) 	!== FALSE ) { $server_name = $_SERVER['HTTP_HOST']; }
	elseif 	( !empty( $wpss_env_http_host ) 	&& strpos( $wpss_site_domain, $wpss_env_http_host ) 	!== FALSE ) { $server_name = $wpss_env_http_host; }
	elseif 	( !empty( $_SERVER['SERVER_NAME'] ) && strpos( $wpss_site_domain, $_SERVER['SERVER_NAME'] ) !== FALSE ) { $server_name = $_SERVER['SERVER_NAME']; }
	elseif 	( !empty( $wpss_env_srvr_name ) 	&& strpos( $wpss_site_domain, $wpss_env_srvr_name ) 	!== FALSE ) { $server_name = $wpss_env_srvr_name; }
	return spamshield_casetrans( 'lower', $server_name );
	}

function spamshield_get_query_arr($url) {
	/* Get array of variables from query string */
	$query_str = spamshield_get_query_string($url); /* 1.7.3 - Validates better */
	if ( !empty( $query_str ) ) { $query_arr = explode( '&', $query_str ); } else { $query_arr = ''; }
	return $query_arr;
	}

function spamshield_remove_query( $url, $skip_wp_args = FALSE ) {
	/***
	* For removing specific query argument(s)
	* If you need URL fragments removed, or the entire query string removed, use spamshield_fix_url()
	***/
	$query_arr = spamshield_get_query_arr($url);
	if ( empty( $query_arr ) ) { return $url; }
	$remove_args = array();
	foreach( $query_arr as $i => $query_arg ) {
		$query_arg_arr = explode( '=', $query_arg );
		$key = $query_arg_arr[0];
		if ( !empty( $skip_wp_args ) && ( $key == 'p' || $key == 'page_id' ) ) { continue; } /* DO NOT ADD 'cpage', only 'p' and 'page_id'!! */
		$remove_args[] = $key;
		}
	$clean_url = remove_query_arg( $remove_args, $url );
	return $clean_url;
	}

function spamshield_get_user_agent( $raw = FALSE, $lowercase = FALSE ) {
	/***
	* Gives User-Agent with filters
	* If blank, gives an initialized var to eliminate need for testing if isset() everywhere
	* Default is sanitized - use raw for testing, and sanitized for output
	* Added option for raw & lowercase in 1.5
	***/
	if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
		if ( !empty ( $raw ) ) 			{ $user_agent = trim( $_SERVER['HTTP_USER_AGENT'] ); } else { $user_agent = sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ); }
		if ( !empty ( $lowercase ) ) 	{ $user_agent = spamshield_casetrans( 'lower', $user_agent ); }
		}
	else { $user_agent = ''; }
	return $user_agent;
	}

function spamshield_get_http_accept( $raw = FALSE, $lowercase = FALSE, $lang = FALSE ) {
	/***
	* Gives $_SERVER['HTTP_ACCEPT'] and $_SERVER['HTTP_ACCEPT_LANGUAGE'] with filters
	* Default is sanitized
	***/
	$http_accept = $http_accept_language = '';
	if ( !empty( $_SERVER['HTTP_ACCEPT'] ) )			{ $http_accept			= $_SERVER['HTTP_ACCEPT']; }
	if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) )	{ $http_accept_language	= $_SERVER['HTTP_ACCEPT_LANGUAGE']; }
	$raw_serv_var							= $http_accept;
	if ( !empty( $lang ) ) { $raw_serv_var	= $http_accept_language; }
	if ( !empty( $raw_serv_var ) ) {
		if ( !empty ( $raw ) ) 			{ $http_accept_var = trim( $raw_serv_var ); } else { $http_accept_var = sanitize_text_field( $raw_serv_var ); }
		if ( !empty ( $lowercase ) ) 	{ $http_accept_var = spamshield_casetrans( 'lower', $http_accept_var ); }
		}
	else { $http_accept_var = ''; }
	return $http_accept_var;
	}

function spamshield_get_referrer( $raw = FALSE, $lowercase = FALSE, $init = FALSE ) {
	/***
	* Gives $_SERVER['HTTP_REFERER'] with filters
	* Default is sanitized
	***/
	$http_referrer = $init_referrer = '';
	$site_domain = RSMP_SERVER_NAME;
	if ( !empty( $_SERVER['HTTP_REFERER'] ) )	{ $http_referrer = $_SERVER['HTTP_REFERER']; }
	if ( !empty( $_COOKIE['JCS_INENREF'] ) )	{ 
		$init_referrer			= $_COOKIE['JCS_INENREF'];
		$init_referrer_no_query	= spamshield_fix_url( $init_referrer, TRUE, TRUE ); /* Remove query string and fragments */
		if ( strpos( $init_referrer_no_query, $site_domain ) !== FALSE ) { $init_referrer = ''; } /* Tracking referrals from other sites only */
		}
	if ( empty( $init_referrer ) && !empty( $_COOKIE['_referrer_og'] ) )	{ 
		$init_referrer			= $_COOKIE['_referrer_og'];
		$init_referrer_no_query	= spamshield_fix_url( $init_referrer, TRUE, TRUE ); /* Remove query string and fragments */
		if ( strpos( $init_referrer_no_query, $site_domain ) !== FALSE ) { $init_referrer = ''; } /* Tracking referrals from other sites only */
		}
	$referrer = $http_referrer;
	if ( !empty( $init ) ) { $referrer = $init_referrer; }
	if ( !empty( $referrer ) ) {
		if ( !empty ( $raw ) ) 			{ $referrer = trim( $referrer ); } else { $referrer = esc_url_raw( $referrer ); }
		if ( !empty ( $lowercase ) )	{ $referrer = spamshield_casetrans( 'lower', $referrer ); }
		}
	else { $referrer = ''; }
	return $referrer;
	}

function spamshield_get_error_type( $error_code ) {
	/***
	* Returns type of error - JavaScript/Cookies Layer or Algorithmic Layer - 'jsck' or 'algo'
	* Added 1.8.9.6
	***/
	if ( empty( $error_code ) ) { return FALSE; }
	if ( strpos( $error_code, 'COOKIE-' ) !== FALSE || strpos( $error_code, 'REF-2-1023-' ) !== FALSE || strpos( $error_code, 'JSONST-1000-' ) !== FALSE || strpos( $error_code, 'FVFJS-' ) !== FALSE) {
		return 'jsck';
		}
	else { return 'algo'; }
	}

function spamshield_is_valid_ip( $ip, $incl_priv_res = FALSE, $ipv4_c_block = FALSE ) {
	if ( empty( $ip ) ) { return FALSE; }
	if ( !empty( $ipv4_c_block ) ) {
		if ( preg_match( "~^(([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.){3}$~", $ip ) ) {
			/* Valid C-Block check - checking for C-block: '123.456.78.' format */
			return TRUE;
			}
		}
	if ( function_exists( 'filter_var' ) ) {
		if ( empty( $incl_priv_res ) ) {
			if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) { return TRUE; } 
			}
		elseif ( filter_var( $ip, FILTER_VALIDATE_IP ) ) { return TRUE; }
		/* FILTER_FLAG_IPV4,FILTER_FLAG_IPV6,FILTER_FLAG_NO_PRIV_RANGE,FILTER_FLAG_NO_RES_RANGE */
		}
	elseif ( preg_match( "~^(([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])$~", $ip ) && !preg_match( "~^192\.168\.~", $ip ) ) { return TRUE; }
	return FALSE;
	}

function spamshield_is_google_domain($domain) {
	/***
	* Check if domain is a Google Domain
	* Added 1.7.8
	* Google Domains - updated at: https://www.google.com/supported_domains
	***/
	$google_domains = array(
		'.com','.ad','.ae','.com.af','.com.ag','.com.ai','.al','.am','.co.ao','.com.ar','.as','.at','.com.au','.az','.ba','.com.bd','.be','.bf','.bg','.com.bh','.bi','.bj','.com.bn','.com.bo','.com.br','.bs','.bt','.co.bw','.by','.com.bz','.ca','.cd','.cf','.cg','.ch','.ci','.co.ck','.cl','.cm','.cn','.com.co','.co.cr','.com.cu','.cv','.com.cy','.cz','.de','.dj','.dk','.dm','.com.do','.dz','.com.ec','.ee','.com.eg','.es','.com.et','.fi','.com.fj','.fm','.fr','.ga','.ge','.gg','.com.gh','.com.gi','.gl','.gm','.gp','.gr','.com.gt','.gy','.com.hk','.hn','.hr','.ht','.hu','.co.id','.ie','.co.il','.im','.co.in','.iq','.is','.it','.je','.com.jm','.jo','.co.jp','.co.ke','.com.kh','.ki','.kg','.co.kr','.com.kw','.kz','.la','.com.lb','.li','.lk','.co.ls','.lt','.lu','.lv','.com.ly','.co.ma','.md','.me','.mg','.mk','.ml','.com.mm','.mn','.ms','.com.mt','.mu','.mv','.mw','.com.mx','.com.my','.co.mz','.com.na','.com.nf','.com.ng','.com.ni','.ne','.nl','.no','.com.np','.nr','.nu','.co.nz','.com.om','.com.pa','.com.pe','.com.pg','.com.ph','.com.pk','.pl','.pn','.com.pr','.ps','.pt','.com.py','.com.qa','.ro','.ru','.rw','.com.sa','.com.sb','.sc','.se','.com.sg','.sh','.si','.sk','.com.sl','.sn','.so','.sm','.sr','.st','.com.sv','.td','.tg','.co.th','.com.tj','.tk','.tl','.tm','.tn','.to','.com.tr','.tt','.com.tw','.co.tz','.com.ua','.co.ug','.co.uk','.com.uy','.co.uz','.com.vc','.co.ve','.vg','.co.vi','.com.vn','.vu','.ws','.rs','.co.za','.co.zm','.co.zw','.cat'
		);
	foreach( $google_domains as $i => $ext ) {
		$google_domain 	= 'google'.$ext;
		$regex_check_phrase = spamshield_get_regex_phrase( $google_domain, '', 'domain' );
		if ( preg_match( $regex_check_phrase, $domain ) ) {
			return TRUE;
			}
		}
	return FALSE;
	}

function spamshield_is_google_ip($ip) {
	/***
	* Check if domain is a Google IP
	* Added 1.7.8
	***/
	if ( preg_match( "~^(64\.233\.1([6-8][0-9]|9[0-1])|66\.102\.([0-9]|1[0-5])|66\.249\.(6[4-9]|[7-8][0-9]|9[0-5])|72\.14\.(19[2-9]|2[0-4][0-9]|25[0-5])|74\.125\.([0-9]|[0-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])|209\.85\.(1(2[8-9]|[3-9][0-9])|2[0-4][0-9]|25[0-5])|216\.239\.(3[2-9]|[4-5][0-9]|6[0-3]))\.~", $ip ) ) {
		return TRUE;
		}
	return FALSE;
	}

function spamshield_get_regex_phrase( $input, $custom_delim = NULL, $flag = "N" ) {
	/* Get Regex Phrase from an Array or String */
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
		/***
		* REFERENCE: Parse full html links with this:
		* $parse_links_regex = "~(<\s*a\s+[a-z0-9\-_\.\?\='\"\:\(\)\{\}\s]*\s*href|\[(url|link))\s*\=\s*['\"]?\s*(https?\:/+[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)\s*['\"]?\s*[a-z0-9\-_\.\?\='\"\:;\(\)\{\}\s]*\s*(>|\])([a-z0-9àáâãäåçèéêëìíîïñńņňòóôõöùúûü\-_\/\.\?\&\=\~\@\%\+\#\:;\!,'\(\)\{\}\s]*)(<|\[)\s*\/\s*a\s*(>|(url|link)\])~iu";
		***/
		"linkwrap"		=> "(<\s*a\s+([a-z0-9\-_\.\?\='\"\:\(\)\{\}\s]*)\s*href|\[(url|link))\s*\=\s*(['\"])?\s*https?\:/+((ww[w0-9]|m)\.)?(X)/?([a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]*)(['\"])?(>|\])",
		"httplinkwrap"	=> "(^|\b)https?\:/+((ww[w0-9]|m)\.)?(X)/?([a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]*)",
		/***
		* REFERENCE: Parse stripped http links with this:
		* $search_http_regex ="~\s+(https?\://[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)\s+~i";
		***/
		"red_str"		=> "(X)", /* Red-flagged string */
		"rgx_str"		=> "(X)", /* Regex-ready string */
		);
	if ( is_array( $input) ) {
		$regex_flag = $flag_regex_arr[$flag];
		$regex_phrase_pre_arr = array();
		foreach( $input as $i => $val ) {
			if ( $flag == "rgx_str" || $flag == "authorkw" || $flag == "atxtwrap" ) { $val_reg_pre = $val; } /* Variable must come in prepped for regex (preg_quoted) */
			else { $val_reg_pre = spamshield_preg_quote($val); }
			$regex_phrase_pre_arr[] = $val_reg_pre;
			}
		$regex_phrase_pre_str 	= implode( "|", $regex_phrase_pre_arr );
		$regex_phrase_str 		= preg_replace( "~X~", $regex_phrase_pre_str, $regex_flag );
		if ( !empty( $custom_delim ) ) {  $delim = $custom_delim; } else { $delim = "~"; }
		$regex_phrase = $delim.$regex_phrase_str.$delim."iu"; /* UTF-8 enabled */
		if ( $flag == "email_addr" || $flag == "red_str" ) {
			$regex_phrase = str_replace( '@gmail\.', '@g(oogle)?mail\.', $regex_phrase );
			}
		}
	elseif ( is_string( $input ) ) {
		$val = $input;
		$regex_flag = $flag_regex_arr[$flag];
		if ( $flag == "rgx_str" || $flag == "authorkw" || $flag == "atxtwrap" ) { $val_reg_pre = $val; } /* Variable must come in prepped for regex (preg_quoted) */
		else { $val_reg_pre = spamshield_preg_quote($val); }
		$regex_phrase_str 	= preg_replace( "~X~", $val_reg_pre, $regex_flag );
		if ( !empty( $custom_delim ) ) {  $delim = $custom_delim; } else { $delim = "~"; }
		$regex_phrase = $delim.$regex_phrase_str.$delim."iu"; /* UTF-8 enabled */
		if ( $flag == "email_addr" || $flag == "red_str" ) {
			$regex_phrase = str_replace( '@gmail\.', '@g(oogle)?mail\.', $regex_phrase );
			}
		}
	else {
		return $input;
		}

	return $regex_phrase;
	}

function spamshield_rubik_mod( $str, $mod = 'en', $exp = FALSE, $del = '~' ) {
	$lft = '.!:;1234567890|abcdefghijklmnopqrstuvwxyz{}()<>~@#$%^&*?,_-+= ';
	$rgt = 'ghiJVWXyz@#$%^&*?,_-+=1234567890ABCdefGHIjklMNOpqrSTUvwxYZabcD';
	if ( $mod == 'en' ) { $mod_dat = strtr( $str, $lft, $rgt ); } else { $mod_dat = strtr( $str, $rgt, $lft ); }
	if ( !empty( $exp ) ) { $mod_dat = explode(  $del, $mod_dat ); }
	return $mod_dat;
	}

function spamshield_is_plugin_active( $plugin_name ) {
	/***
	* Using this because is_plugin_active() only works in Admin 
	* ex. $plugin_name = 'commentluv/commentluv.php';
	***/
	$plugin_active_status = FALSE;
	if ( empty( $plugin_name ) ){ return $plugin_active_status; }
	$wpss_active_plugins = get_option( 'active_plugins' );
	if ( in_array( $plugin_name, $wpss_active_plugins, TRUE ) ) {
		$plugin_active_status = TRUE;
		}
	return $plugin_active_status;
	}

function spamshield_error_txt( $case = 'UC' ) {
	/* $case = 'def' (default - unaltered), 'UC' (uppercase) */
	$default_txt = __( 'Error' );
	if ( $case == 'UC' ) { return spamshield_casetrans( 'upper', $default_txt ); } else { return $default_txt; }
	}

function spamshield_blocked_txt( $case = 'def' ) {
	/* $case = 'def' (default - unaltered), 'UCW' (uppercase words) */
	$default_txt = __( 'SPAM BLOCKED', WPSS_PLUGIN_NAME );
	if ( $case == 'UCW' ) { return spamshield_casetrans( 'ucwords', $default_txt ); } else { return $default_txt; }
	}

function spamshield_doc_txt() {
	return __( 'Documentation', WPSS_PLUGIN_NAME );
	}

function spamshield_is_lang_en_us( $strict = TRUE ) {
	/* Test if site is set to use English (US) - the default - or another language/localization */
	$wpss_locale = get_locale();
	if ( $strict != TRUE ) {
		/* Not strict - English, but localized translations may be in use */
		if ( !empty( $wpss_locale ) && !preg_match( "~^(en(_[a-z]{2})?)?$~i", $wpss_locale ) ) { $lang_en_us = FALSE; } else { $lang_en_us = TRUE; }
		}
	else {
		/* Strict - English (US), no translation being used */
		if ( !empty( $wpss_locale ) && !preg_match( "~^(en(_us)?)?$~i", $wpss_locale ) ) { $lang_en_us = FALSE; } else { $lang_en_us = TRUE; }
		}
	return $lang_en_us;
	}

function spamshield_is_login_page() {
	$wpss_login_page_check = in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
    return $wpss_login_page_check;
	}

function spamshield_is_xmlrpc() {
	$xmlrpc_status = FALSE;
	if ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) { return TRUE; }
    return $xmlrpc_status;
	}

function spamshield_is_cron() {
	$cron_status = FALSE;
	if ( defined('DOING_CRON') && DOING_CRON ) { return TRUE; }
    return $cron_status;
	}

function spamshield_is_firefox() {
	$is_firefox = FALSE;
	$user_agent = spamshield_get_user_agent( TRUE, FALSE );
	if ( strpos( $user_agent, 'Firefox' ) !== FALSE && strpos( $user_agent, 'SeaMonkey' ) === FALSE ) { return TRUE; }
    return $is_firefox;
	}

/* Standard Functions - END */

function spamshield_load_languages() {
	load_plugin_textdomain( WPSS_PLUGIN_NAME, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}

function spamshield_first_action() {

	spamshield_start_session();
	/* Add all commands after this */

	/* Add Vars Here */
	$key_main_page_hits		= 'wpss_page_hits_'.RSMP_HASH;
	$key_main_pages_hist 	= 'wpss_pages_hit_'.RSMP_HASH;
	$key_main_hits_per_page	= 'wpss_pages_hit_count_'.RSMP_HASH;
	$key_first_ref			= 'wpss_referer_init_'.RSMP_HASH;
	$current_ref			= spamshield_get_referrer();
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

	if ( !is_admin() && !current_user_can( 'moderate_comments' ) ) {
		/* Page hits */
		if ( empty( $_SESSION[$key_main_page_hits] ) ) {
			$_SESSION[$key_main_page_hits] = 0;
			}
		++$_SESSION[$key_main_page_hits];
		/* Pages visited history */
		if ( empty( $_SESSION[$key_main_pages_hist] ) ) {
			$_SESSION[$key_main_pages_hist] = array();
			$_SESSION[$key_main_hits_per_page] = array();
			}
		$_SESSION[$key_main_pages_hist][] = spamshield_get_url();
		/* Initial referrer */
		if ( empty( $_SESSION[$key_first_ref] ) ) {
			if ( !empty( $current_ref ) ) { $_SESSION[$key_first_ref] = $current_ref; }
			else { $_SESSION[$key_first_ref] = '[No Data]'; }
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
	/* TEST FOR CACHING */
	$wpss_active_plugins		= get_option( 'active_plugins' );
	$wpss_active_plugins_ser	= spamshield_casetrans( 'lower', serialize( $wpss_active_plugins ) );
	if ( defined( 'WP_CACHE' ) && TRUE == WP_CACHE ) { $wpss_caching_status = 'ACTIVE'; } else { $wpss_caching_status = 'INACTIVE'; }
	if ( defined( 'ENABLE_CACHE' ) && TRUE == ENABLE_CACHE ) { $wpss_caching_enabled_status = 'ACTIVE'; } else { $wpss_caching_enabled_status = 'INACTIVE'; }
	/* Check if any popular cache plugins are active */
	$popular_cache_plugins				= unserialize( WPSS_POPULAR_CACHE_PLUGINS );
	$popular_cache_plugins_array_count	= count( $popular_cache_plugins );
	$wpss_active_plugins_array_count	= count( $wpss_active_plugins );
	$popular_cache_plugins_active		= array();
	$popular_cache_plugins_temp			= 0;
	$i = 0;
	while ($i < $popular_cache_plugins_array_count) {
		if ( strpos( $wpss_active_plugins_ser, $popular_cache_plugins[$i] ) !== FALSE ) {
			$popular_cache_plugins_temp = 1;
			$popular_cache_plugins_active[] = $popular_cache_plugins[$i];
			}
		$i++;
		}
	if ( $popular_cache_plugins_temp == 1 ) { $wpss_caching_plugin_status = 'ACTIVE'; } else { $wpss_caching_plugin_status = 'INACTIVE'; }
	if ( $wpss_caching_status == 'ACTIVE' || $wpss_caching_enabled_status == 'ACTIVE' || $wpss_caching_plugin_status == 'ACTIVE' ) {
		/* This is the overall test if caching is active. */
		$wpss_cache_check_status = 'ACTIVE';
		}
	else { $wpss_cache_check_status = 'INACTIVE'; }
	/***
	* NOT USING FOR NOW
	$wpss_caching_plugins_active	= serialize( $popular_cache_plugins_active );
	$wpss_all_plugins_active		= serialize( $wpss_active_plugins );
	***/
	$wpss_cache_check = array(
		'cache_check_status'		=> $wpss_cache_check_status,
		/***
		* NOT USING FOR NOW
		'caching_status'			=> $wpss_caching_status,
		'caching_enabled_status'	=> $wpss_caching_enabled_status,
		'caching_plugin_status'		=> $wpss_caching_plugin_status,
		'caching_plugins_active'	=> $wpss_caching_plugins_active,
		'all_plugins_active'		=> $wpss_all_plugins_active,
		***/
		);
	return $wpss_cache_check;
	}

function spamshield_count() {
	$spamshield_count = get_option( 'spamshield_count' );
	if ( empty( $spamshield_count ) ) { $spamshield_count = 0; }
	return $spamshield_count;
	}

function spamshield_increment_count( $type = 'jsck' ) {
	/* $type: 'algo', 'jsck' */
	$max_attempts = 7;
	$max_js_errors = 3;
	update_option( 'spamshield_count', spamshield_count() + 1 );
	if ( empty( $_SESSION['user_spamshield_count_'.RSMP_HASH] ) ) 		{ $_SESSION['user_spamshield_count_'.RSMP_HASH] = 0; }
	if ( empty( $_SESSION['user_spamshield_count_jsck_'.RSMP_HASH] ) )	{ $_SESSION['user_spamshield_count_jsck_'.RSMP_HASH] = 0; }
	if ( empty( $_SESSION['user_spamshield_count_algo_'.RSMP_HASH] ) )	{ $_SESSION['user_spamshield_count_algo_'.RSMP_HASH] = 0; }
	++$_SESSION['user_spamshield_count_'.RSMP_HASH];
	++$_SESSION['user_spamshield_count_'.$type.'_'.RSMP_HASH];
	if ( $_SESSION['user_spamshield_count_algo_'.RSMP_HASH] >= $max_attempts && $_SESSION['user_spamshield_count_jsck_'.RSMP_HASH] < $max_js_errors ) { spamshield_ubl_cache( 'set' ); } /* Changed 1.8.9.6 */
	}

function spamshield_reg_count() {
	$spamshield_reg_count = get_option( 'spamshield_reg_count' );
	if ( empty( $spamshield_reg_count ) ) { $spamshield_reg_count = 0; }
	return $spamshield_reg_count;
	}

function spamshield_increment_reg_count() {
	update_option( 'spamshield_reg_count', spamshield_reg_count() + 1 );
	if ( empty( $_SESSION['user_spamshield_reg_count_'.RSMP_HASH] ) ) { 
		$_SESSION['user_spamshield_reg_count_'.RSMP_HASH] = 0;
		}
	++$_SESSION['user_spamshield_reg_count_'.RSMP_HASH];
	}

function spamshield_procdat( $method = 'get' ) {
	/* $method: 'reset','get' */
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
	if ( $method == 'reset' ) { update_option( 'spamshield_procdat', $wpss_proc_data ); } else { return $wpss_proc_data; }
	}

/* Counters - BEGIN */

function spamshield_counter( $counter_option = 0 ) {
	/***
	* As of 1.8.4, this is calls spamshield_counter_short()
	* Display Counter
	* Implementation: <?php if ( function_exists('spamshield_counter') ) { spamshield_counter(1); } ?>
	***/
	$atts = array();
	$atts['style'] = $counter_option;
	echo spamshield_counter_short( $atts );
	}

function spamshield_counter_short( $atts = array() ) {
	global $wpss_wid_inst;
	if ( !isset( $wpss_wid_inst ) ) { $wpss_wid_inst = 0; }
	++$wpss_wid_inst;
	$counter_option = $atts['style'];
	$counter_option_max = 9;
	$counter_option_min = 1;
	$counter_spam_blocked_msg = __( 'spam blocked by WP-SpamShield', WPSS_PLUGIN_NAME );
	if ( empty( $counter_option ) || $counter_option > $counter_option_max || $counter_option < $counter_option_min ) {
		$spamshield_count = spamshield_number_format( spamshield_count() );
		$wpss_shortcode_content = '<a href="'.WPSS_HOME_URL.'" style="text-decoration:none;" target="_blank" rel="external" title="'.spamshield_promo_text(11).'" >'.$spamshield_count.' '.$counter_spam_blocked_msg.'</a>'."\n";
		return $wpss_shortcode_content;
		}
	/***
	* Display Counter
	* Implementation: <?php if ( function_exists('spamshield_counter') ) { spamshield_counter(1); } ?>
	* Implementation: [spamshieldcounter style=1] or [spamshieldcounter] where "style" is 0-9
	***/
	$spamshield_count = !empty( $atts['spamshield_count'] ) ? $atts['spamshield_count'] : spamshield_number_format( spamshield_count() );
	$counter_div_height = array('0','66','66','66','106','61','67','66','66','106');
	$counter_count_padding_top = array('0','11','11','11','75','14','17','11','11','75');
	$wpss_shortcode_content  = '';
	$wpss_shortcode_content .= '<style type="text/css">'."\n";
	$wpss_shortcode_content .= '#spamshield_counter_wrap_'.$wpss_wid_inst.' {color:#ffffff;text-decoration:none;width:140px;}'."\n";
	$wpss_shortcode_content .= '#spamshield_counter_'.$wpss_wid_inst.' {background:url('.WPSS_PLUGIN_COUNTER_URL.'/spamshield-counter-bg-'.$counter_option.'.png) no-repeat top left;height:'.$counter_div_height[$counter_option].'px;width:140px;overflow:hidden;border-style:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;padding-top:'.$counter_count_padding_top[$counter_option].'px;}'."\n";
	$wpss_shortcode_content .= '</style>'."\n";
	$wpss_shortcode_content .= '<div id="spamshield_counter_wrap_'.$wpss_wid_inst.'" >'."\n";
	$wpss_shortcode_content .= "\t".'<div id="spamshield_counter_'.$wpss_wid_inst.'" >'."\n";
	$sip1c = substr(RSMP_SERVER_ADDR, 0, 1);
	if ( ( $counter_option >= 1 && $counter_option <= 3 ) || ( $counter_option >= 7 && $counter_option <= 8 ) ) {
		$spamshield_counter_title_text = $sip1c > '5' ? spamshield_promo_text(2) : spamshield_promo_text(3);
		$wpss_shortcode_content .= "\t".'<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_URL.'" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" target="_blank" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:20px !important;line-height:80% !important;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:14px !important;line-height:130% !important;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.spamshield_promo_text(0).'</span><br />'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:9px !important;line-height:90% !important;letter-spacing:1px;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.spamshield_promo_text(1).'</span>'."\n";
		$wpss_shortcode_content .= "\t".'</a></strong>';
		}
	elseif ( $counter_option == 4 || $counter_option == 9 ) {
		if ( $sip1c > '5' ) {
			$spamshield_counter_title_text = spamshield_promo_text(4);
			}
		else {
			$spamshield_counter_title_text = spamshield_promo_text(5);
			}
		$wpss_shortcode_content .= "\t".'<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_URL.'" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" target="_blank" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#000000;font-size:9px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.' '.spamshield_promo_text(0).'</span><br />'."\n";
		$wpss_shortcode_content .= "\t".'</a></strong>'."\n";
		}
	elseif ( $counter_option == 5 ) {
		$wpss_shortcode_content .= "\t".'<strong style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_URL.'" style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" target="_blank" rel="external" title="'.spamshield_promo_text(6).'" >'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#FEB22B;font-size:14px !important;line-height:100% !important;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n";
		$wpss_shortcode_content .= "\t".'</a></strong>'."\n";
		}
	elseif ( $counter_option == 6 ) {
		if ( $sip1c > '5' ) {
			$spamshield_counter_title_text = "\t".''.spamshield_promo_text(7)."\n";
			}
		else {
			$spamshield_counter_title_text = "\t".''.spamshield_promo_text(8)."\n";
			}
		$wpss_shortcode_content .= "\t".'<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100% !important;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_URL.'" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" target="_blank" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#000000;font-size:14px !important;line-height:100% !important;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n";
		$wpss_shortcode_content .= "\t".'</a></strong>'."\n";
		}
	$wpss_shortcode_content .= "\t".'</div>'."\n";
	$wpss_shortcode_content .= '</div>'."\n";
	return $wpss_shortcode_content;
	}

function spamshield_counter_sm( $counter_sm_option = 1 ) {
	/***
	* As of 1.8.4, this is calls spamshield_counter_sm_short()
	* Display Small Counter
	* Implementation: <?php if ( function_exists('spamshield_counter_sm') ) { spamshield_counter_sm(1); } ?>
	***/
	$atts = array();
	$atts['style'] = $counter_sm_option;
	echo spamshield_counter_sm_short( $atts );
	}

function spamshield_counter_sm_short( $atts = array() ) {
	global $wpss_wid_inst;
	if ( !isset( $wpss_wid_inst ) ) { $wpss_wid_inst = 0; }
	++$wpss_wid_inst;
	$counter_sm_option = $atts['style'];
	$counter_sm_option_max = 5;
	$counter_sm_option_min = 1;
	if ( empty( $counter_sm_option ) || $counter_sm_option > $counter_sm_option_max || $counter_sm_option < $counter_sm_option_min ) {
		$counter_sm_option = 1;
		}
	/***
	* Display Small Counter
	* Implementation: [spamshieldcountersm style=1] or [spamshieldcountersm] where "style" is 1-5
	***/
	$spamshield_count = !empty( $atts['spamshield_count'] ) ? $atts['spamshield_count'] : spamshield_number_format( spamshield_count() );
	$counter_sm_div_height = array('0','50','50','50','50','50');
	$counter_sm_count_padding_top = array('0','11','11','11','11','11');
	$wpss_shortcode_content  = '';
	$wpss_shortcode_content .= "\n\n";
	$wpss_shortcode_content .= '<style type="text/css">'."\n";
	$wpss_shortcode_content .= '#spamshield_counter_sm_wrap_'.$wpss_wid_inst.' {color:#ffffff;text-decoration:none;width:120px;}'."\n";
	$wpss_shortcode_content .= '#spamshield_counter_sm_'.$wpss_wid_inst.' {background:url('.WPSS_PLUGIN_COUNTER_URL.'/spamshield-counter-sm-bg-'.$counter_sm_option.'.png) no-repeat top left;height:'.$counter_sm_div_height[$counter_sm_option].'px;width:120px;overflow:hidden;border-style:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;padding-top:'.$counter_sm_count_padding_top[$counter_sm_option].'px;}'."\n";
	$wpss_shortcode_content .= '</style>'."\n\n";
	$wpss_shortcode_content .= '<div id="spamshield_counter_sm_wrap_'.$wpss_wid_inst.'" >'."\n\t";
	$wpss_shortcode_content .= '<div id="spamshield_counter_sm_'.$wpss_wid_inst.'" >'."\n";
	$sip1c = substr(RSMP_SERVER_ADDR, 0, 1);
	if ( ( $counter_sm_option >= 1 && $counter_sm_option <= 5 ) ) {
		if ( $sip1c > '5' ) {
			$spamshield_counter_title_text = spamshield_promo_text(9);
			}
		else {
			$spamshield_counter_title_text = spamshield_promo_text(10);
			}
		$wpss_shortcode_content .= "\t".'<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_URL.'" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" target="_blank" rel="external" title="'.$spamshield_counter_title_text.'" >'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:18px !important;line-height:100% !important;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'."\n";
		$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:10px !important;line-height:120% !important;letter-spacing:1px;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.spamshield_promo_text(0).'</span>'."\n";
		$wpss_shortcode_content .= "\t".'</a></strong>'."\n";
		}
	$wpss_shortcode_content .= "\t".'</div>'."\n";
	$wpss_shortcode_content .= '</div>'."\n";
	return $wpss_shortcode_content;
	}

/* Widgets */
function spamshield_load_widgets() {
	register_widget( 'WP_SpamShield_Counter_LG' );
	register_widget( 'WP_SpamShield_Counter_CG' );
	register_widget( 'WP_SpamShield_End_Blog_Spam' );
	}

/* Widget classes were here. Moved to include in V1.8.4 */
	
/* Counters - END */

function spamshield_log_reset( $ip = NULL, $get_fws = FALSE, $clr_hta = FALSE, $mk_log = FALSE ) {
	/***
	* $ip 		- Optional
	* $get_fws	- File writeable status - returns bool
	* $clr_hta	- Reset .htaccess only, don't reset log
	* $mk_log	- Make log log file if none exists
	***/
	if ( ( empty( $ip ) || !spamshield_is_valid_ip( $ip ) ) && current_user_can( 'manage_options' ) && !empty( $_SERVER['REMOTE_ADDR'] ) ) { $ip = $_SERVER['REMOTE_ADDR']; }
	else {
		$last_admin_ip = get_option( 'spamshield_last_admin' );
		if ( !empty( $last_admin_ip ) && spamshield_is_valid_ip( $last_admin_ip ) ) { $ip = $last_admin_ip; }
		}
	$wpss_log_filns	= array('','temp-comments-log.txt','temp-comments-log.init.txt','.htaccess','htaccess.txt','htaccess.init.txt'); /* Filenames - log, log_empty, htaccess, htaccess,_orig, htaccess_empty */
	$wpss_log_perlr	= array(0775,0666,0666,0666,0666,0666); /* Permission level recommended */
	$wpss_log_perlm	= array(0755,0644,0644,0644,0644,0644); /* Permission level minimum */
	$wpss_log_files	= array(); /* Log files with full paths */
	foreach ( $wpss_log_filns as $f => $filn ) { $wpss_log_files[] = WPSS_PLUGIN_DATA_PATH.'/'.$filn; }
	/* 1 - Create temp-comments-log.txt if it doesn't exist */
	clearstatcache();
	if ( !file_exists( $wpss_log_files[1] ) ) {
		@chmod( $wpss_log_files[2], 0666 );
		@copy( $wpss_log_files[2], $wpss_log_files[1] );
		@chmod( $wpss_log_files[1], 0666 );
		}
	if ( !empty( $mk_log ) ) { return FALSE; }
	/* 2 - Create .htaccess if it doesn't exist */
	clearstatcache();
	if ( !file_exists( $wpss_log_files[3] ) ) {
		@chmod( $wpss_log_files[0], 0775 ); @chmod( $wpss_log_files[4], 0666 ); @chmod( $wpss_log_files[5], 0666 );
		@rename( $wpss_log_files[4], $wpss_log_files[3] ); @copy( $wpss_log_files[5], $wpss_log_files[4] );
		foreach ( $wpss_log_files as $f => $file ) { @chmod( $file, $wpss_log_perlr[$f] ); }
		}
	/* 3 - Check file permissions and fix */
	clearstatcache();
	$wpss_log_perms = array(); /* File permissions */
	foreach ( $wpss_log_files as $f => $file ) { $wpss_log_perms[] = substr(sprintf('%o', fileperms($file)), -4); }
	foreach ( $wpss_log_perlr as $p => $perlr ) { 
		if ( $wpss_log_perms[$p] < $perlr || !is_writable( $wpss_log_files[$p] ) ) {
			foreach ( $wpss_log_files as $f => $file ) { @chmod( $file, $wpss_log_perlr[$f] ); } /* Correct the permissions... */
			break;
			}
		}
	/* 4 - Clear files by copying fresh versions to existing files */
	if ( empty( $clr_hta ) ) { 
		if ( file_exists( $wpss_log_files[1] ) && file_exists( $wpss_log_files[2] ) ) { @copy( $wpss_log_files[2], $wpss_log_files[1] ); } /* Log file */
		}
	if ( file_exists( $wpss_log_files[3] ) && file_exists( $wpss_log_files[5] ) ) { @copy( $wpss_log_files[5], $wpss_log_files[3] ); } /* .htaccess file */
	/* 5 - Write .htaccess */
	$wpss_htaccess_data	= $wpss_access_ap22 = '';
	$wpss_access_ap24 = "Require all denied\n";
	if ( !empty( $ip ) ) {
		$ip_rgx = spamshield_preg_quote( $ip );
		$wpss_htaccess_data .= "SetEnvIf Remote_Addr ^".$ip_rgx."$ wpss_access\n\n";
		$wpss_access_ap22 = "\t\tAllow from env=wpss_access\n";
		$wpss_access_ap24 = "Require env wpss_access\n";
		}
	$wpss_htaccess_data .= "<Files temp-comments-log.txt>\n";
	$wpss_htaccess_data .= "\t# Apache 2.2\n\t<IfModule !mod_authz_core.c>\n\t\tOrder deny,allow\n\t\tDeny from all\n".$wpss_access_ap22."\t</IfModule>\n\n";
	$wpss_htaccess_data .= "\t# Apache 2.4\n\t<IfModule mod_authz_core.c>\n\t\t".$wpss_access_ap24."\t</IfModule>\n";
	$wpss_htaccess_data .= "</Files>\n";
	$wpss_htaccess_fp = @fopen( $wpss_log_files[3],'a+' );
	@fwrite( $wpss_htaccess_fp, $wpss_htaccess_data );
	@fclose( $wpss_htaccess_fp );
	/* 6 - If $get_fws (File Writeable Status), repeat #3 again and return status */
	if ( !empty( $get_fws ) ) {
		clearstatcache();
		$wpss_log_perms = array(); /* File permissions */
		foreach ( $wpss_log_files as $f => $file ) { $wpss_log_perms[] = substr(sprintf('%o', fileperms($file)), -4); }
		foreach ( $wpss_log_perlm as $p => $perlm ) {
			if ( $wpss_log_perms[$p] < $perlm || !is_writable( $wpss_log_files[$p] ) ) { return FALSE; } 
			}
		return TRUE;
		}

	}

function spamshield_update_session_data( $spamshield_options, $extra_data = NULL ) {
	/* $_SESSION['wpss_spamshield_options_'.RSMP_HASH] 	= $spamshield_options; */
	$_SESSION['wpss_version_'.RSMP_HASH] 				= WPSS_VERSION;
	$_SESSION['wpss_site_url_'.RSMP_HASH_ALT] 			= RSMP_SITE_URL;
	$_SESSION['wpss_plugin_url_'.RSMP_HASH_ALT] 		= WPSS_PLUGIN_URL;
	$_SESSION['wpss_user_ip_current_'.RSMP_HASH]		= $_SERVER['REMOTE_ADDR'];
	$_SESSION['wpss_user_agent_current_'.RSMP_HASH] 	= spamshield_get_user_agent();
	/* First Referrer - Where Visitor Entered Site */
	if ( !empty( $_SERVER['HTTP_REFERER'] ) && empty( $_SESSION['wpss_referer_init_'.RSMP_HASH] ) ) { $_SESSION['wpss_referer_init_'.RSMP_HASH] = spamshield_get_referrer(); }
	}

function spamshield_get_key_values( $ignore_cache = FALSE ) {
	/***
	* Get the Cookie and JS Key Values
	* Default is dynamically generate keys tied to website and session.
	* Checks caching status and serves static keys for JS form fields if caching enabled.
	* Param $ignore_cache can be used to skip the cache check, for non-cached pages like registration form.
	***/
	$wpss_session_id = @session_id();
	/* Cookie key - dynamic */
	$wpss_ck_key_phrase		= 'wpss_ckkey_'.RSMP_SERVER_IP_NODOT.'_'.$wpss_session_id;
	$wpss_ck_val_phrase		= 'wpss_ckval_'.RSMP_SERVER_IP_NODOT.'_'.$wpss_session_id;
	$wpss_ck_key 			= spamshield_md5( $wpss_ck_key_phrase );
	$wpss_ck_val 			= spamshield_md5( $wpss_ck_val_phrase );
	/* JavaScript key - dynamic */
	$wpss_js_key_phrase		= 'wpss_jskey_'.RSMP_SERVER_IP_NODOT.'_'.$wpss_session_id;
	$wpss_js_val_phrase		= 'wpss_jsval_'.RSMP_SERVER_IP_NODOT.'_'.$wpss_session_id;
	$wpss_js_key 			= spamshield_md5( $wpss_js_key_phrase );
	$wpss_js_val 			= spamshield_md5( $wpss_js_val_phrase );
	/* JavaScript key - static */
	$wpss_cache_status		= 'NOT CHECKED';
	if ( empty( $ignore_cache ) ) {
		$wpss_js_ke2_phrase	= 'wpss_jske2_'.RSMP_SERVER_IP_NODOT.'_'.WPSS_REF2XJS;
		$wpss_js_va2_phrase	= 'wpss_jsva2_'.RSMP_SERVER_IP_NODOT.'_'.WPSS_REF2XJS;
		$wpss_cache_check	= spamshield_check_cache_status();
		$wpss_cache_status	= $wpss_cache_check['cache_check_status'];
		if ( $wpss_cache_status == 'ACTIVE' ) {
			$wpss_js_key 	= spamshield_md5( $wpss_js_ke2_phrase );
			$wpss_js_val 	= spamshield_md5( $wpss_js_va2_phrase );
			}
		}
	/* Store and return keys and values */
	$wpss_key_values = array(
		'wpss_ck_key'			=> $wpss_ck_key,
		'wpss_ck_val'			=> $wpss_ck_val,
		'wpss_js_key'			=> $wpss_js_key,
		'wpss_js_val'			=> $wpss_js_val,
		'cache_check_status'	=> $wpss_cache_status,
		);
	return $wpss_key_values;
	}

function spamshield_append_log_data( $str = NULL, $rsds_only = FALSE ) {
	/***
	* Adds data to the log for debugging - only use when Debugging - Use with WP_DEBUG & WPSS_DEBUG
	* Example:
	* spamshield_append_log_data( "\n".'$wpss_example_variable: "'.$wpss_example_variable.'" Line: '.__LINE__.' | '.__FUNCTION__.' | '.__FILE__, TRUE );
	* spamshield_append_log_data( "\n".'$wpss_example_variable: "'.$wpss_example_variable.'" Line: '.__LINE__.' | '.__FUNCTION__.' | MEM USED: ' . spamshield_wp_memory_used() . ' | VER: ' . WPSS_VERSION, TRUE );
	* spamshield_append_log_data( "\n".'$wpss_example_variable: "'.$wpss_example_variable.'" Line: '.__LINE__.' | '.__FUNCTION__.' | MEM USED: ' . spamshield_wp_memory_used() . ' | VER: ' . WPSS_VERSION . ' | BACKTRACE: ' . wp_debug_backtrace_summary(), TRUE );
	* spamshield_append_log_data( "\n".'[A]$wpss_example_array_var: "'.serialize($wpss_example_array_var).'" Line: '.__LINE__.' | '.__FUNCTION__.' | MEM USED: ' . spamshield_wp_memory_used() . ' | VER: ' . WPSS_VERSION, TRUE );
	* spamshield_append_log_data( "\n".'Line: '.__LINE__.' | '.__FUNCTION__.' | MEM USED: ' . spamshield_wp_memory_used() . ' | VER: ' . WPSS_VERSION, TRUE );
	* spamshield_append_log_data( "\n".'Ver '.WPSS_VERSION.' Changelog: Line '.__LINE__.' ADDED|MODDED|DELETED '.__FUNCTION__.'()', TRUE );
	***/
	if ( WP_DEBUG === TRUE && WPSS_DEBUG === TRUE ) {
		if ( !empty( $rsds_only ) && strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) !== 0 ) { return; }
		$wpss_log_str = 'WP-SpamShield DEBUG: ['.$_SERVER['REMOTE_ADDR'].'] '.str_replace("\n", "", $str);
		error_log( $wpss_log_str, 0 ); /* Logs to debug.log */
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
	if ( !empty( $_SESSION[$key_ip_hist] ) ) { $wpss_ip_history = implode(', ', $_SESSION[$key_ip_hist]); } else { $wpss_ip_history = $noda; }
	if ( !empty( $_SESSION[$key_init_mt] ) ) { $wpss_time_init = $_SESSION[$key_init_mt]; } else { $wpss_time_init = ''; }
	$ck_key_init_dt 	= 'NCS_INENTIM'; /* Initial Entry Time - PHP Cookie is backup to session var */
	$ck_key_init_dt_js 	= 'JCS_INENTIM'; /* Initial Entry Time - JS Cookie is backup to PHP Cookie */
	if ( !empty( $_COOKIE[$ck_key_init_dt] ) ) { $wpss_ck_timestamp_init_int = $_COOKIE[$ck_key_init_dt]; }
	elseif ( !empty( $_COOKIE[$ck_key_init_dt_js] ) ) { $wpss_ck_timestamp_init_int = round($_COOKIE[$ck_key_init_dt_js]/1000); }
	else { $wpss_ck_timestamp_init_int = ''; }
	if ( !empty( $_SESSION[$key_init_dt] ) ) { $wpss_timestamp_init = $_SESSION[$key_init_dt]; }
	elseif ( !empty( $wpss_ck_timestamp_init_int ) ) { $wpss_timestamp_init = $wpss_ck_timestamp_init_int; } else { $wpss_timestamp_init = ''; }
	if ( !empty( $_SESSION[$key_first_ref] ) ) { $wpss_referer_init = $_SESSION[$key_first_ref]; } else { $wpss_referer_init = $noda; }
	$wpss_referer_init_js = spamshield_get_referrer( FALSE, TRUE, TRUE ); /* Initial referrer, aka "Referring Site" - Changed 1.7.9 */
	if ( empty( $wpss_referer_init_js ) ) { $wpss_referer_init_js = $noda; }
	if ( !empty( $_SESSION[$key_auth_hist] ) ) { $wpss_author_history = implode(', ', $_SESSION[$key_auth_hist]); }
	elseif ( !empty( $_COOKIE[$key_comment_auth] ) ) { $wpss_author_history = $_COOKIE[$key_comment_auth]; }
	else { $wpss_author_history = $noda; }
	if ( !empty( $_SESSION[$key_email_hist] ) ) { $wpss_author_email_history = implode(', ', $_SESSION[$key_email_hist]); }
	elseif ( !empty( $_COOKIE[$key_comment_email] ) ) { $wpss_author_email_history = $_COOKIE[$key_comment_email]; } 
	else { $wpss_author_email_history = $noda; }
	if ( !empty( $_SESSION[$key_auth_url_hist] ) ) { $wpss_author_url_history = implode(', ', $_SESSION[$key_auth_url_hist]); }
	elseif ( !empty( $_COOKIE[$key_comment_url] ) ) { $wpss_author_url_history = $_COOKIE[$key_comment_url]; } 
	else { $wpss_author_url_history = $noda; }
	if ( !empty( $_SESSION[$key_comment_acc] ) ) { $wpss_comments_accepted = $_SESSION[$key_comment_acc]; } else { $wpss_comments_accepted = $noda; }
	if ( !empty( $_SESSION[$key_comment_den] ) ) { $wpss_comments_denied = $_SESSION[$key_comment_den]; } else { $wpss_comments_denied = $noda; }
	/* Current status */
	if ( !empty( $_SESSION[$key_comment_stat_curr] ) ) { $wpss_comments_status_current = $_SESSION[$key_comment_stat_curr]; } else { $wpss_comments_status_current = $noda; }
	if ( !empty( $_SESSION[$key_append_log_data] ) ) { $wpss_append_log_data = $_SESSION[$key_append_log_data]; } else { $wpss_append_log_data = $noda; }
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
		'wpss_referer_init_js'			=> $wpss_referer_init_js,
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

function spamshield_log_data( $wpss_log_comment_data_array, $wpss_log_comment_data_errors, $wpss_log_comment_type = 'comment', $wpss_log_contact_form_data = NULL, $wpss_log_contact_form_id = NULL, $wpss_log_contact_form_mcid = NULL ) {
	/***
	* Example:
	* Comment:				spamshield_log_data( $commentdata, $wpss_error_code )
	* Contact Form:			spamshield_log_data( $contact_form_author_data, $wpss_error_code, 'contact form', $wpss_contact_form_msg, $wpss_contact_form_mid, $wpss_contact_form_mcid );
	* Registration:			spamshield_log_data( $register_author_data, $wpss_error_code, 'register' );
	***/
	
	spamshield_log_reset( NULL, FALSE, FALSE, TRUE ); /* Create log file if it doesn't exist */

	$wpss_log_filename = 'temp-comments-log.txt';
	$wpss_log_empty_filename = 'temp-comments-log.init.txt';
	$wpss_log_file = WPSS_PLUGIN_DATA_PATH.'/'.$wpss_log_filename;
	$wpss_log_max_filesize = 2*1048576; /* 2 MB */

	if ( empty( $wpss_log_comment_type) ) { $wpss_log_comment_type = 'comment'; }
	$wpss_log_comment_type_display 			= spamshield_casetrans('upper',$wpss_log_comment_type);
	$wpss_log_comment_type_ucwords			= spamshield_casetrans('ucwords',$wpss_log_comment_type);
	$wpss_log_comment_type_ucwords_ref_disp	= preg_replace("~\sform~i", "", $wpss_log_comment_type_ucwords);

	$noda = '[No Data]';
	$wpss_display_name = ''; $wpss_user_firstname = ''; $wpss_user_lastname = ''; $wpss_user_email = ''; $wpss_user_url = ''; $wpss_user_login = ''; $wpss_user_id = '';
	$wpss_user_logged_in 		= FALSE;
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
		$wpss_user_logged_in	= TRUE;
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
	$wpss_referer_init_js			= $wpss_log_session_data['wpss_referer_init_js'];
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
	if ( empty( $wpss_time_init ) && !empty( $wpss_timestamp_init ) ) {
		$wpss_time_init = $wpss_timestamp_init;
		}
	if ( !empty( $wpss_time_init ) ) {
		$wpss_time_on_site			= spamshield_timer( $wpss_time_init, $wpss_time_end, TRUE, 2 );
		}
	else { $wpss_time_on_site 		= $noda; }
	if ( !empty( $wpss_timestamp_init ) ) {
		$wpss_site_entry_time		= get_date_from_gmt( date( 'Y-m-d H:i:s', $wpss_timestamp_init ), 'Y-m-d (D) H:i:s e' ); /* Added 1.7.3 */
		}
	else { $wpss_site_entry_time 	= $noda; }

	$comment_logging 				= $spamshield_options['comment_logging'];
	$comment_logging_start_date 	= $spamshield_options['comment_logging_start_date'];
	$comment_logging_all 			= $spamshield_options['comment_logging_all'];
	if ( !empty( $wpss_log_comment_data_array['javascript_page_referrer'] ) ) {
		$wpss_javascript_page_referrer = $wpss_log_comment_data_array['javascript_page_referrer'];
		}
	else { $wpss_javascript_page_referrer = ''; }
	if ( !empty( $wpss_log_comment_data_array['jsonst'] ) ) {
		$wpss_jsonst		 		= $wpss_log_comment_data_array['jsonst'];
		}
	else { $wpss_jsonst = ''; }
	$get_current_time = time();
	/* Updated next line in Version 1.1.4.4 - Display local time in logs. Won't match other time logs, because those need to be UTC. */
	$get_current_time_display = current_time( 'timestamp', 0 );
	$reset_interval_hours = 24 * 7; /* Reset interval in hours */
	$reset_interval_minutes = 60; /* Reset interval minutes default */
	$reset_interval_minutes_override = $reset_interval_minutes; /* Use as override for testing; leave = $reset_interval_minutes when not testing */
	if ( $reset_interval_minutes_override != $reset_interval_minutes ) {
		$reset_interval_hours = 1;
		$reset_interval_minutes = $reset_interval_minutes_override;
		}
	/* Default is one week */
	$reset_interval = 60 * $reset_interval_minutes * $reset_interval_hours; /* seconds * minutes * hours */
	if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) { $reset_interval = $reset_interval * 4; }
	$time_threshold = $get_current_time - $reset_interval;
	/* This turns off if over x amount of time since starting, or filesize exceeds max */
	if ( ( !empty( $comment_logging_start_date ) && $time_threshold > $comment_logging_start_date ) || ( file_exists( $wpss_log_file ) && filesize( $wpss_log_file ) >= $wpss_log_max_filesize ) ) {
		$comment_logging = $comment_logging_start_date = $comment_logging_all = 0; /* Turns logging off */
		$spamshield_options = array (
			'block_all_trackbacks' 					=> $spamshield_options['block_all_trackbacks'],
			'block_all_pingbacks' 					=> $spamshield_options['block_all_pingbacks'],
			'comment_logging'						=> $comment_logging,
			'comment_logging_start_date'			=> $comment_logging_start_date,
			'comment_logging_all'					=> $comment_logging_all,
			'enhanced_comment_blacklist'			=> $spamshield_options['enhanced_comment_blacklist'],
			'enable_whitelist'						=> $spamshield_options['enable_whitelist'],
			'allow_proxy_users'						=> $spamshield_options['allow_proxy_users'],
			'hide_extra_data'						=> $spamshield_options['hide_extra_data'],
			'js_head_disable'						=> $spamshield_options['js_head_disable'],
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
		update_option( 'spamshield_options', $spamshield_options );
		}
	else {
		/* LOG DATA */
		$wpss_log_datum = date('Y-m-d (D) H:i:s',$get_current_time_display);
		$wpss_log_comment_data = "*************************************************************************************\n";
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		$wpss_log_comment_data .= ":: ".$wpss_log_comment_type_display." BEGIN ::"."\n";

		$submitter_ip_address = $_SERVER['REMOTE_ADDR'];
		$submitter_ip_address_short_l = trim( substr( $submitter_ip_address, 0, 6) );
		$submitter_ip_address_short_r = trim( substr( $submitter_ip_address, -6, 2) );
		$submitter_ip_address_obfuscated = $submitter_ip_address_short_l.'****'.$submitter_ip_address_short_r.'.***';

		/* IP / PROXY INFO - BEGIN */
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
		$reverse_dns_verification 		= $ip_proxy_info['reverse_dns_verification'];
		$ip_proxy_via 					= $ip_proxy_info['ip_proxy_via'];
		$ip_proxy_via_lc 				= $ip_proxy_info['ip_proxy_via_lc'];
		$masked_ip 						= $ip_proxy_info['masked_ip'];
		$ip_proxy 						= $ip_proxy_info['ip_proxy'];
		$ip_proxy_short 				= $ip_proxy_info['ip_proxy_short'];
		$ip_proxy_data 					= $ip_proxy_info['ip_proxy_data'];
		$proxy_status 					= $ip_proxy_info['proxy_status'];
		$ip_proxy_chrome_compression	= $ip_proxy_info['ip_proxy_chrome_compression'];
		/* IP / PROXY INFO - END */

		$wpss_spamshield_count = spamshield_number_format( spamshield_count() );
		if ( $wpss_log_comment_type == 'register' ) { $body_content_length = ''; }
		else {
			$body_content_length = spamshield_number_format( $wpss_log_comment_data_array['body_content_len'] );
			}
		if ( $wpss_log_comment_type == 'comment' ) {
			$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
			/* Comment Post Info */
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
			$comment_post_type_ucw = spamshield_casetrans('ucwords',$wpss_log_comment_data_array['comment_post_type']);

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
			$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
			$wpss_log_comment_data .= "WPSSCID: 		['".$wpss_log_comment_data_array['comment_wpss_cid']."']\n"; /* Added 1.7.7 - WPSS Comment ID */
			$wpss_log_comment_data .= "WPSSCCID:		['".$wpss_log_comment_data_array['comment_wpss_ccid']."']\n"; /* Added 1.7.7 - WPSS Comment Content ID */
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
			$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
			$wpss_log_comment_data .= "WPSSMID: 		['".$wpss_log_contact_form_id."']\n"; /* Added 1.7.7 - WPSS Message ID */
			$wpss_log_comment_data .= "WPSSMCID:		['".$wpss_log_contact_form_mcid."']\n"; /* Added 1.7.7 - WPSS Message Content ID */
			}
		$wpss_sessions_enabled = isset( $_SESSION ) ? 'Enabled' : 'Disabled';

		/* Sanitized versions for output */
		$wpss_http_accept_language 		= spamshield_get_http_accept( FALSE, FALSE, TRUE );
		$wpss_http_accept				= spamshield_get_http_accept();
		$wpss_http_user_agent 			= spamshield_get_user_agent();
		$wpss_http_referer				= spamshield_get_referrer(); /* Not original ref - Comment Processor Referrer */
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		if ( $wpss_user_logged_in != FALSE ) {
			$wpss_log_comment_data .= "User ID: 		['".$wpss_user_id."']\n";
			}
		$wpss_log_comment_data .= "IP Address: 		['".$ip."'] ['http://ipaddressdata.com/".$ip."']\n";
		$wpss_log_comment_data .= "Reverse DNS: 		['".$reverse_dns."']\n";
		$wpss_log_comment_data .= "Reverse DNS IP: 	['".$reverse_dns_ip."']\n";
		$wpss_log_comment_data .= "FCrDNS Verified: 	['".$reverse_dns_verification."']\n"; /* Forward-confirmed reverse DNS (FCrDNS) */
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
			$wpss_log_comment_data .= $wpss_log_comment_type_ucwords_ref_disp." Processor Ref: ['"; /* Adjust spacing */
			}
		else {
			$wpss_log_comment_data .= $wpss_log_comment_type_ucwords_ref_disp." Processor Ref: 	['"; /* Adjust spacing */
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

		/* New Data Section - Begin */
		if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) {
			if ( !empty( $_SESSION ) ) { 	$wpss_log_data_serial_session	= serialize($_SESSION); }	else { $wpss_log_data_serial_session = ''; }
			if ( !empty( $_COOKIE ) ) { 	$wpss_log_data_serial_cookie 	= serialize($_COOKIE); }	else { $wpss_log_data_serial_cookie = ''; }
			if ( !empty( $_GET ) ) { 		$wpss_log_data_serial_get 		= serialize($_GET); }		else { $wpss_log_data_serial_get = ''; }
			if ( !empty( $_POST ) ) {
				$wpss_log_data_post_raw = $_POST;
				switch ( $wpss_log_comment_type ) {
					case 'comment'		: unset( $wpss_log_data_post_raw['comment'] ); break;
					case 'contact form'	: unset( $wpss_log_data_post_raw['wpss_contact_message'] ); break;
					}
				$wpss_log_data_serial_post 	= serialize($wpss_log_data_post_raw);
				}
			else { $wpss_log_data_serial_post = ''; }
			if ( !empty( $_SERVER['REQUEST_METHOD'] ) ) { $wpss_server_request_method = $_SERVER['REQUEST_METHOD']; } else { $wpss_server_request_method = ''; }
			$wpss_mem_used = spamshield_wp_memory_used();
			if ( !empty( $_SESSION['user_spamshield_count_'.RSMP_HASH] ) )		{ $wpss_user_spamshield_count = $_SESSION['user_spamshield_count_'.RSMP_HASH]; }		else { $wpss_user_spamshield_count = 0; }
			if ( !empty( $_SESSION['user_spamshield_count_jsck_'.RSMP_HASH] ) )	{ $wpss_jsck_spamshield_count = $_SESSION['user_spamshield_count_jsck_'.RSMP_HASH]; }	else { $wpss_jsck_spamshield_count = 0; }
			if ( !empty( $_SESSION['user_spamshield_count_algo_'.RSMP_HASH] ) )	{ $wpss_algo_spamshield_count = $_SESSION['user_spamshield_count_algo_'.RSMP_HASH]; }	else { $wpss_algo_spamshield_count = 0; }

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
			$wpss_log_comment_data .= "Site Entry Time: 	['".$wpss_site_entry_time."']\n"; /* Added 1.7.3 */
			$wpss_log_comment_data .= "First Page Hit: 	['".$wpss_referer_init."']\n"; /* PHP */
			$wpss_log_comment_data .= "Original Referrer: 	['".$wpss_referer_init_js."']\n"; /* JS */
			$wpss_log_comment_data .= "Author History:		['".$wpss_author_history."']\n";
			$wpss_log_comment_data .= "Email History:		['".$wpss_author_email_history."']\n";
			$wpss_log_comment_data .= "URL History: 		['".$wpss_author_url_history."']\n";
			$wpss_log_comment_data .= "Entries Accepted: 	['".$wpss_comments_accepted."']\n";
			$wpss_log_comment_data .= "Entries Denied: 	['".$wpss_comments_denied."']\n";
			$wpss_log_comment_data .= "Spam Count:  		['".$wpss_spamshield_count."']\n"; /* Added 1.8 */
			$wpss_log_comment_data .= "User Spam Count:  	['".$wpss_user_spamshield_count."']\n"; /* Added 1.8 */
			$wpss_log_comment_data .= "JSCK Spam Count:  	['".$wpss_jsck_spamshield_count."']\n"; /* Added 1.8.9.6 */
			$wpss_log_comment_data .= "ALGO Spam Count:  	['".$wpss_algo_spamshield_count."']\n"; /* Added 1.8.9.6 */
			$wpss_log_comment_data .= "Current Status: 	['".$wpss_comments_status_current."']\n"; /* Changed 1.8 */
			$wpss_log_comment_data .= "REQUEST_METHOD: 	['".$wpss_server_request_method."']\n";
			if ( $wpss_log_comment_type != 'register' ) {
				$wpss_log_comment_data .= "Content Length: 	['".$body_content_length."']\n";
				}
			$wpss_log_comment_data .= '$_COOKIE'." Data:		['".$wpss_log_data_serial_cookie."']\n";
			$wpss_log_comment_data .= '$_GET'." Data: 		['".$wpss_log_data_serial_get."']\n";
			$wpss_log_comment_data .= 'MOD $_POST'." Data:	['".$wpss_log_data_serial_post."']\n";
			$wpss_log_comment_data .= "CL Active: 		['".$wpss_cl_active."']\n";
			$wpss_log_comment_data .= "Mem Used: 		['".$wpss_mem_used."']\n";
			$wpss_log_comment_data .= "Extra Data: 		['".$wpss_append_log_data."']\n";
			}
		/* New Data Section - End */
		$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
		if ( strpos( $wpss_log_comment_data_errors, 'No Error' ) === 0 ) { /* Changed 1.8 */
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
			/* Timer End - Comment Processing */
			$wpss_end_time_comment_processing 		= spamshield_microtime();
			$wpss_total_time_wpss_processing 		= $wpss_total_time_jsck_filter + $wpss_total_time_content_filter;
			$wpss_total_time_wpss_processing_disp	= spamshield_number_format( $wpss_total_time_wpss_processing, 6 );
			$wpss_total_time_comment_processing 	= spamshield_timer( $wpss_start_time_comment_processing, $wpss_end_time_comment_processing, FALSE, 6, TRUE );
			$wpss_total_time_comment_proc_disp		= spamshield_number_format( $wpss_total_time_comment_processing, 6);
			$wpss_total_time_wp_processing			= $wpss_total_time_comment_processing - $wpss_total_time_wpss_processing;
			$wpss_total_time_wp_processing_disp		= spamshield_number_format( $wpss_total_time_wp_processing, 6 );
			if ( !empty( $wpss_total_time_jsck_filter_disp ) || !empty( $wpss_total_time_content_filter_disp ) || !empty( $wpss_total_time_wpss_processing_disp ) ) {
				$wpss_log_comment_data .= "JS/C Processing Time: 	['".$wpss_total_time_jsck_filter_disp." seconds'] Time for JS/Cookies Layer to test for spam\n";
				$wpss_log_comment_data .= "Algo Processing Time: 	['".$wpss_total_time_content_filter_disp." seconds'] Time for Algorithmic Layer to test for spam\n";
				$wpss_log_comment_data .= "WPSS Processing Time: 	['".$wpss_total_time_wpss_processing_disp." seconds'] Total time for WP-SpamShield to test for spam\n";
				}
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
				$wpss_log_comment_data .= "WP Processing Time:	['".$wpss_total_time_wp_processing_disp." seconds'] Time for other WordPress processes\n";
				$wpss_log_comment_data .= "Total Processing Time: 	['".$wpss_total_time_comment_proc_disp." seconds'] Total time for WordPress to process comment\n";
				$wpss_log_comment_data .= "Avg WPSS Proc Time:	['".$wpss_proc_data_avg_wpss_proc_time_disp." seconds'] Average total time for WP-SpamShield to test for spam\n";
				$wpss_log_comment_data .= "FAvg WPSS Proc Time:	['".$wpss_proc_data_avg2_wpss_proc_time_disp." seconds'] Fuzzy Average total WPSS time\n";
				$wpss_log_comment_data .= "Avg Total Proc Time:	['".$wpss_proc_data_avg_comment_proc_time_disp." seconds'] Average total time for WordPress to process comments\n";
				}
			$wpss_log_comment_data .= "-------------------------------------------------------------------------------------\n";
			}
		$wpss_log_comment_data .= "Failed Tests: 		['".$wpss_log_comment_data_errors_count."']\n";
		$wpss_log_comment_data .= "Failed Test Codes: 	['".$wpss_log_comment_data_errors."']\n";
		$wpss_log_comment_data .= "Spam Count:  		['".$wpss_spamshield_count."']\n"; /* Added 1.8 */
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

		$wpss_log_fp = @fopen( $wpss_log_file,'a+' );
		@fwrite( $wpss_log_fp, $wpss_log_comment_data );
		@fclose( $wpss_log_fp );
		}
	}

function spamshield_comment_form_addendum() {
	$spamshield_options 	= get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);
	$promote_plugin_link 	= $spamshield_options['promote_plugin_link'];
	$wpss_key_values 		= spamshield_get_key_values();
	$wpss_js_key 			= $wpss_key_values['wpss_js_key'];
	$wpss_js_val 			= $wpss_key_values['wpss_js_val'];

	echo "\n".'<script type=\'text/javascript\'>'."\n".'// <![CDATA['."\n".WPSS_REF2XJS.'=escape(document[\'referrer\']);'."\n".'hf1N=\''.$wpss_js_key.'\';'."\n".'hf1V=\''.$wpss_js_val.'\';'."\n".'document.write("<input type=\'hidden\' name=\''.WPSS_REF2XJS.'\' value=\'"+'.WPSS_REF2XJS.'+"\' /><input type=\'hidden\' name=\'"+hf1N+"\' value=\'"+hf1V+"\' />");'."\n".'// ]]>'."\n".'</script>';
	echo '<noscript><input type="hidden" name="'.WPSS_JSONST.'" value="NS1" /></noscript>'."\n";

	if ( !empty( $promote_plugin_link ) ) {
		$sip5c = '0'; $sip5c = substr(RSMP_SERVER_ADDR, 4, 1); /* Server IP 5th Char */
		$ppl_code = array( '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '.' => 10 );
		if ( preg_match( "~^[0-9\.]$~", $sip5c ) ) { $int = $ppl_code[$sip5c]; } else { $int = 0; }
		echo spamshield_comment_promo_link($int)."\n";
		}
	$wpss_js_disabled_msg 	= __( 'Currently you have JavaScript disabled. In order to post comments, please make sure JavaScript and Cookies are enabled, and reload the page.', WPSS_PLUGIN_NAME );
	$wpss_js_enable_msg 	= __( 'Click here for instructions on how to enable JavaScript in your browser.', WPSS_PLUGIN_NAME );
	echo '<noscript><p><strong>'.$wpss_js_disabled_msg.'</strong> <a href="http://enable-javascript.com/" rel="nofollow external" >'.$wpss_js_enable_msg.'</a></p></noscript>'."\n";

	/* If need to add anything else to comment area, start here */

	}

function spamshield_get_author_cookie_data() {
	/* Get Comment Author Data Stored in Cookies */
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
	/* Get Comment Author Data Stored in Cookies and Session Vars */
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

function spamshield_update_accept_status( $commentdata, $status = 'r', $line = NULL, $error_code = NULL ) {
	$get_current_time_display = current_time( 'timestamp', 0 );
	$wpss_datum 			= date('Y-m-d (D) H:i:s',$get_current_time_display);
	$key_comment_acc 		= 'wpss_comments_accepted_'.RSMP_HASH;
	$key_comment_den 		= 'wpss_comments_denied_'.RSMP_HASH;
	$key_comment_den_jsck	= 'wpss_comments_denied_jsck_'.RSMP_HASH;
	$key_comment_den_algo	= 'wpss_comments_denied_algo_'.RSMP_HASH;
	$key_comment_stat_curr	= 'wpss_comments_status_current_'.RSMP_HASH;
	$key_auth_hist 			= 'wpss_author_history_'.RSMP_HASH;
	$key_email_hist 		= 'wpss_author_email_history_'.RSMP_HASH;
	$key_auth_url_hist 		= 'wpss_author_url_history_'.RSMP_HASH;
	if ( empty( $_SESSION[$key_comment_acc] ) ) 		{ $_SESSION[$key_comment_acc] = 0; }
	if ( empty( $_SESSION[$key_comment_den] ) ) 		{ $_SESSION[$key_comment_den] = 0; }
	if ( empty( $_SESSION[$key_comment_den_jsck] ) )	{ $_SESSION[$key_comment_den_jsck] = 0; }
	if ( empty( $_SESSION[$key_comment_den_algo] ) )	{ $_SESSION[$key_comment_den_algo] = 0; }
	if ( !empty( $line ) ) { $line .= ' '; }
	if ( $status == 'r' ) {
		++$_SESSION[$key_comment_den];
		$_SESSION[$key_comment_stat_curr] = '[REJECTED '.$line.$wpss_datum.']';
		$error_type = spamshield_get_error_type( $error_code ); /* 1.8.9.6 */
		spamshield_increment_count( $error_type ); /* 1.8 */
		}
	elseif ( $status == 'a' ) {
		++$_SESSION[$key_comment_acc];
		$_SESSION[$key_comment_stat_curr] = '[ACCEPTED '.$line.$wpss_datum.']';
		$_SESSION['user_spamshield_count_'.RSMP_HASH] = 0; /* 1.8 */
		$_SESSION['user_spamshield_count_jsck_'.RSMP_HASH] = 0; /* 1.8.9.6 */
		$_SESSION['user_spamshield_count_algo_'.RSMP_HASH] = 0;
		}
	else { $_SESSION[$key_comment_stat_curr] = '[ERROR '.$line.' '.$wpss_datum.']'; }
	if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) !== 0 ) { $_SESSION[$key_comment_stat_curr] = ''; }
	$wpss_comment_author 		= $commentdata['comment_author'];
	$wpss_comment_author_email	= $commentdata['comment_author_email'];
	$wpss_comment_author_url 	= $commentdata['comment_author_url'];
	if ( empty ( $wpss_comment_author ) ) 		{ $wpss_comment_author 			= ''; }
	if ( empty ( $wpss_comment_author_email ) ) { $wpss_comment_author_email 	= ''; }
	if ( empty ( $wpss_comment_author_url ) ) 	{ $wpss_comment_author_url 		= ''; }
	$_SESSION['wpss_comment_author_'.RSMP_HASH] = $wpss_comment_author;
	if ( empty( $_SESSION[$key_auth_hist] ) ) { $_SESSION[$key_auth_hist] = array(); }
	$_SESSION[$key_auth_hist][] = $wpss_comment_author;
	$_SESSION['wpss_comment_author_email_'.RSMP_HASH] = $wpss_comment_author_email;
	if ( empty( $_SESSION[$key_email_hist] ) ) { $_SESSION[$key_email_hist] = array(); }
	$_SESSION[$key_email_hist][] = $wpss_comment_author_email;
	$_SESSION['wpss_comment_author_url_'.RSMP_HASH] = $wpss_comment_author_url;
	if ( empty( $_SESSION[$key_auth_url_hist] ) ) { $_SESSION[$key_auth_url_hist] = array(); }
	$_SESSION[$key_auth_url_hist][] = $wpss_comment_author_url;
	/***
	* if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) { $_SESSION['wpss_commentdata_'.RSMP_HASH] = $commentdata; }
	* To pass the $commentdata values through SESSION vars to denied_post functions because WP hook won't allow us to.
	***/
	$_SESSION['wpss_commentdata_'.RSMP_HASH] = $commentdata;
	}

function spamshield_contact_shortcode( $atts = array() ) {
	/* Implementation: [spamshieldcontact] */
	if ( is_page() && in_the_loop() && ( !is_home() && !is_feed() && !is_archive() && !is_search() && !is_404() ) ) { 
		$shortcode_check = 'shortcode';
		$content_new_shortcode = spamshield_contact_form('',$shortcode_check);
		return $content_new_shortcode;
		}
	else {
		return NULL;
		}
	}

function spamshield_contact_form( $content = NULL, $shortcode_check = NULL ) {
	$spamshield_contact_repl_text = array( '<!--spamshield-contact-->', '<!--spamfree-contact-->' );

	$server_name 					= RSMP_SERVER_NAME;
	if ( substr( $server_name , 0, 4 ) == 'www.' ) { $server_name = substr( $server_name, 4 ); } /* Get rid of 'www'
	* Get rid of 'www' and such - Preferred Version
	* $server_name 					= preg_replace( "~^(ww[w0-9]|m)\.~i", '', $server_name );
	***/
	$wpss_contact_sender_email		= 'wpspamshield.noreply@'.$server_name;
	$wpss_contact_sender_name		= __( 'Contact Form', WPSS_PLUGIN_NAME );

	/* IP / PROXY INFO SHORT - BEGIN */
	global $wpss_ip_proxy_info;
	if ( empty( $wpss_ip_proxy_info ) ) {
		$wpss_ip_proxy_info 		= spamshield_ip_proxy_info();
		}
	$ip_proxy_info					= $wpss_ip_proxy_info;
	$ip 							= $ip_proxy_info['ip'];
	$reverse_dns 					= $ip_proxy_info['reverse_dns'];
	$reverse_dns_ip					= $ip_proxy_info['reverse_dns_ip'];
	$reverse_dns_ip_regex 			= $ip_proxy_info['reverse_dns_ip_regex'];
	$reverse_dns_lc					= $ip_proxy_info['reverse_dns_lc'];
	$reverse_dns_lc_regex 			= $ip_proxy_info['reverse_dns_lc_regex'];
	$reverse_dns_lc_rev 			= $ip_proxy_info['reverse_dns_lc_rev'];
	$reverse_dns_lc_rev_regex 		= $ip_proxy_info['reverse_dns_ip_regex'];
	/* IP / PROXY INFO SHORT - END */
	$user_agent 					= spamshield_get_user_agent( TRUE, FALSE );
	$user_agent_lc 					= spamshield_casetrans('lower',$user_agent);
	$user_agent_lc_word_count 		= spamshield_count_words($user_agent_lc);
	$user_http_accept 				= spamshield_get_http_accept( TRUE, FALSE );
	$user_http_accept_lc			= spamshield_casetrans('lower',$user_http_accept);
	$user_http_accept_language		= spamshield_get_http_accept( TRUE, FALSE, TRUE );
	$user_http_accept_language_lc	= spamshield_casetrans('lower',$user_http_accept_language);
	$spamshield_contact_form_url	= $_SERVER['REQUEST_URI'];
	$spamshield_contact_form_url_lc	= spamshield_casetrans('lower',$spamshield_contact_form_url);
	/* Detect Incapsula, and disable spamshield_ubl_cache - 1.8.9.6 */
	if ( strpos( $reverse_dns_lc, '.ip.incapdns.net' ) !== FALSE ) { update_option( 'spamshield_ubl_cache_disable', TRUE ); }
	/* Moved Back URL here to make available to rest of contact form back end - v 1.5.5 */
	if ( strpos( $spamshield_contact_form_url_lc, '&form=response' ) !== FALSE ) {
		$spamshield_contact_form_back_url = str_replace('&form=response','',$spamshield_contact_form_url );
		}
	elseif ( strpos( $spamshield_contact_form_url_lc, '?form=response' ) !== FALSE ) {
		$spamshield_contact_form_back_url = str_replace('?form=response','',$spamshield_contact_form_url );
		}
	if ( !empty( $_SERVER['QUERY_STRING'] ) ) { $spamshield_contact_form_query_op = '&amp;'; } else { $spamshield_contact_form_query_op = '?'; }
	if ( !empty( $_GET['form'] ) ) { $get_form = $_GET['form']; } else { $get_form = ''; }
	if ( !empty( $_POST[WPSS_JSONST] ) ) { $post_jsonst = $_POST[WPSS_JSONST]; } else { $post_jsonst = ''; }
	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }
	$post_ref2xjs_lc	= spamshield_casetrans( 'lower', $post_ref2xjs );
	$ref2xjs_lc			= spamshield_casetrans( 'lower', WPSS_REF2XJS );
	$wpss_error_code = $spamshield_contact_form_content = '';
	if ( is_page() && in_the_loop() && ( !is_home() && !is_feed() && !is_archive() && !is_search() && !is_404() ) ) { /* Modified 1.7.7 */
		/* MAKE SURE WE ONLY SHOW THE FORM IN THE RIGHT PLACE */
		$spamshield_options						= get_option('spamshield_options');
		$wpss_key_values 						= spamshield_get_key_values();
		$wpss_ck_key  							= $wpss_key_values['wpss_ck_key'];
		$wpss_ck_val 							= $wpss_key_values['wpss_ck_val'];
		$wpss_js_key 							= $wpss_key_values['wpss_js_key'];
		$wpss_js_val 							= $wpss_key_values['wpss_js_val'];
		$wpss_cache_status						= $wpss_key_values['cache_check_status'];
		if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wpss_jsck_cookie_val = $_COOKIE[$wpss_ck_key]; } else { $wpss_jsck_cookie_val = ''; }
		if ( !empty( $_POST[$wpss_js_key] ) ) { $wpss_jsck_field_val 	= $_POST[$wpss_js_key]; } else { $wpss_jsck_field_val = ''; }
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
		$form_response_thank_you_message		= trim(stripslashes($spamshield_options['form_response_thank_you_message']));
		$form_include_user_meta					= $spamshield_options['form_include_user_meta'];
		$form_include_user_meta_hide_ext_data	= $spamshield_options['hide_extra_data'];
		$promote_plugin_link					= $spamshield_options['promote_plugin_link'];

		$form_require_website_sess_ovr			= 0; /* SESSION Override - Added 1.7.8 */
		if ( !empty( $_SESSION['form_require_website_'.RSMP_HASH] ) ) { $form_require_website_sess_ovr = 1; } else { $_SESSION['form_require_website_'.RSMP_HASH] = 0; }
		if ( empty( $form_require_website ) && !empty( $form_require_website_sess_ovr ) ) { $form_require_website = 1; }

		if ( $form_message_width < 40 ) { $form_message_width = 40; }
		if ( $form_message_height < 5 ) { $form_message_height = 5; } elseif ( empty( $form_message_height ) ) { $form_message_height = 10; }
		if ( $form_message_min_length < 15 ) { $form_message_min_length = 15; } elseif ( empty( $form_message_min_length ) ) { $form_message_min_length = 25; }
		$form_message_max_length				= 25600; /* 25kb */

		if ( $get_form == 'response' && ( $_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST) ) ) {
			/***
			* 1 - PRE-CHECK FOR BLANK FORMS
			* REQUEST_METHOD not POST or empty $_POST - Not a legitimate contact form submission - likely a bot scraping the site
			* Added in v 1.5.5 to conserve server resources
			***/
			$error_txt = spamshield_error_txt();
			$wpss_error = $error_txt.':';
			$spamshield_contact_form_content = '<p><strong>'.$wpss_error.' ' . __( 'Please return to the contact form and fill out all required fields.', WPSS_PLUGIN_NAME ) . '</strong></p><p>&nbsp;</p>'."\n";
			$content_new = str_replace($content, $spamshield_contact_form_content, $content);
			$content_shortcode = $spamshield_contact_form_content;
			}
		elseif ( $get_form == 'response' ) {
			/***
			* 2 - RESPONSE PAGE - FORM HAS BEEN SUBMITTED
			* CONTACT FORM BACK END - BEGIN
			***/
			$wpss_whitelist = $wp_blacklist = $message_spam = $blank_field = $invalid_value = $restricted_email = $bad_email = $bad_phone = $bad_company = $message_short = $message_long = $cf_jsck_error = $cf_badrobot_error = $contact_form_spam_loc = $contact_form_domain_spam_loc = $generic_spam_company = $free_email_address = 0;
			$combo_spam_signal_1 = $combo_spam_signal_2  = $combo_spam_signal_3 = $bad_phone_spammer = 0;
			$wpss_user_blacklisted_prior_cf = 0;
			/* TO DO: Add here */

			/* PROCESSING CONTACT FORM - BEGIN */
			$wpss_contact_name = $wpss_contact_email = $wpss_contact_website = $wpss_contact_phone = $wpss_contact_company = $wpss_contact_drop_down_menu = $wpss_contact_subject = $wpss_contact_message = $wpss_raw_contact_message = '';

			$wpss_contact_time 					= spamshield_microtime(); /* Added in v1.6 */
			$contact_form_author_data			= array();

			if ( !empty( $_POST['wpss_contact_name'] ) ) {
				$wpss_contact_name 				= sanitize_text_field($_POST['wpss_contact_name']);
				}
			if ( !empty( $_POST['wpss_contact_email'] ) ) {
				$wpss_contact_email 			= sanitize_email($_POST['wpss_contact_email']);
				}
			$wpss_contact_email_lc 				= spamshield_casetrans( 'lower', $wpss_contact_email );
			$wpss_contact_email_lc_rev 			= strrev( $wpss_contact_email_lc );
			if ( !empty( $_POST['wpss_contact_website'] ) ) {
				$wpss_contact_website 			= esc_url_raw($_POST['wpss_contact_website']);
				}
			$wpss_contact_website_lc 			= spamshield_casetrans( 'lower', $wpss_contact_website );
			$wpss_contact_domain 				= spamshield_get_domain( $wpss_contact_website_lc );
			$wpss_contact_domain_rev 			= strrev( $wpss_contact_domain );
			if ( !empty( $_POST['wpss_contact_phone'] ) ) {
				$wpss_contact_phone 			= sanitize_text_field($_POST['wpss_contact_phone']);
				}
			if ( !empty( $_POST['wpss_contact_company'] ) ) {
				$wpss_contact_company 			= sanitize_text_field($_POST['wpss_contact_company']);
				}
			$wpss_contact_company_lc			= spamshield_casetrans( 'lower', $wpss_contact_company );
			$wpss_common_spam_countries			= array('india','china','russia','ukraine','pakistan','turkey'); /* Most common sources of human spam */
			$wpss_contact_company_lc_nc			= trim( str_replace( $wpss_common_spam_countries, '', $wpss_contact_company_lc ) ); /* Remove country names for testing */

			if ( !empty( $_POST['wpss_contact_drop_down_menu'] ) ) {
				$wpss_contact_drop_down_menu	= sanitize_text_field($_POST['wpss_contact_drop_down_menu']);
				}
			if ( !empty( $_POST['wpss_contact_subject'] ) ) {
				$wpss_contact_subject 			= sanitize_text_field($_POST['wpss_contact_subject']);
				}
			$wpss_contact_subject_lc 			= spamshield_casetrans( 'lower', $wpss_contact_subject );
			if ( !empty( $_POST['wpss_contact_message'] ) ) {
				$wpss_contact_message 			= sanitize_text_field($_POST['wpss_contact_message']);/* body_content */
				$wpss_raw_contact_message 		= trim($_POST['wpss_contact_message']);/* body_content_unsan */
				}
			$wpss_contact_message_lc 			= spamshield_casetrans('lower',$wpss_contact_message); /* body_content_lc */
			$wpss_raw_contact_message_lc 		= spamshield_casetrans('lower',$wpss_raw_contact_message);
			$wpss_raw_contact_message_lc_deslashed	= stripslashes($wpss_raw_contact_message_lc);
			$wpss_contact_extracted_urls 		= spamshield_parse_links( $wpss_raw_contact_message_lc_deslashed, 'url' ); /* Parse message content for all URLs */
			$wpss_contact_num_links 			= count( $wpss_contact_extracted_urls ); /* Count extracted URLS from body content - Added 1.8.4 */
			$wpss_contact_num_limit				= 10; /* Max number of links in message body content */

			$message_length						= spamshield_strlen($wpss_contact_message);
			$contact_form_author_data['body_content_len']		= $message_length;

			$contact_form_author_data['comment_author']			= $wpss_contact_name;
			$contact_form_author_data['comment_author_email']	= $wpss_contact_email_lc;
			$contact_form_author_data['comment_author_url']		= $wpss_contact_website_lc;

			$wpss_contact_id_str 			= $wpss_contact_email_lc.'_'.$ip.'_'.$wpss_contact_time; /* Email/IP/Time */
			$wpss_contact_id_hash 			= spamshield_md5( $wpss_contact_id_str );
			$key_contact_status				= 'contact_status_'.$wpss_contact_id_hash;
			/* Update Session Vars */
			$key_comment_auth 				= 'comment_author_'.RSMP_HASH;
			$key_comment_email				= 'comment_author_email_'.RSMP_HASH;
			$key_comment_url				= 'comment_author_url_'.RSMP_HASH;
			$_SESSION[$key_comment_auth] 	= $wpss_contact_name;
			$_SESSION[$key_comment_email]	= $wpss_contact_email_lc;
			$_SESSION[$key_comment_url] 	= $wpss_contact_website_lc;
			$_SESSION[$key_contact_status] 	= 'INITIATED';

			/* Add New Tests for Logging - BEGIN */
			if ( !empty( $post_ref2xjs ) ) {
				$ref2xJS = spamshield_casetrans( 'lower', addslashes( urldecode( $post_ref2xjs ) ) );
				$ref2xJS = str_replace( '%3a', ':', $ref2xJS );
				$ref2xJS = str_replace( ' ', '+', $ref2xJS );
				$wpss_javascript_page_referrer = esc_url_raw( $ref2xJS );
				}
			else { $wpss_javascript_page_referrer = '[None]'; }

			if ( $post_jsonst == 'NS2' ) { $wpss_jsonst = $post_jsonst; } else { $wpss_jsonst = '[None]'; }

			$contact_form_author_data['javascript_page_referrer']	= $wpss_javascript_page_referrer;
			$contact_form_author_data['jsonst']						= $wpss_jsonst;

			unset( $wpss_javascript_page_referrer, $wpss_jsonst );
			
			/* Add New Tests for Logging - END */

			/* PROCESSING CONTACT FORM - END */

			/* FORM INFO - BEGIN */

			if ( !empty( $form_message_recipient ) ) {
				$wpss_contact_form_to			= $form_message_recipient;
				}
			else {
				$wpss_contact_form_to 			= get_option('admin_email');
				}
			$wpss_contact_form_to_name 			= $wpss_contact_form_to;
			$wpss_contact_form_subject 			= '[' . __( 'Website Contact', WPSS_PLUGIN_NAME ) . '] '.$wpss_contact_subject;
			$wpss_contact_form_msg_headers 		= "From: $wpss_contact_sender_name <$wpss_contact_sender_email>" . "\r\n" . "Reply-To: $wpss_contact_name <$wpss_contact_email_lc>" . "\r\n" . "Content-Type: text/plain\r\n"; /* 1.7.3 */
			$wpss_contact_form_blog				= RSMP_SITE_URL;
			/* Another option: "Content-Type: text/html" */

			/* FORM INFO - END */

			/* TEST TO PREVENT CONTACT FORM SPAM - BEGIN */

			/* Check if user is blacklisted prior to submitting contact form */
			if ( spamshield_ubl_cache() ) {
				$wpss_user_blacklisted_prior_cf = 1;
				}

			/* TESTING CONTACT FORM SUBMISSION FOR SPAM - BEGIN */

			/* JS/CK Tests - BEGIN */
			$wpss_ck_key_bypass = $wpss_js_key_bypass = FALSE;
			//if ( TRUE == WPSS_EDGE && !empty( $spamshield_options['js_head_disable'] ) ) { /* EDGE - 1.8.4 */
			if ( !empty( $spamshield_options['js_head_disable'] ) ) { /* 1.8.9 */
				$wpss_ck_key_bypass = TRUE;
				}
			if ( $wpss_cache_status == 'ACTIVE' ) { /* 1.8.4 - TRANSITION TO NEW CODE - BYPASS IF CACHING ACTIVE */
				$wpss_js_key_bypass = TRUE;
				}

			if ( FALSE == $wpss_ck_key_bypass ) {
				if ( $wpss_jsck_cookie_val != $wpss_ck_val ) {
					$wpss_error_code .= ' CF-COOKIE-2';
					$cf_jsck_error = TRUE;
					}
				}
			if ( FALSE == $wpss_js_key_bypass ) { /* 1.8.9 */
				if ( $wpss_jsck_field_val != $wpss_js_val ) {
					$wpss_error_code .= ' CF-FVFJS-2';
					$cf_jsck_error = TRUE;
					}
				}
			if ( $post_jsonst == 'NS2' ) {
				$wpss_error_code .= ' CF-JSONST-1000-2';
				$cf_jsck_error = TRUE;
				}
			/* JS/CK Tests - END */

			/***
			* WPSS Whitelist Check - BEGIN
			* Test WPSS Whitelist if option set
			***/
			if ( !empty( $spamshield_options['enable_whitelist'] ) && empty( $wpss_error_code ) && spamshield_whitelist_check( $wpss_contact_email_lc ) ) {
				$wpss_whitelist = 1;
				}
			/* WPSS Whitelist Check - END */

			/* TO DO: REWORK SO THAT IF FAILS COOKIE TEST, TESTS ARE COMPLETE */

			/* ERROR CHECKING */
			$contact_form_blacklist_status = $contact_response_status_message_addendum = '';
			/* TO DO: Switch this old code to REGEX */
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

			/* Message Content Red Flags - TO DO: Add Blacklist Function */
			$contact_form_msg_spam_signals = array(
				 /* Sub 'e?mail' for 'mail' in regex */
				'.Email us back to get a full proposal.', 
				'This is an advertisement and a promotional mail',
				'Disclaimer: The CAN-SPAM Act of',
				'CAN-SPAM Act of 200', /* or regex 20[0-9]{2} */
				'P.S: This email is not spam,',
				'If you find this mail unsolicited, please reply with',
				);
			/*
			$regex_phrase_arr = array();
			foreach( $contact_form_msg_spam_signals_rgx as $i => $contact_form_msg_spam_signal_rgx ) {
				$regex_phrase_arr[] = spamshield_get_regex_phrase($contact_form_msg_spam_signal_rgx,'','rgx_str');
				}
			*/

			/* Check if Subject seems spammy */
			$subject_blacklisted_count = 0;
			$contact_form_spam_subj_arr = array(
				'link request', 'link exchange', 'seo service $99 per month', 'seo services $99 per month', 'seo services @ $99 per month', 'partnership with offshore development center',
				);
			$contact_form_spam_subj_arr_regex = spamshield_get_regex_phrase( $contact_form_spam_subj_arr,'','red_str' );
			if ( preg_match( $contact_form_spam_subj_arr_regex, $wpss_contact_subject_lc ) ) { $subject_blacklisted = TRUE; $subject_blacklisted_count = 1; } else { $subject_blacklisted = FALSE; }

			/* Check if email is blacklisted */
			if( empty( $wpss_whitelist ) && spamshield_email_blacklist_chk( $wpss_contact_email_lc ) ) { 
				$email_blacklisted = TRUE;
				$wpss_error_code .= ' CF-9200E-BL';
				} 
			else { $email_blacklisted = FALSE; }
			/* Website - Check if domain is blacklisted */
			if( empty( $wpss_whitelist ) && spamshield_domain_blacklist_chk( $wpss_contact_domain ) ) { 
				$domain_blacklisted = TRUE;
				$wpss_error_code .= ' CF-10500AU-BL';
				} 
			else { $domain_blacklisted = FALSE; }
			/* Website - URL Shortener Check - Added in 1.3.8 */
			if ( empty( $wpss_whitelist ) && spamshield_urlshort_blacklist_chk( $wpss_contact_website_lc ) ) {
				$website_shortened_url = TRUE;
				$wpss_error_code .= ' CF-10501AU-BL';
				}
			else { $website_shortened_url = FALSE; }
			/* Website - Excessively Long URL Check (Obfuscated & Exploit) - Added in 1.3.8 */
			if( empty( $wpss_whitelist ) && spamshield_long_url_chk( $wpss_contact_website_lc ) ) {
				$website_long_url = TRUE;
				$wpss_error_code .= ' CF-10502AU-BL';
				}
			else { $website_long_url = FALSE; }
			/***
			* Spam URL Check -  Check for URL Shorteners, Bogus Long URLs, and Misc Spam Domains
			if( empty( $wpss_whitelist ) && spamshield_at_link_spam_url_chk( $wpss_contact_website_lc ) ) {
				$website_spam_url = TRUE;
				$wpss_error_code .= ' CF-10510AU-BL';
				}
			else { $website_spam_url = FALSE; }
			***/

			/* Add Misc Spam URLs next... */

			/* Check Website URL for Exploits - Ignores Whitelist */
			if ( spamshield_exploit_url_chk( $wpss_contact_website_lc ) ) {
				$website_exploit_url = TRUE;
				$wpss_error_code .= ' CF-15000AU-XPL'; /* Added in 1.4 */
				}
			else { $website_exploit_url = FALSE; }
			
			/* Body Content - Check for excessive number of links in message ( body_content ) - Added 1.8.4 */
			if ( empty( $wpss_whitelist ) && $wpss_contact_num_links > $wpss_contact_num_limit ) {
				$content_excess_links = TRUE;
				$wpss_error_code .= ' CF-1-HT';
				}
			else { $content_excess_links = FALSE; }

			/* Body Content - Parse URLs and check for URL Shortener Links - Added in 1.3.8 */
			if( empty( $wpss_whitelist ) && spamshield_cf_link_spam_url_chk( $wpss_raw_contact_message_lc_deslashed, $wpss_contact_email_lc ) ) {
				$content_shortened_url = TRUE;
				$wpss_error_code .= ' CF-10530CU-BL';
				}
			else { $content_shortened_url = FALSE; }

			/* Check all URL's in Body Content for Exploits - Ignores Whitelist */
			if ( spamshield_exploit_url_chk( $wpss_contact_extracted_urls ) ) {
				$content_exploit_url = TRUE;
				$wpss_error_code .= ' CF-15000CU-XPL'; /* Added in 1.4 */
				}
			else { $content_exploit_url = FALSE; }

			$contact_form_spam_term_total = $contact_form_spam_1_count + $contact_form_spam_2_count + $contact_form_spam_3_count + $contact_form_spam_4_count + $contact_form_spam_7_count + $contact_form_spam_10_count + $contact_form_spam_11_count + $contact_form_spam_12_count + $subject_blacklisted_count;
			$contact_form_spam_term_total_limit = 15;

			if ( strpos( $reverse_dns_lc_rev, 'ni.' ) === 0 || strpos( $reverse_dns_lc_rev, 'ur.' ) === 0 || strpos( $reverse_dns_lc_rev, 'kp.' ) === 0 || strpos( $reverse_dns_lc_rev, 'nc.' ) === 0 || strpos( $reverse_dns_lc_rev, 'au.' ) === 0 || strpos( $reverse_dns_lc_rev, 'rt.' ) === 0 || preg_match( "~^1\.22\.2(19|20|23)\.~", $ip ) || strpos( $reverse_dns_lc_rev, '.aidni-tenecap.' ) ) {
				$contact_form_spam_loc = 1;
				/* TO DO: Add more, switch to Regex */
				}
			elseif ( strpos( $wpss_contact_email_lc_rev , 'ni.' ) === 0 || strpos( $wpss_contact_email_lc_rev , 'ur.' ) === 0 || strpos( $wpss_contact_email_lc_rev , 'kp.' ) === 0 || strpos( $wpss_contact_email_lc_rev , 'nc.' ) === 0 || strpos( $wpss_contact_email_lc_rev , 'au.' ) === 0 || strpos( $wpss_contact_email_lc_rev , 'rt.' ) === 0 ) {
				$contact_form_spam_loc = 2;
				/* TO DO: Add more, switch to Regex */
				}
			elseif ( strpos( $wpss_contact_domain_rev , 'ni.' ) === 0 || strpos( $wpss_contact_domain_rev , 'ur.' ) === 0 || strpos( $wpss_contact_domain_rev , 'kp.' ) === 0 || strpos( $wpss_contact_domain_rev , 'nc.' ) === 0 || strpos( $wpss_contact_domain_rev , 'au.' ) === 0 || strpos( $wpss_contact_domain_rev , 'rt.' ) === 0 ) {
				$contact_form_spam_loc = 3;
				/* TO DO: Add more, switch to Regex */
				}
			if ( strpos( RSMP_SERVER_NAME_REV, 'ni.' ) === 0 || strpos( RSMP_SERVER_NAME_REV, 'ur.' ) === 0 || strpos( RSMP_SERVER_NAME_REV, 'kp.' ) === 0 || strpos( RSMP_SERVER_NAME_REV, 'nc.' ) === 0 || strpos( RSMP_SERVER_NAME_REV, 'au.' ) === 0 || strpos( RSMP_SERVER_NAME_REV, 'rt.' ) === 0 ) {
				$contact_form_domain_spam_loc = 1;
				/* TO DO: Add more, switch to Regex */
				}
			if ( !empty( $form_include_company ) && !empty( $wpss_contact_company_lc ) && preg_match( "~^(se(o|m)|(search\s*engine|internet)\s*(optimiz(ation|ing|er)|market(ing|er))|(se(o|m)|((search\s*engine|internet)\s*)?(optimiz(ation|ing|er)|market(ing|er))|web\s*(design(er|ing)?|develop(ment|er|ing))|(content\s*|copy\s*)?writ(er?|ing))s?\s*(company|firm|services?|freelanc(er?|ing))|(company|firm|services?|freelanc(er?|ing))\s*(se(o|m)|((search\s*engine|internet)\s*)?(optimiz(ation|ing|er)|market(ing|er))|web\s*(design(er|ing)?|develop(ment|er|ing))|(content\s*|copy\s*)?writ(er?|ing))s?)$~", $wpss_contact_company_lc_nc ) ) {
				$generic_spam_company = 1;
				}
			if ( preg_match( "~\@(g(oogle)?mail|hotmail|outlook|yahoo|mail|gmx|inbox|myway)\.(com|(com?\.)?[a-z]{2})$~", $wpss_contact_email_lc ) ) {
				$free_email_address = 1;
				}

			/* Combo Tests - Pre */
			if ( preg_match( "~((reply|email\s+us)\s+back\s+to\s+get\s+(a\s+)?full\s+proposal\.$|can\s+you\s+outsource\s+some\s+seo\s+business\s+to\s+us|humble\s+request\s+we\s+are\s+not\s+spammers\.|if\s+by\s+sending\s+this\s+email\s+we\s+have\s+made\s+(an\s+)?offense\s+to\s+you|if\s+you\s+are\s+not\s+interested\s+then\s+please\s+(do\s+)?reply\s+back\s+as|in\s+order\s+to\s+stop\s+receiving\s+(such\s+)?emails\s+from\s+us\s+in\s+(the\s+)?future\s+please\s+reply\s+with|if\s+you\s+do\s+not\s+wish\s+to\s+receive\s+further\s+emails\s+(kindly\s+)?reply\s+with)~", $wpss_contact_message_lc ) ) {
				$combo_spam_signal_1 = 1;
				}
			if ( preg_match( "~^(get|want)\s+more\s+(customer|client|visitor)s?\s+(and|\&|or)\s+(customer|client|visitor)s?\?+$~", $wpss_contact_subject_lc ) ) {
				$combo_spam_signal_2 = 1;
				}

			if ( preg_match( "~(?:^|[,;\.\!\?\s]+)india(?:[,;\.\!\?\s]+|$)~", $wpss_contact_message_lc ) ) {
				preg_match_all( "~(?:^|[,;\.\!\?\s]+)(SEO)(?:[,;\.\!\?\s]+|$)~", $wpss_contact_message, $matches_raw, PREG_PATTERN_ORDER );
				$spam_signal_3_matches 			= $matches_raw[1]; /* Array containing matches parsed from haystack text ($wpss_contact_message) */
				$spam_signal_3_matches_count	= count( $spam_signal_3_matches );
				/* Changed from 7 to 2 occurrences - 1.6.2 */
				if ( $spam_signal_3_matches_count > 1 ) { $combo_spam_signal_3 = 1;	}
				}
			if ( preg_match( "~^(01[2-9]){3}0$~", $wpss_contact_phone ) ) {
				$bad_phone_spammer = 1;
				}
			/* Combo Tests */
			if( empty( $wpss_whitelist ) && ( $contact_form_spam_term_total > $contact_form_spam_term_total_limit || $contact_form_spam_1_count > $contact_form_spam_1_limit || $contact_form_spam_2_count > $contact_form_spam_2_limit || $contact_form_spam_5_count > $contact_form_spam_5_limit || $contact_form_spam_6_count > $contact_form_spam_6_limit || $contact_form_spam_10_count > $contact_form_spam_10_limit ) && !empty( $contact_form_spam_loc ) ) {
				$message_spam = 1;
				$wpss_error_code .= ' CF-MSG-SPAM1';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing requests will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			elseif( empty( $wpss_whitelist ) && ( !empty( $subject_blacklisted ) || $contact_form_spam_8_count > $contact_form_spam_8_limit || $contact_form_spam_9_count > $contact_form_spam_9_limit || $contact_form_spam_11_count > $contact_form_spam_11_limit || $contact_form_spam_12_count > $contact_form_spam_12_limit || !empty( $email_blacklisted ) || !empty( $domain_blacklisted ) || !empty( $website_shortened_url ) || !empty( $website_long_url ) || !empty( $website_exploit_url ) || !empty( $content_excess_links ) || !empty( $content_shortened_url ) || !empty( $content_exploit_url ) ) ) {
				$message_spam = 1;
				$wpss_error_code .= ' CF-MSG-SPAM2';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			elseif( empty( $wpss_whitelist ) && !empty( $contact_form_spam_loc ) && empty ( $contact_form_domain_spam_loc ) && !empty( $free_email_address ) && ( !empty( $generic_spam_company ) || !empty( $combo_spam_signal_1 ) || !empty( $combo_spam_signal_2 ) || !empty( $bad_phone_spammer ) ) ) {
				$message_spam = 1;
				$wpss_error_code .= ' CF-MSG-SPAM3';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				/* Blacklist on failure - future attempts blocked */
				spamshield_ubl_cache( 'set' );
				}
			elseif( empty( $wpss_whitelist ) && !empty( $generic_spam_company ) && !empty( $combo_spam_signal_3 ) ) {
				$message_spam = 1;
				$wpss_error_code .= ' CF-MSG-SPAM4';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				/* Blacklist on failure - future attempts blocked */
				spamshield_ubl_cache( 'set' );
				}
			elseif( empty( $wpss_whitelist ) && !empty( $generic_spam_company ) && !empty( $free_email_address ) ) {
				/* BOTH are odd as legit companies include their name and don't use free email */
				$message_spam = 1;
				$wpss_error_code .= ' CF-MSG-SPAM5';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, and SEO outsourcing/offshoring spam will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				/* Blacklist on failure - future attempts blocked */
				spamshield_ubl_cache( 'set' );
				}

			if ( empty( $wpss_contact_name ) || empty( $wpss_contact_email ) || empty( $wpss_contact_subject ) || empty( $wpss_contact_message ) || ( !empty( $form_include_website ) && !empty( $form_require_website ) && empty( $wpss_contact_website ) ) || ( !empty( $form_include_phone ) && !empty( $form_require_phone ) && empty( $wpss_contact_phone ) ) || ( !empty( $form_include_company ) && !empty( $form_require_company ) && empty( $wpss_contact_company ) ) || ( !empty( $form_include_drop_down_menu ) && !empty( $form_require_drop_down_menu ) && empty( $wpss_contact_drop_down_menu ) ) ) {
				$blank_field=1;
				$wpss_error_code .= ' CF-BLANKFIELD';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'At least one required field was left blank.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) === 0 ) {
				$wpss_debug_server_rgx = spamshield_preg_quote( ltrim( RSMP_DEBUG_SERVER_NAME, '.' ) );
				$last_admin_ip = get_option( 'spamshield_last_admin' );
				if ( preg_match( "~@$wpss_debug_server_rgx$~", $wpss_contact_email ) && $ip != $last_admin_ip ) {
					$invalid_value=1;
					$restricted_email=1;
					$wpss_error_code .= ' CF-RESTR-EMAIL';
					$contact_response_status_message_addendum .= '&bull; ' . __( 'That email is address is restricted. Please use your real email address.' ) . '<br />&nbsp;<br />';
					/***
					* $contact_response_status_message_addendum .= '&bull; ' . __( 'Please enter your real email address, not one of ours.' ) . '<br />&nbsp;<br />';
					* $contact_response_status_message_addendum .= '&bull; ' . 'Please enter your real email address, not one of ours.' . '<br />&nbsp;<br />';
					* Bump user spam count to 5
					***/
					if ( empty( $_SESSION['user_spamshield_count_'.RSMP_HASH] ) || $_SESSION['user_spamshield_count_'.RSMP_HASH] < 5 ) {
						$_SESSION['user_spamshield_count_'.RSMP_HASH] = 5;
						}
					}
				}
			if ( !is_email( $wpss_contact_email ) ) {
				$invalid_value=1;
				$bad_email=1;
				$wpss_error_code .= ' CF-INVAL-EMAIL';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Please enter a valid email address.' ) . '<br />&nbsp;<br />';
				}

			/* TO DO: RE-WORK THIS SECTION */
			$wpss_contact_phone_zero = str_replace( array( '0120120120', '0130130130', '123456', ' ', '0', '-', '(', ')', '+', 'N/A', 'NA', 'n/a', 'na' ), '', $wpss_contact_phone );
			$wpss_contact_phone_clean = preg_replace( "~[^0-9]+~", "", $wpss_contact_phone );
			$phone_length = spamshield_strlen( $wpss_contact_phone_clean ); /* Min = 5 */
			if ( !empty( $form_require_phone ) && !empty( $form_include_phone ) && ( empty( $wpss_contact_phone_zero ) || !empty( $bad_phone_spammer ) || $phone_length < 5 || strpos( $wpss_contact_phone, '123456' ) === 0 ) ) {
				$invalid_value=1;
				$bad_phone=1;
				$wpss_error_code .= ' CF-INVAL-PHONE';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Please enter a valid phone number.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			$wpss_contact_company_zero = str_replace( array( ' ', '0', '-', '(', ')', '+', 'N/A', 'NA', 'n/a', 'na' ), '', $wpss_contact_company_lc );
			if( !empty( $form_require_company ) && !empty( $form_include_company ) && ( empty( $wpss_contact_company_zero ) || preg_match( "~(^https?\:/+|^(0+|company|confidential|empty|fuck\s*you|invalid|na|n/a|nada|negative|nein|no|non|none|nothing|null|nyet|private|personal|restricted|secret|unknown|void)$)~", $wpss_contact_company_lc ) ) ) {
				$invalid_value=1;
				$bad_company=1;
				$wpss_error_code .= ' CF-INVAL-COMPANY';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Please enter a valid company.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			/* Spammers using one of Google's official domains as their URL */
			if( !empty( $form_include_website ) && ( !empty( $generic_spam_company ) && strpos( $reverse_dns_lc, 'google' ) === FALSE && !spamshield_is_google_ip($ip) ) && spamshield_is_google_domain($wpss_contact_domain) ) {
				$invalid_value=1;
				$bad_website=1;
				$wpss_error_code .= ' CF-INVAL-URL-G';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Please enter a valid website.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please use <em>your</em> company website URL, not Google\'s.', WPSS_PLUGIN_NAME ). '<br />&nbsp;<br />';
				/***
				* The only reason we're even putting up with these fools is to honeypot them.
				* Also, now makes website field required temporarily for this SESSION.
				***/
				$_SESSION['form_require_website_'.RSMP_HASH] = 1;
				}

			if ( $message_length < $form_message_min_length ) {
				$message_short=1;
				$wpss_error_code .= ' CF-MSG-SHORT';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message too short. Please enter a complete message.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			if ( $message_length > $form_message_max_length ) {
				$message_long=1;
				$wpss_error_code .= ' CF-MSG-LONG';
				$contact_response_status_message_addendum .= '&bull; ' . __( 'Message too long. Please enter a shorter message.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}

			/***
			* BAD ROBOT TEST - BEGIN
			* This replaces previous CF-REF-2-1023 test and previous spamshield_revdns_filter() here.
			***/

			$bad_robot_filter_data 	 = spamshield_bad_robot_blacklist_chk( 'contact', '', $ip, $reverse_dns, $wpss_contact_name, $wpss_contact_email_lc );
			$cf_filter_status		 = $bad_robot_filter_data['status'];
			$bad_robot_blacklisted 	 = $bad_robot_filter_data['blacklisted'];

			if ( !empty( $bad_robot_blacklisted ) ) {
				$message_spam 		 = 1;
				$wpss_error_code 	.= $bad_robot_filter_data['error_code'];
				$cf_badrobot_error 	 = TRUE;
				$contact_form_blacklist_status = '3'; /* Implement */
				$contact_response_status_message_addendum = '&bull; ' . __( 'Message appears to be spam.', WPSS_PLUGIN_NAME ) . ' ' . __( 'Please note that link requests, link exchange requests, SEO outsourcing/offshoring spam, and automated contact form submissions will be automatically deleted, and are not an acceptable use of this contact form.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}


			/* BAD ROBOT TEST - END */

			/* WP Blacklist Check - BEGIN */

			/* Test WP Blacklist if option set */
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
			/* WP Blacklist Check - END */

			/***
			* FINAL TEST
			* TEST 0-POST - See if user has already been blacklisted this session (before submission of this form), or a previous session, included for cases where caching is active
			***/
			if ( !empty( $wpss_user_blacklisted_prior_cf ) ) {
				/* User is blacklisted prior to submitting contact form */
				$message_spam = 1;
				$user_blacklisted = TRUE;
				$wpss_error_code .= ' CF-0-POST-BL';
				$contact_form_blacklist_status = '3'; /* Implement */
				spamshield_ubl_cache( 'set' );
				$contact_response_status_message_addendum = '&bull; ' . __( 'Contact form has been temporarily disabled to prevent spam. Please try again later.', WPSS_PLUGIN_NAME ) . '<br />&nbsp;<br />';
				}
			else { $user_blacklisted = FALSE; }

			/***
			* Track # of submissions this session
			* Must go after spam tests
			***/
			if ( !isset( $_SESSION['wpss_cf_submissions_'.RSMP_HASH] ) ) {
				$_SESSION['wpss_cf_submissions_'.RSMP_HASH] = 1;
				}
			else {
				++$_SESSION['wpss_cf_submissions_'.RSMP_HASH];
				}

			/* TESTING SUBMISSION FOR SPAM - END */

			/* Sanitized versions for output */
			$wpss_contact_form_http_accept_language = $wpss_contact_form_http_accept = $wpss_contact_form_http_referer = '';
			$wpss_contact_form_http_accept_language = spamshield_get_http_accept( FALSE, FALSE, TRUE );
			$wpss_contact_form_http_accept 			= spamshield_get_http_accept();
			$wpss_contact_form_http_user_agent 		= spamshield_get_user_agent();
			$wpss_contact_form_http_referer 		= spamshield_get_referrer( FALSE, TRUE, TRUE ); /* Initial referrer, aka "Referring Site" - Changed 1.7.9 */

			/* MESSAGE CONTENT - BEGIN */
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
			if ( !empty( $form_include_user_meta ) ) {
				$wpss_contact_form_msg_2 .= "\n";
				$wpss_contact_form_msg_2 .= __( 'Website Generating This Email', WPSS_PLUGIN_NAME ) . ': '.$wpss_contact_form_blog."\n";
				$wpss_contact_form_msg_2 .= __( 'Referrer', WPSS_PLUGIN_NAME ) . ': '.$wpss_contact_form_http_referer."\n"; /* Initial referrer, aka "Referring Site" - Changed 1.7.9 */
				$wpss_contact_form_msg_2 .= __( 'User-Agent (Browser/OS)', WPSS_PLUGIN_NAME ) . ": ".$wpss_contact_form_http_user_agent."\n";
				$wpss_contact_form_msg_2 .= __( 'IP Address', WPSS_PLUGIN_NAME ) . ': '.$ip."\n";
				$wpss_contact_form_msg_2 .= __( 'Server', WPSS_PLUGIN_NAME ) . ': '.$reverse_dns."\n";
				$wpss_contact_form_msg_2 .= __( 'IP Address Lookup', WPSS_PLUGIN_NAME ) . ': http://ipaddressdata.com/'.$ip."\n";
				if ( !current_user_can( 'edit_posts' ) ) {
					$blacklist_text = __( 'Blacklist the IP Address:', WPSS_PLUGIN_NAME );
					$ip_nodot = str_replace( '.', '', $ip );
					$ip_blacklist_nonce_action	= 'blacklist_IP_'.$ip;
					$ip_blacklist_nonce_name	= 'bl'.$ip_nodot.'tkn';
					$nonce = spamshield_create_nonce( $ip_blacklist_nonce_action, $ip_blacklist_nonce_name );
					$blacklist_url = RSMP_ADMIN_URL.'/options-general.php?page='.WPSS_PLUGIN_NAME.'&wpss_action=blacklist_ip&bl_ip='.$ip.'&'.$ip_blacklist_nonce_name.'='.$nonce;
					$wpss_contact_form_msg_2 .= $blacklist_text.' '.$blacklist_url."\n";
					}
				}

			$wpss_contact_form_msg_3 .= "\n\n";

			$wpss_contact_form_msg = $wpss_contact_form_msg_1.$wpss_contact_form_msg_2.$wpss_contact_form_msg_3;
			$wpss_contact_form_msg_cc = $wpss_contact_form_msg_1.$wpss_contact_form_msg_3;
			/* MESSAGE CONTENT - END */

			/***
			* CREATE MESSAGE WPSSID - BEGIN
			* Added 1.7.7
			***/
			$wpsseid_args 				= array( 'name' => $wpss_contact_name, 'email' => $wpss_contact_email_lc, 'url' => $wpss_contact_website_lc, 'content' => $wpss_contact_message );
			$wpsseid 					= spamshield_get_wpss_eid( $wpsseid_args );
			$wpss_contact_form_mid 		= $wpsseid['eid'];
			$wpss_contact_form_mcid 	= $wpsseid['ecid'];
			/* CREATE MESSAGE WPSSID - END */

			if ( empty( $blank_field ) && empty( $invalid_value ) && empty( $message_short ) && empty( $message_long ) && empty( $message_spam ) && empty( $cf_jsck_error ) && empty( $server_blacklisted ) && empty( $cf_badrobot_error ) && empty( $user_blacklisted ) ) {  

				/* SEND MESSAGE */

				/* Verify if Already Sent - to Prevent Duplicates - Added in 1.6 */
				$key_contact_forms_submitted = 'contact_forms_submitted_'.RSMP_HASH;
				if ( empty( $_SESSION[$key_contact_forms_submitted] ) ) {
					$_SESSION[$key_contact_forms_submitted] = array();
					}
				$spamshield_wpssmid_cache = get_option( 'spamshield_wpssmid_cache' );
				if ( empty( $spamshield_wpssmid_cache ) ) {
					$spamshield_wpssmid_cache = array();
					}
				if ( !empty( $_SESSION[$key_contact_status] ) && $_SESSION[$key_contact_status] != 'SENT' && !in_array( $wpss_contact_form_mid, $_SESSION[$key_contact_forms_submitted], TRUE ) && !in_array( $wpss_contact_form_mid, $spamshield_wpssmid_cache, TRUE ) ) {
					@wp_mail( $wpss_contact_form_to, $wpss_contact_form_subject, $wpss_contact_form_msg, $wpss_contact_form_msg_headers );
					$_SESSION[$key_contact_status] = 'SENT';
					$_SESSION[$key_contact_forms_submitted][] = $wpss_contact_form_mid;
					$spamshield_wpssmid_cache[] = $wpss_contact_form_mid;
					update_option( 'spamshield_wpssmid_cache', $spamshield_wpssmid_cache );
					}
				elseif ( in_array( $wpss_contact_form_mid, $_SESSION[$key_contact_forms_submitted], TRUE ) ) {
					if ( !in_array( $wpss_contact_form_mid, $spamshield_wpssmid_cache, TRUE ) ) {
						$spamshield_wpssmid_cache[] = $wpss_contact_form_mid;
						update_option( 'spamshield_wpssmid_cache', $spamshield_wpssmid_cache );
						}
					spamshield_append_log_data( "\n".'Duplicate contact form submission. Message not sent. WPSSMID: '.$wpss_contact_form_mid.' WPSSMCID: '.$wpss_contact_form_mcid.' [S]', FALSE );
					}
				elseif ( in_array( $wpss_contact_form_mid, $spamshield_wpssmid_cache, TRUE ) ) {
					$_SESSION[$key_contact_forms_submitted][] = $wpss_contact_form_mid;
					spamshield_append_log_data( "\n".'Duplicate contact form submission. Message not sent. WPSSMID: '.$wpss_contact_form_mid.' WPSSMCID: '.$wpss_contact_form_mcid.' [D]', FALSE );
					}

				$contact_response_status = 'thank-you';
				$wpss_error_code = 'No Error';
				spamshield_update_accept_status( $contact_form_author_data, 'a', 'Line: '.__LINE__ );
				if ( !empty( $spamshield_options['comment_logging'] ) && !empty( $spamshield_options['comment_logging_all'] ) ) {
					spamshield_log_data( $contact_form_author_data, $wpss_error_code, 'contact form', $wpss_contact_form_msg, $wpss_contact_form_mid, $wpss_contact_form_mcid );
					}
				}
			else {
				$wpss_error_code = ltrim( $wpss_error_code );
				spamshield_update_accept_status( $contact_form_author_data, 'r', 'Line: '.__LINE__, $wpss_error_code );
				$contact_response_status = 'error';
				if ( !empty( $spamshield_options['comment_logging'] ) ) {
					spamshield_log_data( $contact_form_author_data, $wpss_error_code, 'contact form', $wpss_contact_form_msg, $wpss_contact_form_mid, $wpss_contact_form_mcid );
					}
				}

			/* TEST TO PREVENT CONTACT FORM SPAM - END */

			$form_response_thank_you_message_default = '<p>' . __( 'Your message was sent successfully. Thank you.', WPSS_PLUGIN_NAME ) . '</p><p>&nbsp;</p>';
			$form_response_thank_you_message = __( $form_response_thank_you_message, WPSS_PLUGIN_NAME );

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
				/* Back URL was here...moved */
				if ( !empty( $message_spam ) ) {
					$contact_response_status_message_addendum .= '<noscript><br />&nbsp;<br />&bull; '.$wpss_js_disabled_msg_short.'</noscript>'."\n";
					$spamshield_contact_form_content .= '<p><strong>'.$wpss_error.' <br />&nbsp;<br />'.$contact_response_status_message_addendum.'</strong></p><p>&nbsp;</p>'."\n";
					}
				else {
					$contact_response_status_message_addendum .= '<noscript><br />&nbsp;<br />&bull; '.$wpss_js_disabled_msg_short.'</noscript>'."\n";
					$spamshield_contact_form_content .= '<p><strong>'.$wpss_error.' ' . __( 'Please return to the contact form and fill out all required fields.', WPSS_PLUGIN_NAME );
					$spamshield_contact_form_content .= ' ' . __( 'Please make sure JavaScript and Cookies are enabled in your browser.', WPSS_PLUGIN_NAME );
					$spamshield_contact_form_content .= '<br />&nbsp;<br />'.$contact_response_status_message_addendum.'</strong></p><p>&nbsp;</p>'."\n";
					}

				}
			$content_new = str_replace($content, $spamshield_contact_form_content, $content);
			$content_shortcode = $spamshield_contact_form_content;
			/* CONTACT FORM BACK END - END */
			}
		else {
			/***
			* 3 - ALL OTHER CASES
			* CONTACT FORM FRONT END - BEGIN
			***/

			if ( !empty( $_COOKIE['comment_author_'.RSMP_HASH] ) ) {
				/* Can't use server side if caching is active - TO DO: AJAX */
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
			$spamshield_contact_form_content .= WPSS_REF2XJS.'=escape(document[\'referrer\']);'."\n"; /* FVFJS-2 - Added 1.8.4 */
			$spamshield_contact_form_content .= 'hf2N=\''.$wpss_js_key.'\';'."\n".'hf2V=\''.$wpss_js_val.'\';'."\n";
			$spamshield_contact_form_content .= 'document.write("<input type=\'hidden\' name=\''.WPSS_REF2XJS.'\' value=\'"+'.WPSS_REF2XJS.'+"\' /><input type=\'hidden\' name=\'"+hf2N+"\' value=\'"+hf2V+"\' />");'."\n";
			$spamshield_contact_form_content .= '// ]]>'."\n";
			$spamshield_contact_form_content .= '</script>'."\n";
			$spamshield_contact_form_content .= '<noscript><input type="hidden" name="'.WPSS_JSONST.'" value="NS2" /></noscript>'."\n";
			$wpss_js_disabled_msg 	= __( 'Currently you have JavaScript disabled. In order to use this contact form, please make sure JavaScript and Cookies are enabled, and reload the page.', WPSS_PLUGIN_NAME );
			$wpss_js_enable_msg 	= __( 'Click here for instructions on how to enable JavaScript in your browser.', WPSS_PLUGIN_NAME );
			$spamshield_contact_form_content .= '<noscript><p><strong>'.$wpss_js_disabled_msg.'</strong> <a href="http://enable-javascript.com/" rel="nofollow external" >'.$wpss_js_enable_msg.'</a></p></noscript>'."\n";
			$spamshield_contact_form_content .= '<p><input type="submit" id="wpss_contact_submit" name="wpss_contact_submit" value="' . __( 'Send Message', WPSS_PLUGIN_NAME ) . '" /></p>'."\n";
			$spamshield_contact_form_content .= '<p>' . sprintf( __( 'Required fields are marked %s' ), '*' ) . '</p>'."\n";
			$spamshield_contact_form_content .= '<p>&nbsp;</p>'."\n";
			if ( !empty( $promote_plugin_link ) ) {
				$sip5c = '0';
				$sip5c = substr(RSMP_SERVER_ADDR, 4, 1); /* Server IP 5th Char */
				$ppl_code = array( '0' => 2, '1' => 2, '2' => 2, '3' => 2, '4' => 2, '5' => 2, '6' => 1, '7' => 0, '8' => 2, '9' => 2, '.' => 2 );
				if ( preg_match( "~^[0-9\.]$~", $sip5c ) ) {
					$int = $ppl_code[$sip5c];
					}
				else { $int = 0; }
				$spamshield_contact_form_content .= spamshield_contact_promo_link($int)."\n";
				$spamshield_contact_form_content .= '<p>&nbsp;</p>'."\n";
				}
			$spamshield_contact_form_content .= '</form>'."\n";

			/* PRE-TESTS, WILL DISABLE CONTACT FORM */
			$contact_form_blacklist_status = ''; /* Used in pre-tests, not yet implemented in post */

			/***
			* TEST 0-PRE - See if user has already been blacklisted this session.
			* As of 1.8.4, this is only test that will shut down contact form BEFORE it's submitted.
			***/

			if ( spamshield_ubl_cache() ) {
				$contact_form_blacklist_status = '3'; /* Was '2', changed to '3' in 1.8.4 */
				$wpss_error_code .= ' CF-0-PRE-BL';
				}

			$wpss_cache_status	= $wpss_key_values['cache_check_status'];

			/* DISABLE CONTACT FORM IF BLACKLISTED */
			if ( !empty( $contact_form_blacklist_status ) && $wpss_cache_status != 'ACTIVE' ) {
				$spamshield_contact_form_content = '<strong>' . __( 'Contact form has been temporarily disabled to prevent spam. Please try again later.', WPSS_PLUGIN_NAME ) . '</strong>';
				}
			$content_new = str_replace($spamshield_contact_repl_text, $spamshield_contact_form_content, $content);
			$content_shortcode = $spamshield_contact_form_content;
			/* CONTACT FORM FRONT END - END */
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

/* BLACKLISTS - BEGIN */

function spamshield_bad_robot_blacklist_chk( $type = 'comment', $status = NULL, $ip = NULL, $rev_dns = NULL, $author = NULL, $email = NULL ) {
	/* Use this to determine if a visitor is a bad robot. */
	$wpss_error_code			= '';
	$blacklisted				= FALSE;
	/* IP / PROXY INFO SHORT - BEGIN */
	global $wpss_ip_proxy_info;
	if ( empty( $wpss_ip_proxy_info ) ) {
		$wpss_ip_proxy_info 	= spamshield_ip_proxy_info();
		}
	$ip_proxy_info				= $wpss_ip_proxy_info;
	if ( empty( $ip ) ) 		{ $ip		= $ip_proxy_info['ip']; }
	if ( empty( $rev_dns  ) ) 	{ $rev_dns	= $ip_proxy_info['reverse_dns']; }
	/* IP / PROXY INFO SHORT - END */
	$rev_dns					= spamshield_casetrans('lower',trim($rev_dns));
	$user_agent_lc				= spamshield_get_user_agent( TRUE, TRUE );
	$user_agent_word_count		= spamshield_count_words( $user_agent_lc );
	$user_http_accept			= spamshield_get_http_accept( TRUE, TRUE );
	$user_http_accept_language	= spamshield_get_http_accept( TRUE, TRUE, TRUE );
	/* Detect Incapsula, and disable spamshield_ubl_cache - 1.8.9.6 */
	if ( strpos( $rev_dns, '.ip.incapdns.net' ) !== FALSE ) { update_option( 'spamshield_ubl_cache_disable', TRUE ); }
	if ( $type == 'register' ) { $pref = 'R-'; $ns_val = 'NS3'; } elseif ( $type == 'contact' ) { $pref = 'CF-'; $ns_val = 'NS2'; } else { $pref = ''; $ns_val = 'NS1'; }

	/***
	* REF2XJS
	* This case only happens if bots scrape the form. Nice try guys.
	***/
	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }
	$post_ref2xjs_lc	= spamshield_casetrans('lower',$post_ref2xjs);
	$ref2xjs_lc			= spamshield_casetrans( 'lower', WPSS_REF2XJS );
	if ( !empty( $post_ref2xjs ) && strpos( $post_ref2xjs_lc, $ref2xjs_lc ) !== FALSE ) {
		$wpss_error_code .= ' '.$pref.'REF-2-1023';
		$blacklisted = TRUE;
		}

	/* HA / HAL - RoboBrowsers */
	if ( empty( $user_http_accept ) ) {
		$wpss_error_code .= ' '.$pref.'HA1001';
		$blacklisted = TRUE;
		}
	if ( $user_http_accept == 'application/json, text/javascript, */*; q=0.01' ) {
		$wpss_error_code .= ' '.$pref.'HA1002';
		$blacklisted = TRUE;
		}
	if ( $user_http_accept == '*' ) {
		$wpss_error_code .= ' '.$pref.'HA1003';
		$blacklisted = TRUE;
		}
	$user_http_accept_mod_1 = preg_replace( "~([\s\;]+)~", ",", $user_http_accept );
	$user_http_accept_elements = explode( ',', $user_http_accept_mod_1 );
	$user_http_accept_elements_count = count($user_http_accept_elements);
	$i = 0;
	/* The following line to prevent exploitation: */
	$i_max = 20;
	while ( $i < $user_http_accept_elements_count && $i < $i_max ) {
		if ( !empty( $user_http_accept_elements[$i] ) ) {
			if ( $user_http_accept_elements[$i] == '*' ) {
				$wpss_error_code .= ' '.$pref.'HA1004';
				$blacklisted = TRUE;
				break;
				}
			}
		$i++;
		}
	if ( empty( $user_http_accept_language ) ) {
		$wpss_error_code .= ' '.$pref.'HAL1001';
		$blacklisted = TRUE;
		}
	if ( $user_http_accept_language == '*' ) {
		$wpss_error_code .= ' '.$pref.'HAL1002';
		$blacklisted = TRUE;
		}
	$user_http_accept_language_mod_1 = preg_replace( "~([\s\;]+)~", ",", $user_http_accept_language );
	$user_http_accept_language_elements = explode( ',', $user_http_accept_language_mod_1 );
	$user_http_accept_language_elements_count = count($user_http_accept_language_elements);
	$i = 0;
	/* The following line to prevent exploitation: */
	$i_max = 20;
	while ( $i < $user_http_accept_language_elements_count && $i < $i_max ) {
		if ( !empty( $user_http_accept_language_elements[$i] ) ) {
			if ( $user_http_accept_language_elements[$i] == '*' && strpos( $user_agent_lc, 'links (' ) !== 0 ) {
				$wpss_error_code .= ' '.$pref.'HAL1004';
				$blacklisted = TRUE;
				break;
				}
			}
		$i++;
		}
	/* HAL1005 - NOT IMPLEMENTED */

	/***
	* USER-AGENT
	* Add Blacklisted User-Agent Function - Note 1.4
	***/
	if ( empty( $user_agent_lc ) ) {
		$wpss_error_code .= ' '.$pref.'UA1001';
		$blacklisted = TRUE;
		}
	if ( !empty( $user_agent_lc ) && $user_agent_word_count < 3 ) {
		$wpss_error_code .= ' '.$pref.'UA1003';
		$blacklisted = TRUE;
		}
	if ( spamshield_skiddie_ua_check( $user_agent_lc ) ) {
		$wpss_error_code .= ' '.$pref.'UA1004';
		$blacklisted = TRUE;
		}

	/* REVDNS */
	$rev_dns_filter_data 	 = spamshield_revdns_filter( $type, $status, $ip, $rev_dns, $author, $email );
	$revdns_blacklisted 	 = $rev_dns_filter_data['blacklisted'];

	if ( !empty( $revdns_blacklisted ) ) {
		$wpss_error_code 	.= $rev_dns_filter_data['error_code'];
		$blacklisted = TRUE;
		}

	if ( !empty( $blacklisted ) ) { $status = '3'; } /* Was 2, changed to 3 - V1.8.4 */
	$bad_robot_filter_data = array( 'status' => $status, 'error_code' => $wpss_error_code, 'blacklisted' => $blacklisted );
	return $bad_robot_filter_data;
	}

function spamshield_email_blacklist_chk( $email = NULL, $get_eml_list_arr = FALSE, $get_pref_list_arr = FALSE, $get_str_list_arr = FALSE, $get_str_rgx_list_arr = FALSE ) {
	/* Email Blacklist Check */
	$blacklisted_emails = spamshield_rubik_mod( spamshield_get_email_blacklist(), 'de', TRUE );
	if ( !empty( $get_eml_list_arr ) ) { return $blacklisted_emails; }
	$blacklisted_email_prefixes = array(
		/* The beginning part of the email */
		"anonymous@", "fuckyou@", "root@", "spam@", "spambot@", "spammer@",
		);
	if ( !empty( $get_pref_list_arr ) ) { return $blacklisted_email_prefixes; }

	$blacklisted_email_strings = array(
		/* Red-flagged strings that occur anywhere in the email address */
		".seo@gmail.com", ".bizapps@gmail.com", 
		);
	if ( !empty( $get_str_list_arr ) ) { return $blacklisted_email_strings; }

	$blacklisted_email_strings_rgx = array(
		/* Custom regex strings that occur in the email address */
		"spinfilel?namesdat", "\.((marketing|business|web)manager|seo(services?)?)[0-9]*\@(g(oogle)?mail|hotmail|outlook|yahoo|mail|gmx|inbox|myway)\.(com|(com?\.)?[a-z]{2})", "^((marketing|business|web)manager|seo(services?)?)\..*\@(g(oogle)?mail|hotmail|outlook|yahoo|mail|gmx|inbox|myway)\.(com?|(co\.)?[a-z]{2})", 
		"\.((marketing|business|web)manager|seo(services?)?).*\@(g(oogle)?mail|hotmail|outlook|yahoo|mail|gmx|inbox|myway)\.(com|(com?\.)?[a-z]{2})", "^name\-[0-9]{5}\@g(oogle)?mail\.com$", 
		);
	if ( !empty( $get_str_rgx_list_arr ) ) { return $blacklisted_email_strings_rgx; }

	/* Goes after all arrays */
	$blacklist_status = FALSE;
	if ( empty( $email ) ) { return FALSE; }
	$blacklisted_domains = spamshield_domain_blacklist_chk('',TRUE);

	$n = 0; /* 1-4 */
	$t = 0; /* Total */
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
			if ( $i > $n ) { spamshield_ubl_cache( 'set' ); }
			return TRUE;
			}
		}

	return $blacklist_status;
	}

function spamshield_domain_blacklist_chk( $domain = NULL, $get_list_arr = FALSE ) {
	/* Domain Blacklist Check */
	$blacklisted_domains = spamshield_rubik_mod( spamshield_get_domain_blacklist(), 'de', TRUE );
	if ( !empty( $get_list_arr ) ) { return $blacklisted_domains; }
	/* Goes after array */
	$blacklist_status = FALSE;
	if ( empty( $domain ) ) { return FALSE; }
	/* Other Checks */
	$regex_phrases_other_checks = array(
		/* Payday Loan Spammers - Keywords in Domain */
		"~((payday|short-?term|instant|(personal-?)?cash)-?loans?|(cash|payday)-?advance|quick-?cash)~i", 
		/* "plan cul" Spammers */
		"~(plan-?cul|tonplanq)~i", 
		/* Misc */
		"~^((ww[w0-9]|m)\.)?whereto(buy|get)cannabisoil~i", 
		);
	foreach( $regex_phrases_other_checks as $i => $regex_check_phrase ) {
		if ( preg_match( $regex_check_phrase, $domain ) ) { 
			spamshield_ubl_cache( 'set' );
			return TRUE;
			}
		}
	/* Final Check - The Blacklist...takes longest once blacklist is populated, so put last */
	foreach( $blacklisted_domains as $i => $blacklisted_domain ) {
		$regex_check_phrase = spamshield_get_regex_phrase( $blacklisted_domain, '', 'domain' );
		if ( preg_match( $regex_check_phrase, $domain ) ) {
			spamshield_ubl_cache( 'set' );
			return TRUE;
			}
		}

	return $blacklist_status;
	}

function spamshield_urlshort_blacklist_chk( $url = NULL, $email_domain = NULL, $comment_type = NULL  ) {
	/***
	* URL Shortener Blacklist Check
	* Dangerous because users have no idea what website they are clicking through to
	* Added $comment_type in 1.5 for trackbacks
	***/
	$url_shorteners = spamshield_rubik_mod( spamshield_get_urlshort_blacklist(), 'de', TRUE );
	/* Goes after array */
	$blacklist_status = FALSE;
	if ( empty( $url ) ) { return FALSE; }
	$url = spamshield_fix_url($url);
	$domain = spamshield_get_domain($url);
	if ( empty( $domain ) ) { return FALSE; }
	$domain_rgx = spamshield_preg_quote($domain);
	/* See if link points to domain root or legit corporate page (ie. bitly.com) */
	if ( $comment_type != 'trackback' && preg_match( "~^https?\://$domain_rgx(/|.*([a-z0-9\-]+/[a-z0-9\-]{4,}.*|[a-z0-9\-]{4,}\.[a-z]{3,4}))?$~iu", $url ) ) { return FALSE; }
	/* Shortened URL check begins */
	$regex_phrase = spamshield_get_regex_phrase($url_shorteners,'','domain');
		/* Consider adding regex for 2-letter domains with 2-letter extensions ( "aa.xx" ) */
	if ( $email_domain != $domain && preg_match( $regex_phrase, $domain ) ) {
		return TRUE;
		}
	return $blacklist_status;
	}

function spamshield_long_url_chk( $url = NULL ) {
	/***
	* Excessively Long URL Check - Added in 1.3.8
	* To prevent obfuscated & exploit URL's
	***/
	$blacklist_status = FALSE;
	if ( empty( $url ) ) { return FALSE; }
	$url_lim = 140;
	$url_len = spamshield_strlen($url);
	if ( $url_len > $url_lim ) { 
		return TRUE;
		}
	return $blacklist_status;
	}

function spamshield_social_media_url_chk( $url = NULL, $comment_type = NULL ) {
	/***
	* Social Media URL Check - Added in 1.3.8
	* To prevent author url and anchor text links to spam social media profiles
	* Added $comment_type in 1.5 for trackbacks
	***/
	$social_media_domains = spamshield_rubik_mod( spamshield_get_sm_domain_blacklist(), 'de', TRUE );
	$social_media_domains_ext = spamshield_rubik_mod( spamshield_get_sm_ext_domain_blacklist(), 'de', TRUE );
	/* Goes after array */
	$blacklist_status = FALSE;
	if ( empty( $url ) ) { return FALSE; }
	if ( $comment_type == 'trackback' ) { $social_media_domains = $social_media_domains_ext; }
	$domain = spamshield_get_domain($url);
	$domain_rgx = spamshield_preg_quote($domain);
	$url = spamshield_fix_url($url);
	/* See if link points to domain root (ie. facebook.com) */
	if ( $comment_type != 'trackback' && preg_match( "~^https?\://$domain_rgx/?$~iu", $url ) ) { return FALSE; }
	$regex_phrase = spamshield_get_regex_phrase($social_media_domains,'','domain');
	if ( preg_match( $regex_phrase, $domain ) ) {
		return TRUE;
		}
	/* When $regex_phrase exceeds a certain size, switch this to run smaller groups or run each domain individually */
	return $blacklist_status;
	}

function spamshield_misc_spam_url_chk( $url = NULL ) {
	/***
	* Spam Domain URL Check - Added in 1.3.8
	* To prevent author url and anchor text links to spam domains
	***/
	$spam_domains = spamshield_rubik_mod( spamshield_get_spam_domain_blacklist(), 'de', TRUE );
	/* Goes after array */
	$blacklist_status = FALSE;
	if ( empty( $url ) ) { return FALSE; }
	$domain = spamshield_get_domain($url);
	$regex_phrases_other_checks = array(
		"~youporn([0-9]+)\.vox\.com~i", "~shopsquareone\.com/stores/~i", "~yellowpages\.ca/bus/~i", "~seo-?services?-?(new-?york|ny)~i", 
		);
	foreach( $regex_phrases_other_checks as $i => $regex_check_phrase ) {
		if ( preg_match( $regex_check_phrase, $url ) ) {
			return TRUE;
			}
		}
	$regex_phrase = spamshield_get_regex_phrase($spam_domains,'','domain');
	if ( preg_match( $regex_phrase, $domain ) ) {
		return TRUE;
		}
	/* When $regex_phrase exceeds a certain size, switch this to run smaller groups or run each domain individually */
	return $blacklist_status;
	}

function spamshield_referrer_blacklist_chk( $url = NULL ) {
	/***
	* Referrer Blacklist Check - Added in 1.8
	* Certain referrers result in spam 100% of the time
	***/
	$referrer_domains = spamshield_rubik_mod( spamshield_get_referrer_blacklist(), 'de', TRUE );
	/* Goes after array */
	$blacklist_status = FALSE;
	if ( empty( $url ) ) { $url = spamshield_get_referrer( FALSE, TRUE, TRUE );	}
	if ( empty( $url ) ) { return FALSE; }
	$domain = spamshield_get_domain($url);
	$regex_phrases_other_checks = array(
		"~nksmart\.com/tools/website-opener\.php$~i", "~rygist\.com/multiple-url-opener\.php$~i", 
		);
	foreach( $regex_phrases_other_checks as $i => $regex_check_phrase ) {
		if ( preg_match( $regex_check_phrase, $url ) ) {
			return TRUE;
			}
		}
	$regex_phrase = spamshield_get_regex_phrase($referrer_domains,'','domain');
	if ( preg_match( $regex_phrase, $domain ) ) {
		return TRUE;
		}
	/* When $regex_phrase exceeds a certain size, switch this to run smaller groups or run each domain individually */
	return $blacklist_status;
	}

function spamshield_link_blacklist_chk( $haystack = NULL, $type = 'domain' ) {
	/***
	* Link Blacklist Check
	* $haystack can be any body of content you want to search for links to blacklisted domains
	* $type can be 'domain', 'url', or 'urlshort' depending on what kind of check you need to do. 'domain' is faster, hence it's the default
	***/
	$blacklist_status = FALSE;
	if ( empty( $haystack ) ) { return FALSE; }
	$extracted_domains = spamshield_parse_links( $haystack, 'domain' );
	foreach( $extracted_domains as $d => $domain ) {
		if ( spamshield_domain_blacklist_chk( $domain ) ) {
			return TRUE;
			}
		}
	return $blacklist_status;
	}

function spamshield_at_link_spam_url_chk( $urls = NULL, $comment_type = NULL ) {
	/***
	* Anchor Text Link Spam URL Check
	* Check Anchor Text Links in comment content for links to common spam URLs
	* $urls - an array of URLs parsed from anchor text links
	* If $urls is string, will convert to array
	* Added $comment_type in 1.5 for trackbacks
	***/
	$blacklist_status = FALSE;
	if ( empty( $urls ) ) { return FALSE; }
	if ( is_string( $urls ) ) {
		$urls_arr 	= array();
		$urls_arr[]	= $urls;
		$urls 		= $urls_arr;
		}
	foreach( $urls as $u => $url ) {
		if ( spamshield_urlshort_blacklist_chk( $url, '', $comment_type ) || spamshield_long_url_chk( $url ) || spamshield_social_media_url_chk( $url, $comment_type ) || spamshield_misc_spam_url_chk( $url ) ) {
			/* Shortened URLs, Long URLs, Social Media, Other common spam URLs */
			return TRUE;
			}
		}
	return $blacklist_status;
	}

function spamshield_cf_link_spam_url_chk( $haystack = NULL, $email = NULL ) {
	/***
	* Contact Form Link Spam URL Check
	* Check Anchor Text Links in message content for links to shortened URLs
	* $haystack is contact form message content
	***/
	$blacklist_status = FALSE;
	if ( empty( $haystack ) || empty( $email ) ) { return FALSE; }
	$email_domain = spamshield_get_email_domain($email);
	$extracted_urls = spamshield_parse_links( $haystack, 'url' );
	foreach( $extracted_urls as $u => $url ) {
		if ( spamshield_urlshort_blacklist_chk( $url, $email_domain ) ) {
			return TRUE;
			}
		}
	return $blacklist_status;
	}

function spamshield_exploit_url_chk( $urls = NULL ) {
	/***
	* Security - Misc Exploit URL Check - Added in 1.4
	* Check ALL links for common exploit URLs
	* $urls - an array of URLs parsed from comment or message content
	* If $urls is string, will convert to array (so can be used for Comment Author URL or Contact Form Website)
	***/
	$blacklist_status = FALSE;
	if ( empty( $urls ) ) { return FALSE; }
	if ( is_string( $urls ) ) {
		$urls_arr 	= array();
		$urls_arr[]	= $urls;
		$urls 		= $urls_arr;
		}
	foreach( $urls as $u => $url ) {
		$query_str = spamshield_get_query_string($url);
		if ( preg_match( "~/phpinfo\.ph(p[3-6]?|tml)\?~i", $url ) ) {
			/* phpinfo.php Redirect - Used in XSS */
			return TRUE;
			}
		elseif ( preg_match( "~^(https?\:/+)?([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/?~i", $url ) ) {
			/***
			* IP Address URLs
			* Normal people (and Trackbacks/Pingbacks) don't post IP addresses as their website address in a comment
			* Dangerous because users have no idea what website they are clicking through to
			* Likely a Phishing site or XSS
			***/
			return TRUE;
			}
		elseif ( !empty( $query_str ) && preg_match( "~(\.\.(\/|%2f)|boot\.ini|(ftp|https?)(\:|%3a)|mosconfig_[a-z_]{1,21}(\=|%3d)|base64_encode.*\(.*\)|[\[\]\(\)\{\}\<\>\|\"\';\?\*\$]|%22|%24|%27|%2a|%3b|%3c|%3e|%3f|%5b|%5d|%7b|%7c|%7d|%0|%a|%b|%c|%d|%e|%f|127\.0|globals|encode|localhost|loopback|request|select|insert|union|declare)~i", $query_str ) ) { /* Check Query String */
			/***
			* Dangerous Exploit URLs - XSS, SQL injection, or other
			* Test Query String - This covers a number of SQL Injection and other exploits
			***/
			return TRUE;
			}
		elseif ( preg_match( "~([\[\]\(\)\{\}\<\>\|\"\';\*\$]|%22|%24|%27|%2a|%3b|%3c|%3e|%3f|%5b|%5d|%7b|%7c|%7d)~i", $url ) ) { /* Check Query String */
			/***
			* Dangerous Exploit URLs - XSS, SQL injection, or other
			* Test URL - no reason these would occur in a normal URL - they're not legal in a URL, but we've seen them in a lot of spam URL submissions
			***/
			return TRUE;
			}
		}
	return $blacklist_status;
	}

function spamshield_anchortxt_blacklist_chk( $haystack = NULL, $get_list_arr = FALSE, $haystack_type = 'author', $url = NULL ) {
	/***
	* Author Keyword Blacklist Check
	* Use for testing Comment Author, New User Registrations, and anywhere else you need to test an author name.
	* This list assembled based on statistical analysis of common anchor text spam keyphrases.
	* Script creates all the necessary alphanumeric and linguistic variations to effectively test.
	* $haystack_type can be 'author' (default) or 'content'
	***/
	$wpss_cl_active		= spamshield_is_plugin_active( 'commentluv/commentluv.php' ); /* Check if active for compatibility with CommentLuv */
	$spamshield_options = get_option('spamshield_options');
	if ( !empty( $spamshield_options['allow_comment_author_keywords'] ) ) { $wpss_cak_active = 1; } else { $wpss_cak_active = 0; } /* Check if Comment Author Name Keywords are allowed - equivalent to CommentLuve being active */
	$blacklisted_keyphrases = spamshield_rubik_mod( spamshield_get_anchortxt_blacklist(), 'de', TRUE );
	$blacklisted_keyphrases_lite = spamshield_rubik_mod( spamshield_get_anchortxt_blacklist_lite(), 'de', TRUE );
	if ( $haystack_type == 'author' && ( !empty( $wpss_cl_active ) || !empty( $wpss_cak_active ) || empty( $url ) ) ) {
		$blacklisted_keyphrases = $blacklisted_keyphrases_lite;
		}
	if ( !empty( $get_list_arr ) ) {
		if ( $haystack_type == 'content' ) { return $blacklisted_keyphrases_lite; }
		else { return $blacklisted_keyphrases; }
		}
	/* Goes after array */
	$blacklist_status = FALSE;
	if ( empty( $haystack ) ) { return FALSE; }
	if ( $haystack_type == 'author' ) {
		/* Check 1: Testing for URLs in author name */
		if ( preg_match( "~^https?~i", $haystack ) ) {
			return TRUE;
			}
		/* Check 2: Testing for max # words in author name, more than 7 is fail */
		$author_words = spamshield_count_words( $haystack );
		$word_max = 7; /* Default */
		If ( !empty( $wpss_cl_active ) || !empty( $wpss_cak_active ) ) { $word_max = 10; } /* CL or CAK active */
		if ( $author_words > $word_max ) {
			return TRUE;
			}
		/* Check 3: Testing for Odd Characters in author name */
		$odd_char_regex = "~[\@\*]+~"; /* Default */
		If ( !empty( $wpss_cl_active ) || !empty( $wpss_cak_active ) ) { $odd_char_regex = "~(\@{2,}|\*)+~"; } /* CL or CAK active */
		if ( preg_match( $odd_char_regex, $haystack ) ) {
			return TRUE;
			}
		/***
		* Check 4: Testing for *author name* surrounded by asterisks
		* Check 5: Testing for numbers and cash references ('1000','$5000', etc) in author name 
		***/
		if ( empty( $wpss_cl_active ) && empty( $wpss_cak_active ) && preg_match( "~(^|[\s\.])(\$([0-9]+)([0-9,\.]+)?|([0-9]+)([0-9,\.]{3,})|([0-9]{3,}))($|[\s])~", $haystack ) ) {
			return TRUE;
			}
		/* Final Check: The Blacklist */
		foreach( $blacklisted_keyphrases as $i => $blacklisted_keyphrase ) {
			$blacklisted_keyphrase_rgx = spamshield_regexify( $blacklisted_keyphrase );
			$regex_check_phrase = spamshield_get_regex_phrase( $blacklisted_keyphrase_rgx, '', 'authorkw' );
			if ( preg_match( $regex_check_phrase, $haystack ) ) {
				return TRUE;
				}
			}
		}
	elseif ( $haystack_type == 'content' ) {
		/***
		* Parse content for links with Anchor Text
		* Test 1: Coming Soon
		* For possible use later - from old filter: ((payday|students?|title|onli?ne|short([\s\.\-_]*)term)([\s\.\-_]*)loan|cash([\s\.\-_]*)advance)
		* Final Check: The Blacklist
		***/
		$anchor_text_phrases = spamshield_parse_links( $haystack, 'anchor_text' );
		foreach( $anchor_text_phrases as $a => $anchor_text_phrase ) {
			foreach( $blacklisted_keyphrases_lite as $i => $blacklisted_keyphrase ) {
				$blacklisted_keyphrase_rgx = spamshield_regexify( $blacklisted_keyphrase );
				$regex_check_phrase = spamshield_get_regex_phrase( $blacklisted_keyphrase_rgx, '', 'authorkw' );
				if ( preg_match( $regex_check_phrase, $anchor_text_phrase ) ) {
					return TRUE;
					}
				}
			}
		}
	return $blacklist_status;
	}

function spamshield_ubl_cache( $method = 'chk' ) {
	/***
	* Check if user has been added to blacklist cache
	* Added 1.8
	* $method: 'set','chk'
	***/
	/* Temporarily disabled in 1.8.9.2 for testing, re-enabled with modifications in 1.8.9.6 */
	$wpss_ubl_cache_disable = get_option('spamshield_ubl_cache_disable');
	if ( !empty( $wpss_ubl_cache_disable ) ) { return FALSE; }
	$ip = $_SERVER['REMOTE_ADDR'];
	if ( strpos( $ip, '.' ) !== FALSE ) {
		$ip_arr = explode( '.', $ip ); unset( $ip_arr[3] ); $ip_c = implode( '.', $ip_arr ) . '.';
		if ( strpos( RSMP_SERVER_ADDR, $ip_c ) === 0 ) { return FALSE; } /* Skip anything on same C-Block as website */
		}
	$blacklist_status = FALSE;
	if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) !== 0 ) {
		$last_admin_ip = get_option( 'spamshield_last_admin' );
		if ( $ip == $last_admin_ip ) { return FALSE; }
		}
	$wpss_lang_ck_key = 'UBR_LANG'; $wpss_lang_ck_val = 'default';
	$wpss_ubl_cache = get_option('spamshield_ubl_cache');
	if ( empty( $wpss_ubl_cache ) ) { $wpss_ubl_cache = array(); }
	/* Check */
	if ( !empty( $_SESSION['wpss_blacklisted_user_'.RSMP_HASH] ) || ( !empty( $_COOKIE[$wpss_lang_ck_key] ) && $_COOKIE[$wpss_lang_ck_key] == $wpss_lang_ck_val ) || ( !empty( $ip ) && in_array( $ip, $wpss_ubl_cache, TRUE ) ) ||spamshield_referrer_blacklist_chk() ) {
		$blacklist_status = TRUE;
		}
	/* Set */
	if ( !empty( $blacklist_status ) || $method == 'set' ) {
		if ( !empty( $ip ) && !in_array( $ip, $wpss_ubl_cache, TRUE ) ) { $wpss_ubl_cache[] = $ip; }
		$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = TRUE;
		update_option( 'spamshield_ubl_cache', $wpss_ubl_cache );
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
	$tmp_string = spamshield_casetrans( 'lower', trim( $string ) );
	/* Translates 1337 (LEET) as well */
	$input = array( /* 26 */
		"~(^|[\s\.])(online|internet|web(\s*(site|page))?)\s*gambling($|[\s])~i", "~(^|[\s\.])gambling\s*(online|internet|web(\s*(site|page))?)($|[\s])~i", 
		"~(?!^online|internet|web(\s*(site|page))?$)(^|[\s\.])(online|internet|web(\s*(site|page))?)($|[\s])~i", "~(?!^india|china|russia|ukraine$)(^|[\s\.])(india|china|russia|ukraine)($|[\s])~i", 
		"~(?!^offshore|outsource|data\s+entry$)(^|[\s\.])(offshore|outsource|data\s+entry)($|[\s])~i", "~ph~i",	"~(^|[\s\.])porn~i", "~ual($|[\s])~i", "~al($|[\s])~i", "~ay($|[\s])~i", "~ck($|[\s])~i", 
		"~(ct|x)ion($|[\s])~i", "~te($|[\s])~i", "~(?!te$)e($|[\s])~i", "~er($|[\s])~i", "~ey($|[\s])~i", "~ic($|[\s])~i", "~ign($|[\s])~i", "~iou?r($|[\s])~i", "~ism($|[\s])~i", "~ous($|[\s])~i", 
		"~oy($|[\s])~i", "~ss($|[\s])~i", "~tion($|[\s])~i", "~y($|[\s])~i", "~([abcdghklmnoprtw])($|[\s])~i", 
		);
	$output = array( /* 26 */
		" (online|internet|web( (site|page))?)s? (bet(ting|s)?|blackjack|casinos?|gambl(e|ing)|poker) ", " (bet(ting|s)?|blackjack|casinos?|gambl(e|ing)|poker) (online|internet|web( (site|page))?)s? ", 
		" (online|internet|web( (site|page))?)s? ", " (india|china|russia|ukraine) ", " (offshor(e(d|r|s|n|ly)?|ing)s?|outsourc(e(d|r|s|n|ly)?|ing)s?|data entry) ", "(ph|f)", "p(or|ro)n", "u(a|e)l(ly|s)? ", 
		"al(ly|s)? ", "ays? ", "ck(e(d|r)?|ing)?s? ", "(ct|cc|x)ions? ", "t(e(d|r|s|n|ly)?|ing|ion)?s? ", "(e(d|r|s|n|ly)?|ing|ation)s? ", "(er|ing)s? ", "eys? ", "i(ck?|que)(s|ly)? ", "ign(e(d|r))?s? ", 
		"iou?rs? ", "is(m|t) ", "ous(ly)? ", "oys? ", "ss(es)? ", "(t|c)ions? ", "(y|ie(d|r|s)?) ", "$1s? ", 
		);
	$tmp_string = preg_replace( $input, $output, $tmp_string );
	$tmp_string = spamshield_casetrans( 'lower', trim( $tmp_string ) );
	$the_replacements = array(
		" "	=> "([\s\.,\;\:\?\!\/\|\@\(\)\[\]\{\}\-_]*)", "-" => "([\s\.,\;\:\?\!\/\|\@\(\)\[\]\{\}\-_]*)", "a"	=> "([a4\@àáâãäåæāăą])", "b" => "([b8ßƀƃƅ])", "c" => "([c¢©çćĉċč])", "d" => "([dďđ])", 
		"e" => "([e3èéêëēĕėęěǝ])", "g" => "([g9ĝğġģ])", "h" => "([hĥħ])", "i" => "([i1yìíîïĩīĭį])", "k" => "([kķĸ])", "j" => "([jĵ])", "l" => "([l1ĺļľŀł])", "n" => "([nñńņňŉ])", "o" => "([o0ðòóôõöōŏőœ])", 
		"r"	=> "([r®ŕŗř])", "s"	=> "([s5\$śŝşš])", "t" => "([t7ťŧţ])", "u" => "([uùúûüũūŭůűų])", "w" => "([wŵ])", "y" => "([y1i¥ýÿŷ])", "z" => "([z2sźżž])", 
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
	$blacklisted = FALSE;
	/* IP / PROXY INFO SHORT - BEGIN */
	global $wpss_ip_proxy_info;
	if ( empty( $wpss_ip_proxy_info ) ) {
		$wpss_ip_proxy_info 	= spamshield_ip_proxy_info();
		}
	$ip_proxy_info				= $wpss_ip_proxy_info;
	if ( empty( $ip ) ) 		{ $ip		= $ip_proxy_info['ip']; }
	if ( empty( $rev_dns  ) ) 	{ $rev_dns	= $ip_proxy_info['reverse_dns']; }
	/* IP / PROXY INFO SHORT - END */
	$rev_dns = spamshield_casetrans('lower',trim($rev_dns));
	/* Detect Incapsula, and disable spamshield_ubl_cache - 1.8.9.6 */
	if ( strpos( $rev_dns, '.ip.incapdns.net' ) !== FALSE ) { update_option( 'spamshield_ubl_cache_disable', TRUE ); }
	if ( !empty( $author ) ) 	{ $author = spamshield_casetrans( 'lower', $author ); }
	if ( !empty( $email ) ) 	{ $author = spamshield_casetrans( 'lower', $email ); }
	if ( $type == 'contact' ) 	{ $pref = 'CF-'; } elseif ( $type == 'register' ) { $pref = 'R-'; } elseif ( $type == 'trackback' ) { $pref = 'T-'; } else { $pref = ''; }

	/* Test Reverse DNS Hosts - Do all with Reverse DNS moving forward */

	/* Bad Robot! */
	$banned_servers = array(
		/***
		* Web servers should not post comments/contact forms/register
		* Regex must be *specific* and *tight* to prevent false pos
		***/
		"REVD1023" => "~(^|\.)keywordspy\.com$~",
		"REVD1024" => "~(^|\.)clients\.your-server\.de$~",
		"REVD1025" => "~^rover-host\.com$~",
		"REVD1026" => "~^host\.lotosus\.com$~",
		"REVD1027" => "~^rdns\.softwiseonline\.com$~",
		"REVD1028" => "~^s([a-z0-9]+)\.websitehome\.co\.uk$~",
		"REVD1029" => "~\.opentransfer\.com$~",
		"REVD1030" => "~(^|\.)arkada\.rovno\.ua$~",
		"REVD1031" => "~^(host?|vm?)[0-9]+\.server[0-9]+\.vpn(999|2buy)\.com$~",
		"REVD1032" => "~^(ip[\.\-])?([0-9]{1,3}[\.\-]){4}(rdns\.(3e\.vc|as15003\.net|cloudradium\.com|continuumdatacenters\.com|dafeature\.com|mach9servers\.com|micfo\.com|mydnsprovider\.com|purewebtech\.net|racklot\.com|scalabledns\.com|servebyte\.com|smtp[0-9]+\.zhaoshengzixun\.com|ubiquityservers\.com)|static\.(hostnoc\.net|dimenoc\.com|reverse\.(softlayer\.com|queryfoundry\.net))|ip\.idealhosting\.net\.tr|triolan\.net|chunkhost\.com|rev\.poneytelecom\.eu|ipvnow.com|customer-(rethinkvps|incero)\.com|unknown\.steephost\.(net|com))$~",
		"REVD1033" => "~^(r?d)?ns([0-9]{1,3})\.(webmasters|rootleveltech)\.com$~",
		"REVD1034" => "~^server([0-9]+)\.(shadowbrokers|junctionmethod|([a-z0-9\-]+))\.(com|net)$~",
		"REVD1035" => "~^(([0-9]{1,3}[\.\-]){4})?hosted-by\.(ecatel\.net|hosthatch\.com|i3d\.net|inceptionhosting\.com|ipxcore\.com|leaseweb\.com|provisionhost\.com|reliablesite\.net|securefastserver\.com|slaskdatacenter\.pl|snel\.com )$~",
		"REVD1036" => "~^(([0-9]{1,3}[\.\-]){4}static|unassigned)\.quadranet\.com$~",
		"REVD1037" => "~^([a-z]{2}[0-9]*[\.\-])?[0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}(\.[a-z]{2}-((north|south)(east|west)|north|south|east|west)-([0-9]+))?\.compute\.amazonaws\.com$~",
		"REVD1038" => "~^([a-z]+)([0-9]{1,3})\.(guarmarr\.com|startdedicated\.com)$~",
		"REVD1039" => "~^([a-z0-9\-\.]+)\.rev\.sprintdatacenter\.pl$~",
		"REVD1040" => "~^ns([0-9]+)\.ovh\.net$~", /* OVH also provides ISP services, so do not block those */
		"REVD1041" => "~^(static|clients?)[\.\-]([0-9]{1,3}[\.\-]){4}(clients\.your-server\.de|customers\.filemedia\.net|hostwindsdns\.com)$~",
		"REVD1042" => "~^([0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}[\.\-][0-9]{1,3}|ks[0-9]{7})[\.\-]kimsufi\.com$~",
		"REVD1043" => "~^([0-9]{1,3}[\.\-]){4}static-reverse\.[a-z]+-cloud\.serverhub\.com$~",
		"REVD1044" => "~^host[0-9]+\.sale24news\.com$~",
		"REVD1045" => "~^[0-9]+-[0-9]+-[0-9]+\.unassigned\.userdns\.com$~",
		"REVD1046" => "~^h?([0-9]{1,3}[\.\-]){4}(rackcentre|host)\.redstation\.(co|net)\.uk$~",
		"REVD1047" => "~^([0-9]{1,3}[\.\-]){4}(hostvenom\.com|contina\.com|techserverdns\.com)$~",
		"REVD1048" => "~^[a-z]+[0-9]*\.zeehostbox\.com$~",
		"REVD1049" => "~^(h[\.\-])?([0-9]{1,3}[\.\-]){4}keyweb\.de$~",
		"REVD1051" => "~^(([0-9]{1,3}[\.\-]){4})?hosted-by\.~",
		"REVD1052" => "~^no\.rdns-yet\.ukservers\.com$~",
		"REVD1054" => "~^seo[0-9]*\.heilink\.com$~",
		"REVD1055" => "~^([0-9]+[a-z]+){2}\.rdns\.100tb\.com$~",
		"REVD1056" => "~^we\.love\.servers\.at\.ioflood\.com$~",
		"REVD1057" => "~^[a-z]+\.highspeedinternetsecurity\.com$~",
		"REVD1058" => "~^(([0-9]{1,3}[\.\-]){4})?host\.colocrossing\.com$~",
		"REVD1059" => "~^([0-9]{1,3}[\.\-]){4}bc\.googleusercontent\.com$~",
		"REVD1060" => "~^([0-9]{1,3}[\.\-]){4}static\.reverse\.lstn\.net$~",
		);

	$banned_trackback_servers = array(
		/* ISP's Should not send trackbacks, ever */
		"REVD2102" => "~^c\-([0-9]{1,3}[\.\-]){4}hsd[0-9]+\.[a-z]{2,}\.comcast\.net$~", 
		"REVD2103" => "~([0-9]{1,3}[\.\-]){4}(static[\.\-])?[a-z]+broadband(\.[a-z]{2,3})?\.[a-z]{2,3}$~", 
		"REVD2104" => "~^host([0-9]{1,3}[\.\-]){4}range[0-9]+\-[0-9]+\.btcentralplus\.com$~", 
		"REVD2105" => "~^ip([0-9]{1,3}[\.\-]){4}[a-z]{2,}\.[a-z]{2,}\.cox\.net$~", 
		"REVD2106" => "~^([0-9]{1,3}[\.\-]){4}(([a-z]+\.)?broadband|gprs)\.kyivstar\.net$~", 
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
		/***
		* "REVD2119" => "~^([0-9]{1,3}[\.\-]){4}(board|broad|dail|dial)\.([a-z]{2}\.){2}dynamic\.163data\.com\.cn$~",
		* Covered by REVD2000
		***/
		);

	if ( $type == 'trackback' ) { $banned_servers = $banned_trackback_servers; }

	if ( $type == 'trackback' && strpos( $rev_dns, '.dynamic.' ) !== FALSE && strpos( $rev_dns, 'www.dynamic.' ) === FALSE ) {
		$wpss_error_code .= ' '.$pref.'REVD2000'; $blacklisted = TRUE;
		}
	elseif ( $type == 'trackback' && strpos( $rev_dns, '.broadband.' ) !== FALSE && strpos( $rev_dns, 'www.broadband.' ) === FALSE ) {
		$wpss_error_code .= ' '.$pref.'REVD2001'; $blacklisted = TRUE;
		}
	elseif ( $type == 'trackback' && ( strpos( $rev_dns, '.dsl.dyn.' ) !== FALSE || strpos( $rev_dns, '.dyn.dsl.' ) !== FALSE ) ) {
		$wpss_error_code .= ' '.$pref.'REVD2010'; $blacklisted = TRUE;
		}
	/*
	elseif ( $type == 'trackback' && strpos( $rev_dns, '.dial-up.' ) !== FALSE && strpos( $rev_dns, 'www.dial-up.' ) === FALSE ) {
		$wpss_error_code .= ' '.$pref.'REVD2011'; $blacklisted = TRUE;
		}
	*/
	else {
		foreach( $banned_servers as $error_code => $regex_phrase ) {
			if ( preg_match( $regex_phrase, $rev_dns ) ) { $wpss_error_code .= ' '.$pref.$error_code; $blacklisted = TRUE; }
			}
		}

	if ( empty( $blacklisted ) && !empty( $author ) && !empty( $email ) && ( $type == 'comment' || $type == 'register' ) ) { 
		/* The 8's Pattern - from relakks.com - Anonymous surfing, powered by bots */
		if ( preg_match( "~^anon-([0-9]+)-([0-9]+)\.relakks\.com$~", $rev_dns ) && preg_match( "~^([a-z]{8})$~", $author ) && preg_match( "~^([a-z]{8})\@([a-z]{8})\.com$~", $email ) ) {
			/* anon-###-##.relakks.com spammer pattern */
			$wpss_error_code .= ' '.$pref.'REVDA-1050';
			$blacklisted = TRUE;
			}
		/* The 8's - also coming from from rackcentre.redstation.net.uk */
		}

	if ( !empty( $wpss_error_code ) ) { $status = '3'; } /* Was 2, changed to 3 - V1.8.4 */
	$rev_dns_filter_data = array( 'status' => $status, 'error_code' => $wpss_error_code, 'blacklisted' => $blacklisted );
	return $rev_dns_filter_data;
	}

function spamshield_skiddie_ua_check( $user_agent_lc = NULL ) {
	/***
	* Undisguised User-Agents commonly used by script kiddies to attack/spam WP.
	* There is no reason for a human or Trackback/Pingback to use one of these UA strings.
	* Added 1.7.9
	***/
	if ( empty( $user_agent_lc ) ) { $user_agent_lc = spamshield_get_user_agent( TRUE, TRUE ); }
	if ( strpos( $user_agent_lc, 'libwww' ) !== FALSE 
		|| strpos( $user_agent_lc, 'nutch' ) === 0
		|| strpos( $user_agent_lc, 'larbin' ) === 0
		|| strpos( $user_agent_lc, 'jakarta' ) === 0
		|| strpos( $user_agent_lc, 'java' ) === 0
		|| strpos( $user_agent_lc, 'mechanize' ) === 0
		|| strpos( $user_agent_lc, 'phpcrawl' ) === 0
		|| strpos( $user_agent_lc, 'iopus-' ) !== FALSE 
		|| strpos( $user_agent_lc, 'synapse' ) !== FALSE 
		) {
		return TRUE;
		}
	return FALSE;
	}

/* BLACKLISTS - END */

function spamshield_check_comment_spam( $commentdata ) {
	/* Timer Start - Comment Processing */
	$commentdata['start_time_comment_processing'] = spamshield_microtime();

	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);

	$wpss_error_code 	= $wpss_js_key_bypass = '';
	$bypass_tests 		= FALSE;

	/* Add New Tests for Logging - BEGIN */
	if ( !empty( $_POST[WPSS_JSONST] ) ) { $post_jsonst = $_POST[WPSS_JSONST]; } else { $post_jsonst = ''; }
	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }
	$post_ref2xjs_lc 	= spamshield_casetrans( 'lower', $post_ref2xjs ); /* For both logging and testing */
	$ref2xjs_lc			= spamshield_casetrans( 'lower', WPSS_REF2XJS ); /* For testing later on, not logging */
	if ( !empty( $post_ref2xjs ) ) {
		$ref2xJS = spamshield_casetrans( 'lower', addslashes( urldecode( $post_ref2xjs ) ) );
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

	$wpss_comment_author_email_lc				= spamshield_casetrans('lower',$commentdata['comment_author_email']);

	$commentdata_comment_content_lc_deslashed	= stripslashes(spamshield_casetrans('lower',$commentdata['comment_content']));
	$commentdata['body_content_len']			= spamshield_strlen($commentdata_comment_content_lc_deslashed);
	unset( $commentdata_comment_content_lc_deslashed, $wpss_javascript_page_referrer, $wpss_jsonst );

	/***
	* CREATE COMMENT WPSSID - BEGIN
	* Added 1.7.7
	***/
	$wpsseid_args 						= array( 'name' => $commentdata['comment_author'], 'email' => $commentdata['comment_author_email'], 'url' => $commentdata['comment_author_url'], 'content' => $commentdata['comment_content'] );
	$wpsseid 							= spamshield_get_wpss_eid( $wpsseid_args );
	$commentdata['comment_wpss_cid']	= $wpsseid['eid'];
	$commentdata['comment_wpss_ccid']	= $wpsseid['ecid'];
	/* CREATE COMMENT WPSSID - END */

	/* Add New Tests for Logging - END */


	/* User Authorization - BEGIN */

	/* Don't use elseif() for these tests - we are stacking $wpss_error_code_addendum results */

	$wpss_error_code_addendum = '';
	/* 1) is_admin() - If in Admin, don't test so user can respond directly to comments through admin */
	if ( is_admin() ) {
			$bypass_tests = TRUE;
			$wpss_error_code_addendum .= ' 1-ADMIN';
			}

	/* 2) current_user_can( 'moderate_comments' ) - If user has Admin or Editor level access, don't test */
	if ( current_user_can( 'moderate_comments' ) ) {
			$bypass_tests = TRUE;
			$wpss_error_code_addendum .= ' 2-MODCOM';
			}

	if ( current_user_can( 'publish_posts' ) ) {
		/* Added Author Requirement - current_user_can( 'publish_posts' ) - v 1.4.7 */
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

		/* 3) If user is logged in, Author, and email is from same domain as website, don't test */
		if ( !empty( $wpss_user_email_domain_no_w3 ) && preg_match( "~(^|\.)$wpss_user_email_domain_no_w3_regex$~i", $wpss_server_domain_no_w3 ) ) {
			$bypass_tests = TRUE;
			$wpss_error_code_addendum .= ' 3-AEMLDOM';
			}
		/* 4) If user is logged in, Author, and url is same domain as website, don't test */
		if ( !empty( $wpss_user_domain_no_w3 ) && preg_match( "~(^|\.)$wpss_user_domain_no_w3_regex$~i", $wpss_server_domain_no_w3 ) ) {
			$bypass_tests = TRUE;
			$wpss_error_code_addendum .= ' 4-AURLDOM';
			}

		}

	/* 5) Whitelist */
	if ( !empty( $spamshield_options['enable_whitelist'] ) && spamshield_whitelist_check( $wpss_comment_author_email_lc ) ) {
		$bypass_tests = TRUE;
		$wpss_error_code_addendum .= ' 5-WHITELIST';
		}

	if ( $bypass_tests != FALSE ) {
		$wpss_error_code = 'No Error';
		/* $wpss_error_code .= $wpss_error_code_addendum; */
		}
	/* Timer End - Part 1 */
	$wpss_end_time_part_1 = spamshield_microtime();
	$wpss_total_time_part_1 = spamshield_timer( $commentdata['start_time_comment_processing'], $wpss_end_time_part_1, FALSE, 6, TRUE );
	$commentdata['total_time_part_1'] = $wpss_total_time_part_1;
	/* User Authorization - END */

	if ( $bypass_tests != TRUE ) {
		/* ONLY IF NOT ADMINS, EDITORS - BEGIN */

		/* First Do JS/Cookies Test */

		/***
		* JS/Cookies TEST - BEGIN
		* Rework this
		***/
		if ( $commentdata['comment_type'] != 'trackback' && $commentdata['comment_type'] != 'pingback' ) {
			/* If Comment is not a trackback or pingback */

			/* Timer Start - JS/Cookies Filter */
			$wpss_start_time_jsck_filter = spamshield_microtime();
			$commentdata['start_time_jsck_filter'] = $wpss_start_time_jsck_filter;

			$wpss_key_values 	= spamshield_get_key_values();
			$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
			$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
			$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
			$wpss_js_val 		= $wpss_key_values['wpss_js_val'];
			$wpss_cache_status	= $wpss_key_values['cache_check_status'];
			if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wpss_jsck_cookie_val = $_COOKIE[$wpss_ck_key]; } else { $wpss_jsck_cookie_val = ''; }
			if ( !empty( $_POST[$wpss_js_key] ) ) { $wpss_jsck_field_val 	= $_POST[$wpss_js_key]; } else { $wpss_jsck_field_val = ''; }
			$wpss_ck_key_bypass = $wpss_js_key_bypass = FALSE;
			//if ( TRUE == WPSS_EDGE && !empty( $spamshield_options['js_head_disable'] ) ) { /* EDGE - 1.8.4 */
			if ( !empty( $spamshield_options['js_head_disable'] ) ) { /* 1.8.9 */
				$wpss_ck_key_bypass = TRUE;
				}
			if ( $wpss_cache_status == 'ACTIVE' ) { /* 1.8.4 - TRANSITION TO NEW CODE - BYPASS IF CACHING ACTIVE */
				$wpss_js_key_bypass = TRUE;
				}
			if ( FALSE == $wpss_ck_key_bypass ) {
				if ( $wpss_jsck_cookie_val != $wpss_ck_val ) {
					/***
					* JS/CK Test 01
					* Failed the Cookie Test
					* Part of the JavaScript/Cookies Layer
					***/
					$wpss_error_code .= ' COOKIE-1';
					$commentdata['wpss_error_code'] = ltrim( $wpss_error_code );
					spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code );
					}
				}
			/***
			* ALSO PART OF BAD ROBOTS TEST - BEGIN - Will have to add modifier for JS/CK TESTS only and add callback & args to spamshield_bad_robot_blacklist_chk() before it can be implemented here
			* Test JS Referrer for Obvious Scraping Spambots
			***/
			if ( !empty( $post_ref2xjs ) && strpos( $post_ref2xjs_lc, $ref2xjs_lc ) !== FALSE ) {
				/* JS/CK Test 02 */
				$wpss_error_code .= ' REF-2-1023-1';
				$commentdata['wpss_error_code'] = ltrim( $wpss_error_code );
				spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code );
				}
			/* ALSO PART OF BAD ROBOTS TEST - END */
			/* JavaScript Off NoScript Test - JSONST - will only be sent by Scraping Spambots */
			if ( $post_jsonst == 'NS1' ) {
				/* JS/CK Test 03 */
				$wpss_error_code .= ' JSONST-1000-1';
				$commentdata['wpss_error_code'] = ltrim( $wpss_error_code );
				spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code );
				}

			if ( FALSE == $wpss_js_key_bypass ) {
				if ( $wpss_jsck_field_val != $wpss_js_val ) {
					/***
					* JS/CK Test 04
					* Failed the FVFJS Test
					* Part of the JavaScript/Cookies Layer
					***/
					$wpss_error_code .= ' FVFJS-1';
					$commentdata['wpss_error_code'] = ltrim( $wpss_error_code );
					spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code );
					}
				}
			/* Timer End - JS/Cookies Filter */
			$wpss_end_time_jsck_filter = spamshield_microtime();
			$wpss_total_time_jsck_filter = spamshield_timer( $wpss_start_time_jsck_filter, $wpss_end_time_jsck_filter, FALSE, 6, TRUE );
			$commentdata['total_time_jsck_filter'] = $wpss_total_time_jsck_filter;
			if ( !empty( $wpss_error_code ) ) {
				$commentdata['wpss_error_code'] = ltrim( $wpss_error_code );
				spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code );
				}
			}
		/* JS/Cookies TEST - END */

		/* 2ND - Trackbacks/Pingbacks */
		if ( $commentdata['comment_type'] == 'trackback' || $commentdata['comment_type'] == 'pingback' ) {
			/* 1ST - TR1 - Trackback Content Filter */
			$commentdata 			= spamshield_trackback_content_filter( $commentdata, $spamshield_options );
			$content_filter_status 	= $commentdata['content_filter_status'];
			$wpss_error_code		= ltrim( $commentdata['wpss_error_code'] );
			if ( !empty( $content_filter_status ) ) { /* Same actions as TR2 - Needs Trackback exit filter similar to spamshield_exit_jsck_filter() */
				spamshield_update_accept_status( $commentdata, 'r', 'Line: '.__LINE__, $wpss_error_code );
				if ( !empty( $spamshield_options['comment_logging'] ) ) { spamshield_log_data( $commentdata, $wpss_error_code ); }
				spamshield_denied_post_response( $commentdata, 'vague' );
				}
			/* 2ND - TR2 - Trackback False IP Check */
			if ( $commentdata['comment_type'] == 'trackback' ) {
				$commentdata			= spamshield_trackback_ip_filter( $commentdata, $spamshield_options );
				$content_filter_status	= $commentdata['content_filter_status'];
				$wpss_error_code		= ltrim( $commentdata['wpss_error_code'] );
				if ( !empty( $content_filter_status ) ) { /* Same actions as TR1 - Needs Trackback exit filter similar to spamshield_exit_jsck_filter() */
					spamshield_update_accept_status( $commentdata, 'r', 'Line: '.__LINE__, $wpss_error_code );
					if ( !empty( $spamshield_options['comment_logging'] ) ) { spamshield_log_data( $commentdata, $wpss_error_code ); }
					spamshield_denied_post_response( $commentdata, 'vague' );
					}
				}
			}

		/* 3RD - (Was 1ST), test if comment is too short or too long */
		$commentdata			= spamshield_content_length( $commentdata, $spamshield_options );
		$content_short_status	= $commentdata['content_short_status'];
		$content_long_status	= $commentdata['content_long_status']; /* Added 1.7.9 */

		if ( empty( $content_short_status ) && empty( $content_long_status ) ) {
			/* If it doesn't fail the comment length tests, run it through the content filter. This is where the magic happens... */

			/* 4TH - Full Content Filter */
			$commentdata			= spamshield_comment_content_filter( $commentdata, $spamshield_options );
			$content_filter_status	= $commentdata['content_filter_status'];
			/* Now we have a lot more power to work with */
			}

		$wpss_error_code = ltrim( $commentdata['wpss_error_code'] );

		if ( !empty( $content_short_status ) ) {
			spamshield_update_accept_status( $commentdata, 'r', 'Line: '.__LINE__, $wpss_error_code );
			if ( !empty( $spamshield_options['comment_logging'] ) ) { spamshield_log_data( $commentdata, $wpss_error_code ); }
			spamshield_denied_post_response( $commentdata, 'short' );
			}
		elseif ( !empty( $content_long_status ) ) {
			spamshield_update_accept_status( $commentdata, 'r', 'Line: '.__LINE__, $wpss_error_code );
			if ( !empty( $spamshield_options['comment_logging'] ) ) { spamshield_log_data( $commentdata, $wpss_error_code ); }
			spamshield_denied_post_response( $commentdata, 'long' );
			}
		elseif ( $content_filter_status == '2' ) {
			/*
			* Up to 1.8.2: Only comment filter using this is spamshield_revdns_filter(). Used in spamshield_bad_robot_blacklist_chk() and CF.
			* 1.8.4 on: No filters are using this.
			*/
			spamshield_update_accept_status( $commentdata, 'r', 'Line: '.__LINE__, $wpss_error_code );
			if ( !empty( $spamshield_options['comment_logging'] ) ) { spamshield_log_data( $commentdata, $wpss_error_code ); }
			spamshield_denied_post_response( $commentdata, 'network' );
			}
		elseif ( $content_filter_status == '3' ) { /* Added 1.8 */
			spamshield_update_accept_status( $commentdata, 'r', 'Line: '.__LINE__, $wpss_error_code );
			if ( !empty( $spamshield_options['comment_logging'] ) ) { spamshield_log_data( $commentdata, $wpss_error_code ); }
			spamshield_denied_post_response( $commentdata, 'vague' );
			}
		elseif ( $content_filter_status == '10' ) {
			spamshield_update_accept_status( $commentdata, 'r', 'Line: '.__LINE__, $wpss_error_code );
			if ( !empty( $spamshield_options['comment_logging'] ) ) { spamshield_log_data( $commentdata, $wpss_error_code ); }
			spamshield_denied_post_response( $commentdata, 'proxy' );
			}
		elseif ( $content_filter_status == '100' ) {
			spamshield_update_accept_status( $commentdata, 'r', 'Line: '.__LINE__, $wpss_error_code );
			if ( !empty( $spamshield_options['comment_logging'] ) ) { spamshield_log_data( $commentdata, $wpss_error_code ); }
			spamshield_denied_post_response( $commentdata, 'blacklist' );
			}
		elseif ( !empty( $content_filter_status ) ) {
			spamshield_update_accept_status( $commentdata, 'r', 'Line: '.__LINE__, $wpss_error_code );
			if ( !empty( $spamshield_options['comment_logging'] ) ) { spamshield_log_data( $commentdata, $wpss_error_code ); }
			spamshield_denied_post_content_filter( $commentdata );
			}

		/* ONLY IF NOT ADMINS, EDITORS - END */
		}

	/* No Error - Not a Spam Comment - Accepted */
	if ( !empty( $spamshield_options['comment_logging_all'] ) && ( empty( $wpss_error_code ) || ( !empty( $wpss_error_code ) && strpos( $wpss_error_code, 'No Error' ) === 0 ) ) ) {
		$wpss_error_code = 'No Error';
		$commentdata['wpss_error_code'] = ltrim( $wpss_error_code );
		spamshield_update_accept_status( $commentdata, 'a', 'Line: '.__LINE__ );
		spamshield_log_data( $commentdata, $wpss_error_code );
		}
	return $commentdata;
	}


/* REJECT BLOCKED SPAM COMMENTS - BEGIN */

function spamshield_denied_post_js_cookie( $commentdata = NULL ) {
	$error_txt = spamshield_error_txt();
	$error_msg_alt = '<strong>'.$error_txt.':</strong> ' . __( 'Sorry, there was an error. Please be sure JavaScript and Cookies are enabled in your browser and try again.', WPSS_PLUGIN_NAME );
	$error_msg = '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'JavaScript and Cookies are required in order to post a comment.', WPSS_PLUGIN_NAME ) . '</strong><br /><br />'."\n";
	$error_msg .= '<noscript>' . __( 'Status: JavaScript is currently disabled.', WPSS_PLUGIN_NAME ) . '<br /><br /></noscript>'."\n";
	$error_msg .= '<strong>' . __( 'Please be sure JavaScript and Cookies are enabled in your browser. Then, please hit the back button on your browser, and try posting your comment again. (You may need to reload the page.)', WPSS_PLUGIN_NAME ) . '</strong><br /><br />'."\n";
	$error_msg .= '<br /><hr noshade />'."\n";
	if ( !empty( $_COOKIE['SJECT15'] ) && $_COOKIE['SJECT15'] == 'CKON15' ) {
		$error_msg .= __( 'If you feel you have received this message in error (for example if JavaScript and Cookies are in fact enabled and you have tried to post several times), there is most likely a technical problem (could be a plugin conflict or misconfiguration). Please contact the author of this site, and let them know they need to look into it.', WPSS_PLUGIN_NAME ) . '<br />'."\n";
		$error_msg .= '<hr noshade /><br />'."\n";
		}
	$error_msg .= '</span>'."\n";
	$args = array( 'response' => '403' );
	wp_die( $error_msg, '', $args );
	}

function spamshield_denied_post_content_filter( $commentdata = NULL ) {
	$error_txt = spamshield_error_txt();
	$error_msg_alt = '<span style="font-size:12px;"><strong>'.$error_txt.':</strong> ' . __( 'Comments have been temporarily disabled to prevent spam. Please try again later.', WPSS_PLUGIN_NAME ) . '</span>'; /* Stop spammers without revealing why. */
	$error_msg = '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'Your comment appears to be spam.', WPSS_PLUGIN_NAME ) . '</strong><br /><br />'."\n";
	if ( $commentdata['wpss_error_code'] == '10500A-BL' && strpos( $commentdata['comment_author'], '@' ) !== FALSE ) {
		$error_msg .= sprintf( __( '"%1$s" appears to be spam. Please enter a different value in the <strong> %2$s </strong> field.', WPSS_PLUGIN_NAME ), sanitize_text_field($commentdata['comment_author']), __( 'Name' ) ) . '<br /><br />'."\n";
		}
	$error_msg .= __( 'Please go back and check all parts of your comment submission (including name, email, website, and comment content).', WPSS_PLUGIN_NAME ) . '</span>'."\n";
	if ( is_user_logged_in() ) {
		$error_msg .= '<br /><br />'."\n";
		$error_msg .= '<span style="font-size:12px;">' . __( 'If you are a logged in user, and you are seeing this message repeatedly, then you may need to check your registered user information for spam data.', WPSS_PLUGIN_NAME ) . '</span>'."\n";
		}
	$args = array( 'response' => '403' );
	wp_die( $error_msg, '', $args );
	}

function spamshield_denied_post_response( $commentdata = NULL, $error ) {
	$error_txt = spamshield_error_txt();
	$error_msgs = array(
		'short'		=> '<span style="font-size:12px;"><strong>'.$error_txt.':</strong> ' . __( 'Your comment was too short. Please go back and try your comment again.', WPSS_PLUGIN_NAME ) . '</span>',
		'long'		=> '<span style="font-size:12px;"><strong>'.$error_txt.':</strong> ' . __( 'Your comment was too long. Please go back and try your comment again.', WPSS_PLUGIN_NAME ) . '</span>', 
		'network'	=> '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'Your location has been identified as part of a reported spam network. Comments have been disabled to prevent spam.', WPSS_PLUGIN_NAME ) . '</strong></span>', 
		'vague'		=> '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'Comments have been temporarily disabled to prevent spam. Please try again later.', WPSS_PLUGIN_NAME ) . '</strong><br /><br /></span>', 
		'proxy'		=> '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'Your comment has been blocked because the website owner has set their spam filter to not allow comments from users behind proxies.', WPSS_PLUGIN_NAME ) . '</strong><br /><br />' . __( 'If you are a regular commenter or you feel that your comment should not have been blocked, please contact the site owner and ask them to modify this setting.', WPSS_PLUGIN_NAME ) . '</span>', 
		'blacklist'	=> '<span style="font-size:12px;"><strong>'.$error_txt.': ' . __( 'Your comment has been blocked based on the website owner\'s blacklist settings.', WPSS_PLUGIN_NAME ) . '</strong><br /><br />' . __( 'If you feel this is in error, please contact the site owner by some other method.', WPSS_PLUGIN_NAME ) . '</span>', 
		);
	$args = array( 'response' => '403' );
	wp_die( $error_msgs[$error], '', $args );
	}

/* REJECT BLOCKED SPAM COMMENTS - END */


/* COMMENT SPAM FILTERS - BEGIN */

function spamshield_content_length( $commentdata, $spamshield_options ) {
	/* COMMENT LENGTH CHECK - BEGIN */

	/* Timer Start  - Content Filter */
	if ( empty( $commentdata['start_time_content_filter'] ) ) {
		$wpss_start_time_content_filter				= spamshield_microtime();
		$commentdata['start_time_content_filter']	= $wpss_start_time_content_filter;
		}

	$content_short_status 						= $content_long_status = $wpss_error_code = ''; /* Must go before tests */
	$commentdata_comment_content				= $commentdata['comment_content'];
	$commentdata_comment_content_lc				= spamshield_casetrans('lower',$commentdata_comment_content);
	$commentdata_comment_content_lc_deslashed	= stripslashes($commentdata_comment_content_lc);
	$comment_length 							= $commentdata['body_content_len'];
	$comment_min_length 						= 15;
	$comment_max_length 						= 15360; /* 15kb */
	$commentdata_comment_type					= $commentdata['comment_type'];
	if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		if ( $comment_length < $comment_min_length ) {
			$content_short_status = TRUE;
			$wpss_error_code .= ' SHORT15';
			}
		if ( $comment_length > $comment_max_length ) {
			$content_long_status = TRUE;
			$wpss_error_code .= ' LONG15K';
			}
		}
	if ( !empty( $wpss_error_code ) ) {
		$wpss_error_code = ltrim( $wpss_error_code );
		spamshield_update_session_data($spamshield_options);
		/* Timer End - Content Filter */
		$wpss_end_time_content_filter 				= spamshield_microtime();
		$wpss_total_time_content_filter 			= spamshield_timer( $commentdata['start_time_content_filter'], $wpss_end_time_content_filter, FALSE, 6, TRUE );
		$commentdata['total_time_content_filter']	= $wpss_total_time_content_filter;
		}

	$commentdata['content_short_status'] 	= $content_short_status;
	$commentdata['content_long_status'] 	= $content_long_status;
	$commentdata['wpss_error_code'] 		= $wpss_error_code;
	return $commentdata;
	/* COMMENT LENGTH CHECK - END */
	}

function spamshield_exit_jsck_filter( $commentdata, $spamshield_options, $wpss_error_code ) {
	/***
	* Exit JS/CK Filter
	* This fires when a JavaScript/Cookies spam test is failed.
	***/

	$commentdata['wpss_error_code']			= $wpss_error_code = ltrim( $wpss_error_code );
	/* Timer End - Content Filter */
	$wpss_end_time_jsck_filter 				= spamshield_microtime();
	$wpss_total_time_jsck_filter 			= spamshield_timer( $commentdata['start_time_jsck_filter'], $wpss_end_time_jsck_filter, FALSE, 6, TRUE );
	$commentdata['total_time_jsck_filter']	= $wpss_total_time_jsck_filter;
	spamshield_update_accept_status( $commentdata, 'r', 'Line: '.__LINE__, $wpss_error_code );
	if ( !empty( $spamshield_options['comment_logging'] ) ) { spamshield_log_data( $commentdata, $wpss_error_code ); }
	spamshield_denied_post_js_cookie( $commentdata );
	}

function spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status ) {
	/***
	* Exit Content Filter
	* This fires when an algo spam test is failed.
	***/

	$commentdata['wpss_error_code']				= $wpss_error_code = ltrim( $wpss_error_code );
	/* Timer End - Content Filter */
	$wpss_end_time_content_filter 				= spamshield_microtime();
	$wpss_total_time_content_filter 			= spamshield_timer( $commentdata['start_time_content_filter'], $wpss_end_time_content_filter, FALSE, 6, TRUE );
	$commentdata['total_time_content_filter']	= $wpss_total_time_content_filter;
	$commentdata['content_filter_status']		= $content_filter_status;
	return $commentdata;
	}

function spamshield_trackback_content_filter( $commentdata, $spamshield_options ) {
	/***
	* Trackback Content Filter
	* This will knock out 98% of Trackback Spam
	* Keeping this separate and before trackback IP filter because it's fast
	* If passes this, then next filter will take out the rest
	***/

	/* Timer Start  - Content Filter */
	if ( empty( $commentdata['start_time_content_filter'] ) ) {
		$wpss_start_time_content_filter = spamshield_microtime();
		$commentdata['start_time_content_filter'] = $wpss_start_time_content_filter;
		}

	$content_filter_status 						= $wpss_error_code = ''; /* Must go before tests */

	$block_all_trackbacks 						= $spamshield_options['block_all_trackbacks'];
	$block_all_pingbacks 						= $spamshield_options['block_all_pingbacks'];

	$commentdata_comment_type					= $commentdata['comment_type'];

	$commentdata_comment_author					= $commentdata['comment_author'];
	$commentdata_comment_author_deslashed		= stripslashes($commentdata_comment_author);
	$commentdata_comment_author_lc				= spamshield_casetrans('lower',$commentdata_comment_author);
	$commentdata_comment_author_lc_deslashed	= stripslashes($commentdata_comment_author_lc);
	$commentdata_comment_author_url				= $commentdata['comment_author_url'];
	$commentdata_comment_author_url_lc			= spamshield_casetrans('lower',$commentdata_comment_author_url);
	$commentdata_comment_author_url_domain_lc	= spamshield_get_domain($commentdata_comment_author_url_lc);
	$commentdata_comment_content				= $commentdata['comment_content'];
	$commentdata_comment_content_lc				= spamshield_casetrans('lower',$commentdata_comment_content);
	$commentdata_comment_content_lc_deslashed	= stripslashes($commentdata_comment_content_lc);
	/***
	* For 1-HT Test - Other version using spamshield_parse_links() is more robust but not needed yet - current implementation is faster.
	***/

	$commentdata_remote_addr					= $_SERVER['REMOTE_ADDR'];
	$commentdata_remote_addr_lc					= spamshield_casetrans('lower',$commentdata_remote_addr);
	$commentdata_user_agent 					= spamshield_get_user_agent( TRUE, FALSE );
	$commentdata_user_agent_lc					= spamshield_casetrans('lower',$commentdata_user_agent);
	$commentdata_user_agent_lc_word_count 		= spamshield_count_words($commentdata_user_agent_lc);
	$trackback_length 							= $commentdata['body_content_len'];
	$trackback_max_length 						= 3072; /* 3kb */

	$commentdata_comment_author_lc_spam_strong = '<strong>'.$commentdata_comment_author_lc_deslashed.'</strong>'; /* Trackbacks */

	$commentdata_comment_author_lc_spam_strong_dot1 = '...</strong>'; /* Trackbacks */
	$commentdata_comment_author_lc_spam_strong_dot2 = '...</b>'; /* Trackbacks */
	$commentdata_comment_author_lc_spam_strong_dot3 = '<strong>...'; /* Trackbacks */
	$commentdata_comment_author_lc_spam_strong_dot4 = '<b>...'; /* Trackbacks */
	$commentdata_comment_author_lc_spam_a1 = $commentdata_comment_author_lc_deslashed.'</a>'; /* Trackbacks/Pingbacks */
	$commentdata_comment_author_lc_spam_a2 = $commentdata_comment_author_lc_deslashed.' </a>'; /* Trackbacks/Pingbacks */

	if ( $commentdata_remote_addr == RSMP_SERVER_ADDR && $commentdata['comment_type'] == 'pingback' ) { $local_pingback = TRUE; } else { $local_pingback = FALSE; }

	if ( !empty( $block_all_trackbacks ) && $commentdata['comment_type'] == 'trackback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' BLOCKING-TRACKBACKS ';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	if ( !empty( $block_all_pingbacks ) && $commentdata['comment_type'] == 'pingback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' BLOCKING-PINGBACKS';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* Check Length */
	if ( $trackback_length > $trackback_max_length ) {
		/* There is no reason for an exceptionally long Trackback or Pingback. */
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' T-LONG3K';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	/* Test User-Agents */
	if ( empty( $commentdata_user_agent_lc ) ) {
		/* There is no reason for a blank UA String, unless it's been altered. */
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' TUA1001';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	$trackback_is_mobile = wp_is_mobile();
	$trackback_is_firefox = spamshield_is_firefox();
	global $is_chrome, $is_IE, $is_gecko, $is_opera, $is_safari, $is_iphone, $is_lynx, $is_NS4;
	if ( $trackback_is_mobile || $trackback_is_firefox || $is_chrome || $is_IE || $is_gecko || $is_opera || $is_safari || $is_iphone || $is_lynx || $is_NS4 ) {
		/* There is no reason for a normal browser's UA String to be used in a Trackback/Pingback, unless it's been altered. */
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' TUA1002';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/***
	* TUA1003 - Another test for altered UA's.
	* DEPRECATED - Removed 1.7.5
	***/

	if ( spamshield_skiddie_ua_check( $commentdata_user_agent_lc ) ) {
		/* There is no reason for a human or Trackback/Pingback to use one of these UA strings. Commonly used to attack/spam WP. */
		$content_filter_status = '3';
		$wpss_error_code .= ' TUA1004';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* TRACKBACK/PINGBACK SPECIFIC TESTS -  BEGIN */

	/* TRACKBACK COOKIE TEST - Trackbacks can't have cookies, but some fake ones do. SMH. */
	if ( !empty( $_COOKIE ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' T-COOKIE';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* Body Content - Check for excessive number of links (any) in trackback ( body_content ) */
	$trackback_count_http 	= spamshield_substr_count( $commentdata_comment_content_lc_deslashed, 'http://' );
	$trackback_count_https 	= spamshield_substr_count( $commentdata_comment_content_lc_deslashed, 'https://' );
	$trackback_num_links 	= $trackback_count_http + $trackback_count_https;
	$trackback_num_limit 	= 0;
	if ( empty( $local_pingback ) && $trackback_num_links > $trackback_num_limit ) { /* Not using spamshield_parse_links() since this should be zero anyway, this is faster */
		/* Genuine trackbacks should have text only, not hyperlinks */
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' T-1-HT';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	if ( preg_match( "~\[\.{1,3}\]\s*\[\.{1,3}\]~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' T200-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/***
	* T3000-1 - WordPress UA for a Trackback
	* DEPRECATED - Removed 1.7.5
	***/

	/***
	* T1001-1, T1002-1, T1003-1
	* Testing if Bot Uses Faked User-Agent for WordPress version that doesn't exist yet
	* Check History of WordPress User-Agents and Keep up to Date
	* Current: 'The Incutio XML-RPC PHP Library -- WordPress/4.0.1'
	* DEPRECATED - Removed 1.7.5
	***/

	/***
	* T1010-1
	* Check to see if Comment Author is lowercase. Normal blog ping Authors are properly capitalized. No brainer.
	* DEPRECATED - Removed 1.7.5
	***/

	/* IP / PROXY INFO - BEGIN */
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
	$reverse_dns_verification 		= $ip_proxy_info['reverse_dns_verification'];
	$ip_proxy_via 					= $ip_proxy_info['ip_proxy_via'];
	$ip_proxy_via_lc 				= $ip_proxy_info['ip_proxy_via_lc'];
	$masked_ip 						= $ip_proxy_info['masked_ip'];
	$ip_proxy 						= $ip_proxy_info['ip_proxy'];
	$ip_proxy_short 				= $ip_proxy_info['ip_proxy_short'];
	$ip_proxy_data 					= $ip_proxy_info['ip_proxy_data'];
	$proxy_status 					= $ip_proxy_info['proxy_status'];
	$ip_proxy_chrome_compression	= $ip_proxy_info['ip_proxy_chrome_compression'];
	/* IP / PROXY INFO - END */

	if ( empty( $local_pingback ) && $ip_proxy == 'PROXY DETECTED' ) {
		/* Check to see if Trackback/Pingback is using proxy. Real ones don't do that since they come directly from a website/server. */
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' T1011-FPD-1';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* REVDNS FILTER */
	$rev_dns_filter_data = spamshield_revdns_filter( 'trackback', $content_filter_status, $ip, $reverse_dns_lc, $commentdata_comment_author_lc_deslashed );
	$revdns_blacklisted = $rev_dns_filter_data['blacklisted'];
	if ( !empty( $revdns_blacklisted ) ) {
		$content_filter_status = $rev_dns_filter_data['status'];
		$wpss_error_code .= $rev_dns_filter_data['error_code'];
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/***
	* MISC - T1020-1, T2003-1, T2003-1, T2004-1, T2005-1, T2006-1, T2007-1-1, T2007-2-1, T2010-1, T3001-1, T3002-1, T3003-1-1, T3003-2-1, T3003-3-1, T9000 Variants
	* DEPRECATED - Removed 1.7.5
	***/

	/* Blacklisted Domains Check */
	if ( spamshield_domain_blacklist_chk( $commentdata_comment_author_url_domain_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' T-10500AU-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* Check for URL Shorteners, Bogus Long URLs, Social Media, and Misc Spam Domains */
	if ( spamshield_at_link_spam_url_chk( $commentdata_comment_author_url_lc, 'trackback' ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
		$wpss_error_code .= ' T-10510AU-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* TRACKBACK/PINGBACK SPECIFIC TESTS -  END */

	/***
	* return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
	***/

	/* After spamshield_exit_content_filter() implemented, can remove following code - BEGIN */
	if ( !empty( $wpss_error_code ) ) {
		$wpss_error_code = ltrim( $wpss_error_code );
		/* Timer End - Content Filter */
		$wpss_end_time_content_filter 				= spamshield_microtime();
		$wpss_total_time_content_filter 			= spamshield_timer( $commentdata['start_time_content_filter'], $wpss_end_time_content_filter, FALSE, 6, TRUE );
		$commentdata['total_time_content_filter']	= $wpss_total_time_content_filter;
		}
	/* After spamshield_exit_content_filter() implemented, can remove previous code - END */

	$commentdata['wpss_error_code'] = ltrim( $wpss_error_code );
	$commentdata['content_filter_status'] = $content_filter_status;
	return $commentdata;
	}

function spamshield_trackback_ip_filter( $commentdata, $spamshield_options ) {
	/***
	* Trackback IP Filter
	* This will knock out 99.99% of Trackback Spam
	* Keeping this separate and before content filter because it's fast
	* If passes this, then content filter will take out the rest
	***/

	/* Timer Start  - Content Filter */
	if ( empty( $commentdata['start_time_content_filter'] ) ) {
		$wpss_start_time_content_filter				= spamshield_microtime();
		$commentdata['start_time_content_filter']	= $wpss_start_time_content_filter;
		}

	$content_filter_status 				= $wpss_error_code = ''; /* Must go before tests */

	$commentdata_remote_addr			= $_SERVER['REMOTE_ADDR'];
	$commentdata_remote_addr_lc			= spamshield_casetrans('lower',$commentdata_remote_addr);
	$commentdata_comment_type			= $commentdata['comment_type'];
	$commentdata_comment_author_url		= $commentdata['comment_author_url'];
	$commentdata_comment_author_url_lc	= spamshield_casetrans('lower',$commentdata_comment_author_url);

	/* Check to see if IP Trackback client IP matches IP of Server where link is supposedly coming from */
	if ( $commentdata_comment_type == 'trackback' ) {
		$trackback_domain = spamshield_get_domain($commentdata_comment_author_url_lc);
		$trackback_ip = gethostbyname($trackback_domain);
		if ( $commentdata_remote_addr_lc != $trackback_ip ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; }
			$wpss_error_code .= ' T1000-FIP-1';
			}
		}

	if ( !empty( $wpss_error_code ) ) {
		$wpss_error_code = ltrim( $wpss_error_code );
		/* Timer End - Content Filter */
		$wpss_end_time_content_filter 				= spamshield_microtime();
		$wpss_total_time_content_filter 			= spamshield_timer( $commentdata['start_time_content_filter'], $wpss_end_time_content_filter, FALSE, 6, TRUE );
		$commentdata['total_time_content_filter']	= $wpss_total_time_content_filter;
		}

	$commentdata['wpss_error_code']			= ltrim( $wpss_error_code );
	$commentdata['content_filter_status']	= $content_filter_status;
	return $commentdata;
	}

function spamshield_comment_content_filter( $commentdata, $spamshield_options ) {
	/***
	* Content Filter aka "The Algorithmic Layer"
	* Blocking the Obvious to Improve Human/Pingback/Trackback Defense
	***/

	/* Timer Start  - Content Filter */
	if ( empty( $commentdata['start_time_content_filter'] ) ) {
		$wpss_start_time_content_filter = spamshield_microtime();
		$commentdata['start_time_content_filter'] = $wpss_start_time_content_filter;
		}

	$content_filter_status = $wpss_error_code = ''; /* Must go before tests */

	spamshield_update_session_data($spamshield_options);

	/* TEST 0 - See if user has already been blacklisted this session */
	if ( !is_user_logged_in() && ( spamshield_ubl_cache() ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '3'; } /* 1.8 - Changed from '2' to '3' */
		$wpss_error_code .= ' 0-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }
	$post_ref2xjs_lc = spamshield_casetrans('lower',$post_ref2xjs);
	$wpss_date_this_year = date('Y');
	$wpss_date_next_year = $wpss_date_this_year + 1;
	$wpss_date_last_year = $wpss_date_this_year - 1;

	/* CONTENT FILTERING - BEGIN */

	$commentdata_comment_post_id					= $commentdata['comment_post_ID'];
	$commentdata_comment_post_title					= $commentdata['comment_post_title'];
	$commentdata_comment_post_title_lc				= spamshield_casetrans('lower',$commentdata_comment_post_title);
	$commentdata_comment_post_title_lc_regex 		= spamshield_preg_quote($commentdata_comment_post_title_lc);
	$commentdata_comment_post_url					= $commentdata['comment_post_url'];
	$commentdata_comment_post_url_lc				= spamshield_casetrans('lower',$commentdata_comment_post_url);
	$commentdata_comment_post_url_lc_regex 			= spamshield_preg_quote($commentdata_comment_post_url_lc);

	$commentdata_comment_post_type					= $commentdata['comment_post_type'];
	/* Possible results: 'post', 'page', 'attachment', 'revision', 'nav_menu_item' */

	/* Next two are boolean */
	$commentdata_comment_post_comments_open			= $commentdata['comment_post_comments_open'];
	$commentdata_comment_post_pings_open			= $commentdata['comment_post_pings_open'];

	$commentdata_comment_author						= $commentdata['comment_author'];
	$commentdata_comment_author_deslashed			= stripslashes($commentdata_comment_author);
	$commentdata_comment_author_lc					= spamshield_casetrans('lower',$commentdata_comment_author);
	$commentdata_comment_author_lc_regex 			= spamshield_preg_quote($commentdata_comment_author_lc);
	$commentdata_comment_author_lc_words 			= spamshield_count_words($commentdata_comment_author_lc);
	$commentdata_comment_author_lc_space 			= ' '.$commentdata_comment_author_lc.' ';
	$commentdata_comment_author_lc_deslashed		= stripslashes($commentdata_comment_author_lc);
	$commentdata_comment_author_lc_deslashed_regex 	= spamshield_preg_quote($commentdata_comment_author_lc_deslashed);
	$commentdata_comment_author_lc_deslashed_words 	= spamshield_count_words($commentdata_comment_author_lc_deslashed);
	$commentdata_comment_author_lc_deslashed_space 	= ' '.$commentdata_comment_author_lc_deslashed.' ';
	$commentdata_comment_author_email				= $commentdata['comment_author_email'];
	$commentdata_comment_author_email_lc			= spamshield_casetrans('lower',$commentdata_comment_author_email);
	$commentdata_comment_author_email_lc_regex 		= spamshield_preg_quote($commentdata_comment_author_email_lc);
	$commentdata_comment_author_url					= $commentdata['comment_author_url'];
	$commentdata_comment_author_url_lc				= spamshield_casetrans('lower',$commentdata_comment_author_url);
	$commentdata_comment_author_url_lc_regex 		= spamshield_preg_quote($commentdata_comment_author_url_lc);
	$commentdata_comment_author_url_domain_lc		= spamshield_get_domain($commentdata_comment_author_url_lc);

	$commentdata_comment_content					= $commentdata['comment_content'];
	$commentdata_comment_content_lc					= spamshield_casetrans('lower',$commentdata_comment_content);
	$commentdata_comment_content_lc_deslashed		= stripslashes($commentdata_comment_content_lc);
	$commentdata_comment_content_extracted_urls 	= spamshield_parse_links( $commentdata_comment_content_lc_deslashed, 'url' ); /* Parse comment content for all URLs */
	$commentdata_comment_content_extracted_urls_at 	= spamshield_parse_links( $commentdata_comment_content_lc_deslashed, 'url_at' ); /* Parse comment content for Anchor Text Link URLs */
	$commentdata_comment_content_num_links 			= count( $commentdata_comment_content_extracted_urls ); /* Count extracted URLS from body content - Added 1.8.4 */
	$commentdata_comment_content_num_limit			= 3; /* Max number of links in comment body content */

	$replace_apostrophes							= array('’','`','&acute;','&grave;','&#39;','&#96;','&#101;','&#145;','&#146;','&#158;','&#180;','&#207;','&#208;','&#8216;','&#8217;');
	$commentdata_comment_content_lc_norm_apost 		= str_replace($replace_apostrophes,"'",$commentdata_comment_content_lc_deslashed);

	$commentdata_comment_type						= $commentdata['comment_type'];

	/*
	if ( $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		$commentdata_comment_type = 'comment';
		}
	*/

	$commentdata_user_agent 			= spamshield_get_user_agent( TRUE, FALSE );
	$commentdata_user_agent_lc			= spamshield_casetrans('lower',$commentdata_user_agent);

	$user_http_accept					= spamshield_get_http_accept( TRUE, TRUE );
	$user_http_accept_language 			= spamshield_get_http_accept( TRUE, TRUE, TRUE );

	$commentdata_remote_addr			= $_SERVER['REMOTE_ADDR'];
	$commentdata_remote_addr_regex 		= spamshield_preg_quote($commentdata_remote_addr);
	$commentdata_remote_addr_lc			= spamshield_casetrans('lower',$commentdata_remote_addr);
	$commentdata_remote_addr_lc_regex 	= spamshield_preg_quote($commentdata_remote_addr_lc);

	$commentdata_referrer				= spamshield_get_referrer();
	$commentdata_referrer_lc			= spamshield_casetrans('lower',$commentdata_referrer);
	$commentdata_blog					= RSMP_SITE_URL;
	$commentdata_blog_lc				= spamshield_casetrans('lower',$commentdata_blog);
	$commentdata_php_self				= $_SERVER['PHP_SELF'];
	$commentdata_php_self_lc			= spamshield_casetrans('lower',$commentdata_php_self);
	$wp_comments_post_url 				= $commentdata_blog_lc.'/wp-comments-post.php';

	$blog_server_ip 					= RSMP_SERVER_ADDR;
	$blog_server_name 					= RSMP_SERVER_NAME;

	/* IP / PROXY INFO - BEGIN */
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
	$reverse_dns_verification 		= $ip_proxy_info['reverse_dns_verification'];
	$ip_proxy_via 					= $ip_proxy_info['ip_proxy_via'];
	$ip_proxy_via_lc 				= $ip_proxy_info['ip_proxy_via_lc'];
	$masked_ip 						= $ip_proxy_info['masked_ip'];
	$ip_proxy 						= $ip_proxy_info['ip_proxy'];
	$ip_proxy_short 				= $ip_proxy_info['ip_proxy_short'];
	$ip_proxy_data 					= $ip_proxy_info['ip_proxy_data'];
	$proxy_status 					= $ip_proxy_info['proxy_status'];
	$ip_proxy_chrome_compression	= $ip_proxy_info['ip_proxy_chrome_compression'];
	/* IP / PROXY INFO - END */

	/***
	* Post Type Filter - INVALTY
	* Removed V 1.1.7 - Found Exception
	***/

	/* Simple Filters */

	/* BEING DEPRECATED... */
	$blacklist_word_combo_total_limit = 10; /* you may increase to 30+ if blog's topic is adult in nature - DEPRECATED */
	$blacklist_word_combo_total = 0;

	/* Body Content - Check for excessive number of links in message ( body_content ) - 1.8.4 */
	if ( $commentdata_comment_content_num_links > $commentdata_comment_content_num_limit ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 1-HT';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/***
	* Authors Only - Non-Trackback
	* Removed Filters 300-423 and replaced with Regex
	***/

	/* Author Blacklist Check - Invalid Author Names - Stopping Human Spam */
	if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' && spamshield_anchortxt_blacklist_chk( $commentdata_comment_author_lc_deslashed, '', 'author', $commentdata_comment_author_url_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10500A-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* Regular Expression Tests - 2nd Gen - Comment Author/Author URL - BEGIN */

	/* 10500-13000 - Complex Test for terms in Comment Author/URL - $commentdata_comment_author_lc_deslashed/$commentdata_comment_author_url_domain_lc */

	/* Blacklisted Domains Check */
	if ( spamshield_domain_blacklist_chk( $commentdata_comment_author_url_domain_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10500AU-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* Check for URL Shorteners, Bogus Long URLs, and Misc Spam Domains */
	if ( spamshield_at_link_spam_url_chk( $commentdata_comment_author_url_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10510AU-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* Testing for a unique identifying string from the comment content in the Author URL Domain */
	preg_match( "~\s+([a-z0-9]{6,})$~i", $commentdata_comment_content_lc_deslashed, $wpss_str_matches );
	if ( !empty( $wpss_str_matches[1] ) ) { $wpss_spammer_id_string = $wpss_str_matches[1]; } else { $wpss_spammer_id_string = ''; }
	$commentdata_comment_author_url_domain_lc_elements = explode( '.', $commentdata_comment_author_url_domain_lc );
	$commentdata_comment_author_url_domain_lc_elements_count = count( $commentdata_comment_author_url_domain_lc_elements ) - 1;
	if ( !empty ( $wpss_spammer_id_string ) ) {
		$i = 0;
		/* The following line to prevent exploitation: */
		$i_max = 20;
		while ( $i < $commentdata_comment_author_url_domain_lc_elements_count && $i < $i_max ) {
			if ( !empty( $commentdata_comment_author_url_domain_lc_elements[$i] ) ) {
				if ( $commentdata_comment_author_url_domain_lc_elements[$i] == $wpss_spammer_id_string ) {
					if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
					$wpss_error_code .= ' 10511AUA';
					return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
					}
				}
			$i++;
			}
		}
	/***
	* Potential Exploits
	* Includes protection for Trackbacks and Pingbacks
	***/

	/* Check Author URL for Exploits */
	if ( spamshield_exploit_url_chk( $commentdata_comment_author_url_lc ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 15000AU-XPL'; /* Added in 1.4 - Replacing 15001AU-XPL and 15002AU-XPL, and adds additional protection */
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* Regular Expression Tests - 2nd Gen - Comment Author/Author URL - END */

	$blacklist_word_combo_limit = 7;
	$blacklist_word_combo = 0;

	$i = 0;

	/* Regular Expression Tests - 2nd Gen - Comment Content - BEGIN */

	/* Miscellaneous Patterns that Keep Repeating */
	if ( preg_match( "~^([0-9]{6})\s([0-9]{6})(.*)\s([0-9]{6})$~i", $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10401C';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	/* Blacklisted Anchor Text Check - Links in Content - Stopping Human Spam */
	if ( spamshield_anchortxt_blacklist_chk( $commentdata_comment_content_lc_deslashed, '', 'content' ) && $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10500CAT-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	/* Blacklisted Domains Check - Links in Content */
	if ( spamshield_link_blacklist_chk( $commentdata_comment_content_lc_deslashed ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10500CU-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	/* Check Anchor Text Links for URL Shorteners, Bogus Long URLs, and Misc Spam Domains */
	if ( spamshield_at_link_spam_url_chk( $commentdata_comment_content_extracted_urls_at ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 10510CU-BL'; /* Replacing 10510CU-MSC */
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* Check all URL's in Comment Content for Exploits */
	if ( spamshield_exploit_url_chk( $commentdata_comment_content_extracted_urls ) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 15000CU-XPL'; /* Added in 1.4 */
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* Regular Expression Tests - 2nd Gen - Comment Content - END */


	/***
	* Test Comment Author
	* Words in Comment Author Repeated in Content - With Keyword Density
	***/
	$repeated_terms_filters			= array('.','-',':');
	$repeated_terms_temp_phrase		= str_replace($repeated_terms_filters,'',$commentdata_comment_author_lc_deslashed);
	$repeated_terms_test			= explode(' ',$repeated_terms_temp_phrase);
	$repeated_terms_test_count		= count($repeated_terms_test);
	$comment_content_total_words	= spamshield_count_words($commentdata_comment_content_lc_deslashed);
	$i = 0;
	while ( $i < $repeated_terms_test_count ) {
		if ( !empty( $repeated_terms_test[$i] ) ) {
			$repeated_terms_in_content_count = spamshield_substr_count( $commentdata_comment_content_lc_deslashed, $repeated_terms_test[$i] );
			$repeated_terms_in_content_str_len = spamshield_strlen($repeated_terms_test[$i]);
			if ( $repeated_terms_in_content_count > 1 && $comment_content_total_words < $repeated_terms_in_content_count ) {
				$repeated_terms_in_content_count = 1;
				}
			$repeated_terms_in_content_density = ( $repeated_terms_in_content_count / $comment_content_total_words ) * 100;
			if ( $repeated_terms_in_content_count >= 5 && $repeated_terms_in_content_str_len >= 4 && $repeated_terms_in_content_density > 40 ) {
				if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
				$wpss_error_code .= ' 9000-'.$i;
				return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
				}
			}
		$i++;
		}

	/* Comment Author and URL Tests */
	if ( !empty( $commentdata_comment_author_url_lc ) && !empty( $commentdata_comment_author_lc_deslashed ) ) {
		/* Comment Author and Comment Author URL appearing in Content - REGEX VERSION */
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
			/* Changed to include Trackbacks and Pingbacks in 1.1.4.4 */
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' 9102';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		if ( $commentdata_comment_author_url_lc == $commentdata_comment_author_lc_deslashed && !preg_match( "~https?\:/+~i", $commentdata_comment_author_url_lc ) && preg_match( "~(https?\:/+[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+)~i", $commentdata_comment_content_lc_deslashed ) ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' 9103';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}

	/***
	* Email Filters
	* New Test with Blacklists
	***/
	if ( spamshield_email_blacklist_chk($commentdata_comment_author_email_lc) ) {
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' 9200E-BL';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* TEST REFERRERS 1 - TO THE COMMENT PROCESSOR */
	if ( strpos( $wp_comments_post_url, $commentdata_php_self_lc ) !== FALSE && $commentdata_referrer_lc == $wp_comments_post_url ) {
		/* Often spammers send the referrer as the URL for the wp-comments-post.php page. */
		if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
		$wpss_error_code .= ' REF-1-1011';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}

	/* TEST REFERRERS 2 - SPAMMERS SEARCHING FOR PAGES TO COMMENT ON */
	if ( !empty( $post_ref2xjs ) ) {
		$ref2xJS = addslashes( urldecode( $post_ref2xjs ) );
		$ref2xJS = str_replace( '%3A', ':', $ref2xJS );
		$ref2xJS = str_replace( ' ', '+', $ref2xJS );
		$ref2xJS = esc_url_raw( $ref2xJS );
		$ref2xJS_lc = spamshield_casetrans( 'lower', $ref2xJS );
		if ( preg_match( "~\.google\.co(m|\.[a-z]{2})~i", $ref2xJS ) && strpos( $ref2xJS_lc, 'leave a comment' ) !== FALSE ) {
			/* make test more robust for other versions of google & search query */
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' REF-2-1021';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		/* add Keyword Script Here */
		}

	/***
	* TEST REFERRERS 3 - TO THE PAGE BEING COMMENTED ON
	* DISABLED IN V1.5.9
	***/

	/* Spam Network - BEGIN */

	/***
	* PART OF BAD ROBOTS TEST - BEGIN
	* Test User-Agents
	***/
	if ( empty( $commentdata_user_agent_lc ) ) {
		/* There is no reason for a blank UA String, unless it's been altered or a bot. */
		$content_filter_status = '3'; /* Was 1, changed to 3 - V1.8.4 */
		$wpss_error_code .= ' UA1001';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	$commentdata_user_agent_lc_word_count = spamshield_count_words($commentdata_user_agent_lc);
	if ( !empty( $commentdata_user_agent_lc ) && $commentdata_user_agent_lc_word_count < 3 ) {
		if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' || ( strpos( $commentdata_user_agent_lc, 'movabletype' ) === FALSE && $commentdata_comment_type == 'trackback' ) ) {
			/* Another test for altered UA's. */
			$content_filter_status = '3'; /* Was 1, changed to 3 - V1.8.4 */
			$wpss_error_code .= ' UA1003';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	if ( spamshield_skiddie_ua_check( $commentdata_user_agent_lc ) ) {
		/* There is no reason for a human to use one of these UA strings. Commonly used to attack/spam WP. */
		$content_filter_status = '3'; /* Was 1, changed to 3 - V1.8.4 */
		$wpss_error_code .= ' UA1004';
		return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
		}
	/* PART OF BAD ROBOTS TEST - END */

	if ( $commentdata_comment_type != 'trackback' && $commentdata_comment_type != 'pingback' ) {

		/***
		* PART OF BAD ROBOTS TEST - BEGIN
		* Test HTTP_ACCEPT
		***/
		if ( empty( $user_http_accept ) ) {
			$content_filter_status = '3'; /* Was 1, changed to 3 - V1.8.4 */
			$wpss_error_code .= ' HA1001';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		if ( $user_http_accept == 'application/json, text/javascript, */*; q=0.01' ) {
			$content_filter_status = '3'; /* Was 1, changed to 3 - V1.8.4 */
			$wpss_error_code .= ' HA1002';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		if ( $user_http_accept == '*' ) {
			$content_filter_status = '3'; /* Was 1, changed to 3 - V1.8.4 */
			$wpss_error_code .= ' HA1003';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		/* More complex test for invalid 'HTTP_ACCEPT' */
		$user_http_accept_mod_1 = preg_replace( "~([\s\;]+)~", ",", $user_http_accept );
		$user_http_accept_elements = explode( ',', $user_http_accept_mod_1 );
		$user_http_accept_elements_count = count($user_http_accept_elements);
		$i = 0;
		/* The following line to prevent exploitation: */
		$i_max = 20;
		while ( $i < $user_http_accept_elements_count && $i < $i_max ) {
			if ( !empty( $user_http_accept_elements[$i] ) ) {
				if ( $user_http_accept_elements[$i] == '*' ) {
					$content_filter_status = '3'; /* Was 1, changed to 3 - V1.8.4 */
					$wpss_error_code .= ' HA1004';
					return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
					}
				}
			$i++;
			}

		/* Test HTTP_ACCEPT_LANGUAGE */
		if ( empty( $user_http_accept_language ) ) {
			$content_filter_status = '3'; /* Was 1, changed to 3 - V1.8.4 */
			$wpss_error_code .= ' HAL1001';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		if ( $user_http_accept_language == '*' ) {
			$content_filter_status = '3'; /* Was 1, changed to 3 - V1.8.4 */
			$wpss_error_code .= ' HAL1002';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		/* More complex test for invalid 'HTTP_ACCEPT_LANGUAGE' */
		$user_http_accept_language_mod_1 = preg_replace( "~([\s\;]+)~", ",", $user_http_accept_language );
		$user_http_accept_language_elements = explode( ',', $user_http_accept_language_mod_1 );
		$user_http_accept_language_elements_count = count($user_http_accept_language_elements);
		$i = 0;
		/* The following line to prevent exploitation: */
		$i_max = 20;
		while ( $i < $user_http_accept_language_elements_count && $i < $i_max ) {
			if ( !empty( $user_http_accept_language_elements[$i] ) ) {
				if ( $user_http_accept_language_elements[$i] == '*' && strpos( $commentdata_user_agent_lc, 'links (' ) !== 0 ) {
					$content_filter_status = '3'; /* Was 1, changed to 3 - V1.8.4 */
					$wpss_error_code .= ' HAL1004';
					return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
					}
				}
			$i++;
			}

		/***
		* HAL1005 - NOT IMPLEMENTED
		* PART OF BAD ROBOTS TEST - END
		***/

		/***
		* Test PROXY STATUS if option
		* Google Chrome Compression Proxy Bypass
		***/
		if ( $ip_proxy == 'PROXY DETECTED' && $ip_proxy_chrome_compression != 'TRUE' && empty( $spamshield_options['allow_proxy_users'] ) ) {
			$content_filter_status = '10';
			$wpss_error_code .= ' PROXY1001';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}

		}

	/***
	* Test IPs - was here
	* IP1003 - Removed in 1.8
	***/

	/* Reverse DNS Server Tests - BEGIN */
	if ( $commentdata_comment_type != 'pingback' && $commentdata_comment_type != 'trackback' ) {
		/* Test Reverse DNS Hosts - Do all with Reverse DNS not Remote Host */
		$rev_dns_filter_data = spamshield_revdns_filter( 'comment', $content_filter_status, $ip, $reverse_dns_lc, $commentdata_comment_author_lc_deslashed, $commentdata_comment_author_email_lc );
		$revdns_blacklisted = $rev_dns_filter_data['blacklisted'];
		if ( !empty( $revdns_blacklisted ) ) {
			$content_filter_status = $rev_dns_filter_data['status'];
			$wpss_error_code .= $rev_dns_filter_data['error_code'];
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	/* Reverse DNS Server Tests - END */

	/* Spam Network - END */


	/* Test Pingbacks and Trackbacks - OLD LOCATION */

	/* Miscellaneous Preg Match Tests - Changed to regex in V1.8.4 */
	$wpss_misc_spam_phrases_to_check = 
		array(
			'5000' => "~\[\.+\]\s+\[\.+\]~",
			'5001' => "~^<new\s+comment>$~i",
			'5003' => "~^([a-z0-9\s\.,!]{0,12})?((he.a?|h([ily]{1,2}))(\s+there)?|howdy|hello|bonjour|good\s+day)([\.,!])?\s+(([ily]{1,2})\s+know\s+)?th([ily]{1,2})s\s+([ily]{1,2})s\s+([a-z\s]{3,12}|somewhat|k([ily]{1,2})nd\s*of)?(of{1,2}\s+)?of{1,2}\s+top([ily]{1,2})c\s+(but|however)\s+([ily]{1,2})\s+(was\s+wonder([ily]{1,2})nn?g?|need\s+some\s+adv([ily]{1,2})ce)~i",
			'5004' => "~^th([ily]{1,2})s\s+([ily]{1,2})s\s+k([ily]{1,2})nd\s+of\s+off\s+top([ily]{1,2})c\s+but~i",
			);
			/* 5002 - Removed in V1.8.4 */
	foreach ( $wpss_misc_spam_phrases_to_check as $ec => $rgx_phrase ) {
		if ( preg_match( $rgx_phrase, $commentdata_comment_content_lc_deslashed ) ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '1'; }
			$wpss_error_code .= ' '.$ec;
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}


	/* BOILERPLATE: Add common boilerplate/template spam phrases... Add Blacklist functions */

	/* WP Blacklist Check - BEGIN */

	/* Test WP Blacklist if option set */
	if ( !empty( $spamshield_options['enhanced_comment_blacklist'] ) && empty( $content_filter_status ) ) {
		if ( spamshield_blacklist_check( $commentdata_comment_author_lc_deslashed, $commentdata_comment_author_email_lc, $commentdata_comment_author_url_lc, $commentdata_comment_content_lc_deslashed, $ip, $commentdata_user_agent_lc, '' ) ) {
			if ( empty( $content_filter_status ) ) { $content_filter_status = '100'; }
			$wpss_error_code .= ' WP-BLACKLIST';
			return spamshield_exit_content_filter( $commentdata, $spamshield_options, $wpss_error_code, $content_filter_status );
			}
		}
	/* WP Blacklist Check - END */

	/* Timer End - Content Filter */
	$wpss_end_time_content_filter 				= spamshield_microtime();
	$wpss_total_time_content_filter 			= spamshield_timer( $commentdata['start_time_content_filter'], $wpss_end_time_content_filter, FALSE, 6, TRUE );
	$commentdata['total_time_content_filter']	= $wpss_total_time_content_filter;

	if ( empty( $wpss_error_code ) ) {
		$wpss_error_code = 'No Error';
		}
	else {
		$wpss_error_code = ltrim( $wpss_error_code );
		}

	/***
	* $spamshield_error_data = array( $wpss_error_code, $blacklist_word_combo, $blacklist_word_combo_total );
	*/

	$commentdata['wpss_error_code']			= ltrim( $wpss_error_code );
	$commentdata['content_filter_status']	= $content_filter_status;

	return $commentdata;

	/* CONTENT FILTERING - END */
	}

function spamshield_ip_proxy_info() {
	/* IP / PROXY INFO - BEGIN */
	$ip = $_SERVER['REMOTE_ADDR'];
	if ( !empty( $_SERVER['HTTP_VIA'] ) ) { $ip_proxy_via=trim($_SERVER['HTTP_VIA']); } else { $ip_proxy_via = ''; }
	$ip_proxy_via_lc = spamshield_casetrans('lower',$ip_proxy_via);
	$masked_ip = '';
	if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$masked_ip = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
		if ( $masked_ip == $ip ) { $masked_ip = ''; }
		}
	$reverse_dns 				= @gethostbyaddr($ip);
	if ( $reverse_dns == '.' ) { $reverse_dns_ip = '[No Data]'; } elseif ( $reverse_dns == $ip ) { $reverse_dns_ip = $ip; } else { $reverse_dns_ip = gethostbyname($reverse_dns); }
	$reverse_dns_ip_regex 		= spamshield_preg_quote($reverse_dns_ip);
	$reverse_dns_lc 			= spamshield_casetrans('lower',$reverse_dns);
	$reverse_dns_lc_regex 		= spamshield_preg_quote($reverse_dns_lc);
	$reverse_dns_lc_rev 		= strrev($reverse_dns_lc);
	$reverse_dns_lc_rev_regex 	= spamshield_preg_quote($reverse_dns_lc_rev);
	/* Forward-confirmed reverse DNS (FCrDNS) */
	if ( $reverse_dns_ip == $ip ) { $reverse_dns_verification = '[Verified]'; } else { $reverse_dns_verification = '[Possibly Forged]'; }
	/* Detect Incapsula, and disable spamshield_ubl_cache - 1.8.9.6 */
	if ( strpos( $reverse_dns_lc, '.ip.incapdns.net' ) !== FALSE ) { update_option( 'spamshield_ubl_cache_disable', TRUE ); }
	/* Detect Use of Proxy */
	$ip_proxy_chrome_compression='FALSE';
	if ( !empty( $ip_proxy_via ) || !empty( $masked_ip ) ) {
		if ( empty( $masked_ip ) ) { $masked_ip='[No Data]'; }
		$ip_proxy='PROXY DETECTED';
		$ip_proxy_short='PROXY';
		$ip_proxy_data=$ip.' | MASKED IP: '.$masked_ip;
		$proxy_status='TRUE';
		/* Google Chrome Compression Check */
		if ( strpos( $ip_proxy_via_lc, 'chrome compression proxy' ) !== FALSE && preg_match( "~^google-proxy-(.*)\.google\.com$~i", $reverse_dns ) ) { $ip_proxy_chrome_compression='TRUE'; }
		}
	else {
		$ip_proxy='No Proxy';
		$ip_proxy_short=$ip_proxy;
		$ip_proxy_data=$ip;
		$proxy_status='FALSE';
		}
	/* IP / PROXY INFO - END */
	$ip_proxy_info = array(
		'ip' 							=> $ip,
		'reverse_dns' 					=> $reverse_dns,
		'reverse_dns_lc' 				=> $reverse_dns_lc,
		'reverse_dns_lc_regex' 			=> $reverse_dns_lc_regex,
		'reverse_dns_lc_rev' 			=> $reverse_dns_lc_rev,
		'reverse_dns_lc_rev_regex'		=> $reverse_dns_lc_rev_regex,
		'reverse_dns_ip' 				=> $reverse_dns_ip,
		'reverse_dns_ip_regex' 			=> $reverse_dns_ip_regex,
		'reverse_dns_verification' 		=> $reverse_dns_verification,
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

/* COMMENT SPAM FILTERS - END */

function spamshield_comment_moderation_addendum( $text, $comment_id ) {
	$spamshield_options = get_option('spamshield_options');
	if ( !current_user_can( 'edit_posts' ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$blacklist_text = __( 'Blacklist the IP Address:', WPSS_PLUGIN_NAME );
		$ip_nodot = str_replace( '.', '', $ip );
		$ip_blacklist_nonce_action	= 'blacklist_IP_'.$ip;
		$ip_blacklist_nonce_name	= 'bl'.$ip_nodot.'tkn';
		$nonce = spamshield_create_nonce( $ip_blacklist_nonce_action, $ip_blacklist_nonce_name );
		$blacklist_url = RSMP_ADMIN_URL.'/options-general.php?page='.WPSS_PLUGIN_NAME.'&wpss_action=blacklist_ip&bl_ip='.$ip.'&'.$ip_blacklist_nonce_name.'='.$nonce;
		$text .= $blacklist_text.' '.$blacklist_url."\r\n";
		}
	if ( empty( $spamshield_options['hide_extra_data'] ) ) {
		$text = spamshield_extra_notification_data( $text, $spamshield_options );
		}
	return $text;
	}

function spamshield_comment_notification_addendum( $text, $comment_id ) {
	$spamshield_options = get_option('spamshield_options');
	if ( !current_user_can( 'edit_posts' ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$blacklist_text = __( 'Blacklist the IP Address:', WPSS_PLUGIN_NAME );
		$ip_nodot = str_replace( '.', '', $ip );
		$ip_blacklist_nonce_action	= 'blacklist_IP_'.$ip;
		$ip_blacklist_nonce_name	= 'bl'.$ip_nodot.'tkn';
		$nonce = spamshield_create_nonce( $ip_blacklist_nonce_action, $ip_blacklist_nonce_name );
		$blacklist_url = RSMP_ADMIN_URL.'/options-general.php?page='.WPSS_PLUGIN_NAME.'&wpss_action=blacklist_ip&bl_ip='.$ip.'&'.$ip_blacklist_nonce_name.'='.$nonce;
		$text .= $blacklist_text.' '.$blacklist_url."\r\n";
		}
	if ( empty( $spamshield_options['hide_extra_data'] ) ) {
		$text = spamshield_extra_notification_data( $text, $spamshield_options );
		}
	return $text;
	}

function spamshield_comment_moderation_check( $emails, $comment_id ) {
	/* Check user roles of email recipients to make sure only admins receive modified emails. */
	if ( empty( $emails ) ) { return $emails; }
	foreach( (array) $emails as $i => $email ) {
		$user = get_user_by( 'email', $email );
		if ( !user_can( $user->ID, 'manage_options' ) ) { return $emails; }
		}
	add_filter( 'comment_moderation_text', 'spamshield_comment_moderation_addendum', 10, 2 );
	return $emails;
	}

function spamshield_comment_notification_check( $emails, $comment_id ) {
	/***
	* Check user roles of email recipients to make sure only admins receive modified emails.
	* Necessitated from other plugins adding their own email notification functions and carelessly using duplicate WordPress filter hooks without proper validation or authentication.
	***/
	if ( empty( $emails ) ) { return $emails; }
	foreach( (array) $emails as $i => $email ) {
		$user = get_user_by( 'email', $email );
		if ( !user_can( $user->ID, 'manage_options' ) ) { return $emails; }
		}
	add_filter( 'comment_notification_text', 'spamshield_comment_notification_addendum', 10, 2 );
	return $emails;
	}

function spamshield_extra_notification_data( $text, $spamshield_options = NULL ) {
	if ( empty( $spamshield_options ) ) { $spamshield_options = get_option('spamshield_options'); }
	spamshield_update_session_data($spamshield_options);
	if ( !empty( $_POST[WPSS_JSONST] ) ) { $post_jsonst = $_POST[WPSS_JSONST]; } else { $post_jsonst = ''; }
	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }
	$post_ref2xjs_lc = spamshield_casetrans('lower',$post_ref2xjs);

	/* IP / PROXY INFO - BEGIN */
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
	$reverse_dns_verification 		= $ip_proxy_info['reverse_dns_verification'];
	$ip_proxy_via 					= $ip_proxy_info['ip_proxy_via'];
	$ip_proxy_via_lc 				= $ip_proxy_info['ip_proxy_via_lc'];
	$masked_ip 						= $ip_proxy_info['masked_ip'];
	$ip_proxy 						= $ip_proxy_info['ip_proxy'];
	$ip_proxy_short 				= $ip_proxy_info['ip_proxy_short'];
	$ip_proxy_data 					= $ip_proxy_info['ip_proxy_data'];
	$proxy_status 					= $ip_proxy_info['proxy_status'];
	$ip_proxy_chrome_compression	= $ip_proxy_info['ip_proxy_chrome_compression'];
	/* IP / PROXY INFO - END */

	/* Sanitized versions for output */
	$wpss_http_accept_language 		= spamshield_get_http_accept( FALSE, FALSE, TRUE );
	$wpss_http_accept				= spamshield_get_http_accept();
	$wpss_http_user_agent 			= spamshield_get_user_agent();
	$wpss_http_referer				= spamshield_get_referrer( FALSE, TRUE, TRUE ); /* Initial referrer, aka "Referring Site" - Changed 1.7.9 */
	if ( empty( $spamshield_options['hide_extra_data'] ) ) {
		$text .= "\r\n";
		$text .= '-----------------------------------------------------------------'."\r\n";
		$text .= __( 'Additional Technical Data Added by WP-SpamShield', WPSS_PLUGIN_NAME ) . "\r\n";
		$text .= '-----------------------------------------------------------------'."\r\n";
		/* DEBUG ONLY - BEGIN */
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
		/* DEBUG ONLY - END */
		else {
			if ( !empty( $post_ref2xjs ) ) {
				$ref2xJS = addslashes( urldecode( $post_ref2xjs ) );
				$ref2xJS = str_replace( '%3A', ':', $ref2xJS );
				$ref2xJS = str_replace( ' ', '+', $ref2xJS );
				$ref2xJS = esc_url_raw( $ref2xJS );
				$text .= "\r\n" . __( 'Page Referrer Check.', WPSS_PLUGIN_NAME ) . ': '.$ref2xJS."\r\n";
				}
			}
		$text .= "\r\n";
		$text .= __( 'Referrer', WPSS_PLUGIN_NAME ) . ': '.$wpss_http_referer."\r\n\r\n";  /* Initial referrer, aka "Referring Site" - Changed 1.7.9 */
		$text .= __( 'User-Agent (Browser/OS)', WPSS_PLUGIN_NAME ) . ': '.$wpss_http_user_agent."\r\n";
		$text .= __( 'IP Address', WPSS_PLUGIN_NAME ) . ': '.$ip."\r\n";
		$text .= __( 'Server', WPSS_PLUGIN_NAME ) . ': '.$reverse_dns."\r\n";
		$text .= __( 'IP Address Lookup', WPSS_PLUGIN_NAME ) . ': http://ipaddressdata.com/'.$ip."\r\n\r\n";
		$text .= '(' . __( 'This data is helpful if you need to submit a spam sample.', WPSS_PLUGIN_NAME ) . ')'."\r\n";
		}
	return $text;
	}

function spamshield_add_ip_to_blacklist($ip_to_blacklist) {
	$blacklist_keys 		= trim(stripslashes(get_option('blacklist_keys')));
	$blacklist_keys_update	= $blacklist_keys."\n".$ip_to_blacklist;
	spamshield_update_bw_list_keys( 'black', $blacklist_keys_update );
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
 * @return bool TRUE if submission contains blacklisted content, FALSE if submission does not
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

	/* do_action( 'spamshield_blacklist_check', $author, $email, $url, $content, $user_ip, $user_agent, $user_server ); */

	$blacklist_keys = trim(stripslashes(get_option('blacklist_keys')));
	if ( empty( $blacklist_keys ) ) {
		return FALSE; /* If blacklist keys are empty */
		}
	$blacklist_keys_arr = explode("\n", $blacklist_keys );

	foreach ( (array) $blacklist_keys_arr as $key ) {
		$key = trim( $key );

		/* Skip empty lines */
		if ( empty( $key ) ) { continue; }

		/* Do some escaping magic so that '~' chars in the spam words don't break things: */
		$key_pq = spamshield_preg_quote( $key );
		$pattern_regex = "~$key_pq~i";

		if ( strpos( $key, '[WPSS-ECBL]' ) === 0 ) { /* Advanced flags work on contact form only, for now */
			$key = str_replace( '[WPSS-ECBL]', '', $key );
			if ( strpos( $key, '[SERVER]' ) === 0 && !empty( $user_server ) ) {
				$key = str_replace( '[SERVER]', '', $key );
				$referrer = spamshield_get_referrer( FALSE, TRUE, TRUE );
				$ref_domain = spamshield_get_domain($referrer);
				if ( strpos( $key, '[REF]' ) === 0 && !empty( $ref_domain ) ) { /* Added 1.8.1 */
					$key = str_replace( '[REF]', '', $key );
					$key_pq = spamshield_preg_quote( $key );
					if ( preg_match( "~$key_pq$~i", $ref_domain ) ) { return TRUE; }
					}
				elseif ( strpos( $key, '.' ) === 0 || strpos( $key, '-' ) === 0 ) {
					$key_pq = spamshield_preg_quote( $key );
					if ( preg_match( "~$key_pq$~i", $user_server ) ) { return TRUE; }
					}
				elseif ( $key == $user_server ) { return TRUE; }
				}
			}
		elseif ( is_email( $key ) ) {
			if ( !empty( $email ) && $key == $email ) { return TRUE; }
			}
		elseif ( spamshield_is_valid_ip( $key, '', TRUE ) ) { /* IP C-block */
			if ( !empty( $user_ip ) && strpos( $user_ip, $key ) === 0 ) { return TRUE; }
			}
		elseif ( spamshield_is_valid_ip( $key ) ) { /* Complete IP Address */
			if ( !empty( $user_ip ) && $key == $user_ip ) { return TRUE; }
			}
		elseif ( 
			   ( !empty( $author ) 	&& preg_match( $pattern_regex, $author ) )
			|| ( !empty( $url ) 	&& preg_match( $pattern_regex, $url ) )
			|| ( !empty( $content )	&& preg_match( $pattern_regex, $content ) )
			) {
			return TRUE;
			}
		}
	return FALSE;
	}

function spamshield_whitelist_check( $email = NULL ) {
	/**
	 * Fires at beginning of contact form and comment content filters.
	 * Bypasses filters if TRUE.
	 * Still subject to cookies test on contact form to prevent potential abuse by bots.
	 * @since 1.7.4
	 * @param string $email      	Comment or Contact Form author's email.
	 */

	$whitelist_keys = trim(stripslashes(get_option('spamshield_whitelist_keys')));
	if ( empty( $whitelist_keys ) || empty( $email ) ) {
		return FALSE; /* If whitelist keys or $email are empty */
		}
	$whitelist_keys_arr = explode("\n", $whitelist_keys );

	foreach ( (array) $whitelist_keys_arr as $key ) {
		$key = trim( $key );

		/* Skip empty lines */
		if ( empty( $key ) ) { continue; }

		if ( is_email( $key ) ) {
			if ( $key == $email ) { return TRUE; }
			}
		}
	return FALSE;
	}

function spamshield_get_bw_list_keys( $list ) {
	/***
	* Get blacklist or whitelist keys
	* $list - 'white' or 'black'
	***/
	$opname = array( 'white' => 'spamshield_whitelist_keys', 'black' => 'blacklist_keys' );
	$keys	= trim(stripslashes(get_option($opname[$list])));
	$arr	= explode("\n",$keys);
	$tmp	= spamshield_sort_unique($arr);
	$keys	= implode("\n",$tmp);
	return $keys;
	}

function spamshield_update_bw_list_keys( $list, $keys ) {
	/***
	* Update blacklist or whitelist keys
	* $list - 'white' or 'black'
	***/
	$opname = array( 'white' => 'spamshield_whitelist_keys', 'black' => 'blacklist_keys' );
	$arr	= explode("\n",$keys);
	$tmp	= spamshield_sort_unique($arr);
	$keys	= implode("\n",$tmp);
	update_option( $opname[$list], $keys );
	}


/* Spam Registration Protection - BEGIN */

function spamshield_register_form_addendum() {
	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);

	/* Check if registration spam shield is disabled - Added in 1.6.9 */
	if ( !empty( $spamshield_options['registration_shield_disable'] ) ) { return; }

	$wpss_key_values 	= spamshield_get_key_values( TRUE );
	$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
	$wpss_js_val 		= $wpss_key_values['wpss_js_val'];
	/* No need to check cache status here since registration form isn't cached */

	$new_fields = array(
		'first_name' 	=> __( 'First Name', WPSS_PLUGIN_NAME ),
		'last_name' 	=> __( 'Last Name', WPSS_PLUGIN_NAME ),
		'disp_name' 	=> __( 'Display Name', WPSS_PLUGIN_NAME ),
		);

	foreach( $new_fields as $k => $v ) {
		echo '	<p>
		<label for="'.$k.'">'.$v.'<br />
		<input type="text" name="'.$k.'" id="'.$k.'" class="input" value="" size="25" /></label>
	</p>
';
		}

	echo "\n\t".'<script type=\'text/javascript\'>'."\n\t".'// <![CDATA['."\n\t".WPSS_REF2XJS.'=escape(document[\'referrer\']);'."\n\t".'hf3N=\''.$wpss_js_key.'\';'."\n\t".'hf3V=\''.$wpss_js_val.'\';'."\n\t".'document.write("<input type=\'hidden\' name=\''.WPSS_REF2XJS.'\' value=\'"+'.WPSS_REF2XJS.'+"\' /><input type=\'hidden\' name=\'"+hf3N+"\' value=\'"+hf3V+"\' />");'."\n\t".'// ]]>'."\n\t".'</script>'."\n\t";
	echo '<noscript><input type="hidden" name="'.WPSS_JSONST.'" value="NS3" /></noscript>'."\n\t";
	$wpss_js_disabled_msg 	= __( 'Currently you have JavaScript disabled. In order to register, please make sure JavaScript and Cookies are enabled, and reload the page.', WPSS_PLUGIN_NAME );
	$wpss_js_enable_msg 	= __( 'Click here for instructions on how to enable JavaScript in your browser.', WPSS_PLUGIN_NAME );
	echo '<noscript><p><strong>'.$wpss_js_disabled_msg.'</strong> <a href="http://enable-javascript.com/" rel="nofollow external" >'.$wpss_js_enable_msg.'</a><br /><br /></p></noscript>'."\n\t";

	/* If need to add anything else to registration area, start here */
	}

if ( !function_exists('wp_new_user_notification') ) {
	/**
	 * WPSS Redefined: Copied from pluggable.php in WordPress core and added filters.
	 * Email login credentials to a newly-registered user.
	 * A new user registration notification is also sent to admin email.
	 * @since 2.0.0
	 * @param int    $user_id        User ID.
	 * @param string $plaintext_pass Optional. The user's plaintext password. Default empty.
	 */
	function wp_new_user_notification($user_id, $plaintext_pass = '') {
		$user = get_userdata( $user_id );

		/***
		* The blogname option is escaped with esc_html on the way into the database in sanitize_option
		* we want to reverse this for the plain text arena of emails.
		***/
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

		@wp_mail($user->user_email, sprintf(__('[%s] Your username and password'), $blogname), $user_message);
		}

	/* WPSS Added */

	function spamshield_modify_signup_notification_admin( $text, $user_id, $user ) {
		/* Check if registration spam shield is disabled - Added in 1.6.9 */
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

		$text = spamshield_extra_notification_data( $text, $spamshield_options );

		return $text;
		}

	function spamshield_modify_signup_notification_user( $text, $user_id, $user ) {

		/* Check if registration spam shield is disabled - Added in 1.6.9 */
		$spamshield_options = get_option('spamshield_options');
		if ( !empty( $spamshield_options['registration_shield_disable'] ) ) { return $text; }

		/* Add three new fields */
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
	/* Error checking for new user registration */
	$spamshield_options 	= get_option('spamshield_options');

	/* Check if registration spam shield is disabled - Added in 1.6.9 */
	if ( !empty( $spamshield_options['registration_shield_disable'] ) ) { return $errors; }

	$reg_filter_status		= $wpss_error_code= '';
	$reg_jsck_error			= $reg_badrobot_error = FALSE;
	$ns_val					= 'NS3';
	$pref					= 'R-';
	$error_txt = spamshield_error_txt();

	$new_fields = array(
		'first_name' 	=> __( 'First Name', WPSS_PLUGIN_NAME ),
		'last_name' 	=> __( 'Last Name', WPSS_PLUGIN_NAME ),
		'disp_name' 	=> __( 'Display Name', WPSS_PLUGIN_NAME ),
		);
	$user_data = array();
	foreach( $new_fields as $k => $v ) {
		if ( isset( $_POST[$k] ) ) { $user_data[$k] = trim( wp_unslash( $_POST[$k] ) ); } else { $user_data[$k] = ''; }
		}

	/* Check New Fields for Blanks */
	foreach( $new_fields as $k => $v ) {
		$k_uc = spamshield_casetrans('upper',$k);
		if ( empty( $_POST[$k]) ) {
			$errors->add( 'empty_'.$k, '<strong>'.$error_txt.':</strong> ' . sprintf( __( 'Please enter your %s', WPSS_PLUGIN_NAME ) . '.', $v ) );
			$wpss_error_code .= ' R-BLANK-'.$k_uc;
			}
		}

	/* BAD ROBOT TEST - BEGIN */

	$bad_robot_filter_data 	 = spamshield_bad_robot_blacklist_chk( 'register', $reg_filter_status, '', '', $user_data['disp_name'], $user_email );
	$reg_filter_status		 = $bad_robot_filter_data['status'];
	$bad_robot_blacklisted 	 = $bad_robot_filter_data['blacklisted'];

	if ( !empty( $bad_robot_blacklisted ) ) {
		$wpss_error_code 	.= $bad_robot_filter_data['error_code'];
		$reg_badrobot_error = TRUE;
		}

	/* BAD ROBOT TEST - END */

	/* BAD ROBOTS */
	if ( $reg_badrobot_error != FALSE ) {
		$errors->add( 'badrobot_error', '<strong>'.$error_txt.':</strong> ' . __( 'User registration is currently not allowed.' ) );
		}

	/* JS/COOKIES CHECK */
	$wpss_key_values 	= spamshield_get_key_values( TRUE );
	$wpss_ck_key  		= $wpss_key_values['wpss_ck_key'];
	$wpss_ck_val 		= $wpss_key_values['wpss_ck_val'];
	$wpss_js_key 		= $wpss_key_values['wpss_js_key'];
	$wpss_js_val 		= $wpss_key_values['wpss_js_val'];
	/* No need to check cache status here since registration form isn't cached */

	if ( !empty( $_COOKIE[$wpss_ck_key] ) ) { $wpss_jsck_cookie_val	= $_COOKIE[$wpss_ck_key]; }	else { $wpss_jsck_cookie_val = ''; }
	if ( !empty( $_POST[$wpss_js_key] ) ) { $wpss_jsck_field_val	= $_POST[$wpss_js_key]; } 	else { $wpss_jsck_field_val = ''; }
	$wpss_ck_key_bypass = $wpss_js_key_bypass = FALSE;
	//if ( TRUE == WPSS_EDGE && !empty( $spamshield_options['js_head_disable'] ) ) { /* EDGE - 1.8.4 */
	if ( !empty( $spamshield_options['js_head_disable'] ) ) { /* 1.8.9 */
		$wpss_ck_key_bypass = TRUE;
		}
	if ( FALSE == $wpss_ck_key_bypass ) { /* 1.8.9 */
		if ( $wpss_jsck_cookie_val != $wpss_ck_val ) {
			$wpss_error_code .= ' '.$pref.'COOKIE-3';
			$reg_jsck_error = TRUE;
			}
		}
	if ( $wpss_jsck_field_val != $wpss_js_val ) {
		$wpss_error_code .= ' '.$pref.'FVFJS-3';
		$reg_jsck_error = TRUE;
		}
	if ( !empty( $_POST[WPSS_JSONST] ) ) { $post_jsonst = $_POST[WPSS_JSONST]; } else { $post_jsonst = ''; }
	if ( $post_jsonst == $ns_val ) {
		$wpss_error_code .= ' '.$pref.'JSONST-1000-3';
		$reg_jsck_error = TRUE;
		}

	if ( $reg_jsck_error != FALSE && $reg_badrobot_error != TRUE ) {
		$errors->add( 'jsck_error', '<strong>'.$error_txt.':</strong> ' . __( 'JavaScript and Cookies are required in order to register. Please be sure JavaScript and Cookies are enabled in your browser, and reload the page.', WPSS_PLUGIN_NAME ) );
		}

	/* EMAIL BLACKLIST */
	if ( spamshield_email_blacklist_chk($user_email) ) {
		$wpss_error_code .= ' '.$pref.'9200E-BL';
		if ( $reg_badrobot_error != TRUE && $reg_jsck_error != TRUE ) {
			$errors->add( 'blacklist_email_error', '<strong>'.$error_txt.':</strong> ' . __( 'Sorry, that email address is not allowed!' ) . ' ' . __( 'Please enter a valid email address.' ) );
			}
		}

	/* AUTHOR KEYPHRASE BLACKLIST */
	foreach( $user_data as $k => $v ) {
		$k_uc = spamshield_casetrans('upper',$k);
		if ( ( $k == 'user_login' || $k == 'first_name' || $k == 'last_name' || $k == 'disp_name' ) && spamshield_anchortxt_blacklist_chk($v) ) {
			$wpss_error_code .= ' '.$pref.'10500A-BL-'.$k_uc;
			if ( $reg_badrobot_error != TRUE && $reg_jsck_error != TRUE ) {
				$nfk = $new_fields[$k];
				$errors->add( 'blacklist_'.$k.'_error', '<strong>'.$error_txt.':</strong> ' . sprintf( __( '"%1$s" appears to be spam. Please enter a different value in the <strong> %2$s </strong> field.', WPSS_PLUGIN_NAME ), sanitize_text_field($v), $nfk ) );
				}
			}
		}

	/* BLACKLISTED USER */
	if ( empty( $wpss_error_code ) && ( spamshield_ubl_cache() ) ) {
		$wpss_error_code .= ' '.$pref.'0-BL';
		$errors->add( 'blacklisted_user_error', '<strong>'.$error_txt.':</strong> ' . __( 'User registration is currently not allowed.' ) );
		}

	/* Done with Tests */

	/* Now Log the Errors, if any */

	if ( !empty( $_POST[WPSS_REF2XJS] ) ) { $post_ref2xjs = $_POST[WPSS_REF2XJS]; } else { $post_ref2xjs = ''; }
	$post_ref2xjs = spamshield_casetrans('lower',$post_ref2xjs);
	if ( !empty( $post_ref2xjs ) ) {
		$ref2xJS = spamshield_casetrans( 'lower', addslashes( urldecode( $post_ref2xjs ) ) );
		$ref2xJS = str_replace( '%3a', ':', $ref2xJS );
		$ref2xJS = str_replace( ' ', '+', $ref2xJS );
		$wpss_javascript_page_referrer = esc_url_raw( $ref2xJS );
		}
	else { $wpss_javascript_page_referrer = '[None]'; }

	if ( $post_jsonst == 'NS3' ) { $wpss_jsonst = $post_jsonst; } else { $wpss_jsonst = '[None]'; }

	$user_id = 'None'; /* Possibly change to '' */

	$register_author_data = array(
		'display_name' 				=> $user_data['disp_name'],
		'user_firstname' 			=> $user_data['first_name'],
		'user_lastname' 			=> $user_data['last_name'],
		'user_email' 				=> $user_email,
		'user_login' 				=> $user_login,
		'ID' 						=> $user_id,
		'comment_author'			=> $user_data['disp_name'],
		'comment_author_email'		=> $user_email,
		'comment_author_url'		=> '',
		'javascript_page_referrer'	=> $wpss_javascript_page_referrer,
		'jsonst'					=> $wpss_jsonst,
		);
	if ( empty( $register_author_data['comment_author'] ) && !empty( $user_login ) ) { $register_author_data['comment_author'] = $user_login; }

	unset( $wpss_javascript_page_referrer, $wpss_jsonst );

	$wpss_error_code = trim( $wpss_error_code );

	if ( !empty( $wpss_error_code ) ) {
		spamshield_update_accept_status( $register_author_data, 'r', 'Line: '.__LINE__, $wpss_error_code );
		spamshield_increment_reg_count();
		if ( !empty( $spamshield_options['comment_logging'] ) ) {
			spamshield_log_data( $register_author_data, $wpss_error_code, 'register' );
			}
		}

	/* Now return the error values */
	return $errors;
	}

function spamshield_user_register( $user_id ) {
	if ( spamshield_is_login_page() ) {
		$spamshield_options 	= get_option('spamshield_options');

		/* Check if registration spam shield is disabled - Added in 1.6.9 */
		if ( !empty( $spamshield_options['registration_shield_disable'] ) ) { return; }

		$new_fields = array(
			'first_name' 	=> __( 'First Name', WPSS_PLUGIN_NAME ),
			'last_name' 	=> __( 'Last Name', WPSS_PLUGIN_NAME ),
			'disp_name' 	=> __( 'Display Name', WPSS_PLUGIN_NAME ),
			);
		$user_data = array();
		foreach( $new_fields as $k => $v ) {
			if ( isset( $_POST[$k] ) ) { $user_data[$k] = trim( wp_unslash( $_POST[$k] ) ); } else { $user_data[$k] = ''; }
			}
		if ( !empty($user_data) ) {
			$user_data['ID']			= $user_id;
			$user_data['display_name']	= $user_data['disp_name'];
			unset( $user_data['disp_name'] );
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

		spamshield_update_accept_status( $register_author_data, 'a', 'Line: '.__LINE__ );
		if ( !empty( $spamshield_options['comment_logging'] ) && !empty( $spamshield_options['comment_logging_all'] ) ) {
			spamshield_log_data( $register_author_data, $wpss_error_code, 'register' );
			}
		}
	}

/* Spam Registration Protection - END */


/* Admin Functions - BEGIN */

function spamshield_dashboard_stats() {
	$spamshield_count = spamshield_count();
	$spamshield_options = get_option('spamshield_options');
	spamshield_update_session_data($spamshield_options);
	$current_date = date('Y-m-d');
	if ( empty( $spamshield_options['install_date'] ) ) {
		$install_date = $current_date;
		$spamshield_options['install_date'] = $install_date;
		update_option( 'spamshield_options', $spamshield_options );
		}
	else { $install_date = $spamshield_options['install_date']; }
	$num_days_installed = spamshield_date_diff($install_date, $current_date);
	if ( $num_days_installed < 1 ) { $num_days_installed = 1; }
	$spam_count_so_far = $spamshield_count;
	if ( $spam_count_so_far < 1 ) { $spam_count_so_far = 1; }
	$avg_blocked_daily		= round( $spam_count_so_far / $num_days_installed );
	$avg_blocked_daily_disp	= spamshield_number_format( $avg_blocked_daily );
	if ( current_user_can( 'manage_options' ) ) {
		$spam_stat_incl_link = ' (<a href="options-general.php?page='.WPSS_PLUGIN_NAME.'"">' . __( 'Settings' ) . '</a>)</p>'."\n";
		$spam_stat_url = 'options-general.php?page='.WPSS_PLUGIN_NAME;
		$spam_stat_href_attr = '';
		}
	else {
		$spam_stat_incl_link = '';
		$spam_stat_url = WPSS_HOME_URL;
		$spam_stat_href_attr = 'target="_blank" rel="external"';
		}

	if ( empty( $spamshield_count ) ) {
		echo '<p>' . __( 'No comment spam attempts have been detected yet.', WPSS_PLUGIN_NAME ) . $spam_stat_incl_link;
		}
	else {
		echo '<p>'."<img src='".WPSS_PLUGIN_IMG_URL."/dashboard-spam-protection-24.png' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' />".' <a href="'.$spam_stat_url.'" '.$spam_stat_href_attr.'>WP-SpamShield</a> '.sprintf( __( 'has blocked <strong> %s </strong> spam.', WPSS_PLUGIN_NAME ), spamshield_number_format( $spamshield_count ) ).'</p>'."\n";
		if ( $avg_blocked_daily >= 2 ) {
			echo "<p><img src='".WPSS_PLUGIN_IMG_URL."/spacer.gif' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> " . __( 'Average spam blocked daily', WPSS_PLUGIN_NAME ) . ": <strong>".$avg_blocked_daily_disp."</strong></p>"."\n";
			}
		}
	}

function spamshield_filter_plugin_actions( $links, $file ) {
	/* Add "Settings" Link on Dashboard Plugins page, in plugin listings */
	if ( $file == WPSS_PLUGIN_BASENAME ){
		$settings_link = '<a href="options-general.php?page='.WPSS_PLUGIN_NAME.'">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link ); /* Before other links */
		}
	return $links;
	}

function spamshield_filter_plugin_meta( $links, $file ) {
	/* Add Links on Dashboard Plugins page, in plugin meta */
	if ( $file == WPSS_PLUGIN_BASENAME ){
		/* After other links */
		$links[] = '<a href="'.WPSS_HOME_URL.'" target="_blank" rel="external" >' . spamshield_doc_txt() . '</a>';
		$links[] = '<a href="'.WPSS_HOME_URL.'support/" target="_blank" rel="external" >' . __( 'Support', WPSS_PLUGIN_NAME ) . '</a>';
		if ( spamshield_count() >= 2000 ) { $links[] = '<a href="'.WPSS_WP_RATING_URL.'" title="' . __( 'Let others know by giving it a good rating on WordPress.org!', WPSS_PLUGIN_NAME ) . '" target="_blank" rel="external" >' . __( 'Rate the Plugin', WPSS_PLUGIN_NAME ) . '</a>'; }
		$links[] = '<a href="http://bit.ly/wp-spamshield-donate" target="_blank" rel="external" >' . __( 'Donate', WPSS_PLUGIN_NAME ) . '</a>';
		}
	return $links;
	}

function spamshield_admin_notices() {
	$admin_notices = get_option('spamshield_admin_notices');
	if ( !empty( $admin_notices ) ) {
		$style 	= $admin_notices['style']; /* 'error'  or 'updated' */
		$notice	= $admin_notices['notice'];
		echo '<div class="'.$style.'"><p>'.$notice.'</p></div>';
		}
	delete_option( 'spamshield_admin_notices' );
	}

function spamshield_upgrade_check( $installed_ver = NULL ) {
	if ( empty( $installed_ver ) ) { $installed_ver = get_option( 'wp_spamshield_version' ); }
	if ( $installed_ver != WPSS_VERSION ) {
		$upd_options = array( 'wp_spamshield_version' => WPSS_VERSION, 'spamshield_ubl_cache' => array(), 'spamshield_wpssmid_cache' => array() );
		foreach( $upd_options as $option => $val ) { update_option( $option, $val ); }
		$del_options = array( 'spamshield_install_status', 'spamshield_warning_status', 'spamshield_regalert_status' ); /* Added 1.8.4 */
		foreach( $del_options as $i => $option ) { delete_option( $option ); }
		spamshield_purge_nonces();
		}
	}

function spamshield_admin_jp_fix() {
	/***
	* Fix Compatibility with JetPack if active
	* The JP Comments module modifies WordPress' comment system core functionality, incapacitating MANY fine plugins...sorry guys, but this has to be deactivated
	***/
	if ( is_multisite() ) { return;}
	$wpss_jp_active	= spamshield_is_plugin_active( 'jetpack/jetpack.php' );
	if ( !empty( $wpss_jp_active ) ) {
		$jp_active_mods = get_option('jetpack_active_modules');
		if ( !empty( $jp_active_mods ) && is_array( $jp_active_mods ) ) { $jp_com_key = array_search( 'comments', $jp_active_mods, TRUE ); } else { return; }
		if ( isset( $jp_com_key ) && is_int( $jp_com_key ) ) {
			$jp_num_active_mods = count( $jp_active_mods );
			if ( empty( $jp_active_mods ) ) { $jp_num_active_mods = 0; }
			if ( $jp_num_active_mods < 2 ) { $jp_active_mods = array(); } else { unset( $jp_active_mods[$jp_com_key] ); }
			update_option( 'jetpack_active_modules', $jp_active_mods );
			spamshield_append_log_data( "\n".'JetPack Comments module deactivated.', FALSE );
			}
		}
	}

function spamshield_admin_ao_fix() {
	/***
	* Fix Compatibility with Autoptimize if active
	* The Autoptimize plugin forces the WP-SpamShield head JavaScript into the footer, which will cause problems. This fix automatically adds WP-SpamShield to the list of ignored scripts.
	***/
	if ( is_multisite() ) { return;}
	$wpss_ao_active	= spamshield_is_plugin_active( 'autoptimize/autoptimize.php' );
	if ( !empty( $wpss_ao_active ) ) {
		$ao_js = get_option('autoptimize_js');
		if ( empty( $ao_js ) ) { return; }
		$ao_js_exc = trim( get_option('autoptimize_js_exclude'), ", \t\n\r\0\x0B" );
		if ( FALSE !== strpos( $ao_js_exc, 'wp-spamshield' ) ) { return; }
		$s = empty( $ao_js_exc ) ? '' : ',';
		$ao_js_exc .= $s.'wp-spamshield';
		update_option( 'autoptimize_js_exclude', $ao_js_exc );
		spamshield_append_log_data( "\n".'Autoptimize JavaScript exclusion setting appended.', FALSE );
		}
	}

function spamshield_settings_ver_ftr() {
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
	}

/* Admin Functions - END */


/* wpSpamShield CLASS - BEGIN */

if (!class_exists('wpSpamShield')) {
    class wpSpamShield {

		function wpSpamShield() {
			if ( strpos( RSMP_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) !== 0 && RSMP_SERVER_ADDR != '127.0.0.1' && !WPSS_DEBUG && !WP_DEBUG ) {
				error_reporting(0); /* Prevents error display on production sites, but testing on 127.0.0.1 will display errors, or if debug mode turned on */
				}
			/* Activation */
			register_activation_hook( __FILE__, array(&$this,'spamshield_activation') );
			/* Actions */
			add_action( 'plugins_loaded', 'spamshield_load_languages' );
			add_action( 'init', 'spamshield_first_action', 1 );
			add_action( 'widgets_init', 'spamshield_load_widgets' ); /* Added 1.6.5 */
			add_action( 'admin_menu', array(&$this,'spamshield_add_plugin_settings_page') );
			add_action( 'admin_init', array(&$this,'spamshield_check_version') );
			add_action( 'wp_enqueue_scripts', array(&$this, 'spamshield_enqueue_scripts') );
			add_action( 'wp_head', array(&$this, 'spamshield_insert_head_js') );
			add_action( 'activity_box_end', 'spamshield_dashboard_stats' );
			add_action( 'comment_form', 'spamshield_comment_form_addendum', 10 );
			add_action( 'preprocess_comment', 'spamshield_check_comment_spam', -10 );
			add_action( 'admin_print_footer_scripts', array(&$this, 'spamshield_editor_add_quicktags' ) ); /* Added 1.8.3 */
			add_action( 'login_enqueue_scripts', array(&$this, 'spamshield_enqueue_scripts') );
			add_action( 'login_head', array(&$this, 'spamshield_insert_head_js') );
			add_action( 'register_form', 'spamshield_register_form_addendum', 1 );
			add_action( 'user_register', 'spamshield_user_register', 1 );
			add_action( 'wp_logout', 'spamshield_end_session' );
			add_action( 'wp_login', 'spamshield_end_session' );
			/* Filters */
			add_filter( 'plugin_action_links', 'spamshield_filter_plugin_actions', 10, 2 );
			add_filter( 'plugin_row_meta', 'spamshield_filter_plugin_meta', 10, 2 ); /* Added 1.7.1 */
			add_filter( 'the_content', 'spamshield_contact_form', 10 );
			add_filter( 'comment_notification_recipients', 'spamshield_comment_notification_check', 9999, 2 ); /* Added 1.8.3 */
			add_filter( 'comment_moderation_recipients', 'spamshield_comment_moderation_check', 9999, 2 ); /* Added 1.8.3 */
			add_filter( 'registration_errors', 'spamshield_check_new_user', 1, 3 );
			if ( !empty( $_POST[WPSS_REF2XJS] ) ) { /* Don't add if registrations not processed by WPSS, or missing token - 1.8.3 */
				if ( function_exists( 'spamshield_modify_signup_notification_admin' ) ) {
					add_filter( 'spamshield_signup_notification_text_admin', 'spamshield_modify_signup_notification_admin', 1, 3 );
					}
				if ( function_exists( 'spamshield_modify_signup_notification_user' ) ) {
					add_filter( 'spamshield_signup_notification_text_user', 'spamshield_modify_signup_notification_user', 1, 3 );
					}
				}
			/* Shortcodes */
			add_shortcode( 'spamshieldcountersm', 'spamshield_counter_sm_short' );
			add_shortcode( 'spamshieldcounter', 'spamshield_counter_short' );
			add_shortcode( 'spamshieldcontact', 'spamshield_contact_shortcode' );
			/* Deactivation */
			register_deactivation_hook( __FILE__, array(&$this,'spamshield_deactivation') );
        	}

		/* Class Admin Functions - BEGIN */

		function spamshield_activation() {
			$installed_ver = get_option('wp_spamshield_version');
			$spamshield_options = get_option('spamshield_options');
			spamshield_update_session_data($spamshield_options);

			spamshield_upgrade_check( $installed_ver );

			/* Only run installation if not installed already */
			if ( empty( $installed_ver ) || empty( $spamshield_options ) ) {

				/***
				* Upgrade from old version
				* Import existing WP-SpamFree Options, only on first activation, if old plugin is active
				***/
				$old_version = 'wp-spamfree/wp-spamfree.php';
				$old_version_active = spamshield_is_plugin_active( $old_version );
				$wpsf_installed_ver = get_option('wp_spamfree_version');
				if ( !empty( $wpsf_installed_ver ) && empty( $installed_ver ) && !empty( $old_version_active ) ) {
					$spamfree_options = get_option('spamfree_options');
					}

				/* Set Initial Options */
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
					/* DATE */
					$install_date = date('Y-m-d');
					/* DEFAULTS */
					$spamshield_default = unserialize(WPSS_DEFAULT_VALUES);
					$spamshield_options_update = array (
						'block_all_trackbacks' 					=> $spamshield_default['block_all_trackbacks'],
						'block_all_pingbacks' 					=> $spamshield_default['block_all_pingbacks'],
						'comment_logging'						=> $spamshield_default['comment_logging'],
						'comment_logging_start_date'			=> $spamshield_default['comment_logging_start_date'],
						'comment_logging_all'					=> $spamshield_default['comment_logging_all'],
						'enhanced_comment_blacklist'			=> $spamshield_default['enhanced_comment_blacklist'],
						'enable_whitelist'						=> $spamshield_default['enable_whitelist'],
						'allow_proxy_users'						=> $spamshield_default['allow_proxy_users'],
						'hide_extra_data'						=> $spamshield_default['hide_extra_data'],
						'js_head_disable'						=> $spamshield_default['js_head_disable'],
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
				if ( empty( $spamshield_count ) ) { update_option( 'spamshield_count', 0 ); }
				update_option( 'spamshield_options', $spamshield_options_update );
				/***
				* NEXT LINE - DEBUGGING ONLY
				* spamshield_procdat('reset');
				* Reset Log and Initialize .htaccess
				***/
				$last_admin_ip = get_option( 'spamshield_last_admin' );
				if ( !empty( $last_admin_ip ) ) { spamshield_log_reset( $last_admin_ip ); } else { spamshield_log_reset( NULL, FALSE, FALSE, TRUE ); /* Create log file if it doesn't exist */ }
				/* Require Author Names and Emails on Comments - Added 1.1.7 */
				update_option('require_name_email', '1');
				/* Set 'default_role' to 'subscriber' for security - Added 1.3.7 */
				update_option('default_role', 'subscriber');
				/***
				* Turn on Comment Moderation
				* update_option('comment_moderation', 1);
				* update_option('moderation_notify', 1);
				* Compatibility Checks
				***/
				spamshield_admin_jp_fix();
				spamshield_admin_ao_fix(); /* Added 1.8.9.5 */

				/* Ensure Correct Permissions of JS file - BEGIN */
				$installation_file_test_3 = WPSS_PLUGIN_JS_PATH.'/jscripts.php';
				clearstatcache();
				$installation_file_test_3_perm = substr(sprintf('%o', fileperms($installation_file_test_3)), -4);
				if ( $installation_file_test_3_perm < '0755' || !is_readable($installation_file_test_3) || !is_executable($installation_file_test_3) ) {
					@chmod( $installation_file_test_3, 0755 ); /* 755, really? Yes, this really has to be 755! */
					}
				/* Ensure Correct Permissions of JS file - END */
				}
			}

		function spamshield_deactivation() {
			spamshield_log_reset();
			$upd_options = array( 'spamshield_ubl_cache' => array(), 'spamshield_wpssmid_cache' => array() );
			foreach( $upd_options as $option => $val ) { update_option( $option, $val ); }
			$del_options = array( 'spamshield_admin_notices', 'spamshield_install_status', 'spamshield_warning_status', 'spamshield_regalert_status' ); /* Added 1.8.4 */
			foreach( $del_options as $i => $option ) { delete_option( $option ); }
			spamshield_purge_nonces();
			}

		function spamshield_add_plugin_settings_page() {
			add_options_page( 'WP-SpamShield ' . __( 'Settings' ), 'WP-SpamShield', 'manage_options', WPSS_PLUGIN_NAME, array(&$this,'spamshield_plugin_settings_page') );
			}

		function spamshield_custom_admin_style() {
			$css_handle = 'wpss-admin';
			wp_register_style( $css_handle, WPSS_PLUGIN_CSS_URL.'/'.$css_handle.'.css', NULL, WPSS_VERSION );
			wp_enqueue_style( $css_handle );
			}

		function spamshield_plugin_settings_page() {
			if ( !current_user_can( 'manage_options' ) ) {
				$restricted_area_warning = __( 'You do not have sufficient permissions to access this page.' );
				$args = array( 'response' => '403' );
				wp_die( $restricted_area_warning, '', $args );
				}

			echo "\n\t\t\t".'<div class="wrap">'."\n\t\t\t".'<h2>WP-SpamShield ' . __( 'Settings' ) . '</h2>'."\n";
			
			$ip = $_SERVER['REMOTE_ADDR'];
			$spamshield_count = spamshield_count();
			$spamshield_options = get_option('spamshield_options');
			spamshield_update_session_data($spamshield_options);
			$wpss_admin_email = get_option('admin_email');
			$current_date = date('Y-m-d');
			if ( empty( $spamshield_options['install_date'] ) ) { $install_date = $current_date; } else { $install_date = $spamshield_options['install_date']; }
			$num_days_installed = spamshield_date_diff($install_date, $current_date);
			if ( $num_days_installed < 1 ) { $num_days_installed = 1; }
			$spam_count_so_far = $spamshield_count;
			if ( $spam_count_so_far < 1 ) { $spam_count_so_far = 1; }
			$spam_per_hour				= 240; /* Avg spam a person can deal with per hour */
			$avg_blocked_daily			= round( $spam_count_so_far / $num_days_installed );
			$avg_blocked_daily_disp		= spamshield_number_format( $avg_blocked_daily );
			$est_hours_returned			= round( $spam_count_so_far / $spam_per_hour );
			$est_hours_returned_disp	= spamshield_number_format( $est_hours_returned );

			/* Check Installation Status */
			$wpss_inst_status_option	= get_option( 'spamshield_install_status' );
			if ( !empty( $wpss_inst_status_option ) && $wpss_inst_status_option == 'correct' ) {
				$wpss_inst_status = TRUE;
				}
			elseif ( !empty( $wpss_inst_status_option ) && $wpss_inst_status_option == 'incorrect' ) {
				$wpss_inst_status = FALSE;
				}
			else {
				$installation_plugins_get_test_1	= WPSS_PLUGIN_NAME; /* 'wp-spamshield' - Checking for 'options-general.php?page='.WPSS_PLUGIN_NAME */
				$installation_file_test_0 			= WPSS_PLUGIN_FILE_PATH; /* '/public_html/wp-content/plugins/wp-spamshield/wp-spamshield.php' */
				$installation_file_test_3 			= WPSS_PLUGIN_JS_PATH.'/jscripts.php';
				clearstatcache();
				$installation_file_test_3_perm = substr(sprintf('%o', fileperms($installation_file_test_3)), -4);
				if ( $installation_file_test_3_perm < '0755' || !is_readable($installation_file_test_3) || !is_executable($installation_file_test_3) ) {
					@chmod( $installation_file_test_3, 0755 ); /* 755, really? Yes, this really has to be 755! */
					}
				clearstatcache();
				if ( !empty( $_GET['page'] ) ) { $get_page = $_GET['page']; } else { $get_page = ''; }
				if ( $installation_plugins_get_test_1 == $get_page && file_exists($installation_file_test_0) && file_exists($installation_file_test_3) ) {
					$wpss_inst_status = TRUE;
					update_option( 'spamshield_install_status', 'correct' );
					}
				else { 
					$wpss_inst_status = FALSE;
					update_option( 'spamshield_install_status', 'incorrect' );
					}
				}
			if ( !empty( $wpss_inst_status ) ) {
				$wpss_inst_status_image		= 'settings-status-installed-correctly-24';
				$wpss_inst_status_color		= '#77A51F'; /* '#D6EF7A', 'green' */
				$wpss_inst_status_bg_color	= '#EBF7D5'; /* '#77A51F', '#CCFFCC' */
				$wpss_inst_status_msg_main	= __( 'Installed Correctly', WPSS_PLUGIN_NAME );
				$wpss_inst_status_msg_text	= spamshield_casetrans('lower',$wpss_inst_status_msg_main);
				}
			else {
				$wpss_inst_status_image		= 'settings-status-not-installed-correctly-24';
				$wpss_inst_status_color		= '#A63104'; /* '#FC956D', 'red' */
				$wpss_inst_status_bg_color	= '#FEDBCD'; /* '#A63104', '#FFCCCC' */
				$wpss_inst_status_msg_main	= __( 'Not Installed Correctly', WPSS_PLUGIN_NAME );
				$wpss_inst_status_msg_text	= spamshield_casetrans('lower',$wpss_inst_status_msg_main);
				}



			/* Checks Complete */

			/* Save Options */
			if ( !empty( $_REQUEST['submit_wpss_general_options'] ) && current_user_can( 'manage_options' ) && check_admin_referer('wpss_update_general_options_token','ugo_tkn') ) {
				echo '<div class="updated fade"><p>' . __( 'Plugin Spam settings saved.', WPSS_PLUGIN_NAME ) . '</p></div>';
				}
			if ( !empty( $_REQUEST['submit_wpss_contact_options'] ) && current_user_can( 'manage_options' ) && check_admin_referer('wpss_update_contact_options_token','uco_tkn') ) {
				echo '<div class="updated fade"><p>' . __( 'Plugin Contact Form settings saved.', WPSS_PLUGIN_NAME ) . '</p></div>';
				}

			/* Add IP to Blacklist */
			$ip_to_blacklist = $ip_blacklist_nonce_value = '';
			if ( !empty( $_REQUEST['bl_ip'] ) ) {
				$ip_to_blacklist 			= trim(stripslashes($_REQUEST['bl_ip']));
				}
			$ip_nodot = str_replace( '.', '', $ip_to_blacklist );
			$ip_blacklist_nonce_action		= 'blacklist_IP_'.$ip_to_blacklist;
			$ip_blacklist_nonce_name		= 'bl'.$ip_nodot.'tkn';
			if ( !empty ( $_REQUEST[$ip_blacklist_nonce_name] ) ) {
				$ip_blacklist_nonce_value	= $_REQUEST[$ip_blacklist_nonce_name];
				}
			if ( !empty( $_REQUEST['wpss_action'] ) ) { $wpss_action = $_REQUEST['wpss_action']; } else { $wpss_action = ''; }
			if ( $wpss_action == 'blacklist_ip' && !empty( $_REQUEST['bl_ip'] ) && !empty( $_REQUEST[$ip_blacklist_nonce_name] ) && current_user_can( 'manage_options' ) && empty( $_REQUEST['submit_wpss_general_options'] ) && empty( $_REQUEST['submit_wpss_contact_options'] ) ) {
				if ( spamshield_verify_nonce( $ip_blacklist_nonce_value, $ip_blacklist_nonce_action, $ip_blacklist_nonce_name ) ) {
					if ( spamshield_is_valid_ip( $ip_to_blacklist ) ) {
						$last_admin_ip = get_option( 'spamshield_last_admin' );
						if ( $ip_to_blacklist == $ip ) {
							echo '<div class="error fade"><p>' . __( 'Are you sure you want to blacklist yourself? IP Address not added to Comment Blacklist.', WPSS_PLUGIN_NAME ) . '</p></div>'; /* NEEDS TRANSLATION */
							}
						elseif ( !empty( $last_admin_ip ) && $ip_to_blacklist == $last_admin_ip ) { /* TO DO: Switch to in_array once changes are made to last_admin_ip */
							echo '<div class="error fade"><p>' . __( 'Are you sure you want to blacklist an Administrator? IP Address not added to Comment Blacklist.', WPSS_PLUGIN_NAME ) . '</p></div>'; /* NEEDS TRANSLATION */
							}
						else {
							$ip_to_blacklist_valid='1';
							spamshield_add_ip_to_blacklist($ip_to_blacklist);
							echo '<div class="updated fade"><p>' . __( 'IP Address added to Comment Blacklist.', WPSS_PLUGIN_NAME ) . '</p></div>';
							}
						}
					else {
						echo '<div class="error fade"><p>' . __( 'Invalid IP Address - not added to Comment Blacklist.', WPSS_PLUGIN_NAME ) . '</p></div>';
						}
					}
				else {
					echo '<div class="error fade"><p>' . __( 'Security token invalid or expired. IP Address not added to Comment Blacklist.', WPSS_PLUGIN_NAME ) . '</p></div>'; /* NEEDS TRANSLATION */
					}
				}

			/* Set Margins */
			$wpss_vert_margins = '15'; $wpss_horz_margins = '15'; $wpss_icon_size = '48';$wpss_sm_txt_spacer_size = '24';

			/* Display Install Status */
			?>
			<div style='width:797px;border-style:solid;border-width:1px;border-color:<?php echo $wpss_inst_status_color; ?>;background-color:<?php echo $wpss_inst_status_bg_color; ?>;padding:0px 15px 0px 15px;margin-top:30px;float:left;clear:left;'>
			<p><strong><?php echo "<img src='".WPSS_PLUGIN_IMG_URL."/".$wpss_inst_status_image.".png' alt='' width='24' height='24' style='border-style:none;vertical-align:middle;padding-right:7px;' /> " . __( 'Installation Status', WPSS_PLUGIN_NAME ) . ": <span style='color:".$wpss_inst_status_color.";'>".$wpss_inst_status_msg_main."</span>"; ?></strong></p>
			</div>

			<?php
			/**
			* TO DO:
			* Detect PHP Version and Memory Issues and Display Warning, with info on how to fix
			**/

			/* Start Layout */
			if ( !empty( $spamshield_count ) ) {
				echo "\n\t\t\t<div style='width:797px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:".$wpss_vert_margins."px;float:left;clear:left;'>\n\t\t\t<p style='font-size:20px;line-height:180%;'><img src='".WPSS_PLUGIN_IMG_URL."/settings-spam-protection-".$wpss_icon_size.".png' alt='' width='".$wpss_icon_size."' height='".$wpss_icon_size."' style='border-style:none;vertical-align:middle;padding-right:7px;' /> " . sprintf( __( 'WP-SpamShield has blocked <strong> %s </strong> spam!', WPSS_PLUGIN_NAME ), spamshield_number_format( $spamshield_count ) ) . "</p>\n\t\t\t";
				if ( $avg_blocked_daily >= 2 ) {
					echo "<p style='line-height:180%;'><img src='".WPSS_PLUGIN_IMG_URL."/spacer.gif' alt='' width='".$wpss_icon_size."' height='".$wpss_sm_txt_spacer_size."' style='border-style:none;vertical-align:middle;padding-right:7px;' /> " . sprintf( __( 'That\'s <strong> %s </strong> spam a day that you don\'t have to worry about.', WPSS_PLUGIN_NAME ), $avg_blocked_daily_disp ) . "</p>\n\t\t\t";
					}
				if ( spamshield_is_lang_en_us() && $est_hours_returned >= 2 ) {
					echo "<p style='line-height:180%;'><img src='".WPSS_PLUGIN_IMG_URL."/spacer.gif' alt='' width='".$wpss_icon_size."' height='".$wpss_sm_txt_spacer_size."' style='border-style:none;vertical-align:middle;padding-right:7px;' /> " . sprintf( __( 'This plugin has saved you <strong> %s </strong> hours of managing spam. You\'re welcome!', WPSS_PLUGIN_NAME ), $est_hours_returned_disp ) . "</p>\n\t\t\t";
					}
				echo "</div>\n\t\t\t";
				}
			if ( !empty( $_REQUEST['submitted_wpss_general_options'] ) && current_user_can( 'manage_options' ) && check_admin_referer('wpss_update_general_options_token','ugo_tkn') ) {
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
				else { $comment_logging_start_date = 0; }

				/* Reset Log when turning on Comment Logging - 1.7.9 */
				if ( !empty( $req_comment_logging ) && empty( $spamshield_options['comment_logging'] ) ) {
					$comment_logging_start_date = time();
					spamshield_log_reset();
					}


				/* Validate Request Values */
				if ( !empty( $_REQUEST ) ) { $valid_req_spamshield_options = $_REQUEST;	} else { $valid_req_spamshield_options = array(); }

				$wpss_options_general_boolean = array ( 'block_all_trackbacks', 'block_all_pingbacks', 'comment_logging', 'comment_logging_all', 'enhanced_comment_blacklist', 'enable_whitelist', 'allow_proxy_users', 'hide_extra_data', 'js_head_disable', 'registration_shield_disable', 'registration_shield_level_1', 'allow_comment_author_keywords', 'promote_plugin_link' );

				$wpss_options_general_boolean_count = count( $wpss_options_general_boolean );
				$i = 0;
				while ( $i < $wpss_options_general_boolean_count ) {
					if ( !empty( $_REQUEST[$wpss_options_general_boolean[$i]] ) && ( $_REQUEST[$wpss_options_general_boolean[$i]] == 'on' || $_REQUEST[$wpss_options_general_boolean[$i]] == 1 || $_REQUEST[$wpss_options_general_boolean[$i]] == '1' ) ) { $valid_req_spamshield_options[$wpss_options_general_boolean[$i]] = 1; }
					else { $valid_req_spamshield_options[$wpss_options_general_boolean[$i]] = 0; }
					$i++;
					}
				if ( empty( $spamshield_options['comment_logging_all'] ) && $valid_req_spamshield_options['comment_logging_all'] == 1 ) { /* Added 1.4.3 - Turns Blocked Comment Logging Mode on if user selects "Log All Comments" */
					$valid_req_spamshield_options['comment_logging'] = 1;
					}
				if ( !empty( $spamshield_options['comment_logging'] ) && $valid_req_spamshield_options['comment_logging'] == 0 ) { /* Added 1.4.3 - If Blocked Comment Logging Mode is turned off then deselects "Log All Comments" */
					$valid_req_spamshield_options['comment_logging_all'] = 0;
					}

				/* Update Values */
				$spamshield_options = array (
						'block_all_trackbacks' 					=> $valid_req_spamshield_options['block_all_trackbacks'],
						'block_all_pingbacks' 					=> $valid_req_spamshield_options['block_all_pingbacks'],
						'comment_logging'						=> $valid_req_spamshield_options['comment_logging'],
						'comment_logging_start_date'			=> $comment_logging_start_date,
						'comment_logging_all'					=> $valid_req_spamshield_options['comment_logging_all'],
						'enhanced_comment_blacklist'			=> $valid_req_spamshield_options['enhanced_comment_blacklist'],
						'enable_whitelist'						=> $valid_req_spamshield_options['enable_whitelist'],
						'allow_proxy_users'						=> $valid_req_spamshield_options['allow_proxy_users'],
						'hide_extra_data'						=> $valid_req_spamshield_options['hide_extra_data'],
						'js_head_disable'						=> $valid_req_spamshield_options['js_head_disable'],
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
				update_option( 'spamshield_options', $spamshield_options );
				if ( !empty( $ip ) ) { update_option( 'spamshield_last_admin', $ip ); }
				$blacklist_keys_update = trim(stripslashes($_REQUEST['wordpress_comment_blacklist']));
				spamshield_update_bw_list_keys( 'black', $blacklist_keys_update );
				$whitelist_keys_update = trim(stripslashes($_REQUEST['wpss_whitelist']));
				spamshield_update_bw_list_keys( 'white', $whitelist_keys_update );
				}
			if ( !empty( $_REQUEST['submitted_wpss_contact_options'] ) && current_user_can( 'manage_options' ) && check_admin_referer('wpss_update_contact_options_token','uco_tkn') ) {
				/* Validate Request Values */
				if ( !empty( $_REQUEST ) ) { $valid_req_spamshield_options = $_REQUEST;	} else { $valid_req_spamshield_options = array(); }
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
					if ( $form_message_width_temp < 40 ) { $form_message_width_temp = 40; }
					$valid_req_spamshield_options['form_message_width']	= $form_message_width_temp;
					}
				else { $valid_req_spamshield_options['form_message_width'] = $spamshield_default['form_message_width']; }
				if ( !empty( $_REQUEST['form_message_height'] ) ) {
					$form_message_height_temp = trim( stripslashes( $_REQUEST['form_message_height'] ) );
					if ( $form_message_height_temp < 5 ) { $form_message_height_temp = 5; }
					$valid_req_spamshield_options['form_message_height'] = $form_message_height_temp;
					}
				else { $valid_req_spamshield_options['form_message_height'] = $spamshield_default['form_message_height']; }
				if ( !empty( $_REQUEST['form_message_min_length'] ) ) {
					$form_message_min_length_temp = trim( stripslashes( $_REQUEST['form_message_min_length'] ) );
					if ( $form_message_min_length_temp < 15 ) {	$form_message_min_length_temp = 15;	}
					$valid_req_spamshield_options['form_message_min_length'] = $form_message_min_length_temp;
					}
				else { $valid_req_spamshield_options['form_message_min_length'] = $spamshield_default['form_message_min_length']; }
				if ( !empty( $_REQUEST['form_message_recipient'] ) ) {
					$form_message_recipient_temp = trim( stripslashes( $_REQUEST['form_message_recipient'] ) );
					if ( is_email( $form_message_recipient_temp ) ) { $valid_req_spamshield_options['form_message_recipient'] = $form_message_recipient_temp; }
					else { $valid_req_spamshield_options['form_message_recipient'] = $wpss_admin_email; }
					}
				else { $valid_req_spamshield_options['form_message_recipient'] = $wpss_admin_email; }
				$spamshield_options = array (
						'block_all_trackbacks' 					=> $spamshield_options['block_all_trackbacks'],
						'block_all_pingbacks' 					=> $spamshield_options['block_all_pingbacks'],
						'comment_logging'						=> $spamshield_options['comment_logging'],
						'comment_logging_start_date'			=> $spamshield_options['comment_logging_start_date'],
						'comment_logging_all'					=> $spamshield_options['comment_logging_all'],
						'enhanced_comment_blacklist'			=> $spamshield_options['enhanced_comment_blacklist'],
						'enable_whitelist'						=> $spamshield_options['enable_whitelist'],
						'allow_proxy_users'						=> $spamshield_options['allow_proxy_users'],
						'hide_extra_data'						=> $spamshield_options['hide_extra_data'],
						'js_head_disable'						=> $spamshield_options['js_head_disable'],
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
				if ( !empty( $spamshield_options['comment_logging_all'] ) ) { $spamshield_options['comment_logging'] = 1; }
				if ( empty( $spamshield_options['comment_logging'] ) ) { $spamshield_options['comment_logging_all'] = 0; }
				update_option( 'spamshield_options', $spamshield_options );
				spamshield_update_session_data($spamshield_options);
				if ( !empty( $_SERVER['REMOTE_ADDR'] ) ) { update_option( 'spamshield_last_admin', $_SERVER['REMOTE_ADDR'] ); }
				}
			if ( !spamshield_is_lang_en_us() ) { $wpss_info_box_height = '335'; } else { $wpss_info_box_height = '315'; }

			$wordpress_comment_blacklist	= spamshield_get_bw_list_keys( 'black' );
			$wpss_whitelist 				= spamshield_get_bw_list_keys( 'white' );

			?>

			<div style="width:375px;height:<?php echo $wpss_info_box_height; ?>px;border-style:solid;border-width:1px;border-color:#003366;background-color:#DDEEFF;padding:0px 15px 0px 15px;margin-top:<?php echo $wpss_vert_margins; ?>px;margin-right:<?php echo $wpss_horz_margins; ?>px;float:left;clear:left;">
			<p><a name="wpss_top"><h3><?php _e( 'Quick Navigation - Contents', WPSS_PLUGIN_NAME ); ?></h3></a></p>
			<ol style="list-style-type:decimal;padding-left:30px;">
				<li><a href="#wpss_general_options"><?php _e( 'General Settings', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="#wpss_contact_form_options"><?php _e( 'Contact Form Settings', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_URL; ?>#wpss_installation_instructions" target="_blank" rel="external" ><?php _e( 'Installation Instructions', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_URL; ?>#wpss_displaying_stats" target="_blank" rel="external" ><?php _e( 'Displaying Spam Stats on Your Blog', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_URL; ?>#wpss_adding_contact_form" target="_blank" rel="external" ><?php _e( 'Adding a Contact Form to Your Blog', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_URL; ?>#wpss_configuration" target="_blank" rel="external" ><?php _e( 'Configuration Information', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_URL; ?>#wpss_known_conflicts" target="_blank" rel="external" ><?php _e( 'Known Plugin Conflicts', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_URL; ?>#wpss_troubleshooting" target="_blank" rel="external" ><?php _e( 'Troubleshooting Guide / Support', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="#wpss_let_others_know"><?php _e( 'Let Others Know About WP-SpamShield', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="#wpss_download_plugin_documentation"><?php echo spamshield_doc_txt(); ?></a></li>
			</ol>
			</div>
			<div style="width:375px;height:<?php echo $wpss_info_box_height; ?>px;border-style:solid;border-width:1px;border-color:#003366;background-color:#DDEEFF;padding:0px 15px 0px 15px;margin-top:<?php echo $wpss_vert_margins; ?>px;margin-right:<?php echo $wpss_horz_margins; ?>px;float:left;">
			<p>
			<?php if ( $spamshield_count > 100 ) { ?>
			<a name="wpss_rate"><h3><?php _e( 'Happy with WP-SpamShield?', WPSS_PLUGIN_NAME ); ?></h3></a></p>
			<p><img src='<?php echo WPSS_PLUGIN_IMG_URL; ?>/5-stars-rating.png' alt='' width='99' height='19' align='right' style='border-style:none;padding:3px 0 20px 20px;float:right;' /><a href="<?php echo WPSS_WP_RATING_URL; ?>" target="_blank" rel="external" ><?php _e( 'Let others know by giving it a good rating on WordPress.org!', WPSS_PLUGIN_NAME ); ?></a><br /><br />
			<?php } ?>

			<strong><?php echo spamshield_doc_txt(); ?>:</strong> <a href="<?php echo WPSS_HOME_URL; ?>" target="_blank" rel="external" ><?php _e( 'Plugin Homepage', WPSS_PLUGIN_NAME ); ?></a><br />
			<strong><?php _e( 'Tech Support', WPSS_PLUGIN_NAME ); ?>:</strong> <a href="<?php echo WPSS_HOME_URL; ?>support/" target="_blank" rel="external" ><?php _e( 'WP-SpamShield Support', WPSS_PLUGIN_NAME ); ?></a><br />
			<strong><?php _e( 'Follow on Twitter', WPSS_PLUGIN_NAME ); ?>:</strong> <a href="http://twitter.com/WPSpamShield" target="_blank" rel="external" >@WPSpamShield</a><br />			
			<strong><?php _e( 'Let Others Know', WPSS_PLUGIN_NAME ); ?>:</strong> <a href="http://www.redsandmarketing.com/blog/wp-spamshield-wordpress-plugin-released/#comments" target="_blank" rel="external" ><?php _e( 'Leave a Comment' ); ?></a><br />
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="margin-top:10px;">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="DFMTNHJEPFFUL">
            <input type="image" src="<?php echo WPSS_PLUGIN_IMG_URL; ?>/btn-donate-sm.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
            <img alt="" border="0" src="<?php echo WPSS_PLUGIN_IMG_URL; ?>/spacer.gif" width="1" height="1" />
            </form>
			</p>
			<?php 
			echo '<p><strong><a href="http://bit.ly/wp-spamshield-donate" title="' . __( 'WP-SpamShield is provided for free.', WPSS_PLUGIN_NAME ) . ' ' . __( 'If you like the plugin, consider a donation to help further its development.', WPSS_PLUGIN_NAME ) . '" target="_blank" rel="external" >' . __( 'Donate to WP-SpamShield', WPSS_PLUGIN_NAME ) . '</a></strong></p>';
			?>
			</div>
			<div style='width:797px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:<?php echo $wpss_vert_margins; ?>px;margin-right:<?php echo $wpss_horz_margins; ?>px;float:left;clear:left;'>
			<p><a name="wpss_general_options"><h3><?php _e( 'General Settings', WPSS_PLUGIN_NAME ); ?></h3></a></p>
			<form name="wpss_general_options" method="post">
			<input type="hidden" name="submitted_wpss_general_options" value="1" />
            <?php
			wp_nonce_field('wpss_update_general_options_token','ugo_tkn');
			/* TO DO: Roll up to improve efficiency of code */
			?>

			<fieldset class="options">
				<ul style="list-style-type:none;padding-left:30px;">

					<li>
					<label for="comment_logging">
						<input type="checkbox" id="comment_logging" name="comment_logging" <?php echo ($spamshield_options['comment_logging']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php echo __( 'Blocked Comment Logging Mode', WPSS_PLUGIN_NAME ) . ' &mdash; ' . __( 'See what spam has been blocked!', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Temporary diagnostic mode that logs blocked comment submissions for 7 days, then turns off automatically.', WPSS_PLUGIN_NAME ); ?><br /><?php _e( 'Log is cleared each time this feature is turned on.', WPSS_PLUGIN_NAME ); ?>
					</label>
					<?php
					if ( !empty( $spamshield_options['comment_logging'] ) ) {
						// If comment logging is on, check file permissions and attempt to fix. Reset .htaccess file for data dir, to allow IP of current admin to view log file. Let user know if not set correctly.
						$wpss_hta_reset = spamshield_log_reset( NULL, TRUE, TRUE );
						if ( empty( $wpss_hta_reset ) ) {
							echo '<br />'."\n".'<span style="color:red;"><strong>' . sprintf( __( 'The log file may not be writeable. You may need to manually correct the file permissions.<br />Set the permission for the "%1$s" directory to "%2$s" and all files within it to "%3$s".</strong><br />If that doesn\'t work, then please read the <a href="%4$s" %5$s>FAQ</a> for this topic.', WPSS_PLUGIN_NAME ), WPSS_PLUGIN_DATA_PATH, '755', '644', WPSS_HOME_URL.'#wpss_faqs_5', 'target="_blank"' ) . '</span><br />'."\n";
							}
						}
					else { spamshield_log_reset( NULL, FALSE, FALSE, TRUE ); /* Create log file if it doesn't exist */ }
					?>
					<br /><strong><a href="<?php echo WPSS_PLUGIN_DATA_URL; ?>/temp-comments-log.txt" target="_blank"><?php _e( 'Download Comment Log File', WPSS_PLUGIN_NAME ); ?></a> - <?php _e( 'Right-click, and select "Save Link As"', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</li>
					<li>
					<label for="comment_logging_all">
						<input type="checkbox" id="comment_logging_all" name="comment_logging_all" <?php echo ($spamshield_options['comment_logging_all']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Log All Comments', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Requires that Blocked Comment Logging Mode be engaged. Instead of only logging blocked comments, this will allow the log to capture all comments while logging mode is turned on. This provides more technical data for comment submissions than WordPress provides, and helps us improve the plugin.<br />If you plan on submitting spam samples to us for analysis, it\'s helpful for you to turn this on, otherwise it\'s not necessary.', WPSS_PLUGIN_NAME ); ?></label>
					<br /><a href="<?php echo WPSS_HOME_URL; ?>#wpss_configuration_log_all_comments" target="_blank" rel="external" ><?php _e( 'For more about this, see the documentation.', WPSS_PLUGIN_NAME ); ?></a><br />&nbsp;
					</li>
					<li>
					<label for="enhanced_comment_blacklist">
						<input type="checkbox" id="enhanced_comment_blacklist" name="enhanced_comment_blacklist" <?php echo ($spamshield_options['enhanced_comment_blacklist']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Enhanced Comment Blacklist', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Enhances WordPress\'s Comment Blacklist - instead of just sending comments to moderation, they will be completely blocked. Also adds a link in the comment notification emails that will let you blacklist a commenter\'s IP with one click.<br />(Useful if you receive repetitive human spam or harassing comments from a particular commenter.)', WPSS_PLUGIN_NAME ); ?></label>
					<br /><a href="<?php echo WPSS_HOME_URL; ?>#wpss_configuration_enhanced_comment_blacklist" target="_blank" rel="external" ><?php _e( 'For more about this, see the documentation.', WPSS_PLUGIN_NAME ); ?></a><br />&nbsp;
					</li>
					<label for="wordpress_comment_blacklist">
						<strong><?php _e( 'Your current WordPress Comment Blacklist', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'When a comment contains any of these words in its content, name, URL, e-mail, or IP, it will be completely blocked, not just marked as spam. One word or IP per line. It is not case-sensitive and will match included words, so "press" on your blacklist will block "WordPress" in a comment.', WPSS_PLUGIN_NAME ); ?><br />
						<textarea id="wordpress_comment_blacklist" name="wordpress_comment_blacklist" cols="80" rows="8" /><?php echo $wordpress_comment_blacklist; ?></textarea><br />
					</label>
					<?php _e( 'You can update this list here.', WPSS_PLUGIN_NAME ); ?> <a href="<?php echo RSMP_ADMIN_URL; ?>/options-discussion.php"><?php _e( 'You can also update it on the WordPress Discussion Settings page.', WPSS_PLUGIN_NAME ); ?></a><br />&nbsp;
					<li>
					<label for="enable_whitelist">
						<input type="checkbox" id="enable_whitelist" name="enable_whitelist" <?php echo ($spamshield_options['enable_whitelist']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Enable WP-SpamShield Whitelist', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Enables WP-SpamShield\'s Whitelist - for comments and contact form submissions. When a comment or contact form is submitted from an e-mail address on the whitelist, it will bypass spam filters and be allowed through.<br />(Useful if you have specific users that you want to let bypass the filters.)', WPSS_PLUGIN_NAME ); ?></label>
					<br /><a href="<?php echo WPSS_HOME_URL; ?>#wpss_configuration_enable_whitelist" target="_blank" rel="external" ><?php _e( 'For more about this, see the documentation.', WPSS_PLUGIN_NAME ); ?></a><br />&nbsp;
					</li>
					<label for="wpss_whitelist">
						<strong><?php _e( 'Your current WP-SpamShield Whitelist', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'One email address per line. Each entry must be a valid and complete email address, like <em>user@yourwebsite.com</em>. It is not case-sensitive and will only make exact matches, not partial matches.', WPSS_PLUGIN_NAME ); ?><br />
						<textarea id="wpss_whitelist" name="wpss_whitelist" cols="80" rows="8" /><?php echo $wpss_whitelist; ?></textarea><br />&nbsp;
					</label>
					<li>
					<label for="block_all_trackbacks">
						<input type="checkbox" id="block_all_trackbacks" name="block_all_trackbacks" <?php echo ($spamshield_options['block_all_trackbacks']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Disable trackbacks.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Use if trackback spam is excessive. (Not recommended)', WPSS_PLUGIN_NAME ); ?><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="block_all_pingbacks">
						<input type="checkbox" id="block_all_pingbacks" name="block_all_pingbacks" <?php echo ($spamshield_options['block_all_pingbacks']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Disable pingbacks.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Use if pingback spam is excessive. Disadvantage is reduction of communication between blogs. (Not recommended)', WPSS_PLUGIN_NAME ); ?><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="allow_proxy_users">
						<input type="checkbox" id="allow_proxy_users" name="allow_proxy_users" <?php echo ($spamshield_options['allow_proxy_users']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Allow users behind proxy servers to comment?', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'Many human spammers hide behind proxies, so you can uncheck this option for extra protection. (For highest user compatibility, leave it checked.)', WPSS_PLUGIN_NAME ); ?><br />&nbsp;</label>
					</li>
					<li>
					<label for="hide_extra_data">
						<input type="checkbox" id="hide_extra_data" name="hide_extra_data" <?php echo ($spamshield_options['hide_extra_data']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Hide extra technical data in comment notifications.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'This data is helpful if you need to submit a spam sample. If you dislike seeing the extra info, you can use this option.', WPSS_PLUGIN_NAME ); ?><br />&nbsp;</label>					
					</li>
					<li>
					<label for="registration_shield_disable">
						<input type="checkbox" id="registration_shield_disable" name="registration_shield_disable" <?php echo ($spamshield_options['registration_shield_disable']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Disable Registration Spam Shield.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php _e( 'This option will disable the anti-spam shield for the WordPress registration form only. While not recommended, this option is available if you need it. Anti-spam will still remain active for comments, pingbacks, trackbacks, and contact forms.', WPSS_PLUGIN_NAME ); ?><br />&nbsp;</label>					
					</li>
					<li>
					<label for="allow_comment_author_keywords">
						<input type="checkbox" id="allow_comment_author_keywords" name="allow_comment_author_keywords" <?php echo ($spamshield_options['allow_comment_author_keywords']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Allow Keywords in Comment Author Names.', WPSS_PLUGIN_NAME ); ?></strong><br /><?php echo sprintf( __( 'This will allow some keywords to be used in comment author names. By default, WP-SpamShield blocks many common spam keywords from being used in the comment "%1$s" field. This option is useful for sites with users that use pseudonyms, or for sites that simply want to allow business names and keywords to be used in the comment "%2$s" field. This option is not recommended, as it can potentially allow more human spam, but it is available if you choose. Your site will still be protected against all automated comment spam.', WPSS_PLUGIN_NAME ), __( 'Name' ), __( 'Name' ) ); ?><br />&nbsp;</label>
					</li>
					<li>
					<label for="promote_plugin_link">
						<input type="checkbox" id="promote_plugin_link" name="promote_plugin_link" <?php echo ($spamshield_options['promote_plugin_link']==TRUE?"checked=\"checked\"":"") ?> value="1" />
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
			<div style='width:797px;border-style:solid;border-width:1px;border-color:#003366;background-color:#DDEEFF;padding:0px 15px 0px 15px;margin-top:<?php echo $wpss_vert_margins; ?>px;margin-right:<?php echo $wpss_horz_margins; ?>px;float:left;clear:left;'>
			<p><a name="wpss_contact_form_options"><h3><?php _e( 'Contact Form Settings', WPSS_PLUGIN_NAME ); ?></h3></a></p>
			<form name="wpss_contact_options" method="post">
			<input type="hidden" name="submitted_wpss_contact_options" value="1" />
            <?php
			wp_nonce_field('wpss_update_contact_options_token','uco_tkn');
			/* TO DO: Roll up to improve efficiency of code */
			?>

			<fieldset class="options">
				<ul style="list-style-type:none;padding-left:30px;">

					<li>
					<label for="form_include_website">
						<input type="checkbox" id="form_include_website" name="form_include_website" <?php echo ($spamshield_options['form_include_website']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Include "Website" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_website">
						<input type="checkbox" id="form_require_website" name="form_require_website" <?php echo ($spamshield_options['form_require_website']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Require "Website" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_phone">
						<input type="checkbox" id="form_include_phone" name="form_include_phone" <?php echo ($spamshield_options['form_include_phone']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Include "Phone" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_phone">
						<input type="checkbox" id="form_require_phone" name="form_require_phone" <?php echo ($spamshield_options['form_require_phone']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Require "Phone" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_company">
						<input type="checkbox" id="form_include_company" name="form_include_company" <?php echo ($spamshield_options['form_include_company']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Include "Company" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_company">
						<input type="checkbox" id="form_require_company" name="form_require_company" <?php echo ($spamshield_options['form_require_company']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Require "Company" field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>					<li>
					<label for="form_include_drop_down_menu">
						<input type="checkbox" id="form_include_drop_down_menu" name="form_include_drop_down_menu" <?php echo ($spamshield_options['form_include_drop_down_menu']==TRUE?"checked=\"checked\"":"") ?> value="1" />
						<strong><?php _e( 'Include drop-down menu select field.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_require_drop_down_menu">
						<input type="checkbox" id="form_require_drop_down_menu" name="form_require_drop_down_menu" <?php echo ($spamshield_options['form_require_drop_down_menu']==TRUE?"checked=\"checked\"":"") ?> value="1" />
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
						<input type="text" size="40" id="form_message_recipient" name="form_message_recipient" value="<?php if ( empty( $form_message_recipient ) ) { echo $wpss_admin_email; } else { echo $form_message_recipient; } ?>" />
						<strong><?php _e( 'Optional: Enter alternate form recipient. Default is blog admin email.', WPSS_PLUGIN_NAME ); ?></strong><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_response_thank_you_message">
						<?php 
						$form_response_thank_you_message = trim(stripslashes($spamshield_options['form_response_thank_you_message']));
						?>
						<?php _e( '<strong>Enter message to be displayed upon successful contact form submission.</strong><br />Can be plain text, HTML, or an ad, etc.', WPSS_PLUGIN_NAME ); ?><br />
						<textarea id="form_response_thank_you_message" name="form_response_thank_you_message" cols="80" rows="3" /><?php if ( empty( $form_response_thank_you_message ) ) { _e( 'Your message was sent successfully. Thank you.', WPSS_PLUGIN_NAME ); } else { echo $form_response_thank_you_message; } ?></textarea><br />&nbsp;
					</label>
					</li>
					<li>
					<label for="form_include_user_meta">
						<input type="checkbox" id="form_include_user_meta" name="form_include_user_meta" <?php echo ($spamshield_options['form_include_user_meta']==TRUE?"checked=\"checked\"":"") ?> value="1" />
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
			<div style='width:797px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:<?php echo $wpss_vert_margins; ?>px;margin-right:<?php echo $wpss_horz_margins; ?>px;float:left;clear:left;'>
  			<p><a name="wpss_let_others_know"><h3><?php _e( 'Let Others Know About WP-SpamShield', WPSS_PLUGIN_NAME ); ?></h3></a></p>
			<p><?php _e( '<strong>How does it feel to blog without being bombarded by automated comment spam?</strong> If you\'re happy with WP-SpamShield, there\'s a few things you can do to let others know:', WPSS_PLUGIN_NAME ); ?></p>
			<ul style="list-style-type:disc;padding-left:30px;">
				<li><a href="http://www.redsandmarketing.com/blog/wp-spamshield-wordpress-plugin-released/#comments" target="_blank" rel="external" ><?php _e( 'Leave a Comment' ); ?></a></li>
				<li><a href="<?php echo WPSS_WP_RATING_URL; ?>" target="_blank" rel="external" ><?php _e( 'Give WP-SpamShield a good rating on WordPress.org.', WPSS_PLUGIN_NAME ); ?></a></li>
				<li><a href="<?php echo WPSS_HOME_URL; ?>end-blog-spam/" target="_blank" rel="external" ><?php _e( 'Place a graphic link on your site.', WPSS_PLUGIN_NAME ); ?></a> <?php _e( 'Let others know how they can help end blog spam.', WPSS_PLUGIN_NAME ); ?> ( &lt/BLOGSPAM&gt; )</li>
			</ul>
			<p><a href="<?php echo WPSS_HOME_URL; ?>" style="border-style:none;text-decoration:none;" target="_blank" rel="external" ><img src="<?php echo WPSS_PLUGIN_IMG_URL; ?>/end-blog-spam-button-01-black.png" alt="End Blog Spam! WP-SpamShield Comment Spam Protection for WordPress" width="140" height="66" style="border-style:none;text-decoration:none;margin-top:15px;margin-left:15px;" /></a></p>
			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top"><?php _e( 'BACK TO TOP', WPSS_PLUGIN_NAME ); ?></a> ]</div></p>
			<p>&nbsp;</p>
			</div>
			<div style='width:797px;border-style:solid;border-width:1px;border-color:#003366;background-color:#DDEEFF;padding:0px 15px 0px 15px;margin-top:<?php echo $wpss_vert_margins; ?>px;margin-right:<?php echo $wpss_horz_margins; ?>px;float:left;clear:left;'>
			<p><a name="wpss_download_plugin_documentation"><h3><?php echo spamshield_doc_txt(); ?></h3></a></p>
			<p><?php echo __( 'Plugin Homepage', WPSS_PLUGIN_NAME ) . ' / ' . spamshield_doc_txt(); ?>: <a href="<?php echo WPSS_HOME_URL; ?>" target="_blank" rel="external" >WP-SpamShield</a><br />
			<?php _e( 'Leave a Comment' ); ?>: <a href="http://www.redsandmarketing.com/blog/wp-spamshield-wordpress-plugin-released/" target="_blank" rel="external" ><?php _e( 'WP-SpamShield Release Announcement Blog Post', WPSS_PLUGIN_NAME ); ?></a><br />
			<?php _e( 'WordPress.org Page', WPSS_PLUGIN_NAME ); ?>: <a href="<?php echo WPSS_WP_URL; ?>" target="_blank" rel="external" >WP-SpamShield</a><br />
			<?php _e( 'Tech Support / Questions', WPSS_PLUGIN_NAME ); ?>: <a href="<?php echo WPSS_HOME_URL; ?>support/" target="_blank" rel="external" ><?php _e( 'WP-SpamShield Support Page', WPSS_PLUGIN_NAME ); ?></a><br />
			<?php _e( 'End Blog Spam', WPSS_PLUGIN_NAME ); ?>: <a href="<?php echo WPSS_HOME_URL; ?>end-blog-spam/" target="_blank" rel="external" ><?php _e( 'Let Others Know About WP-SpamShield', WPSS_PLUGIN_NAME ); ?>!</a><br />
			Twitter: <a href="http://twitter.com/WPSpamShield" target="_blank" rel="external" >@WPSpamShield</a><br />
			<?php 
			if ( spamshield_is_lang_en_us() ) {
				echo 'Need WordPress Consulting? <a href="http://www.redsandmarketing.com/web-design/wordpress-consulting/" target="_blank" rel="external" >We can help.</a><br />';
				}
			?>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="margin-top:10px;">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="DFMTNHJEPFFUL">
            <input type="image" src="<?php echo WPSS_PLUGIN_IMG_URL; ?>/btn-donate-sm.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
            <img alt="" border="0" src="<?php echo WPSS_PLUGIN_IMG_URL; ?>/spacer.gif" width="1" height="1" />
            </form>
			</p>
			<?php 
			echo '<p><strong><a href="http://bit.ly/wp-spamshield-donate" target="_blank" rel="external" >' . __( 'Donate to WP-SpamShield', WPSS_PLUGIN_NAME ) . '</a></strong><br />' . __( 'WP-SpamShield is provided for free.', WPSS_PLUGIN_NAME ) . ' ' . __( 'If you like the plugin, consider a donation to help further its development.', WPSS_PLUGIN_NAME ) . '</p>';
			?>

			<p><div style="float:right;font-size:12px;">[ <a href="#wpss_top"><?php _e( 'BACK TO TOP', WPSS_PLUGIN_NAME ); ?></a> ]</div></p>
			<p>&nbsp;</p>
			</div>

			<?php
			// Recommended Partners - BEGIN - Added in 1.6.9
			if ( spamshield_is_lang_en_us() ) {
			?>

			<div style='width:797px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:<?php echo $wpss_vert_margins; ?>px;margin-right:<?php echo $wpss_horz_margins; ?>px;float:left;clear:left;'>
			<p><h3>Recommended Partners</h3></p>
			<p>Each of these products or services are ones that we highly recommend, based on our experience and the experience of our clients. We do receive a commission if you purchase one of these, but these are all products and services we were already recommending because we believe in them. By purchasing from these providers, you get quality and you help support the further development of WP-SpamShield.</p></div>

			<?php
				$wpss_rpd	= array(
								array('clear:left;','RSM_Genesis','Genesis WordPress Framework','Other themes and frameworks have nothing on Genesis. Optimized for site speed and SEO.','Simply put, the Genesis framework is one of the best ways to design and build a WordPress site. Built-in SEO and optimized for speed. Create just about any kind of design with child themes.'),
								array('','RSM_AIOSEOP','All in One SEO Pack Pro','The best way to manage the code-related SEO for your WordPress site.','Save time and effort optimizing the code of your WordPress site with All in One SEO Pack. One of the top rated, and most downloaded plugins on WordPress.org, this time-saving plugin is incredibly valuable. The pro version provides powerful features not available in the free version.'),
								/* array('clear:left;','RSM_Hostgator','Hostgator Website Hosting','Affordable, high quality web hosting. Great for WordPress and a variety of web applications.','Hostgator has variety of affordable plans, reliable service, and customer support. Even on shared hosting, you get fast servers that are well-configured. Hostgator provides great balance of value and quality, which is why we recommend them.'), */
								/* array('','RSM_Level10','Level10 Domains','Inexpensive web domains with an easy to use admin dashboard.','Level10 Domains offers some of the best prices you\'ll find on web domain purchasing. The dashboard provides an easy way to manage your domains.'), */
								);
				foreach( $wpss_rpd as $i => $v ) {
					echo '<div style="width:375px;height:280px;border-style:solid;border-width:1px;border-color:#333333;background-color:#FEFEFE;padding:0px 15px 0px 15px;margin-top:'.$wpss_vert_margins.'px;margin-right:'.$wpss_horz_margins.'px;float:left;'.$v[0].'">'."\n".'<p><strong><a href="http://bit.ly/'.$v[1].'" target="_blank" rel="external" >'.$v[2].'</a></strong></p>'."\n".'<p><strong>'.$v[3].'</strong></p>'."\n".'<p>'.$v[4].'</p>'."\n".'<p><a href="http://bit.ly/'.$v[1].'" target="_blank" rel="external" >Click here to find out more. &raquo;</a></p>'."\n".'</div>'."\n";
					}

				}
			// Recommended Partners - END - Added in 1.6.9
			?>
			<p style="clear:both;">&nbsp;</p>
			<p style="clear:both;"><em><?php 
			spamshield_settings_ver_ftr();
			?></em></p>
			<p><div style="float:right;clear:both;font-size:12px;">[ <a href="#wpss_top"><?php _e( 'BACK TO TOP', WPSS_PLUGIN_NAME ); ?></a> ]</div></p>
			<p>&nbsp;</p>
			</div>
			<?php
			}

		function spamshield_check_version() {
			if ( current_user_can( 'manage_network' ) ) {
				/* Check for pending admin notices */
				$admin_notices = get_option('spamshield_admin_notices');
				if ( !empty( $admin_notices ) ) { add_action( 'network_admin_notices', 'spamshield_admin_notices' ); }
				/* Make sure not network activated */
				if ( is_plugin_active_for_network( WPSS_PLUGIN_BASENAME ) ) {
					deactivate_plugins( WPSS_PLUGIN_BASENAME, TRUE, TRUE );
					$notice_text = __( 'Plugin deactivated. WP-SpamShield is not available for network activation.', WPSS_PLUGIN_NAME );
					$new_admin_notice = array( 'style' => 'error', 'notice' => $notice_text );
					update_option( 'spamshield_admin_notices', $new_admin_notice );
					add_action( 'network_admin_notices', 'spamshield_admin_notices' );
					spamshield_append_log_data( "\n".$notice_text, FALSE );
					return FALSE;
					}
				}
			if ( current_user_can( 'manage_options' ) ) {
				/* Check if plugin has been upgraded */
				spamshield_upgrade_check();
				/* Check for pending admin notices */
				$admin_notices = get_option('spamshield_admin_notices');
				if ( !empty( $admin_notices ) ) { add_action( 'admin_notices', 'spamshield_admin_notices' ); }
				/* Make sure user has minimum required WordPress version, in order to prevent issues */
				$wpss_wp_version = RSMP_WP_VERSION;
				if ( !empty( $wpss_wp_version ) && version_compare( $wpss_wp_version, WPSS_REQUIRED_WP_VERSION, '<' ) ) {
					deactivate_plugins( WPSS_PLUGIN_BASENAME );
					$notice_text = sprintf( __( 'Plugin deactivated. WordPress Version %s required. Please upgrade WordPress to the latest version.', WPSS_PLUGIN_NAME ), WPSS_REQUIRED_WP_VERSION );
					$new_admin_notice = array( 'style' => 'error', 'notice' => $notice_text );
					update_option( 'spamshield_admin_notices', $new_admin_notice );
					add_action( 'admin_notices', 'spamshield_admin_notices' );
					spamshield_append_log_data( "\n".$notice_text, FALSE );
					return FALSE;
					}
				/* Make sure user has minimum required PHP version, in order to prevent issues */
				$wpss_php_version = RSMP_PHP_VERSION;
				if ( !empty( $wpss_php_version ) && version_compare( $wpss_php_version, WPSS_REQUIRED_PHP_VERSION, '<' ) ) {
					deactivate_plugins( WPSS_PLUGIN_BASENAME );
					$notice_text = sprintf( __( '<p>Plugin deactivated. <strong>Your server is running PHP version %3$s, but WP-SpamShield requires at least PHP %1$s.</strong> We are no longer supporting PHP 5.2, as it has not been supported by the PHP team <a href=%2$s>since 2011</a>, and there are known security, performance, and compatibility issues.</p><p>The version of PHP running on your server is <em>extremely out of date</em>. You should upgrade your PHP version as soon as possible.</p><p>If you need help with this, please contact your web hosting company and ask them to switch your PHP version to 5.4 or 5.5. Please see the <a href=%4$s>plugin documentation</a> and <a href=%5$s>changelog</a> if you have further questions.</p>', WPSS_PLUGIN_NAME ), WPSS_REQUIRED_PHP_VERSION, '"http://php.net/archive/2011.php#id2011-08-23-1" target="_blank" rel="external" ', $wpss_php_version, '"'.WPSS_HOME_URL.'?src='.WPSS_VERSION.'-php-notice#wpss_requirements" target="_blank" rel="external" ', '"'.WPSS_HOME_URL.'version-history/?src='.WPSS_VERSION.'-php-notice#ver_182" target="_blank" rel="external" ' ); /* NEEDS TRANSLATION - Added 1.8.2 */
					$new_admin_notice = array( 'style' => 'error', 'notice' => $notice_text );
					update_option( 'spamshield_admin_notices', $new_admin_notice );
					add_action( 'admin_notices', 'spamshield_admin_notices' );
					spamshield_append_log_data( "\n".$notice_text, FALSE );
					return FALSE;
					}
				/* Security Check - See if (extremely) old version of plugin still active */
				$old_version = 'wp-spamfree/wp-spamfree.php';
				$old_version_active = spamshield_is_plugin_active( $old_version );
				if ( !empty( $old_version_active ) ) {
					/***
					* Not safe to keep old version active due to unpatched security hole(s), broken PHP, and lack of maintenance.
					* For security reasons, deactivate old version.
					***/
					deactivate_plugins( $old_version );
					/* Clean up database */
					$del_options = array( 'wp_spamfree_version', 'spamfree_count', 'spamfree_options' );
					foreach( $del_options as $i => $option ) { delete_option( $option ); }
					/***
					* Good to go!
					* Since WP-SpamShield takes over 100% of old version's responsibilities, there is no loss of functionality, only improvements.
					* Site speed will improve and server load will now drop dramatically.
					***/
					}
				/* Compatibility Checks */
				spamshield_admin_jp_fix();
				spamshield_admin_ao_fix(); /* Added 1.8.9.5 */
				}
			}

		function spamshield_editor_add_quicktags() {
			 /* Added 1.8.3 */
			$current_screen = get_current_screen();
			if ( !wp_script_is( 'quicktags' ) || $current_screen->parent_base != 'edit' || $current_screen->post_type != 'page' || !current_user_can( 'edit_pages' ) ) { return; }
			echo "\n".'<script type=\'text/javascript\'>'."\n".'// <![CDATA['."\n".'QTags.addButton( \'wpss_cf\', \'WPSS '.__( 'Contact Form', WPSS_PLUGIN_NAME ).'\', \'[spamshieldcontact]\', \'\', \'\', \'WP-SpamShield '.__( 'Contact Form', WPSS_PLUGIN_NAME ).'\', 999 );'."\n".'// ]]>'."\n".'</script>';
			}

		/* Class Admin Functions - END */

		function spamshield_insert_head_js() {
			/***
			* This JavaScript is purposely NOT enqueued. It's not coded improperly. This is done exactly like it is for a very good reason.
			* It needs to not be altered or messed with by any other plugin.
			* The JS file is really a dynamically generated hybrid script that uses both server-side and client-side code so it requires the PHP functionality.
			* "But couldn't that be done by..." Stop right there...No, it cannot.
			***/

			if ( ( !is_admin() && is_user_logged_in() ) || !is_user_logged_in() ) {
				if ( TRUE == WPSS_EDGE ) { $spamshield_options = get_option('spamshield_options'); }
				if ( FALSE == WPSS_EDGE || empty( $spamshield_options['js_head_disable'] ) ) {
					echo "\n";
					echo "<script type='text/javascript' src='".WPSS_PLUGIN_JS_URL."/jscripts.php'></script> "."\n";
					if ( !empty( $_SESSION['wpss_user_ip_init_'.RSMP_HASH] ) ) {
						$_SESSION['wpss_user_ip_init_'.RSMP_HASH] = $_SERVER['REMOTE_ADDR'];
						}
					}
				}
			}

		function spamshield_enqueue_scripts() {
			if ( is_preview() ) { return FALSE; }
			if ( ( !is_admin() && is_user_logged_in() ) || !is_user_logged_in() ) {
				$js_handle = 'wpss-jscripts-ftr'; $js_file = 'jscripts-ftr-min.js'; $js_ver = NULL; /* '1.0', WPSS_VERSION */
				wp_register_script( $js_handle, WPSS_PLUGIN_JS_URL.'/'.$js_file, array(), $js_ver, TRUE );
				wp_enqueue_script( $js_handle );
				}
			}

		}
	}

/* wpSpamShield CLASS - END */


/* Promo Link Functions - BEGIN */
function spamshield_promo_text( $int ) {
	/***
	* Text to display in counter widget and promo links.
	* Also used in link title attribute.
	***/
	$promo_txt = array(
		/* Showing key numbers to avoid having to count manually when implementing */
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
	/* Promo link for contact page if user opts in */
	$contact_form_title = __( 'WP-SpamShield Contact Form for WordPress', WPSS_PLUGIN_NAME );
	$contact_form_txt = __( 'Contact Form', WPSS_PLUGIN_NAME );
	$url = spamshield_get_promo_link_url();
	if ( empty( $url ) ) { $url = WPSS_HOME_URL; }
	$link_template = '<a href="'.$url.'" title="'.$contact_form_title.'" >X1X2X</a>';
	$promo_link_data = array(
		/* Showing key numbers to avoid having to count manually when implementing */
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
	/* Promo link for comments box if user opts in */
	$comment_title = __( 'WP-SpamShield WordPress Anti-Spam Plugin', WPSS_PLUGIN_NAME );
	$url = spamshield_get_promo_link_url();
	if ( empty( $url ) ) { $url = WPSS_HOME_URL; }
	$link_template = '<a href="'.$url.'" title="'.$comment_title.'" >X1X2X</a>';
	$promo_link_data = array(
		/* Showing key numbers to avoid having to count manually when implementing */
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
	/***
	* In the plugin promo links, sometimes use plugin homepage link, sometime use WP.org link to make sure both get visited
	* 4 to 1 plugin homepage to WP.org (which more people link to anyway)
	***/
	$sip5c = '0';
	$sip5c = substr(RSMP_SERVER_ADDR, 4, 1); /* Server IP 5th Char */
	$gplu_code = array( '0' => 0, '1' => 1, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 1, '.' => 0 );
	$urls = array( WPSS_HOME_URL, WPSS_WP_URL );
	if ( preg_match( "~^[0-9\.]$~", $sip5c ) ) { $k = $gplu_code[$sip5c]; } else { $k = 0; }
	$url = $urls[$k];
	if ( empty( $url ) ) { $url = WPSS_HOME_URL; }
	return $url;
	}
/* Promo Link Functions - END */


/* Instantiate the class */
if (class_exists('wpSpamShield')) {
	$wpSpamShield = new wpSpamShield();
	}

/* PLUGIN - END */

