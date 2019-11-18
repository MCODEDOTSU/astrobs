<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Tours extends Admin_Controller
{
    //var $root;

    function Tours()
    {
        parent::Admin_Controller();
        parent::access('tours');
        //$this->root = $this->folder->root;
    }

    function index()
    {
		$q = $this->db->get ('th_folder')->result_array ();
		//print_r ($q);
		$arr = array ();
		foreach ($q AS $k => $v) {
			$arr[$k]['title'] = $v['title'];
			$is_check = ($v['is_tour'] == 1) ? true : false;
			$arr[$k]['check'] = form_checkbox ('check[' . $v['id'] . ']', '1', $is_check);
			$arr[$k]['edit_desc'] = anchor (site_url (array ('admin', 'tours', 'tours', 'editdesc', $v['id'])), 'Изменить описание');
		}
		$data = array (
			'form_open'		 => form_open (site_url (array ('admin', 'tours', 'tours', 'save'))),
			'form_submit'	 => form_submit ('sub', 'Сохранить'),
			'form_close'	 => form_close (),
			'arr' => $arr
		);

        $this->module->parse('tours', 'tours.php', $data);
    }
	
	function editdesc () {
		$id = $this->uri->segment (5);
		$q = $this->db->where ('id', $id)->limit (1)->get ('th_folder')->result_array ();
		if ((int) $id == 0) redirect (site_url (array ('admin', 'tours', 'tours')));
		$data = array (
			'form_open'		 => form_open (site_url (array ('admin', 'tours', 'tours', 'save_desc'))),
			'form_submit'	 => form_submit ('sub', 'Сохранить'),
			'form_close'	 => form_close (),
			'name'			 => $q[0]['title'],
			'desc'			 => form_ckeditor('desc', $q[0]['desc_tour']),
			'id'			 => form_hidden ('id', $id)
		);
		$this->module->parse('tours', 'form.php', $data);
	}
	
	function save () {
		if ($this->input->post ('sub') === false) redirect (site_url (array ('admin', 'tours', 'tours')));
		$this->db->update ('th_folder', array ('is_tour' => '0'));
		
		if ($this->input->post ('check') !== false) {
			foreach ($this->input->post ('check') AS $k => $v) {
				$this->db->where ('id', $k)->update ('th_folder', array ('is_tour' => $v));
			}
		}
		redirect (site_url (array ('admin', 'tours', 'tours')));
	}
	
	function save_desc () {
		if ($this->input->post ('sub') === false) redirect (site_url (array ('admin', 'tours', 'tours')));
		$this->db->where ('id', $this->input->post ('id'))->update ('th_folder', array ('desc_tour' => $this->input->post ('desc')));
		redirect (site_url (array ('admin', 'tours', 'tours')));
	}
}
?>
