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
        if(isset($_POST["posted"]) && $_POST["posted"] == 1){
            $account = new Account();
            $userInfos = $account->signIn("users", array("email" => $_POST["email"], "password" => $_POST["password"]), true);
            $user = new ModelUser();
            $user->hydrate($userInfos);
            $_SESSION["user"] = serialize($user);
        }
        elseif(isset($_SESSION["user"])){
            $user = unserialize($_SESSION["user"]);
        }
        else{
            header("Location:signIn.php");
        }

        $this->_user = (isset($user) && $user instanceof ModelUser)?$user:null;
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
                    if($this->_ajax->createUserAndTask($this->_user->id, $this->_ajax->getLastInsertId())){
                        return true;
                    }
                    else{
                        $this->_ajax->deleteTask($this->_ajax->getLastInsertId());
                        return false;
                    }
                }
                else{
                    return false;
                }

                break;

            case "updateTask":
                $task = new ModelTask();
                $task->hydrate($_POST);

                $this->_ajax->updateTask($task);
                break;

            case "deleteTask":
                $id = (isset($_GET["id"]))?$_GET["id"]:0;
                $this->_ajax->deleteTask($id);
                break;

            case "createUser":

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
} 