<?php

class Message {
    
    private static function _message($type = '', $text = '', $url = '')
    {
        $CI = get_instance();
        $CI->session->set_flashdata('text', $text);
        $CI->session->set_flashdata('type', $type);
        redirect('/'.$url); 
    }

    static function success($text = 'Success', $url = '')
    {
        return self::_message('success', $text, $url);
    }

    static function error($text = 'Error', $url = '')
    {
        return self::_message('error', $text, $url);
    }

    static function warning($text = 'Warning', $url = '')
    {
        return self::_message('warning', $text, $url);
    }

    static function info($text = 'Info', $url = '')
    {
        return self::_message('info', $text, $url);
    }


    static function get($sticky = FALSE)
    {
        $CI = get_instance();

        $text = $CI->session->flashdata('text');
        $type = $CI->session->flashdata('type');

        if (empty($text) OR empty($type))
        {
            return NULL;
        }

        $close = ($sticky === TRUE) ? '' : "<span class='messages-close'></span>";
        $class = ($sticky === TRUE) ? 'sticky' : ''; 

        return "<div class='row'><div class='span12' style='margin: 0px auto;float: none;display: block;'><div class='alert-message $type $class'><a class='close' href='#'>×</a><p>$text</p></div></div></div>";
    }
}
