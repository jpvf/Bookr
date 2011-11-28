<?php

class Admin_Controller extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->template->set_template('admin');
	}

	protected function before() 
	{
		//$this->output->enable_profiler(true);
		if ( ! $this->auth->is_logged_in('admin'))
		{
			$this->auth->login('admin', 'devel', 'admin');
		}
	}

}