<?php get_header(); ?>

	<div id="container">

			<div id="main">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class('entry');?>>
		<div class="authordate">
									<span id="bcrum"><?php gab_breadcrumb(); ?></span>
						<h1 class="entry_title">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
						</h1>
						<!--div id="InRead"> </div-->
						</div>
						
							<div id="sidebar">
				<?php get_sidebar(); ?>
			</div><!-- #Sidebar -->
<!-- Share div start -->
<div id="share">
<!-- Share This div start -->
<?php echo do_shortcode('[ultimatesocial networks="facebook,twitter,google,linkedin,print,mail,total" ]'); ?>

</div>
<!-- Share div end -->

						<?php
						// Display edit post link to site admin
						edit_post_link(__('Edit This Post','advanced'),'<p>','</p>'); 				
						
						// If there is a video, display it
						gab_media(array(
							'name' => 'gab-fea',
							'enable_video' => 1,
							'video_id' => 'archive',
							'catch_image' => 0,
							'enable_thumb' => 0,
							'media_width' => 611,
							'media_height' => 350,
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						
						// Display content
						the_content();
						
						// make sure any blognewsed content gets cleared
						echo '<div class="clear"></div>';
						
						// Display pagination
						wp_link_pages('before=<p>&after=</p>&next_or_number=number&pagelink= %');
					
						// Display edit post link to site admin
						edit_post_link(__('Edit This Post','advanced'),'<p>','</p>'); 
						?>
					</div>

				<?php endwhile; else : endif; ?>
				
				<?php 
				/* comments template is not included intentionally as pages are generally static. */
				/* Uncomment the line below in order to enable comments for pages */ 
				/* comments_template(); */ ?>
			</div><!-- #main -->
			
		
			<div class="clear"></div>
	</div><!-- #Container -->

<?php get_footer(); ?>