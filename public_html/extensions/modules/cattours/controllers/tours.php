<?php
class Tours extends Public_Controller
{
    function Tours()
    {
        parent::Public_Controller();
        //print_r ($this->lang);
        $this->lang->setModule ('cattours');
        
    }
    function index () {
		$q = $this->db->get ('th_tours')->result_array ();
		$data = array ();
		foreach ($q AS $v) {
			$data['tours'][]['name'] = anchor (site_url (array ('cattours', 'tours', 'categories', $v['id'])), $v['name']);
		}
		echo $this->lang->mline ('kkkk');

		$this->module->parse('cattours', 'index.php', $data);
	}

    function categories () {
		$id = $this->uri->segment (4);
		if ((int) $id == 0) redirect (site_url (array ('cattours', 'tours')));
		$q = $this->db->where ('tid', $id)->get ('th_article')->result_array ();
		$q2 = $this->db->select ('name')->where ('id', $id)->limit (1)->get ('th_tours')->result_array ();
		$links = array ();
		//print_r ($q);
		foreach ($q AS $v) {
			$links[]['link'] = anchor (site_url (array ('article', 'article', 'view', $v['file_id'])), $v['title']);
		}
		$data = array (
			'name' => $q2[0]['name'],
			'objs' => $links
		);
		/*$q = $this->db->where ('tid', $id)->get ('th_tours_cats')->result_array ();
		$q2 = $this->db->where ('id', $id)->get ('th_tours')->result_array ();

		$objs = array ();
		foreach ($q AS $v) {
			$objs[]['link'] = anchor (site_url (array ('cattours', 'tours', 'objects', $v['id'])), $v['name']);
		}
		
		$data = array (
			'name'		=>	$q2[0]['name'],
			'desc'		=>	$q2[0]['desc'],
			'objs'		=>	$objs
		);*/
		$this->module->parse('cattours', 'categories.php', $data);
	}
	
	function objects () {
		$id = $this->uri->segment (4);
		if ((int) $id == 0) redirect (site_url (array ('cattours', 'tours')));
		$q = $this->db->where ('cid', $id)->get ('th_tours_objs')->result_array ();
		$q2 = $this->db->where ('id', $id)->get ('th_tours_cats')->result_array ();
		$objs = array ();
		foreach ($q AS $v) {
			$objs[]['link'] = anchor (site_url (array ('cattours', 'tours', 'object', $v['id'])), $v['name']);
		}
		$tours = $this->_getArrs ('tours');
		$nums = $this->_getArrs ('nums');
		$days = $this->_getArrs ('days');
		$moons = $this->_getArrs ('moons');
		$thisDay = date ('d');
		$thisMoon = date ('m');
		$data = array (
			'name'		=>	$q2[0]['name'],
			'desc'		=>	$q2[0]['desc'],
			'objs'		=>	$objs,
			'fo'		=>	form_open (site_url (array ('cattours', 'tours', 'setorder')), array ('id' => 'fo', 'onsubmit' => 'return obj.checkFields ();')),
			'fc'		=>	form_close (),
			'fname'		=>	form_input ('fname', '', 'id="fname"'),
			'femail'	=>	form_input ('femail', '', 'id="femail"'),
			'fphone'	=>	form_input ('fphone', '', 'id="fphone"'),
			'ftname'	=>	$tours[$id],
			//'ftype'		=>	form_dropdown ('ftype', $tours, $id),
			//'ptyurl'	=>	"'" . site_url (array ('cattours', 'tours', 'gettypes')) . "'",
			//'ptourl'	=>	"'" . site_url (array ('cattours', 'tours', 'gettours')) . "'",
			'fnums'		=>	form_dropdown ('fnums', $nums),
			'fdays'		=>	form_dropdown ('fdays', $days, $thisDay),
			'fmoons'	=>	form_dropdown ('fmoons', $moons, $thisMoon),
			'fdaye'		=>	form_dropdown ('fdaye', $days, $thisDay),
			'fmoone'	=>	form_dropdown ('fmoone', $moons, $thisMoon),
			'fneeds'	=>	form_textarea ('fneeds', ''),
			'fmore'		=>	form_textarea ('fmore', ''),
			'ftid'		=>	form_hidden ('ftid', $id),
			'fbut'		=>	form_submit ('fbut', 'Сделать заказ')
		);
		$this->module->parse('cattours', 'objects.php', $data);
	}

	function object () {
		$id = $this->uri->segment (4);
		if ((int) $id == 0) redirect (site_url (array ('cattours', 'tours')));
		$q = $this->db->where ('id', $id)->get ('th_tours_objs')->result_array ();
		$data = array (
			'name'	=>	$q[0]['name'],
			'desc'	=>	$q[0]['desc']
		);
		$this->module->parse('cattours', 'object.php', $data);
	}
	
