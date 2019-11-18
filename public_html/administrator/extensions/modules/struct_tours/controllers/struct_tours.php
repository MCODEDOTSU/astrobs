<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class struct_tours extends Admin_Controller 
{
    function struct_tours()
    {
        parent::Admin_Controller();
        parent::access('struct_tours');

    }
    
    function index()
    {

        $this->display->_content('<div class="cms_title">'._icon('chart_curve_add').'Виды туров</div>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/struct_tours/struct_tours/form', _icon('chart_curve_add').'Добавить вид тура', 'class="cms_btn" rel="facebox"').'</div>');
        
        $Tours = $this->tours_model->get();
        
        if(count($Tours) == 0) return FALSE;
        
        foreach($Tours as $tour)
        {
            $data['tours'][] = array(
                'id'        =>	$tour['id'],
                'title'     =>	$tour['name'],
                'actions'   =>	//anchor('admin/struct_tours/orders/table/'.$tour['id'], _icon('chart_curve_go'), 'title="Просмотр заказов по категории"').
                				//anchor('admin/struct_tours/cat_tours/table/'.$tour['id'], _icon('chart_curve_go'), 'title="Просмотр туров"').
                				/*anchor('admin/struct_tours/struct_tours/form_block_rus/'.$tour['id'], '<img align="absmiddle" src="/templates/zesar/image/language_ru.gif">', 'title="Ответственное лицо для русской версии"').
                				anchor('admin/struct_tours/struct_tours/form_block_eng/'.$tour['id'], '<img align="absmiddle" src="/templates/zesar/image/language_en.gif">', 'title="Ответственное лицо для английской версии"').*/
								anchor('admin/struct_tours/struct_tours/form/'.$tour['id'], _icon('chart_curve_go'), 'title="Изменить"').
								anchor('admin/struct_tours/struct_tours/remove/'.$tour['id'], _icon('chart_curve_delete'), 'title="Удалить"')
            );
        }
		//$data['url'] = anchor (site_url (array ('admin', 'struct_tours', 'struct_tours')), 'Список туров');
        
        $this->_show('get_tours.php', $data);
    }
    
    function form()
    {
        $tourId = $this->uri->segment(5);
        
        $Tour = $this->tours_model->extra(array('id'=>$tourId));
        if(count($Tour)) $Tour = $Tour[0];
        
        $data = array(
            'tour'			=> form_hidden('tour', @$Tour['id']),
            'title'			=> form_input('title', @$Tour['name']),
            'title_eng'		=> form_input('title_eng', @$Tour['name_eng']),
			//'desc'			=> form_ckeditor('desc', @$Tour['desc']),
			//'lang'			=> form_dropdown ('lang', $this->tours_model->getLang (), @$Tour['lang_id']),
            'form_open'		=> form_open('admin/struct_tours/struct_tours/save'),
            'form_close'	=> form_close(),
            'submit'		=> form_submit('frmSubmit', 'Сохранить'),
            'email'			=> form_input ('email', @$Tour['email'])
        );
        
        $this->_show('form_tour.php', $data);
    }
    
    function _show($view = '', $data = array(), $return = FALSE)
    {
        return $this->module->parse('struct_tours', $view, $data, $return);
    }

    function form_block_rus () {
		$tourId = $this->uri->segment(5);
		$Tour = $this->tours_model->extra(array('id'=>$tourId));
		if(count($Tour)) $Tour = $Tour[0];
		$this->display->_content('<div class="cms_title">'._icon('chart_curve_add').'Ответственное лицо для русской версии тура "' . $Tour['name'] . '" </div>');
		$data = array (
			'form_open'		=> form_open('admin/struct_tours/struct_tours/save_block'),
            'form_close'	=> form_close(),
            'form_text'		=> form_ckeditor ('text', $Tour['face_rus']),
            'form_submit'	=> form_submit ('frmSubmit', 'Сохранить'),
            'form_id'		=> form_hidden ('id', $this->uri->segment (5)),
            'form_type'		=> form_hidden ('type', 'rus')
		);

		$this->_show ('form_face.php', $data);
    }

    function form_block_eng () {
		$tourId = $this->uri->segment(5);
		$Tour = $this->tours_model->extra(array('id'=>$tourId));
		if(count($Tour)) $Tour = $Tour[0];
		$this->display->_content('<div class="cms_title">'._icon('chart_curve_add').'Ответственное лицо для английской версии тура "' . $Tour['name'] . '" </div>');
		$data = array (
			'form_open'		=> form_open('admin/struct_tours/struct_tours/save_block'),
            'form_close'	=> form_close(),
            'form_text'		=> form_ckeditor ('text', $Tour['face_eng']),
            'form_submit'	=> form_submit ('frmSubmit', 'Сохранить'),
            'form_id'		=> form_hidden ('id', $this->uri->segment (5)),
            'form_type'		=> form_hidden ('type', 'eng')
		);

		$this->_show ('form_face.php', $data);
    }

    function save_block () {
    	$type = $this->input->post ('type');
    	$id = $this->input->post ('id');
    	$text = $this->input->post ('text');
    	$field = '';
    	if ($type == 'rus') {
			$field = 'face_rus';
    	}
    	else if ($type == 'eng') {
			$field = 'face_eng';
    	}
    	else {
			return false;
    	}
		$this->tours_model->update (array ($field => $text), array ('id' => $id));
		redirect('admin/struct_tours/struct_tours');
    }
    
    function save()
    {
        $title = $this->input->post('title');
        $title_eng = $this->input->post('title_eng');
        $tour = $this->input->post('tour');
        $email = $this->input->post('email');
        //$lang = $this->input->post ('lang');
        //echo (int) $tour;
        if(isset($tour) && (int) $tour == 0){
            $tourId = $this->tours_model->create(array(
                'name' 		=> $title,
                'name_eng' 	=> $title_eng,
                'email'		=> $email,
                //'lang_id'	=> $lang
            ));
        } else if (isset ($tour) && (int) $tour > 0) {
            $this->tours_model->update(array(
                'name' 		=> $title,
                'name_eng' 	=> $title_eng,
                'email'		=> $email,
                //'lang_id'	=> $lang
            ), array('id'=> $tour));
            $tourId = $tour;
        }
        
        redirect('admin/struct_tours/struct_tours');
        
    }

    function remove()
    {
        $tourId = $this->uri->segment(5);
        if(!is_numeric($tourId)) return FALSE;
		$delCats = $this->cats_model->get (array ('tid'=> $tourId));
		foreach ($delCats AS $v) {
			$this->objs_model->delete (array ('cid' => $v['id']));
			$this->orders_model->delete (array ('tid' => $v['id']));
		}
        $this->cats_model->delete(array('tid'=> $tourId));
        $this->tours_model->delete(array('id'=>$tourId));
		
        redirect('admin/struct_tours/struct_tours');
    }
    
     
}

?>
