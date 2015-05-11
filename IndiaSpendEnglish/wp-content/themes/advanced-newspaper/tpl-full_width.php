<?php
	/*
		Template Name: Full Width
	*/
?>
<?php get_header(); ?>

	<div id="container">

			<div id="main" class="fullwidth">
			<span id="bcrum"><?php gab_breadcrumb(); ?></span>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('entry');?>>
						<h1 class="entry_title">
							<?php the_title(); ?>
						</h1>

						<?php
								
						// Display edit post link to site admin
						edit_post_link(__('Edit This Post','advanced'),'<p>','</p>'); 				
						
						// If there is a video, display it
						gab_media(array(  'imgtag' => 1,   'link' => 1,
							'name' => 'gab-fea',
							'enable_video' => 1,
							'video_id' => 'archive',
							'catch_image' => 0,
							'enable_thumb' => 0,
							'media_width' => 958, 
							'media_height' => 530, 
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						
						// Display content
						the_content();
						
						// make sure any advanceded content gets cleared
						echo '<div class="clear"></div>';
						
						// Display pagination
						wp_link_pages('before=<p>&after=</p>&next_or_number=number&pagelink= %');
					
						// Display edit post link to site admin
						edit_post_link(__('Edit This Post','advanced'),'<p>','</p>'); 
						?>
				</div><!-- .entry -->
				<?php endwhile; endif; ?>
			</div><!-- #main -->
			<div class="clear"></div>
	</div><!-- #Container -->

<?php get_footer(); ?>