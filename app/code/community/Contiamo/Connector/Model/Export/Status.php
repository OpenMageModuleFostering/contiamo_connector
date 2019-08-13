<?php

class Contiamo_Connector_Model_Export_Status
{
    protected $_orders, $_orderItems, $_customers;

    public function export()
    {
        $data = array(
            'order_count'       => $this->_orders->getSize(),
            'order_items_count' => $this->_orderItems->getSize(),
            'customer_count'    => $this->_customers->getSize(),
            'version'           => Mage::helper('contiamo')->getVersion()
        );

        $header = array_keys($data);
        $row = array_values($data);

        return array($header, $row);
    }

    public function init($dateFrom)
    {
        $this->_orders      = Mage::getModel('sales/order')->getCollection();
        $this->_orderItems  = Mage::getModel('sales/order_item')->getCollection();
        $this->_customers   = Mage::getModel('customer/customer')->getCollection();

        // date should have the format: yyyy-mm-dd
        if ($dateFrom) $this->_setDateFilter($dateFrom);

        return $this;
    }

    private function _setDateFilter($dateFrom)
    {
        $collections = array($this->_orders, $this->_orderItems, $this->_customers);

        foreach ($collections as $collection) {
            $collection->addAttributeToFilter('created_at', array('from' => $dateFrom, 'date' => true));
        }
    }
}