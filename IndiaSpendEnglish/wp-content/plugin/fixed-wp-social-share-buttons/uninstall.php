<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

if ( ! WP_UNINSTALL_PLUGIN ) {
	exit();
}

/**
 * @var wpdb $wpdb
 */
global $wpdb;

if ( is_a( $wpdb, 'wpdb' ) ) {
	$wpdb->query( 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "wpbfsb_%"' );
	$wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE meta_key LIKE "_wpbfsb_%"' );
	$wpdb->query( 'DELETE FROM ' . $wpdb->usermeta . ' WHERE meta_key LIKE "%wpbfsb%"' );


	// delete all post metas
	$ids = $wpdb->get_var( 'SELECT GROUP_CONCAT(ID) FROM ' . $wpdb->posts . ' WHERE post_type = "wpbfsb" GROUP BY post_type' );
	if ( ! empty( $ids ) ) {
		$wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE post_id IN (' . $ids . ')' );
		$wpdb->query( 'DELETE FROM ' . $wpdb->posts . ' WHERE ID IN (' . $ids . ')' );
	}

}

// delete update transient
delete_option( 'wpbp_u_fixed-wp-social-share-buttons' );