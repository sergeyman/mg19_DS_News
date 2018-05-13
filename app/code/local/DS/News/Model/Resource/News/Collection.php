<?php
class DS_News_Model_Resource_News_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    public function __construct($resource = null)
    {
        parent::__construct($resource);
        $this->_init('dsnews/news');
    }
}