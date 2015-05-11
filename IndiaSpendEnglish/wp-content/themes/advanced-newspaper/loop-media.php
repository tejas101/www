<div class="post" id="gab_gallery">
	<?php 
	$count = 1;
	if (have_posts()) : while (have_posts()) : the_post();			
	$gab_thumb = get_post_meta($post->ID, 'thumbnail', true);
	$gab_video = get_post_meta($post->ID, 'video', true);
	$gab_flv = get_post_meta($post->ID, 'videoflv', true);
	$ad_flv = get_post_meta($post->ID, 'adflv', true);
	$gab_iframe = get_post_meta($post->ID, 'iframe', true);	
	?>
		<div id="post-<?php the_ID(); ?>" class="media-wrapper <?php if($count % 4 == 0) { echo ' last'; } ?>">
			
			<div class="entry">
				
				<?php 
				if (($gab_flv) or ($gab_video) or ($gab_iframe) ) {
					gab_media(array(
						'name' => 'gabfire',
						'enable_video' => 1,
						'enable_thumb' => 0,
						'media_width' => 208, 
						'media_height' => 180, 
						'thumb_align' => 'null', 
						'enable_default' => 0
					));
				} else { ?>
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
							<?php
							gab_media(array(
								'name' => 'an-media',
								'imgtag' => 1,
								'link' => 0,
								'enable_video' => 0,
								'enable_thumb' => 1,
								'catch_image' => of_get_option('of_catch_img', 0),
								'resize_type' => 'c',
								'media_width' => 208, 
								'media_height' => 180, 
								'thumb_align' => 'null', 
								'enable_default' => 1,
								'default_name' => 'archive-media.jpg'
								));
							?>
					</a>
				<?php } ?>
				
				<h2 class="entry_title s_title">
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h2>
				<p class="small-text">
					<?php _e('Posted by','advanced'); ?>  <?php the_author_posts_link(); ?> <?php _e('on','advanced'); ?> <?php echo get_the_date(''); ?><br />
					<?php _e('Filed under','advanced'); ?>
					<?php the_category(', '); echo get_the_term_list( $post->ID, 'gallery-cat', ': ', ' ', '' ); ?> 
				</p>					
			</div><!-- .entry -->
			<span class="entry-shadow"></span>
		</div><!-- .media-wrapper -->
			
		<?php if($count % 4 == 0) { echo '<div class="clear"></div>'; } ?>
	<?php $count++; endwhile; endif; ?>
	<div class="clear"></div>	
</div>
<div class="clear"></div>	