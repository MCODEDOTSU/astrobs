<?php
	class Central_menu_model extends Model {

		var $sort_id;
		var $ftable;
		var $ctable;
		var $atable;
		var $filetable;

		function Central_menu_model () {
			parent::Model ();
			$this->sort_id = $this->_getHM ();
			$this->ftable = 'th_folder';
			$this->ctable = 'th_category';
			$this->atable = 'th_anchor';
			$this->filetable = 'th_file';
		}

		// ВЫвод меню туров
		function get () {
			$isSelect = false;
			$id = $this->uri->segment (4);
			$arrIds = $this->gettours ();
			$tours = $this->getfolds ($arrIds);
			$arrTours = array ();
			$selTour = 0;
			$i = -1;
			$ind = 0;
			foreach ($tours AS $v) {
				$arrTours[++$i]['select'] = $this->isSelected ($v['id'], $id, $this->uri->segment (1));
				$arrTours[$i]['name'] = $v['title'];
				$arrTours[$i]['img1'] = $v['img1'];
				$arrTours[$i]['img2'] = $v['img2'];
				$arrTours[$i]['img3'] = $v['img3'];
				$arrTours[$i]['id'] = $v['id'];
				if ($arrTours[$i]['select']) {
					$isSelect = true;
					$selTour = $v['id'];
					$ind = $i;
				}
			}
			return $this->getHTML ($arrTours, $isSelect, $selTour, $ind);
			
		}

		function getHTML ($arr, $isSelect = false, $id = 0, $ind = 0) {
			$html = '<ul class="tours_menu">';
			if (!$isSelect ) {
				if ($this->uri->total_segments () == 0) {
					foreach ($arr AS $k => $v) {
						$html .= '<li>' . anchor (site_url (array ('folders', 'folders', 'get', $v['id'])), '<img id="timg_' . $v['id'] . '" src="' . site_url (array ('uploads', 'folders', 'img1_' . $v['id'] . '.' . $v['img1'])) . '"><span>' . $v['name'] . '</span>') . '</li>';
					} 
				} else {
					return '';
				}
			} else {
				$html .= '<li class="TourSelected">' . anchor (site_url (array ('folders', 'folders', 'get', $id)), '<img id="timg_' . $id . '" src="' . site_url (array ('uploads', 'folders', 'img1_' . $id . '.' . $arr[$ind]['img1'])) . '"><span>' . $arr[$ind]['name'] . '</span>') . '</li>';
				foreach ($arr AS $k => $v) {
					if ($v['id'] != $id) {
						$html .= '<li class="tourMOver">' . anchor (site_url (array ('folders', 'folders', 'get', $v['id'])), '<img id="timg_' . $v['id'] . '" src="' . site_url (array ('uploads', 'folders', 'img2_' . $v['id'] . '.' . $v['img2'])) . '"><span>' . $v['name'] . '</span>', array ('onmouseover' => 'mouseRule.mouseOver (' . $v['id'] . ', \'' . $v['img3'] . '\');', 'onmouseout' => 'mouseRule.mouseUp (' . $v['id'] . ', \'' . $v['img2'] . '\');')) . '</li>';
					}
				}
			}
			$html .= '</ul>';
			return $html;
		}

		// Поиск в таблице 'th_anchor' ссылок, совподающих с 'REQUEST_URI' и запись их 'id' в массив данных
		function getAnchor () {
			$link1 = (substr ($this->input->server ('REQUEST_URI'), 0, 1) == '/') ? substr ($this->input->server ('REQUEST_URI') , 1) : $this->input->server ('REQUEST_URI');
			$linklen = strlen ($link1);
			$link1 = (substr ($link1, $linklen - 1, $linklen) == '/') ? substr ($link1, 0, $linklen - 1) : $link1;
			$link2 = '/' . $link1;
			$link3 = $link1 . '/';
			$link4 = '/' . $link1 . '/';

			$q = $this->db->query ("SELECT `file_id` FROM `th_anchor` WHERE `url` = '" . mysql_real_escape_string ($link1) . "' or `url` = '" . mysql_real_escape_string ($link2) . "' or `url` = '" . mysql_real_escape_string ($link3) . "' or `url` = '" . mysql_real_escape_string ($link4) . "'")->result_array ();

			$links = array ();
			foreach ($q AS $v) {
				$links[] = $v['file_id'];
			}
			return $links;
		}

		// Определить, совпадает ли 'id' 'URI' сегмента с разделом
		function folderPos ($id, $type) {
			$fid = 0;
			switch ($type) {
				case 'a':
					$q = $this->db->select ('folder_id, category_id')->where ('id', $id)->limit (1)->get ($this->filetable)->result_array ();
					if (count ($q) == 0) return array ();
					if ($q[0]['folder_id'] > 0) {
						$fid = $q[0]['folder_id'];
					}
					else if ($q[0]['category_id'] > 0) {
						$q = $this->db->select ('folder_id')->where ('id', $q[0]['category_id'])->limit (1)->get ($this->ctable)->result_array ();
						$fid = $q[0]['folder_id'];				
					}
					break;
				case 'c':
					$q = $this->db->select ('folder_id')->where ('id', $id)->limit (1)->get ($this->ctable)->result_array ();
					if (count ($q) == 0) return array (); 
					$fid = $q[0]['folder_id'];
					break;
				case 'f':
					$fid = $id;
					break;
			}
			return $this->arrFolds ($fid);
		}

		// получить массив 'ID' всех родительских разделов, включая исходный 'ID'
		function arrFolds ($id) {
			$rootfolds = array (1, 237);
			$arr = array ();
			$arr[] = $id;
			if (in_array ($id, $rootfolds)) {
				return $arr;
			}
			$q = $this->db->select ('id')->like ('sort', 'f'.$id.';')->limit (1)->get ($this->ftable)->result_array (); 
			if (count ($q) > 0) {
				$tempArr = $this->arrFolds ($q[0]['id']);
				foreach ($tempArr AS $v) {
					$arr[] = $v;
				}
			} 
			return $arr;
		}

		// Проверка на совпадаемость раздела с элементом в 'URI'
		// Если совпадает, то возвращаем TRUE, если нет, то возвращаем FALSE
		function isSelected ($fid, $id, $type) {
			$result = false;
			switch ($type) {
				case 'article':
				case 'photo':
				case 'video':
				case 'news':
				case 'document':
					$tempArr = $this->folderPos ($id, 'a');
					if (in_array ($fid, $tempArr)) $result = true;
					break;
				case 't_menu':
					$tempArr = $this->folderPos ($id, 'c');
					if (in_array ($fid, $tempArr)) $result = true;
					break;
				case 'folders':
					$tempArr = $this->folderPos ($id, 'f');
					if (in_array ($fid, $tempArr)) $result = true;
					break;
				default:
					$lids = $this->getAnchor ();
					foreach ($lids AS $v) {
						$tempArr = $this->folderPos ($v, 'a');
						if (in_array ($fid, $tempArr) && count ($tempArr) > 0) {
							$result = true;
							break;
						}
					}
					break;
			}
			return $result;
		}

		// Получаем массив разделов
		function getfolds ($arr) {
			$starting = true;
			foreach ($arr AS $v) {
				if ($starting) {
					$this->db->where ('id', $v);
					$starting = false;
				} else {
					$this->db->or_where ('id', $v);
				}
			}
			$q = $this->db->get ($this->ftable)->result_array ();
			$qcnt = count ($q);
			$arrs = array ();
			foreach ($arr AS $k => $v) {
				for ($i = 0; $i < $qcnt; $i++) {
					if ($q[$i]['id'] == $v) $arrs[$v] = $q[$i];
				}
			}
			return $arrs;
		}

		// получаем массив ID разделов
		function gettours () {
			list ($q) = $this->db->select ('sort')->where ('id', $this->sort_id)->limit (1)->get ($this->ftable)->result_array ();
			$arrfolds = explode (';', $q['sort']);
			$arrIds = array ();
			foreach ($arrfolds AS $v) {
				if (!empty ($v) && substr ($v, 0, 1) == 'f') {
					$arrIds[] = substr ($v, 1);
				}
			}
			return $arrIds;
		}

		function _getHM () {
			$lang =  ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
			switch ($lang) {
				case 'russian':
					$result = 257;
					break;
				case 'english':
					$result = 259;
					break;
				case 'kazakh':
					$result = 324;
					break;
				default:
					$result = false;
					break;
			}
			return $result;
		}

		function _getDefaultLang () {
			$q = $this->db->select ('language')->where ('default', 1)->limit (1)->get ('th_language_structure')->result_array ();
			return $q[0]['language'];
		}

		
	}
?>
