<?php

class Contiamo_Connector_Model_Admin_SaleSource extends Contiamo_Connector_Model_Admin_AbstractSource
{
    protected static $_ignoreEmptyLabels = false;

    // Sales do not have attributes so fetch a list of all columns on the sales table.
    public function attributes()
    {
        $resource = Mage::getSingleton('core/resource');
        $connection = $resource->getConnection('core_read');
        $orderTbl = $resource->getTableName('sales/order');
        $cols = $connection->fetchAll('SHOW COLUMNS FROM ' . $orderTbl);

        $attributes = array();
        foreach ($cols as $col) {
            $field = $col['Field'];
            $label = Mage::helper('contiamo')->snakeCaseToTitle($field);
            $attribute = new Varien_Object();
            $attribute->setAttributecode($field);
            $attribute->setFrontendLabel($label);
            $attributes[] = $attribute;
        }

        return $attributes;
    }
}