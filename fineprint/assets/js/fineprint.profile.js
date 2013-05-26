var profile = {
	update_password: function()
	{
		var current_password = $('#current-password').val();
		var new_password = $('#new-password').val();
		var confirm_new_password = $('#confirm-new-password').val();
		
		$.post(ci.admin_url + '/profile/do_update_password', {
			'current-password': current_password,
			'new-password': new_password,
			'confirm-new-password': confirm_new_password
		}, function(data) {			
			var type = 'success';
			var title = 'Success!';
			
			if (!data.success)
			{
				type = 'error';
				title = 'Error!';
			}
			
			ui.show_alert('alert-container', title, data.message, type);
		});
		
		// Clear the inputs
		$('#current-password').val('');
		$('#new-password').val('');
		$('#confirm-new-password').val('');
	},
	
	update_information: function()
	{
		var first_name = $('#first-name').val();
		var last_name = $('#last-name').val();
		var username = $('#username').val();
		var email = $('#email').val();
		
		$.post(ci.admin_url + "/profile/do_update_information", {
			'first-name': first_name,
			'last-name': last_name,
			'username': username,
			'email': email
		}, function(data) {			
			var type = 'success';
			var title = 'Success!';
			
			if (!data.success)
			{
				type = 'error';
				title = 'Error!';
			}
			
			ui.show_alert('alert-container', title, data.message, type);
		});
	}
};

$(document).ready(function() {
	$('#change-password').submit(function() {
		profile.update_password();
		return false;
	});
	
	$('#profile-information').submit(function() {
		profile.update_information();
		return false;
	});
});
