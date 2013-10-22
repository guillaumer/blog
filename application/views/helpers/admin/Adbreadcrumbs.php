<?php
/**
 * Display admin breadcrumbs (you are here)
 *
 * @author guillaume
 */

class Zend_View_Helper_Adbreadcrumbs extends Zend_Controller_Action_Helper_Url
{

    public function Adbreadcrumbs()
    {
        //Récupération de l'action en cours
        $action = $this->getRequest()->getActionName();
        $breadcrumbs = '<img src="'.$this->getRequest()->getBaseUrl().'/images/icon_info.png" alt="icon_info"/>';
        $breadcrumbs .=
            'Vous êtes ici :
                <a href="'.$this->url(array('controller'=>'admin', 'action'=> $action),false,true).'">'.$action.'</a>
            ';
        return $breadcrumbs;
    }
}