jQuery.noConflict();
    
jQuery(window).load(function() {
		if(jQuery(".upt-thumb-slider").data('lightbox')) {
			args = jQuery(".upt-thumb-slider").data('lightbox');
		} else if(jQuery(".upt-link-single").data('lightbox') ) {
			args = jQuery(".upt-link-single").data('lightbox');
		} else
			args = '';

	jQuery(".upt-link[data-upt-gal]").not('.clone .upt-link').prettyPhoto(args);
	jQuery(".upt-link-single[data-upt-gal]").prettyPhoto(args);

});