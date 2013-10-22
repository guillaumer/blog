<?php

/**
 * Login form (seriously ?)
 *
 * @author g.raoult@gmail.com
 */

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setName('login_user');

        $username = new Application_Form_Text('username','Utilisateur');
        $password = new Zend_Form_Element_Password('password');

        $password->setLabel('Mot de passe')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim');

        $remember = new Zend_Form_Element_Checkbox('remember');
        $remember->setLabel('Se souvenir de moi')
                 ->setAttrib('id', 'remember_me')
                 ->setChecked(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitlogin')
                ->setLabel('');

        $elements = array($username,$password,$remember,$submit);
                $clean_elems =array();
        foreach ($elements as $elem){
            $this->mrProper($elem, array('keep_label' => false));
            $clean_elems[] = $elem;
        }
        $this->addElements($clean_elems);

        $this->setDecorators(array(
                'FormElements',
                array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
                'Form'
        ));
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

