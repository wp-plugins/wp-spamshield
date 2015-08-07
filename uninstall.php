<?php
/*
WP-SpamShield - uninstall.php
Version: 1.9.5.5

This script uninstalls WP-SpamShield and removes all options and traces of its existence.
*/

if( !defined( 'ABSPATH' ) || !defined( 'WP_UNINSTALL_PLUGIN' ) ) { die(); }

if( !defined( 'WPSS_DEBUG' ) )	 				{ define( 'WPSS_DEBUG', FALSE ); }
if( !defined( 'RSMP_CONTENT_DIR_PATH' ) ) 		{ define( 'RSMP_CONTENT_DIR_PATH', WP_CONTENT_DIR ); }
if( !defined( 'WPSS_PLUGIN_PATH' ) ) 			{ define( 'WPSS_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) ); }

function rs_wpss_uninstall_plugin() {
	/* Delete Options */
	$del_options = array( 'wp_spamshield_version', 'spamshield_options', 'spamshield_widget_settings', 'spamshield_last_admin', 'spamshield_admins', 'spamshield_admin_notices', 'spamshield_count', 'spamshield_reg_count', 'spamshield_procdat', 'spamshield_install_status', 'spamshield_warning_status', 'spamshield_regalert_status', 'spamshield_nonces', 'spamshield_wpssmid_cache', 'spamshield_ubl_cache', 'spamshield_ubl_cache_disable', 'spamshield_ip_ban_disable', 'spamshield_ip_ban', 'spamshield_whitelist_keys', 'ak_count_pre', 'spamshield_init_user_approve_run' );
	foreach( $del_options as $i => $option ) { delete_option( $option ); }
	/* Unregister Widgets */
	$unreg_widgets = array( 'WP_SpamShield_Counter_LG', 'WP_SpamShield_Counter_CG', 'WP_SpamShield_End_Blog_Spam' );
	foreach( $unreg_widgets as $i => $widget ) { unregister_widget( $widget ); }
	/* Clean Up Widget Options */
	$all_widgets = get_option('sidebars_widgets');
	foreach( $all_widgets as $i => $s ) {
		if( is_array( $s ) ) {
			foreach( $s as $k => $v ) {
				if( FALSE !== strpos( $v, 'spamshield' ) ) { unset( $all_widgets[$i][$k] ); }
				}
			$all_widgets[$i] = array_values( $all_widgets[$i] );
			}
		}
	update_option( 'sidebars_widgets', $all_widgets );
	/* Delete Orphaned Options */
	$all_options = wp_load_alloptions();
	foreach( $all_options  as $option => $value ) { 
		if( FALSE !== strpos( $option, 'spamshield' ) ) { delete_option( $option ); }
		}
	/* Delete User Meta */
	$del_user_meta = array( 'wpss_user_ip', 'wpss_admin_status', 'wpss_new_user_approved', 'wpss_new_user_email_sent', 'wpss_nag_status', 'wpss_nag_notices' );
	$user_ids = get_users( array( 'blog_id' => '', 'fields' => 'ID' ) );
	foreach ( $user_ids as $user_id ) { foreach( $del_user_meta as $i => $key ) { delete_user_meta( $user_id, $key ); } }
	/* Clear Banned IP Info */
	rs_wpss_uninstall_ip_ban_htaccess();
	}

function rs_wpss_uninstall_ip_ban_htaccess() {
	/***
	* Clear banned IP info from .htaccess during uninstall.
	***/
	$hta_bak_dir		= RSMP_CONTENT_DIR_PATH.'/backup';
	$hta_wpss_bak_dir	= $hta_bak_dir.'/wp-spamshield';
	$hta_file			= ABSPATH.'/.htaccess';
	$hta_bak_file		= $hta_wpss_bak_dir.'/original.htaccess';
	$wpss_index_file	= WPSS_PLUGIN_PATH.'/index.php';
	$bak_dir_hta_file	= WPSS_PLUGIN_PATH.'/lib/sec/.htaccess';

	$wpss_dirs			= array( $hta_wpss_bak_dir.'/' );

	foreach( $wpss_dirs as $d => $dir ) {
		if( is_dir( $wpss_dirs[$d] ) ) {
			$filelist = rs_wpss_scandir( $wpss_dirs[$d] );
			foreach( $filelist as $f => $filename ) {
				$file = $wpss_dirs[$d].$filename;
				if( is_file( $file ) ){
					@chmod( $file, 0775 ); @unlink( $file );
					if( file_exists( $file ) ) { @chmod( $file, 0644 ); @unlink( $file ); }
					}
				}
			@chmod( $wpss_dirs[$d], 0775 ); @rmdir( $wpss_dirs[$d] );
			if( file_exists( $wpss_dirs[$d] ) ) { @chmod( $wpss_dirs[$d], 0755 ); @rmdir( $wpss_dirs[$d] ); }
			}
		}

	$wpss_files = array( $hta_bak_dir.'/.htaccess', $hta_bak_dir.'/index.php' );

	foreach( $wpss_files as $f => $file ) {
		if( is_file( $file ) ){
			@chmod( $file, 0775 ); @unlink( $file );
			if( file_exists( $file ) ) { @chmod( $file, 0644 ); @unlink( $file ); }
			}
		}

	$hta_contents = file_get_contents( $hta_file );
	if( FALSE !== strpos( $hta_contents, '# BEGIN WP-SpamShield' ) && FALSE !== strpos( $hta_contents, '# END WP-SpamShield' ) ) {
		$hta_contents_mod = preg_replace( "~".PHP_EOL."#\ BEGIN\ WP-SpamShield[\w\W]+#\ END\ WP-SpamShield".PHP_EOL."~i", '', $hta_contents );
		if( $hta_contents_mod !== $hta_contents ) {
			file_put_contents( $hta_file, $hta_contents_mod, LOCK_EX );
			}
		}
	}

function rs_wpss_scandir( $dir ) {
	clearstatcache();
	$dot_files = array( '..', '.' );
	$dir_contents_raw = scandir( $dir );
	$dir_contents = array_values( array_diff( $dir_contents_raw, $dot_files ) );
	return $dir_contents;
	}

function rs_wpss_un_append_log_data( $str = NULL, $rsds_only = FALSE ) {
	if ( TRUE === WP_DEBUG && TRUE === WPSS_DEBUG ) {
		$wpss_log_str = 'WP-SpamShield UN DEBUG: ';
		error_log( $wpss_log_str, 0 );
		}
	}

rs_wpss_uninstall_plugin();

/* "Then it's time I disappear..." */
