<?php
class Search extends Public_Controller
{
    function Search()
    {
        parent::Public_Controller();
        $this->load->helper('text');
    }
    
    function index()
    {
    	$this->lang->setModule ('search');
    	$this->display->_content('<div class="content_desc">' . $this->breadcrumbs->getHome(array ($this->lang->mline ('search_text') => site_url (array ('search', 'search')))) . '</div>');
        $keywords = $this->input->post('search');
                              
        if(($keywords == '') || (!is_string($keywords))) return false;
        
        $this->display->_content('<div class="content_desc"><h1>' . $this->lang->mline ('search_result_text') . '</h1>');
        $this->display->_content($this->lang->mline ('search_you_searched') . ': <strong style="color: red;">'.$keywords.'</strong><br /><br />');
        
        $searchArticles = $this->search_model->searchInArticles($keywords);
        $searchNews = $this->search_model->searchInNews($keywords);

        
        $data['text_find_results'] = $this->lang->mline ('search_results_of_text');
        $data['news_find_results'] = $this->lang->mline ('search_results_of_news');
        $data['result_title'] = $this->lang->mline ('search_result_title');
        
        if(count($searchArticles) > 0) {     
            foreach($searchArticles as $article){
                
                $data['article'][] = array(
                    'title' => $article['title'],
                    'body' => highlight_phrase($this->search_model->parseText($this->search_model->delTags($article['body']), $keywords),$keywords,'<span style="background-color: orange">', '</span>'),
                    'next' => anchor('article/article/view/'.$article['file_id'], $this->lang->mline ('search_more'), 'class="but_search"')
                );
            }
            
            $this->module->parse('search', 'article.php', $data);
        } else {
            $this->display->_content('<p>' . $this->lang->mline ('search_text_no_results') . '</p>');
        }
        
        if(count($searchNews) > 0) {
            foreach($searchNews as $news){
                $data['news'][] = array(
                    'title' => $news['title'],
                    'body' => highlight_phrase($this->search_model->parseText($this->search_model->delTags($news['body']), $keywords),$keywords,'<span style="background-color: orange">', '</span>'),   
                    'next' => anchor('news/news/view/'.$news['file_id'], $this->lang->mline ('search_more'), 'class="but_search"')
                );
            }
            
            $this->module->parse('search', 'news.php', $data);            
        } else {
            $this->display->_content('<p>' . $this->lang->mline ('search_news_no_results') . '</p>'); 
        }
        
        $this->display->_content('</div>');
        
        
    }
    
    function page () {
    	if ($this->session->userdata('version_site') != 'ver2') return false;
        $this->module->parse('search', 'block2.php', array(
            'search' => form_input('search', '', 'id="search_text"'),
            'submit' => form_submit('frmSubmit', 'Найти', 'id="search_submit" onclick="search_obj.search_submit()"'),
            'form_open' => form_open('/search/search', 'onsubmit="return false;"'),
            'form_close' => form_close()
        ));
    }
}
?>
