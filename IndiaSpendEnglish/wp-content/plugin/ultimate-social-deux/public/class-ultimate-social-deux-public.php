<?php
/**
 * Ultimate Social Deux.
 *
 * @package		Ultimate Social Deux
 * @author		Ultimate Wordpress <hello@ultimate-wp.com>
 * @link		http://social.ultimate-wp.com
 * @copyright 	2013 Ultimate Wordpress
 */

class UltimateSocialDeux {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since	1.0.0
	 *
	 * @var	 string
	 */
	const VERSION = '1.0.0';

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since	1.0.0
	 *
	 * @var		string
	 */
	protected $plugin_slug = 'ultimate-social-deux';

	/**
	 * Instance of this class.
	 *
	 * @since	1.0.0
	 *
	 * @var		object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since	 1.0.0
	 */
	private function __construct() {

		add_action( 'init', array( $this, 'load_textdomain' ) );

		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );

		add_action( 'wp_ajax_nopriv_us_send_mail', array( $this, 'us_send_mail' ) );

		add_action( 'wp_ajax_us_send_mail', array( $this, 'us_send_mail' ) );

		add_action( 'wp_ajax_nopriv_us_counts', array( $this, 'us_counts' ) );

		add_action( 'wp_ajax_us_counts', array( $this, 'us_counts' ) );

		add_action( 'wp_ajax_nopriv_us_love', array( $this, 'us_love_button_ajax' ) );

		add_action( 'wp_ajax_us_love', array( $this, 'us_love_button_ajax' ) );

		add_action( 'wp_ajax_nopriv_us_bitly', array( $this, 'us_bitly_shortener' ) );

		add_action( 'wp_ajax_us_bitly', array( $this, 'us_bitly_shortener' ) );

		add_action( 'template_redirect', array( $this, 'custom_css' ) );

		add_action( 'wp_head', array( $this, 'open_graph_tags' ), 1 );

		add_action( 'wp_footer', array( $this, 'us_mail_form' ) );

