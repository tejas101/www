<?php

	require_once(EMFW__PLUGIN_DIR . 'class.emfwmc_list_table.php');

	class EMFWSettingsPage {
		private $options;
		private $list_data;
		private $general_settings_key = 'emfw_general_settings';
		private $stylespreview_settings_key = 'emfw_stylespreview_settings';
		private $plugin_options_key = 'emfw_plugin_options';
		private $plugin_settings_tabs = array();
		
		function __construct() {
			$this->list_data = emfw_generate_general_table_data();
			add_action( 'init', array( $this, 'load_settings' ) );
			add_action( 'admin_init', array( $this, 'register_general_settings' ) );
			add_action( 'admin_init', array( $this, 'register_stylespreview_settings' ) );
			add_action( 'admin_init', array( $this, 'register_dynamic_list_settings' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_menus' ) );
		}
		
		// Load settings from database
		function load_settings() {
			$this->options = get_option('emfw_options', emfw_get_defaultvalues());
			if (empty($this->options) || 
				 (!empty($this->options) && !isset($this->options['id_number'])) || 
				 (!empty($this->options) && isset($this->options['id_number']) && strlen($this->options['id_number']) == 0)) add_action( 'admin_notices', array( $this, 'emfw_admin_notices') );
		}
		
		// Show a notice is API key is not set
		function emfw_admin_notices() {
			echo "<div id='notice' class='updated fade'><p>" . 
				__('Easy MailChimp For Wordpress plugin is not configured yet. Please do it now.', 'emfw_language_domain') . 
				" <a href='" . admin_url( 'options-general.php?page=emfw_plugin_options' ) . "'>" . 
				__("Easy MailChimp For Wordpress Options", "emfw_language_domain") . "</a></p></div>";
		}
		
		// Add general settings page
		function register_general_settings() {
			$this->plugin_settings_tabs[$this->general_settings_key] = 'General settings';
			
			register_setting($this->general_settings_key, "emfw_options", array($this, 'sanitize'));
			add_settings_section( 'section_general', __('General Plugin Settings', 'emfw_language_domain'), array( $this, 'section_general_desc' ), $this->general_settings_key );
			add_settings_field( 'id_number', __('MailChimp API Key', 'emfw_language_domain'), array( $this, 'field_general_option' ), $this->general_settings_key, 'section_general' );
		}
		
		// Add style preview settings page
		function register_stylespreview_settings() {
			$this->plugin_settings_tabs[$this->stylespreview_settings_key] = 'Styles preview';
			
			register_setting( $this->stylespreview_settings_key, "emfw_options", array($this, 'sanitize'));
			add_settings_section( 'section_stylespreview', __('Styles Preview', 'emfw_language_domain'), array( $this, 'section_stylespreview_desc' ), $this->stylespreview_settings_key );
			add_settings_field( 'stylespreview_option', __('Default Styles', 'emfw_language_domain'), array( $this, 'field_stylespreview_option' ), $this->stylespreview_settings_key, 'section_stylespreview' );
		}
		
		// Add settings for each MC lists
		function register_dynamic_list_settings() {
			if (!empty($this->list_data)) {
				for ($i = 0; $i < count($this->list_data); $i++) {
					$listid = $this->list_data[$i]['link_id'];
					register_setting($listid, "emfw_options", array($this, 'sanitize'));
					add_settings_section('section_listinfo_' . $listid, __('List Settings', 'emfw_language_domain'), array( $this, 'section_dynlistinfo_desc' ), $listid);
					add_settings_field('doubleoptin_option', __('Double opt-in?', 'emfw_language_domain'), array( $this, 'field_cb_option' ), $listid, 'section_listinfo_' . $listid, array("name" => "emfw_options[lists][" . $listid . "][doubleoptin]", "value" => $this->options['lists'][$listid]['doubleoptin'], "desc" => __('Select "yes" if you want people to confirm their email address before being subscribed (recommended)', 'emfw_language_domain')));
					add_settings_field('welcomeemail_option', __('Send Welcome Email?', 'emfw_language_domain'), array( $this, 'field_cb_option' ), $listid, 'section_listinfo_' . $listid, array("name" => "emfw_options[lists][" . $listid . "][welcomeemail]", "value" => $this->options['lists'][$listid]['welcomeemail'], "desc" => __('Select "yes" if you want to send your lists Welcome Email if a subscribe succeeds (only when double opt-in is disabled).', 'emfw_language_domain')));
					add_settings_field('updateexisting_option', __('Update existing subscribers?', 'emfw_language_domain'), array( $this, 'field_cb_option' ), $listid, 'section_listinfo_' . $listid, array("name" => "emfw_options[lists][" . $listid . "][updateexisting]", "value" => $this->options['lists'][$listid]['updateexisting'], "desc" => __('Select "yes" if you want to update existing subscribers (instead of showing the "already subscribed" message).', 'emfw_language_domain')));
					add_settings_field('replaceig_option', __('Replace interest groups?', 'emfw_language_domain'), array( $this, 'field_cb_option' ), $listid, 'section_listinfo_' . $listid, array("name" => "emfw_options[lists][" . $listid . "][replaceig]", "value" => $this->options['lists'][$listid]['replaceig'], "desc" => __('Select "yes" if you want to replace the interest groups with the groups provided instead of adding the provided groups to the member\'s interest groups (only when updating a subscriber).', 'emfw_language_domain')));					
					add_settings_field('hideform_option', __('Hide form after successful subscription?', 'emfw_language_domain'), array( $this, 'field_cb_option' ), $listid, 'section_listinfo_' . $listid, array("name" => "emfw_options[lists][" . $listid . "][hideform]", "value" => $this->options['lists'][$listid]['hideform'], "desc" => __('Select "yes" to hide the form fields after a successful sign-up.', 'emfw_language_domain')));
					add_settings_field('redirurl_option', __('Redirect to the following URL after successful subscription', 'emfw_language_domain'), array( $this, 'field_text_option' ), $listid, 'section_listinfo_' . $listid, array("name" => "emfw_options[lists][" . $listid . "][redirurl]", "value" => $this->options['lists'][$listid]['redirurl'], "desc" => __('Leave empty for no redirection. Use complete (absolute) URLs, including http://', 'emfw_language_domain')));
				}
			}			
		}
		
		// Sanitize each settings field if needed
		public function sanitize( $input ) {
			$new_input = array();
			$temp = emfw_get_defaultvalues();
			$new_input['styles'] = $temp['styles'];
			$new_input['lists'] = $temp['lists'];
			if (isset($input['inputtype'])) {
				switch ($input['inputtype']) {
					case 'general':
						if (isset($input['id_number'])) {
							$new_input['id_number'] = sanitize_text_field($input['id_number']);
							$new_input['lists'] = emfw_get_def_lists_array($input['id_number'], true);
						}
						break;
					case 'popup':
						// Data validation
						if (isset($input['mylistid'])) {
							$new_input['id_number'] = $temp['id_number'];
							if (isset($input['lists'][$input['mylistid']])) {
								// There are some "checked" items
								if (isset($input['lists'][$input['mylistid']]['doubleoptin'])) $new_input['lists'][$input['mylistid']]['doubleoptin'] = true; else $new_input['lists'][$input['mylistid']]['doubleoptin'] = false;
								if (isset($input['lists'][$input['mylistid']]['welcomeemail'])) $new_input['lists'][$input['mylistid']]['welcomeemail'] = true; else $new_input['lists'][$input['mylistid']]['welcomeemail'] = false;
								if (isset($input['lists'][$input['mylistid']]['updateexisting'])) $new_input['lists'][$input['mylistid']]['updateexisting'] = true; else $new_input['lists'][$input['mylistid']]['updateexisting'] = false;
								if (isset($input['lists'][$input['mylistid']]['replaceig'])) $new_input['lists'][$input['mylistid']]['replaceig'] = true; else $new_input['lists'][$input['mylistid']]['replaceig'] = false;
								if (isset($input['lists'][$input['mylistid']]['hideform'])) $new_input['lists'][$input['mylistid']]['hideform'] = true; else $new_input['lists'][$input['mylistid']]['hideform'] = false;
								if (isset($input['lists'][$input['mylistid']]['redirurl'])) $new_input['lists'][$input['mylistid']]['redirurl'] = trim($input['lists'][$input['mylistid']]['redirurl']); else $new_input['lists'][$input['mylistid']]['redirurl'] = "";
							}
						}
						break;
				}
			}
			return $new_input;
		}
		
		// Description of the tab pages
		function section_general_desc() { echo __('General settings for MailChimp Forms Plugin. ', 'emfw_language_domain'); }
		function section_stylespreview_desc() { echo __('This is only a preview page of the available styles', 'emfw_language_domain'); }
		function section_dynlistinfo_desc() { echo __('Please set up the list general options. This will affect all the forms you use for this list in this site.', 'emfw_language_domain'); }
		
		// Checkbox fields callback, renders inputs
		function field_cb_option($args) {
			if ($args['value']) $ischecked = "checked";
			else $ischecked = "";
			printf('<input type="checkbox" name="' . $args['name'] . '" value="1" ' . $ischecked . '/>&nbsp; %s', $args['desc']);
		}
		
		// Text fields callback, renders inputs
		function field_text_option($args) {
			printf('<input type="text" name="' . $args['name'] . '" value="%s" /><br/>' . $args['desc'], isset($args['value']) ? esc_attr($args['value']) : '');
		}
						
		// General fields callback, renders inputs
		function field_general_option() {
			printf(
				'<input type="text" id="id_number" name="emfw_options[id_number]" value="%s" /><br/>' . __("You can get more information about MailChimp API key here:", "emfw_language_domain") . " <a href='http://kb.mailchimp.com/article/where-can-i-find-my-api-key/' target='_blank'>" . __("API Key", "emfw_language_domain") . "</a>",
				isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
			);
		}
		
		// Styles preview callback, displays the preview inputs
		function field_stylespreview_option() {
			echo emfw_get_defaultstyles_dropdown();
		}
		
		// Add an options page under Settings
		function add_admin_menus() {
			$hook_suffix = add_options_page(
				__('Easy Mailchimp Options', 'emfw_language_domain'), 
				__('Easy Mailchimp For WP', 'emfw_language_domain'), 
				'manage_options', 
				$this->plugin_options_key, 
				array($this, 'plugin_options_page')
			);
			add_action( 'load-' . $hook_suffix , array( $this, 'emfw_load_function' ) );
		}
		
		public function emfw_load_function() {
			// Current admin page is the options page for our plugin, so do not display the notice
			// (remove the action responsible for this)
			remove_action( 'admin_notices', array( $this, 'emfw_admin_notices') );
		}
		
		// Plugin Options page rendering
		function plugin_options_page() {
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;
			$this->options = get_option('emfw_options', emfw_get_defaultvalues());
			if (!current_user_can('manage_options'))	{
				wp_die( __('You do not have sufficient permissions to access this page.', 'emfw_language_domain') );
			}
			?>
			<div class="wrap">
				<h2>
					<?php
						echo '<img src="' . EMFW__PLUGIN_URL . '/tinymce/plugin.png" alt="' . __('Easy MailChimp Forms Plugin Logo', 'emfw_language_domain') . '"/>&nbsp;';
						echo __('Easy MailChimp Forms Plugin Settings', 'emfw_language_domain');
					?>
				</h2>
				<?php $this->plugin_options_tabs(); ?>
				<form method="post" action="options.php">
					<input type="hidden" name="emfw_options[inputtype]" value="general"/>
					<?php wp_nonce_field( 'update-options' ); ?>
					<?php settings_fields( $tab ); ?>
					<?php do_settings_sections( $tab ); ?>
					<?php if ($tab != $this->stylespreview_settings_key) submit_button(); ?>
				</form>
				<?php
					if ($tab == $this->general_settings_key) {
					 	// Display table of MC lists
						$wp_list_table = new EMFWMC_List_Table($this->list_data);
						$wp_list_table->prepare_items();
						$wp_list_table->display();
						// Popup for list settings
						add_thickbox();
						$this->display_inlineforms($this->list_data);
					}
				?>
			</div>
			<?php
		}
		
		// Display modal popup windows for list settings
		private function display_inlineforms($data) {
			if (!empty($data)) {
				for ($i = 0; $i < count($data); $i++) {
					?>
						<div id="icid_<?php echo $data[$i]['link_id']; ?>" style="display:none;">
							<form method="post" action="options.php">
								<input type="hidden" name="link_id" value="<?php echo $data[$i]['link_id']; ?>"/>
								<input type="hidden" name="emfw_options[inputtype]" value="popup"/>
								<input type="hidden" name="emfw_options[mylistid]" value="<?php echo $data[$i]['link_id']; ?>"/>
								<?php wp_nonce_field( 'update-options' ); ?>
								<?php settings_fields( $data[$i]['link_id'] ); ?>
								<?php do_settings_sections( $data[$i]['link_id'] ); ?>
								<?php submit_button(); ?>
							</form>
						</div>
					<?php
				}
			}
		}
		
		// Renders our tabs in the plugin options page
		function plugin_options_tabs() {
			$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;
			echo '<h2 class="nav-tab-wrapper">';
			foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
			}
			echo '</h2>';
		}
			
}