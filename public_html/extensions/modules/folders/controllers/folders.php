<?php

	class folders extends Public_Controller {
	
		function folders () {
			parent::Public_Controller ();
		}

		function get () {
			$id = (int) $this->uri->segment (4);
			
			if ($id == 0) {
				if ($this->input->server ('HTTP_REFERER') != false) {
					redirect ($this->input->server ('HTTP_REFERER'));
				} else {
					redirect (base_url ());
				}
			}
			$q = $this->db->where ('id', $id)->limit (1)->get ('th_folder')->result_array ();

			$biletOrd = '';
			$sys_name = '';
			
			if ($q[0]['type_id'] > 0) {
				$temp_q = $this->db->select ('sys_name')->where ('id', $q[0]['type_id'])->limit (1)->get ('th_folder_type')->result_array ();
				if (count ($temp_q) > 0) {
					$sys_name = $temp_q[0]['sys_name'];
				}
			}

			switch ($sys_name) {
				case 'bilet_ord':
					$biletOrd = $this->tour_ord_model->_getOrdFrm ();
					break;
			}
			
			$links = $this->folders_model->getLinks ($q[0]['sort']);

			$link = array ();
			foreach ($links AS $v) {
				$link[]['link'] = $v;
			}
			$data = array (
				'links' 		=> $link,
				'title' 		=> $q[0]['title'],
				'breadCrumbs'	=> $this->breadcrumbs->foldget ($id),
				//'catTours'		=> $this->central_menu_model->get (),
				'biletOrd'		=> $biletOrd
			);
			$this->module->parse('folders', 'index.php', $data);
			
		}
	}

?>
