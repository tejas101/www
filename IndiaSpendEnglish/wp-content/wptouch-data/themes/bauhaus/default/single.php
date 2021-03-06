<?php get_header(); ?>

	<div id="content">
		<?php while ( wptouch_have_posts() ) { ?>

			<?php wptouch_the_post(); ?>

			<div class="<?php wptouch_post_classes(); ?>">
				<div class="post-page-head-area bauhaus">
					<span class="post-date-comments">
						<?php if ( bauhaus_should_show_date() ) { ?>
							<?php wptouch_the_time(); ?>
						<?php } ?>
						<?php if ( bauhaus_should_show_comment_bubbles() ) { ?>
							<?php if ( bauhaus_should_show_date() && ( comments_open() || wptouch_have_comments() ) ) echo '&harr;'; ?>
							<?php if ( comments_open() || wptouch_have_comments() ) comments_number( __( 'no comments', 'wptouch-pro' ), __( '1 comment', 'wptouch-pro' ), __( '% comments', 'wptouch-pro' ) ); ?>
						<?php } ?>
					</span>
					<h2 class="post-title heading-font"><?php wptouch_the_title(); ?></h2>
					<?php if ( bauhaus_should_show_author() ) { ?>
						<span class="post-author"><?php the_author(); ?></span>
					<?php } ?>
				</div>

				<div class="post-page-content">

				 <div class="sliverbag" style="height: 30px;">	                                            
<?php echo do_shortcode('[ultimatesocial networks="facebook,twitter,google,linkedin,mail,total" count="flase"]'); ?>


<?php $counter=bac_PostViews_mobile(get_the_ID());


echo'<div class="calsscount" style="width: 110px;padding-top: 17px;margin-top: -33px;border-left: 1px solid black;height: 13px;"> <div class="totalview" style="font-weight: bold;margin-top: -3px;font-size: 11px;padding-left: 5px;margin-left: 2%;font-family: \'Lato\', sans-serif;">Views &nbsp;</div><div class="views-count" style="background: none;margin-top: -34%;margin-left: -1%;font-weight: bold;font-size: 11px;padding-left: 9px;font-family: \'Lato\', sans-serif;">'.bac_PostViews_mobile(get_the_ID()).'</div></div>';
?>  



			</div>





					<?php if ( bauhaus_should_show_thumbnail() && wptouch_has_post_thumbnail() ) { ?>
						<div class="post-page-thumbnail">
							<?php the_post_thumbnail('large', array( 'class' => 'post-thumbnail wp-post-image' ) ); ?>
						</div>
					<?php } ?>
					<?php wptouch_the_content(); ?>
					<?php if ( bauhaus_should_show_taxonomy() ) { ?>
						<?php if ( wptouch_has_categories() || wptouch_has_tags() ) { ?>
							<div class="cat-tags">
								<?php if ( wptouch_has_categories() ) { ?>
									<?php _e( 'Categories', 'wptouch-pro' ); ?>: <?php wptouch_the_categories(); ?><br />
								<?php } ?>
								<?php if ( wptouch_has_tags() ) { ?>
									<?php _e( 'Tags', 'wptouch-pro' ); ?>: <?php wptouch_the_tags(); ?>
								<?php } ?>
							</div>
						<?php } ?>

						<?php if ( wptouch_has_tags() ) { ?>
						<?php } ?>
					<?php } ?>
				</div>
			</div>

		<?php } ?>
	</div> <!-- content -->

	<?php get_template_part( 'related-posts' ); ?>

	<?php get_template_part( 'nav-bar' ); ?>
	<?php if ( comments_open() || wptouch_have_comments() ) { ?>
		<div id="comments">
			<?php comments_template(); ?>
		</div>
	<?php } ?>

<?php get_footer(); ?>