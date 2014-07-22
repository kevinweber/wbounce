<?php
/*
 * Plugin Name: wBounce
 * Plugin URI: http://kevinw.de/wbounce
 * Description: More to come (based on https://github.com/carlsednaWBOUNCE/WBOUNCEbounce)
 * Author: Kevin Weber
 * Version: 0.1
 * Author URI: http://kevinw.de/
 * License: MIT
 * Text Domain: wbounce
*/

if ( !defined( 'WBOUNCE_OPTION_KEY' ) ) {
	define( 'WBOUNCE_OPTION_KEY', 'wbounce' );
}

if (!defined('WBOUNCE_VERSION_NUM'))
    define('WBOUNCE_VERSION_NUM', '0.1');
if (!defined('WBOUNCE_VERSION_KEY'))
    define('WBOUNCE_VERSION_KEY', WBOUNCE_OPTION_KEY.'_version');
// Store the plugin version for upgrades
add_option(WBOUNCE_VERSION_KEY, WBOUNCE_VERSION_NUM);


if ( !defined( 'WBOUNCE_PLUGIN_NAME' ) ) {
	define( 'WBOUNCE_PLUGIN_NAME', 'wBounce' );
}

if ( !defined( 'WBOUNCE_FILE' ) ) {
	define( 'WBOUNCE_FILE', __FILE__ );
}

if ( !defined( 'WBOUNCE_PATH' ) )
	define( 'WBOUNCE_PATH', plugin_dir_path( __FILE__ ) );

require_once( WBOUNCE_PATH . 'admin/class-register.php' );


////////////////////////////////////////////////////////////////////////////////
/**
 * Framework by Kevin Weber (kevinw.de)
 */
$kevinw_framework_setup_arr = array(
		'option_page_url_name' => WBOUNCE_OPTION_KEY,
		'option_key' => WBOUNCE_OPTION_KEY
	);
// Don't edit the following code //
if ( !defined( 'KEVINW_FRAMEWORK' ) )
	define( 'KEVINW_FRAMEWORK', true );
if ( defined( 'KEVINW_FRAMEWORK' ) ) {
	if ( is_admin() ) {
		require_once( 'kevinw_framework/class-kevinw-admin.php' );
		$kevinw_admin_init = new Kevinw_Admin();
		$kevinw_admin_init->setup_admin_options( $kevinw_framework_setup_arr );
	}
}
////////////////////////////////////////////////////////////////////////////////


class Wbounce_Init {
	function __construct() {
		if ( is_admin() ) {
			add_action( 'plugins_loaded', array( $this, 'admin_init' ), 14 );
		}
		else {
			add_action( 'plugins_loaded', array( $this, 'frontend_init' ), 14 );
		}
	}
	function admin_init() {
		require_once( WBOUNCE_PATH . 'admin/class-admin-options.php' );
	}

	function frontend_init() {
		require_once( WBOUNCE_PATH . 'frontend/class-frontend.php' );
	}
}
new Wbounce_Init();


/***** Plugin by Kevin Weber || kevinw.de *****/