<?php
class Qas_model extends Model
{
    function Qas_model()
    {
        parent::Model();
        
        $this->TABLE = 'th_qas';
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
    
    function get()
    {
        return $this->db->get($this->TABLE)->result_array();
    }
    
    function extra($where = array())
    {
        if(count($where) == 0) return false;
        
        return $this->db->where($where)->get($this->TABLE)->result_array();        
    }
}  
?>
