<?php
class Mod_municipal_model extends Model
{
    var $moduleName = 'mod_municipal';
    
    function Mod_municipal_model()
    {
        parent::Model();
    }
    
    function block()
    {
        return '
            <div id="mod_municipal" class="block">
                <ul>
                    <li>'.anchor($this->moduleName.'/auction', 'Аукционы').'</li>
                    <li>'.anchor($this->moduleName.'/concurs', 'Конкурсы').'</li>
                    <li>'.anchor($this->moduleName.'/eauction', 'Электронные аукционы').'</li>
                    <li>'.anchor($this->moduleName.'/quotation', 'Котировки').'</li>
                </ul>
            </div>
        ';     
    }
}
?>
