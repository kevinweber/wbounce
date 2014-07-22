<?php
/**
 * register_activation_hook() and register_deactivation_hook() MUST NOT be called with action 'plugins_loaded' or any 'admin_init'
 * @package Admin
 */

class Wbounce_Register {

	function __construct() {
		require_once( 'inc/define.php' );
		register_activation_hook( WBOUNCE_FILE, array( $this, 'plugin_activation' ) );
		register_deactivation_hook( WBOUNCE_FILE, array( $this, 'plugin_deactivation' ) );
		add_action( 'admin_notices', array( $this, 'plugin_notice_activation' ) );
	}

	function plugin_activation() {
		$signup = '<div id="mc_embed_signup">
				<form action="'.WBOUNCE_NEWS_ACTION_URL.'" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<div class="mc-field-group">
						<label for="mce-EMAIL" style="line-height:2.5em">'.WBOUNCE_NEWS_TEXT.'</label><br>
						<input type="email" value="Enter your email address" name="EMAIL" class="required email" id="mce-EMAIL" onclick="this.focus();this.select()" onfocus="if(this.value == \'\') { this.value = this.defaultValue; }" onblur="if(this.value == \'\') { this.value = this.defaultValue; }">
						<input type="hidden" name="GROUPS" id="GROUPS" value="'.WBOUNCE_NEWS_GROUP.'" />
						<input type="submit" value="'.WBOUNCE_NEWS_BUTTON.'" name="subscribe" id="mc-embedded-subscribe" class="button">
					</div>
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>
				    <div style="position: absolute; left: -5000px;"><input type="text" name="'.WBOUNCE_NEWS_NAME.'" tabindex="-1" value=""></div>
				</form>
				</div>';


		$notices = get_option( WBOUNCE_OPTION_KEY.'_deferred_admin_notices', array() );
		$notices[] = $signup . '<br>Edit your plugin settings: <strong>
						<a href="options-general.php?page='.WBOUNCE_OPTION_KEY.'.php">'.WBOUNCE_PLUGIN_NAME.'</a>
						</strong>';
					;
		update_option( WBOUNCE_OPTION_KEY.'_deferred_admin_notices', $notices );
	}

	function plugin_deactivation() {
		delete_option( WBOUNCE_OPTION_KEY.'_deferred_admin_notices' );
		delete_option( WBOUNCE_VERSION_KEY );
	}

	/**
	 * Display notification when plugin is activated
	 */
	function plugin_notice_activation() {
	  if ( $notices = get_option( WBOUNCE_OPTION_KEY.'_deferred_admin_notices' ) ) {
	    foreach ($notices as $notice) {
	      echo "<div class='updated'><p>$notice</p></div>";
	    }
	    delete_option( WBOUNCE_OPTION_KEY.'_deferred_admin_notices' );
	  }
	}
}

new Wbounce_Register();