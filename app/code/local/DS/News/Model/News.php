<?php
    class DS_News_Model_News extends Mage_Core_Model_Abstract {
        public function __construct() {
            parent::__construct();
            $this->_init('dsnews/news');
        }
    }