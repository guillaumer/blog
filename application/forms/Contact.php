<?php

/**
 * Contact form
 *
 * @author g.raoult@gmail.com
 */

class Application_Form_Contact extends Zend_Form
{

    /*
     * Init
     */
    public function init()
    {
        $view = $this->getView();
        $this->setName('contact');
        $this->setAction($this->getView()->url(array(), 'contact'));
        $this->setMethod('post');

        $nom = $this->nom_form();
        $email= $this->email_form();
        $message = $this->message_form();
        $submit = $this->submit_form();

        $elements = array($nom, $email, $message, $submit);
        $clean_elems =array();
        foreach ($elements as $elem){
            $this->mrProper($elem, array('keep_label' => false));
            $clean_elems[] = $elem;
        }
        $this->addElements($clean_elems);
    }
    
    /*
     * Auteur
     */
    public function nom_form()
    {
        $nom= new Zend_Form_Element_Text('nom');
        $nom
                ->setRequired(true)
                ->setAllowEmpty(false)
                ->setAttrib("required", "true")
                ->setLabel('Nom')
                ->setAttrib("placeholder", "Nom")
                ->setFilters(array('StringTrim'))
                ->addFilter('StripTags')
                ->addErrorMessage('Nom manquant');
        return($nom);
    }

    /*
     * EMail de l'auteur
     */
    public function email_form()
    {
        $email= new Zend_Form_Element_Text('email');
        $email
                ->setRequired(true)
                ->setAllowEmpty(false)
                ->setAttrib("required", "true")
                ->setLabel('E-mail')
                ->setAttrib("placeholder", "E-mail")
                ->setFilters(array('StringTrim'))
                ->setValidators(array('EmailAddress' ))
                ->addErrorMessage('L\'email est manquant ou non valide');
        return($email);
    }

	/*
     * Contenu du message
     */
    public function message_form()
    {
        $message= new Zend_Form_Element_Textarea('message');
        $message
                ->setRequired(true)
                ->setAllowEmpty(false)
                ->setLabel('Message')
                ->setAttrib('cols', '40')
                ->setAttrib('rows', '7')
                ->setAttrib("required", "true")
                ->setAttrib("placeholder", "Message");
        return($message);
    }

    /*
     * Bouton Envoi
     */
    public function submit_form()
    {
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitcontact')
                ->setLabel('');
        return($submit);
    }
	

    public function mrProper(&$element, $params = null  ){
        if($params['keep_label'])
            $element->addDecorator('Label', array('tag' => 'b'));
        else {
            $element->removeDecorator('DlWrapper');
            $element->removeDecorator('DtDdWrapper');
            $element->removeDecorator('HtmlTag');
        }
    }
}

