<?php

class Pittarosso_ShipmentForm_Block_Items extends Mage_Adminhtml_Block_Sales_Order_Shipment_Create_Items
{
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        /** @var Mage_Adminhtml_Block_Widget_Button $submitButtonBlock */
        $submitButtonBlock = $this->getChild('submit_button');
        $submitButtonBlock->setData('onclick', 'showShipmentSku(this)');
    }
}