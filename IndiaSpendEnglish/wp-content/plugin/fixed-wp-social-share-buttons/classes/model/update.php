<?php
/**
 * The Transient Model Class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Updates class
 *
 * @since  1.0.0
 * @access public
 */
class WPB_Fixed_Social_Share_Model_Update {


	/**
	 * Plugin name
	 *
	 * @var string
	 *
	 * @since  1.0.0
	 * @access public
	 */
	const PLUGIN_NAME = 'fixed-wp-social-share-buttons';


	/**
	 * update check function.
	 *
	 * @param $trans
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return $trans
	 */
	public static function site_transient_update_plugins( $trans ) {

		// never do this if it's not an admin page
		if ( ! is_admin() ) {
			return $trans;
		}

		// read the plugins we have received from the webserver
		$remote_plugins = self::get_client_upgrade_data();

		// stop here if there are no plugins to check
		if ( ! $remote_plugins ) {
			return $trans;
		}

		// run through all plugins and do a version_compare
		// here the $plugin_slug is something like "rich-snippets-wordpress-plugin/rich-snippets-wordpress-plugin.php"
		foreach ( get_plugins() as $plugin_slug => $plugin ) {

			// if the plugin is not in our list, go to the next one
			if ( ! isset( $remote_plugins[$plugin_slug] ) ) {
				continue;
			}

			// the actual version compare
			// if the version is lower we will add the plugin information to the $trans array
			if ( version_compare( $plugin['Version'], $remote_plugins[$plugin_slug]->version, '<' ) ) {
				$trans->response[$plugin_slug]      = new stdClass();
				$trans->response[$plugin_slug]->url = $remote_plugins[$plugin_slug]->homepage;

				// here the slug-name is something like "rich-snippets-wordpress-plugin"
				// extracted from the filename
				// this only works if the plugin is inside of a folder
				$trans->response[$plugin_slug]->slug           = str_replace( array( '/', '.php' ), '', strstr( $plugin_slug, '/' ) );
				$trans->response[$plugin_slug]->package        = $remote_plugins[$plugin_slug]->download_url;
				$trans->response[$plugin_slug]->new_version    = $remote_plugins[$plugin_slug]->version;
				$trans->response[$plugin_slug]->id             = '0';
				$trans->response[$plugin_slug]->upgrade_notice = $remote_plugins[$plugin_slug]->upgrade_notice;
			}
			else {
				if ( isset( $trans->response[$plugin_slug] ) ) {
					unset( $trans->response[$plugin_slug] );
				}
			}
		}

		return $trans;
	}


	/**
	 * plugins_api function.
	 * Will return the plugin information or false. If it returns false WordPress will look after some plugin information in the wordpress.org plugin database
	 *
	 * @access   public
	 *
	 * @param boolean      $api
	 * @param string       $action
	 * @param array|object $args
	 *
	 * @internal param mixed $def
	 * @return stdClass | boolean
	 * @since    1.0.0
	 */
	public static function plugins_api( $api, $action, $args ) {

		$slug = self::PLUGIN_NAME;

		if ( false !== $api ) {
			return false;
		}

		if ( ! isset( $args->slug ) ) {
			return false;
		}

		if ( $slug != $args->slug ) {
			return false;
		}

		if ( 'plugin_information' != $action ) {
			return false;
		}

		$plugins = self::get_client_upgrade_data();

		if ( ! $plugins ) {
			return false;
		}

		$extended_slug = str_replace( WP_PLUGIN_DIR . '/', '', WPBFSB_FILE );

		if ( ! isset( $plugins[$extended_slug] ) ) {
			return false;
		}

		return $plugins[$extended_slug]; // stdClass object
	}


