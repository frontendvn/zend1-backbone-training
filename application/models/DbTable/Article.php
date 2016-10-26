<?php

class Application_Model_DbTable_Article extends Zend_Db_Table_Abstract {

    protected $_name = 'article';
    protected $_primary = 'id';

    public function addArticle($title, $content) {
        $data = array(
            'title' => $title,
            'content' => $content,
        );
        $this->insert($data);
    }

    public function deleteArticle($id) {
        $this->delete('id=' . $id);
    }
    
    public function getArticle($id) {
        $id=(int)$id;
        $row=$this->fetchRow('id='.$id);
        if(!$row){
            throw new Exception('Could nor find row $id');
        }
        return $row->toArray();
    }
     
    public function searchArticle($id) {
         $id=(int)$id;
        $row=$this->fetchRow('title='.$id);
        if(!$row){
            throw new Exception('Could nor find row $id');
        }
        return $row->toArray();
    }
    public function updateArticle($id, $title, $content) {
        $data = array('title' => $title, 'content' => $content);
        $this->update($data, 'id=' . $id);
    }
    
}
