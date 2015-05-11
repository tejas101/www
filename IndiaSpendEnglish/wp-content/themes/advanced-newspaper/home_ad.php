<?php get_header(); ?>

<div id="primarycontent">
		<div id="primary-left">
							
		<div id="featured-slider">
			<div class="fea-slides">
				<?php 
				$count = 1;
				if ( of_get_option('of_an_fea_recent', 0) == 1 ) {
					$args = array(
					   'posts_per_page' => of_get_option('of_an_nrfea' , 5)
					);
				} else {
					if ( of_get_option('of_an_fea_tag') <> '' ) {
						$args = array(
						  'post_type' => 'any',
						  'posts_per_page' => of_get_option('of_an_nrfea' , 5),
						  'tag' => of_get_option('of_an_fea_tag')
						);
					} elseif ( of_get_option('of_an_fea_cf', 0) == 1 ) {
						$args = array(
						  'post_type' => 'any',
						  'posts_per_page' => of_get_option('of_an_nrfea' , 5),
						  'meta_key' => 'featured', 
						  'meta_value' => 'true'
						);
					} else {
						$args = array(
						  'posts_per_page' => of_get_option('of_an_nrfea' , 5), 
						  'cat' => of_get_option('of_an_fea_cat')
						);				
					}
				}
				$gab_query = new WP_Query();$gab_query->query($args); 
				while ($gab_query->have_posts()) : $gab_query->the_post();  
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }
				?>
				
				<div class="slide_item">
				
					<div class="featured-media">
						<?php 
						gab_media(array(
							'name' => 'an-featured',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 1, 
							'catch_image' => of_get_option('of_catch_img', 0),
							'enable_thumb' => 1, 
							'resize_type' => 'c', /* c to crop, h to resize only height, w to resize only width */
							'media_width' => 960, 
							'media_height' => 430, 
							'thumb_align' => 'media', 
							'enable_default' => 1,
							'default_name' => 'featured.jpg'
						)); 										
						?>

						<?php if (($gab_flv == '') and ($gab_video == '') and ($gab_iframe == '') ) { ?>	
                               
                               		<div class="postteaser">
							<h2 class="posttitle">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ),                                the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
							</h2>
							<!-- <p><?php //echo string_limit_words(get_the_excerpt(),10); ?></p>-->
						</div>
                               
                               
                               
                               
						<?php } ?>
					</div>
				</div><!-- .slide-item -->

				<?php $count++; endwhile; wp_reset_query(); ?>
			</div><!-- .fea-slides -->
			
			
			
		</div><!-- #featured-slider -->
			
			<div class="section_shadow"></div>
						
				<?php gab_dynamic_sidebar('PrimaryLeft1');?>
				
				<?php if (intval(of_get_option('of_an_nr1', 2)) > 0 ) { ?>
				
					<!-- below slider -->
					<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat1'));?>"><?php echo get_cat_name(of_get_option('of_an_cat1')); ?></a></span>
					
					<?php
					$count = 1;
					$args = array(
					 'post__not_in'=> $do_not_duplicate,
					  'posts_per_page' => of_get_option('of_an_nr1', 2),
					  'cat' => of_get_option('of_an_cat1', 1)
					);
					$gab_query = new WP_Query();$gab_query->query($args); 
					while ($gab_query->have_posts()) : $gab_query->the_post();
					if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
					?>
					
						<div class="featuredpost<?php if($count == of_get_option('of_an_nr1', 2)) { echo ' lastpost'; } ?>">

							<?php
								gab_media(array(
									'name' => 'an-belowfea',
									'imgtag' => 1,
									'link' => 1,
									'enable_video' => 0,
									'catch_image' => of_get_option('of_catch_img', 0),
									'enable_thumb' => 1,
									'resize_type' => 'c',
									'media_width' => 110, 
									'media_height' => 77, 
									'thumb_align' => 'alignleft',
									'enable_default' => 0
								));
							?>

							<h2 class="posttitle">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
							</h2>							
							
						<p><?php  
						$text = get_post_meta($post->ID,'Home Text' , true);
						if (!empty($text)){
						echo $text;
						} 
						else {
							$text= string_limit_words(get_the_excerpt(), 15); 
							$text = string_limit_words(get_the_excerpt(), 50); 					
							$pos = strpos($text, '.'); 
							echo substr($text, 0, $pos); 
						} ?>&hellip;</p>	
							
							<?php gab_postmeta(); ?>

							
						</div><!-- .featuredpost -->
					<?php $count++; endwhile; wp_reset_query(); ?>
				
				<?php } ?>
				
				<?php gab_dynamic_sidebar('PrimaryLeft2');?>
				
		</div><!-- #primary-left -->
		
		<div id="primary-mid">
		<!-- Yahoo AD MK 
	<script id="mNCC" language="javascript">  medianet_width='300';  medianet_height= '250';  medianet_crid='621660518';  </script>  
		<script id="mNSC" src="http://contextual.media.net/nmedianet.js?cid=8CU5Z545Q" language="javascript"></script> 
						<!-- Yahoo AD MK -->
			<?php gab_dynamic_sidebar('PrimaryMid1');?>
			
			<?php if (intval(of_get_option('of_an_nr2', 4)) > 0 ) { ?>
			
				<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat2'));?>"><?php echo get_cat_name(of_get_option('of_an_cat2')); ?></a></span>
				
				<?php
				$count = 1;
				$args = array(
				 'post__not_in'=> $do_not_duplicate,
				  'posts_per_page' => of_get_option('of_an_nr2', 4),
				  'cat' => of_get_option('of_an_cat2')
				);
				$gab_query = new WP_Query();$gab_query->query($args); 
				while ($gab_query->have_posts()) : $gab_query->the_post();
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
				?>
				
					<div class="featuredpost<?php if($count == of_get_option('of_an_nr2', 4)) { echo ' lastpost'; } ?>">

						<h2 class="posttitle">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
						</h2>									
					
						<?php
							gab_media(array(
								'name' => 'an-generic',
								'imgtag' => 1,
								'link' => 1,
								'enable_video' => 0,
								'catch_image' => of_get_option('of_catch_img', 0),
								'enable_thumb' => 1,
								'resize_type' => 'c',
								'media_width' => 80, 
								'media_height' => 63, 
								'thumb_align' => 'alignleft',
								'enable_default' => 0
							));
						?>				
						<p><?php  
						$text = get_post_meta($post->ID, 'Home Text', true);
						if (!empty($text)){
						echo $text;
						} 
						else {
							$text= string_limit_words(get_the_excerpt(), 15); 
							$text = string_limit_words(get_the_excerpt(), 50); 					
							$pos = strpos($text, '.'); 
							echo substr($text, 0, $pos); 
						} ?>&hellip;</p>						
					 			
						<?php gab_postmeta(); ?>
						
					</div><!-- .featuredpost -->
				<?php $count++; endwhile; wp_reset_query(); ?>
				
			<?php } ?>
			
			<?php gab_dynamic_sidebar('PrimaryMid2');?>
            
            <a  href="http://www.indiaspend.com/category/mumbai-special-the-revival-agenda"> <p id="read"> Read More on Mumbai Special</p> </a>
			
		</div><!-- #primary-mid -->
		
		<div id="primary-right">
			<?php 
			gab_dynamic_sidebar('PrimaryRight1');
				 include_once(TEMPLATEPATH . '/ads/mainpage_120x600.php');
			gab_dynamic_sidebar('PrimaryRight2');
			?>
		</div><!-- primary-right -->
		<div class="clear"></div>
