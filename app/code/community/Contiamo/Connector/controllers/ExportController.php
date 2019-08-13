<?php

class Contiamo_Connector_ExportController extends Mage_Core_Controller_Front_Action
{
    public function ordersAction()
    {
        try {
            $this->_authorize();

            $request = $this->getRequest();
            $dateFrom = $request->getParam('date_from');
            $pageNum = $request->getParam('page');
            $pageSize = $request->getParam('page_size');

            $orders = Mage::getModel('contiamo/export_orders')->init($dateFrom, $pageNum, $pageSize);
            $response = $orders->export();

            $this->_respondCsv($response);
        } catch (Exception $e) {
            $this->_logEx($e);
        }
    }

    public function orderUpdatesAction()
    {
        try {
            $this->_authorize();

            $request = $this->getRequest();
            $dateFrom = $request->getParam('date_from');
            $pageNum = $request->getParam('page');
            $pageSize = $request->getParam('page_size');

            $orders = Mage::getModel('contiamo/export_orderUpdates')->init($dateFrom, $pageNum, $pageSize);
            $response = $orders->export();

            $this->_respondCsv($response);
        } catch (Exception $e) {
            $this->_logEx($e);
        }
    }

    public function orderItemsAction()
    {
        try {
            $this->_authorize();

            $request = $this->getRequest();
            $dateFrom = $request->getParam('date_from');
            $pageNum = $request->getParam('page');
            $pageSize = $request->getParam('page_size');

            $orderItems = Mage::getModel('contiamo/export_orderItems')->init($dateFrom, $pageNum, $pageSize);
            $response = $orderItems->export();

            $this->_respondCsv($response);
        } catch (Exception $e) {
            $this->_logEx($e);
        }
    }

    public function customersAction()
    {
        try {
            $this->_authorize();

            $request = $this->getRequest();
            $dateFrom = $request->getParam('date_from');
            $pageNum = $request->getParam('page');
            $pageSize = $request->getParam('page_size');

            $orderItems = Mage::getModel('contiamo/export_customers')->init($dateFrom, $pageNum, $pageSize);
            $response = $orderItems->export();

            $this->_respondCsv($response);
        } catch (Exception $e) {
            $this->_logEx($e);
        }
    }

    public function customAttributesAction()
    {
        try {
            $this->_authorize();

            $attributes = Mage::getModel('contiamo/export_customAttributes');
            $response = $attributes->export();

            $this->_respondCsv($response);
        } catch (Exception $e) {
            $this->_logEx($e);
        }
    }

    public function statusAction()
    {
        try {
            $this->_authorize();

            $request = $this->getRequest();
            $dateFrom = $request->getParam('date_from');

            $status = Mage::getModel('contiamo/export_status')->init($dateFrom);
            $response = $status->export();

            $this->_respondCsv($response);
        } catch (Exception $e) {
            $this->_logEx($e);
        }
    }

    private function _respondCsv($data)
    {
        $csv = new Varien_File_Csv();
        $handle = 'php://output';
        $csv->saveData($handle, $data);
    }

    private function _authorize()
    {
        $helper = Mage::helper('contiamo');

        if ($helper->getModuleIsEnabled()) {
            $key = trim($helper->getSecretKey());

            // request param is only accepted in POST
            $paramKey = trim($this->getRequest()->getPost('auth_key'));

            if ($key != '' && $key == $paramKey) {
                // allow the request if the secret key is not empty and matches the request param
                return true;
            }
        }

        // reject this request
        $this->getResponse()->setHttpResponseCode(401);
        throw new Exception('Invalid export authorization');
    }

    private function _logEx($e)
    {
        Mage::logException($e);
    }
}
