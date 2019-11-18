<?php
	class panel extends Public_Controller {

		function panel () {
			parent::Public_Controller ();
			$this->lang->setModule ('fontpanel');
		}
	
		function color () {
			$color = $this->input->post('color');
			$result = 'color1';
			switch ($color) {
				case 'site_color1':
					$result = 'color1';
					break;
				case 'site_color2':
					$result = 'color2';
					break;
				case 'site_color3':
					$result = 'color3';
					break;
			}
			$this->session->set_userdata(array('site_color' => $result));
		}
		
		function font () {
			$font = substr($this->input->post('font'), 0, 10);
			$result = '100%';
			switch ($font) {
				case 'site_font1':
					$result = '100%';
					break;
				case 'site_font2':
					$result = '120%';
					break;
				case 'site_font3':
					$result = '140%';
					break;
			}
			$this->session->set_userdata(array('site_font' => $result));
		}
	}
?>
