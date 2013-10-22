<?php
/**
 * Display popular tags
 *
 * @author guillaume
 */
class Zend_View_Helper_PopularTags extends Zend_Controller_Action_Helper_Url
{
    public function PopularTags()
    {
        $metas = new Application_Model_DbTable_Metas();
        //Récupère tous les mots-clefs
        $tags = $metas->getAllMeta('15');
        $freqData = array(); 
        $mots = '';
        foreach ($tags as $mot_clef) :
                        $mots .= $mot_clef->meta_name;
                        $mots .= ' ';
        endforeach;

        // Get individual words and build a frequency table
        foreach( str_word_count( $mots, 1 ) as $word )
        {
                // For each word found in the frequency table, increment its value by one
                array_key_exists( $word, $freqData ) ? $freqData[ $word ]++ : $freqData[ $word ] = 0;
        }
        echo '
            <h2>Mots-Clés</h2>
            <ul id="tags">'.$this->getCloud($freqData).'</ul>';
    }

    function getCloud( $data = array(), $minFontSize = 12, $maxFontSize = 30 )
    {
            $minimumCount = min( array_values( $data ) );
            $maximumCount = max( array_values( $data ) );
            $spread       = $maximumCount - $minimumCount;
            $cloudHTML    = '';
            $cloudTags    = array();
            $spread == 0 && $spread = 1;

            foreach( $data as $tag => $count )
            {
                    $size = $minFontSize + ( $count - $minimumCount )
                            * ( $maxFontSize - $minFontSize ) / $spread;
                    $cloudTags[] = '<li><a style="font-size: ' . floor( $size ) . 'px'
                    . '" class="tag_cloud" href="'.$this->url(array('tag' => $tag),'archives-tags',true).'">'
                    . htmlspecialchars( stripslashes( $tag ) ) . '</a></li>';
            }
             shuffle($cloudTags);
            return(join( "\n", $cloudTags ) . "\n");
    }



}