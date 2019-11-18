<?php
	class Appointments extends Admin_Controller {

		function Appointments() {
		    parent::Admin_Controller();
		    parent::access('appointments');
		}

		function index() {
		    $this->display->_content('<div class="cms_title">'._icon('table').'Записи на прием</div>');

		    $Groups = $this->appointments_model->get();

		    foreach ($Groups as $group) {

				if (strtotime($group['time']) < time()) {
					$state = '#FFD3DC';
				} else {
					$state = '#FFFFD3';
				}
	    
		        $data['appointments'][] = array(
		        	'state'		=> $state,
		            'id'        => $group['id'],
		            'author'	=> $group['author'],
		            'email'		=> $group['email'],
		            'phone'		=> $group['phone'],
		            'time'		=> date('d.m.Y H:i', strtotime($group['time'])),
		            'description'	=> $group['description'],
		            'actions'       => anchor('admin/appointments/appointments/delete/'.$group['id'], _icon('book_delete'), 'title="Удалить"')
		        );
		    }

		    $this->module->parse('appointments', 'index.php', $data);
		}

		function delete() {
		    $group_id = $this->uri->segment(5);
		    
		    if(!is_numeric($group_id)) return false;

		    $this->appointments_model->delete(array('id' => $group_id));

		    redirect('admin/appointments/appointments');
		}
	}
?>