</div><!-- #primary-content -->

<div id="secondarycontent">
	<div id="secondary-left">
		<?php gab_dynamic_sidebar('SecondaryLeft1');?>
		<?php if (intval(of_get_option('of_an_nr3', 9)) > 0 ) { ?>
			<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat3'));?>"><?php if ( of_get_option('of_an_name3') <> '' ) { echo of_get_option('of_an_name3'); } else { echo get_cat_name(of_get_option('of_an_cat3')); } ?></a></span>
			<div class="widget">
				<ul>	
					<?php
					$count = 1;
					$args = array(
					 'post__not_in'=> $do_not_duplicate,
					  'posts_per_page' => of_get_option('of_an_nr3', 9),
					  'cat' => of_get_option('of_an_cat3')
					);
					$gab_query = new WP_Query();$gab_query->query($args); 
					while ($gab_query->have_posts()) : $gab_query->the_post();  if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }
					?>	
						<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a></li>
					<?php $count++; endwhile; wp_reset_query(); ?>
				</ul>
			</div><!-- .widget -->
		<?php } ?>
		<?php gab_dynamic_sidebar('SecondaryLeft2');?>
	</div><!-- #secondary-left -->
	
	<div id="secondary-mid">
		<?php gab_dynamic_sidebar('SecondaryMid1');?>
		
		<?php if (intval(of_get_option('of_an_nr4', 4)) > 0 ) { ?>
			
			<!-- below slider -->
			<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat4'));?>"><?php echo get_cat_name(of_get_option('of_an_cat4')); ?></a></span>
			
			<?php
			$count = 1;
			$args = array(
			 'post__not_in'=> $do_not_duplicate,
			  'posts_per_page' => of_get_option('of_an_nr4', 4),
			  'cat' => of_get_option('of_an_cat4', 1)
			);
			$gab_query = new WP_Query();$gab_query->query($args); 
			while ($gab_query->have_posts()) : $gab_query->the_post();
			if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
			?>
			
				<div class="featuredpost<?php if($count == of_get_option('of_an_nr4', 4)) { echo ' lastpost'; } ?>">
					<h2 class="posttitle">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
					</h2>		
					
					<?php
						gab_media(array(
							'name' => 'an-scndrmid',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 0,
							'catch_image' => of_get_option('of_catch_img', 0),
							'enable_thumb' => 1,
							'resize_type' => 'c',
							'media_width' => 110, 
							'media_height' => 66, 
							'thumb_align' => 'alignleft',
							'enable_default' => 0
						));
					?>					
					
					<p><?php  
					$text = get_post_meta($post->ID,'Home Text', true);
					if (!empty($text)){
					echo $text;
					} 
					else {
						$text= string_limit_words(get_the_excerpt(), 15); 
						$text = string_limit_words(get_the_excerpt(), 50); 					
						$pos = strpos($text, '.'); 
						echo substr($text, 0, $pos); 
					} ?>&hellip;</p>						
			
					<?php gab_postmeta(); ?>
					
				</div><!-- .featuredpost -->
			<?php $count++; endwhile; wp_reset_query(); ?>
			
		<?php } ?>		
		
		<?php gab_dynamic_sidebar('SecondaryMid2');?>
	</div><!-- #secondary-left -->
    
    
    <a href="http://www.tech4ce.org/" target="_blank"> <img src="http://indiaspend.com/wp-content/uploads/myad.jpg" 
    style="width:310px;height:388px;border-bottom:1px solid #efefef;">
