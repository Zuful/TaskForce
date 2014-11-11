<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 11/11/14
 * Time: 10:40
 */

namespace Metier;

include_once(dirname(__FILE__) . "/../helper/Html.php");
include_once(dirname(__FILE__) . "/Ajax.php");

use Helper\Html;
use Model\ModelTask;
use Model\ModelUser;

class UserInterface {
    private $_ajax;
    private $_html;
    private $_head;
    private $_header;
    private $_footer;
    private $_home;

    private $_signInPage;
    private $_signUpPage;
    private $_signOutPage;

    public function __CONSTRUCT(ModelUser $user){
        $this->_ajax = new Ajax($user);
        $this->_html = new Html();

        //********************************************************Head*****************************************************************
        $this->_head .= $this->_html->newTitle("Home");
        $this->_head .= $this->_html->newMeta(array("charset" => "UTF-8"));
        $this->_head .= $this->_html->newLinkTag(array("rel" => "stylesheet", "href" => "css/jquery.mobile-1.4.5.min.css"));
        $this->_head .= $this->_html->newScriptTag(array("type" => "text/javascript", "src" => "js/jquery.min.js"));
        $this->_head .= $this->_html->newScriptTag(array("type" => "text/javascript", "src" => "js/jquery.mobile-1.4.5.min.js"));

        //********************************************************Header*****************************************************************
        $linkHome = $this->_html->newA(array("href" => "#home", "data-transition" => "turn", "data-icon" => "home"), "Home");
        $linkAccount = $this->_html->newA(array("href" => "#users", "data-transition" => "turn", "data-icon" => "user"), "Utilisateurs");
        $linkSettings = $this->_html->newA(array("href" => "#settings", "data-transition" => "turn", "data-icon" => "gear"), "Settings");

        $list = $this->_html->newLi(array(), array($linkHome, $linkAccount, $linkSettings));
        $nav = $this->_html->newUl(array(), $list);
        $navBar = $this->_html->newDiv(array("data-role" => "navbar"), $nav);

        $this->_header = $this->_html->newDiv(array("data-role" => "header"), $navBar);

        //********************************************************Footer*****************************************************************
        $this->_footer = $this->_html->newDiv(array("data-role" => "footer"), "<h2>&copy; Yamani ADAME 2014</h2>");

        //********************************************************Variables*****************************************************************
        $this->_signInPage = "index.php";
        $this->_signUpPage = "signUp.php";
        $this->_signOutPage = "signOut.php";
    }

    public function getHead(){
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
        $this->_ajax->getTasks();

        $btnSee = $this->_html->newButton(array("data-icon" => "eye"), "Voir");
        $btnEdit = $this->_html->newButton(array("data-icon" => "edit"), "Editer");
        $btnDone = $this->_html->newButton(array("data-icon" => "check"), "Marquer comme fait");

        $linkSee = $this->_html->newA(array("href" => "#see_task?taskId=" . $task->id, "data-transition" => "turn"), $btnSee);
        $linkEdit = $this->_html->newA(array("href" => "#edit_task?taskId=" . $task->id, "data-transition" => "turn"), $btnEdit);
        $linkDone = $this->_html->newA(array("href" => "#task_done?taskId=" . $task->id, "data-transition" => "turn"), $btnDone);

        $name = $this->_html->newH(array(), 1, $task->name);
        $basicInfos = "Date limite : " . $task->due_date ."<br>
                       Importance : " . $task->importance;
        $basicInfos = $this->_html->newP(array(), $basicInfos);

        $content = $name . $basicInfos . $linkSee . $linkEdit . $linkDone;

        $formatedTask = $this->_html->newDiv(array("data-role" => "collapsible"), $content);

        return $formatedTask;
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

        $formProps = array("action" => $this->_signInPage, "method" => "post", "id" => "connexion", "data-transition" => "turn", "data-direction" => "reverse");
        $signInForm = $this->_html->newForm($formProps, $fieldset);
        $title = $this->_html->newH(array(), 1, "Connexion");

        $uiContent = $title . "Connectez vous et accédez au service<br><br>" . $signInForm;
        $uiContent = $this->_html->newDiv(array("data-role" => "ui-content"), $uiContent);

        $signInPage = $this->_header . $uiContent . $this->_footer;
        $signInPage = $this->_html->newDiv(array("data-role" => "page", "id" => "signIn"), $signInPage);

        return $signInPage;
    }

    public function taskPage(ModelTask $task){
        $name = $this->_html->newH(array(), 2,$task->name);
        $basicInfos = "Date limite : " . $task->due_date . "<br>
                       Importance : " . $task->importance;
        $basicInfos = $this->_html->newP(array(), $basicInfos);
        $description = $this->_html->newP(array(), $task->description);
        $btnFait = $this->_html->newButton(array("data-icon" => "check", "id" => "done"), "Fait");
        $uiContent = $name . $basicInfos . $description . $btnFait;

        $uiContent = $this->_html->newDiv(array("data-role" => "main", "class" => "ui-content"), $uiContent);

        $pageContent = $this->_header . $uiContent . $this->_footer;
        $seeTaskPage = $this->_html->newDiv(array("data-role" => "page"), $pageContent);

    }
} 