<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function upt_version_update() {
	$options = get_option('upt');
	if(!$options) {
		return;
	}

	if(!isset($options['upt_version'])) { // UPT v1.0.4 and earlier
		// Map options
		global $upt_defaults;
		$updated = $upt_defaults;
		$updated['slider_add_img_class']	=	$options['slider']['promote_img_class'];
		$updated['slider_btn_style']		=	$options['slider']['style'];
		$updated['slider_btn_color']		=	$options['slider']['color'];
		$updated['enable_lightbox']			=	$options['lightbox']['enable'];
		$updated['lightbox']['theme']		=	$options['lightbox']['theme'];
		$updated['thumb_link']				=	$options['link']['linkto'];
		$updated['thumb_link_target']		=	$options['link']['target'];
		$updated['enable_custom_css']		=	$options['general']['enable_custom_css'];
		$updated['custom_css']				=	$options['general']['custom_css'];
		update_option('upt_options', $updated);

	} else { // UPT v2.1 and earlier
		if(isset($options['post_types'])) {
			foreach($post_types as $post_type => $state) {
				$options['post_type_'. $post_type] = $state;
			}
			unset($options['post_types']);
		}
		update_option('upt_options', $options);
		delete_option('upt');
	}
}

function _upt_thumb_settings_sanitize($values, $options) {
	foreach ($values as $name=>$value) {
		$option_name = '_upt_thumb_settings['. $name. ']';
		foreach($options as $option) {
			if( isset($option['name']) && isset($option['type'])
				&& $option['name'] == $option_name && method_exists('TN_Sanitize', $option['type'])) {
				$values[$name] = TN_Sanitize::$option['type']($value, $option);
			}
		}
	}
	return $values;
}

function _upt_slider_sanitize($values, $options) {
	foreach ($values as $name=>$value) {
		$option_name = '_upt_slider['. $name. ']';
		foreach($options as $option) {
			if( isset($option['name']) && isset($option['type'])
				&& $option['name'] == $option_name && method_exists('TN_Sanitize', $option['type'])) {
				$values[$name] = TN_Sanitize::$option['type']($value, $option);
			}
		}
	}
	return $values;
}

function _upt_thumbnails_sanitize($values, $options) {
	foreach ($values as $index=>$thumbnail) {
		if(!isset($thumbnail['id'])) {
			$values[$index]['id'] = 0;
		} else {
			$values[$index]['id'] = intval($thumbnail['id']);
		}
	}
	return $values;
}

function upt_is_standalone() {
	return ( defined('UPT_STANDALONE') && UPT_STANDALONE );
}

function upt_do_replacement($size) {

	// replace image when 1 or 2:
	// 1. Not Standalone
	// 2. Standalone && $size match $replace_size
	$replace_size = apply_filters('upt_replace_image_sizes', false);

	return ( !defined('UPT_STANDALONE') || !UPT_STANDALONE )
			|| ( defined('UPT_STANDALONE') && UPT_STANDALONE && $replace_size == $size );
}

function upt_get_option($name) {
	global $upt_defaults;
	
	$options = get_option('upt_options');
	
	if(!isset($options[$name]) && !isset($upt_defaults[$name]))
		return;
		
	return isset($options[$name]) ? $options[$name] : $upt_defaults[$name];
}

function upt_get_post_meta($post_id, $meta_key, $single) {
	return get_post_meta($post_id, UPT_METAPREFIX. $meta_key, $single);
}

/**
 * Get available indexes.
 * *
 * @return array
 */
function upt_get_index_array() {
	$max_number = apply_filters('upt_thumbnail_number', 3);
	$index_array = array();
	for($i=0; $i < $max_number; $i++) {
		$index_array[] = $i;
	}
	return $index_array;
}

/**
 * Check if post has an image attached.
 * *
 * @param int $post_id Optional. Post ID.
 * @return bool Whether post has an image attached.
 */
function upt_has_post_thumbnail( $post_id = null ) {
	return (bool) upt_get_post_thumbnail_id( $post_id );
}

/**
 * Retrieve Post Thumbnail Settings. In order.
 *
 * @param int $post_id Optional. Post ID.
 * @return array of thumbnail settings, an empty array if no featured images found
 */
