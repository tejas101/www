<?php
/* Define file storage if that is a wpmu site */
if(is_multisite()) {
	function redirect_wpmu ($img) {
		global $blog_id;
	  $imageParts = explode('/files/', $img);
		if (isset($imageParts[1])) {
			$img = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
		}
		return($img);
	}
}


function catch_that_image() {
	if (of_get_option('of_catch_img') == 1) { 
	  global $post, $posts;
	  $first_img = '';
	  ob_start();
	  ob_end_clean();
	  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	  $first_img = $matches [1] [0];

		return $first_img;
	}
}



	function call_flv_with_add ($parameters){
		global $post, $gab_flv, $ad_flv;
		
			$video_id = $parameters['name'].'_'.$parameters['video_id'].'_'.$post->ID;
			
			echo '
				<span class="'.$parameters['thumb_align'].'">
					<a href="'.esc_url($gab_flv).'" style="display:block;width:'.$parameters['media_width'].'px;height:'.$parameters['media_height'].'px" id="'.$video_id.'"></a> 
					<script type="text/javascript">
						$f("'.$video_id.'", "http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf", {

							// controlbar is initially hidden
							plugins: {
								controls:  {display: "none" }
							},
							
							// properties that are common to both clips in the playlist
							clip: {
								baseUrl: "http://blip.tv/file/get",
								wmode: "transparent",
							},
							
							// playlist with two entries
							playlist: [
							
								// user is forced to see this entry. pause action is disabled
								{
									url: "'. esc_url( $ad_flv ).'",

									onBeforePause: function() {
										return false;
									} 
								},
								
								// this is the actual video. controlbar is shown
								{
									url: "'. esc_url( $ad_flv ).'",
									onStart: function() {
										this.getControls().show();
									}, 
									
									// when playback finishes player is resumed back to its original state
									onFinish: function() {
										this.unload();
									}
								}	
							]
						});				
					</script>
				</span>';
	}
	function call_flv ($parameters){
		global $post, $gab_flv;
			$video_id = $parameters['name'].'_'.$parameters['video_id'].'_'.$post->ID;
			echo '
			<span class="'.$parameters['thumb_align'].'">
				<a href="'.esc_url($gab_flv).'" style="display:block;width:'.$parameters['media_width'].'px;height:'.$parameters['media_height'].'px" id="'.$video_id.'"></a> 				
				<script type="text/javascript">
				   flowplayer(
					  "'.$video_id.'",
					  { src:"'; echo GABFIRE_JS_DIR . '/flowplayer/flowplayer-3.2.7.swf",
						wmode: "opaque" },
					  { clip: {
						  autoPlay: false,
						  autoBuffering: true  }
					  }
				   );
				</script>
			</span>';
	}
	
	function call_swf ($parameters){
		global $post, $gab_video;
		$gab_video = get_post_meta($post->ID, 'video', true);
		echo '
			<span class="'.$parameters['thumb_align'].'">
				<object type="application/x-shockwave-flash" style="width:'.$parameters['media_width'].'px; height:'.$parameters['media_height'].'px;" data="'. esc_url ( $gab_video ) .'">
				<param name="wmode" value="opaque" /><param name="movie" value="'. esc_url ( $gab_video ) .'" /></object>
			</span>'; 
	}
	
	function call_iframe ($parameters){
		global $post, $gab_iframe;
		
		/* Remove WWW to support videos for old gabfire theme's framework not to cause any conflict with real embed codes over regular video urls */
		$gab_iframe = str_replace("http://www.", "http://", $gab_iframe);
		
		$orj_value = array("http://youtube.com/watch?v=", "http://vimeo.com/",   "http://dailymotion.com/video/", "http://screenr.com/" );
		$new_value = array("http://youtube.com/embed/", "http://player.vimeo.com/video/", "http://dailymotion.com/embed/video/", "http://screenr.com/embed/");
		$gab_iframe = str_replace($orj_value, $new_value, $gab_iframe);
		
		if(strpos($gab_iframe, "&")) { /* Remove unnecessary part from youtube's URL */
			$gab_iframe = strpos($gab_iframe, "&") ? substr($gab_iframe, 0, strpos($gab_iframe, "&")) : $gab_iframe; 
		} else if(strpos($gab_iframe, "_") and strstr($gab_iframe,'dailymotion') ) { /* Remove unnecessary part from dailymotion's URL */
			$gab_iframe = strpos($gab_iframe, "_") ? substr($gab_iframe, 0, strpos($gab_iframe, "_")) : $gab_iframe; 
		}
		
		$orj_value_final = array("http://youtube.com", "http://dailymotion.com", "http://screenr.com" );
        $new_value_final = array("http://www.youtube.com", "http://www.dailymotion.com", "http://www.screenr.com");
        $gab_iframe = str_replace($orj_value_final, $new_value_final, $gab_iframe); 
		
		echo '
			<span class="'.$parameters['thumb_align'].'">		
				<iframe title="';the_title(''); echo '" src="'. esc_url($gab_iframe) .'?wmode=opaque&amp;showinfo=0&amp;autohide=1" width="'.$parameters['media_width'].'" height="'.$parameters['media_height'].'" allowfullscreen></iframe>
			</span>'; 
	}

	function call_tthumb ($parameters){ /* Call plain url to post thumbnail */
		global $post, $gab_thumb;
		
		if ($gab_thumb !== '') { 
			echo esc_url( get_template_directory_uri()) . '/timthumb.php?src=';
			echo urlencode($gab_thumb) .'&amp;q=90&amp;';
				if (($parameters['resize_type'] == 'c') or ($parameters['resize_type'] !== 'h') ) { 
					echo 'w='.$parameters['media_width'].'&amp;';
				}
				if (($parameters['resize_type'] == 'c') or ($parameters['resize_type'] !== 'w') ) { 
					echo 'h='.$parameters['media_height'].'&amp;'; 
				}
			echo 'zc=1"';
		} else {
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'gab_featured' );
			$url = $thumb['0'];
			
			echo esc_url( get_template_directory_uri()) . '/timthumb.php?src=';
			if(is_multisite()) { 
				echo urlencode(redirect_wpmu($url)); 
			} else { 
				echo urlencode($url); 
			}
			echo '&amp;q=90&amp;'; 
				if (($parameters['resize_type'] == 'c') or ($parameters['resize_type'] !== 'h') ) { 
					echo 'w='.$parameters['media_width'].'&amp;';
				}
				if (($parameters['resize_type'] == 'c') or ($parameters['resize_type'] !== 'w') ) { 
					echo 'h='.$parameters['media_height'].'&amp;'; 
				}
			echo 'zc=1';
			
		}
	}
	function call_tthumbimg ($parameters){ /* Get post thumbnail and add img tag based on parameters of gab_media array */
		global $post;
		
		echo '<img src="';
			call_tthumb($parameters);
		echo '" class="'.$parameters['thumb_align'].'" alt="';the_title(''); echo '" title="';the_title(''); echo '" />'; 
	}
	
	function call_tthumblink ($parameters){ /* Get post thumbnail and add a link based on parameters of gab_media array */
		global $post;
			echo '<a href="';the_permalink(); echo '" rel="bookmark">';	
				call_tthumbimg($parameters);
			echo '</a>';	
	}
	
	function call_post_thumb ($parameters){ /* Get default Post Thumbnail of WordPress */ 
		global $post;
		$image_id = get_post_thumbnail_id();  
		$image_url = wp_get_attachment_image_src($image_id,$parameters['name']);  
		$image_url = $image_url[0]; 
		
		if ($parameters['link'] == 1) {
			echo '<a href="';the_permalink(); echo '" rel="bookmark">';	
		}
		
		if ($parameters['imgtag'] == 1) { 
			echo '<img src="';
		}
		echo $image_url;
		
		if ($parameters['imgtag'] == 1) {  
			echo '" class="'.$parameters['thumb_align'].'" alt="';the_title(''); echo '" title="';the_title(''); echo '" />';
		}
		
		if ($parameters['link'] == 1) {
			echo '</a>'; 
		} 
	}
	
	function call_firstimage ($parameters){ /* Catch first image */ 
		$url = catch_that_image();
		
		if ($parameters['link'] == 1) {
			echo '<a href="';the_permalink(); echo '" rel="bookmark">';	
		}
		if ($parameters['imgtag'] == 1) { 
			echo '<img src="';
		}
		
		echo esc_url( get_template_directory_uri()) . '/timthumb.php?src=';
		
		if(is_multisite()) { 
			echo urlencode(redirect_wpmu($url)); 
		} else { 
			echo urlencode($url); 
		}
		echo '&amp;q=90&amp;'; 
			if (($parameters['resize_type'] == 'c') or ($parameters['resize_type'] !== 'h') ) { 
				echo 'w='.$parameters['media_width'].'&amp;';
			}
			if (($parameters['resize_type'] == 'c') or ($parameters['resize_type'] !== 'w') ) { 
				echo 'h='.$parameters['media_height'].'&amp;'; 
			}
		echo 'zc=1';		
		
		if ($parameters['imgtag'] == 1) {  
			echo '" class="'.$parameters['thumb_align'].'" alt="';the_title(''); echo '" title="';the_title(''); echo '" />';
		}
		
		if ($parameters['link'] == 1) {
			echo '</a>'; 
		}
	}
	
	function call_default_thumb ($parameters){ /* Catch default thumbnail (image name is written into gab_media array. The image is located in template_url/images/thumbs dir */ 
		global $post;
		
		if ($parameters['link'] == 1) {
			echo '<a href="';the_permalink(); echo '" rel="bookmark">';	
		}

		if ($parameters['imgtag'] == 1) { 
			echo '<img src="';
		}
			echo esc_url(get_template_directory_uri()); echo '/images/thumbs/'.$parameters['default_name'];
		
		if ($parameters['imgtag'] == 1) {  
			echo '" class="'.$parameters['thumb_align'].'" alt="';the_title(''); echo '" title="';the_title(''); echo '" />';
		}		
		
		if ($parameters['link'] == 1) {
			echo '</a>'; 
		}		
	}

