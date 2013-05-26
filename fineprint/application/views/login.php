<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.login.js'); ?>"></script>

<div id="login-form-container" class="well">
	<form class="form-horizontal" action='<?php echo admin_url('login/do_login/no_ajax'); ?>' method="POST" id="login-form">
		<fieldset>
			<div id="legend">
				<legend class="">Login - Fine Print CMS</legend>
			</div>
			
			<div id="alert-container">
				<?php
					$username = ''; // This will be put into the username input
					
					if ($message = $this->session->flashdata('message'))
					{
						$username = $this->session->flashdata('username');
						
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
			
			<div class="control-group">
				<!-- Username -->
				<label class="control-label"  for="username">Username</label>
				<div class="controls">
					<input type="text" id="username" name="username" placeholder="" class="input-xlarge" value="<?php echo $username; ?>">
				</div>
			</div>
			<div class="control-group">
				<!-- Password-->
				<label class="control-label" for="password">Password</label>
				<div class="controls">
					<input type="password" id="password" name="password" placeholder="" class="input-xlarge">
				</div>
			</div>
			<div class="control-group">
				<!-- Button -->
				<div class="controls">
					<button class="btn btn-success">Login</button>
				</div>
			</div>
		</fieldset>
	</form>
</div>