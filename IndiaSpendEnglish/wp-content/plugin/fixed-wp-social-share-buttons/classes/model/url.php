<?php
/**
 * The UrlModel Class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The UrlModel Class.
 *
 * @since  1.0.0
 * @access public
 *
 */
class WPB_Fixed_Social_Share_Model_Url {

	/**
	 * Returns the URL of a page.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public static function guess_url() {

		/**
		 * Use $wp_the_query instead of the global $post variable to help
		 * customers that themes and/or other plugins make subqueries but do not
		 * reset the query using wp_reset_query()
		 * @var WP_Query $wp_the_query
		 */
		global $wp_the_query;

		if ( isset( $wp_the_query ) && is_a( $wp_the_query, 'WP_Query' ) && $wp_the_query->is_singular() ) {
			return get_permalink( $wp_the_query->post->ID );
		}

		/*
		 * Old approach
		global $post;
		if ( is_a( $post, 'WP_Post' ) ) {
			return get_permalink( $post->ID );
		}*/

		if ( defined( 'WP_SITEURL' ) && '' != WP_SITEURL ) {
			$url = WP_SITEURL;
		}
		else {
			$schema = is_ssl() ? 'https://' : 'http://'; // set_url_scheme() is not defined yet
			$url    = preg_replace( '#/(wp-admin/.*|wp-login.php)#i', '', $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		}

		// this causes issues with some hosts together with the Facebook sharer
		// $url = rtrim( $url, '/' );

		return $url;
	}

}