<?php

class Admin extends Admin_Controller {

	function index()
	{
		$this->load->library('table');
		$this->load->model('menus_model');

		$this->table->set_template(array(
		    'heading_cell_start'  => '<th class="blue header">',
		    'heading_cell_end'    => '</th>',
		    'table_open'          => '<table class="zebra-striped">',
		));

		$sort_column = '';
		$sort_column = '';


		if (_get('order'))
		{
			$order_type = strrchr(_get('order'), '_');
			$sort_order = str_replace('_', '', $order_type);
			$sort_column = str_replace($order_type, '', _get('order'));
			$this->db->order_by($sort_column, $sort_order);
		}

		switch($this->input->get('scope')) 
		{
			
			default:
				$menus = $this->menus_model->find_all();
				break;
		}	

		$filters = array();
		
		if (_get('name'))
		{
			$filters['menus.name'] = _get('name');
		}
			
		if ( ! empty($filters))
		{
			$menus = $this->menus_model->find_all($filters);
		}

		$this->table->set_heading(
			anchor('admin/menus','Titulo'),
			anchor('admin/menus','Nombre'), 
			anchor('admin/menus','Descripción')
		);

		if ($menus->num_rows() > 0)
		{
			foreach ($menus->result() as $row)
			{
				$this->table->add_row(
					anchor("admin/menus/{$row->uid}/details","$row->title"),
					$row->name, 
					$row->description
				);				
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
		
		$this->template->set_content('main', 'form_create')
					   ->set_content('menu', 'menu/actions')
					   ->render();
	}

	function details($menu = 0)
	{
		if ($menu == 0)
		{
			show_error('404');
		}
		
		$this->load->model('menus_model');
		$this->load->model('items_model');		

		$items = $this->items_model->find_by('menus.uid', $menu);

		if ($items->num_rows() > 0)
		{
			$this->load->library('table');

			$this->table->set_template(array(
			    'heading_cell_start'  => '<th class="blue header">',
			    'heading_cell_end'    => '</th>',
			    'table_open'          => '<table class="zebra-striped">',
			));

			foreach ($items->result() as $item)
			{
				$this->table->add_row(
					anchor("admin/menus/$menu/items_details/{$item->uid}", $item->title),
					$item->position,
					$item->uri,
					$item->segment,
					($item->active == 1 ? 'Si' : 'No')
				);
			}

			$this->table->set_heading(
				'Titulo',
				'Posicion',
				'URI',
				'Segmento',
				'Activo'
			);

			$table = $this->table->generate();
		}
		else
		{
			$table = '<div class="empty">No hay items para mostrar. '.anchor("admin/menus/{$menu}/create_item", 'Crear un nuevo item para este menu').'</div>';
		}

		$data['uid']   = $menu;
		$data['menu_items'] = $table;

		$this->template->set_content('main', 'details')
					   ->set_content('menu', 'menu/menu_items')
					   ->render($data);
	}

	function save()
	{
		$this->load->model('menus_model');
		$saved = $this->menus_model->save(array(
			'uid'  => _post('ui', _uid('menus')),
			'name' => _post('name'),
			'title'=> _post('title'),
			'description' => _post('description')
		));

		if ( ! $saved)
		{
			Message::error('An error has occured during saving the category', 'admin/menus');
		}

		Message::success('The category has been saved', 'admin/menus');
	}

	function create_item($menu = 0)
	{
		if ($menu == 0)
		{
			show_error('404');
		}
		
		$this->load->model('menus_model');
		$this->load->model('items_model');		

		$items = $this->menus_model->find_by('menus.uid', $menu);

		if ($items->num_rows() == 0)
		{
			Messages::warning('El item requerido no ha sido encontrado', 'admin/menus/');
		}

		$data['uid'] = $menu;

		$this->template->set_content('main', 'create_item')
					   ->render($data);
	}

	function items_save()
	{
		$this->load->model('items_model');
		$this->load->model('menus_model');

		$items = $this->items_model->find_by_position(_post('uid'));

		if ($items->num_rows() == 0)
		{
			$position = 1;
		}
		else
		{
			$position = $items->row()->position + 1;
		}

		$menu = $this->menus_model->find(_post('uid'))->row()->id;

		$saved = $this->items_model->save(array(
			'uid'     => _post('item_uid', _uid('menus_items')),
			'title'   => _post('title'),
			'segment' => _post('segment'),
			'uri'	  => _post('uri'),
			'position'=> $position,
			'id_menu' => $menu
		));

		if ( ! $saved)
		{
			Message::error('Error guardando el item', 'admin/menus');
		}

		Message::success('Item del menu guardado', "admin/menus/"._post('uid')."/details");
	}
}