<h3>Delete Role</h3>

<div class="alert alert-error">
	<p>Are you sure you want to delete this role?  This action cannot be undone!</p>
	<a class="btn" href="<?php echo admin_url('roles'); ?>">No, Cancel</a>
	<a class="btn btn-danger" href="<?php echo admin_url("roles/do_delete/$role_id/no_ajax"); ?>">Yes, Delete</a>
</div>