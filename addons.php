<?php
/* Load addons if exists
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$addons = array('addons/thumbnail_number.php',
		'addons/custom_post_type.php',
		'addons/custom_link.php',
		'addons/slider_settings.php',
		'addons/visual_composer_integration/visual_composer_integration.php',
		'shortcode/shortcode.thumbslider.php',
	);
$lightbox = upt_get_option('lightbox');
if($lightbox == 1 || $lightbox == '') {
	$addons[] = 'inc/prettyphoto/prettyphoto.php';
} elseif($lightbox == 2) {
	$addons[] = 'addons/photoswipe/photoswipe.php';
}
$addons[] = 'addons/image_ratio.php';
$addons[] = 'addons/image_filters/image_filters.php';
foreach($addons as $addon) {
	if(file_exists(plugin_dir_path( __FILE__ ). $addon)) {
		require_once $addon;
	}
}

/**
 * Image Ratio Options
 */
add_filter('upt_metabox_options', 'upt_image_ratio_options');
function upt_image_ratio_options($options) {
	$title = __('Image Ratio', UPT_NAME);

	if(!defined('UPT_PREMIUM')) {
	    $title .= ' <span class="upt-pro-only">'. __('(Pro only)', UPT_NAME). '</span>';
	}

	$options[] = array('name'=>'_upt_thumb_settings[ratio]',
			'title' => $title,
			'desc' => __('Works only where image size isn\'t limited, e.g. in a masonry grid.', UPT_NAME) ,
	        'type'=>'select',
	        'std'=>'original',
			'class'=>'tn-opt-toggle toggle-key-custom',
	                'items'=>array(
	                            'original' =>__('Original', UPT_NAME),
	                            '1' =>'1:1',
	                            '2' =>'1:2',
	                            '0.5' =>'2:1',
	                            '1.5' =>'2:3',
	                            '0.667' =>'3:2',
	                            '1.33' =>'3:4',
	                            '0.75' =>'4:3',
	                            'custom' =>__('Custom', UPT_NAME),
	                        ),
	       'premium' => 1,
	       'premium_unlock' => defined('UPT_PREMIUM'),
	    );
	$options[] = array('name'=>'_upt_thumb_settings[custom_ratio]',
			'title' => __('Custom Ratio', UPT_NAME) ,
			'desc' => __('Width : Height, example 2:1', UPT_NAME) ,
	        'type'=>'text',
			'class'=>'',
	        'std'=>'',
			'rowclass'=>'hidden',
	       'premium' => 1,
	       'premium_unlock' => defined('UPT_PREMIUM'),
	    );
	
	return $options;
}

/**
 * Image Filters Option
 */
add_filter('upt_metabox_options', 'upt_image_filter_option');
function upt_image_filter_option($options) {
	$title = __('Filter', UPT_NAME);

	if(!defined('UPT_PREMIUM')) {
	    $title .= ' <span class="upt-pro-only">'. __('(Pro only)', UPT_NAME). '</span>';
	}

	$options[] = array('name'=>'_upt_thumb_settings[filter]',
			'title' => $title,
			'desc' => __('Works only in modern browsers.', UPT_NAME). '<a target="_blank" href="http://www.themenow.com/plugins/ultimate-post-thumbnails/filters/"> '. __('Examples', UPT_NAME). '</a>',
	        'type'=>'select',
	        'std'=>'none',
	        'items'=>array(
	                    'none' =>__('No Filter', UPT_NAME),
	                    '_1977' =>__('1977', UPT_NAME),
	                    'aden' =>__('Aden', UPT_NAME),
	                    'brannan' =>__('Brannan', UPT_NAME),
	                    'brooklyn' =>__('Brooklyn', UPT_NAME),
	                    'clarendon' =>__('Clarendon', UPT_NAME),
	                    'earlybird' =>__('Earlybird', UPT_NAME),
	                    'gingham' =>__('Gingham', UPT_NAME),
	                    'hudson' =>__('Hudson', UPT_NAME),
						'inkwell'	=>__('Inkwell', UPT_NAME),
						'kelvin'	=>__('Kelvin', UPT_NAME),
						'lark'		=>__('Lark', UPT_NAME),
						'lofi'		=>__('Lo-Fi', UPT_NAME),
						'maven'		=>__('Maven', UPT_NAME),
						'mayfair'	=>__('Mayfair', UPT_NAME),
						'moon'		=>__('Moon', UPT_NAME),
						'nashville'	=>__('Nashville', UPT_NAME),
						'perpetua'	=>__('Perpetua', UPT_NAME),
						'reyes'		=>__('Reyes', UPT_NAME),
						'rise'		=>__('Rise', UPT_NAME),
						'slumber'	=>__('Slumber', UPT_NAME),
						'stinson'	=>__('Stinson', UPT_NAME),
						'toaster'	=>__('Toaster', UPT_NAME),
						'valencia'	=>__('Valencia', UPT_NAME),
						'walden'	=>__('Walden', UPT_NAME),
						'willow'	=>__('Willow', UPT_NAME),
						'xpro2'		=>__('X-pro II'	, UPT_NAME),
	                ),
	       'premium' => 1,
	       'premium_unlock' => defined('UPT_PREMIUM'),
	    );
	
	return $options;
}
