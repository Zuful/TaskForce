<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 11/11/14
 * Time: 10:40
 */

namespace Metier;

include_once(dirname(__FILE__) . "/../helper/Html.php");
include_once(dirname(__FILE__) . "/../helper/Helper.php");
include_once(dirname(__FILE__) . "/Ajax.php");

use Helper\Helper;
use Helper\Html;
use Model\ModelAjaxLoadFiles;
use Model\ModelContextualMenu;
use Model\ModelDoubleChoiceBox;
use Model\ModelFilters;
use Model\ModelPanel;
use Model\ModelUser;

class UserInterface {
    private $_ajax;
    private $_helper;
    private $_html;
    private $_head;
    private $_header;
    private $_footer;
    private $_home;
    private $_user;

    public $indexPage;
    private $_contactsPage;
    private $_settingsPage;

    private $_createTaskPage;
    private $_seeTaskPage;
    private $_editTaskPage;

    private $_signInPage;
    private $_signUpPage;
    private $_signOutPage;

    private $_ajaxLoadFiles;

    public function __CONSTRUCT(ModelUser $user){
        $this->_user = $user;
        $this->_ajax = new Ajax($this->_user);
        $this->_html = new Html();
        $this->_helper = new Helper();

        //********************************************************Head*****************************************************************
        $this->_head .= $this->_html->newTitle("Home");
        $this->_head .= $this->_html->newMeta(array("charset" => "UTF-8"));
        $this->_head .= $this->_html->newLinkTag(array("rel" => "stylesheet", "href" => "css/jquery.mobile-1.4.5.min.css"));
        $this->_head .= $this->_html->newScriptTag(array("type" => "text/javascript", "src" => "js/jquery.min.js"));
        $this->_head .= $this->_html->newScriptTag(array("type" => "text/javascript", "src" => "js/jquery.mobile-1.4.5.min.js"));

        //********************************************************Header*****************************************************************
        $linkHome = $this->_html->newA(array("href" => "index.php", "data-transition" => "turn", "data-icon" => "home"), "Home");
        $linkAccount = $this->_html->newA(array("href" => "contacts.php", "data-transition" => "turn", "data-direction" => "reverse","data-icon" => "user"), "Contacts");
        $linkSettings = $this->_html->newA(array("href" => "settings.php", "data-transition" => "turn", "data-direction" => "reverse","data-icon" => "gear"), "Settings");

        $list = $this->_html->newLi(array(), array($linkHome, $linkAccount, $linkSettings));
        $nav = $this->_html->newUl(array(), $list);
        $navBar = $this->_html->newDiv(array("data-role" => "navbar"), $nav);

        $this->_header = $this->_html->newDiv(array("data-role" => "header"), $navBar);

        //********************************************************Footer*****************************************************************
        $this->_footer = $this->_html->newDiv(array("data-role" => "footer"), "<h2>&copy; Yamani ADAME 2014</h2>");

        //********************************************************Variables*****************************************************************
        $this->indexPage = "index.php";
        $this->_contactsPage = "contacts.php";
        $this->_settingsPage = "settings.php";

        $this->_createTaskPage = "createTask.php";
        $this->_seeTaskPage = "seeTask.php";
        $this->_editTaskPage = "editTask.php";

        $this->_signInPage = "signIn.php";
        $this->_signUpPage = "signUp.php";
        $this->_signOutPage = "signOut.php";
    }

    public function getHead(){
        return $this->_head;
    }

    public function getTaskHead(){
        $this->_head .= $this->_html->newLinkTag(array("rel" => "stylesheet", "href" => "css/jquery.mobile.datepicker.css"));
        $this->_head .= $this->_html->newLinkTag(array("rel" => "stylesheet", "href" => "css/jquery.mobile.datepicker.theme.css"));
        $this->_head .= $this->_html->newScriptTag(array("type" => "text/javascript", "src" => "js/jquery.min.js"));//TODO : Chopper le js de datepicker

        return $this->_head;
    }

    /**
     * @return ModelAjaxLoadFiles
     */
    private function _getAjaxLoadFiles(){
        if(is_null($this->_ajaxLoadFiles)){
            $this->_ajaxLoadFiles = new ModelAjaxLoadFiles();
            return $this->_ajaxLoadFiles;
        }
        else{
            return $this->_ajaxLoadFiles;
        }
    }

