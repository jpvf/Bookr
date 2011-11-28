<div class="well">
  <h5>Elements</h5>
  <ul id="elements">
    <li><a href="#" data-type="text">Text</a></li>
    <li><a href="#" data-type="textarea">Textarea</a></li>
    <li><a href="#" data-type="dropdown">Dropdown</a></li>
    <li><a href="#" data-type="radio">Radio</a></li>
    <li><a href="#" data-type="checkbox">Checkbox</a></li>
  </ul>
</div>

<div class="well">
	<h5>Field Settings</h5>
		<ul id="field-settings">
			<li>
				<label>Name</label>
				<div>
					<input type='text' class="span2" id="settings-name">
				</div>
			</li>
			<li>
				<label>Label</label>
				<div>
					<input type='text' class="span2" id="settings-label">
				</div>
			</li>
			<li>
				<label>Description</label>
				<div>
					<textarea class="span3" id="settings-description"></textarea>
				</div>
			</li>			
			<li>
				<label>Class</label>
				<div>
					<select id="settings-class" class="span2">
						<?php for ($i = 1; $i < 13; $i++): ?>
							<option value="span<?php echo $i ?>">span<?php echo $i ?></option>
						<?php endfor; ?>
					</select>
				</div>
			</li>
		</ul>
</div>

<style>
	#field-settings {padding:0px;margin:0px;}
	#field-settings li{padding:5px;list-style-type:none}
	#field-settings label{width:auto}
	#main-form .selected{background:#FDF5D9}
	</style>
<div class="well">
  <h5>Form Settings</h5>
  <ul>
    <li><a href="#">Text</a></li>
    <li><a href="#">Dropdown</a></li>
    <li><a href="#">Radio</a></li>
    <li><a href="#">Checkbox</a></li>
  </ul>
</div>