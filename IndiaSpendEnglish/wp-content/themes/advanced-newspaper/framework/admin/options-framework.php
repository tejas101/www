<?php
/*
Plugin Name: Options Framework
Plugin URI: http://www.wptheming.com
Description: A framework for building theme options.
Version: 0.8
Author: Devin Price
Author URI: http://www.wptheming.com
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* Basic plugin definitions */

define('OPTIONS_FRAMEWORK_VERSION', '0.8');

/* Make sure we don't expose any info if called directly */

if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a little plugin, don't mind me.";
	exit;
}

/* If the user can't edit theme options, no use running this plugin */

add_action('init', 'optionsframework_rolescheck' );

function optionsframework_rolescheck () {
	if ( current_user_can( 'edit_theme_options' ) ) {
		// If the user can edit theme options, let the fun begin!
		add_action( 'admin_menu', 'optionsframework_add_page');
		add_action( 'admin_init', 'optionsframework_init' );
		add_action( 'admin_init', 'optionsframework_mlu_init' );
	}
}

/* Loads the file for option sanitization */

add_action('init', 'optionsframework_load_sanitization' );

function optionsframework_load_sanitization() {
	require_once dirname( __FILE__ ) . '/options-sanitize.php';
}

/* 
 * Creates the settings in the database by looping through the array
 * we supplied in options.php.  This is a neat way to do it since
 * we won't have to save settings for headers, descriptions, or arguments.
 *
 * Read more about the Settings API in the WordPress codex:
 * http://codex.wordpress.org/Settings_API
 *
 */

function optionsframework_init() {

	// Include the required files
	require_once dirname( __FILE__ ) . '/options-interface.php';
	require_once dirname( __FILE__ ) . '/options-medialibrary-uploader.php';
	
	// Loads the options array from the theme
	if ( $optionsfile = load_template(TEMPLATEPATH . '/inc/theme-options.php') ) {
		require_once($optionsfile);
	}
	else if (file_exists( dirname( __FILE__ ) . '/theme-options.php' ) ) {
		require_once dirname( __FILE__ ) . '/theme-options.php';
	}
	
	$optionsframework_settings = get_option('optionsframework' );
	
	// Updates the unique option id in the database if it has changed
	optionsframework_option_name();
	
	// Gets the unique id, returning a default if it isn't defined
	if ( isset($optionsframework_settings['id']) ) {
		$option_name = $optionsframework_settings['id'];
	}
	else {
		$option_name = 'optionsframework';
	}
	
	// If the option has no saved data, load the defaults
	if ( ! get_option($option_name) ) {
		optionsframework_setdefaults();
	}
	
	// Registers the settings fields and callback
	register_setting( 'optionsframework', $option_name, 'optionsframework_validate' );
}

/* 
 * Adds default options to the database if they aren't already present.
 * May update this later to load only on plugin activation, or theme
 * activation since most people won't be editing the admin/theme-options.php
 * on a regular basis.
 *
 * http://codex.wordpress.org/Function_Reference/add_option
 *
 */

function optionsframework_setdefaults() {
	
	$optionsframework_settings = get_option('optionsframework');

	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	/* 
	 * Each theme will hopefully have a unique id, and all of its options saved
	 * as a separate option set.  We need to track all of these option sets so
	 * it can be easily deleted if someone wishes to remove the plugin and
	 * its associated data.  No need to clutter the database.  
	 *
	 */
	
	if ( isset($optionsframework_settings['knownoptions']) ) {
		$knownoptions =  $optionsframework_settings['knownoptions'];
		if ( !in_array($option_name, $knownoptions) ) {
			array_push( $knownoptions, $option_name );
			$optionsframework_settings['knownoptions'] = $knownoptions;
			update_option('optionsframework', $optionsframework_settings);
		}
	} else {
		$newoptionname = array($option_name);
		$optionsframework_settings['knownoptions'] = $newoptionname;
		update_option('optionsframework', $optionsframework_settings);
	}
	
	// Gets the default options data from the array in admin/theme-options.php
	$options = optionsframework_options();
	
	// If the options haven't been added to the database yet, they are added now
	$values = of_get_default_values();
	
	if ( isset($values) ) {
		add_option( $option_name, $values ); // Add option with default settings
	}
}

