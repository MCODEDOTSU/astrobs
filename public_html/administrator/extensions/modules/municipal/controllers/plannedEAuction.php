<?php
class PlannedEAuction extends Admin_Controller
{
    var $moduleName = 'municipal';
    var $controllerName = 'plannedEAuction';
    
    function PlannedEAuction()
    {
        parent::Admin_Controller();
        parent::access($this->moduleName);
    }
    
    function index()
    {
        $words[0] = array(
            'держать пари, спорить',
            'лопнуть',
            'бросать, лить (метал)',
            'стоить',
            'резать', 
            'ударять',
            'делать больно',
            'вязать',
            'позволять',
            'ставить, класть',
            'оставить',
            'освобождать',
            'ставить',
            'терять',
            'кромсать',
            'закрывать',
            'разрезать',
            'колоть',
            'распространять',
            'потеть',
            'толкать'
        );
        $words[1] = array(
            'bet',
            'burst',
            'cast',
            'cost',
            'cut', 
            'hit',
            'hurt',
            'knit',
            'let',
            'put',
            'quit',
            'rid',
            'set',
            'shed',
            'shred',
            'shut',
            'slit',
            'split',
            'spread',
            'sweat',
            'thrust'
        );
        
        $words[1] = array(
            'bleed, bled, bled',
            'breed, bred, bred',
            'feed, fed, fed',
            'lead, led, led',
            'speed, sped, sped',
            'have, had, had',
            'bend, bent, bent',
            'build, built, built',
            'lend, lent, lent',
            'send, sent, sent',
            'spend, spent, spent',
            'burn, burnt, burnt',
            'creep, crept, crept',
            'deal, dealt, dealt',
            'dwell, dwelt, dwelt',
            'feel, felt, felt',
            'keep, kept, kept',
            'kneel, knelt, knelt',
            'mean, meant, meant',
            'meet, met, met',
            'shoot, shot, shot',
            'sleep, slept, slept',
            'smell, smelt, smelt',
            'spell, spelt, spelt',
            'spill, spilt, spilt',
            'sweep, swept, swept',
            'weep, wept, wept'
        );
        $words[0] = array(
            'кровоточить',
            'порождать',
            'кормить',
            'вести',
            'спешить; гнать',
            'иметь',
            'сгибать',
            'строить',
            'одалживать',
            'посылать',
            'тратить',
            'гореть',
            'ползти',
            'иметь дело с ..., договариться',
            'проживать, жить',
            'чувствовать',
            '(со)держать',
            'встать на колени; стоять на коленях',
            'иметь ввиду; говорить серьезно',
            'встречать(-ся)',
            'стрелять',
            'спать',
            'пахнуть; нюхать',
            'произносить по буквам',
            'проливать(-ся); разбрызгивать(-ся)',
            'мести, подметать',
            'плакать'
        );
        
        //echo count($words[0]).' = '.count($words[1]).'<br / ><br />';
        
        /*$rnd = rand(0,20);        */
        /*$this->display->_content( '
            <script>
                 $(document).ready(function(){
                    window.setTimeout(\'$("#p1312341").text($("#p1312341").text() + " = " + $("#p1312341").attr("rel"));\', 7000);
                    window.setTimeout(\'document.location.href = document.location.href\', 15000);
                 });
            </script>
        ');   */
        
        
        //return FALSE;
        $this->display->_content('<h2>Планируемые электронные аукционы</h2>');
        $this->display->_content('<div id="cms_bar">'.anchor('admin/'.$this->moduleName.'/addEAuction', _icon('add').'Добавить', 'class="cms_btn"').'</div>');
        
        $plannedEAuction = $this->plannedEAuction_model->get();  
        
        if(count($plannedEAuction) == 0) {
            return FALSE;
        }
        
        foreach($plannedEAuction as $eauction){
            $data['eauction'][] = array(
                'id'                        => $eauction['id'],    
                'number'                    => $eauction['number'],
                'title'                     => $eauction['title'],
                'subjectMunicipalContract'  => $eauction['subjectMunicipalContract'],
                'dateStartWork'             => date('d.m.Y', $eauction['dateStartWork']),
                'timeStartOrEnd'            => $eauction['timeStartOrEnd'],
                'initialContractPrice'      => $eauction['initialContractPrice'],
                'actions'                   => anchor('admin/'.$this->moduleName.'/municipalFiles/index/eauction/'.$eauction['id'], _icon('page_white'),'title="Прикрепленные файлы"')    
            );   
        }
        
        $this->module->parse('municipal', $this->controllerName.'/index.php', $data);
        //$this->display->_footer('<p align="center" rel="'.$words[0][$rnd].'" id="p1312341">'.$words[1][$rnd].'</p>');    
    }
}
?>
