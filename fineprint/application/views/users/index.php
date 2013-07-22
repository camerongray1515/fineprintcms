<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.users.js'); ?>"></script>

<h3>Manage Users</h3>

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

<div class="top-buttons">
	<?php echo top_buttons_link('users/add', 'Add User', 'success', 'plus'); ?>
</div>

<table class="table table-hover">
	<thead>
		<tr>
			<th>Name</th>
			<th>Username</th>
			<th>Email</th>
			<th>Last Logged In</th>
			<th>Role</th>
			<th>Actions</th>
		</tr>
	</thead>
	
	<tbody>
		<?php foreach ($users as $user): ?>
			<tr data-user-id="<?php echo $user->id; ?>">
				<td><?php echo "{$user->first_name} {$user->last_name}"; ?></td>
				<td><?php echo $user->username; ?></td>
				<td><?php echo $user->email; ?></td>
				<td><?php echo date(TABLE_DATE_FORMAT, strtotime($user->last_logged_in)); ?> from <?php echo $user->last_ip; ?></td>
				<td><?php echo $user->role_name; ?></td>
				<td>
					<a class="btn btn-danger delete-button" href="<?php echo admin_url("users/delete/{$user->id}"); ?>" data-user-id="<?php echo $user->id; ?>"><i class="icon-trash icon-white"></i> Delete</a>
					<a class="btn btn-info" href="<?php echo admin_url("users/edit/{$user->id}"); ?>"><i class="icon-edit icon-white"></i> Edit</a>
				</td>
			</tr>
		<?php endforeach; ?>			
	</tbody>
</table>