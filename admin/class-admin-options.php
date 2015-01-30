<?php
/**
 * @package Admin
 */
class Wbounce_Admin_Options {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'create_menu' ));	
		add_action( 'admin_init', array( $this, 'admin_init_options' ) );
	}

	function admin_init_options() {
		$plugin = plugin_basename( WBOUNCE_FILE ); 
		add_filter("plugin_action_links_$plugin", array( $this, 'settings_link' ) );
		$this->register_settings();
	}

	/**
	 * Add settings link on plugin page
	 */
	function settings_link($links) { 
	  $settings_link = '<a href="options-general.php?page='.WBOUNCE_OPTION_KEY.'.php">Settings</a>'; 
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
			'_content',
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
			<h2><?php echo WBOUNCE_PLUGIN_NAME; ?> <span class="subtitle">by <a href="http://kevinw.de/wb" target="_blank" title="Website by Kevin Weber">Kevin Weber</a> (Version <?php echo WBOUNCE_VERSION_NUM; ?>)</span></h2>

			<ul class="ui-tabs-nav">
		        <li><a href="#content">Content <span class="newred_dot">&bull;</span></a></li>
		        <li><a href="#options">Options</a></li>
		        <li><a href="#styling">Styling</a></li>
		        <li><a href="#analytics">Analytics <span class="newred_dot">&bull;</span></a></li>
		        <li><a href="#more" class="tab-orange tab-premium">15% coupon for OptinMonster <span class="newred_dot">&bull;</span></a></li>
		    	<?php do_action( WBOUNCE_OPTION_KEY.'_settings_page_tabs_link_after' ); ?>
		    </ul>

			<form method="post" action="options.php">
			    <?php settings_fields( WBOUNCE_OPTION_KEY.'-settings-group' ); ?>
			    <?php do_settings_sections( WBOUNCE_OPTION_KEY.'-settings-group' ); ?>

			    <div id="content">

					<h3>Content</h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
						        <th scope="row">Test mode</th>
						        <td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_test_mode" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_test_mode' ) ); ?> /> <label>Check this option to enable "Aggressive Mode" <b>for admins</b>, regardless of the actual setting in the tab "Options".</label>
						        </td>
					        </tr>
					        <tr valign="top">
					        	<th scope="row">Default status <span class="newred">Extended</span></th>
						        <td>
									<select class="select" typle="select" name="<?php echo WBOUNCE_OPTION_KEY; ?>_status_default">
								    	<option value="on"<?php if (get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on') { echo ' selected="selected"'; } ?>>Always fire</option>
								    	<option value="on_posts"<?php if (get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_posts') { echo ' selected="selected"'; } ?>>Fire on posts</option>
		     							<option value="on_pages"<?php if (get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_pages') { echo ' selected="selected"'; } ?>>Fire on pages</option>
		     							<option value="on_posts_pages"<?php if (get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_posts_pages') { echo ' selected="selected"'; } ?>>Fire on posts and pages</option>
		     							<option value="off"<?php if (get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'off') { echo ' selected="selected"'; } ?>>Don't fire</option>
		     						</select>
									<p>Define if wBounce should be fired on posts and/or pages by default. You can override the default setting on every post and page individually.</p>
						        </td>
					        </tr>
					        <tr valign="top">
					        	<th scope="row">wBounce content <span class="description thin"><br>Add code that should be displayed within the wBounce window.</span></th>
					        	<td>
					        		<textarea rows="14" cols="70" type="text" name="<?php echo WBOUNCE_OPTION_KEY; ?>_content" placeholder="Exemplary template below."><?php echo get_option(WBOUNCE_OPTION_KEY.'_content'); ?></textarea>
					        		<span>

					        			Exemplary template:<br>
<pre>
&lt;div class=&quot;modal-title&quot;&gt;
  &lt;h3&gt;Title&lt;/h3&gt;
&lt;/div&gt;

&lt;div class=&quot;modal-body&quot;&gt;
  &lt;p&gt;Paragraph&lt;/p&gt;

  &lt;form&gt;
    &lt;input type=&quot;email&quot; placeholder=&quot;you@email.com&quot;&gt;
    &lt;input type=&quot;submit&quot; value=&quot;learn more &raquo;&quot;&gt;
    &lt;p class=&quot;form-notice&quot;&gt;*this is a fake form&lt;/p&gt;
  &lt;/form&gt;
&lt;/div&gt;

&lt;div class=&quot;modal-footer&quot;&gt;
  &lt;p&gt;no thanks&lt;/p&gt;
&lt;/div&gt;
</pre>

					        		</span>
					        	</td>
					        </tr>
					    </tbody>
				    </table>

			    </div>

			    <div id="options">

					<h3>Options</h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
						        <th scope="row">Aggressive mode</th>
						        <td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_aggressive_mode" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_aggressive_mode' ) ); ?> /> <label>By default, wBounce will only fire once for each visitor. When wBounce fires, a cookie is created to ensure a non obtrusive experience.<br><br>There are cases, however, when you may want to be more aggressive. An example use-case might be on your paid landing pages. If you enable aggressive, the modal can be fired any time the page is reloaded.</label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row">Self-acting fire (timer)</th>
						        <td>
									<input type="number" name="<?php echo WBOUNCE_OPTION_KEY; ?>_autofire" placeholder="milliseconds" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_autofire'); ?>" /><br><label>Automatically trigger the popup after a certain time period. Insert 0 to fire immediately when the page is loaded. Leave blank to not use this option.</label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row">Set a min time<br><span class="description thin">&hellip; before wBounce fires.</span></th>
						        <td>
						        	<input type="number" name="<?echo WBOUNCE_OPTION_KEY; ?>_timer" placeholder="milliseconds" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_timer'); ?>" /><br><label>By default, wBounce won't fire in the first second to prevent false positives, as it's unlikely the user will be able to exit the page within less than a second. If you want to change the amount of time that firing is surpressed for, you can pass in a number of milliseconds to timer.<br>Insert 0 to fire immediately.</label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row">Hesitation</th>
						        <td>
						        	<input type="number" name="<?php echo WBOUNCE_OPTION_KEY; ?>_hesitation" placeholder="milliseconds" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_hesitation'); ?>" /><br><label>By default, wBounce will show the modal immediately when the user's cursor leaves the window. You could instead configure it to wait <i>x</i> milliseconds before showing the modal. If the cursor re-enters the body before delay ms have passed, the modal will not appear. This can be used to provide a "grace period" for visitors instead of immediately presenting the modal window.</label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row">Cookie expiration</th>
						        <td>
						        	<input type="number" name="<?php echo WBOUNCE_OPTION_KEY; ?>_cookieexpire" placeholder="days" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_cookieexpire'); ?>" /><br><label>wBounce sets a cookie by default to prevent the modal from appearing more than once per user. You can add a cookie expiration (in days) to adjust the time period before the modal will appear again for a user. By default, the cookie will expire at the end of the session, which for most browsers is when the browser is closed entirely.</label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row">Cookie per page</th>
						        <td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_sitewide" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_sitewide' ) ); ?> /> <label>By default, the cookie is stored for the whole site. With the "cookie per page" option enabled, every page/post gets its own cookie.</label>
						        </td>
					        </tr>
					        <tr valign="top">
					        	<th scope="row">Cookie domain</th>
					        	<td>
					        		<input type="text" name="<?php echo WBOUNCE_OPTION_KEY; ?>_cookiedomain" placeholder="" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_cookiedomain'); ?>" /><br><span><?php esc_html_e( 'wBounce sets a cookie by default to prevent the modal from appearing more than once per user. You can add a cookie domain to specify the domain under which the cookie should work. By default, no extra domain information will be added. If you need a cookie to work also in your subdomain (like blog.example.com and example.com), then set a cookie domain such as .example.com (notice the dot in front).', WBOUNCE_TD ); ?></span>
					        	</td>
					        </tr>
					        <tr valign="top">
						        <th scope="row">Sensitivity <span class="newred">Deprecated</span><br><span class="description thin">Feature will be removed with one of the next updates.</span></th>
						        <td>
						        	<input type="number" name="<?php echo WBOUNCE_OPTION_KEY; ?>_sensitivity" placeholder="20" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_sensitivity'); ?>" /><br><label>wBounce fires when the mouse cursor moves close to (or passes) the top of the viewport. You can define how far the mouse has to be before wBounce fires. The higher value, the more sensitive, and the more quickly the event will fire. Defaults to 20.</label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row">Load script in footer</th>
						        <td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_load_in_footer" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_load_in_footer' ) ); ?> /> <label>Normally, scripts are placed in &lt;head&gt; of the HTML document. If this parameter is true, the script is placed before the &lt;/body&gt; end tag. This requires the theme to have the wp_footer() template tag in the appropriate place.</label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row" style="color: red">MORE TO COME<br><span class="description thin">with the next updates</span></th>
						        <td>
						        </td>
					        </tr>
					    </tbody>
				    </table>

			    </div>

			    <div id="styling">

					<h3>Styling</h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
					        	<th scope="row">Custom CSS <span class="description thin"><br>Add additional CSS. This should override any other stylesheets.</span></th>
					        	<td>
					        		<textarea rows="14" cols="70" type="text" name="<?php echo WBOUNCE_OPTION_KEY; ?>_custom_css" placeholder="selector { property: value; }"><?php echo get_option(WBOUNCE_OPTION_KEY.'_custom_css'); ?></textarea>
					        		<span>
					        			Examplary code:<br>
					        			<i>.wbounce-modal .modal-title { background-color: #4ab471; }</i><br>
					        			(You don't know CSS? Try the <a href="http://kevinw.de/css-tutorial" target="_blank" title="CSS Tutorial on W3Schools">CSS Tutorial</a> on W3Schools.)
					        		</span>
					        	</td>
					        </tr>
					        <tr valign="top">
						        <th scope="row" style="color: red">MORE TO COME<br><span class="description thin">with the next updates</span></th>
						        <td>
						        </td>
					        </tr>
					    </tbody>
				    </table>

			    </div>

			    <div id="analytics">

					<h3>Analytics</h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
						        <th scope="row">Enable <a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/events" target="_blank" title="Google Analytics Event Tracking">GA event tracking</a> <span class="description thin"><br>Requires Google Analytics.</span> <span class="newred">New!</span></th>
						        <td>
									<input name="<?php echo WBOUNCE_OPTION_KEY; ?>_analytics" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_analytics' ) ); ?> /> <label>Check this option to track events with Google Analytics.
									<br><b>Notice:</b> Event tracking might not work on your local (localhost) test environment when you haven't <a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/advanced#localhost" target="_blank" title="Testing on localhost">disabled the default</a> cookie domain.</label>	        	
						        </td>
					        </tr>
							<tr valign="top">
								<th scope="row">Available events <span class="newred">New!</span> <span class="description thin"><br>You can monitor tracked events with your Google Analytics accout. For example, go to "Real-Time > Events" or "Behaviour > Events" and look for Event Category "wBounce".</th>
								<td>
									<!-- Generated with http://www.tablesgenerator.com/html_tables -->
									<table class="inline-table">
										<tr>
										    <th class="first-column">Trigger</th>
										    <th>Event Category</th>
										    <th>Event Action</th>
										    <th>Event Label*</th>
										  </tr>
										  <tr>
										    <td class="first-column italic">Popup appears.</td>
										    <td>wBounce</td>
										    <td>fired</td>
										    <td>document.url</td>
										  </tr>
										  <tr>
										    <td class="first-column italic">Click on area outside of the popup.</td>
										    <td>wBounce</td>
										    <td>hidden_outside</td>
										    <td>document.url</td>
										  </tr>
										  <tr>
										    <td class="first-column italic">Click on '.modal-footer'.</td>
										    <td>wBounce</td>
										    <td>hidden_footer</td>
										    <td>document.url</td>
										  </tr>
										  <tr>
										    <td class="first-column italic">Click on '.modal-close'.</td>
										    <td>wBounce</td>
										    <td>hidden_close</td>
										    <td>document.url</td>
										  </tr>
									</table>
									<p>*<i>document.url</i> = URL of the page where the event is triggered.</p>
								</td>
							</tr>
					        <tr valign="top">
						        <th scope="row" style="color: red">MORE TO COME<br><span class="description thin">with the next updates</span></th>
						        <td>
						        </td>
					        </tr>
					    </tbody>
				    </table>

			    </div>

			    <div id="more">

					<h3>Should you switch to a premium plugin?</h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
						        <td>
									<p>wBounce is the most lightweight exit popup plugin for WordPress, and it's available for free!</p>
									<p>But I'm aware of those people who want (and need) more than that; many people desire fancy ready-made popup themes, automatic popups on mobiles, A/B testing and more. You can choose:</p>
									<ol>
										<li>Either stick with the <b>feather-light free wBounce</b> and get surprised by new features in future – but don't expect superpowers. wBounce does what it does.</li>
										<li>Or go premium and <b>get superpowers like split testing</b> and a conversion rate for each popup. I'm pretty sure that you're aware of the fact that popups can boost newsletter signups vastly. So consider if a premium solution is worthwhile. (Not for everyone, but in many cases: <b>Yes, it's worth it.</b>)</li>
									</ol>

									<p>
										<a class="button button-primary button-monster" href="http://optinmonster.com/" title="OptinMonster Website" target="_blank">Premium Popup Plugin: OptinMonster<br><span style="font-size:0.6em;">(exclusive 15% coupon: kevinweber)</span></a>
									</p>

									<h4>What's the best premium popup solution?</h4>
									<p>I've tested several popup plugins with prices that range from $0 to $500, and you should do the same. My favourite premium popup plugin is OptinMonster. Let me explain why:</p>
									<ol>
										<li>OptinMonster's user experience and service outranges other plugins, especially the popup builder which allows you to design popups easily and fast – without coding know-how.</li>
										<li>Lots of useful features, e.g. more granular targeting, built-in stats, various types of optin forms, &hellip;</li>
										<li>A/B or split testing, of course.</li>
										<li>100% no-risk money back guarantee: If you don't like OptinMonster over the next 14 days, then they'll refund 100% of your money. No questions asked. [This statement is from their website.]</li>
										<li>I captured an <b>exclusive 15% coupon for users of wBounce!</b></li>
									</ol>

									<p style="border:1px solid #000;padding:10px;margin-bottom:12px;">To get the 15% coupon, enter my name (<i>kevinweber</i>) as coupon on the checkout page of <a href="http://optinmonster.com/" title="OptinMonster Website" target="_blank">OptinMonster</a>.</p>							

									<p>But before you start to test OptinMonster, here is one more hint: OptinMonster comes with several add-ons, and to use the exit popup add-on, you must acquire at least the Pro license. Fortunately, you can use my coupon with any license.</p>
									
									<h4>This video preview gives you an impression of the popup builder:</h4>
									<iframe width="560" height="315" src="//www.youtube.com/embed/T_gTIXGlU1Y" frameborder="0" allowfullscreen></iframe>
									<p>
										<br>
										<a class="button button-primary button-monster" href="http://optinmonster.com/" title="OptinMonster Website" target="_blank">Discover OptinMonster now<br><span style="font-size:0.6em;">(exclusive 15% coupon: kevinweber)</span></a>
									</p>
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
		        <th scope="row" style="width:100px;"><a href="http://kevinw.de/wb/" target="_blank"><img src="https://www.gravatar.com/avatar/9d876cfd1fed468f71c84d26ca0e9e33?d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536&s=100" style="-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;"></a></th>
		        <td style="width:200px;">
		        	<p><a href="http://kevinw.de/wb" target="_blank">Kevin Weber</a> &ndash; that's me.<br>
		        	I'm the developer of this plugin. Love it!</p></td>
			        <td>
						<p><b>It's easy:</b> You increase sales thanks to my plugin. In exchange, you donate at least <a href="http://kevinw.de/donate/wBounce/" title="Donate me" target="_blank">9,37€</a> so I can further develop it. And please, give this plugin a 5 star rating <a href="http://wordpress.org/support/view/plugin-reviews/wbounce?filter=5" title="Vote for wBounce" target="_blank">on WordPress.org</a>.</p>
			        </td>       
		        <td style="width:300px;">
					<p>
						<b>Personal tip: Must use plugins</b>
						<ol>
							<li><a href="http://kevinw.de/wb-ic" title="Inline Comments" target="_blank">Inline Comments</a> (on my part)</li>
							<li><a href="https://yoast.com/wordpress/plugins/seo/" title="WordPress SEO by Yoast" target="_blank">WordPress SEO</a> (by Yoast)</li>
							<li><a href="http://kevinw.de/wb-ll" title="Lazy Load for Videos" target="_blank">Lazy Load for Videos</a> (on my part)</li>
							<li><a href="https://wordpress.org/plugins/broken-link-checker/" title="Broken Link Checker" target="_blank">Broken Link Checker</a> (by Janis Elsts)</li>
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