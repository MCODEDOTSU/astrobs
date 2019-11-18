<?php
/**
*
*	Класс для ведения рассылки
*
*	@author Белоусов Иван Алексеевич <belousovivanalekseevichwork@gmail.com>
*	@since 05/12/2012
*
*/
class Register_form extends Public_Controller {

	// Таблица со списком подписчиков
	const TABLE = 'th_subscribe';

	// Сообщения активации	
	const ACTIVATE_TITLE = 'Подписка на сайте "Астраханская библиотека-центр социокультурной реабилитации инвалидов по зрению"';
	const ACTIVATE_SUCCESS = 'Вы были успешно подписаны на рассылку';
	const ACTIVATE_ERROR = 'Вы уже подписаны';
	
	// Сообщения деактивации	
	const DEACTIVATE_TITLE = 'Отписка на сайте "Астраханская библиотека-центр социокультурной реабилитации инвалидов по зрению"';
	const DEACTIVATE_SUCCESS = 'Вам пришло письмо на почту о деактивации. Пройдите по нему, чтобы подтвердить что это ваше.';
	const DEACTIVATE_ERROR = 'Такого пользователя нет, проверьте вами введенный адрес электронной почты';

	// Почта администратора
	const ADMIN_EMAIL = 'astrakhanobs@yandex.ru';

	/**
	*	Контроллер
	*/
	function Register_form()
	{
		parent::Public_Controller();
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('email');
	}

	/**
	*	Главная страница
	*/
	function index()
	{		
		$form_message = '';
		
		// Установка правил
		$this->form_validation->set_rules('email','Электронная почта','required|valid_email');
		
		if( $this->input->post('submit') == 'activate')
		{
			$this->form_validation->set_rules('fullname','ФИО','required|min_length[3]|max_length[255]');
			$this->form_validation->set_rules('birth','Дата рождения','required|min_length[3]|max_length[255]');
			$this->form_validation->set_rules('sex','Пол','required|min_length[4]|max_length[6]');
			$this->form_validation->set_rules('email_conf','Подтверждение электронной почты','required|matches[email_conf]|valid_email');
		}

		// Валидация формы
		if( $this->form_validation->run() == FALSE ) {
			$this->getForm( validation_errors() );
			
		} else {
			switch( $this->input->post('submit') )
			{
				case 'activate': $form_message = $this->activate() ? self::ACTIVATE_SUCCESS : self::ACTIVATE_ERROR; break;
				case 'deactivate': $form_message = $this->deactivate() ? self::DEACTIVATE_SUCCESS : self::DEACTIVATE_ERROR; break;
				default: break;
			}
			$this->getForm( $form_message );
		}

	}	// конец index

	/**
	*	Форма
	*/
	private function getForm( $error = '' )
	{		
		// Опции
		$options['male']	= 'мужской';
		$options['female']	= 'женский';

		// Форма активации подписки
		$form = form_open('/register_form/register_form/index', 'class="form-horizontal"');
		$form .= form_fieldset('Активация рассылки');
		$form .= form_label('Ф.И.О.', 'fullname').'<br/>';
		$form .= form_input('fullname', '', 'class="form-control"').'<br/>';
		$form .= form_label('Дата рождения', 'birthdate').'<br/>';
		$form .= form_input('birth','', 'class="form-control"').'<br/>';		
		$form .= form_label('Пол', 'sex').'<br/>';
		$form .= form_dropdown('sex', $options, 'male', 'class="form-control"').'<br/>';		
		$form .= form_label('Электронная почта', 'email').'<br/>';
		$form .= form_input('email', '', 'class="form-control"').'<br/>';		
		$form .= form_label('Подтверждение электронной почты', 'email_conf').'<br/>';
		$form .= form_input('email_conf', '', 'class="form-control"').'<br/>';
		$form .= '<button type="submit" name="submit" value="activate" class="btn btn-primary pull-right">Активировать</button>';
		$form .= '<div class="clearfix"></div>';
		$form .= form_fieldset_close();
		$form .= form_close();
		
		// Форма деактивации подписки
		$form .= form_open('/register_form/register_form/index');
		$form .= form_fieldset('Деактивация рассылки');
		$form .= form_label('Электронная почта', 'email').'<br/>';
		$form .= form_input('email', '', 'class="form-control"').'<br/>';
		$form .= '<button type="submit" name="submit" value="deactivate" class="btn btn-danger pull-right">Деактивировать</button>';
		$form .= '<div class="clearfix"></div>';
		$form .= form_fieldset_close();
		$form .= form_close();

		// Форма
		$data['form'] = $form;
		// Ошибки
		$data['errs'] = $error;
		
		// Парсинг модуля
		$this->module->parse('register_form', 'index.php', $data);
	}	// конец getForm
	
