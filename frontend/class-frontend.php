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
		include_once( WBOUNCE_PATH . 'frontend/class-shortcodes.php' );
	}

	/**
	 * Create modal
	 */
	function create_modal_content() { ?>
		<div id="wbounce-modal" class="wbounce-modal underlay" style="display:none">
			<div id="wbounce-modal-flex" class="wbounce-modal-flex">
				<div id="wbounce-modal-sub" class="wbounce-modal-sub">
					<?php
						$templateEngine = get_option(WBOUNCE_OPTION_KEY.'_template_engine');
						$templateEngineTemplate = get_post_meta(get_the_ID(), WBOUNCE_OPTION_KEY.'_template', true);
						$totalOverrideText = get_post_meta(get_the_ID(), WBOUNCE_OPTION_KEY.'_override', true);

						if ($templateEngineTemplate == 'all' && $totalOverrideText != '' && $templateEngine == 'enabled') {
                            printf( __( '%s', WBOUNCE_TD ), do_shortcode( html_entity_decode(stripslashes($totalOverrideText))) );
						}
						else if (stripslashes(get_option(WBOUNCE_OPTION_KEY.'_content')) != '') {
							printf( __( '%s', WBOUNCE_TD ), do_shortcode( html_entity_decode(stripslashes(get_option(WBOUNCE_OPTION_KEY.'_content'))) ) );
						}
						else if (current_user_can( 'manage_options' )) {
							printf( __( '%s', WBOUNCE_TD ), $this->create_modal_content_default_admin() );
						}
						else {
							printf( __( '%s', WBOUNCE_TD ), $this->create_modal_content_default() );
						}
					?>
				</div>
			</div>
		</div>
	<?php }

	function create_modal_content_default_admin() {
		$content_default = '
			<div class="modal-title">
	          <h3>' . __( 'Do you love this plugin as much as I do?', WBOUNCE_TD ) . '</h3>
	        </div>

	        <div class="modal-body" style="text-align:center">

	        	<p><a href="//kevinw.de/" target="_blank"><img src="//www.gravatar.com/avatar/9d876cfd1fed468f71c84d26ca0e9e33?d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536&s=100" style="-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;"></a></p>
				<p><a href="//kevinw.de/wbounce" target="_blank" style="color:#009000;font-size:20px"><h4>' . __( 'wBounce by Kevin Weber', WBOUNCE_TD ) . '</h4></a></p>
				<p style="font-size:15px">' . __( 'I&#39;m the developer of this plugin. Feel free to contact and follow me <a href="//twitter.com/kevinweber" title="Kevin Weber on Twitter" target="_blank" style="color:#4099FF">on Twitter</a>. And subscribe to my list for WordPress enthusiasts:', WBOUNCE_TD ) . '</p>

				<div id="mc_embed_signup">
				<form action="//kevinw.us2.list-manage.com/subscribe/post?u=f65d804ad274b9c8812b59b4d&amp;id=39ca44d8d3" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<div class="mc-field-group updated">
						<input type="email" value="' . __( 'Enter your email address', WBOUNCE_TD ) . '" name="EMAIL" class="required email" id="mce-EMAIL" onclick="this.focus();this.select()" onfocus="if(this.value == \'\') { this.value = this.defaultValue; }" onblur="if(this.value == \'\') { this.value = this.defaultValue; }">
						<input type="hidden" name="GROUPS" id="GROUPS" value="' . __( 'Signup via Plugin (frontend)', WBOUNCE_TD ) . '" />
						<input style="background-color:#009000" type="submit" value="' . __( 'Click to subscribe', WBOUNCE_TD ) . '" name="subscribe" id="mc-embedded-subscribe" class="button">
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
		echo apply_filters( WBOUNCE_OPTION_KEY.'_create_modal_content_default_admin', $content_default );
	}

	function create_modal_content_default() {
		$content_default = '
			<div class="modal-title">
	          <h3>' . __( 'Congrats!', WBOUNCE_TD ) . '</h3>
	        </div>

	        <div class="modal-body" style="text-align:center">

	        	<p><a href="//kevinw.de/wbounce/" title="WordPress plugin wBounce" target="_blank">wBounce</a> is succesfully installed and triggered.</p>
	        	<p>Now go ahead and set up your own modal.</p>

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


	function load_footer_script() {
      $WBOUNCE_CONFIG = [
        "cookieName" => "wBounce",
        "isAggressive" => $this->is_aggressive(),
        "isSitewide" => get_option(WBOUNCE_OPTION_KEY.'_sitewide') != '1',
        "hesitation" => $this->get_option('hesitation'),
        "openAnimation" => $this->is_animation_none('open') ? false : $this->get_option("open_animation"),
        "exitAnimation" => $this->is_animation_none('exit') ? false : $this->get_option("exit_animation"),
        "timer" => $this->get_option("timer"),
        "sensitivity" => $this->get_option("sensitivity"),
        "cookieExpire" => $this->get_option("cookieexpire"),
        "cookieDomain" => $this->get_option("cookiedomain"),
        "autoFire" => $this->get_option('autoFire'),
        "isAnalyticsEnabled" => get_option(WBOUNCE_OPTION_KEY.'_analytics') == '1'
      ];
      
      echo '<div id="wbounce-config" style="display: block;">';
      echo json_encode($WBOUNCE_CONFIG);
      echo '</div>'
    ?>

		<script>
		(function ( $ ) {
			if (typeof __gaTracker == 'function') {
				__gaTracker( function() {
				  window.ga = __gaTracker;
				});
			}

            $(function() {
                var WBOUNCE_CONFIG = JSON.parse($('#wbounce-config').text());
              
                if (typeof ga !== 'function') {
                  WBOUNCE_CONFIG.isAnalyticsEnabled = false;
                }

              console.log(WBOUNCE_CONFIG);
              
				var wBounceModal = document.getElementById('wbounce-modal');
				var wBounceModalSub = document.getElementById('wbounce-modal-sub');
				var wBounceModalFlex = document.getElementById('wbounce-modal-flex');

				// Assuming, if (animation !== 'none' or IE > 9)
				var isAnimationIn = false;
				var isAnimationOut = false;
				var animationInClass, animationOutClass;

				if (WBOUNCE_CONFIG.openAnimation) {
					animationInClass = 'animated ' + WBOUNCE_CONFIG.openAnimation;
                    isAnimationIn = true;
                }
              
                if (WBOUNCE_CONFIG.exitAnimation) {
                    animationOutClass = 'animated ' + WBOUNCE_CONFIG.exitAnimation;
                    isAnimationOut = true;
                }

				// Time to correct our assumption
				if (isIE() && isIE() < 10) {
					isAnimationIn = false;
					isAnimationOut = false;
					animationOutClass = 'belowIE10';
					$(wBounceModalSub).addClass(animationOutClass);
				} else {
					$(wBounceModalFlex).addClass('wbounce-modal-flex-activated');
				}

				if (typeof ouibounce !== 'undefined' && $.isFunction(ouibounce)) {
					var OUIBOUNCE_CONFIG = {
                      // Aggressive Mode
                      aggressive: WBOUNCE_CONFIG.isAggressive,
                      // Cookie per page (sitewide cookie)
                      sitewide: WBOUNCE_CONFIG.isSitewide,
                      // Custom cookie name
                      cookieName: WBOUNCE_CONFIG.cookieName,
                      cookieExpire: WBOUNCE_CONFIG.cookieExpire,
                      cookieDomain: WBOUNCE_CONFIG.cookieDomain,
                      // Timer (Set a min time before wBounce fires)
                      timer: parseInt(WBOUNCE_CONFIG.timer, 10),
                      sensitivity: parseInt(WBOUNCE_CONFIG.sensitivity, 10)
					};

                    WBOUNCE_CONFIG.hesitation = parseInt(WBOUNCE_CONFIG.hesitation, 10);
                  
                    // Hesitation
                    if (isInteger(WBOUNCE_CONFIG.hesitation)) {
                      OUIBOUNCE_CONFIG.delay = WBOUNCE_CONFIG.hesitation;
                    }
                  
                    console.log(OUIBOUNCE_CONFIG);
                  
                    function sendAnalyticsEvent(action) {
                      if (WBOUNCE_CONFIG.isAnalyticsEnabled) {
                        ga('send', 'event', 'wBounce', action, document.URL);
                      }
                    }
                  
					// Callback
					OUIBOUNCE_CONFIG.callback = function() {
						sendAnalyticsEvent("fired");
						if (isAnimationIn) {
							$(wBounceModalSub)
								.addClass(animationInClass)
								.one(
									'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
									function() { $(this).removeClass(animationInClass); }
								);
						}
					};

					// Init
			      	var _ouibounce = ouibounce(wBounceModal, OUIBOUNCE_CONFIG);
				}

                var $wBounceModal = $(wBounceModal);
              
				$wBounceModal.on('click', function() {
                  	hidePopup();
					sendAnalyticsEvent("hidden_outside");
				});

				$wBounceModal.find('.modal-close').on('click', function() {
					hidePopup();
					sendAnalyticsEvent("hidden_close");
				});

				$wBounceModal.find('.modal-footer').on('click', function() {
					hidePopup();
					sendAnalyticsEvent("hidden_footer");
				});

				$(wBounceModalSub).on('click', function(e) {
					e.stopPropagation();
				});

                $(document).keyup(function(e) {
                  if (e.which === 27 && $wBounceModal.is(":visible")) {
					hidePopup();
					sendAnalyticsEvent("hidden_escape");
                  }
                });

				function hidePopup() {
					if (!isAnimationOut) {
						return $wBounceModal.hide();
					}

					$(wBounceModalSub)
						.addClass(animationOutClass)
						.one(
							'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
							function() {
								$(this).removeClass(animationOutClass);
								$wBounceModal.hide()
							}
						);
				}

				/*
				 * AUTOFIRE JS
				 */
				var autoFire = parseInt(WBOUNCE_CONFIG.autoFire, 10);
                if (autoFire < 1000) {
                    autoFire = 1000;
                }

				function isInteger(x) {
					return (typeof x === 'number') && (x % 1 === 0);
				}
				function handleAutoFire( delay ) {
					if ( _ouibounce.isDisabled() ) return;
					setTimeout( _ouibounce.fire, delay );
				}
				if (isInteger(autoFire)) {
				  handleAutoFire( autoFire );
				}
                
				/**
				 * Reference: http://stackoverflow.com/a/15983064/2706988
				 * @returns {*}
                 */
				function isIE() {
					var myNav = navigator.userAgent.toLowerCase();
					return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
				}
			});
		})(jQuery);
		</script>
	<?php }

	function get_option( $optionname ) {
		return get_option(WBOUNCE_OPTION_KEY.'_'.$optionname);
	}
	function is_animation_none( $optionname ) {
		return ( get_option(WBOUNCE_OPTION_KEY.'_'.$optionname.'_animation') != "none" ) ? false : true;
	}

	function is_aggressive() {
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
			wp_enqueue_script( WBOUNCE_OPTION_KEY.'-function', plugins_url( 'js/min/'.WBOUNCE_OPTION_KEY.'.min.js' , plugin_dir_path( __FILE__ ) ), 'jquery', WBOUNCE_VERSION_NUM, $this->test_if_script_should_be_loaded_in_footer() );
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

        wp_register_style( WBOUNCE_OPTION_KEY.'-style', plugins_url('css/min/'.WBOUNCE_OPTION_KEY.'.min.css', plugin_dir_path( __FILE__ ) ) );
		wp_enqueue_style( WBOUNCE_OPTION_KEY.'-style' );
        
        if ((get_option(WBOUNCE_OPTION_KEY.'_open_animation') | get_option(WBOUNCE_OPTION_KEY.'_exit_animation'))
                != 'none') {
            wp_register_style( 'animate-style', plugins_url('css/min/animate.min.css', plugin_dir_path( __FILE__ ) ) );
            wp_enqueue_style( 'animate-style' );
        }
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
		
		$result = false;
		if (!isset($post->ID)) {
			$id = null;
		}
		else {
			$id = $post->ID;
		}


		// When the individual status for a page/post is 'off', all the other setting don't matter. So this has to be tested at first. 
		if ( get_post_meta( $id, 'wbounce_status', true ) && get_post_meta( $id, 'wbounce_status', true ) === 'off' ) {
			$result = true;
		}
		else if (
			( !get_option(WBOUNCE_OPTION_KEY.'_status_default') ) ||	// Fire when no option is defined yet
			( get_post_meta( $id, 'wbounce_status', true ) === 'on' ) ||
			( get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on' ) ||
			( get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_posts' && is_single() ) ||
			( get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_pages' && is_page() ) ||
			( get_option(WBOUNCE_OPTION_KEY.'_status_default') === 'on_posts_pages' && (is_single()||is_page()) )
		) {
			$result = false;
		}
		else
			$result = true;

		// wbounce_test_if_status_is_off
		$result = apply_filters( WBOUNCE_OPTION_KEY.'_test_if_status_is_off', $result );

		return $result;
 	}
}

new Wbounce_Frontend();