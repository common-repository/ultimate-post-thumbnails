<?php
/*
Functions here can be used in UPT template file
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function upt_get_the_post_thumbnails() {
	global $upt_query;
	return $upt_query['thumbs'];
}

function upt_has_direction_nav( $post_id = null ) {
	global $upt_query;
	return $upt_query['direction_nav'];
}

function upt_has_carousel_nav( $post_id = null ) {
	global $upt_query;
	return isset($upt_query['control_nav']) && $upt_query['control_nav'] == 'thumbnails';
}

function upt_class() {
	global $upt_query;
	echo $upt_query['upt_class'];
}

function upt_slider_style() {
	global $upt_query;
	echo $upt_query['slider_style'];
}

function upt_slider_atts() {
	global $upt_query;
	foreach($upt_query['slider_atts'] as $name=>$value) 
		echo ' '. $name. '="'. $value. '"';
}

function upt_item_data($thumb_id) {
	global $upt_query;
	$size = $upt_query['thumb_size'];
	$item_data = array();
	
	if(upt_has_carousel_nav()) {
		// Image for carousel navigation, use the same size of post thumbnail
		$src = wp_get_attachment_image_src($thumb_id, $size, false);
		$item_data['data-thumb'] = $src[0]; 
	}
	
	foreach($item_data as $name=>$value) 
		echo ' '. $name. '="'. $value. '"';
}

?>