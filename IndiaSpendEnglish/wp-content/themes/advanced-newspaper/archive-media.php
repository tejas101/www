<div id="container">
		<div id="main" class="fullwidth">
			
			<span id="bcrum"><?php gab_breadcrumb(); ?></span>
			
				<?php 
				include (TEMPLATEPATH . '/loop-media.php'); 
				
				// load pagination
				if (($wp_query->max_num_pages > 1) && (function_exists('pagination'))) {
					pagination($additional_loop->max_num_pages);
				}
				?>
		</div><!-- #main -->
</div><!-- #Container -->