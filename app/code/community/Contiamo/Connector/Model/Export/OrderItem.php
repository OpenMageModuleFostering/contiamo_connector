<?php

class Contiamo_Connector_Model_Export_OrderItem
{
    public $orderItem, $order;

    public function init($orderItem)
    {
        $this->orderItem = $orderItem;
        $this->order = $orderItem->getOrder();
        $this->product = $orderItem->getProduct();
        $this->categoryIds = $this->product->getCategoryIds();

        return $this;
    }

    public function getData($field)
    {
        switch ($field) {
            case 'increment_id':
                return $this->order->getIncrementId();

            case 'product_brand':
                return $this->product->getAttributeText('manufacturer');

            case 'product_category':
                $category = $this->getCategory(0);
                return $category ? $category->getName() : '';

            case 'product_subcategory':
                $category = $this->getCategory(1);
                return $category ? $category->getName() : '';

            default:
                return $this->orderItem->getData($field);
        }
    }

    public function getCategory($index)
    {
        if (!array_key_exists($index, $this->categoryIds)) return '';

        $catId = $this->categoryIds[$index];
        return Mage::getModel('catalog/category')->load($catId);
    }
}