	/**
	*	Отправка email
	*/
	private function email( $email, $title, $body )
	{
		$this->email->from('astrobs@astrobs.ru', 'Администрация сайта для слепых');
		$this->email->to( $email ); 
		$this->email->subject( $title );
		$this->email->message( $body );
		$this->email->send();
	}

	/**
	*	Активация подписки
	*/	
	private function activate()
	{
		// возвращает все данные POST (с XSS фильтрацией)
		$data['fullname'] = $this->input->post('fullname', TRUE);
		$data['email'] = $this->input->post('email', TRUE);
		$data['birth'] = $this->input->post('birth', TRUE);
		$data['sex'] = $this->input->post('sex', TRUE);
		$data['activation'] = sha1("{$data['email']}{$data['birth']}{$data['sex']}");
		
		// Есть ли уже в базе такая запись
		$is_exists = $this->db->where( 'email', $data['email'] )->get(self::TABLE)->num_rows();
	
		if( $is_exists == 0 )
		{
			// Сунул-вынул из базы
			$this->db->insert( self::TABLE, $data );	
			
			// Взятиё из базы данных
			$user = $this->db->where('email',$data['email'])->get(self::TABLE)->row_array();
		
			// Текст письма и заголовок
			$letterBody = $this->module->parse('register_form', 'letterA.php', $user, TRUE );
			$letterTitle = self::ACTIVATE_TITLE;
						
			// Отправка на email пользователя
			$this->email( $data['email'], $letterTitle, $letterBody );

			// Отправка на email администратора
			$adminLetterTitl = 'Подписка на рассылку';
			$adminLetterBody = "Пользователь {$user['fullname']} с адресом {$user['email']} подписался на вашу рассылку.";

			$this->email( self::ADMIN_EMAIL, $adminLetterTitl, $adminLetterBody );

			return true;
		} 
		
		return false;
		
	} // конец activate


	/**
	*	Деактивация подписки
	*/
	public function deactivate()
	{
		$email = $this->input->post('email', TRUE);
		
		// Берём данные пользователя
		$user = $this->db->where( 'email', $email )->get(self::TABLE);

		// Если пользователь есть
		if( $user->num_rows() == 1 )
		{
			$letterTitle = self::DEACTIVATE_TITLE;
			$user = $user->row_array();
			$letterBody = $this->module->parse('register_form', 'letterB.php', $user , TRUE );
			$this->email( $email, $letterTitle, $letterBody );
			
			// Отправка на email администратора
			$adminLetterTitl = 'Отписка от рассылки';
			$adminLetterBody = "Пользователь {$user['fullname']} с адресом {$user['email']} отказался от вашей рассылки.";

			$this->email( self::ADMIN_EMAIL, $adminLetterTitl, $adminLetterBody );

			return true;
		} 
		
		return false;
		
	}
	
	/**
	*	Подтверждение деактивации
	*/
	public function acceptDeactivate(){
		$id = $this->uri->segment(4);
		$activate = $this->uri->segment(5);
		$error = '';
		$is_exists = $this->db->where('id', $id)
					->where('activation', $activate)
					->delete( self::TABLE );
		if( $is_exists ) {
			$this->getForm('Вы успешно отписаны');
		} else {
			$this->getForm('При удалении произошла какая-то ошибка - вы можете попробовать выслать повторную форму');
		}
	}

}	// конец Register_form
?>