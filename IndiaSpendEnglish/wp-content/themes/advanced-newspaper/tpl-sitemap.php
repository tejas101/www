<?php
	/*
		Template Name: Sitemap
	*/
?>
<?php get_header(); ?>

	<div id="container">

			<div id="main">
				<div id="post-<?php the_ID(); ?>" <?php post_class('entry');?>>
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<h1 class="entry_title">
							<?php the_title(); ?>
						</h1>
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
							'media_width' => 483,
							'media_height' => 300,
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						
						// Display content
						the_content();
						
						// make sure any advanceded content gets cleared
						echo '<div class="clear"></div>';
						
						// Display pagination
						wp_link_pages('before=<p>&after=</p>&next_or_number=number&pagelink= %');
					
						endwhile; endif; 
						?>
					
						<div style="width:40%" class="left">  
		            	<h3><?php _e( 'Pages', 'advanced' ) ?></h3>
		            	<ul class="sitemap">
		           	    	<?php wp_list_pages( 'depth=0&sort_column=menu_order&title_li=' ); ?>		
		            	</ul>
		            	</div>				
		    
						<div style="width:30%" class="left">												  	    
			            <h3><?php _e( 'Categories', 'advanced' ) ?></h3>
			            <ul class="sitemap">
		    	            <?php wp_list_categories( 'title_li=&hierarchical=0&show_count=1') ?>	
		        	    </ul>
		        	    </div>
						
						<div style="width:30%" class="left">												  	    
			            <h3><?php _e( 'Media Gallery', 'advanced' ) ?></h3>
						<ul class="sitemap">
							<?php wp_list_categories('taxonomy=gallery-cat&hide_empty=0&title_li='); ?>
						</ul>	
		        	    </div>					
		        	    <div class="clear"></div>
				        
				        <h3><?php _e( 'Posts per category', 'advanced' ); ?></h3>
						<?php
					   // This is where loop for archives list starts
						$cats = get_categories();
						foreach ($cats as $cat) {
						query_posts('cat='.$cat->cat_ID);
						?>
							<div class="widget">
							<h4><?php echo $cat->cat_name; ?></h4>
							<ul>	
								<?php while (have_posts()) : the_post(); ?>
								<li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - (<?php echo $post->comment_count ?>)</li>
								<?php endwhile;  ?>
							</ul>
							</div>
						<?php } ?>
				
				        <h3><?php _e( 'Media Gallery Entries', 'advanced' ); ?></h3>
						<?php
					   // This is where loop for archives list starts
						query_posts('post_type=gab_gallery&showposts=999'); 
						?>
							<div class="widget">
							<ul>	
								<?php while (have_posts()) : the_post(); ?>
								<li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - (<?php echo $post->comment_count ?>)</li>
								<?php endwhile;  ?>
							</ul>
							</div>
						<?php
						edit_post_link(__('Edit This Post','advanced'),'<p>','</p>'); 
					?>
				</div><!-- .entry -->
			</div><!-- #main -->
			
			<div id="sidebar">
				<?php get_sidebar(); ?>
			</div><!-- #Sidebar -->					
			<div class="clear"></div>
	</div><!-- #Container -->

<?php get_footer(); ?>