	//	if (!is_admin()) {
	//		echo '<pre>';
	//		print_r( unserialize(get_option( "ultimate-social-deux_options" ) ));
	//		echo '</pre>';
	//	}

	}

	/**
	 * If trigger (query var) is tripped, load our pseudo-stylesheet
	 *
	 * We would prefer to esc $content at the very last moment, but we need to allow the > character.
	 *
	 * @since 1.3
	 */
	public static function custom_css() {

		$custom_css = self::opt('us_custom_css', '' );
		$floating_speed = self::opt('us_floating_speed', 1000);

		$facebook_color = self::opt('us_facebook_color', '#3b5998');
		$twitter_color = self::opt('us_twitter_color', '#00ABF0');
		$googleplus_color = self::opt('us_googleplus_color', '#D95232');
		$delicious_color = self::opt('us_delicious_color', '#66B2FD');
		$stumble_color = self::opt('us_stumble_color', '#E94B24');
		$linkedin_color = self::opt('us_linkedin_color', '#1C86BC');
		$pinterest_color = self::opt('us_pinterest_color', '#AE181F');
		$buffer_color = self::opt('us_buffer_color', '#000000');
		$reddit_color = self::opt('us_reddit_color', '#CEE3F8');
		$vkontakte_color = self::opt('us_vkontakte_color', '#537599');
		$mail_color = self::opt('us_mail_color', '#666666');
		$love_color = self::opt('us_love_color', '#FF0000');
		$pocket_color = self::opt('us_pocket_color', '#ee4056');
		$tumblr_color = self::opt('us_tumblr_color', '#529ecc');
		$print_color = self::opt('us_print_color', '#60d0d4');
		$flipboard_color = self::opt('us_flipboard_color', '#c10000');
		$comments_color = self::opt('us_comments_color', '#b69823');
		$feedly_color = self::opt('us_feedly_color', '#414141');
		$youtube_color = self::opt('us_youtube_color', '#cc181e');
		$vimeo_color = self::opt('us_vimeo_color', '#1bb6ec');
		$dribbble_color = self::opt('us_dribbble_color', '#f72b7f');
		$envato_color = self::opt('us_envato_color', '#82b540');
		$github_color = self::opt('us_github_color', '#201e1f');
		$soundcloud_color = self::opt('us_soundcloud_color', '#ff6f00');
		$instagram_color = self::opt('us_instagram_color', '#48769c');
		$feedpress_color = self::opt('us_feedpress_color', '#ffafaf');
		$mailchimp_color = self::opt('us_mailchimp_color', '#6dc5dc');
		$flickr_color = self::opt('us_flickr_color', '#0062dd');
		$members_color = self::opt('us_members_color', '#0ab071');
		$posts_color = self::opt('us_posts_color', '#924e2a');
		$behance_color = self::opt('us_behance_color', '#1769ff');
		$ok_color = self::opt('us_ok_color', '#f2720c');
		$weibo_color = self::opt('us_weibo_color', '#e64141');
		$managewp_color = self::opt('us_managewp_color', '#098ae0');
		$xing_color = self::opt('us_xing_color', '#026466');
		$whatsapp_color = self::opt('us_whatsapp_color', '#34af23');
		$meneame_color = self::opt('us_meneame_color', '#ff6400');
		$more_color = self::opt('us_more_color', '#53B27C');
		$digg_color = self::opt('us_digg_color', '#000');
		$hover_color = self::opt('us_hover_color', '#008000');
		$sticky_background = self::opt('us_sticky_background_color', '#ffffff');

		$border_radius_sharing_top_left = self::opt('us_border_radius_sharing_top_left', '0');
		$border_radius_sharing_top_right = self::opt('us_border_radius_sharing_top_right', '0');
		$border_radius_sharing_bottom_left = self::opt('us_border_radius_sharing_bottom_left', '0');
		$border_radius_sharing_bottom_right = self::opt('us_border_radius_sharing_bottom_right', '0');

		$border_radius_fan_count_top_left = self::opt('us_border_radius_fan_count_top_left', '0');
		$border_radius_fan_count_top_right = self::opt('us_border_radius_fan_count_top_right', '0');
		$border_radius_fan_count_bottom_left = self::opt('us_border_radius_fan_count_bottom_left', '0');
		$border_radius_fan_count_bottom_right = self::opt('us_border_radius_fan_count_bottom_right', '0');

		$content = '';
		if ( $custom_css ) {
			$raw_content = isset( $custom_css ) ? $custom_css : '';
			$esc_content = esc_html( $raw_content );
			$content .= str_replace( '&gt;', '>', $esc_content );
		}

		$content .= sprintf(".us_sticky .us_wrapper { background-color:%s; }", $sticky_background);

		$content .= sprintf(".us_floating .us_wrapper .us_button { width: 45px; -webkit-transition: width %sms ease-in-out, background-color 400ms ease-out; -moz-transition: width %sms ease-in-out, background-color 400ms ease-out; -o-transition: width %sms ease-in-out, background-color 400ms ease-out; transition: width %sms ease-in-out, background-color 400ms ease-out; }", $floating_speed, $floating_speed, $floating_speed, $floating_speed );
		$content .= sprintf(".us_floating .us_wrapper .us_button:hover { width: 90px;-webkit-transition: width %sms ease-in-out, background-color 400ms ease-out; -moz-transition: width %sms ease-in-out, background-color 400ms ease-out; -o-transition: width %sms ease-in-out, background-color 400ms ease-out; transition: width %sms ease-in-out, background-color 400ms ease-out; }", $floating_speed, $floating_speed, $floating_speed, $floating_speed);
		$content .= sprintf(".us_fan_count_button { -moz-border-radius-topleft: %spx; -moz-border-radius-topright: %spx; -moz-border-radius-bottomright: %spx; -moz-border-radius-bottomleft: %spx; border-top-left-radius: %spx; border-top-right-radius: %spx; border-bottom-right-radius: %spx; border-bottom-left-radius: %spx; -webkit-border-top-left-radius: %spx; -webkit-border-top-right-radius: %spx; -webkit-border-bottom-right-radius: %spx; -webkit-border-bottom-left-radius: %spx; }", $border_radius_fan_count_top_left, $border_radius_fan_count_top_right, $border_radius_fan_count_bottom_right, $border_radius_fan_count_bottom_left, $border_radius_fan_count_top_left, $border_radius_fan_count_top_right, $border_radius_fan_count_bottom_right, $border_radius_fan_count_bottom_left, $border_radius_fan_count_top_left, $border_radius_fan_count_top_right, $border_radius_fan_count_bottom_right, $border_radius_fan_count_bottom_left );

		$content .= sprintf(".us_skin_default .us_button { -moz-border-radius-topleft: %spx; -moz-border-radius-topright: %spx; -moz-border-radius-bottomright: %spx; -moz-border-radius-bottomleft: %spx; border-top-left-radius: %spx; border-top-right-radius: %spx; border-bottom-right-radius: %spx; border-bottom-left-radius: %spx; -webkit-border-top-left-radius: %spx; -webkit-border-top-right-radius: %spx; -webkit-border-bottom-right-radius: %spx; -webkit-border-bottom-left-radius: %spx; }", $border_radius_sharing_top_left, $border_radius_sharing_top_right, $border_radius_sharing_bottom_right, $border_radius_sharing_bottom_left, $border_radius_sharing_top_left, $border_radius_sharing_top_right, $border_radius_sharing_bottom_right, $border_radius_sharing_bottom_left, $border_radius_sharing_top_left, $border_radius_sharing_top_right, $border_radius_sharing_bottom_right, $border_radius_sharing_bottom_left );
		$content .= sprintf(".us_skin_default div[class*='us_facebook'], .us_skin_modern div[class*='us_facebook'] { background-color:%s; }", $facebook_color);
		$content .= sprintf(".us_skin_default div[class*='us_twitter'], .us_skin_modern div[class*='us_twitter'] { background-color:%s; }", $twitter_color);
		$content .= sprintf(".us_skin_default div[class*='us_google'], .us_skin_modern div[class*='us_google'] { background-color:%s; }", $googleplus_color);
		$content .= sprintf(".us_skin_default div[class*='us_delicious'], .us_skin_modern div[class*='us_delicious'] { background-color:%s; }", $delicious_color);
		$content .= sprintf(".us_skin_default div[class*='us_stumble'], .us_skin_modern div[class*='us_stumble'] { background-color:%s; }", $stumble_color);
		$content .= sprintf(".us_skin_default div[class*='us_linkedin'], .us_skin_modern div[class*='us_linkedin'] { background-color:%s; }", $linkedin_color);
		$content .= sprintf(".us_skin_default div[class*='us_pinterest'], .us_skin_modern div[class*='us_pinterest'] { background-color:%s; }", $pinterest_color);
		$content .= sprintf(".us_skin_default div[class*='us_buffer'], .us_skin_modern div[class*='us_buffer'] { background-color:%s; }", $buffer_color);
		$content .= sprintf(".us_skin_default div[class*='us_reddit'], .us_skin_modern div[class*='us_reddit'] { background-color:%s; }", $reddit_color);
		$content .= sprintf(".us_skin_default div[class*='us_vkontakte'], .us_skin_modern div[class*='us_vkontakte'] { background-color:%s; }", $vkontakte_color);
		$content .= sprintf(".us_skin_default .us_mail, .us_skin_modern .us_mail { background-color:%s; }", $mail_color);
		$content .= sprintf(".us_skin_default div[class*='us_love'], .us_skin_modern div[class*='us_love'] { background-color:%s; }", $love_color);
		$content .= sprintf(".us_skin_default div[class*='us_pocket'], .us_skin_modern div[class*='us_pocket'] { background-color:%s; }", $pocket_color);
		$content .= sprintf(".us_skin_default div[class*='us_tumblr'], .us_skin_modern div[class*='us_tumblr'] { background-color:%s; }", $tumblr_color);
		$content .= sprintf(".us_skin_default div[class*='us_print'], .us_skin_modern div[class*='us_print'] { background-color:%s; }", $print_color);
		$content .= sprintf(".us_skin_default div[class*='us_flipboard'], .us_skin_modern div[class*='us_flipboard'] { background-color:%s; }", $flipboard_color);
		$content .= sprintf(".us_skin_default div[class*='us_comments'], .us_skin_modern div[class*='us_comments']{ background-color:%s; }", $comments_color);
		$content .= sprintf(".us_skin_default div[class*='us_feedly'], .us_skin_modern div[class*='us_feedly'] { background-color:%s; }", $feedly_color);
		$content .= sprintf(".us_skin_default div[class*='us_youtube'], .us_skin_modern div[class*='us_youtube'] { background-color:%s; }", $youtube_color);
		$content .= sprintf(".us_skin_default div[class*='us_vimeo'], .us_skin_modern div[class*='us_vimeo'] { background-color:%s; }", $vimeo_color);
		$content .= sprintf(".us_skin_default div[class*='us_behance'], .us_skin_modern div[class*='us_behance'] { background-color:%s; }", $behance_color);
		$content .= sprintf(".us_skin_default div[class*='us_ok'], .us_skin_modern div[class*='us_ok'] { background-color:%s; }", $ok_color);
		$content .= sprintf(".us_skin_default div[class*='us_weibo'], .us_skin_modern div[class*='us_weibo'] { background-color:%s; }", $weibo_color);
		$content .= sprintf(".us_skin_default div[class*='us_managewp'], .us_skin_modern div[class*='us_managewp'] { background-color:%s; }", $managewp_color);
		$content .= sprintf(".us_skin_default div[class*='us_xing'], .us_skin_modern div[class*='us_xing'] { background-color:%s; }", $xing_color);
		$content .= sprintf(".us_skin_default div[class*='us_whatsapp'], .us_skin_modern div[class*='us_whatsapp'] { background-color:%s; }", $whatsapp_color);
		$content .= sprintf(".us_skin_default div[class*='us_meneame'], .us_skin_modern div[class*='us_meneame'] { background-color:%s; }", $meneame_color);
		$content .= sprintf(".us_skin_default div[class*='us_digg'], .us_skin_modern div[class*='us_digg'] { background-color:%s; }", $digg_color);
		$content .= sprintf(".us_skin_default div[class*='us_dribbble'], .us_skin_modern div[class*='us_dribbble'] { background-color:%s; }", $dribbble_color);
		$content .= sprintf(".us_skin_default div[class*='us_envato'], .us_skin_modern div[class*='us_envato'] { background-color:%s; }", $envato_color);
		$content .= sprintf(".us_skin_default div[class*='us_github'], .us_skin_modern div[class*='us_github'] { background-color:%s; }", $github_color);
		$content .= sprintf(".us_skin_default div[class*='us_soundcloud'], .us_skin_modern div[class*='us_soundcloud'] { background-color:%s; }", $soundcloud_color);
		$content .= sprintf(".us_skin_default div[class*='us_instagram'], .us_skin_modern div[class*='us_instagram'] { background-color:%s; }", $instagram_color);
		$content .= sprintf(".us_skin_default div[class*='us_feedpress'], .us_skin_modern div[class*='us_feedpress'] { background-color:%s; }", $feedpress_color);
		$content .= sprintf(".us_skin_default div[class*='us_mailchimp'], .us_skin_modern div[class*='us_mailchimp'] { background-color:%s; }", $mailchimp_color);
		$content .= sprintf(".us_skin_default div[class*='us_flickr'], .us_skin_modern div[class*='us_flickr'] { background-color:%s; }", $flickr_color);
		$content .= sprintf(".us_skin_default div[class*='us_members'], .us_skin_modern div[class*='us_members'] { background-color:%s; }", $members_color);
		$content .= sprintf(".us_skin_default div[class*='us_more'], .us_skin_modern div[class*='us_more'] { background-color:%s; }", $more_color);
		$content .= sprintf(".us_skin_default .us_posts_fan_count, .us_skin_modern .us_posts_fan_count { background-color:%s; }", $posts_color);
		$content .= sprintf(".us_skin_default .us_button:hover, .us_skin_modern .us_fan_count_button:hover, .us_skin_modern .us_button:hover, .us_skin_modern .us_fan_count_button:hover { background-color:%s; }", $hover_color);

		$content .= sprintf(".us_skin_simple div[class*='us_facebook'] a, .us_skin_minimal div[class*='us_facebook'] a { color:%s; }", $facebook_color);
		$content .= sprintf(".us_skin_simple div[class*='us_twitter'] a, .us_skin_minimal div[class*='us_twitter'] a { color:%s; }", $twitter_color);
		$content .= sprintf(".us_skin_simple div[class*='us_google'] a, .us_skin_minimal div[class*='us_google'] a { color:%s; }", $googleplus_color);
		$content .= sprintf(".us_skin_simple div[class*='us_delicious'] a, .us_skin_minimal div[class*='us_delicious'] a { color:%s; }", $delicious_color);
		$content .= sprintf(".us_skin_simple div[class*='us_stumble'] a, .us_skin_minimal div[class*='us_stumble'] a { color:%s; }", $stumble_color);
		$content .= sprintf(".us_skin_simple div[class*='us_linkedin'] a, .us_skin_minimal div[class*='us_linkedin'] a { color:%s; }", $linkedin_color);
		$content .= sprintf(".us_skin_simple div[class*='us_pinterest'] a, .us_skin_minimal div[class*='us_pinterest'] a { color:%s; }", $pinterest_color);
		$content .= sprintf(".us_skin_simple div[class*='us_buffer'] a, .us_skin_minimal div[class*='us_buffer'] a { color:%s; }", $buffer_color);
		$content .= sprintf(".us_skin_simple div[class*='us_reddit'] a, .us_skin_minimal div[class*='us_reddit'] a { color:%s; }", $reddit_color);
		$content .= sprintf(".us_skin_simple div[class*='us_vkontakte'] a, .us_skin_minimal div[class*='us_vkontakte'] a { color:%s; }", $vkontakte_color);
		$content .= sprintf(".us_skin_simple .us_mail a, .us_skin_minimal .us_mail a { color:%s; }", $mail_color);
		$content .= sprintf(".us_skin_simple div[class*='us_love'] a, .us_skin_minimal div[class*='us_love'] a { color:%s; }", $love_color);
		$content .= sprintf(".us_skin_simple div[class*='us_pocket'] a, .us_skin_minimal div[class*='us_pocket'] a { color:%s; }", $pocket_color);
		$content .= sprintf(".us_skin_simple div[class*='us_tumblr'] a, .us_skin_minimal div[class*='us_tumblr'] a { color:%s; }", $tumblr_color);
		$content .= sprintf(".us_skin_simple div[class*='us_print'] a, .us_skin_minimal div[class*='us_print'] a { color:%s; }", $print_color);
		$content .= sprintf(".us_skin_simple div[class*='us_flipboard'] a, .us_skin_minimal div[class*='us_flipboard'] a { color:%s; }", $flipboard_color);
		$content .= sprintf(".us_skin_simple div[class*='us_comments'] *, .us_skin_minimal div[class*='us_comments'] * { color:%s; }", $comments_color);
		$content .= sprintf(".us_skin_simple div[class*='us_feedly'] a, .us_skin_minimal div[class*='us_feedly'] a { color:%s; }", $feedly_color);
		$content .= sprintf(".us_skin_simple div[class*='us_youtube'] a, .us_skin_minimal div[class*='us_youtube'] a { color:%s; }", $youtube_color);
		$content .= sprintf(".us_skin_simple div[class*='us_vimeo'] a, .us_skin_minimal div[class*='us_vimeo'] a { color:%s; }", $vimeo_color);
		$content .= sprintf(".us_skin_simple div[class*='us_behance'] a, .us_skin_minimal div[class*='us_behance'] a { color:%s; }", $behance_color);
		$content .= sprintf(".us_skin_simple div[class*='us_ok'] a, .us_skin_minimal div[class*='us_ok'] a { color:%s; }", $ok_color);
		$content .= sprintf(".us_skin_simple div[class*='us_weibo'] a, .us_skin_minimal div[class*='us_weibo'] a { color:%s; }", $weibo_color);
		$content .= sprintf(".us_skin_simple div[class*='us_managewp'] a, .us_skin_minimal div[class*='us_managewp'] a { color:%s; }", $managewp_color);
		$content .= sprintf(".us_skin_simple div[class*='us_xing'] a, .us_skin_minimal div[class*='us_xing'] a { color:%s; }", $xing_color);
		$content .= sprintf(".us_skin_simple div[class*='us_whatsapp'] a, .us_skin_minimal div[class*='us_whatsapp'] a { color:%s; }", $whatsapp_color);
		$content .= sprintf(".us_skin_simple div[class*='us_meneame'] a, .us_skin_minimal div[class*='us_meneame'] a { color:%s; }", $meneame_color);
		$content .= sprintf(".us_skin_simple div[class*='us_digg'] a, .us_skin_minimal div[class*='us_digg'] a { color:%s; }", $digg_color);
		$content .= sprintf(".us_skin_simple div[class*='us_dribbble'] a, .us_skin_minimal div[class*='us_dribbble'] a { color:%s; }", $dribbble_color);
		$content .= sprintf(".us_skin_simple div[class*='us_envato'] a, .us_skin_minimal div[class*='us_envato'] a { color:%s; }", $envato_color);
		$content .= sprintf(".us_skin_simple div[class*='us_github'] a, .us_skin_minimal div[class*='us_github'] a { color:%s; }", $github_color);
		$content .= sprintf(".us_skin_simple div[class*='us_soundcloud'] a, .us_skin_minimal div[class*='us_soundcloud'] a { color:%s; }", $soundcloud_color);
		$content .= sprintf(".us_skin_simple div[class*='us_instagram'] a, .us_skin_minimal div[class*='us_instagram'] a { color:%s; }", $instagram_color);
		$content .= sprintf(".us_skin_simple div[class*='us_feedpress'], .us_skin_minimal div[class*='us_feedpress'] a { color:%s; }", $feedpress_color);
		$content .= sprintf(".us_skin_simple div[class*='us_mailchimp'], .us_skin_minimal div[class*='us_mailchimp'] { color:%s; }", $mailchimp_color);
		$content .= sprintf(".us_skin_simple div[class*='us_flickr'] a, .us_skin_minimal div[class*='us_flickr'] a { color:%s; }", $flickr_color);
		$content .= sprintf(".us_skin_simple div[class*='us_members'], .us_skin_minimal div[class*='us_members'] { color:%s; }", $members_color);
		$content .= sprintf(".us_skin_simple div[class*='us_more'] a, .us_skin_minimal div[class*='us_more'] a { color:%s; }", $more_color);
		$content .= sprintf(".us_skin_simple .us_posts_fan_count, .us_skin_minimal .us_posts_fan_count { color:%s; }", $posts_color);
		$content .= sprintf(".us_skin_simple .us_button:hover a, .us_skin_minimal .us_button:hover a { color:%s; }", $hover_color);

		$content .= sprintf(".us_skin_round div[class*='us_facebook'] .us_share, .us_skin_round div[class*='us_facebook'].us_fan_count { background-color:%s; }", $facebook_color);
		$content .= sprintf(".us_skin_round div[class*='us_twitter'] .us_share, .us_skin_round div[class*='us_twitter'].us_fan_count { background-color:%s; }", $twitter_color);
		$content .= sprintf(".us_skin_round div[class*='us_google'] .us_share, .us_skin_round div[class*='us_google'].us_fan_count { background-color:%s; }", $googleplus_color);
		$content .= sprintf(".us_skin_round div[class*='us_delicious'] .us_share, .us_skin_round div[class*='us_delicious'].us_fan_count { background-color:%s; }", $delicious_color);
		$content .= sprintf(".us_skin_round div[class*='us_stumble'] .us_share { background-color:%s; }", $stumble_color);
		$content .= sprintf(".us_skin_round div[class*='us_linkedin'] .us_share, .us_skin_round div[class*='us_linkedin'].us_fan_count { background-color:%s; }", $linkedin_color);
		$content .= sprintf(".us_skin_round div[class*='us_pinterest'] .us_share, .us_skin_round div[class*='us_pinterest'].us_fan_count { background-color:%s; }", $pinterest_color);
		$content .= sprintf(".us_skin_round div[class*='us_buffer'] .us_share { background-color:%s; }", $buffer_color);
		$content .= sprintf(".us_skin_round div[class*='us_reddit'] .us_share { background-color:%s; }", $reddit_color);
		$content .= sprintf(".us_skin_round div[class*='us_vkontakte'] .us_share, .us_skin_round div[class*='us_vkontakte'].us_fan_count { background-color:%s; }", $vkontakte_color);
		$content .= sprintf(".us_skin_round .us_mail .us_share { background-color:%s; }", $mail_color);
		$content .= sprintf(".us_skin_round div[class*='us_love'] .us_share, .us_skin_round div[class*='us_love'].us_fan_count { background-color:%s; }", $love_color);
		$content .= sprintf(".us_skin_round div[class*='us_pocket'] .us_share { background-color:%s; }", $pocket_color);
		$content .= sprintf(".us_skin_round div[class*='us_tumblr'] .us_share { background-color:%s; }", $tumblr_color);
		$content .= sprintf(".us_skin_round div[class*='us_print'] .us_share { background-color:%s; }", $print_color);
		$content .= sprintf(".us_skin_round div[class*='us_flipboard'] .us_share { background-color:%s; }", $flipboard_color);
		$content .= sprintf(".us_skin_round div[class*='us_more'] .us_share { background-color:%s; }", $more_color);
		$content .= sprintf(".us_skin_round div[class*='us_comments'] .us_share, .us_skin_round div[class*='us_comments'].us_fan_count { background-color:%s; }", $comments_color);
		$content .= sprintf(".us_skin_round div[class*='us_feedly'] .us_share { background-color:%s; }", $feedly_color);
		$content .= sprintf(".us_skin_round div[class*='us_youtube'].us_fan_count { background-color:%s; }", $youtube_color);
		$content .= sprintf(".us_skin_round div[class*='us_vimeo'].us_fan_count { background-color:%s; }", $vimeo_color);
		$content .= sprintf(".us_skin_round div[class*='us_behance'].us_fan_count { background-color:%s; }", $behance_color);
		$content .= sprintf(".us_skin_round div[class*='us_ok'] .us_share { background-color:%s; }", $ok_color);
		$content .= sprintf(".us_skin_round div[class*='us_weibo'] .us_share { background-color:%s; }", $weibo_color);
		$content .= sprintf(".us_skin_round div[class*='us_managewp'] .us_share { background-color:%s; }", $managewp_color);
		$content .= sprintf(".us_skin_round div[class*='us_xing'] .us_share { background-color:%s; }", $xing_color);
		$content .= sprintf(".us_skin_round div[class*='us_whatsapp'].us_fan_count { background-color:%s; }", $whatsapp_color);
		$content .= sprintf(".us_skin_round div[class*='us_meneame'].us_fan_count { background-color:%s; }", $meneame_color);
		$content .= sprintf(".us_skin_round div[class*='us_digg'].us_fan_count { background-color:%s; }", $digg_color);
		$content .= sprintf(".us_skin_round div[class*='us_dribbble'].us_fan_count { background-color:%s; }", $dribbble_color);
		$content .= sprintf(".us_skin_round div[class*='us_envato'].us_fan_count { background-color:%s; }", $envato_color);
		$content .= sprintf(".us_skin_round div[class*='us_github'].us_fan_count { background-color:%s; }", $github_color);
		$content .= sprintf(".us_skin_round div[class*='us_soundcloud'].us_fan_count  { background-color:%s; }", $soundcloud_color);
		$content .= sprintf(".us_skin_round div[class*='us_instagram'].us_fan_count  { background-color:%s; }", $instagram_color);
		$content .= sprintf(".us_skin_round div[class*='us_feedpress'].us_fan_count { background-color:%s; }", $feedpress_color);
		$content .= sprintf(".us_skin_round div[class*='us_mailchimp'].us_fan_count { background-color:%s; }", $mailchimp_color);
		$content .= sprintf(".us_skin_round div[class*='us_flickr'].us_fan_count { background-color:%s; }", $flickr_color);
		$content .= sprintf(".us_skin_round div[class*='us_members'].us_fan_count { background-color:%s; }", $members_color);
		$content .= sprintf(".us_skin_round .us_posts_fan_count.us_fan_count { background-color:%s; }", $posts_color);
		$content .= sprintf(".us_skin_round .us_button:hover .us_share, .us_skin_round .us_button.us_fan_count:hover { background-color:%s; }", $hover_color);

		return $content;
	}

	/**
	* Register and enqueue public-facing style sheet.
	*
	* @since	1.0.0
	*/
	public function register_styles() {
		wp_register_style( 'us-plugin-styles', plugins_url( 'assets/css/style.css', __FILE__ ), array(), ULTIMATE_SOCIAL_DEUX_VERSION );
		if (self::opt('us_enqueue', 'all_pages') == 'all_pages') {
			self::enqueue_stuff();
		}
	}

	/**
	* Register and enqueues public-facing JavaScript files.
	*
	* @since	1.0.0
	*/
	public function register_scripts() {

		wp_register_script( 'us-script', plugins_url( 'assets/js/script.js',__FILE__ ), array('jquery', 'jquery-color'), ULTIMATE_SOCIAL_DEUX_VERSION );
		wp_localize_script( 'us-script', 'us_script',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'tweet_via' => self::opt('us_tweet_via', ''),
				'sharrre_url' => admin_url( 'admin-ajax.php' ),
				'success' => self::opt('us_mail_success', __('Great work! Your message was sent.', 'ultimate-social-deux' ) ),
				'trying' => self::opt('us_mail_try', __('Trying to send email...', 'ultimate-social-deux' ) ),
				'total_shares_text' => self::opt('us_total_shares_text', __( 'Shares', 'ultimate-social-deux' ) ),
				'facebook_height' => intval(self::opt('us_facebook_height', '500' ) ),
				'facebook_width' => intval(self::opt('us_facebook_width', '900' ) ),
				'twitter_height' => intval(self::opt('us_twitter_height', '500' ) ),
				'twitter_width' => intval(self::opt('us_twitter_width', '900' ) ),
				'googleplus_height' => intval(self::opt('us_googleplus_height', '500') ),
				'googleplus_width' => intval(self::opt('us_googleplus_width', '900') ),
				'delicious_height' => intval(self::opt('us_delicious_height', '550') ),
				'delicious_width' => intval(self::opt('us_delicious_width', '550') ),
				'stumble_height' => intval(self::opt('us_stumble_height', '550') ),
				'stumble_width' => intval(self::opt('us_stumble_width', '550') ),
				'linkedin_height' => intval(self::opt('us_linkedin_height', '550') ),
				'linkedin_width' => intval(self::opt('us_linkedin_width', '550') ),
				'pinterest_height' => intval(self::opt('us_pinterest_height', '320') ),
				'pinterest_width' => intval(self::opt('us_pinterest_width', '720') ),
				'buffer_height' => intval(self::opt('us_buffer_height', '500' ) ),
				'buffer_width' => intval(self::opt('us_buffer_width', '900' ) ),
				'reddit_height' => intval(self::opt('us_reddit_height', '500' ) ),
				'reddit_width' => intval(self::opt('us_reddit_width', '900' ) ),
				'vkontakte_height' => intval(self::opt('us_vkontakte_height', '500')),
				'vkontakte_width' => intval(self::opt('us_vkontakte_width', '900')),
				'printfriendly_height' => intval(self::opt('us_printfriendly_height', '500')),
				'printfriendly_width' => intval(self::opt('us_printfriendly_width', '1045')),
				'pocket_height' => intval(self::opt('us_pocket_height', '500')),
				'pocket_width' => intval(self::opt('us_pocket_width', '900')),
				'tumblr_height' => intval(self::opt('us_tumblr_height', '500')),
				'tumblr_width' => intval(self::opt('us_tumblr_width', '900')),
				'flipboard_height' => intval(self::opt('us_flipboard_height', '500')),
				'flipboard_width' => intval(self::opt('us_flipboard_width', '900')),
				'weibo_height' => intval(self::opt('us_weibo_height', '500')),
				'weibo_width' => intval(self::opt('us_weibo_width', '900')),
				'xing_height' => intval(self::opt('us_xing_height', '500')),
				'xing_width' => intval(self::opt('us_xing_width', '900')),
				'ok_height' => intval(self::opt('us_ok_height', '500')),
				'ok_width' => intval(self::opt('us_ok_width', '900')),
				'managewp_height' => intval(self::opt('us_managewp_height', '500')),
				'managewp_width' => intval(self::opt('us_managewp_width', '900')),
				'meneame_height' => intval(self::opt('us_meneame_height', '500')),
				'meneame_width' => intval(self::opt('us_meneame_width', '900')),
				'digg_height' => intval(self::opt('us_digg_height', '500')),
				'digg_width' => intval(self::opt('us_digg_width', '900')),
				'vkontakte_appid' => self::opt('us_vkontakte_appid', ''),
				'facebook_appid' => self::opt('us_facebook_appid', ''),
				'home_url' => home_url(),
				'nonce' => wp_create_nonce( 'us_nonce' ),
				'already_loved_message' => __( 'You have already loved this item.', 'ultimate-social-deux' ),
				'error_message' => __( 'Sorry, there was a problem processing your request.', 'ultimate-social-deux' ),
				'logged_in' => is_user_logged_in() ? 'true' : 'false',
				'bitly' => ( self::opt('us_bitly_access_token', '') ) ? 'true' : 'false',
			)
		);

	}

	/**
	* Enqueues public-facing JavaScript and CSS files.
	*
	* @since	1.0.0
	*/
	public static function enqueue_stuff() {

		if (self::opt('us_enqueue', 'all_pages') != 'manually') {
			wp_enqueue_style( 'us-plugin-styles' );
			wp_add_inline_style( 'us-plugin-styles', self::custom_css() );
			wp_enqueue_script( 'us-script' );
		}

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since	1.0.0
	 *
	 * @return	Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

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
	 * Load the plugin text domain for translation.
	 *
	 * @since	1.0.0
	 */
	public function load_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

		load_textdomain( $domain, realpath(dirname(__FILE__) . '/..') . '/languages' . '/' . $domain . '-' . $locale . '.mo' );

	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since	1.0.0
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses
	 *										"Network Activate" action, false if
	 *										WPMU is disabled or plugin is
	 *										activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide	) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since	1.0.0
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses
	 *										"Network Deactivate" action, false if
	 *										WPMU is disabled or plugin is
	 *										deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

			if ( function_exists( 'is_multisite' ) && is_multisite() ) {

					if ( $network_wide ) {

							// Get all blog ids
							$blog_ids = self::get_blog_ids();

							foreach ( $blog_ids as $blog_id ) {

									switch_to_blog( $blog_id );
									self::single_deactivate();

							}

							restore_current_blog();

					} else {
							self::single_deactivate();
					}

			} else {
					self::single_deactivate();
			}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since	1.0.0
	 *
	 * @param	int	$blog_id	ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

			if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
					return;
			}

			switch_to_blog( $blog_id );
			self::single_activate();
			restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since	1.0.0
	 *
	 * @return	array|false	The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

			global $wpdb;

			// get an array of blog ids
			$sql = "SELECT blog_id FROM $wpdb->blogs
					WHERE archived = '0' AND spam = '0'
					AND deleted = '0'";

			return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since	1.0.0
	 */
	private static function single_activate() {

		wp_cache_flush();

		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush();
		}
		if ( function_exists( 'w3tc_minify_flush' ) ) {
			w3tc_minify_flush();
		}

	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since	1.0.0
	 */
	private static function single_deactivate() {

		delete_option( 'us_fan_count_counters' );
		delete_transient( 'us_fan_count_counters' );

	}

	public static function opt_old( $option, $section, $default = '' ) {

		$options = get_option( $section );

		if ( isset( $options[$option] ) ) {
			return $options[$option];
		}

		return $default;
	}

	public static function opt( $option, $default = '' ) {

		$options = unserialize(get_option( "ultimate-social-deux_options" ) );

		if ( isset($options[$option]) ) {
			return $options[$option];
		}

		return $default;
	}

	/**
	 * Replace strings for mail options
	 *
	 * @since	1.0.0
	 *
	 * @return	Replaced string
	 */
	public static function mail_replace_vars( $string, $url, $sender_name, $sender_email ) {

		if ( in_the_loop() || is_singular() ) {
			$post_title = get_the_title();
			$post_url = get_permalink();

			global $post;
			$post = get_post();
			$author_id=$post->post_author;
			$user_info = get_userdata($author_id);
			$post_author = $user_info->user_nicename;
		} elseif ( is_home() ) {
			$post_title = get_bloginfo('name');
			$post_url = get_bloginfo('url');
			$post_author = get_bloginfo('name');
		} else {
			$post_title = get_bloginfo('name');
			$post_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$post_author = get_bloginfo('name');
		}

		$post_url = ($url) ? $url: $post_url;

		$site_title = get_bloginfo('name');
		$site_url = get_bloginfo('url');

		$string = str_replace('{post_title}', $post_title, $string);
		$string = str_replace('{post_url}', $post_url, $string);
		$string = str_replace('{post_author}', $post_author, $string);
		$string = str_replace('{site_title}', $site_title, $string);
		$string = str_replace('{site_url}', $site_url, $string);
		$string = str_replace('{sender_name}', $sender_name, $string);
		$string = str_replace('{sender_email}', $sender_email, $string);

		return $string;
	}

	/**
	 * Returns first image of a post or page. If no images found it returns default image.
	 *
	 * @since	1.0.0
	 *
	 * @return	post image
	 */
	public static function catch_first_image($url = '') {

		$post_id = url_to_postid( $url );

		$post = ( get_post($post_id) ) ? get_post($post_id): get_page($post_id);

		$first_img = '';

		$post_content = $post->post_content;

		if ( $post_id ) {
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);

			if ( has_post_thumbnail( $post_id ) ) {
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
				$url = $thumb['0'];

				$first_img = $url;
			} elseif (isset($matches[1][0])) {
				$first_img = $matches[1][0];
			}
		}

		return $first_img;
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public static function buttons($sharetext = '', $networks = array(), $url = '', $align = "center", $count = true, $native = false, $skin = 'default') {

		if ( $url ) {
			$url = $url;
			$text = '';
		} elseif ( is_singular() ) {
			$text = get_the_title();
			$url = get_permalink();
		} elseif ( in_the_loop() ) {
			$text = get_the_title();
			$url = get_permalink();
		} elseif ( is_home() ) {
			$text = get_bloginfo('name');
			$url = get_bloginfo('url');
		} elseif ( is_tax() || is_category() ) {
			global $wp_query;
			$term = $wp_query->get_queried_object();
			$text = $term->name;
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		} else {
			$text = get_bloginfo('name');
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}

		if ($align == 'left') {
			$align = " tal";
		} elseif ($align == 'right') {
			$align = " tar";
		} else {
			$align = " tac";
		}

		$skin = ( $skin ) ? sprintf(' us_skin_%s', $skin): ' us_skin_default';

		$media = self::catch_first_image($url);

		$networks = str_replace(' ', '', $networks);

		$networks = ( is_array($networks) ) ? $networks: explode(',', $networks);

		$count = filter_var($count, FILTER_VALIDATE_BOOLEAN);

		$native = filter_var($native, FILTER_VALIDATE_BOOLEAN);

		$output = sprintf('<div class="us_wrapper%s%s">', $align, $skin);

		$facebook_both = (in_array('facebook', $networks) && in_array('facebook_native', $networks)) ? true: false;

		$google_both = ( (in_array('google', $networks) && in_array('google_native', $networks) ) || (in_array('googleplus', $networks) && in_array('googleplus_native', $networks) ) ) ? true: false;

		$vkontakte_both = (in_array('vkontakte', $networks) && in_array('vkontakte_native', $networks)) ? true: false;

		$more = '';

		if ($sharetext) {
			$output .= sprintf('<div class="us_button us_share_text" data-text="%s"><span class="us_share_text_span"></span></div>', $sharetext);
		}

		foreach ($networks as $key => $button) {

			switch ($button) {
				case 'total':
					$output .= self::total_button($url, $text, $networks, $more);
				break;
				case 'facebook':
					$output .= self::facebook_button($url, $text, $count, $native, false, $more);
				break;
				case 'facebook_native':
					$output .= self::facebook_button($url, $text, $count, true, $facebook_both, $more);
				break;
				case 'twitter':
					$output .= self::twitter_button($url, $text, $count, $more);
				break;
				case 'google':
					$output .= self::google_button($url, $text, $count, $native, false, $more);
				break;
				case 'google_native':
					$output .= self::google_button($url, $text, $count, true, $google_both, $more);
				break;
				case 'googleplus':
					$output .= self::google_button($url, $text, $count, $native, false, $more);
				break;
				case 'googleplus_native':
					$output .= self::google_button($url, $text, $count, true, $google_both, $more);
				break;
				case 'pinterest':
					$output .= self::pinterest_button($url, $text, $count, $more);
				break;
				case 'linkedin':
					$output .= self::linkedin_button($url, $text, $count, $more);
				break;
				case 'stumble':
					$output .= self::stumble_button($url, $text, $count, $more);
				break;
				case 'delicious':
					$output .= self::delicious_button($url, $text, $count, $more);
				break;
				case 'reddit':
					$output .= self::reddit_button($url, $text, $count, $more);
				break;
				case 'buffer':
					$output .= self::buffer_button($url, $text, $media, $count, $more);
				break;
				case 'vkontakte':
					$output .= self::vkontakte_button($url, $text, $media, $count, $native, false, $more);
				break;
				case 'vkontakte_native':
					$output .= self::vkontakte_button($url, $text, $media, $count, true, $vkontakte_both, $more);
				break;
				case 'mail':
					$output .= self::mail_button($url, $more);
				break;
				case 'comments':
					$output .= self::comments_button($url, $count, $more);
				break;
				case 'love':
					$output .= self::love_button($url, $count, $more);
				break;
				case 'pocket':
					$output .= self::pocket_button($url, $text, $count, $more);
				break;
				case 'xing':
					$output .= self::xing_button($url, $text, $count, $more);
				break;
				case 'ok':
					$output .= self::ok_button($url, $text, $count, $more);
				break;
				case 'managewp':
					$output .= self::managewp_button($url, $text, $count, $more);
				break;
				case 'weibo':
					$output .= self::weibo_button($url, $text, $more);
				break;
				case 'tumblr':
					$output .= self::tumblr_button($url, $text, $more);
				break;
				case 'meneame':
					$output .= self::meneame_button($url, $text, $more);
				break;
				case 'digg':
					$output .= self::digg_button($url, $text, $more);
				break;
				case 'whatsapp':
					$output .= self::whatsapp_button($url, $text, $more);
				break;
				case 'print':
					$output .= self::print_button($url, $more);
				break;
				case 'flipboard':
					$output .= self::flipboard_button($url, $text, $more);
				break;
				case 'more':
					$more = ' us_after_more us_display_none';
				break;
			}

		}

		if (in_array('more', $networks)	) {
			$output .= '<div class="us_button us_more us_no_count"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-plus"></i></div></a></div>';
		}

		$output .= '</div>';

		return $output;

	}

	/**
	 * Returns total button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function total_button($url, $text, $networks, $more = '') {

		self::enqueue_stuff();

		$buttons = '';

		$love_number = 0;
		$comments_number = 0;
		$google_number = 0;
		$vkontakte_number = 0;
		$pinterest_number = 0;
		$stumble_number = 0;
		$reddit_number = 0;
		$ok_number = 0;
		$weibo_number = 0;
		$xing_number = 0;
		$managewp_number = 0;

		if( count($networks) === 1 ) {
			$networks = 'facebook,googleplus,vkontakte,stumble,twitter,buffer,delicious,pinteres,reddit,comments,love,linkedin,pocket,ok,weibo,xing,managewp';
			$networks = ( is_array($networks) ) ? $networks: explode(',', $networks);
		}

		if (is_array($networks)) {

			foreach ($networks as $key => $button) {

				if ($button == 'facebook_native') {
					$button = 'facebook';
				} elseif($button == 'google_native' || $button == 'googleplus_native' || $button == 'google' || $button == 'googleplus' ) {
					$transient = get_transient( 'us_counts_'.md5('googlePlus_'.urlencode($url) ) );
					$button = 'googleplus';
					if ( is_numeric($transient) || $transient == 'zoro' ) {
						$google_number = ( $transient == 'zoro' ) ? 0: $transient;
						$button = '';
					}
				} elseif($button == 'vkontakte_native' || $button == 'vkontakte' ) {
					$transient = get_transient( 'us_counts_'.md5('vkontakte_'.urlencode($url) ) );
					$button = 'vkontakte';
					if ( is_numeric($transient) || $transient == 'zoro' ) {
						$vkontakte_number = ( $transient == 'zoro' ) ? 0: $transient;
						$button = '';
					}
				} elseif($button == 'pinterest' ) {
					$transient = get_transient( 'us_counts_'.md5('pinterest_'.urlencode($url) ) );
					$button = 'pinterest';
					if ( is_numeric($transient) || $transient == 'zoro' ) {
						$pinterest_number = ( $transient == 'zoro' ) ? 0: $transient;
						$button = '';
					}
				} elseif($button == 'stumble' ) {
					$transient = get_transient( 'us_counts_'.md5('stumbleupon_'.urlencode($url) ) );
					$button = 'stumble';
					if ( is_numeric($transient) || $transient == 'zoro' ) {
						$stumble_number = ( $transient == 'zoro' ) ? 0: $transient;
						$button = '';
					}
				} elseif($button == 'reddit' ) {
					$transient = get_transient( 'us_counts_'.md5('reddit_'.urlencode($url) ) );
					$button = 'reddit';
					if ( is_numeric($transient) || $transient == 'zoro' ) {
						$reddit_number = ( $transient == 'zoro' ) ? 0: $transient;
						$button = '';
					}
				} elseif($button == 'ok' ) {
					$transient = get_transient( 'us_counts_'.md5('ok_'.urlencode($url) ) );
					$button = 'ok';
					if ( is_numeric($transient) || $transient == 'zoro' ) {
						$ok_number = ( $transient == 'zoro' ) ? 0: $transient;
						$button = '';
					}
				} elseif($button == 'weibo' ) {
					$transient = get_transient( 'us_counts_'.md5('weibo_'.urlencode($url) ) );
					$button = 'weibo';
					if ( is_numeric($transient) || $transient == 'zoro' ) {
						$weibo_number = ( $transient == 'zoro' ) ? 0: $transient;
						$button = '';
					}
				} elseif($button == 'xing' ) {
					$transient = get_transient( 'us_counts_'.md5('xing_'.urlencode($url) ) );
					$button = 'xing';
					if ( is_numeric($transient) || $transient == 'zoro' ) {
						$xing_number = ( $transient == 'zoro' ) ? 0: $transient;
						$button = '';
					}
				} elseif($button == 'managewp' ) {
					$transient = get_transient( 'us_counts_'.md5('managewp_'.urlencode($url) ) );
					$button = 'managewp';
					if ( is_numeric($transient) || $transient == 'zoro' ) {
						$managewp_number = ( $transient == 'zoro' ) ? 0: $transient;
						$button = '';
					}
				} elseif($button == 'total' ) {
					$button = '';
				} elseif($button == 'comments' ) {
					$post_id = url_to_postid( $url );
					$comments_number = ( get_comments_number( $post_id ) ) ? get_comments_number( $post_id ): 0;
					$button = '';
				} elseif($button == 'love') {
					$love_options = get_option( 'us_love_count' );
					$love_number = ( !empty( $love_options['data'][$url]['count'] ) ) ? $love_options['data'][$url]['count']: 0;
					$button = '';
				}

				if ($button) {
					$buttons .= ' data-'.$button.'="true" ';
				}

			}
		}

		$defaults = $love_number + $comments_number + $google_number + $vkontakte_number + $pinterest_number + $stumble_number + $reddit_number + $ok_number + $weibo_number + $xing_number + $managewp_number;

		$button = sprintf('<div class="us_total us_button%s" data-defaults="%s" data-url="%s"%s><div class="us_box" href="#"><div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div><div class="us_share"></div></div></div>', $more, $defaults, $url, $buttons);

		return $button;
	}

	/**
	 * Returns Facebook button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function facebook_button($url, $text, $count = true, $native = false, $both = false, $more = '' ) {

		self::enqueue_stuff();

		$counter = ( $count && !$both ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count && !$both ) ? '': ' us_no_count';

		$native_class = ( $native ) ? ' us_native': '';

		$both_class = ( $both ) ? ' us_both': '';

		$native_markup = ( $native ) ? sprintf('<a class="usnative facebook-like" href="#" data-href="%s" data-layout="button" data-action="like" data-show-faces="true" data-share="false"></a>',$url): '';

		$button = sprintf('<div class="us_facebook%s%s%s%s us_button" data-url="%s" data-text="%s"><a class="us_box" href="#" data-url="%s"><div class="us_share"><i class="us-icon-facebook"></i></div>%s</a>%s</div>', $more, $counter_class, $native_class, $both_class, $url, strip_tags($text), $url, $counter, $native_markup );

		return $button;
	}

	/**
	 * Returns Twitter button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function twitter_button($url, $text, $count = true, $more = '') {

		self::enqueue_stuff();

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_twitter%s%s us_button" data-url="%s" data-text="%s" ><a class="us_box" href="#"><div class="us_share"><i class="us-icon-twitter"></i></div>%s</a></div>', $more, $counter_class, $url, strip_tags($text), $counter );

		return $button;

	}

	/**
	 * Returns Google button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function google_button($url, $text, $count = true, $native = false, $both = false, $more = '') {

		self::enqueue_stuff();

		$transient = get_transient( 'us_counts_'.md5('googlePlus_'.urlencode($url) ) );

		$transient_class = ( is_numeric($transient) || $transient == 'zoro' ) ? ' us_transient': '';

		$number = ( is_numeric($transient) || $transient == 'zoro' ) ? sprintf(' data-count="%s"', self::number_format($transient) ): '';

		$counter = ( $count && !$both ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count && !$both ) ? '': ' us_no_count';

		$native_class = ( $native ) ? ' us_native': '';

		$both_class = ( $both ) ? ' us_both': '';

		$native_markup = ( $native ) ? sprintf('<a class="usnative googleplus-one" href="#" data-href="%s" data-size="medium" data-annotation="none"></a>',$url): '';

		$button = sprintf('<div class="us_googleplus%s%s%s%s%s us_button" data-url="%s" data-text="%s"%s><a class="us_box" href="#" data-url="%s"><div class="us_share"><i class="us-icon-google"></i></div>%s</a>%s</div>', $more, $counter_class, $native_class, $transient_class, $both_class, $url, strip_tags($text), $number, $url, $counter, $native_markup );

		return $button;

	}

	/**
	 * Returns Pinterest button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function pinterest_button($url, $text, $count = true, $more = '') {

		self::enqueue_stuff();

		$transient = get_transient( 'us_counts_'.md5('pinterest_'.urlencode($url) ) );

		$transient_class = ( is_numeric($transient) || $transient == 'zoro' ) ? ' us_transient': '';

		$number = ( is_numeric($transient) || $transient == 'zoro' ) ? sprintf(' data-count="%s"', self::number_format($transient) ): '';

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_pinterest%s%s%s us_button" data-url="%s" data-text="%s"%s><a class="us_box" href="#"><div class="us_share"><i class="us-icon-pinterest"></i></div>%s</a></div>',$more, $counter_class, $transient_class, $url, strip_tags($text), $number, $counter);

		return $button;

	}

	/**
	 * Returns Linkedin button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function linkedin_button($url, $text, $count = true, $more) {

		self::enqueue_stuff();

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_linkedin%s%s us_button" data-url="%s" data-text="%s"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-linkedin"></i></div>%s</a></div>',$more, $counter_class, $url, strip_tags($text), $counter );

		return $button;

	}

	/**
	 * Returns Stumble button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function stumble_button($url, $text, $count = true, $more = '') {

		self::enqueue_stuff();

		$transient = get_transient( 'us_counts_'.md5('stumbleupon_'.urlencode($url) ) );

		$transient_class = ( is_numeric($transient) || $transient == 'zoro' ) ? ' us_transient': '';

		$number = ( is_numeric($transient) || $transient == 'zoro' ) ? sprintf(' data-count="%s"', self::number_format($transient) ): '';

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_stumble%s%s%s us_button" data-url="%s" data-text="%s"%s><a class="us_box" href="#"><div class="us_share"><i class="us-icon-stumbleupon"></i></div>%s</a></div>', $more, $counter_class, $transient_class, $url, strip_tags($text), $number, $counter );

		return $button;

	}

	/**
	 * Returns Delicious button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function delicious_button($url, $text, $count = true, $more = '') {

		self::enqueue_stuff();

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_delicious%s%s us_button" data-url="%s" data-text="%s"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-delicious"></i></div>%s</a></div>', $more, $counter_class, $url, strip_tags($text), $counter );

		return $button;

	}

	/**
	 * Returns Buffer button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function buffer_button($url, $text, $media, $count = true, $more = '') {

		self::enqueue_stuff();

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_buffer%s%s us_button" data-url="%s" data-text="%s" data-media="%s"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-buffer"></i></div>%s</a></div>', $more, $counter_class, $url, strip_tags($text), $media, $counter );

		return $button;

	}

	/**
	 * Returns Reddit button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function reddit_button($url, $text, $count = true, $more = '') {

		self::enqueue_stuff();

		$transient = get_transient( 'us_counts_'.md5('reddit_'.urlencode($url) ) );

		$transient_class = ( is_numeric($transient) || $transient == 'zoro' ) ? ' us_transient': '';

		$number = ( is_numeric($transient) || $transient == 'zoro' ) ? sprintf(' data-count="%s"', self::number_format($transient) ): '';

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_reddit%s%s%s us_button" data-url="%s" data-text="%s"%s><a class="us_box" href="#"><div class="us_share"><i class="us-icon-reddit"></i></div>%s</a></div>', $more, $counter_class, $transient_class, $url, strip_tags($text), $number, $counter );

		return $button;

	}

	/**
	 * Returns comments button.
	 *
	 * @since	3.0.0
	 *
	 * @return	button
	 */
	public static function comments_button($url, $count = true, $more = '') {

		$post_id = url_to_postid( $url );

		if ( $post_id != 0 && comments_open() ){

			self::enqueue_stuff();

			$number = ( self::number_format( get_comments_number( $post_id ) ) ) ? self::number_format( get_comments_number( $post_id ) ): 0;

			$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

			$counter_class = ( $count ) ? '': ' us_no_count';

			$button = sprintf('<div class="us_comments%s%s us_button" data-count="%s"><a class="us_box" href="%s#respond"><div class="us_share"><i class="us-icon-comments"></i></div>%s</a></div>', $more, $counter_class, $number, $url, $counter);

			return $button;

		}

	}

	/**
	 * Returns love button.
	 *
	 * @since	3.0.0
	 *
	 * @return	button
	 */
	public static function love_button($url, $count = true, $more = '') {

		self::enqueue_stuff();

		$options = get_option( 'us_love_count' );

		$user_id = ( get_current_user_id() != "0" ) ? sprintf( " data-user_id='%s'", get_current_user_id() ): "";

		$number = ( !empty( $options['data'][$url]['count'] ) ) ? $options['data'][$url]['count']: 0;

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$id_array = ( !empty($options['data'][$url]['ids']) ) ? $options['data'][$url]['ids']: array();

		$loved_class = ( in_array( $user_id, $id_array ) ) ? ' loved': '';

		$button = sprintf('<div class="us_love%s%s us_button" data-count="%s"><a class="us_box%s" href="#"%s data-url="%s"><div class="us_share"><i class="us-icon-love"></i></div>%s</a></div>', $more, $counter_class, $number, $loved_class, $user_id, $url, $counter);

		return $button;

	}

	/**
	 * Returns vkontakte button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function vkontakte_button($url, $text, $media, $count = true, $native = false, $both = false, $more = '') {

		self::enqueue_stuff();

		$transient = get_transient( 'us_counts_'.md5('vkontakte_'.urlencode($url) ) );

		$transient_class = ( is_numeric($transient) || $transient == 'zoro' ) ? ' us_transient': '';

		$number = ( is_numeric($transient) || $transient == 'zoro' ) ? sprintf(' data-count="%s"', self::number_format($transient) ): '';

		$counter = ( $count && !$both ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count && !$both ) ? '': ' us_no_count';

		$native_class = ( $native ) ? ' us_native': '';

		$both_class = ( $both ) ? ' us_both': '';

		$native_markup = ( $native ) ? sprintf('<a class="usnative vkontakte-like vk-like" href="#" data-pageUrl="%s"></a>',$url): '';

		$button = sprintf('<div class="us_vkontakte%s%s%s%s%s us_button" data-url="%s" data-text="%s"%s><a class="us_box" href="#" data-url="%s"><div class="us_share"><i class="us-icon-vkontakte"></i></div>%s</a>%s</div>', $more, $counter_class, $native_class, $transient_class, $both_class, $url, strip_tags($text), $number, $url, $counter, $native_markup );

		return $button;

	}

	/**
	 * Returns Pocket button.
	 *
	 * @since	3.0.0
	 *
	 * @return	button
	 */
	public static function pocket_button($url, $text, $count = true, $more = '') {

		self::enqueue_stuff();

		$transient = get_transient( 'us_counts_'.md5('pocket_'.urlencode($url) ) );

		$transient_class = ( is_numeric($transient) || $transient == 'zoro' ) ? ' us_transient': '';

		$number = ( is_numeric($transient) || $transient == 'zoro' ) ? sprintf(' data-count="%s"', self::number_format($transient) ): '';

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_pocket%s%s%s us_button" data-url="%s" data-text="%s"%s><a class="us_box" href="#"><div class="us_share"><i class="us-icon-pocket"></i></div>%s</a></div>', $more, $counter_class, $transient_class, $url, strip_tags($text), $number, $counter );

		return $button;

	}

	/**
	 * Returns Odnoklassniki button.
	 *
	 * @since	4.0.0
	 *
	 * @return	button
	 */
	public static function ok_button($url, $text, $count = true, $more = '') {

		self::enqueue_stuff();

		$transient = get_transient( 'us_counts_'.md5('ok_'.urlencode($url) ) );

		$transient_class = ( is_numeric($transient) || $transient == 'zoro' ) ? ' us_transient': '';

		$number = ( is_numeric($transient) || $transient == 'zoro' ) ? sprintf(' data-count="%s"', self::number_format($transient) ): '';

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_ok%s%s%s us_button" data-url="%s" data-text="%s"%s><a class="us_box" href="#"><div class="us_share"><i class="us-icon-ok"></i></div>%s</a></div>', $more, $counter_class, $transient_class, $url, strip_tags($text), $number, $counter );

		return $button;

	}

	/**
	 * Returns Weibo button.
	 *
	 * @since	4.0.0
	 *
	 * @return	button
	 */
	public static function weibo_button($url, $text, $more = '') {

		self::enqueue_stuff();

		$button = sprintf('<div class="us_weibo%s us_no_count us_button" data-url="%s" data-text="%s"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-weibo"></i></div></a></div>', $more, $url, strip_tags($text) );

		return $button;

	}

	/**
	 * Returns ManageWP button.
	 *
	 * @since	4.0.0
	 *
	 * @return	button
	 */
	public static function managewp_button($url, $text, $count = true, $more = '') {

		self::enqueue_stuff();

		$transient = get_transient( 'us_counts_'.md5('managewp_'.urlencode($url) ) );

		$transient_class = ( is_numeric($transient) || $transient == 'zoro' ) ? ' us_transient': '';

		$number = ( is_numeric($transient) || $transient == 'zoro' ) ? sprintf(' data-count="%s"', self::number_format($transient) ): '';

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_managewp%s%s%s us_button" data-url="%s" data-text="%s"%s><a class="us_box" href="#"><div class="us_share"><i class="us-icon-managewp"></i></div>%s</a></div>', $more, $counter_class, $transient_class, $url, strip_tags($text), $number, $counter );

		return $button;

	}

	/**
	 * Returns Xing button.
	 *
	 * @since	4.0.0
	 *
	 * @return	button
	 */
	public static function xing_button($url, $text, $count = true, $more = '') {

		self::enqueue_stuff();

		$transient = get_transient( 'us_counts_'.md5('xing_'.urlencode($url) ) );

		$transient_class = ( is_numeric($transient) || $transient == 'zoro' ) ? ' us_transient': '';

		$number = ( is_numeric($transient) || $transient == 'zoro' ) ? sprintf(' data-count="%s"', self::number_format($transient) ): '';

		$counter = ( $count ) ? '<div class="us_count"><i class="us-icon-spin us-icon-spinner"></i></div>': '';

		$counter_class = ( $count ) ? '': ' us_no_count';

		$button = sprintf('<div class="us_xing%s%s us_no_count us_button" data-url="%s" data-text="%s"%s><a class="us_box" href="#"><div class="us_share"><i class="us-icon-xing"></i></div>%s</a></div>', $more, $counter_class, $transient_class, $url, strip_tags($text), $number, $counter );

		return $button;

	}

	/**
	 * Returns Tumblr button.
	 *
	 * @since	3.0.0
	 *
	 * @return	button
	 */
	public static function tumblr_button($url, $text, $more = '') {

		self::enqueue_stuff();

		$button = sprintf('<div class="us_tumblr%s us_no_count us_button" data-url="%s" data-text="%s"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-tumblr"></i></div></a></div>', $more, $url, strip_tags($text) );

		return $button;

	}

	/**
	 * Returns Meneame button.
	 *
	 * @since	4.0.0
	 *
	 * @return	button
	 */
	public static function meneame_button($url, $text, $more = '') {

		self::enqueue_stuff();

		$button = sprintf('<div class="us_meneame%s us_no_count us_button" data-url="%s" data-text="%s"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-meneame"></i></div></a></div>', $more, $url, strip_tags($text) );

		return $button;

	}

	/**
	 * Returns Digg button.
	 *
	 * @since	4.0.0
	 *
	 * @return	button
	 */
	public static function digg_button($url, $text, $more = '') {

		self::enqueue_stuff();

		$button = sprintf('<div class="us_digg%s us_no_count us_button" data-url="%s" data-text="%s"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-digg"></i></div></a></div>', $more, $url, strip_tags($text) );

		return $button;

	}

	/**
	 * Returns WhatsApp button.
	 *
	 * @since	4.0.0
	 *
	 * @return	button
	 */
	public static function whatsapp_button($url, $text, $more = '') {

		if (wp_is_mobile()) {

			self::enqueue_stuff();

			$button = sprintf('<div class="us_whatsapp%s us_no_count us_button" data-url="%s" data-text="%s"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-whatsapp"></i></div></a></div>', $more, $url, strip_tags($text) );

			return $button;
		}
	}

	/**
	 * Returns Print button.
	 *
	 * @since	3.0.0
	 *
	 * @return	button
	 */
	public static function print_button($url, $more = '') {

		self::enqueue_stuff();

		$button = sprintf('<div class="us_print%s us_no_count us_button" data-url="%s"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-print"></i></div></a></div>', $more, $url);

		return $button;

	}

	/**
	 * Returns Flipboard button.
	 *
	 * @since	3.0.0
	 *
	 * @return	button
	 */
	public static function flipboard_button($url, $text, $more = '') {

		self::enqueue_stuff();

		$button = sprintf('<div class="us_flipboard%s us_no_count us_button" data-url="%s" data-text="%s"><a class="us_box" href="#"><div class="us_share"><i class="us-icon-flipboard"></i></div></a></div>', $more, $url, strip_tags($text) );

		return $button;

	}

	/**
	 * Returns Mail button.
	 *
	 * @since	1.0.0
	 *
	 * @return	button
	 */
	public static function mail_button($url, $more = '') {

		global $us_mail_form;

		self::enqueue_stuff();

		$random_string = self::random_string(5);

		$body = self::mail_replace_vars( self::opt('us_mail_body', __('I read this article and found it very interesting, thought it might be something for you. The article is called', 'ultimate-social-deux' ) . ' ' . '{post_title} ' . ' ' . __('and is located at', 'ultimate-social-deux' ) . ' ' . ' {post_url}.'), $url, '', '' );

		$captcha_enable	= self::opt('us_mail_captcha_enable', 'yes');
		$captcha = self::opt('us_mail_captcha_question', __('What is the sum of 7 and 2?', 'ultimate-social-deux') );

		$us_share = self::opt('us_mail_header', __('Share with your friends','ultimate-social-deux') );

		$your_name = __('Your Name','ultimate-social-deux');
		$your_email = __('Your Email','ultimate-social-deux');
		$recipient_email = __('Recipient Email','ultimate-social-deux');
		$your_message = __('Enter a Message','ultimate-social-deux');
		$captcha_label = __('Captcha','ultimate-social-deux');

		$form = sprintf('<div class="us_wrapper us_modal mfp-hide" id="us-modal-%s">', $random_string);
			$form .= '<div class="us_heading">';
				$form .= $us_share;
			$form .= '</div>';
			$form .= '<div class="us_mail_response"></div>';
			$form .= '<div class="us_mail_form_holder">';
				$form .= '<form role="form" id="ajaxcontactform" class="form-group contact" action="" method="post" enctype="multipart/form-data">';
					$form .= '<div class="form-group">';
						$form .= sprintf('<label class="label" for="ajaxcontactyour_name">%s</label><br>', $your_name );
						$form .= sprintf('<input type="text" id="ajaxcontactyour_name" class="border-box form-control us_mail_your_name" name="%s" placeholder="%s"><br>', $your_name, $your_name );
						$form .= sprintf('<label class="label" for="ajaxcontactyour_email">%s</label><br>', $your_email );
						$form .= sprintf('<input type="email" id="ajaxcontactyour_email" class="border-box form-control us_mail_your_email" name="%s" placeholder="%s"><br>', $your_email, $your_email );
						$form .= sprintf('<label class="label" for="ajaxcontactrecipient_email">%s</label><br>', $recipient_email );
						$form .= sprintf('<input type="email" id="ajaxcontactrecipient_email" class="border-box form-control us_mail_recipient_email" name="%s" placeholder="%s"><br>', $recipient_email, $recipient_email);
						$form .= sprintf('<label class="label" for="ajaxcontactmessage">%s</label><br>', $your_message);
						$form .= sprintf('<textarea class="border-box form-control border-us_box us_mail_message" id="ajaxcontactmessage" name="%s" placeholder="%s">%s</textarea>', $your_message, $your_message, $body);
						$form .= sprintf('<input type="email" id="ajaxcontactrecipient_url" class="border-box form-control us_mail_url" style="display:none;" name="%s" placeholder="%s"><br>', $url, $url);
					$form .= '</div>';

					if ( $captcha_enable == 'yes' ){
						$form .= '<div class="form-group">';
							$form .= sprintf('<label class="label" for="ajaxcontactcaptcha">%s</label><br>', $captcha_label);
							$form .= sprintf('<input type="text" id="ajaxcontactcaptcha" class="border-box form-control us_mail_captcha" name="%s" placeholder="%s"><br>', $captcha_label, $captcha);
						$form .= '</div>';
					}
				$form .= '</form>';
				$form .= sprintf('<a class="btn btn-success us_mail_send">%s</a>', __('Submit','ultimate-social-deux') );
			$form .= '</div>';
		$form .= '</div>';

		$us_mail_form[$random_string] = $form;

		$button = sprintf('<div class="us_mail%s us_button us_no_count"><a class="us_box" href="#us-modal-%s"><div class="us_share"><i class="us-icon-mail"></i></div></a></div>', $more, $random_string );

		return $button;

	}

	public function us_mail_form() {
		global $us_mail_form;

		if ($us_mail_form) {

			foreach($us_mail_form as $form){
				echo $form;
			}
		}
	}

	/**
	 * Ajax function to send mail.
	 *
	 * @since	1.0.0
	 */
	public function us_send_mail(){

		$url 			= ( $_POST['url'] ) ? $_POST['url']: '';
		$your_name		= ( $_POST['your_name'] ) ? $_POST['your_name']: '';
		$your_email		= ( $_POST['your_email'] ) ? $_POST['your_email']: '';
		$recipient_email = ( $_POST['recipient_email'] ) ? $_POST['recipient_email']: '';
		$subject		= self::mail_replace_vars( self::opt('us_mail_subject', __('A visitor of', 'ultimate-social-deux' ) . ' ' . '{site_title}' . ' ' . __('shared', 'ultimate-social-deux' ) . ' ' . '{post_title}' . ' ' . __('with you.','ultimate-social-deux') ), $url, $your_name, $your_email );
		$message		= ( $_POST['message'] ) ? $_POST['message']: '';
		$captcha		= ( $_POST['captcha'] ) ? $_POST['captcha']: '';
		$captcha_answer	= self::opt('us_mail_captcha_answer', 9);
		$captcha_enable	= self::opt('us_mail_captcha_enable', 'yes');

		$admin_email	= get_bloginfo('admin_email');
		$from_email		= self::opt('us_mail_from_email', $admin_email);
		$from_name		= self::opt('us_mail_from_name', get_bloginfo('name') );
		$admin_copy		= self::opt('us_mail_bcc_enable', 'yes' );

		if ( $captcha_enable == 'yes' ){
			if( '' == $captcha )
				die( __( 'Captcha cannot be empty!', 'ultimate-social-deux' ) );
			if( $captcha !== $captcha_answer )
				die( __( 'Captcha does not match.', 'ultimate-social-deux' ) );
		}

		if ( ! filter_var( $recipient_email, FILTER_VALIDATE_EMAIL ) ) {
			die( __( 'Recipient email address is not valid.', 'ultimate-social-deux' ) );
		} elseif ( ! filter_var( $your_email, FILTER_VALIDATE_EMAIL ) ) {
			die( __( 'Your email address is not valid.', 'ultimate-social-deux' ) );
		} elseif( strlen( $your_name ) == 0 ) {
			die( __( 'Your name cannot be empty.', 'ultimate-social-deux' ) );
		} elseif( strlen( $message ) == 0 ) {
			die( __( 'Message cannot be empty.', 'ultimate-social-deux' ) );
		}
		$headers	= array();
		$headers[] = sprintf('From: %s <%s>', $from_name, $from_email );
		$headers[] = sprintf('Reply-To: %s <%s>', $your_name, $your_email );
		if ($admin_copy == 'yes') {
			$headers[] = sprintf('Bcc: %s', $admin_email);
		}

		if( true === ( $result = wp_mail( $recipient_email, stripslashes($subject), stripslashes($message), implode("\r\n", $headers) ) ) )
			die( 'ok' );

		if ( ! $result ) {

			global $phpmailer;

			if( isset( $phpmailer->ErrorInfo ) ) {
				die( sprintf( 'Error: %s', $phpmailer->ErrorInfo ) );
			} else {
				die( __( 'Unknown wp_mail() error.', 'ultimate-social-deux' ) );
			}
		}
	}

	/**
	 * Ajax function for love button
	 *
	 * @since	3.0.0
	 */
	public function us_love_button_ajax() {

		$url = ( $_POST['url'] ) ? $_POST['url']: '';
		$urlencode = urlencode($url);
		$user_id = ( $_POST['user_id'] ) ? $_POST['user_id']: '';
		$options = get_option( 'us_love_count' );

		$id_array = ( !empty($options['data'][$url]['ids']) ) ? $options['data'][$url]['ids']: array();

		if ( $url && wp_verify_nonce( $_POST['nonce'], 'us_nonce' ) ) {
			if (!in_array( $user_id, $id_array ) ) {

				if ( !empty( $options['data'][$url]['count'] ) ) {
					$options['data'][$url]['count'] = $options['data'][$url]['count'] + 1;
				} else {
					$options['data'][$url]['count'] = 1;
				}

				if( is_user_logged_in() ) {
					$options['data'][$url]['ids'][$user_id] = $user_id;
				}

				update_option( 'us_love_count', $options );

				die('ok');
			} else {
				die();
			}
		}
	}

	/**
	 * Ajax function for bit.ly shortener
	 *
	 * @since	3.0.0
	 */
	public function us_bitly_shortener() {

		header('content-type: application/json');

		$url = ( $_POST['url'] ) ? $_POST['url']: '';

		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');

		$url = strtr(rawurlencode($url), $revert);

		$access_token = self::opt('us_bitly_access_token', '');

		if ($access_token) {
			$content = @file_get_contents('https://api-ssl.bitly.com/v3/shorten?access_token='.$access_token.'&longUrl='.$url);

			if ($content === FALSE) {
				$content = @self::parse('https://api-ssl.bitly.com/v3/shorten?access_token='.$access_token.'&longUrl='.$url);
			}
		}

		die($content);

	}

	/**
	 * Ajax function for getting counts
	 *
	 * @since	1.0.0
	 */
	public function us_counts() {

		header('content-type: application/json');

		$json = array('url'=>'','count'=>0);
		$json['url'] = $_GET['url'];
		$url = urlencode($_GET['url']);
		$url_transient = urlencode($_GET['url']);
		$type = $_GET['type'];

		$expire = intval(self::opt('us_counts_transient', '600'));

		if(filter_var($_GET['url'], FILTER_VALIDATE_URL)){
			switch($type) {
				case 'googlePlus':

					$transient = get_transient( 'us_counts_'.md5('googlePlus_'.$url_transient) );

					if ( is_numeric($transient) || $transient == 'zoro' ) {

						$transient_value = ( $transient == 'zoro' ) ? 0: $transient;

						$json['count'] = $transient_value;
					} else {
						$content = @file_get_contents("https://plusone.google.com/u/0/_/+1/fastbutton?url=".$url."&count=true");

						if ($content === FALSE) {
							$content = @self::parse("https://plusone.google.com/u/0/_/+1/fastbutton?url=".$url."&count=true");
						}

					  	if (preg_match("/window\.__SSR\s=\s\{c:\s([0-9]+)\.0/", $content, $matches)) {
							$json['count'] = $matches[1];
						}

						$transient_value = ( $json['count'] ) ? $json['count']: 'zoro';

						set_transient( 'us_counts_'.md5('googlePlus_'.$url_transient), $transient_value, $expire );
					}
					break;

				case 'stumbleupon':
					$transient = get_transient( 'us_counts_'.md5('stumbleupon_'.$url_transient) );

					if ( is_numeric($transient) || $transient == 'zoro' ) {

						$transient_value = ( $transient == 'zoro' ) ? 0: $transient;

						$json['count'] = $transient_value;
					}  else {
						$content = @file_get_contents("https://www.stumbleupon.com/services/1.01/badge.getinfo?url=".$url);

						if ($content === FALSE) {
							$content = @self::parse("https://www.stumbleupon.com/services/1.01/badge.getinfo?url=".$url);
						}

						$result = json_decode($content);
						if (isset($result->result->views)) {
							$json['count'] = $result->result->views;
						}

						$transient_value = ( $json['count'] ) ? $json['count']: 'zoro';

						set_transient( 'us_counts_'.md5('stumbleupon_'.$url_transient), $transient_value, $expire );
					}

					break;

				case 'pinterest':
					$transient = get_transient( 'us_counts_'.md5('pinterest_'.$url_transient) );

					if ( is_numeric($transient) || $transient == 'zoro' ) {

						$transient_value = ( $transient == 'zoro' ) ? 0: $transient;

						$json['count'] = $transient_value;
					} else {
						$content = @file_get_contents("https://api.pinterest.com/v1/urls/count.json?url=".$url);

						if ($content === FALSE) {
							$content = @self::parse("https://api.pinterest.com/v1/urls/count.json?url=".$url);
						}

						$content = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $content);

						$result = json_decode($content);

						$json['count'] = intval($result->count);

						$transient_value = ( $json['count'] ) ? $json['count']: 'zoro';

						set_transient( 'us_counts_'.md5('pinterest_'.$url_transient), $transient_value, $expire );
					}
					break;

				case 'reddit':
					$transient = get_transient( 'us_counts_'.md5('reddit_'.$url_transient) );

					if ( is_numeric($transient) || $transient == 'zoro' ) {

						$transient_value = ( $transient == 'zoro' ) ? 0: $transient;

						$json['count'] = $transient_value;
					}  else {
						$content = @file_get_contents("http://www.reddit.com/api/info.json?url=".$url);

						if ($content === FALSE) {
							$content = @self::parse("http://www.reddit.com/api/info.json?url=".$url);
						}

						$result = json_decode($content);

						$children = $result->data->children;

						if ( isset($children[0]->data->score) && is_int($children[0]->data->score)) {

							$sum = 0;
							foreach($children as $child) {

								$sum+= $child->data->score;
							}

							$json['count'] = $sum;

						}

						$transient_value = ( $json['count'] ) ? $json['count']: 'zoro';

						set_transient( 'us_counts_'.md5('reddit_'.$url_transient), $transient_value, $expire );
					}
					break;

				case 'vkontakte':
					$transient = get_transient( 'us_counts_'.md5('vkontakte_'.$url_transient) );

					if ( is_numeric($transient) || $transient == 'zoro' ) {

						$transient_value = ( $transient == 'zoro' ) ? 0: $transient;

						$json['count'] = $transient_value;
					}  else {

						$content = @file_get_contents("https://vk.com/share.php?act=count&index=0&url=".$url);

						if ($content === FALSE) {
							$content = @self::parse("https://vk.com/share.php?act=count&index=0&url=".$url);
						}

						$content = substr($content, 18, -2);

						$content = intval($content);

						if (is_int($content)) {
							$json['count'] = $content;
						}

						$transient_value = ( $json['count'] ) ? $json['count']: 'zoro';

						set_transient( 'us_counts_'.md5('vkontakte_'.$url_transient), $transient_value, $expire );
					}
					break;

				case 'pocket':
					$transient = get_transient( 'us_counts_'.md5('pocket_'.$url_transient) );

					if ( is_numeric($transient) || $transient == 'zoro' ) {

						$transient_value = ( $transient == 'zoro' ) ? 0: $transient;

						$json['count'] = $transient_value;
					}  else {

						$content = @file_get_contents("https://widgets.getpocket.com/v1/button?align=center&count=vertical&label=pocket&url=".$url);

						if ($content === FALSE) {
							$content = @self::parse("https://widgets.getpocket.com/v1/button?align=center&count=vertical&label=pocket&url=".$url);
						}

						$shares = array();

						preg_match( '/<em id="cnt">(.*?)<\/em>/s', $content, $shares );

						if (count($shares) > 0) {
							$result = $shares[1];

							$content = intval($result);
						}

						if (is_int($content)) {
							$json['count'] = $content;
						}

						$transient_value = ( $json['count'] ) ? $json['count']: 'zoro';

						set_transient( 'us_counts_'.md5('pocket_'.$url_transient), $transient_value, $expire );
					}
					break;

				case 'ok':
					$transient = get_transient( 'us_counts_'.md5('ok_'.$url_transient) );

					if ( is_numeric($transient) || $transient == 'zoro' ) {

						$transient_value = ( $transient == 'zoro' ) ? 0: $transient;

						$json['count'] = $transient_value;
					}  else {

						$content = @file_get_contents("http://www.odnoklassniki.ru/dk?st.cmd=extLike&uid=odklcnt0&ref=".$url);

						if ($content === FALSE) {
							$content = @self::parse("http://www.odnoklassniki.ru/dk?st.cmd=extLike&uid=odklcnt0&ref=".$url);
						}

						$content = substr($content, 29, -3);

						$content = intval($content);

						if (is_int($content)) {
							$json['count'] = $content;
						}

						$transient_value = ( $json['count'] ) ? $json['count']: 'zoro';

						set_transient( 'us_counts_'.md5('ok_'.$url_transient), $transient_value, $expire );
					}
					break;

				case 'xing':
					$transient = get_transient( 'us_counts_'.md5('xing_'.$url_transient) );

					if ( is_numeric($transient) || $transient == 'zoro' ) {

						$transient_value = ( $transient == 'zoro' ) ? 0: $transient;

						$json['count'] = $transient_value;
					}  else {

						$content = @file_get_contents('https://www.xing-share.com/app/share?op=get_share_button;url='.$url.';counter=top;lang=en;type=iframe;hovercard_position=2;shape=rectangle');

						if ($content === FALSE) {
							$content = @self::parse('https://www.xing-share.com/app/share?op=get_share_button;url='.$url.';counter=top;lang=en;type=iframe;hovercard_position=2;shape=rectangle');
						}
						$shares = array();

						preg_match( '/<span class="xing-count top">(.*?)<\/span>/s', $content, $shares );

						if (count($shares) > 0) {
							$result = $shares[1];

							$content = $result;
						}

						$content = intval($content);

						if (is_int($content)) {
							$json['count'] = $content;
						}

						$transient_value = ( $json['count'] ) ? $json['count']: 'zoro';

						set_transient( 'us_counts_'.md5('xing_'.$url_transient), $transient_value, $expire );
					}
					break;

				case 'managewp':
					$transient = get_transient( 'us_counts_'.md5('managewp_'.$url_transient) );

					if ( is_numeric($transient) || $transient == 'zoro' ) {

						$transient_value = ( $transient == 'zoro' ) ? 0: $transient;

						$json['count'] = $transient_value;
					}  else {

						$content = @file_get_contents('https://managewp.org/share/frame/small?url='.$url);

						if ($content === FALSE) {
							$content = @self::parse('https://managewp.org/share/frame/small?url='.$url);
						}

						$content = preg_match( '/<form(.*?)<\/form>/s', $content, $shares );

						if (count($shares) > 0) {
							$current_result = $shares[1];

							$second_parse = array();
							preg_match( '/<div>(.*?)<\/div>/s', $current_result, $second_parse );

							$value = $second_parse[1];
							$value = str_replace("<span>", "", $value);
							$value = str_replace("</span>", "", $value);

							$content = $value;
						}

						$content = intval($content);

						if (is_int($content)) {
							$json['count'] = $content;
						}

						$transient_value = ( $json['count'] ) ? $json['count']: 'zoro';

						set_transient( 'us_counts_'.md5('managewp_'.$url_transient), $transient_value, $expire );
					}
					break;

				case 'comments':
					$post_id = url_to_postid( $_GET['url'] );

					$json['count'] = ( get_comments_number( $post_id ) ) ? get_comments_number( $post_id ): 0;

					break;

				case 'love':
					$options = get_option( 'us_love_count' );

					$json['count'] = ( !empty( $options['data'][$_GET['url']]['count'] ) ) ? $options['data'][$_GET['url']]['count']: 0;

					break;

				default:
					$json['count'] = 0;
					break;
			}

		}
		echo str_replace('\\/','/',json_encode($json));

		die();

	}

	public function parse($encUrl){

		$options = array(
			CURLOPT_RETURNTRANSFER => true, // return web page
			CURLOPT_HEADER => false, // don't return headers
			CURLOPT_FOLLOWLOCATION => true, // follow redirects
			CURLOPT_ENCODING => "", // handle all encodings
			CURLOPT_USERAGENT => 'sharrre', // who am i
			CURLOPT_AUTOREFERER => true, // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 5, // timeout on connect
			CURLOPT_TIMEOUT => 10, // timeout on response
			CURLOPT_MAXREDIRS => 3, // stop after 10 redirects
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => false,
		);
		$ch = curl_init();

		$options[CURLOPT_URL] = $encUrl;
		curl_setopt_array($ch, $options);

		$content = curl_exec($ch);
		$err = curl_errno($ch);
		$errmsg = curl_error($ch);

		curl_close($ch);

		return $content;

	}

	public static function remote_get( $url , $json = true) {
		$request = wp_remote_retrieve_body( wp_remote_get( $url , array( 'timeout' => 18 , 'sslverify' => false ) ) );
		if( $json ) $request = @json_decode( $request , true );
		return $request;
	}

	/**
	 * Returns random string.
	 *
	 * @since	1.0.0
	 *
	 * @return	random string
	 */
	public static function random_string($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}

	public function open_graph_tags() {
		global $post;

		$option = self::opt('us_open_graph', 'off');
		$fb_app_id = self::opt('us_facebook_appid', '');

		if ( $option === 'on' )  {

			if ( is_singular() ) {
				$title = get_the_title();
				$url = get_permalink();
			} elseif ( in_the_loop() ) {
				$title = get_the_title();
				$url = get_permalink();
			} elseif ( is_home() ) {
				$title = get_bloginfo('name');
				$url = get_bloginfo('url');
			} elseif ( is_archive() ) {
				global $wp_query;
				$term = $wp_query->get_queried_object();
				$title = $term->name;
				$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			} else {
				$title = get_bloginfo('name');
				$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			}

			$image = self::catch_first_image($url);
			?>
				<meta property="og:title" content="<?php echo $title; ?>" />
				<meta property="og:type" content="article" />
				<meta property="og:image" content="<?php echo $image; ?>" />
				<meta property="og:url" content="<?php echo $url; ?>" />
				<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>" />
			<?php

			if ($fb_app_id) {
				printf('<meta property="fb:app_id" content="%s" />', $fb_app_id);
			}
		}
	}

	/**
	 * Return readable count
	 *
	 * @since 	 1.0.0
	 *
	 * @return 	 readable number
	 */
	public static function number_format($n) {
		$n = intval($n);
		if($n>1000000000) return round(($n/1000000000),1).'B';
		elseif($n>1000000) return round(($n/1000000),1).'M';
		elseif($n>1000) return round(($n/1000),1).'k';

		return number_format(intval($n));

	}

	public static function skins_array() {

		$array = array(
			'default' => __( 'Default', 'ultimate-social-deux' ),
			'simple' => __( 'Simple', 'ultimate-social-deux' ),
			'minimal' => __( 'Minimal', 'ultimate-social-deux' ),
			'modern' => __( 'Modern', 'ultimate-social-deux' ),
			'round' => __( 'Round', 'ultimate-social-deux' )
		);

		return $array;
	}

}
