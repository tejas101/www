<?php
/**
 * Ultimate Social Deux.
 *
 * @package		Ultimate Social Deux
 * @author		Ultimate Wordpress <hello@ultimate-wp.com>
 * @link		http://social.ultimate-wp.com
 * @copyright 	2013 Ultimate Wordpress
 */

class UltimateSocialDeuxFanCount {

	private function __construct() {
		add_action( 'wp_ajax_nopriv_us_fan_counts', array( $this, 'us_fan_counts' ) );
		add_action( 'wp_ajax_us_fan_counts', array( $this, 'us_fan_counts' ) );
	}

	public static function us_fan_counts() {
		header('content-type: application/json');

		$json = array('network'=>'','count'=>0);
		$json['network'] = $_GET['network'];

		if ( isset($json['network']) ) {
			switch ($json['network']) {
			  	case "facebook":
					$count = UltimateSocialDeux::number_format( self::facebook_count() );
				break;
			  	case "twitter":
			  		$count = UltimateSocialDeux::number_format( self::twitter_count() );
				break;
				case "google":
					$count = UltimateSocialDeux::number_format( self::google_count() );
				break;
				case "youtube":
					$count = UltimateSocialDeux::number_format( self::youtube_count() );
				break;
				case "love":
					$count = UltimateSocialDeux::number_format( self::love_count() );
				break;
				case "delicious":
					$count = UltimateSocialDeux::number_format( self::delicious_count() );
				break;
				case "linkedin":
					$count = UltimateSocialDeux::number_format( self::linkedin_count() );
				break;
				case "behance":
					$count = UltimateSocialDeux::number_format( self::behance_count() );
				break;
				case "vimeo":
					$count = UltimateSocialDeux::number_format( self::vimeo_count() );
				break;
				case "dribbble":
					$count = UltimateSocialDeux::number_format( self::dribbble_count() );
				break;
				case "envato":
					$count = UltimateSocialDeux::number_format( self::envato_count() );
				break;
				case "github":
					$count = UltimateSocialDeux::number_format( self::github_count() );
				break;
				case "soundcloud":
					$count = UltimateSocialDeux::number_format( self::soundcloud_count() );
				break;
				case "instagram":
					$count = UltimateSocialDeux::number_format( self::instagram_count() );
				break;
				case "vkontakte":
					$count = UltimateSocialDeux::number_format( self::vkontakte_count() );
				break;
				case "feedpress":
					$count = UltimateSocialDeux::number_format( self::feedpress_count() );
				break;
				case "pinterest":
					$count = UltimateSocialDeux::number_format( self::pinterest_count() );
				break;
				case "mailchimp":
					$count = UltimateSocialDeux::number_format( self::mailchimp_count() );
				break;
				case "flickr":
					$count = UltimateSocialDeux::number_format( self::flickr_count() );
				break;
				case "members":
					$count = UltimateSocialDeux::number_format( self::members_count() );
				break;
				case "posts":
					$count = UltimateSocialDeux::number_format( self::posts_count() );
				break;
				case "comments":
					$count = UltimateSocialDeux::number_format( self::comments_count() );
				break;
			}
		}

		$json['count'] = $count;

		echo str_replace('\\/','/',json_encode($json));

		die();

	}

