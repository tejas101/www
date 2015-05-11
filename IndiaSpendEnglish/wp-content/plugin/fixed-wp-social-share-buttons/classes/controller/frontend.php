<?php
/**
 * The Frontend Controller Class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend Controller
 *
 * @since  1.0.0
 * @access public
 *
 */
class WPB_Fixed_Social_Share_Controller_Frontend {

	/**
	 * Short description.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @var null ${DS}plugin_file Description.
	 */
	public $plugin_file = null;


	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param null $plugin_file
	 *
	 * @return \WPB_Fixed_Social_Share_Controller_Frontend
	 */
	public function __construct( $plugin_file = null ) {
		$this->plugin_file = $plugin_file;

		add_filter( 'option_wpbfsb_full-width-layout_position', array(
			$this,
			'change_full_width_position_option_to_default',
		) );
		add_filter( 'option_wpbfsb_responsive-width-layout_position', array(
			$this,
			'change_responsive_width_position_option_to_default',
		) );

		add_filter( 'option_wpbfsb_full-width-layout_position', array( $this, 'get_option_change_position_filter' ) );
		add_filter( 'option_wpbfsb_responsive-width-layout_position', array(
			$this,
			'get_option_change_position_filter',
		) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'the_content', array( $this, 'the_content' ) );

		add_action( 'wp_footer', array( $this, 'wp_footer' ) );

		#add_action( 'wp_head', array( $this, 'custom_styles' ) );

		add_shortcode( 'fixed_social_share', array( $this, 'shortcode' ) );

		add_filter( 'widget_text', 'do_shortcode' );
	}


	/**
	 * Enqueues scripts.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! $this->buttons_active() ) {
			return;
		}

		$icon_font_css_files = apply_filters(
			'wpbfsb_icon_files',
			array(
				'wpbfsb-frontend-icons' => apply_filters( 'wpbfsb_icon_font_url', plugins_url( 'assets/css/wpbfsb-frontend-icons.css', $this->plugin_file ) )
			)
		);

		foreach ( $icon_font_css_files as $icon_file_handle => $icon_font_css_file_url ) {
			wp_enqueue_style( $icon_file_handle, $icon_font_css_file_url );
		}

		wp_enqueue_style( 'wpbfsb-frontend', plugins_url( 'assets/css/wpbfsb-frontend.css', $this->plugin_file ), array_keys( $icon_font_css_files ) );

		$this->custom_styles();

	}


	/**
	 * Add buttons to the footer of each page.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 *
	 */
	public function wp_footer() {

		if ( get_option( 'wpbfsb_full-width-layout_position', 'vertical-right' ) == 'after-content' OR
		     get_option( 'wpbfsb_responsive-width-layout_position', 'horizontal-bottom-right' ) == 'after-content'
		) {
			return;
		}

		if ( ! $this->buttons_active() ) {
			return;
		}

		$this->render_frontend_buttons();
	}


