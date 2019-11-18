<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Place extends Admin_Controller
{
    var $root;

    function Place()
    {
        parent::Admin_Controller();
        parent::access('place');
        $this->root = $this->folder->root;
    }

    function index()
    { 

        $this->display->_content('<div class="cms_title">'._icon('chart_organisation').'Редактор структуры</div>');
        setcookie("place[f]", $this->authorization->group['folder'], time() + 3600);

        $langId = $this->_getLang ($this->input->post ('fid'));

		$Tree = $this->place_model->getTreeAsHTML(array (), $langId);

		//print_r ($Tree);
		$data = array ( 
			'tree'		=>	$Tree,
			'fo'		=>	form_open (site_url (array ('admin', 'place', 'place')), array ('name' => 'formLang', 'id' => 'formLang', 'style' => 'float: left; display: inline; margin: 10px 10px 0 0;')),
			'fc'		=>	form_close (),
			'fd'		=>	form_dropdown ('fid', $this->place_model->getLangs (), $langId, 'onchange="this.form.submit();"'),
			'addItem'	=>	anchor('#', _icon('add').'Добавить элемент в структуру', 'class="cms_btn" id="placeFormBtn"')
		);

        $this->module->parse('place', 'place.php', $data);
    } 

	// Текущий язык
    function _getLang ($lid = 0) {
		if ($lid) {
			$this->session->set_userdata (array ('fid' => $this->input->post ('fid')));
			$langId = $lid;
		}
		else if ($this->session->userdata ('fid')) {
			$langId = $this->session->userdata ('fid');
		}
		else {
			$langId = $this->place_model->defaultLang ();
		}

		
        return $langId;
    }

    function getTree () {
		$langId = $this->_getLang (1);
		echo $this->place_model->getTreeAsHTML(array (), $langId);
		die;
    }
}
?>
