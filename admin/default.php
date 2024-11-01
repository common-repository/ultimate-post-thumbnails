<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
// Lists: link_categories, widget_areas, featured_posts, sticky_posts, post_types, category, tag

$options = array();
	
$options[] = array('name'=>'thumb_link',
			'title' => __('Link', UPT_NAME) ,
            'type'=>'radio',
            'std'=>'none',
            'items'=>array(
                        'none' =>__('None', UPT_NAME),
                        'file' =>__('Media File', UPT_NAME),
                        'post' =>__('Post', UPT_NAME),
                        'attachment' =>__('Attachment Page', UPT_NAME),
                    )
        );
		
$options[] = array('name'=>'thumb_link_target',
			'title' => __('Open to', UPT_NAME) ,
            'type'=>'radio',
            'std'=>'_self',
            'items'=>array(
                        '_self' =>__('Same window/tab ', UPT_NAME),
                        '_blank' =>__('New window/tab', UPT_NAME),
                        '_global_lightbox' =>__('Lightbox', UPT_NAME),
                        '_lightbox' =>__('Dedicated Lightbox', UPT_NAME),
                    ),
            'premium_choice' => array('_lightbox'=>__('(Pro only)', UPT_NAME), '_global_lightbox'=>__('(Pro only)', UPT_NAME)),
            'premium_unlock' => defined('UPT_PREMIUM'),
			'alt'=>1
        );
		
global $upt_option_groups;
if(!isset($upt_option_groups))
    $upt_option_groups = array();
    
$upt_option_groups[] = array('title'=>__('Default Settings', UPT_NAME),
				'icon' => 'fa fa-clone',
                    'desc'=>__('Featured image or thumbnail is being added to follow default settings. Changes here will not affect existent thumbnails.', UPT_NAME),
                    'options'=>$options,
					'data_source'=>'upt_options'
                );

?>