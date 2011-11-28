<div class="row">
	<div class="span16">
		<?php echo form_open('admin/settings/save_category'); ?>
		<ul>
			<li>
				<label for="name">
					Name
					<small>Name of the setting, the one inside the label</small>
				</label>
				<div>
					<input type="text" id="name" name="name">
				</div>
			</li>
			<li>
				<label for='description'>
					Description
					<small>Description of the category, what is it for etc.</small>
				</label>
				<div>
					<textarea type="text" id="description" name="description" style="height: 114px;width: 577px;"></textarea>
				</div>
			</li>
			<li>
				 <input type="submit" class="btn success" value="Save changes">&nbsp;<button type="reset" class="btn">Cancel</button>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>