<?php
	/*
		Template Name: Videos (for Iframe Vids only)
	*/
?>

<?php get_header(); ?>

	<div id="container">

			<div id="main" class="fullwidth">
			
					<span id="bcrum"><?php gab_breadcrumb(); ?></span>			
					
						<?php
						if ( get_query_var( 'paged') ) $paged = get_query_var( 'paged' ); elseif ( get_query_var( 'page') ) $paged = get_query_var( 'page' ); else $paged = 1;
						query_posts( "post_type=any&paged=$paged&showposts=8&meta_key=iframe" ); 

						$count = 1;
						if (have_posts()) : while (have_posts()) : the_post();
						$gab_iframe = get_post_meta($post->ID, 'iframe', true);	
						?>
						<div class="post" id="gab_gallery">

								<div id="post-<?php the_ID(); ?>" class="media-wrapper <?php if($count % 4 == 0) { echo ' last'; } ?>">
									<div class="entry">
										
										<?php 
											gab_media(array(
												'name' => 'an-media',
												'enable_video' => 1,
												'enable_thumb' => 0,
												'media_width' => 208, 
												'media_height' => 180, 
												'thumb_align' => 'null', 
												'enable_default' => 0
											));
										?>
										
										<h2 class="entry_title s_title">
											<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
										</h2>
										<p class="small-text">
											<?php _e('Posted by','advanced'); ?>  <?php the_author_posts_link(); ?> <?php _e('on','advanced'); ?> <?php echo get_the_date(''); ?><br />
											<?php _e('Filed under','advanced'); ?>
											<?php the_category(', '); echo get_the_term_list( $post->ID, 'gallery-cat', ': ', ' ', '' ); ?> 
											<?php if ($gab_iframe) { echo '<a class="iframe" href="'.$gab_iframe.'"><img class="right" src="'. get_bloginfo(template_url) .'/framework/images/expand.png" alt="" /></a>'; } ?>
										</p>					
									</div><!-- .entry -->
									<span class="entry-shadow"></span>
								</div><!-- .media-wrapper -->
									
								<?php if($count % 4 == 0) { echo '<div class="clear"></div>'; } ?>
							<?php $count++; endwhile; endif; ?>
							<div class="clear"></div>	
						</div>
						<div class="clear"></div>	


					
					<?php
					// load pagination
					if (($wp_query->max_num_pages > 1) && (function_exists('pagination'))) {
						pagination($additional_loop->max_num_pages);
					}
					wp_reset_query();
					?>
			</div><!-- #main -->
			<div class="clear"></div>
	</div><!-- #Container -->

<?php get_footer(); ?>