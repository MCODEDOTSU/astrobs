<?php
	class printer_model extends Model {
	
		function printer_model () {
			parent::Model ();
			$this->lang->setModule ('printer');
			//$this->lang->module_load ('public', 'printer', 'printer', 'english');
		}


		function printer () {
			//print_r ($this->lang);
			//print_R($this->lang->mline ('print_text'));
			return '<a href="#" onclick="print(); return false;" id="print"><img src="' . site_url (array ('cms_icons', 'printer.png')) . '" align="absmiddle" /> ' . $this->lang->mline ('print_text') . '</a>';
		}
	}
?>
