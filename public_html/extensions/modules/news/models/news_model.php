<?php
class News_model extends Model
{
    function News_model()
    {
        parent::Model();
        $this->TABLE = 'th_news';
    }
    
    function extra($where = array())
    {
        if(count($where) == 0) return false;
	$page  = (int) $this->uri->segment(5);
	if ($page < 1) $page = 1;
	$start = ($page - 1) * 15;
        return $this->db->where($where)->order_by('created', 'asc')->get($this->TABLE)->result_array();
    }

    function news_in_category($fileId)
    {
        $news =  $this->extra(array('file_id' => $fileId));
        if(count($news) == 0) return;


        foreach($news as $_news)
        {
		if ($_news['img'] != '' && $this->session->userdata('version_site') != 'ver2')
		{
			$img = str_replace (".", "_150x150.", $_news['img']);
			$img = '<img src="/extensions/image.php?src=../news_imgs/' . $img . '" border="0" align="left" style="margin: 5px 10px 5px 0;">';
			//die ($img);
		}
		else
		{
			$img = '';
		}
            $data = array(
		'img'   => $img,
                'title' => '<a href="' . base_url() . 'news/news/view/' . $_news['file_id'] . '">' . ucfirst($_news['title']) . '</a>',
                'body'  => ucfirst($_news['desc']),
                'created' => date('d.m.Y', strtotime($_news['created'])),
                'rating' => $_news['rating'],
        		'button' => anchor('/news/news/view/'.$_news['file_id'], 'Подробнее', 'class="next"'),
        		'breadCrumbs' => ''
            );
            return $this->module->parse('news', 'block.php', $data, true);
        }
    }

    function rating($newsId = NULL)
    {
        $news=$this->extra(array('file_id' => $newsId));
        $news_rating=$news[0]['rating'];
        $news_rating++;

        $this->db->where('file_id', $newsId);
        return $this->db->update($this->TABLE, array('rating' => $news_rating));
    }
}
?>
