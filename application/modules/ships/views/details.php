<div class="row">
	<div class="span16">
		<?php echo form_open('admin/ships/save'); ?>
		<ul>
			<li>
				<label for='name'>Nombre del barco</label>
			<input type='text' name='name' id='name' class='span10' value='<?php echo $ship->name ?>'>
		</li>
		<li>
			<label for='abbreviature'>Abreviatura del nombre</label>
			<input type='text' name='abbreviature' id='abbreviature' class='span10' value='<?php echo $ship->abbreviature ?>'>
			</li>
			<li>
				<input type="hidden" name="id" value="<?php echo $ship->id ?>" />
				<input type="submit" class="btn success" value="Save changes">&nbsp;<button type="reset" class="btn">Cancel</button>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>
