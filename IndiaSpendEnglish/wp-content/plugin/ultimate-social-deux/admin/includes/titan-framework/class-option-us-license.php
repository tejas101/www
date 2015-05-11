<?php
/**
 * Ultimate Social License Activation.
 *
 * This class is meant to handle Easy Digital Downloads licenses.
 * When a license is entered, it's checked with the server through
 * the Ultimate WP API. If the license is valid it is activated and
 * the activation result is saved as a transient.
 *
 * As the licensed can be deactivated directly from the server,
 * a regular check needs to be done on the license in order to make sure
 * that the status is up to date.
 *
 * The required option parameters for the activator to work are:
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( class_exists( 'TitanFrameworkOption' ) ) {

	class TitanFrameworkOptionUsLicense extends TitanFrameworkOption {

		public $defaultSecondarySettings = array(
			'placeholder' => '', // show this when blank
			'is_password' => false
		);

		/**
		 * Display for options and meta
		 */
		public function display() {

			/* Get the license */
			$license = esc_attr( $this->getValue() );

			/* License ID */
			$key = substr( md5( $license ), 0, 10 );

			$this->echoOptionHeader();

			printf( "<input class=\"regular-text\" name=\"%s\" placeholder=\"%s\" id=\"%s\" type=\"%s\" value=\"%s\" />",
				$this->getID(),
				$this->settings['placeholder'],
				$this->getID(),
				$this->settings['is_password'] ? 'password' : 'text',
				$license );

			/* If the license is set, we display its status and check it if necessary. */
			if ( strlen( $license ) > 0 ) {
				/* First activation of the license. */
				if ( false === get_transient( "tf_us_license_try_$key" ) ) {
					$status = $this->check( $license, 'envato_activate' );
				}

				/* Otherwise try to get the license activation status from DB. */
				else {
					$status = get_transient( "tf_us_license_status_$key" );
				}

				/* If no transient is found or it is expired to check the license again. */
				if ( false === $status ) {
					$status = $this->check( $license );
				}

				switch( $status ) {

					case 'valid':
						?><p class="description"><?php _e( 'Your license is valid.', 'wpmc' ); ?></p><?php
					break;

					case 'used':
						?><p class="description"><?php _e( 'Your license is in use on a different domain, please buy a new license for this domain.', 'wpmc' ); ?></p><?php
					break;

					case 'inactive':
						?><p class="description"><?php printf( __( 'Your license is valid but inactive. <a href="%s">Click here to activate it</a>.', 'wpmc' ), '' ); ?></p><?php
					break;

					default:
						?><p class="description"><?php _e( 'Your license is invalid.', 'wpmc' ); ?></p><?php
					break;

				}

			}

			$this->echoOptionFooter();

		}

		/*
		 * Display for theme customizer
		 */
		public function registerCustomizerControl( $wp_customize, $section, $priority = 1 ) {
			$wp_customize->add_control( new TitanFrameworkCustomizeControl( $wp_customize, $this->getID(), array(
				'label' => $this->settings['name'],
				'section' => $section->settings['id'],
				'settings' => $this->getID(),
				'description' => $this->settings['desc'],
				'priority' => $priority,
			) ) );
		}

		/**
		 * Check license status.
		 *
		 * The function makes an API call to the remote server and
		 * requests the license status.
		 *
		 * This function check (only) the license status or activate it
		 * depending on the $action parameter. The license status is then
		 * stored as a transient, and if an activation was made, an activation
		 * transient is also set in order to avoid activating when
		 * checking only is required.
		 *
		 * @param  string $license License key
		 * @param  string $action  Action to take (check_license or activate_license)
		 * @return string          Current license status
		 */
		public function check( $license = false, $action = 'envato_verify' ) {

			if ( false === $license ) {
				return false;
			}

			/* Trim the key. */
			$license = trim( $license );

			/* Set the transients lifetime. */
			$status_lifetime     = apply_filters( 'tf_us_license_status_lifetime', 48*60*60 );         // Default is set to two days
			$activation_lifetime = apply_filters( 'tf_us_license_activation_lifetime', 365*24*60*60 ); // Default is set to one year

			/* Prepare the data to send with the API request. */
			$api_params = array(
				'update-check' => 1,
				'action' => $action,
				'unique' => 'WoqUdWkA64w3Iy3A',
				'license' => $license,
				'domain'  => get_site_url( '', '', 'http' ),
			);

			/* Call the API. */
			$response = wp_remote_get( add_query_arg( $api_params, 'http://api.ultimate-wp.com/update-check/' ), array( 'timeout' => 15, 'sslverify' => false ) );

			/* Decode license data. */
			$license_data = json_decode( wp_remote_retrieve_body( $response ), true );

			// FIXME @julien731: what if $license_data is a non-object (error), what should the return be?
			if ( empty( $license_data ) ) {
				return false;
			}

			/* License ID */
			$key = substr( md5( $license ), 0, 10 );

			if( ! empty( $license_data['verify-purchase']['item_id'] ) && $license_data['verify-purchase']['item_id'] == $license_data['local_id'] ) {
                $status = 'valid';
            } elseif( ! empty( $license_data['verify-purchase']['item_id'] ) && $license_data['verify-purchase']['item_id'] == $license_data['local_id'] && $action == 'envato_verify') {
                $status = 'inactive';
            } elseif( $license_data['error_code'] == "LICENSE_ALREADY_USED") {
            	$status = 'used';
            } else {
            	$status = 'invalid';
           	}

			/* Set the status transient. */
			set_transient( "tf_us_license_status_$key", $status, $status_lifetime );

			if ( 'envato_activate' == $action ) {

				/* Set the activation transient */
				set_transient( "tf_us_license_try_$key", true, $activation_lifetime );

			}

			/* Return the license status. */
			return $status;

		}

	}
}