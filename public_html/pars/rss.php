<?php

//set_time_limit(0);

mysql_connect ("localhost", "regionvol", "gid8Lu35j");
mysql_select_db ("new_regionvol");

mysql_query ("TRUNCATE TABLE `th_astrobl_news` ");
mysql_query ("SET NAMES `utf8`");

function limit_words($string, $word_limit)
{
   $words = explode(" ", $string);
   return implode(" ", array_splice ($words, 0, $word_limit));
}

$file = 'http://www.astrobl.ru/RSS/';
$ch = curl_init ($file); 
curl_setopt($ch, CURLOPT_TIMEOUT, 15); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.7)'); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$body = curl_exec($ch); 
curl_close($ch); 

preg_match ('#<guid isPermaLink="true">(.*)</guid>#isU', $body, $link_id);
$link = $link_id['1'];
$id   = (int) str_replace('http://www.astrobl.ru/RSS/art.aspx?id=', '', $link);
$finish_id = (int) ($id - 5);

while ($id > $finish_id)
{
   $rss_lent = 'http://www.astrobl.ru/RSS/art.aspx?id='. (int) $id;
   $ch = curl_init ($rss_lent);
   curl_setopt($ch, CURLOPT_TIMEOUT, 60);
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.7)');
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $content = curl_exec($ch);

   curl_close($ch);
   preg_match ('#<span id="fvArticle_TitleLabel" class="ArtHeader">(.*)</span>#isU', $content, $title);
   preg_match ('#<span id="fvArticle_Date_ActLabel">(.*)</span>#isU', $content, $date);
   preg_match ('#<span id="fvArticle_TextLabel"><p>(.*)</p></span>#isU', $content, $text);
   $title = mysql_real_escape_string ($title['1']);
   $desc  = mysql_real_escape_string ( limit_words ($text['1'], 15) ).' ...';
   $date  = mysql_real_escape_string ($date['1']);
   $linkl  = 'http://www.astrobl.ru/Default.aspx?id=2&item='. (int) $id;
   mysql_query ("INSERT INTO `th_astrobl_news` (`title`, `desc`, `date`, `link`) VALUE ('$title', '$desc', '$date', '$linkl') ");
  
   $id    = $id - 1;
//   echo $desc;
}

mysql_close();
?>
