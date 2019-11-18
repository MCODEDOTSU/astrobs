<?php
class Parse extends Public_Controller
{
    function Parse()
    {
        parent::Public_Controller();
    }
    
    function index()
    {
        $ch = curl_init();
        curl_setopt($ch , CURLOPT_URL , "http://habrahabr.ru/");
        curl_setopt($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.12) Gecko/20050919 Firefox/1.0.7");
        curl_setopt($ch , CURLOPT_RETURNTRANSFER , 1 );
        $content = curl_exec($ch);
        curl_close($ch);   
        
        $content = '<div> dsgf hfgh dfshsd DSJ B DGjldsgn njklsngkdsf
        hdfgh df hjs mkngoajgJ  bfdkjbg dsofh
        dsfh sdK GHI HJGDFKjh 
          </div>'; 
        
        //<span>27 апреля 2010, 10:10</span>
        //preg_match_all("/<p>(.*)<br>\r\n<a href=\"(.*)\">.*<\/a><\/p>/isU", $content, $matches, PREG_PATTERN_ORDER); 
        //preg_match_all('/<span>[0-9]{2}\s[a-я]+\s[0-9]{4},\s[0-9]{2}:[0-9]{2}<\/span>/', $content, $matches, PREG_PATTERN_ORDER);
        preg_match_all('/<div>[^<\/div>]+<\/div>/', $content, $matches, PREG_PATTERN_ORDER);
        
        print_r($matches);
        die;
        
        
         
        for ($i =  0; $i < count($matches[1]); $i++)
        {
            $this->display->_content("<h1>".$matches[1][$i]."</h1>");
            flush();
            $ch = curl_init ();
            curl_setopt ($ch , CURLOPT_URL , @$matches[2][$i]);
            curl_setopt ($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.12) Gecko/20050919 Firefox/1.0.7");
            curl_setopt ($ch , CURLOPT_RETURNTRANSFER , 1 );
            $content = curl_exec($ch);
            curl_close($ch);
         
            preg_match_all("/<hr width=\"200\" size=\"1\" noshade align=\"left\">(.*)<center><p><a href=\"\.\.\/\">.*<\/a>/isU", $content, $matches_art, PREG_PATTERN_ORDER);
            $this->display->_content(@$matches_art[1][0]);
            flush();
            sleep(2);
        }  
    }
}
?>
