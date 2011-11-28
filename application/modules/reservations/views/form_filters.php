<div class="well">
		<?php echo form_open('admin/reservations', array('method' => 'get', 'class' => 'form-stacked')); ?>
		<fieldset>
        	<legend>Filter By</legend>
        		<div class='clearfix'>
					<label for='reservation_num'>Número de reserva</label>
			<div class='input'><input type='text' name='reservation_num' id='reservation_num' class='medium' value='<?php echo _get('reservation_num') ?>'></div>
		</div>
		<div class='clearfix'>
			<label for='name'>Nombre del pasajero</label>
			<div class='input'><input type='text' name='name' id='name' class='medium' value='<?php echo _get('name') ?>'></div>
		</div>
		<div class='clearfix'>
			<label for='last_name'>Apellido del pasajero</label>
			<div class='input'><input type='text' name='last_name' id='last_name' class='medium' value='<?php echo _get('last_name') ?>'></div>
		</div>
		<div class='clearfix'>
			<label for='sailing_date'>Fecha de salida</label>
			<div class='input'><input type='text' name='sailing_date' id='sailing_date' class='medium' value='<?php echo _get('sailing_date') ?>'></div>
		</div>
		<div class='clearfix'>
			<label for='ship'>Barco</label>
			<?php echo form_dropdown('ship', $ships, _get('ship'), "name='ship' id='ship' class='medium'"); ?>
				</div>
				<input type="submit" class="btn small success" value="Filter">
				<?php echo anchor('admin/reservations', 'Reset'); ?>
			</fieldset>
		<?php echo form_close() ?>
</div>
