<?php
	add_action( 'add_meta_boxes', 'gabfire_meta_box_add' );

	function gabfire_meta_box_add()
	{
		add_meta_box( '', 'Gabfire Custom Fields', 'gabfire_meta_box', 'post', 'normal', 'high' );
		add_meta_box( '', 'Gabfire Custom Fields', 'gabfire_meta_box', 'page', 'normal', 'high' );
		add_meta_box( '', 'Gabfire Custom Fields', 'gabfire_meta_box', 'gab_gallery', 'normal', 'high' );
	}

	function gabfire_meta_box( $post )
	{
		$values = get_post_custom( $post->ID );
		$video = isset( $values['iframe'] ) ? esc_attr( $values['iframe'][0] ) : '';
		$feapost = isset( $values['featured'] ) ? esc_attr( $values['featured'][0] ) : '';  
		wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
		?>		
			
		<p class="gab_caption">Video URL</p>
		<div class="gab_fieldrow">
			<div class="gab_fieldname"><label for="iframe"><?php _e('You can add any Youtube, Vimeo, Dailymotion or Screenr video url into this box','artcltr'); ?></label></div>
			<div class="gab_fieldinput"><input type="text" class="gab_textfield" name="iframe" class="regular-text" id="iframe" value="<?php echo $video; ?>" /></div>
			<div class="clear"></div>
		</div>
		
		<hr class="gab_hr">
		
		<p class="gab_caption">Is Featured?</p>
		<div class="gab_fieldrow">
			<div class="gab_fieldname"><label for="featured"><label for="featured"><?php _e('If featured post option set as custom fields on theme options page; check this box to display this post on featured section of front page','artcltr'); ?></label></div>
			<div class="gab_fieldinput"><input type="checkbox" id="featured" name="featured" <?php checked( $feapost, 'true' ); ?> />  </div>
			<div class="clear"></div>
		</div>	
		
		<?php	
	}

	add_action( 'save_post', 'gabfire_meta_box_save' );
	function gabfire_meta_box_save( $post_id )
	{
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
		// if our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
		
		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' ) ) return;
		
		// now we can actually save the data
		$allowed = array( 
			'a' => array( // on allow a tags
				'href' => array() // and those anchords can only have href attribute
			)
		);
		
		// Probably a good idea to make sure your data is set
		if( isset( $_POST['iframe'] ) && !empty( $_POST['iframe'] ) )
			update_post_meta( $post_id, 'iframe', wp_kses( $_POST['iframe'], $allowed ) );

		// This is purely my personal preference for saving check-boxes  
		$chk = isset( $_POST['featured'] ) && $_POST['featured'] ? 'true' : 'false';  
		update_post_meta( $post_id, 'featured', $chk );  
	
	}
	
	if (is_admin()) {	
		add_action('admin_print_styles-post.php', 'gabfire_adminstyle');
		add_action('admin_print_styles-post-new.php', 'gabfire_adminstyle');

		function gabfire_adminstyle() {
			wp_enqueue_style('adminstyle', get_template_directory_uri() .'/inc/custom-fields.css');
		}
	}