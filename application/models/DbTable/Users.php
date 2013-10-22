<?php

/**
 * Model handling users
 *
 * @author g.raoult@gmail.com
 * @see models/DbTable/UsersStatus.php
 */

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{
    //Nom de la table
    protected $_name = 'cm_user';
    //Clé primaire
    protected $_primary = 'user_id';
    protected $_dependentTables = array('UserStatus');

    
     /*
     * Correspondance nom_utulisateur / id
     * @param = username
     */
    public function getId($username) {
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)
                ->where('cm_user.user_username IN (?)', $username);
        $result = $this->fetchRow($query);
        if (!$result) {
            throw new Exception("Could not find row $id");
        }
        return $result;
    }
    
    /*
     * Récupération des infos sur un utilisateur
     */
    public function getAllUsers() {
        $query = $this->select()->distinct()->setIntegrityCheck(false)->from($this->_name)
                ->joinInner('cm_user_status', 'cm_user_status.status_id = cm_user.user_status');
        $result = $this->fetchAll($query);
        if (!$result) {
            throw new Exception("Could not find row");
        }
        return $result;
    }
    
    /*
     * Récupération des infos sur un utilisateur
     * @param = id d'un utilisateur
     */
    public function getInfos($id) {
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)
                ->joinInner('cm_user_status', 'cm_user_status.status_id = cm_user.user_status')
                ->where('cm_user.user_id = '.$id);
        $result = $this->fetchRow($query);
        if (!$result) {
            throw new Exception("Could not find row $id");
        }
        return $result;
    }
    
    /*
     * Récupération du statut d'un utilisateur
     * @param = status d'un utilisateur
     * @param = id d'un utilisateur
     */
    public function getStatus($id,$user) {
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)
                ->join('cm_user_status', 'cm_user_status.status_id = cm_user.user_status')
                ->where('cm_user.user_status IN (?)', $id)
                ->where('cm_user.user_id IN (?)', $user);
        $result = $this->fetchRow($query);
        if (!$result) {
            throw new Exception("Could not find row $id");
        }
        return $result;
    }
    
}

