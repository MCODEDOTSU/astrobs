<?php
class Tours extends Public_Controller
{
    function Tours()
    {
        parent::Public_Controller();
    }
    
    function get () {
		$id = $this->uri->segment (4);
		$q = $this->db->where ('id', $id)->limit (1)->get ('th_folder')->result_array ();
		//print_r ($q);
		$arrIds = explode (';', $q[0]['sort']);
		$start = true;
		$query = "SELECT * FROM `th_folder` WHERE `is_tour` = '1' AND (";
		foreach ($arrIds AS $k => $v) {
			if (substr ($v, 0, 1) == 'f' && !empty ($v)) {
				if (!$start) $query .= 'OR';
				$query .= " `id` = '" . (int) substr ($v, 1) . "' ";
				//$this->db->where ('id', substr ($v, 1));
				$start = false;
			} /*else if (!$start && substr ($v, 0, 1) == 'f' && !empty ($v)) {
				$this->db->or_where ('id', substr ($v, 1));
			}*/
		}
		$query .= ")";
		//print_r ($query);
		if (!$start) {
			$q2 = $this->db->query ($query)->result_array ();
		} else {
			$q2 = array ();
		}
		$arr = array ();
		foreach ($q2 AS $v) {
			$arr[]['title'] = anchor (site_url (array ('tours', 'tours', 'get', $v['id'])), $v['title']);
		}
		$moons = array (1 => 
			'Январь', 
			'Февраль', 
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
		for ($i = 1; $i <= 31; $i++) {
			$days[$i] = $i;
		}
		for ($i = 0; $i < 5; $i++) {
			$thisDate = date ('Y') + $i;
			//echo $thisDate . "\n<br>";
			$years[$thisDate] = $thisDate;
		}
		//print_r ($thisDate);
		//print_r ($arrIds);
		$data = array (
			'name'		=> $q[0]['title'],
			'desc'		=> $q[0]['desc_tour'],
			'arr'		=> $arr,
			'fo'		=> form_open (site_url (array ('tours', 'tours', 'setord')), array ('onsubmit' => "return scr.checkF ()")),
			'fname'		=> form_label ('Имя: ', 'fname') . form_input ('fname', '', 'id="fname"'),
			'fphone'	=> form_label ('Телефон: ', 'fphone') . form_input ('fphone', '', 'id="fphone"'),
			'femail'	=> form_label ('E-Mail: ', 'femail') . form_input ('femail', '', 'id="femail"'),
			'ffrom'		=> form_label ('Адрес: ', 'ffrom') . form_input ('ffrom', '', 'id="ffrom"'),
			'fdate'		=> form_dropdown ('fday', $days, date ('d')) . form_dropdown ('fmoon', $moons, date ('m')) . form_dropdown ('fyear', $years, date ('Y')),
			'fid'		=> form_hidden ('fid', $id, 'id="fid"'),
			//'ftours'	=> form_dropdown (),
			'fs'		=> form_submit ('sub', 'Сделать заказ'),
			'fc'		=> form_close ()
		);
		$this->module->parse('tours', 'view.php', $data);
	}

	function setord () {

	}
}
?>
