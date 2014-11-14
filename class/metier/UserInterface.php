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
use Model\ModelTask;
use Model\ModelUser;

class UserInterface {
    private $_ajax;
    private $_helper;
    private $_html;
    private $_head;
    private $_header;
    private $_footer;
    private $_home;

    private $_indexPage;
    private $_contactsPage;
    private $_settingsPage;

    private $_createTaskPage;
    private $_seeTaskPage;
    private $_editTaskPage;
    private $_doneTaskPage;

    private $_signInPage;
    private $_signUpPage;
    private $_signOutPage;

    public function __CONSTRUCT(ModelUser $user){
        $this->_ajax = new Ajax($user);
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
        $this->_indexPage = "index.php";
        $this->_contactsPage = "contacts.php";
        $this->_settingsPage = "settings.php";

        $this->_createTaskPage = "createTask.php";
        $this->_seeTaskPage = "seeTask.php";
        $this->_editTaskPage = "editTask.php";
        $this->_doneTaskPage = "doneTask.php";

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
        $this->_head .= $this->_html->newScriptTag(array("type" => "text/javascript", "src" => "js/jquery.min.js"));

        return $this->_head;
    }

    public function homePage(){
        if(is_null($this->_home)){
            $tasks = $this->_ajax->getTasks();
            $collapsibleTasks = null;

            foreach($tasks as $task){
                $collapsibleTasks .= $this->getCollapsibleTask($task);
            }

            $btnNewTask = $this->_html->newButton(array("data-icon" => "plus"), "Nouvelle tache");
            $linkNewTask = $this->_html->newA(array("href" => "#new_task"), $btnNewTask);
            $ui = $linkNewTask . $collapsibleTasks;

            $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $ui);

            $page = $this->_header . $uiContent . $this->_footer;

            $this->_home = $this->_html->newDiv(array("data-role" => "page", "id" => "home"), $page);

            return $this->_home;
        }
        else{
            return $this->_home;
        }

    }

    public function  getCollapsibleTask(ModelTask $task){
        $seeSubmit = $this->_html->newInput(array("type" => "submit", "data-icon" => "eye", "value" => "Voir"));
        $editSubmit = $this->_html->newInput(array("type" => "submit", "data-icon" => "edit", "value" => "Editer"));
        $doneSubmit = $this->_html->newInput(array("type" => "submit", "data-icon" => "check", "value" => "Marquer comme fait"));

        $see = $this->_html->newForm(array("action" => $this->_seeTaskPage . "?taskId=" . $task->id,
                                           "method" => "post",
                                           "id" => "see" . $task->id,
                                           "data-transition" => "turn",
                                           "data-direction" => "reverse"),
                                     $seeSubmit);
        $edit = $this->_html->newForm(array("action" => $this->_editTaskPage . "?taskId=" . $task->id,
                                            "method" => "post",
                                            "id" => "edit" . $task->id,
                                            "data-transition" => "turn",
                                            "data-direction" => "reverse"),
                                      $editSubmit);
        $done = $this->_html->newForm(array("action" => $this->_doneTaskPage . "?taskId=" . $task->id,
                                            "method" => "post",
                                            "id" => "done" . $task->id,
                                            "data-transition" => "turn",
                                            "data-direction" => "reverse"),
                                      $doneSubmit);

        $name = $this->_html->newH(array(), 1, $task->name);
        $basicInfos = "Date limite : " . $this->_helper->datePickerToTime($task->due_date, true) ."<br>
                       Importance : " . $task->importance;
        $basicInfos = $this->_html->newP(array(), $basicInfos);

        $content = $name . $basicInfos . $see . $edit . $done;

        $formatedTask = $this->_html->newDiv(array("data-role" => "collapsible"), $content);

        return $formatedTask;
    }

    public function getTaskList(){
        $tasks = $this->_ajax->getTasks();
        $list = null;

        foreach($tasks as $task){
            $img = null;
            $title = $this->_html->newH(array(), 2, $task->name);
            $basicInfosContent = "Importance : " . $task->importance ."<br> Date limite : " . $task->due_date;
            $basicInfos = $this->_html->newP(array(), $basicInfosContent);
            $linkContent = $img . $title . $basicInfos;
            $linkSeeTask = $this->_html->newA(array("href" => "seeTask?taskId=" . $task->id, "data-transition" => "turn", "data-direction" => "reverse"), $linkContent);

//<a href="#purchase" data-rel="popup" data-position-to="window" data-transition="pop">Purchase album</a>

            $linkDeleteTask = $this->_html->newA(array("href" => "deleteTask?taskId=" . $task->id,
                                                       "data-transition" => "turn",
                                                       "data-direction" => "reverse"),
                                                       "Editer la tache");
            $linkEditTask = $this->_html->newA(array("href" => "doneTask?taskId=" . $task->id, "data-transition" => "turn", "data-direction" => "reverse"), "Editer la tache");
            $linkDoneTask = $this->_html->newA(array("href" => "doneTask?taskId=" . $task->id, "data-transition" => "turn", "data-direction" => "reverse"), "Marquer comme faite");


            $liContent = ;
            $li = ;
            $list .= ;
        }
    }

    public function signInPage(){
        $identifiant = $this->_html->newInput(array("type" => "text", "name" => "email"), "Identifiant : ");
        $password = $this->_html->newInput(array("type" => "password", "name" => "password"), "Mot de passe : ");
        $hiddenPost = $this->_html->newInput(array("type" => "hidden", "name" => "posted", "value" => 1));

        $hiddenTime = $this->_html->newInput(array("type" => "hidden", "name" => "last_connexion", "value" => time()));
        $submit = $this->_html->newInput(array("type" => "submit", "value" => "Se connecter"));
        $formContent = $identifiant . $password . $hiddenTime . $hiddenPost . $submit;

        $fieldContain = $this->_html->newDiv(array("data-role" => "fieldcontain"), $formContent);
        $fieldset = $this->_html->newFieldset(array(), $fieldContain);

        $formProps = array("action" => $this->_indexPage, "method" => "post", "id" => "connexion", "data-transition" => "turn", "data-direction" => "reverse");
        $signInForm = $this->_html->newForm($formProps, $fieldset);
        $title = $this->_html->newH(array(), 1, "Connexion");

        $uiContent = $title . "Connectez vous et accédez au service<br><br>" . $signInForm;
        $uiContent = $this->_html->newDiv(array("data-role" => "ui-content"), $uiContent);

        $signInPage = $this->_header . $uiContent . $this->_footer;
        $signInPage = $this->_html->newDiv(array("data-role" => "page", "id" => "signIn"), $signInPage);

        return $signInPage;
    }

    public function seeTaskPage($taskId){
        $task = $this->_ajax->getTask($taskId);

        $name = $this->_html->newH(array(), 2,$task->name);
        $basicInfos = "Date limite : " . $task->due_date . "<br>
                       Importance : " . $task->importance;
        $basicInfos = $this->_html->newP(array(), $basicInfos);
        $description = $this->_html->newP(array(), $task->description);
        $btnFait = $this->_html->newButton(array("data-icon" => "check", "id" => "done"), "Fait");
        $uiContent = $name . $basicInfos . $description . $btnFait;

        $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $uiContent);

        $pageContent = $this->_header . $uiContent . $this->_footer;
        $taskPage = $this->_html->newDiv(array("data-role" => "page"), $pageContent);

        return $taskPage;
    }

    public function createTaskPage(){
        $name = $this->_html->newInput(array("type" => "text","name" => "name"), "Nom : ");
        $description = $this->_html->newTextarea(array("name" => "description"), "Description : ");

        $optionBasse = $this->_html->newFormOption(array(), "Basse");
        $optionMoyenne = $this->_html->newFormOption(array(), "Moyenne");
        $optionHaute = $this->_html->newFormOption(array(), "Haute");
        $choiceImportance = $optionBasse . $optionMoyenne . $optionHaute;

        $selectImportance = $this->_html->newFormSelect(array("name" => "importance"), $choiceImportance);
        $dueDate = $this->_html->newInput(array("type" => "text", "data-role" => "date"));
        $creationDate = $this->_html->newInput(array("type" => "hidden", "name" => "creation_date", "value" => time()));
        $submit = $this->_html->newInput(array("type" => "submit", "value" => time()));

        $formContent = $name . $description . $selectImportance . $dueDate . $creationDate . $submit;

        $formProps = array("action" => $this->_indexPage . "?action=createTask", "method" => "post", "id" => "createTask", "data-transition" => "turn");
        $formCreate = $this->_html->newForm($formProps, $formContent);

        $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $formCreate);
        $pageContent = $this->_header . $uiContent . $this->_footer;
        $createTaskPage = $this->_html->newDiv(array("data-role" => "page"), $pageContent);

        return $createTaskPage;
    }

    public function editTaskPage($taskId){
        $task = $this->_ajax->getTask($taskId);
        $task->due_date = $this->_helper->datePickerToTime($task->due_date, true);

        $name = $this->_html->newInput(array("type" => "text", "name" => "name", "value" => $task->name), "Nom : ");
        $description = $this->_html->newTextarea(array("name" => "description", "value" => $task->description), "Description : ");

        $optionBasse = $this->_html->newFormOption(array(), "Basse");
        $optionMoyenne = $this->_html->newFormOption(array(), "Moyenne");
        $optionHaute = $this->_html->newFormOption(array(), "Haute");
        $choiceImportance = $optionBasse . $optionMoyenne . $optionHaute;

        $selectImportance = $this->_html->newFormSelect(array("name" => "importance"), $choiceImportance);
        $dueDate = $this->_html->newInput(array("type" => "text", "data-role" => "date", "value" => $task->due_date));
        $creationDate = $this->_html->newInput(array("type" => "hidden", "name" => "creation_date", "value" => time()));
        $submit = $this->_html->newInput(array("type" => "submit", "value" => "Editer"));

        $formContent = $name . $description . $selectImportance . $dueDate . $creationDate . $submit;

        $formProps = array("action" => $this->_indexPage . "?action=updateTask", "method" => "post", "id" => "createTask", "data-transition" => "turn");
        $formCreate = $this->_html->newForm($formProps, $formContent);

        $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $formCreate);
        $pageContent = $this->_header . $uiContent . $this->_footer;
        $createTaskPage = $this->_html->newDiv(array("data-role" => "page"), $pageContent);

        return $createTaskPage;
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
} 