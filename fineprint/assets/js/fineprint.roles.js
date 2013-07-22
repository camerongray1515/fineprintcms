var roles = {
    check_all: function()
    {
        $('input.controller-checkbox').prop('checked', true);
        return false;
    },

    uncheck_all: function()
    {
        $('input.controller-checkbox').prop('checked', false);
        return false;
    },

    swap_checked_and_unchecked: function()
    {
        // Add a class to all checked permissions to identify them after checking the unchecked ones
        $('input.controller-checkbox:checked').addClass('originally-checked');

        // Check all currently unchecked inputs
        $('input.controller-checkbox:not(:checked)').prop('checked', true);

        // Now uncheck any originally checked inputs
        $('input.controller-checkbox.originally-checked').prop('checked', false);

        // Finally remove the 'originally-unchecked' class from all inputs that have it
        $('input.controller-checkbox.originally-checked').removeClass('originally-checked');

        return false;
    },

    add: function()
    {
        var name = $('#name').val();
        var allowed_controllers = functions.checkbox_array('input.controller-checkbox:checked');

        $.post(ci.admin_url + '/roles/do_add', {
            'name': name,
            'controllers': allowed_controllers
        }, function (data){
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

    edit: function()
    {
        var role_id = $('#role-id').val();
        var name = $('#name').val();
        var allowed_controllers = functions.checkbox_array('input.controller-checkbox:checked');

        $.post(ci.admin_url + '/roles/do_edit', {
            'role-id': role_id,
            'name': name,
            'controllers': allowed_controllers
        }, function (data){
            var type = 'success';
            var title = 'Success!';
            if (!data.success)
            {
                // Did not add successfully
                type = 'error';
                title = 'Error!';
            }
            ui.show_alert('alert-container', title, data.message, type);
        });

        return false;
    },

    delete: function()
    {
        var role_id = $(this).attr('data-role-id');

        bootbox.confirm("Are you sure you want to delete this role?  This action cannot be undone!", function(approved) {
            if (approved)
            {
                $.post(ci.admin_url + '/roles/do_delete/' + role_id, {}, function (data){
                    if (data.success)
                    {
                        ui.show_alert('alert-container', 'Success!', data.message, 'success');

                        // Now remove the role's row from the table
                        ui.remove_tr('tr[data-role-id="' + role_id + '"] > td');
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
    }
};

$(document).ready(function() {
    $('#add').click(roles.add);
    $('#edit').click(roles.edit);
    $('.delete-role').click(roles.delete);
    $('#check-all').click(roles.check_all);
    $('#uncheck-all').click(roles.uncheck_all);
    $('#swap-checked-and-unchecked').click(roles.swap_checked_and_unchecked);
});