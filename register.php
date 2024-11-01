<?php

/* Register Common*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$version = '1.5.2';
$path = dirname(__FILE__). '/common';
$url = plugins_url('/common', __FILE__);

global $acf_version;
if( empty($acf_version) || $acf_version < $version ) {
	global $acf_path, $acf_url;
	$acf_path = $path;
	$acf_url = $url;
	$acf_version = $version;
}

if(!function_exists('tn_common_files')) {
	// Load common files after plugins and theme are being loaded, but before the hook "init" being triggered
	add_action('after_setup_theme', 'tn_common_files', 0);

	function tn_common_files() {
		global $acf_version, $acf_path, $acf_url;
		
		define('TN_COMMON_VERSION', $acf_version);
		define('TN_COMMON_PATH', $acf_path);
		define('TN_COMMON_URI', $acf_url);

		require_once $acf_path. '/init.php';
	}
}
?>