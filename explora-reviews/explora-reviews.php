<?php
/**
 * @package explora_reviews
 * @version 1.0
 */
/*
Plugin Name: Explora Reviews
Plugin URI: http://henr1k.com
Description: This is a plugin to show posts on a google map
Author: Henrik "Henr1k"
Version: 1.0
Author URI: http://henr1k.com
*/


/**
 * Add Google Maps API Key for admin dashboard and front end
 */

function explora_add_api() {
$google_api_key_array = get_option( 'explora_reviews_settings' );
$google_api_key = $google_api_key_array == false ? '' : $google_api_key_array['explora_reviews_google_api_key'];
    echo '<script src="https://maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&libraries=places&callback=initMap"></script>';
}
add_action('wp_footer', 'explora_add_api');


function explora_add_api_admin() {
  $google_api_key_array = get_option( 'explora_reviews_settings' );
  $google_api_key = $google_api_key_array == false ? '' : $google_api_key_array['explora_reviews_google_api_key'];
      echo '<script src="https://maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&libraries=places&callback=initMap"></script>';
}
add_action('admin_footer','explora_add_api_admin');


/**
 * Register Explora Reviews custom post type
 */

 if ( ! function_exists('explora_reviews') ) {

 function explora_reviews() {

 	$labels = array(
 		'name'                  => _x( 'Explora Reviews', 'Post Type General Name', 'explora_reviews' ),
 		'singular_name'         => _x( 'Explora Review', 'Post Type Singular Name', 'explora_reviews' ),
 		'menu_name'             => __( 'Explora Reviews', 'explora_reviews' ),
 		'name_admin_bar'        => __( 'Explora Reviews', 'explora_reviews' ),
 		'archives'              => __( 'Explora Review Archives', 'explora_reviews' ),
 		'attributes'            => __( 'Explora Review Attributes', 'explora_reviews' ),
 		'parent_item_colon'     => __( 'Parent Explora Review:', 'explora_reviews' ),
 		'all_items'             => __( 'All Explora Reviews', 'explora_reviews' ),
 		'add_new_item'          => __( 'Add New Explora Review', 'explora_reviews' ),
 		'add_new'               => __( 'Add New', 'explora_reviews' ),
 		'new_item'              => __( 'New Explora Review', 'explora_reviews' ),
 		'edit_item'             => __( 'Edit Explora Review', 'explora_reviews' ),
 		'update_item'           => __( 'Update Explora Review', 'explora_reviews' ),
 		'view_item'             => __( 'View Explora Review', 'explora_reviews' ),
 		'view_items'            => __( 'View Explora Review', 'explora_reviews' ),
 		'search_items'          => __( 'Search Explora Review', 'explora_reviews' ),
 		'not_found'             => __( 'Not found', 'explora_reviews' ),
 		'not_found_in_trash'    => __( 'Not found in Trash', 'explora_reviews' ),
 		'featured_image'        => __( 'Featured Image', 'explora_reviews' ),
 		'set_featured_image'    => __( 'Set featured image', 'explora_reviews' ),
 		'remove_featured_image' => __( 'Remove featured image', 'explora_reviews' ),
 		'use_featured_image'    => __( 'Use as featured image', 'explora_reviews' ),
 		'insert_into_item'      => __( 'Insert into Explora Review', 'explora_reviews' ),
 		'uploaded_to_this_item' => __( 'Uploaded to this Explora Review', 'explora_reviews' ),
 		'items_list'            => __( 'Explora Reviews list', 'explora_reviews' ),
 		'items_list_navigation' => __( 'Explora Reviews list navigation', 'explora_reviews' ),
 		'filter_items_list'     => __( 'Filter Explora Reviews list', 'explora_reviews' ),
 	);
 	$args = array(
 		'label'                 => __( 'Explora Review', 'explora_reviews' ),
 		'description'           => __( 'Explora Reviews', 'explora_reviews' ),
 		'labels'                => $labels,
 		'supports'              => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions', ),
 		'taxonomies'            => array( 'explora_tag', 'region' ),
 		'hierarchical'          => false,
 		'public'                => true,
 		'show_ui'               => true,
 		'show_in_menu'          => true,
 		'menu_position'         => 5,
 		'menu_icon'             => 'dashicons-location-alt',
 		'show_in_admin_bar'     => true,
 		'show_in_nav_menus'     => true,
 		'can_export'            => true,
 		'has_archive'           => true,
 		'exclude_from_search'   => false,
 		'publicly_queryable'    => true,
 		'capability_type'       => 'post',
		'rewrite' => array('slug' => 'explora-reviews','with_front' => false),
 	);
 	register_post_type( 'explora-reviews', $args );

 }
 add_action( 'init', 'explora_reviews', 0 );

 }


/**
 * Add custom post type to recent posts sidebar widget
 */


 function mycustomname_links($post_link, $post = 0) {
    if($post->post_type === 'explora-reviews') {
        return home_url('explora-reviews/' . $post->ID . '/');
    }
    else{
        return $post_link;
    }
}
add_filter('post_type_link', 'explora-reviews', 1, 3);

