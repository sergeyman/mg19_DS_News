<?php

class Pittarosso_ShipmentForm_Block_Rewrite_Adminhtml_Sales_Order_Shipment_Create_Items extends Pittarosso_ShipmentForm_Block_Items
{
    /**
     * {@inheritdoc}
     */
    public function canCreateShippingLabel()
    {
        return false;
    }
}