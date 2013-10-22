<?php
/**
 * Display social links in sidebar
 *
 * @author guillaume
 */
class Zend_View_Helper_SocialNetworks extends Zend_View_Helper_Abstract
{
    public function SocialNetworks()
    {
        ?>

        <h2>Aussi sur...</h2>
        <ul id="socialnetworks" class="liste_filtre"> 
                <li>
                    <a href="http://twitter.com/icanhasnickname" target="_blank">
                        <img src="<?php echo $this->view->BaseUrl() ?>/images/twitter_logo.png" title="Twitter" alt="Twitter"/>
                    </a>
                </li>
                <li>
                    <a href="http://fr.linkedin.com/pub/guillaume-raoult/25/777/b28" target="_blank">
                        <img src="<?php echo $this->view->BaseUrl() ?>/images/linked-in_logo.png" title="Linked In" alt="Linked In"/>
                    </a>
                </li>
				<li>
                    <a href="http://github.com/" target="_blank">
                        <img src="<?php echo $this->view->BaseUrl() ?>/images/github_logo.png" title="GitHub" alt="GitHub"/>
                    </a>
                </li>
                
        </ul>
            
        <?php
    }
}