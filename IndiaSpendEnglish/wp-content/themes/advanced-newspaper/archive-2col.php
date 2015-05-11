<div id="container">

			<div id="main">			

					<?php 
					include (TEMPLATEPATH . '/loop-2col.php'); 
					
					// load pagination
					if (($wp_query->max_num_pages > 1) && (function_exists('pagination'))) {
						pagination($additional_loop->max_num_pages);
					}
					?>
			</div><!-- #main -->

			<div id="sidebar">
				<?php get_sidebar(); ?>
			</div><!-- #Sidebar -->
			<div class="clear"></div>			

</div><!-- #Container -->