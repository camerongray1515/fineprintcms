<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.pages.js'); ?>"></script>

<h3>Pages</h3>

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
	<?php echo top_buttons_link('pages/add', 'Add Page', 'success', 'plus'); ?>
</div>

<?php if ($pages): ?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Title</th>
				<th>Alias</th>
				<th>Layout</th>
				<th>Created</th>
				<th>Modified</th>
				<th>Actions</th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($pages as $page): ?>
				<tr data-page-id="<?php echo $page->id; ?>">
					<td><?php echo $page->title; ?></td>
					<td><?php echo $page->alias; ?></td>
					<td><?php echo $page->layout_name; ?></td>
					<td><?php echo date(TABLE_DATE_FORMAT, strtotime($page->created)); ?> by <?php echo $page->created_first_name; ?> <?php echo $page->created_last_name; ?></td>
					<td><?php echo date(TABLE_DATE_FORMAT, strtotime($page->modified)); ?> by <?php echo $page->modified_first_name; ?> <?php echo $page->modified_last_name; ?></td>
					<td>
						<a class="btn btn-danger delete-button" href="<?php echo admin_url("pages/delete/{$page->id}"); ?>" data-page-id="<?php echo $page->id; ?>"><i class="icon-trash icon-white"></i> Delete</a>
						<a class="btn btn-info" href="<?php echo admin_url("pages/edit/{$page->id}"); ?>"><i class="icon-edit icon-white"></i> Edit</a>
					</td>
				</tr>
			<?php endforeach; ?>			
		</tbody>
	</table>
<?php else: ?>
	<div class="alert alert-info"><strong>Notice:</strong> You have not added any pages yet!</div>
<?php endif; ?>