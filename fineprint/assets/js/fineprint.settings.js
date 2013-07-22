var settings = {
	'save_site_settings': function ()
	{
		var home_page = $('#home-page').val();
		var error_404_page = $('#404-page').val();
		
		$.post(ci.admin_url + '/settings/site_settings_do_save', {
			'home-page': home_page,
			'404-page': error_404_page
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
	
	'save_default_editors': function ()
	{
		var editor_pages = $('#editor-pages').val();
		var editor_blocks = $('#editor-blocks').val();
		var editor_snippets = $('#editor-snippets').val();
		var editor_layouts = $('#editor-layouts').val();
        var editor_dashboard_settings = $('#editor-dashboard_settings').val();
		
		$.post(ci.admin_url + '/settings/default_editors_do_save', {
			'editor-pages':             editor_pages,
			'editor-blocks':            editor_blocks,
			'editor-snippets':          editor_snippets,
			'editor-layouts':           editor_layouts,
            'editor-dashboard_settings':editor_dashboard_settings
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

    'save_dashboard_settings': function ()
    {
        var dashboard_type = $('input[name="dashboard-type"]:checked').val();
        var frame_url = $('#frame-url').val();
        var content = '';

        if (typeof content_editor == 'object' && typeof content_editor.get_content == 'function')
        {
            content = content_editor.get_content();
        }
        else
        {
            content = $('#content').val();
        }

        $.post(ci.admin_url + '/settings/dashboard_settings_do_save', {
            'dashboard-type':   dashboard_type,
            'frame-url':        frame_url,
            'content':          content
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
    }
};

$(document).ready(function() {
	$('#site-settings-form').submit(settings.save_site_settings);
	$('#default-editors-form').submit(settings.save_default_editors);
    $('#dashboard-form').submit(settings.save_dashboard_settings);
});
