<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class News extends Admin_Controller
{
    var $formYear;
    var $formDay;
    var $formMonth;

    function News()
    {
        parent::Admin_Controller();

        $this->formYear = array(
            '' => 'год',
            date('Y') => date('Y'),
            date('Y') + 1 => date('Y') + 1,
            date('Y') + 2 => date('Y') + 2,
            date('Y') + 3 => date('Y') + 3
        );

        $this->formMonth = array(
            '' => 'месяц', '01' => 'январь', '02' => 'февраль', '03' => 'март‚',
            '04' => 'апрель', '05' => 'май', '06' => 'июнь', '07' => 'июль',
            '08' => 'август', '09' => 'сентябрь', '10' => 'октябрь', '11' => 'ноябрь', '12' => 'декабрь'

        );

        $this->formDay = array(
            '' => 'день', '01' => '01',
            '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07',
            '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12', '13' => '13',
            '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19',
            '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25',
            '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31'
        );
    }

    function file()
    {
        //$fileId = $this->input->post('id');
        $fileId = $this->uri->segment(5);

        if (!is_numeric($fileId)) die;

        // return $mas[0] = array(... ... ..)
        $news = $this->news_model->extra(array('file_id' => $fileId));
        if (count($news)) $news = $news[0];

        if (!empty($news['img']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/news_imgs/' . $news['img'])) {
            $img = '/news_imgs/' . $news['img'];
            //die ($img);
        } else {
            $img = '';
        }

        $data = array(
            'form_open' => form_open_multipart('admin/news/news/save'),
            'form_close' => form_close(),
            'submit' => form_submit('frmSubmit', 'Сохранить'),
            'file_id' => form_hidden('file_id', $fileId),
            'title' => !empty($news['title']) ? form_input('title', $news['title'], 'style="width: 300px;"') : form_input('title', '', 'style="width: 300px;"'),
            'img' => '<img src="' . $img . '" border="0" width="80">',
            'desc' => !empty($news['desc']) ? form_textarea('desc', $news['desc']) : form_textarea('desc', ''),
            'body' => !empty($news['body']) ? form_ckeditor('body', $news['body']) : form_ckeditor('body', ''),
            'created' => form_dropdown('created_day', $this->formDay, date('d')) .
                form_dropdown('created_month', $this->formMonth, date('m')) .
                form_dropdown('created_year', $this->formYear, date('Y')),
//            'expired' => form_dropdown('expired_day', $this->formDay, date('d')) .
//                form_dropdown('expired_month', $this->formMonth, date('m')) .
//                form_dropdown('expired_year', $this->formYear, date('Y')),
            'commented' => !empty($news['commented']) ? 'Нет' . form_radio('commented', 0, $news['commented'], 'checked') . ' Да' . form_radio('commented', 1, $news['commented']) :
                'Нет' . form_radio('commented', 0, '', 'checked') . ' Да' . form_radio('commented', 1, ''),
            'rating' => !empty($news['rating']) ? 'Нет' . form_radio('rating', 0, $news['rating']) . 'Да' . form_radio('rating', 1, $news['rating']) :
                'Нет' . form_radio('rating', 0, '') . 'Да' . form_radio('rating', 1, ''),
        );

        $this->module->parse('news', 'form.php', $data);
    }

    function save()
    {
        $fileId = $this->input->post('file_id');
        $title = $this->input->post('title');
        $desc = $this->input->post('desc');
        $body = $this->input->post('body');


        $created = $this->news_model->datetime(array(
            'day' => $this->input->post('created_day'),
            'month' => $this->input->post('created_month'),
            'year' => $this->input->post('created_year')
        ));

        //$created    = date('Y-m-d h:i:s');  // notice
        //print_r($created);	die;	    // notice
        $created = date('Y-m-d h:i:s', $created);
//        $expired = $this->news_model->datetime(array(
//            'day' => $this->input->post('expired_day'),
//            'month' => $this->input->post('expired_month'),
//            'year' => $this->input->post('expired_year')
//        ));

        $commented = $this->input->post('commented');
        $rating = $this->input->post('rating');

        $news = $this->news_model->extra(array('file_id' => $fileId));

        if (!empty($_FILES['preview']['name'])) {
            $upload_data = $this->photo_model->do_upload('preview');

            if (count($upload_data) == 0) {
                return FALSE;
            }
            move_uploaded_file($_FILES['preview']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/news_imgs/' . $upload_data['file_name']);
            $img = $upload_data['file_name'];
            //die ($img);
        } else {
            $img_data = mysql_fetch_array(mysql_query("SELECT `img` FROM `th_news` WHERE `file_id` = '$fileId'"));
            $img = $img_data['img'];
        }

        if (count($news)) {

            $this->news_model->update(array(
                'title' => $title,
                'desc' => $desc,
                'body' => $body,
                'created' => $created,
                'img' => $img,
                //'expired' => $expired,
                'commented' => $commented,
                'rating' => $rating
            ), array('id' => $news[0]['id']));
        }
        redirect('admin/place/place');
    }
}

?>
