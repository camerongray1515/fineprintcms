var users = {
	'check_password_match': function()
	{
		var password_1 = $('#password').val();
		var password_2 = $('#password-confirm').val();
		
		if (password_1 == '' || password_2 == '')
		{
			$('#password-message').text('');
			return;
		}
		
		$('#password-message').removeClass('success');
		$('#password-message').removeClass('error');
		
		if (password_1 == password_2)
		{
			$('#password-message').addClass('success');
			$('#password-message').text('Passwords match!');
		}
		else
		{
			$('#password-message').addClass('error');
			$('#password-message').text('Passwords do not match!');
		}
	}
}

$(document).ready(function() {
	$('#password,#password-confirm').keyup(function() {
		users.check_password_match();
	});
});
