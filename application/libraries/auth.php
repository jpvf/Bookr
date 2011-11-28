<?php

/**
 * Libreria de autenticación de usuarios.
 * 
 * @package JFramework
 * @author Juan Velandia
 * @version 2011
 * @access public
 */
class Auth {

    
	private $errors = array();
    private $_config = array();
    
	/**
	 * Carga la configuración del sistema.
	 * 
	 * @return void
	 */
	function __construct()
	{
		$this->CI = get_instance();
		$this->CI->config->load('auth');
	}

	/**
	 * Realiza el login del usuario
	 * 
	 * @param string $username campo por el cual se hace el login del usuario
	 * @param string $password contrasena del usurio
	 * @return boolean
	 */
	function login($username = '', $password = '', $type = 'default')
	{
		if (empty($username) OR empty($password))
		{
			return FALSE;
		}
		
		$password = $this->_salt_it($password);
		
		$username_field = $this->CI->config->item('username_field');
        
		$results = $this->CI->db->where($username_field, $username)
								->where('password', $password)
					  			->get('users');

		if ($results->num_rows() == 0)
		{
			return FALSE;
		}				

		$user = $results->row();
		
		$session_id = $this->CI->db->update('users', array(
			'session_id' => $this->CI->session->userdata('session_id')
		), "id = {$user->id}");

		$user->ip_address = $this->CI->input->ip_address();

		$this->CI->session->set_userdata($type, (array) $user);

		return TRUE;		
	}


	private function _salt_it($password = '')
	{
		if ($password == '')
		{
        	return FALSE;
	    }

	    $salt = $this->CI->config->item('salt');
	    $salt = md5(sha1($salt));
	    $password  = sha1(md5($password));
	    $password  = md5(sha1(md5($salt . $password . $salt)));
	    return $password; 
	}

	/**
	 * Auth::is_logged_in()
	 * 
	 * @return
	 */
	function is_logged_in($type = 'default')
	{
		$userdata = $this->CI->session->userdata('admin');

		if ( ! empty($userdata))
    	{
    		$data = $this->CI->db->select('users.session_id')
    							 ->from('users')
    							 ->where('users.id', $userdata['id'])
    							 ->get();
  			
  			if ($data->num_rows() === 0)
  			{
  				return FALSE;
  			}

  			$data = $data->row();
    		
	    	if ($data->session_id !== $this->CI->session->userdata('session_id'))
	    	{
	    	   	$this->CI->session->sess_destroy();
    		}

	    	if ($this->CI->session->userdata('ip_address') !== $this->CI->input->ip_address())
	    	{
	    	 	$this->CI->session->sess_destroy();
	    	}	    	

	    	return TRUE;
    	}
    
    	return FALSE;
	}

	/**
	 * Auth::logout()
	 * 
	 * @return
	 */
	function logout($type = 'default')
	{
		$this->CI->session->unset_userdata($type);
 		$this->CI->session->sess_destroy();
	}

	
}