<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Access extends Admin_Controller
{
    function Access()
    {
        parent::Admin_Controller();
        parent::access('access');
    }

    function index()
    {
        $this->display->_content('<div class="cms_title">'._icon('shield').'Управление доступом</div>');

        $Modules = $this->access_model->modules();

        foreach($Modules as $module_name => $module_title)
        {
            $data['modules'][] = array(
                'title' => $module_title,
                'actions' => anchor('admin/access/access/module/'.$module_name, _icon('shield_go'), 'title="Доступ" rel="ajaxDialog"')
            );
        }

        $this->module->parse('access', 'index.php', $data);
    }

    function module()
    {
        $this->display->_content('<div class="cms_title">Редактирование прав для группы</div>');
        $module = $this->uri->segment(5);  if(!is_string($module) || strlen($module) == 0) die;
        $Groups = $this->access_model->groups();
        $Access = $this->access_model->get();
        $data = array(
            'form_open' => form_open('admin/access/access/save/'.$module),
            'form_close' => form_close(),
            'submit' => form_submit('frmSubmit', 'Сохранить')
        );

        foreach($Groups as $group){
            $Access = $this->access_model->extra(array(
                'module' => $module,
                'group_id' => $group['id']
            ));

            $data['groups'][] = array(
                'name' => $group['name'],
                'checkbox' => form_checkbox('group_id[]', $group['id'], ((isset($Access[0]['id']))? TRUE:FALSE))
            );
        }

        $this->module->parse('access', 'groups.php', $data);
    }

    function save()
    {
        $module = $this->uri->segment(5);
        if(!is_string($module) || strlen($module) == 0) return FALSE;

        $group_ids = $this->input->post('group_id');

        $this->access_model->delete(array('module'=>$module));

        if($group_ids){
            foreach($group_ids as $id){
                $this->access_model->create(array(
                    'module' => $module,
                    'group_id' => $id
                ));
            }
        }

        redirect('admin/access/access');
    }
}
?>
