<?php
class Video extends Public_Controller
{
    var $moduleName = 'video';
    
    function Video()
    {
        parent::Public_Controller();
    }
    
    function index()
    {
        die;
        $photoId = $this->uri->segment(5);  if(!is_numeric($photoId)) return FALSE;
        
        $Photo = $this->photo_model->extra(array('id' => $photoId));
        
        if(count($Photo) == 0) {
            return FALSE;
        }
        
        $data = array(
            /*'title' => ucfirst($Photo[0]['title']),
            'body'  => ucfirst($Photo[0]['body']),
            'created' => date('l dS \of\ F Y h:I:s A', time($Article[0]['created'])),
            'modifed' => $Article[0]['modifed']  */
        );
        
        $this->module->parse($this->moduleName, 'view.php', $data);    
    }
    
    function view()
    {
        $photoId = $this->uri->segment(4); if(!is_numeric($photoId)) return FALSE; 
        
        $Photo = $this->video_model->extra(array('file_id' => $photoId));
        
        if(count($Photo) == 0) {
            return FALSE;    
        }
        
        $data = array(
            'title'     => ucfirst($Photo[0]['title']),
            'desc'      => ucfirst($Photo[0]['desc']),
            'image'     => '
                <object id="videoplayer933" width="500" height="409" align="center">
                <param name="allowFullScreen" value="true" />
                <param name="allowScriptAccess" value="always" />
                <param name="wmode" value="transparent" />
                <param name="movie" value="http://new.regionvol.ru/extensions/plugins/uppod/js/uppod.swf" />
                <param name="flashvars" value="comment=test&amp;st=http://new.regionvol.ru/extensions/plugins/video47-478.txt&amp;file=http://new.regionvol.ru/uploads/video/'.$Photo[0]['file'].'" />
                <embed src="http://new.regionvol.ru/extensions/plugins/uppod/js/uppod.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" flashvars="comment=test&amp;st=http://new.regionvol.ru/extensions/plugins/uppod/js/video47-478.txt&amp;file=http://new.regionvol.ru/uploads/video/'.$Photo[0]['file'].'" width="500" height="409"></embed>
                </object>
            ', 
            'created'   => date('d.m.Y', strtotime($Photo[0]['created'])),
            'breadCrumbs'	=> $this->breadcrumbs->get ($photoId),
			//'catTours'		=> $this->central_menu_model->get ()
        );
        
        $this->module->parse($this->moduleName, 'view.php', $data);
            
    }
}  
?>
