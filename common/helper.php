<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if (!function_exists('tn_generate_html_element')) {
	function tn_generate_html_element($tag, $atts = array(), $content = '') {

		$attributes = '';
		foreach ($atts as $attr => $value) {
			if(is_bool($value)) {
				$attributes .= ' ' . $attr;

			} elseif ($value != '') {
				$value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = '<' . $tag . $attributes . '>';

		if ($content !== '') {
			$item_output .= $content === true ? '' : $content;
			$item_output .= '</' . $tag . '>';
		}

		return $item_output;
	}
}

if (!function_exists('tn_sanitize_options')) {
	function tn_sanitize_options($options) {

		foreach ($options as &$option) {
			if (!is_array($option)) {
				$option = array('type' => 'html', 'text' => $option);

			} elseif (isset($option['type']) && $option['type'] == 'repeat' && !empty($option['repeat_options'])) {
				$extracted = array();

				$order = $option['order_by'] ? get_post_meta(get_the_id(), $option['order_by'], true) : null;

				if (!$order) {
					for ($i = 1; $i <= $option['repeat_times']; $i++) {
						$order[] = $i;
					}
				}

				foreach ($order as $index) {
					$extracted[$index] = $option['repeat_options'];
					tn_replace_options_name('%placeholder%', $index, $extracted[$index]);
				}
				$option = tn_sanitize_options($extracted);

			} elseif(!isset($option['name']) && !isset($option['type'])) {
				// No name and type found, $option is a group of options
				$option = tn_sanitize_options($option);
			}
		}
		return $options;
	}
}

if (!function_exists('tn_replace_options_name')) {
	function tn_replace_options_name($search, $replace, &$options) {
		if (!is_array($options)) {
			$options = (array) $options;
		}

		foreach ($options as &$option) {
			if (isset($option['name'])) {
				$option['name'] = str_replace($search, $replace, $option['name']);
			} elseif(is_string($option)) {
				$option = str_replace($search, $replace, $option);
			}
		}
	}
}

if (!function_exists('tn_str_replace')) {
	function tn_str_replace($find, $replace, $array) {
		if (!is_array($array)) {
			return str_replace($find, $replace, $array);
		}
		$newArray = array();
		foreach ($array as $key => $value) {
			$newArray[$key] = tn_str_replace($find, $replace, $value);
		}
		return $newArray;
	}
}

if (!function_exists('tn_json_encode')) {
	// Used in function "get_option" in class.option_table.php
	// reference: http://www.zomeoff.com/php-isset-and-multi-dimentional-array/
	function tn_json_encode($array) {
		if (!is_array($array)) {
			return false;
		}

		foreach ($array as $param => $value) {
			$value = $value == 'true' || $value == 'false' || is_numeric($value) ? $value : '"' . $value . '"';
			$json[] = '"' . $param . '":' . $value;
		}
		$json = '{' . implode(',', $json) . '}';

		return $json;
	}
}

if (!function_exists('tn_array_isset')) {
	// Used in function "get_option" in class.option_table.php
	// reference: http://www.zomeoff.com/php-isset-and-multi-dimentional-array/
	function tn_array_isset($key, $array) {
		if (!is_array($array)) {
			return false;
		}

		if (isset($array[$key])) {
			return true;
		}

		return array_key_exists($key, $array);
	}
}

// Give string like 'a,b,c' and array '$array', return true if '$array[a][b][c]' exists
if (!function_exists('tn_array_key_exists')) {
	function tn_array_key_exists($keys, array $array, $matchall = true) {
		if (!is_array($keys)) {
			$keys = explode(',', $keys);
		}

		$blnFound = array_key_exists(array_shift($keys), $array);

		if ($blnFound && (count($keys) == 0 || !$matchall)) {
			return true;
		}

		if (!$blnFound && count($keys) == 0 || $matchall) {
			return false;
		}

		return tn_array_key_exists($keys, $array, $matchall);
	}
}

// Give string like 'key_name[a][b][c]', return 'key_name' for offset=0, or 'a' for offset=1
if (!function_exists('tn_get_key')) {
	function tn_get_key($string, $offset = 0) {

		if (!preg_match('/^([^\[\]]+)(\[([^\[\]]+)\])(\[([^\[\]]+)\])?(\[([^\[\]]+)\])?/', $string, $matches)) {
			return $string;
		}

		$keys = array();
		$result = '';
		foreach ($matches as $index => $match) {
			if ($index % 2 == 0) {
				continue;
			}

			$keys[] = $match;
		}

		if ($offset) {
			while ($offset > 0) {
				array_shift($keys);
				$offset--;
			}
		}

		return $keys[0];
	}
}

/**
 * Giving an array '$data', and $keys like 'a[b][c][d]' or 'a,b,c,d' or array('a', 'b', 'c', 'd')
 * return $data['a']['b']['c']['d'] or $data['b']['c']['d'] with offset=1
 *
 * @param
 * @param
 */
if (!function_exists('tn_extract_meta_option_value')) {
	function tn_extract_meta_option_value($keys, array $data, $offset = 0) {
		if (empty($data)) {
			return;
		}

		if (!is_array($keys)) {
			if (preg_match('/^([^\[\]]+)(\[([^\[\]]+)\])(\[([^\[\]]+)\])?(\[([^\[\]]+)\])?/', $keys, $matches)) {
				$keys = array();
				foreach ($matches as $index => $match) {
					if ($index % 2 == 0) {
						continue;
					}

					$keys[] = $match;
				}
			} else {
				$keys = explode(',', $keys);
			}
		}

		// bypass a number of keys according to $offset
		if ($offset) {
			while ($offset > 0) {
				array_shift($keys);
				$offset--;
			}
		}

		$result = '';
		if (isset($data[$keys[0]])) {
			$result = $data[$keys[0]];
			for ($i = 1; $i < count($keys); $i++) {
				if (tn_array_isset($keys[$i], $result)) {
					$result = $result[$keys[$i]];
				} else {
					return;
				}

			}
		}

		return $result;
	}
}

if (!function_exists('tn_dump')) {
	function tn_dump($v = '', $c = "&nbsp;&nbsp;&nbsp;&nbsp;", $in = -1, $k = null) {
		echo tn_pretty($v, $c, $in, $k);
	}
}

if (!function_exists('tn_pretty')) {
	function tn_pretty($v = '', $c = "&nbsp;&nbsp;&nbsp;&nbsp;", $in = -1, $k = null) {
		$r = '';
		if (in_array(gettype($v), array('object', 'array'))) {
			$r .= ($in != -1 ? str_repeat($c, $in) : '') . (is_null($k) ? '' : "$k: ") . '<br>';
			foreach ($v as $sk => $vl) {
				$r .= tn_pretty($vl, $c, $in + 1, $sk) . '<br>';
			}
		} else {
			$r .= ($in != -1 ? str_repeat($c, $in) : '') . (is_null($k) ? '' : "$k: ") . (is_null($v) ? '&lt;NULL&gt;' : "<strong>". esc_html($v). "</strong>");
		}
		return $r;
	}
}

if (!function_exists('tn_array_merge')) {
	function tn_array_merge(array &$array1, array &$array2) {
		$merged = $array1;

		foreach ($array2 as $key => &$value) {
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = tn_array_merge($merged[$key], $value);
			} else {
				$merged[$key] = $value;
			}

		}

		return $merged;
	}
}

if (!function_exists('tn_array_unique')) {
	function tn_array_unique($root_array) {
		if (!is_array($root_array)) {
			return $root_array;
		}

		foreach ($root_array as &$child_array) {
			$child_array = serialize($child_array);
		}

		$root_array = array_unique($root_array);

		foreach ($root_array as &$child_array) {
			$child_array = unserialize($child_array);
		}

		return $root_array;
	}
}

if (!function_exists('tn_get_list')) {
	function tn_get_list($type) {
		$func = 'tn_list_' . $type;
		if (function_exists($func)) {
			return $func();
		}

		global $tn_cache;

		if (!isset($tn_cache)) {
			$tn_cache = new TN_Cache();
		}

		$method = 'get_' . $type;
		if (method_exists($tn_cache, $method)) {
			return $tn_cache->$method();
		}

		if (taxonomy_exists($type)) {
			$method = 'get_term_list';
			$parameter = $type;
		} elseif (post_type_exists($type)) {
			$method = 'get_post_list';
			$parameter = $type;
		} else {
			return;
		}

		return isset($parameter) ? $tn_cache->$method($parameter) : $tn_cache->$method();
	}
}

if (!function_exists('tn_substr')) {
	//get sub string
	function tn_substr($input, $start, $length, $end = '...') {

		$text = substr($input, $start, $length);
		$len = strlen($text);
		$i = 0;

		if ($len < $length) {
			return $text;
		}

		if ($len > 0 && !seems_utf8($text[$len - 1])) {

			for ($i = 0; $i > -3; $i--) {

				if ($len < 3) {
					return '';
				}

				if (!seems_utf8($text[$len - 3] . $text[$len - 2] . $text[$len - 1])) {
					$len--;
				} else {
					break;
				}

			}

		}

		if ($i != 0) {
			return substr($text, 0, $i) . $end;
		} else {
			return $text . $end;
		}

	}
}

/**
 * Tell if a size already exists
 *
 * @param $s, should be an array of ('width'=>..., 'height'=>..., 'crop'=>...)
 * @return (string) size name if exist, (bool) false if not
 */
if (!function_exists('tn_image_size_exists')) {
	function tn_image_size_exists($s) {
		if(!is_array($s) || !isset($s[0]) || !isset($s[1]) || !is_numeric($s[0]) || !is_numeric($s[1]))
			return false;

		// Cropped?
		if(!isset($s[2]))
			$s[2] = false;

		$sizes = tn_get_image_sizes();
		foreach ($sizes as $name => $size) {
			if($size == $s)
				return $name;
		}
		return false;
	}
}

if (!function_exists('tn_get_image_sizes')) {
	function tn_get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
				$sizes[ $_size ][0]  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ][1] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ][2]   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					0  => $_wp_additional_image_sizes[ $_size ]['width'],
					1 => $_wp_additional_image_sizes[ $_size ]['height'],
					2   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}

		return $sizes;
	}
}