	function setOrder () {
		if ($this->input->post ('fbut') == false) {
			if ($this->input->server ('HTTP_REFERER') !== false) {
				redirect ($this->input->server ('HTTP_REFERER'));
			} else {
				redirect (base_url ());
			}
		}
		$tid = (int) $this->input->post ('ftid');
		$q = $this->db->query ("SELECT `th_tours`.* FROM `th_tours`, `th_tours_cats` WHERE `th_tours_cats`.`id` = '" . $tid . "' AND `th_tours`.`id` = `th_tours_cats`.`tid` LIMIT 1")->result_array ();

		$data = array (
			'name'	=>	$this->input->post ('fname'),
			'email'	=>	$this->input->post ('femail'),
			'phone'	=>	$this->input->post ('fphone'),

			'nums'	=>	(int) $this->input->post ('fnums'),
			'sday'	=>	(int) $this->input->post ('fdays'),
			'smoon'	=>	(int) $this->input->post ('fmoons'),
			'eday'	=>	(int) $this->input->post ('fdaye'),
			'emoon'	=>	(int) $this->input->post ('fmoone'),
			'needs'	=>	$this->input->post ('fneeds'),
			'more'	=>	$this->input->post ('fmore')
		);

		$tourId = ($this->input->post ('newTour') == '1') ? $this->input->post ('trName') : $tid;
		$data['tid'] = $tourId;
		
		$q2 = $this->db->where ('id', $tourId)->limit (1)->get ('th_tours_cats')->result_array ();
		$moons = $this->_getArrs ('moons');
		$nums = $this->_getArrs ('nums');

		$this->load->library ('email');
		$this->email->from('support@zesar.ru', 'Оповещение о заказе');
		$this->email->to($q[0]['email']);
		$this->email->subject('Новый заказ');
		$message =
			'Заказчик: ' . $data['name'] . "\n" .
			'Email: ' . $data['email'] . "\n" .
			'Телефон: ' . $data['phone'] . "\n" .
			'Тур: ' . $q2[0]['name'] . "\n" .
			'Количество человек в группе: ' . $nums[$data['nums']] . "\n" .
			'Период: с ' . $data['sday'] . ' ' . $moons[$data['smoon']] . ' по ' . $data['eday'] . ' ' . $moons[$data['emoon']] . "\n\n" .
			"Пожелания:\n" . $data['needs'] . "\n\n" .
			"Дополнительная информация:\n" . $data['more'];
		$this->email->message($message);	
		$this->email->send(); 
		$this->db->insert ('th_tours_ords', $data);
		$this->module->parse('cattours', 'getord.php', array ('link' => anchor (site_url (array ('cattours', 'tours', 'objects', $tid)), 'Вернуться на страницу тура')));
	}

	function _getArrs ($type = '') {
		$item = array ();
		switch ($type) {
			case 'tours':
				$temp_q = $this->db->select ('tid')->where ('id', (int) $this->uri->segment (4))->limit (1)->get ('th_tours_cats')->result_array ();
				$q = $this->db->where ('tid', $temp_q[0]['tid'])->get ('th_tours_cats')->result_array ();
				foreach ($q AS $v) $item[$v['id']] = $v['name'];
				break;
			case 'nums':
				for ($i = 1; $i <= 10; $i++) $item[$i] = $i;
				$item[11] = '10 - 15';
				$item[12] = '16 - 20';
				$item[13] = '20 - 30';
				$item[14] = 'более 30';
				break;
			case 'days':
				for ($i = 1; $i < 31; $i++) {
					$item[$i] = $i;
				}
				break;
			case 'moons':
				$item = array (1 =>
					'Январь',
					'февраль',
					'Март',
					'Апрель',
					'Май',
					'Июнь',
					'Июль',
					'Август',
					'Сентябрь',
					'Октябрь',
					'Ноябрь',
					'Декабрь'
				);
				break;
			default:
				$item = false;
				break;
		}
		return $item;
	}
	
	function getTours () {
		if (!($id = $this->input->post ('segment'))) die;
		$q = $this->db->select ('tid')->where ('id', $id)->limit (1)->get ('th_tours_cats')->result_array ();
		if (count ($q) == 0) die;
		$q2 = $this->db->where ('tid', $q[0]['tid'])->get ('th_tours_cats')->result_array ();
		$html = '';
		foreach ($q2 AS $v) {
			$sel = ($id == $v['id'])?'selected="selected"':'';
			$html .= '<option ' . $sel . ' value="' . $v['id'] . '">' . $v['name'] . '</option>';
		}
		echo $html;
		die;
		
	}
	
	function getTypes () {
		if (!($id = $this->input->post ('segment'))) die;
		$q = $this->db->get ('th_tours')->result_array ();
		$q2 = $this->db->query ("SELECT `th_tours`.`id` FROM `th_tours`, `th_tours_cats` WHERE `th_tours`.`id` = `th_tours_cats`.`tid` AND `th_tours_cats`.`id` = " . (int) $id . " LIMIT 1")->result_array ();
		$html = '';
		foreach ($q AS $v) {
			$sel = ($q2[0]['id'] == $v['id'])?'selected="selected"':'';
			$html .= '<option ' . $sel . ' value="' . $v['id'] . '">' . $v['name'] . '</option>';
		}
		echo $html;
		die;
	}
	
	function getTourId () {
		if (!($id = $this->input->post ('segment'))) die;
		$q = $this->db->select ('id')->where ('tid', $id)->limit (1)->get ('th_tours_cats')->result_array ();
		echo $q[0]['id'];
		die;
	} 
}
?>
