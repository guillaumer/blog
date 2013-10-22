<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
    /**
     * @return Zend_Navigation
     */
       protected function _initNavigation()
        {
            $view = $this->bootstrap('layout')->getResource('layout')->getView();
            
            $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml','nav');
			$config = $config->toArray();

            $nav = new Zend_Navigation($config);
			$view->navigation($nav);
            
            $view->addHelperPath(APPLICATION_PATH.'/views/helpers/blog');
            $view->addHelperPath(APPLICATION_PATH.'/views/helpers/admin');
        }
}


