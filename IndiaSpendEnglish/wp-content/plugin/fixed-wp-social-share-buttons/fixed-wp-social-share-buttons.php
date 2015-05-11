<?php
/*
Plugin Name: Fixed WordPress Social Share Buttons by WP-Buddy
Plugin URI: http://wp-buddy.com/products/plugins/fixed-wordpress-social-share-buttons/
Description: A social share plugin for WordPress that fixes buttons to a side and protects the users privacy!
Version: 1.6.0
Author: WPBuddy
Author URI: http://wp-buddy.com
Text Domain: wpbfsb
Domain Path: /assets/langs/
*/

__( 'A social share plugin for WordPress that fixes buttons to a side and protects the users privacy!', 'wpbfsb' );

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	$plugin_data = get_plugin_data( __FILE__ );
	wp_die( sprintf( __( 'You are using PHP in version %1$s. This version is outdated and cannot be used with the %2$s plugin. Please update to the latest PHP version in order to use this plugin. You can ask your provider on how to do this.' ), PHP_VERSION, '<strong>' . $plugin_data['Name'] . '</strong>' ) );
}

if ( ! function_exists( 'wpbfsb_autoloader' ) ) {
	/**
	 * The autoloader class
	 *
	 * @param string $class_name
	 *
	 * @return bool
	 * @since 1.0
	 */
	function wpbfsb_autoloader( $class_name ) {

		// do not include classes that already exist
		if ( class_exists( $class_name ) ) {
			return true;
		}

		$file_name = str_replace( 'wpb_fixed_social_share_', '', strtolower( $class_name ) );
		$file_name = str_replace( '_', '/', $file_name );

		$file = trailingslashit( dirname( __FILE__ ) ) . 'classes/' . $file_name . '.php';
		if ( is_file( $file ) ) {
			require_once( $file );
			return true;
		}

		return false;
	}
}

// registering the autoloader function
try {
	spl_autoload_register( 'wpbfsb_autoloader', true );
} catch ( Exception $e ) {
	/**
	 * Autoload function.
	 *
	 * @param $class_name
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function __autoload( $class_name ) {
		wpbfsb_autoloader( $class_name );
	}
}

define( 'WPBFSB_SHARE_INFO_NONE', 0 );
define( 'WPBFSB_SHARE_INFO_TEXT', 1 );
define( 'WPBFSB_SHARE_INFO_TOOLTIP', 2 );

new WPB_Fixed_Social_Share_Model_Posttypes();

if ( is_admin() ) {
	new WPB_Fixed_Social_Share_Controller_Backend( __FILE__ );
}
else {
	new WPB_Fixed_Social_Share_Controller_Frontend( __FILE__ );
}


