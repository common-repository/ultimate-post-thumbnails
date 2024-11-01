<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// Lists: link_categories, widget_areas, featured_posts, sticky_posts, post_types, category, tag

$options = array();
						
$options[] = array('name'=>'post_type_post',
			'title' => __('Enable for Posts', UPT_NAME),
			'type'=>'radio',
			'std'=>1,
            'items'=>array(
                                  1 =>__('Yes', UPT_NAME),
                                  0 =>__('No', UPT_NAME),
                               )
		);

$options[] = array('name'=>'post_type_page',
			'title' => __('Enable for Pages', UPT_NAME),
			'type'=>'radio',
			'std'=>1,
            'items'=>array(
                                  1 =>__('Yes', UPT_NAME),
                                  0 =>__('No', UPT_NAME),
                               ),
			'alt'=>1
		);

/* ---------------------- Custom Post Types ---------------------- */

$args = array(
   'public'   => true,
   '_builtin' => false
);

$output = 'objects'; // names or objects, note names is the default
$operator = 'and'; // 'and' or 'or'

$post_types = get_post_types( $args, $output, $operator ); 
foreach( $post_types as $post_type )
	$options[] = array('name'=>'post_type_'. $post_type->name,
				'title' => __('Enable for', UPT_NAME). ' '. $post_type->labels->name,
				'type'=>'radio',
				'std'=>0,
                'items'=>array(
                                      1 =>__('Yes', UPT_NAME),
                                      0 =>__('No', UPT_NAME),
                                   ),
				'alt'=>1,
            'premium_choice' => array(1=>__('(Pro only)', UPT_NAME)),
			  'premium_unlock' => defined('UPT_PREMIUM'),
			);

global $upt_option_groups;
if(!isset($upt_option_groups))
    $upt_option_groups = array();
    
$upt_option_groups[] = array('title'=>__('Post Types', UPT_NAME),
				'icon' => 'fa fa-briefcase',
                    'desc'=>__('Select post types you want to enable multiple featured images for', UPT_NAME),
                    'options'=>$options,
					'data_source'=>'upt_options'
                );

?>