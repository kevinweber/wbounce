<?php
/**
 * Create options panel (http://codex.wordpress.org/Creating_Options_Pages)
 * @package Admin
 */

class Kevinw_Admin_Options {

	private $optionKey;
	private $optionPageUrlName;

	function setOptionPageUrlName( $value ){
		$this->optionPageUrlName = $value;
	}
	function getOptionPageUrlName(){
		return $this->optionPageUrlName;
	}

	function setOptionKey( $value ){
		$this->optionKey = $value;
	}
	function getOptionKey(){
		return $this->optionKey;
	}

	function kevinw_admin_options_init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_jquery' ) );
		if ( isset( $_GET['page'] ) && ( $_GET['page'] == $this->getOptionPageUrlName().'.php' ) ) {
			add_action( 'admin_footer', array( $this, 'kevinw_admin_css' ) );
			add_action( 'admin_footer', array( $this, 'kevinw_admin_js' ) );
		}
	}

	// /**
	//  * Test if one of the option_keys matches the current page ($_GET['page']).
	//  */
	// private function kevinw_test_get_and_option_keys() {
	// 	$option = 'kevinw_framework_option_keys';
	// 	$new_value = get_option( $option );
	// 	$new_value_arr = explode(';', $new_value);

	// 	for ($i=1; $i < count($new_value_arr); $i++) { 
	// 		if ( $_GET['page'] == $new_value_arr[$i].'.php' ) return true;
	// 	};
	// 	return false;
	// }

	/**
	 * Admin JS
	 */
	function kevinw_admin_js() {
	    wp_enqueue_script( 'kevinw_admin_js', plugins_url( '../js/min/admin-ck.js' , __FILE__ ), array( 'jquery', 'jquery-ui-tabs' ) );
	}

	/**
	 * Admin CSS
	 */
	function kevinw_admin_css() {
		wp_enqueue_style( 'kevinw_admin_css', plugins_url('../css/min/admin.css', __FILE__) );
		wp_enqueue_style( 'farbtastic' );	// Required for colour picker
	}

	/**
 	 * Enable jQuery (comes with WordPress)
 	 */
 	function enqueue_jquery() {
     	wp_enqueue_script( 'jquery' );
 	}

	// /**
	//  * Update values in database.
	//  * Mostly, this function is called directly from plugin_dir_path( __FILE__ )
	//  */
	// function kevinw_framework_update_values( $arr ) {
	// 	if ( isset($arr['option_key']) ) {
	// 		$this->kevinw_framework_update_value_option_keys( $arr['option_key'] );
	// 	}
	// }
	// /**
	//  * Update 'option_key'
	//  */
	// private function kevinw_framework_update_value_option_keys( $val ) {
	// 	$option = 'kevinw_framework_option_keys';
	// 	$new_value = get_option( $option );

	// 	// if $new_value does not already contain $val then add it with a preceding semikolon (;) 
	// 	if (strpos( $new_value, $val ) === false) {
	// 		$new_value .= ';'.$val;
	// 		update_option( $option, $new_value );
	// 	}
	// }

}