<?php

class Pittarosso_ShipmentForm_Model_Shipment_Sda extends Tbuy_Sda_Model_Shipment_Sda
{
    /**
     * @param array $qtys
     * @param int $packageCount
     * @return array
     *
     * @throws Exception
     */
    public function getLimitedPackages(array $qtys, $packageCount = 1)
    {
        if ($packageCount < 1) {
            return [];
        }

        $packages = [];
        for ($i = 0; $i < $packageCount; ++$i) {
            $packages[$i + 1] = [
                'params' => $this->_getPackageParams(),
                'items' => []
            ];
        }

        $numberOfProducts = array_sum($qtys);

        if ($packageCount > $numberOfProducts) {
            Mage::throwException('Number of package is more than necessary');
        } else {
            $itemsCounter = 0;

            // Process each itemId
            foreach ($qtys as $itemId => $itemQty) {
                // Process all items for current itemId
                for ($itemIndex = 0; $itemIndex < $itemQty; $itemIndex++) {
                    $packageNumber = $itemsCounter % $packageCount + 1;

                    $item = Mage::getModel('sales/order_item')->load($itemId);
                    $packageItem = $this->_getItemPackageRow($item, 1);
                    $packages[$packageNumber]['items'][$packageItem['order_item_id'] . '_' . $itemIndex] = $packageItem;
                    $packages[$packageNumber]['params']['weight'] += $packageItem['weight'];
                    $packages[$packageNumber]['params']['customs_value'] += $packageItem['price'];
                    unset($item);

                    $itemsCounter++;
                }
            }
        }

        return $packages;
    }

    /**
     * @param Mage_Sales_Model_Order_Item $item
     * @param int $qty
     *
     * @return array
     */
    protected function _getItemPackageRow($item, $qty)
    {
        return [
            'qty' => $qty,
            'custom_value' => $item->getRegularPrice(),
            "price"=> ($item->getRowTotalInclTax() - $item->getDiscountAmount()) / $item->getQtyOrdered(),
            'name' => $item->getName(),
            'weight' => (float)$item->getWeight(),
            'product_id' => $item->getProductId(),
            'order_item_id' => $item->getId()
        ];
    }
    /**
     * @return array
     */
    protected function _getPackageParams()
    {
        return [
            'container' => '',
            'weight' => 0,
            'customs_value' => 0,
            'lenght' => 1,
            'width' => 1,
            'height' => 1,
            'weight_units' => 'KILOGRAM',
            'dimension_units' => 'CENTIMETER',
            'content_type' => '',
            'content_type_order' => '',
        ];
    }
}
