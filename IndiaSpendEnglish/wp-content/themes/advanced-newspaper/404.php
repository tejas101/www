<?php get_header(); ?>

	<div id="container">

			<div id="main">
			
					<div id="post-<?php the_ID(); ?>" class="entry">
					
						<h1 class="entry_title">
							<?php _e('Page not found!','advanced'); ?>
						</h1>

						<p><?php _e('Sorry the page you were looking is not here.','advanced'); ?></p>
						
					</div>


			</div><!-- #main -->
			
			<div id="sidebar">
				<?php get_sidebar(); ?>
			</div><!-- #Sidebar -->
			
			<div class="clear"></div>
	</div><!-- #Container -->

<?php get_footer(); ?>