<?php

class Tpl_block_right_model extends Model
{
    function Tpl_block_right_model()
    {
        parent::Model();
    }

    function block()
    {
        $Block = $this->db->where('side', $this->_getHM ())->get('th_tpl_block')->result_array();
        return '<div class="block"><div class="block_title">' . $Block[0]['title'] . '</div><div class="block_content">' . $Block[0]['body'] . '</div></div>';
    }

    function _getHM () {
		$lang =  ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
		switch ($lang) {
			case 'russian':
				$result = 'right';
				break;
			case 'english':
				$result = 'right_eng';
				break;
			case 'kazakh':
				$result = 'right_kz';
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
