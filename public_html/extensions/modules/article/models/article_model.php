<?php
class Article_model extends Model
{
    function Article_model()
    {
        parent::Model();
        $this->TABLE = 'th_article';   
    }
    
    function getArticle($where = array())
    {
        if(count($where) == 0) return FALSE;
        
        return $this->db->from($this->TABLE)->where($where)->get()->result_array();    
    }

    function article_in_category($articleId)
    {
        if(!is_numeric($articleId)) return false;

        $Article = $this->getArticle(array('file_id'=>$articleId));


        if(count($Article) == 0) return false;

        foreach($Article as $_article)
        {
            $data = array(
                'title' => ucfirst($_article['title']),
                'body'  => ucfirst($_article['body']),
                'created' => date('d.m.Y', strtotime($_article['created'])),
                'modifed' => $_article['modifed'],
                'is_tour' => '',
                'script' => '',
                'breadCrumbs' => ''
            );
            

            return $this->module->parse('article', 'view.php', $data, TRUE);
        }
    }

    function _getArrs ($type = '') {
		$item = array ();
		switch ($type) {
			case 'tours':
				$q = $this->db->where ('id', $temp_q = $this->_getUriId ())->get ('th_tours')->result_array ();
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

	function _getUriId () {
		$q = $this->db->select ('tid')->where ('file_id', $this->uri->segment (4))->get ('th_article')->result_array ();
		return $q[0]['tid'];
	}

	function _getOrdFrm () {

		$tours = $this->_getArrs ('tours');
		$nums = $this->_getArrs ('nums');
		$days = $this->_getArrs ('days');
		$moons = $this->_getArrs ('moons');
		$thisDay = date ('d');
		$thisMoon = date ('m');

		return '
			<a id="linkOpen" href="#" onclick="return false;">Заказать тур</a>
			<div id="order" style="display: none">
				'.form_open (site_url (array ('article', 'article', 'setorder')), array ('id' => 'fo', 'onsubmit' => 'return obj.checkFields ();')).'
					 <label for="fname">Ф.И.О.:</label>
					 '.form_input ('fname', '', 'id="fname"').'
					 <br>
					 <label for="femail">Email:</label>
					 '.form_input ('femail', '', 'id="femail"').'
					 <br>
					 <label for="fphone">Контактный телефон:</label>
					 '.form_input ('fphone', '', 'id="fphone"').'
					 <br>
					 <label>Тур:</label> '.$tours[$this->_getUriId ()].'<br> 
					 <div id="tourSels" style="display: none">
					 Тип тура: <select id="trType" style="display: block" onchange="obj.setChangeTour (this.value);"></select> 
					 Тур: <select id="trName" name="trName" style="display: block"></select>
					 </div>
					 <br>
					 <label for="fnums">Количество человек в группе:</label>
					 '.form_dropdown ('fnums', $nums).'
					 <br>
					 <label>Период:</label>
					 С '.form_dropdown ('fdays', $days, $thisDay).' '.form_dropdown ('fmoons', $moons, $thisMoon).'
					 
					 По '.form_dropdown ('fdaye', $days, $thisDay).' '.form_dropdown ('fmoone', $moons, $thisMoon).'
					 <br>
					 <label for="fneeds">Пожелания:</label>
					 '.form_textarea ('fneeds', '').'
					 <br>
					 <label for="fnums">Прочая информация:</label>
					 '.form_textarea ('fmore', '').'
					 <br>
					 '.form_hidden ('ftid', $this->_getUriId ()).'
					 '.form_hidden ('file_id', $this->uri->segment (4)).'
					 <input type="hidden" name="newTour" id="newTour" value="0">
					 '.form_submit ('fbut', 'Сделать заказ').'
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

	/*function getArcFolds ($id) {
		$q1 = $this->db->where ('id', $id)->limit (1)->get ('th_file')->result_array ();
		if ((int) $q1[0]['folder_id'] > 0)  {
			$fId = $q1[0]['folder_id'];
		}
		else if ((int) $q1[0]['category_id'] > 0) {
			$q2 = $this->db->where ('id', $q1[0]['category_id'])->limit (1)->get ('th_category')->result_array ();
			$fId = $q2[0]['folder_id'];
		} else {
			return false;
		}
		//print_r ($fId);
		//print_r ($item);
	}**/
}
?>
