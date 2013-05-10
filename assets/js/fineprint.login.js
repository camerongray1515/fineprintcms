var login = {
	make_request: function()
	{
		var username = $('#username').val();
		var password = $('#password').val();
		
		$.post(ci.site_url + '/admin/login/do_login', {
			'username': username,
			'password': password
		}, function(json) {
			var data = $.parseJSON(json);
						
			if (data.success)
			{
				ui.show_alert('alert-container', 'Success!', data.message, 'success');
				setTimeout(function() {
					window.location = data.redirect_to;
				}, 2000);
			}
			else
			{
				ui.show_alert('alert-container', 'Error!', data.message, 'error');
			}
			
			// Clear password input
			$('#password').val('');
		});
	}
};

// Give focus to the username input
$(document).ready(function() {
	$('#username').focus();
	
	$('#login-form').submit(function() {
		login.make_request();
		return false;
	});
});