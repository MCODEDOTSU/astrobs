<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class User_groups extends Admin_Controller
{
    function User_groups()
    {
        parent::Admin_Controller();
        parent::access('user_groups');
    }

    function index()
    {
        $this->display->_content('<div class="cms_title">'._icon('group').'Группы пользователей</div>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/user_groups/user_groups/create', _icon('group').'Добавить группу', 'class="cms_btn" rel="ajaxDialog"').'</div>');

        $Groups = $this->user_groups_model->get();

        foreach($Groups as $group)
        {
            $data['user_groups'][] = array(
                'id'            => $group['id'],
                'name'          => $group['name'],
                'actions'       => anchor('admin/user_groups/user_groups/form/'.$group['id'], _icon('group_edit'), 'title="Редактировать" rel="ajaxDialog"').
                                   anchor('admin/user_groups/user_groups/delete/'.$group['id'], _icon('group_delete'), 'title="Удалить"')
            );
        }

        $this->module->parse('user_groups', 'index.php', $data);
    }

    function form()
    {
        
        $group_id = $this->uri->segment(5); if(!is_numeric($group_id)) die;  
        $this->display->_content('<div class="cms_title">'._icon('group').'Изменение группы</div>');
        $group = $this->user_groups_model->extra(array('id' => $group_id)); if(count($group) == 0) die;
        $group = $group[0]; if(count($group) == 0) die; 

        
        
        $this->module->parse('user_groups', 'form.php', array(
            'name' => form_input('name', $group['name']),
            'submit' => form_submit('frmSubmit','Сохранить'),
            'form_open' => form_open('admin/user_groups/user_groups/update/'.$group_id),
            'form_close' => form_close(),
            'folder' => $this->nested_sets_model->getTreeAsHTML(array('title'), $group['folder'])
        ));

    }

    function create()
    {
        $this->display->_content('<div class="cms_title">'._icon('group').'Добавление группы</div>');

        $data = array(
            'name' => form_input('name', ''),
            'submit' => form_submit('frmSubmit','Сохранить'),
            'form_open' => form_open('admin/user_groups/user_groups/insert', 'id="userGroupsForm"'),
            'form_close' => form_close(),
            'folder' => $this->nested_sets_model->getTreeAsHTML(array('title'))      
        );

        $this->module->parse('user_groups', 'form.php', $data);
    }

    function update()
    {
        $group_id = $this->uri->segment(5); if(!is_numeric($group_id)) return false;  
        $name = $this->input->post('name');  if(strlen($name) == 0) return false; 
        $folder = $this->input->post('folder');  if(strlen($folder) == 0) return false; 

        $this->user_groups_model->update(array('name' => $name, 'folder' => $folder), array(
            'id' => $group_id
        ));

        redirect('admin/user_groups/user_groups');
    }

    function insert()
    {
        $name = $this->input->post('name');
        if(strlen($name) == 0) return false;
        $folder = $this->input->post('folder');  if(strlen($folder) == 0) return false;                         
        
        $this->user_groups_model->create(array(
            'name' => $name,
            'folder' => $folder
        ));

        redirect('admin/user_groups/user_groups');
    }

    function delete()
    {
        $group_id = $this->uri->segment(5);
        
        if(!is_numeric($group_id)) return false;

        $this->user_groups_model->delete(array('id' => $group_id));

        redirect('admin/user_groups/user_groups');
    }
}
?>
