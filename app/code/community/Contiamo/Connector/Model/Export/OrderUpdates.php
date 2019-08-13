<?php

class Contiamo_Connector_Model_Export_OrderUpdates extends Contiamo_Connector_Model_Export_Collection
{
    protected static $_fixedItemAttributes = array(
        // unique references
        'sale_reference' => 'increment_id',

        // order states
        'sale_status'   => 'contiamo_state',
        'custom_status' => 'status'
    );

    public static function customAttributes() {
        return array();
    }

    public function init($dateFrom, $pageNum, $pageSize)
    {
        $this->_items = Mage::getModel('sales/order')->getCollection()
            ->setCurPage($pageNum)
            ->setPageSize($pageSize);

        // date should have the format: yyyy-mm-dd
        if ($dateFrom) $this->_items->addAttributeToFilter('updated_at', array('from' => $dateFrom, 'date' => true));

        return $this;
    }

    protected function _exportItem($item)
    {
        return Mage::getModel('contiamo/export_order')->init($item);
    }
}