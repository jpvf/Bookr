<?php

class Admin extends Admin_Controller {
	
	function index()
	{
		$this->template->set_content('main', 'main')
					   ->set_content('menu', 'menu/main')
					   ->render();
	}


	function menu()
	{
		$this->load->model('menus_model');
		$data['menus'] = $this->menus_model->find_all();

		$this->template->set_content('main', 'menu_categories')
					   ->set_content('menu', 'menu/main')
					   ->render($data);
	}

	function new_menu()
	{
		
	}
}