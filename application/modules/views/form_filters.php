<div class="row">
	<div class="span16">

		<?php echo form_open('admin/', array('method' => 'get')); ?>
		<fieldset>
        	<legend>Filter By</legend>
        		<div class='clearfix'>
					
				</div>
				<input type="submit" class="btn small success" value="Filter">
				<?php echo anchor('admin/', 'Reset', array('class' => 'btn small')); ?>
			</fieldset>
		<?php echo form_close() ?>
	</div>
</div>
