<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.layouts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.editing.js'); ?>"></script>

<h3>Edit Layout</h3>

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

<form class="form-horizontal" action="<?php echo admin_url('layouts/do_edit/no_ajax'); ?>" method="POST" id="layout-form">
	<div class="control-group">
		<label class="control-label" for="title">Title</label>
		<div class="controls">
			<input type="text" id="title" name="title" placeholder="A descriptive name for your layout e.g. 'Right Sidebar Layouts'" class="input-xxlarge" value="<?php echo $layout->title; ?>">
		</div>
	</div>
	
	<div class="control-group js-only">
		<label class="control-label" for="editor">Editor</label>
		<div class="controls">
			<select name="editor" id="editor" class="editor-edit input-xlarge">
				<?php foreach ($editor_list as $editor): ?>
					<option<?php if ($layout->editor == $editor->id) { echo " selected"; } ?> value="<?php echo $editor->id; ?>"><?php echo $editor->name; ?></option>	
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="content">Content</label>
		<div class="controls">
			<textarea id="content" name="content" class="textarea-content"><?php echo htmlspecialchars($layout->content); ?></textarea>
		</div>
	</div>
	
	<input type="hidden" name="layout-id" id="layout-id" value="<?php echo $layout_id; ?>">
	<input type="hidden" name="original-title" id="original-title" value="<?php echo $layout->title; ?>">
	
	<div class="form-actions">
		<?php echo top_buttons_button('save', 'Save Layout', 'success', 'hdd'); ?>
		<?php echo top_buttons_link('layouts', 'Cancel', 'default'); ?>
	</div>
</form>
