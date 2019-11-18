<?php
	class Search_Tour extends Public_Controller {
		function Search_Tour () {
			parent::Public_Controller ();
			$this->lang->setModule ('search_tour');
		}

		function index () {
			if (!$this->input->post ('find')) {
				$this->_noresults ();
			} else {
				$this->_viewresults ();
			}
		}

		function _noresults () {
			$link = site_url (array ('search_tour', 'search_tour'));
			
			$data = array (
				'breadCrumbs'				=>	$this->breadcrumbs->getHome (array ($this->lang->mline ('search_tour_text') => $link)),
				'form_tour_type'			=>	form_input ('fttype', '', 'id="fttype"'),
				'form_tour_sub_type'		=>	form_input ('ftstype', '', 'id="ftstype"'),
				'form_tour_name'			=>	form_input ('ftname', '', 'id="ftname"'),
				'check_tour_type'			=>	form_checkbox ('cttype', 1, true, 'id="cttype"'),
				'check_tour_sub_type'		=>	form_checkbox ('ctstype', 1, false, 'id="ctstype"'),
				'check_tour_name'			=>	form_checkbox ('ctname', 1, false, 'id="ctname"'),
				'form_open'					=>	form_open (site_url (array ('search_tour', 'search_tour')), array ('id' => 'fo')),
				'form_close'				=>	form_close (),
				'form_submit'				=>	form_submit ('find', $this->lang->mline ('search_tour_find_text')),
				'search_tour_text'			=>	$this->lang->mline ('search_tour_text'),
				'text_type_tour'			=>	$this->lang->mline ('search_tour_type_tour'),
				'text_sub_type_tour'		=>	$this->lang->mline ('search_tour_sub_type_tour'),
				'text_name_tour'			=>	$this->lang->mline ('search_tour_name_tour'),
				'text_search_type_tour'		=>	$this->lang->mline ('search_tour_find_type_tour'),
				'text_search_sub_type_tour'	=>	$this->lang->mline ('search_tour_find_sub_type_tour'),
				'text_search_name_tour'		=>	$this->lang->mline ('search_tour_find_name_tour')
			);

			$this->module->parse ('search_tour', 'index.php', $data);
		}

		function _viewresults () {
			if (!$this->input->post ('find')) redirect (base_url ());
			$link = site_url (array ('search_tour', 'search_tour'));
			$lang = $this->sitelang->getlang ();
			
			$toursDir = 0;
			
			switch ($lang) {
				case 'russian':
					$toursDir = 257;
					break;
				case 'english':
					$toursDir = 259;
					break;
			}

			$tTypeIds = $this->search_tour_model->getTypeTours ($toursDir);
			$tSTypeIds = $this->search_tour_model->getSubTypeTours ($tTypeIds);
			$tNameIds = $this->search_tour_model->getNameTours ($tSTypeIds);

			$tours->name = false;
			$tours->type = false;
			$tours->subtype = false;
			$tours->nameIds = $tNameIds;
			$tours->typeIds = $tTypeIds;
			$tours->subtypeIds = $tSTypeIds;
			
			if ($this->input->post ('cttype') !== false) $tours->type = $this->input->post ('fttype');
			if ($this->input->post ('ctstype') !== false) $tours->subtype = $this->input->post ('ftstype');
			if ($this->input->post ('ctname') !== false) $tours->name = $this->input->post ('ftname');

			$results = $this->search_tour_model->getResults ($tours);
			
			
			$data = array (
				'breadCrumbs'	=>	$this->breadcrumbs->getHOME (array ($this->lang->mline ('search_tour_text') => $link)),
				'results'		=>	$this->search_tour_model->getFilterResults ($results)
			);
			$this->module->parse ('search_tour', 'results.php', $data);

		}
	}
?>