</a>
    
    
	
	<div id="secondary-right">
		<?php gab_dynamic_sidebar('SecondaryRight1');?>
		
		<div class="most_section">
			<div class="tab">
			<a class="active_most" href="javascript:void(0)" id="most_read" onclick="popularpost(this.id)" >Most Read</a>
			<a href="javascript:void(0)" id="most_popular" onclick="popularpost(this.id)" >Most Popular</a>
			<a href="javascript:void(0)" id="most_mail" onclick="popularpost(this.id)" >Most Mailed</a>
			</div>
				
			<div id="most_read1">
			<?php
			echo '<ul class="entries">';
			$posts = wmp_get_popular( array( 'limit' => 5, 'post_type' => 'post', 'range' => 'monthly' ) );
			global $post;
			if ( count( $posts ) > 0 ): foreach ( $posts as $post ):
				setup_postdata( $post );
				?>
				<li><a href="<?php the_permalink() ?>"><?php the_post_thumbnail( array(60,60) ); ?> </a> 
				<a class="post-title" href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>" style="text-decoration:none;color:#003863"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
				<?php
			endforeach; endif;
			echo '</ul>';
			?> </div>
			
			<div id="most_popular1" style="display:none;">
			<?php $qry=mysql_query("select post_id,meta_value from wp_postmeta where meta_key='_msp_total_shares'"); 
			if(mysql_num_rows($qry)>0){
			while($res=mysql_fetch_assoc($qry)){
			$value[]=$res['meta_value']; ?>
			<?php }
			rsort($value);
			$fval=array_slice($value,0,5);
			$fval=array_unique($fval);
			echo '<ul class="entries">';
			for($i=0;$i<=6;$i++){
			$sid[$i]=mysql_query("Select post_id from wp_postmeta where meta_value='".$fval[$i]."' and meta_key='_msp_total_shares' ");
			while($sharedid=mysql_fetch_assoc($sid[$i])){
				?>
			<li>
			<a class="post-img" href="<?php echo get_permalink( $sharedid['post_id'] );?>" style="text-decoration:none;color:#003863"><?php echo get_the_post_thumbnail( $sharedid['post_id'], array( 60, 60) );  ?></a>
			<a class="post-title" href="<?php echo get_permalink( $sharedid['post_id'] );?>" style="text-decoration:none;color:#003863"><?php echo get_the_title($sharedid['post_id']);?></a></li>
			<?php }
			}
			echo "</ul>";
			}
			?>
			</div>

			<div id="most_mail1" style="display:none;">
			<?php $email=mysql_query("SELECT email_posttitle,email_postid,COUNT(email_postid) as count FROM wp_email GROUP BY email_postid ORDER BY count DESC limit 5");
			echo '<ul class="entries">';
			while($res_email=mysql_fetch_assoc($email)){ ?>
			<li>
			<a class="post-img" href="<?php echo get_permalink( $res_email['email_postid'] );?>" style="text-decoration:none;color:#003863"><?php echo get_the_post_thumbnail( $res_email['email_postid'], array( 60, 60) );  ?></a>
			<a class="post-title" href="<?php echo get_permalink($res_email['email_postid']); ?>" style="text-decoration:none;color:#003863"><?php echo $res_email['email_posttitle']; ?></a></li>				
			<?php }
			echo "</ul>";
			?>
			</div>

			</div>
		<!-- Close MOST SECTION -->
			
				<div class="featuredpost">
				<!--
