<?php
//app/code/local/Day/Two/controllers/RenderController.php
//setTemplate('path/relative/to/template/*.phtml')

//https://www.youtube.com/watch?v=5HDn717uw4U&t=3788s

class Day_Two_RenderController
    extends Mage_Core_Controller_Front_Action {
    public function blockAction()   {
        $this->getResponse()-setBody('Hello from RenderController.blockAction()');
    }

    public function overrideAction() {
        $blockHtml = $this->getLayout()
            ->createBlock('day_two/sample')
            ->toHtml();
        $this->getResponce()->setBody($blockHtml);
    }

    //asign template to a blockAction
    public function templateAction() {
        $blockHtml = $this->getLayout()
            ->createBlock('core/tamplate')
            ->setTemplate('day_two/random.phtml')  //app/design/fronend/base/template/day_two/random.phtml
            ->toHtml();
        $this->getResponce()->setBody($blockHtml);
    }
}