    /**
     *
     * @param null $level
     * @return array | string
     */
    private function _getTaskImportance($level = null){
        $arrayImportance = array(1 => "Basse", 2 => "Moyenne", 3 => "Haute");

        if(!is_null($level)){
            foreach($arrayImportance as $importanceKey => $importanceVal){
                if($importanceKey == $level ){
                    return $importanceVal;
                }
            }
        }


        return $arrayImportance;
    }

    private function _getConfirmDelete(){
        $ajaxLoadFiles = $this->_getAjaxLoadFiles();
        if(is_null($ajaxLoadFiles->confirmDelete)){
            $ajaxLoadFiles->confirmDelete = "ajax/confirmDelete.php";

            return $ajaxLoadFiles->confirmDelete;
        }
        else{
            return $ajaxLoadFiles->confirmDelete;
        }
    }

    public function homePage(){
        if(is_null($this->_home)){
            $leftMenu = $this->getLeftMenu();

            $leftMenuBtn = $this->_html->newButton(array("data-icon" => "arrow-r"), "Options");
            $leftMenuLink = $this->_html->newA(array("href" => "#leftMenu"), $leftMenuBtn);
            $taskList = $this->getTaskList();

            $btnNewTask = $this->_html->newButton(array("data-icon" => "plus"), "Nouvelle tache");
            $linkNewTask = $this->_html->newA(array("href" => $this->_createTaskPage,
                                                    "data-transition" => "turn",
                                                    "data-direction" => "reverse"),
                                              $btnNewTask);
            $ui = $linkNewTask . $leftMenuLink . $taskList;

            $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $ui);

            $page = $leftMenu . $this->_header . $uiContent . $this->_footer;

            $this->_home = $this->_html->newDiv(array("data-role" => "page", "id" => "home"), $page);

            return $this->_home;
        }
        else{
            return $this->_home;
        }
    }

    public function getTaskList(){
        $modelFilter = new ModelFilters();
        $modelFilter->search = (isset($_POST["search"]))?$_POST["search"]:null;
        $modelFilter->importance = (isset($_POST["filterImportance"]))?$_POST["filterImportance"]:null;
        $modelFilter->chronology = (isset($_POST["filterChronology"]))?$_POST["filterChronology"]:null;
        $modelFilter->status = (isset($_POST["filterStatus"]))?$_POST["filterStatus"]:null;

        $tasks = $this->_ajax->getTasks($modelFilter);
        $list = array();

        foreach($tasks as $task){
            //******************************************Task basic infos
            $img = null;
            $title = $this->_html->newH(array(), 2, $task->name);
            $basicInfosContent = "Importance : " . $this->_getTaskImportance($task->importance) ."<br> Date limite : " . $task->due_date;
            $basicInfos = $this->_html->newP(array(), $basicInfosContent);
            $linkContent = $img . $title . $basicInfos;

            $linkSeeTask = $this->_html->newA(array("href" => $this->_seeTaskPage . "?id=" . $task->id,
                                                    "data-transition" => "turn",
                                                    "data-direction" => "reverse"),
                                              $linkContent);
            //*******************************************Contextual Menu
            $liDivider = "Choisissez une action";
            $linkEditTask = $this->_html->newA(array("href" => $this->_editTaskPage . "?id=" . $task->id, "data-transition" => "turn"), "Editer");
            $linkDeleteTask = $this->_html->newA(array("href" => $this->indexPage . "?action=deleteTask&id=" . $task->id, "data-transition" => "turn"), "Supprimer");
            $linkDoneTask = $this->_html->newA(array("href" => $this->indexPage . "?action=doneTask&id=" . $task->id, "data-transition" => "turn"), "Marquer comme fait");

            $liContent = array($liDivider, $linkEditTask, $linkDeleteTask, $linkDoneTask);
            $liMenu = $this->_html->newLi(array("data-role" => "list-divider"), $liContent, 1);
            $modelContextualMenu = new ModelContextualMenu();
            $modelContextualMenu->liMenu = $liMenu;
            $modelContextualMenu->idTag = "contextualMenu_" . $task->id;

            $contextualMenu = $this->contextualMenu($modelContextualMenu);
            $linkTaskContextualMenu = $this->_html->newA(array("href" => "#" . $modelContextualMenu->idTag,
                                                     "data-split-icon" => "gear",
                                                     "data-rel" => "popup",
                                                     "data-transition" => "turn",
                                                     "class" => "ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-gear ui-btn-icon-left ui-btn-a"),
                                               "Options");

            $liContent = $linkSeeTask . $linkTaskContextualMenu . $contextualMenu;

            $list[] =  $liContent;
        }

        $lis = $this->_html->newLi(array(), $list);
        $ul = $this->_html->newUl(array("data-role" => "listview", "data-split-icon" => "check", "data-inset" => "true"), $lis);

        return $ul;
    }

    public function signUpPage(){
        $name = $this->_html->newFormInput((array("type" => "text", "name" => "name", "placeholder" => "Nom")));
        $firstName = $this->_html->newFormInput(array("type" => "text", "name" => "firstname", "placeholder" => "Prénom"));
        $pseudo = $this->_html->newFormInput(array("type" => "text", "name" => "pseudo", "placeholder" => "Pseudo"));
        $email = $this->_html->newFormInput(array("type" => "text", "name" => "email", "placeholder" => "Adresse e-mail"));
        $password = $this->_html->newFormInput(array("type" => "password", "name" => "password", "placeholder" => "Mot de passe"));

        $action = $this->_html->newFormInput(array("type" => "hidden", "name" => "action", "value" => "createUser"));
        $creationDate = $this->_html->newFormInput(array("type" => "hidden", "name" => "creaction_date", "value" => time()));
        $active = $this->_html->newFormInput(array("type" => "hidden", "name" => "active", "value" => 0));

        $submit = $this->_html->newFormInput(array("type" => "submit", "value" => "Valider"));

        $formLi = $this->_html->newLi(array("class" => "ui-field-contain"), array($name, $firstName, $pseudo, $email, $password, $submit));
        $formUl = $this->_html->newUl(array("data-role" => "listview",
                                            "data-inset" => "true"),
                                      $formLi
        );

        $hiddenInputs = $action . $creationDate . $active;

        $formSignUp = $this->_html->newForm(array("action" => $this->_signUpPage, "method" => "post", "id" => "signUp"), $formUl . $hiddenInputs);
        $title = $this->_html->newH(array(), 1, "Création de compte");
        $uiContent = $title . $formSignUp;
        $signUpPage = $this->_header . $uiContent . $this->_footer;

        return $signUpPage;
    }

    public function signInPage(){
        $identifiant = $this->_html->newFormInput(array("type" => "text", "name" => "email"), "Identifiant : ");
        $password = $this->_html->newFormInput(array("type" => "password", "name" => "password"), "Mot de passe : ");
        $hiddenPost = $this->_html->newFormInput(array("type" => "hidden", "name" => "posted", "value" => 1));

        $hiddenTime = $this->_html->newFormInput(array("type" => "hidden", "name" => "last_connexion", "value" => time()));
        $submit = $this->_html->newFormInput(array("type" => "submit", "value" => "Se connecter"));
        $formContent = $identifiant . $password . $hiddenTime . $hiddenPost . $submit;

        $fieldContain = $this->_html->newDiv(array("data-role" => "fieldcontain"), $formContent);
        $fieldset = $this->_html->newFieldset(array(), $fieldContain);

        $formProps = array("action" => $this->indexPage, "method" => "post", "id" => "connexion", "data-transition" => "turn", "data-direction" => "reverse");
        $signInForm = $this->_html->newForm($formProps, $fieldset);
        $title = $this->_html->newH(array(), 1, "Connexion");

        $signUpBtn = $this->_html->newButton(array(), "Créer un compte");
        $signUpLink = $this->_html->newA(array("href" => $this->_signUpPage, "data-transition" => "turn"), $signUpBtn);

        $uiContent = $title . "Connectez vous et accédez au service<br><br>" . $signInForm . $signUpLink;
        $uiContent = $this->_html->newDiv(array("data-role" => "ui-content"), $uiContent);

        $signInPage = $this->_header . $uiContent . $this->_footer;
        $signInPage = $this->_html->newDiv(array("data-role" => "page", "id" => "signIn"), $signInPage);

        return $signInPage;
    }

    public function seeTaskPage($taskId){
        $task = $this->_ajax->getTask($taskId);

        $name = $this->_html->newH(array(), 2,$task->name);
        $basicInfos = "Date limite : " . $task->due_date . "<br>
                       Importance : " . $this->_getTaskImportance($task->importance) . "<br>
                       Date de création : " . date("d/M/Y", $task->creation_date);
        $basicInfos = $this->_html->newP(array(), $basicInfos);
        $description = $this->_html->newP(array(), $task->description);

        $editBtn = $this->_html->newButton(array("data-icon" => "edit"), "Editer");
        $editLink = $this->_html->newA(array("href" => $this->_editTaskPage . "?id=" . $task->id,
                                             "data-transition" => "turn",
                                             "data-direction" => "reverse"),
                                       $editBtn
        );

        $deleteBtn = $this->_html->newButton(array("data-icon" => "delete", "data-transition" => "turn", "data-direction" => ""), "Supprimer");
        $deleteLink = $this->_html->newA(array("href" => "#confirmDelete" . $task->id,
                                               "data-rel" => "popup",
                                               "data-position-to" => "window",
                                               "data-transition" => "pop",
                                               /*"class" => "ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-delete ui-btn-icon-left ui-btn-b"*/),
                                        $deleteBtn
        );

        $doneBtn = $this->_html->newButton(array("data-icon" => "check"), "Fait");
        $doneLink = $this->_html->newA(array("href" => $this->indexPage . "?action=doneTask&id=" . $task->id,
                                             "data-transition" => "turn",
                                             "data-direction" => "reverse"),
                                      $doneBtn
        );

        $modelDoubleChoiceBox = new ModelDoubleChoiceBox();
        $modelDoubleChoiceBox->title = "Supprimer '" .$task->name."'?";
        $modelDoubleChoiceBox->idTag = "confirmDelete" . $task->id;
        $modelDoubleChoiceBox->mainMessage = "Etes-vous certain de vouloir supprimer la tâche '" . $task->name . "'?";
        $modelDoubleChoiceBox->subMessage = "Toute suppression est irréversible.";
        $modelDoubleChoiceBox->firstOption = $this->_html->newA(array("href" => "#",
                "class" => "ui-btn ui-cornet-all ui-shadow ui-btn-inline ui-btn-b",
                "data-rel" => "back"),
            "Annuler"
        );
        $modelDoubleChoiceBox->secondOption = $this->_html->newA(array("href" => $this->indexPage ."?action=deleteTask?id=" . $task->id,
                "data-transition" => "turn",
                "class" => "ui-btn ui-cornet-all ui-shadow ui-btn-inline ui-btn-b",
                "data-rel" => "back"),
            "Confirmer"
        );

        $doubleChoiceBox = $this->doubleChoiceBox($modelDoubleChoiceBox);

        $uiContent = $name . $basicInfos . $description . $editLink . $deleteLink . $doubleChoiceBox . $doneLink ;
        $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $uiContent);

        $pageContent = $this->_header . $uiContent . $this->_footer;
        $taskPage = $this->_html->newDiv(array("data-role" => "page"), $pageContent);

        return $taskPage;
    }

    public function createTaskPage(){
        $name = $this->_html->newFormInput(array("type" => "text","name" => "name", "placeholder" => "Nom"));
        $description = $this->_html->newTextarea(array("name" => "description", "placeholder" => "Description"), null);

        $arrayImportance = $this->_getTaskImportance();
        array_unshift($arrayImportance, "Choix importance");
        $choiceImportance = $this->_html->newFormOption(array("data-placeholder" => "true"), $arrayImportance, 1);
        $selectImportance = $this->_html->newFormSelect(array("name" => "importance", "id" => "selectImportance", "data-native-menu" => "false"), $choiceImportance);
        $importance = $this->_html->newDiv(array("class" => "ui-field-contain"), $selectImportance);

        $dueDate = $this->_html->newFormInput(array("type" => "text", "data-role" => "date"));
        $status = $this->_html->newFormInput(array("type" => "hidden", "name" => "status", "value" => 0));
        $id_task_creator = $this->_html->newFormInput(array("type" => "hidden", "name" => "id_task_creator", "value" => $this->_user->id));
        $creationDate = $this->_html->newFormInput(array("type" => "hidden", "name" => "creation_date", "value" => time()));
        $submit = $this->_html->newFormInput(array("type" => "submit", "value" => "Creer"));

        $formContent = $name . $description . $importance . $dueDate . $status . $id_task_creator . $creationDate . $submit;

        $formProps = array("action" => $this->indexPage . "?action=createTask", "method" => "post", "id" => "createTask", "data-transition" => "turn");
        $formCreate = $this->_html->newForm($formProps, $formContent);

        $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $formCreate);
        $pageContent = $this->_header . $uiContent . $this->_footer;
        $createTaskPage = $this->_html->newDiv(array("data-role" => "page"), $pageContent);

        return $createTaskPage;
    }

    public function editTaskPage($taskId){
        $task = $this->_ajax->getTask($taskId);
        $task->due_date = $this->_helper->datePickerToTime($task->due_date, true);

        $name = $this->_html->newFormInput(array("type" => "text", "name" => "name", "value" => $task->name), "Nom : ");
        $description = $this->_html->newTextarea(array("name" => "description"), $task->description);

        $arrayImportance = $this->_getTaskImportance();
        array_unshift($arrayImportance, "Choix importance");
        $choiceImportance = $this->_html->newFormOption(array("data-placeholder" => "true"), $arrayImportance);
        $selectImportance = $this->_html->newFormSelect(array("name" => "importance", "id" => "selectEditImportance", "data-native-menu" => "false"), $choiceImportance);
        $importance = $this->_html->newDiv(array("class" => "ui-field-contain"), $selectImportance);

        $dueDate = $this->_html->newFormInput(array("type" => "text", "data-role" => "date", "value" => $task->due_date));
        $id = $this->_html->newFormInput(array("type" => "hidden", "name" => "id", "value" => $task->id));
        $status = $this->_html->newFormInput(array("type" => "hidden", "name" => "status", "value" => $task->status));
        $idTaskCreator = $this->_html->newFormInput(array("type" => "hidden", "name" => "id_task_creator", "value" => $task->id_task_creator));
        $creationDate = $this->_html->newFormInput(array("type" => "hidden", "name" => "creation_date", "value" => time()));
        $submit = $this->_html->newFormInput(array("type" => "submit", "value" => "Editer"));

        $formContent = $name . $description . $importance . $dueDate . $status . $idTaskCreator . $id . $creationDate . $submit;

        $formProps = array("action" => $this->indexPage  ."?action=editTask", "method" => "post", "id" => "editTask", "data-transition" => "turn");
        $formEdit = $this->_html->newForm($formProps, $formContent);

        $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $formEdit);
        $pageContent = $this->_header . $uiContent . $this->_footer;
        $editTaskPage = $this->_html->newDiv(array("data-role" => "page"), $pageContent);

        return $editTaskPage;
    }

    public function contactsPage(){
        $title = $this->_html->newH(array(), 1, "Contacts");

        $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $title);
        $contactsPage = $this->_header . $uiContent . $this->_footer;

        return $contactsPage;
    }

    public function settingPage(){
        $title = $this->_html->newH(array(), 1, "Parametres");

        $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $title);
        $settingsPage = $this->_header . $uiContent . $this->_footer;

        return $settingsPage;
    }

    /**
     * @return string $leftMenu
     */
    public function getLeftMenu(){
        //*****************************************SEARCH FORM************************************************
        $searchInput = $this->_html->newFormInput(array("type" => "text", "name" => "search"));
        $searchSubmit = $this->_html->newFormInput(array("type" => "submit", "data-icon" => "search", "value" => "Recherche"));
        $searchContent = $searchInput . $searchSubmit;
        $formSearch = $this->_html->newForm(array("action" => $this->indexPage . "?action=search",
                                                  "method" => "post",
                                                  "id" => "search"),
                                            $searchContent
        );

        //*****************************************IMPORTANCE AND CHRONOLOGY FILTER FORM************************************************
        $filterImportanceArray = array(
            0 => "Filtre par importance",
            3 => "Importance élevée seulement",
            2 => "Importance moyenne seulement",
            1 => "Importance basse seulement"
        );

        $filterChronologyArray = array(
            0 => "Filtre par chronologie",
            5 => "Partant de la plus récente créaction",
            4 => "Partant de la plus ancienne créaction",
            7 => "Partant de la plus récente date limite",
            6 => "Partant de la plus ancienne date limite",
            9 => "Partant de la plus récente résolution",
            8 => "Partant de la plus ancienne résolution"
        );

        $filterImportanceOptions = $this->_html->newFormOption(array("data-placeholder" => "true"), $filterImportanceArray, 1);
        $filterImportanceSelect = $this->_html->newFormSelect(array("name" => "filterImportance", "id" => "selectFilterImportance", "data-native-menu" => "false"), $filterImportanceOptions);
        $filterImportance = $this->_html->newDiv(array("class" => "ui-field-contain"), $filterImportanceSelect);

        $filterChronologyOptions = $this->_html->newFormOption(array("data-placeholder" => "true"), $filterChronologyArray, 1);
        $filterChronologySelect = $this->_html->newFormSelect(array("name" => "filterChronology", "id" => "selectFilterChronology", "data-native-menu" => "false"), $filterChronologyOptions);
        $filterChronology = $this->_html->newDiv(array("class" => "ui-field-contain"), $filterChronologySelect);


        //**************************************************STATUS FILTER FORM***************************************************

        $filterStatusOption = $this->_html->newFormOption(array(), array("A faire", "Fait"));
        $filterStatusSelect = $this->_html->newFormSelect(array("id" => "flip-select",
                                                                "name" => "filterStatus",
                                                                "data-role" => "flipswitch"),
                                                          $filterStatusOption
        );
        $formSelect = $filterImportance . $filterChronology . $filterStatusSelect;

        //TODO : Ajouter un form switch pour résolu - non résolu avec pour name "filterStatus"

        $filterSubmit = $this->_html->newFormInput(array("type" => "submit", "data-icon" => "bullets" , "value" => "Filtrer"));
        $filterContent = $formSelect . $filterSubmit;
        $formFilters = $this->_html->newForm(array("action" => $this->indexPage . "?action=filter",
                                                   "method" => "post",
                                                   "id" => "filter"),
                                             $filterContent
        );

        $panelContent = $formSearch . $formFilters;

        $modelPanel = new ModelPanel();
        $modelPanel->dataDisplay = "reveal";
        $modelPanel->dataPosition = "left";
        $modelPanel->panelContent = $panelContent;

        $leftMenu = $this->getPanelMenu($modelPanel);

        return $leftMenu;

        /*HOW TO IMPLEMENT THE PANEL IN A PAGE
            <div data-role="page">

            <div data-role="panel" id="mypanel">
                <!-- panel content goes here (data-position = "left" / data-display="reveal") -->
            </div><!-- /panel -->

            <!-- header -->
            <!-- content -->
            <!-- footer -->

            </div><!-- page -->
        */
    }
    //************************************************************FOR MOBILE HELPER***********************************//
    /**
     * @param ModelPanel $modelPanel
     * @return string $panel
     */
    public function getPanelMenu(ModelPanel $modelPanel){
        $panel = $this->_html->newDiv(array("data-role" => "panel",
                                            "id" => "leftMenu",
                                            "data-position" => $modelPanel->dataPosition,
                                            "data-display" => $modelPanel->dataDisplay),
                                      $modelPanel->panelContent
        );

        return $panel;
    }

    public function dialogBox($message, $idTag = null){
        $idTag = (!is_null($idTag))?$idTag:"dialogBox";
        $paragMessage = $this->_html->newP(array(), $message);
        $popUp = $this->_html->newDiv(array("data-role" => "popup", "id" => $idTag), $paragMessage);

        return $popUp;
        /*
            <a href="#popupBasic" data-rel="popup" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-transition="pop">Basic Popup</a>
            <div data-role="popup" id="popupBasic">
                <p>This is a completely basic popup, no options set.</p>
            </div>
        */
    }

    public function doubleChoiceBox(ModelDoubleChoiceBox $boxContent){
        $headerTitle = $this->_html->newH(array(), 2, $boxContent->title);
        $header = $this->_html->newDiv(array("data-role" => "header", "data-theme" => "a"), $headerTitle);

        $mainMessage = $this->_html->newH(array("class" => "ui-title"), 3, $boxContent->mainMessage);
        $subMessage = $this->_html->newP(array(), $boxContent->subMessage);

        $uiContent = $mainMessage . $subMessage . $boxContent->firstOption . $boxContent->secondOption;
        $uiContent = $this->_html->newDiv(array("role" => "main", "class" => "ui-content"), $uiContent);

        $confirmContent = $header . $uiContent;
        $confirmPopUp = $this->_html->newDiv(array("data-role" => "popup",
                                                   "id" => $boxContent->idTag,
                                                   "data-overlay-theme" => "b",
                                                   "data-theme" => "b",
                                                   "data-dismissible" => "false",
                                                   "style" => "max-width:400px;"),
                                             $confirmContent
        );

        return $confirmPopUp;
    }

    public function contextualMenu(ModelContextualMenu $modelContextualMenu){
        $listMenu = $this->_html->newUl(array("data-role" => "listview", "data-inset" => "true", "style" => "min-width:210px;"), $modelContextualMenu->liMenu);
        $contextualMenu = $this->_html->newDiv(array("data-role" => "popup",
                                                     "id" => $modelContextualMenu->idTag,
                                                     "data-theme" => "b"),
                                               $listMenu
        );

        return $contextualMenu;
    }
}