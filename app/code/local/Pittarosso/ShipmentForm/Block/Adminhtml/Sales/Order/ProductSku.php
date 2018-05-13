<?php

class Pittarosso_ShipmentForm_Block_Adminhtml_Sales_Order_ProductSku extends Mage_Adminhtml_Block_Template
{
    /**
     * Retrieve shipment model instance
     *
     * @return Mage_Sales_Model_Order_Shipment
     */
    public function getShipment()
    {
        return Mage::registry('current_shipment');
    }

    /**
     * @return array
     */
    public function getOrderSku()
    {
        return Mage::helper('pittarosso_shipmentform')->getOrderSku($this->getShipment()->getOrder());
    }
}
