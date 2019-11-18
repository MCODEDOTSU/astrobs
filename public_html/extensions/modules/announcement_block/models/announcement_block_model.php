<?php
class Announcement_block_model extends Model
{
    function Announcement_block_model()
    {
        parent::Model();
        $this->TABLE = 'th_announcement';
        
    }
    function extra($where = array())
    {
        if(count($where) == 0) return false;

        return $this->db->where($where)->get($this->TABLE)->result_array();
    }

    function get($limit = 5)
    {
        
        return $this->db->order_by("id", "asc")->limit($limit)->get($this->TABLE)->result_array();
    }

    function block()
    {
        return '

            <div id="news_block_container" class="block">
                <h4><span>Объявления</span></h4>
                '.$this->announcement_block_model->getBlock().'
            </div>

        ';
    }

    function getBlock()
    {
        $html = '';
        
        $arrAnnouncement = $this->get(5);

        foreach($arrAnnouncement as $news)
        {
            if(strlen($news['title']) == 0) continue;
            
            if(strlen($news['body']) == 0) continue;

            $html .= $this->module->parse('announcement_block', 'block', array(
                'title'     => $news['title'],
                'desc'      => $news['body']
            ), true);
        }

        return $html;
    }
}
?>
