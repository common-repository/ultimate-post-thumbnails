(function() {
	
    tinymce.PluginManager.add('ultimate_post_thumbnails', function(editor, url) {
	
        function popup_options() {
            jQuery.magnificPopup.open({
              items: {
                src: ajaxurl + '?action=upt_shortcode_panel',
                type: 'ajax'
              },
              //modal:'true'
              closeOnBgClick:false,
              showCloseBtn:true

            });
		}
		
		editor.addButton('ultimate_post_thumbnails', {
            title: 'Thumbnail Slider',
            icon: 'icon dashicons-images-alt2',
            onclick: popup_options
        });
	
	});
})();