<?php
/*
WP-SpamShield - index.php
Version: 1.7
*/

// This page keeps search engines, bots, and unwanted visitors from viewing your private plugin directory contents.

/* 
You can avoid the need for pages like this by adding a single line of code to the beginning of your .htaccess file:
	## Add the following line to the beginning of your .htaccess for security and SEO.
	Options All -Indexes
	## This will turn off indexes so your site won't reveal contents of directories that don't have an index file.
*/

error_reporting(0);

// We're going to redirect bots and human visitors to the website root.
$new_url = spamshield_get_site_url();
header( 'Location: '.$new_url, true, 301 );

function spamshield_get_site_url() {
	if ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) { $url = 'https://'; } else { $url = 'http://'; }
	$url .= spamshield_get_server_name();
	return $url;
	}

function spamshield_get_server_name() {
	if ( !empty( $_SERVER['SERVER_NAME'] ) ) { $server_name = strtolower( $_SERVER['SERVER_NAME'] ); } else { $server_name = strtolower( getenv('SERVER_NAME') ); }
	return $server_name;
	}

?>