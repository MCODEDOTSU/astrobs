<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tours_model extends Model
{
    function Tours_model()
    {
        parent::Model();
        
        $this->TABLE = 'th_tours';
        $this->language = 'th_language_structure';
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

    function getLang () {
		$lang = $this->db->select ('text, id')->get ($this->language)->result_array ();
		$arr = array ();
		foreach ($lang AS $v) {
			$arr[$v['id']] = $v['text'];
		}
		return $arr;
    }
    

}

?>
