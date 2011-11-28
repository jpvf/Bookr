<div class="row">
	<div class="span16">
		<h2>Crear nuevo item de menu</h2>
		<?php echo form_open('admin/menus/items_save'); ?>
		<ul>
			<li>
				<label for='title'>Titulo</label>
				<input type='text' name='title' id='title' class='xlarge'>
			</li>
			<li>
				<label for='uri'>Uri</label>
				<input type='text' name='uri' id='uri' class='xlarge'>
			</li>
			<li>
				<label for='segment'>Segmento</label>
				<input type='text' name='segment' id='segment' class='small'>
			</li>
			<li>
				<input type="hidden" name="uid" value="<?php echo $uid ?>">
				<input type="submit" class="btn success" value="Guardar item">&nbsp;
				<?php echo anchor("admin/menus/$uid/details", 'Cancelar') ?>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>