<?php
	class minimap_model extends Model {

		var $ltable;
		var $ftable;
		var $fltable;
		var $ctable;

		function minimap_model () {
			parent::Model ();
			$this->ltable = 'th_language_structure';
			$this->ftable = 'th_folder';
			$this->fltable = 'th_file';
			$this->ctable = 'th_category';
		}

		// Подгружаем миникарту под контентом
		function get () {
			$rid = $this->getLangId ();
			$arrSort = $this->getArrSort ($rid);
			$arrMap = array ();
			$links = '';
			foreach ($arrSort AS $v) {
				$tempArrs = $this->getStructs ($v);
				foreach ($tempArrs AS $v2) {
					$arrMap[] = $v2;
				}
			}
			$start = true;
			foreach ($arrMap AS $v) {
				if (!$start) $links .= ' | ';
				switch ($v['type']) {
					case 'anchor':
					case 'article':
					case 'news':
					case 'photo':
					case 'video':
					case 'document':
						$links .= anchor (site_url (array ($v['type'], $v['type'], 'view', $v['id'])), $v['name']);
						break;
					case 'category':
						$links .= anchor (site_url (array ('t_menu', 't_menu', 'category', $v['id'])), $v['name']);
						break;
					case 'folder':
						$links .= anchor (site_url (array ('folders', 'folders', 'get', $v['id'])), $v['name']);
						break;
					default:
						break;
				}
				$start = false;
			}
			return $links;
			//$this->module->parse ('minimap', 'minimap', array ('links' => $links));
		}

		// получение массива структуры для карты сайта
		function getStructs ($id) {
			$q = $this->db->select ('sort')->where ('id', $id)->limit (1)->get ($this->ftable)->result_array ();
			$tempArrs = explode (';', $q[0]['sort']);
			$i = -1;
			$arrStruct = array ();
			foreach ($tempArrs AS $v) {
				if (!empty ($v)) {
					switch (substr ($v, 0, 1)) {
						case 'f':
							$arrStruct[++$i]['id'] = substr ($v, 1);
							$arrStruct[$i]['type'] = 'folder';
							$arrStruct[$i]['name'] = $this->getName ($arrStruct[$i]['id'], 'f');
							break;
						case 'c':
							$arrStruct[++$i]['id'] = substr ($v, 1);
							$arrStruct[$i]['type'] = 'category';
							$arrStruct[$i]['name'] = $this->getName ($arrStruct[$i]['id'], 'c');
							break;
						case 'a':
							$arrStruct[++$i]['id'] = substr ($v, 1);
							$arrStruct[$i]['type'] = $this->getType ($arrStruct[$i]['id']);
							$arrStruct[$i]['name'] = $this->getName ($arrStruct[$i]['id'], 'a');
							break;
						default:
							break;
					}
				}
			}
			return $arrStruct;
		}

		// Получение имени у элемента структуры
		function getName ($id, $type) {
			$table = '';
			switch ($type) {
				case 'a':
					$table = $this->fltable;
					break;
				case 'f':
					$table = $this->ftable;
					break;
				case 'c':
					$table = $this->ctable;
					break;
				default:
					break;
			}

			$q = $this->db->select ('title')->where ('id', $id)->limit (1)->get ($table)->result_array ();
			return $q[0]['title'];
		}

		// получение типа элемента структуры
		function getType ($id) {
			$q = $this->db->select ('type')->where ('id', $id)->limit (1)->get ($this->fltable)->result_array ();
			return $q[0]['type'];
		}

		// Получение массива с Id'шниками категорий
		function getArrSort ($id) {
			$q = $this->db->select ('sort')->where ('id', $id)->limit (1)->get ($this->ftable)->result_array ();
			$tempArrs = explode (';', $q[0]['sort']);
			$arrIds = array ();
			foreach ($tempArrs AS $v) {
				if (!empty ($v) && substr ($v, 0, 1) == 'f' && $v != 'f190' && $v != 'f238' && $v != 'f257' && $v != 'f259') {
					$arrIds[] = substr ($v, 1);
				}
			}
			return $arrIds;
		}

		// Получение ID root'а
		function getLangId () {
			$q = $this->db->select ('fid')->where ('language', $this->_getHM ())->limit (1)->get ($this->ltable)->result_array ();
			return $q[0]['fid'];
		}

		// Определение текущего языка
		function _getHM () {
			return ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
		}

		// Загрузка языка по умолчанию
		function _getDefaultLang () {
			$q = $this->db->select ('language')->where ('default', 1)->limit (1)->get ('th_language_structure')->result_array ();
			return $q[0]['language'];
		}
	}
?>
