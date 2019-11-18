<?php
class RegisterOfContracts_Model extends Model
{
    var $moduleName = 'municipal';
    var $tables = array();
    
    function RegistrOfContracts_Model()
    {
        parent::Model();
        $this->tables = $this->module->config($this->moduleName, 'TABLES');
    }
}
?>
