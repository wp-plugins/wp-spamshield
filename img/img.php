<?php
// WP-SpamShield IMG File
// Updated in Version 1.1.4

// Security Sanitization - BEGIN
$id='';
if ( $_GET || preg_match ( "/\?/", $_SERVER['REQUEST_URI'] ) ) {
	header('HTTP/1.1 403 Forbidden');
	die('ERROR: This file will not function with a query string. Remove the query string from the URL and try again.');
	}
if ( $_POST || $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	header('HTTP/1.1 405 Method Not Allowed');
	die('ERROR: This file does not accept POST requests.');
	}
// Security Sanitization - END

function spamshield_create_random_key_img() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    srand((double)microtime()*1000000);
	$keyCode = '';
    $i = 0;
    $pass = '';
    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $keyCode = $keyCode . $tmp;
        $i++;
    	}
	if ($keyCode=='') {
		srand((double)74839201183*1000000);
    	$i = 0;
    	$pass = '';
    	while ($i <= 7) {
        	$num = rand() % 33;
        	$tmp = substr($chars, $num, 1);
        	$keyCode = $keyCode . $tmp;
        	$i++;
    		}
		}
    return $keyCode;
	}

$wpss_session_test = @session_id();
if( empty($wpss_session_test) && !headers_sent() ) {
	@session_start();
	global $wpss_session_id;
	$wpss_session_id = @session_id();
	}
$spider_status_check = '';
$spider_status_check_img = '';
if ( isset($_SESSION['wpSpamShieldVer']) && isset($_SESSION['CookieValidationName']) && isset($_SESSION['CookieValidationKey']) ) {
	$wpSpamShieldVer			= $_SESSION['wpSpamShieldVer'];
	$CookieValidationName 		= $_SESSION['CookieValidationName'];
	$CookieValidationKey 		= $_SESSION['CookieValidationKey'];
	}
elseif ( isset($session_wpSpamShieldVer) && isset($session_CookieValidationName) && isset($session_CookieValidationKey) ) {
	$wpSpamShieldVer			= $session_wpSpamShieldVer;
	$CookieValidationName  		= $session_CookieValidationName;
	$CookieValidationKey 		= $session_CookieValidationKey;
	}
else {
	if ( isset($_SESSION['spider_status_check']) ) {
		$spider_status_check = $_SESSION['spider_status_check'];
		}
	elseif ( $_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR'] ) {
		$spider_status_check_img = 1;
		}
	else {
		$spider_status_check_img = 0;
		$wpss_spiders_array_img = array( 'googlebot', 'google.com', 'googleproducer', 'feedfetcher-google', 'google wireless transcoder', 'google favicon', 'mediapartners-google', 'adsbot-google', 'yahoo', 'slurp', 'msnbot', 'bingbot', 'gtmetrix', 'wordpress', 'twitterfeed', 'feedburner', 'ia_archiver', 'spider', 'crawler', 'search', 'bot', 'offline', 'download', 'validator', 'link', 'user-agent:', 'curl', 'httpclient', 'jakarta', 'java/', 'larbin', 'libwww', 'lwp-trivial', 'mechanize', 'nutch', 'parser', 'php/', 'python-urllib ', 'wget', 'snoopy', 'binget', 'lftp/', '!susie', 'arachmo', 'automate', 'cerberian', 'charlotte', 'cocoal.icio.us', 'copier', 'cosmos', 'covario', 'csscheck', 'cynthia', 'emailsiphon', 'extractor', 'ezooms', 'feedly', 'getright', 'heritrix', 'holmes', 'htdig', 'htmlparser', 'httrack', 'igdespyder', 'internetseer', 'itunes', 'l.webis', 'mabontland', 'magpie', 'metauri', 'mogimogi', 'morning paper', 'mvaclient', 'newsgator', 'nymesis', 'oegp', 'peach', 'pompos', 'pxyscand', 'qseero', 'reaper', 'sbider', 'scoutjet', 'scrubby', 'semanticdiscovery', 'snagger', 'silk', 'snappy', 'sqworm', 'stackrambler', 'stripper', 'sucker', 'teoma', 'truwogps', 'updated', 'vyu2', 'webcapture', 'webcopier', 'webzip', 'windows-media-player', 'yeti' );
		$wpss_spiders_array_img_count = count($wpss_spiders_array_img);
		// the User Agent
		$user_agent_lc = strtolower($_SERVER['HTTP_USER_AGENT']);
		$i = 0;
		while ($i < $wpss_spiders_array_img_count) {
			if ( strpos( $user_agent_lc, $wpss_spiders_array_img[$i] ) !== false ) {
				$spider_status_check_img = 1;
				break;
				}
			$i++;
			}
		$_SESSION['spider_status_check_img'] = $spider_status_check_img;
		}
	if ( $spider_status_check_img != 1 && $spider_status_check != 1 ) {
		global $root, $wpSpamShieldVer, $spamshield_options, $CookieValidationName, $CookieValidationKey;
		$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
		if (file_exists($root.'/wp-load.php')) {
			// WP 2.6
			include_once($root.'/wp-load.php');
			} else {
			// Before 2.6
			include_once($root.'/wp-config.php');
			}
		$wpSpamShieldVer			= get_option('wp_spamshield_version');
		$spamshield_options			= get_option('spamshield_options');
		$CookieValidationName  		= $spamshield_options['cookie_validation_name'];
		$CookieValidationKey 		= $spamshield_options['cookie_validation_key'];
		}
	else {
		$wpSpamShieldVer			= '1.0.0.0';
		$randomComValCodeCVN1 		= spamshield_create_random_key_img();
		$randomComValCodeCVN2 		= spamshield_create_random_key_img();
		$randomComValCodeCVN3 		= spamshield_create_random_key_img();
		$randomComValCodeCVN4 		= spamshield_create_random_key_img();
		//$randomComValCodeCVN5 	= spamshield_create_random_key_img();
		//$randomComValCodeCVN6 	= spamshield_create_random_key_img();
		//$randomComValCodeCVN7 	= spamshield_create_random_key_img();
		//$randomComValCodeCVN8 	= spamshield_create_random_key_img();
		$CookieValidationName 		= $randomComValCodeCVN1.$randomComValCodeCVN2;
		$CookieValidationKey  		= $randomComValCodeCVN3.$randomComValCodeCVN4;
		//$CookieValidationNameJS	= $randomComValCodeCVN5.$randomComValCodeCVN6;
		//$CookieValidationKeyJS 	= $randomComValCodeCVN7.$randomComValCodeCVN8;
		}
	}
@setcookie( $CookieValidationName, $CookieValidationKey, 0, '/' );
	
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('HTTP/1.1 200 OK');
header('Content-Type: image/gif');

include('spacer.gif');

?>