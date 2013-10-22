<?php

/**
 * Form handling blog comments
 *
 * @author g.raoult@gmail.com
 */

class Application_Form_Comment extends Zend_Form
{

    /*
     * Init
     */
    public function init()
    {
        $this->setName('nouveau_commentaire');
        $this->setAction('#');
        $this->setMethod('post');
        
        $auteur = $this->auteur_form();
        $web = $this->web_form();
        $email = $this->email_form();
        $picture = $this->picture_form();
        $contenu = $this->content_form();
        $submit = $this->submit_form();
        
        $elements = array($auteur, $web, $email, $picture, $contenu, $submit);
        $clean_elems =array();
        foreach ($elements as $elem){
            $this->mrProper($elem, array('keep_label' => true));
            $clean_elems[] = $elem;
        }
        $this->addElements($clean_elems);
    }

    /*
     * auteur commentaire
     */
    public function auteur_form()
    {
        $auteur = new Zend_Form_Element_Text('auteur');
        $auteur
                ->setRequired(true)
                ->setAllowEmpty(false)
                ->getDecorator('label')->setOption('requiredSuffix', ' * ');
        $auteur->class = 'required';
        return($auteur);
    }

    /*
     * web commentaire
     */
    public function web_form()
    {
        $web = new Zend_Form_Element_Text('web');
        $web
                ->setRequired(false)
                ->setValue('http://')
                ->setAllowEmpty(true);
        return($web);
    }

    /*
     * email commentaire
     */
    public function email_form()
    {
        $email = new Zend_Form_Element_Text('email');
        $email
                ->setRequired(true)
                ->setAllowEmpty(false)
                ->setFilters(array('StringTrim'))
                ->setValidators(array('EmailAddress' ))
                ->addErrorMessage('L\'email est manquant ou non valide');
        $email->class = 'required email';
        return($email);
    }

    /*
     * picture comment
     */
    public function picture_form()
    {

        $picture = new Zend_Form_Element_Text('picture');
        $picture
                ->setRequired(false)
                ->setAllowEmpty(true);
        return($picture);
    }

    /*
     * contenu commentaire
     */
    public function content_form()
    {

        $contenu= new Zend_Form_Element_Textarea('contenu');
        $contenu
                ->setRequired(true)
                ->setAllowEmpty(false)
                ->setAttrib('cols', '40')
                ->setAttrib('rows', '7');
        $contenu->class = 'required';
        return($contenu);
    }

    /*
     * Bouton Envoi
     */
    public function submit_form()
    {
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submit_comment')
                ->setLabel('Poster ce commentaire');
        return($submit);
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

