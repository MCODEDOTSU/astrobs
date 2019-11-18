<?php
class Library extends Admin_Controller
{
    function Library()
    {
        parent::Admin_Controller();
        parent::access('library');
    }

    function index()
    {
        $this->display->_content('<div class="cms_title">'._icon('book').'Библиотека</div>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/library/library/create', _icon('book_add').'Добавить книгу', 'class="cms_btn" rel="ajaxDialog"').'</div>');

        $Groups = $this->library_model->get();

		$Category = $this->db->get('th_library_category')->result_array();
		 
        foreach ($Groups as $group) {
        
			foreach ($Category as $category) {
				if ($group['category'] == $category['id']) $categ = $category['title'];
			}

            $data['library'][] = array(
                'id'        => $group['id'],
                'name'      => $group['name'],
                'author'	=> $group['author'],
                'year'		=> $group['year'],
                'category'  => $categ,
                'size'		=> $group['size'],
                'create'	=> $group['create'],
                'update'	=> $group['update'],
                'actions'       => anchor('admin/library/library/form/'.$group['id'], _icon('book_edit'), 'title="Редактировать" rel="ajaxDialog"').
                                   anchor('admin/library/library/delete/'.$group['id'], _icon('book_delete'), 'title="Удалить"')
            );
        }

        $this->module->parse('library', 'index.php', $data);
    }

    function form()
    {

        $group_id = $this->uri->segment(5); if (!is_numeric($group_id)) die;
        $this->display->_content('<div class="cms_title">'._icon('book_edit').'Изменение книги</div>');
        $group = $this->library_model->extra(array('id' => $group_id)); if(count($group) == 0) die;
        $group = $group[0]; if(count($group) == 0) die;

		$Category = $this->db->get('th_library_category')->result_array();

		foreach ($Category as $category) {
				$options[$category['id']] = $category['title'];
		}

        $this->module->parse('library', 'form.php', array(
            'name' => form_input('name', $group['name']),
            'author' => form_input('author', $group['author']),
            'year' => form_input('year', $group['year']),
            'category' => form_dropdown('category', $options, $group['category']),
            'description' => form_textarea('description', $group['description']),
            'document' => form_upload('document'),
            'submit' => form_submit('frmSubmit','Сохранить'),
            'form_open' => form_open_multipart('admin/library/library/update/'.$group_id),
            'form_close' => form_close()
        ));

    }

    function create()
    {
        $this->display->_content('<div class="cms_title">'._icon('book_add').'Добавить книгу</div>');

		$Category = $this->db->get('th_library_category')->result_array();

		foreach ($Category as $category) {
				$options[$category['id']] = $category['title'];
		}
		 
        $data = array(
            'submit' => form_submit('frmSubmit','Сохранить'),
            'form_open' => form_open_multipart('admin/library/library/insert'),
            'form_close' => form_close(),
            'name' => form_input('name', ''),
            'author' => form_input('author', ''),
            'year' => form_input('year', ''),
            'category' => form_dropdown('category', $options, ''),
            'description' => form_textarea('description', ''),
            'document' => form_upload('document')
        );

        $this->module->parse('library', 'form.php', $data);
    }

    function update()
    {
        $group_id = $this->uri->segment(5); if(!is_numeric($group_id)) return false;
        $name = $this->input->post('name');  if(strlen($name) == 0) return false;
        $author = $this->input->post('author');
        $year = $this->input->post('year'); 
		$category = $this->input->post('category');
        $description = $this->input->post('description');

        if(!empty($_FILES['document']['name'])){

			$upload_data = $this->library_model->do_upload('document');

			if(is_string($upload_data)){
		        $this->display->_content($upload_data);
		        return FALSE;             
		    }
		    $this->library_model->update(array(
		    		'name' => $name,
		    		'author' => $author,
		    		'year' => $year,
		    		'category' => $category,
		    		'description' => $description,
					'document' => $upload_data['file_name'],
					'size' => $upload_data['file_size'],
					'update' => date('Y-m-d')
		    		), array(
		        'id' => $group_id
		    ));
		    
		} else {
		
		    $this->library_model->update(array(
		    		'name' => $name,
		    		'author' => $author,
		    		'year' => $year,
		    		'category' => $category,
		    		'description' => $description,
		    		'update' => date('Y-m-d')
		    		), array(
		        'id' => $group_id
		    ));
		    
		}


        redirect('admin/library/library');
    }

    function insert()
    {

        $name = $this->input->post('name');
        if(strlen($name) == 0) return false;
        
        $author = $this->input->post('author');
        
        $year = $this->input->post('year');
        
        $category = $this->input->post('category');
        
        $description = $this->input->post('description');

		$upload_data = $this->library_model->do_upload('document');

		if(is_string($upload_data)){
            $this->display->_content($upload_data);
            return FALSE;             
        }
     
        $this->library_model->create(array(
            'name' => $name,
            'author' => $author,
            'year' => $year,
            'category' => $category,
            'description' => $description,
            'document' => $upload_data['file_name'],
            'size' => $upload_data['file_size'],
            'create' => date('Y-m-d')
        ));

        redirect('admin/library/library');
    }

    function delete()
    {
        $group_id = $this->uri->segment(5);
        
        if(!is_numeric($group_id)) return false;

        $this->library_model->delete(array('id' => $group_id));

        redirect('admin/library/library');
    }
}
?>
