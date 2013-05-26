<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.layouts.js'); ?>"></script>

<h3>Layouts</h3>

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
	<?php echo top_buttons_link('layouts/add', 'Add Layout', 'success', 'plus'); ?>
</div>

<?php if ($layouts): ?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Title</th>
				<th>Created</th>
				<th>Modified</th>
				<th>Actions</th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($layouts as $layout): ?>
				<tr data-layout-id="<?php echo $layout->id; ?>">
					<td><?php echo $layout->title; ?></td>
					<td><?php echo date(TABLE_DATE_FORMAT, strtotime($layout->created)); ?> by <?php echo $layout->created_first_name; ?> <?php echo $layout->created_last_name; ?></td>
					<td><?php echo date(TABLE_DATE_FORMAT, strtotime($layout->modified)); ?> by <?php echo $layout->modified_first_name; ?> <?php echo $layout->modified_last_name; ?></td>
					<td>
						<a class="btn btn-danger delete-button" href="<?php echo admin_url("layouts/delete/{$layout->id}"); ?>" data-layout-id="<?php echo $layout->id; ?>"><i class="icon-trash icon-white"></i> Delete</a>
						<a class="btn btn-info" href="<?php echo admin_url("layouts/edit/{$layout->id}"); ?>"><i class="icon-edit icon-white"></i> Edit</a>
						<?php if ($layout->default): ?>
							<button class="btn btn-success" disabled>Default</button>
						<?php else: ?>
							<a class="btn btn-success" href="<?php echo admin_url("layouts/set_default/{$layout->id}"); ?>" data-layout-id="<?php echo $layout->id; ?>">Set Default</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>			
		</tbody>
	</table>
<?php else: ?>
	<div class="alert alert-info"><strong>Notice:</strong> You have not added any layouts yet!</div>
<?php endif; ?>