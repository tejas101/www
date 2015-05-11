<?php
/**
 * The view class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The view class.
 *
 * @since  1.0.0
 * @access public
 *
 */
class WPB_Fixed_Social_Share_View {

	/**
	 * The page name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @var    string $_page Current page name.
	 */
	private $_page;


	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $page Current page name.
	 *
	 * @return \WPB_Fixed_Social_Share_View
	 */
	public function __construct( $page ) {
		$this->_page = $page;
	}

	/**
	 * Renders a page with the given page name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function render() {

		$file_name = trailingslashit( dirname( __FILE__ ) ) . 'view/' . $this->_page . '.php';

		if ( is_file( $file_name ) ) {
			include( $file_name );
		}

	}

}