<?php

class Contiamo_Connector_Model_Export_Orders extends Contiamo_Connector_Model_Export_Collection
{
    protected static $_itemAttributes = array(
        'date' => 'created_at',

        // unique references
        'sale_reference' => 'increment_id',
        'user_reference' => 'customer_id',

        // user info
        'user_email'        => 'customer_email',
        'user_firstname'    => 'customer_firstname',
        'user_lastname'     => 'customer_lastname',

        // order states
        'sale_status'   => 'contiamo_state',
        'custom_status' => 'status',

        // payment & shipping methods
        'payment_method'  => 'payment_method',
        'shipping_method' => 'shipping_method',

        // discount codes
        'discount_code' => 'coupon_code',
        'discount_name' => '',

        // billing address
        'billing_address_postal_code'   => 'billing_address_postcode',
        'billing_address_city'          => 'billing_address_city',
        'billing_address_state'         => 'billing_address_region',
        'billing_address_country'       => 'billing_address_country_id',

        // shipping address
        'shipping_address_postal_code'  => 'shipping_address_postcode',
        'shipping_address_city'         => 'shipping_address_city',
        'shipping_address_state'        => 'shipping_address_region',
        'shipping_address_country'      => 'shipping_address_country_id',

        // metrics
        'gross_total'       => 'grand_total',
        'tax_total'         => 'tax_amount',
        'net_total'         => 'subtotal',
        'shipping_total'    => 'shipping_amount',
        'discount_total'    => 'discount_amount'
    );

    public function init($dateFrom, $pageNum, $pageSize)
    {
        $this->_items = Mage::getModel('sales/order')->getCollection()
            ->setCurPage($pageNum)
            ->setPageSize($pageSize);

        // date should have the format: yyyy-mm-dd
        if ($dateFrom) $this->_items->addAttributeToFilter('created_at', array('from' => $dateFrom, 'date' => true));

        return $this;
    }

    protected function _exportItem($item)
    {
        return Mage::getModel('contiamo/export_order')->init($item);
    }
}