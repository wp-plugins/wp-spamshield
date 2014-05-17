<?php
// WP-SpamShield Dynamic JS File
// Updated in Version 1.1.6

// Security Sanitization - BEGIN
$id='';
if ( !empty( $_GET ) || preg_match ( "/\?/", $_SERVER['REQUEST_URI'] ) ) {
	header('HTTP/1.1 403 Forbidden');
	die('ERROR: This file will not function with a query string. Remove the query string from the URL and try again.');
	}
if ( !empty( $_POST ) || $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	header('HTTP/1.1 405 Method Not Allowed');
	die('ERROR: This file does not accept POST requests.');
	}
// Security Sanitization - END

// Uncomment to use:
$wpss_js_start_time = spamshield_microtime_js();

// SESSION CHECK AND FUNCTIONS - BEGIN
$wpss_session_test = @session_id();
if( empty($wpss_session_test) && !headers_sent() ) {
	@session_start();
	global $wpss_session_id;
	$wpss_session_id = @session_id();
	}

$wpss_server_ip_nodot = preg_replace( "/\./", "", $_SERVER['SERVER_ADDR'] );
if ( ! defined( 'WPSS_HASH_ALT' ) ) {
	$wpss_alt_prefix = hash( 'md5', $wpss_server_ip_nodot );
	define( 'WPSS_HASH_ALT', $wpss_alt_prefix );
	}
if ( ! defined( 'WPSS_SITE_URL' ) ) {
	if ( !empty( $_SESSION['wpss_site_url_'.WPSS_HASH_ALT] ) ) {
		$wpss_site_url 		= $_SESSION['wpss_site_url_'.WPSS_HASH_ALT];
		$wpss_plugin_url	= $_SESSION['wpss_plugin_url_'.WPSS_HASH_ALT];
		} 
	else { 
		// If not available, then best guess
		$raw_url = spamshield_get_url_js();
		// Next line is a 3rd string backup...rarely ever happens and won't break anything
		$wpss_site_url1 = preg_replace( "@/wp-content/plugins/wp-spamshield/js/jscripts\.php$@i", "", $raw_url );
		$wpss_site_url_exploded = explode( '/', $raw_url );
		$wpss_site_url_exploded_count = count( $wpss_site_url_exploded );
		if ( $wpss_site_url_exploded_count > 5 ) {
			$wpss_site_url_element_count = $wpss_site_url_exploded_count - 5;
			}
		$wpss_site_url_elements = array();
		$i = 0;
		while( $i < $wpss_site_url_element_count ) {
			$wpss_site_url_elements[] = $wpss_site_url_exploded[$i];
			$i++;
			}
		$wpss_site_url2 = implode('/', $wpss_site_url_elements);
		if ( !empty( $wpss_site_url2 ) ) { $wpss_site_url = $wpss_site_url2; } else { $wpss_site_url = $wpss_site_url1; }	
		}
	define( 'WPSS_SITE_URL', $wpss_site_url );
	}
if ( ! defined( 'WPSS_HASH' ) ) {
	$wpss_hash_prefix = hash( 'md5', WPSS_SITE_URL );
	define( 'WPSS_HASH', $wpss_hash_prefix );
	}
// SESSION CHECK AND FUNCTIONS - END

// IP, PAGE HITS, PAGES VISITED HISTORY - BEGIN
// Initial IP Address when visitor first comes to site
if ( empty( $_SESSION['wpss_user_ip_init_'.WPSS_HASH] ) ) {
	$_SESSION['wpss_user_ip_init_'.WPSS_HASH] = $_SERVER['REMOTE_ADDR'];
	}
// IP History - Lets see if they change IP's
if ( empty( $_SESSION['wpss_jscripts_ip_history_'.WPSS_HASH] ) ) {
	$_SESSION['wpss_jscripts_ip_history_'.WPSS_HASH] = array();
	$_SESSION['wpss_jscripts_ip_history_'.WPSS_HASH][] = $_SERVER['REMOTE_ADDR'];
	}
if ( $_SERVER['REMOTE_ADDR'] != $_SESSION['wpss_user_ip_init_'.WPSS_HASH] ) {
	$_SESSION['wpss_jscripts_ip_history_'.WPSS_HASH][] = $_SERVER['REMOTE_ADDR'];
	}
