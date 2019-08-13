<?php

class Contiamo_Connector_Model_Export_Status
{
    protected $_orders, $_orderUpdates, $_orderItems, $_customers;

    public function export()
    {
        $data = array(
            'order_count'         => $this->_orders->getSize(),
            'order_updates_count' => $this->_orderUpdates->getSize(),
            'order_items_count'   => $this->_orderItems->getSize(),
            'customer_count'      => $this->_customers->getSize(),
            'version'             => Mage::helper('contiamo')->getVersion(),
            'mage_version'        => Mage::getVersion()
        );

        $header = array_keys($data);
        $row = array_values($data);

        return array($header, $row);
    }

    public function init($dateFrom)
    {
        $this->_orders       = Mage::getModel('sales/order')->getCollection();
        $this->_orderUpdates = Mage::getModel('sales/order')->getCollection();
        $this->_orderItems   = Mage::getModel('sales/order_item')->getCollection();
        $this->_customers    = Mage::getModel('customer/customer')->getCollection();

        $createdAtCollections = array($this->_orders, $this->_orderItems, $this->_customers);
        foreach ($createdAtCollections as $coll) {
            $this->_setDateFilter($coll, 'created_at', $dateFrom);
        }

        $this->_setDateFilter($this->_orderUpdates, 'updated_at', $dateFrom);

        return $this;
    }

    private function _setDateFilter($collection, $dateField, $dateFrom)
    {
        $collection->addAttributeToFilter($dateField, array('from' => $dateFrom, 'date' => true));
    }
}