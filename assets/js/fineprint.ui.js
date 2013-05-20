var ui = {
	show_alert: function(alert_container, title, message, type)
	{
		ui.scroll_to_top();
		
		$('#' + alert_container).css('overflow', 'hidden');
		$('#' + alert_container).css('height', '0');
		
		if (title != false)
		{
			title = '<strong>' + title + '</strong> ';
		}
		else
		{
			title = '';
		}
		
		var alert_html = '<div class="alert alert-' + type + '">' + title + message + '</div>';
		
		$('#' + alert_container).html(alert_html);
		
		$('#' + alert_container).animate({
            'height': '50px',
            'margin-bottom': '10px'
        }, 500, function() {
            setTimeout(function() {
                $('#' + alert_container).animate({
                    'height': '0',
                    'margin-bottom': '0'
                }, 500);
            }, 5000)
        });
	},
	
	scroll_to_top: function()
	{
		$("html, body").animate({
			scrollTop: 0
		}, 'slow');
	},
	
	redirect_then_message: function(success, message, redirect_to)
	{
		// Sets a flashdata message over AJAX then redirects
		
		$.post(ci.admin_url + '/javascript/set_flashdata_message', {
			'success': toString(success),
			'message': message
		}, function() {
			window.location = redirect_to;
		});
	},
	
	remove_tr: function (selector)
	{
		$(selector).animate({
			'opacity': 0
		}, 500, function () {
			$(selector).html('');
			
			$(selector).animate({
				'height': 0
			}, 500, function() {
				$(selector).remove();
			});
		});
	}
}

$(document).ready(function() {
	$('.js-only').show();
});
