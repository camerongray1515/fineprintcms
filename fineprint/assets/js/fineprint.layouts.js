var layout = {
	add: function()
	{
		var title = $('#title').val();
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
		
		$.post(ci.admin_url + '/layouts/do_add', {
			'title': title,
			'content': content,
			'editor': editor
		}, function(data) {			
			var type = 'success';
			var title = 'Success!';
			if (data.success)
			{
				// Added successfully - Set flashdata and redirect!
				ui.redirect_then_message(true, data.message, ci.admin_url + '/layouts');
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
		var content = '';
		var editor = $('#editor').val();
		var layout_id = $('#layout-id').val();
		var original_title = $('#original-title').val();
		
		if (typeof content_editor == 'object' && typeof content_editor.get_content == 'function')
		{
			content = content_editor.get_content();
		}
		else
		{
			content = $('#content').val();
		}
		
		$.post(ci.admin_url + '/layouts/do_edit', {
			'title': title,
			'content': content,
			'editor': editor,
			'layout-id': layout_id,
			'original-title': original_title
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
	
	delete: function(layout_id)
	{
		bootbox.confirm("Are you sure you want to delete this layout?  This action cannot be undone!", function(approved) {
			if (approved)
			{
				$.get(ci.admin_url + '/layouts/do_delete/' + layout_id, function (data) {					
					var type = 'success';
					var title = 'Success!';
					if (!data.success)
					{
						type = 'error';
						title = 'Error!';
					}
					
					ui.show_alert('alert-container', title, data.message, type);
					
					// Now remove the row from the table
					ui.remove_tr('tr[data-layout-id="' + layout_id + '"] > td');
				});
			}
		});
	}
}

$(document).ready(function() {
	$('#add').click(function() {
		layout.add();
		return false;
	});
	
	$('#save').click(function() {
		layout.edit();
		return false;
	});
	
	$('.delete-button').click(function() {
		var layout_id = $(this).attr('data-layout-id');
		
		layout.delete(layout_id);
		
		return false; // Prevent link from navigating to the no-js fallback!
	});
});
