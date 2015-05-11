<?php
	/*
		Template Name: Media
	*/
?>

<?php get_header(); ?>

	<div id="container">

			<div id="main" class="fullwidth">
			
					<span id="bcrum"><?php gab_breadcrumb(); ?></span>			
					
					<?php 
						if ( get_query_var( 'paged') ) $paged = get_query_var( 'paged' ); elseif ( get_query_var( 'page') ) $paged = get_query_var( 'page' ); else $paged = 1;
						query_posts( "post_type=gab_gallery&paged=$paged&showposts=8" ); 
						
						include (TEMPLATEPATH . '/loop-media.php'); 
		
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