	/**
	 * Renders the frontend.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public static function render_frontend_buttons() {
		$view = new WPB_Fixed_Social_Share_View( 'frontend' );

		$view->site_url       = WPB_Fixed_Social_Share_Model_Url::guess_url();
		$view->site_title     = WPB_Fixed_Social_Share_Model_Title::get_site_title();
		$view->featured_image = WPB_Fixed_Social_Share_Model_Image::get_site_thumbnail();

		$view->display_shares            = (bool) get_option( 'wpbfsb_full-width-layout_display-shares', true );
		$view->display_shares_responsive = (bool) get_option( 'wpbfsb_responsive-width-layout_display-shares', false );

		$view->icon_quantity            = intval( get_option( 'wpbfsb_full-width-layout_icon-quantity', 5 ) );
		$view->icon_quantity_responsive = intval( get_option( 'wpbfsb_responsive-width-layout_icon-quantity', 5 ) );

		$view->buttons_available = wp_count_posts( 'wpbfsb' )->publish;

		$view->buttons = apply_filters( 'wpbfsb_frontend_buttons', get_posts( array(
			'numberposts' => $view->buttons_available,
			'post_type'   => 'wpbfsb',
			'orderby'     => 'menu_order',
			'sort_column' => 'menu_order',
			'sort_order'  => 'asc',
			'order'       => 'asc',
		) ) );

		$view->position            = get_option( 'wpbfsb_full-width-layout_position', 'vertical-right' );
		$view->position_responsive = get_option( 'wpbfsb_responsive-width-layout_position', 'horizontal-bottom-right' );

		$view->is_schema_org = (bool) get_option( 'wpbfsb_schema_active', false );

		$view->responsive_width = intval( get_option( 'wpbfsb_responsive-width-layout_responsive-width', 968 ) );

		// Share Info
		$view->is_share_info              = get_option( 'wpbfsb_other_share-info', 0 );
		$view->share_info_text            = get_option( 'wpbfsb_full-width-layout_share-info-text', __( 'Share this!', 'wbpfsb' ) );
		$view->share_info_text_responsive = get_option( 'wpbfsb_responsive-width-layout_share-info-text', __( 'Share!', 'wbpfsb' ) );

		if ( empty( $view->share_info_text ) || empty( $view->share_info_text_responsive ) ) {
			$view->is_share_info = 0;
		}

		$view->render();
	}


	/**
	 * Adds custom styles
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function custom_styles() {
		if ( ! $this->buttons_active() ) {
			return;
		}

		$buttons = apply_filters( 'wpbfsb_frontend_buttons', get_posts( array(
			'numberposts' => - 1,
			'post_type'   => 'wpbfsb',
		) ) );

		$buttons_available = wp_count_posts( 'wpbfsb' )->publish;

		ob_start();

		#echo '<style type="text/css">/*<![CDATA[*/';

		/**
		 * Style each button separately
		 */
		foreach ( $buttons as $button ) {
			$background_color = get_post_meta( $button->ID, '_wpbfsb_button_bg-color', true );
			if ( empty( $background_color ) ) {
				continue;
			}

			$key = sanitize_key( $button->post_title );

			echo '.wpbfsb .wpbfsb-button-named-' . $key . ' {'
			     . 'background-color:' . esc_attr( $background_color ) . ';'
			     . ' color:' . esc_attr( get_post_meta( $button->ID, '_wpbfsb_button_icon-color', true ) ) . ';'
			     . '}';

			$background_color_hover = get_post_meta( $button->ID, '_wpbfsb_button_bg-color-hover', true );

			if ( empty( $background_color_hover ) ) {

				$background_color_dec = hexdec( str_replace( '#', '', $background_color ) ) - 1118481;

				if ( $background_color_dec < 0 ) {
					$background_color_dec = $background_color_dec + 1118481;
				}

				$background_color_hover = '#' . dechex( $background_color_dec );

			}

			echo '.wpbfsb .wpbfsb-button-named-' . $key . ':hover {'
			     . 'background-color:' . $background_color_hover . ';'
			     . '}';

			// custom CSS
			echo get_post_meta( $button->ID, '_wpbfsb_button_style', true );
		}

		/**
		 * Style Icon quantity + icon quantity when responsive
		 */
		$icon_quantity_responsive = intval( get_option( 'wpbfsb_responsive-width-layout_icon-quantity', 5 ) );
		$icon_quantity            = intval( get_option( 'wpbfsb_full-width-layout_icon-quantity', 5 ) );

		// hide buttons >= $icon_quantity +1
		$is_share_info = (bool) get_option( 'wpbfsb_other_share-info', 0 );
		echo '.wpbfsb button:nth-child(n+' . ( $icon_quantity + 1 + ( intval( $is_share_info ) ) ) . ') { display: none; }';

		// but show "more" button
		echo '.wpbfsb button.wpbfsb-more-button {display: inline-block;}';

		$button_size = intval( get_option( 'wpbfsb_full-width-layout_button-size', 60 ) );

		echo '.wpbfsb {';

		/**
		 * Position
		 */
		$original_position = $position = get_option( 'wpbfsb_full-width-layout_position', 'vertical-right' );

		if ( 'after-content' == $original_position ) {
			$position = 'horizontal-bottom';
		}

		$this->position_css( $position );

		/**
		 * Width
		 */
		if ( $icon_quantity != 0 ) {
			if ( false !== stripos( $position, 'horizontal' ) ) {
				echo 'max-height: ' . ceil( $buttons_available / $icon_quantity ) * ( $button_size + 10 ) . 'px;';
				echo 'width: 100%;';
			} else {
				echo 'max-width: ' . ceil( $buttons_available / $icon_quantity ) * ( $button_size + 10 ) . 'px;';
				echo 'width: auto;';
			}
		}

