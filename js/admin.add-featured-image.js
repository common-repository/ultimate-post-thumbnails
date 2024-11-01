var ds = ds || {};

/**
 * array shift or pop for jQuery object 
 */
(function( $ ) {
    $.fn.pop = function() {
        var top = this.get(-1);
        this.splice(this.length-1,1);
        return top;
    };

    $.fn.shift = function() {
        var bottom = this.get(0);
        this.splice(0,1);
        return bottom;
    };
})( jQuery );

/**
 * Add Featured Images
 */
( function( $ ) {
	var media;

	ds.media = media = {
		buttonId: '#upt-add-featured-image',
		availableSlot: '.upt-thumbnails .upt-image-thumb.hidden',
		availableIndex: '.upt-thumbnails .upt-entry-available',

		frame: function() {
			if ( this._frame )
				return this._frame;

			this._frame = wp.media( {
				title: 'Select Your Images',
				button: {
					text: 'Choose'
				},
				multiple: true,
				library: {
					type: 'image'
				}
			} );

			this._frame.on( 'ready', this.ready );

			this._frame.state( 'library' ).on( 'select', this.select );

			return this._frame;
		},

		ready: function() {
			$( '.media-modal' ).addClass( 'no-sidebar smaller' );
		},

		select: function() {
			var settings = wp.media.view.settings,
				selection = this.get( 'selection' );

			selection.map( media.showAttachmentDetails );
		},

		showAttachmentDetails: function( attachment ) {
			var thumb_entry = $( media.availableSlot ),
				thumb = thumb_entry.shift();
			var option_entry = $( media.availableIndex ),
				option = option_entry.shift();
	
			if(!option || !thumb)
				return;
			
			// Create new image options entry
			$( 'input.tn-media-id', option ).attr( 'value', attachment.get( 'id' ) );
			var sizes = attachment.get( 'sizes' );
            var url = 'large' in sizes ? sizes.large.url : attachment.get( 'url' );
			$( '.tn-media-id', option).after("<img class='tn-preview-image' src='" + url + "' />");
			$( option ).removeClass( 'upt-entry-available' );
			
			// Create new image thumbnail
			var url = 'thumbnail' in sizes ? sizes.thumbnail.url : attachment.get( 'url' );
			$( '.del', thumb).before("<img class='attachment-thumbnail size-thumbnail' src='" + url + "' />");
			$( thumb ).removeClass( 'hidden' ).removeClass( 'in-active' );
		},

		init: function() {
			$( media.buttonId ).on( 'click', function( e ) {
				e.preventDefault();

				media.frame().open();
			});
		}
	};

	$( media.init );
} )( jQuery );
