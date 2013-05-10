var snippet = {
	add: function()
	{
		var title = $('#title').val();
		var alias = $('#alias').val();
		var content = '';
		
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
			'content': content
		}, function(json) {
			var data = $.parseJSON(json);
			
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
	
	delete: function(snippet_id)
	{
		bootbox.confirm("Are you sure you want to delete this snippet?  This action cannot be undone!", function(approved) {
			if (approved)
			{
				$.get(ci.admin_url + '/snippets/do_delete/' + snippet_id, function (json) {
					var data = $.parseJSON(json);
					
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
	
	$('.delete-button').click(function() {
		var snippet_id = $(this).attr('data-snippet-id');
		
		snippet.delete(snippet_id);
		
		return false; // Prevent link from navigating to the no-js fallback!
	});
});
