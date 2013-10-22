<?php   
/**
 * Display sidebar
 *
 * @author guillaume
 */
class Zend_View_Helper_Sidebar extends Zend_View_Helper_Abstract
{
    
    public function Sidebar() {
            echo  $recherche = new Application_Form_Search();
            if($this->view->layout()->similar_articles)
            {
                $similar = $this->view->SimilarPosts();
            }
            $sorting = $this->view->Sorting();
            $tags = $this->view->PopularTags();
            $social = $this->view->SocialNetworks();
            $liens = $this->view->Liens();
            $twitter = $this->view->Twitter();
    }

}