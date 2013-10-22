<?php

/**
 * Main blog controller
 *
 * @author g.raoult@gmail.com
 */

class BlogController extends Zend_Controller_Action
{
    
    /*
     * Initialisation
     */
    public function init()
    {
        setlocale(LC_ALL, 'fra');
        //Nombre d'articles par page
        define('NB_ARTICLES_PAR_PAGE',5);
        //On utilise le layout par défaut des articles
        $this->_helper->layout->setLayout('layout');
        $this->view->titre_blog = '';
        $this->view->setEscape('htmlentities');
        //Menu
        $activeNav = $this->view->navigation()->findByController('BlogController');
        $activeNav->active = true;
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $layout = $bootstrap->getResource('layout');
        $view = $layout->getView();
        $view->page = $this->getRequest()->getActionName();
        $this->layout= Zend_Layout::getMVCInstance();
        $articles = new Application_Model_DbTable_Articles();
        $this->layout->assign('dates', $articles->getAllDates());
        
    }

    /*
     * Action par défaut
     */
    public function indexAction()
    {        
        $articles = new Application_Model_DbTable_Articles();
        //Pagination
        $pagination = Zend_Paginator::factory($articles->getAllArticles(array(0,1), array(1)));
        $page = $this->_getParam('page',1);
        $pagination->setCurrentPageNumber($page)
                            ->setItemCountPerPage(NB_ARTICLES_PAR_PAGE);
        $page_en_cours = $pagination->getCurrentPageNumber();
        $this->layout->assign('page_en_cours', $page_en_cours);
        $this->layout->assign('view', 'index');
        $this->view->articles = $pagination;
    }

    /*
     * Action des archives
     */
    public function archivesAction()
    {
        $articles = new Application_Model_DbTable_Articles();
        //Recherche par ID article
        if ($this->_hasParam('post')) {
            //Récupération paramètre
            $index = $this->_getParam('post');
            //Récupération de l'ID de l'article d'après le nom unique
            $id = $articles->getId($index);
            //Transmission à la vue du tuple d'un seul article
            $article = $articles->getArticle($index);
            $this->view->article = $article;
            /*Breadcrumbs*/
            $this->layout->assign('annee', strftime("%Y", strtotime($article->article_date)))
                                   ->assign('mois', strftime("%m", strtotime($article->article_date)));
            //Articles similaires
            $similar = array();
            foreach($article->article_meta as $meta)
            {
                    $nouveau = $articles->getArticleMetas($meta->meta_name);
                    foreach($nouveau as $a)
                    {
                        if($a->article_id != $article->article_id)
                        {   
                            if(count($similar) < 5) $similar[$a->article_id] = $a;
                        }
                    }
            }
            $this->layout->assign('similar_articles', $similar);
        } else {
            if ($this->_hasParam('year') || $this->_hasParam('month')) {
                $year = $this->_getParam('year');
                $month = $this->_getParam('month');
                $this->layout->assign('annee', $year);
                $this->layout->assign('mois', $month);
                $pagination = Zend_Paginator::factory($articles->getArticleMonth($year, $month));
            } else if ($this->_hasParam('author')) {
                $author = $this->_getParam('author');
                //Transmission à la vue du tuple des articles correspondants à l'auteur
                $this->layout->assign('author', $author);
                $pagination = Zend_Paginator::factory($articles->getArticleAuthor($author,''));
            } else if ($this->_hasParam('tag')) {
                $tag = $this->_getParam('tag');
                $this->layout->assign('tag', $tag);
                $pagination = Zend_Paginator::factory($articles->getArticleMetas($tag));
            } else if ($this->_hasParam('important')) {
                $this->layout->assign('important', 'important');
                $pagination = Zend_Paginator::factory($articles->getAllArticles(array(1), array(1),''));
            } else {
                $this->_redirect('/');
            }
            $this->_helper->viewRenderer('index');
            $page = $this->_getParam('page',1);
            $pagination->setCurrentPageNumber($page)
                                ->setItemCountPerPage(NB_ARTICLES_PAR_PAGE);
            $page_en_cours = $pagination->getCurrentPageNumber();
            $this->layout->assign('page_en_cours', $page_en_cours);
            $this->view->articles = $pagination;
        }
    }
    
    /*
     * Action des pages
     */
    public function pagesAction()
    { 
            $articles = new Application_Model_DbTable_Articles();
            $index = $this->_getParam('page');
            $id = $articles->getId($index);
            //Transmission à la vue du tuple d'un seul article
            $this->view->page = $articles->getArticle($index);
    }
    
