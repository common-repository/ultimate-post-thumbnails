<?php

/* Define admin page and menu (* is required)
 *
 *	page_title	(*) -	Title to be displayed on the admin page
 *	menu_name	(*) -	Display name of the menu
 *	topmenu_name	-	Display name of top menu, if not set, 'menu_name' of the first menu will be used
 *	slug		(*) -	The slug name to refer to this menu/page by (should be unique), also used as CSS class of the page
 *	parent_slug		-	The slug name to refer to the parent menu/page by, a page without 'parent_slug' will be treated as Parent
 *	tabs			-	Tabs to be displayed on the admin page, there must be a same name PHP file in the same directory of this file
 *	brand_line		-	A brand line to be displayed on the admin page
 *	version_line	-	A version line to be displayed on the admin page
 *	capability		-	The capability required for this menu/page to be displayed to the user. Default: "Administrator"
 *						Ref https://codex.wordpress.org/Roles_and_Capabilities
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'class.admin_page.php';

$brand_line = __('WordPress Plugin by', UPT_NAME). ' <a target="_blank" href="http://themeforest.net/user/addway?ref=addway">Addway</a>';
$version_line = __('Version', UPT_NAME). ' : <span>'. UPT_VERSION. '</span>';
$quick_links = array();

if(defined('UPT_PREMIUM')) {
	$quick_links[] = array(
							'icon'=>'fa fa-file-text-o',
							'text'=>'documentation',
							'url'=>plugins_url(UPT_NAME). '/doc'
					);
}

if(defined('UPT_PREMIUM')) {
	$quick_links[] = array(
						'icon'=>'fa fa-life-ring',
						'text'=>'support',
						'url'=>'http://community.themenow.com/Forum-Ultimate-Post-Thumbnails'
				);
}

$UPT['admin'][] = array('page_title'=>__('Ultimate Post Thumbnails', UPT_NAME). ( defined('UPT_PREMIUM') ? '' : '<span class="lite-version"> ('. __('lite version', UPT_NAME). ')</span>'),
			   'menu_name'=>__('Thumbnails', UPT_NAME),
               'slug'=>'upt-options',
               'parent_slug'=>'options-general.php',
               'tabs'=>array('general', 'default', 'post-types'),
				'brand_line'=>$brand_line,
				'version_line'=>$version_line,
				'quick_links'=>$quick_links
            );
			
if( isset($UPT['admin']) && is_array($UPT['admin']) ) {
	global $upt_defaults;
	$upt_defaults = array();
	
	foreach($UPT['admin'] as $page)	{
		if(isset($page['tabs'])) {
			global $upt_option_groups;
			$upt_option_groups = array();
			foreach($page['tabs'] as $options_group)
				include_once($options_group. '.php');
			
			$page['tabs'] = $upt_option_groups;
		}

		$admin_page = new UPT_Admin_Page($page);
		$adm_defaults = $admin_page->get_default_vals();
		$upt_defaults = tn_array_merge($upt_defaults, $adm_defaults);
	}
}			

?>