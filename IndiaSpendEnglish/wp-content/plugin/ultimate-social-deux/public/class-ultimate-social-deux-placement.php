<?php
/**
 * Ultimate Social Deux.
 *
 * @package		Ultimate Social Deux
 * @author		Ultimate Wordpress <hello@ultimate-wp.com>
 * @link		http://social.ultimate-wp.com
 * @copyright 	2013 Ultimate Wordpress
 */

class UltimateSocialDeuxPlacement {

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

		add_filter( 'the_content', array( $this, 'buttons_pages_top'), 15 );

		add_filter( 'the_content', array( $this, 'buttons_pages_bottom'), 15 );

		add_filter( 'the_content', array( $this, 'buttons_posts_top'), 15 );

		add_filter( 'the_content', array( $this, 'buttons_posts_bottom'), 15 );

		add_filter( 'get_the_excerpt', array( $this, 'remove_buttons_excerpts'), -999 );

		add_filter( 'the_excerpt', array( $this, 'remove_post_text_excerpts'), -999 );

		add_filter( 'get_the_excerpt', array( $this, 'buttons_excerpts_top') );

		add_filter( 'get_the_excerpt', array( $this, 'buttons_excerpts_bottom') );

		add_action( 'wp_head', array( $this, 'buttons_floating' ) );