function upt_get_post_thumbnail_data( $post_id = null ) {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
	if(!$post_id || upt_post_type_disabled($post_id) ) {
		return false;
	}

    $cache_name = 'upt_thumbnails_data_'. $post_id;
    $thumbnails_data = wp_cache_get( $cache_name );
    if ( is_array( $thumbnails_data ) ) {
    	return $thumbnails_data;
    }

	$thumbnails_data = array();

	// Get images
	$thumbnails = upt_get_post_meta($post_id, 'thumbnails', true);
	if(!$thumbnails || !is_array($thumbnails) ) {
		$thumbnails = array();
	} else {
		$index_array = upt_get_index_array();
		foreach($thumbnails as $index => $thumbnail) {
			// In case of thumbnail number reduced, say from 4 to 3, we need to ensure #4 will not output
			if(!in_array($index, $index_array))
				unset($thumbnails[$index]);

			// Remove thumbnails entries with meida id==0 (No image assigned)
			if(!$thumbnail['id'])
				unset($thumbnails[$index]);

			// Remove thumbnails that original image not found(removed)
			if(!wp_get_attachment_image_src($thumbnail['id']))
				unset($thumbnails[$index]);
		}
	}
	
	// Include WP Featured Image if not standalone
	if(!upt_is_standalone() && $wp_thumb_id = get_post_meta( $post_id, '_thumbnail_id', true )) {
		$thumbnail = (array)upt_get_post_meta($post_id, 'wp_thumbnail', true);
		$thumbnail['id'] = $wp_thumb_id;
		$thumbnails[0] = $thumbnail;
	}

	if(!empty($thumbnails)) {
		ksort($thumbnails);
		
		if($order = upt_get_post_meta($post_id, 'thumbnails_order', true)) {
			$order = explode(',', $order);
		}
		
		// Fulfill $thumbnails_data with key (thumbnail ID) ordered by the order set by user
		if($order) {
			foreach($order as $index) {
				if(!array_key_exists($index, $thumbnails) || empty($thumbnails[$index]['id']) ) continue;
				$thumbnails_data[$index] = $thumbnails[$index];
				unset($thumbnails[$index]);
			}

			// If there are elements left in $thumbnail (generally wp thumbanil if switch from standalone mode to integration mode, as standalone mode doesn't have position of wp thumbnail in $order), append them to end of $thumbnails_data
			if(!empty($thumbnails)) {
				$thumbnails_data += $thumbnails;
			}
		} else {
			foreach($thumbnails as $index=>$thumbnail) {
				if(empty($thumbnail['id'])) continue;
				$thumbnails_data[$index] = $thumbnail;
			}
		}
	} elseif( upt_is_standalone() && $wp_thumb_id = get_post_meta( $post_id, '_thumbnail_id', true ) ) {
		// If UPT is integrated as 'standalone' mode, add fallback of Featured Image
		$thumbnails_data[0] = array('id'=>$wp_thumb_id);
	}

    wp_cache_add( $cache_name, $thumbnails_data);
	return $thumbnails_data;
}

/**
 * Retrieve Post Thumbnail Ids. In order.
 *
 * @param int $post_id Optional. Post ID.
 * @return array
 */
function upt_get_post_thumbnail_id( $post_id = null ) {
	$key = 'id';
	
	$thumb_array = upt_get_post_thumbnail_data($post_id);
	if(!$thumb_array)
		return;
	$thumb_array = (array)$thumb_array;
	
	return array_map('upt_fetch_thumbnail_id', $thumb_array);
}

function upt_fetch_thumbnail_id($item) {
		return $item['id'];
}

/**
 * Retrieve Post Thumbnail Settings. Link/Open to/Image Ratio, etc.
 *
 * @param int $post_id Optional. Post ID.
 * @return array
 */
function upt_get_post_thumbnail_settings( $post_id = null, $size = 'large' ) {
	if(!$post_id)
		return false;

	// Get settings
	$thumb_settings = upt_get_post_meta($post_id, 'thumb_settings', true);
	$thumb_settings = wp_parse_args($thumb_settings, array(
        'linkto'  => upt_get_option('thumb_link'),
        'target'  => upt_get_option('thumb_link_target'),
        'url'     => '',
        'before'  => '',
        'after'  => '',
		'wrapper' => 'a'
	) );
	
	return apply_filters('upt_post_thumbnail_settings', $thumb_settings, $post_id, $size);
}

/**
 * Update cache for extra post thumbnails in the current loop
 * *
 * @param object $wp_query Optional. A WP_Query instance. Defaults to the $wp_query global.
 */
