<?php

class News extends Public_Controller
{
    function News()
    {
        parent::Public_Controller();
    }

    function view()
    {
        $newsId = $this->uri->segment(4);

        $news = $this->news_model->extra(array('file_id' => $newsId));
        if (count($news) == 0) return;

        foreach ($news as $_news) {
            if ($_news['img'] != '' && $this->session->userdata('version_site') != 'ver2') {
                $img = str_replace(".", "_150x150.", $_news['img']);
                $img = '<img src="/extensions/image.php?src=../news_imgs/' . $img . '" border="0" align="left" style="margin: 5px 10px 5px 0;">';
                //die ($img);
            } else {
                $img = '';
            }
            $data = array(
                'img' => $img,
                'title' => ucfirst($_news['title']),
                'body' => ucfirst($_news['body']),
                'created' => date('d.m.Y', strtotime($_news['created'])),
                //'created' => date($_news['created']),
                'button' => anchor($_SERVER['HTTP_REFERER'], 'Назад...', 'class="back"'),
                'breadCrumbs' => $this->breadcrumbs->get($newsId),
                //'catTours'		=> $this->central_menu_model->get ()
            );
            $this->module->parse('news', 'view.php', $data);
        }

        $this->news_model->rating($newsId);
    }
}

?>
