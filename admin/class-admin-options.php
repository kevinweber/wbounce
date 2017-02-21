<?php
/**
 * @package Admin
 */
class Wbounce_Admin_Options {

	private $animationOptions = array(
		'open' => array(
			'None' => array('none'),
			'Attention Seekers' => array('bounce', 'flash', 'pulse', 'rubberBand', 'shake', 'swing', 'tada', 'wobble', 'jello'),
			'Bouncing Entrances' => array('bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp'),
			'Fading Entrances' => array('fadeIn', 'fadeInDown', 'fadeInDownBig', 'fadeInLeft', 'fadeInLeftBig', 'fadeInRight', 'fadeInRightBig', 'fadeInUp', 'fadeInUpBig'),
			'Flippers' => array('flip', 'flipInX', 'flipInY'),
			'Lightspeed' => array('lightSpeedIn'),
			'Rotating Entrances' => array('rotateIn', 'rotateInDownLeft', 'rotateInDownRight', 'rotateInUpLeft', 'rotateInUpRight'),
			'Sliding Entrances' => array('slideInUp', 'slideInDown', 'slideInLeft', 'slideInRight'),
			'Zoom Entrances' => array('zoomIn', 'zoomInDown', 'zoomInLeft', 'zoomInRight', 'zoomInUp'),
			'Specials' => array('hinge', 'rollIn')
		),
		'exit' => array(
			'None' => array('none'),
			'Attention Seekers' => array('bounce', 'flash', 'pulse', 'rubberBand', 'shake', 'swing', 'tada', 'wobble', 'jello'),
			'Bouncing Exits' => array('bounceOut', 'bounceOutDown', 'bounceOutLeft', 'bounceOutRight', 'bounceOutUp'),
			'Fading Exits' => array('fadeOut', 'fadeOutDown', 'fadeOutDownBig', 'fadeOutLeft', 'fadeOutLeftBig', 'fadeOutRight', 'fadeOutRightBig', 'fadeOutUp', 'fadeOutUpBig'),
			'Flippers' => array('flip', 'flipOutX', 'flipOutY'),
			'Lightspeed' => array('lightSpeedOut'),
			'Rotating Exits' => array('rotateOut', 'rotateOutDownLeft', 'rotateOutDownRight', 'rotateOutUpLeft', 'rotateOutUpRight'),
			'Sliding Exits' => array('slideOutUp', 'slideOutDown', 'slideOutLeft', 'slideOutRight'),
			'Zoom Exits' => array('zoomOut', 'zoomOutDown', 'zoomOutLeft', 'zoomOutRight', 'zoomOutUp'),
			'Specials' => array('hinge', 'rollOut')
		)
	);

	function __construct() {
		add_action( 'admin_menu', array( $this, 'create_menu' ));
		add_action( 'admin_init', array( $this, 'admin_init_options' ) );
	}

	function admin_init_options() {
		$plugin = plugin_basename( WBOUNCE_FILE );
		add_filter("plugin_action_links_$plugin", array( $this, 'settings_link' ) );
		$this->register_settings();
		$this->admin_js();
	}

	function admin_js() {
		wp_enqueue_script( 'wbounce_backend_admin_js', plugins_url( 'js/wbounce-backend.js' , __FILE__ ), array( 'jquery' ) );
	}

	/**
	 * Add settings link on plugin page
	 */
	function settings_link($links) {
	  $settings_link = '<a href="options-general.php?page='.WBOUNCE_OPTION_KEY.'.php">' . __( 'Settings', WBOUNCE_TD ) . '</a>';
	  array_unshift($links, $settings_link);
	  return $links;
	}

	function create_menu() {
		add_options_page(WBOUNCE_PLUGIN_NAME, WBOUNCE_PLUGIN_NAME, 'manage_options', WBOUNCE_OPTION_KEY.'.php', array( $this, 'settings_page'));
	}

	function register_settings() {
		$arr = array(	// Use these options like this: WBOUNCE_OPTION_KEY.'_content'
			// Tab 'Content'
			'_test_mode',
			'_status_default',
			'_template_engine',
			'_content',
            '_attribution',
			// Tab 'Options'
			'_aggressive_mode',
			'_autofire',
			'_timer',
			'_hesitation',
			'_cookieexpire',
			'_sitewide',
			'_cookiedomain',
			'_sensitivity',
			'_load_in_footer',
			// Tab 'Styling'
			'_custom_css',
			'_open_animation',
			'_exit_animation',
			'_demo_css',
			// Tab 'Analytics'
			'_analytics',
			//... more to come
		);
		foreach ( $arr as $i ) {
			register_setting( WBOUNCE_OPTION_KEY.'-settings-group', WBOUNCE_OPTION_KEY.$i );
		}
		do_action( WBOUNCE_OPTION_KEY.'_register_settings_after' );
	}

