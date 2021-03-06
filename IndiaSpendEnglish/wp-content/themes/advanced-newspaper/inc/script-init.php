<?php 
function theme_scripts() {
	$options_feaslide = array("scrollUp", "scrollDown", "scrollLeft", "scrollRight", "turnUp", "turnDown", "turnLeft", "turnRight", "fade");
?>

	<script type='text/javascript'>
	(function($) {
		$(document).ready(function() { 
			
			$('a[href=#top]').click(function(){
				$('html, body').animate({scrollTop:0}, 'slow');
				return false;
			});
			
			$("a[rel=gab_gallery]").fancybox({
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'titlePosition' 	: 'over',
			'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
				return '<span id="fancybox-title-over">' + title /* + ' ('+(currentIndex + 1) + ' / ' + currentArray.length + ')' */ +'</span>';
			}
			});			
			
			$(".show").fancybox({  
				'titleShow'     : 'false',  
				'transitionIn'      : 'fade',  
				'transitionOut'     : 'fade'  
			});
			
			$(".iframe").fancybox({	
				'width'	: '75%',
				'height' : '75%',
				'autoScale'     	: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe'
			});				

			<?php if(is_home()) { ?>
				$('#featured-slider .fea-slides').cycle({ 
					pauseOnPagerHover: 1,
					prev:   '.fea_prev',
					next:   '.fea_next',
					pauseOnPagerHover: 1,
					fx:     '<?php echo $options_feaslide[of_get_option('of_an_sfunc', 8)]; ?>',
					timeout: '<?php echo of_get_option('of_an_featimeout', 5); if (intval(of_get_option('of_an_featimeout', 5)) > 0 ) { echo "000"; } ?>',
					speed: '<?php echo of_get_option('of_an_feaspeed', 1); if (intval(of_get_option('of_an_feaspeed', 3)) > 0 ) { echo "000"; } ?>',
					pager:  '#featured-nav', 
					pagerAnchorBuilder: function(idx, slide) { 
						// return selector string for existing anchor 
						return '#featured-nav li:eq(' + idx + ') a'; 
					} 
				});

				$('#video-slider .fea-slides').cycle({ 
					pauseOnPagerHover: 1,
					prev:   '.vid_prev',
					next:   '.vid_next',
					pager:  '#vid-nav', 
					fx:     'fade',
					timeout: 0
				});
				
				$('#mid-slider .fea-slides').cycle({ 
					fx:     'fade', 
					timeout: 0,
					prev:   '.media_prev',
					next:   '.media_next',
					pauseOnPagerHover: 1,
					pager:  '#mid-slider-pagination', 
					pagerAnchorBuilder: function(idx, slide) { 
						// return selector string for existing anchor 
						return '#mid-slider-pagination li:eq(' + idx + ') a'; 
					} 
				});
			<?php } elseif (is_single()) { ?>
				$('#slides').slides({
					<?php if(of_get_option('of_inner_rotate') == 1) { ?>
					play: <?php if ( of_get_option('of_inner_pause', 5) <> "" ) { echo of_get_option('of_an_inner_pause', 5).'000'; } else { echo '5000'; } ?>,
					pause: 2500,
					hoverPause: true,
					<?php } ?>
					autoHeight: true
				});
			<?php } ?>
			
			$("ul.tabs").tabs("div.panes > div");
			// $("ul.sc_tabs").tabs("div.sc_tabs-content > div");
			
		});
	})(jQuery);
	</script>
<?php } 

/* ********************
 * Share Items
 * Required JS libraries for share items
 ******************************************************************** */
 function gab_twitter() {
	echo '<script type=\'text/javascript\'>
	<!--
	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
	// -->
	</script>';
 }
 
 function gab_facebook() {
	$language = get_bloginfo('language'); 
	$language = str_replace("-", "_", $language); 

	echo '
	<div id="fb-root"></div>
	<script type=\'text/javascript\'>
	<!--
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/'. $language .'/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, \'script\', \'facebook-jssdk\'));
	// -->
	</script>';
 }
 
 function gab_googleplus() {
	echo '<script type="text/javascript">
	  <!--
	  (function() {
		var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
		po.src = \'https://apis.google.com/js/plusone.js\';
		var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
	  })();
	  // -->
	</script>';
 
 }
 function gab_pinterest() {
	echo '<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
	
	<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js">
	<!--
		document.body.innerHTML = document.body.innerHTML.replace(/&amp;;/g, "a");
	// -->
	</script>';
 }

add_action("wp_head", "theme_scripts"); 
add_action("wp_footer", "gab_facebook"); 