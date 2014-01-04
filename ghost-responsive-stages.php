<?php
/*
Plugin Name: Ghost Responsive Stages
Plugin URI: http://ghosttoa.st
Description: Shows responsive stages flag for seeing when transitions should occur
Version: 1.0
Author: Gustave Gerhardt
Author URI: http://ghosttoa.st
License: GPL2
*/

class ghost_stages {
	
	public function __construct(){
		add_action('admin_menu', array($this, 'add_settings_page'));
		add_action('admin_init', array($this, 'settings_init'));
	}
	
	/**
	* add settings
	*/
	public function add_settings_page(){
		$settings = add_submenu_page(
			'tools.php',
			__( 'Ghost Stages Options' ),
			__( 'Ghost Stages' ),
			'manage_options',
			'ghost-stages-options',
			array( $this, 'settings_page_content' )
		);
		
		//We use the provided hook_suffix that's returned to add our styles and scripts only on our settings page.
		add_action( 'load-' . $settings, array($this, 'add_styles_scripts' ));
	}
	
	/**
	* Build the basic structure for the settings page including the form, fields, and submit button.
	*/
	public function settings_page_content(){
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Ghost Stages Options' ); ?></h2>
		
		<form id="ghost_stages_options" action="options.php" method="post">
			<?php
				settings_fields( 'ghost_stages_options' ); 
				do_settings_sections( 'ghost-stages-color-picker-settings' ); 
				do_settings_sections( 'ghost-stages-other-options' );
			?>
			<p class="submit">
				<input id="ghost_stages_submit" name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
			</p>
		</form>
	</div>
	<?php
	}  
	
	/**
	* Register settings, add a settings section, and add our single color field.
	*/
	function settings_init(){    
		register_setting(
			'ghost_stages_options',
			'ghost_stages_options',
			array( $this, 'ghost_stages_validate_options' )
		);
		
		add_settings_section(
			'ghost-color-picker-section',
			__( 'Choose Your Color' ),
			array( $this, 'ghost_stages_color_options_settings_text' ),
			'ghost-stages-color-picker-settings'
		);
		
		add_settings_field(
			'ghost_stages_box_color',
			__( 'Box Color' ),
			array( $this, 'ghost_stages_box_color_input' ),
			'ghost-stages-color-picker-settings',
			'ghost-color-picker-section'
		);   
		
		add_settings_field(
			'ghost_stages_font_color',
			__( 'Font Color' ),
			array( $this, 'ghost_stages_font_color_input' ),
			'ghost-stages-color-picker-settings',
			'ghost-color-picker-section'
		);  
		
		add_settings_section(
			'ghost-other-options-section',
			__( 'Other Options' ),
			array( $this, 'ghost_stages_other_options_settings_text' ),
			'ghost-stages-other-options'
		);
		
		add_settings_field(
			'ghost_stages_corner',
			__( 'Choose a Corner' ),
			array( $this, 'ghost_stages_corner_input' ),
			'ghost-stages-other-options',
			'ghost-other-options-section'
		);  
		
		add_settings_field(
			'ghost_stages_admin_display',
			__( 'Admin Only?' ),
			array( $this, 'ghost_stages_admin_only' ),
			'ghost-stages-other-options',
			'ghost-other-options-section'
		);  
		
	}
	
	// help text
	function ghost_stages_color_options_settings_text(){
		echo '<p>' . __( 'Use the color pickers below to choose the <strong>box</strong> and <strong>font</strong> colors of the responsive stage tester.' ) . '</p>';
	}
	
	// more help text
	function ghost_stages_other_options_settings_text(){
		echo '<p>' . __( 'Select a corner for the responsive tester to appear in, as well as whether to hide the responsive tester to non-administrators.' ) . '</p>';
	}
	
	/**
	* display color fields
	*/
	function ghost_stages_box_color_input(){
		$options = get_option( 'ghost_stages_options' );
		$box_color = ( $options['ghost_box_color'] != "" ) ? sanitize_text_field( $options['ghost_box_color'] ) : '#990000';
		
		echo '<input id="ghost_box_color" name="ghost_stages_options[ghost_box_color]" type="text" value="' . $box_color .'" />';
		// farbtastic uses this if necessary, otherwise it is left empty.
		echo '<div id="ghost_box_color"></div>'; 
	}
	
	function ghost_stages_font_color_input(){
		$options = get_option( 'ghost_stages_options' );
		$font_color = ( $options['ghost_font_color'] != "" ) ? sanitize_text_field( $options['ghost_font_color'] ) : '#ffffff';
		
		echo '<input id="ghost_font_color" name="ghost_stages_options[ghost_font_color]" type="text" value="' . $font_color .'" />';
		
		// farbtastic uses this if necessary, otherwise it is left empty.
		echo '<div id="ghost_font_color"></div>'; 
	}
	
