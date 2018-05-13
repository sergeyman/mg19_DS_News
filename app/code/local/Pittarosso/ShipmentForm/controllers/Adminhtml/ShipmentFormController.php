<?php

class Pittarosso_ShipmentForm_Adminhtml_ShipmentFormController extends Mage_Adminhtml_Controller_Action
{
    public function downloadAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');

        /** @var Mage_Sales_Model_Order_Shipment $shipment */
        $shipment = Mage::getModel('sales/order_shipment');
        $shipment->load($id);

        if ($shipment->hasData()) {
            $response = $this->getResponse();
            $response->setHeader('Content-Type', 'application/pdf');
            $response->setBody($shipment->getShippingLabel());
        } else {
            Mage::throwException('Shipment not found');
        }
    }

    public function pdfinvoicesAction()
    {
        $invoiceId = $this->getRequest()->getParam('id');
        if ($invoiceId) {
            $invoices = Mage::getResourceModel('sales/order_invoice_collection')
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', $invoiceId)
                ->load();

            if (!isset($pdf)){
                $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf($invoices);
            } else {
                $pages = Mage::getModel('sales/order_pdf_invoice')->getPdf($invoices);
                $pdf->pages = array_merge ($pdf->pages, $pages->pages);
            }

            return $this->_prepareDownloadResponse('invoice'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                '.pdf', $pdf->render(), 'application/pdf');
        }

        $this->_redirect('*/*/');
    }
}