     /*
     * Sitemap
     */
    public function sitemapAction()
    {
            $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
            $layout = $bootstrap->getResource('layout');
            $this->_helper->viewRenderer->setNoRender();
            $this->view->layout()->disableLayout();
            $this->getResponse()->setHeader('Content-Type', 'text/xml; charset=utf-8');
            echo $layout->getView()->navigation()->sitemap();
    }
    
     /*
     * A propos
     */
    public function aboutAction()
    { 
        //Nothing to do
    }
    
     /*
     * Contact
     */
    public function contactAction()
    { 
        $form = new Application_Form_Contact();
        $this->view->formContact = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            //Mon formulaire est correctement formaté
            if ($form->isValid($formData)) {
                $nom= $form->getValue('nom');
                $email = $form->getValue('email');
                $message = $form->getValue('message');
                //Envoi du mail
                $config = array('auth' => 'PLAIN',
                'username' => 'postmaster@liandri.fr',
                'password' => 'vi3wsonic',
                'port' => 587
                );
                $transport = new Zend_Mail_Transport_Smtp('mail.liandri.fr', $config);
                $mail = new Zend_Mail();
                $mail->setBodyText($message);
                $mail->setFrom($email, $nom);
                $mail->addTo('g.raoult@gmail.com', 'Guillaume Raoult');
                $mail->setSubject('Email test');
                $mail->send($transport);
                $this->view->message = 'Merci, je vous répondrai dès que possible';
            } else {
                $this->view->erreur = 'Erreur, veuillez vérifier les champs.';
            }
        }
    }
    
     /*
     * Recherche générale
     */
    public function rechercheAction()
    { 
            $articles = new Application_Model_DbTable_Articles();
            $this->mergeQueryString();
            $p = $this->getRequest()->getParams();
            $this->_helper->viewRenderer('index');
            $res_recherche = $articles->search($p["recherche"]);
            //echo $res_recherche;
            if($res_recherche != 0)
            {
                    $pagination = Zend_Paginator::factory($res_recherche);
                    $page = $this->_getParam('page',1);
                    $pagination->setCurrentPageNumber($page)
                                        ->setItemCountPerPage(NB_ARTICLES_PAR_PAGE);
                    $page_en_cours = $pagination->getCurrentPageNumber();
                    $this->layout->assign('nb_res', $pagination->getTotalItemCount());
                    $this->layout->assign('page_en_cours', $page_en_cours);
                    $this->view->articles = $pagination;
            } else {
                $this->layout->assign('nb_res', 0);
            }
            $this->view->recherche = $p["recherche"];
            $this->layout->assign('recherche', $p["recherche"]);
    }
    
    /*
     * RSS
     */
    public function rssAction()
    {
            $baseURL = $this->getRequest()->getHttpHost().$this->view->baseUrl();
            $this->_helper->viewRenderer->setNoRender();
            $this->view->layout()->disableLayout();
            $articles = new Application_Model_DbTable_Articles();
            $items = $articles->getAllArticles(array(0,1), array(1));
            $feedArray = array(
                'title' => "Liste de mes articles",
                'link' => $baseURL,'/rss',
                'charset' => 'utf-8',
                'description' => "Les articles de mon merveilleux site",
                'author' => 'Guillaume Raoult',
                'email' => 'g.raoult@gmail.com',
                'copyright' => 'ce sont mes articles !',
                'generator' => 'Zend Framework Zend_Feed',
                'language' => 'fr',
                'entries' => array()
            );
            foreach ($items as $article) {
                $feedArray['entries'][] = array(
                    'title' => $article->article_title,
                    'link' => $baseURL.'/'.$article->article_name,
                    'description' => '',
                    'content' => utf8_encode($article->article_extract)
                );
            }
            $feed = Zend_Feed::importArray($feedArray, 'rss');
            $feed->send();
    }
    
    /**
   * Translate standard URL parameters (?foo=bar&baz=bork) to zend-style 
   * param (foo/bar/baz/bork).  Query-string style
   * values override existing route-params.
   */
    public function mergeQueryString(){
        if ($this->getRequest()->isPost()){
          throw new Exception("mergeQueryString only works on GET requests.");
        }    
        $q = $this->getRequest()->getQuery();
        $p = $this->getRequest()->getParams();
        if (isset($p['submit'])) unset($p['submit']);
        if (empty($q)) {
          //there's nothing to do.
          return;
        }
        unset($p['controller'],$p['action'],$p['module'],$q['submit'], $p['page']);        
        $params = array_merge($p,$q);
        
        $this->_helper->getHelper('Redirector')
                ->setCode(301)
                ->gotoSimple(
                    '',
                    '',
                    '',
                    $params);
    }
   
}