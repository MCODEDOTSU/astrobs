<?php
	class Appointments extends Public_Controller {

		function Appointments() {
		    parent::Public_Controller();
		}
		
		function index() {
		    $data = array(
				'form_open' => form_open('appointments/appointments/save'),
				'form_close' => form_close(),
				'submit' => form_submit('frmSubmit','Сохранить'),
				'author' => form_input('author', ''),
				'email' => form_input('email', ''),
				'phone' => form_input('phone', ''),
				'time' => form_input('time', '', 'id="time"'),
				'description' => form_textarea('description', ''),
		    );
        
        	$this->module->parse('appointments', 'appointments.php', $data);
		}

		function save() {
		    
		    $author = $this->input->post('author');
		    if(strlen($author) == 0) return false;		    

		    $email = $this->input->post('email');

			$phone = $this->input->post('phone');
		    if(strlen($phone) == 0) return false;

   		    $time = $this->input->post('time');
		    if(strlen($time) == 0) return false;

		    $description = $this->input->post('description');
		    if(strlen($description) == 0) return false;		

		    $this->appointments_model->create(array(
		        'author' => $author,
		        'email' => $email,
		        'phone' => $phone,
		        'time' => $time,
		        'description' => $description
		    ));

		    redirect('appointments/appointments');
		}
		
	}	
?>
