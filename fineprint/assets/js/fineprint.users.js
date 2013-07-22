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
	},

    'add': function()
    {
        var first_name = $('#first-name').val();
        var last_name = $('#last-name').val();
        var email = $('#email').val();
        var username = $('#username').val();
        var role = $('#role').val();
        var password = $('#password').val();
        var password_confirm = $('#password-confirm').val();

        $.post(ci.admin_url + '/users/do_add', {
            'first-name':       first_name,
            'last-name':        last_name,
            'email':            email,
            'username':         username,
            'role':             role,
            'password':         password,
            'password-confirm': password_confirm
        }, function(data) {
            var type = 'success';
            var title = 'Success!';
            if (data.success)
            {
                // Added successfully - Set flashdata and redirect!
                ui.redirect_then_message(true, data.message, data.redirect_to);
            }
            else
            {
                // Did not add successfully
                type = 'error';
                title = 'Error!';

                ui.show_alert('alert-container', title, data.message, type);
            }
        });

        return false;
    },

    'check_username': function()
    {
        var username = $('#username').val();

        if (username == '')
        {
            $('#username-message').text('');
            return;
        }

        $.post(ci.admin_url + '/users/check_username', {
            'username': username
        }, function (data){
            $('#username-message').removeClass('success');
            $('#username-message').removeClass('error');

            if (data.username_in_use)
            {
                $('#username-message').addClass('error');
                $('#username-message').text('Username already in use!');
            }
            else
            {
                $('#username-message').addClass('success');
                $('#username-message').text('Username available!');
            }
        });
    },

    delete: function()
    {
        var user_id = $(this).attr('data-user-id');

        bootbox.confirm("Are you sure you want to delete this user?  This action cannot be undone!", function(approved) {
            if (approved)
            {
                $.post(ci.admin_url + '/users/do_delete/' + user_id, {}, function (data){
                    if (data.success)
                    {
                        ui.show_alert('alert-container', 'Success!', data.message, 'success');

                        // Now remove the role's row from the table
                        ui.remove_tr('tr[data-user-id="' + user_id + '"] > td');
                    }
                    else
                    {
                        // Did not add successfully
                        bootbox.alert('<div class="alert alert-error"><strong>Error!</strong> ' + data.message + '</div>');
                    }
                });
            }
        });

        return false;
    },

    'save': function()
    {
        var first_name = $('#first-name').val();
        var last_name = $('#last-name').val();
        var email = $('#email').val();
        var username = $('#username').val();
        var role = $('#role').val();
        var password = $('#password').val();
        var password_confirm = $('#password-confirm').val();
        var original_username = $('#original-username').val();
        var user_id = $('#user-id').val();

        $.post(ci.admin_url + '/users/do_edit', {
            'first-name':       first_name,
            'last-name':        last_name,
            'email':            email,
            'username':         username,
            'role':             role,
            'password':         password,
            'password-confirm': password_confirm,
            'original-username':original_username,
            'user-id':          user_id
        }, function(data) {
            var type = 'success';
            var title = 'Success!';
            if (!data.success)
            {
                // Did not save successfully
                type = 'error';
                title = 'Error!';
            }

            ui.show_alert('alert-container', title, data.message, type);
        });

        return false;
    },
}

$(document).ready(function() {
    $('#add').click(users.add);
    $('#edit').click(users.save);

    $('.delete-button').click(users.delete);

    $('#username').keyup(users.check_username);
	$('#password,#password-confirm').keyup(users.check_password_match);
});
