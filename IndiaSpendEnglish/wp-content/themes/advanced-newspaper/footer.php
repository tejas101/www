</div><!-- wrapper -->

<div id="footer-wrapper">
<div id="footer">

	<ul class="footercats">	
	<?php
		if(of_get_option('of_nav4', 0) == 1) { 
			wp_nav_menu( array('theme_location' => 'footer-nav1', 'container' => false, 'items_wrap' => '%3$s'));
		} else { ?>
		<?php wp_list_categories('depth=1&orderby='. of_get_option('of_order_cats') .'&order='. of_get_option('of_sort_cats') .'&title_li=&exclude='. of_get_option('of_ex_cats')); ?>
	<?php } ?>	
	</ul>	
	<div class="clear"></div>

	<!-- <ul class="footerpages">	
	<?php /*
		if(of_get_option('of_nav5', 0) == 1) { 
			wp_nav_menu( array('theme_location' => 'footer-nav2', 'container' => false, 'items_wrap' => '%3$s'));
		} else { ?>
		<?php wp_list_pages('depth=1&sort_column=menu_order&title_li=&exclude='. of_get_option('of_ex_pages')); ?>
		<li><a rel="nofollow" href="<?php if ( of_get_option('of_rssaddr') <> '' ) { echo of_get_option('of_rssaddr'); } else { echo bloginfo('rss2_url'); } ?>"><?php _e('RSS','advanced'); ?></a></li> 
	<?php } */?>	
	</ul> -->
	<div class="clear"></div>
			
	<div id="footer_meta">
	
<div class="foot_col1">
<strong>IndiaSpend <hr /></strong>
<p>
<a href="<?php echo get_site_url(); ?>" >Home</a><br>
<a href="<?php echo get_site_url(); ?>/about" >About Us</a><br>
<a href="<?php echo get_site_url(); ?>/category/viznomics">Viznomics</a><br>
<a href="<?php echo get_site_url(); ?>/category/resources">Resources</a>   <br>     
<a href="<?php echo get_site_url(); ?>/contactus">Contact</a><br>
</p>
</div>

<div class="foot_col1">
<strong>Sectors <hr /></strong>
<p>
<a href="<?php echo get_site_url(); ?>/category/sectors/economy-policy">Economy & Policy</a><br>
<a href="<?php echo get_site_url(); ?>/category/sectors/education">Education</a><br>
<a href="<?php echo get_site_url(); ?>/category/sectors/health">Health</a><br>
<a href="<?php echo get_site_url(); ?>/category/sectors/infrastructure">Infrastructure</a><br>
<a href="<?php echo get_site_url(); ?>/category/sectors/agriculture">Agriculture</a><br>
<a href="<?php echo get_site_url(); ?>/category/sectors/defence">Defence</a>

</p>
</div>

<div class="foot_col1">
<strong>Investigations<hr /></strong>
<p>
 <a href="<?php echo get_site_url(); ?>/category/investigations/central">Centre</a><br>
<a href="<?php echo get_site_url(); ?>/category/investigations/state">State</a>
</p>

</div>

<div class="foot_col1">
<strong>States<hr /></strong>
<p>
<a href="<?php echo get_site_url(); ?>/category/states/new-delhi">New Delhi</a><br>
<a href="<?php echo get_site_url(); ?>/category/states/west-bengal">West Bengal</a><br>
<a href="<?php echo get_site_url(); ?>/category/states/tamil-nadu">Tamil Nadu</a><br>
<a href="<?php echo get_site_url(); ?>/category/states/maharashtra">Maharashtra</a>
</p>
</div><!--
<div class="foot_col1">
<table class="foot_col2_inside">
<tr><td colspan="2">
</td></tr>
<tr><td colspan="2">Subscribe to our premium bi-weekly newsletter to receive special reports and updates.</td></tr>
<tr>
<td><?php // mailchimpSF_signup_form(); ?></td>
</tr>
</table>
</div>   --> 
<div class="foot_col2">
<a href="http://gijn.org/"> <img src="<?php echo get_site_url(); ?>/wp-content/uploads/gijn-footer.png" /></a>
</div>
<div class="copyright">
		<p class="footer-left-text">	
			<?php /* Replace default text if option is set */
			if( of_get_option('of_an_footer1', 0) == 1){
				echo of_get_option('of_an_footer1_text');
			} else { 
			?>
				&copy; <?php echo the_date('Y'); ?>, <a href="#top" title="<?php bloginfo('name'); ?>" rel="home"><strong>&uarr;</strong> <?php bloginfo('name'); ?></a>
			<?php } ?>
		</p>
		
		<p class="footer-right-text">
			<?php /* Replace default text if option is set */
			if( of_get_option('of_an_footer2', 0) == 1){ 
				echo of_get_option('of_an_footer2_text');
			} else {
				wp_loginout(); 
				if ( !is_user_logged_in() ) { 
					echo '-'; ?>
					<a href="<?php bloginfo('url'); ?>/wp-admin/edit.php">Posts</a> - 
					<a href="<?php bloginfo('url'); ?>/wp-admin/post-new.php">Add New</a>
				<?php } ?> - 
			<?php } ?>
			<!--<a href="http://wordpress.org/" title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'advanced'); ?>" rel="generator"><?php _e('Powered by WordPress', 'advanced'); ?></a> - 
			
			Designed by <a href="http://www.gabfirethemes.com/" title="Premium WordPress Themes">Gabfire Themes</a>-->
			<?php wp_footer(); ?>
		</p>
		</div>
		<div class="clear"></div>
	</div>
<!-- Javascript tag  -->

</html>



