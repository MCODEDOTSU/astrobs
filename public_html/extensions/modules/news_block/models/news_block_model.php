<?php
	class News_block_model extends Model {

		function News_block_model() {
			parent::Model();
			$this->TABLE = 'th_news';

		}

		function extra($where = array()) {
			if(count($where) == 0) return false;

			return $this->db->where($where)->get($this->TABLE)->result_array();
		}

		function get($limit = 20, $lid = 0) {
			if ((int) $lid > 0) {
				$this->db->where ('lang_id', $lid);
				$this->db->order_by("id", "desc");
				$this->db->limit($limit);
			}
			return $this->db->get($this->TABLE)->result_array();
		}

		function block()
		{
		
		    return '
		        <div id="news_block" class="block">
		            <div class="block_title">' . $this->_getHM () . '</div>
		            '.$this->news_block_model->getBlock().'
		        </div>
		    ';
		}

		function _getHM () {
			$lang =  ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
			switch ($lang) {
				case 'russian':
					$result = 'Новости';
					break;
				case 'english':
					$result = 'News';
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

		

		// ART editing function...
		function getBlock()
		{
		    $html = '';
		    
		    $lang =  ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
			$lang_id = $this->db->select ('id')->where ('language', $lang)->get ('th_language_structure')->result_array ();
			
			
		    $arrNews = $this->get(5, $lang_id[0]['id']);
		    //print_r ($arrNews);

		    foreach($arrNews as $news)
		    {
		        if(strlen($news['title']) == 0) continue;
		        
		        if(strlen($news['desc']) == 0) continue;

				if ($news['img'] != '') {
					$img = str_replace (".", "_150x150.", $news['img']);
					$img = '<center><img src="/extensions/image.php?src=../news_imgs/' . $img . '" border="0"></center>';
				} else {
					$img = '';
				}

		        $html .= $this->module->parse('news_block', 'block', array(
		        	'img'   => $img,
		            'title'     => $news['title'],
		            'desc'      => $news['desc'],
		            'button'    => anchor('news/news/view/'.$news['file_id'], 'Полный текст', 'class="next"')
		        ), true);
		    }

		    return $html;
		}

	}
?>
