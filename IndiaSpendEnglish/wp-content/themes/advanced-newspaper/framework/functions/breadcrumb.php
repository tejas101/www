<?php 
// original code => dimox_breadcrumbs http://bit.ly/8UPWU3
function gab_breadcrumb() {
  $delimiter = '&nbsp;&raquo;&nbsp;';
  $home = __('Home', 'source'); // text for the 'Home' link
 
  if ( !is_home() && !is_front_page() || is_paged() ) {
    
	global $post;
    $homeLink = home_url();
    echo '<a href="' . $homeLink . '">' . $home . '</a>' . $delimiter;
 
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      //esc_attr_e('Archives by category', 'source'); 
	  //echo $delimiter;
	  if ($thisCat->parent != 0) echo (get_category_parents($parentCat, TRUE, '' . $delimiter));
      echo single_cat_title('', false);
 
    }  elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter;
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $delimiter;
      echo get_the_time('d');
 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter;
      echo get_the_time('F');
 
    } elseif ( is_year() ) {
      echo get_the_time('Y');
 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo $post_type->labels->singular_name . $delimiter;
        echo get_the_title();
      } else {
        $cat = get_the_category(); $cat = $cat[0];
		echo get_category_parents($cat, TRUE, '' . $delimiter);
        echo get_the_title();
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {
      $post_type = get_post_type_object(get_post_type());
      echo $post_type->labels->singular_name;
 
    } elseif ( is_attachment() ) {
      esc_attr_e('Gallery', 'source'); echo $delimiter;
      echo get_the_title();
 
    } elseif ( is_page() && !$post->post_parent ) {
      echo get_the_title();
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo get_the_title();
 
    } elseif ( is_search() ) {
      esc_attr_e('Search results for', 'source'); 
	  echo ' ' . get_search_query();
 
    } elseif ( is_tag() ) {
      esc_attr_e('Posts tagged with', 'source'); echo $delimiter . single_tag_title('', false);
 
    } elseif ( is_author() ) {
      global $author;
      $userdata = get_userdata($author);
      esc_attr_e('Entries posted by', 'source'); echo ' ' . $userdata->display_name;
 
    } elseif ( is_404() ) {
      esc_attr_e('404! Page not found', 'source');
    }
 
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() );
      esc_attr_e(' (Page', 'source');
	  echo ' ' . get_query_var('paged'); echo ')';
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() );
    }
  }
}