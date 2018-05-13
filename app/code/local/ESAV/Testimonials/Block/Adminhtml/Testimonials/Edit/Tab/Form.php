<?php
class ESAV_Testimonials_Block_Adminhtml_Testimonials_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("testimonials_form", array("legend"=>Mage::helper("testimonials")->__("Item information")));


//						$fieldset->addField("testimonials_id", "text", array(
//						"label" => Mage::helper("testimonials")->__("ID"),
//						"class" => "required-entry",
//						"required" => true,
//						"name" => "testimonials_id",
//						));
					
						$fieldset->addField("user_id", "text", array(
						"label" => Mage::helper("testimonials")->__("User ID"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "user_id",
						));
					
						$fieldset->addField("testimonial", "textarea", array(
						"label" => Mage::helper("testimonials")->__("Testimonial"),
						"name" => "testimonial",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getTestimonialsData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getTestimonialsData());
					Mage::getSingleton("adminhtml/session")->setTestimonialsData(null);
				} 
				elseif(Mage::registry("testimonials_data")) {
				    $form->setValues(Mage::registry("testimonials_data")->getData());
				}
				return parent::_prepareForm();
		}
}
