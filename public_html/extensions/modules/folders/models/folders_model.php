<?php
	class Folders_model extends Model {
		var $foldTab;
		var $catTab;
		var $ancTab;
		var $artTab;
		var $docTab;
		var $nTab;
		var $pTab;
		var $vTab;
		var $fileTab;

		function Folders_model () {
			parent::Model ();
			$this->foldTab	=	'th_folder';
			$this->catTab	=	'th_category';
			$this->ancTab	=	'th_anchor';
			$this->artTab	=	'th_article';
			$this->docTab	=	'th_document';
			$this->nTab		=	'th_news';
			$this->pTab		=	'th_photo';
			$this->vTab		=	'th_video';
			$this->fileTab	=	'th_file';
		}

		function getLinks ($item) {
			$arrIts = explode (';', $item);
			$link = array ();
			foreach ($arrIts AS $v) {
				if (!empty($v)) {
					$objId = substr ($v, 0, 1);
					$objId2 = substr ($v, 1);
					switch ($objId) {
						case 'a':
							$q = $this->db->select ('type, title')->where ('id', $objId2)->limit (1)->get ($this->fileTab)->result_array ();
							$link[] = $this->getFile ($q[0]['type'], $objId2, $q[0]['title']);
							break;
						case 'f':
							$link[] = $this->getIerch ($objId2);
							break;
						case 'c':
							$q = $this->db->where ('id', $objId2)->limit (1)->get ($this->catTab)->result_array ();
							$link[] = anchor (site_url (array ('t_menu', 't_menu', 'category', $objId2)), $q[0]['title']);
							break;
						default:
							break;
					}
				}
			}
			return $link;
		}

		// Всегда есть попытки сделать свой код более оптимизированным.
		// И постоянно работаешь над старыми ошибками, исправляя всё,
		// Делая своё приложение более быстрым и неглюченным,
		// И ведь получается,
		// Но тут же останавливаешься, понимая, какой же всё-таки твой код неоптимизированный :)
		function getFile ($item, $id, $name) {
			$result = false;
			
			switch ($item) {
				case 'anchor':
					$table = $this->ancTab;
					break;
				case 'article':
					$table = $this->artTab;
					break;
				case 'document':
					$table = $this->docTab;
					break;
				case 'news':
					$table = $this->nTab;
					break;
				case 'photo':
					$table = $this->pTab;
					break;
				case 'video':
					$table = $this->vTab;
					break;
				default:
					return false;
					break;
			}
			$q = $this->db->where ('file_id', $id)->get ($table)->result_array ();
			return anchor (site_url (array ($item, $item, 'view', $id)), $name);
		}

		function getIerch ($id) {
			list ($q) = $this->db->where ('id', $id)->limit (1)->get ($this->foldTab)->result_array ();
			$links = array ();
			$arrSorts = explode (';', $q['sort']);
			$html = anchor ('#', $q['title'], array ('onclick' => 'return obj.hideDiv (' . $id . ');')) . '<ul class="tree_more" id="ho' . $id . '" style="display: none;">';
			
			foreach ($arrSorts AS $v) {
				if (!empty ($v)) {
					$sortType = substr ($v, 0, 1);
					$sortId = substr ($v, 1);
					switch ($sortType) {
						case 'f':
							$html .= '<li>' . $this->getIerch ($sortId) . '</li>';
							break;
						case 'c':
							list ($q2) = $this->db->where ('id', $sortId)->limit (1)->get ($this->catTab)->result_array ();
							$html .= '<li>' . anchor (site_url (array ('t_menu', 't_menu', 'category', $sortId)), $q2['title']) . '</li>';
							break;
						case 'a':
							list ($q2) = $this->db->select ('type, title')->where ('id', $sortId)->limit (1)->get ($this->fileTab)->result_array ();
							$html .= '<li>' . $this->getFile ($q2['type'], $sortId, $q2['title']) . '</li>';
							break;
						default:
							break;
					}
				}
			}
			$html .= '</ul>';
			return $html;
		}
	}
?>