		echo '}'; // .wpbfsb

		/**
		 * Font Size
		 */
		echo '.wpbfsb i { font-size: ' . intval( get_option( 'wpbfsb_full-width-layout_icon-size', 30 ) ) . 'px; }';

		/**
		 * Button Size and margin
		 */
		echo '.wpbfsb button {';

		$this->margin_css( $position );

		echo 'height:' . $button_size . 'px;width:' . $button_size . 'px;';

		if ( false !== stripos( $position, 'horizontal' ) ) {
			echo 'display: inline-block;';
		} else {
			echo 'display: block;';
		}

		echo '}'; // .wpbfsb button

		/**
		 * Counter
		 */
		echo '.wpbfsb-count { ';

		if ( ! (bool) get_option( 'wpbfsb_full-width-layout_display-shares', false ) ) {
			echo '.wpbfsb-count { display: none; }';
		}

		echo 'font-size:' . intval( get_option( 'wpbfsb_full-width-layout_shares-font-size', 10 ) ) . 'px;';

		echo '}';

		$is_more_button_visible = ! ( wp_count_posts( 'wpbfsb' )->publish <= $icon_quantity );

		if ( ! $is_more_button_visible ) {
			echo '.wpbfsb button.wpbfsb-more-button {display: none;}';
		}

		// Style Share Text
		$share_text_font_size = get_option( 'wpbfsb_full-width-layout_share-text-font-size', 14 );

		echo '.wpbfsb button.wpbfsb-share-info {';

		echo 'font-size: ' . $share_text_font_size . 'px;';

		$share_text_color = get_option( 'wpbfsb_full-width-layout_share-text-font-color', '' );
		if ( ! empty( $share_text_color ) ) {
			echo 'color: ' . $share_text_color . ';';
		}
		echo '}';

		echo '.wpbfsb-share-info-responsive-inner { display: none; }';
		echo '.wpbfsb-share-info-inner { display: inline; }';

		/**
		 * Tooltip text
		 */
		echo '.wpbfsb button.wpbfsb-tooltips span.wpbfsb-tooltip {';
		echo 'font-size: ' . $share_text_font_size . 'px;';
		echo 'color: ' . $share_text_color . ';';
		echo '}';

		/**
		 * Tooltip position
		 */
		$this->tooltip_position_css( $position, $button_size );

		/**
		 *
		 * RESPONSIVENESS 1: if 'in content'
		 *
		 */
		echo '@media all and (min-width: ' . intval( get_option( 'wpbfsb_responsive-width-layout_responsive-width', 968 ) ) . 'px) {';
		if ( 'after-content' == $original_position ) {
			echo '.wpbfsb.wpbfsb-position-after-content {
				width: 100%;
				left: 0;
				top: 0;
				position: relative;
			}

