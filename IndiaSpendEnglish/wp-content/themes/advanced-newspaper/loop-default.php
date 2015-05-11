<?php if(is_author()) { ?>
<div class="gab_authorInfo">
	<?php global $wp_query; $curauth = $wp_query->get_queried_object(); ?>
	<div class="gab_authorPic">
		<?php echo get_avatar( $curauth->user_email, '50' ); ?>
	</div>
	<strong><?php _e('Stories written by','advanced'); ?> <?php echo $curauth->nickname; ?></strong><br /><?php echo $curauth->description; ?>
	<div class="clear"></div>
</div>	
<?php } ?>

<?php 
$count = 1;
if (have_posts()) : while (have_posts()) : the_post();			
$gab_thumb = get_post_meta($post->ID, 'thumbnail', true);
$gab_video = get_post_meta($post->ID, 'video', true);
$gab_flv = get_post_meta($post->ID, 'videoflv', true);
$ad_flv = get_post_meta($post->ID, 'adflv', true);
$gab_iframe = get_post_meta($post->ID, 'iframe', true);	

 ?>
	
	<div id="post-<?php the_ID(); ?>" <?php post_class('entry');?>>	

		<h2 class="entry_title">
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
		</h2>
		
		<?php /* check if this is a video */ 
		if (($gab_flv !== '') or ($gab_video !== '') or ($gab_iframe !== '') ) 
		{ 
			gab_media(array(
					'name' => 'an-generic',
					'imgtag' => 1,
					'link' => 1,
					'enable_video' => 1,
					'catch_image' => 0,
					'enable_thumb' => 0,
					'media_width' => 80,
					'media_height' => 66,
					'thumb_align' => 'alignleft',
					'enable_default' => 0
				));
			 } else {
			 /* else, display post thumbnail */
				gab_media(array(
					'name' => 'an-generic',
					'imgtag' => 1,
					'link' => 1,
					'enable_video' => 0,
					'catch_image' => of_get_option('of_catch_img', 0),
					'enable_thumb' => 1,
					'resize_type' => 'c',
					'media_width' => 80,
					'media_height' => 66,
					'thumb_align' => 'alignleft',
					'enable_default' => 0
				));
		}
		?>
		<p><?php echo string_limit_words(get_the_excerpt(), 30); ?>&hellip;</p>
		<?php gab_postmeta();	?>

	</div>

<?php $count++; endwhile; else: ?>

	<h2 class="entry_title">
		<p><?php _e( 'Nothing Found.', 'advanced' ); ?></p>
	</h2>
	<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'advanced' ); ?></p>
<?php endif; ?>