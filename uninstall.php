<?php
/*
WP-SpamShield - uninstall.php
Version: 1.8.4

This script uninstalls WP-SpamShield and removes all options and traces of its existence.
*/

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit(); }

function spamshield_uninstall_plugin() {
	// Options to Delete
	$del_options = array( 'wp_spamshield_version', 'spamshield_options', 'spamshield_widget_settings', 'spamshield_last_admin', 'spamshield_admin_notices', 'spamshield_count', 'spamshield_reg_count', 'spamshield_procdat', 'spamshield_install_status', 'spamshield_warning_status', 'spamshield_regalert_status', 'spamshield_nonces', 'spamshield_ubl_cache', 'spamshield_wpssmid_cache', 'spamshield_whitelist_keys', 'ak_count_pre' );
	foreach( $del_options as $i => $option ) { delete_option( $option ); }
	}

spamshield_uninstall_plugin();

// "Then it's time I disappear..."
?>