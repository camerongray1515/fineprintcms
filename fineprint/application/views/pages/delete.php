<h3>Delete Page</h3>

<div class="alert alert-error">
	<p>Are you sure you want to delete this page?  This action cannot be undone!</p>
	<a class="btn" href="<?php echo admin_url('pages'); ?>">No, Cancel</a>
	<a class="btn btn-danger" href="<?php echo admin_url("pages/do_delete/$page_id/no_ajax"); ?>">Yes, Delete</a>
</div>