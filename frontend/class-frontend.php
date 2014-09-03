<?php
/**
 * @package Frontend
 */

new Wbounce_Frontend();

class Wbounce_Frontend {

	function __construct() {
		add_action( 'wp_head', array( $this, 'custom_css') );
		add_action( 'wp_footer', array( $this, 'wp_footer'), 0, WBOUNCE_OPTION_KEY.'-functions' );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_jquery' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style') );
	}

	/**
	 * Create modal
	 */
	function create_modal_content() { ?>
	
		<div id="wbounce-modal" class="wbounce-modal underlay" style="display:none">
			<div id="wbounce-modal-sub" class="modal">
				<?php 
					if (stripslashes(get_option(WBOUNCE_OPTION_KEY.'_content')) != '') {
						echo do_shortcode( stripslashes(get_option(WBOUNCE_OPTION_KEY.'_content')) );
					}
					else {
						$this->create_modal_content_default();
					}
				?>
			</div>
		</div>
	<?php }

	function create_modal_content_default() {
		$content_default = '
			<div class="modal-title">
	          <h3>Do you love this plugin as much as I do?</h3>
	        </div>

	        <div class="modal-body" style="text-align:center">

	        	<p><a href="http://kevinw.de/" target="_blank"><img src="http://www.gravatar.com/avatar/9d876cfd1fed468f71c84d26ca0e9e33?d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536&s=100" style="-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;"></a></p>
				<p><a href="http://kevinw.de/wbounce" target="_blank" style="color:#009000;font-size:20px"><h4>wBounce by Kevin Weber</h4></a></p>
				<p style="font-size:15px">I\'m the developer of this plugin. Feel free to contact and follow me <a href="https://twitter.com/kevinweber" title="Kevin Weber on Twitter" target="_blank" style="color:#4099FF">on Twitter</a>. And subscribe to my list for WordPress enthusiasts:</p>

				<div id="mc_embed_signup">
				<form action="//kevinw.us2.list-manage.com/subscribe/post?u=f65d804ad274b9c8812b59b4d&amp;id=39ca44d8d3" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<div class="mc-field-group updated">
						<input type="email" value="Enter your email address" name="EMAIL" class="required email" id="mce-EMAIL" onclick="this.focus();this.select()" onfocus="if(this.value == \'\') { this.value = this.defaultValue; }" onblur="if(this.value == \'\') { this.value = this.defaultValue; }">
						<input type="hidden" name="GROUPS" id="GROUPS" value="Signup via Plugin (frontend)" />
						<input style="background-color:#009000" type="submit" value="Click to subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
					</div>
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>
				    <div style="position: absolute; left: -5000px;"><input type="text" name="b_f65d804ad274b9c8812b59b4d_39ca44d8d3" tabindex="-1" value=""></div>
				</form>
				</div>

	        </div>
		';
		echo apply_filters( WBOUNCE_OPTION_KEY.'_create_modal_content_default', $content_default );
	}

	/**
	 * Add Code to Footer
	 */
	function wp_footer() {
		if ($this->test_if_status_is_off()) return;

		$this->create_modal_content();
		$this->load_footer_script();
	}

	function load_footer_script() { ?>
		<script>
			var $<?= WBOUNCE_OPTION_KEY ?> = jQuery.noConflict();

			$<?= WBOUNCE_OPTION_KEY ?>(document).ready(function() {

		      var _ouibounce = ouibounce(document.getElementById('wbounce-modal'), {
		      	<?php
	      		// Aggressive Mode
	      		if (
	      			( get_option(WBOUNCE_OPTION_KEY.'_aggressive_mode') == '1' ) ||
	      			( current_user_can( 'manage_options' ) && ( get_option(WBOUNCE_OPTION_KEY.'_test_mode') == '1' ) )
	      		) {
	      			echo 'aggressive:true,';
		      	}
		      	// Cookie expiration
	      		if ( get_option(WBOUNCE_OPTION_KEY.'_cookieexpire') != "" ) {
	      			echo 'cookieExpire:'.get_option(WBOUNCE_OPTION_KEY.'_cookieexpire').',';
	      		}

	      		// Cookie domain
	      		// ...

	      		// Sitewide cookie
	      		// ...

	      		// Timer (Set a min time before wBounce fires)
	      		if ( get_option(WBOUNCE_OPTION_KEY.'_timer') != "" ) {
	      			echo 'timer:'.get_option(WBOUNCE_OPTION_KEY.'_timer').',';
	      		}

	      		// Hesitation
	      		if ( get_option(WBOUNCE_OPTION_KEY.'_hesitation') != "" ) {
	      			echo 'delay:'.get_option(WBOUNCE_OPTION_KEY.'_hesitation').',';
	      		}

	      		// Sensitivity
	      		if ( get_option(WBOUNCE_OPTION_KEY.'_sensitivity') != "" ) {
	      			echo 'sensitivity:'.get_option(WBOUNCE_OPTION_KEY.'_sensitivity').',';
	      		}

	      		// Callback
	      		// ...
	      		// Delay/Intelligent timer
	      		// ...

	      		
		      	?>
		      });

		      $<?= WBOUNCE_OPTION_KEY ?>('body').on('click', function() {
		        $<?= WBOUNCE_OPTION_KEY ?>('#wbounce-modal').hide();
		      });

		      $<?= WBOUNCE_OPTION_KEY ?>('#wbounce-modal .modal-footer').on('click', function() {
		        $<?= WBOUNCE_OPTION_KEY ?>('#wbounce-modal').hide();
		      });

		      $<?= WBOUNCE_OPTION_KEY ?>('#wbounce-modal .modal').on('click', function(e) {
		        e.stopPropagation();
		      });

			});
		</script>
	<?php }

	/**
	 * Add scripts (like JS)
	 */
	function enqueue_scripts() {
		if ($this->test_if_status_is_off()) return;
		
		wp_enqueue_script( WBOUNCE_OPTION_KEY.'-function', plugins_url( 'js/min/'.WBOUNCE_OPTION_KEY.'-ck.js' , plugin_dir_path( __FILE__ ) ) );	
	}

	/**
	 * Add stylesheet
	 */
	function enqueue_style() {
		if ($this->test_if_status_is_off()) return;

		wp_register_style( WBOUNCE_OPTION_KEY.'-style', plugins_url('css/min/'.WBOUNCE_OPTION_KEY.'.css', plugin_dir_path( __FILE__ ) ) );
		wp_enqueue_style( WBOUNCE_OPTION_KEY.'-style' );
	}

	/**
	 * Add Custom CSS
	 */
	function custom_css(){
		if ($this->test_if_status_is_off()) return;

		echo '<style type="text/css">';
		if (stripslashes(get_option(WBOUNCE_OPTION_KEY.'_custom_css')) != '') {
			echo stripslashes(get_option(WBOUNCE_OPTION_KEY.'_custom_css'));
		}
		echo '</style>';
	}

	/**
 	 * Enable jQuery (comes with WordPress)
 	 */
 	function enqueue_jquery() {
 		if ($this->test_if_status_is_off()) return;

     	wp_enqueue_script( 'jquery' );
 	}

 	/**
 	 * Test if status is "off" for specific post/page
 	 */
 	function test_if_status_is_off() {
		global $post;

		if (!isset($post->ID)) {
			$id = null;
		}
		else {
			$id = $post->ID;
		}
		
		// When the individual status for a page/post is 'off', all the other setting don't matter. So this has to be tested at first. 
		if ( get_post_meta( $id, 'wbounce_status', true ) && get_post_meta( $id, 'wbounce_status', true ) === 'off' ) {
			return true;
		}
		else if (
			( !get_option(WBOUNCE_OPTION_KEY.'_status_default') ) ||	// Fire when no option is defined yet
			( get_post_meta( $id, 'wbounce_status', true ) === 'on' ) ||
			( get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on' ) ||
			( get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_posts' && is_single() ) ||
			( get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_pages' && is_page() )
		) {
			return false;
		}
		else
			return true;
 	}
}