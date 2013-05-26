var block = {
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
		
		$.post(ci.admin_url + '/blocks/do_add', {
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
				ui.redirect_then_message(true, data.message, ci.admin_url + '/blocks');
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
		var block_id = $('#block-id').val();
		var original_alias = $('#original-alias').val();
		
		if (typeof content_editor == 'object' && typeof content_editor.get_content == 'function')
		{
			content = content_editor.get_content();
		}
		else
		{
			content = $('#content').val();
		}
		
		$.post(ci.admin_url + '/blocks/do_edit', {
			'title': title,
			'alias': alias,
			'content': content,
			'editor': editor,
			'block-id': block_id,
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
	
	delete: function(block_id)
	{
		bootbox.confirm("Are you sure you want to delete this block?  This action cannot be undone!", function(approved) {
			if (approved)
			{
				$.get(ci.admin_url + '/blocks/do_delete/' + block_id, function (data) {					
					var type = 'success';
					var title = 'Success!';
					if (!data.success)
					{
						type = 'error';
						title = 'Error!';
					}
					
					ui.show_alert('alert-container', title, data.message, type);
					
					// Now remove the row from the table
					ui.remove_tr('tr[data-block-id="' + block_id + '"] > td');
				});
			}
		});
	}
}

$(document).ready(function() {
	$('#add').click(function() {
		block.add();
		return false;
	});
	
	$('#save').click(function() {
		block.edit();
		return false;
	});
	
	$('.delete-button').click(function() {
		var block_id = $(this).attr('data-block-id');
		
		block.delete(block_id);
		
		return false; // Prevent link from navigating to the no-js fallback!
	});
});
