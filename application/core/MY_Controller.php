<?php 

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function _remap($method, $args) {
        $this->before();        

        $class = get_class($this);

        if (is_callable(array($this, $method)))
        {
        	call_user_func_array(array($this, $method), $args);        	
        }
        else
        {
			show_404("{$class}/{$method}");
        }
        
        $this->after();
    }
    
    protected function before() {}
    protected function after() {}

}