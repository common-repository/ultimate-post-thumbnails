function tn_get_cookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

jQuery.noConflict();
    
jQuery(document).ready(function() {
	
    var context = jQuery('#tn-admin');

    RegExp.quote = function(str) {
        return String(str).replace(/([.?*+^$[\]\\(){}-])/g, "\\$1");
    };

    jQuery('.row-toggle', context).on('click', function() {
        var curr_row = jQuery(this);
        var curr_table = jQuery(this).parents('table');
		
        if (curr_row.next().is(':hidden')) {
            active_row = curr_table.find('.row-toggle.active');
            if (active_row.length && active_row != curr_row) {
                active_row.removeClass('active').nextUntil('.row_no_options').hide();
                curr_row.addClass('active');
            } else 
				curr_row.addClass('active');
				
            curr_row.nextUntil('.row_no_options').show();
			
        } else {
            curr_row.nextUntil('.row_no_options').hide();
            curr_row.removeClass('active');
        }
    });

    jQuery('span.icon-delete', context).on('click', function() {
        jQuery(this).parents('table').remove();
    });
	
    // Switch between internal pages on theme settings page
    jQuery('ul.tn-nav-menu li:first-child').addClass('current');
    jQuery('ul.tn-nav-menu li').click(
        function() {
            if( jQuery(this).hasClass('collapse') ) {
			
				var collapse;
				if(jQuery('span', this).is(':hidden')) {
					jQuery(this).parent().find('span').show();
					jQuery('ul.tn-opt-groups').css('margin-left', '+=190').parent().removeClass('tn-collapse-menu');
					collapse = 0;
				}
				else {
					jQuery(this).parent().find('span').hide();
					jQuery('ul.tn-opt-groups').css('margin-left', '-=190').parent().addClass('tn-collapse-menu');
					collapse = 1;
				}
				document.cookie='tnMenuCollapse='+ collapse;
				
            } else if( !jQuery(this).hasClass('current') ) {
			
                jQuery('li.current').removeClass('current');
                jQuery(this).addClass('current');
                
                index = jQuery('ul.tn-nav-menu li').index(this);
                // active page
                jQuery('ul.tn-opt-groups li:visible').css({'display':'none'});
                jQuery('ul.tn-opt-groups li:eq(' + index + ')').css({'visibility':'visible', 'height':'auto', 'display':'block'});
            }
        }
    );
		
    jQuery('.tn-add').click(function() {
        var group = jQuery(this).parents('div.group');
        var default_item = jQuery(this).parent().prev('.default-item');
        if (!default_item.length) return;

        // Fetch index of the last item and plus 1
        var index = 0;
        var last_item = jQuery('p.submit', group).prev('.list-item');

        if (last_item.length) {
            var reg = /([^\[]+)\[(\d+)\]/;
            var matches = reg.exec(jQuery(':input:first', last_item).attr('id'));
            if (matches) {
                index = ++matches[2];
                var newstr = matches[1] + '[' + index + ']';
            }
        }

        // Generate the new item
        reg = /([^\[]+)\[.+\]/;
        matches = reg.exec(jQuery(':input:first', default_item).attr('id'));
        if (!newstr) {
            newstr = matches[1] + 's[0]';
        }
        var pattern = new RegExp(RegExp.quote(matches[1]), 'ig');
        var new_item = default_item.clone().removeClass('default-item').removeClass('hidden');

        // Replace option id&name, old implementation is to replace all match string found in whole html string
        // Current logic is to find form inputs one by one and do replace only on attributes 'id' and 'name', better but performance probably down a bit
        // Alternatively, update old way by a more complex pattern which match only 'id' and 'name', has the same effect and may has better performance, compare to current logic
        // do a performance compare will help, anyway, sometime in future

        jQuery(':input', new_item).each(function() {
            jQuery(this).attr('id', jQuery(this).attr('id').replace(pattern, newstr));
            jQuery(this).attr('name', jQuery(this).attr('name').replace(pattern, newstr));
        });
        jQuery('p.submit', group).before(new_item);

    });
			
});

