<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.snippets.js'); ?>"></script>
<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.editing.js'); ?>"></script>

<h3>Add Snippet</h3>

<div id="alert-container">
	<?php
		// The only time flashdata will be sent here is in the case of an error so don't need to check success!
	
		$alias = $title = $content = ''; // This will be put into the inputs
		
		if ($message = $this->session->flashdata('message'))
		{
			$alias = $this->session->flashdata('alias');
			$title = $this->session->flashdata('title');
			$content = $this->session->flashdata('content');
			
			echo alert_html('Error!', $message, 'error');
		}
	?>
</div>

<form class="form-horizontal" action="<?php echo admin_url('snippets/do_add/no_ajax'); ?>" method="POST" id="snippet-form">
	<div class="control-group">
		<label class="control-label" for="title">Title</label>
		<div class="controls">
			<input type="text" id="title" name="title" placeholder="A descriptive name for your snippet e.g. 'Right Sidebar Blocks'" class="input-xxlarge" value="<?php echo $title; ?>">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="alias">Alias</label>
		<div class="controls">
			<input type="text" id="alias" name="alias" placeholder="A short name to use when including your snippet e.g. 'sidebar-right'" class="input-xxlarge" value="<?php echo $alias; ?>">
		</div>
	</div>
	
	<div class="control-group js-only">
		<label class="control-label" for="editor">Editor</label>
		<div class="controls">
			<select name="editor" id="editor" class="editor-add input-xlarge">
				<?php foreach ($editor_list as $editor): ?>
					<option<?php if ($editor_id == $editor->id) { echo " selected"; } ?> value="<?php echo $editor->id; ?>"><?php echo $editor->name; ?></option>	
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="content">Content</label>
		<div class="controls">
			<textarea id="content" name="content" class="textarea-content"><?php echo $content; ?></textarea>
		</div>
	</div>
	
	<div class="form-actions">
		<?php echo top_buttons_button('add', 'Save Snippet', 'success', 'hdd'); ?>
		<?php echo top_buttons_link('snippets', 'Cancel', 'default'); ?>
	</div>
</form>
