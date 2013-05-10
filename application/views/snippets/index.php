<script type="text/javascript" src="<?php echo base_url('assets/js/fineprint.snippets.js'); ?>"></script>

<h3>Snippets</h3>

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
	<?php echo top_buttons_link('snippets/add', 'Add Snippet', 'success', 'plus'); ?>
</div>

<?php if ($snippets): ?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Alias</th>
				<th>Title</th>
				<th>Created</th>
				<th>Modified</th>
				<th>Actions</th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($snippets as $snippet): ?>
				<tr data-snippet-id="<?php echo $snippet->id; ?>">
					<td><?php echo $snippet->alias; ?></td>
					<td><?php echo $snippet->title; ?></td>
					<td><?php echo date(TABLE_DATE_FORMAT, strtotime($snippet->created)); ?> by <?php echo $snippet->created_first_name; ?> <?php echo $snippet->created_last_name; ?></td>
					<td><?php echo date(TABLE_DATE_FORMAT, strtotime($snippet->modified)); ?> by <?php echo $snippet->modified_first_name; ?> <?php echo $snippet->modified_last_name; ?></td>
					<td>
						<a class="btn btn-danger delete-button" href="<?php echo admin_url("snippets/delete/{$snippet->id}"); ?>" data-snippet-id="<?php echo $snippet->id; ?>"><i class="icon-trash icon-white"></i> Delete</a>
						<a class="btn btn-info" href="<?php echo admin_url("snippets/edit/{$snippet->id}"); ?>"><i class="icon-edit icon-white"></i> Edit</a>
					</td>
				</tr>
			<?php endforeach; ?>			
		</tbody>
	</table>
<?php else: ?>
	<div class="alert alert-info"><strong>Notice:</strong> You have not added any snippets yet!</div>
<?php endif; ?>