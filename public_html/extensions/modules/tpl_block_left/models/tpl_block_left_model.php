<?php

class Tpl_block_left_model extends Model
{
    function Tpl_block_left_model()
    {
        parent::Model();
    }

    function block()
    {
        $Block = $this->db->where('side', $this->_getHM ())->get('th_tpl_block')->result_array();
        return '<div class="block block-left"><div class="block-title">' . $Block[0]['title'] .'</div><div class="block-content">'. $Block[0]['body'] . '</div></div>';
    }

    function _getHM () {
		$lang =  ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
		switch ($lang) {
			case 'russian':
				$result = 'left';
				break;
			case 'english':
				$result = 'left_eng';
				break;
			case 'kazakh':
				$result = 'left_kz';
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
