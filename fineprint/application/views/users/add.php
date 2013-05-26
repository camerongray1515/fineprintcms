<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.users.js'); ?>"></script>

<h3>Add User</h3>

<?php
	$first_name = $last_name = $email = $username = $role = '';

	if ($message = $this->session->flashdata('message'))
	{
		$first_name = $this->session->flashdata('first_name');
		$last_name = $this->session->flashdata('last_name');
		$email = $this->session->flashdata('email');
		$username = $this->session->flashdata('username');
		$role = $this->session->flashdata('role');
		
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

<form class="form-horizontal" id="add-user-form" method="POST" action="<?php echo admin_url('users/do_add/no_ajax'); ?>">
	<div class="control-group">
		<label class="control-label" for="first-name">First Name</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="first-name" name="first-name" value="<?php echo $first_name; ?>"> 
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="last-name">Last Name</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="last-name" name="last-name" value="<?php echo $last_name; ?>"> 
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="email">Email</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="email" name="email" value="<?php echo $email; ?>"> 
		</div>
	</div>
	
	<div class="control-group" style="margin-top: 50px;">
		<label class="control-label" for="username">Username</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="username" name="username" value="<?php echo $username; ?>"> 
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="role">Role</label>
		<div class="controls">
			<select id="role" name="role" class="input-medium">
				<option value="administrator">Administrator</option>
				<option value="designer">Designer</option>
				<option value="user">User</option>
			</select>
		</div>
	</div>
	
	<div id="password-group">
		<div class="control-group">
			<label class="control-label" for="password">Password</label>
			<div class="controls">
				<input type="password" class="input-xlarge" id="password" name="password"> 
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="password-confirm">Password (Confirm)</label>
			<div class="controls">
				<input type="password" class="input-xlarge" id="password-confirm" name="password-confirm">
				<span id="password-message" class="help-inline"></span>
			</div>
		</div>
	</div>
	
	<div class="form-actions">
		<?php echo top_buttons_button('add', 'Add User', 'success', 'plus'); ?>
		<?php echo top_buttons_link('users', 'Cancel', 'default'); ?>
	</div>
</form>
