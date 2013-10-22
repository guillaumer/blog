<?php
/**
 * Display admin menu
 *
 * @author guillaume
 */
class Zend_View_Helper_Admenu extends Zend_Controller_Action_Helper_Url
{
    /*
     * Affiche le menu principal
     */
    public function Admenu()
    {
        //Récupération de l'action en cours
        $action = $this->getRequest()->getActionName();
        $menu =
            '<ul id="nav">
                <li><a href="'.$this->url(array(),'admin',true).'">Dashboard</a></li>
                <li><a href="'.$this->url(array(),'admin-articles',true).'">Articles</a></li>
                <li><a href="'.$this->url(array(),'admin-comments',true).'">Commentaires</a></li>
                <li><a href="'.$this->url(array(),'admin-users',true).'">Utilisateurs</a></li>
            </ul>';
        return $menu;
    }
}