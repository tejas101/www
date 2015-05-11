<?php
/**
 * The Remote Model Class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Remote Model Class.
 *
 * @since  1.0.1
 * @access public
 *
 */
class WPB_Fixed_Social_Share_Model_Remote {


	/**
	 * Retrieves the current URL
	 *
	 * @param string $url
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return bool|string
	 */
	public static function get_current_url( $url = null ) {
		// fetch the current post permalink if no url was given
		global $post;
		if ( is_null( $url ) && $post instanceof WP_Post ) {
			$url = get_permalink( $post->ID );
		}
		elseif ( is_null( $url ) ) {
			$url = WPB_Fixed_Social_Share_Model_Url::guess_url();
		}

		return $url;
	}

	/**
	 * Retrieves the +1's of a link
	 *
	 * @param null|string $url
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return int
	 *
	 */
	public static function get_google_plusone_statistics( $url = null ) {

		$url = self::get_current_url( $url );

		$parsed_results = self::remote( 'google_plus_likes', $url, 'https://clients6.google.com/rpc', array(
			'method'  => 'post',
			'headers' => array(
				'Content-type' => 'application/json'
			),
			'body'    => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]'
		) );

		if ( isset( $parsed_results[0]['result']['metadata']['globalCounts']['count'] ) ) {
			return $parsed_results[0]['result']['metadata']['globalCounts']['count'];
		}

		return 0;

	}

	/**
	 * Retrieves the number of tweets of a link
	 *
	 * @param null|string $url
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return int
	 *
	 */
	public static function get_twitter_statistics( $url = null ) {

		$url = self::get_current_url( $url );

		$parsed_results = self::remote( 'twitter_tweets', $url, "http://urls.api.twitter.com/1/urls/count.json?url=" . urlencode( $url ) );

		if ( isset( $parsed_results['count'] ) ) {
			return $parsed_results['count'];
		}

		return 0;
	}


	/**
	 * Retrieves the number of likes of a link
	 *
	 * @param null|string $url
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return int
	 *
	 */
	public static function get_facebook_statistics( $url = null ) {
		;

		$url = self::get_current_url( $url );

		$parsed_results = self::remote( 'facebook_thumbs_up', $url, "http://graph.facebook.com/?ids=" . urlencode( $url ) );

		// the key "shares" does only exist when the curl-call has been made from your domainname.
		if ( isset( $parsed_results[$url]['shares'] ) ) {
			return $parsed_results[$url]['shares'];
		}

		return 0;
	}


	/**
	 * Retrieves the number of pinterest shares of a link
	 *
	 * @param null|string $url
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return int
	 *
	 */
	public static function get_pinterest_statistics( $url = null ) {

		$url = self::get_current_url( $url );

		$parsed_results = self::remote( 'pinterest_pins', $url, "http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=" . urlencode( $url ) );

		// remove the function call
		$parsed_results = str_replace( "receiveCount(", "", $parsed_results );
		$parsed_results = substr( $parsed_results, 0, - 1 );
		$parsed_results = json_decode( $parsed_results, true );
		if ( ! $parsed_results ) {
			return 0;
		}

		// the key "shares" does only exist when the curl-call has been made from your domainname.
		if ( isset( $parsed_results['count'] ) ) {
			return $parsed_results['count'];
		}

		return 0;
	}


	/**
	 * Retrieves the number of LinkedIn shares of a link
	 *
	 * @param null|string $url
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return int
	 *
	 */
	public static function get_linkedin_statistics( $url = null ) {

		$url = self::get_current_url( $url );

		$parsed_results = self::remote( 'linkedin_shares', $url, "http://www.linkedin.com/countserv/count/share?url=" . urlencode( $url ) . "&callback=myCallback&format=jsonp" );

		$parsed_results = str_replace( "myCallback(", "", $parsed_results );
		$parsed_results = substr( $parsed_results, 0, - 2 );
		$parsed_results = json_decode( $parsed_results, true );

		if ( ! $parsed_results ) {
			return 0;
		}

		if ( ! isset( $parsed_results['count'] ) ) {
			return 0;
		}

		return $parsed_results['count'];

	}


	/**
	 * Makes POST or GET calls.
	 *
	 * @param string $name
	 * @param string $url
	 * @param string $remote_url
	 * @param array  $additional_args
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array|bool|mixed|string
	 */
	public static function remote( $name, $url, $remote_url, $additional_args = array() ) {

		$timeout = intval( get_option( '', 3 ) );
		$timeout = max( 0, $timeout );

		// matching args together
		$args = array( 'timeout' => $timeout );
		$args += $additional_args;

		// check if there was a connection already
		$info = WPB_Fixed_Social_Share_Model_Transient::get_transient( $url, $name );
		if ( false !== $info ) {
			return $info;
		}

		// get the actual data from the url
		$data = wp_remote_request( $remote_url, $args );

		// setting the transient here if something went wrong to prevent doing the same thing over and over again
		WPB_Fixed_Social_Share_Model_Transient::set_transient( $url, $name, 0 );

		// check if the above call was successful
		if ( ! is_wp_error( $data ) && 200 == $data['response']['code'] ) {
			// do json decode
			if ( $info = json_decode( wp_remote_retrieve_body( $data ), true ) ) {

				// set the site_transient for the next call
				WPB_Fixed_Social_Share_Model_Transient::set_transient( $url, $name, $info );
				return $info;
			}
			else {
				return wp_remote_retrieve_body( $data );
			}
		}

		return false;
	}


	/**
	 * Returns registered functions
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public static function get_remote_functions() {
		return apply_filters( 'wpbfsb_get_remote_functions', array(
			'facebook'   => array(
				'label'    => __( 'Facebook', 'wpbfsb' ),
				'function' => array( 'WPB_Fixed_Social_Share_Model_Remote', 'get_facebook_statistics' )
			),
			'twitter'    => array(
				'label'    => __( 'Twitter', 'wpbfsb' ),
				'function' => array( 'WPB_Fixed_Social_Share_Model_Remote', 'get_twitter_statistics' )
			),
			'googleplus' => array(
				'label'    => __( 'Google+', 'wpbfsb' ),
				'function' => array( 'WPB_Fixed_Social_Share_Model_Remote', 'get_google_plusone_statistics' )
			),
			'pinterest'  => array(
				'label'    => __( 'Pinterest', 'wpbfsb' ),
				'function' => array( 'WPB_Fixed_Social_Share_Model_Remote', 'get_pinterest_statistics' )
			),
			'linkedin'   => array(
				'label'    => __( 'LinkedIn', 'wpbfsb' ),
				'function' => array( 'WPB_Fixed_Social_Share_Model_Remote', 'get_linkedin_statistics' )
			),
		) );

	}


}