<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 11/11/14
 * Time: 10:39
 */

namespace Controller;

include_once(dirname(__FILE__) . "/../../include/bdd.php");
include_once(dirname(__FILE__) . "/../metier/Ajax.php");
include_once(dirname(__FILE__) . "/../helper/Account.php");
include_once(dirname(__FILE__) . "/../model/ModelTaskForce.php");
include_once(dirname(__FILE__) . "/../metier/UserInterface.php");

use Metier\Ajax;
use Metier\UserInterface;
use Model\ModelUser;
use Model\ModelTask;
use Helper\Account;

class Controller {
    private $_ajax;
    private $_ui;
    private $_user;
    private $_isSignedIn;

    public function __CONSTRUCT(){
        if(isset($_POST["posted"]) && $_POST["posted"] == 1){//The user is signin in
            $account = new Account();
            $userInfos = $account->signIn("users", array("email" => $_POST["email"], "password" => $_POST["password"]), true);
            $this->_user = new ModelUser();
            $this->_user->hydrate($userInfos);

            if($this->_user->active == 0){
                return $this->userNotification("nonActive", $this->_user);
            }

            $_SESSION["user"] = serialize($this->_user);
        }
        elseif(isset($_SESSION["user"])){//The user is already signed in
            $this->_user = unserialize($_SESSION["user"]);
        }
        elseif(isset($_POST["action"]) && $_POST["action"] == "createUser"){//The user just created his account
            $this->_user = new ModelUser();
            $this->_user->hydrate($_POST);
        }
        else{
            header("Location:signIn.php");
        }

        $this->_user = (!is_null($this->_user) && $this->_user instanceof ModelUser)?$this->_user:null;
        $this->_ajax = new Ajax($this->_user);
        $this->_ui = new UserInterface($this->_user);
        $this->_isSignedIn = (is_null($this->_user->id))?false:true;
    }

    public function getUi(){
        return $this->_ui;
    }

    public function action($action){
        switch($action){
            case "createTask":
                $task = new ModelTask();
                $task->hydrate($_POST);

                //TODO : Put these sql operations in a transaction
                if($this->_ajax->createTask($task)){
                    $idJustCreatedTask = $this->_ajax->getLastInsertId();
                    if($this->_ajax->createUserAndTask($this->_user->id, $idJustCreatedTask)){
                        return true;
                    }
                    else{
                        $this->_ajax->deleteTask($idJustCreatedTask);
                        return false;
                    }
                }
                else{
                    return false;
                }

                break;

            case "editTask":
                if($this->_ajax->hasTaskRight()){
                    $task = new ModelTask();
                    $task->hydrate($_POST);

                    $this->_ajax->updateTask($task);
                }
                else{
                    header("Location : " . $this->_ui->indexPage);
                }

                break;

            case "deleteTask":
                if($this->_ajax->hasTaskRight()){

                    $id = (isset($_GET["id"]))?$_GET["id"]:0;
                    $this->_ajax->deleteTask($id);
                }
                else{
                    header("Location : " . $this->_ui->indexPage);
                }

                break;

            case "doneTask":
                if($this->_ajax->hasTaskRight(true)){
                    if(isset($_GET["id"])){//$_GET is checked because it is also possible to realize this operation through a link
                        $id = $_GET["id"];
                    }
                    else{
                        $id = 0;
                    }

                    $task = $this->_ajax->getTask($id);
                    $this->_ajax->doneTask($task);
                }

                break;

            case "createUser":
                $this->_ajax->createUser($this->_user);
                break;

            case "updateUser":
                //Todo : add an updateUser method
                break;

            case "deleteUser":
                //Todo : add a deleteUser method
                break;

            default:
                return false;
        }
        return false;
    }

    public function taskNotification($event, ModelTask $task){
        switch($event){
            case "createTask":
                $message = "Nouvelle tâche '" . $task->name . "' créée";

                break;

            case "editTask":
                $message = "Tâche '" . $task->name . "' éditée";
                break;

            case "deleteTask":
                $message = "Tâche '" . $task->name . "' supprimée";
                break;

            case "doneTask":
                $message = "Tâche '" . $task->name . "' marquée comme faite";
                break;

            case "createUser":
                $message = "Votre compte à bien été créé, un e-mail de confirmation viende vous être envoyé.
                            Vous devez maintenant confirmer votre compte en cliquant sur le lien que contient l'email.";
                break;

            case "updateUser":
                //Todo : add an updateUser method
                $message = "Votre compte à bien été modifié";
                break;

            case "deleteUser":
                $message = "Votre compte à bien été modifié";
                //Todo : add a deleteUser method
                break;

            default:
                return false;
        }
        return false;
    }

    public function userNotification($event, ModelUser $user){
        $message = null;

        switch($event){
            case "nonActive":
                $message = "Le compte utilisateur '" . $user->firstname . " " . $user->name . "' n'a pas encore été confirmé.
                            Pour confirmer un compte rendez-vous sur le mail envoyé à l'adresse indiquée lors de sa création
                            et cliquez sur le lien qui s'y trouve";
                break;

            default:
                return false;
        }

        return $message;
    }
} 