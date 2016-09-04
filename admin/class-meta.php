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
				__( 'wBounce by Kevin Weber', WBOUNCE_TD ),
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

		// wp_nonce_field( 'add_wbounce_meta_box_nonce', 'wbounce_meta_box_nonce' );
		?>

		<h4 id="wbounce-status-group"><?php esc_html_e( 'Status', WBOUNCE_TD ); ?></h4>
		<p>
			<label for="<?php echo $select_name; ?>">
				<?php
					printf( __( 'Use wBounce on this %s?', WBOUNCE_TD ),
						get_current_screen()->post_type
					);
				?>				
			</label>
		</p>
		<p>
			<select class="select" type="select" name="<?php echo $select_name; ?>" id="<?php echo $select_name; ?>">
			<?php $meta_element_class = get_post_meta($id, $select_name, true);	?>
		      <option value="default" <?php selected( $meta_element_class, 'default' ); ?>><?php esc_html_e( 'Default', WBOUNCE_TD ); ?></option>
		      <option value="on" <?php selected( $meta_element_class, 'on' ); ?>><?php esc_html_e( 'On', WBOUNCE_TD ); ?></option>
		      <option value="off" <?php selected( $meta_element_class, 'off' ); ?>><?php esc_html_e( 'Off', WBOUNCE_TD ); ?></option>
			</select>
		</p>

		<?php if (get_option(WBOUNCE_OPTION_KEY.'_template_engine') != 'disabled') { ?>
			<h4 id="wbounce-template-group"><?php esc_html_e( 'Template Engine', WBOUNCE_TD ); ?></h4>
			<p><label for="<?php echo $select_template; ?>"><?php esc_html_e( 'Which template should be used?', WBOUNCE_TD ); ?></label></p>
			<p>
				<select class="select" type="select" name="<?php echo $select_template; ?>" id="<?php echo $select_template; ?>">
				<?php $meta_element_class = get_post_meta($id, $select_template, true);	?>
			      <option value="default" <?php selected( $meta_element_class, 'default' ); ?>><?php esc_html_e( 'Global Default', WBOUNCE_TD ); ?></option>
			      <option value="magic" <?php selected( $meta_element_class, 'magic' ); ?>><?php esc_html_e( 'Magic Override', WBOUNCE_TD ); ?></option>
			      <option value="all" <?php selected( $meta_element_class, 'all' ); ?>><?php esc_html_e( 'Total Override', WBOUNCE_TD ); ?></option>
				</select>
			</p>


			<div id="wbounce-magic" class="hidden-by-default" style="display:none;">
				<p>
					<?php
						printf( __( 'This template allows you to override the [wbounce-magic] shortcodes that can be placed in your default template. See <a href="%s" title="wBounce Documentation" target="_blank">documentation</a>.', WBOUNCE_TD ),
							'http://kevinw.de/wb-doc-te'
						);
					?>
				</p>

				<label for="<?php echo $text_content_magic_arr['title']; ?>"><?php esc_html_e( 'Title', WBOUNCE_TD ); ?></label>
				<input placeholder="[wbounce-title]" type="text" name="<?php echo $text_content_magic_arr['title']; ?>" id="<?php echo $text_content_magic_arr['title']; ?>" value="<?php echo $text_content_magic_arr_input['title']; ?>" style="width:100%;" />

				<label for="<?php echo $text_content_magic_arr['text']; ?>"><?php esc_html_e( 'Text', WBOUNCE_TD ); ?></label>
				<textarea rows="5" type="text" name="<?php echo $text_content_magic_arr['text']; ?>" placeholder="[wbounce-text]" style="width:100%;"><?php echo $text_content_magic_arr_input['text']; ?></textarea>

				<label for="<?php echo $text_content_magic_arr['cta']; ?>"><?php esc_html_e( 'Call to action', WBOUNCE_TD ); ?></label>
				<input placeholder="[wbounce-cta]" type="text" name="<?php echo $text_content_magic_arr['cta']; ?>" id="<?php echo $text_content_magic_arr['cta']; ?>" value="<?php echo $text_content_magic_arr_input['cta']; ?>" style="width:100%;" />

				<label for="<?php echo $text_content_magic_arr['url']; ?>"><?php _e( 'URL (http://&hellip;)', WBOUNCE_TD ); ?></label>
				<input placeholder="[wbounce-url]" type="text" name="<?php echo $text_content_magic_arr['url']; ?>" id="<?php echo $text_content_magic_arr['url']; ?>" value="<?php echo $text_content_magic_arr_input['url']; ?>" style="width:100%;" />
			</div>


			<p id="wbounce-all" class="hidden-by-default" style="display:none;">
				<textarea rows="10" type="text" name="<?php echo $text_content; ?>" placeholder="<?php _e( 'Insert some content to override the default', WBOUNCE_TD ); ?>" style="width:100%;"><?php echo $text; ?></textarea>
			</p>
		<?php } else {
			echo __( '<p><i>Template engine disabled (<a href="options-general.php?page=' . WBOUNCE_OPTION_KEY . '.php">settings</a>).</i></p>', WBOUNCE_TD );
		} ?>

	<?php do_action( WBOUNCE_OPTION_KEY.'_meta_box_after', $id );
	}

	function save( $post_id ) {

		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
		// // If our nonce isn't there, or we can't verify it, bail
		// if ( 
		// 	!empty( $_POST ) &&
		// 	(
		// 		!isset( $_POST['wbounce_meta_box_nonce'] )
		// 		|| !wp_verify_nonce( $_POST['wbounce_meta_box_nonce'], 'add_wbounce_meta_box_nonce' ) 
		// 	)
		// ) {
		// 	print 'Sorry, your nonce did not verify.';
		// 	exit;
		// } else {

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
					update_post_meta( $post_id, $value, esc_attr( $_POST[$value] ) );
			}

			// TEXTAREA
			$text_content = $this->text_content;
			if( isset( $_POST[$text_content] ) )
				update_post_meta( $post_id, $text_content, esc_attr( $_POST[$text_content] ) );
			
			do_action( WBOUNCE_OPTION_KEY.'_save_post', $post_id );

		// }

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
		$wbounce_title = __( 'Default', WBOUNCE_TD );

	    switch ($column_name) {
	    case 'wbounce':
	        $widget_id = get_post_meta( $id, $select_name, true );

	        if ($widget_id) {
	        	$get_post_custom = get_post_meta( $id, $select_name, true );
		        switch ($get_post_custom) {
		        	case 'on' :
		        		$wbounce_status = 'on';
		        		$wbounce_title = __( 'On', WBOUNCE_TD );
		        		break;
		        	case 'off' :
		        		$wbounce_status = 'off';
		        		$wbounce_title = __( 'Off', WBOUNCE_TD );
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
			wp_enqueue_style( 'wbounce-edit-page', plugins_url('css/min/edit-page.min.css', __FILE__) );
		}
	}

}

new Wbounce_Meta();