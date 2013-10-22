<?php
/**
 * Display user infos in admin panel
 *
 * @author guillaume
 */
class Zend_View_Helper_Aduserinfo extends Zend_Controller_Action_Helper_Url
{
    /*
     * Affiche le menu principal
     */
    public function Aduserinfo($status)
    {
        $auth = Zend_Auth::getInstance();
        $username = $auth->getIdentity()->user_username;
        
        $userinfo = '<div id="infos">
                        <p>Bienvenue ';
        $userinfo .= '<span class="bold">'.$username.'</span>';
        $userinfo .= '</p><br/><p id="user_status">';
        $userinfo .= $status->status_name.'</p></div>';
        $userinfo .= '<img alt="user_icon" src="'.$status->user_picture.'"/>';
        return $userinfo;
    }
}