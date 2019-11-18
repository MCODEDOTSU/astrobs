<?php

class Rubric extends Admin_Controller
{
    var $moduleName = 'rubric';
    var $tables = array();

    function Rubric()
    {
        parent::Admin_Controller();
        parent::access('rubric');

        $this->tables = $this->module->config($this->moduleName, 'tables');
    }

    function index()
    {
		$this->module->parse($this->moduleName, 'index.php', array(
            '1' => '',
        ));
    }
	
	function descrub () {
		$id = $this->uri->segment (5);
		if ((int) $id == 0) redirect (site_url (array ('admin', 'rubric', 'rubric')));
		$q = $this->db->where ('id', $id)->limit (1)->get ('th_category')->result_array ();
		$data = array (
			'form_open'     => form_open(site_url (array ('admin', 'rubric', 'rubric', 'addesc', $id))),
            'body'          => form_ckeditor('body', $q[0]['cat_desc']),
			'form_submit'	=> form_submit ('sub', 'Сохранить'),
            'form_close'    => form_close(),
			'name' => $q[0]['title']
		);
		
		
		//$data['name'] = ;
		//print_r ($id);
		$this->module->parse($this->moduleName, 'desc.php', $data);
	}
	
	function addesc () {
		$id = $this->uri->segment (5);
		//print_r ($_POST);
		if ((int) $id == 0 || count ($_POST) == 0) redirect (site_url (array ('admin', 'rubric', 'rubric')));
		$this->db->where ('id', $id)->update ('th_category', array (
			'cat_desc'	=> $this->input->post ('body'),
			'if_desc'	=> '1'
		));
		redirect (site_url (array ('admin', 'rubric', 'rubric')));
	}
}
?>
