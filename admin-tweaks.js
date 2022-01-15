jQuery( document ).ready(function() {
	jQuery(document).on( 'click', '.easy_notices .notice-dismiss:not(".disabled")', function(e) {
		jQuery(this).attr('disabled','disabled')
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'remove_error_from_notices_option',
			key: jQuery(this).parent().attr('data-dismiss-key')
        },
		type:		"post",
		dataType:	"json",
		async: true,
		success: function(json){
			jQuery('.easy_notices .notice-dismiss').removeAttr('disabled')}
    })

	})
	jQuery(document).on( 'click', '.easy_notices#easy_notices_remove_all a', function(e) {
		e.preventDefault();
			jQuery.ajax({
					url: ajaxurl,
					data: {
							action: 'remove_all_errors_from_notices_option'
					},
			type:		"post",
			dataType:	"json",
			async: true,
			
			}).done(function(json){
				console.log(json);
				
			})
			jQuery('.easy_notices').toggle('slow',function() {jQuery(this).remove();})

	})
})