			.wpbfsb-position-after-content button {
				float: left;
				margin: 0 5px 5px 0;
			}';
		}

		echo '}'; // end responsiveness 1


		/**
		 *
		 * RESPONSIVENESS 2: if not 'in content'
		 *
		 */
		echo '@media all and (max-width: ' . absint( get_option( 'wpbfsb_responsive-width-layout_responsive-width', 968 ) ) . 'px) {';

		$button_size_responsive = intval( get_option( 'wpbfsb_responsive-width-layout_button-size', 30 ) );

		$is_more_button_visible_responsive = ! ( wp_count_posts( 'wpbfsb' )->publish <= $icon_quantity_responsive );
		if ( ! $is_more_button_visible_responsive ) {
			echo 'wpbfsb button.wpbfsb-more-button {display: none;}';
		} else {
			echo 'wpbfsb button.wpbfsb-more-button {display: inline-block;}';
		}

		//Responsive Position
		$original_position_responsive = $position_responsive = get_option( 'wpbfsb_responsive-width-layout_position', 'horizontal-bottom-right' );
		if ( 'after-content' == $position_responsive ) {
			$position_responsive = 'horizontal-bottom';
		}

		/**
		 * Responsive icon quantity
		 */
		// first: show all
		echo '.wpbfsb button:nth-child(n+' . ( $icon_quantity + 1 + ( intval( $is_share_info ) ) ) . ') { ';

		if ( false !== stripos( $position_responsive, 'horizontal' ) ) {
			echo 'display: inline-block';
		} else {
			echo 'display: block;';
		}

		echo '}';

		// then hide the icons
		echo '.wpbfsb button:nth-child(n+' . ( $icon_quantity_responsive + 1 + ( intval( $is_share_info ) ) ) . ') { display: none; }';

		// but keep "more" button
		echo '.wpbfsb button.wpbfsb-more-button {';
		if ( false !== stripos( $position_responsive, 'horizontal' ) ) {
			echo 'display: inline-block';
		} else {
			echo 'display: block;';
		}
		echo '}';

		echo '.wpbfsb {';

		/**
		 * Width
		 */
		if ( $icon_quantity_responsive != 0 ) {
			if ( $icon_quantity_responsive != 0 && false !== stripos( $position_responsive, 'horizontal' ) ) {
				echo 'max-height: ' . ceil( $buttons_available / $icon_quantity_responsive ) * ( $button_size_responsive + 10 ) . 'px;';
				echo 'width: 100%;';
			} else {
				echo 'max-width: ' . ceil( $buttons_available / $icon_quantity_responsive ) * ( $button_size_responsive + 10 ) . 'px;';
				echo 'width: auto;';
			}
		}

		$this->position_css( $position_responsive, true );

		echo '}';

		/**
		 * Responsive Font Size
		 */
		echo '.wpbfsb i { font-size: ' . intval( get_option( 'wpbfsb_responsive-width-layout_icon-size', 20 ) ) . 'px; }';


		/**
		 * Responsive Button Size and margin
		 */
		echo '.wpbfsb button {';

		$this->margin_css( $position_responsive );

		echo 'height:' . $button_size_responsive . 'px;width:' . $button_size_responsive . 'px;';

		if ( false !== stripos( $position_responsive, 'horizontal' ) ) {
			echo 'display: inline-block;';
		} else {
			echo 'display: block;';
		}

		echo '}'; //end button

		// counter
		echo '.wpbfsb-count { ';

		if ( ! (bool) get_option( 'wpbfsb_responsive-width-layout_display-shares', false ) ) {
			echo 'display: none;';
		}

		echo 'font-size:' . intval( get_option( 'wpbfsb_responsive-width-layout_shares-font-size', 8 ) ) . 'px;';

		echo '}';

		if ( 'after-content' != $original_position_responsive ) {
			//$this->print_css_animation( $position_responsive, $button_size_responsive, $icon_quantity_responsive, $is_more_button_visible_responsive, true );
		}

		if ( 'after-content' == $original_position_responsive ) {
			echo '.wpbfsb.wpbfsb-position-responsive-after-content {
				width: 100%;
				left: 0;
				top: 0;
				position: relative;
			}

			.wpbfsb-position-responsive-after-content button {
				float: left;
				margin: 0 5px 5px 0;
			}';
		}

		/**
		 * Style Share Text
		 */
		$share_text_font_size = get_option( 'wpbfsb_responsive-width-layout_share-text-font-size', 10 );
		echo '.wpbfsb button.wpbfsb-share-info {';
		echo 'font-size: ' . $share_text_font_size . 'px;';
		echo 'min-height:' . $button_size_responsive . 'px;min-width:' . $button_size_responsive . 'px;';

		$share_text_color = get_option( 'wpbfsb_responsive-width-layout_share-text-font-color', '' );
		if ( ! empty( $share_text_color ) ) {
			echo 'color: ' . $share_text_color . ';';
		}
		echo '}'; // wpbfsb-share-info

		echo '.wpbfsb-share-info-responsive-inner { display: inline; }';
		echo '.wpbfsb-share-info-inner { display: none; }';

		/**
		 * Tooltip Text
		 */
		echo '.wpbfsb button.wpbfsb-tooltips span.wpbfsb-tooltip {';
		echo 'font-size: ' . $share_text_font_size . 'px;';
		echo 'color: ' . $share_text_color . ';';
		echo '}';

		/**
		 * Tooltip position
		 */
		$this->tooltip_position_css( $position_responsive, $button_size_responsive, true );

		echo '}'; // end responsiveness

		echo get_option( 'wpbfsb_other_custom-css', '' );

		#echo '/*]]>*/</style>';

		wp_add_inline_style( 'wpbfsb-frontend', ob_get_clean() );
	}


	/**
	 * Returns the CSS styles for the position
	 *
	 * @param string $position
	 * @param bool   $is_reponsive
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	private function get_position_css( $position = '', $is_reponsive = false ) {

		$content = '';

		$option_name = 'wpbfsb';
		if ( $is_reponsive ) {
			$option_name .= '_responsive-width-layout_';
		} else {
			$option_name .= '_full-width-layout_';
		}

		switch ( $position ) {
			case 'horizontal-top-left':
				$content .= 'left: 0; top: 0; right: auto; bottom: auto; text-align: left;';
				break;
			case 'horizontal-top':
				$content .= 'left: auto; top: 0; right: auto; bottom: auto; text-align: center; max-width: 100%;';
				break;
			case 'horizontal-top-right':
				$content .= 'left: auto; top: 0; right: 0; bottom: auto; text-align: right;';
				break;
			case 'vertical-right-top':
				$content .= 'left: auto; top: 0; right: 0; bottom: auto; text-align: right;';
				break;
			case 'vertical-right':
				$content .= 'left: auto; top: ' . absint( get_option( $option_name . 'from-top', 20 ) ) . '%; right: 0; bottom: auto; text-align: right;';
				break;
			case 'vertical-right-bottom':
				$content .= 'left: auto; top: auto; right: 0; bottom: 0; text-align: right;';
				$content .= 'margin: 5px 0 0 5px;';
				break;
			case 'horizontal-bottom-right':
				$content .= 'left: auto; top: auto; right: 0; bottom: 0; text-align: right;';
				break;
			case 'horizontal-bottom':
				$content .= 'left: auto; top: auto; right: auto; bottom: 0; text-align: center;  max-width: 100%;';
				break;
			case 'horizontal-bottom-left':
				$content .= 'left: 0; top: auto; right: auto; bottom: 0; text-align: left;';
				break;
			case 'vertical-left-bottom':
				$content .= 'left: 0; top: auto; right: auto; bottom: 0; text-align: left;';
				break;
			case 'vertical-left':
				$content .= 'left: 0; top: ' . absint( get_option( $option_name . 'from-top', 20 ) ) . '%; right: auto; bottom: auto; text-align: left;';
				break;
			case 'vertical-left-top':
				$content .= 'left: 0; top: 0; right: auto; bottom: auto; text-align: left;';
				break;
		}

		return $content;
	}


	/**
	 * Returns the CSS styles for the button margins
	 *
	 * @param string $position
	 * @param bool   $is_reponsive
	 *
	 * @since  1.4.0
	 * @access public
	 *
	 * @return void
	 */
	public function margin_css( $position = '', $is_reponsive = false ) {
		echo $this->get_margin_css( $position, $is_reponsive );
	}


	/**
	 * Returns the CSS styles for the button margins
	 *
	 * @param string $position
	 * @param bool   $is_reponsive
	 *
	 * @since  1.4.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_margin_css( $position = '', $is_reponsive = false ) {
		$content = '';

		switch ( $position ) {
			case 'horizontal-top-left':
				$content .= 'margin: 0 5px 5px 0;';
				break;
			case 'horizontal-top':
				$content .= 'margin: 0 5px 5px 0;';
				break;
			case 'horizontal-top-right':
				$content .= 'margin: 0 0 5px 5px;';
				break;
			case 'vertical-right-top':
				$content .= 'margin: 0 0 5px 5px;';
				break;
			case 'vertical-right':
				$content .= 'margin: 0 0 5px 5px;';
				break;
			case 'vertical-right-bottom':
				$content .= 'margin: 5px 0 0 5px;';
				break;
			case 'horizontal-bottom-right':
				$content .= 'margin: 0 0 5px 5px;';
				break;
			case 'horizontal-bottom':
				$content .= 'margin: 0 0 5px 5px;';
				break;
			case 'horizontal-bottom-left':
				$content .= 'margin: 5px 5px 0 0;';
				break;
			case 'vertical-left-bottom':
				$content .= 'margin: 5px 5px 0 0;';
				break;
			case 'vertical-left':
				$content .= 'margin: 5px 5px 0 0;';
				break;
			case 'vertical-left-top':
				$content .= 'margin: 0 5px 5px 0;';
				break;
		}

		return $content;
	}


	/**
	 * Echos out the CSS styles for the position
	 *
	 * @param string $position
	 * @param bool   $is_reponsive
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function position_css( $position = '', $is_reponsive = false ) {
		echo $this->get_position_css( $position, $is_reponsive );
	}


	/**
	 * Returns the opposite of a position
	 *
	 * @param string $position
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string
	 */
	private function position_css_opposite( $position ) {
		if ( 'left' == $position ) {
			return 'right';
		}
		if ( 'right' == $position ) {
			return 'left';
		}
		if ( 'bottom' == $position ) {
			return 'top';
		}
		if ( 'top' == $position ) {
			return 'bottom';
		}

		return '';
	}


	/**
	 * Prints the css animations
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param      $position
	 * @param      $button_size
	 * @param      $icon_quantity
	 * @param      $more_button_visible
	 * @param bool $is_responsive
	 *
	 * @return void
	 */
	private function print_css_animation( $position, $button_size, $icon_quantity, $more_button_visible, $is_responsive = false ) {

		$is_horziontal = false !== stripos( $position, 'horizontal' ) ? true : false;

		$responsivness     = $is_responsive ? 'responsive-' : '';
		$responsiveness_id = 'responsive_';

		if ( $more_button_visible ) {
			$icon_quantity ++;
		}

		// overgo devision by zero
		if ( $icon_quantity <= 0 ) {
			$icon_quantity = 1;
		}

		if ( ! $is_horziontal ) {

			$animate_in_from_width  = $button_size;
			$animate_in_from_height = ( $button_size + 5 ) * ( $icon_quantity );
			$animate_in_to_width    = ( $button_size + 5 ) * ceil( wp_count_posts( 'wpbfsb' )->publish / ( $icon_quantity ) );
			$animate_in_to_height   = ( $button_size + 5 ) * 2;

			$animate_out_to_width  = $animate_in_from_width;
			$animate_out_to_height = $animate_in_from_height;

			echo '.wpbfsb button {
				float: left;
				margin-right: 5px;
			}';

			echo '.wpbfsb.wpbfsb-animate-in button {
				margin-right: 5px;
				display: block!important;
			}';
		} else {
			$animate_in_from_width  = ( $button_size + 5 ) * ( $icon_quantity );
			$animate_in_from_height = $button_size;
			$animate_in_to_width    = $animate_in_from_width;
			$animate_in_to_height   = ( $button_size + 5 ) * ceil( wp_count_posts( 'wpbfsb' )->publish / ( $icon_quantity ) );

			$animate_out_to_width  = $animate_in_from_width;
			$animate_out_to_height = $animate_in_from_height;

			echo '.wpbfsb.wpbfsb-animate-in button {
				margin-bottom: 5px;
				display: block!important;
			}';
		}

		echo '.wpbfsb { width: ' . $animate_in_from_width . 'px; }';

		echo '.wpbfsb-' . $responsivness . 'animate-in {
				animation: wpbfsb_' . $responsiveness_id . 'animate_in .5s ease-out 0 1 alternate;
				-webkit-animation: wpbfsb_' . $responsiveness_id . 'animate_in .5s ease-out 0 1 alternate;
				width: ' . $animate_in_to_width . 'px;
				height: ' . $animate_in_to_height . 'px;
			}';

		foreach ( array( 'keyframes', '-webkit-keyframes' ) as $keyframes ) {
			echo '@' . $keyframes . ' wpbfsb_' . $responsiveness_id . 'animate_in {
				from {
					width: ' . $animate_in_from_width . 'px;
					height: ' . $animate_in_from_height . 'px;
				}
				to {
					width: ' . $animate_in_to_width . 'px;
					height: ' . $animate_in_to_height . 'px;
				}
			}';
		}

		echo '.wpbfsb-' . $responsivness . 'animate-out {
			animation: wpbfsb_' . $responsiveness_id . 'animate_out .5s ease-out 0 1 alternate;
			-webkit-animation: wpbfsb_' . $responsiveness_id . 'animate_out .5s ease-out 0 1 alternate;
			width: ' . $animate_out_to_width . 'px;
			height: ' . $animate_out_to_height . 'px;
		}';

		foreach ( array( 'keyframes', '-webkit-keyframes' ) as $keyframes ) {
			echo '@' . $keyframes . ' wpbfsb_' . $responsiveness_id . 'animate_out {
				from {
					width: ' . $animate_in_to_width . 'px;
					height: ' . $animate_in_to_height . 'px;
				}
				to {
					width: ' . $animate_out_to_width . 'px;
					height: ' . $animate_out_to_height . 'px;
				}
			}';
		}
	}


	/**
	 * Checks if the buttons are active on the current page
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return bool
	 */
	public static function buttons_active() {
		// this slows down some themes
		//wp_reset_query();

		$buttons_active_filter = apply_filters( 'wpbfsb_buttons_active', null );

		if ( is_bool( $buttons_active_filter ) ) {
			return $buttons_active_filter;
		}

		if ( is_home() && ! (bool) get_option( 'wpbfsb_visibility_homepage', true ) ) {
			return false;
		}

		if ( is_front_page() && ! (bool) get_option( 'wpbfsb_visibility_frontpage', true ) ) {
			return false;
		}

		if ( is_category() && ! (bool) get_option( 'wpbfsb_visibility_category', true ) ) {
			return false;
		}

		if ( is_tag() && ! (bool) get_option( 'wpbfsb_visibility_tag', true ) ) {
			return false;
		}

		if ( is_tax() && ! (bool) get_option( 'wpbfsb_visibility_taxonomy', true ) ) {
			return false;
		}

		if ( is_author() && ! (bool) get_option( 'wpbfsb_visibility_author', true ) ) {
			return false;
		}

		if ( is_date() && ! (bool) get_option( 'wpbfsb_visibility_date', true ) ) {
			return false;
		}

		if ( is_search() && ! (bool) get_option( 'wpbfsb_visibility_search', true ) ) {
			return false;
		}

		if ( is_404() && ! (bool) get_option( 'wpbfsb_visibility_404', true ) ) {
			return false;
		}

		global $post;

		if ( isset( $post ) && is_a( $post, 'WP_Post' ) ) {

			# Check for post type settings
			if ( is_singular() && ! (bool) get_option( 'wpbfsb_visibility_' . get_post_type( $post ), true ) ) {
				return false;
			}

			return ! (bool) get_post_meta( $post->ID, '_wpbfsb_button_hide-icons', true );
		}

		return true;
	}


	/**
	 * Adds the rating after the content area
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param string $content
	 *
	 * @return string mixed
	 */
	public function the_content( $content ) {
		if ( ! $this->buttons_active() ) {
			return $content;
		}

		if ( ! ( get_option( 'wpbfsb_full-width-layout_position', 'vertical-right' ) == 'after-content' OR
		         get_option( 'wpbfsb_responsive-width-layout_position', 'horizontal-bottom-right' ) == 'after-content' )
		) {
			return $content;
		}

		if ( isset( $this->_shortcode ) && $this->_shortcode ) {
			return $content;
		}

		ob_start();
		$this->render_frontend_buttons();
		$content .= ob_get_clean();

		return $content;
	}


	/**
	 * Renders the shortcode
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function shortcode( $atts, $content ) {

		if ( ! $this->buttons_active() ) {
			return '';
		}

		$this->_shortcode = true;

		ob_start();
		$this->render_frontend_buttons();

		return ob_get_clean();

	}


	/**
	 * Hooks into the get_option value to change the position
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public function get_option_change_position_filter( $value ) {

		$this->if_shortcode();
		if ( isset( $this->_shortcode ) && $this->_shortcode ) {
			return 'after-content';
		}

		return $value;
	}


	/**
	 * Checks if a shortcode is used in the current page
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @return bool
	 */
	public function if_shortcode() {
		// this slows down some themes
		//wp_reset_query();

		global $post;
		if ( ! isset( $post ) ) {
			return false;
		}

		if ( ! is_a( $post, 'WP_Post' ) ) {
			return false;
		}

		if ( has_shortcode( $post->post_content, 'fixed_social_share' ) ) {
			$this->_shortcode = true;

			return true;
		}

		return false;
	}


	/**
	 * Hooks into the get_option and set the value for the wpbfsb_full-width-layout_position option to its default
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public function change_full_width_position_option_to_default( $value ) {
		if ( empty( $value ) ) {
			return 'vertical-right';
		}

		return $value;
	}


	/**
	 * Hooks into the get_option and set the value for the wpbfsb_responsive-width-layout_position option to its default
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public function change_responsive_width_position_option_to_default( $value ) {
		if ( empty( $value ) ) {
			return 'horizontal-bottom-right';
		}

		return $value;
	}


	/**
	 * Returns the CSS styles for the tooltip position
	 *
	 * @param string $position
	 * @param int    $button_size
	 * @param bool   $is_reponsive
	 *
	 * @since  1.4.0
	 * @access public
	 *
	 * @return void
	 */
	public function tooltip_position_css( $position = '', $button_size, $is_reponsive = false ) {

		ob_start();

		switch ( $position ) {
			case 'horizontal-top-left':
			case 'horizontal-top':
			case 'horizontal-top-right':
				?>
				.wpbfsb button.wpbfsb-tooltips span.wpbfsb-tooltip:after {
				bottom: 100%;
				left: 50%;
				top: auto;
				right: auto;
				margin: 0 0 0 -8px;
				width: 0;
				height: 0;
				border-bottom: 8px solid #000000;
				border-right: 8px solid transparent;
				border-left: 8px solid transparent;
				border-top: 0 none;
				}
				.wpbfsb button.wpbfsb-tooltips:hover span.wpbfsb-tooltip {
				top: <?php echo $button_size + 10; ?>px;
				left: 50%;
				right: auto;
				bottom: auto;
				margin: 0 0 0 -70px;
				}
				<?php
				break;
			case 'vertical-right-top':
			case 'vertical-right':
			case 'vertical-right-bottom':
				?>
				.wpbfsb button.wpbfsb-tooltips span.wpbfsb-tooltip:after {
				top: 50%;
				left: 100%;
				right: auto;
				bottom: auto;
				margin: -8px 0 0 0;
				width: 0;
				height: 0;
				border-left: 8px solid #000000;
				border-top: 8px solid transparent;
				border-bottom: 8px solid transparent;
				border-right: 0 none;
				}
				.wpbfsb button.wpbfsb-tooltips:hover span.wpbfsb-tooltip {
				right: <?php echo $button_size + 10; ?>px;
				top: <?php echo $button_size / 2 - 15; ?>px;
				left: auto;
				bottom: auto;
				margin: 0;
				}
				<?php
				break;
			case 'horizontal-bottom-right':
			case 'horizontal-bottom':
			case 'horizontal-bottom-left':
				?>
				.wpbfsb button.wpbfsb-tooltips span.wpbfsb-tooltip:after {
				top: 100%;
				left: 50%;
				right: auto;
				bottom: auto;
				margin: 0 0 0 -8px;
				width: 0;
				height: 0;
				border-top: 8px solid #000000;
				border-right: 8px solid transparent;
				border-left: 8px solid transparent;
				border-bottom: 0 none;
				}
				.wpbfsb button.wpbfsb-tooltips:hover span.wpbfsb-tooltip {
				bottom: <?php echo $button_size + 10; ?>px;
				left: 50%;
				top: auto;
				right: auto;
				margin: 0 0 0 -70px;
				}
				<?php
				break;
			case 'vertical-left-bottom':
			case 'vertical-left':
			case 'vertical-left-top':
				?>
				.wpbfsb button.wpbfsb-tooltips span.wpbfsb-tooltip:after {
				top: 50%;
				right: 100%;
				bottom: auto;
				left: auto;
				margin: -8px 0 0 0;
				width: 0;
				height: 0;
				border-right: 8px solid #000000;
				border-top: 8px solid transparent;
				border-bottom: 8px solid transparent;
				border-left: 0 none;
				}
				.wpbfsb button.wpbfsb-tooltips:hover span.wpbfsb-tooltip {
				left: <?php echo $button_size + 10; ?>px;
				top: <?php echo $button_size / 2 - 15; ?>px;
				right: auto;
				bottom: auto;
				margin: 0;
				}
				<?php
				break;
		}

		$content = ob_get_clean();
		echo str_replace( array(
			chr( 10 ),
			chr( 13 ),
			chr( 9 ),
		), '', $content );
	}

}
