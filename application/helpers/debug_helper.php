<?php

if ( ! function_exists('debug'))
{
	function debug($array = array(), $exit = FALSE)
	{
		echo '<pre>';
		print_r($array);
		echo '</pre>';

		if ($exit)
		{
			exit;
		}
	}
}

function active_to_string($string = '')
{
	return ($string == 1) ? 'Yes' : 'No';
}