<h3>Delete Snippet</h3>

<div class="alert alert-error">
	<p>Are you sure you want to delete this snippet?  This action cannot be undone!</p>
	<a class="btn" href="<?php echo admin_url('snippets'); ?>">No, Cancel</a>
	<a class="btn btn-danger" href="<?php echo admin_url("snippets/do_delete/$snippet_id/no_ajax"); ?>">Yes, Delete</a>
</div>