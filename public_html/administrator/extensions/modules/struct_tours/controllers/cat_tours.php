<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class cat_tours extends Admin_Controller 
{
    function cat_tours()
    {
        parent::Admin_Controller();
        parent::access('struct_tours');

    }
    
    function table()
    {
		$tid = (int) $this->uri->segment (5);
		$tName = $this->tours_model->get (array ('id' => $tid));
		$fpage = anchor (site_url (array ('admin', 'struct_tours', 'struct_tours')), 'Виды туров');
		//$spage = anchor (site_url (array ('admin', 'struct_tours', '_tours')), $tName['name']);
		$this->display->_content($fpage);
        $this->display->_content('<h2>'._icon('chart_curve_add').'Туры</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/struct_tours/cat_tours/form', _icon('chart_curve_add').'Добавить тур', 'class="cms_btn" rel="facebox"').'</div>');
        
		if ($tid == 0) redirect (site_url (array ('admin', 'struct_tours', 'struct_tours')));
		
        $Cats = $this->cats_model->get(array ('tid' => $tid));
        
        if(count($Cats) == 0) return FALSE;
        
        foreach($Cats as $cat)
        {
            $data['tours'][] = array(
                'id'        =>	$cat['id'],
                'title'     =>	$cat['name'],
                'actions'   =>	anchor('admin/struct_tours/obj_tours/table/'.$cat['id'], _icon('chart_curve_go'), 'title="Просмотр объектов"').
								anchor('admin/struct_tours/cat_tours/form/'.$cat['id'], _icon('chart_curve_go'), 'title="Изменить"').
								anchor('admin/struct_tours/cat_tours/remove/'.$cat['id'], _icon('chart_curve_delete'), 'title="Удалить"')
            );
        }
        
		$data['tname'] = $tName[0]['name'];
        $this->_show('get_cats.php', $data);
    }
    
    function form()
    {
        $catId = $this->uri->segment(5);

		$url = str_replace (base_url (), '', $this->input->server ('HTTP_REFERER'));
		$segs = explode ('/', $url);
        $Cat = $this->cats_model->extra(array('id'=>$catId));
		$tours = $this->tours_model->get ();
		foreach ($tours AS $v) {
			$arrTours[$v['id']] = $v['name'];
		}
        if(count($Cat)) $Cat = $Cat[0];
        
        $data = array(
            'cat'			=> form_hidden('cat', @$Cat['id']),
			'seltour'		=> form_dropdown ('seltour', $arrTours, @$segs[4]),
			'tour'			=> form_hidden('tour', @$segs[4]),
            'title'			=> form_input('title', @$Cat['name']),
			'desc'			=> form_ckeditor('desc', @$Cat['desc']),
            'form_open'		=> form_open('admin/struct_tours/cat_tours/save'),
            'form_close'	=> form_close(),
            'submit'		=> form_submit('frmSubmit', 'Сохранить')
        );
        
        $this->_show('form_cats.php', $data);
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
		$tid = $this->input->post ('seltour');
        $cat = $this->input->post('cat');
        $tour = $this->input->post ('tour');
        if(isset($cat) && (int) $cat == 0){
			//print_r ($_POST);
            $catId = $this->cats_model->create(array(
                'name' => $title,
                'desc' => $desc,
				'tid'  => $tid
            ));
        } else if (isset ($cat) && (int) $cat > 0) {
            $this->cats_model->update(array(
                'name' => $title,
                'desc' => $desc,
				'tid'  => $tid
            ), array('id'=> $cat));
            $catId = $cat;
        }
        
        redirect('admin/struct_tours/cat_tours/table/'.$tour);
        
    }

    function remove()
    {
        $tourId = $this->uri->segment(5);
        if(!is_numeric($tourId)) return FALSE;
		$url = str_replace (base_url (), '', $this->input->server ('HTTP_REFERER'));
		$segs = explode ('/', $url);
        $this->objs_model->delete(array('cid'=>$tourId));
        $this->cats_model->delete(array('id'=>$tourId));
        $this->orders_model->delete (array ('tid' => $tourId));
        redirect('admin/struct_tours/cat_tours/table/'.$segs[4]);
    }
    
     
}

?>
