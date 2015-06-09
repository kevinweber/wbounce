<?php
class WBOUNCE_Shortcodes {

	function __construct() {
		$this->create_magic_shortcodes();
	}

	/*
	 * Available magic variables
	 * @return Array
	 */
	private function text_content_magic_arr() {
		$wbounce_setup = new WBOUNCE_Setup();
		$arr = $wbounce_setup->text_content_magic_arr();
		return $arr;
	}

	private function create_magic_shortcodes() {
		$arr = $this->text_content_magic_arr();

		foreach ($arr as $key => $value) {
			add_shortcode( WBOUNCE_OPTION_KEY.'-'.$key, array( $this, 'magic_shortcode' ) );
		}
	}

	private function create_php_shortcodes() {
		add_shortcode( WBOUNCE_OPTION_KEY.'-php', array( $this, 'php_shortcode' ) );
	}

	/*
	 * Return content depending on the shortcode
	 * This function is specialised for "magic shortcodes"
	 * The $tag must have a format like this: 'wbounce-title' ('wbounce' and the dash at the beginning are important)
	 */
	function magic_shortcode( $atts, $content = null, $tag ) {
		$templateEngine = get_option(WBOUNCE_OPTION_KEY.'_template_engine');
		
	    $a = shortcode_atts( array(
	        'default' => ''
	    ), $atts );

	    // Find the first occurrence of "-" in the given tag and connect it with the WBOUNCE_OPTION_KEY
	    $type = WBOUNCE_OPTION_KEY.'_'.substr(strstr($tag, '-'), 1);

	    if ( $this->is_magic_override_available( $type ) && $templateEngine == 'enabled' ) {
	    	return $this->get_override_text( $type );
	    }
	    else if ( $a['default'] != '' ) {
	    	return $a['default'];
	    }
	    else return;
	}

	/*
	 * When the template "magic override" is available, return true
	 */
	private function is_magic_override_available( $type ) {
		// If the template is not available, return false
		$template = get_post_meta(get_the_ID(), WBOUNCE_OPTION_KEY.'_template', true);
		if ( $template != 'magic' ) return;

		$test = get_post_meta(get_the_ID(), $type, true);
		return $test != '' ? true : false;
	}

	/*
	 * Return $text depending on the type of the text that should be overridden
	 */
	private function get_override_text( $type ) {
		$text = get_post_meta(get_the_ID(), $type, true);
		return $text;
	}

}

new WBOUNCE_Shortcodes();