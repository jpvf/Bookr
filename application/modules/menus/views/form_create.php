<div class="row">
	<div class="span16">
		<?php echo form_open('admin/menus/save'); ?>
		<ul>
			<li>
				<label for='name'>Nombre</label>
			<input type='text' name='name' id='name' class='span10'>
			</li>
			<li>
				<label for='title'>Titulo</label>
				<input type='text' name='title' id='title' class='span10'>
			</li>
			<li>
				<label for='description'>Descripcion</label>
				<textarea name="description" cols="90" rows="12" id="description" class="span10" ></textarea>
			</li>
			<li>
				<input type="submit" class="btn success" value="Save changes">&nbsp;<button type="reset" class="btn">Cancel</button>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>