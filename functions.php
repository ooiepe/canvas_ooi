<?php
/**
  * OOI Canvas Functions
  */

/**
 * Finds featured image for the post specified by $post_id and returns all the properties for the desired $size
 * @param  int $post_id [id of the post whose featured image we would like to view]
 * @param  string/array $size [size of the image, parameter is identical to the one specified for <code>wp_get_attachmment_image_src</code> <http://codex.wordpress.org/Function_Reference/wp_get_attachment_image_src> ]
 * @return array [returns an array with following key value pairs: id, url, alt, caption, description]
 * Adapted from https://gist.github.com/makbeta/5210410
 */
if (!function_exists('get_post_featured_image')) {
	function get_post_featured_image($post_id, $size) {
		$return_array = [];
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

?>