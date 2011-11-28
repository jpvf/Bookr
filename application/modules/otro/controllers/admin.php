<?php

class Admin extends Admin_Controller {
	
	function index()
	{
		$this->template->set_content('main', 'new_form')
					   ->render();
	}

}