function upt_update_post_thumbnail_cache( $wp_query = null ) {
	if ( ! $wp_query )
		$wp_query = $GLOBALS['wp_query'];

	if ( isset($wp_query->upt_thumbnails_cached) )
		return;

	$thumb_ids = array();
	foreach ( $wp_query->posts as $post ) {
		if ( $ids = upt_get_post_thumbnail_id( $post->ID ) )
			$thumb_ids = array_merge($thumb_ids, $ids);
	}

	if ( ! empty ( $thumb_ids ) ) {
		_prime_post_caches( $thumb_ids, false, true );
	}

	$wp_query->upt_thumbnails_cached = true;
}

/**
 * Echo Post Thumbnails.
 *
 * @param int $post_id Optional. Post ID.
 * @param string $size Optional. Image size. Defaults to 'post-thumbnail'.
 * @param string|array $attr Optional. Query string or array of attributes.
 * @param int $default_thumbnail_id Optional. Default featured image ID.
 */
function upt_the_post_thumbnail( $size = 'thumbnail', $attr = '' ) {
	
	$post = get_post();
	if ( ! $post ) {
	    return '';
	}
	echo upt_get_post_thumbnails_html('', $post->ID, '', $size, $attr);
}

/**
 * Get Post Thumbnails HTML.
 *
 * @param string $html. original featured image html, or UPT_NAME when called by shortcode
 * @param int $post_id Optional. Post ID.
 * @param int $post_thumbnail_id Optional. Featured image ID.
 * @param string $size Optional. Image size. Defaults to 'post-thumbnail'.
 * @param string|array $attr Optional. Query string or array of attributes.
 */
function upt_get_post_thumbnails_html($html, $post_id, $post_thumbnail_id, $size = 'post-thumbnail', $attr = '') {
	if( upt_post_type_disabled($post_id) )
		return $html;

	global $upt_query;
	$upt_query['post_id'] = $post_id;
	$upt_query['thumb_size'] = $size;

	// UPT container classes
	$upt_query['upt_class'] = 'upt-container upt-container-'. $post_id. ' upt-theme-'. upt_get_option('slider_btn_style');

	/**
	 * Using post id instead of 'count'
	 * - user should avoid duplicate posts on one page
	 * 
	 * @since 2.0
	 */
	// if(!isset($upt_query['count']))
	// 	$upt_query['count'] = 0;
	// $upt_query['count']++;

	do_action('upt_render_start');

	global $upt_debug;
	if($upt_debug) {
		return print_r($upt_query, true);
	}

	// $image_size = tn_get_image_size($size);
	$slider_style = '';//'width:'. $image_size[0]. 'px;'; height:'. $image_size[1]. 'px';
	if( $icon_id = upt_get_option('loading_icon') ) {
		$icon = wp_get_attachment_image_src($icon_id);
		$slider_style .= 'background:transparent url('. $icon[0]. ') no-repeat 50% 50%;';
	}
	$upt_query['slider_style'] = $slider_style;
	
	if(upt_get_option('slider_add_img_class') || $html == UPT_NAME) {
		// UPT container styles (width & height)
		if(!empty($upt_query['image_class']))
			$upt_query['upt_class'] .= ' upt-promote-imgclass '. $upt_query['image_class'];
	} else {
		$upt_query['upt_class'] .= ' upt-no-imgclass';
	}

	// Slider settings
	$slider_settings = array(
		'prevText' => '',
		'nextText' => '',
		'selector' => '.upt-slides > div'
		);
	$slider_settings['controlsContainer'] = '.upt-container-'. $post_id;
	$slider_settings['directionNav'] = 'false';
	$slider_settings['slideshow'] = 'false';
	$slider_settings['theme'] = 'default';
	$slider_settings['themeColor'] = 'light';
	$slider_settings['directionNavSize'] = 'sm';
	// $slider_settings['fadeFirstSlide'] = 'false';

	$upt_query['direction_nav'] = true;
	$upt_query['control_nav'] = true;

	$slider_settings = apply_filters('upt_slider_settings', $slider_settings, $post_id);
			
	$upt_query['upt_class'] .= ' upt-theme-'. $slider_settings['themeColor']. ' upt-direction-nav-'. $slider_settings['directionNavSize'];
	
	$slider_atts = array(
		'class' => 'upt-thumb-slider',
		'data-upt-id'=>$post_id,
		'data-slider'=>esc_attr(tn_json_encode($slider_settings))
	);

	$upt_query['slider_atts'] = apply_filters('upt_slider_atts', $slider_atts, $post_id);

	$thumbs = upt_get_post_thumbnails($post_id, $size, $attr, $post_thumbnail_id);	
	$upt_query['thumbs'] = $thumbs;	

	ob_start();
	include "template-thumbnail-slider.php";
	return ob_get_clean();
}

