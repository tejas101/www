<?php
	/*
		Plugin Name: Easy Mailchimp Forms By MarketingV8
		Plugin URI: http://marketingv8.wordpress.com
		Description: Insert your MailChimp Forms easily to your site. Use short codes to insert forms to your posts or pages. Use our custom widget to insert forms to your widget areas. Select your list, pick a style and you are ready.
		Version: 1.1.2
		Text Domain: emfw_language_domain
		Domain Path: /languages/ 
		Author: Marketing V8
		Author URI: http://marketingv8.wordpress.com
		License: GPL2
	*/
	
	// Global constants
	define( 'EMFW_VERSION', '1.0.0' );
	define( 'EMFW__MINIMUM_WP_VERSION', '3.5' );
	define( 'EMFW__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	define( 'EMFW__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	if (!defined('EMFW_URL_WP_AJAX')) {
		if ($_SERVER['SERVER_PORT'] == 80)
			define('EMFW_URL_WP_AJAX', admin_url('admin-ajax.php', 'http'));
		else if ($_SERVER['SERVER_PORT'] == 443)
			define('EMFW_URL_WP_AJAX', admin_url('admin-ajax.php', 'https'));
		else
			define('EMFW_URL_WP_AJAX', admin_url('admin-ajax.php'));
	} 
	
	// Multi language support
	load_plugin_textdomain('emfw_language_domain', false, basename( dirname( __FILE__ ) ) . '/languages');
	
	// Check options
	emfw_update_manually_options();
	
	// Admin page init
	if( is_admin() ) {
		require_once( EMFW__PLUGIN_DIR . 'class.emfwsettingspage.php' ); 
		$my_settings_page = new EMFWSettingsPage();
	}
	
	// Init Widget
	require_once( EMFW__PLUGIN_DIR . 'class.emfwwidget.php' );
	add_action('widgets_init', create_function('', 'return register_widget("EMFWWidget");'));
	
	// Admin JavaScript file
	function emfw_admin_scripts_import($hook) {
    if ('settings_page_emfw_plugin_options' == $hook) {
    	wp_enqueue_script('emfw_custom_script', EMFW__PLUGIN_URL . 'scripts.js');
    }
	}
	add_action( 'admin_enqueue_scripts', 'emfw_admin_scripts_import' );
	
	// Front-End Javascript file
	function emfw_fe_scripts_import($hook) {
		wp_enqueue_script('json2');
		wp_enqueue_script('jquery');
	}
	add_action( 'wp_enqueue_scripts', 'emfw_fe_scripts_import' );
	
	// Ajax actions
	add_action('wp_ajax_emfw_ajaxActions', 'emfw_ajaxActions');
	add_action('wp_ajax_nopriv_emfw_ajaxActions', 'emfw_ajaxActions');
	
	// Shortcode
	add_shortcode('emfw_mailchimp_forms', 'emfw_shortcode_handler');
	
	function emfw_shortcode_handler($atts, $content = null) {
		$retval = "";
		// Default and user setted parameters
		extract ( shortcode_atts( array(
				'title' => '',
				'list' => '',
				'style' => '',
			), $atts ) );
		// User custom content
		if (!is_null($content)) $mycontent = do_shortcode($content);
		else $mycontent = "";
		// Generate shortcode
		require_once( EMFW__PLUGIN_DIR . 'class.emfwshortcode.php' );
		$generator = new EMFWShortCode(array($title, $list, $style), $mycontent);
		$retval = $generator->get_display_content();
		return $retval;
	}
	
	function emfw_ajaxActions() {
		if (!empty($_POST)) {
			parse_str($_POST['form_data'], $fd);
			require_once( EMFW__PLUGIN_DIR . 'class.emfwmcdata.php' );
			$options = get_option('emfw_options', emfw_get_defaultvalues());
			$mcdata = new EMFWMCData($options['id_number']);
			$res = $mcdata->ajax_subscribe($fd);
			echo $res['msg'];
			$mcdata = null;
		}
		exit;
	}
	
	// Get default styles array
	function emfw_get_def_styles_array() {
		$retval = array();
		$retval['styles'] = array();
		$retval['styles']['xrvs28dZ'] = array('style.css', 'Default - Simple 1', 'style_mce.css');
		$retval['styles']['385hvEUb'] = array('style.css', 'Default - Simple 2', 'style_mce.css');
		$retval['styles']['8PuCVUoM'] = array('style.css', 'Default - Light gray', 'style_mce.css');
		$retval['styles']['RePzD4Nz'] = array('style.css', 'Default - Heavy Blue', 'style_mce.css');
		$retval['styles']['zrz6zh8J'] = array('style.css', 'Default - Gray', 'style_mce.css');
		$retval['styles']['5GqvX8Cp'] = array('style.css', 'Default - Full Red', 'style_mce.css');
		$retval['styles']['7Wb5yQ4S'] = array('style.css', 'Default - Gray 2', 'style_mce.css');
		$retval['styles']['5Jnk2HAh'] = array('style.css', 'Default - Light gray 2', 'style_mce.css');
		
		return $retval['styles'];
	}
	
	// Get default list settings
	function emfw_get_def_lists_array($t_apikey = "", $disabledefvalues = false) {
		$retval = array();
		$mykey = $t_apikey;
		require_once( EMFW__PLUGIN_DIR . 'class.emfwmcdata.php' );
		if (strlen($mykey) == 0) {
			if ($disabledefvalues) $options = get_option('emfw_options');
			else $options = get_option('emfw_options', emfw_get_defaultvalues());
			$mykey = $options['id_number'];
		}
		$mcdata = new EMFWMCData($mykey);
		$listdata = $mcdata->get_alllists();
		for ($i = 0; $i < $listdata['total']; $i++) {
			$retval[$listdata['data'][$i]['id']] = array("doubleoptin" => true, "welcomeemail" => false, "updateexisting" => false, "replaceig" => true, "hideform" => false, "redirurl" => "");
		}
		return $retval;
	}
	
	// Get default settings
	function emfw_get_defaultvalues() {
		$retval = array();
		$options = get_option( 'emfw_options', array() );
		if (!isset($options['styles'])) {
			$retval['styles'] = emfw_get_def_styles_array();
		} else {
			$retval['styles'] = $options['styles'];
		}
		if (!isset($options['id_number'])) {
			$retval['id_number'] = '';
		} else {
			$retval['id_number'] = $options['id_number'];
		}
		$temp = emfw_get_def_lists_array($retval['id_number'], true);
		if (!isset($options['lists'])) {
			$retval['lists'] = $temp;
		} else {
			// If list is not in the option array then apend it
			$op_keys = array_keys($options['lists']);
			$retval['lists'] = $options['lists'];
			foreach ($temp as $key => $value) {
				if (!in_array($key, $op_keys)) {
					$retval['lists'][$key] = $value;
				}
			}
		}
		return $retval;
	}
	
	// Get dafult styles dropdown
	function emfw_get_defaultstyles_dropdown($defvalue = '', $fname = 'emfw_options[styles]', $fid = 'styles') {
		$retval = "";
		$temp = emfw_get_defaultvalues();
		$retval = '<select id="' . $fid . '" name="' . $fname . '" onchange="emfw_change_style_preview(\'' . EMFW__PLUGIN_URL . '\')">';
		$sorted = $temp['styles'];
		asort($sorted);
		foreach ($sorted as $key => $value) {
			if (strlen($defvalue) == 0) $defvalue = $key;
			$additional = "";
			if ($defvalue == $key) $additional = "selected";
			$retval .= '<option value="' . $key . '" ' . $additional . ' >' . $value[1] . '</option>';
		}
		$retval .= '</select>';
		$retval .= '<br/><br/><div id="style_preview"><img src="' . emfw_get_style_url($defvalue) . 'preview.png" alt="" /></div>';
		return $retval;
	}
	
	// Return plugin styles' URL
	function emfw_get_style_url($sid) {
		return EMFW__PLUGIN_URL . "styles/" . $sid . "/";
	}
	
	// Init TinyMCE editor settings
	function emfw_buttonhooks() {
		// Only add hooks when the current user has permissions AND is in Rich Text editor mode
		if ( ( current_user_can('edit_posts') || current_user_can('edit_pages') ) && get_user_option('rich_editing') ) {
			emfw_before_reg_mce_js();
			add_filter("mce_external_plugins", "emfw_register_tinymce_javascript");
			add_filter('mce_buttons', 'emfw_register_buttons');
		}
	}

	function emfw_register_buttons($buttons) {
		$buttons[] = "emfw_button";
		return $buttons;
	}
	
	// Localize plugin js
	function emfw_before_reg_mce_js() {

		wp_enqueue_script('emfw_mce_before_script', EMFW__PLUGIN_URL . "tinymce/before_mce.js");
		
		// Prepare default values for ajax calls - styles
		$temp = emfw_get_defaultvalues();
		$sorted = $temp['styles'];
		asort($sorted);
		
		// Prepare default values for ajax calls - MC lists
		require_once( EMFW__PLUGIN_DIR . 'class.emfwmcdata.php' );
		$options = get_option('emfw_options', emfw_get_defaultvalues());
		$mcdata = new EMFWMCData($options['id_number']);
		$listdata = $mcdata->get_alllists();
		$sorted2 = array();
		for ($i = 0; $i < $listdata['total']; $i++) {
			$sorted2[$listdata['data'][$i]['id']] = $listdata['data'][$i]['name'];
		}
		asort($sorted2);
		
		$script_params = array(
			'texts' => array(__('Form title', 'emfw_language_domain'), __('MailChimp list', 'emfw_language_domain'), __('Form style', 'emfw_language_domain'), __('Insert MailChimp Form shortcode', 'emfw_language_domain')),
			'ajaxdata' => array($sorted, $sorted2)
		);
		
		wp_localize_script( 'emfw_mce_before_script', 'emfw_params', $script_params );
		$mcdata = null;
	}

	// Load the EMFW plugin js
	function emfw_register_tinymce_javascript($plugin_array) {
		$plugin_array['emfw_button'] = EMFW__PLUGIN_URL . "tinymce/plugin.js";
		return $plugin_array;
	}

	// init process for button control
	add_action('init', 'emfw_buttonhooks');
	
	// Check the options especially after udating
	function emfw_update_manually_options() {
		$defs = emfw_get_def_styles_array();;
		$checkoptions = get_option('emfw_options', emfw_get_defaultvalues());
		if (count($checkoptions['styles']) != count($defs)) {
			$checkoptions['styles'] = $defs;
			update_option('emfw_options', $checkoptions);
		}
	}
	
	// Prepare data for the settings page table
	function emfw_generate_general_table_data() {
		$retval = array();
		// Prepare default values for ajax calls - MC lists
		require_once( EMFW__PLUGIN_DIR . 'class.emfwmcdata.php' );
		$options = get_option('emfw_options', emfw_get_defaultvalues());
		$mcdata = new EMFWMCData($options['id_number']);
		$listdata = $mcdata->get_alllists();
		for ($i = 0; $i < count($listdata['data']); $i++) {
			$lid = $listdata['data'][$i]['id'];
			$retval[] = array(
				'link_id' => $lid,
				'link_listname' => $listdata['data'][$i]['name'],
				'link_isdoubleoption' => $options['lists'][$lid]['doubleoptin'],
				'link_hideform' => $options['lists'][$lid]['hideform'],
				'link_redirurl' => $options['lists'][$lid]['redirurl'],
			);
		}
		$mcdata = null;
		return $retval;
	}
	
?>