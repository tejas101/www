<?php
/**
 * The Title Model Class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Title Model Class.
 *
 * @since  1.0.0
 * @access public
 *
 */
class WPB_Fixed_Social_Share_Model_Title {

	/**
	 * Returns the title of a page
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function get_site_title() {
		global $post;
		if ( is_singular() && is_a( $post, 'WP_Post' ) ) {
			$site_title = get_the_title( $post->ID );
		}
		else {
			$site_title = get_bloginfo( 'name' );
		}
		return $site_title;
	}

}