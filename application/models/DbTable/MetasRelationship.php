<?php

/**
 * Model form posts <-> tags relationship
 *
 * @author g.raoult@gmail.com
 * @see models/DbTable/Metas.php
 */

class Application_Model_DbTable_MetasRelationship extends Zend_Db_Table_Abstract
{
    /*
     * Nom de la table
     */
    protected $_name = 'cm_meta_relationship';
    protected $_primary = array('article_id','meta_id');
    protected $_referenceMap= array(
        'Metas' => array(
            'columns'           => array('meta_id'),
            'refTableClass'     => 'Application_Model_DbTable_Metas',
            'refColumns'        => array('meta_id')
        ),
        'Articles' => array(
            'columns'           => array('article_id'),
            'refTableClass'     => 'Application_Model_DbTable_Articles',
            'refColumns'        => array('article_id')
        )
    );

    /*
     * Vérifie si un article a déjà un tag dans la base RS
     * @param = id d'un article
     */
    public function checkTag($id) {
        $query = $this->select()->from($this->_name)
                ->where('article_id = '.$id);
        $result = $this->fetchAll($query);
        if (!$result) {
            return false;
        } else {
            return $result;
        }
    }
    
}
