<div class="row">
	<div class="span16">
		<h2>Settings</h2>
		<?php echo form_open('admin/settings/save_settings'); ?>
		<ul>
			<?php foreach ($fields as $field): ?>
				<li>
					<label for='<?php echo $field->name ?>'>
						<?php echo $field->title ?>
						<small><?php echo $field->desc ?></small> 
					</label>
					<div>
						<?php echo $field->field ?>
					</div>
				</li>
			<?php endforeach; ?>
			<li>
				<?php echo form_button(array('class' => 'btn success primary', 'type' => 'submit', 'content' => 'Save'))?>
				Or <?php echo anchor('#', 'Cancel', 'class="btn small"'); ?>
			</li>
		</ul>
		<?php echo form_close() ?>
	</div>
</div>