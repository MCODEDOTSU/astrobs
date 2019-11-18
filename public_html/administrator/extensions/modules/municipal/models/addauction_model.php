<?php
class AddAuction_model extends Model
{
    var $TABLES; 
    
    function AddAuction_model()
    {
        parent::Model();
        $this->TABLES = $this->module->config('municipal', 'TABLES');
    }
    
    function create($extrafields = array())
    {
        if(count($extrafields) == 0) return false;

        $extrafields['date'] = time();
        
        $this->db->insert($this->TABLES['Auctions'], $extrafields);
        
        return $this->db->insert_id();
    }

    function delete($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->delete($this->TABLES['Auctions'], $where);
    }

    function update($extrafields = array(), $where = array())
    {
        if(count($extrafields) == 0) return false;
        if(count($where) == 0) return false;

        return $this->db->update($this->TABLES['Auctions'], $extrafields, $where);
    }

    function get()
    {
        return $this->db->get($this->TABLES['Auctions'])->result_array();
    }

    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->TABLES['Auctions'])->result_array();
    }
    
      
    
    
}
?>
