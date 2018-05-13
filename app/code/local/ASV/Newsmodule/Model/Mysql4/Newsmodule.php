<?php
/**
 * Created by PhpStorm.
 * User: Sergei
 * Date: 07.05.2018
 * Time: 3:01
 */
class ASV_Newsmodule_Model_Mysql4_Newsmodule extends Mage_Core_Model_Mysql4_Abstract{
    public function _construct(){
        $this->_init('newsmodule/mymodule_message', 'id');
    }
}