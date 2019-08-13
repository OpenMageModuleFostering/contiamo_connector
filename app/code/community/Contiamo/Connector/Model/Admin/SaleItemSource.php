<?php

class Contiamo_Connector_Model_Admin_SaleItemSource extends Contiamo_Connector_Model_Admin_AbstractSource
{
    protected static $_ignoreEmptyLabels = false;

    public function attributes()
    {
        return Mage::getResourceModel('catalog/product_attribute_collection')->getItems();
    }
}