if (!function_exists('tn_get_image_size')) {

	function tn_get_image_size($s) {
		$sizes = tn_get_image_sizes();

		if ( isset( $sizes[ $s ] ) ) {
			return $sizes[ $s ];
		}

		return $s;
	}
}

if (!function_exists('tn_enqueue_style')) {

	function tn_enqueue_style($style_name) {

		$type = defined('TN_DCSS') ? strtolower(TN_DCSS) : 'css';
		if ($type == 'less') {
			add_filter('style_loader_tag', 'tn_fix_less_type', 5, 2);
		}

		switch (strtolower($style_name)) {
			case 'admin-page':
				wp_enqueue_style('tn-admin-page', TN_COMMON_URI . '/' . $type . '/admin-page.' . $type, array(), TN_COMMON_VERSION);
				break;
			case 'options':
				wp_enqueue_style('tn-options', TN_COMMON_URI . '/' . $type . '/options.' . $type, array(), TN_COMMON_VERSION);
				break;
			default:
				break;
		}
	}
}

if (!function_exists('tn_fix_less_type')) {
	// Turn style 'type' to 'text/less' for style files of *.less
	function tn_fix_less_type($tag, $handle) {
		global $wp_styles;
		$match_pattern = '/\.less$/U';
		if (preg_match($match_pattern, $wp_styles->registered[$handle]->src)) {
			$handle = $wp_styles->registered[$handle]->handle;
			$media = $wp_styles->registered[$handle]->args;
			$href = $wp_styles->registered[$handle]->src . '?ver=' . $wp_styles->registered[$handle]->ver;
			$rel = isset($wp_styles->registered[$handle]->extra['alt']) && $wp_styles->registered[$handle]->extra['alt'] ? 'alternate stylesheet' : 'stylesheet';
			$title = isset($wp_styles->registered[$handle]->extra['title']) ? "title='" . esc_attr($wp_styles->registered[$handle]->extra['title']) . "'" : '';

			$tag = "<link rel='stylesheet' id='$handle' $title href='$href' type='text/less' media='$media' />";
		}
		return $tag;
	}
}

