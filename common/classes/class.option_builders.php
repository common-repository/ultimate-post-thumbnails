<?php
/* Option Types
 *
 * editor
 * icons selector
 * control
 * select
 * text(box)
 * textarea
 * color selector
 * radio
 * upload(image)
 * multi
 * button
 * menu
 * icon
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('TN_Option_Builders')) {

    class TN_Option_Builders {
	
		function init($option) {
            // Extract option items
            if(isset($option['items']) && is_string($option['items']) && preg_match('/^\{(\w+)\}$/', $option['items'], $matches)) {
                $option['items'] = tn_get_list($matches[1]);
                if( isset($option['before_items']) ) {
                    $option['items'] = (array)$option['before_items'] + (array)$option['items'];
                }
				if( isset($option['after_items']) ) {
                    $option['items'] = (array)$option['items'] + (array)$option['after_items'];
                }
            }

            $option['disabled'] = isset($option['premium'])
                    && !( isset($option['premium_unlock']) && $option['premium_unlock'] ) ? ' disabled' : '';

			return $option;
		}
		
		function editor_builder($option, $option_value = null) {
		
            if(!isset($option_value))
                $option_value = $option['std'];
            
			$settings = isset($option['settings']) ? array_merge(array('teeny' => true,'textarea_rows' => 15,'tabindex' => 1), $option['settings']) : array();
							
            ob_start();
			wp_editor($option_value, $option['name'], $settings);
            return ob_get_clean();
		}
	
		function icons_select_builder($option, $option_value = null) {
			$option = self::init($option);
			
			$icon = empty($option_value) ? '' : '<i class="'. $option_value. '"></i>';
			
			$o = '<div class="icons-selector '. $option['class']. '" name="'. $option['name'].'"><span class="selected">'. $icon. '</span><span class="icon-sort-down"></span></div>';

			$option['class'] = 'hidden';
			$o .= self::text_builder($option, $option_value);
			
			$o .= '<div class="icons hidden"><ul>';
			foreach((array)$option['items'] as $icon_value) {
				$o .= '<li class="icon '. $icon_value. '" data-value="'. $icon_value. '"></li>';
			}
			$o .= '</ul></div>';
			
			return $o;
		}
		
        function control_builder($tag, $attr, $innerHTML = null) {
            $control = '<'. $tag;
            foreach ( $attr as $name => $value ) {
                $control .= ' '. $name. '="' . $value . '"';
            }
            if(!$innerHTML) {
                $control .= ' />';
                return $control;
            }
            $control .= '>'. $innerHTML. '</'. $tag. '>';
            return $control;
        }
        
        function select_builder($option, $option_value = null) {
			$option = self::init($option);
		
            if(!isset($option_value))
                $option_value = $option['std'];
            
            $id = $option['name'] ? ' id="'. $option['name']. '"' : '';
            $name = $option['name'] ? ' name="'. $option['name']. '"' : '';
            $class = isset($option['class']) ? ' class="'. $option['class']. '"' : ' class="regular-select"';
            $o =   '<select'. $id. $name. $class. $option['disabled']. '>';
            $append = '';

            foreach((array)$option['items'] as $value=>$text) {
                $attr = $option_value == $value ? ' selected="selected"' : '';

                if( isset($option['premium_choice']) 
                    && array_key_exists($value, (array)$option['premium_choice'])
                    && !( isset($option['premium_unlock']) && $option['premium_unlock'] )
                ) {
                    $attr = ' disabled';
                   $append = $option['premium_choice'][$value];
                }
                $o .= ' <option value="'. $value. '"'. $attr. '>'. $text. ' '. $append. '</option>';
            }
            $o .= '</select>';
            return $o;
        }
        
        function text_builder($option, $option_value = null) {
            $option = self::init($option);
            if(!isset($option_value))
                $option_value = $option['std'];
                
            $option_value = esc_html($option_value );
            $class = isset($option['class']) ? ' class="'. $option['class']. '"' : 'class="regular-text"';
            $o = '<input name="'. $option['name']. '" id="'. $option['name']. '" value="'. $option_value. '" type="text"'. $class. $option['disabled']. ' />';
            return $o;
        }
        
        function color_builder($option, $option_value = null) {
            $option = self::init($option);
            if(!isset($option_value))
                $option_value = $option['std'];
                
            $class = isset($option['class']) ? ' class="'. $option['class']. '"' : 'class="regular-text"';
            $o = '<div class="tn-color-preview"><div style="background-color:#'. $option_value. '"></div></div><input class="tn-color" name="'. esc_attr($option['name']). '" id="'. esc_attr($option['name']). '" value="'. $option_value. '" type="text"'. $class. $option['disabled']. ' />';
            return $o;
        }
         
        function textarea_builder($option, $option_value = null) {
            $option = self::init($option);
            if(!isset($option_value))
                $option_value = $option['std'];
                
            $class = isset($option['class']) ? ' class="'. $option['class']. '"' : 'class="regular-textarea"';
            $o = '<textarea name="'. $option['name']. '" id="'. $option['name']. '"'. $class. $option['disabled']. '>'. $option_value. '</textarea>';
            return $o;
        }
         
        function radio_builder($option, $option_value = null) {
            $option = self::init($option);
            if(!isset($option_value))
                $option_value = $option['std'];
                
            $class = isset($option['class']) ? $option['class'] : "regular-radio";
            $o = '';
            foreach((array)$option['items'] as $value=>$text) {
                $attr = $append = $extra_class ='';
                if( isset($option['premium_choice']) 
                    && array_key_exists($value, (array)$option['premium_choice'])
                    && !( isset($option['premium_unlock']) && $option['premium_unlock'] )
                ) {
                    $attr = ' disabled';
                   $append = ' '. $option['premium_choice'][$value];
                   $extra_class = ' tn-disabled';
                }

                $checked = $option_value == $value ? ' checked="checked"' : '';
                $o .= '<label class="'. $class. $extra_class. '""><input name="'. $option['name']. '" id="'. $option['name']. '" value="'. $value. '" type="radio" class="radiobutton" '. $checked. $attr. '/>'. $text. $append. '</label>';
            }
            return $o;
        }
          
        function checkbox_builder($option, $option_value = null) {
		
			$option = self::init($option);

            $option['text'] = isset($option['text']) ? $option['text'] : $option['title'];
                
            $class = isset($option['class']) ? ' class="'. $option['class']. '"' : 'class="regular-checkbox"';
                $checked = $option_value ? ' checked="checked"' : '';
                $o = '<label '. $class. '><input name="'. $option['name']. '" id="'. $option['name']. '" value="1" type="checkbox" class="checkbox" '. $checked. $option['disabled']. '/>'. $option['text']. '</label>';
            return $o;
        }
        
        function upload_builder($option, $option_value = null) {
            $option = self::init($option);
            $href = $extra_class = '';
            if($option['disabled']) {
                $href = ' href="javascript:void(0);"';
                $extra_class = ' tn-disabled';
            }

            if( !isset($option_value) && isset($option['std']) )
                $option_value = $option['std'];
                            
            $option['name'] = esc_html($option['name']);
            
            // Create input that holds option value, make it hidden
            $attr = array(
                'id' => $option['name'],
                'name' => $option['name'],
                'value' => $option_value,
                'type' => 'text',
                'class' => 'hidden tn-media-id ',
            );
            $attr['class'] .= isset($option['class']) ? $option['class'] : 'regular-text';
            $o = $this->control_builder('input', $attr);
                    
            // Create preview image
            if($option_value) {
                if(is_numeric($option_value)) {
                    $image = wp_get_attachment_image_src( $option_value, 'large' );
                    $url = $image[0];
                } else {
                    $url = $option_value;
                }
                
                $o .= "<img class='tn-preview-image' src='$url' />";
            }

            // Create upload button
            if(!isset($option['upload_button']) || $option['upload_button']) {
                if(!isset($option['upload_txt']))
                    $option['upload_txt'] = esc_html__('Upload', 'themenow-framework');
                $button_class = isset($option['btn_class']) ? $option['btn_class'] : 'button upload_button';
                $button_class .= ' tn-upload-media';
                $o .= '<a id="'. $option['name']. '_button" class="'. $button_class. $extra_class. '"'. $href. '>'. $option['upload_txt']. '</a>';                
            }

            if(!isset($option['remove_button']) && isset($option['no_remove']) ) {
                $option['remove_button'] = false;
            }

            // Create remove button
            if(!isset($option['remove_button']) || $option['remove_button']) {
				if(!isset($option['remove_txt']))
					$option['remove_txt'] = esc_html__('Remove', 'themenow-framework');
                $button_class = isset($option['btn_class']) ? $option['btn_class'] : 'button';
                $button_class .= ' tn-remove-media';
                $o .= '<a class="'. $button_class. $extra_class. '"'. $href. '>'. $option['remove_txt']. '</a>';
            }
            
            return $o;
        }
        
        function multi_builder($option) {
            $o = isset($option['text']) ? $option['text'] : '';
            return $o;
        }
        
        function html_builder($option) {
            $o = isset($option['text']) ? $option['text'] : '';
            return $o;
        }
        
        function icon_builder($option) {
            $before = isset($option['before']) ? $option['before'] : '';
            $after = isset($option['after']) ? $option['after'] : '';
            $class = isset($option['class']) ? ' class="'. $option['class']. '"' : 'class="icon"';
            $o = '<span '. $class. '></span>';
            $o .= isset($option['text']) ? $option['text'] : '';
            return $before. $o. $after;
        }
        
        function button_builder($option, $option_value = null) {
            if(!isset($option_value))
                $option_value = $option['std'];
                
            $class = $option_value ? 'tn-button tn-button-status button-status-on' : 'tn-button tn-button-status';
            $class .= isset($option['class']) ? ' '. $option['class'] : '';
            $class = ' class="'. $class. '"';
            $o = '<span '. $class. '>'. $option['text']. '</span>';
            $option['class'] = 'hidden tn-button';
            $option['class'] .= isset($option['class']) ? ' '. $option['class'] : '';
            $option['items'] = array(0, 1);
            if(!empty($option['name']))
                $o .= $this->select_builder($option, $option_value);
            return $o;
        }
        
    }

}
?>