<?php
/*
WP-SpamShield Dynamic JS File
Version: 1.7.4
*/

// Security Sanitization - BEGIN
$id='';
if ( !empty( $_GET ) || preg_match ( "~\?~", $_SERVER['REQUEST_URI'] ) ) {
	header('HTTP/1.1 403 Forbidden');
	die('ERROR: This resource will not function with a query string. Remove the query string from the URL and try again.');
	}
if ( !empty( $_SERVER['REQUEST_METHOD'] ) ) { $wpss_request_method = $_SERVER['REQUEST_METHOD']; } else { $wpss_request_method = getenv('REQUEST_METHOD'); }
if ( empty( $wpss_request_method ) ) { $wpss_request_method = ''; }
if ( !empty( $_POST ) || preg_match( "~^(POST|TRACE|TRACK|DEBUG|DELETE)$~", $wpss_request_method ) ) {
	header('HTTP/1.1 405 Method Not Allowed');
	die('ERROR: This resource does not accept requests of that type.');
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

if ( !defined( 'RSMP_SERVER_IP_NODOT' ) ) {
	$wpss_server_ip_nodot = str_replace( '.', '', spamshield_get_server_addr_js() );
	define( 'RSMP_SERVER_IP_NODOT', $wpss_server_ip_nodot );
	}
if ( !defined( 'RSMP_HASH_ALT' ) ) { $wpss_alt_prefix = spamshield_md5_js( RSMP_SERVER_IP_NODOT ); define( 'RSMP_HASH_ALT', $wpss_alt_prefix ); }
if ( !defined( 'RSMP_SITE_URL' ) && !empty( $_SESSION['wpss_site_url_'.RSMP_HASH_ALT] ) ) {
	$wpss_site_url 		= $_SESSION['wpss_site_url_'.RSMP_HASH_ALT];
	$wpss_plugin_url	= $_SESSION['wpss_plugin_url_'.RSMP_HASH_ALT];
	define( 'RSMP_SITE_URL', $wpss_site_url );
	}

if ( defined( 'RSMP_SITE_URL' ) && !defined( 'RSMP_HASH' ) ) {
	$wpss_hash_prefix = spamshield_md5_js( RSMP_SITE_URL ); define( 'RSMP_HASH', $wpss_hash_prefix );
	}
elseif ( !empty( $_SESSION ) && !empty( $_COOKIE ) && !defined( 'RSMP_HASH' ) ) {
	//$wpss_cookies = $_COOKIE;
	foreach( $_COOKIE as $ck_name => $ck_val ) {
		if ( preg_match( "~^comment_author_([a-z0-9]{32})$~i", $ck_name, $matches ) ) { define( 'RSMP_HASH', $matches[1] ); break; }
		}
	}

$wpss_lang_ck_key = 'UBR_LANG';
$wpss_lang_ck_val = 'default';

// SESSION CHECK AND FUNCTIONS - END

if ( defined( 'RSMP_HASH' ) && !empty( $_SESSION )  ) {
	// IP, PAGE HITS, PAGES VISITED HISTORY - BEGIN
	// Initial IP Address when visitor first comes to site
	$key_pages_hist 		= 'wpss_jscripts_referers_history_'.RSMP_HASH;
	$key_hits_per_page		= 'wpss_jscripts_referers_history_count_'.RSMP_HASH;
	$key_total_page_hits	= 'wpss_page_hits_js_'.RSMP_HASH;
	$key_ip_hist 			= 'wpss_jscripts_ip_history_'.RSMP_HASH;
	$key_init_ip			= 'wpss_user_ip_init_'.RSMP_HASH;
	$key_init_ua			= 'wpss_user_agent_init_'.RSMP_HASH;
	$key_init_mt			= 'wpss_time_init_'.RSMP_HASH;
	$key_init_dt			= 'wpss_timestamp_init_'.RSMP_HASH;
	$ck_key_init_dt			= 'NCS_INENTIM'; //Initial Entry Time
	$current_ip 			= $_SERVER['REMOTE_ADDR'];
	$current_ua 			= spamshield_get_user_agent_js();
	$current_mt 			= spamshield_microtime_js(); // Site entry time - microtime
	$current_dt 			= time(); // Site entry time - timestamp
	if ( empty( $_SESSION[$key_init_ip] ) ) { $_SESSION[$key_init_ip] = $current_ip; }
	if ( empty( $_SESSION[$key_init_ua] ) ) { $_SESSION[$key_init_ua] = $current_ua; }
	if ( empty( $_SESSION[$key_init_mt] ) ) { $_SESSION[$key_init_mt] = $current_mt; }
	if ( empty( $_SESSION[$key_init_dt] ) ) { $_SESSION[$key_init_dt] = $current_dt; }
	// Set Cookie
	if ( empty( $_COOKIE[$ck_key_init_dt] ) ) { @setcookie( $ck_key_init_dt, $current_dt, time()+60*60, '/' ); } // 1 hour
	// IP History - Lets see if they change IP's
	if ( empty( $_SESSION[$key_ip_hist] ) ) { $_SESSION[$key_ip_hist] = array(); $_SESSION[$key_ip_hist][] = $current_ip; }
	if ( $current_ip != $_SESSION[$key_init_ip] ) { $_SESSION[$key_ip_hist][] = $current_ip; }
	//Page hits - this page is more reliable than main if caching is on, so we'll keep a separate count
	if ( empty( $_SESSION[$key_total_page_hits] ) ) { $_SESSION[$key_total_page_hits] = 0; }
	++$_SESSION[$key_total_page_hits];
	// Referrer History - More reliable way to keep a list of pages, than using main
	if ( empty( $_SESSION[$key_pages_hist] ) ) { $_SESSION[$key_pages_hist] = array(); }
	if ( empty( $_SESSION[$key_hits_per_page] ) ) { $_SESSION[$key_hits_per_page] = array(); }
	if ( !empty( $_SERVER['HTTP_REFERER'] ) ) {
		$current_ref 	= $_SERVER['HTTP_REFERER'];
		$key_first_ref	= 'wpss_referer_init_'.RSMP_HASH;
		$key_last_ref	= 'wpss_jscripts_referer_last_'.RSMP_HASH;
		if ( !array_key_exists( $current_ref, $_SESSION[$key_pages_hist] ) ) {
			$_SESSION[$key_pages_hist][] = $current_ref;
			}
		if ( !array_key_exists( $current_ref, $_SESSION[$key_hits_per_page] ) ) {
			$_SESSION[$key_hits_per_page][$current_ref] = 1;
			}
		++$_SESSION[$key_hits_per_page][$current_ref];
		// First Referrer - Where Visitor Entered Site
		if ( empty( $_SESSION[$key_first_ref] ) ) { $_SESSION[$key_first_ref] = $current_ref; }
		// Last Referrer
		if ( empty( $_SESSION[$key_last_ref] ) ) {
			$_SESSION[$key_last_ref] = '';
			}
		$_SESSION[$key_last_ref] = $current_ref;
		}
	// IP, PAGE HITS, PAGES VISITED HISTORY - END

	// AUTHOR, EMAIL, URL HISTORY - BEGIN

	// Keep history of Author, Author Email, and Author URL in case they keep changing
	// This will expose spammer behavior patterns

	// Comment Author
	$key_auth_hist 		= 'wpss_author_history_'.RSMP_HASH;
	$key_comment_auth 	= 'comment_author_'.RSMP_HASH;
	if ( empty( $_SESSION[$key_auth_hist] ) ) {
		$_SESSION[$key_auth_hist] = array();
		if ( !empty( $_COOKIE[$key_comment_auth] ) ) {
			$_SESSION[$key_comment_auth] 	= $_COOKIE[$key_comment_auth];
			$_SESSION[$key_auth_hist][] 	= $_COOKIE[$key_comment_auth];
			}
		}
	else {
		if ( !empty( $_COOKIE[$key_comment_auth] ) ) {
			$_SESSION[$key_comment_auth] = $_COOKIE[$key_comment_auth];
			}
		}
	// Comment Author Email
	$key_email_hist 	= 'wpss_author_email_history_'.RSMP_HASH;
	$key_comment_email	= 'comment_author_email_'.RSMP_HASH;
	if ( empty( $_SESSION[$key_email_hist] ) ) {
		$_SESSION[$key_email_hist] = array();
		if ( !empty( $_COOKIE[$key_comment_email] ) ) {
			$_SESSION[$key_comment_email] 	= $_COOKIE[$key_comment_email];
			$_SESSION[$key_email_hist][] 	= $_COOKIE[$key_comment_email];
			}
		}
	else {
		if ( !empty( $_COOKIE[$key_comment_email] ) ) { $_SESSION[$key_comment_email] = $_COOKIE[$key_comment_email]; }
		}
	// Comment Author URL
	$key_auth_url_hist 	= 'wpss_author_url_history_'.RSMP_HASH;
	$key_comment_url	= 'comment_author_url_'.RSMP_HASH;
	if ( empty( $_SESSION[$key_auth_url_hist] ) ) {
		$_SESSION[$key_auth_url_hist] = array();
		if ( !empty( $_COOKIE[$key_comment_url] ) ) {
			$_SESSION[$key_comment_url] = $_COOKIE[$key_comment_url];
			$_SESSION[$key_auth_url_hist][] = $_COOKIE[$key_comment_url];
			}
		}
	else { 
		if ( !empty( $_COOKIE[$key_comment_url] ) ) { $_SESSION[$key_comment_url] = $_COOKIE[$key_comment_url]; }
		}
	// AUTHOR, EMAIL, URL HISTORY - END
	
	// SESSION USER BLACKLIST CHECK - BEGIN
	if ( !empty( $_SESSION['wpss_blacklisted_user_'.RSMP_HASH] ) && empty( $_COOKIE[$wpss_lang_ck_key] ) ) {
		// Set Blacklisted User Cookie = true
		$wpss_sbluck = true;
		}
	elseif ( !empty( $_COOKIE[$wpss_lang_ck_key] ) && $_COOKIE[$wpss_lang_ck_key] == $wpss_lang_ck_val ) {
		$_SESSION['wpss_blacklisted_user_'.RSMP_HASH] = true;
		}
	// SESSION USER BLACKLIST CHECK - END
	}

// STANDARD FUNCTIONS - BEGIN
function spamshield_md5_js( $string ) {
	// Use this function instead of hash for compatibility
	// BUT hash is faster than md5, so use it whenever possible
	if ( function_exists( 'hash' ) ) { $hash = hash( 'md5', $string ); } else { $hash = md5( $string );	}
	return $hash;
	}
function spamshield_microtime_js() {
	$mtime = microtime( true );
	return $mtime;
	}
function spamshield_timer_js( $start = NULL, $end = NULL, $show_seconds = false, $precision = 8 ) {
	if ( empty( $start ) || empty( $end ) ) { $start = 0; $end = 0; }
	// $precision will default to 8 but can be set to anything - 1,2,3,5,etc.
	$total_time = $end - $start;
	$total_time_for = number_format( $total_time, $precision );
	if ( !empty( $show_seconds ) ) { $total_time_for .= ' seconds'; }
	return $total_time_for;
	}
function spamshield_get_url_js() {
	if ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) { $url = 'https://'; } else { $url = 'http://'; }
	$url .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	return $url;
	}
