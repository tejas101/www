<?php
/**
 * The Transient Model Class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Transient Model Class.
 *
 * @since  1.0.0
 * @access public
 *
 */
class WPB_Fixed_Social_Share_Model_Transient {

	/**
	 * Set a transient.
	 *
	 * @param string $url
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function set_transient( $url, $name, $value ) {
		$transient_name = substr( 'wpbfsb_' . md5( $url ), 0, 40 );

		$option = get_option( $transient_name, array() );

		$refresh_rate = intval( get_option( 'wpbfsb_ping_refresh-rate', 60 ) );
		$refresh_rate = max( 1, $refresh_rate );

		$option['timestamp'] = current_time( 'timestamp' ) + 60 * $refresh_rate;

		$option[$name]['value'] = $value;

		update_option( $transient_name, $option );
	}

	/**
	 * Gets a transient value.
	 *
	 * @param string $url
	 * @param string $name
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return mixed
	 */
	public static function get_transient( $url, $name ) {
		$transient_name = substr( 'wpbfsb_' . md5( $url ), 0, 40 );
		$transient      = get_option( $transient_name, 0 );

		if ( ! isset( $transient['timestamp'] ) ) {
			return false;
		}

		if ( ! isset( $transient[$name]['value'] ) ) {
			return false;
		}

		if ( $transient['timestamp'] > current_time( 'timestamp' ) ) {
			return $transient[$name]['value'];
		}

		return false;
	}

}