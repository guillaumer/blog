<?php

/**
 * Controller handling administration interface
 *
 * @author g.raoult@gmail.com
 */

class AdminController extends Zend_Controller_Action
{

    /*
     * Init!!
     */
    public function init()
    {
        $action = $this->getRequest()->getActionName();
        if ($action != 'login') {
            $this->_helper->layout->setLayout('admin_layout');
        }
        else {
            $this->_helper->layout->setLayout('layout');
        }
    }

    /*
     * Action index. SI loggué, on renvoie vers le dashboard, sinon vers le formulaire de login
     */
    public function indexAction()
    {
        //Récupération de l'instance en cours
        $auth = Zend_Auth::getInstance();
        //Si on est connecté
        if ($auth->hasIdentity()) {
            //On est déjà connecté
            $users = new Application_Model_DbTable_Users();
            $this->_helper->layout->setLayout('admin_layout');
            $this->view->message = 'Bienvenue dans l\'administration';
            $this->_redirect('admin/dashboard');
        //Sinon
        } else {
            //Redirection vers la page de login
            $this->_redirect("admin/login");
        }
    }

    /*
     * On vérifie si l'on est loggué
     */
    public function checkIdentity()
    {
        //Récupération de l'instance en cours
        $auth = Zend_Auth::getInstance();
        //Si on est connecté
        if ($auth->hasIdentity()) {
            $users = new Application_Model_DbTable_Users();
            $status = $auth->getIdentity()->user_status;
            $id = $auth->getIdentity()->user_id;
            $this->view->status = $users->getStatus($status,$id);
            Zend_Layout::getMvcInstance()->assign('status', $users->getStatus($status,$id));
            return true;
        } else {
            $this->_redirect("admin/login");
        }
    }

