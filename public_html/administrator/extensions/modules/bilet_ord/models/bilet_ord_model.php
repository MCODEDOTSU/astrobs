<?php
	class Bilet_ord_model extends Model {

		var $_table;
	
		function Bilet_ord_model () {
			parent::Model ();
			$this->_table = 'th_bilet_email';
		}

		function getEmail ($where = array ()) {
			foreach ($where AS $k => $v) {
				$this->db->where ($k, $v);
			}
			$email = $this->db->get ($this->_table)->result_array ();
			if (count ($email) == 0) return false;
			return $email[0]['email'];
		}

		function updateEmail ($where = array (), $update = array ()) {
			foreach ($where AS $k => $v) {
				$this->db->where ($k, $v);
			}

			$this->db->update ($this->_table, $update);
		}
	}
?>
