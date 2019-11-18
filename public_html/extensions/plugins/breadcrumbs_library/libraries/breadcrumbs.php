<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Breadcrumbs {

		var $CI;
		var $table;
		var $folder;
		var $category;
		var $file;
		var $type;
		var	$homePage;

		function Breadcrumbs () {
			$this->CI = &get_instance ();
			$this->folder = 'th_folder';
			$this->category = 'th_category';
			$this->file = 'th_file';
			$this->CI->lang->setPlugin ('breadcrumbs_library', 'public', $this->_getHM (), 'breadcrumbs_lang.php');
			$this->homePage = $this->CI->lang->pline ('breadcrumbs_home_page');
		}

		// Определение текущего языка
		function _getHM () {
			return ($this->CI->session->userdata ('language')) ? $this->CI->session->userdata ('language') : $this->_getDefaultLang ();
		}

		// Загрузка языка по умолчанию
		function _getDefaultLang () {
			$q = $this->CI->db->select ('language')->where ('default', 1)->limit (1)->get ('th_language_structure')->result_array ();
			return $q[0]['language'];
		}

		// Получаем главную страницу
		function getHome ($links = array ()) {
			$params = array ('class' => 'breadcrumbs_active');
			$html = '<div id="breadcrumbs">';
			if (count ($links) > 0) {
				foreach ($links AS $k => $v) {
					$html .= anchor (base_url (), $this->homePage) . ' / ' . anchor ($v, $k, $params);
				}
			} else {
				$html .= anchor (base_url (), $this->homePage, $params);
			}
			$html .= '</div>';
			return $html;
		}

		// Получаем ссылки по модулям
		function get ($id) {
			
			$file = $this->CI->db->where ('id', $id)->limit (1)->get ($this->file)->result_array ();
			$this->table = 'th_' . $file[0]['type'];
			$this->type = $file[0]['type'];
			$link = anchor (site_url (array ($this->type, $this->type, 'view', $id)), $file[0]['title'], array ('class' => 'breadcrumbs_active'));
			
			if ($file[0]['folder_id'] > 0) {
				$fid = $file[0]['folder_id'];
			}
			else if ($file[0]['category_id'] > 0) {
				$cat = $this->CI->db->where ('id', $file[0]['category_id'])->get ($this->category)->result_array (); 
				$link = anchor (site_url (array ('t_menu', 't_menu', 'category', $cat[0]['id'])), $cat[0]['title']) . ' / ' . $link;
				$fid = $cat[0]['folder_id'];
			}
			else {
				return false;
			}
			$arrLinks = $this->getArrLinks ($fid);
			$html = '<div id="breadcrumbs">' . anchor (base_url (), $this->homePage) . ' / ' . $arrLinks . $link . '</div>';

			return $html;
		}

		// Пути к рубрикам
		function catget ($id) {

			$file = $this->CI->db->where ('id', $id)->limit (1)->get ($this->category)->result_array ();
			$this->table = 'th_category';
			$this->type = 'category';
			$link = anchor (site_url (array ('t_menu', 't_menu', 'category', $id)), $file[0]['title'], array ('class' => 'breadcrumbs_active'));
			
			if ($file[0]['folder_id'] > 0) {
				$fid = $file[0]['folder_id'];
			}
			else {
				return false;
			}
			$arrLinks = $this->getArrLinks ($fid);
			$html = '<div id="breadcrumbs">' . anchor (base_url (), $this->homePage) . ' / ' . $arrLinks . $link . '</div>';

			return $html;
		}

		// Пути к разделам
		function foldget ($id) {
			$file = $this->CI->db->where ('id', $id)->limit (1)->get ($this->folder)->result_array ();
			$this->table = 'th_folder';
			$this->type = 'folder';
			$arrLinks = $this->getArrLinks ($id, true);
			$html = '<div id="breadcrumbs">' . anchor (base_url (), $this->homePage) . ' / ' . $arrLinks .  '</div>';
			return $html;
		}

		// Получаем  ссылки
		function getArrLinks ($id, $is_fold = false) {
			$arrHomes = $this->getMenues ();
			$arrMenues = array ();
			foreach ($arrHomes AS $v) {
				$home = $this->CI->db->select ('sort')->where ('id', $v)->get ($this->folder)->result_array ();
				if (count ($home) > 0 && !empty ($home[0]['sort'])) {
					$arrMenues = $this->setArrMenues ($home[0]['sort'], $arrMenues); 
				}
				
			}
			if (!in_array ($id, $arrMenues)) {
				$larrs = $this->getLinks ($id, $arrMenues);
			} else {
				$larrs = array ();
			}
			$cnt = count ($larrs) - 1;
			$html = '';
			for ($i = $cnt; $i >= 0; $i--) {
				$q = $this->CI->db->select ('title')->where ('id', $larrs[$i])->get ($this->folder)->result_array ();
				if ($i > 0 || !$is_fold) {
					$html .= anchor (site_url (array ('folders', 'folders', 'get',$larrs[$i])), $q[0]['title']) . ' / ';
				}
				else if ($is_fold) {
					$html .= anchor (site_url (array ('folders', 'folders', 'get',$larrs[$i])), $q[0]['title'], array ('class' => 'breadcrumbs_active'));
				}
			}
			return $html;
		}

		// Работа с массивами ссылок и их выборка
		function getLinks ($id, $arrs) {
			$lids[] = $id;
			$q = $this->CI->db->select ('id')->like ('sort', 'f' . $id . ';')->get ($this->folder)->result_array ();
			if (count ($q) > 0 && !in_array ($q[0]['id'], $arrs)) {
				$q2 = $this->getLinks ($q[0]['id'], $arrs);
				if (count ($q2) > 0) {
					foreach ($q2 AS $v) {
						$lids[] = $v;
					}
				}
			}
			return $lids;
		}

		// Получаем директории по ID
		function getMenues ($id = 1, $type = 'id') {
			$result = array ();
			$q = $this->CI->db->select ('rgt, id')->where ($type, $id)->get ($this->folder)->result_Array ();
			if (count ($q) > 0) {
				$result[] = $q[0]['id'];
				$arrs = $this->getMenues ($q[0]['rgt'] + 1, 'lft');
				foreach ($arrs AS $v) {
					$result[] = $v;
				}
			}

			return $result;
		}

		// Получуем массив IDs директорий
		function setArrMenues ($els, $arrs) {
			$arrEls = explode (';', $els);
			foreach ($arrEls AS $v) {
				if (!empty ($v) && substr ($v, 0, 1) == 'f') {
					$arrs[] = substr ($v, 1);
				}
			}
			return $arrs;
		}
		
	}
?>
