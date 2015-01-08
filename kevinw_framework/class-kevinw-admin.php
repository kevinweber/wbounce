<?php
/*
 * Framework by Kevin Weber (kevinw.de) - follow @kevinweber
 * Version: 0.1
*/
class Kevinw_Admin {

	function __construct( $arr ) {
		$this->setup_admin_options( $arr );
	}

	function setup_admin_options( $arr ) {
		$option_optionPageUrlName = 'option_page_url_name';
		$option_optionKey = 'option_key';
		$option_versionCurrent = 'version_current';

		require_once( 'admin/class-admin-options.php' );
		$kevinw_admin_options = new Kevinw_Admin_Options();

		// $option_optionPageUrlName
		if (isset( $arr[$option_optionPageUrlName] ))
			$kevinw_admin_options->setOptionPageUrlName( $arr[$option_optionPageUrlName] );

		// $option_versionCurrent
		if (isset( $arr[$option_versionCurrent] ))
			$kevinw_admin_options->setVersionCurrent( $arr[$option_versionCurrent] );
		
		$kevinw_admin_options->kevinw_admin_options_init();
	}
}