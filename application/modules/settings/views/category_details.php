<h2>Settings - Categories</h2>
<div class="row">
	<div class="span16">
		<table class="zebra-striped sortable-table">
			<thead>
				<tr>
					<th class="blue header">Title</th>
					<th class="blue header">Name</th>
					<th class="blue header">Description</th>
					<th class="blue header">Type</th>
					<th class="blue header">Active</th>
					<th class="blue header">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<?php if ($items->num_rows() > 0) : ?>
				<?php foreach ($items->result() as $item) : ?>
			 		<tr>
						<td><?php echo anchor('admin/settings/'.$item->uid.'/item',$item->title); ?></td>
						<td><?php echo $item->name; ?></td>
						<td><?php echo $item->description; ?></td>
						<td><?php echo $item->type; ?></td>
						<td><?php echo ($item->active ? 'Yes' : 'No' ); ?></td>
						<td>
							<?php echo anchor('admin/settings/categories/'.$item->uid.'/edit_item', 'Edit') ?> |
							<?php echo anchor('admin/settings/categories/'.$item->uid.'/deactivate_item', 'Deactivate') ?>
						</td>
				    </tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td>There are no items to show yet</td>
			    </tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>