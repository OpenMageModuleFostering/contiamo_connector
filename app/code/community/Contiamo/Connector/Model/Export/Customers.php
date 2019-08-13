<?php

class Contiamo_Connector_Model_Export_Customers extends Contiamo_Connector_Model_Export_Collection
{
    protected static $_itemAttributes = array(
        'user_reference'        => 'entity_id',
        'signup_date'           => 'created_at',
        'email'                 => 'email',
        'gender'                => 'gender',
        'group'                 => 'group',
        'birthdate'             => 'dob',
        'newsletter_subscribed' => 'newsletter_subscribed'
    );

    public function init($dateFrom, $pageNum, $pageSize)
    {
        $this->_items = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToSelect(array('dob', 'gender'))
            ->setCurPage($pageNum)
            ->setPageSize($pageSize);

        // date should have the format: yyyy-mm-dd
        if ($dateFrom) $this->_items->addAttributeToFilter('created_at', array('from' => $dateFrom, 'date' => true));

        return $this;
    }

    protected function _exportItem($item)
    {
        return Mage::getModel('contiamo/export_customer')->init($item);
    }
}
