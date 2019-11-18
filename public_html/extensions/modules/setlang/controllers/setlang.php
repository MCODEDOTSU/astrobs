<?php
	class SetLang extends Public_Controller {
		function SetLang () {
			parent::Public_Controller ();
		}

		function _remap () {
			$this->index ();
		}

		function index () {
			$lang = $this->uri->segment (3);
			$q = $this->db->select ('language')->get ('th_language_structure')->result_array ();
			$languages = array ();
			foreach ($q AS $v) {
				$languages[] = $v['language'];
			}
			//$languages = array ('russian', 'english');
			if (in_array ($lang, $languages)) $this->session->set_userdata ('language', $lang);
			//redirect ($this->_page ());
			redirect (base_url ());
		}

		function _page () {
			return ($this->input->server ('HTTP_REFERER')) ? $this->input->server ('HTTP_REFERER') : base_url ();
		}
	}
?>
