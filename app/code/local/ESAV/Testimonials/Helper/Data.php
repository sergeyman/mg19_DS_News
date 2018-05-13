<?php
class ESAV_Testimonials_Helper_Data extends Mage_Core_Helper_Abstract
{
    const ROUTE_TEST_INDEX = 'testimonials';
    public function getIndexUrl()
    {
        return Mage::getBaseUrl().self::ROUTE_TEST_INDEX;
    }

}
	 