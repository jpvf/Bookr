<div class="row">
	<div class="span16">

		<?php echo form_open('admin/ships', array('method' => 'get')); ?>
		<fieldset>
        	<legend>Filter By</legend>
        		<div class='clearfix'>
					<label for='name'>Nombre del barco</label>
			<div class='input'><input type='text' name='name' id='name' class='xlarge' value='<?php echo _get('name') ?>'></div>
		</div>
		<div class='clearfix'>
			<label for='abbreviature'>Abreviatura</label>
			<div class='input'><input type='text' name='abbreviature' id='abbreviature' class='xlarge' value='<?php echo _get('abbreviature') ?>'></div>
				</div>
				<input type="submit" class="btn small success" value="Filter">
				<?php echo anchor('admin/ships', 'Reset', array('class' => 'btn small')); ?>
			</fieldset>
		<?php echo form_close() ?>
	</div>
</div>
