<?php

class Application_Form_Article extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $artist = new Zend_Form_Element_Text('title');
        $artist->setLabel('Title')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
        
        $title = new Zend_Form_Element_Textarea('content');
        $title->setLabel('Content')
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($artist, $title, $submit));


    }
}
