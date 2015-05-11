	<div class="widget">
		<div class="widgetinner">
			<h2 class="widgettitle"><?php _e('Media Gallery', 'advanced'); ?></h2>
			<?php query_posts( 'post_type=gab_gallery&paged=$paged&showposts='.of_get_option('of_an_nr19') ); ?>
				<?php 
				$count = 1;
				if (have_posts()) : while (have_posts()) : the_post();
				$gab_thumb = get_post_meta($post->ID, 'thumbnail', true);
				$gab_video = get_post_meta($post->ID, 'video', true);
				$gab_flv = get_post_meta($post->ID, 'videoflv', true);
				$ad_flv = get_post_meta($post->ID, 'adflv', true);
				$gab_iframe = get_post_meta($post->ID, 'iframe', true);	
				?>
					<div class="sidebar_media">
						<?php 
						gab_media(array(
							'name' => 'an-sidebarmedia',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 1,
							'enable_thumb' => 1,
							'catch_image' => of_get_option('of_catch_img', 0),
							'resize_type' => 'c', /* c to crop, h to resize only height, w to resize only width */
							'media_width' => 118, 
							'media_height' => 80, 
							'thumb_align' => 'aligncenter', 
							'enable_default' => 0
							)); 
						?>						
						
						<h2 class="posttitle">
							<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
						</h2>
					</div><!-- .sidebar-media -->
									
			<?php $count++; endwhile; endif; wp_reset_query(); ?>
		</div><!-- .widgetinner -->
	</div><!-- .widget -->