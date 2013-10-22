<?php

/**
 * Model handling post comments
 *
 * @author g.raoult@gmail.com
 */

class Application_Model_DbTable_Comments extends Zend_Db_Table_Abstract
{
    //Nom de la table
    protected $_name = 'cm_comment';
    protected $_primary = 'comment_id';
    
    /*
     * Récupération des commentaires
     * @param = id d'un article
     */
    public function getComment($id) {
        $query = $this->select()->from($this->_name)
                ->where("comment_post = ". $id);
        $result = $this->fetchAll($query);
        if (!$result) {
            throw new Exception("Could not find row $id");
        }
        return $result;
    }

     /*
     * Récupération de tous les commentaires
     */
    public function getAllComments($limit = false) {
        
        $query = $this->select()->from($this->_name)
                ->limit($limit)
                ->order('cm_comment.comment_date DESC');
        $result = $this->fetchAll($query);
        if (!$result) {
            throw new Exception("Could not find row $id");
        }
        return $result;
    }

    /*
     * Enregistrement d'un nouveau commentaire
     * @param = formulaire d'ajout
     */
    public function setComment($form) {
        $data = array(
            'comment_id' => '',
            'comment_post' => $form['post'],
            'comment_date' => new Zend_Db_Expr('NOW()'),
            'comment_author' => $form['auteur'],
            'comment_email' => $form['email'],
            'comment_web' => $form['web'],
            'comment_ip' => $form['ip'],
            'comment_content' => $form['contenu'],
        );
        /*Insertion dans la table*/
        $row = $this->insert($data);
        /*Récupération du dernier article inséré*/
        $last = $this->getAdapter()->lastInsertId();
        return $last;
    }

     /*
     * Suppression d'un commentaire
     * @param = id
     */
     public function deleteComment($index) {
         $where = "comment_id = " . $index;
         $row = $this->delete($where);
     }

}
