<?php
// Updated in Version 1.1.0.0

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

function wpss_microtime_js() {
	$mtime = microtime();
	$mtime = explode(' ',$mtime); 
   	$mtime = $mtime[1]+$mtime[0];
	return $mtime;
	}
	
function wpss_create_random_key_js() {
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

// Uncomment to use:
//$wpss_js_start_time = wpss_microtime_js();

$wpss_session_test = session_id();
if(empty($wpss_session_test)) {
	session_start();
	global $wpss_session_id;
	$wpss_session_id = session_id();
	}
if ( $_SESSION['wpSpamShieldVer'] && $_SESSION['CookieValidationName'] && $_SESSION['CookieValidationKey'] ) {
	$wpSpamShieldVer			= $_SESSION['wpSpamShieldVer'];
	$CookieValidationName 		= $_SESSION['CookieValidationName'];
	$CookieValidationKey 		= $_SESSION['CookieValidationKey'];
	}
elseif ( $session_wpSpamShieldVer && $session_CookieValidationName && $session_CookieValidationKey ) {
	$wpSpamShieldVer			= $session_wpSpamShieldVer;
	$CookieValidationName  		= $session_CookieValidationName;
	$CookieValidationKey 		= $session_CookieValidationKey;
	}
else {
	if ( $_SESSION['spider_status_check'] ) {
		$spider_status_check = $_SESSION['spider_status_check'];
		}
	elseif ( $_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR'] ) {
		$spider_status_check_js = 1;
		}
	else {
		$spider_status_check_js = 0;
		$wpss_spiders_array_js = array( 'googlebot', 'google.com', 'googleproducer', 'feedfetcher-google', 'google wireless transcoder', 'google favicon', 'mediapartners-google', 'adsbot-google', 'yahoo', 'slurp', 'msnbot', 'bingbot', 'gtmetrix', 'wordpress', 'twitterfeed', 'feedburner', 'ia_archiver', 'spider', 'crawler', 'search', 'bot', 'offline', 'download', 'validator', 'link', 'user-agent:', 'curl', 'httpclient', 'jakarta', 'java/', 'larbin', 'libwww', 'lwp-trivial', 'mechanize', 'nutch', 'parser', 'php/', 'python-urllib ', 'wget', 'snoopy', 'binget', 'lftp/', '!susie', 'arachmo', 'automate', 'cerberian', 'charlotte', 'cocoal.icio.us', 'copier', 'cosmos', 'covario', 'csscheck', 'cynthia', 'emailsiphon', 'extractor', 'ezooms', 'feedly', 'getright', 'heritrix', 'holmes', 'htdig', 'htmlparser', 'httrack', 'igdespyder', 'internetseer', 'itunes', 'l.webis', 'mabontland', 'magpie', 'metauri', 'mogimogi', 'morning paper', 'mvaclient', 'newsgator', 'nymesis', 'oegp', 'peach', 'pompos', 'pxyscand', 'qseero', 'reaper', 'sbider', 'scoutjet', 'scrubby', 'semanticdiscovery', 'snagger', 'silk', 'snappy', 'sqworm', 'stackrambler', 'stripper', 'sucker', 'teoma', 'truwogps', 'updated', 'vyu2', 'webcapture', 'webcopier', 'webzip', 'windows-media-player', 'yeti' );
		$wpss_spiders_array_js_count = count($wpss_spiders_array_js);
		// the User Agent
		$user_agent_lc = strtolower($_SERVER['HTTP_USER_AGENT']);
		$i = 0;
		while ($i < $wpss_spiders_array_js_count) {
			if ( strpos( $user_agent_lc, $wpss_spiders_array_js[$i] ) !== false ) {
				$spider_status_check_js = 1;
				break;
				}
			$i++;
			}
		$_SESSION['spider_status_check_js'] = $spider_status_check_js;
		}
	if ( !$spider_status_check_js && !$spider_status_check ) {
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
		$randomComValCodeCVN1 		= wpss_create_random_key_js();
		$randomComValCodeCVN2 		= wpss_create_random_key_js();
		$randomComValCodeCVN3 		= wpss_create_random_key_js();
		$randomComValCodeCVN4 		= wpss_create_random_key_js();
		//$randomComValCodeCVN5 	= wpss_create_random_key_js();
		//$randomComValCodeCVN6 	= wpss_create_random_key_js();
		//$randomComValCodeCVN7 	= wpss_create_random_key_js();
		//$randomComValCodeCVN8 	= wpss_create_random_key_js();
		$CookieValidationName 		= $randomComValCodeCVN1.$randomComValCodeCVN2;
		$CookieValidationKey  		= $randomComValCodeCVN3.$randomComValCodeCVN4;
		//$CookieValidationNameJS	= $randomComValCodeCVN5.$randomComValCodeCVN6;
		//$CookieValidationKeyJS 	= $randomComValCodeCVN7.$randomComValCodeCVN8;
		}
	}
@setcookie( $CookieValidationName, $CookieValidationKey, 0, '/' );
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Content-Type: application/x-javascript');
echo "
function GetCookie(e){var t=document.cookie.indexOf(e+'=');var n=t+e.length+1;if(!t&&e!=document.cookie.substring(0,e.length)){return null}if(t==-1)return null;var r=document.cookie.indexOf(';',n);if(r==-1)r=document.cookie.length;return unescape(document.cookie.substring(n,r))}function SetCookie(e,t,n,r,i,s){var o=new Date;o.setTime(o.getTime());if(n){n=n*1e3*60*60*24}var u=new Date(o.getTime()+n);document.cookie=e+'='+escape(t)+(n?';expires='+u.toGMTString():'')+(r?';path='+r:'')+(i?';domain='+i:'')+(s?';secure':'')}function DeleteCookie(e,t,n){if(getCookie(e))document.cookie=e+'='+(t?';path='+t:'')+(n?';domain='+n:'')+';expires=Thu, 01-Jan-1970 00:00:01 GMT'}
function commentValidation(){SetCookie('".$CookieValidationName."','".$CookieValidationKey."','','/');SetCookie('SJECT14','CKON14','','/');}
commentValidation();
";
// Uncomment to use:
//$wpss_js_end_time = wpss_microtime_js();
//$wpss_js_total_time = substr(($wpss_js_end_time - $wpss_js_start_time),0,8). " seconds";
//echo "// Benchmark: ".$wpss_js_total_time."\n";

?>