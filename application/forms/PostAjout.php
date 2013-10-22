<?php

/**
 * Form for adding a new post
 *
 * @author g.raoult@gmail.com
 * @see Application_Form_Post
 */

class Application_Form_PostAjout extends Application_Form_Post
{

    public function init()
    {
        parent::init();

        $articles = new Application_Model_DbTable_Articles();
        //$post = $articles->getArticlebyId($index);
        $this->setAction('');

        $pdate = date("d/m/Y");
        $this->pdate->setValue($pdate);
        $mdate = date("d/m/Y");
        $this->mdate->setValue($mdate);
        //$this->tags=$this->tags_form();
        $users =  new Application_Model_DbTable_Users();
        $auteurs = $users->fetchAll();
        $auts = array();

        /* UGLY SH*T*/

        foreach($auteurs as $user) {
                    $auts[$user->user_id] = $user->user_username;
        }
        $this->author->addMultiOptions($auts);
        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity()->user_username;
        $this->author->setValue($user);
        $this->status->addMultiOptions(array('0'=>'Brouillon','1'=>'Publié'));
        $this->status->setValue('1');
        $this->featured->addMultiOptions(array('0'=>'Non','1'=>'Oui'));
        $this->type->setValue('0');
        $this->type->addMultiOptions(array('0'=>'Post','1'=>'Page'));
    }
    
}

