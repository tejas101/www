<?php

	require_once( EMFW__PLUGIN_DIR . 'class.emfwmcdata.php' );

	class EMFWWidget extends WP_Widget {
		
		private $options;
		private $mcdata;
	
		/**
		 * Sets up the widgets name etc
		 */
		public function __construct() {
			parent::__construct(
				'emfw_widget', // Base ID
				__('Easy Mailchimp Forms', 'emfw_language_domain'), // Name
				array( 'description' => __( 'Easy Mailchimp Forms Widget For WordPress', 'emfw_language_domain' ), ) // Args
			);
			$this->options = get_option('emfw_options', emfw_get_defaultvalues());
		}
		
		private function init_mcdata() {
			$this->mcdata = new EMFWMCData($this->options['id_number']);
			$this->mcdata->check_apikey();
		}
	
		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			extract($args);
			$title = apply_filters( 'widget_title', $instance['title'] );
			$listid = $instance['listid'];
			$styleid = $instance['styleid'];
			// Display the widget
			echo $args['before_widget'];
			echo '<div class="emfw_widget_container" id="emfw_widget_container">';
			if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];
			if ( ! empty( $listid ) ) {
				echo '<div class="emfw_mcform_container">';
				$temp = emfw_get_defaultvalues();
				echo '<link rel="stylesheet" href="' . emfw_get_style_url($styleid) . $temp['styles'][$styleid][0] . '" type="text/css" media="all" />';
				$this->init_mcdata();
				echo $this->mcdata->get_form_display($listid, "emfw_widget_container");
				echo '</div>';
			}
			echo '</div>';
			echo $args['after_widget'];
		}
	
		/**
		 * Ouputs the options form on admin
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			if (isset($instance['title'])) $title = $instance['title']; else $title = "";
			if (isset($instance['listid'])) $listid = $instance['listid']; else $listid = "";
			if (isset($instance['styleid'])) $styleid = $instance['styleid']; else $styleid = "";
			$this->init_mcdata();
			$dropdown_html = __( 'Invalid API key or connection error', 'emfw_language_domain' );
			if (count($this->mcdata->get_error()) == 0) {
				$dropdown_html = $this->mcdata->get_listdropdown($this->get_field_id('listid'), $this->get_field_name('listid'), esc_attr($listid));
			}
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Widget title:', 'emfw_language_domain' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'listid' ); ?>"><?php echo __( 'MailChimp list:', 'emfw_language_domain' ); ?></label> 
				<?php echo $dropdown_html; ?>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'styleid' ); ?>"><?php echo __( 'Form style:', 'emfw_language_domain' ); ?></label> 
				<?php echo emfw_get_defaultstyles_dropdown(esc_attr($styleid), $this->get_field_name('styleid'), $this->get_field_id('styleid')); ?>
			</p>
			<?php 
		}
	
		/**
		 * Processing widget options on save
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['listid'] = ( ! empty( $new_instance['listid'] ) ) ? strip_tags( $new_instance['listid'] ) : '';
			$instance['styleid'] = ( ! empty( $new_instance['styleid'] ) ) ? strip_tags( $new_instance['styleid'] ) : '';
			return $instance;
		}
	}
?>