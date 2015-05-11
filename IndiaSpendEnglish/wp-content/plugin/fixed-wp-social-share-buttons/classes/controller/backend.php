<?php
/**
 * The Backend Controller.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Backend Controller.
 *
 * @since  1.0.0
 * @access public
 *
 */
class WPB_Fixed_Social_Share_Controller_Backend {

	/**
	 * The plugin file name.
	 *
	 * @since  1.0.1
	 * @access public
	 *
	 * @var null|string $plugin_file plugin file.
	 */
	public $plugin_file = null;


	/**
	 * Settings Page Hook
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @var string $settings_page_hook
	 */
	public $settings_page_hook = '';


	/**
	 * Constructor.
	 *
	 * @param null|string $plugin_file The plugin file
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return \WPB_Fixed_Social_Share_Controller_Backend
	 */
	public function __construct( $plugin_file = null ) {
		$this->plugin_file = $plugin_file;

		define( 'WPBFSB_FILE', $plugin_file );

		add_action( 'init', array( $this, 'init_translation' ) );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_filter( 'manage_wpbfsb_posts_columns', array( $this, 'button_posts_columns' ) );

		add_action( 'manage_wpbfsb_posts_custom_column', array( $this, 'button_posts_column' ), 10, 2 );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'load-options.php', array( $this, 'whitelist_options' ) );

		add_filter( 'admin_notices', array( $this, 'install_buttons_notification' ), 10, 2 );

		add_action( 'save_post', array( $this, 'save_metabox' ) );

		add_action( 'admin_print_scripts-edit.php', array( $this, 'admin_print_scripts' ) );

		add_action( 'admin_print_scripts-post.php', array( $this, 'admin_print_scripts' ) );

