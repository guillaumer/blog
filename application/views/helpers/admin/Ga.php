<?php
/**
 * Googe analytic stats
 *
 * @author guillaume
 */
class Zend_View_Helper_Ga extends Zend_Controller_Action_Helper_Url
{
    /*
     * Affiche le menu principal
     */
    public function Ga()
    {
        require_once('GoogleAnalyticsAPI.class.php');
        //$ga = new GoogleAnalyticsAPI('ergeais@gmail.com', 'm4l4b4r3', '42849942', date('2011-03-01', time()));
        $date2 = date("Y-m-d");
        //$date1 = '2011-05-13';
        $date1  = mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
        $date1 = date('Y-m-d',$date1);
        $date1_fra = date("j/m/y", strtotime($date1));
        $date2_fra = date("j/m/y", strtotime($date2));
        $ga = new GoogleAnalyticsAPI('ergeais@gmail.com', 'm4l4b4r3', '42849942', $date1, $date2);
        $visits = $ga->getDimensionByMetric('visits', 'day');
        $navigateurs = $ga->getDimensionByMetric('visits', 'browser');
        $visits_chart = '<div id="visits_chart"><img src="http://chart.apis.google.com/chart?chf=bg,s,ecf1f4&chtt=Visites par jour ('.$date1_fra.' au '.$date2_fra.')&chxl=0:|L|M|M|J|V|S|D|1:|0|5|10|15|&chxr=1,0,20&chxt=x,y&chs=300x225&cht=ls&chco=daa71e&chds=0,20&chd=t:'.implode(',',$visits['datas']).'&chg=14.3,-1,1,1&chls=2,4,0&chm=B,e8cf8a,0,0,0" width="300" height="225" alt="Visites" /></div>';
        $nav_chart = '<div id="navs_chart"><img src="http://chart.apis.google.com/chart?chf=bg,s,ecf1f4&cht=p3&chtt=Visites par navigateur&chdlp=b&chd=t:'.implode(',',$navigateurs['datas']).'&chs=300x225&chco=daa71e&chdl='.implode('|',$navigateurs['labels']).'" width="300" height="225" alt="Navigateurs"/></div>';
        return $visits_chart.$nav_chart;
    }
}