//Page hits - this page is more reliable than main if caching is on, so we'll keep a separate count
if ( empty( $_SESSION['wpss_page_hits_js_'.WPSS_HASH] ) ) {
	$_SESSION['wpss_page_hits_js_'.WPSS_HASH] = 0;
	}
++$_SESSION['wpss_page_hits_js_'.WPSS_HASH];
// Referrer History - More reliable way to keep a list of pages, than using main
if ( empty( $_SESSION['wpss_jscripts_referers_history_'.WPSS_HASH] ) ) {
	$_SESSION['wpss_jscripts_referers_history_'.WPSS_HASH] = array();
	}
if ( empty( $_SESSION['wpss_jscripts_referers_history_count_'.WPSS_HASH] ) ) {
	$_SESSION['wpss_jscripts_referers_history_count_'.WPSS_HASH] = array();
	}
if ( !empty( $_SERVER['HTTP_REFERER'] ) ) {
	$max = count( $_SESSION['wpss_jscripts_referers_history_'.WPSS_HASH] );
	if ( $max >= 1 ) {
		$o = 0;
		$i = 0;
		while ( $i < $max ) { if ( $_SESSION['wpss_jscripts_referers_history_'.WPSS_HASH][$i] == $_SERVER['HTTP_REFERER'] ) { $o++; } $i++; }
		}
	if ( $max == 0 || $o == 0 ) {
		$_SESSION['wpss_jscripts_referers_history_'.WPSS_HASH][] = $_SERVER['HTTP_REFERER'];
		}
	if ( empty( $_SESSION['wpss_jscripts_referers_history_count_'.WPSS_HASH][$_SERVER['HTTP_REFERER']] ) ) {
		$_SESSION['wpss_jscripts_referers_history_count_'.WPSS_HASH][$_SERVER['HTTP_REFERER']] = 0;
		}
	++$_SESSION['wpss_jscripts_referers_history_count_'.WPSS_HASH][$_SERVER['HTTP_REFERER']];
	// Last Referrer
	if ( empty( $_SESSION['wpss_jscripts_referer_last_'.WPSS_HASH] ) ) {
		$_SESSION['wpss_jscripts_referer_last_'.WPSS_HASH] = '';
		}
	$_SESSION['wpss_jscripts_referer_last_'.WPSS_HASH] = $_SERVER['HTTP_REFERER'];
	}
// IP, PAGE HITS, PAGES VISITED HISTORY - END

// AUTHOR, EMAIL, URL - BEGIN

// Keep history of Author, Author Email, and Author URL in case they keep changing
// This will expose patterns

// Comment Author
if ( empty( $_SESSION['wpss_author_history_'.WPSS_HASH] ) ) {
	$_SESSION['wpss_author_history_'.WPSS_HASH] = array();
	if ( !empty( $_COOKIE['comment_author_'.WPSS_HASH] ) ) {
		$_SESSION['comment_author_'.WPSS_HASH] = $_COOKIE['comment_author_'.WPSS_HASH];
		$_SESSION['wpss_author_history_'.WPSS_HASH][] = $_COOKIE['comment_author_'.WPSS_HASH];
		}
	}
else {
	if ( !empty( $_COOKIE['comment_author_'.WPSS_HASH] ) ) {
		$_SESSION['comment_author_'.WPSS_HASH] = $_COOKIE['comment_author_'.WPSS_HASH];
		}
	}
// Comment Author Email
if ( empty( $_SESSION['wpss_author_email_history_'.WPSS_HASH] ) ) {
	$_SESSION['wpss_author_email_history_'.WPSS_HASH] = array();
	if ( !empty( $_COOKIE['comment_author_email_'.WPSS_HASH] ) ) {
		$_SESSION['comment_author_email_'.WPSS_HASH] = $_COOKIE['comment_author_email_'.WPSS_HASH];
		$_SESSION['wpss_author_email_history_'.WPSS_HASH][] = $_COOKIE['comment_author_email_'.WPSS_HASH];
		}
	}
