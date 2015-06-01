<?php
/**
 * @package Admin
 */
class Wbounce_Meta {

	private $select_name = 'wbounce_status';
	private $select_template = 'wbounce_template';
	private $text_content = 'wbounce_override';

	function __construct() {
		$this->init_meta_boxes();
		$this->init_post_columns();
	}

	/**
	 * Add additonal fields to the page where you create your posts and pages
	 * (Based on http://wp.tutsplus.com/tutorials/plugins/how-to-create-custom-wordpress-writemeta-boxes/)
	 */
	function init_meta_boxes() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	function add_meta_box() {

		$screens = array( 'post', 'page' );

		foreach ( $screens as $screen ) {
			add_meta_box(
				'meta-box-wbounce',
				'wBounce by Kevin Weber',
				array( $this, 'meta_box' ),
				$screen,
				'side',	// position
				'high'	// priority
			);
		}

	}

	function meta_box( $post ) {
		$id = $post->ID;
		$values = get_post_custom( $id );
		// $check = isset( $values['wbounce_check_custom'] ) ? esc_attr( $values['wbounce_check_custom'][0] ) : '';
		
		$select_name = $this->select_name;
			$selected = isset( $values[$select_name] ) ? esc_attr( $values[$select_name][0] ) : '';

		$select_template = $this->select_template;
		$selected = isset( $values[$select_template] ) ? esc_attr( $values[$select_template][0] ) : '';

		$text_content = $this->text_content;
		$text = isset( $values[$text_content] ) ? esc_attr( $values[$text_content][0] ) : '';

		$text_content_magic_arr = $this->text_content_magic_arr();
		foreach ($text_content_magic_arr as $key => $value) {
			$text_content_magic_arr_input[$key] = isset( $values[$text_content_magic_arr[$key]] ) ? esc_attr( $values[$text_content_magic_arr[$key]][0] ) : '';
		}



		?>

<!-- 		<h4>Custom Text</h4>
		<p>
			<p>Description &hellip;</p>
			<input type="text" name="oembed_link" id="oembed_link" value="<?php //echo $text; ?>" style="width:100%;" />
			<label for="oembed_link">E.g. <i>example</i></label>	
		</p> -->

<!-- 		<h4>Checkbox</h4>
		<p>
			<input type="checkbox" name="wbounce_check_custom" id="wbounce_check_custom" <?php // checked( $check, 'on' ); ?> />
			<label for="wbounce_check_custom">If checked: Display ...</label>
		</p> -->

		<h4 id="wbounce-status-group">Status</h4>
		<p><label for="<?php echo $select_name; ?>">Use wBounce on this <?php echo get_current_screen()->post_type; ?>?</label></p>
		<p>
			<select class="select" type="select" name="<?php echo $select_name; ?>" id="<?php echo $select_name; ?>">
			<?php $meta_element_class = get_post_meta($id, $select_name, true);	?>
		      <option value="default" <?php selected( $meta_element_class, 'default' ); ?>>Default</option>
		      <option value="on" <?php selected( $meta_element_class, 'on' ); ?>>On</option>
		      <option value="off" <?php selected( $meta_element_class, 'off' ); ?>>Off</option>
			</select>
		</p>

		<?php if (get_option(WBOUNCE_OPTION_KEY.'_template_engine') != 'original') { ?>
			<h4 id="wbounce-template-group">Template</h4>
			<p><label for="<?php echo $select_template; ?>">Which template should be used?</label></p>
			<p>
				<select class="select" type="select" name="<?php echo $select_template; ?>" id="<?php echo $select_template; ?>">
				<?php $meta_element_class = get_post_meta($id, $select_template, true);	?>
			      <option value="default" <?php selected( $meta_element_class, 'default' ); ?>>Global Default</option>
			      <option value="magic" <?php selected( $meta_element_class, 'magic' ); ?>>Magic Override</option>
			      <option value="all" <?php selected( $meta_element_class, 'all' ); ?>>Total Override</option>
				</select>
			</p>


			<div id="wbounce-magic" class="hidden-by-default" style="display:none;">
				<p>This template allows you to override the [wbounce-magic] shortcodes that can be placed in your default template. See <a href="">documentation [tbd]</a>.</p>

				<label for="<?php echo $text_content_magic_arr['title']; ?>">Title</label>
				<input placeholder="[wbounce-title]" type="text" name="<?php echo $text_content_magic_arr['title']; ?>" id="<?php echo $text_content_magic_arr['title']; ?>" value="<?php echo $text_content_magic_arr_input['title']; ?>" style="width:100%;" />

				<label for="<?php echo $text_content_magic_arr['text']; ?>">Text</label>
				<textarea rows="5" type="text" name="<?php echo $text_content_magic_arr['text']; ?>" placeholder="[wbounce-text]" style="width:100%;"><?php echo $text_content_magic_arr_input['text']; ?></textarea>

				<label for="<?php echo $text_content_magic_arr['cta']; ?>">Call to action</label>
				<input placeholder="[wbounce-cta]" type="text" name="<?php echo $text_content_magic_arr['cta']; ?>" id="<?php echo $text_content_magic_arr['cta']; ?>" value="<?php echo $text_content_magic_arr_input['cta']; ?>" style="width:100%;" />

				<label for="<?php echo $text_content_magic_arr['url']; ?>">URL (http://&hellip;)</label>
				<input placeholder="[wbounce-url]" type="text" name="<?php echo $text_content_magic_arr['url']; ?>" id="<?php echo $text_content_magic_arr['url']; ?>" value="<?php echo $text_content_magic_arr_input['url']; ?>" style="width:100%;" />
			</div>


			<p id="wbounce-all" class="hidden-by-default" style="display:none;">
				<textarea rows="10" type="text" name="<?php echo $text_content; ?>" placeholder="Insert some content to override the default" style="width:100%;"><?php echo $text; ?></textarea>
			</p>
		<?php } else {
			echo '<p><i>Template engine disabled (<a href="options-general.php?page='.WBOUNCE_OPTION_KEY.'.php">settings</a>).</i></p>';
		} ?>

	<?php do_action( WBOUNCE_OPTION_KEY.'_meta_box_after', $id );
	}

	function save( $post_id ) {

		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
		// If our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
		
		// SELECT
		$select_name = $this->select_name;
		if( isset( $_POST[$select_name] ) )
			update_post_meta( $post_id, $select_name, esc_attr( $_POST[$select_name] ) );

		// SELECT
		$select_template = $this->select_template;
		if( isset( $_POST[$select_template] ) )
			update_post_meta( $post_id, $select_template, esc_attr( $_POST[$select_template] ) );

		// MAGIC SHORTCODES (INPUT or TEXTAREA)
		$text_content_magic_arr = $this->text_content_magic_arr();
		foreach ($text_content_magic_arr as $key => $value) {
			if( isset( $_POST[$value] ) )
				update_post_meta( $post_id, $value, wp_kses_post( $_POST[$value] ) );
		}

		// TEXTAREA
		$text_content = $this->text_content;
		if( isset( $_POST[$text_content] ) )
			update_post_meta( $post_id, $text_content, wp_kses_post( $_POST[$text_content] ) );
		
		do_action( WBOUNCE_OPTION_KEY.'_save_post', $post_id );

	}

	/*
	 * Available magic variables
	 */
	private function text_content_magic_arr() {
		$wbounce_setup = new WBOUNCE_Setup();
		$arr = $wbounce_setup->text_content_magic_arr();
		return $arr;
	}

	/**
	 * Add custom column
	 * (Based on http://shibashake.com/wordpress-theme/expand-the-wordpress-quick-edit-menu)
	 */
	function init_post_columns() {

		$screens = array( 'posts', 'pages' );

		foreach ( $screens as $screen ) {
			add_filter( 'manage_'.$screen.'_columns', array( $this, 'add_post_columns' ) );
			add_action( 'manage_'.$screen.'_custom_column', array( $this, 'render_post_columns' ), 10, 2 );
		}

		add_action( 'admin_footer', array( $this, 'post_columns_css' ) );
	}
	 
	function add_post_columns($columns) {
	    $columns['wbounce'] = 'wBounce';
	    return $columns;
	}

	function render_post_columns($column_name, $id) {
		$select_name = $this->select_name;
		$wbounce_status = 'default';
		$wbounce_title = 'Default';

	    switch ($column_name) {
	    case 'wbounce':
	        $widget_id = get_post_meta( $id, $select_name, true );

	        if ($widget_id) {
	        	$get_post_custom = get_post_meta( $id, $select_name, true );
		        switch ($get_post_custom) {
		        	case 'on' :
		        		$wbounce_status = 'on';
		        		$wbounce_title = 'On';
		        		break;
		        	case 'off' :
		        		$wbounce_status = 'off';
		        		$wbounce_title = 'Off';
		        		break;
		        }
	        }
	    	echo '<div title="'. $wbounce_title .'" class="wbounce-status '. $wbounce_status .'"></div>';
	    }

	}

	/**
	 * Add CSS for post columns
	 */
	function post_columns_css() {
		$screen = get_current_screen();
		if ( $screen->base == 'edit' ) {
			wp_enqueue_style( 'wbounce-edit-page', plugins_url('../css/min/edit-page.css', __FILE__) );
		}
	}

}

new Wbounce_Meta();