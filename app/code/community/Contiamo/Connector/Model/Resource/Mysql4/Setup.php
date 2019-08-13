<?php

class Contiamo_Connector_Model_Resource_Mysql4_Setup extends Mage_Core_Model_Resource_Setup
{
    public function configure()
    {
        $key = $this->_generateKey();

        // persist key
        Mage::app()->getStore()->setConfig('contiamo_settings/general/secret_key', $key);
        Mage::getConfig()->saveConfig('contiamo_settings/general/secret_key', $key);

        return true;
    }

    private function _generateKey()
    {
        return Mage::helper('core')->getRandomString(20);
    }
}