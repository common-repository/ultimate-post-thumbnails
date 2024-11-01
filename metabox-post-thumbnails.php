<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$title_text = upt_is_standalone() ? __( 'Image', UPT_NAME ) : __( 'Featured Image', UPT_NAME );
$button_text = upt_is_standalone() ? __( 'Add Image', UPT_NAME ) : __( 'Add Featured Image', UPT_NAME );

/*  ____________________________________________________________________________________________

								WP thumbnail metadata
	____________________________________________________________________________________________ */
global $upt_wp_thumb_options;
$upt_wp_thumb_options = array(
	array( 'text'=>'<div id="upt_0" class="upt-image-entry upt-image-entry-0 upt-entry-available">', 'type'=>'html' ),
	array( 'name'=>'_thumbnail_id',
		'type'=>'upload',
		'upload_button'=>false,
		'remove_button'=>false,
		'std'=>0,
		'class'=>'tn-required-area',
		'rowclass'=>'upt-preview-image',
	),
	array( 'text'=>'</div>', 'type'=>'html' )
);

$upt_wp_thumbnail_metabox = array(
	'id' => 'upt-wp-featured-image',
	'title' => __( 'WP Featured Image', UPT_NAME ) ,
	'options' => $upt_wp_thumb_options,
	'post_type' => upt_post_types(),
	'meta_prefix' => '',
	'context'=>'side',
);

/*  ____________________________________________________________________________________________

								Multiple thumbnails metadata
	____________________________________________________________________________________________ */
global $upt_thumb_options;
$upt_thumb_options = array(
	array( 'text'=>'<div id="upt_%placeholder%" class="upt-image-entry upt-image-entry-%placeholder% upt-entry-available">', 'type'=>'html' ),
	array( 'name'=>'_upt_thumbnails[%placeholder%][id]',
		'type'=>'upload',
		'upload_button'=>false,
		'remove_button'=>false,
		'std'=>0,
		'class'=>'tn-required-area',
		'rowclass'=>'upt-preview-image',
		'skip_if_empty'=>true,
	),
	array( 'text'=>'</div>', 'type'=>'html' )
);

$options = array(
	'thumbnails' => array(
		'name' => '_upt_thumbnails',
		'type' => 'custom',
		'options' => $upt_thumb_options,
		'callback' => 'upt_generate_options',
	),
	'order' => array(
		'name' => '_upt_thumbnails_order',
		'type' => 'text',
		'rowclass' => 'hidden'
	),
	array( 'text'=>'<div class="upt-options">', 'type'=>'html' ),
	array( 'name'=>'_upt_thumb_settings[linkto]',
		'title' => __( 'Link', UPT_NAME ) ,
		'type'=>'select',
		'class'=>'ipo-linkto-select',
		'std'=>'none',
		'items'=>array(
			'none' =>__( 'None', UPT_NAME ),
			'file' =>__( 'Media File', UPT_NAME ),
			'post' =>__( 'Current Post', UPT_NAME ),
			'attachment' =>__( 'Attachment Page', UPT_NAME ),
			'custom' =>__( 'Custom', UPT_NAME ),
		),
		'premium_choice' => array( 'custom'=>__( '(Pro only)', UPT_NAME ) ),
		'premium_unlock' => defined( 'UPT_PREMIUM' ),
	),
	array( 'name'=>'_upt_thumb_settings[url]',
		'title' => __( 'Custom URL', UPT_NAME ) ,
		'type'=>'text',
		'class'=>'',
		'std'=>'',
		'rowclass'=>'hidden',
	),
	array( 'name'=>'_upt_thumb_settings[target]',
		'title' => __( 'Open to', UPT_NAME ) ,
		'type'=>'select',
		'std'=>'_lightbox',
		'class'=>'',
		'items'=>array(
			'_self' =>__( 'Same window/tab', UPT_NAME ),
			'_blank' =>__( 'New window/tab', UPT_NAME ),
			'_global_lightbox' =>__( 'Lightbox', UPT_NAME ),
			'_lightbox' =>__( 'Dedicated Lightbox', UPT_NAME ),
		),
		'premium_choice' => array( '_lightbox'=>__( '(Pro only)', UPT_NAME ) ),
		'premium_unlock' => defined( 'UPT_PREMIUM' ),
		'priority'=>1,
	),
	array( 'name'=>'_upt_thumb_settings[position]',
		'title' => __( 'Position', UPT_NAME ) ,
		'desc' => __( 'Some themes require an absolute position for post thumbnail.', UPT_NAME ) ,
		'type'=>'select',
		'std'=>'relative',
		'class'=>'',
		'items'=>array(
			'relative' =>__( 'Relative', UPT_NAME ),
			'absolute' =>__( 'Absolute', UPT_NAME ),
		),
	),
);
$options = apply_filters( 'upt_metabox_options', $options );
array_push( $options, array( 'text'=>'</div>', 'type'=>'html' ) );

$metabox = array(
	'id' => 'ultimate-post-thumbnails',
	'title' => __( 'Ultimate Post Thumbnails', UPT_NAME ) ,
	'options' => $options,
	'post_type' => upt_post_types(),
	'meta_prefix' => '',
	'before' => '<span id="upt-add-featured-image" class="button button-primary hide-if-no-js"><i class="icon-plus" style="margin-right:5px;"></i>' . $button_text . '</span>',
	'context'=>'side'
);

