<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('TN_Option_Table')) {

    class TN_Option_Table {
        var $table;
        var $colnum;
        
        function __construct($table) {
            $this->colnum = 2;
            $this->table = $table;
        }
        
        function __get($name) {
            if(isset($this->table[$name]))
                return $this->table[$name];
            else
                return;
        }
        
        function default_vals() {
            $vals = array();
            
            foreach($this->options as $option) {
                if(isset($option['std']) && isset($option['name']))
                    if(preg_match('/^([^\[\]]+)(\[.+\])$/', $option['name'], $matches)) { 
                        // throw error: option name like name[key1][key2][...] isn't allowed any more
                    } else
                        $vals[$option['name']] = $option['std'];
            }
            
            return $vals;
        }

        function compare($a, $b) {
            if(!isset($a['priority']))
                $a['priority'] = 10;
            if(!isset($b['priority']))
                $b['priority'] = 10;

            // if ($a['priority'] == $b['priority']) {
            //    return 0;
            // }
            return $a['priority'] > $b['priority'] ? 1 : -1;
        }
        
        /* class based logic
         > ------------------------------------------------------
         > option = createOption($args);
         >
         > if depth increase
         >      anchor.push(prev_option);
         >      prev_option.add($option);
         > elseif depth decrease
         >      parent_option = anchor.pop();
         >      parent_option.add($option);
         > else depth equal
         >      parent_option.add($option);
         > endif
         >
         > prev_option = option;
         */
         function build() {
            $table = $this->table;
            $builders = empty($table['builders']) ? 'TN_Option_Builders' : $table['builders'];
            if(empty($builders))
                return;
            $builders = new $builders();
            
            if(empty($table['source']))
                $table['source'] = 'general';

            ob_start();
            echo "<table><tbody>";
            $i = 0;
            $alt ='';

            // Sort by priority ASC, or if equal, by key ASC
            foreach ($table['options'] as $key => $option) {
                if(!isset($option['priority']))
                    $option['priority'] = 10;
                $_priority[$key]  = $option['priority'];
                $_key[$key] = $key;
            }
            array_multisort($_priority, SORT_ASC, $_key, SORT_ASC, $table['options']);

            foreach($table['options'] as $option) {
                
                if( !isset($option['depth']) || $option['depth'] < 0) 
                    $option['depth'] = 0;
                
				$value = isset($option['name']) ? $this->get_option($option['name']) : null;
                    
                $body_builder = $option['type']. '_builder';
                
                $option['before'] = isset($option['before']) ? $option['before'] : '';
                $option['after'] = isset($option['after']) ? $option['after'] : '';
                $option['body'] = $option['before']. $builders->$body_builder($option, $value). $option['after'];
                
				// Add a button for toggle enable/disable when match options like "styles[]...[val]" or "custom[]...[val]"
				if( isset($option['name']) && preg_match('/^(styles|custom)\[.+\[val\]$/', $option['name']) ) {
					//convert option name "styles[#header][color][val]" to "styles[#header][color][enabled]"
					$extra_option = array('name'=>str_replace('[val]', '', $option['name']). '[enabled]', 
								'text'=>' ', 
								'std'=>0,
								'type'=>'button'
							);
					$extra_option_val = $this->get_option($extra_option['name']);
				}
				$button = empty($extra_option) ? null : $builders->button_builder($extra_option, $extra_option_val);
				
				if( empty($option['title']) )
					$title = '';
				elseif( empty($button) )
					$title = '<th>'. $option['title']. '</th>';
				else
					$title = '<th class="has-check-btn">'. $button. $option['title']. '</th>';

				$class = 'iaopt tn-opt depth-'. $option['depth'];
                $class .= ($i%2 == 0) ? ' even' : ' odd';
                $optname = isset($option['name']) ? preg_replace('/\[.*$/', '', $option['name']) : null;
                $class .= $optname ? ' row_has_option row_'. $optname : ' row_no_options';
                $class .= isset($option['rowclass']) ? ' '. $option['rowclass'] : '';
                $desc = empty($option['desc']) ? '' : '<p class="desc">'. $option['desc']. '</p>';
                $colspan = $optname ? '' : ' colspan=2';
                
                if(isset($option['alt']))
                    $alt = empty($alt) ? 'alt' : '';
                
                $class = esc_attr($class);
                
                if(!isset($prev_option))
                    echo "<tr class='$class $alt'>". $title. '<td'. $colspan. '>'. $option['body']. $desc;
                elseif($option['depth'] > $prev_option['depth'])
                    echo "<table><tbody><tr class='$class $alt'>". $title. '<td>'. $option['body']. $desc;
                elseif($option['depth'] < $prev_option['depth'])
                    echo "</tbody></table></td></tr><tr class='$class $alt'>". $title. '<td>'. $option['body']. $desc;
                elseif(isset($option['inline']))
                    echo "</td><td class='$class $alt'>". $option['body']. $desc;
                else
                    echo "</td></tr><tr class='$class $alt'>". $title. '<td'. $colspan. '>'. $option['body']. $desc;
                    
                $prev_option = $option;
                $i++;
            }
            
            for( $d = $prev_option['depth']; $d >= 1; $d -- )
                echo '</tbody></table></td></tr>';
            echo '</tbody></table>';
            
            unset($prev_option);
            return ob_get_clean();
        }
        
        // Get option's value
		function get_option($option_name) {
			$option_value = null;
			
			$values = get_option($this->data_source);
            $values = (array)$values;

            if(!preg_match('/^([^\[\]]+)(\[([^\[\]]+)\])(\[([^\[\]]+)\])?(\[([^\[\]]+)\])?/', $option_name, $matches))
                return isset($values[$option_name]) ? $values[$option_name] : null;
				
            $keys = array();
            foreach($matches as $index=>$match) {
                if($index%2 == 0)
                    continue;
                $keys[] = $match;
            }	
			if(isset($values[$keys[0]])) {
				$option_value = $values[$keys[0]];
				for($i=1;$i<count($keys);$i++) {//showinfo($keys[$i]);
					if(tn_array_isset($keys[$i], $option_value))
						$option_value = $option_value[$keys[$i]];
					else
						return;
				}
			}

			return $option_value;
		}
    }

}    
?>
