<?php

/**
 * Model handling admin notes
 *
 * @author g.raoult@gmail.com
 */

class Application_Model_DbTable_Notes extends Zend_Db_Table_Abstract
{
    //Nom de la table
    protected $_name = 'cm_notes';
    protected $_primary = 'note_id';
    
    /*
     * Récupération des liens par ordre
     * @param:int = ID de l'utilisateur
     */
    public function getAllNotes($id) {
        $query = $this->select()->distinct(true)->from($this->_name)
                ->where('cm_notes.author_id = '.$id);
        $result = $this->fetchAll($query);
        if (!$result) {
            throw new Exception("Could not find row");
        }
        return $result;
    }

    /*
     * Vérifie si une note existe déjà
     * @param id:int = ID de la note
     */
    public function isNote($id) {
        $note = $this->find($id)->current();
        if ($note != null){
            $exist = true;
        } else{
            $exist = false;
        }
        return($exist);
    }

    /*
     * Met à jour une note existante
     * @param id:int = ID de la note
     * @param value:string = contenu de la note
     */
    public function updateNote($id, $value) {
        $data = array(
            'note_content' => $value,
        );
        $where = "note_id = " . $id;
        /*Mise à jour*/
        $row = $this->update($data, $where);
    }

    /*
     * Ajoute une nouvelle note
     * @param id:int = ID de la note
     * @param author:int = ID de l'auteur
     * @param value:string = contenu de la note
     */
    public function addNote($id, $author, $value) {
        $data = array(
            'note_id' => $id,
            'author_id' => $author,
            'note_content' => $value,
        );
        /*Insertion dans la table*/
        $row = $this->insert($data);
    }

    /*
     * Supprime une note
     * @param id:int = ID de la note
     */
    public function deleteNote($id) {
        $where = "note_id = " . $id;
        $row = $this->delete($where);
    }
    
    /*
     * Supprime une note
     * @param id:int = ID de la note
     */
    public function getlastNote() {
        $query = $this->select('note_id')->limit(1)->order('note_id DESC');
        $result = $this->fetchRow($query);
        if (!$result) {
            return null;
        }
        else {
            return $result;
        }
        
    }
}
