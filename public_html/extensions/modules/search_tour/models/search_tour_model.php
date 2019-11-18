<?php
	class Search_Tour_model extends Model {

		var $ftable;	// Таблица 'th_folder'
		var $atable;	// Таблица 'th_article'
		
		function Search_Tour_model () {
			parent::Model ();
			$this->ftable = 'th_folder';
			$this->atable = 'th_article';
		}

		// получение ID'шников разделов
		function gettypetours ($id) {
			$sorts = $this->db->select ('sort')->where ('id', $id)->limit (1)->get ($this->ftable)->result_array ();
			if (count ($sorts) == 0) return false;
			$arrEls = explode (';', $sorts[0]['sort']);
			$arrFolds = array ();
			foreach ($arrEls AS $v) {
				if (substr ($v, 0, 1) == 'f' && !empty ($v)) {
					$arrFolds[] =  substr ($v , 1);
				}
			}
			return $arrFolds;
		}

		// получение ID'шников подразделов
		function getsubtypetours ($arr) {
			$arrFolds = array ();
			foreach ($arr AS $v) {
				$tempArr = $this->getTypeTours ($v);
				foreach ($tempArr AS $v2) {
					$arrFolds[] = $v2;
				}
			}
			return $arrFolds;
		}

		// получение ID'шников названий
		function getnametours ($arr) {
			$this->db->select ('sort');
			$starting = true;
			$func = 'where';
			foreach ($arr AS $v) {
				$this->db->$func ('id', $v);
				if ($starting) {
					$func = 'or_where';
					$starting = false;
				}
			}
			$folds = $this->db->get ($this->ftable)->result_array ();
			$files = array ();
			foreach ($folds AS $v) {
				$tmpSorts = explode (';', $v['sort']);
				foreach ($tmpSorts AS $v2) {
					if (!empty ($v2) && substr ($v2, 0, 1) == 'a') {
						$files[] = substr ($v2, 1);
					}
				}
			}
			$starting = true;
			$where = 'WHERE (';
			foreach ($files AS $v) {
				if ($starting) {
					$starting = false;
				} else {
					$where .= ' OR ';
				}
				$where .= " `file_id` = '" . (int) $v . "'";
			}
			$where .= ') AND `tid` > 0';
			$query = 'SELECT `file_id` FROM `' . $this->atable . '` ' . $where;
			$names = $this->db->query ($query)->result_array ();
			$result = array ();
			foreach ($names AS $v) {
				$result[] = $v['file_id'];
			}
			return $result;
		}

		// Получение результатов
		function getresults ($tours) {
			$results = array ();
			
			$i = -1;

			// Поиск по названию
			if ($tours->name !== false) {
				$where = 'WHERE (';
				$starting = true;
				foreach ($tours->nameIds AS $v) {
					if ($starting) {
						$starting = false;
					} else {
						$where .= ' OR ';
					}
					$where .= " `file_id` = '" . $v . "' ";
				}
				$where .= ") AND `title` LIKE '%" . mysql_real_escape_string ($tours->name) . "%'";
				$names = $this->db->query ('SELECT * FROM `' . $this->atable . '` ' . $where)->result_array ();
				
				foreach ($names AS $v) {
					$q1 = $this->db->like ('sort', 'a'.$v['file_id'].';')->limit (1)->get ($this->ftable)->result_array ();
					$q2 = $this->db->like ('sort', 'f'.$q1[0]['id'].';')->get ($this->ftable)->result_array ();
					$results[++$i]['name_id'] = $v['file_id'];
					$results[$i]['name'] = $v['title'];
					$results[$i]['subtype_id'] = $q1[0]['id'];
					$results[$i]['subtype'] = $q1[0]['title'];
					$results[$i]['type_id'] = $q2[0]['id'];
					$results[$i]['type'] = $q2[0]['title'];
				}

			}

			// Поиск по подтипу
			if ($tours->subtype !== false) {
				$where = 'WHERE (';
				$starting = true;
				foreach ($tours->subtypeIds AS $v) {
					if ($starting) {
						$starting = false;
					} else {
						$where .= ' OR ';
					}
					$where .= " `id` = '" . $v . "' ";
				}
				$where .= ") AND `title` LIKE '%" . mysql_real_escape_string ($tours->subtype) . "%'";
				$subTypes = $this->db->query ('SELECT * FROM `' . $this->ftable . '` ' . $where)->result_array ();
				foreach ($subTypes AS $v) {
					$types = $this->db->like ('sort', 'f'.$v['id'].';')->limit (1)->get ($this->ftable)->result_array ();
					$tempNames = explode (';', $v['sort']);
					foreach ($tempNames AS $v2) {
						if (!empty ($v2) && substr ($v2, 0, 1) == 'a') {
							$names = $this->db->where ('file_id', substr ($v2, 1))->limit (1)->get ($this->atable)->result_array ();
							$results[++$i]['name_id'] = $names[0]['file_id'];
							$results[$i]['name'] = $names[0]['title'];
							$results[$i]['subtype_id'] = $v['id'];
							$results[$i]['subtype'] = $v['title'];
							$results[$i]['type_id'] = $types[0]['id'];
							$results[$i]['type'] = $types[0]['title'];
						}
					}
				}
			}

			// Поиск по типу
			if ($tours->type !== false) {
				$where = 'WHERE (';
				$starting = true;
				foreach ($tours->typeIds AS $v) {
					if ($starting) {
						$starting = false;
					} else {
						$where .= ' OR ';
					}
					$where .= " `id` = '" . $v . "' ";
				}
				$where .= ") AND `title` LIKE '%" . mysql_real_escape_string ($tours->type) . "%'";
				$types = $this->db->query ('SELECT * FROM `' . $this->ftable . '` ' . $where)->result_array ();
				foreach ($types AS $v) {
					$tempSubTypes = explode (';', $v['sort']);
					foreach ($tempSubTypes AS $v2) {
						if (!empty ($v2) && substr ($v2, 0, 1) == 'f') {
							$subTypes = $this->db->where ('id', substr ($v2, 1))->get ($this->ftable)->result_array ();
							foreach ($subTypes AS $v3) {
								$tempNames = explode (';', $v3['sort']);
								foreach ($tempNames AS $v4) {
									if (!empty ($v4) && substr ($v4, 0, 1) == 'a') {
										$names = $this->db->where ('file_id', substr ($v4, 1))->where ('tid >', 0)->limit (1)->get ($this->atable)->result_array ();
										$results[++$i]['name_id'] = $names[0]['file_id'];
										$results[$i]['name'] = $names[0]['title'];
										$results[$i]['subtype_id'] = $v3['id'];
										$results[$i]['subtype'] = $v3['title'];
										$results[$i]['type_id'] = $v['id'];
										$results[$i]['type'] = $v['title'];
									}
								}
							}
						}
					}
				}
			}

			return /*array_unique*/ ($results);
		}

		// Отфильтрованные результаты
		function getFilterResults ($arr) {
			foreach ($arr AS $k => $v) {
				$arr[$k]['name_link'] = site_url (array ('article', 'article', 'view', $v['name_id']));
				$arr[$k]['subtype_link'] = site_url (array ('folders', 'folders', 'get', $v['subtype_id']));
				$arr[$k]['type_link'] = site_url (array ('folders', 'folders', 'get', $v['type_id']));
			}
			return $arr;
		}
		
	}
?>
