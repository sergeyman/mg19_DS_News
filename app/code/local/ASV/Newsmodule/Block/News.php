<?php

class DS_News_Block_News extends Mage_Core_Block_Template
{
    public function getNewsCollection()
    {
        $newsCollection = Mage::getModel('dsnews/news')->getCollection();
        $newsCollection->setOrder('created', 'DESC');
        return $newsCollection;
    }
}