else {
	if ( !empty( $_COOKIE['comment_author_email_'.WPSS_HASH] ) ) {
		$_SESSION['comment_author_email_'.WPSS_HASH] = $_COOKIE['comment_author_email_'.WPSS_HASH];
		}
	}
// Comment Author URL
if ( empty( $_SESSION['wpss_author_url_history_'.WPSS_HASH] ) ) {
	$_SESSION['wpss_author_url_history_'.WPSS_HASH] = array();
	if ( !empty( $_COOKIE['comment_author_url_'.WPSS_HASH] ) ) {
		$_SESSION['comment_author_url_'.WPSS_HASH] = $_COOKIE['comment_author_url_'.WPSS_HASH];
		$_SESSION['wpss_author_url_history_'.WPSS_HASH][] = $_COOKIE['comment_author_url_'.WPSS_HASH];
		}
	}
else {
	if ( !empty( $_COOKIE['comment_author_url_'.WPSS_HASH] ) ) {
		$_SESSION['comment_author_url_'.WPSS_HASH] = $_COOKIE['comment_author_url_'.WPSS_HASH];
		}
	}
// AUTHOR, EMAIL, URL - END

function spamshield_microtime_js() {
	$mtime = microtime();
	$mtime = explode(' ',$mtime); 
   	$mtime = $mtime[1]+$mtime[0];
	return $mtime;
	}

function spamshield_get_url_js() {
	if ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) { $url = 'https://'; } else { $url = 'http://'; }
	$url .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	return $url;
	}

// Set Cookie & JS Values - BEGIN
$wpss_session_id = @session_id();
//$wpss_server_ip_nodot = preg_replace( "/\./", "", $_SERVER['SERVER_ADDR'] );

//CK
$wpss_ck_key_phrase 	= 'wpss_ckkey_'.$wpss_server_ip_nodot.'_'.$wpss_session_id;
$wpss_ck_val_phrase 	= 'wpss_ckval_'.$wpss_server_ip_nodot.'_'.$wpss_session_id;
$wpss_ck_key 			= hash( 'md5', $wpss_ck_key_phrase );
$wpss_ck_val 			= hash( 'md5', $wpss_ck_val_phrase );
//JS
/*
$wpss_js_key_phrase 	= 'wpss_jskey_'.$wpss_server_ip_nodot.'_'.$wpss_session_id;
$wpss_js_val_phrase 	= 'wpss_jsval_'.$wpss_server_ip_nodot.'_'.$wpss_session_id;
$wpss_js_key 			= hash( 'md5', $wpss_js_key_phrase );
$wpss_js_val 			= hash( 'md5', $wpss_js_val_phrase );
*/
// Set Cookie & JS Values - END

@setcookie( $wpss_ck_key, $wpss_ck_val, 0, '/' );
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Content-Type: application/x-javascript');
echo "
function GetCookie(e){var t=document.cookie.indexOf(e+'=');var n=t+e.length+1;if(!t&&e!=document.cookie.substring(0,e.length)){return null}if(t==-1)return null;var r=document.cookie.indexOf(';',n);if(r==-1)r=document.cookie.length;return unescape(document.cookie.substring(n,r))}function SetCookie(e,t,n,r,i,s){var o=new Date;o.setTime(o.getTime());if(n){n=n*1e3*60*60*24}var u=new Date(o.getTime()+n);document.cookie=e+'='+escape(t)+(n?';expires='+u.toGMTString():'')+(r?';path='+r:'')+(i?';domain='+i:'')+(s?';secure':'')}function DeleteCookie(e,t,n){if(getCookie(e))document.cookie=e+'='+(t?';path='+t:'')+(n?';domain='+n:'')+';expires=Thu, 01-Jan-1970 00:00:01 GMT'}
function commentValidation(){SetCookie('".$wpss_ck_key ."','".$wpss_ck_val ."','','/');SetCookie('SJECT14','CKON14','','/');}
commentValidation();
";

// Uncomment to use:
$wpss_js_end_time = spamshield_microtime_js();
$wpss_js_total_time = substr(($wpss_js_end_time - $wpss_js_start_time),0,8). " seconds";
echo "// Generated in: ".$wpss_js_total_time."\n";

?>