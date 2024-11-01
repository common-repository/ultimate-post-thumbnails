jQuery.noConflict();

jQuery(document).ready(function() {

    jQuery(document).on('click', '.icons-selector', function() {
        jQuery(this).next().next('.icons').toggle();
    });

    jQuery(document).on('click', '.icons li', function() {
        var icon_name = jQuery(this).data('value');
        var icon = "<i class='" + jQuery(this).data('value') + "'></i>";
        jQuery(this).parents('.icons').hide().prev('input').val(icon_name).prev('.icons-selector').attr('data-value', icon_name).find('.selected').html(icon);
    });

    jQuery(document).on('click', '#tn-insert-shortcode', function() {
        var name = jQuery(this).attr('name');
        var shortcode = '[' + name;

        var options = '';
        var content = false;
        jQuery('.isopt-control').each(function() {
            var opt_name = jQuery(this).attr('name');
            if (typeof jQuery(this).attr('data-value') !== 'undefined') {
                opt_value = jQuery(this).attr('data-value');
            } else
                opt_value = jQuery(this).val();

            if (opt_name == 'content') {
                content = opt_value;
            } else if (jQuery.trim(opt_value).length > 0)
                options += opt_name + '="' + opt_value + '" ';
        });

        shortcode += ' ' + options + ']';

        if (content)
            shortcode += content + '[/' + name + ']';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        var magnificPopup = jQuery.magnificPopup.instance;
        magnificPopup.close();
    });

    jQuery('.tn-upload-media').click(function(e) {
        if(jQuery(this).hasClass('tn-disabled'))
            return;

        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = jQuery(this);
        var parent = jQuery(this).parent();

        // Add Image
        wp.media.editor.send.attachment = function(props, attachment) {
            jQuery('input', parent).val(attachment.id);
            var url = 'large' in attachment.sizes ? attachment.sizes.large.url : attachment.url;
            button.prev('img').remove().end().before("<img class='tn-preview-image' src='" + url + "' />");
            wp.media.editor.send.attachment = send_attachment_bkp;
        };
        
        wp.media.editor.open(button);
        return false;
    });

    jQuery('.tn-remove-media').click(function(e) {
        if(jQuery(this).hasClass('tn-disabled'))
            return;
    
        var parent = jQuery(this).parent();
        parent.find('input').val(0).end().find('img').remove();

        return false;
    });

    jQuery(document).on('click', '.tn-button-status', function() {
        if (jQuery(this).hasClass('button-status-on')) {
            // Turn off
            jQuery(this).removeClass('button-status-on');
            opt = jQuery(this).next('select');
            if (opt.length) {
                jQuery('option[value=1]', opt).removeAttr('selected');
                jQuery('option[value=0]', opt).attr('selected', 'selected');
            }

        } else {
            // Turn on
            jQuery(this).addClass('button-status-on');
            opt = jQuery(this).next('select');
            if (opt.length) {
                jQuery('option[value=0]', opt).removeAttr('selected');
                jQuery('option[value=1]', opt).attr('selected', 'selected');
            }
        }
    });

    jQuery('.tn-color').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            jQuery(el).val(hex);
            jQuery(el).ColorPickerHide();
        },
        onBeforeShow: function() {
            jQuery(this).ColorPickerSetColor(this.value);
            iwakc_input = jQuery(this);
            iwakc_previewer = jQuery(this).prev().find('div');
        },
        onChange: function(hsb, hex, rgb, el) {
            iwakc_input.val(hex);
            iwakc_previewer.css('background-color', '#' + hex);
        }
    }).bind('keyup', function() {
        jQuery(this).ColorPickerSetColor(this.value);
    });

    jQuery('.tn-color-preview').click(function() {
        jQuery(this).next().click();
    });

    jQuery(document).on('click', 'span.tn-icon-expand', function() {
        var curr_icon = jQuery(this);
        var curr_row = jQuery(this).parents('.ipopt');
        var curr_table = curr_row.parent();
        if (curr_row.next().is(':hidden')) {
            active_icon = curr_table.find('span.active');
            if (active_icon.length && active_icon != curr_icon) {
                active_icon.parents('.ipopt').nextUntil('.ipopt-divider').hide();
                active_icon.removeClass('active');
                curr_icon.addClass('active');
            } else curr_icon.addClass('active');
            curr_row.nextUntil('.ipopt-divider').show();
        } else {
            curr_row.nextUntil('.ipopt-divider').hide();
            curr_icon.removeClass('active');
        }
    });

    jQuery('.tn-accordion').accordion({
        header: 'h4',
        collapsible: true,
        active: false,
        heightStyle: "content",
        autoHeight: false,
        animate: "easeOutQuad"
    });

    jQuery('.tn-accordion-entry').each(function() {
        required = jQuery(this).find('.tn-required-area');
        if (!required.length)
            required = jQuery(this).find('.wp-editor-area');

        if (!required.length)
            return true; //continue

        if (required.val().length < 1 || required.val() == 0)
            jQuery(this).addClass('hidden');
        else
            jQuery(this).find('h4').addClass('active');
    });


    jQuery(document).on('click', '.tn-add-entry', function() {
        container = jQuery(this).parent();
        jQuery('.tn-accordion-entry:hidden:first', container).toggleClass('hidden').find('h4').click();
        if (!jQuery('.tn-accordion-entry:hidden', container).length)
            jQuery(this).addClass('button-disabled');
    });

    jQuery('.ipo-linkto-select').each(function() {
        if (jQuery(this).val() == 'custom')
            jQuery(this).parents('.ipopt').next('.hidden').show();
    });

    jQuery('.ipo-linkto-select').change(function() {
        jQuery(this).parents('.ipopt').next('.hidden').toggle(jQuery(this).val() == 'custom');
    });

    // Show/hide options followed, by default 1 for show, 0 for hide
    jQuery('.tn-opt-toggle').each(function() {
        var str = jQuery(this).attr('class');
        var re = /toggle\-key\-(\w+)/gi;
        var found = re.exec(str);

        if ((found && jQuery(this).val() == found[1]) || (!found && jQuery(this).val() == 1))
            jQuery(this).parents('.tn-opt').next('.hidden').show();
    });

    jQuery('.tn-opt-toggle').change(function() {
        var str = jQuery(this).attr('class');
        var re = /toggle\-key\-(\w+)/gi;
        var found = re.exec(str);

        if (found)
            jQuery(this).parents('.tn-opt').next('.hidden').toggle(jQuery(this).val() == found[1]);
        else
            jQuery(this).parents('.tn-opt').next('.hidden').toggle(jQuery(this).val() == 1);
    });

    jQuery('.tn-opt-toggle-all').change(function() {
        var str = jQuery(this).attr('class');
        var re = /toggle\-key\-(\w+)/gi;
        var found = re.exec(str);

        if (found)
            jQuery(this).parents('.tn-opt').nextAll('.hidden').toggle(jQuery(this).val() == found[1]);
        else
            jQuery(this).parents('.tn-opt').nextAll('.hidden').toggle(jQuery(this).val() == 1);
    });

    // toggle content
    jQuery('.tn-toggle').click(function() {
        jQuery(this).toggleClass('tn-toggle-active').next('.tn-toggle-content').slideToggle();
    });

});