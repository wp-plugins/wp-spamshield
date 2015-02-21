<?php
/*
WP-SpamShield - uninstall.php
Version: 1.7.7

This script uninstalls WP-SpamShield and removes all options and traces of its existence.
*/

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit(); }

function spamshield_uninstall_plugin() {
	// Options to Delete
	$wpss_option_names = array( 'wp_spamshield_version', 'spamshield_options', 'spamshield_last_admin', 'spamshield_admin_notices', 'spamshield_count', 'spamshield_reg_count', 'spamshield_procdat', 'spamshield_wpssmid_cache', 'spamshield_whitelist_keys', 'ak_count_pre' );
	foreach( $wpss_option_names as $i => $wpss_option ) {
		delete_option( $wpss_option );
		}
	}

spamshield_uninstall_plugin();

// And now I disappear...
?>