(function($) {
    "use strict";

    $(document).ready(function() {

        $('.upt-images-thumb').sortable({
            handle: 'img',
            stop: function() {
                $('#_upt_thumbnails_order').val($(this).sortable('toArray').toString().replace(/[^\d,]/g, ''));
            }
        });

        /**
         * Toggle thumbnail and settings
        */
        $(document).on('click', '.upt-images-thumb li img', function() {
            var thumb_entry = $(this).parent();

            var id = thumb_entry.attr('id').replace(/[^\d,]/g, '');
            if(thumb_entry.hasClass('in-active')) {
                thumb_entry.removeClass('in-active');
                thumb_entry.siblings().addClass('in-active');
                $('.upt-images .upt-image-entry-' + id).show().siblings().hide();
                $('.upt-options').show();
            } else {
                thumb_entry.siblings().toggleClass('in-active');
                $('.upt-images .upt-image-entry-' + id).toggle().siblings().hide();
                $('.upt-options').toggle();
            }
        });

        /**
         * Delete images
         * 
         * Entry isn't deleted actually, but got reset to initial status, so that can be added later again
         * 
         */
        $('.upt-images-thumb li .del').click(function() {
            var thumb_entry = $(this).parent();
            var id = thumb_entry.attr('id').replace(/[^\d,]/g, '');

            // Reset siblings if active item got deleted
            if(!thumb_entry.hasClass('in-active')) {
                thumb_entry.siblings().removeClass('in-active');
            }

            thumb_entry.addClass('hidden').find('img').remove();
            $('.upt-images .upt-image-entry-' + id).hide().addClass('upt-entry-available').find('.tn-media-id').val(0).next('img').remove();

        });

        // Show/hide options for animation 'slide'
        $('.upt-slider-animation').each(function() {
            if ($(this).val() == 'slide')
                $(this).parents('.ipopt').next('.hidden').show().next('.hidden').show();
        });

        $('.upt-slider-animation').change(function() {
            $(this).parents('.ipopt').next('.hidden').toggle('normal');
        });

        $('.tn-linkto').each(function() {
            if ($(this).val() == 'custom')
                $(this).parents('.tn-opt').next('.hidden').show();
        });

        $('.tn-linkto').change(function() {
            $(this).parents('.tn-opt').next('.hidden').toggle($(this).val() == 'custom');
        });

        $('.upt-reveal-lightbox-settings').each(function() {
            if ($(this).val() == '_lightbox' || $(this).val() == '_global_lightbox')
                $(this).parents('.tn-opt').next('.hidden').show();
        });

        $('.upt-reveal-lightbox-settings').change(function() {
            $(this).parents('.tn-opt').next('.hidden').toggle($(this).val() == '_lightbox' || $(this).val() == '_global_lightbox');
        });
    });

})(jQuery);