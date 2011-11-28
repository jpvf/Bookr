<?php 

class Acl{

    private static $acl;
    
    public static function getInstance()
    {
    	if ( ! self::$acl) 
        { 
            self::$acl = new acl(); 
        } 
        return self::$acl; 
    }
    
    function __construct()
    {
        $this->CI = get_instance();

        if ($this->CI->session->userdata('admin'))
        {
            foreach ($this->CI->session->userdata('admin') as $key => $val)
            {
                $this->$key = $val;
            }
        } 
    }

    function is_allowed($resource = NULL, $id_user = NULL,$id = 0)
    {
        if (is_null($resource))
        {
            return FALSE;
        }
        
        if (is_null($id_user))
        {
            $id_user = $this->id;
        }

        $resource = $this->find_resource($resource);
        $results  = $this->find_permission($resource, $id_user, $id);

        if ($results->num_rows() > 0)
        {
            $permission = $results->row();

            return $permission->value == 1 ? TRUE : FALSE;
        }                            

        return FALSE;
    }

    function allow($resource = NULL, $id_user = NULL, $id_project = 0)
    {
        $this->save_permission($resource, $id_user, $id_project, 1);
        return $this;
    }

    function deny($resource = NULL, $id_user = NULL, $id_project = 0)
    {
        $this->save_permission($resource, $id_user, $id_project, 0);
        return $this;
    }

    function save_permission($resource = NULL, $id_user = NULL, $id_project = 0, $value)
    {
        if (is_null($resource) OR is_null($id_user))
        {
            return FALSE;
        }

        if (is_string($resource))
        {
            $resource = $this->find_resource($resource);          
        }

        if ( ! is_numeric($resource) OR $resource < 1)
        {
            return FALSE;
        }

        $permission = $this->find_permission($resource, $id_user, $id_project);

        $save = array();

        if ($permission->num_rows() > 0)
        {
            $save['value'] = $value;

            $where = "id_resource = $resource AND id_project = $id_project AND id_user = $id_user";
            return $this->CI->db->update('users_permissions', $save, $where);
        }

        $save['id_resource'] = $resource;
        $save['id_user']     = $id_user;
        $save['id_project']  = $id_project;
        $save['value']       = $value;

        return $this->CI->db->insert('users_permissions', $save);
    }

    function find_permission($resource, $user, $id)
    {
        return $this->CI->db->where("id_resource = $resource AND id_project = $id AND id_user = $user")
                                ->get('pmt_users_permissions');
    }


    function find_resource($resource)
    {
        return $this->CI->db->where('name', $resource)
                            ->get('users_resources')
                            ->row()
                            ->id;
    }

    function get_name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    function find_parent_resources()
    {
        return $this->CI->db->where('parent_id = 0')
                                ->get('users_resources')
                                ->result();      
    }

    function find_resources($id = NULL)
    {
        if (is_null($id))
        {
          return FALSE;
        }

        return $this->CI->db->where("parent_id = $id")
                                ->get('users_resources')
                                ->result();
    }

  
}