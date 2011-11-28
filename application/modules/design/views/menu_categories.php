<div class="row">
	<div class="span16">
		<?php echo anchor('admin/design/new_menu', 'New menu', 'class="btn small"'); ?>
		<table class="tablesorter">
			<thead>
				<th>Name</th>
				<th>Description</th>
			</thead>
			<tbody>
				<?php foreach ($menus->result() as $menu): ?>
					<tr>
						<td><?php echo $menu->name ?></td>
						<td><?php echo $menu->description ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>