	/**
	 * Instance of this class.
	 *
	 * @since	1.0.0
	 *
	 * @var		object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since	 1.0.0
	 *
	 * @return	object	A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Return fan counters
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function fan_count_output($networks = '', $rows = '1', $skin = '') {

		UltimateSocialDeux::enqueue_stuff();

		global $us_fan_count_data;

		$skin = ( $skin ) ? sprintf(' us_skin_%s', $skin): ' us_skin_default';

		$output = sprintf('<div class="us_wrapper us_fan_count_wrapper%s">', $skin);

		$networks = str_replace(' ', '', $networks);

		$networks = explode(',', $networks);

		foreach ($networks as &$network) {

			switch ($network) {
			  	case "facebook":
					$network = 'facebook';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Fans', 'ultimate-social-deux');
					$link = sprintf('https://facebook.com/%s', UltimateSocialDeux::opt('us_facebook_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
			  	case "twitter":
					$network = 'twitter';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('https://twitter.com/%s', UltimateSocialDeux::opt('us_twitter_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "google":
					$network = 'google';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('https://plus.google.com/%s/', UltimateSocialDeux::opt('us_google_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "youtube":
					$network = 'youtube';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Subscribers', 'ultimate-social-deux');
					$link = sprintf('https://www.youtube.com/user/%s/', UltimateSocialDeux::opt('us_youtube_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "love":
					$network = 'love';
					$count = UltimateSocialDeux::number_format( self::love_count() );
					$desc = __('Total loves', 'ultimate-social-deux');
					$link = '';
					$output .= self::counter($network, $count, $desc, $rows, $link, '');
				break;
				case "delicious":
					$network = 'delicious';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('https://delicious.com/%s/', UltimateSocialDeux::opt('us_delicious_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "linkedin":
					$network = 'linkedin';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('https://linkedin.com/company/%s', UltimateSocialDeux::opt('us_linkedin_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "behance":
					$network = 'behance';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('https://www.behance.net/%s', UltimateSocialDeux::opt('us_behance_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "vimeo":
					$network = 'vimeo';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Subscribers', 'ultimate-social-deux');
					$link = sprintf('http://vimeo.com/channels/%s', UltimateSocialDeux::opt('us_vimeo_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "dribbble":
					$network = 'dribbble';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('https://dribbble.com/%s', UltimateSocialDeux::opt('us_dribbble_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "envato":
					$network = 'envato';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('http://codecanyon.net/user/%s/follow', UltimateSocialDeux::opt('us_envato_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "github":
					$network = 'github';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('https://github.com/%s', UltimateSocialDeux::opt('us_github_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "soundcloud":
					$network = 'soundcloud';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('https://soundcloud.com/%s', UltimateSocialDeux::opt('us_soundcloud_username', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "instagram":
					$network = 'instagram';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('http://instagram.com/%s', UltimateSocialDeux::opt('us_instagram_username', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "vkontakte":
					$network = 'vkontakte';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Members', 'ultimate-social-deux');
					$link = sprintf('http://vk.com/%s', UltimateSocialDeux::opt('us_vkontakte_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "feedpress":
					$network = 'feedpress';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Subscribers', 'ultimate-social-deux');
					$link = sprintf('%s', UltimateSocialDeux::opt('us_feedpress_url', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "pinterest":
					$network = 'pinterest';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Followers', 'ultimate-social-deux');
					$link = sprintf('http://www.pinterest.com/%s', UltimateSocialDeux::opt('us_pinterest_username', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "mailchimp":
					$network = 'mailchimp';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Subscribers', 'ultimate-social-deux');
					$link = sprintf('%s', UltimateSocialDeux::opt('us_mailchimp_link', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "flickr":
					$network = 'flickr';
					$count = ( self::us_get_transient($network) ) ? UltimateSocialDeux::number_format( self::us_get_transient($network) ) : '<i class="us-icon-spin us-icon-spinner"></i>';
					$ajax = ( self::us_get_transient($network) ) ? '': 'true';
					$desc = __('Members', 'ultimate-social-deux');
					$link = sprintf('https://www.flickr.com/groups/%s/', UltimateSocialDeux::opt('us_flickr_id', ''));
					$output .= self::counter($network, $count, $desc, $rows, $link, $ajax);
				break;
				case "members":
					$network = 'members';
					$count = UltimateSocialDeux::number_format( self::members_count() );
					$desc = __('Members', 'ultimate-social-deux');
					$link = '';
					$output .= self::counter($network, $count, $desc, $rows, $link, 'false');
				break;
				case "posts":
					$network = 'posts';
					$count = UltimateSocialDeux::number_format( self::posts_count() );
					$desc = __('Posts', 'ultimate-social-deux');
					$link = '';
					$output .= self::counter($network, $count, $desc, $rows, $link, 'false');
				break;
				case "comments":
					$network = 'comments';
					$count = UltimateSocialDeux::number_format( self::comments_count() );
					$desc = __('Comments', 'ultimate-social-deux');
					$link = '';
					$output .= self::counter($network, $count, $desc, $rows, $link, 'false');
				break;
			}
		}

		$output .= '</div>';

		if ($us_fan_count_data) {
			self::update_count($us_fan_count_data);
		}

		return $output;
	}

	/**
	 * Return counter markup
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 markup
	 */
	public static function counter($network, $count, $desc, $rows, $link = '', $ajax) {
		$no_link = ($link) ? '': ' us_no_link';
		$output = sprintf('<div class="us_fan_count rows-%s us_button us_%s_fan_count%s" data-ajax="%s" data-network="%s">', $rows, $network, $no_link, $ajax, $network);
			$output .= ($link) ? sprintf('<a href="%s" target="_blank" class="us_%s_fan_count_link">', $link, $network): '';
				$output .= sprintf('<div class="us_fan_count_button">', $network);
					$output .= '<div class="us_fan_count_icon_holder">';
						$output .= sprintf('<i class="us-icon-%s"></i>', $network);
					$output .= '</div>';
					$output .= '<div class="us_fan_count_holder">';
						$output .= $count;
					$output .= '</div>';
					$output .= '<div class="us_fan_count_desc">';
						$output .= $desc;
					$output .= '</div>';
				$output .= '</div>';
			$output .= ($link) ? sprintf('</a>', $link): '';
		$output .= '</div>';

		return $output;

	}

