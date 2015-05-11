<?php

/**
 * Author: Alin Marcu
 * Author URI: https://deconf.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class GADSH_Frontend_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct('gadash_frontend_widget', __('Google Analytics Dashboard', 'ga-dash'), array(
            'description' => __("Will display your google analytics stats in a widget", 'ga-dash')
        ));
        if (! wp_script_is('googlejsapi')) {
            wp_register_script('googlejsapi', 'https://www.google.com/jsapi');
            wp_enqueue_script('googlejsapi');
        }
    }

    public function widget($args, $instance)
    {
        
        global $GADASH_Config;
        $title = apply_filters('widget_title', $instance['title']);
        echo "\n<!-- BEGIN GADWP v" . GADWP_CURRENT_VERSION . " Widget - https://deconf.com/google-analytics-dashboard-wordpress/ -->\n";
        echo $args['before_widget'];
        if (! empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
            /*
         * Include GAPI
         */
        if ($GADASH_Config->options['ga_dash_token'] and $GADASH_Config->options['ga_dash_tableid_jail'] and function_exists('curl_version')) {
            include_once ($GADASH_Config->plugin_path . '/tools/gapi.php');
            global $GADASH_GAPI;
        } else {
            return;
        }
        
        /*
         * Include Tools
         */
        include_once ($GADASH_Config->plugin_path . '/tools/tools.php');
        $tools = new GADASH_Tools();
        
        if (! $GADASH_GAPI->client->getAccessToken()) {
            return;
        }
        
        if (isset($GADASH_Config->options['ga_dash_tableid_jail'])) {
            $projectId = $GADASH_Config->options['ga_dash_tableid_jail'];
            $profile_info = $tools->get_selected_profile($GADASH_Config->options['ga_dash_profile_list'], $projectId);
            if (isset($profile_info[4])) {
                $GADASH_GAPI->timeshift = $profile_info[4];
            } else {
                $GADASH_GAPI->timeshift = (int) current_time('timestamp') - time();
            }
        } else {
            return;
        }
        
        ob_start();
        
        if ($data = $GADASH_GAPI->frontend_widget_stats($projectId, $instance['period'], (int) $instance['anonim'], (int) $instance['display'])) {
            echo $data;
        }
        
        if ((int) $instance['give_credits'] == 1)
            echo '<div style="text-align:right;width:100%;font-size:0.8em;">' . __('generated by', 'ga-dash') . ' <a href="https://deconf.com/google-analytics-dashboard-wordpress/" rel="nofollow" style="text-decoration:none;font-size:1em;">GADWP</a></div>';
        
        $widget_content = ob_get_contents();
        
        ob_end_clean();
        
        echo apply_filters('widget_html_content', $widget_content);
        
        echo $args['after_widget'];
        echo "\n<!-- END GADWP Widget -->\n";
    }

    public function form($instance)
    {
        $title = (isset($instance['title']) ? $instance['title'] : __("Google Analytics Stats", 'ga-dash'));
        $period = (isset($instance['period']) ? $instance['period'] : '7daysAgo');
        $display = (isset($instance['display']) ? $instance['display'] : 1);
        $give_credits = (isset($instance['give_credits']) ? $instance['give_credits'] : 1);
        $anonim = (isset($instance['anonim']) ? $instance['anonim'] : 0);
        ?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( "Title:",'ga-dash' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'title' ); ?>"
		name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
		value="<?php echo esc_attr( $title ); ?>">
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e( "Display:",'ga-dash' ); ?></label>
	<select id="<?php echo $this->get_field_id('display'); ?>"
		class="widefat"
		name="<?php   echo $this->get_field_name( 'display' ); ?>">
		<option value="1" <?php selected( $display, 1 ); ?>><?php _e('Chart & Totals', 'ga-dash');?></option>
		<option value="2" <?php selected( $display, 2 ); ?>><?php _e('Chart', 'ga-dash');?></option>
		<option value="3" <?php selected( $display, 3 ); ?>><?php _e('Totals', 'ga-dash');?></option>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'anonim' ); ?>"><?php _e( "Anonimize chart&#39;s stats:",'ga-dash' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'anonim' ); ?>"
		name="<?php echo $this->get_field_name( 'anonim' ); ?>"
		type="checkbox" <?php checked( $anonim, 1 ); ?> value="1">
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'period' ); ?>"><?php _e( "Stats for:",'ga-dash' ); ?></label>
	<select id="<?php echo $this->get_field_id('period'); ?>"
		class="widefat"
		name="<?php   echo $this->get_field_name( 'period' ); ?>">
		<option value="7daysAgo" <?php selected( $period, '7daysAgo' ); ?>><?php _e('Last 7 Days', 'ga-dash');?></option>
		<option value="14daysAgo" <?php selected( $period, '14daysAgo' ); ?>><?php _e('Last 14 Days', 'ga-dash');?></option>
		<option value="30daysAgo" <?php selected( $period, '30daysAgo' ); ?>><?php _e('Last 30 Days', 'ga-dash');?></option>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'give_credits' ); ?>"><?php _e( "Give credits:",'ga-dash' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'give_credits' ); ?>"
		name="<?php echo $this->get_field_name( 'give_credits' ); ?>"
		type="checkbox" <?php checked( $give_credits, 1 ); ?> value="1">
</p>


<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : 'Analytics Stats';
        $instance['period'] = (! empty($new_instance['period'])) ? strip_tags($new_instance['period']) : '7daysAgo';
        $instance['display'] = (! empty($new_instance['display'])) ? strip_tags($new_instance['display']) : 1;
        $instance['give_credits'] = (! empty($new_instance['give_credits'])) ? strip_tags($new_instance['give_credits']) : 0;
        $instance['anonim'] = (! empty($new_instance['anonim'])) ? strip_tags($new_instance['anonim']) : 0;
        return $instance;
    }
}

function register_GADSH_Frontend_Widget()
{
    register_widget('GADSH_Frontend_Widget');
}
add_action('widgets_init', 'register_GADSH_Frontend_Widget');
