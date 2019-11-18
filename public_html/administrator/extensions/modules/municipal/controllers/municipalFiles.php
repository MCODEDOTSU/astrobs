<?php
class MunicipalFiles extends Admin_Controller
{
    var $upload_config = array();
    
    function MunicipalFiles()
    {
        parent::Admin_Controller();
        $this->upload_config = $this->module->config('municipal', 'upload');
    }
    
    function index()
    {
        $type = $this->uri->segment(5); if(!is_string($type)) return FALSE; $this->session->set_userdata('municipal_municipalFiles_type', $type);
        $did = $this->uri->segment(6); if(!is_numeric($did)) return FALSE;  $this->session->set_userdata('municipal_municipalFiles_did', $did);  
        
        $this->display->_content('<h2>Прикрепленные файлы</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/municipal/municipalFiles/form', _icon('page_white_add').'Прикрепить файл', 'class="cms_btn"').'</div>');
        
        $Files = $this->municipal_model->getFiles(array(
            'type' => $type,
            'did' => $did
        ));
        
        if(count($Files) == 0) return FALSE;
        
        foreach($Files as $file){
            $data['files'][] = array(
                'title' => anchor($file['full_path'], $file['title']),
                'desc' => $file['desc'],
                'actions' => anchor('admin/municipal/addAuction/edit/'.$file['id'], 'Редактировать').
                             anchor('admin/municipal/addAuction/delete/'.$file['id'], 'Удалить')
            );         
        }
        
        $this->module->parse('municipal', 'municipal/files.php', $data);  
    }
    
    function form()
    {
        $this->module->parse('municipal', 'municipal/addFile.php', array(
            'title' => form_input('title'),
            'desc' => form_textarea('desc'),
            'upload' => form_upload('upload'),
            'form_open' => form_open_multipart('admin/municipal/municipalFiles/save'),
            'form_close' => form_close(),
            'submit' => form_submit('frmSubmit', 'Прикрепить')
        ));    
    }
    
    function save()
    {
        $this->load->library('upload', $this->upload_config);
        
        $type = $this->session->userdata('municipal_municipalFiles_type'); if(!is_string($type)) return false;
        $did = $this->session->userdata('municipal_municipalFiles_did'); if(!is_numeric($did)) return false;     
        
        $title = $this->input->post('title'); if(!is_string($title)) return FALSE;
        $desc = $this->input->post('desc');  if(!is_string($desc)) return FALSE;
        
        if($this->upload->do_upload('upload'))
        {
            $this->municipal_model->addFile(array(
                'title' => $title,
                'desc' => $desc,
                'did' => $did,
                'type' => $type
            ), $this->upload->data()); 
        }
        
        redirect('admin/municipal/municipalFiles/index/'.$type.'/'.$did);    
    }
    
    function edit()
    {
        $fileId = $this->uri->segment(5); if(!is_numeric($fileId)) return FALSE;
        
        $extraFile = $this->municipal_model->getExtraFile($fileId); if(count($extraFile) == 0) return FALSE;
        
        foreach($extraFile as $file){
            $data['file'] = array(
                'title' => $title,
                'desc' => $desc,
                'upload' => '',
                'form_open' => form_open_multipart('admin/municipal/municipalFiles/update/'.$fileId),
                'form_close' => form_close(),
                'submit' => form_submit('frmSubmit', 'Сохранить')
            );
        }
    }
    
    function update()
    {
        $type = $this->session->userdata('municipal_municipalFiles_type'); if(!is_string($type)) return false;
        $did = $this->session->userdata('municipal_municipalFiles_did'); if(!is_numeric($did)) return false;     
        $fileId = $this->uri->segment(5); if(!is_numeric($fileId)) return FALSE;     
        $title = $this->input->post('title'); if(!is_string($title)) return FALSE;
        $desc = $this->input->post('desc');  if(!is_string($desc)) return FALSE;
        
        $this->municipal_model->updateExtraFile(array('title' => $title, 'desc' => $desc), array('id' => $fileId));
        
        redirect('admin/municipal/municipalFiles/index/'.$type.'/'.$did);
    }
    
    function delete()
    {
        $type = $this->session->userdata('municipal_municipalFiles_type'); if(!is_string($type)) return false;
        $did = $this->session->userdata('municipal_municipalFiles_did'); if(!is_numeric($did)) return false;
        $fileId = $this->uri->segment(5); if(!is_numeric($fileId)) return FALSE;
        $this->municipal_model->deleteFile($fileId);                         
        redirect('admin/municipal/municipalFiles/index/'.$type.'/'.$did);                            
    }
}
?>
