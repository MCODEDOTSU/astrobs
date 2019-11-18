<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Content extends Admin_Controller
{
    function Content()
    {
        parent::Admin_Controller();
        parent::access('place');

    }
    
    function folder()
    {

//    unlink('.uploads/photo/'.$news[0]['file_name']); 
        $folderId = $this->input->post('id');
        $itemType = $this->input->post('item');
        if($itemType != 'f') die;

        if( !is_numeric($folderId) ) {
            
            $folderId = $this->authorization->group['folder'];
        }

        setcookie("place[f]", $folderId, time() + 3600, '/');

        // =====================================================================
        echo $this->module->parse('place', 'form.php', array(
            'create_to'     => form_hidden('create_to', 'folder'),
            'form_open'     => form_open(),
            'form_close'    => form_close(),
            'title'         => form_input('title', null, 'style="width:200px;"'),
            'type'          => form_dropdown('type', $this->place_model->ftype(array('folder', 'category'))),
            'buttons'       => form_submit('frmAddItem', 'Добавить', 'class="container_btn"')
         ), TRUE);
        // =====================================================================

        $arrItems = $this->place_model->getItemsToFolder($folderId);
        /*
        $arrItems = array_merge(
            $this->place_model->getFolders($folderId),
            $this->place_model->getCategories($folderId),
            $this->place_model->getFiles($folderId)
        );
        */

        if(count($arrItems) > 0){
            $data['items'] = $arrItems;
        } else {
            $data['items'][]['item'] = 'Элементы отсутствуют';
        }

        echo $this->module->parse('place', 'content.php', $data, TRUE);

        die;
    }

    function category()
    {
        $categoryId = $this->input->post('id');
        $itemType = $this->input->post('item');
        if($itemType != 'c') die;

        if( !is_numeric($categoryId) ) die;

        setcookie("place[c]", $categoryId, time() + 3600, '/');

        // =====================================================================
        echo $this->module->parse('place', 'form.php', array(
            'create_to'     => form_hidden('create_to', 'category'),
            'form_open'     => form_open(),
            'form_close'    => form_close(),
            'title'         => form_input('title', null, 'style="width:200px;"'),
            'type'          => form_dropdown('type', $this->place_model->ftype()),
            'buttons'       => form_submit('frmAddItem', 'Добавить', 'class="container_btn"')
         ), TRUE);
         // =====================================================================

        $arrItems = $this->place_model->getItemsToCategory($categoryId);
        //$arrItems = $this->place_model->getFilesToCategory($categoryId);
        
        if(count($arrItems) > 0){
            $data['items'] = $arrItems;
        } else {
            $data['items'][]['item'] = 'Элементы отсутствуют';
        }

        echo $this->module->parse('place', 'content.php', $data, TRUE);

        die;
    }

    function create()
    {
        $create_to  = $this->input->post('create_to');
        if(strlen($create_to) == 0) die;

        $title      = $this->input->post('title');
        if(strlen($title)     == 0) die;

        $type       = $this->input->post('type');
        if(strlen($type)      == 0) die;

        $parentFolderId     = @$_COOKIE['place']['f'];
        $parentCategoryId   = @$_COOKIE['place']['c'];

        $username=$this->authorization->getUserName();

        // Определяем тип создаваемого объекта
        switch($type)
        {
            // Если тип: "раздел"
            case 'folder':
                
                // Создаем раздел, return node
                $resultNode = $this->folder->createFolder(array('title'=>$title), $parentFolderId);

                if(count($resultNode) == 0 && !is_array($resultNode)) die;

                // Получаем все данные по созданому разделу
                $Item = $this->folder->getExtraFolder($resultNode);

                if(count($Item) == 0) die;

                echo $this->place_model->item(anchor(
                    'admin/place/content/folder',
                    _icon('folder').$title,
                    'item="f:'.$Item['id'].'" title="'.$this->place_model->langPlace['folder'].'"'
                ));
                
                $extraFolder = $this->folder->_getExtraById($parentFolderId);
                
                log_message('INFO', 'Пользователь '.$username.': Создан раздел с названием <b>"'.$title.'"</b> в разделе <b>"'.$extraFolder['title'].'"</b>');
                break;

            // Если тип: "категория"
            case 'category':

                // Создаем категорию, return insert id
                $resultId = $this->category->createCategory(array('title'=>$title, 'desc'=> ''), $parentFolderId);

                if(!is_numeric($resultId)) die;

                // Получаем все данные по созданой категории
                $Item = $this->category->getExtraCategory($resultId);

                if(count($Item) == 0) die;

                echo $this->place_model->item(anchor(
                    'admin/place/content/category',
                    _icon('folder_blue').$title,
                    'item="c:'.$Item[0]['id'].'" rel="" title="'.$this->place_model->langPlace['category'].'"'
                ));
                
                $extraFolder = $this->folder->_getExtraById($parentFolderId); 
                          
                log_message('INFO', 'Пользователь '.$username.': Создана рубрика с названием <b>"'.$title.'"</b> в разделе <b>"'.$extraFolder['title'].'"</b>');              
                break;

            // Иначе это "страница" с неизвестным типом
            default:

                if($create_to == 'folder')
                {
                    $extraParentNode = $this->folder->_getExtraById($parentFolderId);         
                    $log_text = 'в разделе с названием <b>"'.$extraParentNode['title'].'"</b>';  
                    $parentCategoryId = 0;
                }
                elseif($create_to == 'category')
                {
                    $extraParentNode = $this->category->getExtraCategory($parentCategoryId);
                    $log_text = 'в рубрике с названием <b>"'.$extraParentNode[0]['title'].'"</b>';
                    $parentFolderId = 0;
                }

                // Создаем страницу, return insert id
                $result = $this->file->createFile(array(
                    'title'=>$title,
                    'type'=> $type,
                    'folder_id'=> $parentFolderId,
                    'category_id' => $parentCategoryId
                ));

                 
                
                if(!is_numeric($result)) die;

                foreach($this->module->config($type, 'place') as $file => $functions)
                {
                    $this->$file->$functions['create'](array('file_id'=>$result));
                }

                // Получаем все данные по созданой странице
                $Item = $this->file->getExtraFile($result);

                if(count($Item) == 0) die;

                echo $this->place_model->item(anchor(
                    'admin/'.$type.'/'.$type.'/file',
                    $this->place_model->_icon($Item[0]['type']).$title,
                    'rel="dialog" item="a:'.$Item[0]['id'].'" title="'.$this->module->config($Item[0]['type'],'title').'"'
                ));
                    
                log_message('INFO', 'Пользователь '.$username.': Создан(а) '.$this->module->config($Item[0]['type'],'title').' с названием '.$title.'&nbsp;'.$log_text);
                break;
        }
        die;
    }

    function remove()
    {
        $username=$this->authorization->getUserName();

        $item_id = $this->input->post('id');
        $type = $this->input->post('type');

        if(!is_numeric($item_id)) die;
        if(strlen($item_id) == 0) die;

        switch($type)
        {
            case 'f':
                
                $extraParentFolder = $this->folder->_getExtraById($item_id);                 
                $this->folder->deleteFolder($item_id);
                log_message('INFO', 'Пользователь '.$username.': Удалена раздел с названием <b>"'.$extraParentFolder['title'].'"</b>');
                break;
            case 'c':
                $extraParentNode = $this->category->getExtraCategory($item_id);  
                $Files = $this->file->getFilesToCategory($item_id);

                foreach($Files as $_file){
                    foreach($this->module->config($_file['type'], 'place') as $file => $functions){
                        $this->$file->$functions['remove'](array('file_id'=>$_file['id']));
                        log_message('INFO', 'Пользователь '.$username.': Удален объект c названием '.$_file['title']);            
                    }
                    $this->file->deleteFile($_file['id']);
                }
                
                $this->category->deleteCategory($item_id);
                
                log_message('INFO', 'Пользователь '.$username.': Удалена рубрика c названием '.$extraParentNode[0]['title']);
                break;
            case 'a':
                $File = $this->file->getExtraFile($item_id);
                
                if(count($File) == 0) die;

                //если удаляемый объект -  фото, то удаляем изображения с папки
                if ($File[0]['type']=='photo') {
                $news=$this->photo_model->extra(array('file_id'=>$File[0]['id']));
                
                $img = explode('.', $news[0]['file_name']);
                $img[0]=$img[0].'_thumb';
                $img = implode($img, '.');
                
                unlink($news[0]['full_path']);
                unlink($news[0]['file_path'].$img);
                unlink($news[0]['file_path'].'mini/mini_'.$news[0]['file_name']);
                }
                
                foreach($this->module->config($File[0]['type'], 'place') as $file => $functions){
                    $this->$file->$functions['remove'](array('file_id'=>$item_id));
                }
                
                
                $this->file->deleteFile($item_id);
                
                log_message('INFO', 'Пользователь '.$username.': Удален объект c названием '.$File[0]['title']);            
                break;
        }

        die;
    }

    function rename()
    {
        $username=$this->authorization->getUserName();

        $item_id = $this->input->post('id');
        $type    = $this->input->post('type');
        $title   = $this->input->post('title');

        if(!is_numeric($item_id)) die;
        if(strlen($type) == 0) die;
        if(strlen($title) == 0) die;

        switch($type)
        {
            case 'f':
                $this->folder->renameFolder($title, $item_id);
                log_message('INFO', 'Пользователь '.$username.': Переименован раздел #'.$item_id.', новое название <b>"'.$title.'"</b>');
                break;
            case 'c':
                $this->category->renameCategory($title, $item_id);
                log_message('INFO', 'Пользователь '.$username.': Переименована рубрика #'.$item_id.', новое название <b>"'.$title.'"</b>');
                break;
            default:
                $this->file->renameFile($title, $item_id);
                log_message('INFO', 'Пользователь '.$username.': Переименован объект #'.$item_id.', новое название <b>"'.$title.'"</b>');    
                break;
        }

        die;
    }

    function onmove()
    {
        $username=$this->authorization->getUserName();

        // Что перемещаем ...
        $node['id'] = $this->input->post('node_id');
        $node['type'] = $this->input->post('node_type');

        // Куда (относительно чего) перемещаем ...
        $refNode['id'] = $this->input->post('ref_node_id');
        $refNode['type'] = $this->input->post('ref_node_type');

        $TYPE = $this->input->post('onmove_type');

        log_message('INFO', 'Пользователь '.$username.': Действие '.$TYPE.' с id = '.$node['id'].' type = '.$node['type'].' -> id = '.$refNode['id'].' type = '.$refNode['type']);
        
        $this->place_model->onMove($node, $refNode, $TYPE);
        
        die;
    }

}
?>
