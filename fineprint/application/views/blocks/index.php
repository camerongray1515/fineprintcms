<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.blocks.js'); ?>"></script>

<h3>Blocks</h3>

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
	<?php echo top_buttons_link('blocks/add', 'Add Block', 'success', 'plus'); ?>
</div>

<?php if ($blocks): ?>
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
			<?php foreach ($blocks as $block): ?>
				<tr data-block-id="<?php echo $block->id; ?>">
					<td><?php echo $block->alias; ?></td>
					<td><?php echo $block->title; ?></td>
					<td><?php echo date(TABLE_DATE_FORMAT, strtotime($block->created)); ?> by <?php echo $block->created_first_name; ?> <?php echo $block->created_last_name; ?></td>
					<td><?php echo date(TABLE_DATE_FORMAT, strtotime($block->modified)); ?> by <?php echo $block->modified_first_name; ?> <?php echo $block->modified_last_name; ?></td>
					<td>
						<a class="btn btn-danger delete-button" href="<?php echo admin_url("blocks/delete/{$block->id}"); ?>" data-block-id="<?php echo $block->id; ?>"><i class="icon-trash icon-white"></i> Delete</a>
						<a class="btn btn-info" href="<?php echo admin_url("blocks/edit/{$block->id}"); ?>"><i class="icon-edit icon-white"></i> Edit</a>
					</td>
				</tr>
			<?php endforeach; ?>			
		</tbody>
	</table>
<?php else: ?>
	<div class="alert alert-info"><strong>Notice:</strong> You have not added any blocks yet!</div>
<?php endif; ?>