<?php

/**
 * Search form
 *
 * @author g.raoult@gmail.com
 */

class Application_Form_Search extends Zend_Form
{

    /*
     * Init
     */
    public function init()
    {
        $view = $this->getView();
        $this->setName('form_recherche');
        $this->setAction($this->getView()->url(array(), 'recherche'));
        $this->setMethod('get');
        
        $terme = $this->terme_form();
        $submit = $this->submit_form();
        
        $elements = array($terme, $submit);
        $clean_elems =array();
        foreach ($elements as $elem){
            $this->mrProper($elem, array('keep_label' => false));
            $clean_elems[] = $elem;
        }
        $this->addElements($clean_elems);
    }

    /*
     * Titre article 
     */
    public function terme_form()
    {
        $terme = new Zend_Form_Element_Text('recherche');
        $terme
                ->setRequired(true)
                ->setAllowEmpty(false)
                ->setAttrib("required", "true")
                ->setAttrib("placeholder", "Rechercher");
        return($terme);
    }

    /*
     * Bouton Envoi
     */
    public function submit_form()
    {
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
                ->setLabel('');
        return($submit);
    }

    public function mrProper(&$element, $params = null  ){
        if($params['keep_label'])
            $element->addDecorator('Label', array('tag' => 'b'));
        else {
            $element->removeDecorator('Label');
            $element->removeDecorator('DlWrapper');
            $element->removeDecorator('DtDdWrapper');
            $element->removeDecorator('HtmlTag');
        }
    }
    
}