/* Add a subpage called "Theme Options" to the appearance menu. */

if ( !function_exists( 'optionsframework_add_page' ) ) {
function optionsframework_add_page() {

	
}
}

/* Loads the CSS */

function optionsframework_load_styles() {
	wp_enqueue_style('admin-style', OPTIONS_FRAMEWORK_DIRECTORY.'css/admin-style.css');
	wp_enqueue_style('color-picker', OPTIONS_FRAMEWORK_DIRECTORY.'css/colorpicker.css');
		$_html = '';
		$_html .= '<link rel="stylesheet" href="' . get_option('siteurl') . '/' . WPINC . '/js/thickbox/thickbox.css" type="text/css" media="screen" />' . "\n";
		$_html .= '<script type="text/javascript">
		var tb_pathToImage = "' . get_option('siteurl') . '/' . WPINC . '/js/thickbox/loadingAnimation.gif";
	    var tb_closeImage = "' . get_option('siteurl') . '/' . WPINC . '/js/thickbox/tb-close.png";
	    </script>' . "\n";
	    
	    echo $_html;
}	

/* Loads the javascript */

function optionsframework_load_scripts() {

	// Inline scripts from options-interface.php
	add_action('admin_head', 'of_admin_head');
	
	// Enqueued scripts
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('color-picker', OPTIONS_FRAMEWORK_DIRECTORY.'js/colorpicker.js', array('jquery'));
	wp_enqueue_script('options-custom', OPTIONS_FRAMEWORK_DIRECTORY.'js/options-custom.js', array('jquery'));
	wp_register_script( 'of-medialibrary-uploader', OPTIONS_FRAMEWORK_DIRECTORY .'js/of-medialibrary-uploader.js', array( 'jquery', 'thickbox' ) );
	wp_enqueue_script( 'of-medialibrary-uploader' );
	wp_enqueue_script( 'media-upload' );
}

function of_admin_head() {

	// Hook to add custom scripts
	do_action( 'optionsframework_custom_scripts' );
}

/* 
 * Builds out the options panel.
 *
 * If we were using the Settings API as it was likely intended we would use
 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
 * we'll call our own custom optionsframework_fields.  See options-interface.php
 * for specifics on how each individual field is generated.
 *
 * Nonces are provided using the settings_fields()
 *
 */
function gab_adminheader() { ?>
		<div id="panelheader">
			<div id="branding">
				<a href="http://www.gabfirethemes.com/" />
					<img src="<?php echo get_template_directory_uri(); ?>/framework/admin/images/logo.png" alt="" />
				</a>
			</div>
			<div class="header-info">
				<?php
					$themename = wp_get_theme();
					$t_name = $themename->{'Name'};
					$t_version = $themename->{'Version'};
					$t_name = str_replace("-", " ", $t_name);
					echo ucwords($t_name) . '&nbsp;' . $t_version;
				?>
			</div>
		</div>
		
<?php }
 
if ( !function_exists( 'optionsframework_page' ) ) {
function optionsframework_page() {
	$return = optionsframework_fields();
	settings_errors();
	?>
    
	<div class="wrap">
		<div class="metabox-holder">
		
			<?php gab_adminheader(); ?>
			
			<div id="optionsframework" class="postbox">
				<div id="panelcontrol">
					<h2 class="nav-tab-wrapper">
						<?php echo $return[1]; ?>
					</h2>
				</div>
					
				<form action="options.php" method="post">
					<div id="panelform">
						<?php settings_fields('optionsframework'); ?>
						<?php echo $return[0]; /* Settings */ ?>					
					</div>
				
					<div class="clear"></div>
					<div id="optionsframework-submit">
						<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options' ); ?>" />
						<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme settings will be lost!', 'optionsframework' ) ); ?>' );" />
						<div class="clear"></div>
					</div>
				</form>
			</div> <!-- / #container -->
		</div>
	</div> <!-- / .wrap -->

<?php
}
}

