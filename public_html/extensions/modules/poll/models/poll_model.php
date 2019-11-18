<?php
class Poll_model extends Model
{
    function Poll_model()
    {
        parent::Model();
        $this->TABLE = 'th_poll';
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
    function block()
    {
        return '
            <div id="poll"><div id="question" class="block"></div></div>
        ';
    }
}
?>
