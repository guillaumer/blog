<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path(),
)));

/** Zend_Application */
require_once APPLICATION_PATH."/library/Zend/Application.php";

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$ctrl = Zend_Controller_Front::getInstance();
$router = $ctrl->getRouter();

$router->addRoute('archives', new Zend_Controller_Router_Route(
    'archives',
    array(
        'controller' => 'blog',
        'action'     => 'archives'
    )
));
$router->addRoute('page', new Zend_Controller_Router_Route(
    'page/:page',
    array(
        'controller' => 'blog',
        'action'     => 'index',
        'page'       => '',
    )
));
$router->addRoute('archives-featured', new Zend_Controller_Router_Route(
    'important/:important/:page',
    array(
        'controller' => 'blog',
        'action'     => 'archives',
        'important' => '',
        'page'       => '',
    )
));
$router->addRoute('archives-author', new Zend_Controller_Router_Route(
    'auteur/:author/:page',
    array(
        'controller' => 'blog',
        'action'     => 'archives',
        'author'       => '',
        'page'       => '',
    )
));
$router->addRoute('archives-tags', new Zend_Controller_Router_Route(
    'tag/:tag/:page',
    array(
        'controller' => 'blog',
        'action'     => 'archives',
        'tag' => '',
        'page'       => '',
    )
));
$router->addRoute('archives-date', new Zend_Controller_Router_Route(
    'archives/:year/:month/:page',
    array(
        'controller' => 'blog',
        'action'     => 'archives',
        'year'       => '',
        'month'       => '',
        'page'       => '',
    )
));
$router->addRoute('archives-post', new Zend_Controller_Router_Route(
    '/:post',
    array(
        'controller' => 'blog',
        'action'     => 'archives',
        'post'       => '',
    )
));
$router->addRoute('index', new Zend_Controller_Router_Route(
    '/',
    array(
        'controller' => 'blog',
        'action'     => 'index',
    )
));
$router->addRoute('contact', new Zend_Controller_Router_Route(
    '/contact',
    array(
        'controller' => 'blog',
        'action'     => 'contact',
    )
));
$router->addRoute('about', new Zend_Controller_Router_Route(
    '/a-propos',
    array(
        'controller' => 'blog',
        'action'     => 'about',
    )
));
$router->addRoute('pages', new Zend_Controller_Router_Route(
    'pages/:page',
    array(
        'controller' => 'blog',
        'action'     => 'pages',
        'page'       => '',
    )
));
$router->addRoute('sitemap', new Zend_Controller_Router_Route(
    'sitemap.xml',
    array(
        'controller' => 'blog',
        'action'     => 'sitemap',
    )
));
$router->addRoute('recherche', new Zend_Controller_Router_Route(
    'recherche/:recherche/:page',
    array(
        'controller' => 'blog',
        'action'     => 'recherche',
        'recherche' => '',
        'page' => '',
    )
));
$router->addRoute('rss', new Zend_Controller_Router_Route(
    '/rss',
    array(
        'controller' => 'blog',
        'action'     => 'rss',
    )
));
$router->addRoute('admin', new Zend_Controller_Router_Route(
    'admin',
    array(
        'controller' => 'admin',
        'action'     => 'index',
    )
));
$router->addRoute('logout', new Zend_Controller_Router_Route(
    'admin/logout',
    array(
        'controller' => 'admin',
        'action'     => 'logout',
    )
));
$router->addRoute('admin-articles', new Zend_Controller_Router_Route(
    'admin/articles/:type',
    array(
        'controller' => 'admin',
        'action'     => 'articles',
        'type'       => ''
    )
));
$router->addRoute('admin-comments', new Zend_Controller_Router_Route(
    'admin/comments',
    array(
        'controller' => 'admin',
        'action'     => 'comments',
    )
));
$router->addRoute('admin-users', new Zend_Controller_Router_Route(
    'admin/users',
    array(
        'controller' => 'admin',
        'action'     => 'users',
    )
));
$router->addRoute('edit-article', new Zend_Controller_Router_Route(
    'admin/edit-article/:post',
    array(
        'controller' => 'admin',
        'action'     => 'edit-article',
        'post'       => ''
    )
));
$router->addRoute('new-article', new Zend_Controller_Router_Route(
    'admin/new-article',
    array(
        'controller' => 'admin',
        'action'     => 'new-article',
    )
));
$router->addRoute('delete-article', new Zend_Controller_Router_Route(
    'admin/delete-article/:post',
    array(
        'controller' => 'admin',
        'action'     => 'delete-article',
        'post'       => ''
    )
));
$application->bootstrap()
                     ->run();
