<?php

/* SETTINGS */ 

class Admin extends Admin_Controller {
	
	function index()
	{
		$this->load->model('settings_model');
		$this->load->library('settings');

		$settings = $this->settings_model->find_all();

		$form = array();

		foreach ($settings->result() as $setting)
		{
			$form[] = (object) array(
				'field' => $this->settings->form_control($setting),
				'title' => $setting->title, 
				'name'	=> $setting->name,
				'desc'	=> $setting->description
			);
		}

		$data['fields'] = $form;

		$this->template->set_content('main', 'settings')
					   ->set_content('menu', 'menu/settings')
					   ->render($data);
	}

	function save_settings()
	{
		$this->load->model('settings_model');
		$this->load->model('settings_categories_model');

		foreach ($_POST as $key => $val)
		{
			$item = $this->settings_model->find_by('settings.name', $key);

			if ($item->num_rows() > 0)
			{
				$item = $item->row();
				$this->settings_model->save(array(
					'uid' => $item->uid,
					'value' => (is_array($val) ? implode(',', $val) :$val)
				));
			}
		}

		Message::success('Settings saved','admin/settings');
	}

	function categories()
	{
		$this->load->model('settings_model');
		$this->load->model('settings_categories_model');

		$data['categories'] = $this->settings_categories_model->find_all()->result();

		$this->template->set_content('main', 'index')
					   ->set_content('menu', 'menu/main')
					   ->js('tablesorter.min, settings')
					   ->render($data);
	}

	function add_category()
	{
		$this->template->set_content('main', 'form_add_category')
					   ->set_content('menu', 'menu/main')					   
					   ->render();
	}

	function save_category()
	{
		$this->load->model('settings_categories_model');
		
		$saved = $this->settings_categories_model->save(array(
			'name'        => _post('name'),
			'description' => _post('description'),
			'uid'         => _uid('settings_categories')
		));

		if ( ! $saved)
		{
			Message::error('An error has occured during saving the category', 'admin/settings');
		}

		Message::success('The category has been saved', 'admin/settings/categories');
	}

	function details($uid = NULL)
	{
		if (is_null($uid) OR ! is_numeric($uid))
		{
			show_error('404');	
		}

		$this->load->model('settings_categories_model');
		$this->load->model('settings_model');

		$category = $this->settings_categories_model->find_by('uid', $uid);

		if ($category->num_rows() == 0)
		{
			show_error('404');
		}

		$data['category'] = $category = $category->row();

		$data['items']	  = $this->settings_model->find_by('settings_categories.id', $category->id);

		$this->template->set_content('main', 'category_details')
					   ->set_content('menu', 'menu/details_menu')
					   ->js('tablesorter.min')
					   ->render($data);
	}

	function add_item($uid = NULL)
	{
		if (is_null($uid) OR ! is_numeric($uid))
		{
			show_error('404');	
		}

		$this->load->model('settings_categories_model');
		$this->load->model('settings_model');

		$category = $this->settings_categories_model->find_by('uid', $uid);

		if ($category->num_rows() == 0)
		{
			show_error('404');
		}
		
		$data['category'] = $category->row();
		
		$this->template->set_content('main', 'form_add_item')
					   ->set_content('menu', 'menu/details_menu')
					   ->render($data);		
	}

	function save_item()
	{
		$this->load->model('settings_categories_model');
		$this->load->model('settings_model');

		$category = $this->settings_categories_model->find_by('settings_categories.uid', _post('category'));

		if ($category->num_rows() == 0)
		{
			echo $this->db->last_query();
			exit;
			show_error('404');
		}

		$category = $category->row();

		$save = array(
			'name'        => _post('name'),
			'title'       => _post('title'),
			'description' => _post('description'),
			'type'        => _post('type'),
			'options'     => _post('options'),
			'id_category' => $category->id, 
			'uid'		  => _uid('settings')
		);

		if ($this->settings_model->save($save))
		{
			Message::success('The item was saved sucesfully', 'admin/settings/'.$category->uid.'/details');
		}
		Message::success('Something went wrong, try again', 'admin/settings/'.$category->uid.'/details');
	}

	function permissions()
	{
		
	}

	function save_permissions()
	{
		
	}

	function resources()
	{
		
	}
	
	function add_resources()
	{
		
	}

	function edit_resources()
	{
		
	}

	function save_resources()
	{
		
	}
}