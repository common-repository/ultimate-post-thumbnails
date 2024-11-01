<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/*  ____________________________________________________________________________________________

								thumbnail slider settings 
	____________________________________________________________________________________________ */

	
$options = array(
    array('name'=>'slider[themeColor]',
        'title'=>__('Nav Button Color', UPT_NAME),
            'type'=>'select',
            'std'=>'light',
            'items'=>array(
                        'light' =>__('Light', UPT_NAME),
                        'dark' =>__('Dark', UPT_NAME),
                    )
        ),
	array('name'=>'slider[animation]',
		'title'=>__('Animation', UPT_NAME),
            'type'=>'select',
            'std'=>'fade',
			'class'=>'upt-slider-animation',
            'items'=>array(
                        'fade' =>__('Fade', UPT_NAME),
                        'slide' =>__('Slide', UPT_NAME),
                    )
        ),
	array('name'=>'slider[direction]',
		'title'=>__('Direction', UPT_NAME),
			'desc'=>__('Select the sliding direction', UPT_NAME),
            'type'=>'select',
            'std'=>'horizontal',
			'rowclass'=>'hidden',
            'items'=>array(
                        'horizontal' =>__('Horizontal', UPT_NAME),
                        'vertical' =>__('Vertical', UPT_NAME),
                    )
        ),
	array('name'=>'slider[reverse]',
		'title'=>__('Reverse', UPT_NAME),
			'desc'=>__('Reverse the animation direction', UPT_NAME),
            'type'=>'select',
            'std'=>'false',
			'rowclass'=>'hidden',
            'items'=>array(
                        'true' =>__('Yes', UPT_NAME),
                        'false' =>__('No', UPT_NAME),
                    )
        ),
	array('name'=>'slider[slideshow]',
		'title'=>__('Autoplay', UPT_NAME),
            'type'=>'select',
            'std'=>'false',
            'items'=>array(
                        'true' =>__('Yes', UPT_NAME),
                        'false' =>__('No', UPT_NAME),
                    )
        ),
	array('name'=>'slider[easing]',
		'title'=>__('Easing', UPT_NAME),
			'desc'=>'<a href="http://api.jqueryui.com/easings/" target="_blank">'. __('What is easing?', UPT_NAME). '</a>',
            'type'=>'select',
            'std'=>'easeOutQuad',
            'items'=>'{easing_list}'
        ),
	array('name'=>'slider[slideshowSpeed]',
		'title'=>__('Slider Speed', UPT_NAME),
            'type'=>'text',
            'class'=>'small-text',
            'std'=>7000,
			'desc'=>__('Set the speed of the slideshow cycling, in milliseconds', UPT_NAME)
        ),
	array('name'=>'slider[animationSpeed]',
		'title'=>__('Animation Speed', UPT_NAME),
            'type'=>'text',
            'class'=>'small-text',
            'std'=>600,
			'desc'=>__('Set the speed of animations, in milliseconds', UPT_NAME)
        ),
	array('name'=>'slider[initDelay]',
		'title'=>__('Start Time', UPT_NAME),
            'type'=>'text',
            'class'=>'small-text',
            'std'=>0,
			'desc'=>__('Set the start time, in milliseconds', UPT_NAME)
        ),
	array('name'=>'slider[directionNav]',
		'title'=>__('Show Direction Nav', UPT_NAME),
            'type'=>'select',
            'std'=>'true',
            'items'=>array(
                        'true' =>__('Yes', UPT_NAME),
                        'false' =>__('No', UPT_NAME),
                    )
        ),
    array('name'=>'slider[directionNavSize]',
        'title'=>__('Direction Nav Size', UPT_NAME),
            'type'=>'select',
            'std'=>'sm',
            'items'=>array(
                        'sm' =>__('Small', UPT_NAME),
                        'md' =>__('Medium', UPT_NAME),
                        'lg' =>__('Large', UPT_NAME),
                    )
        ),
	array('name'=>'slider[controlNav]',
		'title'=>__('Show Control Nav', UPT_NAME),
            'type'=>'select',
            'std'=>'true',
            'items'=>array(
                        'true' =>__('Yes', UPT_NAME),
                        'false' =>__('No', UPT_NAME),
                        'thumbnails' =>__('Thumbnails', UPT_NAME),
                    )
        ),
	array('name'=>'slider[randomize]',
		'title'=>__('Random Order', UPT_NAME),
            'type'=>'select',
			'desc'=>__('Randomize slide order', UPT_NAME),
            'std'=>'false',
            'items'=>array(
                        'true' =>__('Yes', UPT_NAME),
                        'false' =>__('No', UPT_NAME),
                    )
        ),
	array('name'=>'slider[pauseOnHover]',
		'title'=>__('Pause on hover', UPT_NAME),
            'type'=>'select',
            'std'=>'false',
            'items'=>array(
                        'true' =>__('Yes', UPT_NAME),
                        'false' =>__('No', UPT_NAME),
                    )
        ),
	array('name'=>'slider[smoothHeight]',
		'title'=>__('Auto Height', UPT_NAME),
            'type'=>'select',
            'std'=>'false',
            'items'=>array(
                        'true' =>__('Yes', UPT_NAME),
                        'false' =>__('No', UPT_NAME),
                    )
        ),

);

global $upt_slider_settings;

$title = __('Thumbnail Slider Settings', UPT_NAME);
$class = 'tn-toggle-content hidden';
if(!defined('UPT_PREMIUM')) {
    $title .= ' <span class="upt-pro-only">'. __('(Pro only)', UPT_NAME). '</span>';
    $class .= ' upt-disabled';
}
$metabox = array(
	'id' => 'upt-slider-settings',
	'title' => __('Thumbnail Slider Settings', UPT_NAME) ,
	'options' => $options,
	'post_type' => upt_post_types(),
	'meta_prefix'	=>	UPT_METAPREFIX,
	'context'=>'side',
	'before'=>'<div id="upt-slider-settings"><h4 class="tn-toggle">'. $title .'</h4><div class="'. $class. '">',
	'after'=>'</div></div>'
);

$upt_slider_settings = new TN_Meta_Box($metabox);

?>