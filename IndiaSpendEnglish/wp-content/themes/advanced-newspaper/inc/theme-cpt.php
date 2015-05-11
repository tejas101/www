<?php
/* Create Custom Post Type */
add_action('init', 'gallery_init');
function gallery_init() 
{
  $labels = array(
  
	'name' => _x( 'Media Gallery', 'advanced' ),
	'singular_name' => _x( 'Multimedia', 'advanced' ),
	'add_new' => _x( 'Add New', 'advanced' ),
	'add_new_item' => _x( 'Add New', 'advanced' ),
	'edit_item' => _x( 'Edit', 'advanced' ),
	'new_item' => _x( 'New', 'advanced' ),
	'view_item' => _x( 'View', 'advanced' ),
	'search_items' => _x( 'Search', 'advanced' ),
	'not_found' => _x( 'Nothing found', 'advanced' ),
	'not_found_in_trash' => _x( 'Nothing found in Trash', 'advanced' ),
	'parent_item_colon' => _x( 'Parent:', 'advanced' ),
	'menu_name' => _x( 'Multimedia', 'advanced' ), 
  );
    
  $args = array(
        'labels' => $labels,
        'hierarchical' => true,
		'menu_icon' => get_bloginfo('template_directory') . '/framework/images/gabfire-icon.png',
        'supports' => array( 'title', 'editor', 'comments', 'author', 'excerpt', 'thumbnail', 'custom-fields', 'revisions', 'post-formats' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
  ); 
  register_post_type('gab_gallery',$args);
}

//hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'gallery_taxonomies', 0 );

//create two taxonomies, genres and writers for the post type "book"
function gallery_taxonomies() 
{
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'gallery-cats', 'advanced' ),
    'singular_name' => _x( 'gallery-cat', 'advanced' ),
    'search_items' =>  _x( 'Search', 'advanced' ),
    'all_items' => _x( 'All', 'advanced' ),
    'parent_item' => _x( 'Parent', 'advanced' ),
    'parent_item_colon' => _x( 'Parent:', 'advanced' ),
    'edit_item' => _x( 'Edit', 'advanced' ), 
    'update_item' => _x( 'Update', 'advanced' ),
    'add_new_item' => _x( 'Add New', 'advanced' ),
    'new_item_name' => _x( 'New Name', 'advanced' ),
    'menu_name' => _x( 'Gallery Categories', 'advanced' ),
  ); 	

  register_taxonomy('gallery-cat',array('gab_gallery'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'gallery-cats' ),
  ));

  // Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name' => _x( 'gallery-tags', 'advanced' ),
    'singular_name' => _x( 'gallery-tag', 'advanced'),
    'search_items' =>  _x( 'Search', 'advanced' ),
    'popular_items' => _x( 'Popular', 'advanced' ),
    'all_items' => _x( 'All', 'advanced' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => _x( 'Edit', 'advanced' ), 
    'update_item' => _x( 'Update', 'advanced' ),
    'add_new_item' => _x( 'Add New', 'advanced' ),
    'new_item_name' => _x( 'New', 'advanced' ),
    'separate_items_with_commas' => _x( 'Separate with commas', 'advanced' ),
    'add_or_remove_items' => _x( 'Add or remove', 'advanced' ),
    'choose_from_most_used' => _x( 'Choose from the most used', 'advanced' ),
    'menu_name' => _x( 'Gallery Tags', 'advanced' ),
  ); 

  register_taxonomy('gallery-tag','gab_gallery',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'gallery-tag' ),
  ));
}