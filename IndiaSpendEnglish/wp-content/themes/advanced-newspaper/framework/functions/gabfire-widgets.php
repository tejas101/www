<?php
/*
 * TEXT WIDGET
 */
class text_widget extends WP_Widget {
	function text_widget() {
		$widget_ops = array( 'classname' => 'text_widget', 'description' => 'Display text widget with an icon' );
		$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'text_widget' );
		$this->WP_Widget( 'text_widget', 'Gabfire Widget : Text', $widget_ops, $control_ops);	
	}
	
	function widget($args, $instance) {
		extract( $args );
		$title	= $instance['title'];
		$icon	= $instance['icon'];
		$text	= $instance['a_text'];
		$link 	= $instance['a_link'];
		$anchor	= $instance['a_anchor'];

		echo $before_widget;

			if ( $title ) {
				echo $before_title;
					echo '<span style="background: url(' . get_template_directory_uri() . '/framework/images/24x/' . $icon . '.png) no-repeat left center;padding:3px 0 3px 30px;">';
						echo $title;
					echo '</span>';
				echo $after_title;
			}
						
			echo '<p>' . nl2br($text) . '</p>';
				
			if($link) {
				echo '<span class="about_more"><a href="' . get_permalink($link) . '">'. $anchor . '</a></span>';
			}
			
		echo $after_widget; 
	}
	
	function update($new_instance, $old_instance) {  
		$instance['title']		= strip_tags($new_instance['title']);
		$instance['icon']		= strip_tags($new_instance['icon']);
		$instance['a_text'] 	= strip_tags($new_instance['a_text']);
		$instance['a_link'] 	= strip_tags($new_instance['a_link']);
		$instance['a_anchor'] 	= strip_tags($new_instance['a_anchor']); 
		return $new_instance;
	}
 
	function form($instance) {
		$defaults	= array( 'title' => 'About', 'icon' => 'world', 'a_text' => '', 'a_link' => '', 'a_anchor' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id( 'icon' ); ?>">Icon</label> 
		<select id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>">
			
			<option value="appearance" <?php selected( $instance['icon'], 'appearance'  );?>>Appearance</option>
			<option value="arrow-right" <?php  selected( $instance['icon'], 'arrow-right' ); ?>>Arrow Right</option>
			<option value="checkmark" <?php  selected( $instance['icon'], 'checkmark' ); ?>>Checkmark</option>
			<option value="comment" <?php  selected( $instance['icon'], 'comment' ); ?>>Comment</option>
			<option value="contact" <?php  selected( $instance['icon'], 'contact' ); ?>>Contact</option>
			<option value="excellent" <?php  selected( $instance['icon'], 'excellent'); ?>>Excellent</option>
			<option value="home" <?php  selected( $instance['icon'], 'home' ); ?>>Home</option>
			<option value="info" <?php  selected( $instance['icon'], 'info'  ); ?>>Info</option>
			<option value="gear" <?php  selected( $instance['icon'], 'gear'  ); ?>>Gear</option>
			<option value="magnifier" <?php  selected( $instance['icon'], 'magnifier'  ); ?>>Magnifier</option>
			<option value="rss" <?php  selected( $instance['icon'], 'rss'  ); ?>>RSS</option>
			<option value="star" <?php  selected( $instance['icon'], 'star' ); ?>>Star</option>
			<option value="support" <?php  selected( $instance['icon'], 'support' ); ?>>Support</option>
			<option value="question" <?php selected( $instance['icon'], 'question'  ); ?>>Question Mark</option>
			<option value="tools" <?php  selected( $instance['icon'], 'tools' ); ?>>Tools</option>
			<option value="twitter" <?php  selected( $instance['icon'], 'twitter' ); ?>>Twitter</option>
			<option value="weather" <?php  selected( $instance['icon'], 'weather' ); ?>>Weather</option>
			<option value="world" <?php  selected( $instance['icon'], 'world' ); ?>>World</option>
		</select>
	</p>	
		
	<p>
		<label for="<?php echo $this->get_field_id('a_text'); ?>">About Text</label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('a_text'); ?>" name="<?php echo $this->get_field_name('a_text'); ?>"><?php echo esc_attr($instance['a_text']); ?></textarea>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('a_link'); ?>">Post or Page ID for link</label>
		<input id="<?php echo $this->get_field_id('a_link'); ?>" name="<?php echo $this->get_field_name('a_link'); ?>" type="text" value="<?php echo esc_attr( $instance['a_link'] ); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id('a_anchor'); ?>">Link label</label>
		<input class="widefat" id="<?php echo $this->get_field_id('a_anchor'); ?>" name="<?php echo $this->get_field_name('a_anchor'); ?>" type="text" value="<?php echo esc_attr($instance['a_anchor']); ?>" />
	</p>
<?php
	}
}

/** MK
 * Adds Twitter_mk widget.
 */
class twitter extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'twitter', // Base ID
			'twitter', // Name
			array( 'description' => __( 'Twitter Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );?>
	<!-- <a class="twitter-timeline" href="https://twitter.com/search?q=IndiaSpend" data-widget-id="308948473678544896">Tweets about "IndiaSpend"</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script> 
<a class="twitter-timeline" href="https://twitter.com/IndiaSpend" data-widget-id="309968634992791552">Tweets by @IndiaSpend</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>-->
	<div class='spr_iframe'>

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
			</script>
			</div>
			
			<!-- spr_content div end -->
<?php

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();		

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
	
	
	}

} // class Twitter_mk Widget

/** MK
 * Adds MOST_mk widget.
 */
class most extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'most', // Base ID
			'most', // Name
			array( 'description' => __( 'Most Read/ Most Popular/Most Emailed', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );?>
		<div class="most_section" style="margin-bottom:10px">
			<div>
			<a href="javascript:void(0)" id="most_read" onclick="popularpost(this.id)" >Most Read</a>
			<a href="javascript:void(0)" id="most_popular" onclick="popularpost(this.id)" >Most Popular</a>
			<a href="javascript:void(0)" id="most_mail" onclick="popularpost(this.id)" >Most Mailed</a>
			</div>
				
			<div id="most_read1">
			<?php
			echo '<ul class="entries">';
			$posts = wmp_get_popular( array( 'limit' => 7, 'post_type' => 'post', 'range' => 'monthly' ) );
			global $post;
			if ( count( $posts ) > 0 ): foreach ( $posts as $post ):
				setup_postdata( $post );
				?>
				<li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>" style="text-decoration:none;color:#003863"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
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
			$fval=array_slice($value,0,7);
			$fval=array_unique($fval);
			echo '<ul class="entries">';
			for($i=0;$i<=6;$i++){
			$sid[$i]=mysql_query("Select post_id from wp_postmeta where meta_value='".$fval[$i]."' and meta_key='_msp_total_shares' ");
			while($sharedid=mysql_fetch_assoc($sid[$i])){
				?>
			<li><a href="<?php echo get_permalink( $sharedid['post_id'] );?>" style="text-decoration:none;color:#003863"><?php echo get_the_title($sharedid['post_id']);?></a></li>
			<?php }
			}
			echo "</ul>";
			}
			?>
			</div>

			<div id="most_mail1" style="display:none;">
			<?php $email=mysql_query("SELECT email_posttitle,email_postid,COUNT(email_postid) as count FROM wp_email GROUP BY email_postid ORDER BY count DESC limit 7");
			echo '<ul class="entries">';
			while($res_email=mysql_fetch_assoc($email)){ ?>
			<li><a href="<?php echo get_permalink($res_email['email_postid']); ?>" style="text-decoration:none;color:#003863"><?php echo $res_email['email_posttitle']; ?></a></li>				
			<?php }
			echo "</ul>";
			?>
			</div>

			</div>
<?php

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();		

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
	
	
	}

} // class most_mk Widget

/*
 * TEXT 2 WIDGET
 */
class text2_widget extends WP_Widget {
	function text2_widget() {
		$widget_ops = array( 'classname' => 'text2_widget', 'description' => 'Display widget with a big icon' );
		$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'text2_widget' );
		$this->WP_Widget( 'text2_widget', 'Gabfire Widget : Text 2', $widget_ops, $control_ops);	
	}
	
	function widget($args, $instance) {
		extract( $args );
		$title	= $instance['title'];
		$icon	= $instance['icon'];
		$text	= $instance['a_text'];
		$link 	= $instance['a_link'];
		$anchor	= $instance['a_anchor'];

		echo $before_widget;
			echo '<div style="background: url(' . get_template_directory_uri() . '/framework/images/40x/' . $icon . '.png) no-repeat left 7px;padding:3px 0 3px 50px;">';
				if ( $title ) {
					echo $before_title . $title . $after_title;
				}
							
				echo '<p>' . nl2br($text) . '</p>';
					
				if($link) {
					echo '<span class="about_more"><a href="' . get_permalink($link) . '">'. $anchor . '</a></span>';
				}
			echo '</div>';
		echo $after_widget; 
	}
	
	function update($new_instance, $old_instance) {  
		$instance['title']		= strip_tags($new_instance['title']);
		$instance['icon']		= strip_tags($new_instance['icon']);
		$instance['a_text'] 	= strip_tags($new_instance['a_text']);
		$instance['a_link'] 	= strip_tags($new_instance['a_link']);
		$instance['a_anchor'] 	= strip_tags($new_instance['a_anchor']); 
		return $new_instance;
	}
 
	function form($instance) {
		$defaults	= array( 'title' => 'About', 'icon' => 'world', 'a_text' => '', 'a_link' => '', 'a_anchor' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id( 'icon' ); ?>">Icon</label> 
		<select id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>">
			<option value="appearance" <?php selected( $instance['icon'],'appearance' ); ?>>Appearance</option>
			<option value="arrow-right" <?php  selected( $instance['icon'], 'arrow-right' ); ?>>Arrow Right</option>
			<option value="checkmark" <?php  selected( $instance['icon'], 'checkmark' ); ?>>Checkmark</option>
			<option value="comment" <?php  selected( $instance['icon'], 'comment' ); ?>>Comment</option>
			<option value="contact" <?php  selected( $instance['icon'], 'contact' ); ?>>Contact</option>
			<option value="download" <?php  selected( $instance['icon'], 'download' ); ?>>Download</option>
			<option value="home" <?php  selected( $instance['icon'], 'home' ); ?>>Home</option>
			<option value="info" <?php  selected( $instance['icon'], 'info' ); ?>>Info</option>
			<option value="galleries" <?php  selected( $instance['icon'], 'galleries' ); ?>>Galleries</option>
			<option value="gear" <?php  selected( $instance['icon'], 'gear' ); ?>>Gear</option>
			<option value="magnifier" <?php  selected( $instance['icon'], 'magnifier' ); ?>>Magnifier</option>
			<option value="rss" <?php  selected( $instance['icon'],'rss' ); ?>>RSS</option>
			<option value="star" <?php  selected( $instance['icon'], 'star' ); ?>>Star</option>
			<option value="support" <?php  selected( $instance['icon'], 'support' ); ?>>Support</option>
			<option value="question" <?php selected( $instance['icon'], 'question' ); ?>>Question Mark</option>
			<option value="trash" <?php  selected( $instance['icon'], 'trash' ); ?>>Trash</option>
			<option value="tools" <?php  selected( $instance['icon'], 'tools' ); ?>>Tools</option>
			<option value="twitter" <?php  selected( $instance['icon'], 'twitter' ); ?>>Twitter</option>
			<option value="weather" <?php  selected( $instance['icon'], 'weather' ); ?>>Weather</option>
			<option value="world" <?php  selected( $instance['icon'], 'world' ); ?>>World</option>
		</select>
	</p>	
		
	<p>
		<label for="<?php echo $this->get_field_id('a_text'); ?>">About Text</label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('a_text'); ?>" name="<?php echo $this->get_field_name('a_text'); ?>"><?php echo esc_attr($instance['a_text']); ?></textarea>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('a_link'); ?>">Post or Page ID for link</label>
		<input id="<?php echo $this->get_field_id('a_link'); ?>" name="<?php echo $this->get_field_name('a_link'); ?>" type="text" value="<?php echo esc_attr( $instance['a_link'] ); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id('a_anchor'); ?>">Link label</label>
		<input class="widefat" id="<?php echo $this->get_field_id('a_anchor'); ?>" name="<?php echo $this->get_field_name('a_anchor'); ?>" type="text" value="<?php echo esc_attr($instance['a_anchor']); ?>" />
	</p>
<?php
	}
}

