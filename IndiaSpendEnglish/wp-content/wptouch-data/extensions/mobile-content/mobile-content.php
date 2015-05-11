<?php
define( 'MOBILE_CONTENT_VERSION', '1.0' );
add_action( 'add_meta_boxes', 'wptouch_addon_mobile_admin_init' );
add_action( 'save_post', 'wptouch_addon_save_mobile_content' );
add_action( 'the_posts', 'wptouch_addon_show_mobile_content', 1 );
add_filter( 'wptouch_addon_options', 'wptouch_addon_mobile_content_options' );
add_filter( 'wptouch_setting_defaults_addons', 'wptouch_addon_mobile_content_settings_defaults' );

function wptouch_addon_mobile_admin_init() {
	$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );
	if ( !$settings->enable_mobile_content ) {
		return;
	}

	$screens = apply_filters( 'wptouch_mobile_content_post_types', array( 'post', 'page' ) );

	foreach( $screens as $screen ) {
		add_meta_box(
			'mobile-content-area',
			__( 'WPtouch Alternate Mobile Content', 'wptouch-pro' ),
			'wptouch_admin_render_meta_box',
			$screen,
			'normal',
			'high'
		);
	}
}

function wptouch_addon_mobile_content_settings_defaults( $settings ) {
	$settings->enable_mobile_content = true;

	return $settings;
}

function wptouch_addon_mobile_content_options( $page_options ) {
	wptouch_add_page_section(
		WPTOUCH_PRO_ADMIN_ADDON_OPTIONS_GENERAL,
		__( 'Mobile Content', 'wptouch-pro' ),
		'addons-mobile-content',
		array(
			wptouch_add_setting(
				'checkbox',
				'enable_mobile_content',
				__( 'Enable mobile content display', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'3.1'
			)
		),
		$page_options,
		ADDON_SETTING_DOMAIN
	);

	return $page_options;
}

function wptouch_addon_get_mobile_content( $post_ID ) {
	$content = get_post_meta( $post_ID, '_wptouch_addon_mobile_content', true );

	return $content;
}

function wptouch_admin_render_meta_box( $post ) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'wptouch_addon_mobile_nonce' );

	$content = wptouch_addon_get_mobile_content( $post->ID );
	wp_editor( $content, 'mobile-content-editor' );
}

function wptouch_addon_save_mobile_content( $post_id ) {
	// First we need to check if the current user is authorised to do this action.
	if ( isset($_POST['post_type']) && 'page' == $_REQUEST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Secondly we need to check if the user intended to change this value.
	if ( ! isset( $_POST['wptouch_addon_mobile_nonce'] ) || ! wp_verify_nonce( $_POST['wptouch_addon_mobile_nonce'], plugin_basename( __FILE__ ) ) ) {
		return;
	}

	$post_ID = $_POST[ 'post_ID' ];
	$content = $_POST[ 'mobile-content-editor' ];

	delete_post_meta( $post_ID, '_wptouch_addon_mobile_content' );
	add_post_meta( $post_ID, '_wptouch_addon_mobile_content', $content, true );
}

function wptouch_addon_show_mobile_content( $posts ) {
	$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );
	if ( !$settings->enable_mobile_content ) {
		return $posts;
	}

	if ( !is_admin() && wptouch_is_mobile_theme_showing() ) {
		foreach ( $posts as $enumerator => $post ) {
			if ( $mobile_content = wptouch_addon_get_mobile_content( $post->ID ) ) {
				$posts[ $enumerator ]->post_content = $mobile_content;
			}
		}
	}

	return $posts;
}