<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
			<script>
			new TWTR.Widget({
			  version: 2,
			  type: 'search',
			  search: 'indiaspend',
			  interval: 30000,
			  title: '',
			  subject: 'IndiaSpend',
			  width: 300,
			  height: 275,
			  theme: {
				shell: {
				  background: '#CD1713',
				  color: '#ffffff'
				},
				tweets: {
				  background: '#eeeeee',
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
			</script>
					-->
					<!-- <a class="twitter-timeline" href="https://twitter.com/IndiaSpend" data-widget-id="299132840023564288">Tweets by @IndiaSpend</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script> 
-->

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
		

				</div><!-- .featuredpost --> 
		<!-- MOST SECTION -->

		<?php //include_once(TEMPLATEPATH . '/ads/mainpage_300x250-top.php'); ?>

		<?php //gab_dynamic_sidebar('SecondaryRight2');?>
	</div><!-- #secondary-right -->
	<div class="clear"></div>
</div><!-- #secondarycontent -->


<?php /* Call media slider if the number of entries are set greater than 0 on theme control panel */
$postnr = of_get_option('of_an_tabnr1') + of_get_option('of_an_tabnr2') + of_get_option('of_an_tabnr3') + of_get_option('of_an_tabnr4') + of_get_option('of_an_tabnr5');
if (intval($postnr) > 0 ) { ?>
	
	<div id="mid-slider">
			
		<div id="mid-slider-nav">
			<ul id="mid-slider-pagination">
				<?php
				$cat_count = 5;
				$options = array();
				for ($i=1; $i<=$cat_count;$i++) {
					if(of_get_option('of_an_tabnr'.$i) !== '0' ) {
						if (intval(of_get_option('of_an_tabnr'.$i)) > 0 ) { ?>
							<li><a href="#"><?php echo get_cat_name(of_get_option('of_an_tabcat'.$i)); ?></a></li><?php 
						} 
					} 
				}
				?>
			</ul>
			<a class="media_next" id="media_next" href="#"><?php _e('Next','advanced'); ?></a>
			<a class="media_prev" id="media_prev" href="#"><?php _e('Previous','advanced'); ?></a>
			<div class="clear"></div>
		</div><!-- /nav -->

		<div class="fea-slides-wrapper"> <!-- using this class just to generate a border -->
			<div class="fea-slides">
				<?php 
				$cat_count = 5;
				$options = array();

				for ($i=1; $i<=$cat_count;$i++) {
					if(0 < strlen($variable = of_get_option('of_an_tabnr'.$i))) {
						$options[$i]['posts_per_page'] = $variable;
					}
					if(0 < strlen($variable = of_get_option('of_an_tabcat'.$i))) {
						$options[$i]['cat'] = $variable;
					}
				}

				foreach ($options as $id => $option)
				{				
					 if (0 == $options[$id]['posts_per_page']) {
						continue;
					}
				?>

					<div class="slide_item">
						<?php
						$count = 1;
						$args = array(
						  'post__not_in'=> $do_not_duplicate,
						  'posts_per_page' => $options[$id]['posts_per_page'], 
						  'cat' => $options[$id]['cat']
						);				
						$gab_query = new WP_Query();$gab_query->query($args); 
						while ($gab_query->have_posts()) : $gab_query->the_post();
						if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }
						?>

							<div class="featured-media<?php if($count% 4 == 0) { echo ' last'; }?>">
								<?php 
								gab_media(array(
									'name' => 'an-midtabs', 
									'imgtag' => 1,
									'link' => 1,
									'enable_video' => 1, 
									'catch_image' => of_get_option('of_catch_img', 'false'),
									'video_id' => 'midslide1', 
									'enable_thumb' => 1, 
									'resize_type' => 'c', 
									'media_width' => '216', 
									'media_height' => '120', 
									'thumb_align' => 'tabbedimg', 
									'enable_default' => 0
								)); 										
								?>
								
								<h2 class="posttitle">
									<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
								</h2>
								
							<!-- <p><?php //echo string_limit_words(get_the_excerpt(),10); ?></p>-->
							
							
							</div><!-- .featured-media -->
							<?php if($count % 4 == 0) { echo '<div class="clear"></div>';  } ?>
						<?php $count++; endwhile; wp_reset_query(); ?>
					</div><!-- .slide-item -->			
				<?php } ?>			
			</div><!-- .fea-slides -->
			
		</div><!-- .fea-slides-wrapper -->
	</div><!-- #mid-slider -->
	
<?php } ?>


<div id="subnews">
	<div id="subnews_left">
		<div class="col">
			<?php gab_dynamic_sidebar('Subnews1');?>
			
			<?php if (intval(of_get_option('of_an_nr11', 1)) > 0 ) { ?>
				<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat11'));?>"><?php echo get_cat_name(of_get_option('of_an_cat11')); ?></a></span>
				<?php
				$count = 1;
				$args = array(
				  'post__not_in'=> $do_not_duplicate,
				  'posts_per_page' => of_get_option('of_an_nr11', 1),
				  'cat' => of_get_option('of_an_cat11')
				);
				$gab_query = new WP_Query();$gab_query->query($args); 
				while ($gab_query->have_posts()) : $gab_query->the_post();
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
				?>
				<div class="featuredpost<?php if($count == of_get_option('of_an_nr11', 1)) { echo ' lastpost'; } ?>">

					<h2 class="posttitle">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
					</h2>
										
					<?php
						if(of_get_option('of_an_subphotos', 0) == 1) {
						gab_media(array(
							'name' => 'an-subnews',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 0,
							'catch_image' => of_get_option('of_catch_img', 0),
							'enable_thumb' => 1,
							'resize_type' => 'c',
							'media_width' => 153, 
							'media_height' => 100, 
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						}
					?>						
					
					<?php //echo string_limit_words(get_the_excerpt(), 27); ?>

					
					<?php gab_postmeta(true,true,false,true); ?>
				</div><!-- .featuredpost -->
				<?php $count++; endwhile; wp_reset_query(); ?>
			<?php } ?>
		</div><!-- .col -->
		
		<div class="col">
			<?php gab_dynamic_sidebar('Subnews2');?>
			
			<?php if (intval(of_get_option('of_an_nr12', 1)) > 0 ) { ?>
				<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat12'));?>"><?php echo get_cat_name(of_get_option('of_an_cat12')); ?></a></span>
				<?php
				$count = 1;
				$args = array(
				  'post__not_in'=> $do_not_duplicate,
				  'posts_per_page' => of_get_option('of_an_nr12', 1),
				  'cat' => of_get_option('of_an_cat12')
				);
				$gab_query = new WP_Query();$gab_query->query($args); 
				while ($gab_query->have_posts()) : $gab_query->the_post();
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
				?>
				<div class="featuredpost<?php if($count == of_get_option('of_an_nr12', 1)) { echo ' lastpost'; } ?>">
						
					<h2 class="posttitle">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
					</h2>
										
					<?php
						if(of_get_option('of_an_subphotos', 0) == 1) {
						gab_media(array(
							'name' => 'an-subnews',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 0,
							'catch_image' => of_get_option('of_catch_img', 0),
							'enable_thumb' => 1,
							'resize_type' => 'c',
							'media_width' => 153, 
							'media_height' => 100, 
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						}
					?>						
					
				<!-- <p><?php //echo string_limit_words(get_the_excerpt(),10); ?></p>-->
					
					<?php gab_postmeta(true,true,false,true); ?>
				</div><!-- .featuredpost -->
				<?php $count++; endwhile; wp_reset_query(); ?>
			<?php } ?>
		</div><!-- .col -->
		
		<div class="col">
			<?php gab_dynamic_sidebar('Subnews3');?>
			
			<?php if (intval(of_get_option('of_an_nr13', 1)) > 0 ) { ?>
				<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat13'));?>"><?php echo get_cat_name(of_get_option('of_an_cat13')); ?></a></span>
				<?php
				$count = 1;
				$args = array(
				  'post__not_in'=> $do_not_duplicate,
				  'posts_per_page' => of_get_option('of_an_nr13', 1),
				  'cat' => of_get_option('of_an_cat13')
				);
				$gab_query = new WP_Query();$gab_query->query($args); 
				while ($gab_query->have_posts()) : $gab_query->the_post();
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
				?>
				<div class="featuredpost<?php if($count == of_get_option('of_an_nr13', 1)) { echo ' lastpost'; } ?>">
						
					<h2 class="posttitle">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
					</h2>
										
					<?php
						if(of_get_option('of_an_subphotos', 0) == 1) {
						gab_media(array(
							'name' => 'an-subnews',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 0,
							'catch_image' => of_get_option('of_catch_img', 0),
							'enable_thumb' => 1,
							'resize_type' => 'c',
							'media_width' => 153, 
							'media_height' => 100, 
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						}
					?>						
					
					<!-- <p><?php //echo string_limit_words(get_the_excerpt(),10); ?></p>-->
					
					<?php gab_postmeta(true,true,false,true); ?>
				</div><!-- .featuredpost -->
				<?php $count++; endwhile; wp_reset_query(); ?>
			<?php } ?>
		</div><!-- .col -->
		
		<div class="col last">
			<?php gab_dynamic_sidebar('Subnews4');?>
			
			<?php if (intval(of_get_option('of_an_nr14', 1)) > 0 ) { ?>
				<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat14'));?>"><?php echo get_cat_name(of_get_option('of_an_cat14')); ?></a></span>
				<?php
				$count = 1;
				$args = array(
				  'post__not_in'=> $do_not_duplicate,
				  'posts_per_page' => of_get_option('of_an_nr14', 1),
				  'cat' => of_get_option('of_an_cat14')
				);
				$gab_query = new WP_Query();$gab_query->query($args); 
				while ($gab_query->have_posts()) : $gab_query->the_post();
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
				?>
				<div class="featuredpost<?php if($count == of_get_option('of_an_nr14', 1)) { echo ' lastpost'; } ?>">
						
					<h2 class="posttitle">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
					</h2>
										
					<?php
						if(of_get_option('of_an_subphotos', 0) == 1) {
						gab_media(array(
							'name' => 'an-subnews',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 0,
							'catch_image' => of_get_option('of_catch_img', 0),
							'enable_thumb' => 1,
							'resize_type' => 'c',
							'media_width' => 153, 
							'media_height' => 100, 
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						}
					?>						
					
					<!-- <p><?php //echo string_limit_words(get_the_excerpt(),10); ?></p>-->
					
					<?php gab_postmeta(true,true,false,true); ?>
				</div><!-- .featuredpost -->
				<?php $count++; endwhile; wp_reset_query(); ?>
			<?php } ?>
		</div><!-- .col -->		
		
		<div class="border"></div>
		
		<div class="col">
			<?php gab_dynamic_sidebar('Subnews5');?>
			
			<?php if (intval(of_get_option('of_an_nr15', 1)) > 0 ) { ?>
				<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat15'));?>"><?php echo get_cat_name(of_get_option('of_an_cat15')); ?></a></span>
				<?php
				$count = 1;
				$args = array(
				  'post__not_in'=> $do_not_duplicate,
				  'posts_per_page' => of_get_option('of_an_nr15', 1),
				  'cat' => of_get_option('of_an_cat15')
				);
				$gab_query = new WP_Query();$gab_query->query($args); 
				while ($gab_query->have_posts()) : $gab_query->the_post();
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
				?>
				<div class="featuredpost<?php if($count == of_get_option('of_an_nr15', 1)) { echo ' lastpost'; } ?>">
						
					<h2 class="posttitle">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
					</h2>
										
					<?php
						if(of_get_option('of_an_subphotos', 0) == 1) {
						gab_media(array(
							'name' => 'an-subnews',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 0,
							'catch_image' => of_get_option('of_catch_img', 0),
							'enable_thumb' => 1,
							'resize_type' => 'c',
							'media_width' => 153, 
							'media_height' => 100, 
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						}
					?>						
					
					<!-- <p><?php //echo string_limit_words(get_the_excerpt(),10); ?></p>-->
					
					<?php gab_postmeta(true,true,false,true); ?>
				</div><!-- .featuredpost -->
				<?php $count++; endwhile; wp_reset_query(); ?>
			<?php } ?>
		</div><!-- .col -->
		
		<div class="col">
			<?php gab_dynamic_sidebar('Subnews6');?>
			
			<?php if (intval(of_get_option('of_an_nr16', 1)) > 0 ) { ?>
				<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat16'));?>"><?php echo get_cat_name(of_get_option('of_an_cat16')); ?></a></span>
				<?php
				$count = 1;
				$args = array(
				  'post__not_in'=> $do_not_duplicate,
				  'posts_per_page' => of_get_option('of_an_nr16', 1),
				  'cat' => of_get_option('of_an_cat16')
				);
				$gab_query = new WP_Query();$gab_query->query($args); 
				while ($gab_query->have_posts()) : $gab_query->the_post();
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
				?>
				<div class="featuredpost<?php if($count == of_get_option('of_an_nr16', 1)) { echo ' lastpost'; } ?>">

					<h2 class="posttitle">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
					</h2>
										
					<?php
						if(of_get_option('of_an_subphotos', 0) == 1) {
						gab_media(array(
							'name' => 'an-subnews',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 0,
							'catch_image' => of_get_option('of_catch_img', 0),
							'enable_thumb' => 1,
							'resize_type' => 'c',
							'media_width' => 153, 
							'media_height' => 100, 
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						}
					?>						
					
<!-- <p><?php //echo string_limit_words(get_the_excerpt(),10); ?></p>-->					
					<?php gab_postmeta(true,true,false,true); ?>
				</div><!-- .featuredpost -->
				<?php $count++; endwhile; wp_reset_query(); ?>
			<?php } ?>
		</div><!-- .col -->
		
		<div class="col">
			<?php gab_dynamic_sidebar('Subnews7');?>
			
			<?php if (intval(of_get_option('of_an_nr17', 1)) > 0 ) { ?>
				<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat17'));?>"><?php echo get_cat_name(of_get_option('of_an_cat17')); ?></a></span>
				<?php
				$count = 1;
				$args = array(
				  'post__not_in'=> $do_not_duplicate,
				  'posts_per_page' => of_get_option('of_an_nr17', 1),
				  'cat' => of_get_option('of_an_cat17')
				);
				$gab_query = new WP_Query();$gab_query->query($args); 
				while ($gab_query->have_posts()) : $gab_query->the_post();
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
				?>
				<div class="featuredpost<?php if($count == of_get_option('of_an_nr17', 1)) { echo ' lastpost'; } ?>">

					<h2 class="posttitle">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
					</h2>
										
					<?php
						if(of_get_option('of_an_subphotos', 0) == 1) {
						gab_media(array(
							'name' => 'an-subnews',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 0,
							'catch_image' => of_get_option('of_catch_img', 0),
							'enable_thumb' => 1,
							'resize_type' => 'c',
							'media_width' => 153, 
							'media_height' => 100, 
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						}
					?>						
					
					<!-- <p><?php //echo string_limit_words(get_the_excerpt(),10); ?></p>-->
					
					<?php gab_postmeta(true,true,false,true); ?>
				</div><!-- .featuredpost -->
				<?php $count++; endwhile; wp_reset_query(); ?>
			<?php } ?>
		</div><!-- .col -->
		
		<div class="col last">
			<?php gab_dynamic_sidebar('Subnews8');?>
			
			<?php if (intval(of_get_option('of_an_nr18', 1)) > 0 ) { ?>	
				<span class="catname"><a href="<?php echo get_category_link(of_get_option('of_an_cat18'));?>"><?php echo get_cat_name(of_get_option('of_an_cat18')); ?></a></span>
				<?php
				$count = 1;
				$args = array(
				  'post__not_in'=> $do_not_duplicate,
				  'posts_per_page' => of_get_option('of_an_nr18', 1),
				  'cat' => of_get_option('of_an_cat18')
				);
				$gab_query = new WP_Query();$gab_query->query($args); 
				while ($gab_query->have_posts()) : $gab_query->the_post();
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }	
				?>
				<div class="featuredpost<?php if($count == of_get_option('of_an_nr18', 1)) { echo ' lastpost'; } ?>">
						
					<h2 class="posttitle">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
					</h2>
										
					<?php
						if(of_get_option('of_an_subphotos', 0) == 1) {
						gab_media(array(
							'name' => 'an-subnews',
							'imgtag' => 1,
							'link' => 1,
							'enable_video' => 0,
							'catch_image' => of_get_option('of_catch_img', 0),
							'enable_thumb' => 1,
							'resize_type' => 'c',
							'media_width' => 153, 
							'media_height' => 100, 
							'thumb_align' => 'aligncenter',
							'enable_default' => 0
						));
						}
					?>						
					
				<!-- <p><?php //echo string_limit_words(get_the_excerpt(),10); ?></p>-->
					
					<?php gab_postmeta(true,true,false,true); ?>
				</div><!-- .featuredpost -->
				<?php $count++; endwhile; wp_reset_query(); ?>
			<?php } ?>
		</div><!-- .col -->
	</div><!-- #subnews_left -->
	
	<div id="subnews_right">
		<?php 
		
		
		gab_dynamic_sidebar('Home-Sidebar1');
		
		if (intval(of_get_option('of_an_nr20', 5)) > 0 ) { ?>

			<div id="video-slider">
				<div id="video-slider-nav">
					<div id="vid-nav"></div>
					<a class="vid_next" id="vid_next" href="#"><?php _e('Next','advanced'); ?></a>
					<a class="vid_prev" id="vid_prev" href="#"><?php _e('Previous','advanced'); ?></a>
				</div><!-- /nav -->

				<div class="fea-slides">
					<?php 
					$count = 1;
					$args = array(
					  'post__not_in'=> $do_not_duplicate,
					  'post_type' => 'any',
					  'posts_per_page' => of_get_option('of_an_nr20', 5), 
					  'meta_key' => 'iframe'
					);

					$gab_query = new WP_Query();$gab_query->query($args); 
					while ($gab_query->have_posts()) : $gab_query->the_post();
					if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }
					$gab_iframe = get_post_meta($post->ID, 'iframe', true);
					?>
					
					<div class="slide_item">
					
						<h2 class="posttitle">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( '%s', 'advanced' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
						</h2>
						
						<?php 
						gab_media(array(
							'name' => 'an-vs', 
							'enable_video' => 1, 
							'catch_image' => of_get_option('of_catch_img', 0),
							'video_id' => 'sidebar-vid', 
							'enable_thumb' => 1, 
							'resize_type' => 'c', /* c to crop, h to resize only height, w to resize only width */
							'media_width' => 280, 
							'media_height' => 220, 
							'thumb_align' => 'media', 
							'enable_default' => 0
						)); 										
						?>
					</div><!-- .slide-item -->

					<?php $count++; endwhile; wp_reset_query(); ?>
				</div><!-- .fea-slides -->
			</div><!-- #video-slider -->
		
		<?php }
		include_once(TEMPLATEPATH . '/ads/mainpage_300x250-bottom.php');
		
		gab_dynamic_sidebar('Home-Sidebar2');
		?>
	</div>
	<div class="clear"></div>
</div><!-- .subnews -->

<?php get_footer(); ?>