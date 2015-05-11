<?php get_header(); ?>
<script type="text/javascript" src="/wp-content/themes/advanced-newspaper/single.js"></script>
	<div id="container">
	
			<div id="main">
			<div class="innerarticle">
	
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class('entry');?>>
					<div class="authordate">
							<span id="bcrum"><?php gab_breadcrumb(); ?></span>
						<h1 class="entry_title">
							<?php the_title(); ?>
						</h1>
				<?php $by = get_post_meta($post->ID, 'Author Name', true); 

				if(!empty($by)) { ?>

				<span> <?php echo $by; 
				echo ",";
				}?></span>
			
<span class="entry-date"><?php echo get_the_date(); ?></span>
<?php $twitterfollow = get_post_meta($post->ID, 'twitter follow button', true); 

					if(!empty($twitterfollow)) { 

					echo '<span style=" float: right; "> '.$twitterfollow.' </span>'; }
?>
</div>
<div id="sidebar">
				<?php get_sidebar(); ?>
			</div><!-- #Sidebar -->
			
						<?php
						// Theme innerpage slider
						gab_innerslider();
						
						// If there is a video, display it
						gab_media(array(
							'name' => 'gab-fea',
							'enable_video' => 1,
							'video_id' => 'archive',
							'catch_image' => 0,
							'enable_thumb' => 0,
							'media_width' => 615,
							'media_height' => 300,							
							//'thumb_align' => 'aligncenter',
				
							'enable_default' => 0
						));?>
							
			
						<!-- Share div start -->
<!-- code added frm here fr sliver strip -->

<div id="share" >
<?php echo do_shortcode('[ultimatesocial networks="facebook,twitter,google,linkedin,print,mail,total" ]');   ?>
<?php  echo'<div class="calsscount" style="width: 110px;margin-left: 567px;padding-top: 16px;border-left: 1px solid;height: 14px;margin-top: 6px;"> <div class="totalview" style="width: 50px; text-align: center;margin-top: -2px;font-style: inherit;font-size: 10px;"> Views</div><div class="views-count" style="width: 50px; text-align: center;background: none;margin-top: -41px;font-size: 18px;">'.bac_PostViews(get_the_ID()).'</div></div>';?> 
</div>
    <!-- code ended here fr sliver strip  -->

							
			<div id="contentishere">  		
			<?php		// Display content
						the_content(); ?>
			</div>			
						
			<?php	
					// make sure any advanceded content gets cleared
						echo '<div class="space"></div>';
						//echo '<div class="clear"></div>';
							
						$source_post = get_post_meta($post->ID, 'credit', true);
						if($source_post !== '') {
							echo '<div class="postcredit"><strong>'; _e('Source', 'source'); echo '</strong>: ' .  $source_post . '</div>';
						}								
						
						// Display pagination
						wp_link_pages('before=<p>&after=</p>&next_or_number=number&pagelink= %');
						
						// Post Widget
						gab_dynamic_sidebar('PostWidget');
					
						// Display edit post link to site admin
						edit_post_link(__('Edit This Post','advanced'),'<p>','</p>'); 
						?>
						
						<?php if(of_get_option('of_entry_meta', 0) == 1) { ?>
							<div class="singlepostmeta">
								
							<!-- MK -->
								<?php /* echo get_avatar( get_the_author_email(), '39' ); ?>
								<?php _e('Posted by','advanced'); ?>  <?php the_author_posts_link(); ?> 
								<?php /* This is commented, because it requires a little adjusting sometimes.
									You'll need to download this plugin, and follow the instructions:
									http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
									/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
								<?php /* _e('on','advanced'); ?> <?php echo get_the_date(); ?>. <?php _e('Filed under','advanced'); ?> <?php the_category(','); echo get_the_term_list( $post->ID, 'gallery-cat', ': ', ' ', '' ); ?>.
								<?php _e('You can follow any responses to this entry through the','advanced'); ?> <?php comments_rss_link('RSS 2.0'); ?>.
								<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
									// Both Comments and Pings are open ?>
								<?php _e('You can leave a response or trackback to this entry','advanced'); ?>
								<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
									// Only Pings are Open ?>
								<?php _e('Responses are currently closed, but you can trackback from your own site.','advanced'); ?>
								<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
									// Comments are open, Pings are not ?>
								<?php _e('You can skip to the end and leave a response. Pinging is currently not allowed.','advanced'); ?>
								<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
									// Neither Comments, nor Pings are open ?>
								<?php _e('Both comments and pings are currently closed.','advanced'); ?>
								<?php } */?>	
						
						<?php  // echo get_avatar( get_the_author_email(), '39' ); ?>
								<?php _e('Posted by','advanced'); ?>  <?php the_author_posts_link(); ?> 
								<?php /* This is commented, because it requires a little adjusting sometimes.
									You'll need to download this plugin, and follow the instructions:
									http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
									/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
								<?php _e('on','advanced'); ?> <?php echo get_the_date(); ?>
									
								<?php// } ?>
						
								<div class="clear"></div>
								
							</div>	
						<?php } ?>		
						
				<?php comments_template(); ?>		
					</div><!-- .entry -->

				<?php endwhile; else : endif; ?>
				
			
				
			</div><!-- #main -->
			
				
			<div class="clear"></div>
			</div>
	</div><!-- #Container -->

<?php get_footer(); ?>