/* Since we include WP featured image in the content, Metabox object must be initialized
   before creating metabox content (if initialized during creating metabox content, multipe instances!), because
   WordPress featured image will trigger Ajax request and re-create metabox content
   */
global $upt_thumbnails;
$upt_thumbnails = new TN_Meta_Box( $metabox );

/*  ____________________________________________________________________________________________

								Metabox content, js, css
	____________________________________________________________________________________________ */

if ( upt_is_standalone() ) {
	upt_register_metabox();
} else {
	global $upt_wp_thumbnail;
	$upt_wp_thumbnail = new TN_Meta_Box( $upt_wp_thumbnail_metabox );
	add_filter( 'admin_post_thumbnail_html', 'upt_admin_post_thumbnail_html', 1000, 2 );
}

function upt_register_metabox() {
	global $upt_thumbnails;
	$upt_thumbnails->order_by = '_upt_thumbnails_order';
	$upt_thumbnails->callback = 'upt_metabox_content';
	$upt_thumbnails->add();
}

/*
 * Standalone metabox
 */
function upt_metabox_content( $post, $metabox ) {
	global $upt_thumbnails, $upt_slider_settings;
	$upt_thumbnails->extract();
	$upt_slider_settings->extract( 'edit_mark=0' );
}

/*
 * Replace Featured Image metabox
 */
function upt_admin_post_thumbnail_html( $content, $post_id ) {
	if ( upt_post_type_disabled( $post_id ) )
		return $content;

	global $upt_thumbnails, $upt_slider_settings;
	ob_start();

	$upt_thumbnails->extract();
	$featured_images = ob_get_contents();
	ob_clean();

	$upt_slider_settings->extract( 'edit_mark=0' );
	$slider_settings = ob_get_clean();

	return $featured_images. $slider_settings;

}

/*
 * Callback function to render metabox options
 */
function upt_generate_options() {
	$thumbnails = upt_get_post_thumbnail_data();
	$index_array = upt_get_index_array();

	echo '<ul class="upt-images-thumb">';
	foreach ( $thumbnails as $index => $thumb ) {
		unset( $index_array[$index] );?>
		<li id="<?php echo 'upt-image-thumb-'. $index; ?>" class="upt-image-thumb"><?php echo wp_get_attachment_image( $thumb['id'] ); ?><span class="del"></span></li><?php
	}

	// Remove 0 from $available_index if standalone mode, 0 is reserved for wp thumbnail
	if ( upt_is_standalone() ) {
		unset( $index_array[0] );
	}

	foreach ( $index_array as $available_index ) {?>
		<li id="<?php echo 'upt-image-thumb-'. $available_index; ?>" class="upt-image-thumb hidden"><span class="del"></span></li><?php
	}
	echo '</ul>';

	echo '<div class="upt-images">';
	global $upt_wp_thumbnail, $upt_thumb_options;
	foreach ( $thumbnails as $index => $thumb ) {
		if ( $index == 0 ) {
			$upt_wp_thumbnail->options = json_decode( str_replace( ' upt-entry-available', '', json_encode( $upt_wp_thumbnail->options ) ), true );
			$upt_wp_thumbnail->extract( 'edit_mark=0' );
		} else {
			$metabox = array(
				'options' => $upt_thumb_options,
				'post_type' => upt_post_types(),
			);
			$metabox = json_decode( str_replace( array( '%placeholder%', ' upt-entry-available' ), array( $index, '' ), json_encode( $metabox ) ), true );
			$settings = new TN_Meta_Box( $metabox );
			$settings->extract( 'edit_mark=0' );
		}
	}
	foreach ( $index_array as $available_index ) {
		if ( $available_index == 0 ) {
			$upt_wp_thumbnail->extract( 'edit_mark=0' );
		} else {
			$metabox = array(
				'options' => $upt_thumb_options,
				'post_type' => upt_post_types(),
			);
			$metabox = json_decode( str_replace( '%placeholder%', $available_index, json_encode( $metabox ) ), true );
			$settings = new TN_Meta_Box( $metabox );
			$settings->extract( 'edit_mark=0' );
		}

	}
	echo '</div>';
}

// Example of usage of filter 'tn_post_meta_html', add a word after the thumbnail
function upt_post_meta_html( $html, $meta_name, $meta_value ) {
	if ( preg_match( '/\[id\]/', $meta_name, $matches ) ) {
		$html .= '<br>hello world!';
	}
	return $html;
}

/**
 * Add upt class to featured image metabox
 *
 */
if ( $post_types = upt_post_types() ) {
	$box_id = upt_is_standalone() ? 'ultimate-post-thumbnails' : 'postimagediv';
	foreach ( $post_types as $post_type ) {
		add_filter( 'postbox_classes_'. $post_type. '_'. $box_id, 'upt_metabox_classes' );
	}
}
function upt_metabox_classes( $classes ) {
	array_push( $classes, 'upt-thumbnails' );
	return $classes;
}

// add_action('admin_head', 'upt_admin_print_scripts');
function upt_admin_print_scripts() {
	global $post;

	if ( !$post || upt_post_type_disabled( $post->ID ) || upt_is_standalone() )
		return;
?>
    <script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#postimagediv').find('h2 span').html('<?php _e( 'Ultimate Post Thumbnails', UPT_NAME ); ?>');
		});
	</script><?php
}
?>
