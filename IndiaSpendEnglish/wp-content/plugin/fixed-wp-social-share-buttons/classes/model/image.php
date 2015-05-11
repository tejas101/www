<?php
/**
 * The Image Model Class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Title Model Class.
 *
 * @since  1.2
 * @access public
 *
 */
class WPB_Fixed_Social_Share_Model_Image {

	/**
	 * Returns the title of a page
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @return string
	 */
	public static function get_site_thumbnail() {
		global $post;
		if ( is_singular() && is_a( $post, 'WP_Post' ) && has_post_thumbnail( $post->ID ) ) {
			$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
			$thumbnail         = wp_get_attachment_url( $post_thumbnail_id );
			if ( ! is_string( $thumbnail ) ) {
				return '';
			}

			return $thumbnail;
		}
		return '';
	}

}