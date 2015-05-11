<div class="sidebarinner">
	<div class="sidebar_left">
	
	<?php 
		/* Sidebar 300x250 ad top */
		if(file_exists(TEMPLATEPATH . '/ads/sidebar_300x250-top/'. current_catID() .'.php') && (is_single() || is_category())) {
			include_once(TEMPLATEPATH . '/ads/sidebar_300x250-top/'. current_catID() .'.php');
		}
		else {
			include_once(TEMPLATEPATH . '/ads/sidebar_300x250-top.php');
		}?>
		
		<?php gab_dynamic_sidebar('Innerpage-Sidebar1');?>
		
		<a class="twitter-timeline" href="https://twitter.com/search?q=IndiaSpend" data-widget-id="308948473678544896">Tweets about "IndiaSpend"</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

<!-- Twitter By MK -->
	<div class='spr_iframe'>

			<!--MK <script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
			<script>
			new TWTR.Widget({
			  version: 2,
			  type: 'search',
			  search: 'indiaspend',
			  interval: 30000,
			  title: '',
			  subject: 'IndiaSpend',
			  width: 300,			  
			  height: 300,
			  theme: {
				shell: {
				  background: '#eee',
				  color: '#333',
				  
				},
				tweets: {
				  background: '#fff',
				  color: '#151515',
				  links: '#225278'
				}
			  },
			  features: {
				scrollbar: true,
				loop: true,
				live: true,				
				behavior: 'all'		 
			  }
			}).render().start();
			</script> -->
			

	</div>
			<!-- Close Twitter by MK -->
		<!--Sidebar 300x250 ad bottom -->
		<?php if(file_exists(TEMPLATEPATH . '/ads/sidebar_300x250-bottom/'. current_catID() .'.php') && (is_single() || is_category())) {
			include_once(TEMPLATEPATH . '/ads/sidebar_300x250-bottom/'. current_catID() .'.php');
		}
		else {
			include_once(TEMPLATEPATH . '/ads/sidebar_300x250-bottom.php');
		}
		
		gab_dynamic_sidebar('Innerpage-Sidebar2');
	?>
	
<?php  echo do_shortcode('[emfw_mailchimp_forms title="" list="33140735d2" style="xrvs28dZ"]') ; ?>
	</div><!-- .sidebar_left -->
	<!--
	<div class="sidebar_right">
		<?php /* 
		if (intval(of_get_option('of_an_nr19')) > 0 ) {
			include_once(TEMPLATEPATH . '/sidebar-media.php'); 
		}
		
		gab_dynamic_sidebar('Innerpage-Sidebar3');
		
		/* Sidebar 120x600 ad */
		/* if(file_exists(TEMPLATEPATH . '/ads/sidebar_120x600/'. current_catID() .'.php') && (is_single() || is_category())) {
			include_once(TEMPLATEPATH . '/ads/sidebar_120x600/'. current_catID() .'.php');
		}
		else {
			include_once(TEMPLATEPATH . '/ads/sidebar_120x600.php');
		}
		
		gab_dynamic_sidebar('Innerpage-Sidebar4');
		*/ ?>
	</div><!-- .sidebar_right --> 
	<div class="clear"></div>
</div><!-- .sidebarinner -->