add_filter('widget_posts_args', 'widget_posts_args_add_custom_type');


function widget_posts_args_add_custom_type($params) {
   $params['post_type'] = array('post','explora-reviews');
   return $params;
}

/**
 * Register taxonomies Tags and Region
 */

 if ( ! function_exists( 'region_taxonomy' ) ) {

 function region_taxonomy() {

 	$labels = array(
 		'name'                       => _x( 'Regions', 'Taxonomy General Name', 'explora_reviews' ),
 		'singular_name'              => _x( 'Region', 'Taxonomy Singular Name', 'explora_reviews' ),
 		'menu_name'                  => __( 'Regions', 'explora_reviews' ),
 		'all_items'                  => __( 'All Regions', 'explora_reviews' ),
 		'parent_item'                => __( 'Parent Region', 'explora_reviews' ),
 		'parent_item_colon'          => __( 'Parent Region:', 'explora_reviews' ),
 		'new_item_name'              => __( 'New Region Name', 'explora_reviews' ),
 		'add_new_item'               => __( 'Add New Region', 'explora_reviews' ),
 		'edit_item'                  => __( 'Edit Region', 'explora_reviews' ),
 		'update_item'                => __( 'Update Region', 'explora_reviews' ),
 		'view_item'                  => __( 'View Region', 'explora_reviews' ),
 		'separate_items_with_commas' => __( 'Separate Regions with commas', 'explora_reviews' ),
 		'add_or_remove_items'        => __( 'Add or remove Regions', 'explora_reviews' ),
 		'choose_from_most_used'      => __( 'Choose from the most used', 'explora_reviews' ),
 		'popular_items'              => __( 'Popular Regions', 'explora_reviews' ),
 		'search_items'               => __( 'Search Regions', 'explora_reviews' ),
 		'not_found'                  => __( 'Not Found', 'explora_reviews' ),
 		'no_terms'                   => __( 'No Regions', 'explora_reviews' ),
 		'items_list'                 => __( 'Regions list', 'explora_reviews' ),
 		'items_list_navigation'      => __( 'Regions list navigation', 'explora_reviews' ),
 	);
 	$args = array(
 		'labels'                     => $labels,
 		'hierarchical'               => true,
 		'public'                     => true,
 		'show_ui'                    => true,
 		'show_admin_column'          => true,
 		'show_in_nav_menus'          => true,
 		'show_tagcloud'              => true,
 	);
 	register_taxonomy( 'region', array( 'explora-reviews' ), $args );

 }
 add_action( 'init', 'region_taxonomy', 0 );

 }

 if ( ! function_exists( 'tag_taxonomy' ) ) {

function tag_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Tags', 'Taxonomy General Name', 'explora_reviews' ),
		'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'explora_reviews' ),
		'menu_name'                  => __( 'Tags', 'explora_reviews' ),
		'all_items'                  => __( 'All Tags', 'explora_reviews' ),
		'parent_item'                => __( 'Parent Tag', 'explora_reviews' ),
		'parent_item_colon'          => __( 'Parent Tag:', 'explora_reviews' ),
		'new_item_name'              => __( 'New Tag Name', 'explora_reviews' ),
		'add_new_item'               => __( 'Add New Tag', 'explora_reviews' ),
		'edit_item'                  => __( 'Edit Tag', 'explora_reviews' ),
		'update_item'                => __( 'Update Tag', 'explora_reviews' ),
		'view_item'                  => __( 'View Tag', 'explora_reviews' ),
		'separate_items_with_commas' => __( 'Separate Tags with commas', 'explora_reviews' ),
		'add_or_remove_items'        => __( 'Add or remove Tags', 'explora_reviews' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'explora_reviews' ),
		'popular_items'              => __( 'Popular Tags', 'explora_reviews' ),
		'search_items'               => __( 'Search Tags', 'explora_reviews' ),
		'not_found'                  => __( 'Not Found', 'explora_reviews' ),
		'no_terms'                   => __( 'No Tags', 'explora_reviews' ),
		'items_list'                 => __( 'Tags list', 'explora_reviews' ),
		'items_list_navigation'      => __( 'Tags list navigation', 'explora_reviews' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'explora_tag', array( 'explora-reviews' ), $args );

}
add_action( 'init', 'tag_taxonomy', 0 );

}

/**
 * Custom taxonomy widget short code
 */

add_filter('widget_text', 'do_shortcode');
add_shortcode( 'ct_terms', 'list_terms_custom_taxonomy' );

function list_terms_custom_taxonomy( $atts ) {
  extract( shortcode_atts( array('custom_taxonomy' => '', ), $atts ) );

$args = array( taxonomy => $custom_taxonomy, title_li => '', 'echo' => false );
$output  = '<ul>' . wp_list_categories($args) . '</ul>';
return $output;
}

$dir = plugin_dir_path( __FILE__ );
require_once($dir.'explora-reviews-custom-fields.php');
require_once($dir.'explora-reviews-frontend-map.php');
require_once($dir.'explora-reviews-options.php');