		add_action( 'admin_print_scripts-post-new.php', array( $this, 'admin_print_scripts' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( $this->plugin_file ), array(
			$this,
			'plugin_action_links',
		) );

		add_action( 'load-edit.php', array( $this, 'new_button_install' ) );

		add_action( 'admin_init', array( $this, 'upgrade' ) );

		add_filter( 'site_transient_update_plugins', array(
			'WPB_Fixed_Social_Share_Model_Update',
			'site_transient_update_plugins',
		) );

		add_filter( 'plugins_api', array( 'WPB_Fixed_Social_Share_Model_Update', 'plugins_api' ), - 100, 3 );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Loads language files
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function init_translation() {

		load_plugin_textdomain( 'wpbfsb', false, trailingslashit( dirname( plugin_basename( $this->plugin_file ) ) ) . 'assets/langs/', $this->plugin_file );

	}


	/**
	 * Adds settings page menu.
	 *
	 * Called by the 'admin_menu' action hook in @see WPB_Fixed_Social_Share_Controller_Backend::__construct().
	 *
	 * @since  1.0.0
	 * @access public
	 * @see    WPB_Fixed_Social_Share_Controller_Backend::__construct()
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		$this->settings_page_hook = add_submenu_page(
			'options-general.php',
			_x( 'Fixed Social Share', 'plugin settings page title', 'wpbfsb' ),
			_x( 'Fixed Social Share', 'plugin settings menu title', 'wpbfsb' ),
			'manage_options',
			'wpbfsb',
			array( $this, 'settings_page_render' )
		);

		add_action( 'load-' . $this->settings_page_hook, array( $this, 'enqueue_settings_scripts' ) );
		add_action( 'load-' . $this->settings_page_hook, array( $this, 'do_custom_actions' ) );

	}


	/**
	 * Settings page render function.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function settings_page_render() {
		$view = new WPB_Fixed_Social_Share_View( 'backend-settings-page' );

		include( trailingslashit( dirname( $this->plugin_file ) ) . 'config.php' );

		$view->current_tab = $current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'layout';

		// register all tab settings
		//$this->register_settings( $wpbfsb_config['settings_page_options']['tabs'], $current_tab );
		$this->register_settings_sections( $wpbfsb_config['settings_page_options']['tabs'], $current_tab, $this->settings_page_hook );

		// get all tabs
		$view->tabs = wp_list_pluck( $wpbfsb_config['settings_page_options']['tabs'], 'label' );

		// the current page hook
		$view->page_hook = $this->settings_page_hook;

		// render everything
		$view->render();
	}


	/**
	 * Enqueue scripts and styles.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 *
	 */
	public function enqueue_settings_scripts() {

		wp_enqueue_style( 'wpbfsb-backend-settings', plugins_url( 'assets/css/backend-settings.css', $this->plugin_file ), array( 'wp-color-picker' ) );
		wp_enqueue_style( 'wpbfsb-frontend', apply_filters( 'wpbfsb_icon_font_url', plugins_url( 'assets/css/wpbfsb-frontend-icons.css', $this->plugin_file ) ) );

		wp_enqueue_script( 'wpbfsb-backend-settings', plugins_url( 'assets/js/backend-settings.js', $this->plugin_file ), array(
			'jquery',
			'wp-color-picker',
		) );

		wp_enqueue_script( 'postbox' );
	}


	/**
	 * Register settings
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array  $settings
	 * @param string $tab
	 *
	 * @return void
	 */
	private function register_settings( $settings, $tab ) {
		$s = wp_list_pluck( $settings[ $tab ]['sections'], 'fields' );

		foreach ( $s as $section_name => $fields ) {

			if ( is_null( $fields ) ) {
				continue;
			}

			//$section_id = 'wpbfsb_' . $tab . '_' . $section_name;
			$page = $this->settings_page_hook . '_' . $tab;

			foreach ( $fields as $field_name => $field ) {
				$sanitize_callback = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '';
				register_setting( $page, 'wpbfsb_' . $section_name . '_' . $field_name, $sanitize_callback );
			}
		}
	}

	/**
	 * Add settings sections
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array  $settings
	 * @param string $tab
	 * @param string $page_hook
	 * @param string $where
	 * @param int    $post_id
	 *
	 * @return void
	 */
	private function register_settings_sections( $settings, $tab, $page_hook, $where = '', $post_id = null ) {

		if ( ! isset( $settings[ $tab ]['sections'] ) ) {
			return;
		}

		$sections = $settings[ $tab ]['sections'];

		$page = $page_hook . '_' . $tab;

		foreach ( $sections as $section_name => $section ) {
			//$metabox_id = $page . '_' . $section_name;
			$section_id = 'wpbfsb_' . $tab . '_' . $section_name;
			add_settings_section( $section_id, '', '', $page );

			if ( isset( $section['metabox_position'] ) ) {

				if ( ! isset( $section['metabox_view'] ) ) {
					$function = function ( $section_id, $page ) use ( $section_id, $page ) {

						echo '<table class="form-table">';
						do_settings_fields( $page, $section_id );
						echo '</table>';
						submit_button( null, 'primary', 'submit', false );
					};
				} else {
					$metabox_view = $section['metabox_view'];
					$function     = function ( $metabox_view ) use ( $metabox_view ) {
						$view = new WPB_Fixed_Social_Share_View( $metabox_view );
						$view->render();
					};
				}

				add_meta_box( 'wpbfsb_metabox_' . $tab . '_' . $section_name, $section['label'], $function, $page_hook . '_' . $tab, $section['metabox_position'], 'default' );
			}

			if ( ! isset( $section['fields'] ) ) {
				continue;
			}

			foreach ( $section['fields'] as $field_name => $field ) {

				if ( ! method_exists( 'WPB_Fixed_Social_Share_Model_Form', $field['type'] ) ) {
					continue;
				}

				$id = 'wpbfsb_' . $section_name . '_' . $field_name;

				if ( 'in_metabox' == $where ) {
					$id = '_' . $id;
				}

				// register_setting() has to be called before that @see WPB_Fixed_Social_Share_Controller_Backend::register_settings()
				add_settings_field(
					$id, // id,
					$field['label'], // title
					array( 'WPB_Fixed_Social_Share_Model_Form', $field['type'] ), // callback
					$page, // page
					$section_id, // section
					$field + array(
						'name'      => $field_name,
						'label_for' => $id,
						'where'     => $where,
						'post_id'   => $post_id,
					) // args
				);
			}
		}

	}


	/**
	 *
	 * Adds columns to the wpbfsb post type edit screen table
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $columns
	 *
	 * @return array
	 */
	public function button_posts_columns( $columns ) {
		return array_slice( $columns, 0, 1, true ) +
		       array( 'icon' => __( 'Icon', 'wpbfsb' ) ) +
		       array_slice( $columns, 1, count( $columns ) - 1, true );

	}


	/**
	 * Fills new columns with content.
	 *
	 * @see    WPB_Fixed_Social_Share_Controller_Backend::button_posts_columns()
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $column
	 * @param int    $post_id
	 *
	 * @return void
	 */
	public function button_posts_column( $column, $post_id ) {
		$icon = get_post_meta( $post_id, '_wpbfsb_button_font-icon', true );
		if ( ! empty( $icon ) ) {
			echo '<span class="wpbfsb-overview-icon ' . esc_attr( $icon ) . '"></span>';

			return;
		}

		$icon = get_post_meta( $post_id, '_wpbfsb_button_icon-url', true );
		if ( ! empty( $icon ) ) {
			echo '<img src="' . esc_url( $icon ) . '" alt="Icon" />';
		}
	}


	/**
	 * Adds meta boxes to the social buttons post type edit screen
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'wpbfsb_settings',
			__( 'Social Button Settings', 'wpbfsb' ),
			array( $this, 'settings_metabox_render' ),
			'wpbfsb'
		);

		add_meta_box(
			'wpbfsb_settings_about',
			__( 'About', 'wpbfsb' ),
			function () {
				$view = new WPB_Fixed_Social_Share_View( 'backend-settings-page-about-metabox' );
				$view->render();
			},
			'wpbfsb',
			'side'
		);

		$get_post_types_args = apply_filters( 'wpbfsb_get_post_types_args', array(
			'public'  => true,
			'show_ui' => true,
		) );

		foreach ( get_post_types( $get_post_types_args, 'names' ) as $post_type ) {
			add_meta_box(
				'wpbfsb_other_posts_metabox_settings',
				__( 'Social Share Button Options', 'wpbfsb' ),
				array( $this, 'posts_metabox_render' ),
				$post_type,
				'side',
				'default'
			);
		}
	}


	/**
	 * Renders the settings meta box for the custom post type 'wpbfsb'
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public function settings_metabox_render( $post ) {

		$view = new WPB_Fixed_Social_Share_View( 'backend-metabox-settings' );

		/**
		 * - color
		 * - icon select
		 * - icon upload
		 * - function select
		 */

		include( trailingslashit( dirname( $this->plugin_file ) ) . 'config.php' );
		$this->register_settings_sections( $wpbfsb_config['metabox_settings_options']['tabs'], 'general', 'wpbfsb_button_metabox', 'in_metabox', $post->ID );

		$view->render();
	}


