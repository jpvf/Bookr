<div class="row">
	<div class="span16">
		<?php echo form_open('admin/ships/save'); ?>
		<ul>
			<li>
				<label for='name'>Nombre del barco</label>
			<input type='text' name='name' id='name' class='span10'>
		</li>
		<li>
			<label for='abbreviature'>Abreviatura del nombre</label>
			<input type='text' name='abbreviature' id='abbreviature' class='span10'>
			</li>
			<li>
				<input type="submit" class="btn success" value="Save changes">&nbsp;<button type="reset" class="btn">Cancel</button>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>