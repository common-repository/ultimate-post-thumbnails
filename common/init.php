<?php

/* Common Files
 *
 * include base classes, js, css for Addway WP themes and plugins
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/** Start loading common files  */
require_once 'helper.php';
require_once 'template-admin.php';
require_once 'classes/class.autoloader.php';

/** Note, Visual Composer enqueue its flexslider script in 'template_redirect', which
 * makes it impossible to load our flexslider script even with a higher version
 */
add_action('wp_enqueue_scripts', 'tn_register_scripts', 0);
function tn_register_scripts() {
	/** Register Librarys */

	wp_register_style('magnific-popup', TN_COMMON_URI.'/lib/magnific-popup/magnific-popup.css', array(), '0.9.6'); 
	wp_register_script('magnific-popup', TN_COMMON_URI.'/lib/magnific-popup/jquery.magnific-popup.min.js', array('jquery'), '0.9.6', true);

	wp_register_style('bootstrap-less', TN_COMMON_URI.'/lib/bootstrap/less/bootstrap.less', array(), '3.0.0'); 

	wp_register_style('font-awesome', TN_COMMON_URI.'/lib/fontawesome/css/font-awesome.min.css', array(), '4.6.1'); 
	wp_register_style('ionicons', TN_COMMON_URI.'/lib/ionicons/css/ionicons.min.css', array(), '2.0.0'); 

	wp_register_style('flexslider', TN_COMMON_URI.'/lib/flexslider/flexslider.css', array(), '2.2.0'); 
	wp_register_script( 'flexslider', TN_COMMON_URI.'/lib/flexslider/jquery.flexslider-min.js', array( 'jquery' ), '2.2.0', true);

	wp_register_style('photoswipe', TN_COMMON_URI. '/lib/photoswipe/photoswipe.css', array(), '4.1.1');  
	wp_register_style('photoswipe-default-skin', TN_COMMON_URI. '/lib/photoswipe/default-skin/default-skin.css', array(), '4.1.1');  
	wp_register_script( 'photoswipe', TN_COMMON_URI. '/lib/photoswipe/photoswipe.js', array(), '4.1.1', true);
	wp_register_script( 'photoswipe-ui-default', TN_COMMON_URI. '/lib/photoswipe/photoswipe-ui-default.min.js', array(), '4.1.1', true);

	wp_register_style('prettyphoto', TN_COMMON_URI. '/lib/prettyphoto/css/prettyPhoto.css', array(), '3.1.6');  
	wp_register_script( 'prettyphoto', TN_COMMON_URI. '/lib/prettyphoto/js/jquery.prettyPhoto.js', array( 'jquery' ), '3.1.6', true);

	wp_register_script( 'angularjs', TN_COMMON_URI. '/lib/angularjs/js/angular.min.js', '', '1.5.8', true);
}

add_action('admin_enqueue_scripts', 'tn_register_adm_scripts');
function tn_register_adm_scripts() {
	/** If Less enabled, register less files instead of css files */
	$type = defined('TN_DCSS') && TN_DCSS_ADMIN ? strtolower(TN_DCSS) : 'css';

	/** Register Librarys */
	wp_register_style('magnific-popup', TN_COMMON_URI.'/lib/magnific-popup/magnific-popup.css', array(), '0.9.6'); 
	wp_register_script('magnific-popup', TN_COMMON_URI.'/lib/magnific-popup/jquery.magnific-popup.min.js', array('jquery'), '0.9.6');

	wp_register_style('tn-admin-page', TN_COMMON_URI.'/'. $type. '/options-page.'. $type, array(), TN_COMMON_VERSION); 
	wp_register_script('tn-admin-page', TN_COMMON_URI.'/js/options-page.js', array('jquery'), TN_COMMON_VERSION); 

	wp_register_style('tn-admin', TN_COMMON_URI.'/'. $type. '/admin-style.'. $type, array(), TN_COMMON_VERSION); 
	wp_register_script('tn-admin', TN_COMMON_URI.'/js/admin.js', array('jquery', 'jquery-ui-accordion'), TN_COMMON_VERSION); 

	wp_register_style('font-awesome', TN_COMMON_URI.'/lib/fontawesome/css/font-awesome.min.css', array(), '4.0.3'); 
}

/** Add essential admin scripts */
if(!function_exists('tn_admin_script')) {
	add_action('admin_enqueue_scripts', 'tn_admin_script');
	
	function tn_admin_script() {
		wp_enqueue_script("jquery-effects-core");
					
		wp_enqueue_style('tn-colorpicker', TN_COMMON_URI. '/lib/colorpicker/css/colorpicker.css'); 
		wp_enqueue_script('tn-colorpicker', TN_COMMON_URI. '/lib/colorpicker/js/colorpicker.js'); 
		
		wp_enqueue_style('font-awesome');
		
		wp_enqueue_style('tn-admin');
		wp_enqueue_script('tn-admin');
	}
}

?>