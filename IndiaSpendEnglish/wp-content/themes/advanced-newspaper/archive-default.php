<div id="container">

			<div id="main">	
	
				<span id="bcrum"><?php gab_breadcrumb(); ?></span>
			<div id="sidebar">
 
				<?php get_sidebar(); ?>
			</div><!-- #Sidebar -->	
					<?php 
					include (TEMPLATEPATH . '/loop-default.php'); 
					
					// load pagination
					if (($wp_query->max_num_pages > 1) && (function_exists('pagination'))) {
						pagination($additional_loop->max_num_pages);
					}
					?>
			</div><!-- #main -->
			
			
			<div class="clear"></div>
</div><!-- #Container -->