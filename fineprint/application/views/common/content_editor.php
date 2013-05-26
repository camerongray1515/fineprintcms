<?php if (is_array($editor->scripts)): ?>
	<?php foreach($editor->scripts as $script): ?>
		<script type="text/javascript" src="<?php echo application_base_url($script) ?>"></script>
	<?php endforeach; ?>
<?php else: ?>
	<?php if (trim($editor->scripts) != ""): ?>
		<script type="text/javascript" src="<?php echo application_base_url($editor->scripts) ?>"></script>
	<?php endif; ?>
<?php endif; ?>

<?php echo $editor->code; ?>