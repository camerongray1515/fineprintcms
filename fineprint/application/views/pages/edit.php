<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.pages.js'); ?>"></script>
<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.editing.js'); ?>"></script>

<h3>Edit Page</h3>

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

<form class="form-horizontal" action="<?php echo admin_url('pages/do_edit/no_ajax'); ?>" method="POST" id="page-form">
	<div class="control-group">
		<label class="control-label" for="title">Title</label>
		<div class="controls">
			<input type="text" id="title" name="title" placeholder="A descriptive name for your page e.g. 'About Us'" class="input-xxlarge" value="<?php echo $page->title; ?>">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="alias">Alias</label>
		<div class="controls">
			<input type="text" id="alias" name="alias" placeholder="This will be used in the URL to your page e.g. 'http://www.example.com/contact_us'" class="input-xxlarge" value="<?php echo $page->alias; ?>">
		</div>
	</div>
	
	<div class="control-group js-only">
		<label class="control-label" for="editor">Layout</label>
		<div class="controls">
			<select name="layout" id="layout" class="input-xlarge">
				<?php foreach ($layouts as $layout): ?>
					<option<?php if ($page->layout == $layout->id) { echo " selected"; } ?> value="<?php echo $layout->id; ?>"><?php echo $layout->title; ?></option>	
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<div class="control-group js-only">
		<label class="control-label" for="editor">Editor</label>
		<div class="controls">
			<select name="editor" id="editor" class="editor-edit input-xlarge">
				<?php foreach ($editor_list as $editor): ?>
					<option<?php if ($page->editor == $editor->id) { echo " selected"; } ?> value="<?php echo $editor->id; ?>"><?php echo $editor->name; ?></option>	
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="content">Content</label>
		<div class="controls">
			<textarea id="content" name="content" class="textarea-content"><?php echo htmlspecialchars($page->content); ?></textarea>
		</div>
	</div>
	
	<input type="hidden" name="page-id" id="page-id" value="<?php echo $page_id; ?>">
	<input type="hidden" name="original-alias" id="original-alias" value="<?php echo $page->alias; ?>">
	
	<div class="form-actions">
		<?php echo top_buttons_button('save', 'Save Page', 'success', 'hdd'); ?>
		<?php echo top_buttons_link('pages', 'Cancel', 'default'); ?>
	</div>
</form>