	/**
	 * get_client_upgrade_data function.
	 *
	 * @access public
	 * @return array | false
	 * @since  1.0.0
	 * @global $wp_version
	 * @global $wpb_has_plugin_remote_sent
	 */
	public static function get_client_upgrade_data() {
		global $wpb_has_plugin_remote_sent;

		// if yes, than just return the results
		if ( isset( $wpb_has_plugin_remote_sent[self::PLUGIN_NAME] ) ) {
			return $wpb_has_plugin_remote_sent[self::PLUGIN_NAME];
		}

		/**
		 * The transient can only have 40 more characters because wordpress adds "_site_transient_" AND "_site_transient_timeout_" to the db entries
		 * The database can only have 64 characters for the name
		 */
		$transient_name = substr( 'wpbp_u_' . self::PLUGIN_NAME, 0, 64 );

		// if a plugin-check was already done, return the results
		$transient_plugins = self::get_transient( $transient_name );

		if ( false === $transient_plugins ) {
			// the transient is no longer valid because it returned 'false'. => we have to do a request
			$do_request = true;
		}
		else {
			// the transient is valid and returned anything else than 'false'. => we have NOT to do a request
			$do_request = false;

			/**
			 * but first we have to check if we are on the update-core.php page. if so we HAVE to do the request
			 */
			global $pagenow;
			if ( isset( $pagenow ) && 'update-core.php' == $pagenow
				//&& isset( $_GET['force-check'] )
			) {
				$do_request = true;
			}
		}

		if ( ! $do_request ) {
			return $transient_plugins;
		}

		$plugins = get_plugins();

		// what wp-version do we have here?
		global $wp_version;

		$purchase_code = ( ( method_exists( 'WPB_Fixed_Social_Share_Model_Update', 'get_purchase_code' ) ) ? self::get_purchase_code() : '' );
		if ( empty( $purchase_code ) ) {
			return false;
		}

		// prepare the elements for the POST-call
		$post_elements = array(
			'action'        => 'wpbcb_ajax_plugin_update',
			'plugins'       => $plugins,
			'wp_version'    => $wp_version,
			'purchase_code' => $purchase_code,
			'blog_url'      => home_url()
		);

		// some more options for the POST-call
		$options = array(
			'timeout'    => 30,
			'body'       => $post_elements,
			'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url()
		);

		$data = wp_remote_post( base64_decode( 'aHR0cDovL3dwLWJ1ZGR5LmNvbS93cC1hZG1pbi9hZG1pbi1hamF4LnBocA==' ), $options );

		// alright. We did the request, we store an empty array into the transient if something goes wrong later (this means if there was a 404 error or something like that)
		// just to prevent doing the same remote_post over and over again
		self::set_transient( $transient_name, array(), 60 * 60 * 24 );

		if ( ! is_wp_error( $data ) && 200 == wp_remote_retrieve_response_code( $data ) ) {
			if ( $body = json_decode( wp_remote_retrieve_body( $data ), true ) ) {
				if ( is_array( $body ) && isset( $body['plugins'] ) && is_serialized( $body['plugins'] ) ) {
					$remote_plugins = unserialize( $body['plugins'] );

					// set transient for a later usage. set transient to 24 hours
					self::set_transient( $transient_name, $remote_plugins, 60 * 60 * 24 );

					$GLOBALS['wpb_has_plugin_remote_sent'][self::PLUGIN_NAME] = $remote_plugins;

					return $remote_plugins;
				}
			}
		}

		return false;
	}


	/**
	 * Returns the purchase code (if set - otherwise an empty string)
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string purchase code
	 */
	public static function get_purchase_code() {
		return sanitize_text_field( get_option( 'wpbfsb_other_purchase-code', '' ) );
	}


	/**
	 * @param string $name
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return mixed
	 */
	public static function get_transient( $name ) {
		$transient = get_option( $name, null );
		if ( ! is_array( $transient ) ) {
			// transient is not valid because the option does not exist
			return false;
		}

		if ( ! isset( $transient['time'] ) ) {
			// transient is no longer valid because the time does not exist
			return false;
		}

		if ( current_time( 'timestamp' ) > $transient['time'] ) {
			// transient is no longer valid because we're over time
			return false;
		}

		if ( ! isset( $transient['value'] ) ) {
			// transient is no longer valid because the content does not exist
			return false;
		}

		return $transient['value'];

	}


	/**
	 * Sets a transient
	 *
	 * @param string $name
	 * @param mixed  $value
	 * @param int    $time timestamp
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public static function set_transient( $name, $value, $time ) {
		$a = array(
			'time'  => current_time( 'timestamp' ) + $time,
			'value' => $value
		);
		return update_option( $name, $a );
	}

}