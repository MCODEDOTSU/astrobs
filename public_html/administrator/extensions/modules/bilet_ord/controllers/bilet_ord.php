<?php
	class Bilet_ord extends Admin_controller {
	
		function Bilet_ord () {
			parent::Admin_controller ();
		}

		function index () {
			$email = $this->bilet_ord_model->getEmail (array ('id' => 1));
			
			$email_qas = $this->bilet_ord_model->getEmail (array ('id' => 2));
			
			$this->display->_content('<div class="cms_title">'._icon('chart_curve_add').'Редактировать E-mail для заказа билетов</div>');
			
			$data = array (
				'form_open'		=>	form_open (site_url (array ('admin', 'bilet_ord', 'bilet_ord', 'edit_email'))),
				'email'			=>	form_input ('email', $email),
				'email_qas'		=>	form_input ('email_qas', $email_qas),
				'button'		=>	form_submit ('frmSubmit', 'Изменить'),
				'form_close'	=>	form_close ()
			);
		
			$this->module->parse ('bilet_ord', 'index.php', $data);
		}

		function edit_email () {
			if (!$this->input->post ('email')) redirect (site_url (array ('admin', 'bilet_ord', 'bilet_ord')));
			$where = array ('id' => 1);
			$set = array (
				'email' => $this->input->post ('email')
			);
			$this->bilet_ord_model->updateEmail ($where, $set);

			$where = array ('id' => 2);
			$set = array (
				'email' => $this->input->post ('email_qas')
			);
			$this->bilet_ord_model->updateEmail ($where, $set);
			
			redirect (site_url (array ('admin', 'bilet_ord', 'bilet_ord')));
		}
	}
?>
