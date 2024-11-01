/* Create thumbnail link
 *
 * HTML code change is involved here, in which case, if Isotope loaded after
 * this script, it won't work, reason unknown
 *
 * So to say, we need to ensure this script loaded after Isotope.
 */

jQuery.fn.upt_span2link = function(before, after) {
	data_link = jQuery(this).attr('data-link-atts');
	if(!data_link)
		return true; // equal to 'continue'
	
	data_link = JSON.parse(data_link);
	var link = jQuery(before + jQuery(this).html() + after);
	if(data_link.hasOwnProperty('class')) {
		link.addClass(data_link.class);
		delete data_link.class;
	}
	jQuery.each(data_link, function(key, value) {
		link.attr(key, value);
	});
	jQuery(this).replaceWith(link);
};

jQuery(document).ready(function() {

	// Multiple thumbnails
	jQuery('.upt-container').each(function() {
		parent_a = jQuery(this).parents('a');
		upt_links = jQuery('span.upt-link', this);

		if(parent_a && parent_a.length && upt_links.length) {
			// if feature image is wrapped by an <a> outside, remove it and use it to wrap each images
			wrapper = parent_a[0].outerHTML.split(jQuery(this)[0].outerHTML);
			upt_links.each(function() {jQuery(this).upt_span2link(wrapper[0], wrapper[1]);});
			parent_a.replaceWith(jQuery(this));
			//content.unwrap().wrap(wrapper);
		} else if(upt_links.length) {
			// Feature image isn't wrapped by an <a>, life is much easier
			upt_links.each(function() {jQuery(this).upt_span2link('<a>', '</a>');});
		}
		
	});
	
	// Single thumbnail - since all span.upt-link in a thumbnail slider has been turned to link in above process, the rest must be single ones
	jQuery('span.upt-link').each(function() {
		parent_a = jQuery(this).parents('a');
		
		if(parent_a && parent_a.length) {
			parent_a.addClass('upt-link-single');
			// if feature image is wrapped by an <a> outside, remove it and use it to wrap each images
			wrapper = parent_a[0].outerHTML.split(jQuery(this)[0].outerHTML);
			parent_a.replaceWith(jQuery(this));
			jQuery(this).upt_span2link(wrapper[0], wrapper[1]);
			//content.unwrap().wrap(wrapper);
		} else {
			// Feature image isn't wrapped by an <a>, life is much easier
			jQuery(this).upt_span2link('<a class="upt-link-single">', '</a>');
		}
		
	});
	
});

jQuery(window).load(function() {
	jQuery('.upt-slides .upt-item:first-child').css('display', 'none');

	jQuery('.upt-thumb-slider').each(function() {
		if(typeof jQuery(this).attr('data-slider') !== 'undefined') {
			args = jQuery(this).data('slider');
		} else
			args = '';

		// If an ajax loading GIF exists, remove it when all images loaded
		jQuery(this).css('background-image', 'none');
		
		if(args['controlNav'] && args['controlNav'] == 'thumbnails')
			args['start'] = function(slider) {
				slider.parent().find('.flex-control-thumbs img').addClass(slider.find('img:first').attr('class'));
			};

		jQuery(this).flexslider(args).flexsliderManualDirectionControls({
			previousElementSelector: ".upt-previous",
			nextElementSelector: ".upt-next"
			// disabledStateClassName: "alternativeDisabledClass"
		});
	});

});