	/**
	 * Renders the settings meta box for the custom post type 'wpbfsb'
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public function posts_metabox_render( $post ) {
		$view = new WPB_Fixed_Social_Share_View( 'backend-posts-settings-metabox' );

		include( trailingslashit( dirname( $this->plugin_file ) ) . 'config.php' );
		$this->register_settings_sections( $wpbfsb_config['posts_metabox_settings_options']['tabs'], 'general', 'wpbfsb_button_posts_metabox', 'in_metabox', $post->ID );

		$view->render();
	}


	/**
	 * Adds options to the white lists
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function whitelist_options() {
		if ( ! isset( $_REQUEST['option_page'] ) ) {
			return;
		}

		$option_page = $_REQUEST['option_page'];

		if ( false === stripos( $option_page, $this->settings_page_hook ) ) {
			return;
		}

		$tab = str_replace( $this->settings_page_hook . '_', '', $option_page );

		include( trailingslashit( dirname( $this->plugin_file ) ) . 'config.php' );
		$this->register_settings( $wpbfsb_config['settings_page_options']['tabs'], $tab );

	}


	/**
	 * Saves the meta box fields
	 *
	 * @param int $post_id
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function save_metabox( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		include( trailingslashit( dirname( $this->plugin_file ) ) . 'config.php' );

		if ( get_post_type( $post_id ) == 'wpbfsb' ) {
			$tabs = $wpbfsb_config['metabox_settings_options']['tabs'];
			$this->update_metabox_fields( $tabs, $post_id );
		} else {
			$tabs = $wpbfsb_config['posts_metabox_settings_options']['tabs'];
			$this->update_metabox_fields( $tabs, $post_id );
		}
	}


	/**
	 * Updates post meta fields
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param array $tabs
	 * @param int   $post_id
	 */
	private function update_metabox_fields( $tabs, $post_id ) {
		foreach ( $tabs as $tab ) {
			foreach ( $tab['sections'] as $section_name => $section ) {
				foreach ( $section['fields'] as $field_id => $field ) {

					$id = '_wpbfsb_' . $section_name . '_' . $field_id;

					// checkboxes are not in the _REQUEST variable if they are not checked, so we have to fill it
					if ( ! isset( $_REQUEST[ $id ] ) && 'checkbox' == $field['type'] ) {
						$_REQUEST[ $id ] = 0;
					} elseif ( ! isset( $_REQUEST[ $id ] ) ) {
						delete_post_meta( $post_id, $id );
						continue;
					}

					//$value = isset( $field['default'] ) ? $field['default'] : $_REQUEST[$id];
					$value = $_REQUEST[ $id ];

					if ( isset( $field['sanitize_callback'] ) && ! empty( $field['sanitize_callback'] ) ) {
						$value = call_user_func_array( $field['sanitize_callback'], array( $value ) );
					}

					update_post_meta( $post_id, $id, $value );

				}
			}
		}
	}


