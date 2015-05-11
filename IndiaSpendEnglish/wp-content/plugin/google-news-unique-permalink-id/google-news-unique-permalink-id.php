<?php
/*
Plugin Name: Google News Unique Permalink ID
Plugin URI: http://www.brainsap-development.com/plugins-resources/wordpress-plugin-google-news-unique-permalink-id/
Description: Plugin adding 5 Digit Unique IDs to Permalinks ready for Google News
Author: Lukas Najduk
Version: 1.0.4
Author URI: http://www.brainsap-development.com/
*/
if (!function_exists('bsd_google_news_unique_permalink_id')) {
    function bsd_google_news_unique_permalink_id($data = array(), $postarr = array()){ 
        global $wpdb;
        if ( ($data['post_status'] == 'publish' || $data['post_status'] == 'future') && $data['post_type'] == 'post') {
            $old_post_edit = false;
            $post_name = $data['post_name'];
            
            // get the id of the item we are currently working on
            $current_post_id = array();
            preg_match("/\d+$/", $data['guid'], $current_post_id);
            $current_post_id = $current_post_id[0];
            
            $unique_id_matches = array();
            if (!preg_match("/\d{3,}$/", $post_name, $unique_id_matches)) {
                // If the post does not have an unique id it's either a new one or a post which has been edited.
                // We're gonna find out later.
                $unique_id = rand(10000,99999);
                $check_sql = "SELECT ID,post_name FROM $wpdb->posts WHERE post_status='publish' AND post_type='post'";
                $existing_post_names = $wpdb->get_results($check_sql);
                $existing_unique_ids = array();
                foreach ($existing_post_names as $existing_post) {
                    if ($current_post_id == $existing_post->ID) {
                        // the post is an old one which is being edited, according to google this post will not be
                        // given a unique id
                        $old_post_edit = true;
                        break;
                    }
                    $matches = array();
                    $this_post_unique_id = preg_match("/\d{3,}$/", $existing_post->post_name, $matches);
                    if (!empty($matches[0])) {
                        $existing_unique_ids[] = $matches[0];
                    }
                }
                if ($old_post_edit == false) {
                    while (in_array($unique_id, $existing_unique_ids)) {
                        $unique_id = rand(10000,99999);
                    }
                    $data['post_name'] = $post_name.'-'.$unique_id;
                }

            }
            else {
                // The post already has a unique id, so its either an edit of a post, or the unique id has
                // been manually put in.
                $unique_id = $unique_id_matches[0];
                $check_sql = "SELECT ID,post_name FROM $wpdb->posts WHERE (post_status='publish' OR post_status='future') AND post_type='post' AND post_name LIKE '%".$unique_id."%'";
                $existing_post_name = $wpdb->get_results($check_sql);
                // The unique id already exists
                if (!empty($existing_post_name)) {
                    foreach ($existing_post_name as $e_p_n) {
                        
                        // if the ID of the post is matching, we edit the title, else were not gonna do anything,
                        // as the user probably has a plan by inserting his own id
                        if ($e_p_n->ID == $current_post_id) {
                            $data['post_name'] = sanitize_title($data['post_title']).'-'.$unique_id;
                            break;
                        }
                    }
                }
            }
        }
        return $data;
    }
}


add_filter( 'wp_insert_post_data' , 'bsd_google_news_unique_permalink_id' , '99');
?>