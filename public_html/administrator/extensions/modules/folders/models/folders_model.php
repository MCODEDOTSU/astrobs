<?php
	class Folders_model extends Model {
		var $ftypes;	// Типы файлов
		var $ftable;	// Таблица категорий
		var $fttable;	// Таблица типов категорий
	
		function Folders_model () {
			parent::Model ();
			$this->ftable = 'th_folder';
			$this->ftypes = array ('gif', 'jpg', 'jpeg', 'png');
			$this->fttable = 'th_folder_type';
		}

		// Получение разделов из БД
		function get ($where = array ()) {
			foreach ($where AS $k => $v) {
				$this->db->where ($k, $v);
			}

			return $this->db->get ($this->ftable)->result_array ();
		}

		// Получиние типов разделов
		function getFolderTypes () {
			$types = array ();
			$types[0] = 'Без типа';
			$arrTypes = $this->db->get ($this->fttable)->result_array ();
			foreach ($arrTypes AS $v) {
				$types[$v['id']] = $v['app_name'];
			}
			return $types;
		}

		// Получение расширения рисунка и непосредственная загрузка его на сервер
		function getImg ($item, $id, $pref = '') {
			$result = 'none';
			if (isset ($item) && $item['error'] == 0 && strlen ($item['name']) > 2 && strpos ($item['name'], '.')) {
				$arrItem = explode ('.', $item['name']);
				$ext = $arrItem[count ($arrItem) - 1];
				if (in_array ($ext, $this->ftypes)) {
					$result = $ext;
					move_uploaded_file ($item['tmp_name'], $this->input->server ('DOCUMENT_ROOT') . '/uploads/folders/' . $pref . $id . '.' . $ext);
				}
			}
			return $result;
		}

		function update ($set = array (), $where = array ()) {
			foreach ($where AS $k => $v) {
				$this->db->where ($k, $v);
			}
			if (count ($set) == 0) return false;
			$this->db->update ($this->ftable, $set);
		}

		// получение массива ID'шников разделов
		function getArrFolds ($id) {
			$arr = array ();
			$arr[] = $id;
			$sid = $this->db->select ('id')->like ('sort', 'f'.$id.';')->limit (1)->get ($this->ftable)->result_array ();
			if (count ($sid) > 0) {
				$tempArr = $this->getArrFolds ($sid[0]['id']);
				foreach ($tempArr AS $v) {
					$arr[] = $v;
				}
			}
			return $arr;
		}

		// получение родительского ID, в сллучае если его не существуе, метод возвращает FALSE
		function getParentId ($id) {
			$fid = $this->db->select ('id')->like ('sort', 'f'.$id.';')->limit (1)->get ($this->ftable)->result_array ();
			if (count ($fid) > 0) {
				return $fid[0]['id'];
			} else {
				return false;
			} 
		}
	}
?>
