<?php
/**
 * French months
 *
 * @author guillaume
 */
class Zend_View_Helper_Mois extends Zend_View_Helper_Abstract
{

    function Mois($mois, $format) {
            setlocale( LC_TIME, "fr" );
            // Recuperation du nom du mois
            $mois_numerique =  $mois;
            if($format == 'long')
            {
                switch ($mois_numerique){
                    case '01' : $mois_francais = 'Janvier'; break;
                    case '02' : $mois_francais = 'Février'; break;
                    case '03' : $mois_francais = 'Mars'; break;
                    case '04' : $mois_francais = 'Avril'; break;
                    case '05' : $mois_francais = 'Mai'; break;
                    case '06' : $mois_francais = 'Juin'; break;
                    case '07' : $mois_francais = 'Juillet'; break;
                    case '08' : $mois_francais = 'Août'; break;
                    case '09' : $mois_francais = 'Septembre'; break;
                    case '10' : $mois_francais = 'Octobre'; break;
                    case '11' : $mois_francais = 'Novembre'; break;
                    case '12' : $mois_francais = 'Décembre'; break;
                }
            } else {
                switch ($mois_numerique){
                    case '01' : $mois_francais = 'Jan.'; break;
                    case '02' : $mois_francais = 'Fév.'; break;
                    case '03' : $mois_francais = 'Mars'; break;
                    case '04' : $mois_francais = 'Avr.'; break;
                    case '05' : $mois_francais = 'Mai'; break;
                    case '06' : $mois_francais = 'Juin'; break;
                    case '07' : $mois_francais = 'Juil.'; break;
                    case '08' : $mois_francais = 'Août'; break;
                    case '09' : $mois_francais = 'Sep'; break;
                    case '10' : $mois_francais = 'Oct.'; break;
                    case '11' : $mois_francais = 'Nov.'; break;
                    case '12' : $mois_francais = 'Déc.'; break;
                }
            }
            return $mois_francais;
        }
}