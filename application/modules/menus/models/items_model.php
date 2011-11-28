<?php

class Items_Model extends MY_Model {
	
	protected $table = 'menus_items';
	protected $join  = array(
		'menus' => 'menus_items.id_menu = menus.id'
	); 
	protected $id = 'uid';

	function find_by_position()
	{
		$this->db->order_by('menus_items.position DESC')
				 ->limit(1);

		return parent::find_by('menus.uid', _post('uid'));		
	}
}