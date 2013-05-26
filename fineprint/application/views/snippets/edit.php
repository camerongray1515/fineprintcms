<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.snippets.js'); ?>"></script>
<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.editing.js'); ?>"></script>

<h3>Edit Snippet</h3>

<div id="alert-container">
	<?php			
	if ($message = $this->session->flashdata('message'))
	{
		$type = 'success';
		$title = 'Success!';
		if (!$this->session->flashdata('success'))
		{
			$type = 'error';
			$title = 'Error!';
		}
			
		echo alert_html($title, $message, $type);
	}
	?>
</div>

<form class="form-horizontal" action="<?php echo admin_url('snippets/do_edit/no_ajax'); ?>" method="POST" id="snippet-form">
	<div class="control-group">
		<label class="control-label" for="title">Title</label>
		<div class="controls">
			<input type="text" id="title" name="title" placeholder="A descriptive name for your snippet e.g. 'Right Sidebar Blocks'" class="input-xxlarge" value="<?php echo $snippet->title; ?>">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="alias">Alias</label>
		<div class="controls">
			<input type="text" id="alias" name="alias" placeholder="A short name to use when including your snippet e.g. 'sidebar-right'" class="input-xxlarge" value="<?php echo $snippet->alias; ?>">
		</div>
	</div>
	
	<div class="control-group js-only">
		<label class="control-label" for="editor">Editor</label>
		<div class="controls">
			<select name="editor" id="editor" class="editor-edit input-xlarge">
				<?php foreach ($editor_list as $editor): ?>
					<option<?php if ($snippet->editor == $editor->id) { echo " selected"; } ?> value="<?php echo $editor->id; ?>"><?php echo $editor->name; ?></option>	
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="content">Content</label>
		<div class="controls">
			<textarea id="content" name="content" class="textarea-content"><?php echo htmlspecialchars($snippet->content); ?></textarea>
		</div>
	</div>
	
	<input type="hidden" name="snippet-id" id="snippet-id" value="<?php echo $snippet_id; ?>">
	<input type="hidden" name="original-alias" id="original-alias" value="<?php echo $snippet->alias; ?>">
	
	<div class="form-actions">
		<?php echo top_buttons_button('save', 'Save Snippet', 'success', 'hdd'); ?>
		<?php echo top_buttons_link('snippets', 'Cancel', 'default'); ?>
	</div>
</form>
