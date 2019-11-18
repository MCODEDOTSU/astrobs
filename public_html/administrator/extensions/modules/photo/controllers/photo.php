<?php if (!defined('BASEPATH')) exit('No direct script access allowed');        
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Photo extends Admin_Controller
{
    var $moduleName = 'photo';
    var $controllerName = 'photo';
    var $moduleTitles = '';
    var $confValid = array();
    
    function Photo()
    {
        parent::Admin_Controller();
        $this->moduleTitles = $this->module->config($this->moduleName, 'title');
        $this->confValid = $this->module->config($this->moduleName, 'validation');
    }

    function file()
    {
        $this->display->_content('<div class="cms_title"><img src="' . base_url() . 'cms_icons/photo.png" align="absmiddle">'.$this->moduleTitles.'</div>');
        
        $fileId = $this->uri->segment(5);  
        
        if(!is_numeric($fileId)) return FALSE;

        $this->validation->set_fields($this->confValid['fields']);
        
        $Photo = $this->photo_model->extra(array('file_id' => $fileId));

        if(count($Photo) == 0) {
            return FALSE;
        }

        $data = array(
            'title'         => form_input('title', $Photo[0]['title'], 'style="width: 300px;"'),
            'desc'          => form_textarea('desc', $Photo[0]['desc']),
            'path'          => '<img src="/uploads/photo/'.$Photo[0]['file_name'].'" '/*.$Photo[0]['image_size_str']*/.'/><br />'.form_upload('path'),
            'file_id'       => form_hidden('file_id', $fileId),
            'submit'        => form_submit('frmSubmit', 'Сохранить'),
            'form_open'     => form_open_multipart('admin/'.$this->moduleName.'/'.$this->controllerName.'/save'),
            'form_close'    => form_close()
        );
        $this->module->parse($this->moduleName, 'form.php', $data);
    }

    function save()
    {
        $fileId  = $this->input->post('file_id'); if(!is_numeric($fileId)) return FALSE;

        if (!empty($_FILES['path']['name'])) {
            $upload_data = $this->photo_model->do_upload('path');

            if(count($upload_data) == 0) {
                return FALSE;
            }
			
            $this->photo_model->image_resize($upload_data['full_path']);
            $this->photo_model->image_resize_mini($upload_data['full_path'], $upload_data['file_name']);
            if ($upload_data['image_width'] > 800 || $upload_data['image_height'] > 600) {
            	$this->photo_model->image_resize_stand($upload_data['full_path'], $upload_data['file_name']);
            	$upload_data['image_width'] = 800;
            	$upload_data['image_height'] = 600;
            }
            
        }

        $this->validation->set_rules($this->confValid['rules']);
        $this->validation->set_fields($this->confValid['fields']);

        if($this->validation->run() == TRUE){

            $upload_data['title'] = $this->validation->title;
            $upload_data['desc'] = $this->validation->desc;

            $Photo = $this->photo_model->extra(array('file_id' => $fileId));

            if(count($Photo) == 0){
                return FALSE;
            }

            $this->photo_model->update($upload_data, array('id' => $Photo[0]['id']));

            redirect('admin/place/place');

        } else {
            $this->index();    
        }
    }
}
?>
