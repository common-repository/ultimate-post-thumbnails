<?php 
/*
Plugin Name: Ultimate Post Thumbnails
Plugin URI: http://www.themenow.com/plugins/ultimate-post-thumbnails
Description: Seamlessly WP integrated post thumbnail plugin, turn single post thumbnail to a responsive slider of multiple post thumbnails, integrates a beautiful and powerful lightbox, automatically match theme style, drag and drop backend, and much more.
Author: Addway
Version: 2.1
Author URI: http://www.themeforest.net/user/addway
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* >>>>> Version for use in code <<<<< */
define('UPT_VERSION', '2.1');
define('UPT_NAME', 'ultimate-post-thumbnails');
define('UPT_METAPREFIX', '_upt_');

load_plugin_textdomain(UPT_NAME, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

require_once 'inc/welcome.php';
require_once 'inc/fly-dynamic-image-resizer.php';
require_once 'baseadmin/class.baseadmin.php';
require_once 'register.php';
require_once 'functions.php';
require_once 'template-tags.php';
require_once 'addons.php';

// Make sure 'init' triggered at last so that to recognize custom post types registered by theme or other plugins
add_action('init', 'upt_init', 9999);
function upt_init() {
	include_once 'admin/init.php';
	include_once 'metabox-post-thumbnails.php';
	include_once 'metabox-thumbnail-slider-settings.php';
	
	upt_version_update();
}

/**
 * Dismiss notification forever
 */
require  'inc/notice-dismissal/persist-admin-notices-dismissal.php';
function upt_2_clear_cache_notice() {
    if ( ! PAnD::is_admin_notice_active( 'upt-notice-clear-cache' ) ) {
        return;
    }

    ?>
    <div data-dismissible="upt-notice-clear-cache" class="notice notice-warning is-dismissible">
        <p><strong><?php _e( 'Clear your browser cache if abnormal style in the Feature Image option box.' , UPT_NAME); ?></strong></p>
    </div>
    <?php
}
add_action( 'admin_init', array( 'PAnD', 'init' ) );
add_action( 'admin_notices', 'upt_2_clear_cache_notice' );

add_action('admin_enqueue_scripts', 'upt_admin_enqueue_scripts');
function upt_admin_enqueue_scripts() {
    wp_enqueue_script('jquery-ui-sortable'); 
    wp_enqueue_script('upt-admin', plugins_url('/js/admin.js', __FILE__), array('jquery')); 
    wp_enqueue_script('upt-add-featured-image', plugins_url('/js/admin.add-featured-image.js', __FILE__), array('jquery')); 
    wp_enqueue_style('upt-admin', plugins_url('/css/admin.css', __FILE__)); 
}

// Make sure front.js is loaded after Isotope
add_action('wp_enqueue_scripts', 'upt_enqueue_scripts', 9999);
function upt_enqueue_scripts() {

	wp_enqueue_style('font-awesome');
	
	wp_enqueue_script('jquery-effects-core');
	wp_enqueue_script('flexslider');
    wp_enqueue_script( 'flexslider-manual-direction-nav', plugins_url('/js/jquery.flexslider.manualDirectionControls.js', __FILE__), array( 'jquery' ), NULL, true);
    wp_enqueue_script(UPT_NAME, plugins_url('/js/front.js', __FILE__), array( 'jquery' ), UPT_VERSION, true);
    wp_enqueue_style(UPT_NAME, plugins_url('/css/front.css', __FILE__)); 

		wp_enqueue_script( 'imagesloaded', plugins_url('/js/imagesloaded.pkgd.min.js', __FILE__), array( 'jquery' ), '3.1.8', true);
}

add_action('admin_enqueue_scripts', 'upt_admin_scripts', 9999);
function upt_admin_scripts() {
    wp_enqueue_style('magnific-popup'); 
	wp_enqueue_script( 'magnific-popup');
	
	wp_enqueue_style('font-awesome');
}

add_action('wp_head', 'upt_print_styles');
function upt_print_styles() {
	if(!upt_get_option('enable_custom_css') || !upt_get_option('custom_css') )
		return;
	
    echo "<style type='text/css'>\n";
	echo upt_get_option('custom_css');
    echo "</style>\n";
}

function upt_remove_img_class($attr) {
	global $upt_query;
			
	$upt_query['image_class'] = $attr['class'];
	
	if(upt_get_option('slider_add_img_class') && isset($upt_query['in_filter']) )
		$attr['class'] = 'upt-image';
		
	return $attr;
}
add_filter('wp_get_attachment_image_attributes','upt_remove_img_class', 9999);

/**
 * Rewrite @return of get_the_post_thumbnail()
 *
 * @param string $html. Featured image html, or UPT_NAME when called by shortcode
 * @param int $post_id Optional. Post ID.
 * @param int $post_thumbnail_id Optional. Featured image ID.
 * @param string $size Optional. Image size. Defaults to 'post-thumbnail'.
 * @param string|array $attr Optional. Query string or array of attributes.
 */
function upt_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size = 'post-thumbnail', $attr = '') {

	if( !upt_do_replacement($size) ) {
		return $html;
	} 
	
	global $upt_query;
	if($current_filter = current_filter()) {
		$upt_query['in_filter'] = 1;
	}

	$html = upt_get_post_thumbnails_html($html, $post_id, $post_thumbnail_id, $size, $attr);

	unset($upt_query['in_filter']);
	return $html;
}
add_filter( 'post_thumbnail_html', 'upt_post_thumbnail_html', 10, 5);

/**
 * Add class 'upt-link-single' to thumbnail link
 */
function upt_single_thumbnail_link_attr($atts, $post_id) {
	
	// As we do not add a container DIV for single thumbnail, required attributes have to be added on thumbnail link
	$thumbs_data = upt_get_post_thumbnail_data($post_id);	
	if(empty($thumbs_data) || count($thumbs_data) > 1) {
		return $atts;			
	}

	$atts['class'] .= ' upt-link-single';
	return $atts;
}
add_filter('upt_post_thumbnail_link_atts', 'upt_single_thumbnail_link_attr', 10, 2 );
?>