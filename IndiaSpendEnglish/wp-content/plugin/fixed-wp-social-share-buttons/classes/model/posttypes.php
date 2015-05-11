<?php
/**
 * The Posttypes Model Class.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Posttypes Model Class.
 *
 * @since  1.0.1
 * @access public
 *
 */
class WPB_Fixed_Social_Share_Model_Posttypes {

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return WPB_Fixed_Social_Share_Model_Posttypes
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_post_type' ) );
	}

	/**
	 * Adds the new post type 'Buttons'
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @see    WPB_Fixed_Social_Share_Controller_Backend::init()
	 *
	 * @return void
	 */
	public static function add_post_type() {
		$labels = array(
			'name'               => _x( 'Social Buttons', 'post type general name', 'wpbfsb' ),
			'singular_name'      => _x( 'Social Button', 'post type singular name', 'wpbfsb' ),
			'menu_name'          => _x( 'Social Buttons', 'admin menu', 'wpbfsb' ),
			'name_admin_bar'     => _x( 'Social Button', '"add new" on admin bar', 'wpbfsb' ),
			'add_new'            => _x( 'Add New', 'book', 'wpbfsb' ),
			'add_new_item'       => __( 'Add New Social Button', 'wpbfsb' ),
			'new_item'           => __( 'New Social Button', 'wpbfsb' ),
			'edit_item'          => __( 'Edit Social Button', 'wpbfsb' ),
			'view_item'          => __( 'View Social Button', 'wpbfsb' ),
			'all_items'          => __( 'All Buttons', 'wpbfsb' ),
			'search_items'       => __( 'Search Social Buttons', 'wpbfsb' ),
			'parent_item_colon'  => __( 'Parent Social Buttons:', 'wpbfsb' ),
			'not_found'          => __( 'No buttons found.', 'wpbfsb' ),
			'not_found_in_trash' => __( 'No buttons found in Trash.', 'wpbfsb' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-share',
			'supports'           => array( 'title', 'page-attributes' )
		);

		$args = apply_filters( 'wpbfsb_post_type_args', $args );

		register_post_type( 'wpbfsb', $args );
	}
}