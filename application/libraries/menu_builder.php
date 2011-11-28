<?php

class Menu_Builder {
	
	public static function get_menu($name = '') 
	{
		if ($name == '')
		{
			return FALSE;
		}

		$CI = get_instance();
		$CI->load->model('menus/menus_model');
		$CI->load->model('menus/items_model');
		$items = $CI->items_model->find_by('menus.name', $name)->result();
		$menu = '';
		$active_class = ' class="active"'; 

		foreach ($items as $item)
		{
			$item->title = anchor($item->uri, $item->title);

			if (strpos($item->uri, '/') !== FALSE)
			{
				$item->uri = explode('/', $item->uri);
				$item->uri = $item->uri[$item->segment - 1];
			}

			$active = ( ($item->uri == $CI->uri->segment($item->segment)) ? $active_class : ''); 
			
			if ($item->default == 1 AND ! $CI->uri->segment($item->segment))
			{
				$active = $active_class;
			} 

			$menu .= "<li{$active}>{$item->title}</li>";
		}

		return "<ul class='nav'>$menu</ul>";
	}


}