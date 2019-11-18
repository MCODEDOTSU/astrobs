<?php
class Users_model extends Model
{
    var $_TABLES;
    
    function Users_model()
    {
        parent::Model();
        
        $this->_TABLES = $this->module->config('users', 'TABLES');
        
    }
    
    function users()
    {
        return $this->db->get($this->_TABLES['Auser'])->result();
    }
    
    function role()
    {
        return $this->db->get($this->_TABLES['Aurole'])->result();    
    }
    
    function group()
    {
        return $this->db->get($this->_TABLES['Augroup'])->result();    
    }
    
    function _arrayReverse($mas)
    {
        foreach($mas as $m)
        {
            $results[$m->id] = $m;
        }
        
        return $results;
    }
    
    function _arrayDropdown($mas)
    {
        foreach($mas as $k=>$v)
        {
            $results[$k] = $v->name;
        }
        
        return $results;
    }
    
    function delete($id)
    {
        if(!$id) return FALSE;
        
        return $this->db->delete($this->_TABLES['Auser'], array('id'=> $id));
    }
    
    function update($id, $mas)
    {
        return $this->db->update($this->_TABLES['Auser'], $mas, array('id'=>$id));
    }
    
    function insert($mas)
    {
        return $this->db->insert($this->_TABLES['Auser'], $mas);
    }
    
}
?>
