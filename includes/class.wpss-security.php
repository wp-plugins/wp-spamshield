<?php
/***
* WP-SpamShield Security
* Ver 1.9.5.5
***/

if ( !defined( 'ABSPATH' ) ) {
	if ( !headers_sent() ) { header('HTTP/1.1 403 Forbidden'); }
	die('ERROR: Direct access to this file is not allowed.');
	}

class WPSS_Security {

	function __construct() {
		/***
		* Run initial processes when class is instantiated
		* TO DO: Add processes
		***/
		$sec_test_var = 1;
		}

	public function check_post_sec() {
		/***
		* Check if POST submission is security threat: hack attempt or vulnerability probe
		***/

		$site_url	= RSMP_SITE_URL;
		$site_dom	= RSMP_SITE_DOMAIN;
		$admin_url	= RSMP_ADMIN_URL.'/';
		$cont_url	= RSMP_CONTENT_DIR_URL.'/';
		$plug_url	= RSMP_PLUGINS_DIR_URL.'/';
		$post_count	= count( $_POST );
		$user_agent = rs_wpss_get_user_agent();
		$req_url	= rs_wpss_casetrans( 'lower', rs_wpss_get_url() );
		$req_ajax	= rs_wpss_is_ajax_request();
		$req_404	= rs_wpss_is_404(); /* Not all WP sites return proper 404 status. The fact this security check even got activated means it was a 404. */
		$req_hal	= rs_wpss_get_http_accept( TRUE, TRUE, TRUE );
		$req_ha		= rs_wpss_get_http_accept( TRUE, TRUE );

		/* IP / PROXY INFO - BEGIN */
		global $wpss_ip_proxy_info;
		if ( empty( $wpss_ip_proxy_info ) ) { $wpss_ip_proxy_info = rs_wpss_ip_proxy_info(); }
		extract( $wpss_ip_proxy_info );
		/* IP / PROXY INFO - END */
		
		/* Short Signatures - Regex */

		$rgx_sig_arr = array( '-e*5l?*B-@yZ_-,8_-lSZ98BC[', '+25-Z9dCZ,87C-7CBlSZ=-C[', );

		foreach( $_POST as $k => $v ) {
			$v = rs_wpss_casetrans( 'lower', $v );
			foreach( $rgx_sig_arr as $i => $s ) { /* Switch to single preg_match as this expands, replace nested foreach() */
				$sd = rs_wpss_rbkmd( $s, 'de' );
				if( FALSE !== strpos( $v, $sd ) ) { return TRUE; }
				}
			}
		
		/* Full Signatures */
		
		$signatures = array(
			/* SIGNATURES - BEGIN */

			array(
				'description' 		=> 'Revslider & Showbiz Pro - AJAX Vulnerability', 
				'post_i_min'		=> 2, 
				'post_i_max'		=> 2, 
				'target_urls'		=> array( '/wp-admin/admin-ajax.php', ),
				'ajax_request'		=> FALSE, 
				'404'				=> '*', 
				'session_cookie'	=> FALSE, 
				'hal_signature'		=> array( '', ), 
				'ha_signature'		=> array( '', '*/*', ), 
				'key_val_pairs'		=> array( 
					array( 
						'action'		=> 'revslider_ajax_action', 
						'client_action'	=> 'update_plugin',
						), 
					array( 
						'action'		=> 'showbiz_ajax_action', 
						'client_action'	=> 'update_plugin', 
						), 
					),
				),

			array(
				'description' 		=> 'WP Marketplace <= 2.4.0 & WP Download Manager <=2.7.4 - Remote Code Execution', 
				'post_i_min'		=> 5, 
				'post_i_max'		=> 5, 
				'target_urls'		=> array(), 
				'ajax_request'		=> FALSE, 
				'404'				=> '*', 
				'session_cookie'	=> FALSE, 
				'hal_signature'		=> array( '', ), 
				'ha_signature'		=> array( '', '*/*', ), 
				'key_val_pairs'		=> array( 
					array( 
						'action'		=> 'wpmp_pp_ajax_call', 
						'user_login'	=> '*', 
						'execute'		=> 'wp_insert_user', 
						'role'			=> 'administrator', 
						'user_pass'		=> '*', 
						), 
					array( 
						'action'		=> 'wpdm_ajax_call', 
						'user_login'	=> '*', 
						'execute'		=> 'wp_insert_user', 
						'role'			=> 'administrator', 
						'user_pass'		=> '*', 
						), 
					),
				),

			array(
				'description' 		=> 'WP Symposium <= 14.11 - Shell Upload Vulnerability', 
				'post_i_min'		=> 2, 
				'post_i_max'		=> 3, 
				'target_urls'		=> array( '/wp-content/plugins/wp-symposium/server/php/index.php', ), 
				'ajax_request'		=> FALSE, 
				'404'				=> '*', 
				'session_cookie'	=> FALSE, 
				'hal_signature'		=> array( '', ), 
				'ha_signature'		=> array( '', '*/*', ), 
				'key_val_pairs'		=> array( 
					array( 
						'uploader_url'	=> $plug_url.'/wp-symposium/server/php/', 
						'uploader_uid'	=> '1', 
						), 
					),
				),

			array(
				'description' 		=> 'Ultimate Product Catalogue <= 3.11 - Multiple Vulnerabilities', 
				'post_i_min'		=> 3, 
				'post_i_max'		=> 3, 
				'target_urls'		=> array( '/wp-content/plugins/ultimate-product-catalogue/product-sheets/wp-links-ompt.php', '/wp-content/plugins/ultimate-product-catalogue/product-sheets/wp-includes.php', '/wp-content/plugins/ultimate-product-catalogue/product-sheets/wp-styles.php', ), 
				'ajax_request'		=> FALSE, 
				'404'				=> '*', 
				'session_cookie'	=> FALSE, 
				'hal_signature'		=> array( '', ), 
				'ha_signature'		=> array( '', '*/*', ), 
				'key_val_pairs'		=> array( 
					array( 
						'p2'			=> '2929', 
						'abc28'			=> 'print $_REQUEST[\'p1\'].$_REQUEST[\'p2\']', 
						'p1'			=> '4242', 
						), 
					array( 
						'p2'			=> '2929', 
						'af5f492a1'		=> 'print $_REQUEST[\'p1\'].$_REQUEST[\'p2\']', 
						'p1'			=> '4242', 
						), 
					array( 
						'p2'			=> '2929', 
						'e41e'			=> 'print $_REQUEST[\'p1\'].$_REQUEST[\'p2\']', 
						'p1'			=> '4242', 
						), 
					),
				),

			array(
				'description' 		=> 'Ultimate Product Catalogue <= 3.11 - Multiple Vulnerabilities', 
				'post_i_min'		=> 1, 
				'post_i_max'		=> 1, 
				'target_urls'		=> array( '/wp-content/plugins/ultimate-product-catalogue/product-sheets/wp-setup.php', '/wp-content/plugins/ultimate-product-catalogue/product-sheets/wp-includes.php', ), 
				'ajax_request'		=> FALSE, 
				'404'				=> '*', 
				'session_cookie'	=> FALSE, 
				'hal_signature'		=> array( '', ), 
				'ha_signature'		=> array( '', '*/*', ), 
				'key_val_pairs'		=> array( 
					array( 
						'e51e'			=> 'die(pi());', 
						), 
					array( 
						'af5f492a1'		=> 'die(pi());', 
						), 
					),
				),

			array(
				'description' 		=> 'Simple Ads Manager <= 2.5.94 - Arbitrary File Upload', 
				'post_i_min'		=> 2, 
				'post_i_max'		=> 2, 
				'target_urls'		=> array( '/wp-content/plugins/simple-ads-manager/sam-ajax-admin.php', ), 
				'ajax_request'		=> FALSE, 
				'404'				=> '*', 
				'session_cookie'	=> FALSE, 
				'hal_signature'		=> array( '', ), 
				'ha_signature'		=> array( '', '*/*', ), 
				'key_val_pairs'		=> array( 
					array( 
						'action'		=> 'upload_ad_image', 
						'path'			=> '*', 
						), 
					),
				),

			array(
				'description' 		=> 'Work The Flow File Upload <= 2.5.2 - Shell Upload', 
				'post_i_min'		=> 1, 
				'post_i_max'		=> 1, 
				'target_urls'		=> array( '/wp-content/plugins/work-the-flow-file-upload/public/assets/jquery-file-upload-9.5.0/server/php/index.php', '/assets/plugins/jquery-file-upload/server/php/index.php', ), 
				'ajax_request'		=> FALSE, 
				'404'				=> '*', 
				'session_cookie'	=> FALSE, 
				'hal_signature'		=> array( '', ), 
				'ha_signature'		=> array( '', '*/*', ), 
				'key_val_pairs'		=> array( 
					array( 
						'action'		=> 'upload', 
						), 
					),
				),


			/* SIGNATURES - END */
			);
		
		/* Run Checks Against Signatures */
		
		foreach ( $signatures as $i => $sig ) {
			if ( !empty( $sig['post_i_min'] ) && ( $post_count < $sig['post_i_min'] || $post_count > $sig['post_i_max'] ) ) { continue; }
			if ( !empty( $sig['target_urls'] ) ) { 
				$urls_rgx = rs_wpss_get_regex_phrase( $sig['target_urls'],'','red_str' );
				if ( !preg_match( $urls_rgx, $req_url ) ) { continue; }
				}
			if ( $sig['ajax_request'] !== '*' && $sig['ajax_request'] !== $req_ajax ) { continue; }
			if ( $sig['404'] !== '*' && $sig['404'] !== $req_404 ) { continue; }
			$hal_max = count( $sig['hal_signature'] ) - 1; $m = 0; /* Matches */
			foreach( $sig['hal_signature'] as $i => $hal_sig ) {
				if ( $hal_sig == $req_hal ) { $m++; }
				if ( $i == $hal_max && $m === 0 ) { continue 2; }
				}
			$ha_max = count( $sig['ha_signature'] ) - 1; $m = 0; /* Matches */
			foreach( $sig['ha_signature'] as $i => $ha_sig ) {
				if ( $ha_sig == $req_ha ) { $m++; }
				if ( $i == $ha_max && $m === 0 ) { continue 2; }
				}
			foreach( $sig['key_val_pairs'] as $i => $kvp ) {
				$kvp_max = count( $kvp ); $m = 0; /* Matches */
				foreach( $kvp as $k => $v ) {
					if ( ( !empty( $_POST[$k] ) && $_POST[$k] === $v ) || ( $v === '*' && isset( $_POST[$k] ) ) ) { $m++; }
					if ( $m === $kvp_max ) { return TRUE; }
					}
				}
			}
		return FALSE;
		}

