<?php
class AddEAuction_model extends Model
{
    var $TABLES;
    
    function AddEAuction_model()
    {
        parent::Model();
        $this->TABLES = $this->module->config('municipal', 'TABLES');      
    }
    
    function create($extrafields = array())
    {
        if(count($extrafields) == 0) return false;

        $extrafields['date'] = time(); 
        
        $this->db->insert($this->TABLES['EAuctions'], $extrafields);
        
        return $this->db->insert_id();
    }

    function delete($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->delete($this->TABLES['EAuctions'], $where);
    }

    function update($extrafields = array(), $where = array())
    {
        if(count($extrafields) == 0) return false;
        if(count($where) == 0) return false;

        return $this->db->update($this->TABLES['EAuctions'], $extrafields, $where);
    }

    function get()
    {
        return $this->db->get($this->TABLES['EAuctions'])->result_array();
    }

    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->TABLES['EAuctions'])->result_array();
    }
}
?>
