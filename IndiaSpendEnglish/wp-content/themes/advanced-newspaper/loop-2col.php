<div id="two-column">
<?php 
$count = 1;
if (have_posts()) : while (have_posts()) : the_post();			
$gab_thumb = get_post_meta($post->ID, 'thumbnail', true);
$gab_video = get_post_meta($post->ID, 'video', true);
$gab_flv = get_post_meta($post->ID, 'videoflv', true);
$ad_flv = get_post_meta($post->ID, 'adflv', true);
$gab_iframe = get_post_meta($post->ID, 'iframe', true);	
?>
	
	<?php if($count % 2 == 0) { $align = 'entry right'; } else { $align = 'entry left'; } ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class($align);?>>
		<h2 class="entry_title">
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
		</h2>

		<?php
			gab_media(array(
				'name' => 'an-arc2col',
				'imgtag' => 1,
				'link' => 1,
				'enable_video' => 0,
				'catch_image' => of_get_option('of_catch_img', 0),
				'enable_thumb' => 1,
				'resize_type' => 'w',
				'media_width' => 60, 
				'media_height' => 40, 
				'thumb_align' => 'alignleft',
				'enable_default' => 0
			));
			the_excerpt(); 
		?>

		<div class="clear"></div>
	</div>
	
	<?php if($count % 2 == 0) { echo '<div class="clear"></div>'; } ?>
<?php $count++; endwhile; endif; ?>
</div><!-- #2column -->
<div class="clear"></div>