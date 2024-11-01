<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('TN_AutoLoader')) {

    class TN_AutoLoader {
        public static $paths = array(
            //PATH_TO_LIBRARIES,
        );
        protected static $prefixes = array('tn_', 'atc_',	'acf_');
        protected static $replacement = array('class.','theme.','common.');
        public static function addPath($path, $sub_directories = false) {
            if(!$path)
                return;

            if ($sub_directories) {
                $path = glob($path . '/*' , GLOB_ONLYDIR);
                self::$paths = array_merge(self::$paths, $path);
            } else {
                self::$paths[] = $path;
            }
        }
        public static function load($classname) {
            if(stripos($classname, 'tn_') === false
			    && stripos($classname, 'atc_') === false
			    && stripos($classname, 'acf_') === false
			)
                return;

            $filename = strtolower(str_ireplace(array('tn_', 'atc_',	'acf_'), array('class.','theme.','common.'), $classname)). '.php';
            
            foreach (self::$paths as $path) {
                if (is_file($path . '/'. $filename)) {
                    require_once $path . '/'. $filename;
                    return;
                }
            }
        }
    }

    TN_AutoLoader::AddPath(TN_COMMON_PATH. '/classes');
    spl_autoload_register(array('TN_AutoLoader', 'load'));
}



?>