class widget_flickr extends WP_Widget {

	function widget_flickr() {
		$widget_ops = array( 'classname' => 'flickr_widget', 'description' => 'Display flickr photos on your site' );
		$control_ops = array( 'width' => 330, 'height' => 350, 'id_base' => 'flickr-widget' );
		$this->WP_Widget( 'flickr-widget', 'Gabfire Widget : Flickr', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title 			= apply_filters('widget_title', $instance['title'] );
		$photo_source 	= $instance['photo_source'];
		$flickr_id 		= $instance['flickr_id'];
		$flickr_tag 	= $instance['flickr_tag'];
		$display 		= $instance['display'];
		$size 			= $instance['size'];
		$photo_number 	= $instance['photo_number'];

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			echo '
				<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count='; 
				if ( $photo_number ) {
					printf( '%1$s', esc_attr( $photo_number ) ); echo '&amp;display=';
				}
				if ( $display )  {
					printf( '%1$s', esc_attr( $display ) ); echo '&amp;layout=x&amp;';
				}
				
				if ( $instance['photo_source'] == 'user' ) { 
					printf( 'source=user&amp;user=%1$s', esc_attr( $flickr_id ) );
				}
				elseif ( $instance['photo_source'] == 'group' ) {
					printf( 'source=group&amp;group=%1$s', esc_attr( $flickr_id ) );
				}
				if  ( $instance['photo_source'] == 'all_tag' ) {
					printf( 'source=all_tag&amp;tag=%1$s', esc_attr( $flickr_tag ) ); 
				}

				echo '&amp;size=';

				if ( $size )  {
					printf( '%1$s', esc_attr( $size ) ); echo '"></script>';
				}
				
			echo '<div class="clear"></div>';
			
		echo $after_widget; 
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['photo_source'] 	= $new_instance['photo_source'];
		$instance['flickr_id'] 		= strip_tags( $new_instance['flickr_id'] );
		$instance['flickr_tag'] 	= strip_tags( $new_instance['flickr_tag'] );
		$instance['group_id'] 		= strip_tags( $new_instance['group_id'] );
		$instance['display'] 		= $new_instance['display'];
		$instance['size'] 			= $new_instance['size'];
		$instance['photo_number'] 	= (int)$new_instance['photo_number'];

		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => 'Flickr Photo Stream', 'flickr_id' => '', 'photo_source' => 'all_tag', 'display' => 'latest', 'photo_number' => '6', 'size' => 's', 'flickr_tag' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		$items  = (int) $items;
		if ( $items < 1 || 10 < $items )
		$items  = 10;
		?>
		
		<div class="controlpanel">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'photo_source' ); ?>">Image Source</label> 
				<select id="<?php echo $this->get_field_id( 'photo_source' ); ?>" name="<?php echo $this->get_field_name( 'photo_source' ); ?>">
					<option value="user" <?php if ( 'user' == $instance['photo_source'] ) echo 'selected="selected"'; ?>>User</option>
					<option value="group" <?php if ( 'group' == $instance['photo_source'] ) echo 'selected="selected"'; ?>>Group</option>
					<option value="all_tag" <?php  if ( 'all_tag' == $instance['photo_source'] ) echo 'selected="selected"'; ?>>All Users Photos (based on tags)</option>			
				</select>
			</p>
			
			<div rel="flickr_id">
				<p>
					<label for="<?php echo $this->get_field_id( 'flickr_id' ); ?>">User or Group ID <a href="http://idgettr.com/">Get your Flickr ID</a></label>
					<input id="<?php echo $this->get_field_id( 'flickr_id' ); ?>" name="<?php echo $this->get_field_name( 'flickr_id' ); ?>" value="<?php echo esc_attr( $instance['flickr_id'] ); ?>" class="widefat" />
				</p>
			</div>
			
			<div rel="flickr_tag">
				<p>
					<label for="<?php echo $this->get_field_id( 'flickr_tag' ); ?>">Tags (separate with comma) (only if "All Users Photos" selected)</label>
					<input id="<?php echo $this->get_field_id( 'flickr_tag' ); ?>" name="<?php echo $this->get_field_name( 'flickr_tag' ); ?>" value="<?php echo esc_attr( $instance['flickr_tag'] ); ?>" class="widefat" />
				</p>
			</div>

			<p>
				<label for="<?php echo $this->get_field_id( 'display' ); ?>">Display Latest or Random Photos</label> 
				<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
					<option value="latest" <?php selected( $instance['display'], 'latest' ); ?>>Latest</option>
					<option value="random" <?php selected( $instance['display'], 'random' ); ?>>Random</option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_name( 'photo_number' ); ?>">How many items would you like to display?</label>
				<select id="<?php echo $this->get_field_id( 'photo_number' ); ?>" name="<?php echo $this->get_field_name( 'photo_number' ); ?>">			
				<?php
					for ( $i = 1; $i <= 10; ++$i )
					echo "<option value='$i' " . selected( $instance['photo_number'], $i, false ) . ">$i</option>";
				?>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'size' ); ?>">Photo Size</label> 
				<select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
					<option value="s" <?php selected( $instance['size'], 's' ); ?>>Small</option>
					<option value="t" <?php selected( $instance['size'], 't' ); ?>>Thumbnail</option>
					<option value="m" <?php  selected( $instance['size'], 'm' ); ?>>Medium</option>
				</select>
			</p>
		</div>
		
	<?php
	}
}

/*
 * FEEDBURNER WIDGET
 */
class feedburner_widget extends WP_Widget {

	function feedburner_widget() {
		$widget_ops = array( 'classname' => 'feedburner_widget', 'description' => 'Feedburner Email Subscribe');
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'feedburner_widget' );
		$this->WP_Widget( 'feedburner_widget', 'Gabfire Widget : Subscribe', $widget_ops, $control_ops);
	}	
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$user	= $instance['user'];
		$text	= $instance['text'];
		$bgcol	= $instance['bgcol'];
		$bordercol	= $instance['bordercol'];
		$textcol	= $instance['textcol'];

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			?>
			
			<form class="feedburner_widget" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo esc_attr( $user ); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
				<fieldset <?php if ( $bgcol ) { echo 'style="background:' . esc_attr( $bgcol ) .';border:1px solid ' . esc_attr( $bordercol ) .';"'; } ?>>
					<input type="text" style="width:80%;color:<?php echo esc_attr( $textcol ); ?>;<?php if ( $bgcol ) { echo 'background:' . esc_attr( $bgcol ); } ?>" class="text" name="email" value="<?php echo esc_attr( $text ); ?>" onfocus="if (this.value == '<?php echo esc_attr( $text ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo esc_attr( $text ); ?>';}" />
					<input type="hidden" value="<?php echo esc_attr( $user ); ?>" name="uri" />
					<input type="hidden" name="loc" value="<?php bloginfo('language'); ?>"/>
					<input type="image" class="feedburner_submit" src="<?php echo get_template_directory_uri(); ?>/framework/images/add.png" alt="Subscribe" />
				</fieldset>
			</form>
			<?php 
		echo $after_widget; 
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['user'] = strip_tags($new_instance['user']);
		$instance['text'] = strip_tags($new_instance['text']);
		$instance['bgcol'] = strip_tags($new_instance['bgcol']);
		$instance['bordercol'] = strip_tags($new_instance['bordercol']);
		$instance['textcol'] = strip_tags($new_instance['textcol']);
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => 'Subscribe by Email', 'user' => '', 'text' => 'Enter your email','bordercol' => '#cccccc','bgcol' => '#efefef','textcol' => '#555555' );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
			<input class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('user'); ?>">Feedburner ID</label>
			<input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr($instance['user']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('bgcol'); ?>">Input field background color</label>
			<input class="widefat" id="<?php echo $this->get_field_id('bgcol'); ?>" name="<?php echo $this->get_field_name('bgcol'); ?>" type="text" value="<?php echo esc_attr($instance['bgcol']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('bordercol'); ?>">Input field border color</label>
			<input class="widefat" id="<?php echo $this->get_field_id('bordercol'); ?>" name="<?php echo $this->get_field_name('bordercol'); ?>" type="text" value="<?php echo esc_attr($instance['bordercol']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('textcol'); ?>">Input field text color</label>
			<input class="widefat" id="<?php echo $this->get_field_id('textcol'); ?>" name="<?php echo $this->get_field_name('textcol'); ?>" type="text" value="<?php echo esc_attr($instance['textcol']); ?>" />
		</p>
			
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">Text</label>
			<input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo esc_attr($instance['text']); ?>" />
		</p>
		
	<?php
	}
}

/*
 * TWITTER WIDGET
 */

class tweet_widget extends WP_Widget {
 
	function tweet_widget() {
		$widget_ops = array( 'classname' => 'tweet_widget', 'description' => 'Display Latest Tweets' );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'tweet_widget' );
		$this->WP_Widget( 'tweet_widget', 'Gabfire Widget : Latest Tweets', $widget_ops, $control_ops);
	}
 
	function widget($args, $instance) {	  
		extract( $args );
		$title	= $instance['title'];
		$user	= $instance['user'];
		$link	= $instance['t_link'] ? '1' : '0';
		$anchor	= $instance['t_anchor'];
		$number = $instance['t_nr'];

		echo $before_widget;
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			?>
			
			<div id="twitter_div">
				<ul id="twitter_update_list" class="twitter-list"><li></li></ul>
			</div>
			<script src="http://twitter.com/javascripts/blogger.js" type="text/javascript"></script>
			<script type="text/javascript" src="http://api.twitter.com/1/statuses/user_timeline.json?screen_name=<?php echo esc_attr( $user ); ?>&include_rts=1&callback=twitterCallback2&count=<?php echo esc_attr( $number ); ?>"></script>				

			<?php 
			if($link) {
				echo '<span class="twitter_link"><a href="http://twitter.com/'. esc_attr( $user ) . '">' . esc_attr( $anchor ) . '</a></span>';
			}
		echo $after_widget; 
	}

	function update($new_instance, $old_instance) {
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['user'] 		= strip_tags($new_instance['user']);
		$instance['t_link'] 	= $new_instance['t_link'] ? '1' : '0';
		$instance['t_anchor']	= strip_tags($new_instance['t_anchor']);
		$instance['t_nr'] 		= (int) $new_instance['t_nr'];  
		return $new_instance;
	}
 
	function form($instance) {
		$defaults = array( 'title' => 'Latest Tweets', 'user' => '', 't_link' => '0', 't_anchor' => '', 't_nr' => '5' );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('user'); ?>">User</label>
			<input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr($instance['user']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_name( 't_nr' ); ?>">How many tweets you like to display?</label>
			<select id="<?php echo $this->get_field_id( 't_nr' ); ?>" name="<?php echo $this->get_field_name( 't_nr' ); ?>">			
			<?php
				for ( $i = 1; $i <= 15; ++$i )
				echo "<option value='$i' " . selected( $instance['t_nr'], $i, false ) . ">$i</option>";
			?>
			</select>
		</p>		
		
		<p>
			<label for="<?php echo $this->get_field_id( 't_link' ); ?>">Show link to Twitter</label> 
			<select id="<?php echo $this->get_field_id( 't_link' ); ?>" name="<?php echo $this->get_field_name( 't_link' ); ?>">
				<option value="1" <?php selected( $instance['t_link'], '1' ); ?>>Enable</option>
				<option value="0" <?php selected( $instance['t_link'], '0' ); ?>>Disable</option>	
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('t_anchor'); ?>">Link label:
			<input class="widefat" id="<?php echo $this->get_field_id('t_anchor'); ?>" name="<?php echo $this->get_field_name('t_anchor'); ?>" type="text" value="<?php echo esc_attr($instance['t_anchor']); ?>" />
			</label>
		</p>
		
<?php
	}
}


