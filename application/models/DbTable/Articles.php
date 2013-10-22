<?php

/**
 * Model handling blog posts
 *
 * @author g.raoult@gmail.com
 */
class Application_Model_DbTable_Articles extends Zend_Db_Table_Abstract
{
    //Nom de la table
    protected $_name = 'cm_article';
    protected $_primary = 'article_id';
    protected $_dependentTables = array('MetasRelationship');

    /*
     * Récupération de l'ID d'un article d'après son nom
     * @param : nom d'un article
     */
    public function getId($name) {
        $query = $this->select()->from($this->_name)
                    ->where('cm_article.article_name IN (?)', $name);
        $result = $this->fetchRow($query);
        if (!$result) {
            throw new Exception("Could not find row $name");
        }
        return $result->article_id;
    }
    
    /*
     * Récupération des articles
     * @param = SEO friendly nom d'un article (article_name)
     */
    public function getArticle($name) {
        //Adapters externes
        $metas = new Application_Model_DbTable_Metas();
        $metasrs = new Application_Model_DbTable_MetasRelationship();
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)
                ->join('cm_user', 'cm_user.user_id = article_author')
                ->where('article_name IN (?)', $name)
                ->where('article_status = 1');
        $result = $this->fetchRow($query);
        $tags = $result->findManyToManyRowset($metas, $metasrs);
        //Assignation des metas à la colonne article_meta de la table
        $result->__set('article_meta', $tags);
        if (!$result) {
            throw new Exception("Could not find row $name");
        }
        return $result;
    }

    /*
     * Récupération des articles
     * @param = id d'un article
     */
    public function getArticlebyId($id) {
        //Adapters externes
        $metas = new Application_Model_DbTable_Metas();
        $metasrs = new Application_Model_DbTable_MetasRelationship();
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)
                ->join('cm_user', 'cm_user.user_id = article_author')
                ->where('article_id IN (?)', $id);
        $result = $this->fetchRow($query);
		if ($result) {
			$tags = $result->findManyToManyRowset($metas, $metasrs);
			$result->__set('article_meta', $tags);
		}
        //Assignation des metas à la colonne article_meta de la table
        if (!$result) {
            throw new Exception("Could not find row $id");
        } else {
            return $result;
        }
    }

    /*
     * Récupère plusieurs articles
     * @param $featured:array = paramètre article important. 1 = featured;
     * @param $published:array = Article publié ou non. 1 = publié;
     * @param $limit:boolean, int = nombre d'article souhaité. default = tous;
     * @param $type:int, post ou page. Default : 0 (post)
     */
    public function getAllArticles($featured, $published, $limit = false, $type = '0') {
        //Adapters externes
        $metas = new Application_Model_DbTable_Metas();
        $metasrs = new Application_Model_DbTable_MetasRelationship();
        //Requête
        $query = $this->select()->distinct()->setIntegrityCheck(false)->from($this->_name)
                //Jointure table user
                ->joinLeft('cm_user', 'cm_user.user_id = cm_article.article_author')
                //Jointure table comments
                ->joinLeft('cm_comment', 'cm_comment.comment_post = cm_article.article_id')
                //Clause WHERE : Array. 0 = not_featured, 1 = featured
                ->where('cm_article.article_featured IN (?)', $featured)
                //Clause WHERE : Array. 0 = tous, 1 = publiés
                ->where('cm_article.article_status IN (?)', $published)
                //Clause WHERE : Array. 0 = tous, 1 = publiés
                ->where('cm_article.article_type IN (?)', $type)
                //Clause LIMIT
                ->limit($limit)
                //Clause GROUP
                ->group('cm_article.article_id')
                //Clause ORDER
                ->order('cm_article.article_date DESC');
        //Fetch
        $result = $this->fetchAll($query);
        //Correspondances articles / metas
        foreach ($result as $un_article) {
            //Jojnture
            $tags = $un_article->findManyToManyRowset($metas, $metasrs);
            //Assignation des metas à la colonne article_meta de la table
            $un_article->__set('article_meta', $tags);
        }
        if (!$result) {
            //Fail
            throw new Exception("Could not find row");
        } else {
            //Go
            return $result;
        }
    }

    /*
     * WUT ?
     */
    public function getTags($id){
        $meta = new Application_Model_DbTable_Metas();
        $metars = new Application_Model_DbTable_MetasRelationship();
        $tags = $this->findManyToManyRowset($meta,$metars);
        return $tags;
    }

    /*
     * Récupération des articles sur une année spécifiée
     * @param = année d'un article
     */
    public function getArticleYear($year) {
        $metas = new Application_Model_DbTable_Metas();
        $metasrs = new Application_Model_DbTable_MetasRelationship();
        $query = $this->select()->distinct()->setIntegrityCheck(false)->from($this->_name)
                ->join('cm_user', 'cm_user.user_id = cm_article.article_author')
                ->where('YEAR(article_date) = '.$year)
                ->where('cm_article.article_status = 1')
                ->order('cm_article.article_date DESC');
        $result = $this->fetchAll($query);
        //Assignation des metas à la colonne article_meta de la table
         foreach ($result as $un_article) {
            //Jojnture
            $tags = $un_article->findManyToManyRowset($metas, $metasrs);
            //Assignation des metas à la colonne article_meta de la table
            $un_article->__set('article_meta', $tags);
        }
        if (!$result) {
            throw new Exception("Could not find row $year");
        }
        return $result;
    }
    
    /*
     * Récupération des articles sur un mois+année spécifiés
     * @param = année d'un article
     * @param = mois d'un article
     */
    public function getArticleMonth($year, $month) {
        //Adapters externes
        $metas = new Application_Model_DbTable_Metas();
        $metasrs = new Application_Model_DbTable_MetasRelationship();
        $query = $this->select()->distinct()->setIntegrityCheck(false)->from($this->_name)
                ->join('cm_user', 'cm_user.user_id = cm_article.article_author')
                ->where('YEAR(article_date) = '.$year)
                ->where('MONTH(article_date) = '.$month)
                ->where('cm_article.article_status = 1')
                ->order('cm_article.article_date DESC');
        $result = $this->fetchAll($query);
        //Correspondances articles / metas
        foreach ($result as $un_article) {
            //Jojnture
            $tags = $un_article->findManyToManyRowset($metas, $metasrs);
            //Assignation des metas à la colonne article_meta de la table
            $un_article->__set('article_meta', $tags);
        }
        if (!$result) {
            throw new Exception("Could not find row $date");
        }
        return $result;
    }

    /*
     * Récupération des articles sur un meta spécifié
     * @param = id d'un meta
     */
    public function getArticleMetas($meta) {
        //Adapters externes
        $metas = new Application_Model_DbTable_Metas();
        $metasrs = new Application_Model_DbTable_MetasRelationship();
        $query = $this->select()->distinct()->setIntegrityCheck(false)->from($this->_name)
                ->joinInner('cm_user', 'cm_user.user_id = cm_article.article_author')
                ->joinInner('cm_meta_relationship', 'cm_meta_relationship.article_id = cm_article.article_id')
                ->joinInner('cm_meta', 'cm_meta_relationship.meta_id = cm_meta.meta_id')
                ->where('cm_meta.meta_name IN (?)', $meta)
                ->where('cm_article.article_type = 0')
                ->order('cm_article.article_date DESC')
                ->group('cm_article.article_id');
        $result = $this->fetchAll($query);
        foreach ($result as $un_article) {
            //Jojnture
            $tags = $un_article->findManyToManyRowset($metas, $metasrs);
            //Assignation des metas à la colonne article_meta de la table
            $un_article->__set('article_meta', $tags);
        }
        if (!$result) {
            throw new Exception("Could not find row $meta");
        }
        return $result;
    }

    /*
     * Récupération des articles sur toutes les dates
     */
    public function getAllDates() {
        $query = $this->select()->distinct(true)->from($this->_name)
                ->where('cm_article.article_status = 1')
                ->where('cm_article.article_type = 0')
                ->group('MONTH(article_date)')
                ->group('YEAR(article_date)')
                ->order('article_date DESC');
        $result = $this->fetchAll($query);
        if (!$result) {
            throw new Exception("Could not find row");
        }
        return $result;
    }

    /*
     * Récupération des articles sur un auteur spécifié
     * @param = nom auteur d'un article
     */
    public function getArticleAuthor($author, $nb ='0') {
        $metas = new Application_Model_DbTable_Metas();
        $metasrs = new Application_Model_DbTable_MetasRelationship();
        $auteurs = new Application_Model_DbTable_Users();
        $author_id = $auteurs->getId($author);
        $nb = (int)$nb;
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)
                ->join('cm_user', 'cm_user.user_id = article_author')
                ->where('cm_article.article_author IN (?)', $author_id->user_id)
                ->where('cm_article.article_type = 0')
                ->where('cm_article.article_status = 1')
                ->order('article_date DESC');
        if ($nb == '1') {
            $result = $this->fetchRow($query);
            //Correspondances articles / metas
            //Jointure
            $tags = $result->findManyToManyRowset($metas, $metasrs);
            //Assignation des metas à la colonne article_meta de la table
            $result->__set('article_meta', $tags);
            return $result;
        } else {
            //Fetch my a**
            $result = $this->fetchAll($query);
            //Correspondances articles / metas
            foreach ($result as $un_article) {
                //Jointure
                $tags = $un_article->findManyToManyRowset($metas, $metasrs);
                //Assignation des metas à la colonne article_meta de la table
                $un_article->__set('article_meta', $tags);
            }
            return $result;
        }
    }
    
    /*
     * Récupération du nombre d'articles sur un auteur spécifié
     * @param = nom auteur d'un article
     */
    public function getCountArticleAuthor($author) {
        $author = (int)$author;
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)
                ->join('cm_user', 'cm_user.user_id = article_author')
                ->where('cm_user.user_id = '.$author)
                ->where('article_type = 0')
                 ->where('article_status = 1')
                ->order('cm_article.article_id DESC');
        $result = $this->fetchAll($query);
        $count = count($result);
        return $count;
    }
    /*
     * Post d'un nouvel article
     * @param = formulaire d'ajout
     */
    public function setArticle($form) {
        $data = array(
            'article_id' => '',
            'article_type' => $form['type'],
            'article_title' => $form['titre'],
            'article_author' => $form['author'],
            'article_extract' => $form['extrait'],
            'article_content' => $form['contenu'],
            'article_name' => '',
            'article_date' => new Zend_Db_Expr('NOW()'),
            'article_modified' => new Zend_Db_Expr('NOW()'),
            'article_featured' => $form['featured'],
            'article_status' =>$form['status'],
        );
        /*Insertion dans la table*/
        $row = $this->insert($data);
        /*Récupération du dernier article inséré*/
        $last = $this->getAdapter()->lastInsertId();
        return $last;
    }

    /*
     * Modification d'un article
     * @param = formulaire d'ajout
     * @index = ID de l'article à modifier
     */
    public function updateArticle($form, $index) {
        //Converti la date PHP pour la rendre ok en SQL
        function convert_date($date){
            $date = str_replace('/','-', $date);
            $date = explode('-', $date);
            $date = $date[2].'-'.$date[1].'-'.$date[0];
            $date = $date.' 00:00:00';
            return $date;
        }
        $pdate = convert_date($form['pdate']);
        $mdate = convert_date($form['mdate']);
        $data = array(
            'article_type' => $form['type'],
            'article_title' => $form['titre'],
            'article_author' => $form['author'],
            'article_extract' => $form['extrait'],
            'article_content' => $form['contenu'],
            'article_status' => $form['status'],
            'article_featured' => $form['featured'],
            'article_date' => $pdate,
            'article_modified' => $mdate,
        );
        $where = "article_id = " . $index;
        /*Insertion dans la table*/
        $row = $this->update($data, $where);
    }

    /*
     * Vérification et création du nom unique de l'article
     */
    public function createName($length, $name) {
        $query = $this->select()->distinct()->from($this->_name)
        ->order('cm_article.article_name ASC');
        $result = $this->fetchAll($query);
        foreach($result as $article){
            /*if (substr($article->article_name, 0, $length) == $name){
                $exist = true;
            }*/
            if ($article->article_name == $name){
                $exist = true;
            }
        }
        if(isset($exist)){
            $last_index = count($result);
            $last_name = $result[$last_index-1]->article_name;
            $ind = strrchr($last_name, '-');
            $i = substr($ind, 1);
            if($i){
                settype($i, "integer");
                $i =2;
            } else{
                settype($i, "integer");
                $i +=1;
            }
            $new_name = $name.'-'.$i;
            return $new_name;
        } else {
            return $name;
        }
    }

    /*
     * Mise à jour du permalien de l'article
     */
    public function updateName($id, $name) {
        $data = array(
            'article_name' => $name,
        );
        $where = "article_id = " . $id;
        /*Mise à jour*/
        $row = $this->update($data, $where);
        $new_name = $name;
        return $new_name;
    }

    /*
     * Suppression d'un article
     * @param = id
     */
     public function deleteArticle($index) {
         $where = "article_id = " . $index;
         $row = $this->delete($where);
     }
    
    /*
     * Récupération de tous les auteurs d'articles
     */
    public function getAllAuthor() {
        $query = $this->select()->distinct(true)->setIntegrityCheck(false)->from($this->_name)
               ->join('cm_user', 'cm_user.user_id = article_author')
               ->group('user_username');
        $result = $this->fetchAll($query);
        return $result;
    }

    /*
     * Récupération du nombre d'articles
     */
    public function getNombreArticle() {
        $this->nbArticles = $this->fetchAll();
        return($this->nbArticles = count($this->nbArticles));
    }

    /*
     * Récupération du nombre d'articles
     */
    public function getName($id) {
        $query = $this->select('article_name')->from($this->_name)
                    ->where('article_id IN (?)', $id)
                    ->where('article_status = 1');
        $result = $this->fetchRow($query);
        if (!$result) {
            throw new Exception("Could not find row $id");
        }
        return $result;
    }
    
    /**
     * Recherche générale
     * @param string $name
     * @return string 
     */
    public function search($name) {
        $metas = new Application_Model_DbTable_Metas();
        $metasrs = new Application_Model_DbTable_MetasRelationship();
        $name = new Zend_Db_Expr('%' . $name . '%');
        $perimeter = 
            'cm_article.article_title LIKE("?") 
            OR cm_article.article_extract LIKE("?") 
            OR cm_article.article_content LIKE("?") 
            OR cm_meta.meta_name LIKE("?")';
        $query = $this->select()->distinct()->setIntegrityCheck(false)->from($this->_name)
                    ->join('cm_user', 'cm_user.user_id = cm_article.article_author')
                    ->join('cm_meta_relationship', 'cm_meta_relationship.article_id = cm_article.article_id')
                    ->join('cm_meta', 'cm_meta_relationship.meta_id = cm_meta.meta_id')
                    ->where('cm_article.article_status = ?', 1)
                    ->where($perimeter, $name)
                    ->order('cm_article.article_date DESC')
                    ->group('cm_article.article_id');
        $result = $this->fetchAll($query);
        $count_result = count($result);
        if($count_result == 0) {
            return $count_result;
        } else {
                foreach($result as $article) {
                    $articles[] = $this->getArticlebyId($article->article_id);
                }
                return $articles;
        }
    }

}