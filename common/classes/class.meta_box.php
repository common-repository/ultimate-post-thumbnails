<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if(!class_exists('TN_Meta_Box')) {

	class TN_Meta_Box {

		private $properties;
		public $post_id;
			
		function __get($name) {
			if(isset($this->properties[$name]))
				return $this->properties[$name];
			else
				return null; // isset return false if null
		}        
		
		function __set($name, $value) {
			$this->properties[$name] = $value;
		}
		
		function __construct($metabox) {
			$this->properties = $metabox;
			
			/* post_type - (string) (required) The type of Write screen on which to show the edit screen section ('post', 'page', 'dashboard', 'link', 'attachment' or 'custom_post_type' where custom_post_type is the custom post type slug)
			Default: None

			$context
			(string) (optional) The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side'). (Note that 'side' doesn't exist before 2.7)
			Default: 'advanced'

			$priority
			(string) (optional) The priority within the context where the boxes should show ('high', 'core', 'default' or 'low')
			Default: 'default'

			$callback_args
			(array) (optional) Arguments to pass into your callback function. The callback will receive the $post object and whatever parameters are passed through this variable.
			Default: null
			*/
			
			// do not use empty(), it doesn't work with __get()
			if(!$this->context) $this->context = 'advanced';		
			if(!$this->priority) $this->priority = 'default';		
			if(!$this->callback_args) $this->callback_args = "";	
			if(!$this->callback) $this->callback = array($this, 'extract');

			add_action('save_post', array($this, 'save_post'));
			add_action('admin_enqueue_scripts', array($this, 'add_admin_script'));
			
		}
		
		function add_admin_script() {
			wp_enqueue_script('jquery-ui-accordion');
		}

		function add() {
			add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		}
		
		function add_meta_boxes() {
			$post_types = (array)$this->post_type;
			
			foreach($post_types as $post_type)
				add_meta_box( 
					$this->id,
					$this->title,
					$this->callback,
					$post_type,
					$this->context,
					$this->priority,
					$this->callback_args
				);
		}
		
		/* Extract meta box content */
		function extract($args = array()) {
			$args = wp_parse_args( $args, array('id'=>'', 'edit_mark'=>true) );
			extract($args);

			$this->builders = new TN_Option_Builders();
			
			echo $this->before;
			$this->add_options($this->options, false, $id);
			echo $this->after;
			
			if($edit_mark):
			?><input name="edit" type="hidden" value="edit" /><?php
			endif;
		}	
		
		function add_options($options, $placeholder=false, $id="") {
			$prefix = $this->meta_prefix;
			$builders = $this->builders;
			
			if(!empty($id)) {
				if(is_array($id)) {
					$wanted = array_shift($id);
				} else {
					$wanted = $id;
					$id = '';
				}				
			} 
// tn_dump($options);

            // Sort by priority ASC, or if equal, by key ASC
            // foreach ($options as $key => $option) {
            //     if(!isset($option['priority']))
            //         $option['priority'] = 10;
            //     $_priority[$key]  = $option['priority'];
            //     $_key[$key] = $key;
            // }
            // array_multisort($_priority, SORT_ASC, $_key, SORT_ASC, $options);

			foreach($options as $index=>$option):
				// if specified an id, skip others
				if( isset($wanted) && $index != $wanted )
					continue;
				
				unset($wanted);

				// Repeat for a few times
				if( isset($option['callback']) && function_exists($option['callback'])) {
				      call_user_func( $option['callback'], $option );
				      continue;
				}

				// Repeat for a few times
				if( $option['type'] == 'repeat' && !empty($option['repeat_options']) ) {
					if(!empty($id)) {
						if(is_array($id)) {
							$wanted = array_shift($id);
						} else {
							$wanted = $id;
							$id = '';
						}
					} 

					$order = $option['order_by'] ? get_post_meta(get_the_id(), $option['order_by'], true) : null;

					if (!$order) {
						for ($i = 1; $i <= $option['repeat_times']; $i++) {
							$order[] = $i;
						}
					}

					foreach($order as $placeholder) {
						// if specified an id, skip others
						if( isset($wanted) && $placeholder != $wanted )
							continue;
			
						$this->add_options($option['repeat_options'], $placeholder, $id);
					}
					continue;
				}
				
				if($option['type'] == 'html') {
					$option['text'] = str_replace('%placeholder%', $placeholder, $option['text']);
				}	

				if(!isset($option['name'])) {
					$body_builder = $option['type']. '_builder';
					echo $builders->$body_builder($option);
					continue;
				}						
				
				// Add Prefix
				$option['name'] = $prefix.str_replace('%placeholder%', $placeholder, $option['name']);
				if(!isset($option['std']))
					$option['std'] = null;
				$option_value = $this->get_option($option['name'], $option['std']);
				if($option_value && !empty($option['html_allowed'])) $option_value = esc_html($option_value);
				
				$body_builder = $option['type']. '_builder';

				$wrapper = !empty($option['inline']) ? 'span' : 'div';
				ob_start();
				
				if(!empty($option['title'])):?>
				<<?php echo $wrapper; ?> class="ipopt-title tn-opt-title">
					<strong><?php echo $option['title']; ?></strong>
				</<?php echo $wrapper;?>>
				<?php endif; ?>
				
				<<?php echo $wrapper; ?> class="ipopt-body tn-opt-body">
				<?php  echo $builders->$body_builder($option, $option_value); ?>
				</<?php echo $wrapper; ?>>
				
				<?php if(isset($option['desc'])):?>
				<<?php echo $wrapper; ?> class="help ipopt-desc"><?php echo $option['desc']; ?></<?php echo $wrapper; ?>><?php endif;

				$body = ob_get_clean();
				
				if(!isset($option['inline']) || !$option['inline']):
					ob_start();?>

					<div class="ipopt tn-opt<?php if(isset($option['rowclass'])) echo ' '. esc_attr($option['rowclass']); ?>"<?php if(isset($option['rowstyle'])) echo ' style="'. esc_attr($option['rowstyle']). '"'; ?>>
					
					<?php echo $body;?>
					<div class="clear"></div>

					</div><?php 
				
					$body = ob_get_clean();
				endif;
								
				$body = apply_filters('tn_post_meta_html', $body, $option['name'], $option_value);

				echo $body;
				
			endforeach;
// tn_dump($this->properties);
			if($this->id) {
				wp_nonce_field('save', $this->id. '_nonce');				
			}
			
		}

		function get_option($option_name, $std){

			$post_id = empty($this->post_id) ? get_the_id() : $this->post_id;
			$meta_key = tn_get_key($option_name);
			$meta_data = get_post_meta($post_id, $meta_key, true);
// if( $post_id == 727 && ($meta_key == '_upt_thumb_settings' || $meta_key == '_upt_slider'))
// 	update_post_meta($post_id, $meta_key, '' );
// tn_dump($meta_key);
// echo $meta_data;
// tn_dump($option_name);
			if( $meta_data == '' ) 
				return $std;
			elseif($meta_key == $option_name)
				$option_value = $meta_data;
			else
				$option_value = tn_extract_meta_option_value($option_name, $meta_data, 1);
				
			return $option_value;
		}	

		/* When the post is saved, saves our custom data */
		function save_post( $post_id ) {
			global $post;
// tn_dump($this->properties);
			// verify if this is an auto save routine. 
			// If it is our form has not been submitted, so we dont want to do anything
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
				return $post_id;

		    if ( !isset($_POST[$this->id. '_nonce']) )
		        return;

			check_admin_referer('save', $this->id. '_nonce');

			// Check post types
			if ( isset($_POST['post_type']) && !in_array($_POST['post_type'], (array)$this->post_type) ) 
			{
				  return $post_id;
			}

			// Check permissions
			if ( isset($_POST['post_type']) && 'page' == $_POST['post_type'] ) 
			{
			  if ( !current_user_can( 'edit_page', $post_id ) )
				  return $post_id;
			}
			else
			{
			  if ( !current_user_can( 'edit_post', $post_id ) )
				  return $post_id;
			}
			
			// OK, we're authenticated: we need to find and save the data  
			$is_saving = isset($_POST['edit']) ? $_POST['edit'] : '';
			if(empty($is_saving))
				return;
				
			$this->update_options($this->options, $post_id);

		}

		function update_options($options, $post_id) {
			$prefix = $this->meta_prefix;
			$updated = array();

			foreach($options as $option) {
				if( $option['type'] == 'repeat' && !empty($option['repeat_options']) ) {
					$this->update_options($option['repeat_options'], $post_id);
					continue;
				}

				if( empty($option['name']) )
					continue;
				
				$meta_key = $prefix. tn_get_key($option['name']);

                if(isset($_POST[$meta_key])) {
					// there could be multiple post metas with same key, update once enough, don't duplicate updates.
					if( !in_array($meta_key, $updated) ) { 
	                    $value = $_POST[$meta_key];

	                    // Debug
	                    // if(1) {
	                    // 	tn_dump($meta_key);
	                    // 	tn_dump($option);
	                    // 	tn_dump($value);
	                    // }

						// Sanitize & Validate
	                    if(is_array($value) && function_exists($meta_key. '_sanitize')) {
	                    	$sanitize = $meta_key. '_sanitize';
	                    	$_POST[$meta_key] = $sanitize($value, $this->options);
	                    } else {
		                    if ( isset( $option['sanitize_callback'] ) && null !== $option['sanitize_callback'] ) {
		                        $_POST[$meta_key] = call_user_func( $option['sanitize_callback'], $value, $option );
		                    } elseif(method_exists('TN_Sanitize', $option['type'])) {
		                        $_POST[$meta_key] = TN_Sanitize::$option['type']($value, $option);
		                    }
	                    }

					    update_post_meta($post_id, $meta_key, $_POST[$meta_key]);
						$updated[] = $meta_key;
					}
                } else {
					// !isset($_POST[$meta_key], Checkbox unchecked
					delete_post_meta($post_id, $meta_key );
                }
			}
		}
	}
}
?>