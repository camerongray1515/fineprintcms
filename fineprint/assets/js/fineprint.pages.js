var page = {
	add: function()
	{
		var title = $('#title').val();
		var alias = $('#alias').val();
		var content = '';
		var editor = $('#editor').val();
		var layout = $('#layout').val();
		
		if (typeof content_editor == 'object' && typeof content_editor.get_content == 'function')
		{
			content = content_editor.get_content();
		}
		else
		{
			content = $('#content').val();
		}
		
		$.post(ci.admin_url + '/pages/do_add', {
			'title': title,
			'alias': alias,
			'content': content,
			'editor': editor,
			'layout': layout,
		}, function(data) {			
			var type = 'success';
			var title = 'Success!';
			if (data.success)
			{
				// Added successfully - Set flashdata and redirect!
				ui.redirect_then_message(true, data.message, ci.admin_url + '/pages');
			}
			else
			{
				// Did not add successfully
				type = 'error';
				title = 'Error!';
				
				ui.show_alert('alert-container', title, data.message, type);
			}
		});
	},
	
	edit: function()
	{
		var title = $('#title').val();
		var alias = $('#alias').val();
		var content = '';
		var editor = $('#editor').val();
		var page_id = $('#page-id').val();
		var original_alias = $('#original-alias').val();
		var layout = $('#layout').val();
		
		if (typeof content_editor == 'object' && typeof content_editor.get_content == 'function')
		{
			content = content_editor.get_content();
		}
		else
		{
			content = $('#content').val();	
		}
		
		$.post(ci.admin_url + '/pages/do_edit', {
			'title': title,
			'alias': alias,
			'content': content,
			'editor': editor,
			'page-id': page_id,
			'original-alias': original_alias,
			'layout': layout
		}, function(data) {			
			// If the editor has changed, then refresh the page so the new editor takes effect
			if (editing.editor_changed)
			{
				ui.redirect_then_message (data.success, data.message, document.URL);
				return;
			}
			
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
	
	delete: function(page_id)
	{
		bootbox.confirm("Are you sure you want to delete this page?  This action cannot be undone!", function(approved) {
			if (approved)
			{
				$.get(ci.admin_url + '/pages/do_delete/' + page_id, function (data) {					
					var type = 'success';
					var title = 'Success!';
					if (!data.success)
					{
						type = 'error';
						title = 'Error!';
					}
					
					ui.show_alert('alert-container', title, data.message, type);
					
					// Now remove the row from the table
					ui.remove_tr('tr[data-page-id="' + page_id + '"] > td');
				});
			}
		});
	}
}

$(document).ready(function() {
	$('#add').click(function() {
		page.add();
		return false;
	});
	
	$('#save').click(function() {
		page.edit();
		return false;
	});
	
	$('.delete-button').click(function() {
		var page_id = $(this).attr('data-page-id');
		
		page.delete(page_id);
		
		return false; // Prevent link from navigating to the no-js fallback!
	});
});
