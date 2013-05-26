<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.settings.js'); ?>"></script>

<h3>Settings</h3>

<p>Note: You must save settings on each tab before changing to another.</p>

<ul class="nav nav-tabs">
	<?php echo tab_item_link('Site Settings', admin_url('settings')); ?>
	<?php echo tab_item_link('Default Editors', admin_url('settings/default_editors')); ?>
</ul>

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