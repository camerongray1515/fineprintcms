<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.users.js'); ?>"></script>

<h3>Edit User</h3>

<div id="alert-container">
<?php
    $user->original_username = $user->username;

	if ($message = $this->session->flashdata('message'))
	{
		$user->first_name = $this->session->flashdata('first_name');
		$user->last_name = $this->session->flashdata('last_name');
		$user->email = $this->session->flashdata('email');
		$user->username = $this->session->flashdata('username');
        $user->role = $this->session->flashdata('role');
        $user->original_username = $this->session->flashdata('original_username');
		
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

<form class="form-horizontal" id="edit-user-form" method="POST" action="<?php echo admin_url('users/do_edit/no_ajax'); ?>">
	<div class="control-group">
		<label class="control-label" for="first-name">First Name</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="first-name" name="first-name" value="<?php echo $user->first_name; ?>">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="last-name">Last Name</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="last-name" name="last-name" value="<?php echo $user->last_name; ?>">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="email">Email</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="email" name="email" value="<?php echo $user->email; ?>">
		</div>
	</div>
	
	<div class="control-group" style="margin-top: 50px;">
		<label class="control-label" for="username">Username</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="username" name="username" value="<?php echo $user->username; ?>">
            <span id="username-message" class="help-inline"></span>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="role">Role</label>
		<div class="controls">
			<select id="role" name="role" class="input-medium">
				<?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role->id; ?>" <?php echo ($role->id == $user->role)? 'selected': ''; ?>><?php echo $role->name; ?></option>
                <?php endforeach; ?>
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

    <input type="hidden" id="original-username" name="original-username" value="<?php echo $user->original_username; ?>"/>
    <input type="hidden" id="user-id" name="user-id" value="<?php echo $user->id; ?>"/>
	
	<div class="form-actions">
		<?php echo top_buttons_button('edit', 'Save User', 'success', 'hdd'); ?>
		<?php echo top_buttons_link('users', 'Cancel', 'default'); ?>
	</div>
</form>
