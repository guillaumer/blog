<?php
/**
 * Display similar posts
 *
 * @author guillaume
 */
class Zend_View_Helper_SimilarPosts extends Zend_View_Helper_Url
{

    public function SimilarPosts()
    {        
        ?>
        <h2>Articles similaires</h2>
        <ul id="similar_posts" class="liste_filtre">
            <?php
                foreach($this->view->layout()->similar_articles as $article)
                {
                    /**
                     * @todo : Upload small pictures
                     */
                    echo '<li>
                                <span>
                                    <img src="'.
                                        $this->view->BaseUrl().'/upload/'.$article->article_id.
                                        '/similar_illus.png" alt="'.
                                        $article->article_name.
                                        '" width="60px" height="42px" />
                                </span>
                                <a href="' .
                                $this->url(array('post' => $article->article_name),'archives-post'). '">' .
                                $article->article_title. '</a>
                            </li>';
                }
        ?>
         </ul>
        <?php
    }
}