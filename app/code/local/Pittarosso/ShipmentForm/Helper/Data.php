<?php

class Pittarosso_ShipmentForm_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param Mage_Sales_Model_Order $order
     *
     * @return array
     */
    public function getOrderSku($order)
    {
        $result = array();
        foreach ($order->getAllItems() as $item) {
            /** @var Mage_Sales_Model_Order_Item $item */

            $product = Mage::getModel('catalog/product');
            $product->load($item->getProductId());

            if ($product->getTypeId() !== 'simple') {
                continue;
            }

            $qty = $item->getQtyOrdered();

            while ($qty > 0) {
                $qty -= 1;
                $result[] = $item->getSku();
            }
        }

        return $result;
    }

    /**
     * @param string $delimiter
     * @return string
     */
    public function getFullActionName($delimiter='_')
    {
        $request = Mage::app()->getRequest();

        return $request->getRequestedRouteName().$delimiter.
            $request->getRequestedControllerName().$delimiter.
            $request->getRequestedActionName();
    }
}
