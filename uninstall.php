<?php
/*
WP-SpamShield - uninstall.php
Version: 1.9.4

This script uninstalls WP-SpamShield and removes all options and traces of its existence.
*/

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) { die(); }

function spamshield_uninstall_plugin() {
	/* Delete Options */
	$del_options = array( 'wp_spamshield_version', 'spamshield_options', 'spamshield_widget_settings', 'spamshield_last_admin', 'spamshield_admins', 'spamshield_admin_notices', 'spamshield_count', 'spamshield_reg_count', 'spamshield_procdat', 'spamshield_install_status', 'spamshield_warning_status', 'spamshield_regalert_status', 'spamshield_nonces', 'spamshield_wpssmid_cache', 'spamshield_ubl_cache', 'spamshield_ubl_cache_disable', 'spamshield_ip_ban_disable', 'spamshield_ip_ban', 'spamshield_whitelist_keys', 'ak_count_pre', 'spamshield_init_user_approve_run' );
	foreach( $del_options as $i => $option ) { delete_option( $option ); }
	/* Delete User Meta */
	$del_user_meta = array( 'wpss_user_ip', 'wpss_admin_status', 'wpss_new_user_approved', 'wpss_new_user_email_sent' );
	$user_ids = get_users( array( 'blog_id' => '', 'fields' => 'ID' ) );
	foreach ( $user_ids as $user_id ) { foreach( $del_user_meta as $i => $key ) { delete_user_meta( $user_id, $key ); } }
	}

spamshield_uninstall_plugin();

/* "Then it's time I disappear..." */
?>