	public function ip_ban( $method = 'set' ) {
		/***
		* Ban users by IP address or check if they have been banned
		* Added 1.9.4
		* $method: 'set','chk'
		***/
		if ( FALSE === WPSS_IP_BAN_ENABLE || TRUE === WPSS_IP_BAN_CLEAR ) { self::clear_ip_ban(); return FALSE; }
		$wpss_ip_ban_disable = get_option('spamshield_ip_ban_disable');
		if ( !empty( $wpss_ip_ban_disable ) ) { self::clear_ip_ban(); return FALSE; }
		$ip = rs_wpss_get_ip_addr();
		if ( $ip === WPSS_SERVER_ADDR ) { return FALSE; } /* Skip website IP address */
		if ( strpos( $ip, '.' ) !== FALSE ) {
			$ip_arr = explode( '.', $ip ); unset( $ip_arr[3] ); $ip_c = implode( '.', $ip_arr ) . '.';
			if ( strpos( WPSS_SERVER_ADDR, $ip_c ) === 0 ) { return FALSE; } /* Skip anything on same C-Block as website */
			}
		if ( strpos( WPSS_SERVER_NAME_REV, RSMP_DEBUG_SERVER_NAME_REV ) !== 0 ) { if ( rs_wpss_is_admin_ip( $ip ) ) { return FALSE; } }

		/* TO DO: Add logic for reverse proxies */


		$ip_ban_status = FALSE;
		$wpss_ip_ban = get_option('spamshield_ip_ban');
		if ( empty( $wpss_ip_ban ) ) { $wpss_ip_ban = array(); }
		/* Check */
		if ( !empty( $ip ) && in_array( $ip, $wpss_ip_ban, TRUE ) ) { $ip_ban_status = TRUE; }
		/* Set */
		if ( !empty( $ip_ban_status ) || $method === 'set' ) {
			if ( !empty( $ip ) && !in_array( $ip, $wpss_ip_ban, TRUE ) ) { $wpss_ip_ban[] = $ip; }
			$wpss_ip_ban = rs_wpss_sort_unique( $wpss_ip_ban );
			update_option( 'spamshield_ip_ban', $wpss_ip_ban );
			self::ip_ban_htaccess();
			$ip_ban_status = TRUE;
			}
		return $ip_ban_status;
		}

