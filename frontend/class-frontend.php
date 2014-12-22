<?php
/**
 * @package Frontend
 */
class Wbounce_Frontend {

	function __construct() {
		add_action( 'wp_head', array( $this, 'custom_css') );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer'), 0, WBOUNCE_OPTION_KEY.'-functions' );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style') );
	}

	/**
	 * Create modal
	 */
	function create_modal_content() { ?>
		<div id="wbounce-modal" class="wbounce-modal underlay" style="display:none">
			<div id="wbounce-modal-sub" class="wbounce-modal-sub modal">
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
			var $<?php echo WBOUNCE_OPTION_KEY; ?> = jQuery.noConflict();
			var fired = false;	// Set "fired" to true as soon as the popup is fired
			var cookieName = 'wBounce';
			var aggressive = '<?php echo $this->test_if_aggressive(); ?>';


			$<?php echo WBOUNCE_OPTION_KEY; ?>(document).ready(function() {



		      var _ouibounce = ouibounce(document.getElementById('wbounce-modal'), {
		      	<?php
		      	// Echo options that require a string input
		      	$option_str = array(
		      		'cookieExpire',	// Cookie expiration
		      		'cookieDomain', // Cookie domain
		      		'timer', // Timer (Set a min time before wBounce fires)
		      		'sensitivity',	// Sensitivity
		      	);	
	      		foreach ($option_str as $str) {
	      			$this->echo_option_str( $str );
	      		}

	      		// Aggressive Mode
	      		if ( $this->test_if_aggressive() ) {
	      			echo 'aggressive:true,';
		      	}

	      		// Cookie per page (sitewide cookie)
	      		if ( get_option(WBOUNCE_OPTION_KEY.'_sitewide') != '1' ) {
		      		echo 'sitewide:true,';
		      	}

	      		// Hesitation
	      		if ( $this->test_if_given_str('hesitation') ) {
	      			echo 'delay:'.$this->get_option('hesitation').',';
	      		}

		      	// Custom cookie name
		      	echo "cookieName:cookieName,";

	      		// Callback
	      		echo "callback:function(){fired = true;}"	// Set fired to "true" when popup is fired
	      		// ... TODO: trigger Google Analytics event

	      		// Delay/Intelligent timer
	      		// ...
		      	?>
		      });

		      $<?php echo WBOUNCE_OPTION_KEY; ?>('body').on('click', function() {
		        $<?php echo WBOUNCE_OPTION_KEY; ?>('#wbounce-modal').hide();
		      });

		      $<?php echo WBOUNCE_OPTION_KEY; ?>('#wbounce-modal .modal-footer').on('click', function() {
		        $<?php echo WBOUNCE_OPTION_KEY; ?>('#wbounce-modal').hide();
		      });

		      $<?php echo WBOUNCE_OPTION_KEY; ?>('#wbounce-modal-sub').on('click', function(e) {
		        e.stopPropagation();
		      });

/*
 * AUTOFIRE JS
 * Setup variables for autoFire
 */
var autoFire = null;
<?php
if ( $this->test_if_given_str('autoFire') ) {
	echo 'autoFire = '.$this->get_option('autoFire').';';
}
?>

function isInteger(x) {
	return (typeof x === 'number') && (x % 1 === 0);
}
function handleAutoFire( delay ) {
	if ( (_ouibounce.checkCookieValue( cookieName, 'true') && !aggressive ) || fired === true ) return;
	setTimeout( _ouibounce._fireAndCallback, delay );
}
if ( isInteger(autoFire) && autoFire !== null ) {
  handleAutoFire( autoFire );
}
/*** /AUTOFIRE JS ***/

			});
		</script>
	<?php }

	function get_option( $optionname ) {
		return get_option(WBOUNCE_OPTION_KEY.'_'.$optionname);
	}
	function test_if_given_str( $optionname ) {
		return ( get_option(WBOUNCE_OPTION_KEY.'_'.$optionname) != "" ) ? true : false;
	}
	function echo_option_str( $optionname ) {
  		if ( $this->test_if_given_str(strtolower($optionname)) ) {
  			echo $optionname.':\''.$this->get_option(strtolower($optionname)).'\',';
  		}
	}

	function test_if_aggressive() {
		return ( 
			( $this->get_option('aggressive_mode') == '1' ) ||
		    ( current_user_can( 'manage_options' ) && ( $this->get_option('test_mode') == '1' ) )
		 ) ? true : false;
	}

	/**
	 * Add scripts (like JS)
	 */
	function enqueue_scripts() {
		if ($this->test_if_status_is_off()) return;
		
		wp_enqueue_script( 'jquery' );	// Enable jQuery (comes with WordPress)
		if ( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) {
			wp_enqueue_script( WBOUNCE_OPTION_KEY.'-function', plugins_url( 'js/'.WBOUNCE_OPTION_KEY.'.js' , plugin_dir_path( __FILE__ ) ), 'jquery', WBOUNCE_VERSION_NUM, $this->test_if_script_should_be_loaded_in_footer() );
		} else {
			wp_enqueue_script( WBOUNCE_OPTION_KEY.'-function', plugins_url( 'js/min/'.WBOUNCE_OPTION_KEY.'-ck.js' , plugin_dir_path( __FILE__ ) ), 'jquery', WBOUNCE_VERSION_NUM, $this->test_if_script_should_be_loaded_in_footer() );
		}
	}

	function test_if_script_should_be_loaded_in_footer() {
		if ( get_option(WBOUNCE_OPTION_KEY.'_load_in_footer') ) {
			return true;
		}
		else 
			return false;
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

new Wbounce_Frontend();