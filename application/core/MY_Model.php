<?php

/**
 * Clase Base de modelo, contiene los metodos básicos de un modelo como
 * encontrar un registro de una tabla, todos los registros de la tabla, 
 * contar, guardar, actualizar, métodos mágicos para los queries.
 * 
 */

class MY_Model extends CI_Model {
    
    /**
     * @var array $join contiene las relaciones básicas de la tabla
	*/
	protected $join   = array();
	
	/**
	* @var string $table contiene la tabla del modelo actual
	*/
	protected $table  = '';
	
	/**
	* @var string $find contiene el identificador del modelo actual
	*/
	protected $id     = 'id';
	
	/**
	* @var string $base_select contiene los campos basicos a seleccionar
	*/
	protected $select = '';
	
	/**
	* @var string $where contiene los campos a filtrar en el where
	*/
	protected $where  = '';

    /**
     * Realiza un query buscando todos los registros con la preparación 
     * básica realizada en el modelo, ej.:
     * 
     * SELECT * FROM table
     *
     * @return  array
     */
    public function find_all($where = array())
    {
        $this->db->like($where);
        $this->_setup();
        return $this->db->get($this->table);
    }
    
    /**
     * Realiza un query buscando todos los registros con la preparación 
     * básica realizada en el modelo y recibiendo un id para filtrar por tal campo, 
     * ej.:
     * 
     * SELECT * FROM table WHERE table.id = '$id'
     *
     * @param   numeric el id a buscar
     * @return  array   el array con el resultado de la clase DB
     */
    public function find($id = NULL)
    {
        //No hay nada, devuelva nada.
        if (is_null($id))
        {
            return $this->find_all();
        }

        $this->_setup();

        $this->db->where($this->table.'.'.$this->id, $id)
                 ->limit(1);

        return $this->db->get($this->table);
    }
    
    /**
     * Realiza un query buscando todos los registros con la preparación 
     * básica realizada en el modelo pero el resultado va a ser un total nada mas, 
     * ej.:
     * 
     * SELECT count(id) as total FROM table
     *
     * @return  string el numero total de filas
     */
    public function count()
    {
        $this->_setup(TRUE);
        
        $result = $this->db->get($this->table);
        
        if ($result->num_rows() == 0)
        {
            $total = 0;
        }
        else 
        {
            $total = $result->row()->total;
        }
        
        return $total; 
    }
    
    /**
     * Realiza un query insertando el array asociativo que llegue, verifica si existe el uid, si
     * existe actualiza el registro, sino lo saca del array para que no cree uno nuevo o genere un error
     *
     * @param   array el array con los datos a guardar
     * @return  bool  true o false dependiendo del resultado del query
     */
    function save($save, $table = '')
    {
        $field = array();
        
        if (isset($save[$this->id]))
        {
            $field = $this->find($save[$this->id]);            
        }
        
        $table = ( ! empty($table) ? $table : $this->table );
        
        if ( empty($field) OR $field->num_rows() == 0)
        {
            return $this->db->insert($table, $save);
        }
        else
        {
            $uid = $save[$this->id];
            
            unset($save[$this->id]);
            
            $field = $field->row();
            
            if ( ! isset($field->id))
            {
                return FALSE;
            }
            
            return $this->db->update($table, $save, "id = {$field->id}");
        }        
    }
    
    /**
     * Prepara las partes del query que llegan desde el modelo tales como las asociaciones, 
     * selects, where.
     *
     * @param   bool si se hace un query de conteo o no
     * @return  void
     */ 
    private function _setup($count = FALSE)
    {
        if ($count === FALSE)
        {
            if (empty($this->select))
            {
                $this->db->select($this->table.'.*');   
            }
            else
            {
                $this->db->select($this->select);
            }         
        }
        else
        {
            $this->db->select("count({$this->table}.{$this->id}) as total");
        }
        
        foreach ($this->join as $table => $relation)
        {        	
            $type = '';

            if (strpos($relation, '|') !== FALSE)
            {
                list($relation, $type) = explode('|', $relation);
            }

        	$this->db->join($table, $relation, $type);
        }

        if ( ! empty($this->where))
        {
        	$this->db->where($this->where);
        }
    }
    
    /**
     * Realiza el query dinámico llamado desde la función mágica.
     *
     * @param   bool si se hace un query de conteo o no
     * @return  void
     */ 
    public function find_by($field = '', $value = NULL, $operator = '=')
    {
        if (is_string($value))
        {
            $value = "'$value'";
        }
        
        $this->_setup();
          
        if (is_array($field))
        {
        	$where = $field;
        }
        else
        {
        	$where = "$field $operator $value";
        }

        $this->db->where($where);
        return $this->db->get($this->table);         
    }
    
}