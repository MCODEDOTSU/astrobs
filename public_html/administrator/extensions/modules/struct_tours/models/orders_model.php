<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Orders_model extends Model {

	function Orders_model() {
		parent::Model();
		$this->TABLE = 'th_tours_ords';
		$this->TABLE_2 = 'th_tours_cats';
		$this->TABLE_3 = 'th_tours';
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

    function getOrds ($id) {
		$q = $this->db->select ('id')->where ('tid', $id)->get ($this->TABLE_2)->result_array ();
		$start = true;
		foreach ($q AS $v) {
			if ($start) {
				$this->db->where ('tid', $v['id']);
				$start = false;
			} else {
				$this->db->or_where ('tid', $v['id']);
			}
		}
		return $this->db->get ($this->TABLE)->result_array ();
    }

    function getName ($id) {
		$q = $this->db->select ('name')->where ('id', $id)->limit (1)->get ($this->TABLE_3)->result_array ();
		return $q[0]['name'];
    }

    function getCaTname ($id) {
		$q = $this->db->select ('tid')->where ('id', $id)->get ($this->TABLE)->result_array ();
		$q2 = $this->db->select ('name')->where ('id', $q[0]['tid'])->get ($this->TABLE_2)->result_array ();
		return $q2[0]['name'];
    }

    function getLink ($id) {
    	$q = $this->db->select ('tid')->where ('id', $id)->limit (1)->get ($this->TABLE)->result_array ();
    	$q2 = $this->db->select ('tid')->where ('id', $q[0]['tid'])->limit (1)->get ($this->TABLE_2)->result_array ();
    	return site_url (array ('admin', 'struct_tours', 'orders', 'table', $q2[0]['tid']));
    }

    
    
    function extra($where = array())
    {
        if(count($where) == 0) return false;
        
        return $this->db->where($where)->get($this->TABLE)->result_array();        
    }
}
?>
