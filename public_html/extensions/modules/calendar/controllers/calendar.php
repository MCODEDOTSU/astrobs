<?php

class Calendar extends Public_Controller
{

    function index()
    {
        $data=array();

        if ($this->input->post('year')) $year=$this->input->post('year'); else $year=date('Y');
        if ($this->input->post('month')) $month=$this->input->post('month'); else $month=date('m');  
        if ($this->input->post('day'))
        {
         $day=$this->input->post('day');
         $year=$this->input->post('y');
         $month=$this->input->post('m');
        } else $day=0;
        if (!empty($_POST))
        {
//          $this->calendar_model->calendar_block($year, $month);
    //    $this->load->module_model('news', 'news_model');
        //$news = $this->news_model->get_news_date($year, $month);
        $news=$this->calendar_model->get_news($year, $month, $day);

        if ($news)
        {
        foreach($news as $_news)
        {
		if ($_news['img'] != '')
		{
			$img = str_replace (".", "_150x150.", $_news['img']);
			$img = '<a href="/news/news/view/'.$_news['file_id'].'"><img src="/extensions/image.php?src=../news_imgs/' . $img . '" border=0 align="left">';
			//die ($img);
		}
		else
		{
			$img = '<a href="/news/news/view/'.$_news['file_id'].'">';
		}
            $data = array(
		'img'   => $img,
              'title' => ucfirst($_news['title']),
              'body'  => ucfirst($_news['desc']) . '</a>',
              'created' => date('d.m.Y', strtotime($_news['created'])),
	      'rating'	=> $_news['rating'],
              'button'  => anchor('news/news/view/'.$_news['file_id'], 'подробнее...', 'class="next radius"')
         );
         $this->module->parse('calendar', 'article.php', $data);
        }
        }
       }
    }

}
?>
