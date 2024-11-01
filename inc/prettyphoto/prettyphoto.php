<?php
/**
 * Lightbox
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * PrettyPhoto Options
 */
add_filter('upt_settings_general', 'upt_prettyphoto_settings');
function upt_prettyphoto_settings($options) {

	$options[] = array('name' => 'lightbox_theme',
		'title' => __('Lightbox Theme', UPT_NAME),
		'desc' => __('Choose a lightbox theme', UPT_NAME),
		'type' => 'select',
		'std' => 'pp_default',
		'class' => 'regular-select',
		'items' => array(
			'pp_default' => __('Default', UPT_NAME),
			'light_rounded' => __('Light Rounded', UPT_NAME),
			'dark_rounded' => __('Dark Rounded', UPT_NAME),
			'light_square' => __('Light Square', UPT_NAME),
			'dark_square' => __('Dark Square', UPT_NAME),
			'facebook' => __('Facebook', UPT_NAME),
		),
	  'premium' => 1,
	  'premium_unlock' => defined('UPT_PREMIUM'),
	  'priority' => 6,
	);
	
	return $options;
}

/**
 * PrettyPhoto Options
 */
add_filter('upt_metabox_options', 'upt_prettyphoto_metabox_options');
function upt_prettyphoto_metabox_options($options) {

	$insert[] = array('text'=>'<div class="hidden">', 'type'=>'html');
	$insert[] = array('name'=>'lightbox_size',
			'title' => __('Lightbox Size', UPT_NAME) ,
            'type'=>'select',
            'std'=>'auto',
			'class'=>'tn-opt-toggle toggle-key-custom',
            'items'=>array(
                        'auto' =>__('Auto', UPT_NAME),
                        'custom' =>__('Custom Size', UPT_NAME),
                    ),
	       'premium_choice' => array('custom'=>__('(Pro only)', UPT_NAME)),
            'premium_unlock' => defined('UPT_PREMIUM'),
        );
	$insert[] = array('name'=>'lightbox_width',
			'title' => __('Width', UPT_NAME),
            'type'=>'text',
			'class'=>'',
            'std'=>800,
			'rowclass'=>'hidden',
        );
	$insert[] = array('name'=>'lightbox_height',
			'title' => __('Height', UPT_NAME),
            'type'=>'text',
			'class'=>'',
            'std'=>600,
			'rowclass'=>'hidden',
        );
	$insert[] = array('text'=>'</div>', 'type'=>'html');

	foreach($options as $index => $option) {
		if(isset($option['name']) && $option['name'] == '_upt_thumb_settings[target]') {
			$options[$index]['class'] .= ' upt-reveal-lightbox-settings';
			// As our arrays contain numeric keys, using array_merge will not overwrite the value of same key, but append.
			$keys = array_keys( $options );
			$pos = array_search( $index, $keys );
			$pos = false === $pos ? count( $options ) : $pos + 1;
			$options = array_merge(array_slice($options, 0, $pos), $insert, array_slice($options, $pos));
			break;
		}
	}

	return $options;
}

add_action('wp_enqueue_scripts', 'upt_lightbox_script', 9999);
function upt_lightbox_script() {
	wp_enqueue_style('prettyphoto');
	wp_enqueue_script('prettyphoto');
	wp_enqueue_script( UPT_NAME. '-prettyphoto', plugins_url('/js/front.prettyphoto.js', __FILE__), array( 'jquery', UPT_NAME ), UPT_VERSION, true);
}

function upt_prettyphoto_sanitize_theme($value) {
	return $value == 'true' || $value == 'false' || is_numeric($value) ? $value : "'". $value. "'";
}

function upt_generate_attr_lightbox() {
	$data_lightbox = array();
	$data_lightbox[] = '"hook":"data-upt-gal"';
	
	if(upt_get_option('enable_lightbox')) {
		$theme = upt_get_option('lightbox_theme');
		$theme = upt_prettyphoto_sanitize_theme($theme);
		$data_lightbox[] = "'theme':". $theme;

		// foreach(upt_get_option('lightbox') as $param => $value) {
		// 	$value = $value == 'true' || $value == 'false' || is_numeric($value) ? $value : "'". $value. "'";
		// 	$data_lightbox[] = "'". $param. "':". $value;
		// }
	}
	
	$data_lightbox = "{". implode(',', $data_lightbox). "}";
	$data_lightbox = esc_attr($data_lightbox);

	return $data_lightbox;
}

// Output lightbox settings to slider container attribute, if multiple thumbnails
add_filter('upt_slider_atts', 'upt_link_attr_lightbox');
function upt_link_attr_lightbox($atts) {

	$atts['data-lightbox'] = upt_generate_attr_lightbox();
	return $atts;
}

add_filter('upt_post_thumbnail_link_atts', 'upt_attr_lightbox', 10, 4);
function upt_attr_lightbox($atts, $post_id, $post_thumbnail_id, $args) {
	$thumbs_data = upt_get_post_thumbnail_data($post_id);	
	if(empty($thumbs_data) || !isset($atts['href'])) {
		return $atts;			
	}

	$num = count($thumbs_data);
	// Output lightbox settings into thumbnail link attribute, if only one thumbnail
	if($num == 1) {
		$atts['data-lightbox'] = upt_generate_attr_lightbox();
	}

	// Retrieve thumbnail settings
    $default_args = array(
        'linkto'  => '',
        'target'  => '',
        'lightbox_id' => 'post-'. $post_id,
        'global_lightbox_id' => 1,
		'lightbox_size' => 'auto',
		'lightbox_width' => 800,
		'lightbox_height' => 600,
    );
    $args = wp_parse_args($args, $default_args);
	extract($args);

	// Output gallery ID into link attribute
    switch($target) {
        case '_lightbox':
        case '_global_lightbox':
            $atts['data-upt-gal'] = $target == '_lightbox' ? 'gallery['. $lightbox_id. ']' : 'gallery['. $global_lightbox_id. ']';
			if($linkto == 'post' || $linkto == 'attachment') {
				$params[] = 'iframe=true';
			}
            break;
        default:
            break;
    }

	// Control lightbox size
    $params = array();
	if($lightbox_size == 'custom')
		$params[] = 'width='. $lightbox_width. '&height='. $lightbox_height;
	if(!empty($params)) {
		$params = implode('&', $params);
		$atts['href'] .= stripos($atts['href'], '?') !== false ? '&' : '?';
		$atts['href'] .= $params;
	}
		
	return $atts;
}
