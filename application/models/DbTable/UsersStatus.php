<?php

/**
 * Model handling users status
 *
 * @author g.raoult@gmail.com
 * @see models/DbTable/Users.php
 */

class Application_Model_DbTable_UsersStatus extends Zend_Db_Table_Abstract
{
    //Nom de la table
    protected $_name = 'cm_user_status';
    //Clé primaire
    protected $_primary = 'status_id';
    protected $_dependentTables = array('Users');
    
     /*
     * Récupération les groupes d'utilisateurs
     */
    public function getGroups() {
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)
                ->joinInner('cm_user', 'cm_user_status.status_id = cm_user.user_status');
        $result = $this->fetchAll($query);
        if (!$result) {
            throw new Exception("Could not find row");
        }
        return $result;
    }
    
    /*
     * Récupère les groupes vides
     */
    public function getEmpty() {
        $users = $this->select()->setIntegrityCheck(false)->from('cm_user', array('user_status'));
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)
                ->where('cm_user_status.status_id NOT IN (?)', ($users));
        $result = $this->fetchAll($query);
        if (!$result) {
            throw new Exception("Could not find row");
        }
        return $result;
    }
    
    /*
     * Mise à jour du nom du groupe
     */
    public function updateName($id, $name) {
        $data = array(
            'status_name' => $name,
        );
        $where = "status_id = " . $id;
        /*Mise à jour*/
        $row = $this->update($data, $where);
        return $name;
    }
    
}

