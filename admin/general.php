<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
// Lists: link_categories, widget_areas, featured_posts, sticky_posts, post_types, category, tag, image_sizes

$options = array();

$options[] = array('name' => 'upt_version',
    'type' => 'text',
    'std' => UPT_VERSION,
    'title' => ' ',
    'rowclass' => 'hidden',
    'alt' => 1,
    'priority' => 0,
);

$options[] = array('name' => 'max_thumbnails',
    'type' => 'text',
    'class' => 'small-text',
    'std' => 3,
    'title' => __('Number of Featured Images', UPT_NAME),
    'desc' => __('At max (per post)', UPT_NAME),
    'alt' => 1,
    'premium' => 1,
    'premium_unlock' => defined('UPT_PREMIUM'),
    'sanitize_callback' => array('TN_Sanitize', 'number'),
    'priority' => 1,
);

// $options[] = array('name' => 'fallback',
//   'type' => 'radio',
//   'std' => 0,
//   'title' => __('Fallback', UPT_NAME),
//   'desc' => __('Automatically load attached images if no featured images specified.', UPT_NAME),
//     'items' => array(
//         0 => __('None', UPT_NAME),
//         1 => __('Attached Images', UPT_NAME),
//     ),
//   'alt' => 1,
// );

$options[] = array('name' => 'slider_add_img_class',
    'type' => 'select',
    'std' => 0,
    'title' => __('Inherit Theme Style', UPT_NAME),
    'desc' => __('Not work on thumbnail sliders inserted by shortcode, because there is no style to inherit.', UPT_NAME),
    'class' => 'regular-select',
    'items' => array(
        1 => __('On thumbnail slider', UPT_NAME),
        0 => __('On thumbnail', UPT_NAME),
    ),
    'alt' => 1,
    'priority' => 2,
);

// $options[] = array('name' => 'slider_position',
//     'type' => 'select',
//     'std' => 0,
//     'title' => __('Thumbnails Position', UPT_NAME),
//     'desc' => __('Some themes .', UPT_NAME),
//     'class' => 'regular-select',
//     'items' => array(
//         'relative' => __('Relative', UPT_NAME),
//         'absolute' => __('Absolute', UPT_NAME),
//     ),
//     'alt' => 1,
//     'priority' => 2,
// );

$options[] = array('name' => 'slider_btn_style',
    'type' => 'select',
    'std' => 'circle',
    'title' => __('Slider Button Style', UPT_NAME),
    'desc' => __('Choose a button style', UPT_NAME),
    'class' => 'regular-select',
    'items' => array(
        'default' => __('Default', UPT_NAME),
        'circle' => __('Circle', UPT_NAME),
        'dock_square' => __('Dock Square', UPT_NAME),
        'outline_circle' => __('Outline Circle', UPT_NAME),
    ),
    'alt' => 1,
    'premium' => 1,
    'premium_unlock' => defined('UPT_PREMIUM'),
    'priority' => 3,
);

// $options[] = array('name' => 'slider_btn_color',
//     'type' => 'select',
//     'std' => 'white',
//     'title' => __('Slider Button Color', UPT_NAME),
//     'desc' => __('Choose a button color', UPT_NAME),
//     'class' => 'regular-select',
//     'items' => array(
//         'white' => __('White', UPT_NAME),
//         'black' => __('Black', UPT_NAME),
//     ),
//     'premium' => 1,
//     'premium_unlock' => defined('UPT_PREMIUM'),
//     'priority' => 4,
// );

$options[] = array('name' => 'lightbox',
    'type' => 'select',
    'std' => 1,
    'title' => __('Lightbox', UPT_NAME),
    'desc' => __('Use <strong>PhotoSwipe</strong> for Visual Composer Integration, the lightbox is image only at this time.', UPT_NAME),
    'class' => 'regular-select',
    'items' => array(
        0 => __('None', UPT_NAME),
        1 => __('PrettyPhoto', UPT_NAME),
        2 => __('PhotoSwipe', UPT_NAME),
    ),
    'alt' => 1,
    'premium_choice' => array(2=>__('(Pro only)', UPT_NAME)),
    'premium_unlock' => defined('UPT_PREMIUM'),
    'priority' => 5,
);

$options[] = array('name' => 'lightbox_image_size',
    'type' => 'select',
    'std' => 'large',
    'title' => __('Image size in lightbox', UPT_NAME),
    'class' => 'regular-select',
    'items' => '{image_sizes}',
    'after_items' => array('full' => __('Full Size', UPT_NAME)),
    'premium' => 1,
    'premium_unlock' => defined('UPT_PREMIUM'),
    'priority' => 6,
);

// $options[] = array('name' => 'lightbox_animation',
//     'type' => 'select',
//     'std' => 'fade',
//     'title' => __('Lightbox Animation', UPT_NAME),
//     'desc' => __('To get the best Zoom In effect, image in lightbox should match aspect ratio of the thumbnail.', UPT_NAME),
//     'class' => 'regular-select',
//     'items' => array(
//         'fade' => __('Fade in', UPT_NAME),
//         'zoom' => __('Zoom in', UPT_NAME),
//     ),
//     'alt' => 1,
//   'premium' => 1,
//   'premium_unlock' => defined('UPT_PREMIUM'),
//   'priority' => 6,
// );
$options[] = array('name' => 'loading_icon',
    'title' => __('Loading Icon', UPT_NAME),
    'desc' => __('Upload a loading GIF to enable loading animation', UPT_NAME),
    'type' => 'upload',
    'alt' => 1,
    'premium' => 1,
    'premium_unlock' => defined('UPT_PREMIUM'),
    'sanitize_callback' => array('TN_Sanitize', 'number'),
    'priority' => 7,
);

// $options[] = array('name' => 'enable_custom_css',
//     'type' => 'select',
//     'title' => __('Custom CSS', UPT_NAME),
//     'desc' => __('Use custom CSS', UPT_NAME),
//     'std' => 0,
//     'class' => 'regular-select tn-opt-toggle',
//     'items' => array(
//         1 => __('Enabled', UPT_NAME),
//         0 => __('Disabled', UPT_NAME),
//     ),
//     'alt' => 1,
//     'priority' => 11,
// );

// $options[] = array('name' => 'custom_css',
//     'type' => 'textarea',
//     'title' => ' ',
//     'desc' => '<a href="http://www.htmldog.com/guides/css/beginner/" target="_blank">Learn CSS</a>',
//     'class' => 'large-textarea',
//     'std' => "",
//     'rowclass' => 'hidden',
//     'priority' => 11,
// );

global $upt_option_groups;
if (!isset($upt_option_groups)) {
    $upt_option_groups = array();
}

$group = array('title' => __('General', UPT_NAME),
    'icon' => 'fa fa-gears',
    'options' => apply_filters('upt_settings_general', $options),
    'data_source' => 'upt_options',
);

if (!defined('UPT_PREMIUM')) {
    $group['desc'] = __('Pro features are unlocked with premium version.', UPT_NAME) . ' <a href="https://codecanyon.net/item/ultimate-post-thumbnails-wordpress-plugin/6231608?ref=addway" target="_blank">Go to Purchase</a>';
}

$upt_option_groups[] = $group;