/*
 * SEARCH WIDGET
 */

class search_widget extends WP_Widget {
 
	function search_widget() {
		$widget_ops = array( 'classname' => 'search_widget', 'description' => 'Display Search Form' );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'search_widget' );
		$this->WP_Widget( 'search_widget', 'Gabfire Widget : Search', $widget_ops, $control_ops);
	}
 
	function widget($args, $instance) {	  
		extract( $args );
		$title	= $instance['title'];
		$label	= $instance['label'];
		$s_style	= $instance['s_style'] ? '1' : '0';
		$bgcol	= $instance['bgcol'];
		$bordercol	= $instance['bordercol'];

		echo $before_widget;
			
				if ( $title ) {
					echo $before_title . $title . $after_title;
				}
			
				if($s_style == 1) {
				
				?>
					<form class="gab_search_style1" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<fieldset style="<?php if ( $bgcol ) { echo 'background:' . esc_html( $bgcol ); echo ';';} if ( $bordercol ) { echo 'border:1px solid ' . esc_html( $bordercol ); echo ';';} ?>">
							<input type="text" style="width:80%;<?php if ( $bgcol ) { echo 'background:' . esc_attr( $bgcol ); } ?>" class="text" name="s" value="<?php echo esc_attr( $label ); ?>" onfocus="if (this.value == '<?php echo esc_attr( $label ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo esc_attr( $label ); ?>';}" />
							<input type="image" class="submit_style1" src="<?php echo get_template_directory_uri(); ?>/framework/images/search.png" alt="<?php echo esc_attr( $label ); ?>" />
							<div class="clearfix"></div>
						</fieldset>
					</form>				
				<?php } else { ?>
					<form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="gab_search_style2" style="background:url(<?php echo get_template_directory_uri(); ?>/framework/images/bgr_search_box.png) no-repeat;">
						<fieldset>
							<p><input type="image" class="submit_style2" src="<?php echo get_template_directory_uri(); ?>/framework/images/bgr_search_box-submit.png" alt="<?php echo esc_attr( $label ); ?>" /></p>
							<p><input type="text" class="text" name="s" value="<?php echo esc_attr( $label ); ?>" onfocus="if (this.value == '<?php echo esc_attr( $label ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo esc_attr( $label ); ?>';}" /></p>
						</fieldset>
					</form>
				<?php 
				}
		echo $after_widget; 
	}

	function update($new_instance, $old_instance) {
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['label'] 		= strip_tags($new_instance['label']);
		$instance['s_style']	= $new_instance['s_style'] ? '1' : '0';
		$instance['bgcol'] = strip_tags($new_instance['bgcol']);
		$instance['bordercol'] = strip_tags($new_instance['bordercol']);
		return $new_instance;
	}
 
	function form($instance) {
		$defaults = array( 'title' => 'Search in Site', 'label' => 'Search...', 'bgcol' => '#efefef','bordercol' => '#eee' );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('label'); ?>">Search Label</label>
			<input class="widefat" id="<?php echo $this->get_field_id('label'); ?>" name="<?php echo $this->get_field_name('label'); ?>" type="text" value="<?php echo esc_attr($instance['label']); ?>" />
		</p>
		
		<p>
			<select id="<?php echo $this->get_field_id( 's_style' ); ?>" name="<?php echo $this->get_field_name( 's_style' ); ?>">
				<option value="1" <?php if ( '1' == $instance['s_style'] ) echo 'selected="selected"'; ?>>Search Style 1</option>
				<option value="0" <?php if ( '0' == $instance['s_style'] ) echo 'selected="selected"'; ?>>Search Style 2</option>	
			</select>
		</p>
		<p><small>Search style 2 requires 300px width to display correct</small></p>
		<p>
			<label for="<?php echo $this->get_field_id('bgcol'); ?>">Background color for input field</label>
			<input class="widefat" id="<?php echo $this->get_field_id('bgcol'); ?>" name="<?php echo $this->get_field_name('bgcol'); ?>" type="text" value="<?php echo esc_attr($instance['bgcol']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('bordercol'); ?>">Border color for input field</label>
			<input class="widefat" id="<?php echo $this->get_field_id('bordercol'); ?>" name="<?php echo $this->get_field_name('bordercol'); ?>" type="text" value="<?php echo esc_attr($instance['bordercol']); ?>" />
		</p>

<?php
	}
}


/*
 * ABOUT WIDGET
 */
