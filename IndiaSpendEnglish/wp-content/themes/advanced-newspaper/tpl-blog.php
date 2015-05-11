<?php
	/*
		Template Name: Blog
	*/
?>
<?php get_header(); ?>

	<div id="container">

			<div id="main">
					<?php 
						if ( get_query_var( 'paged') ) $paged = get_query_var( 'paged' ); elseif ( get_query_var( 'page') ) $paged = get_query_var( 'page' ); else $paged = 1;
						query_posts( "post_type=post&showposts=6&paged=$paged" ); 
						
						include (TEMPLATEPATH . '/loop-default.php'); 
						
						// load pagination if needed
						if (($wp_query->max_num_pages > 1) && (function_exists('pagination'))) {
							pagination($additional_loop->max_num_pages);
						}
						wp_reset_query();
					?>
			</div><!-- #main -->
			
			<div id="sidebar">
				<?php get_sidebar(); ?>
			</div><!-- #Sidebar -->		
			<div class="clear"></div>
	</div><!-- #Container -->

<?php get_footer(); ?>