<?php
/***
* WP-SpamShield Widgets
* Ver 1.9.5.2
***/

if ( !defined( 'ABSPATH' ) ) {
	if ( !headers_sent() ) { header('HTTP/1.1 403 Forbidden'); }
	die('ERROR: Direct access to this file is not allowed.');
	}

class WP_SpamShield_Counter_CG extends WP_Widget {

	function __construct() {
		
		parent::__construct(
			'wp_spamshield_counter_css', // Base ID
			__( 'WP-SpamShield Counter', WPSS_PLUGIN_NAME ) .' - '. __( 'Custom', WPSS_PLUGIN_NAME ), // Name
			array( 
				'description' => __( 'Show how much spam is being blocked by WP-SpamShield.', WPSS_PLUGIN_NAME ) .' '. __( 'This is a very customizable widget with options for color and style, including a custom color chooser.', WPSS_PLUGIN_NAME ), /* NEEDS TRANSLATION */
				)
			);
		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_head', array( $this, 'css' ) );
			}
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_footer-widgets.php', array( $this, 'print_scripts' ), 9999 );
		}

    public function enqueue_scripts( $hook_suffix ) {
		if ( 'widgets.php' !== $hook_suffix ) { return; }
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'underscore' );
		}

	public function print_scripts() {
		?>
		<script>
			( function( $ ){
				function initColorPicker( widget ) {
					widget.find( '.wpss-color-picker' ).wpColorPicker( {
						change: _.throttle( function() { // For Customizer
							$(this).trigger( 'change' );
						}, 3000 )
					});
				}
				function onFormUpdate( event, widget ) {
					initColorPicker( widget );
				}

				$( document ).on( 'widget-added widget-updated', onFormUpdate );

				$( document ).ready( function() {
					$( '#widgets-right .widget:has(.wpss-color-picker)' ).each( function () {
						initColorPicker( $( this ) );
					} );
				} );
			}( jQuery ) );
		</script>
		<?php
	}

	public function css() {
		/***
		* Allow users to customize.
		* Load colors from options, only use individual color override is that option is selected from the drop down, otherwise use palettes
		***/
		
		$widget_options = get_option('spamshield_widget_settings');
		if ( empty( $widget_options ) ) { $widget_options = array( 'basecolor' => '#5A5A5A', 'style' => '1' ); }
		extract($widget_options);

		/* Custom Palettes */
		$custom_palettes = array(
			/* 'Base Color' => array( 'BG Light','BG Dark','Line 2 Text','Hover BG Light','Hover BG Dark','Line 1 Text' ); */
			'#5A5A5A' => array('#5A5A5A','#000000','#BEBEBE','#31332F','#2D2D2D','#FFF'), 		/* Black/Gray */
			'#5B5853' => array('#5B5853','#262523','#C4B49D','#5B5853','#262523','#D1C1A9'),	/* Brown/Tan */
			'#5D4022' => array('#5D4022','#000000','#E7BC6D','#5D4022','#000000','#FAF1B8'),	/* Brown/Black/Gold */
			'#623C17' => array('#623C17','#27190E','#A5651C','#623C17','#27190E','#FBC36C'),	/* Brown/Yellow */
			'#5B1B04' => array('#5B1B04','#350E00','#E5D8B8','#491301','#3F1101','#FFF'),		/* Dark Brown */
			'#AB0101' => array('#AB0101','#410101','#E7BC6D','#AB0101','#410101','#FAF1B8'),	/* Red/Black/Gold */
			'#B61C3E' => array('#B61C3E','#76242A','#E0ADA1','#B61C3E','#76242A','#EDE5CE'),	/* Cherry Red/Tan */
			'#DB3446' => array('#DB3446','#8B142A','#E9C5B3','#D52C42','#860C27','#F6EDDB'),	/* Red */
			'#F2922B' => array('#F8AA58','#A96119','#764115','#EB8C23','#A25c13','#F6EDDB'),	/* Orange-Yellow */
			'#F8AA58' => array('#F8AA58','#F2922C','#A0672D','#EA9E4D','#E98B25','#F6EDDB'),	/* Orange-Yellow */
			'#00B599' => array('#00B599','#058674','#066350','#00AE92','#00806E','#F6EDDB'),	/* Teal */
			'#079AAD' => array('#079AAD','#005962','#C3D4D4','#007D8D','#00555E','#F6EDDB'),	/* Dark Teal Blue */
			'#125C69' => array('#125C69','#002738','#9EB6BA','#125C69','#002738','#DCDCD2'),	/* Blue/Tan */
			'#5386BB' => array('#5386BB','#030629','#E7BC6D','#5386BB','#030629','#FAF1B8'),	/* Blue/Black/Gold */	
			);

		$colors = $this->get_colors();
		$basecolor = ( !empty( $basecolor ) && $this->is_valid_hex_color( $basecolor ) ) ? sanitize_text_field( $basecolor ) : '#5A5A5A';
		$style = !empty( $style ) ? sanitize_text_field( $style ) : '1';
	
		if ( array_key_exists( $basecolor, $custom_palettes ) ) {
			$color_pal = $custom_palettes[$basecolor];
			}
		else {
			// If we don't have a palette set for a base color, auto-generate a palette based on the base color
			$color_pal = array_values( $this->generate_widget_palette( $basecolor ) );
			}

		/* Set dynamic CSS values */
		$style1_borrad	= '15';	$style2_borrad	= '8';	/* Border Radius */
		$style1_bor		= '2';	$style2_bor		= '2';	/* Border Color */
		$l1_let_spac	= '2';	$l2_let_spac	= '3';	$l3_let_spac	= '2';	/* Default Letter Spacing */
		$l1_fnt_sz		= '20';	$l2_fnt_sz		= '11';	$l3_fnt_sz		= '11';	/* Default Font Size */

		/* Check string length so we can adjust letter spacing and font size to make everything fit nicely, without JS */
		$blocked_txt_len 		= rs_wpss_strlen(rs_wpss_blocked_txt());
		if		( $blocked_txt_len > 18 ) { $l2_let_spac = 1; $l3_let_spac = 1; $l2_fnt_sz = '10'; $l3_fnt_sz = '10'; }
		elseif	( $blocked_txt_len > 15 ) { $l2_let_spac = 1; $l3_let_spac = 1; }
		elseif	( $blocked_txt_len > 14 ) { $l2_let_spac = 2; $l3_let_spac = 1; }
		$style1_boxshad_col		= $this->hex_color_mod($color_pal[1], 0, 0, -0.2); /* Box Shadow Color */
		$style1_hovbg			= $this->hex_color_mod($color_pal[0], 0, 0, -0.06); /* Hover BG Color */
		$style1_actbg			= $this->hex_color_mod($style1_hovbg, 0, 0, -0.1); /* Active BG Color */
		$style1_boxshad_hovcol	= $style1_boxshad_col; /* Hover Box Shadow Color */
		$style1_boxshad_blurrad	= '40'; /* Box Shadow Blur Radius */
		$style1_boxshad_sprdrad	= '5'; /* Box Shadow Spread Radius */
		$style1_borcol			= $this->hex_color_mod($color_pal[1], 0, 0, -0.2); /* Border Color */
		$style2_actbglt			= $this->hex_color_mod($color_pal[3], 0, 0, -0.1); /* Active BG Light */
		$style2_actbgdk			= $this->hex_color_mod($color_pal[4], 0, 0, -0.1); /* Active BG Dark */

		if ( $style == '2' ) {
			/* Style 2 */
?>
<style type="text/css">
.wpssstats { width: auto; }
.wpssstats a { background: <?php echo $color_pal[0]; ?>; background-image:-moz-linear-gradient(0% 100% 90deg,<?php echo $color_pal[1]; ?>,<?php echo $color_pal[0]; ?>); background-image:-webkit-gradient(linear,0% 0,0% 100%,from(<?php echo $color_pal[0]; ?>),to(<?php echo $color_pal[1]; ?>)); border: <?php echo $style2_bor; ?>px solid <?php echo $color_pal[1]; ?>; border-radius:<?php echo $style2_borrad; ?>px; color: <?php echo $color_pal[2]; ?> !important; cursor: pointer; display: block; font-weight: normal; height: 100%; -moz-border-radius:<?php echo $style2_borrad; ?>px; padding: 7px 0 6px; text-align: center; text-decoration: none; -webkit-border-radius:<?php echo $style2_borrad; ?>px; width: 98%; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpssstats a:hover { text-decoration: none; background-image:-moz-linear-gradient(0% 100% 90deg,<?php echo $color_pal[3]; ?>,<?php echo $color_pal[4]; ?>); background-image:-webkit-gradient(linear,0% 0,0% 100%,from(<?php echo $color_pal[4]; ?>),to(<?php echo $color_pal[3]; ?>)); transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpssstats a:active { text-decoration: none; background-image:-moz-linear-gradient(0% 100% 90deg,<?php echo $style2_actbglt; ?>,<?php echo $style2_actbgdk; ?>); background-image:-webkit-gradient(linear,0% 0,0% 100%,from(<?php echo $style2_actbgdk; ?>),to(<?php echo $style2_actbglt; ?>)); transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpssstats .wpsscount { color: <?php echo $color_pal[5]; ?> !important; display: block; font-size: <?php echo $l1_fnt_sz; ?>px; line-height: 140%; letter-spacing: <?php echo $l1_let_spac; ?>px; padding: 0 13px; white-space: nowrap; }
.wpssstats .wpsscount2 { color: <?php echo $color_pal[5]; ?> !important; display: block; font-size: <?php echo $l2_fnt_sz; ?>px; line-height: 120%; letter-spacing: <?php echo $l2_let_spac; ?>px; padding: 0 13px; white-space: nowrap; }
.wpssstats .wpsscount3 { font-size: <?php echo $l3_fnt_sz; ?>px; line-height: 120%; letter-spacing: <?php echo $l3_let_spac; ?>px; padding: 0 0; white-space: nowrap; }
</style>
<?php
			}
		else {
			/* Style 1 - Default */
?>
<style type="text/css">
.wpssstats { width: auto; }
.wpssstats a { background: <?php echo $color_pal[0]; ?>; border: <?php echo $style1_bor; ?>px solid <?php echo $style1_borcol; ?>; border-radius:<?php echo $style1_borrad; ?>px; color: <?php echo $color_pal[2]; ?> !important; cursor: pointer; display: block; font-weight: normal; height: 100%; -moz-border-radius:<?php echo $style1_borrad; ?>px; padding: 7px 0 6px; text-align: center; text-decoration: none; -webkit-border-radius:<?php echo $style1_borrad; ?>px; width: 98%; -moz-box-shadow: inset 0 0 <?php echo $style1_boxshad_blurrad; ?>px <?php echo $style1_boxshad_sprdrad; ?>px <?php echo $style1_boxshad_col; ?>; -webkit-box-shadow: inset 0 0 <?php echo $style1_boxshad_blurrad; ?>px <?php echo $style1_boxshad_sprdrad; ?>px <?php echo $style1_boxshad_col; ?>; box-shadow: inset 0 0 <?php echo $style1_boxshad_blurrad; ?>px <?php echo $style1_boxshad_sprdrad; ?>px <?php echo $style1_boxshad_col; ?>; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpssstats a:hover { background: <?php echo $style1_hovbg; ?>; border: <?php echo $style1_bor; ?>px solid <?php echo $style1_borcol; ?>; text-decoration: none; -moz-box-shadow: inset 0 0 <?php echo $style1_boxshad_blurrad; ?>px <?php echo $style1_boxshad_sprdrad; ?>px <?php echo $style1_boxshad_hovcol; ?>; -webkit-box-shadow: inset 0 0 <?php echo $style1_boxshad_blurrad; ?>px <?php echo $style1_boxshad_sprdrad; ?>px <?php echo $style1_boxshad_hovcol; ?>; box-shadow: inset 0 0 <?php echo $style1_boxshad_blurrad; ?>px <?php echo $style1_boxshad_sprdrad; ?>px <?php echo $style1_boxshad_hovcol; ?>; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpssstats a:active { background: <?php echo $style1_actbg; ?>; border: <?php echo $style1_bor; ?>px solid <?php echo $style1_borcol; ?>; text-decoration: none; -moz-box-shadow: inset 0 0 <?php echo $style1_boxshad_blurrad; ?>px <?php echo $style1_boxshad_sprdrad; ?>px <?php echo $style1_boxshad_hovcol; ?>; -webkit-box-shadow: inset 0 0 <?php echo $style1_boxshad_blurrad; ?>px <?php echo $style1_boxshad_sprdrad; ?>px <?php echo $style1_boxshad_hovcol; ?>; box-shadow: inset 0 0 <?php echo $style1_boxshad_blurrad; ?>px <?php echo $style1_boxshad_sprdrad; ?>px <?php echo $style1_boxshad_hovcol; ?>; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpssstats .wpsscount { color: <?php echo $color_pal[5]; ?> !important; display: block; font-size: <?php echo $l1_fnt_sz; ?>px; line-height: 140%; letter-spacing: <?php echo $l1_let_spac; ?>px; padding: 0 13px; white-space: nowrap; }
.wpssstats .wpsscount2 { color: <?php echo $color_pal[5]; ?> !important; display: block; font-size: <?php echo $l2_fnt_sz; ?>px; line-height: 120%; letter-spacing: <?php echo $l2_let_spac; ?>px; padding: 0 13px; white-space: nowrap; }
.wpssstats .wpsscount3 { font-size: <?php echo $l3_fnt_sz; ?>px; line-height: 120%; letter-spacing: <?php echo $l3_let_spac; ?>px; padding: 0 0px; white-space: nowrap; }
</style>

<?php
		}

	}

	private function is_valid_hex_color( $color ) {
		if ( preg_match("~^#([a-f0-9]{6}|[a-f0-9]{3})$~i", $color) ) { return TRUE; }
		return FALSE;
		}

	private function is_light_color( $hex ) {
		$hsv = $this->HEX_TO_HSV($hex);
		if ( $hsv['V'] > .5 ) { return TRUE; }
		return FALSE;
		}

	private function hex_color_mod($hex, $H_diff, $S_diff, $V_diff) {
		$hsv = $this->HEX_TO_HSV($hex);
		$hsv['H'] = $hsv['H'] + $H_diff; $hsv['S'] = $hsv['S'] + $S_diff; $hsv['V'] = $hsv['V'] + $V_diff;
		return $this->HSV_TO_HEX($hsv['H'], $hsv['S'], $hsv['V']);
		}

	private function HEX_TO_RGB($hex) {
		$rgbhex = str_split(trim($hex, '# '), 2);
		$rgbdec = array_map('hexdec', $rgbhex);
		$RGB = array( 'R' => $rgbdec[0], 'G' => $rgbdec[1], 'B' => $rgbdec[2] );
		return $RGB;
		}

	private function HEX_TO_HSV($hex) {
		$rgb = $this->HEX_TO_RGB($hex);
		$HSV = $this->RGB_TO_HSV($rgb['R'],$rgb['G'],$rgb['B']);
		return $HSV;
		}

	private function RGB_TO_HEX($R, $G, $B) {
		$rgb = compact('R','G','B');
		$output = array_map('dechex', $rgb);
		$output = array_map( array( $this, 'fixhex2' ), $output); /* Fix single-digit hex */
		$HEX = '#'.implode($output);
		return $HEX;
		}

	private function HSV_TO_HEX($H, $S, $V) {
		$rgb = $this->HSV_TO_RGB($H, $S, $V);
		$HEX = $this->RGB_TO_HEX($rgb['R'],$rgb['G'],$rgb['B']);
		return $HEX;
		}

	private function RGB_TO_HSV ($R, $G, $B) {  /* RGB Values:Number 0-255, HSV Results:Number 0-1 */
		if ( $R < 0 ) { $R = 0; } elseif ( $R > 255 ) { $R = 255; }
		if ( $G < 0 ) { $G = 0; } elseif ( $G > 255 ) { $G = 255; }
		if ( $B < 0 ) { $B = 0; } elseif ( $B > 255 ) { $B = 255; }
		$var_R = ($R / 255); $var_G = ($G / 255); $var_B = ($B / 255); $var_Min = min($var_R, $var_G, $var_B);
		$var_Max = max($var_R, $var_G, $var_B);
		$del_Max = $var_Max - $var_Min;
		$V = $var_Max;
		if ($del_Max == 0) { $H = 0; $S = 0; }
		else {
			$S = $del_Max / $var_Max;
			$del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			$del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			$del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			if      ($var_R == $var_Max) $H = $del_B - $del_G;
			else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
			else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;
			if ($H<0) $H++; if ($H>1) $H--;
			}
		$HSV = compact('H','S','V');
		return $HSV;
		}

	private function HSV_TO_RGB ($H, $S, $V) { /* HSV Values:Number 0-1, RGB Results:Number 0-255 */
		if ( $H < 0 ) { $H = 0; } elseif ( $H > 1 ) { $H = 1; }
		if ( $S < 0 ) { $S = 0; } elseif ( $S > 1 ) { $S = 1; }
		if ( $V < 0 ) { $V = 0; } elseif ( $V > 1 ) { $V = 1; }
		if ( $S == 0 ) { $R = $G = $B = $V * 255; }
		else {
			$var_H = $H * 6;
			$var_i = floor( $var_H );
			$var_1 = $V * ( 1 - $S );
			$var_2 = $V * ( 1 - $S * ( $var_H - $var_i ) );
			$var_3 = $V * ( 1 - $S * (1 - ( $var_H - $var_i ) ) );
			if       ($var_i == 0) { $var_R = $V     ; $var_G = $var_3  ; $var_B = $var_1 ; }
			else if  ($var_i == 1) { $var_R = $var_2 ; $var_G = $V      ; $var_B = $var_1 ; }
			else if  ($var_i == 2) { $var_R = $var_1 ; $var_G = $V      ; $var_B = $var_3 ; }
			else if  ($var_i == 3) { $var_R = $var_1 ; $var_G = $var_2  ; $var_B = $V     ; }
			else if  ($var_i == 4) { $var_R = $var_3 ; $var_G = $var_1  ; $var_B = $V     ; }
			else                   { $var_R = $V     ; $var_G = $var_1  ; $var_B = $var_2 ; }
			$R = $var_R * 255; $G = $var_G * 255; $B = $var_B * 255;
			}
		$RGB = compact('R','G','B');
		return $RGB;
		}

	private function fixhex2($num) {
		$padnum = '0'.$num;
		return (rs_wpss_strlen($num) < 2) ? $padnum : $num;
		}

	private function get_colors() {
		$colors = array( 
			/* Custom */
			'#5A5A5A', '#5B5853', '#5D4022', '#623C17', '#5B1B04', '#AB0101', '#B61C3E', '#DB3446', '#F2922B', '#F8AA58', '#00B599', '#079AAD', '#125C69', '#5386BB', 
			/* Auto-generated */
			'#215DA8', '#2183A8', '#38A821', '#56A821', '#86A821', '#A3A821', '#A88D21', '#A87021', '#A85221', '#A83A21', '#A82121', '#A82156', '#A82186', '#A821A8', '#3121A8', 
			);
		return $colors;
		}
		
	private function get_HSV_diff( $hex, $H_target, $S_target, $V_target ) {
		$hsv = $this->HEX_TO_HSV($hex);
		extract($hsv);
		if ( $H_target === FALSE || $S == 0 ) { $H_target = $H; }
		if ( $S_target === FALSE || $S == 0 ) { $S_target = $S; }
		if ( $V_target === FALSE ) { $V_target = $V; }
		$H_diff = $H_target - $H; $S_diff = $S_target - $S; $V_diff = $V_target - $V;
		$HSV_diff = compact('H_diff','S_diff','V_diff');
		return $HSV_diff;
		}

	private function generate_widget_palette( $hex ) {
		/* Generates a color palette for the widget from a given base color  */
		$colors = $this->get_colors();
		if ( !in_array( $hex, $colors, TRUE ) && $this->is_light_color( $hex ) ) {
			$S_target = .25; $V_target = .90; $l2_txt_H_diff = -0.002; $l2_txt_S_diff = -0.65; $l2_txt_V_diff = .25; $text = '#444';
			}
		else {
			$S_target = .85; $V_target = .65; $l2_txt_H_diff = -0.002; $l2_txt_S_diff = -0.65; $l2_txt_V_diff = 0.35; $text = '#FFF';
			}
		$basecolor_HSV_diff	= $this->get_HSV_diff( $hex, FALSE, $S_target , $V_target );
		$basecolor_HSV		= $this->hex_color_mod($hex, $basecolor_HSV_diff['H_diff'], $basecolor_HSV_diff['S_diff'], $basecolor_HSV_diff['V_diff']);
		$bg_light			= $basecolor_HSV;
		$bg_dark			= $this->hex_color_mod($basecolor_HSV, 0.01, .075, -0.15);
		$line_2_text		= $this->hex_color_mod($basecolor_HSV, $l2_txt_H_diff, $l2_txt_S_diff, $l2_txt_V_diff);
		$hover_bg_light		= $this->hex_color_mod($basecolor_HSV, 0.0025, 0.02, -0.075);
		$hover_bg_dark		= $this->hex_color_mod($basecolor_HSV, 0.01, 0.04, -0.125);
		$palette 			= compact('bg_light','bg_dark','line_2_text','hover_bg_light','hover_bg_dark','text');
		return $palette;
		}

	public function form( $instance ) {
		$color_options = $this->get_colors();
		$color_options['user_color'] = __('Choose your own color', WPSS_PLUGIN_NAME );
		$title = !empty( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : rs_wpss_blocked_txt('UCW');
		$color = isset( $instance['color'] ) ? sanitize_text_field( $instance['color'] ) : '0';
		$style = isset( $instance['style'] ) ? sanitize_text_field( $instance['style'] ) : '1';
		$user_color = ( !empty( $instance['user_color'] ) && $color == 'user_color' ) ? sanitize_text_field( $instance['user_color'] ) : $color_options[$color];
		$style_text = __( 'Style', WPSS_PLUGIN_NAME );
		$style_options = array( '1' => $style_text.' 1', '2' => $style_text.' 2' );
?>
		<p>
		<label for="<?php echo $this->get_field_id('title' ); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Select Style:', WPSS_PLUGIN_NAME); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" >
<?php
			foreach ( $style_options as $i => $option ) {
				$selected = $style == $i ? ' selected="selected"' : '';
				echo '<option value="'.$i.'" id="'.$i.'"'.$selected.' >'.$option.'</option>';
				}
?>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Select Base Color:', WPSS_PLUGIN_NAME); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" >
<?php
		$i = 0;
		$textcolor = '#FFF';
		foreach ( $color_options as $k => $option ) {
			++$i; $selected = $color == $k ? 'selected="selected"' : '';
			$textcolor = ( $i > 1 && $k == 'user_color' ) ? '#000' : '#FFF';
			echo '<option value="'.$k.'" style="background-color:'.$option.';color:'.$textcolor.';" '.$selected.'>'. $i.' - '. $option .'</option>';
			}
?>
		</select>
		<?php _e( 'Once you select a base color, the plugin will use this to generate a color palette for your new widget.', WPSS_PLUGIN_NAME ); ?>
		</p>
		<p>
		<?php printf( __( 'If you\'d prefer to use your own color, for example if you\'d like to match things more closely with your website colors, select the "%1$s" option from the menu above, and then choose a base color below.', WPSS_PLUGIN_NAME ), $color_options['user_color'] ); ?>
		</p>
        <p>
		<label for="<?php echo $this->get_field_id('user_color' ); ?>"><?php _e('Choose a Custom Base Color:'); ?></label><br />
			<input class="wpss-color-picker" type="text" id="<?php echo $this->get_field_id( 'user_color' ); ?>" name="<?php echo $this->get_field_name( 'user_color' ); ?>" value="<?php echo esc_attr($user_color); ?>" />
		</p>
		<p>
		<?php _e( 'You pick the color, and the plugin will make sure your new widget looks great.', WPSS_PLUGIN_NAME ); ?>
		</p>
<?php
		}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$color_options = $this->get_colors();
		$color_options['user_color'] = __('Choose your own color', WPSS_PLUGIN_NAME );
		$instance['color'] = isset( $new_instance['color'] ) ? sanitize_text_field( $new_instance['color'] ) : '0';
		$instance['style'] = isset( $new_instance['style'] ) ? sanitize_text_field( $new_instance['style'] ) : '1';
		$instance['title'] = !empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : rs_wpss_blocked_txt('UCW');
		$instance['user_color'] = !empty( $new_instance['user_color'] ) ? sanitize_text_field( $new_instance['user_color'] ) : '#5A5A5A';
		$basecolor = $color_options[$instance['color']];
		if ( $instance['color'] == 'user_color' ) {  $basecolor = $instance['user_color']; }
		$basecolor = rs_wpss_casetrans( 'upper', $basecolor );
		$style = $instance['style'];
		$widget_settings = compact('basecolor','style');
		update_option( 'spamshield_widget_settings', $widget_settings );
		return $instance;
		}

	public function widget( $args, $instance ) {
		$title	= !empty( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : rs_wpss_blocked_txt('UCW');
		$count	= rs_wpss_number_format( rs_wpss_count() );
		//$count	= rs_wpss_number_format( 1000000 ); /* FOR TESTING & SCREEN SHOTS ONLY */
		$byline	= str_replace( WPSS_PLUGIN_NAME, '<strong>WP-SpamShield</strong>', rs_wpss_casetrans( 'lower', WPSS_Promo_Links::promo_text(1) ) );
		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
?>
	<div class="wpssstats">
		<a href="<?php echo WPSS_HOME_URL; ?>" target="_blank" rel="external" title=""><?php printf( __( '<strong class="wpsscount">%1$s</strong> <strong class="wpsscount2">%2$s</strong> <span class="wpsscount3">%3$s</span>', WPSS_PLUGIN_NAME ), $count, rs_wpss_blocked_txt(), $byline ); ?></a>
	</div>
<?php
		echo $args['after_widget'];
		}
	}

class WP_SpamShield_Counter_LG extends WP_Widget {
	/* Legacy Graphic Counter */

	function __construct() {
		parent::__construct(
			'spamshield_widget_counter_sm', // Base ID
			__( 'WP-SpamShield Counter', WPSS_PLUGIN_NAME ).' - '.__( 'Graphic', WPSS_PLUGIN_NAME ), // Name
			array(
				'description' => __( 'Show how much spam is being blocked by WP-SpamShield.', WPSS_PLUGIN_NAME ) .' '. __( 'This widget provides a spam counter graphic that lets you choose what color and size you prefer.', WPSS_PLUGIN_NAME ) , /* NEEDS TRANSLATION */
				)
			);
		}

	public function form( $instance ) {
		$title		= !empty( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : rs_wpss_blocked_txt('UCW');
		$style		= !empty( $instance['style'] ) ? sanitize_text_field( $instance['style'] ) : '6';
		$lg_txt		= __('Large', WPSS_PLUGIN_NAME); $sm_txt = __('Small', WPSS_PLUGIN_NAME); $ctr_txt = __( 'Counters', WPSS_PLUGIN_NAME );
		$blk_txt	= __('Black', WPSS_PLUGIN_NAME); $red_txt = __('Red', WPSS_PLUGIN_NAME); $lbl_txt = __('Light Blue', WPSS_PLUGIN_NAME); $dbl_txt = __('Dark Blue', WPSS_PLUGIN_NAME); $grn_txt = __('Green', WPSS_PLUGIN_NAME);
		$options	= array('1'=>$lg_txt.' - '.$blk_txt, '2'=>$lg_txt.' - '.$lbl_txt, '3'=>$lg_txt.' - '.$red_txt, '4'=>$lg_txt.' - '.$dbl_txt, '5'=>$lg_txt.' - '.$grn_txt, '6'=>$sm_txt.' - '.$blk_txt, '7'=>$sm_txt.' - '.$lbl_txt, '8'=>$sm_txt.' - '.$red_txt, '9'=>$sm_txt.' - '.$dbl_txt, '10'=>$sm_txt.' - '.$grn_txt, );
?>
		<p>
		<label for="<?php echo $this->get_field_id('title' ); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Select Style:', WPSS_PLUGIN_NAME); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" >
<?php
			foreach ( $options as $i => $option ) {
				echo '<option value="'.$i.'" id="'.$i.'"', $style == $i ? ' selected="selected"' : '' , ' >', $i, ' - ', $option ,'</option>';
				}
?>
		</select>
		</p>
		<p>
		<?php echo '<strong>'.$lg_txt.' '.$ctr_txt.':</strong>'; ?>
		</p>
		<p>
<?php
		foreach ( $options as $i => $option ) {
			if ( $i > 5 ) { break; }
			echo '<img src="'.WPSS_PLUGIN_COUNTER_URL.'/spamshield-counter-lgn-bg-'.$i.'-preview.png" style="border-style:none; margin: 10px 15px 0 0; width: 170px; height: 136px" width="170" height="136" title="" alt="" />';
			}
?>
		</p>
		<p>
		<?php echo '<strong>'.$sm_txt.' '.$ctr_txt.':</strong>'; ?>
		</p>
		<p>
<?php
		foreach ( $options as $i => $option ) {
			if ( $i < 6 ) { continue; }
			echo '<img src="'.WPSS_PLUGIN_COUNTER_URL.'/spamshield-counter-smn-bg-'.$i.'-preview.png" style="border-style:none; margin: 10px 15px 0 0; width: width: 150px; height: 90px" width="150" height="90" title="" alt="" />';
			}
?>
		</p>
<?php
		}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = !empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : rs_wpss_blocked_txt('UCW');
		$instance['style'] = !empty( $new_instance['style'] ) ? sanitize_text_field( $new_instance['style'] ) : '6';
		return $instance;
		}

	public function widget( $args, $instance ) {
		$title	= !empty( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : __( 'Spam', WPSS_PLUGIN_NAME);
		$style	= !empty( $instance['style'] ) ? sanitize_text_field( $instance['style'] ) : '6';
		global $wpss_wid_inst;
		if ( !isset( $wpss_wid_inst ) ) { $wpss_wid_inst = 0; }
		++$wpss_wid_inst;

		$style_max = 10;
		$style_min = 1;
		if ( empty( $style ) || $style > $style_max || $style < $style_min ) { $style = 1; }
		if ( $style > 5 ) { $size = 's'; $imgn = $style-5; $ht_x_diff = 7; } else { $size = 'lg'; $imgn = $style; $ht_x_diff = 0; }

		$count	= rs_wpss_number_format( rs_wpss_count() );
		//$count	= rs_wpss_number_format( 1000000 ); /* FOR TESTING & SCREEN SHOTS ONLY */
		$byline	= WPSS_Promo_Links::promo_text(1);
		$sip1c 	= substr(WPSS_SERVER_ADDR, 0, 1);
		$ht_x 				= $sip1c > '5' ? 2 + $ht_x_diff : 3 + $ht_x_diff;
		$hreftitle_txt 		= WPSS_Promo_Links::promo_text($ht_x);
		$blocked_txt		= rs_wpss_blocked_txt();
		$blocked_txt_len 	= rs_wpss_strlen($blocked_txt);

		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		
		if ( $size == 's' ) {
			/* Small */

			/* Set dynamic CSS values */
			$l1_let_spac	= '1';	$l2_let_spac	= '1';	/* Default Letter Spacing */
			$l1_fnt_sz		= '18';	$l2_fnt_sz		= '10';	/* Default Font Size */

			/* Check string length so we can adjust letter spacing and font size to make everything fit nicely, without JS */
			if		( $blocked_txt_len > 18 ) { $blocked_txt = 'SPAM BLOCKED'; }
			elseif	( $blocked_txt_len > 16 ) { $l2_fnt_sz = '7'; }
			elseif	( $blocked_txt_len > 14 ) { $l2_fnt_sz = '8'; }
			elseif	( $blocked_txt_len > 13 ) { $l2_fnt_sz = '9'; }
?>
<style type="text/css">
.wpsslstatssm_<?php echo $wpss_wid_inst; ?> { width: 120px; height: 50px; overflow: hidden; }
.wpsslstatssm_<?php echo $wpss_wid_inst; ?> a { background: transparent; background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-<?php echo $size; ?>-bg-<?php echo $imgn; ?>.png) no-repeat top left; background-position: 0px 0px; border-style: none; color: #FFF !important; cursor: pointer; display: block; font-family: Arial, Helvetica, sans-serif !important; font-weight: bold !important; padding: none; text-align: center; text-decoration: none; width: 120px; height: 50px; padding: 8px 0 0 0; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpsslstatssm_<?php echo $wpss_wid_inst; ?> a:hover { text-decoration: none; background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-<?php echo $size; ?>-bg-<?php echo $imgn; ?>.png) no-repeat top left; background-position: 0px -50px; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpsslstatssm_<?php echo $wpss_wid_inst; ?> a:active { text-decoration: none; background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-<?php echo $size; ?>-bg-<?php echo $imgn; ?>.png) no-repeat top left;  background-position: 0px -100px; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpsslstatssm_<?php echo $wpss_wid_inst; ?> .wpsslcountsm_<?php echo $wpss_wid_inst; ?> { color: #FFF !important; display: block; font-family: Arial, Helvetica, sans-serif !important; font-weight: bold !important; font-size: <?php echo $l1_fnt_sz; ?>px; line-height: 140% !important; letter-spacing: <?php echo $l1_let_spac; ?>px !important; padding: 0 0 0 0; white-space: nowrap; }
.wpsslstatssm_<?php echo $wpss_wid_inst; ?> .wpsslcountsm2_<?php echo $wpss_wid_inst; ?> { color: #FFF !important; display: block; font-family: Arial, Helvetica, sans-serif !important; font-weight: bold !important; font-size: <?php echo $l2_fnt_sz; ?>px; line-height: 70% !important; letter-spacing: <?php echo $l2_let_spac; ?>px !important; padding: 0 0 0 0; white-space: nowrap; }
</style>
	<div class="wpsslstatssm_<?php echo $wpss_wid_inst; ?>">
		<a href="<?php echo WPSS_HOME_URL; ?>" target="_blank" rel="external" title="<?php echo $hreftitle_txt; ?>"><?php printf( __( '<strong class="wpsslcountsm_%1$s">%2$s</strong> <strong class="wpsslcountsm2_%3$s">%4$s</strong>', WPSS_PLUGIN_NAME ), $wpss_wid_inst, $count, $wpss_wid_inst, $blocked_txt ); ?></a>
	</div>
<?php
			}
		else {
			/* Large */
			/* Set dynamic CSS values */
			$l1_let_spac	= '2';	$l2_let_spac	= '1';	$l3_let_spac	= '1';	/* Default Letter Spacing */
			$l1_fnt_sz		= '19';	$l2_fnt_sz		= '12';	$l3_fnt_sz		= '9';	/* Default Font Size */
			/* Check string length so we can adjust letter spacing and font size to make everything fit nicely, without JS */
			if		( $blocked_txt_len > 18 ) { $blocked_txt = 'SPAM BLOCKED'; $byline = 'BY WP-SPAMSHIELD'; }
			elseif	( $blocked_txt_len > 16 ) { $l2_let_spac = 1; $l3_let_spac = 1; $l2_fnt_sz = '9'; $l3_fnt_sz = '7'; }
			elseif	( $blocked_txt_len > 14 ) { $l2_let_spac = 1; $l3_let_spac = 1; $l2_fnt_sz = '10'; $l3_fnt_sz = '8'; }
			elseif	( $blocked_txt_len > 13 ) { $l2_let_spac = 1; $l3_let_spac = 1; $l2_fnt_sz = '11';}
?>
<style type="text/css">
.wpsslstats_<?php echo $wpss_wid_inst; ?> { width: 140px; height: 66px; overflow: hidden; }
.wpsslstats_<?php echo $wpss_wid_inst; ?> a { background: transparent; background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-<?php echo $size; ?>-bg-<?php echo $imgn; ?>.png) no-repeat top left; background-position: 0px 0px; border-style: none; color: #FFF !important; cursor: pointer; display: block; font-family: Arial, Helvetica, sans-serif !important; font-weight: bold !important; padding: none; text-align: center; text-decoration: none; width: 140px; height: 66px; padding: 8px 0 0 0; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpsslstats_<?php echo $wpss_wid_inst; ?> a:hover { text-decoration: none; background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-<?php echo $size; ?>-bg-<?php echo $imgn; ?>.png) no-repeat top left; background-position: 0px -66px;  transition: none !important; -moz-transition: none !important; -webkit-transition: none !important;}
.wpsslstats_<?php echo $wpss_wid_inst; ?> a:active { text-decoration: none; background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spamshield-counter-<?php echo $size; ?>-bg-<?php echo $imgn; ?>.png) no-repeat top left;  background-position: 0px -132px; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpsslstats_<?php echo $wpss_wid_inst; ?> .wpsslcount_<?php echo $wpss_wid_inst; ?> { color: #FFF !important; display: block; font-family: Arial, Helvetica, sans-serif !important; font-weight: bold !important; font-size: <?php echo $l1_fnt_sz; ?>px; line-height: 140% !important; letter-spacing: <?php echo $l1_let_spac; ?>px !important; padding: 0 0 0 0; white-space: nowrap; }
.wpsslstats_<?php echo $wpss_wid_inst; ?> .wpsslcount2_<?php echo $wpss_wid_inst; ?> { color: #FFF !important; display: block; font-family: Arial, Helvetica, sans-serif !important; font-weight: bold !important; font-size: <?php echo $l2_fnt_sz; ?>px; line-height: 80% !important; letter-spacing: <?php echo $l2_let_spac; ?>px !important; padding: 1px 0 0 0; white-space: nowrap; }
.wpsslstats_<?php echo $wpss_wid_inst; ?> .wpsslcount3_<?php echo $wpss_wid_inst; ?> { font-family: Arial, Helvetica, sans-serif !important; font-weight: bold !important; font-size: <?php echo $l3_fnt_sz; ?>px; line-height: 70% !important; letter-spacing: <?php echo $l3_let_spac; ?>px !important; padding: 0 0 0 0; white-space: nowrap; position: relative; top: -2px; }
</style>
	<div class="wpsslstats_<?php echo $wpss_wid_inst; ?>">
		<a href="<?php echo WPSS_HOME_URL; ?>" target="_blank" rel="external" title="<?php echo $hreftitle_txt; ?>"><?php printf( __( '<strong class="wpsslcount_%1$s">%2$s</strong> <strong class="wpsslcount2_%3$s">%4$s</strong> <span class="wpsslcount3_%5$s">%6$s</span>', WPSS_PLUGIN_NAME ), $wpss_wid_inst, $count, $wpss_wid_inst, $blocked_txt, $wpss_wid_inst, $byline ); ?></a>
	</div>
<?php
			}
		echo $args['after_widget'];
		}

	}

class WP_SpamShield_End_Blog_Spam extends WP_Widget {

	function __construct() {
		parent::__construct(
			'wp_spamshield_end_blog_spam', // Base ID
			__( 'End Blog Spam', WPSS_PLUGIN_NAME ), // Name
			array(
				'description' => __( 'Let others know how they can help end blog spam.', WPSS_PLUGIN_NAME ) . ' </BLOGSPAM>', /* NEEDS TRANSLATION */
				)
			);
		}

	public function form( $instance ) {
		$title = !empty( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : __('End Blog Spam', WPSS_PLUGIN_NAME);
		$style = !empty( $instance['style'] ) ? sanitize_text_field( $instance['style'] ) : '1';
		$options = array( '1'=>__('Black', WPSS_PLUGIN_NAME), '2'=>__('Light Blue', WPSS_PLUGIN_NAME), '3'=>__('Red', WPSS_PLUGIN_NAME), '4'=>__('Dark Blue', WPSS_PLUGIN_NAME), '5'=>__('Green', WPSS_PLUGIN_NAME) );
?>
		<p>
		<label for="<?php echo $this->get_field_id('title' ); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Choose Color:', WPSS_PLUGIN_NAME); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" >
<?php
			foreach ( $options as $i => $option ) {
				echo '<option value="'.$i.'" id="'.$i.'"', $style == $i ? ' selected="selected"' : '' , ' >', $i, ' - ', $option ,'</option>';
				}
?>
		</select>
		</p>
		<p>
<?php
		foreach ( $options as $i => $option ) {
			echo '<img src="'.WPSS_PLUGIN_COUNTER_URL.'/spamshield-end-blog-spam-'.$i.'-preview.png" style="border-style:none; margin: 10px 15px 0 0; width: 170px; height: 136px" width="170" height="136" title="" alt="" />';
			}
?>
		</p>
<?php
		}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = !empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : __( 'End Blog Spam', WPSS_PLUGIN_NAME );
		$instance['style'] = !empty( $new_instance['style'] ) ? sanitize_text_field( $new_instance['style'] ) : '1';
		return $instance;
		}

	public function widget( $args, $instance ) {
		$title = !empty( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : __('End Blog Spam', WPSS_PLUGIN_NAME);
		$style	= !empty( $instance['style'] ) ? sanitize_text_field( $instance['style'] ) : '1';
		global $wpss_wid_inst;
		if ( !isset( $wpss_wid_inst ) ) { $wpss_wid_inst = 0; }
		++$wpss_wid_inst;
		$style_max = 5; $style_min = 1;
		if ( empty( $style ) || $style > $style_max || $style < $style_min ) { $style = 1; }
		$sip1c = substr(WPSS_SERVER_ADDR, 0, 1);
		$ht_x = $sip1c > '5' ? 2 : 3;
		$hreftitle_txt = WPSS_Promo_Links::promo_text($ht_x);

		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
?>
<style type="text/css">
.wpssebs_<?php echo $wpss_wid_inst; ?> { width: 140px; height: 66px; overflow: hidden; }
.wpssebs_<?php echo $wpss_wid_inst; ?> a { background: transparent; background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/end-blog-spam-b-<?php echo $style; ?>.png) no-repeat top left; background-position: 0px 0px; border-style: none; cursor: pointer; display: block; padding: none; text-align: center; text-decoration: none; width: 140px; height: 66px; padding: 8px 0 0 0; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpssebs_<?php echo $wpss_wid_inst; ?> a:hover { text-decoration: none; background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/end-blog-spam-b-<?php echo $style; ?>.png) no-repeat top left; background-position: 0px -66px; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
.wpssebs_<?php echo $wpss_wid_inst; ?> a:active { text-decoration: none; background:url(<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/end-blog-spam-b-<?php echo $style; ?>.png) no-repeat top left;  background-position: 0px -132px; transition: none !important; -moz-transition: none !important; -webkit-transition: none !important; }
</style>
	<div class="wpssebs_<?php echo $wpss_wid_inst; ?>">
		<a href="<?php echo WPSS_HOME_URL; ?>" target="_blank" rel="external" title="<?php echo $hreftitle_txt; ?>"><img src="<?php echo WPSS_PLUGIN_COUNTER_URL; ?>/spacer.gif" width="140" height="66" title="<?php echo $hreftitle_txt; ?>" alt="<?php echo $hreftitle_txt; ?>" /></a>
	</div>
<?php
		echo $args['after_widget'];
		}

	}

class WPSS_Old_Counters {
	/* Old counter functions */

	public static function counter_short( $atts = array() ) {
		if ( rs_wpss_is_admin_sproc() ) { return NULL; }
		global $wpss_wid_inst;
		if ( !isset( $wpss_wid_inst ) ) { $wpss_wid_inst = 0; }
		++$wpss_wid_inst;
		$counter_option = $atts['style'];
		$counter_option_max = 9;
		$counter_option_min = 1;
		$counter_spam_blocked_msg = __( 'spam blocked by WP-SpamShield', WPSS_PLUGIN_NAME );
		if ( empty( $counter_option ) || $counter_option > $counter_option_max || $counter_option < $counter_option_min ) {
			$spamshield_count = rs_wpss_number_format( rs_wpss_count() );
			$wpss_shortcode_content = '<a href="'.WPSS_HOME_URL.'" style="text-decoration:none;" target="_blank" rel="external" title="'.WPSS_Promo_Links::promo_text(11).'" >'.$spamshield_count.' '.$counter_spam_blocked_msg.'</a>'.WPSS_EOL;
			return $wpss_shortcode_content;
			}
		/***
		* Display Counter
		* Implementation: [spamshieldcounter style=1] or [spamshieldcounter] where "style" is 0-9
		***/
		$spamshield_count = !empty( $atts['spamshield_count'] ) ? $atts['spamshield_count'] : rs_wpss_number_format( rs_wpss_count() );
		$counter_div_height = array('0','66','66','66','106','61','67','66','66','106');
		$counter_count_padding_top = array('0','11','11','11','75','14','17','11','11','75');
		$wpss_shortcode_content  = '';
		$wpss_shortcode_content .= '<style type="text/css">'.WPSS_EOL;
		$wpss_shortcode_content .= '#spamshield_counter_wrap_'.$wpss_wid_inst.' {color:#ffffff;text-decoration:none;width:140px;}'.WPSS_EOL;
		$wpss_shortcode_content .= '#spamshield_counter_'.$wpss_wid_inst.' {background:url('.WPSS_PLUGIN_COUNTER_URL.'/spamshield-counter-bg-'.$counter_option.'.png) no-repeat top left;height:'.$counter_div_height[$counter_option].'px;width:140px;overflow:hidden;border-style:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;padding-top:'.$counter_count_padding_top[$counter_option].'px;}'.WPSS_EOL;
		$wpss_shortcode_content .= '</style>'.WPSS_EOL;
		$wpss_shortcode_content .= '<div id="spamshield_counter_wrap_'.$wpss_wid_inst.'" >'.WPSS_EOL;
		$wpss_shortcode_content .= "\t".'<div id="spamshield_counter_'.$wpss_wid_inst.'" >'.WPSS_EOL;
		$sip1c = substr(WPSS_SERVER_ADDR, 0, 1);
		if ( ( $counter_option >= 1 && $counter_option <= 3 ) || ( $counter_option >= 7 && $counter_option <= 8 ) ) {
			$spamshield_counter_title_text = $sip1c > '5' ? WPSS_Promo_Links::promo_text(2) : WPSS_Promo_Links::promo_text(3);
			$wpss_shortcode_content .= "\t".'<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_URL.'" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" target="_blank" rel="external" title="'.$spamshield_counter_title_text.'" >'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:20px !important;line-height:80% !important;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:14px !important;line-height:130% !important;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.WPSS_Promo_Links::promo_text(0).'</span><br />'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:9px !important;line-height:90% !important;letter-spacing:1px;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.WPSS_Promo_Links::promo_text(1).'</span>'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'</a></strong>';
			}
		elseif ( $counter_option == 4 || $counter_option == 9 ) {
			if ( $sip1c > '5' ) { $spamshield_counter_title_text = WPSS_Promo_Links::promo_text(4); }
			else { $spamshield_counter_title_text = WPSS_Promo_Links::promo_text(5); }
			$wpss_shortcode_content .= "\t".'<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_URL.'" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" target="_blank" rel="external" title="'.$spamshield_counter_title_text.'" >'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'<span style="color:#000000;font-size:9px;line-height:100%;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.' '.WPSS_Promo_Links::promo_text(0).'</span><br />'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'</a></strong>'.WPSS_EOL;
			}
		elseif ( $counter_option == 5 ) {
			$wpss_shortcode_content .= "\t".'<strong style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_URL.'" style="color:#FEB22B;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" target="_blank" rel="external" title="'.WPSS_Promo_Links::promo_text(6).'" >'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'<span style="color:#FEB22B;font-size:14px !important;line-height:100% !important;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'</a></strong>'.WPSS_EOL;
			}
		elseif ( $counter_option == 6 ) {
			if ( $sip1c > '5' ) { $spamshield_counter_title_text = "\t".''.WPSS_Promo_Links::promo_text(7).WPSS_EOL; }
			else { $spamshield_counter_title_text = "\t".''.WPSS_Promo_Links::promo_text(8).WPSS_EOL; }
			$wpss_shortcode_content .= "\t".'<strong style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100% !important;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_URL.'" style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" target="_blank" rel="external" title="'.$spamshield_counter_title_text.'" >'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'<span style="color:#000000;font-size:14px !important;line-height:100% !important;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'</a></strong>'.WPSS_EOL;
			}
		$wpss_shortcode_content .= "\t".'</div>'.WPSS_EOL;
		$wpss_shortcode_content .= '</div>'.WPSS_EOL;
		return $wpss_shortcode_content;
		}

	public static function counter_sm_short( $atts = array() ) {
		if ( rs_wpss_is_admin_sproc() ) { return NULL; }
		global $wpss_wid_inst;
		if ( !isset( $wpss_wid_inst ) ) { $wpss_wid_inst = 0; }
		++$wpss_wid_inst;
		$counter_sm_option = $atts['style'];
		$counter_sm_option_max = 5;
		$counter_sm_option_min = 1;
		if ( empty( $counter_sm_option ) || $counter_sm_option > $counter_sm_option_max || $counter_sm_option < $counter_sm_option_min ) { $counter_sm_option = 1; }
		/***
		* Display Small Counter
		* Implementation: [spamshieldcountersm style=1] or [spamshieldcountersm] where "style" is 1-5
		***/
		$spamshield_count = !empty( $atts['spamshield_count'] ) ? $atts['spamshield_count'] : rs_wpss_number_format( rs_wpss_count() );
		$counter_sm_div_height = array('0','50','50','50','50','50');
		$counter_sm_count_padding_top = array('0','11','11','11','11','11');
		$wpss_shortcode_content  = '';
		$wpss_shortcode_content .= WPSS_EOL.WPSS_EOL;
		$wpss_shortcode_content .= '<style type="text/css">'.WPSS_EOL;
		$wpss_shortcode_content .= '#rs_wpss_counter_sm_wrap_'.$wpss_wid_inst.' {color:#ffffff;text-decoration:none;width:120px;}'.WPSS_EOL;
		$wpss_shortcode_content .= '#rs_wpss_counter_sm_'.$wpss_wid_inst.' {background:url('.WPSS_PLUGIN_COUNTER_URL.'/spamshield-counter-sm-bg-'.$counter_sm_option.'.png) no-repeat top left;height:'.$counter_sm_div_height[$counter_sm_option].'px;width:120px;overflow:hidden;border-style:none;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;padding-top:'.$counter_sm_count_padding_top[$counter_sm_option].'px;}'.WPSS_EOL;
		$wpss_shortcode_content .= '</style>'.WPSS_EOL.WPSS_EOL;
		$wpss_shortcode_content .= '<div id="rs_wpss_counter_sm_wrap_'.$wpss_wid_inst.'" >'.WPSS_EOL."\t";
		$wpss_shortcode_content .= '<div id="rs_wpss_counter_sm_'.$wpss_wid_inst.'" >'.WPSS_EOL;
		$sip1c = substr(WPSS_SERVER_ADDR, 0, 1);
		if ( ( $counter_sm_option >= 1 && $counter_sm_option <= 5 ) ) {
			if ( $sip1c > '5' ) { $spamshield_counter_title_text = WPSS_Promo_Links::promo_text(9); }
			else { $spamshield_counter_title_text = WPSS_Promo_Links::promo_text(10); }
			$wpss_shortcode_content .= "\t".'<strong style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;line-height:100%;text-align:center;text-decoration:none;border-style:none;"><a href="'.WPSS_HOME_URL.'" style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;" target="_blank" rel="external" title="'.$spamshield_counter_title_text.'" >'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:18px !important;line-height:100% !important;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.$spamshield_count.'</span><br />'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'<span style="color:#ffffff;font-size:10px !important;line-height:120% !important;letter-spacing:1px;font-family:Arial,Helvetica,sans-serif;font-weight:bold;text-decoration:none;border-style:none;">'.WPSS_Promo_Links::promo_text(0).'</span>'.WPSS_EOL;
			$wpss_shortcode_content .= "\t".'</a></strong>'.WPSS_EOL;
			}
		$wpss_shortcode_content .= "\t".'</div>'.WPSS_EOL;
		$wpss_shortcode_content .= '</div>'.WPSS_EOL;
		return $wpss_shortcode_content;
		}

	}