/**
 * Register metabox
 *
 * This function may only be used within hook "after_setup_theme"
 * 
 * @return void
 **/
if (!function_exists('tn_register_metabox')) {
	function tn_register_metabox($metabox) {
		$metabox = new TN_Meta_Box($metabox);
		$metabox->add();
	}
}

if (!function_exists('tn_create_shortcode_panel')) {
	function tn_create_shortcode_panel($shortcode) {
		$prefix = isset($shortcode['prefix']) ? $shortcode['prefix'] : 'tn_';
		?>
		<div id="tn-shortcode-options">
			<h2><?php echo $shortcode['title'];?></h2>
			<table  border="0" cellpadding="4" cellspacing="0" class="form-table">
			<?php
			foreach ($shortcode['options'] as $option):
			$builders = new TN_Option_Builders();
			$option_builder = $option['type'] . '_builder';?>

					<tr class="isopt tn-opt">
						<th class="isopt-title">
							<strong><?php echo $option['title'];?></strong>
						</th>

						<td class="isopt-body">
							<?php echo $builders->$option_builder($option);
							if (isset($option['desc'])): ?>
								<span class="isopt-desc"><?php echo $option['desc'];?></span>
							<?php endif;?>
					</td>
				</tr>

			<?php endforeach;?>
			</table>

			<div>
			<button type="submit" class="tn-button-primary" id="tn-insert-shortcode" name="<?php echo $prefix . $shortcode['name']?>">
			<?php $label = $shortcode['labels']['insert_shortcode'];
				echo $label ? $label : esc_html__('Undefined Text', 'themenow-framework');?>
			</button>
			</div>
		</div>
		<?php
	}
}
?>