	/**
	 * Prints Scripts on edit.php and post-new.php
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function admin_print_scripts() {
		global $typenow;

		if ( isset( $typenow ) && 'wpbfsb' == $typenow ) {
			wp_enqueue_script( 'wpbfsb-backend-settings', plugins_url( 'assets/js/backend-settings.js', $this->plugin_file ), array(
				'jquery',
				'wp-color-picker',
				'media-upload',
			) );

			$icon_font_css_files = apply_filters(
				'wpbfsb_icon_files',
				array(
					'wpbfsb-frontend-icons' => apply_filters( 'wpbfsb_icon_font_url', plugins_url( 'assets/css/wpbfsb-frontend-icons.css', $this->plugin_file ) )
				)
			);

			foreach ( $icon_font_css_files as $icon_file_handle => $icon_font_css_file_url ) {
				wp_enqueue_style( $icon_file_handle, $icon_font_css_file_url );
			}

			wp_enqueue_style( 'wpbfsb-backend-settings', plugins_url( 'assets/css/backend-settings.css', $this->plugin_file ), array( 'wp-color-picker' ) );
			wp_enqueue_style( 'wpbfsb-frontend', plugins_url( 'assets/css/wpbfsb-frontend.css', $this->plugin_file ), array_keys( $icon_font_css_files ) );

			wp_enqueue_media();
		}
	}


	/**
	 * Adds some more links to the plugins-overview
	 *
	 * @param array $links
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 *
	 */
	public function plugin_action_links( $links ) {

		//$links[] = '<a style="color:#a00;" href="' . admin_url( 'options-general.php?page=wpbfsb' ) . '">' . __( 'Uninstall', 'wpbfsb' ) . '</a>';
		$links[] = '<a href="' . admin_url( 'options-general.php?page=wpbfsb' ) . '">' . __( 'Settings', 'wpbfsb' ) . '</a>';
		$links[] = '<a href="http://wp-buddy.com">' . __( 'More cool stuff by WP-Buddy', 'wpbfsb' ) . '</a>';

		return $links;
	}


	/**
	 * generates a notification to install new buttons, if available
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return void
	 */
	public function install_buttons_notification() {
		global $typenow;

		if ( 'wpbfsb' != $typenow ) {
			return;
		}

		$new_buttons = $this->new_button_availability();

		if ( $new_buttons <= 0 ) {
			return;
		}

		?>
		<div class="updated">
			<p>
				<?php
				_e( 'There are new buttons available. Do you want to install them now?', 'wpbfsb' );
				echo ' <a class="button" href="' . esc_url( admin_url( 'edit.php?post_type=wpbfsb&wpbfsb_action=new_buttons_install' ) ) . '">' . __( 'Yes' ) . '</a>';
				?>

			</p>
		</div>
	<?php
	}


	/**
	 * Returns the number of new buttons that can be installed
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return int
	 */
	public function new_button_availability() {
		global $wpdb;

		if ( ! is_a( $wpdb, 'wpdb' ) ) {
			return 0;
		}

		include( trailingslashit( dirname( $this->plugin_file ) ) . 'config.php' );

		$button_list = wp_list_pluck( $wpbfsb_config['buttons'], 'title' );

		$san_titles = array_keys( $button_list );

		$target_count = count( $san_titles );

		$san_titles = array_map( function ( $value ) {
			return '"' . $value . '"';
		}, $san_titles );

		$is_count = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->postmeta . ' WHERE meta_key = "_wpbfsb_title" AND meta_value IN (' . implode( ',', $san_titles ) . ')' );

