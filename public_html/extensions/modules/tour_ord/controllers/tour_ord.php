<?php
	class Tour_ord extends Public_Controller {
		function Tour_ord () {
			parent::Public_Controller ();
			$this->lang->setModule ('tour_ord');
		}

		function index () {
			
			$data = array (
				'breadCrumbs'	=>	$this->breadcrumbs->getHome(array ($this->lang->mline ('tour_ord_text') => site_url (array ('tour_ord', 'tour_ord')))),
				'tour_ord'		=>	$this->tour_ord_model->_getOrdFrm ()
			);

			$this->module->parse ('tour_ord', 'index.php', $data);
		}

		function setorder () {
			if ($this->input->post ('fbut') == false) {
				if ($this->input->server ('HTTP_REFERER') !== false) {
					redirect ($this->input->server ('HTTP_REFERER'));
				} else {
					redirect (base_url ());
				}
			}

			$tid = (int) $this->input->post ('ftid');

			$data = array (
				'name'		=>	$this->input->post ('fname'),
				'email'		=>	$this->input->post ('femail'),
				'phone'		=>	$this->input->post ('fphone'),
				'nums'		=>	(int) $this->input->post ('fnums'),
				'sday'		=>	(int) $this->input->post ('fdays'),
				'smoon'		=>	(int) $this->input->post ('fmoons'),
				'eday'		=>	(int) $this->input->post ('fdaye'),
				'emoon'		=>	(int) $this->input->post ('fmoone'),
				'needs'		=>	$this->input->post ('fneeds'),
				'more'		=>	$this->input->post ('fmore'),
				'tid'		=>	$tid,
				'file_id'	=>	(int) $this->input->post ('file_id'),
				'set_tours'	=>	(int) $this->input->post ('set_tours')
			);

			$email = $this->tour_ord_model->getOrdTour ($data['set_tours']);
			$tempQ = $this->tour_ord_model->_getArrs ('tours');
			$tName = $tempQ[$data['set_tours']];

			$moons = $this->tour_ord_model->_getArrs ('moons');
			$nums = $this->tour_ord_model->_getArrs ('nums');

			$this->tour_ord_model->mailTo ($data['name'], $data['email'], $data['phone'], $tName, $nums[$data['nums']], $data['sday'], $moons[$data['smoon']], $data['eday'], $moons[$data['emoon']], $data['needs'], $data['more'], $email);
			$data = array (
				'link'		=>	anchor (base_url (), $this->lang->mline ('tour_ord_to_home')),
				'order_run'	=>	$this->lang->mline ('tour_ord_run'),
				'run_text'	=>	$this->lang->mline ('tour_ord_run_text')
				
			);
			$this->module->parse('tour_ord', 'ordresult.php', $data);
		}

		function getTextErrors () {
			//if (!$this->input->post ('request')) redirect (base_url ());

			$obj->error_name = $this->lang->mline ('tour_ord_error_name');
			$obj->error_name_2 = $this->lang->mline ('tour_ord_error_name_2');
			$obj->error_email = $this->lang->mline ('tour_ord_error_email');
			$obj->error_phone = $this->lang->mline ('tour_ord_error_phone');
			$obj->error_tour = $this->lang->mline ('tour_ord_error_tour');

			echo (json_encode ($obj));

			die;
		}
	}
?>