	public static function us_get_transient($network) {

		$transient = get_transient( 'us_fan_count_'.$network );

		$count = ( isset($transient) ) ? $transient: 0;

		return $count;
	}

	/**
	 * Updates options and transients
	 *
	 * @since 	 1.0.0
	 *
	 */
	public static function update_count($data, $network){

		$options = get_option( 'us_fan_count_counters' );

		$cache = intval(UltimateSocialDeux::opt('us_cache', 2));

		$options['data'][$network] = $data;

		set_transient( 'us_fan_count_'.$network, $data , $cache*60*60 );
		update_option( 'us_fan_count_counters', $options );
	}

	/**
	 * Return Twitter count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function twitter_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_twitter_id');
		$key = UltimateSocialDeux::opt('us_twitter_key');
		$secret = UltimateSocialDeux::opt('us_twitter_secret');

		if ($id && $key && $secret) {
			$token = get_option( 'us_fan_count_twitter_token' );

			if(!$token) {
				$credentials = $key . ':' . $secret;
				$encode = base64_encode($credentials);

				$args = array(
					'method' => 'POST',
					'httpversion' => '1.1',
					'blocking' => true,
					'headers' => array(
						'Authorization' => 'Basic ' . $encode,
						'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
					),
					'body' => array( 'grant_type' => 'client_credentials' )
				);

				add_filter('https_ssl_verify', '__return_false');
				$response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);

				$keys = json_decode(wp_remote_retrieve_body($response));

				if(!isset($keys->errors) && $keys) {
					update_option('us_fan_count_twitter_token', $keys->access_token);
					$token = $keys->access_token;
				}
			}

			$args = array(
				'httpversion' => '1.1',
				'blocking' => true,
				'headers' => array(
					'Authorization' => "Bearer $token"
				)
			);

			add_filter('https_ssl_verify', '__return_false');
			$api_url = "https://api.twitter.com/1.1/users/show.json?screen_name=$id";
			$response = wp_remote_get($api_url, $args);

			if (!is_wp_error($response) ) {
				$followers = json_decode(wp_remote_retrieve_body($response));
				$count = 0;
				if (!isset($followers->errors) && $followers) {
					$count = intval($followers->followers_count);
				}
			}
		}

		if( !empty( $count ) ) {
			$data = $count;
			self::update_count($data, 'twitter');
		}

		if( empty( $count ) && !empty( $options['twitter'] ) ) {
			$count = $options['twitter'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Facebook count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function facebook_count(){

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_facebook_id');
		if ($id) {
			try {
				$data = @UltimateSocialDeux::remote_get( "http://graph.facebook.com/$id");
				if (isset($data['likes'])) {
					$count = intval($data['likes']);
				}
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ){
			$data = $count;
			self::update_count($data, 'facebook');
		}

		if( empty( $count ) && !empty( $options['facebook'] ) ){
			$count = $options['facebook'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Google count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function google_count(){

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_google_id');
		$key = UltimateSocialDeux::opt('us_google_key');
		if($key && $id) {
			try {
				$data = @UltimateSocialDeux::remote_get("https://www.googleapis.com/plus/v1/people/".$id."?key=".$key);
				if (isset($data['circledByCount'])) {
					$count = intval( $data['circledByCount'] );
				}
			} catch (Exception $e) {
				$count = 0;
			}

		} elseif($id) {
			$id = 'https://plus.google.com/' . $id;
			try {
				$data_params = array(
					'method'  => 'POST',
					'sslverify' => false,
					'timeout'  => 30,
					'headers'  => array( 'Content-Type' => 'application/json' ),
					'body'   => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $id . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]'
		  		);
				$data = wp_remote_get( 'https://clients6.google.com/rpc', $data_params );

				if ( is_wp_error( $data ) || '400' <= $data['response']['code'] ) {
					$count = ( isset( $options['google'] ) ) ? $options['google'] : 0;
				} else {
					$response = json_decode( $data['body'], true );
					if ( isset( $response[0]['result']['metadata']['globalCounts']['count'] ) ) {
						$count = intval($response[0]['result']['metadata']['globalCounts']['count']);
					}
				}
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ) {
			$data = $count;
			self::update_count($data, 'google');
		}

		if( empty( $count ) && !empty( $options['google'] ) ) {
			$count = $options['google'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Behance count
	 *
	 * @since 	 4.0.0
	 *
	 * @return 	 count
	 */
	public static function behance_count(){

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_behance_id');
		$api = UltimateSocialDeux::opt('us_behance_api');
		if ($id && $api) {
			try {
				$data = @UltimateSocialDeux::remote_get( "http://www.behance.net/v2/users/$id?api_key=$api" );
				$count = intval($data['user']['stats']['followers']);
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ){
			$data = $count;
			self::update_count($data, 'behance');
		}

		if( empty( $count ) && !empty( $options['behance'] ) ){
			$count = $options['behance'];
		}


		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Delicious count
	 *
	 * @since 	 4.0.0
	 *
	 * @return 	 count
	 */
	public static function delicious_count(){

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_delicious_id');
		if ($id) {
			try {
				$data = @UltimateSocialDeux::remote_get("http://feeds.delicious.com/v2/json/userinfo/$id" );
				$count = intval($data[2]['n']);
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ){
			$data = $count;
			self::update_count($data, 'delicious');
		}

		if( empty( $count ) && !empty( $options['delicious'] ) ){
			$count = $options['delicious'];
		}


		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Love count
	 *
	 * @since 	 4.0.0
	 *
	 * @return 	 count
	 */
	public static function love_count(){

		$options = get_option( 'us_love_count' );

		$options = $options['data'];

		$count = 0;

		foreach ($options as &$option) {
			$c = $option['count'];
		    $count = $c + $count;
		}

		return $count;
	}

	/**
	 * Return LinkedIn count
	 *
	 * @since 	 4.0.0
	 *
	 * @return 	 count
	 */
	public static function linkedin_count(){

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_linkedin_id');
		$app = UltimateSocialDeux::opt('us_linkedin_app');
		$api = UltimateSocialDeux::opt('us_linkedin_api');
		if (!class_exists('LinkedIn')) {
			require_once plugin_dir_path( __FILE__ ) . 'includes/linkedin/linkedin.php';
		}
		if ( !class_exists( 'OAuthServer' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'includes/OAuth/OAuth.php';
		}
		if ($id && $api && $id) {
			$opt = array (
				'appKey' => $app ,
				'appSecret' => $api ,
				'callbackUrl' => '' ,
			);

			$api_call = new LinkedIn( $opt );
			$response = $api_call->company( trim( 'universal-name=' . $id . ':(num-followers)' ) );

			if ( false !== $response['success'] ) {
				$company = new SimpleXMLElement( $response['linkedin'] );

				if ( isset( $company->{'num-followers'} ) ) {

					$count = intval(current( $company->{'num-followers'} ));
				}
			}

		}

		if( !empty( $count ) ){
			$data = $count;
			self::update_count($data, 'linkedin');
		}

		if( empty( $count ) && !empty( $options['linkedin'] ) ){
			$count = $options['linkedin'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}

		return $count;
	}

	/**
	 * Return Youtube count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function youtube_count(){

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_youtube_id');
		if ($id) {
			try {
				$data = @UltimateSocialDeux::remote_get("http://gdata.youtube.com/feeds/api/users/$id?alt=json");
				$count = intval($data['entry']['yt$statistics']['subscriberCount']);
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ){
			$data = $count;
			self::update_count($data, 'youtube');
		}

		if( empty( $count ) && !empty( $options['youtube'] ) ){
			$count = $options['youtube'];
		}


		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return SoundCloud count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function soundcloud_count(){

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_soundcloud_id');
		$user = UltimateSocialDeux::opt('us_soundcloud_username');
		if ($id && $user) {
			try {
				$data = @wp_remote_get( 'http://api.soundcloud.com/users/' . $user . '.json?client_id=' . $id );
				$response = json_decode( $data['body'], true );

				if (isset($response['followers_count'])) {
					$count = intval( $response['followers_count'] );
				}
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ){
			$data = $count;
			self::update_count($data, 'soundcloud');
		}

		if( empty( $count ) && !empty( $options['soundcloud'] ) ){
			$count = $options['soundcloud'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Vimeo count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function vimeo_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_vimeo_id');
		if ($id) {
			try {
				@$data = UltimateSocialDeux::remote_get( "http://vimeo.com/api/v2/channel/$id/info.json" );
				$count = intval($data['total_subscribers']);
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ) {
			$data = $count;
			self::update_count($data, 'vimeo');
		}

		if( empty( $count ) && !empty( $options['vimeo'] ) ) {
			$count = $options['vimeo'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Dribbble count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function dribbble_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_dribbble_id');
		if ($id) {
			try {
				$data = @UltimateSocialDeux::remote_get("http://api.dribbble.com/$id");
				if (isset($data['followers_count'])) {
					$count = intval($data['followers_count']);
				}
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ) {
			$data = $count;
			self::update_count($data, 'dribbble');
		}

		if( empty( $count ) && !empty( $options['dribbble'] ) ) {
			$count = $options['dribbble'];
		}


		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Github count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function github_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_github_id');
		if ($id) {
			try {
				$data = UltimateSocialDeux::remote_get("https://api.github.com/users/$id");
				if (isset($data['followers'])) {
					$count = intval($data['followers']);
				} else {
					$count = 0;
				}
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) )
			$data = $count;
			self::update_count($data, 'github');

		if( empty( $count ) && !empty( $options['github'] ) ) {
			$count = $options['github'];
		}


		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Envato count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function envato_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_envato_id');
		if ($id) {
			try {
				$data = @UltimateSocialDeux::remote_get("http://marketplace.envato.com/api/edge/user:$id.json");
				if (isset($data['user']['followers'])) {
					$count = intval($data['user']['followers']);
				}
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ) {
			$data = $count;
			self::update_count($data, 'envato');
		}

		if( empty( $count ) && !empty( $options['envato'] ) ) {
			$count = $options['envato'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Instagram count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function instagram_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$api = UltimateSocialDeux::opt('us_instagram_api');
		$id = explode(".", $api);
		if ($api && $id) {
			try {
				$data = @UltimateSocialDeux::remote_get("https://api.instagram.com/v1/users/$id[0]/?access_token=$api");
				$count = intval($data['data']['counts']['followed_by']);
			} catch (Exception $e) {
				$count = 0;
			}
		}


		if( !empty( $count ) ) {
			$data = $count;
			self::update_count($data, 'instagram');
		}

		if( empty( $count ) && !empty( $options['instagram'] ) ) {
			$count = $options['instagram'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Mailchimp count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function mailchimp_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$name = UltimateSocialDeux::opt('us_mailchimp_name');
		$api = UltimateSocialDeux::opt('us_mailchimp_api');

		if ($name && $api) {
			if (!class_exists('MCAPI')) {
				require_once( plugin_dir_path( __FILE__ ) . 'includes/MCAPI.class.php' );
			}

			$api = new MCAPI($api);
			$retval = $api->lists();
			$count = 0;

			if (count($retval['data']) > 0) {
				foreach ($retval['data'] as $list){
					if($list['name'] == $name){
						$count = intval($list['stats']['member_count']);
						break;
					}
				}
			}
		}

		if( !empty( $count ) ) {
			$data = $count;
			self::update_count($data, 'mailchimp');
		}

		if( empty( $count ) && !empty( $options['mailchimp'] ) ) {
			$count = $options['mailchimp'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return VKontakte count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function vkontakte_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_vkontakte_id');
		if ($id) {
			try {
				$data = @UltimateSocialDeux::remote_get( "http://api.vk.com/method/groups.getById?gid=$id&fields=members_count");
				if (isset($data['response'][0]['members_count'])) {
					$count = intval($data['response'][0]['members_count']);
				}
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ) {
			$data = $count;
			self::update_count($data, 'vkontakte');
		}

		if( empty( $count ) && !empty( $options['vkontakte'] ) ) {
			$count = $options['vkontakte'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}

		return $count;
	}

	/**
	 * Return Pinterest count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function pinterest_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$username = UltimateSocialDeux::opt('us_pinterest_username');
		if ($username) {
			try {
				$html = UltimateSocialDeux::remote_get( "http://www.pinterest.com/$username/" , false);
				$doc = new DOMDocument();
				@$doc->loadHTML($html);
				$metas = $doc->getElementsByTagName('meta');
				for ($i = 0; $i < $metas->length; $i++){
					$meta = $metas->item($i);
					if($meta->getAttribute('name') == 'pinterestapp:followers'){
						$count = intval($meta->getAttribute('content'));
						break;
					}
				}

			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ) {
			$data = $count;
			self::update_count($data, 'pinterest');
		}

		if( empty( $count ) && !empty( $options['pinterest'] ) ) {
			$count = $options['pinterest'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return Flickr count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function flickr_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$id = UltimateSocialDeux::opt('us_flickr_id');
		$api = UltimateSocialDeux::opt('us_flickr_api');
		if ($id && $api) {
			try {
				$data = @UltimateSocialDeux::remote_get( "https://api.flickr.com/services/rest/?method=flickr.groups.getInfo&api_key=$api&group_id=$id&format=json&nojsoncallback=1");
				if (isset($data['group']['members']['_content'])) {
					$count = intval($data['group']['members']['_content']);
				}
			} catch (Exception $e) {
				$count = 0;
			}
		}

		if( !empty( $count ) ) {
			$data = $count;
			self::update_count($data, 'flickr');
		}

		if( empty( $count ) && !empty( $options['flickr'] ) ) {
			$count = $options['flickr'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Return feedpress count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function feedpress_count() {

		$options = UltimateSocialDeux::opt_old('data', 'us_fan_count_counters', '');

		$manual = intval( UltimateSocialDeux::opt('us_feedpress_manual', 0) );

		$url = UltimateSocialDeux::opt('us_feedpress_url');

		$count = 0;

		if (filter_var($url, FILTER_VALIDATE_URL)) {
			try {
				$data = @UltimateSocialDeux::remote_get( $url );
				if (isset($data[ 'subscribers' ])) {
					$count = intval($data[ 'subscribers' ]);
				}
			} catch (Exception $e) {
				$count = 0;
			}
		}
		if( !empty( $count ) ) {

			$data = $count;

			if ($manuel) {
				$data = $count + $manuel;
			}
			self::update_count($data, 'feedpress');
		}

		if( empty( $count ) && !empty( $options['feedpress'] ) ) {
			$count = $options['feedpress'];
		}

		if( empty( $count ) ) {
			$count = 0;
		}

		return $count + $manual;
	}

	/**
	 * Return post count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function posts_count() {
		$count_posts = wp_count_posts();
		$count = $count_posts->publish ;
		return $count;
	}

	/**
	 * Return comments count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function comments_count() {
		$comments_count = wp_count_comments() ;
		$count = $comments_count->approved ;
		return $count;
	}

	/**
	 * Return members count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 count
	 */
	public static function members_count() {
		$members_count = count_users() ;
		$count = $members_count['total_users'] ;
		return $count;
	}

}
