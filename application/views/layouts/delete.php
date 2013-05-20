<h3>Delete Layout</h3>

<div class="alert alert-error">
	<p>Are you sure you want to delete this layout?  This action cannot be undone!</p>
	<a class="btn" href="<?php echo admin_url('layouts'); ?>">No, Cancel</a>
	<a class="btn btn-danger" href="<?php echo admin_url("layouts/do_delete/$layout_id/no_ajax"); ?>">Yes, Delete</a>
</div>