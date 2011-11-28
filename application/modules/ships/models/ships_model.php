<?php

class Ships_Model extends MY_Model {
	
	protected $table = 'Ships';
	protected $join  = array(
		
	);
	protected $id    = 'id';
	protected $select = 'ships.id, ships.name, ships.abbreviature, ships.active';

}