<h2>Settings - Categories</h2>
<div class="row">
	<div class="span16">
		<table class="zebra-striped sortable-table">
			<thead>
				<tr>
					<th class="blue header">Name</th>
					<th class="blue header">Description</th>
					<th class="blue header">Active</th>
					<th class="blue header">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($categories as $category) : ?>
				<tr>
					<td><?php echo anchor('admin/settings/'.$category->uid.'/details',$category->name); ?></td>
					<td><?php echo $category->description; ?></td>
					<td><?php echo ($category->active ? 'Yes' : 'No' ); ?></td>
					<td>
						<?php echo anchor('admin/settings/categories/'.$category->uid.'/edit', 'Edit') ?> |
						<?php echo anchor('admin/settings/categories/'.$category->uid.'/deactivate', 'Deactivate') ?>
					</td>
			    </tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>