/** 
 * Validate Options.
 *
 * This runs after the submit/reset button has been clicked and
 * validates the inputs.
 *
 * @uses $_POST['reset']
 * @uses $_POST['update']
 */
function optionsframework_validate( $input ) {

	/*
	 * Restore Defaults.
	 *
	 * In the event that the user clicked the "Restore Defaults"
	 * button, the options defined in the theme's admin/theme-options.php
	 * file will be added to the option for the active theme.
	 */
	 
	if ( isset( $_POST['reset'] ) ) {
		add_settings_error( 'options-framework', 'restore_defaults', __( 'Default options restored.', 'optionsframework' ), 'updated fade' );
		return of_get_default_values();
	}

	/*
	 * Udpdate Settings.
	 */
	 
	if ( isset( $_POST['update'] ) ) {
		$clean = array();
		$options = optionsframework_options();
		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {
				continue;
			}

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			$id = preg_replace( '/\W/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = '0';
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = '0';
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'of_sanitize_' . $option['type'], $input[$id], $option );
			}
		}

		add_settings_error( 'options-framework', 'save_options', __( 'Options saved.', 'optionsframework' ), 'updated fade' );
		return $clean;
	}

	/*
	 * Request Not Recognized.
	 */
	
	return of_get_default_values();
}

/**
 * Format Configuration Array.
 *
 * Get an array of all default values as set in
 * admin/theme-options.php. The 'id','std' and 'type' keys need
 * to be defined in the configuration array. In the
 * event that these keys are not present the option
 * will not be included in this function's output.
 *
 * @return    array     Rey-keyed options configuration array.
 *
 * @access    private
 */
 
function of_get_default_values() {
	$output = array();
	$config = optionsframework_options();
	foreach ( (array) $config as $option ) {
		if ( ! isset( $option['id'] ) ) {
			continue;
		}
		if ( ! isset( $option['std'] ) ) {
			continue;
		}
		if ( ! isset( $option['type'] ) ) {
			continue;
		}
		if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
			$output[$option['id']] = apply_filters( 'of_sanitize_' . $option['type'], $option['std'], $option );
		}
	}
	return $output;
}

/**
 * Add Theme Options menu item to Admin Bar.
 */
 

if ( ! function_exists( 'of_get_option' ) ) {

	/**
	 * Get Option.
	 *
	 * Helper function to return the theme option value.
	 * If no value has been saved, it returns $default.
	 * Needed because options are saved as serialized strings.
	 */
	 
	function of_get_option( $name, $default = false ) {
		$config = get_option( 'optionsframework' );

		if ( ! isset( $config['id'] ) ) {
			return $default;
		}

		$options = get_option( $config['id'] );

		if ( isset( $options[$name] ) ) {
			return $options[$name];
		}

		return $default;
	}
}
// STYLES
	function dnomia_head_css() {		
			$output = '';
			$output = of_get_option('of_custom_css');
			// MENU POSITION
			if ( of_get_option('menu_position') ) {
				$output .= "#access {clear:both; float:left;}\n";
				$output .= "#access li {margin-left:0; margin-right:2.8em;}\n";
			}
			// TAGLINE
			if ( of_get_option('tagline') ) {
				$output .= "#access {margin-top:25px;}\n";
			}
			// OUTPUT STYLES
			if ($output <> '') {
				$output = "<!-- Custom Styling -->\n<style type=\"text/css\">\n".$output."</style>\n";
				echo $output;
			}
	}
	add_action('wp_head', 'optionsframework_wp_head');
	
