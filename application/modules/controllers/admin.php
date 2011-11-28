<?php

class Admin extends Admin_Controller {

	function index()
	{
		$this->load->library('table');
		$this->load->model('_model');

		$this->table->set_template(array(
		    'heading_cell_start'  => '<th class="blue header">',
		    'heading_cell_end'    => '</th>',
		    'table_open'          => '<table class="sortable-table zebra-striped">',
		));

		switch($this->input->get('scope')) 
		{
			
			default:
				$ = $this->_model->find_all();
				break;
		}	

		$filters = array();
		
		if ( ! empty($filters))
		{
			$ = $this->_model->find_all($filters);
		}

		$this->table->set_heading('');

		if ($->num_rows() > 0)
		{
			foreach ($->result() as $row)
			{
				$this->table->add_row();				
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
					   ->render($data);
	}

	function details($ = 0)
	{
		if ($ == 0)
		{
			show_error('404');
		}
		
		$this->load->model('_model');
		

		$ = $this->_model->find($);

		if ($->num_rows() == 0)
		{
			show_error('404');
		}

		$data[''] = $->row();

		$this->template->set_content('main', 'details')
					   ->set_content('menu', 'menu/actions')
					   ->render($data);
	}

	function save()
	{
		$this->load->model('_model');
		$saved = $this->_model->save(array(
			'id'  => _post('id'),
			
		));

		if ( ! $saved)
		{
			Message::error('An error has occured during saving the category', 'admin/');
		}

		Message::success('The category has been saved', 'admin/');
	}

}