/**
 * Retrieve Post Thumbnails.
 *
 * @param int $post_id Optional. Post ID.
 * @param string $size Optional. Image size. Defaults to 'post-thumbnail'.
 * @param string|array $attr Optional. Query string or array of attributes.
 * @param int $default_thumbnail_id Optional. Default featured image ID.
 */
function upt_get_post_thumbnails( $post_id, $size = 'thumbnail', $attr = '', $default_thumbnail_id = '') {
	global $upt_query;
	$html = array();

	$thumb_array = upt_get_post_thumbnail_data($post_id);
	$thumb_settings = upt_get_post_thumbnail_settings($post_id, $size);
	if(isset($thumb_settings['position']) && $thumb_settings['position'] == 'absolute') {
		$upt_query['upt_class'] .= ' upt-pos-absolute';
	}
	// $thumbnails = get_post_meta( $post_id, '_upt_thumbnails' );
	// $thumbnails[1]['id'] = 540;
	// $thumbnails[2]['id'] = 483;
	// update_post_meta( $post_id, '_upt_thumbnails', $thumbnails );
	// $thumb_array[1]['id'] = 540;
	// $thumb_array[2]['id'] = 483;

	foreach($thumb_array as $thumbnail) {
		$html[$thumbnail['id']] = upt_fetch_post_thumbnail_html($post_id, $thumbnail['id'], $size, $thumb_settings, $attr);
	}

	return apply_filters( 'upt_get_post_thumbnails', $html, $post_id, $size, $attr );
}

/**
 * Retrieve Post Thumbnails. Not used.
 *
 * @param int $post_id Optional. Post ID.
 * @param string $size Optional. Image size. Defaults to 'post-thumbnail'.
 * @param string|array $attr Optional. Query string or array of attributes.
 * @param int $default_thumbnail_id Optional. Default featured image ID.
 */
function upt_get_post_attachments( $post_id, $size = 'thumbnail', $attr = '', $default_thumbnail_id = '') {
	
	$html = array();
	
    $attachments = get_children( array (
        'post_parent' => $post_id,
        'post_type' => 'attachment'
    ));

    if ( empty($attachments) )
        return $html;

    foreach ( $attachments as $attachment_id => $attachment ) {
        $html[$attachment_id] = upt_fetch_post_thumbnail_html($post_id, $attachment_id, $size, array(), $attr);
    }
	
    return $html;
}

/**
 * Retrieve Post Thumbnail src.
 *
 * @param int $post_id Optional. Post ID.
 * @param int $post_thumbnail_order Optional. No. of post thumbnail.
 * @param string $size Optional. Image size. Defaults to 'post-thumbnail'.
 */
function upt_get_post_thumbnail_src($post_id, $post_thumbnail_order = 0, $size = 'thumbnail') {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	$src = '';
	
	if($order = upt_get_post_meta($post_id, 'thumbnails_order', true))
		$order = explode(',', $order);
	
	$post_thumbnail_index = $order ? $order[$post_thumbnail_order] : $post_thumbnail_order;

	if($post_thumbnail_index == 0) {
		$post_thumbnail_id = get_post_meta($post_id, '_thumbnail_id', true);
        $src = wp_get_attachment_image_src($post_thumbnail_id, $size, false);
	} else {
		if(!$thumbnails = upt_get_post_meta($post_id, 'thumbnails', true))
			return;
		
		foreach($thumbnails as $index=>$thumbnail) {
			if( empty($thumbnail['id']) || $index != $post_thumbnail_index ) continue;
			$src = wp_get_attachment_image_src($thumbnail['id'], $size, false);
		}
	}
	
    return $src;	
}

/**
 * Retrieve a post Thumbnail (with link). Not used.
 *
 * @param int $post_id Optional. Post ID.
 * @param int $post_thumbnail_order Optional. No. of post thumbnail.
 * @param string $size Optional. Image size. Defaults to 'post-thumbnail'.
 * @param string|array $attr Optional. Query string or array of attributes.
 */
