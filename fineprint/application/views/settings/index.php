<form id="site-settings-form" method="POST" action="<?php echo admin_url('settings/site_settings_do_save/no_ajax'); ?>">
	<fieldset>
		<h4>Site Settings</h4>
		
		<label>Home Page</label>
		<select id="home-page" name="home-page">
			<?php foreach($pages as $page): ?>
				<option<?php if ($page->alias == $home_page) { echo ' selected'; } ?> value="<?php echo $page->alias; ?>"><?php echo $page->title; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="help-block">This is the page that will be displayed when navigating directly to the website.</span>
		
		<br>
		
		<label>404 Error Page</label>
		<select id="404-page" name="404-page">
			<?php foreach($pages as $page): ?>
				<option<?php if ($page->alias == $error_404_page) { echo ' selected'; } ?> value="<?php echo $page->alias; ?>"><?php echo $page->title; ?></option>
			<?php endforeach; ?>
		</select>
		<span class="help-block">This is the page that will be displayed when navigating directly to the website.</span>
	</fieldset>
	
	<div class="form-actions">
		<?php echo top_buttons_button('save-system-settings', 'Save Settings', 'success', 'hdd'); ?>
	</div>
</form>
