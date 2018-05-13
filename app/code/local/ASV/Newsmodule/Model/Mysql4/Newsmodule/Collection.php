<?php
/**
 * Created by PhpStorm.
 * User: Sergei
 * Date: 07.05.2018
 * Time: 3:02
 */
class ASV_Newsmodule_Model_Mysql4_Newsmodule_Collection extends
    Mage_Core_Model_Mysql4_Collection_Abstract {
    public function _construct(){
        $this->_init('newsmodule/newsmodule');
    }
}