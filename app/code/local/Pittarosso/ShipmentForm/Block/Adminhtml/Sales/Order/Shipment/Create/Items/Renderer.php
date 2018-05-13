<?php

class Pittarosso_ShipmentForm_Block_Adminhtml_Sales_Order_Shipment_Create_Items_Renderer extends Mage_Adminhtml_Block_Sales_Items_Renderer_Default
{
    /**
     * {@inheritdoc}
     */
    public function canShipPartially($order = null)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function canShipPartiallyItem($order = null)
    {
        return false;
    }
}