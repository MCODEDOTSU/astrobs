<?php
	class font_panel_model extends Model {
	
		function font_panel_model () {
			parent::Model ();
		}

		function get_panel () {
		
			$font = $this->session->userdata('site_font');
			$data = array();
			//print_r($font);
			switch ($font) {
				case '120%':
					$data['class1'] = '';
					$data['class2'] = 'active_f';
					$data['class3'] = '';
					break;
				case '140%':
					$data['class1'] = '';
					$data['class2'] = '';
					$data['class3'] = 'active_f';
					break;
				case '100%':
				default:
					$data['class1'] = 'active_f';
					$data['class2'] = '';
					$data['class3'] = '';
					break;
			}
			
			$ver = $this->session->userdata('version_site');
			$result = '';
			if ($ver == 'ver2') $result = $this->module->parse('font_panel', 'panel', $data, true);
			return $result;
		}
	}
?>
