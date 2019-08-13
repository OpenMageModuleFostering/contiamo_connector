<?php

abstract class Contiamo_Connector_Model_Admin_AbstractSource
{
    public function toOptionArray()
    {
        $attributes = $this->attributes();

        $options = array();
        foreach ($attributes as $attribute){
            $value = $attribute->getAttributecode();
            $label = $attribute->getFrontendLabel();

            if (static::$_ignoreEmptyLabels && !$label) continue;

            // if the label is empty use the titled version of value
            $label = $label ? $label : Mage::helper('contiamo')->snakeCaseToTitle($value);

            $options[] = array(
                'value' => $value,
                'label' => $label
            );
        }

        // sort options by label
        $sorter = function($a, $b) {
            return strcasecmp($a['label'], $b['label']);
        };
        usort($options, $sorter);

        // add default none option
        array_unshift($options, array('value' => '', 'label' => 'none'));

        return $options;
    }
}