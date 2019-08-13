<?php

class Contiamo_Connector_Model_Admin_CustomerSource extends Contiamo_Connector_Model_Admin_AbstractSource
{
    // ignore empty labels as we don't want to allow system fields (e.g. password hash)
    protected static $_ignoreEmptyLabels = true;

    public function attributes()
    {
        return Mage::getModel('customer/entity_attribute_collection')->getItems();
    }
}