<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('TN_BaseAdmin')) {

    abstract class TN_BaseAdmin {

		private $properties;
			
		function __get($name) {
			if(isset($this->properties[$name]))
				return $this->properties[$name];
		}        
		
		function __set($name, $value) {
			$this->properties[$name] = $value;
		}
		
		abstract protected function save_admin();

        function __construct($args) {
            if(!is_array($args))
                return;
				
			$this->properties = $args;
                
			$tabs = array();
            foreach((array)$this->tabs as $tab) {

                if(empty($tab['walker']))
                    $tab['walker'] = 'option_table';
                    
                if(empty($tab['source'])) {
                    preg_match('/^(\w+)/i', $this->slug, $matches);
                    $tab['source'] = strtolower($matches[1]);//showinfo($tab['source']);
                }

                $class = 'tn_'. $tab['walker'];
                $tabs[] = new $class($tab);
            }
			$this->tabs = $tabs;

            //add_filter( 'attachment_fields_to_edit', array(&$this, 'attachment_fields_to_edit'), 1, 2 );
            
            add_action('admin_menu', array($this, 'admin_menu'));
        }

        function media_uploader_strings( $strings )
        { 
            //$strings['insertMediaTitle'] = esc_html__('Choose Image', 'themenow-framework');
            $strings['insertIntoPost'] = esc_html__('Use this image', 'themenow-framework');
            
            return $strings;
        }

        function attachment_fields_to_edit($formfields, $post) {
            var_dump($formfields);
            return $formfields;
        }
        
        function admin_enqueue_scripts() {
            wp_enqueue_media();
            
            // Make sure jQuery UI version is compatible to the jQuery version, otherwise, issues
            //wp_enqueue_script( 'tn-jquery-ui', $this->template_url.'/js/jquery-ui-1.10.0.custom.min.js' );
            
            //wp_enqueue_script('tn-ajaxupload', $this->template_url.'/js/ajaxupload.js'); 
            wp_enqueue_style('tn-admin-page'); 
            wp_enqueue_script('tn-admin-page'); 
        }
        
        /*  This function is added to hook 'admin_head' which loaded after hook 'admin_enqueue_scripts',
         *  and called only on theme admin pages.
         */
        function print_scripts() {

			foreach($this->tabs as $group)
                if(method_exists($group, 'print_scripts'))
                    $group->print_scripts();

        }
        
        function admin_menu() {

            if(!$this->parent_slug) {
                if(function_exists('add_object_page'))
                    add_object_page ('Page Title', $this->menu_name, 'edit_pages', $this->slug);
                else
                    add_theme_page($this->page_title, $this->menu_name, 'Administrator', $this->slug);
            } else {
				$admin_page = add_submenu_page($this->parent_slug, $this->page_title, $this->menu_name, 'edit_pages', $this->slug, array(&$this, 'render'));
			}
			
            if( isset($_GET['page']) && $_GET['page'] === $this->slug ) {
                //wp_deregister_script('jquery');
				//add_action('admin_print_scripts', array(&$this, 'print_scripts'));

				add_filter('media_view_strings', array($this, 'media_uploader_strings'));
				add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

                foreach($this->tabs as $group) {
                    if($group->enqueue_scripts)
                        add_action('admin_enqueue_scripts', $group->enqueue_scripts);

                    if($group->print_script)
                        add_action('admin_head', $group->print_script);
                }
			
				$this->save_admin();
            }
            
            // Seperately add scripts for each theme options page, in favor of add the scripts only when the page is theme options page
            //add_action("admin_print_scripts-$admin_page", array(&$this, 'print_scripts'));
        }
		
        function render() {
			global $tn_admin;
            $tn_admin = $this;
			
            include_once('admin-page.php');
        }
        
        function get_quick_links() {
            return $this->quick_links;
        }
        
        function get_notice() {
            $msg = '';
                
            if( (isset($_POST['action']) && $_POST['action'] == 'save') 
                || (isset($_REQUEST['saved']) && $_REQUEST['saved']) )
                $msg .= '<div class="tn-message message-saved"><p><strong>'. esc_html__("Settings saved.", 'themenow-framework'). '</strong></p></div>';
            
            return $msg;
        }

        function get_tabs() {
            $tabs = array();
            
            foreach($this->tabs as $group) {
				$group->options = $group->build();
                $tabs[] = $group;
            }
            
            return $tabs;
        }

        function get_default_vals() {
            $vals = array();
            
            foreach($this->tabs as $tab) {
				$defaults = $tab->default_vals();
                $vals = tn_array_merge($vals, $defaults);
            }
            
            return $vals;
        }

        function sanitize_saving($input) {
            foreach($this->tabs as $group) {
                foreach ($group->options as $option) {

                    if(isset($option['name']) && isset($option['type']) && isset($input[$option['name']])) {
                        $name = $option['name'];
                        $value = $input[$option['name']];

                        if ( isset( $option['sanitize_callback'] ) && null !== $option['sanitize_callback'] ) {
                            $input[$name] = call_user_func( $option['sanitize_callback'], $value, $option );
                        } elseif(method_exists('TN_sanitize', $option['type'])) {
                            $input[$name] = TN_sanitize::$option['type']($value, $option);
                        }
                    }
                }
            }

            return $input;
        }

    }

}
?>