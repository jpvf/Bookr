<?php 

class Template{
	
	private $title;
	private $submenu;
	private $section_title;
	private $section;
	private $content;
	private $inner_menu;
	public  $menu = TRUE;
	private static $instance;
	
	public static function getInstance()
	{
	    if ( ! self::$instance)
	    {
	        self::$instance = new Template;
	    }	    
	    return self::$instance;
	}
	

	function __construct()
	{
		$this->CI = get_instance();

		$this->CI->config->load('template');

		$this->set_template($this->CI->config->item('template'));	
		
		$this->set_title();			
	}

	function js($js = '')
	{
		Assets::js($js, TRUE);
		return $this;
	}

	function css($css = '')
	{
		Assets::css($css);
		return $this;
	}

	function set_template($template = '')
	{
		if (empty($template))
		{
			return $this;
		}

		$this->template = $template;
		return $this;
	}

	function set_title($title = '')
	{
		$this->title = ($title == '') ? $this->CI->config->item('title') : $title;
		return $this;
	}

	function set_section_title($section_title = '')
	{
		$this->section_title = $section_title;
		return $this;
	}

	function get_copyright()
	{
		return $this->CI->config->item('copyright');
	}

	function set_content($place = '', $content = '')
	{
		if ($content == '' OR $place == '')
		{
			return $this;			
		}	

		$this->content[$place] = $content;
		return $this;
	}

	function get_content()
	{
		return $this->content;
	}

	function json($data = array())
	{
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
		exit;
	}

	function render($data = array(), $return = FALSE)
	{
		if ( ! empty($this->content))
		{
			$template_data = array();

		    foreach ($this->content as $place => $content)
            {
                $template_data['template_'.$place] = $this->CI->load->view($content, $data, TRUE);    
            }
        } 

        if ( ! empty($this->section_title))
        {
        	$template_data['template_section_title'] = $this->section_title;
        }

        $template_data['template_title'] = $this->title;

		$this->CI->load->view($this->template, $template_data, $return);
	}

}