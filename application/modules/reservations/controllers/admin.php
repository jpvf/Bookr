<?php

class Admin extends Admin_Controller {

	function index()
	{
		$this->load->library('table');
		$this->load->model('reservations_model');

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
				$reservations = $this->reservations_model->find_all();
				break;
		}	

		$filters = array();
		
		if (_get('reservation_num'))
		{
			$filters['reservations.reservation_num'] = _get('reservation_num');
		}
			

		if (_get('name'))
		{
			$filters['reservations.name'] = _get('name');
		}
			

		if (_get('last_name'))
		{
			$filters['reservations.last_name'] = _get('last_name');
		}
			

		if (_get('sailing_date'))
		{
			$filters['reservations.sailing_date'] = _get('sailing_date');
		}
			

		if (_get('ship'))
		{
			$filters['ships.id'] = _get('ship');
		}
			
		if ( ! empty($filters))
		{
			$reservations = $this->reservations_model->find_all($filters);
		}

		$this->table->set_heading(anchor('admin/reservations?order=reserva_'.($sort_column == 'reserva' && $sort_order == 'asc' ? 'desc' : 'asc'),'Reserva'), anchor('admin/reservations?order=pasajero_'.($sort_column == 'pasajero' && $sort_order == 'asc' ? 'desc' : 'asc'),'Pasajero'), anchor('admin/reservations?order=monto_'.($sort_column == 'monto' && $sort_order == 'asc' ? 'desc' : 'asc'),'Monto'), anchor('admin/reservations?order=impuestos_'.($sort_column == 'impuestos' && $sort_order == 'asc' ? 'desc' : 'asc'),'Impuestos'), anchor('admin/reservations?order=otros_'.($sort_column == 'otros' && $sort_order == 'asc' ? 'desc' : 'asc'),'Otros'), anchor('admin/reservations?order=fecha_de_reserva_'.($sort_column == 'fecha_de_reserva' && $sort_order == 'asc' ? 'desc' : 'asc'),'Fecha de Reserva'), anchor('admin/reservations?order=fecha_de_salida_'.($sort_column == 'fecha_de_salida' && $sort_order == 'asc' ? 'desc' : 'asc'),'Fecha de Salida'), anchor('admin/reservations?order=barco_'.($sort_column == 'barco' && $sort_order == 'asc' ? 'desc' : 'asc'),'Barco'));

		if ($reservations->num_rows() > 0)
		{
			foreach ($reservations->result() as $row)
			{
				$this->table->add_row(anchor("admin/reservation/{$row->reservation_num}/details","$row->reservation_num"), $row->name.' '.$row->last_name, $row->gross_rate, $row->taxes, $row->others, $row->reservation_date, $row->sailing_date, $row->ship_name);				
			}			
		}
		else
		{
			$this->table->add_row('There are no items to show.');
		}
		
		$this->load->model('ships/ships_model');
		$ships = $this->ships_model->find_all()->result();
		$options = array();
		foreach ($ships as $barco)
		{
			$options[$barco->id] = $barco->name;	
		}
		$data['ships'] = $options; 
					

		$data['table'] = $this->table->generate();

		$this->template->set_content('filters', 'form_filters')
					   ->set_content('main', 'index')
					   ->set_content('menu', 'menu/actions')
					   ->js('tablesorter.min')
					   ->render($data);
	}	

	function create()
	{
		
		$this->load->model('ships/ships_model');
		$ships = $this->ships_model->find_all()->result();
		$options = array();
		foreach ($ships as $barco)
		{
			$options[$barco->id] = $barco->name;	
		}
		$data['ships'] = $options; 
					
		$this->template->set_content('main', 'form_create')
					   ->set_content('menu', 'menu/actions')
					   ->render($data);
	}

	function details($reservation = 0)
	{
		if ($reservation == 0)
		{
			show_error('404');
		}
		
		$this->load->model('reservations_model');
		
		$this->load->model('ships/ships_model');
		$ships = $this->ships_model->find_all()->result();
		$options = array();
		foreach ($ships as $barco)
		{
			$options[$barco->id] = $barco->name;	
		}
		$data['ships'] = $options; 
					

		$reservation = $this->reservations_model->find($reservation);

		if ($reservation->num_rows() == 0)
		{
			show_error('404');
		}

		$data['reservation'] = $reservation->row();

		$this->template->set_content('main', 'details')
					   ->set_content('menu', 'menu/actions')
					   ->render($data);
	}

	function save()
	{
		$this->load->model('reservations_model');
		$saved = $this->reservations_model->save(array(
			'id'  => _post('id'),
			'reservation_num' => _post('reservation_num'),
			'name' => _post('name'),
			'last_name' => _post('last_name'),
			'gross_rate' => _post('gross_rate'),
			'taxes' => _post('taxes'),
			'others' => _post('others'),
			'reservation_date' => _post('reservation_date'),
			'sailing_date' => _post('sailing_date'),
			'created_at' => today(TRUE),
			'id_ship' => _post('id_ship'),
			'id_user' => $this->acl->id
		));

		if ( ! $saved)
		{
			Message::error('An error has occured during saving the category', 'admin/reservations');
		}

		Message::success('The category has been saved', 'admin/reservations');
	}

}