<?php
class Anews_block_model extends Model
{
    function Anews_block_model()
    {
        parent::Model();
        $this->TABLE = 'th_astrobl_news';
        
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
                <h4><span>Новости области</span></h4>
                '.$this->anews_block_model->getBlock().'
            </div>

        ';
    }

    function getBlock()
    {
        $html = '';
        
        $arrAnews = $this->get(5);

        foreach($arrAnews as $news)
        {
            if(strlen($news['title']) == 0) continue;
            
            if(strlen($news['desc']) == 0) continue;

            $html .= $this->module->parse('anews_block', 'block', array(
                'title'     => $news['title'],
                'desc'      => $news['desc'],
                'date'	   => $news['date'],
                'button'    => anchor($news['link'], 'подробнее...', 'class="next radius"')
            ), true);
        }

        return $html;
    }
}
?>
