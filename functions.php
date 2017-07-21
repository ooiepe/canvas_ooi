<?php
/**
  * OOI Canvas Functions
  */

/**
 * Finds featured image for the post specified by $post_id and returns all the properties for the desired $size
 * @param  int $post_id [id of the post whose featured image we would like to view]
 * @param  string/array $size [size of the image, parameter is identical to the one specified for wp_get_attachmment_image_src ]
 * @return array [returns an array with following key value pairs: id, url, alt, caption, description]
 * Adapted from https://gist.github.com/makbeta/5210410
 */
if (!function_exists('get_post_featured_image')) {
	function get_post_featured_image($post_id, $size) {
		$return_array = array();
		$image_id = get_post_thumbnail_id($post_id);
		$image = wp_get_attachment_image_src($image_id, $size);
		$image_full = wp_get_attachment_image_src($image_id, 'full');
		$image_url = $image[0];
		$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
		$image_post = get_post($image_id);
		$image_caption = $image_post->post_excerpt;
		$image_description = $image_post->post_content;
		$return_array['id'] = $image_id;
		$return_array['url'] = $image_url;
		$return_array['alt'] = $image_alt;
		$return_array['caption'] = $image_caption;
		$return_array['description'] = $image_description;
		$return_array['attachment_link'] = esc_url(get_permalink($image_id));
		$return_array['full_link'] = $image_full[0];
		$return_array['img'] = wp_get_attachment_image($image_id, $size);
		return $return_array;
	}
}

/**
 * ooi_image
 * Outputs and image wrapped in a link to the full size version
 */
if (!function_exists('ooi_image')) {
	function ooi_image($image_id, $size) {
    $image = wp_get_attachment_image($image_id, $size);
		$image_full = wp_get_attachment_image_src($image_id, 'full');
		$image_full_url = $image_full[0];
    return sprintf( '<a href="%s">%s</a>', $image_full_url, $image );
	}
}


/**
 * ooi_image
 * Outputs the array of photos in a Wordpress Gallery shortcode
 */
if (!function_exists('ooi_gallery')) {
	function ooi_gallery($photos) {
    if ( is_array( $photos ) ) {
      foreach ( $photos as $photo ) {
        $ids[]=$photo['ID'];
      }
      $output = gallery_shortcode( array( 
        'include'=>implode(',',$ids),
        'columns'=>4
      ) );
    } else {
      $output = 'No photos';    
    }
  	return $output;  	
	}
}


/**
 * ooi_image_caption
 * Outputs and image wrapped in a link to the full size version all inside 
 *   a div container that incldes the caption
 */
if (!function_exists('ooi_image_caption')) {
	function ooi_image_caption($image_id, $size) {
    $image = wp_get_attachment_image($image_id, $size);
		$image_full = wp_get_attachment_image_src($image_id, 'full');
		$image_full_url = $image_full[0];
		$image_post = get_post($image_id);
		$image_caption = $image_post->post_excerpt;
    return sprintf( '<div class="wp-caption alignright"><a href="%s">%s</a><p class="wp-caption-text">%s</p></div>', 
      $image_full_url,
      $image,
      $image_caption);
	}
}

// Remove comments on all attachment post types
function filter_media_comment_status( $open, $post_id ) {
	$post = get_post( $post_id );
	if( $post->post_type == 'attachment' ) {
		return false;
	}
	return $open;
}
add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );

// Changes past event views to reverse chronological order
function tribe_past_reverse_chronological ($post_object) {
	$past_ajax = (defined( 'DOING_AJAX' ) && DOING_AJAX && $_REQUEST['tribe_event_display'] === 'past') ? true : false;
	if(tribe_is_past() || $past_ajax) {
		$post_object = array_reverse($post_object);
	}
	return $post_object;
}
add_filter('the_posts', 'tribe_past_reverse_chronological', 100);


//Move Search bar to header 
function woo_custom_add_searchform () {
  get_search_form();
} // End woo_custom_add_searchform()
add_action ( 'woo_header_inside', 'woo_custom_add_searchform', 10 )


?>
