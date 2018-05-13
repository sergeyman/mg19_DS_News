<?php

class Pittarosso_ShipmentForm_Model_Observer
{
    protected $_createdShipmentLabels = false;
    protected $_createdShipmentPackages = false;
    protected $_isShipmentNew = false;

    /**
     * @param Varien_Event_Observer $observer
     */
    public function checkShipmentConfirmation($observer)
    {
        $request = Mage::app()->getRequest();
        $orderId = $request->getParam('order_id');

        /** @var Mage_Sales_Model_Order $order */
        $order = Mage::getModel('sales/order')->load($orderId);

        $sku = Mage::helper('pittarosso_shipmentform')->getOrderSku($order);
        $insertedSku = explode(',', Mage::app()->getRequest()->getParam('shipment_confirmation'));
        $packageNumbers = Mage::app()->getRequest()->getParam('package_numbers');

        /** @var Mage_Adminhtml_Controller_Action $controller */
        $controller = $observer->getControllerAction();

        if (array_diff($sku, $insertedSku)) {
            $this->_stopAction($controller, 'Incorrect shipment confirmation', $orderId);
        } else if (!$packageNumbers || $packageNumbers < 0) {
            $this->_stopAction($controller, 'Incorrect package numbers', $orderId);
        }
    }

    /**
     * @param Mage_Adminhtml_Controller_Action $controller
     * @param string $message
     * @param int $orderId
     */
    protected function _stopAction($controller, $message, $orderId)
    {
        $controller->getRequest()->setDispatched(true);
        $controller->setFlag(
            '',
            Mage_Core_Controller_Front_Action::FLAG_NO_DISPATCH,
            true
        );

        $this->_getSession()->addError($message);

        $response = Mage::app()->getResponse();
        $url = Mage::helper('adminhtml')->getUrl('*/*/new', array('order_id' => $orderId));
        $response->setRedirect($url);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function createShipmentPackages($observer)
    {
        if ($this->_createdShipmentPackages) {
            return;
        }
        $this->_createdShipmentPackages = true;

        /** @var Mage_Sales_Model_Order_Shipment $shipment */
        $shipment = $observer->getEvent()->getShipment();
        if ($shipment->getSkipPackages()) {
            return;
        }

        $packageNumbers = (int)Mage::app()->getRequest()->getParam('package_numbers');
        $qtys = $this->_getQtys($shipment->getOrder());

        /** @var Pittarosso_ShipmentForm_Model_Shipment_Sda $sda */
        $sda = Mage::getModel('flatratesda/shipment_sda');
        $packages = $sda->getLimitedPackages($qtys, $packageNumbers);
        $totalweight = 0;
        foreach ($packages as $package) {
            $totalweight += $package['params']['weight'];
        }

        if(!@unserialize($packages)){
            $shipment->setPackages(serialize($packages));
        }else{
	        $shipment->setPackages($packages);
        }
        $shipment->setTotalWeight($totalweight);
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return array
     */
    protected function _getQtys($order)
    {
        $qtys = [];
        $oldOrder = Mage::getModel('sales/order')->load($order->getId());
        foreach ($oldOrder->getAllVisibleItems() as $item) {
            if (($qty = $item->getQtyOrdered() - $item->getQtyShipped() - $item->getQtyRefunded() - $item->getQtyCanceled()) > 0) {
                $qtys[$item->getId()] = $qty;
            }
        }

        return $qtys;
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function createShipmentLabels($observer)
    {
        if ($this->_createdShipmentLabels) {
            return;
        }
        $this->_createdShipmentLabels = true;


        /** @var Mage_Sales_Model_Order_Shipment $shipment */
        $shipment = $observer->getEvent()->getShipment();
        if ($shipment->getSkipPackages()) {
            return;
        }

        if($shipment->getShippingLabel()){
			return;
        }

        /** @var Tbuy_Sda_Model_Shipment_Label $label */
        $label = Mage::getModel('flatratesda/shipment_label')
            ->setData(array(
                'shipment' => $shipment,
                'order' => $shipment->getOrder(),
                'warehouse' => 1,
                'delay_pickup' => false
            ));

        $shipment->setPackages(unserialize($shipment->getPackages()));
        if (!$label->generateLabel()) {
            Mage::throwException('Cannot create shipment label');
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function addDownloadLabelButton($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Shipment_View) {
            if ($this->_getShipment()->getShippingLabel()) {
                $block->addButton('shipment_label',
                    array(
                        'label' => Mage::helper('pittarosso_shipmentform')->__('Download Label'),
                        'onclick' => "popWin('{$this->_getDownloadLabelLink()}', '_blank')"
                    )
                );
            }
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function addDownloadInvoiceButton($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Shipment_View) {
            /** @var Mage_Sales_Model_Resource_Order_Invoice_Collection $collection */
            $collection = $this->_getShipment()->getOrder()->getInvoiceCollection();
            if (count($collection)) {
                $invoiceId = $collection->getLastItem()->getId();
                $url = Mage::helper('adminhtml')->getUrl('*/shipmentForm/pdfinvoices', array('id' => $invoiceId));

                $block->addButton('download_invoice',
                    array(
                        'label' => Mage::helper('pittarosso_shipmentform')->__('Download Invoice'),
                        'onclick' => "setLocation('{$url}')"
                    )
                );
            }
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function checkNewShipment($observer)
    {
        /** @var Mage_Sales_Model_Order_Shipment $shipment */
        $shipment = $observer->getShipment();

        if (!$shipment->getId()) {
            $this->_isShipmentNew = true;
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function updateOrderStatus($observer)
    {
        /** @var Mage_Sales_Model_Order_Shipment $shipment */

        $shipment = $observer->getShipment();

        if ($this->_isShipmentNew) {
            $order = $shipment->getOrder();
            $order->setStatus(Tbuy_Ax_Helper_Order_Status::AX_MAGE_STATUS_SHIPPED);
            $order->setState(Tbuy_Ax_Helper_Order_Status::AX_MAGE_STATE_SHIPPED);

            $this->_isShipmentNew = false;
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function updateRedirectToShipment($observer)
    {
        $response = Mage::app()->getResponse();
        /** @var Mage_Sales_Model_Order_Shipment $shipment */
        $shipment = Mage::getModel('sales/order_shipment')->getCollection()->getLastItem();

        $lastUrl = null;
        foreach ($response->getHeaders() as $key => $header) {
            if ('Location' === $header['name']) {
                $lastUrl = $header['value'];
            }
        }

        $url = $this->_getAdminhtmlHelper()->getUrl('*/sales_order/view', array('order_id' => $shipment->getOrderId()));
        if ($lastUrl === $url) {
            $response->setRedirect($this->_getAdminhtmlHelper()->getUrl('*/sales_order_shipment/view', array('shipment_id' => $shipment->getId())));
        }
    }

    /**
     * @return string
     */
    protected function _getDownloadLabelLink()
    {
        return  Mage::helper("adminhtml")->getUrl('*/shipmentForm/download', array('id' => $this->_getShipment()->getId()));
    }

    /**
     * @return Mage_Sales_Model_Order_Shipment
     */
    protected function _getShipment()
    {
        return Mage::registry('current_shipment');
    }

    /**
     * @return Pittarosso_ShipmentForm_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('pittarosso_shipmentform');
    }

    /**
     * @return Mage_Core_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('core/session');
    }

    /**
     * @return Mage_Adminhtml_Helper_Data
     */
    protected function _getAdminhtmlHelper()
    {
        return Mage::helper('adminhtml');
    }
}