<?php
	class version_site_model extends Model {
	
		function version_site_model () {
			parent::Model ();
			//$this->lang->setModule ('printer');
			//$this->lang->module_load ('public', 'printer', 'printer', 'english');
		}


		function get_version () {
		
			//print_r ($this->lang);
			//print_R($this->lang->mline ('print_text'));
			$ver = $this->session->userdata('version_site');
			$result = 'Версия для слабовидящих';
			switch ($ver) {
				case 'ver1':
					$result = 'Версия для слабовидящих';
					break;
				case 'ver2':
					$result = 'Обычная версия сайта';
					break;
			}
			return '<div id="visually"><a href="/version_site/version">'.$result.'</a></div>';
		}
	}
?>
