<?php
/* 
WP-SpamShield Advanced Features
Version 1.9.1
*/

if ( !defined( 'ABSPATH' ) ) {
	if ( !headers_sent() ) { header('HTTP/1.1 403 Forbidden'); }
	die('ERROR: Direct access to this file is not allowed.');
	}

if ( !defined( 'WPSS_COMPAT_MODE' ) ) {
	define( 'WPSS_COMPAT_MODE', FALSE );
	}
if ( !defined( 'WPSS_TEMP_BL_DISABLE' ) ) {
	define( 'WPSS_TEMP_BL_DISABLE', FALSE );
	}
if ( !defined( 'WPSS_TEMP_BL_CF_ONLY' ) ) {
	define( 'WPSS_TEMP_BL_CF_ONLY', FALSE );
	}
