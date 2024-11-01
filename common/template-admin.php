<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function tn_admin_title() {
	global $tn_admin;
	echo $tn_admin->page_title;
}

function tn_admin_brand_line() {
	global $tn_admin;
	echo $tn_admin->brand_line;
}

function tn_admin_version_line() {
	global $tn_admin;
	echo $tn_admin->version_line;
}

function tn_admin_quick_links() {
	global $tn_admin;
	if(!$tn_admin->quick_links)
		return;
		
	echo '<ul>';
	foreach($tn_admin->quick_links as $link) {
		echo '<li><a target="_blank" href="'. esc_url($link['url']). '"><i class="'. esc_attr($link['icon']). '"></i>'. $link['text']. '</a></li>';
	}
	echo '</ul>';
}

function tn_admin_menu() {
	global $tn_admin;
	echo $tn_admin->get_menu();
}

function tn_admin_notice() {
	global $tn_admin;
	echo $tn_admin->get_notice();
}

function tn_admin_get_tabs() {
	global $tn_admin;
	return $tn_admin->get_tabs();
}

function tn_get_text_domain() {
	global $tn_admin;
	return $tn_admin->text_domain;
}

?>