<?php

class ASV_Newsmodule_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        echo "<h2>Hello from indexAction();</h2>";

        //* using template
        $this->loadLayout();
        $this->renderLayout();

        //var_dump(Mage::getModel(Mage::getStoreConfigFlag('newsmodule/general/enabled')));
        //$myModule = Mage::getModel('newsmodule/newsmodule');
        //print_r($myModule);


        // Check to see if module is enabled!   //*????????????????????   //disabled now (return false)
        //if (Mage::getStoreConfigFlag('newsmodule/general/enabled')) {
            //print_r($this->getRequest()->getParams());            // outputs customer data
        /*
            $params = $this->getRequest()->getParams();
            $customer = Mage::getModel('customer/customer')->load($params['id']);
            Mage::log($customer->getData());
            Mage::log(get_class_methods($customer));                //writes available customer methods
            //echo (get_class_methods($customer));
            echo 'Hello, ' . $customer->getName();
        //}
        //else {
        //    echo "<h2>This module is disabled.</h2>";

            //$myModule = Mage::getModel('newsmodule/newsmodule');
            //print_r($myModule);
        //}

        $myModule = Mage::getModel('newsmodule/newsmodule');
        print_r($myModule);
        */
    }
}