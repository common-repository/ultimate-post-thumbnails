<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('TN_Sanitize')) {

    class TN_Sanitize {
        
        public static function select($option_value, $option) {
            /**
             * for Debug
             */
            // if($option['name'] == 'post_type_tn_portfolio') {
            //     tn_dump($option);
            //     tn_dump($option_value);
            //     echo '<br>';
            //     if(array_key_exists($option_value, $option['items']))
            //         echo 'valid value';
            //     die;                
            // }

            if(!is_array($option['items']) || !isset($option['items']) || !array_key_exists($option_value, $option['items']))
                return isset($option['std']) ? $option['std'] : '';

            return $option_value;
        }

        public static function radio($option_value, $option) {
            return self::select($option_value, $option);
        }

        public static function number($option_value, $option) {
            return ( is_numeric( $option_value ) ) ? $option_value : intval( $option_value );
        }
    }
}
?>