	function settings_page() { ?>

		<style>
			.button.button-monster {background:#78ac06;border-color:#78ac06;height:auto;text-align:center;font-size:1.2em;padding:9px;box-shadow:none;}
			.button.button-monster:hover, .button.button-monster:active, .button.button-monster:focus {box-shadow:none;background-color:#6E9D06;border-color:#6E9D06;}
		</style>

		<div id="tabs" class="ui-tabs">
			<?php
				printf( '<h2>' . WBOUNCE_PLUGIN_NAME . '<span class="subtitle">' . __( 'by <a href="%1$s" target="_blank" title="Website by Kevin Weber">Kevin Weber</a> (version %2$s) with best thanks to <a href="%3$s" target="_blank" title="Ouibounce by Carl Sednaoui">Ouibounce by Carl Sednaoui</a>', WBOUNCE_TD ) . '</span></h2>',
					'//kevinw.de/wb',
					WBOUNCE_VERSION_NUM,
					'//github.com/carlsednaoui/ouibounce'
				);
			?>

			<ul class="ui-tabs-nav">
		        <li><a href="#content"><?php esc_html_e( 'Content', WBOUNCE_TD ); ?></a></li>
		        <li><a href="#options"><?php esc_html_e( 'Options', WBOUNCE_TD ); ?></a></li>
		        <li><a href="#styling"><?php esc_html_e( 'Styling', WBOUNCE_TD ); ?> <span class="newred_dot">&bull;</span></a></li>
		        <li><a href="#analytics"><?php esc_html_e( 'Analytics', WBOUNCE_TD ); ?></a></li>
		    	<?php do_action( WBOUNCE_OPTION_KEY.'_settings_page_tabs_link_after' ); ?>
		    </ul>


			<form method="post" action="options.php">
			    <?php settings_fields( WBOUNCE_OPTION_KEY.'-settings-group' ); ?>
			    <?php do_settings_sections( WBOUNCE_OPTION_KEY.'-settings-group' ); ?>

			    <div id="content">

					<h3><?php esc_html_e( 'Content', WBOUNCE_TD ); ?></h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
						        <th scope="row"><?php esc_html_e( 'Test mode', WBOUNCE_TD ); ?></th>
						        <td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_test_mode" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_test_mode' ) ); ?> /> <label><?php _e( 'Check this option to enable "Aggressive Mode" <b>for admins</b>. If this option is checked, the popup will <b>always</b> fire for you (regardless of the actual setting in the tab "Options") but not for your regular visitors.', WBOUNCE_TD ); ?></label>
						        </td>
					        </tr>
					        <tr valign="top">
					        	<th scope="row"><?php esc_html_e( 'Default status', WBOUNCE_TD ); ?></th>
						        <td>
									<select class="select" typle="select" name="<?php echo WBOUNCE_OPTION_KEY; ?>_status_default">
								    	<option value="on"<?php if (get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on') { echo ' selected="selected"'; } ?>><?php esc_html_e( 'Always fire', WBOUNCE_TD ); ?></option>
								    	<option value="on_posts"<?php if (get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_posts') { echo ' selected="selected"'; } ?>><?php esc_html_e( 'Fire on posts', WBOUNCE_TD ); ?></option>
		     							<option value="on_pages"<?php if (get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_pages') { echo ' selected="selected"'; } ?>><?php esc_html_e( 'Fire on pages', WBOUNCE_TD ); ?></option>
		     							<option value="on_posts_pages"<?php if (get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_posts_pages') { echo ' selected="selected"'; } ?>><?php esc_html_e( 'Fire on posts and pages', WBOUNCE_TD ); ?></option>
		     							<option value="off"<?php if (get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'off') { echo ' selected="selected"'; } ?>><?php esc_html_e( 'Don&#39;t fire', WBOUNCE_TD ); ?></option>
		     						</select>
									<p><?php esc_html_e( 'Define if wBounce should be fired on posts and/or pages by default. You can override the default setting on every post and page individually.', WBOUNCE_TD ); ?></p>
						        </td>
					        </tr>
					        <tr valign="top">
					        	<th scope="row"><?php esc_html_e( 'Template Engine', WBOUNCE_TD ); ?><br>
					        	<span class="description thin">
					        	<?php
						        	printf( __( 'See <a href="%s" target="_blank" title="wBounce Documentation">documentation</a>.', WBOUNCE_TD ),
						        	'//kevinw.de/wb-doc-te'
						        	); ?>
						        </span>
                                </th>
						        <td>
									<select class="select" typle="select" name="<?php echo WBOUNCE_OPTION_KEY; ?>_template_engine">
								    	<option value="enabled"<?php if (get_option(WBOUNCE_OPTION_KEY.'_template_engine') === 'enabled') { echo ' selected="selected"'; } ?>><?php esc_html_e( 'Enabled', WBOUNCE_TD ); ?></option>
								    	<option value="disabled"<?php if (get_option(WBOUNCE_OPTION_KEY.'_template_engine') === 'disabled') { echo ' selected="selected"'; } ?>><?php esc_html_e( 'Disabled (No Override)', WBOUNCE_TD ); ?></option>
		     						</select>
							        <p>
								        <?php
								        	printf( __( 'When you use this feature, please <a href="%1$s" target="_blank">donate</a> and give this plugin a <a href="%2$s" target="_blank">5 star rating</a>.', WBOUNCE_TD ),
												'//kevinw.de/donate/wBounce/',
												'//wordpress.org/support/view/plugin-reviews/wbounce?filter=5'
								        	);
								        ?>
								    </p>
						        </td>
					        </tr>
					        <tr valign="top">
					        	<th scope="row"><?php _e( 'wBounce content <span class="description thin"><br>Add code that should be displayed within the wBounce window.</span>', WBOUNCE_TD ); ?></th>
					        	<td>
					        		<textarea rows="14" cols="70" type="text" name="<?php echo WBOUNCE_OPTION_KEY; ?>_content" placeholder="<?php esc_html_e( 'Exemplary template below.', WBOUNCE_TD ); ?>"><?php echo get_option(WBOUNCE_OPTION_KEY.'_content'); ?></textarea>
					        		<span>

					        			<?php esc_html_e( 'Exemplary template:', WBOUNCE_TD ); ?><br>
<pre>
&lt;div class=&quot;modal-title&quot;&gt;
  &lt;h3&gt;<?php esc_html_e( 'Title', WBOUNCE_TD ); ?>&lt;/h3&gt;
&lt;/div&gt;

&lt;div class=&quot;modal-body&quot;&gt;
  &lt;p&gt;<?php esc_html_e( 'Paragraph', WBOUNCE_TD ); ?>&lt;/p&gt;

  &lt;form&gt;
    &lt;input type=&quot;email&quot; placeholder=&quot;<?php esc_html_e( 'you@email.com', WBOUNCE_TD ); ?>&quot;&gt;
    &lt;input type=&quot;submit&quot; value=&quot;<?php esc_html_e( 'learn more &raquo;', WBOUNCE_TD ); ?>&quot;&gt;
    &lt;p class=&quot;form-notice&quot;&gt;<?php esc_html_e( '*this is a fake form', WBOUNCE_TD ); ?>&lt;/p&gt;
  &lt;/form&gt;
&lt;/div&gt;

&lt;div class=&quot;modal-footer&quot;&gt;
  &lt;p&gt;<?php esc_html_e( 'no thanks', WBOUNCE_TD ); ?>&lt;/p&gt;
&lt;/div&gt;
</pre>

					        		</span>
					        	</td>
					        </tr>
					        <tr valign="top">
						        <th scope="row"><?php esc_html_e( 'Attribution', WBOUNCE_TD ); ?><br><span class="description thin"><?php esc_html_e( 'Give appropriate credit for my time-consuming efforts', WBOUNCE_TD ); ?></span></th>
						        <td>
									<?php $options = get_option( WBOUNCE_OPTION_KEY.'_attribution' ); ?>
									<input class="radio" type="radio" name="<?php echo WBOUNCE_OPTION_KEY.'_attribution'; ?>" value="none"<?php checked( 'none' == $options || empty($options) ); ?> /> <label for="none"><?php esc_html_e( 'No attribution: "I can not afford to give appropriate credit for this free plugin."', WBOUNCE_TD ); ?></label><br><br>
									<input class="radio" type="radio" name="<?php echo WBOUNCE_OPTION_KEY.'_attribution'; ?>" value="donate"<?php checked( 'donate' == $options ); ?> />
									<label for="donate">
										<?php esc_html_e( 'Donation: "I have donated already or will do so soon."', WBOUNCE_TD ); ?>
										<?php printf( esc_html__( 'Please %1$sdonate now%2$s so that I can keep up the development of this plugin.', WBOUNCE_TD ),
											'<a href="//kevinw.de/donate/wBounce/" target="_blank">',
											'</a>'
										); ?>
									</label><br>
						        </td>
					        </tr>
					    </tbody>
				    </table>

			    </div>

			    <div id="options">

					<h3><?php esc_html_e( 'Options', WBOUNCE_TD ); ?></h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
						        <th scope="row"><?php esc_html_e( 'Aggressive mode', WBOUNCE_TD ); ?></th>
						        <td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_aggressive_mode" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_aggressive_mode' ) ); ?> /> <label><?php _e( 'By default, wBounce will only fire once for each visitor. When wBounce fires, a cookie is created to ensure a non obtrusive experience.<br><br>There are cases, however, when you may want to be more aggressive. An example use-case might be on your paid landing pages. If you enable aggressive, the modal can be fired any time the page is reloaded.', WBOUNCE_TD ); ?></label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row"><?php esc_html_e( 'Self-acting fire (timer)', WBOUNCE_TD ); ?></th>
						        <td>
									<input type="number" name="<?php echo WBOUNCE_OPTION_KEY; ?>_autofire" placeholder="milliseconds" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_autofire'); ?>" /><br><label><?php esc_html_e( 'Automatically trigger the popup after a certain time period. Insert 0 to fire immediately when the page is loaded. Leave blank to not use this option.', WBOUNCE_TD ); ?></label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row"><?php _e( 'Set a min time<br><span class="description thin">&hellip; before wBounce fires.</span>', WBOUNCE_TD ); ?></th>
						        <td>
						        	<input type="number" name="<?echo WBOUNCE_OPTION_KEY; ?>_timer" placeholder="milliseconds" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_timer'); ?>" /><br><label><?php _e( 'By default, wBounce won&#39;t fire in the first second to prevent false positives, as it&#39;s unlikely the user will be able to exit the page within less than a second. If you want to change the amount of time that firing is surpressed for, you can pass in a number of milliseconds to timer.<br>Insert 0 to fire immediately.', WBOUNCE_TD ); ?></label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row"><?php esc_html_e( 'Hesitation', WBOUNCE_TD ); ?></th>
						        <td>
						        	<input type="number" name="<?php echo WBOUNCE_OPTION_KEY; ?>_hesitation" placeholder="milliseconds" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_hesitation'); ?>" /><br><label><?php _e( 'By default, wBounce will show the modal immediately when the user&#39;s cursor leaves the window. You could instead configure it to wait <i>x</i> milliseconds before showing the modal. If the cursor re-enters the body before delay ms have passed, the modal will not appear. This can be used to provide a "grace period" for visitors instead of immediately presenting the modal window.', WBOUNCE_TD ); ?></label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row"><?php esc_html_e( 'Cookie expiration', WBOUNCE_TD ); ?></th>
						        <td>
						        	<input type="number" name="<?php echo WBOUNCE_OPTION_KEY; ?>_cookieexpire" placeholder="days" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_cookieexpire'); ?>" /><br><label><?php esc_html_e( 'wBounce sets a cookie by default to prevent the modal from appearing more than once per user. You can add a cookie expiration (in days) to adjust the time period before the modal will appear again for a user. By default, the cookie will expire at the end of the session, which for most browsers is when the browser is closed entirely.', WBOUNCE_TD ); ?></label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row"><?php esc_html_e( 'Cookie per page', WBOUNCE_TD ); ?></th>
						        <td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_sitewide" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_sitewide' ) ); ?> /> <label><?php esc_html_e( 'By default, the cookie is stored for the whole site. With the "cookie per page" option enabled, every page/post gets its own cookie.', WBOUNCE_TD ); ?></label>
						        </td>
					        </tr>
					        <tr valign="top">
					        	<th scope="row"><?php esc_html_e( 'Cookie domain', WBOUNCE_TD ); ?></th>
					        	<td>
					        		<input type="text" name="<?php echo WBOUNCE_OPTION_KEY; ?>_cookiedomain" placeholder="" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_cookiedomain'); ?>" /><br><span><?php esc_html_e( 'wBounce sets a cookie by default to prevent the modal from appearing more than once per user. You can add a cookie domain to specify the domain under which the cookie should work. By default, no extra domain information will be added. If you need a cookie to work also in your subdomain (like blog.example.com and example.com), then set a cookie domain such as .example.com (notice the dot in front).', WBOUNCE_TD ); ?></span>
					        	</td>
					        </tr>
					        <tr valign="top">
						        <th scope="row"><?php _e( 'Sensitivity <span class="newred">Deprecated</span><br><span class="description thin">Feature will be removed with one of the next updates.</span>', WBOUNCE_TD ); ?></th>
						        <td>
						        	<input type="number" name="<?php echo WBOUNCE_OPTION_KEY; ?>_sensitivity" placeholder="20" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_sensitivity'); ?>" /><br><label><?php esc_html_e( 'wBounce fires when the mouse cursor moves close to (or passes) the top of the viewport. You can define how far the mouse has to be before wBounce fires. The higher value, the more sensitive, and the more quickly the event will fire. Defaults to 20.', WBOUNCE_TD ); ?></label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row"><?php esc_html_e( 'Load script in footer', WBOUNCE_TD ); ?></th>
						        <td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_load_in_footer" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_load_in_footer' ) ); ?> /> <label><?php esc_html_e( 'Normally, scripts are placed in &lt;head&gt; of the HTML document. If this parameter is true, the script is placed before the &lt;/body&gt; end tag. This requires the theme to have the wp_footer() template tag in the appropriate place.', WBOUNCE_TD ); ?></label>
						        </td>
					        </tr>
					    </tbody>
				    </table>

			    </div>

			    <div id="styling">

					<h3><?php esc_html_e( 'Styling', WBOUNCE_TD ); ?></h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
					        	<th scope="row"><?php _e( 'Custom CSS <span class="description thin"><br>Add additional CSS. This should override any other stylesheets.</span>', WBOUNCE_TD ); ?></th>
					        	<td>
					        		<textarea rows="14" cols="70" type="text" name="<?php echo WBOUNCE_OPTION_KEY; ?>_custom_css" placeholder="selector { property: value; }"><?php echo get_option(WBOUNCE_OPTION_KEY.'_custom_css'); ?></textarea>
					        		<span>
					        			<?php esc_html_e( 'Examplary code:', WBOUNCE_TD ); ?><br>
					        			<i>.wbounce-modal .modal-title { background-color: #4ab471; }</i><br>
					        			<?php
						        			printf( __( '(You don&#39;t know CSS? Try the <a href="%s" target="_blank" title="CSS Tutorial on W3Schools">CSS Tutorial</a> on W3Schools.)', WBOUNCE_TD ),
						        			'//kevinw.de/css-tutorial'
						        			);
					        			?>
					        		</span>
					        	</td>
					        </tr>
					        <tr valign="top">
						        <th scope="row"><?php esc_html_e( 'Open Animation', WBOUNCE_TD ); ?> <span class="newred"><?php esc_html_e( 'New!', WBOUNCE_TD ); ?></span></th>
								<td>
									<select class="select" typle="select" name="<?php echo WBOUNCE_OPTION_KEY; ?>_open_animation">
										<?php $openAnimation = get_option(WBOUNCE_OPTION_KEY.'_open_animation'); ?>
										<?php foreach($this->animationOptions['open'] as $group => $options) : ?>
											<optgroup label="<?php echo $group; ?>">
												<?php foreach($options as $option) : ?>
													<option value="<?php echo $option; ?>" <?php selected($openAnimation, $option); ?>><?php echo $option; ?></option>
												<?php endforeach; ?>
											</optgroup>
										<?php endforeach; ?>
									</select>
									<p>
                  <?php printf( __( 'Define animation when wBounce fires up. <a href="%s" target="_blank" title="Preview animations of animate.css">Preview animations</a>.', WBOUNCE_TD ),
			        			'//daneden.github.io/animate.css/'
			        			);
		        			?>
                	</p>
								</td>
					        </tr>
					        <tr valign="top">
						        <th scope="row"><?php esc_html_e( 'Exit Animation', WBOUNCE_TD ); ?> <span class="newred"><?php esc_html_e( 'New!', WBOUNCE_TD ); ?></span></th>
										<td>
											<select class="select" typle="select" name="<?php echo WBOUNCE_OPTION_KEY; ?>_exit_animation">
												<?php $exitAnimation = get_option(WBOUNCE_OPTION_KEY.'_exit_animation'); ?>
												<?php foreach($this->animationOptions['exit'] as $group => $options) : ?>
													<optgroup label="<?php echo $group; ?>">
														<?php foreach($options as $option) : ?>
															<option value="<?php echo $option; ?>" <?php selected($exitAnimation, $option); ?>><?php echo $option; ?></option>
														<?php endforeach; ?>
													</optgroup>
												<?php endforeach; ?>
											</select>
											<p>
		                  	<?php printf( __( 'Define animation when closing wBounce. <a href="%s" target="_blank" title="Preview animations of animate.css">Preview animations</a>.', WBOUNCE_TD ),
						        			'//daneden.github.io/animate.css/'
						        			);
					        			?>
		                	</p>
										</td>
					        </tr>
									<tr valign="top">
										<th scope="row"><?php esc_html_e( 'Ignore Demo CSS', WBOUNCE_TD ); ?> <span class="newred"><?php esc_html_e( 'New!', WBOUNCE_TD ); ?></span></th>
										<td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_demo_css" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_demo_css' ) ); ?> /> <label><?php _e( 'If checked, the styles written for the exemplary template will be removed. This reduces the CSS file size, and makes your site a little bit faster. It also makes it easier to style the popup the way you want.', WBOUNCE_TD ); ?></label>
										</td>
									</tr>
					        <tr valign="top">
						        <th scope="row" style="color: red"><?php _e( 'MORE TO COME<br><span class="description thin">with the next updates</span>', WBOUNCE_TD ); ?></th>
						        <td>
						        </td>
					        </tr>
					    </tbody>
				    </table>

			    </div>

			    <div id="analytics">

					<h3><?php esc_html_e( 'Analytics', WBOUNCE_TD ); ?></h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
						        <th scope="row">
				        			<?php
					        			printf( __( 'Enable <a href="%s" target="_blank" title="Google Analytics Event Tracking">GA event tracking</a> <span class="description thin"><br>Requires Google Analytics.</span>', WBOUNCE_TD ),
					        			'//developers.google.com/analytics/devguides/collection/analyticsjs/events'
					        			);
				        			?>
							        </th>
						        <td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_analytics" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_analytics' ) ); ?> />
									<label>
					        			<?php
						        			printf( __( 'Check this option to track events with Google Analytics.<br><b>Notice:</b> Event tracking might not work on your local (localhost) test environment when you haven&#39;t <a href="%s" target="_blank" title="Testing on localhost">disabled the default</a> cookie domain.', WBOUNCE_TD ),
						        			'//developers.google.com/analytics/devguides/collection/analyticsjs/advanced#localhost'
						        			);
					        			?>
									</label>
						        </td>
					        </tr>
							<tr valign="top">
								<th scope="row"><?php _e( 'Available events <span class="newred">Beta</span> <span class="description thin"><br>You can monitor tracked events with your Google Analytics accout. For example, go to "Real-Time > Events" or "Behaviour > Events" and look for Event Category "wBounce".', WBOUNCE_TD ); ?></th>
								<td>
									<!-- Generated with //www.tablesgenerator.com/html_tables -->
									<table class="inline-table">
										<tr>
										    <th class="first-column"><?php esc_html_e( 'Trigger', WBOUNCE_TD ); ?></th>
										    <th><?php esc_html_e( 'Event Category', WBOUNCE_TD ); ?></th>
										    <th><?php esc_html_e( 'Event Action', WBOUNCE_TD ); ?></th>
										    <th><?php esc_html_e( 'Event Label*', WBOUNCE_TD ); ?></th>
										  </tr>
										  <tr>
										    <td class="first-column italic"><?php esc_html_e( 'Popup appears.', WBOUNCE_TD ); ?></td>
										    <td>wBounce</td>
										    <td>fired</td>
										    <td>document.url</td>
										  </tr>
										  <tr>
										    <td class="first-column italic"><?php esc_html_e( 'Click on area outside of the popup.', WBOUNCE_TD ); ?></td>
										    <td>wBounce</td>
										    <td>hidden_outside</td>
										    <td>document.url</td>
										  </tr>
										  <tr>
										    <td class="first-column italic"><?php esc_html_e( 'Click on &#39;.modal-footer&#39;.', WBOUNCE_TD ); ?></td>
										    <td>wBounce</td>
										    <td>hidden_footer</td>
										    <td>document.url</td>
										  </tr>
										  <tr>
										    <td class="first-column italic"><?php esc_html_e( 'Click on &#39;.modal-close&#39;.', WBOUNCE_TD ); ?></td>
										    <td>wBounce</td>
										    <td>hidden_close</td>
										    <td>document.url</td>
										  </tr>
										  <tr>
										    <td class="first-column italic"><?php esc_html_e( 'Closed popup using ESC key.', WBOUNCE_TD ); ?></td>
										    <td>wBounce</td>
										    <td>hidden_escape</td>
										    <td>document.url</td>
										  </tr>
									</table>
									<p><?php _e( '*<i>document.url</i> = URL of the page where the event is triggered.', WBOUNCE_TD ); ?></p>
								</td>
							</tr>
					        <tr valign="top">
						        <th scope="row">
						        	<span style="color: red"><?php _e( 'MORE TO COME<br><span class="description thin">with the next updates</span>', WBOUNCE_TD ); ?></span><br>
							        <span class="description thin"><?php
							        	printf( __( 'You can contribute <a href="%s" target="_blank">on Github</a>', WBOUNCE_TD ),
							        		'//github.com/kevinweber/wbounce'
							        	);
							        ?></span>
						        </th>
						        <td>
						        </td>
					        </tr>
					    </tbody>
				    </table>

			    </div>

				<?php do_action( WBOUNCE_OPTION_KEY.'_settings_page_tabs_after' ); ?>

			    <?php submit_button(); ?>
			</form>

			<?php require_once( 'inc/signup.php' ); ?>

		    <table class="form-table">
		        <tr valign="top">
		        <th scope="row" style="width:100px;"><a href="//kevinw.de/wb/" target="_blank"><img src="//www.gravatar.com/avatar/9d876cfd1fed468f71c84d26ca0e9e33?d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536&s=100" style="-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;"></a></th>
		        <td style="width:200px;">
		        	<p>
			        	<?php
				        	printf( __( '<a href="%s" target="_blank">Kevin Weber</a> &ndash; that&#39;s me.<br>I&#39;m the developer of this plugin. Love it!', WBOUNCE_TD ),
				        		'//kevinw.de/wb'
				        	);
			        	?>
			        </p>
			    </td>
		        <td>
					<p>
			        	<?php
				        	printf( __( '<b>It&#39;s easy:</b> You increase sales thanks to my plugin. In exchange, you donate at least <a href="%1$s" title="Donate me" target="_blank">9,37€</a> so I can further develop it. And please, give this plugin a 5 star rating <a href="%2$s" title="Vote for wBounce" target="_blank">on WordPress.org</a>.', WBOUNCE_TD ),
				        		'//kevinw.de/donate/wBounce/',
				        		'//wordpress.org/support/view/plugin-reviews/wbounce?filter=5'
				        	);
			        	?>
					</p>
		        </td>
		        <td style="width:300px;">
					<p>
						<b><?php esc_html_e( 'Personal tip: Must use plugins', WBOUNCE_TD ); ?></b>
						<ol>
							<li>
					        	<?php
						        	printf( __( '<a href="%s" title="Lazy Load for Videos" target="_blank">Lazy Load for Videos</a> (on my part)', WBOUNCE_TD ),
						        		'//kevinw.de/wb-ll'
						        	);
					        	?>
							</li>
							<li>
					        	<?php
						        	printf( __( '<a href="%s" title="WordPress SEO by Yoast" target="_blank">WordPress SEO</a> (by Yoast)', WBOUNCE_TD ),
						        		'//yoast.com/wordpress/plugins/seo/'
						        	);
					        	?>
							</li>
							<li>
					        	<?php
						        	printf( __( '<a href="%s" title="Inline Comments" target="_blank">Inline Comments</a> (on my part)', WBOUNCE_TD ),
						        		'//kevinw.de/wb-ic'
						        	);
					        	?>
							</li>
							<li>
					        	<?php
						        	printf( __( '<a href="%s" title="Broken Link Checker" target="_blank">Broken Link Checker</a> (by Janis Elsts)', WBOUNCE_TD ),
						        		'//wordpress.org/plugins/broken-link-checker/'
						        	);
					        	?>
							</li>
						</ol>
					</p>
		        </td>
		        </tr>
			</table>
		</div>

	<?php
	}

}

new Wbounce_Admin_Options();
