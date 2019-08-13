<?php

class Contiamo_Connector_Model_Export_CustomAttributes
{
    public function export()
    {
        $header = array();
        foreach (range(1,10) as $i) {
            $header[] = 'custom_' . $i;
        }

        $customerLookup = $this->_generateLookup(Mage::getModel('contiamo/admin_customerSource')->attributes());
        $orderItemLookup = $this->_generateLookup(Mage::getModel('contiamo/admin_saleItemSource')->attributes());
        $orderLookup = $this->_generateLookup(Mage::getModel('contiamo/admin_saleSource')->attributes());

        $customerAttrs = array_values(Contiamo_Connector_Model_Export_Customers::customAttributes());
        $customerLabels = array();
        foreach ($customerAttrs as $attr) {
            $customerLabels[] = $customerLookup[$attr];
        }

        $orderItemAttrs = array_values(Contiamo_Connector_Model_Export_OrderItems::customAttributes());
        $orderItemLabels = array();
        foreach ($orderItemAttrs as $attr) {
            $orderItemLabels[] = $orderItemLookup[$attr];
        }

        $orderAttrs = array_values(Contiamo_Connector_Model_Export_Orders::customAttributes());
        $orderLabels = array();
        foreach ($orderAttrs as $attr) {
            $orderLabels[] = $orderLookup[$attr];
        }

        // add type information column to the beginning of the export data
        array_unshift($header, 'type', 'label');
        array_unshift($customerAttrs, 'customers', false);
        array_unshift($customerLabels, 'customers', true);
        array_unshift($orderItemAttrs, 'order_items', false);
        array_unshift($orderItemLabels, 'order_items', true);
        array_unshift($orderAttrs, 'orders', false);
        array_unshift($orderLabels, 'orders', true);

        return array(
            $header,
            $customerAttrs,
            $customerLabels,
            $orderItemAttrs,
            $orderItemLabels,
            $orderAttrs,
            $orderLabels
        );
    }

    private function _generateLookup($collection)
    {
        $lookup = array();
        foreach ($collection as $attribute) {
            $lookup[$attribute->getAttributecode()] = $attribute->getFrontendLabel();
        }
        return $lookup;
    }
}