<?php

class Contiamo_Connector_Model_Export_Collection extends Mage_Core_Model_Abstract
{
    protected $_items;

    private $_exportData;

    public function export()
    {
        // memoize
        if ($this->_exportData) return $this->_exportData;

        $itemAttrs = static::$_itemAttributes;

        // set header row
        $this->_exportData = array(array_keys($itemAttrs));

        // add row for each item
        foreach ($this->_items as $item) {
            $itemData = array();

            // decorate the item
            $exportItem = $this->_exportItem($item);

            // add item value for each attribute
            foreach ($itemAttrs as $attr) {
                $itemData[] = $exportItem->getData($attr);
            }

            $this->_exportData[] = $itemData;
        }

        return $this->_exportData;
    }
}