		if (class_exists('Woocommerce')) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'woocommerce_top' ), 1 );
			add_action( 'woocommerce_single_product_summary', array( $this, 'woocommerce_bottom' ), 50 );
		}

		if (class_exists('IT_Exchange')) {
			add_action( 'it_exchange_content_product_before_base_price_element', array( $this, 'exchange_top' ) );
			add_action( 'it_exchange_content_product_after_super_widget_element', array( $this, 'exchange_bottom' ), 99 );
		}

		if (class_exists('Jigoshop')) {
			add_action( 'jigoshop_before_single_product_summary', array( $this, 'jigoshop_top' ) );
			add_action( 'jigoshop_template_single_summary', array( $this, 'jigoshop_bottom' ), 100 );
		}

		if (class_exists('Easy_Digital_Downloads')) {
			add_filter( 'the_content', array( $this, 'buttons_edd_bottom'), 15 );
			add_filter( 'the_content', array( $this, 'buttons_edd_top'), 15 );
		}

		if (class_exists('bbPress')) {
			add_action ( 'bbp_template_before_replies_loop', array ($this, 'bbpress_before_replies' ) );
			add_action ( 'bbp_template_after_replies_loop', array ($this, 'bbpress_after_replies' ) );
			add_action ( 'bbp_template_before_topics_loop', array ($this, 'bbpress_before_topics' ) );
			add_action ( 'bbp_template_after_topics_loop', array ($this, 'bbpress_after_topics' ) );
		}

		if (class_exists('BuddyPress')) {
			add_action ( 'bp_before_group_home_content', array ($this, 'buddypress_group' ) );
			add_action ( 'bp_activity_entry_meta', array ($this, 'buddypress_activity' ), 999 );
		}

		add_filter( 'the_content', array( $this, 'buttons_cpt_bottom'), 15 );

		add_filter( 'the_content', array( $this, 'buttons_cpt_top'), 15 );

	}

	/**
	 * Removes buttons at excerpts.
	 *
	 * @since	1.0.0
	 */
	public function remove_buttons_excerpts($text){

		remove_filter( 'the_content', array( $this, 'buttons_posts_bottom'), 20 );
		remove_filter( 'the_content', array( $this, 'buttons_posts_top'), 20 );
		remove_filter( 'the_content', array( $this, 'buttons_edd_bottom'), 20 );
		remove_filter( 'the_content', array( $this, 'buttons_edd_top'), 20 );

		return $text;
	}

	/**
	 * Removes buttons at excerpts.
	 *
	 * @since	1.0.0
	 */
	public function remove_post_text_excerpts($text){

		$sharetext_top = UltimateSocialDeux::opt('us_posts_top_share_text' );
		$sharetext_bottom = UltimateSocialDeux::opt('us_posts_bottom_share_text');
		$total = UltimateSocialDeux::opt('us_total_shares_text');

		$text = self::str_replace_first($sharetext_top, '', $text);
		$text = self::str_replace_last($sharetext_bottom, '', $text);
		$text = self::str_replace_first($total, '', $text);
		$text = self::str_replace_last($total, '', $text);

		return $text;
	}

	/**
	 * Returns a subsituted string.
	 *
	 * @since	1.0.0
	 */
	public function str_replace_first($search, $replace, $string, $offset = 0) {
	    if( $search && ( $pos = strpos( $string, $search, $offset ) ) !== false ) {
	        $string = substr_replace($string, $replace, $pos, strlen($search));
	    }
	    return $string;
	}

	/**
	 * Returns a subsituted string.
	 *
	 * @since	1.0.0
	 */
	public function str_replace_last( $search , $replace , $string ) {
	    if( $search && ( $pos = strrpos( $string , $search ) ) !== false ) {
	        $search_length  = strlen( $search );
	        $string    = substr_replace( $string , $replace , $pos , $search_length );
	    }
		return $string;
	}

	/**
	 * Return the options set in admin.
	 *
	 * @since	1.0.0
	 *
	 * @return	Plugin option value.
	 */
	public function opt( $option, $section, $default = '' ) {

		$options = get_option( $section );

		if ( isset( $options[$option] ) ) {
			return $options[$option];
		}

		return $default;
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

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_posts_top($content) {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_posts_top_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_posts_top_options'));

		$sharetext = UltimateSocialDeux::opt('us_posts_top_share_text');

		$hide_blogpage = ( in_array('hide_blogpage', $options ) ) ? true : '';
		$hide_archive = ( in_array('hide_archive', $options ) ) ? true : '';
		$hide_search = ( in_array('hide_search', $options ) ) ? true : '';
		$sticky = ( in_array('sticky', $options ) ) ? true : '';

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_posts_top_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_posts_top_align', 'center' );

		$skin = UltimateSocialDeux::opt('us_posts_top_skin');

		$margin_top = intval(UltimateSocialDeux::opt('us_posts_top_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_posts_top_margin_bottom', '0'));

		$custom_content = '';

		if( !( ( $hide_blogpage && is_home() ) || ( $hide_search && is_search() ) || ( $hide_archive && is_archive() ) ) && !in_array($post->ID, $exclude, false) && get_post_type( $post->ID ) == 'post' && ( $networks ) ) {

			$custom_content .= sprintf('<div class="us_posts_top" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			$custom_content .= $content;

			if ( !( is_home() || is_search() || is_archive() ) && $sticky ) {

				$sticky_offset = intval(UltimateSocialDeux::opt('us_sticky_offset', 0));
				$sticky_get_width_from = UltimateSocialDeux::opt('us_sticky_get_width_from');
				$custom_content .= '
					<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery(".us_posts_top").sticky({
								topSpacing:'.$sticky_offset.',
								getWidthFrom: "'.$sticky_get_width_from.'"
							});
						});
					</script>
				';
			}

			$content = $custom_content;

		}

		return $content;
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_posts_bottom( $content ) {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_posts_bottom_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_posts_bottom_options'));

		$sharetext = UltimateSocialDeux::opt('us_posts_bottom_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$hide_blogpage = ( in_array('hide_blogpage', $options ) ) ? true : '';
		$hide_archive = ( in_array('hide_archive', $options ) ) ? true : '';
		$hide_search = ( in_array('hide_search', $options ) ) ? true : '';

		$skin = UltimateSocialDeux::opt('us_posts_bottom_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_posts_bottom_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_posts_bottom_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_posts_bottom_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_posts_bottom_margin_bottom', '0'));

		$custom_content = '';

		if( !( ( $hide_blogpage && is_home() ) || ( $hide_search && is_search() ) || ( $hide_archive && is_archive() ) ) && !in_array($post->ID, $exclude, false) && get_post_type( $post->ID ) == 'post' && ( $networks ) ) {

			$custom_content .= $content;

			$custom_content .= sprintf('<div class="us_posts_bottom" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			$content = $custom_content;

		}

		return $content;
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_pages_top ($content) {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_pages_top_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_pages_top_options'));

		$sharetext = UltimateSocialDeux::opt('us_pages_top_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;
		$native = ( in_array('native', $options ) ) ? true : false;
		$sticky = ( in_array('sticky', $options ) ) ? true : '';

		$skin = UltimateSocialDeux::opt('us_pages_top_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_pages_top_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_pages_top_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_pages_top_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_pages_top_margin_bottom', '0'));

		$custom_content = '';

		if( get_post_type( $post->ID ) == 'page' && !in_array($post->ID, $exclude, false) && ( $networks ) ) {

			$custom_content .= sprintf('<div class="us_pages_top" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			if ( $sticky ) {

				$sticky_offset = intval(UltimateSocialDeux::opt('us_sticky_offset', 0));
				$sticky_get_width_from = UltimateSocialDeux::opt('us_sticky_get_width_from');
				$custom_content .= '
					<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery(".us_pages_top").sticky({
								topSpacing:'.$sticky_offset.',
								getWidthFrom: "'.$sticky_get_width_from.'"
							});
						});
					</script>
				';
			}

			$custom_content .= $content;

			$content = $custom_content;

		}

		return $content;
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_pages_bottom( $content ) {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_pages_bottom_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_pages_bottom_options'));

		$sharetext = UltimateSocialDeux::opt('us_pages_bottom_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_pages_bottom_skin');

		$url = '';

		$exclude =UltimateSocialDeux::opt('us_pages_bottom_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_pages_bottom_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_pages_bottom_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_pages_bottom_margin_bottom', '0'));

		$custom_content = '';

		if( get_post_type( $post->ID ) == 'page' && !in_array($post->ID, $exclude, false) && ( $networks ) ) {

				$custom_content .= $content;

				$custom_content .= sprintf('<div class="us_pages_bottom" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

					$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

				$custom_content .= '</div>';

				$content = $custom_content;

		}

		return $content;

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_floating() {

		global $wp_query;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_floating_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_floating_options'));

		$hide_frontpage = ( in_array('hide_frontpage', $options) ) ? true : '';

		$hide_blogpage = ( in_array('hide_blogpage', $options ) ) ? true : '';

		$hide_posts = ( in_array('hide_posts', $options ) ) ? true : '';

		$hide_pages = ( in_array('hide_pages', $options ) ) ? true : '';

		$hide_archive = ( in_array('hide_archive', $options ) ) ? true : '';

		$hide_search = ( in_array('hide_search', $options ) ) ? true : '';

		$hide_mobile = ( in_array('hide_mobile', $options ) ) ? true : '';

		$hide_mobile_class = ( $hide_mobile ) ? ' us_mobile_hide' : '';

		$hide_desktop = ( in_array('hide_desktop', $options ) ) ? true : '';

		$hide_desktop_class = ( $hide_desktop ) ? ' us_desktop_hide' : '';

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_floating_skin');

		$url = UltimateSocialDeux::opt('us_floating_url');

		$exclude = UltimateSocialDeux::opt('us_floating_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$floating = '';

		$post_id = ( isset( $wp_query->post->ID ) ) ? $wp_query->post->ID : 0;

		$post_type = get_post_type( $post_id );

		if ( !( ( $hide_frontpage && is_front_page() ) || ( $hide_posts && $post_type == 'post' && !is_home() ) || ( $hide_blogpage && is_home() ) || ( $hide_pages && $post_type == 'page' ) || ( $hide_mobile && wp_is_mobile() ) || ( $hide_search && is_search() ) || ( $hide_archive && is_archive() ) || is_404() ) && !in_array($post_id, $exclude, false) && ( $networks ) ) {

			$floating .= sprintf('<div class="us_floating%s%s">', $hide_mobile_class, $hide_desktop_class);

				$floating .= UltimateSocialDeux::buttons('', $networks, $url, '', $count, $native, $skin);

			$floating .= '</div>';
		}

		echo $floating;

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function woocommerce_top () {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_woocommerce_top_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_woocommerce_top_options'));

		$sharetext = UltimateSocialDeux::opt('us_woocommerce_top_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_woocommerce_top_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_woocommerce_top_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_woocommerce_top_align','center');

		$margin_top = intval(UltimateSocialDeux::opt('us_woocommerce_top_margin_top', '0'));

		$margin_bottom =intval(UltimateSocialDeux::opt('us_woocommerce_top_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && ( $networks ) ) {

			$custom_content .= sprintf('<div class="us_woocommerce_top" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;

		}

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function woocommerce_bottom () {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_woocommerce_bottom_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_woocommerce_bottom_options'));

		$sharetext = UltimateSocialDeux::opt('us_woocommerce_bottom_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_woocommerce_bottom_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_woocommerce_bottom_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_woocommerce_bottom_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_woocommerce_bottom_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_woocommerce_bottom_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && ( $networks ) ) {

			$custom_content .= sprintf('<div class="us_woocommerce_bottom" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;

		}

	}

		/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function jigoshop_top () {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_jigoshop_top_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_jigoshop_top_options'));

		$sharetext = UltimateSocialDeux::opt('us_jigoshop_top_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_jigoshop_top_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_jigoshop_top_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_jigoshop_top_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_jigoshop_top_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_jigoshop_top_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && ( $networks ) ) {

			$custom_content .= sprintf('<div class="us_jigoshop_top" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;

		}

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function jigoshop_bottom () {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_jigoshop_bottom_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_jigoshop_bottom_options'));

		$sharetext = UltimateSocialDeux::opt('us_jigoshop_bottom_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_jigoshop_bottom_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_jigoshop_bottom_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_jigoshop_bottom_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_jigoshop_bottom_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_jigoshop_bottom_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && ( $networks ) ) {

			$custom_content .= sprintf('<div class="us_jigoshop_bottom" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;
		}
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function exchange_top () {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_exchange_top_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_exchange_top_options'));

		$sharetext = UltimateSocialDeux::opt('us_exchange_top_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_exchange_top_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_exchange_top_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_exchange_top_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_exchange_top_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_exchange_top_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && ( $networks ) ) {

			$custom_content .= sprintf('<div class="us_exchange_top" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;

		}

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function exchange_bottom () {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_exchange_bottom_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_exchange_bottom_options'));

		$sharetext = UltimateSocialDeux::opt('us_exchange_bottom_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_exchange_bottom_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_exchange_bottom_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_exchange_bottom_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_exchange_bottom_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_exchange_bottom_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && ( $networks ) ) {

			$custom_content .= sprintf('<div class="us_exchange_bottom" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;
		}
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_edd_top ($content) {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_edd_top_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_edd_top_options'));

		$sharetext = UltimateSocialDeux::opt('us_edd_top_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_edd_top_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_edd_top_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_edd_top_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_edd_top_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_edd_top_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && get_post_type( $post->ID ) == 'download' && ( $networks ) ) {

				$custom_content .= sprintf('<div class="us_edd_top" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

					$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

				$custom_content .= '</div>';

				$custom_content .= $content;

		}

		return $content;

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_edd_bottom( $content ) {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_edd_bottom_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_edd_bottom_options'));

		$sharetext = UltimateSocialDeux::opt('us_edd_bottom_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_edd_bottom_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_edd_bottom_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_edd_bottom_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_edd_bottom_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_edd_bottom_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && get_post_type( $post->ID ) == 'download' && ( $networks ) ) {

			$custom_content .= $content;

			$custom_content .= sprintf('<div class="us_edd_bottom" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			$content = $custom_content;

		}

		return $content;

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_excerpts_top ($content) {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_excerpts_top_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_excerpts_top_options'));

		$sharetext = UltimateSocialDeux::opt('us_excerpts_top_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_excerpts_top_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_excerpts_top_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_excerpts_top_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_excerpts_top_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_excerpts_top_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && get_post_type( $post->ID ) == 'post' &&  is_array($networks) && !empty($networks) ) {

			$custom_content .= sprintf('<div class="us_excerpts_top" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';



		}

		$custom_content .= $content;

		return $custom_content;

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_excerpts_bottom( $content ) {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_excerpts_bottom'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_excerpts_bottom_options'));

		$sharetext = UltimateSocialDeux::opt('us_excerpts_bottom_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_excerpts_bottom_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_excerpts_bottom_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$align = UltimateSocialDeux::opt('us_excerpts_bottom_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_excerpts_bottom_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_excerpts_bottom_margin_bottom', '0'));

		$custom_content = '';

		$custom_content .= $content;

		if( !in_array($post->ID, $exclude, false) && get_post_type( $post->ID ) == 'post' && is_array($networks) && !empty($networks) ) {

			$custom_content .= sprintf('<div class="us_excerpts_bottom" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

		}

		return $custom_content;
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_cpt_top ($content) {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_cpt_top_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_cpt_top_options'));

		$sharetext = UltimateSocialDeux::opt('us_cpt_top_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_cpt_top_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_cpt_top_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$cpt = UltimateSocialDeux::opt('us_cpt_top_cpt');

		$cpt = explode(',', $cpt);

		$cpt = array_map('trim', $cpt);

		$align = UltimateSocialDeux::opt('us_cpt_top_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_cpt_top_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_cpt_top_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && in_array(get_post_type( $post->ID ), $cpt, false) && ( $networks ) ) {

			$custom_content .= sprintf('<div class="us_cpt_top" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			$custom_content .= $content;

			$content = $custom_content;

		}

		return $content;

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	1.0.0
	 *
	 * @return	buttons
	 */
	public function buttons_cpt_bottom( $content ) {

		global $post;

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_cpt_bottom_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_cpt_bottom_options'));

		$sharetext = UltimateSocialDeux::opt('us_cpt_bottom_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$skin = UltimateSocialDeux::opt('us_cpt_bottom_skin');

		$url = '';

		$exclude = UltimateSocialDeux::opt('us_cpt_bottom_exclude');

		$exclude = explode(',', $exclude);

		$exclude = array_map('trim', $exclude);

		$cpt = UltimateSocialDeux::opt('us_cpt_bottom_cpt');

		$cpt = explode(',', $cpt);

		$cpt = array_map('trim', $cpt);

		$align = UltimateSocialDeux::opt('us_cpt_bottom_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_cpt_bottom_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_cpt_bottom_margin_bottom', '0'));

		$custom_content = '';

		if( !in_array($post->ID, $exclude, false) && in_array(get_post_type( $post->ID ), $cpt, false) && ( $networks ) ) {

			$custom_content .= $content;

			$custom_content .= sprintf('<div class="us_cpt_bottom" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			$content = $custom_content;

		}

		return $content;

	}

	/**
	 * Returns social buttons.
	 *
	 * @since	4.0.0
	 *
	 * @return	buttons
	 */
	public function buddypress_group() {

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_buddypress_group_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_buddypress_group_options'));

		$sharetext = UltimateSocialDeux::opt('us_buddypress_group_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$url = '';

		$align = UltimateSocialDeux::opt('us_buddypress_group_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_buddypress_group_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_buddypress_group_margin_bottom', '0'));

		$skin = UltimateSocialDeux::opt('us_buddypress_group_skin');

		$custom_content = '';

		if( $networks ) {

			$custom_content .= sprintf('<div class="us_buddypress_group" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;
		}
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	4.0.0
	 *
	 * @return	buttons
	 */
	public function buddypress_activity() {

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_buddypress_activity_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_buddypress_activity_options'));

		$sharetext = UltimateSocialDeux::opt('us_buddypress_activity_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$url = '';

		$align = UltimateSocialDeux::opt('us_buddypress_activity_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_buddypress_activity_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_buddypress_activity_margin_bottom', '0'));

		$skin = UltimateSocialDeux::opt('us_buddypress_activity_skin');

		$custom_content = '';

		if( $networks ) {

			$custom_content .= sprintf('<div class="us_buddypress_activity" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;
		}
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	4.0.0
	 *
	 * @return	buttons
	 */
	public function bbpress_before_replies() {

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_bbpress_before_replies_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_bbpress_before_replies_options'));

		$sharetext = UltimateSocialDeux::opt('us_bbpress_before_replies_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$url = '';

		$align = UltimateSocialDeux::opt('us_bbpress_before_replies_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_bbpress_before_replies_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_bbpress_before_replies_margin_bottom', '0'));

		$skin = UltimateSocialDeux::opt('us_bbpress_before_replies_skin');

		$custom_content = '';

		if( $networks ) {

			$custom_content .= sprintf('<div class="us_bbpress_before_replies" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;
		}
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	4.0.0
	 *
	 * @return	buttons
	 */
	public function bbpress_after_replies() {

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_bbpress_after_replies_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_bbpress_after_replies_options'));

		$sharetext = UltimateSocialDeux::opt('us_bbpress_after_replies_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$url = '';

		$align = UltimateSocialDeux::opt('us_bbpress_after_replies_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_bbpress_after_replies_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_bbpress_after_replies_margin_bottom', '0'));

		$skin = UltimateSocialDeux::opt('us_bbpress_after_replies_skin');

		$custom_content = '';

		if( $networks ) {

			$custom_content .= sprintf('<div class="us_bbpress_after_replies" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;
		}
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	4.0.0
	 *
	 * @return	buttons
	 */
	public function bbpress_before_topics() {

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_bbpress_before_topics_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_bbpress_before_topics_options'));

		$sharetext = UltimateSocialDeux::opt('us_bbpress_before_topics_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$url = '';

		$align = UltimateSocialDeux::opt('us_bbpress_before_topics_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_bbpress_before_topics_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_bbpress_before_topics_margin_bottom', '0'));

		$skin = UltimateSocialDeux::opt('us_bbpress_before_topics_skin');

		$custom_content = '';

		if( $networks ) {

			$custom_content .= sprintf('<div class="us_bbpress_before_topics" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;
		}
	}

	/**
	 * Returns social buttons.
	 *
	 * @since	4.0.0
	 *
	 * @return	buttons
	 */
	public function bbpress_after_topics() {

		$networks = maybe_unserialize(UltimateSocialDeux::opt('us_bbpress_after_topics_buttons'));

		$options = maybe_unserialize(UltimateSocialDeux::opt('us_bbpress_after_topics_options'));

		$sharetext = UltimateSocialDeux::opt('us_bbpress_after_topics_share_text');

		$count = ( in_array('hide_count', $options ) ) ? false : true;

		$native = ( in_array('native', $options ) ) ? true : false;

		$url = '';

		$align = UltimateSocialDeux::opt('us_bbpress_after_topics_align', 'center');

		$margin_top = intval(UltimateSocialDeux::opt('us_bbpress_after_topics_margin_top', '0'));

		$margin_bottom = intval(UltimateSocialDeux::opt('us_bbpress_after_topics_margin_bottom', '0'));

		$skin = UltimateSocialDeux::opt('us_bbpress_after_topics_skin');

		$custom_content = '';

		if( $networks ) {

			$custom_content .= sprintf('<div class="us_bbpress_after_topics" style="margin-top:%spx;margin-bottom:%spx;">', $margin_top, $margin_bottom);

				$custom_content .= UltimateSocialDeux::buttons($sharetext, $networks, $url, $align, $count, $native, $skin);

			$custom_content .= '</div>';

			echo $custom_content;
		}
	}

}