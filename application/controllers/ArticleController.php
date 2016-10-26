<?php

class  ArticleController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function index1Action()
    {
        $model = new Application_Model_DbTable_Article();
        $articles = $model->fetchAll();        
        $this->view->articles = $articles;       
        
    }
     public function indexAction()
    {
        $model = new Application_Model_DbTable_Article();
        $articles = $model->fetchAll();        
        $this->view->articles = $articles;       
        
    }
    public function searchAction()
    {       
        if($this->getRequest()->isPost()){
           $title =  $this->getRequest()->getPost('search');
           $articles =new Application_Model_DbTable_Article();
           $kq =  $articles->searchArticle($title);
           $this->view->search = $kq;
            
        }
           
        
    }
    public function viewAction()
    {
        $model = new Application_Model_DbTable_Article();
        $articles = $model->fetchAll();
                
        $this->_helper->json($articles);        
        
    }
    public function syncAction(){
        
        $rawJSONString = file_get_contents('php://input');
        
        $item = json_decode($rawJSONString);
        var_dump($_SERVER);die;
        $articles =new Application_Model_DbTable_Article();
        if($_SERVER['REQUEST_METHOD'] ==="POST"){
            $articles->addArticle($item->title,$item->content);
        }else if($_SERVER['REQUEST_METHOD'] ==="PUT"){
            
            $articles->updateArticle($item->id,$item->title,$item->content);
        }else if($_SERVER['REQUEST_METHOD']==="DELETE"){
            $articles->deleteArticle($item->id);
        }
       
        exit();
    }

    public function addAction()
    {

        $form = new Application_Form_Article();
        $this->view->form=$form;
        if($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost(); 
            $form->isValid($formData);  
            $title = $form->getValue('title');
            $content = $form->getValue('content');
            $articles =new Application_Model_DbTable_Article();
            $articles->addArticle($title,$content);
            $this->_helper->redirector('index1');
        }
    }
    
    public function deleteAction(){
        $id = $this->_getParam('id');
        if($this->getRequest()->isPost()){
            $del = $this->getRequest()->getPost('del');
            if($del=="Yes"){
                $articles =new Application_Model_DbTable_Article();
                $articles->deleteArticle($id);
                
            }
            $this->_helper->redirector('index1');
        }
    }
    
    public function editAction(){
       $form = new Application_Form_Article();
       $form->submit->setLabel('Save');
       $this->view->form = $form;
        $id = $this->_getParam('id');
        if ($this->getRequest()->isPost()) {  
            $formData = $this->getRequest()->getPost(); 
            $form->isValid($formData); 
            $title = $form->getValue('title');                
            $content = $form->getValue('content');
            $articles = new Application_Model_DbTable_Article();
            $articles->updateArticle($id,$title,$content);
            $this->_helper->redirector('index1');
        } else {          
            if ($id > 0) {
                $articles = new Application_Model_DbTable_Article();
                $form->populate($articles->getArticle($id));
            }
        }
        
    }
    
}
