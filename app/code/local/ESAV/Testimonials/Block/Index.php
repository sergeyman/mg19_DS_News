<?php

class ESAV_Testimonials_Block_Index extends Mage_Core_Block_Template
{
    //http://excellencemagentoblog.com/blog/2011/10/18/magento-collection-paging/#sthash.25ch3n6k.dpuf
    public function __construct()
    {
        parent::__construct();
        $collection = Mage::getModel('testimonials/testimonials')->getCollection();
        $this->setCollection($collection);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(5=>5,10=>10,20=>20,'all'=>'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getSaveUrl()
    {
        return Mage::helper('testimonials')->getIndexUrl().'/index/save/';
    }
}