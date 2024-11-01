<?php
 
/* UPT Shortcode:
 *
 * [upt_slider image_size="large"] 
 * [upt_slider image_size="large" align="center"] 
 * [upt_slider image_size="large" post_id="1"] 
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Shortcode_UPT_Slider {

	function __construct() {
        // Make sure the theme admin menu is created already
        add_action( 'after_setup_theme', array( $this, 'init' ));
		add_action('wp_ajax_upt_shortcode_panel', array( $this, 'show_shortcode_panel'));
		add_shortcode('upt_slider', array( $this, 'execute'));
	}

    function init() {

		if ((current_user_can('edit_posts') || current_user_can('edit_pages')) && get_user_option('rich_editing')) {
			add_filter( 'mce_external_plugins', array($this, 'reg_editor_plugins') );
			add_filter( 'mce_buttons_2', array($this, 'reg_editor_btns'));
			//add_filter('mce_css', array(&$this, 'add_tinymce_editor_sytle'));
		}
	}
	
	function execute($atts, $content = null) {
		extract(shortcode_atts(array(
			'post_id'			=> '',
			'align'			=> 'none',
			'image_size'		=> 'post-thumbnail'
		), $atts));
		
		if(empty($post_id))
			$post_id = get_the_id();
		
		if(!$post_id)
			return __("Ultimate Post Thumbnail: Invalid Post ID!", UPT_NAME);
			
		switch($align) {
			case 'left':
				$before = '<div class="alignleft upt">';
				$after = '</div>';
				break;
			case 'center':
				$before = '<div style="text-align:center">';
				$after = '</div>';
				break;
			case 'right':
				$before = '<div class="alignright upt">';
				$after = '</div>';
				break;
			case 'none':
			default:
				$before = $after = '';
				break;
		}
		
		return $before. upt_post_thumbnail_html(UPT_NAME, $post_id, null, $image_size). $after;
	}
	
	function reg_editor_btns($buttons)
	{
		array_push($buttons, '|', 'ultimate_post_thumbnails');
		return $buttons;
	}	

	function reg_editor_plugins($plgs)
	{
		$plgs['ultimate_post_thumbnails'] = plugins_url('/thumbslider-btn.js', __FILE__ );
		return $plgs;
	}

	function show_shortcode_panel() {
		include_once dirname(__FILE__). '/thumbslider-opt.php';
		die;
	}
}

new Shortcode_UPT_Slider;
?>