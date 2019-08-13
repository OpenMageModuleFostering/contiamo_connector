<?php

class Contiamo_Connector_Model_Export_Order
{
    public $order, $billing_address, $shipping_address;

    // map magento order states to contiamo states
    private static $_orderStateMap = array(
        'new'             => 'pending',
        'pending_payment' => 'pending',
        'processing'      => 'pending',
        'holded'          => 'pending',
        'payment_review'  => 'pending',
        'complete'        => 'complete',
        'closed'          => 'complete',
        'canceled'        => 'cancelled'
    );

    public function init($order)
    {
        $this->order = $order;
        $this->shipping_address = $this->order->getShippingAddress();
        $this->billing_address = $this->order->getBillingAddress();

        return $this;
    }

    public function getData($field)
    {
        switch ($field) {
            case 'contiamo_state':
                return self::$_orderStateMap[$this->order->getState()];

            case 'coupon_name':
                if ($code = $this->order->getCouponCode()) {
                    if (version_compare(Mage::getVersion(), '1.4.1', '>=')) {
                        $coupon = Mage::getModel('salesrule/coupon')->load($code, 'code');
                        $rule = Mage::getModel('salesrule/rule')->load($coupon->getRuleId());
                        return $rule->getName();

                    } else {
                        $rule = Mage::getModel('salesrule/rule')->load($code, 'coupon_code');
                        return $rule->getName();
                    }
                }
                return '';

            case 'payment_method':
                return $this->order->getPayment()->getMethod();

            case strstr($field, 'shipping_address_'):
                $shipping_field = str_replace('shipping_address_', '', $field);
                return $this->shipping_address->getData($shipping_field);

            case strstr($field, 'billing_address_'):
                $billing_field = str_replace('billing_address_', '', $field);
                return $this->billing_address->getData($billing_field);

            default:
                return $this->order->getData($field);
        }
    }

    public function getCustomData($field)
    {
        return $this->order->getData($field);
    }
}