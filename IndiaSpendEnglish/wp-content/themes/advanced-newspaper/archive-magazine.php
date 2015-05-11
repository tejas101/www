<?php $count = 1; ?>

<div id="container">
		<div id="main" class="fullwidth">
			<?php if(!is_paged()) { ?>
									<?php 
									$count = 1;
									if (have_posts()) : while (have_posts()) : the_post();			
									$gab_thumb = get_post_meta($post->ID, 'thumbnail', 1);
									$gab_video = get_post_meta($post->ID, 'video', 1);
									$gab_flv = get_post_meta($post->ID, 'videoflv', 1);
									$ad_flv = get_post_meta($post->ID, 'adflv', 1);
									$gab_iframe = get_post_meta($post->ID, 'iframe', 1);
									 ?>		
								
									<?php if($count == 1) { ?>
									<div id="primary-left">
									<?php } ?>

										<?php if($count <= 3) { ?>	
											<div class="featuredpost<?php if($count == 3) { echo ' lastpost'; } ?>">
												<?php
												if($count == 1) {
													gab_media(array(
														'name' => 'an-mag1',
														'imgtag' => 1,
														'link' => 1,
														'enable_video' => 1,
														'video_id' => 'mag1',
														'catch_image' => of_get_option('of_catch_img', 0),
														'enable_thumb' => 1,
														'resize_type' => 'w',
														'media_width' => 500, 
														'media_height' => 300, 
														'thumb_align' => 'aligncenter',
														'enable_default' => 0
													));
												} 
												?>				
												<h2 class="posttitle">
													<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
												</h2>
												<?php
												if($count !== 1) {
													gab_media(array(
														'name' => 'an-mag2',
														'imgtag' => 1,
														'link' => 1,
														'enable_video' => 0,
														'catch_image' => of_get_option('of_catch_img', 0),
														'enable_thumb' => 1,
														'resize_type' => 'c',
														'media_width' => 120, 
														'media_height' => 90, 
														'thumb_align' => 'alignleft',
														'enable_default' => 0
													));	
												}
												?>
												<p><?php echo string_limit_words(get_the_excerpt(),48); ?>&hellip;</p>
												<?php gab_postmeta(); ?>
											</div>
										<?php } ?>
										
									<?php if($count == 4) { ?>
									</div><!-- archive_left -->
									
									<div id="primary-mid">
									<?php } ?>

									
										<?php if( $count > 3 ) { ?>						
											<div class="featuredpost<?php if($count == 8) { echo ' lastpost'; } ?>">

												<h2 class="posttitle">
													<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
												</h2>									
											
												<?php
													gab_media(array(
														'name' => 'an-generic',
														'enable_video' => 0,
														'imgtag' => 1,
														'link' => 1,
														'catch_image' => of_get_option('of_catch_img', 0),
														'enable_thumb' => 1,
														'resize_type' => 'c',
														'media_width' => 80, 
														'media_height' => 63, 
														'thumb_align' => 'alignleft',
														'enable_default' => 0
													));
												?>				
												
												<p><?php echo string_limit_words(get_the_excerpt(),18); ?>&hellip;</p>
												
												<?php gab_postmeta(); ?>
												
											</div><!-- .featuredpost -->
										<?php } ?>			

									<?php if($count == 8) { ?>
									</div>
									
									<div id="primary-right">
								
											<?php 
											gab_dynamic_sidebar('Archive-Magazine1');
											/* Sidebar 300x250 ad top */
											if(file_exists(TEMPLATEPATH . '/ads/magazine_120x600/'. current_catID() .'.php') && (is_single() || is_category())) {
												include_once(TEMPLATEPATH . '/ads/magazine_120x600/'. current_catID() .'.php');
											}
											else {
												include_once(TEMPLATEPATH . '/ads/magazine_120x600.php');
											}
											gab_dynamic_sidebar('Archive-Magazine2');
											?>
										<?php } ?>
										
									<?php $count++; endwhile; endif; ?>						
									
									</div>
									<div class="clear"></div>

									<div class="archive-border"></div>
									<?php
									// load pagination if needed
									if (($wp_query->max_num_pages > 1) && (function_exists('pagination'))) {
										pagination($additional_loop->max_num_pages);
									}
									wp_reset_query();
									?>	
			<?php } else {
									include (TEMPLATEPATH . '/archive-default.php'); 
			} ?>			
		</div><!-- #main -->
	
</div><!-- #Container -->