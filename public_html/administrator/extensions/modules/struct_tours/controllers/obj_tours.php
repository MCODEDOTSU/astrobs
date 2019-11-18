<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class obj_tours extends Admin_Controller 
{
    function obj_tours()
    {
        parent::Admin_Controller();
        parent::access('struct_tours');

    }
    
    function table()
    {
		$cid = (int) $this->uri->segment (5);
		$tName = $this->cats_model->get (array ('id' => $cid));
		$fpage = anchor (site_url (array ('admin', 'struct_tours', 'struct_tours')), 'Виды туров');
		$spage = anchor (site_url (array ('admin', 'struct_tours', 'cat_tours', 'table', $tName[0]['tid'])), $tName[0]['name']);
		$this->display->_content($fpage . ' / ' . $spage);
        $this->display->_content('<h2>'._icon('chart_curve_add').'Объекты туров</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/struct_tours/obj_tours/form', _icon('chart_curve_add').'Добавить объект', 'class="cms_btn" rel="facebox"').'</div>');
        
		if ($cid == 0) redirect (site_url (array ('admin', 'struct_tours', 'struct_tours')));
		
        $Objs = $this->objs_model->get(array ('cid' => $cid));
        
        if(count($Objs) == 0) return FALSE;
        
        foreach($Objs as $obj)
        {
            $data['tours'][] = array(
                'id'        =>	$obj['id'],
                'title'     =>	$obj['name'],
                'actions'   =>	//anchor('admin/poll/answer/index/'.$obj['id'], _icon('chart_curve_go'), 'title="Просмотр"').
								anchor('admin/struct_tours/obj_tours/form/'.$obj['id'], _icon('chart_curve_go'), 'title="Изменить"').
								anchor('admin/struct_tours/obj_tours/remove/'.$obj['id'], _icon('chart_curve_delete'), 'title="Удалить"')
            );
        }
        
		$data['tname'] = $tName[0]['name'];
        $this->_show('get_objs.php', $data);
    }
    
    function form()
    {
        $objId = $this->uri->segment(5);

		$url = str_replace (base_url (), '', $this->input->server ('HTTP_REFERER'));
		$segs = explode ('/', $url);
        $Obj = $this->objs_model->extra(array('id'=>$objId));
		$cats = $this->cats_model->get ();
		//print_r ($cats);
		foreach ($cats AS $v) {
			$arrCats[$v['id']] = $v['name'];
		}
        if(count($Obj)) $Obj = $Obj[0];
        
        $data = array(
            'obj'			=> form_hidden('obj', @$Obj['id']),
			'selcat'		=> form_dropdown ('selcat', $arrCats, @$segs[4]),
			'cat'			=> form_hidden('cat', @$segs[4]),
            'title'			=> form_input('title', @$Obj['name']),
			'desc'			=> form_ckeditor('desc', @$Obj['desc']),
            'form_open'		=> form_open('admin/struct_tours/obj_tours/save'),
            'form_close'	=> form_close(),
            'submit'		=> form_submit('frmSubmit', 'Сохранить')
        );
        
        $this->_show('form_objs.php', $data);
    }
    
    function _show($view = '', $data = array(), $return = FALSE)
    {
        return $this->module->parse('struct_tours', $view, $data, $return);
    }
    
    function save()
    {
		//print_r ($_POST);
        $title = $this->input->post('title');
        $desc = $this->input->post('desc');
		$cid = $this->input->post ('selcat');
        $obj = $this->input->post('obj');
        $cat = $this->input->post ('cat');
        if(isset($obj) && (int) $obj == 0){
			//print_r ($_POST);
            $objId = $this->objs_model->create(array(
                'name' => $title,
                'desc' => $desc,
				'cid'  => $cid
            ));
        } else if (isset ($obj) && (int) $obj > 0) {
            $this->objs_model->update(array(
                'name' => $title,
                'desc' => $desc,
				'cid'  => $cid
            ), array('id'=> $obj));
            $objId = $obj;
        }
        
        redirect('admin/struct_tours/obj_tours/table/'.$cat);
        
    }

    function remove()
    {
        $tourId = $this->uri->segment(5);
        if(!is_numeric($tourId)) return FALSE;
		$url = str_replace (base_url (), '', $this->input->server ('HTTP_REFERER'));
		$segs = explode ('/', $url);
        //$this->answer_model->delete(array('poll_id'=> $pollId));
        $this->objs_model->delete(array('id'=>$tourId));
        redirect('admin/struct_tours/obj_tours/table/'.$segs[4]);
    }
    
     
}

?>
