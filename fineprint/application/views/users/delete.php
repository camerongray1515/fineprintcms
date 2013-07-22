<h3>Delete User</h3>

<div class="alert alert-error">
	<p>Are you sure you want to delete this user?  This action cannot be undone!</p>
	<a class="btn" href="<?php echo admin_url('users'); ?>">No, Cancel</a>
	<a class="btn btn-danger" href="<?php echo admin_url("users/do_delete/$user_id/no_ajax"); ?>">Yes, Delete</a>
</div>