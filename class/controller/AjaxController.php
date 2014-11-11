<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 18/10/14
 * Time: 02:29
 */

namespace Metier;

include_once(dirname(__FILE__) . "/Ajax.php");
include_once(dirname(__FILE__) . "/model/ModelTaskForce.php");

use Model\ModelTask;
use Model\ModelUser;

class AjaxController {
    public function __CONSTRUCT(ModelUser $user, $action){
        $ajax = new Ajax($user);

        switch($action){
            case "createTask":
                $task = new ModelTask();
                $task->hydrate($_POST);

                //TODO : Put these sql operations in a transaction
                if($ajax->createTask($task)){
                    if($ajax->createUserAndTask($user->id, $ajax->getLastInsertId())){
                        return true;
                    }
                    else{
                        $ajax->deleteTask($ajax->getLastInsertId());
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

                $ajax->updateTask($task);
                break;

            case "deleteTask":
                $id = (isset($_GET["id"]))?$_GET["id"]:0;
                $ajax->deleteTask($id);
                break;

            case "createUser":

                break;

            case "updateUser":
                //Todo : add an updateUser method
                break;

            case "deleteUser":
                //Todo : add a deleteUser method
                break;

            /*default:

                break;*/
        }
    }
} 