    /*
     * Récup adapter pour login
     */
    protected function _getAuthAdapter()
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        //Récupération des valeurs de la BDD (base, user, pass, serveur... -> application.ini)
        $authAdapter->setTableName('cm_user')
            ->setIdentityColumn('user_username')
            ->setCredentialColumn('user_password')
            ->setCredentialTreatment('MD5(CONCAT(user_salt,?))');
        return $authAdapter;
    }

    /*
     * J'affihe un formulaire si je ne suis pas déjà logué et j'envoie un cookie si l'utilisateur le veut
     */
    public function loginAction()
    {
        $this->layout= Zend_Layout::getMVCInstance();
        $articles = new Application_Model_DbTable_Articles();
        $this->layout->assign('dates', $articles->getAllDates());
        //Récupération de l'instance en cours
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            //Nouveau formulaire
            $form = new Application_Form_Login();
            //Transmission du formulaire à la vue
            $this->view->formLogin = $form;
            //Si l'on a un envoi du formulaire
            if ($this->_request->isPost()) {
                //Récupération des données du formulaire
                $formData = $this->_request->getPost();
                //Mon formulaire est correctement formaté
                if ($form->isValid($formData)) {
                    //Assignation des valeurs du formulaire
                    $f = new Zend_Filter_StripTags();

                    $user = $f->filter($form->getValue('username'));
                    $pass = $f->filter($form->getValue('password'));
                    $remember = $form->getValue('remember');

                    //Appel de la fonction de connexion au gestionnaire SQL
                    $authAdapter = $this->_getAuthAdapter();
                    //Assignation des valeurs user et password du formulaire vers l'adaptateur pour récupérer les tuples
                    $authAdapter->setIdentity($user);
                    $authAdapter->setCredential($pass);
                    $result = $auth->authenticate($authAdapter);
                    if (!$result->isValid()) {
                        $this->view->erreur = 'Erreur : utilisateur ou mot de passe incorrect';
                    } else {
                        $user = $authAdapter->getResultRowObject();
                        $seconds  = 60 * 60 * 24 * 7;
                        if ($remember) {
                            Zend_Session::RememberMe($seconds);
                        }
                        else {
                            Zend_Session::ForgetMe();
                        }
                        $session = new Zend_Session_Namespace('identity');
                        $auth->getStorage()->write($user);
                        $this->_helper->redirector('dashboard');
                    }
                }
            }
        } else {
            $this->_helper->redirector('dashboard');
        }
    }

    /*
     * Je me délogue
     */
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login');
    }

    /*
     * Affiche le dashboard qui est également la page d'index si on est loggué
     */
    public function dashboardAction()
    {
        if ($this->checkIdentity()) {
            $auth = Zend_Auth::getInstance();
            $user = $auth->getIdentity()->user_id;
            
            $articles = new Application_Model_DbTable_Articles();
            $meta = new Application_Model_DbTable_Metas();
            $comments = new Application_Model_DbTable_Comments();
            $links = new Application_Model_DbTable_Links();
            $notes = new Application_Model_DbTable_Notes();
            
            $this->view->articles = $articles->getAllArticles(array(0,1),array(0,1));
            $this->view->tags = $meta->getAllUniqueMeta();
            $this->view->comments = $comments->getAllComments();
            $this->view->links = $links->getAllLinks();
            $this->view->notes = $notes->getAllNotes($user);
            $this->view->last_note_id = $notes->getlastNote();
        }
    }

    /*
     * Affiche la page récap des articles
     */
    public function articlesAction()
    {
        
        if ($this->checkIdentity()) {
            
            $articles = new Application_Model_DbTable_Articles();

            //J'ai une action de tri par auteur demandée
            if ($this->_hasParam('author')) {
                $index = $this->_getParam('author');
                $index = (int)$index;
                $this->view->articles = $articles->getArticleAuthor($index,'');
            //J'ai une action de tri par tag demandée
            } else if ($this->_hasParam('meta')) {
                $index = $this->_getParam('meta');
                $index = (int)$index;
                $this->view->articles = $articles->getArticleMetas($index);
            //Tri à l'affichage
            } else if ($this->_hasParam('type')) {
                $type = $this->_getParam('type');
                switch($type){
                    //Aficher tous les articles
                    case 'tous':
                        $this->view->articles = $articles->getAllArticles(array(0,1),array(0,1),false,array(0,1));
                        $this->view->type = 'tous';
                        break;
                    //Aficher que les articles
                    case 'articles':
                        $this->view->articles = $articles->getAllArticles(array(0,1),array(0,1),false,array(0,0));
                        $this->view->type = 'articles';
                        break;
                    //Aficher que les pages
                    case 'pages':
                        $this->view->articles = $articles->getAllArticles(array(0,1),array(0,1),false,array(1,1));
                        $this->view->type = 'pages';
                        break;
                     default:
                        $this->view->articles = $articles->getAllArticles(array(0,1),array(0,1),false,array(0,1));
                        $this->view->type = 'tous';
                        break;
                }
            } else {
                $this->view->articles = $articles->getAllArticles(array(0,1),array(0,1),false,array(0,1));
                $this->view->type = 'tous';
            }
            if ($this->_hasParam('id')) {
                $this->_helper->layout()->disableLayout();
                $id = $this->_getParam('id');
                $article = $articles->getArticlebyId($id);
                $this->view->article_resume = $article;
                $this->_helper->viewRenderer('article-infos');
            }
            $this->view->flashMessages = $this->_helper->FlashMessenger->getMessages();
        }
    }

    /*
     * Affiche la page de création d'article
     */
    public function newArticleAction()
    {
        if ($this->checkIdentity()) {
            //Nouveau formulaire d'ajout
            $form = new Application_Form_PostAjout();
            //Transmission du formulaire à la vue
            $this->view->formAjout = $form;
            //Si l'on a un envoi du formulaire
            if ($this->_request->isPost()) {
                $metas = new Application_Model_DbTable_Metas();
                //Récupération des données du formulaire
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    //Mon formulaire est correctement formaté
                    $articles = new Application_Model_DbTable_Articles();
                    //Transmission à la vue du tuple
                    $article = $articles->setArticle($formData);
                    $formData['name'] = $this->view->Niceurl($formData['titre']);
                    $length = strlen($formData['name']);
                    //Vérification si le nom unique existe déjà et incrémentation si c'est le cas
                    $new_name = $articles->createName($length, $formData['name']);
                    //Mise à jour du nom
                    $article_name = $articles->updateName($article ,$new_name);
                    foreach ($formData as $k => $v)
                    {
                        if (preg_match('/^block_new[0-9]+/i', $k))
                        {
                            $id = str_ireplace('block_', '', $k);
                             foreach($v as $i => $value){
                                 $return = $metas->newTag($article, $value);
                            }
                        }
                    }
                    $this->_helper->FlashMessenger('Article publié avec succès');
                    $this->_helper->redirector('articles');
                } else {
                    $this->view->message = 'Echec de l\'enregistrement';
                }
            }
        }
    }

    /*
     * Affiche la page de modification d'un article
     */
    public function editArticleAction()
    {
        if ($this->checkIdentity()) {
            $articles = new Application_Model_DbTable_Articles();
            //Mon URL a un paramètre ID
            //Recherche par ID article
            //Récupération paramètre
            $index = $this->_getParam('post');
            $this->view->article_id = $index;
            $article = $articles->getArticlebyId($index);
            $this->view->article_name = $article->article_name;
            //Nouveau formulaire de modification
            $form = new Application_Form_PostModif(array('ind' => $index));
            //Transmission du formulaire à la vue
            $this->view->formEdit = $form;
            if ($this->_request->isPost()) {
                //Récupération des données du formulaire
                $formData = $this->_request->getPost();
                if ($form->isValid($formData)) {
                    $metas = new Application_Model_DbTable_Metas();
                    $metars = new Application_Model_DbTable_MetasRelationship();
                    //Assignation à un nouveau tableau
                    //et déplacement de tous les éléments tags dans un élément
                    //du tableau commun
                    // TODO : Refonte Subform tags //
                    $forms = $formData;
                    foreach ($formData as $k => $v)
                    {
                        if (preg_match('/^block_new[0-9]+/i', $k))
                        {
                            $forms['tags'][$k] = $v;
                        }
                    }
                    //Mise à jour de l'article
                    $article = $articles->updateArticle($formData, $index);
                    //SI on a des tags dans notre formulaire
                    if (isset($forms['tags'])) {
                        //On remet à 0 les RS
                        $where = "article_id = " . $index;
                        $row = $metars->delete($where);
                        foreach ($forms['tags'] as $k => $v)
                        {
                            if (preg_match('/^block_new[0-9]+/i', $k))
                            {
                                $id = str_ireplace('block_', '', $k);
                                 foreach($v as $i => $value){
                                    $return = $metas->newTag($index, $value);
                                }
                            }
                            
                            if (preg_match('/^block_[0-9]+/i', $k))
                            {
                                $id = str_ireplace('block_', '', $k);
                                 foreach($v as $i => $value){
                                    $return = $metas->newTag($index, $value);
                                }
                            }
                        }
                    } else {
                        //Sinon on supprime tous les tags de l'article
                        $where = "article_id = " . $index;
                        $row = $metars->delete($where);
                    }
                    $this->_helper->FlashMessenger('Article modifié avec succès');
                    $this->_helper->redirector('articles');
                } else {
                    $this->view->message = 'Echec de l\'enregistrement';
                }
            }
        }
    }

    /*
     * Récup subform tags
     */
    public function getTagsformAction() {
        $tags = $this->_getParam('tags');
        $tags = explode(',', $tags);
        $this->TagAjout = new Application_Form_Tags();
        foreach ($tags as $tag){
            if($tag != ''){
                $tag = trim(strip_tags($tag));
                $id = rand(1,  10000000);
                $this->formEdit = $this->TagAjout->buildBlock(array('id' => $id, 'tag' => $tag));
                $this->formEdit->setName('block_new'.$id);
                echo $this->formEdit->render();
            }
        }
        exit;
    }

     /*
     * Affiche la page récap des commentaires
     */
    public function commentsAction()
    {
        if ($this->checkIdentity()) {
            $comments = new Application_Model_DbTable_Comments();
            $this->view->comments = $comments->getAllComments();
        }
    }

        /*
     * Supprime un commentaire
     */
    public function deleteCommentAction()
    {
        if ($this->checkIdentity()) {
            $index = $this->_getParam('id');
            $comments = new Application_Model_DbTable_Comments();
            $comments->deleteComment($index);
            exit;
        }
    }
    
     /*
     * Affiche la page récap des utilisateurs
     */
    public function usersAction()
    {
        if ($this->checkIdentity()) {
            $users = new Application_Model_DbTable_Users();
            $articles = new Application_Model_DbTable_Articles();
            $this->view->users = $users->getAllUsers();
            $auth = Zend_Auth::getInstance();
            $this->view->active_user = $auth->getIdentity()->user_id;
            
            $groups = new Application_Model_DbTable_UsersStatus();
            $this->view->groups = $groups->getGroups();
            $this->view->empty = $groups->getEmpty();
            //$this->getArticleAuthor(
        }
    }
    
    /*
     * Récupère toutes les fnfos et articles d'un utilisateur
     */
    public function userInfosAction()
    {
        $this->_helper->layout->disableLayout();
        $user = $this->_getParam('user');
        $username = $this->_getParam('username');
        $users = new Application_Model_DbTable_Users;
        $articles = new Application_Model_DbTable_Articles;
        $data = $users->getInfos($user);
        $articles_auteur = $articles->getArticleAuthor($username);
        $this->view->data = $data;
        $this->view->articles_auteur = $articles_auteur;
        return($data);
        return($articles_auteur);
        exit;
    }
    
    /*
     * Modifie le nom d'un groupe
     */
    public function editGroupAction()
    {
        $value = $this->_getParam('value');
        $id = $this->_getParam('id');
        $groups = new Application_Model_DbTable_UsersStatus;
        $nom = $groups->updateName($id, $value);
        echo $nom;
        exit;
    }
    
    /*
     * Modifie l'appartenance d'un utilisateur
     */
    public function editUserGroupAction()
    {
        /*$value = $this->_getParam('value');
        $id = $this->_getParam('id');
        $groups = new Application_Model_DbTable_UsersStatus;
        $nom = $groups->updateName($id, $value);*/
        echo 'okk';
        exit;
    }
    

    /*
     * Modifie le nom d'un article
     */
    public function editNameAction()
    {
        $value = $this->_getParam('value');
        $id = $this->_getParam('id');
        $articles = new Application_Model_DbTable_Articles();
        $length = 0;
        //Vérification si le nom unique existe déjà et incrémentation si c'est le cas
        $new_name = $articles->createName($length, $value);
        $nom = $articles->updateName($id, $new_name);
        echo $nom;
        exit;
    }

    /*
     * Supprime un article
     */
    public function deleteArticleAction()
    {
        if ($this->checkIdentity()) {
            $index = $this->_getParam('id');
            $articles = new Application_Model_DbTable_Articles();
            $articles->deleteArticle($index);
            exit;
        }
    }

    /*
     * Récap d'infos sur les articles, page gestion des articles
     */
     public function articleInfosAction()
    {
        $this->_helper->layout()->disableLayout(); 
        $articles = new Application_Model_DbTable_Articles();
        $id = $this->_getParam('id');
        $article = $articles->getArticlebyId($id);
        $this->view->article_resume = $article;
    }

    /*
     * Je gère mes notes dans mon dashboard
     */
    public function manageNotesAction()
    {
        $value = $this->_getParam('value');
        $id = $this->_getParam('id');
        $auth = Zend_Auth::getInstance();
        $author = $auth->getIdentity()->user_id;
        $notes = new Application_Model_DbTable_Notes();
        //Vérification si la note existe déjà
        $exist = $notes->isNote($id);
        if ($exist == true){
            $notes->updateNote($id, $value);
        } else {
            $notes->addNote($id, $author, $value);
        }
        echo $value;
        exit;
    }

    /*
     * Je supprime une note
     */
    public function deleteNoteAction()
    {
        if ($this->checkIdentity()) {
            $index = $this->_getParam('id');
            $notes = new Application_Model_DbTable_Notes();
            $notes->deleteNote($index);
            exit;
        }
    }

    /*
     * Je récupère la dernière note, peu importe l'utilisateur
     */
    public function getlastNoteAction()
    {
        if ($this->checkIdentity()) {
            $notes = new Application_Model_DbTable_Notes();
            $note = $notes->getlastNote();
            if ($note != null) {
                echo $note->note_id;
            } else {
                echo '0';
            }
            exit;
        }
    }

    /*
     * Met à jour les liens
     */
    public function updateLinksAction()
    {
        if ($this->checkIdentity()) {
            $id = $this->_getParam('id');
            $index = $this->_getParam('index');
            $links = new Application_Model_DbTable_Links();
            $links->updateLinks($id, $index);
            exit;
        }
    }

    /*
     * Ajoute un lien
     */
    public function addLinkAction()
    {
        if ($this->checkIdentity()) {
            $index = $this->_getParam('index');
            $url = $this->_getParam('url');
            $name = $this->_getParam('name');
            $desc = $this->_getParam('desc');
            $links = new Application_Model_DbTable_Links();
            $new_id = $links->addLink($index, $url, $name,$desc);
            echo $new_id;
            exit;
        }
    }

    /*
     * Supprime un lien
     */
    public function deleteLinkAction()
    {
        if ($this->checkIdentity()) {
            $index = $this->_getParam('id');
            $links = new Application_Model_DbTable_Links();
            $links->deleteLink($index);
            exit;
        }
    }
}

?>