	private function ip_ban_htaccess() {
		/***
		* Write the updated list of banned IP's to .htaccess.
		* Added 1.9.4
		***/
		$hta_bak_dir		= RSMP_CONTENT_DIR_PATH.'/backup';
		$hta_wpss_bak_dir	= $hta_bak_dir.'/wp-spamshield';
		$hta_file			= ABSPATH.'/.htaccess';
		$hta_bak_file		= $hta_wpss_bak_dir.'/original.htaccess';
		$wpss_index_file	= WPSS_PLUGIN_PATH.'/index.php';
		$bak_dir_hta_file	= WPSS_PLUGIN_PATH.'/lib/sec/.htaccess';
		$ip 				= rs_wpss_get_ip_addr();

		$wpss_ip_ban = get_option('spamshield_ip_ban');
		if ( empty( $wpss_ip_ban ) ) { return FALSE; }
		$wpss_ip_ban = rs_wpss_sort_unique( $wpss_ip_ban );
		$banned_ip_count = count( $wpss_ip_ban );
		$ip_ban_rgx = '^('.str_replace( '.', '\.', implode( '|', $wpss_ip_ban ) ).')$';

		$wpss_hta_data = WPSS_EOL.WPSS_EOL.'# BEGIN WP-SpamShield'.WPSS_EOL.WPSS_EOL;
		$wpss_hta_data .= '<IfModule mod_setenvif.c>'.WPSS_EOL."\t".'SetEnvIf Remote_Addr '.$ip_ban_rgx.' wpss_sec_threat'.WPSS_EOL.'</IfModule>';
		$wpss_hta_data .= WPSS_EOL.WPSS_EOL.'# END WP-SpamShield'.WPSS_EOL.WPSS_EOL;
		$wpss_hta_data_wp = '# BEGIN WordPress';

		if ( file_exists( $hta_file ) ) {
			if ( !file_exists( $hta_wpss_bak_dir ) ) {
				wp_mkdir_p( $hta_wpss_bak_dir );
				@chmod( $hta_wpss_bak_dir, 0750 );
				@chmod( $hta_bak_dir, 0750 );
				@copy ( $bak_dir_hta_file, $hta_wpss_bak_dir.'/.htaccess' );
				@copy ( $wpss_index_file, $hta_wpss_bak_dir.'/index.php' );
				@copy ( $bak_dir_hta_file, $hta_bak_dir.'/.htaccess' );
				@copy ( $wpss_index_file, $hta_bak_dir.'/index.php' );
				}
			if ( !file_exists( $hta_bak_file ) ) {
				@copy ( $hta_file, $hta_bak_file );
				}
			$hta_contents = file_get_contents( $hta_file );
			if ( strpos( $hta_contents, '# BEGIN WP-SpamShield' ) !== FALSE && strpos( $hta_contents, '# END WP-SpamShield' ) !== FALSE ) {
				$hta_contents_mod = preg_replace( "~#\ BEGIN\ WP-SpamShield[\w\W]+#\ END\ WP-SpamShield~i", trim( $wpss_hta_data, WPSS_EOL ), $hta_contents );
				if ( $hta_contents_mod !== $hta_contents ) {
					file_put_contents( $hta_file, $hta_contents_mod, LOCK_EX );
					}
				}
			elseif ( strpos( $hta_contents, '# BEGIN WordPress' ) !== FALSE ) {
				$hta_contents_mod = preg_replace( "~#\ BEGIN\ WordPress~i", $wpss_hta_data.$wpss_hta_data_wp, $hta_contents );
				file_put_contents( $hta_file, $hta_contents_mod, LOCK_EX );
				}
			else {
				file_put_contents( $hta_file, WPSS_EOL.WPSS_EOL.$wpss_hta_data.WPSS_EOL.WPSS_EOL, FILE_APPEND | LOCK_EX );
				}
			rs_wpss_append_log_data( WPSS_EOL.'IP address banned and added to .htaccess block list. IP: '.$ip, FALSE );
			}
		}

