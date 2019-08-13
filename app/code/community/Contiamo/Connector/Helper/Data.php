<?php

class Contiamo_Connector_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getModuleIsEnabled()
    {
        return Mage::getStoreConfig('contiamo_settings/general/enabled');
    }

    public function getSecretKey()
    {
        return Mage::getStoreConfig('contiamo_settings/general/secret_key');
    }

    public function getVersion()
    {
        return Mage::getConfig()->getNode()->modules->Contiamo_Connector->version;
    }

    public function snakeCaseToTitle($s)
    {
        return ucwords(str_replace('_', ' ', $s));
    }
}