		return $target_count - $is_count;

	}


	/**
	 * Installs new buttons
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function new_button_install() {
		global $typenow;

		if ( 'wpbfsb' != $typenow ) {
			return;
		}

		if ( ! isset( $_GET['wpbfsb_action'] ) ) {
			return;
		}

		if ( 'new_buttons_install' != $_GET['wpbfsb_action'] ) {
			return;
		}

		global $wpdb;

		if ( ! is_a( $wpdb, 'wpdb' ) ) {
			return;
		}

		$values_in_db = $wpdb->get_var( 'SELECT GROUP_CONCAT(meta_value) FROM ' . $wpdb->postmeta . ' WHERE meta_key = "_wpbfsb_title"' );

		$values_in_db = explode( ',', $values_in_db );

		if ( ! is_array( $values_in_db ) ) {
			return;
		}

		include( trailingslashit( dirname( $this->plugin_file ) ) . 'config.php' );

		$button_list = wp_list_pluck( $wpbfsb_config['buttons'], 'title' );

		$san_titles = array_keys( $button_list );

		$diff = array_diff( $san_titles, $values_in_db );

		foreach ( $diff as $title ) {
			if ( ! isset( $wpbfsb_config['buttons'][ $title ] ) ) {
				continue;
			}

			$post_id = wp_insert_post( array(
				'post_type'   => 'wpbfsb',
				'post_title'  => $wpbfsb_config['buttons'][ $title ]['title'],
				'post_status' => 'publish',
				'menu_order'  => $wpbfsb_config['buttons'][ $title ]['menu_order'],
			) );

			if ( ! is_int( $post_id ) ) {
				continue;
			}

			foreach ( $wpbfsb_config['buttons'][ $title ]['post_meta'] as $meta_key => $meta_value ) {
				update_post_meta( $post_id, $meta_key, $meta_value );
			}
		}

		wp_redirect( admin_url( 'edit.php?post_type=wpbfsb' ) );

	}


	/**
	 * Updates the plugin
	 *
	 * @since  1.3.0
	 * @access private
	 *
	 * @return void
	 */
	public function upgrade() {
		global $wpdb;

		$old_version = get_option( 'wpbfsb_version', '' );
		if ( empty( $old_version ) ) {
			/**
			 * This plugin has never been installed before
			 * OR
			 * a newer version > 1.3.0 has been installed
			 * OR
			 * has been uninstalled and installed again
			 */

			include( trailingslashit( dirname( $this->plugin_file ) ) . 'config.php' );

			$results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->postmeta . ' WHERE meta_key="_wpbfsb_button_font-icon" AND meta_value NOT LIKE "wpbfsb-icon-%"' );

			if ( $results ) {

				$icons = wp_list_pluck( wp_list_pluck( $wpbfsb_config['buttons'], 'post_meta' ), '_wpbfsb_button_font-icon' );
				$icons = array_flip( $icons );

				foreach ( $results as $res ) {
					if ( isset( $icons[ 'wpbfsb-icon-' . $res->meta_value ] ) ) {
						// rename
						update_post_meta( $res->post_id, '_wpbfsb_button_font-icon', 'wpbfsb-icon-' . $res->meta_value );
					}
				}
			}

			$plugin_data     = get_plugin_data( $this->plugin_file );
			$current_version = $plugin_data['Version'];

			update_option( 'wpbfsb_version', $current_version );
		}
	}


	/**
	 * Shows admin messages.
	 *
	 * @since  1.4.0
	 * @access public
	 *
	 * @return void
	 */
	public function admin_notices() {

		global $pagenow, $typenow;

		if ( wp_count_posts( 'wpbfsb' )->publish <= 0 && $pagenow != 'edit.php' ) {
			?>
			<div class="updated error">
				<p>
					<?php echo sprintf( __( 'There are no social buttons installed. Please go the <a href="%s">social buttons page</a> and create some buttons.', 'wpbfsb' ), admin_url( 'edit.php?post_type=wpbfsb' ) ); ?>
				</p>
			</div>
		<?php
		}

		if ( $pagenow == 'edit.php' && $typenow == 'wpbfsb' ) {
			?>
			<div class="updated">
				<p>
					<?php echo sprintf( __( 'Searching for more options? Please go to the <a href="%s">settings page</a> of the plugin to configure it.', 'wpbfsb' ), admin_url( 'options-general.php?page=wpbfsb' ) ); ?>
				</p>
			</div>
		<?php
		}

	}


	/**
	 * Do custom actions.
	 *
	 * @since  1.5.0
	 * @access public
	 *
	 * @return void
	 */
	public function do_custom_actions() {
		$action = filter_input( INPUT_GET, 'wpbfsb_action', FILTER_SANITIZE_STRING );
		if ( empty( $action ) ) {
			return;
		}

		switch ( $action ) {
			case 'flushcache':
				global $wpdb;

				$items_deleted = $wpdb->query( 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "wpbfsb_%" AND option_value LIKE "a:6:{s:9:\"timestamp\";%"' );

				if ( is_int( $items_deleted ) ) {
					add_settings_error(
						'flushcache',
						0,
						sprintf( _nx( 'One item has been deleted from the cache', '%s items have been deleted from the cache', $items_deleted, '%s is the number of items', 'wpbfsb' ), $items_deleted ),
						'updated'
					);
				} else {
					add_settings_error( 'flushcache', 0, __( 'Could not flush the cache. Maybe there is nothing to delete.', 'wpbfsb' ) );
				}

				break;
		}

	}

}