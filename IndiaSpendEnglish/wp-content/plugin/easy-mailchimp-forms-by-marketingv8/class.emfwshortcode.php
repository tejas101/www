<?php

	require_once( EMFW__PLUGIN_DIR . 'class.emfwmcdata.php' );

	class EMFWShortCode {
		
		private $atts;
		private $content;
		private $options;
		private $mcdata;
	
		public function __construct($atts, $content) {
			$this->atts = $atts;
			$this->content = $content;
			$this->options = get_option('emfw_options', emfw_get_defaultvalues());
			$this->mcdata = new EMFWMCData($this->options['id_number']);
			$this->mcdata->check_apikey();
		}
		
		public function __destruct() {
			$this->mcdata = null;
		}
			
		public function get_display_content() {
			$retval = "";
			$retval .= '<div class="emfw_shortcode_container" id="emfw_shortcode_container">';
			// Title
			if (strlen($this->atts[0]) > 0) $retval .= '<h3 class="shortcode-title">' . $this->atts[0] . '</h3>';
			// If listid is set, then generate content
			if (strlen($this->atts[1]) > 0) {
				$retval .= '<div class="emfw_mcform_container">';
				// If content is set then display it
				if (strlen($this->content) > 0) $retval .= '<div class="emfw_mcform_mycontent">' . $this->content . '</div>';
				$temp = emfw_get_defaultvalues();
				$retval .= '<link rel="stylesheet" href="' . emfw_get_style_url($this->atts[2]) . $temp['styles'][$this->atts[2]][2] . '" type="text/css" media="all" />';
				$retval .= $this->mcdata->get_form_display($this->atts[1], "emfw_shortcode_container");
				$retval .= '</div>';
			}
			$retval .= '</div>';
			return $retval;
		}
	
	}
?>