function upt_get_post_thumbnail($post_id, $post_thumbnail_order = 0, $size = 'thumbnail', $attr = '') {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
	
	if($order = upt_get_post_meta($post_id, 'thumbnails_order', true))
		$order = explode(',', $order);
	
	$post_thumbnail_index = $order ? $order[$post_thumbnail_order] : $post_thumbnail_order;

	if($post_thumbnail_index == 0) {
		$post_thumbnail_id = get_post_meta($post_id, '_thumbnail_id', true);
		$thumbnail = upt_get_post_meta($post_id, 'wp_thumbnail', true);
	} else {
		if(!$thumbnails = upt_get_post_meta($post_id, 'thumbnails', true))
			return;
		
		foreach($thumbnails as $index=>$thumbnail) {
			if( empty($thumbnail['id']) || $index != $post_thumbnail_index ) continue;
			$post_thumbnail_id = $thumbnail['id'];
			break;
		}
	}
	
	if(!$post_thumbnail_id)
		return;

	// Get settings
	$thumb_settings = upt_get_post_thumbnail_settings($post_id, $size);

    return upt_fetch_post_thumbnail_html($post_id, $post_thumbnail_id, $size, $thumb_settings, $attr);
}


/**
 * Retrieve Post Thumbnail (with link).
 *
 * @param int $post_id. Post ID.
 * @param int $post_thumbnail_id. Post thumbnail ID.
 * @param string $size Optional. Image size. Defaults to 'post-thumbnail'.
 * @param array $thumb_settings Optional. Extra options for each post thumbnail.
 * @param string|array $attr Optional. Query string or array of attributes.
 */
function upt_fetch_post_thumbnail_html($post_id, $post_thumbnail_id, $size = 'thumbnail', $thumb_settings = array(), $attr = '') {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
	
	$size = apply_filters( 'upt_post_thumbnail_size', $size, $post_thumbnail_id, $thumb_settings );
	$attr = apply_filters( 'upt_post_thumbnail_attr', $attr, $post_thumbnail_id, $thumb_settings );
	
	do_action( 'begin_fetch_post_thumbnail_html', $post_id, $post_thumbnail_id, $size ); // for "Just In Time" filtering of all of wp_get_attachment_image()'s filters
	if ( in_the_loop() )
	    upt_update_post_thumbnail_cache();
// return print_r($thumb_settings, true). print_r($size, true). print_r($attr, true);

	if(isset($size['fly']) && $size['fly']) {
		unset($size['fly']);
	    $post_thumbnail_html = upt_fly_get_attachment_image($post_thumbnail_id, $size, true, $attr);
	} else {
	    $post_thumbnail_html = wp_get_attachment_image($post_thumbnail_id, $size, false, $attr);
	}

    if($caption = get_post_field('post_excerpt', $post_thumbnail_id))
    	$post_thumbnail_html .= '<span class="upt-caption">'. $caption. '</span>';
	$html = upt_generate_link($post_thumbnail_html, $post_id, $post_thumbnail_id, $thumb_settings);

	// wrap thumbnail link with "upt-item", required for image filters
	$item_atts = array('class' => 'upt-item');
	if(upt_has_carousel_nav()) {
		// Image for carousel navigation, use size 'thumbnail'
		$src = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
		$item_atts['data-thumb'] = $src[0]; 
	}
	$item_atts = apply_filters( 'upt_item_atts', $item_atts, $post_thumbnail_id, $thumb_settings );
	
    $before = "<div ";
    foreach ( $item_atts as $name => $value ) {
        $before .= " $name=" . '"' . $value . '"';
    }
    $before .= ' >';
    $after = "</div>";

    $html = $before. $html. $after;
	do_action( 'end_fetch_post_thumbnail_html', $post_id, $post_thumbnail_id, $size );
	
	return apply_filters( 'upt_post_thumbnail_html', $html, $post_id, $post_thumbnail_id, $size, $attr );

}

/**
 * Set corrent max image size for lightbox, remove the limitation of $content_width
 *
 * @param array $max_image_size. Limited by $content_width
 * @return array
 */
function upt_bypass_content_width($max_image_size, $size, $context) {
	$_wp_additional_image_sizes = wp_get_additional_image_sizes();

	if ( ! $context )
		$context = is_admin() ? 'edit' : 'display';

	if ( is_array($size) ) {
		$max_width = $size[0];
		$max_height = $size[1];
	}
	elseif ( $size == 'medium' ) {
		$max_width = intval(get_option('medium_size_w'));
		$max_height = intval(get_option('medium_size_h'));

	}
	elseif ( $size == 'medium_large' ) {
		$max_width = intval( get_option( 'medium_large_size_w' ) );
		$max_height = intval( get_option( 'medium_large_size_h' ) );

	}
	elseif ( $size == 'large' ) {
		$max_width = intval(get_option('large_size_w'));
		$max_height = intval(get_option('large_size_h'));

	} elseif ( ! empty( $_wp_additional_image_sizes ) && in_array( $size, array_keys( $_wp_additional_image_sizes ) ) ) {
		$max_width = intval( $_wp_additional_image_sizes[$size]['width'] );
		$max_height = intval( $_wp_additional_image_sizes[$size]['height'] );

	}
	// $size == 'thumb', 'thumbanil', 'full', return original max_image_size
	else {
		return $max_image_size;
	}

	return array( $max_width, $max_height );

}

