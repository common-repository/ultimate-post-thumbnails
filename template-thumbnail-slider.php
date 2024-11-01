<?php 
/*	
Template of post thumbnail slider

Input:

	$thumbs	-	an array of thumbnails of the current post, 
				thumbnail id as key, thumbnail HTML (with or without link) as value
*/
				
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<?php if( count($thumbs) > 1 ): ?>

	<div id="upt-container" class="<?php upt_class();?>">
		<div style="<?php upt_slider_style(); ?>"<?php upt_slider_atts(); ?>>
		
			<div class="upt-slides">
				<?php foreach($thumbs as $thumb_id=>$thumb_html):?>
						<?php echo $thumb_html; ?>
				<?php endforeach;?>
			</div>
			
			<?php if(upt_has_direction_nav()): ?>
			<span class="upt-previous disabled"></span>
			<span class="upt-next"></span>
			<?php endif; ?>
			
		</div>
	</div>

<?php else:

	list($thumb_id, $thumb_html) = each($thumbs);
	echo $thumb_html; 

endif; ?>
