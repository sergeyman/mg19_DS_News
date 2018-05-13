<?php

class ESAV_Testimonials_IndexController
    extends Mage_Core_Controller_Front_Action
{
    public function IndexAction()
    {

        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle(
            $this->__("Testimonials")
        );
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb(
            "home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
            )
        );

        $breadcrumbs->addCrumb(
            "testimonials", array(
                "label" => $this->__("Testimonials"),
                "title" => $this->__("Testimonials")
            )
        );

        $this->renderLayout();

    }

    public function preDispatch()
    {
        parent::preDispatch();
        $action = $this->getRequest()->getActionName();
        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate(
            $this, $loginUrl
        )
        ) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    public function saveAction()
    {
        $post_data = $this->getRequest()->getPost();
        $testimonial = $post_data['testimonial'];
        $user_id =  Mage::getSingleton('customer/session')->getId();
        if ($post_data) {
            try {
                $model = Mage::getModel("testimonials/testimonials")
                    ->setTestimonial($testimonial)
                    ->setUserId($user_id)
                    ->save();

                Mage::getSingleton("core/session")->addSuccess(Mage::helper("adminhtml")->__("Testimonials was successfully saved"));
                Mage::getSingleton("core/session")->setTestimonialsData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/");
                    return;
                }
                $this->_redirect("*/*/");
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton("core/session")->addError($e->getMessage());
                Mage::getSingleton("core/session")->setTestimonialsData($this->getRequest()->getPost());
                $this->_redirect("*/*/");
                return;
            }
        }
        $this->_redirect("*/*/");
    }
}