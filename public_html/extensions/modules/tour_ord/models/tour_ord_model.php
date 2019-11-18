<?php
class Tour_ord_model extends Model
{
    function Tour_ord_model()
    {
        parent::Model();
        $this->TABLE = 'th_article';   
    }

    function _getArrs ($type = '') {
		$item = array ();
		switch ($type) {
			case 'tours':
				$q = $this->db->get ('th_tours')->result_array ();
				foreach ($q AS $v) $item[$v['id']] = $v['name'];
				break;
			case 'nums':
				for ($i = 1; $i <= 10; $i++) $item[$i] = $i;
				$item[11] = '10 - 15';
				$item[12] = '16 - 20';
				$item[13] = '20 - 30';
				$item[14] = 'более 30';
				break;
			case 'lang_nums':
				for ($i = 1; $i <= 10; $i++) $item[$i] = $i;
				$item[11] = '10 - 15';
				$item[12] = '16 - 20';
				$item[13] = '20 - 30';
				$item[14] = $this->lang->mline ('tour_ord_more_30');
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
			case 'lang_moons':
				$item = array (1 =>
					$this->lang->mline ('tour_ord_jan'),
					$this->lang->mline ('tour_ord_feb'),
					$this->lang->mline ('tour_ord_mar'),
					$this->lang->mline ('tour_ord_apr'),
					$this->lang->mline ('tour_ord_may'),
					$this->lang->mline ('tour_ord_jun'),
					$this->lang->mline ('tour_ord_jul'),
					$this->lang->mline ('tour_ord_aug'),
					$this->lang->mline ('tour_ord_sep'),
					$this->lang->mline ('tour_ord_oct'),
					$this->lang->mline ('tour_ord_nov'),
					$this->lang->mline ('tour_ord_dec')
				);
				break;
			default:
				$item = false;
				break;
		}
		return $item;
	}

	function _getUriId () {
		$q = $this->db->select ('tid')->where ('file_id', $this->uri->segment (4))->get ('th_article')->result_array ();
		return 13379;
		return $q[0]['tid'];
	}

	

	function _getOrdFrm () {
		$this->lang->setModule ('tour_ord');
		$tours = $this->_getArrs ('tours');

		
		$nums = $this->_getArrs ('lang_nums');
		$days = $this->_getArrs ('days');
		$moons = $this->_getArrs ('lang_moons');
		$thisDay = date ('d');
		$thisMoon = date ('m');
		return '
			<div id="order">
				'.form_open (site_url (array ('tour_ord', 'tour_ord', 'setorder')), array ('id' => 'fo', 'onsubmit' => 'return tour_obj.checkFields ();')).'
					 <label for="fname">' . $this->lang->mline ('tour_ord_name') . ':</label>
					 '.form_input ('fname', '', 'id="fname"').'
					 <br>
					 <label for="femail">' . $this->lang->mline ('tour_ord_email') . ':</label>
					 '.form_input ('femail', '', 'id="femail"').'
					 <br>
					 <label for="fphone">' . $this->lang->mline ('tour_ord_phone') . ':</label>
					 '.form_input ('fphone', '', 'id="fphone"').'
					 <br>
					 <label>' . $this->lang->mline ('tour_ord_tour') . ':</label> '.form_dropdown ('set_tours', $tours).'<br> 
					 <div id="tourSels" style="display: none">
					 Тип тура: <select id="trType" style="display: block" onchange="tour_obj.setChangeTour (this.value);"></select> 
					 ' . $this->lang->mline ('tour_ord_tour') . ': <select id="trName" name="trName" style="display: block"></select>
					 </div>
					 <br>
					 <label for="fnums">' . $this->lang->mline ('tour_ord_count_people') . ':</label>
					 '.form_dropdown ('fnums', $nums).'
					 <br>
					 <label>' . $this->lang->mline ('tour_ord_period') . ':</label>
					 ' . $this->lang->mline ('tour_ord_from') . ' '.form_dropdown ('fdays', $days, $thisDay).' '.form_dropdown ('fmoons', $moons, $thisMoon).'
					 
					 ' . $this->lang->mline ('tour_ord_to') . ' '.form_dropdown ('fdaye', $days, $thisDay).' '.form_dropdown ('fmoone', $moons, $thisMoon).'
					 <br>
					 <label for="fneeds">' . $this->lang->mline ('tour_ord_needs') . ':</label>
					 '.form_textarea ('fneeds', '').'
					 <br>
					 <label for="fnums">' . $this->lang->mline ('tour_ord_more') . ':</label>
					 '.form_textarea ('fmore', '').'
					 <br>
					 '.form_hidden ('ftid', $this->_getUriId ()).'
					 '.form_hidden ('file_id', $this->uri->segment (4)).'
					 <input type="hidden" name="newTour" id="newTour" value="0">
					 '.form_submit ('fbut', $this->lang->mline ('tour_ord_make_ord')).'
					 <br>
				'.form_close ().'
			</div>
		';
	}

	function getOrdTour ($id) {
		$q = $this->db->where ('id', $id)->limit (1)->get ('th_tours')->result_array ();
		return $q[0]['email'];
	}

	function mailTo ($orderer = null, $mail = null, $phone = null, $tour = null, $cntP = null, $sday = null, $smoon = null, $eday = null, $emoon = null, $needs = null, $more = null, $mymail = null) {

		$this->load->library ('email');
		$this->email->from('support@zesar.ru', 'Оповещение о заказе');
		$this->email->to($mymail);
		$this->email->subject('Новый заказ');
		$message =
			'Заказчик: ' . $orderer . "\n" .
			'Email: ' . $mail . "\n" .
			'Телефон: ' . $phone . "\n" .
			'Тур: ' . $tour . "\n" .
			'Количество человек в группе: ' . $cntP . "\n" .
			'Период: с ' . $sday . ' ' . $smoon . ' по ' . $eday . ' ' . $emoon . "\n\n" .
			"Пожелания:\n" . $needs . "\n\n" .
			"Дополнительная информация:\n" . $more;
		$this->email->message($message);	
		$this->email->send();
	}

	function getTourName ($id) {
		$q = $this->db->where ('file_id', $id)->limit (1)->get ('th_article')->result_array ();
		return $q[0]['title'];
	}
}
?>
