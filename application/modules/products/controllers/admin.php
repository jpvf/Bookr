<?php

class Admin extends Admin_Controller {

	function index()
	{
		$this->load->library('table');
		$this->load->model('products_model');

		$this->table->set_template(array(
		    'heading_cell_start'  => '<th class="blue header">',
		    'heading_cell_end'    => '</th>',
		    'table_open'          => '<table class="zebra-striped">',
		));

		$sort_column = '';
		$sort_column = '';


		if (_get('order'))
		{
			if (preg_match('/(asc|desc)/i', _get('order')))
			{				
				$order_type = strrchr(_get('order'), '_');
				$sort_order = str_replace('_', '', $order_type);
			}
			$sort_column = str_replace($order_type, '', _get('order'));
			$this->db->order_by($sort_column, $sort_order);
		}

		switch($this->input->get('scope')) 
		{
			case 'active':
				$products = $this->products_model->find_by('products.active', 1);
				break;
case 'id':
				$products = $this->products_model->find_by('categories.id', 2);
				break;
			default:
				$products = $this->products_model->find_all();
				break;
		}	

		$filters = array();
		
		if (_get('name'))
		{
			$filters['products.name'] = _get('name');
		}
			

		if (_get('first_name'))
		{
			$filters['users.first_name'] = _get('first_name');
		}
			

		if (_get('last_name'))
		{
			$filters['users.last_name'] = _get('last_name');
		}
			

		if (_get('created_at'))
		{
			$filters['products.created_at'] = _get('created_at');
		}
			

		if (_get('category'))
		{
			$filters['categories.id'] = _get('category');
		}
			
		if ( ! empty($filters))
		{
			$products = $this->products_model->find_all($filters);
		}

		$this->table->set_heading(anchor('admin/products?order=name_'.($sort_column == 'name' && $sort_order == 'asc' ? 'desc' : 'asc'),'Name'), anchor('admin/products?order=description_'.($sort_column == 'description' && $sort_order == 'asc' ? 'desc' : 'asc'),'Description'), anchor('admin/products?order=created_by_'.($sort_column == 'created_by' && $sort_order == 'asc' ? 'desc' : 'asc'),'Created By'), anchor('admin/products?order=created_at_'.($sort_column == 'created_at' && $sort_order == 'asc' ? 'desc' : 'asc'),'Created at'), anchor('admin/products?order=active_'.($sort_column == 'active' && $sort_order == 'asc' ? 'desc' : 'asc'),'Active'), anchor('admin/products?order=category_'.($sort_column == 'category' && $sort_order == 'asc' ? 'desc' : 'asc'),'Category'), anchor('admin/products?order=user_email_'.($sort_column == 'user_email' && $sort_order == 'asc' ? 'desc' : 'asc'),'User email'));

		if ($products->num_rows() > 0)
		{
			foreach ($products->result() as $row)
			{
				$this->table->add_row(anchor("admin/products/{$row->id}/details","$row->name"), $row->description, $row->first_name.' '.$row->last_name, $row->created_at, active_to_string($row->active), $row->category, anchor("admin/users/{$row->user_id}/details","$row->ee"));				
			}			
		}
		else
		{
			$this->table->add_row('There are no items to show.');
		}
		
		$this->load->model('categories/categories_model');
		$categories = $this->categories_model->find_all()->result();
		$options = array();
		foreach ($categories as $category)
		{
			$options[$category->id] = $category->name;	
		}
		$data['categories'] = $options; 
					

		$data['table'] = $this->table->generate();

		$this->template->set_content('filters', 'form_filters')
					   ->set_content('main', 'index')
					   ->set_content('menu', 'menu/actions')
					   ->js('tablesorter.min')
					   ->render($data);
	}	

	function create()
	{
		
		$this->load->model('categories/categories_model');
		$categories = $this->categories_model->find_all()->result();
		$options = array();
		foreach ($categories as $category)
		{
			$options[$category->id] = $category->name;	
		}
		$data['categories'] = $options; 
					
		$this->template->set_content('main', 'form_create')
					   ->set_content('menu', 'menu/actions')
					   ->render($data);
	}

	function details($product = 0)
	{
		if ($product == 0)
		{
			show_error('404');
		}
		
		$this->load->model('products_model');
		
		$this->load->model('categories/categories_model');
		$categories = $this->categories_model->find_all()->result();
		$options = array();
		foreach ($categories as $category)
		{
			$options[$category->id] = $category->name;	
		}
		$data['categories'] = $options; 
					

		$product = $this->products_model->find($product);

		if ($product->num_rows() == 0)
		{
			show_error('404');
		}

		$data['product'] = $product->row();

		$this->template->set_content('main', 'details')
					   ->set_content('menu', 'menu/actions')
					   ->render($data);
	}

	function save()
	{
		$this->load->model('products_model');
		$saved = $this->products_model->save(array(
			'id'  => _post('id'),
			'name' => _post('name'),
			'description' => _post('description'),
			'created_at' => today(TRUE),
			'id_category' => _post('id_category'),
			'id_user' => $this->acl->id
		));

		if ( ! $saved)
		{
			Message::error('An error has occured during saving the category', 'admin/products');
		}

		Message::success('The category has been saved', 'admin/products');
	}

}