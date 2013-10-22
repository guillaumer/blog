<?php

/**
 * Form for handling post tags
 *
 * @author g.raoult@gmail.com
 * @see forms/PostAjout
 * @see forms/PostModif
 */

class Application_Form_Tags extends Zend_Form_SubForm
{

    /*
     * Init
     */
    public function init($options = null, $form_params = null)
    {
        $this->setName('tags-detail');
        $this->setAttrib('class', 'tag_add');
        //Bouton ajout
        $tag_add = new Zend_Form_Element_Text('tag_add');
        $tag_add->setValue("");
        $tag_submit = new Zend_Form_Element_Submit('submit');
        $tag_submit->setLabel('');
        $tag_add->setAttrib('class', 'tag_add');
        $this->mrProper($tag_add);
        $this->mrProper($tag_submit);
        
        $tags = $this->addElements(array($tag_add, $tag_submit));
        return $tags;
    }

    public function buildBlock($params)
    {
        $id = $params['id'];
        $val = $params['tag'];
        $subform = new Zend_Form_SubForm();
        $subform->removeDecorator('DtDdWrapper');
        $subform->removeDecorator('HtmlTag');
        

        //Input text d'un tag
        $tag = new Zend_Form_Element_Hidden('tags_'.$id);
        $tag
                ->setRequired(false)
                ->setAttrib('class', 'tag_name')
		->setAttrib('title', 'tag name')
                ->setAllowEmpty(true)
                ->setValue($val);
        $this->mrProper($tag);
        return $subform->addElements(array($tag));
    }

    public function mrProper(&$element, $params = null  ){
        if($params['keep_label'])
            $element->addDecorator('Label', array('tag' => 'b'));
        else
            $element->removeDecorator('Label');
        $element->removeDecorator('DtDdWrapper');
        $element->removeDecorator('HtmlTag');
    }
    
}

