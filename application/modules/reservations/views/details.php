<div class="row">
	<div class="span16">
		<?php echo form_open('admin/reservations/save'); ?>
		<ul>
			<li>
				<label for='reservation_num'>Número de reserva</label>
			<input type='text' name='reservation_num' id='reservation_num' class='span10' value='<?php echo $reservation->reservation_num ?>'>
		</li>
		<li>
			<label for='name'>Nombre del pasajero</label>
			<input type='text' name='name' id='name' class='span10' value='<?php echo $reservation->name ?>'>
		</li>
		<li>
			<label for='last_name'>Apellido del pasajero</label>
			<input type='text' name='last_name' id='last_name' class='span10' value='<?php echo $reservation->last_name ?>'>
		</li>
		<li>
			<label for='gross_rate'>Monto</label>
			<input type='text' name='gross_rate' id='gross_rate' class='span10' value='<?php echo $reservation->gross_rate ?>'>
		</li>
		<li>
			<label for='taxes'>Impuestos</label>
			<input type='text' name='taxes' id='taxes' class='span10' value='<?php echo $reservation->taxes ?>'>
		</li>
		<li>
			<label for='others'>Otros</label>
			<input type='text' name='others' id='others' class='span10' value='<?php echo $reservation->others ?>'>
		</li>
		<li>
			<label for='reservation_date'>Fecha de Reserva</label>
			<input type='text' name='reservation_date' id='reservation_date' class='span10' value='<?php echo $reservation->reservation_date ?>'>
		</li>
		<li>
			<label for='sailing_date'>Fecha de Salida</label>
			<input type='text' name='sailing_date' id='sailing_date' class='span10' value='<?php echo $reservation->sailing_date ?>'>
		</li>
		<li>
			<label for='id_ship'>Barco</label>
			<?php echo form_dropdown('id_ship', $ships, $reservation->id_ship, "name='id_ship' id='id_ship'"); ?>
			</li>
			<li>
				<input type="hidden" name="id" value="<?php echo $reservation->id ?>" />
				<input type="submit" class="btn success" value="Save changes">&nbsp;<button type="reset" class="btn">Cancel</button>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>
