<?php

class Admin extends Admin_Controller {

	function index()
	{
		$this->load->library('table');
		$this->load->model('ships_model');

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
			
			default:
				$ships = $this->ships_model->find_all();
				break;
		}	

		$filters = array();
		
		if (_get('name'))
		{
			$filters['ships.name'] = _get('name');
		}
			

		if (_get('abbreviature'))
		{
			$filters['ships.abbreviature'] = _get('abbreviature');
		}
			
		if ( ! empty($filters))
		{
			$ships = $this->ships_model->find_all($filters);
		}

		$this->table->set_heading(anchor('admin/ships?order=nombre_'.($sort_column == 'nombre' && $sort_order == 'asc' ? 'desc' : 'asc'),'Nombre'), anchor('admin/ships?order=abreviatura_'.($sort_column == 'abreviatura' && $sort_order == 'asc' ? 'desc' : 'asc'),'Abreviatura'), anchor('admin/ships?order=active_'.($sort_column == 'active' && $sort_order == 'asc' ? 'desc' : 'asc'),'Active'));

		if ($ships->num_rows() > 0)
		{
			foreach ($ships->result() as $row)
			{
				$this->table->add_row(anchor("admin/ships/{$row->id}/details","$row->name"), $row->abbreviature, active_to_string($row->active));				
			}			
		}
		else
		{
			$this->table->add_row('There are no items to show.');
		}
		

		$data['table'] = $this->table->generate();

		$this->template->set_content('filters', 'form_filters')
					   ->set_content('main', 'index')
					   ->set_content('menu', 'menu/actions')
					   ->js('tablesorter.min')
					   ->render($data);
	}	

	function create()
	{
		$data = array();
		$this->template->set_content('main', 'form_create')
					   ->set_content('menu', 'menu/actions')
					   ->render($data);
	}

	function details($ship = 0)
	{
		if ($ship == 0)
		{
			show_error('404');
		}
		
		$this->load->model('ships_model');
		

		$ship = $this->ships_model->find($ship);

		if ($ship->num_rows() == 0)
		{
			show_error('404');
		}

		$data['ship'] = $ship->row();

		$this->template->set_content('main', 'details')
					   ->set_content('menu', 'menu/actions')
					   ->render($data);
	}

	function save()
	{
		$this->load->model('ships_model');
		$saved = $this->ships_model->save(array(
			'id'  => _post('id'),
			'name' => _post('name'),
			'abbreviature' => _post('abbreviature')
		));

		if ( ! $saved)
		{
			Message::error('An error has occured during saving the category', 'admin/ships');
		}

		Message::success('The category has been saved', 'admin/ships');
	}

}