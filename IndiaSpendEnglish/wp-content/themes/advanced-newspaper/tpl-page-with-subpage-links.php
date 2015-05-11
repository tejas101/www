<?php
	/*
		Template Name: Page - Subpagelinks at top
	*/
?>

<?php get_header(); ?>

	<div id="container">

			<div id="main">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class('entry');?>>
					
					
						<h1 class="entry_title">
							<?php the_title(); ?>
						</h1>

							
						<?php
						if($post->post_parent)
						$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&echo=0"); else
						$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
						if ($children) { ?>
						<ul id="subpagelinks"><?php echo $children; ?></ul>
						<div class="clear"></div>
						<?php } ?>							
					
						
						<?php
						// Display edit post link to site admin
						edit_post_link(__('Edit This Post','advanced'),'<p>','</p>'); 				
						
						// If there is a video, display it
						gab_media(array(
							'name' => 'gab-fea',
							'enable_video' => 1,
							'video_id' => 'archive',
							'catch_image' => 0,
							'media_width' => 483,
							'media_height' => 300,
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
					</div>

				<?php endwhile; endif; ?>
			</div><!-- #main -->
			
			<div id="sidebar">
				<?php get_sidebar(); ?>
			</div><!-- #Sidebar -->		
			<div class="clear"></div>
	</div><!-- #Container -->

<?php get_footer(); ?>