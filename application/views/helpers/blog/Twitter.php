<?php
/**
 * Display last tweets
 *
 * @author guillaume
 */
class Zend_View_Helper_Twitter extends Zend_Controller_Action_Helper_Url
{

    public function Twitter() {
            $twitter_widget = '
                <h2>Tweets 
                    <span id="twitter_id">(<a href="http://twitter.com/icanhasnickname">@icanhasnickname</a>)</span>
                </h2>
                <ul id="twitter_update_list" class="liste_filtre">
                    <li>Récupération du dernier gazouilli...</li>
                </ul>
                <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
                <script type="text/javascript" src="http://twitter.com/statuses/user_timeline/icanhasnickname.json?callback=twitterCallback2&amp;count=5&amp;include_rts=1&amp;include_entities=1"></script>
                ';
            echo $twitter_widget;
    }
}