<?php

/**
 * Model handling favorites links
 *
 * @author g.raoult@gmail.com
 */

class Application_Model_DbTable_Links extends Zend_Db_Table_Abstract
{
    //Nom de la table
    protected $_name = 'cm_links';
    protected $_primary = 'link_id';
    
    /*
     * Récupération des liens par ordre
     */
    public function getAllLinks() {
        $query = $this->select()->setIntegrityCheck(false)->from($this->_name)
                ->order('link_order');
        $result = $this->fetchAll($query);
        if (!$result) {
            throw new Exception("Could not find row");
        }
        return $result;
    }

    /*
     * Met à jour les liens
     * @param id:int = index du lien
     * @param index:int = position du lien
     */
    public function updateLinks($id, $index) {
        $data = array(
            'link_order' => $index,
        );
        $where = "link_id = " . $id;
        /*Mise à jour*/
        $row = $this->update($data, $where);
    }

    /*
     * Ajoute un lien
     * @param index:int = ordre du lien
     * @param url:string = adresse du lien
     * @param name:string = intitulé du lien
     */
    public function addLink($index, $url, $name, $desc) {
        $data = array(
            'link_url' => $url,
            'link_name' => $name,
            'link_desc' => $desc,
            'link_order' => $index,
        );
        /*Mise à jour*/
        $row = $this->insert($data);
        $new_id = $row.'-'.$index;
        return $new_id;
    }

    /*
     * Supprime un lien
     * @param index:int = ordre du lien
     * @param url:string = adresse du lien
     * @param name:string = intitulé du lien
     */
    public function deleteLink($index) {
        $where = "link_id = " . $index;
        $row = $this->delete($where);
    }

    /*
     * Vérifie si le lien existe déjà
     * @param id:int = ID du lien
     */
    public function isLink($id) {
        $link = $this->find($id)->current();
        if ($link != null){
            $exist = true;
        } else{
            $exist = false;
        }
        return($exist);
    }
}
