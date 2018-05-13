<?php

class ESAV_Testimonials_Adminhtml_TestimonialsController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("testimonials/testimonials")->_addBreadcrumb(Mage::helper("adminhtml")->__("Testimonials  Manager"),Mage::helper("adminhtml")->__("Testimonials Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Testimonials"));
			    $this->_title($this->__("Manager Testimonials"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Testimonials"));
				$this->_title($this->__("Testimonials"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("testimonials/testimonials")->load($id);
				if ($model->getId()) {
					Mage::register("testimonials_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("testimonials/testimonials");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Testimonials Manager"), Mage::helper("adminhtml")->__("Testimonials Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Testimonials Description"), Mage::helper("adminhtml")->__("Testimonials Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("testimonials/adminhtml_testimonials_edit"))->_addLeft($this->getLayout()->createBlock("testimonials/adminhtml_testimonials_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("testimonials")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Testimonials"));
		$this->_title($this->__("Testimonials"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("testimonials/testimonials")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("testimonials_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("testimonials/testimonials");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Testimonials Manager"), Mage::helper("adminhtml")->__("Testimonials Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Testimonials Description"), Mage::helper("adminhtml")->__("Testimonials Description"));


		$this->_addContent($this->getLayout()->createBlock("testimonials/adminhtml_testimonials_edit"))->_addLeft($this->getLayout()->createBlock("testimonials/adminhtml_testimonials_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("testimonials/testimonials")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Testimonials was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setTestimonialsData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setTestimonialsData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("testimonials/testimonials");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('testimonials_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("testimonials/testimonials");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'testimonials.csv';
			$grid       = $this->getLayout()->createBlock('testimonials/adminhtml_testimonials_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'testimonials.xml';
			$grid       = $this->getLayout()->createBlock('testimonials/adminhtml_testimonials_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
