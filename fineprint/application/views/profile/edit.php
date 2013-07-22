<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.profile.js'); ?>"></script>

<h3>Edit Profile</h3>

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
				
				// If profile information has been updated, replace the data from the database with the previously supplied data
				if ($this->session->flashdata('first_name') === FALSE)
				{
					$user->first_name = $this->session->flashdata('first_name');
					$user->last_name = $this->session->flashdata('last_name');
					$user->username = $this->session->flashdata('username');
					$user->email = $this->session->flashdata('email');
				}
			}
				
			echo alert_html($title, $message, $type);
		}
	?>
</div>

<div style="width: 650px;">
	<div class="well pull-left" style="width: 285px;">
		<h4>Profile Information</h4>
		
		<form id="profile-information" method="POST" action="<?php echo admin_url('profile/do_update_information/no_ajax'); ?>">
			<label for="first-name">First Name</label>
			<input type="text" class="input-xlarge" id="first-name" name="first-name" value="<?php echo $user->first_name; ?>">
			
			<label for="last-name">Last Name</label>
			<input type="text" class="input-xlarge" id="last-name" name="last-name" value="<?php echo $user->last_name; ?>">
			
			<label for="username">Username</label>
			<input type="text" class="input-xlarge" id="username" name="username" value="<?php echo $user->username; ?>">
			
			<label for="email">Email Address</label>
			<input type="text" class="input-xlarge" id="email" name="email" value="<?php echo $user->email; ?>">

            <input type="hidden" id="original-username" name="original-username" value="<?php echo $user->username; ?>">
		
			<br>
			
			<button class="btn btn-primary" id="update-profile">Update Profile</button>
		</form>
	</div>
	
	<div class="well pull-right" style="width: 220px;">
		<h4>Change Password</h4>
		<form id="change-password" method="POST" action="<?php echo admin_url('profile/do_update_password/no_ajax'); ?>">
			<label for="current-password">Current Password</label>
			<input type="password" name="current-password" id="current-password">
			
			<label for="new-password">New Password</label>
			<input type="password" name="new-password" id="new-password">
			
			<label for="confirm-new-password">New Password (Confirm)</label>
			<input type="password" name="confirm-new-password" id="confirm-new-password">
			
			<br>
				
			<button class="btn btn-primary" id="update-password">Update Password</button>
		</form>
	</div>
</div>