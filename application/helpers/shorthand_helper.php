<?php

function _post($item = '', $default_value = FALSE)
{
	$CI = get_instance();
	$value = $CI->input->post($item, TRUE);
    return $value ? $value : $default_value;
}

function _get($item = '', $default_value = FALSE)
{
	$CI = get_instance();
	$value = $CI->input->get($item);
    return $value ? $value : $default_value;
}

function _uid($table = NULL)
{
    $number  = _random_id();
    
    $CI      = get_instance();
    
    $results = $CI->db->where("uid = $number")->get($table);

    if ($results->num_rows() > 0)
    {
        return _uid($table);
    }

    return $number;
}

function _random_id()
{
    $number = '';
    for ($i=0; $i<8; $i++) 
    { 
        $number .= rand(1,9); 
        
    }
    return $number;
}

function _json($string = '')
{
    $CI = get_instance();
    return $CI->template->json($string);
}