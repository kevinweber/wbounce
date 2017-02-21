<?php
/*
Plugin Name: wBounce
Plugin URI: http://kevinw.de/wbounce/
Description: wBounce improves bounce rate to boost conversions and sales. The free alternative to Bounce Exchange for WordPress.
Version: 1.6.3
Author: Kevin Weber
Author URI: http://kevinw.de/
License: MIT
Text Domain: wbounce
Domain Path: /languages
*/
if ( !defined( 'WBOUNCE_OPTION_KEY' ) ) {
	define( 'WBOUNCE_OPTION_KEY', 'wbounce' );
}

if (!defined('WBOUNCE_VERSION_NUM'))
    define('WBOUNCE_VERSION_NUM', '1.6.3');
if (!defined('WBOUNCE_VERSION_KEY'))
    define('WBOUNCE_VERSION_KEY', WBOUNCE_OPTION_KEY.'_version');
// Store the plugin version for upgrades
add_option( WBOUNCE_VERSION_KEY, WBOUNCE_VERSION_NUM );


if ( !defined( 'WBOUNCE_PLUGIN_NAME' ) ) {
	define( 'WBOUNCE_PLUGIN_NAME', 'wBounce' );
}

if ( !defined( 'WBOUNCE_TD' ) ) {
	define( 'WBOUNCE_TD', 'wbounce' ); // = text domain (used for translations)
}

if ( !defined( 'WBOUNCE_FILE' ) ) {
	define( 'WBOUNCE_FILE', __FILE__ );
}

if ( !defined( 'WBOUNCE_PATH' ) )
	define( 'WBOUNCE_PATH', plugin_dir_path( __FILE__ ) );


require_once( WBOUNCE_PATH . 'admin/class-register.php' );
require_once( WBOUNCE_PATH . 'admin/inc/class-setup.php' );


////////////////////////////////////////////////////////////////////////////////
/**
 * Framework by Kevin Weber (kevinw.de)
 */
$kevinw_framework_setup_arr = array(
		'option_page_url_name' => WBOUNCE_OPTION_KEY,
		'option_key' => WBOUNCE_OPTION_KEY,
		'version_current' => WBOUNCE_VERSION_NUM
	);
// Don't edit the following code //
if ( !defined( 'KEVINW_FRAMEWORK' ) )
	define( 'KEVINW_FRAMEWORK', true );
if ( defined( 'KEVINW_FRAMEWORK' ) ) {
	if ( is_admin() ) {
		require_once( 'kevinw_framework/class-kevinw-admin.php' );
		$kevinw_admin_init = new Kevinw_Admin( $kevinw_framework_setup_arr );
	}
}
////////////////////////////////////////////////////////////////////////////////


class Wbounce_Init {
	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		if ( is_admin() ) {
			add_action( 'plugins_loaded', array( $this, 'admin_init' ), 14 );
		}
		else {
			add_action( 'plugins_loaded', array( $this, 'frontend_init' ), 14 );
		}
	}

	/**
	 * Load plugin textdomain.
	 * @since 1.5
	 */
	function load_textdomain() {
	  load_plugin_textdomain( WBOUNCE_TD, false, dirname( plugin_basename( WBOUNCE_FILE ) ) . '/languages/' );
	}

	function admin_init() {
		require_once( WBOUNCE_PATH . 'admin/class-admin-options.php' );
		require_once( WBOUNCE_PATH . 'admin/class-meta.php' );
	}

	function frontend_init() {
		require_once( WBOUNCE_PATH . 'frontend/class-frontend.php' );
	}
}
new Wbounce_Init();
/***** Plugin by Kevin Weber || kevinw.de *****/
?>
