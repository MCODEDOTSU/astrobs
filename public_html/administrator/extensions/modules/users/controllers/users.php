<?php
class Users extends Admin_Controller
{
    var $user;
    var $role;
    var $group;

    function Users()
    {
        parent::Admin_Controller();
        parent::access('users');
        $this->role = $this->users_model->_arrayReverse($this->users_model->role());
        $this->group = $this->users_model->_arrayReverse($this->users_model->group());
        $this->user = $this->users_model->_arrayReverse($this->users_model->users());
        
    }
    
    function index()
    {
        $this->display->_content('<div class="cms_title">'._icon('group').'Пользователи</div>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/users/users/form', _icon('user_add').'Добавить пользователя', 'class="cms_btn" rel="facebox"').'</div>');
        
        $users = $this->users_model->users();
        
        foreach($users as $user)
        {
            $data['users_entries'][] = array(
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'group'         => $this->group[$user->group_id]->name,
                'role'          => $this->role[$user->role_id]->name,
                'created'       => date('H:i d.m.y', strtotime($user->created)),
                'last_visit'    => date('H:i d.m.y', strtotime($user->last_visit)),
                'state'         => 1,
                'actions'       => anchor('admin/users/users/form/'.$user->id, _icon('user_edit'), 'title="Редактировать" rel="facebox"').anchor('admin/users/users/delete/'.$user->id, _icon('user_delete'), 'title="Удалить"')
            ); 
        }
        
        $this->module->parse('users', 'index.php', $data);
    }
    
    function form()
    {
        if($this->uri->segment(5)) 
        {
            $user = $this->user[$this->uri->segment(5)];
            
            $data = array(
                'name' => form_input('name', $user->name), 
                'email' => form_input('email', $user->email), 
                'group' => form_dropdown('group_id', $this->users_model->_arrayDropdown($this->group), $user->group_id), 
                'role' => form_dropdown('role_id', $this->users_model->_arrayDropdown($this->role), $user->role_id),
                'password' => form_password('password', 'qwerty'),
                'confpwd' => form_password('confpwd', ''), 
                'state' => 1,
                'submit' => form_submit('frmSubmit','Сохранить'),
                'form_open' => form_open('admin/users/users/update/'.$user->id),
                'form_close' => form_close() 
            );
        }
        else
        {
            $data = array(
                'name' => form_input('name', null), 
                'email' => form_input('email', null), 
                'group' => form_dropdown('group_id', $this->users_model->_arrayDropdown($this->group), null), 
                'role' => form_dropdown('role_id', $this->users_model->_arrayDropdown($this->role), null), 
                'password' => form_password('password', ''),
                'confpwd' => form_password('confpwd', ''),
                'state' => 1,
                'submit' => form_submit('frmSubmit','Сохранить'),
                'form_open' => form_open('admin/users/users/insert'),
                'form_close' => form_close()
            );
        } 
        
        $this->module->parse('users', 'form.php', $data);
    }
    
    function update()
    {
        if(!$this->uri->segment(5)) return false;
        
        $id = $this->uri->segment(5);
        
        if($this->input->post('password') != $this->input->post('confpwd')) return FALSE;
        
        $this->users_model->update($id, array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'group_id' => $this->input->post('group_id'),
            'role_id' => $this->input->post('role_id'),
            'state' => $this->input->post('state'),
            'password' => md5($this->input->post('password'))
        ));
        
        redirect('admin/users/users');
    }
    
    function insert()
    {
        $this->users_model->insert(array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'group_id' => $this->input->post('group_id'),
            'role_id' => $this->input->post('role_id'),
            'state' => $this->input->post('state'),
            'password' => md5($this->input->post('password'))
        ));
        
        redirect('admin/users/users');
    }
    
    function delete()
    {
        if(!$this->uri->segment(5)) return;
        $id = $this->uri->segment(5);
        
        $this->users_model->delete($id);
        
        redirect('admin/users/users');
    }
    
    
}   
?>
