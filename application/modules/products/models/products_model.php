<?php

class Products_Model extends MY_Model {
	
	protected $table = 'Products';
	protected $join  = array(
		'categories' => 'categories.id = products.id_category|left',
		'users' => 'users.id = products.id_user'
	);
	protected $id    = 'id';
	protected $select = 'products.id, users.id as user_id, id_category, products.name, products.description, users.first_name, users.last_name, products.created_at, products.active, categories.name as category, users.email as ee';

}