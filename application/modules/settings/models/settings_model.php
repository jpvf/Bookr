<?php

class Settings_Model extends MY_Model {

	protected $table = 'settings';

	protected $join  = array(
		'settings_categories' =>  'settings.id_category = settings_categories.id'
	);

	protected $id = 'uid';
	
}