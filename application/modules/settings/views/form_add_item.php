<div class="row">
	<div class="span16">
		<?php echo form_open('admin/settings/save_item'); ?>
		<ul>
			<li>
				<label for="name">
					Name
					<small>Name of the setting, used for the html id and name attribute</small>
				</label>
				<div>
					<input type="text" id="name" name="name">
				</div>
			</li>
			<li>
				<label for="title">
					Title
					<small>Title of the setting, the one inside the label</small>
				</label>
				<div>
					<input type="text" id="title" name="title">
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
				<label for="type">
					Type
					<small>Type of the setting, select, text etc</small>
				</label>
				<div>
					<select name='type' id='type'>
						<option>Select</option>
						<option>Text</option>
						<option>Textarea</option>
						<option>Password</option>
						<option>Select-multiple</option>
						<option>Radio</option>
						<option>Checkbox</option>
					</select>
				</div>
			</li>
			<li>
				<label for="options">
					Options
					<small>Depending on the field, if it is a multiple choice field it can contain options separated by the pipe char |</small>
				</label>
				<div>
					<textarea type="text" id="options" name="options" style="height: 114px;width: 577px;"></textarea>
				</div>
			</li>
			<li>
				<input type="hidden" name="category" value="<?php echo $category->uid ?>" />
				 <input type="submit" class="btn success" value="Save changes">&nbsp;<button type="reset" class="btn">Cancel</button>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>