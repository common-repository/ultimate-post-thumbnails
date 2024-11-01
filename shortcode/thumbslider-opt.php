<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

	$options = array(
		array('name'=>'image_size',
			'title'=>__('Slider Size', UPT_NAME), 
			'type'=>'select', 
			'class'=>'form-control isopt-control', 
			'std'=>'large',
			'items'=>'{image_sizes}',
		),
		array('name'=>'align',
			'title'=>__('Alignment', UPT_NAME), 
			'type'=>'select', 
			'class'=>'form-control isopt-control', 
			'std'=>'none',
			'items'=>array(
				'none'=>__('None', UPT_NAME),
				'left'=>__('Left', UPT_NAME),
				'center'=>__('Center', UPT_NAME),
				'right'=>__('Right', UPT_NAME),
			)
		),
		array('name'=>'post_id',
			'title'=>__('Post ID (optional)', UPT_NAME), 
            'desc'=>__('You can pull thumbnails from a specific post', UPT_NAME),
			'type'=>'text', 
			'class'=>'form-control isopt-control', 
			'std'=>'',
		)
	);
	
	$shortcode = array(
		'title'=>__('Thumbnail Slider', UPT_NAME),
		'name'=>'slider',
		'options'=>$options,
		'labels'=>array('insert_shortcode'=>__('Insert Shortcode', UPT_NAME)),
		'prefix'=>'upt_',
	);
				
	tn_create_shortcode_panel($shortcode);
?>