	static public function clear_ip_ban() {
		/***
		* Clear IP ban from database and .htaccess.
		* Added 1.9.4
		***/
		update_option( 'spamshield_ip_ban', array() );
		self::clear_ip_ban_htaccess();
		}

	static private function clear_ip_ban_htaccess() {
		/***
		* Clear banned IP info from .htaccess.
		* Added 1.9.4
		***/
		$hta_bak_dir		= RSMP_CONTENT_DIR_PATH.'/backup';
		$hta_wpss_bak_dir	= $hta_bak_dir.'/wp-spamshield';
		$hta_file			= ABSPATH.'/.htaccess';
		$hta_bak_file		= $hta_wpss_bak_dir.'/original.htaccess';
		$wpss_index_file	= WPSS_PLUGIN_PATH.'/index.php';
		$bak_dir_hta_file	= WPSS_PLUGIN_PATH.'/lib/sec/.htaccess';

		$wpss_hta_data = '# BEGIN WP-SpamShield'.WPSS_EOL.WPSS_EOL.'# END WP-SpamShield';

		if ( file_exists( $hta_file ) ) {
			if ( !file_exists( $hta_wpss_bak_dir ) ) {
				wp_mkdir_p( $hta_wpss_bak_dir );
				@copy ( $bak_dir_hta_file, $hta_wpss_bak_dir.'/.htaccess' );
				@copy ( $wpss_index_file, $hta_wpss_bak_dir.'/index.php' );
				@copy ( $bak_dir_hta_file, $hta_bak_dir.'/.htaccess' );
				@copy ( $wpss_index_file, $hta_bak_dir.'/index.php' );
				}
			if ( !file_exists( $hta_bak_file ) ) {
				@copy ( $hta_file, $hta_bak_file );
				}
			$hta_contents = file_get_contents( $hta_file );
			if ( strpos( $hta_contents, '# BEGIN WP-SpamShield' ) !== FALSE && strpos( $hta_contents, '# END WP-SpamShield' ) !== FALSE ) {
				$hta_contents_mod = preg_replace( "~#\ BEGIN\ WP-SpamShield[\w\W]+#\ END\ WP-SpamShield~i", $wpss_hta_data, $hta_contents );
				if ( $hta_contents_mod !== $hta_contents ) {
					file_put_contents( $hta_file, $hta_contents_mod, LOCK_EX );
					rs_wpss_append_log_data( WPSS_EOL.'Banned IP addresses removed from .htaccess.', TRUE );
					}
				}
			}
		}

	}
