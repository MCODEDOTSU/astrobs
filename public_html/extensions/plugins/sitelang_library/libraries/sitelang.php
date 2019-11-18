<?php
	class sitelang {

		var $CI;
	
		function sitelang () {
			$this->CI = &get_instance ();
		}

		// Определение текущего языка
		function getlang () {
			return ($this->CI->session->userdata ('language')) ? $this->CI->session->userdata ('language') : $this->_getDefaultLang ();
		}

		function getId ($lang) {
			$q = $this->CI->db->select ('fid')->where ('language', $lang)->limit (1)->get ('th_language_structure')->result_array ();
			if (count ($q) > 0) {
				return $q[0]['fid'];
			} else {
				return false;
			}
		}

		// Загрузка языка по умолчанию
		function _getDefaultLang () {
			$q = $this->CI->db->select ('language')->where ('default', 1)->limit (1)->get ('th_language_structure')->result_array ();
			return $q[0]['language'];
		}
	}
?>
