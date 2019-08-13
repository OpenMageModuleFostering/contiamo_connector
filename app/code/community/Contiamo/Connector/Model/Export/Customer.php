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

                // Use the same mapping used in the Newsletter/Subscriber/Grid
                switch ($subscriber->getStatus()) {
                    case Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE:
                        return Mage::helper('newsletter')->__('Not Activated');

                    case Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED:
                        return Mage::helper('newsletter')->__('Subscribed');

                    case Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED:
                        return Mage::helper('newsletter')->__('Unsubscribed');

                    case Mage_Newsletter_Model_Subscriber::STATUS_UNCONFIRMED:
                        return Mage::helper('newsletter')->__('Unconfirmed');

                    default:
                        // In any other case return a default
                        return 'Other';
                }

            default:
                return $this->customer->getData($field);
        }
    }

    public function getCustomData($field)
    {
        $data = $this->customer->getData($field);
        // if data is a number, try to get the attribute text (if it exists)
        if (ctype_digit($data)) {
            $attr = $this->customer->getResource()->getAttribute($field);
            try {
                $attrText = $attr->getSource()->getOptionText($data);
                // overwrite $data only if attrText has a value
                if ($attrText) {
                    if (is_array($attrText)) {
                        $data = $attrText['label'];
                    } else {
                        $data = $attrText;
                    }
                }
            } catch (Mage_Eav_Exception $e) {
            }
        }

        return $data;
    }
}