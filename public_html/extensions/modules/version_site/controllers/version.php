<?php
	class version extends Public_Controller {

		function version () {
			parent::Public_Controller ();
			//$this->lang->setModule ('printer');
			//$this->lang->mid ('');
		}
	
		function index () {
			$ver = $this->session->userdata('version_site');
			switch ($ver) {
				case 'ver1':
					$this->session->set_userdata(array('version_site' => 'ver2'));
					break;
				default:
				case 'ver2':
					$this->session->set_userdata(array('version_site' => 'ver1'));
					break;
			}
			
			redirect ($this->input->server('HTTP_REFERER'));
		}
	}
?>