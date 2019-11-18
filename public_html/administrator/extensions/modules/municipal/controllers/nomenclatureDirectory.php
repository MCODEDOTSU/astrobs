<?php
class nomenclatureDirectory extends Admin_Controller
{
    function nomenclatureDirectory()
    {
        parent::Admin_Controller();
        parent::access('municipal');
    }
    
    function index()
    {
        $this->display->_content('<h2>Справочник номенклатуры</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/municipal/nomenclatureDirectory/insert', _icon('add').'Добавить раздел', 'class="cms_btn" rel="ajaxDialog"').'</div>');
        
        $Sections = $this->nomenclatureDirectory_model->get();
        
        if(count($Sections) == 0) return FALSE;
        
        foreach($Sections as $section){
            
            $sections_name[$section['id']] = $section['title'];
            
            $data['sections'][] = array(
                'number' => ($section['number'])?$section['number']:'',
                'title' => $section['title'],
                'parent_section' => @$sections_name[$section['parent_id']],
                'actions' => anchor('admin/municipal/nomenclatureDirectory/form/'.$section['id'], _icon('pencil'), 'rel="ajaxDialog" title="Редактировать"').
                             anchor('admin/municipal/nomenclatureDirectory/delete/'.$section['id'], _icon('delete'), 'title="Удалить"')
            );
        }
        
        $this->module->parse('municipal', 'nomenclatureDirectory/sections.php', $data);
    }
    
    function insert()
    {
        $Sections = $this->nomenclatureDirectory_model->get();
        
        if(count($Sections) == 0) die;
        
        foreach($Sections as $section){
            $sections_dropdown[ $section['id'] ] = $section['number'].'&nbsp;'.$section['title'];
        }
        
        $data = array(
            'form_open' => form_open('admin/municipal/nomenclatureDirectory/save'),
            'form_close' => form_close(),
            'submit' => form_submit('frmSubmit', 'Добавить'),
            
            'parent_section' => form_dropdown('parent_section', $sections_dropdown),
            'number' => form_input('number'),
            'title' => form_input('title')
        );
        
        echo '<title>Добавленипе нового раздела</title>';
        echo $this->module->parse('municipal', 'nomenclatureDirectory/form.php', $data, TRUE);
        die;          
    }
    
    function save()
    {
        $parent_section = $this->input->post('parent_section'); if(!is_numeric($parent_section)) return FALSE;
        $number = $this->input->post('number'); if(!is_numeric($number)) $number = 0;
        $title = $this->input->post('title'); if(!is_string($title)) return FALSE;
        
        $this->nomenclatureDirectory_model->create(array('parent_id' => $parent_section, 'number' => $number, 'title' => $title));
        
        redirect('admin/municipal/nomenclatureDirectory');
    }
    
    function form()
    {
        $sectionId = $this->uri->segment(5); if(!is_numeric($sectionId)) return FALSE;
        
        $Section = $this->nomenclatureDirectory_model->extra(array('id' => $sectionId));
        
        $allSections = $this->nomenclatureDirectory_model->get();
        if(count($allSections) == 0) die;
        
        foreach($allSections as $section){
            $sections_dropdown[ $section['id'] ] = $section['number'].'&nbsp;'.$section['title'];
        }
        
        $data = array(
            'form_open' => form_open('admin/municipal/nomenclatureDirectory/update/'.$Section[0]['id']),
            'form_close' => form_close(),
            'submit' => form_submit('frmSubmit', 'Добавить'),
            
            'parent_section' => form_dropdown('parent_section', $sections_dropdown, $Section[0]['parent_id']),
            'number' => form_input('number', ($Section[0]['number'])?$Section[0]['number']:''),
            'title' => form_input('title', $Section[0]['title'])
        );
        
        echo '<title>Редактирование раздела</title>';
        echo $this->module->parse('municipal', 'nomenclatureDirectory/form.php', $data, TRUE);
        die;    
    }
    
    function update()
    {
        $sectionId = $this->uri->segment(5); if(!is_numeric($sectionId)) return FALSE;
        $parent_section = $this->input->post('parent_section'); if(!is_numeric($parent_section)) return FALSE;
        $number = $this->input->post('number'); if(!is_numeric($number)) $number = 0;
        $title = $this->input->post('title'); if(!is_string($title)) return FALSE;
        
        $this->nomenclatureDirectory_model->update(array('parent_id' => $parent_section, 'number' => $number, 'title' => $title), array('id' => $sectionId));
        
        redirect('admin/municipal/nomenclatureDirectory');    
    }
    
    function delete()
    {
        $sectionId = $this->uri->segment(5); if(!is_numeric($sectionId)) return FALSE;
        $this->nomenclatureDirectory_model->delete(array('id'=> $sectionId));
        redirect('admin/municipal/nomenclatureDirectory');    
    }
}
?>