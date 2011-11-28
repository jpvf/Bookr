<div class="row">
	<div class="span16">
		<h3>Generate Folder Structure</h3>
		<?php echo form_open('admin/generators/create_folders'); ?>
		<ul>
			<li>
				<label for="module_name">
					Module name
					<small>Creates the module folder with this name</small>
				</label>
				<div>
					<input type="text" id="module_name" name="module_name" />
				</div>
			</li>
			<li>
				<label for="mvc">
					Controllers, Models, Views
					<small>Creates the controllers, models and views folders</small>
				</label>
				<div>
					<input type="checkbox" id="mvc" name="mvc" checked="checked" />
				</div>
			</li>
			<li>
				<label for="admin-controller">
					Admin Controller
					<small>Creates the admin controller file for the module.</small>
				</label>
				<div>
					<input type="checkbox" id="admin-controller" name="admin-controller"/>
				</div>
			</li>
			<li>
				<label for="front-controller">
					Front Controller
					<small>Creates the font controller file for the module</small>
				</label>
				<div>
					<input type="checkbox" id="front-controller" name="front-controller"/>
				</div>
			</li>
			<li>
				<label for="model-file">
					Model File
					<small>Creates the model file</small>
				</label>
				<div>
					<input type="checkbox" id="model-file" name="model-file" />
				</div>
			</li>
			<li>
				<label for="config">
					Configurations
					<small>Creates the Config folder</small>
				</label>
				<div>
					<input type="checkbox" id="config" name="config" />
				</div>
			</li>
			<li>
				<label for="lang">
					Languages
					<small>Creates the laguage folder</small>
				</label>
				<div>
					<input type="checkbox" id="lang" name="lang" />
				</div>
			</li>
			<li>
				<label for="libraries">
					Libraries
					<small>Creates the Libraries folders</small>
				</label>
				<div>
					<input type="checkbox" id="libraries" name="libraries" />
				</div>
			</li>
			<li>
				<label for="helpers">
					Helpers
					<small>Creates the Helpers folders</small>
				</label>
				<div>
					<input type="checkbox" id="helpers" name="helpers" />
				</div>
			</li>
			<li>
		        <input type="submit" class="btn success" value="Save changes">&nbsp;<button type="reset" class="btn">Cancel</button>
			</li>
		</ul>
		<?php echo form_close(); ?>
	</div>
</div>