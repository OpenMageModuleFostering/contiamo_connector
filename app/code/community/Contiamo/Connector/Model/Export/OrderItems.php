<?php

class Contiamo_Connector_Model_Export_OrderItems extends Contiamo_Connector_Model_Export_Collection
{
    protected static $_fixedItemAttributes = array(
        'date' => 'created_at',

        // unique references
        'sale_reference' => 'increment_id',

        // product info
        'product_reference'     => 'sku',
        'product_sku'           => 'sku',
        'product_name'          => 'name',
        'product_brand'         => 'product_brand',
        'product_category'      => 'product_category',
        'product_subcategory'   => 'product_subcategory',
        'qty'                   => 'qty_ordered',

        // metrics
        'gross_total'       => 'row_total',
        'tax_total'         => 'tax_amount',
        'net_total'         => 'subtotal',
        'discount_total'    => 'discount_amount'
    );

    public static function customAttributes()
    {
        $customAttributes = Mage::getStoreConfig('contiamo_settings/sale_item_attributes');
        $sorter = function($a, $b) {
          $a = intval(str_replace('custom_', '', $a));
          $b = intval(str_replace('custom_', '', $b));
          return $a - $b;
        };
        uksort($customAttributes, $sorter);

        return $customAttributes;
    }

    public function init($dateFrom, $pageNum, $pageSize)
    {
        $this->_items = Mage::getModel('sales/order_item')->getCollection()
            // only get parent order items (visible order items)
            ->addFieldToFilter('parent_item_id', array('null' => true))
            ->setCurPage($pageNum)
            ->setPageSize($pageSize);

        // date should have the format: yyyy-mm-dd
        if ($dateFrom) $this->_items->addAttributeToFilter('created_at', array('from' => $dateFrom, 'date' => true));

        return $this;
    }

    protected function _exportItem($item)
    {
        return Mage::getModel('contiamo/export_orderItem')->init($item);
    }
}
