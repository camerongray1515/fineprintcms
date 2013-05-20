<h3>Delete Block</h3>

<div class="alert alert-error">
	<p>Are you sure you want to delete this block?  This action cannot be undone!</p>
	<a class="btn" href="<?php echo admin_url('blocks'); ?>">No, Cancel</a>
	<a class="btn btn-danger" href="<?php echo admin_url("blocks/do_delete/$block_id/no_ajax"); ?>">Yes, Delete</a>
</div>