<?php
/**
 * Created by PhpStorm.
 * User: Sergei
 * Date: 07.05.2018
 * Time: 3:00
 */
class ASV_Newsmodule_Model_Newsmodule extends Mage_Core_Model_Abstract {
    public function _construct(){
        parent::_construct();
        $this->_init('newsmodule/newsmodule');
    }
}