<?php

class Admin extends Admin_Controller {
	
	function index()
	{
		
		$this->template->set_content('main', 'index')
					   ->set_content('menu', 'sidebar/index')
					   ->render();
	}
}