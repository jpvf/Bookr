<?php echo form_open('admin/generators/generate_form'); ?>
	<ul id="main-form">
		<li>
			<input type="submit" class="btn success" value="Generate Form">&nbsp;<button type="reset" class="btn">Cancel</button>
			<input type="hidden" name="content" id="content">
		</li>
	</ul>
<?php echo form_close(); ?>