function spamshield_get_user_agent_js() {
	if ( !empty( $_SERVER['HTTP_USER_AGENT'] ) ) { $user_agent = trim(addslashes(strip_tags($_SERVER['HTTP_USER_AGENT']))); } else { $user_agent = ''; }
	return $user_agent;
	}
function spamshield_get_server_addr_js() {
	if ( !empty( $_SERVER['SERVER_ADDR'] ) ) { $server_addr = $_SERVER['SERVER_ADDR']; } else { $server_addr = getenv('SERVER_ADDR'); }
	return $server_addr;
	}
// STANDARD FUNCTIONS - END

// SET COOKIE VALUES - BEGIN
$wpss_session_id = @session_id();
$wpss_ck_key_phrase 	= 'wpss_ckkey_'.RSMP_SERVER_IP_NODOT.'_'.$wpss_session_id;
$wpss_ck_val_phrase 	= 'wpss_ckval_'.RSMP_SERVER_IP_NODOT.'_'.$wpss_session_id;
$wpss_ck_key 			= spamshield_md5_js( $wpss_ck_key_phrase );
$wpss_ck_val 			= spamshield_md5_js( $wpss_ck_val_phrase );
// SET COOKIE VALUES - END

// Last thing before headers sent
$_SESSION['wpss_sess_status'] = 'on';

