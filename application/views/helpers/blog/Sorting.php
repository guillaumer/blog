<?php
/**
 * Sort articles by date in sidebar (archives)
 *
 * @author guillaume
 */
class Zend_View_Helper_Sorting extends Zend_View_Helper_Abstract
{

    public function Sorting()
    {        
        ?>
        
        <h2>Archives 
            <a href="<?php echo $this->view->url(array(),'rss',true) ?>">
                <img src="<?php echo $this->view->BaseUrl().'/images/icon_rss.png'; ?>" alt="Flux RSS" />
            </a>
        </h2>
        <ul id="dates" class="liste_filtre">
            <?php
                $dates = $this->view->layout()->dates;
                foreach($dates as $date) {
                    echo '<li><a style="text-transform:capitalize" href="'.
                    $this->view->url(array(
                                        'year' => date("Y", strtotime($date->article_date)), 
                                        'month' => date("m", strtotime($date->article_date))
                    ),'archives-date',true).'">'.
                    utf8_encode(strftime("%B", strtotime($date->article_date))).
                    ' '.
                    strftime("%Y", strtotime($date->article_date)).
                    '</a></li>';
                }
             ?>
         </ul>
        <?php
    }
}