<?php

/**
 * Model handling post tags
 *
 * @author g.raoult@gmail.com
 * @see models/DbTable/MetasRelationship.php
 */

class Application_Model_DbTable_Metas extends Zend_Db_Table_Abstract
{
    /*
     * Nom de la table
     */
    protected $_name = 'cm_meta';
    protected $_primary = 'meta_name';
    protected $_dependentTables = array('MetasRelationship');

    /**
     * Récupère les metas
     * @param type $limit : int
     * @return type : object
     */
    public function getAllMeta($limit = false) {
        $query = $this->select()->distinct(true)->setIntegrityCheck(false)->from($this->_name)
                ->joinInner('cm_meta_relationship', 'cm_meta_relationship.meta_id = cm_meta.meta_id')
                ->joinInner('cm_article', 'cm_meta_relationship.article_id = cm_article.article_id')
                ->where('cm_article.article_id = cm_meta_relationship.article_id')
                ->where('article_status = 1')
                ->limit($limit);
        $result = $this->fetchAll($query);
        if (!$result) {
            throw new Exception("Could not find row");
        }
        return $result;
    }
    
    /*
     *Récupération de tous les metas unique (tags)
     */
    public function getAllUniqueMeta() {
        $query = $this->select()->distinct(true)->setIntegrityCheck(false)->from($this->_name)
                ->joinInner('cm_meta_relationship', 'cm_meta_relationship.meta_id = cm_meta.meta_id')
                ->joinInner('cm_article', 'cm_meta_relationship.article_id = cm_article.article_id')
                ->group('cm_meta.meta_id');
        $result = $this->fetchAll($query);
        if (!$result) {
            throw new Exception("Could not find row");
        }
        return $result;
    }

    /*
     * Correspondance ID -> Meta
     * @param:int : meta_id
     * @return:string : meta_name
     */
    public function getTagName($id) {
        $query = $this->select()->from($this->_name)
                ->where('cm_meta.meta_id = '.$id);
        $result = $this->fetchRow($query);
        if (!$result) {
            throw new Exception("Could not find row");
        } else {
            return $result;
        }
    }

    /*
     * Récupération de l'ID du dernier tag inséré
     */
    public function getTagId() {
        $last = $this->getAdapter()->lastInsertId();
        return $last;
    }

    /*
     *Vérification si le meta existe déjà ou non
     * @return : meta si existe, false sinon
     */
    public function verifieMeta($meta) {
        $tag = $this->find($meta)->current();
        if ($tag != null){      
            return $tag;
        } else{
            $exist = false;
        }
        return($exist);
    }

    /*
     * Ajout nouveau meta
     */
    public function newTag($article, $value) {
        $metars = new Application_Model_DbTable_MetasRelationship();               
        //Vérification si tag existe déjà
        $exist = $this->verifieMeta($value);
        //Si existe déjà :
        if($exist != false)
        {
            $data = array(
                'article_id' => $article,
                'meta_id' => $exist->meta_id,
            );
        //Sinon
        } else {
            $tag = array(
                'meta_id' => '',
                'meta_name' => $value,
            );
            //Insertion
            $row = $this->insert($tag);
            /*Récupération du dernier meta inséré*/
            $last = $this->getAdapter()->lastInsertId();
            $data = array(
                'article_id' => $article,
                'meta_id' => $last,
            );
        }
        //Insertion dans RS
        $row = $metars->insert($data);
        return $row;
    }
}
