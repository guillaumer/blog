<?php
/**
 * Affiche les liens favoris
 *
 * @author guillaume
 */
class Zend_View_Helper_Liens extends Zend_Controller_Action_Helper_Url
{
    public function Liens() {
            $links = new Application_Model_DbTable_Links();
            $liens = $links->getAllLinks();
            ?>
            <h2>A voir</h2>
            <ul id="liens" class="liste_filtre">
            <?php             
                foreach ($liens as $lien){
                        echo '
                            <li>
                                <a href="'.
                                    $lien->link_url.'">'.
                                    $lien->link_name.
                                '</a><br/>'.
                                $lien->link_desc.'
                            </li>';
                }
                ?>
            </ul>
            <?php
    }
}