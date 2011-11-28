<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends Admin_Controller {

	public function index()
	{
		
		$this->template->set_content('main', 'welcome_message')
					   ->set_content('titulo', 'titulo')
					   ->set_section_title('Dashboard')
					   ->render();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */