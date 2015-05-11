<?php
/* Fancybox theme gallery. This file is called into single.php */
$number_photos = -1; 		// -1 to display all
$photo_size = 'large';		// The standard WordPress size to use for the large image
$thumb_size = 'thumbnail';	// The standard WordPress size to use for the thumbnail
$thumb_width = 125;			// Size of thumbnails to embed into img tag
$thumb_height = 125;			// Size of thumbnails to embed into img tag
$photo_width = 600;		// Width of photo
$themeurl = get_template_directory_uri();

$attachments = get_children( array(
'post_parent' => $post->ID,
'numberposts' => $number_photos,
'post_type' => 'attachment',
'post_mime_type' => 'image',
'order' => 'ASC', 
'orderby' => 'menu_order date')
);

$siteurl = get_bloginfo('template_url');

if ( !empty($attachments) ) :
	$counter = 0;
	$photo_output = '';
	$thumb_output = '';	
	foreach ( $attachments as $att_id => $attachment ) {
		$counter++;
		
		# Caption
		$caption = "";
		if ($attachment->post_excerpt) 
			$caption = $attachment->post_excerpt;	
			
		# Large photo
		$src = wp_get_attachment_image_src($att_id, $photo_size, true);
			if(function_exists('is_multisite')) { 
				$photo_output .= '
					<a rel="gab_gallery" title="'.$caption.'" href="' . esc_url($themeurl) . '/timthumb.php?src='.urlencode(redirect_wpmu($src[0])).'&amp;q=90&amp;w='.$photo_width.'&amp;zc=1">
						<img class="inner_gallerythumb" src="' . esc_url($themeurl) . '/timthumb.php?src='.urlencode(redirect_wpmu($src[0])).'&amp;q=90&amp;w='.$thumb_width.'&amp;h='.$thumb_height.'&amp;zc=1" width="'.$thumb_width.'" height="'.$thumb_height.'" alt="" />
					</a>';
			} else {
				$photo_output .= '
					<a rel="gab_gallery" href="' . esc_url($themeurl) . '/timthumb.php?src='.urlencode(redirect_wpmu($src[0])).'&amp;q=90&amp;w='.$photo_width.'&amp;zc=1">
						<img src="' . esc_url($themeurl) . '/timthumb.php?src='.urlencode($src[0]).'&amp;q=90&amp;w='.$thumb_width.'&amp;h='.$thumb_height.'&amp;zc=1" width="'.$thumb_width.'" height="'.$thumb_height.'" alt="" />
					</a>';
			}
		# Thumbnail
		$src = wp_get_attachment_image_src($att_id, $thumb_size, true);
		$thumb_output .= ''; 
	}  
endif; ?>

<?php if ($counter > 1) : ?>
	<div id="fancyboxgal">
		<?php echo $photo_output; ?>
	</div>
<?php endif; ?>