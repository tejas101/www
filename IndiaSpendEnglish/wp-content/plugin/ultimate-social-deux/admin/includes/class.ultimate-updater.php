<?php
/**
 *
 * @package     Ultimate\Updater
 * @since       1.0.0
 * @author      Ultimate WP
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'UltimateUpdater' ) ) {

    class UltimateUpdater {


        /**
         * @access      private
         * @since       1.0.0
         * @var         string $api_url The URL of the remote API
         */
        private $api_url = '';


        /**
         * @access      private
         * @since       1.0.0
         * @var         array $api_data Data to pass to the API
         */
        private $api_data = array();


        /**
         * @access      private
         * @since       1.0.0
         * @var         string $name The name of this plugin
         */
        private $name = '';


        /**
         * @access      private
         * @since       1.0.0
         * @var         string $unique The unique ID of this plugin
         */
        private $unique;


        /**
         * @access      private
         * @since       1.0.0
         * @var         string $license An optional Envato purchase key
         */
        private $license = false;


        /**
         * @access      private
         * @since       1.0.0
         * @var         string $slug The slug of this plugin
         */
        private $slug;


        /**
         * Class constructor
         *
         * @access      public
         * @since       1.0.0
         * @param       string $_api_url The URL of the remote API
         * @param       string $_plugin_file The main plugin file
         * @param       array $_api_data The data to pass to the API
         * @return      void
         */
        public function __construct( $_api_url, $_plugin_file, $_api_data ) {
            $this->api_url      = trailingslashit( $_api_url );
            $this->api_data     = urlencode_deep( $_api_data );
            $this->name         = plugin_basename( $_plugin_file );
            $this->slug         = basename( $_plugin_file, '.php' );
            $this->version      = $_api_data['version'];
            $this->unique       = $_api_data['unique'];
            $this->license      = ( isset( $_api_data['license'] ) ? $_api_data['license'] : false );

            $this->hooks();

            // Uncomment these line for testing
            //add_filter( 'plugins_api_result', array(&$this, 'debug_result'), 10, 3 );
            //set_site_transient( 'update_plugins', false );
        }

        function debug_result( $res, $action, $args ) {
            echo '<pre>'.print_r($res,true).'</pre>';
            return $res;
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {
            // Run transient check
            add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'api_check' ) );
            add_filter( 'plugins_api', array( $this, 'api_data' ), 10, 3 );
            add_filter( 'upgrader_post_install', array( $this, 'run_remote_count' ), 10, 3 );
            add_filter( 'http_request_args', array( $this, 'disable_wporg' ), 5, 2 );

            register_activation_hook( __FILE__, array( $this, 'clear_transient' ) );

        }


        /**
         * Remove transient on initial install
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function clear_transient() {
            delete_transient( 'update_plugins' );
        }


        /**
         * Run transient check
         *
         * @access      public
         * @since       1.0.0
         * @param       object $_transient_data The transient data
         * @return      void
         */
        public function api_check( $_transient_data ) {
            // Bail if no data
            if( empty( $_transient_data ) ) {
                return $_transient_data;
            }

            $to_send = array( 'slug' => $this->slug );

            // Check licensing
            if( $this->license ) {
                $api_response = $this->api_request( 'envato_verify', $to_send );

                if( $api_response === false ) {
                    return $_transient_data;
                }
            }

            $api_response   = $this->api_request( 'plugin_latest_version', $to_send );

            if( $api_response !== false && is_object( $api_response ) ) {
                if( version_compare( $this->version, $api_response->new_version, '<' ) ) {
                    $_transient_data->response[$this->name] = $api_response;

                    $name = $this->name;
                    add_action( "after_plugin_row_$name", 'wp_plugin_update_row', 10, 2 );
                }
            }

            return $_transient_data;
        }


        /**
         * Update details modal
         *
         * @access      public
         * @since       1.0.0
         * @param       mixed $_data
         * @param       string $_action The action to perform
         * @param       object $_args Args to pass to the API
         * @return      object $data
         */
        public function api_data( $_data, $_action = '', $_args = null ) {
            // Bail if this isn't our modal!
            if( ( $_action != 'plugin_information' ) || ! isset( $_args->slug ) || ( $_args->slug != $this->slug ) ) {
                return $_data;
            }

            $to_send        = array( 'slug' => $this->slug );

            // Check licensing
            if( $this->license ) {

                $api_response = $this->api_request( 'envato_verify', $to_send );

                if( $api_response === false ) {
                    return $_transient_data;
                }
            }

            $api_response = $this->api_request( 'plugin_information', $to_send );

            if( $api_response !== false ) {
                $_data = $api_response;
            }

            return $_data;
        }


        /**
         * Call the API
         *
         * @access      private
         * @since       1.0.0
         * @param       string $_action The action to perform
         * @param       array $_data Parameters for the API call
         * @global      string $wp_version The version of WordPress we are running
         * @return      mixed false||object
         */
        private function api_request( $_action, $_data ) {
            global $wp_version;

            $data = array_merge( $this->api_data, $_data );

            // Make sure we belong here
            if( ! isset( $data['slug'] ) || $data['slug'] != $this->slug ) {
                return;
            }

            // Bail if important info is missing
            if( ! isset( $data['unique'] ) || ! isset( $data['version'] ) ) {
                return;
            }

            // Build the query
            $api_args = array(
                'method'    => 'POST',
                'timeout'   => 15,
                'sslverify' => false,
                'user-agent'=> 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
                'body'      => array(
                    'update-check' => 1,
                    'action'    => $_action,
                    'unique'    => $data['unique'],
                    'version'   => $data['version'],
                    'slug'      => $data['slug'],
                )
            );

            // Do we have a license?
            if( isset( $data['license'] ) && !empty( $data['license'] ) ) {
                $api_args['body']['license'] = $data['license'];
                $api_args['body']['domain'] = get_site_url( '', '', 'http' );
            }

            // Off to the races!
            $request = wp_remote_post( $this->api_url, $api_args );

            // Bail on errors
            if( is_wp_error( $request ) ) {
                return false;
            }

            // Bail if connection failed
            if( ! isset( $request['response'] ) || $request['response']['code'] != 200 ) {
                return false;
            }

            // Decode the response
            $response = json_decode( wp_remote_retrieve_body( $request ), true );

            // Bail if no data
            if( ! is_array( $response ) || is_array( $response ) && empty( $response ) ) {
                return false;
            }

            // Bail if success is false (wat?)
            if( $_action != 'envato_verify' ) {
                if( ! isset( $response['success'] ) || ! $response['success'] ) {
                    return false;
                }

                if( ! isset( $response['fields'] ) || isset( $response['fields'] ) && empty( $response['fields'] ) ) {
                    return false;
                }
            }

            $update = false;

            // Build and return basic info
            if( $_action == 'plugin_latest_version' ) {
                $updates = self::get_version_response( $response['fields'] );
            }

            // Build complete return
            if( $_action == 'plugin_information' ) {
                $updates = self::get_information_response( $response['fields'] );
            }

            // Fake return for envato checks
            if( $_action == 'envato_verify' ) {
                $updates = new stdClass;
            }

            // One final check...
            if( ! $updates ) {
                return false;
            }

            return $updates;
        }


        /**
         * Get basic info
         *
         * @access      public
         * @since       1.0.0
         * @param       array $response The API response
         * @return      object $updates The info
         */
        public static function get_version_response( $response ) {
            // Create the data object
            $updates = new stdClass;

            $updates->slug          = $response['slug'];
            $updates->new_version   = $response['new_version'];
            $updates->url           = $response['url'];
            $updates->package       = $response['package'];

            return $updates;
        }


        /**
         * Get full info
         *
         * @access      public
         * @since       1.0.0
         * @param       array $response The API response
         * @return      object $updates The info
         */
        public static function get_information_response( $response ) {
            // Create the data object
            $updates = new stdClass;

            $updates->name          = $response['name'];
            $updates->slug          = $response['slug'];
            $updates->version       = $response['version'];
            $updates->author        = $response['author'];
            $updates->author_profile= $response['author_profile'];
            $updates->contributors  = $response['contributors'];
            $updates->requires      = $response['requires'];
            $updates->tested        = $response['tested'];
            $updates->rating        = $response['rating'];
            $updates->num_ratings   = $response['num_ratings'];
            $updates->downloaded    = $response['downloaded'];
            $updates->last_updated  = $response['last_updated'];
            $updates->added         = $response['added'];
            $updates->homepage      = $response['homepage'];
            $updates->download_link = $response['download_link'];
            $updates->sections      = $response['sections'];

            return $updates;
        }


        /**
         * Update remote count
         *
         * @access      public
         * @since       1.0.0
         * @param       array $data Data to send to the API
         * @return      bool
         */
        public function update_remote_count( $data ) {
            // Build array
            $api_args = array(
                'method'    => 'POST',
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => array(
                    'update-check' => 1,
                    'action'    => 'update_counts',
                    'unique'    => $data['unique'],
                    'version'   => $data['version'],
                    'slug'      => $data['slug']
                )
            );

            // Send request
            $request = wp_remote_post( $this->api_url, $api_args );

            return;
        }


        /**
         * Run remote count
         *
         * @access      public
         * @since       1.0.0
         * @param       bool $true
         * @param       array $hook_extra Check the plugin
         * @param       $result
         * @return      $result
         */
        public function run_remote_count( $true, $hook_extra, $result ) {
            // Allow bypass
            if( apply_filters( 'ultimate_update_update_count', true ) === false ) {
                return $result;
            }

            // Make sure there's data to check
            if( ! isset( $hook_extra ) ) {
                return $result;
            }

            // Make sure this is our plugin
            if( ! isset( $hook_extra['plugin'] ) || $hook_extra['plugin'] != $this->name ) {
                return $result;
            }

            // Build array
            $data = array(
                'unique'    => $this->unique,
                'version'   => $this->version,
                'slug'      => $this->slug
            );

            // Run our callback counter
            $this->update_remote_count( $data );

            return $result;
        }


        /**
         * Keep wp.org from running updates for this plugin
         *
         * @access      public
         * @since       1.0.0
         * @param       string $r
         * @param       string $url
         * @return      mixed
         */
        public function disable_wporg( $r, $url ) {
            // Set wp.org update URL
            $wp_url_string = 'api.wordpress.org/plugins/update-check';

            // Bail if this isn't a plugin update check
            if( strpos( $url, $wp_url_string ) === false ) {
                return $r;
            }

            // Get the plugin slug
            $plugin_slug = dirname( $this->slug );

            // Get response body
            $r_body = wp_remote_retrieve_body( $r );

            // Get plugins request
            $r_plugins = '';
            $r_plugins_json = false;

            if( isset( $r_body['plugins'] ) ) {
                // Check if data can be serialized
                if( is_serialized( $r_body['plugins'] ) ) {
                    // Unserialize data (pre WP3.7)
                    $r_plugins = @unserialize( $r_body['plugins'] );
                    $r_plugins = (array) $r_plugins;
                } else {
                    // Post WP3.7, using JSON
                    $r_plugins = json_decode( $r_body['plugins'], true );
                    $r_plugins_json = true;
                }
            }

            $to_disable = '';

            // Check if request is not empty
            if( ! empty( $r_plugins ) ) {
                // All plugins
                $all_plugins = $r_plugins['plugins'];

                foreach( $all_plugins as $plugin_base => $plugin_data ) {
                    if( dirname( $plugin_base ) == $plugin_slug ) {
                        $to_disable = $plugin_base;
                    }
                }

                // Unset this plugin
                if( ! empty( $to_disable ) ) {
                    unset( $all_plugins[$to_disable] );
                }

                // Merge back to request
                if( $r_plugins_json === true ) {
                    $r_plugins['plugins'] = $all_plugins;
                    $r['body']['plugins'] = json_encode( $r_plugins );
                } else {
                    $r_plugins['plugins'] = $all_plugins;
                    $r_plugins_object = (object) $r_plugins;
                    $r['body']['plugins'] = serialize( $r_plugins_object );
                }
            }

            return $r;
        }
    }
}
