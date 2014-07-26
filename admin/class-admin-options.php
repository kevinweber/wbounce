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
			'_content',
			// Tab 'Options'
			'_aggressive_mode',
			'_timer',
			// Tab 'Styling'
			'_custom_css',
			// Tab 'Analytics'
			//...
		);
		foreach ( $arr as $i ) {
			register_setting( WBOUNCE_OPTION_KEY.'-settings-group', WBOUNCE_OPTION_KEY.$i );
		}
		do_action( WBOUNCE_OPTION_KEY.'_register_settings_after' );
	}

	function settings_page()	{ ?>

		<div id="tabs" class="ui-tabs">
			<h2><?= WBOUNCE_PLUGIN_NAME ?> <span class="subtitle">by <a href="http://kevinw.de/wb" target="_blank" title="Website by Kevin Weber">Kevin Weber</a> (Version <?php echo WBOUNCE_VERSION_NUM; ?>)</span></h2>

			<ul class="ui-tabs-nav">
		        <li><a href="#tab-content">Content</a></li>
		        <li><a href="#tab-options">Options</a></li>
		        <li><a href="#tab-styling">Styling</a></li>
		        <li><a href="#tab-analytics">Analytics</a></li>
		    	<?php do_action( WBOUNCE_OPTION_KEY.'_settings_page_tabs_link_after' ); ?>
		    </ul>

			<form method="post" action="options.php">
			    <?php settings_fields( WBOUNCE_OPTION_KEY.'-settings-group' ); ?>
			    <?php do_settings_sections( WBOUNCE_OPTION_KEY.'-settings-group' ); ?>

			    <div id="tab-content">

					<h3>Content</h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
						        <th scope="row">Test Mode</th>
						        <td>
									<input name="<?= WBOUNCE_OPTION_KEY ?>_test_mode" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_test_mode' ) ); ?> /> <label>Check this option to enable "Aggressive Mode" <b>for admins</b>, regardless of the actual setting in the tab "Options".</label>
						        </td>
					        </tr>
					        <tr valign="top">
					        	<th scope="row">wBounce Content <span class="description thin"><br>Add code that should be displayed within the wBounce window.</span></th>
					        	<td>
					        		<textarea rows="14" cols="70" type="text" name="<?= WBOUNCE_OPTION_KEY ?>_content" placeholder="Exemplary template below."><?php echo get_option(WBOUNCE_OPTION_KEY.'_content'); ?></textarea>
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

			    <div id="tab-options">

					<h3>Options</h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
						        <th scope="row">Aggressive Mode</th>
						        <td>
									<input name="<?= WBOUNCE_OPTION_KEY ?>_aggressive_mode" type="checkbox" value="1" <?php checked( '1', get_option( WBOUNCE_OPTION_KEY.'_aggressive_mode' ) ); ?> /> <label>By default, wBounce will only fire once for each visitor. When wBbounce fires, a cookie is created to ensure a non obtrusive experience.<br><br>There are cases, however, when you may want to be more aggressive (as in, you want the modal to be elegible to fire anytime the page is loaded/ reloaded). An example use-case might be on your paid landing pages. If you enable aggressive, the modal will fire any time the page is reloaded, for the same user.</label>
						        </td>
					        </tr>
					        <tr valign="top">
						        <th scope="row">Timer</th>
						        <td>
						        	<input type="number" name="<?= WBOUNCE_OPTION_KEY ?>_timer" placeholder="milliseconds" value="<?php echo get_option(WBOUNCE_OPTION_KEY.'_timer'); ?>" /><br><label>By default, wBounce won't fire in the first second to prevent false positives, as it's unlikely the user will be able to exit the page within less than a second. If you want to change the amount of time that firing is surpressed for, you can pass in a number of milliseconds to timer.<br><b>Insert 0 to fire immediately.</b></label>
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

			    <div id="tab-styling">

					<h3>Styling</h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
					        	<th scope="row">Custom CSS <span class="description thin"><br>Add additional CSS. This should override any other stylesheets.</span></th>
					        	<td>
					        		<textarea rows="14" cols="70" type="text" name="<?= WBOUNCE_OPTION_KEY ?>_custom_css" placeholder="selector { property: value; }"><?php echo get_option(WBOUNCE_OPTION_KEY.'_custom_css'); ?></textarea>
					        		<span>
					        			Examplary code:<br>
					        			<i>.wbounce-modal .modal-title { background-color: #4ab471; }</i><br>
					        			(You don't know CSS? Try the <a href="http://kevinw.de/css-tutorial" target="_blank" title="CSS Tutorial on W3Schools">CSS Tutorial</a> on W3Schools.)
					        		</span>
					        	</td>
					        </tr>
					    </tbody>
				    </table>

			    </div>

			    <div id="tab-analytics">

					<h3>Analytics</h3>

				    <table class="form-table">
					    <tbody>
					        <tr valign="top">
						        <th scope="row" style="color: red">MORE TO COME<br><span class="description thin">with the next updates</span></th>
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
		        <th scope="row" style="width:100px;"><a href="http://kevinw.de/wb/" target="_blank"><img src="http://www.gravatar.com/avatar/9d876cfd1fed468f71c84d26ca0e9e33?d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536&s=100" style="-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;"></a></th>
		        <td style="width:200px;">
		        	<p><a href="http://kevinw.de/wb" target="_blank">Kevin Weber</a> &ndash; that's me.<br>
		        	I'm the developer of this plugin. Love it!</p></td>
			        <td>
						<p><b>It's easy:</b> You increase sales thanks to my plugin. In exchange, you donate at least <a href="http://kevinw.de/donate/wBounce/" title="Donate me" target="_blank">9,37€</a> so I can further develop it. And please, give this plugin a 5 star rating <a href="http://wordpress.org/support/view/plugin-reviews/wbounce?filter=5" title="Vote for wBounce" target="_blank">on WordPress.org</a>.</p>
			        </td>       
		        <td>
					<p><b>Speed up your site</b> by replacing embedded Youtube and Vimeo videos with a clickable preview image: <a href="http://kevinw.de/wb-ll" title="Lazy Load for Videos" target="_blank">Lazy Load for Videos</a>.</p>
		        </td>
		        </tr>
			</table>
		</div>

	<?php
	}

}

new Wbounce_Admin_Options();