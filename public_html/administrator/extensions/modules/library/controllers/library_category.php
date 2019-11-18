<?php
class Library_category extends Admin_Controller
{
    function Library_category()
    {
        parent::Admin_Controller();
        parent::access('library');
    }

    function index()
    {
        $this->display->_content('<div class="cms_title">'._icon('book').'Тематика библиотеки</div>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/library/library_category/create', _icon('book_add').'Добавить тематику', 'class="cms_btn" rel="ajaxDialog"').'</div>');

        $Groups = $this->library_category_model->get();
		 
        foreach ($Groups as $group) {
        
            $data['library'][] = array(
                'id'        => $group['id'],
                'name'      => $group['title'],
                'actions'       => anchor('admin/library/library_category/form/'.$group['id'], _icon('book_edit'), 'title="Редактировать" rel="ajaxDialog"').
                                   anchor('admin/library/library_category/delete/'.$group['id'], _icon('book_delete'), 'title="Удалить" onclick="return confirm(\'Все книги этой тематике будут удалены!\')"')            );
        }

        $this->module->parse('library', 'category.php', $data);
    }

    function form()
    {

        $group_id = $this->uri->segment(5); if (!is_numeric($group_id)) die;
        $this->display->_content('<div class="cms_title">'._icon('book_edit').'Изменение тематики</div>');
        $group = $this->library_category_model->extra(array('id' => $group_id)); if(count($group) == 0) die;
        $group = $group[0]; if(count($group) == 0) die;


        $this->module->parse('library', 'category_form.php', array(
            'name' => form_input('name', $group['title']),
            'submit' => form_submit('frmSubmit','Сохранить'),
            'form_open' => form_open('admin/library/library_category/update/'.$group_id),
            'form_close' => form_close()
        ));

    }

    function create()
    {
        $this->display->_content('<div class="cms_title">'._icon('book_add').'Добавить тематику</div>');
 
        $data = array(
            'submit' => form_submit('frmSubmit','Сохранить'),
            'form_open' => form_open('admin/library/library_category/insert'),
            'form_close' => form_close(),
            'name' => form_input('name', '')
        );

        $this->module->parse('library', 'category_form.php', $data);
    }

    function update()
    {
        $group_id = $this->uri->segment(5); if(!is_numeric($group_id)) return false;
        $name = $this->input->post('name');  if(strlen($name) == 0) return false;

	    $this->library_category_model->update(array(
	    		'title' => $name
    		), array(
	        'id' => $group_id
	    ));
		    
        redirect('admin/library/library_category');
    }

    function insert()
    {

        $name = $this->input->post('name');
        if(strlen($name) == 0) return false;
     
        $this->library_category_model->create(array(
            'title' => $name
        ));

        redirect('admin/library/library_category');
    }

    function delete()
    {
        $group_id = $this->uri->segment(5);
        
        if(!is_numeric($group_id)) return false;

        $this->library_category_model->delete(array('id' => $group_id));

		$this->db->query('DELETE FROM th_library WHERE category = ' . $group_id);

        redirect('admin/library/library_category');
    }
}
?>
