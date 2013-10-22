<?php

/**
 * Form for editing a blog post
 *
 * @author g.raoult@gmail.com
 * @see Application_Form_Post
 */

class Application_Form_PostModif extends Application_Form_Post
{

    protected $ind;

    public function setInd($ind)
    {
        $this->ind = $ind;
    }

    public function init()
    {
        parent::init();
        
        $articles = new Application_Model_DbTable_Articles();
        //Article en paramètre
        $index = $this->ind;
        $post = $articles->getArticlebyId($index);
        $this->setAction('');
        $this->titre->setValue($post->article_title);
        $this->extrait->setValue($post->article_extract);
        $this->contenu->setValue($post->article_content);
        $this->status->setValue($post->article_status);
        $this->type->setValue($post->article_type);
        $date = strtotime($post->article_date);
        $pdate = date("d/m/Y", $date);
        $this->pdate->setValue($pdate);
        $date = strtotime($post->article_modified);
        $mdate = date("d/m/Y", $date);
        $this->mdate->setValue($mdate);

        $metas = $post->article_meta;
        $tags_value = array();
        foreach ($metas as $meta){
            $tags_value[] = $this->create_tags($meta);
        }

        foreach($tags_value as $tag_val){
            $name = $tag_val->getName();
            $this->tags->addSubForms(array(
                $name => $tag_val
            ));
        }
        
        $users =  new Application_Model_DbTable_Users();
        $auteurs = $users->fetchAll();
        $auts = array();
        /* UGLY*/
        foreach($auteurs as $user) {
                if ($post->user_username ==$user->user_username )
                    $auts[$user->user_id] = $user->user_username;
        }
        foreach($auteurs as $user) {
                if ($post->user_username !=$user->user_username )
                    $auts[$user->user_id] = $user->user_username;
        }
        $this->author->addMultiOptions($auts);
        $this->author->setValue($post->user_username);
        $this->status->addMultiOptions(array('0'=>'Brouillon','1'=>'Publié'));
        $this->featured->setValue($post->article_featured);
        $this->featured->addMultiOptions(array('0'=>'Non','1'=>'Oui'));
        $this->type->setValue($post->article_type);
        $this->type->addMultiOptions(array('0'=>'Post','1'=>'Page'));
    }

    /*
     * Metas article
     */
    public function create_tags($tag)
    {
            $tagAjout = new Application_Form_Tags();
            $tag_form = $tagAjout->buildBlock(array('id' => $tag->meta_id, 'tag' => $tag->meta_name));
            $tag_form->setName('block_'.$tag->meta_id);
            return $tag_form;        
    }
}

