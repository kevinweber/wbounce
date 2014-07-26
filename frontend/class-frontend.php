<?php
/**
 * @package Frontend
 */

new Wbounce_Frontend();

class Wbounce_Frontend {

	function __construct() {
		$this->create_modal();
		$this->add_actions();
	}

	/**
	 * Create modal
	 */
	function create_modal() {
		add_filter( 'wp_footer', array( $this, 'create_modal_content' ), 0 );
	}

	function create_modal_content() { ?>
	
		<div id="wbounce-modal" class="wbounce-modal" style="display:none">
			<div class="underlay"></div>
			<div id="wbounce-modal-sub" class="modal">
				<?php 
					if (stripslashes(get_option(WBOUNCE_OPTION_KEY.'_content')) != '') {
						echo stripslashes(get_option(WBOUNCE_OPTION_KEY.'_content'));
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
				<p><a href="http://kevinw.de/" target="_blank" style="color:#009000;font-size:1.25em"><h4>wBounce by Kevin Weber</h4></a></p>
				<p style="font-size:1.2em">I\'m the developer of this plugin. Feel free to contact and follow me <a href="https://twitter.com/kevinweber" title="Kevin Weber on Twitter" target="_blank" style="color:#4099FF">on Twitter</a>. And subscribe to my list for WordPress enthusiasts:</p>

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
	 * Add actions
	 */
	function add_actions() {
		add_action( 'wp_head', array( $this, 'custom_css') );
		add_action( 'wp_footer', array( $this, 'wp_footer'), 10, WBOUNCE_OPTION_KEY.'-functions' );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_jquery' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style') );
	}

	/**
	 * Add Scripts into Footer
	 */
	function wp_footer() { ?>
		<script>

		// // Javascript-only version (does not work work optimal on any site and in any case; no full cross-browser support)
		// document.body.onload=function(){
		// 	var modalId = document.getElementById('wbounce-modal');
		// 	var modalIdClass = modalId.getElementsByTagName('modal')[0];
		// 	ouibounce(modalId, {
		//       	<?php
	 //      		// Aggressive Mode
	 //      		if (
	 //      			( get_option(WBOUNCE_OPTION_KEY.'_aggressive_mode') == '1' ) ||
	 //      			( current_user_can( 'manage_options' ) && ( get_option(WBOUNCE_OPTION_KEY.'_test_mode') == '1' ) )
	 //      		) {
	 //      			echo 'aggressive:true,';
		//       	}
	 //      		// Timer
	 //      		if ( get_option(WBOUNCE_OPTION_KEY.'_timer') != "" ) {
	 //      			echo 'timer:'.get_option(WBOUNCE_OPTION_KEY.'_timer').',';
	 //      		}
		//       	?>
		//       	// Callback
		// 		// callback: function() {
		// 		// 	ga('send', 'event', 'wbounce', 'bounce', document.URL);
		// 		//	// do_action ...
		// 		// },
		// 	});
		// 	addListener(document.body, 'click', function(e) {
		//             modalId.style.display = 'none';
		//     	}
		// 	);
		// 	addListener(document.getElementById('wbounce-modal-sub'), 'click', function(e) {
		// 		cancelBubble(e);
		// 	});

		// 	var matchClass = 'modal-footer';
		//     var elems = document.getElementsByTagName('*'), i;
		//     for (i in elems) {
		//         if((' ' + elems[i].className + ' ').indexOf(' ' + matchClass + ' ')
		//                 > -1) {

		// 			addListener(elems[i], 'click', function(e) {
		// 				modalId.style.display = 'none';
		// 			});

		//         }
		//     }

		// 	// Cancel event bubbling (as described here: http://www.javascripter.net/faq/canceleventbubbling.htm)
		// 	function cancelBubble(e) {
		// 		var evt = e ? e:window.event;
		// 		if (evt.stopPropagation)    evt.stopPropagation();
		// 		if (evt.cancelBubble!=null) evt.cancelBubble = true;
		// 	}

		// 	/**
		// 	 * Utility to wrap the different behaviors between W3C-compliant browsers
		// 	 * and IE when adding event handlers.
		// 	 */
		// 	function addListener(element, type, callback) {
		// 	 if (element.addEventListener) element.addEventListener(type, callback);
		// 	 else if (element.attachEvent) element.attachEvent('on' + type, callback);
		// 	}
		// };



		// jQuery version
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
	      		// Timer
	      		if ( get_option(WBOUNCE_OPTION_KEY.'_timer') != "" ) {
	      			echo 'timer:'.get_option(WBOUNCE_OPTION_KEY.'_timer').',';
	      		}
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
		wp_enqueue_script( WBOUNCE_OPTION_KEY.'-function', plugins_url( 'js/min/'.WBOUNCE_OPTION_KEY.'-ck.js' , plugin_dir_path( __FILE__ ) ) );	
	}

	/**
	 * Add stylesheet
	 */
	function enqueue_style() {
		wp_register_style( WBOUNCE_OPTION_KEY.'-style', plugins_url('css/min/'.WBOUNCE_OPTION_KEY.'.css', plugin_dir_path( __FILE__ ) ) );
		wp_register_style( WBOUNCE_OPTION_KEY.'-extended-style', plugins_url('css/min/'.WBOUNCE_OPTION_KEY.'_extended.css', plugin_dir_path( __FILE__ ) ) );
		wp_enqueue_style( array( WBOUNCE_OPTION_KEY.'-style', WBOUNCE_OPTION_KEY.'-extended-style' ) );
	}

	/**
	 * Add Custom CSS
	 */
	function custom_css(){
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
     	wp_enqueue_script( 'jquery' );
 	}
}