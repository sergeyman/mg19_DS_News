<?php

class DS_News_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {

        $this->loadLayout();
        $this->renderLayout();

        /*
        //5 Использование шаблона для вывода данных
        $layoutHandles = $this->getLayout()->getUpdate()->getHandles();
        echo '<pre>' . print_r($layoutHandles, true) . '</pre>';
        */

        /*
         //Прямой доступ к базе данных
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $table = $resource->getTableName('dsnews/table_news');

        $select = $read->select()
            ->from($table, array('news_id', 'title', 'content', 'created'))
            ->order('created DESC');

        $news = $read->fetchAll($select);
        Mage::register('news', $news);

        $this->loadLayout();
        $this->renderLayout();
        */

        //Работа с БД через модели и коллекции
        // Название класса коллекции вычисляется из названия класса ресурса модели DS_News_Model_Resource_News + _Collection.
        // список новостей в виде ссылок, при клике на которые будет открываться страница с содержимым новости.

        //$params = $this->getRequest()->getParams();
        //$news = Mage::getModel('dsnews/news')->load($params['id']);
        //$news = Mage::getModel('dsnews/news')->getCollection();
        //echo (string) $news->getSelect();
        //var_dump($news);
        //echo 'Hello, ' . $news->getName();

        //$news = Mage::getModel('dsnews/news')->getCollection();
        //var_dump($news->getFirstItem()->getData());
        //var_dump($news->getLastItem()->getData());

        //var_dump($news->addAttributeToFilter('news_id', '2'));


        /*
        $news = Mage::getModel('dsnews/news')->getCollection()->setOrder('created', 'DESC');
        $viewUrl = Mage::getUrl('news/index/view');
        var_dump($viewUrl);
        echo "<br /> " . $viewUrl;

        echo '<h1>News3 (from IndexController)</h1>';
        foreach($news as $item) {
            echo '<h2><a href="' . $viewUrl . '?id=' . $item->getId() . '">' . $item->getTitle() . '</a></h2>';
        }
        */
    }

    // загрузка новости по id, полученному в запросе.
    public function viewAction() {
        $newsId = Mage::app()->getRequest()->getParam('id', 0);
        $news = Mage::getModel('dsnews/news')->load($newsId);

        /*
        $news = Mage::getModel('dsnews/news')->getCollection()
            ->addAttributeToSelect(array('news_id', 'title', 'content'))
            ->addAttributeToFilter('sku', array('like' => 'UX%'))
            ->load();
        */



        var_dump(($newsId));
        //print_r($news);
        if($news->getId() > 0) {
            echo '<h1>' . $news->getTitle() . '</h1>';
            echo '<div class="content">' . $news->getContent() . ', ' . $news->getCreated() . '</div>';
        }
        else {
            $this->_forward('noRoute');
        }
    }
}