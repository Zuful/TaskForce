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
use Model\ModelDoubleChoiceBox;
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
            $taskList = $this->getTaskList();

            $btnNewTask = $this->_html->newButton(array("data-icon" => "plus"), "Nouvelle tache");
            $linkNewTask = $this->_html->newA(array("href" => $this->_createTaskPage,
                                                    "data-transition" => "turn",
                                                    "data-direction" => "reverse"),
                                              $btnNewTask);
            $ui = $linkNewTask . $taskList;

            $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $ui);

            $page = $this->_header . $uiContent . $this->_footer;

            $this->_home = $this->_html->newDiv(array("data-role" => "page", "id" => "home"), $page);

            return $this->_home;
        }
        else{
            return $this->_home;
        }
    }

    public function getTaskList(){
        $tasks = $this->_ajax->getTasks();
        $list = array();

        foreach($tasks as $task){
            $img = null;
            $title = $this->_html->newH(array(), 2, $task->name);
            $basicInfosContent = "Importance : " . $task->importance ."<br> Date limite : " . $task->due_date;
            $basicInfos = $this->_html->newP(array(), $basicInfosContent);
            $linkContent = $img . $title . $basicInfos;

            $linkSeeTask = $this->_html->newA(array("href" => $this->_seeTaskPage . "?taskId=" . $task->id,
                                                    "data-transition" => "turn",
                                                    "data-direction" => "reverse"),
                                              $linkContent);
            $linkDoneTask = $this->_html->newA(array("href" => $this->_indexPage . "?action=doneTask&id=" . $task->id, "data-split-icon" => "gear"), "Options");

            $liContent = $linkSeeTask . $linkDoneTask;

            $list[] =  $liContent;
        }

        $lis = $this->_html->newLi(array(), $list);
        $ul = $this->_html->newUl(array("data-role" => "listview", "data-split-icon" => "check", "data-inset" => "true"), $lis);

        return $ul;
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

        $formEditAction = $this->_editTaskPage . "?taskId=" . $task->id;
        $formEditSubmit = $this->_html->newFormInput(array("type" => "submit", "value" => "Editer", "data-icon" => "edit"));
        $formEdit = $this->_html->newForm(array("action" => $formEditAction,
                                                "method" => "post",
                                                "id" => $formEditAction,
                                                "data-transition" => "turn",
                                                "data-direction" => "reverse"),
                                          $formEditSubmit
        );

        $formTaskId = $this->_html->newFormInput(array("type" => "hidden", "value" => $task->id, "name" => "id"));

        $formDeleteAction = $this->_indexPage ."?action=deleteTask";
        $formDeleteSubmit = $this->_html->newFormInput(array("type" => "submit", "value" => "Supprimer", "data-icon" => "delete"));
        $formContent = $formTaskId . $formDeleteSubmit;
        $formDelete = $this->_html->newForm(array("action" => $formDeleteAction,
                                                  "method" => "post",
                                                  "id" => $formDeleteAction,
                                                  "data-transition" => "turn",
                                                  "data-direction" => "reverse"),
                                            $formContent
        );


        $modelDoubleChoiceBox = new ModelDoubleChoiceBox();
        $modelDoubleChoiceBox->title = "Supprimer '" .$task->name."'?";
        $modelDoubleChoiceBox->idTag = "deletePopup";
        $modelDoubleChoiceBox->mainMessage = "Etes-vous certain de vouloir effectuer cette opération?";
        $modelDoubleChoiceBox->subMessage = "Toute suppression est irréversible.";
        $modelDoubleChoiceBox->firstOption = $this->_html->newA(
            array("href" => "#",
                  "class" => "ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b"),
            "Annuler"
        );
        $modelDoubleChoiceBox->secondOption = $this->_html->newA(
            array("href" => "#",
                "class" => "ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b"),
            "Supprimer"
        );

        $doubleChoiceBox = $this->doubleChoiceBox($modelDoubleChoiceBox);
        $deleteBtn = $this->_html->newButton(array("data-icon" => "delete"), "Supprimer");
        $deleteLink = $this->_html->newA(array("href" => $modelDoubleChoiceBox->idTag,
                                               "data-rel" => "popup",
                                               "data-position-to" => "window",
                                               "data-transition" => "pop",
                                               "class" => "ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-delete ui-btn-icon-left ui-btn-b"),
                                         $deleteBtn
        );

        /*
<a href="#popupDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-delete ui-btn-icon-left ui-btn-b">Delete page...</a>
<div data-role="popup" id="popupDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:400px;">
    <div data-role="header" data-theme="a">
    <h1>Delete Page?</h1>
    </div>
    <div role="main" class="ui-content">
        <h3 class="ui-title">Are you sure you want to delete this page?</h3>
        <p>This action cannot be undone.</p>
        <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back">Cancel</a>
        <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back" data-transition="flow">Delete</a>
    </div>
</div>
*/

        $formDoneAction = $this->_indexPage . "?action=doneTask";
        $formDoneSubmit = $this->_html->newFormInput(array("type" => "submit", "value" => "Fait", "data-icon" => "check"));
        $formContent = $formTaskId . $formDoneSubmit;
        $formDone = $this->_html->newForm(array("action" => $formDoneAction,
                                                "method" => "post",
                                                "id" => $formDoneAction,
                                                "data-transition" => "turn",
                                                "data-direction" => "reverse"),
                                          $formContent
        );


        $uiContent = $name . $basicInfos . $description . $formEdit . $formDelete . $formDone ;
        $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $uiContent);

        $pageContent = $this->_header . $uiContent . $this->_footer;
        $taskPage = $this->_html->newDiv(array("data-role" => "page"), $pageContent);

        return $taskPage;
    }

    public function createTaskPage(){
        $name = $this->_html->newFormInput(array("type" => "text","name" => "name"), "Nom : ");
        $description = $this->_html->newTextarea(array("name" => "description"), "Description : ");

        $optionBasse = $this->_html->newFormOption(array(), "Basse");
        $optionMoyenne = $this->_html->newFormOption(array(), "Moyenne");
        $optionHaute = $this->_html->newFormOption(array(), "Haute");
        $choiceImportance = $optionBasse . $optionMoyenne . $optionHaute;

        $selectImportance = $this->_html->newFormSelect(array("name" => "importance"), $choiceImportance);
        $dueDate = $this->_html->newFormInput(array("type" => "text", "data-role" => "date"));
        $status = $this->_html->newFormInput(array("type" => "hidden", "name" => "status", "value" => 0));
        $creationDate = $this->_html->newFormInput(array("type" => "hidden", "name" => "creation_date", "value" => time()));
        $submit = $this->_html->newFormInput(array("type" => "submit", "value" => "Creer"));

        $formContent = $name . $description . $selectImportance . $dueDate . $status . $creationDate . $submit;

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

        $name = $this->_html->newFormInput(array("type" => "text", "name" => "name", "value" => $task->name), "Nom : ");
        $description = $this->_html->newTextarea(array("name" => "description", "value" => $task->description), "Description : ");

        $optionBasse = $this->_html->newFormOption(array(), "Basse");
        $optionMoyenne = $this->_html->newFormOption(array(), "Moyenne");
        $optionHaute = $this->_html->newFormOption(array(), "Haute");
        $choiceImportance = $optionBasse . $optionMoyenne . $optionHaute;

        $selectImportance = $this->_html->newFormSelect(array("name" => "importance"), $choiceImportance);
        $dueDate = $this->_html->newFormInput(array("type" => "text", "data-role" => "date", "value" => $task->due_date));
        $id = $this->_html->newFormInput(array("type" => "hidden", "name" => "id", "value" => $taskId));
        $creationDate = $this->_html->newFormInput(array("type" => "hidden", "name" => "creation_date", "value" => time()));
        $submit = $this->_html->newFormInput(array("type" => "submit", "value" => "Editer"));

        $formContent = $name . $description . $selectImportance . $dueDate . $id . $creationDate . $submit;

        $formProps = array("action" => $this->_indexPage  ."?action=editTask&taskId=" . $task->id, "method" => "post", "id" => "createTask", "data-transition" => "turn");
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

    public function doubleChoiceBox(ModelDoubleChoiceBox $boxContent){
        $headerTitle = $this->_html->newH(array(), 2, $boxContent->title);
        $header = $this->_html->newDiv(array("data-role" => "header", "data-theme" => "a"), $headerTitle);

        $mainMessage = $this->_html->newH(array(), 3, $boxContent->mainMessage);
        $subMessage = $this->_html->newP(array(), $boxContent->subMessage);
        $firstOption = $this->_html->newA(array("href" => "#",
                                                "class" => "ui-btn ui-cornet-all ui-shadow ui-btn-inline ui-btn-b",
                                                "data-rel" => "back"),
                                          $boxContent->firstOption
        );
        $secondOption = $this->_html->newA(array("href" => "#",
                                                 "class" => "ui-btn ui-cornet-all ui-shadow ui-btn-inline ui-btn-b",
                                                 "data-rel" => "back"),
                                           $boxContent->secondOption
        );
        $uiContent = $mainMessage . $subMessage . $firstOption . $secondOption;
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
}