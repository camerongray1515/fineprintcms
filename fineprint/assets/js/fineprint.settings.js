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
				// Did not add successfully
				type = 'error';
				title = 'Error!';
			}
			
			ui.show_alert('alert-container', title, data.message, type);
		});
	},
	
	'save_default_editors': function ()
	{
		var editor_pages = $('#editor-pages').val();
		var editor_blocks = $('#editor-blocks').val();
		var editor_snippets = $('#editor-snippets').val();
		var editor_layouts = $('#editor-layouts').val();
		
		$.post(ci.admin_url + '/settings/default_editors_do_save', {
			'editor-pages': editor_pages,
			'editor-blocks': editor_blocks,
			'editor-snippets': editor_snippets,
			'editor-layouts': editor_layouts
		}, function(data) {
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
	}
};

$(document).ready(function() {
	$('#site-settings-form').submit(function() {
		settings.save_site_settings();
		return false;
	});
	
	$('#default-editors-form').submit(function() {
		settings.save_default_editors();
		return false;
	});
});
