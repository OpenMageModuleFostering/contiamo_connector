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

    public function getSettings($key)
    {
        $customAttributes = Mage::getStoreConfig('contiamo_settings/' . $key);
        if (!$customAttributes || empty($customAttributes)) {
            $customAttributes = array();
            foreach (range(1,10) as $i) {
                $key = 'custom_' . $i;
                $customAttributes[$key] = '';
            }
        }

        $sorter = function($a, $b) {
          $a = intval(str_replace('custom_', '', $a));
          $b = intval(str_replace('custom_', '', $b));
          return $a - $b;
        };
        uksort($customAttributes, $sorter);

        return $customAttributes;
    }

    public function snakeCaseToTitle($s)
    {
        return ucwords(str_replace('_', ' ', $s));
    }
}