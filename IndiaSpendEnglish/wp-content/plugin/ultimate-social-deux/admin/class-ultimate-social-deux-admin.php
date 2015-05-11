<?php
/**
 * Ultimate Social Deux.
 *
 * @package		Ultimate Social Deux
 * @author		Ultimate Wordpress <hello@ultimate-wp.com>
 * @link		http://social.ultimate-wp.com
 * @copyright 	2013 Ultimate Wordpress
 */

class UltimateSocialDeuxAdmin {

	private $settings_api;

	/**
	 * Instance of this class.
	 *
	 * @since	1.0.0
	 *
	 * @var		object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since	1.0.0
	 *
	 * @var		string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since	 1.0.0
	 */
	private function __construct() {

		$plugin = UltimateSocialDeux::get_instance();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'tf_admin_page_before', array( $this, 'header' ) );

		add_action( 'tf_create_options', array( $this, 'options' ) );

	}

	/**
	 * Enqueue admin CSS.
	 *
	 * @since	 1.0.0
	 *
	 */
	public function admin_enqueue_scripts() {

		global $pagenow;
		wp_register_style( 'ultimate-social-deux-admin-styles', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', false, '1.0.0' );

		if ( is_admin() && $pagenow == 'admin.php' && ( substr($_GET['page'], 0, 15)=="ultimate-social")){
        	wp_enqueue_style( 'ultimate-social-deux-admin-styles' );
        	delete_option('us_basic');
        	delete_option('us_styling');
        	delete_option('us_mail');
        	delete_option('us_fan_count');
        	delete_option('us_license');
        	delete_option('us_advanced');
	    }

	}

	public function options() {

		$titan = TitanFramework::getInstance( 'ultimate-social-deux' );

		// Create an admin page with a menu
		$panel = $titan->createAdminPanel( array(
		    'name' => 'Ultimate Social',
		    'icon' => plugin_dir_url( __FILE__ ) . 'assets/img/icon.png',
		) );
		$panelPlacement = $panel->createAdminPanel( array(
		    'name' => __( 'Placement', 'ultimate-social-deux' ),
		) );
		$panelStyling = $panel->createAdminPanel( array(
		    'name' => __( 'Styling', 'ultimate-social-deux' ),
		) );
		$panelMail = $panel->createAdminPanel( array(
		    'name' => __( 'Mail', 'ultimate-social-deux' ),
		) );
		$panelFanCount = $panel->createAdminPanel( array(
		    'name' => __( 'Fan Count', 'ultimate-social-deux' ),
		) );
		$panelLicense = $panel->createAdminPanel( array(
		    'name' => __( 'License', 'ultimate-social-deux' ),
		) );

		/**
		 * Basic settings.
		 */
		$basicTab = $panel->createTab( array(
		    'name' => __( 'Basic', 'ultimate-social-deux' ),
		) );

		$basicTab->createOption( array(
		    'name' => __( 'Basic', 'ultimate-social-deux' ).' '.__( 'Settings', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$basicTab->createOption( array(
			'id' => 'us_tweet_via',
			'name' => __( 'Tweet via: @', 'ultimate-social-deux' ),
			'desc' => __( 'Write your Twitter username here to be mentioned in visitors tweets', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_tweet_via', 'us_basic', ''),
		) );
		$basicTab->createOption( array(
			'id' => 'us_vkontakte_appid',
			'name' => __( 'VKontakte', 'ultimate-social-deux' ) . ' ' . __( 'App ID', 'ultimate-social-deux' ),
			'desc' => __( 'You need to register your site at vk.com to use the native vk.com button. Register your app abd obtain your ID', 'ultimate-social-deux' ) . ' ' . '<a href="http://vk.com/dev/Like" target="_blank">' . __( 'here', 'ultimate-social-deux' ) . '</a>.',
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_vkontakte_appid', 'us_basic', ''),
		) );
		$basicTab->createOption( array(
			'id' => 'us_facebook_appid',
			'name' => __( 'Facebook', 'ultimate-social-deux' ) . ' ' . __( 'App ID', 'ultimate-social-deux' ),
			'desc' => __( 'This is used for insights of how people are sharing your content.', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_facebook_appid', 'us_basic', ''),
		) );
		$basicTab->createOption( array(
			'id' => 'us_total_shares_text',
			'name' => __( 'Total Shares Button Text', 'ultimate-social-deux' ),
			'desc' => __( 'The text you want for the total shares button', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_total_shares_text', 'us_basic', 'Shares'),
		) );
		$basicTab->createOption( array(
			'id' => 'us_open_graph',
			'name' => __( 'Add Open Graph tags', 'ultimate-social-deux' ),
			'desc' => __( 'This is used for some social networks to fetch data from your site', 'ultimate-social-deux' ),
			'type' => 'checkbox',
			'default' => UltimateSocialDeux::opt_old('us_open_graph', 'us_basic', ''),
		) );
		$basicTab->createOption( array(
			'id' => 'us_bitly_access_token',
			'name' => __( 'Bit.ly access token', 'ultimate-social-deux' ),
			'desc' => __( 'Bit.ly link shortener is available for Twitter and Buffer URLs. Get your access token by going to:', 'ultimate-social-deux' ) . ' ' . 'https://bitly.com/a/oauth_apps',
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_bitly_access_token', 'us_basic', ''),
		) );
		$basicTab->createOption( array(
			'id' => 'us_counts_transient',
			'name' => __( 'Caching for share buttons', 'ultimate-social-deux' ),
			'desc' => __( "in seconds. We are caching some of the results from the API's that are not returning a valid JSONP output.", 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_counts_transient', 'us_basic', '600'),
		) );
		$basicTab->createOption( array(
			'id' => 'us_enqueue',
			'name' => __( 'Style and script enqueuing', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_enqueue', 'us_basic', 'all_pages'),
			'desc' => __( "Some themes need to have the styles and scripts loaded on 'All Pages', some can load the styles and scripts on 'Individual Pages' where the buttons are visual.", 'ultimate-social-deux' ),
			'options' => array(
				'all_pages' => __( 'All Pages', 'ultimate-social-deux' ),
				'individual' => __( 'Individual Pages', 'ultimate-social-deux' ),
				'manually' => __( 'Manually - no styles or scripts will load', 'ultimate-social-deux' )
			)
		) );

		$basicTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		$stickyTab = $panel->createTab( array(
		    'name' => __( 'Sticky', 'ultimate-social-deux' ),
		) );

		$stickyTab->createOption( array(
			'id' => 'us_sticky_get_width_from',
			'name' => __( 'Sticky get width from this element', 'ultimate-social-deux' ),
			'desc' => __( 'If using the sticky option from Placements Settings, you can set the width of the sticky container to match the parent element. Default: ".entry-content"', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => '.entry-content',
		) );
		$stickyTab->createOption( array(
			'id' => 'us_sticky_offset',
			'name' => __( 'Sticky offset from top of page', 'ultimate-social-deux' ),
			'desc' => __( 'Some sites have a fixed navbar. Preventing our sticky sharebar from showing. You can offset with this option. In px.', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => '0',
		) );
		$stickyTab->createOption( array(
			'id' => 'us_sticky_background_color',
			'name' => __( 'Background color for sticky container', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => '#ffffff',
		) );
		$stickyTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Styling settings.
		 */
		$colorTab = $panelStyling->createTab( array(
		    'name' => __( 'Color control', 'ultimate-social-deux' ),
		) );
		$miscTab = $panelStyling->createTab( array(
		    'name' => __( 'Misc', 'ultimate-social-deux' ),
		) );
		$customCssTab = $panelStyling->createTab( array(
		    'name' => __( 'Custom CSS', 'ultimate-social-deux' ),
		) );
		$colorTab->createOption( array(
		    'name' => __( 'Color control', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$colorTab->createOption( array(
			'id' => 'us_hover_color',
			'name' => __( 'Hover', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'When hovering over a button the button changes to this color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_hover_color', 'us_styling', '#008000'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_facebook_color',
			'name' => __( 'Facebook', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Facebook', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_facebook_color', 'us_styling', '#3b5998'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_twitter_color',
			'name' => __( 'Twitter', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Twitter', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_twitter_color', 'us_styling', '#00ABF0'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_googleplus_color',
			'name' => __( 'Google Plus', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Google Plus', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_googleplus_color', 'us_styling', '#D95232'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_pinterest_color',
			'name' => __( 'Pinterest', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Pinterest', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_pinterest_color', 'us_styling', '#AE181F'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_linkedin_color',
			'name' => __( 'LinkedIn', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'LinkedIn', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_linkedin_color', 'us_styling', '#1C86BC'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_delicious_color',
			'name' => __( 'Delicious', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Delicious', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_delicious_color', 'us_styling', '#66B2FD'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_stumble_color',
			'name' => __( 'StumbleUpon', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'StumbleUpon', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_stumble_color', 'us_styling', '#E94B24'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_buffer_color',
			'name' => __( 'Buffer', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Buffer', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_buffer_color', 'us_styling', '#000000'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_reddit_color',
			'name' => __( 'Reddit', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Reddit', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_reddit_color', 'us_styling', '#CEE3F8'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_vkontakte_color',
			'name' => __( 'VKontakte', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'VKontakte', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_vkontakte_color', 'us_styling', '#537599'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_mail_color',
			'name' => __( 'Mail', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Mail', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_mail_color', 'us_styling', '#666666'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_love_color',
			'name' => __( 'Love', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Love', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_love_color', 'us_styling', '#FF0000'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_pocket_color',
			'name' => __( 'Pocket', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Pocket', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_pocket_color', 'us_styling', '#ee4056'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_print_color',
			'name' => __( 'Printfriendlyfriendly', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Printfriendlyfriendly', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_print_color', 'us_styling', '#60d0d4'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_flipboard_color',
			'name' => __( 'Flipboard', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Flipboard', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_flipboard_color', 'us_styling', '#c10000'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_ok_color',
			'name' => __( 'Odnoklassniki', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Odnoklassniki', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_ok_color', 'us_styling', '#f2720c'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_weibo_color',
			'name' => __( 'Weibo', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Weibo', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_weibo_color', 'us_styling', '#e64141'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_managewp_color',
			'name' => __( 'ManageWP', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'ManageWP', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_managewp_color', 'us_styling', '#098ae0'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_xing_color',
			'name' => __( 'Xing', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Xing', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_xing_color', 'us_styling', '#026466'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_whatsapp_color',
			'name' => __( 'WhatsApp', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'WhatsApp', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_whatsapp_color', 'us_styling', '#34af23'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_meneame_color',
			'name' => __( 'Meneame', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Meneame', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_meneame_color', 'us_styling', '#ff6400'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_digg_color',
			'name' => __( 'Digg', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Digg', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_digg_color', 'us_styling', '#000000'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_comments_color',
			'name' => __( 'Comments', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Comments', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_comments_color', 'us_styling', '#b69823'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_more_color',
			'name' => __( 'More', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'More', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => '#53B27C',
		) );
		$colorTab->createOption( array(
			'id' => 'us_tumblr_color',
			'name' => __( 'Tumblr', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Tumblr', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_tumblr_color', 'us_styling', '#529ecc'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_feedly_color',
			'name' => __( 'Feedly', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Feedly', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_feedly_color', 'us_styling', '#414141'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_youtube_color',
			'name' => __( 'Youtube', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Youtube', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_youtube_color', 'us_styling', '#cc181e'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_vimeo_color',
			'name' => __( 'Vimeo', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Vimeo', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_vimeo_color', 'us_styling', '#1bb6ec'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_dribbble_color',
			'name' => __( 'Dribbble', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Dribbble', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_dribbble_color', 'us_styling', '#f72b7f'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_envato_color',
			'name' => __( 'Envato', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Envato', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_envato_color', 'us_styling', '#82b540'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_github_color',
			'name' => __( 'Github', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Github', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_github_color', 'us_styling', '#201e1f'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_soundcloud_color',
			'name' => __( 'SoundCloud', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'SoundCloud', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_soundcloud_color', 'us_styling', '#ff6f00'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_behance_color',
			'name' => __( 'Behance', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Behance', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_behance_color', 'us_styling', '#1769ff'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_instagram_color',
			'name' => __( 'Instagram', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Instagram', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_instagram_color', 'us_styling', '#48769c'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_feedpress_color',
			'name' => __( 'Feedpress', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Feedpress', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_feedpress_color', 'us_styling', '#ffafaf'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_mailchimp_color',
			'name' => __( 'Mailchimp', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Mailchimp', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_mailchimp_color', 'us_styling', '#6dc5dc'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_flickr_color',
			'name' => __( 'Flickr', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Flickr', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_flickr_color', 'us_styling', '#0062dd'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_members_color',
			'name' => __( 'Members', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Members', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_members_color', 'us_styling', '#0ab071'),
		) );
		$colorTab->createOption( array(
			'id' => 'us_posts_color',
			'name' => __( 'Posts', 'ultimate-social-deux' ) . ' ' . __( 'Color', 'ultimate-social-deux' ),
			'desc' => __( 'Posts', 'ultimate-social-deux' ) . ' ' . __( 'button color', 'ultimate-social-deux' ),
			'type' => 'color',
			'default' => UltimateSocialDeux::opt_old('us_posts_color', 'us_styling', '#924e2a'),
		) );
		$colorTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );
		$miscTab->createOption( array(
		    'name' => __( 'Border radius - sharing buttons', 'ultimate-social-deux' ) . ' - ' . __( 'only works for the Default skin', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$miscTab->createOption( array(
			'id' => 'us_border_radius_sharing_top_left',
			'name' => __( 'Top', 'ultimate-social-deux' ) . ' ' . __( 'left', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => self::fifteenpx_array(),
			'default' => UltimateSocialDeux::opt_old('us_border_radius_sharing_top_left', 'us_styling', '0'),
		) );
		$miscTab->createOption( array(
			'id' => 'us_border_radius_sharing_top_right',
			'name' => __( 'Top', 'ultimate-social-deux' ) . ' ' . __( 'right', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => self::fifteenpx_array(),
			'default' => UltimateSocialDeux::opt_old('us_border_radius_sharing_top_right', 'us_styling', '0'),
		) );
		$miscTab->createOption( array(
			'id' => 'us_border_radius_sharing_bottom_left',
			'name' => __( 'Bottom', 'ultimate-social-deux' ) . ' ' . __( 'left', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => self::fifteenpx_array(),
			'default' => UltimateSocialDeux::opt_old('us_border_radius_sharing_bottom_left', 'us_styling', '0'),
		) );
		$miscTab->createOption( array(
			'id' => 'us_border_radius_sharing_bottom_right',
			'name' => __( 'Bottom', 'ultimate-social-deux' ) . ' ' . __( 'right', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => self::fifteenpx_array(),
			'default' => UltimateSocialDeux::opt_old('us_border_radius_sharing_bottom_right', 'us_styling', '0'),
		) );
		$miscTab->createOption( array(
		    'name' => __( 'Border radius - Fan Count', 'ultimate-social-deux' ) . ' - ' . __( 'only works for the Default skin', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$miscTab->createOption( array(
			'id' => 'us_border_radius_fan_count_top_left',
			'name' => __( 'Top', 'ultimate-social-deux' ) . ' ' . __( 'left', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => self::sixtypx_array(),
			'default' => UltimateSocialDeux::opt_old('us_border_radius_fan_count_top_left', 'us_styling', '0'),
		) );
		$miscTab->createOption( array(
			'id' => 'us_border_radius_fan_count_top_right',
			'name' => __( 'Top', 'ultimate-social-deux' ) . ' ' . __( 'right', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => self::sixtypx_array(),
		 	'default' => UltimateSocialDeux::opt_old('us_border_radius_fan_count_top_right', 'us_styling', '0'),
		) );
		$miscTab->createOption( array(
			'id' => 'us_border_radius_fan_count_bottom_left',
			'name' => __( 'Bottom', 'ultimate-social-deux' ) . ' ' . __( 'left', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => self::sixtypx_array(),
			'default' => UltimateSocialDeux::opt_old('us_border_radius_fan_count_bottom_left', 'us_styling', '0'),
		) );
		$miscTab->createOption( array(
			'id' => 'us_border_radius_fan_count_bottom_right',
			'name' => __( 'Bottom', 'ultimate-social-deux' ) . ' ' . __( 'right', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => self::sixtypx_array(),
			'default' => UltimateSocialDeux::opt_old('us_border_radius_fan_count_bottom_right', 'us_styling', '0'),
		) );
		$miscTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		$customCssTab->createOption( array(
		    'name' => __( 'Custom CSS', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$customCssTab->createOption( array(
			'id' => 'us_custom_css',
			'name' => __( 'Custom CSS field', 'ultimate-social-deux' ),
			'type' => 'code',
			'default' => UltimateSocialDeux::opt_old('us_custom_css', 'us_styling'),
		) );
		$customCssTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );


		/**
		 * Mail settings.
		 */
		$panelMail->createOption( array(
		    'name' => __( 'Mail', 'ultimate-social-deux' ) . ' ' . __( 'Settings', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );

		$panelMail->createOption( array(
			'id' => 'us_mail_header',
			'name' => __( 'Popup header:', 'ultimate-social-deux' ),
			'desc' => __( 'The heading of the mail popup', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_mail_header', 'us_mail', __('Share with your friends','ultimate-social-deux')),
		) );
		$panelMail->createOption( array(
			'id' => 'us_mail_from_email',
			'name' => __( 'Mail From:', 'ultimate-social-deux' ),
			'desc' => __( 'Email address that mail form will email from', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_mail_from_email', 'us_mail', get_bloginfo('admin_email')),
		) );
		$panelMail->createOption( array(
			'id' => 'us_mail_from_name',
			'name' => __( 'Mail From Name:', 'ultimate-social-deux' ),
			'desc' => __( 'Name that mail form will email with', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_mail_from_name', 'us_mail', get_bloginfo('name')),
		) );
		$panelMail->createOption( array(
			'id' => 'us_mail_subject',
			'name' => __( 'Mail Subject:', 'ultimate-social-deux' ),
			'desc' => __( 'Subject of email.', 'ultimate-social-deux' ) . '<br>' . __('Available tags is:', 'ultimate-social-deux' ) . '<br>{post_title} -> ' . __('Outputs title of the post or page', 'ultimate-social-deux' ) . '<br>{post_url} -> ' . __('Outputs url of the post or page', 'ultimate-social-deux' ) . '<br>{post_author} -> ' . __('Outputs the author of the post or page', 'ultimate-social-deux' ) . '<br>{site_title} -> ' . __('Outputs the title of the Wordpress Install', 'ultimate-social-deux' ) . '<br>{site_url} -> ' . __('Outputs the url of the Wordpress Install', 'ultimate-social-deux' ) . '<br>{sender_name} -> ' . __('Outputs the senders name', 'ultimate-social-deux' ) . '<br>{sender_email} -> ' . __('Outputs the senders email', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_mail_subject', 'us_mail', __('A visitor of', 'ultimate-social-deux' ) . ' ' . '{site_title}' . ' ' . __('shared', 'ultimate-social-deux' ) . ' ' . '{post_title}' . ' ' . __('with you.','ultimate-social-deux')),
		) );
		$panelMail->createOption( array(
			'id' => 'us_mail_body',
			'name' => __( 'Mail Message:', 'ultimate-social-deux' ),
			'desc' => __( 'Body of email.', 'ultimate-social-deux' ). ' ' . __('Available tags is:', 'ultimate-social-deux' ) . '<br>{post_title} -> ' . __('Outputs title of the post or page', 'ultimate-social-deux' ) . '<br>{post_url} -> ' . __('Outputs url of the post or page', 'ultimate-social-deux' ) . '<br>{post_author} -> ' . __('Outputs the author of the post or page', 'ultimate-social-deux' ) . '<br>{site_title} -> ' . __('Outputs the title of the Wordpress Install', 'ultimate-social-deux' ) . '<br>{site_url} -> ' . __('Outputs the url of the Wordpress Install', 'ultimate-social-deux' ),
			'type' => 'textarea',
			'default' => UltimateSocialDeux::opt_old('us_mail_body', 'us_mail', __('I read this article and found it very interesting, thought it might be something for you. The article is called', 'ultimate-social-deux' ) . ' ' . '{post_title}' . ' ' . __('and is located at', 'ultimate-social-deux' ) . ' ' . '{post_url}.'),
		) );
		$panelMail->createOption( array(
			'id' => 'us_mail_bcc_enable',
			'name' => __( 'Send copy to admin?', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_mail_bcc_enable', 'us_mail', 'no'),
			'options' => array(
				'yes' => __( 'Yes', 'ultimate-social-deux' ),
				'no' => __( 'No', 'ultimate-social-deux' )
			)
		) );
		$panelMail->createOption( array(
			'id' => 'us_mail_captcha_enable',
			'name' => __( 'Enable Captcha?', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_mail_captcha_enable', 'us_mail', 'yes'),
			'options' => array(
				'yes' => __( 'Yes', 'ultimate-social-deux' ),
				'no' => __( 'No', 'ultimate-social-deux' )
			)
		) );
		$panelMail->createOption( array(
			'id' => 'us_mail_captcha_question',
			'name' => __( 'Captcha Question', 'ultimate-social-deux' ),
			'desc' => __( 'Your captcha question', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_mail_captcha_question', 'us_mail', __( 'What is the sum of 7 and 2?', 'ultimate-social-deux' )),
		) );
		$panelMail->createOption( array(
			'id' => 'us_mail_captcha_answer',
			'name' => __( 'Captcha Answer', 'ultimate-social-deux' ),
			'desc' => __( 'Your captcha answer', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_mail_captcha_answer', 'us_mail', '9'),
		) );
		$panelMail->createOption( array(
			'id' => 'us_mail_try',
			'name' => __( 'Try message', 'ultimate-social-deux' ),
			'desc' => __( 'Before the message has been sent', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_mail_try', 'us_mail', __( 'Trying to send email...', 'ultimate-social-deux' )),
		) );
		$panelMail->createOption( array(
			'id' => 'us_mail_success',
			'name' => __( 'Success message', 'ultimate-social-deux' ),
			'desc' => __( 'For successful sent email', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_mail_success', 'us_mail', __( 'Great work! Your message was sent.', 'ultimate-social-deux' )),
		) );
		$panelMail->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Placement settings.
		 */
		$floatingTab = $panelPlacement->createTab( array(
		    'name' => __( 'Floating', 'ultimate-social-deux' ),
		) );

		/**
		 * Floating placement.
		 */
		$floatingTab->createOption( array(
		    'name' => __( 'Floating', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );

		$floatingTab->createOption( array(
			'id' => 'us_floating_buttons',
			'name' => __( 'Buttons', 'ultimate-social-deux' ),
			'type' => 'sortable',
			'options' => $this->buttons_array(),
			'default' => self::unset_options( UltimateSocialDeux::opt_old('us_floating', 'us_placement', array() ) ),
		) );

		$floatingTab->createOption( array(
			'id' => 'us_floating_skin',
			'name' => __( 'Skin', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => UltimateSocialDeux::skins_array(),
		) );
		$floatingTab->createOption( array(
			'id' => 'us_floating_options',
			'name' => __( 'Options', 'ultimate-social-deux' ),
			'type' => 'multicheck',
			'options' => array(
				'hide_frontpage' => __( 'Hide on frontpage?', 'ultimate-social-deux' ),
				'hide_blogpage' => __( 'Hide on blog page?', 'ultimate-social-deux' ),
				'hide_posts' => __( 'Hide on posts?', 'ultimate-social-deux' ),
				'hide_pages' => __( 'Hide on pages?', 'ultimate-social-deux' ),
				'hide_archive' => __( 'Hide on archive pages?', 'ultimate-social-deux' ),
				'hide_search' => __( 'Hide on search pages?', 'ultimate-social-deux' ),
				'hide_mobile' => __( 'Hide on mobile?', 'ultimate-social-deux' ),
				'hide_desktop' => __( 'Hide on desktop?', 'ultimate-social-deux' ),
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
			),
			'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_floating', 'us_placement', array() ) ),
		) );
		$floatingTab->createOption( array(
			'id' => 'us_floating_url',
			'name' => __( 'Custom URL', 'ultimate-social-deux' ),
			'desc' => __( 'You might want a static URL for your floating buttons across your site.', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_floating_url', 'us_placement' ),
		) );
		$floatingTab->createOption( array(
			'id' => 'us_floating_exclude',
			'name' => __( 'Exclude', 'ultimate-social-deux' ),
			'desc' => __( 'Exclude Floating buttons on posts/pages with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
			'default' => UltimateSocialDeux::opt_old('us_floating_exclude', 'us_placement' ),
		) );
		$floatingTab->createOption( array(
			'id' => 'us_floating_speed',
			'name' => __( 'Floating animation speed', 'ultimate-social-deux' ),
			'desc' => __( 'in ms', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_floating_speed', 'us_placement', '1000' ),
		) );

		$floatingTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Pages top placement.
		 */
		$pagesTab = $panelPlacement->createTab( array(
		    'name' => __( 'Pages', 'ultimate-social-deux' ),
		) );
		$pagesTab->createOption( array(
		    'name' => __( 'Top of Pages', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_top_buttons',
			'name' => __( 'Buttons', 'ultimate-social-deux' ),
			'type' => 'sortable',
			'options' => $this->buttons_array(),
			'default' => self::unset_options( UltimateSocialDeux::opt_old('us_pages_top', 'us_placement', array() ) ),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_top_options',
			'name' => __( 'Options', 'ultimate-social-deux' ),
			'type' => 'multicheck',
			'options' => array(
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				'sticky' => __( 'Make sticky?', 'ultimate-social-deux' ),
			),
			'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_pages_top', 'us_placement', array() ) ),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_top_skin',
			'name' => __( 'Skin', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => UltimateSocialDeux::skins_array(),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_top_align',
			'name' => __( 'Align', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_pages_top_align', 'us_placement', 'center' ),
			'options' => array(
				'left' => __( 'Left', 'ultimate-social-deux' ),
				'center' => __( 'Center', 'ultimate-social-deux' ),
				'right' => __( 'Right', 'ultimate-social-deux' )
			)
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_top_exclude',
			'name' => __( 'Exclude', 'ultimate-social-deux' ),
			'desc' => __( 'Exclude Buttons on top of pages with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pages_top_exclude', 'us_placement' ),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_top_share_text',
			'name' => __( 'Share text', 'ultimate-social-deux' ),
			'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pages_top_share_text', 'us_placement' ),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_top_margin_top',
			'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pages_top_margin_top', 'us_placement' ),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_top_margin_bottom',
			'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pages_top_margin_bottom', 'us_placement' ),
		) );
		$pagesTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Pages bottom placement.
		 */
		$pagesTab->createOption( array(
		    'name' => __( 'Bottom of Pages', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_bottom_buttons',
			'name' => __( 'Buttons', 'ultimate-social-deux' ),
			'type' => 'sortable',
			'options' => $this->buttons_array(),
			'default' => self::unset_options( UltimateSocialDeux::opt_old('us_pages_bottom', 'us_placement', array() ) ),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_bottom_skin',
			'name' => __( 'Skin', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => UltimateSocialDeux::skins_array(),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_bottom_options',
			'name' => __( 'Options', 'ultimate-social-deux' ),
			'type' => 'multicheck',
			'options' => array(
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
			),
			'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_pages_bottom', 'us_placement', array() ) ),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_bottom_align',
			'name' => __( 'Align', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_pages_bottom_align', 'us_placement', 'center' ),
			'options' => array(
				'left' => __( 'Left', 'ultimate-social-deux' ),
				'center' => __( 'Center', 'ultimate-social-deux' ),
				'right' => __( 'Right', 'ultimate-social-deux' )
			)
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_bottom_exclude',
			'name' => __( 'Exclude', 'ultimate-social-deux' ),
			'desc' => __( 'Exclude Buttons on bottom of pages with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pages_bottom_exclude', 'us_placement' ),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_bottom_share_text',
			'name' => __( 'Share text', 'ultimate-social-deux' ),
			'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pages_bottom_share_text', 'us_placement' ),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_bottom_margin_top',
			'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pages_bottom_margin_top', 'us_placement' ),
		) );
		$pagesTab->createOption( array(
			'id' => 'us_pages_bottom_margin_bottom',
			'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pages_bottom_margin_bottom', 'us_placement' ),
		) );
		$pagesTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Post excerpts top placement.
		 */
		$excerptsTab = $panelPlacement->createTab( array(
		    'name' => __( 'Excerpts', 'ultimate-social-deux' ),
		) );
		$excerptsTab->createOption( array(
		    'name' => __( 'Top of Post excerpts', 'ultimate-social-deux' ) . ' - ' . __( 'Your theme may not support this.', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_top_buttons',
			'name' => __( 'Buttons', 'ultimate-social-deux' ),
			'type' => 'sortable',
			'options' => $this->buttons_array(),
			'default' => self::unset_options( UltimateSocialDeux::opt_old('us_excerpts_top', 'us_placement', array() ) ),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_top_skin',
			'name' => __( 'Skin', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => UltimateSocialDeux::skins_array(),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_top_options',
			'name' => __( 'Options', 'ultimate-social-deux' ),
			'type' => 'multicheck',
			'options' => array(
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
			),
			'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_excerpts_top', 'us_placement', array() ) ),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_top_align',
			'name' => __( 'Align', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_excerpts_top_align', 'us_placement', 'center' ),
			'options' => array(
				'left' => __( 'Left', 'ultimate-social-deux' ),
				'center' => __( 'Center', 'ultimate-social-deux' ),
				'right' => __( 'Right', 'ultimate-social-deux' )
			)
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_top_exclude',
			'name' => __( 'Exclude', 'ultimate-social-deux' ),
			'desc' => __( 'Exclude Buttons on top of post excerpts with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_excerpts_top_exclude', 'us_placement' ),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_top_share_text',
			'name' => __( 'Share text', 'ultimate-social-deux' ),
			'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_excerpts_top_share_text', 'us_placement' ),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_top_margin_top',
			'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_excerpts_top_margin_top', 'us_placement' ),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_top_margin_bottom',
			'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_excerpts_top_margin_bottom', 'us_placement' ),
		) );
		$excerptsTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Post excerpts bottom placement.
		 */
		$excerptsTab->createOption( array(
		    'name' => __( 'Bottom of Post excerpts', 'ultimate-social-deux' ) . ' - ' . __( 'Your theme may not support this.', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_bottom',
			'name' => __( 'Buttons', 'ultimate-social-deux' ),
			'type' => 'sortable',
			'options' => $this->buttons_array(),
			'default' => self::unset_options( UltimateSocialDeux::opt_old('us_excerpts_bottom', 'us_placement', array() ) ),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_bottom_skin',
			'name' => __( 'Skin', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => UltimateSocialDeux::skins_array(),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_bottom_options',
			'name' => __( 'Options', 'ultimate-social-deux' ),
			'type' => 'multicheck',
			'options' => array(
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
			),
			'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_excerpts_bottom', 'us_placement', array() ) ),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_bottom_align',
			'name' => __( 'Align', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_excerpts_bottom_align', 'us_placement', 'center' ),
			'options' => array(
				'left' => __( 'Left', 'ultimate-social-deux' ),
				'center' => __( 'Center', 'ultimate-social-deux' ),
				'right' => __( 'Right', 'ultimate-social-deux' )
			)
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_bottom_exclude',
			'name' => __( 'Exclude', 'ultimate-social-deux' ),
			'desc' => __( 'Exclude Buttons on bottom of post excerpts with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_excerpts_bottom_exclude', 'us_placement' ),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_bottom_share_text',
			'name' => __( 'Share text', 'ultimate-social-deux' ),
			'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_excerpts_bottom_share_text', 'us_placement' ),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_bottom_margin_top',
			'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_excerpts_bottom_margin_top', 'us_placement' ),
		) );
		$excerptsTab->createOption( array(
			'id' => 'us_excerpts_bottom_margin_bottom',
			'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_excerpts_bottom_margin_bottom', 'us_placement' ),
		) );
		$excerptsTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Posts top placement.
		 */
		$postsTab = $panelPlacement->createTab( array(
		    'name' => __( 'Posts', 'ultimate-social-deux' ),
		) );
		$postsTab->createOption( array(
		    'name' => __( 'Top of Posts', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_top_buttons',
			'name' => __( 'Buttons', 'ultimate-social-deux' ),
			'type' => 'sortable',
			'options' => $this->buttons_array(),
			'default' => self::unset_options( UltimateSocialDeux::opt_old('us_posts_top', 'us_placement', array() ) ),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_top_skin',
			'name' => __( 'Skin', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => UltimateSocialDeux::skins_array(),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_top_options',
			'name' => __( 'Options', 'ultimate-social-deux' ),
			'type' => 'multicheck',
			'options' => array(
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				'hide_blogpage' => __( 'Hide on blog page?', 'ultimate-social-deux' ),
				'hide_archive' => __( 'Hide on archive pages?', 'ultimate-social-deux' ),
				'hide_search' => __( 'Hide on search pages?', 'ultimate-social-deux' ),
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				'sticky' => __( 'Make sticky?', 'ultimate-social-deux' ),
			),
			'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_posts_top', 'us_placement', array() ) ),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_top_align',
			'name' => __( 'Align', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_posts_top_align', 'us_placement', 'center' ),
			'options' => array(
				'left' => __( 'Left', 'ultimate-social-deux' ),
				'center' => __( 'Center', 'ultimate-social-deux' ),
				'right' => __( 'Right', 'ultimate-social-deux' )
			)
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_top_exclude',
			'name' => __( 'Exclude', 'ultimate-social-deux' ),
			'desc' => __( 'Exclude Buttons on top of posts with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_posts_top_exclude', 'us_placement' ),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_top_share_text',
			'name' => __( 'Share text', 'ultimate-social-deux' ),
			'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_posts_top_share_text', 'us_placement' ),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_top_margin_top',
			'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_posts_top_margin_top', 'us_placement' ),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_top_margin_bottom',
			'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_posts_top_margin_bottom', 'us_placement' ),
		) );
		$postsTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Posts bottom placement.
		 */
		$postsTab->createOption( array(
		    'name' => __( 'Bottom of Posts', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_bottom_buttons',
			'name' => __( 'Buttons', 'ultimate-social-deux' ),
			'type' => 'sortable',
			'options' => $this->buttons_array(),
			'default' => self::unset_options( UltimateSocialDeux::opt_old('us_posts_bottom', 'us_placement', array() ) ),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_bottom_skin',
			'name' => __( 'Skin', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => UltimateSocialDeux::skins_array(),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_bottom_options',
			'name' => __( 'Options', 'ultimate-social-deux' ),
			'type' => 'multicheck',
			'options' => array(
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				'hide_blogpage' => __( 'Hide on blog page?', 'ultimate-social-deux' ),
				'hide_archive' => __( 'Hide on archive pages?', 'ultimate-social-deux' ),
				'hide_search' => __( 'Hide on search pages?', 'ultimate-social-deux' ),
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
			),
			'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_posts_bottom', 'us_placement', array() ) ),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_bottom_align',
			'name' => __( 'Align', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_posts_bottom_align', 'us_placement', 'center' ),
			'options' => array(
				'left' => __( 'Left', 'ultimate-social-deux' ),
				'center' => __( 'Center', 'ultimate-social-deux' ),
				'right' => __( 'Right', 'ultimate-social-deux' )
			)
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_bottom_exclude',
			'name' => __( 'Exclude', 'ultimate-social-deux' ),
			'desc' => __( 'Exclude Buttons on bottom of posts with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_posts_bottom_exclude', 'us_placement' ),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_bottom_share_text',
			'name' => __( 'Share text', 'ultimate-social-deux' ),
			'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_posts_bottom_share_text', 'us_placement' ),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_bottom_margin_top',
			'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_posts_bottom_margin_top', 'us_placement' ),
		) );
		$postsTab->createOption( array(
			'id' => 'us_posts_bottom_margin_bottom',
			'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_posts_bottom_margin_bottom', 'us_placement' ),
		) );
		$postsTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		if (class_exists('Woocommerce')) {

			/**
			 * WooCommerce top placement.
			 */
			$woocommerceTab = $panelPlacement->createTab( array(
			    'name' => __( 'WooCommerce', 'ultimate-social-deux' ),
			) );
			$woocommerceTab->createOption( array(
			    'name' => __( 'WooCommerce Top', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_top_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
			'default' => self::unset_options( UltimateSocialDeux::opt_old('us_woocommerce_top', 'us_placement', array() ) ),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_top_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_top_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_woocommerce_top', 'us_placement', array() ) ),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_top_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => UltimateSocialDeux::opt_old('us_woocommerce_top_align', 'us_placement', 'center' ),
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_top_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on top of WooCommerce products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_woocommerce_top_exclude', 'us_placement' ),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_top_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_woocommerce_top_share_text', 'us_placement' ),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_top_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_woocommerce_top_margin_top', 'us_placement' ),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_top_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_woocommerce_top_margin_bottom', 'us_placement' ),
			) );
			$woocommerceTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

			/**
			 * WooCommerce bottom placement.
			 */
			$woocommerceTab->createOption( array(
			    'name' => __( 'WooCommerce Bottom', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_bottom_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => self::unset_options( UltimateSocialDeux::opt_old('us_woocommerce_bottom', 'us_placement', array() ) ),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_bottom_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_bottom_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_woocommerce_bottom', 'us_placement', array() ) ),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_bottom_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => UltimateSocialDeux::opt_old('us_woocommerce_bottom_align', 'us_placement', 'center' ),
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_bottom_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on bottom of WooCommerce products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_woocommerce_bottom_exclude', 'us_placement' ),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_bottom_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_woocommerce_bottom_share_text', 'us_placement' ),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_bottom_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_woocommerce_bottom_margin_top', 'us_placement' ),
			) );
			$woocommerceTab->createOption( array(
				'id' => 'us_woocommerce_bottom_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_woocommerce_bottom_margin_bottom', 'us_placement' ),
			) );
			$woocommerceTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

		}

		if (class_exists('Jigoshop')) {

			/**
			 * Jigoshop top placement.
			 */
			$jigoshopTab = $panelPlacement->createTab( array(
			    'name' => __( 'Jigoshop', 'ultimate-social-deux' ),
			) );
			$jigoshopTab->createOption( array(
			    'name' => __( 'Jigoshop Top', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_top_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => self::unset_options( UltimateSocialDeux::opt_old('us_jigoshop_top', 'us_placement', array() ) ),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_top_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_top_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_jigoshop_top', 'us_placement', array() ) ),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_top_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => UltimateSocialDeux::opt_old('us_jigoshop_top_align', 'us_placement', 'center' ),
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_top_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on top of Jigoshop products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_jigoshop_top_exclude', 'us_placement' ),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_top_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_jigoshop_top_share_text', 'us_placement' ),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_top_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_jigoshop_top_margin_top', 'us_placement' ),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_top_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_jigoshop_top_margin_bottom', 'us_placement' ),
			) );
			$jigoshopTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

			/**
			 * Jigoshop bottom placement.
			 */
			$jigoshopTab->createOption( array(
			    'name' => __( 'Jigoshop Bottom', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_bottom_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => self::unset_options( UltimateSocialDeux::opt_old('us_jigoshop_bottom', 'us_placement', array() ) ),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_bottom_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_bottom_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_jigoshop_bottom', 'us_placement', array() ) ),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_bottom_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => UltimateSocialDeux::opt_old('us_jigoshop_bottom_align', 'us_placement', 'center' ),
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_bottom_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on bottom of Jigoshop products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_jigoshop_bottom_exclude', 'us_placement' ),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_bottom_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_jigoshop_bottom_share_text', 'us_placement' ),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_bottom_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_jigoshop_bottom_margin_top', 'us_placement' ),
			) );
			$jigoshopTab->createOption( array(
				'id' => 'us_jigoshop_bottom_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_jigoshop_bottom_margin_bottom', 'us_placement' ),
			) );
			$jigoshopTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

		}

		if (class_exists('IT_Exchange')) {

			/**
			 * iThemes Exchange top placement.
			 */
			$exchangeTab = $panelPlacement->createTab( array(
			    'name' => __( 'iThemes Exchange', 'ultimate-social-deux' ),
			) );
			$exchangeTab->createOption( array(
			    'name' => __( 'iThemes Exchange Top', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_top_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => array(),
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_top_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_top_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => array(),
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_top_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => 'center',
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_top_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on top of iThemes Exchange products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_top_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_top_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_top_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$exchangeTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

			/**
			 * iThemes Exchange bottom placement.
			 */
			$exchangeTab->createOption( array(
			    'name' => __( 'iThemes Exchange Bottom', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_bottom_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => array(),
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_bottom_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_bottom_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => array(),
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_bottom_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => 'center',
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_bottom_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on bottom of iThemes Exchange products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_bottom_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_bottom_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$exchangeTab->createOption( array(
				'id' => 'us_exchange_bottom_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$exchangeTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

		}

		if (class_exists('Easy_Digital_Downloads')) {

			/**
			 * Easy Digital Downloads top placement.
			 */
			$eddTab = $panelPlacement->createTab( array(
			    'name' => __( 'Easy Digital Downloads', 'ultimate-social-deux' ),
			) );
			$eddTab->createOption( array(
			    'name' => __( 'Easy Digital Downloads Top', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_top_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => self::unset_options( UltimateSocialDeux::opt_old('us_edd_top', 'us_placement', array() ) ),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_top_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_top_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_edd_top', 'us_placement', array() ) ),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_top_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => UltimateSocialDeux::opt_old('us_edd_top_align', 'us_placement', 'center' ),
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_top_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on top of BuddyPress products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_edd_top_exclude', 'us_placement' ),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_top_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_edd_top_share_text', 'us_placement' ),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_top_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_edd_top_margin_top', 'us_placement' ),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_top_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_edd_top_margin_bottom', 'us_placement' ),
			) );
			$eddTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

			/**
			 * Easy Digital Downloads bottom placement.
			 */
			$eddTab->createOption( array(
			    'name' => __( 'Easy Digital Downloads Bottom', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_bottom_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => self::unset_options( UltimateSocialDeux::opt_old('us_edd_bottom', 'us_placement', array() ) ),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_bottom_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_bottom_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_edd_bottom', 'us_placement', array() ) ),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_bottom_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => UltimateSocialDeux::opt_old('us_edd_bottom_align', 'us_placement', 'center' ),
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_bottom_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on bottom of BuddyPress products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_edd_bottom_exclude', 'us_placement' ),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_bottom_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_edd_bottom_share_text', 'us_placement' ),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_bottom_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_edd_bottom_margin_top', 'us_placement' ),
			) );
			$eddTab->createOption( array(
				'id' => 'us_edd_bottom_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
				'default' => UltimateSocialDeux::opt_old('us_edd_bottom_margin_bottom', 'us_placement' ),
			) );
			$eddTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

		}


		if (class_exists('bbPress')) {

			/**
			 * bbPress before topic placement.
			 */
			$bbpressTab = $panelPlacement->createTab( array(
			    'name' => __( 'bbPress', 'ultimate-social-deux' ),
			) );
			$bbpressTab->createOption( array(
			    'name' => __( 'bbPress Before Topics', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_topics_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_topics_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_topics_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_topics_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => 'center',
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_topics_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on top of BuddyPress products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_topics_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_topics_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_topics_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

			/**
			 * bbPress after replies placement.
			 */
			$bbpressTab->createOption( array(
			    'name' => __( 'bbPress After Topics', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_topics_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_topics_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_topics_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_topics_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => 'center',
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_topics_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on top of BuddyPress products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_topics_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_topics_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_topics_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

			/**
			 * bbPress before replies placement.
			 */
			$bbpressTab->createOption( array(
			    'name' => __( 'bbPress Before Replies', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_replies_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_replies_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_replies_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_replies_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => 'center',
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_replies_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on top of BuddyPress products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_replies_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_replies_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_before_replies_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

			/**
			 * bbPress after replies placement.
			 */
			$bbpressTab->createOption( array(
			    'name' => __( 'bbPress After Replies', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_replies_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_replies_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_replies_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => array(),
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_replies_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => 'center',
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_replies_exclude',
				'name' => __( 'Exclude', 'ultimate-social-deux' ),
				'desc' => __( 'Exclude Buttons on top of BuddyPress products with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_replies_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_replies_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
				'id' => 'us_bbpress_after_replies_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$bbpressTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

		}

		if (class_exists('BuddyPress')) {

			/**
			 * BuddyPress top placement.
			 */
			$buddypressTab = $panelPlacement->createTab( array(
			    'name' => __( 'BuddyPress', 'ultimate-social-deux' ),
			) );
			$buddypressTab->createOption( array(
			    'name' => __( 'BuddyPress Group', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_top_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => array(),
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_top_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_top_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => array(),
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_top_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => 'center',
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_top_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_top_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_top_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$buddypressTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

			/**
			 * BuddyPress Activity placement.
			 */
			$buddypressTab->createOption( array(
			    'name' => __( 'BuddyPress Activity', 'ultimate-social-deux' ),
			    'type' => 'heading',
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_activity_buttons',
				'name' => __( 'Buttons', 'ultimate-social-deux' ),
				'type' => 'sortable',
				'options' => $this->buttons_array(),
				'default' => array(),
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_activity_skin',
				'name' => __( 'Skin', 'ultimate-social-deux' ),
				'type' => 'select',
				'options' => UltimateSocialDeux::skins_array(),
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_activity_options',
				'name' => __( 'Options', 'ultimate-social-deux' ),
				'type' => 'multicheck',
				'options' => array(
					'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
					'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
				),
				'default' => array(),
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_activity_align',
				'name' => __( 'Align', 'ultimate-social-deux' ),
				'type' => 'radio',
				'default' => 'center',
				'options' => array(
					'left' => __( 'Left', 'ultimate-social-deux' ),
					'center' => __( 'Center', 'ultimate-social-deux' ),
					'right' => __( 'Right', 'ultimate-social-deux' )
				)
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_activity_share_text',
				'name' => __( 'Share text', 'ultimate-social-deux' ),
				'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_activity_margin_top',
				'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$buddypressTab->createOption( array(
				'id' => 'us_buddypress_activity_margin_bottom',
				'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
				'desc' => __( 'In pixels', 'ultimate-social-deux' ),
				'type' => 'text',
			) );
			$buddypressTab->createOption( array(
			    'type' => 'save',
			    'use_reset' => false,
			) );

		}

		/**
		 * Custom Post Types top placement.
		 */
		$cptTab = $panelPlacement->createTab( array(
		    'name' => __( 'Custom Post Types', 'ultimate-social-deux' ),
		) );
		$cptTab->createOption( array(
		    'name' => __( 'Top of Custom Post Types', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_top_cpt',
			'name' => __( 'CPT Slugs', 'ultimate-social-deux' ),
			'desc' => __( 'Add the slugs of the CPT\'s that you want the buttons to display on. Comma seperated: "books, movies, links"', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cpt_top_cpt', 'us_placement' ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_top_buttons',
			'name' => __( 'Buttons', 'ultimate-social-deux' ),
			'type' => 'sortable',
			'options' => $this->buttons_array(),
			'default' => self::unset_options( UltimateSocialDeux::opt_old('us_cpt_top', 'us_placement', array() ) ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_top_skin',
			'name' => __( 'Skin', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => UltimateSocialDeux::skins_array(),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_top_options',
			'name' => __( 'Options', 'ultimate-social-deux' ),
			'type' => 'multicheck',
			'options' => array(
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
			),
			'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_cpt_top', 'us_placement', array() ) ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_top_align',
			'name' => __( 'Align', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_cpt_top_align', 'us_placement', 'center' ),
			'options' => array(
				'left' => __( 'Left', 'ultimate-social-deux' ),
				'center' => __( 'Center', 'ultimate-social-deux' ),
				'right' => __( 'Right', 'ultimate-social-deux' )
			),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_top_exclude',
			'name' => __( 'Exclude', 'ultimate-social-deux' ),
			'desc' => __( 'Exclude Buttons on top of custom post types with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cpt_top_exclude', 'us_placement' ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_top_share_text',
			'name' => __( 'Share text', 'ultimate-social-deux' ),
			'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cpt_top_share_text', 'us_placement' ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_top_margin_top',
			'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cpt_top_margin_top', 'us_placement' ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_top_margin_bottom',
			'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cpt_top_margin_bottom', 'us_placement' ),
		) );
		$cptTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Custom Post Types bottom placement.
		 */
		$cptTab->createOption( array(
		    'name' => __( 'Bottom of Custom Post Types', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );

		$cptTab->createOption( array(
			'id' => 'us_cpt_bottom_cpt',
			'name' => __( 'CPT Slugs', 'ultimate-social-deux' ),
			'desc' => __( 'Add the slugs of the CPT\'s that you want the buttons to display on. Comma seperated: "books, movies, links"', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cpt_bottom_cpt', 'us_placement' ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_bottom_buttons',
			'name' => __( 'Buttons', 'ultimate-social-deux' ),
			'type' => 'sortable',
			'options' => $this->buttons_array(),
			'default' => self::unset_options( UltimateSocialDeux::opt_old('us_cpt_bottom', 'us_placement', array() ) ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_bottom_skin',
			'name' => __( 'Skin', 'ultimate-social-deux' ),
			'type' => 'select',
			'options' => UltimateSocialDeux::skins_array(),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_bottom_options',
			'name' => __( 'Options', 'ultimate-social-deux' ),
			'type' => 'multicheck',
			'options' => array(
				'hide_count' => __( 'Hide count numbers?', 'ultimate-social-deux' ),
				'native' => __( 'Native buttons on hover for Facebook, Google+ and VKontakte?', 'ultimate-social-deux' ),
			),
			'default' => self::unset_buttons( UltimateSocialDeux::opt_old('us_cpt_bottom', 'us_placement', array() ) ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_bottom_align',
			'name' => __( 'Align', 'ultimate-social-deux' ),
			'type' => 'radio',
			'default' => UltimateSocialDeux::opt_old('us_cpt_bottom_align', 'us_placement', 'center' ),
			'options' => array(
				'left' => __( 'Left', 'ultimate-social-deux' ),
				'center' => __( 'Center', 'ultimate-social-deux' ),
				'right' => __( 'Right', 'ultimate-social-deux' )
			),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_bottom_exclude',
			'name' => __( 'Exclude', 'ultimate-social-deux' ),
			'desc' => __( 'Exclude Buttons on bottom of custom post types with these IDs? Comma seperated:', 'ultimate-social-deux' ) . ' "42, 12, 4"',
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cpt_bottom_exclude', 'us_placement' ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_bottom_share_text',
			'name' => __( 'Share text', 'ultimate-social-deux' ),
			'desc' => __( 'Text to be added left of the buttons', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cpt_bottom_share_text', 'us_placement' ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_bottom_margin_top',
			'name' => __( 'Margin above buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cpt_bottom_margin_top', 'us_placement' ),
		) );
		$cptTab->createOption( array(
			'id' => 'us_cpt_bottom_margin_bottom',
			'name' => __( 'Margin below buttons', 'ultimate-social-deux' ),
			'desc' => __( 'In pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cpt_bottom_margin_bottom', 'us_placement' ),
		) );
		$cptTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Fan Count settings.
		 */
		$panelFanCount->createOption( array(
		    'name' => __( 'Cache', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_cache',
			'name' => __( 'How long should we cache the counts?', 'ultimate-social-deux' ),
			'desc' => __( 'in hours', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_cache', 'us_fan_count', '2' ),
		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Facebook', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_facebook_id',
			'name' => __( 'Facebook', 'ultimate-social-deux' ) . ' ' . __( 'Page ID', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Your ID is what comes after', 'ultimate-social-deux' ) . ' ' . 'http://facebook.com/',
			'default' => UltimateSocialDeux::opt_old('us_facebook_id', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Twitter', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_twitter_id',
			'name' => __( 'Twitter', 'ultimate-social-deux' ). ' ' .__( 'handle', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_twitter_id', 'us_fan_count' ),

		) );
		$panelFanCount->createOption( array(
			'id' => 'us_twitter_key',
			'name' => __( 'Twitter', 'ultimate-social-deux' ). ' ' .__( 'App key', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Register your App at', 'ultimate-social-deux' ) . ' ' . 'https://apps.twitter.com/',
			'default' => UltimateSocialDeux::opt_old('us_twitter_key', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_twitter_secret',
			'name' => __( 'Twitter', 'ultimate-social-deux' ). ' ' .__( 'App secret', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_twitter_secret', 'us_fan_count' ),

		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Google Plus', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_google_id',
			'name' => __( 'Google Plus', 'ultimate-social-deux' ) . ' ' . __( 'Page ID', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Your ID is what comes after', 'ultimate-social-deux' ) . ' ' . 'https://plus.google.com/',
			'default' => UltimateSocialDeux::opt_old('us_google_id', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_google_key',
			'name' => __( 'Google Plus', 'ultimate-social-deux' ). ' ' .__( 'API key', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Register your App at', 'ultimate-social-deux' ) . ' ' . 'https://developers.google.com/console/help/new/#generatingdevkeys',
			'default' => UltimateSocialDeux::opt_old('us_google_key', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'LinkedIn', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_linkedin_id',
			'name' => __( 'LinkedIn', 'ultimate-social-deux' ) . ' ' . __( 'Company ID', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Your ID is what comes after', 'ultimate-social-deux' ) . ' ' . 'https://linkedin.com/',
			'default' => UltimateSocialDeux::opt_old('us_linkedin_id', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_linkedin_app',
			'name' => __( 'LinkedIn', 'ultimate-social-deux' ). ' ' .__( 'App Secret', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Register your App at', 'ultimate-social-deux' ) . ' ' . 'https://www.linkedin.com/secure/developer',
			'default' => UltimateSocialDeux::opt_old('us_linkedin_app', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_linkedin_api',
			'name' => __( 'LinkedIn', 'ultimate-social-deux' ). ' ' .__( 'API key', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_linkedin_api', 'us_fan_count' ),

		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'YouTube', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_youtube_id',
			'name' => __( 'Youtube', 'ultimate-social-deux' ) . ' ' . __( 'Page ID', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Your ID is what comes after', 'ultimate-social-deux' ) . ' ' . 'https://www.youtube.com/user/',
			'default' => UltimateSocialDeux::opt_old('us_youtube_id', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Vimeo', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_vimeo_id',
			'name' => __( 'Vimeo', 'ultimate-social-deux' ) . ' ' . __( 'Channel ID', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Your ID is what comes after', 'ultimate-social-deux' ) . ' ' . 'http://vimeo.com/channels/',
			'default' => UltimateSocialDeux::opt_old('us_vimeo_id', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Behance', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_behance_id',
			'name' => __( 'Behance', 'ultimate-social-deux' ) . ' ' . __( 'username', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Your ID is what comes after', 'ultimate-social-deux' ) . ' ' . 'http://behance.net/',
			'default' => UltimateSocialDeux::opt_old('us_behance_id', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_behance_api',
			'name' => __( 'Behance', 'ultimate-social-deux' ). ' ' .__( 'API key', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Register your App at', 'ultimate-social-deux' ) . ' ' . 'https://www.behance.net/dev/register',
			'default' => UltimateSocialDeux::opt_old('us_behance_api', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'SoundCloud', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_soundcloud_id',
			'name' => __( 'SoundCloud', 'ultimate-social-deux' ) . ' ' . __( 'Client ID', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Register your App at', 'ultimate-social-deux' ) . ' ' . 'http://soundcloud.com/you/apps',
			'default' => UltimateSocialDeux::opt_old('us_soundcloud_id', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_soundcloud_username',
			'name' => __( 'SoundCloud username', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_soundcloud_username', 'us_fan_count' ),

		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Dribbble', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_dribbble_id',
			'name' => __( 'Dribbble', 'ultimate-social-deux' ) . ' ' . __( 'Page ID', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Your ID is what comes after', 'ultimate-social-deux' ) . ' ' . 'https://dribbble.com/',
			'default' => UltimateSocialDeux::opt_old('us_dribbble_id', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Github', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_github_id',
			'name' => __( 'Github', 'ultimate-social-deux' ) . ' ' . __( 'username', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_github_id', 'us_fan_count' ),

		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Envato', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_envato_id',
			'name' => __( 'Envato', 'ultimate-social-deux' ) . ' ' . __( 'username', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_envato_id', 'us_fan_count' ),

		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Delicious', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_delicious_id',
			'name' => __( 'Delicious', 'ultimate-social-deux' ) . ' ' . __( 'username', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_delicious_id', 'us_fan_count' ),

		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Instagram', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_instagram_api',
			'name' => __( 'Instagram', 'ultimate-social-deux' ) . ' ' . __( 'Access Token', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' =>  __( 'Find your access token by following', 'ultimate-social-deux' ). ' ' . '<a href="http://www.pinceladasdaweb.com.br/instagram/access-token/" target="_blank">'. __( 'this link.', 'ultimate-social-deux' ).'</a>',
			'default' => UltimateSocialDeux::opt_old('us_instagram_api', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_instagram_username',
			'name' => __( 'Instagram', 'ultimate-social-deux' ) . ' ' . __( 'username', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_instagram_username', 'us_fan_count' ),

		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'VKontakte', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_vkontakte_id',
			'name' => __( 'VKontakte', 'ultimate-social-deux' ) . ' ' . __( 'Group ID', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Your ID is what comes after', 'ultimate-social-deux' ) . ' ' . 'http://vk.com/.' . ' ' . __( 'Make sure that you are getting a group ID and not a page ID.', 'ultimate-social-deux' ),
			'default' => UltimateSocialDeux::opt_old('us_vkontakte_id', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Pinterest', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_pinterest_username',
			'name' => __( 'Pinterest', 'ultimate-social-deux' ) . ' ' . __( 'username', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pinterest_username', 'us_fan_count' ),

		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Flickr', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_flickr_id',
			'name' => __( 'Flickr', 'ultimate-social-deux' ) . ' ' . __( 'Group ID', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'You can find your ID here:', 'ultimate-social-deux' ) . ' ' . 'http://idgettr.com/',
			'default' => UltimateSocialDeux::opt_old('us_flickr_id', 'us_placement' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_flickr_api',
			'name' => __( 'Flickr', 'ultimate-social-deux' ) . ' ' . __( 'API key', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Register your App at', 'ultimate-social-deux' ) . ' ' . 'https://www.flickr.com/services/apps/create/apply',
			'default' => UltimateSocialDeux::opt_old('us_flickr_api', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Mailchimp', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_mailchimp_name',
			'name' => __( 'Mailchimp', 'ultimate-social-deux' ) . ' ' . __( 'list name', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Get your list name from', 'ultimate-social-deux' ) . ' ' . 'https://admin.mailchimp.com/lists/',
			'default' => UltimateSocialDeux::opt_old('us_mailchimp_name', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_mailchimp_api',
			'name' => __( 'Mailchimp', 'ultimate-social-deux' ) . ' ' . __( 'API key', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Register your App at', 'ultimate-social-deux' ) . ' ' . 'https://admin.mailchimp.com/account/api/',
			'default' => UltimateSocialDeux::opt_old('us_mailchimp_api', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_mailchimp_link',
			'name' => __( 'Mailchimp', 'ultimate-social-deux' ) . ' ' . __( 'subscription link', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Use your own or build one with Mailchimp', 'ultimate-social-deux' ),
			'default' => UltimateSocialDeux::opt_old('us_mailchimp_link', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
		    'name' => __( 'Feedpress', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_feedpress_url',
			'name' => __( 'Feedpress', 'ultimate-social-deux' ) . ' ' . __( 'JSON url', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Link yo your feeds .json file. First go to', 'ultimate-social-deux' ) . ' ' . 'http://feedpress.it/feeds/YOUR-FEED-ID.' . ' ' . __( 'Then choose JSON File from the Miscellaneous dropdown menu. You must have a Premium Feedpress account to do use this.', 'ultimate-social-deux' ),
			'default' => UltimateSocialDeux::opt_old('us_feedpress_url', 'us_fan_count' ),
		) );
		$panelFanCount->createOption( array(
			'id' => 'us_feedpress_manual',
			'name' => __( 'Manual RSS count', 'ultimate-social-deux' ),
			'type' => 'text',
			'desc' => __( 'Add a manual count if you do not have a Premium Feedpress account.', 'ultimate-social-deux' ),
			'default' => UltimateSocialDeux::opt_old('us_feedpress_manual', 'us_fan_count' ),
		) );

		$panelFanCount->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * Advanced settings.
		 */

		$popupTab = $panel->createTab( array(
		    'name' => __( 'Popup', 'ultimate-social-deux' ),
		) );
		$popupTab->createOption( array(
		    'name' => __( 'Popup Settings', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$popupTab->createOption( array(
			'id' => 'us_facebook_height',
			'name' => __( 'Facebook', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_facebook_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_facebook_width',
			'name' => __( 'Facebook', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_facebook_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_twitter_height',
			'name' => __( 'Twitter', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_twitter_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_twitter_width',
			'name' => __( 'Twitter', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_twitter_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_googleplus_height',
			'name' => __( 'Google Plus', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_googleplus_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_googleplus_width',
			'name' => __( 'Google Plus', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_googleplus_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_delicious_height',
			'name' => __( 'Delicious', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_delicious_height', 'us_advanced', '550' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_delicious_width',
			'name' => __( 'Delicious', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => '550',
		) );
		$popupTab->createOption( array(
			'id' => 'us_stumble_height',
			'name' => __( 'StumbleUpon', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_stumble_height', 'us_advanced', '550' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_stumble_width',
			'name' => __( 'StumbleUpon', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_stumble_width', 'us_advanced', '550' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_linkedin_height',
			'name' => __( 'LinkedIn', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_linkedin_height', 'us_advanced', '550' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_linkedin_width',
			'name' => __( 'LinkedIn', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_linkedin_width', 'us_advanced', '550' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_pinterest_height',
			'name' => __( 'Pinterest', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pinterest_height', 'us_advanced', '320' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_pinterest_width',
			'name' => __( 'Pinterest', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pinterest_width', 'us_advanced', '720' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_buffer_height',
			'name' => __( 'Buffer', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_buffer_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_buffer_width',
			'name' => __( 'Buffer', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_buffer_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_reddit_height',
			'name' => __( 'Reddit', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_reddit_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_reddit_width',
			'name' => __( 'Reddit', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_reddit_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_vkontakte_height',
			'name' => __( 'VKontakte', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_vkontakte_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_vkontakte_width',
			'name' => __( 'VKontakte', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_vkontakte_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_printfriendly_height',
			'name' => __( 'Printfriendlyfriendly', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_printfriendly_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_printfriendly_width',
			'name' => __( 'Printfriendlyfriendly', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_printfriendly_width', 'us_advanced', '1045' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_pocket_height',
			'name' => __( 'Pocket', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pocket_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_pocket_width',
			'name' => __( 'Pocket', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_pocket_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_tumblr_height',
			'name' => __( 'Tumblr', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_tumblr_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_tumblr_width',
			'name' => __( 'Tumblr', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_tumblr_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_flipboard_height',
			'name' => __( 'Flipboard', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_flipboard_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_flipboard_width',
			'name' => __( 'Flipboard', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_flipboard_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_ok_height',
			'name' => __( 'Odnoklassniki', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_ok_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_ok_width',
			'name' => __( 'Odnoklassniki', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_ok_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_weibo_height',
			'name' => __( 'Weibo', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_weibo_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_weibo_width',
			'name' => __( 'Weibo', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_weibo_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_xing_height',
			'name' => __( 'Xing', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_xing_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_xing_width',
			'name' => __( 'Xing', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_xing_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_managewp_height',
			'name' => __( 'Managewp', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_managewp_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_managewp_width',
			'name' => __( 'Managewp', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_managewp_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_meneame_height',
			'name' => __( 'Meneame', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_meneame_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_meneame_width',
			'name' => __( 'Meneame', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_meneame_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_digg_height',
			'name' => __( 'Digg', 'ultimate-social-deux' ) . ' ' . __('Popup Height', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_digg_height', 'us_advanced', '500' ),
		) );
		$popupTab->createOption( array(
			'id' => 'us_digg_width',
			'name' => __( 'Digg', 'ultimate-social-deux' ) . ' ' . __('Popup Width', 'ultimate-social-deux' ),
			'desc' => __( 'in pixels', 'ultimate-social-deux' ),
			'type' => 'text',
			'default' => UltimateSocialDeux::opt_old('us_digg_width', 'us_advanced', '900' ),
		) );
		$popupTab->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );

		/**
		 * License settings.
		 */
		$panelLicense->createOption( array(
		    'name' => __( 'License', 'ultimate-social-deux' ),
		    'type' => 'heading',
		) );
		$panelLicense->createOption( array(
			'id' => 'us_license',
			'name' => __( 'Enter your CodeCanyon Purchase Code', 'ultimate-social-deux' ),
			'desc' => __( 'This enables automatic updates.', 'ultimate-social-deux' ) . ' <a href="'.admin_url( 'update-core.php?force-check=1' ).'">'.__( 'Check for updates.', 'ultimate-social-deux' ).'</a>',
			'type' => 'us-license',
			'is_password' => false,
			'default' => UltimateSocialDeux::opt_old('us_license', 'us_license' ),
		) );
		$panelLicense->createOption( array(
			'id' => 'us_license_help',
			'name' => __( 'To access your Purchase Code for an item:', 'ultimate-social-deux' ),
			'desc' => '<ol><li>' . __( 'Log into your CodeCanyon account.', 'ultimate-social-deux' ) . '</li><li>' . __('From your account dropdown links, select "Downloads".', 'ultimate-social-deux' ) . '</li><li>' . __('Click the "Download" button that corresponds with your purchase.', 'ultimate-social-deux' ) . '</li><li>' . __('Select the "License certificate & purchase code" download link. Your Purchase Code will be displayed within the License Certificate.', 'ultimate-social-deux' ) . '</li></ol>',
			'type' => 'note',
		) );
		$panelLicense->createOption( array(
		    'type' => 'save',
		    'use_reset' => false,
		) );
	}

	public function header() {
		echo '<div class="us_admin_header">';
			echo '<div class="us_admin_logo"><img src="'.plugin_dir_url( __FILE__ ) . 'assets/img/ultimate-social-deux.png'.'"></div>';
			echo '<div class="us_admin_right">';
				echo '<a class="us_admin_rating" href="http://codecanyon.net/downloads?ref=ultimate-wp" target="_blank"><span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span></a>';
				echo '<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fcodecanyon.net%2Fitem%2Fultimate-social-deux%2F6556073%3Fref%3Dultimate-wp&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px; width:109px;" allowTransparency="true"></iframe>';
			echo "</div>";
		echo "</div>";
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

	public static function fifteenpx_array() {
		$array = array(
			'0' => 'none',
			'1' => '1px',
			'2' => '2px',
			'3' => '3px',
			'4' => '4px',
			'5' => '5px',
			'6' => '6px',
			'7' => '7px',
			'8' => '8px',
			'9' => '9px',
			'10' => '10px',
			'11' => '11px',
			'12' => '12px',
			'13' => '13px',
			'14' => '14px',
			'15' => '15px',
		);

		return $array;
	}

	public static function sixtypx_array() {
		$array = array(
			'0' => 'none',
			'1' => '1px',
			'2' => '2px',
			'3' => '3px',
			'4' => '4px',
			'5' => '5px',
			'6' => '6px',
			'7' => '7px',
			'8' => '8px',
			'9' => '9px',
			'10' => '10px',
			'11' => '11px',
			'12' => '12px',
			'13' => '13px',
			'14' => '14px',
			'15' => '15px',
			'16' => '16px',
			'17' => '17px',
			'18' => '18px',
			'19' => '19px',
			'20' => '20px',
			'21' => '21px',
			'22' => '22px',
			'23' => '23px',
			'24' => '24px',
			'25' => '25px',
			'26' => '26px',
			'27' => '27px',
			'28' => '28px',
			'29' => '29px',
			'30' => '30px',
			'31' => '31px',
			'32' => '32px',
			'33' => '33px',
			'34' => '34px',
			'35' => '35px',
			'36' => '36px',
			'37' => '37px',
			'38' => '38px',
			'39' => '39px',
			'40' => '40px',
			'41' => '41px',
			'42' => '42px',
			'43' => '43px',
			'44' => '44px',
			'45' => '45px',
			'46' => '46px',
			'47' => '47px',
			'48' => '48px',
			'49' => '49px',
			'50' => '50px',
			'51' => '51px',
			'52' => '52px',
			'53' => '53px',
			'54' => '54px',
			'55' => '55px',
			'56' => '56px',
			'57' => '57px',
			'58' => '58px',
			'59' => '59px',
			'60' => '60px',

		);

		return $array;
	}

	public static function buttons_array() {

		$facebook = __('Facebook','ultimate-social-deux');
		$twitter = __('Twitter','ultimate-social-deux');
		$google = __('Google Plus','ultimate-social-deux');
		$pinterest = __('Pinterest','ultimate-social-deux');
		$linkedin = __('LinkedIn','ultimate-social-deux');
		$stumble = __('StumbleUpon','ultimate-social-deux');
		$delicious = __('Delicious','ultimate-social-deux');
		$buffer = __('Buffer','ultimate-social-deux');
		$reddit = __('Reddit','ultimate-social-deux');
		$vkontakte = __('VKontakte','ultimate-social-deux');
		$mail = __('Mail','ultimate-social-deux');

		$array = array(
			'total' => __( 'Total', 'ultimate-social-deux' ),
			'facebook' => $facebook,
			'facebook_native' => $facebook . ' ' . __( 'native', 'ultimate-social-deux' ),
			'twitter' => $twitter,
			'googleplus' => $google,
			'googleplus_native' => $google . ' ' . __( 'native', 'ultimate-social-deux' ),
			'pinterest' => $pinterest,
			'linkedin' => $linkedin,
			'stumble' => $stumble,
			'delicious' => $delicious,
			'buffer' => $buffer,
			'reddit' => $reddit,
			'vkontakte' => $vkontakte,
			'vkontakte_native' => $vkontakte . ' ' . __( 'native', 'ultimate-social-deux' ),
			'love' => __( 'Love', 'ultimate-social-deux' ),
			'pocket' => __( 'Pocket', 'ultimate-social-deux' ),
			'ok' => __( 'Odnoklassniki', 'ultimate-social-deux' ),
			'weibo' => __( 'Weibo', 'ultimate-social-deux' ),
			'managewp' => __( 'ManageWP', 'ultimate-social-deux' ),
			'xing' => __( 'Xing', 'ultimate-social-deux' ),
			'whatsapp' => __( 'WhatsApp', 'ultimate-social-deux' ),
			'meneame' => __( 'Meneame', 'ultimate-social-deux' ),
			'digg' => __( 'Digg', 'ultimate-social-deux' ),
			'flipboard' => __( 'Flipboard', 'ultimate-social-deux' ),
			'tumblr' => __( 'Tumblr', 'ultimate-social-deux' ),
			'print' => __( 'Printfriendly', 'ultimate-social-deux' ),
			'mail' => $mail,
			'comments' => __( 'Comments', 'ultimate-social-deux' ),
			'more' => __( 'More - Buttons after this will be hidden', 'ultimate-social-deux' ),

		);

		return $array;
	}

	public static function unset_options($a) {
		unset($a['hide_frontpage']);
		unset($a['hide_blogpage']);
		unset($a['hide_posts']);
		unset($a['hide_pages']);
		unset($a['hide_archive']);
		unset($a['hide_search']);
		unset($a['hide_mobile']);
		unset($a['hide_desktop']);
		unset($a['hide_count']);
		unset($a['native']);
		unset($a['sticky']);

		return $a;
	}

	public static function unset_buttons($a) {
		unset($a['total']);
		unset($a['facebook']);
		unset($a['facebook_native']);
		unset($a['twitter']);
		unset($a['googleplus']);
		unset($a['googleplus_native']);
		unset($a['pinterest']);
		unset($a['linkedin']);
		unset($a['stumble']);
		unset($a['delicious']);
		unset($a['buffer']);
		unset($a['reddit']);
		unset($a['vkontakte']);
		unset($a['vkontakte_native']);
		unset($a['love']);
		unset($a['pocket']);
		unset($a['ok']);
		unset($a['weibo']);
		unset($a['managewp']);
		unset($a['xing']);
		unset($a['whatsapp']);
		unset($a['meneame']);
		unset($a['digg']);
		unset($a['flipboard']);
		unset($a['tumblr']);
		unset($a['print']);
		unset($a['mail']);
		unset($a['comments']);

		return serialize($a);
	}

}