<?php
class Video_model extends Model
{
    var $moduleName = 'video';
    var $controllerName = 'video';
    
    function Video_model()
    {
        parent::Model();
        $this->TABLE = 'th_video';   
    }
    
    function extra($where = array())
    {
        if(count($where) == 0) return FALSE;
        
        return $this->db->from($this->TABLE)->where($where)->get()->result_array();    
    }

    function photo_in_category($photoId = null)
    {
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
            'breadCrumbs'	=> ''
        );
        
        return $this->module->parse($this->moduleName, 'view.php', $data, TRUE);
    }
}  
?>
