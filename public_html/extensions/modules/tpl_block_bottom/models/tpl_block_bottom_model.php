<?php

class Tpl_block_bottom_model extends Model
{
    function Tpl_block_bottom_model()
    {
        parent::Model();
    }

    function block()
    {
        $Block = $this->db->where('side', $this->_getHM ())->get('th_tpl_block')->result_array();
        return $Block[0]['body'];
    }

    function _getHM () {
		$lang =  ($this->session->userdata ('language')) ? $this->session->userdata ('language') : $this->_getDefaultLang ();
		switch ($lang) {
			case 'russian':
				$result = 'bottom';
				break;
			case 'english':
				$result = 'bottom_eng';
				break;
			case 'kazakh':
				$result = 'bottom_kz';
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
