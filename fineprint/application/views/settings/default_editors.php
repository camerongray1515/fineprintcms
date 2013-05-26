<form class="form-horizontal" id="default-editors-form" method="POST" action="<?php echo admin_url('settings/default_editors_do_save/no_ajax'); ?>">
	<fieldset>
		<h4>Default Editors</h4>
		<p>Here you can set different default editors for pages, blocks, snippets and layouts.  These editors will be the first displayed when creating a new page, block, snippet or layout.</p>
		
		<?php
			// Build array of different areas editors operate in
			$editor_areas = array('Pages', 'Blocks', 'Snippets', 'Layouts');
		?>
		
		<?php foreach ($editor_areas as $area): ?>
			<div class="control-group">
				<label class="control-label"><?php echo $area ?></label>
				<div class="controls">
					<select class="input-xlarge" id="editor-<?php echo strtolower($area); ?>" name="editor-<?php echo strtolower($area); ?>">
						<?php foreach($editors as $editor): ?>
							<option<?php if ($default_editors[strtolower($area)] == $editor->id) { echo ' selected'; } ?> value="<?php echo $editor->id; ?>"><?php echo $editor->name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		<?php endforeach; ?>
	</fieldset>
	
	<div class="form-actions">
		<?php echo top_buttons_button('save-default-editors', 'Save Settings', 'success', 'hdd'); ?>
	</div>
</form>