// DESIGN FUNCTIONS
	// ALTERNATIVE STYLESHEET
	if (!function_exists('optionsframework_wp_head')) {
		function optionsframework_wp_head() { 
			//Stylesheets Reader
			$alt_stylesheet_path = OF_FILEPATH . '/styles/';
			$alt_stylesheets = array();
			if ( is_dir($alt_stylesheet_path) ) {
				if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
					while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
						if(stristr($alt_stylesheet_file, ".css") !== false) {
							$alt_stylesheets[] = $alt_stylesheet_file;
						}
					}    
				}
			}
			// STYLES
			 if(!isset($alt_stylesheets))
				$style = ''; 
			 else 
				$style = $alt_stylesheets;
			 if ($style != '') {
				  @$GLOBALS['stylesheet'] = $style[of_get_option('of_alt_stylesheet')];
				  echo '<link href="'. OF_DIRECTORY .'/styles/'. $GLOBALS['stylesheet'].'" rel="stylesheet" type="text/css" />'."\n"; 
			 } else { 
				   echo '<link href="'. OF_DIRECTORY .'/styles/default.css" rel="stylesheet" type="text/css" />'."\n";         		  
			 }
			// This prints out the custom css and specific styling options
			dnomia_head_css();
		}
	}

// Options - Allow tags : img
add_action('admin_init','optionscheck_change_santiziation', 100);
 
function optionscheck_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'of_sanitize_textarea_custom' );
}
 
function of_sanitize_textarea_custom($input) {
    global $allowedposttags;

        $of_custom_allowedtags["img"] = array(
		"src" => array(),
		"type" => array(),
		"alt" => array(),
		"class" => array(),
		"id" => array(),
		"height" => array(),
		"width" => array()
		);
		
		$of_custom_allowedtags["div"] = array(
		"style" => array(),
		"class" => array(),
		"id" => array(),
		"height" => array(),
		"width" => array()
		);	

		$of_custom_allowedtags["iframe"] = array(
		"style" => array(),
		"class" => array(),
		"id" => array(),
		"height" => array(),
		"width" => array(),
		"align" => array(),
		"frameborder" => array(),
		"scrolling" => array(),
		"src" => array(),
		"marginheight" => array(),
		"marginwidth" => array(),
		"name" => array()
		);			
		
        $of_custom_allowedtags["a"] = array(
            "class" => array(),
            "href" => array(),
			"id" => array(),
			"title" => array(),
			"rel" => array(),
            "rev" => array(),
			"name" => array(),
			"target" => array()
		);
		
        $of_custom_allowedtags["script"] = array(
             "type" => array(),
			 "src" => array(),
             "allowfullscreen" => array(),
             "allowscriptaccess" => array(),
             "height" => array(),
			 "width" => array(),
			"width" => array()
		);

        $of_custom_allowedtags["object"] = array(
             "type" => array(),
			 "data" => array(),
			 "src" => array(),
             "allowfullscreen" => array(),
             "allowscriptaccess" => array(),
             "height" => array(),
			 "width" => array()
		);
		
        $of_custom_allowedtags["param"] = array(
             "name" => array(),
			 "value" => array()
		);
 
        $of_custom_allowedtags = array_merge($of_custom_allowedtags, $allowedposttags);
        $output = wp_kses( $input, $of_custom_allowedtags);

    return $output;
}

function loadtheme_init() {
	if (of_get_option('of_alt_stylesheet') == '' and !is_admin() ) {
		echo '<p class="notice" style="width:800px;margin:20px auto">Congratulations! You have successfully installed your theme. However, it may look incomplete at this moment. Do <strong>NOT</strong> panic as you simply need to configure your <a href="'. get_option('siteurl') .'/wp-admin/admin.php?page=gabfire_theme">Theme Options</a>. Please go through the <a href="'. get_option('siteurl') .'/wp-admin/admin.php?page=gabfire_theme">Theme Options</a> completely and select an option for each setting. After that, you\'re site will be ready for the world!
			<link rel="stylesheet" type="text/css" media="all" href="'.get_template_directory_uri().'/styles/default.css" />';
	}
}
add_action('wp_head', 'loadtheme_init');