function gab_media($parameters) 
{
	# Define globals
	global $post, $gab_video, $gab_thumb, $gab_flv, $gab_iframe,$ad_flv;
	$gab_thumb = get_post_meta($post->ID, 'thumbnail', true);
	$gab_video = get_post_meta($post->ID, 'video', true);
	$gab_flv = get_post_meta($post->ID, 'videoflv', true);
	$ad_flv = get_post_meta($post->ID, 'adflv', true);
	$gab_iframe = get_post_meta($post->ID, 'iframe', true);

	if($ad_flv != '' and $gab_flv != '' and $parameters['enable_video'] == 1) 
	{ 
		call_flv_with_add ($parameters);
	}
	
	elseif($gab_flv != '' and $parameters['enable_video'] == 1) 
	{ 
		call_flv ($parameters);
	}
	
	elseif ($gab_video != '' and $parameters['enable_video'] == 1) 
	{ 
		call_swf ($parameters);
	}
	
	elseif ($gab_iframe != '' and $parameters['enable_video'] == 1)
	{ 
		call_iframe ($parameters);
	}
	
	elseif ($gab_thumb != '' and $parameters['enable_thumb'] == 1)
	{ 
		if ($parameters['link'] == 1) { 
			call_tthumblink($parameters);
		} 
		elseif ($parameters['imgtag'] == 1) {
			call_tthumbimg($parameters);
		} 
		else {
			call_tthumb ($parameters);
		}
	} 		
	
	elseif((of_get_option('of_wpmumode')== 0 ) and $parameters['enable_thumb'] == 1 and has_post_thumbnail()) 
	{ 
		if ($parameters['link'] == 1) 
		{ 
			call_tthumblink($parameters);
		} 
		elseif ($parameters['imgtag'] == 1)
		{
			call_tthumbimg($parameters);
		} 
		else {
			call_tthumb ($parameters);
		}
	} 	

	elseif((of_get_option('of_wpmumode')==1) and has_post_thumbnail() and ($parameters['enable_thumb'] == 1))
	{
		call_post_thumb ($parameters);
	} 
		
	else 
	{
		$url = catch_that_image();
		if( (isset($url)) and ($parameters['catch_image'] == 1))  
		{
			call_firstimage ($parameters);
		}
		elseif($parameters['enable_default'] == 1) 
		{
			call_default_thumb ($parameters);
		} 
	}
}