<div class="row">
	<div class="span16">
		<?php echo form_open('admin/products/save'); ?>
		<ul>
			<li>
				<label for='name'>Name</label>
			<input type='text' name='name' id='name' class='span10'>
		</li>
		<li>
			<label for='description'>Description</label>
			<textarea name="description" cols="90" rows="12" id="description" class="span10" ></textarea>
		</li>
		<li>
			<label for='id_category'>Category</label>
			<?php echo form_dropdown('id_category', $categories, '', "name='id_category' id='id_category'"); ?>
			</li>
			<li>
				<input type="submit" class="btn success" value="Save changes">&nbsp;<button type="reset" class="btn">Cancel</button>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>