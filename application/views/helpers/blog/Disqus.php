<?php

/**
 * Disqus comments
 *
 * @author g.raoult@gmail.com
 */

class Zend_View_Helper_Disqus extends Zend_View_Helper_Abstract
{
    /**
     * @param  string $disqusName
     * @param  string $id
     * @return string html
     */
    public function Disqus($disqusName, $id)
    {
         return '
           <div id="disqus_thread"></div>
            <script type="text/javascript">
                var disqus_developer = 1;
                var disqus_identifier = "'.$id.'"
                var disqus_shortname = "'.$disqusName.'";
                (function() {
                    var dsq = document.createElement(\'script\'); dsq.type = \'text/javascript\'; dsq.async = true;
                    dsq.src = \'http://\' + disqus_shortname + \'.disqus.com/embed.js\';
                    (document.getElementsByTagName(\'head\')[0] || document.getElementsByTagName(\'body\')[0]).appendChild(dsq);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        ';
    }
}