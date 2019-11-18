<?php

	class FoldLang_Model extends Model {

		var $ltable;
		var $ftable;

		function FoldLang_Model () {
			parent::Model ();
			$this->ltable = 'th_language_structure';
			$this->ftable = 'th_folder';
		}

		// Получить список языков
		function getLangs () {
			return $this->db->select ('`id`, `text` AS \'lang\', `fid`, `default`')->get ($this->ltable)->result_array ();
		}

		// Получить массив элементов для выпадающих форм
		function getDrops () {
			$left = 1;
			$drops = array ();
			do {
				$q = $this->db->where ('lft', $left)->get ($this->ftable)->result_array ();
				if (count ($q) > 0) {
					list ($drops[count ($drops)]) = $q;
				} else {
					break;
				}
				$left = $drops[count ($drops) - 1]['rgt'] + 1;
			} while (count ($drops[count ($drops) - 1]) > 0);
			$sortIds = array ();
			foreach ($drops AS $v) {
				$sortIds[$v['id']] = $v['title'];
			}
			return $sortIds;
		}

		// Получить все объекты вместе с выпадающими формами
		function getFormDrop ($langs, $drops) {
			$objs = array ();
			foreach ($langs AS $k => $v) {
				$fname = 'drop[' . $v['id'] . ']';
				$rcheck = ($v['default'] == 1) ? true : false;
				$objs[$k]['lang']	=	$v['lang'];
				$objs[$k]['drop']	=	form_dropdown ($fname, $drops, $v['fid']);
				$objs[$k]['radio']	=	form_radio ('radio', $v['id'], $rcheck);
			}
			return $objs;
		}

		// Записать языки в базу
		function setLangs ($item, $radio) {
			foreach ($item AS $k => $v) {
				$this->db->where ('id', $k)->update ($this->ltable, array ('fid' => $v));
			}
			$this->db->update ($this->ltable, array ('default' => 0));
			$this->db->where ('id', $radio)->update ($this->ltable, array ('default' => 1));
		}
	}
	
?>
