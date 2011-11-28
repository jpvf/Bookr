<div class="well">

	<?php echo form_open('admin/menus', array('method' => 'get', 'class' => 'form-stacked')); ?>
	<h4>Filtrar</h4>
	<div class='clearfix'>
		<label for='name'>Nombre</label>
		<div>
			<input type='text' name='name' id='name' class='medium' value='<?php echo _get('name') ?>'>
		</div>
	</div>
	<input type="submit" class="btn small" value="Filtrar">
	<?php echo anchor('admin/menus', 'Reiniciar filtros'); ?>
	<?php echo form_close() ?>
</div>