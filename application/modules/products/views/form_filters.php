<div class="row">
	<div class="span16">

		<?php echo form_open('admin/products', array('method' => 'get')); ?>
		<fieldset>
        	<legend>Filter By</legend>
        		<div class='clearfix'>
					<label for='name'>Name</label>
			<div class='input'><input type='text' name='name' id='name' class='xlarge' value='<?php echo _get('name') ?>'></div>
		</div>
		<div class='clearfix'>
			<label for='first_name'>First Name</label>
			<div class='input'><input type='text' name='first_name' id='first_name' class='xlarge' value='<?php echo _get('first_name') ?>'></div>
		</div>
		<div class='clearfix'>
			<label for='last_name'>Last Name</label>
			<div class='input'><input type='text' name='last_name' id='last_name' class='xlarge' value='<?php echo _get('last_name') ?>'></div>
		</div>
		<div class='clearfix'>
			<label for='created_at'>Created at</label>
			<div class='input'><input type='text' name='created_at' id='created_at' class='xlarge' value='<?php echo _get('created_at') ?>'></div>
		</div>
		<div class='clearfix'>
			<label for='category'>Category</label>
			<?php echo form_dropdown('category', $categories, _get('category'), "name='category' id='category'"); ?>
				</div>
				<input type="submit" class="btn small success" value="Filter">
				<?php echo anchor('admin/products', 'Reset', array('class' => 'btn small')); ?>
			</fieldset>
		<?php echo form_close() ?>
	</div>
</div>