class about_widget extends WP_Widget {
	function about_widget() {
		$widget_ops = array( 'classname' => 'about_widget', 'description' => 'Display an "about" widget' );
		$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'about_widget' );
		$this->WP_Widget( 'about_widget', 'Gabfire Widget : About', $widget_ops, $control_ops);	
	}
	
	function widget($args, $instance) {
		extract( $args );
		$title	= $instance['title'];
		$avatar	= $instance['a_avatar'] ? '1' : '0';
		$text	= $instance['a_text'];
		$link 	= $instance['a_link'];
		$anchor	= $instance['a_anchor'];

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			
			if($avatar) {
				echo '<div class="widget_avatar">' . get_avatar(get_bloginfo('admin_email'),'50') . '</div>';
			}	
			
			echo '<p>' . nl2br($text) . '</p><div class="clear"></div>';
				
			if($link) {
				echo '<span class="about_more"><a href="' . get_permalink($link) . '">'. $anchor . '</a></span>';
			}
			
		echo $after_widget; 
	}
	
	function update($new_instance, $old_instance) {  
		$instance['title']		= strip_tags($new_instance['title']);
		$instance['a_avatar']	= $new_instance['a_avatar'] ? '1' : '0';
		$instance['a_text'] 	= strip_tags($new_instance['a_text']);
		$instance['a_link'] 	= strip_tags($new_instance['a_link']);
		$instance['a_anchor'] 	= strip_tags($new_instance['a_anchor']); 
		return $new_instance;
	}
 
	function form($instance) {
		$defaults	= array( 'title' => 'About', 'a_avatar' => '1', 'a_text' => '', 'a_link' => '', 'a_anchor' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id( 'a_avatar' ); ?>">Site Admin's Avatar</label> 
		<select id="<?php echo $this->get_field_id( 'a_avatar' ); ?>" name="<?php echo $this->get_field_name( 'a_avatar' ); ?>">
			<option value="1" <?php selected( $instance['a_avatar'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['a_avatar'], '0' ) ; ?>>Disable</option>	
		</select>
	</p>	
		
	<p>
		<label for="<?php echo $this->get_field_id('a_text'); ?>">About Text</label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('a_text'); ?>" name="<?php echo $this->get_field_name('a_text'); ?>"><?php echo esc_attr($instance['a_text']); ?></textarea>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('a_link'); ?>">Post or Page ID for link</label>
		<input id="<?php echo $this->get_field_id('a_link'); ?>" name="<?php echo $this->get_field_name('a_link'); ?>" type="text" value="<?php echo esc_attr( $instance['a_link'] ); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id('a_anchor'); ?>">Link label</label>
		<input class="widefat" id="<?php echo $this->get_field_id('a_anchor'); ?>" name="<?php echo $this->get_field_name('a_anchor'); ?>" type="text" value="<?php echo esc_attr($instance['a_anchor']); ?>" />
	</p>
<?php
	}
}

/*
 * SOCIALIZE WIDGET
 */
class gab_social_widget extends WP_Widget {
	function gab_social_widget() {
		$widget_ops = array( 'classname' => 'gab_social_widget', 'description' => 'Display social icons on your site' );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gab_social_widget' );
		$this->WP_Widget( 'gab_social_widget', 'Gabfire Widget : Social', $widget_ops, $control_ops);	
	}
	function widget($args, $instance) {
		extract( $args );
		$title	= $instance['title'];
		$fbook_l	= $instance['fbook_l'];
		$tweet_l	= $instance['tweet_l'];
		$feed_l 	= $instance['feed_l'];
		$mspace_l	= $instance['mspace_l'];
		$picasa_l	= $instance['picasa_l'];
		$flickr_l	= $instance['flickr_l'];
		$lastfm_l	= $instance['lastfm_l'];
		$linkin_l	= $instance['linkin_l'];
		$plus1_l	= $instance['plus1_l'];
		$ytube_l	= $instance['ytube_l'];
		$vimeo_l	= $instance['vimeo_l'];
		$digg_l	= $instance['digg_l'];
		$stumb_l	= $instance['stumb_l'];
		$devia_l	= $instance['devia_l'];
		$delic_l	= $instance['delic_l'];
		$fsq_l	= $instance['fsq_l'];

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;	
			}
			
			if($fbook_l) { 
				echo '<a target="_blank" class="facebook" href="' . $fbook_l . '" rel="nofollow">Facebook</a>';
			}
			if($tweet_l) {
				echo '<a target="_blank" class="twitter" href="' . $tweet_l . '" rel="nofollow">Twitter</a>';
			}
			if($feed_l) {
				echo '<a target="_blank" class="feed" href="' . $feed_l . '" rel="nofollow">RSS Feed</a>';
			}
			if($mspace_l) {
				echo '<a target="_blank" class="myspace" href="' . $mspace_l . '" rel="nofollow">Myspace</a>';
			}
			if($plus1_l) {
				echo '<a target="_blank" class="plus1" href="' . $plus1_l . '" rel="nofollow">Google +1</a>';
			}
			if($picasa_l) {
				echo '<a target="_blank" class="picasa" href="' . $picasa_l . '" rel="nofollow">Picasa</a>';
			}
			if($flickr_l) {
				echo '<a target="_blank" class="flickr" href="' . $flickr_l . '" rel="nofollow">Flickr</a>';
			}
			if($lastfm_l) {
				echo '<a target="_blank" class="lastfm" href="' . $lastfm_l . '" rel="nofollow">LastFM</a>';
			}
			if($linkin_l) {
				echo '<a target="_blank" class="linkedin" href="' . $linkin_l . '" rel="nofollow">LinkedIn</a>';
			}
			if($ytube_l) {
				echo '<a target="_blank" class="youtube" href="' . $ytube_l . '" rel="nofollow">Youtube</a>';
			}
			if($vimeo_l) {
				echo '<a target="_blank" class="vimeo" href="' . $vimeo_l . '" rel="nofollow">Vimeo</a>';
			}
			if($delic_l) {
				echo '<a target="_blank" class="delicious" href="' . $delic_l . '" rel="nofollow">Delicious</a>';
			}
			if($stumb_l) {
				echo '<a target="_blank" class="stumbleupon" href="' . $stumb_l . '" rel="nofollow">Stumbleupon</a>';
			}
			if($devia_l) {
				echo '<a target="_blank" class="deviantart" href="' . $devia_l . '" rel="nofollow">Deviantart</a>';
			}
			if($digg_l) {
				echo '<a target="_blank" class="digg" href="' . $digg_l . '" rel="nofollow">Digg</a>';
			}
			if($fsq_l) {
				echo '<a target="_blank" class="foursquare" href="' . $fsq_l . '" rel="nofollow">FourSquare</a>';
			}

			echo '<div class="clear"></div>';
			
		echo $after_widget; 
	}
	
	function update($new_instance, $old_instance) { 
		$instance['title']	= strip_tags($new_instance['title']);
		$instance['fbook_l'] = strip_tags($new_instance['fbook_l']);
		$instance['tweet_l'] = strip_tags($new_instance['tweet_l']);
		$instance['feed_l']  = strip_tags($new_instance['feed_l']);
		$instance['mspace_l'] = strip_tags($new_instance['mspace_l']);
		$instance['plus1_l'] = strip_tags($new_instance['plus1_l']);
		$instance['picasa_l'] = strip_tags($new_instance['picasa_l']);
		$instance['flickr_l'] = strip_tags($new_instance['flickr_l']);
		$instance['lastfm_l'] = strip_tags($new_instance['lastfm_l']);
		$instance['linkin_l'] = strip_tags($new_instance['linkin_l']);
		$instance['ytube_l'] = strip_tags($new_instance['ytube_l']);
		$instance['vimeo_l'] = strip_tags($new_instance['vimeo_l']);
		$instance['digg_l'] = strip_tags($new_instance['digg_l']);
		$instance['stumb_l'] = strip_tags($new_instance['stumb_l']);
		$instance['devia_l'] = strip_tags($new_instance['devia_l']);
		$instance['delic_l'] = strip_tags($new_instance['delic_l']);
		$instance['fsq_l'] = strip_tags($new_instance['fsq_l']);
		return $new_instance;
	}
 
	function form($instance) {
		$defaults	= array( 'title' => 'Socialize');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>To disable a social icon, leave the link field empty below</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('fbook_l'); ?>">Link to Facebook account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('fbook_l'); ?>" name="<?php echo $this->get_field_name('fbook_l'); ?>" type="text" value="<?php echo esc_attr($instance['fbook_l']); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tweet_l'); ?>">Link to Twitter account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('tweet_l'); ?>" name="<?php echo $this->get_field_name('tweet_l'); ?>" type="text" value="<?php echo esc_attr($instance['tweet_l']); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('feed_l'); ?>">RSS Feed Link</label>
		<input class="widefat" id="<?php echo $this->get_field_id('feed_l'); ?>" name="<?php echo $this->get_field_name('feed_l'); ?>" type="text" value="<?php echo esc_attr($instance['feed_l']); ?>" />
	</p>
				
	<p>
		<label for="<?php echo $this->get_field_id('mspace_l'); ?>">Link to Myspace account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('mspace_l'); ?>" name="<?php echo $this->get_field_name('mspace_l'); ?>" type="text" value="<?php echo esc_attr($instance['mspace_l']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('plus1_l'); ?>">Link to Google +1 account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('plus1_l'); ?>" name="<?php echo $this->get_field_name('plus1_l'); ?>" type="text" value="<?php echo esc_attr($instance['plus1_l']); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id('picasa_l'); ?>">Link to Picasa account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('picasa_l'); ?>" name="<?php echo $this->get_field_name('picasa_l'); ?>" type="text" value="<?php echo esc_attr($instance['picasa_l']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('flickr_l'); ?>">Link to Flickr account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('flickr_l'); ?>" name="<?php echo $this->get_field_name('flickr_l'); ?>" type="text" value="<?php echo esc_attr($instance['flickr_l']); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id('lastfm_l'); ?>">Link to Last.Fm account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('lastfm_l'); ?>" name="<?php echo $this->get_field_name('lastfm_l'); ?>" type="text" value="<?php echo esc_attr($instance['lastfm_l']); ?>" />
	</p>	
		
	<p>
		<label for="<?php echo $this->get_field_id('linkin_l'); ?>">Link to LinkedIn account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('linkin_l'); ?>" name="<?php echo $this->get_field_name('linkin_l'); ?>" type="text" value="<?php echo esc_attr($instance['linkin_l']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('ytube_l'); ?>">Link to Youtube account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('ytube_l'); ?>" name="<?php echo $this->get_field_name('ytube_l'); ?>" type="text" value="<?php echo esc_attr($instance['ytube_l']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('vimeo_l'); ?>">Link to Vimeo account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('vimeo_l'); ?>" name="<?php echo $this->get_field_name('vimeo_l'); ?>" type="text" value="<?php echo esc_attr($instance['vimeo_l']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('digg_l'); ?>">Link to Digg account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('digg_l'); ?>" name="<?php echo $this->get_field_name('digg_l'); ?>" type="text" value="<?php echo esc_attr($instance['digg_l']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('stumb_l'); ?>">Link to Stumble Upon account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('stumb_l'); ?>" name="<?php echo $this->get_field_name('stumb_l'); ?>" type="text" value="<?php echo esc_attr($instance['stumb_l']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('devia_l'); ?>">Link to Deviantart account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('devia_l'); ?>" name="<?php echo $this->get_field_name('devia_l'); ?>" type="text" value="<?php echo esc_attr($instance['devia_l']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('delic_l'); ?>">Link to Delicious account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('delic_l'); ?>" name="<?php echo $this->get_field_name('delic_l'); ?>" type="text" value="<?php echo esc_attr($instance['delic_l']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('fsq_l'); ?>">Link to FourSquare account</label>
		<input class="widefat" id="<?php echo $this->get_field_id('fsq_l'); ?>" name="<?php echo $this->get_field_name('fsq_l'); ?>" type="text" value="<?php echo esc_attr($instance['fsq_l']); ?>" />
	</p>
	
<?php
	}
}

/*
 * ARCHIVE WIDGET
 */
class archive_widget extends WP_Widget {

	function archive_widget() {
		$widget_ops = array( 'classname' => 'archive_widget', 'description' => 'Search in Archive');
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'archive_widget' );
		$this->WP_Widget( 'archive_widget', 'Gabfire Widget : Archive', $widget_ops, $control_ops);
	}	
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$date	= $instance['date'];
		$cat	= $instance['cat'];
		$month	= $instance['month'];
		$google	= $instance['google'];
		$google_df	= $instance['google_df'];
		$bgcol	= $instance['bgcol'];
		
		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			?>
				<div id="gab_archive_wrapper" style="background:<?php echo esc_attr( $bgcol ); ?>">
					<span class="archive_span"><?php echo esc_attr( $date ); ?></span>
					<form class="arc-dropdown" action="<?php echo esc_url( home_url( '/' ) ); ?>"  method="get" > 		
						<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'> 
						<option value=""><?php echo esc_attr( $month ); ?></option> 
						<?php wp_get_archives('type=monthly&format=option&nwsp_post_count=1'); ?> </select>
					</form>
					
					<span class="archive_span"><?php echo esc_attr( $cat ); ?></span>
					<form class="arc-dropdown" action="<?php echo esc_url( home_url( '/' ) ); ?>"  method="get" > 
						<?php wp_dropdown_categories('orderby=Name&hierarchical=1&nwsp_count=1'); ?>
					</form>
					
					<script type="text/javascript"><!--
						var dropdown = document.getElementById("cat");
						function onCatChange() {
							if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
								location.href = "<?php echo esc_url(of_get_option('home')); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
							}
						}
						dropdown.onchange = onCatChange;
					--></script>
							
					<span class="archive_span"><?php echo esc_attr( $google ); ?></span>
					<form method="get" action="http://www.google.com/search">
						<input name="q" class="google" value="<?php echo esc_attr( $google_df ); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" /> 
						<input type="hidden" name="sitesearch" value="<?php echo esc_url( home_url( '/' ) ); ?>" />
					</form>		
				</div>
			<?php 		
		echo $after_widget; 
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['date'] = strip_tags($new_instance['date']);
		$instance['month'] = strip_tags($new_instance['month']);
		$instance['cat'] = strip_tags($new_instance['cat']);
		$instance['google'] = strip_tags($new_instance['google']);
		$instance['google_df'] = strip_tags($new_instance['google_df']);
		$instance['bgcol'] = strip_tags($new_instance['bgcol']);
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => 'Search in Archive', 'date' => 'Select a date','month' => 'Select month','bgcol' => 'transparent', 'cat' => 'Select a category', 'google_df' => 'Write keyword and hit return', 'google' => 'Search with Google');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
			<input class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('date'); ?>">Date search string</label>
			<input class="widefat" id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" type="text" value="<?php echo esc_attr($instance['date']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('month'); ?>">Month selectbox label</label>
			<input class="widefat" id="<?php echo $this->get_field_id('month'); ?>" name="<?php echo $this->get_field_name('month'); ?>" type="text" value="<?php echo esc_attr($instance['month']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">Category search string</label>
			<input class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo esc_attr($instance['cat']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('google'); ?>">Google search string</label>
			<input class="widefat" id="<?php echo $this->get_field_id('google'); ?>" name="<?php echo $this->get_field_name('google'); ?>" type="text" value="<?php echo esc_attr($instance['google']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('google_df'); ?>">Google field input string</label>
			<input class="widefat" id="<?php echo $this->get_field_id('google_df'); ?>" name="<?php echo $this->get_field_name('google_df'); ?>" type="text" value="<?php echo esc_attr($instance['google_df']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('bgcol'); ?>">Background color (eg #fff or white)</label>
			<input class="widefat" id="<?php echo $this->get_field_id('bgcol'); ?>" name="<?php echo $this->get_field_name('bgcol'); ?>" type="text" value="<?php echo esc_attr($instance['bgcol']); ?>" />
		</p>
		
	<?php
	}
}

/*
 * MOST RECENT WIDGET
 */

class gab_tabs extends WP_Widget {
 
	function gab_tabs() {
		$widget_ops = array( 'classname' => 'gab_tabs', 'description' => 'Display recent entries and comments (ajax tab supported)' );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gab_tabs' );
		$this->WP_Widget( 'gab_tabs', 'Gabfire Widget : Ajax Tabs', $widget_ops, $control_ops);
	}
 
	function widget($args, $instance) {	  
		extract( $args );
		$title	= $instance['title'];
		$post_label	= $instance['post_label'];
		$popular_label	= $instance['popular_label'];
		$postmeta	= $instance['postmeta'];
		$post_nr	= $instance['post_nr'];
		$comment_label	= $instance['comment_label'];
		$post_title	= $instance['post_title'];
		$popular_title	= $instance['popular_title'];
		$comments_title	= $instance['comments_title'];
		$comment_nr	= $instance['comment_nr'];
		$popular_nr	= $instance['popular_nr'];
		$color_sch	= $instance['color_sch'];
		$colorsc	= $instance['colorsc'] ? '1' : '0';
		$postmeta	= $instance['postmeta'] ? '1' : '0';
		$avatar	= $instance['a_avatar'] ? '1' : '0';

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			?>
			<div id="<?php if($colorsc) { echo 'light_cs'; } else { echo 'dark_cs'; } ?>">
				<ul class="tabs">
					<li><a href="#first"><?php echo esc_attr( $post_label ); ?></a></li>
					<li><a href="#third"><?php echo esc_attr( $popular_label ); ?></a></li>
					<li><a href="#second"><?php echo esc_attr( $comment_label ); ?></a></li>
				</ul>
				
				<div class="panes">
					<div>
						<?php if($post_title) { ?>
							<h3 class="widgettitle_in"><?php echo esc_attr($post_title); ?></h3>
						<?php } ?>
						<ul>
							<?php
							$count=1;
							$args = array( 'posts_per_page'=> $post_nr, );						
							$gab_query = new WP_Query();$gab_query->query($args); 
							while ($gab_query->have_posts()) : $gab_query->the_post();
							?>
								<li>
									<?php
									gab_media(array(
										'name' => 'ajaxtabs',
										'imgtag' => 1,
										'link' => 1,
										'enable_video' => 0,
										'catch_image' => 0,
										'enable_thumb' => 1,
										'resize_type' => 'c',
										'media_width' => 30, 
										'media_height' => 30, 
										'enable_default' => 0
									));							
									?>
									<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'source' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="pad"><?php the_title(); ?></a>
									<?php if($postmeta) { ?>
										<span class="block"><?php _e('by','source'); ?> <?php the_author_posts_link(); ?> - <?php comments_popup_link(__('No Comment','source'), __('1 Comment','source'), __('% Comments','source'));?></span>
									<?php } ?>
								</li>
							<?php $count++; endwhile; wp_reset_query(); ?>
						</ul>
					</div>
					
					<div>
						<?php if($popular_title) { ?>
							<h3 class="widgettitle_in"><?php echo esc_attr($popular_title); ?></h3>
						<?php } ?>
						<ul>
							<?php
							$count=1;
							$args = array( 'posts_per_page'=> $comment_nr, 'orderby' => 'comment_count');						
							$gab_query = new WP_Query();$gab_query->query($args); 
							while ($gab_query->have_posts()) : $gab_query->the_post();
							?>
								<li>
									<?php
									gab_media(array(
										'name' => 'ajaxtabs',
										'imgtag' => 1,
										'link' => 1,
										'enable_video' => 0,
										'catch_image' => of_get_option('of_catch_img', 0),
										'enable_thumb' => 1,
										'resize_type' => 'c',
										'media_width' => 30, 
										'media_height' => 30, 
										'enable_default' => 0
									));							
									?>
									<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'source' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="pad"><?php the_title(); ?></a>
									<?php if($postmeta) { ?>
							
										<span class="block"><?php _e('by','source'); ?> <?php the_author_posts_link(); ?> - <?php comments_popup_link(__('No Comment','source'), __('1 Comment','source'), __('% Comments','source'));?></span>
									<?php }?>
								</li>
							<?php $count++; endwhile; wp_reset_query(); ?>
						</ul>
					</div>
					

					<div>
						<?php if($comments_title) { ?>
							<h3 class="widgettitle_in"><?php echo esc_attr($comments_title); ?></h3>
						<?php } ?>
						<ul>
							<?php
								$args = array(
									'status' => 'approve',
									'number' => $comment_nr
								);
								$comments = get_comments($args);
								foreach($comments as $comment) :
									echo '<li>'; 
										if($avatar) {
											echo get_avatar( $comment->comment_author_email, 30 );
										}
										echo ( '<strong>' . $comment->comment_author . '</strong>: <a href="' . get_permalink($comment->comment_post_ID) . '" rel="bookmark">' . get_the_title($comment->comment_post_ID) . '</a>');
									echo '</li>';
								endforeach;
							?>
						</ul>
					</div>
				</div>
			</div>
	
			<?php 
			
		echo $after_widget; 
	}

	function update($new_instance, $old_instance) {
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['post_label'] = strip_tags($new_instance['post_label']);
		$instance['postmeta']	= $new_instance['postmeta'] ? '1' : '0';
		$instance['a_avatar']	= $new_instance['a_avatar'] ? '1' : '0';
		$instance['colorsc']	= $new_instance['colorsc'] ? '1' : '0';
		$instance['post_nr'] = (int) $new_instance['post_nr'];  
		$instance['post_title']	= strip_tags($new_instance['post_title']);
		$instance['popular_title']	= strip_tags($new_instance['popular_title']);
		$instance['comments_title']	= strip_tags($new_instance['comments_title']);	
		$instance['comment_label']	= strip_tags($new_instance['comment_label']);
		$instance['popular_label']	= strip_tags($new_instance['popular_label']);
		$instance['comment_nr'] 	= (int) $new_instance['comment_nr'];  
		$instance['popular_nr'] 	= (int) $new_instance['popular_nr']; 
		return $new_instance;
	}
 
	function form($instance) {
		$defaults = array( 'title' => 'Posts', 'post_label' => 'Latest', 'comment_label' => 'Comments','popular_label' => 'Popular', 'post_nr' => '5', 'comment_nr' => '5','popular_nr' => '5' );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('post_label'); ?>">Recent Posts tab label</label>
			<input class="widefat" id="<?php echo $this->get_field_id('post_label'); ?>" name="<?php echo $this->get_field_name('post_label'); ?>" type="text" value="<?php echo esc_attr($instance['post_label']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('comment_label'); ?>">Comments tab label</label>
			<input class="widefat" id="<?php echo $this->get_field_id('comment_label'); ?>" name="<?php echo $this->get_field_name('comment_label'); ?>" type="text" value="<?php echo esc_attr($instance['comment_label']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('popular_label'); ?>">Popular tab label</label>
			<input class="widefat" id="<?php echo $this->get_field_id('popular_label'); ?>" name="<?php echo $this->get_field_name('popular_label'); ?>" type="text" value="<?php echo esc_attr($instance['popular_label']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('post_title'); ?>">Recent Posts List Title</label>
			<input class="widefat" id="<?php echo $this->get_field_id('post_title'); ?>" name="<?php echo $this->get_field_name('post_title'); ?>" type="text" value="<?php echo esc_attr($instance['post_title']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('popular_title'); ?>">Popular Posts List Title</label>
			<input class="widefat" id="<?php echo $this->get_field_id('popular_title'); ?>" name="<?php echo $this->get_field_name('popular_title'); ?>" type="text" value="<?php echo esc_attr($instance['popular_title']); ?>" />
		</p>		
		
		<p>
			<label for="<?php echo $this->get_field_id('comments_title'); ?>">Comments List Title</label>
			<input class="widefat" id="<?php echo $this->get_field_id('comments_title'); ?>" name="<?php echo $this->get_field_name('comments_title'); ?>" type="text" value="<?php echo esc_attr($instance['comments_title']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'colorsc' ); ?>">Color Scheme</label> 
			<select id="<?php echo $this->get_field_id( 'colorsc' ); ?>" name="<?php echo $this->get_field_name( 'colorsc' ); ?>">
				<option value="1" <?php selected( $instance['colorsc'], '1' ); ?>>Light</option>
				<option value="0" <?php selected( $instance['colorsc'], '0' ); ?>>Dark</option>	
			</select>
		</p>			
		
		<p>
			<label for="<?php echo $this->get_field_id( 'postmeta' ); ?>">Display post meta below title</label> 
			<select id="<?php echo $this->get_field_id( 'postmeta' ); ?>" name="<?php echo $this->get_field_name( 'postmeta' ); ?>">
				<option value="1" <?php selected( $instance['postmeta'] , '1' ); ?>>Enable</option>
				<option value="0" <?php selected( $instance['postmeta'] , '0' ); ?>>Disable</option>	
			</select>
		</p>	
		
		<p>
			<label for="<?php echo $this->get_field_id( 'a_avatar' ); ?>">Display author avatar for comments</label> 
			<select id="<?php echo $this->get_field_id( 'a_avatar' ); ?>" name="<?php echo $this->get_field_name( 'a_avatar' ); ?>">
				<option value="1" <?php selected( $instance['a_avatar'], '1' ); ?>>Enable</option>
				<option value="0" <?php selected( $instance['a_avatar'], '0' ); ?>>Disable</option>	
			</select>
		</p>	
		
		<p>
			<label for="<?php echo $this->get_field_name( 'post_nr' ); ?>">Number of posts</label>
			<select id="<?php echo $this->get_field_id( 'post_nr' ); ?>" name="<?php echo $this->get_field_name( 'post_nr' ); ?>">			
			<?php
				for ( $i = 1; $i <= 15; ++$i )
				echo "<option value='$i' " . selected( $instance['post_nr'], $i, false ) . ">$i</option>";
			?>
			</select>
		</p>	
		
		<p>
			<label for="<?php echo $this->get_field_name( 'comment_nr' ); ?>">Number of Comments</label>
			<select id="<?php echo $this->get_field_id( 'comment_nr' ); ?>" name="<?php echo $this->get_field_name( 'comment_nr' ); ?>">			
			<?php
				for ( $i = 1; $i <= 15; ++$i )
				echo "<option value='$i' " . selected( $instance['comment_nr'], $i, false ) . ">$i</option>";
			?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_name( 'popular_nr' ); ?>">Number of Popular Posts</label>
			<select id="<?php echo $this->get_field_id( 'popular_nr' ); ?>" name="<?php echo $this->get_field_name( 'popular_nr' ); ?>">			
			<?php
				for ( $i = 1; $i <= 15; ++$i )
				echo "<option value='$i' " . selected( $instance['popular_nr'], $i, false ) . ">$i</option>";
			?>
			</select>
		</p>
<?php
	}
}

/*
 * SHARE WIDGET
 */
class gab_share_widget extends WP_Widget {
	function gab_share_widget() {
		$widget_ops = array( 'classname' => 'gab_share_widget', 'description' => 'Display share icons for entries' );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gab_share_widget' );
		$this->WP_Widget( 'gab_share_widget', 'Gabfire Widget : Share Items', $widget_ops, $control_ops);	
	}
	function widget($args, $instance) {
		extract( $args );
		$title	= $instance['title'];
		$boxtweet	= $instance['boxtweet'] ? '1' : '0';
		$boxg1	= $instance['boxg1'] ? '1' : '0';
		$boxlike	= $instance['boxlike'] ? '1' : '0';
		$boxpinterest	= $instance['boxpinterest'] ? '1' : '0';
		$fbook	= $instance['fbook'] ? '1' : '0';
		$tweet	= $instance['tweet'] ? '1' : '0';
		$plus1	= $instance['plus1'] ? '1' : '0';
		$email 	= $instance['email'] ? '1' : '0';
		$blogger	= $instance['blogger'] ? '1' : '0';
		$dlc	= $instance['dlc'] ? '1' : '0';
		$digg	= $instance['digg'] ? '1' : '0';
		$google	= $instance['google'] ? '1' : '0';
		$myspace	= $instance['myspace'] ? '1' : '0';
		$supon	= $instance['supon'] ? '1' : '0';
		$yahoo	= $instance['yahoo'] ? '1' : '0';
		$reddit	= $instance['reddit'] ? '1' : '0';
		$tech	= $instance['tech'] ? '1' : '0';
		$rss	= $instance['rss'] ? '1' : '0';

		echo $before_widget;
			global $post, $page;
			if (has_post_thumbnail( $post->ID )) { $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' ); }
			
			if ( $title ) {
				echo $before_title . $title . $after_title;	
			}
			if(($boxtweet == 1) or ($boxg1 == 1) or ($boxlike == 1) or ($boxpinterest == 1)) {
				echo '<div class="gab_share_buttons">';
			}			
				if($boxg1) {
					add_action("wp_footer", "gab_googleplus");
					echo '<div class="google-share-button"><div class="g-plus" data-action="share" data-annotation="bubble"></div></div>';
				}		
			
				if($boxtweet) {
					add_action("wp_footer", "gab_twitter"); 
					echo '<div class="twitter-share-button"><a href="https://twitter.com/share" class="twitter-share-button" data-url="'.get_permalink().'" data-text="';the_title(); echo '" data-lang="'. substr(get_bloginfo ( 'language' ), 0, 2) .'"></a></div>';
				}
				if($boxlike) {
					echo '<div class="facebook-share-button"><div class="fb-like" data-href="'.get_permalink().'" data-send="false" data-layout="button_count" data-width="80" data-show-faces="true"></div></div>';
				}
				if($boxpinterest) {
					add_action("wp_footer", "gab_pinterest"); 
					echo '<div class="pinterest-share-button"><a href="http://pinterest.com/pin/create/button/?url='. urlencode(get_permalink()) .'&media='. urlencode($image[0]) .'&description='. get_the_excerpt() .'" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="';_e('Pin It','gabfirethemes'); echo'" /></a></div>';
				}
				
			if(($boxtweet == 1) or ($boxg1 == 1) or ($boxlike == 1) or ($boxpinterest == 1)) {
				echo '<div class="clear"></div></div>';
			}
			if($fbook) {
				echo '<a target="_blank" rel="nofollow" class="facebook" href="http://www.facebook.com/share.php?u='.get_permalink().'&t=';the_title(); echo '" title="'; _e('Share on Facebook' , 'source'); echo' ">Facebook</a>';			
			}
			if($tweet) {
				echo '<a target="_blank" rel="nofollow" class="twitter" href="http://twitter.com/home?status='.get_the_title() .' '.get_permalink().'" title="'; _e('Share on Twitter' , 'source'); echo' ">Twitter</a>';
			}
			if($plus1) {
				echo '<a target="_blank" rel="nofollow" class="plus1" href="https://plusone.google.com/_/+1/confirm?hl=en&url='.get_permalink().'" title="'; _e('Share on Google1' , 'source'); echo' ">Google1</a>';
			}	
			if($email) {
				echo '<a target="_blank" rel="nofollow" class="email" href="http://www.freetellafriend.com/tell/?heading=Share+This+Article&bg=1&option=email&url='.get_permalink().'" title="'; _e('Email a Friend' , 'source'); echo' ">Email</a>';
			}
			if($dlc) {
				echo '<a target="_blank" rel="nofollow" class="delicious" href="http://del.icio.us/post?url='.get_permalink().'&title=';the_title(); echo '" title="'; _e('Share on Delicious' , 'source'); echo' ">Delicious</a>';
			}
			if($digg) {
				echo '<a target="_blank" rel="nofollow" class="digg" href="http://digg.com/submit?url='.get_permalink().'&title=';the_title(); echo '" title="'; _e('Share on Digg' , 'source'); echo' ">Digg</a>';
			}
			if($google) {
				echo '<a target="_blank" rel="nofollow" class="google2" href="http://www.google.com/bookmarks/mark?op=add&bkmk='.get_permalink().'&title=';the_title(); echo '" title="'; _e('Share on Google' , 'source'); echo' ">Google</a>';
			}
			if($supon) {
				echo '<a target="_blank" rel="nofollow" class="stumbleupon" href="http://www.stumbleupon.com/submit?url='.get_permalink().'&title=';the_title(); echo '" title="'; _e('Share on StumbleUpon' , 'source'); echo' ">Stumbleupon</a>';
			}
			if($reddit) {
				echo '<a target="_blank" rel="nofollow" class="reddit" href="http://reddit.com/submit?url='.get_permalink().'&title=';the_title(); echo '" title="'; _e('Share on Reddit' , 'source'); echo' ">Reddit</a>';
			}
			if($tech) {
				echo '<a target="_blank" rel="nofollow" class="technorati" href="http://technorati.com/faves?sub=favthis&add='.get_permalink().'" title="'; _e('Share on Technorati' , 'source'); echo' ">Technorati</a>';
			}
			if($yahoo) {
				echo '<a target="_blank" rel="nofollow" class="yahoo" href="http://buzz.yahoo.com/buzz?targetUrl='.get_permalink().'&headline=';the_title(); echo '" title="'; _e('Share on Yahoo' , 'source'); echo' ">Yahoo</a>';
			}
			if($blogger) {
				echo '<a target="_blank" rel="nofollow" class="blogger" href="http://www.blogger.com/blog_this.pyra?t&u='.get_permalink().'&n=';the_title(); echo '&pli=1" title="'; _e('Share on Blogger' , 'source'); echo' ">Blogger</a>';
			}
			if($myspace) {
				echo '<a target="_blank" rel="nofollow" class="myspace" href="http://www.myspace.com/Modules/PostTo/Pages/?u='.get_permalink().'&t=';the_title(); echo '&c='.get_permalink().'" title="'; _e('Share on Myspace' , 'source'); echo' ">Myspace</a>';
			}
			if($rss) {
				echo '<a target="_blank" rel="nofollow" class="rss" href="'.get_post_comments_feed_link($post->ID) .'" title="'; _e('RSS Feed' , 'source'); echo' ">RSS</a>';
			}
			echo '<div class="clear"></div>';
			
		echo $after_widget; 
	}
	
	function update($new_instance, $old_instance) { 
		$instance['title'] 		= strip_tags($new_instance['title']);
		$instance['boxtweet']	= $new_instance['boxtweet'];
		$instance['boxg1']	= $new_instance['boxg1'];
		$instance['boxlike']	= $new_instance['boxlike'];
		$instance['boxpinterest']	= $new_instance['boxpinterest'];
		$instance['plus1']	= $new_instance['plus1'];
		$instance['fbook'] = $new_instance['fbook'] ? '1' : '0';
		$instance['tweet'] = $new_instance['tweet'] ? '1' : '0';
		$instance['email'] = $new_instance['email'] ? '1' : '0';
		$instance['blogger']  = $new_instance['blogger'] ? '1' : '0';
		$instance['dlc'] = $new_instance['dlc'] ? '1' : '0';
		$instance['digg'] = $new_instance['digg'] ? '1' : '0';
		$instance['google'] = $new_instance['google'] ? '1' : '0';
		$instance['myspace'] = $new_instance['myspace'] ? '1' : '0';
		$instance['supon'] = $new_instance['supon'] ? '1' : '0';
		$instance['yahoo'] = $new_instance['yahoo'] ? '1' : '0';
		$instance['reddit'] = $new_instance['reddit'] ? '1' : '0';
		$instance['tech'] = $new_instance['tech'] ? '1' : '0';
		$instance['rss'] = $new_instance['rss'] ? '1' : '0';
		return $new_instance;
	}
 
	function form($instance) {
		$defaults	= array( 'title' => 'Share This Post', 'boxtweet' => '1', 'boxg1' => '1', 'boxlike' => '1','plus1' => '1', 'fbook' => '1', 'tweet' => '1', 'email' => '1', 'blogger' => '1', 'dlc' => '1', 'digg' => '1', 'google' => '1', 'myspace' => '1', 'supon' => '1', 'yahoo' => '1', 'reddit' => '1', 'tech' => '1', 'rss' => '1');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>
	
	<p>
		<select id="<?php echo $this->get_field_id( 'boxtweet' ); ?>" name="<?php echo $this->get_field_name( 'boxtweet' ); ?>">
			<option value="1" <?php if ( '1' == $instance['boxtweet'] ) echo 'selected="selected"'; ?>>Enable</option>
			<option value="0" <?php if ( '0' == $instance['boxtweet'] ) echo 'selected="selected"'; ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'boxtweet' ); ?>">Twitter Button</label> 
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'boxg1' ); ?>" name="<?php echo $this->get_field_name( 'boxg1' ); ?>">
			<option value="1" <?php selected( $instance['boxg1'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['boxg1'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'boxg1' ); ?>">Google +1 Button</label> 
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'boxlike' ); ?>" name="<?php echo $this->get_field_name( 'boxlike' ); ?>">
			<option value="1" <?php selected( $instance['boxlike'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['boxlike'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'boxlike' ); ?>">Facebook Like Button</label> 
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'boxpinterest' ); ?>" name="<?php echo $this->get_field_name( 'boxpinterest' ); ?>">
			<option value="1" <?php selected( $instance['boxpinterest'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['boxpinterest'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'boxpinterest' ); ?>">Pinterest Pin It Button</label> 
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'fbook' ); ?>" name="<?php echo $this->get_field_name( 'fbook' ); ?>">
			<option value="1" <?php selected( $instance['fbook'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['fbook'], '0' );  ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'fbook' ); ?>">Share on Facebook</label> 
	</p>		
	
	<p>
		<select id="<?php echo $this->get_field_id( 'tweet' ); ?>" name="<?php echo $this->get_field_name( 'tweet' ); ?>">
			<option value="1" <?php selected( $instance['tweet'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['tweet'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'tweet' ); ?>">Share on Twitter</label> 
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'plus1' ); ?>" name="<?php echo $this->get_field_name( 'plus1' ); ?>">
			<option value="1" <?php selected( $instance['plus1'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['plus1'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'plus1' ); ?>">Share on Google1</label> 
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>">
			<option value="1" <?php selected( $instance['email'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['email'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'email' ); ?>">Send as Email</label> 
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'dlc' ); ?>" name="<?php echo $this->get_field_name( 'dlc' ); ?>">
			<option value="1" <?php selected( $instance['dlc'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['dlc'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'dlc' ); ?>">Bookmark on Delicious</label> 
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'digg' ); ?>" name="<?php echo $this->get_field_name( 'digg' ); ?>">
			<option value="1" <?php selected( $instance['digg'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['digg'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'digg' ); ?>">Share on Digg</label> 
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'google' ); ?>" name="<?php echo $this->get_field_name( 'google' ); ?>">
			<option value="1" <?php selected( $instance['google'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['google'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'google' ); ?>">Share on Google</label> 
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'supon' ); ?>" name="<?php echo $this->get_field_name( 'supon' ); ?>">
			<option value="1" <?php selected( $instance['supon'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['supon'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'supon' ); ?>">Share on Stumbleupon</label> 
	</p>

	<p>
		<select id="<?php echo $this->get_field_id( 'reddit' ); ?>" name="<?php echo $this->get_field_name( 'reddit' ); ?>">
			<option value="1" <?php selected( $instance['reddit'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['reddit'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'reddit' ); ?>">Share on Reddit</label> 
	</p>

	<p>
		<select id="<?php echo $this->get_field_id( 'tech' ); ?>" name="<?php echo $this->get_field_name( 'tech' ); ?>">
			<option value="1" <?php selected( $instance['tech'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['tech'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'tech' ); ?>">Share on Technorati</label> 
	</p>
	
	<p>
		<select id="<?php echo $this->get_field_id( 'yahoo' ); ?>" name="<?php echo $this->get_field_name( 'yahoo' ); ?>">
			<option value="1" <?php selected( $instance['yahoo'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['yahoo'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'yahoo' ); ?>">Share on Yahoo</label> 
	</p>
	
	<p>
		<select id="<?php echo $this->get_field_id( 'blogger' ); ?>" name="<?php echo $this->get_field_name( 'blogger' ); ?>">
			<option value="1" <?php selected( $instance['blogger'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['blogger'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'blogger' ); ?>">Share on Blogger</label> 
	</p>		
	
	<p>
		<select id="<?php echo $this->get_field_id( 'myspace' ); ?>" name="<?php echo $this->get_field_name( 'myspace' ); ?>">
			<option value="1" <?php selected( $instance['myspace'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['myspace'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'myspace' ); ?>">Share on MySpace</label> 
	</p>
	<p>
		<select id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>">
			<option value="1" <?php selected( $instance['rss'], '1' ); ?>>Enable</option>
			<option value="0" <?php selected( $instance['rss'], '0' ); ?>>Disable</option>	
		</select>
		<label for="<?php echo $this->get_field_id( 'rss' ); ?>">Display Comments RSS</label> 
	</p>
	
<?php
	}
}

/*
 * Custom Query
 */

 class gab_custom_widget extends WP_Widget {
 
    function gab_custom_widget() {
        $widget_ops = array( 'classname' => 'gab_custom_widget', 'description' => 'Display Custom Queries' );
        $control_ops = array( 'width' => 480, 'height' => 350, 'id_base' => 'gab_custom_widget' );
        $this->WP_Widget( 'gab_custom_widget', 'Gabfire Widget : Custom Query', $widget_ops, $control_ops);
    }
 
    function widget($args, $instance) {      
        extract( $args );
        $title    = $instance['title'];
        $swap = $instance['swap'] ? '1' : '0';
		$video = $instance['video'] ? '1' : '0';
        $c_image = $instance['c_image'];
		$c_link = $instance['c_link'];
        $postnr    = $instance['postnr'];
		$postids    = $instance['postids'];
        $cat_or_postpage    = $instance['cat_or_postpage'];
        $d_thumb    = $instance['d_thumb'] ? '1' : '0';
        $postmeta    = $instance['postmeta'] ? '1' : '0';
        $media_w    = $instance['media_w'];
        $media_h    = $instance['media_h'];
        $excerpt_l    = $instance['excerpt_l'];
        $thumbalign    = $instance['thumbalign'];
		$postcls    = $instance['postcls'];
		$titlecls    = $instance['titlecls'];
        
        echo $before_widget;
		
            if ( $title ) {
                echo '<p class="catname">';
                    if ( $c_link ) { echo '<a href="' . $c_link . '">'; }
                        echo $title;
                    if ( $c_link ) { echo '</a>'; }
                echo '</p>';
            }
			
            if ( $c_image ) { 
				if ( $c_link ) { echo '<a href="' . $c_link . '">'; }
					echo '<img style="display:block;margin:0 0 7px;line-height:0" src="'.$c_image.'" alt="" />'; 
				if ( $c_link ) { echo '</a>'; }
			}
            $count = 1;
			global $do_not_duplicate, $post;
            
			if ( $cat_or_postpage == 0 ) { 
				$args = array(
				  'post_type' =>array('page','post'),
				  'post__in'=> explode(',', $postids),
				);
			} else { 	
				$args = array(
				  'post__not_in'=>$do_not_duplicate,
				  'posts_per_page' => $postnr,
				  'cat' => $postids
				);	
			}
			
            $gab_query = new WP_Query();$gab_query->query($args); 
            while ($gab_query->have_posts()) : $gab_query->the_post();
			if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }
            ?>

                <div class="<?php if ( $postcls ) { echo $postcls; } else { echo 'featuredpost'; } if($count == $postnr) { echo ' lastpost'; } ?>">

					<?php
					if ( $d_thumb ) {
						if ( $swap ) { ?>
							<h2 class="<?php if ( $titlecls ) { echo $titlecls; } else { echo 'posttitle'; } ?>">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'source' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
							</h2>   
							<?php
							gab_media(array(
								'name' => 'gabfire',
								'imgtag' => 1,
								'link' => 1,
								'enable_video' => $video,
								'video_id' => 'custom-widget',
								'catch_image' => 0,
								'enable_thumb' => 1,
								'resize_type' => 'c',
								'media_width' => $media_w, 
								'media_height' => $media_h, 
								'thumb_align' => $thumbalign,
								'enable_default' => 0
							));							
						} else {
							gab_media(array(
								'name' => 'gabfire',
								'imgtag' => 1,
								'link' => 1,
								'enable_video' => $video,
								'video_id' => 'custom-widget',
								'catch_image' => 0,
								'enable_thumb' => 1,
								'resize_type' => 'c',
								'media_width' => $media_w, 
								'media_height' => $media_h, 
								'thumb_align' => $thumbalign,
								'enable_default' => 0
							)); ?>
							<h2 class="posttitle">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'source' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
							</h2><?php							
						}
					} else { ?>
						<h2 class="<?php if ( $titlecls ) { echo $titlecls; } else { echo 'posttitle'; } ?>">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'source' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
						</h2>
					<?php }
				    
					if ( $excerpt_l ) {
						echo '<p>' . string_limit_words(get_the_excerpt(), $excerpt_l) . '&hellip;</p>';
					}
                    
                    if ( $postmeta ) {
                        gab_postmeta(); 
                    } ?>
                    
                </div><!-- .featuredpost -->
            <?php $count++; endwhile; wp_reset_query(); ?>
    <?php         
        echo $after_widget; 
    }
    
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['video']     = $new_instance['video'] ? '1' : '0';
        $instance['swap']     = $new_instance['swap'] ? '1' : '0';  
        $instance['postnr']     = (int)$new_instance['postnr'];
		$instance['postids']     = $new_instance['postids'];
		$instance['c_link']     = $new_instance['c_link'];
        $instance['c_image']     = $new_instance['c_image']; 
        $instance['cat_or_postpage']     = $new_instance['cat_or_postpage']; 
        $instance['d_thumb']     = $new_instance['d_thumb'] ? '1' : '0';
        $instance['postmeta']     = $new_instance['postmeta'] ? '1' : '0';
        $instance['media_w']     = (int)$new_instance['media_w']; 
        $instance['media_h']     = (int)$new_instance['media_h']; 
        $instance['excerpt_l']     = (int)$new_instance['excerpt_l'];
        $instance['thumbalign']     = $new_instance['thumbalign'];
		$instance['postcls']     = $new_instance['postcls'];
		$instance['titlecls']     = $new_instance['titlecls'];
        return $instance;
    }

    function form( $instance ) {
        $defaults = array( 'title' => 'Custom Query', 'postcls' => 'featuredpost', 'titlecls' => 'posttitle');
        $instance = wp_parse_args( (array) $instance, $defaults ); 
        ?>
        
		<p style="background-color: #efefef;border:1px solid #ddd;padding:10px;">Huh, you think I am just another widget? Believe it or not, I'm quite powerful. You'll be amazed to learn that I can be used to build an entire site :) So let's start, shall we?<p>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Section or Category Heading - <span style="color:#aaa">(leave empty for no heading)</span></label>
			<input class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
        <p>
			<label for="<?php echo $this->get_field_id('c_image'); ?>">Section or Category Image URL - <span style="color:#aaa">(ex: http://www.domain.com/image.jpg)</span></label> 
			<input class="widefat"  id="<?php echo $this->get_field_id('c_image'); ?>" name="<?php echo $this->get_field_name('c_image'); ?>" type="text" value="<?php echo esc_attr( $instance['c_image'] ); ?>" />
        </p>

        <p>
			<label for="<?php echo $this->get_field_id('c_link'); ?>">Section or Category Link URL - <span style="color:#aaa">Leave empty to disable</span></label> 
			<input class="widefat"  id="<?php echo $this->get_field_id('c_link'); ?>" name="<?php echo $this->get_field_name('c_link'); ?>" type="text" value="<?php echo esc_attr( $instance['c_link'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('cat_or_postpage'); ?>">Do you want to run a query based on Category or Post/Page ID</label><br />
            <select id="<?php echo $this->get_field_id( 'cat_or_postpage' ); ?>" name="<?php echo $this->get_field_name( 'cat_or_postpage' ); ?>" style="width:480px">
                <option value="1" <?php if ( '1' == $instance['cat_or_postpage'] ) echo 'selected="selected"'; ?>>Category</option>
                <option value="0" <?php if ( '0' == $instance['cat_or_postpage'] ) echo 'selected="selected"'; ?>>Post/Page</option>    
            </select>
        </p>		
		
        <p>
            <label for="<?php echo $this->get_field_id('postids'); ?>">Enter <a href="http://www.gabfirethemes.com/how-to-check-category-ids/">Category ID(s)</a> - OR - Post/Page ID(s) <span style="color:#aaa">(ex:3,5,99 comma separated, no spaces)</span></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('postids'); ?>" name="<?php echo $this->get_field_name('postids'); ?>" type="text" value="<?php echo esc_attr( $instance['postids'] ); ?>" />
        </p>   		
		
        <p>
            <label for="<?php echo $this->get_field_name( 'postnr' ); ?>">Number of entries to display</label>
            <select id="<?php echo $this->get_field_id( 'postnr' ); ?>" name="<?php echo $this->get_field_name( 'postnr' ); ?>">            
            <?php
                for ( $i = 1; $i <= 15; ++$i )
                echo "<option value='$i' " . ( $instance['postnr'] == $i ? "selected='selected'" : '' ) . ">$i</option>";
            ?>
            </select>
        </p>        

        <p style="background-color: #efefef;border:1px solid #ddd;padding:10px;">Custom query will generate thumbnails only if TimThumb is the active thumbnail script. All Gabfire themes have TimThumb option enabled by default. However, if you use WP Post Thumbnails option, then thumbnails CANNOT be generated by this widget.</p>
		
        <p style="float:left;width:235px">
            <label for="<?php echo $this->get_field_id( 'd_thumb' ); ?>">Show Thumbnails</label><br />
            <select id="<?php echo $this->get_field_id( 'd_thumb' ); ?>" name="<?php echo $this->get_field_name( 'd_thumb' ); ?>" style="width:235px">
                <option value="1" <?php if ( '1' == $instance['d_thumb'] ) echo 'selected="selected"'; ?>>Yes</option>
                <option value="0" <?php if ( '0' == $instance['d_thumb'] ) echo 'selected="selected"'; ?>>No</option>    
            </select>
        </p>
		
        <p style="float:right;width:235px">
            <label for="<?php echo $this->get_field_id( 'video' ); ?>">Show Videos</label><br />
            <select id="<?php echo $this->get_field_id( 'video' ); ?>" name="<?php echo $this->get_field_name( 'video' ); ?>" style="width:235px">
                <option value="1" <?php if ( '1' == $instance['video'] ) echo 'selected="selected"'; ?>>Yes</option>
                <option value="0" <?php if ( '0' == $instance['video'] ) echo 'selected="selected"'; ?>>No</option>    
            </select>
        </p>   		
		
        <p style="float:left;width:235px">
            <label for="<?php echo $this->get_field_id('media_w'); ?>">Width of Thumbnail (ex: 50 or 300)</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('media_w'); ?>" name="<?php echo $this->get_field_name('media_w'); ?>" type="text" value="<?php echo esc_attr( $instance['media_w'] ); ?>" />
        </p>

        <p style="float:right;width:235px">
            <label for="<?php echo $this->get_field_id('media_h'); ?>">Height of Thumbnail (ex: 50 or 300)</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('media_h'); ?>" name="<?php echo $this->get_field_name('media_h'); ?>" type="text" value="<?php echo esc_attr( $instance['media_h'] ); ?>" />
        </p>
		
        <p style="float:left;width:235px">
            <label for="<?php echo $this->get_field_id( 'thumbalign' ); ?>">Thumbnail Alignment</label><br />
            <select id="<?php echo $this->get_field_id( 'thumbalign' ); ?>" name="<?php echo $this->get_field_name( 'thumbalign' ); ?>" style="width:235px">
                <option value="alignleft" <?php if ( 'alignleft' == $instance['thumbalign'] ) echo 'selected="selected"'; ?>>Left</option>
                <option value="alignright" <?php if ( 'alignright' == $instance['thumbalign'] ) echo 'selected="selected"'; ?>>Right</option>
                <option value="aligncenter" <?php  if ( 'aligncenter' == $instance['thumbalign'] ) echo 'selected="selected"'; ?>>Center</option>            
            </select>
        </p>
		
        <p style="float:right;width:235px">
            <label for="<?php echo $this->get_field_id('excerpt_l'); ?>"># of Words in Excerpt (ex:35 / Max: 55)</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('excerpt_l'); ?>" name="<?php echo $this->get_field_name('excerpt_l'); ?>" type="text" value="<?php echo esc_attr( $instance['excerpt_l'] ); ?>" />
        </p> 
		
		<div style="clear:both"></div>
		
        <p>
			<label for="<?php echo $this->get_field_id( 'swap' ); ?>">Thumbnail and Title Position</label><br />
			<select id="<?php echo $this->get_field_id( 'swap' ); ?>" name="<?php echo $this->get_field_name( 'swap' ); ?>" style="width:480px">
				<option value="1" <?php if ( '1' == $instance['swap'] ) echo 'selected="selected"'; ?>>Show title first then thumbnail</option>
				<option value="0" <?php if ( '0' == $instance['swap'] ) echo 'selected="selected"'; ?>>Show thumbnail first then title</option>
			</select>
		</p>			
		        
        <p>
            <label for="<?php echo $this->get_field_id( 'postmeta' ); ?>">Display post meta below excerpt</label> 
            <select id="<?php echo $this->get_field_id( 'postmeta' ); ?>" name="<?php echo $this->get_field_name( 'postmeta' ); ?>">
                <option value="1" <?php if ( '1' == $instance['postmeta'] ) echo 'selected="selected"'; ?>>Enable</option>
                <option value="0" <?php if ( '0' == $instance['postmeta'] ) echo 'selected="selected"'; ?>>Disable</option>    
            </select>
        </p>
		
		<h4>Advanced - For Developers Only</h4>
		
		<p style="background-color: #efefef;border:1px solid #ddd;padding:10px;">For developers only. Do not edit any of classes below unless you know what you are doing.</p>
		
        <p style="float:left;width:235px">
            <label for="<?php echo $this->get_field_id('postcls'); ?>">CSS Class of Post Wrapper Div</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('postcls'); ?>" name="<?php echo $this->get_field_name('postcls'); ?>" type="text" value="<?php echo esc_attr( $instance['postcls'] ); ?>" />
        </p>

        <p style="float:right;width:235px">
            <label for="<?php echo $this->get_field_id('titlecls'); ?>">CSS Class of Post Title</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('titlecls'); ?>" name="<?php echo $this->get_field_name('titlecls'); ?>" type="text" value="<?php echo esc_attr( $instance['titlecls'] ); ?>" />
        </p>		
<?php
    }
}
register_widget('gab_custom_widget');


/*
 * Author Badge
 */
class gab_authorbadge extends WP_Widget {
	function gab_authorbadge() {
		$widget_ops = array( 'classname' => 'gab_authorbadge', 'description' => 'Display widget with a big icon' );
		$control_ops = array( 'width' => 250, 'id_base' => 'gab_authorbadge' );
		$this->WP_Widget( 'gab_authorbadge', 'Gabfire Widget : Author Badge', $widget_ops, $control_ops);	
	}
	
	function widget($args, $instance) {
		extract( $args );
		$number = $instance['t_nr'];
		$picsize     = $instance['picsize'];
		$f_twitter     = $instance['f_twitter'];
		$f_google     = $instance['f_google'];
		$f_facebook     = $instance['f_facebook'];
		$f_viewwebsite     = $instance['f_viewwebsite'];
		$f_allposts     = $instance['f_allposts'];
		$f_ltweets     = $instance['f_ltweets'];

		if ( get_the_author_meta( 'description' ) and (is_single() or is_author()) ) {
		echo $before_widget;
				?>
					<h3 class="widgettitle"><?php printf( esc_attr__( 'By %s', 'source' ), get_the_author() ); ?></h3>
					
					<p style="overflow:hidden">
						<?php echo get_avatar( get_the_author_meta('email'), $picsize ); ?>
						<?php the_author_meta( 'description' ); ?>
					</p>
					
					<?php if ( get_the_author_meta( 'twitter' ) ) { ?>
						<p><a class="author_social t_link" href="http://www.twitter.com/<?php the_author_meta('twitter'); ?>" rel="nofollow" target="_blank">
							<?php echo $f_twitter; ?>
							</a>
						</p>
					<?php } ?>
					
					<?php if ( get_the_author_meta( 'facebook' ) ) { ?>
						<p><a class="author_social f_link" href="<?php the_author_meta('facebook'); ?>" rel="nofollow" target="_blank">
							<?php echo $f_facebook; ?>
							</a>
						</p>
					<?php } ?>
					
					<?php if ( get_the_author_meta( 'googleplus' ) ) { ?>
						<p><a class="author_social g_link" href="<?php the_author_meta('googleplus'); ?>" rel="nofollow" target="_blank">
							<?php echo $f_google; ?>
							</a>
						</p>
					<?php } ?>		
					
					<?php if ( !is_author() ) { ?>
						<p><a class="author_social a_link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
							<?php echo $f_allposts; ?>
							</a>					
						</p>
					<?php } ?>
					
					<?php if ( get_the_author_meta( 'user_url' ) ) { ?>
						<p class="last"><a class="author_social w_link" href="<?php the_author_meta('user_url'); ?>" rel="nofollow" target="_blank">
							<?php echo $f_viewwebsite; ?>
							</a>
						</p>
					<?php } ?>						

					<?php if($number > 0) { ?>
						<div class="authorstweets">
							<p><strong><?php echo $f_ltweets; ?></strong></p>
							<div id="twitter_div">
								<ul id="twitter_update_list" class="twitter-list"><li></li></ul>
							</div>
							<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
							<script type="text/javascript" src="http://api.twitter.com/1/statuses/user_timeline.json?screen_name=<?php the_author_meta('twitter'); ?>&include_rts=1&callback=twitterCallback2&count=<?php echo esc_attr( $number ); ?>"></script>	
						</div>
					<?php }
				
		echo $after_widget; 
		}
	}
		
	function update($new_instance, $old_instance) {  
		$instance['t_nr']  = (int)$new_instance['t_nr'];
		$instance['picsize']  = (int)$new_instance['picsize'];
		$instance['f_twitter']     = $new_instance['f_twitter'];
		$instance['f_facebook']     = $new_instance['f_facebook'];
		$instance['f_google']     = $new_instance['f_google'];
		$instance['f_viewwebsite']     = $new_instance['f_viewwebsite'];
		$instance['f_allposts']     = $new_instance['f_allposts'];		
		$instance['f_ltweets']     = $new_instance['f_ltweets'];		
		return $new_instance;
	}	  
 
	function form($instance) {
		$defaults	= array( 't_nr' => '0', 'f_ltweets' => 'Latest Tweets', 'picsize' => '60', 'f_twitter' => 'Follow on Twitter', 'f_facebook' => 'Connect on Facebook', 'f_google' => 'Find on Google+', 'f_viewwebsite' => 'Visit Website', 'f_allposts' => 'View all Posts');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>
        <p>
            <label for="<?php echo $this->get_field_id('picsize'); ?>">Width/Height of author thumbnail</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('picsize'); ?>" name="<?php echo $this->get_field_name('picsize'); ?>" type="text" value="<?php echo esc_attr( $instance['picsize'] ); ?>" />
        </p>		
		
        <p>
            <label for="<?php echo $this->get_field_id('f_twitter'); ?>">Anchor text for Twitter</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('f_twitter'); ?>" name="<?php echo $this->get_field_name('f_twitter'); ?>" type="text" value="<?php echo esc_attr( $instance['f_twitter'] ); ?>" />
        </p>		

        <p>
            <label for="<?php echo $this->get_field_id('f_facebook'); ?>">Anchor text for Facebook</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('f_facebook'); ?>" name="<?php echo $this->get_field_name('f_facebook'); ?>" type="text" value="<?php echo esc_attr( $instance['f_facebook'] ); ?>" />
        </p>	

        <p>
            <label for="<?php echo $this->get_field_id('f_google'); ?>">Anchor text for Google+</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('f_google'); ?>" name="<?php echo $this->get_field_name('f_google'); ?>" type="text" value="<?php echo esc_attr( $instance['f_google'] ); ?>" />
        </p>			

        <p>
            <label for="<?php echo $this->get_field_id('f_viewwebsite'); ?>">Anchor text for Author URL</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('f_viewwebsite'); ?>" name="<?php echo $this->get_field_name('f_viewwebsite'); ?>" type="text" value="<?php echo esc_attr( $instance['f_viewwebsite'] ); ?>" />
        </p>		

        <p>
            <label for="<?php echo $this->get_field_id('f_allposts'); ?>">Anchor text for Author Posts</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('f_allposts'); ?>" name="<?php echo $this->get_field_name('f_allposts'); ?>" type="text" value="<?php echo esc_attr( $instance['f_allposts'] ); ?>" />
        </p>				
		
        <p>
            <label for="<?php echo $this->get_field_id('f_ltweets'); ?>">String to display before author tweets</label>
            <input class="widefat"  id="<?php echo $this->get_field_id('f_ltweets'); ?>" name="<?php echo $this->get_field_name('f_ltweets'); ?>" type="text" value="<?php echo esc_attr( $instance['f_ltweets'] ); ?>" />
        </p>
		
		<p>
			<label for="<?php echo $this->get_field_name( 't_nr' ); ?>">Number of Author's tweet?</label>
			<select id="<?php echo $this->get_field_id( 't_nr' ); ?>" name="<?php echo $this->get_field_name( 't_nr' ); ?>">			
			<?php
				for ( $i = 0; $i <= 15; ++$i )
				echo "<option value='$i' " . selected( $instance['t_nr'], $i, false ) . ">$i</option>";
			?>
			</select>
		</p>		
				
		
		<p><small>0 to disable - Set twitter username on user profile page.<br />Go to User profile page to enter Facebook, Twitter and Author URL details</small></p>
	
<?php
	}
}


/*
 * Related Posts
 */
class gab_relatedposts extends WP_Widget {
	function gab_relatedposts() {
		$widget_ops = array( 'classname' => 'gab_relatedposts', 'description' => 'Display related post with thumbnails' );
		$control_ops = array( 'width' => 250, 'id_base' => 'gab_relatedposts' );
		$this->WP_Widget( 'gab_relatedposts', 'Gabfire Widget : Related Posts Thumbs', $widget_ops, $control_ops);	
	}
	
	function widget($args, $instance) {
		extract( $args );
		$title    = $instance['title'];
		$postnr    = $instance['postnr'];

		echo $before_widget;
			global $post,$page;
			$tags = wp_get_post_tags($post->ID);
			if ($tags) {
			$tag_ids = array();
			foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
			$args=array(
			'tag__in' => $tag_ids,
			'post__not_in' => array($post->ID),
			'posts_per_page'=> $postnr, // Enter the Number of posts that will be shown.
			'caller_get_posts'=> 1,
			'orderby'=>'rand' // Randomize the posts
			);
			$my_query = new wp_query( $args );

			if( $my_query->have_posts() ) {	
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			
			if ($postnr == 2) {
				$width = '48%';
			} elseif ($postnr == 3) {
				$width = '31%';
			} elseif ($postnr == 4) {
				$width = '23%';
			} elseif ($postnr == 5) {
				$width = '18%';
			}
			
			while( $my_query->have_posts() ) {
			$my_query->the_post(); ?>
				<div class="gab_relateditem" style="width:<?php echo $width; ?>">
					<a href="<?php the_permalink()?>" rel="bookmark" class="related_postthumb" title="<?php the_title(); ?>">
						<?php the_post_thumbnail('thumbnail'); ?>
					</a>
					<a href="<?php the_permalink()?>" rel="bookmark" class="related_posttitle" title="<?php the_title(); ?>">
						<?php the_title(); ?>
					</a>
				 </div>
			<?php }
			echo '<div class="clear"></div>';
			} }
			wp_reset_query();
		echo $after_widget;
		}
		
	function update($new_instance, $old_instance) {  
		$instance['title']  = $new_instance['title'];	
		$instance['postnr']  = (int)$new_instance['postnr'];	
		return $new_instance;
	}	  

	function form($instance) {
		$defaults	= array( 'title' => 'Related Posts', 'postnr' => '5');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>
	
		<p>
			<label for="<?php echo $this->get_field_name( 'postnr' ); ?>">Number of Posts to display?</label>
			<select id="<?php echo $this->get_field_id( 'postnr' ); ?>" name="<?php echo $this->get_field_name( 'postnr' ); ?>">			
			<?php
				for ( $i = 2; $i <= 5; ++$i )
				echo "<option value='$i' " . selected( $instance['postnr'], $i, false ) . ">$i</option>";
			?>
			</select>
		</p>			
<?php
	}
}

function gab_register_widgets(){
    register_widget('text_widget');
    register_widget('text2_widget');
    register_widget('widget_flickr');
    register_widget('feedburner_widget');
    register_widget('tweet_widget');
    register_widget('search_widget');
    register_widget('about_widget');
    register_widget('gab_social_widget');
    register_widget('archive_widget');
    register_widget('gab_tabs');
    register_widget('gab_share_widget');
    register_widget('gab_custom_widget');
    register_widget('gab_authorbadge');
    register_widget('gab_relatedposts');
	register_widget('twitter');
	register_widget('most');
}

function unregister_widgets() {
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'BP_Groups_Widget' );
	unregister_widget( 'BP_Core_Members_Widget' );
}
add_action( 'widgets_init', 'unregister_widgets' );
add_action( 'widgets_init', 'gab_register_widgets' );