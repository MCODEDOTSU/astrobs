<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Objs_model extends Model
{
    function Objs_model()
    {
        parent::Model();
        
        $this->TABLE = 'th_tours_objs';
    }

    function create($extrafields = array())
    {
        if(count($extrafields) == 0) return false;
        
        return $this->db->insert($this->TABLE, $extrafields);    
    }
    
    function delete($where = array())
    {
        if(count($where) == 0) return false;
        
        return $this->db->delete($this->TABLE, $where);
    }
    
    function update($extrafields = array(), $where = array())
    {
        if(count($extrafields) == 0) return false;
        if(count($where) == 0) return false;
        
        return $this->db->update($this->TABLE, $extrafields, $where);    
    }
    
    function get($where = array ())
    {
		foreach ($where AS $k => $v) {
			$this->db->where ($k, $v);
		}
        return $this->db->get($this->TABLE)->result_array();
    }
    
    function extra($where = array())
    {
        if(count($where) == 0) return false;
        
        return $this->db->where($where)->get($this->TABLE)->result_array();        
    }
    

}

?>
