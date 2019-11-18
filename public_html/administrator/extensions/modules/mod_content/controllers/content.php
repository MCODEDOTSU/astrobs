<?php
class Content extends Admin_Controller
{
    var $moduleName = 'mod_content';
    var $controllerName = 'content';
    var $moduleTitles = '';
    
    function Content()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
        $this->moduleTitles = $this->module->config($this->moduleName, 'title');
    }
    
    function index()
    {
        $this->display->_content('<div class="cms_title">' . $this->moduleTitles . '</div>');
        
        $Files = $this->content_model->get();
        
        if(count($Files) == 0) {
            return FALSE;
        }
        
        foreach($Files as $file){
            
            if($file['category_id'] > 0){
                $extraFolder = $this->category->getExtraCategory($file['category_id']);
                @$extraFolder = $extraFolder[0];
            }
            
            if($file['folder_id'] > 0){
                $extraFolder = $this->folder->_getExtraById($file['folder_id']); 
            }
            
            $data['file'][] = array(
                'id'        => $file['id'],
                'title'     => '<div class="mod_content_item">'._module_icon($file['type']).'<b>'.$file['title'].'</b><span>'.$extraFolder['title'].'</span></div>',
                'type'      => $this->module->config($file['type'], 'title'),
                'created'   => date('H:i d.m.y',  strtotime($file['created'])),
                'actions'   => anchor('admin/'.$file['type'].'/'.$file['type'].'/file/'.$file['id'], _icon('pencil'), 'title="Редактировать"')
            );
        }
        
        $this->module->parse($this->moduleName, 'index.php', $data);
        
    }
}
?>
