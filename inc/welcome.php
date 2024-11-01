<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

add_action( 'admin_enqueue_scripts', 'upt_welcome_screen_style', 9999 );
function upt_welcome_screen_style() {
  $path = plugins_url('/logo.png', __FILE__);
  $custom_css = "

  .upt-badge {
      position: absolute;
      top: 0;
      right: 0;
      background: url({$path}) center 25px no-repeat #fff;
      -webkit-background-size: 80px 80px;
      background-size: 80px 80px;
      color: #000;
      font-size: 14px;
      text-align: center;
      font-weight: 600;
      margin: 5px 0 0;
      padding-top: 120px;
      height: 40px;
      display: inline-block;
      width: 120px;
      text-rendering: optimizeLegibility;
      -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.2);
      box-shadow: 0 1px 3px rgba(0,0,0,.2);
  }";
  wp_add_inline_style( 'upt-admin', $custom_css );
}

add_action( 'admin_init', 'upt_welcome_screen_do_activation_redirect' );
function upt_welcome_screen_do_activation_redirect() {
  if ( ! $saved_version = get_option( 'upt_version' ) ) {
    $saved_version = 0;
  }

  $my_version = UPT_VERSION;

  if ( $saved_version >= $my_version ) {
    return;
  }

  // Update version number
  update_option( 'upt_version', $my_version );

  // Bail if activating from network, or bulk
  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
    return;
  }

  // Redirect to about page
  wp_safe_redirect( add_query_arg( array( 'page' => 'upt-about' ), admin_url( 'index.php' ) ) );

}

add_action( 'admin_menu', 'upt_welcome_screen_pages' );

function upt_welcome_screen_pages() {
  add_dashboard_page(
    'About Ultimate Post Thumbnails',
    'About Ultimate Post Thumbnails',
    'read',
    'upt-about',
    'upt_welcome_screen_content'
  );
}

function upt_welcome_screen_content() {
  include_once 'welcome-content.php';
}

add_action( 'admin_head', 'upt_welcome_screen_remove_menus' );
function upt_welcome_screen_remove_menus() {
  remove_submenu_page( 'index.php', 'upt-about' );
}
?>
