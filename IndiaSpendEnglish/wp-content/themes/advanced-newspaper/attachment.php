<?php get_header(); ?>

	<div id="container">
			<div id="main">
				<span id="bcrum"><?php gab_breadcrumb(); ?></span>

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class('entry');?>>
						<h1 class="entry_title">
							<?php the_title(); ?>
						</h1>

						<?php
						if (of_get_option('of_wpmumode')==0) {
							if (wp_attachment_is_image($post->id)) {
								$att_image = wp_get_attachment_image_src( $post->id, "full");
							}
							if(is_multisite()) { ?>
								<img src="<?php bloginfo('template_url'); ?>/timthumb.php?src=<?php echo redirect_wpmu($att_image[0]); ?>&amp;q=90&amp;w=479&amp;zc=1" class="attachment-full" alt="" />
							<?php } else { ?>
								<img src="<?php bloginfo('template_url'); ?>/timthumb.php?src=<?php echo $att_image[0]; ?>&amp;q=90&amp;w=479&amp;zc=1" class="attachment-full" alt="" />
							<?php }
						} else {
							if (wp_attachment_is_image($post->id)) {
								$att_image = wp_get_attachment_image_src( $post->id, "an-innerslide");
							} ?>
							<img src="<?php echo $att_image[0]; ?>" alt="<?php the_title(); ?>" class="attachment-full" />
						<?php } ?>						
						
						<div class="attachment-nav">
							<?php previous_image_link( false, __('&laquo; Previous Image','advanced')); ?> | <a href="<?php echo get_permalink($post->post_parent); ?>"><?php _e('Back to Post','advanced'); ?></a> | <?php next_image_link( false, __('Next Image &raquo;','advanced')); ?>
						</div>
						
						<?php
							$args = array(
							'post_type' => 'attachment',
							'numberposts' => -1,
							'order' => 'ASC',
							'post_parent' => $post->post_parent);
							$attachments = get_posts($args);

							if ($attachments) 
							{
							  foreach ($attachments as $attachment) 
							  {
								echo '<div class="gallery-icon"><a href="'.get_attachment_link($attachment->ID, false).'">'.wp_get_attachment_image($attachment->ID, 'thumbnail').'</a></div>';
							  }
							}
						?>
						
						<div class="clear"></div>
					</div><!-- .entry -->

				<?php endwhile; else : endif; ?>
				
			</div><!-- #main -->
			
			<div id="sidebar">
				<?php get_sidebar(); ?>
			</div><!-- #Sidebar -->
			<div class="clear"></div>
	</div><!-- #Container -->

<?php get_footer(); ?>