	function ghost_stages_corner_input(){
		$options = get_option( 'ghost_stages_options' );
		$corner = ( $options['ghost_stages_corner'] != "" ) ? $options['ghost_stages_corner'] : 'upper_right';
		$select = ' selected="selected"';
		?>
		
		<select id="ghost_stages_corner" name="ghost_stages_options[ghost_stages_corner]">
			<option<?php if($corner == 'upper_right') echo $select; ?> value="upper_right">Upper Right</option>
			<option<?php if($corner == 'lower_right') echo $select; ?> value="lower_right">Lower Right</option>
			<option<?php if($corner == 'lower_left' ) echo $select; ?> value="lower_left" >Lower Left</option>
			<option<?php if($corner == 'upper_left' ) echo $select; ?> value="upper_left" >Upper Left</option>
		</select>
		
		<?php
	}
	
	function ghost_stages_admin_only() {
		$options = get_option( 'ghost_stages_options' );
		$admin_only = ( $options['ghost_stages_admin_only'] != '' ) ? $options['ghost_stages_admin_only'] : 'yes';
		$select = ' selected="selected"';
		?>
		
		<select id="ghost_stages_admin_only" name="ghost_stages_options[ghost_stages_admin_only]">
			<option<?php if($admin_only == 'yes') echo $select; ?> value="yes">Yes</option>
			<option<?php if($admin_only == 'no' ) echo $select; ?> value="no">No</option>
		</select>
		
		<?php
	}
	
	/**
	* validate the submitted fields.
	*/
	function ghost_stages_validate_options( $input ){
		$valid = array();
		$valid['ghost_box_color'] = sanitize_text_field( $input['ghost_box_color'] );
		$valid['ghost_font_color'] = sanitize_text_field( $input['ghost_font_color'] );
		$valid['ghost_stages_corner'] = $input['ghost_stages_corner'];
		$valid['ghost_stages_admin_only'] = $input['ghost_stages_admin_only'];
		return $valid;
	}
	
	
	/**
	* add color picker styles and scripts
	*/
	function add_styles_scripts(){
		global $wp_version;
		// check version, fallback to farbastic if old		
		if ( 3.5 <= $wp_version ){
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
		} else {
			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_script( 'farbtastic' );
		}

		wp_enqueue_script( 'ghost_stages_settings', plugin_dir_url(__FILE__) . 'color-picker.js' );
	}
}

// load !!
if( is_admin() ){
	$wp_color_picker = new ghost_stages;
}




add_action('wp_enqueue_scripts', 'ghost_stages_styles');
add_action('wp_head', 'add_ghost_responsive_stages');

// load styles
function ghost_stages_styles(){
	$options = get_option('ghost_stages_options');
	
	// do not show for admin!
	if(isset($options['ghost_stages_admin']) && $options['ghost_stages_admin'] == 'yes'){
		return;
	}

	wp_enqueue_style('ghost_responsive_stages_css', plugins_url('ghost-responsive-stages.css', __FILE__));

	$custom_css = '.ghost-responsive-tester {';
	
	if($options['ghost_box_color']){
		$custom_css .= 'background: '.$options['ghost_box_color'].';';
	}
	if($options['ghost_font_color']){
		$custom_css .= 'color: '.$options['ghost_font_color'].';';
	}
	
	switch ($options['ghost_stages_corner']) {
		case 'lower_right':
			$custom_css .= 'bottom:30px; top:auto;';
			break;
		
		case 'lower_left':
			$custom_css .= 'bottom:30px; left:25px; top:auto; right: auto;';
			break;
			
		case 'upper_left':
			$custom_css .= 'left:25px; right:auto;';
			break;
		
	}
	
	$custom_css .= '}';
	
	wp_add_inline_style( 'ghost_responsive_stages_css', $custom_css);
}

// display the stages tester
function add_ghost_responsive_stages(){
	
	$options = get_option('ghost_stages_options');
	// do not show for admin!
	if($options['ghost_stages_admin_only'] == 'yes' && !current_user_can('administrator')){
		return;
	}

	$html  = '<div class="ghost-responsive-tester">';
	$html .= '<div class="ghost-snap-one">1</div>';
	$html .= '<div class="ghost-snap-two">2</div>';
	$html .= '<div class="ghost-snap-three">3</div>';
	$html .= '<div class="ghost-snap-four">4</div>';
	$html .= '</div>';
	
	echo $html;
}

?>
