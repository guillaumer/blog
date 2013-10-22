<?php

class Plugin_Navigation extends Zend_Controller_Plugin_Abstract
{
    
    function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;

        //load initial navigation from XML
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml','nav');
        $container = new Zend_Navigation($config);

        //get root page
        $rootPage = $container->findOneBy('sth', 'value');

        //get database data
        //$data = Model_Sth::getData();

        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
            $container = new Zend_Navigation($config);
            $rootPage = $container->findOneBy('sth', 'value');
            $rootPage->addPage(new Zend_Navigation_Page_Mvc(array('module'=> 'default',
                'controller' => 'BlogController',
                'action'     => 'archives',
                'route'      => 'archives-post',
                'label'      => 'A propos',
                'params'     => array(
                    'page' => 'test-js'
                )
            )));
        $view->navigation($container);
    }
}

?>
