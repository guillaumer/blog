<?php
/**
 * Display social widgets (like) on a blog post
 *
 * @author guillaume
 */
class Zend_View_Helper_Social extends Zend_View_Helper_Abstract
{

    function Social() {
            $facebook = '<iframe src="http://www.facebook.com/plugins/like.php?href='.$this->view->serverUrl().$this->view->url().'&amp;layout=button_count&amp;show_faces=false&amp;width=65&amp;action=like&amp;colorscheme=light" style="border:none; overflow:hidden; width:65px; height:20px;"></iframe>';
            //$facebook='<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="" send="false" layout="button_count" width="250" show_faces="false" action="like" font="arial"></fb:like>';
            echo $facebook;
            
            echo '<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>';
            
           $twitter = '
                <span id="tweet-this">
                     <a href="http://twitter.com/share" class="twitter-share-button" data-count="none" data-via="icanhasnickname" data-lang="fr">Tweet</a>
                 </span>';
  
            
            echo $twitter;
            
            echo '</p>';
        }
}