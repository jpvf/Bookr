<?php

class Assets
{
	private static $temp_js = array();
	private static $css 	= array();
	private static $js  	= array();
	
	static function css($css = '')
	{		
	    $css = self::_string_to_array($css);        
		
		foreach($css as $row){
			
		    $path = RUTA_CSS.$row.'.css';
		    
			if (file_exists($path))
			{
				$src = base_url().'assets/css/'.$row.'.css';
				self::$css[] = "<link type='text/css' href='$src' rel='stylesheet' />";
			}			
		}
	}

	static function js($script = '', $append = FALSE)
	{		
		$script = self::_string_to_array($script);

		foreach($script as $row)
		{
			$row = trim($row);
			
			$path = RUTA_JS.$row.'.js';
			
			if (file_exists($path))
			{
				$src = base_url().'assets/js/'.$row.'.js';
				if ($append === TRUE)
				{
					self::$temp_js[] = "<script type='text/javascript' src='$src' ></script>";
				}
				else
				{
					self::$js[] = "<script type='text/javascript' src='$src' ></script>";
				}					
			}
		}	
	}

	static private function _string_to_array($items = NULL)
	{
		if (is_string($items))
		{		    
		   if (strpos($items, ',') !== FALSE)
           {
               $items = explode(',', $items);    
           }
           else 
           {
		       $items = array($items);
           }
		}

		return $items;
	}
	
	static function show_js()
	{
		return (isset(self::$js) && ! empty(self::$js)) ? implode("\n\t", self::$js).implode("\n\t", self::$temp_js) : '';
	}
	
	static function show_css()
	{  
		return (isset(self::$css) && ! empty(self::$css)) ? implode("\n\t", self::$css) : '';
	}
}