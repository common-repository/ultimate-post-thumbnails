<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('UPT_Admin_Page')) {

    class UPT_Admin_Page extends TN_BaseAdmin {
			
    	function save_admin() {
            
            if( !isset($_POST['action']) )
                return;
            
            check_admin_referer('save', '_tnnonce' );

            if ( 'save' == $_POST['action'] ) {
                unset($_POST['action']);
                
                // Get rid of influence of PHP Magic Quotes
                $_POST = array_map( 'stripslashes_deep', $_POST );
                $_POST = $this->sanitize_saving($_POST);

                update_option('upt_options', $_POST);
                header("Location: admin.php?page=". $_GET['page']. "&saved=true");
                die;
                
            } else if( 'reset' == $_POST['action'] ) {
                //update_option(OPTION_NAME, $this->default_options);
                //wp_redirect($_SERVER['REQUEST_URI']);
                exit;
            }
        }

    }
}
?>