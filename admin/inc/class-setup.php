<?php
/**
 * @package Admin + Frontend
 */
class WBOUNCE_Setup {

	/*
	 * Available magic variables
	 * @return Array
	 */
	function text_content_magic_arr() {
		$text_content_magic_arr = array(
			'title' => WBOUNCE_OPTION_KEY.'_title',
			'text' => WBOUNCE_OPTION_KEY.'_text',
			'cta' => WBOUNCE_OPTION_KEY.'_cta',
			'url' => WBOUNCE_OPTION_KEY.'_url',
		);
		return $text_content_magic_arr;
	}

}