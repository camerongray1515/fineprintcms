var snippet = {
	add: function()
	{
		var title = $('#title').val();
		var alias = $('#alias').val();
		var content = '';
		var editor = $('#editor').val();
		
		if (typeof content_editor == 'object' && typeof content_editor.get_content == 'function')
		{
			content = content_editor.get_content();
		}
		else
		{
			content = $('#content').val();
		}
		
		$.post(ci.admin_url + '/snippets/do_add', {
			'title': title,
			'alias': alias,
			'content': content,
			'editor': editor
		}, function(data) {			
			var type = 'success';
			var title = 'Success!';
			if (data.success)
			{
				// Added successfully - Set flashdata and redirect!
				ui.redirect_then_message(true, data.message, ci.admin_url + '/snippets');
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
		var snippet_id = $('#snippet-id').val();
		var original_alias = $('#original-alias').val();
		
		if (typeof content_editor == 'object' && typeof content_editor.get_content == 'function')
		{
			content = content_editor.get_content();
		}
		else
		{
			content = $('#content').val();
		}
		
		$.post(ci.admin_url + '/snippets/do_edit', {
			'title': title,
			'alias': alias,
			'content': content,
			'editor': editor,
			'snippet-id': snippet_id,
			'original-alias': original_alias
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
	
	delete: function(snippet_id)
	{
		bootbox.confirm("Are you sure you want to delete this snippet?  This action cannot be undone!", function(approved) {
			if (approved)
			{
				$.get(ci.admin_url + '/snippets/do_delete/' + snippet_id, function (data) {					
					var type = 'success';
					var title = 'Success!';
					if (!data.success)
					{
						type = 'error';
						title = 'Error!';
					}
					
					ui.show_alert('alert-container', title, data.message, type);
					
					// Now remove the row from the table
					ui.remove_tr('tr[data-snippet-id="' + snippet_id + '"] > td');
				});
			}
		});
	}
}

$(document).ready(function() {
	$('#add').click(function() {
		snippet.add();
		return false;
	});
	
	$('#save').click(function() {
		snippet.edit();
		return false;
	});
	
	$('.delete-button').click(function() {
		var snippet_id = $(this).attr('data-snippet-id');
		
		snippet.delete(snippet_id);
		
		return false; // Prevent link from navigating to the no-js fallback!
	});
});