/**
 * Turn post thumbnail html to a link, or not if $args['linkto'] is 'none'
 *
 * @param string $post_thumbnail_html. Featured image html
 * @param int $post_id Optional. Post ID.
 * @param int $post_thumbnail_id Optional. Featured image ID.
 * @param array $args Optional.
 */
function upt_generate_link($post_thumbnail_html, $post_id, $post_thumbnail_id, $args = "") {
    
    $default_args = array(
        'linkto'  => upt_get_option('thumb_link'),
        'target'  => upt_get_option('thumb_link_target'),
        'url'     => '',
        'before'  => '',
        'after'  => '',
		'wrapper' => 'a'
    );

    $args = wp_parse_args($args, $default_args);	
    extract($args);
                
    $href = '';
    switch($linkto) {
        case 'post':
            $href = get_permalink($post_id);
            break;
        case 'attachment':
            $href = get_attachment_link($post_thumbnail_id);
            break;
        case 'file':
        	$lightbox_image_size = upt_get_option('lightbox_image_size');
        	add_filter('editor_max_image_size', 'upt_bypass_content_width', 10, 3 );
            $image = wp_get_attachment_image_src($post_thumbnail_id, $lightbox_image_size);
			$href = $image[0];
            $medium_image = wp_get_attachment_image_src($post_thumbnail_id, 'medium_large');
        	remove_filter('editor_max_image_size', 'upt_bypass_content_width', 10, 3 );
            break;
        case 'none':
        default:
        	break;
    }

	$href = apply_filters('upt_post_thumbnail_link_url', $href, $post_id, $args);
    if(!empty($href)) {
		// Query String
		$atts = array();
        $atts['target'] = $target;
  		$atts['class'] = 'upt-link upt-link-'. $post_id;
        $atts['href'] = $href;

        $image_caption = get_post_field('post_excerpt', $post_thumbnail_id);
        $atts['title'] = $image_caption ? $image_caption : get_the_title($post_thumbnail_id);

        if(isset($image) && $image) {
        	$atts['data-size'] = $image[1]. 'x'. $image[2];
        }
        if(isset($medium_image) && $medium_image) {
        	$atts['data-med'] = $medium_image[0];
        	$atts['data-med-size'] = $medium_image[1]. 'x'. $medium_image[2];
        }

		$atts = apply_filters('upt_post_thumbnail_link_atts', $atts, $post_id, $post_thumbnail_id, $args);

        // href again for photoswipe
        $atts['data-href'] = $atts['href'];

		/* Detect if we are in a wordpress filter or used standalone 
		 * 
		 * If in filter, wrap image with <span ...> instead of <a ...>  as we 
		 * do not know if our code is wrapped by a parent <a ...>, which will result in 
		 * invalid html of <a> inside of <a>
		 */
		global $upt_query;
		if(!empty($upt_query['in_filter'])) {
			// Save a copy of $atts in data-link-atts, for later to extract by script
			$atts['data-link-atts'] = esc_attr(json_encode($atts));
			$atts['data-href'] = $atts['href'];
			$atts['data-target'] = $atts['target'];

			// Wrap image in a 'span', remove 'a' specific attributes
			$wrapper = 'span';
			unset($atts['href']);
			unset($atts['target']);
		}
		
        $before = $before. "<$wrapper ";
        foreach ( $atts as $name => $value ) {
            $before .= " $name=" . '"' . $value . '"';
        }
        $before .= ' >';
        $after = "</$wrapper>". $after;
    }

	$before = apply_filters('upt_before_post_thumbnail', $before);
	$after = apply_filters('upt_after_post_thumbnail', $after);
	
    return $before. $post_thumbnail_html. $after;
}

function upt_post_type_disabled($post_id) {
	$post_type = get_post_type($post_id);
	$post_types = upt_post_types();
	return $post_types && in_array($post_type, $post_types) ? false : true;
}

function upt_post_types() {
	return apply_filters('upt_post_types', array('post', 'page'));
}

?>