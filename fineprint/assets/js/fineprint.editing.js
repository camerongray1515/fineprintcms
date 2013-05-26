var editing = {
	editor_changed: false,
	
	editor_changed_add: function(editor_id)
	{
		bootbox.confirm("Changing the editor will clear any form data, if you have data you wish to keep you should save the item you are adding and then edit it to change the editor.<br><br>Clicking OK will reload the editor, Cancel will go back to the current editor and the change will take effect the next time you save.", function(result) {
			if (result)
			{
				// Redirect to the new editor
				window.location = window.location.pathname + '?editor_id=' + editor_id;
			}
		});
	},
	
	editor_changed_edit: function()
	{
		bootbox.dialog("In order for this change to take effect, you need to save this item, do you want to do this now?", [{
		    "label" : "Go back to editing and save later",
		    "class" : "btn",
		    "callback": function() {
		        bootbox.hideAll();
		        
		        // Mark editor as changed so page refreshes when next saved
		        editing.editor_changed = true;
		    }
		}, {
			"label" : "Save and reload",
		    "class" : "btn-success",
		    "callback": function() {
		        // Submit the form
		        $('form').submit();
		    }
		}]);
	}
};

$(document).ready(function() {
	$('#editor.editor-add').change(function() {
		var editor_id = $('#editor.editor-add').val();
		editing.editor_changed_add(editor_id);
	});
	
	$('#editor.editor-edit').change(function() {
		var editor_id = $('#editor.editor-edit').val();
		editing.editor_changed_edit(editor_id);
	});
});