if ( !empty( $current_ref ) && preg_match( "~([&\?])form\=response$~i", $current_ref ) && !empty( $_SESSION[$key_comment_auth] ) ) {
	@setcookie( $key_comment_auth, $_SESSION[$key_comment_auth], 0, '/' );
	if ( !empty( $_SESSION[$key_comment_email] ) )	{ @setcookie( $key_comment_email, $_SESSION[$key_comment_email], 0, '/' ); }
	if ( !empty( $_SESSION[$key_comment_url] ) ) 	{ @setcookie( $key_comment_url, $_SESSION[$key_comment_url], 0, '/' ); }
	}
if ( !empty( $wpss_sbluck ) ) {
	@setcookie( $wpss_lang_ck_key, $wpss_lang_ck_val, time()+60*60*24*365*10, '/' );
	}
@setcookie( $wpss_ck_key, $wpss_ck_val, 0, '/' );
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Content-Type: application/x-javascript');
echo "
function wpssGetCookie(e){var t=document.cookie.indexOf(e+'=');var n=t+e.length+1;if(!t&&e!=document.cookie.substring(0,e.length)){return null}if(t==-1)return null;var r=document.cookie.indexOf(';',n);if(r==-1)r=document.cookie.length;return unescape(document.cookie.substring(n,r))}function wpssSetCookie(e,t,n,r,i,s){var o=new Date;o.setTime(o.getTime());if(n){n=n*1e3*60*60*24}var u=new Date(o.getTime()+n);document.cookie=e+'='+escape(t)+(n?';expires='+u.toGMTString():'')+(r?';path='+r:'')+(i?';domain='+i:'')+(s?';secure':'')}function wpssDeleteCookie(e,t,n){if(wpssGetCookie(e))document.cookie=e+'='+(t?';path='+t:'')+(n?';domain='+n:'')+';expires=Thu, 01-Jan-1970 00:00:01 GMT'}
function wpssCommentVal(){wpssSetCookie('".$wpss_ck_key ."','".$wpss_ck_val ."','','/');wpssSetCookie('SJECT15','CKON15','','/');}
wpssCommentVal();
";

// Uncomment to use:
$wpss_js_end_time = spamshield_microtime_js();
$wpss_js_total_time = spamshield_timer_js( $wpss_js_start_time, $wpss_js_end_time, true, 6 );
echo "// Generated in: ".$wpss_js_total_time."\n";
?>