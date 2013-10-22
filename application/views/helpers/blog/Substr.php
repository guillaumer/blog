<?php
/**
 *
 * Better substr with ellipsis add and no word cutting
 *
 * @author guillaume
 */
class Zend_View_Helper_Substr extends Zend_View_Helper_Abstract
{

    public function Substr($str, $length, $minword = 3) {
        $sub = '';
        $len = 0;
        foreach (explode(' ', $str) as $word)
        {
            $part = (($sub != '') ? ' ' : '') . $word;
            $sub .= $part;
            $len += strlen($part);
            if (strlen($word) > $minword && strlen($sub) >= $length)
            {
                break;
            }
        }
        return $sub . (($len < strlen($str)) ? '...' : '');
    }
}