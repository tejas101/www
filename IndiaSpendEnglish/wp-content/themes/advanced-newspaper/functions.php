<?php
	// DIRECTORIES
	define('OF_FILEPATH', TEMPLATEPATH);
	define('OF_DIRECTORY', get_template_directory_uri());

	define('GABFIRE_INC_PATH', TEMPLATEPATH . '/inc');
	define('GABFIRE_FRAMEWORK_PATH', TEMPLATEPATH . '/framework');
	define('GABFIRE_INC_DIR', get_template_directory_uri() . '/inc');
	define('GABFIRE_FUNCTIONS_PATH', TEMPLATEPATH . '/inc/functions');
	define('GABFIRE_JS_DIR', get_template_directory_uri() . '/inc/js');
	
	// OPTION PANEL	
	if ( !function_exists( 'optionsframework_init' ) ) {
		define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/framework/admin/' );
		require_once dirname( __FILE__ ) . '/framework/admin/options-framework.php';
	}	
	
	/* This builds dashboard menu */
	require_once (GABFIRE_FRAMEWORK_PATH . '/admin/admin-menu.php');

	require_once (GABFIRE_INC_PATH . '/theme-js.php'); // Load theme Javascripts
	require_once (GABFIRE_INC_PATH . '/theme-comments.php');	// Load custom comments template
	require_once (GABFIRE_INC_PATH . '/widgetize-theme.php'); // Register sidebars
	require_once (GABFIRE_INC_PATH . '/I18n-functions.php'); // localization support
	require_once (GABFIRE_INC_PATH . '/post-thumbnails.php'); // Load theme thumbnails
	require_once (GABFIRE_INC_PATH . '/script-init.php'); // Javascript init
	require_once (GABFIRE_INC_PATH . '/theme-cpt.php'); // Custom post type and taxonomies (CPT is used with CMS themes only)
	require_once (GABFIRE_INC_PATH . '/custom-fields.php'); // Breadcrumb function
	
	// FRAMEWORK FILES
	require_once (GABFIRE_FRAMEWORK_PATH . '/functions/breadcrumb.php'); // Breadcrumb function
	require_once (GABFIRE_FRAMEWORK_PATH . '/functions/misc-functions.php'); // Misc theme functions
	require_once (GABFIRE_FRAMEWORK_PATH . '/functions/dashboard-widget.php'); // Gabfire Themes RSS widget for WP Dashboard
	require_once (GABFIRE_FRAMEWORK_PATH . '/functions/gabfire-media.php'); // Gabfire Media Module
	require_once (GABFIRE_FRAMEWORK_PATH . '/functions/gabfire-widgets.php'); // Custom gabfire widgets

	/* Add custom navigation support
	 * For header navigations check the core files at
	 * inc/functions/misc-functions file
	 */
	register_nav_menus(array(
		'masthead' => 'Masthead',
		'primary-navigation' => 'Primary',
		'secondary-navigation' => 'Secondary',
		'footer-nav1' => 'Footer Navigation - #1',
		'footer-nav2' => 'Footer Navigation - #2'
	));

	/* Redirecting to Homepage */
	add_action('wp_logout','go_home');
	function go_home(){
	wp_redirect( home_url() );
	exit();
	}
	
// Creating the widget for Home Splash
class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'wpb_widget', 

// Widget name will appear in UI
__('Home Splash', 'wpb_widget_domain'), 

// Widget description
array( 'description' => __( 'Homepage splash for sign up', 'wpb_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
// This is where you run the code and display the output
if(is_home()):
echo __( '<p><script type="text/javascript">// <![CDATA[
jQuery(document).ready(function() {
   jQuery("#clicksconnected").click();
});
// ]]></script></p>', 'wpb_widget_domain' );
endif;
echo $args['after_widget'];
}
		
// Widget Backend 

} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

add_filter('the_content', 'mte_add_incontent_ad');
function mte_add_incontent_ad($content)   
{	if(is_single()){
		$content_block = explode('<p>',$content);
		if(!empty($content_block[4]))
		{	$content_block[4] .= '<div style="width:625px;margin-top:15px"><div id="InRead"></div></div>';
		}
		for($i=1;$i<count($content_block);$i++)
		{	$content_block[$i] = '<p>'.$content_block[$i];
		}
		$content = implode('',$content_block);
	}
	return $content;	
}
function bac_PostViews($post_ID) {
 
    //Set the name of the Posts Custom Field.
    $count_key = 'post_views_count'; 
     
    //Returns values of the custom field with the specified key from the specified post.
    $count = get_post_meta($post_ID, $count_key, true);
     
    //If the the Post Custom Field value is empty. 
    if($count == ''){
        $count = 0; // set the counter to zero.
         
        //Delete all custom fields with the specified key from the specified post. 
        delete_post_meta($post_ID, $count_key);
         
        //Add a custom (meta) field (Name/value)to the specified post.
        add_post_meta($post_ID, $count_key, '0');
        return $count . ' ';
     
    //If the the Post Custom Field value is NOT empty.
    }else{
        $count++; //increment the counter by 1.
        //Update the value of an existing meta key (custom field) for the specified post.
        update_post_meta($post_ID, $count_key, $count);
         
        //If statement, is just to have the singular form 'View' for the value '1'
        if($count == '1'){
        return $count . ' ';
        }
        //In all other cases return (count) Views
        else {
        return $count . ' ';
        }
    }
}