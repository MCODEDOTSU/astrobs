<?php
	class SetLangFold extends Admin_Controller {

		function SetLangFold () {
			parent::Admin_Controller ();
		}

		function index () {
			$langs		= $this->foldlang_model->getLangs ();
			$drops		= $this->foldlang_model->getDrops ();
			$objects	= $this->foldlang_model->getformdrop ($langs, $drops);
			$data = array (
				'fo'	=>	form_open (site_url (array ('admin', 'setlangfold', 'setlangfold', 'savelang'))),
				'langs'	=>	$objects,
				'fc'	=>	form_close (),
				'fs'	=>	form_submit ('submit', 'Сохранить')
			);
			$this->module->parse ('setlangfold', 'index.php', $data);
		}

		function saveLang () {
			if (!$this->input->post ('submit')) $this->_redir ();
			$this->foldlang_model->setlangs ($this->input->post ('drop'), $this->input->post ('radio'));
			$this->session->unset_userdata ('fid');
			$this->_redir ();
		}

		function _redir () {
			return redirect (site_url (array ('admin', 'setlangfold', 'setlangfold')));
		}
		
	}
?>
