<?php

class Contiamo_Connector_Model_Export_Customer
{
    public $customer;

    public function init($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    public function getData($field)
    {
        switch ($field) {
            case 'gender':
                $attr = $this->customer->getResource()->getAttribute('gender');
                return $attr ? $attr->getSource()->getOptionText($this->customer->getData('gender')) : '';

            case 'group':
                $group = Mage::getModel('customer/group')->load($this->customer->getGroupId());
                return $group->getCustomerGroupCode();

            case 'newsletter_subscribed':
                $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($this->customer->getEmail());
                return Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED == $subscriber->getSubscriberStatus();

            default:
                return $this->customer->getData($field);
        }
    }
}