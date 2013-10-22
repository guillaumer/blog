<?php

/**
 * Form handling input fields for a blog post
 *
 * @author g.raoult@gmail.com
 */

class Application_Form_Post extends Zend_Form
{

    /*
     * Init
     */
    public function init()
    {
        $this->setName('nouvel_article');
        $this->setAction('');
        $this->setMethod('post');
        
        $titre = $this->titre_form();
        $extrait = $this->extract_form();
        $contenu = $this->content_form();
        $type = $this->type_form();
        $author = $this->author_form();
        $status = $this->status_form();
        $featured = $this->featured_form();
        $pdate = $this->pdate_form();
        $mdate = $this->mdate_form();
        
        $tags = $this->tags_form();
        $submit = $this->submit_form();
        
        $elements = array($titre, $extrait, $contenu, $author, $type, $status, $featured, $pdate, $mdate, $submit);
        $clean_elems =array();
        foreach ($elements as $elem){
            $this->mrProper($elem, array('keep_label' => false));
            $clean_elems[] = $elem;
        }
        $this->mrProper($tags);

        $tags = $this->addSubForms(array(
            'tags' => $tags
        ));
        
        $this->addElements($clean_elems);

    }

    /*
     * Titre article 
     */
    public function titre_form()
    {
        $titre = new Zend_Form_Element_Text('titre');
        $titre
                ->setAttrib('class', 'noteditable')
                ->setRequired(true)
                ->setAllowEmpty(false);
        return($titre);
    }

    /*
     * Extrait article
     */
    public function extract_form()
    {
        $extrait = new Zend_Form_Element_Textarea('extrait');
        $extrait
                ->setLabel('Extrait')
                ->setRequired(true)
                ->setAllowEmpty(false);
        return($extrait);
    }

    /*
     * Contenu article
     */
    public function content_form()
    {
        $contenu = new Zend_Form_Element_Textarea('contenu');
        $contenu
                ->setLabel('Contenu')
                ->setRequired(true)
                ->setAllowEmpty(false);
        return($contenu);
    }
    
    /*
     * type de l'article
     */
    public function type_form()
    {

        $type = new Zend_Form_Element_Select('type');
        $type->setRequired(false);
        return($type);
    }

    /*
     * Auteur de l'article
     */
    public function author_form()
    {

        $author = new Zend_Form_Element_Select('author');
        $author->setRequired(false);
        return($author);
    }

    /*
     * Status de l'article
     */
    public function status_form()
    {

        $status = new Zend_Form_Element_Select('status');
        $status->setRequired(false);
        return($status);
    }

     /*
     * Featured
     */
    public function featured_form()
    {

        $featured = new Zend_Form_Element_Select('featured');
        $featured->setRequired(false);
        return($featured);
    }

    /*
     * Date publication
     */
    public function pdate_form()
    {

        $pdate = new Zend_Form_Element_Text('pdate');
        $pdate->setRequired(false);
        return($pdate);
    }

    /*
     * Date modif
     */
    public function mdate_form()
    {

        $mdate = new Zend_Form_Element_Text('mdate');
        $mdate->setRequired(false);
        return($mdate);
    }
    
    /*
     * Tags article
     */
    public function tags_form()
    {
        $tags = new Application_Form_Tags();
        return($tags);
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
        else
            $element->removeDecorator('Label');
        $element->removeDecorator('DtDdWrapper');
        $element->removeDecorator('HtmlTag');
    }
    
}

