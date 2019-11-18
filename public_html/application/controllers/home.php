<?php

class Home extends Public_Controller {

	function Home()
	{
		parent::Public_Controller();
	}
	
	function index()
	{
	// tpl_block_main

		$tpl_block_main = $this->db->where('side', $this->_getHM ())->get('th_tpl_block')->result_array();
		$this->display->_content('<div class="content_desc">');
		$this->display->_content ($this->breadcrumbs->getHome ());
		//$this->display->_content ($this->central_menu_model->get ());
		$this->display->_content('<h1>');
		$this->display->_content($tpl_block_main[0]['title']);
		$this->display->_content('</h1>');
		
		$this->display->_content($tpl_block_main[0]['body']);
		$this->display->_content('</div>');

	// last news

	}

	function _getHM () {
		$lang =  ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
		switch ($lang) {
			case 'russian':
				$result = 'main';
				break;
			case 'english':
				$result = 'main_eng';
				break;
			default:
				$result = false;
				break;
		}
		return $result;
    }

    function _getDefaultLang () {
		$q = $this->db->select ('language')->where ('default', 1)->limit (1)->get ('th_language_structure')->result_array ();
		return $q[0]['language'];
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
