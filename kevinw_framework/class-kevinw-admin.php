<?php
/*
 * Framework by Kevin Weber (kevinw.de) - follow @kevinweber
 * Version: 0.1
*/
class Kevinw_Admin {
	function __construct() {
		require_once( 'admin/class-admin-options.php' );
	}

	function setup_admin_options( $arr ) {
		$option_optionPageUrlName = 'option_page_url_name';
		$option_optionKey = 'option_key';

		$kevinw_admin_options = new Kevinw_Admin_Options();

		// $option_optionPageUrlName
		if (isset( $arr[$option_optionPageUrlName] ))
			$kevinw_admin_options->setOptionPageUrlName( $arr[$option_optionPageUrlName] );
		
		$kevinw_admin_options->kevinw_admin_options_init();
	}
}